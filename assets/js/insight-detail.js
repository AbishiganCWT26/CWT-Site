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

});