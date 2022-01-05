<?php

class Waymark_Cache {
	private static $cache_prefix = 'Waymark_Cache_';
	
	static function set_item($cache_id, $cache_content, $cache_minutes = 0) {
		$cache_seconds = $cache_minutes * 60;
		
		set_transient(self::$cache_prefix . $cache_id, $cache_content, $cache_seconds);						
	}
	
	static function get_item($cache_id) {
		return get_transient(self::$cache_prefix . $cache_id);
	}

	static function do_hash($string) {	
		return md5($string);
	}
	
	static function flush() {
		global $wpdb;
		
		$wpdb->query("DELETE FROM " . $wpdb->options . " WHERE option_name LIKE '_transient_%" . self::$cache_prefix . "%'");
	}
}