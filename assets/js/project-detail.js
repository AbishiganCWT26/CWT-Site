/* ==========================================================================
   Project Detail — Laptop Mockup Showcase
   Reads image URLs from #mockupStage[data-images] (JSON array)
   ========================================================================== */
(function () {
    'use strict';

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

        var INTERVAL = 4500; // ms per slide

        var screen = document.getElementById('mockupScreen');
        var dotsBox = document.getElementById('mockupDots');
        var bar = document.getElementById('mockupBar');
        if (!screen || !dotsBox || !bar) return;

        bar.style.setProperty('--dur', INTERVAL + 'ms');

        // Build slides
        var slides = SLIDES.map(function (src, i) {
            var d = document.createElement('div');
            d.className = 'slide';
            var img = new Image();
            img.src = src;
            img.alt = 'Project screenshot ' + (i + 1);
            img.loading = (i === 0) ? 'eager' : 'lazy';
            d.appendChild(img);
            screen.insertBefore(d, dotsBox);
            return d;
        });

        // Build dots
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
            timer = setInterval(function () { go(cur + 1); }, INTERVAL);
        }
        function stop() { if (timer) clearInterval(timer); timer = null; }
        function restart() { stop(); start(); }

        // Pause on hover
        screen.addEventListener('mouseenter', function () {
            stop();
            bar.style.animationPlayState = 'paused';
        });
        screen.addEventListener('mouseleave', function () {
            bar.style.animationPlayState = 'running';
            start();
        });

        // Open the lid when scrolled into view, then start slideshow
        if ('IntersectionObserver' in window) {
            var observer = new IntersectionObserver(function (entries, obs) {
                if (entries[0].isIntersecting) {
                    stage.classList.add('open');
                    setTimeout(function () { go(0); start(); }, 900);
                    obs.disconnect();
                }
            }, { threshold: 0.25 });
            observer.observe(stage);
        } else {
            // Fallback for very old browsers
            stage.classList.add('open');
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