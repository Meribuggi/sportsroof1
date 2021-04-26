<?php
add_action( 'wp_enqueue_scripts', 'enqueue_child_theme_styles', PHP_INT_MAX);

function enqueue_child_theme_styles() {
		
 wp_enqueue_style( 'style-child', get_stylesheet_directory_uri() . '/assets/css/style-child.css', array( 'sydney-style' ) );
}
?>