# Videos

This is a design and reference document for the screencasts published with the Waymark documentation. It records the current inventory, the constraints on any replacement, and a proposed pipeline for generating videos programmatically.

> [!WARNING]
> No generation pipeline exists in this repository. All eight videos are legacy manual captures, produced outside the repository and committed as final MP4s. Nothing here describes an implemented tool, and the current recordings cannot be re-edited from source in this repository.

The repository contains only the delivery files: eight MP4s and eight JPEG posters under `docs/public/assets/video/`. There are no raw masters, project files, narration scripts, timestamps, captions, transcripts or generation scripts.

## Current inventory

| Video                   |                Duration |        Size | Poster                  |
| ----------------------- | ----------------------: | ----------: | ----------------------- |
| `getting-started.mp4`   |       795.8 s (13:15.8) |       14 MB | `getting-started.jpg`   |
| `settings.mp4`          |       996.4 s (16:36.4) |       28 MB | `settings.jpg`          |
| `using-the-editor.mp4`  |        476.7 s (7:56.7) |       17 MB | `using-the-editor.jpg`  |
| `markers.mp4`           |        273.1 s (4:33.1) |      8.1 MB | `markers.jpg`           |
| `using-types.mp4`       |        253.1 s (4:13.1) |      5.7 MB | `using-types.jpg`       |
| `adding-map-meta.mp4`   |        171.3 s (2:51.3) |      5.3 MB | `adding-map-meta.jpg`   |
| `using-collections.mp4` |        161.0 s (2:41.0) |      3.8 MB | `using-collections.jpg` |
| `changing-basemaps.mp4` |        144.1 s (2:24.1) |      4.5 MB | `changing-basemaps.jpg` |
| **Total**               | **3,271.5 s (54:31.5)** | **86.4 MB** | **8 posters**           |

Sizes are rounded; the total is the sum of the rounded per-file figures. Every video is **1280×720, H.264 video + AAC audio**, with audio at roughly 59–65 kbps (≈64 kbps typical). Posters are 1280×720 JPEG. The posters add about 625 KB.

### Placement

- `docs/content/index.md` lines 16–26 contain the six-video “Getting Started” grid: `getting-started`, `using-the-editor`, `using-types`, `changing-basemaps`, `using-collections` and `adding-map-meta`.
- `docs/content/customising/markers.md` line 6 contains the `markers` video.
- `docs/content/customising/settings.md` line 6 contains the `settings` video.

The grid is wrapped by `docs/app/components/content/VideoGrid.vue` (lines 1–5); individual videos are rendered by `docs/app/components/content/VideoFigure.vue`.

### Playback and base path

`VideoFigure.vue` (lines 28–40) renders a native `<video controls playsinline preload="metadata">` element with the supplied poster. There is currently no `<track>` element, caption file or transcript.

Both the source and poster are resolved through `withAssetBase` (`VideoFigure.vue:20-23`, `docs/app/utils/assets.ts:42-54`). A path such as `/assets/video/getting-started.mp4` therefore resolves correctly whether the documentation is served from `/` or `/waymark-wp/`. Any replacement pipeline must keep the same `/assets/video/...` convention; otherwise the deployed docs will break under the base path.

### Legacy status and poster frames

All eight posters show the 2019–2021 WordPress admin and front-end UI with the old red Waymark “W” branding. None shows the current dark OGIS admin styling. Several frames are clipped or visually inconsistent: five are full admin captures, two are split composites, and one is a pure front-end capture. No poster is blank or corrupt.

The following observations are inferences from the poster frames, not a full shot list. The underlying recordings have not been transcribed.

| Video               | Poster frame (inference)                                                                                                                                                                                |
| ------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `getting-started`   | WP admin “Edit Map” for “Our Test Map”; Map Editor metabox; red-outlined polygon over Isle René-Levasseur; left editing toolbar with red W; Publish box and Map Shortcode panel.                        |
| `using-the-editor`  | WP admin Edit Map; marker edit popover with Photo type, Marker Title, image upload and Marker Description; blue line and red arrow shape; Publish box.                                                  |
| `using-types`       | Front-end “Seal Bay Park” map; red/blue lines, green bike markers, blue droplet marker, “Footpath” hover label; Thunderforest/OSM attribution.                                                          |
| `changing-basemaps` | WP admin Edit Map for “Log Train Trail”; Leaflet layers control open with Open Street Map, Outdoors, Streets, Transport and Satellite; Publish box.                                                     |
| `using-collections` | Waymark → Collections admin; create-collection form; “Island Rides” row, Count 2, truncated shortcode; standard WP table.                                                                               |
| `adding-map-meta`   | WP admin Edit Map for “Trent River Loop”; map; Collections box with “Island Rides” and “Courtenay” checked; Publish box.                                                                                |
| `markers`           | Split composite: Settings/Marker Types list (Camp, Beer, Water; icon types and colours) and front-end “Route Map” popup “The Scarlet Ibis” with photo and description.                                  |
| `settings`          | Split composite: Settings tabs Basemaps/Markers/Lines/Shapes/Meta/Misc, Marker Types rows Photo/Water, old first-person “Hi, I’m …” sidebar; front-end “Waypoint” page with green route and GPX export. |

Because the current admin uses the OGIS design rather than the old red branding, all eight videos need re-recording before they can represent the current plugin.

## 1. Goals and constraints

The generated set should be:

