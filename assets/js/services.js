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

    const heroCanvas = document.getElementById('heroCanvas');
    if (heroCanvas && heroCanvas.getContext) {
        (function () {
            const hero = heroCanvas.parentElement;
            const ctx = heroCanvas.getContext('2d');
            if (!ctx || !hero) return;

            const DENSITY_DIVISOR = 1200, MAX_STARS = 900, MOUSE_RADIUS = 220, MOUSE_FORCE = 2.2;
            const SPRING = 0.012, DAMPING = 0.91, CONSTELLATION_RANGE = 90, CONSTELLATION_ALPHA = 0.16;
            const COLORS = { white: [255, 255, 255], paleBlue: [200, 220, 255], blue400: [91, 156, 255], blue500: [43, 123, 255], pink: [126, 160, 248] };
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
                    return {
                        x, y, homeX: x, homeY: y, vx: 0, vy: 0, r: baseR, depth, shape, color: pickColor(),
                        baseAlpha: (depth === 0 ? 0.20 : depth === 1 ? 0.35 : 0.55) + Math.random() * 0.30,
                        phase: Math.random() * Math.PI * 2, speed: Math.random() * 0.018 + 0.005,
                        twinkleAmp: Math.random() * 0.30 + 0.15,
                        driftX: (Math.random() - 0.5) * 0.04 * (depth + 1), driftY: (Math.random() - 0.5) * 0.04 * (depth + 1),
                        angle: Math.random() * Math.PI * 2, proximity: 0
                    };
                });
            }
            function setPointer(cx, cy) { const rect = hero.getBoundingClientRect(); mouse.x = cx - rect.left; mouse.y = cy - rect.top; mouse.active = true; }
            hero.addEventListener('mousemove', function (e) { setPointer(e.clientX, e.clientY); });
            hero.addEventListener('mouseleave', function () { mouse.active = false; });
            hero.addEventListener('touchstart', function (e) { const t = e.touches[0]; if (t) setPointer(t.clientX, t.clientY); }, { passive: true });
            hero.addEventListener('touchmove', function (e) { const t = e.touches[0]; if (t) setPointer(t.clientX, t.clientY); }, { passive: true });
            hero.addEventListener('touchend', function () { mouse.active = false; });

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

    const orbitCards = Array.prototype.slice.call(document.querySelectorAll('.orbit .card'));

    if (orbitCards.length) {
        (function initOrbitCardReveal() {
            const prefersReduced =
                window.matchMedia &&
                window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            if (prefersReduced || !('IntersectionObserver' in window)) {
                orbitCards.forEach(function (el) { el.classList.add('shown'); });
                return;
            }

            orbitCards.forEach(function (el, i) {
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

            orbitCards.forEach(function (el) { cardObserver.observe(el); });
        })();
    }

    const SECTIONS = window.CWT_SECTIONS || [];

    (function initWhatWeOffer() {
        const tabsEl = document.getElementById('tabs');
        const cardsEl = document.getElementById('cards');
        const panelEl = document.getElementById('panel');
        const panelTitle = document.getElementById('panelTitle');
        const panelMeta = document.getElementById('panelMeta');
        const panelIconUse = document.getElementById('panelIconUse');

        if (!tabsEl || !cardsEl || !panelEl) return;

        let current = -1;

        function pad2(n) {
            return n < 10 ? '0' + n : String(n);
        }

        function cardHTML(card, index) {
            return '' +
                '<article class="card fx-card" style="--i:' + index + '">' +
                '<div class="fx-card-inner">' +
                '<div class="fx-front">' +
                '<span class="card__glow" aria-hidden="true"></span>' +
                '<div class="card__top">' +
                '<span class="card__icon" aria-hidden="true">' +
                '<svg class="ico" viewBox="0 0 24 24" aria-hidden="true" focusable="false">' +
                '<use href="#i-' + card.i + '"></use>' +
                '</svg>' +
                '</span>' +
                '<span class="card__num">' + pad2(index + 1) + '</span>' +
                '</div>' +
                '<h3 class="card__title">' + card.t + '</h3>' +
                '</div>' +
                '<div class="fx-back">' +
                '<div class="fx-back-content">' +
                '<span class="card__glow" aria-hidden="true"></span>' +
                '<h3 class="card__title fx-back-title">' + card.t + '</h3>' +
                '<p class="card__text">' + card.d + '</p>' +
                '</div>' +
                '<span class="card__line" aria-hidden="true"></span>' +
                '</div>' +
                '</div>' +
                '</article>';
        }

        function buildTabs() {
            const frag = document.createDocumentFragment();
            SECTIONS.forEach(function (section, index) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'tab';
                btn.id = 'tab-' + section.id;
                btn.setAttribute('role', 'tab');
                btn.setAttribute('aria-controls', 'panel');
                btn.setAttribute('aria-selected', index === 0 ? 'true' : 'false');
                btn.tabIndex = index === 0 ? 0 : -1;
                btn.dataset.index = index;
                btn.innerHTML =
                    '<svg class="ico tab__ico" viewBox="0 0 24 24" aria-hidden="true" focusable="false">' +
                    '<use href="#i-' + section.icon + '"></use>' +
                    '</svg>' +
                    '<span class="tab__label">' + section.label + '</span>';
                btn.addEventListener('click', function () { selectSection(index, false); });
                frag.appendChild(btn);
            });
            tabsEl.appendChild(frag);
        }

        function renderSection(index) {
            const section = SECTIONS[index];
            if (!section) return;

            Array.prototype.forEach.call(tabsEl.children, function (btn, i) {
                const isOn = i === index;
                btn.setAttribute('aria-selected', isOn ? 'true' : 'false');
                btn.tabIndex = isOn ? 0 : -1;
            });

            panelEl.style.setProperty('--accent', section.accent);
            panelEl.style.setProperty('--accent-2', section.accent2);
            panelEl.style.setProperty('--accent-soft', section.soft);

            cardsEl.style.setProperty('--accent', section.accent);
            cardsEl.style.setProperty('--accent-2', section.accent2);
            cardsEl.style.setProperty('--accent-soft', section.soft);

            if (panelIconUse) panelIconUse.setAttribute('href', '#i-' + section.icon);
            if (panelTitle) panelTitle.textContent = section.label;
            if (panelMeta) panelMeta.textContent =
                section.cards.length + (section.cards.length === 1 ? ' capability' : ' capabilities');

            panelEl.setAttribute('aria-labelledby', 'tab-' + section.id);

            let html = '';
            for (let i = 0; i < section.cards.length; i++) {
                html += cardHTML(section.cards[i], i);
            }
            cardsEl.innerHTML = html;
        }

        function buildMobileDropdown() {
            if (!SECTIONS.length) return;
            if (document.getElementById('mobileTabDropdown') || !tabsEl) return;

            const wrap = document.createElement('div');
            wrap.className = 'mobile-tab-dropdown';
            wrap.id = 'mobileTabDropdown';

            const trigger = document.createElement('button');
            trigger.type = 'button';
            trigger.className = 'tab-dropdown-trigger';
            trigger.id = 'tabDropdownTrigger';
            trigger.setAttribute('aria-expanded', 'false');
            trigger.innerHTML =
                '<div class="tab-dropdown-left">' +
                '  <span class="tab-dropdown-icon" id="dropdownActiveIcon">' +
                '    <svg class="ico" viewBox="0 0 24 24"><use id="dropdownIconUse" href="#i-' + SECTIONS[0].icon + '"></use></svg>' +
                '  </span>' +
                '  <div class="tab-dropdown-info">' +
                '    <span class="tab-dropdown-sub" id="dropdownSubText">CATEGORY (1 OF ' + SECTIONS.length + ')</span>' +
                '    <h3 class="tab-dropdown-title" id="dropdownActiveTitle">' + SECTIONS[0].label + '</h3>' +
                '  </div>' +
                '</div>' +
                '<span class="tab-dropdown-chevron">' +
                '  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>' +
                '</span>';

            const menu = document.createElement('div');
            menu.className = 'tab-dropdown-menu';
            menu.id = 'tabDropdownMenu';

            SECTIONS.forEach(function (sec, idx) {
                const item = document.createElement('button');
                item.type = 'button';
                item.className = 'tab-dropdown-item' + (idx === 0 ? ' is-active' : '');
                item.dataset.index = idx;
                item.innerHTML =
                    '<svg class="ico item__ico" viewBox="0 0 24 24"><use href="#i-' + sec.icon + '"></use></svg>' +
                    '<span class="item__title">' + sec.label + '</span>' +
                    '<svg class="item__check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>';
                item.addEventListener('click', function () {
                    selectSection(idx, false);
                    closeMobileDropdown();
                });
                menu.appendChild(item);
            });

            trigger.addEventListener('click', function (e) {
                e.stopPropagation();
                toggleMobileDropdown();
            });

            document.addEventListener('click', function (e) {
                if (!wrap.contains(e.target)) closeMobileDropdown();
            });

            wrap.appendChild(trigger);
            wrap.appendChild(menu);
            tabsEl.parentNode.insertBefore(wrap, tabsEl);
        }

        function toggleMobileDropdown() {
            const wrap = document.getElementById('mobileTabDropdown');
            if (!wrap) return;
            if (wrap.classList.contains('is-open')) closeMobileDropdown();
            else openMobileDropdown();
        }

        function openMobileDropdown() {
            const wrap = document.getElementById('mobileTabDropdown');
            const trigger = document.getElementById('tabDropdownTrigger');
            if (!wrap || !trigger) return;
            wrap.classList.add('is-open');
            trigger.setAttribute('aria-expanded', 'true');
        }

        function closeMobileDropdown() {
            const wrap = document.getElementById('mobileTabDropdown');
            const trigger = document.getElementById('tabDropdownTrigger');
            if (!wrap || !trigger) return;
            wrap.classList.remove('is-open');
            trigger.setAttribute('aria-expanded', 'false');
        }

        function updateMobileDropdown(index) {
            const sec = SECTIONS[index];
            if (!sec) return;
            const iconUse = document.getElementById('dropdownIconUse');
            const activeTitle = document.getElementById('dropdownActiveTitle');
            const subText = document.getElementById('dropdownSubText');
            const menu = document.getElementById('tabDropdownMenu');
            if (iconUse) iconUse.setAttribute('href', '#i-' + sec.icon);
            if (activeTitle) activeTitle.textContent = sec.label;
            if (subText) subText.textContent = 'CATEGORY (' + (index + 1) + ' OF ' + SECTIONS.length + ')';
            if (menu) {
                Array.from(menu.children).forEach(function (item, i) {
                    item.classList.toggle('is-active', i === index);
                });
            }
        }

        function selectSection(index, focusTab) {
            if (index === current) return;
            current = index;
            renderSection(index);
            updateMobileDropdown(index);
            if (window.innerWidth < 768 && tabsEl) {
                const activeTab = tabsEl.children[index];
                if (activeTab) activeTab.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            }
            if (focusTab) {
                const btn = tabsEl.children[index];
                if (btn) btn.focus();
            }
        }

        tabsEl.addEventListener('keydown', function (event) {
            const key = event.key;
            const handled = ['ArrowRight', 'ArrowLeft', 'ArrowUp', 'ArrowDown', 'Home', 'End'].indexOf(key) !== -1;
            if (!handled) return;
            event.preventDefault();
            let next = current;
            const last = SECTIONS.length - 1;
            if (key === 'ArrowRight' || key === 'ArrowDown') next = current >= last ? 0 : current + 1;
            else if (key === 'ArrowLeft' || key === 'ArrowUp') next = current <= 0 ? last : current - 1;
            else if (key === 'Home') next = 0;
            else if (key === 'End') next = last;
            selectSection(next, true);
        });

        buildTabs();
        buildMobileDropdown();
        selectSection(0, false);

        document.addEventListener('click', function (e) {
            const card = e.target.closest('.fx-card');
            if (card && window.innerWidth >= 768) {
                card.classList.toggle('is-flipped');
            }
        });
    })();

    (function initServiceDetailPopup() {
        const SWAL_SOURCES = [
            'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js',
            'https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.14.5/sweetalert2.all.min.js',
            'https://unpkg.com/sweetalert2@11/dist/sweetalert2.all.min.js'
        ];

        function esc(str) {
            return String(str == null ? '' : str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        function getText(root, sel) {
            const el = root.querySelector(sel);
            return el ? el.textContent.trim() : '';
        }

        function isDarkTheme() {
            try {
                const html = document.documentElement;
                const body = document.body;
                const htmlTheme = (html.getAttribute('data-theme') || '').toLowerCase();
                const bodyTheme = (body.getAttribute('data-theme') || '').toLowerCase();
                const darkMarkers = ['dark', 'dark-mode', 'dark-theme', 'theme-dark'];
                const lightMarkers = ['light', 'light-mode', 'light-theme', 'theme-light'];

                if (htmlTheme === 'dark' || bodyTheme === 'dark') return true;
                for (let i = 0; i < darkMarkers.length; i++) {
                    if (html.classList.contains(darkMarkers[i]) ||
                        body.classList.contains(darkMarkers[i])) return true;
                }
                if (htmlTheme === 'light' || bodyTheme === 'light') return false;
                for (let i = 0; i < lightMarkers.length; i++) {
                    if (html.classList.contains(lightMarkers[i]) ||
                        body.classList.contains(lightMarkers[i])) return false;
                }
                return !!(window.matchMedia &&
                    window.matchMedia('(prefers-color-scheme: dark)').matches);
            } catch (err) {
                return false;
            }
        }

        function buildHtml(card, idx) {
            const desc = getText(card, '.svc-hub-text');
            const cname = getText(card, '.svc-hub-cname');
            const crole = getText(card, '.svc-hub-crole');

            const leadRow = card.querySelector('.svc-hub-detail:nth-child(2)');
            const teamRow = card.querySelector('.svc-hub-detail:nth-child(3)');
            const leadChips = leadRow
                ? Array.from(leadRow.querySelectorAll('.svc-hub-chip')).map(function (c) { return c.textContent.trim(); })
                : [];
            const teamChips = teamRow
                ? Array.from(teamRow.querySelectorAll('.svc-hub-chip')).map(function (c) { return c.textContent.trim(); })
                : [];

            let html = '';
            if (desc) {
                html += '<p class="swal-svc-desc">' + esc(desc) + '</p>';
            }

            if (cname || crole) {
                html += '<div class="swal-svc-row">' +
                    '<span class="swal-svc-key">Consultant</span>' +
                    '<span class="swal-svc-val">' +
                    '<strong>' + esc(cname) + '</strong>' +
                    (crole ? '<small>' + esc(crole) + '</small>' : '') +
                    '</span></div>';
            }

            if (leadChips.length) {
                html += '<div class="swal-svc-row">' +
                    '<span class="swal-svc-key">Lead</span>' +
                    '<span class="swal-svc-val">' +
                    leadChips.map(function (c) { return '<span class="swal-svc-chip">' + esc(c) + '</span>'; }).join('') +
                    '</span></div>';
            }

            if (teamChips.length) {
                html += '<div class="swal-svc-row">' +
                    '<span class="swal-svc-key">Team</span>' +
                    '<span class="swal-svc-val">' +
                    teamChips.map(function (c) { return '<span class="swal-svc-chip">' + esc(c) + '</span>'; }).join('') +
                    '</span></div>';
            }

            const section = SECTIONS[idx];
            if (section && Array.isArray(section.cards) && section.cards.length) {
                html += '<div class="swal-svc-caps-wrap">' +
                    '<h4 class="swal-svc-caps-title">What We Offer</h4>' +
                    '<ul class="swal-caps">';
                section.cards.forEach(function (cap) {
                    html += '<li><strong>' + esc(cap.t) + '</strong><p>' + esc(cap.d) + '</p></li>';
                });
                html += '</ul></div>';
            }

            return html;
        }

        function ensureSwal(callback) {
            if (typeof Swal !== 'undefined') {
                callback(true);
                return;
            }

            let sourceIndex = 0;

            function tryNext() {
                if (typeof Swal !== 'undefined') {
                    callback(true);
                    return;
                }
                if (sourceIndex >= SWAL_SOURCES.length) {
                    callback(false);
                    return;
                }
                const script = document.createElement('script');
                script.src = SWAL_SOURCES[sourceIndex++];
                script.onload = tryNext;
                script.onerror = tryNext;
                document.head.appendChild(script);
            }

            tryNext();
        }

        function openPopup(btn) {
            const card = btn.closest('.svc-hub-card');
            if (!card) return;

            const rawIdx = card.getAttribute('data-service-index') || btn.getAttribute('data-service-index');
            let idx = parseInt(rawIdx, 10);
            if (!Number.isFinite(idx) || idx < 0) idx = 0;

            const title = getText(card, '.svc-hub-name');
            const html = buildHtml(card, idx);

            const dark = isDarkTheme();
            const isReduced = window.matchMedia &&
                window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            Swal.fire({
                title: title,
                html: html,
                background: dark ? '#04092a' : '#ffffff',
                color: dark ? '#ffffff' : '#1a1a1a',
                confirmButtonText: 'Close',
                confirmButtonColor: '#1a66ff',
                width: '640px',
                padding: '0',
                showCloseButton: true,
                animation: !isReduced,
                customClass: {
                    popup: 'swal-svc-popup ' + (dark ? 'is-dark' : 'is-light'),
                    title: 'swal-svc-title',
                    htmlContainer: 'swal-svc-html',
                    confirmButton: 'swal-svc-confirm'
                }
            });
        }

        document.addEventListener('click', function (e) {
            const target = e.target;
            if (!target || typeof target.closest !== 'function') return;

            const btn = target.closest('.svc-hub-view-btn');
            if (!btn) return;

            e.preventDefault();
            e.stopPropagation();

            if (!window.matchMedia('(max-width: 1000px)').matches) return;

            ensureSwal(function (ok) {
                if (!ok) {
                    console.error('SweetAlert2 could not be loaded from any source');
                    return;
                }
                try {
                    openPopup(btn);
                } catch (err) {
                    console.error(err);
                }
            });
        }, true);
    })();

});