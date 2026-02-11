<?php
/**
 * AAA Chinese Builder Controller.
 *
 * @package astra-builder
 * @since 3.0.0
 */

// No direct access, please.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class AAA_Chinese_Builder_Customizer.
 *
 * Customizer Configuration for Header Footer Builder.
 *
 * @since 3.0.0
 */
final class AAA_Chinese_Builder_Customizer {
	/**
	 * Constructor
	 *
	 * @since 3.0.0
	 */
	public function __construct() {

		add_action( 'customize_preview_init', array( $this, 'enqueue_customizer_preview_scripts' ) );
		add_action( 'customize_register', array( $this, 'woo_header_configs' ), 2 );

		$this->load_extended_components();

		if ( false === AAA_Chinese_Builder_Helper::$is_header_footer_builder_active ) {
			return;
		}

		require_once AAA_THEME_DIR . 'inc/customizer/configurations/builder/class-aaa-chinese-builder-base-configuration.php';
		// Base Config Files.
		require_once AAA_THEME_DIR . 'inc/customizer/configurations/builder/base/class-aaa-chinese-social-icon-component-configs.php';
		require_once AAA_THEME_DIR . 'inc/customizer/configurations/builder/base/class-aaa-chinese-html-component-configs.php';
		require_once AAA_THEME_DIR . 'inc/customizer/configurations/builder/base/class-aaa-chinese-button-component-configs.php';

		define( 'AAA_HEADER_BUILDER_CONFIGS_DIR', AAA_THEME_DIR . 'inc/customizer/configurations/builder/header/configs/' );
		foreach ( scandir( AAA_HEADER_BUILDER_CONFIGS_DIR ) as $config_file ) {
			$path = AAA_HEADER_BUILDER_CONFIGS_DIR . $config_file;
			if ( is_file( $path ) ) {
				require_once $path;
			}
		}

		define( 'AAA_FOOTER_BUILDER_CONFIGS_DIR', AAA_THEME_DIR . 'inc/customizer/configurations/builder/footer/configs/' );
		foreach ( scandir( AAA_FOOTER_BUILDER_CONFIGS_DIR ) as $config_file ) {
			$path = AAA_FOOTER_BUILDER_CONFIGS_DIR . $config_file;
			if ( is_file( $path ) ) {
				require_once $path;
			}
		}

		$this->load_base_components();

		add_action( 'customize_register', array( $this, 'builder_configs' ), 2 );
		add_action( 'customize_register', array( $this, 'header_configs' ), 2 );
		add_action( 'customize_register', array( $this, 'footer_configs' ), 2 );
		add_action( 'customize_register', array( $this, 'update_default_wp_configs' ) );
		add_action( 'init', array( $this, 'deregister_menu_locations_widgets' ), 999 );
		add_action( 'customize_controls_print_footer_scripts', array( $this, 'builder_customizer_preview_styles' ) );
	}

