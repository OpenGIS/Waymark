<?php
class Waymark_Config {
	//Set defaults
	private static $data = array();
	private static $default = array();
	
	public static function init() {
		$multi_value_seperator = '__multi__';
		
		self::$data = array(
			'plugin_name' => 'Waymark',
			'plugin_name_short' => 'Waymark',		
			'custom_types' => array(),
			'plugin_version' => '0.9.14',
			'site_url' => 'https://www.joesway.ca/waymark/',
			'multi_value_seperator' => $multi_value_seperator,
			'shortcode' => 'Waymark',
			'meta' => array(
				'inputs' => array(
					'meta_title' => esc_html__('Description', 'waymark-plugin'),
					'meta_default' => '',
					'meta_tip' => esc_html__('The Description you enter here will be displayed on the Map Details page.', 'waymark-plugin'),
					'meta_type' => 'textarea_rich',
					'meta_options' => ''										
				)
			),
			'misc' => array(
				'map_options' => array(
					'map_default_latlng' => '51.38436,-68.74923',
					'map_default_zoom' => '9',
					'map_height' => '450',
					'show_type_labels' => '1',
					'button_position' => 'topleft',
					'show_gallery' => '1',
					'show_filter' => '1',
					'allow_export' => '0'						
				),
				'collection_options' => array(
					'link_to_maps' => '1',
					'link_from_maps' => '1'					
				),
				'shortcode_options' => array(
					'shortcode_header' => '1',
					'header_override' => '0'
				),				
				'editor_options' => array(
					'confirm_delete' => '1',
					'editor_basemap' => 'Open Street Map'
				),
				'elevation_options' => array(
					'show_elevation' => '2',
					'elevation_units' => 'metric',
					'elevation_colour' => '#b42714',
					'elevation_initial' => '1'
				),				
				'advanced' => array(
					'debug_mode' => '0'
				)
			),
			'tiles' => array(
				'layers' => array(
			    'layer_name' => 'Open Street Map',
			    'layer_url' => 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', 
			    'layer_attribution' => '© &lt;a href=&quot;https://www.openstreetmap.org/copyright&quot;&gt;OpenStreetMap&lt;/a&gt; contributors'
				)
			),
			'markers' => array(
				'marker_types' => array(
					'marker_title' => esc_html__('Photo', 'waymark-plugin') . $multi_value_seperator . esc_html__('Information', 'waymark-plugin') . $multi_value_seperator . esc_html__('Alert', 'waymark-plugin') . $multi_value_seperator . esc_html__('Trail Access', 'waymark-plugin') . $multi_value_seperator . esc_html__('Food', 'waymark-plugin') . $multi_value_seperator . esc_html__('Water', 'waymark-plugin') . $multi_value_seperator . esc_html__('Shelter', 'waymark-plugin') . $multi_value_seperator . esc_html__('Beer', 'waymark-plugin') . $multi_value_seperator . esc_html__('Start', 'waymark-plugin') . $multi_value_seperator . esc_html__('Finish', 'waymark-plugin'),
					'marker_icon' => 'ion-camera' . $multi_value_seperator . 'ion-information-circled' . $multi_value_seperator . 'ion-android-alert' . $multi_value_seperator . 'ion-android-bicycle' . $multi_value_seperator . 'ion-pizza' . $multi_value_seperator . 'ion-waterdrop' . $multi_value_seperator . 'ion-home' . $multi_value_seperator . 'ion-beer' . $multi_value_seperator . 'ion-power' . $multi_value_seperator . 'ion-power',
					'marker_colour' => 'white' . $multi_value_seperator . 'white' . $multi_value_seperator . 'red' . $multi_value_seperator . 'green' . $multi_value_seperator . 'red' . $multi_value_seperator . 'blue' . $multi_value_seperator . 'darkgreen' . $multi_value_seperator . 'white' . $multi_value_seperator . 'green' . $multi_value_seperator . 'darkred',
					'icon_colour' => '#475260' . $multi_value_seperator . '#0069a5' . $multi_value_seperator . 'white' . $multi_value_seperator . 'white' . $multi_value_seperator . '#ffba00' . $multi_value_seperator . '#fff' . $multi_value_seperator . 'white' . $multi_value_seperator . '#754423' . $multi_value_seperator . 'white' . $multi_value_seperator . 'white',
					'marker_display' => '1' . $multi_value_seperator . '1' . $multi_value_seperator . '1' . $multi_value_seperator . '1' . $multi_value_seperator . '1' . $multi_value_seperator . '1' . $multi_value_seperator . '1' . $multi_value_seperator . '1' . $multi_value_seperator . '1' . $multi_value_seperator . '1'					
				)		
			),
			'lines' => array(
				'line_types' => array(
					'line_title' => esc_html__('Red', 'waymark-plugin') . $multi_value_seperator . esc_html__('Green', 'waymark-plugin') . $multi_value_seperator . esc_html__('Blue', 'waymark-plugin'),
					'line_colour' => '#d84848' . $multi_value_seperator . '#3cbc47' . $multi_value_seperator . '#487bd9',
					'line_weight' => '3' . $multi_value_seperator . '3' . $multi_value_seperator . '3',
					'line_display' => '1' . $multi_value_seperator . '1' . $multi_value_seperator . '1',
				)
			),
			'shapes' => array(
				'shape_types' => array(
					'shape_title' => esc_html__('Red', 'waymark-plugin') . $multi_value_seperator . esc_html__('Green', 'waymark-plugin') . $multi_value_seperator . esc_html__('Blue', 'waymark-plugin'),
					'shape_colour' => '#d84848' . $multi_value_seperator . '#3cbc47' . $multi_value_seperator . '#487bd9',
					'fill_opacity' => '0.5' . $multi_value_seperator . '0.5' . $multi_value_seperator . '0.5',
					'shape_display' => '1' . $multi_value_seperator . '1' . $multi_value_seperator . '1',					
				)
			)
		);
		
		//Keep a copy of the original values
		self::$default = self::$data;
	
		//Read config options from DB
		$settings_data = get_option('Waymark_Settings');

		//Waymark_Helper::debug($settings_data);
		
		//Add settings to config data
		if(is_array($settings_data)) {
			foreach($settings_data as $tab_key => $tab_data) {
				foreach($tab_data as $section_key => $section_data) {
					foreach($section_data as $parameter_key => $parameter_value) {
						self::$data[$tab_key][$section_key][$parameter_key] = $parameter_value;
					}
				}
			}
			
			//Fix multi-settings

			//Lines exist
			if(is_array(self::$data['lines']['line_types']['line_title'])) {
				$line_count = sizeof(self::$data['lines']['line_types']['line_title']);
				
				foreach(self::$data['lines']['line_types'] as &$value) {
					if(! is_array($value) || sizeof($value) != $line_count) {
						$default = $value[0];
					
						$value = array();
						for($i=0; $i<$line_count; $i++) {
							$value[$i] = $default;
						}	
					}
				}				
			}
		}
	}	

