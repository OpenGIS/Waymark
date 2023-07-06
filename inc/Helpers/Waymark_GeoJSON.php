<?php

class Waymark_GeoJSON {

	static public function string_to_feature_collection($string = '') {			
		if(is_string($string)) {
			return json_decode($string, true, 512,  JSON_OBJECT_AS_ARRAY);		
		}

		return false;	
	}	

	static public function get_feature_count($FeatureCollection = []) {			
		if(is_string($FeatureCollection)) {
			$FeatureCollection = self::string_to_feature_collection($FeatureCollection);		
		}
		
		if($FeatureCollection && isset($FeatureCollection['features']) && is_array($FeatureCollection['features'])) {			

			return sizeof($FeatureCollection['features']);
		}		

		return false;	
	}	

	static public function update_feature_property($FeatureCollection = [], $property_key = null, $property_value = null) {		
		if(is_string($FeatureCollection)) {
			$FeatureCollection = self::string_to_feature_collection($FeatureCollection);		
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

		return $FeatureCollection;	
	}	
	
	static public function clean_feature_descriptions($FeatureCollection = []) {
		//Feature Collection
		if($FeatureCollection && isset($FeatureCollection['features'])) {	

			//Each Feature
			foreach($FeatureCollection['features'] as &$Feature) {
				if(isset($Feature['properties']['description'])) {
					$bad  = ['"'];
					$good = ['\"'];
					$Feature['properties']['description'] = str_replace($bad, $good, $Feature['properties']['description']);
				}
			}
		}		

		return $FeatureCollection;
	}
}