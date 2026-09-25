document.addEventListener('DOMContentLoaded', function () {

    const navbar = document.querySelector('.navbar');
    if (navbar) {
        const onScroll = function () {
            navbar.classList.toggle('scrolled', window.scrollY > 60);
        };
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
    }

    const hamburger = document.querySelector('.hamburger');
    const mobileMenu = document.querySelector('.mobile-menu');
    if (hamburger && mobileMenu) {
        hamburger.addEventListener('click', function () {
            hamburger.classList.toggle('active');
            mobileMenu.classList.toggle('open');
        });

        mobileMenu.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                hamburger.classList.remove('active');
                mobileMenu.classList.remove('open');
            });
        });

        document.addEventListener('click', function (e) {
            if (!navbar.contains(e.target)) {
                hamburger.classList.remove('active');
                mobileMenu.classList.remove('open');
            }
        });
    }

    const currentPath = window.location.pathname.split('/').pop() || 'index.php';
    document.querySelectorAll('.navbar-nav a, .mobile-menu a').forEach(function (link) {
        const href = link.getAttribute('href') || '';
        if (href && currentPath.includes(href.replace('.php', ''))) {
            link.classList.add('active');
        }
    });

    document.querySelectorAll('.marquee-wrapper').forEach(function (wrapper) {
        const track = wrapper.querySelector('.marquee-track');
        if (!track) return;

        wrapper.addEventListener('mouseenter', function () {
            track.style.animationPlayState = 'paused';
        });
        wrapper.addEventListener('mouseleave', function () {
            track.style.animationPlayState = 'running';
        });
    });

    function animateCounter(el) {
        const target = parseInt(el.dataset.target, 10);
        const suffix = el.dataset.suffix || '';
        const prefix = el.dataset.prefix || '';
        const duration = 1800;
        const start = performance.now();

        function update(time) {
            const elapsed = time - start;
            const progress = Math.min(elapsed / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            const current = Math.round(eased * target);
            el.textContent = prefix + current + suffix;
            if (progress < 1) requestAnimationFrame(update);
        }

        requestAnimationFrame(update);
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

    const counterObserver = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                counterObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    document.querySelectorAll('.count-up').forEach(function (el) {
        counterObserver.observe(el);
    });

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

    /* =========================================================
       HERO NEXUS FIELD
       Interactive starfield with cursor attraction, twinkle,
       constellation web, and a bright cursor hub.
       Runs only on the index hero canvas (#heroCanvas).
       ========================================================= */
    const heroCanvas = document.getElementById('heroCanvas');
    if (heroCanvas && heroCanvas.getContext) {
        (function () {
            const hero = heroCanvas.parentElement; // <section class="hero">
            const ctx = heroCanvas.getContext('2d');
            if (!ctx || !hero) return;

            /* ---------- Tuning ---------- */
            const DENSITY_DIVISOR = 1200;  // lower = more stars
            const MAX_STARS = 900;
            const MOUSE_RADIUS = 220;
            const MOUSE_FORCE = 2.2;
            const SPRING = 0.012;
            const DAMPING = 0.91;
            const CONSTELLATION_RANGE = 90;
            const CONSTELLATION_ALPHA = 0.16;

            const COLORS = {
                white: [255, 255, 255],
                paleBlue: [200, 220, 255],
                blue400: [91, 156, 255],
                blue500: [43, 123, 255],
                pink: [126, 160, 248]
            };

            let w = 0;
            let h = 0;
            let stars = [];
            let running = true;

            const mouse = { x: 0, y: 0, active: false };

            /* ---------- Setup / resize ---------- */
            function resize() {
                const dpr = window.devicePixelRatio || 1;
                const rect = hero.getBoundingClientRect();
                w = rect.width;
                h = rect.height;

                heroCanvas.width = Math.round(w * dpr);
                heroCanvas.height = Math.round(h * dpr);
                heroCanvas.style.width = w + 'px';
                heroCanvas.style.height = h + 'px';
                ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

                initStars();
            }

            function pickColor() {
                const roll = Math.random();
                if (roll < 0.55) return COLORS.white;
                if (roll < 0.75) return COLORS.paleBlue;
                if (roll < 0.88) return COLORS.blue400;
                if (roll < 0.96) return COLORS.pink;
                return COLORS.blue500;
            }

            function initStars() {
                const count = Math.min(
                    Math.round((w * h) / DENSITY_DIVISOR),
                    MAX_STARS
                );

                stars = new Array(count).fill(0).map(function () {
                    const x = Math.random() * w;
                    const y = Math.random() * h;

                    const depth = Math.random() < 0.55
                        ? 0
                        : (Math.random() < 0.7 ? 1 : 2);

                    const baseR =
                        depth === 0 ? Math.random() * 0.5 + 0.25 :
                            depth === 1 ? Math.random() * 0.9 + 0.4 :
                                Math.random() * 1.4 + 0.6;

                    const r = Math.random();
                    const shape = r < 0.78 ? 'dot'
                        : r < 0.94 ? 'sparkle'
                            : 'streak';

                    return {
                        x: x, y: y,
                        homeX: x, homeY: y,
                        vx: 0, vy: 0,
                        r: baseR,
                        depth: depth,
                        shape: shape,
                        color: pickColor(),
                        baseAlpha: (depth === 0 ? 0.20 : depth === 1 ? 0.35 : 0.55)
                            + Math.random() * 0.30,
                        phase: Math.random() * Math.PI * 2,
                        speed: Math.random() * 0.018 + 0.005,
                        twinkleAmp: Math.random() * 0.30 + 0.15,
                        driftX: (Math.random() - 0.5) * 0.04 * (depth + 1),
                        driftY: (Math.random() - 0.5) * 0.04 * (depth + 1),
                        angle: Math.random() * Math.PI * 2,
                        proximity: 0
                    };
                });
            }

            /* ---------- Pointer ---------- */
            function setPointer(clientX, clientY) {
                const rect = hero.getBoundingClientRect();
                mouse.x = clientX - rect.left;
                mouse.y = clientY - rect.top;
                mouse.active = true;
            }

            hero.addEventListener('mousemove', function (e) {
                setPointer(e.clientX, e.clientY);
            });

            hero.addEventListener('mouseleave', function () {
                mouse.active = false;
            });

            hero.addEventListener('touchstart', function (e) {
                const t = e.touches[0];
                if (t) setPointer(t.clientX, t.clientY);
            }, { passive: true });

            hero.addEventListener('touchmove', function (e) {
                const t = e.touches[0];
                if (t) setPointer(t.clientX, t.clientY);
            }, { passive: true });

            hero.addEventListener('touchend', function () {
                mouse.active = false;
            });

            /* ---------- Shape drawing ---------- */
            function drawDot(s, drawR, alpha) {
                ctx.beginPath();
                ctx.fillStyle = 'rgba(' + s.color.join(',') + ',' + alpha + ')';
                ctx.arc(s.x, s.y, drawR, 0, Math.PI * 2);
                ctx.fill();
            }

            function drawSparkle(s, drawR, alpha, glowBoost) {
                const arm = drawR * (3.4 + glowBoost * 1.6);
                const thin = Math.max(drawR * 0.55, 0.4);
                const rgb = s.color.join(',');

                ctx.save();
                ctx.translate(s.x, s.y);
                ctx.rotate(s.angle);

                if (glowBoost > 0.05) {
                    ctx.beginPath();
                    ctx.fillStyle = 'rgba(' + rgb + ',' + (glowBoost * 0.22) + ')';
                    ctx.arc(0, 0, arm * 1.4, 0, Math.PI * 2);
                    ctx.fill();
                }

                const gradH = ctx.createLinearGradient(-arm, 0, arm, 0);
                gradH.addColorStop(0, 'rgba(' + rgb + ',0)');
                gradH.addColorStop(0.5, 'rgba(' + rgb + ',' + alpha + ')');
                gradH.addColorStop(1, 'rgba(' + rgb + ',0)');
                ctx.fillStyle = gradH;
                ctx.fillRect(-arm, -thin / 2, arm * 2, thin);

                const gradV = ctx.createLinearGradient(0, -arm, 0, arm);
                gradV.addColorStop(0, 'rgba(' + rgb + ',0)');
                gradV.addColorStop(0.5, 'rgba(' + rgb + ',' + alpha + ')');
                gradV.addColorStop(1, 'rgba(' + rgb + ',0)');
                ctx.fillStyle = gradV;
                ctx.fillRect(-thin / 2, -arm, thin, arm * 2);

                ctx.beginPath();
                ctx.fillStyle = 'rgba(255,255,255,' + Math.min(alpha * 1.2, 1) + ')';
                ctx.arc(0, 0, drawR * 0.9, 0, Math.PI * 2);
                ctx.fill();

                ctx.restore();
            }

            function drawStreak(s, drawR, alpha) {
                const len = drawR * 5;
                const rgb = s.color.join(',');

                ctx.save();
                ctx.translate(s.x, s.y);
                ctx.rotate(s.angle);

                const grad = ctx.createLinearGradient(-len / 2, 0, len / 2, 0);
                grad.addColorStop(0, 'rgba(' + rgb + ',0)');
                grad.addColorStop(0.5, 'rgba(' + rgb + ',' + alpha + ')');
                grad.addColorStop(1, 'rgba(' + rgb + ',0)');
                ctx.fillStyle = grad;
                ctx.fillRect(-len / 2, -drawR * 0.4, len, drawR * 0.8);

                ctx.restore();
            }

            /* ---------- Constellation web (only near the cursor) ---------- */
            function drawConstellation(neighbors) {
                const range2 = CONSTELLATION_RANGE * CONSTELLATION_RANGE;

                for (let i = 0; i < neighbors.length; i++) {
                    const a = neighbors[i];
                    for (let j = i + 1; j < neighbors.length; j++) {
                        const b = neighbors[j];
                        const dx = a.x - b.x;
                        const dy = a.y - b.y;
                        const d2 = dx * dx + dy * dy;

                        if (d2 < range2) {
                            const t = 1 - d2 / range2;
                            ctx.beginPath();
                            ctx.strokeStyle = 'rgba(91,156,255,' +
                                (t * CONSTELLATION_ALPHA * a.proximity) + ')';
                            ctx.lineWidth = 0.6;
                            ctx.moveTo(a.x, a.y);
                            ctx.lineTo(b.x, b.y);
                            ctx.stroke();
                        }
                    }
                }
            }

            /* ---------- Animation loop ---------- */
            function tick() {
                if (!running) return;

                ctx.clearRect(0, 0, w, h);

                const R = MOUSE_RADIUS;
                const R2 = R * R;
                const neighbors = [];

                // Cursor hub glow
                if (mouse.active) {
                    const hubGrad = ctx.createRadialGradient(
                        mouse.x, mouse.y, 0,
                        mouse.x, mouse.y, R * 0.9
                    );
                    hubGrad.addColorStop(0, 'rgba(91,156,255,0.10)');
                    hubGrad.addColorStop(0.4, 'rgba(43,123,255,0.05)');
                    hubGrad.addColorStop(1, 'rgba(0,27,228,0)');
                    ctx.fillStyle = hubGrad;
                    ctx.fillRect(mouse.x - R, mouse.y - R, R * 2, R * 2);
                }

                for (let i = 0; i < stars.length; i++) {
                    const s = stars[i];

                    // Drift the anchor, wrapping across edges
                    s.homeX += s.driftX;
                    s.homeY += s.driftY;
                    if (s.homeX < 0) { s.homeX += w; s.x += w; }
                    if (s.homeX > w) { s.homeX -= w; s.x -= w; }
                    if (s.homeY < 0) { s.homeY += h; s.y += h; }
                    if (s.homeY > h) { s.homeY -= h; s.y -= h; }

                    // Spring back to anchor
                    s.vx += (s.homeX - s.x) * SPRING;
                    s.vy += (s.homeY - s.y) * SPRING;

                    // Cursor attraction + swirl
                    let proximity = 0;
                    if (mouse.active) {
                        const dx = mouse.x - s.x;
                        const dy = mouse.y - s.y;
                        const d2 = dx * dx + dy * dy;

                        if (d2 < R2) {
                            const d = Math.sqrt(d2) || 0.0001;
                            proximity = 1 - d / R;

                            const f = proximity * proximity * MOUSE_FORCE *
                                (0.6 + s.depth * 0.4);

                            s.vx += (dx / d) * f;
                            s.vy += (dy / d) * f;
                            // Perpendicular swirl
                            s.vx += (-dy / d) * f * 0.35;
                            s.vy += (dx / d) * f * 0.35;
                        }
                    }
                    s.proximity = proximity;

                    // Integrate
                    s.vx *= DAMPING;
                    s.vy *= DAMPING;
                    s.x += s.vx;
                    s.y += s.vy;

                    // Twinkle
                    s.phase += s.speed;
                    const twinkle = Math.sin(s.phase) * s.twinkleAmp;
                    const alpha = Math.max(
                        0.05,
                        Math.min(s.baseAlpha + twinkle + proximity * 0.6, 1)
                    );
                    const drawR = s.r * (1 + proximity * 1.6);

                    // Draw by shape
                    if (s.shape === 'dot') {
                        drawDot(s, drawR, alpha);
                    } else if (s.shape === 'sparkle') {
                        drawSparkle(s, drawR, alpha, proximity);
                    } else {
                        drawStreak(s, drawR, alpha);
                    }

                    // Collect candidates for constellation web
                    if (mouse.active && proximity > 0.15) {
                        neighbors.push(s);
                    }
                }

                // Constellation web near cursor
                if (neighbors.length > 1) drawConstellation(neighbors);

                // Cursor nexus — layered bright core
                if (mouse.active) {
                    ctx.beginPath();
                    ctx.fillStyle = 'rgba(255,255,255,0.9)';
                    ctx.arc(mouse.x, mouse.y, 2.2, 0, Math.PI * 2);
                    ctx.fill();

                    ctx.beginPath();
                    ctx.fillStyle = 'rgba(91,156,255,0.35)';
                    ctx.arc(mouse.x, mouse.y, 6, 0, Math.PI * 2);
                    ctx.fill();

                    ctx.beginPath();
                    ctx.fillStyle = 'rgba(43,123,255,0.15)';
                    ctx.arc(mouse.x, mouse.y, 14, 0, Math.PI * 2);
                    ctx.fill();
                }

                requestAnimationFrame(tick);
            }

            /* ---------- Lifecycle ---------- */
            window.addEventListener('resize', resize);

            // Pause rendering when the hero scrolls off screen
            if ('IntersectionObserver' in window) {
                const heroObserver = new IntersectionObserver(function (entries) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting && !running) {
                            running = true;
                            requestAnimationFrame(tick);
                        } else if (!entry.isIntersecting) {
                            running = false;
                        }
                    });
                }, { threshold: 0 });

                heroObserver.observe(hero);
            }

            resize();
            requestAnimationFrame(tick);
        })();
    }

    /* =========================================================
       WHY CWT — mobile card scroller
       ========================================================= */
    const cardsWrapper = document.getElementById('cardsWrapper');
    if (cardsWrapper) {
        const whyCards = Array.from(cardsWrapper.querySelectorAll('.card'));
        const whyDots = Array.from(document.querySelectorAll('#dots .dot'));
        let whyRaf = null;
        let whyAutoPlayTimer = null;
        let isUserInteractingWhy = false;
        let activeWhyIndex = 0;

        function isMobileWhy() {
            return window.innerWidth < 768;
        }

        function updateActiveWhyCard() {
            if (!isMobileWhy()) return;

            const wrapperRect = cardsWrapper.getBoundingClientRect();
            const center = wrapperRect.left + wrapperRect.width / 2;
            let closest = 0;
            let min = Infinity;

            whyCards.forEach((card, i) => {
                const rect = card.getBoundingClientRect();
                const distance = Math.abs((rect.left + rect.width / 2) - center);
                if (distance < min) {
                    min = distance;
                    closest = i;
                }
            });

            activeWhyIndex = closest;

            whyCards.forEach((card, i) => {
                card.classList.toggle('is-active', i === activeWhyIndex);
            });

            whyDots.forEach((dot, i) => {
                dot.classList.toggle('is-active', i === activeWhyIndex);
            });
        }

        function scrollToWhyCard(index) {
            if (!isMobileWhy()) return;
            const card = whyCards[index];
            if (!card) return;
            const wrapperRect = cardsWrapper.getBoundingClientRect();
            const cardRect = card.getBoundingClientRect();
            const scrollLeft = cardsWrapper.scrollLeft + (cardRect.left - wrapperRect.left) - (wrapperRect.width - cardRect.width) / 2;
            cardsWrapper.scrollTo({ left: scrollLeft, behavior: 'smooth' });
        }

        function nextWhyCard() {
            if (!isMobileWhy()) return;
            const next = (activeWhyIndex + 1) % whyCards.length;
            scrollToWhyCard(next);
        }

        function startWhyAutoPlay() {
            stopWhyAutoPlay();
            if (!isMobileWhy()) return;
            whyAutoPlayTimer = setInterval(() => {
                if (!isUserInteractingWhy) {
                    nextWhyCard();
                }
            }, 6000);
        }

        function stopWhyAutoPlay() {
            if (whyAutoPlayTimer) {
                clearInterval(whyAutoPlayTimer);
                whyAutoPlayTimer = null;
            }
        }

        cardsWrapper.addEventListener('scroll', () => {
            if (whyRaf) cancelAnimationFrame(whyRaf);
            whyRaf = requestAnimationFrame(updateActiveWhyCard);
        }, { passive: true });

        cardsWrapper.addEventListener('touchstart', () => {
            isUserInteractingWhy = true;
        }, { passive: true });

        cardsWrapper.addEventListener('touchend', () => {
            setTimeout(() => {
                isUserInteractingWhy = false;
            }, 3000);
        }, { passive: true });

        whyDots.forEach((dot, i) => {
            dot.addEventListener('click', () => {
                isUserInteractingWhy = true;
                scrollToWhyCard(i);
                setTimeout(() => {
                    isUserInteractingWhy = false;
                }, 3000);
            });
        });

        window.addEventListener('resize', () => {
            updateActiveWhyCard();
            if (isMobileWhy()) {
                startWhyAutoPlay();
            } else {
                stopWhyAutoPlay();
            }
        });

        updateActiveWhyCard();
        startWhyAutoPlay();
    }

});