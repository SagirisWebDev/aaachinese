<?php
/**
 * Breadcrumbs for AAA Chinese theme.
 *
 * @package     AAA Chinese
 * @link        https://www.brainstormforce.com
 * @since       AAA Chinese 1.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'AAA_THEME_BREADCRUMBS_DIR', AAA_THEME_DIR . 'inc/addons/breadcrumbs/' );
define( 'AAA_THEME_BREADCRUMBS_URI', AAA_THEME_URI . 'inc/addons/breadcrumbs/' );

if ( ! class_exists( 'AAA_Chinese_Breadcrumbs' ) ) {

	/**
	 * Breadcrumbs Initial Setup
	 *
	 * @since 1.7.0
	 */
	class AAA_Chinese_Breadcrumbs {
		/**
		 * Member Variable
		 *
		 * @var object instance
		 */
		private static $instance;

		/**
		 *  Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor function that initializes required actions and hooks
		 */
		public function __construct() {

			// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once AAA_THEME_BREADCRUMBS_DIR . 'class-aaa-chinese-breadcrumbs-loader.php';
			require_once AAA_THEME_BREADCRUMBS_DIR . 'class-aaa-chinese-breadcrumbs-markup.php';
			require_once AAA_THEME_BREADCRUMBS_DIR . 'class-aaa-chinese-breadcrumb-trail.php';
			// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound

			// Third Party plugins in the breadcrumb options.
			add_filter( 'aaa_chinese_breadcrumb_source_list', array( $this, 'aaa_chinese_breadcrumb_source_list_items' ) );

			// Include front end files.
			if ( ! is_admin() ) {
				require_once AAA_THEME_BREADCRUMBS_DIR . 'dynamic-css/dynamic.css.php';// phpcs:ignore: WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			}
		}

		/**
		 * Third Party Breadcrumb option
		 *
		 * @param Array $options breadcrumb options array.
		 *
		 * @return Array breadcrumb options array.
		 * @since 1.0.0
		 */
		public function aaa_chinese_breadcrumb_source_list_items( $options ) {

			$breadcrumb_enable = is_callable( 'WPSEO_Options::get' ) ? WPSEO_Options::get( 'breadcrumbs-enable' ) : false;
			$wpseo_option      = get_option( 'wpseo_internallinks' ) ? get_option( 'wpseo_internallinks' ) : $breadcrumb_enable;
			if ( ! is_array( $wpseo_option ) ) {
				unset( $wpseo_option );
				$wpseo_option = array(
					'breadcrumbs-enable' => $breadcrumb_enable,
				);
			}

			if ( function_exists( 'yoast_breadcrumb' ) && true === $wpseo_option['breadcrumbs-enable'] ) {
				$options['yoast-seo-breadcrumbs'] = 'Yoast SEO Breadcrumbs';
			}

			if ( function_exists( 'bcn_display' ) ) {
				$options['breadcrumb-navxt'] = 'Breadcrumb NavXT';
			}

			if ( function_exists( 'rank_math_the_breadcrumbs' ) ) {
				$options['rank-math'] = 'Rank Math';
			}

			if ( function_exists( 'seopress_display_breadcrumbs' ) ) {
				$options['seopress'] = 'SEOPress';
			}

			return $options;
		}
	}

	/**
	 *  Kicking this off by calling 'get_instance()' method
	 */
	AAA_Chinese_Breadcrumbs::get_instance();

}
