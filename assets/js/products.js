(function () {
    'use strict';

    const carousel = document.getElementById('prdCarousel');
    const track = document.getElementById('prdGrid');
    const dotsWrap = document.getElementById('prdDots');
    const pagination = document.getElementById('prdPagination');
    const pagDots = document.getElementById('prdPagDots');
    const prevBtn = document.getElementById('prdPrevBtn');
    const nextBtn = document.getElementById('prdNextBtn');

    if (!carousel || !track) return;

    const cards = Array.from(track.querySelectorAll('.prd-card'));
    if (!cards.length) return;

    const DELAY = 5000;                // autoplay interval (mobile / tablet)
    const DESKTOP_QUERY = '(min-width: 1024px)';
    const ITEMS_PER_PAGE = 10;                  // ← cards per desktop page

    const mqDesktop = window.matchMedia(DESKTOP_QUERY);
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const TOTAL_PAGES = Math.max(1, Math.ceil(cards.length / ITEMS_PER_PAGE));

    let index = 0;   // mobile carousel index
    let currentPage = 0;   // desktop page index
    let timer = null;
    let paused = false;
    let lockScrollSync = false;
    let lockTimer = null;

    /* =========================================================
       DOTS  (mobile / tablet)
       ========================================================= */
    const dots = cards.map((card, i) => {
        const b = document.createElement('button');
        b.type = 'button';
        b.className = 'prd-dot';
        const title = card.querySelector('.prd-card-title');
        b.setAttribute('aria-label', 'Go to ' + (title ? title.textContent.trim() : 'card ' + (i + 1)));
        b.addEventListener('click', () => { goTo(i); restart(); });
        dotsWrap.appendChild(b);
        return b;
    });

    function paintDots() {
        dots.forEach((d, i) => d.classList.toggle('is-active', i === index));
    }

    /* =========================================================
       PAGINATION  (desktop)
       ========================================================= */
    const pageButtons = [];

    function buildPagination() {
        if (!pagDots) return;
        pagDots.innerHTML = '';
        pageButtons.length = 0;

        for (let i = 0; i < TOTAL_PAGES; i++) {
            const b = document.createElement('button');
            b.type = 'button';
            b.className = 'prd-page-btn';
            b.textContent = i + 1;
            b.setAttribute('aria-label', 'Go to page ' + (i + 1));
            b.addEventListener('click', () => goToPage(i));
            pagDots.appendChild(b);
            pageButtons.push(b);
        }
        paintPagination();
    }

    function paintPagination() {
        pageButtons.forEach((b, i) => b.classList.toggle('is-active', i === currentPage));
        if (prevBtn) prevBtn.disabled = currentPage === 0;
        if (nextBtn) nextBtn.disabled = currentPage === TOTAL_PAGES - 1;
    }

    function applyPage() {
        if (!mqDesktop.matches) return;
        cards.forEach((card, i) => {
            const page = Math.floor(i / ITEMS_PER_PAGE);
            card.style.display = (page === currentPage) ? '' : 'none';
        });
    }

    function goToPage(p) {
        const next = Math.max(0, Math.min(p, TOTAL_PAGES - 1));
        if (next === currentPage) { paintPagination(); return; }
        currentPage = next;
        applyPage();
        paintPagination();
    }

    if (prevBtn) prevBtn.addEventListener('click', () => goToPage(currentPage - 1));
    if (nextBtn) nextBtn.addEventListener('click', () => goToPage(currentPage + 1));

    /* =========================================================
       MOBILE CAROUSEL SCROLL
       ========================================================= */
    function scrollToCard(i) {
        const card = cards[i];
        if (!card || mqDesktop.matches) return;

        const target = card.offsetLeft - (track.clientWidth - card.offsetWidth) / 2;
        const max = track.scrollWidth - track.clientWidth;
        const left = Math.max(0, Math.min(target, max));

        lockScrollSync = true;
        clearTimeout(lockTimer);
        lockTimer = setTimeout(() => { lockScrollSync = false; }, 700);

        track.scrollTo({ left, behavior: reduceMotion ? 'auto' : 'smooth' });
    }

    function goTo(i) {
        if (mqDesktop.matches) return;
        index = (i % cards.length + cards.length) % cards.length;   // wrap both ways
        paintDots();
        scrollToCard(index);
    }

    /* =========================================================
       AUTOPLAY  (mobile / tablet only)
       ========================================================= */
    function start() {
        if (reduceMotion || mqDesktop.matches) return;
        stop();
        timer = setInterval(() => {
            if (paused || document.hidden) return;
            goTo(index + 1);
        }, DELAY);
    }
    function stop() { clearInterval(timer); timer = null; }
    function restart() { if (!mqDesktop.matches) start(); }

    /* pause while hovering / focused inside */
    carousel.addEventListener('mouseenter', () => { paused = true; });
    carousel.addEventListener('mouseleave', () => { paused = false; });
    carousel.addEventListener('focusin', () => { paused = true; });
    carousel.addEventListener('focusout', e => {
        if (!carousel.contains(e.relatedTarget)) paused = false;
    });

    /* restart the 5s clock after any manual scrolling */
    ['wheel', 'touchstart', 'pointerdown'].forEach(evt =>
        track.addEventListener(evt, restart, { passive: true })
    );

    /* keep the index in sync when the user scrolls by hand */
    let syncTimer;
    track.addEventListener('scroll', () => {
        if (mqDesktop.matches || lockScrollSync) return;
        clearTimeout(syncTimer);
        syncTimer = setTimeout(() => {
            const center = track.scrollLeft + track.clientWidth / 2;
            let best = index, bestDist = Infinity;
            cards.forEach((c, i) => {
                const d = Math.abs(c.offsetLeft + c.offsetWidth / 2 - center);
                if (d < bestDist) { bestDist = d; best = i; }
            });
            if (best !== index) { index = best; paintDots(); }
        }, 150);
    });

    /* keep the current card centred on resize (mobile) */
    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            if (!mqDesktop.matches) scrollToCard(index);
        }, 150);
    });

    /* arrow keys move one card at a time (mobile) */
    track.addEventListener('keydown', e => {
        if (mqDesktop.matches) return;
        if (e.key === 'ArrowRight') { e.preventDefault(); goTo(index + 1); restart(); }
        if (e.key === 'ArrowLeft') { e.preventDefault(); goTo(index - 1); restart(); }
    });

    /* =========================================================
       BREAKPOINT SWITCH
       ========================================================= */
    function handleBreakpoint() {
        if (mqDesktop.matches) {
            /* ---- DESKTOP: grid + pagination ---- */
            stop();
            cards.forEach(c => { c.style.display = ''; });   // reset first
            applyPage();
            paintPagination();
            track.scrollTo({ left: 0 });
        } else {
            /* ---- MOBILE / TABLET: scroller + dots ---- */
            cards.forEach(c => { c.style.display = ''; });
            currentPage = 0;
            paintDots();
            start();
        }
    }

    if (mqDesktop.addEventListener) {
        mqDesktop.addEventListener('change', handleBreakpoint);
    } else if (mqDesktop.addListener) {
        mqDesktop.addListener(handleBreakpoint);   // Safari < 14
    }

    /* =========================================================
       REVEAL ANIMATION  (section headers / CTA)
       ========================================================= */
    if ('IntersectionObserver' in window) {
        const io = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });

        document.querySelectorAll('.reveal').forEach(el => io.observe(el));
    } else {
        document.querySelectorAll('.reveal').forEach(el => el.classList.add('is-visible'));
    }

    /* =========================================================
       DYNAMIC CARD HEIGHT EXPANSION ON HOVER (SHOW FULL PARA)
       ========================================================= */
    cards.forEach(card => {
        const revealInner = card.querySelector('.prd-card-reveal-inner');
        if (!revealInner) return;

        function expand() {
            card.classList.add('is-hovered');
            const defaultHeight = mqDesktop.matches ? 200 : 190;
            const requiredHeight = revealInner.scrollHeight + 32;
            if (requiredHeight > defaultHeight) {
                card.style.height = requiredHeight + 'px';
            }
        }

        function collapse() {
            card.classList.remove('is-hovered');
            card.style.height = '';
        }

        card.addEventListener('mouseenter', expand);
        card.addEventListener('mouseleave', collapse);
        card.addEventListener('focusin', expand);
        card.addEventListener('focusout', e => {
            if (!card.contains(e.relatedTarget)) collapse();
        });
    });

    /* =========================================================
       BOOT
       ========================================================= */
    paintDots();
    buildPagination();
    handleBreakpoint();

    requestAnimationFrame(() => {
        if (!mqDesktop.matches) goTo(0);
    });
})();