	public static function set_item($key = null, $value) {
		if(array_key_exists($key, self::$data)) {
			self::$data[$key] = $value;
		}
	}

	public static function get_item($key, $key_2 = null) {	
		if(array_key_exists($key, self::$data)) {
			if(is_array(self::$data[$key]) && array_key_exists($key_2, self::$data[$key])) {
				return self::$data[$key][$key_2];
			} else {
				return self::$data[$key];
			}			
		} else {
			return null;
		}			
	}

	public static function get_data() {	
		return self::$data;
	}	

	public static function get_default($tab, $group, $key) {	
		if(array_key_exists($tab, self::$default) && array_key_exists($group, self::$default[$tab]) && array_key_exists($key, self::$default[$tab][$group])) {
			return self::$default[$tab][$group][$key];
		} else {
			return false;
		}	
	}

	public static function get_setting($tab, $group, $key) {	
		if(array_key_exists($tab, self::$data) && array_key_exists($group, self::$data[$tab]) && array_key_exists($key, self::$data[$tab][$group])) {
			return self::$data[$tab][$group][$key];
		} else {
			return false;
		}	
	}

	//Helpers
	public static function get_name($short = false, $really_short = false) {
		if(! $short) {
			return self::get_item('plugin_name');				
		} else {
			if(! $really_short) {
				return self::get_item('plugin_name_short');															
			} else {
				return strip_tags(self::get_item('plugin_name_short'));															
			}
		}		
	}	

	public static function get_version() {
		return self::get_item('plugin_version');	
	}	
	
	public static function get_settings_parameters($tab_id = null, $group_id = null) {
		$settings = array();
		
		//If only getting a secific section
		if(array_key_exists($tab_id, self::$parameters) && array_key_exists($group_id, self::$parameters[$tab_id])) {
			$group_data = self::$parameters[$tab_id][$group_id];
			//Iterate over each parameter
			foreach($group_data as $parameter_data) {
				if(array_key_exists('setting', $parameter_data) && $parameter_data['setting']) {
					$settings[] = $parameter_data;
				}
			}								
		}
	
		return $settings;		
	}

	public static function get_map_config() {
		$map_config = array();
		
		//Basemaps
		$tile_layers = Waymark_Config::get_item('tiles', 'layers');
		$tile_layers = Waymark_Helper::convert_values_to_single_value($tile_layers);	
		//Process for output
		foreach($tile_layers as &$tl) {
			$tl = htmlspecialchars_decode($tl);		
		}
		$tile_layers = Waymark_Helper::convert_single_value_to_array($tile_layers);
		$map_config['tile_layers'] = $tile_layers;
		
		//Object Types	
		$map_config['marker_types'] = Waymark_Helper::get_object_types('marker');
		$map_config['line_types'] =  Waymark_Helper::get_object_types('line');
		$map_config['shape_types'] = Waymark_Helper::get_object_types('shape');

		//Editor Options
		$map_config['map_options'] = array(
			'show_type_labels' => Waymark_Config::get_setting('misc', 'map_options', 'show_type_labels'),
			'button_position' => 'topleft'			
		);

		//Editor Options
		$map_config['editor_options'] = array(
			'confirm_delete' => Waymark_Config::get_setting('misc', 'editor_options', 'confirm_delete')
		);

		return $map_config;
	}
	
	public static function is_custom_type($type = null) {
		//Get from post
		if($type == null) {
			global $post;
			
			//If it exists			
			if(isset($post->post_type)) {
				$type = $post->post_type;			
			}
		}
		
		return in_array($type, self::$data['custom_types']) || in_array('waymark_' . $type, self::$data['custom_types']);
	}
}

Waymark_Config::init();
