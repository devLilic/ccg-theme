<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enqueue gallery & lightbox assets.
 */
function ccg_gallery_enqueue_assets() {
    // CSS.
    wp_enqueue_style(
        'ccg-gallery',
        CCG_GALLERY_URL . 'assets/css/gallery.css',
        [],
        CCG_GALLERY_VERSION
    );

    // JS.
    wp_enqueue_script(
        'ccg-gallery',
        CCG_GALLERY_URL . 'assets/js/gallery.js',
        [ 'jquery' ],
        CCG_GALLERY_VERSION,
        true
    );

    wp_enqueue_script(
        'ccg-gallery-lightbox',
        CCG_GALLERY_URL . 'assets/js/lightbox.js',
        [ 'jquery' ],
        CCG_GALLERY_VERSION,
        true
    );

    $settings = [
        'enableSwipe' => true,
        'closeOnEsc'  => true,
        'selector'    => '.ccg-gallery [data-lightbox]',
    ];

    /**
     * Filter lightbox settings passed to JS.
     */
    $settings = apply_filters( 'ccg_gallery_lightbox_settings', $settings );

    wp_localize_script(
        'ccg-gallery-lightbox',
        'CCG_GALLERY_SETTINGS',
        $settings
    );
}
