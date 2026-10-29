document.addEventListener('DOMContentLoaded', function () {

    const travelMap = document.querySelector('.prd-travel-map');
    const travelHighlight = document.querySelector('.prd-travel-highlight');
    const mapSlot = document.querySelector('.prd-client-map-slot');
    const clientsSection = document.getElementById('our-clients');
    const pageMain = document.getElementById('main-content');
    const mapMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    if (travelMap && travelHighlight && mapSlot && clientsSection && pageMain) {
        let mapGeometry = null;
        let mapAnimation = null;
        let highlightAnimation = null;
        let mapArrived = false;
        function measureMap() {
            if (mapMotion.matches) return;
            const mainRect = pageMain.getBoundingClientRect();
            const slotRect = mapSlot.getBoundingClientRect();
            const viewportWidth = document.documentElement.clientWidth;
            const startWidth = Math.min(780, viewportWidth * (viewportWidth <= 540 ? .65 : .9));
            mapGeometry = {
                startWidth,
                startX: mainRect.width - viewportWidth * .04 - startWidth,
                endX: slotRect.left - mainRect.left,
                endY: slotRect.top - mainRect.top,
                endWidth: slotRect.width
            };
            travelMap.style.width = `${startWidth}px`;
            travelHighlight.style.width = `${startWidth}px`;
            placeMap();
        }
        function placeMap(animate = false) {
            if (!mapGeometry || mapMotion.matches) return;
            const g = mapGeometry;
            const previousTransform = getComputedStyle(travelMap).transform;
            const transform = mapArrived
                ? `translate(${g.endX}px, ${g.endY}px) scale(${g.endWidth / g.startWidth})`
                : `translate(${g.startX}px, 104px) scale(1)`;
            document.body.classList.remove('prd-countries-visible');
            mapAnimation?.cancel();
            highlightAnimation?.cancel();
            travelMap.style.transform = transform;
            travelHighlight.style.transform = transform;
            if (animate && travelMap.animate) {
                const frames = [{ transform: previousTransform }, { transform }];
                const timing = { duration: 1600, easing: 'cubic-bezier(.22,1,.36,1)' };
                mapAnimation = travelMap.animate(frames, timing);
                highlightAnimation = travelHighlight.animate(frames, timing);
                const currentAnimation = mapAnimation;
                currentAnimation.onfinish = () => {
                    if (mapAnimation === currentAnimation && mapArrived) {
                        document.body.classList.add('prd-countries-visible');
                    }
                };
            } else {
                document.body.classList.toggle('prd-countries-visible', mapArrived);
            }
        }
        function syncMapMotion() {
            document.body.classList.toggle('prd-map-enhanced', !mapMotion.matches);
            if (mapMotion.matches) {
                mapAnimation?.cancel();
                highlightAnimation?.cancel();
                mapSlot.append(travelMap, travelHighlight);
                travelMap.style.width = '';
                travelMap.style.transform = '';
                travelHighlight.style.width = '';
                travelHighlight.style.transform = '';
            } else {
                pageMain.prepend(travelMap, travelHighlight);
                measureMap();
            }
        }
        window.addEventListener('resize', measureMap, { passive: true });
        if ('ResizeObserver' in window) new ResizeObserver(measureMap).observe(mapSlot);
        document.fonts?.ready.then(measureMap);
        mapMotion.addEventListener('change', syncMapMotion);
        syncMapMotion();
        if ('IntersectionObserver' in window) {
            const arrivalObserver = new IntersectionObserver(entries => {
                const entry = entries[0];
                if (entry.isIntersecting && entry.intersectionRatio >= .15) {
                    if (!mapArrived) {
                        mapArrived = true;
                        placeMap(true);
                    }
                } else if (mapArrived && entry.boundingClientRect.top > (entry.rootBounds?.top ?? 0)) {
                    mapArrived = false;
                    placeMap(true);
                }
            }, { threshold: [0, .15], rootMargin: '0px 0px -20% 0px' });
            arrivalObserver.observe(mapSlot);
        } else {
            mapArrived = true;
            placeMap();
        }
    }

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
                for (let i = 0; i < neighbors.length; i++) {
                    const a = neighbors[i];
                    for (let j = i + 1; j < neighbors.length; j++) {
                        const b = neighbors[j], dx = a.x - b.x, dy = a.y - b.y, d2 = dx * dx + dy * dy;
                        if (d2 < range2) {
                            const t = 1 - d2 / range2;
                            ctx.beginPath();
                            ctx.strokeStyle = 'rgba(91,156,255,' + (t * CONSTELLATION_ALPHA * a.proximity) + ')';
                            ctx.lineWidth = 0.6;
                            ctx.moveTo(a.x, a.y);
                            ctx.lineTo(b.x, b.y);
                            ctx.stroke();
                        }
                    }
                }
            }
            function tick() {
                if (!running) return;
                ctx.clearRect(0, 0, w, h);
                const R = MOUSE_RADIUS, R2 = R * R, neighbors = [];
                if (mouse.active) {
                    const hub = ctx.createRadialGradient(mouse.x, mouse.y, 0, mouse.x, mouse.y, R * 0.9);
                    hub.addColorStop(0, 'rgba(91,156,255,0.10)');
                    hub.addColorStop(0.4, 'rgba(43,123,255,0.05)');
                    hub.addColorStop(1, 'rgba(0,27,228,0)');
                    ctx.fillStyle = hub;
                    ctx.fillRect(mouse.x - R, mouse.y - R, R * 2, R * 2);
                }
                for (let i = 0; i < stars.length; i++) {
                    const s = stars[i];
                    s.homeX += s.driftX; s.homeY += s.driftY;
                    if (s.homeX < 0) { s.homeX += w; s.x += w; }
                    if (s.homeX > w) { s.homeX -= w; s.x -= w; }
                    if (s.homeY < 0) { s.homeY += h; s.y += h; }
                    if (s.homeY > h) { s.homeY -= h; s.y -= h; }
                    s.vx += (s.homeX - s.x) * SPRING;
                    s.vy += (s.homeY - s.y) * SPRING;
                    let proximity = 0;
                    if (mouse.active) {
                        const dx = mouse.x - s.x, dy = mouse.y - s.y, d2 = dx * dx + dy * dy;
                        if (d2 < R2) {
                            const d = Math.sqrt(d2) || 0.0001;
                            proximity = 1 - d / R;
                            const f = proximity * proximity * MOUSE_FORCE * (0.6 + s.depth * 0.4);
                            s.vx += (dx / d) * f; s.vy += (dy / d) * f;
                            s.vx += (-dy / d) * f * 0.35; s.vy += (dx / d) * f * 0.35;
                        }
                    }
                    s.proximity = proximity;
                    s.vx *= DAMPING; s.vy *= DAMPING;
                    s.x += s.vx; s.y += s.vy;
                    s.phase += s.speed;
                    const tw = Math.sin(s.phase) * s.twinkleAmp;
                    const alpha = Math.max(0.05, Math.min(s.baseAlpha + tw + proximity * 0.6, 1));
                    const drawR = s.r * (1 + proximity * 1.6);
                    if (s.shape === 'dot') drawDot(s, drawR, alpha);
                    else if (s.shape === 'sparkle') drawSparkle(s, drawR, alpha, proximity);
                    else drawStreak(s, drawR, alpha);
                    if (mouse.active && proximity > 0.15) neighbors.push(s);
                }
                if (neighbors.length > 1) drawConstellation(neighbors);
                if (mouse.active) {
                    ctx.beginPath(); ctx.fillStyle = 'rgba(255,255,255,0.9)'; ctx.arc(mouse.x, mouse.y, 2.2, 0, Math.PI * 2); ctx.fill();
                    ctx.beginPath(); ctx.fillStyle = 'rgba(91,156,255,0.35)'; ctx.arc(mouse.x, mouse.y, 6, 0, Math.PI * 2); ctx.fill();
                    ctx.beginPath(); ctx.fillStyle = 'rgba(43,123,255,0.15)'; ctx.arc(mouse.x, mouse.y, 14, 0, Math.PI * 2); ctx.fill();
                }
                requestAnimationFrame(tick);
            }
            window.addEventListener('resize', resize);
            if ('IntersectionObserver' in window) {
                new IntersectionObserver(function (entries) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting && !running) { running = true; requestAnimationFrame(tick); }
                        else if (!entry.isIntersecting) { running = false; }
                    });
                }, { threshold: 0 }).observe(hero);
            }
            resize();
            requestAnimationFrame(tick);
        })();
    }

    const marqueeMedia = window.matchMedia('(max-width: 950px)');

    function setupMarquee(el) {
        if (!el || el.classList.contains('is-marquee')) return;
        const originals = Array.prototype.slice.call(el.children);
        if (!originals.length) return;

        const wrapper = document.createElement('div');
        wrapper.className = 'prd-marquee-wrap';
        el.parentNode.insertBefore(wrapper, el);
        wrapper.appendChild(el);

        el.classList.add('is-marquee', 'is-marquee-paused');

        function makeClone(node) {
            const clone = node.cloneNode(true);
            clone.classList.add('prd-marquee-clone');
            clone.setAttribute('aria-hidden', 'true');
            return clone;
        }

        originals.forEach(function (node) {
            el.appendChild(makeClone(node));
        });

        let copies = 2;
        while (copies < 12 && el.scrollWidth < wrapper.clientWidth * 2) {
            originals.forEach(function (node) {
                el.appendChild(makeClone(node));
            });
            originals.forEach(function (node) {
                el.appendChild(makeClone(node));
            });
            copies += 2;
        }

        el.classList.remove('is-marquee-paused');
    }

    function teardownMarquee(el) {
        if (!el || !el.classList.contains('is-marquee')) return;
        el.classList.remove('is-marquee');
        const clones = el.querySelectorAll('.prd-marquee-clone');
        for (let i = 0; i < clones.length; i++) clones[i].remove();
        const wrapper = el.parentNode;
        if (wrapper && wrapper.classList.contains('prd-marquee-wrap')) {
            wrapper.parentNode.insertBefore(el, wrapper);
            wrapper.remove();
        }
    }

    const logosEl = document.querySelector('.prd-client-logos');
    if (logosEl) setupMarquee(logosEl);

    const countriesEl = document.querySelector('.prd-client-countries');
    function syncCountriesMarquee() {
        if (!countriesEl) return;
        if (marqueeMedia.matches) setupMarquee(countriesEl);
        else teardownMarquee(countriesEl);
    }
    syncCountriesMarquee();
    if (marqueeMedia.addEventListener) {
        marqueeMedia.addEventListener('change', syncCountriesMarquee);
    } else if (marqueeMedia.addListener) {
        marqueeMedia.addListener(syncCountriesMarquee);
    }

});