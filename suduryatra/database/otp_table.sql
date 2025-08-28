-- Create OTP storage table for Sudur Yatra
-- Run this SQL in your phpMyAdmin or MySQL client

CREATE TABLE IF NOT EXISTS `otp_storage` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `email` varchar(255) NOT NULL,
    `otp` varchar(6) NOT NULL,
    `expiry_time` datetime NOT NULL,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `email_index` (`email`),
    INDEX `expiry_index` (`expiry_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Optional: Add email configuration for better email delivery
-- You may need to configure your PHP mail settings in php.ini
-- For better email delivery, consider using PHPMailer or similar library

-- Sample data for testing (optional)
-- INSERT INTO `otp_storage` (`email`, `otp`, `expiry_time`) VALUES
-- ('test@example.com', '123456', DATE_ADD(NOW(), INTERVAL 10 MINUTE));
