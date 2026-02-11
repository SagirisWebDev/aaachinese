<?php
/**
 * Live Search - Dynamic CSS
 *
 * @package astra
 * @since 4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_filter( 'aaa_chinese_dynamic_theme_css', 'aaa_chinese_live_search_css', 12 );

/**
 * Live Search - Dynamic CSS.
 *
 * @param string $dynamic_css
 * @since 4.4.0
 */
function aaa_chinese_live_search_css( $dynamic_css ) {
	$ltr_left = is_rtl() ? 'right' : 'left';

	$heading_base_color = aaa_chinese_get_option( 'heading-base-color' );

	$static_css = '
		form.search-form {
			position: relative;
		}
		.ast-live-search-results {
			position: absolute;
			width: 100%;
			top: 60px;
			padding: 0px 4px 4px;
			max-height: 400px;
			height: auto;
			overflow-x: hidden;
			overflow-y: auto;
			background: var(--ast-global-color-primary, --ast-global-color-4);
			z-index: 999999;
			border-radius: 4px;
			border: 1px solid var(--ast-border-color);
			box-shadow: 0px 4px 6px -2px rgba(16, 24, 40, 0.03), 0px 12px 16px -4px rgba(16, 24, 40, 0.08);
		}
		.ast-live-search-results > * {
			-js-display: flex;
			display: flex;
			justify-content: ' . esc_attr( $ltr_left ) . ';
			flex-wrap: wrap;
			align-items: center;
		}
		label.ast-search--posttype-heading {
			text-transform: capitalize;
			padding: 16px 16px 10px;
			color: ' . esc_attr( $heading_base_color ) . ';
			font-weight: 500;
		}
		label.ast-search--no-results-heading {
			padding: 14px 20px;
		}
		a.ast-search-item {
			position: relative;
			padding: 14px 20px;
			font-size: 0.9em;
		}
		a.ast-search-item:hover {
			background-color: #f9fafb;
		}
		a.ast-search-page-link {
			justify-content: center;
			justify-content: center;
			border: 1px solid var(--ast-border-color);
			margin-top: 10px;
		}
		.ast-search-item + .ast-search--posttype-heading {
			border-top: 1px solid var(--ast-border-color);
			margin-top: 10px;
		}
	';

	$dynamic_css .= AAA_Chinese_Enqueue_Scripts::trim_css( $static_css );

	$search_style = aaa_chinese_get_option( 'header-search-box-type' );
	if ( ! defined( 'AAA_EXT_VER' ) || ( defined( 'AAA_EXT_VER' ) && ( 'slide-search' === $search_style || 'search-box' === $search_style ) ) ) {
		$search_width    = aaa_chinese_get_option( 'header-search-width' );
		$search_selector = '.ast-header-search .ast-search-menu-icon';

		$container_css        = array(
			$search_selector . ' .search-field' => array(
				'width' => ! empty( $search_width['desktop'] ) ? aaa_chinese_get_css_value( $search_width['desktop'], 'px' ) : 'auto',
			),
		);
		$container_css_tablet = array(
			$search_selector . ' .search-field' => array(
				'width' => ! empty( $search_width['tablet'] ) ? aaa_chinese_get_css_value( $search_width['tablet'], 'px' ) : '100%',
			),
		);
		$container_css_mobile = array(
			$search_selector . ' .search-field' => array(
				'width' => ! empty( $search_width['mobile'] ) ? aaa_chinese_get_css_value( $search_width['mobile'], 'px' ) : '100%',
			),
		);

		$dynamic_css .= aaa_chinese_parse_css( $container_css );
		$dynamic_css .= aaa_chinese_parse_css( $container_css_tablet, '', aaa_chinese_get_tablet_breakpoint() );
		$dynamic_css .= aaa_chinese_parse_css( $container_css_mobile, '', aaa_chinese_get_mobile_breakpoint() );
	}

	return $dynamic_css;
}
