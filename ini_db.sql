
CREATE DATABASE IF NOT EXISTS itcs333 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE itcs333;

-- users table: teacher/admin and students
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  student_id VARCHAR(50) DEFAULT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','student') NOT NULL DEFAULT 'student',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- create default admin (password: ChangeMe123!)
INSERT INTO users (name, student_id, email, password, role)
VALUES ('Course Teacher','T000','admin@course.local',
  -- password hash for ChangeMe123!
  '$2y$10$wH8oR12uJ8s1b6c0Q6kGROJYb6JY3pQwX3rXQ7f6yqv5TnZkL2G6',
  'admin')
ON DUPLICATE KEY UPDATE email=email;