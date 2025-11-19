<?php
/**
 * HERO – Pagina principală
 * Layout curat, fără logică de date.
 */
?>

<section class="relative overflow-hidden">
    <div class="container mx-auto px-4 py-12 md:py-16">
        <div class="grid gap-10 md:grid-cols-2 items-center">

            <!-- LEFT TEXT -->
            <div>
                <span class="inline-flex items-center rounded-full bg-ccg-primary/10 px-3 py-1 text-xs font-semibold text-ccg-primary mb-4">
                    Călătorii • Gastronomie • Tradiții
                </span>

                <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-slate-900 mb-4 leading-tight">
                    Descoperă Moldova <span class="text-ccg-primary">cu gust</span>
                </h1>

                <p class="text-slate-600 text-base md:text-lg mb-6 max-w-xl">
                    Locuri autentice, evenimente locale și rute turistice – toate într-o singură platformă.
                </p>

                <!-- SEARCH BOX -->
                <form class="bg-white rounded-2xl shadow-sm p-3 md:p-4 mb-4 flex flex-col md:flex-row gap-3">
                    <div class="flex-1">
                        <label for="ccg-search" class="sr-only">Căutare</label>
                        <input
                            id="ccg-search"
                            type="text"
                            class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ccg-primary focus:ring-ccg-primary"
                            placeholder="Caută locuri, evenimente, rute..."
                        >
                    </div>

                    <div>
                        <label for="ccg-type" class="sr-only">Tip</label>
                        <select
                            id="ccg-type"
                            class="w-full md:w-40 rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-ccg-primary focus:ring-ccg-primary"
                        >
                            <option>Toate</option>
                            <option>Locuri</option>
                            <option>Evenimente</option>
                            <option>Rute</option>
                        </select>
                    </div>

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-xl bg-ccg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-ccg-primaryDark transition"
                    >
                        Caută
                    </button>
                </form>

                <!-- QUICK LINKS -->
                <div class="flex flex-wrap gap-2 text-sm">
                    <a href="#" class="inline-flex px-3 py-1.5 rounded-full bg-white border border-slate-200 hover:border-ccg-primary hover:text-ccg-primary">
                        Locuri turistice
                    </a>
                    <a href="#" class="inline-flex px-3 py-1.5 rounded-full bg-white border border-slate-200 hover:border-ccg-primary hover:text-ccg-primary">
                        Evenimente
                    </a>
                    <a href="#" class="inline-flex px-3 py-1.5 rounded-full bg-white border border-slate-200 hover:border-ccg-primary hover:text-ccg-primary">
                        Rute turistice
                    </a>
                </div>
            </div>

            <!-- RIGHT VISUAL ELEMENT -->
            <div class="flex justify-center md:justify-end">
                <div class="relative">
                    <div class="absolute -inset-6 rounded-full bg-ccg-primary/5 blur-2xl"></div>

                    <div class="relative bg-white rounded-full shadow-md p-6 md:p-8 flex items-center justify-center">
                        <img
                            src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/logo-ccg.png' ); ?>"
                            alt="Calatorii cu Gust"
                            class="w-40 h-40 md:w-52 md:h-52 object-contain"
                        >
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
