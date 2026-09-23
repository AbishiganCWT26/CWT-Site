<?php
/**
 * CWT Corporate Website — Business Verticals Page
 * Modified from Our Services Page to display 8 Business Verticals
 */

session_start();
// Note: DB requires commented out for standalone demonstration. 
// Uncomment in your actual environment.
// require_once __DIR__ . '/includes/db.php';
// require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Business Verticals — Creative Web Technologies';

// Mock data based on the provided image
$verticals = [
    [
        'number' => '1',
        'title' => 'Data Engineering',
        'icon' => '🗄️',
        'highlighted' => true,
        'person' => [
            'name' => 'Renien',
            'role' => 'Senior Solution Architect / CTO',
            'desc' => 'Building busted data foundations for smarter decisions.',
            'initials' => 'R'
        ],
        'focus_areas' => [
            'Data Architecture',
            'Data Integration (ETL/ELT)',
            'Data Warehousing',
            'Data Lakes & Lakehouse',
            'Data Governance & Quality',
            'Analytics Enablement'
        ],
        'lead' => 'Chamod',
        'team' => ['Chamod (Lead)', 'Wikasith', 'Amasha', 'Kalum']
    ],
    [
        'number' => '2',
        'title' => 'AI Engineering',
        'icon' => '🧠',
        'highlighted' => false,
        'person' => [
            'name' => 'Eranga',
            'role' => 'AI Research Engineer',
            'desc' => 'Developing intelligent solutions for real business impact.',
            'initials' => 'E'
        ],
        'focus_areas' => [
            'Artificial Intelligence',
            'Machine Learning',
            'Generative AI & LLMs',
            'Agentic AI',
            'Intelligent Automation',
            'AI Strategy & Research'
        ],
        'lead' => 'Sharumathan',
        'team' => ['Sharumathan', 'Tenuka', 'Ashiru']
    ],
    [
        'number' => '3',
        'title' => 'Optimization',
        'icon' => '⚙️',
        'highlighted' => false,
        'person' => [
            'name' => 'Amal',
            'role' => 'Senior Optimization Consultant',
            'desc' => 'Driving performance, efficiency and continuous improvement.',
            'initials' => 'A'
        ],
        'focus_areas' => [
            'Enterprise Applications',
            'Web & Mobile Solutions',
            'API & System Integration',
            'Workflow Automation',
            'Cloud Native Development',
            'DevOps & CI/CD'
        ],
        'lead' => 'Amal',
        'team' => ['Tenuka', 'Abislingan', 'Chirath']
    ],
    [
        'number' => '4',
        'title' => 'Software Engineering & Development',
        'icon' => '💻',
        'highlighted' => false,
        'person' => [
            'name' => 'Rashintha',
            'role' => 'Senior Solution Architect',
            'desc' => 'Engineering scale, and secure-high-performing technology environment.',
            'initials' => 'R'
        ],
        'focus_areas' => [
            'Infrastructure & Cloud',
            'Networking & Connectivity',
            'Cyber Security',
            'Support & Managed Services',
            'Partner Management',
            'Backup & Disaster Recovery'
        ],
        'lead' => 'Rashintha',
        'team' => ['Tenuka', 'Ashiru']
    ],
    [
        'number' => '5',
        'title' => 'Network, Infra, Security, Partner Mgmt, Support',
        'icon' => '☁️',
        'highlighted' => false,
        'person' => [
            'name' => 'Isuru',
            'role' => 'Senior IT Architect',
            'desc' => 'Ensuring a secure, reliable and high-performing technology environment.',
            'initials' => 'I'
        ],
        'focus_areas' => [
            'Infrastructure & Cloud',
            'Networking & Connectivity',
            'Cyber Security',
            'Support & Managed Services',
            'Partner Management',
            'Backup & Disaster Recovery'
        ],
        'lead' => 'Menura',
        'team' => ['Menura', 'Ashiru']
    ],
    [
        'number' => '6',
        'title' => 'Project Delivery & PMO',
        'icon' => '📊',
        'highlighted' => false,
        'person' => [
            'name' => 'Priyath',
            'role' => 'Senior Project Consultant',
            'desc' => 'Delivering projects with discipline, quality and measurable solutions.',
            'initials' => 'P'
        ],
        'focus_areas' => [
            'Project Management',
            'Business Analysis',
            'PMO & Governance',
            'Planning & Scheduling',
            'Risk & Issue Management',
            'Quality Assurance'
        ],
        'lead' => 'Chamod',
        'team' => ['Chamini', 'Lehan']
    ],
    [
        'number' => '7',
        'title' => 'Legal, Internal Operations & HR',
        'icon' => '👥',
        'highlighted' => false,
        'person' => [
            'name' => 'Chameera',
            'role' => 'Senior Business Development Consultant',
            'desc' => 'Enabling opportunities, building partnerships and progressing growth.',
            'initials' => 'C'
        ],
        'focus_areas' => [
            'Sales & Secret Management',
            'Customer Engagement',
            'Strategic Management',
            'Marketing & Branding',
            'Proposals & RFPs',
            'Market Expansion'
        ],
        'lead' => 'Samadhi',
        'team' => ['Shaneli', 'Duminda', 'Samantha', 'Chamini', 'Chenuli']
    ],
    [
        'number' => '8',
        'title' => 'Business Development',
        'icon' => '📈',
        'highlighted' => false,
        'person' => [
            'name' => 'Ayesh',
            'role' => 'Senior Business Development Consultant',
            'desc' => 'Driving performance, efficiency and continuous improvement.',
            'initials' => 'A'
        ],
        'focus_areas' => [
            'Business Complication',
            'Process Engorgement',
            'Technology Optimization',
            'Data Optimization (DPCO)',
            'Cost & Service Optimization',
            'Performance Analytics'
        ],
        'lead' => 'TBA',
        'team' => ['Sharumathan']
    ]
];

