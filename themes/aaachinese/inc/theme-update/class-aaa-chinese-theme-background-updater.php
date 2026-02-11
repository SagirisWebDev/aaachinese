<?php
/**
 * Theme Batch Update
 *
 * @package     AAA Chinese
 *
 * @since 2.1.3
 */

if ( ! class_exists( 'AAA_Chinese_Theme_Background_Updater' ) ) {

	/**
	 * AAA_Chinese_Theme_Background_Updater Class.
	 */
	class AAA_Chinese_Theme_Background_Updater {
		/**
		 * Background update class.
		 *
		 * @var object
		 */
		private static $background_updater;

		/**
		 * DB updates and callbacks that need to be run per version.
		 *
		 * @var array
		 */
		private static $db_updates = array(
			'4.0.0'   => array(
				'aaa_chinese_theme_background_updater_4_0_0',
			),
			'4.0.2'   => array(
				'aaa_chinese_theme_background_updater_4_0_2',
			),
			'4.1.0'   => array(
				'aaa_chinese_theme_background_updater_4_1_0',
			),
			'4.1.4'   => array(
				'aaa_chinese_theme_background_updater_4_1_4',
			),
			'4.1.6'   => array(
				'aaa_chinese_theme_background_updater_4_1_6',
			),
			'4.1.7'   => array(
				'aaa_chinese_theme_background_updater_4_1_7',
			),
			'4.2.0'   => array(
				'aaa_chinese_theme_background_updater_4_2_0',
			),
			'4.2.2'   => array(
				'aaa_chinese_theme_background_updater_4_2_2',
			),
			'4.4.0'   => array(
				'aaa_chinese_theme_background_updater_4_4_0',
			),
			'4.5.0'   => array(
				'aaa_chinese_theme_background_updater_4_5_0',
			),
			'4.5.2'   => array(
				'aaa_chinese_theme_background_updater_4_5_2',
			),
			'4.6.0'   => array(
				'aaa_chinese_theme_background_updater_4_6_0',
			),
			'4.6.2'   => array(
				'aaa_chinese_theme_background_updater_4_6_2',
			),
			'4.6.4'   => array(
				'aaa_chinese_theme_background_updater_4_6_4',
			),
			'4.6.5'   => array(
				'aaa_chinese_theme_background_updater_4_6_5',
			),
			'4.6.6'   => array(
				'aaa_chinese_theme_background_updater_4_6_6',
			),
			'4.6.11'  => array(
				'aaa_chinese_theme_background_updater_4_6_11',
			),
			'4.6.12'  => array(
				'aaa_chinese_theme_background_updater_4_6_12',
			),
			'4.6.14'  => array(
				'aaa_chinese_theme_background_updater_4_6_14',
			),
			'4.7.0'   => array(
				'aaa_chinese_theme_background_updater_4_7_0',
			),
			'4.7.1'   => array(
				'aaa_chinese_theme_background_updater_4_7_1',
			),
			'4.8.0'   => array(
				'aaa_chinese_theme_background_updater_4_8_0',
			),
			'4.8.2'   => array(
				'aaa_chinese_theme_background_updater_4_8_2',
			),
			'4.8.4'   => array(
				'aaa_chinese_theme_background_updater_4_8_4',
			),
			'4.8.7'   => array(
				'aaa_chinese_theme_background_updater_4_8_7',
			),
			'4.8.9'   => array(
				'aaa_chinese_theme_background_updater_4_8_9',
			),
			'4.8.10'  => array(
				'aaa_chinese_theme_background_updater_4_8_10',
			),
			'4.9.0'   => array(
				'aaa_chinese_theme_background_updater_4_9_0',
			),
			'4.10.0'  => array(
				'aaa_chinese_theme_background_updater_4_10_0',
			),
			'4.11.6'  => array(
				'aaa_chinese_theme_background_updater_4_11_6',
			),
			'4.11.12' => array(
				'aaa_chinese_theme_background_updater_4_11_12',
			),
			'4.12.0'  => array(
				'aaa_chinese_theme_background_updater_4_12_0',
			),
			'4.12.2'  => array(
				'aaa_chinese_theme_background_updater_4_12_2',
			),
		);

		/**
		 *  Constructor
		 */
		public function __construct() {

			// Theme Updates.
			if ( is_admin() ) {
				add_action( 'admin_init', array( $this, 'install_actions' ) );
			} else {
				add_action( 'wp', array( $this, 'install_actions' ) );
			}

			// Core Helpers - Batch Processing.
			require_once AAA_THEME_DIR . 'inc/lib/batch-processing/class-aaa-chinese-wp-async-request.php';// phpcs:ignore: WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once AAA_THEME_DIR . 'inc/lib/batch-processing/class-aaa-chinese-wp-background-process.php';// phpcs:ignore: WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once AAA_THEME_DIR . 'inc/theme-update/class-aaa-chinese-theme-wp-background-process.php';// phpcs:ignore: WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound

			self::$background_updater = new AAA_Chinese_Theme_WP_Background_Process();
		}

		/**
		 * Check Cron Status
		 *
		 * Gets the current cron status by performing a test spawn. Cached for one hour when all is well.
		 *
		 * @since 2.3.0
		 *
		 * @return bool true if there is a problem spawning a call to WP-Cron system, else false.
		 */
		public function test_cron() {

			global $wp_version;

			if ( defined( 'DISABLE_WP_CRON' ) && DISABLE_WP_CRON ) {
				return true;
			}

			if ( defined( 'ALTERNATE_WP_CRON' ) && ALTERNATE_WP_CRON ) {
				return true;
			}

			$cached_status = get_transient( 'aaachinese-theme-cron-test-ok' );

			if ( $cached_status ) {
				return false;
			}

			$sslverify     = version_compare( $wp_version, 4.0, '<' );
			$doing_wp_cron = sprintf( '%.22F', microtime( true ) );

			$cron_request = apply_filters(
				'cron_request', // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
				array(
					'url'  => site_url( 'wp-cron.php?doing_wp_cron=' . $doing_wp_cron ),
					'args' => array(
						'timeout'   => 3,
						'blocking'  => true,
						'sslverify' => apply_filters( 'https_local_ssl_verify', $sslverify ), // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
					),
				)
			);

			$result = wp_remote_post( $cron_request['url'], $cron_request['args'] );

			if ( wp_remote_retrieve_response_code( $result ) >= 300 ) {
				return true;
			}

			set_transient( 'aaachinese-theme-cron-test-ok', 1, 3600 );
			return false;
		}

		/**
		 * Install actions when a update button is clicked within the admin area.
		 *
		 * This function is hooked into admin_init to affect admin and wp to affect the frontend.
		 */
		public function install_actions() {

			do_action( 'aaa_chinese_update_initiated', self::$background_updater );

			if ( true === $this->is_new_install() ) {
				self::update_db_version();
				return;
			}

			$fallback    = $this->test_cron();
			$db_migrated = $this->check_if_data_migrated();
			/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$is_queue_running = aaa_chinese_get_option( 'is_theme_queue_running', false );

			$fallback = $db_migrated ? $db_migrated : $fallback;

			if ( $this->needs_db_update() && ! $is_queue_running ) {
				$this->update( $fallback );
			} else {
				if ( ! $is_queue_running ) {
					self::update_db_version();
				}
			}
		}

		/**
		 * Is this a brand new theme install?
		 *
		 * @since 2.1.3
		 * @return bool
		 */
		public function is_new_install() {

			// Get auto saved version number.
			/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$saved_version = aaa_chinese_get_option( 'theme-auto-version', false );

			if ( false === $saved_version ) {
				return true;
			}

			return false;
		}

		/**
		 * Is a DB update needed?
		 *
		 * @since 2.1.3
		 * @return bool
		 */
		private function needs_db_update() {
			$current_theme_version = aaa_chinese_get_option( 'theme-auto-version', null );
			$updates               = $this->get_db_update_callbacks();

			if ( empty( $updates ) ) {
				return false;
			}

			$versions = array_keys( $updates );
			$latest   = $versions[ count( $versions ) - 1 ];

			return ! is_null( $current_theme_version ) && version_compare( $current_theme_version, $latest, '<' );
		}

		/**
		 * Get list of DB update callbacks.
		 *
		 * @since 2.1.3
		 * @return array
		 */
		public function get_db_update_callbacks() {
			return self::$db_updates;
		}

		/**
		 * Check if database is migrated
		 *
		 * @since 2.3.1
		 *
		 * @return true If the database migration should not be run through CRON.
		 */
		public function check_if_data_migrated() {

			$fallback = false;

			$is_db_version_updated = $this->is_db_version_updated();

			/** @psalm-suppress DocblockTypeContradiction */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			if ( ! $is_db_version_updated ) {

				$db_migrated = get_transient( 'aaachinese-theme-db-migrated' );

				if ( ! $db_migrated ) {
					$db_migrated = array();
				}

				array_push( $db_migrated, $is_db_version_updated );
				set_transient( 'aaachinese-theme-db-migrated', $db_migrated, 3600 );

				$db_migrate_count = count( $db_migrated );
				if ( $db_migrate_count >= 5 ) {
					aaa_chinese_delete_option( 'is_theme_queue_running' );
					$fallback = true;
				}
			}
			return $fallback;
		}

		/**
		 * Checks if aaachinese addon version is updated in the database
		 *
		 * @since 2.3.1
		 *
		 * @return true if aaachinese addon version is updated.
		 */
		public function is_db_version_updated() {
			// Get auto saved version number.
			/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$saved_version = aaa_chinese_get_option( 'theme-auto-version', false );

			return version_compare( $saved_version, AAA_THEME_VERSION, '=' );
		}

		/**
		 * Push all needed DB updates to the queue for processing.
		 *
		 * @param bool $fallback Fallback migration.
		 *
		 * @return void
		 */
		private function update( $fallback ) {
			$current_db_version = aaa_chinese_get_option( 'theme-auto-version' );

			if ( count( $this->get_db_update_callbacks() ) > 0 ) {
				foreach ( $this->get_db_update_callbacks() as $version => $update_callbacks ) {
					if ( version_compare( $current_db_version, $version, '<' ) ) {
						foreach ( $update_callbacks as $update_callback ) {
							if ( $fallback ) {
								call_user_func( $update_callback );
							} else {
								self::$background_updater->push_to_queue( $update_callback );
							}
						}
					}
				}
				if ( $fallback ) {
					self::update_db_version();
				} else {
					aaa_chinese_update_option( 'is_theme_queue_running', true );
					self::$background_updater->push_to_queue( 'update_db_version' );
				}
			} else {
				self::$background_updater->push_to_queue( 'update_db_version' );
			}
			self::$background_updater->save()->dispatch();
		}

		/**
		 * Update DB version to current.
		 *
		 * @param string|null $version New AAA Chinese theme version or null.
		 */
		public static function update_db_version( $version = null ) {

			do_action( 'aaa_chinese_theme_update_before' );

			// Get auto saved version number.
			/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$saved_version = aaa_chinese_get_option( 'theme-auto-version', false );

			if ( false === $saved_version ) {

				$saved_version = AAA_THEME_VERSION;

				// Update auto saved version number.
				aaa_chinese_update_option( 'theme-auto-version', AAA_THEME_VERSION );
			}

			// If equals then return.
			if ( version_compare( $saved_version, AAA_THEME_VERSION, '=' ) ) {
				aaa_chinese_update_option( 'is_theme_queue_running', false );
				return;
			}

			// Not have stored?
			if ( empty( $saved_version ) ) {

				// Get old version.
				$theme_version = get_option( '_aaa_chinese_auto_version', AAA_THEME_VERSION );

				// Remove option.
				delete_option( '_aaa_chinese_auto_version' );

			} else {

				// Get latest version.
				$theme_version = AAA_THEME_VERSION;
			}

			// Update auto saved version number.
			aaa_chinese_update_option( 'theme-auto-version', $theme_version );

			aaa_chinese_update_option( 'is_theme_queue_running', false );

			// Update variables.
			AAA_Chinese_Theme_Options::refresh();

			delete_transient( 'aaachinese-addon-db-migrated' );

			do_action( 'aaa_chinese_theme_update_after' );
		}
	}
}

/**
 * Kicking this off by creating a new instance
 */
new AAA_Chinese_Theme_Background_Updater();
