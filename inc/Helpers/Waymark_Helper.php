<?php

class Waymark_Helper {
	
	static public function plugin_about() {
		$out = '';
		
		$out .= '	<div id="waymark-about">' . "\n";	
		$out .= '		<img width="75" height="75" alt="Joe\'s mug" src="//www.josephhawes.co.uk/assets/images/Joe1BW.jpg" />' . "\n";		
		$out .= '		<p class="waymark-first"><b>' . esc_html__(sprintf("Hi, I'm %s.", "Joe"), 'waymark-plugin') . '</b></p>' . "\n";		

		$out .= '		<p>' . __(sprintf('Thanks for checking out %s. You can find the documentation <a href="%s">here</a>.', Waymark_Config::get_name(true), Waymark_Helper::site_url('docs/')), 'waymark-plugin') . '</p>' . "\n";		
		$out .= '		<p>' . __(sprintf('This plugin is a work in progress, so please <a href="%s">let me know</a> of any bugs you encounter, or improvements you would like to see. I appreciate all feedback.', 'https://wordpress.org/support/plugin/waymark/'), 'waymark-plugin') . '</p>' . "\n";		
		$out .= '		<p>' . esc_html__('Thanks', 'waymark-plugin') . '.</p>' . "\n";		

		$out .= '		<hr />' . "\n";		

		$out .= '		<p>' . esc_html__('A big thank you to the following. Without their work this plugin would not be possible:', 'waymark-plugin') . '</p>' . "\n";		

		$out .= '		<ul>' . "\n";		
		$out .= '			<li><a href="https://wordpress.org/">WordPress</a></li>' . "\n";		
		$out .= '			<li><a href="https://www.openstreetmap.org/">OpenStreetMap</a></li>' . "\n";				
		$out .= '			<li><a href="https://leafletjs.com/">Leaflet</a></li>' . "\n";				
		$out .= '			<li><a href="https://jquery.com/">jQuery</a></li>' . "\n";		
		$out .= '			<li><a href="https://github.com/mapbox/togeojson">mapbox/togeojson</a></li>' . "\n";				
		$out .= '			<li><a href="https://github.com/lvoogdt/Leaflet.awesome-markers">Leaflet.awesome-markers</a></li>' . "\n";				
		$out .= '			<li><a href="https://ionicons.com/v2/">Ionicons</a></li>' . "\n";				
		$out .= '			<li><a href="https://github.com/Leaflet/Leaflet.Editable">Leaflet.Editable</a></li>' . "\n";	
		$out .= '			<li><a href="https://github.com/CliffCloud/Leaflet.Sleep">Leaflet.Sleep</a></li>' . "\n";	
		$out .= '			<li><a href="https://github.com/perliedman/leaflet-control-geocoder">leaflet-control-geocoder</a></li>' . "\n";	
		$out .= '			<li><a href="https://github.com/Raruto/leaflet-elevation">leaflet-elevation</a></li>' . "\n";			
		$out .= '			<li class="waymark-multi">' . "\n";	
		$out .= '				<a href="https://stackoverflow.com/questions/41522376/leaflet-open-popup-at-cursor-position-instead-of-linestring-center">S</a>' . "\n";	
		$out .= '				<a href="https://stackoverflow.com/questions/32106243/regex-to-remove-all-non-alpha-numeric-and-replace-spaces-with/32106277">t</a>' . "\n";	
		$out .= '				<a href="https://stackoverflow.com/questions/22278282/get-the-dom-element-associated-with-a-leaflet-class">a</a>' . "\n";	
		$out .= '				<a href="https://stackoverflow.com/questions/22948096/get-the-bounding-box-of-the-visible-leaflet-map">c</a>' . "\n";	
		$out .= '				<a href="https://stackoverflow.com/questions/20816173/close-all-popups-with-leaflet-js">k</a>' . "\n";	
		$out .= '				<a href="https://stackoverflow.com/questions/793014/jquery-trigger-file-input">o</a>' . "\n";	
		$out .= '				<a href="https://stackoverflow.com/questions/18382945/how-do-i-get-the-latlng-after-the-dragend-event-in-leaflet">v</a>' . "\n";	
		$out .= '				<a href="https://stackoverflow.com/questions/33759578/how-to-change-base-layer-using-js-and-leaflet-layers-control">e</a>' . "\n";	
		$out .= '				<a href="https://stackoverflow.com/questions/35125036/export-leaflet-map-to-geojson">r</a>' . "\n";	
		$out .= '				<a href="https://stackoverflow.com/questions/4817029/whats-the-best-way-to-detect-a-touch-screen-device-using-javascript/4819886#4819886">f</a>' . "\n";	
		$out .= '				<a href="https://stackoverflow.com/questions/2320069/jquery-ajax-file-upload/24939229#24939229">l</a>' . "\n";	
		$out .= '				<a href="https://stackoverflow.com/questions/44832150/how-to-append-the-leaflet-js-attribution-string">o</a>' . "\n";	
		$out .= '				<a href="https://stackoverflow.com/questions/22808065/how-to-make-all-links-in-an-iframe-open-in-new-tab/22808227#22808227">w</a>' . "\n";	
		$out .= '			</li>' . "\n";		
		$out .= '			<li class="waymark-multi">' . esc_html__('and', 'waymark-plugin') . " \n";	
		$out .= '				<a href="https://geojson.org/">o</a>' . "\n";	
		$out .= '				<a href="https://www.thunderforest.com/maps/opencyclemap/">t</a>' . "\n";	
		$out .= '				<a href="https://www.gaiagps.com/">h</a>' . "\n";	
		$out .= '				<a href="https://thewebsitedev.com/dynamic-content-tinymce/">e</a>' . "\n";	
		$out .= '				<a href="https://jamesdigioia.com/add-button-pop-wordpresss-tinymce-editor/">r</a>' . "\n";	
		$out .= '				<a href="http://www.jacquet80.eu/blog/post/2011/02/Display-GPX-tracks-using-Google-Maps-API">s</a>' . "\n";	
		$out .= '				<a href="https://kristarella.blog/2009/04/add-image-exif-metadata-to-wordpress/">!</a>' . "\n";	
		$out .= '			</li>' . "\n";	
		$out .= '		</ul>' . "\n";		

		$out .= '	</div>' . "\n";		
		
		return $out;
	}				
	
