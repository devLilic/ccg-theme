<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <?php wp_head(); ?>
</head>

<body <?php body_class('bg-slate-50 text-slate-900'); ?>>

<!-- Overlay pentru meniul mobil -->
<div id="mobile-menu-overlay"
     class="fixed inset-0 bg-black/40 backdrop-blur-sm z-40 hidden opacity-0 transition-opacity duration-300">
</div>

<header class="bg-white border-b border-slate-200 sticky top-0 z-50">
    <div class="container mx-auto px-4 py-4 flex items-center justify-between">

        <!-- Logo -->
        <a href="<?php echo esc_url(home_url('/')); ?>" class="flex items-center gap-2">

            <?php if (has_custom_logo()) : ?>
                <?php
                $logo_id = get_theme_mod('custom_logo');
                $logo_url = wp_get_attachment_image_src($logo_id, 'full')[0];
                ?>
                <img
                        src="<?php echo esc_url($logo_url); ?>"
                        alt="<?php bloginfo('name'); ?>"
                        class="h-10 w-auto md:h-12 object-contain"
                >
            <?php else : ?>
                <img
                        src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/logo-ccg.png'); ?>"
                        alt="<?php bloginfo('name'); ?>"
                        class="h-10 w-auto md:h-12 object-contain"
                >
            <?php endif; ?>

            <span class="font-semibold text-lg text-ccg-primary hidden md:block">
                <?php bloginfo('name'); ?>
            </span>
        </a>

        <!-- Meniu desktop -->
        <nav class="hidden md:block">
            <?php
            wp_nav_menu([
                    'theme_location' => 'primary',
                    'container' => false,
                    'menu_class' => 'ccg-primary-menu',
            ]);
            ?>
        </nav>

        <!-- Buton meniu mobil -->
        <button
                id="mobile-menu-toggle"
                class="md:hidden text-slate-700 text-3xl focus:outline-none transition-transform"
                aria-label="Deschide meniul"
        >
            <span id="icon-hamburger" class="block leading-none">☰</span>
            <span id="icon-close" class="hidden leading-none">✕</span>
        </button>
    </div>

    <!-- Meniu mobil -->
    <nav
            id="mobile-menu"
            class="fixed top-0 left-0 w-full bg-white shadow-xl z-50 transform -translate-y-full transition-transform duration-300 md:hidden"
    >
        <div class="px-4 py-5">
            <?php
            wp_nav_menu([
                    'theme_location' => 'primary',
                    'container' => false,
                    'menu_class' => 'ccg-mobile-menu flex flex-col gap-1',
            ]);
            ?>
        </div>
    </nav>
</header>

<main class="min-h-screen">
