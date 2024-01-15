<?php

class Waymark_GeoJSON {

	/**
	 * Convert a GeoJSON string to an array
	 *
	 * @param  string $string GeoJSON string
	 * @return array          GeoJSON array
	 */
	static public function string_to_feature_collection(String $string = '') {
		if (is_string($string)) {
			return json_decode($string, true, 512, JSON_OBJECT_AS_ARRAY);
		}

		return false;
	}

	/**
	 * Get count of features in a GeoJSON array
	 *
	 * @param		array|string 			$FeatureCollection GeoJSON array or string
	 * @return	integer|boolean 	Count of features or false
	 */
	static public function get_feature_count($FeatureCollection = []) {
		if (is_string($FeatureCollection)) {
			$FeatureCollection = self::string_to_feature_collection($FeatureCollection);
		}

		if ($FeatureCollection && isset($FeatureCollection['features']) && is_array($FeatureCollection['features'])) {

			return sizeof($FeatureCollection['features']);
		}

		return false;
	}

	/**
	 * Update every feature's property in a GeoJSON array
	 *
	 * @param  array  $FeatureCollection GeoJSON array
	 * @param  string $property_key      Property key
	 * @param  string $property_value    Property value
	 * @return array                     Updated GeoJSON array
	 */
	static public function update_feature_property(Array $FeatureCollection = [], String $property_key = '', String $property_value = '') {
		if (is_string($FeatureCollection)) {
			$FeatureCollection = self::string_to_feature_collection($FeatureCollection);
		}

		//Feature Collection
		if ($FeatureCollection && isset($FeatureCollection['features'])) {

			//Each Feature
			foreach ($FeatureCollection['features'] as &$Feature) {
				if (!isset($Feature['properties']) || !is_array($Feature['properties'])) {
					$Feature['properties'] = [];
				}

				$Feature['properties'][$property_key] = $property_value;
			}
		}

		return $FeatureCollection;
	}

	/**
	 * Replace bad characters in every feature's description property in a GeoJSON array
	 *
	 * @param  array  $FeatureCollection GeoJSON array
	 * @return array                     Updated GeoJSON array
	 */
	static public function clean_feature_descriptions(Array $FeatureCollection = []) {
		//Feature Collection
		if ($FeatureCollection && isset($FeatureCollection['features'])) {

			//Each Feature
			foreach ($FeatureCollection['features'] as &$Feature) {
				if (isset($Feature['properties']['description'])) {
					$bad = ['"'];
					$good = ['\"'];
					$Feature['properties']['description'] = str_replace($bad, $good, $Feature['properties']['description']);
				}
			}
		}

		return $FeatureCollection;
	}

	/**
	 * Sort features into Markers, Lines and Shapes and then by type
	 *
	 * @param  array  $FeatureCollection GeoJSON array
	 * @return array                     Sorted GeoJSON array
	 */
	static public function features_by_overlay_type(Array $FeatureCollection = []) {

		// Waymark_Helper::debug($FeatureCollection);

		$overlays = array(
			'markers' => array(),
			'lines' => array(),
			'shapes' => array(),
		);

		foreach ($FeatureCollection['features'] as $feature) {

			// Waymark_Helper::debug($feature);

			//Ensure feature properties has type
			if (!isset($feature['properties']['type'])) {
				continue;
			}

			if (isset($feature['geometry']['type'])) {
				switch ($feature['geometry']['type']) {
				case 'Point':
					//Circle
					if (isset($feature['properties']['radius'])) {
						$type = 'shapes';
						//Marker
					} else {
						$type = 'markers';
					}

					break;

				case 'LineString':
				case 'MultiLineString':
					$type = 'lines';

					break;
				case 'Polygon':
					$type = 'shapes';

					break;
				}
			}

			//Add to Array if we have a type
			if (isset($type)) {
				$overlays[$type][$feature['properties']['type']][] = $feature;
			}
		}

		// Order markers/lines/shapes by type
		foreach ($overlays as &$overlay) {
			ksort($overlay);
		}

		return $overlays;
	}
}