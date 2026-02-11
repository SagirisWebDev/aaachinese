<?php
/**
 * Global Misc Options for AAA Chinese Theme.
 *
 * @package     AAA Chinese
 *
 * @since       AAA Chinese  4.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register AAA Chinese Global Misc Configurations.
 */
class AAA_Chinese_Global_Misc_Configs extends AAA_Chinese_Customizer_Config_Base {
	/**
	 * Register AAA Chinese Global Misc  Configurations.
	 *
	 * @param Array                $configurations AAA Chinese Customizer Configurations.
	 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
	 * @since 4.0.0
	 * @return Array AAA Chinese Customizer Configurations with updated configurations.
	 */
	public function register_configuration( $configurations, $wp_customize ) {

		$_configs = array(

			/**
			 * Option: Scroll to id.
			 */
			array(
				'name'     => AAA_THEME_SETTINGS . '[enable-scroll-to-id]',
				'default'  => aaa_chinese_get_option( 'enable-scroll-to-id' ),
				'type'     => 'control',
				'control'  => 'ast-toggle-control',
				'title'    => __( 'Enable Smooth Scroll to ID', 'astra' ),
				'section'  => 'section-global-misc',
				'divider'  => array( 'ast_class' => 'ast-section-spacing' ),
				'priority' => 10,
			),
		);

		return array_merge( $configurations, $_configs );
	}
}

new AAA_Chinese_Global_Misc_Configs();