- **Deterministic.** The same scenario, narration, seed data and browser version produce the same frames and timings. Map tiles, fonts and third-party assets must be pinned or stubbed.
- **Repeatable.** A single command (or a short sequence of commands) rebuilds a video from its scenario and narration without manual video editing.
- **Reviewable.** Scenarios and narration are text files under version control, so changes can be reviewed like code. The final MP4 is a build artefact.
- **Accessible.** Every video has captions and a transcript, keyboard-accessible native controls and sufficient contrast. Visual actions are described, not only spoken.
- **Compatible with the current docs.** Delivery stays at `docs/public/assets/video/<id>.mp4` with a poster at `docs/public/assets/video/posters/<id>.jpg`, so `VideoFigure` and the base-path resolution continue to work unchanged.

Operational constraints:

- **Never use the development instance on ports 8888 or 8889.** Recording and seeding run against a dedicated, disposable wp-env instance, for example on session port 11000 (with 11010 for a separate tests site). The project’s Playwright config refuses `:8888` (`playwright.config.js:13-17`), and the demo seeder refuses it too (`tests/fixtures/demo/seed.php:43-46`). The 8889 tests instance is also off-limits by policy; the code guards are not a substitute for that rule.
- **Do not regenerate videos in CI.** Recording needs a disposable WordPress instance, a pinned browser and human review. Generate locally; commit the compressed MP4s, posters and captions. CI may validate metadata and assets.
- **Do not commit raw intermediates.** The delivered set is already 86.4 MB of MP4. Raw WebM, TTS audio and ffmpeg intermediates belong in `scripts/video/work/`, which should be gitignored when the pipeline is created.
- **Commit the delivery files.** The decision is to keep the MP4s in the repository rather than use external hosting. Consider Git LFS only if the delivered set grows substantially.

## 2. Proposed layout

The pipeline does not exist yet. The following layout keeps generation separate from delivery and makes the inputs obvious:

```text
scripts/video/
  README.md                 # setup, commands, prerequisites
  record.mjs                # Playwright launcher and scene runner
  tts.mjs                   # narration -> per-scene audio
  build.mjs                 # timing, ffmpeg, posters, VTT, manifest
  scenarios/
    changing-basemaps.json
    using-collections.json
  narration/
    changing-basemaps.md
    using-collections.md
  lib/
    browser.mjs             # context, storage state, video lifecycle
    cursor.mjs              # fake pointer and click ring
    highlight.mjs           # target outlines and backdrop
    timeline.mjs            # performance.now() bookkeeping
    vtt.mjs                 # timeline + narration -> WebVTT
    manifest.mjs            # ffprobe metadata and SHA-256
  work/                     # gitignored
    auth/storage-state.json
    audio/
    raw/
```

Delivery remains:

```text
docs/public/assets/video/<id>.mp4
docs/public/assets/video/posters/<id>.jpg
docs/public/assets/video/captions/<id>.en.vtt
docs/public/assets/video/captions/<id>.<lang>.vtt
```

The `captions/` directory is proposed; it does not exist yet. Transcripts can live alongside the captions, in the docs page as a `<details>` block, or in this file.

When implementation starts, add `scripts/video/work/` to the repository’s `.gitignore`. The root `.gitignore` currently has no entry for it.

## 3. Scenario script schema

A scenario is a JSON file with a top-level video definition and an ordered list of scenes. The recorder resolves template tokens such as `{{baseURL}}` and `{{mapId}}` at runtime; it does not hard-code instance URLs.

| Field          | Type   | Required | Purpose                                                     |
| -------------- | ------ | -------- | ----------------------------------------------------------- |
| `id`           | string | yes      | Stable identifier; also the delivery basename.              |
| `title`        | string | yes      | Human-readable title for logs and the manifest.             |
| `output`       | string | yes      | Delivery MP4 path, relative to the repository root.         |
| `viewport`     | object | yes      | `{ "width": 1280, "height": 720 }`.                         |
| `storageState` | string | yes      | Saved authenticated state, relative to the repository root. |
| `posterAt`     | string | yes      | Fallback poster timestamp, `HH:MM:SS` or seconds.           |
| `scenes`       | array  | yes      | Ordered scenes; see below.                                  |

Each scene supports:

| Field       | Type           | Purpose                                                                                                  |
| ----------- | -------------- | -------------------------------------------------------------------------------------------------------- |
| `id`        | string         | Stable scene identifier; used for audio filenames and captions.                                          |
| `narration` | string         | Narration text. May be empty for a visual-only beat.                                                     |
| `action`    | string         | Action from the vocabulary below.                                                                        |
| `selector`  | string         | Target for actions that operate on an element.                                                           |
| `url`       | string         | Target for `goto`.                                                                                       |
| `value`     | string         | Text or option value for `fill` and `select`.                                                            |
| `key`       | string         | Key for `press`, for example `Enter` or `Escape`.                                                        |
| `hold`      | number         | Seconds to hold after the action, normally audio duration + padding.                                     |
| `highlight` | object or null | `{ "selector": "...", "pad": 8 }`; draws a target outline.                                               |
| `cursor`    | object or null | `{ "to": "..." }` or `{ "x": 640, "y": 360 }`; moves the fake pointer.                                   |
| `assert`    | object         | Optional post-action check, for example `{ "selector": "...", "visible": true }` or `{ "text": "..." }`. |
| `notes`     | string         | Optional direction for reviewers; ignored by the recorder.                                               |

### Example

