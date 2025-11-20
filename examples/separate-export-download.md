# Separating Export/Download from the Map

This example demonstrates how to separate the export and download options from the Waymark map and place them elsewhere on your page (e.g., below or next to the map).

## Overview

By default, Waymark's export feature appears within the map's interface. Using the `waymark_loaded_callback` function, you can clone the export controls and position them anywhere on your page.

## Implementation

### Step 1: Add the Callback Function

Add this JavaScript code to your theme's JavaScript file, or in a custom JavaScript file, or directly in your page/post (within `<script>` tags):

```javascript
/**
 * Global callback function that runs after Waymark has loaded
 * This function is called for every Waymark map instance on the page
 * 
 * @param {Object} Waymark - The Waymark instance for the map
 */
function waymark_loaded_callback(Waymark) {
    // Only process if we have a valid Waymark instance
    if (!Waymark || !Waymark.map) {
        return;
    }
    
    // Find the export form within this map instance
    var mapContainer = jQuery(Waymark.map.getContainer()).closest('.waymark-map');
    var exportForm = mapContainer.find('.waymark-map-export, .waymark-collection-export').first();
    
    if (exportForm.length === 0) {
        console.log('No export form found for this map');
        return;
    }
    
    // Clone the export form
    var clonedExport = exportForm.clone(true, true);
    
    // Add a custom class to identify the cloned export
    clonedExport.addClass('waymark-export-cloned');
    
    // Update the ID to avoid duplicates
    var originalId = clonedExport.attr('id');
    if (originalId) {
        clonedExport.attr('id', originalId + '-cloned');
    }
    
    // Option 1: Insert the cloned export after the map
    mapContainer.after(clonedExport);
    
    // Option 2: Insert into a specific container (uncomment and adjust selector as needed)
    // jQuery('#my-custom-export-location').html(clonedExport);
    
    // Option 3: Insert before the map (uncomment to use)
    // mapContainer.before(clonedExport);
    
    // Hide the original export form (optional)
    exportForm.hide();
    // Or remove it completely (uncomment to use)
    // exportForm.remove();
    
    // Re-initialize the export functionality for the cloned form
    waymark_setup_map_export();
}
```

### Step 2: Add Custom Styling (Optional)

You can add CSS to style your separated export controls. Add this to your theme's CSS file or in a Custom CSS section:

```css
/* Style the cloned export form */
.waymark-export-cloned {
    margin: 20px 0;
    padding: 15px;
    background: #f5f5f5;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.waymark-export-cloned select {
    margin-right: 10px;
    padding: 8px;
}

.waymark-export-cloned .button {
    padding: 8px 16px;
    background: #0073aa;
    color: white;
    border: none;
    border-radius: 3px;
    cursor: pointer;
}

.waymark-export-cloned .button:hover {
    background: #005177;
}
```

### Step 3: Place Export in a Specific Container

If you want to place the export controls in a specific location on your page, create a container element:

```html
<!-- In your page/post content or template -->
<div id="my-custom-export-location">
    <!-- Export controls will be inserted here -->
</div>
```

Then use Option 2 in the callback function to target that specific container.

## Advanced Examples

### Example 1: Separate Export for Each Map

If you have multiple maps on one page and want separate export controls for each:

```javascript
function waymark_loaded_callback(Waymark) {
    if (!Waymark || !Waymark.map) {
        return;
    }
    
    var mapContainer = jQuery(Waymark.map.getContainer()).closest('.waymark-map');
    var exportForm = mapContainer.find('.waymark-map-export, .waymark-collection-export').first();
    
    if (exportForm.length === 0) {
        return;
    }
    
    // Get the map ID from the container
    var mapId = mapContainer.data('map-id') || mapContainer.attr('id');
    
    // Clone and customize
    var clonedExport = exportForm.clone(true, true);
    clonedExport.addClass('waymark-export-cloned waymark-export-map-' + mapId);
    
    // Create a wrapper with a label
    var exportWrapper = jQuery('<div class="waymark-export-wrapper"></div>');
    exportWrapper.append('<h3>Download Map Data</h3>');
    exportWrapper.append(clonedExport);
    
    // Insert after the map
    mapContainer.after(exportWrapper);
    
    // Hide original
    exportForm.hide();
    
    // Re-initialize
    waymark_setup_map_export();
}
```

