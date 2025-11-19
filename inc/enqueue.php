<?php
/**
 * Enqueue scripts & styles for Calatorii Cu Gust theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function ccg_enqueue_assets() {

    // Încarcă bundle-ul via Vite (nu modificăm configurarea)
    if ( function_exists( 'vite' ) ) {
        vite( 'main.js' );
    }

    // Google Fonts (opțional)
    wp_enqueue_style(
        'ccg-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap',
        [],
        null
    );
}
add_action( 'wp_enqueue_scripts', 'ccg_enqueue_assets' );
