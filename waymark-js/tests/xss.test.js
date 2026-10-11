import { describe, it, expect, beforeEach } from "vitest";
import DOMPurify from "dompurify";

// The vendored DOMPurify is a UMD global in the production bundle. In the test
// environment it is not loaded by tests/setup.js, so we provide it ourselves by
// binding the npm package to the jsdom window before exercising the sanitizer.
window.DOMPurify = DOMPurify;

describe("XSS Hardening", () => {
  let viewer;

  beforeEach(() => {
    // Setup the DOM
    document.body.innerHTML =
      '<div id="waymark-map" style="height: 500px; width: 500px;"></div>';

    // Mock clientHeight/clientWidth for Leaflet
    const container = document.getElementById("waymark-map");
    Object.defineProperty(container, "clientWidth", { value: 500 });
    Object.defineProperty(container, "clientHeight", { value: 500 });
  });

  describe("sanitize_description", () => {
    it("should strip <script> and <img onerror=...> payloads", () => {
      viewer = window.Waymark_Map_Factory.viewer();

      const result = viewer.sanitize_description(
        "<p>Hello</p><script>alert(1)</script><img src=x onerror=alert(1)>",
      );

      expect(result).not.toContain("<script");
      expect(result).not.toContain("<img");
      expect(result).toContain("<p>Hello</p>");
    });

    it("should strip event-handler attributes", () => {
      viewer = window.Waymark_Map_Factory.viewer();

      const result = viewer.sanitize_description(
        '<p onclick="alert(1)" onmouseover="steal()" onfocus="steal()">Text</p>',
      );

      expect(result).not.toContain("onclick");
      expect(result).not.toContain("onmouseover");
      expect(result).not.toContain("onfocus");
      expect(result).toContain("<p>Text</p>");
    });

    it("should preserve parent-plugin injected markup", () => {
      viewer = window.Waymark_Map_Factory.viewer();

      const result = viewer.sanitize_description(
        '<div class="waymark-description-link"><span class="waymark-map-link"><a href="https://example.com" target="_blank">Map title</a></span></div>' +
          '<p class="waymark-property waymark-property-size"><b>Size</b><br>value</p>',
      );

      // Tags survive
      expect(result).toContain('<div class="waymark-description-link">');
      expect(result).toContain('<span class="waymark-map-link">');
      expect(result).toContain(
        '<p class="waymark-property waymark-property-size">',
      );
      expect(result).toContain("<b>Size</b>");
      expect(result).toContain("<br>");

      // Allowed attributes survive
      expect(result).toContain(
        '<a href="https://example.com" target="_blank">Map title</a>',
      );
    });

    it("should strip javascript: hrefs", () => {
      viewer = window.Waymark_Map_Factory.viewer();

      const result = viewer.sanitize_description(
        '<a href="javascript:alert(1)">Click</a>',
      );

      expect(result).not.toContain("javascript:");
      expect(result).toContain("Click");
    });

    it("should return the raw string when DOMPurify is unavailable (defensive fallback)", () => {
      viewer = window.Waymark_Map_Factory.viewer();

      const saved = window.DOMPurify;
      delete window.DOMPurify;

      try {
        const result = viewer.sanitize_description("<script>alert(1)</script>");
        expect(result).toBe("<script>alert(1)</script>");
      } finally {
        window.DOMPurify = saved;
      }
    });
  });

  describe("is_safe_url", () => {
    beforeEach(() => {
      viewer = window.Waymark_Map_Factory.viewer();
    });

    it("should accept http:// and https:// URLs", () => {
      expect(viewer.is_safe_url("https://example.com/photo.jpg")).toBe(true);
      expect(viewer.is_safe_url("http://example.com/photo.jpg")).toBe(true);
    });

    it("should reject javascript: and data: URLs", () => {
      expect(viewer.is_safe_url("javascript:alert(1)")).toBe(false);
      expect(viewer.is_safe_url("data:text/html;base64,PHNjcmlwdD4=")).toBe(
        false,
      );
    });

    it("should reject protocol-relative URLs", () => {
      // /^https?:\/\//i does not match //host/path
      expect(viewer.is_safe_url("//evil.com/photo.jpg")).toBe(false);
    });

    it("should reject scheme-less and other-scheme URLs", () => {
      expect(viewer.is_safe_url("example.com/photo.jpg")).toBe(false);
      expect(viewer.is_safe_url("ftp://example.com/photo.jpg")).toBe(false);
      expect(viewer.is_safe_url("")).toBe(false);
    });
  });

  describe("escape_html", () => {
    beforeEach(() => {
      viewer = window.Waymark_Map_Factory.viewer();
    });

    it("should render an <img onerror> payload as inert text", () => {
      const result = viewer.escape_html("<img src=x onerror=alert(1)>");

      expect(result).not.toContain("<img");
      expect(result).not.toContain("<");
      expect(result).toContain("&lt;img");
    });

    it("should escape ampersands", () => {
      const result = viewer.escape_html("Tom & Jerry");

      expect(result).toContain("&amp;");
      expect(result).not.toContain("<");
    });

    it("should not emit raw angle brackets for special characters", () => {
      const result = viewer.escape_html("a < b > c & \"quote\" 'apos'");

      expect(result).not.toContain("<");
      expect(result).not.toContain(">");
      // jQuery's .text()/.html() only escapes &, < and > in text content
      // (sufficient for text-node injection); quotes pass through verbatim.
      expect(result).toContain("&lt;");
      expect(result).toContain("&gt;");
      expect(result).toContain("&amp;");
      expect(result).toContain('"quote"');
      expect(result).toContain("'apos'");
    });
  });

  describe("Tooltip", () => {
    it("should escape a malicious feature title bound to the tooltip", () => {
      viewer = window.Waymark_Map_Factory.viewer();
      viewer.init();

      viewer.load_json({
        type: "FeatureCollection",
        features: [
          {
            type: "Feature",
            geometry: { type: "Point", coordinates: [-85.038, 49.4595] },
            properties: { title: "<img src=x onerror=alert(1)>" },
          },
        ],
      });

      const layer = viewer.map_data.getLayers()[0];
      const tooltip = layer.getTooltip();
      expect(tooltip).toBeDefined();

      const content = tooltip.getContent();
      expect(content).not.toContain("<img");
      expect(content).toContain("&lt;img");
    });
  });

  describe("Viewer popup (build_content)", () => {
    beforeEach(() => {
      viewer = window.Waymark_Map_Factory.viewer();
      viewer.init();
    });

    it("should render the popup title as text (no HTML parsing)", () => {
      const content = viewer.build_content("marker", {
        properties: { title: "<img src=x onerror=alert(1)>" },
      });

      expect(content.find("img").length).toBe(0);
      expect(content.find(".waymark-info-title strong").text()).toBe(
        "<img src=x onerror=alert(1)>",
      );
    });

    it("should sanitize an HTML description before insertion", () => {
      const content = viewer.build_content("marker", {
        properties: {
          description: "<script>alert(1)</script><p>Safe & sound</p>",
        },
      });

      expect(content.find("script").length).toBe(0);
      expect(content.find(".waymark-info-description p").text()).toBe(
        "Safe & sound",
      );
    });

    it("should sanitize a plain-text description containing embedded markup", () => {
      const content = viewer.build_content("marker", {
        properties: { description: "hello <img src=x onerror=alert(1)>" },
      });

      expect(content.find("img").length).toBe(0);
      expect(content.find(".waymark-info-description p").text()).toBe("hello ");
    });

    it("should not emit an image link when the image URL is unsafe", () => {
      const content = viewer.build_content("marker", {
        properties: { image_large_url: "javascript:alert(1)" },
      });

      expect(content.find("a").length).toBe(0);
      expect(content.find(".waymark-info-image_large_url").length).toBe(0);
    });

    it("should emit an image link for a safe https image URL", () => {
      const content = viewer.build_content("marker", {
        properties: { image_large_url: "https://example.com/photo.jpg" },
      });

      const link = content.find(".waymark-info-image_large_url a");
      expect(link.length).toBe(1);
      expect(link.attr("href")).toBe("https://example.com/photo.jpg");
    });
  });

  describe("Overlay content (build_overlay_content)", () => {
    beforeEach(() => {
      // Initialise so the global Waymark always points to a viewer with a
      // jq_map_container set (prevents leaked setup_hidden_checker intervals
      // from crashing on the shared global; see setup_hidden_checker in
      // Waymark_Map_Viewer.js which never clears in jsdom).
      viewer = window.Waymark_Map_Factory.viewer();
      viewer.init();
    });

    it("should escape the overlay title", () => {
      const content = viewer.build_overlay_content(
        { properties: { title: "<img src=x onerror=alert(1)>" } },
        "marker",
        {},
      );

      expect(content).not.toContain("<img");
      expect(content).toContain("&lt;img");
    });

    it("should sanitize the overlay description", () => {
      const content = viewer.build_overlay_content(
        { properties: { description: "<script>alert(1)</script><p>Safe</p>" } },
        "marker",
        {},
      );

      expect(content).not.toContain("<script");
      expect(content).toContain("<p>Safe</p>");
    });

    it("should not emit an overlay image anchor for an unsafe large URL", () => {
      const content = viewer.build_overlay_content(
        { properties: { image_large_url: "javascript:alert(1)" } },
        "marker",
        {},
      );

      expect(content).not.toContain("background-image");
      expect(content).not.toContain('href="javascript');
    });

    it("should fall back to the safe large URL when the thumbnail URL is unsafe", () => {
      const content = viewer.build_overlay_content(
        {
          properties: {
            image_large_url: "https://example.com/large.jpg",
            image_medium_url: "javascript:alert(1)",
          },
        },
        "marker",
        {},
      );

      expect(content).toContain('href="https://example.com/large.jpg"');
      expect(content).toContain(
        "background-image:url(https://example.com/large.jpg)",
      );
      expect(content).not.toContain("javascript:");
    });

    it("should emit an overlay image anchor for safe image URLs", () => {
      const content = viewer.build_overlay_content(
        {
          properties: {
            image_large_url: "https://example.com/large.jpg",
            image_medium_url: "https://example.com/medium.jpg",
          },
        },
        "marker",
        {},
      );

      expect(content).toContain('href="https://example.com/large.jpg"');
      expect(content).toContain(
        "background-image:url(https://example.com/medium.jpg)",
      );
    });
  });
});
