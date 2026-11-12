document.addEventListener('DOMContentLoaded', function () {

    var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

    var navbar = document.querySelector('.cwt-navbar, .navbar');
    if (navbar) {
        var onScroll = function () {
            navbar.classList.toggle('is-scrolled', window.scrollY > 60);
            navbar.classList.toggle('scrolled', window.scrollY > 60);
        };
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
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

    var grid = document.getElementById('awardsGrid');
    var nav = document.getElementById('awardsPagination');
    if (!grid || !nav) return;

    var cards = Array.prototype.slice.call(grid.querySelectorAll('.award-card'));
    if (!cards.length) return;

    var perPageDesktop = parseInt(nav.getAttribute('data-desktop'), 10) || 5;
    var perPageMobile = parseInt(nav.getAttribute('data-mobile'), 10) || 3;
    var mobileQuery = window.matchMedia('(max-width: 599px)');
    var currentPage = 1;
    var busy = false;
    var cardObserver = null;

    var MAX_ANGLE = Math.PI / 3; // 60 degrees scroll-based rotation
    var LOAD_TIMEOUT = 12000;
    var models = [];
    var tickPending = false;

    function getPerPage() {
        return mobileQuery.matches ? perPageMobile : perPageDesktop;
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

        // 1. Direct group access (same-origin, fastest)
        try {
            if (win.cwtAwardGroup) {
                win.cwtAwardGroup.rotation.y = angle;
                return;
            }
        } catch (errGrp) { }

        // 2. Exposed function (same-origin fallback)
        try {
            if (typeof win.cwtAwardRotate === 'function') {
                win.cwtAwardRotate(angle, progress);
                return;
            }
        } catch (errFn) { }

        // 3. postMessage (cross-origin fallback)
        try {
            win.postMessage({ type: 'cwt-award-rotate', angle: angle, progress: progress }, '*');
        } catch (errMsg) { }
    }

    function targetProgress(model) {
        var rect = model.wrap.getBoundingClientRect();
        var vh = window.innerHeight || document.documentElement.clientHeight;
        var travel = vh + rect.height;
        if (travel <= 0) return 0;
        var p = (vh - rect.top) / travel;
        return Math.max(0, Math.min(1, p));
    }

    function tick() {
        tickPending = false;
        var again = false;

        models.forEach(function (model) {
            if (!model.ready || model.card.hidden) return;
            var progress = targetProgress(model);
            var target = progress * MAX_ANGLE;

            if (reducedMotion.matches) {
                model.current = target;
            } else {
                model.current += (target - model.current) * 0.14;
                if (Math.abs(target - model.current) < 0.001) {
                    model.current = target;
                } else {
                    again = true;
                }
            }

            if (model.applied !== model.current || !model.touched) {
                rotateModel(model, model.current, progress);
                model.applied = model.current;
                model.touched = true;
            }
        });

        if (again) requestTick();
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
                current: 0,
                applied: 0,
                group: null,
                bases: null,
                poll: null,
                timeout: null
            });
        });
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

    function renderPagination() {
        var total = getTotalPages();
        if (total <= 1) {
            nav.innerHTML = '';
            nav.setAttribute('hidden', '');
            return;
        }
        nav.removeAttribute('hidden');

        var html = '';
        html += '<button type="button" class="page-btn prev-btn" data-action="prev" aria-label="Previous page"' + (currentPage === 1 ? ' disabled' : '') + '>' +
            '<i class="fa-solid fa-chevron-left" aria-hidden="true"></i><span>Prev</span></button>';

        pageList(total, currentPage).forEach(function (p) {
            if (p === 'gap') {
                html += '<span class="page-ellipsis" aria-hidden="true">&hellip;</span>';
                return;
            }
            var active = p === currentPage;
            html += '<button type="button" class="page-btn' + (active ? ' active' : '') + '" data-action="page" data-page="' + p + '" aria-label="Page ' + p + '"' + (active ? ' aria-current="page"' : '') + '>' + p + '</button>';
        });

        html += '<button type="button" class="page-btn next-btn" data-action="next" aria-label="Next page"' + (currentPage === total ? ' disabled' : '') + '>' +
            '<span>Next</span><i class="fa-solid fa-chevron-right" aria-hidden="true"></i></button>';

        nav.innerHTML = html;
    }

    function scrollToSection() {
        var section = document.getElementById('awards');
        if (!section) return;
        var offset = (navbar ? navbar.offsetHeight : 72) + 16;
        var top = section.getBoundingClientRect().top + window.pageYOffset - offset;
        window.scrollTo({ top: top, behavior: reducedMotion.matches ? 'auto' : 'smooth' });
    }

    function restoreFocus(action) {
        var target = null;
        if (action === 'next') target = nav.querySelector('[data-action="next"]:not(:disabled)');
        else if (action === 'prev') target = nav.querySelector('[data-action="prev"]:not(:disabled)');
        if (!target) target = nav.querySelector('.page-btn.active');
        if (target) target.focus({ preventScroll: true });
    }

    function goToPage(page, action) {
        if (busy || page === currentPage) return;
        busy = true;
        currentPage = page;

        var apply = function () {
            showPage(currentPage);
            renderPagination();
            restoreFocus(action);
            scrollToSection();
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

    if (mobileQuery.addEventListener) mobileQuery.addEventListener('change', onBreakpointChange);
    else if (mobileQuery.addListener) mobileQuery.addListener(onBreakpointChange);

    window.addEventListener('scroll', requestTick, { passive: true });
    window.addEventListener('resize', requestTick);

    setupModels();
    watchImages();
    showPage(currentPage);
    renderPagination();

});