	static public function logo($colour = 'dark', $width = '20', $height = '20', $title = false) {
		if(! $title) {
			$title = Waymark_Config::get_name();
		}
		return '<img class="waymark-logo" alt="' . Waymark_Config::get_name() . '" src="' . self::asset_url('admin/img/waymark-icon-' . $colour . '.png') . '" width="' . $width . '" height="' . $height . '" />';
	}
	
	static public function site_url($url_path = '') {
		return Waymark_Config::get_item('site_url') . $url_path;
	}

	static public function asset_url($file_path = '') {
		return plugins_url('assets/' . $file_path, str_replace('/inc', '', dirname(__FILE__)));
	}	
	
	static public function http_url($data = array()) {
		return trim(add_query_arg(array_merge(array('waymark_http' => '1'), $data), home_url('/')), '/');
	}

	static public function get_meta($post_id) {
		$meta_array = get_post_meta($post_id);
		
		$meta_out = array();
		foreach($meta_array as $meta_key => $meta_value) {			
			if($meta_key[0] == '_') {
				continue;
			}
			
			if(is_array($meta_value)) {
				$meta_value = array_shift($meta_value);
			}
			
			$meta_out[$meta_key] = $meta_value;
		}
		
		return $meta_out;
	}	

	static public function get_map_meta($Map) {
		$map_meta = array();
		
		//Get settings
		$settings_meta = Waymark_Config::get_item('meta', 'inputs');		
		$settings_meta = Waymark_Helper::convert_values_to_single_value($settings_meta);
		$settings_meta = Waymark_Helper::convert_single_value_to_array($settings_meta);		
			
		//For each setting
		foreach($settings_meta as $setting_meta) {
			$meta_key = self::make_key($setting_meta['meta_title'], 'map');

			//If we have data
			if(array_key_exists($meta_key, $Map->data) && ! empty($Map->data[$meta_key])) {
				$data = array(
					'meta_key' => $meta_key,
					'meta_title' => $setting_meta['meta_title'],
				);
				
				//Select				
				if(in_array($setting_meta['meta_type'], array('select')) && array_key_exists('meta_options', $setting_meta)) {
					$options_array = Waymark_Helper::comma_string_to_array($setting_meta['meta_options']);
					
					//Only if option exists					
					if(isset($options_array[$Map->data[$meta_key]])) {
						$data['meta_value'] = $options_array[$Map->data[$meta_key]];					
					}
				//Multi-Select				
				} elseif(in_array($setting_meta['meta_type'], array('select_multi')) && array_key_exists('meta_options', $setting_meta)) {
					$options_array = Waymark_Helper::comma_string_to_array($setting_meta['meta_options']);					
					$values_array = Waymark_Helper::array_string_to_array($Map->data[$meta_key]);
					
					//Build output
					$data['meta_value'] = '';
					foreach($values_array as $value) {
						//Only if option exists					
						if(isset($options_array[$value])) {
							$data['meta_value'] .= $options_array[$value] . '<br />';	
						}
					}
				//Rich text
				} elseif(in_array($setting_meta['meta_type'], array('textarea_rich'))) {
					$data['meta_value'] = wpautop($Map->data[$meta_key]);
				//Value
				} else {
					$data['meta_value'] = $Map->data[$meta_key];
				}
				
				$map_meta[] = $data;
			}
		}	
		
		return $map_meta;
	}

