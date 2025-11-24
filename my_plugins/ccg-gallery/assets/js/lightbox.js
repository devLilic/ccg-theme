(function ($) {
    let $lightbox, $image, $caption;
    let items = [];
    let currentIndex = 0;

    function openLightbox(index) {
        $('body').addClass('ccg-no-scroll');

        currentIndex = index;
        let item = items[currentIndex];

        $image.attr('src', item.href);
        $image.attr('alt', item.alt);
        $caption.text(item.caption || '');

        $lightbox.addClass('is-active');
    }

    function closeLightbox() {
        $('body').removeClass('ccg-no-scroll');
        $lightbox.removeClass('is-active');
    }

    function navigate(delta) {
        currentIndex = (currentIndex + delta + items.length) % items.length;
        openLightbox(currentIndex);
    }

    $(document).ready(function () {
        $lightbox = $('#ccg-lightbox');
        $image = $lightbox.find('.ccg-lightbox__image');
        $caption = $lightbox.find('.ccg-lightbox__caption');

        const selector = '.ccg-gallery [data-lightbox]';

        $(document).on('click', selector, function (e) {
            e.preventDefault();

            const group = $(this).data('lightbox');
            const $groupItems = $('[data-lightbox="' + group + '"]');

            items = [];
            $groupItems.each(function (i) {
                const $el = $(this);
                items.push({
                    href: $el.attr('href'),
                    alt: $el.find('img').attr('alt') || '',
                    caption: $el.data('caption') || ''
                });

                if ($el[0] === e.currentTarget) {
                    currentIndex = i;
                }
            });

            openLightbox(currentIndex);
        });

        $lightbox.on('click', '.ccg-lightbox__close, .ccg-lightbox__overlay', closeLightbox);
        $lightbox.on('click', '.ccg-lightbox__next', () => navigate(1));
        $lightbox.on('click', '.ccg-lightbox__prev', () => navigate(-1));

        $(document).on('keyup', function (e) {
            if (!$lightbox.hasClass('is-active')) return;

            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowRight') navigate(1);
            if (e.key === 'ArrowLeft') navigate(-1);
        });

        // swipe on mobile
        let startX = 0;
        $image.on('touchstart', e => startX = e.originalEvent.touches[0].clientX);
        $image.on('touchend', e => {
            let dx = e.originalEvent.changedTouches[0].clientX - startX;
            if (dx > 50) navigate(-1);
            if (dx < -50) navigate(1);
        });
    });
})(jQuery);
