<?php
/**
 * Scroll to Top - Static CSS
 *
 * @package AAA Chinese
 *
 * @since 4.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_filter( 'aaa_chinese_dynamic_theme_css', 'aaa_chinese_scroll_to_top_static_css', 11 );

/**
 * Scroll to Top - Static CSS
 *
 * @param  string $dynamic_css          AAA Chinese Dynamic CSS.
 * @return String Generated dynamic CSS for Scroll to Top.
 *
 * @since 4.0.0
 */
function aaa_chinese_scroll_to_top_static_css( $dynamic_css ) {

	if ( true !== aaa_chinese_get_option( 'scroll-to-top-enable', true ) ) {
		return $dynamic_css;
	}

	$is_site_rtl = is_rtl() ? true : false;
	$ltr_left    = $is_site_rtl ? 'right' : 'left';
	$ltr_right   = $is_site_rtl ? 'left' : 'right';

	$dynamic_css .= '
		#ast-scroll-top {
			display: none;
			position: fixed;
			text-align: center;
			cursor: pointer;
			z-index: 99;
			width: 2.1em;
			height: 2.1em;
			line-height: 2.1;
			color: #ffffff;
			border-radius: 2px;
			content: "";
			outline: inherit;
		}
		@media (min-width: 769px) {
			#ast-scroll-top {
				content: "769";
			}
		}
		#ast-scroll-top .ast-icon.icon-arrow svg {
			margin-' . esc_attr( $ltr_left ) . ': 0px;
			vertical-align: middle;
			transform: translate(0, -20%) rotate(180deg);
			width: 1.6em;
		}
		.ast-scroll-to-top-right {
			' . esc_attr( $ltr_right ) . ': 30px;
			bottom: 30px;
		}
		.ast-scroll-to-top-left {
			' . esc_attr( $ltr_left ) . ': 30px;
			bottom: 30px;
		}
	';

	return $dynamic_css;
}
