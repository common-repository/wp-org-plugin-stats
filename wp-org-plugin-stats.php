<?php
/**
 * Plugin Name: WP Org Plugin Stats
 * Plugin URI:  https://jeweltheme.com
 * Description: Shows WordPress Directory Plugin Statistics on a Good Way
 * Version:     1.0.6
 * Author:      Jewel Theme
 * Author URI:  https://jeweltheme.com
 * Text Domain: wp-org-plugin-stats
 * Domain Path: languages/
 * License:     GPLv3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package wp-org-plugin-stats
 */

/*
 * don't call the file directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	wp_die( esc_html__( 'You can\'t access this page', 'wp-org-plugin-stats' ) );
}

$jltwporgst_plugin_data = get_file_data(
	__FILE__,
	array(
		'Version'     => 'Version',
		'Plugin Name' => 'Plugin Name',
		'Author'      => 'Author',
		'Description' => 'Description',
		'Plugin URI'  => 'Plugin URI',
	),
	false
);

// Define Constants.
if ( ! defined( 'JLTWPORGST' ) ) {
	define( 'JLTWPORGST', $jltwporgst_plugin_data['Plugin Name'] );
}

if ( ! defined( 'JLTWPORGST_VER' ) ) {
	define( 'JLTWPORGST_VER', $jltwporgst_plugin_data['Version'] );
}

if ( ! defined( 'JLTWPORGST_AUTHOR' ) ) {
	define( 'JLTWPORGST_AUTHOR', $jltwporgst_plugin_data['Author'] );
}

if ( ! defined( 'JLTWPORGST_DESC' ) ) {
	define( 'JLTWPORGST_DESC', $jltwporgst_plugin_data['Author'] );
}

if ( ! defined( 'JLTWPORGST_URI' ) ) {
	define( 'JLTWPORGST_URI', $jltwporgst_plugin_data['Plugin URI'] );
}

if ( ! defined( 'JLTWPORGST_DIR' ) ) {
	define( 'JLTWPORGST_DIR', __DIR__ );
}

if ( ! defined( 'JLTWPORGST_FILE' ) ) {
	define( 'JLTWPORGST_FILE', __FILE__ );
}

if ( ! defined( 'JLTWPORGST_SLUG' ) ) {
	define( 'JLTWPORGST_SLUG', dirname( plugin_basename( __FILE__ ) ) );
}

if ( ! defined( 'JLTWPORGST_BASE' ) ) {
	define( 'JLTWPORGST_BASE', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'JLTWPORGST_PATH' ) ) {
	define( 'JLTWPORGST_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
}

if ( ! defined( 'JLTWPORGST_URL' ) ) {
	define( 'JLTWPORGST_URL', trailingslashit( plugins_url( '/', __FILE__ ) ) );
}

if ( ! defined( 'JLTWPORGST_INC' ) ) {
	define( 'JLTWPORGST_INC', JLTWPORGST_PATH . '/Inc/' );
}

if ( ! defined( 'JLTWPORGST_LIBS' ) ) {
	define( 'JLTWPORGST_LIBS', JLTWPORGST_PATH . 'Libs' );
}

if ( ! defined( 'JLTWPORGST_ASSETS' ) ) {
	define( 'JLTWPORGST_ASSETS', JLTWPORGST_URL . 'assets/' );
}

if ( ! defined( 'JLTWPORGST_IMAGES' ) ) {
	define( 'JLTWPORGST_IMAGES', JLTWPORGST_ASSETS . 'images' );
}

if ( ! class_exists( '\\JLTWPORGST\\JLT_WP_Org_Stats' ) ) {
	// Autoload Files.
	include_once JLTWPORGST_DIR . '/vendor/autoload.php';
	// Instantiate JLT_WP_Org_Stats Class.
	include_once JLTWPORGST_DIR . '/class-wp-org-plugin-stats.php';
}