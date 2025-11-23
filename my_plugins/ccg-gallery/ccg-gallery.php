<?php
/**
 * Plugin Name: CCG Gallery
 * Plugin URI:  https://calatoriicugust.com
 * Description: Galerie de imagini + lightbox full-screen pentru Calatorii cu Gust (posturi, pagini, Places etc.).
 * Version:     1.0.0
 * Author:      Calatorii cu Gust
 * Text Domain: ccg-gallery
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'CCG_GALLERY_VERSION', '1.0.0' );
define( 'CCG_GALLERY_PLUGIN_FILE', __FILE__ );
define( 'CCG_GALLERY_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CCG_GALLERY_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Loader.
 */
require_once CCG_GALLERY_PLUGIN_DIR . 'includes/class-ccg-gallery-loader.php';

add_action(
    'plugins_loaded',
    array( 'CCG_Gallery_Loader', 'init' )
);
