<?php

class Waymark_Settings {
	private $settings_id = 'Waymark_Settings';
	private $page_slug = 'waymark-settings';			
	private $default_tab = 'tiles';
	private $current_settings = array();

	protected $tabs = array();	
	
	function __construct() {
    add_action('admin_notices', array($this, 'admin_notices'));
		
		//Execute action?
		if(sizeof($_POST)) {
			//Clear cache
			if(isset($_POST['Waymark_Settings']['advanced']['performance']['clear_cache'])) {	
				$this->execute_action('clear_cache');			
			}
		}
		
		//Get current settings from DB
		$current_settings = get_option('Waymark_Settings');
		if(is_array($current_settings)) {
			$this->current_settings = $current_settings;
		}
	
		// === Tabs ===

		//Tiles
		$this->tabs['tiles'] = array(
			'name' => esc_html__('Basemaps', 'waymark-plugin'),
			'description' => '',
			'sections' => array(
				'layers' => array(
					'repeatable' => true,
					'title' => esc_html__('Basemaps', 'waymark-plugin'),
					'description' => sprintf(__('<span class="waymark-lead">Waymark uses the excellent <a href="%s">OpenStreetMap</a> as it’s default Basemap and allows integration for services that support <a href="%s">tiled web maps</a></span>.<br /><br /><a href="%s">Thunderforest</a> and <a href="%s">Mapbox</a> are examples of providers that offer easy access to beautiful Basemaps (including satellite imagery). They require registration, but have a free usage tier.<br /><br /><small><b>Pro Tip!</b> If you have more than one Basemap, you can switch between them when viewing the Map. The first listed will be used as the default, unless specified in the shortcode like this: %s. Drag to re-order, remove all to restore defaults.</small>', 'waymark-plugin'), 'https://www.openstreetmap.org/fixthemap', 'https://en.wikipedia.org/wiki/Tiled_web_map', 'https://www.thunderforest.com/', 'https://www.mapbox.com/maps/', '[' . Waymark_Config::get_item('shortcode') . ' map_id=&quot;1234&quot; basemap=&quot;Basemap Name&quot;]'),
					'help' => array(
						'url' => esc_attr(Waymark_Helper::site_url('docs/basemaps')),
						'text' => esc_attr__('View Docs &raquo;')
					),
					'fields' => array(
						'layer_name' => array(
							'name' => 'layer_name',
							'id' => 'layer_name',
							'type' => 'text',
							'class' => '',				
							'title' => '<u>' . esc_html__('Basemap', 'waymark-plugin') . '</u> ' . esc_html__('Name', 'waymark-plugin'),
							'default' => Waymark_Config::get_setting('tiles', 'layers', 'layer_name'),
							'tip' => sprintf(esc_attr__('The Layer Name will appear in a dropdown list shown by the Map when multiple Basemaps have been entered. You can change the default basemap in the shortcode: %s', 'waymark-plugin'), '[' . Waymark_Config::get_item('shortcode') . ' map_id=&quot;1234&quot; basemap=&quot;Basemap Name&quot;]'),
							'input_processing' => array(
								'(! empty($param_value)) ? $param_value : "' . esc_html__('Basemap', 'waymark-plugin') . ' ' . substr(md5(rand(0,999999)), 0, 5) . '";'	//Fallback
							)									
						),
						'layer_url' => array(
							'name' => 'layer_url',
							'id' => 'layer_url',
							'type' => 'text',
							'class' => '',				
							'title' => '<span class="waymark-invisible">' . esc_html__('Basemap', 'waymark-plugin') . '</span> URL',
							'default' => Waymark_Config::get_setting('tiles', 'layers', 'layer_url'),
							'tip' => sprintf(esc_attr__('Many mapping services support the Slippy Map format. Waymark requires URLs that contain {z} (zoom level) and {x}/{y} (tile coordinates). For example the OpenCycleMap URL is %s.', 'waymark-plugin'), 'https://tile.thunderforest.com/cycle/{z}/{x}/{y}@2x.png?apikey=[your_api_key]'),
							'tip_link' => 'https://www.thunderforest.com/docs/map-tiles-api/'								
						),
						'layer_attribution' => array(
							'name' => 'layer_attribution',
							'id' => 'layer_attribution',
							'type' => 'text',
							'class' => '',				
							'title' => '<span class="waymark-invisible">' . esc_html__('Basemap', 'waymark-plugin') . '</span> Attribution',
							'default' => Waymark_Config::get_setting('tiles', 'layers', 'layer_attribution'),
							'tip' => esc_attr__('Mapping services often have the requirement that attribution is displayed by the map. Text and HTML links are supported.', 'waymark-plugin'),
							'tip_link' => 'https://www.thunderforest.com/terms/#attribution'
						)
					)																	
				)
			)
		);
			
		//Markers
		$this->tabs['markers'] = array(
			'name' => esc_html__('Markers', 'waymark-plugin'),
			'description' => '',
			'sections' => array(
				'marker_types' => array(
					'repeatable' => true,
					'title' => esc_html__('Marker', 'waymark-plugin') . ' ' . esc_html__('Types', 'waymark-plugin'),
					'description' => '<span class="waymark-lead">' . __('Customise how Markers are displayed on the Map. Set these styles once, then select the appropriate Type when you add to the Map.', 'waymark-plugin') . '</span>',
					'footer' => '<small>' . __('<b>Pro Tip!</b> The first listed will be used as the default. Drag to re-order, remove all to restore defaults.', 'waymark-plugin') . '</small>',
					'help' => array(
						'url' => esc_attr(Waymark_Helper::site_url('docs/types')),
						'text' => esc_attr__('View Docs &raquo;')
					),
					'fields' => array(
						'marker_title' => array(
							'name' => 'marker_title',
							'id' => 'marker_title',
							'type' => 'text',
							'class' => 'waymark-uneditable',				
							'title' => '<u>' . esc_html__('Marker', 'waymark-plugin') . '</u> ' . esc_html__('Label', 'waymark-plugin'),
							'default' => Waymark_Config::get_setting('markers', 'marker_types', 'marker_title'),
							'tip' => esc_attr__('What kind of Marker is this? E.g. "Photo", "Grocery Store", "Warning!". The Marker Label is displayed in the Tooltip (when hovering over the Marker) and in the Marker Info Window. Once saved, Marker labels can not be edited.', 'waymark-plugin'),
							'input_processing' => array(
								'(! empty($param_value)) ? $param_value : "' . esc_html__('Marker', 'waymark-plugin') . ' ' . substr(md5(rand(0,999999)), 0, 5) . '";'	//Fallback
							)									
						),
						'marker_colour' => array(
							'name' => 'marker_colour',
							'id' => 'marker_colour',
							'type' => 'text',
							'class' => 'waymark-short-input',				
							'title' => '<span class="waymark-invisible">' . esc_html__('Marker', 'waymark-plugin') . '</span> ' . esc_html__('Colour', 'waymark-plugin'),
							'default' => Waymark_Config::get_setting('markers', 'marker_types', 'marker_colour'),
							'tip' => esc_attr__('The Marker background colour. Thanks awesome-markers!', 'waymark-plugin'),
							'tip_link' => 'https://github.com/lvoogdt/Leaflet.awesome-markers',
							'input_processing' => array(
								'(! empty($param_value)) ? $param_value : "white";'	//Fallback
							)								
						),							
						'marker_icon' => array(
							'name' => 'marker_icon',
							'id' => 'marker_icon',
							'type' => 'text',
							'class' => 'waymark-short-input',				
							'title' => '<span class="waymark-invisible">' . esc_html__('Marker', 'waymark-plugin') . '</span> ' . esc_html__('Icon Name', 'waymark-plugin'),
							'default' => Waymark_Config::get_setting('markers', 'marker_types', 'marker_icon'),
							'tip' => esc_attr__('The desired icon name, e.g. "camera". Full list provided by Ionicons. Thanks Ionicons!', 'waymark-plugin'),
							'tip_link' => 'https://ionicons.com/v2/',
							'input_processing' => array(
								'(! empty($param_value)) ? $param_value : "ion-help";'	//Fallback
							)	
						),							
						'icon_colour' => array(
							'name' => 'icon_colour',
							'id' => 'icon_colour',
							'type' => 'text',
							'class' => 'waymark-short-input waymark-colour-picker',				
							'title' => '<span class="waymark-invisible">' . esc_html__('Marker', 'waymark-plugin') . '</span> ' . esc_html__('Icon Colour', 'waymark-plugin'),
							'default' => Waymark_Config::get_setting('markers', 'marker_types', 'icon_colour'),
							'tip' => esc_attr__('The colour of the icon. Click "Select Colour" to select.', 'waymark-plugin'),
							'input_processing' => array(
								'(! empty($param_value)) ? $param_value : "#81d742";'	//Fallback
							)							
						),
						'marker_display' => array(
							'name' => 'marker_display',
							'id' => 'marker_display',
							'type' => 'boolean',
							'title' => '<span class="waymark-invisible">' . esc_html__('Marker', 'waymark-plugin') . '</span> ' . esc_html__('Show Initially', 'waymark-plugin'),
							'default' => Waymark_Config::get_setting('markers', 'marker_types', 'marker_display'),
							'tip' => esc_attr__('When using the Overlay Filter you can choose to show/hide certain Types when the Map initially loads.', 'waymark-plugin'),
							'input_processing' => array(
								'(is_numeric($param_value)) ? $param_value : 1;'	//Fallback
							)					
						)
					)																	
				)
			)
		);
		
		//Lines
		$this->tabs['lines'] = array(
			'name' => esc_html__('Lines', 'waymark-plugin'),
			'description' => '',
			'sections' => array(
				'line_types' => array(
					'repeatable' => true,
					'title' => esc_html__('Line', 'waymark-plugin') . ' ' . esc_html__('Types', 'waymark-plugin'),
					'description' => '<span class="waymark-lead">' . __('Customise how Lines are displayed on the Map. Set these styles once, then select the appropriate Type when you add to the Map.', 'waymark-plugin') . '</span>',
					'help' => array(
						'url' => esc_attr(Waymark_Helper::site_url('docs/types')),
						'text' => esc_attr__('View Docs &raquo;')
					),
					'footer' => '<small>' . __('<b>Pro Tip!</b> The first listed will be used as the default. Drag to re-order, remove all to restore defaults.', 'waymark-plugin') . '</small>',
					'fields' => array(
						'line_title' => array(
							'name' => 'line_title',
							'id' => 'line_title',
							'type' => 'text',
							'class' => 'waymark-uneditable',				
							'title' => '<u>' . esc_html__('Line', 'waymark-plugin') . '</u> ' . esc_html__('Label', 'waymark-plugin'),
							'default' => Waymark_Config::get_setting('lines', 'line_types', 'line_title'),
							'tip' => esc_attr__('What kind of Line is this? E.g. "Easy", "Walking Only", "Dark Red". The Line Label is displayed in the Tooltip (when hovering over the Line) and in the Line Info Window. Once saved, Line labels can not be edited.', 'waymark-plugin'),
							'input_processing' => array(
								'(! empty($param_value)) ? $param_value : "' . esc_html__('Line', 'waymark-plugin') . ' ' . substr(md5(rand(0,999999)), 0, 5) . '";'	//Fallback
							)										
						),						
						'line_colour' => array(
							'name' => 'line_colour',
							'id' => 'line_colour',
							'type' => 'text',
							'class' => 'waymark-short-input waymark-colour-picker',				
							'title' => '<span class="waymark-invisible">' . esc_html__('Line', 'waymark-plugin') . '</span> ' . esc_html__('Colour', 'waymark-plugin'),
							'default' => Waymark_Config::get_setting('lines', 'line_types', 'line_colour'),
							'tip' => esc_attr__('The colour of the Line. Click "Select Colour" to select.', 'waymark-plugin'),
							'input_processing' => array(
								'(! empty($param_value)) ? $param_value : "#81d742";'	//Fallback
							)							
						),						
						'line_weight' => array(
							'name' => 'line_weight',
							'id' => 'line_weight',
							'type' => 'text',
							'class' => 'waymark-short-input',				
							'title' => '<span class="waymark-invisible">' . esc_html__('Line', 'waymark-plugin') . '</span> ' . esc_html__('Weight', 'waymark-plugin'),
							'default' => Waymark_Config::get_setting('lines', 'line_types', 'line_weight'),
							'tip' => esc_attr__('The width of the Line, in pixels.', 'waymark-plugin'),
							'input_processing' => array(
								'(is_numeric($param_value)) ? $param_value : 3;'	//Fallback
							)					
						),
						'line_display' => array(
							'name' => 'line_display',
							'id' => 'line_display',
							'type' => 'boolean',
							'title' => '<span class="waymark-invisible">' . esc_html__('Line', 'waymark-plugin') . '</span> ' . esc_html__('Show Initially', 'waymark-plugin'),
							'default' => Waymark_Config::get_setting('lines', 'line_types', 'line_display'),
							'tip' => esc_attr__('When using the Overlay Filter you can choose to show/hide certain Types when the Map initially loads.', 'waymark-plugin'),
							'input_processing' => array(
								'(is_numeric($param_value)) ? $param_value : 1;'	//Fallback
							)					
						)
					)																	
				)
			)
		);
		
		//Shapes
		$this->tabs['shapes'] = array(
			'name' => esc_html__('Shapes', 'waymark-plugin'),
			'description' => '',
			'sections' => array(
				'shape_types' => array(
					'repeatable' => true,
					'title' => esc_html__('Shape', 'waymark-plugin') . ' ' . esc_html__('Types', 'waymark-plugin'),
					'description' => '<span class="waymark-lead">' . __('Customise how Shapes are displayed on the Map. Set these styles once, then select the appropriate Type when you add to the Map.', 'waymark-plugin') . '</span>',
					'help' => array(
						'url' => esc_attr(Waymark_Helper::site_url('docs/types')),
						'text' => esc_attr__('View Docs &raquo;')
					),
					'footer' => '<small>' . __('<b>Pro Tip!</b> The first listed will be used as the default. Drag to re-order, remove all to restore defaults.', 'waymark-plugin') . '</small>',
					'fields' => array(
						'shape_title' => array(
							'name' => 'shape_title',
							'id' => 'shape_title',
							'type' => 'text',
							'class' => 'waymark-uneditable',				
							'title' => '<u>' . esc_html__('Shape', 'waymark-plugin') . '</u> ' . esc_html__('Label', 'waymark-plugin'),
							'default' => Waymark_Config::get_setting('shapes', 'shape_types', 'shape_title'),
							'tip' => esc_attr__('What kind of Shape is this? E.g. "Park", "Danger!", "Light Blue". The Shape Label is displayed in the Tooltip (when hovering over the Shape) and in the Shape Info Window. Once saved, Shape labels can not be edited.', 'waymark-plugin'),
							'input_processing' => array(
								'(! empty($param_value)) ? $param_value : "' . esc_html__('Shape', 'waymark-plugin') . ' ' . substr(md5(rand(0,999999)), 0, 5) . '";'	//Fallback
							)							
						),						
						'shape_colour' => array(
							'name' => 'shape_colour',
							'id' => 'shape_colour',
							'type' => 'text',
							'class' => 'waymark-short-input waymark-colour-picker',				
							'title' => '<span class="waymark-invisible">' . esc_html__('Shape', 'waymark-plugin') . '</span> ' . esc_html__('Colour', 'waymark-plugin'),
							'default' => Waymark_Config::get_setting('shapes', 'shape_types', 'shape_colour'),
							'tip' => esc_attr__('The colour of the Shape. Click "Select Colour" to select.', 'waymark-plugin'),
							'input_processing' => array(
								'(! empty($param_value)) ? $param_value : "#81d742";'	//Fallback
							)							
						),						
						'fill_opacity' => array(
							'name' => 'fill_opacity',
							'id' => 'fill_opacity',
							'type' => 'text',
							'class' => 'waymark-short-input',				
							'title' => '<span class="waymark-invisible">' . esc_html__('Shape', 'waymark-plugin') . '</span> ' . esc_html__('Fill Opacity', 'waymark-plugin'),
							'default' => Waymark_Config::get_setting('shapes', 'shape_types', 'fill_opacity'),
							'tip' => esc_attr__('The opacity of the inside of the shape, between 0.0 and 1.0 (e.g. "0.5").', 'waymark-plugin'),
							'input_processing' => array(
								'(is_numeric($param_value) && $param_value > 0 && $param_value <= 1) ? $param_value : 0.5;' //Fallback
							)										
						),
						'shape_display' => array(
							'name' => 'shape_display',
							'id' => 'shape_display',
							'type' => 'boolean',
							'title' => '<span class="waymark-invisible">' . esc_html__('Shape', 'waymark-plugin') . '</span> ' . esc_html__('Show Initially', 'waymark-plugin'),
							'default' => Waymark_Config::get_setting('shapes', 'shape_types', 'shape_display'),
							'tip' => esc_attr__('When using the Overlay Filter you can choose to show/hide certain Types when the Map initially loads.', 'waymark-plugin'),
							'input_processing' => array(
								'(is_numeric($param_value)) ? $param_value : 1;'	//Fallback
							)					
						)
					)																	
				)
			)
		);	
			
		//Meta
		$this->tabs['meta'] = array(
			'name' => esc_html__('Meta', 'waymark-plugin'),
			'description' => '',
			'sections' => array(
				'inputs' => array(
					'repeatable' => true,
					'title' => esc_html__('Meta', 'waymark-plugin'),
					'description' => '<span class="waymark-lead">' . __(sprintf('Create additional input fields that appear underneath the Map Editor. Any Meta that has been input is displayed on the <a href="%s">Map Details</a> page.', 'https://www.joesway.ca/map/route-map/'), 'waymark-plugin') . '</span>',
					'help' => array(
						'url' => esc_attr(Waymark_Helper::site_url('docs/meta')),
						'text' => esc_attr__('View Docs &raquo;')
					),
					'footer' => '<small>' . __('<b>Pro Tip!</b> The first listed will be used as the default. Drag to re-order, remove all to restore defaults.', 'waymark-plugin') . '</small>',
					'fields' => array(
						'meta_title' => array(
							'name' => 'meta_title',
							'id' => 'meta_title',
							'type' => 'text',
							'class' => '',				
							'title' => '<u>' . esc_html__('Meta', 'waymark-plugin') . '</u> ' . esc_html__('Title', 'waymark-plugin'),
							'default' => Waymark_Config::get_setting('meta', 'inputs', 'meta_title'),
							'tip' => esc_attr__('The title appears next to the input field.', 'waymark-plugin'),
							'class' => Waymark_Config::get_item('meta', 'inputs') ? 'waymark-uneditable' : '',
							'input_processing' => array(
								'(! empty($param_value)) ? $param_value : "' . esc_html__('Meta', 'waymark-plugin') . ' ' . substr(md5(rand(0,999999)), 0, 5) . '";'	//Fallback
							)								
						),						
						'meta_default' => array(
							'name' => 'meta_default',
							'id' => 'meta_default',
							'type' => 'text',
							'class' => '',				
							'title' => '<span class="waymark-invisible">' . esc_html__('Meta', 'waymark-plugin') . '</span> ' . esc_html__('Default', 'waymark-plugin'),
							'default' => Waymark_Config::get_setting('meta', 'inputs', 'meta_default'),
							'tip' => esc_attr__('The default value for the input field. For Select and Multi-Select enter the option/comma-separated options to be selected by default.', 'waymark-plugin')
						),
						'meta_tip' => array(
							'name' => 'meta_tip',
							'id' => 'meta_tip',
							'type' => 'text',
							'class' => '',				
							'title' => '<span class="waymark-invisible">' . esc_html__('Meta', 'waymark-plugin') . '</span> ' . esc_html__('Tip', 'waymark-plugin'),
							'default' => Waymark_Config::get_setting('meta', 'inputs', 'meta_tip'),
							'tip' => esc_attr__('A tip provides additional information about an input field... just like this!', 'waymark-plugin')
						),
						'meta_type' => array(
							'name' => 'meta_type',
							'id' => 'meta_type',
							'type' => 'select',
							'class' => '',				
							'title' => '<span class="waymark-invisible">' . esc_html__('Meta', 'waymark-plugin') . '</span> ' . esc_html__('Type', 'waymark-plugin'),
							'default' => Waymark_Config::get_setting('meta', 'inputs', 'meta_type'),
							'tip' => esc_attr__('The type of input field to use.', 'waymark-plugin'),
							'options' => array(
								'text' => esc_html__('Text', 'waymark-plugin'),
								'textarea' => esc_html__('Textarea', 'waymark-plugin'),
								'textarea_rich' => esc_html__('Rich Text', 'waymark-plugin'),								
								'select' => esc_html__('Select', 'waymark-plugin'),
								'select_multi' => esc_html__('Multi-Select', 'waymark-plugin')																							
							)
						),
						'meta_options' => array(
							'name' => 'meta_options',
							'id' => 'meta_options',
							'type' => 'text',
							'class' => '',				
							'title' => '<span class="waymark-invisible">' . esc_html__('Meta', 'waymark-plugin') . '</span> ' . esc_html__('Options', 'waymark-plugin'),
							'default' => Waymark_Config::get_setting('meta', 'inputs', 'meta_options'),
							'tip' => esc_attr__('A comma-separated list of options for the input.', 'waymark-plugin')
						)												
					)																										
				)
			)			
		);	

		//Prepare Basemap values for editor option
		$tile_layers = Waymark_Config::get_item('tiles', 'layers');
		$basemap_options = array();
		$tile_layers = Waymark_Helper::convert_values_to_single_value($tile_layers);
		$tile_layers = Waymark_Helper::convert_single_value_to_array($tile_layers);
		
		//Each layer
		foreach($tile_layers as $layer) {
			//If name exists
			if(array_key_exists('layer_name', $layer)) {
				//Add as option
				$basemap_options[$layer['layer_name']] = $layer['layer_name'];
			}		
		}
		
		//Waymark_Helper::debug($basemap_options);
		
		//Misc
		$this->tabs['misc'] = array(
			'name' => esc_html__('Misc.', 'waymark-plugin'),
			'description' => '',
			'sections' => array(
				'map_options' => array(
					'title' => esc_html__('Map Options', 'waymark-plugin'),
					'description' => esc_html__('Use these options to change how Maps are displayed.', 'waymark-plugin'),
					'fields' => array(	
						'map_height' => array(
							'name' => 'map_height',
							'id' => 'map_height',
							'type' => 'text',
							'class' => 'waymark-short-input',				
							'title' => esc_html__('Map Height', 'waymark-plugin'),
							'default' => Waymark_Config::get_setting('misc', 'map_options', 'map_height'),
							'tip' => sprintf(esc_attr__('Specify the desired height of the Map (in pixels). The Map will automatically adjust it’s width to fill the space available to it. Pro Tip! This will affect all Maps, but you can change the height of an individual Map through the shortcode: %s', 'waymark-plugin'), '[' . Waymark_Config::get_item('shortcode') . ' map_id=&quot;1234&quot; map_height=&quot;' . Waymark_Config::get_setting('misc', 'map_options', 'map_height') . '&quot;]'),
							'input_processing' => array(
								'preg_replace("/[^0-9]/", "", $param_value);'
							),
							'output_processing' => array(
								sprintf('(! empty($param_value)) ? $param_value : %d;', Waymark_Config::get_default('misc', 'map_options', 'map_height'))
							)							
						),																
						'show_type_labels' => array(
							'name' => 'show_type_labels',
							'id' => 'show_type_labels',
							'type' => 'boolean',
							'title' => esc_html__('Type Labels', 'waymark-plugin'),
							'default' => Waymark_Config::get_setting('misc', 'map_options', 'show_type_labels'),
							'tip' => esc_attr__('Whether to display the Marker/Line/Shape Type Label in the tooltip and Info Window.', 'waymark-plugin'),
							'options' => array(
								'1' => esc_html__('Show', 'waymark-plugin'),
								'0' => esc_html__('Hide', 'waymark-plugin')								
							)
						),
						'map_default_latlng' => array(
							'name' => 'map_default_latlng',
							'id' => 'map_default_latlng',
							'type' => 'text',
							'class' => '',				
							'title' => esc_html__('Default Centre', 'waymark-plugin'),
							'default' => Waymark_Config::get_setting('misc', 'map_options', 'map_default_latlng'),
							'tip' => esc_attr__('Waymark centres the Map automatically when displaying data. These coordinates (Latitude,Longitude) will be used when there is no data available.', 'waymark-plugin'),
							'input_processing' => array(
								'preg_replace("/[^0-9.,-]+/", "", $param_value);'
							),
							'output_processing' => array(
								sprintf('(! empty($param_value)) ? $param_value : "%s";', Waymark_Config::get_default('misc', 'map_options', 'map_default_latlng'))
							)								
						),
						'map_default_zoom' => array(
							'name' => 'map_default_zoom',
							'id' => 'map_default_zoom',
							'type' => 'text',
							'class' => 'waymark-short-input',				
							'title' => esc_html__('Default Zoom', 'waymark-plugin'),
							'default' => Waymark_Config::get_setting('misc', 'map_options', 'map_default_zoom'),
							'tip' => esc_attr__('Waymark zooms the Map automatically when displaying data. This zoom level (0-18) will be used when there is no data available.', 'waymark-plugin'),
							'input_processing' => array(
								'preg_replace("/[^0-9]/", "", $param_value);'
							),
							'output_processing' => array(
								sprintf('(! empty($param_value)) ? $param_value : "%d";', Waymark_Config::get_default('misc', 'map_options', 'map_default_zoom'))
							)								
						),
						'show_gallery' => array(
							'name' => 'show_gallery',
							'id' => 'show_gallery',
							'type' => 'boolean',
							'title' => esc_html__('Image Gallery', 'waymark-plugin'),
							'default' => Waymark_Config::get_setting('misc', 'map_options', 'show_gallery'),
							'tip' => sprintf(esc_attr__('Whether to display an image gallery for Markers that have images associated with them. Pro Tip! This will affect all Maps, but you can choose to show/hide the gallery of an individual Map through the shortcode: %s', 'waymark-plugin'), '[' . Waymark_Config::get_item('shortcode') . ' show_gallery=&quot;1&quot;]'),
							'options' => array(
								'1' => esc_html__('Show', 'waymark-plugin'),
								'0' => esc_html__('Hide', 'waymark-plugin')										
							)
						),
						'show_filter' => array(
							'name' => 'show_filter',
							'id' => 'show_filter',
							'type' => 'boolean',
							'title' => esc_html__('Overlay Filter', 'waymark-plugin'),
							'default' => Waymark_Config::get_setting('misc', 'map_options', 'show_filter'),
							'tip' => sprintf(esc_attr__('Allow the user to filter which Markers, Lines and Shapes are currently visible on the Map. Pro Tip! This will affect all Maps, but you can choose to show/hide the filter for individual Maps through the shortcode: %s', 'waymark-plugin'), '[' . Waymark_Config::get_item('shortcode') . ' show_filter=&quot;1&quot;]'),
							'options' => array(
								'1' => esc_html__('Show', 'waymark-plugin'),
								'0' => esc_html__('Hide', 'waymark-plugin')									
							)
						),
						'allow_export' => array(
							'name' => 'allow_export',
							'id' => 'allow_export',
							'type' => 'boolean',
							'title' => esc_html__('Public Export', 'waymark-plugin'),
							'default' => Waymark_Config::get_setting('misc', 'map_options', 'allow_export'),
							'tip' => sprintf(esc_attr__('Enable visitors to download the Overlays (Markers, Lines and Shapes) currently displayed on the Map Details page. Can be used in conjunction with the Overlay Filter to select which Overlays to download. GeoJSON, GPX and KML formats supported.', 'waymark-plugin'))
						)													
					)																
				),

				'collection_options' => array(
					'title' => esc_html__('Collection Options', 'waymark-plugin'),
					'description' => esc_html__('How Collections are displayed.', 'waymark-plugin'),
					'fields' => array(															
						'link_to_maps' => array(
							'name' => 'link_to_maps',
							'id' => 'link_to_maps',
							'type' => 'boolean',
							'title' => esc_html__('Link to Maps', 'waymark-plugin'),
							'default' => Waymark_Config::get_setting('misc', 'collection_options', 'link_to_maps'),
							'tip' => esc_attr__('Whether to display a link to the individual Map Details page when clicking on a Marker/Line/Shape displayed by the Collection.', 'waymark-plugin')
						),
						'link_from_maps' => array(
							'name' => 'link_from_maps',
							'id' => 'link_from_maps',
							'type' => 'boolean',
							'title' => esc_html__('Link from Maps', 'waymark-plugin'),
							'default' => Waymark_Config::get_setting('misc', 'collection_options', 'link_from_maps'),
							'tip' => esc_attr__('Whether to display a link to the Collection(s) that a Map belongs to on the Map Details page.', 'waymark-plugin')
						)											
					)											
				),

				'shortcode_options' => array(
					'title' => esc_html__('Shortcode Options', 'waymark-plugin'),
					'description' => esc_html__('How Maps are embedded into your content using the shortcode.', 'waymark-plugin'),
					'fields' => array(
						'shortcode_header' => array(
							'name' => 'shortcode_header',
							'id' => 'shortcode_header',
							'type' => 'select',
							'title' => esc_html__('Shortcode Header', 'waymark-plugin'),
							'default' => Waymark_Config::get_setting('misc', 'shortcode_options', 'shortcode_header'),
							'tip' => sprintf(esc_attr__('The shortcode header displays the title and link to the Map or Collection. Pro Tip! This will affect all shortcodes, but you can override the setting through the shortcode: %s (the value must be either 0 or 1).', 'waymark-plugin'), '[' . Waymark_Config::get_item('shortcode') . ' map_id=&quot;1234&quot; shortcode_header=&quot;' . Waymark_Config::get_setting('misc', 'shortcode_options', 'shortcode_header') . '&quot;]'),
							'options' => array(
								'1' => esc_html__('Show', 'waymark-plugin'),
								'0' => esc_html__('Hide', 'waymark-plugin')
							)
						),
						'header_override' => array(
							'name' => 'header_override',
							'id' => 'header_override',
							'type' => 'select',
							'title' => esc_html__('Header for Admin', 'waymark-plugin'),
							'default' => Waymark_Config::get_setting('misc', 'shortcode_options', 'header_override'),
							'tip' => esc_attr__('Use this Setting to always show the Shortcode Header when signed in as admin, useful for quickly navigating to embeded Maps.'),
							'options' => array(
								'0' => esc_html__('Use Setting', 'waymark-plugin'),
								'1' => esc_html__('Always Show', 'waymark-plugin')						
							)
						)												
					)											
				),				

				'elevation_options' => array(
					'title' => esc_html__('Elevation Options', 'waymark-plugin'),
					'description' => esc_html__('Lines with elevation data.', 'waymark-plugin'),
					'fields' => array(
						'show_elevation' => array(
							'name' => 'show_elevation',
							'id' => 'show_elevation',
							'type' => 'select',
							'title' => esc_html__('Elevation Profile', 'waymark-plugin'),
							'default' => Waymark_Config::get_setting('misc', 'elevation_options', 'show_elevation'),
							'tip' => sprintf(esc_attr__('Display an interactive elevation profile graph below the Map for Lines that have elevation data. Pro Tip! You can choose to show/hide the elevation graph of an individual Map through the shortcode: %s', 'waymark-plugin'), '[' . Waymark_Config::get_item('shortcode') . ' map_id=&quot;1234&quot; show_elevation=&quot;1&quot;]'),
							'options' => array(
								'2' => esc_html__('Show on Map Details', 'waymark-plugin'),
								'1' => esc_html__('Show everywhere', 'waymark-plugin'),
								'0' => esc_html__('Hide everywhere', 'waymark-plugin')
							)							
						),
						'elevation_units' => array(
							'name' => 'elevation_units',
							'id' => 'elevation_units',
							'type' => 'select',
							'title' => esc_html__('Elevation Units', 'waymark-plugin'),
							'default' => Waymark_Config::get_setting('misc', 'elevation_options', 'elevation_units'),
							'tip' => sprintf(esc_attr__('Display elevation data in metric (m/km) or imperial (ft/mi) units.', 'waymark-plugin')),
							'options' => array(
								'metric' => esc_html__('Metric (m/km)', 'waymark-plugin'),
								'imperial' => esc_html__('Imperial (ft/mi)', 'waymark-plugin')
							)							
						),
						'elevation_colour' => array(
							'name' => 'elevation_colour',
							'id' => 'elevation_colour',
							'type' => 'text',
							'class' => 'waymark-short-input waymark-colour-picker',				
							'title' => esc_html__('Elevation Colour', 'waymark-plugin'),
							'default' => Waymark_Config::get_setting('misc', 'elevation_options', 'elevation_colour'),
							'tip' => sprintf(esc_attr__('The colour of the elevation graph and associated Line.', 'waymark-plugin')),
							'input_processing' => array(
								'(! empty($param_value)) ? $param_value : "#b42714";'	//Fallback
							)									
						),
						'elevation_initial' => array(
							'name' => 'elevation_initial',
							'id' => 'elevation_initial',
							'type' => 'boolean',
							'title' => esc_html__('Show Initially?', 'waymark-plugin'),
							'default' => Waymark_Config::get_setting('misc', 'elevation_options', 'elevation_initial'),
							'tip' => sprintf(esc_attr__('Whether to show the elevation profile when the Map loads. If set to No, the user must click on a Line in order to display the elevation data. If there are multiple Lines with elevation data, the one added to the editor first will be the one shown initially.', 'waymark-plugin'))
						)														
					)																					
				),

				'editor_options' => array(
					'title' => esc_html__('Editor Options', 'waymark-plugin'),
					'description' => esc_html__('Customising the Map Editor.', 'waymark-plugin'),
					'fields' => array(
						'confirm_delete' => array(
							'name' => 'confirm_delete',
							'id' => 'confirm_delete',
							'type' => 'boolean',
							'title' => esc_html__('Confirm Delete?', 'waymark-plugin'),
							'default' => Waymark_Config::get_setting('misc', 'editor_options', 'confirm_delete'),
							'tip' => esc_attr__('Whether to show a confirmation dialog before deleting Markers/Lines/Shapes from the Map. Pro Tip! Even if you accidentally delete something, changes are not saved until the "Update" button is clicked.', 'waymark-plugin')
						),
						'editor_basemap' => array(
							'name' => 'editor_basemap',
							'id' => 'editor_basemap',
							'type' => 'select',
							'title' => esc_html__('Default Basemap', 'waymark-plugin'),
							'default' => Waymark_Config::get_setting('misc', 'editor_options', 'editor_basemap'),
							'tip' => esc_attr__('Which Basemap to use as the editor default.', 'waymark-plugin'),
							'options' => $basemap_options
						)													
					)											
				),

				'advanced' => array(
					'title' => esc_html__('Advanced', 'waymark-plugin'),
					'description' => '',
					'fields' => array(
						'debug_mode' => array(
							'name' => 'debug_mode',
							'id' => 'debug_mode',
							'type' => 'boolean',
							'title' => esc_html__('Debug Mode', 'waymark-plugin'),
							'default' => Waymark_Config::get_setting('misc', 'advanced', 'debug_mode'),
							'tip' => esc_attr__('With debug mode enabled, the plugin will output Map and Settings data in Admin Dashboard. This may come in handy if you need to report a bug.', 'waymark-plugin'),
							'options' => array(
								'0' => esc_html__('Disable', 'waymark-plugin'),
								'1' => esc_html__('Enable', 'waymark-plugin')								
							)
						),
						'settings_output' => array(
							'name' => 'settings_output',
							'id' => 'settings_output',
							'type' => 'textarea',
							'title' => esc_html__('Settings Data', 'waymark-plugin'),
							'default' => json_encode(get_option('Waymark_Settings')),
							//Don't save to DB
							'input_processing' => array(
								'null'
							),
							//Don't allow editing
							'output_processing' => array(
								'json_encode(get_option("Waymark_Settings"))'
							)
						)													
					)											
				)												
			)
		);
			
		add_action('admin_init', array($this, 'register_settings'));				
	}
	
