<?php
/**
 * Template Name: Front Page
 */

get_header();
?>

    <div class="bg-slate-50">

        <!-- HERO -->
        <section class="relative overflow-hidden">
            <div class="container mx-auto px-4 py-12 md:py-16">
                <div class="grid gap-10 md:grid-cols-2 items-center">
                    <!-- Text -->
                    <div>
                    <span class="inline-flex items-center rounded-full bg-ccg-primary/10 px-3 py-1 text-xs font-semibold text-ccg-primary mb-4">
                        Călătorii • Gastronomie • Tradiții
                    </span>
                        <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-slate-900 mb-4">
                            Descoperă Moldova <span class="text-ccg-primary">cu gust</span>
                        </h1>
                        <p class="text-slate-600 text-base md:text-lg mb-6 max-w-xl">
                            Locuri autentice, evenimente locale, rute turistice și experiențe gastronomice –
                            toate adunate într-o singură platformă.
                        </p>

                        <!-- Căutare principală -->
                        <form class="bg-white rounded-2xl shadow-sm p-3 md:p-4 mb-4 flex flex-col md:flex-row gap-3">
                            <div class="flex-1">
                                <label class="sr-only" for="ccg-search">Caută</label>
                                <input
                                    id="ccg-search"
                                    type="text"
                                    class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ccg-primary focus:ring-ccg-primary"
                                    placeholder="Caută locuri, evenimente, rute..."
                                >
                            </div>
                            <div>
                                <label class="sr-only" for="ccg-type">Tip</label>
                                <select
                                    id="ccg-type"
                                    class="w-full md:w-40 rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ccg-primary focus:ring-ccg-primary"
                                >
                                    <option>Toate</option>
                                    <option>Locuri</option>
                                    <option>Evenimente</option>
                                    <option>Rute</option>
                                </select>
                            </div>
                            <button
                                type="submit"
                                class="inline-flex items-center justify-center rounded-xl bg-ccg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-ccg-primaryDark transition"
                            >
                                Caută
                            </button>
                        </form>

                        <!-- Butoane rapide -->
                        <div class="flex flex-wrap gap-2 text-sm">
                            <a href="#" class="inline-flex items-center px-3 py-1.5 rounded-full bg-white border border-slate-200 text-slate-700 hover:border-ccg-primary hover:text-ccg-primary">
                                Locuri turistice
                            </a>
                            <a href="#" class="inline-flex items-center px-3 py-1.5 rounded-full bg-white border border-slate-200 text-slate-700 hover:border-ccg-primary hover:text-ccg-primary">
                                Evenimente
                            </a>
                            <a href="#" class="inline-flex items-center px-3 py-1.5 rounded-full bg-white border border-slate-200 text-slate-700 hover:border-ccg-primary hover:text-ccg-primary">
                                Rute turistice
                            </a>
                        </div>
                    </div>

                    <!-- Imagine / logo -->
                    <div class="flex justify-center md:justify-end">
                        <div class="relative">
                            <div class="absolute -inset-6 rounded-full bg-ccg-primary/5 blur-2xl"></div>
                            <div class="relative bg-white rounded-full shadow-md p-6 md:p-8 flex items-center justify-center">
                                <img
                                    src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/logo-ccg.png' ); ?>"
                                    alt="Calatorii cu Gust"
                                    class="w-40 h-40 md:w-52 md:h-52 object-contain"
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- DESTINAȚII POPULARE -->
        <section class="py-10 md:py-12">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between gap-3 mb-6">
                    <div>
                        <h2 class="text-xl md:text-2xl font-bold text-slate-900">
                            Destinații populare
                        </h2>
                        <p class="text-sm text-slate-500">
                            Locuri pe care nu vrei să le ratezi când vizitezi Moldova.
                        </p>
                    </div>
                    <a href="#" class="hidden md:inline-flex text-sm font-medium text-ccg-primary hover:text-ccg-primaryDark">
                        Vezi toate locurile →
                    </a>
                </div>

                <div class="grid gap-5 md:grid-cols-3">
                    <?php
                    /**
                     * Aici, în viitor, pluginul va injecta cardurile de tip PLACE.
                     * Exemplu: do_action( 'ccg_home_places' );
                     */
                    get_template_part(
                            'template-parts/cards/card',
                            'place',
                            [
                                    'place' => [
                                            'permalink' => '#',
                                            'title'     => 'Orheiul Vechi',
                                            'region'    => 'Raionul Orhei',
                                            'tags'      => [ 'Natură', 'Istorie', 'Panoramă' ],
                                            'meta'      => 'Traseu de o zi, potrivit pentru familie',
                                    ],
                            ]
                    );
                    get_template_part(
                            'template-parts/cards/card',
                            'place',
                            [
                                    'place' => [
                                            'permalink' => '#',
                                            'title'     => 'Orheiul Vechi',
                                            'region'    => 'Raionul Orhei',
                                            'tags'      => [ 'Natură', 'Istorie', 'Panoramă' ],
                                            'meta'      => 'Traseu de o zi, potrivit pentru familie',
                                    ],
                            ]
                    );
                    get_template_part(
                            'template-parts/cards/card',
                            'place',
                            [
                                    'place' => [
                                            'permalink' => '#',
                                            'title'     => 'Orheiul Vechi',
                                            'region'    => 'Raionul Orhei',
                                            'tags'      => [ 'Natură', 'Istorie', 'Panoramă' ],
                                            'meta'      => 'Traseu de o zi, potrivit pentru familie',
                                    ],
                            ]
                    );

                    do_action( 'ccg_home_places_placeholder' ); // momentan nimic
                    ?>
                    <!-- Până există plugin, poți insera manual 3 carduri demo sau să lași gol. -->

                </div>

                <div class="mt-4 md:hidden">
                    <a href="#" class="inline-flex text-sm font-medium text-ccg-primary hover:text-ccg-primaryDark">
                        Vezi toate locurile →
                    </a>
                </div>
            </div>
        </section>

        <!-- EVENIMENTE ÎN CURÂND -->
        <section class="py-8 md:py-10 bg-white">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between gap-3 mb-6">
                    <div>
                        <h2 class="text-xl md:text-2xl font-bold text-slate-900">
                            Evenimente în curând
                        </h2>
                        <p class="text-sm text-slate-500">
                            Festivaluri, târguri, degustări și alte experiențe.
                        </p>
                    </div>
                    <a href="#" class="hidden md:inline-flex text-sm font-medium text-ccg-primary hover:text-ccg-primaryDark">
                        Vezi toate evenimentele →
                    </a>
                </div>

                <div class="flex gap-4 overflow-x-auto pb-2 -mx-4 px-4 md:grid md:grid-cols-3 md:gap-5 md:overflow-visible md:mx-0 md:px-0">
                    <?php
                    get_template_part(
                            'template-parts/cards/card',
                            'event',
                            [
                                    'event' => [
                                            'permalink' => '#',
                                            'title' => 'Festivalul Vinului de Toamnă',
                                            'location' => 'Cricova',
                                            'date_day' => '12',
                                            'date_month' => 'OCT',
                                            'date_label' => '12 – 13 octombrie 2025',
                                            'price_label' => 'Intrare liberă',
                                    ],
                            ]
                    );
                    get_template_part(
                            'template-parts/cards/card',
                            'event',
                            [
                                    'event' => [
                                            'permalink' => '#',
                                            'title' => 'Festivalul Vinului de Toamnă',
                                            'location' => 'Cricova',
                                            'date_day' => '12',
                                            'date_month' => 'OCT',
                                            'date_label' => '12 – 13 octombrie 2025',
                                            'price_label' => 'Intrare liberă',
                                    ],
                            ]
                    );
                    get_template_part(
                            'template-parts/cards/card',
                            'event',
                            [
                                    'event' => [
                                            'permalink' => '#',
                                            'title' => 'Festivalul Vinului de Toamnă',
                                            'location' => 'Cricova',
                                            'date_day' => '12',
                                            'date_month' => 'OCT',
                                            'date_label' => '12 – 13 octombrie 2025',
                                            'price_label' => 'Intrare liberă',
                                    ],
                            ]
                    );
                    get_template_part(
                            'template-parts/cards/card',
                            'event',
                            [
                                    'event' => [
                                            'permalink' => '#',
                                            'title' => 'Festivalul Vinului de Toamnă',
                                            'location' => 'Cricova',
                                            'date_day' => '12',
                                            'date_month' => 'OCT',
                                            'date_label' => '12 – 13 octombrie 2025',
                                            'price_label' => 'Intrare liberă',
                                    ],
                            ]
                    );
                    get_template_part(
                            'template-parts/cards/card',
                            'event',
                            [
                                    'event' => [
                                            'permalink' => '#',
                                            'title' => 'Festivalul Vinului de Toamnă',
                                            'location' => 'Cricova',
                                            'date_day' => '12',
                                            'date_month' => 'OCT',
                                            'date_label' => '12 – 13 octombrie 2025',
                                            'price_label' => 'Intrare liberă',
                                    ],
                            ]
                    );

                    // viitor: do_action( 'ccg_home_events' );
                    do_action( 'ccg_home_events_placeholder' );
                    ?>
                </div>

                <div class="mt-4 md:hidden">
                    <a href="#" class="inline-flex text-sm font-medium text-ccg-primary hover:text-ccg-primaryDark">
                        Vezi toate evenimentele →
                    </a>
                </div>
            </div>
        </section>

        <!-- RUTE TURISTICE RECOMANDATE -->
        <section class="py-10 md:py-12">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between gap-3 mb-6">
                    <div>
                        <h2 class="text-xl md:text-2xl font-bold text-slate-900">
                            Rute turistice recomandate
                        </h2>
                        <p class="text-sm text-slate-500">
                            Trasee de o zi sau mai multe, pregătite pentru tine.
                        </p>
                    </div>
                    <a href="#" class="hidden md:inline-flex text-sm font-medium text-ccg-primary hover:text-ccg-primaryDark">
                        Vezi toate rutele →
                    </a>
                </div>

                <div class="grid gap-5 md:grid-cols-3">
                    <?php
                    get_template_part(
                            'template-parts/cards/card',
                            'route',
                            [
                                    'route' => [
                                            'permalink'  => '#',
                                            'title'      => 'Ruta „Defileul Trebujeni”',
                                            'start'      => 'Trebujeni',
                                            'end'        => 'Orheiul Vechi',
                                            'distance'   => '8 km',
                                            'duration'   => '3h 20m',
                                            'difficulty' => 'Medie',
                                            'image_html' => '<img src="https://picsum.photos/600/400?random=101" class="w-full h-48 object-cover" />',
                                    ]
                            ]
                    );
                    get_template_part(
                            'template-parts/cards/card',
                            'route',
                            [
                                    'route' => [
                                            'permalink'  => '#',
                                            'title'      => 'Ruta „Defileul Trebujeni”',
                                            'start'      => 'Trebujeni',
                                            'end'        => 'Orheiul Vechi',
                                            'distance'   => '8 km',
                                            'duration'   => '3h 20m',
                                            'difficulty' => 'Medie',
                                            'image_html' => '<img src="https://picsum.photos/600/400?random=101" class="w-full h-48 object-cover" />',
                                    ]
                            ]
                    );

                    get_template_part(
                            'template-parts/cards/card',
                            'route',
                            [
                                    'route' => [
                                            'permalink'  => '#',
                                            'title'      => 'Ruta „Defileul Trebujeni”',
                                            'start'      => 'Trebujeni',
                                            'end'        => 'Orheiul Vechi',
                                            'distance'   => '8 km',
                                            'duration'   => '3h 20m',
                                            'difficulty' => 'Medie',
                                            'image_html' => '<img src="https://picsum.photos/600/400?random=101" class="w-full h-48 object-cover" />',
                                    ]
                            ]
                    );

                    // viitor: do_action( 'ccg_home_routes' );
                    do_action( 'ccg_home_routes_placeholder' );
                    ?>
                </div>

                <div class="mt-4 md:hidden">
                    <a href="#" class="inline-flex text-sm font-medium text-ccg-primary hover:text-ccg-primaryDark">
                        Vezi toate rutele →
                    </a>
                </div>
            </div>
        </section>

        <!-- HARTĂ / TEASER -->
        <section class="py-10 md:py-12 bg-slate-900">
            <div class="container mx-auto px-4">
                <div class="grid gap-8 md:grid-cols-2 items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-white mb-3">
                            Vezi toate experiențele pe hartă
                        </h2>
                        <p class="text-sm text-slate-200 mb-6">
                            Explorează locuri, evenimente și rute direct pe o hartă interactivă.
                        </p>
                        <a href="#"
                           class="inline-flex items-center rounded-xl bg-ccg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-ccg-primaryDark transition">
                            Deschide harta interactivă
                        </a>
                    </div>
                    <div>
                        <div class="aspect-[16/9] rounded-2xl bg-slate-800 border border-slate-700 flex items-center justify-center text-slate-400 text-sm">
                            Harta interactivă va fi aici (React + Leaflet)
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- BLOG / POVEȘTI CU GUST -->
        <section class="py-10 md:py-12">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between gap-3 mb-6">
                    <div>
                        <h2 class="text-xl md:text-2xl font-bold text-slate-900">
                            Povești cu gust
                        </h2>
                        <p class="text-sm text-slate-500">
                            Articole și ghiduri pentru a descoperi Moldova diferit.
                        </p>
                    </div>
                    <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>"
                       class="hidden md:inline-flex text-sm font-medium text-ccg-primary hover:text-ccg-primaryDark">
                        Vezi toate articolele →
                    </a>
                </div>

                <div class="grid gap-5 md:grid-cols-3">
                    <?php
                    // Loop standard pentru ultimele postări de blog
                    $ccg_blog = new WP_Query( [
                        'posts_per_page' => 3,
                        'post_type'      => 'post',
                    ] );

                    if ( $ccg_blog->have_posts() ) :
                        while ( $ccg_blog->have_posts() ) : $ccg_blog->the_post(); ?>
                            <article <?php post_class('bg-white rounded-2xl shadow-sm overflow-hidden flex flex-col'); ?>>
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <a href="<?php the_permalink(); ?>" class="block">
                                        <?php the_post_thumbnail( 'medium_large', [
                                            'class' => 'w-full h-48 object-cover',
                                        ] ); ?>
                                    </a>
                                <?php endif; ?>
                                <div class="p-4 flex flex-col flex-1">
                                    <div class="text-xs text-slate-400 mb-1">
                                        <?php echo get_the_date(); ?>
                                    </div>
                                    <h3 class="text-base font-semibold text-slate-900 mb-2">
                                        <a href="<?php the_permalink(); ?>" class="hover:text-ccg-primary">
                                            <?php the_title(); ?>
                                        </a>
                                    </h3>
                                    <div class="text-sm text-slate-600 mb-3 line-clamp-3">
                                        <?php the_excerpt(); ?>
                                    </div>
                                    <a href="<?php the_permalink(); ?>" class="mt-auto inline-flex text-sm font-medium text-ccg-primary hover:text-ccg-primaryDark">
                                        Citește mai mult →
                                    </a>
                                </div>
                            </article>
                        <?php endwhile;
                        wp_reset_postdata();
                    else : ?>
                        <p class="text-sm text-slate-500">Nu există articole momentan.</p>
                    <?php endif; ?>
                </div>

                <div class="mt-4 md:hidden">
                    <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>"
                       class="inline-flex text-sm font-medium text-ccg-primary hover:text-ccg-primaryDark">
                        Vezi toate articolele →
                    </a>
                </div>
            </div>
        </section>

        <!-- NEWSLETTER -->
        <section class="py-10 md:py-12">
            <div class="container mx-auto px-4">
                <div class="rounded-3xl bg-ccg-primary text-white px-6 py-8 md:px-10 md:py-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                    <div>
                        <h2 class="text-2xl font-bold mb-2">
                            Primește idei de călătorie cu gust
                        </h2>
                        <p class="text-sm md:text-base text-rose-50/90 max-w-xl">
                            Înscrie-te la newsletter și vei primi periodic trasee, evenimente și recomandări gastronomice.
                        </p>
                    </div>
                    <form class="w-full md:w-auto flex flex-col sm:flex-row gap-3">
                        <input
                            type="email"
                            class="w-full sm:w-64 rounded-xl px-3 py-2 text-sm text-slate-900 border-0 focus:ring-2 focus:ring-white"
                            placeholder="Emailul tău"
                        >
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-white px-4 py-2 text-sm font-semibold text-ccg-primary hover:bg-rose-50 transition"
                        >
                            Mă abonez
                        </button>
                    </form>
                </div>
            </div>
        </section>

    </div>

<?php
get_footer();
