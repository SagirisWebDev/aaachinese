<?php
/**
 * HTML footer Configuration.
 *
 * @package     AAA Chinese
 * @link        https://wpastra.com/
 * @since       4.5.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register html footer builder Customizer Configurations.
 *
 * @param array $configurations AAA Chinese Customizer Configurations.
 * @since 4.5.2
 * @return array AAA Chinese Customizer Configurations with updated configurations.
 */
function aaa_chinese_html_footer_configuration( $configurations = array() ) {
	$_configs = AAA_Chinese_Html_Component_Configs::register_configuration( $configurations, 'footer', 'section-fb-html-' );

	if ( AAA_Chinese_Builder_Customizer::aaa_chinese_collect_customizer_builder_data() ) {
		array_map( 'aaa_chinese_save_footer_customizer_configs', $_configs );
	}

	return $_configs;
}

if ( AAA_Chinese_Builder_Customizer::aaa_chinese_collect_customizer_builder_data() ) {
	add_action( 'init', 'aaa_chinese_html_footer_configuration', 10, 0 );
}
