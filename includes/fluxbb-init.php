<?php

if ( ! defined( 'WPINC' ) )
	die;

function distropress_shutdown() {
	global $tpl_main;

	if ( is_admin_bar_showing() ) {
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

	echo $tpl_main;
}
add_action( 'shutdown', 'distropress_shutdown' );

$db_type = 'distropress';