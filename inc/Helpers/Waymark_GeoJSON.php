<?php

class Waymark_GeoJSON {

	static public function get_feature_count($FeatureCollection = []) {		
		if(is_string($FeatureCollection)) {
			$FeatureCollection = json_decode($FeatureCollection);		
		}
			
		if($FeatureCollection && isset($FeatureCollection->features)) {	

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
				if(! isset($Feature['properties']) || ! is_array($Feature['properties'])) {
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
					
				break;

			//Lines
			case 'line' :
				//Waymark_Helper::debug($Element);
				
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
		if(is_string($Json)) {
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
		
		return $FeatureCollection;
	}
}