### Example 2: Add Custom Text/Instructions

Add helpful text or instructions with your export controls:

```javascript
function waymark_loaded_callback(Waymark) {
    if (!Waymark || !Waymark.map) {
        return;
    }
    
    var mapContainer = jQuery(Waymark.map.getContainer()).closest('.waymark-map');
    var exportForm = mapContainer.find('.waymark-map-export, .waymark-collection-export').first();
    
    if (exportForm.length === 0) {
        return;
    }
    
    // Clone the export form
    var clonedExport = exportForm.clone(true, true);
    clonedExport.addClass('waymark-export-cloned');
    
    // Create a custom container with instructions
    var customContainer = jQuery('<div class="waymark-custom-export"></div>');
    customContainer.append('<h3>Download Options</h3>');
    customContainer.append('<p>Download the map data in your preferred format:</p>');
    customContainer.append('<ul><li><strong>GPX</strong> - GPS Exchange Format (for GPS devices)</li><li><strong>KML</strong> - Keyhole Markup Language (for Google Earth)</li><li><strong>GeoJSON</strong> - Geographic JSON (for web applications)</li></ul>');
    customContainer.append(clonedExport);
    
    // Insert after the map
    mapContainer.after(customContainer);
    
    // Hide original
    exportForm.hide();
    
    // Re-initialize
    waymark_setup_map_export();
}
```

### Example 3: Sidebar Placement

Place the export controls in a sidebar next to the map:

```html
<!-- HTML Structure -->
<div class="map-and-sidebar">
    <div class="map-column">
        <!-- Your Waymark shortcode goes here -->
        [Waymark map_id="123"]
    </div>
    <div class="sidebar-column">
        <div id="export-sidebar">
            <!-- Export will be inserted here -->
        </div>
    </div>
</div>
```

```javascript
function waymark_loaded_callback(Waymark) {
    if (!Waymark || !Waymark.map) {
        return;
    }
    
    var mapContainer = jQuery(Waymark.map.getContainer()).closest('.waymark-map');
    var exportForm = mapContainer.find('.waymark-map-export, .waymark-collection-export').first();
    
    if (exportForm.length === 0) {
        return;
    }
    
    // Clone and place in sidebar
    var clonedExport = exportForm.clone(true, true);
    clonedExport.addClass('waymark-export-cloned');
    
    jQuery('#export-sidebar').html(clonedExport);
    
    // Hide original
    exportForm.hide();
    
    // Re-initialize
    waymark_setup_map_export();
}
```

```css
.map-and-sidebar {
    display: flex;
    gap: 20px;
}

.map-column {
    flex: 1;
}

.sidebar-column {
    width: 300px;
}

#export-sidebar {
    padding: 20px;
    background: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 4px;
}
```

## Troubleshooting

### Export form not found
- Make sure public export is enabled in Waymark Settings
- Check that you're using the correct map shortcode
- Verify jQuery is loaded on your page

### Export functionality doesn't work after cloning
- Make sure to call `waymark_setup_map_export()` after cloning
- Ensure you're cloning with `clone(true, true)` to copy event handlers

### Multiple maps on one page
- Use unique IDs or classes to target specific maps
- Consider using data attributes to identify maps

## Requirements

- Waymark plugin installed and activated
- Public Export enabled in Waymark Settings > Maps > Misc
- jQuery (included with WordPress)

## Related Documentation

- [Waymark Callback Function Documentation](https://www.waymark.dev/docs/callback-function/)
- [Waymark Shortcodes](https://www.waymark.dev/docs/shortcodes/)
- [Waymark Settings](https://www.waymark.dev/docs/settings/)

## Notes

- The `waymark_loaded_callback` function is called automatically when Waymark loads
- This function is called for each Waymark instance on the page
- The export form must exist (i.e., public export must be enabled) for this to work
- Changes made to the original export form may require updates to this code

## Support

If you need further assistance, please visit:
- [Waymark Support Forum](https://wordpress.org/support/plugin/waymark/)
- [Waymark Documentation](https://www.waymark.dev/docs/)
- [GitHub Issues](https://github.com/OpenGIS/Waymark/issues)
