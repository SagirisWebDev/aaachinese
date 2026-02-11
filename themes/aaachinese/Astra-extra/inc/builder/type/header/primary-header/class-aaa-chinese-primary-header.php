<?php
/**
 * Heading Colors for Astra theme.
 *
 * @package     Astra
 * @link        https://www.brainstormforce.com
 * @since       Astra 3.0.0.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'AAA_PRIMARY_HEADER_DIR', AAA_THEME_DIR . 'inc/builder/type/header/primary-header' );
define( 'AAA_PRIMARY_HEADER_URI', AAA_THEME_URI . 'inc/builder/type/header/primary-header' );

/**
 * Heading Initial Setup
 *
 * @since 3.0.0
 */
class Astra_Primary_Header {
	/**
	 * Constructor function that initializes required actions and hooks
	 */
	public function __construct() {
		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		require_once AAA_PRIMARY_HEADER_DIR . '/class-aaa-chinese-primary-header-loader.php';

		// Include front end files.
		if ( ! is_admin() || Astra_Builder_Customizer::astra_collect_customizer_builder_data() ) {
			require_once AAA_PRIMARY_HEADER_DIR . '/dynamic-css/dynamic.css.php';
			remove_filter( 'astra_dynamic_theme_css', 'astra_header_breakpoint_style' );
		}
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}
}

/**
 *  Kicking this off by creating an object.
 */
new Astra_Primary_Header();
