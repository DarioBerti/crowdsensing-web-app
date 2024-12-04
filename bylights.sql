-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Dic 04, 2024 alle 11:15
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
-- Database: `bylights`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(64) NOT NULL,
  `id_profile_img` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `badge`
--

CREATE TABLE `badge` (
  `badge_id` int(11) NOT NULL,
  `nameBadge` varchar(50) NOT NULL,
  `description` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `path`
--

CREATE TABLE `path` (
  `path_id` int(11) NOT NULL,
  `brightness` double NOT NULL,
  `view_user_id` int(11) DEFAULT NULL,
  `del_admin_id` int(11) DEFAULT NULL,
  `Ana_admin_id` int(11) DEFAULT NULL,
  `Rec_user_id` int(11) NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `path_time` varchar(8) NOT NULL,
  `path_date` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `path`
--

INSERT INTO `path` (`path_id`, `brightness`, `view_user_id`, `del_admin_id`, `Ana_admin_id`, `Rec_user_id`, `latitude`, `longitude`, `path_time`, `path_date`) VALUES
(2, 108.6146848819509, 1, NULL, NULL, 1, 45.0527232, 7.6382208, '0.27', '2024-12-03'),
(3, 85.73595060420453, 1, NULL, NULL, 1, 45.0527232, 7.6382208, '0.31', '2024-12-03'),
(4, 117.96743437166658, 1, NULL, NULL, 1, 45.0527232, 7.6382208, '0.09', '2024-12-03');

-- --------------------------------------------------------

--
-- Struttura della tabella `profile_image`
--

CREATE TABLE `profile_image` (
  `id_profile_img` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `session`
--

CREATE TABLE `session` (
  `session_id` int(11) NOT NULL,
  `session_user_id` int(11) DEFAULT NULL,
  `session_admin_id` int(11) DEFAULT NULL,
  `data` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `last_activity` datetime NOT NULL,
  `expires_at` datetime NOT NULL
) ;

-- --------------------------------------------------------

--
-- Struttura della tabella `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(64) NOT NULL,
  `dateRegistration` date NOT NULL,
  `BadgesAcquired` int(11) DEFAULT NULL,
  `id_profile_img` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `user`
--

INSERT INTO `user` (`user_id`, `username`, `name`, `surname`, `email`, `password`, `dateRegistration`, `BadgesAcquired`, `id_profile_img`) VALUES
(1, 'darioberti', 'dario', 'berti', 'dario@berti', '$2y$10$/m7h/wcL1.39vzVB7DNwB.mDgA8ofwQij7u.Xl5nlg7bTyBdIF81u', '2024-12-03', 0, NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `user_badge`
--

CREATE TABLE `user_badge` (
  `user_id_earner` int(11) NOT NULL,
  `badge_id_acquired` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `ID_ADMIN_IND` (`admin_id`),
  ADD KEY `FKadmin_img_IND` (`id_profile_img`);

--
-- Indici per le tabelle `badge`
--
ALTER TABLE `badge`
  ADD PRIMARY KEY (`badge_id`),
  ADD UNIQUE KEY `ID_BADGE_IND` (`badge_id`);

--
-- Indici per le tabelle `path`
--
ALTER TABLE `path`
  ADD PRIMARY KEY (`path_id`),
  ADD UNIQUE KEY `ID_PATH_IND` (`path_id`),
  ADD KEY `FKrecords` (`Rec_user_id`),
  ADD KEY `FKviews_IND` (`view_user_id`),
  ADD KEY `FKdeletes_IND` (`del_admin_id`),
  ADD KEY `FKanalyze_IND` (`Ana_admin_id`);

--
-- Indici per le tabelle `profile_image`
--
ALTER TABLE `profile_image`
  ADD PRIMARY KEY (`id_profile_img`),
  ADD UNIQUE KEY `ID_PROFILE_IMAGE_IND` (`id_profile_img`);

--
-- Indici per le tabelle `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`session_id`),
  ADD UNIQUE KEY `ID_SESSION_IND` (`session_id`),
  ADD KEY `FKuser_session_IND` (`session_user_id`),
  ADD KEY `FKadmin_session_IND` (`session_admin_id`);

--
-- Indici per le tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `ID_USER_IND` (`user_id`),
  ADD KEY `FKuser_img_IND` (`id_profile_img`);

--
-- Indici per le tabelle `user_badge`
--
ALTER TABLE `user_badge`
  ADD PRIMARY KEY (`user_id_earner`,`badge_id_acquired`),
  ADD UNIQUE KEY `ID_USER_BADGE_IND` (`user_id_earner`),
  ADD UNIQUE KEY `SID_USER_BADGE_IND` (`badge_id_acquired`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `badge`
--
ALTER TABLE `badge`
  MODIFY `badge_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `path`
--
ALTER TABLE `path`
  MODIFY `path_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `profile_image`
--
ALTER TABLE `profile_image`
  MODIFY `id_profile_img` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `session`
--
ALTER TABLE `session`
  MODIFY `session_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `FKadmin_img_FK` FOREIGN KEY (`id_profile_img`) REFERENCES `profile_image` (`id_profile_img`);

--
-- Limiti per la tabella `path`
--
ALTER TABLE `path`
  ADD CONSTRAINT `FKanalyze_FK` FOREIGN KEY (`Ana_admin_id`) REFERENCES `admin` (`admin_id`),
  ADD CONSTRAINT `FKdeletes_FK` FOREIGN KEY (`del_admin_id`) REFERENCES `admin` (`admin_id`),
  ADD CONSTRAINT `FKrecords` FOREIGN KEY (`Rec_user_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `FKviews_FK` FOREIGN KEY (`view_user_id`) REFERENCES `user` (`user_id`);

--
-- Limiti per la tabella `session`
--
ALTER TABLE `session`
  ADD CONSTRAINT `FK_SESSION_ADMIN` FOREIGN KEY (`session_admin_id`) REFERENCES `admin` (`admin_id`),
  ADD CONSTRAINT `FK_SESSION_USER` FOREIGN KEY (`session_user_id`) REFERENCES `user` (`user_id`);

--
-- Limiti per la tabella `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FKuser_img_FK` FOREIGN KEY (`id_profile_img`) REFERENCES `profile_image` (`id_profile_img`);

--
-- Limiti per la tabella `user_badge`
--
ALTER TABLE `user_badge`
  ADD CONSTRAINT `FK_BADGE` FOREIGN KEY (`badge_id_acquired`) REFERENCES `badge` (`badge_id`),
  ADD CONSTRAINT `FK_USER` FOREIGN KEY (`user_id_earner`) REFERENCES `user` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