	static public function meta_table($meta_array = array()) {
		$out = '';
		
		if(is_array($meta_array) && sizeof($meta_array)) {
			$out .= '<table class="waymark-meta-table">' . "\n";	

			foreach($meta_array as $meta) {	
				//Ensure we have a value
				if(isset($meta['meta_value'])) {
					$out .= '	<tr id="waymark-meta-' . $meta['meta_key'] . '" class="waymark-meta-row">' . "\n";			
					$out .= '		<th class="waymark-meta-title" scope="row">' . $meta['meta_title'] . '</th>' . "\n";	
					$out .= '		<td class="waymark-meta-content">' . $meta['meta_value'] . '</td>' . "\n";	
					$out .= '		<td class="waymark-meta-info">' . "\n";
					if(array_key_exists('meta_info', $meta) && ! empty($meta['meta_info'])) {
						$out .= $meta['meta_info'];
					}
					$out .= '</td>' . "\n";	


					$out .= '	</tr>' . "\n";				
				}
			}

			$out .= '</table>' . "\n";	
		}
		
		return $out;
	}

	static public function flatten_meta($data_in) {
		$data_out = array();		
		
		if(is_array($data_in)) {
			foreach($data_in as $data_key => $data_value) {
				$data_out[$data_key] = $data_value[0];
			}		
		}
		
		return $data_out;		
	}	

	//Thanks https://stackoverflow.com/questions/2526304/php-extract-gps-exif-data/16437888#16437888
	static public function exif_gps_to_gps_float($coordinate, $hemisphere) {
	  if(is_string($coordinate)) {
	    $coordinate = array_map("trim", explode(",", $coordinate));
	  }
	  
	  for($i = 0; $i < 3; $i++) {
	    $part = explode('/', $coordinate[$i]);
	    if(count($part) == 1) {
	      $coordinate[$i] = $part[0];
	    } else if (count($part) == 2) {
	      $coordinate[$i] = floatval($part[0])/floatval($part[1]);
	    } else {
	      $coordinate[$i] = 0;
	    }
	  }
	  
	  list($degrees, $minutes, $seconds) = $coordinate;
	  $sign = ($hemisphere == 'W' || $hemisphere == 'S') ? -1 : 1;
	  
	  return $sign * ($degrees + $minutes/60 + $seconds/3600);
	}

	static public function make_key($str, $prefix = '', $use_underscores = true) {
		$str = str_replace(' ', '_', $str);

		if($prefix) {
			$str = $prefix . '_' . $str;	
		}
		
		//Like in JS
		if(! $use_underscores) {
			$str = str_replace('_', '', $str);		
		}
		
		$str = strtolower($str);
		$str = preg_replace('/[^a-z0-9+_]+/i', '', $str);
		
		return $str;
	}
	
	static public function latlng_string_to_array($latlng_string) {
		$latlng_array = explode(',', $latlng_string);
		
		if(is_array($latlng_array) && sizeof($latlng_array) == 2) {
			if(is_numeric($latlng_array[0]) && is_numeric($latlng_array[1])) {
				return $latlng_array;								
			}				
		}		
		
		return false;	
	}
	
