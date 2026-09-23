/**
 * CWT Website - Frontend JavaScript
 */

document.addEventListener('DOMContentLoaded', function () {

  // ─── Navbar scroll effect ──────────────────────────────────────
  const navbar = document.querySelector('.navbar');
  if (navbar) {
    window.addEventListener('scroll', function () {
      navbar.classList.toggle('scrolled', window.scrollY > 60);
    });
  }

  // ─── Mobile menu toggle ────────────────────────────────────────
  const hamburger = document.querySelector('.hamburger');
  const mobileMenu = document.querySelector('.mobile-menu');
  if (hamburger && mobileMenu) {
    hamburger.addEventListener('click', function () {
      hamburger.classList.toggle('active');
      mobileMenu.classList.toggle('open');
    });

    // Close menu on link click
    mobileMenu.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', function () {
        hamburger.classList.remove('active');
        mobileMenu.classList.remove('open');
      });
    });

    // Close menu on outside click
    document.addEventListener('click', function (e) {
      if (!navbar.contains(e.target)) {
        hamburger.classList.remove('active');
        mobileMenu.classList.remove('open');
      }
    });
  }

  // ─── Active nav link ──────────────────────────────────────────
  const currentPath = window.location.pathname.split('/').pop() || 'index.php';
  document.querySelectorAll('.navbar-nav a, .mobile-menu a').forEach(function (link) {
    const href = link.getAttribute('href') || '';
    if (href && currentPath.includes(href.replace('.php', ''))) {
      link.classList.add('active');
    }
  });

  // ─── Marquee hover pause ──────────────────────────────────────
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

  // ─── Tech Stack Tabs ──────────────────────────────────────────
  const techTabs = document.querySelectorAll('.tech-tab');
  if (techTabs.length) {
    techTabs.forEach(function (tab) {
      tab.addEventListener('click', function () {
        // Deactivate all
        document.querySelectorAll('.tech-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tech-panel').forEach(p => p.classList.remove('active'));

        // Activate clicked
        tab.classList.add('active');
        const target = document.getElementById(tab.dataset.target);
        if (target) target.classList.add('active');
      });
    });
  }

  // ─── History Read More ────────────────────────────────────────
  const readMoreBtn = document.querySelector('.read-more-btn');
  const historyMore = document.querySelector('.history-more');
  if (readMoreBtn && historyMore) {
    readMoreBtn.addEventListener('click', function () {
      const isOpen = readMoreBtn.classList.contains('open');
      if (isOpen) {
        historyMore.classList.remove('show');
        readMoreBtn.classList.remove('open');
        readMoreBtn.innerHTML = readMoreBtn.innerHTML.replace('Show Less', 'Read More');
      } else {
        historyMore.classList.add('show');
        readMoreBtn.classList.add('open');
        readMoreBtn.innerHTML = readMoreBtn.innerHTML.replace('Read More', 'Show Less');
      }
    });
  }

  // ─── Counter Animation ────────────────────────────────────────
  function animateCounter(el) {
    const target = parseInt(el.dataset.target, 10);
    const suffix = el.dataset.suffix || '';
    const prefix = el.dataset.prefix || '';
    const duration = 1800;
    const start = performance.now();

    function update(time) {
      const elapsed = time - start;
      const progress = Math.min(elapsed / duration, 1);
      const eased = 1 - Math.pow(1 - progress, 3); // ease-out cubic
      const current = Math.round(eased * target);
      el.textContent = prefix + current + suffix;
      if (progress < 1) requestAnimationFrame(update);
    }

    requestAnimationFrame(update);
  }

  // ─── Scroll Reveal + Counter trigger ─────────────────────────
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

  // ─── Smooth scroll for anchor links ──────────────────────────
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

  // ─── Hero particle effect (subtle) ───────────────────────────
  const heroCanvas = document.getElementById('heroCanvas');
  if (heroCanvas) {
    const ctx = heroCanvas.getContext('2d');
    let w = heroCanvas.width = heroCanvas.offsetWidth;
    let h = heroCanvas.height = heroCanvas.offsetHeight;

    const dots = Array.from({ length: 60 }, () => ({
      x: Math.random() * w,
      y: Math.random() * h,
      r: Math.random() * 1.5 + 0.5,
      vx: (Math.random() - 0.5) * 0.3,
      vy: (Math.random() - 0.5) * 0.3,
      a: Math.random() * 0.5 + 0.15,
    }));

    function drawDots() {
      ctx.clearRect(0, 0, w, h);
      dots.forEach(function (d) {
        ctx.beginPath();
        ctx.arc(d.x, d.y, d.r, 0, Math.PI * 2);
        ctx.fillStyle = `rgba(228,0,70,${d.a})`;
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

});

// ─── Verticals Interactive Cards (services.php) ───────────────
document.addEventListener('DOMContentLoaded', function () {
  const grid = document.getElementById('verticalsGrid');
  if (!grid) return;

  const verticals = [
    {
      icon: '<img src="assets/Images/Data Engineering & Analytics.png" onerror="this.onerror=null; this.src=\'https://www.anadtechnologies.com/wp-content/uploads/2023/05/data-engineering-analytics-featured.png\';" alt="Data Engineering and Analytics" style="width: 50px; height: auto;">',
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
      icon: '<img src="assets/Images/AI Engineering & Intelligent Automation.png" onerror="this.onerror=null; this.src=\'https://img.magnific.com/premium-vector/ai-logo-template-vector-with-white-background_1023984-15069.jpg?semt=ais_hybrid&w=740&q=80\';" alt="🤖" style="width: 50px; height: auto;">',
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
      icon: '<img src="assets/Images/Optimization.png" onerror="this.onerror=null; this.src=\'https://png.pngtree.com/png-vector/20230302/ourmid/pngtree-optimizing-line-icon-vector-png-image_6626615.png\';" alt="⚡" style="width: 50px; height: auto;">',
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
      icon: '<img src="assets/Images/Software Engineering & Development.png" onerror="this.onerror=null; this.src=\'https://static.vecteezy.com/system/resources/thumbnails/036/584/129/small_2x/3d-illustration-of-software-development-free-png.png\';" alt="💻" style="width: 50px; height: auto;">',
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
      icon: '<img src="assets/Images/Cybersecurity, Network, Infrastructure, Partner Management, Cloud, Support & Services.png" onerror="this.onerror=null; this.src=\'https://www.intergy.com.au/wp-content/uploads/2024/12/cloud-strategy-and-plannings-process1.png\';" alt="🛡️" style="width: 50px; height: auto;">',
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
      icon: '<img src="assets/Images/Project Delivery & PMO.png" onerror="this.onerror=null; this.src=\'https://cdn-icons-png.flaticon.com/512/10692/10692220.png\';" alt="📊" style="width: 50px; height: auto;">',
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
      icon: '<img src="assets/Images/Legal, Internal Operations & HR.png" onerror="this.onerror=null; this.src=\'https://png.pngtree.com/png-clipart/20250517/original/pngtree-operations-manager-3d-icon-with-structured-design-isolated-on-white-background-png-image_20174965.png\';" alt="⚖️" style="width: 50px; height: auto;">',
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
      icon: '<img src="assets/Images/Business Development.png" onerror="this.onerror=null; this.src=\'https://www.pngmart.com/files/23/Business-PNG-File.png\';" alt="🚀" style="width: 40px; height: auto;">',
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
    var toggleText = card.querySelector('.v-toggle-text');

    function toggle() {
      var isOpen = card.classList.toggle('open');
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
});

// ─── Capabilities Explorer Interactive Card Grid ───────────────
document.addEventListener('DOMContentLoaded', function () {
  var tabsEl      = document.getElementById("tabs");
  var cardsEl     = document.getElementById("cards");
  var panelEl     = document.getElementById("panel");
  var panelTitle  = document.getElementById("panelTitle");
  var panelMeta   = document.getElementById("panelMeta");
  var panelIconUse = document.getElementById("panelIconUse");

  if (!tabsEl || !cardsEl || !panelEl) return;

  var SECTIONS = [
    {
      id: "data",
      label: "Data Engineering & Analytics",
      icon: "database",
      accent: "#001be4",
      accent2: "#0015b0",
      soft: "rgba(0, 27, 228, 0.16)",
      cards: [
        { t: "Data Architecture", i: "grid",
          d: "Defines the structural blueprint for how data is collected, stored, and accessed across the organization. It ensures scalability, security, and alignment with overarching business goals." },
        { t: "Data Integration (ETL/ELT)", i: "shuffle",
          d: "Extracts, transforms, and loads data from disparate sources into a unified destination. It ensures timely, accurate, and seamless data availability for analysis." },
        { t: "Centralized Data Warehouses", i: "box",
          d: "Stores structured, historical data optimized for high-performance querying and reporting. It acts as a single source of truth for enterprise business intelligence." },
        { t: "Data Lakes & Lakehouse", i: "layers",
          d: "Provides flexible storage for raw, structured, and unstructured data at scale. It combines the elasticity of data lakes with the transactional reliability of warehouses." },
        { t: "Data Governance & Quality", i: "filter",
          d: "Establishes policies, standards, and controls to ensure data accuracy, privacy, and compliance. It builds essential trust in the data used for decision-making." },
        { t: "Analytics Enablement", i: "chart",
          d: "Provides the tools, training, and infrastructure for teams to derive insights from data. It bridges the gap between raw data and actionable intelligence." },
        { t: "Custom Analytics Dashboards", i: "monitor",
          d: "Delivers visually intuitive, interactive interfaces tailored to specific business KPIs. It empowers stakeholders with real-time visibility into performance metrics." }
      ]
    },
    {
      id: "ai",
      label: "AI Engineering & Intelligent Automation",
      icon: "cpu",
      accent: "#1a66ff",
      accent2: "#0050b8",
      soft: "rgba(26, 102, 255, 0.16)",
      cards: [
        { t: "Artificial Intelligence", i: "cpu",
          d: "Simulates human intelligence processes to perform tasks like reasoning, learning, and problem-solving. It drives innovation and competitive advantage across industries." },
        { t: "Machine Learning Models", i: "trending",
          d: "Uses statistical algorithms to identify patterns and make predictions from data. These models continuously improve with experience to automate complex decisions." },
        { t: "Generative AI & LLMs", i: "star",
          d: "Leverages large language models to create new content, code, or insights. It revolutionizes creativity, customer interaction, and knowledge retrieval." },
        { t: "Agentic AI Workflows", i: "branch",
          d: "Deploys autonomous AI agents capable of executing multi-step tasks independently. It goes beyond simple automation by enabling goal-oriented decision-making." },
        { t: "Intelligent Automation", i: "robot",
          d: "Combines AI and robotic process automation to handle cognitive and repetitive tasks. It significantly reduces manual effort and operational errors." },
        { t: "AI Strategy & Research", i: "compass",
          d: "Defines the roadmap for AI adoption, identifying high-value use cases and ethical guidelines. It ensures AI investments align with long-term business objectives." },
        { t: "Custom LLM Implementations", i: "code",
          d: "Fine-tunes or builds proprietary large language models on domain-specific data. It provides tailored AI capabilities that protect sensitive data and ensure relevance." },
        { t: "Automated Business Process Orchestration", i: "refresh",
          d: "Coordinates complex workflows across multiple systems and teams automatically. It ensures seamless execution of end-to-end business operations." }
      ]
    },
    {
      id: "optimization",
      label: "Optimization",
      icon: "gear",
      accent: "#1557c4",
      accent2: "#0f3b8a",
      soft: "rgba(21, 87, 196, 0.16)",
      cards: [
        { t: "Business Optimization", i: "trending",
          d: "Analyzes and redesigns business functions to maximize efficiency and value. It drives continuous improvement and competitive agility." },
        { t: "Process Improvement", i: "sliders",
          d: "Systematically identifies and eliminates bottlenecks and waste in workflows. It enhances productivity and quality across the organization." },
        { t: "Technology Optimization", i: "gear",
          d: "Evaluates and upgrades the technology stack to improve performance and reduce technical debt. It ensures the IT infrastructure supports current and future needs." },
        { t: "Data Optimization (DPOC)", i: "database",
          d: "Focuses on streamlining data pipelines and storage to reduce costs and improve speed. It ensures the data ecosystem operates at peak efficiency." },
        { t: "Cost & Resource Optimization", i: "dollar",
          d: "Aligns spending and resource allocation with strategic priorities. It maximizes ROI by eliminating redundancies and improving utilization." },
        { t: "Performance Analytics", i: "activity",
          d: "Continuously monitors and analyzes key performance indicators (KPIs). It provides the insights needed to drive data-informed strategic adjustments." },
        { t: "RFP proposal decks", i: "file",
          d: "Crafts compelling, customized presentations that clearly articulate value propositions. It wins business by addressing client needs with tailored solutions." },
        { t: "go-to-market strategies", i: "target",
          d: "Develops comprehensive plans to launch products or services successfully. It defines target audiences, pricing, and channels to drive market adoption." }
      ]
    },
    {
      id: "software",
      label: "Software Engineering & Development",
      icon: "code",
      accent: "#0f3b8a",
      accent2: "#0d2a5c",
      soft: "rgba(15, 59, 138, 0.16)",
      cards: [
        { t: "Enterprise Applications", i: "layers",
          d: "Builds complex, scalable software systems that support core business functions. They integrate with various departments to streamline enterprise-wide operations." },
        { t: "Web & Mobile Solutions", i: "smartphone",
          d: "Develops responsive applications for browsers and mobile devices. It ensures seamless user experiences across all digital touchpoints." },
        { t: "API & System Integration", i: "link",
          d: "Connects disparate software applications and data sources. It enables seamless data flow and functionality across the technology ecosystem." },
        { t: "Workflow Automation", i: "refresh",
          d: "Automates routine business processes using software tools. It reduces manual intervention, speeds up task completion, and minimizes errors." },
        { t: "Cloud-native Software Platforms", i: "cloud",
          d: "Designs applications specifically to leverage the scalability and resilience of the cloud. It enables rapid deployment and continuous innovation." },
        { t: "DevOps", i: "terminal",
          d: "Fosters collaboration between development and operations teams. It accelerates software delivery through automation and continuous improvement." },
        { t: "Custom API Integrations", i: "code",
          d: "Creates tailored connections between proprietary and third-party systems. It ensures unique business requirements are met without disrupting existing workflows." },
        { t: "CI/CD Deployment Pipelines", i: "branch",
          d: "Automates the building, testing, and deployment of software. It ensures faster, more reliable releases with minimal manual intervention." }
      ]
    },
    {
      id: "cyber",
      label: "Cybersecurity, Network, Infrastructure, Partner Management, Cloud, Support & Services",
      icon: "shield",
      accent: "#2b7bff",
      accent2: "#1557c4",
      soft: "rgba(43, 123, 255, 0.18)",
      cards: [
        { t: "Infrastructure & Cloud", i: "cloud",
          d: "Manages the physical and virtual resources required to run applications. It provides the scalable foundation for all digital operations." },
        { t: "Networking & Connectivity", i: "globe",
          d: "Ensures reliable, high-speed communication between systems, users, and locations. It is the backbone of seamless digital interaction." },
        { t: "Cyber Security", i: "shield",
          d: "Protects systems, networks, and data from digital attacks. It safeguards confidentiality, integrity, and availability of critical information." },
        { t: "Support & Managed Services", i: "wrench",
          d: "Provides ongoing monitoring, maintenance, and troubleshooting for IT systems. It ensures optimal performance and minimizes downtime." },
        { t: "Partner Management", i: "users",
          d: "Builds and maintains strategic relationships with technology vendors and service providers. It ensures access to specialized expertise and resources." },
        { t: "Backup & Disaster Recovery Solutions", i: "harddrive",
          d: "Implements robust data backup and restoration protocols. It ensures business continuity in the event of system failures or disasters." },
        { t: "Secure Hybrid Cloud Architectures", i: "lock",
          d: "Integrates on-premises infrastructure with public and private clouds securely. It offers flexibility, scalability, and enhanced data control." },
        { t: "24/7 System Monitoring", i: "eye",
          d: "Continuously tracks system health, performance, and security. It enables proactive issue resolution before problems impact users." }
      ]
    },
    {
      id: "pmo",
      label: "Project Delivery & PMO",
      icon: "briefcase",
      accent: "#5b9cff",
      accent2: "#2b7bff",
      soft: "rgba(91, 156, 255, 0.20)",
      cards: [
        { t: "Project Management", i: "briefcase",
          d: "Applies methodologies to plan, execute, and close projects successfully. It ensures deliverables are completed on time, within budget, and to scope." },
        { t: "Business Analysis", i: "chart",
          d: "Bridges the gap between business needs and technical solutions. It gathers requirements and defines solutions that deliver value." },
        { t: "PMO & Governance", i: "shield",
          d: "Establishes standard project management frameworks and oversight. It ensures consistency, compliance, and alignment with strategic goals." },
        { t: "Planning & Scheduling", i: "clipboard",
          d: "Develops detailed project timelines, milestones, and resource allocations. It provides a roadmap for execution and tracks progress." },
        { t: "Risk & Issue Management", i: "alert",
          d: "Identifies potential threats and resolves existing problems proactively. It minimizes negative impacts on project timelines and outcomes." },
        { t: "Quality Assurance Testing Suites", i: "check",
          d: "Implements rigorous testing protocols to ensure software reliability and performance. It validates that solutions meet requirements and user expectations." }
      ]
    },
    {
      id: "legal",
      label: "Legal, Internal Operations & HR",
      icon: "users",
      accent: "#1d3f7d",
      accent2: "#0d2a5c",
      soft: "rgba(29, 63, 125, 0.16)",
      cards: [
        { t: "Legal & Compliance", i: "file",
          d: "Ensures business operations adhere to laws, regulations, and industry standards. It mitigates legal risks and protects the organization's reputation." },
        { t: "Internal Operations", i: "gear",
          d: "Streamlines day-to-day business activities to maximize efficiency. It supports core functions and ensures smooth organizational workflows." },
        { t: "Human Resource Management", i: "userplus",
          d: "Manages recruitment, onboarding, and employee relations. It focuses on building a productive and engaged workforce." },
        { t: "Policies & Governance", i: "lock",
          d: "Develops internal rules and frameworks to guide employee behavior and decision-making. It ensures consistency and accountability across the organization." },
        { t: "Administration & Procurement", i: "cart",
          d: "Handles office management and the acquisition of goods and services. It ensures the organization has the resources it needs to operate." },
        { t: "People Development", i: "award",
          d: "Invests in training, mentorship, and career growth for employees. It builds a skilled, adaptable, and loyal workforce." }
      ]
    },
    {
      id: "bd",
      label: "Business Development",
      icon: "trending",
      accent: "#7ea0f8",
      accent2: "#1a66ff",
      soft: "rgba(126, 160, 248, 0.22)",
      cards: [
        { t: "Sales & Account Management", i: "trending",
          d: "Drives revenue through client acquisition and relationship nurturing. It focuses on understanding client needs and delivering ongoing value." },
        { t: "Customer Engagement", i: "message",
          d: "Builds meaningful interactions across the customer journey. It fosters loyalty, satisfaction, and long-term retention." },
        { t: "Strategic Partnerships", i: "link",
          d: "Forms alliances with other businesses to achieve mutual goals. It expands market reach, capabilities, and resources." },
        { t: "Marketing & Branding", i: "star",
          d: "Creates awareness and positive perception of the company's offerings. It communicates value propositions to target audiences." },
        { t: "Proposals & RFPs", i: "file",
          d: "Responds to client solicitations with tailored, competitive bids. It demonstrates capability and secures new business contracts." },
        { t: "Market Expansion", i: "globe",
          d: "Identifies and enters new geographic or demographic markets. It drives growth by capturing new customer segments." }
      ]
    }
  ];

  var current = -1;

  function pad2(n) {
    return n < 10 ? "0" + n : String(n);
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
    var frag = document.createDocumentFragment();

    SECTIONS.forEach(function (section, index) {
      var btn = document.createElement("button");
      btn.type = "button";
      btn.className = "tab";
      btn.id = "tab-" + section.id;
      btn.setAttribute("role", "tab");
      btn.setAttribute("aria-controls", "panel");
      btn.setAttribute("aria-selected", index === 0 ? "true" : "false");
      btn.tabIndex = index === 0 ? 0 : -1;
      btn.dataset.index = index;

      btn.innerHTML =
        '<svg class="ico tab__ico" viewBox="0 0 24 24" aria-hidden="true" focusable="false">' +
          '<use href="#i-' + section.icon + '"></use>' +
        '</svg>' +
        '<span class="tab__label">' + section.label + '</span>';

      btn.addEventListener("click", function () {
        selectSection(index, false);
      });

      frag.appendChild(btn);
    });

    tabsEl.appendChild(frag);
  }

  function renderSection(index) {
    var section = SECTIONS[index];
    if (!section) return;

    Array.prototype.forEach.call(tabsEl.children, function (btn, i) {
      var isOn = i === index;
      btn.setAttribute("aria-selected", isOn ? "true" : "false");
      btn.tabIndex = isOn ? 0 : -1;
    });

    panelEl.style.setProperty("--accent", section.accent);
    panelEl.style.setProperty("--accent-2", section.accent2);
    panelEl.style.setProperty("--accent-soft", section.soft);

    cardsEl.style.setProperty("--accent", section.accent);
    cardsEl.style.setProperty("--accent-2", section.accent2);
    cardsEl.style.setProperty("--accent-soft", section.soft);

    if (panelIconUse) panelIconUse.setAttribute("href", "#i-" + section.icon);
    if (panelTitle) panelTitle.textContent = section.label;
    if (panelMeta) panelMeta.textContent =
      section.cards.length + (section.cards.length === 1 ? " capability" : " capabilities");

    panelEl.setAttribute("aria-labelledby", "tab-" + section.id);

    var html = "";
    for (var i = 0; i < section.cards.length; i++) {
      html += cardHTML(section.cards[i], i);
    }
    cardsEl.innerHTML = html;
  }

  function selectSection(index, focusTab) {
    if (index === current) return;
    current = index;
    renderSection(index);

    if (focusTab) {
      var btn = tabsEl.children[index];
      if (btn) btn.focus();
    }
  }

  tabsEl.addEventListener("keydown", function (event) {
    var key = event.key;
    var handled = ["ArrowRight", "ArrowLeft", "ArrowUp", "ArrowDown", "Home", "End"].indexOf(key) !== -1;
    if (!handled) return;

    event.preventDefault();

    var next = current;
    var last = SECTIONS.length - 1;

    if (key === "ArrowRight" || key === "ArrowDown") next = current >= last ? 0 : current + 1;
    else if (key === "ArrowLeft" || key === "ArrowUp") next = current <= 0 ? last : current - 1;
    else if (key === "Home") next = 0;
    else if (key === "End") next = last;

    selectSection(next, true);
  });

  function init() {
    buildTabs();
    selectSection(0, false);
  }

  init();
});

// ─── Circle Steps Delivery Process Animation ─────────────────
document.addEventListener('DOMContentLoaded', function () {
  var list = document.querySelector(".steps");
  if (!list) return;

  var items = Array.prototype.slice.call(list.children);
  if (!items.length) return;

  var prefersReduced = window.matchMedia &&
    window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  /* ---------- Fallback: show everything instantly ---------- */
  if (prefersReduced || !("IntersectionObserver" in window)) {
    items.forEach(function (li) { li.classList.add("shown"); });
    return;
  }

  /* ---------- Prepare each card ---------- */
  items.forEach(function (li, i) {
    li.dataset.index = i;
    li.setAttribute("tabindex", "0");

    /* When the entrance animation finishes, swap classes so the
       hover transform is free to take over. */
    li.addEventListener("animationend", function (e) {
      if (e.target !== li || e.animationName !== "cardIn") return;
      li.classList.add("shown");
      li.classList.remove("in-view");
      li.style.animationDelay = "";
    });
  });

  /* ---------- Reveal on scroll with stagger ---------- */
  var observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (!entry.isIntersecting) return;

      var li = entry.target;
      var i = Number(li.dataset.index) || 0;

      li.style.animationDelay = (i * 110) + "ms";
      li.classList.add("in-view");

      observer.unobserve(li);
    });
  }, {
    threshold: 0.12,
    rootMargin: "0px 0px -60px 0px"
  });

  items.forEach(function (li) { observer.observe(li); });
});

