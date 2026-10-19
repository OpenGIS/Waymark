// Deterministic capture specs for the Waymark readme screenshots.
//
// One spec per capture subject (hero `editor-demo` plus `screenshot-1..9`).
// Each navigates, settles the Map, asserts the UI it exercises, gates on zero
// console/page errors (excluding only the browser-chrome `/favicon.ico`
// request), waits for the capture target's geometry to stop reflowing, and
// writes a deterministic PNG to `.opencode/tmp/screenshots/`.
//
// These specs double as functional smoke coverage: they run in the normal
// `npm run test:e2e` suite. With `WAYMARK_SCREENSHOTS=1` they instead write
// quality-85 JPEGs to `.opencode/tmp/screenshots/canonical/`; the
// `screenshots`/`screenshots:check` npm scripts publish and verify those. This
// file never writes to `.wordpress-org/` itself.
"use strict";

const fs = require("node:fs");
const path = require("node:path");
const crypto = require("node:crypto");
const { test, expect, waitForLayoutStable } = require("./fixtures");

const ROOT = path.join(__dirname, "..", "..");
const SHOT_DIR = path.join(ROOT, ".opencode", "tmp", "screenshots");

// `WAYMARK_SCREENSHOTS=1` switches the captures to the canonical readme assets:
// JPEG (quality 85) written to a separate directory, from which the npm scripts
// publish them to `.wordpress-org/`. Normal runs keep the deterministic PNGs
// checked against the baseline manifest.
const CANONICAL = process.env.WAYMARK_SCREENSHOTS === "1";
const OUT_DIR = CANONICAL ? path.join(SHOT_DIR, "canonical") : SHOT_DIR;
const EXT = CANONICAL ? "jpg" : "png";
const MANIFEST_PATH = path.join(OUT_DIR, "manifest.json");

// Headed Chromium's browser UI requests /favicon.ico for the tab icon; the
// wp-env tests site serves no favicon, so it 404s. Exclude only this
// browser-chrome noise from the console gate.
const FAVICON_URL = /\/favicon\.ico$/;

// A seeded marker with an associated photo (see the demo GeoJSON).
const POPUP_MARKER_TITLE = "The Scarlet Ibis";

// ---------------------------------------------------------------------------
// Capture bookkeeping
// ---------------------------------------------------------------------------

/** @type {Array<{name: string, file: string, width: number, height: number, sha256: string}>} */
let captures = [];

/**
 * Read the pixel dimensions from a PNG's IHDR chunk.
 *
 * @param {Buffer} buffer
 * @returns {{ width: number, height: number }}
 */
function pngSize(buffer) {
  return { width: buffer.readUInt32BE(16), height: buffer.readUInt32BE(20) };
}

/**
 * Read the pixel dimensions from a JPEG's first SOF segment.
 *
 * @param {Buffer} buffer
 * @returns {{ width: number, height: number }}
 */
function jpegSize(buffer) {
  let offset = 2; // Skip the SOI marker.

  while (offset < buffer.length) {
    if (buffer[offset] !== 0xff) {
      offset += 1;
      continue;
    }

    const marker = buffer[offset + 1];

    // Standalone markers carry no length payload.
    if (
      marker === 0xd8 ||
      marker === 0xd9 ||
      (marker >= 0xd0 && marker <= 0xd7)
    ) {
      offset += 2;
      continue;
    }

    const length = buffer.readUInt16BE(offset + 2);

    // SOF markers (C0–CF) except DHT (C4), JPG (C8) and DAC (CC).
    if (
      marker >= 0xc0 &&
      marker <= 0xcf &&
      marker !== 0xc4 &&
      marker !== 0xc8 &&
      marker !== 0xcc
    ) {
      return {
        height: buffer.readUInt16BE(offset + 5),
        width: buffer.readUInt16BE(offset + 7),
      };
    }

    offset += 2 + length;
  }

  throw new Error("No JPEG SOF segment found");
}

/**
 * Read a capture's pixel dimensions for the current output format.
 *
 * @param {Buffer} buffer
 * @returns {{ width: number, height: number }}
 */
