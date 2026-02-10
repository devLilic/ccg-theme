<?php
/**
 * Content Single Article
 */
?>

<article <?php post_class('mx-auto max-w-3xl px-1 md:py-2'); ?>>
    <?php if ( has_post_thumbnail() ) : ?>
        <div class="mb-7 overflow-hidden md:rounded-2xl shadow-sm -mx-5">
            <?php the_post_thumbnail( 'large', [
                'class' => 'w-full h-auto'
            ] ); ?>
        </div>
    <?php endif; ?>



    <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-slate-900 mb-3 text-justify">
        <?php the_title(); ?>
    </h1>

    <div class="mb-3 text-sm text-slate-500 text-right">
        Publicat la <?php echo esc_html( get_the_date('j.m.Y') ); ?>
    </div>

    <!-- CONTENT -->
    <div class="ccg-prose">
        <?php the_content(); ?>
    </div>

    <?php if ( function_exists('ccg_gallery_render') ) : ?>
        <div class="mt-10">
            <?php ccg_gallery_render( get_the_ID() ); ?>
        </div>
    <?php endif; ?>

</article>
