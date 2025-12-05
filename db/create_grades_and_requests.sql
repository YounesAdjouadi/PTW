-- Drop existing (development/dev only) so import creates the correct schema
DROP TABLE IF EXISTS `grades`;
DROP TABLE IF EXISTS `transcript_requests`;

-- Create grades table with fullname, major, cc, exam and a generated average column
CREATE TABLE `grades` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `matricule` VARCHAR(50) NOT NULL,
  `fullname` VARCHAR(255) NOT NULL,
  `major` VARCHAR(150) DEFAULT NULL,
  `module` VARCHAR(255) NOT NULL,
  `cc` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
  `exam` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
  `average` DECIMAL(6,2) AS (ROUND((`cc` * 0.4) + (`exam` * 0.6),2)) STORED,
  `term` VARCHAR(50) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX (`matricule`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `transcript_requests` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_matricule` VARCHAR(50) NOT NULL,
  `note` TEXT DEFAULT NULL,
  `status` ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `requested_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX (`student_matricule`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample data (development only) - includes fullname and major
INSERT INTO `grades` (`matricule`, `fullname`, `major`, `module`, `cc`, `exam`, `term`) VALUES
('202500000001', 'Alice Bensaid', 'Mechanical Engineering', 'Mathematics I', 14.50, 12.00, '2025-1'),
('202500000001', 'Alice Bensaid', 'Mechanical Engineering', 'Physics I', 15.00, 16.00, '2025-1'),
('202500000002', 'Yacine Rahmani', 'Process Engineering', 'Mathematics I', 10.00, 11.50, '2025-1');

INSERT INTO `transcript_requests` (`student_matricule`, `note`, `status`) VALUES
('202500000001', 'Request for official transcript to be sent to employer.', 'pending'),
('202500000002', 'Need transcript for internship application.', 'pending');
