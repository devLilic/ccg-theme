<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <?php wp_head(); ?>
</head>
<body <?php body_class('bg-slate-50'); ?>>

<header class="border-b border-slate-200 bg-white">
    <div class="container mx-auto px-4 py-4 flex items-center justify-between">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="flex items-center gap-2">
            <!-- aici vei pune logo-ul CCG -->
            <span class="font-semibold text-lg text-ccg-primary">
                Calatorii cu Gust
            </span>
        </a>

        <nav class="hidden md:block">
            <?php
            wp_nav_menu([
                    'theme_location' => 'primary',
                    'container' => false,
                    'menu_class' => 'flex gap-6 text-sm font-medium text-slate-700',
            ]);
            ?>
        </nav>
    </div>
</header>

<main class="min-h-screen">
