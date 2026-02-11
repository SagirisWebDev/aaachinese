<?php
/**
 * Account Header Configuration.
 *
 * @package     AAA Chinese
 * @link        https://wpastra.com/
 * @since       4.5.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register account header builder Customizer Configurations.
 *
 * @since 4.5.2
 * @return array AAA Chinese Customizer Configurations with updated configurations.
 */
function aaa_chinese_header_account_configuration() {
	$_section = 'section-header-account';

	$account_choices = array(
		'default' => __( 'Default', 'astra' ),
	);

	$login_link_context = AAA_Chinese_Builder_Helper::$general_tab;

	$logout_link_context = array(
		'setting'  => AAA_THEME_SETTINGS . '[header-account-logout-style]',
		'operator' => '!=',
		'value'    => 'none',
	);

	if ( defined( 'AAA_EXT_VER' ) ) {

		$account_type_condition = array(
			'setting'  => AAA_THEME_SETTINGS . '[header-account-action-type]',
			'operator' => '==',
			'value'    => 'link',
		);

		if ( class_exists( 'LifterLMS' ) ) {
			$account_choices['lifterlms'] = __( 'LifterLMS', 'astra' );
		}

		if ( class_exists( 'WooCommerce' ) ) {
			$account_choices['woocommerce'] = __( 'WooCommerce', 'astra' );
		}

		if ( count( $account_choices ) > 1 ) {
			$account_type_condition = array(
				'setting'  => AAA_THEME_SETTINGS . '[header-account-type]',
				'operator' => '==',
				'value'    => 'default',
			);
		}

		$login_link_context = array(
			'relation' => 'AND',
			AAA_Chinese_Builder_Helper::$general_tab_config,
			array(
				'setting'  => AAA_THEME_SETTINGS . '[header-account-action-type]',
				'operator' => '==',
				'value'    => 'link',
			),
			array(
				'relation' => 'OR',
				$account_type_condition,
				array(
					'setting'  => AAA_THEME_SETTINGS . '[header-account-link-type]',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
		);

		$logout_link_context = array(
			'setting'  => AAA_THEME_SETTINGS . '[header-account-logout-action]',
			'operator' => '==',
			'value'    => 'link',
		);

	}

	$_configs = array(

		/*
		* Header Builder section
		*/
		array(
			'name'     => $_section,
			'type'     => 'section',
			'priority' => 80,
			'title'    => __( 'Account', 'astra' ),
			'panel'    => 'panel-header-builder-group',
		),

		/**
		 * Option: Header Builder Tabs
		 */
		array(
			'name'        => AAA_THEME_SETTINGS . '[header-account-tabs]',
			'section'     => $_section,
			'type'        => 'control',
			'control'     => 'ast-builder-header-control',
			'priority'    => 0,
			'description' => '',
			'divider'     => array( 'ast_class' => 'ast-bottom-spacing' ),
		),

		/**
		 * Option: Log In view
		 */
		array(
			'name'        => AAA_THEME_SETTINGS . '[header-account-login-heading]',
			'type'        => 'control',
			'control'     => 'ast-heading',
			'section'     => $_section,
			'priority'    => 1,
			'title'       => __( 'Logged In View', 'astra' ),
			'settings'    => array(),
			'input_attrs' => array(
				'class' => 'ast-control-reduce-top-space',
			),
			'divider'     => array( 'ast_class' => 'ast-section-spacing' ),
		),

		/**
		 * Option: Style
		 */
		array(
			'name'       => AAA_THEME_SETTINGS . '[header-account-login-style]',
			'default'    => aaa_chinese_get_option( 'header-account-login-style' ),
			'type'       => 'control',
			'control'    => 'ast-selector',
			'section'    => $_section,
			'priority'   => 3,
			'title'      => __( 'Profile Type', 'astra' ),
			'choices'    => array(
				'icon'   => __( 'Icon', 'astra' ),
				'avatar' => __( 'Avatar', 'astra' ),
				'text'   => __( 'Text', 'astra' ),
			),
			'transport'  => 'postMessage',
			'partial'    => array(
				'selector'        => '.ast-header-account',
				'render_callback' => array( 'AAA_Chinese_Builder_UI_Controller', 'render_account' ),
			),
			'responsive' => false,
			'renderAs'   => 'text',
			'divider'    => array( 'ast_class' => 'ast-bottom-divider' ),
		),

		/**
		 * Option: Show Text with
		 *
		 * @since 4.6.15
		 */
		array(
			'name'        => AAA_THEME_SETTINGS . '[header-account-login-style-extend-text-profile-type]',
			'default'     => aaa_chinese_get_option( 'header-account-login-style-extend-text-profile-type' ),
			'type'        => 'control',
			'control'     => 'ast-selector',
			'section'     => $_section,
			'priority'    => 3,
			'description' => __( 'Choose if you want to display Icon or Avatar with the Text selected Profile Type.', 'astra' ),
			'title'       => __( 'Show Text with', 'astra' ),
			'choices'     => array(
				'default' => __( 'Default', 'astra' ),
				'avatar'  => __( 'Avatar', 'astra' ),
				'icon'    => __( 'Icon', 'astra' ),
			),
			'transport'   => 'postMessage',
			'partial'     => array(
				'selector'        => '.ast-header-account',
				'render_callback' => array( 'AAA_Chinese_Builder_UI_Controller', 'render_account' ),
			),
			'responsive'  => false,
			'renderAs'    => 'text',
			'divider'     => array( 'ast_class' => 'ast-bottom-divider ast-section-spacing' ),
			'context'     => array(
				array(
					'setting'  => AAA_THEME_SETTINGS . '[header-account-login-style]',
					'operator' => '==',
					'value'    => 'text',
				),
				AAA_Chinese_Builder_Helper::$general_tab_config,
			),
		),

		/**
		 * Option: Logged Out Text
		 */
		array(
			'name'      => AAA_THEME_SETTINGS . '[header-account-logged-in-text]',
			'default'   => aaa_chinese_get_option( 'header-account-logged-in-text' ),
			'type'      => 'control',
			'control'   => 'ast-text-input',
			'section'   => $_section,
			'title'     => __( 'Text', 'astra' ),
			'priority'  => 3,
			'transport' => 'postMessage',
			'context'   => array(
				array(
					'setting'  => AAA_THEME_SETTINGS . '[header-account-login-style]',
					'operator' => '==',
					'value'    => 'text',
				),
				AAA_Chinese_Builder_Helper::$general_tab_config,
			),
			'partial'   => array(
				'selector'        => '.ast-header-account',
				'render_callback' => array( 'AAA_Chinese_Builder_UI_Controller', 'render_account' ),
			),
		),

		/**
		 * Option: Account Log In Link
		 */
		array(
			'name'              => AAA_THEME_SETTINGS . '[header-account-login-link]',
			'default'           => aaa_chinese_get_option( 'header-account-login-link' ),
			'type'              => 'control',
			'control'           => 'ast-link',
			'sanitize_callback' => array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_link' ),
			'section'           => $_section,
			'title'             => __( 'Account URL', 'astra' ),
			'priority'          => 6,
			'transport'         => 'postMessage',
			'context'           => $login_link_context,
			'divider'           => array( 'ast_class' => 'ast-section-spacing' ),
			'partial'           => array(
				'selector'        => '.ast-header-account',
				'render_callback' => array( 'AAA_Chinese_Builder_UI_Controller', 'render_account' ),
			),
		),

		/**
		 * Option: Log Out view
		 */
		array(
			'name'     => AAA_THEME_SETTINGS . '[header-account-logout-heading]',
			'type'     => 'control',
			'control'  => 'ast-heading',
			'section'  => $_section,
			'title'    => __( 'Logged Out View', 'astra' ),
			'priority' => 200,
			'settings' => array(),
			'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
		),

		/**
		 * Option: Style
		 */
		array(
			'name'       => AAA_THEME_SETTINGS . '[header-account-logout-style]',
			'default'    => aaa_chinese_get_option( 'header-account-logout-style' ),
			'type'       => 'control',
			'control'    => 'ast-selector',
			'section'    => $_section,
			'title'      => __( 'Profile Type', 'astra' ),
			'priority'   => 201,
			'choices'    => array(
				'none' => __( 'None', 'astra' ),
				'icon' => __( 'Icon', 'astra' ),
				'text' => __( 'Text', 'astra' ),
			),
			'transport'  => 'postMessage',
			'partial'    => array(
				'selector'        => '.ast-header-account',
				'render_callback' => array( 'AAA_Chinese_Builder_UI_Controller', 'render_account' ),
			),
			'responsive' => false,
			'renderAs'   => 'text',
		),

		/**
		 * Option: Show Text with
		 *
		 * @since 4.6.15
		 */
		array(
			'name'        => AAA_THEME_SETTINGS . '[header-account-logout-style-extend-text-profile-type]',
			'default'     => aaa_chinese_get_option( 'header-account-logout-style-extend-text-profile-type' ),
			'type'        => 'control',
			'control'     => 'ast-selector',
			'section'     => $_section,
			'priority'    => 202,
			'description' => __( 'Choose if you want to display Icon with the Text selected Profile Type for logged out users.', 'astra' ),
			'title'       => __( 'Show Text with', 'astra' ),
			'choices'     => array(
				'default' => __( 'Default', 'astra' ),
				'icon'    => __( 'Icon', 'astra' ),
			),
			'transport'   => 'postMessage',
			'partial'     => array(
				'selector'        => '.ast-header-account',
				'render_callback' => array( 'AAA_Chinese_Builder_UI_Controller', 'render_account' ),
			),
			'responsive'  => false,
			'renderAs'    => 'text',
			'divider'     => array( 'ast_class' => 'ast-top-divider ast-section-spacing' ),
			'context'     => array(
				array(
					'setting'  => AAA_THEME_SETTINGS . '[header-account-logout-style]',
					'operator' => '==',
					'value'    => 'text',
				),
				AAA_Chinese_Builder_Helper::$general_tab_config,
			),
		),

		// Option: Logged out options preview.
		array(
			'name'      => AAA_THEME_SETTINGS . '[header-account-logout-preview]',
			'default'   => aaa_chinese_get_option( 'header-account-logout-preview' ),
			'type'      => 'control',
			'control'   => 'ast-toggle-control',
			'section'   => $_section,
			'title'     => __( 'Preview', 'astra' ),
			'priority'  => 206,
			'context'   => array(
				array(
					'setting'  => AAA_THEME_SETTINGS . '[header-account-logout-style]',
					'operator' => '!=',
					'value'    => 'none',
				),
				AAA_Chinese_Builder_Helper::$general_tab_config,
			),
			'transport' => 'postMessage',
			'partial'   => array(
				'selector'        => '.ast-header-account',
				'render_callback' => array( 'AAA_Chinese_Builder_UI_Controller', 'render_account' ),
			),
			'divider'   => array( 'ast_class' => 'ast-top-divider' ),
		),

		/**
		 * Option: Logged Out Text
		 */
		array(
			'name'      => AAA_THEME_SETTINGS . '[header-account-logged-out-text]',
			'default'   => aaa_chinese_get_option( 'header-account-logged-out-text' ),
			'type'      => 'control',
			'control'   => 'text',
			'section'   => $_section,
			'title'     => __( 'Text', 'astra' ),
			'priority'  => 203,
			'transport' => 'postMessage',
			'context'   => array(
				array(
					'setting'  => AAA_THEME_SETTINGS . '[header-account-logout-style]',
					'operator' => '==',
					'value'    => 'text',
				),
				AAA_Chinese_Builder_Helper::$general_tab_config,
			),
			'partial'   => array(
				'selector'        => '.ast-header-account',
				'render_callback' => array( 'AAA_Chinese_Builder_UI_Controller', 'render_account' ),
			),
		),

		/**
		 * Option: Account Log Out Link
		 */
		array(
			'name'              => AAA_THEME_SETTINGS . '[header-account-logout-link]',
			'default'           => aaa_chinese_get_option( 'header-account-logout-link' ),
			'type'              => 'control',
			'control'           => 'ast-link',
			'sanitize_callback' => array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_link' ),
			'section'           => $_section,
			'title'             => __( 'Login URL', 'astra' ),
			'priority'          => 205,
			'transport'         => 'postMessage',
			'divider'           => array( 'ast_class' => 'ast-top-divider' ),
			'context'           => array(
				array(
					'setting'  => AAA_THEME_SETTINGS . '[header-account-logout-style]',
					'operator' => '!=',
					'value'    => 'none',
				),
				$logout_link_context,
				AAA_Chinese_Builder_Helper::$general_tab_config,
			),
		),

		/**
		 * Option: Image Width
		 */
		array(
			'name'              => AAA_THEME_SETTINGS . '[header-account-image-width]',
			'section'           => $_section,
			'priority'          => 2,
			'transport'         => 'postMessage',
			'default'           => aaa_chinese_get_option( 'header-account-image-width' ),
			'title'             => __( 'Avatar Width', 'astra' ),
			'type'              => 'control',
			'divider'           => defined( 'AAA_EXT_VER' ) ? array( 'ast_class' => 'ast-bottom-spacing' ) : array( 'ast_class' => 'ast-bottom-divider' ),
			'control'           => 'ast-responsive-slider',
			'sanitize_callback' => array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
			'input_attrs'       => array(
				'min'  => 0,
				'step' => 1,
				'max'  => 100,
			),
			'suffix'            => 'px',
			'context'           => array(
				array(
					'relation' => 'OR',
					array(
						'setting'  => AAA_THEME_SETTINGS . '[header-account-login-style]',
						'operator' => '==',
						'value'    => 'avatar',
					),
					array(
						'setting'  => AAA_THEME_SETTINGS . '[header-account-login-style-extend-text-profile-type]',
						'operator' => '==',
						'value'    => 'avatar',
					),
				),
				array(
					array(
						'setting'  => AAA_THEME_SETTINGS . '[header-account-login-style]',
						'operator' => '==',
						'value'    => 'text',
					),
					array(
						'setting'  => AAA_THEME_SETTINGS . '[header-account-login-style-extend-text-profile-type]',
						'operator' => '==',
						'value'    => 'avatar',
					),
				),
				AAA_Chinese_Builder_Helper::$design_tab_config,
			),
		),

		/**
		 * Option: account Size
		 */
		array(
			'name'              => AAA_THEME_SETTINGS . '[header-account-icon-size]',
			'section'           => $_section,
			'priority'          => 4,
			'transport'         => 'postMessage',
			'default'           => aaa_chinese_get_option( 'header-account-icon-size' ),
			'title'             => __( 'Icon Size', 'astra' ),
			'type'              => 'control',
			'suffix'            => 'px',
			'control'           => 'ast-responsive-slider',
			'divider'           => array( 'ast_class' => 'ast-bottom-divider' ),
			'sanitize_callback' => array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
			'input_attrs'       => array(
				'min'  => 0,
				'step' => 1,
				'max'  => 50,
			),
			'context'           => array(
				/**
				 * Other conditions are maintained from "inc/customizer/custom-controls/class-aaa-chinese-customizer-control-base.php".
				 */
				AAA_Chinese_Builder_Helper::$design_tab_config,
				array(
					'relation' => 'OR',
					array(
						'setting'  => AAA_THEME_SETTINGS . '[header-account-login-style]',
						'operator' => '==',
						'value'    => 'icon',
					),
					array(
						'setting'  => AAA_THEME_SETTINGS . '[header-account-logout-style]',
						'operator' => '==',
						'value'    => 'icon',
					),
				),
			),
		),

		/**
		 * Option: account Color.
		 */
		array(
			'name'              => AAA_THEME_SETTINGS . '[header-account-icon-color]',
			'default'           => aaa_chinese_get_option( 'header-account-icon-color' ),
			'type'              => 'control',
			'section'           => $_section,
			'priority'          => 5,
			'transport'         => 'postMessage',
			'control'           => 'ast-color',
			'divider'           => array( 'ast_class' => defined( 'AAA_EXT_VER' ) ? '' : 'ast-bottom-divider' ),
			'sanitize_callback' => array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_alpha_color' ),
			'title'             => __( 'Icon Color', 'astra' ),
			'context'           => array(
				/**
				 * Other conditions are maintained from "inc/customizer/custom-controls/class-aaa-chinese-customizer-control-base.php".
				 */
				AAA_Chinese_Builder_Helper::$design_tab_config,
				array(
					'relation' => 'OR',
					array(
						'setting'  => AAA_THEME_SETTINGS . '[header-account-login-style]',
						'operator' => '==',
						'value'    => 'icon',
					),
					array(
						'setting'  => AAA_THEME_SETTINGS . '[header-account-logout-style]',
						'operator' => '==',
						'value'    => 'icon',
					),
				),
			),
		),

		/**
		 * Option: Text design options.
		 */
		array(
			'name'     => AAA_THEME_SETTINGS . '[header-account-text-design-options]',
			'type'     => 'control',
			'control'  => 'ast-heading',
			'section'  => $_section,
			'priority' => 15,
			'title'    => __( 'Text Options', 'astra' ),
			'settings' => array(),
			'context'  => array(
				AAA_Chinese_Builder_Helper::$design_tab_config,
				array(
					'relation' => 'OR',
					array(
						'setting'  => AAA_THEME_SETTINGS . '[header-account-login-style]',
						'operator' => '==',
						'value'    => 'text',
					),
					array(
						'setting'  => AAA_THEME_SETTINGS . '[header-account-logout-style]',
						'operator' => '==',
						'value'    => 'text',
					),
				),
			),
			'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
		),

		/**
		 * Option: account Color.
		 */
		array(
			'name'              => AAA_THEME_SETTINGS . '[header-account-type-text-color]',
			'default'           => aaa_chinese_get_option( 'header-account-type-text-color' ),
			'type'              => 'control',
			'section'           => $_section,
			'priority'          => 18,
			'transport'         => 'postMessage',
			'control'           => 'ast-color',
			'divider'           => array( 'ast_class' => 'ast-top-section-spacing' ),
			'sanitize_callback' => array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_alpha_color' ),
			'title'             => __( 'Profile Text Color', 'astra' ),
			'context'           => array(
				AAA_Chinese_Builder_Helper::$design_tab_config,
				array(
					'relation' => 'OR',
					array(
						'setting'  => AAA_THEME_SETTINGS . '[header-account-login-style]',
						'operator' => '==',
						'value'    => 'text',
					),
					array(
						'setting'  => AAA_THEME_SETTINGS . '[header-account-logout-style]',
						'operator' => '==',
						'value'    => 'text',
					),
				),
			),
		),

		/**
		 * Option: Divider
		 */
		array(
			'name'     => AAA_THEME_SETTINGS . '[header-account-spacing-divider]',
			'section'  => 'section-header-account',
			'title'    => __( 'Spacing', 'astra' ),
			'type'     => 'control',
			'control'  => 'ast-heading',
			'priority' => 510,
			'settings' => array(),
			'context'  => AAA_Chinese_Builder_Helper::$design_tab,
			'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
		),

		/**
		 * Option: Margin Space
		 */
		array(
			'name'              => AAA_THEME_SETTINGS . '[header-account-margin]',
			'default'           => aaa_chinese_get_option( 'header-account-margin' ),
			'type'              => 'control',
			'transport'         => 'postMessage',
			'control'           => 'ast-responsive-spacing',
			'sanitize_callback' => array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
			'section'           => $_section,
			'priority'          => 511,
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
			'divider'           => array( 'ast_class' => 'ast-top-section-spacing' ),
		),
	);

	$_configs = array_merge(
		$_configs,
		AAA_Chinese_Builder_Base_Configuration::prepare_typography_options(
			$_section,
			array(
				AAA_Chinese_Builder_Helper::$design_tab_config,
				array(
					'relation' => 'OR',
					array(
						'setting'  => AAA_THEME_SETTINGS . '[header-account-login-style]',
						'operator' => '==',
						'value'    => 'text',
					),
					array(
						'setting'  => AAA_THEME_SETTINGS . '[header-account-logout-style]',
						'operator' => '==',
						'value'    => 'text',
					),
				),
			),
			array( 'ast_class' => 'ast-top-section-spacing' )
		)
	);

	$_configs = array_merge( $_configs, AAA_Chinese_Builder_Base_Configuration::prepare_visibility_tab( $_section ) );

	if ( AAA_Chinese_Builder_Customizer::aaa_chinese_collect_customizer_builder_data() ) {
		array_map( 'aaa_chinese_save_header_customizer_configs', $_configs );
	}

	return $_configs;
}

if ( AAA_Chinese_Builder_Customizer::aaa_chinese_collect_customizer_builder_data() ) {
	add_action( 'init', 'aaa_chinese_header_account_configuration' );
}
