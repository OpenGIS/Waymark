<?php

class Waymark_JS {
	
	static private $chunks = array();
	static private $calls = array();
	
	static function init() {
		self::add_chunk('//' . Waymark_Config::get_name(true, true) . ' v' . Waymark_Config::get_version());
		
		add_action('admin_footer', array('Waymark_JS', 'admin_footer'));					
	}
	
	static function add_chunk($chunk) {	
		if($chunk[strlen($chunk)-1] != ';') {
			$chunk .= ';';
		}
		self::$chunks[] = $chunk;
	}

	static function add_call($call) {	
		if(! in_array($call, self::$calls)) {
			self::$calls[] = $call;			
		}
	}

	static function admin_footer() {
		echo "\n" . '<!-- START ' . Waymark_Config::get_name(true, true) . ' Footer JS -->' . "\n";
		echo '<script type="text/javascript">' . "\n";
		//Lines
		foreach(self::$chunks as $chunk) {
			 echo $chunk . "\n";
		}
		
		//Calls
		if(sizeof(self::$calls)) {
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