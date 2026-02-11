<?php
/**
 * Heading Colors for Astra theme.
 *
 * @package     astra-builder
 * @link        https://wpastra.com/
 * @since       3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'AAA_HEADER_SOCIAL_ICON_DIR', AAA_THEME_DIR . 'inc/builder/type/header/social-icon' );
define( 'AAA_HEADER_SOCIAL_ICON_URI', AAA_THEME_URI . 'inc/builder/type/header/social-icon' );

/**
 * Heading Initial Setup
 *
 * @since 3.0.0
 */
class Astra_Header_Social_Icon_Component {
	/**
	 * Constructor function that initializes required actions and hooks
	 */
	public function __construct() {

		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		require_once AAA_HEADER_SOCIAL_ICON_DIR . '/class-aaa-chinese-header-social-icon-component-loader.php';

		// Include front end files.
		if ( ! is_admin() || Astra_Builder_Customizer::astra_collect_customizer_builder_data() ) {
			require_once AAA_HEADER_SOCIAL_ICON_DIR . '/dynamic-css/dynamic.css.php';
		}
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}
}

/**
 *  Kicking this off by creating an object.
 */
new Astra_Header_Social_Icon_Component();
