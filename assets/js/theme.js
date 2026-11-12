/* Apply the saved theme before page content paints, with circular-reveal transition. */
(function () {
    'use strict';
    var root = document.documentElement;
    var key = 'cwt-theme';
    var preference = null;
    var system = window.matchMedia('(prefers-color-scheme: dark)');
    var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

    /* Theme-color meta tag colours. */
    var THEME_COLORS = { light: '#eaf2ff', dark: '#0b1425' };

    function readPreference() {
        try {
            var value = localStorage.getItem(key);
            return value === 'dark' || value === 'light' ? value : null;
        } catch (_) { return null; }
    }

    function syncMetaThemeColor(theme) {
        var meta = document.querySelector('meta[name="theme-color"]');
        if (!meta) {
            meta = document.createElement('meta');
            meta.name = 'theme-color';
            document.head.appendChild(meta);
        }
        meta.content = THEME_COLORS[theme] || THEME_COLORS.light;
    }

    function applyThemeAttr(theme) {
        root.dataset.theme = theme;
        syncMetaThemeColor(theme);
        var button = document.getElementById('cwtThemeToggle');
        if (button) {
            var dark = theme === 'dark';
            button.setAttribute('aria-pressed', String(dark));
            button.setAttribute('aria-label', dark ? 'Switch to light mode' : 'Switch to dark mode');
            button.title = dark ? 'Switch to light mode' : 'Switch to dark mode';
        }
    }

    /* Circular-reveal transition via View Transitions API. */
    function switchTheme(nextTheme, originEl) {
        /* If the API is unavailable or the user prefers reduced motion, switch instantly. */
        if (!document.startViewTransition || reducedMotion.matches) {
            applyThemeAttr(nextTheme);
            return;
        }

        /* Calculate ripple origin from the toggle button's centre. */
        var x = window.innerWidth / 2;
        var y = window.innerHeight / 2;
        if (originEl) {
            var rect = originEl.getBoundingClientRect();
            x = Math.round(rect.left + rect.width / 2);
            y = Math.round(rect.top  + rect.height / 2);
        }
        var radius = Math.hypot(
            Math.max(x, window.innerWidth  - x),
            Math.max(y, window.innerHeight - y)
        );
        var clipStart = 'circle(0px at ' + x + 'px ' + y + 'px)';
        var clipEnd   = 'circle(' + (radius + 2) + 'px at ' + x + 'px ' + y + 'px)';

        var transition = document.startViewTransition(function () {
            applyThemeAttr(nextTheme);
        });

        transition.ready.then(function () {
            var anim = document.documentElement.animate(
                { clipPath: [clipStart, clipEnd] },
                {
                    duration: 700,
                    easing: 'cubic-bezier(.65,0,.35,1)',
                    pseudoElement: '::view-transition-new(root)',
                }
            );
            /* Suppress the default cross-fade on the outgoing snapshot. */
            document.documentElement.animate(
                { opacity: [1, 1] },
                {
                    duration: 700,
                    pseudoElement: '::view-transition-old(root)',
                }
            );
            return anim.finished;
        }).catch(function () { /* transition was skipped — no-op */ });
    }

    /* ---- Boot: apply saved / system preference before first paint ---- */
    preference = readPreference();
    applyThemeAttr(preference || (system.matches ? 'dark' : 'light'));
    root.classList.add('cwt-theme-ready');

    /* ---- Wire up button after DOM is ready ---- */
    document.addEventListener('DOMContentLoaded', function () {
        applyThemeAttr(root.dataset.theme); /* sync button state */
        syncMetaThemeColor(root.dataset.theme);

        var button = document.getElementById('cwtThemeToggle');
        if (!button) return;

        button.addEventListener('click', function (e) {
            preference = root.dataset.theme === 'dark' ? 'light' : 'dark';
            try { localStorage.setItem(key, preference); } catch (_) {}
            switchTheme(preference, e.currentTarget);
        });
    });

    /* ---- Follow OS changes only when no explicit user choice is saved ---- */
    system.addEventListener('change', function () {
        if (!preference) applyThemeAttr(system.matches ? 'dark' : 'light');
    });

    /* ---- Sync across browser tabs ---- */
    window.addEventListener('storage', function (event) {
        if (event.key !== key && event.key !== null) return;
        preference = readPreference();
        applyThemeAttr(preference || (system.matches ? 'dark' : 'light'));
    });
})();
