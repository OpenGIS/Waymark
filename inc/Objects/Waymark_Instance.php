<?php

class Waymark_Instance extends Waymark_Class {
	protected $parameters = [
		'hash' => null,
		'type' => 'viewer',
		'basemap' => null,
		'add_class' => null,
 		'init_latlng' => null,	
 		'init_zoom' => null,	
 		'init_bounds' => null,	
 		'bb_selector' => null,
 		'latlng_selector' => null,
 		'height' => null,
	];
	
	function __construct($params_in = []) {
		parent::__construct($params_in);
		
		//Gotta have a hash		
		if(! $this->get_parameter('hash')) {
			$this->set_parameter('hash', substr(md5(json_encode($this->get_parameters())), 0, 6));		
		}

		$this->enqueue();
	}	
	
	function enqueue() {
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
	}
	
	function add_js() {
		//Config
		Waymark_JS::add_call('var waymark_user_config = ' . json_encode(Waymark_Config::get_map_config()) . ';');				
		Waymark_JS::add_call('waymark_user_config.map_div_id = "waymark-map-' . $this->get_parameter('hash') . '"');					

		//Create new Map object
		switch($this->get_parameter('type')) {
			// Editor
			case 'editor' :
		 		Waymark_JS::add_call('var waymark_instance_' . $this->get_parameter('hash') . ' = window.Waymark_Map_Factory.editor();');

		 		Waymark_JS::add_call('waymark_user_config.map_height = 600;');				

				//Warn user about navigating away from page before Publish/Update
				//I'm not sure why, but we have to return something here to get the desired behaviour :-/
				Waymark_JS::add_call('waymark_instance_' . $this->get_parameter('hash') . '.map_was_edited = function() {
					jQuery(window).on(\'beforeunload.edit-post\', function() {
						return null;
					});
				}');

				break;

		 	//Viewer	
			case 'viewer' :
			default :
		 		Waymark_JS::add_call('var waymark_instance_' . $this->get_parameter('hash') . ' = window.Waymark_Map_Factory.viewer();');
			
				break;
		}

		if($editor_basemap = $this->get_parameter('basemap')) {
			Waymark_JS::add_call('waymark_user_config.map_init_basemap = "' . $editor_basemap . '"');					
		}

		if(! $this->get_parameter('init_bounds')) {
			//Initial center?
			if($latlng_array = Waymark_Helper::latlng_string_to_array($this->get_parameter('init_latlng'))) {
				Waymark_JS::add_call('waymark_user_config.map_init_latlng = [' . $latlng_array[0] . ',' . $latlng_array[1] . ']');							
			//Default
			} elseif($latlng_array = Waymark_Helper::latlng_string_to_array(Waymark_Config::get_setting('misc', 'map_options', 'map_default_latlng'))) {
				Waymark_JS::add_call('waymark_user_config.map_init_latlng = [' . $latlng_array[0] . ',' . $latlng_array[1] . ']');									
			}

			//Initial center?
			if($zoom = $this->get_parameter('init_zoom')) {
				Waymark_JS::add_call('waymark_user_config.map_init_zoom = ' . $zoom);							
			//Default
			} elseif($zoom = Waymark_Config::get_setting('misc', 'map_options', 'map_default_zoom')) {
				Waymark_JS::add_call('waymark_user_config.map_init_zoom = ' . $zoom);								
			}		
		}

		//Go!
		Waymark_JS::add_call('waymark_instance_' . $this->get_parameter('hash') . '.init(waymark_user_config)');
		
		//Bounds?
		if($bounds = $this->get_parameter('init_bounds')) {		
			Waymark_JS::add_call('waymark_instance_' . $this->get_parameter('hash') . '.map.fitBounds(' . $bounds . ')');
		}					

		if($latlng_selector = $this->get_parameter('latlng_selector')) {		
			Waymark_JS::add_call('
//Query
var input = jQuery(\'#' . $latlng_selector . '\');
var centre = input.val().split(\',\');
if(typeof centre === \'object\') {
	var type = waymark_instance_' . $this->get_parameter('hash') . '.get_type(\'marker\');
	var marker = L.marker([centre[0], centre[1]], { draggable: true }).addTo(waymark_instance_' . $this->get_parameter('hash') . '.map);
	marker.setIcon(
		L.divIcon(waymark_instance_' . $this->get_parameter('hash') . '.build_icon_data(type))
	);	
	marker.on(\'dragend\', function(e) {
		var ll = e.target.getLatLng();
		console.log(ll);
		input.val(ll.lat + \',\' + ll.lng);
	});
}
');		
		}

		//Bounding Box
		//!!!
		if($bounds && $bb_selector = $this->get_parameter('bb_selector')) {		
			Waymark_JS::add_call('
//Query
var bounds = ' . $bounds . ';
var rectangle = L.rectangle(bounds, {
	color: "#ff7800",
	weight: 1
}).addTo(waymark_instance_' . $this->get_parameter('hash') . '.map);
rectangle.enableEdit();
waymark_instance_' . $this->get_parameter('hash') . '.map.on(\'editable:vertex:dragend\', function(e) {
	jQuery(\'#' . $bb_selector . '\').val(e.layer.getBounds().toBBoxString());
});
');		
		}
	}
	
	function get_html() {
		//Style
		$style = '';
		if($height = $this->get_parameter('height')) {
			$style = ' style="height:' . $height . 'px"';
		}
		
		return '<div id="waymark-map-' . $this->get_parameter('hash') . '" class="waymark-map waymark-instance waymark-' . $this->get_parameter('type') . ' ' . $this->get_parameter('add_class') . '" data-shortcode_hash="' . $this->get_parameter('hash') . '"' . $style . '></div>' . "\n";	}
		
	function load_json(string $json, $data_layer = 'map_data') {
		if($data_layer == 'map_data') {
			Waymark_JS::add_call('waymark_instance_' . $this->get_parameter('hash') . '.load_json(' . $json . ');');											
		}	elseif($data_layer == 'query_data') {
			Waymark_JS::add_call('waymark_instance_' . $this->get_parameter('hash') . '.load_query_json(' . $json . ');');										
		}	
	}
}