This illustrative scenario uses the proposed vocabulary. Selectors are examples and must be confirmed against the current DOM when the pipeline is implemented.

```json
{
  "id": "changing-basemaps",
  "title": "Changing Basemaps",
  "output": "docs/public/assets/video/changing-basemaps.mp4",
  "viewport": { "width": 1280, "height": 720 },
  "storageState": "scripts/video/work/auth/storage-state.json",
  "posterAt": "00:00:08",
  "scenes": [
    {
      "id": "open-map",
      "narration": "Start in the WordPress admin and open the Map you want to update.",
      "action": "goto",
      "url": "{{baseURL}}/wp-admin/post.php?post={{mapId}}&action=edit",
      "hold": 6.2,
      "highlight": null,
      "cursor": { "to": "#waymark-map" },
      "assert": { "selector": "#waymark-map", "visible": true }
    },
    {
      "id": "wait-for-map",
      "narration": "Waymark loads the Map Editor with the saved Map.",
      "action": "waitForMap",
      "hold": 2.4,
      "highlight": { "selector": "#waymark-map", "pad": 8 },
      "cursor": { "to": "#waymark-map" }
    },
    {
      "id": "open-layers",
      "narration": "Open the layers control in the top-right corner.",
      "action": "click",
      "selector": ".leaflet-control-layers-toggle",
      "hold": 3.8,
      "highlight": { "selector": ".leaflet-control-layers", "pad": 8 },
      "cursor": { "to": ".leaflet-control-layers-toggle" }
    },
    {
      "id": "mark-poster",
      "narration": "",
      "action": "poster",
      "hold": 0.2,
      "highlight": { "selector": ".leaflet-control-layers", "pad": 8 },
      "cursor": null
    },
    {
      "id": "choose-satellite",
      "narration": "Choose Satellite to switch the basemap.",
      "action": "click",
      "selector": "text=Satellite",
      "hold": 5.0,
      "highlight": { "selector": ".leaflet-control-layers", "pad": 8 },
      "cursor": { "to": "text=Satellite" },
      "assert": { "selector": ".leaflet-tile-pane", "visible": true }
    },
    {
      "id": "restore-default",
      "narration": "Switch back to Open Street Map to leave the Map as it was.",
      "action": "click",
      "selector": "text=Open Street Map",
      "hold": 5.4,
      "highlight": { "selector": ".leaflet-control-layers", "pad": 8 },
      "cursor": { "to": "text=Open Street Map" }
    }
  ]
}
```

### Action vocabulary

| Action           | Payload                            | Behaviour                                                              |
| ---------------- | ---------------------------------- | ---------------------------------------------------------------------- |
| `goto`           | `url`                              | Navigate, then wait for the target page to be usable.                  |
| `click`          | `selector`                         | Move the fake cursor, click, and wait for actionability.               |
| `fill`           | `selector`, `value`                | Click, fill the field and show a visible fake caret.                   |
| `select`         | `selector`, `value`                | Choose an option in a native `<select>`.                               |
| `press`          | `selector`, `key`                  | Focus the target and press a key.                                      |
| `hover`          | `selector`                         | Move the cursor over the target without clicking.                      |
| `scrollIntoView` | `selector`                         | Scroll the target into view and wait for layout to settle.             |
| `waitFor`        | `selector`                         | Wait for the target to become visible or attached.                     |
| `waitForMap`     | none                               | Wait for `window.Waymark.map` and then run the Leaflet settle routine. |
| `dragMap`        | `selector`, `from`/`to` or `delta` | Pan a map by a recorded pixel offset.                                  |
| `clickMapPoint`  | `selector`, `latlng` or `point`    | Click a map coordinate, preferring `latlng` over screen pixels.        |
| `openPopup`      | `selector` or map point            | Open a marker popup and wait for `.leaflet-popup`.                     |
| `pause`          | `hold`                             | An intentional beat with no UI action.                                 |
| `poster`         | none                               | Record a candidate poster frame in `timeline.json`; no UI action.      |
| `assertText`     | `selector`, `value`                | Assert visible text and hold on the result.                            |

`assert` runs after the action and does not create a separate timing beat. `assertText` is for scenes where the assertion itself is the visible beat, such as a success notice.

### Selector guidance

- Prefer accessible, user-facing selectors: `role=button[name="Publish"]`, `text=Satellite`, or a visible label. Playwright resolves role and text selectors natively.
- Avoid `nth-child`, generated class names and selectors tied to DOM order. The admin markup can change without any visible design change.
- Use stable plugin containers where no accessible role exists, and document why the selector is stable. The admin map container is `#waymark-map` (`inc/Admin/Waymark_JS.php:70`).
- Pin the site language for recording. Translatable strings are not stable selectors across locales.
- Scope text selectors to a container when the same label can appear in more than one place.
- Keep assertions user-visible. Avoid asserting implementation details such as a particular class on a map pane.

## 4. Timing model

Use an **audio-first** workflow. Do not hand-maintain absolute timestamps in the scenario.

1. Write the narration per scene in `scripts/video/narration/<id>.md`. Use the same scene IDs as the scenario.
2. Generate one audio file per scene, for example `work/audio/<id>/<scene>.wav`.
3. Measure each file with `ffprobe`:

   ```sh
   ffprobe -v error -show_entries format=duration \
     -of default=noprint_wrappers=1:nokey=1 \
     scripts/video/work/audio/changing-basemaps/open-map.wav
   ```

