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

if ( ! class_exists( 'AAA_Chinese_Body_Colors_Configs' ) ) {

	/**
	 * Register Body Color Customizer Configurations.
	 */
	class AAA_Chinese_Body_Colors_Configs extends AAA_Chinese_Customizer_Config_Base {
		/**
		 * Register Body Color Customizer Configurations.
		 *
		 * @param Array                $configurations AAA Chinese Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array AAA Chinese Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_section = 'section-colors-background';

			if ( class_exists( 'AAA_Chinese_Ext_Extension' ) && AAA_Chinese_Ext_Extension::is_active( 'colors-and-background' ) && ! aaa_chinese_has_gcp_typo_preset_compatibility() ) {
				$_section = 'section-colors-body';
			}

			$_configs = array(
				array(
					'name'      => AAA_THEME_SETTINGS . '[global-color-palette]',
					'type'      => 'control',
					'control'   => 'ast-hidden',
					'section'   => $_section,
					'priority'  => 5,
					'title'     => __( 'Global Palette', 'astra' ),
					'default'   => aaa_chinese_get_option( 'global-color-palette' ),
					'transport' => 'postMessage',
				),

				array(
					'name'      => 'astra-color-palettes',
					'type'      => 'control',
					'control'   => 'ast-color-palette',
					'section'   => $_section,
					'priority'  => 5,
					'title'     => __( 'Global Palette', 'astra' ),
					'default'   => aaa_chinese_get_palette_colors(),
					'transport' => 'postMessage',
					'divider'   => array( 'ast_class' => 'ast-section-spacing ast-bottom-section-divider' ),
				),

				/**
				 * Option: Theme color heading
				 */
				array(
					'name'        => AAA_THEME_SETTINGS . '[theme-color-divider-reset]',
					'section'     => $_section,
					'title'       => __( 'Theme Color', 'astra' ),
					'type'        => 'control',
					'control'     => 'ast-group-title',
					'priority'    => 5,
					'settings'    => array(),
					'input_attrs' => array(
						'reset_linked_controls' => array(
							'theme-color',
							'link-color',
							'link-h-color',
							'heading-base-color',
							'text-color',
							'border-color',
						),
					),
				),

				/**
				 * Option: Theme Color
				 */
				array(
					'name'     => AAA_THEME_SETTINGS . '[theme-color]',
					'type'     => 'control',
					'control'  => 'ast-color',
					'section'  => $_section,
					'default'  => aaa_chinese_get_option( 'theme-color' ),
					'priority' => 5,
					'title'    => __( 'Accent', 'astra' ),
				),

				/**
				 * Option: Link Colors group.
				 */
				array(
					'name'       => AAA_THEME_SETTINGS . '[base-link-colors-group]',
					'default'    => aaa_chinese_get_option( 'base-link-colors-group' ),
					'type'       => 'control',
					'control'    => 'ast-color-group',
					'title'      => __( 'Links', 'astra' ),
					'section'    => $_section,
					'transport'  => 'postMessage',
					'priority'   => 5,
					'responsive' => false,
				),

				array(
					'name'     => 'link-color',
					'parent'   => AAA_THEME_SETTINGS . '[base-link-colors-group]',
					'section'  => $_section,
					'type'     => 'sub-control',
					'control'  => 'ast-color',
					'default'  => aaa_chinese_get_option( 'link-color' ),
					'priority' => 5,
					'title'    => __( 'Normal', 'astra' ),
				),

				/**
				 * Option: Link Hover Color
				 */
				array(
					'name'     => 'link-h-color',
					'parent'   => AAA_THEME_SETTINGS . '[base-link-colors-group]',
					'section'  => $_section,
					'default'  => aaa_chinese_get_option( 'link-h-color' ),
					'type'     => 'sub-control',
					'control'  => 'ast-color',
					'priority' => 10,
					'title'    => __( 'Hover', 'astra' ),
				),

				/**
				 * Option: Text Color
				 */
				array(
					'name'     => AAA_THEME_SETTINGS . '[text-color]',
					'default'  => aaa_chinese_get_option( 'text-color' ),
					'type'     => 'control',
					'control'  => 'ast-color',
					'section'  => $_section,
					'priority' => 6,
					'title'    => __( 'Body Text', 'astra' ),
				),

				/**
				 * Option: Text Color
				 */
				array(
					'name'     => AAA_THEME_SETTINGS . '[border-color]',
					'default'  => aaa_chinese_get_option( 'border-color' ),
					'type'     => 'control',
					'control'  => 'ast-color',
					'section'  => $_section,
					'priority' => 6,
					'title'    => __( 'Borders', 'astra' ),
					'divider'  => array( 'ast_class' => 'ast-bottom-divider' ),
				),

			);

			return array_merge( $configurations, $_configs );
		}
	}
}

new AAA_Chinese_Body_Colors_Configs();
