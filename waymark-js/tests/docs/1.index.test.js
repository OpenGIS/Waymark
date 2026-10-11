import { describe, it, expect, beforeEach } from "vitest";

describe("1. Start Here", () => {
  let viewer;

  beforeEach(() => {
    // Setup the DOM as per the Quick Start example
    document.body.innerHTML =
      '<div id="waymark-map" style="height: 500px; width: 500px;"></div>';

    // Mock clientHeight/clientWidth for Leaflet
    const container = document.getElementById("waymark-map");
    Object.defineProperty(container, "clientWidth", { value: 500 });
    Object.defineProperty(container, "clientHeight", { value: 500 });
  });

  describe("Quick Start", () => {
    it("should initialize a map with custom marker and load GeoJSON", () => {
      // Create viewer Instance
      viewer = window.Waymark_Map_Factory.viewer();

      // Initialise with our options
      viewer.init({
        map_options: {
          // Initial Map Zoom
          map_init_zoom: 16,
          map_div_id: "waymark-map", // Explicitly set ID for test environment safety

          // Our Pub Icon
          marker_types: [
            {
              // The Title is used to create the "pub" Type Key
              marker_title: "Pub",
              marker_icon: "ion-beer",
              marker_colour: "brown",
            },
          ],
        },
      });

      // Verify map is initialized
      const mapContainer = document.getElementById("waymark-map");
      expect(mapContainer.classList.contains("leaflet-container")).toBe(true);

      // Verify init_done() was called (which adds this class)
      // This ensures the map is fully initialized and visible
      expect(mapContainer.classList.contains("waymark-is-viewer")).toBe(true);

      // Load GeoJSON
      viewer.load_json({
        type: "FeatureCollection",
        features: [
          {
            type: "Feature",
            properties: {
              type: "pub",
              title: "The Scarlet Ibis",
              description:
                "Great pub, great food! Especially after a Long Ride 🚴🍔🍟🍺🍺💤",
              image_large_url:
                "https://www.ogis.org/waymark-js/assets/img/pub.jpeg",
            },
            geometry: {
              type: "Point",
              coordinates: [-128.0094, 50.6539],
            },
          },
        ],
      });

      // Verify marker is added to the map
      // We can check if a marker icon with the expected class exists
      // Note: Waymark likely adds classes based on the icon type
      const markers = document.querySelectorAll(".leaflet-marker-icon");
      expect(markers.length).toBeGreaterThan(0);
    });
  });
});