4. Set `hold = audio duration + 0.4–0.8 s` for each scene. The padding is the visual breathing room after the sentence ends. Use the longer end for scenes with a map transition or a popup.
5. Record the scenario. The recorder logs actual `performance.now()` values to `scripts/video/work/timeline.json`.
6. Generate WebVTT and the transcript from the timeline plus the narration. Do not rely on frame detection or visual diffing.

A timeline record should capture at least the scene ID, start and end seconds, action, narration and audio path:

```json
{
  "id": "changing-basemaps",
  "startedAt": "2026-10-21T14:00:00.000Z",
  "viewport": { "width": 1280, "height": 720 },
  "scenes": [
    {
      "id": "open-map",
      "start": 0.0,
      "end": 6.2,
      "action": "goto",
      "narration": "Start in the WordPress admin and open the Map you want to update.",
      "audio": "work/audio/changing-basemaps/open-map.wav"
    }
  ]
}
```

The timeline is the source of truth for captions and audio placement. If narration changes after recording, re-record the affected scene or adjust its hold and re-record; do not stretch the audio.

### Pace and duration

The current set is long: `getting-started` is 13:15.8 and `settings` is 16:36.4. A programmatic first pass should aim for **2–6 minute pieces**, one concept each. Split long topics into separate videos rather than producing another 13–16 minute recording.

Working targets:

- 5–20 seconds per scene.
- Roughly 130–150 words per minute of narration.
- A maximum of about 6 minutes for a first-pass video.
- A 0.4–0.8 second gap between scenes, included in `hold`.

### Poster timestamps

`posterAt` is a fallback. The recorder can mark a preferred frame with the `poster` action; the build step then extracts the poster from the **final muxed video**, not from the raw WebM. This ensures the poster matches the published colour, audio timing and captions.

After extraction, inspect the frame for an unintentional cursor, highlight or popup. If the frame is wrong, adjust the marker and rebuild the poster; do not hand-edit the JPEG unless the edit is recorded in the manifest.

## 5. Playwright recording

### Reuse the existing harness

The deterministic screenshot harness in `tests/e2e/fixtures.js` already solves most of the hard problems. Reuse its helpers rather than reimplementing them:

| Helper                        | Location                    | Use for video                                                                  |
| ----------------------------- | --------------------------- | ------------------------------------------------------------------------------ |
| `waymark.login`               | `tests/e2e/fixtures.js:292` | One-off authentication to create `storageState`; keep login out of recordings. |
| `waymark.getMap`              | `tests/e2e/fixtures.js:54`  | Resolve a seeded Map by slug.                                                  |
| `waymark.seed`                | `tests/e2e/fixtures.js:679` | Access the parsed `seed-output.json` data.                                     |
| `settleMap`                   | `tests/e2e/fixtures.js:362` | Wait for Leaflet tiles, fonts and (optionally) popups.                         |
| `installTileRoutes`           | `tests/e2e/fixtures.js:581` | Record/replay the pinned tile pack.                                            |
| `makePageDeterministic`       | `tests/e2e/fixtures.js:264` | Disable transitions, animations and caret blink.                               |
| `installExternalRequestGuard` | `tests/e2e/fixtures.js:153` | Fail the run on unexpected network egress.                                     |
| `stubExternalAssets`          | `tests/e2e/fixtures.js:221` | Serve emoji, Gravatars and the d3 build locally.                               |
| `waitForLayoutStable`         | `tests/e2e/fixtures.js:420` | Wait for admin meta boxes to stop reflowing.                                   |

`tests/e2e/global.setup.js:49-87` is the reference for preparing a disposable instance: activate the plugin, activate Twenty Twenty-Five and run the demo seeder. The production video instance needs the same preparation.

The screenshot suite is `tests/e2e/screenshots.spec.js`; it is the closest existing capture workflow and a useful reference for navigation, assertions and layout settling.

> [!WARNING]
> `tests/e2e/fixtures.js` resolves its allowed origin from `WP_BASE_URL` at import time (default `http://localhost:8889`). Set `WP_BASE_URL=http://localhost:11000` in the environment before starting the launcher; changing it after the import will not update the request guard. The seed fixture’s permalinks are also written for 8889, so use `getMap().id` and build URLs against the video `baseURL` rather than reusing `.permalink`.

### Separate launcher, not the test runner

Use a separate Playwright launcher (`scripts/video/record.mjs`) rather than a spec executed by `playwright test`. The test runner’s pass/fail lifecycle, retries and reporting are not appropriate for a long recording session, and a failed assertion should stop the take cleanly rather than trigger a retry that produces a second partial video.

The launcher can still import the pure helpers from `tests/e2e/fixtures.js`. That file is CommonJS, so from an `.mjs` launcher use `createRequire`:

```js
import fs from "node:fs";
import path from "node:path";
import { createRequire } from "node:module";

const require = createRequire(import.meta.url);
const { chromium } = require("@playwright/test");
const {
  login,
  installExternalRequestGuard,
  stubExternalAssets,
  makePageDeterministic,
  installTileRoutes,
  settleMap,
} = require("../../tests/e2e/fixtures.js");
```

### Context and video lifecycle

Record one context per video at 1280×720. Do not inherit the screenshot project’s 1600×1000 @ DPR 2 defaults (`playwright.config.js:21-25`); video has its own dimensions and needs a stable 30 fps output. Run the launcher from the repository root; the paths below are resolved from the current working directory.

