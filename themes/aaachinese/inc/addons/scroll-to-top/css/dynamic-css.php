<?php
/**
 * Scroll To Top - Dynamic CSS
 *
 * @package AAA Chinese
 */

add_filter( 'aaa_chinese_dynamic_theme_css', 'aaa_chinese_scroll_to_top_dynamic_css', 11 );

/**
 * Dynamic CSS
 *
 * @param  string $dynamic_css          AAA Chinese Dynamic CSS.
 * @param  string $dynamic_css_filtered AAA Chinese Dynamic CSS Filters.
 * @return string
 */
function aaa_chinese_scroll_to_top_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	if ( true !== aaa_chinese_get_option( 'scroll-to-top-enable', true ) ) {
		return $dynamic_css;
	}

	$link_color                       = aaa_chinese_get_option( 'link-color' );
	$scroll_to_top_icon_size          = aaa_chinese_get_option( 'scroll-to-top-icon-size', 15 );
	$scroll_to_top_icon_radius_fields = aaa_chinese_get_option( 'scroll-to-top-icon-radius-fields' );
	$scroll_to_top_icon_color         = aaa_chinese_get_option( 'scroll-to-top-icon-color' );
	$scroll_to_top_icon_h_color       = aaa_chinese_get_option( 'scroll-to-top-icon-h-color' );
	$scroll_to_top_icon_bg_color      = aaa_chinese_get_option( 'scroll-to-top-icon-bg-color', $link_color );
	$scroll_to_top_icon_h_bg_color    = aaa_chinese_get_option( 'scroll-to-top-icon-h-bg-color' );

	$scroll_to_top = array(
		'#ast-scroll-top'       => array(
			'color'                      => $scroll_to_top_icon_color,
			'background-color'           => $scroll_to_top_icon_bg_color,
			'font-size'                  => aaa_chinese_get_css_value( $scroll_to_top_icon_size, 'px' ),
			'border-top-left-radius'     => aaa_chinese_responsive_spacing( $scroll_to_top_icon_radius_fields, 'top', 'desktop' ),
			'border-top-right-radius'    => aaa_chinese_responsive_spacing( $scroll_to_top_icon_radius_fields, 'right', 'desktop' ),
			'border-bottom-right-radius' => aaa_chinese_responsive_spacing( $scroll_to_top_icon_radius_fields, 'bottom', 'desktop' ),
			'border-bottom-left-radius'  => aaa_chinese_responsive_spacing( $scroll_to_top_icon_radius_fields, 'left', 'desktop' ),
		),
		'#ast-scroll-top:hover' => array(
			'color'            => $scroll_to_top_icon_h_color,
			'background-color' => $scroll_to_top_icon_h_bg_color,
		),
	);

	$scroll_css = aaa_chinese_parse_css( $scroll_to_top );

	$scroll_to_top_tablet = array(
		'#ast-scroll-top' => array(
			'border-top-left-radius'     => aaa_chinese_responsive_spacing( $scroll_to_top_icon_radius_fields, 'top', 'tablet' ),
			'border-top-right-radius'    => aaa_chinese_responsive_spacing( $scroll_to_top_icon_radius_fields, 'right', 'tablet' ),
			'border-bottom-right-radius' => aaa_chinese_responsive_spacing( $scroll_to_top_icon_radius_fields, 'bottom', 'tablet' ),
			'border-bottom-left-radius'  => aaa_chinese_responsive_spacing( $scroll_to_top_icon_radius_fields, 'left', 'tablet' ),
		),
	);
	/* Parse CSS from array() -> max-width: (tablet-breakpoint) px CSS */
	$scroll_css .= aaa_chinese_parse_css( $scroll_to_top_tablet, '', aaa_chinese_get_tablet_breakpoint() );

	$scroll_to_top_mobile = array(
		'#ast-scroll-top' => array(
			'border-top-left-radius'     => aaa_chinese_responsive_spacing( $scroll_to_top_icon_radius_fields, 'top', 'mobile' ),
			'border-top-right-radius'    => aaa_chinese_responsive_spacing( $scroll_to_top_icon_radius_fields, 'right', 'mobile' ),
			'border-bottom-right-radius' => aaa_chinese_responsive_spacing( $scroll_to_top_icon_radius_fields, 'bottom', 'mobile' ),
			'border-bottom-left-radius'  => aaa_chinese_responsive_spacing( $scroll_to_top_icon_radius_fields, 'left', 'mobile' ),
		),
	);
	/* Parse CSS from array() -> max-width: (mobile-breakpoint) px CSS */
	$scroll_css .= aaa_chinese_parse_css( $scroll_to_top_mobile, '', aaa_chinese_get_mobile_breakpoint() );

	if ( is_rtl() ) {
		$scroll_to_top_rtl = array(
			'#ast-scroll-top .ast-icon.icon-arrow svg' => array(
				'margin-right' => '0px',
			),
		);

		$scroll_css .= aaa_chinese_parse_css( $scroll_to_top_rtl );
	}

	if ( false === AAA_Chinese_Icons::is_svg_icons() ) {
		$scroll_to_top_icon = array(
			'.ast-scroll-top-icon::before' => array(
				'content'         => '"\e900"',
				'font-family'     => 'AAA Chinese',
				'text-decoration' => 'inherit',
			),
			'.ast-scroll-top-icon'         => array(
				'transform' => 'rotate(180deg)',
			),

		);

		$scroll_css .= aaa_chinese_parse_css( $scroll_to_top_icon );
	}

	// Only if responsive devices is selected.
	$svg_width = array(
		/**
		 * Add spacing based on padded layout spacing
		 */
		'#ast-scroll-top .ast-icon.icon-arrow svg' => array(
			'width' => '1em',
		),
	);

	$scroll_css .= aaa_chinese_parse_css( $svg_width, '', aaa_chinese_get_tablet_breakpoint() );

	return $dynamic_css . $scroll_css;
}
