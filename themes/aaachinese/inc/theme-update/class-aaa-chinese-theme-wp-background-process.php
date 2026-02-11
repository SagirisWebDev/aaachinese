<?php
/**
 * Database Background Process
 *
 * @package AAA Chinese
 * @since 2.1.3
 */

if ( class_exists( 'AAA_Chinese_WP_Background_Process' ) ) {

	/**
	 * Database Background Process
	 *
	 * @since 2.1.3
	 */
	class AAA_Chinese_Theme_WP_Background_Process extends AAA_Chinese_WP_Background_Process {
		/**
		 * Database Process
		 *
		 * @var string
		 */
		protected $action = 'aaa_chinese_theme_db_migration';

		/**
		 * Task
		 *
		 * Override this method to perform any actions required on each
		 * queue item. Return the modified item for further processing
		 * in the next pass through. Or, return false to remove the
		 * item from the queue.
		 *
		 * @since 2.1.3
		 *
		 * @param object $process Queue item object.
		 * @return mixed
		 */
		protected function task( $process ) {

			do_action( 'aaa_chinese_batch_process_task_' . $process, $process );

			if ( function_exists( $process ) ) {
				call_user_func( $process );
			}

			if ( 'update_db_version' === $process ) {
				AAA_Chinese_Theme_Background_Updater::update_db_version();
			}

			return false;
		}

		/**
		 * Complete
		 *
		 * Override if applicable, but ensure that the below actions are
		 * performed, or, call parent::complete().
		 *
		 * @since 2.1.3
		 */
		protected function complete() {
			do_action( 'aaa_chinese_database_migration_complete' );
			parent::complete();
		}

	}

}
