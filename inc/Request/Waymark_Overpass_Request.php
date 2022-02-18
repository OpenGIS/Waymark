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
		if(! $this->get_config('query_area_bounds')) {
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

					//Convert from Leaflet to Overpass
					$overpass_bounding_box = Waymark_Overpass::leaflet_bb_to_overpass_bb($this->get_config('query_area_bounds'));
										
					//Add to request			
					$overpass_query = str_replace('{{bbox}}', $overpass_bounding_box, $overpass_query);

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
					$response_out['status'] = 'success';

					//Ensure is Array
					$response_json = json_decode($response_raw['body'], null, 512, JSON_OBJECT_AS_ARRAY);
					
					//Get Overlays GeoJSON		
					$response_geojson = Waymark_Overpass::overpass_json_to_geojson($response_json, $this->get_config('query_cast_overlay'));
					
					//If we have
					$overlay_count = Waymark_GeoJSON::get_feature_count($response_geojson);
					
					if($overlay_count) {
						//What kind of Overlay?
						switch($this->get_config('query_cast_overlay')) {
							//Markers
							case 'marker' :
								$query_data = Waymark_GeoJSON::update_feature_property($response_geojson, 'type', $this->get_config('query_cast_marker_type'));						

								break;

							//Lines
							case 'line' :
								$query_data = Waymark_GeoJSON::update_feature_property($response_geojson, 'type', $this->get_config('query_cast_line_type'));

								break;
						}		
	
						$response_out['query_data'] = json_encode($query_data);
					}
					
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