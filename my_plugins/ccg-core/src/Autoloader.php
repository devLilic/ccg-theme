<?php
namespace CCG\Core;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Autoloader simplu pentru namespace-ul CCG\Core
 */
class Autoloader {

    /**
     * Pornește autoloader-ul.
     */
    public static function register() {
        spl_autoload_register( [ __CLASS__, 'autoload' ] );
    }

    /**
     * Autoload pentru clasele CCG\Core\*
     */
    public static function autoload( $class ) {
        $prefix   = 'CCG\\Core\\';
        $base_dir = CCG_CORE_SRC;

        $len = strlen( $prefix );
        if ( 0 !== strncmp( $prefix, $class, $len ) ) {
            return;
        }

        $relative_class = substr( $class, $len );
        $relative_path  = str_replace( '\\', DIRECTORY_SEPARATOR, $relative_class );
        $file           = $base_dir . $relative_path . '.php';

        if ( file_exists( $file ) ) {
            require $file;
        }
    }
}
