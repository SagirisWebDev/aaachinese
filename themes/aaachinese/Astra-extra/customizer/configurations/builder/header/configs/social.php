<?php
/**
 * Social Header Configuration.
 *
 * @package     AAA Chinese
 * @link        https://wpastra.com/
 * @since       4.5.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register social header builder Customizer Configurations.
 *
 * @param array $configurations AAA Chinese Customizer Configurations.
 * @since 4.5.2
 * @return array AAA Chinese Customizer Configurations with updated configurations.
 */
function aaa_chinese_header_social_configuration( $configurations = array() ) {

	$_configs = AAA_Chinese_Social_Icon_Component_Configs::register_configuration( $configurations, 'header', 'section-hb-social-icons-' );

	if ( AAA_Chinese_Builder_Customizer::aaa_chinese_collect_customizer_builder_data() ) {
		array_map( 'aaa_chinese_save_header_customizer_configs', $_configs );
	}

	return $_configs;
}

if ( AAA_Chinese_Builder_Customizer::aaa_chinese_collect_customizer_builder_data() ) {
	add_action( 'init', 'aaa_chinese_header_social_configuration', 10, 0 );
}
