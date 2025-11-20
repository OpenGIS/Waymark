# Reply Template for GitHub Issue

This file contains a suggested reply to the user's issue about separating export/download from the map.

---

## Suggested Reply

Thank you for your question! You can achieve this by using the `waymark_loaded_callback` function to clone and reposition the export controls. 

I've created comprehensive documentation and ready-to-use code examples for this. Please see:

### 📖 Documentation
**[Separating Export/Download from Map](examples/separate-export-download.md)** - Complete guide with multiple implementation approaches

### 🚀 Quick Start Files
- **[JavaScript File](examples/waymark-export-separation.js)** - Ready-to-use script you can add to your theme
- **[PHP Snippet](examples/functions-php-snippet.php)** - Code for your theme's `functions.php` file
- **[HTML Example](examples/export-separation-example.html)** - Test file to see how it works

### 💡 Basic Implementation

Here's the simplest approach - add this JavaScript to your page/theme:

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
    
    // Update ID to avoid duplicates
    var originalId = clonedExport.attr('id');
    if (originalId) {
        clonedExport.attr('id', originalId + '-cloned');
    }
    
    // Place after the map (or use .before() to place it before)
    mapContainer.after(clonedExport);
    
    // Hide the original
    exportForm.hide();
    
    // Re-initialize export functionality
    waymark_setup_map_export();
}
```

You can also place the export controls in a specific location by creating a container:

```html
<div id="my-export-location"></div>
```

And then targeting it in the callback:

```javascript
jQuery('#my-export-location').html(clonedExport);
```

### 📋 Requirements
- Public Export must be enabled in Waymark Settings > Maps > Misc
- jQuery (included with WordPress)

### 🎨 Optional Styling

Add this CSS to style the separated export controls:

```css
.waymark-export-cloned {
    margin: 20px 0;
    padding: 15px;
    background: #f5f5f5;
    border: 1px solid #ddd;
    border-radius: 4px;
}
```

For more advanced examples (multiple maps, custom styling, sidebar placement, etc.), please see the [complete documentation](examples/separate-export-download.md).

Let me know if you need any clarification or have questions about implementing this!

---

### Related Links
- [Callback Function Documentation](https://www.waymark.dev/docs/callback-function/)
- [Waymark Shortcodes](https://www.waymark.dev/docs/shortcodes/)
- [Waymark Settings](https://www.waymark.dev/docs/settings/)
