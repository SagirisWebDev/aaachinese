<?php
/**
 * AAA Chinese Loop
 *
 * @package AAA Chinese
 * @since 1.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AAA_Chinese_Mobile_Header' ) ) {

	/**
	 * AAA_Chinese_Mobile_Header
	 *
	 * @since 1.4.0
	 */
	class AAA_Chinese_Mobile_Header {
		/**
		 * Instance
		 *
		 * @since 1.4.0
		 *
		 * @var object Class object.
		 */
		private static $instance;

		/**
		 * Initiator
		 *
		 * @since 1.4.0
		 *
		 * @return object initialized object of class.
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @since 1.4.0
		 */
		public function __construct() {
			add_action( 'aaa_chinese_header', array( $this, 'mobile_header_markup' ), 5 );
			add_action( 'body_class', array( $this, 'add_body_class' ) );
			add_filter( 'aaa_chinese_main_menu_toggle_classes', array( $this, 'menu_toggle_classes' ) );
			add_filter( 'walker_nav_menu_start_el', array( $this, 'toggle_button' ), 20, 4 );
			add_filter( 'aaa_chinese_walker_nav_menu_start_el', array( $this, 'toggle_button' ), 10, 4 );
		}

		/**
		 * Add submenu toggle button used for mobile devices.
		 *
		 * @since 1.6.9
		 *
		 * @param string   $item_output The menu item's starting HTML output.
		 * @param WP_Post  $item        Menu item data object.
		 * @param int      $depth       Depth of menu item. Used for padding.
		 * @param stdClass $args        An object of wp_nav_menu() arguments.
		 *
		 * @return String Menu item's starting markup.
		 */
		public function toggle_button( $item_output, $item, $depth, $args ) {

			$menu_locations = array( 'primary', 'above_header_menu', 'secondary_menu', 'below_header_menu', 'mobile_menu' );

			for ( $index = 3; $index <= AAA_Chinese_Builder_Helper::$component_limit; $index++ ) {
				array_push( $menu_locations, 'menu_' . $index );
			}

			// Add toggle button if menu is from AAA Chinese.
			if ( true === is_object( $args ) ) {
				if ( isset( $args->theme_location ) && in_array( $args->theme_location, $menu_locations ) ) {
					if ( isset( $item->classes ) && in_array( 'menu-item-has-children', $item->classes ) ) {
						$item_output = $this->menu_arrow_button_markup( $item_output, $item );
					}
				}
			} else {
				if ( isset( $item->post_parent ) && 0 === $item->post_parent ) {
					$item_output = $this->menu_arrow_button_markup( $item_output, $item );
				}
			}

			return $item_output;
		}

		/**
		 * Get Menu Arrow Button Mark up
		 *
		 * @param string  $item_output The menu item's starting HTML output.
		 * @param WP_Post $item        Menu item data object.
		 *
		 * @since 1.7.2
		 * @return string Menu item arrow button markup.
		 */
		public function menu_arrow_button_markup( $item_output, $item ) {
			$item_output  = apply_filters( 'aaa_chinese_toggle_button_markup', $item_output, $item );
			$item_output .= '<button ' . aaa_chinese_attr(
				'ast-menu-toggle',
				array(
					'aria-expanded' => 'false',
					'aria-label'    => __( 'Toggle Menu', 'astra' ),
				),
				$item
			) . '>' . AAA_Chinese_Icons::get_icons( 'arrow' ) . '</button>';

			return $item_output;
		}

		/**
		 * Header Cart Icon Class
		 *
		 * @param array $classes Default argument array.
		 *
		 * @since 1.4.0
		 * @return array;
		 */
		public function menu_toggle_classes( $classes ) {
			return ' ast-mobile-menu-buttons-' . aaa_chinese_get_option( 'mobile-header-toggle-btn-style' ) . ' ';
		}

		/**
		 * Mobile Header Markup
		 *
		 * @return void
		 */
		public function mobile_header_markup() {
			$mobile_header_logo = aaa_chinese_get_option( 'mobile-header-logo' );
			$different_logo     = aaa_chinese_get_option( 'different-mobile-logo' );

			if ( '' !== $mobile_header_logo && '1' == $different_logo ) {
				add_filter( 'aaa_chinese_has_custom_logo', '__return_true' );
				add_filter( 'get_custom_logo', array( $this, 'aaa_chinese_mobile_header_custom_logo' ), 10, 2 );
				add_filter( 'aaa_chinese_is_logo_attachment', array( $this, 'add_mobile_logo_svg_class' ), 10, 2 );
			}
		}

		/**
		 * Replace logo with Mobile Header logo.
		 *
		 * @param sting $html Size name.
		 * @param int   $blog_id Icon.
		 * @since 1.4.0
		 * @return string html markup of logo.
		 */
		public function aaa_chinese_mobile_header_custom_logo( $html, $blog_id ) {

			$mobile_header_logo = aaa_chinese_get_option( 'mobile-header-logo' );

			$custom_logo_id = attachment_url_to_postid( $mobile_header_logo );

			$size = 'ast-mobile-header-logo-size';

			if ( is_customize_preview() ) {
				$size = 'full';
			}

			$logo = sprintf(
				'<a href="%1$s" class="custom-mobile-logo-link" rel="home" itemprop="url">%2$s</a>',
				esc_url( home_url( '/' ) ),
				wp_get_attachment_image(
					$custom_logo_id,
					$size,
					false,
					array(
						'class' => 'ast-mobile-header-logo',
					)
				)
			);

			return $html . $logo;
		}

		/**
		 * Add svg class to mobile logo.
		 *
		 * @param bool  $is_logo_attachment is attachment is logo image?.
		 * @param array $attachment attachment data.
		 * @since 2.1.0
		 * @return bool return if attachment is mobile logo image.
		 */
		public function add_mobile_logo_svg_class( $is_logo_attachment, $attachment ) {

			$mobile_header_logo = aaa_chinese_get_option( 'mobile-header-logo' );
			$custom_logo_id     = attachment_url_to_postid( $mobile_header_logo );

			if ( $custom_logo_id === $attachment->ID ) {
				return true;
			}

			return $is_logo_attachment;
		}

		/**
		 * Add Body Classes
		 *
		 * @param array $classes Body Class Array.
		 * @return array
		 */
		public function add_body_class( $classes ) {

			/**
			 * Add class for header width
			 */
			$header_content_layout = aaa_chinese_get_option( 'different-mobile-logo' );

			if ( '0' == $header_content_layout ) {
				$classes[] = 'ast-mobile-inherit-site-logo';
			}

			return $classes;
		}

	}

	/**
	 * Initialize class object with 'get_instance()' method
	 */
	AAA_Chinese_Mobile_Header::get_instance();

}