	function get_settings() {
		return $this->current_settings;
	}
	
	function register_settings(){
		register_setting($this->page_slug, 'Waymark_Settings', array($this, 'sanitize_callback'));
		
		//For each tab		
		foreach($this->tabs as $tab_key => $tab_data) {		
			//For each section
			foreach($tab_data['sections'] as $section_key => $section_data) {				
				//Create section
				add_settings_section($section_key, $section_data['title'], array($this, 'section_text'), $this->page_slug);		
				
				//For each field in section
				if(is_array($section_data['fields']) && sizeof($section_data['fields'])) {
					foreach($section_data['fields'] as $field) {
						//Get set_value
						if(array_key_exists($tab_key, $this->current_settings) && array_key_exists($section_key, $this->current_settings[$tab_key])) {
							if(array_key_exists($field['name'], $this->current_settings[$tab_key][$section_key])) {
								$field['set_value'] = $this->current_settings[$tab_key][$section_key][$field['name']];
							}
						}
						
						//Modify name for multi-dimensional array
						$field['name'] = 'Waymark_Settings[' . $tab_key . '][' . $section_key . '][' . $field['name'] . ']';
						
						//Repeatable section
						if(isset($section_data['repeatable']) && $section_data['repeatable']) {
							//Get count
							$repeatable_count = Waymark_Helper::get_section_repeatable_count($section_data);
							
							//Must be an array
							if(! is_array($field['default']) ) {
								//Make array
								$field['default'] = Waymark_Helper::convert_single_value_to_array($field['default']);
							}
							
							//Array size must match
							if(sizeof($field['default']) < $repeatable_count) {
								//Pad
								$field['default'] = array_pad($field['default'], $repeatable_count, $field['default'][0]);	 							
							}							
						}	

						add_settings_field($field['name'], $field['title'], array($this, 'create_input'), $this->page_slug, $section_key, $field);														
					}						
				}			
			}			
		}
	}
	
