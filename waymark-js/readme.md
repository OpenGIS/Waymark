# Waymark JS

Waymark JS powers the Viewer and Editor in the [Waymark](https://github.com/opengis/waymark) WordPress plugin. The library lives in this directory as part of the plugin, developed and maintained in this repository.

It was vendored from [OpenGIS/Waymark-JS](https://github.com/OpenGIS/Waymark-JS/) at commit `2cc3882f` (v1.2.10); upstream development has since moved on to a new major version, and the v1 line continues here.

## Layout

- `src/` — library sources: `src/js/` (JavaScript) and `src/less/` (stylesheets, compiled to `src/css/`)
- `libs/` — third-party dependencies bundled into the built files (Leaflet, fonts, images)
- `dist/` — build output shipped with the plugin: `waymark-js.js`, `waymark-js.min.js`, `waymark-js.css`, `waymark-js.min.css`, plus fonts and images

## Build

Built through the plugin's root Grunt toolchain, from the repository root:

    npm run grunt          # full build (all plugin assets, including Waymark JS)
    npx grunt build_wj_js  # Waymark JS JavaScript only
    npx grunt build_wj_css # Waymark JS CSS only
    npm run grunt:watch    # watch and rebuild

## Tests

    npm run test:js

Unit tests (Vitest with jsdom) live in `tests/`. They load the library sources into a simulated browser environment, covering the documented API and description sanitisation.

## License

GNU General Public License v2 — see the plugin's [LICENSE.txt](../LICENSE.txt).
