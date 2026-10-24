<?php
/**
 * Boundary normalisation tests.
 *
 * @package Waymark
 */

/**
 * Regression tests for helper functions that receive values from stored or
 * submitted data, ensuring null and non-scalar values do not raise notices.
 */
class Test_Helper_Normalisation extends WP_UnitTestCase {

	/**
	 * Previous error handler, restored after each test.
	 *
	 * @var callable|null
	 */
	private $previous_error_handler = null;

	/**
	 * Restore the previous error handler.
	 */
	public function tear_down() {
		if ( $this->previous_error_handler !== null ) {
			restore_error_handler();
			$this->previous_error_handler = null;
		}

		parent::tear_down();
	}

	/**
	 * Run a callback with deprecations and warnings converted to exceptions.
	 *
	 * @param callable $callback Callback to run.
	 * @return mixed             Callback result.
	 */
	private function run_with_error_handler( $callback ) {
		$this->previous_error_handler = set_error_handler(
			function ( $errno, $errstr ) {
				throw new \ErrorException( $errstr, 0, $errno );
			},
			E_DEPRECATED | E_WARNING
		);

		try {
			return $callback();
		} finally {
			restore_error_handler();
			$this->previous_error_handler = null;
		}
	}

	/**
	 * make_key() returns an empty string for null without raising notices.
	 */
	public function test_make_key_returns_empty_string_for_null_without_notices() {
		$key = $this->run_with_error_handler(
			function () {
				return Waymark_Helper::make_key( null );
			}
		);

		$this->assertSame( '', $key );
	}

	/**
	 * build_icon_data() handles a missing marker icon without raising notices.
	 */
	public function test_build_icon_data_handles_missing_marker_icon_without_notices() {
		$this->assert_icon_data_for_marker_icon( null );
	}

	/**
	 * build_icon_data() handles an empty marker icon without raising notices.
	 */
	public function test_build_icon_data_handles_empty_marker_icon_without_notices() {
		$this->assert_icon_data_for_marker_icon( '' );
	}

	/**
	 * Build icon data for a type with the given marker icon and assert its shape.
	 *
	 * @param string|null $marker_icon Marker icon value, or null to omit it.
	 */
	private function assert_icon_data_for_marker_icon( $marker_icon ) {
		$type = [
			'type_key'      => 'test',
			'icon_type'     => 'icon',
			'icon_colour'   => '#ffffff',
			'marker_colour' => 'red',
		];

		if ( $marker_icon !== null ) {
			$type['marker_icon'] = $marker_icon;
		}

		$icon_data = $this->run_with_error_handler(
			function () use ( $type ) {
				return Waymark_Helper::build_icon_data( $type );
			}
		);

		$this->assertSame( 'waymark-marker waymark-marker-test', $icon_data['className'] );
		$this->assertStringContainsString( 'waymark-marker-icon', $icon_data['html'] );
		$this->assertStringContainsString( 'waymark-icon-icon', $icon_data['html'] );
	}
}