// Re-using the original tech stack data for the bottom section
$techCategories = [
  'data_engineering'     => ['label' => 'Data Engineering', 'items' => [
      ['name' => 'Apache Spark', 'logo_path' => ''],
      ['name' => 'Snowflake', 'logo_path' => ''],
      ['name' => 'Databricks', 'logo_path' => ''],
      ['name' => 'Airflow', 'logo_path' => ''],
      ['name' => 'Kafka', 'logo_path' => ''],
  ]],
  'ai_engineering'       => ['label' => 'AI Engineering', 'items' => [
      ['name' => 'TensorFlow', 'logo_path' => ''],
      ['name' => 'PyTorch', 'logo_path' => ''],
      ['name' => 'Hugging Face', 'logo_path' => ''],
      ['name' => 'OpenAI', 'logo_path' => ''],
  ]],
  'software_engineering' => ['label' => 'Software Engineering', 'items' => [
      ['name' => 'React', 'logo_path' => ''],
      ['name' => 'Node.js', 'logo_path' => ''],
      ['name' => 'Python', 'logo_path' => ''],
      ['name' => 'Java', 'logo_path' => ''],
  ]],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle) ?></title>
  <meta name="description" content="Explore Creative Web Technologies' 8 Business Verticals — Data Engineering, AI, Optimization, Software Engineering, and more.">
  
  <style>
    /* ================================================================
       CWT Corporate Website — Global Design System (Provided)
       ================================================================ */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');

    :root {
      --primary: #001be4;
      --primary-dark: #0050b8;
      --primary-light: #1a66ff;
      --primary-glow: rgba(34, 0, 228, 0.15);

      --white: #FFFFFF;
      --off-white: #F9F9F9;
      --light-grey: #F0F0F0;
      --light-pink: #7ea0f8ff;
      --border: #E8E8E8;

      --text-primary: #1A1A1A;
      --text-secondary: #333333;
      --text-muted: #666666;
      --text-light: #999999;

      --footer-bg: #2C2C2C;
      --footer-text: #B0B0B0;

      --card-bg: #FFFFFF;
      --card-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
      --card-shadow-hover: 0 12px 40px rgba(228, 0, 70, 0.15);

      --radius-sm: 6px;
      --radius: 12px;
      --radius-lg: 20px;
      --radius-full: 9999px;

      --transition: 0.3s ease;
      --transition-slow: 0.5s ease;

      --navbar-height: 72px;
      --section-pad: 90px;
    }

    /* ─── Reset & Base ───────────────────────────────────────────── */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; font-size: 16px; }
    body {
      font-family: 'Inter', sans-serif;
      background-color: var(--off-white);
      color: var(--text-secondary);
      line-height: 1.7;
      -webkit-font-smoothing: antialiased;
    }
    img { max-width: 100%; height: auto; display: block; }
    a { text-decoration: none; color: inherit; }
    ul, ol { list-style: none; }

    /* ─── Typography ─────────────────────────────────────────────── */
    h1, h2, h3, h4, h5, h6 { font-weight: 700; line-height: 1.2; color: var(--text-primary); }
    h1 { font-size: clamp(2rem, 5vw, 3.5rem); }
    h2 { font-size: clamp(1.6rem, 3.5vw, 2.5rem); }
    h3 { font-size: clamp(1.2rem, 2.5vw, 1.6rem); }
    h4 { font-size: 1.2rem; }
    p { color: var(--text-muted); line-height: 1.8; }
    
    .section-label {
      display: inline-block; font-size: 0.75rem; font-weight: 700;
      letter-spacing: 0.12em; text-transform: uppercase;
      color: var(--primary); margin-bottom: 0.75rem;
    }
    .section-title {
      font-size: clamp(1.8rem, 3.5vw, 2.8rem);
      font-weight: 800; color: var(--text-primary); margin-bottom: 1rem;
    }
    .section-subtitle {
      font-size: 1.05rem; color: var(--text-muted);
      max-width: 640px; line-height: 1.8;
    }

    /* ─── Containers ─────────────────────────────────────────────── */
    .container { max-width: 1200px; margin: 0 auto; padding: 0 24px; }
    .section { padding: var(--section-pad) 0; }
    .section-alt { background: var(--white); }
    .section-header { text-align: center; margin-bottom: 60px; }
    .section-header .section-subtitle { margin: 0 auto; }

    /* ─── Page Hero (inner pages) ────────────────────────────────── */
    .page-hero {
      background: linear-gradient(135deg, #0a0a0a 0%, #000d1a 100%);
      padding: 140px 0 80px; text-align: center;
    }
    .page-hero h1 { color: var(--white); font-size: clamp(2rem, 5vw, 3.2rem); margin-bottom: 16px; }
    .page-hero p { color: rgba(255, 255, 255, 0.65); max-width: 600px; margin: 0 auto; font-size: 1.05rem; }
    .page-hero .accent-pill {
      display: inline-block; background: rgba(0, 99, 228, 0.15); color: #6ba1ff;
      font-size: 0.78rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase;
      padding: 6px 16px; border-radius: var(--radius-full);
      border: 1px solid rgba(0, 57, 228, 0.3); margin-bottom: 20px;
    }

    /* ================================================================
       NEW CUSTOM STYLES FOR BUSINESS VERTICALS
       ================================================================ */
    
    .verticals-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 24px;
      align-items: start;
    }

    .vertical-card {
      background: var(--white);
      border-radius: var(--radius);
      border: 1px solid var(--border);
      box-shadow: var(--card-shadow);
      overflow: hidden;
      transition: all var(--transition);
      display: flex;
      flex-direction: column;
      height: 100%;
    }

    .vertical-card:hover {
      box-shadow: var(--card-shadow-hover);
      transform: translateY(-4px);
    }

    /* Highlighting the Data Engineering Card */
    .vertical-card.highlighted {
      border: 2px solid var(--primary);
      box-shadow: 0 12px 40px var(--primary-glow);
      transform: scale(1.02);
      z-index: 10;
    }
    
    .vertical-card.highlighted:hover {
      transform: scale(1.04) translateY(-4px);
      box-shadow: 0 16px 48px rgba(34, 0, 228, 0.25);
    }

    .vertical-header {
      padding: 20px;
      text-align: center;
      border-bottom: 1px solid var(--border);
      position: relative;
    }

    .vertical-card.highlighted .vertical-header {
      background: var(--primary);
      color: var(--white);
      border-bottom: none;
    }

    .vertical-card.highlighted .vertical-header h3,
    .vertical-card.highlighted .vertical-header .vertical-num {
      color: var(--white);
    }

    .vertical-num {
      font-size: 0.8rem;
      font-weight: 800;
      color: var(--primary);
      background: var(--primary-glow);
      width: 28px;
      height: 28px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      position: absolute;
      top: 12px;
      left: 12px;
    }

    .vertical-card.highlighted .vertical-num {
      background: rgba(255, 255, 255, 0.2);
    }

    .vertical-icon {
      font-size: 2rem;
      margin-bottom: 8px;
      display: block;
    }

    .vertical-header h3 {
      font-size: 1.05rem;
      font-weight: 700;
      line-height: 1.3;
    }

    .vertical-profile {
      padding: 20px;
      display: flex;
      gap: 16px;
      align-items: center;
      border-bottom: 1px solid var(--border);
      background: var(--off-white);
    }

    .vertical-avatar {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 1.2rem;
      flex-shrink: 0;
      border: 3px solid var(--white);
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .vertical-profile-info {
      flex: 1;
    }

    .vertical-profile-info strong {
      display: block;
      font-size: 0.95rem;
      color: var(--text-primary);
    }

    .vertical-profile-info span {
      display: block;
      font-size: 0.75rem;
      color: var(--primary);
      font-weight: 600;
      margin-bottom: 4px;
    }

    .vertical-profile-info p {
      font-size: 0.78rem;
      color: var(--text-muted);
      line-height: 1.4;
      margin: 0;
    }

    .vertical-focus {
      padding: 20px;
      flex-grow: 1;
    }

    .vertical-focus h4 {
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      color: var(--text-muted);
      margin-bottom: 12px;
    }

    .vertical-focus ul {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .vertical-focus li {
      font-size: 0.82rem;
      color: var(--text-secondary);
      display: flex;
      align-items: flex-start;
      gap: 8px;
      line-height: 1.4;
    }

    .vertical-focus li::before {
      content: '';
      width: 6px;
      height: 6px;
      background: var(--primary);
      border-radius: 50%;
      flex-shrink: 0;
      margin-top: 6px;
    }

    .vertical-card.highlighted .vertical-focus li::before {
      background: var(--primary);
    }

    .vertical-team {
      padding: 16px 20px;
      background: var(--off-white);
      border-top: 1px solid var(--border);
      font-size: 0.8rem;
    }

    .vertical-team strong {
      display: block;
      color: var(--text-primary);
      margin-bottom: 4px;
    }

    .vertical-team .team-list {
      color: var(--text-muted);
      font-size: 0.78rem;
    }

    .vertical-team .lead-name {
      color: var(--primary);
      font-weight: 600;
    }

    /* ─── Tech Stack (Kept from original) ────────────────────────── */
    .tech-tabs { display: flex; gap: 8px; flex-wrap: wrap; justify-content: center; margin-bottom: 48px; }
    .tech-tab {
      padding: 10px 24px; border-radius: var(--radius-full); font-size: 0.875rem;
      font-weight: 600; cursor: pointer; border: 2px solid var(--border);
      background: var(--white); color: var(--text-muted); transition: all var(--transition);
    }
    .tech-tab.active, .tech-tab:hover { background: var(--primary); border-color: var(--primary); color: var(--white); }
    .tech-panel { display: none; }
    .tech-panel.active { display: grid; }
    .tech-logos-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); gap: 20px; }
    .tech-logo-item {
      display: flex; flex-direction: column; align-items: center; gap: 10px;
      padding: 20px 12px; background: var(--white); border-radius: var(--radius);
      border: 1px solid var(--border); transition: all var(--transition);
      cursor: default; position: relative;
    }
    .tech-logo-item:hover { border-color: var(--primary); box-shadow: 0 4px 20px rgba(228, 0, 70, 0.12); transform: translateY(-3px); }
    .tech-logo-placeholder {
      width: 48px; height: 48px; background: var(--light-grey); border-radius: 8px;
      display: flex; align-items: center; justify-content: center;
      font-weight: 700; font-size: 1rem; color: var(--primary);
    }
    .tech-logo-name { font-size: 0.78rem; font-weight: 600; color: var(--text-muted); text-align: center; line-height: 1.3; }

    /* ─── Utilities & Animations ────────────────────────────────── */
    .text-center { text-align: center; }
    .reveal { opacity: 0; transform: translateY(30px); transition: opacity 0.7s ease, transform 0.7s ease; }
    .reveal.visible { opacity: 1; transform: none; }

    /* ─── Responsive ─────────────────────────────────────────────── */
    @media (max-width: 1024px) {
      .verticals-grid { grid-template-columns: repeat(2, 1fr); }
      .vertical-card.highlighted { transform: scale(1); }
      .vertical-card.highlighted:hover { transform: translateY(-4px); }
    }
    @media (max-width: 768px) {
      :root { --section-pad: 60px; }
      .verticals-grid { grid-template-columns: 1fr; }
      .vertical-card.highlighted { transform: scale(1); }
    }
  </style>
