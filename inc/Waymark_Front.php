<?php

class Waymark_Front {
	function __construct() {
		//Don't do anything if we're in the back-end
		if(is_admin()) {
			return;
		}
		
		add_action('init', array($this, 'init'));
		add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));						
		add_action('wp_head', array($this, 'wp_head'));			
	}
	
	function init() {
		require_once('Front/Waymark_JS.php');
		require_once('Front/Waymark_CSS.php');
		require_once('Front/Waymark_Shortcode.php');
		require_once('Front/Waymark_Content.php');
		require_once('Front/Waymark_HTTP.php');
		
		//CSS
		Waymark_CSS::add_chunk('
div.waymark-container .waymark-map .elevation-polyline { stroke: ' . Waymark_Config::get_setting('misc', 'elevation_options', 'elevation_colour') . '; }
div.waymark-container .waymark-elevation .elevation-control.elevation .area { fill: ' . Waymark_Config::get_setting('misc', 'elevation_options', 'elevation_colour') . ';	}
');
			
		//JS
		Waymark_JS::add_chunk('
function waymark_load_map_data(map_instance, map_id, link_to_map = false) {
	//Build request
	var data = {
		"waymark_action": "get_map_data",
		"waymark_security": waymark_http_security,
		"map_id": map_id,
		"link_to_map": link_to_map === true
	};

	//Do request
	jQuery.post(waymark_http_endpoint, data, function(map_data) {	
		map_instance.load_json(map_data);
	});
}');
			
		$this->add_footer_js();
	}

	function enqueue_scripts() {
		// === JS ===
	
		wp_register_style('waymark-js', Waymark_Helper::asset_url('dist/waymark-js/css/waymark-js.min.css'), array(), Waymark_Config::get_version());
		wp_enqueue_style('waymark-js');		
		wp_register_script('waymark-js', Waymark_Helper::asset_url('dist/waymark-js/js/waymark-js.min.js'), array('jquery'), Waymark_Config::get_version(), true);		
		wp_localize_script('waymark-js', 'waymark_js_lang', array(
			//Viewer
			'action_fullscreen_activate' => esc_attr__('View Fullscreen', 'waymark-plugin'),		
			'action_fullscreen_deactivate' => esc_attr__('Exit Fullscreen', 'waymark-plugin'),		
			'action_locate_activate' => esc_attr__('Show me where I am', 'waymark-plugin'),		
			'action_zoom_in' => esc_attr__('Zoom in', 'waymark-plugin'),		
			'action_zoom_out' => esc_attr__('Zoom out', 'waymark-plugin'),
			'label_total_length' => esc_attr__('Total Length: ', 'waymark-plugin'),
			'label_max_elevation' => esc_attr__('Max. Elevation: ', 'waymark-plugin'),
			'label_min_elevation' => esc_attr__('Min. Elevation: ', 'waymark-plugin'),
		));
		wp_enqueue_script('waymark-js');								
			
		wp_register_script('waymark_shared_js', Waymark_Helper::asset_url('shared/js/shared.js'), array('jquery'), Waymark_Config::get_version(), true);
		wp_enqueue_script('waymark_shared_js');			

		// === CSS ===
		
		wp_register_style('waymark_shared_css', Waymark_Helper::asset_url('shared/css/shared.css'), array(), Waymark_Config::get_version());
		wp_enqueue_style('waymark_shared_css');		
		wp_register_style('waymark_front_css', Waymark_Helper::asset_url('front/css/front.css'), array(), Waymark_Config::get_version());
		wp_enqueue_style('waymark_front_css');		

		//Output JS
		//Waymark_JS::add_chunk("\n" . '//Front');		
 		Waymark_JS::add_chunk("\n" . 'var waymark_user_config = ' . json_encode(Waymark_Config::get_map_config()));						
	}
	
	function wp_head() {
		echo '<meta name="' . Waymark_Config::get_name(true, true) . ' Version" content="' . Waymark_Config::get_version() . '" />' . "\n";	
	}
	
	function add_footer_js() {
		//Waymark_JS::add_chunk("\n" . '//Front');
	}
}	
new Waymark_Front;
