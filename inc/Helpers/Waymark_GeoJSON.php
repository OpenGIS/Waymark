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
		if(! is_object($FeatureCollection)) {
			$FeatureCollection = json_decode($FeatureCollection);		

			$return_type = 'string';
		}
		
		//Feature Collection
		if($FeatureCollection && is_array($FeatureCollection->features)) {	
			//Each Feature
			foreach($FeatureCollection->features as &$Feature) {
				if(! is_array($Feature->properties)) {
					$Feature->properties = [];
				}
				
				$Feature->properties[$property_key] = $property_value;
			}
		}		
		
		if($return_type == 'string') {
			$FeatureCollection = json_encode($FeatureCollection);
		}
		
		return $FeatureCollection;	
	}		
}