-- Shilo Production Database Schema
-- MySQL Database Setup

CREATE DATABASE IF NOT EXISTS `shilo_production` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `shilo_production`;

-- Users Table
CREATE TABLE `users` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) UNIQUE NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin', 'editor') DEFAULT 'editor',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_email` (`email`),
  INDEX `idx_role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Services Table
CREATE TABLE `services` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) UNIQUE NOT NULL,
  `description` TEXT,
  `image` VARCHAR(255),
  `price` DECIMAL(10, 2),
  `duration` VARCHAR(100),
  `features` JSON,
  `featured` BOOLEAN DEFAULT FALSE,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_status` (`status`),
  INDEX `idx_featured` (`featured`),
  INDEX `idx_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Portfolio Table
CREATE TABLE `portfolio` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) UNIQUE NOT NULL,
  `description` TEXT,
  `category` ENUM('Wedding', 'Events', 'Portrait', 'Corporate', 'Commercial', 'Product', 'Music', 'Documentary', 'Promotional', 'Other') DEFAULT 'Other',
  `client` VARCHAR(255),
  `location` VARCHAR(255),
  `project_date` DATE,
  `cover_image` VARCHAR(255),
  `featured` BOOLEAN DEFAULT FALSE,
  `status` ENUM('published', 'draft') DEFAULT 'draft',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_status` (`status`),
  INDEX `idx_category` (`category`),
  INDEX `idx_featured` (`featured`),
  INDEX `idx_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Portfolio Images Table
CREATE TABLE `portfolio_images` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `portfolio_id` INT NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  `caption` TEXT,
  `sort_order` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`portfolio_id`) REFERENCES `portfolio` (`id`) ON DELETE CASCADE,
  INDEX `idx_portfolio_id` (`portfolio_id`),
  INDEX `idx_sort_order` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Videos Table
CREATE TABLE `videos` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `thumbnail` VARCHAR(255),
  `video_url` VARCHAR(500) NOT NULL,
  `video_type` ENUM('youtube', 'vimeo', 'local') DEFAULT 'youtube',
  `category` VARCHAR(100),
  `client` VARCHAR(255),
  `project_date` DATE,
  `featured` BOOLEAN DEFAULT FALSE,
  `status` ENUM('published', 'draft') DEFAULT 'draft',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_status` (`status`),
  INDEX `idx_featured` (`featured`),
  INDEX `idx_video_type` (`video_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bookings Table
CREATE TABLE `bookings` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `reference_no` VARCHAR(50) UNIQUE,
  `full_name` VARCHAR(255) NOT NULL,
  `company` VARCHAR(255),
  `email` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `service_id` INT,
  `project_type` VARCHAR(255),
  `event_date` DATE,
  `location` VARCHAR(255),
  `participants` INT,
  `budget` DECIMAL(10, 2),
  `description` TEXT,
  `status` ENUM('pending', 'reviewing', 'quoted', 'confirmed', 'completed', 'cancelled', 'rejected') DEFAULT 'pending',
  `admin_notes` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL,
  INDEX `idx_status` (`status`),
  INDEX `idx_email` (`email`),
  INDEX `idx_reference` (`reference_no`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Quote Requests Table
CREATE TABLE `quote_requests` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `full_name` VARCHAR(255) NOT NULL,
  `company` VARCHAR(255),
  `email` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `service_id` INT,
  `project_type` VARCHAR(255),
  `location` VARCHAR(255),
  `budget` DECIMAL(10, 2),
  `deadline` DATE,
  `description` TEXT,
  `status` ENUM('pending', 'reviewed', 'quoted', 'accepted', 'rejected') DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL,
  INDEX `idx_status` (`status`),
  INDEX `idx_email` (`email`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Quotes Table
CREATE TABLE `quotes` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `quote_request_id` INT NOT NULL,
  `amount` DECIMAL(10, 2) NOT NULL,
  `description` TEXT,
  `terms` TEXT,
  `valid_until` DATE,
  `status` ENUM('draft', 'sent', 'accepted', 'rejected') DEFAULT 'draft',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`quote_request_id`) REFERENCES `quote_requests` (`id`) ON DELETE CASCADE,
  INDEX `idx_status` (`status`),
  INDEX `idx_request_id` (`quote_request_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Contact Messages Table
CREATE TABLE `contact_messages` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20),
  `subject` VARCHAR(255),
  `message` TEXT NOT NULL,
  `is_read` BOOLEAN DEFAULT FALSE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_is_read` (`is_read`),
  INDEX `idx_email` (`email`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Testimonials Table
CREATE TABLE `testimonials` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `client_name` VARCHAR(255) NOT NULL,
  `company` VARCHAR(255),
  `position` VARCHAR(255),
  `message` TEXT NOT NULL,
  `rating` INT DEFAULT 5,
  `image` VARCHAR(255),
  `approved` BOOLEAN DEFAULT FALSE,
  `featured` BOOLEAN DEFAULT FALSE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_approved` (`approved`),
  INDEX `idx_featured` (`featured`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Blog Posts Table
CREATE TABLE `blog_posts` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) UNIQUE NOT NULL,
  `summary` TEXT,
  `content` LONGTEXT NOT NULL,
  `cover_image` VARCHAR(255),
  `category` VARCHAR(100),
  `author` VARCHAR(255),
  `tags` JSON,
  `status` ENUM('published', 'draft') DEFAULT 'draft',
  `published_at` DATETIME,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_status` (`status`),
  INDEX `idx_slug` (`slug`),
  INDEX `idx_category` (`category`),
  INDEX `idx_published_at` (`published_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Team Members Table
