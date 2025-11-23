<?php
namespace CCG\Core\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Sanitizer {

    /**
     * Sanitizare safe pentru text simplu.
     */
    public static function text( $value ) {
        return sanitize_text_field( $value );
    }

    /**
     * Sanitizare pentru URL.
     */
    public static function url( $value ) {
        return esc_url_raw( $value );
    }

    /**
     * Sanitizare coordonată GPS (lat/lng).
     */
    public static function coord( $value ) {
        $value = str_replace( ',', '.', trim( (string) $value ) );
        return is_numeric( $value ) ? $value : '';
    }

    /**
     * Sanitizare boolean (checkbox).
     */
    public static function bool( $value ) {
        return ! empty( $value ) ? '1' : '0';
    }

    /**
     * Sanitizare select (variantă finită de opțiuni).
     */
    public static function select( $value, array $allowed ) {
        $value = (string) $value;
        return in_array( $value, $allowed, true ) ? $value : '';
    }
}
