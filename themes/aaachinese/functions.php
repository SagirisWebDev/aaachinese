<?php
/**
 * AAA Chinese functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AAA Chinese
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Define Constants
 */
define( 'AAA_THEME_VERSION', '1.0.0' );
define( 'AAA_THEME_SETTINGS', 'aaa-chinese-settings' );
define( 'AAA_THEME_DIR', trailingslashit( get_template_directory() ) );
define( 'AAA_THEME_URI', trailingslashit( esc_url( get_template_directory_uri() ) ) );
define( 'AAA_THEME_ORG_VERSION', file_exists( AAA_THEME_DIR . 'inc/w-org-version.php' ) );

/**
 * Minimum Version requirement of the AAA Chinese Pro addon.
 * This constant will be used to display the notice asking user to update the AAA Chinese addon to the version defined below.
 */
define( 'AAA_EXT_MIN_VER', '4.12.0' );

/**
 * Load in-house compatibility.
 */
if ( AAA_THEME_ORG_VERSION ) {
	require_once AAA_THEME_DIR . 'inc/w-org-version.php';
}

/**
 * Setup helper functions of AAA Chinese.
 */
require_once AAA_THEME_DIR . 'inc/core/class-aaa-chinese-theme-options.php';
require_once AAA_THEME_DIR . 'inc/core/class-theme-strings.php';
require_once AAA_THEME_DIR . 'inc/core/common-functions.php';
require_once AAA_THEME_DIR . 'inc/core/class-aaa-chinese-icons.php';

define( 'AAA_WEBSITE_BASE_URL', 'https://wpaaa-chinese.com' );

/**
 * Update theme
 */
require_once AAA_THEME_DIR . 'inc/theme-update/aaa-chinese-update-functions.php';
require_once AAA_THEME_DIR . 'inc/theme-update/class-aaa-chinese-theme-background-updater.php';

/**
 * Fonts Files
 */
// require_once AAA_THEME_DIR . 'inc/customizer/class-aaa-chinese-font-families.php';
// if ( is_admin() ) {
// 	require_once AAA_THEME_DIR . 'inc/customizer/class-aaa-chinese-fonts-data.php';
// }

require_once AAA_THEME_DIR . 'inc/lib/webfont/class-aaa-chinese-webfont-loader.php';
require_once AAA_THEME_DIR . 'inc/lib/docs/class-aaa-chinese-docs-loader.php';
// require_once AAA_THEME_DIR . 'inc/customizer/class-aaa-chinese-fonts.php';

require_once AAA_THEME_DIR . 'inc/dynamic-css/custom-menu-old-header.php';
require_once AAA_THEME_DIR . 'inc/dynamic-css/container-layouts.php';
require_once AAA_THEME_DIR . 'inc/dynamic-css/aaa-chinese-icons.php';
require_once AAA_THEME_DIR . 'inc/core/class-aaa-chinese-walker-page.php';
require_once AAA_THEME_DIR . 'inc/core/class-aaa-chinese-enqueue-scripts.php';
require_once AAA_THEME_DIR . 'inc/core/class-gutenberg-editor-css.php';
require_once AAA_THEME_DIR . 'inc/core/class-aaa-chinese-wp-editor-css.php';
require_once AAA_THEME_DIR . 'inc/core/class-aaa-chinese-command-palette.php';
require_once AAA_THEME_DIR . 'inc/dynamic-css/block-editor-compatibility.php';
require_once AAA_THEME_DIR . 'inc/dynamic-css/inline-on-mobile.php';
require_once AAA_THEME_DIR . 'inc/dynamic-css/content-background.php';
require_once AAA_THEME_DIR . 'inc/dynamic-css/dark-mode.php';
require_once AAA_THEME_DIR . 'inc/class-aaa-chinese-dynamic-css.php';
require_once AAA_THEME_DIR . 'inc/class-aaa-chinese-global-palette.php';

