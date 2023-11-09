<?php

class Waymark_JS {
	
	static private $chunks = array();
	static private $calls = array();
	
	static function init() {
		add_action('wp_footer', array('Waymark_JS', 'wp_footer'));					
		add_action('wp_enqueue_scripts', array('Waymark_JS', 'enqueue_scripts'));							
	}

	static function enqueue_scripts() {
		wp_register_style('waymark-js', Waymark_Helper::asset_url('dist/waymark-js/css/waymark-js.min.css'), array(), Waymark_Config::get_version());
		wp_enqueue_style('waymark-js');		
		wp_register_script('waymark-js', Waymark_Helper::asset_url('dist/waymark-js/js/waymark-js.min.js'), array('jquery'), Waymark_Config::get_version(), true);		
		wp_localize_script('waymark-js', 'waymark_js', array(
			//AJAX
			'ajaxurl' => admin_url('admin-ajax.php'),
			'lang' => Waymark_Lang::get_js_lang()
		));
		wp_enqueue_script('waymark-js');								
			
		wp_register_script('waymark_front_js', Waymark_Helper::asset_url('js/front.min.js'), array('jquery'), Waymark_Config::get_version(), true);
		wp_enqueue_script('waymark_front_js');			

		//Config
 		self::add_chunk("\n" . 'var waymark_user_config = ' . json_encode(Waymark_Config::get_map_config()));						

		//AJAX
		if('fetch' === Waymark_Config::get_setting('misc', 'collection_options', 'load_method')) {
			self::add_chunk('
function waymark_load_map_data(map_instance, map_id, link_to_map = false) {
	//Build request
	var data = {
		"waymark_action": "get_map_data",
		"waymark_security": "' . wp_create_nonce(Waymark_Config::get_item('nonce_string')) . '",
		"map_id": map_id,
		"link_to_map": link_to_map === true
	};

	//Do request
	jQuery.post(waymark_http_endpoint, data, function(map_data) {	
		map_instance.load_json(map_data);
	});
}');
		}
	}

	static function add_chunk($chunk) {	
//		Waymark_Helper::debug(strpos($chunk, '//'));
		
		if((! in_array($chunk[strlen($chunk)-1], array(';', "\n")) && (strpos($chunk, '//') === false))) {
			$chunk .= ';';
		}
		self::$chunks[] = $chunk;
	}

	static function add_call($call) {	
		if(! in_array($call, self::$calls)) {
			self::$calls[] = $call;			
		}
	}

	static function wp_footer() {
		echo "\n" . '<!-- START ' . Waymark_Config::get_name(true, true) . ' Footer JS -->' . "\n";
		echo '<script type="text/javascript">' . "\n";
		//Lines
		foreach(self::$chunks as $chunk) {
			 echo $chunk . "\n";
		}
		
		//Calls
		if(sizeof(self::$calls)) {
			echo "\n//Call";
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