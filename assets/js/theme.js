/* Apply the saved theme before page content paints. */
(function () {
    'use strict';
    var root = document.documentElement;
    var key = 'cwt-theme';
    var preference = null;
    var system = window.matchMedia('(prefers-color-scheme: dark)');
    function readPreference() {
        try {
            var value = localStorage.getItem(key);
            return value === 'dark' || value === 'light' ? value : null;
        } catch (_) { return null; }
    }
    function apply(theme) {
        root.dataset.theme = theme;
        var button = document.getElementById('cwtThemeToggle');
        if (button) {
            var dark = theme === 'dark';
            button.setAttribute('aria-pressed', String(dark));
            button.setAttribute('aria-label', dark ? 'Switch to light mode' : 'Switch to dark mode');
            button.title = dark ? 'Switch to light mode' : 'Switch to dark mode';
        }
    }
    preference = readPreference();
    apply(preference || (system.matches ? 'dark' : 'light'));
    root.classList.add('cwt-theme-ready');
    document.addEventListener('DOMContentLoaded', function () {
        apply(root.dataset.theme);
        var button = document.getElementById('cwtThemeToggle');
        if (!button) return;
        button.addEventListener('click', function () {
            preference = root.dataset.theme === 'dark' ? 'light' : 'dark';
            try { localStorage.setItem(key, preference); } catch (_) {}
            apply(preference);
        });
    });
    system.addEventListener('change', function () {
        if (!preference) apply(system.matches ? 'dark' : 'light');
    });
    window.addEventListener('storage', function (event) {
        if (event.key !== key && event.key !== null) return;
        preference = readPreference();
        apply(preference || (system.matches ? 'dark' : 'light'));
    });
})();