	static public function debug($thing, $die = true) {
		if(Waymark_Config::get_setting('misc', 'advanced', 'debug_mode') != true) {
			//return;	
		}
			
		echo '<pre>';
		print_r($thing);
		echo '</pre>';
		if($die) {
			die;
		}
	}

	//Thanks! https://stackoverflow.com/a/24365425/569788
	static public function stringify_numbers($obj) {
		//Bad data
		if(! $obj) {
			return $obj;
		}
		
		foreach($obj as &$item) {
			if(is_object($item) || is_array($item)) {
				$item = self::stringify_numbers($item); // recurse!
			}
	
			if(is_numeric($item)) {
				$item = (string) $item;
			}
		}				
		
		return $obj;
	}

	static public function remove_unwanted_data_properties($data_in, $wanted = array('type', 'title', 'description', 'image_thumbnail_url', 'image_medium_url', 'image_large_url')) {		
		$FeatureCollection = json_decode($data_in);
		
		$FeatureCollection = self::stringify_numbers($FeatureCollection);
		
		//self::debug($FeatureCollection);
		
		if($FeatureCollection && sizeof($FeatureCollection->features)) {	
			foreach($FeatureCollection->features as &$feature) {
				//No existing properties
				if(! property_exists($feature, 'properties')) {
					return json_encode($FeatureCollection);	
				}
				
				$properties_out = new stdClass();
				foreach($wanted as $key) {
					if(property_exists($feature->properties, $key)) {
						$properties_out->{$key} = (string)$feature->properties->{$key};
					}					
				}
				//Update
				$feature->properties = $properties_out;
			}
		}		
		
		$data_out = json_encode($FeatureCollection);
		
		return $data_out;	
	}

	static public function set_map_data_property($map_data, $key = false, $value = false, $append = false) {
		$FeatureCollection = json_decode($map_data);
		
		//Ensure valid data		
		if($FeatureCollection && sizeof($FeatureCollection->features)) {	
			foreach($FeatureCollection->features as &$feature) {
				//No existing properties
				if(! property_exists($feature, 'properties')) {
					$feature->properties = new stdClass();
				}
				
				//Set
				if(! property_exists($feature->properties, $key)) {
					$feature->properties->{$key} = $value;								
				//Update
				} else {
					if($append) {
						$feature->properties->{$key} .= $value;				
					} else {
						$feature->properties->{$key} = $value;				
					}				
				}
			}
		//Invalid data
		} else {
			return $map_data;
		}
		
		return json_encode($FeatureCollection);
	}	

	static public function add_map_link_to_description($map_id, $map_title = false, $map_data) {
		$desc_append = '<div class="waymark-description-link">';
		$desc_append .= esc_html__('Part of', 'waymark-plugin') . ' <b>';
		if($map_title) {
			$desc_append .= '<a href="' . get_permalink($map_id) . '">' . $map_title . '</a>';
		} else {
			$desc_append .= '<a href="' . get_permalink($map_id) . '">' . esc_html__('Map', 'waymark-plugin') . '</a>';
		}
		$desc_append .= '</b>.</div>';
		
		return Waymark_Helper::set_map_data_property($map_data, 'description', $desc_append, true);												
	}
	
	static public function map_data_to_objects($map_data) {
		$objects = array(
			'markers' => array(),
			'lines' => array(),
			'shapes' => array()		
		);

		$FeatureCollection = json_decode($map_data);
		
		//Ensure valid data		
		if($FeatureCollection && sizeof($FeatureCollection->features)) {	
			foreach($FeatureCollection->features as $feature) {
				if(isset($feature->geometry->type)) {
					switch($feature->geometry->type) {
						case 'Point' :
							//Waymark_Helper::debug($feature);
					
							//Circle
							if(isset($feature->properties->radius)) {
								$objects['shapes'][$feature->properties->type][] = $feature->properties;										
							//Marker
							} else {
								$objects['markers'][$feature->properties->type][] = $feature->properties;					
							}

							break;

						case 'LineString' :
						case 'MultiLineString' :
							$objects['lines'][$feature->properties->type][] = $feature->properties;

							break;
						case 'Polygon' :
							$objects['shapes'][$feature->properties->type][] = $feature->properties;
					
							break;
					}
				}
			}	

			//Sort by size of child array
			foreach($objects as $type => &$objs) {
				//Thanks! https://stackoverflow.com/a/9455586/569788
				uasort($objs, function($a, $b){
					return (count($b) - count($a));
				});
			}

		}
	
		return $objects;
	}	

