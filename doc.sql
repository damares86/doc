-- phpMyAdmin SQL Dump
-- Versione del database per doc (Damares CMS)
-- Formato tabelle standard: snake_case

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Struttura della tabella `accounts`
--

CREATE TABLE IF NOT EXISTS `accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT 'default.png',
  `details` text DEFAULT NULL,
  `details_opt` text DEFAULT NULL,
  `auth_token` varchar(255) DEFAULT 'none',
  `last_login` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `accounts` (`id`, `username`, `password`, `email`, `avatar`, `details`, `details_opt`, `auth_token`, `last_login`) VALUES
(1, 'dmweblab', '$2y$10$EibgaewcRGXQhrMQswfqRuJQeWKMkGxHcWC62HV1g39CmtuU3lG9.', 'davidemasera@gmail.com', 'sd.png', NULL, NULL, 'none', '2026-05-07 13:44:15'),
(2, 'admin', '$2y$10$ox88LdYM2aFpxP63jJma6.jPi.eCRmgpqQpaQPLtje85sZGvLSf/.', 'dmweblab86@gmail.com', 'default.png', NULL, NULL, 'none', '2026-04-17 11:14:05')
ON DUPLICATE KEY UPDATE `username`=VALUES(`username`);

-- --------------------------------------------------------

--
-- Struttura della tabella `accounts_roles`
--

CREATE TABLE IF NOT EXISTS `accounts_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `account_id` (`account_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `accounts_roles` (`id`, `account_id`, `role_id`) VALUES
(1, 1, 1),
(2, 2, 2)
ON DUPLICATE KEY UPDATE `role_id`=VALUES(`role_id`);

-- --------------------------------------------------------

--
-- Struttura della tabella `doc_bookings`
--

CREATE TABLE IF NOT EXISTS `doc_bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `reason_id` int(11) DEFAULT 1,
  `notes` text NOT NULL,
  `slot_booked` datetime NOT NULL,
  `token` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `slot_booked` (`slot_booked`),
  KEY `token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `doc_settings`
--

CREATE TABLE IF NOT EXISTS `doc_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `doc_settings` (`id`, `name`, `value`) VALUES
(1, 'email', 'noreply@mail.com'),
(2, 'slot_length', '10')
ON DUPLICATE KEY UPDATE `value`=VALUES(`value`);

-- --------------------------------------------------------

--
-- Struttura della tabella `doc_timetable`
--

CREATE TABLE IF NOT EXISTS `doc_timetable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weekday` int(11) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `booking` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `weekday` (`weekday`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `doc_timetable` (`id`, `weekday`, `start_time`, `end_time`, `booking`) VALUES
(1, 1, '09:00:00', '12:00:00', 0),
(2, 2, '08:00:00', '11:00:00', 1),
(3, 3, '14:00:00', '17:00:00', 1),
(4, 4, '15:00:00', '19:00:00', 1),
(5, 5, '09:00:00', '11:00:00', 1)
ON DUPLICATE KEY UPDATE `start_time`=VALUES(`start_time`), `end_time`=VALUES(`end_time`), `booking`=VALUES(`booking`);

-- --------------------------------------------------------

--
-- Struttura della tabella `doc_timetable_exception`
--

CREATE TABLE IF NOT EXISTS `doc_timetable_exception` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `day` date NOT NULL,
  `available` int(11) NOT NULL DEFAULT 0,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `slot` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `day` (`day`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `doc_reason`
--

CREATE TABLE IF NOT EXISTS `doc_reason` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `doc_reason` (`id`, `name`) VALUES
(1, 'Visita medica'),
(2, 'Controllo esami/referti'),
(3, 'Certificato medico'),
(4, 'Altro')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- --------------------------------------------------------

--
-- Struttura della tabella `doc_contact_reason`
--

CREATE TABLE IF NOT EXISTS `doc_contact_reason` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `doc_contact_reason` (`id`, `name`) VALUES
(1, 'Richiesta farmaci continuativi'),
(2, 'Invio referti per visione'),
(3, 'Richiesta informazioni'),
(4, 'Altro')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- --------------------------------------------------------

--
-- Struttura della tabella `doc_messages`
--

CREATE TABLE IF NOT EXISTS `doc_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `msg` text DEFAULT NULL,
  `start_time` date DEFAULT NULL,
  `end_time` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `doc_messages` (`id`, `msg`, `start_time`, `end_time`) VALUES
(1, '', '2026-01-01', '2026-12-31')
ON DUPLICATE KEY UPDATE `id`=1;

-- --------------------------------------------------------

--
-- Struttura della tabella `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `home`
--

CREATE TABLE IF NOT EXISTS `home` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text DEFAULT NULL,
  `size` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `home` (`id`, `content`, `size`) VALUES
(1, 'booking.php', 4),
(2, 'addBooking.php', 4),
(3, 'settings.php', 4),
(4, 'messages.php', 4),
(5, 'welcome.php', 4),
(6, 'last_login.php', 4)
ON DUPLICATE KEY UPDATE `content`=VALUES(`content`);

-- --------------------------------------------------------

--
-- Struttura della tabella `password_reset_temp`
--

CREATE TABLE IF NOT EXISTS `password_reset_temp` (
  `email` varchar(250) NOT NULL,
  `token` varchar(250) NOT NULL,
  `expDate` datetime NOT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `plugins`
--

CREATE TABLE IF NOT EXISTS `plugins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pluginname` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `installed` int(11) DEFAULT 0,
  `active` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `plugins` (`id`, `pluginname`, `description`, `installed`, `active`) VALUES
(1, 'account_register', 'Allow user to register with a simple form', 0, 0),
(2, 'recaptcha', 'Use Google recaptcha to protect your forms', 0, 0),
(3, 'role_redirect', 'Redirect users with a role to a specific page', 0, 0),
(4, 'mini_cms', 'A complete CMS, with page management, galleries and more', 0, 0),
(5, 'calendar', 'Manages events and shows in a calendar', 1, 1)
ON DUPLICATE KEY UPDATE `pluginname`=VALUES(`pluginname`);

-- --------------------------------------------------------

--
-- Struttura della tabella `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rolename` varchar(255) NOT NULL,
  `redirect` varchar(255) DEFAULT 'none',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `roles` (`id`, `rolename`, `redirect`) VALUES
(1, 'Root', 'none'),
(2, 'Admin', 'none')
ON DUPLICATE KEY UPDATE `rolename`=VALUES(`rolename`);

-- --------------------------------------------------------

--
-- Struttura della tabella `roles_section`
--

CREATE TABLE IF NOT EXISTS `roles_section` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section_id` varchar(255) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `roles_section` (`id`, `section_id`, `role_id`) VALUES
(1, '1,2,3,4,5,6,7,8,9', 1),
(2, '1,2,3', 2)
ON DUPLICATE KEY UPDATE `section_id`=VALUES(`section_id`);

-- --------------------------------------------------------

--
-- Struttura della tabella `roles_section_child`
--

CREATE TABLE IF NOT EXISTS `roles_section_child` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section_id` varchar(255) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `roles_section_child` (`id`, `section_id`, `role_id`) VALUES
(1, '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27', 1),
(2, '1,2,3,4,5,6,7,8,9,10', 2)
ON DUPLICATE KEY UPDATE `section_id`=VALUES(`section_id`);

-- --------------------------------------------------------

--
-- Struttura della tabella `section_child`
--

CREATE TABLE IF NOT EXISTS `section_child` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `link` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `show_menu` int(11) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `section_child` (`id`, `link`, `label`, `icon`, `parent_id`, `show_menu`) VALUES
(1, 'allAccounts', 'All accounts', 'people-fill', 2, 1),
(2, 'addAccount', 'Add account', 'person-plus-fill', 2, 1),
(3, 'editAccount', 'Edit account', 'icon', 2, 0),
(4, 'allRoles', 'All Roles', 'key-fill', 2, 1),
(5, 'addRole', 'Add role', 'icon', 2, 0),
(6, 'editRole', 'Edit role', 'icon', 2, 0),
(24, 'allBooking', 'All bookings', 'calendar3', 8, 1),
(25, 'addBooking', 'Add booking', 'calendar-plus', 8, 1),
(26, 'bookingSettings', 'Settings', 'gear-wide-connected', 8, 1),
(27, 'addMessage', 'Add message', 'exclamation-circle', 8, 1)
ON DUPLICATE KEY UPDATE `link`=VALUES(`link`);

-- --------------------------------------------------------

--
-- Struttura della tabella `section_parent`
--

CREATE TABLE IF NOT EXISTS `section_parent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `link` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `section_parent` (`id`, `link`, `label`, `icon`) VALUES
(1, 'index', 'Dashboard', 'grid-fill'),
(2, 'accounts', 'Accounts', 'people-fill'),
(3, 'allFiles', 'Files', 'folder-fill'),
(4, 'allSettings', 'Settings', 'tools'),
(5, 'damares', 'Damares', 'dice-6-fill'),
(6, 'allPlugins', 'Modules', 'plus-circle-fill'),
(8, 'booking', 'Booking', 'calendar-week')
ON DUPLICATE KEY UPDATE `link`=VALUES(`link`);

-- --------------------------------------------------------

--
-- Struttura della tabella `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings` (`id`, `name`, `value`) VALUES
(1, 'lang', 'it'),
(2, 'noreply', 'noreply@mail.com'),
(3, 'license', 'none'),
(4, 'debug', '0'),
(5, 'layout', 'v'),
(6, 'role_redirect', '0')
ON DUPLICATE KEY UPDATE `value`=VALUES(`value`);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
