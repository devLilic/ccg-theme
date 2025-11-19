<?php
/**
 * Content Excerpt (Card)
 */
?>

<article <?php post_class('bg-white rounded-2xl shadow-sm overflow-hidden flex flex-col'); ?>>

    <?php if ( has_post_thumbnail() ) : ?>
        <a href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail( 'medium_large', [
                    'class' => 'w-full h-48 object-cover'
            ] ); ?>
        </a>
    <?php endif; ?>

    <div class="p-4 flex flex-col flex-1">

        <div class="text-xs text-slate-500 mb-2">
            <?php echo get_the_date(); ?>
        </div>

        <h2 class="text-lg font-semibold mb-2 leading-tight">
            <a href="<?php the_permalink(); ?>" class="hover:text-ccg-primary">
                <?php the_title(); ?>
            </a>
        </h2>

        <div class="text-sm text-slate-600 line-clamp-3 mb-4">
            <?php the_excerpt(); ?>
        </div>

        <a href="<?php the_permalink(); ?>"
           class="mt-auto inline-flex items-center text-sm font-medium text-ccg-primary hover:text-ccg-primaryDark">
            Citește mai mult →
        </a>

    </div>
</article>
