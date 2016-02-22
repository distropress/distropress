<?php

if ( ! defined( 'WPINC' ) )
	die;

/* DelTree-like function */
function distropress_deltree( $dir ) {
	$files = array_diff( scandir( $dir ), array( '.', '..' ) );
	foreach ( $files as $file ) {
		( is_dir( "$dir/$file" ) ) ? distropress_deltree( "$dir/$file" ) : unlink( "$dir/$file" );
	}
	return rmdir( $dir );
}

/* Admin menu */
function distropress_admin_menu() {
	add_menu_page(
		'DistroPress',
		'DistroPress',
		'manage_options',
		'distropress',
		'distropress_settings',
		'',
		'2.000017'
	);
	add_submenu_page(
		'distropress',
		'DistroPress',
		'Configuración',
		'manage_options',
		'distropress',
		'distropress_settings'
	);
	add_submenu_page(
		'distropress',
		'Instalar paquetes',
		'Instalar paquetes',
		'manage_options',
		'distropress_packages',
		'distropress_packages'
	);
}
add_action( 'admin_menu', 'distropress_admin_menu' );

/* Settings */
function distropress_settings() {
	require_once( DISTROPRESS__PLUGIN_DIR . 'admin/settings.php' );
}

/* Packages */
function distropress_packages() {
	require_once( DISTROPRESS__PLUGIN_DIR . 'admin/packages.php' );
}

/* Install package */
function distropress_install_package() {
	require( DISTROPRESS__PLUGIN_DIR . 'admin/install-package.php' );
}
add_action( 'update-custom_distropress-install-package', 'distropress_install_package' );

/* Install dependency */
function distropress_install_dependency( $dependency = NULL ) {
	require( DISTROPRESS__PLUGIN_DIR . 'admin/install-package.php' );
}