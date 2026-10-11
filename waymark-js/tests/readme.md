### Testing

Tests cover the library's documented API chapters, one test file per chapter. Run them from the repository root (`vitest.config.mjs` configures Vitest with the jsdom environment):

```bash
npm run test:js
```

```
tests/
├── setup.js          # Global test setup (loads globals like $, L)
├── docs/
│   ├── 1.index.test.js
│   ├── 2.map.test.js
│   ├── 3.viewer.test.js
│   ├── 4.editor.test.js
│   └── 5.customise.test.js
└── xss.test.js
```

## Handling Legacy Code (Non-Module)

Since the source code (`src/js/*.js`) uses global function declarations and is not modular (ESM), we cannot simply `import` the files.
**Strategy**:

1. In `tests/setup.js`, we will use `fs` to read the source files.
2. We will execute the file contents in the global scope (using `window.eval` or similar) to register `Waymark_Map`, `Waymark_Map_Viewer`, etc., on the `window` object, mimicking the browser behavior.
3. jQuery and Leaflet will be attached to `window` before loading the source code.

## Test-Documentation Synchronization

The chapter numbering keeps the tests aligned with the library's documented API:

1. **Naming Convention**: Each `tests/docs/` file corresponds to one documented API chapter.
2. **Section Mapping**: `describe` blocks in tests match the section headings within their chapter.
   - Example: the Map Options section maps to `describe('Map Options', ...)` in `2.map.test.js`.
3. **Concept Verification**: Tests implement the documented code examples and assert the expected outcome.

> [!NOTE]
> `tests/xss.test.js` is an exception to the 1:1 mirroring: it is a security regression suite covering the popup sanitisation helpers (`sanitize_description`, `escape_html`, `is_safe_url`) and is not mirrored from a doc page.

## Example Test Structure (`tests/docs/2.map.test.js`)

```javascript
import { describe, it, expect, beforeEach } from 'vitest';

describe('2. Map Configuration', () => {
  let waymark;

  beforeEach(() => {
    // Reset DOM
    document.body.innerHTML = '<div id="waymark-map"></div>';
  });

  describe('Basic Initialization', () => {
    it('should initialize the map with default options', () => {
      // Corresponds to basic usage example in docs
      waymark = new Waymark_Map();
      waymark.init({
        map_div_id: 'waymark-map'
      });

      expect(document.querySelector('.leaflet-container')).toBeTruthy();
    });
  });

  describe('Loading Data', () => {
    it('should load GeoJSON data', () => {
      waymark = new Waymark_Map();
      waymark.init();

      const geojson = { ... }; // Example data from docs
      waymark.load_json(geojson);

      // Assertions to verify data is on map
    });
  });
});
```
