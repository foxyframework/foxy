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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT 0,
  `title` varchar(150) NOT NULL DEFAULT '',
  `alias` varchar(150) NOT NULL DEFAULT '',
  `category` int(11) NOT NULL DEFAULT 0,
  `tags` varchar(150) NOT NULL DEFAULT '',
  `fulltext` text NOT NULL,
  `publishDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `author` varchar(50) NOT NULL DEFAULT '',
  `author_link` varchar(150) NOT NULL DEFAULT '#',
  `status` smallint(1) NOT NULL DEFAULT 0,
  `language` varchar(50) NOT NULL DEFAULT 'en-gb',
  `hits` int(11) NOT NULL  DEFAULT 0,
  `ordering` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Estructura de la taula `foxy_pages`
--

CREATE TABLE `foxy_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL DEFAULT '',
  `translation` varchar(150) NOT NULL DEFAULT '',
  `url` varchar(150) NOT NULL DEFAULT '',
  `auth` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 no login;1 login',
  `type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 Link;1 modal',
  `module` varchar(150) NOT NULL DEFAULT '',
  `template` varchar(50) NOT NULL DEFAULT '',
  `inMenu` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 no;1 yes',
  `ordering` int(11) NOT NULL DEFAULT 0,
  `status` smallint(1) NOT NULL DEFAULT 0,
  `params` text DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=7;

--
-- Bolcament de dades per a la taula `foxy_pages`
--

INSERT INTO `foxy_pages` (`id`, `title`, `translation`, `url`, `auth`, `type`, `module`, `template`, `inMenu`, `ordering`, `status`, `params`) VALUES
(1, 'Home', '', 'index.php?view=home', 0, 0, '', '', 1, 1, 1, '{"show_author":"1","show_date":"1","show_hits":"1","show_tags":"1"}'),
(2, 'About', '', 'index.php?view=about', 0, 0, '', '', 1, 2, 1, '{"show_author":"1","show_date":"1","show_hits":"1","show_tags":"1"}'),
(3, 'Blog', '', 'index.php?task=register.logout', 2, 0, '', '', 1, 3, 1, '{"show_author":"1","show_date":"1","show_hits":"1","show_tags":"1"}'),
(4, 'Contact', '', 'index.php?task=contact', 2, 0, '', '', 1, 4, 1, '{"show_author":"1","show_date":"1","show_hits":"1","show_tags":"1"}'),
(5, 'Login', '', 'index.php?view=register&layout=login', 1, 1, 'login', '', 1, 5, 1, '{"show_author":"1","show_date":"1","show_hits":"1","show_tags":"1"}'),
(6, 'Logout', '', 'index.php?task=register.logout', 2, 0, '', '', 1, 6, 1, '{"show_author":"1","show_date":"1","show_hits":"1","show_tags":"1"}');


-- --------------------------------------------------------

--
-- Estructura de la taula `foxy_blocs`
--

CREATE TABLE `foxy_blocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL DEFAULT '',
  `params` text NOT NULL DEFAULT '',
  `pageId` int(11) NOT NULL DEFAULT 0,
  `language` varchar(5) NOT NULL DEFAULT 'en-gb',
  `ordering` int(11) NOT NULL DEFAULT 0,
  `status` smallint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=3;

--
-- Bolcament de dades per a la taula `foxy_blocs`
--

INSERT INTO `foxy_blocks` (`id`, `title`, `params`, `pageId`, `language`, `ordering`, `status`) VALUES
(1, 'Jumbotron', '{"arg1":"FOXY PHP FRAMEWORK","arg2":"A small PHP Framework for rapid development of web application","arg3":"assets\/img\/icons\/icon264.png"}', 1, 'en-gb', 1, 1),
(2, 'Marketing', '{"arg1":"Light as a feather. Lightweight.","arg2":"Foxy weighs very little, the entire package once uploaded to your server is only 916Kb.","arg3":"assets\/img\/demo\/fox1.jpg","arg4":"Extend the code. Plugins.","arg5":"Foxy allows you to create the pages you want but also extend the functionality with small pieces of code called modules and plugins.", "arg6":"assets\/img\/demo\/fox2.jpg", "arg7":"Speak to the world. Internationalization.", "arg8":"Foxy allows you to create files with the translation of the text strings that you have in your application.", "arg9":"assets\/img\/demo\/fox3.jpg"}', 1, 'en-gb', 2, 1);

-- --------------------------------------------------------


--
-- Estructura de la taula `foxy_languages`
--

