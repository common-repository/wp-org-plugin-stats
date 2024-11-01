<?php
namespace JLTWPORGST\Libs;

// No, Direct access Sir !!!
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RowLinks' ) ) {
	/**
	 * Row Links Class
	 *
	 * Jewel Theme <support@jeweltheme.com>
	 */
	class RowLinks {


		/**
		 * Constructor
		 */
		public function __construct() {
			add_filter( 'plugin_action_links_' . JLTWPORGST_BASE, array( $this, 'plugin_action_links' ) );
		}

		/**
		 * Plugin action links
		 *
		 * @param [type] $links .
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function plugin_action_links( $links ) {
			return $links;
		}
	}
}