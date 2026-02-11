<?php
/**
 * Mobile Trigger.
 *
 * @package     astra-builder
 * @link        https://www.brainstormforce.com
 * @since       3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'AAA_MOBILE_TRIGGER_DIR', AAA_THEME_DIR . 'inc/builder/type/header/mobile-trigger' );
define( 'AAA_MOBILE_TRIGGER_URI', AAA_THEME_URI . 'inc/builder/type/header/mobile-trigger' );

/**
 * Mobile Trigger Initial Setup
 *
 * @since 3.0.0
 */
class Astra_Mobile_Trigger {
	/**
	 * Constructor function that initializes required actions and hooks.
	 */
	public function __construct() {
		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		require_once AAA_MOBILE_TRIGGER_DIR . '/class-aaa-chinese-mobile-trigger-loader.php';

		// Include front end files.
		if ( ! is_admin() || Astra_Builder_Customizer::astra_collect_customizer_builder_data() ) {
			require_once AAA_MOBILE_TRIGGER_DIR . '/dynamic-css/dynamic.css.php';
		}
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}
}

/**
 *  Kicking this off by creating an object.
 */
new Astra_Mobile_Trigger();
