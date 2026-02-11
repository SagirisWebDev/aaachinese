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

if ( ! class_exists( 'AAA_Chinese_Blog_Single_Layout_Configs' ) ) {

	/**
	 * Register Blog Single Layout Configurations.
	 */
	class AAA_Chinese_Blog_Single_Layout_Configs extends AAA_Chinese_Customizer_Config_Base {
		/**
		 * Register Blog Single Layout Configurations.
		 *
		 * @param Array                $configurations AAA Chinese Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array AAA Chinese Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			/** @psalm-suppress DocblockTypeContradiction */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$tab_config = AAA_Chinese_Builder_Helper::$design_tab;

			$_configs = array(

				/**
				 * Option: Single Post Content Width
				 */
				array(
					'name'       => AAA_THEME_SETTINGS . '[blog-single-width]',
					'type'       => 'control',
					'control'    => 'ast-selector',
					'section'    => 'section-blog-single',
					'default'    => aaa_chinese_get_option( 'blog-single-width' ),
					'priority'   => 6,
					'title'      => __( 'Content Width', 'astra' ),
					'choices'    => array(
						'default' => __( 'Default', 'astra' ),
						'custom'  => __( 'Custom', 'astra' ),
					),
					'transport'  => 'postMessage',
					'responsive' => false,
					'divider'    => array( 'ast_class' => 'ast-top-section-divider' ),
					'renderAs'   => 'text',
				),

				/**
				 * Option: Enter Width
				 */
				array(
					'name'        => AAA_THEME_SETTINGS . '[blog-single-max-width]',
					'type'        => 'control',
					'control'     => 'ast-slider',
					'section'     => 'section-blog-single',
					'transport'   => 'postMessage',
					'default'     => aaa_chinese_get_option( 'blog-single-max-width' ),
					'context'     => array(
						AAA_Chinese_Builder_Helper::$general_tab_config,
						array(
							'setting'  => AAA_THEME_SETTINGS . '[blog-single-width]',
							'operator' => '===',
							'value'    => 'custom',
						),
					),
					'priority'    => 6,
					'title'       => __( 'Custom Width', 'astra' ),
					'suffix'      => 'px',
					'input_attrs' => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 1920,
					),
					'divider'     => array( 'ast_class' => 'ast-top-divider' ),
				),

				/**
				 * Option: Content images shadow
				 */
				array(
					'name'     => AAA_THEME_SETTINGS . '[single-content-images-shadow]',
					'default'  => aaa_chinese_get_option( 'single-content-images-shadow' ),
					'type'     => 'control',
					'section'  => 'section-blog-single',
					'title'    => __( 'Content Images Box Shadow', 'astra' ),
					'control'  => 'ast-toggle-control',
					'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
					'priority' => 9,
					'context'  => AAA_Chinese_Builder_Helper::$general_tab,
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => AAA_THEME_SETTINGS . '[section-blog-single-spacing-divider]',
					'section'  => 'section-blog-single',
					'title'    => __( 'Post Spacing', 'astra' ),
					'type'     => 'control',
					'control'  => 'ast-heading',
					'priority' => 24,
					'context'  => $tab_config,
				),

				/**
				 * Option: Single Post Spacing
				 */
				array(
					'name'              => AAA_THEME_SETTINGS . '[single-post-outside-spacing]',
					'default'           => aaa_chinese_get_option( 'single-post-outside-spacing' ),
					'type'              => 'control',
					'control'           => 'ast-responsive-spacing',
					'sanitize_callback' => array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
					'section'           => 'section-blog-single',
					'title'             => __( 'Outside', 'astra' ),
					'linked_choices'    => true,
					'unit_choices'      => array( 'px', 'em', '%' ),
					'choices'           => array(
						'top'    => __( 'Top', 'astra' ),
						'right'  => __( 'Right', 'astra' ),
						'bottom' => __( 'Bottom', 'astra' ),
						'left'   => __( 'Left', 'astra' ),
					),
					'priority'          => 25,
					'context'           => $tab_config,
					'divider'           => array( 'ast_class' => 'ast-section-spacing' ),
				),

				/**
				 * Option: Single Post Margin
				 */
				array(
					'name'              => AAA_THEME_SETTINGS . '[single-post-inside-spacing]',
					'default'           => aaa_chinese_get_option( 'single-post-inside-spacing' ),
					'type'              => 'control',
					'control'           => 'ast-responsive-spacing',
					'sanitize_callback' => array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
					'section'           => 'section-blog-single',
					'title'             => __( 'Inside', 'astra' ),
					'linked_choices'    => true,
					'transport'         => 'refresh',
					'unit_choices'      => array( 'px', 'em', '%' ),
					'choices'           => array(
						'top'    => __( 'Top', 'astra' ),
						'right'  => __( 'Right', 'astra' ),
						'bottom' => __( 'Bottom', 'astra' ),
						'left'   => __( 'Left', 'astra' ),
					),
					'priority'          => 30,
					'divider'           => array( 'ast_class' => 'ast-top-divider' ),
					'context'           => $tab_config,
				),
			);

			$_configs[] = array(
				'name'        => 'section-blog-single-ast-context-tabs',
				'section'     => 'section-blog-single',
				'type'        => 'control',
				'control'     => 'ast-builder-header-control',
				'priority'    => 0,
				'description' => '',
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

new AAA_Chinese_Blog_Single_Layout_Configs();
