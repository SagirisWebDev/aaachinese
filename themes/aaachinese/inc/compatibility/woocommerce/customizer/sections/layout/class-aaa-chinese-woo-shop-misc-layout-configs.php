<?php
/**
 * WooCommerce Options for AAA Chinese Theme.
 *
 * @package     AAA Chinese
 *
 * @since       AAA Chinese 3.9.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AAA_Chinese_Woo_Shop_Misc_Layout_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class AAA_Chinese_Woo_Shop_Misc_Layout_Configs extends AAA_Chinese_Customizer_Config_Base {
		/**
		 * Register AAA Chinese-WooCommerce Misc Customizer Configurations.
		 *
		 * @param Array                $configurations AAA Chinese Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 3.9.2
		 * @return Array AAA Chinese Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Enable Quantity Plus and Minus.
				 */
				array(
					'name'        => AAA_THEME_SETTINGS . '[single-product-plus-minus-button]',
					'default'     => aaa_chinese_get_option( 'single-product-plus-minus-button' ),
					'type'        => 'control',
					'section'     => 'section-woo-misc',
					'title'       => __( 'Enable Quantity Plus and Minus', 'astra' ),
					'description' => __( 'Adds plus and minus buttons besides product quantity', 'astra' ),
					'priority'    => 59,
					'control'     => 'ast-toggle-control',
				),

			);

			/**
			 * Option: Adds tabs only if astra addons is enabled.
			 */
			if ( aaa_chinese_has_pro_woocommerce_addon() ) {
				$_configs[] = array(
					'name'        => 'section-woo-general-tabs',
					'section'     => 'section-woo-misc',
					'type'        => 'control',
					'control'     => 'ast-builder-header-control',
					'priority'    => 0,
					'description' => '',
				);
			}

			if ( aaa_chinese_showcase_upgrade_notices() ) {
				// Learn More link if AAA Chinese Pro is not activated.
				$_configs[] = array(
					'name'      => AAA_THEME_SETTINGS . '[ast-woo-misc-pro-items]',
					'type'      => 'control',
					'control'   => 'ast-upgrade',
					'campaign'  => 'woocommerce',
					'choices'   => array(
						// 'two'   => array(
						// 'title' => __( 'Modern input style', 'astra' ),
						// ),
						// 'one'   => array(
						// 'title' => __( 'Sale badge modifications', 'astra' ),
						// ),
						// 'three' => array(
						// 'title' => __( 'Ecommerce steps navigation', 'astra' ),
						// ),
						// 'four'  => array(
						// 'title' => __( 'Quantity updater designs', 'astra' ),
						// ),
						// 'five'  => array(
						// 'title' => __( 'Modern my-account page', 'astra' ),
						// ),
						// 'six'   => array(
						// 'title' => __( 'Downloads, Orders grid view', 'astra' ),
						// ),
						// 'seven' => array(
						// 'title' => __( 'Modern thank-you page design', 'astra' ),
						// ),
						'one'   => array(
							'title' => __( 'Advanced Input Field Styles & Border Radius', 'astra' ),
						),
						'two'   => array(
							'title' => __( 'Custom Coupon Text & Step Navigation', 'astra' ),
						),
						'three' => array(
							'title' => __( 'Quantity Plus and Minus Buttons', 'astra' ),
						),
					),
					'section'   => 'section-woo-misc',
					'default'   => '',
					'priority'  => 999,
					'title'     => __( 'Get Sleek Storefront. Better UX', 'astra' ),
					'divider'   => array( 'ast_class' => 'ast-top-section-divider' ),
					'context'   => array(),
					'thumbnail' => AAA_THEME_URI . 'inc/assets/images/customizer/woo-misc.png',
				);
			}

			return array_merge( $configurations, $_configs );
		}
	}
}

new AAA_Chinese_Woo_Shop_Misc_Layout_Configs();
