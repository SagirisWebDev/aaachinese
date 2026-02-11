<?php
/**
 * Post Structures loader for AAA Chinese theme.
 *
 * @package     AAA Chinese
 * @link        https://www.brainstormforce.com
 * @since       AAA Chinese 4.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Customizer Initialization
 *
 * @since 4.0.0
 */
class AAA_Chinese_Posts_Structure_Loader {
	/**
	 * Instance
	 *
	 * @var array $customizer_defaults
	 */
	private static $customizer_defaults = array();

	/**
	 * Supported post types to process dynamic customizer.
	 *
	 * @var array $supported_post_types
	 */
	private static $supported_post_types = array();

	/**
	 *  Constructor
	 *
	 * @since 4.0.0
	 */
	public function __construct() {
		/**
		 * Update AAA Chinese default color and typography values. To not update directly on existing users site, added backwards.
		 *
		 * @since 4.0.0
		 */
		$apply_new_default_color_typo_values = AAA_Chinese_Dynamic_CSS::aaa_chinese_check_default_color_typo();

		self::$customizer_defaults['responsive-background'] = array(
			'desktop' => array(
				'background-color'      => '',
				'background-image'      => '',
				'background-repeat'     => 'repeat',
				'background-position'   => 'center center',
				'background-size'       => 'auto',
				'background-attachment' => 'scroll',
				'overlay-type'          => '',
				'overlay-color'         => '',
				'overlay-opacity'       => '',
				'overlay-gradient'      => '',
				'background-type'       => '',
				'background-media'      => '',
			),
			'tablet'  => array(
				'background-color'      => '',
				'background-image'      => '',
				'background-repeat'     => 'repeat',
				'background-position'   => 'center center',
				'background-size'       => 'auto',
				'background-attachment' => 'scroll',
				'overlay-type'          => '',
				'overlay-color'         => '',
				'overlay-opacity'       => '',
				'overlay-gradient'      => '',
				'background-type'       => '',
				'background-media'      => '',
			),
			'mobile'  => array(
				'background-color'      => '',
				'background-image'      => '',
				'background-repeat'     => 'repeat',
				'background-position'   => 'center center',
				'background-size'       => 'auto',
				'background-attachment' => 'scroll',
				'overlay-type'          => '',
				'overlay-color'         => '',
				'overlay-opacity'       => '',
				'overlay-gradient'      => '',
				'background-type'       => '',
				'background-media'      => '',
			),
		);
		self::$customizer_defaults['responsive-spacing']    = array(
			'desktop'      => array(
				'top'    => '',
				'right'  => '',
				'bottom' => '',
				'left'   => '',
			),
			'tablet'       => array(
				'top'    => '',
				'right'  => '',
				'bottom' => '',
				'left'   => '',
			),
			'mobile'       => array(
				'top'    => '',
				'right'  => '',
				'bottom' => '',
				'left'   => '',
			),
			'desktop-unit' => 'px',
			'tablet-unit'  => 'px',
			'mobile-unit'  => 'px',
		);
		self::$customizer_defaults['responsive-padding']    = array(
			'desktop'      => array(
				'top'    => 3,
				'right'  => 3,
				'bottom' => 3,
				'left'   => 3,
			),
			'tablet'       => array(
				'top'    => '',
				'right'  => '',
				'bottom' => '',
				'left'   => '',
			),
			'mobile'       => array(
				'top'    => '',
				'right'  => '',
				'bottom' => '',
				'left'   => '',
			),
			'desktop-unit' => 'em',
			'tablet-unit'  => 'em',
			'mobile-unit'  => 'em',
		);
		self::$customizer_defaults['font-size']             = array(
			'desktop'      => '',
			'tablet'       => '',
			'mobile'       => '',
			'desktop-unit' => 'px',
			'tablet-unit'  => 'px',
			'mobile-unit'  => 'px',
		);
		self::$customizer_defaults['title-font-size']       = array(
			'desktop'      => $apply_new_default_color_typo_values ? '32' : '',
			'tablet'       => '',
			'mobile'       => '',
			'desktop-unit' => 'px',
			'tablet-unit'  => 'px',
			'mobile-unit'  => 'px',
		);
		self::$customizer_defaults['title-font-weight']     = $apply_new_default_color_typo_values ? '600' : 'inherit';
		self::$customizer_defaults['responsive-slider']     = array(
			'desktop' => '',
			'tablet'  => '',
			'mobile'  => '',
		);
		self::$customizer_defaults['responsive-color']      = array(
			'desktop' => '',
			'tablet'  => '',
			'mobile'  => '',
		);
		self::$customizer_defaults['font-extras']           = array(
			'line-height'         => '',
			'line-height-unit'    => 'em',
			'letter-spacing'      => '',
			'letter-spacing-unit' => 'px',
			'text-transform'      => '',
			'text-decoration'     => '',
		);
		add_action( 'customize_register', array( $this, 'posts_structures_customize_register' ), 2 );
		add_action( 'aaa_chinese_get_fonts', array( $this, 'add_fonts' ), 1 );
		add_action( 'customize_preview_init', array( $this, 'preview_scripts' ) );
	}

