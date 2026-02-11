<?php
/**
 * Container Options for AAA Chinese theme.
 *
 * @package     AAA Chinese
 * @link        https://www.brainstormforce.com
 * @since       AAA Chinese 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AAA_Chinese_Woo_Shop_Container_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class AAA_Chinese_Woo_Shop_Container_Configs extends AAA_Chinese_Customizer_Config_Base {
		/**
		 * Register AAA Chinese-WooCommerce Shop Container Settings.
		 *
		 * @param Array                $configurations AAA Chinese Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array AAA Chinese Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Revamped Container Layout.
				 */
				array(
					'name'              => AAA_THEME_SETTINGS . '[woocommerce-ast-content-layout]',
					'type'              => 'control',
					'control'           => 'ast-radio-image',
					'sanitize_callback' => array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_choices' ),
					'section'           => 'section-woo-general',
					'default'           => aaa_chinese_get_option( 'woocommerce-ast-content-layout' ),
					'priority'          => 5,
					'title'             => __( 'Container Layout', 'astra' ),
					'choices'           => array(
						'default'                => array(
							'label' => __( 'Default', 'astra' ),
							'path'  => class_exists( 'AAA_Chinese_Builder_UI_Controller' ) ? AAA_Chinese_Builder_UI_Controller::fetch_svg_icon( 'layout-default', false ) : '',
						),
						'normal-width-container' => array(
							'label' => __( 'Normal', 'astra' ),
							'path'  => class_exists( 'AAA_Chinese_Builder_UI_Controller' ) ? AAA_Chinese_Builder_UI_Controller::fetch_svg_icon( 'normal-width-container', false ) : '',
						),
						'full-width-container'   => array(
							'label' => __( 'Full Width', 'astra' ),
							'path'  => class_exists( 'AAA_Chinese_Builder_UI_Controller' ) ? AAA_Chinese_Builder_UI_Controller::fetch_svg_icon( 'full-width-container', false ) : '',
						),
					),
					'divider'           => array( 'ast_class' => 'ast-section-spacing ast-bottom-spacing' ),
				),

				/**
				 * Option: Content Style Option.
				 */
				array(
					'name'        => AAA_THEME_SETTINGS . '[woocommerce-content-style]',
					'type'        => 'control',
					'control'     => 'ast-selector',
					'section'     => 'section-woo-general',
					'default'     => aaa_chinese_get_option( 'woocommerce-content-style', 'default' ),
					'priority'    => 5,
					'title'       => __( 'Container Style', 'astra' ),
					'description' => __( 'Container style will apply only when layout is set to either normal or narrow.', 'astra' ),
					'choices'     => array(
						'default' => __( 'Default', 'astra' ),
						'unboxed' => __( 'Unboxed', 'astra' ),
						'boxed'   => __( 'Boxed', 'astra' ),
					),
					'renderAs'    => 'text',
					'responsive'  => false,
					'divider'     => array( 'ast_class' => 'ast-top-divider' ),
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

new AAA_Chinese_Woo_Shop_Container_Configs();