CREATE TABLE `team_members` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `position` VARCHAR(255),
  `bio` TEXT,
  `image` VARCHAR(255),
  `facebook` VARCHAR(255),
  `instagram` VARCHAR(255),
  `linkedin` VARCHAR(255),
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Site Settings Table
CREATE TABLE `site_settings` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `setting_key` VARCHAR(255) UNIQUE NOT NULL,
  `value` LONGTEXT,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Activity Logs Table
CREATE TABLE `activity_logs` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT,
  `action` VARCHAR(255) NOT NULL,
  `details` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  INDEX `idx_user_id` (`user_id`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert Default Admin User
-- Email: admin@shilo.com
-- Password: Shilo@2026 (must be changed immediately)
INSERT INTO `users` (`name`, `email`, `password`, `role`, `created_at`) VALUES
('Admin User', 'admin@shilo.com', '$2y$12$q0DzOZ6j8eQ9kcW7.K0XQOwzQ1QxQf5QX1d8L5d5Q0Q0Q0Q0Q0Q0Q', 'admin', NOW());

-- Insert Default Settings
INSERT INTO `site_settings` (`setting_key`, `value`) VALUES
('company_name', 'Shilo Production'),
('phone', '+251-911-123-456'),
('email', 'contact@shiloproduction.com'),
('address', 'Addis Ababa, Ethiopia'),
('hero_title', 'We Capture Stories. We Create Experiences.'),
('hero_description', 'Professional photography, videography and creative media production for events, brands, people and unforgettable moments.'),
('about_text', 'Shilo Production is a premier media production company specializing in photography, videography, and creative storytelling.'),
('footer_text', '© 2026 Shilo Production. All rights reserved.'),
('facebook', 'https://facebook.com/shiloproduction'),
('instagram', 'https://instagram.com/shiloproduction'),
('youtube', 'https://youtube.com/@shiloproduction'),
('tiktok', 'https://tiktok.com/@shiloproduction'),
('telegram', 'https://t.me/shiloproduction'),
('whatsapp', '+251911123456'),
('working_hours', 'Monday - Friday: 9:00 AM - 6:00 PM'),
('google_maps', 'https://maps.google.com/');

-- Insert Sample Services
INSERT INTO `services` (`title`, `slug`, `description`, `price`, `duration`, `features`, `status`) VALUES
('Wedding Photography', 'wedding-photography', 'Professional wedding photography capturing your special moments', 500, '8 hours', '["Engagement session", "Full day coverage", "500+ edited photos", "Digital album"]', 'active'),
('Corporate Video Production', 'corporate-video', 'High-quality corporate videos for your business needs', 2000, '2 weeks', '["Concept development", "Professional crew", "4K video", "Color grading"]', 'active'),
('Event Coverage', 'event-coverage', 'Complete event photography and videography services', 1000, '6 hours', '["Multi-camera setup", "Live streaming", "Same-day highlights", "Full footage"]', 'active'),
('Product Photography', 'product-photography', 'Stunning product photography for e-commerce and marketing', 300, '1 day', '["Professional lighting", "Multiple angles", "Retouching", "High resolution"]', 'active');
