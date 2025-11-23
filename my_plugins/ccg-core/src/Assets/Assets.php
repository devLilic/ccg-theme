<?php
namespace CCG\Core\Assets;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Assets {

    public function enqueue_admin() {
        // CSS admin global
        wp_enqueue_style(
            'ccg-core-admin',
            CCG_CORE_URL . 'assets/css/admin.css',
            [],
            CCG_CORE_VERSION
        );

        // JS admin global
        wp_enqueue_script(
            'ccg-core-admin',
            CCG_CORE_URL . 'assets/js/admin.js',
            [ 'jquery' ],
            CCG_CORE_VERSION,
            true
        );
    }

    public function enqueue_public() {
        // Deocamdată minim, dar aici putem pune utilitare comune (ex: pentru hărți, trackere etc.)
        wp_enqueue_style(
            'ccg-core-public',
            CCG_CORE_URL . 'assets/css/public.css',
            [],
            CCG_CORE_VERSION
        );
    }
}
