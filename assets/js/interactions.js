/* Progressive enhancements shared by the public pages. */
document.addEventListener('DOMContentLoaded', () => {
    const motion = window.matchMedia('(prefers-reduced-motion: reduce)');
    const finePointer = window.matchMedia('(hover: hover) and (pointer: fine)');
    const activeAnimations = new Set();

    // Native adaptation of the floating, spring-driven Fluid Tabs indicator.
    document.querySelectorAll('.cwt-nav, .cwt-mobile').forEach(container => {
        const links = Array.from(container.querySelectorAll('.cwt-nav-link, .cwt-mobile-link'));
        if (!links.length) return;
        let current = null;
        let target = null;
        let indicatorFrame = 0;
        let lastTime = 0;
        const velocity = { left: 0, right: 0, top: 0, bottom: 0 };
        let selected = null;
        let hoveredLink = null;
        container.classList.add('cwt-fluid-ready');
        function paint() {
            container.style.setProperty('--fluid-x', `${current.left}px`);
            container.style.setProperty('--fluid-y', `${current.top}px`);
            container.style.setProperty('--fluid-width', `${Math.max(0, current.right - current.left)}px`);
            container.style.setProperty('--fluid-height', `${Math.max(0, current.bottom - current.top)}px`);
        }
        function tick(time) {
            const dt = Math.min((time - lastTime) / 1000 || 1 / 60, 1 / 30);
            lastTime = time;
            let moving = false;
            Object.keys(velocity).forEach(edge => {
                const distance = target[edge] - current[edge];
                const spring = edge === 'left' || edge === 'top' ? 240 : 185;
                velocity[edge] += (distance * spring - velocity[edge] * 25) * dt;
                current[edge] += velocity[edge] * dt;
                if (Math.abs(distance) > .15 || Math.abs(velocity[edge]) > .15) moving = true;
            });
            paint();
            if (moving && !motion.matches) indicatorFrame = requestAnimationFrame(tick);
            else { current = { ...target }; paint(); indicatorFrame = 0; }
        }
        function moveTo(link, immediate = false) {
            selected = link;
            links.forEach(item => item.classList.toggle('is-fluid-preview', item === link));
            container.classList.toggle('cwt-fluid-visible', !!link);
            if (!link) {
                cancelAnimationFrame(indicatorFrame);
                indicatorFrame = 0;
                Object.keys(velocity).forEach(edge => velocity[edge] = 0);
                return;
            }
            if (!container.clientWidth || !container.clientHeight) return;
            const bounds = container.getBoundingClientRect();
            const rect = link.getBoundingClientRect();
            target = {
                left: rect.left - bounds.left - container.clientLeft,
                right: rect.right - bounds.left - container.clientLeft,
                top: rect.top - bounds.top - container.clientTop,
                bottom: rect.bottom - bounds.top - container.clientTop
            };
            container.classList.add('cwt-fluid-ready');
            if (!current || immediate || motion.matches) {
                cancelAnimationFrame(indicatorFrame);
                indicatorFrame = 0;
                Object.keys(velocity).forEach(edge => velocity[edge] = 0);
                current = { ...target };
                paint();
            } else if (!indicatorFrame) {
                lastTime = performance.now();
                indicatorFrame = requestAnimationFrame(tick);
            }
        }
        function restore() {
            const focused = links.find(link => link === document.activeElement && link.matches(':focus-visible'));
            moveTo(hoveredLink || focused || null);
        }
        links.forEach(link => {
            link.addEventListener('pointerenter', event => {
                if (event.pointerType !== 'touch') { hoveredLink = link; moveTo(link); }
            });
            link.addEventListener('pointerleave', () => {
                hoveredLink = null;
                restore();
            });
            link.addEventListener('focus', restore);
        });
        container.addEventListener('pointerleave', () => { hoveredLink = null; restore(); });
        container.addEventListener('focusout', () => queueMicrotask(restore));
        const resize = () => moveTo(selected, true);
        if ('ResizeObserver' in window) new ResizeObserver(resize).observe(container);
        else window.addEventListener('resize', resize, { passive: true });
        document.fonts?.ready.then(resize);
        motion.addEventListener('change', resize);
        moveTo(null, true);
    });

    function animate(element, frames, options = {}) {
        if (motion.matches || !element.animate) return;
        const animation = element.animate(frames, {
            duration: 600, easing: 'cubic-bezier(.22,1,.36,1)', ...options
        });
        activeAnimations.add(animation);
        const clear = () => activeAnimations.delete(animation);
        animation.addEventListener('finish', clear, { once: true });
        animation.addEventListener('cancel', clear, { once: true });
    }

    // Give the typography graphic its own stagger instead of fading the entire heading.
    function animateTypography(heading) {
        const lines = heading.querySelectorAll(
            '.typegraphic-kicker, .typegraphic-built, .typegraphic-bridge, .typegraphic-ambition'
        );
        lines.forEach((line, index) => animate(line, [
            { opacity: 0, translate: '0 18px' },
            { opacity: 1, translate: '0 0' }
        ], { duration: 750, delay: index * 140, fill: 'backwards' }));
        const spark = heading.querySelector('.typegraphic-spark');
        if (spark) animate(spark, [
            { opacity: 0, scale: 0.65 },
            { opacity: 1, scale: 1 }
        ], { duration: 900, delay: 180, fill: 'backwards' });
        heading.querySelectorAll('.typegraphic-underline path').forEach(path => {
            const length = path.getTotalLength();
            animate(path, [
                { strokeDasharray: String(length), strokeDashoffset: String(length) },
                { strokeDasharray: String(length), strokeDashoffset: '0' }
            ], { duration: 1000, delay: 550, fill: 'backwards' });
        });
        heading.classList.add('is-type-visible');
    }

    // Animate once when content enters the viewport; content stays visible without JS.
    const targets = document.querySelectorAll(
        '.hero-copy > *, .column-art, .home-section-heading, .home-service-card, ' +
        '.partnership-layout > div:first-child, .partnership-list article, .home-cta, ' +
        '.home-client-logos > div, main [class$="-hero-title"], main [class$="-hero-text"]'
    );
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                if (entry.target.matches('.hero-typegraphic')) {
                    animateTypography(entry.target);
                    observer.unobserve(entry.target);
                    return;
                }
                const siblings = Array.from(entry.target.parentElement.children);
                const delay = Math.min(siblings.indexOf(entry.target) * 65, 260);
                animate(entry.target, [
                    { opacity: 0, translate: '0 20px' },
                    { opacity: 1, translate: '0 0' }
                ], { delay, fill: 'backwards' });
                observer.unobserve(entry.target);
            });
        }, { threshold: 0.12 });
        targets.forEach(target => observer.observe(target));
    } else {
        const heading = document.querySelector('.hero-typegraphic');
        if (heading) animateTypography(heading);
    }

    let serviceSlider = null;

    // Filter the homepage's services without navigating away.
    const filterBar = document.querySelector('.home-service-filters');
    if (filterBar) {
        filterBar.hidden = false;
        const buttons = filterBar.querySelectorAll('button');
        const cards = document.querySelectorAll('.home-service-card');
        const status = document.getElementById('service-filter-status');
        buttons.forEach(button => button.addEventListener('click', () => {
            buttons.forEach(item => item.setAttribute('aria-pressed', String(item === button)));
            let count = 0;
            cards.forEach(card => {
                const show = button.dataset.filter === 'all' || card.dataset.category === button.dataset.filter;
                card.getAnimations().forEach(animation => animation.cancel());
                card.hidden = !show;
                if (show) {
                    animate(card, [{ opacity: 0, translate: '0 12px' }, { opacity: 1, translate: '0 0' }],
                        { duration: 350, delay: count * 50, fill: 'backwards' });
                    count++;
                }
            });
            status.textContent = `${count} services shown: ${button.textContent.trim()}`;
            serviceSlider?.refresh();
        }));
    }

    // Native horizontal scrolling supports touch, trackpads and keyboard navigation.
    const track = document.querySelector('.home-service-slider');
    const sliderControls = document.querySelector('.home-slider-controls');
    if (track && sliderControls) {
        const dots = sliderControls.querySelector('.home-slider-dots');
        let stops = [];
        let cards = [];
        let frame = 0;
        let autoplayTimer = 0;
        let inView = !('IntersectionObserver' in window);
        let touching = false;
        const hovered = new Set();
        sliderControls.hidden = false;

        function stopAutoplay(freeze = false) {
            clearTimeout(autoplayTimer);
            autoplayTimer = 0;
            if (freeze) track.scrollTo({ left: track.scrollLeft, behavior: 'instant' });
        }
        function scheduleAutoplay() {
            stopAutoplay();
            // Mouse clicks leave focus behind; only keyboard focus should pause autoplay.
            const focused = document.activeElement?.matches(':focus-visible') &&
                (track.contains(document.activeElement) || sliderControls.contains(document.activeElement));
            if (touching || hovered.size || focused || !inView || document.hidden || stops.length < 2) return;
            autoplayTimer = window.setTimeout(() => {
                autoplayTimer = 0;
                if (track.scrollLeft >= track.scrollWidth - track.clientWidth - 3) goTo(0);
                else slide(1);
                scheduleAutoplay();
            }, 2000);
        }
        function update() {
            frame = 0;
            let active = 0;
            stops.forEach((stop, index) => {
                if (Math.abs(stop.left - track.scrollLeft) < Math.abs(stops[active].left - track.scrollLeft)) active = index;
            });
            Array.from(dots.children).forEach((dot, index) => {
                dot.setAttribute('aria-pressed', String(index === active));
            });
        }
        function scheduleUpdate() {
            if (!frame) frame = requestAnimationFrame(update);
        }
        function goTo(index) {
            if (!stops.length) return;
            track.scrollTo({
                left: stops[Math.max(0, Math.min(index, stops.length - 1))].left,
                behavior: motion.matches ? 'auto' : 'smooth'
            });
        }
        function slide(direction) {
            if (direction > 0) {
                const index = stops.findIndex(stop => stop.left > track.scrollLeft + 3);
                if (index !== -1) goTo(index);
            } else {
                let index = stops.length - 1;
                while (index > 0 && stops[index].left >= track.scrollLeft - 3) index--;
                goTo(index);
            }
        }
        function buildStops() {
            cards = Array.from(track.querySelectorAll('.home-service-card:not([hidden])'));
            const max = Math.max(0, track.scrollWidth - track.clientWidth);
            const bounds = track.getBoundingClientRect();
            stops = [];
            cards.forEach(card => {
                const left = Math.max(0, Math.min(max, card.getBoundingClientRect().left - bounds.left + track.scrollLeft));
                if (!stops.some(stop => Math.abs(stop.left - left) < 2)) stops.push({ left, card });
            });
            dots.replaceChildren();
            if (max > 2) stops.forEach((stop, index) => {
                const dot = document.createElement('button');
                dot.type = 'button';
                dot.setAttribute('aria-label', `Show services starting with ${stop.card.querySelector('h3').textContent}`);
                dot.setAttribute('aria-controls', track.id);
                dot.addEventListener('click', () => goTo(index));
                dots.append(dot);
            });
            update();
            scheduleAutoplay();
        }
        serviceSlider = {
            refresh() {
                track.scrollLeft = 0;
                buildStops();
            }
        };
        track.addEventListener('scroll', () => {
            scheduleUpdate();
        }, { passive: true });
        [track, sliderControls].forEach(element => {
            element.addEventListener('pointerenter', event => {
                if (event.pointerType === 'touch') return;
                hovered.add(element);
                stopAutoplay(true);
            });
            element.addEventListener('pointerleave', () => {
                hovered.delete(element);
                scheduleAutoplay();
            });
            element.addEventListener('focusin', () => {
                if (document.activeElement?.matches(':focus-visible')) stopAutoplay(true);
            });
            element.addEventListener('focusout', () => queueMicrotask(scheduleAutoplay));
        });
        track.addEventListener('pointerdown', () => {
            touching = true;
            stopAutoplay(true);
        }, { passive: true });
        const releasePointer = () => {
            touching = false;
            scheduleAutoplay();
        };
        window.addEventListener('pointerup', releasePointer, { passive: true });
        window.addEventListener('pointercancel', releasePointer, { passive: true });
        document.addEventListener('visibilitychange', scheduleAutoplay);
        motion.addEventListener('change', () => {
            stopAutoplay(true);
            scheduleAutoplay();
        });
        if ('IntersectionObserver' in window) {
            new IntersectionObserver(entries => {
                inView = entries[0].isIntersecting;
                scheduleAutoplay();
            }, { threshold: 0.15 }).observe(track);
        }
        track.addEventListener('keydown', event => {
            if (event.target !== track) return;
            if (event.key === 'ArrowRight' || event.key === 'ArrowLeft') {
                event.preventDefault();
                slide(event.key === 'ArrowRight' ? 1 : -1);
            } else if (event.key === 'Home' || event.key === 'End') {
                event.preventDefault();
                goTo(event.key === 'Home' ? 0 : stops.length - 1);
            }
        });
        if ('ResizeObserver' in window) new ResizeObserver(buildStops).observe(track);
        else window.addEventListener('resize', buildStops, { passive: true });
        buildStops();
    }

    // The blue illustration gently follows the pointer on desktop.
    const art = document.querySelector('.column-art');
    const stack = art?.querySelector('.column-art-content');
    let pointerFrame = 0;
    let latestX = 0;
    let latestY = 0;
    function resetArt() {
        cancelAnimationFrame(pointerFrame);
        pointerFrame = 0;
        if (stack) stack.style.translate = '0 0';
    }
    if (art && stack) {
        art.addEventListener('pointermove', event => {
            if (motion.matches || !finePointer.matches) return;
            const rect = art.getBoundingClientRect();
            latestX = ((event.clientX - rect.left) / rect.width - 0.5) * 16;
            latestY = ((event.clientY - rect.top) / rect.height - 0.5) * 16;
            if (!pointerFrame) pointerFrame = requestAnimationFrame(() => {
                stack.style.translate = `${latestX}px ${latestY}px`;
                pointerFrame = 0;
            });
        }, { passive: true });
        art.addEventListener('pointerleave', resetArt);
    }

    // A lightweight spotlight follows the cursor across service cards.
    document.querySelectorAll('.home-service-card, .svc-hub-card').forEach(card => {
        let frame = 0;
        let x = 0;
        let y = 0;
        card.addEventListener('pointermove', event => {
            if (motion.matches || !finePointer.matches) return;
            const rect = card.getBoundingClientRect();
            x = event.clientX - rect.left;
            y = event.clientY - rect.top;
            if (!frame) frame = requestAnimationFrame(() => {
                card.style.setProperty('--spot-x', `${x}px`);
                card.style.setProperty('--spot-y', `${y}px`);
                frame = 0;
            });
        }, { passive: true });
        card.addEventListener('pointerleave', () => {
            cancelAnimationFrame(frame);
            frame = 0;
        });
    });

    // Count up real company statistics once, preserving the original accessible text.
    const counters = [];
    document.querySelectorAll('.proof-layout > div > strong').forEach(element => {
        const original = element.textContent.trim();
        const match = original.match(/^(\d+)(\+)$/);
        if (!match) return;
        element.setAttribute('aria-label', original);
        const visual = document.createElement('span');
        visual.setAttribute('aria-hidden', 'true');
        visual.textContent = original;
        element.replaceChildren(visual);
        counters.push({ element, visual, original, target: Number(match[1]), frame: 0 });
    });
    if ('IntersectionObserver' in window) {
        const counterObserver = new IntersectionObserver(entries => entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            counterObserver.unobserve(entry.target);
            if (motion.matches) return;
            const counter = counters.find(item => item.element === entry.target);
            const start = performance.now();
            function tick(now) {
                const fraction = Math.min((now - start) / 1000, 1);
                counter.visual.textContent = `${Math.round(counter.target * (1 - (1 - fraction) ** 3))}+`;
                if (fraction < 1 && !motion.matches) counter.frame = requestAnimationFrame(tick);
                else { counter.visual.textContent = counter.original; counter.frame = 0; }
            }
            counter.frame = requestAnimationFrame(tick);
        }), { threshold: 0.8 });
        counters.forEach(counter => counterObserver.observe(counter.element));
    }
    motion.addEventListener('change', () => {
        if (!motion.matches) return;
        activeAnimations.forEach(animation => animation.cancel());
        resetArt();
        counters.forEach(counter => {
            cancelAnimationFrame(counter.frame);
            counter.visual.textContent = counter.original;
        });
    });
    finePointer.addEventListener('change', resetArt);

    // Image protection against contextmenu, dragging, and saving
    document.addEventListener('contextmenu', event => {
        if (event.target.tagName === 'IMG' || event.target.closest('img')) {
            event.preventDefault();
        }
    }, true);

    document.addEventListener('dragstart', event => {
        if (event.target.tagName === 'IMG' || event.target.closest('img')) {
            event.preventDefault();
        }
    }, true);

    document.querySelectorAll('img').forEach(img => {
        img.setAttribute('draggable', 'false');
    });
});

