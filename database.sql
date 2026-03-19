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
