<?php
/**
 * Tema Calatorii Cu Gust – Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'ccg_theme_setup' ) ) :

    function ccg_theme_setup() {

        // Suport pentru <title> generat de WordPress
        add_theme_support( 'title-tag' );

        // Suport pentru imagini featured
        add_theme_support( 'post-thumbnails' );
        set_post_thumbnail_size( 1568, 9999 );


        // HTML5 markup pentru elemente standard
        add_theme_support( 'html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
            'navigation-widgets',
        ) );

        // Suport pentru logo personalizat
        add_theme_support( 'custom-logo', array(
            'height'      => 120,
            'width'       => 120,
            'flex-height' => true,
            'flex-width'  => true,
            'header-text' => array( 'site-title', 'site-description' ),
        ) );


        // Suport pentru feed RSS automat
        add_theme_support( 'automatic-feed-links' );

        // Suport pentru imagini wide și alignfull în Gutenberg
        add_theme_support( 'align-wide' );



        // Meniuri
        register_nav_menus( array(
            'primary' => __( 'Meniu principal', 'calatoriicugust' ),
            'footer'  => __( 'Meniu footer', 'calatoriicugust' ),
        ) );

    }
endif;

add_action( 'after_setup_theme', 'ccg_theme_setup' );
add_theme_support('post-thumbnails');
add_image_size('ccg-partner', 400, 400, false);
