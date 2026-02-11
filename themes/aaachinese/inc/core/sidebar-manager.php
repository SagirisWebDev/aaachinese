<?php
/**
 * Sidebar Manager functions
 *
 * @package     AAA Chinese
 *
 * @since       AAA Chinese 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Site Sidebar
 */
if ( ! function_exists( 'aaa_chinese_page_layout' ) ) {

	/**
	 * Site Sidebar
	 *
	 * Default 'right sidebar' for overall site.
	 */
	function aaa_chinese_page_layout() {

		$supported_post_types = AAA_Chinese_Posts_Structure_Loader::get_supported_post_types();

		if ( is_singular() ) {

			// If post meta value is empty,
			// Then get the POST_TYPE sidebar.
			$layout = aaa_chinese_get_option_meta( 'site-sidebar-layout', '', true );

			// If post meta value is empty or in editor and sidebar set as default.
			if ( empty( $layout ) ) {

				$post_type = strval( get_post_type() );

				if ( in_array( $post_type, $supported_post_types ) ) {

					$layout = aaa_chinese_get_option( 'single-' . $post_type . '-sidebar-layout' );
				}

				if ( 'default' === $layout || empty( $layout ) ) {

					// Get the global sidebar value.
					// NOTE: Here not used `true` in the below function call.
					$layout = aaa_chinese_get_option( 'site-sidebar-layout' );
				}
			}
		} else {

			if ( is_search() ) {

				// Check only post type archive option value.
				$layout = aaa_chinese_get_option( 'archive-post-sidebar-layout' );

				$search_sidebar_layout = aaa_chinese_get_option( 'aaachinese-search-sidebar-layout', 'default' );
				$layout                = 'default' !== $search_sidebar_layout ? $search_sidebar_layout : $layout;

				if ( 'default' === $layout || empty( $layout ) ) {

					// Get the global sidebar value.
					// NOTE: Here not used `true` in the below function call.
					$layout = aaa_chinese_get_option( 'site-sidebar-layout' );
				}
			} else {

				$post_type = aaa_chinese_get_post_type();
				$layout    = '';

				if ( in_array( $post_type, $supported_post_types ) ) {
					$layout = aaa_chinese_get_option( 'archive-' . $post_type . '-sidebar-layout' );
				}

				if ( 'default' === $layout || empty( $layout ) ) {

					// Get the global sidebar value.
					// NOTE: Here not used `true` in the below function call.
					$layout = aaa_chinese_get_option( 'site-sidebar-layout' );
				}
			}
		}

		return apply_filters( 'aaa_chinese_page_layout', $layout );
	}
}
