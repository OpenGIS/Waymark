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
		$shortcode_meta = array();
		
		//Just in case false is passed
		if(! $shortcode_data) {
			$shortcode_data = array();
		}

		// ============ Content? ================
		
		if(array_key_exists('content', $shortcode_data)) {
			switch($shortcode_data['content']) {
				case 'submission' :
					$Submission = new Waymark_Submission;
					
					$content = $Submission->render_front();

					break;
			}

			//Stop here		
			return $content;
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

			//Map Meta?
			$shortcode_meta = Waymark_Helper::get_map_meta($Map, 'shortcode');
			
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

			//Valid Collection
			$Collection = new Waymark_Collection($collection_id);
			if($Collection->collection_id) {
				$shortcode_meta = Waymark_Helper::get_collection_meta($Collection, 'shortcode');
			
				//Map Class
				$map_class .= ' waymark-collection-id-' . $collection_id;
			
				//Iterate over Collection
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
		$out .= '<div id="waymark-shortcode-' . $shortcode_hash . '" data-shortcode_hash="' . $shortcode_hash . '" class="waymark-shortcode waymark-container">' . "\n";


		//Header ?
		$do_header = 0;
		
		//Start with Setting
		$setting = Waymark_Config::get_setting('misc', 'shortcode_options', 'shortcode_header');
		if(is_numeric($setting)) {
			$do_header = $setting;
		}
		
		//Maps
		if(get_post_type() == 'waymark_map') {
			//Map Details Page
			if(is_single()) {
				$do_header = 0;			
			//Archive
			} else {
			
			}
		//I.e. Embedding using the Shortcode
		} else {
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
				}			
			}
		}		
			
		//Header (non Map pages only)
		if($do_header && sizeof($shortcode_header)) {
			$out .= '	<!-- Shortcode Header -->' . "\n";
			$out .= '	<header class="waymark-header">' . "\n";
	
			//Link
			if(array_key_exists('link', $shortcode_header)) {
				$out .= '		<a class="waymark-link" href="' . $shortcode_header['link'] . '">' . esc_html__('Details', 'waymark') . ' <i class="ion ion-android-open"></i></a>' . "\n";			
			}

			//Title
			if(array_key_exists('title', $shortcode_header)) {
				$out .= '		<div class="waymark-title">' . $shortcode_header['title'] . '</div>' . "\n";			
			}			

			//Shortcode Meta?
			//...and it's not the Map Details page
			if(sizeof($shortcode_meta)) {
				$out .= '	<div class="waymark-meta">' . "\n";
				$out .= Waymark_Helper::map_meta_html($shortcode_meta, false);		
				$out .= '		<a class="waymark-link" href="' . $shortcode_header['link'] . '">' . esc_html__('More Details', 'waymark') . ' <i class="ion ion-android-open"></i></a>' . "\n";			
				$out .= '	</div>' . "\n";			
			}	
			
			$out .= '	</header>' . "\n";		
		}

		//Map HTML Container (Initially hidden)
		$out .= '	<!-- Map -->' . "\n";
		$out .= '	<div style="display:none;height:' . $map_height . 'px" id="waymark-map-' . $shortcode_hash . '" class="' . $map_class . '" data-shortcode_hash="' . $shortcode_hash . '"></div>' . "\n";

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
		$out .= 'var waymark_settings  = ' . Waymark_Config::get_settings_js() . ';' . "\n";				

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
 		$out .= 'var waymark_config = jQuery.extend(true, {}, waymark_user_config);' . "\n";					
 		$out .= 'waymark_config.map_div_id = "waymark-map-' . $shortcode_hash . '";' . "\n";					
 		$out .= 'waymark_config.map_height = ' . $map_height . ";\n";

		// ===== Shortcode options (2/2) =====
		
		//Map Centre
		if(array_key_exists('map_centre', $shortcode_data)) {
			$latlng_string = $shortcode_data['map_centre'];
			$map_latlng_array = Waymark_Helper::latlng_string_to_array($latlng_string);
		
			if(is_array($map_latlng_array)) {
				$out .= 'waymark_config.map_init_latlng = [' . $map_latlng_array[0] . ',' . $map_latlng_array[1] . '];' . "\n";								
			}				
		}		

		//Map Zoom
		if(array_key_exists('map_zoom', $shortcode_data)) {
			$map_init_zoom = $shortcode_data['map_zoom'];
		
			if(is_numeric($map_init_zoom)) {
				$out .= 'waymark_config.map_init_zoom = ' . $map_init_zoom . ";\n";								
			}		
		}		

		//Max Zoom
		if(array_key_exists('max_zoom', $shortcode_data) && is_numeric($shortcode_data['max_zoom'])) {
			
			$out .= 'waymark_config.map_options.max_zoom = ' . $shortcode_data['max_zoom'] . ";\n";								
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
	 		$out .= 'waymark_config.elevation_initial = ' . Waymark_Config::get_setting('misc', 'elevation_options', 'elevation_initial') . ';' . "\n";

	 		//Units
	 		//Shortcode
	 		if(array_key_exists('elevation_units', $shortcode_data) && in_array($shortcode_data['elevation_units'], ['metric', 'imperial'])) {
				$elevation_units = $shortcode_data['elevation_units'];
			//Setting
			} else {
				$elevation_units = Waymark_Config::get_setting('misc', 'elevation_options', 'elevation_units');
			}	 		
			$out .= 'waymark_config.elevation_units = "' . $elevation_units . '";' . "\n";
		}
		
		//Initially Show / Hide Types
		foreach(['hide_marker', 'show_marker', 'hide_line', 'show_line', 'hide_shape', 'show_shape'] as $show_hide_type) {
			if(array_key_exists($show_hide_type, $shortcode_data)) {			
				$show_hide_explode = explode('_', $show_hide_type);
				$overlay_kind = $show_hide_explode[1];
				
				if($show_hide_explode[0] == 'show') {
					$overlay_display = 1;
				} elseif($show_hide_explode[0] == 'hide') {
					$overlay_display = 0;				
				}

				$overlay_type_explode = explode(',', $shortcode_data[$show_hide_type]);
						
				foreach($overlay_type_explode as $overlay_type) {
					$out .= 'for(i in waymark_config.' . $overlay_kind . '_types) {' . "\n";
					$out .= '	var this_key = waymark_viewer_' . $shortcode_hash . '.make_key(waymark_config.' . $overlay_kind . '_types[i]["' . $overlay_kind . '_title"]);' . "\n";			
					$out .= '	if(this_key == "' . $overlay_type . '") {' . "\n";
					$out .= '		waymark_config.' . $overlay_kind . '_types[i]["' . $overlay_kind . '_display"] = ' . $overlay_display . ';' . "\n";			
					$out .= '	}' . "\n";			
					$out .= '}' . "\n";
				}
			}
		}		
		
		// =====================================
		// ============ INIT CONFIG ============
		// =====================================				
		
 		$out .= 'waymark_viewer_' . $shortcode_hash . '.init(waymark_config);' . "\n";			



		// =====================================
		// ================ MAPS ===============
		// =====================================	
		
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

		// =====================================
		// =========== START MARKERS ===========
		// =====================================				

		//Get Marker data
		$marker_data_keys = [
			'marker_centre',
			'marker_type',
			'marker_title',
			'marker_description',
			'marker_image'			
		];
		
		//Count for marker data
		$marker_data_count = 0;
		foreach($shortcode_data as $key => $value) {
			if(in_array($key, $marker_data_keys)) {
				$marker_data_count++;
			}
		}

		//We have Marker data
		if($marker_data_count) {
			//Explicit Marker location?
			if(array_key_exists('marker_centre', $shortcode_data)) {
				$latlng_string = $shortcode_data['marker_centre'];
				$marker_latlng_array = Waymark_Helper::latlng_string_to_array($latlng_string);		
			//Use Map centre?
			} elseif(isset($map_latlng_array)) {
				$marker_latlng_array = $map_latlng_array;		
			}

			if(isset($marker_latlng_array)) {
				$marker_data = [];

				foreach($marker_data_keys as $key) {
					if(array_key_exists($key, $shortcode_data)) {
						$value = $shortcode_data[$key];
				
						switch($key) {
							case 'marker_type' :
								$marker_data['type'] = $value;
					
								break;
							case 'marker_title' :
								$marker_data['title'] = $value;

								break;
							case 'marker_description' :
								$marker_data['description'] = $value;

								break;			
							case 'marker_image' :
								$marker_data['image_thumbnail_url'] = $value;
								$marker_data['image_medium_url'] = $value;
								$marker_data['image_large_url'] = $value;

								break;			
						}
					}		
				}

				$marker_geojson = [
					'type' => 'FeatureCollection',
					'features' => [
						[
							'type' => 'Feature',
							'properties' => $marker_data,
							'geometry' => [
								'type' => 'Point',
								'coordinates' => [$marker_latlng_array[1], $marker_latlng_array[0]]
							]
						]
					]				
				];
			
				$out .= 'waymark_viewer_' . $shortcode_hash . '.load_json(' . json_encode($marker_geojson) . ');' . "\n";														
			}
		}

		// ============== END MARKERS ==============

		// =====================================
		// =========== START FILE URL ==========
		// =====================================		
		
		if(array_key_exists('file_url', $shortcode_data)) {
			//Accept multiple
			foreach(explode(',', $shortcode_data['file_url']) as $file_url) {
				$file_response = wp_remote_get($file_url);	
				
				//Success
				if(wp_remote_retrieve_response_code($file_response) == '200') {
					//Get file info
					$file_headers = wp_remote_retrieve_headers($file_response);			
					$file_ext = pathinfo($file_url, PATHINFO_EXTENSION);
					
					//Mime?
					if(isset($file_headers['content-type'])) {
						$file_mime = $file_headers['content-type'];					
					} else {
						$file_mime = false;					
					}

					//Is allowable file
					if(Waymark_Helper::allowable_file($file_ext, $file_mime)) {

						$file_body = wp_remote_retrieve_body($file_response);
						$file_string = preg_replace('/\s+/', ' ', $file_body);

						$out .= 'var file_geo_json = {}' . "\n";
						$out .= 'var file_data = \'' . $file_string . '\';' . "\n";

						switch($file_ext) {
							case 'gpx' :
								$out .= 'var file_data = (new DOMParser()).parseFromString(file_data, "text/xml");' . "\n";
								$out .= 'file_geo_json = toGeoJSON.gpx(file_data);' . "\n";
							
								break;
								
							case 'kml' :
								$out .= 'var file_data = (new DOMParser()).parseFromString(file_data, "text/xml");' . "\n";
								$out .= 'var file_geo_json = toGeoJSON.kml(file_data);' . "\n";

								break;	
								
							default :
								$out .= 'var file_geo_json = JSON.parse(file_data);' . "\n";

								break;																
						}				
						
						foreach(['marker', 'line', 'shape'] as $overlay_type) {
							//Cast Line Type
							if(array_key_exists('file_' . $overlay_type . '_type', $shortcode_data)) {
								$cast_type = $shortcode_data['file_' . $overlay_type . '_type'];

								//Add default type
								$out .= '
								for(i in file_geo_json.features) {
									if(typeof file_geo_json.features[i].properties.type == "undefined") {
										var overlay_type = waymark_viewer_' . $shortcode_hash . '.get_feature_overlay_type(file_geo_json.features[i]);
										var config_types = waymark_viewer_' . $shortcode_hash . '.config.' . $overlay_type . '_types;

										for(var j in config_types) {
											//Valid Type Key
											if("' . $cast_type . '" == waymark_viewer_' . $shortcode_hash . '.make_key(config_types[j][overlay_type + "_title"])) {
												file_geo_json.features[i].properties.type = "' . $cast_type . '";										
											}
										}
									};
								}' . "\n";
							}						
						}

						$out .= 'waymark_viewer_' . $shortcode_hash . '.load_json(file_geo_json);' . "\n";
					}
				}			
			}
		}		

		// =========== END FILE URL ==========

		// ========== START CALLBACK ===========
		
		if(array_key_exists('loaded_callback', $shortcode_data)) {
			$out .= 'if(typeof ' . $shortcode_data['loaded_callback'] . ' === "function") {' . "\n";
			$out .= '	' . $shortcode_data['loaded_callback'] . '(waymark_viewer_' . $shortcode_hash . ');' . "\n";
			$out .= '}' . "\n";
		}

		// =========== END CALLBACK ============
				
		$out .= '});' . "\n";
		$out .= '</script>' . "\n";
		$out .= '<!-- END Waymark Shortcode #' . $shortcode_hash . ' -->' . "\n";

		// ============= END JAVASCRIPT =================
		
		//Return HTML			
		return $out;		
	}
}
new Waymark_Shortcode;
