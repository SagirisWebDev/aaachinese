<?php
/**
 * Schema markup.
 *
 * @package     AAA Chinese
 *
 * @since       AAA Chinese 2.1.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * AAA Chinese CreativeWork Schema Markup.
 *
 * @since 2.1.3
 */
class AAA_Chinese_WPHeader_Schema extends AAA_Chinese_Schema {
	/**
	 * Setup schema
	 *
	 * @since 2.1.3
	 */
	public function setup_schema() {

		if ( true !== $this->schema_enabled() ) {
			return false;
		}

		add_filter( 'aaa_chinese_attr_header', array( $this, 'wpheader_Schema' ) );
	}

	/**
	 * Update Schema markup attribute.
	 *
	 * @param  array $attr An array of attributes.
	 *
	 * @return array       Updated embed markup.
	 */
	public function wpheader_Schema( $attr ) {
		$attr['itemtype']  = 'https://schema.org/WPHeader';
		$attr['itemscope'] = 'itemscope';
		$attr['itemid']    = '#masthead';

		return $attr;
	}

	/**
	 * Enabled schema
	 *
	 * @since 2.1.3
	 */
	protected function schema_enabled() {
		return apply_filters( 'aaa_chinese_wpheader_schema_enabled', parent::schema_enabled() );
	}

}

new AAA_Chinese_WPHeader_Schema();
