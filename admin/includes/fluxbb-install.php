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
$_POST['req_title'] = get_option('blogname');		// Tal vez haya que convertir en global
$_POST['desc'] = get_option('blogdescription');		// Tal vez haya que convertir en global
$_POST['req_base_url'] = home_url() . '/forums';
$_POST['req_default_lang'] = 'English';
$_POST['req_default_style'] = 'Air';
unset($_POST['generate_config']);

ob_start();
global $db, $db_type, $db_host, $db_name, $db_username, $db_password, $db_prefix, $p_connect, $cookie_name, $cookie_domain, $cookie_path, $cookie_secure, $cookie_seed;
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
// Habrá que poblar la base de datos de WordPress con datos importados desde FluxBB...

global $wpdb;
$wpdb->query('RENAME TABLE '.$db_prefix.'users TO '.$db_prefix.'users_old');
$wpdb->query('CREATE VIEW '.$db_prefix.'users AS SELECT * FROM '.$db_prefix.'users_old WHERE id = 1 UNION SELECT u.ID + 1 AS id, MAX(IF(um.meta_key = \'fluxbb-group_id\', um.meta_value, NULL)) AS group_id, u.user_login AS username, \'da39a3ee5e6b4b0d3255bfef95601890afd80709\' AS password, u.user_email AS email, MAX(IF(um.meta_key = \'distropress-title\', um.meta_value, NULL)) AS title, CONCAT(MAX(IF(um.meta_key = \'first_name\', um.meta_value, NULL)), \' \', MAX(IF(um.meta_key = \'last_name\', um.meta_value, NULL))) AS realname, u.user_url AS url, MAX(IF(um.meta_key = \'distropress-jabber\', um.meta_value, NULL)) AS jabber, MAX(IF(um.meta_key = \'distropress-icq\', um.meta_value, NULL)) AS icq, MAX(IF(um.meta_key = \'distropress-msn\', um.meta_value, NULL)) AS msn, MAX(IF(um.meta_key = \'distropress-aim\', um.meta_value, NULL)) AS aim, MAX(IF(um.meta_key = \'distropress-yahoo\', um.meta_value, NULL)) AS yahoo, MAX(IF(um.meta_key = \'distropress-location\', um.meta_value, NULL)) AS location, MAX(IF(um.meta_key = \'fluxbb-signature\', um.meta_value, NULL)) AS signature, MAX(IF(um.meta_key = \'fluxbb-disp_topics\', um.meta_value, NULL)) AS disp_topics, MAX(IF(um.meta_key = \'fluxbb-disp_posts\', um.meta_value, NULL)) AS disp_posts, MAX(IF(um.meta_key = \'fluxbb-email_setting\', um.meta_value, NULL)) AS email_setting, MAX(IF(um.meta_key = \'fluxbb-notify_with_post\', um.meta_value, NULL)) AS notify_with_post, MAX(IF(um.meta_key = \'fluxbb-auto_notify\', um.meta_value, NULL)) AS auto_notify, MAX(IF(um.meta_key = \'fluxbb-show_smilies\', um.meta_value, NULL)) AS show_smilies, MAX(IF(um.meta_key = \'fluxbb-show_img\', um.meta_value, NULL)) AS show_img, MAX(IF(um.meta_key = \'fluxbb-show_img_sig\', um.meta_value, NULL)) AS show_img_sig, MAX(IF(um.meta_key = \'fluxbb-show_avatars\', um.meta_value, NULL)) AS show_avatars, MAX(IF(um.meta_key = \'fluxbb-show_sig\', um.meta_value, NULL)) AS show_sig, MAX(IF(um.meta_key = \'distropress-timezone\', um.meta_value, NULL)) AS timezone, MAX(IF(um.meta_key = \'distropress-dst\', um.meta_value, NULL)) AS dst, MAX(IF(um.meta_key = \'distropress-time_format\', um.meta_value, NULL)) AS time_format, MAX(IF(um.meta_key = \'distropress-date_format\', um.meta_value, NULL)) AS date_format, MAX(IF(um.meta_key = \'fluxbb-language\', um.meta_value, NULL)) AS language, MAX(IF(um.meta_key = \'fluxbb-style\', um.meta_value, NULL)) AS style, MAX(IF(um.meta_key = \'fluxbb-num_posts\', um.meta_value, NULL)) AS num_posts, MAX(IF(um.meta_key = \'fluxbb-last_post\', um.meta_value, NULL)) AS last_post, MAX(IF(um.meta_key = \'fluxbb-last_search\', um.meta_value, NULL)) AS last_search, MAX(IF(um.meta_key = \'fluxbb-last_email_sent\', um.meta_value, NULL)) AS last_email_sent, MAX(IF(um.meta_key = \'fluxbb-last_report_sent\', um.meta_value, NULL)) AS last_report_sent, UNIX_TIMESTAMP(u.user_registered) AS registered, MAX(IF(um.meta_key = \'distropress-registration_ip\', um.meta_value, NULL)) AS registration_ip, MAX(IF(um.meta_key = \'fluxbb-last_visit\', um.meta_value, NULL)) AS last_visit, MAX(IF(um.meta_key = \'fluxbb-admin_note\', um.meta_value, NULL)) AS admin_note, MAX(IF(um.meta_key = \'fluxbb-activate_string\', um.meta_value, NULL)) AS activate_string, MAX(IF(um.meta_key = \'fluxbb-activate_key\', um.meta_value, NULL)) AS activate_key FROM '.$table_prefix.'users AS u LEFT JOIN '.$table_prefix.'usermeta AS um ON um.user_id = ID');

