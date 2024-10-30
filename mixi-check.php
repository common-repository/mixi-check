<?php
/*
Plugin Name: mixi Check for Sharedaddy
Description: Sharing to mixi Check.
Version: 1.1
Author: nemooon
Author URI: http://nemooon.jp/
Plugin URI: http://nemooon.jp/plugins/mixi-check/
*/

define( 'MIXI_CHECK_PLUGIN_NAME', basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ) );
define( 'MIXI_CHECK_PLUGIN_VERSION', 1.0 );

require_once 'mixi-check.class.php';

// Direct
if ( !function_exists( 'add_action' ) ) {
	$page = $_GET[ 'page' ];
	$pages = array(
		'usage' => dirname( __FILE__ ).'/page/usage.php',
	);
	if ( isset( $pages[ $page ] ) && file_exists( $pages[ $page ] ) )
		include $pages[ $page ];
	else
		echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
	exit;
}

load_plugin_textdomain( 'mixi-check', false, basename( dirname( __FILE__ ) ).'/languages/' );

// Only run if PHP5
if ( version_compare( phpversion(), '5.0', '>=' ) ) {
	// Add style
	add_action( 'wp_head', array( 'MixiCheck', 'add_style' ) );
	
	// Add sharing service
	require_once plugin_dir_path( __FILE__ ).'sharing-sources.php';
	add_action( 'load-settings_page_sharing', array( 'MixiCheck', 'add_admin_style' ) );
	add_filter( 'sharing_services', array( 'MixiCheck', 'add_sharing_service' ) );
	add_filter( 'language_attributes', array( 'MixiCheck', 'ogp_namespace' ) );
	
	// plugin page
	add_action( 'plugin_action_links_'.MIXI_CHECK_PLUGIN_NAME, array( 'MixiCheck', 'add_plugin_links' ), 10, 4 );
	add_filter( 'plugin_row_meta', array( 'MixiCheck', 'add_plugin_meta' ), 10, 2 );
}
