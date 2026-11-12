document.addEventListener('DOMContentLoaded', function () {

    var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    var mobileQuery = window.matchMedia('(max-width: 599px)');
    var tabletQuery = window.matchMedia('(max-width: 991px)');

    var navbar = document.querySelector('.cwt-navbar, .navbar');
    if (navbar) {
        var onScroll = function () {
            navbar.classList.toggle('is-scrolled', window.scrollY > 60);
            navbar.classList.toggle('scrolled', window.scrollY > 60);
        };
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
    }

    function listenQuery(query, handler) {
        if (query.addEventListener) query.addEventListener('change', handler);
        else if (query.addListener) query.addListener(handler);
    }

    function pageList(total, current) {
        if (total <= 7) {
            var all = [];
            for (var p = 1; p <= total; p++) all.push(p);
            return all;
        }
        var set = [1, total, current - 1, current, current + 1];
        var pages = set.filter(function (p, i) {
            return p >= 1 && p <= total && set.indexOf(p) === i;
        }).sort(function (a, b) { return a - b; });
        var out = [];
        pages.forEach(function (p, i) {
            if (i > 0 && p - pages[i - 1] > 1) out.push('gap');
            out.push(p);
        });
        return out;
    }

    function buildPagination(target, total, current) {
        if (total <= 1) {
            target.innerHTML = '';
            target.setAttribute('hidden', '');
            return;
        }
        target.removeAttribute('hidden');

        var html = '';
        html += '<button type="button" class="page-btn prev-btn" data-action="prev" aria-label="Previous page"' + (current === 1 ? ' disabled' : '') + '>' +
            '<i class="fa-solid fa-chevron-left" aria-hidden="true"></i><span>Prev</span></button>';

        pageList(total, current).forEach(function (p) {
            if (p === 'gap') {
                html += '<span class="page-ellipsis" aria-hidden="true">&hellip;</span>';
                return;
            }
            var active = p === current;
            html += '<button type="button" class="page-btn' + (active ? ' active' : '') + '" data-action="page" data-page="' + p + '" aria-label="Page ' + p + '"' + (active ? ' aria-current="page"' : '') + '>' + p + '</button>';
        });

        html += '<button type="button" class="page-btn next-btn" data-action="next" aria-label="Next page"' + (current === total ? ' disabled' : '') + '>' +
            '<span>Next</span><i class="fa-solid fa-chevron-right" aria-hidden="true"></i></button>';

        target.innerHTML = html;
    }

    function scrollToId(id) {
        var section = document.getElementById(id);
        if (!section) return;
        var offset = (navbar ? navbar.offsetHeight : 72) + 16;
        var top = section.getBoundingClientRect().top + window.pageYOffset - offset;
        window.scrollTo({ top: top, behavior: reducedMotion.matches ? 'auto' : 'smooth' });
    }

    function restoreNavFocus(target, action) {
        var btn = null;
        if (action === 'next') btn = target.querySelector('[data-action="next"]:not(:disabled)');
        else if (action === 'prev') btn = target.querySelector('[data-action="prev"]:not(:disabled)');
        if (!btn) btn = target.querySelector('.page-btn.active');
        if (btn) btn.focus({ preventScroll: true });
    }

    function initPager(cfg) {
        var holder = document.getElementById(cfg.holder);
        var pagerNav = document.getElementById(cfg.nav);
        if (!holder || !pagerNav) return;

        var items = Array.prototype.slice.call(holder.querySelectorAll(cfg.item));
        if (!items.length) return;

        var perDesktop = parseInt(pagerNav.getAttribute('data-desktop'), 10) || 6;
        var perTablet = parseInt(pagerNav.getAttribute('data-tablet'), 10) || 4;
        var perMobile = parseInt(pagerNav.getAttribute('data-mobile'), 10) || 2;
        var page = 1;

        function perPage() {
            if (mobileQuery.matches) return perMobile;
            if (tabletQuery.matches) return perTablet;
            return perDesktop;
        }

        function totalPages() {
            return Math.max(1, Math.ceil(items.length / perPage()));
        }

        function show() {
            var per = perPage();
            var start = (page - 1) * per;
            var end = start + per;
            items.forEach(function (item, i) {
                item.hidden = !(i >= start && i < end);
                item.classList.remove('is-flipped');
                if (item.hasAttribute('aria-pressed')) item.setAttribute('aria-pressed', 'false');
            });
        }

        function render() {
            buildPagination(pagerNav, totalPages(), page);
        }

        var lastPer = perPage();

        function onBreakpointChange() {
            var newPer = perPage();
            if (newPer === lastPer) return;
            var firstIndex = (page - 1) * lastPer;
            lastPer = newPer;
            page = Math.min(Math.floor(firstIndex / newPer) + 1, totalPages());
            show();
            render();
        }

        function goTo(target, action) {
            if (target === page || target < 1 || target > totalPages()) return;
            page = target;
            show();
            render();
            restoreNavFocus(pagerNav, action);
            scrollToId(cfg.section);
        }

        pagerNav.addEventListener('click', function (e) {
            var btn = e.target.closest('.page-btn');
            if (!btn || btn.disabled) return;
            var action = btn.getAttribute('data-action');
            if (action === 'prev') goTo(page - 1, action);
            else if (action === 'next') goTo(page + 1, action);
            else if (action === 'page') goTo(parseInt(btn.getAttribute('data-page'), 10), action);
        });

        listenQuery(mobileQuery, onBreakpointChange);
        listenQuery(tabletQuery, onBreakpointChange);

        show();
        render();
    }

    var revealEls = document.querySelectorAll('.reveal');
    if (revealEls.length) {
        if (reducedMotion.matches || !('IntersectionObserver' in window)) {
            revealEls.forEach(function (el) {
                el.classList.add('visible', 'is-visible');
            });
        } else {
            var revealObserver = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible', 'is-visible');
                        revealObserver.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12 });
            revealEls.forEach(function (el) {
                revealObserver.observe(el);
            });
        }
    }

    initPager({ holder: 'certTrack', nav: 'certPagination', item: '.cert-card', section: 'certifications' });

    var apprCards = document.querySelectorAll('.appr-card');
    if (apprCards.length) {
        var apprFineHover = window.matchMedia('(hover: hover) and (pointer: fine)');

        apprCards.forEach(function (card) {
            var toggleCard = function () {
                var flipped = card.classList.toggle('is-flipped');
                card.setAttribute('aria-pressed', flipped ? 'true' : 'false');
            };

            card.addEventListener('click', function () {
                if (!apprFineHover.matches) toggleCard();
            });

            card.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    toggleCard();
                }
            });
        });
    }

    initPager({ holder: 'apprGrid', nav: 'apprPagination', item: '.appr-card', section: 'appreciations' });

    var grid = document.getElementById('awardsGrid');
    var nav = document.getElementById('awardsPagination');
    if (!grid || !nav) return;

    var cards = Array.prototype.slice.call(grid.querySelectorAll('.award-card'));
    if (!cards.length) return;

    var perPageDesktop = parseInt(nav.getAttribute('data-desktop'), 10) || 3;
    var perPageTablet = parseInt(nav.getAttribute('data-tablet'), 10) || 2;
    var perPageMobile = parseInt(nav.getAttribute('data-mobile'), 10) || 2;
    var currentPage = 1;
    var busy = false;
    var cardObserver = null;

    var MAX_ANGLE = Math.PI / 3;
    var LOAD_TIMEOUT = 12000;
    var models = [];
    var tickPending = false;
    var isScrolling = false;
    var scrollTimer = null;
    var scrollWeight = 0;
    var lastTimestamp = 0;

    function onScrollActivity() {
        isScrolling = true;
        clearTimeout(scrollTimer);
        scrollTimer = setTimeout(function () {
            isScrolling = false;
        }, 350);
        requestTick();
    }

    function isCardInView(card) {
        if (!card || card.hidden) return false;
        var rect = card.getBoundingClientRect();
        var vh = window.innerHeight || document.documentElement.clientHeight;
        return rect.bottom > 0 && rect.top < vh;
    }

    function cardScrollProgress(card) {
        var rect = card.getBoundingClientRect();
        var vh = window.innerHeight || document.documentElement.clientHeight;
        var travel = vh + rect.height;
        if (travel <= 0) return 0.5;
        var p = (vh - rect.top) / travel;
        return Math.max(0, Math.min(1, p));
    }

    function getPerPage() {
        if (mobileQuery.matches) return perPageMobile;
        if (tabletQuery.matches) return perPageTablet;
        return perPageDesktop;
    }

    function getTotalPages() {
        return Math.max(1, Math.ceil(cards.length / getPerPage()));
    }

    function watchImages() {
        cards.forEach(function (card) {
            var wrap = card.querySelector('.award-image-wrap');
            var img = card.querySelector('.award-image');
            if (!wrap || !img) return;
            var markBroken = function () {
                wrap.classList.add('is-broken');
            };
            var classify = function () {
                if (!img.naturalWidth) return;
                var portrait = img.naturalHeight > img.naturalWidth * 1.1;
                wrap.classList.toggle('is-portrait', portrait);
                wrap.classList.toggle('is-landscape', !portrait);
            };
            img.addEventListener('error', markBroken);
            img.addEventListener('load', classify);
            if (img.complete && img.naturalWidth === 0 && img.getAttribute('src')) markBroken();
            if (img.complete) classify();
        });
    }

    function isRotatable(child) {
        return !(child.isLight || child.isCamera);
    }

    function getScene(win) {
        var found = null;
        try {
            found = win.eval('typeof scene !== "undefined" ? scene : null');
        } catch (err) {
            found = null;
        }
        if (!found) {
            try {
                found = win.scene || null;
            } catch (err2) {
                found = null;
            }
        }
        if (found && found.children && typeof found.add === 'function') return found;
        return null;
    }

    function rotateModel(model, angle, progress) {
        var frame = model.frame;
        var win;
        try {
            win = frame.contentWindow;
        } catch (err) {
            return;
        }
        if (!win) return;

        try {
            if (win.cwtAwardGroup) {
                win.cwtAwardGroup.rotation.y = angle;
                return;
            }
        } catch (errGrp) { }

        try {
            if (typeof win.cwtAwardRotate === 'function') {
                win.cwtAwardRotate(angle, progress);
                return;
            }
        } catch (errFn) { }

        try {
            win.postMessage({ type: 'cwt-award-rotate', angle: angle, progress: progress }, '*');
        } catch (errMsg) { }
    }

    function tick(timestamp) {
        tickPending = false;
        if (!timestamp) timestamp = (typeof performance !== 'undefined' ? performance.now() : Date.now());
        var dt = lastTimestamp ? (timestamp - lastTimestamp) / 1000 : 0.016;
        if (dt > 0.1) dt = 0.016;
        lastTimestamp = timestamp;

        var targetWeight = isScrolling ? 1 : 0;
        scrollWeight += (targetWeight - scrollWeight) * (isScrolling ? 0.3 : 0.08);
        if (Math.abs(targetWeight - scrollWeight) < 0.005) {
            scrollWeight = targetWeight;
        }

        var anyActive = false;

        models.forEach(function (model) {
            if (!model.ready || model.card.hidden) return;

            var inView = isCardInView(model.card);
            model.inView = inView;

            if (!inView) {
                if (Math.abs(model.current) > 0.002) {
                    model.current += (0 - model.current) * 0.12;
                    rotateModel(model, model.current, 0.5);
                    model.applied = model.current;
                    anyActive = true;
                } else if (model.applied !== 0) {
                    model.current = 0;
                    rotateModel(model, 0, 0.5);
                    model.applied = 0;
                }
                return;
            }

            anyActive = true;

            if (reducedMotion.matches) {
                model.current = 0;
                if (model.applied !== 0) {
                    rotateModel(model, 0, 0.5);
                    model.applied = 0;
                }
                return;
            }

            var p = cardScrollProgress(model.card);
            var scrollAngle = (p * 2 - 1) * MAX_ANGLE;

            if (isScrolling) {
                var norm = Math.max(-0.999, Math.min(0.999, model.current / MAX_ANGLE));
                model.oscPhase = Math.asin(norm);
            } else {
                model.oscPhase = (model.oscPhase || 0) + dt * 1.4;
                if (model.oscPhase > Math.PI * 2) model.oscPhase -= Math.PI * 2;
            }
            var oscAngle = Math.sin(model.oscPhase || 0) * MAX_ANGLE;

            var target = (scrollWeight * scrollAngle) + ((1 - scrollWeight) * oscAngle);
            target = Math.max(-MAX_ANGLE, Math.min(MAX_ANGLE, target));

            model.current += (target - model.current) * 0.12;

            if (Math.abs(model.applied - model.current) > 0.0005 || !model.touched) {
                rotateModel(model, model.current, p);
                model.applied = model.current;
                model.touched = true;
            }
        });

        if (anyActive) {
            requestTick();
        }
    }

    function requestTick() {
        if (tickPending) return;
        tickPending = true;
        window.requestAnimationFrame(tick);
    }

    function clearTimers(model) {
        if (model.poll) {
            clearInterval(model.poll);
            model.poll = null;
        }
        if (model.timeout) {
            clearTimeout(model.timeout);
            model.timeout = null;
        }
    }

    function markReady(model) {
        clearTimers(model);
        model.ready = true;
        model.touched = false;
        model.group = null;
        model.bases = null;
        model.wrap.classList.add('is-3d-ready');
        requestTick();
    }

    function markFailed(model) {
        clearTimers(model);
        model.ready = false;
        model.loaded = false;
        model.wrap.classList.remove('is-3d-ready');
        model.wrap.classList.add('is-3d-failed');
        model.frame.src = 'about:blank';
    }

    function loadModel(model) {
        if (model.loaded || model.failed) return;
        model.loaded = true;
        model.wrap.classList.remove('is-3d-failed');

        var frame = model.frame;
        var started = Date.now();

        frame.onload = function () {
            if (!model.loaded || frame.getAttribute('src') === 'about:blank') return;
            clearTimers(model);
            model.poll = setInterval(function () {
                var doc = null;
                try {
                    doc = frame.contentDocument;
                } catch (err) {
                    markReady(model);
                    return;
                }
                if (doc && doc.querySelector('canvas')) {
                    markReady(model);
                    return;
                }
                if (Date.now() - started > LOAD_TIMEOUT) {
                    model.failed = true;
                    markFailed(model);
                }
            }, 200);
        };

        frame.src = frame.getAttribute('data-src');

        model.timeout = setTimeout(function () {
            if (!model.ready) {
                model.failed = true;
                markFailed(model);
            }
        }, LOAD_TIMEOUT + 2000);
    }

    function unloadModel(model) {
        if (!model.loaded && !model.ready) return;
        clearTimers(model);
        model.loaded = false;
        model.ready = false;
        model.touched = false;
        model.group = null;
        model.bases = null;
        model.wrap.classList.remove('is-3d-ready');
        model.frame.onload = null;
        model.frame.src = 'about:blank';
    }

    function setupModels() {
        cards.forEach(function (card) {
            var wrap = card.querySelector('.award-image-wrap');
            var frame = card.querySelector('.award-3d');
            if (!wrap || !frame) return;
            models.push({
                card: card,
                wrap: wrap,
                frame: frame,
                loaded: false,
                ready: false,
                failed: false,
                touched: false,
                inView: false,
                oscPhase: 0,
                current: 0,
                applied: 0,
                group: null,
                bases: null,
                poll: null,
                timeout: null
            });
        });

        if ('IntersectionObserver' in window) {
            var cardViewObserver = new IntersectionObserver(function (entries) {
                var shouldTick = false;
                entries.forEach(function (entry) {
                    models.forEach(function (m) {
                        if (m.card === entry.target) {
                            m.inView = entry.isIntersecting;
                            if (m.inView) shouldTick = true;
                        }
                    });
                });
                if (shouldTick) requestTick();
            }, { threshold: [0, 0.1, 0.5] });

            models.forEach(function (m) {
                cardViewObserver.observe(m.card);
            });
        }
    }

    function syncModels() {
        models.forEach(function (model) {
            if (model.card.hidden) unloadModel(model);
            else loadModel(model);
        });
        requestTick();
    }

    function observeVisible(visible) {
        if (cardObserver) {
            cardObserver.disconnect();
            cardObserver = null;
        }
        if (reducedMotion.matches || !('IntersectionObserver' in window)) {
            visible.forEach(function (card) {
                card.classList.add('is-visible');
            });
            return;
        }
        cardObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    cardObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
        visible.forEach(function (card) {
            cardObserver.observe(card);
        });
    }

    function showPage(page) {
        var perPage = getPerPage();
        var start = (page - 1) * perPage;
        var end = start + perPage;
        var mobile = mobileQuery.matches;
        var visible = [];

        cards.forEach(function (card, i) {
            var inPage = i >= start && i < end;
            card.hidden = !inPage;
            card.classList.remove('is-visible', 'is-reversed', 'is-last');
            card.style.removeProperty('--award-delay');
            if (inPage) visible.push(card);
        });

        visible.forEach(function (card, idx) {
            card.style.setProperty('--award-delay', (idx * 180) + 'ms');
            if (!mobile && idx % 2 === 1) card.classList.add('is-reversed');
            if (idx === visible.length - 1) card.classList.add('is-last');
        });

        observeVisible(visible);
        syncModels();
    }

    function renderPagination() {
        buildPagination(nav, getTotalPages(), currentPage);
    }

    function goToPage(page, action) {
        if (busy || page === currentPage) return;
        busy = true;
        currentPage = page;

        var apply = function () {
            showPage(currentPage);
            renderPagination();
            restoreNavFocus(nav, action);
            scrollToId('awards');
            grid.classList.remove('is-fading');
            busy = false;
        };

        if (reducedMotion.matches) {
            apply();
            return;
        }

        grid.classList.add('is-fading');
        setTimeout(apply, 300);
    }

    nav.addEventListener('click', function (e) {
        var btn = e.target.closest('.page-btn');
        if (!btn || btn.disabled) return;
        var action = btn.getAttribute('data-action');
        var total = getTotalPages();
        if (action === 'prev' && currentPage > 1) goToPage(currentPage - 1, action);
        else if (action === 'next' && currentPage < total) goToPage(currentPage + 1, action);
        else if (action === 'page') goToPage(parseInt(btn.getAttribute('data-page'), 10), action);
    });

    var lastPerPage = getPerPage();
    var onBreakpointChange = function () {
        var newPerPage = getPerPage();
        if (newPerPage === lastPerPage) return;
        var firstIndex = (currentPage - 1) * lastPerPage;
        lastPerPage = newPerPage;
        currentPage = Math.min(Math.floor(firstIndex / newPerPage) + 1, getTotalPages());
        showPage(currentPage);
        renderPagination();
    };

    listenQuery(mobileQuery, onBreakpointChange);
    listenQuery(tabletQuery, onBreakpointChange);

    window.addEventListener('scroll', onScrollActivity, { passive: true });
    window.addEventListener('resize', onScrollActivity);

    setupModels();
    watchImages();
    showPage(currentPage);
    renderPagination();

});