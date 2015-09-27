<?php

if ( ! defined( 'WPINC' ) )
	die;

$package = isset( $_REQUEST['package'] ) ? trim( $_REQUEST['package'] ) : '';

if ( ! current_user_can('install_plugins') )
	wp_die( __( 'You do not have sufficient permissions to install plugins on this site.' ) );

//check_admin_referer( 'bulk-update-themes' );
wp_enqueue_script( 'updates' );

switch ( $package ) {
	case 'fluxbb':
		$version = '1.5.7';
		$ext = 'zip';
		$url = "http://$package.org/download/releases/$version/$package-$version.$ext";
		$root = "$package-$version";
		break;
	case 'indefero':
		$version = '1.3.3';
		$ext = 'tar.bz2';
		$url = "http://projects.ceondo.com/p/$package/downloads/get/$package-$version.$ext";
		$root = "$package";
		$dependencies = array( 'pluf' );
		break;
	case 'mediawiki':
		$version = '1.23.6';
		$branch = '1.23';
		$ext = 'tar.gz';
		$url = "http://releases.wikimedia.org/$package/$branch/$package-$version.$ext";
		$root = "$package-$version";
		break;
	case 'opencart':
		$version = '2.0.0.0';
		$ext = 'zip';
		$url = "https://github.com/$package/$package/archive/$version.$ext";
		$root = "$package-$version/upload";
		break;
	case 'pluf':
		$version = 'master';
		$ext = 'zip';
		$url = "http://projects.ceondo.com/p/$package/source/download/$version/";
		$root = "$package-$version";
		break;
	case 'punbb':
		$version = '1.4.2';
		$extension = 'zip';
		$url = "http://$package.informer.com/download/$package-$version.$extension";
		$root = "$package-$version";
		break;
}

$downloads_path = DISTROPRESS__PLUGIN_DIR . 'downloads/';

if ( ! is_dir( $downloads_path ) )
	wp_mkdir_p( $downloads_path );

$file = $downloads_path . $package . '-' . $version . '.' . $ext;

$options['timeout'] = 240;

iframe_header();

if ( isset( $url ) && ! file_exists( $file ) ) {
	$response = wp_remote_get( $url, $options );

	if ( is_wp_error( $response ) || 200 != wp_remote_retrieve_response_code( $response ) ) {
		echo 'Hubo un problema al intentar descargar el archivo.';
	} else {
		$file_content = wp_remote_retrieve_body( $response );
		$handle = fopen( $file, 'w+' );
		fwrite( $handle, $file_content );
		fclose( $handle );
	}
}

// Revisar si el directorio está o no vacío con is_dir_empty()

WP_Filesystem();
$tmp_path = DISTROPRESS__PLUGIN_DIR . '_tmp.' . wp_rand( 100000, 999999 ) . '/';
$script_path = DISTROPRESS__PLUGIN_DIR . 'scripts/' . $package . '/';
$script_url = DISTROPRESS__PLUGIN_URL . 'scripts/' . $package . '/';

if ( is_dir( $tmp_path ) )
	distropress_deltree( $tmp_path );

if ( ! is_dir( $tmp_path ) )
	wp_mkdir_p( $tmp_path );

if ( ! is_dir( $script_path ) )
	wp_mkdir_p( $script_path );

unzip_file( $file, $tmp_path );
rename( $tmp_path . $root, $script_path); // Ver posibilidad de hacer "merge" al actualizar
distropress_deltree( $tmp_path );

global $wp_rewrite;
/*
add_rewrite_rule(
	'foros/(.*\.(css|gif|ico|jpg|png))',
	$script_url . '$1',
	'top'
);
*/
add_rewrite_rule(
	'foros/(.*)',
	substr( $script_url, strlen( esc_url( home_url( '/' ) ) ) ) . '$1',
	'top'
);
flush_rewrite_rules();

if ( file_exists( DISTROPRESS__PLUGIN_DIR . 'utils/safepatch-roots/' . $package . '/patches/main.sp' ) ) {
	$justLoadSafePatch = true;
	$spConfig = array(
		'spRoot' => DISTROPRESS__PLUGIN_DIR . 'utils/safepatch-roots/' . $package . '/',
		'basePath' => $script_path,
		'ignore' => array('.svn/', '_svn/'),
		'ignorePatchFN' => '.-',
		'logType' => 'default',
		'logPath' => 'logs/%Y-%m-%d.log',
		'logMerge' => 3600 * 24 * 7,
		'onError' => 'skip',
		'addComments' => array('!php' => '/* $ */', '!html' => '<!-- $ -->'),
	);

// REMOVE
	if ( is_dir( $spConfig['spRoot'] . 'state/' ) )
		distropress_deltree( $spConfig['spRoot'] . 'state/' );
// REMOVE

	if ( ! is_dir( $spConfig['spRoot'] . 'logs/' ) )
		wp_mkdir_p( $spConfig['spRoot'] . 'logs/' );
	if ( ! is_dir( $spConfig['spRoot'] . 'state/' ) )
		wp_mkdir_p( $spConfig['spRoot'] . 'state/' );

	include_once( DISTROPRESS__PLUGIN_DIR . 'utils/safepatch/src/safepatch.php' );
	$sp = new SafePatch( $spConfig );
	$freshen = $sp->Freshen();
}

if ( file_exists( DISTROPRESS__PLUGIN_DIR . 'admin/includes/' . $package . '-install.php' ) )
	include_once( DISTROPRESS__PLUGIN_DIR . 'admin/includes/' . $package . '-install.php' );

if ( file_exists( DISTROPRESS__PLUGIN_DIR . 'admin/includes/' . $package . '-update.php' ) )
	include_once( DISTROPRESS__PLUGIN_DIR . 'admin/includes/' . $package . '-update.php' );

iframe_footer();