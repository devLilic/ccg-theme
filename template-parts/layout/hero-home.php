<?php
/**
 * HERO – Pagina principală
 */

// Imaginile din Customizer
$hero_bg = get_theme_mod('ccg_hero_bg_image');
$hero_circle = get_theme_mod('ccg_hero_circle_image');

// Fallback-uri (dacă nu sunt setate încă)
if (!$hero_bg) {
    $hero_bg = get_template_directory_uri() . '/assets/img/hero-bg-placeholder.jpg';
}
if (!$hero_circle) {
    $hero_circle = get_template_directory_uri() . '/assets/img/hero-circle-placeholder.jpg';
}
?>

<section
        class="relative overflow-hidden min-h-[380px] md:min-h-[520px] lg:min-h-[720px] flex items-center"
        style="background-image: url('<?php echo esc_url( $hero_bg ); ?>'); background-size: cover; background-position: center;"
>
    <!-- VIGNETTE + WAVE BOTTOM -->
    <div class="absolute inset-0 bg-gradient-to-b from-white/50 via-transparent to-white pointer-events-none"></div>

    <svg
            class="absolute bottom-0 left-0 w-full"
            style="color:#f1f5f9;"
            viewBox="0 0 1440 120"
            fill="currentColor"
            xmlns="http://www.w3.org/2000/svg"
    >
        <path d="M0,60 C300,140 600,0 900,60 C1200,120 1440,20 1440,20 L1440,120 L0,120 Z"/>
    </svg>


    <!-- Overlay pentru lizibilitate -->
    <div class="absolute inset-0 bg-white/30 backdrop-blur-sm"></div>

    <div class="container mx-auto px-4 py-20 md:py-28 relative z-10">
        <div class="grid gap-12 md:grid-cols-2 items-center">

            <!-- LEFT TEXT -->
            <div class="bg-white/40 backdrop-blur-sm p-2 rounded-2xl shadow-lg ">
                <span class="inline-flex items-center rounded-full bg-ccg-primary/10 px-3 py-1 text-xs font-semibold text-ccg-primary mb-4">
                    Călătorii • Gastronomie • Tradiții
                </span>

                <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-slate-900 mb-4 leading-tight">
                    Descoperă Moldova <span class="text-ccg-primary">cu gust</span>
                </h1>

                <p class="text-slate-600 text-base md:text-lg mb-6 max-w-xl">
                    Locuri autentice, evenimente locale și rute turistice – toate într-o singură platformă.
                    <br>
                    De la sate pitorești și mănăstiri istorice, până la trasee turistice, lacuri, vinării și experiențe culturale — toate se regăsesc în poveștile noastre.
                </p>

                <!-- SEARCH BOX -->
<!--                <form class="bg-white rounded-2xl shadow-sm p-3 md:p-4 mb-4 flex flex-col md:flex-row gap-3">-->
<!--                    <div class="flex-1">-->
<!--                        <label for="ccg-search" class="sr-only">Căutare</label>-->
<!--                        <input-->
<!--                                id="ccg-search"-->
<!--                                type="text"-->
<!--                                class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ccg-primary focus:ring-ccg-primary"-->
<!--                                placeholder="Caută locuri, evenimente, rute..."-->
<!--                        >-->
<!--                    </div>-->
<!---->
<!--                    <div>-->
<!--                        <label for="ccg-type" class="sr-only">Tip</label>-->
<!--                        <select-->
<!--                                id="ccg-type"-->
<!--                                class="w-full md:w-40 rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ccg-primary focus:ring-ccg-primary"-->
<!--                        >-->
<!--                            <option>Toate</option>-->
<!--                            <option>Locuri</option>-->
<!--                            <option>Evenimente</option>-->
<!--                            <option>Rute</option>-->
<!--                        </select>-->
<!--                    </div>-->
<!---->
<!--                    <button-->
<!--                            type="submit"-->
<!--                            class="inline-flex items-center justify-center rounded-xl bg-ccg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-ccg-primaryDark transition"-->
<!--                    >-->
<!--                        Caută-->
<!--                    </button>-->
<!--                </form>-->

                <!-- QUICK LINKS -->
<!--                <div class="flex flex-wrap gap-2 text-sm">-->
<!--                    <a href="#"-->
<!--                       class="inline-flex px-3 py-1.5 rounded-full bg-white/90 border border-slate-200 hover:border-ccg-primary hover:text-ccg-primary">-->
<!--                        Locuri turistice-->
<!--                    </a>-->
<!--                    <a href="#"-->
<!--                       class="inline-flex px-3 py-1.5 rounded-full bg-white/90 border border-slate-200 hover:border-ccg-primary hover:text-ccg-primary">-->
<!--                        Evenimente-->
<!--                    </a>-->
<!--                    <a href="#"-->
<!--                       class="inline-flex px-3 py-1.5 rounded-full bg-white/90 border border-slate-200 hover:border-ccg-primary hover:text-ccg-primary">-->
<!--                        Rute turistice-->
<!--                    </a>-->
<!--                </div>-->
            </div>

            <!-- RIGHT CIRCLE IMAGE -->
            <div class="flex justify-center md:justify-end">
                <div class="relative">
                    <!-- Glow în spate -->
                    <div class="absolute -inset-10 rounded-full bg-ccg-primary/15 blur-3xl opacity-70"></div>

                    <div class="relative w-56 h-56 md:w-72 md:h-72 lg:w-80 lg:h-80 rounded-full overflow-hidden shadow-xl ring-4 ring-white">
                        <img
                                src="<?php echo esc_url($hero_circle); ?>"
                                alt="Calatorii cu Gust"
                                class="w-full h-full object-cover"
                        >
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