// Enable NPS Survey only if the starter templates version is < 4.3.7 or > 4.4.4 to prevent fatal error.
if ( ! defined( 'AAA_SITES_VER' ) || version_compare( AAA_SITES_VER, '4.3.7', '<' ) || version_compare( AAA_SITES_VER, '4.4.4', '>' ) ) {
	// NPS Survey Integration
	require_once AAA_THEME_DIR . 'inc/lib/class-aaa-chinese-nps-notice.php';
	require_once AAA_THEME_DIR . 'inc/lib/class-aaa-chinese-nps-survey.php';
}

/**
 * Custom template tags for this theme.
 */
require_once AAA_THEME_DIR . 'inc/core/class-aaa-chinese-attr.php';
require_once AAA_THEME_DIR . 'inc/template-tags.php';

require_once AAA_THEME_DIR . 'inc/widgets.php';
require_once AAA_THEME_DIR . 'inc/core/theme-hooks.php';
// require_once AAA_THEME_DIR . 'inc/admin-functions.php';
require_once AAA_THEME_DIR . 'inc/class-aaa-chinese-memory-limit-notice.php';
require_once AAA_THEME_DIR . 'inc/core/sidebar-manager.php';

/**
 * Markup Functions
 */
require_once AAA_THEME_DIR . 'inc/markup-extras.php';
require_once AAA_THEME_DIR . 'inc/extras.php';
require_once AAA_THEME_DIR . 'inc/blog/blog-config.php';
require_once AAA_THEME_DIR . 'inc/blog/blog.php';
require_once AAA_THEME_DIR . 'inc/blog/single-blog.php';

/**
 * Markup Files
 */
require_once AAA_THEME_DIR . 'inc/template-parts.php';
require_once AAA_THEME_DIR . 'inc/class-aaa-chinese-loop.php';
require_once AAA_THEME_DIR . 'inc/class-aaa-chinese-mobile-header.php';

/**
 * Functions and definitions.
 */
require_once AAA_THEME_DIR . 'inc/class-aaa-chinese-after-setup-theme.php';

// Required files.
// require_once AAA_THEME_DIR . 'inc/core/class-aaa-chinese-admin-helper.php';

require_once AAA_THEME_DIR . 'inc/schema/class-aaa-chinese-schema.php';

/* Setup API */
// require_once AAA_THEME_DIR . 'admin/includes/class-aaa-chinese-learn.php';
// require_once AAA_THEME_DIR . 'admin/includes/class-aaa-chinese-api-init.php';

if ( is_admin() ) {
	/**
	 * Admin Menu Settings
	 */
	// require_once AAA_THEME_DIR . 'inc/core/class-aaa-chinese-admin-settings.php';
	// require_once AAA_THEME_DIR . 'admin/class-aaa-chinese-admin-loader.php';
	require_once AAA_THEME_DIR . 'inc/lib/aaa-chinese-notices/class-aaa-chinese-notices.php';
}

/**
 * Metabox additions.
 */
// require_once AAA_THEME_DIR . 'inc/metabox/class-aaa-chinese-meta-boxes.php';
// require_once AAA_THEME_DIR . 'inc/metabox/class-aaa-chinese-meta-box-operations.php';
// require_once AAA_THEME_DIR . 'inc/metabox/class-aaa-chinese-elementor-editor-settings.php';

/**
 * Customizer additions.
 */
// require_once AAA_THEME_DIR . 'inc/customizer/class-aaa-chinese-customizer.php';

/**
 * AAA Chinese Modules.
 */
require_once AAA_THEME_DIR . 'inc/modules/posts-structures/class-aaa-chinese-post-structures.php';
require_once AAA_THEME_DIR . 'inc/modules/related-posts/class-aaa-chinese-related-posts.php';

/**
 * Compatibility
 */
