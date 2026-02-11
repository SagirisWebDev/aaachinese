<?php
/**
 * Register customizer panels & sections fro Woocommerce.
 *
 * @package     AAA Chinese
 *
 * @since       AAA Chinese 1.1.0
 * @since       1.4.6 Chnaged to using AAA_Chinese_Customizer API
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AAA_Chinese_Customizer_Register_Woo_Section' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class AAA_Chinese_Customizer_Register_Woo_Section extends AAA_Chinese_Customizer_Config_Base {
		/**
		 * Register Panels and Sections for Customizer.
		 *
		 * @param Array                $configurations AAA Chinese Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array AAA Chinese Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$configs = array(

				array(
					'name'     => 'section-woo-shop',
					'title'    => __( 'Shop', 'astra' ),
					'type'     => 'section',
					'priority' => 20,
					'panel'    => 'woocommerce',
				),

				array(
					'name'     => 'section-woo-shop-single',
					'type'     => 'section',
					'title'    => __( 'Single Product', 'astra' ),
					'priority' => 12,
					'panel'    => 'woocommerce',
				),

				array(
					'name'     => 'section-woo-shop-cart',
					'type'     => 'section',
					'title'    => __( 'Cart', 'astra' ),
					'priority' => 20,
					'panel'    => 'woocommerce',
				),

				array(
					'name'     => 'section-woo-general',
					'title'    => __( 'General', 'astra' ),
					'type'     => 'section',
					'priority' => 10,
					'panel'    => 'woocommerce',
				),

				array(
					'name'     => 'section-woo-misc',
					'title'    => __( 'Misc', 'astra' ),
					'type'     => 'section',
					'priority' => 24.5,
					'panel'    => 'woocommerce',
				),
			);

			return array_merge( $configurations, $configs );
		}
	}
}

new AAA_Chinese_Customizer_Register_Woo_Section();