	function create_input($field) {
		//Set value
		if(array_key_exists('set_value', $field)) {
			$set_value = $field['set_value'];
		} else {
			$set_value = null;
		}

		echo Waymark_Input::create_field($field, $set_value, false);
	}	

	function section_text($args) {
		//Unused
	}
	
	function sanitize_callback($input_data) {
		//For each tab
		foreach($this->tabs as $tab_key => $tab_data) {
			//If we have sections
			if(array_key_exists('sections', $tab_data)) {
				//Iterate over each section
				foreach($tab_data['sections'] as $section_key => $section_data) {
					//If section has fields
					if(array_key_exists('fields', $section_data)) {
						//For each field
						foreach($section_data['fields'] as $field_key => $field_definition) {
							//If this field was submitted
							if(isset($input_data[$tab_key][$section_key][$field_definition['name']])) {															
								$value = $input_data[$tab_key][$section_key][$field_definition['name']];
								
								//Make safe
								$field_definition['input_processing'][] = '(! strpos($param_value, "&")) ? htmlspecialchars($param_value) : $param_value';
								
								//Process the input
								$input_data[$tab_key][$section_key][$field_definition['name']] = Waymark_Input::process_input($field_definition, $value);
							}
						}					
					}
				}				
			}
		}
		
		return $input_data;
	}	

