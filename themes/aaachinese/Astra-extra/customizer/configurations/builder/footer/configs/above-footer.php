<?php
/**
 * Above footer Configuration.
 *
 * @package     AAA Chinese
 * @link        https://wpastra.com/
 * @since       4.5.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Above footer builder Customizer Configurations.
 *
 * @since 4.5.2
 * @return array AAA Chinese Customizer Configurations with updated configurations.
 */
function aaa_chinese_above_footer_configuration() {
	$_section = 'section-above-footer-builder';

	$column_count = range( 1, AAA_Chinese_Builder_Helper::$num_of_footer_columns );
	$column_count = array_combine( $column_count, $column_count );

	$_configs = array(

		// Section: Above Footer.
		array(
			'name'     => $_section,
			'type'     => 'section',
			'title'    => __( 'Above Footer', 'astra' ),
			'panel'    => 'panel-footer-builder-group',
			'priority' => 30,
		),

		/**
		 * Option: Footer Builder Tabs
		 */
		array(
			'name'        => $_section . '-ast-context-tabs',
			'section'     => $_section,
			'type'        => 'control',
			'control'     => 'ast-builder-header-control',
			'priority'    => 0,
			'description' => '',
		),

		/**
		 * Option: Column count
		 */

		array(
			'name'       => AAA_THEME_SETTINGS . '[hba-footer-column]',
			'default'    => aaa_chinese_get_option( 'hba-footer-column' ),
			'type'       => 'control',
			'control'    => 'ast-selector',
			'section'    => $_section,
			'priority'   => 2,
			'title'      => __( 'Column', 'astra' ),
			'choices'    => $column_count,
			'context'    => AAA_Chinese_Builder_Helper::$general_tab,
			'transport'  => 'postMessage',
			'renderAs'   => 'text',
			'partial'    => array(
				'selector'            => '.site-above-footer-wrap',
				'container_inclusive' => false,
				'render_callback'     => array( AAA_Chinese_Builder_Footer::get_instance(), 'above_footer' ),
			),
			'responsive' => false,
			'divider'    => array( 'ast_class' => 'ast-section-spacing' ),
		),

		/**
		 * Option: Row Layout
		 */
		array(
			'name'        => AAA_THEME_SETTINGS . '[hba-footer-layout]',
			'section'     => $_section,
			'default'     => aaa_chinese_get_option( 'hba-footer-layout' ),
			'priority'    => 3,
			'title'       => __( 'Layout', 'astra' ),
			'type'        => 'control',
			'control'     => 'ast-row-layout',
			'context'     => AAA_Chinese_Builder_Helper::$general_tab,
			'input_attrs' => array(
				'responsive' => true,
				'footer'     => 'above',
				'layout'     => AAA_Chinese_Builder_Helper::$footer_row_layouts,
			),
			'divider'     => array( 'ast_class' => 'ast-top-divider ast-bottom-section-divider' ),
			'transport'   => 'postMessage',
		),

		/**
		 * Option: Layout Width
		 */
		array(
			'name'       => AAA_THEME_SETTINGS . '[hba-footer-layout-width]',
			'default'    => aaa_chinese_get_option( 'hba-footer-layout-width' ),
			'type'       => 'control',
			'control'    => 'ast-selector',
			'section'    => $_section,
			'priority'   => 25,
			'title'      => __( 'Width', 'astra' ),
			'choices'    => array(
				'full'    => __( 'Full Width', 'astra' ),
				'content' => __( 'Content Width', 'astra' ),
			),
			'suffix'     => '',
			'context'    => AAA_Chinese_Builder_Helper::$general_tab,
			'transport'  => 'postMessage',
			'renderAs'   => 'text',
			'responsive' => false,
			'divider'    => array( 'ast_class' => 'ast-bottom-section-divider' ),
		),

		// Section: Above Footer Height.
		array(
			'name'        => AAA_THEME_SETTINGS . '[hba-footer-height]',
			'section'     => $_section,
			'transport'   => 'refresh',
			'default'     => aaa_chinese_get_option( 'hba-footer-height' ),
			'priority'    => 30,
			'title'       => __( 'Height', 'astra' ),
			'suffix'      => 'px',
			'type'        => 'control',
			'control'     => 'ast-slider',
			'input_attrs' => array(
				'min'  => 30,
				'step' => 1,
				'max'  => 600,
			),
			'divider'     => array( 'ast_class' => 'ast-bottom-section-divider' ),
			'context'     => AAA_Chinese_Builder_Helper::$general_tab,
		),

		/**
		 * Option: Vertical Alignment
		 */
		array(
			'name'       => AAA_THEME_SETTINGS . '[hba-footer-vertical-alignment]',
			'default'    => aaa_chinese_get_option( 'hba-footer-vertical-alignment' ),
			'type'       => 'control',
			'control'    => 'ast-selector',
			'section'    => $_section,
			'priority'   => 34,
			'title'      => __( 'Vertical Alignment', 'astra' ),
			'choices'    => array(
				'flex-start' => __( 'Top', 'astra' ),
				'center'     => __( 'Middle', 'astra' ),
				'flex-end'   => __( 'Bottom', 'astra' ),
			),
			'context'    => AAA_Chinese_Builder_Helper::$general_tab,
			'transport'  => 'postMessage',
			'renderAs'   => 'text',
			'responsive' => false,
		),

		array(
			'name'     => AAA_THEME_SETTINGS . '[hba-stack]',
			'default'  => aaa_chinese_get_option( 'hba-stack' ),
			'type'     => 'control',
			'control'  => 'ast-selector',
			'section'  => $_section,
			'priority' => 5,
			'title'    => __( 'Inner Elements Layout', 'astra' ),
			'choices'  => array(
				'stack'  => __( 'Stack', 'astra' ),
				'inline' => __( 'Inline', 'astra' ),
			),
			'context'  => AAA_Chinese_Builder_Helper::$general_tab,
			'renderAs' => 'text',
			'divider'  => array( 'ast_class' => 'ast-bottom-section-divider' ),
		),

		// Section: Above Footer Border.
		array(
			'name'        => AAA_THEME_SETTINGS . '[hba-footer-separator]',
			'section'     => $_section,
			'priority'    => 40,
			'transport'   => 'postMessage',
			'default'     => aaa_chinese_get_option( 'hba-footer-separator' ),
			'title'       => __( 'Top Border Size', 'astra' ),
			'suffix'      => 'px',
			'type'        => 'control',
			'control'     => 'ast-slider',
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1,
				'max'  => 600,
			),
			'divider'     => array( 'ast_class' => 'ast-section-spacing ast-bottom-divider' ),
			'context'     => AAA_Chinese_Builder_Helper::$design_tab,
		),

		// Section: Above Footer Border Color.
		array(
			'name'              => AAA_THEME_SETTINGS . '[hba-footer-top-border-color]',
			'transport'         => 'postMessage',
			'default'           => aaa_chinese_get_option( 'hba-footer-top-border-color' ),
			'type'              => 'control',
			'control'           => 'ast-color',
			'sanitize_callback' => array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_alpha_color' ),
			'section'           => $_section,
			'priority'          => 45,
			'title'             => __( 'Border Color', 'astra' ),
			'context'           => array(
				AAA_Chinese_Builder_Helper::$design_tab_config,
				array(
					'setting'  => AAA_THEME_SETTINGS . '[hba-footer-separator]',
					'operator' => '>=',
					'value'    => 1,
				),
			),
			'divider'           => array( 'ast_class' => 'ast-bottom-section-divider' ),
		),

		// Option: Above Footer Background styling.
		array(
			'name'      => AAA_THEME_SETTINGS . '[hba-footer-bg-obj-responsive]',
			'type'      => 'control',
			'section'   => $_section,
			'control'   => 'ast-responsive-background',
			'transport' => 'postMessage',
			'default'   => aaa_chinese_get_option( 'hba-footer-bg-obj-responsive' ),
			'title'     => __( 'Background', 'astra' ),
			'priority'  => 70,
			'context'   => AAA_Chinese_Builder_Helper::$design_tab,
			'divider'   => array( 'ast_class' => 'ast-bottom-section-divider' ),
		),

		/**
		 * Option: Inner Spacing
		 */
		array(
			'name'              => AAA_THEME_SETTINGS . '[hba-inner-spacing]',
			'section'           => $_section,
			'priority'          => 205,
			'transport'         => 'postMessage',
			'default'           => aaa_chinese_get_option( 'hba-inner-spacing' ),
			'title'             => __( 'Inner Column Spacing', 'astra' ),
			'suffix'            => 'px',
			'type'              => 'control',
			'control'           => 'ast-responsive-slider',
			'sanitize_callback' => array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
			'input_attrs'       => array(
				'min'  => 0,
				'step' => 1,
				'max'  => 200,
			),
			'context'           => AAA_Chinese_Builder_Helper::$design_tab,
		),
	);

	$_configs = array_merge( $_configs, AAA_Chinese_Extended_Base_Configuration::prepare_advanced_tab( $_section ) );

	$_configs = array_merge( $_configs, AAA_Chinese_Builder_Base_Configuration::prepare_visibility_tab( $_section, 'footer' ) );

	if ( AAA_Chinese_Builder_Customizer::aaa_chinese_collect_customizer_builder_data() ) {
		array_map( 'aaa_chinese_save_footer_customizer_configs', $_configs );
	}

	return $_configs;
}

if ( AAA_Chinese_Builder_Customizer::aaa_chinese_collect_customizer_builder_data() ) {
	add_action( 'init', 'aaa_chinese_above_footer_configuration' );
}
