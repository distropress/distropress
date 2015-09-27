<?php

if ( ! defined( 'WPINC' ) )
	die;

// Crear script de instalación de tablas en la base de datos
global $table_prefix;
$current_user = wp_get_current_user();

$_POST['form_sent'] = 1;
$_POST['install_lang'] = 'English';
$_POST['req_db_type'] = 'mysqli';
$_POST['req_db_host'] = DB_HOST;
$_POST['req_db_name'] = DB_NAME;
$_POST['db_username'] = DB_USER;
$_POST['db_password'] = DB_PASSWORD;
$_POST['db_prefix'] = $table_prefix . 'fluxbb_';
$_POST['req_username'] = $current_user->user_login;
$_POST['req_email'] = $current_user->user_email;
$_POST['req_password1'] = wp_generate_password();
$_POST['req_password2'] = $_POST['req_password1'];
$_POST['req_title'] = 'Foros de';		// Tal vez haya que convertir en global
$_POST['desc'] = 'Description...';		// Tal vez haya que convertir en global
$_POST['req_base_url'] = home_url() . '/foros';
$_POST['req_default_lang'] = 'English';
$_POST['req_default_style'] = 'Air';
unset($_POST['generate_config']);

ob_start();
global $db;
global $db_type, $db_host, $db_name, $db_username, $db_password, $db_prefix, $p_connect, $cookie_name, $cookie_domain, $cookie_path, $cookie_secure, $cookie_seed;
chdir( $script_path );
include_once( $script_path . 'install.php' );
ob_end_clean();

$config_content = 'define(\'DISTROPRESS_SCRIPT\', \'fluxbb\');' . "\n";
$config_content .= 'define(\'WP_USE_THEMES\', false);' . "\n";
$config_content .= 'require(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . \'/wp-blog-header.php\');' . "\n";
$config_content .= 'do_action( \'distropress_\' . DISTROPRESS_SCRIPT . \'_settings\' );' . "\n";
$config_content .= 'unset( $p );' . "\n";
$config_file = fopen( $script_path . '/config.php', 'a');
fwrite($config_file, $config_content);
fclose($config_file);

//unlink install.php, login.php y register.php

//echo generate_config_file();
// Esconder la salida y manejar "alerts" para ver si hubo algún error...
// Es posible que se tenga que agregar comandos al cierre del script...

echo '<p><a href="' . home_url() . '/foros/' . '">Ir a FluxBB</a></p>';