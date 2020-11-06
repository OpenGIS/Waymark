<?php

class Waymark_Meta {
	
	private $Waymark_Object;
	
	function __construct() {
		//Map
		add_meta_box('waymark_map_meta', esc_html__('Map Editor', 'waymark-plugin'), array($this, 'get_map_form'), 'waymark_map', 'normal', 'high');			

		//Map
		add_meta_box('waymark_map_shortcode', 'Shortcode', array($this, 'map_shortcode_content'), 'waymark_map', 'side', 'default');			
		add_meta_box('waymark_map_export', 'Export', array($this, 'map_export_content'), 'waymark_map', 'side', 'default');			
		add_meta_box('waymark_map_help', 'Help', array($this, 'map_help_content'), 'waymark_map', 'side', 'default');			


		add_filter('mime_types', array($this, 'mime_types'));	
		add_action('post_edit_form_tag', array($this, 'add_post_enctype'));
		add_action('post_updated', array($this, 'post_updated'), 10, 2);			
	
		//Waymark JS
		//CSS
		wp_register_style('waymark-js', Waymark_Helper::asset_url('dist/waymark-js/css/waymark-js.min.css'), array(), Waymark_Config::get_version());
		wp_enqueue_style('waymark-js');		
		//JS
		wp_register_script('waymark-js', Waymark_Helper::asset_url('dist/waymark-js/js/waymark-js.min.js'), array('jquery'), Waymark_Config::get_version());
		//Localize
		wp_localize_script('waymark-js', 'waymark_js_lang', array(
			//Viewer
			'action_fullscreen_activate' => esc_attr__('View Fullscreen', 'waymark-plugin'),		
			'action_fullscreen_deactivate' => esc_attr__('Exit Fullscreen', 'waymark-plugin'),		
			'action_locate_activate' => esc_attr__('Show me where I am', 'waymark-plugin'),		
			'action_zoom_in' => esc_attr__('Zoom in', 'waymark-plugin'),		
			'action_zoom_out' => esc_attr__('Zoom out', 'waymark-plugin'),
			//Editor
			'add_line_title' => esc_attr__('Draw a Line', 'waymark-plugin'),
			'add_photo_title' => esc_attr__('Upload a Photo', 'waymark-plugin'),
			'add_marker_title' => esc_attr__('Place a Marker', 'waymark-plugin'),
			'add_rectangle_title' => esc_attr__('Draw a Rectangle', 'waymark-plugin'),
			'add_polygon_title' => esc_attr__('Draw a Polygon', 'waymark-plugin'),
			'add_circle_title' => esc_attr__('Draw a Circle', 'waymark-plugin'),
			'upload_file_title' => esc_attr__('Read Lines and Markers from file (GPX/KML/GeoJSON supported, which most apps should Export to)', 'waymark-plugin'),
			'action_delete' => esc_attr__('Delete', 'waymark-plugin'),
			'action_edit' => esc_attr__('Edit', 'waymark-plugin'),
			'action_edit_done' => esc_attr__('Finish editing', 'waymark-plugin'),		
			'action_upload_image' => esc_attr__('Upload Image', 'waymark-plugin'),
			'object_title_placeholder' => esc_attr__('Title', 'waymark-plugin'),
			'object_image_placeholder' => esc_attr__('Image URL', 'waymark-plugin'),
			'object_description_placeholder' => esc_attr__('Description', 'waymark-plugin'),
			'object_type_label' => esc_attr__('Type', 'waymark-plugin'),
			'marker_latlng_label' => esc_attr__('Lat,Lng', 'waymark-plugin'),
			'action_delete_confirm' => esc_attr__('Are you sure you want to delete this', 'waymark-plugin'),		
			'action_search_placeholder' => esc_attr__('Search...', 'waymark-plugin'),		
			'object_label_marker' => esc_attr__('Marker', 'waymark-plugin'),		
			'object_label_line' => esc_attr__('Line', 'waymark-plugin'),		
			'object_label_shape' => esc_attr__('Shape', 'waymark-plugin'),	
			'object_label_marker_plural' => esc_attr__('Markers', 'waymark-plugin'),		
			'object_label_line_plural' => esc_attr__('Lines', 'waymark-plugin'),		
			'object_label_shape_plural' => esc_attr__('Shapes', 'waymark-plugin'),					
			'error_message_prefix' => esc_attr__('Waymark Error', 'waymark-plugin'),		
			'info_message_prefix' => esc_attr__('Waymark Info', 'waymark-plugin'),		
			'error_file_type' => esc_attr__('This file type is not supported.', 'waymark-plugin'),		
			'error_file_conversion' => esc_attr__('Could not convert this file to GeoJSON.', 'waymark-plugin'),		
			'error_file_upload' => esc_attr__('File upload error.', 'waymark-plugin'),		
			'error_photo_meta' => esc_attr__('Could not retrieve Photo metadata.', 'waymark-plugin')
		));
		wp_enqueue_script('waymark-js');		
	}

