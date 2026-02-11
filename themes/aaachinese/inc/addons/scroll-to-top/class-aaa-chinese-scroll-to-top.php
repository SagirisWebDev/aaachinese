<?php
/**
 * Scroll To Top Addon
 *
 * @since 4.0.0
 * @package AAA Chinese
 */

define( 'AAA_SCROLL_TO_TOP_DIR', AAA_THEME_DIR . 'inc/addons/scroll-to-top/' );
define( 'AAA_SCROLL_TO_TOP_URL', AAA_THEME_URI . 'inc/addons/scroll-to-top/' );

/**
 * Scroll To Top Initial Setup
 *
 * @since 4.0.0
 */
class AAA_Chinese_Scroll_To_Top {
	/**
	 * Member Variable
	 *
	 * @var null $instance
	 */
	private static $instance;

	/**
	 *  Initiator
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
	 * Constructor function that initializes required actions and hooks
	 */
	public function __construct() {

		require_once AAA_SCROLL_TO_TOP_DIR . 'classes/class-aaa-chinese-scroll-to-top-loader.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound -- Not a template file so loading in a normal way.

		// Include front end files.
		if ( ! is_admin() ) {
			require_once AAA_SCROLL_TO_TOP_DIR . 'css/static-css.php';  // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound -- Not a template file so loading in a normal way.
			require_once AAA_SCROLL_TO_TOP_DIR . 'css/dynamic-css.php';  // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound -- Not a template file so loading in a normal way.
		}
	}
}

/**
 *  Kicking this off by calling 'get_instance()' method.
 */
AAA_Chinese_Scroll_To_Top::get_instance();
