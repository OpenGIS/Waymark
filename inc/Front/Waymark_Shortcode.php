<?php
	
class Waymark_Shortcode {
	function __construct() {
		add_shortcode(Waymark_Config::get_item('shortcode'), array($this, 'handle_shortcode'));		
	}

	function handle_shortcode($shortcode_data, $content = null) {
		$out = '';
		$map_class = 'waymark-map';
		$shortcode_hash = substr(md5(json_encode($shortcode_data)), 0, 6);
		$maps_output = array();	
		$shortcode_header = array();
		
		//Just in case false is passed
		if(! $shortcode_data) {
			$shortcode_data = array();
		}

		// ============ START HTML ================
		
		//Map Hash given?
		if(array_key_exists('map_hash', $shortcode_data)) {
			$shortcode_hash = $shortcode_data['map_hash'];			
		}

		//Map ID
		if(array_key_exists('map_id', $shortcode_data)) {
			//Get Map
			$Map = new Waymark_Map($shortcode_data['map_id']);

			//Shortcode header
			$shortcode_header = array(
				'title' => $Map->post_title,
				'link' => get_permalink($Map->post_id)				
			);	
			
			//Ensure we have data
			if($Map && array_key_exists('map_data', $Map->data)) {
				//Output
				$maps_output[$Map->post_id] = array(
					'map_id' => $Map->post_id,
					'map_title' => $Map->post_title,
					'map_data' => $Map->data['map_data']
				);

				//Map Class
				$map_class .= ' waymark-map-id-' . $Map->post_id;				
			}
		//Collection
		} else if(array_key_exists('collection_id', $shortcode_data)) {
			$collection_id = $shortcode_data['collection_id'];

			//Iterate over Collection
			$Collection = new Waymark_Collection($collection_id);

			//Map Class
			$map_class .= ' waymark-collection-id-' . $collection_id;
			
			foreach($Collection->Maps as $Map) {
				//Ensure we have data
				if(! array_key_exists('map_data', $Map->data)) {
					continue;
				}
				
				//Output
				$maps_output[$Map->post_id] = array(
					'map_id' => $Map->post_id,
					'map_title' => $Map->post_title,
					'collection_id' => $collection_id
				);
				
				//Link to Map page?
				if(Waymark_Config::get_setting('misc', 'collection_options', 'link_to_maps') == true) {
					//Modify map data
					$modified_map_data = Waymark_Helper::add_map_link_to_description($Map->post_id, $Map->post_title, $Map->data['map_data']);
					if($modified_map_data) {
						$maps_output[$Map->post_id]['map_data'] = $modified_map_data;					
					} else {
						$maps_output[$Map->post_id]['map_data'] = $Map->data['map_data'];					
					}
				} else {
					$maps_output[$Map->post_id]['map_data'] = $Map->data['map_data'];
				}
			}
					
			//Shortcode header
			$shortcode_header = array(
				'title' => $Collection->title,
				'link' => get_term_link((int)$Collection->collection_id, 'waymark_collection')
			);
		}
			
		// ===== Shortcode options (1/2) =====
				
		//Height
		if(array_key_exists('map_height', $shortcode_data)) {
			$map_height = $shortcode_data['map_height'];
		} else {
			$map_height = Waymark_Config::get_setting('misc', 'map_options', 'map_height');
		}

		//Output HTML container
		$out = '<!-- START Waymark Shortcode #' . $shortcode_hash . ' -->' . "\n";
		$out .= '<div id="waymark-shortcode-' . $shortcode_hash . '" class="waymark-shortcode waymark-container">' . "\n";


		//Header ?
		$do_header = 0;
		if(get_post_type() !== 'waymark_map') {
			//Always for Admin			
			if(current_user_can('administrator') && Waymark_Config::get_setting('misc', 'shortcode_options', 'header_override')) {
				$do_header = 1;			
			} else {
				//Shortcode
				if(array_key_exists('shortcode_header', $shortcode_data)) {
					$param = $shortcode_data['shortcode_header'];
				
					if(is_numeric($param)) {
						$do_header = $param;
					}
				//Setting
				} else {
					$setting = Waymark_Config::get_setting('misc', 'shortcode_options', 'shortcode_header');
				
					//Boolean
					if(is_numeric($setting)) {
						$do_header = $setting;
					}
				}			
			}
		}		
			
		//Header (non Map pages only)
		if($do_header && sizeof($shortcode_header)) {
			$out .= '	<!-- Shortcode Header -->' . "\n";
			$out .= '	<header class="waymark-header">' . "\n";
			
			//Link
			if(array_key_exists('link', $shortcode_header)) {
				$out .= '		<a class="waymark-link" href="' . $shortcode_header['link'] . '">' . esc_html__('View Details', 'waymark-plugin') . ' &raquo;</a>' . "\n";			
			}

			//Title
			if(array_key_exists('title', $shortcode_header)) {
				$out .= '		<div class="waymark-title">' . $shortcode_header['title'] . '</div>' . "\n";			
			}			
			$out .= '	</header>' . "\n";		
		}

		//Map HTML Container
		$out .= '	<!-- Map -->' . "\n";
		$out .= '	<div id="waymark-map-' . $shortcode_hash . '" class="' . $map_class . '" style="height:' . $map_height . 'px"></div>' . "\n";

		//Elevation?
		if(array_key_exists('show_elevation', $shortcode_data)) {
			$show_elevation = $shortcode_data['show_elevation'];
		} else {
			$show_elevation = Waymark_Config::get_setting('misc', 'elevation_options', 'show_elevation') == '1';
		}
		if($show_elevation) {
			$out .= '	<div id="waymark-elevation-' . $shortcode_hash . '" class="waymark-elevation"></div>' . "\n";
		}

		//Close container
		$out .= '</div>' . "\n";

		// ================ END HTML ====================

		// ============ START JAVASCRIPT ================

		//Create viewer
 		$out .= '<script>' . "\n";					
 		$out .= 'jQuery(document).ready(function() {' . "\n";					
 		$out .= 'var waymark_viewer_' . $shortcode_hash . ' = window.Waymark_Map_Factory.viewer();' . "\n";					

		//Default view
		if($default_latlng = Waymark_Config::get_setting('misc', 'map_options', 'map_default_latlng')) {
			$default_latlng_array = Waymark_Helper::latlng_string_to_array($default_latlng);
			
			//We have a valid LatLng
			if($default_latlng_array) {
		 		$out .= 'waymark_viewer_' . $shortcode_hash . '.fallback_latlng = [' . $default_latlng_array[0] . ',' . $default_latlng_array[1] . '];' . "\n";					
			}
		}
		if($default_zoom = Waymark_Config::get_setting('misc', 'map_options', 'map_default_zoom')) {
	 		$out .= 'waymark_viewer_' . $shortcode_hash . '.fallback_zoom = ' . $default_zoom . ";\n";		
		}

		//Output Config
 		$out .= 'var waymark_config = jQuery.extend({}, waymark_user_config);' . "\n";					
 		$out .= 'waymark_config.map_div_id = "waymark-map-' . $shortcode_hash . '";' . "\n";					
 		$out .= 'waymark_config.map_height = ' . $map_height . ";\n";

		// ===== Shortcode options (2/2) =====
		
		//Map Centre
		if(array_key_exists('map_centre', $shortcode_data)) {
			$latlng_string = $shortcode_data['map_centre'];
			$latlng_array = Waymark_Helper::latlng_string_to_array($latlng_string);
		
			if(is_array($latlng_array)) {
				$out .= 'waymark_config.map_init_latlng = [' . $latlng_array[0] . ',' . $latlng_array[1] . '];' . "\n";								
			}				
		}		

		//Map Zoom
		if(array_key_exists('map_zoom', $shortcode_data)) {
			$map_init_zoom = $shortcode_data['map_zoom'];
		
			if(is_numeric($map_init_zoom)) {
				$out .= 'waymark_config.map_init_zoom = ' . $map_init_zoom . ";\n";								
			}		
		}		

		//Basemap?
		if(array_key_exists('basemap', $shortcode_data)) {
	 		$out .= 'waymark_config.map_init_basemap = "' . $shortcode_data['basemap'] . '";' . "\n";
		}

		//Gallery?
		if(array_key_exists('show_gallery', $shortcode_data)) {
			$show_gallery = $shortcode_data['show_gallery'];
		} else {
			$show_gallery = Waymark_Config::get_setting('misc', 'map_options', 'show_gallery');
		}
		if($show_gallery) {
	 		$out .= 'waymark_config.show_gallery = 1;' . "\n";
		}

		//Overlay filter?
		if(array_key_exists('show_filter', $shortcode_data)) {
			$show_filter = $shortcode_data['show_filter'];
		} else {
			$show_filter = Waymark_Config::get_setting('misc', 'map_options', 'show_filter');
		}
		if($show_filter) {
	 		$out .= 'waymark_config.show_filter = 1;' . "\n";
		}

		//Elevation?
		if($show_elevation) {
	 		$out .= 'waymark_config.show_elevation = 1;' . "\n";
	 		$out .= 'waymark_config.elevation_div_id = "waymark-elevation-' . $shortcode_hash . '";' . "\n";
	 		$out .= 'waymark_config.elevation_units = "' . Waymark_Config::get_setting('misc', 'elevation_options', 'elevation_units') . '";' . "\n";
	 		$out .= 'waymark_config.elevation_initial = ' . Waymark_Config::get_setting('misc', 'elevation_options', 'elevation_initial') . ';' . "\n";
		}

		//Go!
		
 		$out .= 'waymark_viewer_' . $shortcode_hash . '.init(waymark_config);' . "\n";					

 		$map_count = 0;
		foreach($maps_output as $map_id => $map_output) {
			//Load first map_data on-page
			if($map_count == 0) {
				//If map data exists
				if(isset($map_output['map_data'])) {
					$out .= 'waymark_viewer_' . $shortcode_hash . '.load_json(' . $map_output['map_data'] . ');' . "\n";														
				}			
			//Load everything else via HTPP
			} else {
				$out .= 'waymark_load_map_data(waymark_viewer_' . $shortcode_hash . ', ' . $map_id . ', true);' . "\n";																
			}

			$map_count++;
		}

		$out .= '});' . "\n";
		$out .= '</script>' . "\n";
		$out .= '<!-- END Waymark Shortcode #' . $shortcode_hash . ' -->' . "\n";
 					
		// ============= END JAVASCRIPT =================
		
		//Return HTML			
		return $out;		
	}
}
new Waymark_Shortcode;
