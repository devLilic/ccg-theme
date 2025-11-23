<?php
namespace CCG\Core\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Format {

    public static function bool_label( $value, $yes = 'Da', $no = 'Nu' ) {
        return ! empty( $value ) ? $yes : $no;
    }

    public static function badge( $text, $color = '#3b82f6' ) {
        $text  = esc_html( $text );
        $color = esc_attr( $color );

        return '<span style="padding:2px 6px;background:' . $color . ';color:#fff;border-radius:4px;font-size:11px;">' . $text . '</span>';
    }
}
