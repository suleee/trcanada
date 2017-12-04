<?php
/*
Plugin Name: Ticker Ultimate
Plugin URL: https://www.wponlinesupport.com/
Text Domain: ticker-ultimate
Domain Path: /languages/
Description: Ultimate Ticker Plugin
Version: 1.0.0
Author: WP Online Support
Author URI: http://www.wponlinesupport.com/
Contributors: WP Online Support
*/

if( !defined( 'WPTU_VERSION' ) ) {
    define( 'WPTU_VERSION', '1.0.0' ); // Version of plugin
}
if( !defined( 'WPTU_POST_TYPE' ) ) {
    define( 'WPTU_POST_TYPE', 'wptu_ticker' ); // Plugin post type
}
if( !defined( 'WPTU_DIR' ) ) {
    define( 'WPTU_DIR', dirname( __FILE__ ) ); // Plugin dir
}
if( !defined( 'WPTU_URL' ) ) {
    define( 'WPTU_URL', plugin_dir_url( __FILE__ ) ); // Plugin url
}
if( !defined( 'WPTU_PLUGIN_BASENAME' ) ) {
    define( 'WPTU_PLUGIN_BASENAME', plugin_basename( __FILE__ ) ); // Plugin base name
}
if( !defined( 'WPTU_CAT' ) ) {
    define( 'WPTU_CAT', 'wptu-ticker-category' ); // Plugin category name
}
if( !defined( 'WPTU_META_PREFIX' ) ) {
    define( 'WPTU_META_PREFIX', '_wptu_' ); // Plugin metabox prefix
}

/**
 * Load Text Domain
 * This gets the plugin ready for translation
 * 
 * @package Ticker Ultimate
 * @since 1.0.0
 */
function wptu_ticker_load_textdomain() {
    load_plugin_textdomain( 'ticker-ultimate', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
}
add_action('plugins_loaded', 'wptu_ticker_load_textdomain');

/**
 * Activation Hook
 * 
 * Register plugin activation hook.
 * 
 * @package Ticker Ultimate
 * @since 1.0.0
 */
register_activation_hook( __FILE__, 'wptu_ticker_install' );

/**
 * Plugin Activation Function
 * Does the initial setup, sets the default values for the plugin options
 * 
 * @package Ticker Ultimate
 * @since 1.0.0
 */
function wptu_ticker_install() {
    
    // Custom post type and taxonomy function
    wptu_register_post_type();
    wptu_register_taxonomies();
    
    // IMP to call to generate new rules
    flush_rewrite_rules();
}

// Functions file
require_once( WPTU_DIR . '/includes/wptu-functions.php' );

// Plugin Post type file
require_once( WPTU_DIR . '/includes/wptu-post-types.php' );

// Scripts
require_once( WPTU_DIR . '/includes/class-wptu-script.php' );

// Shortcode
require_once( WPTU_DIR . '/includes/shortcode/wptu-ticker-shortcode.php');

//admin file
require_once( WPTU_DIR . '/includes/admin/class-wptu-admin.php');