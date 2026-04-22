-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Creato il: Apr 22, 2026 alle 07:42
-- Versione del server: 8.0.43-0ubuntu0.24.04.2
-- Versione PHP: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `doc`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `accounts`
--

CREATE TABLE `accounts` (
  `id` int NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'default.png',
  `details` text COLLATE utf8mb4_unicode_ci,
  `details_opt` text COLLATE utf8mb4_unicode_ci,
  `auth_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'none',
  `last_login` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `accounts`
--

INSERT INTO `accounts` (`id`, `username`, `password`, `email`, `avatar`, `details`, `details_opt`, `auth_token`, `last_login`) VALUES
(1, 'dmweblab', '$2y$10$EibgaewcRGXQhrMQswfqRuJQeWKMkGxHcWC62HV1g39CmtuU3lG9.', 'davidemasera@gmail.com', 'sd.png', NULL, NULL, 'acaa65bf2d3a3bf25616f9998bdadb6de2970365bb', '2026-04-22 06:36:25'),
(2, 'admin', '$2y$10$ox88LdYM2aFpxP63jJma6.jPi.eCRmgpqQpaQPLtje85sZGvLSf/.', 'dmweblab86@gmail.com', 'default.png', NULL, NULL, 'none', '2026-04-17 11:14:05');

-- --------------------------------------------------------

--
-- Struttura della tabella `accountsRoles`
--

CREATE TABLE `accountsRoles` (
  `id` int NOT NULL,
  `account_id` int NOT NULL,
  `role_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `accountsRoles`
--

INSERT INTO `accountsRoles` (`id`, `account_id`, `role_id`) VALUES
(1, 1, 1),
(2, 2, 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `calendar_cat`
--

CREATE TABLE `calendar_cat` (
  `id` int NOT NULL,
  `cat_name` varchar(255) NOT NULL,
  `cat_color` varchar(7) DEFAULT '#008db1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `calendar_cat`
--

INSERT INTO `calendar_cat` (`id`, `cat_name`, `cat_color`) VALUES
(1, 'default', '#008db1');

-- --------------------------------------------------------

--
-- Struttura della tabella `calendar_events`
--

CREATE TABLE `calendar_events` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `note` text,
  `url` varchar(255) DEFAULT NULL,
  `cat_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `calendar_events`
--

INSERT INTO `calendar_events` (`id`, `title`, `start`, `end`, `note`, `url`, `cat_id`) VALUES
(2, 'Davide Masera', '2026-04-22 12:00:00', '2026-04-22 13:00:00', NULL, '3497408129', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `doc_bookings`
--

CREATE TABLE `doc_bookings` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `slot_booked` datetime NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `doc_settings`
--

CREATE TABLE `doc_settings` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `doc_settings`
--

INSERT INTO `doc_settings` (`id`, `name`, `value`) VALUES
(1, 'email', 'noreply@mail.com'),
(2, 'slot_length', '10');

-- --------------------------------------------------------

--
-- Struttura della tabella `doc_timetable`
--

CREATE TABLE `doc_timetable` (
  `id` int NOT NULL,
  `weekday` int NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `doc_timetable`
--

INSERT INTO `doc_timetable` (`id`, `weekday`, `start_time`, `end_time`) VALUES
(1, 1, '09:00:00', '12:00:00'),
(2, 2, '08:00:00', '11:00:00'),
(3, 3, '14:00:00', '17:00:00'),
(4, 4, '15:00:00', '19:00:00'),
(5, 5, '09:00:00', '11:00:00');

-- --------------------------------------------------------

--
-- Struttura della tabella `doc_timetable_exception`
--

CREATE TABLE `doc_timetable_exception` (
  `id` int NOT NULL,
  `day` date NOT NULL,
  `available` int NOT NULL DEFAULT '0',
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `slot` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `files`
--

CREATE TABLE `files` (
  `id` int NOT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `home`
--

CREATE TABLE `home` (
  `id` int NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `size` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `home`
--

INSERT INTO `home` (`id`, `content`, `size`) VALUES
(1, 'welcome.php', 6),
(2, 'manuals.php', 3),
(3, 'last_login.php', 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `password_reset_temp`
--

CREATE TABLE `password_reset_temp` (
  `email` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `plugins`
--

CREATE TABLE `plugins` (
  `id` int NOT NULL,
  `pluginname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `installed` int DEFAULT '0',
  `active` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `plugins`
--

INSERT INTO `plugins` (`id`, `pluginname`, `description`, `installed`, `active`) VALUES
(1, 'account_register', 'Allow user to register with a simple form', 0, 0),
(2, 'recaptcha', 'Use Google recaptcha to protect your forms', 0, 0),
(3, 'role_redirect', 'Redirect users with a role to a specific page', 0, 0),
(4, 'mini_cms', 'A complete CMS, with page management, galleries and more', 0, 0),
(5, 'calendar', 'Manages events and shows in a calendar', 1, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `roles`
--

CREATE TABLE `roles` (
  `id` int NOT NULL,
  `rolename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `redirect` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'none'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `roles`
--

INSERT INTO `roles` (`id`, `rolename`, `redirect`) VALUES
(1, 'Root', 'none'),
(2, 'Admin', 'none');

-- --------------------------------------------------------

--
-- Struttura della tabella `rolesSection`
--

CREATE TABLE `rolesSection` (
  `id` int NOT NULL,
  `section_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `rolesSection`
--

INSERT INTO `rolesSection` (`id`, `section_id`, `role_id`) VALUES
(1, '1,2,3,4,5,6,7,8,9', 1),
(2, '1,2,3', 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `rolesSectionChild`
--

CREATE TABLE `rolesSectionChild` (
  `id` int NOT NULL,
  `section_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `rolesSectionChild`
--

INSERT INTO `rolesSectionChild` (`id`, `section_id`, `role_id`) VALUES
(1, '1,2,3,4,5,6,7,8,9,10,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23, 25, 26,27,28,28,29,30,31', 1),
(2, '1,2,3,4,5,6,7,8,9,10', 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `sectionChild`
--

CREATE TABLE `sectionChild` (
  `id` int NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` int NOT NULL,
  `show_menu` int DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `sectionChild`
--

INSERT INTO `sectionChild` (`id`, `link`, `label`, `icon`, `parent_id`, `show_menu`) VALUES
(1, 'allAccounts', 'All accounts', 'people-fill', 2, 1),
(2, 'addAccount', 'Add account', 'person-plus-fill', 2, 1),
(3, 'editAccount', 'Edit account', 'icon', 2, 0),
(4, 'allRoles', 'All Roles', 'key-fill', 2, 1),
(5, 'addRole', 'Add role', 'icon', 2, 0),
(6, 'editRole', 'Edit role', 'icon', 2, 0),
(24, 'allBooking', 'All bookings', 'calendar3', 8, 1),
(25, 'addBooking', 'Add booking', 'calendar-plus', 8, 1),
(26, 'bookingSettings', 'Settings', 'gear-wide-connected', 8, 1),
(27, 'allExceptions', 'All Exceptions', 'exclamation-circle', 8, 1),
(28, 'calendar', 'Show calendar', 'calendar3', 9, 1),
(29, 'allCalendars', 'All categories', 'calendar2-range', 9, 1),
(30, 'addCalendar', 'Add a category', 'calendar-plus', 9, 1),
(31, 'editCalendar', 'Edit a category', 'calendar-plus', 9, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `sectionParent`
--

CREATE TABLE `sectionParent` (
  `id` int NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `sectionParent`
--

INSERT INTO `sectionParent` (`id`, `link`, `label`, `icon`) VALUES
(1, 'index', 'Dashboard', 'grid-fill'),
(2, 'accounts', 'Accounts', 'people-fill'),
(3, 'allFiles', 'Files', 'folder-fill'),
(4, 'allSettings', 'Settings', 'tools'),
(5, 'damares', 'Damares', 'dice-6-fill'),
(6, 'allPlugins', 'Modules', 'plus-circle-fill'),
(8, 'booking', 'Booking', 'calendar-week'),
(9, 'calendar', 'Calendar', 'calendar-week');

-- --------------------------------------------------------

--
-- Struttura della tabella `settings`
--

CREATE TABLE `settings` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `settings`
--

INSERT INTO `settings` (`id`, `name`, `value`) VALUES
(1, 'lang', 'it'),
(2, 'noreply', 'noreply@mail.com'),
(3, 'license', 'none'),
(4, 'debug', '1'),
(5, 'layout', 'v'),
(6, 'role_redirect', '0');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `accountsRoles`
--
ALTER TABLE `accountsRoles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indici per le tabelle `calendar_cat`
--
ALTER TABLE `calendar_cat`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `calendar_events`
--
ALTER TABLE `calendar_events`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `doc_bookings`
--
ALTER TABLE `doc_bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `doc_settings`
--
ALTER TABLE `doc_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `doc_timetable`
--
ALTER TABLE `doc_timetable`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `doc_timetable_exception`
--
ALTER TABLE `doc_timetable_exception`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `home`
--
ALTER TABLE `home`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `password_reset_temp`
--
ALTER TABLE `password_reset_temp`
  ADD PRIMARY KEY (`email`);

--
-- Indici per le tabelle `plugins`
--
ALTER TABLE `plugins`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `rolesSection`
--
ALTER TABLE `rolesSection`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `rolesSectionChild`
--
ALTER TABLE `rolesSectionChild`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `sectionChild`
--
ALTER TABLE `sectionChild`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `sectionParent`
--
ALTER TABLE `sectionParent`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `accountsRoles`
--
ALTER TABLE `accountsRoles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `calendar_cat`
--
ALTER TABLE `calendar_cat`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `calendar_events`
--
ALTER TABLE `calendar_events`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `doc_bookings`
--
ALTER TABLE `doc_bookings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `doc_settings`
--
ALTER TABLE `doc_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `doc_timetable`
--
ALTER TABLE `doc_timetable`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `doc_timetable_exception`
--
ALTER TABLE `doc_timetable_exception`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `files`
--
ALTER TABLE `files`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `home`
--
ALTER TABLE `home`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `plugins`
--
ALTER TABLE `plugins`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `rolesSection`
--
ALTER TABLE `rolesSection`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `rolesSectionChild`
--
ALTER TABLE `rolesSectionChild`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `sectionChild`
--
ALTER TABLE `sectionChild`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT per la tabella `sectionParent`
--
ALTER TABLE `sectionParent`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT per la tabella `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `accountsRoles`
--
ALTER TABLE `accountsRoles`
  ADD CONSTRAINT `accountsRoles_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`),
  ADD CONSTRAINT `accountsRoles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
