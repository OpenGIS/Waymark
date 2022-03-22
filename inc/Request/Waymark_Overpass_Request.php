<?php

require_once('Waymark_Request.php');
	
class Waymark_Overpass_Request extends Waymark_Request {
	
	function __construct($params_in = []) {
//		$this->request_endpoint = 'http://overpass-api.de/api/interpreter';	
		$this->request_endpoint = 'https://overpass.kumi.systems/api/interpreter';	

		$this->set_config('output_type', 'json');

		foreach($params_in as $key => $value) {
//			if(is_string($value)) {
				$this->set_config($key, $value);		
//			}
		}
	}

	function build_request_parameters($params_in = []) {
		$Query = $this->get_config('Query');

		$query_area = $Query->get_query_area();
		$query_string = $Query->get_query_overpass();

		//Check for required data
		if(! $query_area || ! $query_string) {
			return false;
		}

		
		// ==== Headers ====

		
		//Output type
		$overpass_query = '[out:' . $this->get_config('output_type') . ']';

		//Query area
		switch($query_area['type']) {
			case 'bounds' :
				//Convert from Leaflet to Overpass
				$overpass_bounding_box = Waymark_Overpass::leaflet_bb_to_overpass_bb($query_area['area']);
				
				//Create header
				$overpass_query .= '[bbox:' . $overpass_bounding_box . ']';
			
				break;		
		}
		$overpass_query .= ';';

		// ==== User Query ====
		
		$overpass_query .= $query_string;

		//Query area
		switch($query_area['type']) {
			case 'polygon' :
				//Convert from Leaflet to Overpass
				$overpass_polygon = Waymark_Overpass::leaflet_poly_to_overpass_poly($query_area['area']);
				
				//Create header
				$overpass_query .= '(poly:"' . $overpass_polygon . '")';

				break;				
		}

		$overpass_query .= ';';

		// ==== Output ====

		switch($Query->get_parameter('query_cast_overlay')) {
			case 'marker' :
				$overpass_query .= 'out center';

				break;

			case 'line' :
				$overpass_query .= 'out geom';	

				break;				
		}
		$overpass_query .= ';';

		//Make safe
		$overpass_query = str_replace('+', '%20', $overpass_query);

//		Waymark_Helper::debug($overpass_query);

		$return = [
			'data' => $overpass_query
		];
		
		return $return;
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
					$Query = $this->get_config('Query');			
					$query_cast_overlay = $Query->get_parameter('query_cast_overlay');

					$response_out['status'] = 'success';

					//Ensure is Array
					$response_json = json_decode($response_raw['body'], null, 512, JSON_OBJECT_AS_ARRAY);

					//Get Overlays GeoJSON		
					$response_geojson = Waymark_Overpass::overpass_json_to_geojson($response_json, $query_cast_overlay);

					//If we have overlays
					$overlay_count = Waymark_GeoJSON::get_feature_count($response_geojson);					
					if($overlay_count) {
						//What kind of Overlay?
						switch($query_cast_overlay) {
							//Markers
							case 'marker' :
								$query_data = Waymark_GeoJSON::update_feature_property($response_geojson, 'type', $Query->get_parameter('query_cast_marker_type'));						

								break;

							//Lines
							case 'line' :
								$query_data = Waymark_GeoJSON::update_feature_property($response_geojson, 'type', $Query->get_parameter('query_cast_line_type'));

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