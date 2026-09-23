document.addEventListener('DOMContentLoaded', function () {

    const navbar = document.querySelector('.cwt-navbar, .navbar');
    if (navbar) {
        const onScroll = function () {
            navbar.classList.toggle('is-scrolled', window.scrollY > 60);
            navbar.classList.toggle('scrolled', window.scrollY > 60);
        };
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
    }

    const revealObserver = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                revealObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });

    document.querySelectorAll('.reveal').forEach(function (el) {
        revealObserver.observe(el);
    });

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

    const cards = document.querySelectorAll('.ins-card');
    if (!cards.length) return;

    const supportsHover = window.matchMedia &&
        window.matchMedia('(hover: hover) and (pointer: fine)').matches;

    if (!supportsHover) return;

    cards.forEach(function (card) {
        let rafId = 0;

        card.addEventListener('mousemove', function (e) {
            if (rafId) return;

            rafId = requestAnimationFrame(function () {
                const rect = card.getBoundingClientRect();
                const x = ((e.clientX - rect.left) / rect.width) * 100;
                const y = ((e.clientY - rect.top) / rect.height) * 100;

                card.style.setProperty('--mx', x.toFixed(2) + '%');
                card.style.setProperty('--my', y.toFixed(2) + '%');

                rafId = 0;
            });
        });

        card.addEventListener('mouseleave', function () {
            if (rafId) {
                cancelAnimationFrame(rafId);
                rafId = 0;
            }
            card.style.setProperty('--mx', '50%');
            card.style.setProperty('--my', '0%');
        });
    });

});