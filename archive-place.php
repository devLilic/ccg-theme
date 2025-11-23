<?php get_header(); ?>

<section class="py-10 bg-slate-50">
    <div class="container mx-auto px-4">

        <h1 class="text-3xl font-bold mb-6">Locuri turistice</h1>

        <!-- OPTIONAL: FILTRE -->
        <?php get_template_part('template-parts/places/filter-bar'); ?>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
            <?php if (have_posts()): ?>
                <?php while (have_posts()): the_post(); ?>
                    <?php get_template_part('template-parts/places/card-place'); ?>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Nu au fost găsite locuri.</p>
            <?php endif; ?>
        </div>

        <!-- PAGINATION -->
        <div class="mt-10">
            <?php the_posts_pagination([
                    'mid_size' => 2,
                    'prev_text' => '« Anterior',
                    'next_text' => 'Următor »',
            ]); ?>
        </div>

    </div>
</section>

<?php get_footer(); ?>
