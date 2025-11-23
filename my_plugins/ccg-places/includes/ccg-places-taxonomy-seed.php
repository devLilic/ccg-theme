<?php
/**
 * Seed pentru taxonomiile modulului ccg-places
 * Rulează o singură dată, apoi de-comentează include-ul din plugin.
 */

if ( ! defined('ABSPATH') ) exit;

function ccg_places_seed_taxonomies() {

    // === Taxonomiile reale din pluginul nostru ===
    $place_category_tax = 'place_category';
    $region_tax         = 'place_region';
    $zone_tax           = 'tourism_zone';
    $theme_tax          = 'place_theme';

    // Helper pentru inserarea termenilor
    $insert_term = function( $name, $taxonomy, $parent_id = 0 ) {
        if ( term_exists( $name, $taxonomy ) ) {
            return term_exists( $name, $taxonomy )['term_id'];
        }
        $args = [];
        if ( $parent_id > 0 ) {
            $args['parent'] = $parent_id;
        }
        $result = wp_insert_term( $name, $taxonomy, $args );
        return is_wp_error( $result ) ? 0 : (int) $result['term_id'];
    };

    /* -------------------------------------------------------
     * 1) CATEGORII (place_category)
     * ----------------------------------------------------- */
    $place_categories = [
        'Localități' => [
            'Oraș',
            'Sat / localitate rurală'
        ],
        'Natură' => [
            'Rezervație naturală',
            'Pădure / parc natural',
            'Lac / zonă umedă',
            'Râu / vale',
            'Canion / defileu',
            'Punct de belvedere'
        ],
        'Patrimoniu cultural & istoric' => [
            'Fortăreață',
            'Sit arheologic',
            'Monument',
            'Castel / conac',
            'Obiectiv urban sovietic'
        ],
        'Religie & spiritualitate' => [
            'Mănăstire',
            'Mănăstire rupestră',
            'Biserică'
        ],
        'Muzee & cultură' => [
            'Muzeu',
            'Muzeu în aer liber'
        ],
        'Vin & gastronomie' => [
            'Vinărie',
            'Cramă subterană',
            'Restaurant / local gastronomic'
        ],
        'Experiențe & rute' => [
            'Traseu de drumeție',
            'Traseu de ciclism',
            'Eco-village / sat ecologic',
            'Complex turistic / resort'
        ],
    ];

    foreach ( $place_categories as $parent => $children ) {
        $parent_id = $insert_term( $parent, $place_category_tax );
        foreach ( $children as $child ) {
            $insert_term( $child, $place_category_tax, $parent_id );
        }
    }

    /* -------------------------------------------------------
     * 2) REGIUNI (place_region)
     * ----------------------------------------------------- */
    $regions = [
        'Chișinău','Bălți','Anenii Noi','Basarabeasca','Briceni','Cahul','Cantemir','Călărași',
        'Căușeni','Cimișlia','Criuleni','Dondușeni','Drochia','Dubăsari','Edineț','Fălești',
        'Florești','Glodeni','Hîncești','Ialoveni','Leova','Nisporeni','Ocnița','Orhei',
        'Rezina','Rîșcani','Sîngerei','Soroca','Strășeni','Șoldănești','Ștefan Vodă','Taraclia',
        'Telenești','Ungheni','ATU Găgăuzia','Transnistria'
    ];

    foreach ( $regions as $region ) {
        $insert_term( $region, $region_tax );
    }

    /* -------------------------------------------------------
     * 3) ZONE TURISTICE (tourism_zone)
     * ----------------------------------------------------- */
    $zones = [
        'Macro-zone' => [
            'Nord','Centru','Sud'
        ],
        'Zone naturale' => [
            'Codrii','Valea Nistrului','Valea Prutului','Rudi–Arionești','Saharna–Țipova',
            'Orheiul Vechi','Prutul de Jos / Beleu','Colinele Tigheciului','Sudul arid – Bugeac'
        ],
        'Zone vitivinicole' => [
            'Codru','Ștefan Vodă','Valul lui Traian'
        ],
        'Zone culturale' => [
            'Zona monastică centrală','Zona Orhei–Butuceni–Trebujeni',
            'Zona Soroca','Zona Găgăuză'
        ],
        'Zone speciale' => [
            'Transnistria (turism alternativ)'
        ],
    ];

    foreach ( $zones as $parent => $children ) {
        $parent_id = $insert_term( $parent, $zone_tax );
        foreach ( $children as $child ) {
            $insert_term( $child, $zone_tax, $parent_id );
        }
    }

    /* -------------------------------------------------------
     * 4) TEME (place_theme)
     * ----------------------------------------------------- */
    $themes = [
        'natură','biodiversitate','pădure','lac','râu','defileu / canion','drumeții',
        'ciclism','birdwatching','eco-turism','istorie','arheologie','tradiții','spiritualitate',
        'patrimoniu','arhitectură','muzee','artă','soviet-heritage','vin','degustare vin',
        'gastronomie','bucătărie tradițională','rural','agroturism','eco-village','kayaking',
        'boat tours','fotografie','family friendly','adventure','wellness','evenimente culturale','festivaluri'
    ];

    foreach ( $themes as $theme ) {
        $insert_term( $theme, $theme_tax );
    }
}

add_action( 'admin_init', 'ccg_places_seed_taxonomies' );
