<?php
/**
 * Heading Colors for AAA Chinese theme.
 *
 * @package     AAA Chinese
 * @link        https://www.brainstormforce.com
 * @since       AAA Chinese 2.1.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'AAA_THEME_HEADING_COLORS_DIR', AAA_THEME_DIR . 'inc/addons/heading-colors/' );
define( 'AAA_THEME_HEADING_COLORS_URI', AAA_THEME_URI . 'inc/addons/heading-colors/' );

if ( ! class_exists( 'AAA_Chinese_Heading_Colors' ) ) {

	/**
	 * Heading Initial Setup
	 *
	 * @since 2.1.4
	 */
	class AAA_Chinese_Heading_Colors {
		/**
		 * Constructor function that initializes required actions and hooks
		 */
		public function __construct() {

			require_once AAA_THEME_HEADING_COLORS_DIR . 'class-aaa-chinese-heading-colors-loader.php';// phpcs:ignore: WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound

			// Include front end files.
			if ( ! is_admin() ) {
				require_once AAA_THEME_HEADING_COLORS_DIR . 'dynamic-css/dynamic.css.php';// phpcs:ignore: WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			}
		}
	}

	/**
	 *  Kicking this off by creating an object.
	 */
	new AAA_Chinese_Heading_Colors();

}
