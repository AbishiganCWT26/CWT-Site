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

    const progressBar = document.getElementById('ppProgressBar');
    if (progressBar) {
        let rafId = 0;

        const updateProgress = function () {
            const scrollTop = window.scrollY || window.pageYOffset;
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            const pct = docHeight > 0 ? Math.min(100, Math.max(0, (scrollTop / docHeight) * 100)) : 0;
            progressBar.style.width = pct.toFixed(2) + '%';
        };

        const onScroll = function () {
            if (rafId) return;
            rafId = requestAnimationFrame(function () {
                updateProgress();
                rafId = 0;
            });
        };

        updateProgress();
        window.addEventListener('scroll', onScroll, { passive: true });
        window.addEventListener('resize', onScroll, { passive: true });
    }

    const tocLinks = Array.prototype.slice.call(document.querySelectorAll('.pp-toc-link'));
    const sections = tocLinks
        .map(function (link) {
            const id = link.dataset.target;
            return id ? document.getElementById(id) : null;
        })
        .filter(Boolean);

    if (tocLinks.length && sections.length) {
        const setActive = function (id) {
            tocLinks.forEach(function (link) {
                link.classList.toggle('is-active', link.dataset.target === id);
            });
        };

        const onScroll = function () {
            const scrollPos = window.scrollY + (window.innerHeight * 0.28);
            let currentId = sections[0].id;

            sections.forEach(function (section) {
                if (section.offsetTop <= scrollPos) {
                    currentId = section.id;
                }
            });

            setActive(currentId);
        };

        let rafId = 0;
        const scheduleScroll = function () {
            if (rafId) return;
            rafId = requestAnimationFrame(function () {
                onScroll();
                rafId = 0;
            });
        };

        window.addEventListener('scroll', scheduleScroll, { passive: true });
        window.addEventListener('resize', scheduleScroll, { passive: true });
        onScroll();

        tocLinks.forEach(function (link) {
            link.addEventListener('click', function (e) {
                const id = link.dataset.target;
                const target = id ? document.getElementById(id) : null;
                if (!target) return;

                e.preventDefault();

                const top = target.getBoundingClientRect().top + window.scrollY - 100;
                window.scrollTo({ top: top, behavior: 'smooth' });

                setActive(id);
            });
        });
    }

    const content = document.getElementById('ppContent');
    const readTime = document.getElementById('ppReadTime');
    if (content && readTime) {
        const text = (content.textContent || '').trim();
        const words = text.split(/\s+/).filter(Boolean).length;
        const minutes = Math.max(1, Math.ceil(words / 220));
        readTime.textContent = '~' + minutes + ' min read';
    }

    const topBtn = document.getElementById('ppTop');
    if (topBtn) {
        const toggleTop = function () {
            topBtn.classList.toggle('is-visible', window.scrollY > 400);
        };

        toggleTop();
        window.addEventListener('scroll', toggleTop, { passive: true });

        topBtn.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

});