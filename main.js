// main entry point
// include your assets here

// get styles
import "./assets/css/styles.css"

// get scripts
import './assets/js/scripts.js'

/*
document.querySelector('#app').innerHTML = `
  <h1>Hello Vite!</h1>
  <a href="https://vitejs.dev/guide/features.html" target="_blank">Documentation</a>
`
*/
document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('mobile-menu-toggle');
    const menu = document.getElementById('mobile-menu');
    const overlay = document.getElementById('mobile-menu-overlay');

    const iconHamburger = document.getElementById('icon-hamburger');
    const iconClose = document.getElementById('icon-close');

    const openMenu = () => {
        if (!menu || !overlay) return;
        menu.classList.remove('-translate-y-full');
        menu.classList.add('mt-[72px]');
        overlay.classList.remove('hidden');
        setTimeout(() => overlay.classList.remove('opacity-0'), 10);

        iconHamburger?.classList.add('hidden');
        iconClose?.classList.remove('hidden');

        document.body.classList.add('overflow-hidden');
    };

    const closeMenu = () => {
        if (!menu || !overlay) return;

        menu.classList.add('-translate-y-full');
        menu.classList.remove('mt-[72px]');

        overlay.classList.add('opacity-0');
        setTimeout(() => overlay.classList.add('hidden'), 300);

        iconHamburger?.classList.remove('hidden');
        iconClose?.classList.add('hidden');

        document.body.classList.remove('overflow-hidden');
    };

    if (toggle && menu && overlay) {
        toggle.addEventListener('click', () => {
            const isOpen = !menu.classList.contains('-translate-y-full');
            isOpen ? closeMenu() : openMenu();
        });

        overlay.addEventListener('click', closeMenu);
    }




    // ------- Submeniuri pe MOBIL (accordion) -------

    const mobileMenu = document.querySelector('.ccg-mobile-menu');
    if (mobileMenu) {
        // Pentru fiecare item cu submeniu
        mobileMenu.querySelectorAll('.menu-item-has-children').forEach((item) => {
            const link = item.querySelector(':scope > a');
            const submenu = item.querySelector(':scope > .sub-menu');
            if (!link || !submenu) return;

            // Wrapper pentru link + buton
            const wrapper = document.createElement('div');
            wrapper.className = 'ccg-mobile-link-wrapper';

            // Mutăm linkul în wrapper
            link.parentNode.insertBefore(wrapper, link);
            wrapper.appendChild(link);

            // Stil pentru link
            link.classList.add('ccg-mobile-menu-link');

            // Buton +/−
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'ccg-mobile-submenu-toggle';
            btn.setAttribute('aria-expanded', 'false');
            btn.innerHTML = '+';
            wrapper.appendChild(btn);

            // Stil pentru submeniu
            submenu.classList.add('ccg-mobile-submenu');

            btn.addEventListener('click', () => {
                const isOpen = submenu.classList.contains('ccg-mobile-submenu--open');
                if (isOpen) {
                    submenu.classList.remove('ccg-mobile-submenu--open');
                    btn.innerHTML = '+';
                    btn.setAttribute('aria-expanded', 'false');
                } else {
                    submenu.classList.add('ccg-mobile-submenu--open');
                    btn.innerHTML = '-';
                    btn.setAttribute('aria-expanded', 'true');
                }
            });
        });
        // ------- Itemuri simple (fără submenu) -------
        mobileMenu.querySelectorAll('.menu-item:not(.menu-item-has-children)').forEach((item) => {
            const link = item.querySelector(':scope > a');
            if (!link) return;

            // Wrapper identic cu cel folosit la itemurile cu submenu
            const wrapper = document.createElement('div');
            wrapper.className = 'ccg-mobile-link-wrapper';

            // Mutăm linkul în wrapper
            link.parentNode.insertBefore(wrapper, link);
            wrapper.appendChild(link);

            // Aplicăm stil pentru link
            link.classList.add('ccg-mobile-menu-link');
        });
    }


    const searchOpen = document.getElementById('ccg-search-open');
    const searchClose = document.getElementById('ccg-search-close');
    const searchPanel = document.getElementById('ccg-search-panel');

    if (searchOpen && searchPanel) {
        searchOpen.addEventListener('click', () => {
            searchPanel.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            closeMenu()
        });
    }

    if (searchClose && searchPanel) {
        searchClose.addEventListener('click', () => {
            searchPanel.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        });
    }
});

document.addEventListener("DOMContentLoaded", () => {


});
