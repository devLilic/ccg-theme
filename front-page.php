<?php
/**
 * Template Name: Front Page
 * Front page pentru versiunea BLOG (fără CPT-uri, fără ACF).
 */

get_header();

// HERO
get_template_part( 'template-parts/layout/hero-home' );
?>

<div class="bg-slate-50">

    <!-- =============================== -->
    <!-- ULTIMELE ARTICOLE (BLOG)       -->
    <!-- =============================== -->
    <section class="py-10 md:py-12">
        <div class="container mx-auto px-4">

            <div class="flex items-center justify-between gap-3 mb-6">
                <div>
                    <h2 class="text-xl md:text-2xl font-bold text-slate-900">
                        Ultimele articole
                    </h2>
                    <p class="text-sm text-slate-500">
                        Cele mai noi povestiri și recomandări de călătorie.
                    </p>
                </div>

                <a href="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>"
                   class="hidden md:inline-flex text-sm font-medium text-ccg-primary hover:text-ccg-primaryDark">
                    Vezi toate articolele →
                </a>
            </div>

            <div class="grid gap-6 md:grid-cols-3">

                <?php
                // Query pentru ultimele 6 articole
                $ccg_latest = new WP_Query([
                        'post_type'      => 'post',
                        'posts_per_page' => 6,
                ]);

                if ( $ccg_latest->have_posts() ) :
                    while ( $ccg_latest->have_posts() ) :
                        $ccg_latest->the_post();
                        get_template_part( 'template-parts/content/content', 'excerpt' );
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<p class="text-slate-500">Nu există articole disponibile.</p>';
                endif;
                ?>

            </div>

            <div class="mt-4 md:hidden">
                <a href="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>"
                   class="inline-flex text-sm font-medium text-ccg-primary hover:text-ccg-primaryDark">
                    Vezi toate articolele →
                </a>
            </div>

        </div>
    </section>


    <!-- =============================== -->
    <!-- SECTIUNEA "DESPRE NOI"         -->
    <!-- =============================== -->
    <section class="py-10 md:py-12 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-10 items-center">

                <div>
                    <h2 class="text-2xl font-bold text-slate-900 mb-3">
                        Calatorii Cu Gust
                    </h2>
                    <p class="text-slate-600 leading-relaxed mb-4">
                        Un proiect dedicat promovării locurilor autentice, tradițiilor și gastronomiei din Moldova.
                        Prin articole, fotografii și recomandări, încurajăm explorarea locală și descoperirea
                        frumuseților din jurul nostru.
                    </p>
                    <p class="text-slate-600 leading-relaxed mb-6">
                        De la sate pitorești și mănăstiri istorice, până la trasee turistice, lacuri, vinării și
                        experiențe culturale — toate se regăsesc în poveștile noastre.
                    </p>

                    <a href="/despre-noi"
                       class="inline-flex items-center text-sm font-semibold text-ccg-primary hover:text-ccg-primaryDark">
                        Află mai multe →
                    </a>
                </div>

                <div>
                    <div class="rounded-2xl overflow-hidden shadow-md">
                        <img
                                src="https://picsum.photos/900/600?random=222"
                                alt="Despre noi"
                                class="w-full h-auto object-cover"
                        >
                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- =============================== -->
    <!-- NEWSLETTER (CTA)               -->
    <!-- =============================== -->
    <section class="py-10 md:py-12">
        <div class="container mx-auto px-4">
            <div class="rounded-3xl bg-ccg-primary text-white px-6 py-8 md:px-10 md:py-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">

                <div>
                    <h2 class="text-2xl font-bold mb-2">
                        Primește idei de călătorie și articole noi
                    </h2>
                    <p class="text-rose-50/90 max-w-xl text-sm md:text-base">
                        Abonează-te ca să primești periodic articole cu trasee, evenimente și locuri frumoase din Moldova.
                    </p>
                </div>

                <form class="w-full md:w-auto flex flex-col sm:flex-row gap-3">
                    <input
                            type="email"
                            class="w-full sm:w-64 rounded-xl px-3 py-2 text-sm text-slate-900 border-0 focus:ring-2 focus:ring-white"
                            placeholder="Emailul tău"
                    >
                    <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-white px-4 py-2 text-sm font-semibold text-ccg-primary hover:bg-rose-50 transition"
                    >
                        Mă abonez
                    </button>
                </form>

            </div>
        </div>
    </section>

</div>

<?php get_footer(); ?>
