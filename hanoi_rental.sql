CREATE DATABASE IF NOT EXISTS hihi_db
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE hihi_db;

-- -----------------------------------------------------
-- USERS TABLE
-- -----------------------------------------------------
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    phone VARCHAR(20),
    zalo VARCHAR(20),
    avatar VARCHAR(255),
    role ENUM('admin','landlord','renter') DEFAULT 'renter',
    status VARCHAR(20) DEFAULT 'active',
    is_admin TINYINT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- POSTS TABLE
-- -----------------------------------------------------
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    status ENUM('pending','approved','rejected') DEFAULT 'pending',
    reviewed_by INT,
    reviewed_at TIMESTAMP NULL DEFAULT NULL,
    title VARCHAR(255),
    type VARCHAR(50),
    khu_vuc VARCHAR(255),
    price INT,
    phone VARCHAR(20),
    zalo VARCHAR(20),
    description TEXT,
    status_rent TINYINT DEFAULT 0,  -- thêm theo yêu cầu PHP
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_posts_user FOREIGN KEY (user_id)
        REFERENCES users(id) ON DELETE CASCADE,

    CONSTRAINT fk_posts_reviewer FOREIGN KEY (reviewed_by)
        REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- POST IMAGES TABLE
-- -----------------------------------------------------
CREATE TABLE post_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    image_path VARCHAR(255),
    filename VARCHAR(255),

    CONSTRAINT fk_post_images_post FOREIGN KEY (post_id)
        REFERENCES posts(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- RENTAL REQUESTS TABLE
-- -----------------------------------------------------
CREATE TABLE rental_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    tenant_id INT NOT NULL,
    fullname VARCHAR(255),
    birthday VARCHAR(50),
    phone VARCHAR(20),
    gmail VARCHAR(255),
    cccd VARCHAR(50),
    address VARCHAR(255),
    status ENUM('pending','accepted','rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_rental_post FOREIGN KEY (post_id)
        REFERENCES posts(id) ON DELETE CASCADE,

    CONSTRAINT fk_rental_user FOREIGN KEY (tenant_id)
        REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- NOTIFICATIONS TABLE
INSERT INTO users (id, name, email, password, role, status)
VALUES (5, 'Landlord', 'landlord@test.com', '123', 'landlord', 'active');
CREATE TABLE rental (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
ALTER TABLE posts MODIFY price DECIMAL(10,2);
CREATE TABLE rent_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    post_id INT NOT NULL,
    status ENUM('pending','approved','rejected') DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    approved_at DATETIME NULL
); 
ALTER TABLE posts MODIFY price VARCHAR(50) NOT NULL;

-- -----------------------------------------------------
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_notifications_user FOREIGN KEY (user_id)
        REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

