<?php
/**
 * Page Template
 */

get_header();
?>

<?php
get_template_part( 'template-parts/layout/hero-inner', null, [
        'title' => get_the_title(),
]);
?>

<div class="container mx-auto px-4 py-10">
    <div class="max-w-3xl mx-auto">

        <?php
        while ( have_posts() ) : the_post();
            get_template_part( 'template-parts/content/content', 'page' );
        endwhile;
        ?>

    </div>
</div>

<?php get_footer(); ?>
