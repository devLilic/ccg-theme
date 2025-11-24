<?php
/**
 * Content Single Article
 */
?>

<article <?php post_class('prose prose-slate max-w-none'); ?>>

    <?php if ( has_post_thumbnail() ) : ?>
        <div class="mb-6">
            <?php the_post_thumbnail( 'large', [
                    'class' => 'rounded-xl w-full h-auto'
            ] ); ?>
        </div>
    <?php endif; ?>

    <div class="text-sm text-slate-500 mb-4">
        Publicat la <?php echo get_the_date(); ?>
    </div>

    <h1 class="text-3xl font-bold mb-6">
        <?php the_title(); ?>
    </h1>

    <div class="content-body text-slate-700 leading-relaxed">
        <?php the_content(); ?>
    </div>
<?php ccg_gallery_render( get_the_ID() ); ?>
</article>
