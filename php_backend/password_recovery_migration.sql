-- Run this once in phpMyAdmin with green_urban selected.
-- Add phone to users for SMS recovery. Existing accounts can add their number later.
ALTER TABLE users ADD COLUMN phone VARCHAR(20) NULL;
CREATE INDEX idx_users_phone ON users (phone);

CREATE TABLE IF NOT EXISTS password_resets (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id INT UNSIGNED NOT NULL,
  method ENUM('email','sms') NOT NULL,
  secret_hash CHAR(64) NOT NULL,
  expires_at DATETIME NOT NULL,
  used_at DATETIME NULL,
  request_ip VARCHAR(45) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_password_resets_user (user_id),
  KEY idx_password_resets_secret (secret_hash),
  CONSTRAINT fk_password_resets_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