function imageSize(buffer) {
  return CANONICAL ? jpegSize(buffer) : pngSize(buffer);
}

/**
 * Merge the canonical JPEG options into a capture's screenshot options.
 *
 * @param {import("@playwright/test").PageScreenshotOptions} [options]
 * @returns {import("@playwright/test").PageScreenshotOptions}
 */
function screenshotOptions(options = {}) {
  return CANONICAL ? { ...options, type: "jpeg", quality: 85 } : options;
}

/**
 * Persist a capture to disk and record it in the manifest.
 *
 * @param {string} name
 * @param {Buffer} buffer PNG bytes
 */
function recordCapture(name, buffer) {
  fs.mkdirSync(OUT_DIR, { recursive: true });

  const file = path.join(OUT_DIR, `${name}.${EXT}`);
  fs.writeFileSync(file, buffer);

  const { width, height } = imageSize(buffer);
  captures = captures.filter((entry) => entry.name !== name);
  captures.push({
    name,
    file: path.relative(ROOT, file),
    width,
    height,
    sha256: crypto.createHash("sha256").update(buffer).digest("hex"),
  });

  fs.writeFileSync(MANIFEST_PATH, `${JSON.stringify(captures, null, 2)}\n`);
}

/**
 * Wait for the whole document's geometry to hold steady.
 *
 * @param {import("@playwright/test").Page} page
 */
async function settleDocumentLayout(page) {
  await waitForLayoutStable(() =>
    page.evaluate(() => ({
      width: document.documentElement.scrollWidth,
      height: document.documentElement.scrollHeight,
      bodyWidth: document.body.scrollWidth,
      bodyHeight: document.body.scrollHeight,
    })),
  );
}

/**
 * Rasterise a screenshot and record it, without a layout wait.
 *
 * @param {import("@playwright/test").Page} page
 * @param {string} name
 * @param {import("@playwright/test").PageScreenshotOptions} [options]
 */
async function screenshotPage(page, name, options = {}) {
  const buffer = await page.screenshot(screenshotOptions(options));
  recordCapture(name, buffer);
}

/**
 * Screenshot the viewport (or a page-coordinate clip) and record it.
 *
 * @param {import("@playwright/test").Page} page
 * @param {string} name
 * @param {import("@playwright/test").PageScreenshotOptions} [options]
 */
async function capturePage(page, name, options = {}) {
  await settleDocumentLayout(page);
  await screenshotPage(page, name, options);
}

/**
 * Screenshot a single element and record it.
 *
 * The element's bounding box is fractional (theme layout), and
 * `locator.screenshot()`'s handling of that fraction flips a handful of
 * anti-aliased pixels between runs. Capture an explicit integer page clip
 * (floor the top-left, ceil the bottom-right) instead — same framing, but
 * rasterised identically every run.
 *
 * @param {import("@playwright/test").Locator} locator
 * @param {string} name
 */
async function captureLocator(locator, name) {
  // The target's own box is the geometry that must settle: the editor meta box
  // grows as its admin bundle finishes wiring up, and a loaded machine can push
  // that growth past `settleMap`.
  await waitForLayoutStable(() =>
    locator.boundingBox().then((box) =>
      box
        ? {
            x: Math.round(box.x),
            y: Math.round(box.y),
            width: Math.round(box.width),
            height: Math.round(box.height),
          }
        : null,
    ),
  );

  const clip = await locator.evaluate((element) => {
    const box = element.getBoundingClientRect();
    const x = Math.max(0, Math.floor(box.left + window.scrollX));
    const y = Math.max(0, Math.floor(box.top + window.scrollY));
    const right = Math.ceil(box.right + window.scrollX);
    const bottom = Math.ceil(box.bottom + window.scrollY);
    return { x, y, width: right - x, height: bottom - y };
  });

  const buffer = await locator
    .page()
    .screenshot(screenshotOptions({ clip, fullPage: true }));
  recordCapture(name, buffer);
}

