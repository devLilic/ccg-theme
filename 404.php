<?php
/**
 * 404 Page
 */

get_header();
?>

<div class="container mx-auto px-4 py-20 text-center">

    <h1 class="text-5xl font-bold text-ccg-primary mb-4">404</h1>

    <p class="text-slate-600 mb-6">
        Pagina pe care o cauți nu există.
    </p>

    <a href="<?php echo esc_url( home_url('/') ); ?>"
       class="inline-flex items-center bg-ccg-primary text-white px-5 py-2 rounded-xl hover:bg-ccg-primaryDark text-sm font-semibold">
        Mergi la pagina principală
    </a>

</div>

<?php get_footer(); ?>
