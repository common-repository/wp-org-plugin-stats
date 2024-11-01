<?php
namespace JLTWPORGST\Inc\Classes;

class Shortcode {


	/**
	 * Constructor
	 */
	public function __construct() {	

		add_action( 'cron_save_org_downloads', array( $this, 'cron_save_org_downloads' ));


		// Schedule cron
		if ( ! wp_next_scheduled( 'cron_save_org_downloads' ) ) {
			wp_schedule_event( 1407110400, 'daily', 'cron_save_org_downloads' ); // 1407110400 is 08 / 4 / 2014 @ 0:0:0 UTC
		}

		// Register the shortcode [wpops slug='wp-awesome-faq' field='version']
		add_shortcode( 'wpops', array( $this, 'shortcode' ) );

		// Widget Ready Shortcode
		add_filter( 'widget_text', 'do_shortcode' );


		// Editor Button
		add_action( 'admin_head', array( $this, 'jltwporgst_tinymce_button' ) );
	}


	function jltwporgst_tinymce_button() {
		global $typenow;

		// check user permissions
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
			return;
		}

		// verify the post type
		if ( ! in_array( $typenow, array( 'post', 'page' ) ) ) {
			return;
		}

		// check if WYSIWYG is enabled
		if ( get_user_option( 'rich_editing' ) == 'true' ) {
			add_filter( 'mce_external_plugins', 'jltwporgst_tinymce_plugin' );
			add_filter( 'mce_buttons', 'jltwporgst_register_tinymce_button' );
		}
	}


	// Editor Button JS include
	function jltwporgst_tinymce_plugin( $plugin_array ) {
		$plugin_array['jltwpost_button'] = plugins_url( '/inc/js/editor-button.js', __FILE__ );
		return $plugin_array;
	}


	// Register Editor Button
	function jeweltheme_wp_org_plugin_stats_register_tinymce_button( $buttons ) {
		array_push( $buttons, 'jltwpost_button' );
		return $buttons;
	}	

	/**
	 * @param string $slug The WordPress.org slug of the plugin
	 * @return StdClass
	 */
	public function get_plugin_info( $slug ) {

		// Create a empty array with variable name different based on plugin slug
		$transient_name = 'wpops' . $slug;

		/**
		 * Check if transient with the plugin data exists
		 */
		$info = get_transient( $transient_name );

		if ( empty( $info ) ) {

			/**
			 * Connect to WordPress.org using plugins_api
			 * About plugins_api -
			 * http://wp.tutsplus.com/tutorials/plugins/communicating-with-the-wordpress-org-plugin-api/
			 */
			require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
			$info = plugins_api( 'plugin_information', array( 'slug' => $slug ) );


			// Check for errors with the data returned from WordPress.org
			if ( ! $info or is_wp_error( $info ) ) {
				return null;
			}


			// Set a transient with the plugin data
			// Use Options API with auto update cron job in next version.
			set_transient( $transient_name, $info, 1 * DAY_IN_SECONDS );
		}

		return $info;
	}


	function js_get_plugin_downloads() {
		$url 		= 'https://api.wordpress.org/plugins/info/1.0/';
		$response 	= wp_remote_post( $url, array(
			'body'		=> array(
				'action'	=> 'query_plugins',
				'request'	=> serialize( (object) array(
					'per_page'	=> 100,
					'action'	=> 'query_plugins',
					'author'	=> 'litonice13',
					'fields'	=> array(
						'downloaded'			=> true,
						'rating'				=> false,
						'description'			=> false,
						'short_description' 	=> false,
						'donate_link'			=> false,
						'tags'					=> false,
						'sections'				=> false,
						'homepage'				=> false,
						'added'					=> false,
						'last_updated'			=> false,
						'compatibility'			=> false,
						'tested'				=> false,
						'requires'				=> false,
						'downloadlink'			=> false,
					)
				) ),
			),
		) );
		$response = unserialize( $response['body'] );
		return isset( $response->plugins ) ? $response->plugins : array();
	}

	// Execute cron
	public function cron_save_org_downloads() {
		$plugins = js_get_plugin_downloads();
		update_option( 'worg_plugin_request', $plugins );
	}
	


	/**
	 * Get a specific field
	 *
	 * @param string $slug The WordPress.org slug of the plugin
	 * @param string $field The field you want to retrieve
	 *
	 * @return string
	 */
	public function get_plugin_field( $slug, $field ) {

		// Fetch info
		$info = $this->get_plugin_info( $slug );

		if( ! is_object( $info ) || ! property_exists( $info, $field ) ) {
			return '';
		}

		return $info->{$field};
	}

	/**
	 * @param array $atts
	 *
	 * @return string
	 */
	public function shortcode( $atts = array() ) {

		// get our variable from $atts
		$atts = shortcode_atts( array(
			'slug' => '', //foo is a default value
			'field' => ''
		), $atts );

		/**
		 * Slug & field must both be givens
		 */
		if ( '' === $atts['slug'] || '' === $atts['field'] ) {
			return '';
		}

		// Sanitize slug attribute
		$slug = sanitize_title( $atts['slug'] );

		// Sanitize field attribute
		$field = sanitize_title( $atts['field'] );

		return $this->get_plugin_field( $slug, $field );
	}


}