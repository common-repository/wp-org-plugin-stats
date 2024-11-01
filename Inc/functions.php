<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * @version       1.0.0
 * @package       JLT_WP_Org_Stats
 * @license       Copyright JLT_WP_Org_Stats
 */

if ( ! function_exists( 'jltwporgst_option' ) ) {
	/**
	 * Get setting database option
	 *
	 * @param string $section default section name jltwporgst_general .
	 * @param string $key .
	 * @param string $default .
	 *
	 * @return string
	 */
	function jltwporgst_option( $section = 'jltwporgst_general', $key = '', $default = '' ) {
		$settings = get_option( $section );

		return isset( $settings[ $key ] ) ? $settings[ $key ] : $default;
	}
}

if ( ! function_exists( 'jltwporgst_exclude_pages' ) ) {
	/**
	 * Get exclude pages setting option data
	 *
	 * @return string|array
	 *
	 * @version 1.0.0
	 */
	function jltwporgst_exclude_pages() {
		return jltwporgst_option( 'jltwporgst_triggers', 'exclude_pages', array() );
	}
}

if ( ! function_exists( 'jltwporgst_exclude_pages' ) ) {
	/**
	 * Get exclude pages except setting option data
	 *
	 * @return string|array
	 *
	 * @version 1.0.0
	 */
	function jltwporgst_exclude_pages_except() {
		return jltwporgst_option( 'jltwporgst_triggers', 'exclude_pages_except', array() );
	}
}