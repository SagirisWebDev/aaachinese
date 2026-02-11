<?php
/**
 * Theme Hook Alliance hook stub list.
 *
 * @see  https://github.com/zamoose/themehookalliance
 *
 * @package     AAA Chinese
 *
 * @since       AAA Chinese 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Themes and Plugins can check for aaa_chinese_hooks using current_theme_supports( 'aaa_chinese_hooks', $hook )
 * to determine whether a theme declares itself to support this specific hook type.
 *
 * Example:
 * <code>
 *      // Declare support for all hook types
 *      add_theme_support( 'aaa_chinese_hooks', array( 'all' ) );
 *
 *      // Declare support for certain hook types only
 *      add_theme_support( 'aaa_chinese_hooks', array( 'header', 'content', 'footer' ) );
 * </code>
 */
add_theme_support(
	'aaa_chinese_hooks',
	array(

		/**
		 * As a Theme developer, use the 'all' parameter, to declare support for all
		 * hook types.
		 * Please make sure you then actually reference all the hooks in this file,
		 * Plugin developers depend on it!
		 */
		'all',

		/**
		 * Themes can also choose to only support certain hook types.
		 * Please make sure you then actually reference all the hooks in this type
		 * family.
		 *
		 * When the 'all' parameter was set, specific hook types do not need to be
		 * added explicitly.
		 */
		'html',
		'body',
		'head',
		'header',
		'content',
		'entry',
		'comments',
		'sidebars',
		'sidebar',
		'footer',

	/**
	 * If/when WordPress Core implements similar methodology, Themes and Plugins
	 * will be able to check whether the version of THA supplied by the theme
	 * supports Core hooks.
	 */
	)
);

/**
 * Determines, whether the specific hook type is actually supported.
 *
 * Plugin developers should always check for the support of a <strong>specific</strong>
 * hook type before hooking a callback function to a hook of this type.
 *
 * Example:
 * <code>
 *      if ( current_theme_supports( 'aaa_chinese_hooks', 'header' ) )
 *          add_action( 'aaa_chinese_head_top', 'prefix_header_top' );
 * </code>
 *
 * @param bool  $bool true.
 * @param array $args The hook type being checked.
 * @param array $registered All registered hook types.
 *
 * @return bool
 */
function aaa_chinese_current_theme_supports( $bool, $args, $registered ) {
	return in_array( $args[0], $registered[0] ) || in_array( 'all', $registered[0] );
}
add_filter( 'current_theme_supports-aaa_chinese_hooks', 'aaa_chinese_current_theme_supports', 10, 3 );

/**
 * HTML <html> hook
 * Special case, useful for <DOCTYPE>, etc.
 * $aaa_chinese_supports[] = 'html;
 */
function aaa_chinese_html_before() {
	do_action( 'aaa_chinese_html_before' );
}
/**
 * HTML <body> hooks
 * $aaa_chinese_supports[] = 'body';
 */
function aaa_chinese_body_top() {
	do_action( 'aaa_chinese_body_top' );
}

/**
 * Body Bottom
 */
function aaa_chinese_body_bottom() {
	do_action( 'aaa_chinese_body_bottom' );
}

/**
 * HTML <head> hooks
 *
 * $aaa_chinese_supports[] = 'head';
 */
function aaa_chinese_head_top() {
	do_action( 'aaa_chinese_head_top' );
}

/**
 * Head Bottom
 */
function aaa_chinese_head_bottom() {
	do_action( 'aaa_chinese_head_bottom' );
}

/**
 * Semantic <header> hooks
 *
 * $aaa_chinese_supports[] = 'header';
 */
function aaa_chinese_header_before() {
	do_action( 'aaa_chinese_header_before' );
}

/**
 * Site Header
 */
function aaa_chinese_header() {
	do_action( 'aaa_chinese_header' );
}

/**
 * Masthead Top
 */
