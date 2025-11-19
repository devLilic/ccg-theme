<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
    exit;

// functions.php is empty so you can easily track what code is needed in order to Vite + Tailwind JIT run well
/**
 * Tema Calatorii Cu Gust - setup de bază
 */
function ccg_theme_setup() {

    // suport pentru <title> din WP
    add_theme_support( 'title-tag' );

    // suport pentru imagini reprezentative
    add_theme_support( 'post-thumbnails' );

    // HTML5 markup pentru elemente uzuale
    add_theme_support( 'html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ] );

    // meniuri
    register_nav_menus( [
        'primary'   => __( 'Meniu principal', 'calatoriicugust' ),
        'footer'    => __( 'Meniu footer', 'calatoriicugust' ),
    ] );
}
add_action( 'after_setup_theme', 'ccg_theme_setup' );

/**
 * Înregistrare sidebars (dacă vei avea)
 */
function ccg_register_sidebars() {
    register_sidebar( [
        'name'          => __( 'Sidebar principal', 'calatoriicugust' ),
        'id'            => 'sidebar-1',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ] );
}
add_action( 'widgets_init', 'ccg_register_sidebars' );

/**
 * Vite + Tailwind integration (din repo)
 */
require get_template_directory() . '/inc/inc.vite.php';

function ccg_enqueue_scripts() {
    // în repo-ul original se folosește ceva gen:
    // vite('main.js');
    if ( function_exists( 'vite' ) ) {
        vite( 'main.js' );
    }
}
add_action( 'wp_enqueue_scripts', 'ccg_enqueue_scripts' );

// Main switch to get frontend assets from a Vite dev server OR from production built folder
// it is recommended to move it into wp-config.php
define('IS_VITE_DEVELOPMENT', true);
