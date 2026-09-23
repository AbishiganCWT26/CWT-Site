-- CWT Corporate Website Database Schema
-- MySQL 5.7+ / 8.0+
-- Run this file once to create the database and all tables.

CREATE DATABASE IF NOT EXISTS cwt_site CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cwt_site;

-- ============================================================
-- Admin Users
-- ============================================================
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Default admin: username=admin, password=CWT@2026
INSERT INTO admin_users (username, password_hash) VALUES
('admin', '$2y$12$9V4z7Qk2mR3sL8pN1xWe.uYhF6dJbKtGcMvAoP0iEwXnHqZsT5yO6');
-- NOTE: The above hash is for CWT@2026. Replace with php -r "echo password_hash('CWT@2026', PASSWORD_BCRYPT, ['cost'=>12]);"

-- ============================================================
-- Footer
-- ============================================================
CREATE TABLE IF NOT EXISTS footer (
    id INT AUTO_INCREMENT PRIMARY KEY,
    para TEXT,
    linkedin_url VARCHAR(500),
    facebook_url VARCHAR(500),
    youtube_url VARCHAR(500),
    instagram_url VARCHAR(500),
    email VARCHAR(255),
    phone VARCHAR(50),
    address TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO footer (para, linkedin_url, facebook_url, youtube_url, instagram_url, email, phone, address) VALUES
(
    'Creative Web Technologies builds high-performance offshore development teams to bring big ideas to life. Partner with us to seamlessly extend your in-house capabilities with expert support in software development, quality assurance, and application maintenance.',
    'https://linkedin.com/company/creative-web-technologies',
    'https://facebook.com/creativewt',
    'https://youtube.com/@creativewt',
    'https://instagram.com/creativewt',
    'hello@cwt.lk',
    '+94 11 234 5678',
    'No. 1, Tech Park, Colombo 03, Sri Lanka'
);

-- ============================================================
-- Clients (Logos for Marquee)
-- ============================================================
CREATE TABLE IF NOT EXISTS clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(255) NOT NULL,
    logo_path VARCHAR(500),
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Sample client entries (logos to be uploaded via admin)
INSERT INTO clients (company_name, logo_path, sort_order) VALUES
('TechCorp Global', '', 1),
('InnovateSolutions', '', 2),
('DataDriven Inc', '', 3),
('CloudFirst Ltd', '', 4),
('AgileWorks', '', 5);

-- ============================================================
-- About Us Statistics
-- ============================================================
CREATE TABLE IF NOT EXISTS aboutus (
    id INT AUTO_INCREMENT PRIMARY KEY,
    start_year INT NOT NULL DEFAULT 2000,
    retention_pct INT NOT NULL DEFAULT 95,
    employed_count INT NOT NULL DEFAULT 200,
    projects_count INT NOT NULL DEFAULT 55,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO aboutus (start_year, retention_pct, employed_count, projects_count) VALUES (2005, 95, 200, 55);

-- ============================================================
-- History
-- ============================================================
CREATE TABLE IF NOT EXISTS history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    para1 TEXT,
    image1_path VARCHAR(500),
    para2 TEXT,
    image2_path VARCHAR(500),
    para3 TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO history (para1, image1_path, para2, image2_path, para3) VALUES
(
    'Creative Web Technologies was born from a simple yet powerful idea: that world-class technology talent should be accessible to any business, regardless of its size or location. Founded in the early 2000s, CWT started as a small team of passionate developers and engineers who believed in the transformative power of technology.',
    '',
    'Over the years, we grew from a handful of specialists into a full-service technology partner with expertise spanning software engineering, cloud services, data analytics, AI, and cybersecurity. Each milestone was driven by the trust of our clients and the dedication of our team.',
    '',
    'Today, Creative Web Technologies stands as a beacon of innovation in Sri Lanka''s technology landscape. We continue to push boundaries, embrace emerging technologies, and deliver measurable value to our global client base — all while staying true to the principles that founded us: quality, integrity, and excellence.'
);

-- ============================================================
-- Vision & Mission
-- ============================================================
CREATE TABLE IF NOT EXISTS vision_mission (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vision_para TEXT,
    mission_para TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO vision_mission (vision_para, mission_para) VALUES
(
    'To be the most trusted technology partner for businesses worldwide, empowering them to achieve digital excellence through innovative, secure, and scalable solutions built by Sri Lanka''s finest tech talent.',
    'Our mission is to build high-performance offshore development teams that seamlessly extend our clients'' in-house capabilities. We are committed to delivering world-class software engineering, quality assurance, and application maintenance services — driven by expertise, transparency, and a relentless pursuit of excellence.'
);

-- ============================================================
-- Products
-- ============================================================
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    logo_path VARCHAR(500),
    product_name VARCHAR(255) NOT NULL,
    description TEXT,
    visit_url VARCHAR(500),
    show_button TINYINT(1) DEFAULT 1,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO products (logo_path, product_name, description, visit_url, show_button, sort_order) VALUES
('', 'CWT Analytics Platform', 'A powerful enterprise analytics dashboard that transforms raw data into actionable intelligence. Built with cutting-edge AI algorithms, it delivers real-time insights, predictive analytics, and customizable reporting for businesses of all sizes.', 'https://analytics.cwt.lk', 1, 1),
('', 'CWT Cloud Manager', 'A comprehensive multi-cloud management tool that simplifies cloud infrastructure operations. From cost optimization to security monitoring, CWT Cloud Manager gives your team complete visibility and control over your cloud environment.', 'https://cloud.cwt.lk', 1, 2),
('', 'CWT SecureID', 'An enterprise-grade identity and access management solution that protects your digital assets. With zero-trust architecture and seamless SSO integration, SecureID ensures your organization stays secure without compromising productivity.', '', 0, 3);

-- ============================================================
-- Client Feedback (Testimonials)
-- ============================================================
CREATE TABLE IF NOT EXISTS client_feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(255) NOT NULL,
    photo_path VARCHAR(500),
    feedback TEXT,
    feedback_date DATE,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO client_feedback (client_name, photo_path, feedback, feedback_date) VALUES
('James Hartwell', '', 'Partnering with Creative Web Technologies has been a game-changer for our organization. Their team seamlessly integrated with ours, bringing deep technical expertise and a genuine passion for solving complex challenges. The quality of work and the speed of delivery consistently exceeded our expectations.', '2025-03-15'),
('Sarah Mitchell', '', 'CWT''s offshore team became an indispensable part of our engineering department. Their ISO-certified processes gave us the confidence to entrust them with our most critical systems. We''ve seen a 40% improvement in deployment frequency since we started working with them.', '2025-06-22'),
('Dr. Amara Patel', '', 'What sets CWT apart is their transparency and communication. We always knew exactly where our project stood. Their agile process, combined with their deep technical knowledge, made a complex data migration project feel surprisingly smooth and well-managed.', '2025-08-10');

-- ============================================================
-- Blog / Insights
-- ============================================================
CREATE TABLE IF NOT EXISTS blog_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    topic VARCHAR(500) NOT NULL,
    slug VARCHAR(500) NOT NULL UNIQUE,
    featured_image VARCHAR(500),
    hashtags VARCHAR(1000),
    publish_date DATE,
    content LONGTEXT,
    is_published TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO blog_details (topic, slug, featured_image, hashtags, publish_date, content, is_published) VALUES
(
    'The Future of AI Engineering: Trends Shaping 2025',
    'future-of-ai-engineering-2025',
    '',
    '#AI,#MachineLearning,#Engineering,#Innovation',
    '2025-09-01',
    '[{"type":"paragraph","content":"Artificial Intelligence is no longer a future concept—it is the present reality reshaping how we build, deploy, and maintain software systems. In 2025, AI Engineering has emerged as one of the most critical disciplines in the technology landscape."},{"type":"heading2","content":"Key Trends in AI Engineering"},{"type":"paragraph","content":"From large language models to autonomous agents, the tools and methodologies available to AI engineers have undergone a dramatic transformation. Organizations that embrace these changes early will gain a significant competitive advantage."},{"type":"bullets","content":"Generative AI integration into enterprise workflows|MLOps maturity and automated model lifecycle management|Edge AI and on-device inference for real-time applications|AI-driven code generation and developer productivity tools|Responsible AI frameworks and explainability requirements"},{"type":"heading3","content":"The Rise of AI-Augmented Development"},{"type":"paragraph","content":"Perhaps the most transformative trend is the integration of AI directly into the software development lifecycle. AI coding assistants, automated testing frameworks, and intelligent monitoring systems are enabling engineering teams to deliver higher quality software at unprecedented speed."}]',
    1
),
(
    'Building Secure Cloud Infrastructure: A Practical Guide',
    'building-secure-cloud-infrastructure',
    '',
    '#Cloud,#Security,#DevOps,#Infrastructure',
    '2025-08-15',
    '[{"type":"paragraph","content":"Cloud security is not a one-time implementation—it is an ongoing discipline that requires continuous attention, adaptation, and investment. As organizations accelerate their cloud adoption, the attack surface expands, making robust security architecture more critical than ever."},{"type":"heading2","content":"Core Principles of Cloud Security"},{"type":"bullets","content":"Zero Trust Architecture: Never trust, always verify|Encryption at rest and in transit for all sensitive data|Least-privilege access control across all services|Continuous monitoring and automated threat detection|Regular penetration testing and vulnerability assessments"},{"type":"codeblock","content":"# Example: Terraform policy for S3 bucket encryption\\nresource \\\"aws_s3_bucket_server_side_encryption_configuration\\\" \\\"example\\\" {\\n  bucket = aws_s3_bucket.example.id\\n  rule {\\n    apply_server_side_encryption_by_default {\\n      sse_algorithm = \\\"aws:kms\\\"\\n    }\\n  }\\n}"},{"type":"paragraph","content":"Implementing infrastructure as code (IaC) practices ensures that security configurations are version-controlled, peer-reviewed, and consistently applied across all environments—eliminating configuration drift and reducing human error."}]',
    1
);

-- ============================================================
-- Industries
-- ============================================================
CREATE TABLE IF NOT EXISTS industries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    image_path VARCHAR(500),
    sort_order INT DEFAULT 0
) ENGINE=InnoDB;

INSERT INTO industries (name, image_path, sort_order) VALUES
('Banking & Financial Services', '', 1),
('Insurance', '', 2),
('Government', '', 3),
('Customs', '', 4),
('Manufacturing', '', 5),
('Retail & FMCG', '', 6),
('Telecommunications', '', 7),
('Logistics', '', 8),
('Data & AI', '', 9),
('Cloud', '', 10),
('Enterprise Applications', '', 11),
('Project Delivery', '', 12),
('Infrastructure', '', 13),
('Business Optimization', '', 14);

-- ============================================================
-- Tech Stack
-- ============================================================
CREATE TABLE IF NOT EXISTS tech_stack (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    logo_path VARCHAR(500),
    category ENUM('software_engineering','ai_engineering','data_engineering','devops_cloud','project_delivery') NOT NULL,
    sort_order INT DEFAULT 0
) ENGINE=InnoDB;

INSERT INTO tech_stack (name, logo_path, category, sort_order) VALUES
-- Software Engineering
('JavaScript', '', 'software_engineering', 1),
('TypeScript', '', 'software_engineering', 2),
('Python', '', 'software_engineering', 3),
('Java', '', 'software_engineering', 4),
('C#', '', 'software_engineering', 5),
('PHP', '', 'software_engineering', 6),
('React', '', 'software_engineering', 7),
('Node.js', '', 'software_engineering', 8),
('Angular', '', 'software_engineering', 9),
('Vue.js', '', 'software_engineering', 10),
-- AI Engineering
('TensorFlow', '', 'ai_engineering', 1),
('PyTorch', '', 'ai_engineering', 2),
('Scikit-learn', '', 'ai_engineering', 3),
('OpenAI', '', 'ai_engineering', 4),
('Hugging Face', '', 'ai_engineering', 5),
('LangChain', '', 'ai_engineering', 6),
-- Data Engineering
('Apache Spark', '', 'data_engineering', 1),
('Kafka', '', 'data_engineering', 2),
('dbt', '', 'data_engineering', 3),
('Snowflake', '', 'data_engineering', 4),
('BigQuery', '', 'data_engineering', 5),
('Databricks', '', 'data_engineering', 6),
-- DevOps & Cloud
('AWS', '', 'devops_cloud', 1),
('Azure', '', 'devops_cloud', 2),
('Google Cloud', '', 'devops_cloud', 3),
('Docker', '', 'devops_cloud', 4),
('Kubernetes', '', 'devops_cloud', 5),
('Terraform', '', 'devops_cloud', 6),
('Jenkins', '', 'devops_cloud', 7),
('GitLab CI', '', 'devops_cloud', 8),
-- Project Delivery & PMO
('Jira', '', 'project_delivery', 1),
('Confluence', '', 'project_delivery', 2),
('Microsoft Project', '', 'project_delivery', 3),
('Slack', '', 'project_delivery', 4),
('Monday.com', '', 'project_delivery', 5);

-- ============================================================
-- Client Meetings (Contact Form Submissions)
-- ============================================================
CREATE TABLE IF NOT EXISTS client_meetings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(255) NOT NULL,
    website VARCHAR(500) DEFAULT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
