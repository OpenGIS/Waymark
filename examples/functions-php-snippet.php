<?php
/**
 * Waymark Export Separation - WordPress Theme Integration
 * 
 * Add this code to your theme's functions.php file to separate
 * the export/download controls from the Waymark map.
 * 
 * This enqueues the JavaScript that implements the export separation
 * using the waymark_loaded_callback function.
 */

/**
 * Enqueue the export separation script
 */
function waymark_enqueue_export_separation_script() {
    // Only load on pages that have Waymark maps
    // You can customize this condition based on your needs
    
    // Example 1: Load on all pages (simple approach)
    // wp_enqueue_script('waymark-export-separation', get_stylesheet_directory_uri() . '/js/waymark-export-separation.js', array('jquery'), '1.0', true);
    
    // Example 2: Load only on specific pages or posts
    // if (is_page('your-map-page') || is_single('your-map-post')) {
    //     wp_enqueue_script('waymark-export-separation', get_stylesheet_directory_uri() . '/js/waymark-export-separation.js', array('jquery'), '1.0', true);
    // }
    
    // Example 3: Load only when Waymark shortcode is detected in content
    // Note: Waymark uses 'Waymark' (capital W) as the shortcode name
    global $post;
    if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'Waymark')) {
        wp_enqueue_script('waymark-export-separation', get_stylesheet_directory_uri() . '/js/waymark-export-separation.js', array('jquery'), '1.0', true);
    }
}
add_action('wp_enqueue_scripts', 'waymark_enqueue_export_separation_script');

/**
 * Alternative: Inline script approach
 * 
 * If you prefer not to create a separate JS file, you can add the script inline.
 * Comment out the function above and uncomment this one:
 */
/*
function waymark_add_export_separation_inline_script() {
    // Only run on pages with Waymark content
    global $post;
    if (!is_a($post, 'WP_Post') || !has_shortcode($post->post_content, 'Waymark')) {
        return;
    }
    ?>
    <script type="text/javascript">
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
        
        // Insert after the map
        mapContainer.after(clonedExport);
        
        // Hide original
        exportForm.hide();
        
        // Re-initialize export functionality
        if (typeof waymark_setup_map_export === 'function') {
            waymark_setup_map_export();
        }
    }
    </script>
    <?php
}
add_action('wp_footer', 'waymark_add_export_separation_inline_script');
*/

/**
 * Add custom CSS for the separated export form
 */
function waymark_export_separation_styles() {
    global $post;
    if (!is_a($post, 'WP_Post') || !has_shortcode($post->post_content, 'Waymark')) {
        return;
    }
    ?>
    <style type="text/css">
    .waymark-export-cloned {
        margin: 20px 0;
        padding: 15px;
        background: #f5f5f5;
        border: 1px solid #ddd;
        border-radius: 4px;
        text-align: left;
    }

    .waymark-export-cloned select {
        margin-right: 10px;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 3px;
    }

    .waymark-export-cloned .button,
    .waymark-export-cloned input[type="submit"] {
        padding: 8px 16px;
        background: #0073aa;
        color: white;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        font-size: 14px;
    }

    .waymark-export-cloned .button:hover,
    .waymark-export-cloned input[type="submit"]:hover {
        background: #005177;
    }
    </style>
    <?php
}
add_action('wp_head', 'waymark_export_separation_styles');

/**
 * Alternative approach: Using a custom container
 * 
 * If you want to place the export in a specific location,
 * you can add a container to your page and target it:
 */
/*
function waymark_add_export_container() {
    // Only show on pages with Waymark content
    global $post;
    if (!is_a($post, 'WP_Post') || !has_shortcode($post->post_content, 'Waymark')) {
        return;
    }
    ?>
    <div id="waymark-custom-export-location" class="waymark-export-container">
        <h3><?php _e('Download Map Data', 'your-theme'); ?></h3>
        <!-- Export controls will be inserted here by the callback -->
    </div>
    <?php
}
// Add this to your theme template where you want the export to appear
// Or use a shortcode to place it anywhere:
function waymark_export_container_shortcode() {
    ob_start();
    waymark_add_export_container();
    return ob_get_clean();
}
add_shortcode('waymark_export_location', 'waymark_export_container_shortcode');
*/
