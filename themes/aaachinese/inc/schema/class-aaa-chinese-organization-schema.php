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
class AAA_Chinese_Organization_Schema extends AAA_Chinese_Schema {
	/**
	 * Setup schema
	 *
	 * @since 2.1.3
	 */
	public function setup_schema() {

		if ( true !== $this->schema_enabled() ) {
			return false;
		}

		add_filter( 'aaa_chinese_attr_site-identity', array( $this, 'organization_Schema' ) );
		add_filter( 'aaa_chinese_attr_site-title', array( $this, 'site_title_attr' ) );
		add_filter( 'aaa_chinese_attr_site-title-link', array( $this, 'site_title_link_attr' ) );
		add_filter( 'aaa_chinese_attr_site-title-custom-link', array( $this, 'site_title_custom_link_attr' ) );
		add_filter( 'aaa_chinese_attr_site-title-sticky-custom-link', array( $this, 'site_title_sticky_custom_link_attr' ) );
		add_filter( 'aaa_chinese_attr_site-title-none-sticky-custom-link', array( $this, 'site_title_none_sticky_custom_link_attr' ) );
		add_filter( 'aaa_chinese_attr_site-title-sticky-custom-logo-link', array( $this, 'site_title_sticky_custom_logo_link_attr' ) );
	}

	/**
	 * Update Schema markup attribute.
	 *
	 * @param  array $attr An array of attributes.
	 *
	 * @return array       Updated embed markup.
	 */
	public function organization_Schema( $attr ) {
		$attr['itemtype']  = 'https://schema.org/Organization';
		$attr['itemscope'] = 'itemscope';

		return $attr;
	}

	/**
	 * Update Schema markup attribute.
	 *
	 * @param  array $attr An array of attributes.
	 *
	 * @return array       Updated embed markup.
	 */
	public function site_title_attr( $attr ) {
		$attr['itemprop'] = 'name';

		return $attr;
	}

	/**
	 * Update Schema markup attribute.
	 *
	 * @param  array $attr An array of attributes.
	 *
	 * @return array       Updated embed markup.
	 */
	public function site_title_link_attr( $attr ) {
		$attr['itemprop'] = 'url';
		$attr['class']    = '';

		return $attr;
	}

	/**
	 * Update Schema markup attribute.
	 *
	 * @param  array $attr An array of attributes.
	 *
	 * @return array       Updated embed markup.
	 */
	public function site_title_custom_link_attr( $attr ) {
		$attr['itemprop'] = 'url';
		$attr['class']    = '';

		return $attr;
	}

	/**
	 * Update Schema markup attribute.
	 *
	 * @param  array $attr An array of attributes.
	 *
	 * @return array       Updated embed markup.
	 */
	public function site_title_sticky_custom_link_attr( $attr ) {
		$attr['itemprop'] = 'url';
		$attr['class']    = '';

		return $attr;
	}

	/**
	 * Update Schema markup attribute.
	 *
	 * @param  array $attr An array of attributes.
	 *
	 * @return array       Updated embed markup.
	 */
	public function site_title_none_sticky_custom_link_attr( $attr ) {
		$attr['itemprop'] = 'url';
		$attr['class']    = '';

		return $attr;
	}

	/**
	 * Update Schema markup attribute.
	 *
	 * @param  array $attr An array of attributes.
	 *
	 * @return array       Updated embed markup.
	 */
	public function site_title_sticky_custom_logo_link_attr( $attr ) {
		$attr['itemprop'] = 'url';
		$attr['class']    = '';

		return $attr;
	}

	/**
	 * Enabled schema
	 *
	 * @since 2.1.3
	 */
	protected function schema_enabled() {
		return apply_filters( 'aaa_chinese_organization_schema_enabled', parent::schema_enabled() );
	}

}

new AAA_Chinese_Organization_Schema();
