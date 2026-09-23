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

    const heroCanvas = document.getElementById('heroCanvas');
    if (heroCanvas) {
        const ctx = heroCanvas.getContext('2d');
        let w = heroCanvas.width = heroCanvas.offsetWidth;
        let h = heroCanvas.height = heroCanvas.offsetHeight;

        const dots = Array.from({ length: 70 }, function () {
            return {
                x: Math.random() * w,
                y: Math.random() * h,
                r: Math.random() * 1.6 + 0.4,
                vx: (Math.random() - 0.5) * 0.28,
                vy: (Math.random() - 0.5) * 0.28,
                a: Math.random() * 0.45 + 0.15
            };
        });

        function drawDots() {
            ctx.clearRect(0, 0, w, h);
            dots.forEach(function (d) {
                ctx.beginPath();
                ctx.arc(d.x, d.y, d.r, 0, Math.PI * 2);
                ctx.fillStyle = 'rgba(126, 160, 248,' + d.a + ')';
                ctx.fill();
                d.x += d.vx;
                d.y += d.vy;
                if (d.x < 0 || d.x > w) d.vx *= -1;
                if (d.y < 0 || d.y > h) d.vy *= -1;
            });
            requestAnimationFrame(drawDots);
        }

        drawDots();

        window.addEventListener('resize', function () {
            w = heroCanvas.width = heroCanvas.offsetWidth;
            h = heroCanvas.height = heroCanvas.offsetHeight;
        });
    }

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
            label: 'Cybersecurity, Network, Infrastructure, Partner Management, Cloud, Support & Services',
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
            icon: 'users',
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
        }
    ];

    let current = -1;

    function pad2(n) {
        return n < 10 ? '0' + n : String(n);
    }

    function cardHTML(card, index) {
        return '' +
            '<article class="card" style="--i:' + index + '">' +
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
            '<p class="card__text">' + card.d + '</p>' +
            '<span class="card__line" aria-hidden="true"></span>' +
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