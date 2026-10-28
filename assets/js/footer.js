(function () {
    'use strict';

    /* ---------- Back to top ---------- */
    var backTop = document.getElementById('cwtBackTop');

    function toggleBackTop() {
        if (!backTop) return;
        if (window.scrollY > 320) {
            backTop.classList.add('is-visible');
        } else {
            backTop.classList.remove('is-visible');
        }
    }

    toggleBackTop();
    window.addEventListener('scroll', toggleBackTop, { passive: true });

    if (backTop) {
        backTop.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    var footer = document.querySelector('.cwt-footer');
    if (!footer) return;

    /* ---------- Hover perf hint ---------- */
    var cards = footer.querySelectorAll('.cwt-contact, .cwt-social, .cwt-links a');

    cards.forEach(function (el) {
        el.addEventListener('mouseenter', function () {
            el.style.willChange = 'transform';
        });
        el.addEventListener('mouseleave', function () {
            el.style.willChange = '';
        });
    });

    /* ---------- Particles background (theme-aware) ---------- */
    var particlesEl = document.getElementById('cwt-particles');
    if (!particlesEl || typeof window.particlesJS !== 'function') return;

    var reduceMotion = window.matchMedia &&
        window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var isMobile = window.innerWidth < 768;

    /* Read a themed CSS custom property off the footer element */
    function token(name, fallback) {
        var v = window.getComputedStyle(footer).getPropertyValue(name);
        v = (v || '').trim();
        return v || fallback;
    }

    function num(name, fallback) {
        var n = parseFloat(token(name, ''));
        return isNaN(n) ? fallback : n;
    }

    function destroyParticles() {
        if (Array.isArray(window.pJSDom) && window.pJSDom.length) {
            window.pJSDom.forEach(function (item) {
                try {
                    if (item && item.pJS && item.pJS.fn &&
                        item.pJS.fn.vendors && item.pJS.fn.vendors.destroypJS) {
                        item.pJS.fn.vendors.destroypJS();
                    }
                } catch (e) { /* noop */ }
            });
        }
        window.pJSDom = [];
        particlesEl.innerHTML = '';
    }

    function buildConfig() {
        return {
            particles: {
                number: {
                    value: isMobile ? 40 : 100,
                    density: { enable: true, value_area: 500 }
                },
                color: { value: token('--cwt-particles-color', '#4169E1') },
                shape: {
                    type: 'circle',
                    stroke: { width: 0, color: token('--cwt-particles-color', '#000080') }
                },
                opacity: {
                    value: num('--cwt-particles-opacity', 0.5),
                    random: false,
                    anim: { enable: false }
                },
                size: { value: 5, random: true, anim: { enable: false } },
                line_linked: {
                    enable: true,
                    distance: 150,
                    color: token('--cwt-particles-line', '#4682B4'),
                    opacity: num('--cwt-particles-line-opacity', 0.4),
                    width: 1
                },
                move: {
                    enable: !reduceMotion,
                    speed: 2,
                    direction: 'none',
                    random: false,
                    straight: false,
                    out_mode: 'out'
                }
            },
            /* Footer content sits above the canvas, so mouse events can't reach it. */
            interactivity: {
                detect_on: 'canvas',
                events: {
                    onhover: { enable: false, mode: 'repulse' },
                    onclick: { enable: false, mode: 'push' },
                    resize: true
                }
            },
            retina_detect: true
        };
    }

    function initParticles() {
        destroyParticles();
        window.particlesJS('cwt-particles', buildConfig());
    }

    initParticles();

    /* Re-colour the canvas when the OS theme flips
       (only if the footer theme isn't pinned by data-theme). */
    if (!footer.hasAttribute('data-theme') && window.matchMedia) {
        var mq = window.matchMedia('(prefers-color-scheme: dark)');
        var onThemeChange = function () { initParticles(); };

        if (typeof mq.addEventListener === 'function') {
            mq.addEventListener('change', onThemeChange);
        } else if (typeof mq.addListener === 'function') {
            mq.addListener(onThemeChange);
        }
    }
})();