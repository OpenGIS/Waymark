import { beforeEach } from 'vitest';
import fs from 'fs';
import path from 'path';
import $ from 'jquery';

// Attach jQuery to window
window.$ = window.jQuery = $;

// Helper to load script content into global scope
function loadScript(filePath) {
  const content = fs.readFileSync(path.resolve(__dirname, '../', filePath), 'utf8');
  // We use eval to execute the script in the global scope
  // This mimics how the browser executes <script> tags
  window.eval(content);
}

// Load Leaflet (bundled)
// Note: Leaflet might try to access window/document immediately
loadScript('libs/js/leaflet.min.js');

// Restore L for plugins (since Waymark's Leaflet uses noConflict)
if (window.Waymark_L) {
  window.L = window.Waymark_L;
}

// Load Leaflet Plugins & Dependencies
loadScript('libs/js/L.Control.Locate.min.js');
loadScript('libs/js/Control.Geocoder.js');
loadScript('libs/js/leaflet.fullscreen.min.js');
loadScript('libs/js/leaflet.markercluster.js');
loadScript('libs/js/leaflet.polylineDecorator.js');
loadScript('libs/js/Leaflet.Editable.js');
loadScript('libs/js/Leaflet.FeatureGroup.SubGroup.js');
loadScript('libs/js/Leaflet.Sleep.js');
loadScript('libs/js/leaflet-elevation.js');
loadScript('libs/js/togeojson.min.js');
loadScript('libs/js/togpx.js');
loadScript('libs/js/tokml.js');

// Load Waymark Source Files in order
// We use import.meta.glob with ?raw to let Vite know about these dependencies
// This ensures tests re-run when these files change
const sourceFiles = import.meta.glob('../src/js/*.js', { eager: true, query: '?raw', import: 'default' });

// Order matters!
const loadOrder = [
  '../src/js/Waymark_Map.js',
  '../src/js/Waymark_Map_Viewer.js',
  '../src/js/Waymark_Map_Editor.js',
  '../src/js/Waymark_Map_Factory.js'
];

loadOrder.forEach(file => {
  if (sourceFiles[file]) {
    window.eval(sourceFiles[file]);
  } else {
    console.error(`Failed to load ${file}`);
  }
});

// Reset DOM before each test
beforeEach(() => {
  document.body.innerHTML = '';
});
