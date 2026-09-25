(function () {
    'use strict';

    const carousel = document.getElementById('prdCarousel');
    const track = document.getElementById('prdGrid');
    const pagination = document.getElementById('prdPagination');
    const pagDots = document.getElementById('prdPagDots');
    const prevBtn = document.getElementById('prdPrevBtn');
    const nextBtn = document.getElementById('prdNextBtn');
    const mobilePagination = document.getElementById('prdMobilePagination');

    if (!carousel || !track) return;

    const cards = Array.from(track.querySelectorAll('.prd-card'));
    if (!cards.length) return;

    /* ------------------------------------------------------------------
       CONFIG
       ------------------------------------------------------------------ */
    const DESKTOP_QUERY = '(min-width: 1024px)';
    const TABLET_MOBILE_QUERY = '(max-width: 1023px)';

    const DESKTOP_ITEMS_PER_PAGE = 6;   // 3 × 2 grid
    const TABLET_MOBILE_ITEMS_PER_PAGE = 5;   // vertical stack

    const mqDesktop = window.matchMedia(DESKTOP_QUERY);
    const mqTabletMobile = window.matchMedia(TABLET_MOBILE_QUERY);
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const TOTAL_DESKTOP_PAGES =
        Math.max(1, Math.ceil(cards.length / DESKTOP_ITEMS_PER_PAGE));

    const TOTAL_TABLET_MOBILE_PAGES =
        Math.max(1, Math.ceil(cards.length / TABLET_MOBILE_ITEMS_PER_PAGE));

    let desktopPage = 0;
    let tabletMobilePage = 0;

    /* =========================================================
       DESKTOP PAGINATION  — 6 cards per page
       ========================================================= */
    const desktopPageButtons = [];

    function buildDesktopPagination() {
        if (!pagDots) return;
        pagDots.innerHTML = '';
        desktopPageButtons.length = 0;

        for (let i = 0; i < TOTAL_DESKTOP_PAGES; i++) {
            const b = document.createElement('button');
            b.type = 'button';
            b.className = 'prd-page-btn';
            b.textContent = i + 1;
            b.setAttribute('aria-label', 'Go to page ' + (i + 1));
            b.addEventListener('click', () => goToDesktopPage(i));
            pagDots.appendChild(b);
            desktopPageButtons.push(b);
        }
        paintDesktopPagination();
    }

    function paintDesktopPagination() {
        desktopPageButtons.forEach((b, i) =>
            b.classList.toggle('is-active', i === desktopPage)
        );
        if (prevBtn) prevBtn.disabled = desktopPage === 0;
        if (nextBtn) nextBtn.disabled = desktopPage === TOTAL_DESKTOP_PAGES - 1;
    }

    function applyDesktopPage() {
        if (!mqDesktop.matches) return;

        const start = desktopPage * DESKTOP_ITEMS_PER_PAGE;
        const end = start + DESKTOP_ITEMS_PER_PAGE;

        cards.forEach((card, i) => {
            const visible = i >= start && i < end;
            card.style.display = visible ? '' : 'none';
            card.style.height = '';
            card.classList.remove('is-hovered');
        });
    }

    function goToDesktopPage(p) {
        const next = Math.max(0, Math.min(p, TOTAL_DESKTOP_PAGES - 1));
        if (next === desktopPage) { paintDesktopPagination(); return; }

        desktopPage = next;
        applyDesktopPage();
        paintDesktopPagination();
    }

    if (prevBtn) prevBtn.addEventListener('click', () => goToDesktopPage(desktopPage - 1));
    if (nextBtn) nextBtn.addEventListener('click', () => goToDesktopPage(desktopPage + 1));

    /* =========================================================
       TABLET + MOBILE PAGINATION  — 5 cards per page
       ========================================================= */
    const tmPageButtons = [];

    function buildTabletMobilePagination() {
        if (!mobilePagination) return;
        mobilePagination.innerHTML = '';
        tmPageButtons.length = 0;

        for (let i = 0; i < TOTAL_TABLET_MOBILE_PAGES; i++) {
            const b = document.createElement('button');
            b.type = 'button';
            b.className = 'prd-page-btn';
            b.textContent = i + 1;
            b.setAttribute(
                'aria-label',
                'Go to page ' + (i + 1) + ' of ' + TOTAL_TABLET_MOBILE_PAGES
            );
            b.addEventListener('click', () => goToTabletMobilePage(i));
            mobilePagination.appendChild(b);
            tmPageButtons.push(b);
        }
        paintTabletMobilePagination();
    }

    function paintTabletMobilePagination() {
        tmPageButtons.forEach((b, i) => {
            const active = i === tabletMobilePage;
            b.classList.toggle('is-active', active);
            b.setAttribute('aria-current', active ? 'true' : 'false');
        });
    }

    function applyTabletMobilePage() {
        if (!mqTabletMobile.matches) return;

        const start = tabletMobilePage * TABLET_MOBILE_ITEMS_PER_PAGE;
        const end = start + TABLET_MOBILE_ITEMS_PER_PAGE;

        cards.forEach((card, i) => {
            const visible = i >= start && i < end;
            card.style.display = visible ? '' : 'none';
            card.style.height = '';
            card.classList.remove('is-hovered');
            card.classList.toggle('is-mobile-visible', visible);
        });
    }

    function goToTabletMobilePage(p) {
        const next = Math.max(0, Math.min(p, TOTAL_TABLET_MOBILE_PAGES - 1));
        if (next === tabletMobilePage) { paintTabletMobilePagination(); return; }

        tabletMobilePage = next;
        applyTabletMobilePage();
        paintTabletMobilePagination();

        /* Scroll the top of the section back into view so the first card
           of the new page is immediately visible. */
        const section = carousel.closest('.prd-section') || carousel;
        const rect = section.getBoundingClientRect();
        const top = rect.top + window.pageYOffset - 90;

        if (window.pageYOffset > Math.max(0, top) - 20) {
            window.scrollTo({
                top: Math.max(0, top),
                behavior: reduceMotion ? 'auto' : 'smooth'
            });
        }
    }

    /* =========================================================
       BREAKPOINT SWITCH
       ========================================================= */
    function handleBreakpoint() {
        if (mqDesktop.matches) {
            /* ---- DESKTOP: 3 × 2 grid, 6 per page ---- */
            cards.forEach(c => {
                c.style.display = '';
                c.style.height = '';
                c.classList.remove('is-hovered', 'is-mobile-visible');
            });
            desktopPage = 0;
            applyDesktopPage();
            paintDesktopPagination();
            track.scrollTo({ left: 0, top: 0 });

        } else {
            /* ---- TABLET + MOBILE: vertical stack, 5 per page ---- */
            cards.forEach(c => {
                c.style.height = '';
                c.classList.remove('is-hovered');
            });
            tabletMobilePage = 0;
            applyTabletMobilePage();
            paintTabletMobilePagination();
            track.scrollTo({ left: 0, top: 0 });
        }
    }

    if (mqDesktop.addEventListener) {
        mqDesktop.addEventListener('change', handleBreakpoint);
    } else if (mqDesktop.addListener) {
        mqDesktop.addListener(handleBreakpoint);           // Safari < 14
    }

    if (mqTabletMobile.addEventListener) {
        mqTabletMobile.addEventListener('change', handleBreakpoint);
    } else if (mqTabletMobile.addListener) {
        mqTabletMobile.addListener(handleBreakpoint);      // Safari < 14
    }

    /* =========================================================
       REVEAL ANIMATION
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
       CARD HEIGHT EXPANSION
       • Desktop:            hover / focus (unchanged)
       • Tablet + Mobile:    tap to toggle reveal + dynamic height
       ========================================================= */
    cards.forEach(card => {
        const revealInner = card.querySelector('.prd-card-reveal-inner');
        if (!revealInner) return;

        function expand() {
            if (mqTabletMobile.matches) return;   // handled by tap below
            card.classList.add('is-hovered');
            const defaultHeight = 300;
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

        /* ---- Tablet + Mobile: tap to toggle reveal ---- */
        card.addEventListener('click', e => {
            if (!mqTabletMobile.matches) return;
            if (e.target.closest('.prd-card-cta')) return;   // let the link work

            const wasOpen = card.classList.contains('is-hovered');

            /* close every card first */
            cards.forEach(c => {
                c.classList.remove('is-hovered');
                c.style.height = '';
            });

            /* open this one (if it wasn't already open) */
            if (!wasOpen) {
                card.classList.add('is-hovered');
                const defaultHeight = 200;
                const requiredHeight = revealInner.scrollHeight + 32;
                if (requiredHeight > defaultHeight) {
                    card.style.height = requiredHeight + 'px';
                }
            }
        });
    });

    /* =========================================================
       BOOT
       ========================================================= */
    buildDesktopPagination();
    buildTabletMobilePagination();
    handleBreakpoint();

    /* Remove the pre-JS guard now that pagination is in control */
    track.classList.remove('prd-mobile-pending');
})();
/* =========================================================
   HERO NEXUS FIELD — added as standalone DOMContentLoaded listener
   ========================================================= */
document.addEventListener('DOMContentLoaded', function () {
    const heroCanvas = document.getElementById('heroCanvas');
    if (heroCanvas && heroCanvas.getContext) {
        (function () {
            const hero = heroCanvas.parentElement;
            const ctx  = heroCanvas.getContext('2d');
            if (!ctx || !hero) return;

            const DENSITY_DIVISOR = 1200, MAX_STARS = 900, MOUSE_RADIUS = 220, MOUSE_FORCE = 2.2;
            const SPRING = 0.012, DAMPING = 0.91, CONSTELLATION_RANGE = 90, CONSTELLATION_ALPHA = 0.16;
            const COLORS = { white:[255,255,255], paleBlue:[200,220,255], blue400:[91,156,255], blue500:[43,123,255], pink:[126,160,248] };
            let w = 0, h = 0, stars = [], running = true;
            const mouse = { x: 0, y: 0, active: false };

            function resize() {
                const dpr = window.devicePixelRatio || 1, rect = hero.getBoundingClientRect();
                w = rect.width; h = rect.height;
                heroCanvas.width = Math.round(w * dpr); heroCanvas.height = Math.round(h * dpr);
                heroCanvas.style.width = w + 'px'; heroCanvas.style.height = h + 'px';
                ctx.setTransform(dpr, 0, 0, dpr, 0, 0); initStars();
            }
            function pickColor() {
                const r = Math.random();
                if (r < 0.55) return COLORS.white; if (r < 0.75) return COLORS.paleBlue;
                if (r < 0.88) return COLORS.blue400; if (r < 0.96) return COLORS.pink;
                return COLORS.blue500;
            }
            function initStars() {
                const count = Math.min(Math.round((w * h) / DENSITY_DIVISOR), MAX_STARS);
                stars = new Array(count).fill(0).map(function () {
                    const x = Math.random() * w, y = Math.random() * h;
                    const depth = Math.random() < 0.55 ? 0 : (Math.random() < 0.7 ? 1 : 2);
                    const baseR = depth === 0 ? Math.random() * 0.5 + 0.25 : depth === 1 ? Math.random() * 0.9 + 0.4 : Math.random() * 1.4 + 0.6;
                    const r = Math.random(), shape = r < 0.78 ? 'dot' : r < 0.94 ? 'sparkle' : 'streak';
                    return { x, y, homeX: x, homeY: y, vx: 0, vy: 0, r: baseR, depth, shape, color: pickColor(),
                        baseAlpha: (depth === 0 ? 0.20 : depth === 1 ? 0.35 : 0.55) + Math.random() * 0.30,
                        phase: Math.random() * Math.PI * 2, speed: Math.random() * 0.018 + 0.005,
                        twinkleAmp: Math.random() * 0.30 + 0.15,
                        driftX: (Math.random() - 0.5) * 0.04 * (depth + 1), driftY: (Math.random() - 0.5) * 0.04 * (depth + 1),
                        angle: Math.random() * Math.PI * 2, proximity: 0 };
                });
            }
            function setPointer(cx, cy) { const rect = hero.getBoundingClientRect(); mouse.x = cx - rect.left; mouse.y = cy - rect.top; mouse.active = true; }
            hero.addEventListener('mousemove', function (e) { setPointer(e.clientX, e.clientY); });
            hero.addEventListener('mouseleave', function () { mouse.active = false; });
            hero.addEventListener('touchstart', function (e) { const t = e.touches[0]; if (t) setPointer(t.clientX, t.clientY); }, { passive: true });
            hero.addEventListener('touchmove',  function (e) { const t = e.touches[0]; if (t) setPointer(t.clientX, t.clientY); }, { passive: true });
            hero.addEventListener('touchend',   function () { mouse.active = false; });

            function drawDot(s, drawR, alpha) { ctx.beginPath(); ctx.fillStyle = 'rgba(' + s.color.join(',') + ',' + alpha + ')'; ctx.arc(s.x, s.y, drawR, 0, Math.PI * 2); ctx.fill(); }
            function drawSparkle(s, drawR, alpha, gb) {
                const arm = drawR * (3.4 + gb * 1.6), thin = Math.max(drawR * 0.55, 0.4), rgb = s.color.join(',');
                ctx.save(); ctx.translate(s.x, s.y); ctx.rotate(s.angle);
                if (gb > 0.05) { ctx.beginPath(); ctx.fillStyle = 'rgba(' + rgb + ',' + (gb * 0.22) + ')'; ctx.arc(0, 0, arm * 1.4, 0, Math.PI * 2); ctx.fill(); }
                const gH = ctx.createLinearGradient(-arm, 0, arm, 0); gH.addColorStop(0, 'rgba(' + rgb + ',0)'); gH.addColorStop(0.5, 'rgba(' + rgb + ',' + alpha + ')'); gH.addColorStop(1, 'rgba(' + rgb + ',0)');
                ctx.fillStyle = gH; ctx.fillRect(-arm, -thin / 2, arm * 2, thin);
                const gV = ctx.createLinearGradient(0, -arm, 0, arm); gV.addColorStop(0, 'rgba(' + rgb + ',0)'); gV.addColorStop(0.5, 'rgba(' + rgb + ',' + alpha + ')'); gV.addColorStop(1, 'rgba(' + rgb + ',0)');
                ctx.fillStyle = gV; ctx.fillRect(-thin / 2, -arm, thin, arm * 2);
                ctx.beginPath(); ctx.fillStyle = 'rgba(255,255,255,' + Math.min(alpha * 1.2, 1) + ')'; ctx.arc(0, 0, drawR * 0.9, 0, Math.PI * 2); ctx.fill(); ctx.restore();
            }
            function drawStreak(s, drawR, alpha) {
                const len = drawR * 5, rgb = s.color.join(','); ctx.save(); ctx.translate(s.x, s.y); ctx.rotate(s.angle);
                const g = ctx.createLinearGradient(-len / 2, 0, len / 2, 0); g.addColorStop(0, 'rgba(' + rgb + ',0)'); g.addColorStop(0.5, 'rgba(' + rgb + ',' + alpha + ')'); g.addColorStop(1, 'rgba(' + rgb + ',0)');
                ctx.fillStyle = g; ctx.fillRect(-len / 2, -drawR * 0.4, len, drawR * 0.8); ctx.restore();
            }
            function drawConstellation(neighbors) {
                const range2 = CONSTELLATION_RANGE * CONSTELLATION_RANGE;
                for (let i = 0; i < neighbors.length; i++) { const a = neighbors[i]; for (let j = i + 1; j < neighbors.length; j++) { const b = neighbors[j], dx = a.x - b.x, dy = a.y - b.y, d2 = dx * dx + dy * dy; if (d2 < range2) { const t = 1 - d2 / range2; ctx.beginPath(); ctx.strokeStyle = 'rgba(91,156,255,' + (t * CONSTELLATION_ALPHA * a.proximity) + ')'; ctx.lineWidth = 0.6; ctx.moveTo(a.x, a.y); ctx.lineTo(b.x, b.y); ctx.stroke(); } } }
            }
            function tick() {
                if (!running) return;
                ctx.clearRect(0, 0, w, h);
                const R = MOUSE_RADIUS, R2 = R * R, neighbors = [];
                if (mouse.active) { const hub = ctx.createRadialGradient(mouse.x, mouse.y, 0, mouse.x, mouse.y, R * 0.9); hub.addColorStop(0, 'rgba(91,156,255,0.10)'); hub.addColorStop(0.4, 'rgba(43,123,255,0.05)'); hub.addColorStop(1, 'rgba(0,27,228,0)'); ctx.fillStyle = hub; ctx.fillRect(mouse.x - R, mouse.y - R, R * 2, R * 2); }
                for (let i = 0; i < stars.length; i++) {
                    const s = stars[i];
                    s.homeX += s.driftX; s.homeY += s.driftY;
                    if (s.homeX < 0) { s.homeX += w; s.x += w; } if (s.homeX > w) { s.homeX -= w; s.x -= w; }
                    if (s.homeY < 0) { s.homeY += h; s.y += h; } if (s.homeY > h) { s.homeY -= h; s.y -= h; }
                    s.vx += (s.homeX - s.x) * SPRING; s.vy += (s.homeY - s.y) * SPRING;
                    let proximity = 0;
                    if (mouse.active) { const dx = mouse.x - s.x, dy = mouse.y - s.y, d2 = dx * dx + dy * dy; if (d2 < R2) { const d = Math.sqrt(d2) || 0.0001; proximity = 1 - d / R; const f = proximity * proximity * MOUSE_FORCE * (0.6 + s.depth * 0.4); s.vx += (dx / d) * f; s.vy += (dy / d) * f; s.vx += (-dy / d) * f * 0.35; s.vy += (dx / d) * f * 0.35; } }
                    s.proximity = proximity; s.vx *= DAMPING; s.vy *= DAMPING; s.x += s.vx; s.y += s.vy;
                    s.phase += s.speed; const tw = Math.sin(s.phase) * s.twinkleAmp;
                    const alpha = Math.max(0.05, Math.min(s.baseAlpha + tw + proximity * 0.6, 1)), drawR = s.r * (1 + proximity * 1.6);
                    if (s.shape === 'dot') drawDot(s, drawR, alpha); else if (s.shape === 'sparkle') drawSparkle(s, drawR, alpha, proximity); else drawStreak(s, drawR, alpha);
                    if (mouse.active && proximity > 0.15) neighbors.push(s);
                }
                if (neighbors.length > 1) drawConstellation(neighbors);
                if (mouse.active) { ctx.beginPath(); ctx.fillStyle = 'rgba(255,255,255,0.9)'; ctx.arc(mouse.x, mouse.y, 2.2, 0, Math.PI * 2); ctx.fill(); ctx.beginPath(); ctx.fillStyle = 'rgba(91,156,255,0.35)'; ctx.arc(mouse.x, mouse.y, 6, 0, Math.PI * 2); ctx.fill(); ctx.beginPath(); ctx.fillStyle = 'rgba(43,123,255,0.15)'; ctx.arc(mouse.x, mouse.y, 14, 0, Math.PI * 2); ctx.fill(); }
                requestAnimationFrame(tick);
            }
            window.addEventListener('resize', resize);
            if ('IntersectionObserver' in window) { new IntersectionObserver(function (entries) { entries.forEach(function (entry) { if (entry.isIntersecting && !running) { running = true; requestAnimationFrame(tick); } else if (!entry.isIntersecting) { running = false; } }); }, { threshold: 0 }).observe(hero); }
            resize(); requestAnimationFrame(tick);
        })();
    }
});
