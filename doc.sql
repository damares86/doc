-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mag 07, 2026 alle 16:52
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.2.12

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
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT 'default.png',
  `details` text DEFAULT NULL,
  `details_opt` text DEFAULT NULL,
  `auth_token` varchar(255) DEFAULT 'none',
  `last_login` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `accounts`
--

INSERT INTO `accounts` (`id`, `username`, `password`, `email`, `avatar`, `details`, `details_opt`, `auth_token`, `last_login`) VALUES
(1, 'dmweblab', '$2y$10$EibgaewcRGXQhrMQswfqRuJQeWKMkGxHcWC62HV1g39CmtuU3lG9.', 'davidemasera@gmail.com', 'sd.png', NULL, NULL, 'acaa65bf2d3a3bf25616f9998bdadb6d7e92c97cb8', '2026-05-07 13:44:15'),
(2, 'admin', '$2y$10$ox88LdYM2aFpxP63jJma6.jPi.eCRmgpqQpaQPLtje85sZGvLSf/.', 'dmweblab86@gmail.com', 'default.png', NULL, NULL, 'none', '2026-04-17 11:14:05');

-- --------------------------------------------------------

--
-- Struttura della tabella `accountsroles`
--

CREATE TABLE `accountsroles` (
  `id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `accountsroles`
--

INSERT INTO `accountsroles` (`id`, `account_id`, `role_id`) VALUES
(1, 1, 1),
(2, 2, 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `doc_bookings`
--

CREATE TABLE `doc_bookings` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `notes` text NOT NULL,
  `slot_booked` datetime NOT NULL,
  `token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `doc_settings`
--

CREATE TABLE `doc_settings` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL
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
  `id` int(11) NOT NULL,
  `weekday` int(11) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `booking` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `doc_timetable`
--

INSERT INTO `doc_timetable` (`id`, `weekday`, `start_time`, `end_time`, `booking`) VALUES
(1, 1, '09:00:00', '12:00:00', 0),
(2, 2, '08:00:00', '11:00:00', 1),
(3, 3, '14:00:00', '17:00:00', 1),
(4, 4, '15:00:00', '19:00:00', 1),
(5, 5, '09:00:00', '11:00:00', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `doc_timetable_exception`
--

CREATE TABLE `doc_timetable_exception` (
  `id` int(11) NOT NULL,
  `day` date NOT NULL,
  `available` int(11) NOT NULL DEFAULT 0,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `slot` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `home`
--

CREATE TABLE `home` (
  `id` int(11) NOT NULL,
  `content` text DEFAULT NULL,
  `size` int(11) NOT NULL
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
  `email` varchar(250) NOT NULL,
  `token` varchar(250) NOT NULL,
  `expDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `plugins`
--

CREATE TABLE `plugins` (
  `id` int(11) NOT NULL,
  `pluginname` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `installed` int(11) DEFAULT 0,
  `active` int(11) DEFAULT 0
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
  `id` int(11) NOT NULL,
  `rolename` varchar(255) NOT NULL,
  `redirect` varchar(255) DEFAULT 'none'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `roles`
--

INSERT INTO `roles` (`id`, `rolename`, `redirect`) VALUES
(1, 'Root', 'none'),
(2, 'Admin', 'none');

-- --------------------------------------------------------

--
-- Struttura della tabella `rolessection`
--

CREATE TABLE `rolessection` (
  `id` int(11) NOT NULL,
  `section_id` varchar(255) NOT NULL,
  `role_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `rolessection`
--

INSERT INTO `rolessection` (`id`, `section_id`, `role_id`) VALUES
(1, '1,2,3,4,5,6,7,8,9', 1),
(2, '1,2,3', 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `rolessectionchild`
--

CREATE TABLE `rolessectionchild` (
  `id` int(11) NOT NULL,
  `section_id` varchar(255) NOT NULL,
  `role_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `rolessectionchild`
--

INSERT INTO `rolessectionchild` (`id`, `section_id`, `role_id`) VALUES
(1, '1,2,3,4,5,6,7,8,9,10,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23, 25, 26,27,28,28,29,30,31', 1),
(2, '1,2,3,4,5,6,7,8,9,10', 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `sectionchild`
--

CREATE TABLE `sectionchild` (
  `id` int(11) NOT NULL,
  `link` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `show_menu` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `sectionchild`
--

INSERT INTO `sectionchild` (`id`, `link`, `label`, `icon`, `parent_id`, `show_menu`) VALUES
(1, 'allAccounts', 'All accounts', 'people-fill', 2, 1),
(2, 'addAccount', 'Add account', 'person-plus-fill', 2, 1),
(3, 'editAccount', 'Edit account', 'icon', 2, 0),
(4, 'allRoles', 'All Roles', 'key-fill', 2, 1),
(5, 'addRole', 'Add role', 'icon', 2, 0),
(6, 'editRole', 'Edit role', 'icon', 2, 0),
(24, 'allBooking', 'All bookings', 'calendar3', 8, 1),
(25, 'addBooking', 'Add booking', 'calendar-plus', 8, 1),
(26, 'bookingSettings', 'Settings', 'gear-wide-connected', 8, 1),
(27, 'allExceptions', 'All Exceptions', 'exclamation-circle', 8, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `sectionparent`
--

CREATE TABLE `sectionparent` (
  `id` int(11) NOT NULL,
  `link` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `sectionparent`
--

INSERT INTO `sectionparent` (`id`, `link`, `label`, `icon`) VALUES
(1, 'index', 'Dashboard', 'grid-fill'),
(2, 'accounts', 'Accounts', 'people-fill'),
(3, 'allFiles', 'Files', 'folder-fill'),
(4, 'allSettings', 'Settings', 'tools'),
(5, 'damares', 'Damares', 'dice-6-fill'),
(6, 'allPlugins', 'Modules', 'plus-circle-fill'),
(8, 'booking', 'Booking', 'calendar-week');

-- --------------------------------------------------------

--
-- Struttura della tabella `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL
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
-- Indici per le tabelle `accountsroles`
--
ALTER TABLE `accountsroles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `role_id` (`role_id`);

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
-- Indici per le tabelle `sectionchild`
--
ALTER TABLE `sectionchild`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `sectionparent`
--
ALTER TABLE `sectionparent`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `sectionchild`
--
ALTER TABLE `sectionchild`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT per la tabella `sectionparent`
--
ALTER TABLE `sectionparent`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
