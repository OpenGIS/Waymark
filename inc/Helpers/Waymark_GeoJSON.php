<?php

class Waymark_GeoJSON {

	static public function get_feature_count($FeatureCollection = []) {		
		if(! is_object($FeatureCollection)) {
			$FeatureCollection = json_decode($FeatureCollection);		
		}
				
		if($FeatureCollection && is_array($FeatureCollection->features)) {	
			return sizeof($FeatureCollection->features);
		}		
			
		return false;	
	}	

	static public function update_feature_property($FeatureCollection = [], $property_key = null, $property_value = null, $return_type = 'object') {		
		if(is_string($FeatureCollection)) {
			$FeatureCollection = json_decode($FeatureCollection);		

			$return_type = 'string';
		}

		//Feature Collection
		if($FeatureCollection && isset($FeatureCollection['features'])) {	

			//Each Feature
			foreach($FeatureCollection['features'] as &$Feature) {
				if(! is_array($Feature['properties'])) {
					$Feature['properties'] = [];
				}
				
				$Feature['properties'][$property_key] = $property_value;
			}
		}		
		
		if($return_type == 'string') {
			$FeatureCollection = json_encode($FeatureCollection);
		}
		
		return $FeatureCollection;	
	}	

	static public function overpass_element_to_geojson_feature($Element = '', $cast_to = 'marker') {
		if(! is_object($Element)) {
			$Element = json_decode($Element);
		}	
		
		$Feature = [];
		
		switch($Element->type) {
			case 'node' :
									
				break;
				
			case 'way' :
				if($cast_to == 'marker' && isset($Element->center)) {
					//Geometry
					$Feature = [
						"type" => "Feature",
						"geometry" => [
							"type" => "Point",
							"coordinates" => [
								$Element->center->lon, $Element->center->lat
							]
						],
						"properties" => []
					];
					
					//Properties
					if(isset($Element->tags))  {
						//Title
						if(isset($Element->tags->name)) {
							$Feature["properties"]["title"] = $Element->tags->name;
						}	
						
						//Description
						$desc = '<table>';						
						foreach($Element->tags as $key => $value) {
							$desc .= '<tr>';
							$desc .= '<th>' . $key . '</th><td>' . $value . '</td>';														
							$desc .= '</tr>';							
						}
						
						$desc .= '</table>';
						
						$Feature["properties"]["description"] = $desc;					
					}
			
				}
				//  elseif($cast_to == 'line' && true) {
// 				
// 				}

				break;
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
}