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

// 		Waymark_Helper::debug($response_raw);

		$response_out = null;
		
		$response_out = [
			'raw' => $response_raw,
			'nodes' => Overpass2Geojson::convertNodes($response_raw),			
			'ways' => Overpass2Geojson::convertWays($response_raw)						
		];
	
/*	
		//Is valid JSON
		if($response_object = json_decode($response)) {
			if(isset($response_object->elements)) {
				//Empty
				if(! sizeof($response_object->elements)) {
					return $response_object->elements; 
				}
				
				//Each element
				foreach($response_object->elements as $element) {
					//Use centers?
					if(! isset($element->lon) && isset($element->center->lon)) {
						$element->lon = $element->center->lon;
					}
					if(! isset($element->lat) && isset($element->center->lat)) {
						$element->lat = $element->center->lat;
					}				
				}			
			}
		}
*/
		
		return $response_out;
	}	
}