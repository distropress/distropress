<?php

if ( ! defined( 'WPINC' ) )
	die;

/* Main menu */
function distropress_main_menu() {
	echo "\n";
	echo '<div id="distropress-main-menu"';
	if ( is_user_logged_in() && is_admin_bar_showing() )
		echo ' style="top: 32px !important;"';
	echo '>' . "\n";
	echo '<ul>' . "\n";
	echo '	<li><a href="' . home_url() . '/">Home</a></li>' . "\n";
	echo '	<li><a href="' . home_url() . '/forums/">Forums</a></li>' . "\n";
	if ( ! is_user_logged_in() ) {
		echo '	<li><a href="' . home_url() . '/wp-login.php">Log in</a></li>' . "\n";
	}
	echo '</ul>' . "\n";
	echo '</div>' . "\n";
}
add_action('wp_footer', 'distropress_main_menu');


function distropress_main_menu_style() {
	wp_register_style( 'distropress-main-menu', plugin_dir_url( __FILE__ ) . 'css/main-menu.css' );
	wp_enqueue_style( 'distropress-main-menu' );
}
add_action( 'wp_enqueue_scripts', 'distropress_main_menu_style', 999 );

