<?php
/**
 * Map data guard tests.
 *
 * @package Waymark
 */

/**
 * Regression tests for Waymark_Helper::set_map_data_property() when the Map
 * data is missing, malformed or not a FeatureCollection.
 */
class Test_Map_Data_Guards extends WP_UnitTestCase {

	/**
	 * Build a minimal valid GeoJSON FeatureCollection.
	 *
	 * @return array GeoJSON FeatureCollection.
	 */
	private function feature_collection() {
		return [
			'type'     => 'FeatureCollection',
			'features' => [
				[
					'type'       => 'Feature',
					'geometry'   => [
						'type'        => 'Point',
						'coordinates' => [ -68.74923, 51.38436 ],
					],
					'properties' => [
						'title' => 'Valid Overlay',
					],
				],
			],
		];
	}

	/**
	 * A FeatureCollection without a features member is returned unchanged.
	 */
	public function test_set_map_data_property_returns_input_when_features_missing() {
		$map_data = wp_json_encode( [ 'type' => 'FeatureCollection' ] );

		$this->assertSame(
			$map_data,
			Waymark_Helper::set_map_data_property( $map_data, 'description', 'Link', true )
		);
	}

	/**
	 * Invalid JSON is returned unchanged.
	 */
	public function test_set_map_data_property_returns_input_for_invalid_json() {
		$map_data = '{"type": "FeatureCollection", "features": [';

		$this->assertSame(
			$map_data,
			Waymark_Helper::set_map_data_property( $map_data, 'description', 'Link', true )
		);
	}

	/**
	 * A JSON scalar is returned unchanged.
	 */
	public function test_set_map_data_property_returns_input_for_json_scalar() {
		foreach ( [ '5', '123', '"text"', 'true' ] as $map_data ) {
			$this->assertSame(
				$map_data,
				Waymark_Helper::set_map_data_property( $map_data, 'description', 'Link', true ),
				'JSON scalar should be returned unchanged: ' . $map_data
			);
		}
	}

	/**
	 * A valid FeatureCollection still gets the property set.
	 */
	public function test_set_map_data_property_sets_property_on_valid_feature_collection() {
		$result = Waymark_Helper::set_map_data_property(
			wp_json_encode( $this->feature_collection() ),
			'description',
			'Map link',
			true
		);

		$decoded = json_decode( $result, true );

		$this->assertIsArray( $decoded, 'Valid map data should remain valid JSON: ' . json_last_error_msg() );
		$this->assertSame( 'Map link', $decoded['features'][0]['properties']['description'] );
	}
}
