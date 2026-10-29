/* Case-study motion, manual monitor controls, and an automatic preview rail. */
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
        function select(index) {
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
        }
        thumbs.forEach((thumb, index) => thumb.addEventListener('click', event => { event.preventDefault(); select(index); }));
        gallery.querySelector('[data-gallery-prev]').addEventListener('click', () => select(current - 1));
        gallery.querySelector('[data-gallery-next]').addEventListener('click', () => select(current + 1));
        gallery.querySelector('[data-gallery-controls]').hidden = urls.length < 2;
        if (dialog && typeof dialog.showModal === 'function') {
            openLink.addEventListener('click', event => {
                event.preventDefault();
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
        // Keep small previews moving beside the monitor without changing its screen.
        const rail = document.querySelector('[data-preview-scroll]');
        const computer = gallery.querySelector('.pd-computer');
        if (rail && computer) {
            let frame = 0, previous = 0, position = 0, cycle = 0;
            let visible = !('IntersectionObserver' in window), hovered = false, touching = false;
            const canRun = () => urls.length > 1 && cycle > 0 && visible && !motion.matches &&
                !document.hidden && !hovered && !touching && !(rail.contains(document.activeElement) && document.activeElement.matches(':focus-visible')) && !dialog?.open;
            function stop() { cancelAnimationFrame(frame); frame = 0; previous = 0; }
            function tick(time) {
                frame = 0;
                if (!canRun()) { previous = 0; return; }
                const delta = previous ? Math.min(time - previous, 64) : 0;
                previous = time;
                position = (position + delta * .022) % cycle;
                rail.scrollTop = position;
                frame = requestAnimationFrame(tick);
            }
            function sync() {
                stop();
                position = rail.scrollTop;
                if (canRun()) frame = requestAnimationFrame(tick);
            }
            function measure() {
                stop();
                const height = computer.getBoundingClientRect().height;
                if (!height) return;
                rail.parentElement.style.setProperty('--pd-preview-height', height + 'px');
                rail.querySelectorAll('[data-preview-clone]').forEach(clone => clone.remove());
                rail.scrollTop = 0; position = 0; cycle = 0;
                if (urls.length > 1 && !motion.matches) {
                    function addCopies() {
                        thumbs.forEach(thumb => {
                            const clone = thumb.cloneNode(true);
                            clone.dataset.previewClone = '';
                            clone.setAttribute('aria-hidden', 'true');
                            clone.tabIndex = -1;
                            clone.querySelectorAll('img').forEach(img => img.loading = 'eager');
                            rail.appendChild(clone);
                        });
                    }
                    addCopies();
                    cycle = rail.querySelector('[data-preview-clone]').offsetTop - thumbs[0].offsetTop;
                    let groups = 2;
                    while (cycle > 0 && rail.scrollHeight < height + cycle + 20 && groups < 20) { addCopies(); groups++; }
                }
                sync();
            }
            rail.addEventListener('click', event => {
                const clone = event.target.closest('[data-preview-clone]');
                if (!clone) return;
                event.preventDefault();
                select(Number(clone.dataset.screenIndex));
            });
            rail.addEventListener('pointerenter', event => { if (event.pointerType !== 'touch') { hovered = true; sync(); } });
            rail.addEventListener('pointerleave', () => { hovered = false; sync(); });
            rail.addEventListener('focusin', sync);
            rail.addEventListener('focusout', () => queueMicrotask(sync));
            rail.addEventListener('pointerdown', () => { touching = true; sync(); }, { passive: true });
            window.addEventListener('pointerup', () => { touching = false; sync(); }, { passive: true });
            window.addEventListener('pointercancel', () => { touching = false; sync(); }, { passive: true });
            rail.addEventListener('scroll', () => { if (!frame) position = rail.scrollTop; }, { passive: true });
            document.addEventListener('visibilitychange', sync);
            dialog?.addEventListener('close', sync);
            motion.addEventListener('change', measure);
            if ('ResizeObserver' in window) new ResizeObserver(measure).observe(computer);
            else window.addEventListener('resize', measure, { passive: true });
            if ('IntersectionObserver' in window) new IntersectionObserver(entries => { visible = entries[0].isIntersecting; sync(); }).observe(rail);
            measure();
        }
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