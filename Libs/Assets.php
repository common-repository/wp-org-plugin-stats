<?php
namespace JLTWPORGST\Libs;

// No, Direct access Sir !!!
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Assets' ) ) {

	/**
	 * Assets Class
	 *
	 * Jewel Theme <support@jeweltheme.com>
	 * @version     1.0.6
	 */
	class Assets {

		/**
		 * Constructor method
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function __construct() {
			add_action( 'admin_enqueue_scripts', array( $this, 'jltwporgst_admin_enqueue_scripts' ), 100 );
			add_action( 'admin_print_scripts', array( $this, 'admin_inline_js' ) );
		}


		/**
		 * Get environment mode
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function get_mode() {
			return defined( 'WP_DEBUG' ) && WP_DEBUG ? 'development' : 'production';
		}


		// Editor Button Icon
		function admin_inline_js(){ ?>
			<style>
				i.mce-ico.mce-i-faq-icon {
					background-image: url('<?php echo plugins_url( 'icon.png', __FILE__ ); ?>');
				}
			</style>
			<?php
		}
		

		/**
		 * Enqueue Scripts
		 *
		 * @method admin_enqueue_scripts()
		 */
		public function jltwporgst_admin_enqueue_scripts() {

			// JS Files .
			wp_enqueue_script( 'wp-org-plugin-stats-admin', JLTWPORGST_ASSETS . 'js/wp-org-plugin-stats-admin.js', array( 'jquery' ), JLTWPORGST_VER, true );
			wp_localize_script(
				'wp-org-plugin-stats-admin',
				'JLTWPORGSTCORE',
				array(
					'admin_ajax'        => admin_url( 'admin-ajax.php' ),
					'recommended_nonce' => wp_create_nonce( 'jltwporgst_recommended_nonce' ),
				)
			);
		}
	}
}