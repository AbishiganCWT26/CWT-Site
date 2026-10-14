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

    document.querySelectorAll('.marquee-wrap').forEach(function (wrapper) {
        const track = wrapper.querySelector('.marquee-track');
        if (!track) return;

        wrapper.addEventListener('mouseenter', function () {
            track.style.animationPlayState = 'paused';
        });
        wrapper.addEventListener('mouseleave', function () {
            track.style.animationPlayState = 'running';
        });
    });

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


    /* =========================================================
       HERO NEXUS FIELD
       Interactive starfield with cursor attraction, twinkle,
       constellation web, and a bright cursor hub.
       Runs only on the hero canvas (#heroCanvas).
       ========================================================= */
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
                for (let i = 0; i < neighbors.length; i++) { const a = neighbors[i]; for (let j = i + 1; j < neighbors.length; j++) { const b = neighbors[j], dx = a.x - b.x, dy = a.y - b.y, d2 = dx * dx + dy * dy; if (d2 < range2) { const t = 1 - d2 / range2; ctx.beginPath(); ctx.strokeStyle = 'rgba(91,156,255,' + (t * CONSTELLATION_ALPHA * a.proximity) + ')'; ctx.lineWidth = 0.6; ctx.moveTo(a.x, a.y); ctx.lineTo(b.x, b.y); ctx.stroke(); } } }
            }
            function tick() {
                if (!running) return;
                ctx.clearRect(0, 0, w, h);
                const R = MOUSE_RADIUS, R2 = R * R, neighbors = [];
                if (mouse.active) { const hub = ctx.createRadialGradient(mouse.x, mouse.y, 0, mouse.x, mouse.y, R * 0.9); hub.addColorStop(0, 'rgba(91,156,255,0.10)'); hub.addColorStop(0.4, 'rgba(43,123,255,0.05)'); hub.addColorStop(1, 'rgba(0,27,228,0)'); ctx.fillStyle = hub; ctx.fillRect(mouse.x - R, mouse.y - R, R * 2, R * 2); }
                for (let i = 0; i < stars.length; i++) {
                    const s = stars[i];
                    s.homeX += s.driftX; s.homeY += s.driftY;
                    if (s.homeX < 0) { s.homeX += w; s.x += w; } if (s.homeX > w) { s.homeX -= w; s.x -= w; }
                    if (s.homeY < 0) { s.homeY += h; s.y += h; } if (s.homeY > h) { s.homeY -= h; s.y -= h; }
                    s.vx += (s.homeX - s.x) * SPRING; s.vy += (s.homeY - s.y) * SPRING;
                    let proximity = 0;
                    if (mouse.active) { const dx = mouse.x - s.x, dy = mouse.y - s.y, d2 = dx * dx + dy * dy; if (d2 < R2) { const d = Math.sqrt(d2) || 0.0001; proximity = 1 - d / R; const f = proximity * proximity * MOUSE_FORCE * (0.6 + s.depth * 0.4); s.vx += (dx / d) * f; s.vy += (dy / d) * f; s.vx += (-dy / d) * f * 0.35; s.vy += (dx / d) * f * 0.35; } }
                    s.proximity = proximity; s.vx *= DAMPING; s.vy *= DAMPING; s.x += s.vx; s.y += s.vy;
                    s.phase += s.speed; const tw = Math.sin(s.phase) * s.twinkleAmp;
                    const alpha = Math.max(0.05, Math.min(s.baseAlpha + tw + proximity * 0.6, 1)), drawR = s.r * (1 + proximity * 1.6);
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
    const cards = Array.prototype.slice.call(document.querySelectorAll('.orbit .card'));

    if (cards.length) {
        const prefersReduced =
            window.matchMedia &&
            window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (prefersReduced || !('IntersectionObserver' in window)) {
            cards.forEach(function (el) { el.classList.add('shown'); });
            return;
        }

        cards.forEach(function (el, i) {
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

        cards.forEach(function (el) { cardObserver.observe(el); });
    }

    /* =========================================================
   WHAT WE OFFER â€” capabilities explorer
   ========================================================= */
    (function initWhatWeOffer() {
        const tabsEl = document.getElementById('tabs');
        const cardsEl = document.getElementById('cards');
        const panelEl = document.getElementById('panel');
        const panelTitle = document.getElementById('panelTitle');
        const panelMeta = document.getElementById('panelMeta');
        const panelIconUse = document.getElementById('panelIconUse');
        const cardsDotsEl = document.getElementById('cardsDots');

        if (!tabsEl || !cardsEl || !panelEl) return;

        const SECTIONS = [
            {
                id: 'data',
                label: 'Data Engineering & Analytics',
                icon: 'database',
                accent: '#001be4',
                accent2: '#0015b0',
                soft: 'rgba(0, 27, 228, 0.16)',
                cards: [
                    { t: 'Data Architecture', i: 'grid', d: 'Defines the structural blueprint for how data is collected, stored, and accessed across the organization. It ensures scalability, security, and alignment with overarching business goals.' },
                    { t: 'Data Integration (ETL/ELT)', i: 'shuffle', d: 'Extracts, transforms, and loads data from disparate sources into a unified destination. It ensures timely, accurate, and seamless data availability for analysis.' },
                    { t: 'Centralized Data Warehouses', i: 'box', d: 'Stores structured, historical data optimized for high-performance querying and reporting. It acts as a single source of truth for enterprise business intelligence.' },
                    { t: 'Data Lakes & Lakehouse', i: 'layers', d: 'Provides flexible storage for raw, structured, and unstructured data at scale. It combines the elasticity of data lakes with the transactional reliability of warehouses.' },
                    { t: 'Data Governance & Quality', i: 'filter', d: 'Establishes policies, standards, and controls to ensure data accuracy, privacy, and compliance. It builds essential trust in the data used for decision-making.' },
                    { t: 'Analytics Enablement', i: 'chart', d: 'Provides the tools, training, and infrastructure for teams to derive insights from data. It bridges the gap between raw data and actionable intelligence.' },
                    { t: 'Custom Analytics Dashboards', i: 'monitor', d: 'Delivers visually intuitive, interactive interfaces tailored to specific business KPIs. It empowers stakeholders with real-time visibility into performance metrics.' }
                ]
            },
            {
                id: 'ai',
                label: 'AI Engineering & Intelligent Automation',
                icon: 'cpu',
                accent: '#1a66ff',
                accent2: '#0050b8',
                soft: 'rgba(26, 102, 255, 0.16)',
                cards: [
                    { t: 'Artificial Intelligence', i: 'cpu', d: 'Simulates human intelligence processes to perform tasks like reasoning, learning, and problem-solving. It drives innovation and competitive advantage across industries.' },
                    { t: 'Machine Learning Models', i: 'trending', d: 'Uses statistical algorithms to identify patterns and make predictions from data. These models continuously improve with experience to automate complex decisions.' },
                    { t: 'Generative AI & LLMs', i: 'star', d: 'Leverages large language models to create new content, code, or insights. It revolutionizes creativity, customer interaction, and knowledge retrieval.' },
                    { t: 'Agentic AI Workflows', i: 'branch', d: 'Deploys autonomous AI agents capable of executing multi-step tasks independently. It goes beyond simple automation by enabling goal-oriented decision-making.' },
                    { t: 'Intelligent Automation', i: 'robot', d: 'Combines AI and robotic process automation to handle cognitive and repetitive tasks. It significantly reduces manual effort and operational errors.' },
                    { t: 'AI Strategy & Research', i: 'compass', d: 'Defines the roadmap for AI adoption, identifying high-value use cases and ethical guidelines. It ensures AI investments align with long-term business objectives.' },
                    { t: 'Custom LLM Implementations', i: 'code', d: 'Fine-tunes or builds proprietary large language models on domain-specific data. It provides tailored AI capabilities that protect sensitive data and ensure relevance.' },
                    { t: 'Automated Business Process Orchestration', i: 'refresh', d: 'Coordinates complex workflows across multiple systems and teams automatically. It ensures seamless execution of end-to-end business operations.' }
                ]
            },
            {
                id: 'optimization',
                label: 'Optimization',
                icon: 'gear',
                accent: '#1557c4',
                accent2: '#0f3b8a',
                soft: 'rgba(21, 87, 196, 0.16)',
                cards: [
                    { t: 'Business Optimization', i: 'trending', d: 'Analyzes and redesigns business functions to maximize efficiency and value. It drives continuous improvement and competitive agility.' },
                    { t: 'Process Improvement', i: 'sliders', d: 'Systematically identifies and eliminates bottlenecks and waste in workflows. It enhances productivity and quality across the organization.' },
                    { t: 'Technology Optimization', i: 'gear', d: 'Evaluates and upgrades the technology stack to improve performance and reduce technical debt. It ensures the IT infrastructure supports current and future needs.' },
                    { t: 'Data Optimization (DPOC)', i: 'database', d: 'Focuses on streamlining data pipelines and storage to reduce costs and improve speed. It ensures the data ecosystem operates at peak efficiency.' },
                    { t: 'Cost & Resource Optimization', i: 'dollar', d: 'Aligns spending and resource allocation with strategic priorities. It maximizes ROI by eliminating redundancies and improving utilization.' },
                    { t: 'Performance Analytics', i: 'activity', d: 'Continuously monitors and analyzes key performance indicators (KPIs). It provides the insights needed to drive data-informed strategic adjustments.' },
                    { t: 'RFP proposal decks', i: 'file', d: 'Crafts compelling, customized presentations that clearly articulate value propositions. It wins business by addressing client needs with tailored solutions.' },
                    { t: 'go-to-market strategies', i: 'target', d: 'Develops comprehensive plans to launch products or services successfully. It defines target audiences, pricing, and channels to drive market adoption.' }
                ]
            },
            {
                id: 'software',
                label: 'Software Engineering & Development',
                icon: 'code',
                accent: '#0f3b8a',
                accent2: '#0d2a5c',
                soft: 'rgba(15, 59, 138, 0.16)',
                cards: [
                    { t: 'Enterprise Applications', i: 'layers', d: 'Builds complex, scalable software systems that support core business functions. They integrate with various departments to streamline enterprise-wide operations.' },
                    { t: 'Web & Mobile Solutions', i: 'smartphone', d: 'Develops responsive applications for browsers and mobile devices. It ensures seamless user experiences across all digital touchpoints.' },
                    { t: 'API & System Integration', i: 'link', d: 'Connects disparate software applications and data sources. It enables seamless data flow and functionality across the technology ecosystem.' },
                    { t: 'Workflow Automation', i: 'refresh', d: 'Automates routine business processes using software tools. It reduces manual intervention, speeds up task completion, and minimizes errors.' },
                    { t: 'Cloud-native Software Platforms', i: 'cloud', d: 'Designs applications specifically to leverage the scalability and resilience of the cloud. It enables rapid deployment and continuous innovation.' },
                    { t: 'DevOps', i: 'terminal', d: 'Fosters collaboration between development and operations teams. It accelerates software delivery through automation and continuous improvement.' },
                    { t: 'Custom API Integrations', i: 'code', d: 'Creates tailored connections between proprietary and third-party systems. It ensures unique business requirements are met without disrupting existing workflows.' },
                    { t: 'CI/CD Deployment Pipelines', i: 'branch', d: 'Automates the building, testing, and deployment of software. It ensures faster, more reliable releases with minimal manual intervention.' }
                ]
            },
            {
                id: 'cyber',
                label: 'Network, Infrastructure, Security, Partner Management, Support & Services',
                icon: 'shield',
                accent: '#2b7bff',
                accent2: '#1557c4',
                soft: 'rgba(43, 123, 255, 0.18)',
                cards: [
                    { t: 'Infrastructure & Cloud', i: 'cloud', d: 'Manages the physical and virtual resources required to run applications. It provides the scalable foundation for all digital operations.' },
                    { t: 'Networking & Connectivity', i: 'globe', d: 'Ensures reliable, high-speed communication between systems, users, and locations. It is the backbone of seamless digital interaction.' },
                    { t: 'Cyber Security', i: 'shield', d: 'Protects systems, networks, and data from digital attacks. It safeguards confidentiality, integrity, and availability of critical information.' },
                    { t: 'Support & Managed Services', i: 'wrench', d: 'Provides ongoing monitoring, maintenance, and troubleshooting for IT systems. It ensures optimal performance and minimizes downtime.' },
                    { t: 'Partner Management', i: 'users', d: 'Builds and maintains strategic relationships with technology vendors and service providers. It ensures access to specialized expertise and resources.' },
                    { t: 'Backup & Disaster Recovery Solutions', i: 'harddrive', d: 'Implements robust data backup and restoration protocols. It ensures business continuity in the event of system failures or disasters.' },
                    { t: 'Secure Hybrid Cloud Architectures', i: 'lock', d: 'Integrates on-premises infrastructure with public and private clouds securely. It offers flexibility, scalability, and enhanced data control.' },
                    { t: '24/7 System Monitoring', i: 'eye', d: 'Continuously tracks system health, performance, and security. It enables proactive issue resolution before problems impact users.' }
                ]
            },
            {
                id: 'pmo',
                label: 'Project Delivery & PMO',
                icon: 'briefcase',
                accent: '#5b9cff',
                accent2: '#2b7bff',
                soft: 'rgba(91, 156, 255, 0.20)',
                cards: [
                    { t: 'Project Management', i: 'briefcase', d: 'Applies methodologies to plan, execute, and close projects successfully. It ensures deliverables are completed on time, within budget, and to scope.' },
                    { t: 'Business Analysis', i: 'chart', d: 'Bridges the gap between business needs and technical solutions. It gathers requirements and defines solutions that deliver value.' },
                    { t: 'PMO & Governance', i: 'shield', d: 'Establishes standard project management frameworks and oversight. It ensures consistency, compliance, and alignment with strategic goals.' },
                    { t: 'Planning & Scheduling', i: 'clipboard', d: 'Develops detailed project timelines, milestones, and resource allocations. It provides a roadmap for execution and tracks progress.' },
                    { t: 'Risk & Issue Management', i: 'alert', d: 'Identifies potential threats and resolves existing problems proactively. It minimizes negative impacts on project timelines and outcomes.' },
                    { t: 'Quality Assurance Testing Suites', i: 'check', d: 'Implements rigorous testing protocols to ensure software reliability and performance. It validates that solutions meet requirements and user expectations.' }
                ]
            },
            {
                id: 'legal',
                label: 'Legal, Internal Operations & HR',
                icon: 'clipboard',
                accent: '#1d3f7d',
                accent2: '#0d2a5c',
                soft: 'rgba(29, 63, 125, 0.16)',
                cards: [
                    { t: 'Legal & Compliance', i: 'file', d: 'Ensures business operations adhere to laws, regulations, and industry standards. It mitigates legal risks and protects the organization\'s reputation.' },
                    { t: 'Internal Operations', i: 'gear', d: 'Streamlines day-to-day business activities to maximize efficiency. It supports core functions and ensures smooth organizational workflows.' },
                    { t: 'Human Resource Management', i: 'userplus', d: 'Manages recruitment, onboarding, and employee relations. It focuses on building a productive and engaged workforce.' },
                    { t: 'Policies & Governance', i: 'lock', d: 'Develops internal rules and frameworks to guide employee behavior and decision-making. It ensures consistency and accountability across the organization.' },
                    { t: 'Administration & Procurement', i: 'cart', d: 'Handles office management and the acquisition of goods and services. It ensures the organization has the resources it needs to operate.' },
                    { t: 'People Development', i: 'award', d: 'Invests in training, mentorship, and career growth for employees. It builds a skilled, adaptable, and loyal workforce.' }
                ]
            },
            {
                id: 'bd',
                label: 'Business Development',
                icon: 'trending',
                accent: '#7ea0f8',
                accent2: '#1a66ff',
                soft: 'rgba(126, 160, 248, 0.22)',
                cards: [
                    { t: 'Sales & Account Management', i: 'trending', d: 'Drives revenue through client acquisition and relationship nurturing. It focuses on understanding client needs and delivering ongoing value.' },
                    { t: 'Customer Engagement', i: 'message', d: 'Builds meaningful interactions across the customer journey. It fosters loyalty, satisfaction, and long-term retention.' },
                    { t: 'Strategic Partnerships', i: 'link', d: 'Forms alliances with other businesses to achieve mutual goals. It expands market reach, capabilities, and resources.' },
                    { t: 'Marketing & Branding', i: 'star', d: 'Creates awareness and positive perception of the company\'s offerings. It communicates value propositions to target audiences.' },
                    { t: 'Proposals & RFPs', i: 'file', d: 'Responds to client solicitations with tailored, competitive bids. It demonstrates capability and secures new business contracts.' },
                    { t: 'Market Expansion', i: 'globe', d: 'Identifies and enters new geographic or demographic markets. It drives growth by capturing new customer segments.' }
                ]
            },
            {
                id: 'bd',
                label: 'Professional training & consultancy services',
                icon: 'users',
                accent: '#71a5f9ff',
                accent2: '#3860a0ff',
                soft: 'rgba(126, 160, 248, 0.22)',
                cards: [
                    { t: 'Financial Planning & Analysis', i: 'dollar', d: 'Develop financial strategies, analyze performance, and deliver insights to support informed decisions and sustainable business growth.' },
                    { t: 'Strategic & Commercial', i: 'target', d: 'Drive business growth through strategic planning, commercial opportunities, resource optimization, and competitive positioning.' },
                    { t: 'Corporate Governance', i: 'shield', d: 'Ensure transparency, accountability, ethical practices, and compliance through effective corporate oversight and governance frameworks.' },
                    { t: 'MInternal Controls', i: 'check-circle', d: 'Strengthen operational processes, minimize risks, safeguard assets, and ensure accuracy through effective internal control mechanisms.' },
                    { t: 'Performance Management', i: 'activity', d: 'Monitor organizational performance, measure outcomes, identify improvement opportunities, and enhance operational effectiveness.' },
                    { t: 'Market Expansion', i: 'globe', d: 'Identify new opportunities, explore emerging markets, expand customer reach, and strengthen competitive market positioning.' },
                    { t: 'Capability & Team Development', i: 'users', d: 'Enhance workforce capabilities through continuous learning, leadership development, collaboration, and effective talent management.' }
                ]
            }
        ];

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
                '<span class="card__line" aria-hidden="true"></span>' +
                '</div>' +
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

                btn.addEventListener('click', function () {
                    selectSection(index, false);
                });

                frag.appendChild(btn);
            });

            tabsEl.appendChild(frag);
        }

        let capRaf = null;
        let capAutoPlayTimer = null;
        let isUserInteractingCap = false;
        let activeCapIndex = 0;

        function isMobileCaps() {
            return window.innerWidth < 768;
        }

        function getCapCards() {
            return Array.from(cardsEl.querySelectorAll('.card'));
        }

        function buildCardDots() {
            if (!cardsDotsEl) return;
            cardsDotsEl.innerHTML = '';
            const cardNodes = getCapCards();
            if (!cardNodes.length) return;

            cardNodes.forEach(function (_, i) {
                const dot = document.createElement('button');
                dot.type = 'button';
                dot.className = 'cards-dot' + (i === 0 ? ' is-active' : '');
                dot.setAttribute('aria-label', 'Go to capability ' + (i + 1));
                dot.addEventListener('click', function () {
                    isUserInteractingCap = true;
                    scrollToCapCard(i);
                    setTimeout(function () {
                        isUserInteractingCap = false;
                    }, 3000);
                });
                cardsDotsEl.appendChild(dot);
            });
        }

        function updateActiveCapCard() {
            if (!isMobileCaps()) return;

            const cardNodes = getCapCards();
            if (!cardNodes.length) return;

            const wrapperRect = cardsEl.getBoundingClientRect();
            const center = wrapperRect.left + wrapperRect.width / 2;
            let closest = 0;
            let min = Infinity;

            cardNodes.forEach(function (card, i) {
                const rect = card.getBoundingClientRect();
                const distance = Math.abs((rect.left + rect.width / 2) - center);
                if (distance < min) {
                    min = distance;
                    closest = i;
                }
            });

            activeCapIndex = closest;

            cardNodes.forEach(function (card, i) {
                card.classList.toggle('is-active', i === activeCapIndex);
            });

            if (cardsDotsEl) {
                Array.from(cardsDotsEl.children).forEach(function (dot, i) {
                    dot.classList.toggle('is-active', i === activeCapIndex);
                });
            }
        }

        function scrollToCapCard(index) {
            if (!isMobileCaps()) return;
            const cardNodes = getCapCards();
            const card = cardNodes[index];
            if (!card) return;

            const wrapperRect = cardsEl.getBoundingClientRect();
            const cardRect = card.getBoundingClientRect();
            const scrollLeft = cardsEl.scrollLeft + (cardRect.left - wrapperRect.left) - (wrapperRect.width - cardRect.width) / 2;

            cardsEl.scrollTo({ left: scrollLeft, behavior: 'smooth' });
        }

        function nextCapCard() {
            if (!isMobileCaps()) return;
            const cardNodes = getCapCards();
            if (!cardNodes.length) return;
            const next = (activeCapIndex + 1) % cardNodes.length;
            scrollToCapCard(next);
        }

        function startCapAutoPlay() {
            stopCapAutoPlay();
            if (!isMobileCaps()) return;
            capAutoPlayTimer = setInterval(function () {
                if (!isUserInteractingCap) {
                    nextCapCard();
                }
            }, 6000);
        }

        function stopCapAutoPlay() {
            if (capAutoPlayTimer) {
                clearInterval(capAutoPlayTimer);
                capAutoPlayTimer = null;
            }
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

            cardsEl.scrollLeft = 0;
            activeCapIndex = 0;

            buildCardDots();

            requestAnimationFrame(function () {
                updateActiveCapCard();
                startCapAutoPlay();
            });
        }

        function buildMobileDropdown() {
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
                if (!wrap.contains(e.target)) {
                    closeMobileDropdown();
                }
            });

            wrap.appendChild(trigger);
            wrap.appendChild(menu);
            tabsEl.parentNode.insertBefore(wrap, tabsEl);
        }

        function toggleMobileDropdown() {
            const wrap = document.getElementById('mobileTabDropdown');
            if (!wrap) return;
            const isOpen = wrap.classList.contains('is-open');
            if (isOpen) closeMobileDropdown();
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
                if (activeTab) {
                    activeTab.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                }
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

        if (cardsEl && cardsDotsEl) {
            cardsEl.addEventListener('scroll', function () {
                if (capRaf) cancelAnimationFrame(capRaf);
                capRaf = requestAnimationFrame(updateActiveCapCard);
            }, { passive: true });

            cardsEl.addEventListener('touchstart', function () {
                isUserInteractingCap = true;
            }, { passive: true });

            cardsEl.addEventListener('touchend', function () {
                setTimeout(function () {
                    isUserInteractingCap = false;
                }, 3000);
            }, { passive: true });

            window.addEventListener('resize', function () {
                updateActiveCapCard();
                if (isMobileCaps()) {
                    startCapAutoPlay();
                } else {
                    stopCapAutoPlay();
                }
            });
        }

        buildTabs();
        buildMobileDropdown();
        selectSection(0, false);

        // Flip cards on desktop (clicking toggles the flip)
        document.addEventListener('click', function (e) {
            const card = e.target.closest('.fx-card');
            if (card && window.innerWidth >= 768) {
                card.classList.toggle('is-flipped');
            }
        });
    })();

    /* =========================================================
       SERVICE CARDS â€” MOBILE PAGINATION (3 cards per page)
       Only active when viewport width <= 767px. Pager is
       inserted after the .svc-hub-grid inside .svc-wrap.
       ========================================================= */
    (function initServiceCardPagination() {
        const grid = document.querySelector('.svc-hub-grid');
        if (!grid) return;

        const cardNodes = Array.from(grid.children);
        if (cardNodes.length <= 3) return; // no need to paginate a single row

        const MOBILE_BREAKPOINT = 767;
        const ITEMS_PER_PAGE = 3;
        const TOTAL_PAGES = Math.ceil(cardNodes.length / ITEMS_PER_PAGE);

        let currentPage = 0;

        function isMobile() {
            return window.innerWidth <= MOBILE_BREAKPOINT;
        }

        /* ---- build pager ---- */
        const pager = document.createElement('nav');
        pager.className = 'svc-hub-pager';
        pager.setAttribute('aria-label', 'Service card pages');

        const prevBtn = document.createElement('button');
        prevBtn.type = 'button';
        prevBtn.className = 'svc-hub-pager-btn svc-hub-pager-prev';
        prevBtn.setAttribute('aria-label', 'Previous page');
        prevBtn.innerHTML =
            '<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>';
        prevBtn.addEventListener('click', function () { goToPage(currentPage - 1); });

        const dotsWrap = document.createElement('div');
        dotsWrap.className = 'svc-hub-pager-dots';

        const dots = [];
        for (let i = 0; i < TOTAL_PAGES; i++) {
            const dot = document.createElement('button');
            dot.type = 'button';
            dot.className = 'svc-hub-pager-dot';
            dot.setAttribute('aria-label', 'Go to page ' + (i + 1));
            dot.addEventListener('click', function () { goToPage(i); });
            dotsWrap.appendChild(dot);
            dots.push(dot);
        }

        const nextBtn = document.createElement('button');
        nextBtn.type = 'button';
        nextBtn.className = 'svc-hub-pager-btn svc-hub-pager-next';
        nextBtn.setAttribute('aria-label', 'Next page');
        nextBtn.innerHTML =
            '<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>';
        nextBtn.addEventListener('click', function () { goToPage(currentPage + 1); });

        pager.appendChild(prevBtn);
        pager.appendChild(dotsWrap);
        pager.appendChild(nextBtn);
        grid.parentNode.insertBefore(pager, grid.nextSibling);

        /* ---- helpers ---- */
        function updatePagerState() {
            dots.forEach(function (dot, i) {
                dot.classList.toggle('is-active', i === currentPage);
                dot.setAttribute('aria-current', i === currentPage ? 'true' : 'false');
            });
            prevBtn.disabled = currentPage === 0;
            nextBtn.disabled = currentPage === TOTAL_PAGES - 1;
        }

        function render() {
            if (!isMobile()) {
                cardNodes.forEach(function (card) {
                    card.style.display = '';
                    card.removeAttribute('aria-hidden');
                });
                pager.style.display = 'none';
                return;
            }

            pager.style.display = '';

            const start = currentPage * ITEMS_PER_PAGE;
            const end = start + ITEMS_PER_PAGE;

            cardNodes.forEach(function (card, i) {
                const visible = i >= start && i < end;
                card.style.display = visible ? '' : 'none';
                if (visible) card.removeAttribute('aria-hidden');
                else card.setAttribute('aria-hidden', 'true');
            });

            updatePagerState();
        }

        function goToPage(index) {
            if (index < 0 || index >= TOTAL_PAGES) return;
            currentPage = index;
            render();
        }

        /* ---- init + respond to resize ---- */
        render();

        let resizeRaf = null;
        window.addEventListener('resize', function () {
            if (resizeRaf) cancelAnimationFrame(resizeRaf);
            resizeRaf = requestAnimationFrame(function () {
                if (!isMobile()) currentPage = 0;
                render();
            });
        }, { passive: true });
    })();

});