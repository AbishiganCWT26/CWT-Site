/* Shared decorative artwork: luminous ribbons, constellations and pointer ripples. */
document.addEventListener('DOMContentLoaded', () => {
    const motion = matchMedia('(prefers-reduced-motion: reduce)');
    const pointer = matchMedia('(hover: hover) and (pointer: fine)');
    const surfaces = [];
    const selectors = '.home-cta, .cwt-footer, .prd-cta, .cs-cta, .ins-cta';

    document.querySelectorAll(selectors).forEach((element, surfaceIndex) => {
        // The current footer already has its own particle animation.
        if (element.querySelector('.cwt-footer-particles')) return;
        const canvas = document.createElement('canvas');
        canvas.className = 'cwt-navy-art';
        canvas.setAttribute('aria-hidden', 'true');
        const context = canvas.getContext('2d');
        if (!context) return;
        element.classList.add('cwt-art-surface');
        element.prepend(canvas);
        let width = 1, height = 1, frame = 0, last = 0, visible = false;
        let phase = 0, previousTime = 0;
        let cursor = { x: 0.72, y: 0.42, active: false };
        let eased = { x: 0.72, y: 0.42 };
        let ripples = [];
        const stars = Array.from({ length: 48 }, (_, index) => ({
            x: ((index * 137 + surfaceIndex * 31) % 997) / 997,
            y: ((index * 233 + surfaceIndex * 67) % 991) / 991,
            size: index % 5 === 0 ? 1.8 : 0.8,
            offset: index * 1.7
        }));
        const canAnimate = () => !motion.matches && pointer.matches && visible && !document.hidden;

        function draw() {
            context.clearRect(0, 0, width, height);
            const x = eased.x * width, y = eased.y * height;
            const radius = Math.min(300, Math.max(140, width * 0.2));
            const glow = context.createRadialGradient(x, y, 0, x, y, radius);
            glow.addColorStop(0, cursor.active ? 'rgba(42,155,255,.23)' : 'rgba(42,155,255,.1)');
            glow.addColorStop(0.45, 'rgba(41,105,255,.07)');
            glow.addColorStop(1, 'rgba(41,105,255,0)');
            context.fillStyle = glow;
            context.fillRect(0, 0, width, height);

            // Sweeping line artwork responds to the pointer without moving the content.
            const shiftX = (eased.x - 0.5) * 45;
            const shiftY = (eased.y - 0.5) * 40;
            for (let i = 0; i < 7; i++) {
                const drift = Math.sin(phase * 0.35 + i * 0.45) * 18;
                const gap = i * 17;
                const stroke = context.createLinearGradient(width * 0.15, height, width, 0);
                stroke.addColorStop(0, 'rgba(57,120,255,0)');
                stroke.addColorStop(0.4, 'rgba(71,150,255,.16)');
                stroke.addColorStop(0.72, 'rgba(114,225,255,.36)');
                stroke.addColorStop(1, 'rgba(79,135,255,0)');
                context.strokeStyle = stroke;
                context.lineWidth = i === 3 ? 1.5 : 0.8;
                context.beginPath();
                context.moveTo(width * 0.12 - gap, height + 70);
                context.bezierCurveTo(width * 0.52 + shiftX, height * 0.8 + gap,
                    width * 0.36 + shiftX, height * 0.02 + drift + shiftY,
                    width + 50, height * 0.22 + gap + drift);
                context.stroke();
            }

            const points = stars.map(star => {
                const baseX = star.x * width, baseY = star.y * height;
                const distance = Math.hypot(baseX - x, baseY - y);
                const attraction = cursor.active ? Math.max(0, 1 - distance / 180) * 0.08 : 0;
                return { x: baseX + Math.sin(phase * 0.3 + star.offset) * 5 + (x - baseX) * attraction,
                    y: baseY + Math.cos(phase * 0.25 + star.offset) * 5 + (y - baseY) * attraction,
                    near: distance < 180, star };
            });
            points.forEach((point, index) => {
                const opacity = 0.25 + (Math.sin(phase + point.star.offset) + 1) * 0.12;
                context.fillStyle = `rgba(152,210,255,${opacity})`;
                context.beginPath();
                context.arc(point.x, point.y, point.star.size, 0, Math.PI * 2);
                context.fill();
                if (!cursor.active || !point.near) return;
                for (let j = index + 1; j < points.length; j++) {
                    const next = points[j];
                    const distance = Math.hypot(point.x - next.x, point.y - next.y);
                    if (!next.near || distance > 105) continue;
                    context.strokeStyle = `rgba(115,205,255,${(1 - distance / 105) * 0.28})`;
                    context.lineWidth = 0.7;
                    context.beginPath();
                    context.moveTo(point.x, point.y);
                    context.lineTo(next.x, next.y);
                    context.stroke();
                }
            });

            ripples.forEach(ripple => {
                const age = phase - ripple.start;
                context.strokeStyle = `rgba(122,215,255,${Math.max(0, 0.4 * (1 - age / 1.1))})`;
                context.lineWidth = 1;
                context.beginPath();
                context.arc(ripple.x * width, ripple.y * height, 10 + age * 110, 0, Math.PI * 2);
                context.stroke();
            });
            ripples = ripples.filter(ripple => phase - ripple.start < 1.1);
        }

        function tick(time) {
            frame = 0;
            if (!canAnimate()) return;
            if (time - last >= 32) {
                phase += previousTime ? Math.min((time - previousTime) / 1000, 0.06) : 0;
                previousTime = time;
                last = time;
                eased.x += (cursor.x - eased.x) * 0.12;
                eased.y += (cursor.y - eased.y) * 0.12;
                draw();
            }
            frame = requestAnimationFrame(tick);
        }
        function synchronize() {
            cancelAnimationFrame(frame);
            frame = 0;
            previousTime = 0;
            if (!canAnimate()) {
                cursor.active = false;
                cursor.x = eased.x = 0.72;
                cursor.y = eased.y = 0.42;
                ripples = [];
            }
            draw();
            if (canAnimate()) frame = requestAnimationFrame(tick);
        }
        function resize() {
            width = Math.max(1, element.clientWidth);
            height = Math.max(1, element.clientHeight);
            const ratio = Math.min(window.devicePixelRatio || 1, 1.5);
            canvas.width = Math.round(width * ratio);
            canvas.height = Math.round(height * ratio);
            context.setTransform(ratio, 0, 0, ratio, 0, 0);
            synchronize();
        }
        function position(event) {
            const rect = element.getBoundingClientRect();
            return { x: Math.min(1, Math.max(0, (event.clientX - rect.left) / width)),
                y: Math.min(1, Math.max(0, (event.clientY - rect.top) / height)) };
        }
        element.addEventListener('pointermove', event => {
            if (!canAnimate()) return;
            cursor = { ...position(event), active: true };
        }, { passive: true });
        element.addEventListener('pointerleave', () => {
            cursor = { x: 0.72, y: 0.42, active: false };
        });
        element.addEventListener('pointerdown', event => {
            if (!canAnimate()) return;
            ripples = [...ripples.slice(-3), { ...position(event), start: phase }];
        }, { passive: true });
        if ('ResizeObserver' in window) new ResizeObserver(resize).observe(element);
        else window.addEventListener('resize', resize, { passive: true });
        if ('IntersectionObserver' in window) {
            new IntersectionObserver(entries => {
                visible = entries[0].isIntersecting;
                synchronize();
            }).observe(element);
        } else visible = true;
        surfaces.push(synchronize);
        resize();
    });
    const synchronizeAll = () => surfaces.forEach(synchronize => synchronize());
    document.addEventListener('visibilitychange', synchronizeAll);
    motion.addEventListener('change', synchronizeAll);
    pointer.addEventListener('change', synchronizeAll);
});
