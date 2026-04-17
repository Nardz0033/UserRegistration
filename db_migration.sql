-- Run this once against your MariaDB userdb database.
-- It creates the users table (if missing) and adds new columns
-- required by the updated application.

CREATE TABLE IF NOT EXISTS users (
    user_id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email             VARCHAR(255) NOT NULL UNIQUE,
    pass              VARCHAR(255) NOT NULL,
    first_name        VARCHAR(100) NOT NULL,
    last_name         VARCHAR(100) NOT NULL,
    active            TINYINT(1) NOT NULL DEFAULT 1,
    user_level        VARCHAR(20) NOT NULL DEFAULT 'user',
    registration_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    password_reset_token   VARCHAR(64) DEFAULT NULL,
    password_reset_expires DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- If the table already exists, add the two new columns safely:
ALTER TABLE users
    ADD COLUMN IF NOT EXISTS password_reset_token   VARCHAR(64) DEFAULT NULL,
    ADD COLUMN IF NOT EXISTS password_reset_expires DATETIME DEFAULT NULL;

-- Existing passwords were stored as SHA1 hashes.
-- After running this migration, users must reset their passwords
-- (or an admin can manually re-hash them) because the app now
-- uses bcrypt via password_hash(). Accounts with old SHA1 hashes
-- will simply fail login until the password is reset.
