-- ============================================
-- DATABASE SETUP FOR BIKINEVENT.MY.ID
-- Event Management & Certificate System
-- ============================================

-- Create database
CREATE DATABASE IF NOT EXISTS `event_management` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `event_management`;

-- ============================================
-- TABLE: users
-- Menyimpan data pengguna (admin & peserta)
-- ============================================
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','participant') NOT NULL DEFAULT 'participant',
  `name` varchar(255) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- TABLE: events
-- Menyimpan data event
-- ============================================
CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `location` varchar(255) NOT NULL,
  `max_participants` int(11) DEFAULT NULL,
  `institution_name` varchar(255) DEFAULT NULL,
  `certificate_number` varchar(100) DEFAULT NULL,
  `organizer_name` varchar(255) DEFAULT NULL,
  `organizer_role` varchar(255) DEFAULT NULL,
  `institution_logo` varchar(255) DEFAULT NULL,
  `organizer_signature` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- TABLE: participants
-- Menyimpan data peserta event
-- ============================================
CREATE TABLE IF NOT EXISTS `participants` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `event_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `event_id` (`event_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `participants_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  CONSTRAINT `participants_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- INSERT DEFAULT DATA
-- ============================================

-- Insert default admin user
-- Email: admin@bikinevent.my.id
-- Password: admin123
INSERT INTO `users` (`email`, `password`, `role`, `name`, `phone_number`) VALUES
('admin@bikinevent.my.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'Administrator', '081234567890');

-- Insert default participant user
-- Email: peserta@bikinevent.my.id
-- Password: peserta123
INSERT INTO `users` (`email`, `password`, `role`, `name`, `phone_number`) VALUES
('peserta@bikinevent.my.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'participant', 'Peserta Demo', '081234567891');

-- Insert sample event
INSERT INTO `events` (`title`, `description`, `start_date`, `end_date`, `location`, `max_participants`, `institution_name`, `certificate_number`, `organizer_name`, `organizer_role`) VALUES
('Workshop Web Development 2024', 'Workshop intensif tentang pengembangan web modern menggunakan PHP, CodeIgniter 4, dan MySQL. Peserta akan belajar membuat aplikasi web dari awal hingga deployment.', '2024-01-15 09:00:00', '2024-01-15 17:00:00', 'Gedung Serbaguna Universitas', 50, 'UNIVERSITAS CONTOH', '001/CERT/WS/2024', 'Dr. John Doe, M.Kom', 'Ketua Panitia'),
('Seminar Teknologi AI & Machine Learning', 'Seminar nasional tentang perkembangan terkini Artificial Intelligence dan Machine Learning dalam industri 4.0', '2024-02-20 08:00:00', '2024-02-20 16:00:00', 'Auditorium Kampus', 100, 'INSTITUT TEKNOLOGI INDONESIA', '002/CERT/SEM/2024', 'Prof. Jane Smith, Ph.D', 'Ketua Pelaksana'),
('Pelatihan Digital Marketing', 'Pelatihan komprehensif tentang strategi digital marketing untuk meningkatkan bisnis online', '2024-03-10 13:00:00', '2024-03-12 17:00:00', 'Hotel Grand Ballroom', 75, 'ASOSIASI DIGITAL MARKETING INDONESIA', '003/CERT/PLT/2024', 'Budi Santoso, S.E., M.M', 'Koordinator Pelatihan');

-- ============================================
-- NOTES
-- ============================================
-- Default password untuk semua user: admin123 atau peserta123
-- Password di-hash menggunakan bcrypt
-- 
-- Untuk membuat password baru, gunakan:
-- password_hash('password_anda', PASSWORD_DEFAULT)
-- 
-- Struktur folder upload yang diperlukan:
-- - public/uploads/institutions/ (untuk logo institusi)
-- - public/uploads/signatures/ (untuk tanda tangan)
-- - public/uploads/temp/ (untuk file temporary)
-- ============================================

