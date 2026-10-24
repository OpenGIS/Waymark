<?php
/**
 * Attachment image meta tests.
 *
 * @package Waymark
 */

/**
 * Regression tests for Waymark_AJAX::get_attachment_image_meta(), which guards
 * callers against missing and malformed attachment metadata.
 */
class Test_Attachment_Meta extends WP_UnitTestCase {

	/**
	 * Load the AJAX handler, which is normally only loaded in the admin.
	 */
	public function set_up() {
		parent::set_up();

		if ( ! class_exists( 'Waymark_JS' ) ) {
			require_once dirname( __DIR__, 2 ) . '/inc/Front/Waymark_JS.php';
		}

		require_once dirname( __DIR__, 2 ) . '/inc/Admin/Waymark_AJAX.php';
	}

	/**
	 * A nonexistent attachment ID returns null.
	 */
	public function test_get_attachment_image_meta_returns_null_for_nonexistent_attachment() {
		$this->assertNull( Waymark_AJAX::get_attachment_image_meta( 999999 ) );
	}

	/**
	 * A real attachment with image_meta metadata returns the image meta array.
	 */
	public function test_get_attachment_image_meta_returns_array_for_attachment_with_image_meta() {
		$attachment_id = self::factory()->attachment->create();

		wp_update_attachment_metadata(
			$attachment_id,
			[
				'image_meta' => [
					'title' => 'Test Image',
				],
			]
		);

		$image_meta = Waymark_AJAX::get_attachment_image_meta( $attachment_id );

		$this->assertIsArray( $image_meta );
		$this->assertSame( 'Test Image', $image_meta['title'] );
	}

	/**
	 * An empty image_meta array is preserved rather than treated as missing.
	 */
	public function test_get_attachment_image_meta_preserves_empty_image_meta() {
		$attachment_id = self::factory()->attachment->create();

		wp_update_attachment_metadata( $attachment_id, [ 'image_meta' => [] ] );

		$this->assertSame( [], Waymark_AJAX::get_attachment_image_meta( $attachment_id ) );
	}

	/**
	 * Metadata without image_meta returns null.
	 */
	public function test_get_attachment_image_meta_returns_null_when_image_meta_missing() {
		$attachment_id = self::factory()->attachment->create();

		wp_update_attachment_metadata( $attachment_id, [ 'sizes' => [] ] );

		$this->assertNull( Waymark_AJAX::get_attachment_image_meta( $attachment_id ) );
	}
}
