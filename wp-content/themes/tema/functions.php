<?php

add_action( 'wp_enqueue_scripts', 'porto_child_css', 1001 );

// Load CSS
function porto_child_css() {
	// porto child theme styles
	wp_deregister_style( 'styles-child' );
	wp_register_style( 'styles-child', esc_url( get_stylesheet_directory_uri() ) . '/style.css' );
	wp_enqueue_style( 'styles-child' );

	if ( is_rtl() ) {
		wp_deregister_style( 'styles-child-rtl' );
		wp_register_style( 'styles-child-rtl', esc_url( get_stylesheet_directory_uri() ) . '/style_rtl.css' );
		wp_enqueue_style( 'styles-child-rtl' );
	}
}

add_action( 'send_headers', 'add_header_xframeoptions' );
function add_header_xframeoptions() {
header( 'X-Frame-Options: SAMEORIGIN' );
}


//Logo pagina login

function logoadmin() {
    echo " <style>
    body.login #login h1 a {
    background: url('https://erwise.com.br/wp-content/uploads/2022/04/login-wp.jpg') no-repeat scroll center top transparent;
    height: 90px;
    width: 250px;
    }
    </style>
    ";
    }
add_action("login_head", "logoadmin");

// Customizar o Footer do WordPress
function remove_footer_admin () {
    echo '© <a href="https://api.whatsapp.com/send?phone=%205511937008521&text=Olá!/">Suporte</a> - Desenvolvimento e Criação Erwise Dev ME';
}
add_filter('admin_footer_text', 'remove_footer_admin'); 

// Retirar logo do Wordpress admin
 function wps_admin_bar (){
     global $wp_admin_bar;
     $wp_admin_bar -> remove_menu ('wp-logo');
     $wp_admin_bar -> remove_menu ('about');
     $wp_admin_bar -> remove_menu ('wporg');
     $wp_admin_bar -> remove_menu ('documentation');
     $wp_admin_bar -> remove_menu ('support-forums');
     $wp_admin_bar -> remove_menu ('feedback');
     $wp_admin_bar -> remove_menu ('view-site');
 }
add_action ('wp_before_admin_bar_render', 'wps_admin_bar');

// removendo campo comentarios admin wp

add_action( 'admin_menu', 'my_remove_admin_menus' );

function my_remove_admin_menus() {
    remove_menu_page( 'edit-comments.php' );
}

add_action('init', 'remove_comment_support', 100);

function remove_comment_support() {
    remove_post_type_support( 'post', 'comments' );
    remove_post_type_support( 'page', 'comments' );
}

function mytheme_admin_bar_render() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('comments');
}
add_action( 'wp_before_admin_bar_render', 'mytheme_admin_bar_render' );
