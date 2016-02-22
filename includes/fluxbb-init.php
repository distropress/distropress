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

		$distropress_main_menu_style = '<link rel=\'stylesheet\' id=\'distropress-main-menu-css\'  href=\'http://localhost/wordpress/wp-content/plugins/distropress/css/main-menu.css?ver=4.4.2\' type=\'text/css\' media=\'all\' />';

	ob_start();
	distropress_main_menu();
	$distropress_main_menu = ob_get_clean();

	$tpl_main = preg_replace( '~\n(?:.*)</head>(?:.*)\n~U', $distropress_main_menu_style . '$0', $tpl_main );
	$tpl_main = preg_replace( '~\n(?:.*)</body>(?:.*)\n~U', $distropress_main_menu . '$0', $tpl_main );

	if ( ! headers_sent() ) // Buscar alguna forma de detectar alertas y ahí recién suprimir la salida.
		echo $tpl_main;
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

function distropress_autoregister() {
	$now = time();
	$current_user = $current_user;

	// Required fields
	if (get_user_meta($current_user, 'fluxbb-group_id',TRUE))
		update_user_meta($current_user, 'fluxbb-group_id', 4);
	if (get_user_meta($current_user, 'fluxbb-language', TRUE))
		update_user_meta($current_user, 'fluxbb-language', 'English');
	if (get_user_meta($current_user, 'fluxbb-style', TRUE))
		update_user_meta($current_user, 'fluxbb-style', 'Air');
	if (get_user_meta($current_user, 'fluxbb-num_posts', TRUE))
		update_user_meta($current_user, 'fluxbb-num_posts', 1);
	if (get_user_meta($current_user, 'fluxbb-last_post', TRUE))
		update_user_meta($current_user, 'fluxbb-last_post', $now);
	if (get_user_meta($current_user, 'distropress-registration_ip', TRUE))
		update_user_meta($current_user, 'distropress-registration_ip', get_remote_address());
	if (get_user_meta($current_user, 'fluxbb-last_visit', TRUE))
		update_user_meta($current_user, 'fluxbb-last_visit', $now);

	// Optional fields
	if (get_user_meta($current_user, 'distropress-title', TRUE))
		update_user_meta($current_user, 'distropress-title', NULL);
	if (get_user_meta($current_user, 'jabber', TRUE))
		update_user_meta($current_user, 'jabber', NULL);
	if (get_user_meta($current_user, 'distropress-icq', TRUE))
		update_user_meta($current_user, 'distropress-icq', NULL);
	if (get_user_meta($current_user, 'distropress-msn', TRUE))
		update_user_meta($current_user, 'distropress-msn', NULL);
	if (get_user_meta($current_user, 'aim', TRUE))
		update_user_meta($current_user, 'aim', NULL);
	if (get_user_meta($current_user, 'yim', TRUE))
		update_user_meta($current_user, 'yim', NULL);
	if (get_user_meta($current_user, 'distropress-location', TRUE))
		update_user_meta($current_user, 'distropress-location', NULL);
	if (get_user_meta($current_user, 'fluxbb-signature', TRUE))
		update_user_meta($current_user, 'fluxbb-signature', NULL);
	if (get_user_meta($current_user, 'fluxbb-disp_topics', TRUE))
		update_user_meta($current_user, 'fluxbb-disp_topics', NULL);
	if (get_user_meta($current_user, 'fluxbb-disp_posts', TRUE))
		update_user_meta($current_user, 'fluxbb-disp_posts', NULL);
	if (get_user_meta($current_user, 'fluxbb-email_setting', TRUE))
		update_user_meta($current_user, 'fluxbb-email_setting', NULL);
	if (get_user_meta($current_user, 'fluxbb-notify_with_post', TRUE))
		update_user_meta($current_user, 'fluxbb-notify_with_post', NULL);
	if (get_user_meta($current_user, 'fluxbb-auto_notify', TRUE))
		update_user_meta($current_user, 'fluxbb-auto_notify', NULL);
	if (get_user_meta($current_user, 'fluxbb-show_smilies', TRUE))
		update_user_meta($current_user, 'fluxbb-show_smilies', NULL);
	if (get_user_meta($current_user, 'fluxbb-show_img', TRUE))
		update_user_meta($current_user, 'fluxbb-show_img', NULL);
	if (get_user_meta($current_user, 'fluxbb-show_img_sig', TRUE))
		update_user_meta($current_user, 'fluxbb-show_img_sig', NULL);
	if (get_user_meta($current_user, 'fluxbb-show_avatars', TRUE))
		update_user_meta($current_user, 'fluxbb-show_avatars', NULL);
	if (get_user_meta($current_user, 'fluxbb-show_sig', TRUE))
		update_user_meta($current_user, 'fluxbb-show_sig', NULL);
	if (get_user_meta($current_user, 'distropress-timezone', TRUE))
		update_user_meta($current_user, 'distropress-timezone', NULL);
	if (get_user_meta($current_user, 'distropress-dst', TRUE))
		update_user_meta($current_user, 'distropress-dst', NULL);
	if (get_user_meta($current_user, 'distropress-time_format', TRUE))
		update_user_meta($current_user, 'distropress-time_format', NULL);
	if (get_user_meta($current_user, 'distropress-date_format', TRUE))
		update_user_meta($current_user, 'distropress-date_format', NULL);
	if (get_user_meta($current_user, 'fluxbb-last_search', TRUE))
		update_user_meta($current_user, 'fluxbb-last_search', NULL);
	if (get_user_meta($current_user, 'fluxbb-last_email_sent', TRUE))
		update_user_meta($current_user, 'fluxbb-last_email_sent', NULL);
	if (get_user_meta($current_user, 'fluxbb-last_report_sent', TRUE))
		update_user_meta($current_user, 'fluxbb-last_report_sent', NULL);
	if (get_user_meta($current_user, 'fluxbb-admin_note', TRUE))
		update_user_meta($current_user, 'fluxbb-admin_note', NULL);
	if (get_user_meta($current_user, 'fluxbb-activate_string', TRUE))
		update_user_meta($current_user, 'fluxbb-activate_string', NULL);
	if (get_user_meta($current_user, 'fluxbb-activate_key', TRUE))
		update_user_meta($current_user, 'fluxbb-activate_key', NULL);
}
add_action ('init', 'distropress_autoregister');

// Debug mode
//define('PUN_DEBUG', 1);
//define('PUN_SHOW_QUERIES', 1);