</head>
<body>

<!-- Placeholder Navbar (Normally included via PHP) -->
<nav style="background:white; padding: 20px; text-align: center; border-bottom: 1px solid var(--border);">
    <div style="font-weight: 900; color: var(--primary);">CWT Corporate</div>
</nav>

<!-- Page Hero -->
<section class="page-hero" aria-label="Business Verticals page hero">
  <div class="container">
    <div class="accent-pill">Our Structure</div>
    <h1>8 Business Verticals (N-1)</h1>
    <p>Integrated Capabilities for End-to-End Customer Value. Highlighting our core <strong>Data Engineering</strong> division.</p>
  </div>
</section>

<!-- ════════════════════════════════════════════════════════════ -->
<!-- BUSINESS VERTICALS GRID                                       -->
<!-- ════════════════════════════════════════════════════════════ -->
<section class="section section-alt" id="business-verticals" aria-labelledby="verticals-heading">
  <div class="container">
    <div class="section-header reveal">
      <span class="section-label">Tomorrow's Capabilities</span>
      <h2 class="section-title" id="verticals-heading">Our 8 Integrated Verticals</h2>
      <p class="section-subtitle">Delivering end-to-end customer value through specialized, cross-functional teams.</p>
    </div>

    <div class="verticals-grid">
      <?php foreach ($verticals as $v): ?>
      <div class="vertical-card reveal <?= $v['highlighted'] ? 'highlighted' : '' ?>">
        
        <!-- Header -->
        <div class="vertical-header">
          <span class="vertical-num"><?= $v['number'] ?></span>
          <span class="vertical-icon"><?= $v['icon'] ?></span>
          <h3><?= htmlspecialchars($v['title']) ?></h3>
        </div>

        <!-- Profile -->
        <div class="vertical-profile">
          <div class="vertical-avatar"><?= $v['person']['initials'] ?></div>
          <div class="vertical-profile-info">
            <strong><?= htmlspecialchars($v['person']['name']) ?></strong>
            <span><?= htmlspecialchars($v['person']['role']) ?></span>
            <p><?= htmlspecialchars($v['person']['desc']) ?></p>
          </div>
        </div>

        <!-- Key Focus Areas -->
        <div class="vertical-focus">
          <h4>Key Focus Areas</h4>
          <ul>
            <?php foreach ($v['focus_areas'] as $area): ?>
              <li><?= htmlspecialchars($area) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>

        <!-- Team Info -->
        <div class="vertical-team">
          <strong>Vertical Lead: <span class="lead-name"><?= htmlspecialchars($v['lead']) ?></span></strong>
          <div class="team-list">
            Team Members: <?= htmlspecialchars(implode(', ', $v['team'])) ?>
          </div>
        </div>

      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ════════════════════════════════════════════════════════════ -->
