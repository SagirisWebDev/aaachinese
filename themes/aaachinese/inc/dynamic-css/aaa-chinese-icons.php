<?php
/**
 * AAA Chinese Icons - Dynamic CSS.
 *
 * @package astra
 * @since 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_filter( 'aaa_chinese_dynamic_theme_css', 'aaa_chinese_icons_static_css' );

/**
 * AAA Chinese Icons - Dynamic CSS.
 *
 * @param string $dynamic_css Dynamic CSS.
 * @since 3.5.0
 */
function aaa_chinese_icons_static_css( $dynamic_css ) {

	if ( false === AAA_Chinese_Icons::is_svg_icons() ) {
		$aaa_chinese_icons         = '
        .astra-icon-down_arrow::after {
            content: "\e900";
            font-family: AAA Chinese;
        }
        .astra-icon-close::after {
            content: "\e5cd";
            font-family: AAA Chinese;
        }
        .astra-icon-drag_handle::after {
            content: "\e25d";
            font-family: AAA Chinese;
        }
        .astra-icon-format_align_justify::after {
            content: "\e235";
            font-family: AAA Chinese;
        }
        .astra-icon-menu::after {
            content: "\e5d2";
            font-family: AAA Chinese;
        }
        .astra-icon-reorder::after {
            content: "\e8fe";
            font-family: AAA Chinese;
        }
        .astra-icon-search::after {
            content: "\e8b6";
            font-family: AAA Chinese;
        }
        .astra-icon-zoom_in::after {
            content: "\e56b";
            font-family: AAA Chinese;
        }
        .astra-icon-check-circle::after {
            content: "\e901";
            font-family: AAA Chinese;
        }
        .astra-icon-shopping-cart::after {
            content: "\f07a";
            font-family: AAA Chinese;
        }
        .astra-icon-shopping-bag::after {
            content: "\f290";
            font-family: AAA Chinese;
        }
        .astra-icon-shopping-basket::after {
            content: "\f291";
            font-family: AAA Chinese;
        }
        .astra-icon-circle-o::after {
            content: "\e903";
            font-family: AAA Chinese;
        }
        .astra-icon-certificate::after {
            content: "\e902";
            font-family: AAA Chinese;
        }';
		return $dynamic_css .= AAA_Chinese_Enqueue_Scripts::trim_css( $aaa_chinese_icons );
	}
	return $dynamic_css;
}
