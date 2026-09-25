document.addEventListener('DOMContentLoaded', function () {

    const navbar = document.querySelector('.cwt-navbar, .navbar');
    if (navbar) {
        const onScroll = function () {
            const scrolled = window.scrollY > 60;
            navbar.classList.toggle('is-scrolled', scrolled);
            navbar.classList.toggle('scrolled', scrolled);
        };
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
    }

    document.querySelectorAll('.marquee-wrap').forEach(function (wrapper) {
        const track = wrapper.querySelector('.marquee-track');
        if (!track) return;

        wrapper.addEventListener('mouseenter', function () {
            track.style.animationPlayState = 'paused';
        });
        wrapper.addEventListener('mouseleave', function () {
            track.style.animationPlayState = 'running';
        });
    });

    const techTabs = document.querySelectorAll('.tech-tab');
    if (techTabs.length) {
        const allTabs = document.querySelectorAll('.tech-tab');
        const allPanels = document.querySelectorAll('.tech-panel');

        techTabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                allTabs.forEach(function (t) {
                    t.classList.remove('active');
                    t.setAttribute('aria-selected', 'false');
                });
                allPanels.forEach(function (p) {
                    p.classList.remove('active');
                });

                tab.classList.add('active');
                tab.setAttribute('aria-selected', 'true');

                const target = document.getElementById(tab.dataset.target);
                if (target) target.classList.add('active');
            });
        });
    }

    const pillars = document.querySelectorAll('.svc-pillar');
    if (pillars.length) {
        pillars.forEach(function (pillar) {
            const head = pillar.querySelector('.svc-pillar-head');
            if (!head) return;

            head.addEventListener('click', function () {
                const isActive = pillar.classList.contains('active');

                pillars.forEach(function (p) {
                    p.classList.remove('active');
                    const h = p.querySelector('.svc-pillar-head');
                    if (h) h.setAttribute('aria-expanded', 'false');
                });

                if (!isActive) {
                    pillar.classList.add('active');
                    head.setAttribute('aria-expanded', 'true');
                }
            });
        });
    }

    const revealEls = document.querySelectorAll('.reveal');
    if (revealEls.length && 'IntersectionObserver' in window) {
        const revealObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });

        revealEls.forEach(function (el) { revealObserver.observe(el); });
    } else {
        revealEls.forEach(function (el) { el.classList.add('visible'); });
    }

    document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
        anchor.addEventListener('click', function (e) {
            const href = anchor.getAttribute('href');
            if (href === '#') return;

            const target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    const cards = Array.prototype.slice.call(document.querySelectorAll('.orbit .card'));

    if (cards.length) {
        const prefersReduced =
            window.matchMedia &&
            window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (prefersReduced || !('IntersectionObserver' in window)) {
            cards.forEach(function (el) { el.classList.add('shown'); });
            return;
        }

        cards.forEach(function (el, i) {
            el.dataset.index = i;

            el.addEventListener('animationend', function (e) {
                if (e.target !== el || e.animationName !== 'cardPop') return;
                el.classList.add('shown');
                el.classList.remove('in-view');
                el.style.animationDelay = '';
            });
        });

        const cardObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;

                const el = entry.target;
                const i = Number(el.dataset.index) || 0;

                el.style.animationDelay = (i * 90) + 'ms';
                el.classList.add('in-view');

                cardObserver.unobserve(el);
            });
        }, {
            threshold: 0.12,
            rootMargin: '0px 0px -40px 0px'
        });

        cards.forEach(function (el) { cardObserver.observe(el); });
    }

});