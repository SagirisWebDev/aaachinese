<?php
/**
 * Site Origin Compatibility File.
 *
 * @package AAA Chinese
 */

// If plugin - 'Site Origin' not exist then return.
if ( ! class_exists( 'SiteOrigin_Panels_Settings' ) ) {
	return;
}

/**
 * AAA Chinese Site Origin Compatibility
 */
if ( ! class_exists( 'AAA_Chinese_Site_Origin' ) ) {

	/**
	 * AAA Chinese Site Origin Compatibility
	 *
	 * @since 1.0.0
	 */
	class AAA_Chinese_Site_Origin {
		/**
		 * Member Variable
		 *
		 * @var object instance
		 */
		private static $instance;

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
		 * Constructor
		 */
		public function __construct() {
			add_filter( 'aaa_chinese_theme_assets', array( $this, 'add_styles' ) );
		}

		/**
		 * Add assets in theme
		 *
		 * @param array $assets list of theme assets (JS & CSS).
		 * @return array List of updated assets.
		 * @since 1.0.0
		 */
		public function add_styles( $assets ) {
			$assets['css']['astra-site-origin'] = 'compatibility/site-origin';
			return $assets;
		}

	}

}

/**
 * Kicking this off by calling 'get_instance()' method
 */
AAA_Chinese_Site_Origin::get_instance();
