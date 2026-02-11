<?php
/**
 * [Header] options for astra theme.
 *
 * @package     AAA Chinese Header Footer Builder
 * @link        https://www.brainstormforce.com
 * @since       3.0.0
 */

// Block direct access to the file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'AAA_Chinese_Customizer_Config_Base' ) ) {

	/**
	 * Register below header Configurations.
	 */
	class AAA_Chinese_Header_Button_Component_Configs extends AAA_Chinese_Customizer_Config_Base {
		/**
		 * Register Button control for Header/Footer Customizer Configurations.
		 *
		 * @param Array                $configurations AAA Chinese Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 3.0.0
		 * @return Array AAA Chinese Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {
			return aaa_chinese_header_button_configuration( $configurations );
		}
	}

	new AAA_Chinese_Header_Button_Component_Configs();
}