	/**
	 * Enqueue google fonts.
	 *
	 * @return void
	 * @since 4.0.0
	 */
	public function add_fonts() {
		$post_types = self::get_supported_post_types();
		foreach ( $post_types as $post_type ) {
			// Single Banner - Font Support.
			$title_section = 'ast-dynamic-single-' . $post_type;

			$single_title_font_family = aaa_chinese_get_option( $title_section . '-title-font-family' );
			$single_title_font_weight = aaa_chinese_get_option( $title_section . '-title-font-weight' );
			AAA_Chinese_Fonts::add_font( $single_title_font_family, $single_title_font_weight );

			$single_text_font_family = aaa_chinese_get_option( $title_section . '-text-font-family' );
			$single_text_font_weight = aaa_chinese_get_option( $title_section . '-text-font-weight' );
			AAA_Chinese_Fonts::add_font( $single_text_font_family, $single_text_font_weight );

			$single_meta_font_family = aaa_chinese_get_option( $title_section . '-meta-font-family' );
			$single_meta_font_weight = aaa_chinese_get_option( $title_section . '-meta-font-weight' );
			AAA_Chinese_Fonts::add_font( $single_meta_font_family, $single_meta_font_weight );

			// Archive Banner - Font Support.
			$title_section = 'ast-dynamic-archive-' . $post_type;

			$archive_text_font_family = aaa_chinese_get_option( $title_section . '-text-font-family' );
			$archive_text_font_weight = aaa_chinese_get_option( $title_section . '-text-font-weight' );
			AAA_Chinese_Fonts::add_font( $archive_text_font_family, $archive_text_font_weight );

			$archive_title_font_family = aaa_chinese_get_option( $title_section . '-title-font-family' );
			$archive_title_font_weight = aaa_chinese_get_option( $title_section . '-title-font-weight' );
			AAA_Chinese_Fonts::add_font( $archive_title_font_family, $archive_title_font_weight );
		}

		foreach ( self::get_special_page_types() as $special_type ) {
			$title_section = 'section-' . $special_type . '-page-title';

			$instance_text_font_family = aaa_chinese_get_option( $title_section . '-text-font-family' );
			$instance_text_font_weight = aaa_chinese_get_option( $title_section . '-text-font-weight' );
			AAA_Chinese_Fonts::add_font( $instance_text_font_family, $instance_text_font_weight );

			$instance_title_font_family = aaa_chinese_get_option( $title_section . '-title-font-family' );
			$instance_title_font_weight = aaa_chinese_get_option( $title_section . '-title-font-weight' );
			AAA_Chinese_Fonts::add_font( $instance_title_font_family, $instance_title_font_weight );
		}
	}

	/**
	 * Add postMessage support for site title and description for the Theme Customizer.
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 *
	 * @since 4.0.0
	 */
	public function posts_structures_customize_register( $wp_customize ) {

		/**
		 * Register Config control in Related Posts.
		 */
		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		require_once AAA_THEME_POST_STRUCTURE_DIR . 'customizer/class-aaa-chinese-posts-structures-configs.php';
		require_once AAA_THEME_POST_STRUCTURE_DIR . 'customizer/class-aaa-chinese-posts-single-structures-configs.php';
		require_once AAA_THEME_POST_STRUCTURE_DIR . 'customizer/class-aaa-chinese-posts-archive-structures-configs.php';
		require_once AAA_THEME_POST_STRUCTURE_DIR . 'customizer/class-aaa-chinese-posts-special-archive-structures-configs.php';
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}

	/**
	 * Get all supported pots types & filter the public ones for further query.
	 *
	 * @since 4.0.0
	 * @return array $post_types
	 */
	public static function get_supported_post_types() {
		if ( empty( self::$supported_post_types ) || is_customize_preview() ) {
			self::$supported_post_types = aaa_chinese_get_queried_post_types();

			// If Elementor is active, reindex the array.
			if ( defined( 'ELEMENTOR_VERSION' ) ) {
				self::$supported_post_types = array_values( self::$supported_post_types );
			}
		}

		return apply_filters( 'aaa_chinese_dynamic_post_structure_posttypes', self::$supported_post_types );
	}

	/**
	 * Get special pages query.
	 *
	 * @since 4.6.0
	 * @return array $special_pages
	 */
	public static function get_special_page_types() {
		$special_pages = array(
			'search',
		);

		return apply_filters( 'aaa_chinese_dynamic_special_pages', $special_pages );
	}

	/**
	 * Customizer preview support.
	 *
	 * @since 4.0.0
	 */
	public function preview_scripts() {
		/** @psalm-suppress RedundantCondition */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
		$dir_name = SCRIPT_DEBUG ? 'unminified' : 'minified';
		/** @psalm-suppress RedundantCondition */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort

		/** @psalm-suppress RedundantCondition */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
		$file_prefix = SCRIPT_DEBUG ? '' : '.min';
		/** @psalm-suppress RedundantCondition */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort

		wp_enqueue_script( 'astra-post-strctures-customizer-preview', AAA_THEME_POST_STRUCTURE_URI . 'assets/js/' . $dir_name . '/customizer-preview' . $file_prefix . '.js', array( 'customize-preview', 'astra-customizer-preview-js' ), AAA_THEME_VERSION, true );

		// Localize variables for further JS.
		wp_localize_script(
			'astra-post-strctures-customizer-preview',
			'AAA ChinesePostStrcturesData',
			array(
				'post_types'           => self::get_supported_post_types(),
				'special_pages'        => self::get_special_page_types(),
				'tablet_break_point'   => aaa_chinese_get_tablet_breakpoint(),
				'mobile_break_point'   => aaa_chinese_get_mobile_breakpoint(),
				'enabled_related_post' => aaa_chinese_get_option( 'enable-related-posts', false ),
			)
		);
	}

	/**
	 * Get customizer dynamic default.
	 *
	 * @param string $key Retrieve default for this parameter.
	 *
	 * @since 4.0.0
	 */
	public static function get_customizer_default( $key ) {
		return isset( self::$customizer_defaults[ $key ] ) ? self::$customizer_defaults[ $key ] : array();
	}
}

/**
 * Initialize class object with 'new' instance.
 */
new AAA_Chinese_Posts_Structure_Loader();
