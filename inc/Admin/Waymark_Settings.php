<?php

class Waymark_Settings {
	private $settings_id = 'Waymark_Settings';
	private $page_slug = 'waymark-settings';
	private $default_content = 'waymark-settings-tab-tiles';
	private $current_settings = array();
	public $tabs = array();
	public $settings_nav;

	function __construct() {

		//Execute action?
		if (sizeof($_POST)) {
			//Clear cache
			if (isset($_POST['Waymark_Settings']['advanced']['performance']['clear_cache'])) {
				$this->execute_action('clear_cache');
			}
		}

		//Get current settings from DB
		$current_settings = get_option('Waymark_Settings');
		if (is_array($current_settings)) {
			$this->current_settings = $current_settings;
		}

		/**
		 * ===========================================
		 * =============== SETTINGS NAV ==============
		 * ===========================================
		 */

		$this->settings_nav = [
			'label_maps' => esc_html__('Maps'),
			'waymark-settings-tab-tiles' => '-- ' . esc_html__('Basemaps', 'waymark'),
			'waymark-settings-section-shortcode_options' => '-- ' . esc_html__('Shortcodes', 'waymark'),
			'waymark-settings-section-elevation_options' => '-- ' . esc_html__('Elevation', 'waymark'),
			'waymark-settings-tab-meta' => '-- ' . esc_html__('Meta', 'waymark'),
			'waymark-settings-section-interaction_options' => '-- ' . esc_html__('Sleep', 'waymark'),
			'waymark-settings-section-cluster_options' => '-- ' . esc_html__('Clustering', 'waymark'),
			'waymark-settings-section-map_options' => '-- ' . esc_html__('Misc.', 'waymark'),
			'label_overlays' => esc_html__('Overlays'),
			'waymark-settings-tab-markers' => '-- ' . esc_html__('Markers', 'waymark'),
			'waymark-settings-tab-lines' => '-- ' . esc_html__('Lines', 'waymark'),
			'waymark-settings-tab-shapes' => '-- ' . esc_html__('Shapes', 'waymark'),
			'waymark-settings-tab-properties' => '-- ' . esc_html__('Properties', 'waymark'),
			'label_sources' => esc_html__('Sources'),
			'waymark-settings-tab-submission' => '-- ' . esc_html__('Submissions', 'waymark'),
			'waymark-settings-tab-misc' => esc_html__('Advanced', 'waymark'),
		];

		/**
		 * ===========================================
		 * ================= BASEMAPS ================
		 * ===========================================
		 */

		$this->tabs['tiles'] = array(
			'name' => esc_html__('Basemaps', 'waymark'),
			'description' => '',
			'sections' => array(
				'layers' => array(
					'repeatable' => true,
					'title' => esc_html__('Basemaps', 'waymark'),
					'description' => sprintf(__('<span class="waymark-lead">Waymark uses the excellent <a href="%s">OpenStreetMap</a> as it’s default Basemap and supports many <a href="%s">other providers</a>.<br /><br /><a href="%s">Thunderforest</a> and <a href="%s">Mapbox</a> are examples of providers that offer easy access to beautiful Basemaps (including satellite imagery). They require registration, but have a free usage tier.', 'waymark'), 'https://www.openstreetmap.org/fixthemap', 'https://leaflet-extras.github.io/leaflet-providers/preview/', 'https://www.thunderforest.com/', 'https://www.mapbox.com/maps/'),
					'footer' => sprintf(__('<small><b>Pro Tip!</b> If you have more than one Basemap, you can switch between them when viewing the Map. The first listed will be used as the default, unless specified in the shortcode like this: %s. Drag to re-order, remove all to restore defaults.</small>', 'waymark'), '[' . Waymark_Config::get_item('shortcode') . ' map_id=&quot;1234&quot; basemap=&quot;Basemap Name&quot;]'),
					'help' => array(
						'url' => esc_attr(Waymark_Helper::site_url('docs/basemaps')),
						'text' => esc_attr__('Basemap Docs &raquo;', 'waymark'),
					),
					'fields' => array(
						'layer_name' => array(
							'name' => 'layer_name',
							'id' => 'layer_name',
							'type' => 'text',
							'class' => '',
							'title' => '<u>' . esc_html__('Basemap', 'waymark') . '</u> ' . esc_html__('Name', 'waymark'),
							'default' => Waymark_Config::get_setting('tiles', 'layers', 'layer_name'),
							'tip' => sprintf(esc_attr__('The Layer Name will appear in a dropdown list shown by the Map when multiple Basemaps have been entered. You can change the default basemap in the shortcode: %s', 'waymark'), '[' . Waymark_Config::get_item('shortcode') . ' map_id=&quot;1234&quot; basemap=&quot;Basemap Name&quot;]'),
							'input_processing' => array(
								'(! empty($param_value)) ? $param_value : "' . esc_html__('Basemap', 'waymark') . ' ' . substr(md5(rand(0, 999999)), 0, 5) . '";', //Fallback
							),
						),
						'layer_url' => array(
							'name' => 'layer_url',
							'id' => 'layer_url',
							'type' => 'text',
							'class' => '',
							'title' => '<span class="waymark-invisible">' . esc_html__('Basemap', 'waymark') . '</span> URL',
							'default' => Waymark_Config::get_setting('tiles', 'layers', 'layer_url'),
							'tip' => sprintf(esc_attr__('Many mapping services support the Slippy Map format. Waymark requires URLs that contain {z} (zoom level) and {x}/{y} (tile coordinates). For example the OpenCycleMap URL is %s.', 'waymark'), 'https://tile.thunderforest.com/cycle/{z}/{x}/{y}@2x.png?apikey=[your_api_key]'),
							'tip_link' => 'https://www.thunderforest.com/docs/map-tiles-api/',
						),
						'layer_attribution' => array(
							'name' => 'layer_attribution',
							'id' => 'layer_attribution',
							'type' => 'text',
							'class' => '',
							'title' => '<span class="waymark-invisible">' . esc_html__('Basemap', 'waymark') . '</span> Attribution',
							'default' => Waymark_Config::get_setting('tiles', 'layers', 'layer_attribution'),
							'tip' => esc_attr__('Mapping services often have the requirement that attribution is displayed by the map. Text and HTML links are supported.', 'waymark'),
							'tip_link' => 'https://www.thunderforest.com/terms/#attribution',
							'input_processing' => array(
								'(! strpos($param_value, "&")) ? htmlspecialchars($param_value) : $param_value',
							),
						),
						'layer_max_zoom' => array(
							'name' => 'layer_max_zoom',
							'id' => 'layer_max_zoom',
							'type' => 'text',
							'class' => 'waymark-short-input',
							'title' => '<span class="waymark-invisible">' . esc_html__('Basemap', 'waymark') . '</span> ' . esc_html__('Max Zoom', 'waymark'),
							'default' => Waymark_Config::get_setting('tiles', 'layers', 'layer_max_zoom'),
							'tip' => esc_attr__('Set a maximum zoom level for this Basemap, the default is 18.', 'waymark'),
							'input_processing' => array(
								'(is_numeric($param_value) && $param_value >= 1 && $param_value <= 18) ? $param_value : 18;', //Fallback
							),
						),
					),
				),
			),
		);

		/**
		 * ===========================================
		 * ================= MARKERS =================
		 * ===========================================
		 */

		$this->tabs['markers'] = array(
			'name' => esc_html__('Markers', 'waymark'),
			'description' => '',
			'sections' => array(
				'marker_types' => array(
					'repeatable' => true,
					'title' => esc_html__('Marker', 'waymark') . ' ' . esc_html__('Types', 'waymark'),
					'description' => '<span class="waymark-lead">' . __('Customise how Markers are displayed on the Map. Set these styles once, then select the appropriate Type when you add to the Map.', 'waymark') . '</span>',
					'footer' => '<small>' . __('<b>Pro Tip!</b> The first listed will be used as the default. Drag to re-order, remove all to restore defaults.', 'waymark') . '</small>',
					'help' => array(
						'url' => esc_attr(Waymark_Helper::site_url('docs/types')),
						'text' => esc_attr__('Type Docs &raquo;', 'waymark'),
					),
					'fields' => array(
						'marker_title' => array(
							'name' => 'marker_title',
							'id' => 'marker_title',
							'type' => 'text',
							'class' => 'waymark-uneditable',
							'title' => '<u>' . esc_html__('Marker', 'waymark') . '</u> ' . esc_html__('Label', 'waymark'),
							'default' => Waymark_Config::get_setting('markers', 'marker_types', 'marker_title'),
							'tip' => esc_attr__('What kind of Marker is this? E.g. "Photo", "Grocery Store", "Warning!". Once saved, Marker labels can not be edited. The Marker Label is displayed in the Tooltip (when hovering over the Marker) and in the Info Window (once the Marker is clicked). Hide in Settings > Map > Misc. > Type Labels.', 'waymark'),
							'input_processing' => array(
								'(! empty($param_value)) ? $param_value : "' . esc_html__('Marker', 'waymark') . ' ' . substr(md5(rand(0, 999999)), 0, 5) . '";', //Fallback
							),
						),
						'marker_shape' => array(
							'name' => 'marker_shape',
							'id' => 'marker_shape',
							'type' => 'select',
							'class' => '',
							'title' => '<span class="waymark-invisible">' . esc_html__('Marker', 'waymark') . '</span> ' . esc_html__('Shape', 'waymark'),
							'default' => Waymark_Config::get_setting('markers', 'marker_types', 'marker_shape'),
							'tip' => esc_attr__('Which shape of Marker to use. Circles and Squares are centered at the specified location, Markers point down to that location.', 'waymark'),
							'options' => array(
								'marker' => esc_html__('Marker', 'waymark'),
								'circle' => esc_html__('Circle', 'waymark'),
								'rectangle' => esc_html__('Square', 'waymark'),
							),
						),
						'marker_size' => array(
							'name' => 'marker_size',
							'id' => 'marker_size',
							'type' => 'select',
							'class' => '',
							'title' => '<span class="waymark-invisible">' . esc_html__('Marker', 'waymark') . '</span> ' . esc_html__('Size', 'waymark'),
							'default' => Waymark_Config::get_setting('markers', 'marker_types', 'marker_size'),
							'tip' => esc_attr__('Which size of Marker to use.', 'waymark'),
							'options' => array(
								'small' => esc_html__('Small', 'waymark'),
								'medium' => esc_html__('Medium', 'waymark'),
								'large' => esc_html__('Large', 'waymark'),
							),
						),
						'marker_colour' => array(
							'name' => 'marker_colour',
							'id' => 'marker_colour',
							'type' => 'text',
							'class' => 'waymark-short-input',
							'title' => '<span class="waymark-invisible">' . esc_html__('Marker', 'waymark') . '</span> ' . esc_html__('Background', 'waymark'),
							'default' => Waymark_Config::get_setting('markers', 'marker_types', 'marker_colour'),
							'tip' => esc_attr__('The Marker background colour. Click "Select Colour" to select.', 'waymark'),
							'input_processing' => array(
								'(! empty($param_value)) ? $param_value : "white";', //Fallback
							),
						),
						'marker_display' => array(
							'name' => 'marker_display',
							'id' => 'marker_display',
							'type' => 'boolean',
							'title' => '<span class="waymark-invisible">' . esc_html__('Marker', 'waymark') . '</span> ' . esc_html__('Show Initially', 'waymark'),
							'default' => Waymark_Config::get_setting('markers', 'marker_types', 'marker_display'),
							'tip' => esc_attr__('When using the Overlay Filter you can choose to show/hide certain Types when the Map initially loads.', 'waymark'),
							'input_processing' => array(
								'(is_numeric($param_value)) ? $param_value : 1;', //Fallback
							),
						),
						'marker_submission' => array(
							'name' => 'marker_submission',
							'id' => 'marker_submission',
							'type' => 'boolean',
							'title' => '<span class="waymark-invisible">' . esc_html__('Marker', 'waymark') . '</span> ' . esc_html__('Submissions?', 'waymark'),
							'default' => Waymark_Config::get_setting('markers', 'marker_types', 'marker_submission'),
							'tip' => esc_attr__('Make this Type available in front-end Submissions?', 'waymark'),
							'input_processing' => array(
								'(is_numeric($param_value)) ? $param_value : 1;', //Fallback
							),
						),
						'icon_type' => array(
							'name' => 'icon_type',
							'id' => 'icon_type',
							'type' => 'select',
							'class' => '',
							'title' => '<span style="display:inline-block;min-width:50px">' . esc_html__('Icon', 'waymark') . '</span>' . esc_html__('Type', 'waymark'),
							'default' => Waymark_Config::get_setting('markers', 'marker_types', 'icon_type'),
							'tip' => esc_attr__('Font Icons are available from Font Awesome and Ionic Icons. Simple Text or Emojis are supported, as well as custom HTML. So you can pretty much use anything you like!', 'waymark'),
							'tip_link' => 'https://emojifinder.com/',
							'options' => array(
								'icon' => esc_html__('Font Icon', 'waymark'),
								'text' => esc_html__('Text (or Emoji!)', 'waymark'),
								'html' => esc_html__('HTML', 'waymark'),
							),
						),
						'marker_icon' => array(
							'name' => 'marker_icon',
							'id' => 'marker_icon',
							'type' => 'text',
							'class' => 'waymark-short-input',
							'title' => '<span class="waymark-invisible" style="display:inline-block;min-width:50px">' . esc_html__('Icon', 'waymark') . '</span><span class="waymark-icon-type">' . esc_html__('Name', 'waymark') . '</span>',
							'default' => Waymark_Config::get_setting('markers', 'marker_types', 'marker_icon'),
							'tip' => esc_attr__('The desired icon name from Ionicons or Font Awesome, e.g. "ion-camera", or "fa-camera". Click the links to see the full list of icons available.|Text to display inside the Marker, in the chosen colour. Space is very limited! Pro Tip: adjust text size using CSS; for all Markers: .waymark-icon-text{font-size: 18px}, or by Type: .waymark-marker-photo .waymark-icon-text{...}. Use your browser\'s inspector to dig for Type class names.|The HTML entered will be added inside each Marker. Pro Tip! HTML Entities supported (e.g. &amp;cross; as well as Unicode and Emojis!), or provide HTML to integrate with other Icon providers.', 'waymark'),
							'input_processing' => array(
								'(strpbrk($param_value, "\">")) ? htmlspecialchars($param_value) : $param_value',
							),
							'append' => '<div class="waymark-icons-help"><a href="https://ionic.io/ionicons/v2/cheatsheet.html">Ionic Icons</a><a href="https://fontawesome.com/v4.7.0/cheatsheet/">Font Awesome</a></div>',
						),
						'icon_colour' => array(
							'name' => 'icon_colour',
							'id' => 'icon_colour',
							'type' => 'text',
							'class' => 'waymark-short-input waymark-colour-picker',
							'title' => '<span class="waymark-invisible" style="display:inline-block;min-width:50px">' . esc_html__('Icon', 'waymark') . '</span>' . esc_html__('Colour', 'waymark'),
							'default' => Waymark_Config::get_setting('markers', 'marker_types', 'icon_colour'),
							'tip' => esc_attr__('The colour of the icon. Click "Select Colour" to select.', 'waymark'),
							'input_processing' => array(
								'(! empty($param_value)) ? $param_value : "#81d742";', //Fallback
							),
						),
					),
				),
			),
		);

		/**
		 * ===========================================
		 * ================== LINES ==================
		 * ===========================================
		 */

		$this->tabs['lines'] = array(
			'name' => esc_html__('Lines', 'waymark'),
			'description' => '',
			'sections' => array(
				'line_types' => array(
					'repeatable' => true,
					'title' => esc_html__('Line', 'waymark') . ' ' . esc_html__('Types', 'waymark'),
					'description' => '<span class="waymark-lead">' . __('Customise how Lines are displayed on the Map. Set these styles once, then select the appropriate Type when you add to the Map.', 'waymark') . '</span>',
					'help' => array(
						'url' => esc_attr(Waymark_Helper::site_url('docs/types')),
						'text' => esc_attr__('Type Docs &raquo;', 'waymark'),
					),
					'footer' => '<small>' . __('<b>Pro Tip!</b> The first listed will be used as the default. Drag to re-order, remove all to restore defaults.', 'waymark') . '</small>',
					'fields' => array(
						'line_title' => array(
							'name' => 'line_title',
							'id' => 'line_title',
							'type' => 'text',
							'class' => 'waymark-uneditable',
							'title' => '<u>' . esc_html__('Line', 'waymark') . '</u> ' . esc_html__('Label', 'waymark'),
							'default' => Waymark_Config::get_setting('lines', 'line_types', 'line_title'),
							'tip' => esc_attr__('What kind of Line is this? E.g. "Easy", "Walking Only", "Dark Red". The Line Label is displayed in the Tooltip (when hovering over the Line) and in the Line Info Window. Once saved, Line labels can not be edited.', 'waymark'),
							'input_processing' => array(
								'(! empty($param_value)) ? $param_value : "' . esc_html__('Line', 'waymark') . ' ' . substr(md5(rand(0, 999999)), 0, 5) . '";', //Fallback
							),
						),
						'line_colour' => array(
							'name' => 'line_colour',
							'id' => 'line_colour',
							'type' => 'text',
							'class' => 'waymark-short-input waymark-colour-picker',
							'title' => '<span class="waymark-invisible">' . esc_html__('Line', 'waymark') . '</span> ' . esc_html__('Colour', 'waymark'),
							'default' => Waymark_Config::get_setting('lines', 'line_types', 'line_colour'),
							'tip' => esc_attr__('The colour of the Line. Click "Select Colour" to select.', 'waymark'),
							'input_processing' => array(
								'(! empty($param_value)) ? $param_value : "#81d742";', //Fallback
							),
						),
						'line_weight' => array(
							'name' => 'line_weight',
							'id' => 'line_weight',
							'type' => 'text',
							'class' => 'waymark-short-input',
							'title' => '<span class="waymark-invisible">' . esc_html__('Line', 'waymark') . '</span> ' . esc_html__('Weight', 'waymark'),
							'default' => Waymark_Config::get_setting('lines', 'line_types', 'line_weight'),
							'tip' => esc_attr__('The width of the Line, in pixels.', 'waymark'),
							'input_processing' => array(
								'(is_numeric($param_value)) ? $param_value : 3;', //Fallback
							),
						),
						'line_opacity' => array(
							'name' => 'line_opacity',
							'id' => 'line_opacity',
							'type' => 'text',
							'class' => 'waymark-short-input',
							'title' => '<span class="waymark-invisible">' . esc_html__('Line', 'waymark') . '</span> ' . esc_html__('Opacity', 'waymark'),
							'default' => Waymark_Config::get_default('lines', 'line_types', 'line_opacity'),
							'tip' => esc_attr__('The opacity of the Line, between 0.0 and 1.0 (e.g. "0.5").', 'waymark'),
							'input_processing' => array(
								'(is_numeric($param_value) && $param_value > 0 && $param_value <= 1) ? $param_value : 0.7;', //Fallback
							),
						),
						'line_display' => array(
							'name' => 'line_display',
							'id' => 'line_display',
							'type' => 'boolean',
							'title' => '<span class="waymark-invisible">' . esc_html__('Line', 'waymark') . '</span> ' . esc_html__('Show Initially', 'waymark'),
							'default' => Waymark_Config::get_setting('lines', 'line_types', 'line_display'),
							'tip' => esc_attr__('When using the Overlay Filter you can choose to show/hide certain Types when the Map initially loads.', 'waymark'),
							'input_processing' => array(
								'(is_numeric($param_value)) ? $param_value : 1;', //Fallback
							),
						),
						'line_submission' => array(
							'name' => 'line_submission',
							'id' => 'line_submission',
							'type' => 'boolean',
							'title' => '<span class="waymark-invisible">' . esc_html__('Line', 'waymark') . '</span> ' . esc_html__('Submissions?', 'waymark'),
							'default' => Waymark_Config::get_setting('lines', 'line_types', 'line_submission'),
							'tip' => esc_attr__('Make this Type available in front-end Submissions?', 'waymark'),
							'input_processing' => array(
								'(is_numeric($param_value)) ? $param_value : 1;', //Fallback
							),
						),
					),
				),
			),
		);

		/**
		 * ===========================================
		 * ================= SHAPES ==================
		 * ===========================================
		 */

		$this->tabs['shapes'] = array(
			'name' => esc_html__('Shapes', 'waymark'),
			'description' => '',
			'sections' => array(
				'shape_types' => array(
					'repeatable' => true,
					'title' => esc_html__('Shape', 'waymark') . ' ' . esc_html__('Types', 'waymark'),
					'description' => '<span class="waymark-lead">' . __('Customise how Shapes are displayed on the Map. Set these styles once, then select the appropriate Type when you add to the Map.', 'waymark') . '</span>',
					'help' => array(
						'url' => esc_attr(Waymark_Helper::site_url('docs/types')),
						'text' => esc_attr__('Type Docs &raquo;', 'waymark'),
					),
					'footer' => '<small>' . __('<b>Pro Tip!</b> The first listed will be used as the default. Drag to re-order, remove all to restore defaults.', 'waymark') . '</small>',
					'fields' => array(
						'shape_title' => array(
							'name' => 'shape_title',
							'id' => 'shape_title',
							'type' => 'text',
							'class' => 'waymark-uneditable',
							'title' => '<u>' . esc_html__('Shape', 'waymark') . '</u> ' . esc_html__('Label', 'waymark'),
							'default' => Waymark_Config::get_setting('shapes', 'shape_types', 'shape_title'),
							'tip' => esc_attr__('What kind of Shape is this? E.g. "Park", "Danger!", "Light Blue". The Shape Label is displayed in the Tooltip (when hovering over the Shape) and in the Shape Info Window. Once saved, Shape labels can not be edited.', 'waymark'),
							'input_processing' => array(
								'(! empty($param_value)) ? $param_value : "' . esc_html__('Shape', 'waymark') . ' ' . substr(md5(rand(0, 999999)), 0, 5) . '";', //Fallback
							),
						),
						'shape_colour' => array(
							'name' => 'shape_colour',
							'id' => 'shape_colour',
							'type' => 'text',
							'class' => 'waymark-short-input waymark-colour-picker',
							'title' => '<span class="waymark-invisible">' . esc_html__('Shape', 'waymark') . '</span> ' . esc_html__('Colour', 'waymark'),
							'default' => Waymark_Config::get_setting('shapes', 'shape_types', 'shape_colour'),
							'tip' => esc_attr__('The colour of the Shape. Click "Select Colour" to select.', 'waymark'),
							'input_processing' => array(
								'(! empty($param_value)) ? $param_value : "#81d742";', //Fallback
							),
						),
						'fill_opacity' => array(
							'name' => 'fill_opacity',
							'id' => 'fill_opacity',
							'type' => 'text',
							'class' => 'waymark-short-input',
							'title' => '<span class="waymark-invisible">' . esc_html__('Shape', 'waymark') . '</span> ' . esc_html__('Fill Opacity', 'waymark'),
							'default' => Waymark_Config::get_setting('shapes', 'shape_types', 'fill_opacity'),
							'tip' => esc_attr__('The opacity of the inside of the shape, between 0.0 and 1.0 (e.g. "0.5").', 'waymark'),
							'input_processing' => array(
								'(is_numeric($param_value) && $param_value > 0 && $param_value <= 1) ? $param_value : 0.5;', //Fallback
							),
						),
						'shape_display' => array(
							'name' => 'shape_display',
							'id' => 'shape_display',
							'type' => 'boolean',
							'title' => '<span class="waymark-invisible">' . esc_html__('Shape', 'waymark') . '</span> ' . esc_html__('Show Initially', 'waymark'),
							'default' => Waymark_Config::get_setting('shapes', 'shape_types', 'shape_display'),
							'tip' => esc_attr__('When using the Overlay Filter you can choose to show/hide certain Types when the Map initially loads.', 'waymark'),
							'input_processing' => array(
								'(is_numeric($param_value)) ? $param_value : 1;', //Fallback
							),
						),
						'shape_submission' => array(
							'name' => 'shape_submission',
							'id' => 'shape_submission',
							'type' => 'boolean',
							'title' => '<span class="waymark-invisible">' . esc_html__('Shape', 'waymark') . '</span> ' . esc_html__('Submissions?', 'waymark'),
							'default' => Waymark_Config::get_setting('shapes', 'shape_types', 'shape_submission'),
							'tip' => esc_attr__('Make this Type available in front-end Submissions?', 'waymark'),
							'input_processing' => array(
								'(is_numeric($param_value)) ? $param_value : 1;', //Fallback
							),
						),
					),
				),
			),
		);

		/**
		 * ===========================================
		 * =================== META ==================
		 * ===========================================
		 */

		$meta_group_options = Waymark_Helper::repeatable_setting_option_array('meta', 'groups', 'group_title');
		$meta_group_options = array_merge(['' => 'None'], $meta_group_options);

		$this->tabs['meta'] = array(
			'name' => esc_html__('Meta', 'waymark'),
			'description' => '',
			'sections' => array(
				'inputs' => array(
					'repeatable' => true,
					'title' => esc_html__('Meta', 'waymark'),
					'description' => '<span class="waymark-lead">' . sprintf(__('Create additional input fields that appear underneath the Map Editor. Any Meta that has been input is displayed on the <a href="%s">Map Details</a> page, and can also be displayed by the Shortcode.', 'waymark'), 'https://www.waymark.dev/map/route-map/') . '</span>',
					'help' => array(
						'url' => esc_attr(Waymark_Helper::site_url('docs/meta')),
						'text' => esc_attr__('Meta Docs &raquo;', 'waymark'),
					),
					'footer' => '<small>' . __('<b>Pro Tip!</b> The first listed will be used as the default. Drag to re-order, remove all to restore defaults.', 'waymark') . '</small>',
					'fields' => array(
						'meta_title' => array(
							'name' => 'meta_title',
							'id' => 'meta_title',
							'type' => 'text',
							'class' => '',
							'title' => '<u>' . esc_html__('Meta', 'waymark') . '</u> ' . esc_html__('Title', 'waymark'),
							'default' => Waymark_Config::get_setting('meta', 'inputs', 'meta_title'),
							'tip' => esc_attr__('The title appears next to the input field.', 'waymark'),
							'class' => Waymark_Config::get_item('meta', 'inputs') ? 'waymark-uneditable' : '',
							'input_processing' => array(
								'(! empty($param_value)) ? $param_value : "' . esc_html__('Meta', 'waymark') . ' ' . substr(md5(rand(0, 999999)), 0, 5) . '";', //Fallback
							),
						),
						'meta_default' => array(
							'name' => 'meta_default',
							'id' => 'meta_default',
							'type' => 'text',
							'class' => '',
							'title' => '<span class="waymark-invisible">' . esc_html__('Meta', 'waymark') . '</span> ' . esc_html__('Default', 'waymark'),
							'default' => Waymark_Config::get_setting('meta', 'inputs', 'meta_default'),
							'tip' => esc_attr__('The default value for the input field. For Select and Multi-Select enter the option/comma-separated options to be selected by default.', 'waymark'),
						),
						'meta_tip' => array(
							'name' => 'meta_tip',
							'id' => 'meta_tip',
							'type' => 'text',
							'class' => '',
							'title' => '<span class="waymark-invisible">' . esc_html__('Meta', 'waymark') . '</span> ' . esc_html__('Tip', 'waymark'),
							'default' => Waymark_Config::get_setting('meta', 'inputs', 'meta_tip'),
							'tip' => esc_attr__('A tip provides additional information about an input field... just like this!', 'waymark'),
						),
						'meta_type' => array(
							'name' => 'meta_type',
							'id' => 'meta_type',
							'type' => 'select',
							'class' => '',
							'title' => '<span class="waymark-invisible">' . esc_html__('Meta', 'waymark') . '</span> ' . esc_html__('Type', 'waymark'),
							'default' => Waymark_Config::get_setting('meta', 'inputs', 'meta_type'),
							'tip' => esc_attr__('The type of input field to use.', 'waymark'),
							'options' => array(
								'text' => esc_html__('Text', 'waymark'),
								'textarea' => esc_html__('Textarea', 'waymark'),
								'textarea_rich' => esc_html__('Rich Text', 'waymark'),
								'select' => esc_html__('Select', 'waymark'),
								'select_multi' => esc_html__('Multi-Select', 'waymark'),
							),
						),
						'meta_options' => array(
							'name' => 'meta_options',
							'id' => 'meta_options',
							'type' => 'text',
							'class' => '',
							'title' => '<span class="waymark-invisible">' . esc_html__('Meta', 'waymark') . '</span> ' . esc_html__('Options', 'waymark'),
							'default' => Waymark_Config::get_setting('meta', 'inputs', 'meta_options'),
							'tip' => esc_attr__('A comma-separated list of options for the input.', 'waymark'),
						),
						'meta_group' => array(
							'name' => 'meta_group',
							'id' => 'meta_group',
							'type' => 'select',
							'options' => $meta_group_options,
							'class' => (sizeof($meta_group_options) === 1) ? 'waymark-hidden' : '',
							'title' => '<span class="waymark-invisible">' . esc_html__('Meta', 'waymark') . '</span> ' . esc_html__('Group', 'waymark'),
							'default' => Waymark_Config::get_setting('meta', 'inputs', 'meta_group'),
							'tip' => esc_attr__('Which group this Meta belongs to (if any). Meta in the same group will be displayed together when editing and viewing Maps. Meta not in a group will be displayed above any groups.', 'waymark'),
						),
						'meta_shortcode' => array(
							'name' => 'meta_shortcode',
							'id' => 'meta_shortcode',
							'type' => 'boolean',
							'class' => '',
							'title' => '<span class="waymark-invisible">' . esc_html__('Meta', 'waymark') . '</span> ' . esc_html__('In Shortcode?', 'waymark'),
							'default' => Waymark_Config::get_setting('meta', 'inputs', 'meta_shortcode'),
							'tip' => esc_attr__('Whether this content should be displayed when embedding a Map using the Shortcode.', 'waymark'),
						),
						'meta_submission' => array(
							'name' => 'meta_submission',
							'id' => 'meta_submission',
							'type' => 'boolean',
							'class' => '',
							'title' => '<span class="waymark-invisible">' . esc_html__('Meta', 'waymark') . '</span> ' . esc_html__('In Submissions?', 'waymark'),
							'default' => Waymark_Config::get_setting('meta', 'inputs', 'meta_submission'),
							'tip' => esc_attr__('Make this Meta available in front-end Submissions?', 'waymark'),
						),
					),
				),
				'groups' => array(
					'repeatable' => true,
					'title' => esc_html__('Groups', 'waymark'),
					'description' => '<span class="waymark-lead">' . __('Create groups to organise your Map Meta. Meta in the same group will be displayed together when editing and viewing Maps.', 'waymark') . '</span>',
					'footer' => '<small>' . __('<b>Pro Tip!</b> Drag to re-order, remove all to disable groups.', 'waymark') . '</small>',
					'fields' => array(
						'group_title' => array(
							'name' => 'group_title',
							'id' => 'group_title',
							'type' => 'text',
							'class' => '',
							'title' => '<u>' . esc_html__('Group', 'waymark') . '</u> ' . esc_html__('Title', 'waymark'),
							'default' => '',
							'tip' => esc_attr__('The title appears above the Meta in that group.', 'waymark'),
// 							'class' => Waymark_Config::get_item('meta', 'inputs') ? 'waymark-uneditable' : '',
// 							'input_processing' => array(
// 								'(! empty($param_value)) ? $param_value : "' . esc_html__('Meta', 'waymark') . ' ' . substr(md5(rand(0,999999)), 0, 5) . '";'	//Fallback
// 							)
						),
					),
				),
			),
		);

		/**
		 * ===========================================
		 * =============== PROPERTIES ================
		 * ===========================================
		 */

		$this->tabs['properties'] = array(
			'name' => esc_html__('Properties', 'waymark'),
			'description' => '',
			'sections' => array(
				'props' => array(
					'repeatable' => true,
					'title' => esc_html__('Properties', 'waymark'),
					'description' => '<span class="waymark-lead">' . __('Read <b><a href="https://geojson.org/">GeoJSON</a></b> feature properties when importing.', 'waymark') . '</span><br /><br />' . __('If Waymark finds data for the property keys below it will stored when it is imported. These can be automatically appended to the Overlay Description, or accessed programatically the <code>layer.feature.properties</code> Object.', 'waymark'),
					'footer' => '<small>' . __(sprintf('For example, the properties below can be accessed through the %s, %s, %s Property Keys:', '<code>created_date</code>', '<code>updated_date</code>', '<code>expires_date</code>'), 'waymark') . '</small>
					<pre><code>{
  "type": "FeatureCollection",
  "features": [
    {
      "geometry": { ... },
      "type": "Feature",
      "properties": {
        "created_date": "2021-07-15T00:42:41Z",
        "updated_date": "2021-07-16T00:42:41Z",
        "expires_date": "2021-07-17T00:42:41Z"
      }
    }
  ]
}
</code></pre>',

// 					'help' => array(
// 						'url' => esc_attr(Waymark_Helper::site_url('docs/meta')),
// 						'text' => esc_attr__('Meta Docs &raquo;', 'waymark')
// 					),
					'fields' => array(
						'property_key' => array(
							'name' => 'property_key',
							'id' => 'property_key',
							'type' => 'text',
							'class' => '',
							'title' => '<u>' . esc_html__('Property', 'waymark') . '</u> ' . esc_html__('Key', 'waymark'),
							'default' => Waymark_Config::get_setting('property', 'props', 'property_key'),
							'tip' => esc_attr__('This is the key associated with the data you are trying to access, i.e. "properties": {"property_key": "Some content here"}', 'waymark'),
// 							'class' => Waymark_Config::get_item('meta', 'inputs') ? 'waymark-uneditable' : '',
							'input_processing' => array(
								'preg_replace("/[^0-9a-zA-Z -_.]/", "", $param_value);',
							),
						),
						'property_title' => array(
							'name' => 'property_title',
							'id' => 'property_title',
							'type' => 'text',
							'class' => '',
							'title' => '<u><span class="waymark-invisible">' . esc_html__('Property', 'waymark') . '</span></u> ' . esc_html__('Title', 'waymark'),
							'default' => Waymark_Config::get_setting('property', 'props', 'property_title'),
							'tip' => esc_attr__('The value for this property will be added to the Overlay Description under this title.', 'waymark'),
// 							'class' => Waymark_Config::get_item('meta', 'inputs') ? 'waymark-uneditable' : ''
						),
					),
				),

				'options' => array(
					'title' => esc_html__('Options', 'waymark'),
					'description' => 'Append listed Properties to the Overlay Description.',
					'footer' => sprintf(__('<small><b>Pro Tip!</b> Properties are added to the Overlay Description with class names that can be used to target them, e.g. %s.</small>', 'waymark'), '<code class="waymark-code" style="display:inline">&lt;p class="waymark-property waymark-property-property_key"&gt;&lt;b&gt;property_title&lt;/b&gt;&lt;br&gt;proprty_value&lt;/p&gt;</code>'),
					'fields' => array(
						'description_append' => array(
							'name' => 'description_append',
							'id' => 'description_append',
							'type' => 'boolean',
							'title' => esc_html__('Append', 'waymark'),
							'default' => Waymark_Config::get_setting('property', 'options', 'description_append'),
							'tip' => esc_attr__('Append the property value to the Overlay Description.', 'waymark'),

						),
					),
				),
			),
		);

		//Prepare Basemap values for editor option
		$tile_layers = Waymark_Config::get_item('tiles', 'layers', true);

		//Each layer
		$basemap_options = array();
		foreach ($tile_layers as $layer) {
			//If name exists
			if (array_key_exists('layer_name', $layer)) {
				//Add as option
				$basemap_options[$layer['layer_name']] = $layer['layer_name'];
			}
		}

		/**
		 * ===========================================
		 * =============== SUBMISSIONS ===============
		 * ===========================================
		 */

		//Build list of Collections to use as <select> options
		$collection_objects = get_terms([
			'taxonomy' => 'waymark_collection',
			'hide_empty' => false,
		]);
		$collection_array = [
			'' => ' - ',
		];
		foreach ($collection_objects as $collection) {
			$collection_array[$collection->term_id] = $collection->name;
		}

		//Roles
		if (!function_exists('get_editable_roles')) {
			require_once ABSPATH . 'wp-admin/includes/user.php';
		}

		$role_options = array();
		foreach (get_editable_roles() as $key => $role) {
			$role_options[$key] = $role['name'];
		}
		unset($role_options['administrator']);

		//Public upload dir
		$upload_dir = wp_upload_dir();
		$upload_dir['subdir'] = ($upload_dir['subdir']) ? $upload_dir['subdir'] : '/';

		$this->tabs['submission'] = array(
			'name' => esc_html__('Submissions', 'waymark'),
			'sections' => array(
				//Global
				'global' => array(
					'title' => esc_html__('Front-End Submissions', 'waymark'),
					'description' => sprintf(__('Use the %s Shortcode to allow Map submissions from the front-end of your site.', 'waymark'), '<span class="waymark-code">[Waymark content="submission"]</span>'),
					'help' => array(
						'url' => esc_attr(Waymark_Helper::site_url('docs/submissions')),
						'text' => esc_attr__('Submission Docs &raquo;', 'waymark'),
					),
					'fields' => array(
						'submission_enable' => array(
							'name' => 'submission_enable',
							'id' => 'submission_enable',
							'type' => 'boolean',
							'title' => esc_html__('Allow Submissions', 'waymark'),
							'default' => Waymark_Config::get_setting('submission', 'global', 'submission_enable'),
							'tip' => esc_attr__('Submissions will be available only to site administrators by default, but can also be allowed for registered users or even to guests without registration.', 'waymark'),
						),
					),
				),
				//By role
				'from_users' => array(
					'title' => esc_html__('User Submissions', 'waymark'),
					'description' => esc_html__('Allow registered users to create Maps from the front-end.', 'waymark'),
					'fields' => array(
						'submission_roles' => array(
							'name' => 'submission_roles',
							'id' => 'submission_roles',
							'type' => 'select_multi',
							'class' => 'waymark-align-top',
							'title' => esc_html__('Allow From', 'waymark'),
							'default' => Waymark_Config::get_setting('submission', 'from_users', 'submission_roles'),
							'tip' => esc_attr__('Users with the selected roles will be able to make Submissions through the front-end', 'waymark'),
							'options' => $role_options,
						),
						'submission_features' => array(
							'name' => 'submission_features',
							'id' => 'submission_users_features',
							'type' => 'select_multi',
							'class' => 'waymark-align-top',
							'title' => esc_html__('Editor Features', 'waymark'),
							'default' => Waymark_Config::get_setting('submission', 'from_users', 'submission_features'),
							'tip' => esc_attr__('What features to offer in the Editor. Important! Uploaded images are added to the Media Library, reading from file does not keep a copy of the file on the server. Whether an individual Meta input is displayed can be set in the Settings > Map > Meta.', 'waymark'),
							'options' => array(
								'draw' => esc_attr__('Drawing', 'waymark'),
								'photo' => esc_attr__('Image upload', 'waymark'),
								'file' => esc_attr__('Read from File', 'waymark'),
								'title' => esc_attr__('Title', 'waymark'),
								'meta' => esc_attr__('Meta', 'waymark'),
							),
						),
						'submission_status' => array(
							'name' => 'submission_status',
							'id' => 'submission_users_status',
							'type' => 'select',
							'title' => esc_html__('Default Status', 'waymark'),
							'default' => Waymark_Config::get_setting('submission', 'from_users', 'submission_status'),
							'tip' => esc_attr__('This is the initial status of the submitted Map. Note! Publish means that the Map (including any images added) will be *immediately* visible on your site.', 'waymark'),
							'options' => array(
								'publish' => esc_attr__('Publish', 'waymark'),
								'draft' => esc_attr__('Draft', 'waymark'),
							),
						),
						'submission_collection' => array(
							'name' => 'submission_collection',
							'id' => 'submission_users_collection',
							'type' => 'select',
							'title' => esc_html__('Default Collection', 'waymark'),
							'tip' => esc_attr__('If specified, user submissions will be automatically added to this Collection.', 'waymark'),
							'options' => $collection_array,
						),
// 						'submission_alert' => array(
// 							'name' => 'submission_alert',
// 							'id' => 'submission_users_alert',
// 							'type' => 'boolean',
// 							'title' => esc_html__('Email Alert', 'waymark'),
// 							'default' => Waymark_Config::get_setting('submission', 'from_users', 'submission_alert'),
// 							'tip' => esc_attr__('Receive email alerts for new submissions.', 'waymark'),
// 							'class' => 'waymark-hidden'
// 						)
					),
				),

				//Public
				'from_public' => array(
					'title' => esc_html__('Public Submissions', 'waymark'),
					'description' => __('This will allow Submissions from <b>any visitor</b>, without registration.<!--<br /><br /><b>Important!</b>-->', 'waymark'),
					'fields' => array(
						'submission_public' => array(
							'name' => 'submission_public',
							'id' => 'submission_public',
							'type' => 'boolean',
							'title' => esc_html__('Public Submissions', 'waymark'),
							'default' => Waymark_Config::get_setting('submission', 'from_public', 'submission_public'),
							'tip' => esc_attr__('Allow *anyone* to submit Maps to your site, without registration.', 'waymark'),
						),
						'submission_features' => array(
							'name' => 'submission_features',
							'id' => 'submission_public_features',
							'type' => 'select_multi',
							'class' => 'waymark-align-top',
							'title' => esc_html__('Editor Features', 'waymark'),
							'default' => Waymark_Config::get_setting('submission', 'from_public', 'submission_features'),
							'tip' => esc_attr__('What features to offer in the Editor. Important! Uploaded images are added to the Media Library (see Upload Location below), reading from file does not keep a copy of the file on the server. Whether an individual Meta input is displayed can be set in the Settings > Map > Meta.', 'waymark'),
							'options' => array(
								'draw' => esc_attr__('Drawing', 'waymark'),
								'photo' => esc_attr__('Image upload', 'waymark'),
								'file' => esc_attr__('Read from File', 'waymark'),
								'title' => esc_attr__('Title', 'waymark'),
								'meta' => esc_attr__('Meta', 'waymark'),
							),
						),
						'submission_upload_dir' => array(
							'name' => 'submission_upload_dir',
							'id' => 'submission_upload_dir',
							'type' => 'select',
							'title' => esc_html__('Upload Location', 'waymark'),
							'default' => Waymark_Config::get_setting('submission', 'from_public', 'submission_upload_dir'),
							'tip' => esc_attr__('Images upload by non-registered users can be stored seperately from other Media Library uploads, to aid with moderation. All uploaded images will be stored in a single directory (/waymark_submission) found in the upload root.', 'waymark'),
							'class' => '',
							'options' => array(
								'waymark_submission' => esc_attr__('Seperated (/waymark_submission)', 'waymark'),
								'' => sprintf(esc_attr__('Media Library Default (%s)', 'waymark'), $upload_dir['subdir']),
							),
						),
						'submission_status' => array(
							'name' => 'submission_status',
							'id' => 'submission_public_status',
							'type' => 'select',
							'title' => esc_html__('Default Status', 'waymark'),
							'default' => Waymark_Config::get_setting('submission', 'from_public', 'submission_status'),
							'tip' => esc_attr__('This is the initial status of the submitted Map. Note! Publish means that the Map (including any images added) will be *immediately* visible on your site.', 'waymark'),
							'options' => array(
								'draft' => esc_attr__('Draft', 'waymark'),
								'publish' => esc_attr__('Publish (not recommended!)', 'waymark'),
							),
							'class' => '',
						),
						'submission_collection' => array(
							'name' => 'submission_collection',
							'id' => 'submission_public_collection',
							'type' => 'select',
							'title' => esc_html__('Default Collection', 'waymark'),
							'tip' => esc_attr__('If specified, user submissions will be automatically added to this Collection.', 'waymark'),
							'options' => $collection_array,
						),
// 						'submission_alert' => array(
// 							'name' => 'submission_alert',
// 							'id' => 'submission_public_alert',
// 							'type' => 'boolean',
// 							'title' => esc_html__('Email Alert', 'waymark'),
// 							'default' => Waymark_Config::get_setting('submission', 'from_public', 'submission_alert'),
// 							'tip' => esc_attr__('Receive email alerts for new submissions.', 'waymark'),
// 							'class' => 'waymark-hidden'
// 						),
					),
				),
			),
		);

		//Submissions not enabled
		if (!Waymark_Config::get_setting('submission', 'global', 'submission_enable')) {
			//Hide related inputs
			$this->tabs['meta']['sections']['inputs']['fields']['meta_submission']['class'] = ' waymark-hidden';

			$this->tabs['markers']['sections']['marker_types']['fields']['marker_submission']['class'] = 'waymark-hidden';
			$this->tabs['lines']['sections']['line_types']['fields']['line_submission']['class'] = 'waymark-hidden';
			$this->tabs['shapes']['sections']['shape_types']['fields']['shape_submission']['class'] = 'waymark-hidden';

			$this->tabs['submission']['sections']['from_users']['class'] = 'waymark-hidden';
			$this->tabs['submission']['sections']['from_public']['class'] = 'waymark-hidden';
			//If No public submissions
		} elseif (!Waymark_Config::get_setting('submission', 'from_public', 'submission_public')) {
			//Hide settings
			$this->tabs['submission']['sections']['from_public']['fields']['submission_features']['class'] = ' waymark-hidden';
			$this->tabs['submission']['sections']['from_public']['fields']['submission_status']['class'] = ' waymark-hidden';
// 			$this->tabs['submission']['sections']['from_public']['fields']['submission_alert']['class'] .= ' waymark-hidden';
			$this->tabs['submission']['sections']['from_public']['fields']['submission_upload_dir']['class'] = ' waymark-hidden';
			$this->tabs['submission']['sections']['from_public']['fields']['submission_collection']['class'] = ' waymark-hidden';
		}

		/**
		 * ===========================================
		 * ================== MISC ===================
		 * ===========================================
		 */

		$this->tabs['misc'] = array(
			'name' => esc_html__('Misc.', 'waymark'),
			'description' => '',
			'sections' => array(
				'map_options' => array(
					'title' => esc_html__('Map Options', 'waymark'),
					'description' => esc_html__('Use these options to change how Maps are displayed.', 'waymark'),
					'help' => array(
						'url' => esc_attr(Waymark_Helper::site_url('docs-cat/examples')),
						'text' => esc_attr__('See Examples &raquo;', 'waymark'),
					),
					'fields' => array(
						'map_default_latlng' => array(
							'name' => 'map_default_latlng',
							'id' => 'map_default_latlng',
							'type' => 'text',
							'class' => '',
							'title' => esc_html__('Default Centre', 'waymark'),
							'default' => Waymark_Config::get_setting('misc', 'map_options', 'map_default_latlng'),
							'tip' => esc_attr__('Waymark centres the Map automatically when displaying data. These coordinates (Latitude,Longitude) will be used when there is no data available.', 'waymark'),
							'input_processing' => array(
								'preg_replace("/[^0-9.,-]+/", "", $param_value);',
							),
							'output_processing' => array(
								sprintf('(! empty($param_value)) ? $param_value : "%s";', Waymark_Config::get_default('misc', 'map_options', 'map_default_latlng')),
							),
						),
						'map_height' => array(
							'name' => 'map_height',
							'id' => 'map_height',
							'type' => 'text',
							'class' => 'waymark-short-input waymark-align-top',
							'title' => esc_html__('Map Height', 'waymark'),
							'default' => Waymark_Config::get_setting('misc', 'map_options', 'map_height'),
							'append' => '<br />' . sprintf(esc_attr__('Or set in Shortcode: %s', 'waymark'), '<code>[' . Waymark_Config::get_item('shortcode') . ' map_height=&quot;' . Waymark_Config::get_setting('misc', 'map_options', 'map_height') . '&quot; map_width=&quot;320&quot;]</code>'),
							'tip' => sprintf(esc_attr__('Specify the desired height of the Map (in pixels). Pro Tip! This will affect all Maps, but you can change the height (and width) of an individual Map through the Shortcode: %s', 'waymark'), '[' . Waymark_Config::get_item('shortcode') . ' map_id=&quot;1234&quot; map_height=&quot;' . Waymark_Config::get_setting('misc', 'map_options', 'map_height') . '&quot;]'),
							'input_processing' => array(
								'preg_replace("/[^0-9]/", "", $param_value);',
							),
							'output_processing' => array(
								sprintf('(! empty($param_value)) ? $param_value : %d;', Waymark_Config::get_default('misc', 'map_options', 'map_height')),
							),
						),
						'map_default_zoom' => array(
							'name' => 'map_default_zoom',
							'id' => 'map_default_zoom',
							'type' => 'text',
							'class' => 'waymark-short-input',
							'title' => esc_html__('Default Zoom', 'waymark'),
							'default' => Waymark_Config::get_setting('misc', 'map_options', 'map_default_zoom'),
							'tip' => esc_attr__('Waymark zooms the Map automatically when displaying data. This zoom level (0-18) will be used when there is no data available.', 'waymark'),
							'input_processing' => array(
								'preg_replace("/[^0-9]/", "", $param_value);',
							),
							'output_processing' => array(
								sprintf('(! empty($param_value)) ? $param_value : "%d";', Waymark_Config::get_default('misc', 'map_options', 'map_default_zoom')),
							),
						),
						'show_gallery' => array(
							'name' => 'show_gallery',
							'id' => 'show_gallery',
							'type' => 'boolean',
							'title' => esc_html__('Image Gallery', 'waymark'),
							'default' => Waymark_Config::get_setting('misc', 'map_options', 'show_gallery'),
							'tip' => sprintf(esc_attr__('Whether to display an image gallery for Markers that have images associated with them. Pro Tip! This will affect all Maps, but you can choose to show/hide the gallery of an individual Map through the shortcode: %s', 'waymark'), '[' . Waymark_Config::get_item('shortcode') . ' show_gallery=&quot;1&quot;]'),
							'options' => array(
								'1' => esc_html__('Show', 'waymark'),
								'0' => esc_html__('Hide', 'waymark'),
							),
						),
						'show_filter' => array(
							'name' => 'show_filter',
							'id' => 'show_filter',
							'type' => 'boolean',
							'title' => esc_html__('Overlay Filter', 'waymark'),
							'default' => Waymark_Config::get_setting('misc', 'map_options', 'show_filter'),
							'tip' => sprintf(esc_attr__('Allow the user to filter which Markers, Lines and Shapes are currently visible on the Map. Pro Tip! This will affect all Maps, but you can choose to show/hide the filter for individual Maps through the shortcode: %s', 'waymark'), '[' . Waymark_Config::get_item('shortcode') . ' show_filter=&quot;1&quot;]'),
							'options' => array(
								'1' => esc_html__('Show', 'waymark'),
								'0' => esc_html__('Hide', 'waymark'),
							),
						),
						'allow_export' => array(
							'name' => 'allow_export',
							'id' => 'allow_export',
							'type' => 'boolean',
							'title' => esc_html__('Public Export', 'waymark'),
							'default' => Waymark_Config::get_setting('misc', 'map_options', 'allow_export'),
							'tip' => sprintf(esc_attr__('Offer visitors the ability to Download all Collection/Map Overlays in the Shortcode Header and on the Map Details page. GeoJSON, GPX and KML formats supported.', 'waymark')),
						),
						'show_scale' => array(
							'name' => 'show_scale',
							'id' => 'show_scale',
							'type' => 'boolean',
							'title' => esc_html__('Show Scale', 'waymark'),
							'default' => Waymark_Config::get_setting('misc', 'map_options', 'show_scale'),
							'tip' => sprintf(esc_attr__('Show a distance scale (km and miles) on the Map.', 'waymark')),
						),
					),
				),

				//Interaction

				//Sleep
				'interaction_options' => array(
					'title' => esc_html__('Sleep Options', 'waymark'),
					'description' => sprintf(__('Waymark Maps will zoom when the user scrolls. This can cause some unexpected/annoying behaviour when scrolling a page.<br /><br /><b>Sleeping</b> the Map initially and <b>Waking</b> upon user interaction (i.e. hovering/clicking/tapping) may create a better experience.', 'waymark'), Waymark_Config::get_default('misc', 'interaction_options', 'delay_seconds')),
					'fields' => array(
						'delay_seconds' => array(
							'name' => 'delay_seconds',
							'id' => 'delay_seconds',
							'type' => 'text',
							'class' => 'waymark-short-input',
							'title' => esc_html__('Hover Wake Time', 'waymark'),
							'append' => esc_html__('Seconds', 'waymark'),
							'default' => Waymark_Config::get_setting('misc', 'interaction_options', 'delay_seconds'),
							'tip' => esc_attr__('How many seconds before scroll zoom is enabled. 0 seconds will mean no delay (disabling this feature). A large number of seconds like 3600 (an hour) will esentially *disable hover to wake*, meaning the user will need to *click* to wake.', 'waymark'),
							'input_processing' => array(
								'(is_numeric($param_value)) ? $param_value : ' . Waymark_Config::get_default('misc', 'interaction_options', 'delay_seconds') . ';', //Fallback
							),
						),
						'do_message' => array(
							'name' => 'do_message',
							'id' => 'do_message',
							'type' => 'boolean',
							'title' => esc_html__('Display Message', 'waymark'),
							'default' => Waymark_Config::get_setting('misc', 'interaction_options', 'do_message'),
							'tip' => esc_attr__('This message will be displayed by the Map while scroll zoom is disabled.', 'waymark'),
						),
						'wake_message' => array(
							'name' => 'wake_message',
							'id' => 'wake_message',
							'type' => 'text',
							'title' => '<span class="waymark-invisible">' . esc_html__('Display', 'waymark') . ' </span> ' . esc_html__('Message Text', 'waymark'),
							'default' => Waymark_Config::get_setting('misc', 'interaction_options', 'wake_message'),
						),
					),
				),

				// Cluster

				'cluster_options' => array(
					'title' => esc_html__('Cluster Options', 'waymark'),
					'description' => sprintf(__('With Clustering enabled, Markers will be grouped together when they are close together. This can help to reduce clutter on the Map.', 'waymark'), Waymark_Config::get_default('misc', 'cluster_options', 'cluster_threshold')),
					'help' => array(
						'url' => esc_attr(Waymark_Helper::site_url('docs/marker-clustering')),
						'text' => esc_attr__('Clustering Docs &raquo;', 'waymark'),
					),
					'fields' => array(
						'show_cluster' => array(
							'name' => 'show_cluster',
							'id' => 'show_cluster',
							'type' => 'boolean',
							'options' => array(
								'1' => esc_html__('Enabled', 'waymark'),
								'0' => esc_html__('Disabled', 'waymark'),
							),

							'title' => esc_html__('Marker Clustering', 'waymark'),
							'default' => Waymark_Config::get_setting('misc', 'cluster_options', 'show_cluster'),
							'tip' => esc_attr__('Whether to cluster (stack) Markers that are close together. Pro Tip! This will affect all Maps, but you can also enable/disable clustering through the Shortcode: [Waymark show_cluster="0"]', 'waymark'),
						),
						'cluster_threshold' => array(
							'name' => 'cluster_threshold',
							'id' => 'cluster_threshold',
							'type' => 'text',
							'class' => 'waymark-short-input',
							'title' => esc_html__('Cluster Threshold', 'waymark'),
							'default' => Waymark_Config::get_setting('misc', 'cluster_options', 'cluster_threshold'),
							'tip' => esc_attr__('Markers will not be clustered above this zoom level.', 'waymark'),
							'input_processing' => array(
								'(is_numeric($param_value)) ? $param_value : ' . Waymark_Config::get_default('misc', 'cluster_options', 'cluster_threshold') . ';', //Fallback
							),
						),
						'cluster_radius' => array(
							'name' => 'cluster_radius',
							'id' => 'cluster_radius',
							'type' => 'text',
							'class' => 'waymark-short-input',
							'title' => '<span class="waymark-invisible">' . esc_html__('Cluster', 'waymark') . ' </span> ' . esc_html__('Radius', 'waymark'),
							'default' => Waymark_Config::get_setting('misc', 'cluster_options', 'cluster_radius'),
							'tip' => esc_attr__('The maximum radius that a cluster will cover from the central marker (in pixels). Decreasing will make more, smaller clusters.	Default 80.', 'waymark'),

							'input_processing' => array(
								'(is_numeric($param_value)) ? $param_value : ' . Waymark_Config::get_default('misc', 'cluster_options', 'cluster_radius') . ';', //Fallback
							),
						),
					),
				),

				//Shortcode

				'shortcode_options' => array(
					'title' => esc_html__('Shortcode Options', 'waymark'),
					'description' => esc_html__('How Maps are embedded into your content using the shortcode.', 'waymark'),
					'help' => array(
						'url' => esc_attr(Waymark_Helper::site_url('docs/shortcodes')),
						'text' => esc_attr__('Shortcode Docs &raquo;', 'waymark'),
					),
					'fields' => array(
						'shortcode_header' => array(
							'name' => 'shortcode_header',
							'id' => 'shortcode_header',
							'type' => 'select',
							'title' => esc_html__('Shortcode Header', 'waymark'),
							'default' => Waymark_Config::get_setting('misc', 'shortcode_options', 'shortcode_header'),
							'tip' => sprintf(esc_attr__('The shortcode header displays the title and link to the Map or Collection. Pro Tip! This will affect all shortcodes, but you can override the setting through the shortcode: %s (the value must be either 0 or 1).', 'waymark'), '[' . Waymark_Config::get_item('shortcode') . ' map_id=&quot;1234&quot; shortcode_header=&quot;' . Waymark_Config::get_setting('misc', 'shortcode_options', 'shortcode_header') . '&quot;]'),
							'options' => array(
								'1' => esc_html__('Show', 'waymark'),
								'0' => esc_html__('Hide', 'waymark'),
							),
						),
						'header_override' => array(
							'name' => 'header_override',
							'id' => 'header_override',
							'type' => 'select',
							'title' => esc_html__('Header for Admin', 'waymark'),
							'default' => Waymark_Config::get_setting('misc', 'shortcode_options', 'header_override'),
							'tip' => esc_attr__('Use this Setting to always show the Shortcode Header when signed in as admin, useful for quickly navigating to embeded Maps.', 'waymark'),
							'options' => array(
								'0' => esc_html__('Use Setting', 'waymark'),
								'1' => esc_html__('Always Show', 'waymark'),
							),
						),
					),
				),

				//Elevation

				'elevation_options' => array(
					'title' => esc_html__('Elevation Options', 'waymark'),
					'description' => esc_html__('Lines with elevation data.', 'waymark'),
					'help' => array(
						'url' => esc_attr(Waymark_Helper::site_url('docs/elevation-profile-colours')),
						'text' => esc_attr__('Elevation Styling &raquo;', 'waymark'),
					),
					'fields' => array(
						'show_elevation' => array(
							'name' => 'show_elevation',
							'id' => 'show_elevation',
							'type' => 'select',
							'title' => esc_html__('Elevation Profile', 'waymark'),
							'default' => Waymark_Config::get_setting('misc', 'elevation_options', 'show_elevation'),
							'tip' => sprintf(esc_attr__('Display an interactive elevation profile graph below the Map for Lines that have elevation data. Pro Tip! You can choose to show/hide the elevation graph of an individual Map through the shortcode: %s', 'waymark'), '[' . Waymark_Config::get_item('shortcode') . ' map_id=&quot;1234&quot; show_elevation=&quot;1&quot;]'),
							'options' => array(
								'2' => esc_html__('Show on Map Details', 'waymark'),
								'1' => esc_html__('Show everywhere', 'waymark'),
								'0' => esc_html__('Hide everywhere', 'waymark'),
							),
						),
						'elevation_units' => array(
							'name' => 'elevation_units',
							'id' => 'elevation_units',
							'type' => 'select',
							'title' => esc_html__('Elevation Units', 'waymark'),
							'default' => Waymark_Config::get_setting('misc', 'elevation_options', 'elevation_units'),
							'tip' => sprintf(esc_attr__('Display elevation data in metric (m/km) or imperial (ft/mi) units.', 'waymark')),
							'options' => array(
								'metric' => esc_html__('Metric (m/km)', 'waymark'),
								'imperial' => esc_html__('Imperial (ft/mi)', 'waymark'),
							),
						),
						'elevation_colour' => array(
							'name' => 'elevation_colour',
							'id' => 'elevation_colour',
							'type' => 'text',
							'class' => 'waymark-short-input waymark-colour-picker',
							'title' => esc_html__('Elevation Colour', 'waymark'),
							'default' => Waymark_Config::get_setting('misc', 'elevation_options', 'elevation_colour'),
							'tip' => sprintf(esc_attr__('The colour of the elevation graph and associated Line.', 'waymark')),
							'input_processing' => array(
								'(! empty($param_value)) ? $param_value : "#b42714";', //Fallback
							),
						),
						'elevation_initial' => array(
							'name' => 'elevation_initial',
							'id' => 'elevation_initial',
							'type' => 'boolean',
							'title' => esc_html__('Show Initially?', 'waymark'),
							'default' => Waymark_Config::get_setting('misc', 'elevation_options', 'elevation_initial'),
							'tip' => sprintf(esc_attr__('Whether to show the elevation profile when the Map loads. If set to No, the user must click on a Line in order to display the elevation data. If there are multiple Lines with elevation data, the one added to the editor first will be the one shown initially.', 'waymark')),
						),
					),
				),

				/**
				 * ===========================================
				 * ================= ADVANCED ================
				 * ===========================================
				 */

				//Collections

				'collection_options' => array(
					'title' => esc_html__('Collection Options', 'waymark'),
					'description' => esc_html__('How Collections are displayed.', 'waymark'),
					'help' => array(
						'url' => esc_attr(Waymark_Helper::site_url('docs/collections')),
						'text' => esc_attr__('Collection Docs &raquo;', 'waymark'),
					),
					'fields' => array(
						'load_method' => array(
							'name' => 'load_method',
							'id' => 'load_method',
							'type' => 'select',
							'options' => [
								'fetch' => esc_html__('Background', 'waymark'),
								'embed' => esc_html__('Embed', 'waymark'),
							],
							'class' => 'waymark-short-input',
							'title' => esc_html__('Shortcode Method', 'waymark'),
							'default' => Waymark_Config::get_setting('misc', 'collection_options', 'load_method'),
							'tip' => esc_attr__('Whether to load multiple Maps in the Background (uses AJAX to improve page load) when embedding with the Shortcode, or to Embed them within the page. Embedding may be a bad idea for LARGE COLLECTIONS, but can resolve some issues where Collections are not displaying correctly.', 'waymark'),
						),
					),
				),

				//Editor

				'editor_options' => array(
					'title' => esc_html__('Editor Options', 'waymark'),
					'description' => esc_html__('Customising the Map Editor.', 'waymark'),
					'fields' => array(
						'confirm_delete' => array(
							'name' => 'confirm_delete',
							'id' => 'confirm_delete',
							'type' => 'boolean',
							'title' => esc_html__('Confirm Delete?', 'waymark'),
							'default' => Waymark_Config::get_setting('misc', 'editor_options', 'confirm_delete'),
							'tip' => esc_attr__('Whether to show a confirmation dialog before deleting Markers/Lines/Shapes from the Map. Pro Tip! Even if you accidentally delete something, changes are not saved until the "Update" button is clicked.', 'waymark'),
						),
						'editor_basemap' => array(
							'name' => 'editor_basemap',
							'id' => 'editor_basemap',
							'type' => 'select',
							'title' => esc_html__('Default Basemap', 'waymark'),
							'default' => Waymark_Config::get_setting('misc', 'editor_options', 'editor_basemap'),
							'tip' => esc_attr__('Which Basemap to use as the editor default.', 'waymark'),
							'options' => $basemap_options,
						),
					),
				),

				//Permalinks

				'permalinks' => array(
					'title' => esc_html__('Permalinks', 'waymark'),
					'description' => 'Customise your Map and Collection URLs.',
					'footer' => '<small>For the changes to take affect you must rebuild your Permalinks by going to WP Settings > Permalinks and clicking "Save Changes".</small>',
					'fields' => array(
						'permalink_slug_map' => array(
							'name' => 'permalink_slug_map',
							'id' => 'permalink_slug_map',
							'type' => 'text',
							'class' => 'waymark-short-input',
							'title' => esc_html__('Map Slug', 'waymark'),
							'default' => Waymark_Config::get_setting('misc', 'permalinks', 'permalink_slug_map'),
							'tip' => esc_attr__('The URL slug that will be used for links to your Maps, i.e. example.com/[map-slug]/example-map/. Only alpha-numeric characters and hyphens (-) are allowed.', 'waymark'),
							'input_processing' => array(
								'preg_replace("/[^0-9a-z-]+/", "", $param_value);',
							),
							'prepend' => '<small>/</small>',
							'append' => '<small>/map-name/</small>',
						),
						'permalink_slug_collection' => array(
							'name' => 'permalink_slug_collection',
							'id' => 'permalink_slug_collection',
							'type' => 'text',
							'class' => 'waymark-short-input',
							'title' => esc_html__('Collection Slug', 'waymark'),
							'default' => Waymark_Config::get_setting('misc', 'permalinks', 'permalink_slug_collection'),
							'tip' => esc_attr__('The URL slug that will be used for links to your Collections, i.e. example.com/[collection-slug]/example-collection/. Only alpha-numeric characters and hyphens (-) are allowed.', 'waymark'),
							'input_processing' => array(
								'preg_replace("/[^0-9a-z-]+/", "", $param_value);',
							),
							'prepend' => '<small>/</small>',
							'append' => '<small>/collection-name/</small>',
						),
					),
				),

				// Custom Post Type Supports

				'post' => [
					'title' => esc_html__('Post Type', 'waymark'),
					'description' => 'Enable Custom Post Type features.',
					'fields' => [
						'supports' => [
							'name' => 'supports',
							'id' => 'supports',
							'type' => 'select_multi',
							'title' => esc_html__('Supports', 'waymark'),
							'default' => Waymark_Config::get_setting('misc', 'post', 'supports'),
							'tip' => esc_attr__('Enable or disable various WordPress features of the Map Custom Post Type.', 'waymark'),
							'tip_link' => 'https://developer.wordpress.org/reference/functions/add_post_type_support/',
							'options' => [
								'title' => esc_html__('Title', 'waymark'),
								'author' => esc_html__('Author', 'waymark'),
								'revisions' => esc_html__('Revisions', 'waymark'),
								'thumbnail' => esc_html__('Thumbnail', 'waymark'),
								'comments' => esc_html__('Comments', 'waymark'),
								'excerpt' => esc_html__('Excerpt', 'waymark'),
							],
						],
					],
				],

				//Debug

				'advanced' => array(
					'title' => esc_html__('Debug', 'waymark'),
					'description' => '',
					'fields' => array(
						'debug_mode' => array(
							'name' => 'debug_mode',
							'id' => 'debug_mode',
							'type' => 'boolean',
							'title' => esc_html__('Debug Mode', 'waymark'),
							'default' => Waymark_Config::get_setting('misc', 'advanced', 'debug_mode'),
							'tip' => esc_attr__('With debug mode enabled, the plugin will output Map and Settings data in Admin Dashboard. This may come in handy if you need to report a bug. Pro Tip! Check the browser console for Waymark output when signed in as an administrator.', 'waymark'),
							'tip_link' => 'https://www.waymark.dev/docs/debug-mode/',
							'options' => array(
								'0' => esc_html__('Disable', 'waymark'),
								'1' => esc_html__('Enable', 'waymark'),
							),
						),
					),
				),
			),
		);

		//Debug?
		if (Waymark_Helper::is_debug()) {
			$this->tabs['misc']['sections']['advanced']['fields']['settings_output'] = [
				'name' => 'settings_output',
				'id' => 'settings_output',
				'type' => 'textarea',
				'class' => 'waymark-align-top',
				'title' => esc_html__('Settings Data', 'waymark'),
				'default' => serialize(get_option('Waymark_Settings')),
				//Don't save to DB
				'input_processing' => array(
					'null',
				),
				//Don't allow editing
				'output_processing' => array(
					'serialize(get_option("Waymark_Settings"))',
				),
			];
		}

		add_action('admin_notices', array($this, 'admin_notices'));
		add_action('admin_init', array($this, 'register_settings'));
	}

