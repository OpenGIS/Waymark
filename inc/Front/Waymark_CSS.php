<?php

class Waymark_CSS {
	
	static private $chunks = array();
	
	static function init() {
		//Settings > Appearance > CSS?
		if($settings_css = Waymark_Config::get_setting('appearance', 'css', 'css')) {
			self::add_chunk($settings_css);			
		}
		
		//add_action('wp_enqueue_scripts', array('Waymark_CSS', 'enqueue_styles'));		
		add_action('wp_head', array('Waymark_CSS', 'wp_head'));		
		
		//Waymark_JS::add_chunk('//CSS');													
	}

	static function enqueue_styles() {
		wp_register_style('waymark_front_css', Waymark_Helper::asset_url('front/css/front.css'), array(), Waymark_Config::get_version());
		wp_enqueue_style('waymark_front_css');	
	}	

	static function add_chunk($chunk) {	
		self::$chunks[] = $chunk . "\n";
	}
	
	static function wp_head() {
		if(! sizeof(self::$chunks)) {
			return;
		}
		
		echo "\n" . '<!-- START ' . Waymark_Config::get_name(true, true) . ' Head CSS -->' . "\n";
		echo '<style type="text/css">' . "\n";

		foreach(self::$chunks as $chunk) {
			 echo $chunk;
		}

		echo '</style>' . "\n";
		echo '<!-- END ' . Waymark_Config::get_name(true, true) . ' Head CSS -->' . "\n\n";			
	}	
}

Waymark_CSS::init();