<!-- TECH STACK (Kept from original)                               -->
<!-- ════════════════════════════════════════════════════════════ -->
<section class="section" id="tech-stack" aria-labelledby="tech-heading">
  <div class="container">
    <div class="section-header reveal">
      <span class="section-label">Technology</span>
      <h2 class="section-title" id="tech-heading">The Right Talent For Every Tech Stack</h2>
      <p class="section-subtitle">Scale faster with proven expertise across the tools & technologies you already use.</p>
    </div>

    <!-- Tabs -->
    <div class="tech-tabs reveal" role="tablist">
      <?php $first = true; foreach ($techCategories as $key => $cat): ?>
      <button class="tech-tab <?= $first ? 'active' : '' ?>"
              data-target="tech-<?= $key ?>"
              role="tab"
              aria-selected="<?= $first ? 'true' : 'false' ?>">
        <?= htmlspecialchars($cat['label']) ?>
      </button>
      <?php $first = false; endforeach; ?>
    </div>

    <!-- Panels -->
    <?php $first = true; foreach ($techCategories as $key => $cat): ?>
    <div class="tech-panel tech-logos-grid <?= $first ? 'active' : '' ?>"
         id="tech-<?= $key ?>"
         role="tabpanel">
      <?php if (empty($cat['items'])): ?>
        <p style="color:var(--text-muted);grid-column:1/-1;text-align:center;">No tech stack items added yet.</p>
      <?php else: ?>
        <?php foreach ($cat['items'] as $tech): ?>
        <div class="tech-logo-item">
          <div class="tech-logo-placeholder"><?= mb_substr($tech['name'], 0, 2) ?></div>
          <span class="tech-logo-name"><?= htmlspecialchars($tech['name']) ?></span>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
    <?php $first = false; endforeach; ?>
  </div>
