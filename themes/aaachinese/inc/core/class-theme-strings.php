<?php
/**
 * AAA Chinese Theme Strings
 *
 * @package     AAA Chinese
 *
 * @since       AAA Chinese 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Default Strings
 */
if ( ! function_exists( 'aaa_chinese_default_strings' ) ) {

	/**
	 * Default Strings
	 *
	 * @since 1.0.0
	 * @param  string $key  String key.
	 * @param  bool   $echo Print string.
	 * @return mixed        Return string or nothing.
	 */
	function aaa_chinese_default_strings( $key, $echo = true ) {

		$post_comment_dynamic_string = true === AAA_Chinese_Dynamic_CSS::aaa_chinese_core_form_btns_styling() ? __( 'Post Comment', 'aaachinese' ) : __( 'Post Comment &raquo;', 'aaachinese' );
		$defaults                    = apply_filters(
			'aaa_chinese_default_strings',
			array(

				// Header.
				'string-header-skip-link'                => __( 'Skip to content', 'aaachinese' ),

				// 404 Page Strings.
				'string-404-sub-title'                   => __( 'It looks like the link pointing here was faulty. Maybe try searching?', 'aaachinese' ),

				// Search Page Strings.
				'string-search-nothing-found'            => __( 'Nothing Found', 'aaachinese' ),
				'string-search-nothing-found-message'    => __( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'aaachinese' ),
				'string-full-width-search-message'       => __( 'Start typing and press enter to search', 'aaachinese' ),
				'string-full-width-search-placeholder'   => __( 'Search...', 'aaachinese' ),
				'string-header-cover-search-placeholder' => __( 'Search...', 'aaachinese' ),
				'string-search-input-placeholder'        => __( 'Search...', 'aaachinese' ),

				// Comment Template Strings.
				'string-comment-reply-link'              => __( 'Reply', 'aaachinese' ),
				'string-comment-edit-link'               => __( 'Edit', 'aaachinese' ),
				'string-comment-awaiting-moderation'     => __( 'Your comment is awaiting moderation.', 'aaachinese' ),
				'string-comment-title-reply'             => __( 'Leave a Comment', 'aaachinese' ),
				'string-comment-cancel-reply-link'       => __( 'Cancel Reply', 'aaachinese' ),
				'string-comment-label-submit'            => $post_comment_dynamic_string,
				'string-comment-label-message'           => __( 'Type here..', 'aaachinese' ),
				'string-comment-label-name'              => __( 'Name', 'aaachinese' ),
				'string-comment-label-email'             => __( 'Email', 'aaachinese' ),
				'string-comment-label-website'           => __( 'Website', 'aaachinese' ),
				'string-comment-closed'                  => __( 'Comments are closed.', 'aaachinese' ),
				'string-comment-navigation-title'        => __( 'Comment navigation', 'aaachinese' ),
				'string-comment-navigation-next'         => __( 'Newer Comments', 'aaachinese' ),
				'string-comment-navigation-previous'     => __( 'Older Comments', 'aaachinese' ),

				// Blog Default Strings.
				'string-blog-page-links-before'          => __( 'Pages:', 'aaachinese' ),
				'string-blog-meta-author-by'             => __( 'By ', 'aaachinese' ),
				'string-blog-meta-leave-a-comment'       => __( 'Leave a Comment', 'aaachinese' ),
				'string-blog-meta-one-comment'           => __( '1 Comment', 'aaachinese' ),
				'string-blog-meta-multiple-comment'      => __( '% Comments', 'aaachinese' ),
				'string-blog-navigation-next'            => __( 'Next', 'aaachinese' ) . ' <span class="aaachinese-right-arrow" aria-hidden="true">&rarr;</span>',
				'string-blog-navigation-previous'        => '<span class="aaachinese-left-arrow" aria-hidden="true">&larr;</span> ' . __( 'Previous', 'aaachinese' ),
				'string-next-text'                       => __( 'Next', 'aaachinese' ),
				'string-previous-text'                   => __( 'Previous', 'aaachinese' ),

				// Single Post Default Strings.
				'string-single-page-links-before'        => __( 'Pages:', 'aaachinese' ),
				/* translators: 1: Post type label */
				'string-single-navigation-next'          => __( 'Next %s', 'aaachinese' ) . ' <span class="aaachinese-right-arrow" aria-hidden="true">&rarr;</span>',
				/* translators: 1: Post type label */
				'string-single-navigation-previous'      => '<span class="aaachinese-left-arrow" aria-hidden="true">&larr;</span> ' . __( 'Previous %s', 'aaachinese' ),

				// Content None.
				'string-content-nothing-found-message'   => __( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'aaachinese' ),

			)
		);

		if ( is_rtl() ) {
			$defaults['string-blog-navigation-next']     = __( 'Next', 'aaachinese' ) . ' <span class="aaachinese-left-arrow" aria-hidden="true">&larr;</span>';
			$defaults['string-blog-navigation-previous'] = '<span class="aaachinese-right-arrow" aria-hidden="true">&rarr;</span> ' . __( 'Previous', 'aaachinese' );

			/* translators: 1: Post type label */
			$defaults['string-single-navigation-next'] = __( 'Next %s', 'aaachinese' ) . ' <span class="aaachinese-left-arrow" aria-hidden="true">&larr;</span>';
			/* translators: 1: Post type label */
			$defaults['string-single-navigation-previous'] = '<span class="aaachinese-right-arrow" aria-hidden="true">&rarr;</span> ' . __( 'Previous %s', 'aaachinese' );
		}

		$output = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';

		/**
		 * Print or return
		 */
		if ( $echo ) {
			echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			return $output;
		}
	}
}
