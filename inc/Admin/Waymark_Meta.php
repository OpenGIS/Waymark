<?php

class Waymark_Meta {
	
	//private $Waymark_Object;
	private $current_screen;
	
	function __construct() {
		add_action('current_screen', array($this, 'current_screen'));	

		add_action('post_edit_form_tag', array($this, 'add_post_enctype'));
		add_action('post_updated', array($this, 'post_updated'), 10, 2);			
	
		//Waymark JS
		//CSS
		wp_register_style('waymark-js', Waymark_Helper::asset_url('dist/waymark-js/css/waymark-js.min.css'), array(), Waymark_Config::get_version());
		wp_enqueue_style('waymark-js');		
		//JS
		wp_register_script('waymark-js', Waymark_Helper::asset_url('dist/waymark-js/js/waymark-js.min.js'), array('jquery'), Waymark_Config::get_version());
		//Localize
		wp_localize_script('waymark-js', 'waymark_js', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'lang' => array(
				//Viewer
				'action_fullscreen_activate' => esc_attr__('View Fullscreen', 'waymark'),		
				'action_fullscreen_deactivate' => esc_attr__('Exit Fullscreen', 'waymark'),		
				'action_locate_activate' => esc_attr__('Show me where I am', 'waymark'),		
				'action_zoom_in' => esc_attr__('Zoom in', 'waymark'),		
				'action_zoom_out' => esc_attr__('Zoom out', 'waymark'),
				//Editor
				'add_line_title' => esc_attr__('Draw a Wine', 'waymark'),
				'add_photo_title' => esc_attr__('Upload an Image', 'waymark'),
				'add_marker_title' => esc_attr__('Place a Marker', 'waymark'),
				'add_rectangle_title' => esc_attr__('Draw a Rectangle', 'waymark'),
				'add_polygon_title' => esc_attr__('Draw a Polygon', 'waymark'),
				'add_circle_title' => esc_attr__('Draw a Circle', 'waymark'),
				'upload_file_title' => esc_attr__('Read Lines and Markers from file (GPX/KML/GeoJSON supported, which most apps should Export to)', 'waymark'),
				'action_duplicate' => esc_attr__('Duplicate', 'waymark'),
				'action_delete' => esc_attr__('Delete', 'waymark'),
				'action_edit' => esc_attr__('Edit', 'waymark'),
				'action_edit_done' => esc_attr__('Finish editing', 'waymark'),		
				'action_upload_image' => esc_attr__('Upload Image', 'waymark'),
				'object_title_placeholder' => esc_attr__('Title', 'waymark'),
				'object_image_placeholder' => esc_attr__('Image URL', 'waymark'),
				'object_description_placeholder' => esc_attr__('Description', 'waymark'),
				'object_type_label' => esc_attr__('Type', 'waymark'),
				'marker_latlng_label' => esc_attr__('Lat,Lng', 'waymark'),
				'action_delete_confirm' => esc_attr__('Are you sure you want to delete this', 'waymark'),		
				'action_search_placeholder' => esc_attr__('Search...', 'waymark'),		
				'object_label_marker' => esc_attr__('Marker', 'waymark'),		
				'object_label_line' => esc_attr__('Line', 'waymark'),		
				'object_label_shape' => esc_attr__('Shape', 'waymark'),	
				'object_label_marker_plural' => esc_attr__('Markers', 'waymark'),		
				'object_label_line_plural' => esc_attr__('Lines', 'waymark'),		
				'object_label_shape_plural' => esc_attr__('Shapes', 'waymark'),					
				'error_message_prefix' => esc_attr__('Waymark Error', 'waymark'),		
				'info_message_prefix' => esc_attr__('Waymark Info', 'waymark'),		
				'error_file_type' => esc_attr__('This file type is not supported.', 'waymark'),		
				'error_file_conversion' => esc_attr__('Could not convert this file to GeoJSON.', 'waymark'),		
				'error_file_upload' => esc_attr__('File upload error.', 'waymark'),		
				'error_photo_meta' => esc_attr__('Could not retrieve Image metadata.', 'waymark'),
				'info_exif_yes' => esc_attr__('Image location metadata (EXIF) detected!', 'waymark'),
				'info_exif_no' => esc_attr__('Image location metadata (EXIF) NOT detected.', 'waymark')
			)
		));
		wp_enqueue_script('waymark-js');		
	}
	
	function current_screen() {

		if(function_exists('get_current_screen')) {  
			$this->current_screen = get_current_screen();
			
			switch($this->current_screen->post_type) {
				//Map
				case 'waymark_map' :									
					add_meta_box('waymark_map_meta', esc_html__('Map Editor', 'waymark'), array($this, 'get_map_form'), 'waymark_map', 'normal', 'high');			

					break;

				//Query
				case 'waymark_query' :									
					add_meta_box('waymark_query_meta', 'Query', array($this, 'get_query_form'), 'waymark_query', 'normal', 'high');			

					break;
			}		
		}
		
		add_meta_box('waymark_map_shortcode', __('Shortcode', 'waymark'), array($this, 'map_shortcode_content'), 'waymark_map', 'side', 'default');			
		add_meta_box('waymark_map_export', __('Export', 'waymark'), array($this, 'map_export_content'), 'waymark_map', 'side', 'default');			
		add_meta_box('waymark_map_help', __('Help', 'waymark'), array($this, 'map_help_content'), 'waymark_map', 'side', 'default');			
	}	

	function add_post_enctype() {
		global $post;

		if(in_array($post->post_type, array('waymark_map'))) {
			echo ' enctype="multipart/form-data"';			
		}
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
				
				//Query
				case 'waymark_query' :									
// 					Waymark_Helper::debug($_POST, 0);

					$Query = new Waymark_Query;
					$Query->set_data($_POST);				
					$Query->save_meta($post->ID);
					
					break;			

			}			
		}
	}

	function map_export_content() {	
		global $post;
		
		$Map = new Waymark_Map($post->ID);
		
		$has_features = array_key_exists('map_data', $Map->data) && Waymark_GeoJSON::get_feature_count($Map->data['map_data']);
		if($has_features) {			
			echo '<a data-title="' . esc_attr__('Download the Overlays added to this Map in the selected format.', 'waymark') . '" href="#" onclick="return false;" class="waymark-tooltip">?</a>';
			echo Waymark_Helper::map_export_html($Map);
		}
	}
	
	function map_shortcode_content() {	
		global $post;
		
		//Shortcode output
		echo '<a data-title="' . esc_attr__('Add this Map to your content with this Shortcode. Click here for more details.', 'waymark') . '" href="' . Waymark_Helper::site_url('docs/shortcodes') . '" class="waymark-tooltip">?</a>';
		echo '<input type="text" value="[' . Waymark_Config::get_item('shortcode') . ' map_id=&quot;' . $post->ID . '&quot;]" />';
		//echo '<p>' . sprintf(__('Add this Map to your content with this <a href="%s">Shortcode</a>.', 'waymark'), ) . '</p>';
	}

	function map_help_content() {	
		//Required the PHP EXIF extension
		if(! function_exists('exif_read_data')) {
			echo '<p><b>' . esc_html__('Image Location Detection Not Supported!', 'waymark') . '</b></p>';
			echo '<p>' . sprintf(__('Your hosting environment does not currently have the <a href="%s">PHP EXIF Extension</a> enabled, which is required to read Image location metadata. Try asking your host to enable it.', 'waymark'), 'https://www.php.net/manual/en/book.exif.php') . '</p>';		
		}	

		echo '<p><b>' . esc_html__('Read from File', 'waymark') . '</b></p>';
		echo '<p>' . esc_html__('You can read Lines and Markers from GPX, KML and GeoJSON files (most mapping apps will be able to export to one of these).', 'waymark') . '</p>';

		echo '<p><b>' . esc_html__('Types', 'waymark') . '</b></p>';
		echo '<p>' . sprintf(__('Types allow you to control how Overlays (Markers, Lines and Shapes) are displayed on the Map. Types can be customised in <a href="%s" target="_blank">Waymark &gt; Settings</a>.', 'waymark'), admin_url('edit.php?post_type=waymark_map&page=waymark-settings&tab=markers')) . '</p>';		

		echo '<p><b>' . esc_html__('Basemap', 'waymark') . '</b></p>';
		echo '<p>' . sprintf(__('Add and edit Basemaps in <a href="%s" target="_blank">Waymark &gt; Settings</a>. The first listed will be used as the default, unless specified in the shortcode like this: %s', 'waymark'), admin_url('edit.php?post_type=waymark_map&page=waymark-settings&tab=tiles'), '<code>[Waymark map_id="1234" basemap="Basemap Name"]</code>') . '</p>';		

		echo '<p><a class="button" href="' . Waymark_Helper::site_url('docs') . '" target="_blank">' . esc_html__('Read the Docs', 'waymark') . ' &raquo;</a></p>';

		echo '<p>&nbsp;</p>';
	}

	/**
	 * ===========================================
	 * =================== MAP ===================
	 * ===========================================
	 */	

	function get_map_form() {	
		//WP Media Library
		wp_enqueue_media();
		
		//WP TinyMCE
		wp_enqueue_editor();
		
		global $post;

		$data = Waymark_Helper::flatten_meta(get_post_meta($post->ID));
								
		//Create new Map object
		Waymark_JS::add_call('var Waymark_Map_Editor = window.Waymark_Map_Factory.editor()');

		//Warn user about navigating away from page before Publish/Update
		//I'm not sure why, but we have to return something here to get the desired behaviour :-/
		Waymark_JS::add_call('Waymark_Map_Editor.map_was_edited = function() {
			jQuery(window).on(\'beforeunload.edit-post\', function() {
				return null;
			});
	 	}');

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
		echo '<div id="waymark-map" class="waymark-map"></div>' . "\n";
		
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

		//Create Feed meta input
		$Map = new Waymark_Map;		
		$Map->set_data($data);
		$Map->set_input_type('meta');
		echo $Map->create_form();		

		echo '<p>' . sprintf(__('You can manage Meta fields in <a href="%s">Settings</a>.', 'waymark'), admin_url('edit.php?post_type=waymark_map&page=waymark-settings&tab=meta')) . '</p>';		
	}

	/**
	 * ===========================================
	 * ================= QUERY ===================
	 * ===========================================
	 */		

	function get_query_form() {	
		global $post;

		$data = Waymark_Helper::flatten_meta(get_post_meta($post->ID));
								
		//Create new Map object
		Waymark_JS::add_call('var Waymark_Map_Viewer = window.Waymark_Map_Factory.viewer()');

		//Default view
		if($default_latlng = Waymark_Config::get_setting('misc', 'map_options', 'map_default_latlng')) {
			//We have a valid LatLng
			if($default_latlng_array = Waymark_Helper::latlng_string_to_array($default_latlng)) {
		 		Waymark_JS::add_call('Waymark_Map_Viewer.fallback_latlng = [' . $default_latlng_array[0] . ',' . $default_latlng_array[1] . ']');					
			}
		}
		if($default_zoom = Waymark_Config::get_setting('misc', 'map_options', 'map_default_zoom')) {
	 		Waymark_JS::add_call('Waymark_Map_Viewer.fallback_zoom = ' . $default_zoom);		
		}

		//Map Div
		echo '<div id="waymark-map" class="waymark-map waymark-query"></div>' . "\n";
		
		//Output Config
		Waymark_JS::add_call('var waymark_user_config = ' . json_encode(Waymark_Config::get_map_config()) . ';');				
		Waymark_JS::add_call('waymark_user_config.map_height = 600;');	
				
		//Set basemap
		if($editor_basemap = Waymark_Config::get_setting('misc', 'editor_options', 'editor_basemap')) {
			Waymark_JS::add_call('waymark_user_config.map_init_basemap = "' . $editor_basemap . '"');		
		}

		//Go!
		Waymark_JS::add_call('Waymark_Map_Viewer.init(waymark_user_config)');

		$Query = new Waymark_Query;		
		$Query->set_data($data);
		$Query->set_input_type('meta');
		echo $Query->create_form();		
	}
}
new Waymark_Meta;
