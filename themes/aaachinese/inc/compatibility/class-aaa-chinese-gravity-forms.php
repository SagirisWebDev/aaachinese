<?php
/**
 * Gravity Forms File.
 *
 * @package AAA Chinese
 */

// If plugin - 'Gravity Forms' not exist then return.
if ( ! class_exists( 'GFForms' ) ) {
	return;
}

/**
 * AAA Chinese Gravity Forms
 */
if ( ! class_exists( 'AAA_Chinese_Gravity_Forms' ) ) {

	/**
	 * AAA Chinese Gravity Forms
	 *
	 * @since 1.0.0
	 */
	class AAA_Chinese_Gravity_Forms {
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
			if ( AAA_Chinese_Dynamic_CSS::aaa_chinese_4_6_0_compatibility() ) {
				return;
			}
			add_action( 'gform_enqueue_scripts', array( $this, 'add_styles' ) );
		}

		/**
		 * Add assets in theme
		 *
		 * @since 1.0.0
		 */
		public function add_styles() {
			$file_prefix = '.min';
			$dir_name    = 'minified';

			if ( is_rtl() ) {
				$file_prefix .= '-rtl';
			}

			$css_file = AAA_THEME_URI . 'assets/css/' . $dir_name . '/compatibility/gravity-forms' . $file_prefix . '.css';

			wp_enqueue_style( 'astra-gravity-forms', $css_file, array(), AAA_THEME_VERSION, 'all' );
		}

	}

}

/**
 * Kicking this off by calling 'get_instance()' method
 */
AAA_Chinese_Gravity_Forms::get_instance();
