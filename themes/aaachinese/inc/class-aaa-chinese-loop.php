<?php
/**
 * AAA Chinese Loop
 *
 * @package AAA Chinese
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AAA_Chinese_Loop' ) ) {

	/**
	 * AAA_Chinese_Loop
	 *
	 * @since 1.0.0
	 */
	class AAA_Chinese_Loop {
		/**
		 * Instance
		 *
		 * @since 1.0.0
		 *
		 * @var object Class object.
		 */
		private static $instance;

		/**
		 * Initiator
		 *
		 * @since 1.0.0
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
		 * @since 1.0.0
		 */
		public function __construct() {
			// Loop.
			add_action( 'aaa_chinese_content_loop', array( $this, 'loop_markup' ) );
			add_action( 'aaa_chinese_content_page_loop', array( $this, 'loop_markup_page' ) );

			// Template Parts.
			add_action( 'aaa_chinese_page_template_parts_content', array( $this, 'template_parts_page' ) );
			add_action( 'aaa_chinese_page_template_parts_content', array( $this, 'template_parts_comments' ), 15 );
			add_action( 'aaa_chinese_template_parts_content', array( $this, 'template_parts_post' ) );
			add_action( 'aaa_chinese_template_parts_content', array( $this, 'template_parts_search' ) );
			add_action( 'aaa_chinese_template_parts_content', array( $this, 'template_parts_default' ) );
			add_action( 'aaa_chinese_template_parts_content', array( $this, 'template_parts_comments' ), 15 );

			// Template None.
			add_action( 'aaa_chinese_template_parts_content_none', array( $this, 'template_parts_none' ) );
			add_action( 'aaa_chinese_template_parts_content_none', array( $this, 'template_parts_404' ) );
			add_action( 'aaa_chinese_404_content_template', array( $this, 'template_parts_404' ) );

			// Content top and bottom.
			add_action( 'aaa_chinese_template_parts_content_top', array( $this, 'template_parts_content_top' ) );
			add_action( 'aaa_chinese_template_parts_content_bottom', array( $this, 'template_parts_content_bottom' ) );

			// Add closing and ending div 'aaachinese-row'.
			add_action( 'aaa_chinese_template_parts_content_top', array( $this, 'aaa_chinese_templat_part_wrap_open' ), 25 );
			add_action( 'aaa_chinese_template_parts_content_bottom', array( $this, 'aaa_chinese_templat_part_wrap_close' ), 5 );

			add_action( 'wp', array( $this, 'comment_layout_adjustments' ) );
		}

		/**
		 * Template part none
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function template_parts_none() {
			if ( is_archive() || is_search() ) {
				get_template_part( 'template-parts/content', 'none' );
			}
		}

		/**
		 * Template part 404
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function template_parts_404() {
			if ( is_404() ) {
				get_template_part( 'template-parts/content', '404' );
			}
		}

		/**
		 * Template part page
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function template_parts_page() {
			get_template_part( 'template-parts/content', 'page' );
		}

		/**
		 * Template part single
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function template_parts_post() {
			if ( is_single() ) {
				get_template_part( 'template-parts/content', 'single' );
			}
		}

		/**
		 * Template part search
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function template_parts_search() {
			if ( is_search() ) {
				get_template_part( 'template-parts/content', 'blog' );
			}
		}

		/**
		 * Template part comments
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function template_parts_comments() {
			if ( is_single() || is_page() ) {
				// If comments are open or we have at leaaachinese one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
			}
		}

		/**
		 * Template part default
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function template_parts_default() {
			if ( ! is_page() && ! is_single() && ! is_search() && ! is_404() ) {
				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				get_template_part( 'template-parts/content', aaa_chinese_get_post_format() );
			}
		}

		/**
		 * Loop Markup for content page
		 *
		 * @since 1.0.0
		 */
		public function loop_markup_page() {
			$this->loop_markup( true );
		}

		/**
		 * Template part loop
		 *
		 * @param  bool $is_page Loop outputs different content action for content page and default content.
		 *         if is_page is set to true - do_action( 'aaa_chinese_page_template_parts_content' ); is added
		 *         if is_page is false - do_action( 'aaa_chinese_template_parts_content' ); is added.
		 * @since 1.0.0
		 * @return void
		 */
		public function loop_markup( $is_page = false ) {
			?>
			<main id="main" class="site-main">
				<?php
				if ( have_posts() ) {
					do_action( 'aaa_chinese_template_parts_content_top' );

					while ( have_posts() ) {
						the_post();

						if ( true === $is_page ) {
							do_action( 'aaa_chinese_page_template_parts_content' );
						} else {
							do_action( 'aaa_chinese_template_parts_content' );
						}
					}
					do_action( 'aaa_chinese_template_parts_content_bottom' );
				} else {
					do_action( 'aaa_chinese_template_parts_content_none' );
				}
				?>
			</main><!-- #main -->
			<?php
		}

		/**
		 * Template part content top
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function template_parts_content_top() {
			if ( is_archive() ) {
				aaa_chinese_content_while_before();
			}
		}

		/**
		 * Template part content bottom
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function template_parts_content_bottom() {
			if ( is_archive() ) {
				aaa_chinese_content_while_after();
			}
		}

		/**
		 * Add wrapper div 'aaachinese-row' for AAA Chinese template part.
		 *
		 * @since  1.0.0
		 * @return void
		 */
		public function aaa_chinese_templat_part_wrap_open() {
			if ( is_archive() || is_search() || is_home() ) {
				echo '<div class="aaachinese-row">';
			}
		}

		/**
		 * Add closing wrapper div for 'aaachinese-row' after AAA Chinese template part.
		 *
		 * @since  1.0.0
		 * @return void
		 */
		public function aaa_chinese_templat_part_wrap_close() {
			if ( is_archive() || is_search() || is_home() ) {
				echo '</div>';
			}
		}

		/**
		 * Comment layout adjustments
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function comment_layout_adjustments() {
			// Bail early if it is the WooCommerce product page, as on the WooCommerce product page reviews are shown in tabs.
			if ( class_exists( 'WooCommerce' ) && 'product' === get_post_type() ) {
				return;
			}
			$comments_section_placement = aaa_chinese_get_option( 'comments-box-placement', '' );
			if ( '' !== $comments_section_placement ) {
				remove_action( 'aaa_chinese_page_template_parts_content', array( $this, 'template_parts_comments' ), 15 );
				remove_action( 'aaa_chinese_template_parts_content', array( $this, 'template_parts_comments' ), 15 );

				if ( 'outside' === $comments_section_placement ) {
					// Pop out of the content.
					add_action( 'aaa_chinese_content_after', array( $this, 'template_parts_comments' ), 15 );
				} else {
					// Insert it in the content.
					add_action( 'aaa_chinese_entry_bottom', array( $this, 'template_parts_comments' ), 12 );
				}
			}
		}
	}

	/**
	 * Initialize class object with 'get_instance()' method
	 */
	AAA_Chinese_Loop::get_instance();

}
