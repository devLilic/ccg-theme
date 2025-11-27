<?php get_header(); ?>

<section class="py-10 bg-slate-50 border-b border-slate-200">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold text-slate-800 mb-2">Locații turistice</h1>
        <p class="text-slate-600 mb-6">
            Explorează locurile autentice din Moldova.
        </p>

        <!-- FILTERS -->
<!--        <form method="get" class="bg-white p-4 rounded-xl shadow mb-8 grid grid-cols-1 md:grid-cols-4 gap-4">-->
<!---->
            <!-- Region -->
<!--            <div>-->
<!--                <label class="text-sm font-semibold mb-1 block">Regiune</label>-->
                <?php
//                wp_dropdown_categories([
//                        'taxonomy'   => 'place_region',
//                        'name'       => 'region',
//                        'show_option_all' => 'Toate',
//                        'class'      => 'w-full border rounded-lg p-2',
//                        'selected'   => isset($_GET['region']) ? $_GET['region'] : ''
//                ]);
                ?>
<!--            </div>-->
<!---->
            <!-- Category -->
<!--            <div>-->
<!--                <label class="text-sm font-semibold mb-1 block">Categorie</label>-->
                <?php
//                wp_dropdown_categories([
//                        'taxonomy'   => 'place_category',
//                        'name'       => 'category',
//                        'show_option_all' => 'Toate',
//                        'class'      => 'w-full border rounded-lg p-2',
//                        'selected'   => isset($_GET['category']) ? $_GET['category'] : ''
//                ]);
//                ?>
<!--            </div>-->
<!---->
            <!-- Tourism Zone -->
<!--            <div>-->
<!--                <label class="text-sm font-semibold mb-1 block">Zonă turistică</label>-->
                <?php
//                wp_dropdown_categories([
//                        'taxonomy'   => 'tourism_zone',
//                        'name'       => 'tourism_zone',
//                        'show_option_all' => 'Toate',
//                        'class'      => 'w-full border rounded-lg p-2',
//                        'selected'   => isset($_GET['tourism_zone']) ? $_GET['tourism_zone'] : ''
//                ]);
                ?>
<!--            </div>-->
<!---->
            <!-- Themes -->
<!--            <div>-->
<!--                <label class="text-sm font-semibold mb-1 block">Teme</label>-->
<!--                <input type="text" name="themes" placeholder="ex: natura, istorie"-->
<!--                       value="--><?php //echo isset($_GET['themes']) ? esc_attr($_GET['themes']) : ''; ?><!--"-->
<!--                       class="w-full border rounded-lg p-2">-->
<!--            </div>-->
<!---->
<!--            <button class="md:col-span-4 bg-ccg-primary text-white rounded-lg px-4 py-2 hover:bg-ccg-primaryDark transition">-->
<!--                Aplică filtre-->
<!--            </button>-->
<!--        </form>-->

        <!-- LOOP -->
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <?php
            if (have_posts()) :
                while (have_posts()) :
                    the_post();
                    get_template_part('template-parts/places/card-place');
                endwhile;
            else:
                echo '<p class="text-slate-600">Nicio locație găsită.</p>';
            endif;
            ?>
        </div>

        <!-- PAGINATION -->
        <div class="mt-10">
            <?php the_posts_pagination([
                    'mid_size' => 2,
                    'prev_text' => '«',
                    'next_text' => '»'
            ]); ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