	function content_admin_page() {
		echo '<div id="waymark-admin-container" class="wrap">' . "\n";

		echo Waymark_Helper::plugin_about();

		echo '	<div class="card">' . "\n";	
		echo '		<h1>' . esc_html__('Settings', 'waymark-plugin') . '</h1>' . "\n";
		
		//Tabs
		$active_tab = (isset($_GET['tab'])) ? $_GET['tab'] : $this->default_tab;
		$this->waymark_admin_tabs($active_tab);
		
		//Open form
		echo '		<form action="' . admin_url('options.php') . '" method="post">' . "\n";
		settings_fields($this->page_slug);

		//For each tab		
		foreach($this->tabs as $tab_key => $tab_data) {
			$style = '';
			if($active_tab != $tab_key) {
				$style = ' style="display:none;"';
			}
			echo '	<div class="waymark-settings-tab waymark-settings-tab-' . $tab_key . '"' . $style . '>' . "\n";

			//Tab description?
			if(array_key_exists('description', $tab_data)) {
				echo '	<p class="waymark-settings-tab-description">' . $tab_data['description'] . '</p>' . "\n";
			}

			//For each section
			foreach($tab_data['sections'] as $section_key => $section_data) {
				echo '		<div class="waymark-settings-section-' . $section_key. '">' . "\n";
				
				//Help
				if(array_key_exists('help', $section_data) && isset($section_data['help']['url'])) {
					$help_text = (isset($section_data['help']['text'])) ? $section_data['help']['text'] : 'View Help &raquo;';

					echo '		<a class="waymark-right button" href="' . $section_data['help']['url'] . '" target="_blank">' . $help_text . '</a>' . "\n";				
				}
				
				//Title
				echo '		<h2>' . $section_data['title'] . '</h2>' . "\n";

				//Description
				if(array_key_exists('description', $section_data)) {
					echo '		<div class="waymark-settings-section-description">' . $section_data['description'] . '</div>' . "\n";
				}		
				
				//Repeatable?
				if(array_key_exists('repeatable', $section_data) && $section_data['repeatable']) {
					echo '<div class="waymark-repeatable" data-count="0">' . "\n";
				}
				
        echo '		<table class="form-table">' . "\n";
        do_settings_fields($this->page_slug, $section_key);					
        echo '		</table>' . "\n";        

				//Footer
				if(array_key_exists('footer', $section_data)) {
					echo '	<div class="waymark-settings-section-footer">' . $section_data['footer'] . '</div>' . "\n";
				}

				//Repeatable?
				if(array_key_exists('repeatable', $section_data) && $section_data['repeatable']) {
					echo '</div>' . "\n";
				}
				
				echo '</div>' . "\n";
			}
			
			echo '	</div>' . "\n";			
		}

		submit_button(null, 'primary large');
		echo '		</form>' . "\n";
		
		echo '	</div>' . "\n";
		echo '</div>' . "\n";
	}	