/**
 * Screenshot the front-end content column, cropping the theme's symmetric
 * whitespace around the centred blocks. Uses a full-page clip so tall content
 * is captured whole.
 *
 * `topPad` overrides the whitespace left above the first content block
 * (defaulting to the shared 20px pad). The default reaches into the theme's
 * own header text above the content, which slices a line of glyphs; pass 0 to
 * clip flush with the first content block instead.
 *
 * @param {import("@playwright/test").Page} page
 * @param {string} name
 * @param {{ topPad?: number }} [options]
 */
async function captureContentColumn(page, name, options = {}) {
  const topPad = options.topPad ?? 20;

  await settleDocumentLayout(page);

  const clip = await page.evaluate((topPad) => {
    const parent = document.querySelector(".wp-block-post-content");
    const boxes = [...parent.children]
      .map((element) => element.getBoundingClientRect())
      .filter((box) => box.width > 0 && box.height > 0);
    const left = Math.min(...boxes.map((box) => box.left + window.scrollX));
    const right = Math.max(...boxes.map((box) => box.right + window.scrollX));
    const top = Math.min(...boxes.map((box) => box.top + window.scrollY));
    const bottom = Math.max(...boxes.map((box) => box.bottom + window.scrollY));
    const pad = 20;
    const y = Math.max(0, top - topPad);
    return {
      x: Math.max(0, left - pad),
      y,
      width: right - left + pad * 2,
      height: Math.max(0, bottom + pad - y),
    };
  }, topPad);

  await screenshotPage(page, name, { clip, fullPage: true });
}

// ---------------------------------------------------------------------------
// Error gate
// ---------------------------------------------------------------------------

/**
 * Start collecting console and page errors. `favicon.ico` console noise is
 * ignored; everything else must be empty at teardown.
 *
 * @param {import("@playwright/test").Page} page
 */
function startErrorGate(page) {
  const consoleErrors = [];
  const pageErrors = [];

  page.on("console", (msg) => {
    if (msg.type() === "error" && !FAVICON_URL.test(msg.location().url)) {
      consoleErrors.push(`[${msg.type()}] ${msg.text()}`);
    }
  });
  page.on("pageerror", (err) => pageErrors.push(String(err)));

  return () => {
    expect(
      consoleErrors,
      `Console errors:\n${consoleErrors.join("\n")}`,
    ).toEqual([]);
    expect(pageErrors, `Page errors:\n${pageErrors.join("\n")}`).toEqual([]);
  };
}

// ---------------------------------------------------------------------------
// Map helpers
// ---------------------------------------------------------------------------

/**
 * Assert a Leaflet map has finished loading its tiles.
 *
 * @param {import("@playwright/test").Page} page
 */
async function expectTilesLoaded(page) {
  await expect
    .poll(() => page.locator("img.leaflet-tile-loaded").count(), {
      timeout: 30_000,
    })
    .toBeGreaterThan(0);
}

/**
 * Force every viewer Map on the page back to a deterministic data-bounds view.
 *
 * The leaflet-elevation control re-fits the Map to its track extent on resize
 * and when its track layer loads. That asynchronous re-fit races the capture,
 * so once the elevation chart has rendered, fit each Map to its GeoJSON bounds
 * so the capture is reproducible.
 *
 * @param {import("@playwright/test").Page} page
 */
async function settleViewerMaps(page) {
  await page
    .locator(".waymark-elevation-container svg")
    .first()
    .waitFor({ state: "visible", timeout: 20_000 })
    .catch(() => {});

  // Give any pending elevation resize work a chance to drain first.
  await page.waitForTimeout(750);

  await page.evaluate(() => {
    document.querySelectorAll(".waymark-map-container").forEach((element) => {
      const waymark = window.jQuery(element).data("Waymark");
      if (!waymark || !waymark.map || !waymark.map_data) {
        return;
      }

      // Detach the elevation control's resize re-fit (and any other resize
      // listener) so a later reflow cannot move the Map off the pinned view.
      waymark.map.off("resize");

      const bounds = waymark.map_data.getBounds();
      if (bounds && bounds.isValid()) {
        waymark.map.fitBounds(bounds);
      }
    });
  });
}

/**
 * Open a Map layer's popup by the feature title, without clicking the map.
 *
 * @param {import("@playwright/test").Page} page
 * @param {string} title
 * @returns {Promise<boolean>} whether a matching layer was found
 */
