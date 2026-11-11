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

    const SWAL_SOURCES = [
        (window.SITE_URL ? window.SITE_URL : '') + '/assets/js/sweetalert2.all.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.14.5/sweetalert2.all.min.js'
    ];

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

    (function initServiceDetailPopup() {

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

    (function initServiceImagePreview() {
        const host = document.getElementById('all-services');
        if (!host || host.getAttribute('data-svc-preview-bound') === '1') return;
        host.setAttribute('data-svc-preview-bound', '1');

        function escape(str) {
            return String(str == null ? '' : str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        function fallbackMarkup(message) {
            return '<div class="svc-preview-fallback">' +
                '<i class="fa-regular fa-image" aria-hidden="true"></i>' +
                '<span>' + escape(message) + '</span>' +
                '</div>';
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

        function openPreview(src, title) {
            const reduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            const dark = isDarkTheme();
            const stageHtml = src
                ? '<div class="svc-preview-stage"><img class="svc-preview-img" src="' + escape(src) + '" alt="' + escape(title) + '"></div>'
                : '<div class="svc-preview-stage">' + fallbackMarkup('No image is available for this service yet.') + '</div>';

            Swal.fire({
                title: title,
                html: stageHtml,
                background: dark ? '#04092a' : '#ffffff',
                color: dark ? '#ffffff' : '#1a1a1a',
                width: '960px',
                padding: '0',
                showCloseButton: true,
                confirmButtonText: 'Close',
                confirmButtonColor: '#1a66ff',
                animation: !reduced,
                showClass: { popup: 'svc-preview-show' },
                hideClass: { popup: 'svc-preview-hide' },
                customClass: {
                    popup: 'svc-preview-popup ' + (dark ? 'is-dark' : 'is-light'),
                    title: 'svc-preview-title',
                    htmlContainer: 'svc-preview-html',
                    confirmButton: 'swal-svc-confirm'
                },
                didOpen: function (popup) {
                    const img = popup.querySelector('.svc-preview-img');
                    if (!img) return;

                    function onFailed() {
                        const stage = img.parentNode;
                        if (stage) stage.innerHTML = fallbackMarkup('The service image could not be loaded.');
                    }

                    if (img.complete) {
                        if (img.naturalWidth === 0) {
                            onFailed();
                        }
                    } else {
                        img.addEventListener('error', onFailed, { once: true });
                    }
                }
            });
        }

        host.addEventListener('click', function (e) {
            const btn = e.target.closest('.svc-hub-more-btn, .svc-view-more');
            if (!btn) return;
            e.preventDefault();
            e.stopPropagation();

            const imgUrl = btn.getAttribute('data-img') || '';
            const title = btn.getAttribute('data-title') || '';

            ensureSwal(function (ok) {
                if (!ok) {
                    console.error('SweetAlert2 could not be loaded');
                    return;
                }
                openPreview(imgUrl, title);
            });
        });
    })();

});