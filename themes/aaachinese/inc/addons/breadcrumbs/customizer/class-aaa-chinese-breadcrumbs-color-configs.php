<?php
/**
 * Colors - Breadcrumbs Options for theme.
 *
 * @package     AAA Chinese
 * @link        https://www.brainstormforce.com
 * @since       AAA Chinese 1.7.0
 */

// Block direct access to the file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Bail if Customizer config base class does not exist.
if ( ! class_exists( 'AAA_Chinese_Customizer_Config_Base' ) ) {
	return;
}

/**
 * Customizer Sanitizes
 *
 * @since 1.7.0
 */
if ( ! class_exists( 'AAA_Chinese_Breadcrumbs_Color_Configs' ) ) {

	/**
	 * Register Colors and Background - Breadcrumbs Options Customizer Configurations.
	 */
	class AAA_Chinese_Breadcrumbs_Color_Configs extends AAA_Chinese_Customizer_Config_Base {
		/**
		 * Register Colors and Background - Breadcrumbs Options Customizer Configurations.
		 *
		 * @param Array                $configurations AAA Chinese Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.7.0
		 * @return Array AAA Chinese Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Divider
				 */
				array(
					'name'     => AAA_THEME_SETTINGS . '[breadcrumb-color-section-divider]',
					'section'  => 'section-breadcumb',
					'title'    => __( 'Colors', 'astra' ),
					'type'     => 'control',
					'control'  => 'ast-heading',
					'priority' => 72,
					'divider'  => array( 'ast_class' => 'ast-bottom-spacing' ),
					'context'  => array(
						array(
							'setting'  => AAA_THEME_SETTINGS . '[breadcrumb-position]',
							'operator' => '!=',
							'value'    => 'none',
						),
						true === AAA_Chinese_Builder_Helper::$is_header_footer_builder_active ?
							AAA_Chinese_Builder_Helper::$design_tab_config : AAA_Chinese_Builder_Helper::$general_tab_config,
					),
				),

				/*
				 * Breadcrumb Color
				 */
				array(
					'name'       => AAA_THEME_SETTINGS . '[breadcrumb-bg-color]',
					'type'       => 'control',
					'default'    => aaa_chinese_get_option( 'breadcrumb-bg-color' ),
					'section'    => 'section-breadcrumb',
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Background Color', 'astra' ),
					'responsive' => true,
					'rgba'       => true,
					'context'    => array(
						array(
							'setting'  => AAA_THEME_SETTINGS . '[breadcrumb-position]',
							'operator' => '!=',
							'value'    => 'none',
						),
						true === AAA_Chinese_Builder_Helper::$is_header_footer_builder_active ?
							AAA_Chinese_Builder_Helper::$design_tab_config : AAA_Chinese_Builder_Helper::$general_tab_config,
					),
					'priority'   => 72,
				),
				array(
					'name'       => AAA_THEME_SETTINGS . '[breadcrumb-active-color-responsive]',
					'default'    => aaa_chinese_get_option( 'breadcrumb-active-color-responsive' ),
					'type'       => 'control',
					'section'    => 'section-breadcrumb',
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Text Color', 'astra' ),
					'responsive' => true,
					'rgba'       => true,
					'context'    => array(
						array(
							'setting'  => AAA_THEME_SETTINGS . '[breadcrumb-position]',
							'operator' => '!=',
							'value'    => 'none',
						),
						true === AAA_Chinese_Builder_Helper::$is_header_footer_builder_active ?
							AAA_Chinese_Builder_Helper::$design_tab_config : AAA_Chinese_Builder_Helper::$general_tab_config,
					),
					'priority'   => 72,
				),
				array(
					'name'       => AAA_THEME_SETTINGS . '[breadcrumb-separator-color]',
					'default'    => aaa_chinese_get_option( 'breadcrumb-separator-color' ),
					'type'       => 'control',
					'section'    => 'section-breadcrumb',
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Separator Color', 'astra' ),
					'responsive' => true,
					'rgba'       => true,
					'context'    => array(
						array(
							'setting'  => AAA_THEME_SETTINGS . '[breadcrumb-position]',
							'operator' => '!=',
							'value'    => 'none',
						),
						true === AAA_Chinese_Builder_Helper::$is_header_footer_builder_active ?
							AAA_Chinese_Builder_Helper::$design_tab_config : AAA_Chinese_Builder_Helper::$general_tab_config,
					),
					'priority'   => 72,
				),

				array(
					'name'       => AAA_THEME_SETTINGS . '[section-breadcrumb-link-color]',
					'default'    => aaa_chinese_get_option( 'section-breadcrumb-color' ),
					'type'       => 'control',
					'control'    => 'ast-color-group',
					'title'      => __( 'Content Link Color', 'astra' ),
					'section'    => 'section-breadcrumb',
					'transport'  => 'postMessage',
					'priority'   => 72,
					'context'    => array(
						array(
							'setting'  => AAA_THEME_SETTINGS . '[breadcrumb-position]',
							'operator' => '!=',
							'value'    => 'none',
						),
						true === AAA_Chinese_Builder_Helper::$is_header_footer_builder_active ?
							AAA_Chinese_Builder_Helper::$design_tab_config : AAA_Chinese_Builder_Helper::$general_tab_config,
					),
					'responsive' => true,
					'divider'    => array( 'ast_class' => 'ast-bottom-section-divider' ),
				),

				array(
					'name'       => 'breadcrumb-text-color-responsive',
					'default'    => aaa_chinese_get_option( 'breadcrumb-text-color-responsive' ),
					'type'       => 'sub-control',
					'parent'     => AAA_THEME_SETTINGS . '[section-breadcrumb-link-color]',
					'section'    => 'section-breadcrumb',
					'transport'  => 'postMessage',
					'tab'        => __( 'Normal', 'astra' ),
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Normal', 'astra' ),
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 15,
				),

				array(
					'name'       => 'breadcrumb-hover-color-responsive',
					'default'    => aaa_chinese_get_option( 'breadcrumb-hover-color-responsive' ),
					'type'       => 'sub-control',
					'parent'     => AAA_THEME_SETTINGS . '[section-breadcrumb-link-color]',
					'section'    => 'section-breadcrumb',
					'transport'  => 'postMessage',
					'tab'        => __( 'Hover', 'astra' ),
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Hover', 'astra' ),
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 20,
				),
			);

			if ( false === AAA_Chinese_Builder_Helper::$is_header_footer_builder_active ) {
				array_push(
					$_configs,
					/**
					 * Option: Divider
					 * Option: breadcrumb color Section divider
					 */
					array(
						'name'     => AAA_THEME_SETTINGS . '[section-breadcrumb-color-divider]',
						'type'     => 'control',
						'control'  => 'ast-heading',
						'section'  => 'section-breadcrumb',
						'title'    => __( 'Colors', 'astra' ),
						'priority' => 71,
						'settings' => array(),
						'context'  => array(
							array(
								'setting'  => AAA_THEME_SETTINGS . '[breadcrumb-position]',
								'operator' => '!=',
								'value'    => 'none',
							),
							AAA_Chinese_Builder_Helper::$general_tab_config,
						),
					)
				);
			}
			return array_merge( $configurations, $_configs );
		}
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
new AAA_Chinese_Breadcrumbs_Color_Configs();
