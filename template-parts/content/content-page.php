<?php
/**
 * Content Page
 */
?>

<article <?php post_class('prose prose-slate max-w-none'); ?>>

    <h1 class="text-3xl font-bold mb-6">
        <?php the_title(); ?>
    </h1>

    <div class="content-body text-slate-700 leading-relaxed">
        <?php the_content(); ?>
    </div>

</article>