async function openPopupByTitle(page, title) {
  return page.evaluate((targetTitle) => {
    const waymark = window.Waymark;
    if (!waymark || !waymark.map_data) {
      return false;
    }

    let found = false;
    waymark.map_data.eachLayer((layer) => {
      const feature = layer.feature;
      if (
        feature &&
        feature.properties &&
        feature.properties.title === targetTitle &&
        typeof layer.openPopup === "function"
      ) {
        // Keep the pinned view: autoPan would shift the Map by a
        // font-timing-dependent amount and make the capture irreproducible.
        const popup = layer.getPopup && layer.getPopup();
        if (popup) {
          popup.options.autoPan = false;
        }
        layer.openPopup();
        found = true;
      }
    });

    return found;
  }, title);
}

// ---------------------------------------------------------------------------
// Admin editor session
// ---------------------------------------------------------------------------

/**
 * Log in and open the seeded Map in the classic editor, settled and ready.
 *
 * @param {import("@playwright/test").Page} page
 * @param {import("./fixtures").test} waymark fixture
 */
async function openEditor(page, waymark) {
  await waymark.login(page);
  await page.goto("/wp-admin/post.php?post=100&action=edit");
  await expect(page.locator("#waymark_map_meta")).toBeVisible();
  await page.locator(".waymark-is-editor").waitFor({ state: "visible" });
  await waymark.settleMap(page);
  await expectTilesLoaded(page);
}

test.beforeAll(() => {
  captures = [];
});

// ---------------------------------------------------------------------------
// Hero: editor-demo
// ---------------------------------------------------------------------------

test("hero editor-demo: tight Map Editor panel", async ({ page, waymark }) => {
  test.setTimeout(120_000);
  const assertNoErrors = startErrorGate(page);
  await openEditor(page, waymark);

  const mapPanel = page.locator(".waymark-is-editor");
  await expect(mapPanel.locator(".waymark-edit-toolbar")).toBeVisible();
  await expect(
    mapPanel.locator(".leaflet-marker-pane .waymark-marker"),
  ).toHaveCount(13);

  await captureLocator(mapPanel, "editor-demo");
  assertNoErrors();
});

// ---------------------------------------------------------------------------
// screenshot-1: editor with overlays
// ---------------------------------------------------------------------------

test("screenshot-1: Map Editor meta box with seeded overlays", async ({
  page,
  waymark,
}) => {
  test.setTimeout(120_000);
  const assertNoErrors = startErrorGate(page);
  await openEditor(page, waymark);

  const mapPanel = page.locator(".waymark-is-editor");
  await expect(
    mapPanel.locator(".leaflet-marker-pane .waymark-marker"),
  ).toHaveCount(13);
  // Seeded Lines and Shapes render as SVG paths in the overlay pane.
  expect(
    await mapPanel.locator(".leaflet-overlay-pane path").count(),
  ).toBeGreaterThan(0);

  await captureLocator(page.locator("#waymark_map_meta"), "screenshot-1");
  assertNoErrors();
});

// ---------------------------------------------------------------------------
// screenshot-3: the full classic editor (viewport)
// ---------------------------------------------------------------------------

test("screenshot-3: full classic editor framing", async ({ page, waymark }) => {
  test.setTimeout(120_000);
  const assertNoErrors = startErrorGate(page);
  await openEditor(page, waymark);

  await expect(page.locator("#submitdiv")).toBeVisible();
  await expect(page.locator("#waymark_collectiondiv")).toBeVisible();
  await expect(page.locator("#waymark_map_shortcode")).toBeVisible();
  await expect(page.locator("#waymark_map_export")).toBeVisible();
  await expect(page.locator("#waymark_map_shortcode input")).toHaveValue(
    /map_id="100"/,
  );
  await expect(
    page.locator("#waymark_map_export select[name=export_format] option"),
  ).toHaveCount(3);

  await page.evaluate(() => window.scrollTo(0, 0));
  await capturePage(page, "screenshot-3");
  assertNoErrors();
});