function aaa_chinese_masthead_top() {
	do_action( 'aaa_chinese_masthead_top' );
}

/**
 * Masthead
 */
function aaa_chinese_masthead() {
	do_action( 'aaa_chinese_masthead' );
}

/**
 * Masthead Bottom
 */
function aaa_chinese_masthead_bottom() {
	do_action( 'aaa_chinese_masthead_bottom' );
}

/**
 * Header After
 */
function aaa_chinese_header_after() {
	do_action( 'aaa_chinese_header_after' );
}

/**
 * Main Header bar top
 */
function aaa_chinese_main_header_bar_top() {
	do_action( 'aaa_chinese_main_header_bar_top' );
}

/**
 * Main Header bar bottom
 */
function aaa_chinese_main_header_bar_bottom() {
	do_action( 'aaa_chinese_main_header_bar_bottom' );
}

/**
 * Main Header Content
 */
function aaa_chinese_masthead_content() {
	do_action( 'aaa_chinese_masthead_content' );
}
/**
 * Main toggle button before
 */
function aaa_chinese_masthead_toggle_buttons_before() {
	do_action( 'aaa_chinese_masthead_toggle_buttons_before' );
}

/**
 * Main toggle buttons
 */
function aaa_chinese_masthead_toggle_buttons() {
	do_action( 'aaa_chinese_masthead_toggle_buttons' );
}

/**
 * Main toggle button after
 */
function aaa_chinese_masthead_toggle_buttons_after() {
	do_action( 'aaa_chinese_masthead_toggle_buttons_after' );
}

/**
 * Semantic <content> hooks
 *
 * $aaa_chinese_supports[] = 'content';
 */
function aaa_chinese_content_before() {
	do_action( 'aaa_chinese_content_before' );
}

/**
 * Content after
 */
function aaa_chinese_content_after() {
	do_action( 'aaa_chinese_content_after' );
}

/**
 * Content top
 */
function aaa_chinese_content_top() {
	do_action( 'aaa_chinese_content_top' );
}

/**
 * Content bottom
 */
function aaa_chinese_content_bottom() {
	do_action( 'aaa_chinese_content_bottom' );
}

/**
 * Content while before
 */
function aaa_chinese_content_while_before() {
	do_action( 'aaa_chinese_content_while_before' );
}

/**
 * Content loop
 */
function aaa_chinese_content_loop() {
	do_action( 'aaa_chinese_content_loop' );
}

/**
 * Conten Page Loop.
 *
 * Called from page.php
 */
function aaa_chinese_content_page_loop() {
	do_action( 'aaa_chinese_content_page_loop' );
}

/**
 * Content while after
 */
function aaa_chinese_content_while_after() {
	do_action( 'aaa_chinese_content_while_after' );
}

/**
 * Semantic <entry> hooks
 *
 * $aaa_chinese_supports[] = 'entry';
 */
function aaa_chinese_entry_before() {
	do_action( 'aaa_chinese_entry_before' );
}

/**
 * Entry after
 */
function aaa_chinese_entry_after() {
	do_action( 'aaa_chinese_entry_after' );
}

/**
 * Entry content before
 */
function aaa_chinese_entry_content_before() {
	do_action( 'aaa_chinese_entry_content_before' );
}

/**
 * Entry content after
 */
function aaa_chinese_entry_content_after() {
	do_action( 'aaa_chinese_entry_content_after' );
}

/**
 * Entry Top
 */
function aaa_chinese_entry_top() {
	do_action( 'aaa_chinese_entry_top' );
}

/**
 * Entry bottom
 */
function aaa_chinese_entry_bottom() {
	do_action( 'aaa_chinese_entry_bottom' );
}

/**
 * Single Post Header Before
 */
function aaa_chinese_single_header_before() {
	do_action( 'aaa_chinese_single_header_before' );
}

/**
 * Single Post Header After
 */
