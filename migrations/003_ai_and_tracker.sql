-- Fix the original missing columns in services table if they don't exist
-- MySQL doesn't have an IF NOT EXISTS for ADD COLUMN natively, so we wrap it in a procedure or just run the query and let autoupdate.php ignore the error if it already exists

-- Blogs & SEO Content
CREATE TABLE IF NOT EXISTS `blogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL UNIQUE,
  `content` longtext NOT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `meta_description` varchar(255) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `author` varchar(100) NOT NULL DEFAULT 'AI Engine',
  `status` ENUM('draft', 'published') NOT NULL DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Client Live Project Tracker
CREATE TABLE IF NOT EXISTS `client_projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `project_name` varchar(200) NOT NULL,
  `description` text,
  `status_phase` varchar(100) NOT NULL DEFAULT 'Requirement Gathering',
  `progress_percent` int(3) NOT NULL DEFAULT 0,
  `total_cost` decimal(10,2) DEFAULT 0.00,
  `paid_amount` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- AI Knowledge Base (Self-Learning Memory)
CREATE TABLE IF NOT EXISTS `ai_knowledge` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic` varchar(200) NOT NULL,
  `learned_content` text NOT NULL,
  `confidence_score` int(3) DEFAULT 50, -- 0 to 100
  `last_used` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- AI Lead Tracking (Sales Deal Closing)
CREATE TABLE IF NOT EXISTS `ai_leads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_ip` varchar(50) DEFAULT NULL,
  `detected_language` varchar(20) DEFAULT 'en',
  `interest_topic` varchar(100) DEFAULT NULL,
  `converted` boolean DEFAULT false,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
