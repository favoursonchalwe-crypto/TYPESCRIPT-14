-- Green Urban Farms (GUF) database schema for XAMPP / phpMyAdmin
-- Import this file in phpMyAdmin to create the database and all tables.

CREATE DATABASE IF NOT EXISTS green_urban_farms
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE green_urban_farms;

-- Website accounts and administrator accounts
CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(190) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('user','admin') NOT NULL DEFAULT 'user',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB;

-- Messages submitted through the Contact Us page
CREATE TABLE IF NOT EXISTS contact_messages (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(190) NOT NULL,
  subject VARCHAR(200) DEFAULT NULL,
  message TEXT NOT NULL,
  status ENUM('new','read','replied','archived') NOT NULL DEFAULT 'new',
  submitted_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_contact_status_date (status, submitted_at)
) ENGINE=InnoDB;

-- Applications from the Get Involved page (volunteers / participants)
CREATE TABLE IF NOT EXISTS volunteer_applications (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id INT UNSIGNED DEFAULT NULL,
  full_name VARCHAR(150) NOT NULL,
  email VARCHAR(190) NOT NULL,
  phone VARCHAR(40) DEFAULT NULL,
  participation_type ENUM('volunteer','community_member','partner','donor','other') NOT NULL DEFAULT 'volunteer',
  interests TEXT DEFAULT NULL,
  availability VARCHAR(150) DEFAULT NULL,
  message TEXT DEFAULT NULL,
  status ENUM('pending','approved','declined','contacted') NOT NULL DEFAULT 'pending',
  submitted_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_volunteer_status_date (status, submitted_at),
  KEY idx_volunteer_user (user_id),
  CONSTRAINT fk_volunteer_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Events displayed on the Events page
CREATE TABLE IF NOT EXISTS events (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  title VARCHAR(200) NOT NULL,
  slug VARCHAR(220) NOT NULL,
  description TEXT NOT NULL,
  location VARCHAR(200) DEFAULT NULL,
  start_datetime DATETIME NOT NULL,
  end_datetime DATETIME DEFAULT NULL,
  image_path VARCHAR(255) DEFAULT NULL,
  registration_url VARCHAR(500) DEFAULT NULL,
  status ENUM('draft','published','cancelled','completed') NOT NULL DEFAULT 'draft',
  created_by INT UNSIGNED DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_events_slug (slug),
  KEY idx_events_status_start (status, start_datetime),
  CONSTRAINT fk_events_creator FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Programs displayed on the Programs page
CREATE TABLE IF NOT EXISTS programs (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  title VARCHAR(200) NOT NULL,
  slug VARCHAR(220) NOT NULL,
  summary VARCHAR(500) DEFAULT NULL,
  description TEXT NOT NULL,
  image_path VARCHAR(255) DEFAULT NULL,
  status ENUM('draft','published','archived') NOT NULL DEFAULT 'draft',
  created_by INT UNSIGNED DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_programs_slug (slug),
  KEY idx_programs_status (status),
  CONSTRAINT fk_programs_creator FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Articles, guides and downloadable materials on the Resources page
CREATE TABLE IF NOT EXISTS resources (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  title VARCHAR(200) NOT NULL,
  slug VARCHAR(220) NOT NULL,
  summary VARCHAR(500) DEFAULT NULL,
  content LONGTEXT DEFAULT NULL,
  resource_type ENUM('article','guide','video','document','external_link','other') NOT NULL DEFAULT 'article',
  file_path VARCHAR(255) DEFAULT NULL,
  external_url VARCHAR(500) DEFAULT NULL,
  status ENUM('draft','published','archived') NOT NULL DEFAULT 'draft',
  created_by INT UNSIGNED DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_resources_slug (slug),
  KEY idx_resources_status (status),
  CONSTRAINT fk_resources_creator FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Optional newsletter sign-up form
CREATE TABLE IF NOT EXISTS newsletter_subscribers (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  email VARCHAR(190) NOT NULL,
  name VARCHAR(150) DEFAULT NULL,
  status ENUM('subscribed','unsubscribed') NOT NULL DEFAULT 'subscribed',
  subscribed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  unsubscribed_at DATETIME DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_newsletter_email (email),
  KEY idx_newsletter_status (status)
) ENGINE=InnoDB;

-- Optional registrations for events
CREATE TABLE IF NOT EXISTS event_registrations (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  event_id INT UNSIGNED NOT NULL,
  user_id INT UNSIGNED DEFAULT NULL,
  full_name VARCHAR(150) NOT NULL,
  email VARCHAR(190) NOT NULL,
  phone VARCHAR(40) DEFAULT NULL,
  registration_status ENUM('registered','cancelled','attended','no_show') NOT NULL DEFAULT 'registered',
  registered_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_event_registration_email (event_id, email),
  KEY idx_registration_user (user_id),
  CONSTRAINT fk_registration_event FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
  CONSTRAINT fk_registration_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Security note: create the first admin account using password_hash() in PHP.
-- Do not insert a plain-text password into users.password_hash.
