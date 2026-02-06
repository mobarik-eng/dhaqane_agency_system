USE dhaqane_db;

-- Add new columns for Employee management if they don't exist
ALTER TABLE users ADD COLUMN fullname VARCHAR(100) AFTER username;
ALTER TABLE users ADD COLUMN phone VARCHAR(20) AFTER fullname;
ALTER TABLE users ADD COLUMN photo VARCHAR(255) AFTER phone;

-- Ensure upload directory exists
