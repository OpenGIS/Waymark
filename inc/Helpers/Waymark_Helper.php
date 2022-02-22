<?php

class Waymark_Helper {

	static public function require($path = '') {
		$path = plugin_dir_path(__DIR__) . $path;
		
		require_once($path);		
	}
	
	static public function plugin_about() {
		$out = '';
		
		$out .= '	<div id="waymark-about">' . "\n";	
		$out .= '		<img width="75" height="75" alt="Joe\'s mug" src="//www.josephhawes.co.uk/assets/images/Joe1BW.jpg" />' . "\n";		
		$out .= '		<p class="waymark-first"><b>' . sprintf(esc_html__("Hi, I'm %s.", 'waymark'), "Joe") . '</b></p>' . "\n";		

		$out .= '		<p>' . __('Waymark is <strong>open source</strong> and a work in progress.', 'waymark') . '</p>' . "\n";		
		$out .= '		<p>' . __('Feedback is very important, so please feel free to get in touch by either:', 'waymark') . '</p>' . "\n";		

		$out .= '		<ul>' . "\n";	
		$out .= '			<li>' . sprintf(__('Leaving a <strong><a href="%s">review</a></strong>, or creating a <a href="%s">support topic</a> on the WordPress plugin directory.', 'waymark'), 'https://wordpress.org/support/plugin/waymark/reviews/#new-post', 'https://wordpress.org/support/plugin/waymark/#new-topic-0') . '</li>' . "\n";	
		$out .= '			<li>' . sprintf(__('Posting in the <a href="%s">Forums</a>.', 'waymark'), 'https://www.waymark.dev/forums/') . '</li>' . "\n";	
		$out .= '			<li>' . sprintf(__('Creating a <a href="%s">GitHub issue</a>.', 'waymark'), 'https://github.com/morehawes/waymark/issues/new') . '</li>' . "\n";	
		$out .= '		</ul>' . "\n";	

		$out .= '		<p>' . esc_html__('Thanks', 'waymark') . '!</p>' . "\n";		

		$out .= '		<hr />' . "\n";		

		$out .= '		<p>' . esc_html__('A big thank you to the following. Without their work this plugin would not be possible:', 'waymark') . '</p>' . "\n";		

		$out .= '		<ul>' . "\n";		
		$out .= '			<li><a href="https://wordpress.org/">WordPress</a></li>' . "\n";		
		$out .= '			<li><a href="https://www.openstreetmap.org/">OpenStreetMap</a></li>' . "\n";				
		$out .= '			<li><a href="https://leafletjs.com/">Leaflet</a></li>' . "\n";				
		$out .= '			<li><a href="https://jquery.com/">jQuery</a></li>' . "\n";		
		$out .= '			<li><a href="https://github.com/mapbox/togeojson">mapbox/togeojson</a></li>' . "\n";				
		$out .= '			<li><a href="https://ionic.io/ionicons/v2/cheatsheet.html">Ionicons</a></li>' . "\n";				
		$out .= '			<li><a href="https://fontawesome.com/v4.7.0/">Font Awesome</a></li>' . "\n";				
		$out .= '			<li><a href="https://github.com/Leaflet/Leaflet.Editable">Leaflet.Editable</a></li>' . "\n";	
		$out .= '			<li><a href="https://github.com/CliffCloud/Leaflet.Sleep">Leaflet.Sleep</a></li>' . "\n";	
		$out .= '			<li><a href="https://github.com/perliedman/leaflet-control-geocoder">leaflet-control-geocoder</a></li>' . "\n";	
		$out .= '			<li><a href="https://github.com/Raruto/leaflet-elevation">leaflet-elevation</a></li>' . "\n";			
		$out .= '			<li><a href="https://github.com/maphubs/tokml">tokml</a></li>' . "\n";			
		$out .= '			<li><a href="https://github.com/tyrasd/togpx">togpx</a></li>' . "\n";			
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
		$out .= '			<li class="waymark-multi">' . esc_html__('and', 'waymark') . " \n";	
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
		return '<img class="waymark-logo" alt="' . Waymark_Config::get_name() . '" src="' . self::asset_url('img/waymark-icon-' . $colour . '.png') . '" width="' . $width . '" height="' . $height . '" />';
	}
	
