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

    const team = document.getElementById('crTeam');
    if (!team) return;

    const photos = team.querySelectorAll('.cr-team-photo');
    const items = team.querySelectorAll('.cr-team-item');

    // Desktop-only: hovering a photo/list row pairs it with its match (see @media hover:hover)
    const isDesktopHoverLayout = function () {
        return window.innerWidth >= 1200;
    };

    const clearActive = function () {
        team.classList.remove('is-hovering');
        photos.forEach(function (p) { p.classList.remove('is-active'); });
        items.forEach(function (i) { i.classList.remove('is-active'); });
    };

    const setActive = function (id) {
        team.classList.add('is-hovering');

        photos.forEach(function (p) {
            p.classList.toggle('is-active', p.dataset.id === id);
        });
        items.forEach(function (i) {
            i.classList.toggle('is-active', i.dataset.id === id);
        });
    };

    photos.forEach(function (photo) {
        photo.addEventListener('mouseenter', function () {
            if (isDesktopHoverLayout()) setActive(photo.dataset.id);
        });
        photo.addEventListener('mouseleave', function () {
            if (isDesktopHoverLayout()) clearActive();
        });
    });

    // Mobile/tablet-only: each card expands in place to reveal extra details.
    // A single click handler per card (not per sub-element) keeps a tap from
    // toggling twice when it lands on the chevron button itself.
    items.forEach(function (item) {
        const toggle = item.querySelector('.cr-team-item-toggle');
        if (!toggle) return;

        item.addEventListener('click', function (e) {
            if (isDesktopHoverLayout()) {
                setActive(item.dataset.id);
                return;
            }

            // Let LinkedIn links behave normally instead of toggling the card.
            if (e.target.closest('a')) return;

            const expanded = item.classList.toggle('is-expanded');
            toggle.setAttribute('aria-expanded', expanded ? 'true' : 'false');
        });

        item.addEventListener('mouseleave', function () {
            if (isDesktopHoverLayout()) clearActive();
        });
    });

});