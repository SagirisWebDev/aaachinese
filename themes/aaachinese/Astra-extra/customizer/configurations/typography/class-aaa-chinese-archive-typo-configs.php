<?php
/**
 * Styling Options for AAA Chinese Theme.
 *
 * @package     AAA Chinese
 *
 * @since       AAA Chinese 1.0.15
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AAA_Chinese_Archive_Typo_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class AAA_Chinese_Archive_Typo_Configs extends AAA_Chinese_Customizer_Config_Base {
		/**
		 * Register Archive Typography Customizer Configurations.
		 *
		 * @param Array                $configurations AAA Chinese Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array AAA Chinese Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array();

			// Learn More link if AAA Chinese Pro is not activated.
			if ( aaa_chinese_showcase_upgrade_notices() ) {

				$_configs = array(

					/**
					 * Option: AAA Chinese Pro items for blog pro.
					 */
					array(
						'name'     => AAA_THEME_SETTINGS . '[ast-blog-pro-items]',
						'type'     => 'control',
						'control'  => 'ast-upgrade',
						'campaign' => 'blog-archive',
						'choices'  => array(
							'one'    => array(
								'title' => __( 'Posts Filter', 'astra' ),
							),
							'eleven' => array(
								'title' => __( 'Posts Reveal Effect', 'astra' ),
							),
							'two'    => array(
								'title' => __( 'Grid, Masonry layout', 'astra' ),
							),
							'twelve' => array(
								'title' => __( 'Extended Meta Options', 'astra' ),
							),
							'three'  => array(
								'title' => __( 'Custom image size', 'astra' ),
							),
							'four'   => array(
								'title' => __( 'Archive pagination', 'astra' ),
							),
							'six'    => array(
								'title' => __( 'Extended typography', 'astra' ),
							),
							'seven'  => array(
								'title' => __( 'Extended spacing', 'astra' ),
							),
							'eight'  => array(
								'title' => __( 'Archive read time', 'astra' ),
							),
							'nine'   => array(
								'title' => __( 'Archive excerpt', 'astra' ),
							),
						),
						'section'  => 'section-blog',
						'default'  => '',
						'priority' => 999,
						'context'  => array(),
						'title'    => __( 'Take your blog to the next level with powerful design features.', 'astra' ),
						'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
					),
				);
			}

			if ( ! defined( 'AAA_EXT_VER' ) || ( defined( 'AAA_EXT_VER' ) && ! AAA_Chinese_Ext_Extension::is_active( 'typography' ) ) ) {
				$new_configs = array(
					/**
					 * Option: Blog - Post Title Font Size
					 */
					array(
						'name'              => AAA_THEME_SETTINGS . '[font-size-page-title]',
						'control'           => 'ast-responsive-slider',
						'sanitize_callback' => array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
						'section'           => 'section-blog',
						'type'              => 'control',
						'transport'         => 'postMessage',
						'title'             => __( 'Post Title Size', 'astra' ),
						'priority'          => 140,
						'default'           => aaa_chinese_get_option( 'font-size-page-title' ),
						'suffix'            => array( 'px', 'em', 'vw', 'rem' ),
						'input_attrs'       => array(
							'px'  => array(
								'min'  => 0,
								'step' => 1,
								'max'  => 200,
							),
							'em'  => array(
								'min'  => 0,
								'step' => 0.01,
								'max'  => 20,
							),
							'vw'  => array(
								'min'  => 0,
								'step' => 0.1,
								'max'  => 25,
							),
							'rem' => array(
								'min'  => 0,
								'step' => 0.1,
								'max'  => 20,
							),
						),
						'context'           => AAA_Chinese_Builder_Helper::$design_tab,
						'divider'           => array( 'ast_class' => 'ast-top-section-divider' ),
					),
					array(
						'name'              => AAA_THEME_SETTINGS . '[font-size-post-meta]',
						'control'           => 'ast-responsive-slider',
						'sanitize_callback' => array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
						'section'           => 'section-blog',
						'type'              => 'control',
						'transport'         => 'postMessage',
						'title'             => __( 'Meta Font Size', 'astra' ),
						'is_font'           => true,
						'priority'          => 140,
						'default'           => aaa_chinese_get_option( 'font-size-post-meta' ),
						'suffix'            => array( 'px', 'em', 'vw', 'rem' ),
						'input_attrs'       => array(
							'px'  => array(
								'min'  => 0,
								'step' => 1,
								'max'  => 200,
							),
							'em'  => array(
								'min'  => 0,
								'step' => 0.01,
								'max'  => 20,
							),
							'vw'  => array(
								'min'  => 0,
								'step' => 0.1,
								'max'  => 25,
							),
							'rem' => array(
								'min'  => 0,
								'step' => 0.1,
								'max'  => 20,
							),
						),
						'context'           => AAA_Chinese_Builder_Helper::$design_tab,
						'divider'           => array( 'ast_class' => 'ast-section-spacing' ),
					),
					array(
						'name'              => AAA_THEME_SETTINGS . '[font-size-post-tax]',
						'control'           => 'ast-responsive-slider',
						'sanitize_callback' => array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
						'section'           => 'section-blog',
						'type'              => 'control',
						'transport'         => 'postMessage',
						'title'             => __( 'Taxonomy Font', 'astra' ),
						'is_font'           => true,
						'priority'          => 140,
						'default'           => aaa_chinese_get_option( 'font-size-post-tax' ),
						'suffix'            => array( 'px', 'em', 'vw', 'rem' ),
						'input_attrs'       => array(
							'px'  => array(
								'min'  => 0,
								'step' => 1,
								'max'  => 200,
							),
							'em'  => array(
								'min'  => 0,
								'step' => 0.01,
								'max'  => 20,
							),
							'vw'  => array(
								'min'  => 0,
								'step' => 0.1,
								'max'  => 25,
							),
							'rem' => array(
								'min'  => 0,
								'step' => 0.1,
								'max'  => 20,
							),
						),
						'context'           => array(
							AAA_Chinese_Builder_Helper::$design_tab_config,
							array(
								'relation' => 'OR',
								array(
									'setting'  => AAA_THEME_SETTINGS . '[blog-post-structure]',
									'operator' => 'contains',
									'value'    => 'category',
								),
								array(
									'setting'  => AAA_THEME_SETTINGS . '[blog-post-structure]',
									'operator' => 'contains',
									'value'    => 'tag',
								),
								array(
									'setting'  => AAA_THEME_SETTINGS . '[blog-meta]',
									'operator' => 'contains',
									'value'    => 'category',
								),
								array(
									'setting'  => AAA_THEME_SETTINGS . '[blog-meta]',
									'operator' => 'contains',
									'value'    => 'tag',
								),
							),
						),
						'divider'           => array( 'ast_class' => 'ast-section-spacing' ),
					),
				);
				$_configs    = array_merge( $_configs, $new_configs );
			}

			return array_merge( $configurations, $_configs );
		}
	}
}

new AAA_Chinese_Archive_Typo_Configs();
