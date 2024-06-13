<?php

class Waymark_HTTP {

	function __construct() {
		add_filter('query_vars', array($this, 'query_vars'));
		add_action('template_redirect', array($this, 'template_redirect'));

		//Setup AJAX
		Waymark_JS::add_chunk('//HTTP');
		Waymark_JS::add_chunk('var waymark_http_endpoint = "' . Waymark_Helper::http_url() . '";');
	}

	public function query_vars($vars) {
		$vars[] = 'waymark_http';

		return $vars;
	}

	public function template_redirect() {
		//If not Waymark HTTP request
		if (!get_query_var('waymark_http')) {
			//WP loads normally
			return;
		}

		//Action
		//!!! Submission
		if (array_key_exists('waymark_message', $_REQUEST)) {
			//Waymark_Helper::debug('Joetest!' . $_REQUEST['waymark_message'], false);
		}

		//Action
		if (array_key_exists('waymark_action', $_REQUEST)) {
			//Requires Map Data
			if (in_array($_REQUEST['waymark_action'], array(
				'get_map_data',
				'download_map_data',
				'download_collection_data',
			))) {
				//Do we have data?
				if (array_key_exists('map_id', $_REQUEST) && is_numeric($_REQUEST['map_id'])) {
					//Valid Map
					if ($Map = new Waymark_Map($_REQUEST['map_id'])) {
						if (isset($Map->data['map_data']) && !empty($Map->data['map_data'])) {
							//Clean
							$map_data = Waymark_GeoJSON::remove_unwanted_overlay_properties($Map->data['map_data']);
							//Invalid Map data
						} else {
							die("-1");
						}
						//Invalid Map
					} else {
						die("-1");
					}
				} elseif (array_key_exists('collection_id', $_REQUEST) && is_numeric($_REQUEST['collection_id'])) {
					$Collection = new Waymark_Collection($_REQUEST['collection_id']);
					//Invalid Map ID
				} else {
					die("-1");
				}

				//Gzip supported?
				if (function_exists('gzcompress') && !in_array('ob_gzhandler', ob_list_handlers())) {
					ob_start("ob_gzhandler");
				} else {
					ob_start();
				}

				//Cache
				header('Cache-control: public,max-age=' . DAY_IN_SECONDS);

				switch ($_REQUEST['waymark_action']) {
				// === AJAX Load ===

				case 'get_map_data':
					//Security
					check_ajax_referer(Waymark_Config::get_item('nonce_string'), 'waymark_security');

					//Required data
					if (!isset($map_data) || !isset($Map)) {
						die("-1");
					}

					//Modify
					$map_data = Waymark_Helper::add_map_link_to_description($Map->post_id, $Map->post_title, $map_data);

					//Type
					header('Content-Type: application/geo+json');

					//The content
					if (isset($map_data) && $map_data) {
						echo $map_data;
					}

					break;

				// === Collection Export ===

				case 'download_collection_data':
					//Security
					check_ajax_referer(Waymark_Config::get_item('nonce_string'), 'waymark_security');

					//Required data
					if (!isset($Collection)) {
						die("-1");
					}

					//File download name
					$export_filename = $Collection->slug . '-' . $Collection->collection_id . '.' . $_REQUEST['export_format'];

					break;

				// === Map Export ===

				case 'download_map_data':
					//Security
					check_ajax_referer(Waymark_Config::get_item('nonce_string'), 'waymark_security');

					//Required data
					if (!isset($map_data) || !isset($Map)) {
						die("-1");
					}

					//File download name
					$export_filename = get_post_field('post_name', $Map->post_id) . '-' . $Map->post_id . '.' . $_REQUEST['export_format'];

					break;
				}

				//Echo
				if (isset($export_filename) && isset($_REQUEST['map_data'])) {
					header('Content-Disposition: attachment; filename="' . $export_filename . '"');

					switch ($_REQUEST['export_format']) {
					case 'gpx':
						header('Content-Type: application/gpx+xml');

						break;
					case 'kml':
						header('Content-Type: application/vnd.google-earth.kml+xml');

						break;
					case 'geojson':
						header('Content-Type: application/geo+json');

						break;
					}

					echo rawurldecode(strip_tags($_REQUEST['map_data']));
				}

				//That's it, that's all...
				die;

				//Does not require Map data
			} else {
				switch ($_REQUEST['waymark_action']) {
				// === Public Submissions ===
				case 'public_add_map':
					//Security
					check_ajax_referer(Waymark_Config::get_item('nonce_string'), 'waymark_security');

					//Create submission
					$Submission = new Waymark_Submission($_REQUEST);

					//Ensure submissions allowed
					if ($Submission->get_allowed() === true) {
						//Create Map
						$Submission->create_map();

						$Submission->do_redirect();

//							Waymark_Helper::debug($Submission);
						//Submission not allowed
					} else {
						///!!! Submission
					}

					break;
				}

				die;
			}
		}
	}
}

new Waymark_HTTP;