	function add_post_enctype() {
		global $post;

		if(in_array($post->post_type, array('waymark_map'))) {
			echo ' enctype="multipart/form-data"';			
		}
	}	

	function mime_types($existing_mimes) {
		$existing_mimes['gpx'] = 'application/gpx+xml';
		$existing_mimes['kml'] = 'application/vnd.google-earth.kml+xml';
		$existing_mimes['kmz'] = 'application/vnd.google-earth.kmz';
		$existing_mimes['json'] = 'application/geo+json';
		$existing_mimes['geojson'] = 'application/geo+json';

		return $existing_mimes;
	}
	
	function post_updated() {
		global $post;
			
		if(is_object($post) && ! (wp_is_post_revision($post->ID) || wp_is_post_autosave($post->ID))) {
			switch($post->post_type) {
				//Map
				case 'waymark_map' :									
					$Map = new Waymark_Map;
					$Map->set_data($_POST);				
					$Map->save_meta($post->ID);
					
					break;			
			}			
		}
	}

	function map_export_content() {	
		global $post;
		
		$Map = new Waymark_Map($post->ID);
		
		$has_features = array_key_exists('map_data', $Map->data) && Waymark_Helper::geojson_feature_count($Map->data['map_data']);
		if($has_features) {			
			echo '<a data-title="' . esc_attr__('Download the Overlays added to this Map in the selected format.', 'waymark-plugin') . '" href="#" onclick="return false;" class="waymark-tooltip">?</a>';
			echo Waymark_Helper::map_export_html($Map);
		}
	}
	
	function map_shortcode_content() {	
		global $post;
		
		//Shortcode output
		echo '<a data-title="' . esc_attr__('Add this Map to your content with this Shortcode. Click here for more details.', 'waymark-plugin') . '" href="' . Waymark_Helper::site_url('docs/shortcodes') . '" class="waymark-tooltip">?</a>';
		echo '<input type="text" value="[' . Waymark_Config::get_item('shortcode') . ' map_id=&quot;' . $post->ID . '&quot;]" />';
		//echo '<p>' . sprintf(__('Add this Map to your content with this <a href="%s">Shortcode</a>.', 'waymark-plugin'), ) . '</p>';
	}

