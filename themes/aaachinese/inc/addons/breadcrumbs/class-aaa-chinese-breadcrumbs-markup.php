<?php
/**
 * Breadcrumbs for AAA Chinese theme.
 *
 * @package     AAA Chinese
 * @link        https://www.brainstormforce.com
 * @since       AAA Chinese 1.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AAA_Chinese_Breadcrumbs_Markup' ) ) {

	/**
	 * Breadcrumbs Markup Initial Setup
	 *
	 * @since 1.8.0
	 */
	class AAA_Chinese_Breadcrumbs_Markup {
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
		 *  Constructor
		 */
		public function __construct() {

			add_action( 'wp', array( $this, 'aaa_chinese_breadcumb_template' ) );
		}

		/**
		 * AAA Chinese Breadcrumbs Template
		 *
		 * Loads template based on the style option selected in options panel for Breadcrumbs.
		 *
		 * @since 1.8.0
		 *
		 * @return void
		 */
		public function aaa_chinese_breadcumb_template() {

			$breadcrumb_position = aaa_chinese_get_option( 'breadcrumb-position' );

			$breadcrumb_enabled = false;

			if ( is_singular() ) {
				$breadcrumb_enabled = get_post_meta( get_the_ID(), 'ast-breadcrumbs-content', true );
			}

			if ( 'disabled' !== $breadcrumb_enabled && $breadcrumb_position && 'none' !== $breadcrumb_position && ! ( ( is_home() || is_front_page() ) && ( 'aaa_chinese_entry_top' === $breadcrumb_position ) ) ) {
				if ( self::aaa_chinese_breadcrumb_rules() ) {
					if ( ( is_archive() || is_search() ) && 'aaa_chinese_entry_top' === $breadcrumb_position ) {
						add_action( 'aaa_chinese_before_archive_title', array( $this, 'aaa_chinese_hook_breadcrumb_position' ), 15 );
					} else {
						add_action( $breadcrumb_position, array( $this, 'aaa_chinese_hook_breadcrumb_position' ), 15 );
					}
				}
			}
		}

		/**
		 * AAA Chinese Hook Breadcrumb Position
		 *
		 * Hook breadcrumb to position of selected option
		 *
		 * @since 1.8.0
		 *
		 * @return void
		 */
		public function aaa_chinese_hook_breadcrumb_position() {
			$breadcrumb_position = aaa_chinese_get_option( 'breadcrumb-position' );

			if ( $breadcrumb_position && ( 'aaa_chinese_header_markup_after' === $breadcrumb_position || 'aaa_chinese_header_after' === $breadcrumb_position ) ) {
				echo '<div class="main-header-bar ast-header-breadcrumb">
							<div class="ast-container">';
			}
			aaa_chinese_get_breadcrumb();
			if ( $breadcrumb_position && ( 'aaa_chinese_header_markup_after' === $breadcrumb_position || 'aaa_chinese_header_after' === $breadcrumb_position ) ) {
				echo '	</div>
					</div>';
			}
		}

		/**
		 * AAA Chinese Breadcrumbs Rules
		 *
		 * Checks the rules defined for displaying Breadcrumb on different pages.
		 *
		 * @since 1.8.0
		 *
		 * @return bool
		 */
		public static function aaa_chinese_breadcrumb_rules() {

			// Display Breadcrumb default true.
			$display_breadcrumb = true;

			if ( is_front_page() && '0' == aaa_chinese_get_option( 'breadcrumb-disable-home-page' ) ) {
				$display_breadcrumb = false;
			}

			if ( is_home() && '0' == aaa_chinese_get_option( 'breadcrumb-disable-blog-posts-page' ) ) {
				$display_breadcrumb = false;
			}

			if ( is_search() && '0' == aaa_chinese_get_option( 'breadcrumb-disable-search' ) ) {
				$display_breadcrumb = false;
			}

			if ( is_archive() && '0' == aaa_chinese_get_option( 'breadcrumb-disable-archive' ) ) {
				$display_breadcrumb = false;
			}

			if ( is_page() && '0' == aaa_chinese_get_option( 'breadcrumb-disable-single-page' ) ) {
				$display_breadcrumb = false;
			}

			if ( is_single() && '0' == aaa_chinese_get_option( 'breadcrumb-disable-single-post' ) ) {
				$display_breadcrumb = false;
			}

			if ( is_singular() && '0' == aaa_chinese_get_option( 'breadcrumb-disable-singular' ) ) {
				$display_breadcrumb = false;
			}

			if ( is_404() && '0' == aaa_chinese_get_option( 'breadcrumb-disable-404-page' ) ) {
				$display_breadcrumb = false;
			}

			return apply_filters( 'aaa_chinese_breadcrumb_enabled', $display_breadcrumb );
		}
	}
}

/**
 *  Kicking this off by calling 'get_instance()' method
 */
AAA_Chinese_Breadcrumbs_Markup::get_instance();
