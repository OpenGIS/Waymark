import { describe, it, expect, beforeEach, vi } from 'vitest';

describe('4. Editor', () => {
  let editor;

  beforeEach(() => {
    // Setup the DOM
    document.body.innerHTML = '<div id="waymark-map" style="height: 500px; width: 500px;"></div>';
    
    // Mock clientHeight/clientWidth for Leaflet
    const container = document.getElementById('waymark-map');
    Object.defineProperty(container, 'clientWidth', { value: 500 });
    Object.defineProperty(container, 'clientHeight', { value: 500 });
  });

  describe('Creation', () => {
    it('should create an editor instance', () => {
      editor = window.Waymark_Map_Factory.editor();
      expect(editor).toBeDefined();
      editor.init();
      expect(editor.mode).toBe('edit');
    });
  });

  describe('Options', () => {
    it('should initialize with editor options', () => {
      editor = window.Waymark_Map_Factory.editor();
      
      const config = {
        editor_options: {
          confirm_delete: "0",
          data_div_id: "custom-data",
        },
      };

      editor.init(config);

      expect(editor.config.editor_options.confirm_delete).toBe("0");
      expect(editor.config.editor_options.data_div_id).toBe("custom-data");
      
      // Verify data container is created with custom ID
      const dataContainer = document.getElementById('custom-data');
      expect(dataContainer).toBeTruthy();
      expect(dataContainer.tagName).toBe('TEXTAREA');
    });
  });

  describe('Loading Data', () => {
    it('should load GeoJSON data and update data container', () => {
      editor = window.Waymark_Map_Factory.editor();
      editor.init();

      const geojson = {
        type: "FeatureCollection",
        features: [
          {
            geometry: { type: "Point", coordinates: [-85.038, 49.4595] },
            type: "Feature",
            properties: { type: "food" },
          },
        ],
      };

      editor.load_json(geojson);
      
      // Verify data is loaded into map_data layer
      expect(editor.map_data.getLayers().length).toBe(1);
      
      // Verify data container is updated
      const dataContainer = document.getElementById('waymark-data');
      expect(dataContainer).toBeTruthy();
      
      // The editor uses .html() to set the content of the textarea
      // In JSDOM/jQuery, .html() on a textarea sets its innerHTML/value?
      // Let's check the value or innerHTML
      const val = dataContainer.value || dataContainer.innerHTML;
      const parsed = JSON.parse(val);
      expect(parsed.features.length).toBe(1);
      expect(parsed.features[0].properties.type).toBe("food");
    });

    it('should load GeoJSON asynchronously (mocked fetch)', async () => {
      editor = window.Waymark_Map_Factory.editor();
      editor.init();

      const geojson = {
        type: "FeatureCollection",
        features: [
          {
            geometry: { type: "Point", coordinates: [-85.038, 49.4595] },
            type: "Feature",
            properties: { type: "food" },
          },
        ],
      };
      
      global.fetch = vi.fn().mockResolvedValue({
        json: () => Promise.resolve(geojson)
      });

      // Simulate the fetch pattern from docs
      const response = await fetch("route.geojson");
      const data = await response.json();
      editor.load_json(data);
      
      expect(editor.map_data.getLayers().length).toBe(1);
    });
    
    it('should load existing data from data container on init', () => {
      // Setup DOM with existing data
      const existingData = {
        type: "FeatureCollection",
        features: [
          {
            type: "Feature",
            geometry: { type: "Point", coordinates: [-85.038, 49.4595] },
            properties: { type: "food" }
          }
        ]
      };
      
      document.body.innerHTML = `
        <div id="waymark-map" style="height: 500px; width: 500px;"></div>
        <textarea id="waymark-data">${JSON.stringify(existingData)}</textarea>
      `;
      
      // Mock dimensions again since we reset innerHTML
      const container = document.getElementById('waymark-map');
      Object.defineProperty(container, 'clientWidth', { value: 500 });
      Object.defineProperty(container, 'clientHeight', { value: 500 });

      editor = window.Waymark_Map_Factory.editor();
      editor.init();
      
      // Verify data is loaded
      expect(editor.map_data.getLayers().length).toBe(1);
    });
  });

  describe('Retrieving Data', () => {
    it('should retrieve GeoJSON data', () => {
      editor = window.Waymark_Map_Factory.editor();
      editor.init();

      const geojson = {
        type: "FeatureCollection",
        features: [
          {
            geometry: { type: "Point", coordinates: [-85.038, 49.4595] },
            type: "Feature",
            properties: { type: "food" },
          },
        ],
      };

      editor.load_json(geojson);
      
      const exported = editor.map_data.toGeoJSON();
      expect(exported.features.length).toBe(1);
      expect(exported.features[0].properties.type).toBe("food");
    });
  });
});
