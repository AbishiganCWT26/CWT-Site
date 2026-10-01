<?php
// PHP Data Structure defining the content of the dashboard
$portfolio_data = [
    'logistics' => [
        'title' => 'LOGISTICS & TRADE SOLUTIONS',
        'subtitle' => 'Digitizing Trade. Enabling a Smarter, Faster, More Connected Sri Lanka.',
        'class' => 'logistics',
        'color' => '#0066cc',
        'bg' => '#e6f2ff',
        'items' => [
            ['title' => 'Customs Edge', 'desc' => 'Integrated Trade & Customs Platform', 'points' => ['HS classification & duty calculation', 'Regulatory information', 'Shipment management', 'AI-assistant trade intelligence']],
            ['title' => 'Customs ARMS', 'desc' => 'Risk Management System', 'points' => ['Risk profiling & analysis', 'Targeting & compliance support', 'Data-driven decision making']],
            ['title' => 'Customs DAT', 'desc' => 'Data Analytics Tool', 'points' => ['Integrates multiple Customs databases', 'Operational & risk intelligence', 'Dashboards and advanced analytics']],
            ['title' => 'Customs CDNS', 'desc' => 'Document Notification System', 'points' => ['Real-time document notifications', 'Improved operational efficiency', 'Secure & reliable communication']]
        ]
    ],
    'banking' => [
        'title' => 'BANKING & FINANCIAL SERVICES',
        'subtitle' => 'Data-Driven Banking. Smarter Operations. Better Customer Experiences.',
        'class' => 'banking',
        'color' => '#009933',
        'bg' => '#e6ffee',
        'items' => [
            ['title' => 'Amana - Banking', 'desc' => 'Enterprise Analytics & Reporting Platform', 'points' => ['Customer, product & financial analytics', 'Regulatory & risk reporting', 'Executive dashboards', 'Data-driven decision making']]
        ]
    ],
    'insurance' => [
        'title' => 'INSURANCE SOLUTIONS',
        'subtitle' => 'Turning Insurance Data into Actionable Insights.',
        'class' => 'insurance',
        'color' => '#cc0066',
        'bg' => '#ffe6f2',
        'items' => [
            ['title' => 'Allianz - AIR', 'desc' => 'Insight Reporting Platform', 'points' => ['Modern data platform', 'Executive dashboards', 'Claims analytics', 'Cost reduction & efficiency', 'Data governance & quality', 'Regulatory reporting']]
        ]
    ],
    'agriculture' => [
        'title' => 'AGRICULTURE & SUSTAINABILITY',
        'subtitle' => 'Supporting Sustainable Agriculture and Inclusive Growth.',
        'class' => 'agriculture',
        'color' => '#66cc00',
        'bg' => '#f2f9e6',
        'items' => [
            ['title' => 'CSIAP', 'desc' => 'Agri Information Platform', 'points' => ['Market information for farmers', 'Price and demand visibility', 'Supporting sustainable agriculture', 'Connecting value chains']],
            ['title' => 'Ruwan Rekha', 'desc' => 'Digital Marketplace', 'points' => ['Connects farmers and buyers', 'Promotes local products', 'Supports rural livelihoods', 'Digital payments and market access']]
        ]
    ],
    'enterprise' => [
        'title' => 'ENTERPRISE SOLUTIONS',
        'subtitle' => 'Enabling Operational Excellence Across Industries.',
        'class' => 'enterprise',
        'color' => '#6600cc',
        'bg' => '#f3e6ff',
        'items' => [
            ['title' => 'Dilmah - Finance & Accounting', 'desc' => 'Modern Data Platform', 'points' => ['Unified financial data platform', 'Management reporting', 'Operational analytics', 'Better visibility and control']],
            ['title' => 'Customer Data Platform (CDP)', 'desc' => 'Unified Customer View', 'points' => ['360° customer data integration', 'Customer segmentation', 'Personalized engagement', 'Supports multiple industries']]
        ]
    ],
    'data' => [
        'title' => 'DATA & ANALYTICS PLATFORMS',
        'subtitle' => 'Unlocking the Power of Data.',
        'class' => 'data',
        'color' => '#cc0000',
        'bg' => '#ffe6e6',
        'items' => [
            ['title' => 'ETL Accelerator - BMI', 'desc' => 'Data Integration Platform', 'points' => ['Accelerated data integration', 'Pre-built connectors', 'Reduced development time', 'Reliable and scalable ETL']],
            ['title' => 'SDB - Data Migration', 'desc' => 'Data Migration Solution', 'points' => ['Secure and structured migration', 'Minimal business disruption', 'Data validation and reconciliation', 'Supports complex environments']],
            ['title' => 'Forte - Business Intelligence', 'desc' => 'Enterprise BI Platform', 'points' => ['Interactive dashboards', 'Self-service analytics', 'Data visualization', 'Supports multiple business functions']]
        ]
    ],
    'healthcare' => [
        'title' => 'HEALTHCARE & SOCIAL IMPACT',
        'subtitle' => 'Technology for Healthier Communities.',
        'class' => 'healthcare',
        'color' => '#0099cc',
        'bg' => '#e6faff',
        'items' => [
            ['title' => 'SLCP Light', 'desc' => 'Child Health Information Platform', 'points' => ['Child health information for medical professionals and parents', 'Real-time ICU bed availability', 'Pediatric drug information', 'SLCP Academy (digital learning)', 'Knowledge resources and guides']]
        ]
    ],
    'human-capital' => [
        'title' => 'HUMAN CAPITAL MANAGEMENT',
        'subtitle' => 'Empowering People. Enabling Performance.',
        'class' => 'human-capital',
        'color' => '#ff6600',
        'bg' => '#fff2e6',
        'items' => [
            ['title' => 'Forte eHRMS', 'desc' => 'End-to-End HR Management', 'points' => ['Employee lifecycle management', 'Leave, attendance and payroll integration', 'Performance management', 'Self-service portal', 'Analytics and reporting']]
        ]
    ],
    'modern-data' => [
        'title' => 'MODERN DATA PLATFORMS',
        'subtitle' => 'Scalable Platforms for a Data-Driven Future.',
        'class' => 'modern-data',
        'color' => '#003366',
        'bg' => '#e6f0ff',
        'items' => [
            ['title' => 'SriLankan Airlines', 'desc' => 'Modern Data Platform', 'points' => ['Unified enterprise data platform', 'Operational and customer analytics', 'Scalable and secure architecture', 'Supports digital transformation']],
            ['title' => 'Air Astana', 'desc' => 'Modern Data Platform', 'points' => ['Integrated data platform', 'Real-time and historical analytics', 'Enhanced operational visibility', 'Supports data-driven decision making']]
        ]
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Solutions Portfolio</title>
    <style>
        /* CSS Variables for Colors */
        :root {
            --brand-red: #e6007e;
            --text-dark: #333;
            --text-light: #666;
            --bg-gray: #f4f7f6;
            --card-radius: 8px;
        }

        /* Base Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-gray);
            color: var(--text-dark);
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        /* Header Styles */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .header-title h1 {
            color: #1a2a6c;
            margin: 0 0 5px 0;
            font-size: 28px;
        }

        .header-title p {
            margin: 0;
            color: var(--text-light);
            font-size: 14px;
        }

        .header-features {
            display: flex;
            gap: 20px;
        }

        .feature {
            text-align: center;
            font-size: 12px;
            color: #1a2a6c;
            font-weight: 600;
        }

        .feature span {
            display: block;
            font-size: 24px;
            margin-bottom: 5px;
        }

        /* Grid Layout */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 15px;
        }

        /* Card Styles */
        .card {
            border-radius: var(--card-radius);
            padding: 15px;
            border: 1px solid rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            transition: transform 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
        }

        .card-header {
            border-bottom: 2px solid rgba(0,0,0,0.1);
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .card-header h2 {
            margin: 0 0 5px 0;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .card-header p {
            margin: 0;
            font-size: 12px;
            color: var(--text-light);
        }

        /* Item Grid inside cards */
        .items-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 15px;
            flex-grow: 1;
        }

        .item {
            background: #fff;
            border-radius: 6px;
            padding: 10px;
            border: 1px solid #eee;
            display: flex;
            flex-direction: column;
        }

        .item h3 {
            margin: 0 0 5px 0;
            font-size: 13px;
            color: #333;
        }

        .item p {
            margin: 0 0 8px 0;
            font-size: 11px;
            color: var(--text-light);
            font-style: italic;
        }

        .item ul {
            margin: 0;
            padding-left: 15px;
            font-size: 10px;
            color: #555;
            flex-grow: 1;
        }

        .item ul li {
            margin-bottom: 4px;
            line-height: 1.3;
        }

        /* Placeholder for UI Screenshots */
        .screenshot-placeholder {
            height: 70px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 4px;
            margin: 8px 0;
            border: 1px solid #ddd;
            position: relative;
            overflow: hidden;
        }

        .screenshot-placeholder::after {
            content: '';
            position: absolute;
            top: 10%;
            left: 10%;
            right: 10%;
            bottom: 10%;
            background: rgba(255,255,255,0.6);
            border-radius: 2px;
        }

        /* Grid Spans (Mapping to the image layout) */
        .logistics { grid-column: span 6; background-color: #e6f2ff; }
        .logistics .card-header h2 { color: #0066cc; }
        
        .banking { grid-column: span 3; background-color: #e6ffee; }
        .banking .card-header h2 { color: #009933; }

        .insurance { grid-column: span 3; background-color: #ffe6f2; }
        .insurance .card-header h2 { color: #cc0066; }

        .agriculture { grid-column: span 4; background-color: #f2f9e6; }
        .agriculture .card-header h2 { color: #66cc00; }

        /* Central Hub Styling */
        .central-hub {
            grid-column: span 4;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #fff;
            border: none;
        }

        .hub-circle {
            width: 220px;
            height: 220px;
            background: radial-gradient(circle, #1a2a6c, #0d1536);
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: #fff;
            padding: 20px;
            box-shadow: 0 10px 25px rgba(26, 42, 108, 0.4);
            position: relative;
        }

        .hub-circle h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 400;
        }

        .hub-circle h2 {
            margin: 10px 0;
            font-size: 22px;
            font-weight: 700;
            line-height: 1.2;
        }

        .hub-circle p {
            margin: 0;
            font-size: 12px;
            opacity: 0.8;
        }

        .enterprise { grid-column: span 4; background-color: #f3e6ff; }
        .enterprise .card-header h2 { color: #6600cc; }

        .data { grid-column: span 6; background-color: #ffe6e6; }
        .data .card-header h2 { color: #cc0000; }

        .healthcare { grid-column: span 3; background-color: #e6faff; }
        .healthcare .card-header h2 { color: #0099cc; }

        .human-capital { grid-column: span 3; background-color: #fff2e6; }
        .human-capital .card-header h2 { color: #ff6600; }

        /* Footer Section */
        .footer-section {
            grid-column: span 12;
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 15px;
            margin-top: 5px;
        }

        .modern-data { grid-column: span 9; background-color: #e6f0ff; }
        .modern-data .card-header h2 { color: #003366; }

        .capabilities { 
            grid-column: span 3; 
            background-color: #fff; 
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: var(--card-radius);
        }
        .capabilities h2 { 
            color: #003366; 
            font-size: 14px; 
            margin-top: 0;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }
        .capabilities-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 15px;
        }
        .capability-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            font-size: 10px;
            color: #555;
        }
        .capability-item span {
            font-size: 20px;
            margin-bottom: 5px;
            color: #003366;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .logistics, .banking, .insurance, .agriculture, .enterprise, .data, .healthcare, .human-capital, .modern-data {
                grid-column: span 6;
            }
            .central-hub {
                grid-column: span 12;
                order: -1; /* Move hub to top on smaller screens */
                margin-bottom: 20px;
            }
        }

        @media (max-width: 768px) {
            .logistics, .banking, .insurance, .agriculture, .enterprise, .data, .healthcare, .human-capital, .modern-data, .central-hub {
                grid-column: span 12;
            }
            .footer-section {
                grid-template-columns: 1fr;
            }
            .modern-data, .capabilities {
                grid-column: span 1;
            }
            header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <header>
        <div class="header-title">
            <h1>Our Digital Solutions Portfolio</h1>
            <p>Transforming Data into Smarter Decisions for a Better Tomorrow</p>
        </div>
        <div class="header-features">
            <div class="feature"><span>💡</span>Innovative<br>Solutions</div>
            <div class="feature"><span>🤝</span>Trusted<br>Partnerships</div>
            <div class="feature"><span>📊</span>Real Impact<br>Across Sectors</div>
        </div>
    </header>

    <main class="dashboard-grid">
        <?php foreach ($portfolio_data as $key => $section): ?>
            <div class="card <?php echo $section['class']; ?>">
                <div class="card-header">
                    <h2><?php echo $section['title']; ?></h2>
                    <p><?php echo $section['subtitle']; ?></p>
                </div>
                <div class="items-container">
                    <?php foreach ($section['items'] as $item): ?>
                        <div class="item">
                            <h3><?php echo $item['title']; ?></h3>
                            <p><?php echo $item['desc']; ?></p>
                            <div class="screenshot-placeholder"></div>
                            <ul>
                                <?php foreach ($item['points'] as $point): ?>
                                    <li><?php echo $point; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php if ($key === 'agriculture'): ?>
                <!-- Central Hub inserted after Agriculture -->
                <div class="central-hub">
                    <div class="hub-circle">
                        <h3>Creative</h3>
                        <h3>Web Technologies</h3>
                        <h2>OUR DIGITAL<br>SOLUTIONS<br>FOR A BRIGHTER<br>TOMORROW</h2>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>

        <!-- Footer Section (Modern Data & Capabilities) -->
        <div class="footer-section">
            <div class="card modern-data">
                <div class="card-header">
                    <h2>MODERN DATA PLATFORMS</h2>
                    <p>Scalable Platforms for a Data-Driven Future.</p>
                </div>
                <div class="items-container" style="grid-template-columns: 1fr 1fr;">
                    <?php foreach ($portfolio_data['modern-data']['items'] as $item): ?>
                        <div class="item">
                            <h3><?php echo $item['title']; ?></h3>
                            <p><?php echo $item['desc']; ?></p>
                            <div class="screenshot-placeholder" style="height: 50px;"></div>
                            <ul>
                                <?php foreach ($item['points'] as $point): ?>
                                    <li><?php echo $point; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="capabilities">
                <h2>OUR CAPABILITIES<br>POWER ALL SOLUTIONS</h2>
                <div class="capabilities-grid">
                    <div class="capability-item"><span>☁️</span>Cloud<br>Ready</div>
                    <div class="capability-item"><span>🛡️</span>Secure<br>& Compliant</div>
                    <div class="capability-item"><span>⚙️</span>Scalable<br>Architecture</div>
                    <div class="capability-item"><span>📈</span>Advanced<br>Analytics</div>
                    <div class="capability-item"><span>🔗</span>Seamless<br>Integration</div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    // Simple JS to add a subtle interactive effect on the central hub
    document.addEventListener('DOMContentLoaded', () => {
        const hub = document.querySelector('.hub-circle');
        
        if (hub) {
            hub.addEventListener('mouseenter', () => {
                hub.style.transform = 'scale(1.05)';
                hub.style.transition = 'transform 0.3s ease';
            });
            
            hub.addEventListener('mouseleave', () => {
                hub.style.transform = 'scale(1)';
            });
        }

        // Console log to confirm the dashboard is loaded
        console.log('Digital Solutions Portfolio Dashboard Loaded Successfully.');
    });
</script>

</body>
</html>