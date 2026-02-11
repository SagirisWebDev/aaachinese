<?php
/**
 * Register customizer Aspra Pro Section.
 *
 * @package   AAA Chinese
 * @link      https://wpastra.com/
 * @since     AAA Chinese 1.0.10
 */

if ( ! class_exists( 'AAA_Chinese_Pro_Upgrade_Link_Configs' ) ) {

	/**
	 * Register Button Customizer Configurations.
	 */
	class AAA_Chinese_Pro_Upgrade_Link_Configs extends AAA_Chinese_Customizer_Config_Base {
		/**
		 * Register Button Customizer Configurations.
		 *
		 * @param Array                $configurations AAA Chinese Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array AAA Chinese Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(
				array(
					'name'             => 'astra-pro',
					'type'             => 'section',
					'ast_type'         => 'astra-pro',
					'title'            => esc_html__( 'More Options Available in AAA Chinese Pro!', 'astra' ),
					'pro_url'          => aaa_chinese_get_upgrade_url( 'pricing' ),
					'priority'         => 1,
					'section_callback' => 'AAA_Chinese_Pro_Customizer',
				),

				array(
					'name'      => AAA_THEME_SETTINGS . '[astra-pro-section-notice]',
					'type'      => 'control',
					'transport' => 'postMessage',
					'control'   => 'ast-hidden',
					'section'   => 'astra-pro',
					'priority'  => 0,
				),

			);

			return array_merge( $configurations, $_configs );
		}
	}
}

new AAA_Chinese_Pro_Upgrade_Link_Configs();