require_once AAA_THEME_DIR . 'inc/compatibility/class-aaa-chinese-gutenberg.php';
require_once AAA_THEME_DIR . 'inc/compatibility/class-aaa-chinese-jetpack.php';
require_once AAA_THEME_DIR . 'inc/compatibility/woocommerce/class-aaa-chinese-woocommerce.php';
// require_once AAA_THEME_DIR . 'inc/compatibility/edd/class-aaa-chinese-edd.php';
// require_once AAA_THEME_DIR . 'inc/compatibility/lifterlms/class-aaa-chinese-lifterlms.php';
// require_once AAA_THEME_DIR . 'inc/compatibility/learndash/class-aaa-chinese-learndash.php';
// require_once AAA_THEME_DIR . 'inc/compatibility/class-aaa-chinese-beaver-builder.php';
// require_once AAA_THEME_DIR . 'inc/compatibility/class-aaa-chinese-bb-ultimate-addon.php';
require_once AAA_THEME_DIR . 'inc/compatibility/class-aaa-chinese-contact-form-7.php';
require_once AAA_THEME_DIR . 'inc/compatibility/class-aaa-chinese-visual-composer.php';
require_once AAA_THEME_DIR . 'inc/compatibility/class-aaa-chinese-site-origin.php';
require_once AAA_THEME_DIR . 'inc/compatibility/class-aaa-chinese-gravity-forms.php';
// require_once AAA_THEME_DIR . 'inc/compatibility/class-aaa-chinese-bne-flyout.php';
// require_once AAA_THEME_DIR . 'inc/compatibility/class-aaa-chinese-ubermeu.php';
// require_once AAA_THEME_DIR . 'inc/compatibility/class-aaa-chinese-divi-builder.php';
// require_once AAA_THEME_DIR . 'inc/compatibility/class-aaa-chinese-amp.php';
require_once AAA_THEME_DIR . 'inc/compatibility/class-aaa-chinese-yoast-seo.php';
// require_once AAA_THEME_DIR . 'inc/compatibility/surecart/class-aaa-chinese-surecart.php';
// require_once AAA_THEME_DIR . 'inc/compatibility/class-aaa-chinese-starter-content.php';
// require_once AAA_THEME_DIR . 'inc/compatibility/class-aaa-chinese-buddypress.php';
require_once AAA_THEME_DIR . 'inc/addons/transparent-header/class-aaa-chinese-ext-transparent-header.php';
require_once AAA_THEME_DIR . 'inc/addons/breadcrumbs/class-aaa-chinese-breadcrumbs.php';
require_once AAA_THEME_DIR . 'inc/addons/scroll-to-top/class-aaa-chinese-scroll-to-top.php';
require_once AAA_THEME_DIR . 'inc/addons/heading-colors/class-aaa-chinese-heading-colors.php';
// require_once AAA_THEME_DIR . 'inc/builder/class-aaa-chinese-builder-loader.php';

// Elementor Compatibility requires PHP 5.4 for namespaces.
// if ( version_compare( PHP_VERSION, '5.4', '>=' ) ) {
// 	require_once AAA_THEME_DIR . 'inc/compatibility/class-aaa-chinese-elementor.php';
// 	require_once AAA_THEME_DIR . 'inc/compatibility/class-aaa-chinese-elementor-pro.php';
// 	require_once AAA_THEME_DIR . 'inc/compatibility/class-aaa-chinese-web-stories.php';
// }

// Beaver Themer compatibility requires PHP 5.3 for anonymous functions.
// if ( version_compare( PHP_VERSION, '5.3', '>=' ) ) {
// 	require_once AAA_THEME_DIR . 'inc/compatibility/class-aaa-chinese-beaver-themer.php';
// }

// require_once AAA_THEME_DIR . 'inc/core/markup/class-aaa-chinese-markup.php';

/**
 * Load deprecated functions
 */
// require_once AAA_THEME_DIR . 'inc/core/deprecated/deprecated-filters.php';
// require_once AAA_THEME_DIR . 'inc/core/deprecated/deprecated-hooks.php';
// require_once AAA_THEME_DIR . 'inc/core/deprecated/deprecated-functions.php';
