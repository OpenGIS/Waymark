/**
 * Waymark Export Separation Script
 * 
 * This script separates the export/download controls from the Waymark map
 * and places them elsewhere on the page.
 * 
 * Usage:
 * 1. Save this file to your theme's js directory (e.g., /wp-content/themes/your-theme/js/waymark-export-separation.js)
 * 2. Enqueue it in your theme's functions.php (see functions-php-snippet.php for examples)
 * 3. Customize the placement options below as needed
 * 
 * @version 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Configuration options
     * Customize these to control how the export separation works
     */
    var config = {
        // Where to place the cloned export form
        // Options: 'after' (after map), 'before' (before map), 'custom' (custom selector)
        placement: 'after',
        
        // If placement is 'custom', specify the jQuery selector for the target container
        customContainer: '#my-custom-export-location',
        
        // Whether to hide or remove the original export form
        // Options: 'hide', 'remove'
        hideOriginal: 'hide',
        
        // Whether to wrap the cloned export in a custom container
        wrapCloned: true,
        
        // Custom wrapper HTML (only used if wrapCloned is true)
        wrapperHTML: '<div class="waymark-export-wrapper"><h3>Download Map Data</h3></div>',
        
        // Additional custom class to add to the cloned export
        customClass: 'waymark-export-cloned',
        
        // Enable console logging for debugging
        debug: false
    };

    /**
     * Log messages if debug mode is enabled
     */
    function log(message) {
        if (config.debug && console && console.log) {
            console.log('[Waymark Export Separation] ' + message);
        }
    }

    /**
     * Global callback function that Waymark calls when a map is loaded
     * This function is called for each Waymark map instance
     * 
     * @param {Object} Waymark - The Waymark instance
     */
    window.waymark_loaded_callback = function(Waymark) {
        log('Callback triggered');
        
        // Validate Waymark instance
        if (!Waymark || !Waymark.map) {
            log('Invalid Waymark instance');
            return;
        }
        
        log('Processing map instance');
        
        // Find the map container and export form
        var mapContainer = $(Waymark.map.getContainer()).closest('.waymark-map');
        var exportForm = mapContainer.find('.waymark-map-export, .waymark-collection-export').first();
        
        // Check if export form exists
        if (exportForm.length === 0) {
            log('No export form found - ensure Public Export is enabled in Waymark Settings');
            return;
        }
        
        log('Export form found, processing...');
        
        // Clone the export form with all data and event handlers
        var clonedExport = exportForm.clone(true, true);
        
        // Add custom class
        if (config.customClass) {
            clonedExport.addClass(config.customClass);
        }
        
        // Update ID to avoid conflicts
        var originalId = clonedExport.attr('id');
        if (originalId) {
            clonedExport.attr('id', originalId + '-cloned');
        }
        
        // Wrap in custom container if configured
        if (config.wrapCloned && config.wrapperHTML) {
            var wrapper = $(config.wrapperHTML);
            wrapper.append(clonedExport);
            clonedExport = wrapper;
        }
        
        // Place the cloned export based on configuration
        switch (config.placement) {
            case 'before':
                mapContainer.before(clonedExport);
                log('Export placed before map');
                break;
                
            case 'custom':
                if ($(config.customContainer).length > 0) {
                    $(config.customContainer).html(clonedExport);
                    log('Export placed in custom container: ' + config.customContainer);
                } else {
                    log('Custom container not found: ' + config.customContainer + ', falling back to after map');
                    mapContainer.after(clonedExport);
                }
                break;
                
            case 'after':
            default:
                mapContainer.after(clonedExport);
                log('Export placed after map');
                break;
        }
        
        // Hide or remove the original export form
        if (config.hideOriginal === 'remove') {
            exportForm.remove();
            log('Original export removed');
        } else {
            exportForm.hide();
            log('Original export hidden');
        }
        
        // Re-initialize the export functionality
        // This ensures the cloned form works correctly
        if (typeof waymark_setup_map_export === 'function') {
            waymark_setup_map_export();
            log('Export functionality re-initialized');
        } else {
            log('Warning: waymark_setup_map_export function not found');
        }
        
        log('Export separation complete');
    };
    
    log('Script loaded and callback registered');
    
})(jQuery);
