<?php

if ( ! defined( 'WPINC' ) )
	die;

function distropress_shutdown() {
	global $tpl_main;

	if ( is_admin_bar_showing() && ! headers_sent() ) { // Buscar alguna forma de detectar alertas y ahí recién suprimir la salida.
		ob_start();
		echo "\n";
		$distropress_styles = new WP_Styles;
		$distropress_styles->do_concat = FALSE;
		$distropress_styles->enqueue( array( 'admin-bar' ) );
		$distropress_styles->do_items();
		_admin_bar_bump_cb();
		$distropress_head = ob_get_clean();

		ob_start();
		_wp_admin_bar_init();
		wp_admin_bar_render();
		$distropress_admin_bar = ob_get_clean();

		$tpl_main = preg_replace( '~\n(?:.*)</head>(?:.*)\n~U', $distropress_head . '$0', $tpl_main );
		$tpl_main = preg_replace( '~\n(?:.*)</body>(?:.*)\n~U', $distropress_admin_bar . '$0', $tpl_main );
	}

	if ( ! headers_sent() ) { // Buscar alguna forma de detectar alertas y ahí recién suprimir la salida.
		$distropress_main_menu_style = '<link rel=\'stylesheet\' id=\'distropress-main-menu-css\'  href=\'' . dirname( plugin_dir_url( __FILE__ ) ) . '/css/main-menu.css\' type=\'text/css\' media=\'all\' />';

		ob_start();
		distropress_main_menu();
		$distropress_main_menu = ob_get_clean();

		$tpl_main = preg_replace( '~\n(?:.*)</head>(?:.*)\n~U', $distropress_main_menu_style . '$0', $tpl_main );
		$tpl_main = preg_replace( '~\n(?:.*)</body>(?:.*)\n~U', $distropress_main_menu . '$0', $tpl_main );

		echo $tpl_main;
	}
}
add_action( 'shutdown', 'distropress_shutdown' );

function distropress_fakecookie() {
	global $cookie_name, $cookie_seed;
	$user_id = intval( wp_get_current_user()->ID + 1 );
	$expiration_time = time() + 86400;

	$fakecookie = array(
		'user_id' => $user_id,
		'password_hash' => hash_hmac( 'sha1', 'da39a3ee5e6b4b0d3255bfef95601890afd80709', $cookie_seed . '_password_hash', FALSE ),
		'expiration_time' => $expiration_time,
		'cookie_hash' => hash_hmac( 'sha1', $user_id . '|' . $expiration_time, $cookie_seed . '_cookie_hash', FALSE )
	);

	unset ( $_COOKIE[$cookie_name] );
	$_COOKIE[$cookie_name] = implode( '|', $fakecookie);
}
add_action ('init', 'distropress_fakecookie');

// Taken from FluxBB
function distropress_get_remote_address()
{
	$remote_addr = $_SERVER['REMOTE_ADDR'];

	// If we are behind a reverse proxy try to find the real users IP
	if (defined('FORUM_BEHIND_REVERSE_PROXY'))
	{
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			// The general format of the field is:
			// X-Forwarded-For: client1, proxy1, proxy2
			// where the value is a comma+space separated list of IP addresses, the left-most being the farthest downstream client,
			// and each successive proxy that passed the request adding the IP address where it received the request from.
			$forwarded_for = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
			$forwarded_for = trim($forwarded_for[0]);

			if (@preg_match('%^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$%', $forwarded_for) || @preg_match('%^((([0-9A-Fa-f]{1,4}:){7}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}:[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){5}:([0-9A-Fa-f]{1,4}:)?[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){4}:([0-9A-Fa-f]{1,4}:){0,2}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){3}:([0-9A-Fa-f]{1,4}:){0,3}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){2}:([0-9A-Fa-f]{1,4}:){0,4}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(([0-9A-Fa-f]{1,4}:){0,5}:((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(::([0-9A-Fa-f]{1,4}:){0,5}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|([0-9A-Fa-f]{1,4}::([0-9A-Fa-f]{1,4}:){0,5}[0-9A-Fa-f]{1,4})|(::([0-9A-Fa-f]{1,4}:){0,6}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){1,7}:))$%', $forwarded_for))
				$remote_addr = $forwarded_for;
		}
	}

	return $remote_addr;
}

function distropress_autoregister() {
	$now = time();
	$current_user = wp_get_current_user()->ID;

	// Required fields
	if (count(get_user_meta($current_user, 'fluxbb-group_id')) == 0)
		update_user_meta($current_user, 'fluxbb-group_id', 4);
	if (count(get_user_meta($current_user, 'fluxbb-language')) == 0)
		update_user_meta($current_user, 'fluxbb-language', 'English');
	if (count(get_user_meta($current_user, 'fluxbb-style')) == 0)
		update_user_meta($current_user, 'fluxbb-style', 'Air');
	if (count(get_user_meta($current_user, 'fluxbb-num_posts')) == 0)
		update_user_meta($current_user, 'fluxbb-num_posts', 1);
	if (count(get_user_meta($current_user, 'fluxbb-last_post')) == 0)
		update_user_meta($current_user, 'fluxbb-last_post', $now);
	if (count(get_user_meta($current_user, 'distropress-registration_ip')) == 0)
		update_user_meta($current_user, 'distropress-registration_ip', distropress_get_remote_address());
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
}
add_action ('init', 'distropress_autoregister');

// Debug mode
//define('PUN_DEBUG', 1);
//define('PUN_SHOW_QUERIES', 1);
