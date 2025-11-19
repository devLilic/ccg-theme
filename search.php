<?php
/**
 * Search Results
 */
get_header();
get_template_part( 'template-parts/layout/hero-inner', null, [
        'title' => 'Căutare',
        'subtitle' => 'Rezultate pentru: "' . get_search_query() . '"'
]);
?>

<div class="container mx-auto px-4 py-10">

    <?php if ( have_posts() ) : ?>

        <div class="grid gap-6 md:grid-cols-3">

            <?php while ( have_posts() ) : the_post(); ?>
                <?php get_template_part( 'template-parts/content/content', 'excerpt' ); ?>
            <?php endwhile; ?>

        </div>

        <?php ccg_pagination(); ?>

    <?php else : ?>

        <p class="text-slate-500">Nu au fost găsite rezultate.</p>

    <?php endif; ?>

</div>

<?php get_footer(); ?>
