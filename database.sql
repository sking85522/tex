-- Create database
CREATE DATABASE IF NOT EXISTS tech_elevate_x;
USE tech_elevate_x;

-- Admins Table
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert Default Admin (Password: admin123)
INSERT INTO admins (username, password) VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi') ON DUPLICATE KEY UPDATE username='admin';

-- Services Table
CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    icon_class VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert Demo Services
INSERT INTO services (title, description, icon_class) VALUES
('Web Development', 'Responsive, fast, and SEO-friendly websites using modern frameworks.', 'fas fa-laptop-code'),
('App Development', 'Native iOS, Android, and cross-platform mobile applications.', 'fas fa-mobile-alt'),
('Custom Software', 'Tailor-made software solutions, ERPs, and CRMs.', 'fas fa-code'),
('UI/UX Design', 'Intuitive, user-centric interfaces and engaging experiences.', 'fas fa-paint-brush'),
('SEO & Marketing', 'Data-driven digital marketing strategies and SEO optimization.', 'fas fa-search-dollar'),
('Cloud Hosting', 'Secure, scalable, and reliable cloud hosting solutions.', 'fas fa-server');

-- Chatbot FAQ Table
CREATE TABLE IF NOT EXISTS chatbot_faqs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question VARCHAR(255) NOT NULL,
    answer TEXT NOT NULL,
    keywords VARCHAR(255) NOT NULL
);

-- Insert Demo Chatbot FAQs
INSERT INTO chatbot_faqs (question, answer, keywords) VALUES
('What services do you offer?', 'We offer Web Development, App Development, Custom Software, UI/UX Design, and SEO & Marketing.', 'services,offer,do'),
('How much does a website cost?', 'The cost of a website depends on your requirements. Please use our contact form for a detailed quote.', 'cost,price,much'),
('How long does it take?', 'Project timelines vary based on complexity, but an average website takes 4-6 weeks.', 'time,long,how'),
('Where are you located?', 'We are located at 123 Tech Street, IT Park, City.', 'location,where,address'),
('Hello / Hi', 'Hello! Welcome to Tech Elevate X. How can I assist you today?', 'hi,hello,hey,greetings');

-- Messages Table (from Contact Form)
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`id`, `username`, `password`, `email`) VALUES
(1, 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user@example.com'); -- Password is 'password'


-- Staff Management Extension
ALTER TABLE `admins` ADD COLUMN `role` ENUM('super_admin', 'hr', 'chat_support') NOT NULL DEFAULT 'super_admin' AFTER `password`;

-- Dynamic Pricing Extension for Services
ALTER TABLE `services` ADD COLUMN `price_inr` DECIMAL(10,2) NOT NULL DEFAULT 0.00 AFTER `description`;
ALTER TABLE `services` ADD COLUMN `price_usd` DECIMAL(10,2) NOT NULL DEFAULT 0.00 AFTER `price_inr`;

UPDATE `services` SET `price_inr` = 9999.00, `price_usd` = 199.00 WHERE `id` = 1;
UPDATE `services` SET `price_inr` = 14999.00, `price_usd` = 299.00 WHERE `id` = 2;
UPDATE `services` SET `price_inr` = 19999.00, `price_usd` = 399.00 WHERE `id` = 3;
UPDATE `services` SET `price_inr` = 7999.00, `price_usd` = 149.00 WHERE `id` = 4;
UPDATE `services` SET `price_inr` = 24999.00, `price_usd` = 499.00 WHERE `id` = 5;
UPDATE `services` SET `price_inr` = 4999.00, `price_usd` = 99.00 WHERE `id` = 6;

-- Site Settings (CMS)
CREATE TABLE IF NOT EXISTS `site_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL UNIQUE,
  `setting_value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `site_settings` (`setting_key`, `setting_value`) VALUES
('home_hero_title', 'Transform Your Business With Tech Elevate X'),
('home_hero_subtitle', 'We provide world-class web development, software solutions, and IT services to scale your business to new heights.'),
('about_heading', 'About Tech Elevate X'),
('about_content', 'Tech Elevate X is a premier software development and IT consulting company. We specialize in building custom web applications, mobile apps, and enterprise software solutions.'),
('contact_email', 'info@techelevatex.com'),
('contact_phone', '+1 234 567 8900'),
('contact_address', '123 Tech Street, IT Park, City');

-- Portfolio / Demos
CREATE TABLE IF NOT EXISTS `portfolio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `category` varchar(100) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `demo_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add some mock portfolio data
INSERT INTO `portfolio` (`title`, `description`, `category`, `image_url`, `demo_url`) VALUES
('E-Commerce Platform', 'A fully featured e-commerce platform built for scale.', 'Web Dev', 'https://via.placeholder.com/400x300', '#'),
('Inventory Management ERP', 'Enterprise resource planning software for manufacturing.', 'Software Dev', 'https://via.placeholder.com/400x300', '#'),
('Food Delivery App', 'Real-time tracking food delivery application.', 'App Dev', 'https://via.placeholder.com/400x300', '#'),
('Real Estate Portal', 'Property listing and management system.', 'Web Dev', 'https://via.placeholder.com/400x300', '#');

-- Careers / Jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `requirements` text NOT NULL,
  `location` varchar(100) NOT NULL,
  `job_type` varchar(50) NOT NULL DEFAULT 'Full-time',
  `status` ENUM('open', 'closed') NOT NULL DEFAULT 'open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `jobs` (`title`, `description`, `requirements`, `location`, `job_type`, `status`) VALUES
('Senior PHP Developer', 'We are looking for an experienced PHP developer to join our backend team.', '5+ years PHP, MySQL, API Development', 'Remote', 'Full-time', 'open'),
('Frontend React Engineer', 'Join us to build futuristic UI/UX interfaces.', '3+ years React, HTML, CSS, Playwright', 'On-site', 'Full-time', 'open'),
('Chat Support Agent', 'Handle client queries and manage live chat sessions.', 'Excellent communication skills, typing speed 50wpm.', 'Remote', 'Part-time', 'open');

-- Job Applications
CREATE TABLE IF NOT EXISTS `job_applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `resume_url` varchar(255) NOT NULL,
  `cover_letter` text DEFAULT NULL,
  `status` ENUM('pending', 'reviewed', 'accepted', 'rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`job_id`) REFERENCES `jobs`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Live Chat System
CREATE TABLE IF NOT EXISTS `chat_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_token` varchar(100) NOT NULL UNIQUE,
  `user_name` varchar(100) DEFAULT 'Guest',
  `status` ENUM('open', 'closed') NOT NULL DEFAULT 'open',
  `assigned_to` int(11) DEFAULT NULL, -- Admin ID (chat_support)
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`assigned_to`) REFERENCES `admins`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `chat_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` int(11) NOT NULL,
  `sender_type` ENUM('user', 'staff') NOT NULL,
  `sender_id` int(11) DEFAULT NULL, -- NULL if user, Admin ID if staff
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`session_id`) REFERENCES `chat_sessions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- System migrations tracking table
CREATE TABLE IF NOT EXISTS `system_migrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `migration_file` varchar(255) NOT NULL UNIQUE,
  `executed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