	public static function convert_single_value_to_array($value_in) {
		//Array
		if(is_array($value_in)) {
			$array_out = array();
		
			foreach($value_in as $key => $value) {
				$multi = explode(Waymark_Config::get_item('multi_value_seperator'), $value);			

				$count = 0;
				foreach($multi as $m) {
					$array_out[$count][$key] = $m;
	//				Waymark_Helper::debug($m, false);
				
					$count++;
				}			
			}	
		
			return $array_out;		
		//String
		} else {
			return explode(Waymark_Config::get_item('multi_value_seperator'), $value_in);			
		}
	}	

	public static function convert_values_to_single_value($array_in) {
		$array_out = array();
		
		if(! is_array($array_in)) {
			return $array_out;
		}
					
		foreach($array_in as $key => $value) {
			//Single value
			if(! is_array($value)) {
				//Use that
				$array_out[$key] = $value;
			//Multiple values
			} else {
				//Single value, use that
				$array_out[$key] = implode(Waymark_Config::get_item('multi_value_seperator'), $value);
			}
		}	
		
		return $array_out;
	}

	public static function multi_use_as_key($array_in, $as_key = false) {
		$array_out = array();
			
		$count = 0;
		foreach($array_in as $data) {
			if(is_array($data) && $as_key && array_key_exists($as_key, $data)) {
				$out_key = self::make_key($data[$as_key]);
			} else {
				$out_key = $count;
			}

			$array_out[$out_key] = $data;			

			$count++;						
		 }	
		
		return $array_out;
	}	
	
	public static function get_object_types($type = 'marker', $use_key = false, $as_options = false) {
		$marker_types = Waymark_Config::get_item($type . 's', $type . '_types');
		$marker_types = self::convert_values_to_single_value($marker_types);
		$marker_types = self::convert_single_value_to_array($marker_types);	
		
		//Use keys
		if($use_key) {
			$marker_types = self::multi_use_as_key($marker_types, $use_key);			
			
			//Convert to dropdown
			if($as_options) {
				foreach($marker_types as $key => $data) {
					if(array_key_exists($use_key, $data)) {
						$marker_types[$key] = $data[$use_key];
					} else {
						$marker_types[$key] = $key;					
					}
				}
			}
		}	
		
		return $marker_types;
	}

	public static function array_string_to_array($string) {
		$string = str_replace(array('[',']','"','"'), array('','','',''), $string);
		
		return self::comma_string_to_array($string);
	}
	
	public static function comma_string_to_array($string) {
		//Process options
		$options_exploded = explode(',', $string);
		$options_array = array();
		foreach($options_exploded as $option) {
			$value = trim($option);
			$key = self::make_key($value);
		
			$options_array[$key] = $value;
		}
	
		return $options_array;
	}
	
	static public function geojson_feature_count($geojson) {		
		$FeatureCollection = json_decode($geojson);
		
		if($FeatureCollection && is_array($FeatureCollection->features)) {	
			return sizeof($FeatureCollection->features);
		}		
			
		return false;	
	}	
	
	static public function map_export_html($Map) {
		if(! isset($Map->post_id) || ! isset($Map->post_title)) {
			return false;
		}
		
		$out  = '<div id="waymark-map-export" data-map_id="' . $Map->post_id . '" data-map_slug="' . sanitize_title($Map->post_title) . '">' . "\n";
		$out .= '	<select name="export_format">' . "\n";
		$out .= '		<option value="geojson">GeoJSON</option>' . "\n";
		$out .= '		<option value="gpx">GPX</option>' . "\n";
		$out .= '		<option value="kml">KML</option>' . "\n";			
		$out .= '	</select>' . "\n";
		$out .= '	<a href="#" class="button">' . __('Download', 'waymark-plugin') . '</a>' . "\n";
		$out .= '</div>' . "\n";
		
		return $out;
	}	

	static public function get_section_repeatable_count($section_data) {
		$first_field = $section_data['fields'][array_keys($section_data['fields'])[0]];
		
		if(is_array($first_field['default'])) {
			return sizeof($first_field['default']);
		}

		return false;	
	}
}
