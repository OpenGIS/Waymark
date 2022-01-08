<?php

require_once('Waymark_Request.php');
	
class Waymark_Overpass_Request extends Waymark_Request {
	
	function __construct() {
		//Thanks! 
		//!!!
		//https://github.com/mediasuitenz/Overpass2Geojson
		Waymark_Helper::require('Libs/Overpass2Geojson/Overpass2Geojson.php');
		
		//$this->request_type = 'rss';
		$this->request_endpoint = 'http://overpass-api.de/api/interpreter';	

		$this->set_config('output_type', 'json');
		$this->set_config('bounding_box', Waymark_Config::get_setting('query', 'defaults', 'query_area'));

		$this->set_config('cast_overlay', 'marker');
		
		//Parameter migration
		//$this->migrate_parameters = array();
		//$this->migrate_parameters_values = array();				
	}

	function build_request_parameters(array $params_in) {
		//debug($params_in, false);
		
		//Setup call
		$params_out = array();
						
		foreach($params_in as $param_key => $param_value) {
			switch($param_key) {   
				case 'data' :
					//Setup
					$overpass_query = '[out:' . $this->get_config('output_type') . ']';

					//User query
					$overpass_query .= $param_value;

					//Remove comments & whitespace
					$overpass_query = preg_replace('/\/\/(.*)/', '', $overpass_query);
					$overpass_query = preg_replace('!/\*.*?\*/!s', '', $overpass_query);
					$overpass_query = preg_replace('/\n\s*\n/', "\n", $overpass_query);

					//Replace boundingbox with area
					$overpass_query = str_replace('{{bbox}}', $this->get_config('bounding_box'), $overpass_query);

					//Make safe
					//$param_value = urlencode($param_value);

					$overpass_query = str_replace('+', '%20', $overpass_query);
			
					$params_out[$param_key] = $overpass_query;						

					break; 
				default:
					$params_out[$param_key] = $param_value;						

					break;
			}
		}
		
// 		debug($params_out, false);
					
		return $params_out;
	}

	function process_response($response_raw) {
//		Waymark_Helper::debug($response_raw, false);

		$response_out = [
			'status' => 'init',
			'raw' => $response_raw
		];
		
		//WP Error?
		if(is_wp_error($response_raw)) {
			$response_out['status'] = 'error';
			$response_out['message'] = $response_raw->get_error_message();
		//Invalid data?	
		} elseif(! is_array($response_raw)) {
			$response_out['status'] = 'error';
			$response_out['message'] = 'Invalid response.';
		//Success!!!
		} elseif(isset($response_raw['response']['code'])) {
			switch($response_raw['response']['code']) {
				case '200' :
					$response_geojson = Waymark_Overpass::overpass_json_to_geojson($response_raw['body'], $this->get_config('cast_overlay'));
					$response_message = Waymark_GeoJSON::get_feature_count($response_geojson);
					$response_message .= ' Overlays!!!';
								
					$response_out['status'] = 'success';
					$response_out['message'] = $response_message;
					$response_out['geojson'] = $response_geojson;
				
					break;
				case '400' :
					$response_out['status'] = 'error';
					$response_out['message'] = Waymark_Overpass::get_overpass_response_error($response_raw['body']);

					break;
			}
		}
			
		return $response_out;
	}	
}