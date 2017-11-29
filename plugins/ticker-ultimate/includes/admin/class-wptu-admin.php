<?php
/**
 * Admin Class
 *
 * Handles the Admin side functionality of plugin
 *
 * @package Ticker Ultimate
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

class Wptu_Admin {
	
	function __construct() {
		// Action to add metabox
		add_action( 'add_meta_boxes', array($this, 'wptu_metabox') );

		// Action to save metabox
		add_action( 'save_post', array($this,'wptu_save_metabox_value') );
	}

	/**
	 * Ticker Post Settings Metabox
	 * 
	 * @package Ticker Ultimate
	 * @since 1.0.0
	 */
	function wptu_metabox() {
		add_meta_box( 'wptu-post-sett', __( 'Ticker Settings - Settings', 'ticker-ultimate' ), array($this, 'wptu_ticker_metabox_sett_mb_content'), WPTU_POST_TYPE, 'normal', 'high' );
	}

	/**
	 * News Post Settings Metabox HTML
	 * 
	 * @package Ticker Ultimate
	 * @since 1.0.0
	 */
	function wptu_ticker_metabox_sett_mb_content() {
		include_once( WPTU_DIR .'/includes/admin/metabox/wptu-post-sett-metabox.php');
	}

	/**
	 * Function to save metabox values
	 * 
	 * @package Ticker Ultimate
	 * @since 1.0.0
	 */
	function wptu_save_metabox_value( $post_id ) {

		global $post_type;
		
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )                	// Check Autosave
		|| ( ! isset( $_POST['post_ID'] ) || $post_id != $_POST['post_ID'] )  	// Check Revision
		|| ( $post_type !=  WPTU_POST_TYPE ) )              					// Check if current post type is supported.
		{
		  return $post_id;
		}

		$prefix = WPTU_META_PREFIX; // Taking metabox prefix

		// Taking variables
		$read_more_link = isset($_POST[$prefix.'more_link']) ? wptu_slashes_deep(trim($_POST[$prefix.'more_link'])) : '';

		update_post_meta($post_id, $prefix.'more_link', $read_more_link);
	}

}
$wptu_admin = new Wptu_Admin();