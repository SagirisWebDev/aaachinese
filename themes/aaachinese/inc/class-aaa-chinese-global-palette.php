<?php
/**
 * AAA Chinese Global color palette
 *
 * @package     AAA Chinese
 * @subpackage  Class
 *
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Global palette class
 */
class AAA_Chinese_Global_Palette {
	/**
	 * Indicates if the current palette is dark.
	 *
	 * @var bool $is_dark_palette
	 */
	private static $is_dark_palette;

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		/**
		 * Support for overriding theme.json from the child theme
		 *
		 * If theme.json is not present in the child theme load Global Color Palette in the editor using add_theme_support( 'editor-color-palette', $editor_palette );.
		 * This is a known issue in Gutenberg - If theme.json is not present in the child theme, it does fallback to the parent theme's theme.json file.
		 * This will be fixed in the future updates of WordPress/Gutenberg.
		 *
		 * @see https://github.com/WordPress/gutenberg/pull/34354
		 */
		$get_stylesheet = get_stylesheet_directory();
		$is_theme_json  = $get_stylesheet . '/theme.json';
		if ( ( get_template_directory() !== $get_stylesheet && false === file_exists( $is_theme_json ) ) || aaa_chinese_wp_version_compare( '5.8', '<' ) ) {
			add_action( 'after_setup_theme', array( $this, 'support_editor_color_palette' ) );
		}
		add_filter( 'aaa_chinese_theme_customizer_js_localize', array( $this, 'localize_variables' ) );
		add_filter( 'aaa_chinese_before_foreground_color_generation', array( $this, 'get_color_by_palette_variable' ) );
		$this->includes();
		add_filter( 'aaa_chinese_dynamic_theme_css', array( $this, 'global_border_compatibility' ) );
	}

	/**
	 * Modify color palette from Gutenberg.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function support_editor_color_palette() {
		$global_palette = aaa_chinese_get_option( 'global-color-palette' );
		$editor_palette = $this->format_global_palette( $global_palette );
		add_theme_support( 'editor-color-palette', $editor_palette );
	}

	/**
	 * Format color palette data required to pass for Gutenberg palette.
	 *
	 * @since 1.0.0
	 * @param array $global_palette global palette data.
	 * @return array
	 */
	public function format_global_palette( $global_palette ) {
		$editor_palette = array();
		$labels         = self::get_palette_labels();

		if ( isset( $global_palette['palette'] ) ) {
			foreach ( $global_palette['palette'] as $key => $color ) {

				$label = 'Theme ' . $labels[ $key ];

				$editor_palette[] = array(
					'name'  => $label,
					'slug'  => str_replace( '--', '', self::get_css_variable_prefix() ) . $key,
					'color' => 'var(' . self::get_css_variable_prefix() . $key . ')',
				);
			}
		}
		return $editor_palette;
	}

	/**
	 * Get CSS variable prefix used for styling.
	 *
	 * @since 1.0.0
	 * @return string variable prefix
	 */
	public static function get_css_variable_prefix() {
		return '--aaachinese-global-color-';
	}

	/**
	 * Localize variables used in the customizer.
	 *
	 * @since 1.0.0
	 * @param array $object localize object.
	 * @return array<array-key, mixed> $object localize object.
	 */
	public function localize_variables( $object ) {

		if ( isset( $object['customizer'] ) ) {
			$object['customizer']['globalPaletteStylePrefix']       = self::get_css_variable_prefix();
			$object['customizer']['isElementorActive']              = aaa_chinese_is_elemetor_active();
			$object['customizer']['isGlobalColorElementorDisabled'] = aaa_chinese_maybe_disable_global_color_in_elementor();
			$object['customizer']['globalPaletteSlugs']             = self::get_palette_slugs();
			$object['customizer']['globalPaletteLabels']            = self::get_palette_labels();
		}
		return $object;
	}

	/**
	 * Default global palette options.
	 *
	 * @since 1.0.0
	 * @return array Palette options.
	 */
	public static function get_default_color_palette() {
		$update_colors_for_starter_library = AAA_Chinese_Dynamic_CSS::aaa_chinese_4_4_0_compatibility();
		$update_color_styles_with_presets  = AAA_Chinese_Dynamic_CSS::aaa_chinese_4_5_0_compatibility();
		$update_color_for_forms_ui         = AAA_Chinese_Dynamic_CSS::aaa_chinese_4_6_0_compatibility();
		$color_palette_reorganize          = AAA_Chinese_Dynamic_CSS::aaa_chinese_4_8_9_compatibility();

		return array(
			'currentPalette' => 'palette_1',
			'palettes'       => $update_color_styles_with_presets ? array(
				'palette_1' => array(
					'#046bd2',
					'#045cb4',
					'#1e293b',
					'#334155',
					$color_palette_reorganize ? '#FFFFFF' : '#F0F5FA',
					$color_palette_reorganize ? '#F0F5FA' : '#FFFFFF',
					$color_palette_reorganize ? '#111111' : ( $update_color_for_forms_ui ? '#D1D5DB' : '#ADB6BE' ),
					$color_palette_reorganize ? ( $update_color_for_forms_ui ? '#D1D5DB' : '#ADB6BE' ) : '#111111',
					'#111111',
				),
				'palette_2' => array(
					'#0067FF',
					'#005EE9',
					'#0F172A',
					'#364151',
					$color_palette_reorganize ? '#FFFFFF' : '#E7F6FF',
					$color_palette_reorganize ? '#E7F6FF' : '#FFFFFF',
					$color_palette_reorganize ? '#070614' : '#D1DAE5',
					$color_palette_reorganize ? '#D1DAE5' : '#070614',
					'#222222',
				),
				'palette_3' => array(
					'#6528F7',
					'#5511F8',
					'#0F172A',
					'#454F5E',
					$color_palette_reorganize ? '#FFFFFF' : '#F2F0FE',
					$color_palette_reorganize ? '#F2F0FE' : '#FFFFFF',
					$color_palette_reorganize ? '#0D0614' : '#D8D8F5',
					$color_palette_reorganize ? '#D8D8F5' : '#0D0614',
					'#222222',
				),
				'palette_4' => array(
					'#0085FF',
					'#0177E3',
					'#FFFFFF',
					'#E7F6FF',
					'#212A37',
					'#0F172A',
					'#4F5B62',
					'#070614',
					'#222222',
				),
			) : array(
				'palette_1' => array(
					'#046bd2',
					'#045cb4',
					'#1e293b',
					'#334155',
					$color_palette_reorganize ? '#FFFFFF' : ( $update_colors_for_starter_library ? '#F0F5FA' : '#f9fafb' ),
					$color_palette_reorganize ? ( $update_colors_for_starter_library ? '#F0F5FA' : '#f9fafb' ) : '#FFFFFF',
					$color_palette_reorganize ? ( $update_colors_for_starter_library ? '#111111' : '#cbd5e1' ) : ( $update_colors_for_starter_library ? '#ADB6BE' : '#e2e8f0' ),
					$color_palette_reorganize ? ( $update_colors_for_starter_library ? '#ADB6BE' : '#e2e8f0' ) : ( $update_colors_for_starter_library ? '#111111' : '#cbd5e1' ),
					$update_colors_for_starter_library ? '#111111' : '#94a3b8',
				),
				'palette_2' => array(
					'#0170B9',
					$update_colors_for_starter_library ? '#045cb4' : '#3a3a3a',
					'#3a3a3a',
					'#4B4F58',
					$color_palette_reorganize ? '#FFFFFF' : ( $update_colors_for_starter_library ? '#F0F5FA' : '#F5F5F5' ),
					$color_palette_reorganize ? ( $update_colors_for_starter_library ? '#F0F5FA' : '#F5F5F5' ) : '#FFFFFF',
					$color_palette_reorganize ? ( $update_colors_for_starter_library ? '#111111' : '#424242' ) : ( $update_colors_for_starter_library ? '#ADB6BE' : '#F2F5F7' ),
					$color_palette_reorganize ? ( $update_colors_for_starter_library ? '#ADB6BE' : '#F2F5F7' ) : ( $update_colors_for_starter_library ? '#111111' : '#424242' ),
					$update_colors_for_starter_library ? '#111111' : '#000000',
				),
				'palette_3' => array(
					'#0170B9',
					$update_colors_for_starter_library ? '#045cb4' : '#3a3a3a',
					'#3a3a3a',
					'#4B4F58',
					$color_palette_reorganize ? '#FFFFFF' : ( $update_colors_for_starter_library ? '#F0F5FA' : '#F5F5F5' ),
					$color_palette_reorganize ? ( $update_colors_for_starter_library ? '#F0F5FA' : '#F5F5F5' ) : '#FFFFFF',
					$color_palette_reorganize ? ( $update_colors_for_starter_library ? '#111111' : '#424242' ) : ( $update_colors_for_starter_library ? '#ADB6BE' : '#F2F5F7' ),
					$color_palette_reorganize ? ( $update_colors_for_starter_library ? '#ADB6BE' : '#F2F5F7' ) : ( $update_colors_for_starter_library ? '#111111' : '#424242' ),
					$update_colors_for_starter_library ? '#111111' : '#000000',
				),
				'palette_4' => array(
					'#0085FF',
					'#0177E3',
					'#FFFFFF',
					'#E7F6FF',
					'#212A37',
					'#0F172A',
					'#4F5B62',
					'#070614',
					'#222222',
				),
			),
			'presets'        => aaa_chinese_get_palette_presets(),
		);
	}

	/**
	 * Get the key of the selected global color palette.
	 *
	 * @return string|null Palette key if found, otherwise null.
	 */
	public static function aaa_chinese_get_active_global_palette() {
		$palettes_data   = aaa_chinese_get_palette_colors();
		$current_palette = isset( $palettes_data['currentPalette'] ) ? $palettes_data['currentPalette'] : 'palette_1';

		/**
		 * Filters the active global palette key.
		 *
		 * @param string $current_palette The active global palette key.
		 *
		 * @return string The filtered active global palette key.
		 * @since 1.0.0
		 */
		return apply_filters( 'aaa_chinese_get_active_global_palette_key', $current_palette );
	}

	/**
	 * Determines if the specified or active global palette is considered dark.
	 *
	 * @param string $palette_key The key of the palette to check. Defaults to 'current' to check the active global palette.
	 *
	 * @since 1.0.0
	 * @return bool True if the specified or active global palette is dark, otherwise false.
	 */
	public static function is_dark_palette( $palette_key = 'current' ) {
		if ( $palette_key === 'current' ) {
			if ( ! self::$is_dark_palette ) {
				// Determine if the active global palette is 'palette_4', which is considered dark.
				self::$is_dark_palette = self::aaa_chinese_get_active_global_palette() === 'palette_4';
			}

			// Return the cached result for the active global palette.
			return self::$is_dark_palette;
		}

		// For a specific palette key, directly check if it's 'palette_4'.
		return $palette_key === 'palette_4';
	}

	/**
	 * Get labels for palette colors.
	 *
	 * @since 1.0.0
	 * @return array Palette labels.
	 */
	public static function get_palette_labels() {
		$aaa_chinese_settings           = AAA_Chinese_Theme_Options::get_options();
		$color_palette_reorganize = AAA_Chinese_Dynamic_CSS::aaa_chinese_4_8_9_compatibility();
		$new_color_palette_labels = apply_filters( 'aaa_chinese_new_color_labels', $color_palette_reorganize || isset( $aaa_chinese_settings['new-color-labels'] ) );

		$color_1_label = __( 'Brand', 'aaachinese' );
		$color_2_label = __( 'Alternate Brand', 'aaachinese' );
		$color_3_label = __( 'Headings', 'aaachinese' );
		$color_4_label = __( 'Text', 'aaachinese' );
		$color_5_label = $color_palette_reorganize ? __( 'Primary Background', 'aaachinese' ) : __( 'Secondary Background', 'aaachinese' );
		$color_6_label = $color_palette_reorganize ? __( 'Secondary Background', 'aaachinese' ) : __( 'Primary Background', 'aaachinese' );
		$color_7_label = $color_palette_reorganize ? __( 'Alternate Background', 'aaachinese' ) : __( 'Subtle Background', 'aaachinese' );
		$color_8_label = $color_palette_reorganize ? __( 'Subtle Background', 'aaachinese' ) : __( 'Alternate Background', 'aaachinese' );
		$color_9_label = __( 'Other Supporting', 'aaachinese' );

		return array(
			$new_color_palette_labels ? $color_1_label : __( 'Color 1', 'aaachinese' ),
			$new_color_palette_labels ? $color_2_label : __( 'Color 2', 'aaachinese' ),
			$new_color_palette_labels ? $color_3_label : __( 'Color 3', 'aaachinese' ),
			$new_color_palette_labels ? $color_4_label : __( 'Color 4', 'aaachinese' ),
			$new_color_palette_labels ? $color_5_label : __( 'Color 5', 'aaachinese' ),
			$new_color_palette_labels ? $color_6_label : __( 'Color 6', 'aaachinese' ),
			$new_color_palette_labels ? $color_7_label : __( 'Color 7', 'aaachinese' ),
			$new_color_palette_labels ? $color_8_label : __( 'Color 8', 'aaachinese' ),
			$new_color_palette_labels ? $color_9_label : __( 'Color 9', 'aaachinese' ),
		);
	}

	/**
	 * Get slugs for palette colors.
	 *
	 * @since 1.0.0
	 * @return array Palette slugs.
	 */
	public static function get_palette_slugs() {
		return array(
			'aaachinese-global-color-0',
			'aaachinese-global-color-1',
			'aaachinese-global-color-2',
			'aaachinese-global-color-3',
			'aaachinese-global-color-4',
			'aaachinese-global-color-5',
			'aaachinese-global-color-6',
			'aaachinese-global-color-7',
			'aaachinese-global-color-8',
		);
	}

	/**
	 * Include required files.
	 *
	 * @since 1.0.0
	 */
	public function includes() {
		require_once AAA_THEME_DIR . 'inc/dynamic-css/global-color-palette.php';// PHPCS:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}

	/**
	 * Generate palette CSS required to display on front end.
	 *
	 * @since 1.0.0
	 * @return array palette style array.
	 */
	public static function generate_global_palette_style() {
		$palette_data = aaa_chinese_get_option( 'global-color-palette' );

		$palette_style = array();

		if ( isset( $palette_data['palette'] ) ) {
			foreach ( $palette_data['palette'] as $key => $color ) {
				$palette_key                   = self::get_css_variable_prefix() . $key;
				$palette_style[ $palette_key ] = $color;
			}
		}

		return $palette_style;
	}

	/**
	 * Pass hex value for global palette to process foreground color.
	 *
	 * @since 1.0.0
	 * @param string $color hex color / css variable.
	 * @return string
	 */
	public function get_color_by_palette_variable( $color ) {
		// Check if color is CSS variable.
		if ( 0 === strpos( $color, 'var(--' ) ) {

			$global_palette = aaa_chinese_get_option( 'global-color-palette' );

			foreach ( $global_palette['palette'] as $palette_index => $value ) {

				if ( 'var(' . self::get_css_variable_prefix() . $palette_index . ')' === $color ) {
					return $value;
				}
			}
		}

		return $color;
	}

	/**
	 * Add dynamic CSS for the global border color.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $dynamic_css          AAA Chinese Dynamic CSS.
	 *
	 * @return String Generated dynamic CSS for global border.
	 */
	public function global_border_compatibility( $dynamic_css ) {
		$global_border_color = aaa_chinese_get_option( 'border-color', '#dddddd' );

		$global_border = '
			:root {
				--aaachinese-border-color : ' . $global_border_color . ';
			}
		';

		return $dynamic_css . AAA_Chinese_Enqueue_Scripts::trim_css( $global_border );
	}
}

new AAA_Chinese_Global_Palette();
