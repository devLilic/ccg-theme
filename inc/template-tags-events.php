<?php
/**
 * Helper functions pentru Event templates
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Lunile în română (lowercase)
 */
function ccg_events_get_month_name_ro( $month_number ) {
    $months = [
        1 => 'ianuarie',
        2 => 'februarie',
        3 => 'martie',
        4 => 'aprilie',
        5 => 'mai',
        6 => 'iunie',
        7 => 'iulie',
        8 => 'august',
        9 => 'septembrie',
        10 => 'octombrie',
        11 => 'noiembrie',
        12 => 'decembrie',
    ];

    $month_number = (int) $month_number;

    return $months[ $month_number ] ?? '';
}

/**
 * Formatarea intervalului de dată pentru un event
 *
 * Exemple:
 *  - one-day: 15 martie 2025
 *  - multi-day same month: 12–14 iunie 2025
 *  - multi-day diff month/year: 28 decembrie 2025 – 2 ianuarie 2026
 *  - cu oră: 15 martie 2025, 14:00
 */
function ccg_events_format_date_range( $post_id, $include_time = false ) {
    $date_start = get_post_meta( $post_id, '_ccg_event_date_start', true );
    $date_end   = get_post_meta( $post_id, '_ccg_event_date_end', true );
    $time_start = get_post_meta( $post_id, '_ccg_event_time_start', true );
    $time_end   = get_post_meta( $post_id, '_ccg_event_time_end', true );
    $is_one_day = get_post_meta( $post_id, '_ccg_event_is_one_day', true );

    if ( ! $date_start ) {
        return '';
    }

    $start = DateTime::createFromFormat( 'Y-m-d', $date_start );
    if ( ! $start ) {
        return '';
    }

    $start_day   = $start->format( 'j' );
    $start_month = (int) $start->format( 'n' );
    $start_year  = $start->format( 'Y' );

    $start_month_name = ccg_events_get_month_name_ro( $start_month );

    $output = '';

    // One day sau fără end date clar
    if ( $is_one_day || ! $date_end || $date_end === $date_start ) {
        $output = sprintf(
            '%s %s %s',
            $start_day,
            $start_month_name,
            $start_year
        );

        if ( $include_time && $time_start ) {
            $output .= ', ' . esc_html( substr( $time_start, 0, 5 ) );
        }

        return $output;
    }

    // Multi-day
    $end = DateTime::createFromFormat( 'Y-m-d', $date_end );
    if ( ! $end ) {
        // fallback ca one-day
        $output = sprintf(
            '%s %s %s',
            $start_day,
            $start_month_name,
            $start_year
        );
        return $output;
    }

    $end_day   = $end->format( 'j' );
    $end_month = (int) $end->format( 'n' );
    $end_year  = $end->format( 'Y' );
    $end_month_name = ccg_events_get_month_name_ro( $end_month );

    if ( $start_year === $end_year && $start_month === $end_month ) {
        // 12–14 iunie 2025
        $output = sprintf(
            '%s–%s %s %s',
            $start_day,
            $end_day,
            $start_month_name,
            $start_year
        );
    } else {
        // 28 decembrie 2025 – 2 ianuarie 2026
        $output = sprintf(
            '%s %s %s – %s %s %s',
            $start_day,
            $start_month_name,
            $start_year,
            $end_day,
            $end_month_name,
            $end_year
        );
    }

    return $output;
}

/**
 * Tipul principal al evenimentului (event_type)
 */
function ccg_events_get_primary_type( $post_id ) {
    $terms = wp_get_post_terms( $post_id, 'event_type' );
    if ( is_wp_error( $terms ) || empty( $terms ) ) {
        return null;
    }
    return $terms[0];
}

/**
 * Returnează lista de teme (event_theme) ca array de nume
 */
function ccg_events_get_themes_list( $post_id ) {
    $terms = wp_get_post_terms( $post_id, 'event_theme' );
    if ( is_wp_error( $terms ) || empty( $terms ) ) {
        return [];
    }
    return wp_list_pluck( $terms, 'name' );
}

/**
 * Returnează primul termen de region (nume)
 */
function ccg_events_get_region_name( $post_id ) {
    $terms = wp_get_post_terms( $post_id, 'region' );
    if ( is_wp_error( $terms ) || empty( $terms ) ) {
        return '';
    }
    return $terms[0]->name;
}

/**
 * Localitatea din meta
 */
function ccg_events_get_locality( $post_id ) {
    $locality = get_post_meta( $post_id, '_ccg_event_locality', true );
    return $locality ? $locality : '';
}
