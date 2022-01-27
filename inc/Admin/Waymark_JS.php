<?php

class Waymark_JS {
	
	static private $chunks = array();
	static private $calls = array();
	
	static function init() {
		self::add_chunk('//' . Waymark_Config::get_name(true, true) . ' v' . Waymark_Config::get_version());
		
		add_action('admin_footer', array('Waymark_JS', 'admin_footer'));					
		add_action('admin_enqueue_scripts', array('Waymark_JS', 'enqueue_scripts'));										
	}
	
	static function enqueue_scripts() {	
		global $current_screen;
		
		if(is_object($current_screen)) {
			switch($current_screen->id) {
				//Map Editor
				case 'waymark_map' :		
				//Query Editor
				case 'edit-waymark_query' :
				//Settings
				case 'waymark_page_waymark-settings' :
					wp_register_script('waymark_admin_js', Waymark_Helper::asset_url('js/admin.min.js'), array('jquery', 'jquery-ui-sortable', 'jquery-effects-core', 'wp-color-picker'), Waymark_Config::get_version());	
					wp_localize_script('waymark_admin_js', 'waymark_admin_js', array(
						'ajaxurl' => admin_url('admin-ajax.php'),
						'security' => wp_create_nonce(Waymark_Config::get_item('nonce_string')),
						'lang' => [
							'repeatable_delete_title' => esc_attr__('Remove!', 'waymark'),
							'marker_icon_icon_label' => esc_attr__('Name', 'waymark'),
							'marker_icon_text_label' => esc_attr__('Text', 'waymark'),
							'marker_icon_html_label' => esc_attr__('HTML', 'waymark'),						
						]
					));
					wp_enqueue_script('waymark_admin_js');	
		
					break;
			}
		}
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