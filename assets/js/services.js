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
        techTabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                document.querySelectorAll('.tech-tab').forEach(function (t) {
                    t.classList.remove('active');
                    t.setAttribute('aria-selected', 'false');
                });
                document.querySelectorAll('.tech-panel').forEach(function (p) {
                    p.classList.remove('active');
                });

                tab.classList.add('active');
                tab.setAttribute('aria-selected', 'true');
                const target = document.getElementById(tab.dataset.target);
                if (target) target.classList.add('active');
            });
        });
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

    const grid = document.getElementById('verticalsGrid');
    if (grid) {
        const verticals = [
            {
                icon: '<img src="assets/Images/Data Engineering & Analytics.png" onerror="this.onerror=null; this.src=\'https://www.anadtechnologies.com/wp-content/uploads/2023/05/data-engineering-analytics-featured.png\';" alt="Data Engineering and Analytics">',
                title: 'Data Engineering & Analytics',
                consultant: 'Renien',
                position: 'Senior Solution Architect / CTO',
                desc: 'We design and deploy robust architectures, pipelines, and governance frameworks to turn complex, scattered enterprise data into trusted, analytics-ready assets that drive strategic decision-making.',
                leads: ['Chamod'],
                members: ['Wikasith', 'Amasha'],
                solutions: [
                    'Data Architecture', 'Data Integration (ETL/ELT)', 'Centralized Data Warehouses',
                    'Data Lakes & Lakehouse', 'Data Governance & Quality', 'Analytics Enablement',
                    'Custom Analytics Dashboards'
                ]
            },
            {
                icon: '<img src="assets/Images/AI Engineering & Intelligent Automation.png" onerror="this.onerror=null; this.src=\'https://img.magnific.com/premium-vector/ai-logo-template-vector-with-white-background_1023984-15069.jpg?semt=ais_hybrid&w=740&q=80\';" alt="AI Engineering">',
                title: 'AI Engineering & Intelligent Automation',
                consultant: 'Eranga',
                position: 'AI Research Engineer',
                desc: 'We build intelligent systems, machine learning models, and automated workflows—from generative AI to process orchestration—that automate repetitive tasks and deliver high-impact business solutions.',
                leads: ['Sharumathan'],
                members: ['Tenuka', 'Ashiru'],
                solutions: [
                    'Artificial Intelligence', 'Machine Learning Models', 'Generative AI & LLMs',
                    'Agentic AI Workflows', 'Intelligent Automation', 'AI Strategy & Research',
                    'Custom LLM Implementations', 'Automated Business Process Orchestration'
                ]
            },
            {
                icon: '<img src="assets/Images/Optimization.png" onerror="this.onerror=null; this.src=\'https://png.pngtree.com/png-vector/20230302/ourmid/pngtree-optimizing-line-icon-vector-png-image_6626615.png\';" alt="Optimization">',
                title: 'Optimization',
                consultant: 'Amal',
                position: 'Senior Optimization Consultant',
                desc: 'We optimize enterprise systems, cloud-native architectures, and CI/CD pipelines to accelerate software delivery, strengthen operational reliability, and drive continuous efficiency.',
                leads: ['Sharumathan'],
                members: ['Tenuka', 'Abishigan', 'Chirath'],
                solutions: [
                    'Business Optimization', 'Process Improvement', 'Technology Optimization',
                    'Data Optimization (DPOC)', 'Cost & Resource Optimization', 'Performance Analytics',
                    'RFP Proposal Decks', 'Go-to-Market Strategies'
                ]
            },
            {
                icon: '<img src="assets/Images/Software Engineering & Development.png" onerror="this.onerror=null; this.src=\'https://static.vecteezy.com/system/resources/thumbnails/036/584/129/small_2x/3d-illustration-of-software-development-free-png.png\';" alt="Software Engineering">',
                title: 'Software Engineering & Development',
                consultant: 'Rashintha',
                position: 'Senior Solution Architect',
                desc: 'We design, build, and modernize scalable enterprise applications, digital platforms, and custom APIs to establish secure, high-performing technology environments.',
                leads: ['Chamod', 'Tenuka'],
                members: ['Abishigan', 'Chirath'],
                solutions: [
                    'Enterprise Applications', 'Web & Mobile Solutions', 'API & System Integration',
                    'Workflow Automation', 'Cloud-native Software Platforms', 'DevOps',
                    'Custom API Integrations', 'CI/CD Deployment Pipelines'
                ]
            },
            {
                icon: '<img src="assets/Images/Cybersecurity, Network, Infrastructure, Partner Management, Cloud, Support & Services.png" onerror="this.onerror=null; this.src=\'https://www.intergy.com.au/wp-content/uploads/2024/12/cloud-strategy-and-plannings-process1.png\';" alt="Cybersecurity">',
                title: 'Cybersecurity, Network, Infrastructure, Partner Management, Cloud, Support & Services',
                consultant: 'Isuru',
                position: 'Senior IT Architect',
                desc: 'We architect, protect, and maintain hybrid infrastructure, cloud environments, and managed IT services to guarantee enterprise compliance, robust security, and 24/7 reliability.',
                leads: ['Chamod', 'Menura'],
                members: ['Ashiru'],
                solutions: [
                    'Infrastructure & Cloud', 'Networking & Connectivity', 'Cyber Security',
                    'Support & Managed Services', 'Partner Management', 'Backup & Disaster Recovery Solutions',
                    'Secure Hybrid Cloud Architectures', '24/7 System Monitoring'
                ]
            },
            {
                icon: '<img src="assets/Images/Project Delivery & PMO.png" onerror="this.onerror=null; this.src=\'https://cdn-icons-png.flaticon.com/512/10692/10692220.png\';" alt="Project Delivery">',
                title: 'Project Delivery & PMO',
                consultant: 'Priyath',
                position: 'Senior Project Consultant',
                desc: 'We enforce disciplined project governance, strategic planning, and continuous quality assurance to deliver transparent stakeholder visibility and predictable, high-value project outcomes.',
                leads: ['Chamod'],
                members: ['Chamini', 'Lehan'],
                solutions: [
                    'Project Management', 'Business Analysis', 'PMO & Governance',
                    'Planning & Scheduling', 'Risk & Issue Management', 'Quality Assurance Testing Suites'
                ]
            },
            {
                icon: '<img src="assets/Images/Legal, Internal Operations & HR.png" onerror="this.onerror=null; this.src=\'https://png.pngtree.com/png-clipart/20250517/original/pngtree-operations-manager-3d-icon-with-structured-design-isolated-on-white-background-png-image_20174965.png\';" alt="Legal">',
                title: 'Legal, Internal Operations & HR',
                consultant: 'Chameera',
                position: 'Senior Consultant',
                desc: 'We enable new market opportunities, cultivate strategic partnerships, and drive sustainable organizational growth by aligning internal operations and strategic governance with proactive marketing, customer engagement, and RFP management.',
                leads: ['Unknown'],
                members: ['Samadhi'],
                solutions: [
                    'Legal & Compliance', 'Internal Operations', 'Human Resource Management',
                    'Policies & Governance', 'Administration & Procurement', 'People Development'
                ]
            },
            {
                icon: '<img src="assets/Images/Business Development.png" onerror="this.onerror=null; this.src=\'https://www.pngmart.com/files/23/Business-PNG-File.png\';" alt="Business Development">',
                title: 'Business Development',
                consultant: 'Ayesh',
                position: 'Senior Business Development Consultant',
                desc: 'We identify strategic market opportunities, optimize commercial engagement processes, and cultivate technology partnerships to drive sustainable revenue growth and enterprise expansion.',
                leads: ['Menura'],
                members: ['Chenuli', 'Lehan'],
                solutions: [
                    'Sales & Account Management', 'Customer Engagement', 'Strategic Partnerships',
                    'Marketing & Branding', 'Proposals & RFPs', 'Market Expansion'
                ]
            }
        ];

        verticals.forEach(function (v, i) {
            const card = document.createElement('article');
            card.className = 'v-card';
            card.tabIndex = 0;
            card.setAttribute('role', 'button');
            card.setAttribute('aria-expanded', 'false');
            card.setAttribute('aria-label', v.title + ' — click to expand details');

            card.innerHTML =
                '<div class="v-card__head">' +
                '<div class="v-card__icon">' + v.icon + '</div>' +
                '<span class="v-card__num">' + String(i + 1).padStart(2, '0') + '</span>' +
                '</div>' +
                '<h2 class="v-card__title">' + v.title + '</h2>' +
                '<div class="v-card__consultant">' +
                '<span class="v-label">Consultant</span>' +
                '<span class="v-name">' + v.consultant + '</span>' +
                '<span class="v-pos">' + v.position + '</span>' +
                '</div>' +
                '<div class="v-card__toggle">' +
                '<span class="v-toggle-text">View details</span>' +
                '<svg class="v-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>' +
                '</div>' +
                '<div class="v-card__panel">' +
                '<div class="v-card__panelInner">' +
                '<div class="v-card__panelBody">' +
                '<p class="v-desc">' + v.desc + '</p>' +
                '<div class="v-meta">' +
                '<div class="v-meta__block">' +
                '<span class="v-label">Vertical Lead</span>' +
                '<div class="v-people">' +
                v.leads.map(function (p) { return '<span class="v-person">' + p + '</span>'; }).join('') +
                '</div>' +
                '</div>' +
                '<div class="v-meta__block">' +
                '<span class="v-label">Team Members</span>' +
                '<div class="v-people">' +
                v.members.map(function (p) { return '<span class="v-person">' + p + '</span>'; }).join('') +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="v-solutions">' +
                '<span class="v-label">Solutions</span>' +
                '<div class="v-chips">' +
                v.solutions.map(function (s) { return '<span class="v-chip">' + s + '</span>'; }).join('') +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>';

            grid.appendChild(card);
        });

        grid.querySelectorAll('.v-card').forEach(function (card) {
            const toggleText = card.querySelector('.v-toggle-text');

            function toggle() {
                const isOpen = card.classList.toggle('open');
                card.setAttribute('aria-expanded', String(isOpen));
                if (toggleText) {
                    toggleText.textContent = isOpen ? 'Hide details' : 'View details';
                }
            }

            card.addEventListener('click', toggle);
            card.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ' || e.key === 'Spacebar') {
                    e.preventDefault();
                    toggle();
                }
            });
        });
    }

    const cards = Array.prototype.slice.call(document.querySelectorAll('.orbit .card'));
    if (cards.length) {
        const prefersReduced = window.matchMedia &&
            window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (prefersReduced || !('IntersectionObserver' in window)) {
            cards.forEach(function (el) { el.classList.add('shown'); });
        } else {
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
    }

});