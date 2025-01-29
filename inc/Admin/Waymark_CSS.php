<?php

class Waymark_CSS {

	static private $chunks = [];

	static function init() {
		self::add_chunk('/* ' . Waymark_Config::get_name(true, true) . ' v' . Waymark_Config::get_version() . ' */');

		add_action('admin_head', ['Waymark_CSS', 'admin_head']);
		add_action('admin_enqueue_scripts', ['Waymark_CSS', 'enqueue_scripts']);
	}

	static function enqueue_scripts() {
		//CSS
		wp_register_style('waymark_admin_css', Waymark_Helper::asset_url('css/admin.min.css'), [], Waymark_Config::get_version());
		wp_enqueue_style('waymark_admin_css');
	}

	function menu_init() {
		require_once 'Admin/Waymark_Settings.php';
		require_once 'Admin/Waymark_CSS.php';
		require_once 'Admin/Waymark_Menu.php';
	}

	static function add_chunk($chunk) {
		self::$chunks[] = $chunk . "\n";
	}

	static function admin_head() {
		echo "\n" . '<!-- START ' . esc_html(Waymark_Config::get_name(true, true)) . ' Head CSS -->' . "\n";
		echo '<style type="text/css">' . "\n";

		foreach (self::$chunks as $chunk) {
			echo esc_html($chunk) . "\n";
		}

		echo '</style>' . "\n";
		echo '<!-- END ' . esc_html(Waymark_Config::get_name(true, true)) . ' Head CSS -->' . "\n\n";
	}

	static function http_render() {
		header('Content-Type: text/css');

		foreach (self::$chunks as $chunk) {
			echo esc_html($chunk) . "\n";
		}

		die;
	}
}

Waymark_CSS::init();