	/**
	 * Update default WP configs.
	 *
	 * @param object $wp_customize customizer object.
	 */
	public function update_default_wp_configs( $wp_customize ) {

		$wp_customize->get_control( 'custom_logo' )->priority     = 2;
		$wp_customize->get_control( 'blogname' )->priority        = 8;
		$wp_customize->get_control( 'blogdescription' )->priority = 12;

		$wp_customize->get_setting( 'custom_logo' )->transport     = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

		$wp_customize->get_section( 'title_tagline' )->panel = 'panel-header-builder-group';

		$wp_customize->selective_refresh->add_partial(
			'custom_logo',
			array(
				'selector'            => '.site-branding',
				'container_inclusive' => true,
				'render_callback'     => 'AAA_Chinese_Builder_Header::site_identity',
			)
		);

		// @codingStandardsIgnoreStart PHPCompatibility.FunctionDeclarations.NewClosure.Found
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => static function() {
					bloginfo( 'description' );
				},
			)
		);

		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title',
				'render_callback' => static function() {
					bloginfo( 'name' );
				},
			)
		);

		// @codingStandardsIgnoreStart PHPCompatibility.FunctionDeclarations.NewClosure.Found
	}

	/**
	 * Function to remove old Header and Footer Menu location and widgets.
	 *
	 * @since 3.0.0
	 * @return void
	 */
	public function deregister_menu_locations_widgets() {

		// Remove Header Menus locations.
		unregister_nav_menu( 'above_header_menu' );
		unregister_nav_menu( 'below_header_menu' );

		// Remove Header Widgets.
		unregister_sidebar( 'above-header-widget-1' );
		unregister_sidebar( 'above-header-widget-2' );
		unregister_sidebar( 'below-header-widget-1' );
		unregister_sidebar( 'below-header-widget-2' );

		// Remove Footer Widgets.
		unregister_sidebar( 'advanced-footer-widget-1' );
		unregister_sidebar( 'advanced-footer-widget-2' );
		unregister_sidebar( 'advanced-footer-widget-3' );
		unregister_sidebar( 'advanced-footer-widget-4' );
		unregister_sidebar( 'advanced-footer-widget-5' );
	}

	/**
	 * Attach customize_controls_print_footer_scripts preview styles conditionally.
	 *
	 * @since 3.0.0
	 */
	public function builder_customizer_preview_styles() {
		/**
		 * Added AAA Chinese Pro dependent customizer style.
		 */
		if ( is_customize_preview() ) {
			echo '<style type="text/css">
				.ahfb-builder-mode-header[data-row="above"] .ahfb-row-actions, .ahfb-builder-mode-header[data-row="below"] .ahfb-row-actions, .ahfb-builder-mode-footer[data-row="above"] .ahfb-row-actions, .ahfb-builder-mode-footer[data-row="primary"] .ahfb-row-actions {
					cursor: pointer;
				}
			</style>';
			if ( aaa_chinese_wp_version_compare( '6.1', '<' ) ) {
				echo '<style type="text/css" class="astra-wp-6-0-builder-popover-compatibility">
					.components-popover.ahfb-popover-add-builder {
						left: 50% !important;
						top: 0 !important;
						position: absolute;
						bottom: auto;
					}
					.ahfb-builder-group .ahfb-builder-area:nth-child(3) .ahfb-builder-add-item.center-on-left .components-popover.ahfb-popover-add-builder, .ahfb-builder-group .ahfb-builder-area:nth-child(4) .ahfb-builder-add-item .components-popover.ahfb-popover-add-builder, .ahfb-builder-group .ahfb-builder-area:nth-child(5) .ahfb-builder-add-item .components-popover.ahfb-popover-add-builder, .ahfb-builder-group.ast-grid-row-layout-3-cwide .ahfb-builder-area-3 .ahfb-builder-add-item .components-popover.ahfb-popover-add-builder {
						left: -20% !important;
					}
					.ahfb-builder-group.ast-grid-row-layout-6-equal .ahfb-builder-area-6 .ahfb-builder-add-item .components-popover.ahfb-popover-add-builder {
						left: -35% !important;
					}
					.customize-control-ast-builder .components-popover.ahfb-popover-add-builder[data-x-axis="center"] {
						left: 160px !important;
					}
					.customize-control-ast-builder .components-popover.ahfb-popover-add-builder[data-x-axis="right"] {
						left: 0px !important;
					}
					.components-popover.ahfb-popover-add-builder .components-popover__content {
						bottom: 0;
					}
					</style>
				';
			}
			if ( aaa_chinese_wp_version_compare( '6.2', '>=' ) ) {
				echo '<style type="text/css" class="astra-wp-6-2-builder-popover-compatibility">
					.popup-vertical-group .components-popover.ahfb-popover-add-builder {
						left: 18% !important;
					}
					</style>
				';
			}
		}
	}

	/**
	 * Add Customizer preview script.
	 *
	 * @since 3.0.0
	 */
	public function enqueue_customizer_preview_scripts() {
		// Bail early if it is not astra customizer.
		if ( ! AAA_Chinese_Customizer::is_aaa_chinese_customizer() ) {
			return;
		}

		// Enqueue Builder CSS.
		wp_enqueue_style(
			'ahfb-customizer-preview-style',
			AAA_THEME_URI . 'inc/assets/css/customizer-preview.css',
			null,
			AAA_THEME_VERSION
		);

		// Advanced Dynamic CSS.
		wp_enqueue_script(
			'ahfb-customizer-preview',
			AAA_THEME_URI . 'inc/assets/js/customizer-preview.js',
			array( 'customize-preview' ),
			AAA_THEME_VERSION,
			true
		);

		// Base Dynamic CSS.
		wp_enqueue_script(
			'ahfb-base-customizer-preview',
			AAA_THEME_URI . 'inc/builder/type/base/assets/js/customizer-preview.js',
			array( 'customize-preview' ),
			AAA_THEME_VERSION,
			true
		);

		// Localize variables for AAA Chinese Breakpoints JS.
		wp_localize_script(
			'ahfb-base-customizer-preview',
			'astraBuilderPreview',
			array(
				'tablet_break_point' => aaa_chinese_get_tablet_breakpoint(),
				'mobile_break_point' => aaa_chinese_get_mobile_breakpoint(),
			)
		);

		wp_localize_script(
			'ahfb-customizer-preview',
			'astraBuilderCustomizer',
			array(
				'ajaxurl'    => admin_url( 'admin-ajax.php' ),
				'ajax_nonce' => wp_create_nonce( 'astra-builder-customizer-nonce' ),
			)
		);
	}

	/**
	 * Register Some extended work for both old-new header footer layouts.
	 *
	 * @since 4.6.5
	 */
	public function load_extended_components() {
		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		require_once AAA_THEME_DIR . 'inc/customizer/class-aaa-chinese-extended-base-configuration.php';
		require_once AAA_THEME_DIR . 'inc/class-aaa-chinese-extended-base-dynamic-css.php';
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}

	/**
	 * Register Base Components for Builder.
	 */
	public function load_base_components() {

		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		require_once AAA_THEME_DIR . 'inc/builder/type/class-aaa-chinese-builder-base-dynamic-css.php';

		// Base Dynamic CSS Files.
		require_once AAA_THEME_DIR . 'inc/builder/type/base/dynamic-css/html/class-aaa-chinese-html-component-dynamic-css.php';
		require_once AAA_THEME_DIR . 'inc/builder/type/base/dynamic-css/social/class-aaa-chinese-social-component-dynamic-css.php';
		require_once AAA_THEME_DIR . 'inc/builder/type/base/dynamic-css/button/class-aaa-chinese-button-component-dynamic-css.php';
		require_once AAA_THEME_DIR . 'inc/builder/type/base/dynamic-css/widget/class-aaa-chinese-widget-component-dynamic-css.php';

		$this->load_header_components();
		$this->load_footer_components();
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}

	/**
	 * Register Components for Header Builder.
	 *
	 * @since 3.0.0
	 */
	public function load_header_components() {
		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		$header_components_path = AAA_THEME_DIR . 'inc/builder/type/header';
		require_once $header_components_path . '/site-identity/class-aaa-chinese-header-site-identity-component.php';
		require_once $header_components_path . '/off-canvas/class-aaa-chinese-off-canvas.php';
		require_once $header_components_path . '/primary-header/class-aaa-chinese-primary-header.php';
		require_once $header_components_path . '/button/class-aaa-chinese-header-button-component.php';
		require_once $header_components_path . '/menu/class-aaa-chinese-header-menu-component.php';
		require_once $header_components_path . '/html/class-aaa-chinese-header-html-component.php';
		require_once $header_components_path . '/search/class-aaa-chinese-header-search-component.php';
		require_once $header_components_path . '/account/class-aaa-chinese-header-account-component.php';
		require_once $header_components_path . '/social-icon/class-aaa-chinese-header-social-icon-component.php';
		require_once $header_components_path . '/widget/class-aaa-chinese-header-widget-component.php';
		require_once $header_components_path . '/mobile-trigger/class-aaa-chinese-mobile-trigger.php';
		require_once $header_components_path . '/mobile-menu/class-aaa-chinese-mobile-menu-component.php';

		require_once $header_components_path . '/above-header/class-aaa-chinese-above-header.php';
		require_once $header_components_path . '/below-header/class-aaa-chinese-below-header.php';

		if ( class_exists( 'AAA_Chinese_Woocommerce' ) ) {
			require_once $header_components_path . '/woo-cart/class-aaa-chinese-header-woo-cart-component.php';
		}

		if ( class_exists( 'Easy_Digital_Downloads' ) ) {
			require_once $header_components_path . '/edd-cart/class-aaa-chinese-header-edd-cart-component.php';
		}

		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}

	/**
	 * Register Components for Footer Builder.
	 *
	 * @since 3.0.0
	 */
	public function load_footer_components() {
		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		$footer_components_path = AAA_THEME_DIR . 'inc/builder/type/footer';
		require_once $footer_components_path . '/below-footer/class-aaa-chinese-below-footer.php';
		require_once $footer_components_path . '/menu/class-aaa-chinese-footer-menu-component.php';
		require_once $footer_components_path . '/html/class-aaa-chinese-footer-html-component.php';
		require_once $footer_components_path . '/button/class-aaa-chinese-footer-button-component.php';
		require_once $footer_components_path . '/copyright/class-aaa-chinese-footer-copyright-component.php';
		require_once $footer_components_path . '/social-icon/class-aaa-chinese-footer-social-icons-component.php';
		require_once $footer_components_path . '/above-footer/class-aaa-chinese-above-footer.php';
		require_once $footer_components_path . '/primary-footer/class-aaa-chinese-primary-footer.php';
		require_once $footer_components_path . '/widget/class-aaa-chinese-footer-widget-component.php';
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}

	/**
	 * Register controls for Header/Footer Builder.
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 * @since 3.0.0
	 */
	public function builder_configs( $wp_customize ) {
		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		$builder_config_path = AAA_THEME_DIR . 'inc/customizer/configurations/builder/';
		// Header Builder.
		require_once $builder_config_path . '/header/class-aaa-chinese-customizer-header-builder-configs.php';
		// Footer Builder.
		require_once $builder_config_path . '/footer/class-aaa-chinese-customizer-footer-builder-configs.php';
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}

	/**
	 * Register controls for Header Builder.
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 * @since 3.0.0
	 */
	public function header_configs( $wp_customize ) {
		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		$header_config_path = AAA_THEME_DIR . 'inc/customizer/configurations/builder/header';
		require_once $header_config_path . '/class-aaa-chinese-customizer-above-header-configs.php';
		require_once $header_config_path . '/class-aaa-chinese-customizer-below-header-configs.php';
		require_once $header_config_path . '/class-aaa-chinese-customizer-header-builder-configs.php';
		require_once $header_config_path . '/class-aaa-chinese-customizer-header-widget-configs.php';
		require_once $header_config_path . '/class-aaa-chinese-customizer-mobile-trigger-configs.php';
		require_once $header_config_path . '/class-aaa-chinese-customizer-off-canvas-configs.php';
		require_once $header_config_path . '/class-aaa-chinese-customizer-primary-header-configs.php';
		require_once $header_config_path . '/class-aaa-chinese-customizer-site-identity-configs.php';
		require_once $header_config_path . '/class-aaa-chinese-header-button-component-configs.php';
		require_once $header_config_path . '/class-aaa-chinese-header-html-component-configs.php';
		require_once $header_config_path . '/class-aaa-chinese-header-menu-component-configs.php';
		require_once $header_config_path . '/class-aaa-chinese-header-search-component-configs.php';
		require_once $header_config_path . '/class-aaa-chinese-header-account-component-configs.php';
		require_once $header_config_path . '/class-aaa-chinese-header-social-icon-component-configs.php';

		if ( class_exists( 'AAA_Chinese_Woocommerce' ) ) {
			require_once $header_config_path . '/class-aaa-chinese-customizer-woo-cart-configs.php';
		}

		if ( class_exists( 'Easy_Digital_Downloads' ) ) {
			require_once $header_config_path . '/class-aaa-chinese-customizer-edd-cart-configs.php';
		}

		require_once $header_config_path . '/class-aaa-chinese-mobile-menu-component-configs.php';
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}

	/**
	 * Register controls for Footer Builder.
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 * @since 3.0.0
	 */
	public function footer_configs( $wp_customize ) {
		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		$footer_config_path = AAA_THEME_DIR . 'inc/customizer/configurations/builder/footer';
		require_once $footer_config_path . '/class-aaa-chinese-customizer-above-footer-configs.php';
		require_once $footer_config_path . '/class-aaa-chinese-customizer-below-footer-configs.php';
		require_once $footer_config_path . '/class-aaa-chinese-customizer-copyright-configs.php';
		require_once $footer_config_path . '/class-aaa-chinese-customizer-footer-builder-configs.php';
		require_once $footer_config_path . '/class-aaa-chinese-customizer-footer-menu-configs.php';
		require_once $footer_config_path . '/class-aaa-chinese-customizer-footer-social-icons-configs.php';
		require_once $footer_config_path . '/class-aaa-chinese-customizer-primary-footer-configs.php';
		require_once $footer_config_path . '/class-aaa-chinese-footer-html-component-configs.php';
		require_once $footer_config_path . '/class-aaa-chinese-footer-button-component-configs.php';
		require_once $footer_config_path . '/class-aaa-chinese-footer-widget-component-configs.php';
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}

	/**
	 * Register Woocommerce controls for new and old Header Builder.
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 * @since 3.0.0
	 */
	public function woo_header_configs( $wp_customize ) {
		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		$header_config_path = AAA_THEME_DIR . 'inc/customizer/configurations/builder/header';

		if ( class_exists( 'AAA_Chinese_Woocommerce' ) ) {
			require_once $header_config_path . '/class-aaa-chinese-customizer-woo-cart-configs.php';
		}

		if ( class_exists( 'Easy_Digital_Downloads' ) ) {
			require_once $header_config_path . '/class-aaa-chinese-customizer-edd-cart-configs.php';
		}
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}

	/**
	 * Collect Customizer Builder Data to process further.
	 *
	 * @since 4.5.2
	 * @return bool
	 */
	public static function aaa_chinese_collect_customizer_builder_data() {
		return ! is_customize_preview() && apply_filters( 'aaa_chinese_collect_customizer_builder_data', false ) ? true : false;
	}
}

/**
 *  Prepare if class 'AAA_Chinese_Builder_Customizer' exist.
 *  Kicking this off by creating new object of the class.
 */
new AAA_Chinese_Builder_Customizer();
