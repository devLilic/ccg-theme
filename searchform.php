<form role="search" method="get" class="ccg-search-form flex gap-2" action="<?php echo esc_url( home_url( '/' ) ); ?>">

    <div class="flex-1">
        <input
            type="search"
            name="s"
            value="<?php echo get_search_query(); ?>"
            placeholder="Caută pe site..."
            class="w-full rounded-xl border border-slate-300 px-4 py-2 text-xl focus:border-ccg-primary focus:ring-ccg-primary"
        >
    </div>

    <button type="submit"
            class="px-4 py-2 rounded-xl bg-ccg-primary text-white font-semibold hover:bg-ccg-primaryDark transition">
        Caută
    </button>

</form>
