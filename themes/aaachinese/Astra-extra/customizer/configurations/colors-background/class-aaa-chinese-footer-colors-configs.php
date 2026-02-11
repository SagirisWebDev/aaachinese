<?php
/**
 * Styling Options for AAA Chinese Theme.
 *
 * @package     AAA Chinese
 *
 * @since       1.4.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AAA_Chinese_Footer_Colors_Configs' ) ) {

	/**
	 * Register Footer Color Configurations.
	 */
	class AAA_Chinese_Footer_Colors_Configs extends AAA_Chinese_Customizer_Config_Base {
		/**
		 * Register Footer Color Configurations.
		 *
		 * @param Array                $configurations AAA Chinese Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array AAA Chinese Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {
			$_configs = array(

				/**
				 * Option: Color
				 */
				array(
					'name'     => 'footer-color',
					'type'     => 'sub-control',
					'priority' => 5,
					'parent'   => AAA_THEME_SETTINGS . '[footer-bar-content-group]',
					'section'  => 'section-footer-small',
					'control'  => 'ast-color',
					'title'    => __( 'Text Color', 'astra' ),
					'default'  => aaa_chinese_get_option( 'footer-color' ),
				),

				/**
				 * Option: Link Color
				 */
				array(
					'name'     => 'footer-link-color',
					'type'     => 'sub-control',
					'priority' => 6,
					'parent'   => AAA_THEME_SETTINGS . '[footer-bar-link-color-group]',
					'section'  => 'section-footer-small',
					'control'  => 'ast-color',
					'default'  => aaa_chinese_get_option( 'footer-link-color' ),
					'title'    => __( 'Normal', 'astra' ),
				),

				/**
				 * Option: Link Hover Color
				 */
				array(
					'name'     => 'footer-link-h-color',
					'type'     => 'sub-control',
					'priority' => 5,
					'parent'   => AAA_THEME_SETTINGS . '[footer-bar-link-color-group]',
					'section'  => 'section-footer-small',
					'control'  => 'ast-color',
					'title'    => __( 'Hover', 'astra' ),
					'default'  => aaa_chinese_get_option( 'section-footer-small' ),
				),

				/**
				 * Option: Footer Background
				 */
				array(
					'name'              => 'footer-bg-obj',
					'type'              => 'sub-control',
					'priority'          => 7,
					'parent'            => AAA_THEME_SETTINGS . '[footer-bar-background-group]',
					'section'           => 'section-footer-small',
					'transport'         => 'postMessage',
					'control'           => 'ast-background',
					'sanitize_callback' => array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_background_obj' ),
					'default'           => aaa_chinese_get_option( 'footer-bg-obj' ),
					'label'             => __( 'Background', 'astra' ),
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

new AAA_Chinese_Footer_Colors_Configs();
