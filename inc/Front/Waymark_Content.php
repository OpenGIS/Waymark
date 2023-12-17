<?php

class Waymark_Content {
	function __construct() {
		add_filter('the_content', array($this, 'the_content'));
		add_filter('the_excerpt', array($this, 'the_content'));
	}

	function the_content($content) {
		global $post;

		//Don't do anything if password required
		if (post_password_required()) {
			return $content;
		}

		//Ensure we have a post object and post type
		if (is_null($post) || !isset($post->post_type)) {
			return $content;
		}

		//Only modify Map page
		if ($post->post_type != 'waymark_map') {
			return $content;
		}

		//Map
		$Map = new Waymark_Map($post->ID);
		$shortcode = '[' . Waymark_Config::get_item('shortcode');
		$shortcode .= ' map_id="' . $post->ID . '"';

		//Shortcode Header?
		if (Waymark_Config::get_setting('misc', 'shortcode_options', 'shortcode_header') == '1') {
			$shortcode .= ' shortcode_header="1"';
		}

		//Elevation?
		if (Waymark_Config::get_setting('misc', 'elevation_options', 'show_elevation') == '2') {
			$shortcode .= ' show_elevation="1"';
		}

		// Cluster
		if (Waymark_Config::get_setting('misc', 'cluster_options', 'show_cluster') == '1') {
			$shortcode .= ' show_cluster="1"';
		}

		$shortcode .= ']';
		$content = do_shortcode($shortcode);

		//Single Map page only
		if (is_single()) {
			//START Meta
			$map_meta = Waymark_Helper::get_map_meta($Map);

			//Do we have something to display?
			if (sizeof($map_meta)) {
				$content .= Waymark_Helper::map_meta_html($map_meta, false);
			}
			//END Meta
		}

		return $content;
	}
}

new Waymark_Content;
