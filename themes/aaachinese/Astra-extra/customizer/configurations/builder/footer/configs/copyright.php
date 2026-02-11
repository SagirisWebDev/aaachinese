<?php
/**
 * Copyright footer Configuration.
 *
 * @package     AAA Chinese
 * @link        https://wpastra.com/
 * @since       4.5.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register copyright footer builder Customizer Configurations.
 *
 * @since 4.5.2
 * @return array AAA Chinese Customizer Configurations with updated configurations.
 */
function aaa_chinese_copyright_footer_configuration() {
	$_section = 'section-footer-copyright';
	$_configs = array(

		/*
		* Footer Builder section
		*/
		array(
			'name'     => $_section,
			'type'     => 'section',
			'priority' => 5,
			'title'    => __( 'Copyright', 'astra' ),
			'panel'    => 'panel-footer-builder-group',
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
		 * Option: Footer Copyright Html Editor.
		 */
		array(
			'name'        => AAA_THEME_SETTINGS . '[footer-copyright-editor]',
			'type'        => 'control',
			'control'     => 'ast-html-editor',
			'section'     => $_section,
			'transport'   => 'postMessage',
			'priority'    => 4,
			'default'     => aaa_chinese_get_option( 'footer-copyright-editor', 'Copyright [copyright] [current_year] [site_title] | Powered by [theme_author]' ),
			'input_attrs' => array(
				'id' => 'ast-footer-copyright',
			),
			'partial'     => array(
				'selector'            => '.ast-footer-copyright',
				'container_inclusive' => true,
				'render_callback'     => array( AAA_Chinese_Builder_Footer::get_instance(), 'footer_copyright' ),
			),
			'context'     => AAA_Chinese_Builder_Helper::$general_tab,
			'divider'     => array( 'ast_class' => 'ast-section-spacing' ),
		),

		/**
		 * Option: Column Alignment
		 */
		array(
			'name'      => AAA_THEME_SETTINGS . '[footer-copyright-alignment]',
			'default'   => aaa_chinese_get_option( 'footer-copyright-alignment' ),
			'type'      => 'control',
			'control'   => 'ast-selector',
			'section'   => $_section,
			'priority'  => 6,
			'title'     => __( 'Alignment', 'astra' ),
			'context'   => AAA_Chinese_Builder_Helper::$general_tab,
			'transport' => 'postMessage',
			'choices'   => array(
				'left'   => 'align-left',
				'center' => 'align-center',
				'right'  => 'align-right',
			),
			'divider'   => array( 'ast_class' => 'ast-top-section-divider' ),
		),

		/**
		 * Option: Text Color.
		 */
		array(
			'name'              => AAA_THEME_SETTINGS . '[footer-copyright-color]',
			'default'           => aaa_chinese_get_option( 'footer-copyright-color' ),
			'type'              => 'control',
			'section'           => $_section,
			'priority'          => 8,
			'transport'         => 'postMessage',
			'control'           => 'ast-color',
			'sanitize_callback' => array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_alpha_color' ),
			'title'             => __( 'Text Color', 'astra' ),
			'context'           => AAA_Chinese_Builder_Helper::$design_tab,
			'divider'           => array( 'ast_class' => 'ast-bottom-section-divider' ),

		),

		/**
		 * Option: Divider
		 */
		array(
			'name'     => AAA_THEME_SETTINGS . '[' . $_section . '-margin-divider]',
			'section'  => $_section,
			'title'    => __( 'Spacing', 'astra' ),
			'type'     => 'control',
			'control'  => 'ast-heading',
			'priority' => 99,
			'settings' => array(),
			'context'  => AAA_Chinese_Builder_Helper::$design_tab,
			'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
		),

		/**
		 * Option: Margin Space
		 */
		array(
			'name'              => AAA_THEME_SETTINGS . '[' . $_section . '-margin]',
			'default'           => aaa_chinese_get_option( $_section . '-margin' ),
			'type'              => 'control',
			'transport'         => 'postMessage',
			'control'           => 'ast-responsive-spacing',
			'sanitize_callback' => array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
			'section'           => $_section,
			'priority'          => 220,
			'title'             => __( 'Margin', 'astra' ),
			'linked_choices'    => true,
			'unit_choices'      => array( 'px', 'em', '%' ),
			'choices'           => array(
				'top'    => __( 'Top', 'astra' ),
				'right'  => __( 'Right', 'astra' ),
				'bottom' => __( 'Bottom', 'astra' ),
				'left'   => __( 'Left', 'astra' ),
			),
			'context'           => AAA_Chinese_Builder_Helper::$design_tab,
			'divider'           => array( 'ast_class' => 'ast-section-spacing' ),
		),
	);

	$_configs = array_merge( $_configs, AAA_Chinese_Builder_Base_Configuration::prepare_typography_options( $_section ) );

	$_configs = array_merge( $_configs, AAA_Chinese_Builder_Base_Configuration::prepare_visibility_tab( $_section, 'footer' ) );

	if ( AAA_Chinese_Builder_Customizer::aaa_chinese_collect_customizer_builder_data() ) {
		array_map( 'aaa_chinese_save_footer_customizer_configs', $_configs );
	}

	return $_configs;
}

if ( AAA_Chinese_Builder_Customizer::aaa_chinese_collect_customizer_builder_data() ) {
	add_action( 'init', 'aaa_chinese_copyright_footer_configuration', 10, 0 );
}