function aaa_chinese_single_header_after() {
	do_action( 'aaa_chinese_single_header_after' );
}

/**
 * Single Post Header Top
 */
function aaa_chinese_single_header_top() {
	do_action( 'aaa_chinese_single_header_top' );
}

/**
 * Single Post Header Bottom
 */
function aaa_chinese_single_header_bottom() {
	do_action( 'aaa_chinese_single_header_bottom' );
}

/**
 * Comments block hooks
 *
 * $aaa_chinese_supports[] = 'comments';
 */
function aaa_chinese_comments_before() {
	do_action( 'aaa_chinese_comments_before' );
}

/**
 * Comments after.
 */
function aaa_chinese_comments_after() {
	do_action( 'aaa_chinese_comments_after' );
}

/**
 * Semantic <sidebar> hooks
 *
 * $aaa_chinese_supports[] = 'sidebar';
 */
function aaa_chinese_sidebars_before() {
	do_action( 'aaa_chinese_sidebars_before' );
}

/**
 * Sidebars after
 */
function aaa_chinese_sidebars_after() {
	do_action( 'aaa_chinese_sidebars_after' );
}

/**
 * Semantic <footer> hooks
 *
 * $aaa_chinese_supports[] = 'footer';
 */
function aaa_chinese_footer() {
	do_action( 'aaa_chinese_footer' );
}

/**
 * Footer before
 */
function aaa_chinese_footer_before() {
	do_action( 'aaa_chinese_footer_before' );
}

/**
 * Footer after
 */
function aaa_chinese_footer_after() {
	do_action( 'aaa_chinese_footer_after' );
}

/**
 * Footer top
 */
function aaa_chinese_footer_content_top() {
	do_action( 'aaa_chinese_footer_content_top' );
}

/**
 * Footer
 */
function aaa_chinese_footer_content() {
	do_action( 'aaa_chinese_footer_content' );
}

/**
 * Footer bottom
 */
function aaa_chinese_footer_content_bottom() {
	do_action( 'aaa_chinese_footer_content_bottom' );
}

/**
 * Archive header
 */
function aaa_chinese_archive_header() {
	do_action( 'aaa_chinese_archive_header' );
}

/**
 * Pagination
 */
function aaa_chinese_pagination() {
	do_action( 'aaa_chinese_pagination' );
}

/**
 * Entry content single
 */
function aaa_chinese_entry_content_single() {
	do_action( 'aaa_chinese_entry_content_single' );
}

/**
 * Entry content single-page.
 *
 * @since 4.0.0
 */
function aaa_chinese_entry_content_single_page() {
	do_action( 'aaa_chinese_entry_content_single_page' );
}

/**
 * 404
 */
function aaa_chinese_entry_content_404_page() {
	do_action( 'aaa_chinese_entry_content_404_page' );
}

/**
 * Entry content blog
 */
function aaa_chinese_entry_content_blog() {
	do_action( 'aaa_chinese_entry_content_blog' );
}

/**
 * Blog featured post section
 */
function aaa_chinese_blog_post_featured_format() {
	do_action( 'aaa_chinese_blog_post_featured_format' );
}

/**
 * Primary Content Top
 */
function aaa_chinese_primary_content_top() {
	do_action( 'aaa_chinese_primary_content_top' );
}

/**
 * Primary Content Bottom
 */
function aaa_chinese_primary_content_bottom() {
	do_action( 'aaa_chinese_primary_content_bottom' );
}

/**
 * 404 Page content template action.
 */
function aaa_chinese_404_content_template() {
	do_action( 'aaa_chinese_404_content_template' );
}

if ( ! function_exists( 'wp_body_open' ) ) {

	/**
	 * Fire the wp_body_open action.
	 * Adds backward compatibility for WordPress versions < 5.2
	 *
	 * @since 1.8.7
	 */
	function wp_body_open() { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound
		do_action( 'wp_body_open' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	}
}
