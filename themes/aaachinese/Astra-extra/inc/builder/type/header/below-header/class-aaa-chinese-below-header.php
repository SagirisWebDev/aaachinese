<?php
/**
 * Below Header.
 *
 * @package     astra-builder
 * @link        https://www.brainstormforce.com
 * @since       3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'AAA_BELOW_HEADER_DIR', AAA_THEME_DIR . 'inc/builder/type/header/below-header' );
define( 'AAA_BELOW_HEADER_URI', AAA_THEME_URI . 'inc/builder/type/header/below-header' );

/**
 * Below Header Initial Setup
 *
 * @since 3.0.0
 */
class Astra_Below_Header {
	/**
	 * Constructor function that initializes required actions and hooks.
	 */
	public function __construct() {

		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		require_once AAA_BELOW_HEADER_DIR . '/class-aaa-chinese-below-header-loader.php';

		// Include front end files.
		if ( ! is_admin() || Astra_Builder_Customizer::astra_collect_customizer_builder_data() ) {
			require_once AAA_BELOW_HEADER_DIR . '/dynamic-css/dynamic.css.php';
		}
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}
}

/**
 *  Kicking this off by creating an object.
 */
new Astra_Below_Header();
