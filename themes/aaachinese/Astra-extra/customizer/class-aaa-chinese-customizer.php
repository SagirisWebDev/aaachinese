<?php
/**
 * AAA Chinese Theme Customizer
 *
 * @package     AAA Chinese
 *
 * @since       AAA Chinese 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Customizer Loader
 */
if ( ! class_exists( 'AAA_Chinese_Customizer' ) ) {

	/**
	 * Customizer Loader
	 *
	 * @since 1.0.0
	 */
	class AAA_Chinese_Customizer {
		/**
		 * Contexts.
		 *
		 * @var object
		 */
		private static $contexts;

		/**
		 * Dynamic options.
		 *
		 * @since 3.1.0
		 * @var object
		 */
		private static $dynamic_options = array();

		/**
		 * Tabful sections.
		 *
		 * @var array
		 */
		private static $tabbed_sections = array();

		/**
		 * Choices.
		 *
		 * @var object
		 */
		private static $choices;

		/**
		 * JS Configs.
		 *
		 * @var object
		 */
		private static $js_configs;

		/**
		 * Instance
		 *
		 * @var object
		 */
		private static $instance;

		/**
		 * Customizer Configurations.
		 *
		 * @since 1.4.3
		 * @var Array
		 */
		private static $configuration;

		/**
		 * All groups parent-child relation array data.
		 *
		 * @since 2.0.0
		 * @var Array
		 */
		public static $group_configs = array();

		/**
		 * All header configs array data.
		 *
		 * @since 4.5.2
		 * @var array
		 */
		public static $customizer_header_configs = array(
			'different-retina-logo',
			'ast-header-retina-logo',
			'different-mobile-logo',
			'mobile-header-logo',
			'header-color-site-tagline',
			'ast-header-responsive-logo-width',
			'display-site-title-responsive',
			'display-site-tagline-responsive',
			'logo-title-inline',
		);

		/**
		 * All footer configs array data.
		 *
		 * @since 4.5.2
		 * @var array
		 */
		public static $customizer_footer_configs = array();

		/**
		 * Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Check if the current customizer request belongs to AAA Chinese theme.
		 *
		 * @return bool True if it is AAA Chinese customizer, false otherwise.
		 *
		 * @since 4.8.3
		 */
		public static function is_aaa_chinese_customizer() {

			// Bail early if it is the Kadence WooCommerce Email Designer plugin customizer.
			if (
				class_exists( 'Kadence_Woomail_Designer' ) &&
				( Kadence_Woomail_Designer::is_own_customizer_request() || Kadence_Woomail_Designer::is_own_preview_request() )
			) {
				return false;
			}

			// Bail early if it is the Decorator - WooCommerce Email Customizer plugin customizer.
			if (
				class_exists( 'RP_Decorator' ) &&
				( RP_Decorator::is_own_customizer_request() || RP_Decorator::is_own_preview_request() )
			) {
				return false;
			}

			// Bail early if it is the Email Customizer Pro plugin customizer.
			if ( isset( $_GET['sa_email_customizer'] ) && true == $_GET['sa_email_customizer'] ) { // phpcs:ignore WordPress.Security.NonceVerification
				return false;
			}

			/**
			 * Allow third-party plugins to bail early from AAA Chinese customizer.
			 *
			 * @param bool $is_aaa_chinese_customizer Whether the current request is for AAA Chinese customizer.
			 *
			 * @return bool True if it is AAA Chinese customizer, false otherwise.
			 * @since 4.11.16
			 */
			return apply_filters( 'aaa_chinese_is_aaa_chinese_customizer', true );
		}
		/**
		 * Constructor
		 */
		public function __construct() {

			add_action( 'aaa_chinese_style_guide_site_icon', array( $this, 'site_icon_update' ) );

			// Hooks that are necessary even if it is not AAA Chinese's customizer.
			if ( is_admin() || is_customize_preview() ) {
				add_action( 'customize_register', array( $this, 'include_configurations' ), 2 );
				add_action( 'customize_register', array( $this, 'aaa_chinese_pro_upgrade_configurations' ), 2 );
			}
			add_action( 'customize_register', array( $this, 'customize_register_panel' ), 2 );

			// Bail early if it is not astra customizer.
			if ( ! self::is_aaa_chinese_customizer() ) {
				return;
			}

			/**
			 * Customizer
			 */
			add_action( 'customize_preview_init', array( $this, 'preview_init' ) );

			if ( is_admin() || is_customize_preview() ) {
				add_action( 'customize_register', array( $this, 'prepare_customizer_javascript_configs' ) );
				add_action( 'customize_register', array( $this, 'prepare_group_configs' ), 9 );

				add_filter( 'customize_dynamic_setting_args', array( $this, 'filter_dynamic_setting_args' ), 10, 2 );
				add_filter( 'customize_dynamic_partial_args', array( $this, 'filter_dynamic_partial_args' ), 10, 2 );

			}

			// Disable block editor for widgets in the customizer.
			if ( defined( 'GUTENBERG_VERSION' ) && version_compare( GUTENBERG_VERSION, '10.6.2', '>' ) && is_customize_preview() ) {
				add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
			}

			add_action( 'customize_controls_enqueue_scripts', array( $this, 'controls_scripts' ) );
			add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_customizer_scripts' ), 999 );

			add_action( 'customize_controls_print_footer_scripts', array( $this, 'print_footer_scripts' ) );

			add_action( 'customize_register', array( $this, 'customize_register' ) );
			add_action( 'customize_register', array( $this, 'customize_register_site_icon' ), 20 );
			add_action( 'customize_save_after', array( $this, 'customize_save' ) );
			add_action( 'customize_save_after', array( $this, 'delete_cached_partials' ) );
			add_action( 'wp_head', array( $this, 'preview_styles' ) );
			add_action( 'wp_ajax_aaa_chinese_regenerate_fonts_folder', array( $this, 'regenerate_aaa_chinese_fonts_folder' ) );

			add_action( 'wp_footer', array( $this, 'style_guide_template' ) );

			// Handles the AJAX request for astra SVG icons.
			add_action( 'wp_ajax_aaa_chinese_logo_svg_icons', array( $this, 'logo_svg_icons' ) );
		}

		/**
		 * Add site icon control in the site identity panel.
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 * @return void
		 *
		 * @since 3.6.9
		 */
		public function customize_register_site_icon( $wp_customize ) {

			/** @psalm-suppress RedundantConditionGivenDocblockType */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			if ( true !== AAA_Chinese_Builder_Helper::$is_header_footer_builder_active ) {
				/** @psalm-suppress RedundantConditionGivenDocblockType */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
				return;
			}

			$panel_arr = array(
				'priority'       => 80,
				'capability'     => 'edit_theme_options',
				'theme_supports' => '',
				'title'          => __( 'Site Identity', 'astra' ),
				'description'    => '',
			);
			// Register panel.
			/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$wp_customize->add_panel( 'astra-site-identity', $panel_arr );
			/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort

			$section_arr = array(
				'priority'       => 80,
				'capability'     => 'edit_theme_options',
				'theme_supports' => '',
				'title'          => __( 'Site Identity', 'astra' ),
				'description'    => '',
			);

			// Register Section.
			$wp_customize->add_section( 'astra-site-identity', $section_arr );

			/** @psalm-suppress PossiblyNullPropertyAssignment */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$wp_customize->get_control( 'site_icon' )->section = 'astra-site-identity';
			/** @psalm-suppress PossiblyNullPropertyAssignment */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort

			/** @psalm-suppress PossiblyNullPropertyAssignment */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$wp_customize->get_control( 'site_icon' )->description = __( 'Site Icons are what you see in browser tabs, bookmark bars, and within the WordPress mobile apps. Upload one here! Site Icons should be square and at least 512 × 512 pixels.', 'astra' );
			/** @psalm-suppress PossiblyNullPropertyAssignment */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
		}

		/**
		 * Reset font folder.
		 *
		 * @return void
		 *
		 * @since 3.6.0
		 */
		public function regenerate_aaa_chinese_fonts_folder() {
			check_ajax_referer( 'aaa_chinese_update_admin_setting', 'security' );

			if ( ! current_user_can( 'edit_theme_options' ) ) {
				wp_send_json_error( 'invalid_permissions' );
			}

			if ( AAA_Chinese_API_Init::get_admin_settings_option( 'self_hosted_gfonts', false ) ) {
				$local_font_loader = aaa_chinese_webfont_loader_instance( '' );
				$flushed           = $local_font_loader->aaa_chinese_delete_fonts_folder();

				if ( ! $flushed ) {
					$response_data = array( 'message' => __( 'Failed to Flush, try again later.', 'astra' ) );
					wp_send_json_error( $response_data );
				}
				wp_send_json_success();
			}

			$response_data = array( 'message' => __( 'Local font files not present.', 'astra' ) );
			wp_send_json_error( $response_data );
		}

		/**
		 *  Delete the cached partial configs.
		 */
		public function delete_cached_partials() {
			delete_option( 'aaa_chinese_partials_config_cache' );

			// Delete previously stored local fonts data, if exists.
			if ( AAA_Chinese_API_Init::get_admin_settings_option( 'self_hosted_gfonts', false ) ) {
				$local_webfont_loader = aaa_chinese_webfont_loader_instance( '' );
				$local_webfont_loader->aaa_chinese_delete_fonts_folder();
			}
		}

		/**
		 * Add dynamic control partial refresh.
		 *
		 * @since 3.1.0
		 * @param array  $partial_args partial configs.
		 * @param string $partial_id partial id.
		 * @return array|mixed
		 */
		public function filter_dynamic_partial_args( $partial_args, $partial_id ) {

			if ( isset( self::$dynamic_options['partials'][ $partial_id ] ) ) {
				if ( false === $partial_args ) {
					$partial_args = array();
				}
				$partial_args = array_merge( $partial_args, self::$dynamic_options['partials'][ $partial_id ] );
			}

			return $partial_args;
		}

		/**
		 * Add dynamic control settings.
		 *
		 * @since 3.1.0
		 * @param array  $setting_args setting configs.
		 * @param string $setting_id setting id.
		 * @return mixed
		 */
		public function filter_dynamic_setting_args( $setting_args, $setting_id ) {

			if ( isset( self::$dynamic_options['settings'][ $setting_id ] ) ) {
				return self::$dynamic_options['settings'][ $setting_id ];
			}

			return $setting_args;
		}

		/**
		 * Prepare Contexts and choices.
		 *
		 * @since 3.0.0
		 */
		public function prepare_customizer_javascript_configs() {

			global $wp_customize;

			$cached_data = get_option( 'aaa_chinese_partials_config_cache', false );

			if ( $wp_customize->selective_refresh->is_render_partials_request() && $cached_data ) {
				self::$dynamic_options = $cached_data;
				return;
			}

			$configurations = $this->get_customizer_configurations();

			$defaults = $this->get_aaa_chinese_customizer_configuration_defaults();

			foreach ( $configurations as $configuration ) {

				$config = wp_parse_args( $configuration, $defaults );

				if ( isset( $configuration['context'] ) ) {
					self::$contexts[ $configuration['name'] ] = $configuration['context'];
				} else {
					if ( isset( $configuration['type'] ) && ( ( 'control' === $configuration['type'] ) || ( 'sub-control' === $configuration['type'] ) ) ) {
						if ( ( isset( $configuration['control'] ) && 'ast-builder-header-control' !== $configuration['control'] ) && ( isset( $configuration['name'] ) && strpos( $configuration['name'], 'ast-callback-notice' ) === false ) ) {
							self::$contexts[ $configuration['name'] ] = AAA_Chinese_Builder_Helper::$general_tab;
						}
					}
				}

				if ( isset( $configuration['choices'] ) ) {
					self::$choices[ $configuration['name'] ] = $configuration['choices'];
				}

				switch ( $config['type'] ) {

					case 'panel':
						$this->prepare_javascript_panel_configs( $config );
						break;
					case 'section':
						$this->prepare_javascript_section_configs( $config );
						break;

					case 'sub-control':
						$this->prepare_javascript_sub_control_configs( $config );
						break;
					case 'control':
						$this->prepare_javascript_control_configs( $config );
						break;
				}
			}

			update_option( 'aaa_chinese_partials_config_cache', self::$dynamic_options, false );
		}

		/**
		 * Get control default.
		 *
		 * @param string $setting_key setting key.
		 * @param array  $default_values default value array.
		 * @return mixed|string
		 */
		private function get_default_value( $setting_key, $default_values ) {
			$return = '';
			preg_match( '#astra-settings\[(.*?)\]#', $setting_key, $match );
			if ( ! empty( $match ) && isset( $match[1] ) ) {
				$return = isset( $default_values[ $match[1] ] ) ? $default_values[ $match[1] ] : '';
			}
			return $return;
		}

		/**
		 * Prepare tabbed sections for dynamic controls to optimize frontend JS calls.
		 */
		private static function prepare_tabbed_sections() {

			if ( ! isset( self::$js_configs['controls'] ) ) {
				return;
			}

			foreach ( self::$js_configs['controls'] as $section_id => $controls ) {
				$tab_id        = $section_id . '-ast-context-tabs';
				$control_names = wp_list_pluck( $controls, 'name' );
				if ( in_array( $tab_id, $control_names, true ) ) {
					array_push( self::$tabbed_sections, $section_id );
				}
			}
		}

		/**
		 * Print Footer Scripts
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function print_footer_scripts() {
			$output  = '<script type="text/javascript">';
			$output .= '
	        	wp.customize.bind(\'ready\', function() {
	            	wp.customize.control.each(function(ctrl, i) {
	                	var desc = ctrl.container.find(".customize-control-description");
	                	if( desc.length) {
	                    	var title 		= ctrl.container.find(".customize-control-title");
	                    	var li_wrapper 	= desc.closest("li");
	                    	var tooltip = desc.text().replace(/[\u00A0-\u9999<>\&]/gim, function(i) {
	                    			return \'&#\'+i.charCodeAt(0)+\';\';
								});
	                    	desc.remove();
	                    	li_wrapper.append(" <i class=\'ast-control-tooltip dashicons dashicons-editor-help\'data-title=\'" + tooltip +"\'></i><span class=\'ast-tooltip\'data-title=\'" + tooltip + "\'><span>");
	                	}
	            	});
	        	});';

			$output .= AAA_Chinese_Fonts_Data::js();
			$output .= '</script>';

			echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		/**
		 *  Set default context for WP default controls.
		 */
		private static function set_default_context() {

			if ( false === AAA_Chinese_Builder_Helper::$is_header_footer_builder_active ) {
				return;
			}

			self::$contexts['blogname'] = array(
				AAA_Chinese_Builder_Helper::$general_tab_config,

			);

			self::$contexts['blogdescription'] = array(
				AAA_Chinese_Builder_Helper::$general_tab_config,

			);
		}

		/**
		 * Bypass JS configs for Controls.
		 *
		 * @param array $configuration configuration.
		 */
		public static function bypass_control_configs( $configuration ) {

			$val = '';

			if ( isset( $configuration['name'] ) ) {

				$data = explode( '[', rtrim( $configuration['name'], ']' ) );

				if ( isset( $data[1] ) ) {
					$val = aaa_chinese_get_option( $data[1] );
				}
			}

			if ( isset( $val ) && ! empty( $val ) ) {

				$configuration['value'] = $val;
			}

			switch ( $configuration['type'] ) {

				case 'ast-builder':
					if ( is_array( $configuration['default'] ) && ! isset( $configuration['default']['popup'] ) ) {
						$configuration['default']['popup'] = array( 'popup_content' => array() );
					}
					break;
				case 'ast-responsive-spacing':
					if ( ! is_array( $val ) || is_numeric( $val ) ) {

						$configuration['value'] = array(
							'desktop'      => array(
								'top'    => $val,
								'right'  => '',
								'bottom' => $val,
								'left'   => '',
							),
							'tablet'       => array(
								'top'    => $val,
								'right'  => '',
								'bottom' => $val,
								'left'   => '',
							),
							'mobile'       => array(
								'top'    => $val,
								'right'  => '',
								'bottom' => $val,
								'left'   => '',
							),
							'desktop-unit' => 'px',
							'tablet-unit'  => 'px',
							'mobile-unit'  => 'px',
						);
					}

					break;
				case 'ast-radio-image':
					$configuration['value'] = $val;

					if ( isset( $configuration['choices'] ) && is_array( $configuration['choices'] ) ) {

						foreach ( $configuration['choices'] as $key => $value ) {
							$configuration['choices'][ $key ]         = $value['path'];
							$configuration['choices_titles'][ $key ]  = $value['label'];
							$configuration['choices_upgrade'][ $key ] = isset( $value['is_pro'] ) ? $value['is_pro'] : false;
						}
					}
					if ( isset( $configuration['inputAttrs'] ) ) {

						$configuration['inputAttrs'] = '';
						$configuration['labelStyle'] = '';
						/** @psalm-suppress PossiblyUndefinedStringArrayOffset */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
						foreach ( $configuration['input_attrs'] as $attr => $value ) {
							/** @psalm-suppress PossiblyUndefinedStringArrayOffset */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
							if ( 'style' !== $attr ) {
								$configuration['inputAttrs'] .= $attr . '="' . esc_attr( $value ) . '" ';
							} else {
								$configuration['labelStyle'] = 'style="' . esc_attr( $value ) . '" ';
							}
						}
					}
					break;
				case 'ast-border':
					$configuration['value'] = $val;
					break;
				case 'ast-section-toggle':
					$configuration['value'] = $val;
					break;
				case 'ast-responsive-slider':
					if ( ! is_array( $val ) || is_numeric( $val ) ) {

						$configuration['value'] = array(
							'desktop' => $val,
							'tablet'  => '',
							'mobile'  => '',
						);
					}
					break;
				case 'ast-responsive-background':
					$configuration['value'] = $val;

					break;
				case 'ast-link':
					$configuration['value'] = $val;

					break;
				case 'ast-hidden':
					$configuration['value'] = $val;

					break;
				case 'ast-settings-group':
				case 'ast-multiselect-checkbox-group':
					$config = array();

					if ( isset( self::$group_configs[ $configuration['name'] ]['tabs'] ) ) {
						$tab = array_keys( self::$group_configs[ $configuration['name'] ]['tabs'] );
						rsort( $tab );
						foreach ( $tab as $value ) {
							$config['tabs'][ $value ] = wp_list_sort( self::$group_configs[ $configuration['name'] ]['tabs'][ $value ], 'priority' );
						}
					} else {
						if ( isset( self::$group_configs[ $configuration['name'] ] ) ) {
							$config = wp_list_sort( self::$group_configs[ $configuration['name'] ], 'priority' );
						}
					}
					$configuration['ast_fields'] = $config;
					break;
				case 'ast-font-weight':
					$configuration['ast_all_font_weight'] = array(
						'100'       => __( 'Thin 100', 'astra' ),
						'100italic' => __( '100 Italic', 'astra' ),
						'200'       => __( 'Extra-Light 200', 'astra' ),
						'200italic' => __( '200 Italic', 'astra' ),
						'300'       => __( 'Light 300', 'astra' ),
						'300italic' => __( '300 Italic', 'astra' ),
						'400'       => __( 'Normal 400', 'astra' ),
						'normal'    => __( 'Normal 400', 'astra' ),
						'italic'    => __( '400 Italic', 'astra' ),
						'500'       => __( 'Medium 500', 'astra' ),
						'500italic' => __( '500 Italic', 'astra' ),
						'600'       => __( 'Semi-Bold 600', 'astra' ),
						'600italic' => __( '600 Italic', 'astra' ),
						'700'       => __( 'Bold 700', 'astra' ),
						'700italic' => __( '700 Italic', 'astra' ),
						'800'       => __( 'Extra-Bold 800', 'astra' ),
						'800italic' => __( '800 Italic', 'astra' ),
						'900'       => __( 'Ultra-Bold 900', 'astra' ),
						'900italic' => __( '900 Italic', 'astra' ),
					);
					break;
				case 'ast-sortable':
					$configuration['value'] = $val;

					if ( isset( self::$group_configs[ $configuration['name'] ] ) ) {
						/** @psalm-suppress PossiblyUndefinedStringArrayOffset */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
						$config = wp_list_sort( self::$group_configs[ $configuration['name'] ], 'priority' );
						/** @psalm-suppress PossiblyUndefinedStringArrayOffset */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
						$configuration['ast_fields'] = $config;
					}

					break;
				case 'ast-font-variant':
					$configuration['value'] = $val;
					break;
				case 'ast-number':
					$configuration['value'] = $val;
					break;
				case 'ast-select-multi':
					$configuration['value'] = $val;
					break;
				case 'ast-logo-svg-icon':
					if ( ! isset( $val['type'] ) ) {
						$configuration['value'] = array(
							'type'  => '',
							'value' => '',
						);
					} else {
						$configuration['value'] = $val;
					}
					break;

				case 'ast-svg-icon-selector':
					if ( ! isset( $val['type'] ) ) {
						$configuration['value'] = array(
							'type'  => '',
							'value' => '',
						);
					} else {
						$configuration['value'] = $val;
					}
					break;

			} // Switch End.

			if ( isset( $configuration['id'] ) ) {

				$configuration['link'] = self::get_control_link( $configuration['id'] );
			}
			$exclude_controls = array( 'ast-builder', 'ast-radio-image' );

			if ( isset( $configuration['type'] ) && ! in_array( $configuration['type'], $exclude_controls ) && isset( $configuration['input_attrs'] ) && is_array( $configuration['input_attrs'] ) ) {

				$configuration['inputAttrs'] = '';

				foreach ( $configuration['input_attrs'] as $attr => $value ) {

					if ( ! is_array( $value ) ) {

						$configuration['inputAttrs'] .= $attr . '="' . esc_attr( $value ) . '" ';
					}
				}
			}

			return $configuration;
		}

		/**
		 * Handles the AJAX request for logo SVG icons.
		 * The main purpose of handling this via AJAX is to improve Customizer performance.
		 *
		 * @return array The array of logo SVG icons.
		 */
		public function logo_svg_icons() {
			// Check if the current user has the capability to edit theme options.
			if ( ! current_user_can( 'edit_theme_options' ) ) {
				wp_send_json_error( __( 'You are not allowed to access this resource.', 'astra' ) );
			}

			// Check if the current request is an AJAX request and if it is being done in the Customizer screen.
			if ( ! is_admin() || ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
				wp_send_json_error( __( 'This request is only allowed in the Customizer screen.', 'astra' ) );
			}

			wp_send_json_success(
				array(
					'icons' => function_exists( 'aaa_chinese_get_logo_svg_icons_array' ) ? aaa_chinese_get_logo_svg_icons_array() : array(),
				)
			);
		}

		/**
		 * Prepare Panel Configs for Javascript.
		 *
		 * @since 3.0.0
		 * @param array $config configs.
		 */
		public function prepare_javascript_panel_configs( $config ) {

			$panel_name = aaa_chinese_get_prop( $config, 'name' );

			unset( $config['type'] );
			$config['type']                            = 'ast_panel';
			$config['active']                          = true;
			$config['id']                              = $panel_name;
			self::$js_configs['panels'][ $panel_name ] = $config;
		}

		/**
		 * Prepare Section Configs for Javascript.
		 *
		 * @since 3.0.0
		 * @param array $config configs.
		 */
		public function prepare_javascript_section_configs( $config ) {

			$section_name = aaa_chinese_get_prop( $config, 'name' );

			unset( $config['type'] );
			$config['type']            = isset( $config['ast_type'] ) ? $config['ast_type'] : 'ast_section';
			$config['active']          = true;
			$config['id']              = $section_name;
			$config['customizeAction'] = sprintf( __( 'Customizing ▸ %s', 'astra' ), aaa_chinese_get_prop( $config, 'title' ) );

			if ( isset( $config['clone_type'] ) && isset( $config['clone_index'] ) ) {

				if ( isset( AAA_Chinese_Builder_Helper::$component_count_array[ $config['clone_type'] ] ) ) {
					/** @psalm-suppress PossiblyUndefinedStringArrayOffset */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
					if ( in_array( $section_name, AAA_Chinese_Builder_Helper::$component_count_array['removed-items'], true ) || AAA_Chinese_Builder_Helper::$component_count_array[ $config['clone_type'] ] < $config['clone_index'] ) {
						/** @psalm-suppress PossiblyUndefinedStringArrayOffset */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
						self::$js_configs['clone_sections'][ $section_name ] = $config;
					} else {
						self::$js_configs['sections'][ $section_name ] = $config;
					}
				}
			} else {
				self::$js_configs['sections'][ $section_name ] = $config;
			}
		}

		/**
		 * Prepare Sub Control Configs for Javascript.
		 *
		 * @since 3.0.0
		 * @param array $config configs.
		 */
		public function prepare_javascript_sub_control_configs( $config ) {

			unset( $config['type'] );

			$name             = aaa_chinese_get_prop( $config, 'name' );
			$parent           = aaa_chinese_get_prop( $config, 'parent' );
			$sub_control_name = AAA_THEME_SETTINGS . '[' . $name . ']';

			$ignore_controls = array(
				'ast-settings-group',
				'ast-multiselect-checkbox-group',
				'ast-sortable',
				'ast-radio-image',
				'ast-slider',
				'ast-responsive-slider',
				'ast-section-toggle',
			);

			$sanitize_callback = in_array( $config['control'], $ignore_controls, true ) ? false : aaa_chinese_get_prop( $config, 'sanitize_callback', AAA_Chinese_Customizer_Control_Base::get_sanitize_call( aaa_chinese_get_prop( $config, 'control' ) ) );

			if ( ! $sanitize_callback ) {
				$config = $this->sanitize_control( $config );
			}

			$new_config = array(
				'name'              => $sub_control_name,
				'datastore_type'    => 'option',
				'transport'         => 'postMessage',
				'control'           => 'ast-hidden',
				'section'           => aaa_chinese_get_prop( $config, 'section', 'title_tagline' ),
				'title'             => aaa_chinese_get_prop( $config, 'title' ),
				'priority'          => aaa_chinese_get_prop( $config, 'priority', '10' ),
				'default'           => aaa_chinese_get_prop( $config, 'default' ),
				'sanitize_callback' => $sanitize_callback,
				'suffix'            => aaa_chinese_get_prop( $config, 'suffix' ),
				'control_type'      => aaa_chinese_get_prop( $config, 'control' ),
				'linked'            => aaa_chinese_get_prop( $config, 'linked' ),
				'variant'           => aaa_chinese_get_prop( $config, 'variant' ),
				'help'              => aaa_chinese_get_prop( $config, 'help' ),
				'description'       => aaa_chinese_get_prop( $config, 'description' ),
				'input_attrs'       => aaa_chinese_get_prop( $config, 'input_attrs' ),
				'disable'           => aaa_chinese_get_prop( $config, 'disable' ),
			);

			self::$dynamic_options['settings'][ aaa_chinese_get_prop( $new_config, 'name' ) ] = array(
				'default'           => aaa_chinese_get_prop( $new_config, 'default' ),
				'type'              => aaa_chinese_get_prop( $new_config, 'datastore_type' ),
				'transport'         => aaa_chinese_get_prop( $new_config, 'transport', 'refresh' ),
				'sanitize_callback' => aaa_chinese_get_prop( $new_config, 'sanitize_callback', AAA_Chinese_Customizer_Control_Base::get_sanitize_call( aaa_chinese_get_prop( $new_config, 'control' ) ) ),
			);

			$new_config['type']                               = aaa_chinese_get_prop( $new_config, 'control' );
			$new_config['id']                                 = aaa_chinese_get_prop( $new_config, 'name' );
			$new_config['settings']                           = array( 'default' => aaa_chinese_get_prop( $new_config, 'name' ) );
			$new_config                                       = self::bypass_control_configs( $new_config );
			self::$js_configs ['sub_controls'] [ $parent ] [] = $new_config;

			// Keep contextual sub controls aside to process initially in customizer.js.
			if ( isset( $config['contextual_sub_control'] ) ) {
				self::$js_configs ['contextual_sub_controls'] [ $name ] = $new_config;
			}
		}

		/**
		 * Get the Link for Control.
		 *
		 * @since 3.0.0
		 * @param array $id Control ID.
		 */
		public static function get_control_link( $id ) {
			if ( isset( $id ) ) {
				return 'data-customize-setting-link="' . $id . '"';
			}
				return 'data-customize-setting-key-link="default"';
		}

		/**
		 * Prepare Control Configs for Javascript.
		 *
		 * @since 3.0.0
		 * @param array $config configs.
		 */
		public function prepare_javascript_control_configs( $config ) {

			// Remove type from configuration.
			unset( $config['type'] );
			$name = aaa_chinese_get_prop( $config, 'name' );

			$ignore_controls = array(
				'ast-settings-group',
				'ast-multiselect-checkbox-group',
				'ast-sortable',
				'ast-radio-image',
				'ast-slider',
				'ast-responsive-slider',
				'ast-section-toggle',
			);

			if ( ! isset( $config['control'] ) ) {
				return;
			}

			$sanitize_callback = in_array( $config['control'], $ignore_controls, true ) ? false : aaa_chinese_get_prop( $config, 'sanitize_callback', AAA_Chinese_Customizer_Control_Base::get_sanitize_call( aaa_chinese_get_prop( $config, 'control' ) ) );

			if ( ! $sanitize_callback ) {
				$config = $this->sanitize_control( $config );
			}

			$config['label'] = aaa_chinese_get_prop( $config, 'title' );
			$config['type']  = aaa_chinese_get_prop( $config, 'control' );
			/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			if ( false !== aaa_chinese_get_prop( $config, 'font-type', false ) ) {
				/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
				$config['type'] = aaa_chinese_get_prop( $config, 'font-type', false );
			}

			if ( 'image' === $config['type'] ) {
				$this->prepare_preload_controls( $config );
			}

			if ( isset( $config['active_callback'] ) ) {
				self::$js_configs ['skip_context'] [] = $name;
				$this->prepare_preload_controls( $config );
				return;
			}

			self::$dynamic_options['settings'][ $name ] = array(
				'default'           => aaa_chinese_get_prop( $config, 'default' ),
				'type'              => aaa_chinese_get_prop( $config, 'datastore_type' ),
				'transport'         => aaa_chinese_get_prop( $config, 'transport', 'refresh' ),
				'sanitize_callback' => $sanitize_callback,
			);
			/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			if ( aaa_chinese_get_prop( $config, 'partial', false ) ) {
				self::$dynamic_options['partials'][ $name ] = array(
					'selector'           => aaa_chinese_get_prop( $config['partial'], 'selector' ),
					'render_callback'    => aaa_chinese_get_prop( $config['partial'], 'render_callback' ),
					'containerInclusive' => aaa_chinese_get_prop( $config['partial'], 'container_inclusive' ),
					'fallbackRefresh'    => aaa_chinese_get_prop( $config['partial'], 'fallback_refresh', true ),
				);
			}

			$config['id']       = $name;
			$config['settings'] = array( 'default' => $name );
			$config             = self::bypass_control_configs( $config );

			if ( isset( $config['section'] ) ) {
				self::$js_configs ['controls'] [ $config['section'] ] [] = $config;
			}

			// Keep contextual sub controls aside to process initially in customizer.js.
			if ( isset( $config['contextual_sub_control'] ) ) {
				self::$js_configs ['contextual_sub_controls'] [ $name ] = $config;
			}
		}

		/**
		 * Map and add sanitize callback to JS configs.
		 *
		 * @param array $config js config array.
		 * @return mixed
		 */
		public function sanitize_control( $config ) {

			$control_type = isset( $config['control'] ) ? $config['control'] : '';
			switch ( $control_type ) {
				case 'color':
					$config['sanitize_callback'] = array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_hex_color' );
					break;
				case 'ast-border':
					$config['sanitize_callback'] = array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_border' );
					break;
				case 'ast-html-editor':
					$config['sanitize_callback'] = array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_html' );
					break;
				case 'ast-color':
					$config['sanitize_callback'] = array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_alpha_color' );
					break;
				case 'ast-sortable':
					$config ['sanitize_callback'] = array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_multi_choices' );
					break;
				case 'ast-radio-image':
					$config ['sanitize_callback'] = array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_choices' );
					break;
				case 'ast-link':
					$config ['sanitize_callback'] = array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_link' );
					break;
				case 'ast-customizer-link':
					$config ['sanitize_callback'] = array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_customizer_links' );
					break;
				case 'ast-responsive-slider':
					$config ['sanitize_callback'] = array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_responsive_slider' );
					break;
				case 'ast-logo-svg-icon':
					$config ['sanitize_callback'] = array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_logo_svg_icon' );
					break;
				case 'ast-toggle-control':
				case 'ast-section-toggle':
					$config ['sanitize_callback'] = array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_toggle_control' );
					break;
				default:
					break;
			}

			return $config;
		}

		/**
		 * Add controls for which active_callback is added.
		 *
		 * @since 3.0.0
		 * @param array $config config.
		 */
		public function prepare_preload_controls( $config ) {

			global $wp_customize;

			$instance = AAA_Chinese_Customizer_Control_Base::get_control_instance( aaa_chinese_get_prop( $config, 'control' ) );

			// Forwarding to the DOM as default control.
			if ( 'title_tagline' !== $config['section'] ) {
				self::$js_configs ['wp_defaults'][ aaa_chinese_get_prop( $config, 'name' ) ] = $config['section'];
				$config['section'] = 'title_tagline';
			}

			$wp_customize->add_setting(
				aaa_chinese_get_prop( $config, 'name' ),
				array(
					'default'           => aaa_chinese_get_prop( $config, 'default' ),
					'type'              => aaa_chinese_get_prop( $config, 'datastore_type' ),
					'transport'         => aaa_chinese_get_prop( $config, 'transport', 'refresh' ),
					'sanitize_callback' => aaa_chinese_get_prop( $config, 'sanitize_callback', AAA_Chinese_Customizer_Control_Base::get_sanitize_call( aaa_chinese_get_prop( $config, 'control' ) ) ),
				)
			);

			if ( false !== $instance ) {
				$wp_customize->add_control(
					new $instance( $wp_customize, aaa_chinese_get_prop( $config, 'name' ), $config )
				);
			} else {
				$wp_customize->add_control( aaa_chinese_get_prop( $config, 'name' ), $config );
			}
		}

		/**
		 * Prepare Group configs to visible sub-controls.
		 *
		 * @since 3.0.0
		 * @param object $wp_customize customizer object.
		 */
		public function prepare_group_configs( $wp_customize ) {

			if ( $wp_customize->selective_refresh->is_render_partials_request() ) {
				return;
			}

			$configurations = $this->get_customizer_configurations();
			$defaults       = $this->get_aaa_chinese_customizer_configuration_defaults();

			foreach ( $configurations as $configuration ) {
				$config = wp_parse_args( $configuration, $defaults );
				if ( 'sub-control' === $config['type'] ) {
					unset( $config['type'] );
					$parent = aaa_chinese_get_prop( $config, 'parent' );
					$tab    = aaa_chinese_get_prop( $config, 'tab' );

					if ( empty( self::$group_configs[ $parent ] ) ) {
						self::$group_configs[ $parent ] = array();
					}

					if ( array_key_exists( 'tab', $config ) ) {
						self::$group_configs[ $parent ]['tabs'][ $tab ][] = $config;
					} else {
						self::$group_configs[ $parent ][] = $config;
					}
				}
			}
		}

		/**
		 * Prepare context.
		 *
		 * @return mixed|void
		 */
		public static function get_contexts() {

			self::set_default_context();
			// Return contexts.
			return apply_filters( 'aaa_chinese_customizer_context', self::$contexts );
		}

		/**
		 * Prepare choices.
		 *
		 * @return mixed|void
		 */
		public static function get_choices() {
			// Return contexts.
			return apply_filters( 'aaa_chinese_customizer_choices', self::$choices );
		}

		/**
		 * Prepare javascript configs.
		 *
		 * @return mixed|void
		 */
		public static function get_js_configs() {

			// Return contexts.
			return apply_filters( 'aaa_chinese_javascript_configurations', self::$js_configs );
		}

		/**
		 * Prepare tabbed sections.
		 *
		 * @return mixed|void
		 */
		public static function get_tabbed_sections() {

			self::prepare_tabbed_sections();
			// Return contexts.
			return apply_filters( 'aaa_chinese_customizer_tabbed_sections', self::$tabbed_sections );
		}

		/**
		 * Prepare default values for the control.
		 *
		 * @return array
		 */
		private function get_control_defaults() {

			$defaults         = array();
			$default_values   = AAA_Chinese_Theme_Options::defaults();
			$default_controls = array_merge( self::$js_configs['controls'], self::$js_configs['sub_controls'] );

			foreach ( $default_controls as $section_controls ) {
				foreach ( $section_controls as $control ) {
					$control_id = aaa_chinese_get_prop( $control, 'name' );
					if ( 'ast-responsive-spacing' === $control['control'] ) {
							$defaults[ $control_id ] = array(
								'desktop'      => array(
									'top'    => '',
									'right'  => '',
									'bottom' => '',
									'left'   => '',
								),
								'tablet'       => array(
									'top'    => '',
									'right'  => '',
									'bottom' => '',
									'left'   => '',
								),
								'mobile'       => array(
									'top'    => '',
									'right'  => '',
									'bottom' => '',
									'left'   => '',
								),
								'desktop-unit' => 'px',
								'tablet-unit'  => 'px',
								'mobile-unit'  => 'px',
							);
					} else {
							$defaults[ $control_id ] = $this->get_default_value( $control_id, $default_values );
					}
				}
			}

			return $defaults;
		}

		/**
		 * Add customizer script.
		 *
		 * @since 3.0.0
		 */
		public function enqueue_customizer_scripts() {
			// Localize variables for Dev mode > Customizer JS.
			wp_localize_script(
				'astra-custom-control-script',
				'AAA ChineseBuilderCustomizerData',
				array(
					'contexts'                => self::get_contexts(),
					'dynamic_setting_options' => self::$dynamic_options['settings'],
					'choices'                 => self::get_choices(),
					'js_configs'              => self::get_js_configs(),
					'tabbed_sections'         => self::get_tabbed_sections(),
					'component_limit'         => AAA_Chinese_Builder_Helper::$component_limit,
					'is_site_rtl'             => is_rtl(),
					'defaults'                => $this->get_control_defaults(),
					'isWP_5_9'                => aaa_chinese_wp_version_compare( '5.8.99', '>=' ),
					'googleFonts'             => array(),
					'variantLabels'           => AAA_Chinese_Font_Families::font_variant_labels(),
					'upgradeUrl'              => array(
						'default'        => aaa_chinese_get_upgrade_url( 'customizer' ),
						'global'         => aaa_chinese_get_pro_url( '/pricing/', 'free-theme', 'customizer', 'global' ),
						'header-builder' => aaa_chinese_get_pro_url( '/pricing/', 'free-theme', 'customizer', 'header-builder' ),
						'footer-builder' => aaa_chinese_get_pro_url( '/pricing/', 'free-theme', 'customizer', 'footer-builder' ),
						'sidebar'        => aaa_chinese_get_pro_url( '/pricing/', 'free-theme', 'customizer', 'sidebar' ),
						'woocommerce'    => aaa_chinese_get_pro_url( '/pricing/', 'free-theme', 'customizer', 'woocommerce' ),
						'blog-single'    => aaa_chinese_get_pro_url( '/pricing/', 'free-theme', 'customizer', 'blog-single' ),
						'blog-archive'   => aaa_chinese_get_pro_url( '/pricing/', 'free-theme', 'customizer', 'blog-archive' ),
						'hfb-pro-widget' => aaa_chinese_get_pro_url( '/pricing/', 'free-theme', 'astra-header-footer', 'unlock-pro-widget' ),
					),
					/** @psalm-suppress RedundantCondition */
					'is_woo_market_zip'       => ! AAA_THEME_ORG_VERSION,
					'pro_active'              => defined( 'AAA_EXT_VER' ),
				/** @psalm-suppress RedundantCondition */
				)
			);

			if ( is_rtl() ) {
				$builder_customizer_css_file = 'ast-builder-customizer-rtl';
				$font_icon_picker_css_file   = 'font-icon-picker-rtl';
			} else {
				$builder_customizer_css_file = 'ast-builder-customizer';
				$font_icon_picker_css_file   = 'font-icon-picker';
			}

			// Enqueue Builder CSS.
			wp_enqueue_style(
				'ahfb-customizer-style',
				AAA_THEME_URI . 'inc/assets/css/' . $builder_customizer_css_file . '.css',
				array( 'wp-components' ),
				AAA_THEME_VERSION
			);

			wp_enqueue_style(
				'ahfb-customizer-color-picker-style',
				AAA_THEME_URI . 'inc/assets/css/' . $font_icon_picker_css_file . '.css',
				array( 'wp-components' ),
				AAA_THEME_VERSION
			);

			$dir_name    = SCRIPT_DEBUG ? 'unminified' : 'minified';
			$file_prefix = SCRIPT_DEBUG ? '' : '.min';
			wp_enqueue_script(
				'astra-google-fonts-loader',
				AAA_THEME_URI . 'assets/js/' . $dir_name . '/customizer-google-fonts' . $file_prefix . '.js',
				array( 'jquery', 'customize-controls' ),
				AAA_THEME_VERSION,
				true
			);

			wp_localize_script(
				'astra-google-fonts-loader',
				'astraCustomizer',
				array(
					'customizer_nonce' => wp_create_nonce( 'aaa_chinese_customizer_nonce' ),
				)
			);
		}

		/**
		 * Check if string is start with a string provided.
		 *
		 * @param string $string main string.
		 * @param string $start_string string to search.
		 * @since 2.0.0
		 * @return bool.
		 */
		public function starts_with( $string, $start_string ) {
			$len = strlen( $start_string );
			return substr( $string, 0, $len ) === $start_string;
		}

		/**
		 * Filter and return Customizer Configurations.
		 *
		 * @since 1.4.3
		 * @return Array Customizer Configurations for registering Sections/Panels/Controls.
		 */
		private function get_customizer_configurations() {

			global  $wp_customize;

			if ( ! is_null( self::$configuration ) ) {
				return self::$configuration;
			}

			self::$configuration = apply_filters( 'aaa_chinese_customizer_configurations', array(), $wp_customize );
			return self::$configuration;
		}

		/**
		 * Return default values for the Customize Configurations.
		 *
		 * @since 1.4.3
		 * @return Array default values for the Customizer Configurations.
		 */
		private function get_aaa_chinese_customizer_configuration_defaults() {
			return apply_filters(
				'aaa_chinese_customizer_configuration_defaults',
				array(
					'priority'             => null,
					'title'                => null,
					'label'                => null,
					'name'                 => null,
					'type'                 => null,
					'description'          => null,
					'capability'           => null,
					'datastore_type'       => 'option', // theme_mod or option. Default option.
					'settings'             => null,
					'active_callback'      => null,
					'sanitize_callback'    => null,
					'sanitize_js_callback' => null,
					'theme_supports'       => null,
					'transport'            => null,
					'default'              => null,
					'selector'             => null,
					'ast_fields'           => array(),
				)
			);
		}

		/**
		 * Include Customizer Configuration files.
		 *
		 * @since 1.4.3
		 * @return void
		 */
		public function include_configurations() {
			// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require AAA_THEME_DIR . 'inc/customizer/configurations/class-aaa-chinese-customizer-config-base.php';

			/**
			 * Register Sections & Panels
			 */
			require AAA_THEME_DIR . 'inc/customizer/class-aaa-chinese-customizer-register-sections-panels.php';
			require AAA_THEME_DIR . 'inc/customizer/configurations/buttons/class-aaa-chinese-customizer-button-configs.php';
			require AAA_THEME_DIR . 'inc/customizer/configurations/layout/class-aaa-chinese-site-layout-configs.php';
			require AAA_THEME_DIR . 'inc/customizer/configurations/layout/class-aaa-chinese-site-identity-configs.php';
			require AAA_THEME_DIR . 'inc/customizer/configurations/layout/class-aaa-chinese-blog-layout-configs.php';
			require AAA_THEME_DIR . 'inc/customizer/configurations/layout/class-aaa-chinese-blog-single-layout-configs.php';
			require AAA_THEME_DIR . 'inc/customizer/configurations/layout/class-aaa-chinese-sidebar-layout-configs.php';
			require AAA_THEME_DIR . 'inc/customizer/configurations/layout/class-aaa-chinese-site-container-layout-configs.php';
			require AAA_THEME_DIR . 'inc/customizer/configurations/colors-background/class-aaa-chinese-body-colors-configs.php';
			require AAA_THEME_DIR . 'inc/customizer/configurations/typography/class-aaa-chinese-archive-typo-configs.php';
			require AAA_THEME_DIR . 'inc/customizer/configurations/typography/class-aaa-chinese-body-typo-configs.php';
			require AAA_THEME_DIR . 'inc/customizer/configurations/block-editor/class-aaa-chinese-block-editor-configs.php';
			require AAA_THEME_DIR . 'inc/customizer/configurations/comments/class-aaa-chinese-comments-configs.php';
			require AAA_THEME_DIR . 'inc/customizer/configurations/typography/class-aaa-chinese-headings-typo-configs.php';
			require AAA_THEME_DIR . 'inc/customizer/configurations/typography/class-aaa-chinese-single-typo-configs.php';
			require AAA_THEME_DIR . 'inc/customizer/configurations/typography/class-aaa-chinese-global-typo-configs.php';
			require AAA_THEME_DIR . 'inc/customizer/configurations/global-misc/class-aaa-chinese-global-misc-configs.php';
			require AAA_THEME_DIR . 'inc/customizer/configurations/accessibility/class-aaa-chinese-accessibility-configs.php';

			if ( aaa_chinese_existing_header_footer_configs() ) {
				require AAA_THEME_DIR . 'inc/customizer/configurations/buttons/class-aaa-chinese-existing-button-configs.php';
				require AAA_THEME_DIR . 'inc/customizer/configurations/layout/class-aaa-chinese-header-layout-configs.php';
				require AAA_THEME_DIR . 'inc/customizer/configurations/layout/class-aaa-chinese-footer-layout-configs.php';
				require AAA_THEME_DIR . 'inc/customizer/configurations/colors-background/class-aaa-chinese-advanced-footer-colors-configs.php';
				require AAA_THEME_DIR . 'inc/customizer/configurations/colors-background/class-aaa-chinese-footer-colors-configs.php';
			}
			// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		}

		/**
		 * Register custom section and panel.
		 *
		 * @since 1.0.0
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		public function customize_register_panel( $wp_customize ) {

			/**
			 * Register Extended Panel
			 */
			$wp_customize->register_panel_type( 'AAA_Chinese_WP_Customize_Panel' );
			$wp_customize->register_section_type( 'AAA_Chinese_WP_Customize_Section' );
			$wp_customize->register_section_type( 'AAA_Chinese_WP_Customize_Separator' );

			$wp_customize->selective_refresh->add_partial(
				'site_icon',
				array(
					'selector'            => '.ast-sg-site-icon-wrap',
					'container_inclusive' => true,
					'render_callback'     => array( $this, 'site_icon_update' ),
				)
			);

			if ( ! defined( 'AAA_EXT_VER' ) ) {
				$wp_customize->register_section_type( 'AAA_Chinese_Pro_Customizer' );
			}

			// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require AAA_THEME_DIR . 'inc/customizer/extend-customizer/class-aaa-chinese-wp-customize-panel.php';
			require AAA_THEME_DIR . 'inc/customizer/extend-customizer/class-aaa-chinese-wp-customize-section.php';
			require AAA_THEME_DIR . 'inc/customizer/extend-customizer/class-aaa-chinese-wp-customize-separator.php';
			require AAA_THEME_DIR . 'inc/customizer/customizer-controls.php';
			// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound

			/**
			 * Add Controls
			 */

			AAA_Chinese_Customizer_Control_Base::add_control(
				'image',
				array(
					'callback'          => 'WP_Customize_Image_Control',
					'sanitize_callback' => 'esc_url_raw',
				)
			);

			AAA_Chinese_Customizer_Control_Base::add_control(
				'ast-font',
				array(
					'callback'          => 'AAA_Chinese_Control_Typography',
					'sanitize_callback' => 'sanitize_text_field',
				)
			);

			AAA_Chinese_Customizer_Control_Base::add_control(
				'ast-logo-svg-icon',
				array(
					'callback'          => 'AAA_Chinese_Control_Logo_SVG_Icon',
					'sanitize_callback' => array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_logo_svg_icon' ),
				)
			);

			AAA_Chinese_Customizer_Control_Base::add_control(
				'ast-description',
				array(
					'callback'          => 'AAA_Chinese_Control_Description',
					'sanitize_callback' => '',
				)
			);

			AAA_Chinese_Customizer_Control_Base::add_control(
				'ast-customizer-link',
				array(
					'callback'         => 'AAA_Chinese_Control_Customizer_Link',
					'santize_callback' => array( 'AAA_Chinese_Customizer_Sanitizes', 'sanitize_customizer_links' ),
				)
			);

			AAA_Chinese_Customizer_Control_Base::add_control(
				'ast-description-with-link',
				array(
					'callback'          => 'AAA_Chinese_Control_Description_With_Link',
					'sanitize_callback' => null,
				)
			);

			/**
			 * Helper files
			 */
			// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require AAA_THEME_DIR . 'inc/customizer/class-aaa-chinese-customizer-partials.php';
			require AAA_THEME_DIR . 'inc/customizer/class-aaa-chinese-customizer-callback.php';
			require AAA_THEME_DIR . 'inc/customizer/class-aaa-chinese-customizer-sanitizes.php';
			// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		}

		/**
		 * Render site icon.
		 *
		 * @since 4.8.0
		 */
		public function site_icon_update() {
			$uploaded_icon_url = get_site_icon_url( 32 );
			$site_icon_url     = empty( $uploaded_icon_url ) ? admin_url() . 'images/wordpress-logo.svg' : $uploaded_icon_url;
			?>
				<p class="ast-sg-site-icon-wrap">
					<span class="ast-sg-site-icon-aside-divider"></span>
					<span class="ast-sg-site-icon-inner-wrap">
						<img class="ast-sg-site-icon" alt="<?php esc_attr_e( 'Site Icon', 'astra' ); ?>" src="<?php echo esc_url( $site_icon_url ); ?>" />
						<span class="ast-sg-site-title"> <?php echo esc_html( get_bloginfo( 'name' ) ); ?> </span>
						<span class="ast-sg-site-blogdescription"> <?php echo esc_attr( ! empty( get_bloginfo( 'description' ) ) ? ' - ' . get_bloginfo( 'description' ) : '' ); ?> </span>
					</span>
					<span class="ast-sg-site-icon-aside-divider"></span>
				</p>
			<?php
		}

		/**
		 * Add postMessage support for site title and description for the Theme Customizer.
		 *
		 * @since 1.0.0
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		public function customize_register( $wp_customize ) {

			/**
			 * Override Defaults
			 */
			require AAA_THEME_DIR . 'inc/customizer/override-defaults.php';// phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		}

		/**
		 * Add upgrade link configurations controls.
		 *
		 * @since 1.0.0
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		public function aaa_chinese_pro_upgrade_configurations( $wp_customize ) {

			if ( ! defined( 'AAA_EXT_VER' ) ) {
				require AAA_THEME_DIR . 'inc/customizer/astra-pro/class-aaa-chinese-pro-customizer.php';// phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
				require AAA_THEME_DIR . 'inc/customizer/astra-pro/class-aaa-chinese-pro-upgrade-link-configs.php';// phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			}
		}

		/**
		 * Customizer Controls
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function controls_scripts() {
			$js_prefix  = '.min.js';
			$css_prefix = '.min.css';
			$dir        = 'minified';
			if ( SCRIPT_DEBUG ) {
				$js_prefix = '.js';
				$dir       = 'unminified';
			}

			if ( is_rtl() ) {
				$css_prefix = '.min-rtl.css';
			}

			wp_enqueue_style( 'wp-components' );
			wp_enqueue_script( 'thickbox' );
			wp_enqueue_style( 'thickbox' );

			// Customizer Core.
			wp_enqueue_script( 'astra-customizer-controls-toggle-js', AAA_THEME_URI . 'assets/js/' . $dir . '/customizer-controls-toggle' . $js_prefix, array(), AAA_THEME_VERSION, true );

			wp_enqueue_script( 'astra-customizer-controls-js', AAA_THEME_URI . 'assets/js/' . $dir . '/customizer-controls' . $js_prefix, array( 'astra-customizer-controls-toggle-js' ), AAA_THEME_VERSION, true );

			// Extended Customizer Assets - Panel extended.
			wp_enqueue_style( 'astra-extend-customizer-css', AAA_THEME_URI . 'assets/css/minified/extend-customizer' . $css_prefix, null, AAA_THEME_VERSION );
			wp_enqueue_script( 'astra-extend-customizer-js', AAA_THEME_URI . 'assets/js/' . $dir . '/extend-customizer' . $js_prefix, array(), AAA_THEME_VERSION, true );

			// Customizer Controls.
			wp_enqueue_style( 'astra-customizer-controls-css', AAA_THEME_URI . 'assets/css/minified/customizer-controls' . $css_prefix, null, AAA_THEME_VERSION );

			wp_enqueue_script( 'astra-customizer-style-guide-js', AAA_THEME_URI . 'assets/js/' . $dir . '/customizer-style-guide' . $js_prefix, array( 'jquery', 'astra-customizer-controls-toggle-js' ), AAA_THEME_VERSION, true );
			wp_localize_script(
				'astra-customizer-style-guide-js',
				'astraStyleGuide',
				array(
					'title' => __( 'Style Guide', 'astra' ),
				)
			);

			$string = $this->generate_font_dropdown();

			$template = '<div class="ast-field-settings-modal">
					<ul class="ast-fields-wrap">
					</ul>
			</div>';

			$sortable_subcontrol_template = '<div class="ast-sortable-subfields-wrap">
					<ul class="ast-fields-wrap">
					</ul>
			</div>';

			wp_localize_script(
				'astra-customizer-controls-toggle-js',
				'astra',
				apply_filters(
					'aaa_chinese_theme_customizer_js_localize',
					array(
						'customizer' => array(
							'settings'            => array(
								'sidebars'     => array(
									'single'  => array(
										'single-post-sidebar-layout',
										'single-page-sidebar-layout',
									),
									'archive' => array(
										'archive-post-sidebar-layout',
									),
								),
								'container'    => array(
									'single'  => array(
										'single-post-content-layout',
										'single-page-content-layout',
									),
									'archive' => array(
										'archive-post-content-layout',
									),
								),
								'google_fonts' => $string,
							),
							'group_modal_tmpl'    => $template,
							'sortable_modal_tmpl' => $sortable_subcontrol_template,
							'is_pro'              => defined( 'AAA_EXT_VER' ),
							'show_upgrade_notice' => aaa_chinese_showcase_upgrade_notices() ? true : false,
							'upgrade_link'        => esc_url( aaa_chinese_get_upgrade_url( 'pricing' ) ),
							'is_block_widget'     => aaa_chinese_has_widgets_block_editor(),
						),
						'theme'      => array(
							'option' => AAA_THEME_SETTINGS,
						),
					)
				)
			);
		}

		/**
		 * Render customizer style guide shortcut pencil.
		 *
		 * @param string $type Section|Control.
		 * @param string $name Section name|Control name.
		 * @param string $context General|Design name.
		 * @param string $extras if any other parameter to pass.
		 *
		 * @return string Trigger for style guide shortcut.
		 * @since 4.8.0
		 */
		public function get_style_guide_shortcut_trigger( $type, $name, $context = 'general', $extras = '' ) {
			if ( 'control' === $type ) {
				$name = 'astra-color-palettes' === $name ? 'astra-color-palettes' : esc_attr( AAA_THEME_SETTINGS ) . $name;
			}
			return '<span class="ast-quick-tour-item" data-type="' . $type . '" data-name="' . $name . '" data-context="' . $context . '" ' . $extras . '> <span class="ast-sg-customizer-shortcut"> <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M0.5 6C0.5 2.96243 2.96243 0.5 6 0.5H18C21.0376 0.5 23.5 2.96243 23.5 6V18C23.5 21.0376 21.0376 23.5 18 23.5H6C2.96243 23.5 0.5 21.0376 0.5 18V6Z" fill="white" fill-opacity="0.8"/> <path d="M0.5 6C0.5 2.96243 2.96243 0.5 6 0.5H18C21.0376 0.5 23.5 2.96243 23.5 6V18C23.5 21.0376 21.0376 23.5 18 23.5H6C2.96243 23.5 0.5 21.0376 0.5 18V6Z" stroke="#E2E8F0"/> <g clip-path="url(#clip0_8460_9362)"> <path d="M14.5 7.50081C14.6273 7.35032 14.7849 7.22784 14.9625 7.14115C15.1402 7.05446 15.334 7.00547 15.5318 6.99731C15.7296 6.98915 15.9269 7.022 16.1112 7.09375C16.2955 7.1655 16.4627 7.27459 16.6022 7.41407C16.7416 7.55354 16.8503 7.72034 16.9213 7.90383C16.9922 8.08732 17.0239 8.28347 17.0143 8.4798C17.0047 8.67612 16.954 8.8683 16.8654 9.04409C16.7769 9.21988 16.6524 9.37542 16.5 9.50081L9.75 16.2508L7 17.0008L7.75 14.2508L14.5 7.50081Z" stroke="#020617" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> <path d="M13.5 8.5L15.5 10.5" stroke="#020617" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </g> <defs> <clipPath id="clip0_8460_9362"> <rect width="12" height="12" fill="white" transform="translate(6 6)"/> </clipPath> </defs> </svg> </span> </span>';
		}

		/**
		 * Get formatted font settings for headings.
		 *
		 * @param string $tag HTML Tag.
		 * @return string formatted string with font, font-size, line-height.
		 *
		 * @since 4.8.0
		 */
		public function get_formatted_font_style( $tag ) {
			$dataset   = array();
			$dataset[] = '<span class="ast-sg-font-family">' . aaa_chinese_get_option( 'font-family-' . $tag ) . '</span>';

			$font_size = aaa_chinese_get_option( 'font-size-' . $tag );
			$desktop   = aaa_chinese_get_css_value( $font_size['desktop'], $font_size['desktop-unit'] );
			$tablet    = aaa_chinese_get_css_value( $font_size['tablet'], $font_size['tablet-unit'] );
			$mobile    = aaa_chinese_get_css_value( $font_size['mobile'], $font_size['mobile-unit'] );

			$tablet = empty( $tablet ) ? $desktop : $tablet;
			$mobile = empty( $mobile ) ? $tablet : $mobile;

			$dataset[] = '<span class="ast-sg-font-size"> <span class="ast-sg-desktop">' . $desktop . '</span> <span class="ast-sg-tablet">' . $tablet . '</span> <span class="ast-sg-mobile">' . $mobile . '</span> </span>';

			$dataset[] = '<span class="ast-sg-line-height">' . aaa_chinese_get_font_extras( aaa_chinese_get_option( 'font-extras-' . $tag ), 'line-height', 'line-height-unit' ) . '</span>';

			$formatted_data = join( ' / ', $dataset );
			return '<p class="ast-sg-field-title ast-sg-typo-field" data-for="' . esc_attr( $tag ) . '"> ' . $formatted_data . ' </p>';
		}

		/**
		 * Customizer Easy Navigation Tour Markup.
		 *
		 * @return mixed HTML Markup.
		 * @since 4.8.0
		 */
		public function render_style_guide_markup() {
			$settings = apply_filters(
				'aaa_chinese_quick_customizer_navigation_setup',
				array(
					'colors' => array(
						'color-0' => array(
							'title' => __( 'Brand', 'astra' ),
							'code'  => 'var(--ast-global-color-0)',
						),
						'color-1' => array(
							'title' => __( 'Alt Brand', 'astra' ),
							'code'  => 'var(--ast-global-color-1)',
						),
						'color-2' => array(
							'title' => __( 'Heading', 'astra' ),
							'code'  => 'var(--ast-global-color-2)',
						),
						'color-3' => array(
							'title' => __( 'Text', 'astra' ),
							'code'  => 'var(--ast-global-color-3)',
						),
						'color-4' => array(
							'title' => __( 'Primary', 'astra' ),
							'code'  => 'var(--ast-global-color-4)',
						),
						'color-5' => array(
							'title' => __( 'Secondary', 'astra' ),
							'code'  => 'var(--ast-global-color-5)',
						),
						'color-6' => array(
							'title' => __( 'Border', 'astra' ),
							'code'  => 'var(--ast-global-color-6)',
						),
						'color-7' => array(
							'title' => __( 'Subtle BG', 'astra' ),
							'code'  => 'var(--ast-global-color-7)',
						),
						'color-8' => array(
							'title' => __( 'Extra', 'astra' ),
							'code'  => 'var(--ast-global-color-8)',
						),
					),
				)
			);

			ob_start();
			?>
				<button class="ast-close-tour" type="button">
					<span class="screen-reader-text"><?php esc_html_e( 'Close', 'astra' ); ?></span>
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><path d="M13 11.8l6.1-6.3-1-1-6.1 6.2-6.1-6.2-1 1 6.1 6.3-6.5 6.7 1 1 6.5-6.6 6.5 6.6 1-1z"></path></svg>
				</button>
				<div class="ast-tour-inner-wrap">
					<div class="ast-quick-tour-body">
						<div class="ast-sg-2-col-grid">
							<div class="ast-styler-card">
								<p class="ast-sg-card-title"> <?php esc_html_e( 'Site Title & Logo', 'astra' ); ?>
								<div class="ast-sg-element-wrap ast-sg-logo-section <?php echo esc_attr( aaa_chinese_get_option( 'logo-title-inline' ) ? 'ast-logo-title-inline' : '' ); ?>">
									<?php echo do_shortcode( $this->get_style_guide_shortcut_trigger( 'section', 'title_tagline' ) ); ?>
									<?php do_action( 'aaa_chinese_site_identity' ); ?>
								</div>
							</div>
							<div class="ast-sg-1-col-grid">
								<div class="ast-styler-card">
									<p class="ast-sg-card-title"> <?php esc_html_e( 'Site Icon', 'astra' ); ?>
									<div class="ast-sg-element-wrap">
										<?php echo do_shortcode( $this->get_style_guide_shortcut_trigger( 'section', 'astra-site-identity' ) ); ?>
										<?php do_action( 'aaa_chinese_style_guide_site_icon' ); ?>
									</div>
								</div>

								<div class="ast-styler-card">
									<p class="ast-sg-card-title"> <?php esc_html_e( 'Buttons', 'astra' ); ?>
									<div class="ast-sg-button-element-wrap">
										<div class="ast-sg-element-wrap">
											<?php echo do_shortcode( $this->get_style_guide_shortcut_trigger( 'control', '[button-preset-style]' ) ); ?>
											<button class="ast-button"> <?php esc_html_e( 'Primary', 'astra' ); ?> </button>
										</div>
										<div class="ast-sg-element-wrap">
											<?php echo do_shortcode( $this->get_style_guide_shortcut_trigger( 'control', '[secondary-button-preset-style]', 'design' ) ); ?>
											<button class="ast-outline-button"> <?php esc_html_e( 'Secondary', 'astra' ); ?> </button>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="ast-sg-colors-section ast-styler-card">
							<p class="ast-sg-card-title"> <?php esc_html_e( 'Colors', 'astra' ); ?>
							<div class="ast-sg-colors-section-wrap">
								<?php
								foreach ( $settings['colors'] as $key => $data_attrs ) {
									?>
									<div class="ast-sg-color-item-wrap">
										<?php echo do_shortcode( $this->get_style_guide_shortcut_trigger( 'control', 'astra-color-palettes', 'general', 'data-reference="ast-' . esc_attr( $key ) . '"' ) ); ?>
										<span class="ast-sg-color-picker" style="background:<?php echo esc_attr( $data_attrs['code'] ); ?>"> </span>
										<span class="ast-sg-field-title"> <?php echo esc_html( $data_attrs['title'] ); ?>
									</div>
									<?php
								}
								?>
									</div>
								</div>

						<div class="ast-sg-content-section-wrap ast-styler-card">
							<p class="ast-sg-card-title"> <?php esc_html_e( 'Typography', 'astra' ); ?>
							<div class="ast-sg-content-inner-wrap">
								<div class="ast-sg-heading-section">
									<?php echo do_shortcode( $this->get_style_guide_shortcut_trigger( 'control', '[ast-headings-font-settings]', 'general', 'data-reference="ast-toggle-desc-wrap"' ) ); ?>
									<h1> <?php esc_html_e( 'Headings', 'astra' ); ?> </h1>
									<h2 class="sub-heading"> A a B b C c D d E e F f G g H h I i J j K k L l M m N n O o P p Q q R r S s T t U u V v W w X x Y y Z z </h2>
								</div>
								<div class="ast-sg-content-section">
									<?php echo do_shortcode( $this->get_style_guide_shortcut_trigger( 'control', '[ast-body-font-settings]', 'general', 'data-reference="ast-toggle-desc-wrap"' ) ); ?>
									<p> <?php esc_html_e( 'Here\'s how the body text will look like on your website. You can customize the typography to match your brand personality. Whether you aim for a modern and sleek appearance or a more traditional and elegant feel, the right typography sets the tone for your content.', 'astra' ); ?> </p>
								</div>

								<div class="ast-sg-heading-more-section">
									<div class="ast-sg-heading-card">
										<?php echo do_shortcode( $this->get_style_guide_shortcut_trigger( 'control', '[ast-heading-h1-typo]', 'general', 'data-reference="ast-toggle-desc-wrap"' ) ); ?>
										<?php echo wp_kses_post( $this->get_formatted_font_style( 'h1' ) ); ?>
										<h1 class="ast-sg-heading"> <?php esc_html_e( 'Heading 1', 'astra' ); ?> </h1>
									</div>

									<div class="ast-sg-heading-card">
										<?php echo do_shortcode( $this->get_style_guide_shortcut_trigger( 'control', '[ast-heading-h2-typo]', 'general', 'data-reference="ast-toggle-desc-wrap"' ) ); ?>
										<?php echo wp_kses_post( $this->get_formatted_font_style( 'h2' ) ); ?>
										<h2 class="ast-sg-heading"> <?php esc_html_e( 'Heading 2', 'astra' ); ?> </h2>
									</div>

									<div class="ast-sg-heading-card">
										<?php echo do_shortcode( $this->get_style_guide_shortcut_trigger( 'control', '[ast-heading-h3-typo]', 'general', 'data-reference="ast-toggle-desc-wrap"' ) ); ?>
										<?php echo wp_kses_post( $this->get_formatted_font_style( 'h3' ) ); ?>
										<h3 class="ast-sg-heading"> <?php esc_html_e( 'Heading 3', 'astra' ); ?> </h3>
									</div>

									<div class="ast-sg-heading-card">
										<?php echo do_shortcode( $this->get_style_guide_shortcut_trigger( 'control', '[ast-heading-h4-typo]', 'general', 'data-reference="ast-toggle-desc-wrap"' ) ); ?>
										<?php echo wp_kses_post( $this->get_formatted_font_style( 'h4' ) ); ?>
										<h4 class="ast-sg-heading"> <?php esc_html_e( 'Heading 4', 'astra' ); ?> </h4>
									</div>

									<div class="ast-sg-heading-card">
										<?php echo do_shortcode( $this->get_style_guide_shortcut_trigger( 'control', '[ast-heading-h5-typo]', 'general', 'data-reference="ast-toggle-desc-wrap"' ) ); ?>
										<?php echo wp_kses_post( $this->get_formatted_font_style( 'h5' ) ); ?>
										<h5 class="ast-sg-heading"> <?php esc_html_e( 'Heading 5', 'astra' ); ?> </h5>
									</div>

									<div class="ast-sg-heading-card">
										<?php echo do_shortcode( $this->get_style_guide_shortcut_trigger( 'control', '[ast-heading-h6-typo]', 'general', 'data-reference="ast-toggle-desc-wrap"' ) ); ?>
										<?php echo wp_kses_post( $this->get_formatted_font_style( 'h6' ) ); ?>
										<h6 class="ast-sg-heading"> <?php esc_html_e( 'Heading 6', 'astra' ); ?> </h6>
									</div>
								</div>

								<div class="ast-sg-content-section">
									<?php echo do_shortcode( $this->get_style_guide_shortcut_trigger( 'control', '[ast-body-font-settings]', 'general', 'data-reference="ast-toggle-desc-wrap"' ) ); ?>
									<p> <?php esc_html_e( 'Explore different font families, sizes, weights, and styles to find the perfect combination that encapsulates the essence of your brand. With each adjustment, see how your message transforms, becoming a powerful reflection of your identity and vision.', 'astra' ); ?> </p>

									<p class="ast-sg-card-title"> <?php esc_html_e( 'Quote', 'astra' ); ?>
									<blockquote>
										<p> <?php esc_html_e( 'The future will belongs to those who believe in the beauty of their dreams.', 'astra' ); ?> </p> <br/>
										<footer> Elanor Rosevelt </footer>
									</blockquote>

									<p class="ast-sg-content-divider"></p>

									<p class="ast-sg-card-title"> <?php esc_html_e( 'Unordered List', 'astra' ); ?>
									<ul>
										<li> <?php esc_html_e( 'List Item 1', 'astra' ); ?> </li>
										<li> <?php esc_html_e( 'List Item 2', 'astra' ); ?> </li>
										<li> <?php esc_html_e( 'List Item 3', 'astra' ); ?> </li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php
			return ob_get_clean();
		}

		/**
		 * Easy navigation template.
		 *
		 * @since 4.8.0
		 * @return void
		 */
		public function style_guide_template() {
			if ( ! is_customize_preview() ) {
				return;
			}

			$js_prefix = '.min.js';
			$dir       = 'minified';
			if ( SCRIPT_DEBUG ) {
				$js_prefix = '.js';
				$dir       = 'unminified';
			}

			wp_enqueue_script( 'astra-style-guide-previewer-js', AAA_THEME_URI . 'assets/js/' . $dir . '/style-guide-previewer' . $js_prefix, array( 'jquery' ), AAA_THEME_VERSION, true );

			?>
				<div class="ast-style-guide-wrapper">
					<?php echo do_shortcode( $this->render_style_guide_markup() ); ?>
				</div>
			<?php
		}

		/**
		 * Generates HTML for font dropdown.
		 *
		 * @return string
		 */
		public function generate_font_dropdown() {

			ob_start();
			?>

			<option value="inherit"><?php esc_html_e( 'Default System Font', 'astra' ); ?></option>

			<optgroup label="<?php echo esc_attr_e( 'Other System Fonts', 'astra' ); ?>">
				<?php
				$system_fonts = AAA_Chinese_Font_Families::get_system_fonts();
				$google_fonts = AAA_Chinese_Font_Families::get_google_fonts();

				foreach ( $system_fonts as $name => $variants ) {
					?>
					<option value="<?php echo esc_attr( $name ); ?>" ><?php echo esc_html( $name ); ?></option>
					<?php
				}
				?>
			</optgroup>

			<?php
			/**
			 * Filter to add custom font list into customizer.
			 */
			do_action( 'aaa_chinese_customizer_font_list', '' );
			?>

			<optgroup label="Google">
				<?php
				foreach ( $google_fonts as $name => $single_font ) {
					$category = aaa_chinese_get_prop( $single_font, '1' );
					?>
					<option value="<?php echo "'" . esc_attr( $name ) . "', " . esc_attr( $category ); ?>"><?php echo esc_html( $name ); ?></option>
					<?php
				}
				?>
			</optgroup>

			<?php
			return ob_get_clean();
		}

		/**
		 * Customizer Preview Init
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function preview_init() {

			// Update variables.
			AAA_Chinese_Theme_Options::refresh();

			$js_prefix = '.min.js';
			$dir       = 'minified';
			/** @psalm-suppress RedundantCondition */
			if ( SCRIPT_DEBUG ) {
				$js_prefix = '.js';
				$dir       = 'unminified';
			}

			wp_enqueue_script( 'astra-customizer-preview-js', AAA_THEME_URI . 'assets/js/' . $dir . '/customizer-preview' . $js_prefix, array( 'customize-preview' ), AAA_THEME_VERSION, null );

			// Get current container layout.
			$content_layout   = aaa_chinese_get_content_layout();
			$is_boxed         = aaa_chinese_is_content_style_boxed();
			$is_sidebar_boxed = aaa_chinese_is_sidebar_style_boxed();
			$content_layout   = aaa_chinese_apply_boxed_layouts( $content_layout, $is_boxed, $is_sidebar_boxed );

			$localize_array = array(
				'headerBreakpoint'                     => aaa_chinese_header_break_point(),
				'includeAnchorsInHeadindsCss'          => AAA_Chinese_Dynamic_CSS::anchors_in_css_selectors_heading(),
				'googleFonts'                          => AAA_Chinese_Font_Families::get_google_fonts(),
				'page_builder_button_style_css'        => AAA_Chinese_Dynamic_CSS::page_builder_button_style_css(),
				'elementor_default_color_font_setting' => AAA_Chinese_Dynamic_CSS::elementor_default_color_font_setting(),
				'dynamic_partial_options'              => self::$dynamic_options['partials'],
				'gb_outline_buttons_patterns_support'  => AAA_Chinese_Dynamic_CSS::gutenberg_core_patterns_compat(),
				'font_weights_widget_title_support'    => AAA_Chinese_Dynamic_CSS::support_font_css_to_widget_and_in_editor(),
				'is_content_bg_option_to_load'         => aaa_chinese_has_gcp_typo_preset_compatibility(),
				'content_layout'                       => $content_layout,
				'site_layout'                          => aaa_chinese_get_option( 'site-layout' ),
				'has_block_editor_support'             => AAA_Chinese_Dynamic_CSS::is_block_editor_support_enabled(),
				'updated_gb_outline_button_patterns'   => aaa_chinese_button_default_padding_updated(),
				'apply_content_bg_fullwidth_layouts'   => aaa_chinese_get_option( 'apply-content-background-fullwidth-layouts', true ),
				'aaa_chinese_woo_btn_global_compatibility'   => is_callable( 'AAA_Chinese_Dynamic_CSS::aaa_chinese_woo_support_global_settings' ) ? AAA_Chinese_Dynamic_CSS::aaa_chinese_woo_support_global_settings() : false,
				'v4_2_2_core_form_btns_styling'        => true === AAA_Chinese_Dynamic_CSS::aaa_chinese_core_form_btns_styling() ? ', #comments .submit, .search .search-submit' : '',
				'isLifterLMS'                          => class_exists( 'LifterLMS' ),
				'improved_button_selector'             => AAA_Chinese_Dynamic_CSS::aaa_chinese_4_6_4_compatibility() ? ', .ast-single-post .entry-content .wp-block-button .wp-block-button__link, .ast-single-post .entry-content .wp-block-search .wp-block-search__button, body .entry-content .wp-block-file .wp-block-file__button' : '',
				'tablet_breakpoint'                    => aaa_chinese_get_tablet_breakpoint(),
				'mobile_breakpoint'                    => aaa_chinese_get_mobile_breakpoint(),
				'is_dark_palette'                      => AAA_Chinese_Global_Palette::is_dark_palette(),
			);

			wp_localize_script( 'astra-customizer-preview-js', 'astraCustomizer', $localize_array );
		}

		/**
		 * Called by the customize_save_after action to refresh
		 * the cached CSS when Customizer settings are saved.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function customize_save() {

			// Update variables.
			AAA_Chinese_Theme_Options::refresh();

			if ( apply_filters( 'aaa_chinese_resize_logo', true ) ) {

				/* Generate Header Logo */
				$custom_logo_id = get_theme_mod( 'custom_logo' );

				add_filter( 'intermediate_image_sizes_advanced', 'AAA_Chinese_Customizer::logo_image_sizes', 10, 2 );
				self::generate_logo_by_width( $custom_logo_id );
				remove_filter( 'intermediate_image_sizes_advanced', 'AAA_Chinese_Customizer::logo_image_sizes', 10 );

			} else {
				// Regenerate the logo without custom image sizes.
				$custom_logo_id = get_theme_mod( 'custom_logo' );
				self::generate_logo_by_width( $custom_logo_id );
			}

			do_action( 'aaa_chinese_customizer_save' );
		}

		/**
		 * Add logo image sizes in filter.
		 *
		 * @since 1.0.0
		 * @param array $sizes Sizes.
		 * @param array $metadata attachment data.
		 *
		 * @return array
		 */
		public static function logo_image_sizes( $sizes, $metadata ) {

			$logo_width = aaa_chinese_get_option( 'ast-header-responsive-logo-width' );

			if ( is_array( $sizes ) && '' != $logo_width['desktop'] ) {
				$max_value              = max( $logo_width );
				$sizes['ast-logo-size'] = array(
					'width'  => (int) $max_value,
					'height' => 0,
					'crop'   => false,
				);
			}

			return $sizes;
		}

		/**
		 * Generate logo image by its width.
		 *
		 * @since 1.0.0
		 * @param int $custom_logo_id Logo id.
		 */
		public static function generate_logo_by_width( $custom_logo_id ) {
			if ( $custom_logo_id ) {

				$image = get_post( $custom_logo_id );

				if ( $image ) {
					$fullsizepath = get_attached_file( $image->ID );
					/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
					if ( false !== $fullsizepath || file_exists( $fullsizepath ) ) {

						if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
							require_once ABSPATH . 'wp-admin/includes/image.php';// phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
						}

						$metadata = wp_generate_attachment_metadata( $image->ID, $fullsizepath );

						/** @psalm-suppress RedundantConditionGivenDocblockType */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
						if ( ! is_wp_error( $metadata ) && ! empty( $metadata ) ) {
							/** @psalm-suppress RedundantConditionGivenDocblockType */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
							wp_update_attachment_metadata( $image->ID, $metadata );
						}
					}
				}
			}
		}

		/**
		 * Customizer Preview icon CSS
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function preview_styles() {
			if ( is_customize_preview() ) {
				$rtl = is_rtl() ? '-rtl' : '';

				wp_enqueue_style( 'astra-style-guide-css', AAA_THEME_URI . 'assets/css/minified/style-guide' . $rtl . '.min.css', array(), AAA_THEME_VERSION );

				wp_enqueue_style( 'astra-style-guide-font', 'https://fonts.googleapis.com/css2?family=Inter:wght@200;400;500&display=swap', array(), AAA_THEME_VERSION ); // Styles.

				echo '<style class="astra-custom-shortcut-edit-icons">
					.customize-partial-edit-shortcut-aaa-chinese-settings-footer-adv {
						position: relative;
					    top: -1em;
					    left: -1.8em;
					}
					.customize-partial-edit-shortcut-aaa-chinese-settings-breadcrumb-position .customize-partial-edit-shortcut-button{
						display: none;
					}
					.ast-small-footer-section-1 .ast-footer-widget-1-area .customize-partial-edit-shortcut,
					.ast-small-footer-section-2 .ast-footer-widget-2-area .customize-partial-edit-shortcut {
						position: absolute;
					    left: 47%;
					}
					.ast-small-footer-section-1.ast-small-footer-section-equally .ast-footer-widget-1-area .customize-partial-edit-shortcut,
					.ast-small-footer-section-2.ast-small-footer-section-equally .ast-footer-widget-2-area .customize-partial-edit-shortcut {
						position: absolute;
					    left: 42%;
					}
					.ast-small-footer-section-1.ast-small-footer-section-equally .ast-footer-widget-1-area .ast-no-widget-row .customize-partial-edit-shortcut-aaa-chinese-settings-footer-sml-section-1 {
						position: absolute;
					    left: 1em;
					}
					.ast-small-footer-section-2.ast-small-footer-section-equally .ast-footer-widget-2-area .ast-no-widget-row .customize-partial-edit-shortcut-aaa-chinese-settings-footer-sml-section-2 {
						left: 83.5%;
					}
					.ast-small-footer-section-1.ast-small-footer-section-equally .nav-menu .customize-partial-edit-shortcut-aaa-chinese-settings-footer-sml-section-1 {
						position: absolute;
					    left: 1em;
					}
					.ast-small-footer-section-2.ast-small-footer-section-equally .nav-menu .customize-partial-edit-shortcut-aaa-chinese-settings-footer-sml-section-2 {
						position: absolute;
					    left: 44.5%;
					}
					.ast-small-footer .ast-container .ast-small-footer-section-1 .footer-primary-navigation > .customize-partial-edit-shortcut,
					.ast-small-footer .ast-container .ast-small-footer-section-2 .footer-primary-navigation > .customize-partial-edit-shortcut{
						display: none;
					}
					.ast-small-footer .customize-partial-edit-shortcut-aaa-chinese-settings-footer-sml-layout {
						    position: absolute;
						    top: 3%;
						    left: 10%;
					}
					.customize-partial-edit-shortcut button:hover {
						border-color: #fff;
					}
					.ast-main-header-bar-alignment .main-header-bar-navigation .customize-partial-edit-shortcut-button {
						display: none;
					}
				</style>';
				echo '<style class="astra-theme-custom-shortcut-edit-icons">
					.ast-replace-site-logo-transparent.ast-theme-transparent-header .customize-partial-edit-shortcut-aaa-chinese-settings-transparent-header-logo,
					.ast-replace-site-logo-transparent.ast-theme-transparent-header .customize-partial-edit-shortcut-aaa-chinese-settings-transparent-header-enable {
					    z-index: 6;
					}
				</style>';
			}
		}
	}
}

/**
 *  Kicking this off by calling 'get_instance()' method
 */
AAA_Chinese_Customizer::get_instance();

/**
 * Customizer save configs.
 *
 * Usecase: Header presets.
 *
 * @param array $configs configs.
 *
 * @since 4.5.2
 * @return void
 */
function aaa_chinese_save_header_customizer_configs( $configs ) {
	if ( ! empty( $configs['name'] ) ) {
		$key = str_replace( AAA_THEME_SETTINGS . '[', '', $configs['name'] );
		$key = str_replace( ']', '', $key );
		AAA_Chinese_Customizer::$customizer_header_configs[] = $key;
	}
}

/**
 * Customizer save configs.
 *
 * Usecase: footer presets.
 *
 * @param array $configs configs.
 *
 * @since 4.5.2
 * @return void
 */
function aaa_chinese_save_footer_customizer_configs( $configs ) {
	if ( ! empty( $configs['name'] ) ) {
		$key = str_replace( AAA_THEME_SETTINGS . '[', '', $configs['name'] );
		$key = str_replace( ']', '', $key );
		AAA_Chinese_Customizer::$customizer_footer_configs[] = $key;
	}
}
