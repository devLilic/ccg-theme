<?php
/**
 * Template pentru paginile de arhivă:
 * - Categorii
 * - Etichete
 * - Autori
 * - Arhivă lunară / anuală
 */

get_header();

// Titlul arhivei
if (is_category()) {
    $archive_title = single_cat_title('', false);
} elseif (is_tag()) {
    $archive_title = single_tag_title('', false);
} else {
    $archive_title = get_the_archive_title();
}
$archive_description = get_the_archive_description();
?>

<main class="bg-slate-50 py-12">
    <div class="container mx-auto px-4">

        <!-- HEADER ARCHIVE -->
        <header class="mb-10">
            <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 flex items-center gap-3">

                <!-- BADGE -->
                <span class="inline-block w-3 h-8 bg-ccg-primary rounded-full"></span>

                <!-- TITLU -->
                <span>
                    <?php echo wp_kses_post($archive_title); ?>
                </span>
            </h1>

            <?php if ($archive_description) : ?>
                <p class="mt-3 text-slate-600 max-w-2xl">
                    <?php echo wp_kses_post($archive_description); ?>
                </p>
            <?php endif; ?>
        </header>

        <!-- GRID LIST -->
        <?php if (have_posts()) : ?>

            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">

                <?php while (have_posts()) : the_post(); ?>

                    <article
                            class="bg-white rounded-2xl shadow-sm overflow-hidden border border-slate-200 hover:shadow-md transition">

                        <!-- IMAGE -->
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>" class="block">
                                <?php the_post_thumbnail('medium_large', [
                                        'class' => 'w-full h-48 object-cover'
                                ]); ?>
                            </a>
                        <?php endif; ?>

                        <!-- CONTENT -->
                        <div class="p-5 flex flex-col h-full">

                            <!-- CATEGORIE -->
                            <?php
                            $categories = get_the_category();
                            if (!empty($categories)) :
                                ?>
                                <a
                                        href="<?php echo esc_url(get_category_link($categories[0]->term_id)); ?>"
                                        class="inline-flex px-3 py-1 text-xs font-semibold bg-ccg-primary/10 text-ccg-primary rounded-full mb-3"
                                >
                                    <?php echo esc_html($categories[0]->name); ?>
                                </a>
                            <?php endif; ?>

                            <!-- TITLU -->
                            <h2 class="text-lg font-bold text-slate-900 mb-3">
                                <a href="<?php the_permalink(); ?>" class="hover:text-ccg-primary transition">
                                    <?php the_title(); ?>
                                </a>
                            </h2>

                            <!-- EXCERPT -->
                            <p class="text-slate-600 text-sm mb-4 flex-1">
                                <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                            </p>

                            <!-- FOOTER -->
                            <div class="mt-auto flex justify-between items-center text-sm text-slate-500">
                                <span><?php echo get_the_date(); ?></span>
                                <a
                                        href="<?php the_permalink(); ?>"
                                        class="text-ccg-primary font-semibold hover:underline"
                                >
                                    Citește →
                                </a>
                            </div>

                        </div>

                    </article>

                <?php endwhile; ?>

            </div>

            <!-- PAGINATION -->
            <div class="mt-12">
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

            <!-- NO RESULTS -->
            <div class="text-center py-20">
                <h2 class="text-2xl font-bold text-slate-800 mb-4">Nu există articole de afișat.</h2>
                <p class="text-slate-600 mb-6">Revino mai târziu sau folosește căutarea.</p>
                <a href="<?php echo home_url('/'); ?>" class="text-ccg-primary font-semibold hover:underline">
                    Înapoi la pagina principală →
                </a>
            </div>

        <?php endif; ?>

    </div>
</main>

<?php get_footer(); ?>