// ---------------------------------------------------------------------------
// screenshot-5: editor meta boxes (About / Details) + Featured Image
// ---------------------------------------------------------------------------

test("screenshot-5: seeded meta groups and Featured Image", async ({
  page,
  waymark,
}) => {
  test.setTimeout(120_000);
  const assertNoErrors = startErrorGate(page);
  await openEditor(page, waymark);

  await expect(page.locator("#waymark-parameter-group-about")).toBeVisible();
  await expect(page.locator("#waymark-parameter-group-details")).toBeVisible();
  await expect(page.locator("#postimagediv img")).toBeVisible();

  // The seeded meta groups (main column) and the Featured Image thumbnail
  // (side column) sit at different heights, so capture the whole editor page.
  await page.evaluate(() => window.scrollTo(0, 0));
  await capturePage(page, "screenshot-5", { fullPage: true });
  assertNoErrors();
});

// ---------------------------------------------------------------------------
// screenshot-2: front-end marker popup with photo
// ---------------------------------------------------------------------------

test("screenshot-2: marker popup with photo", async ({ page, waymark }) => {
  test.setTimeout(120_000);
  const assertNoErrors = startErrorGate(page);

  await page.goto(waymark.getMap("cape-scott-trail").permalink);
  await waymark.settleMap(page);
  await settleViewerMaps(page);
  await waymark.settleMap(page);

  const found = await openPopupByTitle(page, POPUP_MARKER_TITLE);
  expect(found, `No seeded marker titled "${POPUP_MARKER_TITLE}"`).toBe(true);

  const popup = page.locator(".leaflet-popup");
  await expect(popup).toBeVisible();
  await expect(popup).toContainText(POPUP_MARKER_TITLE);

  // The overlay photo is rendered as a background-image link, so wait for it
  // to be applied and then preload its bytes before capturing.
  const photoLink = popup.locator(".waymark-info-image_large_url a");
  await expect(photoLink).toBeVisible();
  await expect
    .poll(
      () => photoLink.evaluate((el) => getComputedStyle(el).backgroundImage),
      { timeout: 15_000 },
    )
    .toContain("url(");
  await photoLink.evaluate(async (el) => {
    const match = getComputedStyle(el).backgroundImage.match(
      /url\(["']?(.*?)["']?\)/,
    );
    if (!match) {
      return;
    }
    await new Promise((resolve) => {
      const img = new Image();
      img.onload = resolve;
      img.onerror = resolve;
      img.src = match[1];
    });
  });

  // Place the marker at a fixed point so the (autoPan-disabled) popup is fully
  // framed and the Map offset is reproducible.
  await page.evaluate((title) => {
    const waymark = window.Waymark;
    if (!waymark || !waymark.map || !waymark.map_data) {
      return;
    }

    let target = null;
    waymark.map_data.eachLayer((layer) => {
      if (
        layer.feature &&
        layer.feature.properties &&
        layer.feature.properties.title === title
      ) {
        target = layer;
      }
    });
    if (!target || typeof target.getLatLng !== "function") {
      return;
    }

    const size = waymark.map.getSize();
    const current = waymark.map.latLngToContainerPoint(target.getLatLng());
    const delta = current.clone();
    delta.x -= size.x / 2;
    delta.y -= size.y * 0.72;
    waymark.map.panBy(delta, { animate: false });
  }, POPUP_MARKER_TITLE);

  await waymark.settleMap(page, { closePopups: false });
  await expectTilesLoaded(page);

  await captureLocator(page.locator(".waymark-map-container"), "screenshot-2");
  assertNoErrors();
});

// ---------------------------------------------------------------------------
// screenshot-4: basemap switcher + overlay type filter
// ---------------------------------------------------------------------------

test("screenshot-4: basemap switcher and type filter", async ({
  page,
  waymark,
}) => {
  test.setTimeout(120_000);
  const assertNoErrors = startErrorGate(page);

  await page.goto(waymark.getMap("cape-scott-trail").permalink);
  await waymark.settleMap(page);
  await settleViewerMaps(page);
  await waymark.settleMap(page);

  const layersControl = page.locator(".leaflet-control-layers");
  const baseLayers = layersControl.locator(
    ".leaflet-control-layers-base label",
  );
  await expect(baseLayers).toHaveCount(2);
  // Leaflet keeps the control collapsed until hover, hiding its labels.
  await layersControl.evaluate((el) =>
    el.classList.add("leaflet-control-layers-expanded"),
  );
  await expect(layersControl).toContainText("Open Street Map");
  await expect(layersControl).toContainText("Open Topo Map");

  // A real interaction: switch to the Open Topo Map basemap.
  await layersControl
    .locator(".leaflet-control-layers-base label", { hasText: "Open Topo Map" })
    .click();
  await expect(layersControl.locator("input[type=radio]").nth(1)).toBeChecked();
  await waymark.settleMap(page);
  await expectTilesLoaded(page);

  // The overlay type filter lists the coloured marker/line/shape types
  // (6 marker types + 1 line type + 2 shape types).
  await expect(
    layersControl.locator(
      ".leaflet-control-layers-overlays .waymark-type-text",
    ),
  ).toHaveCount(9);

  // Leaflet only expands the control on hover, so force the expanded state for
  // the capture (mouse movement while scrolling would otherwise collapse it).
  // Release the overlay list's own height cap too: its 300px max-height scrolls
  // the last filter entries (the final "Green" line type) out of view.
  await page.addStyleTag({
    content:
      ".leaflet-control-layers .leaflet-control-layers-list{display:block !important}" +
      ".leaflet-control-layers .leaflet-control-layers-toggle{display:none !important}" +
      ".leaflet-control-layers-list{max-height:none !important;overflow:visible !important}",
  });

  // The fully expanded control no longer fits above the bottom gallery inside
  // the default 450px Map. Grow the Map container for this capture only so the
  // whole switcher and filter list clear the gallery with a little breathing
  // room, measuring the control and gallery rather than assuming an offset.
  const mapHeight = await page.evaluate(() => {
    const container = document
      .querySelector(".waymark-map-container")
      .getBoundingClientRect();
    const control = document
      .querySelector(".leaflet-control-layers")
      .getBoundingClientRect();
    const gallery = document.querySelector(".waymark-gallery-container");
    const galleryHeight = gallery ? gallery.getBoundingClientRect().height : 0;
    const controlBottom = control.bottom - container.top;
    return Math.ceil(controlBottom + galleryHeight + 24);
  });
  await page.addStyleTag({
    content: `.waymark-map-container{height:${mapHeight}px !important}`,
  });

  // Re-measure the enlarged Map and let it fetch the extra tile rows. Re-pin
  // the data-bounds view afterwards so the resize cannot leave a sub-pixel
  // offset that varies the capture between runs.
  await page.evaluate(() => {
    document.querySelectorAll(".waymark-map-container").forEach((element) => {
      const waymark = window.jQuery(element).data("Waymark");
      if (waymark && waymark.map) {
        waymark.map.invalidateSize();
      }
    });
  });
  await settleViewerMaps(page);
  await waymark.settleMap(page);
  await expectTilesLoaded(page);

  await captureLocator(page.locator(".waymark-map-container"), "screenshot-4");
  assertNoErrors();
});

// ---------------------------------------------------------------------------
// screenshot-6: Map Details page
// ---------------------------------------------------------------------------

test("screenshot-6: Map Details page", async ({ page, waymark }) => {
  test.setTimeout(120_000);
  const assertNoErrors = startErrorGate(page);

  await page.goto(waymark.getMap("cape-scott-trail").permalink);
  await waymark.settleMap(page);

  // Elevation chart renders via the stubbed local d3 build.
  await expect(page.locator(".waymark-elevation-container svg")).toBeVisible({
    timeout: 20_000,
  });
  // Expand the collapsed Details meta group so both groups are visible.
  await page
    .locator(".waymark-meta-group-details legend")
    .click()
    .catch(() => {});

  const content = page.locator(".wp-block-post-content");
  await expect(content.locator(".waymark-map-container")).toBeVisible();
  await expect(content.locator(".waymark-gallery-container")).toBeAttached();
  await expect(content.locator(".waymark-meta-export_data form")).toHaveCount(
    1,
  );
  await expect(content.locator(".waymark-meta-collection_list")).toContainText(
    "Vancouver Island",
  );
  await expect(content.locator(".waymark-meta-map_description")).toContainText(
    "Port Hardy",
  );

  // Pin the Map to a deterministic data-bounds view (the elevation control
  // otherwise re-fits to its near-degenerate track extent on resize).
  await settleViewerMaps(page);
  await waymark.settleMap(page);
  await expectTilesLoaded(page);

  await captureContentColumn(page, "screenshot-6");
  assertNoErrors();
});

// ---------------------------------------------------------------------------
// screenshot-7: shortcode in content
// ---------------------------------------------------------------------------

test("screenshot-7: shortcode rendered in page content", async ({
  page,
  waymark,
}) => {
  test.setTimeout(120_000);
  const assertNoErrors = startErrorGate(page);

  await page.goto(waymark.seed.page.url);
  await expect(page.locator(".wp-block-post-content")).toBeVisible();

  await expect(page.locator(".waymark-shortcode")).toHaveCount(2);
  await expect(page.locator(".waymark-header")).toHaveCount(2);
  await waymark.settleMap(page);
  await expectTilesLoaded(page);

  const content = page.locator(".wp-block-post-content");
  await expect(content).toContainText("Cape Scott Trail");
  await expect(content).toContainText("Vancouver Island");

  // Pin every Map to a deterministic data-bounds view (the elevation control
  // otherwise re-fits to its near-degenerate track extent on resize).
  await settleViewerMaps(page);
  await waymark.settleMap(page);
  await expectTilesLoaded(page);

  // Clip flush with the first content block (the "Cape Scott Trail" heading) so
  // the theme header text above it is not sliced in half.
  await captureContentColumn(page, "screenshot-7", { topPad: 0 });
  assertNoErrors();
});

// ---------------------------------------------------------------------------
// screenshot-8: Collections admin
// ---------------------------------------------------------------------------

test("screenshot-8: Collections admin table", async ({ page, waymark }) => {
  test.setTimeout(120_000);
  const assertNoErrors = startErrorGate(page);

  await waymark.login(page);
  await page.goto(
    "/wp-admin/edit-tags.php?taxonomy=waymark_collection&post_type=waymark_map",
  );
  await expect(page.locator("#the-list")).toBeVisible();

  const head = page.locator("thead");
  await expect(head).toContainText("Count");
  await expect(head).toContainText("Shortcode");

  const list = page.locator("#the-list");
  await expect(list).toContainText("Vancouver Island");
  await expect(list).toContainText("Backpacking");
  await expect(list).toContainText("Day Hikes");
  await expect(list.locator("input[type=text]").first()).toBeVisible();

  // Widen the Shortcode column's readonly inputs for this capture only: their
  // default 180px truncates the full value (e.g. [Waymark collection_id="2"]).
  await page.addStyleTag({
    content:
      "#the-list .column-shortcode input[type=text]{width:220px !important}",
  });
  await expect(list.locator("input[type=text]").first()).toHaveValue(
    '[Waymark collection_id="2"]',
  );

  await page.evaluate(() => window.scrollTo(0, 0));
  await capturePage(page, "screenshot-8");
  assertNoErrors();
});

// ---------------------------------------------------------------------------
// screenshot-9: Settings — Markers tab
// ---------------------------------------------------------------------------

test("screenshot-9: Settings Marker Types", async ({ page, waymark }) => {
  test.setTimeout(120_000);
  const assertNoErrors = startErrorGate(page);

  await waymark.login(page);
  await page.goto(waymark.settingsUrl);
  await expect(page.locator("#waymark-admin-container")).toBeVisible();

  const container = page.locator("#waymark-admin-container");
  await expect(container.locator("input[value=Beer]")).toBeVisible();
  await expect(
    container.locator(".waymark-settings-tab-markers"),
  ).toBeAttached();
  await expect(container).toContainText("Marker Types");

  await captureLocator(container, "screenshot-9");
  assertNoErrors();
});
