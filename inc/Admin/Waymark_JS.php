<?php

class Waymark_JS {
	
	static private $chunks = array();
	static private $calls = array();
	
	static function init() {
		self::add_chunk('//' . Waymark_Config::get_name(true, true) . ' v' . Waymark_Config::get_version());
		
		add_action('admin_footer', array('Waymark_JS', 'admin_footer'));					
		add_action('admin_enqueue_scripts', array('Waymark_JS', 'enqueue_scripts'));										
	}
	
	static function enqueue_scripts() {	
		global $current_screen;
		
		if(is_object($current_screen)) {
			switch($current_screen->id) {
				//Map Editor
				//Query Editor
				case 'waymark_map' :		
				case 'edit-waymark_query' :
					//Waymark JS
					if(Waymark_Helper::is_debug()) {
						$waymark_css_url = Waymark_Helper::asset_url('dist/waymark-js/css/waymark-js.css');
						$waymark_js_url = Waymark_Helper::asset_url('dist/waymark-js/js/waymark-js.js');			
					} else {
						$waymark_css_url = Waymark_Helper::asset_url('dist/waymark-js/css/waymark-js.min.css');
						$waymark_js_url = Waymark_Helper::asset_url('dist/waymark-js/js/waymark-js.min.js');			
					}
		
					//CSS
					wp_register_style('waymark-js', $waymark_css_url, array(), Waymark_Config::get_version());
					wp_enqueue_style('waymark-js');		
					//JS
					wp_register_script('waymark-js', $waymark_js_url, array('jquery'), Waymark_Config::get_version());
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
		
					break;
			}
		}
	
		wp_register_script('waymark_admin_js', Waymark_Helper::asset_url('js/admin.min.js'), array('jquery', 'jquery-ui-sortable', 'jquery-effects-core', 'wp-color-picker'), Waymark_Config::get_version());	
		wp_localize_script('waymark_admin_js', 'waymark_php_lang', array(
			'repeatable_delete_title' => esc_attr__('Remove!', 'waymark'),
			'marker_icon_icon_label' => esc_attr__('Name', 'waymark'),
			'marker_icon_text_label' => esc_attr__('Text', 'waymark'),
			'marker_icon_html_label' => esc_attr__('HTML', 'waymark'),						
		));
		wp_enqueue_script('waymark_admin_js');			
	}
	
	static function add_chunk($chunk) {	
		if($chunk[strlen($chunk)-1] != ';') {
			$chunk .= ';';
		}
		self::$chunks[] = $chunk;
	}

	static function add_call($call) {	
		if(! in_array($call, self::$calls)) {
			self::$calls[] = $call;			
		}
	}

	static function admin_footer() {
		echo "\n" . '<!-- START ' . Waymark_Config::get_name(true, true) . ' Footer JS -->' . "\n";
		echo '<script type="text/javascript">' . "\n";
		//Lines
		foreach(self::$chunks as $chunk) {
			 echo $chunk . "\n";
		}
		
		//Calls
		if(sizeof(self::$calls)) {
			echo "\n" . 'jQuery(document).ready(function() {' . "\n";
			foreach(self::$calls as $call) {
				echo "	" . $call . ";\n";
			}		
			echo '});' . "\n";
		}
		echo '</script>' . "\n";
		echo '<!-- END ' . Waymark_Config::get_name(true, true) . ' Footer JS -->' . "\n\n";			
	}	
}

Waymark_JS::init();