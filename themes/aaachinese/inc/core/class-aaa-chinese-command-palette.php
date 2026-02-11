<?php
/**
 * AAA Chinese Command Palette Integration
 *
 * Integrates AAA Chinese customizer panels with WordPress Command Palette.
 *
 * @package     AAA Chinese
 *
 * @since       AAA Chinese 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AAA Chinese Command Palette Integration
 */
if ( ! class_exists( 'AAA_Chinese_Command_Palette' ) ) {

	/**
	 * AAA Chinese Command Palette Integration Class
	 */
	class AAA_Chinese_Command_Palette {
		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_command_palette_script' ) );
			add_action( 'admin_bar_menu', array( $this, 'add_search_icon_to_admin_bar' ), 999 );
		}

		/**
		 * Add search icon to admin bar
		 *
		 * @since 1.0.0
		 * @param WP_Admin_Bar $wp_admin_bar WP_Admin_Bar instance.
		 * @return void
		 */
		public function add_search_icon_to_admin_bar( $wp_admin_bar ) {

			if ( ! is_admin() ) {
				return;
			}

			/** @psalm-suppress InvalidGlobal */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			global $wp_version;
			if ( version_compare( $wp_version, '6.3', '<' ) ) {
				return;
			}

			$search_icon = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M11 19C15.4183 19 19 15.4183 19 11C19 6.58172 15.4183 3 11 3C6.58172 3 3 6.58172 3 11C3 15.4183 6.58172 19 11 19Z" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
				<path d="M15 11H15.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
				<path d="M11 11H11.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
				<path d="M7 11H7.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
				<path d="M21 21L16.65 16.65" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>';

			// Detect OS for keyboard shortcut display.
			$is_mac     = false;
			$user_agent = isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '';
			if ( strpos( $user_agent, 'Mac' ) !== false || strpos( $user_agent, 'iPhone' ) !== false || strpos( $user_agent, 'iPad' ) !== false ) {
				$is_mac = true;
			}
			$shortcut_key = $is_mac ? '⌘K' : 'Ctrl+K';

			$title = '<span class="aaachinese-search-icon">' . $search_icon . '</span>'
				. '<span class="aaachinese-search-text">' . esc_html__( 'Search Website Settings', 'aaachinese' ) . '</span>'
				. '<span class="aaachinese-search-tooltip">' . esc_html__( 'Search everything: from site settings to pages and design tools', 'aaachinese' ) . ' (' . esc_html( $shortcut_key ) . ')</span>';

			$wp_admin_bar->add_node(
				array(
					'id'     => 'aaachinese-command-palette-search',
					'title'  => $title,
					'href'   => '#',
					'meta'   => array(
						'class' => 'aaachinese-command-palette-trigger',
					),
					'parent' => 'top-secondary',
				)
			);
		}

		/**
		 * Enqueue Command Palette integration script
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function enqueue_command_palette_script() {

			/** @psalm-suppress InvalidGlobal */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			global $wp_version;
			if ( version_compare( $wp_version, '6.3', '<' ) ) {
				return;
			}

			/** @psalm-suppress RedundantCondition */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$dir_name = SCRIPT_DEBUG ? 'unminified' : 'minified';
			/** @psalm-suppress RedundantCondition */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$file_prefix = SCRIPT_DEBUG ? '' : '.min';

			$script_path = AAA_THEME_DIR . 'assets/js/' . $dir_name . '/command-palette' . $file_prefix . '.js';

			if ( ! file_exists( $script_path ) ) {
				return;
			}

			// Enqueue command palette CSS.
			wp_enqueue_style(
				'aaachinese-command-palette',
				AAA_THEME_URI . 'assets/css/' . $dir_name . '/command-palette' . $file_prefix . '.css',
				array(),
				AAA_THEME_VERSION
			);

			/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			wp_enqueue_script(
				'aaachinese-command-palette',
				AAA_THEME_URI . 'assets/js/' . $dir_name . '/command-palette' . $file_prefix . '.js',
				array( 'wp-data', 'wp-element', 'wp-commands', 'wp-dom-ready' ),
				AAA_THEME_VERSION,
				true
			);

			wp_localize_script(
				'aaachinese-command-palette',
				'aaachineseCommandPalette',
				array(
					'customizerUrl' => admin_url( 'customize.php' ),
					'panels'        => $this->get_customizer_panels(),
					'iconUrl'       => AAA_THEME_URI . 'inc/assets/images/AAA.png',
				)
			);
		}

		/**
		 * Get customizer panels configuration
		 *
		 * @since 1.0.0
		 * @return array List of customizer panels.
		 */
		private function get_customizer_panels() {
			$panels = array(
				array(
					'name'        => 'aaachinese/customizer-global',
					'label'       => __( 'Customizer: Global', 'aaachinese' ),
					'searchLabel' => __( 'Global, Container, Layout, Colors, Color, Background, Base Colors, Typography, Font, Fonts, Headings, Text, Buttons, Button, Style, Base Typography', 'aaachinese' ),
					'type'        => 'panel',
					'id'          => 'panel-global',
				),
				array(
					'name'        => 'aaachinese/customizer-header',
					'label'       => __( 'Customizer: Header', 'aaachinese' ),
					'searchLabel' => __( 'Header, Site Identity, Logo, Site Title, Tagline, Primary Header, Primary Menu, Menu, Navigation, Above Header, Below Header, Mobile Header, Mobile Menu, Search, Button, Social, Account, Cart, Widget, HTML, Off Canvas, Mobile Trigger', 'aaachinese' ),
					'type'        => 'panel',
					'id'          => 'panel-header-builder-group',
				),
				array(
					'name'        => 'aaachinese/customizer-footer',
					'label'       => __( 'Customizer: Footer', 'aaachinese' ),
					'searchLabel' => __( 'Footer, Footer Widgets, Footer Bar, Copyright, Primary Footer, Above Footer, Below Footer, Menu, Social, HTML, Widget', 'aaachinese' ),
					'type'        => 'panel',
					'id'          => 'panel-footer-builder-group',
				),
				array(
					'name'        => 'aaachinese/customizer-blog',
					'label'       => __( 'Customizer: Blog', 'aaachinese' ),
					'searchLabel' => __( 'Blog, Archive, Single Post, Post, Single Page, Page, Content, Excerpt, Featured Image, Meta, Author, Date, Categories, Tags, Comments', 'aaachinese' ),
					'type'        => 'section',
					'id'          => 'section-blog-group',
				),
				array(
					'name'        => 'aaachinese/customizer-general',
					'label'       => __( 'Customizer: General', 'aaachinese' ),
					'searchLabel' => __( 'General, Sidebar, Accessibility, Skip to Content, Block Editor, Gutenberg, Misc, Scroll To Top, Back to Top', 'aaachinese' ),
					'type'        => 'section',
					'id'          => 'section-general-group',
				),
			);

			// Add WooCommerce panel only if WooCommerce is active.
			if ( class_exists( 'WooCommerce' ) ) {
				$panels[] = array(
					'name'        => 'aaachinese/customizer-woocommerce',
					'label'       => __( 'Customizer: WooCommerce', 'aaachinese' ),
					'searchLabel' => __( 'WooCommerce, Shop, Store, Products, Single Product, Cart, Checkout, Account, My Account, Orders, Sidebar', 'aaachinese' ),
					'type'        => 'panel',
					'id'          => 'woocommerce',
				);
			}

			return $panels;
		}
	}

	new AAA_Chinese_Command_Palette();
}
