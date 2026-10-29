/* Case-study motion, static previews, and a three-second monitor slideshow. */
(function () {
    'use strict';
    function init() {
        const motion = matchMedia('(prefers-reduced-motion: reduce)');
        function reveal(element, delay = 0) {
            if (motion.matches || !element.animate) return;
            const animation = element.animate([
                { opacity: 0, transform: 'translateY(10px)' },
                { opacity: 1, transform: 'translateY(0)' }
            ], { duration: 450, delay, easing: 'cubic-bezier(.22,1,.36,1)', fill: 'backwards' });
            const changed = () => { if (motion.matches) animation.cancel(); };
            motion.addEventListener('change', changed);
            animation.finished.catch(() => {}).finally(() => motion.removeEventListener('change', changed));
        }
        const hero = document.querySelector('.pd-hero-top');
        if (hero) reveal(hero);
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver(entries => entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                reveal(entry.target);
                observer.unobserve(entry.target);
            }), { threshold: 0.06 });
            document.querySelectorAll('.pd-reveal').forEach(element => observer.observe(element));
        }
        const tocLinks = [...document.querySelectorAll('.pd-toc a')];
        if (tocLinks.length && 'IntersectionObserver' in window) {
            const visible = new Set();
            const observer = new IntersectionObserver(entries => {
                entries.forEach(entry => entry.isIntersecting ? visible.add(entry.target) : visible.delete(entry.target));
                const first = [...visible].sort((a, b) => a.getBoundingClientRect().top - b.getBoundingClientRect().top)[0];
                if (!first) return;
                tocLinks.forEach(link => {
                    if (link.hash === '#' + first.id) link.setAttribute('aria-current', 'location');
                    else link.removeAttribute('aria-current');
                });
            }, { rootMargin: '-100px 0px -55% 0px', threshold: 0 });
            tocLinks.forEach(link => { const target = document.getElementById(link.hash.slice(1)); if (target) observer.observe(target); });
        }
        const gallery = document.querySelector('[data-project-gallery]');
        if (!gallery) return;
        const image = gallery.querySelector('[data-gallery-image]');
        const openLink = gallery.querySelector('[data-gallery-open]');
        const thumbs = [...document.querySelectorAll('[data-screen-index]')];
        const dialog = document.querySelector('[data-gallery-dialog]');
        if (!image || !openLink || !thumbs.length) return;
        const urls = thumbs.map(thumb => thumb.href);
        const label = image.alt.replace(/ screenshot \d+$/, '');
        let current = 0;
        let imageAnimation = null;
        const pad = number => String(number).padStart(2, '0');
        function select(index, manual = true) {
            current = (index + urls.length) % urls.length;
            const selected = current;
            imageAnimation?.cancel();
            image.src = urls[current];
            image.alt = label + ' screenshot ' + (current + 1);
            openLink.href = urls[current];
            openLink.setAttribute('aria-label', 'View screenshot ' + (current + 1) + ' at full size');
            gallery.querySelector('[data-gallery-count]').textContent = pad(current + 1) + ' / ' + pad(urls.length);
            gallery.querySelector('[data-gallery-caption]').textContent = 'Screen ' + pad(current + 1) + ' of ' + pad(urls.length);
            document.querySelectorAll('.pd-preview-rail [data-screen-index]').forEach(thumb => Number(thumb.dataset.screenIndex) === current ? thumb.setAttribute('aria-current', 'true') : thumb.removeAttribute('aria-current')); 
            if (dialog) {
                const fullImage = dialog.querySelector('[data-dialog-image]');
                fullImage.src = urls[current];
                fullImage.alt = image.alt;
                dialog.querySelector('[data-dialog-count]').textContent = 'Screen ' + (current + 1) + ' of ' + urls.length;
            }
            const ready = image.decode ? image.decode() : Promise.resolve();
            ready.then(() => {
                if (selected !== current || motion.matches || !image.animate) return;
                imageAnimation = image.animate([{ opacity: .4 }, { opacity: 1 }], { duration: 180, easing: 'ease-out' });
            }).catch(() => {});
            if (manual) scheduleSlides();
        }
        thumbs.forEach((thumb, index) => thumb.addEventListener('click', event => { event.preventDefault(); select(index); }));
        gallery.querySelector('[data-gallery-prev]').addEventListener('click', () => select(current - 1));
        gallery.querySelector('[data-gallery-next]').addEventListener('click', () => select(current + 1));
        gallery.querySelector('[data-gallery-controls]').hidden = urls.length < 2;
        if (dialog && typeof dialog.showModal === 'function') {
            openLink.addEventListener('click', event => {
                event.preventDefault();
                stopSlides();
                dialog.showModal();
                document.documentElement.classList.add('pd-viewer-open');
            });
            dialog.querySelector('[data-gallery-close]').addEventListener('click', () => dialog.close());
            dialog.querySelector('[data-dialog-prev]').addEventListener('click', () => select(current - 1));
            dialog.querySelector('[data-dialog-next]').addEventListener('click', () => select(current + 1));
            dialog.addEventListener('close', () => document.documentElement.classList.remove('pd-viewer-open'));
            dialog.addEventListener('click', event => {
                if (event.target !== dialog) return;
                const rect = dialog.getBoundingClientRect();
                if (event.clientX < rect.left || event.clientX > rect.right || event.clientY < rect.top || event.clientY > rect.bottom) dialog.close();
            });
            if (urls.length < 2) dialog.querySelectorAll('[data-dialog-prev],[data-dialog-next]').forEach(button => button.hidden = true);
        }
        // Static previews remain manually scrollable beside the monitor.
        const rail = document.querySelector('[data-preview-scroll]');
        const computer = gallery.querySelector('.pd-computer');
        if (rail && computer) {
            function fitPreviewRail() {
                const height = computer.getBoundingClientRect().height;
                if (height) rail.parentElement.style.setProperty('--pd-preview-height', height + 'px');
            }
            if ('ResizeObserver' in window) new ResizeObserver(fitPreviewRail).observe(computer);
            else window.addEventListener('resize', fitPreviewRail, { passive: true });
            fitPreviewRail();
        }

        // Only the computer display advances automatically, every three seconds.
        let slideTimer = 0;
        let showcaseVisible = !('IntersectionObserver' in window);
        function stopSlides() { clearTimeout(slideTimer); slideTimer = 0; }
        function scheduleSlides() {
            stopSlides();
            const focused = document.activeElement?.matches(':focus-visible') &&
                (gallery.contains(document.activeElement) || rail?.contains(document.activeElement));
            if (urls.length < 2 || !showcaseVisible || motion.matches || document.hidden || dialog?.open || focused) return;
            slideTimer = setTimeout(() => { select(current + 1, false); scheduleSlides(); }, 3000);
        }
        document.addEventListener('visibilitychange', scheduleSlides);
        motion.addEventListener('change', scheduleSlides);
        dialog?.addEventListener('close', scheduleSlides);
        [gallery, rail].filter(Boolean).forEach(element => {
            element.addEventListener('focusin', scheduleSlides);
            element.addEventListener('focusout', () => queueMicrotask(scheduleSlides));
        });
        if ('IntersectionObserver' in window) new IntersectionObserver(entries => {
            showcaseVisible = entries[0].isIntersecting;
            scheduleSlides();
        }, { threshold: .15 }).observe(gallery);
        scheduleSlides();
        document.addEventListener('keydown', event => {
            const inGallery = event.target.closest?.('.pd-gallery,.pd-thumbnails');
            if (!dialog?.open && !inGallery) return;
            if (event.key === 'ArrowLeft' || event.key === 'ArrowRight') { event.preventDefault(); select(current + (event.key === 'ArrowRight' ? 1 : -1)); }
            if (event.key === 'Home' || event.key === 'End') { event.preventDefault(); select(event.key === 'Home' ? 0 : urls.length - 1); }
        });
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
    else init();
})();