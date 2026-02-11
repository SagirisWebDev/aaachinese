<?php
/**
 * AAA Chinese Theme Customizer Configuration Site Identity.
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
	 * Register Site Identity Customizer Configurations.
	 *
	 * @since 3.0.0
	 */
	class AAA_Chinese_Customizer_Site_Identity_Configs extends AAA_Chinese_Customizer_Config_Base {
		/**
		 * Register Builder Site Identity Customizer Configurations.
		 *
		 * @param Array                $configurations AAA Chinese Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 3.0.0
		 * @return Array AAA Chinese Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {
			$_configs = aaa_chinese_header_site_identity_configuration();

			$wp_customize->remove_control( 'astra-settings[divider-section-site-identity-logo]' );

			return array_merge( $configurations, $_configs );
		}
	}

	/**
	 * Kicking this off by creating object of this class.
	 */
	new AAA_Chinese_Customizer_Site_Identity_Configs();
}
