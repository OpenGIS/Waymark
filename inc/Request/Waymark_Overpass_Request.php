<?php

require_once('Waymark_Request.php');
	
class Waymark_Overpass_Request extends Waymark_Request {
	
	function __construct($params_in = []) {
		//$this->request_type = 'rss';
		$this->request_endpoint = 'http://overpass-api.de/api/interpreter';	

		$this->set_config('output_type', 'json');

		foreach($params_in as $key => $value) {
			if(is_string($value)) {
				$this->set_config($key, $value);		
			}
		}
	}

	function build_request_parameters(array $params_in) {
		//Check for required data
		if(! $this->get_config('bounding_box')) {
			return false;
		}
		
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
					
		return $params_out;
	}

	function process_response($response_raw) {
		$response_out = [
			'status' => 'init'
		];
		
		//WP Error?
		if(is_wp_error($response_raw)) {
			$response_out['status'] = 'error';
			$response_out['message'] = $response_raw->get_error_message();
		//Success!!!
		} elseif(isset($response_raw['response']['code'])) {
			$raw_output = $response_raw['body'];
			$raw_output = trim(preg_replace('/\s+/', ' ', $raw_output));
			$response_out['raw'] = $raw_output;

			switch($response_raw['response']['code']) {
				case '200' :
					//Ensure is Array
					$response_json = json_decode($response_raw['body'], null, 512, JSON_OBJECT_AS_ARRAY);
					
					Waymark_Helper::debug($response_json);
					
					$response_geojson = Waymark_Overpass::overpass_json_to_geojson($response_json, $this->get_config('cast_overlay'));
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