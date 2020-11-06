<?php

class Waymark_CSS {
	
	static private $chunks = array();
	
	static function init() {
		self::add_chunk('/* ' . Waymark_Config::get_name(true, true) . ' v' . Waymark_Config::get_version() . ' */');
		
		add_action('admin_head', array('Waymark_CSS', 'admin_head'));		
	}
	
	static function add_chunk($chunk) {	
		self::$chunks[] = $chunk . "\n";
	}
	
	static function admin_head() {
		echo "\n" . '<!-- START ' . Waymark_Config::get_name(true, true) . ' Head CSS -->' . "\n";
		echo '<style type="text/css">' . "\n";

		foreach(self::$chunks as $chunk) {
			 echo $chunk . "\n";
		}

		echo '</style>' . "\n";
		echo '<!-- END ' . Waymark_Config::get_name(true, true) . ' Head CSS -->' . "\n\n";			
	}	

	static function http_render() {
		header('Content-Type: text/css');
		
		foreach(self::$chunks as $chunk) {
			 echo $chunk . "\n";
		}

		die;		
	}	
}

Waymark_CSS::init();