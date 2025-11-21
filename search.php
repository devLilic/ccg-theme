<?php get_header(); ?>

<main class="bg-slate-50 py-12">
    <div class="container mx-auto px-4">

        <!-- HEADER SEARCH -->
        <header class="mb-10">
            <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 flex items-center gap-3">
                <span class="inline-block w-3 h-8 bg-ccg-primary rounded-full"></span>
                Rezultatele căutării pentru: “<?php echo esc_html( get_search_query() ); ?>”
            </h1>
        </header>

        <?php if ( have_posts() ) : ?>

            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">

                <?php while ( have_posts() ) : the_post(); ?>

                    <article class="bg-white rounded-2xl shadow-sm overflow-hidden border border-slate-200 hover:shadow-md transition">

                        <!-- Imagine Featured -->
                        <?php if ( has_post_thumbnail() ) : ?>
                            <a href="<?php the_permalink(); ?>" class="block">
                                <?php the_post_thumbnail( 'medium_large', [ 'class' => 'w-full h-48 object-cover' ] ); ?>
                            </a>
                        <?php endif; ?>

                        <div class="p-5 flex flex-col">

                            <h2 class="text-lg font-bold text-slate-900 mb-3">
                                <a href="<?php the_permalink(); ?>" class="hover:text-ccg-primary transition">
                                    <?php the_title(); ?>
                                </a>
                            </h2>

                            <p class="text-slate-600 text-sm mb-4 flex-1">
                                <?php echo wp_trim_words( get_the_excerpt(), 18 ); ?>
                            </p>

                            <div class="mt-auto flex justify-between items-center text-sm text-slate-500">
                                <span><?php echo get_the_date(); ?></span>
                                <a href="<?php the_permalink(); ?>" class="text-ccg-primary font-semibold hover:underline">
                                    Citește →
                                </a>
                            </div>

                        </div>

                    </article>

                <?php endwhile; ?>

            </div>

            <!-- PAGINATION -->
            <div class="mt-10">
                <?php the_posts_pagination([
                        'mid_size'  => 2,
                        'prev_text' => '«',
                        'next_text' => '»',
                        'class'     => 'flex justify-center gap-2 text-slate-600',
                ]); ?>
            </div>

        <?php else : ?>

            <div class="text-center py-20">
                <h2 class="text-2xl font-bold text-slate-800 mb-4">Nu am găsit rezultate.</h2>
                <p class="text-slate-600 mb-6">Încearcă o altă expresie.</p>
                <?php get_search_form(); ?>
            </div>

        <?php endif; ?>

    </div>
</main>

<?php get_footer(); ?>
