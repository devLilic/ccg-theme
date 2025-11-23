(function () {
    'use strict';

    /**
     * Stare internă lightbox.
     */
    var galleries = {};
    var currentGroup = null;
    var currentIndex = 0;
    var isOpen = false;

    var lightboxEl, imageEl, captionEl, counterEl;
    var closeBtns, prevBtn, nextBtn, backdropEl;

    var touchStartX = null;
    var touchEndX = null;
    var TOUCH_THRESHOLD = 50;

    /**
     * Creează și cache-uieste elementele din lightbox (din markup-ul din footer).
     */
    function initLightboxElements() {
        lightboxEl = document.querySelector('[data-ccg-lightbox]');
        if (!lightboxEl) {
            return;
        }

        imageEl = lightboxEl.querySelector('[data-ccg-lightbox-image]');
        captionEl = lightboxEl.querySelector('[data-ccg-lightbox-caption]');
        counterEl = lightboxEl.querySelector('[data-ccg-lightbox-counter]');
        prevBtn = lightboxEl.querySelector('[data-ccg-lightbox-prev]');
        nextBtn = lightboxEl.querySelector('[data-ccg-lightbox-next]');
        backdropEl = lightboxEl.querySelector('[data-ccg-lightbox-close]');

        closeBtns = lightboxEl.querySelectorAll('[data-ccg-lightbox-close]');

        // Event handlers.
        if (prevBtn) {
            prevBtn.addEventListener('click', function () {
                changeSlide(-1);
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function () {
                changeSlide(1);
            });
        }

        if (closeBtns && closeBtns.length) {
            Array.prototype.forEach.call(closeBtns, function (btn) {
                btn.addEventListener('click', function () {
                    closeLightbox();
                });
            });
        }

        // Swipe touch events.
        if (lightboxEl) {
            lightboxEl.addEventListener('touchstart', function (e) {
                if (e.touches && e.touches.length === 1) {
                    touchStartX = e.touches[0].clientX;
                }
            });

            lightboxEl.addEventListener('touchmove', function (e) {
                if (e.touches && e.touches.length === 1) {
                    touchEndX = e.touches[0].clientX;
                }
            });

            lightboxEl.addEventListener('touchend', function () {
                if (touchStartX !== null && touchEndX !== null) {
                    var diff = touchEndX - touchStartX;
                    if (Math.abs(diff) > TOUCH_THRESHOLD) {
                        if (diff > 0) {
                            // Swipe right -> imaginea anterioară.
                            changeSlide(-1);
                        } else {
                            // Swipe left -> imaginea următoare.
                            changeSlide(1);
                        }
                    }
                }
                touchStartX = null;
                touchEndX = null;
            });
        }

        // Keydown global.
        document.addEventListener('keydown', function (e) {
            if (!isOpen) {
                return;
            }

            if (e.key === 'Escape' || e.key === 'Esc') {
                e.preventDefault();
                closeLightbox();
            } else if (e.key === 'ArrowRight') {
                e.preventDefault();
                changeSlide(1);
            } else if (e.key === 'ArrowLeft') {
                e.preventDefault();
                changeSlide(-1);
            }
        });
    }

    /**
     * Construiește harta de galerii: group -> array de elemente.
     */
    function buildGalleries() {
        var triggers = document.querySelectorAll('[data-ccg-gallery]');
        if (!triggers.length) {
            return;
        }

        Array.prototype.forEach.call(triggers, function (el) {
            var group = el.getAttribute('data-ccg-gallery');
            if (!group) {
                return;
            }

            var indexAttr = el.getAttribute('data-ccg-gallery-index');
            var index = parseInt(indexAttr || '0', 10);

            if (!galleries[group]) {
                galleries[group] = [];
            }

            galleries[group].push({
                el: el,
                index: index
            });

            // Click handler pentru fiecare element.
            el.addEventListener('click', function (event) {
                // Dacă e <a>, prevenim navigarea.
                if (el.tagName.toLowerCase() === 'a') {
                    event.preventDefault();
                }
                openLightbox(group, index);
            });
        });

        // Sortare pe index, pentru fiecare grup.
        Object.keys(galleries).forEach(function (groupKey) {
            galleries[groupKey].sort(function (a, b) {
                return a.index - b.index;
            });
        });
    }

    /**
     * Deschide lightbox pentru un anumit grup și index.
     *
     * @param {string} group
     * @param {number} index
     */
    function openLightbox(group, index) {
        if (!lightboxEl || !galleries[group] || !galleries[group].length) {
            return;
        }

        currentGroup = group;

        // Căutăm poziția reală în array pentru index-ul cerut.
        var items = galleries[group];
        var actualIndex = 0;

        for (var i = 0; i < items.length; i++) {
            if (items[i].index === index) {
                actualIndex = i;
                break;
            }
        }

        currentIndex = actualIndex;

        updateSlide();

        lightboxEl.hidden = false;
        lightboxEl.setAttribute('aria-hidden', 'false');
        document.body.classList.add('ccg-lightbox-open');
        isOpen = true;
    }

    /**
     * Închide lightbox.
     */
    function closeLightbox() {
        if (!lightboxEl) {
            return;
        }

        lightboxEl.hidden = true;
        lightboxEl.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('ccg-lightbox-open');
        isOpen = false;
        currentGroup = null;
        currentIndex = 0;
    }

    /**
     * Schimbă slide-ul curent din galerie.
     *
     * @param {number} step +1 sau -1.
     */
    function changeSlide(step) {
        if (!currentGroup || !galleries[currentGroup]) {
            return;
        }

        var items = galleries[currentGroup];
        if (!items.length) {
            return;
        }

        currentIndex = currentIndex + step;

        if (currentIndex < 0) {
            currentIndex = items.length - 1;
        } else if (currentIndex >= items.length) {
            currentIndex = 0;
        }

        updateSlide();
    }

    /**
     * Actualizează imaginea afișată în lightbox.
     */
    function updateSlide() {
        if (!currentGroup || !galleries[currentGroup] || !galleries[currentGroup].length) {
            return;
        }

        var items = galleries[currentGroup];

        if (currentIndex < 0 || currentIndex >= items.length) {
            currentIndex = 0;
        }

        var item = items[currentIndex].el;
        if (!item || !imageEl) {
            return;
        }

        var src = item.getAttribute('data-ccg-gallery-src') || item.getAttribute('href') || '';
        var caption = item.getAttribute('data-ccg-gallery-caption') || '';
        var alt = '';

        // Căutăm eventualul <img> copil pentru alt.
        var imgChild = item.querySelector('img');
        if (imgChild && imgChild.getAttribute('alt')) {
            alt = imgChild.getAttribute('alt');
        }

        // Setăm src + alt.
        imageEl.src = src;
        imageEl.alt = alt || caption || '';

        if (captionEl) {
            captionEl.textContent = caption || '';
        }

        if (counterEl) {
            counterEl.textContent = (currentIndex + 1) + ' / ' + items.length;
        }
    }

    /**
     * Init general la DOMContentLoaded.
     */
    document.addEventListener('DOMContentLoaded', function () {
        initLightboxElements();
        buildGalleries();
    });
})();
