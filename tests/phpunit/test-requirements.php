<?php
/**
 * Compatibility requirement declaration tests.
 *
 * @package Waymark
 */

/**
 * Guards the compatibility floor: the plugin header, the distributed readme.txt
 * and the generated readme.md must all declare the same minimum versions.
 */
class Test_Requirements extends WP_UnitTestCase {

	/**
	 * Resolve the plugin root directory.
	 *
	 * @return string Absolute path with a trailing slash.
	 */
	private function plugin_dir() {
		return dirname( __DIR__, 2 ) . '/';
	}

	/**
	 * Read the declared value for a readme.txt header line.
	 *
	 * @param string $label Header label without the trailing colon.
	 * @return string|null Header value, or null when the label is absent.
	 */
	private function readme_txt_value( $label ) {
		$contents = file_get_contents( $this->plugin_dir() . 'readme.txt' );

		$this->assertIsString( $contents, 'readme.txt should be readable.' );

		if ( preg_match( '/^' . preg_quote( $label, '/' ) . ':\s*(.+)$/m', $contents, $matches ) ) {
			return trim( $matches[1] );
		}

		return null;
	}

	/**
	 * The plugin header declares the compatibility floor.
	 */
	public function test_plugin_header_declares_requirements() {
		$headers = get_file_data(
			$this->plugin_dir() . 'Waymark.php',
			[
				'RequiresAtLeast' => 'Requires at least',
				'RequiresPHP'     => 'Requires PHP',
			],
			'plugin'
		);

		$this->assertSame( '6.4', $headers['RequiresAtLeast'], 'Waymark.php should require WordPress 6.4.' );
		$this->assertSame( '8.3', $headers['RequiresPHP'], 'Waymark.php should require PHP 8.3.' );
	}

	/**
	 * The distributed readme.txt declares the compatibility floor.
	 */
	public function test_readme_txt_declares_requirements() {
		$this->assertSame( '6.4', $this->readme_txt_value( 'Requires at least' ), 'readme.txt should require WordPress 6.4.' );
		$this->assertSame( '8.3', $this->readme_txt_value( 'Requires PHP' ), 'readme.txt should require PHP 8.3.' );
	}

	/**
	 * The generated readme.md is in sync with the compatibility floor.
	 */
	public function test_generated_readme_declares_requirements() {
		$contents = file_get_contents( $this->plugin_dir() . 'readme.md' );

		$this->assertIsString( $contents, 'readme.md should be readable.' );
		$this->assertStringContainsString( '**Requires at least:** 6.4', $contents, 'readme.md is stale: regenerate it from readme.txt.' );
		$this->assertStringContainsString( '**Requires PHP:** 8.3', $contents, 'readme.md is stale: regenerate it from readme.txt.' );
	}

	/**
	 * All three files declare the same minimum versions.
	 */
	public function test_requirements_agree_across_files() {
		$headers = get_file_data(
			$this->plugin_dir() . 'Waymark.php',
			[
				'RequiresAtLeast' => 'Requires at least',
				'RequiresPHP'     => 'Requires PHP',
			],
			'plugin'
		);

		$readme_txt_wp   = $this->readme_txt_value( 'Requires at least' );
		$readme_txt_php  = $this->readme_txt_value( 'Requires PHP' );
		$readme_md       = file_get_contents( $this->plugin_dir() . 'readme.md' );

		$this->assertSame( $headers['RequiresAtLeast'], $readme_txt_wp, 'WordPress minimum in Waymark.php and readme.txt has drifted.' );
		$this->assertSame( $headers['RequiresPHP'], $readme_txt_php, 'PHP minimum in Waymark.php and readme.txt has drifted.' );

		$this->assertIsString( $readme_md, 'readme.md should be readable.' );
		$this->assertStringContainsString( '**Requires at least:** ' . $headers['RequiresAtLeast'], $readme_md, 'WordPress minimum in readme.md has drifted from Waymark.php.' );
		$this->assertStringContainsString( '**Requires PHP:** ' . $headers['RequiresPHP'], $readme_md, 'PHP minimum in readme.md has drifted from Waymark.php.' );
	}
}