	function map_help_content() {	
		//Required the PHP EXIF extension
		if(! function_exists('exif_read_data')) {
			echo '<p><b>' . esc_html__('Photo Location Detection Not Supported!', 'waymark-plugin') . '</b></p>';
			echo '<p>' . __(sprintf('Your hosting environment does not currently have the <a href="%s">PHP EXIF Extension</a> enabled, which is required to read Photo location metadata. Try asking your host to enable it.', 'https://www.php.net/manual/en/book.exif.php'), 'waymark-plugin') . '</p>';		
		}	

		echo '<p><b>' . esc_html__('Read from File', 'waymark-plugin') . '</b></p>';
		echo '<p>' . esc_html__('You can read Lines and Markers from GPX, KML and GeoJSON files (most mapping apps will be able to export to one of these).') . '</p>';

		echo '<p><b>' . esc_html__('Types', 'waymark-plugin') . '</b></p>';
		echo '<p>' . __(sprintf('Types allow you to control how Overlays (Markers, Lines and Shapes) are displayed on the Map. Types can be customised in <a href="%s" target="_blank">Waymark &gt; Settings</a>.', admin_url('edit.php?post_type=waymark_map&page=waymark-settings&tab=markers')), 'waymark-plugin') . '</p>';		

		echo '<p><b>' . esc_html__('Basemap', 'waymark-plugin') . '</b></p>';
		echo '<p>' . __(sprintf('Add and edit Basemaps in <a href="%s" target="_blank">Waymark &gt; Settings</a>. The first listed will be used as the default, unless specified in the shortcode like this: %s', admin_url('edit.php?post_type=waymark_map&page=waymark-settings&tab=tiles'), '<code>[Waymark map_id="1234" basemap="Basemap Name"]</code>'), 'waymark-plugin') . '</p>';		

		echo '<p><a class="button" href="' . Waymark_Helper::site_url('docs') . '" target="_blank">' . esc_html__('Read the Docs', 'waymark-plugin') . ' &raquo;</a></p>';

		echo '<p>&nbsp;</p>';
	}

	function get_map_form() {	
		//WP Media
		wp_enqueue_media();
		
		global $post;

		$data = Waymark_Helper::flatten_meta(get_post_meta($post->ID));
								
		//Create new Map object
		Waymark_JS::add_call('var Waymark_Map_Editor = window.Waymark_Map_Factory.editor()');

		//Default view
		if($default_latlng = Waymark_Config::get_setting('misc', 'map_options', 'map_default_latlng')) {
			//We have a valid LatLng
			if($default_latlng_array = Waymark_Helper::latlng_string_to_array($default_latlng)) {
		 		Waymark_JS::add_call('Waymark_Map_Editor.fallback_latlng = [' . $default_latlng_array[0] . ',' . $default_latlng_array[1] . ']');					
			}
		}
		if($default_zoom = Waymark_Config::get_setting('misc', 'map_options', 'map_default_zoom')) {
	 		Waymark_JS::add_call('Waymark_Map_Editor.fallback_zoom = ' . $default_zoom);		
		}

		//Map Div
		echo '<div id="waymark-map"></div>' . "\n";
		
		//Output Config
		Waymark_JS::add_call('var waymark_user_config = ' . json_encode(Waymark_Config::get_map_config()) . ';');				
		Waymark_JS::add_call('waymark_user_config.map_height = 600;');				
		
		//Set basemap
		if($editor_basemap = Waymark_Config::get_setting('misc', 'editor_options', 'editor_basemap')) {
			Waymark_JS::add_call('waymark_user_config.map_init_basemap = "' . $editor_basemap . '"');		
		}

		//Go!
		Waymark_JS::add_call('Waymark_Map_Editor.init(waymark_user_config)');

		//GeoJSON set?
		if(array_key_exists('waymark_map_data', $data)) {
			Waymark_JS::add_call('Waymark_Map_Editor.load_json(' . $data['waymark_map_data'] . ');');			
		}

		$map_details_link = (get_post_status($post) == 'publish') ? get_permalink($post->ID) : 'https://www.joesway.ca/map/route-map';

		//Create Feed meta input
		$Map = new Waymark_Map;		
		$Map->set_data($data);
		$Map->set_input_type('meta');
		echo $Map->create_form();		

		echo '<p>' . __(sprintf('You can manage Meta fields in <a href="%s">Settings</a>.', admin_url('edit.php?post_type=waymark_map&page=waymark-settings&tab=meta')), 'waymark-plugin') . '</p>';		
	}
}
new Waymark_Meta;