	function get_settings() {
		return $this->current_settings;
	}

	function register_settings() {
		register_setting($this->page_slug, 'Waymark_Settings', array($this, 'sanitize_callback'));

		//For each tab
		foreach ($this->tabs as $tab_key => $tab_data) {
			//For each section
			foreach ($tab_data['sections'] as $section_key => $section_data) {
				//Set if blank if unset
				$section_data['title'] = (isset($section_data['title'])) ? $section_data['title'] : '';

				//Create section
				add_settings_section($section_key, $section_data['title'], array($this, 'section_text'), $this->page_slug);

				//For each field in section
				if (is_array($section_data['fields']) && sizeof($section_data['fields'])) {
					foreach ($section_data['fields'] as $field) {
						//Get set_value
						if (array_key_exists($tab_key, $this->current_settings) && array_key_exists($section_key, $this->current_settings[$tab_key])) {
							if (array_key_exists($field['name'], $this->current_settings[$tab_key][$section_key])) {
								$field['set_value'] = $this->current_settings[$tab_key][$section_key][$field['name']];
							}
						}

						//Modify name for multi-dimensional array
						$field['name'] = 'Waymark_Settings[' . $tab_key . '][' . $section_key . '][' . $field['name'] . ']';

						//Repeatable section
						if (isset($section_data['repeatable']) && $section_data['repeatable']) {
							//Get count
							$repeatable_count = Waymark_Helper::get_section_repeatable_count($section_data);

							//Must be an array
							if (!is_array($field['default'])) {
								//Make array
								$field['default'] = Waymark_Helper::convert_single_value_to_array($field['default']);
							}

							//Array size must match
							if (sizeof($field['default']) < $repeatable_count) {
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
		if (array_key_exists('set_value', $field)) {
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
		foreach ($this->tabs as $tab_key => $tab_data) {
			//If we have sections
			if (array_key_exists('sections', $tab_data)) {
				//Iterate over each section
				foreach ($tab_data['sections'] as $section_key => $section_data) {
					//If section has fields
					if (array_key_exists('fields', $section_data)) {
						//For each field
						foreach ($section_data['fields'] as $field_key => $field_definition) {
							//If this field was submitted
							if (isset($input_data[$tab_key][$section_key][$field_definition['name']])) {
								$value = $input_data[$tab_key][$section_key][$field_definition['name']];

								//If no input processing specified
								if (!array_key_exists('input_processing', $field_definition)) {
									//Make safe by default
									$field_definition['input_processing'][] = 'htmlspecialchars($param_value)';
								}

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
		echo '<div id="waymark-admin-container">' . "\n";

		echo Waymark_Helper::plugin_about();

		echo '	<div class="card">' . "\n";
// 		echo '		<h1>' . esc_html__('Settings', 'waymark') . '</h1>' . "\n";

		//Tabs
		$active_content = (isset($_GET['content'])) ? $_GET['content'] : $this->default_content;
		$this->settings_nav($active_content);

		//Open form
		echo '		<form action="' . admin_url('options.php') . '" method="post">' . "\n";
		settings_fields($this->page_slug);

		//For each tab
		foreach ($this->tabs as $tab_key => $tab_data) {
			$style = '';
// 			if($active_tab != $tab_key) {
// 				$style = ' style="display:none;"';
// 			}
			echo '	<div class="waymark-settings-tab waymark-settings-tab-' . $tab_key . '"' . $style . '>' . "\n";

			//Tab description?
			if (array_key_exists('description', $tab_data)) {
				echo '	<div class="waymark-settings-tab-description">' . $tab_data['description'] . '</div>' . "\n";
			}

			//For each section
			foreach ($tab_data['sections'] as $section_key => $section_data) {
				$class = (isset($section_data['class'])) ? ' ' . $section_data['class'] : '';
				echo '		<div class="waymark-settings-section waymark-settings-section-' . $section_key . $class . '">' . "\n";

				//Help
				if (array_key_exists('help', $section_data) && isset($section_data['help']['url'])) {
					$help_text = (isset($section_data['help']['text'])) ? $section_data['help']['text'] : 'View Help &raquo;';

					echo '		<a class="waymark-docs-link button" href="' . $section_data['help']['url'] . '" target="_blank">' . $help_text . '</a>' . "\n";
				}

				//Title
				if (isset($section_data['title'])) {
					echo '		<h2>' . $section_data['title'] . '</h2>' . "\n";
				}

				//Description
				if (array_key_exists('description', $section_data)) {
					echo '		<div class="waymark-settings-section-description">' . $section_data['description'] . '</div>' . "\n";
				}

				//Repeatable?
				if (array_key_exists('repeatable', $section_data) && $section_data['repeatable']) {
					echo '<div class="waymark-repeatable" data-count="0">' . "\n";
				}

				echo '		<table class="form-table">' . "\n";
				do_settings_fields($this->page_slug, $section_key);
				echo '		</table>' . "\n";

				//Repeatable?
				if (array_key_exists('repeatable', $section_data) && $section_data['repeatable']) {
					echo '</div>' . "\n";
				}

				//Footer
				if (array_key_exists('footer', $section_data)) {
					echo '	<div class="waymark-settings-section-footer">' . $section_data['footer'] . '</div>' . "\n";
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

	function settings_nav($current = 'tiles') {
		echo '<div id="waymark-settings-nav" data-init_tab_key="' . $current . '">' . "\n";
		echo '	<select>' . "\n";

		foreach ($this->settings_nav as $content_id => $content_title) {
			if (strpos($content_id, 'label') === 0) {
				echo '	<option disabled="disabled">' . $content_title . '</option>' . "\n";
			} else {
				echo '	<option value="' . $content_id . '"' . (($current == $content_id) ? ' selected="selected"' : '') . '>' . $content_title . '</option>' . "\n";
			}
		}

		echo '	</select>' . "\n";
		echo '</div>' . "\n";
	}

	function execute_action($action) {
		switch ($action) {
		//Clear cache
		case 'clear_cache':
			Waymark_Cache::flush();

			break;
		}

		wp_redirect(admin_url('admin.php?page=waymark-settings&tab=advanced&settings-updated=waymark_action'));

		die;
	}

	function admin_notices() {
		if (isset($_GET['settings-updated'])) {
			//Settings updates
			if ($_GET['settings-updated'] == 'true') {
				echo '<div class="waymark-notice notice notice-success is-dismissible"><p>' . esc_html__('Settings Updated', 'waymark') . '.</p></div>';
				//Action
			} elseif ($_GET['settings-updated'] == 'waymark_action') {
				echo '<div class="waymark-notice notice notice-success is-dismissible"><p>' . esc_html__('Action Complete', 'waymark') . '.</p></div>';
			}
		}
	}
}
