<?php
/**
 * Bottom Footer Options for AAA Chinese Theme.
 *
 * @package     AAA Chinese
 *
 * @since       AAA Chinese 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AAA_Chinese_Sidebar_Layout_Configs' ) ) {

	/**
	 * Register AAA Chinese Sidebar Layout Configurations.
	 */
	class AAA_Chinese_Sidebar_Layout_Configs extends AAA_Chinese_Customizer_Config_Base {
		/**
		 * Register AAA Chinese Sidebar Layout Configurations.
		 *
		 * @param Array                $configurations AAA Chinese Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array AAA Chinese Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Default Sidebar Position
				 */
				array(
					'name'              => AAA_THEME_SETTINGS . '[site-sidebar-layout]',
					'type'              => 'control',
					'control'           => 'ast-radio-image',
					'sanitize_callback' => array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_choices' ),
					'section'           => 'section-sidebars',
					'default'           => aaa_chinese_get_option( 'site-sidebar-layout' ),
					'priority'          => 5,
					'description'       => __( 'Sidebar will only apply when container layout is set to normal.', 'astra' ),
					'title'             => __( 'Default Layout', 'astra' ),
					'choices'           => array(
						'no-sidebar'    => array(
							'label' => __( 'No Sidebar', 'astra' ),
							'path'  => class_exists( 'AAA_Chinese_Builder_UI_Controller' ) ? AAA_Chinese_Builder_UI_Controller::fetch_svg_icon( 'no-sidebar', false ) : '',
						),
						'left-sidebar'  => array(
							'label' => __( 'Left Sidebar', 'astra' ),
							'path'  => class_exists( 'AAA_Chinese_Builder_UI_Controller' ) ? AAA_Chinese_Builder_UI_Controller::fetch_svg_icon( 'left-sidebar', false ) : '',
						),
						'right-sidebar' => array(
							'label' => __( 'Right Sidebar', 'astra' ),
							'path'  => class_exists( 'AAA_Chinese_Builder_UI_Controller' ) ? AAA_Chinese_Builder_UI_Controller::fetch_svg_icon( 'right-sidebar', false ) : '',
						),
					),
				),

				/**
				 * Option: Site Sidebar Style.
				 */
				array(
					'name'       => AAA_THEME_SETTINGS . '[site-sidebar-style]',
					'type'       => 'control',
					'control'    => 'ast-selector',
					'section'    => 'section-sidebars',
					'default'    => aaa_chinese_get_option( 'site-sidebar-style', 'unboxed' ),
					'priority'   => 9,
					'title'      => __( 'Sidebar Style', 'astra' ),
					'choices'    => array(
						'unboxed' => __( 'Unboxed', 'astra' ),
						'boxed'   => __( 'Boxed', 'astra' ),
					),
					'responsive' => false,
					'renderAs'   => 'text',
					'divider'    => array( 'ast_class' => 'ast-top-divider ast-bottom-section-divider' ),
				),

				/**
				 * Option: Primary Content Width
				 */
				array(
					'name'        => AAA_THEME_SETTINGS . '[site-sidebar-width]',
					'type'        => 'control',
					'control'     => 'ast-slider',
					'default'     => aaa_chinese_get_option( 'site-sidebar-width' ),
					'section'     => 'section-sidebars',
					'priority'    => 15,
					'title'       => __( 'Sidebar Width', 'astra' ),
					'suffix'      => '%',
					'transport'   => 'postMessage',
					'input_attrs' => array(
						'min'  => 15,
						'step' => 1,
						'max'  => 50,
					),

				),

				array(
					'name'     => AAA_THEME_SETTINGS . '[site-sidebar-width-description]',
					'type'     => 'control',
					'control'  => 'ast-description',
					'section'  => 'section-sidebars',
					'priority' => 15,
					'title'    => '',
					'help'     => __( 'Sidebar width will apply only when one of the above sidebar is set.', 'astra' ),
					'divider'  => array( 'ast_class' => 'ast-bottom-section-divider' ),
					'settings' => array(),
				),

				/**
				 * Option: Sticky Sidebar
				 */
				array(
					'name'     => AAA_THEME_SETTINGS . '[site-sticky-sidebar]',
					'default'  => aaa_chinese_get_option( 'site-sticky-sidebar' ),
					'type'     => 'control',
					'section'  => 'section-sidebars',
					'title'    => __( 'Enable Sticky Sidebar', 'astra' ),
					'priority' => 15,
					'control'  => 'ast-toggle-control',
					'divider'  => array( 'ast_class' => 'ast-section-spacing' ),
				),
			);

			// Learn More link if AAA Chinese Pro is not activated.
			if ( aaa_chinese_showcase_upgrade_notices() ) {
				$_configs[] = array(
					'name'     => AAA_THEME_SETTINGS . '[ast-sidebar-pro-items]',
					'type'     => 'control',
					'control'  => 'ast-upgrade',
					'campaign' => 'sidebar',
					'choices'  => array(
						'one'   => array(
							'title' => __( 'Sidebar spacing', 'astra' ),
						),
						'two'   => array(
							'title' => __( 'Sidebar color options', 'astra' ),
						),
						'three' => array(
							'title' => __( 'Widget color options', 'astra' ),
						),
						'four'  => array(
							'title' => __( 'Widget title typography', 'astra' ),
						),
						'five'  => array(
							'title' => __( 'Widget content typography', 'astra' ),
						),
					),
					'section'  => 'section-sidebars',
					'default'  => '',
					'priority' => 999,
					'title'    => __( 'Make sidebars work harder to engage with AAA Chinese Pro', 'astra' ),
					'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
				);
			}

			return array_merge( $configurations, $_configs );
		}
	}
}

new AAA_Chinese_Sidebar_Layout_Configs();
