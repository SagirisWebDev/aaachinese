<?php
/**
 * WooCommerce Cart for Astra theme.
 *
 * @package     astra-builder
 * @link        https://wpastra.com/
 * @since       3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'AAA_HEADER_WOO_CART_DIR', AAA_THEME_DIR . 'inc/builder/type/header/woo-cart' );
define( 'AAA_HEADER_WOO_CART_URI', AAA_THEME_URI . 'inc/builder/type/header/woo-cart' );

if ( ! class_exists( 'Astra_Header_Woo_Cart_Component' ) ) {

	/**
	 * Heading Initial Setup
	 *
	 * @since 3.0.0
	 */
	class Astra_Header_Woo_Cart_Component {
		/**
		 * Constructor function that initializes required actions and hooks
		 */
		public function __construct() {

			// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once AAA_HEADER_WOO_CART_DIR . '/class-aaa-chinese-header-woo-cart-loader.php';

			// Include front end files.
			if ( ! is_admin() || Astra_Builder_Customizer::astra_collect_customizer_builder_data() ) {
				require_once AAA_HEADER_WOO_CART_DIR . '/dynamic-css/dynamic.css.php';
			}
			// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		}
	}

	/**
	 *  Kicking this off by creating an object.
	 */
	new Astra_Header_Woo_Cart_Component();

}
