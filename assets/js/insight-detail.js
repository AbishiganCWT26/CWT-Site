/**
 * insight-detail.js
 * Lightweight progressive enhancements for the Insight Detail page.
 * No frameworks, no bundlers, no new external dependencies.
 */
document.addEventListener('DOMContentLoaded', function () {

    /* ─── Navbar scroll state ───────────────────────────────────────────────── */
    var navbar = document.querySelector('.cwt-navbar, .navbar');
    if (navbar) {
        var onNavScroll = function () {
            navbar.classList.toggle('is-scrolled', window.scrollY > 60);
            navbar.classList.toggle('scrolled',    window.scrollY > 60);
        };
        onNavScroll();
        window.addEventListener('scroll', onNavScroll, { passive: true });
    }

    /* ─── Reading-progress bar ──────────────────────────────────────────────── */
    var progressBar = document.getElementById('indProgressBar');
    var article     = document.querySelector('.ind-article');

    if (progressBar && article) {
        var rafId = 0;

        var updateProgress = function () {
            var scrollTop  = window.scrollY || window.pageYOffset;
            var docHeight  = document.documentElement.scrollHeight - window.innerHeight;
            var pct        = docHeight > 0 ? Math.min(100, Math.max(0, (scrollTop / docHeight) * 100)) : 0;
            progressBar.style.width = pct.toFixed(2) + '%';
        };

        var onScroll = function () {
            if (rafId) return;
            rafId = requestAnimationFrame(function () {
                updateProgress();
                rafId = 0;
            });
        };

        updateProgress();
        window.addEventListener('scroll', onScroll,  { passive: true });
        window.addEventListener('resize', onScroll,  { passive: true });
    }

    /* ─── Reveal-on-scroll ──────────────────────────────────────────────────── */
    var revealObserver = new IntersectionObserver(function (entries) {
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

    /* ─── Table wrapping (progressive enhancement) ──────────────────────────
     * Raw <table> elements from the Quill HTML editor are wrapped in a
     * scrollable container so they never break the layout on narrow screens.
     * We use the class .insight-table-wrap (styled in insight-detail.css).
     * Tables already inside .blog-table-wrap (JSON mode) are left alone.
     * ─────────────────────────────────────────────────────────────────────── */
    var content = document.querySelector('.ind-content');

    if (content) {
        var tables = content.querySelectorAll('table');
        tables.forEach(function (table) {
            /* Skip if already wrapped */
            if (table.parentNode && table.parentNode.classList &&
                (table.parentNode.classList.contains('insight-table-wrap') ||
                 table.parentNode.classList.contains('blog-table-wrap'))) {
                return;
            }

            /* Add styling classes to match the JSON-mode table styles */
            if (!table.classList.contains('blog-table')) {
                table.classList.add('blog-table');
            }

            /* Create the scroll wrapper */
            var wrap = document.createElement('div');
            wrap.className = 'insight-table-wrap';

            table.parentNode.insertBefore(wrap, table);
            wrap.appendChild(table);
        });
    }

    /* ─── Copy-to-clipboard on code blocks ──────────────────────────────────
     * Adds a small "Copy" button to every <pre> (raw HTML) and .blog-code
     * (JSON mode) block. Uses the Clipboard API with a textarea fallback.
     * ─────────────────────────────────────────────────────────────────────── */
    if (content) {
        var codeBlocks = content.querySelectorAll('pre, .blog-code');
        codeBlocks.forEach(function (block) {
            /* Skip duplicates if .blog-code is also a <pre> */
            if (block._cwtCopyAdded) return;
            block._cwtCopyAdded = true;

            /* Ensure position:relative so the button can be positioned inside */
            block.style.position = 'relative';

            /* Build copy button */
            var btn        = document.createElement('button');
            btn.type       = 'button';
            btn.className  = 'ind-code-copy';
            btn.title      = 'Copy code';
            btn.setAttribute('aria-label', 'Copy code to clipboard');
            btn.innerHTML  =
                '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" ' +
                'stroke="currentColor" stroke-width="2.2" stroke-linecap="round" ' +
                'stroke-linejoin="round" aria-hidden="true">' +
                '<rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>' +
                '<path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>' +
                '</svg>' +
                '<span class="ind-code-copy-text">Copy</span>';

            /* Inject copy button CSS inline (keeps JS self-contained) */
            if (!document.getElementById('ind-code-copy-style')) {
                var style       = document.createElement('style');
                style.id        = 'ind-code-copy-style';
                style.textContent =
                    '.ind-code-copy {' +
                    '  position: absolute;' +
                    '  top: 12px;' +
                    '  right: 12px;' +
                    '  display: inline-flex;' +
                    '  align-items: center;' +
                    '  gap: 5px;' +
                    '  padding: 4px 10px;' +
                    '  background: rgba(255,255,255,0.08);' +
                    '  border: 1px solid rgba(255,255,255,0.14);' +
                    '  border-radius: 6px;' +
                    '  color: rgba(224,232,255,0.8);' +
                    '  font-family: inherit;' +
                    '  font-size: 0.7rem;' +
                    '  font-weight: 600;' +
                    '  letter-spacing: 0.03em;' +
                    '  cursor: pointer;' +
                    '  transition: background 0.25s ease, color 0.25s ease;' +
                    '  z-index: 2;' +
                    '}' +
                    '.ind-code-copy:hover {' +
                    '  background: rgba(255,255,255,0.15);' +
                    '  color: #fff;' +
                    '}' +
                    '.ind-code-copy:focus-visible {' +
                    '  outline: 2px solid rgba(91,156,255,0.8);' +
                    '  outline-offset: 2px;' +
                    '}' +
                    '.ind-code-copy.is-copied {' +
                    '  background: rgba(34,197,94,0.18);' +
                    '  border-color: rgba(34,197,94,0.4);' +
                    '  color: #4ade80;' +
                    '}';
                document.head.appendChild(style);
            }

            block.appendChild(btn);

            var resetTimer = null;

            btn.addEventListener('click', function () {
                /* Grab the text from <code> child if present, else the block itself */
                var codeEl  = block.querySelector('code');
                var text    = codeEl ? (codeEl.innerText || codeEl.textContent)
                                     : (block.innerText  || block.textContent);

                /* Strip the button's own label text to avoid copying it */
                text = text.replace(/\bCopy\b|\bCopied!\b/g, '').trim();

                var copyText = btn.querySelector('.ind-code-copy-text');
                var svg      = btn.querySelector('svg');

                var showCopied = function () {
                    btn.classList.add('is-copied');
                    if (copyText) copyText.textContent = 'Copied!';
                    if (svg) svg.innerHTML =
                        '<polyline points="20 6 9 17 4 12" stroke-width="2.5"/>';

                    if (resetTimer) clearTimeout(resetTimer);
                    resetTimer = setTimeout(function () {
                        btn.classList.remove('is-copied');
                        if (copyText) copyText.textContent = 'Copy';
                        if (svg) svg.innerHTML =
                            '<rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>' +
                            '<path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>';
                    }, 2200);
                };

                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(text).then(showCopied).catch(function () {
                        fallbackCopy(text, showCopied);
                    });
                } else {
                    fallbackCopy(text, showCopied);
                }
            });
        });
    }

    /* ─── Clipboard textarea fallback ───────────────────────────────────────── */
    function fallbackCopy(text, callback) {
        var temp = document.createElement('textarea');
        temp.value = text;
        temp.setAttribute('readonly', '');
        temp.style.cssText = 'position:absolute;left:-9999px;top:0;';
        document.body.appendChild(temp);
        temp.select();
        try { document.execCommand('copy'); } catch (err) { /* silent */ }
        document.body.removeChild(temp);
        if (callback) callback();
    }

    /* ─── Copy-page-link button ──────────────────────────────────────────────── */
    var copyBtn = document.getElementById('indCopyLink');
    if (copyBtn) {
        var copyText = copyBtn.querySelector('.ind-copy-text');
        var copyIcon = copyBtn.querySelector('i');
        var resetTimer2 = null;

        copyBtn.addEventListener('click', function () {
            var url = window.location.href;

            var showCopied = function () {
                copyBtn.classList.add('is-copied');
                if (copyText) copyText.textContent = 'Copied!';
                if (copyIcon) copyIcon.className = 'fa-solid fa-check';

                if (resetTimer2) clearTimeout(resetTimer2);
                resetTimer2 = setTimeout(function () {
                    copyBtn.classList.remove('is-copied');
                    if (copyText) copyText.textContent = 'Copy Link';
                    if (copyIcon) copyIcon.className = 'fa-solid fa-link';
                }, 2000);
            };

            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(url).then(showCopied).catch(function () {
                    fallbackCopy(url, showCopied);
                });
            } else {
                fallbackCopy(url, showCopied);
            }
        });
    }

    /* =========================================================
       HERO NEXUS FIELD
       Interactive starfield with cursor attraction, twinkle,
       constellation web, and a bright cursor hub.
       Runs only on the hero canvas (#heroCanvas).
       ========================================================= */
    var heroCanvas = document.getElementById('heroCanvas');
    if (heroCanvas && heroCanvas.getContext) {
        (function () {
            var hero = heroCanvas.parentElement;
            var ctx  = heroCanvas.getContext('2d');
            if (!ctx || !hero) return;

            var DENSITY_DIVISOR = 1200, MAX_STARS = 900, MOUSE_RADIUS = 220, MOUSE_FORCE = 2.2;
            var SPRING = 0.012, DAMPING = 0.91, CONSTELLATION_RANGE = 90, CONSTELLATION_ALPHA = 0.16;
            var COLORS = { white:[255,255,255], paleBlue:[200,220,255], blue400:[91,156,255], blue500:[43,123,255], pink:[126,160,248] };
            var w = 0, h = 0, stars = [], running = true;
            var mouse = { x: 0, y: 0, active: false };

            function resize() {
                var dpr = window.devicePixelRatio || 1, rect = hero.getBoundingClientRect();
                w = rect.width; h = rect.height;
                heroCanvas.width = Math.round(w * dpr); heroCanvas.height = Math.round(h * dpr);
                heroCanvas.style.width = w + 'px'; heroCanvas.style.height = h + 'px';
                ctx.setTransform(dpr, 0, 0, dpr, 0, 0); initStars();
            }
            function pickColor() {
                var r = Math.random();
                if (r < 0.55) return COLORS.white; if (r < 0.75) return COLORS.paleBlue;
                if (r < 0.88) return COLORS.blue400; if (r < 0.96) return COLORS.pink;
                return COLORS.blue500;
            }
            function initStars() {
                var count = Math.min(Math.round((w * h) / DENSITY_DIVISOR), MAX_STARS);
                stars = new Array(count).fill(0).map(function () {
                    var x = Math.random() * w, y = Math.random() * h;
                    var depth = Math.random() < 0.55 ? 0 : (Math.random() < 0.7 ? 1 : 2);
                    var baseR = depth === 0 ? Math.random() * 0.5 + 0.25 : depth === 1 ? Math.random() * 0.9 + 0.4 : Math.random() * 1.4 + 0.6;
                    var r = Math.random(), shape = r < 0.78 ? 'dot' : r < 0.94 ? 'sparkle' : 'streak';
                    return { x: x, y: y, homeX: x, homeY: y, vx: 0, vy: 0, r: baseR, depth: depth, shape: shape, color: pickColor(),
                        baseAlpha: (depth === 0 ? 0.20 : depth === 1 ? 0.35 : 0.55) + Math.random() * 0.30,
                        phase: Math.random() * Math.PI * 2, speed: Math.random() * 0.018 + 0.005,
                        twinkleAmp: Math.random() * 0.30 + 0.15,
                        driftX: (Math.random() - 0.5) * 0.04 * (depth + 1), driftY: (Math.random() - 0.5) * 0.04 * (depth + 1),
                        angle: Math.random() * Math.PI * 2, proximity: 0 };
                });
            }
            function setPointer(cx, cy) { var rect = hero.getBoundingClientRect(); mouse.x = cx - rect.left; mouse.y = cy - rect.top; mouse.active = true; }
            hero.addEventListener('mousemove', function (e) { setPointer(e.clientX, e.clientY); });
            hero.addEventListener('mouseleave', function () { mouse.active = false; });
            hero.addEventListener('touchstart', function (e) { var t = e.touches[0]; if (t) setPointer(t.clientX, t.clientY); }, { passive: true });
            hero.addEventListener('touchmove',  function (e) { var t = e.touches[0]; if (t) setPointer(t.clientX, t.clientY); }, { passive: true });
            hero.addEventListener('touchend',   function () { mouse.active = false; });

            function drawDot(s, drawR, alpha) { ctx.beginPath(); ctx.fillStyle = 'rgba(' + s.color.join(',') + ',' + alpha + ')'; ctx.arc(s.x, s.y, drawR, 0, Math.PI * 2); ctx.fill(); }
            function drawSparkle(s, drawR, alpha, gb) {
                var arm = drawR * (3.4 + gb * 1.6), thin = Math.max(drawR * 0.55, 0.4), rgb = s.color.join(',');
                ctx.save(); ctx.translate(s.x, s.y); ctx.rotate(s.angle);
                if (gb > 0.05) { ctx.beginPath(); ctx.fillStyle = 'rgba(' + rgb + ',' + (gb * 0.22) + ')'; ctx.arc(0, 0, arm * 1.4, 0, Math.PI * 2); ctx.fill(); }
                var gH = ctx.createLinearGradient(-arm, 0, arm, 0); gH.addColorStop(0, 'rgba(' + rgb + ',0)'); gH.addColorStop(0.5, 'rgba(' + rgb + ',' + alpha + ')'); gH.addColorStop(1, 'rgba(' + rgb + ',0)');
                ctx.fillStyle = gH; ctx.fillRect(-arm, -thin / 2, arm * 2, thin);
                var gV = ctx.createLinearGradient(0, -arm, 0, arm); gV.addColorStop(0, 'rgba(' + rgb + ',0)'); gV.addColorStop(0.5, 'rgba(' + rgb + ',' + alpha + ')'); gV.addColorStop(1, 'rgba(' + rgb + ',0)');
                ctx.fillStyle = gV; ctx.fillRect(-thin / 2, -arm, thin, arm * 2);
                ctx.beginPath(); ctx.fillStyle = 'rgba(255,255,255,' + Math.min(alpha * 1.2, 1) + ')'; ctx.arc(0, 0, drawR * 0.9, 0, Math.PI * 2); ctx.fill(); ctx.restore();
            }
            function drawStreak(s, drawR, alpha) {
                var len = drawR * 5, rgb = s.color.join(','); ctx.save(); ctx.translate(s.x, s.y); ctx.rotate(s.angle);
                var g = ctx.createLinearGradient(-len / 2, 0, len / 2, 0); g.addColorStop(0, 'rgba(' + rgb + ',0)'); g.addColorStop(0.5, 'rgba(' + rgb + ',' + alpha + ')'); g.addColorStop(1, 'rgba(' + rgb + ',0)');
                ctx.fillStyle = g; ctx.fillRect(-len / 2, -drawR * 0.4, len, drawR * 0.8); ctx.restore();
            }
            function drawConstellation(neighbors) {
                var range2 = CONSTELLATION_RANGE * CONSTELLATION_RANGE;
                for (var i = 0; i < neighbors.length; i++) { var a = neighbors[i]; for (var j = i + 1; j < neighbors.length; j++) { var b = neighbors[j], dx = a.x - b.x, dy = a.y - b.y, d2 = dx * dx + dy * dy; if (d2 < range2) { var t = 1 - d2 / range2; ctx.beginPath(); ctx.strokeStyle = 'rgba(91,156,255,' + (t * CONSTELLATION_ALPHA * a.proximity) + ')'; ctx.lineWidth = 0.6; ctx.moveTo(a.x, a.y); ctx.lineTo(b.x, b.y); ctx.stroke(); } } }
            }
            function tick() {
                if (!running) return;
                ctx.clearRect(0, 0, w, h);
                var R = MOUSE_RADIUS, R2 = R * R, neighbors = [];
                if (mouse.active) { var hub = ctx.createRadialGradient(mouse.x, mouse.y, 0, mouse.x, mouse.y, R * 0.9); hub.addColorStop(0, 'rgba(91,156,255,0.10)'); hub.addColorStop(0.4, 'rgba(43,123,255,0.05)'); hub.addColorStop(1, 'rgba(0,27,228,0)'); ctx.fillStyle = hub; ctx.fillRect(mouse.x - R, mouse.y - R, R * 2, R * 2); }
                for (var i = 0; i < stars.length; i++) {
                    var s = stars[i];
                    s.homeX += s.driftX; s.homeY += s.driftY;
                    if (s.homeX < 0) { s.homeX += w; s.x += w; } if (s.homeX > w) { s.homeX -= w; s.x -= w; }
                    if (s.homeY < 0) { s.homeY += h; s.y += h; } if (s.homeY > h) { s.homeY -= h; s.y -= h; }
                    s.vx += (s.homeX - s.x) * SPRING; s.vy += (s.homeY - s.y) * SPRING;
                    var proximity = 0;
                    if (mouse.active) { var dx = mouse.x - s.x, dy = mouse.y - s.y, d2 = dx * dx + dy * dy; if (d2 < R2) { var d = Math.sqrt(d2) || 0.0001; proximity = 1 - d / R; var f = proximity * proximity * MOUSE_FORCE * (0.6 + s.depth * 0.4); s.vx += (dx / d) * f; s.vy += (dy / d) * f; s.vx += (-dy / d) * f * 0.35; s.vy += (dx / d) * f * 0.35; } }
                    s.proximity = proximity; s.vx *= DAMPING; s.vy *= DAMPING; s.x += s.vx; s.y += s.vy;
                    s.phase += s.speed; var tw = Math.sin(s.phase) * s.twinkleAmp;
                    var alpha = Math.max(0.05, Math.min(s.baseAlpha + tw + proximity * 0.6, 1)), drawR = s.r * (1 + proximity * 1.6);
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