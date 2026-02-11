<?php
/**
 * Above Footer component.
 *
 * @package     Astra Builder
 * @link        https://www.brainstormforce.com
 * @since       Astra 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'AAA_BUILDER_FOOTER_ABOVE_FOOTER_DIR', AAA_THEME_DIR . 'inc/builder/type/footer/above-footer' );
define( 'AAA_BUILDER_FOOTER_ABOVE_FOOTER_URI', AAA_THEME_URI . 'inc/builder/type/footer/above-footer' );

if ( ! class_exists( 'Astra_Above_Footer' ) ) {

	/**
	 * Above Footer Initial Setup
	 *
	 * @since 3.0.0
	 */
	class Astra_Above_Footer {
		/**
		 * Constructor function that initializes required actions and hooks
		 */
		public function __construct() {

			// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once AAA_BUILDER_FOOTER_ABOVE_FOOTER_DIR . '/class-aaa-chinese-above-footer-component-loader.php';

			// Include front end files.
			if ( ! is_admin() || Astra_Builder_Customizer::astra_collect_customizer_builder_data() ) {
				require_once AAA_BUILDER_FOOTER_ABOVE_FOOTER_DIR . '/dynamic-css/dynamic.css.php';
			}
			// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		}
	}

	/**
	 *  Kicking this off by creating an object.
	 */
	new Astra_Above_Footer();

}
