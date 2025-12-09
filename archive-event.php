<?php
/**
 * Arhivă Evenimente (CPT: event)
 */

get_header();

$archive_title = post_type_archive_title('', false);
?>

<main class="bg-slate-50 py-12 relative min-h-screen overflow-hidden">
    <!-- Background principal -->
    <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-800 to-[#0e1c2f]"></div>

    <!-- Glow #1 – cyan top-right -->
    <div class="absolute top-[-10%] right-[-10%] w-[600px] h-[600px]
                bg-[#3EC1FF]/30 blur-[180px] rounded-full pointer-events-none"></div>

    <!-- Glow #2 – cyan bottom-left -->
    <div class="absolute bottom-[-15%] left-[-10%] w-[700px] h-[700px]
                bg-[#3EC1FF]/20 blur-[200px] rounded-full pointer-events-none"></div>

    <!-- Glow #3 – violet subtle (pentru profunzime) -->
    <div class="absolute bottom-[10%] right-[20%] w-[500px] h-[500px]
                bg-purple-600/20 blur-[160px] rounded-full opacity-50 pointer-events-none"></div>

    <!-- Layer textură fină (grain) -->
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/asfalt-light.png')]
                opacity-[0.04] pointer-events-none"></div>
    <!-- Conținutul real -->
    <div class="relative z-20">
        <!-- aici vine grid-ul de carduri event -->


        <div class="container mx-auto px-4">

            <!-- HEADER -->
            <header class="mb-8">
                <h1 class="text-3xl md:text-4xl font-extrabold text-white flex items-center gap-3">
                    <span class="inline-block w-3 h-8 bg-ccg-primary rounded-full"></span>
                    <span><?php echo esc_html($archive_title ?: 'Evenimente'); ?></span>
                </h1>
                <p class="mt-3 text-slate-200 max-w-2xl">
                    Descoperă evenimente locale, festivaluri, târguri și experiențe autentice din toată Moldova.
                </p>
            </header>

            <!-- FILTER BAR -->
            <section class="mb-10 bg-white rounded-2xl shadow-sm border border-slate-200 p-4 md:p-5">
                <form method="get" class="grid gap-3 md:grid-cols-4 lg:grid-cols-6 items-end">

                    <?php
                    $selected_type = isset($_GET['event_type']) ? (int) $_GET['event_type'] : 0;
                    $selected_region = isset($_GET['region']) ? (int) $_GET['region'] : 0;
                    $selected_theme = isset($_GET['event_theme']) ? (int) $_GET['event_theme'] : 0;
                    $selected_aud = isset($_GET['target_audience']) ? sanitize_text_field($_GET['target_audience']) : '';
                    $selected_date = isset($_GET['date_filter']) ? sanitize_text_field($_GET['date_filter']) : '';
                    $free_only = !empty($_GET['free_only']);
                    ?>

                    <!-- Event Type -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Tip eveniment</label>
                        <select name="event_type" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                            <option value="">Toate</option>
                            <?php
                            $types = get_terms([
                                    'taxonomy' => 'event_type',
                                    'hide_empty' => true,
                            ]);
                            if (!is_wp_error($types)) :
                                foreach ($types as $type) :
                                    ?>
                                    <option value="<?php echo esc_attr($type->term_id); ?>" <?php selected($selected_type, $type->term_id); ?>>
                                        <?php echo esc_html($type->name); ?>
                                    </option>
                                <?php
                                endforeach;
                            endif;
                            ?>
                        </select>
                    </div>

                    <!-- Region -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Regiune</label>
                        <select name="region" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                            <option value="">Toate</option>
                            <?php
                            $regions = get_terms([
                                    'taxonomy' => 'region',
                                    'hide_empty' => true,
                            ]);
                            if (!is_wp_error($regions)) :
                                foreach ($regions as $region) :
                                    ?>
                                    <option value="<?php echo esc_attr($region->term_id); ?>" <?php selected($selected_region, $region->term_id); ?>>
                                        <?php echo esc_html($region->name); ?>
                                    </option>
                                <?php
                                endforeach;
                            endif;
                            ?>
                        </select>
                    </div>

                    <!-- Theme -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Tematică</label>
                        <select name="event_theme" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                            <option value="">Toate</option>
                            <?php
                            $themes = get_terms([
                                    'taxonomy' => 'event_theme',
                                    'hide_empty' => true,
                            ]);
                            if (!is_wp_error($themes)) :
                                foreach ($themes as $term) :
                                    ?>
                                    <option value="<?php echo esc_attr($term->term_id); ?>" <?php selected($selected_theme, $term->term_id); ?>>
                                        <?php echo esc_html($term->name); ?>
                                    </option>
                                <?php
                                endforeach;
                            endif;
                            ?>
                        </select>
                    </div>

                    <!-- Target Audience (meta-based – opțiuni fixe) -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Public țintă</label>
                        <select name="target_audience"
                                class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                            <option value="">Toți</option>
                            <option value="familie" <?php selected($selected_aud, 'familie'); ?>>Familii</option>
                            <option value="tineri" <?php selected($selected_aud, 'tineri'); ?>>Tineri</option>
                            <option value="seniori" <?php selected($selected_aud, 'seniori'); ?>>Seniori</option>
                        </select>
                    </div>

                    <!-- Date Filter -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Perioadă</label>
                        <select name="date_filter" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                            <option value="">Oricând</option>
                            <option value="today" <?php selected($selected_date, 'today'); ?>>Astăzi</option>
                            <option value="weekend" <?php selected($selected_date, 'weekend'); ?>>Weekend-ul acesta
                            </option>
                            <option value="month" <?php selected($selected_date, 'month'); ?>>Luna aceasta</option>
                        </select>
                    </div>

                    <!-- Free only -->
                    <div class="flex items-center gap-2">
                        <input
                                type="checkbox"
                                id="free_only"
                                name="free_only"
                                value="1"
                                class="rounded border-slate-300"
                                <?php checked($free_only); ?>
                        >
                        <label for="free_only" class="text-xs font-semibold text-slate-600">Evenimente gratuite</label>
                    </div>

                    <!-- Submit -->
                    <div class="md:col-span-4 lg:col-span-6 flex justify-end gap-3 mt-2">
                        <a href="<?php echo esc_url(get_post_type_archive_link('event')); ?>"
                           class="px-3 py-2 text-xs rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50">
                            Resetează filtrele
                        </a>
                        <button
                                type="submit"
                                class="px-4 py-2 rounded-xl bg-ccg-primary text-white text-sm font-semibold hover:bg-ccg-primaryDark transition"
                        >
                            Aplică filtrele
                        </button>
                    </div>

                </form>
            </section>

            <!-- LISTĂ EVENIMENTE -->
            <?php if (have_posts()) : ?>

                <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                    <?php
                    while (have_posts()) :
                        the_post();
                        get_template_part('template-parts/events/card', 'event-future', ['post_id' => get_the_ID()]);
                    endwhile;
                    ?>
                </div>

                <!-- PAGINARE -->
                <div class="mt-10">
                    <?php
                    the_posts_pagination([
                            'mid_size' => 2,
                            'prev_text' => '«',
                            'next_text' => '»',
                            'class' => 'flex justify-center gap-2 text-slate-600',
                    ]);
                    ?>
                </div>

            <?php else : ?>

                <div class="text-center py-20">
                    <h2 class="text-2xl font-bold text-slate-800 mb-4">Momentan nu sunt evenimente în această
                        categorie.</h2>
                    <p class="text-slate-600">Încearcă să ajustezi filtrele sau revino mai târziu.</p>
                </div>

            <?php endif; ?>

        </div>

    </div> <!-- end content -->
</main>

<?php get_footer(); ?>
