-- Journey Karo — MySQL Schema
-- PHP 8.x | Hostinger Premium | utf8mb4

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS `journeykaro_db`
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `journeykaro_db`;

-- ─── Users (admin) ─────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(64) NOT NULL,
  `email` VARCHAR(191) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `name` VARCHAR(120) NOT NULL DEFAULT '',
  `role` ENUM('admin','editor') NOT NULL DEFAULT 'admin',
  `status` ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `remember_token` VARCHAR(100) DEFAULT NULL,
  `last_login` DATETIME DEFAULT NULL,
  `last_ip` VARCHAR(45) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_users_username` (`username`),
  UNIQUE KEY `uk_users_email` (`email`),
  KEY `idx_users_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Login attempts (brute force) ──────────────────────────
CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(64) NOT NULL,
  `ip_address` VARCHAR(45) NOT NULL,
  `attempted_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_login_username_ip` (`username`, `ip_address`),
  KEY `idx_login_attempted_at` (`attempted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Destinations ──────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `destinations` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(191) NOT NULL,
  `slug` VARCHAR(191) NOT NULL,
  `icon` VARCHAR(16) DEFAULT NULL,
  `short_description` TEXT,
  `description` LONGTEXT,
  `attractions` TEXT COMMENT 'JSON array or newline list',
  `highlights` TEXT COMMENT 'JSON array',
  `best_time` VARCHAR(120) DEFAULT NULL,
  `climate` VARCHAR(80) DEFAULT NULL,
  `duration_label` VARCHAR(40) DEFAULT NULL,
  `starting_price` INT UNSIGNED DEFAULT 0,
  `featured_image` VARCHAR(500) DEFAULT NULL,
  `gallery` TEXT COMMENT 'JSON array of image paths',
  `meta_title` VARCHAR(191) DEFAULT NULL,
  `meta_description` VARCHAR(320) DEFAULT NULL,
  `meta_keywords` VARCHAR(255) DEFAULT NULL,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `sort_order` INT NOT NULL DEFAULT 0,
  `status` ENUM('active','inactive','draft') NOT NULL DEFAULT 'active',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_destinations_slug` (`slug`),
  KEY `idx_destinations_status` (`status`),
  KEY `idx_destinations_featured` (`is_featured`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Packages ──────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `packages` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `destination_id` INT UNSIGNED DEFAULT NULL,
  `name` VARCHAR(191) NOT NULL,
  `slug` VARCHAR(191) NOT NULL,
  `short_description` TEXT,
  `description` LONGTEXT,
  `category` VARCHAR(80) DEFAULT NULL,
  `days` TINYINT UNSIGNED NOT NULL DEFAULT 1,
  `nights` TINYINT UNSIGNED NOT NULL DEFAULT 0,
  `price` INT UNSIGNED NOT NULL DEFAULT 0,
  `rating` DECIMAL(2,1) DEFAULT 4.8,
  `review_count` INT UNSIGNED DEFAULT 0,
  `featured_image` VARCHAR(500) DEFAULT NULL,
  `gallery` TEXT,
  `inclusions` TEXT COMMENT 'JSON array',
  `exclusions` TEXT COMMENT 'JSON array',
  `meta_title` VARCHAR(191) DEFAULT NULL,
  `meta_description` VARCHAR(320) DEFAULT NULL,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `sort_order` INT NOT NULL DEFAULT 0,
  `status` ENUM('active','inactive','draft') NOT NULL DEFAULT 'active',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_packages_slug` (`slug`),
  KEY `idx_packages_destination` (`destination_id`),
  KEY `idx_packages_status` (`status`),
  KEY `idx_packages_featured` (`is_featured`),
  CONSTRAINT `fk_packages_destination`
    FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Itineraries ───────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `itineraries` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `package_id` INT UNSIGNED NOT NULL,
  `day_number` TINYINT UNSIGNED NOT NULL DEFAULT 1,
  `title` VARCHAR(191) NOT NULL,
  `description` LONGTEXT,
  `activities` TEXT COMMENT 'JSON array',
  `meals` VARCHAR(120) DEFAULT NULL,
  `accommodation` VARCHAR(191) DEFAULT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_itineraries_package` (`package_id`),
  KEY `idx_itineraries_day` (`package_id`, `day_number`),
  CONSTRAINT `fk_itineraries_package`
    FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Blogs ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `blogs` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(191) NOT NULL,
  `slug` VARCHAR(191) NOT NULL,
  `excerpt` TEXT,
  `content` LONGTEXT,
  `featured_image` VARCHAR(500) DEFAULT NULL,
  `author_name` VARCHAR(120) DEFAULT NULL,
  `category` VARCHAR(80) DEFAULT NULL,
  `tags` VARCHAR(255) DEFAULT NULL,
  `read_time_minutes` TINYINT UNSIGNED DEFAULT 5,
  `meta_title` VARCHAR(191) DEFAULT NULL,
  `meta_description` VARCHAR(320) DEFAULT NULL,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `status` ENUM('draft','published','archived') NOT NULL DEFAULT 'draft',
  `published_at` DATETIME DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_blogs_slug` (`slug`),
  KEY `idx_blogs_status` (`status`),
  KEY `idx_blogs_published` (`published_at`),
  KEY `idx_blogs_category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Reviews ───────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `reviewer_name` VARCHAR(120) NOT NULL,
  `reviewer_email` VARCHAR(191) DEFAULT NULL,
  `reviewer_image` VARCHAR(500) DEFAULT NULL,
  `rating` TINYINT UNSIGNED NOT NULL DEFAULT 5,
  `destination` VARCHAR(120) DEFAULT NULL,
  `package_name` VARCHAR(191) DEFAULT NULL,
  `review_text` TEXT NOT NULL,
  `status` ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_reviews_status` (`status`),
  KEY `idx_reviews_rating` (`rating`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Gallery ───────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `gallery` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(191) NOT NULL,
  `image_path` VARCHAR(500) NOT NULL,
  `category` VARCHAR(80) DEFAULT 'general',
  `destination_id` INT UNSIGNED DEFAULT NULL,
  `alt_text` VARCHAR(191) DEFAULT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `status` ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_gallery_category` (`category`),
  KEY `idx_gallery_destination` (`destination_id`),
  KEY `idx_gallery_status` (`status`),
  CONSTRAINT `fk_gallery_destination`
    FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Inquiries / Leads ─────────────────────────────────────
CREATE TABLE IF NOT EXISTS `inquiries` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(120) NOT NULL,
  `email` VARCHAR(191) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `destination` VARCHAR(191) DEFAULT NULL,
  `package_name` VARCHAR(191) DEFAULT NULL,
  `travel_date` DATE DEFAULT NULL,
  `num_guests` INT UNSIGNED DEFAULT 1,
  `budget` VARCHAR(80) DEFAULT NULL,
  `message` TEXT,
  `source` VARCHAR(80) DEFAULT 'website',
  `status` ENUM('new','contacted','quotation_sent','confirmed','lost') NOT NULL DEFAULT 'new',
  `admin_notes` TEXT,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_inquiries_status` (`status`),
  KEY `idx_inquiries_created` (`created_at`),
  KEY `idx_inquiries_email` (`email`),
  KEY `idx_inquiries_phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── SEO Meta (per page slug) ──────────────────────────────
CREATE TABLE IF NOT EXISTS `seo_meta` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `page_slug` VARCHAR(120) NOT NULL,
  `entity_type` VARCHAR(40) DEFAULT 'page',
  `entity_id` INT UNSIGNED DEFAULT NULL,
  `meta_title` VARCHAR(191) DEFAULT NULL,
  `meta_description` VARCHAR(320) DEFAULT NULL,
  `meta_keywords` VARCHAR(255) DEFAULT NULL,
  `canonical_url` VARCHAR(500) DEFAULT NULL,
  `og_title` VARCHAR(191) DEFAULT NULL,
  `og_description` VARCHAR(320) DEFAULT NULL,
  `og_image` VARCHAR(500) DEFAULT NULL,
  `twitter_card` VARCHAR(40) DEFAULT 'summary_large_image',
  `schema_json` LONGTEXT,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_seo_page_slug` (`page_slug`),
  KEY `idx_seo_entity` (`entity_type`, `entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Settings (key-value) ──────────────────────────────────
CREATE TABLE IF NOT EXISTS `settings` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `setting_key` VARCHAR(120) NOT NULL,
  `setting_value` TEXT,
  `setting_group` VARCHAR(60) DEFAULT 'general',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_settings_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
