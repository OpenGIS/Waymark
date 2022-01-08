<?php

class Waymark_Overpass {

	static public function overpass_element_to_geojson_feature($Element = '', $cast_to = 'marker') {
		if(! is_object($Element)) {
			$Element = json_decode($Element);
		}	
		
		$Feature = [];

		switch($cast_to) {
			//Markers
			case 'marker' :
				if(isset($Element->type)) {
					//Geometry
					$Feature = [
						'type' => 'Feature',
						'geometry' => [
							'type' => 'Point'
						],					
						'properties' => []
					];

					switch($Element->type) {
						case 'node' :
							$Feature['geometry']['coordinates'] = [
								$Element->lon, $Element->lat
							];
						
	// 						Waymark_Helper::debug($Element);
									
							break;
				
						case 'way' :
							$Feature['geometry']['coordinates'] = [
								$Element->center->lon, $Element->center->lat
							];

							break;
					}
				}
					
				break;

			//Lines
			case 'line' :
				if(isset($Element->type)) {
					//Geometry
					$Feature = [
						'type' => 'Feature',
						'geometry' => [
							'type' => 'LineString'
						],					
						'properties' => []
					];

					switch($Element->type) {
						case 'way' :
							if(isset($Element->geometry) && sizeof($Element->geometry)) {
								foreach($Element->geometry as $coordinates) {
									$Feature['geometry']['coordinates'][] = [
										$coordinates->lon, $coordinates->lat
									];
								}
							}

							break;
					}
				}
										
				break;
		}

		//Properties
		if(isset($Element->tags))  {
			//Title
			if(isset($Element->tags->name)) {
				$Feature['properties']['title'] = $Element->tags->name;
			}	
			
			//Description
			$desc = '<table>';						
				$desc .= '<th>id</th><td><a href="https://www.openstreetmap.org/' . $Element->type . '/' . $Element->id . '">' . $Element->id . '</a></td>';														
			foreach($Element->tags as $key => $value) {
				$desc .= '<tr>';
				$desc .= '<th>' . $key . '</th><td>' . $value . '</td>';														
				$desc .= '</tr>';							
			}
			
			$desc .= '</table>';
			
			$Feature['properties']['description'] = $desc;					
		}
		
		return $Feature;
	}

	static public function overpass_json_to_geojson($Json = '', $cast_to = 'marker') {
		if(! is_object($Json)) {
			$Json = json_decode($Json);
		}
		
		$FeatureCollection = [
    	"type" => "FeatureCollection",
      "features" => []		
		];
		
		//
		if(isset($Json->elements) && is_array($Json->elements)) {
			//Each feature
			foreach($Json->elements as $Element) {
				$FeatureCollection['features'][] = self::overpass_element_to_geojson_feature($Element, $cast_to);
			}
		}

		if(! is_object($Json)) {
			$FeatureCollection = json_encode($FeatureCollection);
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