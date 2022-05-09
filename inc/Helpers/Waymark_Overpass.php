<?php

class Waymark_Overpass {

	static public function overpass_element_to_geojson_feature(array $element, $cast_to = 'marker') {
		$Feature = [];

		switch($cast_to) {

			// ========== Markers ==========

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
						if(isset($element['lon']) && isset($element['lat'])) {
							$Feature['geometry']['coordinates'] = [
								$element['lon'], $element['lat']
							];
						}
													
						break;
				
					case 'way' :
						if(isset($element['center']['lon']) && isset($element['center']['lat'])) {
							$Feature['geometry']['coordinates'] = [
								$element['center']['lon'], $element['center']['lat']
							];
						}
						
						break;
						
					case 'relation' :
						if(isset($element['center']['lon']) && isset($element['center']['lat'])) {
							$Feature['geometry']['coordinates'] = [
								$element['center']['lon'], $element['center']['lat']
							];
						}
												
						break;						
				}
					
				break;

			// ========== Lines ==========
			
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
						//Way
						case 'way' :
							if(isset($element['geometry']) && sizeof($element['geometry'])) {
								foreach($element['geometry'] as $coordinates) {
									$Feature['geometry']['coordinates'][] = [
										$coordinates['lon'], $coordinates['lat']
									];
								}
							}

							break;

						//Relation
						case 'relation' :
							if(isset($element['members']) && sizeof($element['members'])) {
								//Each Way
								foreach($element['members'] as $way) {							
									if(isset($way['geometry']) && sizeof($way['geometry'])) {
										foreach($way['geometry'] as $coordinates) {
											$Feature['geometry']['coordinates'][] = [
												$coordinates['lon'], $coordinates['lat']
											];
										}
									}
								}
							}
							
							break;
					}
				}
										
				break;
		}

		//OSM ID
		if(isset($element['type']) && isset($element['id']))  {
			$Feature['properties']['osm_id'] = $element['type'] . '/' . $element['id'];
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

				switch($key) {
					case 'website':
						$desc .= '<th>' . $key . '</th><td><a href="' . $value . '">' . $value . '</a></td>';														
						
						break;
										
					default:
						$desc .= '<th>' . $key . '</th><td>' . $value . '</td>';														
						
						break;			
				}

				$desc .= '</tr>';							
			}
			
			$desc .= '</table>';
			
			$Feature['properties']['description'] = $desc;
			//$Feature['properties']['description'] = htmlentities($desc);
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
	
	static public function leaflet_bb_to_overpass_bb($leaflet_bb) {
		$qa = explode(',', $leaflet_bb);
		
		if(is_array($qa)) {
			return $qa[1] . ',' . $qa[0] . ',' . $qa[3] . ',' . $qa[2];				
		} else {
			return false;
		}
	}
	
	static public function leaflet_poly_to_overpass_poly($leaflet_poly) {
		$poly_array = explode(',', $leaflet_poly);
		
		if(is_array($poly_array)) {
			return implode(' ', $poly_array);
		} else {
			return false;
		}	
	}
}