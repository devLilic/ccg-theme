<?php get_header(); ?>

<div class="container mx-auto px-4 py-8">

    <?php if ( have_posts() ) : ?>

        <div class="grid gap-6 md:grid-cols-3">

            <?php while ( have_posts() ) : the_post(); ?>
                <?php get_template_part( 'template-parts/content/content', 'excerpt' ); ?>
            <?php endwhile; ?>

        </div>

        <?php ccg_pagination(); ?>

    <?php else : ?>

        <p class="text-center text-slate-500">Nu există articole de afișat.</p>

    <?php endif; ?>

</div>

<?php get_footer(); ?>
