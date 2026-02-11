<?php
/**
 * Schema markup.
 *
 * @package     AAA Chinese
 *
 * @since       AAA Chinese 2.1.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * AAA Chinese Schema Markup.
 *
 * @since 2.1.3
 */
class AAA_Chinese_Schema {
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->include_schemas();

		add_action( 'wp', array( $this, 'setup_schema' ) );
	}

	/**
	 * Setup schema
	 *
	 * @since 2.1.3
	 */
	public function setup_schema() {
	}

	/**
	 * Include schema files.
	 *
	 * @since 2.1.3
	 */
	private function include_schemas() {
		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		require_once AAA_THEME_DIR . 'inc/schema/class-aaa-chinese-creativework-schema.php';
		require_once AAA_THEME_DIR . 'inc/schema/class-aaa-chinese-wpheader-schema.php';
		require_once AAA_THEME_DIR . 'inc/schema/class-aaa-chinese-wpfooter-schema.php';
		require_once AAA_THEME_DIR . 'inc/schema/class-aaa-chinese-wpsidebar-schema.php';
		require_once AAA_THEME_DIR . 'inc/schema/class-aaa-chinese-person-schema.php';
		require_once AAA_THEME_DIR . 'inc/schema/class-aaa-chinese-organization-schema.php';
		require_once AAA_THEME_DIR . 'inc/schema/class-aaa-chinese-site-navigation-schema.php';
		require_once AAA_THEME_DIR . 'inc/schema/class-aaa-chinese-breadcrumb-schema.php';
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}

	/**
	 * Enabled schema
	 *
	 * @since 2.1.3
	 */
	protected function schema_enabled() {
		return apply_filters( 'aaa_chinese_schema_enabled', true );
	}

}

new AAA_Chinese_Schema();
