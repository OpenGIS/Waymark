<?php

class Waymark_GeoJSON {

	static public function get_feature_count($FeatureCollection = []) {			
		if(is_string($FeatureCollection)) {
			$FeatureCollection = json_decode($FeatureCollection, true, 512,  JSON_OBJECT_AS_ARRAY);		
		}
		
		if($FeatureCollection && isset($FeatureCollection['features']) && is_array($FeatureCollection['features'])) {			

			return sizeof($FeatureCollection['features']);
		}		

		return false;	
	}	

	static public function update_feature_property($FeatureCollection = [], $property_key = null, $property_value = null, $return_type = 'object') {		
		if(is_string($FeatureCollection)) {
			$FeatureCollection = json_decode($FeatureCollection, true, 512,  JSON_OBJECT_AS_ARRAY);		
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
		
// 		if($return_type == 'string') {
// 			$FeatureCollection = json_encode($FeatureCollection);
// 		}
		
		return $FeatureCollection;	
	}	
}