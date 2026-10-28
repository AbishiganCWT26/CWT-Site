/* ==========================================================================
   Project Detail — Flat Screen Mockup Showcase
   Reads image URLs from #mockupStage[data-images] (JSON array)
   ========================================================================== */
(function () {
    'use strict';

    // Design width of the screen mockup (520px screen + breathing room)
    var DESIGN_WIDTH = 620;

    function initMockup() {
        var stage = document.getElementById('mockupStage');
        if (!stage) return;

        var imagesRaw = stage.getAttribute('data-images') || '[]';
        var SLIDES;
        try {
            SLIDES = JSON.parse(imagesRaw);
        } catch (err) {
            console.warn('Mockup: invalid image data.', err);
            return;
        }
        if (!Array.isArray(SLIDES) || SLIDES.length === 0) return;

        var INTERVAL = 2000; // ms per slide

        var fit = document.getElementById('mockupFit');
        var screen = document.getElementById('mockupScreen');
        var dotsBox = document.getElementById('mockupDots');
        var bar = document.getElementById('mockupBar');
        if (!fit || !screen || !dotsBox || !bar) return;

        // ── Scale the fixed-size screen to the available width ──────────────
        function fitLaptop() {
            var s = Math.min(1, fit.clientWidth / DESIGN_WIDTH);
            fit.style.setProperty('--s', Math.max(0.3, s).toFixed(3));
        }
        fitLaptop();
        window.addEventListener('resize', fitLaptop);

        bar.style.setProperty('--dur', INTERVAL + 'ms');

        // ── Build slides ────────────────────────────────────────────────────
        var slides = SLIDES.map(function (src, i) {
            var d = document.createElement('div');
            d.className = 'lp-slide';
            var img = new Image();
            img.src = src;
            img.alt = 'Project screenshot ' + (i + 1);
            img.loading = (i === 0) ? 'eager' : 'lazy';
            d.appendChild(img);
            screen.insertBefore(d, dotsBox);
            return d;
        });

        // ── Build dots ──────────────────────────────────────────────────────
        var dots = SLIDES.map(function (_, i) {
            var b = document.createElement('button');
            b.type = 'button';
            b.setAttribute('aria-label', 'Show slide ' + (i + 1));
            b.addEventListener('click', function () { go(i); restart(); });
            dotsBox.appendChild(b);
            return b;
        });

        var cur = 0;
        var timer = null;

        function go(n) {
            cur = (n + slides.length) % slides.length;
            slides.forEach(function (s, i) { s.classList.toggle('active', i === cur); });
            dots.forEach(function (d, i) { d.classList.toggle('active', i === cur); });
            bar.classList.remove('run');
            void bar.offsetWidth; // force reflow
            bar.classList.add('run');
        }

        function start() {
            stop();
            if (slides.length < 2) return;
            timer = setInterval(function () { go(cur + 1); }, INTERVAL);
        }
        function stop() { if (timer) clearInterval(timer); timer = null; }
        function restart() { stop(); start(); }

        // Hover does NOT pause: the slideshow keeps changing every INTERVAL ms.

        // ── Screen is visible by default: show the first slide right away ───
        slides[0].classList.add('active');
        dots[0].classList.add('active');

        // ── Start the slideshow (autoplay + progress bar) when scrolled in ──
        if ('IntersectionObserver' in window) {
            var observer = new IntersectionObserver(function (entries, obs) {
                if (entries[0].isIntersecting) {
                    go(0);
                    start();
                    obs.disconnect();
                }
            }, { threshold: 0.25 });
            observer.observe(stage);
        } else {
            // Fallback for very old browsers
            go(0);
            start();
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMockup);
    } else {
        initMockup();
    }
})();