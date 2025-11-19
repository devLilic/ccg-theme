<?php get_header(); ?>

<div class="container mx-auto px-4 py-8">
    <?php if ( have_posts() ) : ?>
        <div class="grid gap-6 md:grid-cols-3">
            <?php while ( have_posts() ) : the_post(); ?>
                <article <?php post_class('bg-white shadow-sm rounded-xl p-4'); ?>>
                    <?php if ( has_post_thumbnail() ) : ?>
                        <a href="<?php the_permalink(); ?>" class="block mb-3">
                            <?php the_post_thumbnail( 'medium', [ 'class' => 'w-full h-48 object-cover rounded-lg' ] ); ?>
                        </a>
                    <?php endif; ?>

                    <h2 class="text-lg font-semibold mb-2">
                        <a href="<?php the_permalink(); ?>" class="hover:text-ccg-primary">
                            <?php the_title(); ?>
                        </a>
                    </h2>

                    <div class="text-sm text-slate-500 mb-3">
                        <?php echo get_the_date(); ?>
                    </div>

                    <div class="text-sm text-slate-600">
                        <?php the_excerpt(); ?>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>

        <div class="mt-8">
            <?php the_posts_pagination(); ?>
        </div>
    <?php else : ?>
        <p><?php _e( 'Nu există articole de afișat.', 'calatoriicugust' ); ?></p>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
