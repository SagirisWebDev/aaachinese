<?php
/**
 * Admin functions - Functions that add some functionality to WordPress admin panel
 *
 * @package AAA Chinese
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Register menus
 */
if ( ! function_exists( 'aaa_chinese_register_menu_locations' ) ) {

	/**
	 * Register menus
	 *
	 * @since 1.0.0
	 */
	function aaa_chinese_register_menu_locations() {

		/**
		 * Primary Menus
		 */
		register_nav_menus(
			array(
				'primary' => esc_html__( 'Primary Menu', 'astra' ),
			)
		);

		if ( true === AAA_Chinese_Builder_Helper::$is_header_footer_builder_active ) {

			/**
			 * Register the Secondary & Mobile menus.
			 */
			register_nav_menus(
				array(
					'secondary_menu' => esc_html__( 'Secondary Menu', 'astra' ),
					'mobile_menu'    => esc_html__( 'Off-Canvas Menu', 'astra' ),
				)
			);

			$component_limit = defined( 'AAA_EXT_VER' ) ? AAA_Chinese_Builder_Helper::$component_limit : AAA_Chinese_Builder_Helper::$num_of_header_menu;

			for ( $index = 3; $index <= $component_limit; $index++ ) {

				if ( ! is_customize_preview() && ! AAA_Chinese_Builder_Helper::is_component_loaded( 'menu-' . $index ) ) {
					continue;
				}

				register_nav_menus(
					array(
						'menu_' . $index => esc_html__( 'Menu ', 'astra' ) . $index,
					)
				);
			}

			/**
			 * Register the Account menus.
			 */
			register_nav_menus(
				array(
					'loggedin_account_menu' => esc_html__( 'Logged In Account Menu', 'astra' ),
				)
			);

		}

		/**
		 * Footer Menus
		 */
		register_nav_menus(
			array(
				'footer_menu' => esc_html__( 'Footer Menu', 'astra' ),
			)
		);
	}
}

add_action( 'init', 'aaa_chinese_register_menu_locations' );
