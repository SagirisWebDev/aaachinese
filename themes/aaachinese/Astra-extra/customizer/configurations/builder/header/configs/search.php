<?php
/**
 * Search Header Configuration.
 *
 * @package     AAA Chinese
 * @link        https://wpastra.com/
 * @since       4.5.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register search header builder Customizer Configurations.
 *
 * @since 4.5.2
 * @return array AAA Chinese Customizer Configurations with updated configurations.
 */
function aaa_chinese_header_search_configuration() {
	$_section = 'section-header-search';

	$_configs = array(

		/*
		* Header Builder section
		*/
		array(
			'name'     => $_section,
			'type'     => 'section',
			'priority' => 80,
			'title'    => __( 'Search', 'astra' ),
			'panel'    => 'panel-header-builder-group',
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

		/**
		 * Option: Search Color.
		 */
		array(
			'name'       => AAA_THEME_SETTINGS . '[header-search-icon-color]',
			'default'    => aaa_chinese_get_option( 'header-search-icon-color' ),
			'type'       => 'control',
			'section'    => $_section,
			'priority'   => 8,
			'transport'  => 'postMessage',
			'control'    => 'ast-responsive-color',
			'responsive' => true,
			'rgba'       => true,
			'title'      => __( 'Icon Color', 'astra' ),
			'context'    => AAA_Chinese_Builder_Helper::$design_tab,
			'divider'    => array( 'ast_class' => 'ast-section-spacing' ),
		),

		/**
		 * Option: Search Size
		 */
		array(
			'name'              => AAA_THEME_SETTINGS . '[header-search-icon-space]',
			'section'           => $_section,
			'priority'          => 3,
			'transport'         => 'postMessage',
			'default'           => aaa_chinese_get_option( 'header-search-icon-space' ),
			'title'             => __( 'Icon Size', 'astra' ),
			'suffix'            => 'px',
			'type'              => 'control',
			'control'           => 'ast-responsive-slider',
			'sanitize_callback' => array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
			'divider'           => array( 'ast_class' => defined( 'AAA_EXT_VER' ) ? 'ast-top-section-divider ast-bottom-section-divider' : 'ast-section-spacing' ),
			'input_attrs'       => array(
				'min'  => 0,
				'step' => 1,
				'max'  => 50,
			),
			'context'           => AAA_Chinese_Builder_Helper::$general_tab,
		),

		/**
		 * Option: Search bar width
		 */
		array(
			'name'        => AAA_THEME_SETTINGS . '[header-search-width]',
			'section'     => $_section,
			'priority'    => 2,
			'transport'   => 'postMessage',
			'default'     => aaa_chinese_get_option( 'header-search-width' ),
			'title'       => __( 'Search Width', 'astra' ),
			'suffix'      => 'px',
			'type'        => 'control',
			'control'     => 'ast-responsive-slider',
			'input_attrs' => array(
				'min'  => 1,
				'step' => 1,
				'max'  => 1000,
			),
			'divider'     => defined( 'AAA_EXT_VER' ) ? array( 'ast_class' => 'ast-top-divider' ) : array( 'ast_class' => 'ast-section-spacing ast-bottom-divider' ),
			'context'     => defined( 'AAA_EXT_VER' ) ? array(
				AAA_Chinese_Builder_Helper::$general_tab_config,
				array(
					'setting'  => AAA_THEME_SETTINGS . '[header-search-box-type]',
					'operator' => 'in',
					'value'    => array( 'slide-search', 'search-box' ),
				),
			) : AAA_Chinese_Builder_Helper::$general_tab,
		),

		/**
		 * Option: Live Search.
		 */
		array(
			'name'     => AAA_THEME_SETTINGS . '[live-search]',
			'default'  => aaa_chinese_get_option( 'live-search' ),
			'type'     => 'control',
			'control'  => 'ast-toggle-control',
			'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
			'section'  => $_section,
			'title'    => __( 'Live Search', 'astra' ),
			'priority' => 5,
			'context'  => AAA_Chinese_Builder_Helper::$general_tab,
		),

		/**
		 * Option: Live Search based on Post Types.
		 */
		array(
			'name'        => AAA_THEME_SETTINGS . '[live-search-post-types]',
			'default'     => aaa_chinese_get_option( 'live-search-post-types' ),
			'type'        => 'control',
			'control'     => 'ast-multi-selector',
			'section'     => $_section,
			'priority'    => 5,
			'title'       => __( 'Search Within Post Types', 'astra' ),
			'context'     => array(
				AAA_Chinese_Builder_Helper::$general_tab_config,
				array(
					'setting'  => AAA_THEME_SETTINGS . '[live-search]',
					'operator' => '==',
					'value'    => true,
				),
			),
			'transport'   => 'refresh',
			'choices'     => aaa_chinese_customizer_search_post_types_choices(),
			'divider'     => array( 'ast_class' => 'ast-top-divider' ),
			'renderAs'    => 'text',
			'input_attrs' => array(
				'stack_after' => 2, // Currently stack options supports after 2 & 3.
			),
		),

		/**
		 * Option: Live search result count
		 */
		array(
			'name'         => AAA_THEME_SETTINGS . '[live-search-result-count]',
			'default'      => aaa_chinese_get_option( 'live-search-result-count' ),
			'type'         => 'control',
			'control'      => 'ast-number',
			'qty_selector' => true,
			'section'      => $_section,
			'title'        => __( 'Visible Search Result', 'astra' ),
			'priority'     => 5,
			'responsive'   => false,
			'input_attrs'  => array(
				'min'  => 1,
				'step' => 1,
				'max'  => 20,
			),
			'context'      => array(
				AAA_Chinese_Builder_Helper::$general_tab_config,
				array(
					'setting'  => AAA_THEME_SETTINGS . '[live-search]',
					'operator' => '==',
					'value'    => true,
				),
			),
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
			'priority' => 220,
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

	$_configs = array_merge( $_configs, AAA_Chinese_Builder_Base_Configuration::prepare_visibility_tab( $_section ) );

	if ( AAA_Chinese_Builder_Customizer::aaa_chinese_collect_customizer_builder_data() ) {
		array_map( 'aaa_chinese_save_header_customizer_configs', $_configs );
	}

	return $_configs;
}

if ( AAA_Chinese_Builder_Customizer::aaa_chinese_collect_customizer_builder_data() ) {
	add_action( 'init', 'aaa_chinese_header_search_configuration' );
}
