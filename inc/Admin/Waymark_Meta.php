<?php

class Waymark_Meta {

	private $Waymark_Object;

	function __construct() {
		//Map
		add_meta_box('waymark_map_meta', esc_html__('Map Editor', 'waymark'), array($this, 'get_map_form'), 'waymark_map', 'normal', 'high');

		//Map
		add_meta_box('waymark_map_shortcode', __('Shortcode', 'waymark'), array($this, 'map_shortcode_content'), 'waymark_map', 'side', 'default');
		add_meta_box('waymark_map_export', __('Export', 'waymark'), array($this, 'map_export_content'), 'waymark_map', 'side', 'default');
		add_meta_box('waymark_map_help', __('Help', 'waymark'), array($this, 'map_help_content'), 'waymark_map', 'side', 'default');

		add_action('post_edit_form_tag', array($this, 'add_post_enctype'));
		add_action('post_updated', array($this, 'post_updated'), 10, 2);

		//Waymark JS
		//CSS
		wp_register_style('waymark-js', Waymark_Helper::plugin_url('waymark-js/dist/css/waymark-js.min.css'), array(), Waymark_Config::get_version());
		wp_enqueue_style('waymark-js');

		//JS

		// Use human readble version of Waymark JS if debug is on :)
		$waymark_js_filename = (Waymark_Helper::is_debug()) ? 'waymark-js.js' : 'waymark-js.min.js';

		wp_register_script('waymark-js', Waymark_Helper::plugin_url('waymark-js/dist/js/' . $waymark_js_filename), array('jquery'), Waymark_Config::get_version());
		//Localize
		wp_localize_script('waymark-js', 'waymark_js', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'lang' => Waymark_Lang::get_js_lang(),
		));
		wp_enqueue_script('waymark-js');
	}

	function add_post_enctype() {
		global $post;

		if (in_array($post->post_type, array('waymark_map'))) {
			echo ' enctype="multipart/form-data"';
		}
	}

	function post_updated() {
		global $post;

		if (is_object($post) && !(wp_is_post_revision($post->ID) || wp_is_post_autosave($post->ID))) {
			switch ($post->post_type) {
			//Map
			case 'waymark_map':
				$Map = new Waymark_Map;
				$Map->set_data($_POST);
				$Map->save_meta($post->ID);

				break;
			}
		}
	}

	function map_export_content() {
		global $post;

		$Map = new Waymark_Map($post->ID);

		$has_features = array_key_exists('map_data', $Map->data) && Waymark_GeoJSON::get_feature_count($Map->data['map_data']);
		if ($has_features) {
			echo '<a data-title="' . esc_attr__('Download the Overlays added to this Map in the selected format.', 'waymark') . '" href="#" onclick="return false;" class="waymark-tooltip">?</a>';
			echo Waymark_Helper::map_export_html($Map);
		}
	}

	function map_shortcode_content() {
		global $post;

		//Shortcode output
		echo '<a data-title="' . esc_attr__('Add this Map to your content with this Shortcode. Click here for more details.', 'waymark') . '" href="' . Waymark_Helper::site_url('docs/shortcodes') . '" class="waymark-tooltip">?</a>';
		echo '<input type="text" value="[' . Waymark_Config::get_item('shortcode') . ' map_id=&quot;' . $post->ID . '&quot;]" />';
		//echo '<p>' . sprintf(__('Add this Map to your content with this <a href="%s">Shortcode</a>.', 'waymark'), ) . '</p>';
	}

	function map_help_content() {
		//Required the PHP EXIF extension
		if (!function_exists('exif_read_data')) {
			echo '<p><b>' . esc_html__('Image Location Detection Not Supported!', 'waymark') . '</b></p>';
			echo '<p>' . sprintf(__('Your hosting environment does not currently have the <a href="%s">PHP EXIF Extension</a> enabled, which is required to read Image location metadata. Try asking your host to enable it.', 'waymark'), 'https://www.php.net/manual/en/book.exif.php') . '</p>';
		}

		echo '<p><b>' . esc_html__('Read from File', 'waymark') . '</b></p>';
		echo '<p>' . esc_html__('You can read Lines and Markers from GPX, KML and GeoJSON files (most mapping apps will be able to export to one of these).', 'waymark') . '</p>';

		echo '<p><b>' . esc_html__('Types', 'waymark') . '</b></p>';
		echo '<p>' . sprintf(__('Types allow you to control how Overlays (Markers, Lines and Shapes) are displayed on the Map. Types can be customised in <a href="%s" target="_blank">Waymark &gt; Settings</a>.', 'waymark'), admin_url('edit.php?post_type=waymark_map&page=waymark-settings&tab=markers')) . '</p>';

		echo '<p><b>' . esc_html__('Basemap', 'waymark') . '</b></p>';
		echo '<p>' . sprintf(__('Add and edit Basemaps in <a href="%s" target="_blank">Waymark &gt; Settings</a>. The first listed will be used as the default, unless specified in the shortcode like this: %s', 'waymark'), admin_url('edit.php?post_type=waymark_map&page=waymark-settings&tab=tiles'), '<code>[Waymark map_id="1234" basemap="Basemap Name"]</code>') . '</p>';

		echo '<p><a class="button" href="' . Waymark_Helper::site_url('docs') . '" target="_blank">' . esc_html__('Read the Docs', 'waymark') . ' &raquo;</a></p>';

		echo '<p>&nbsp;</p>';
	}

	function get_map_form() {
		//WP Media Library
		wp_enqueue_media();

		//WP TinyMCE
		wp_enqueue_editor();

		global $post;

		// Create new Map object
		$Map = new Waymark_Map($post->ID);

		// Add Editor
		Waymark_JS::add_editor($Map->get_geojson());

		// Map Form (inc. Meta)
		echo $Map->create_form();

		echo '<p>' . sprintf(__('You can manage Meta fields in <a href="%s">Settings</a>.', 'waymark'), admin_url('edit.php?post_type=waymark_map&page=waymark-settings&tab=meta')) . '</p>';
	}
}
new Waymark_Meta;