$now = time();
$current_user = wp_get_current_user()->ID;

// Required fields
if (count(get_user_meta($current_user, 'fluxbb-group_id')) == 0)
	update_user_meta($current_user, 'fluxbb-group_id', 1);
if (count(get_user_meta($current_user, 'fluxbb-language')) == 0)
	update_user_meta($current_user, 'fluxbb-language', $_POST['req_default_lang']);
if (count(get_user_meta($current_user, 'fluxbb-style')) == 0)
	update_user_meta($current_user, 'fluxbb-style', $_POST['req_default_style']);
if (count(get_user_meta($current_user, 'fluxbb-num_posts')) == 0)
	update_user_meta($current_user, 'fluxbb-num_posts', 0);
if (count(get_user_meta($current_user, 'fluxbb-last_post')) == 0)
	update_user_meta($current_user, 'fluxbb-last_post', $now);
if (count(get_user_meta($current_user, 'distropress-registration_ip')) == 0)
	update_user_meta($current_user, 'distropress-registration_ip', get_remote_address());
if (count(get_user_meta($current_user, 'fluxbb-last_visit')) == 0)
	update_user_meta($current_user, 'fluxbb-last_visit', $now);

// Optional fields
if (count(get_user_meta($current_user, 'distropress-title')) == 0)
	update_user_meta($current_user, 'distropress-title', NULL);
if (count(get_user_meta($current_user, 'jabber')) == 0)
	update_user_meta($current_user, 'jabber', NULL);
if (count(get_user_meta($current_user, 'distropress-icq')) == 0)
	update_user_meta($current_user, 'distropress-icq', NULL);
if (count(get_user_meta($current_user, 'distropress-msn')) == 0)
	update_user_meta($current_user, 'distropress-msn', NULL);
if (count(get_user_meta($current_user, 'aim')) == 0)
	update_user_meta($current_user, 'aim', NULL);
if (count(get_user_meta($current_user, 'yim')) == 0)
	update_user_meta($current_user, 'yim', NULL);
if (count(get_user_meta($current_user, 'distropress-location')) == 0)
	update_user_meta($current_user, 'distropress-location', NULL);
if (count(get_user_meta($current_user, 'fluxbb-signature')) == 0)
	update_user_meta($current_user, 'fluxbb-signature', NULL);
if (count(get_user_meta($current_user, 'fluxbb-disp_topics')) == 0)
	update_user_meta($current_user, 'fluxbb-disp_topics', NULL);
if (count(get_user_meta($current_user, 'fluxbb-disp_posts')) == 0)
	update_user_meta($current_user, 'fluxbb-disp_posts', NULL);
if (count(get_user_meta($current_user, 'fluxbb-email_setting')) == 0)
	update_user_meta($current_user, 'fluxbb-email_setting', NULL);
if (count(get_user_meta($current_user, 'fluxbb-notify_with_post')) == 0)
	update_user_meta($current_user, 'fluxbb-notify_with_post', NULL);
if (count(get_user_meta($current_user, 'fluxbb-auto_notify')) == 0)
	update_user_meta($current_user, 'fluxbb-auto_notify', NULL);
if (count(get_user_meta($current_user, 'fluxbb-show_smilies')) == 0)
	update_user_meta($current_user, 'fluxbb-show_smilies', NULL);
if (count(get_user_meta($current_user, 'fluxbb-show_img')) == 0)
	update_user_meta($current_user, 'fluxbb-show_img', NULL);
if (count(get_user_meta($current_user, 'fluxbb-show_img_sig')) == 0)
	update_user_meta($current_user, 'fluxbb-show_img_sig', NULL);
if (count(get_user_meta($current_user, 'fluxbb-show_avatars')) == 0)
	update_user_meta($current_user, 'fluxbb-show_avatars', NULL);
if (count(get_user_meta($current_user, 'fluxbb-show_sig')) == 0)
	update_user_meta($current_user, 'fluxbb-show_sig', NULL);
if (count(get_user_meta($current_user, 'distropress-timezone')) == 0)
	update_user_meta($current_user, 'distropress-timezone', NULL);
if (count(get_user_meta($current_user, 'distropress-dst')) == 0)
	update_user_meta($current_user, 'distropress-dst', NULL);
if (count(get_user_meta($current_user, 'distropress-time_format')) == 0)
	update_user_meta($current_user, 'distropress-time_format', NULL);
if (count(get_user_meta($current_user, 'distropress-date_format')) == 0)
	update_user_meta($current_user, 'distropress-date_format', NULL);
if (count(get_user_meta($current_user, 'fluxbb-last_search')) == 0)
	update_user_meta($current_user, 'fluxbb-last_search', NULL);
if (count(get_user_meta($current_user, 'fluxbb-last_email_sent')) == 0)
	update_user_meta($current_user, 'fluxbb-last_email_sent', NULL);
if (count(get_user_meta($current_user, 'fluxbb-last_report_sent')) == 0)
	update_user_meta($current_user, 'fluxbb-last_report_sent', NULL);
if (count(get_user_meta($current_user, 'fluxbb-admin_note')) == 0)
	update_user_meta($current_user, 'fluxbb-admin_note', NULL);
if (count(get_user_meta($current_user, 'fluxbb-activate_string')) == 0)
	update_user_meta($current_user, 'fluxbb-activate_string', NULL);
if (count(get_user_meta($current_user, 'fluxbb-activate_key')) == 0)
	update_user_meta($current_user, 'fluxbb-activate_key', NULL);

echo '<p><a href="' . home_url() . '/forums/' . '">Go to FluxBB</a></p>';