```js
// fixtures.js reads WP_BASE_URL at import time; set it before starting this process.
const baseURL = process.env.WP_BASE_URL;
if (!baseURL) {
  throw new Error(
    "Set WP_BASE_URL to the video instance, e.g. http://localhost:11000",
  );
}

const workDir = path.resolve("scripts/video/work");
const rawDir = path.join(workDir, "raw");
fs.mkdirSync(rawDir, { recursive: true });

const browser = await chromium.launch();
const context = await browser.newContext({
  baseURL,
  viewport: { width: 1280, height: 720 },
  recordVideo: {
    dir: rawDir,
    size: { width: 1280, height: 720 },
  },
  reducedMotion: "reduce",
  storageState: path.join(workDir, "auth", "storage-state.json"),
});

const violations = [];
await installExternalRequestGuard(context, violations);
await stubExternalAssets(context);

const page = await context.newPage();
await makePageDeterministic(page);
await installTileRoutes(page, { violations });

await page.goto(`${baseURL}/wp-admin/post.php?post=100&action=edit`);
await settleMap(page);

// Run the scenario scenes here.

const video = page.video();
await context.close();
await video.saveAs(path.join(rawDir, "changing-basemaps.webm"));
await browser.close();
```

Mirror the fixture ordering: install the catch-all request guard first, then the more specific asset and tile routes. A violation should fail the recording run, not be silently ignored.

Playwright records page content only, not browser chrome. That is desirable for clean documentation videos and is the recommended mode. If a future tutorial needs to show the browser toolbar or the OS, use an OS-level capture tool instead; do not try to reconstruct browser chrome inside the page.

### Authentication

Create the storage state once, before recording:

```js
fs.mkdirSync(path.join(workDir, "auth"), { recursive: true });

const authContext = await browser.newContext({ baseURL });
const authPage = await authContext.newPage();
await login(authPage);
await authContext.storageState({
  path: path.join(workDir, "auth", "storage-state.json"),
});
await authContext.close();
```

The recording context then starts authenticated. Keep the login screen and credential entry out of published videos. Do not commit `storage-state.json`; it contains session cookies. Refresh it when the session expires, and validate the session at the start of every take (for example, assert the admin bar) so an expired state fails immediately rather than recording the login screen.

### Fake cursor and highlights

Playwright does not paint a mouse cursor into the video. Inject a fake cursor overlay and move it with real mouse events:

- Create a fixed-position element with `pointer-events: none` and a very high `z-index`.
- Hide the real cursor with `cursor: none` on the page while recording.
- Update the overlay on `mousemove`, and move the real mouse with `page.mouse.move(x, y, { steps: 20 })` so the overlay follows a smooth path.
- Add a click ring or scale pulse on `mousedown`/`mouseup`.
- Use a class rule with higher specificity than the deterministic `* { transition: none !important }` reset if the cursor needs a short CSS transition. Otherwise move it in discrete steps.

Highlights should follow one visual language across the set: a coloured outline, a soft box-shadow or dimmed backdrop, and a short fade in/out. Position the overlay with `position: fixed` and `getBoundingClientRect()` so it does not affect layout. Remove every overlay after its scene; stale highlights are a common source of confusing frames.

`makePageDeterministic` disables animations, transitions and the caret (`tests/e2e/fixtures.js:255-280`). That is correct for stable frames, but typing scenes need a visible **fake** caret: a 2 px element positioned at the input’s text end, with no blink, is more reliable than trying to re-enable the native caret.

### Maps and tiles

Use `settleMap` after navigation and after any action that changes the map view. It waits for tiles, a second confirmation after a possible late `fitBounds`, and fonts. By default it also closes popups (`tests/e2e/fixtures.js:362-395`); pass `{ closePopups: false }` when the popup is part of the scene, or open the popup after settling.

Use the pinned tile pack in `tests/fixtures/demo/tiles/` for deterministic frames. `installTileRoutes` aborts a missing tile and records it as a violation by design, so a recording cannot silently fall back to the network. If a new framing needs tiles that are not in the pack, record only the missing tiles with `WAYMARK_RECORD_TILES=1` and respect the OpenStreetMap and OpenTopoMap usage policies. The tile-pack rules are documented in `tests/e2e/README.md`.

Live OSM tiles are acceptable only for exploratory drafts or when the pack genuinely lacks coverage. Production recordings should use the pack so repeated takes are identical.

### Version and browser

The repository declares `@playwright/test` `^1.63.0` (`package.json:20`) and configures Chromium only (`playwright.config.js:42-47`). Pin the browser build used for recording and note it in the manifest. A Playwright or Chromium upgrade can change rendering and video encoding even when the scenario is unchanged.

## 6. WordPress and admin preparation

### Dedicated instance

Use a separate wp-env configuration for video, for example `scripts/video/wp-env.video.json`:

```json
{
  "plugins": ["."],
  "port": 11000,
  "autoPort": false
}
```

Run these commands from the repository root, so the relative plugin source `"."` resolves to the plugin root rather than to `scripts/video/`. Start the instance on the session port, never on 8888 or 8889:

```sh
npx wp-env --config=scripts/video/wp-env.video.json start
```

If the pipeline also needs a separate tests site, add `"testsEnvironment": true` and `"testsPort": 11010`. The video instance itself only needs the development site.

### Seed and theme

Activate the plugin and Twenty Twenty-Five, then run the demo seeder:

