<?php
/**
 * CCG Gallery Loader.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class CCG_Gallery_Loader {

    /**
     * Init plugin.
     */
    public static function init() {
        // Include fișiere funcționale.
        require_once CCG_GALLERY_PLUGIN_DIR . 'includes/ccg-gallery-front.php';
        require_once CCG_GALLERY_PLUGIN_DIR . 'includes/ccg-gallery-places.php';

        // Înregistrare hook-uri principale.
        add_action( 'init', array( __CLASS__, 'register_hooks' ) );
    }

    /**
     * Înregistrare hook-uri globale.
     */
    public static function register_hooks() {
        if ( function_exists( 'ccg_gallery_front_init' ) ) {
            ccg_gallery_front_init();
        }

        if ( function_exists( 'ccg_gallery_places_init' ) ) {
            ccg_gallery_places_init();
        }
    }
}
