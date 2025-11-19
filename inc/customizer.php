<?php
/**
 * Customizer settings for Calatorii Cu Gust theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function ccg_customize_register( $wp_customize ) {

    // Secțiune pentru HERO Home
    $wp_customize->add_section( 'ccg_hero_home_section', array(
        'title'       => __( 'Hero – Pagina principală', 'ccg-theme' ),
        'priority'    => 30,
        'description' => __( 'Setează imaginile afișate în hero pe pagina principală.', 'ccg-theme' ),
    ) );

    // Imagine de background full-width
    $wp_customize->add_setting( 'ccg_hero_bg_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( new WP_Customize_Image_Control(
        $wp_customize,
        'ccg_hero_bg_image_control',
        array(
            'label'    => __( 'Imagine de fundal (full-width)', 'ccg-theme' ),
            'section'  => 'ccg_hero_home_section',
            'settings' => 'ccg_hero_bg_image',
            'description' => __( 'Imagine mare pentru fundalul hero. Recomandat: landscape, minim 1600px lățime.', 'ccg-theme' ),
        )
    ) );

    // Imagine în cerc (dreapta)
    $wp_customize->add_setting( 'ccg_hero_circle_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( new WP_Customize_Image_Control(
        $wp_customize,
        'ccg_hero_circle_image_control',
        array(
            'label'    => __( 'Imagine în cerc (dreapta)', 'ccg-theme' ),
            'section'  => 'ccg_hero_home_section',
            'settings' => 'ccg_hero_circle_image',
            'description' => __( 'Imagine reprezentativă pentru cercul din partea dreaptă.', 'ccg-theme' ),
        )
    ) );
}

add_action( 'customize_register', 'ccg_customize_register' );
