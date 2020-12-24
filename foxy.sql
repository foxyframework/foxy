-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Temps de generació: 12-06-2020 a les 11:05:16
-- Versió del servidor: 10.2.32-MariaDB
-- Versió de PHP: 7.1.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de dades: `foxy`
--


-- --------------------------------------------------------

--
-- Estructura de la taula `foxy_articles`
--

CREATE TABLE `foxy_articles` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `alias` varchar(150) NOT NULL,
  `category` int(11) NOT NULL,
  `tags` varchar(150) NOT NULL,
  `fulltext` text NOT NULL,
  `publishDate` datetime NOT NULL,
  `author` varchar(50) NOT NULL,
  `author_link` varchar(150) NOT NULL,
  `status` smallint(1) NOT NULL,
  `language` varchar(50) NOT NULL,
  `hits` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de la taula `foxy_menu`
--

CREATE TABLE `foxy_menu` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `translation` varchar(150) NOT NULL,
  `url` varchar(150) NOT NULL,
  `auth` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 no login;1login',
  `type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 Link;1 modal',
  `module` varchar(150) NOT NULL,
  `template` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Bolcament de dades per a la taula `foxy_menu`
--

INSERT INTO `foxy_menu` (`id`, `title`, `translation`, `url`, `auth`, `type`, `module`, `template`) VALUES
(1, 'Home', '', 'index.php?view=home', 0, 0, '', ''),
(2, 'About', '', 'index.php?view=about', 0, 0, '', ''),
(4, 'Blog', '', 'index.php?task=register.logout', 2, 0, '', ''),
(4, 'Contact', '', 'index.php?task=contact', 2, 0, '', ''),
(5, 'Login', '', 'index.php?view=register&layout=login', 1, 1, 'login', ''),
(6, 'Logout', '', 'index.php?task=register.logout', 2, 0, '', '');

-- --------------------------------------------------------

--
-- Estructura de la taula `foxy_sessions`
--

CREATE TABLE `foxy_sessions` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `ssid` varchar(150) NOT NULL,
  `lastvisitDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de la taula `foxy_settings`
--

CREATE TABLE `foxy_settings` (
  `id` int(1) NOT NULL,
  `params` text NOT NULL,
  `styles` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Bolcament de dades per a la taula `foxy_settings`
--

INSERT INTO `foxy_settings` (`id`, `show_register`, `login_redirect`, `debug`, `offline`) VALUES
(1, 0, 'index.php?view=home', 0, 0);

-- --------------------------------------------------------

--
-- Estructura de la taula `foxy_usergroups`
--

CREATE TABLE `foxy_usergroups` (
  `id` int(11) NOT NULL,
  `usergroup` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Bolcament de dades per a la taula `foxy_usergroups`
--

INSERT INTO `foxy_usergroups` (`id`, `usergroup`) VALUES
(1, 'admin'),
(2, 'registered'),
(3, 'public');

-- --------------------------------------------------------

--
-- Estructura de la taula `foxy_users`
--

CREATE TABLE `foxy_users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `registerDate` datetime NOT NULL,
  `lastvisitDate` datetime NOT NULL,
  `level` smallint(1) NOT NULL,
  `language` varchar(50) NOT NULL,
  `token` varchar(150) NOT NULL,
  `block` smallint(1) NOT NULL DEFAULT 1,
  `image` varchar(150) NOT NULL DEFAULT 'nouser.png',
  `cargo` varchar(150) NOT NULL,
  `bio` text NOT NULL,
  `address` varchar(150) NOT NULL,
  `template` varchar(50) NOT NULL,
  `apikey` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Bolcament de dades per a la taula `foxy_users`
--

INSERT INTO `foxy_users` (`id`, `username`, `password`, `email`, `registerDate`, `lastvisitDate`, `level`, `language`, `token`, `block`, `image`, `cargo`, `bio`, `address`, `template`, `apikey`) VALUES
(98, 'kim', '$2y$10$8FzX4NUrUz5YmpdokbyPgOVq5MPqzvo9AH83tyxqb5goT1Pw1xNrm', 'kim@aficat.com', '2017-11-15 12:18:41', '0000-00-00 00:00:00', 1, 'ca-es', '5a0c229196568', 0, 'nouser.png', '', '', '', 'green', '05982d8c-93d7-4f83-9083-c51f6a46beff');

--
-- Índexs per a les taules bolcades
--

--
-- Índexs per a la taula `foxy_articles`
--
ALTER TABLE `foxy_articles`
  ADD PRIMARY KEY (`id`);

--
-- Índexs per a la taula `foxy_menu`
--
ALTER TABLE `foxy_menu`
  ADD PRIMARY KEY (`id`);

--
-- Índexs per a la taula `foxy_sessions`
--
ALTER TABLE `foxy_sessions`
  ADD PRIMARY KEY (`id`);

--
-- Índexs per a la taula `foxy_settings`
--
ALTER TABLE `foxy_settings`
  ADD PRIMARY KEY (`id`);

--
-- Índexs per a la taula `foxy_usergroups`
--
ALTER TABLE `foxy_usergroups`
  ADD PRIMARY KEY (`id`);

--
-- Índexs per a la taula `foxy_users`
--
ALTER TABLE `foxy_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per les taules bolcades
--

--
-- AUTO_INCREMENT per la taula `foxy_articles`
--
ALTER TABLE `foxy_articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT per la taula `foxy_menu`
--
ALTER TABLE `foxy_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT per la taula `foxy_sessions`
--
ALTER TABLE `foxy_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT per la taula `foxy_settings`
--
ALTER TABLE `foxy_settings`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la taula `foxy_usergroups`
--
ALTER TABLE `foxy_usergroups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la taula `foxy_users`
--
ALTER TABLE `foxy_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