	function waymark_admin_tabs($current = 'map') {
	  $links = array();
	  foreach($this->tabs as $tab_key => $tab_data) {
			if($tab_key == $current) {
				$links[] = '<a class="nav-tab nav-tab-active" href="?post_type=waymark_map&page=' . $this->page_slug . '&tab=' . $tab_key . '">' . $tab_data['name'] . '</a>';
			} else {
				$links[] = '<a class="nav-tab" href="?post_type=waymark_map&page=' . $this->page_slug . '&tab=' . $tab_key . '">' . $tab_data['name'] . '</a>';
			}
	  }
	  echo '<h2 class="nav-tab-wrapper">';
	  foreach($links as $link) {
			echo $link; 
	  }      
	  echo '</h2>';
	}	
	
	function execute_action($action) {
		switch($action) {
			//Clear cache
			case 'clear_cache' :
				Waymark_Cache::flush();
				
				break;
		}
		
		wp_redirect(admin_url('admin.php?page=waymark-settings&tab=advanced&settings-updated=waymark_action'));

		die;
	}
	
	function admin_notices() {	
		//Ensure is our plugin page
		if(function_exists('get_current_screen')) {  
			$current_screen = get_current_screen();
	
			if(! strpos($current_screen->base, $this->page_slug)) {
				return;
			}	
		}
		
		if(isset($_GET['settings-updated'])) {
			//Settings updates
			if($_GET['settings-updated'] == 'true') {
				echo '<div class="waymark-notice notice notice-success is-dismissible"><p>' . esc_html__('Settings Updated', 'waymark-plugin') . '.</p></div>';				
			//Action	
			} elseif($_GET['settings-updated'] == 'waymark_action') {
				echo '<div class="waymark-notice notice notice-success is-dismissible"><p>' . esc_html__('Action Complete', 'waymark-plugin') . '.</p></div>';				
			}
		}
	}
}
