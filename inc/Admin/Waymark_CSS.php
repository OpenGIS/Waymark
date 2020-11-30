<?php

class Waymark_CSS {
	
	static private $chunks = array();
	
	static function init() {
		self::add_chunk('/* ' . Waymark_Config::get_name(true, true) . ' v' . Waymark_Config::get_version() . ' */');
		
		add_action('admin_head', array('Waymark_CSS', 'admin_head'));		
		add_action('admin_enqueue_scripts', array('Waymark_CSS', 'enqueue_scripts'));								
	}

	static function enqueue_scripts() {
		//CSS
		wp_register_style('waymark_admin_css', Waymark_Helper::asset_url('css/admin.min.css'), array(), Waymark_Config::get_version());
		wp_enqueue_style('waymark_admin_css');	
	}
	
	function menu_init() {
		require_once('Admin/Waymark_Settings.php');		
		require_once('Admin/Waymark_CSS.php');					
		require_once('Admin/Waymark_Menu.php');	
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