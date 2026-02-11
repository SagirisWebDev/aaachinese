<?php
/**
 * AAA Chinese Theme Customizer Configuration Off Canvas.
 *
 * @package     astra-builder
 * @link        https://wpastra.com/
 * @since       3.0.0
 */

// No direct access, please.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'AAA_Chinese_Customizer_Config_Base' ) ) {

	/**
	 * Register Off Canvas Customizer Configurations.
	 *
	 * @since 3.0.0
	 */
	class AAA_Chinese_Customizer_Off_Canvas_Configs extends AAA_Chinese_Customizer_Config_Base {
		/**
		 * Register Builder Above Customizer Configurations.
		 *
		 * @param Array                $configurations AAA Chinese Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 3.0.0
		 * @return Array AAA Chinese Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {
			$_configs = aaa_chinese_header_off_canvas_configuration();
			return array_merge( $configurations, $_configs );
		}
	}

	/**
	 * Kicking this off by creating object of this class.
	 */
	new AAA_Chinese_Customizer_Off_Canvas_Configs();
}
