<?php
/**
 * Collection shortcode integration tests.
 *
 * @package Waymark
 */

/**
 * Regression tests for the embed Collection shortcode when a Collection
 * contains Maps with malformed or incomplete GeoJSON.
 */
class Test_Shortcode_Collection extends WP_UnitTestCase {

	/**
	 * Use embed mode so Collection Maps are merged into one FeatureCollection.
	 */
	public function set_up() {
		parent::set_up();

		delete_option( 'Waymark_Settings' );

		update_option(
			'Waymark_Settings',
			[
				'misc' => [
					'collection_options' => [
						'load_method' => 'embed',
					],
				],
			]
		);

		Waymark_Config::init();
	}

	/**
	 * Restore the default config for the next test.
	 */
	public function tear_down() {
		delete_option( 'Waymark_Settings' );
		Waymark_Config::init();

		parent::tear_down();
	}

	/**
	 * Create a published Map with raw map data and add it to a Collection.
	 *
	 * @param string $map_data      Stored waymark_map_data value.
	 * @param int    $collection_id Collection term ID.
	 * @param string $title         Map title.
	 * @return int                  Map post ID.
	 */
	private function create_map( $map_data, $collection_id, $title ) {
		$post_id = self::factory()->post->create(
			[
				'post_type'   => 'waymark_map',
				'post_status' => 'publish',
				'post_title'  => $title,
			]
		);

		update_post_meta( $post_id, 'waymark_map_data', wp_slash( $map_data ) );
		wp_set_object_terms( $post_id, [ (int) $collection_id ], 'waymark_collection' );

		return $post_id;
	}

	/**
	 * Extract the GeoJSON passed to waymark_viewer.load_json() from the output.
	 *
	 * @param string $output Shortcode output.
	 * @return array         Decoded GeoJSON.
	 */
	private function extract_loaded_geojson( $output ) {
		$this->assertSame(
			1,
			preg_match( '/waymark_viewer\.load_json\((\{.*?\})\);/s', $output, $matches ),
			'Shortcode output should contain a single embedded GeoJSON payload.'
		);

		$decoded = json_decode( $matches[1], true );

		$this->assertIsArray( $decoded, 'Embedded GeoJSON should decode: ' . json_last_error_msg() );

		return $decoded;
	}

	/**
	 * Invalid Maps are skipped while the valid Map's features survive.
	 */
	public function test_embed_collection_skips_invalid_maps_and_keeps_valid_features() {
		$term = wp_insert_term( 'Shortcode Collection', 'waymark_collection' );

		$this->assertIsArray( $term );

		$collection_id = $term['term_id'];

		$valid_map_data = wp_json_encode(
			[
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
			]
		);

		$this->create_map( $valid_map_data, $collection_id, 'Valid Map' );
		$this->create_map( wp_json_encode( [ 'type' => 'FeatureCollection' ] ), $collection_id, 'Missing Features Map' );
		$this->create_map( '{"type": "FeatureCollection", "features": [', $collection_id, 'Invalid JSON Map' );

		$output = do_shortcode( '[Waymark collection_id="' . $collection_id . '"]' );

		$this->assertIsString( $output );

		$loaded = $this->extract_loaded_geojson( $output );

		$this->assertSame( 'FeatureCollection', $loaded['type'] );
		$this->assertCount( 1, $loaded['features'] );
		$this->assertSame( 'Valid Overlay', $loaded['features'][0]['properties']['title'] );
	}
}
