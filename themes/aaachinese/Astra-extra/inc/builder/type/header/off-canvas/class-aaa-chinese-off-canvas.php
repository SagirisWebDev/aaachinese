<?php
/**
 * Off Canvas.
 *
 * @package     astra-builder
 * @link        https://www.brainstormforce.com
 * @since       3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'AAA_OFF_CANVAS_DIR', AAA_THEME_DIR . 'inc/builder/type/header/off-canvas' );
define( 'AAA_OFF_CANVAS_URI', AAA_THEME_URI . 'inc/builder/type/header/off-canvas' );

/**
 * Off Canvas Initial Setup
 *
 * @since 3.0.0
 */
class Astra_Off_Canvas {
	/**
	 * Constructor function that initializes required actions and hooks.
	 */
	public function __construct() {

		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		require_once AAA_OFF_CANVAS_DIR . '/class-aaa-chinese-off-canvas-loader.php';

		// Include front end files.
		if ( ! is_admin() || Astra_Builder_Customizer::astra_collect_customizer_builder_data() ) {
			require_once AAA_OFF_CANVAS_DIR . '/dynamic-css/dynamic.css.php';
		}
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}
}

/**
 *  Kicking this off by creating an object.
 */
new Astra_Off_Canvas();
