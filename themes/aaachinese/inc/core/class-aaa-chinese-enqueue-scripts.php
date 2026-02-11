<?php
/**
 * Loader Functions
 *
 * @package     AAA Chinese
 *
 * @since       AAA Chinese 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme Enqueue Scripts
 */
if ( ! class_exists( 'AAA_Chinese_Enqueue_Scripts' ) ) {

	/**
	 * Theme Enqueue Scripts
	 */
	class AAA_Chinese_Enqueue_Scripts {
		/**
		 * Class styles.
		 *
		 * @var Enqueued $styles styles.
		 */
		public static $styles;

		/**
		 * Class scripts.
		 *
		 * @var Enqueued $scripts scripts.
		 */
		public static $scripts;

		/**
		 * Constructor
		 */
		public function __construct() {

			add_action( 'aaa_chinese_get_fonts', array( $this, 'add_fonts' ), 1 );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 1 );
			add_action( 'enqueue_block_editor_assets', array( $this, 'gutenberg_assets' ) );
			add_filter( 'admin_body_class', array( $this, 'admin_body_class' ) );
			add_action( 'wp_print_footer_scripts', array( $this, 'aaa_chinese_skip_link_focus_fix' ) );
			add_filter( 'gallery_style', array( $this, 'enqueue_galleries_style' ) );
		}

		/**
		 * Fix skip link focus in IE11.
		 *
		 * This does not enqueue the script because it is tiny and because it is only for IE11,
		 * thus it does not warrant having an entire dedicated blocking script being loaded.
		 *
		 * @link https://git.io/vWdr2
		 * @link https://github.com/WordPress/twentynineteen/pull/47/files
		 * @link https://github.com/ampproject/amphtml/issues/18671
		 */
		public function aaa_chinese_skip_link_focus_fix() {
			// Skip printing script on AMP content, since accessibility fix is covered by AMP framework.
			if ( aaa_chinese_is_amp_endpoint() ) {
				return;
			}

			// The following is minified via `terser --compress --mangle -- js/skip-link-focus-fix.js`.
			?>
			<script>
			/(trident|msie)/i.test(navigator.userAgent)&&document.getElementById&&window.addEventListener&&window.addEventListener("hashchange",function(){var t,e=location.hash.substring(1);/^[A-z0-9_-]+$/.test(e)&&(t=document.getElementById(e))&&(/^(?:a|select|input|button|textarea)$/i.test(t.tagName)||(t.tabIndex=-1),t.focus())},!1);
			</script>
			<?php
		}

		/**
		 * Admin body classes.
		 *
		 * Body classes to be added to <body> tag in admin page
		 *
		 * @param String $classes body classes returned from the filter.
		 * @return String body classes to be added to <body> tag in admin page
		 */
		public function admin_body_class( $classes ) {

			global $pagenow;
			$screen = get_current_screen();

			if ( true === apply_filters( 'aaa_chinese_block_editor_hover_effect', true ) ) {
				$classes .= ' ast-highlight-wpblock-onhover';
			}

			if ( ( 'post-new.php' === $pagenow || 'post.php' === $pagenow ) && ( defined( 'AAA_ADVANCED_HOOKS_POST_TYPE' ) && AAA_ADVANCED_HOOKS_POST_TYPE === $screen->post_type ) || 'widgets.php' === $pagenow ) {
				return $classes;
			}

			$post_id          = get_the_ID();
			$is_boxed         = aaa_chinese_is_content_style_boxed( $post_id );
			$is_sidebar_boxed = aaa_chinese_is_sidebar_style_boxed( $post_id );
			$classes         .= $is_boxed ? ' ast-default-content-style-boxed' : ' ast-default-content-unboxed';
			$classes         .= $is_sidebar_boxed ? ' ast-default-sidebar-style-boxed' : ' ast-default-sidebar-unboxed';

			if ( $post_id ) {
				$meta_content_layout = aaa_chinese_toggle_layout( 'ast-site-content-layout', 'meta', $post_id );
			}

			if ( ( isset( $meta_content_layout ) && ! empty( $meta_content_layout ) ) && 'default' !== $meta_content_layout ) {
				$content_layout = $meta_content_layout;
			} else {
				$content_layout = aaa_chinese_toggle_layout( 'ast-site-content-layout', 'global', false );
			}

			$editor_default_content_layout = aaa_chinese_toggle_layout( 'single-' . strval( get_post_type() ) . '-ast-content-layout', 'single', false );

			if ( 'default' === $editor_default_content_layout || empty( $editor_default_content_layout ) ) {
				// Get the GLOBAL content layout value.
				// NOTE: Here not used `true` in the below function call.
				$editor_default_content_layout = aaa_chinese_toggle_layout( 'ast-site-content-layout', 'global', false );
				$editor_default_content_layout = aaa_chinese_apply_boxed_layouts( $editor_default_content_layout, $is_boxed, $is_sidebar_boxed, $post_id );
				$classes                      .= ' ast-default-layout-' . $editor_default_content_layout;
			} else {
				$editor_default_content_layout = aaa_chinese_apply_boxed_layouts( $editor_default_content_layout, $is_boxed, $is_sidebar_boxed, $post_id );
				$classes                      .= ' ast-default-layout-' . $editor_default_content_layout;
			}

			$content_layout = aaa_chinese_apply_boxed_layouts( $content_layout, $is_boxed, $is_sidebar_boxed, $post_id );

			if ( 'content-boxed-container' === $content_layout ) {
				$classes .= ' ast-separate-container';
			} elseif ( 'boxed-container' === $content_layout ) {
				$classes .= ' ast-separate-container ast-two-container';
			} elseif ( 'page-builder' === $content_layout ) {
				$classes .= ' ast-page-builder-template';
			} elseif ( 'plain-container' === $content_layout ) {
				$classes .= ' ast-plain-container';
			} elseif ( 'narrow-container' === $content_layout ) {
				$classes .= ' ast-narrow-container';
			}

			$site_layout = aaa_chinese_get_option( 'site-layout' );
			if ( 'ast-box-layout' === $site_layout ) {
				$classes .= ' ast-max-width-layout';
			}

			// block CSS class.
			if ( aaa_chinese_block_based_legacy_setup() ) {
				$classes .= ' ast-block-legacy';
			} else {
				$classes .= ' ast-block-custom';
			}

			$classes .= ' ast-' . aaa_chinese_page_layout();
			$classes .= ' ast-sidebar-default-' . aaa_chinese_get_sidebar_layout_for_editor( strval( get_post_type() ) );

			return $classes;
		}

		/**
		 * List of all assets.
		 *
		 * @return array assets array.
		 */
		public static function theme_assets() {

			$default_assets = array(
				// handle => location ( in /assets/js/ ) ( without .js ext).
				'js'  => array(
					'aaachinese-theme-js' => 'style',
				),
				// handle => location ( in /assets/css/ ) ( without .css ext).
				'css' => array(
					'aaachinese-theme-css' => AAA_Chinese_Builder_Helper::apply_flex_based_css() ? 'style-flex' : 'style',
				),
			);

			if ( true === AAA_Chinese_Builder_Helper::$is_header_footer_builder_active ) {

				$default_assets = array(
					// handle => location ( in /assets/js/ ) ( without .js ext).
					'js'  => array(
						'aaachinese-theme-js' => 'frontend',
					),
					// handle => location ( in /assets/css/ ) ( without .css ext).
					'css' => array(
						'aaachinese-theme-css' => AAA_Chinese_Builder_Helper::apply_flex_based_css() ? 'main' : 'frontend',
					),
				);

				if ( defined( 'AAA_EXT_VER' ) && version_compare( AAA_EXT_VER, '3.5.9', '<' ) ) {
					$default_assets['js']['aaachinese-theme-js-pro'] = 'frontend-pro';
				}

				if ( ( class_exists( 'Easy_Digital_Downloads' ) && AAA_Chinese_Builder_Helper::is_component_loaded( 'edd-cart', 'header' ) ) ||
					( class_exists( 'WooCommerce' ) && AAA_Chinese_Builder_Helper::is_component_loaded( 'woo-cart', 'header' ) && self::should_load_woocommerce_js() ) ) {
					$default_assets['js']['aaachinese-mobile-cart'] = 'mobile-cart';
				}

				/** @psalm-suppress RedundantCondition */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
				if ( ( true === AAA_Chinese_Builder_Helper::$is_header_footer_builder_active && AAA_Chinese_Builder_Helper::is_component_loaded( 'search', 'header' ) && aaa_chinese_get_option( 'live-search', false ) ) || ( is_search() && true === aaa_chinese_get_option( 'ast-search-live-search' ) ) ) {
					/** @psalm-suppress RedundantCondition */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
					$default_assets['js']['aaachinese-live-search'] = 'live-search';
				}

				if ( class_exists( 'WooCommerce' ) && self::should_load_woocommerce_js() ) {
					if ( is_product() && aaa_chinese_get_option( 'single-product-sticky-add-to-cart' ) ) {
						$default_assets['js']['aaachinese-sticky-add-to-cart'] = 'sticky-add-to-cart';
					}

					if ( ! is_customize_preview() ) {
						$aaa_chinese_shop_add_to_cart = aaa_chinese_get_option( 'shop-add-to-cart-action' );
						if ( $aaa_chinese_shop_add_to_cart && 'default' !== $aaa_chinese_shop_add_to_cart ) {
							$default_assets['js']['aaachinese-shop-add-to-cart'] = 'shop-add-to-cart';
						}
					}

					/** @psalm-suppress UndefinedFunction */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
					$aaa_chinese_add_to_cart_quantity_btn_enabled = apply_filters( 'aaa_chinese_add_to_cart_quantity_btn_enabled', aaa_chinese_get_option( 'single-product-plus-minus-button' ) );
					if ( $aaa_chinese_add_to_cart_quantity_btn_enabled && self::should_load_add_to_cart_quantity_btn_script() ) {
						$default_assets['js']['aaachinese-add-to-cart-quantity-btn'] = 'add-to-cart-quantity-btn';
					}
				}
			}

			if ( aaa_chinese_get_option( 'site-sticky-sidebar', false ) ) {
				$default_assets['js']['aaachinese-sticky-sidebar'] = 'sticky-sidebar';
			}

			return apply_filters( 'aaa_chinese_theme_assets', $default_assets );
		}

		/**
		 * Add Fonts
		 */
		public function add_fonts() {

			$font_family  = aaa_chinese_get_option( 'body-font-family' );
			$font_weight  = aaa_chinese_get_option( 'body-font-weight' );
			$font_variant = aaa_chinese_get_option( 'body-font-variant' );

			AAA_Chinese_Fonts::add_font( $font_family, $font_weight );
			AAA_Chinese_Fonts::add_font( $font_family, $font_variant );

			// Render headings font.
			$heading_font_family  = aaa_chinese_get_option( 'headings-font-family' );
			$heading_font_weight  = aaa_chinese_get_option( 'headings-font-weight' );
			$heading_font_variant = aaa_chinese_get_option( 'headings-font-variant' );

			AAA_Chinese_Fonts::add_font( $heading_font_family, $heading_font_weight );
			AAA_Chinese_Fonts::add_font( $heading_font_family, $heading_font_variant );
		}

		/**
		 * Check if WooCommerce JS assets should be loaded based on filter settings
		 *
		 * @return bool Whether WooCommerce JS should be loaded
		 * @since 4.11.13
		 */
		public static function should_load_woocommerce_js() {
			// Allow users to disable WooCommerce JS loading on specific pages/posts/post types.
			return apply_filters( 'aaa_chinese_load_woocommerce_js', true );
		}

		/**
		 * Check if WooCommerce CSS assets should be loaded based on filter settings
		 *
		 * @return bool Whether WooCommerce CSS should be loaded
		 * @since 4.11.13
		 */
		public static function should_load_woocommerce_css() {
			// Allow users to disable WooCommerce CSS loading on specific pages/posts/post types.
			return apply_filters( 'aaa_chinese_load_woocommerce_css', true );
		}

		/**
		 * Check if add to cart quantity button script should be loaded based on specific conditions
		 *
		 * @since 4.11.18
		 *
		 * @return bool Whether add to cart quantity button script should be loaded
		 */
		public static function should_load_add_to_cart_quantity_btn_script() {
			// 1. Check if the Cart Widget is present in the header.
			if ( self::has_cart_widget_in_header() ) {
				return true;
			}

			// 2. Check for Product Block or Shortcode.
			if ( self::has_product_block_or_shortcode() ) {
				return true;
			}

			// 3. Check if we are on the Single Product or the Cart Page.
			if ( is_product() || is_cart() || is_shop() ) {
				return true;
			}

			// 4. Check with Elementor's product widget.
			if ( self::has_elementor_product_widget() ) {
				return true;
			}

			return false;
		}

		/**
		 * Check if cart widget is present in header.
		 *
		 * @since 4.11.18
		 *
		 * @return bool
		 */
		public static function has_cart_widget_in_header() {
			// Check if header footer builder is active.
			if ( ! AAA_Chinese_Builder_Helper::$is_header_footer_builder_active ) {
				return false;
			}

			// Check if woo-cart component is loaded in header.
			if ( class_exists( 'AAA_Chinese_Builder_Helper' ) && AAA_Chinese_Builder_Helper::is_component_loaded( 'woo-cart', 'header' ) ) {
				return true;
			}

			// Check for legacy cart widget.
			if ( is_active_widget( false, false, 'woocommerce_widget_cart', true ) ) {
				return true;
			}

			return false;
		}

		/**
		 * Check if page has product block or shortcode.
		 *
		 * @since 4.11.18
		 *
		 * @return bool
		 */
		public static function has_product_block_or_shortcode() {
			// Check for WooCommerce blocks.
			if ( function_exists( 'has_block' ) && (
				has_block( 'woocommerce/product-collection' ) ||
				has_block( 'woocommerce/all-products' ) ||
				has_block( 'woocommerce/products' ) )
			) {
				return true;
			}

			// Check for product shortcodes in post content.
			if ( is_singular() ) {
				$post = get_post();
				if ( $post ) {
					// For single posts, check the post content directly.
					return self::has_woocommerce_shortcode( $post->post_content );
				}
			}

			if ( is_archive() || is_home() ) {
				// For archive pages, check for shortcodes more efficiently.
				// without loading all post content into memory.
				return self::has_woocommerce_shortcode_in_archive();
			}

			return false;
		}

		/**
		 * Check if content has WooCommerce shortcodes.
		 *
		 * @since 4.11.18
		 *
		 * @param string $content Post content to check.
		 * @return bool
		 */
		private static function has_woocommerce_shortcode( $content ) {
			// Check for common WooCommerce shortcodes.
			$shortcodes = array(
				'products',
				'product_page',
				'product_category',
				'recent_products',
				'sale_products',
				'best_selling_products',
				'top_rated_products',
				'featured_products',
				'product_attribute',
			);

			foreach ( $shortcodes as $shortcode ) {
				if ( has_shortcode( $content, $shortcode ) ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * Check for WooCommerce shortcodes in archive pages efficiently.
		 *
		 * @since 4.11.18
		 *
		 * @return bool
		 */
		private static function has_woocommerce_shortcode_in_archive() {
			// Access the global $wp_query variable in a Psalm-compliant way.
			$wp_query = isset( $GLOBALS['wp_query'] ) ? $GLOBALS['wp_query'] : null;

			// If no posts, return false early.
			if ( ! $wp_query || empty( $wp_query->posts ) ) {
				return false;
			}

			// Limit the number of posts to check to prevent memory issues.
			$posts_to_check = array_slice( $wp_query->posts, 0, 5 ); // Check only first 5 posts.

			// Check each post individually instead of concatenating all content.
			foreach ( $posts_to_check as $post ) {
				if ( ! $post ) {
					continue; // Skip if post is empty.
				}
				if ( self::has_woocommerce_shortcode( $post->post_content ) ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * Check if page has Elementor product widget.
		 *
		 * @since 4.11.18
		 *
		 * @return bool
		 */
		public static function has_elementor_product_widget() {
			// Check if Elementor is active.
			if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
				return false;
			}

			// Check if we're on a page that might have Elementor product widgets.
			if ( ! is_singular() ) {
				return false;
			}

			// Check for Elementor data with product-related widgets.
			$post_id = get_the_ID();
			if ( ! $post_id ) {
				return false;
			}

			// Check if post is built with Elementor.
			if ( ! get_post_meta( $post_id, '_elementor_version', true ) ) {
				return false;
			}

			// Get Elementor data.
			$elementor_data = get_post_meta( $post_id, '_elementor_data', true );
			if ( ! $elementor_data || ! is_string( $elementor_data ) ) {
				return false;
			}

			// Check for WooCommerce-related Elementor widgets.
			$woo_widgets = array(
				'woocommerce-menu-cart',
				'woocommerce-product-images',
				'woocommerce-product-meta',
				'woocommerce-product-price',
				'woocommerce-product-rating',
				'woocommerce-product-short-description',
				'woocommerce-product-stock',
				'woocommerce-product-tabs',
				'woocommerce-product-title',
				'woocommerce-product-additional-information',
				'woocommerce-product-data-tabs',
				'woocommerce-products',
				'woocommerce-categories',
				'wc-archive-products',
				'wc-single-product',
			);

			// Check if any WooCommerce widgets exist in Elementor data.
			foreach ( $woo_widgets as $widget ) {
				if ( strpos( $elementor_data, $widget ) !== false ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * Enqueue Scripts
		 */
		public function enqueue_scripts() {

			if ( false === self::enqueue_theme_assets() ) {
				return;
			}

			/* Directory and Extension */
			$file_prefix = SCRIPT_DEBUG ? '' : '.min';
			$dir_name    = SCRIPT_DEBUG ? 'unminified' : 'minified';

			$js_uri  = AAA_THEME_URI . 'assets/js/' . $dir_name . '/';
			$css_uri = AAA_THEME_URI . 'assets/css/minified/';

			/**
			 * IE Only Js and CSS Files.
			 */
			// Flexibility.js for flexbox IE10 support.
			wp_enqueue_script( 'aaachinese-flexibility', $js_uri . 'flexibility' . $file_prefix . '.js', array(), AAA_THEME_VERSION, false );
			wp_add_inline_script( 'aaachinese-flexibility', 'typeof flexibility !== "undefined" && flexibility(document.documentElement);' );

			// Polyfill for CustomEvent for IE.
			wp_register_script( 'aaachinese-customevent', $js_uri . 'custom-events-polyfill' . $file_prefix . '.js', array(), AAA_THEME_VERSION, false );
			wp_register_style( 'aaachinese-galleries-css', $css_uri . 'galleries.min.css', array(), AAA_THEME_VERSION, 'all' );
			// All assets.
			$all_assets = self::theme_assets();
			$styles     = $all_assets['css'];
			$scripts    = $all_assets['js'];

			if ( is_array( $styles ) && ! empty( $styles ) ) {
				// Register & Enqueue Styles.
				foreach ( $styles as $key => $style ) {

					$dependency = array();

					// Add dynamic CSS dependency for all styles except for theme's style.css.
					if ( 'aaachinese-theme-css' !== $key && class_exists( 'AAA_Chinese_Cache_Base' ) ) {
						if ( ! AAA_Chinese_Cache_Base::inline_assets() ) {
							$dependency[] = 'aaachinese-theme-dynamic';
						}
					}

					// Generate CSS URL.
					$css_file = $css_uri . $style . '.min.css';

					// Register.
					wp_register_style( $key, $css_file, $dependency, AAA_THEME_VERSION, 'all' );

					// Enqueue.
					wp_enqueue_style( $key );

					// RTL support.
					wp_style_add_data( $key, 'rtl', 'replace' );
				}
			}

			// Fonts - Render Fonts.
			AAA_Chinese_Fonts::render_fonts();

			/**
			 * Inline styles
			 */

			add_filter( 'aaa_chinese_dynamic_theme_css', array( 'AAA_Chinese_Dynamic_CSS', 'return_output' ) );
			add_filter( 'aaa_chinese_dynamic_theme_css', array( 'AAA_Chinese_Dynamic_CSS', 'return_meta_output' ) );

			$menu_animation = aaa_chinese_get_option( 'header-main-submenu-container-animation' );

			// Submenu Container Animation for header builder.
			if ( true === AAA_Chinese_Builder_Helper::$is_header_footer_builder_active ) {

				for ( $index = 1; $index <= AAA_Chinese_Builder_Helper::$component_limit; $index++ ) {

					$menu_animation_enable = aaa_chinese_get_option( 'header-menu' . $index . '-submenu-container-animation' );

					if ( AAA_Chinese_Builder_Helper::is_component_loaded( 'menu-' . $index, 'header' ) && ! empty( $menu_animation_enable ) ) {
						$menu_animation = 'is_animated';
						break;
					}
				}
			}

			$rtl = is_rtl() ? '-rtl' : '';

			if ( ! empty( $menu_animation ) || is_customize_preview() ) {
				if ( class_exists( 'AAA_Chinese_Cache' ) ) {
					AAA_Chinese_Cache::add_css_file( AAA_THEME_DIR . 'assets/css/minified/menu-animation' . $rtl . '.min.css' );
				} else {
					wp_register_style( 'aaachinese-menu-animation', $css_uri . 'menu-animation.min.css', null, AAA_THEME_VERSION, 'all' );
					wp_enqueue_style( 'aaachinese-menu-animation' );
				}
			}

			if ( ! class_exists( 'AAA_Chinese_Cache' ) ) {
				$theme_css_data = apply_filters( 'aaa_chinese_dynamic_theme_css', '' );
				wp_add_inline_style( 'aaachinese-theme-css', $theme_css_data );
			}

			if ( aaa_chinese_is_amp_endpoint() ) {
				return;
			}

			// Comment assets.
			if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
				wp_enqueue_script( 'comment-reply' );
			}

			if ( is_array( $scripts ) && ! empty( $scripts ) ) {

				// Register & Enqueue Scripts.
				foreach ( $scripts as $key => $script ) {

					// Set dependencies based on script type.
					$dependencies = array();
					if ( 'aaachinese-mobile-cart' === $key && class_exists( 'WooCommerce' ) ) {
						$dependencies = array( 'jquery', 'wc-add-to-cart' );
					}

					// Register.
					wp_register_script( $key, $js_uri . $script . $file_prefix . '.js', $dependencies, AAA_THEME_VERSION, true );

					// Enqueue.
					wp_enqueue_script( $key );
				}
			}

			/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$quantity_type = defined( 'AAA_EXT_VER' ) && AAA_Chinese_Ext_Extension::is_active( 'woocommerce' ) ? aaa_chinese_get_option( 'cart-plus-minus-button-type' ) : 'normal';
			/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort

			$aaa_chinese_localize = array(
				'break_point'                     => aaa_chinese_header_break_point(),    // Header Break Point.
				'isRtl'                           => is_rtl(),
				'is_scroll_to_id'                 => aaa_chinese_get_option( 'enable-scroll-to-id' ),
				'is_scroll_to_top'                => aaa_chinese_get_option( 'scroll-to-top-enable' ),
				'is_header_footer_builder_active' => AAA_Chinese_Builder_Helper::$is_header_footer_builder_active,
				'responsive_cart_click'           => aaa_chinese_get_option( 'responsive-cart-click-action' ),
				'is_dark_palette'                 => AAA_Chinese_Global_Palette::is_dark_palette(),
			);

			wp_localize_script( 'aaachinese-theme-js', 'aaachinese', apply_filters( 'aaa_chinese_theme_js_localize', $aaa_chinese_localize ) );

			// Only localize the quantity button script if it should be loaded.
			if ( class_exists( 'WooCommerce' ) && self::should_load_add_to_cart_quantity_btn_script() ) {
				$aaa_chinese_qty_btn_localize = array(
					'plus_qty'   => __( 'Plus Quantity', 'aaachinese' ),
					'minus_qty'  => __( 'Minus Quantity', 'aaachinese' ),
					'style_type' => $quantity_type,    // Quantity button type.
				);

				wp_localize_script( 'aaachinese-add-to-cart-quantity-btn', 'aaa_chinese_qty_btn', apply_filters( 'aaa_chinese_qty_btn_js_localize', $aaa_chinese_qty_btn_localize ) );
			}

			$aaa_chinese_cart_localize_data = array(
				'desktop_layout'        => aaa_chinese_get_option( 'woo-header-cart-click-action' ),    // WooCommerce sidebar flyout desktop.
				'responsive_cart_click' => aaa_chinese_get_option( 'responsive-cart-click-action' ),    // WooCommerce responsive devices flyout.
			);

			wp_localize_script( 'aaachinese-mobile-cart', 'aaa_chinese_cart', apply_filters( 'aaa_chinese_cart_js_localize', $aaa_chinese_cart_localize_data ) );

			if ( ( true === AAA_Chinese_Builder_Helper::$is_header_footer_builder_active && AAA_Chinese_Builder_Helper::is_component_loaded( 'search', 'header' ) && aaa_chinese_get_option( 'live-search', false ) ) || ( is_search() && true === aaa_chinese_get_option( 'ast-search-live-search' ) ) ) {
				$search_post_types      = array();
				$search_post_type_label = array();
				$search_within_val      = aaa_chinese_get_option( 'live-search-post-types' );
				if ( ! empty( $search_within_val ) && is_array( $search_within_val ) ) {
					foreach ( $search_within_val as $post_type => $value ) {
						if ( $value && post_type_exists( $post_type ) ) {
							$search_post_types[] = $post_type;
							/** @psalm-suppress PossiblyNullPropertyFetch */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
							$post_type_object                     = get_post_type_object( $post_type );
							$search_post_type_label[ $post_type ] = is_object( $post_type_object ) && isset( $post_type_object->labels->name ) ? esc_html( $post_type_object->labels->name ) : $post_type;
							/** @psalm-suppress PossiblyNullPropertyFetch */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
						}
					}
				}

				$search_page_post_types      = array();
				$search_page_post_type_label = array();
				$search_page_within_val      = aaa_chinese_get_option( 'ast-search-live-search-post-types' );
				if ( is_search() && ! empty( $search_page_within_val ) && is_array( $search_page_within_val ) ) {
					foreach ( $search_page_within_val as $post_type => $value ) {
						if ( $value && post_type_exists( $post_type ) ) {
							$search_page_post_types[] = $post_type;
							/** @psalm-suppress PossiblyNullPropertyFetch */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
							$post_type_object                          = get_post_type_object( $post_type );
							$search_page_post_type_label[ $post_type ] = is_object( $post_type_object ) && isset( $post_type_object->labels->name ) ? esc_html( $post_type_object->labels->name ) : $post_type;
							/** @psalm-suppress PossiblyNullPropertyFetch */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
						}
					}
				}

				$aaa_chinese_live_search_localize_data = array(
					'rest_api_url'                 => get_rest_url(),
					'search_posts_per_page'        => aaa_chinese_get_option( 'live-search-result-count' ),
					'search_post_types'            => $search_post_types,
					'search_post_types_labels'     => $search_post_type_label,
					'search_language'              => aaa_chinese_get_current_language_slug(),
					'no_live_results_found'        => __( 'No results found', 'aaachinese' ),
					'search_page_condition'        => is_search() && true === aaa_chinese_get_option( 'ast-search-live-search' ) ? true : false,
					'search_page_post_types'       => $search_page_post_types,
					'search_page_post_type_labels' => $search_page_post_type_label,
				);

				wp_localize_script( 'aaachinese-live-search', 'aaa_chinese_search', apply_filters( 'aaa_chinese_search_js_localize', $aaa_chinese_live_search_localize_data ) );
			}

			if ( class_exists( 'woocommerce' ) && self::should_load_woocommerce_js() ) {
				$is_aaa_chinese_pro = function_exists( 'aaa_chinese_has_pro_woocommerce_addon' ) ? aaa_chinese_has_pro_woocommerce_addon() : false;

				$aaa_chinese_shop_add_to_cart_localize_data = array(
					'shop_add_to_cart_action' => aaa_chinese_get_option( 'shop-add-to-cart-action' ),
					'cart_url'                => wc_get_cart_url(),
					'checkout_url'            => wc_get_checkout_url(),
					'is_aaa_chinese_pro'            => $is_aaa_chinese_pro,
				);
				wp_localize_script( 'aaachinese-shop-add-to-cart', 'aaa_chinese_shop_add_to_cart', apply_filters( 'aaa_chinese_shop_add_to_cart_js_localize', $aaa_chinese_shop_add_to_cart_localize_data ) );
			}

			$sticky_sidebar = aaa_chinese_get_option( 'site-sticky-sidebar', false );
			if ( $sticky_sidebar ) {

				/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
				$sticky_header_addon = ( defined( 'AAA_EXT_VER' ) && AAA_Chinese_Ext_Extension::is_active( 'sticky-header' ) );
				/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort

				$aaa_chinese_sticky_sidebar_localize_data = array(
					'sticky_sidebar_on'   => $sticky_sidebar,
					'header_above_height' => aaa_chinese_get_option( 'hba-header-height' ),
					'header_height'       => aaa_chinese_get_option( 'hb-header-height' ),
					'header_below_height' => aaa_chinese_get_option( 'hbb-header-height' ),
					'header_above_stick'  => aaa_chinese_get_option( 'header-above-stick', false ),
					'header_main_stick'   => aaa_chinese_get_option( 'header-main-stick', false ),
					'header_below_stick'  => aaa_chinese_get_option( 'header-below-stick', false ),
					'sticky_header_addon' => $sticky_header_addon,
					'desktop_breakpoint'  => aaa_chinese_get_tablet_breakpoint( '', 1 ),
				);
				wp_localize_script( 'aaachinese-sticky-sidebar', 'aaa_chinese_sticky_sidebar', apply_filters( 'aaa_chinese_sticky_sidebar_js_localize', $aaa_chinese_sticky_sidebar_localize_data ) );
			}
		}

		/**
		 * Trim CSS
		 *
		 * @since 1.0.0
		 * @param string $css CSS content to trim.
		 * @return string
		 */
		public static function trim_css( $css = '' ) {

			// Trim white space for faster page loading.
			if ( ! empty( $css ) ) {
				  // phpcs:ignore Generic.PHP.ForbiddenFunctions.FoundWithAlternative -- Safe usage: no /e modifier, removes CSS comments only
				$css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );
				$css = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $css );
				$css = str_replace( ', ', ',', $css );
			}

			return $css;
		}

		/**
		 * Enqueue Gutenberg assets.
		 *
		 * @since 1.5.2
		 *
		 * @return void
		 */
		public function gutenberg_assets() {
			/* Directory and Extension */
			$rtl = '';
			if ( is_rtl() ) {
				$rtl = '-rtl';
			}

			$js_uri = AAA_THEME_URI . 'inc/assets/js/block-editor-script.js';
			/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			wp_enqueue_script( 'aaachinese-block-editor-script', $js_uri, false, AAA_THEME_VERSION, 'all' );
			/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort

			$content_bg_obj = aaa_chinese_get_option( 'content-bg-obj-responsive' );
			$site_bg_obj    = aaa_chinese_get_option( 'site-layout-outside-bg-obj-responsive' );

			$site_builder_url = admin_url( 'admin.php?page=theme-builder' );

			/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$is_aaa_chinese_pro_colors_activated = ( defined( 'AAA_EXT_VER' ) && AAA_Chinese_Ext_Extension::is_active( 'colors-and-background' ) );
			/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort

			$aaa_chinese_global_palette_instance = new AAA_Chinese_Global_Palette();
			$aaa_chinese_colors                  = array(
				'var(--ast-global-color-0)'     => $aaa_chinese_global_palette_instance->get_color_by_palette_variable( 'var(--ast-global-color-0)' ),
				'var(--ast-global-color-1)'     => $aaa_chinese_global_palette_instance->get_color_by_palette_variable( 'var(--ast-global-color-1)' ),
				'var(--ast-global-color-2)'     => $aaa_chinese_global_palette_instance->get_color_by_palette_variable( 'var(--ast-global-color-2)' ),
				'var(--ast-global-color-3)'     => $aaa_chinese_global_palette_instance->get_color_by_palette_variable( 'var(--ast-global-color-3)' ),
				'var(--ast-global-color-4)'     => $aaa_chinese_global_palette_instance->get_color_by_palette_variable( 'var(--ast-global-color-4)' ),
				'var(--ast-global-color-5)'     => $aaa_chinese_global_palette_instance->get_color_by_palette_variable( 'var(--ast-global-color-5)' ),
				'var(--ast-global-color-6)'     => $aaa_chinese_global_palette_instance->get_color_by_palette_variable( 'var(--ast-global-color-6)' ),
				'var(--ast-global-color-7)'     => $aaa_chinese_global_palette_instance->get_color_by_palette_variable( 'var(--ast-global-color-7)' ),
				'var(--ast-global-color-8)'     => $aaa_chinese_global_palette_instance->get_color_by_palette_variable( 'var(--ast-global-color-8)' ),
				'ast_wp_version_higher_6_3'     => aaa_chinese_wp_version_compare( '6.2.99', '>' ),
				'ast_wp_version_higher_6_4'     => aaa_chinese_wp_version_compare( '6.4.99', '>' ),
				'is_dark_palette'               => AAA_Chinese_Global_Palette::is_dark_palette(),
				'apply_content_bg_fullwidth'    => aaa_chinese_apply_content_background_fullwidth_layouts(),
				'customizer_content_bg_obj'     => $content_bg_obj,
				'customizer_site_bg_obj'        => $site_bg_obj,
				'is_aaa_chinese_pro_colors_activated' => $is_aaa_chinese_pro_colors_activated,
				'site_builder_url'              => $site_builder_url,
				'mobile_logo'                   => aaa_chinese_get_option( 'mobile-header-logo' ),
				'mobile_logo_state'             => aaa_chinese_get_option( 'different-mobile-logo' ),
			);

			wp_localize_script( 'aaachinese-block-editor-script', 'aaachineseColors', apply_filters( 'aaa_chinese_theme_root_colors', $aaa_chinese_colors ) );

			// Render fonts in Gutenberg layout.
			AAA_Chinese_Fonts::render_fonts();

			if ( aaa_chinese_block_based_legacy_setup() ) {
				$css_uri = AAA_THEME_URI . 'inc/assets/css/block-editor-styles' . $rtl . '.css';
				/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
				wp_enqueue_style( 'aaachinese-block-editor-styles', $css_uri, false, AAA_THEME_VERSION, 'all' );
				/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
				wp_add_inline_style( 'aaachinese-block-editor-styles', apply_filters( 'aaa_chinese_block_editor_dynamic_css', Gutenberg_Editor_CSS::get_css() ) );
			} else {
				$css_uri = AAA_THEME_URI . 'inc/assets/css/wp-editor-styles' . $rtl . '.css';
				/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
				wp_enqueue_style( 'aaachinese-wp-editor-styles', $css_uri, false, AAA_THEME_VERSION, 'all' );
				/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
				wp_add_inline_style( 'aaachinese-wp-editor-styles', apply_filters( 'aaa_chinese_block_editor_dynamic_css', AAA_Chinese_WP_Editor_CSS::get_css() ) );
			}
		}

		/**
		 * Function to check if enqueuing of AAA Chinese assets are disabled.
		 *
		 * @since 2.1.0
		 * @return bool
		 */
		public static function enqueue_theme_assets() {
			return apply_filters( 'aaa_chinese_enqueue_theme_assets', true );
		}

		/**
		 * Enqueue galleries relates CSS on gallery_style filter.
		 *
		 * @param string $gallery_style gallery style and div.
		 * @since 3.5.0
		 * @return string
		 */
		public function enqueue_galleries_style( $gallery_style ) {
			wp_enqueue_style( 'aaachinese-galleries-css' );
			return $gallery_style;
		}

	}

	new AAA_Chinese_Enqueue_Scripts();
}