CREATE TABLE `foxy_languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL DEFAULT '',
  `code` varchar(10) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 unpublished;1 published',
  `ordering` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=4;

--
-- Bolcament de dades per a la taula `foxy_languages`
--

INSERT INTO `foxy_languages` (`id`, `title`, `code`, `status`, `ordering`) VALUES
(1, 'English', 'en-gb', 1, 1),
(2, 'Spanish', 'es-es', 0, 2),
(3, 'Catalan', 'ca-es', 0, 3);

-- --------------------------------------------------------


--
-- Estructura de la taula `foxy_extensions`
--

CREATE TABLE `foxy_extensions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL DEFAULT '',
  `folder` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 unpublished;1 published',
  `ordering` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=9;

--
-- Bolcament de dades per a la taula `foxy_extensions`
--

INSERT INTO `foxy_extensions` (`id`, `type`, `folder`, `name`, `status`, `ordering`) VALUES
(1, 'module', '', 'breadcrumbs', 1, 1),
(2, 'module', '', 'language', 1, 2),
(3, 'module', '', 'login', 1, 3),
(4, 'module', '', 'topmenu', 1, 4),
(5, 'plugin', 'onLogin', 'foxylogin', 1, 5),
(6, 'plugin', 'onLogin', 'gauthenticate', 0, 6),
(7, 'plugin', 'onRender', 'redirect', 1, 7),
(8, 'plugin', 'onAfterBlogPost', 'addthis', 1, 8);


-- --------------------------------------------------------

--
-- Estructura de la taula `foxy_sessions`
--

CREATE TABLE `foxy_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT 0,
  `ssid` varchar(150) NOT NULL DEFAULT '',
  `lastvisitDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Estructura de la taula `foxy_settings`
--

CREATE TABLE `foxy_settings` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `params` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;

--
-- Bolcament de dades per a la taula `foxy_settings`
--

INSERT INTO `foxy_settings` (`id`, `params`) VALUES
(1, '{"show_register":"0","login_redirect":"index.php?view=home","debug":"1","offline":"0","pagination":"10","admin_mails":"0","recaptcha":"0","public_key":"","secret_key":"","analytics":""}');

-- --------------------------------------------------------

--
-- Estructura de la taula `foxy_redirects`
--

CREATE TABLE `foxy_redirects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `old_url` varchar(150) NOT NULL DEFAULT '',
  `new_url` varchar(150) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 unpublished;1 published',
  `ordering` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=4;

-- --------------------------------------------------------

--
-- Estructura de la taula `foxy_usergroups`
--

CREATE TABLE `foxy_usergroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usergroup` varchar(50) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 unpublished;1 published',
  `ordering` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=4;

--
-- Bolcament de dades per a la taula `foxy_usergroups`
--

INSERT INTO `foxy_usergroups` (`id`, `usergroup`, `status`, `ordering`) VALUES
(1, 'admin', 1, 1),
(2, 'registered', 1, 2),
(3, 'public', 1, 3);

-- --------------------------------------------------------

--
-- Estructura de la taula `foxy_users`
--

CREATE TABLE `foxy_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL DEFAULT '',
  `password` varchar(150) NOT NULL DEFAULT '',
  `email` varchar(150) NOT NULL DEFAULT '',
  `registerDate` datetime NOT NULL,
  `lastvisitDate` datetime NOT NULL,
  `level` smallint(1) NOT NULL DEFAULT 0,
  `language` varchar(50) NOT NULL DEFAULT 'en-gb',
  `token` varchar(150) NOT NULL DEFAULT '',
  `block` smallint(1) NOT NULL DEFAULT 1,
  `image` varchar(150) NOT NULL DEFAULT 'nouser.png',
  `cargo` varchar(150) NOT NULL DEFAULT '',
  `bio` text NOT NULL,
  `address` varchar(150) NOT NULL DEFAULT '',
  `template` varchar(50) NOT NULL DEFAULT '',
  `apikey` varchar(150) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 unpublished;1 published',
  `ordering` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=99;

--
-- Bolcament de dades per a la taula `foxy_users`
--

INSERT INTO `foxy_users` (`id`, `username`, `password`, `email`, `registerDate`, `lastvisitDate`, `level`, `language`, `token`, `block`, `image`, `cargo`, `bio`, `address`, `template`, `apikey`, `status`, `ordering`) VALUES
(98, 'kim', '$2y$10$8FzX4NUrUz5YmpdokbyPgOVq5MPqzvo9AH83tyxqb5goT1Pw1xNrm', 'kim@aficat.com', '2017-11-15 12:18:41', '0000-00-00 00:00:00', 1, 'ca-es', '5a0c229196568', 0, 'nouser.png', '', '', '', 'green', '05982d8c-93d7-4f83-9083-c51f6a46beff', 1, 1);


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
