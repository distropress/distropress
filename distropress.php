<?php

/**
 * DistroPress
 *
 * Plugin Name:       DistroPress
 * Plugin URI:        https://distropress.org/
 * Description:       Helps to install easilly some external scripts.
 * Version:           1.0.0
 * Author:            Rodrigo Sepúlveda Heerwagen
 * Author URI:        https://lox.cl/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       distropress
 * Domain Path:       /languages
 */

if ( ! defined( 'DISTROPRESS__PLUGIN_DIR' ) )
	define( 'DISTROPRESS__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

if ( ! defined( 'DISTROPRESS__PLUGIN_URL' ) )
	define( 'DISTROPRESS__PLUGIN_URL', plugin_dir_url( __FILE__ ) );

if (
	! defined( 'WPINC' )
	|| strstr( 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST']  . $_SERVER['REQUEST_URI'], DISTROPRESS__PLUGIN_URL . 'scripts/' )
	)
	die;

/* Load functions for the admin panel */
if ( is_admin() )
	require_once( DISTROPRESS__PLUGIN_DIR . 'admin/functions.php' );

/* Load functions for the theme view */
if ( ! is_admin() )
	require_once( DISTROPRESS__PLUGIN_DIR . 'functions.php' );

/* Load functions for every script */
if ( defined( 'DISTROPRESS_SCRIPT' ) ) {
	if ( file_exists( DISTROPRESS__PLUGIN_DIR . 'includes/' . DISTROPRESS_SCRIPT . '-init.php' ) )
		include_once( DISTROPRESS__PLUGIN_DIR . 'includes/' . DISTROPRESS_SCRIPT . '-init.php' );
	if ( file_exists( DISTROPRESS__PLUGIN_DIR . 'includes/' . DISTROPRESS_SCRIPT . '-settings.php' ) )
		include_once( DISTROPRESS__PLUGIN_DIR . 'includes/' . DISTROPRESS_SCRIPT . '-settings.php' );
	if ( file_exists( DISTROPRESS__PLUGIN_DIR . 'includes/' . DISTROPRESS_SCRIPT . '-shutdown.php' ) )
		include_once( DISTROPRESS__PLUGIN_DIR . 'includes/' . DISTROPRESS_SCRIPT . '-shutdown.php' );
}