```sh
npx wp-env --config=scripts/video/wp-env.video.json run cli \
  wp plugin activate waymark-wp

npx wp-env --config=scripts/video/wp-env.video.json run cli \
  wp theme activate twentytwentyfive

npx wp-env --config=scripts/video/wp-env.video.json run cli \
  wp eval-file wp-content/plugins/waymark-wp/tests/fixtures/demo/seed.php
```

The current seed provides:

| Item        | Value                                                                                                                     |
| ----------- | ------------------------------------------------------------------------------------------------------------------------- |
| Maps        | Cape Scott Trail (#100), San Josef Bay (#101), North Coast Trail (#102)                                                   |
| Page        | Waymark Demo (#110, slug `waymark-demo`)                                                                                  |
| Collections | Vancouver Island (parent), Day Hikes, Backpacking                                                                         |
| Settings    | Marker Types tab at `/wp-admin/edit.php?post_type=waymark_map&page=waymark-settings&content=waymark-settings-tab-markers` |

Use these seeded items for new recordings. The legacy videos use different demo content (“Our Test Map”, “Seal Bay Park”, “Log Train Trail”, “Trent River Loop”, “Island Rides” and so on), which is not part of the current seed.

> [!WARNING]
> The seeder writes `tests/fixtures/demo/seed-output.json` with URLs built from the target instance’s home URL (`tests/fixtures/demo/seed.php:667-671`). Running it against port 11000 will overwrite the fixture used by the 8889 test suite. The pipeline needs an output-path option, a copy of the seeder with a temporary output path, or a restore/reseed of the 8889 instance afterwards. Do not ignore this side effect.

### Run-specific admin setup

These are setup steps, not content changes:

- Use one admin colour scheme for the recording user and keep it consistent across the set.
- Dismiss the WordPress welcome guide and any plugin notices that are not part of the demonstration.
- Hide Screen Options when they are not relevant; they add visual noise and can shift layout.
- Confirm permalinks are set to `/%postname%/`. The seeder already does this (`tests/fixtures/demo/seed.php:231-237`).
- Use the seeded page and map permalinks for front-end scenes.
- For editor scenes, use `post.php?post=<id>&action=edit` and wait for the Map Editor before recording the first action.
- Do not show the login screen or credentials. Local defaults are `admin` / `password` (`tests/e2e/fixtures.js:303-304`); they are for local setup only.

Record the current OGIS admin styling, not the old red “W” branding. This is the main reason the existing eight videos cannot simply be re-encoded.

## 7. AI voice-over

No TTS provider is configured in the repository. The current decision is to use macOS `say` for the published narration; the cloud options remain documented as alternatives if the voice is revisited later.

| Option           | Best for                                    | Notes                                                           |
| ---------------- | ------------------------------------------- | --------------------------------------------------------------- |
| macOS `say`      | Chosen for published videos                 | No setup or credentials; synthetic prosody is accepted.         |
| OpenAI TTS       | Alternative production narration            | Hosted API; needs a key and usage budget.                       |
| ElevenLabs       | Alternative natural, expressive narration   | Hosted API; check voice licensing and cloning consent.          |
| Azure Speech     | Alternative narration with language control | Hosted API; pronunciation and voice selection are configurable. |
| Google Cloud TTS | Alternative narration with many voices      | Hosted API; check quotas and data terms.                        |

Store API keys in environment variables. Never put keys in scenarios, narration files, committed `.env` files or CI logs.

### Voice and pronunciation

Use one voice for the whole set. Build a pronunciation lexicon and apply it before synthesis:

| Term            | Suggested treatment                            |
| --------------- | ---------------------------------------------- |
| Waymark         | “WAY-mark”                                     |
| OGIS / Open GIS | “oh-gee-eye-ess” on first use, then “Open GIS” |
| GeoJSON         | “JEE-oh-JAY-son”                               |
| Leaflet         | “LEF-lit”                                      |
| WordPress       | “WORD-press”                                   |
| wp-admin        | “double-you-pee-admin”                         |
| shortcode       | “SHORT-code”                                   |
| lat/lng         | “latitude and longitude”                       |
| GPX / KML       | “G-P-X” / “K-M-L”                              |

Review every generated track by ear. TTS engines frequently mispronounce product names, abbreviations and place names.

### Per-scene audio

Generate one audio file per scene, not one long file. This keeps timing adjustable and lets a single scene be regenerated without re-recording the whole video.

Normalise each file to broadcast-style loudness:

```sh
ffmpeg -i scene.wav \
  -af "loudnorm=I=-16:TP=-1.5:LRA=11" \
  -ar 48000 -c:a pcm_s16le scene-normalised.wav
```

For the final mux, encode AAC at 128 kbps and 48 kHz. Keep the normalised WAVs in `scripts/video/work/audio/`; do not commit them.

If a TTS take runs long, shorten the script. Do not speed it up beyond about 1.1×; faster speech becomes harder to follow and sounds unnatural. Add 0.4–0.8 seconds between scenes as part of the hold.

A draft macOS track can be generated with:

```sh
mkdir -p scripts/video/work/audio/changing-basemaps
say -v Samantha -r 165 \
  -o scripts/video/work/audio/changing-basemaps/open-map.aiff \
  "Start in the WordPress admin and open the Map you want to update."
```

A production `tts.mjs` can expose a small provider-neutral interface:

```js
export async function synthesise({ text, voice, language = "en-GB", out }) {
  // Provider-specific request. Cache by SHA-256(text + voice + settings).
  // Write the audio to `out` and return its measured duration.
}
```

### Disclosure and consent

Disclose synthetic narration where required by the publishing context or the voice provider’s terms. Obtain consent before cloning or imitating a real person’s voice. Do not use a voice that implies endorsement by a real individual. Keep a record of the provider, voice and consent basis in the video manifest or production notes.

## 8. Post-production with ffmpeg

> [!WARNING]
> ffmpeg is not currently installed in the development environment. `command -v ffmpeg` returns nothing, and `ffprobe` is also absent. Install both before implementing the pipeline:
>
> ```sh
> brew install ffmpeg
> ```

### Transcode the raw recording

Playwright produces a WebM (VP8) file. Convert it to delivery H.264/AAC:

```sh
ffmpeg -i raw.webm \
  -vf "scale=1280:720:flags=lanczos,fps=30" \
  -c:v libx264 -profile:v high -pix_fmt yuv420p \
  -crf 21 -preset slow \
  -c:a aac -b:a 128k -ar 48000 \
  -movflags +faststart out.mp4
```

### Mux the narration

Build a single narration track aligned to the recorded scene starts first. If holds include padding, place each scene’s audio at its start time with silence between; do not simply concatenate files if that would shift the alignment.

```sh
ffmpeg -i silent.mp4 -i narration.wav \
  -c:v copy -c:a aac -b:a 128k -ar 48000 \
  -shortest -movflags +faststart final.mp4
```

### Extract the poster

Extract the poster from the final muxed video:

```sh
ffmpeg -ss 00:00:08 -i final.mp4 -frames:v 1 -q:v 2 \
  docs/public/assets/video/posters/getting-started.jpg
```

The timestamp is a fallback. Prefer the frame marked by the `poster` action in `timeline.json`, and verify that the extracted frame has no unintended cursor, highlight or popup.

### Master files

The decision is 720p delivery only; do not record or archive a separate 1080p master. Record at 1280×720 and keep the intermediate WebM under `scripts/video/work/` only for the duration of the run. If this changes later, record a second Playwright context at 1920×1080 and derive the 720p delivery file; upscaling the 720p recording adds no detail.

### Captions

Prefer soft captions (WebVTT) over burned-in text. Soft captions are searchable, translatable, styleable by the browser and available to assistive technology. Burn captions into the picture only when a target platform cannot render a caption track, and if you do, keep them inside the safe area with sufficient contrast.

### Manifest

Write a manifest next to the delivered assets (or under `scripts/video/work/` and publish a copy) with at least the following. The duration and size are examples from the current `changing-basemaps` file; a rebuilt file will differ.

```json
{
  "id": "changing-basemaps",
  "scenarioVersion": 1,
  "playwright": "^1.63.0",
  "chromium": "<build>",
  "file": "changing-basemaps.mp4",
  "duration": 144.1,
  "width": 1280,
  "height": 720,
  "videoCodec": "h264",
  "audioCodec": "aac",
  "size": 4760810,
  "sha256": "<sha256>",
  "poster": "posters/changing-basemaps.jpg",
  "captions": "captions/changing-basemaps.vtt",
  "transcript": "captions/changing-basemaps.md"
}
```

Read the values with `ffprobe` and compute the digest with `shasum -a 256` on macOS (or `sha256sum` on Linux). A manifest makes metadata validation possible without decoding every video.

## 9. Captions, transcripts and accessibility

### WebVTT from the timeline

Generate captions from `timeline.json` plus the narration. Do not infer timings from frames.

```vtt
WEBVTT

00:00:00.000 --> 00:00:06.200
Start in the WordPress admin and open the Map you want to update.

00:00:06.200 --> 00:00:08.600
Waymark loads the Map Editor with the saved Map.

00:00:08.600 --> 00:00:12.400
[opens the layers control]
```

Captions should describe meaningful UI actions, not only dialogue. When there is no narration, caption the action in square brackets. When narration and action overlap, keep the narration as the cue text and add action detail only when it conveys something the audio does not.

### Add a caption track

`VideoFigure.vue` currently renders no `<track>` element. The proposed change is to accept a `captions` prop, resolve it with `withAssetBase`, and render:

```vue
<track
  kind="captions"
  srclang="en"
  label="English"
  default
  :src="resolvedCaptions"
/>
```

The delivery path should follow the same base-path convention as the video and poster, for example `/assets/video/captions/changing-basemaps.vtt`.

### Caption languages

Ship English as the default track plus Spanish, French, German, Portuguese, Italian and Dutch. Use one WebVTT file per language, for example `changing-basemaps.en.vtt` and `changing-basemaps.es.vtt`. Extend `VideoFigure.vue` to accept a list of tracks:

```vue
<track
  v-for="track in resolvedCaptions"
  :key="track.srclang"
  kind="captions"
  :srclang="track.srclang"
  :label="track.label"
  :default="track.default"
  :src="track.src"
/>
```

Only the English track should be marked `default`. Keep the transcript in English unless a translated transcript is also produced.

### Transcript

Publish a full transcript with each video. It can be:

- a `<details>` block immediately after the video in the docs page, or
- a transcript section in this file, linked from the video caption, or
- a standalone Markdown file alongside the captions.

Include the spoken narration and concise descriptions of the visual actions. Keep it in sync with the narration; a transcript that no longer matches the audio is worse than no transcript.

### Accessibility checklist

- Keep native `<video controls>`; do not replace it with a custom player unless there is a clear need.
- Preserve `playsinline` and `preload="metadata"`.
- Ensure the player and any custom controls are keyboard accessible.
- Provide captions and a transcript for every video.
- Describe visual-only information in captions or the transcript.
- Do not use colour alone to convey meaning; pair highlights with shape, outline or text.
- Maintain sufficient contrast for highlights, captions and on-screen text.
- Avoid rapid flashing, strobing and unnecessary motion. The recorder should disable decorative animation.
- Do not auto-play with sound. The current player does not auto-play; keep it that way.

## 10. Publishing and validation

### Publishing

Replace the same eight filenames so the docs content needs no changes. Add new files only for new topics, and update the docs grid or page that references them.

For each replacement:

- Write the MP4 to `docs/public/assets/video/<id>.mp4`.
- Write the poster to `docs/public/assets/video/posters/<id>.jpg`.
- Write the English captions to `docs/public/assets/video/captions/<id>.en.vtt`, plus the Spanish, French, German, Portuguese, Italian and Dutch variants.
- Update the inventory table in this file with the new duration, size and poster.
- Update any docs caption or transcript that refers to the old content.

Delivery is fixed at 1280×720; no separate master is archived.

### Validate the docs build

```sh
cd docs
npm run generate
```

The docs `dev` script currently uses port 11003 (`docs/package.json:6`). For a smoke test, use a free port in the 11000–11999 range and do not disturb the WordPress instances on 8888/8889.

Smoke-check the generated site:

- Every poster loads.
- Every video plays with the native controls.
- Captions are selectable and timed correctly.
- Paths resolve under both `/` and `/waymark-wp/`.
- No console errors or missing-asset requests.

### Optional CI validation

CI should validate outputs, not generate them. A lightweight check can:

- Confirm each expected MP4 exists and is non-empty.
- Read duration, dimensions and codecs with `ffprobe`.
- Confirm the poster exists at 1280×720.
- Confirm every required WebVTT track and transcript exists and is non-empty.
- Recompute SHA-256 and compare it with the manifest.
- Confirm the docs content references only files that exist.

Do not run TTS, Playwright recording or ffmpeg transcoding in CI.

### Do not commit

Keep the following out of version control:

- Raw Playwright WebM recordings.
- TTS audio and provider responses.
- ffmpeg intermediates.
- `storage-state.json` and any credentials.
- Temporary timelines and pipeline logs.

They belong in `scripts/video/work/`, which should be gitignored.

## 11. Recommended rollout

Pilot with **`changing-basemaps`**. At 2:24 it is the shortest video, it has a clear visual action (opening the Leaflet layers control and switching basemaps), and it exercises cursor movement, highlighting, map settling, TTS, captions and poster extraction without a long script.

Then proceed in this order:

1. `changing-basemaps` (pilot)
2. `using-collections`
3. `adding-map-meta`
4. `using-types`
5. `using-the-editor`
6. `markers`
7. `settings`
8. `getting-started`

Rewrite the long topics into shorter scenes as part of the work. The current 13–16 minute pieces are too long for a first programmatic pass and are harder to review, caption and re-record.

Re-record only after the admin UI and copy are final. A design or wording change after recording invalidates both the footage and the narration.

## 12. Decisions

Resolved:

- **Pilot video:** `changing-basemaps`.
- **Capture style:** page-only Playwright at 1280×720, with an injected cursor and no browser chrome.
- **Narration:** macOS `say` for the published videos.
- **Delivery:** 1280×720 MP4s committed under `docs/public/assets/video/`; no separate 1080p master.
- **Validation:** add a CI check for duration, dimensions, audio stream, poster and caption files.
- **Captions and transcript:** English WebVTT captions plus a transcript.
- **Additional caption languages:** Spanish, French, German, Portuguese, Italian and Dutch, with English as the default track.

No open decisions remain. The remaining work is implementation and the `changing-basemaps` pilot.

## Reference map

| Path                                          | Role                                                                                           |
| --------------------------------------------- | ---------------------------------------------------------------------------------------------- |
| `docs/app/components/content/VideoFigure.vue` | Native video player, poster, base-path resolution; currently no caption track.                 |
| `docs/app/components/content/VideoGrid.vue`   | Grid wrapper used on the docs home page.                                                       |
| `docs/app/utils/assets.ts`                    | `withAssetBase`, which resolves `/assets/...` under the configured base path.                  |
| `docs/content/index.md`                       | Six-video Getting Started grid (lines 16–26).                                                  |
| `docs/content/customising/markers.md`         | Markers video (line 6).                                                                        |
| `docs/content/customising/settings.md`        | Settings video (line 6).                                                                       |
| `tests/e2e/fixtures.js`                       | Deterministic screenshot harness: login, seeding, map settling, tiles, external-request guard. |
| `tests/e2e/global.setup.js`                   | Activates the plugin and theme, then seeds the tests instance.                                 |
| `tests/e2e/screenshots.spec.js`               | Existing deterministic capture spec; reference for navigation and assertions.                  |
| `tests/e2e/README.md`                         | Tile-pack record/replay policy.                                                                |
| `tests/fixtures/demo/seed.php`                | Demo Maps, Collections, Page, Media and Settings.                                              |
| `tests/fixtures/demo/seed-output.json`        | Parsed seed data used by the e2e harness.                                                      |
| `playwright.config.js`                        | Test runner configuration, 8888 guard and screenshot viewport defaults.                        |
| `docs/package.json`                           | Docs build script (`npm run generate`) and dev port.                                           |
