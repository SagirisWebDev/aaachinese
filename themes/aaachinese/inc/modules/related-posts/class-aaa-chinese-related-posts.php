<?php
/**
 * Related Posts for AAA Chinese theme.
 *
 * @package     AAA Chinese
 * @link        https://www.brainstormforce.com
 * @since       AAA Chinese 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'AAA_RELATED_POSTS_DIR', AAA_THEME_DIR . 'inc/modules/related-posts/' );

/**
 * Related Posts Initial Setup
 *
 * @since 3.5.0
 */
class AAA_Chinese_Related_Posts {
	/**
	 * Constructor function that initializes required actions and hooks
	 *
	 * @since 3.5.0
	 */
	public function __construct() {

		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		require_once AAA_RELATED_POSTS_DIR . 'class-aaa-chinese-related-posts-loader.php';
		require_once AAA_RELATED_POSTS_DIR . 'class-aaa-chinese-related-posts-markup.php';
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound

		// Include front end files.
		if ( ! is_admin() ) {
			require_once AAA_RELATED_POSTS_DIR . 'css/static-css.php'; // phpcs:ignore: WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once AAA_RELATED_POSTS_DIR . 'css/dynamic-css.php'; // phpcs:ignore: WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		}
	}
}

/**
 *  Kicking this off by creating NEW instance.
 */
new AAA_Chinese_Related_Posts();
