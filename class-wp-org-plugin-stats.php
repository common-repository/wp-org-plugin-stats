<?php
namespace JLTWPORGST;

use JLTWPORGST\Libs\Assets;
use JLTWPORGST\Libs\Helper;
use JLTWPORGST\Libs\Featured;
use JLTWPORGST\Inc\Classes\Recommended_Plugins;
use JLTWPORGST\Inc\Classes\Notifications\Notifications;
use JLTWPORGST\Inc\Classes\Pro_Upgrade;
use JLTWPORGST\Inc\Classes\Row_Links;
use JLTWPORGST\Inc\Classes\Upgrade_Plugin;
use JLTWPORGST\Inc\Classes\Feedback;
use JLTWPORGST\Inc\Classes\Shortcode;

/**
 * Main Class
 *
 * @wp-org-plugin-stats
 * Jewel Theme <support@jeweltheme.com>
 * @version     1.0.6
 */

// No, Direct access Sir !!!
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * JLT_WP_Org_Stats Class
 */
if ( ! class_exists( '\JLTWPORGST\JLT_WP_Org_Stats' ) ) {

	/**
	 * Class: JLT_WP_Org_Stats
	 */
	final class JLT_WP_Org_Stats {

		const VERSION            = JLTWPORGST_VER;
		private static $instance = null;

		/**
		 * what we collect construct method
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function __construct() {
			$this->includes();
			add_action( 'plugins_loaded', array( $this, 'jltwporgst_plugins_loaded' ), 999 );
			// Body Class.
			add_filter( 'admin_body_class', array( $this, 'jltwporgst_body_class' ) );
			// This should run earlier .
			// add_action( 'plugins_loaded', [ $this, 'jltwporgst_maybe_run_upgrades' ], -100 ); .
		}

		/**
		 * plugins_loaded method
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function jltwporgst_plugins_loaded() {
			$this->jltwporgst_activate();
		}

		/**
		 * Version Key
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public static function plugin_version_key() {
			return Helper::jltwporgst_slug_cleanup() . '_version';
		}

		/**
		 * Activation Hook
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public static function jltwporgst_activate() {
			$current_jltwporgst_version = get_option( self::plugin_version_key(), null );

			if ( get_option( 'jltwporgst_activation_time' ) === false ) {
				update_option( 'jltwporgst_activation_time', strtotime( 'now' ) );
			}

			if ( is_null( $current_jltwporgst_version ) ) {
				update_option( self::plugin_version_key(), self::VERSION );
			}

			$allowed = get_option( Helper::jltwporgst_slug_cleanup() . '_allow_tracking', 'no' );

			// if it wasn't allowed before, do nothing .
			if ( 'yes' !== $allowed ) {
				return;
			}
			// re-schedule and delete the last sent time so we could force send again .
			$hook_name = Helper::jltwporgst_slug_cleanup() . '_tracker_send_event';
			if ( ! wp_next_scheduled( $hook_name ) ) {
				wp_schedule_event( time(), 'weekly', $hook_name );
			}
		}


		/**
		 * Add Body Class
		 *
		 * @param [type] $classes .
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function jltwporgst_body_class( $classes ) {
			$classes .= ' wp-org-plugin-stats ';
			return $classes;
		}

		/**
		 * Run Upgrader Class
		 *
		 * @return void
		 */
		public function jltwporgst_maybe_run_upgrades() {
			if ( ! is_admin() && ! current_user_can( 'manage_options' ) ) {
				return;
			}

			// Run Upgrader .
			$upgrade = new Upgrade_Plugin();

			// Need to work on Upgrade Class .
			if ( $upgrade->if_updates_available() ) {
				$upgrade->run_updates();
			}
		}

		/**
		 * Include methods
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function includes() {
			new Assets();
			new Recommended_Plugins();
			new Row_Links();
			new Pro_Upgrade();
			new Notifications();
			new Featured();
			new Feedback();
			new Shortcode();
		}


		/**
		 * Initialization
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function jltwporgst_init() {
			$this->jltwporgst_load_textdomain();
		}


		/**
		 * Text Domain
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function jltwporgst_load_textdomain() {
			$domain = 'wp-org-plugin-stats';
			$locale = apply_filters( 'jltwporgst_plugin_locale', get_locale(), $domain );

			load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
			load_plugin_textdomain( $domain, false, dirname( JLTWPORGST_BASE ) . '/languages/' );
		}
		
		
		

		/**
		 * Returns the singleton instance of the class.
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof JLT_WP_Org_Stats ) ) {
				self::$instance = new JLT_WP_Org_Stats();
				self::$instance->jltwporgst_init();
			}

			return self::$instance;
		}
	}

	// Get Instant of JLT_WP_Org_Stats Class .
	JLT_WP_Org_Stats::get_instance();
}