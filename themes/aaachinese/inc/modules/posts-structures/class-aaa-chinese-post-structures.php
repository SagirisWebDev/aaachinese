<?php
/**
 * Post Strctures Extension
 *
 * @package AAA Chinese
 * @since 4.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'AAA_THEME_POST_STRUCTURE_DIR', AAA_THEME_DIR . 'inc/modules/posts-structures/' );
define( 'AAA_THEME_POST_STRUCTURE_URI', AAA_THEME_URI . 'inc/modules/posts-structures/' );

/**
 * Post Strctures Initial Setup
 *
 * @since 4.0.0
 */
class AAA_Chinese_Post_Structures {
	/**
	 * Constructor function that loads require files.
	 */
	public function __construct() {

		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		require_once AAA_THEME_POST_STRUCTURE_DIR . 'class-aaa-chinese-posts-structure-loader.php';
		require_once AAA_THEME_POST_STRUCTURE_DIR . 'class-aaa-chinese-posts-structure-markup.php';

		// Include front end files.
		if ( ! is_admin() ) {
			require_once AAA_THEME_POST_STRUCTURE_DIR . 'css/single-dynamic.css.php';
			require_once AAA_THEME_POST_STRUCTURE_DIR . 'css/archive-dynamic.css.php';
			require_once AAA_THEME_POST_STRUCTURE_DIR . 'css/special-dynamic.css.php';
		}
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}
}

/**
 *  Kicking this off by creating new object.
 */
new AAA_Chinese_Post_Structures();
