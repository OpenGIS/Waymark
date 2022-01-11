<?php

class Waymark_Overpass {

	static public function overpass_element_to_geojson_feature(array $element, $cast_to = 'marker') {
		$Feature = [];

		switch($cast_to) {
			//Markers
			case 'marker' :
				//Geometry
				$Feature = [
					'type' => 'Feature',
					'geometry' => [
						'type' => 'Point'
					],					
					'properties' => []
				];

				switch($element['type']) {
					case 'node' :
						$Feature['geometry']['coordinates'] = [
							$element['lon'], $element['lat']
						];
						
// 						Waymark_Helper::debug($Element);
									
						break;
				
					case 'way' :
						$Feature['geometry']['coordinates'] = [
							$element['center']['lon'], $element['center']['lat']
						];

						break;
				}
					
				break;

			//Lines
			case 'line' :
				if(isset($element['type'])) {
					//Geometry
					$Feature = [
						'type' => 'Feature',
						'geometry' => [
							'type' => 'LineString'
						],					
						'properties' => []
					];

					switch($element['type']) {
						case 'way' :
							if(isset($element['geometry']) && sizeof($element['geometry'])) {
								foreach($element['geometry'] as $coordinates) {
									$Feature['geometry']['coordinates'][] = [
										$coordinates['lon'], $coordinates['lat']
									];
								}
							}

							break;
					}
				}
										
				break;
		}

		//Properties
		if(isset($element['tags']))  {
			//Title
			if(isset($element['tags']['name'])) {
				$Feature['properties']['title'] = $element['tags']['name'];
			}	
			
			//Description
			$desc = '<table>';						
			foreach($element['tags'] as $key => $value) {
				$desc .= '<tr>';
				$desc .= '<th>' . $key . '</th><td>' . $value . '</td>';														
				$desc .= '</tr>';							
			}
			
			$desc .= '</table>';
			
			$Feature['properties']['description'] = $desc;
//			$Feature['properties']['description'] = htmlentities($desc);
		}
		
		return $Feature;
	}

	static public function overpass_json_to_geojson(array $overpass_json, $cast_to = 'marker') {
		$FeatureCollection = [
    	"type" => "FeatureCollection",
      "features" => []		
		];
		
		//
		if(isset($overpass_json['elements']) && is_array($overpass_json['elements'])) {
			//Each feature
			foreach($overpass_json['elements'] as $element) {
				$FeatureCollection['features'][] = self::overpass_element_to_geojson_feature($element, $cast_to);
			}
		}
		
		return $FeatureCollection;
	}
	
	static public function get_overpass_response_error($html = '') {
		$html = str_replace([
			'<p>The data included',
			'under ODbL.</p>'
		],
		[
			'<!--<p>The data included',
			'under ODbL.</p>-->'
		], $html);
		
		//Thanks! 
		//https://stackoverflow.com/a/48156323
		preg_match("/<body[^>]*>(.*?)<\/body>/is", $html, $matches);

		return $matches[1];	
	}
}