	static public function site_url($url_path = '') {
		return Waymark_Config::get_item('site_url') . $url_path;
	}

	static public function asset_url($file_path = '') {	
		return plugin_dir_url('') . 'waymark/assets/' . $file_path;
	}

	static public function plugin_path($append = '') {
		return Waymark_Config::get_item('site_url') . $url_path;
	}
	
	static public function http_url($data = array()) {
		return trim(add_query_arg(array_merge(array('waymark_http' => '1'), $data), home_url('/')), '/');
	}
	
	static function waymark_array_random_assoc($arr, $num = 1) {
			$keys = array_keys($arr);
			shuffle($keys);
 
			$r = array();
			for ($i = 0; $i < $num; $i++) {
					$r[$keys[$i]] = $arr[$keys[$i]];
			}
			return $r;
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

	static public function get_map_meta($Map, $context = 'map_single') {
		$map_meta = array();

		// =============== PREPEND ===============

		//Thumbnail?
		$map_thumbnail = get_the_post_thumbnail($Map->post_id, 'large', array(
			'class' => 'waymark-map-thumbnail',
			'width' => '',
			'height' => ''
		));
		if($map_thumbnail) {
			if($context == 'shortcode') {
				$map_thumbnail = '<a href="' . get_permalink($Map->post_id) . '">' . $map_thumbnail . '</a>';
			}
			
			$map_meta['map_thumbnail'] = array(
				'meta_key' => 'map_thumbnail',
				'meta_value' => $map_thumbnail,
				'meta_title' => '',
				'meta_group' => ''					
			);
		}
		
		// =============== SETTINGS ===============

		$settings_meta = Waymark_Config::get_item('meta', 'inputs', true);		
			
		//For each setting
		foreach($settings_meta as $setting_meta) {
			//Shortcode output setting
			//If we are displaying the shortcode *and* there is a meta_shortcode setting *AND* it's set to false
			if($context == 'shortcode' && (isset($setting_meta['meta_shortcode']) && ! $setting_meta['meta_shortcode'])) {
				//Don't display this
				continue;
			}
			
			$meta_key = self::make_key($setting_meta['meta_title'], 'map');

			//If we have data
			if(array_key_exists($meta_key, $Map->data) && ! empty($Map->data[$meta_key])) {
				$data = array(
					'meta_key' => $meta_key,
					'meta_title' => $setting_meta['meta_title'],
					'meta_group' => isset($setting_meta['meta_group']) ? $setting_meta['meta_group'] : ''
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
				
				$map_meta[$meta_key] = $data;
			}
		}

		// =============== APPEND ===============

		//Display Collections?
		if(Waymark_Config::get_setting('misc', 'collection_options', 'link_from_maps')) {
			$collection_list = get_the_term_list($Map->post_id, 'waymark_collection', '', '<!--,-->, ');
			if($collection_list) {
		
				$meta_title = esc_html__('Collection', 'waymark');
// 					if(strpos($collection_list, '<!--,-->')) {
// 						$meta_title .= 's';
// 					}				
		
				$map_meta['collection_list'] = array(
					'meta_key' => 'collection_list',
					'meta_title' => $meta_title,
					'meta_value' => $collection_list,
					'meta_group' => ''					
				);
			}
		}
					
		//Add Export dropdown/link
		$has_features = array_key_exists('map_data', $Map->data) && Waymark_Helper::geojson_feature_count($Map->data['map_data']);
		if($has_features && Waymark_Config::get_setting('misc', 'map_options', 'allow_export') == true) {			
			$map_meta['export_data'] = array(
				'meta_key' => 'export_data',
				'meta_title' => esc_html__('Export', 'waymark'),
				'meta_value' => Waymark_Helper::map_export_html($Map),
				'meta_info' => '<a data-title="' . esc_attr__('This will download the Overlays currently displayed by the Map in the selected format.', 'waymark') . '" href="#" onclick="return false;" class="waymark-tooltip">?</a>',
				'meta_group' => ''					
			);
		}			
		
// 		Waymark_Helper::debug($map_meta);
		
		return $map_meta;
	}

	static public function get_meta_groups() {
		//Get Groups
		$meta_groups = Waymark_Config::get_item('meta', 'groups', true);
		$meta_groups = self::multi_use_as_key($meta_groups, 'group_title');	
		
		return $meta_groups;	
	}
	
	static public function group_meta($meta_array, $meta_groups = []) {
		if(! $meta_groups) {
			//Get Groups
			$meta_groups = Waymark_Helper::get_meta_groups();
		}
		
		//Sort into groups
		$meta_grouped = [];
		foreach($meta_array as $meta) {	
			//Group exists
			if(array_key_exists($meta['meta_group'], $meta_groups)) {
				$meta_grouped[$meta['meta_group']][$meta['meta_key']] = $meta;				
			//Group does not exist
			} else {
				//Add to "None"
				$meta_grouped[''][$meta['meta_key']] = $meta;									
			}
		}
		
		return $meta_grouped;
	}

	static public function map_meta_html($meta_array = array()) {
		$out = '';
		
		//Do we have data?		
		if(is_array($meta_array) && sizeof($meta_array)) {
			//Get Meta Groups
			$meta_groups = Waymark_Helper::get_meta_groups();

			//Sort into groups
			$meta_grouped = Waymark_Helper::group_meta($meta_array, $meta_groups);
			
			//Container					
			$out = '<!-- START Parameter Container -->' . "\n";
			$out .= '<div class="waymark-map-meta waymark-accordion-container waymark-meta-count-' . sizeof($meta_array) . '">' . "\n";	

			//Do ungrouped first
			if(isset($meta_grouped[''])) {
				$out .= '	<div class="waymark-map-meta-ungrouped waymark-self-clear">' . "\n";	

				foreach($meta_grouped[''] as $meta) {
					$out .= self::meta_entry_html($meta);			
				}
				$out .= '	</div>' . "\n";	

				unset($meta_grouped['']);
			}
			
			//Iterate by group
			if(sizeof($meta_groups)) {
				$out .= '	<div class="waymark-map-meta-grouped">' . "\n";	

				foreach($meta_groups as $group_key => $meta_groups) {
					//Do we have meta for this group?
					if(isset($meta_grouped[$group_key])) {
						$out .= '		<div class="waymark-meta-group waymark-accordion-group waymark-meta-group-' . $group_key . '">' . "\n";			

						$group_meta = $meta_grouped[$group_key];

						//Container
						$out .= '			<legend class="waymark-meta-group-title" title="Click to expand">' . $meta_groups['group_title'] . '</legend>' . "\n";			
						$out .= '			<div class="waymark-accordion-group-content">' . "\n";			
				
						//Each meta in group
						foreach($group_meta as $meta) {	
							$out .= self::meta_entry_html($meta);			
						}

						$out .= '			</div>' . "\n";								
						$out .= '		</div>' . "\n";								
					}
				}			
				$out .= '	</div>' . "\n";	
			}

			$out .= '</div>' . "\n";	
			$out .= '<!-- END Parameter Container -->' . "\n";			
		}
		
		return $out;
	}

	static public function meta_entry_html($meta) {
		$out = '';
		
		//Ensure we have a value
		if(! isset($meta['meta_value'])) {
     return $out;
		}		
		
		$out .= '	<div class="waymark-meta-item waymark-meta-' . $meta['meta_key'] . '">' . "\n";			
		//Special case
		if($meta['meta_key'] == 'map_description') {
			$out .= '		<div colspan="3" class="waymark-meta-content">' . $meta['meta_value'] . '</div>' . "\n";						
		} else {
			$out .= '		<div class="waymark-meta-info">' . "\n";
			if(array_key_exists('meta_info', $meta) && ! empty($meta['meta_info'])) {
				$out .= $meta['meta_info'];
			}
			$out .= '		</div>' . "\n";	
			$out .= '		<div class="waymark-meta-title" scope="row">' . $meta['meta_title'] . '</div>' . "\n";	
			$out .= '		<div class="waymark-meta-content">' . $meta['meta_value'] . '</div>' . "\n";				
		}					
		$out .= '	</div>' . "\n";	
		
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

	static public function remove_unwanted_data_properties($data_in, $wanted = array('radius', 'type', 'title', 'description', 'image_thumbnail_url', 'image_medium_url', 'image_large_url')) {		
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
		$desc_append .= esc_html__('Part of', 'waymark') . ' <b>';
		if($map_title) {
			$desc_append .= '<a href="' . get_permalink($map_id) . '">' . $map_title . '</a>';
		} else {
			$desc_append .= '<a href="' . get_permalink($map_id) . '">' . esc_html__('Map', 'waymark') . '</a>';
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
		$object_types = Waymark_Config::get_item($type . 's', $type . '_types', true);

		//Use keys
		if($use_key) {
			$object_types = self::multi_use_as_key($object_types, $use_key);			
			
			//Convert to dropdown
			if($as_options) {
				foreach($object_types as $key => $data) {
					if(array_key_exists($use_key, $data)) {
						$object_types[$key] = $data[$use_key];
					} else {
						$object_types[$key] = $key;					
					}
				}
			}
		}	
		
		return $object_types;
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
		
		$element = (is_admin()) ? 'div' : 'form';
		
		$out  = '<' . $element . ' action="' . Waymark_Helper::http_url() . '" method="post" id="waymark-map-export-' . $Map->post_id . '" class="waymark-map-export" data-map_id="' . $Map->post_id . '" data-map_slug="' . sanitize_title($Map->post_title) . '">' . "\n";
		$out .= '	<select name="export_format">' . "\n";
		$out .= '		<option value="gpx">GPX</option>' . "\n";
		$out .= '		<option value="kml">KML</option>' . "\n";			
		$out .= '		<option value="geojson">GeoJSON</option>' . "\n";
		$out .= '	</select>' . "\n";
		$out .= '	<input type="hidden" name="waymark_action" value="download_map_data" />' . "\n";
		$out .= '	<input type="hidden" name="waymark_security" value="' . wp_create_nonce(Waymark_Config::get_item('nonce_string')) . '" />' . "\n";
		$out .= '	<input type="hidden" name="map_data" value="" />' . "\n";
		$out .= '	<input type="hidden" name="map_id" value="' . $Map->post_id . '" />' . "\n";
		$out .= '	<input type="submit" value="' . __('Download', 'waymark') . '" class="button" />' . "\n";
		$out .= '</' . $element . '>' . "\n";
		
		return $out;
	}	

	static public function get_section_repeatable_count($section_data) {
		$first_field = $section_data['fields'][array_keys($section_data['fields'])[0]];
		
		if(is_array($first_field['default'])) {
			return sizeof($first_field['default']);
		}

		return false;	
	}
	
	static public function repeatable_setting_option_array($tab, $section, $key) {
		$options_array = array();
		$values = Waymark_Config::get_item($tab, $section, true);
		
		if(! is_array($values)) {
			return null;
		}
		
		foreach($values as $s) {
			//If exists
			if(array_key_exists($key, $s)) {
				//Add as option
				$options_array[Waymark_Helper::make_key($s[$key])] = $s[$key];
			}		
		}
		
		return $options_array;
	}
	
	/**
	 * =====================================
	 * ============ Submission =============
	 * =====================================
	 */
 
	static function country_code_to_bounds($country_code = '') {
		$country_bounding_boxes = [
			'AF' => [60.5284298033, 29.318572496, 75.1580277851, 38.4862816432],
			'AO' => [11.6400960629, -17.9306364885, 24.0799052263, -4.43802336998],
			'AL' => [19.3044861183, 39.624997667, 21.0200403175, 42.6882473822],
			'AE' => [51.5795186705, 22.4969475367, 56.3968473651, 26.055464179],
			'AR' => [-73.4154357571, -55.25, -53.628348965, -21.8323104794],
			'AM' => [43.5827458026, 38.7412014837, 46.5057198423, 41.2481285671],
			'AQ' => [-180.0, -90.0, 180.0, -63.2706604895],
			'TF' => [68.72, -49.775, 70.56, -48.625],
			'AU' => [113.338953078, -43.6345972634, 153.569469029, -10.6681857235],
			'AT' => [9.47996951665, 46.4318173285, 16.9796667823, 49.0390742051],
			'AZ' => [44.7939896991, 38.2703775091, 50.3928210793, 41.8606751572],
			'BI' => [29.0249263852, -4.49998341229, 30.752262811, -2.34848683025],
			'BE' => [2.51357303225, 49.5294835476, 6.15665815596, 51.4750237087],
			'BJ' => [0.772335646171, 6.14215770103, 3.79711225751, 12.2356358912],
			'BF' => [-5.47056494793, 9.61083486576, 2.17710778159, 15.1161577418],
			'BD' => [88.0844222351, 20.670883287, 92.6727209818, 26.4465255803],
			'BG' => [22.3805257504, 41.2344859889, 28.5580814959, 44.2349230007],
			'BS' => [-78.98, 23.71, -77.0, 27.04],
			'BA' => [15.7500260759, 42.65, 19.59976, 45.2337767604],
			'BY' => [23.1994938494, 51.3195034857, 32.6936430193, 56.1691299506],
			'BZ' => [-89.2291216703, 15.8869375676, -88.1068129138, 18.4999822047],
			'BO' => [-69.5904237535, -22.8729187965, -57.4983711412, -9.76198780685],
			'BR' => [-73.9872354804, -33.7683777809, -34.7299934555, 5.24448639569],
			'BN' => [114.204016555, 4.007636827, 115.450710484, 5.44772980389],
			'BT' => [88.8142484883, 26.7194029811, 92.1037117859, 28.2964385035],
			'BW' => [19.8954577979, -26.8285429827, 29.4321883481, -17.6618156877],
			'CF' => [14.4594071794, 2.2676396753, 27.3742261085, 11.1423951278],
			'CA' => [-140.99778, 41.6751050889, -52.6480987209, 83.23324],
			'CH' => [6.02260949059, 45.7769477403, 10.4427014502, 47.8308275417],
			'CL' => [-75.6443953112, -55.61183, -66.95992, -17.5800118954],
			'CN' => [73.6753792663, 18.197700914, 135.026311477, 53.4588044297],
			'CI' => [-8.60288021487, 4.33828847902, -2.56218950033, 10.5240607772],
			'CM' => [8.48881554529, 1.72767263428, 16.0128524106, 12.8593962671],
			'CD' => [12.1823368669, -13.2572266578, 31.1741492042, 5.25608775474],
			'CG' => [11.0937728207, -5.03798674888, 18.4530652198, 3.72819651938],
			'CO' => [-78.9909352282, -4.29818694419, -66.8763258531, 12.4373031682],
			'CR' => [-85.94172543, 8.22502798099, -82.5461962552, 11.2171192489],
			'CU' => [-84.9749110583, 19.8554808619, -74.1780248685, 23.1886107447],
			'CY' => [32.2566671079, 34.5718694118, 34.0048808123, 35.1731247015],
			'CZ' => [12.2401111182, 48.5553052842, 18.8531441586, 51.1172677679],
			'DE' => [5.98865807458, 47.3024876979, 15.0169958839, 54.983104153],
			'DJ' => [41.66176, 10.9268785669, 43.3178524107, 12.6996385767],
			'DK' => [8.08997684086, 54.8000145534, 12.6900061378, 57.730016588],
			'DO' => [-71.9451120673, 17.598564358, -68.3179432848, 19.8849105901],
			'DZ' => [-8.68439978681, 19.0573642034, 11.9995056495, 37.1183806422],
			'EC' => [-80.9677654691, -4.95912851321, -75.2337227037, 1.3809237736],
			'EG' => [24.70007, 22.0, 36.86623, 31.58568],
			'ER' => [36.3231889178, 12.4554157577, 43.0812260272, 17.9983074],
			'ES' => [-9.39288367353, 35.946850084, 3.03948408368, 43.7483377142],
			'EE' => [23.3397953631, 57.4745283067, 28.1316992531, 59.6110903998],
			'ET' => [32.95418, 3.42206, 47.78942, 14.95943],
			'FI' => [20.6455928891, 59.846373196, 31.5160921567, 70.1641930203],
			'FJ' => [-180.0, -18.28799, 180.0, -16.0208822567],
			'FK' => [-61.2, -52.3, -57.75, -51.1],
			'FR' => [-54.5247541978, 2.05338918702, 9.56001631027, 51.1485061713],
			'GA' => [8.79799563969, -3.97882659263, 14.4254557634, 2.32675751384],
			'GB' => [-7.57216793459, 49.959999905, 1.68153079591, 58.6350001085],
			'GE' => [39.9550085793, 41.0644446885, 46.6379081561, 43.553104153],
			'GH' => [-3.24437008301, 4.71046214438, 1.0601216976, 11.0983409693],
			'GN' => [-15.1303112452, 7.3090373804, -7.83210038902, 12.5861829696],
			'GM' => [-16.8415246241, 13.1302841252, -13.8449633448, 13.8764918075],
			'GW' => [-16.6774519516, 11.0404116887, -13.7004760401, 12.6281700708],
			'GQ' => [9.3056132341, 1.01011953369, 11.285078973, 2.28386607504],
			'GR' => [20.1500159034, 34.9199876979, 26.6041955909, 41.8269046087],
			'GL' => [-73.297, 60.03676, -12.20855, 83.64513],
			'GT' => [-92.2292486234, 13.7353376327, -88.2250227526, 17.8193260767],
			'GY' => [-61.4103029039, 1.26808828369, -56.5393857489, 8.36703481692],
			'HN' => [-89.3533259753, 12.9846857772, -83.147219001, 16.0054057886],
			'HR' => [13.6569755388, 42.47999136, 19.3904757016, 46.5037509222],
			'HT' => [-74.4580336168, 18.0309927434, -71.6248732164, 19.9156839055],
			'HU' => [16.2022982113, 45.7594811061, 22.710531447, 48.6238540716],
			'ID' => [95.2930261576, -10.3599874813, 141.03385176, 5.47982086834],
			'IN' => [68.1766451354, 7.96553477623, 97.4025614766, 35.4940095078],
			'IE' => [-9.97708574059, 51.6693012559, -6.03298539878, 55.1316222195],
			'IR' => [44.1092252948, 25.0782370061, 63.3166317076, 39.7130026312],
			'IQ' => [38.7923405291, 29.0990251735, 48.5679712258, 37.3852635768],
			'IS' => [-24.3261840479, 63.4963829617, -13.609732225, 66.5267923041],
			'IL' => [34.2654333839, 29.5013261988, 35.8363969256, 33.2774264593],
			'IT' => [6.7499552751, 36.619987291, 18.4802470232, 47.1153931748],
			'JM' => [-78.3377192858, 17.7011162379, -76.1996585761, 18.5242184514],
			'JO' => [34.9226025734, 29.1974946152, 39.1954683774, 33.3786864284],
			'JP' => [129.408463169, 31.0295791692, 145.543137242, 45.5514834662],
			'KZ' => [46.4664457538, 40.6623245306, 87.3599703308, 55.3852501491],
			'KE' => [33.8935689697, -4.67677, 41.8550830926, 5.506],
			'KG' => [69.464886916, 39.2794632025, 80.2599902689, 43.2983393418],
			'KH' => [102.3480994, 10.4865436874, 107.614547968, 14.5705838078],
			'KR' => [126.117397903, 34.3900458847, 129.468304478, 38.6122429469],
			'KW' => [46.5687134133, 28.5260627304, 48.4160941913, 30.0590699326],
			'LA' => [100.115987583, 13.88109101, 107.564525181, 22.4647531194],
			'LB' => [35.1260526873, 33.0890400254, 36.6117501157, 34.6449140488],
			'LR' => [-11.4387794662, 4.35575511313, -7.53971513511, 8.54105520267],
			'LY' => [9.31941084152, 19.58047, 25.16482, 33.1369957545],
			'LK' => [79.6951668639, 5.96836985923, 81.7879590189, 9.82407766361],
			'LS' => [26.9992619158, -30.6451058896, 29.3251664568, -28.6475017229],
			'LT' => [21.0558004086, 53.9057022162, 26.5882792498, 56.3725283881],
			'LU' => [5.67405195478, 49.4426671413, 6.24275109216, 50.1280516628],
			'LV' => [21.0558004086, 55.61510692, 28.1767094256, 57.9701569688],
			'MA' => [-17.0204284327, 21.4207341578, -1.12455115397, 35.7599881048],
			'MD' => [26.6193367856, 45.4882831895, 30.0246586443, 48.4671194525],
			'MG' => [43.2541870461, -25.6014344215, 50.4765368996, -12.0405567359],
			'MX' => [-117.12776, 14.5388286402, -86.811982388, 32.72083],
			'MK' => [20.46315, 40.8427269557, 22.9523771502, 42.3202595078],
			'ML' => [-12.1707502914, 10.0963607854, 4.27020999514, 24.9745740829],
			'MM' => [92.3032344909, 9.93295990645, 101.180005324, 28.335945136],
			'ME' => [18.45, 41.87755, 20.3398, 43.52384],
			'MN' => [87.7512642761, 41.5974095729, 119.772823928, 52.0473660345],
			'MZ' => [30.1794812355, -26.7421916643, 40.7754752948, -10.3170960425],
			'MR' => [-17.0634232243, 14.6168342147, -4.92333736817, 27.3957441269],
			'MW' => [32.6881653175, -16.8012997372, 35.7719047381, -9.23059905359],
			'MY' => [100.085756871, 0.773131415201, 119.181903925, 6.92805288332],
			'NA' => [11.7341988461, -29.045461928, 25.0844433937, -16.9413428687],
			'NC' => [164.029605748, -22.3999760881, 167.120011428, -20.1056458473],
			'NE' => [0.295646396495, 11.6601671412, 15.9032466977, 23.4716684026],
			'NG' => [2.69170169436, 4.24059418377, 14.5771777686, 13.8659239771],
			'NI' => [-87.6684934151, 10.7268390975, -83.147219001, 15.0162671981],
			'NL' => [3.31497114423, 50.803721015, 7.09205325687, 53.5104033474],
			'NO' => [4.99207807783, 58.0788841824, 31.29341841, 80.6571442736],
			'NP' => [80.0884245137, 26.3978980576, 88.1748043151, 30.4227169866],
			'NZ' => [166.509144322, -46.641235447, 178.517093541, -34.4506617165],
			'OM' => [52.0000098, 16.6510511337, 59.8080603372, 26.3959343531],
			'PK' => [60.8742484882, 23.6919650335, 77.8374507995, 37.1330309108],
			'PA' => [-82.9657830472, 7.2205414901, -77.2425664944, 9.61161001224],
			'PE' => [-81.4109425524, -18.3479753557, -68.6650797187, -0.0572054988649],
			'PH' => [117.17427453, 5.58100332277, 126.537423944, 18.5052273625],
			'PG' => [141.000210403, -10.6524760881, 156.019965448, -2.50000212973],
			'PL' => [14.0745211117, 49.0273953314, 24.0299857927, 54.8515359564],
			'PR' => [-67.2424275377, 17.946553453, -65.5910037909, 18.5206011011],
			'KP' => [124.265624628, 37.669070543, 130.780007359, 42.9853868678],
			'PT' => [-9.52657060387, 36.838268541, -6.3890876937, 42.280468655],
			'PY' => [-62.6850571357, -27.5484990374, -54.2929595608, -19.3427466773],
			'QA' => [50.7439107603, 24.5563308782, 51.6067004738, 26.1145820175],
			'RO' => [20.2201924985, 43.6884447292, 29.62654341, 48.2208812526],
			'RU' => [-180.0, 41.151416124, 180.0, 81.2504],
			'RW' => [29.0249263852, -2.91785776125, 30.8161348813, -1.13465911215],
			'SA' => [34.6323360532, 16.3478913436, 55.6666593769, 32.161008816],
			'SD' => [21.93681, 8.61972971293, 38.4100899595, 22.0],
			'SS' => [23.8869795809, 3.50917, 35.2980071182, 12.2480077571],
			'SN' => [-17.6250426905, 12.332089952, -11.4678991358, 16.5982636581],
			'SB' => [156.491357864, -10.8263672828, 162.398645868, -6.59933847415],
			'SL' => [-13.2465502588, 6.78591685631, -10.2300935531, 10.0469839543],
			'SV' => [-90.0955545723, 13.1490168319, -87.7235029772, 14.4241327987],
			'SO' => [40.98105, -1.68325, 51.13387, 12.02464],
			'RS' => [18.82982, 42.2452243971, 22.9860185076, 46.1717298447],
			'SR' => [-58.0446943834, 1.81766714112, -53.9580446031, 6.0252914494],
			'SK' => [16.8799829444, 47.7584288601, 22.5581376482, 49.5715740017],
			'SI' => [13.6981099789, 45.4523163926, 16.5648083839, 46.8523859727],
			'SE' => [11.0273686052, 55.3617373725, 23.9033785336, 69.1062472602],
			'SZ' => [30.6766085141, -27.2858794085, 32.0716654803, -25.660190525],
			'SY' => [35.7007979673, 32.312937527, 42.3495910988, 37.2298725449],
			'TD' => [13.5403935076, 7.42192454674, 23.88689, 23.40972],
			'TG' => [-0.0497847151599, 5.92883738853, 1.86524051271, 11.0186817489],
			'TH' => [97.3758964376, 5.69138418215, 105.589038527, 20.4178496363],
			'TJ' => [67.4422196796, 36.7381712916, 74.9800024759, 40.9602133245],
			'TM' => [52.5024597512, 35.2706639674, 66.5461503437, 42.7515510117],
			'TL' => [124.968682489, -9.39317310958, 127.335928176, -8.27334482181],
			'TT' => [-61.95, 10.0, -60.895, 10.89],
			'TN' => [7.52448164229, 30.3075560572, 11.4887874691, 37.3499944118],
			'TR' => [26.0433512713, 35.8215347357, 44.7939896991, 42.1414848903],
			'TW' => [120.106188593, 21.9705713974, 121.951243931, 25.2954588893],
			'TZ' => [29.3399975929, -11.7209380022, 40.31659, -0.95],
			'UG' => [29.5794661801, -1.44332244223, 35.03599, 4.24988494736],
			'UA' => [22.0856083513, 44.3614785833, 40.0807890155, 52.3350745713],
			'UY' => [-58.4270741441, -34.9526465797, -53.209588996, -30.1096863746],
			'US' => [-171.791110603, 18.91619, -66.96466, 71.3577635769],
			'UZ' => [55.9289172707, 37.1449940049, 73.055417108, 45.5868043076],
			'VE' => [-73.3049515449, 0.724452215982, -59.7582848782, 12.1623070337],
			'VN' => [102.170435826, 8.59975962975, 109.33526981, 23.3520633001],
			'VU' => [166.629136998, -16.5978496233, 167.844876744, -14.6264970842],
			'PS' => [34.9274084816, 31.3534353704, 35.5456653175, 32.5325106878],
			'YE' => [42.6048726743, 12.5859504257, 53.1085726255, 19.0000033635],
			'ZA' => [16.3449768409, -34.8191663551, 32.830120477, -22.0913127581],
			'ZM' => [21.887842645, -17.9612289364, 33.4856876971, -8.23825652429],
			'ZW' => [25.2642257016, -22.2716118303, 32.8498608742, -15.5077869605]
		];
	
		if(array_key_exists($country_code, $country_bounding_boxes)) {
			return $country_bounding_boxes[$country_code];
		} else {
			$bounds = self::waymark_array_random_assoc($country_bounding_boxes);
			//Random
			return array_pop($bounds);

			//CA
			//return [-140.99778, 41.6751050889, -52.6480987209, 83.23324];
		
			//SE
			//return [11.0273686052, 55.3617373725, 23.9033785336, 69.1062472602];
			
			//GB
			//return [-7.57216793459, 49.959999905, 1.68153079591, 58.6350001085];
		}
	}	

	static function allowable_file($ext = '', $mime = '', $file_image = 'file') {
		$allowable_mimes = Waymark_Config::get_item('mimes', $file_image);
		
		//Valid extension
		if(array_key_exists($ext, $allowable_mimes)) {
			if($mime === false) {
				return true;
			}
			
			//Check MIME
			//Single
			if(is_string($allowable_mimes[$ext])) {
				return $mime == $allowable_mimes[$ext];
			//Multiple
			} elseif(is_array($allowable_mimes[$ext])) {
				return in_array($mime, $allowable_mimes[$ext]);
			}
		}
		
		return false;
	}	
}