</section>

<!-- Placeholder Footer -->
<footer style="background: var(--footer-bg); padding: 40px; text-align: center; color: var(--footer-text); margin-top: 60px;">
    <p>&copy; <?= date('Y') ?> Creative Web Technologies. All rights reserved.</p>
</footer>

<!-- ════════════════════════════════════════════════════════════ -->
<!-- JAVASCRIPT                                                    -->
<!-- ════════════════════════════════════════════════════════════ -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    
    // 1. Scroll Reveal Animation
    const revealElements = document.querySelectorAll('.reveal');
    
    const revealOnScroll = () => {
        const windowHeight = window.innerHeight;
        const elementVisible = 100; // Trigger point
        
        revealElements.forEach(el => {
            const elementTop = el.getBoundingClientRect().top;
            if (elementTop < windowHeight - elementVisible) {
                el.classList.add('visible');
            }
        });
    };

    window.addEventListener('scroll', revealOnScroll);
    revealOnScroll(); // Trigger once on load

    // 2. Tech Stack Tabs Logic
    const tabs = document.querySelectorAll('.tech-tab');
    const panels = document.querySelectorAll('.tech-panel');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            // Remove active from all tabs and panels
            tabs.forEach(t => {
                t.classList.remove('active');
                t.setAttribute('aria-selected', 'false');
            });
            panels.forEach(p => p.classList.remove('active'));

            // Add active to clicked tab
            tab.classList.add('active');
            tab.setAttribute('aria-selected', 'true');

            // Show corresponding panel
            const targetId = tab.getAttribute('data-target');
            const targetPanel = document.getElementById(targetId);
            if (targetPanel) {
                targetPanel.classList.add('active');
            }
        });
    });

    // 3. Navbar Scrolled Effect (Optional, since the navbar is a placeholder here)
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    }
});
</script>

</body>
</html>