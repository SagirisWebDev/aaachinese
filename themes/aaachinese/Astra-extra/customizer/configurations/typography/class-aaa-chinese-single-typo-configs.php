<?php
/**
 * Styling Options for AAA Chinese Theme.
 *
 * @package     AAA Chinese
 *
 * @since       AAA Chinese 1.0.15
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AAA_Chinese_Single_Typo_Configs' ) ) {

	/**
	 * Customizer Single Typography Configurations.
	 *
	 * @since 1.4.3
	 */
	class AAA_Chinese_Single_Typo_Configs extends AAA_Chinese_Customizer_Config_Base {
		/**
		 * Register Single Typography configurations.
		 *
		 * @param Array                $configurations AAA Chinese Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array AAA Chinese Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array();

			// Learn More link if AAA Chinese Pro is not activated.
			if ( aaa_chinese_showcase_upgrade_notices() ) {

				$_configs = array(

					/**
					 * Option: AAA Chinese Pro blog single post's options.
					 */
					array(
						'name'     => AAA_THEME_SETTINGS . '[ast-single-post-items]',
						'type'     => 'control',
						'control'  => 'ast-upgrade',
						'campaign' => 'blog-single',
						'choices'  => array(
							'one'   => array(
								'title' => __( 'Author Box with Social Share', 'astra' ),
							),
							'two'   => array(
								'title' => __( 'Auto load previous posts', 'astra' ),
							),
							'three' => array(
								'title' => __( 'Single post navigation control', 'astra' ),
							),
							'four'  => array(
								'title' => __( 'Custom featured images size', 'astra' ),
							),
							'seven' => array(
								'title' => __( 'Single post read time', 'astra' ),
							),
							'five'  => array(
								'title' => __( 'Extended typography options', 'astra' ),
							),
							'six'   => array(
								'title' => __( 'Extended spacing options', 'astra' ),
							),
							'eight' => array(
								'title' => __( 'Social sharing options', 'astra' ),
							),
						),
						'section'  => 'section-blog-single',
						'default'  => '',
						'priority' => 999,
						'context'  => array(),
						'title'    => __( 'Extensive range of tools to help blog pages stand out.', 'astra' ),
						'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
					),
				);
			}

			return array_merge( $configurations, $_configs );
		}
	}
}

new AAA_Chinese_Single_Typo_Configs();
