<?php
/**
 * Astra Admin Loader
 *
 * @package Astra
 * @since 4.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Astra_Admin_Loader' ) ) {
	/**
	 * Astra_Admin_Loader
	 *
	 * @since 4.0.0
	 */
	class Astra_Admin_Loader {
		/**
		 * Instance
		 *
		 * @var null $instance
		 * @since 4.0.0
		 */
		private static $instance;

		/**
		 * Initiator
		 *
		 * @since 4.0.0
		 * @return object initialized object of class.
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				/** @psalm-suppress InvalidPropertyAssignmentValue */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
				self::$instance = new self();
				/** @psalm-suppress InvalidPropertyAssignmentValue */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @since 4.0.0
		 */
		public function __construct() {
			define( 'AAA_THEME_ADMIN_DIR', AAA_THEME_DIR . 'admin/' );
			define( 'AAA_THEME_ADMIN_URL', AAA_THEME_URI . 'admin/' );

			$this->includes();
		}

		/**
		 * Include required classes.
		 *
		 * @since 4.0.0
		 */
		public function includes() {
			/* Ajax init */
			require_once AAA_THEME_ADMIN_DIR . 'includes/class-aaa-chinese-admin-ajax.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound -- Not a template file so loading in a normal way.

			/* Setup Menu */
			require_once AAA_THEME_ADMIN_DIR . 'includes/class-aaa-chinese-menu.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound -- Not a template file so loading in a normal way.

			require_once AAA_THEME_ADMIN_DIR . 'includes/class-aaa-chinese-theme-builder-free.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound -- Not a template file so loading in a normal way.

			/* BSF Analytics */
			require_once AAA_THEME_ADMIN_DIR . 'class-aaa-chinese-bsf-analytics.php';
		}
	}
}

Astra_Admin_Loader::get_instance();
