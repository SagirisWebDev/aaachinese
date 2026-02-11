<?php
/**
 * Related Posts Options for AAA Chinese Theme.
 *
 * @package     AAA Chinese
 * @link        https://wpastra.com/
 * @since       AAA Chinese 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Bail if Customizer config base class does not exist.
if ( ! class_exists( 'AAA_Chinese_Customizer_Config_Base' ) ) {
	return;
}

/**
 * Register Related Posts Configurations.
 */
class AAA_Chinese_Related_Posts_Configs extends AAA_Chinese_Customizer_Config_Base {
	/**
	 * Register Related Posts Configurations.
	 *
	 * @param Array                $configurations AAA Chinese Customizer Configurations.
	 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
	 * @since 3.5.0
	 * @return Array AAA Chinese Customizer Configurations with updated configurations.
	 */
	public function register_configuration( $configurations, $wp_customize ) {
		$related_structure_sub_controls = array();
		$meta_config_options            = array();
		$parent_section                 = 'section-blog-single';

		$related_structure_sub_controls['featured-image'] = array(
			'clone'       => false,
			'is_parent'   => true,
			'main_index'  => 'featured-image',
			'clone_limit' => 2,
			'title'       => __( 'Featured Image', 'astra' ),
		);
		$related_structure_sub_controls['title-meta']     = array(
			'clone'       => false,
			'is_parent'   => true,
			'main_index'  => 'title-meta',
			'clone_limit' => 2,
			'title'       => __( 'Title & Post Meta', 'astra' ),
		);
		$meta_config_options['category']                  = array(
			'clone'       => false,
			'is_parent'   => true,
			'main_index'  => 'category',
			'clone_limit' => 1,
			'title'       => __( 'Category', 'astra' ),
		);
		$meta_config_options['author']                    = array(
			'clone'       => false,
			'is_parent'   => true,
			'main_index'  => 'author',
			'clone_limit' => 1,
			'title'       => __( 'Author', 'astra' ),
		);
		$meta_config_options['date']                      = array(
			'clone'       => false,
			'is_parent'   => true,
			'main_index'  => 'date',
			'clone_limit' => 1,
			'title'       => __( 'Date', 'astra' ),
		);
		$meta_config_options['tag']                       = array(
			'clone'       => false,
			'is_parent'   => true,
			'main_index'  => 'tag',
			'clone_limit' => 1,
			'title'       => __( 'Tag', 'astra' ),
		);

		$_configs = array(

			/**
			 * Option: Related Posts Query
			 */
			array(
				'name'     => AAA_THEME_SETTINGS . '[related-posts-section-heading]',
				'section'  => 'section-blog-single',
				'type'     => 'control',
				'control'  => 'ast-heading',
				'title'    => __( 'Related Posts', 'astra' ),
				'priority' => 10,
				'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
			),

			array(
				'name'        => 'related-posts-section-ast-context-tabs',
				'section'     => 'ast-sub-section-related-posts',
				'type'        => 'control',
				'control'     => 'ast-builder-header-control',
				'priority'    => 0,
				'description' => '',
				'context'     => array(),
			),

			array(
				'name'     => 'ast-sub-section-related-posts',
				'title'    => __( 'Related Posts', 'astra' ),
				'type'     => 'section',
				'section'  => $parent_section,
				'panel'    => '',
				'priority' => 1,
			),

			array(
				'name'     => AAA_THEME_SETTINGS . '[enable-related-posts]',
				'type'     => 'control',
				'default'  => aaa_chinese_get_option( 'enable-related-posts' ),
				'control'  => 'ast-section-toggle',
				'section'  => $parent_section,
				'priority' => 10,
				'linked'   => 'ast-sub-section-related-posts',
				'linkText' => __( 'Related Posts', 'astra' ),
				'divider'  => array( 'ast_class' => 'ast-bottom-divider ast-bottom-section-divider' ),
			),

			/**
			 * Option: Related Posts Title
			 */
			array(
				'name'      => AAA_THEME_SETTINGS . '[related-posts-title]',
				'default'   => aaa_chinese_get_option( 'related-posts-title' ),
				'type'      => 'control',
				'section'   => 'ast-sub-section-related-posts',
				'priority'  => 11,
				'title'     => __( 'Title', 'astra' ),
				'control'   => 'ast-text-input',
				'divider'   => array( 'ast_class' => 'ast-section-spacing' ),
				'transport' => 'postMessage',
				'partial'   => array(
					'selector'            => '.ast-related-posts-title-section .ast-related-posts-title',
					'container_inclusive' => false,
					'render_callback'     => array( 'AAA_Chinese_Related_Posts_Loader', 'render_related_posts_title' ),
				),
				'context'   => array(
					AAA_Chinese_Builder_Helper::$general_tab_config,
					array(
						'setting'  => AAA_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
				),
			),

			/**
			 * Option: Related Posts Title Alignment
			 */
			array(
				'name'       => AAA_THEME_SETTINGS . '[releted-posts-title-alignment]',
				'default'    => aaa_chinese_get_option( 'releted-posts-title-alignment' ),
				'section'    => 'ast-sub-section-related-posts',
				'transport'  => 'postMessage',
				'title'      => __( 'Title Alignment', 'astra' ),
				'type'       => 'control',
				'control'    => 'ast-selector',
				'priority'   => 11,
				'responsive' => false,
				'divider'    => array( 'ast_class' => 'ast-top-divider' ),
				'context'    => array(
					AAA_Chinese_Builder_Helper::$general_tab_config,
					'relation' => 'AND',
					array(
						'setting'  => AAA_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => AAA_THEME_SETTINGS . '[related-posts-title]',
						'operator' => '!=',
						'value'    => '',
					),
				),
				'choices'    => array(
					'left'   => 'align-left',
					'center' => 'align-center',
					'right'  => 'align-right',
				),
			),

			/**
			 * Option: Related Posts Structure
			 */
			array(
				'name'              => AAA_THEME_SETTINGS . '[related-posts-structure]',
				'type'              => 'control',
				'control'           => 'ast-sortable',
				'sanitize_callback' => array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_multi_choices' ),
				'section'           => 'ast-sub-section-related-posts',
				'default'           => aaa_chinese_get_option( 'related-posts-structure' ),
				'priority'          => 12,
				'context'           => array(
					AAA_Chinese_Builder_Helper::$general_tab_config,
					array(
						'setting'  => AAA_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
				),
				'title'             => __( 'Posts Structure', 'astra' ),

				'choices'           => $related_structure_sub_controls,
				'divider'           => array( 'ast_class' => 'ast-top-divider' ),
			),
			/**
			 * Option: Meta Data Separator.
			 */
			array(
				'name'       => 'related-metadata-separator',
				'default'    => aaa_chinese_get_option( 'related-metadata-separator', '/' ),
				'type'       => 'sub-control',
				'transport'  => 'postMessage',
				'parent'     => AAA_THEME_SETTINGS . '[related-posts-structure]',
				'linked'     => 'title-meta',
				'section'    => 'ast-sub-section-related-posts',
				'priority'   => 10,
				'control'    => 'ast-selector',
				'title'      => __( 'Divider Type', 'astra' ),
				'choices'    => array(
					'/'    => '/',
					'-'    => '-',
					'|'    => '|',
					'•'    => '•',
					'none' => __( 'None', 'astra' ),
				),
				'responsive' => false,
				'renderAs'   => 'text',
			),
			array(
				'name'              => AAA_THEME_SETTINGS . '[related-posts-meta-structure]',
				'type'              => 'control',
				'control'           => 'ast-sortable',
				'sanitize_callback' => array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_multi_choices' ),
				'default'           => aaa_chinese_get_option( 'related-posts-meta-structure' ),
				'context'           => array(
					AAA_Chinese_Builder_Helper::$general_tab_config,
					'relation' => 'AND',
					array(
						'setting'  => AAA_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => AAA_THEME_SETTINGS . '[related-posts-structure]',
						'operator' => 'contains',
						'value'    => 'title-meta',
					),
				),
				'section'           => 'ast-sub-section-related-posts',
				'priority'          => 12,
				'title'             => __( 'Meta', 'astra' ),
				'choices'           => array_merge(
					array(
						'comments' => __( 'Comments', 'astra' ),
					),
					$meta_config_options
				),
				'divider'           => array( 'ast_class' => 'ast-top-divider' ),
			),
			array(
				'name'                   => 'related-posts-image-ratio-type',
				'default'                => aaa_chinese_get_option( 'related-posts-image-ratio-type', '' ),
				'type'                   => 'sub-control',
				'transport'              => 'postMessage',
				'parent'                 => AAA_THEME_SETTINGS . '[related-posts-structure]',
				'section'                => 'ast-sub-section-related-posts',
				'linked'                 => 'featured-image',
				'priority'               => 5,
				'control'                => 'ast-selector',
				'title'                  => __( 'Image Ratio', 'astra' ),
				'choices'                => array(
					''           => __( 'Original', 'astra' ),
					'predefined' => __( 'Predefined', 'astra' ),
					'custom'     => __( 'Custom', 'astra' ),
				),
				'responsive'             => false,
				'renderAs'               => 'text',
				'contextual_sub_control' => true,
				'input_attrs'            => array(
					'dependents' => array(
						''           => array( 'related-posts-original-image-scale-description' ),
						'predefined' => array( 'related-posts-image-ratio-pre-scale' ),
						'custom'     => array( 'related-posts-image-custom-scale-width', 'related-posts-image-custom-scale-height', 'related-posts-custom-image-scale-description' ),
					),
				),
			),
			array(
				'name'       => 'related-posts-image-ratio-pre-scale',
				'default'    => aaa_chinese_get_option( 'related-posts-image-ratio-pre-scale' ),
				'type'       => 'sub-control',
				'transport'  => 'postMessage',
				'parent'     => AAA_THEME_SETTINGS . '[related-posts-structure]',
				'linked'     => 'featured-image',
				'section'    => 'ast-sub-section-related-posts',
				'priority'   => 10,
				'control'    => 'ast-selector',
				'choices'    => array(
					'1/1'  => __( '1:1', 'astra' ),
					'4/3'  => __( '4:3', 'astra' ),
					'16/9' => __( '16:9', 'astra' ),
					'2/1'  => __( '2:1', 'astra' ),
				),
				'responsive' => false,
				'renderAs'   => 'text',
			),
			array(
				'name'              => 'related-posts-image-custom-scale-width',
				'default'           => aaa_chinese_get_option( 'related-posts-image-custom-scale-width', 16 ),
				'type'              => 'sub-control',
				'control'           => 'ast-number',
				'transport'         => 'postMessage',
				'parent'            => AAA_THEME_SETTINGS . '[related-posts-structure]',
				'section'           => 'ast-sub-section-related-posts',
				'linked'            => 'featured-image',
				'priority'          => 11,
				'qty_selector'      => true,
				'title'             => __( 'Width', 'astra' ),
				'input_attrs'       => array(
					'style'       => 'text-align:center;',
					'placeholder' => __( 'Auto', 'astra' ),
					'min'         => 1,
					'max'         => 1000,
				),
				'sanitize_callback' => array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
			),
			array(
				'name'              => 'related-posts-image-custom-scale-height',
				'default'           => aaa_chinese_get_option( 'related-posts-image-custom-scale-height', 9 ),
				'type'              => 'sub-control',
				'control'           => 'ast-number',
				'transport'         => 'postMessage',
				'parent'            => AAA_THEME_SETTINGS . '[related-posts-structure]',
				'section'           => 'ast-sub-section-related-posts',
				'linked'            => 'featured-image',
				'priority'          => 12,
				'qty_selector'      => true,
				'title'             => __( 'Height', 'astra' ),
				'input_attrs'       => array(
					'style'       => 'text-align:center;',
					'placeholder' => __( 'Auto', 'astra' ),
					'min'         => 1,
					'max'         => 1000,
				),
				'sanitize_callback' => array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
			),
			array(
				'name'     => 'related-posts-custom-image-scale-description',
				'parent'   => AAA_THEME_SETTINGS . '[related-posts-structure]',
				'linked'   => 'featured-image',
				'type'     => 'sub-control',
				'control'  => 'ast-description',
				'section'  => 'ast-sub-section-related-posts',
				'priority' => 14,
				'label'    => '',
				'help'     => sprintf( /* translators: 1: link open markup, 2: link close markup */ __( 'Calculate a personalized image ratio using this %1$s online tool %2$s for your image dimensions.', 'astra' ), '<a href="' . esc_url( 'https://www.digitalrebellion.com/webapps/aspectcalc' ) . '" target="_blank">', '</a>' ),
			),
			array(
				'name'        => 'related-posts-image-size',
				'default'     => aaa_chinese_get_option( 'related-posts-image-size', 'large' ),
				'parent'      => AAA_THEME_SETTINGS . '[related-posts-structure]',
				'section'     => 'ast-sub-section-related-posts',
				'linked'      => 'featured-image',
				'type'        => 'sub-control',
				'priority'    => 17,
				'transport'   => 'postMessage',
				'title'       => __( 'Image Size', 'astra' ),
				'divider'     => array( 'ast_class' => 'ast-top-divider' ),
				'control'     => 'ast-select',
				'choices'     => aaa_chinese_get_site_image_sizes(),
				'description' => __( 'Note: Image Size & Ratio won\'t work if Image Position set as Background.', 'astra' ),
			),
			array(
				'name'      => 'related-posts-author-prefix-label',
				'default'   => aaa_chinese_get_option( 'related-posts-author-prefix-label', aaa_chinese_default_strings( 'string-blog-meta-author-by', false ) ),
				'parent'    => AAA_THEME_SETTINGS . '[related-posts-meta-structure]',
				'linked'    => 'author',
				'type'      => 'sub-control',
				'control'   => 'ast-text-input',
				'section'   => 'ast-sub-section-related-posts',
				'divider'   => array( 'ast_class' => 'ast-bottom-divider ast-bottom-section-spacing' ),
				'title'     => __( 'Prefix Label', 'astra' ),
				'priority'  => 1,
				'transport' => 'postMessage',
			),
			array(
				'name'      => 'related-posts-author-avatar',
				'parent'    => AAA_THEME_SETTINGS . '[related-posts-meta-structure]',
				'default'   => aaa_chinese_get_option( 'related-posts-author-avatar' ),
				'linked'    => 'author',
				'type'      => 'sub-control',
				'control'   => 'ast-toggle',
				'section'   => 'ast-sub-section-related-posts',
				'priority'  => 5,
				'title'     => __( 'Author Avatar', 'astra' ),
				'transport' => 'postMessage',
			),
			array(
				'name'        => 'related-posts-author-avatar-size',
				'parent'      => AAA_THEME_SETTINGS . '[related-posts-meta-structure]',
				'default'     => aaa_chinese_get_option( 'related-posts-author-avatar-size', 30 ),
				'linked'      => 'author',
				'type'        => 'sub-control',
				'control'     => 'ast-slider',
				'transport'   => 'postMessage',
				'section'     => 'ast-sub-section-related-posts',
				'priority'    => 10,
				'title'       => __( 'Image Size', 'astra' ),
				'suffix'      => 'px',
				'input_attrs' => array(
					'min'  => 1,
					'step' => 1,
					'max'  => 200,
				),
			),
			array(
				'name'       => 'related-posts-meta-date-type',
				'parent'     => AAA_THEME_SETTINGS . '[related-posts-meta-structure]',
				'type'       => 'sub-control',
				'control'    => 'ast-selector',
				'section'    => 'ast-sub-section-related-posts',
				'default'    => aaa_chinese_get_option( 'related-posts-meta-date-type', 'published' ),
				'priority'   => 1,
				'linked'     => 'date',
				'transport'  => 'refresh',
				'title'      => __( 'Type', 'astra' ),
				'choices'    => array(
					'published' => __( 'Published', 'astra' ),
					'updated'   => __( 'Last Updated', 'astra' ),
				),
				'divider'    => array( 'ast_class' => 'ast-bottom-divider ast-bottom-spacing' ),
				'responsive' => false,
				'renderAs'   => 'text',
			),
			array(
				'name'       => 'related-posts-date-format',
				'default'    => aaa_chinese_get_option( 'related-posts-date-format', '' ),
				'parent'     => AAA_THEME_SETTINGS . '[related-posts-meta-structure]',
				'linked'     => 'date',
				'type'       => 'sub-control',
				'control'    => 'ast-select',
				'section'    => 'ast-sub-section-related-posts',
				'priority'   => 2,
				'responsive' => false,
				'renderAs'   => 'text',
				'title'      => __( 'Format', 'astra' ),
				'choices'    => array(
					''       => __( 'Default', 'astra' ),
					'F j, Y' => 'November 6, 2010',
					'Y-m-d'  => '2010-11-06',
					'm/d/Y'  => '11/06/2010',
					'd/m/Y'  => '06/11/2010',
				),
			),
			array(
				'name'       => 'related-posts-category-style',
				'parent'     => AAA_THEME_SETTINGS . '[related-posts-meta-structure]',
				'type'       => 'sub-control',
				'control'    => 'ast-selector',
				'section'    => 'ast-sub-section-related-posts',
				'default'    => aaa_chinese_get_option( 'related-posts-category-style', '' ),
				'priority'   => 2,
				'linked'     => 'category',
				'transport'  => 'refresh',
				'title'      => __( 'Style', 'astra' ),
				'choices'    => array(
					'none'      => __( 'Default', 'astra' ),
					'badge'     => __( 'Badge', 'astra' ),
					'underline' => __( 'Underline', 'astra' ),
				),
				'responsive' => false,
				'renderAs'   => 'text',
			),
			array(
				'name'       => 'related-posts-tag-style',
				'parent'     => AAA_THEME_SETTINGS . '[related-posts-meta-structure]',
				'type'       => 'sub-control',
				'control'    => 'ast-selector',
				'section'    => 'ast-sub-section-related-posts',
				'default'    => aaa_chinese_get_option( 'related-posts-tag-style', '' ),
				'priority'   => 2,
				'linked'     => 'tag',
				'transport'  => 'refresh',
				'title'      => __( 'Style', 'astra' ),
				'choices'    => array(
					'none'      => __( 'Default', 'astra' ),
					'badge'     => __( 'Badge', 'astra' ),
					'underline' => __( 'Underline', 'astra' ),
				),
				'responsive' => false,
				'renderAs'   => 'text',
			),

			/**
			 * Option: Enable excerpt for Related Posts.
			 */
			array(
				'name'     => AAA_THEME_SETTINGS . '[enable-related-posts-excerpt]',
				'default'  => aaa_chinese_get_option( 'enable-related-posts-excerpt' ),
				'type'     => 'control',
				'control'  => 'ast-toggle-control',
				'title'    => __( 'Enable Post Excerpt', 'astra' ),
				'section'  => 'ast-sub-section-related-posts',
				'priority' => 12,
				'context'  => array(
					AAA_Chinese_Builder_Helper::$general_tab_config,
					array(
						'setting'  => AAA_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
				),
				'divider'  => array( 'ast_class' => 'ast-top-divider' ),
			),

			/**
			 * Option: Excerpt word count for Related Posts
			 */
			array(
				'name'        => AAA_THEME_SETTINGS . '[related-posts-excerpt-count]',
				'default'     => aaa_chinese_get_option( 'related-posts-excerpt-count' ),
				'type'        => 'control',
				'control'     => 'ast-slider',
				'context'     => array(
					AAA_Chinese_Builder_Helper::$general_tab_config,
					'relation' => 'AND',
					array(
						'setting'  => AAA_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => AAA_THEME_SETTINGS . '[enable-related-posts-excerpt]',
						'operator' => '==',
						'value'    => true,
					),
				),
				'section'     => 'ast-sub-section-related-posts',
				'title'       => __( 'Excerpt Word Count', 'astra' ),
				'divider'     => array( 'ast_class' => 'ast-section-spacing' ),
				'priority'    => 12,
				'input_attrs' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 60,
				),
			),

			/**
			 * Option: No. of Related Posts
			 */
			array(
				'name'        => AAA_THEME_SETTINGS . '[related-posts-total-count]',
				'default'     => aaa_chinese_get_option( 'related-posts-total-count' ),
				'type'        => 'control',
				'control'     => 'ast-slider',
				'context'     => array(
					AAA_Chinese_Builder_Helper::$general_tab_config,
					array(
						'setting'  => AAA_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
				),
				'section'     => 'ast-sub-section-related-posts',
				'title'       => __( 'Total Number of Related Posts', 'astra' ),
				'priority'    => 11,
				'input_attrs' => array(
					'min'  => 1,
					'step' => 1,
					'max'  => 20,
				),
				'divider'     => array( 'ast_class' => 'ast-top-divider ast-bottom-divider' ),
			),

			/**
			 * Option: Related Posts Columns
			 */
			array(
				'name'       => AAA_THEME_SETTINGS . '[related-posts-grid-responsive]',
				'type'       => 'control',
				'control'    => 'ast-selector',
				'section'    => 'ast-sub-section-related-posts',
				'default'    => aaa_chinese_get_option( 'related-posts-grid-responsive' ),
				'priority'   => 11,
				'context'    => array(
					AAA_Chinese_Builder_Helper::$general_tab_config,
					array(
						'setting'  => AAA_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
				),
				'title'      => __( 'Grid Column Layout', 'astra' ),
				'choices'    => array(
					'full'    => __( '1', 'astra' ),
					'2-equal' => __( '2', 'astra' ),
					'3-equal' => __( '3', 'astra' ),
					'4-equal' => __( '4', 'astra' ),
				),
				'responsive' => true,
				'renderAs'   => 'text',
				'divider'    => array( 'ast_class' => 'ast-bottom-divider' ),
			),

			/**
			 * Option: Related Posts Query group setting
			 */
			array(
				'name'      => AAA_THEME_SETTINGS . '[related-posts-query-group]',
				'default'   => aaa_chinese_get_option( 'related-posts-query-group' ),
				'type'      => 'control',
				'transport' => 'postMessage',
				'control'   => 'ast-settings-group',
				'context'   => array(
					AAA_Chinese_Builder_Helper::$general_tab_config,
					array(
						'setting'  => AAA_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
				),
				'title'     => __( 'Posts Query', 'astra' ),
				'section'   => 'ast-sub-section-related-posts',
				'priority'  => 11,
			),

			/**
			 * Option: Related Posts based on.
			 */
			array(
				'name'       => 'related-posts-based-on',
				'default'    => aaa_chinese_get_option( 'related-posts-based-on' ),
				'type'       => 'sub-control',
				'transport'  => 'postMessage',
				'parent'     => AAA_THEME_SETTINGS . '[related-posts-query-group]',
				'section'    => 'ast-sub-section-related-posts',
				'priority'   => 1,
				'control'    => 'ast-selector',
				'divider'    => array( 'ast_class' => 'ast-sub-bottom-divider' ),
				'title'      => __( 'Related Posts by', 'astra' ),
				'choices'    => array(
					'categories' => __( 'Categories', 'astra' ),
					'tags'       => __( 'Tags', 'astra' ),
				),
				'responsive' => false,
				'renderAs'   => 'text',
			),

			/**
			 * Option: Display Post Structure
			 */
			array(
				'name'      => 'related-posts-order-by',
				'default'   => aaa_chinese_get_option( 'related-posts-order-by' ),
				'parent'    => AAA_THEME_SETTINGS . '[related-posts-query-group]',
				'section'   => 'ast-sub-section-related-posts',
				'type'      => 'sub-control',
				'divider'   => array( 'ast_class' => 'ast-sub-bottom-divider' ),
				'priority'  => 2,
				'transport' => 'postMessage',
				'title'     => __( 'Order by', 'astra' ),
				'control'   => 'ast-select',
				'choices'   => array(
					'date'          => __( 'Date', 'astra' ),
					'title'         => __( 'Title', 'astra' ),
					'menu_order'    => __( 'Post Order', 'astra' ),
					'rand'          => __( 'Random', 'astra' ),
					'comment_count' => __( 'Comment Counts', 'astra' ),
				),
			),

			/**
			 * Option: Display Post Structure
			 */
			array(
				'name'       => 'related-posts-order',
				'parent'     => AAA_THEME_SETTINGS . '[related-posts-query-group]',
				'section'    => 'ast-sub-section-related-posts',
				'type'       => 'sub-control',
				'transport'  => 'postMessage',
				'title'      => __( 'Order', 'astra' ),
				'default'    => aaa_chinese_get_option( 'related-posts-order' ),
				'control'    => 'ast-selector',
				'priority'   => 3,
				'choices'    => array(
					'asc'  => __( 'Ascending', 'astra' ),
					'desc' => __( 'Descending', 'astra' ),
				),
				'responsive' => false,
				'renderAs'   => 'text',
			),

			array(
				'name'        => AAA_THEME_SETTINGS . '[related-posts-box-placement]',
				'default'     => aaa_chinese_get_option( 'related-posts-box-placement' ),
				'type'        => 'control',
				'section'     => 'ast-sub-section-related-posts',
				'priority'    => 12,
				'title'       => __( 'Section Placement', 'astra' ),
				'control'     => 'ast-selector',
				'description' => __( 'Decide whether to isolate or integrate the module with the entry content area.', 'astra' ),
				'divider'     => array( 'ast_class' => 'ast-top-divider' ),
				'choices'     => array(
					'default' => __( 'Default', 'astra' ),
					'inside'  => __( 'Contained', 'astra' ),
					'outside' => __( 'Separated', 'astra' ),
				),
				'context'     => array(
					AAA_Chinese_Builder_Helper::$general_tab_config,
					array(
						'setting'  => AAA_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
				),
				'responsive'  => false,
				'renderAs'    => 'text',
			),
			array(
				'name'        => AAA_THEME_SETTINGS . '[related-posts-outside-location]',
				'default'     => aaa_chinese_get_option( 'related-posts-outside-location' ),
				'type'        => 'control',
				'section'     => 'ast-sub-section-related-posts',
				'priority'    => 12,
				'title'       => __( 'Location', 'astra' ),
				'control'     => 'ast-selector',
				'choices'     => array(
					'below' => __( 'Below Comments', 'astra' ),
					'above' => __( 'Above Comments', 'astra' ),
				),
				'description' => __( 'To sync this option with comments, use the same positioning for both sections: Contained or Separated.', 'astra' ),
				'divider'     => array( 'ast_class' => 'ast-top-section-spacing' ),
				'context'     => array(
					AAA_Chinese_Builder_Helper::$general_tab_config,
					'relation' => 'AND',
					array(
						'setting'  => AAA_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => AAA_THEME_SETTINGS . '[related-posts-box-placement]',
						'operator' => '!=',
						'value'    => 'default',
					),
				),
				'responsive'  => false,
				'renderAs'    => 'text',
			),
			array(
				'name'       => AAA_THEME_SETTINGS . '[related-posts-container-width]',
				'default'    => aaa_chinese_get_option( 'related-posts-container-width' ),
				'type'       => 'control',
				'section'    => 'ast-sub-section-related-posts',
				'priority'   => 12,
				'title'      => __( 'Container Structure', 'astra' ),
				'control'    => 'ast-selector',
				'choices'    => array(
					'narrow' => __( 'Narrow', 'astra' ),
					''       => __( 'Full Width', 'astra' ),
				),
				'divider'    => array( 'ast_class' => 'ast-top-section-spacing' ),
				'context'    => array(
					AAA_Chinese_Builder_Helper::$general_tab_config,
					'relation' => 'AND',
					array(
						'setting'  => AAA_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => AAA_THEME_SETTINGS . '[related-posts-box-placement]',
						'operator' => '==',
						'value'    => 'outside',
					),
				),
				'responsive' => false,
				'renderAs'   => 'text',
			),

			/**
			 * Option: Related Posts colors setting group
			 */
			array(
				'name'      => AAA_THEME_SETTINGS . '[related-posts-colors-group]',
				'default'   => aaa_chinese_get_option( 'related-posts-colors-group' ),
				'type'      => 'control',
				'transport' => 'postMessage',
				'control'   => 'ast-settings-group',
				'context'   => array(
					AAA_Chinese_Builder_Helper::$design_tab_config,
					'relation' => 'AND',
					array(
						'setting'  => AAA_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => AAA_THEME_SETTINGS . '[related-posts-structure]',
						'operator' => 'contains',
						'value'    => 'title-meta',
					),
				),
				'title'     => __( 'Content Colors', 'astra' ),
				'section'   => 'ast-sub-section-related-posts',
				'priority'  => 15,
				'divider'   => array( 'ast_class' => 'ast-bottom-divider' ),
			),

			/**
			 * Option: Related Posts title typography setting group
			 */
			array(
				'name'      => AAA_THEME_SETTINGS . '[related-posts-section-title-typography-group]',
				'type'      => 'control',
				'priority'  => 16,
				'control'   => 'ast-settings-group',
				'is_font'   => true,
				'context'   => array(
					AAA_Chinese_Builder_Helper::$design_tab_config,
					'relation' => 'AND',
					array(
						'setting'  => AAA_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => AAA_THEME_SETTINGS . '[related-posts-title]',
						'operator' => '!=',
						'value'    => '',
					),
				),
				'title'     => __( 'Section Title Font', 'astra' ),
				'section'   => 'ast-sub-section-related-posts',
				'transport' => 'postMessage',
			),

			/**
			 * Option: Related Posts title typography setting group
			 */
			array(
				'name'      => AAA_THEME_SETTINGS . '[related-posts-title-typography-group]',
				'type'      => 'control',
				'priority'  => 17,
				'control'   => 'ast-settings-group',
				'context'   => array(
					AAA_Chinese_Builder_Helper::$design_tab_config,
					'relation' => 'AND',
					array(
						'setting'  => AAA_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => AAA_THEME_SETTINGS . '[related-posts-structure]',
						'operator' => 'contains',
						'value'    => 'title-meta',
					),
				),
				'title'     => __( 'Post Title Font', 'astra' ),
				'is_font'   => true,
				'section'   => 'ast-sub-section-related-posts',
				'transport' => 'postMessage',
			),

			/**
			 * Option: Related Posts meta typography setting group
			 */
			array(
				'name'      => AAA_THEME_SETTINGS . '[related-posts-meta-typography-group]',
				'type'      => 'control',
				'priority'  => 18,
				'control'   => 'ast-settings-group',
				'context'   => array(
					AAA_Chinese_Builder_Helper::$design_tab_config,
					'relation' => 'AND',
					array(
						'setting'  => AAA_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => AAA_THEME_SETTINGS . '[related-posts-structure]',
						'operator' => 'contains',
						'value'    => 'title-meta',
					),
				),
				'title'     => __( 'Meta Font', 'astra' ),
				'is_font'   => true,
				'section'   => 'ast-sub-section-related-posts',
				'transport' => 'postMessage',
			),

			/**
			 * Option: Related Posts content typography setting group
			 */
			array(
				'name'      => AAA_THEME_SETTINGS . '[related-posts-content-typography-group]',
				'type'      => 'control',
				'priority'  => 21,
				'control'   => 'ast-settings-group',
				'is_font'   => true,
				'context'   => array(
					AAA_Chinese_Builder_Helper::$design_tab_config,
					'relation' => 'AND',
					array(
						'setting'  => AAA_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => AAA_THEME_SETTINGS . '[enable-related-posts-excerpt]',
						'operator' => '==',
						'value'    => true,
					),
				),
				'title'     => __( 'Content Font', 'astra' ),
				'section'   => 'ast-sub-section-related-posts',
				'transport' => 'postMessage',
			),

			/**
			 * Option: Related post block text color
			 */
			array(
				'name'      => 'related-posts-text-color',
				'tab'       => __( 'Normal', 'astra' ),
				'type'      => 'sub-control',
				'parent'    => AAA_THEME_SETTINGS . '[related-posts-colors-group]',
				'section'   => 'ast-sub-section-related-posts',
				'default'   => aaa_chinese_get_option( 'related-posts-text-color' ),
				'transport' => 'postMessage',
				'control'   => 'ast-color',
				'title'     => __( 'Text Color', 'astra' ),
			),

			/**
			 * Option: Related post block CTA link color
			 */
			array(
				'name'      => 'related-posts-link-color',
				'tab'       => __( 'Normal', 'astra' ),
				'type'      => 'sub-control',
				'parent'    => AAA_THEME_SETTINGS . '[related-posts-colors-group]',
				'section'   => 'ast-sub-section-related-posts',
				'default'   => aaa_chinese_get_option( 'related-posts-link-color' ),
				'transport' => 'postMessage',
				'control'   => 'ast-color',
				'title'     => __( 'Link Color', 'astra' ),
			),

			/**
			 * Option: Related post block BG color
			 */
			array(
				'name'              => AAA_THEME_SETTINGS . '[related-posts-title-color]',
				'default'           => aaa_chinese_get_option( 'related-posts-title-color' ),
				'type'              => 'control',
				'control'           => 'ast-color',
				'sanitize_callback' => array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_alpha_color' ),
				'section'           => 'ast-sub-section-related-posts',
				'transport'         => 'postMessage',
				'priority'          => 14,
				'context'           => array(
					AAA_Chinese_Builder_Helper::$design_tab_config,
					'relation' => 'AND',
					array(
						'setting'  => AAA_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => AAA_THEME_SETTINGS . '[related-posts-title]',
						'operator' => '!=',
						'value'    => '',
					),
				),
				'title'             => __( 'Section Title', 'astra' ),
				'divider'           => array( 'ast_class' => 'ast-top-section-spacing' ),
			),

			/**
			 * Option: Related post block BG color
			 */
			array(
				'name'              => AAA_THEME_SETTINGS . '[related-posts-background-color]',
				'default'           => aaa_chinese_get_option( 'related-posts-background-color' ),
				'type'              => 'control',
				'control'           => 'ast-color',
				'sanitize_callback' => array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_alpha_color' ),
				'section'           => 'ast-sub-section-related-posts',
				'transport'         => 'postMessage',
				'priority'          => 14,
				'context'           => array(
					AAA_Chinese_Builder_Helper::$design_tab_config,
					array(
						'setting'  => AAA_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
				),
				'title'             => __( 'Section Background', 'astra' ),
				'divider'           => array( 'ast_class' => 'ast-bottom-divider' ),
			),

			/**
			 * Option: Related post meta color
			 */
			array(
				'name'      => 'related-posts-meta-color',
				'default'   => aaa_chinese_get_option( 'related-posts-meta-color' ),
				'tab'       => __( 'Normal', 'astra' ),
				'type'      => 'sub-control',
				'parent'    => AAA_THEME_SETTINGS . '[related-posts-colors-group]',
				'section'   => 'ast-sub-section-related-posts',
				'transport' => 'postMessage',
				'control'   => 'ast-color',
				'title'     => __( 'Meta Color', 'astra' ),
			),

			/**
			 * Option: Related hover CTA link color
			 */
			array(
				'name'      => 'related-posts-link-hover-color',
				'type'      => 'sub-control',
				'tab'       => __( 'Hover', 'astra' ),
				'parent'    => AAA_THEME_SETTINGS . '[related-posts-colors-group]',
				'section'   => 'ast-sub-section-related-posts',
				'control'   => 'ast-color',
				'default'   => aaa_chinese_get_option( 'related-posts-link-hover-color' ),
				'transport' => 'postMessage',
				'title'     => __( 'Link Color', 'astra' ),
			),

			/**
			 * Option: Related hover meta link color
			 */
			array(
				'name'      => 'related-posts-meta-link-hover-color',
				'type'      => 'sub-control',
				'tab'       => __( 'Hover', 'astra' ),
				'parent'    => AAA_THEME_SETTINGS . '[related-posts-colors-group]',
				'section'   => 'ast-sub-section-related-posts',
				'control'   => 'ast-color',
				'default'   => aaa_chinese_get_option( 'related-posts-meta-link-hover-color' ),
				'transport' => 'postMessage',
				'title'     => __( 'Meta Link Color', 'astra' ),
			),

			/**
			 * Option: Related Posts Title Font Family
			 */
			array(
				'name'      => 'related-posts-title-font-family',
				'parent'    => AAA_THEME_SETTINGS . '[related-posts-title-typography-group]',
				'section'   => 'ast-sub-section-related-posts',
				'type'      => 'sub-control',
				'control'   => 'ast-font',
				'font_type' => 'ast-font-family',
				'default'   => aaa_chinese_get_option( 'related-posts-title-font-family' ),
				'title'     => __( 'Font Family', 'astra' ),
				'connect'   => AAA_THEME_SETTINGS . '[related-posts-title-font-weight]',
				'divider'   => array( 'ast_class' => 'ast-sub-bottom-divider' ),
			),

			/**
			 * Option: Related Posts Title Font Weight
			 */
			array(
				'name'              => 'related-posts-title-font-weight',
				'parent'            => AAA_THEME_SETTINGS . '[related-posts-title-typography-group]',
				'section'           => 'ast-sub-section-related-posts',
				'type'              => 'sub-control',
				'control'           => 'ast-font',
				'font_type'         => 'ast-font-weight',
				'sanitize_callback' => array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_font_weight' ),
				'default'           => aaa_chinese_get_option( 'related-posts-title-font-weight' ),
				'title'             => __( 'Font Weight', 'astra' ),
				'connect'           => 'related-posts-title-font-family',
				'divider'           => array( 'ast_class' => 'ast-sub-bottom-divider' ),
			),

			/**
			 * Option: Related Posts Title Font Size
			 */

			array(
				'name'              => 'related-posts-title-font-size',
				'parent'            => AAA_THEME_SETTINGS . '[related-posts-title-typography-group]',
				'section'           => 'ast-sub-section-related-posts',
				'type'              => 'sub-control',
				'control'           => 'ast-responsive-slider',
				'default'           => aaa_chinese_get_option( 'related-posts-title-font-size' ),
				'transport'         => 'postMessage',
				'title'             => __( 'Font Size', 'astra' ),
				'sanitize_callback' => array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
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
			),

			/**
			 * Option: Related Posts Title Font Extras
			 */
			array(
				'name'    => 'related-posts-title-font-extras',
				'type'    => 'sub-control',
				'parent'  => AAA_THEME_SETTINGS . '[related-posts-title-typography-group]',
				'control' => 'ast-font-extras',
				'section' => 'ast-sub-section-related-posts',
				'default' => aaa_chinese_get_option( 'related-posts-title-font-extras' ),
				'title'   => __( 'Font Extras', 'astra' ),
			),

			/**
			 * Option: Related Posts Title Font Family
			 */
			array(
				'name'      => 'related-posts-section-title-font-family',
				'parent'    => AAA_THEME_SETTINGS . '[related-posts-section-title-typography-group]',
				'section'   => 'ast-sub-section-related-posts',
				'type'      => 'sub-control',
				'control'   => 'ast-font',
				'font_type' => 'ast-font-family',
				'default'   => aaa_chinese_get_option( 'related-posts-section-title-font-family' ),
				'title'     => __( 'Font Family', 'astra' ),
				'connect'   => AAA_THEME_SETTINGS . '[related-posts-section-title-font-weight]',
				'divider'   => array( 'ast_class' => 'ast-sub-bottom-divider' ),
			),

			/**
			 * Option: Related Posts Title Font Weight
			 */
			array(
				'name'              => 'related-posts-section-title-font-weight',
				'parent'            => AAA_THEME_SETTINGS . '[related-posts-section-title-typography-group]',
				'section'           => 'ast-sub-section-related-posts',
				'type'              => 'sub-control',
				'control'           => 'ast-font',
				'font_type'         => 'ast-font-weight',
				'sanitize_callback' => array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_font_weight' ),
				'default'           => aaa_chinese_get_option( 'related-posts-section-title-font-weight' ),
				'title'             => __( 'Font Weight', 'astra' ),
				'connect'           => 'related-posts-section-title-font-family',
				'divider'           => array( 'ast_class' => 'ast-sub-bottom-divider' ),
			),

			/**
			 * Option: Related Posts Title Font Size
			 */

			array(
				'name'              => 'related-posts-section-title-font-size',
				'parent'            => AAA_THEME_SETTINGS . '[related-posts-section-title-typography-group]',
				'section'           => 'ast-sub-section-related-posts',
				'type'              => 'sub-control',
				'control'           => 'ast-responsive-slider',
				'default'           => aaa_chinese_get_option( 'related-posts-section-title-font-size' ),
				'transport'         => 'postMessage',
				'title'             => __( 'Font Size', 'astra' ),
				'sanitize_callback' => array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
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
			),

			/**
			 * Option: Related Posts Title Font Extras
			 */
			array(
				'name'    => 'related-posts-section-title-font-extras',
				'type'    => 'sub-control',
				'parent'  => AAA_THEME_SETTINGS . '[related-posts-section-title-typography-group]',
				'control' => 'ast-font-extras',
				'section' => 'ast-sub-section-related-posts',
				'default' => aaa_chinese_get_option( 'related-posts-section-title-font-extras' ),
				'title'   => __( 'Font Extras', 'astra' ),
			),

			/**
			 * Option: Related Posts Meta Font Family
			 */
			array(
				'name'      => 'related-posts-meta-font-family',
				'parent'    => AAA_THEME_SETTINGS . '[related-posts-meta-typography-group]',
				'section'   => 'ast-sub-section-related-posts',
				'type'      => 'sub-control',
				'control'   => 'ast-font',
				'font_type' => 'ast-font-family',
				'default'   => aaa_chinese_get_option( 'related-posts-meta-font-family' ),
				'title'     => __( 'Font Family', 'astra' ),
				'connect'   => AAA_THEME_SETTINGS . '[related-posts-meta-font-weight]',
				'divider'   => array( 'ast_class' => 'ast-sub-bottom-divider' ),
			),

			/**
			 * Option: Related Posts Meta Font Weight
			 */
			array(
				'name'              => 'related-posts-meta-font-weight',
				'parent'            => AAA_THEME_SETTINGS . '[related-posts-meta-typography-group]',
				'section'           => 'ast-sub-section-related-posts',
				'type'              => 'sub-control',
				'control'           => 'ast-font',
				'font_type'         => 'ast-font-weight',
				'sanitize_callback' => array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_font_weight' ),
				'default'           => aaa_chinese_get_option( 'related-posts-meta-font-weight' ),
				'title'             => __( 'Font Weight', 'astra' ),
				'connect'           => 'related-posts-meta-font-family',
				'divider'           => array( 'ast_class' => 'ast-sub-bottom-divider' ),
			),

			/**
			 * Option: Related Posts Meta Font Size
			 */

			array(
				'name'              => 'related-posts-meta-font-size',
				'parent'            => AAA_THEME_SETTINGS . '[related-posts-meta-typography-group]',
				'section'           => 'ast-sub-section-related-posts',
				'type'              => 'sub-control',
				'control'           => 'ast-responsive-slider',
				'default'           => aaa_chinese_get_option( 'related-posts-meta-font-size' ),
				'transport'         => 'postMessage',
				'title'             => __( 'Font Size', 'astra' ),
				'sanitize_callback' => array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
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
			),

			/**
			 * Option: Related Posts Meta Font Extras
			 */
			array(
				'name'    => 'related-posts-meta-font-extras',
				'type'    => 'sub-control',
				'parent'  => AAA_THEME_SETTINGS . '[related-posts-meta-typography-group]',
				'control' => 'ast-font-extras',
				'section' => 'ast-sub-section-related-posts',
				'default' => aaa_chinese_get_option( 'related-posts-meta-font-extras' ),
				'title'   => __( 'Font Extras', 'astra' ),
			),

			/**
			 * Option: Related Posts Content Font Family
			 */
			array(
				'name'      => 'related-posts-content-font-family',
				'parent'    => AAA_THEME_SETTINGS . '[related-posts-content-typography-group]',
				'section'   => 'ast-sub-section-related-posts',
				'type'      => 'sub-control',
				'control'   => 'ast-font',
				'font_type' => 'ast-font-family',
				'default'   => aaa_chinese_get_option( 'related-posts-content-font-family' ),
				'title'     => __( 'Font Family', 'astra' ),
				'connect'   => AAA_THEME_SETTINGS . '[related-posts-content-font-weight]',
				'divider'   => array( 'ast_class' => 'ast-sub-bottom-divider' ),
			),

			/**
			 * Option: Related Posts Content Font Weight
			 */
			array(
				'name'              => 'related-posts-content-font-weight',
				'parent'            => AAA_THEME_SETTINGS . '[related-posts-content-typography-group]',
				'section'           => 'ast-sub-section-related-posts',
				'type'              => 'sub-control',
				'control'           => 'ast-font',
				'font_type'         => 'ast-font-weight',
				'sanitize_callback' => array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_font_weight' ),
				'default'           => aaa_chinese_get_option( 'related-posts-content-font-weight' ),
				'title'             => __( 'Font Weight', 'astra' ),
				'connect'           => 'related-posts-content-font-family',
				'divider'           => array( 'ast_class' => 'ast-sub-bottom-divider' ),
			),

			/**
			 * Option: Related Posts Content Font Size
			 */
			array(
				'name'              => 'related-posts-content-font-size',
				'parent'            => AAA_THEME_SETTINGS . '[related-posts-content-typography-group]',
				'section'           => 'ast-sub-section-related-posts',
				'type'              => 'sub-control',
				'control'           => 'ast-responsive-slider',
				'default'           => aaa_chinese_get_option( 'related-posts-content-font-size' ),
				'transport'         => 'postMessage',
				'title'             => __( 'Font Size', 'astra' ),
				'sanitize_callback' => array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
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
			),

			/**
			 * Option: Related Posts Content Font Extras.
			 */
			/**
			 * Option: Related Posts Meta Font Extras
			 */
			array(
				'name'    => 'related-posts-content-font-extras',
				'type'    => 'sub-control',
				'parent'  => AAA_THEME_SETTINGS . '[related-posts-content-typography-group]',
				'control' => 'ast-font-extras',
				'section' => 'ast-sub-section-related-posts',
				'default' => aaa_chinese_get_option( 'related-posts-content-font-extras' ),
				'title'   => __( 'Font Extras', 'astra' ),
			),
		);

		$_configs = array_merge( $_configs, AAA_Chinese_Extended_Base_Configuration::prepare_section_spacing_border_options( 'ast-sub-section-related-posts' ) );

		return array_merge( $configurations, $_configs );
	}
}

/**
 *  Kicking this off by creating NEW instance.
 */
new AAA_Chinese_Related_Posts_Configs();
