<?php
/**
 * Below Header Configuration.
 *
 * @package     AAA Chinese
 * @link        https://wpastra.com/
 * @since       4.5.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register below header builder Customizer Configurations.
 *
 * @since 4.5.2
 * @return array AAA Chinese Customizer Configurations with updated configurations.
 */
function aaa_chinese_below_header_configuration() {
	$_section = 'section-below-header-builder';

	$_configs = array(

		// Section: Below Header.
		array(
			'name'     => $_section,
			'type'     => 'section',
			'title'    => __( 'Below Header', 'astra' ),
			'panel'    => 'panel-header-builder-group',
			'priority' => 30,
		),

		/**
		 * Option: Header Builder Tabs
		 */
		array(
			'name'        => $_section . '-ast-context-tabs',
			'section'     => $_section,
			'type'        => 'control',
			'control'     => 'ast-builder-header-control',
			'priority'    => 0,
			'description' => '',
		),

		// Section: Below Header Height.
		array(
			'name'              => AAA_THEME_SETTINGS . '[hbb-header-height]',
			'section'           => $_section,
			'transport'         => 'postMessage',
			'default'           => aaa_chinese_get_option( 'hbb-header-height' ),
			'priority'          => 30,
			'title'             => __( 'Height', 'astra' ),
			'type'              => 'control',
			'control'           => 'ast-responsive-slider',
			'sanitize_callback' => array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
			'suffix'            => 'px',
			'input_attrs'       => array(
				'min'  => 30,
				'step' => 1,
				'max'  => 600,
			),
			'context'           => AAA_Chinese_Builder_Helper::$general_tab,
			'divider'           => array( 'ast_class' => 'ast-section-spacing' ),
		),

		// Option: Below Header Background styling.
		array(
			'name'        => AAA_THEME_SETTINGS . '[hbb-header-bg-obj-responsive]',
			'type'        => 'control',
			'section'     => $_section,
			'control'     => 'ast-responsive-background',
			'transport'   => 'postMessage',
			'default'     => aaa_chinese_get_option( 'hbb-header-bg-obj-responsive' ),
			'title'       => __( 'Background', 'astra' ),
			'description' => __( 'It would not be effective if transparent header is enabled.', 'astra' ),
			'priority'    => 40,
			'context'     => AAA_Chinese_Builder_Helper::$design_tab,
			'divider'     => array( 'ast_class' => 'ast-section-spacing' ),
		),

		// Section: Below Header Border Color.
		array(
			'name'              => AAA_THEME_SETTINGS . '[hbb-header-bottom-border-color]',
			'transport'         => 'postMessage',
			'default'           => aaa_chinese_get_option( 'hbb-header-bottom-border-color' ),
			'type'              => 'control',
			'control'           => 'ast-color',
			'sanitize_callback' => array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_alpha_color' ),
			'section'           => $_section,
			'priority'          => 40,
			'title'             => __( 'Bottom Border Color', 'astra' ),
			'context'           => array(
				array(
					'setting'  => AAA_THEME_SETTINGS . '[hbb-header-separator]',
					'operator' => '>=',
					'value'    => 1,
				),
				AAA_Chinese_Builder_Helper::$design_tab_config,
			),
		),

		// Section: Below Header Border.
		array(
			'name'        => AAA_THEME_SETTINGS . '[hbb-header-separator]',
			'section'     => $_section,
			'priority'    => 40,
			'transport'   => 'postMessage',
			'default'     => aaa_chinese_get_option( 'hbb-header-separator' ),
			'title'       => __( 'Bottom Border Size', 'astra' ),
			'type'        => 'control',
			'control'     => 'ast-slider',
			'suffix'      => 'px',
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1,
				'max'  => 10,
			),
			'context'     => AAA_Chinese_Builder_Helper::$design_tab,
			'divider'     => array( 'ast_class' => 'ast-top-section-divider' ),
		),

	);

	$_configs = array_merge( $_configs, AAA_Chinese_Extended_Base_Configuration::prepare_advanced_tab( $_section ) );

	$_configs = array_merge( $_configs, AAA_Chinese_Builder_Base_Configuration::prepare_visibility_tab( $_section ) );

	if ( AAA_Chinese_Builder_Customizer::aaa_chinese_collect_customizer_builder_data() ) {
		array_map( 'aaa_chinese_save_header_customizer_configs', $_configs );
	}

	return $_configs;
}

if ( AAA_Chinese_Builder_Customizer::aaa_chinese_collect_customizer_builder_data() ) {
	add_action( 'init', 'aaa_chinese_below_header_configuration' );
}
