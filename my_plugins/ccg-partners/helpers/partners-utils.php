<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Badge HTML pentru tipul partenerului.
 */
function ccg_partners_format_type_badge( $type ) {
    $type = $type ?: 'partner';

    if ( 'sponsor' === $type ) {
        $label = __( 'Sponsor', 'ccg-partners' );
        $bg    = '#ab2b36';
    } else {
        $label = __( 'Partener', 'ccg-partners' );
        $bg    = '#3b82f6';
    }

    $label = esc_html( $label );
    $style = sprintf(
        'padding:2px 6px;background:%s;color:#fff;border-radius:4px;font-size:11px;',
        esc_attr( $bg )
    );

    return '<span style="' . $style . '">' . $label . '</span>';
}

/**
 * Badge HTML pentru status publicare.
 */
function ccg_partners_format_status_badge( $status ) {
    $status = (string) $status;
    if ( '1' === $status ) {
        $label = __( 'Publicat', 'ccg-partners' );
        $bg    = '#10b981';
    } else {
        $label = __( 'Inactiv', 'ccg-partners' );
        $bg    = '#ef4444';
    }

    $label = esc_html( $label );
    $style = sprintf(
        'padding:2px 6px;background:%s;color:#fff;border-radius:4px;font-size:11px;',
        esc_attr( $bg )
    );

    return '<span style="' . $style . '">' . $label . '</span>';
}
