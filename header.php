<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <?php wp_head(); ?>
    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Cormorant+Infant:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

</head>

<body <?php body_class('bg-slate-50 text-slate-900'); ?>>

<!-- Overlay pentru meniul mobil -->
<div id="mobile-menu-overlay"
     class="fixed inset-0 bg-black/40 backdrop-blur-sm z-40 hidden opacity-0 transition-opacity duration-300">
</div>

<header class="bg-white border-b border-slate-200 sticky top-0 z-50">
    <div class="container mx-auto px-4 py-4 flex items-center justify-between">

        <!-- Logo -->
        <a href="<?php echo esc_url(home_url('/')); ?>" class="flex items-center gap-2 ">

            <?php if (has_custom_logo()) : ?>
                <?php
                $logo_id = get_theme_mod('custom_logo');
                $logo_url = wp_get_attachment_image_src($logo_id, 'full')[0];
                ?>
                <img
                        src="<?php echo esc_url($logo_url); ?>"
                        alt="<?php bloginfo('name'); ?>"
                        class="h-10 w-auto md:h-10 object-contain"
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

        <button id="ccg-search-open"
                class="flex items-center gap-2 px-4 py-1.5 rounded-full bg-white border border-slate-300 hover:border-ccg-primary group shadow-sm hover:shadow transition">
            <svg xmlns="http://www.w3.org/2000/svg"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2"
                 stroke-linecap="round"
                 stroke-linejoin="round"
                 class="h-6 w-6 text-slate-600 group-hover:text-ccg-primary ">
                <path d="M11 4a7 7 0 1 0 0 14a7 7 0 0 0 0-14zM21 21l-4.35-4.35"/>
            </svg>
            <span class="text-sm text-slate-600 group-hover:text-ccg-primary ">Caută</span>
        </button>


        <div id="ccg-search-panel" class="fixed inset-0 bg-white/90 backdrop-blur-sm z-50 hidden p-6">
            <div class="max-w-xl mx-auto mt-20">
                <?php get_search_form(); ?>
                <button id="ccg-search-close" class="mt-6 block mx-auto text-slate-600 hover:text-ccg-primary">
                    Închide ✕
                </button>
            </div>
        </div>


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
