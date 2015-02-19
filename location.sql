-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Client :  localhost:8889
-- Généré le :  Jeu 19 Février 2015 à 11:44
-- Version du serveur :  5.5.34
-- Version de PHP :  5.5.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données :  `location`
--

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Contenu de la table `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(4, 'Maison'),
(5, 'Transport'),
(6, 'Loisirs'),
(8, 'Multimédia'),
(9, 'Autres');

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_from` int(11) NOT NULL,
  `id_to` int(11) NOT NULL,
  `content` text NOT NULL,
  `mark` int(1) NOT NULL,
  `created_at` int(11) NOT NULL,
  `disabled_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Contenu de la table `comment`
--

INSERT INTO `comment` (`id`, `id_from`, `id_to`, `content`, `mark`, `created_at`, `disabled_at`) VALUES
(7, 17, 16, 'Donnez une appréciation au loueur', 2, 1424263828, 0);

-- --------------------------------------------------------

--
-- Structure de la table `offer`
--

CREATE TABLE `offer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_category` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `name` text NOT NULL,
  `content` text NOT NULL,
  `price_per_day` float NOT NULL,
  `location` int(5) NOT NULL,
  `created_at` int(11) NOT NULL,
  `disabled_at` int(11) NOT NULL,
  `availability` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Contenu de la table `offer`
--

INSERT INTO `offer` (`id`, `id_category`, `id_user`, `name`, `content`, `price_per_day`, `location`, `created_at`, `disabled_at`, `availability`) VALUES
(14, 5, 16, 'Citroen C2', 'Pour que votre offre soit publié, ne divilguez pas vos coordonnées.\r\n        ', 125, 75002, 1423518814, 0, 2),
(15, 9, 16, 'Chaussette', 'Pour que votre offre soit publié, ne divilguez pas vos coordonnées.\r\n        ', 5, 83200, 1423597681, 0, 1),
(16, 4, 17, 'Nain de jardin', 'Pour que votre offre soit publié, ne divilguez pas vos coordonnées.\r\n        ', 15, 77410, 1424108870, 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `photo`
--

CREATE TABLE `photo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_offer` int(11) NOT NULL,
  `photo_name` text NOT NULL,
  `created_at` int(11) NOT NULL,
  `disabled_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Contenu de la table `photo`
--

INSERT INTO `photo` (`id`, `id_offer`, `photo_name`, `created_at`, `disabled_at`) VALUES
(6, 14, 'public/assets/offer_1423518814_Capture d’écran 2015-02-09 à 17.51.24.png', 1423518814, 0),
(7, 14, 'public/assets/offer_1423518814_Capture d’écran 2015-02-09 à 22.07.34.png', 1423518814, 0),
(8, 15, 'public/assets/offer_1423597681_1761049009.png', 1423597681, 0),
(9, 15, 'public/assets/offer_1423597681_908559869.png', 1423597681, 0),
(10, 16, 'public/assets/offer_1424108870_285535673.png', 1424108870, 0),
(11, 17, 'public/assets/offer_1424108951_1137179708.png', 1424108951, 0);

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

CREATE TABLE `reservation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(1) NOT NULL,
  `id_offer` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `date_start` int(11) NOT NULL,
  `date_end` int(11) NOT NULL,
  `commented` int(1) NOT NULL,
  `created_at` int(11) NOT NULL,
  `disabled_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `reservation`
--

INSERT INTO `reservation` (`id`, `status`, `id_offer`, `id_user`, `date_start`, `date_end`, `commented`, `created_at`, `disabled_at`) VALUES
(1, 1, 14, 17, 1424247213, 1424353600, 1, 1424248345, 0);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `address` varchar(255) NOT NULL,
  `postal` varchar(10) NOT NULL,
  `city` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `mark` int(1) NOT NULL,
  `photo` varchar(100) NOT NULL,
  `created_at` int(11) NOT NULL,
  `disabled_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `firstname`, `lastname`, `address`, `postal`, `city`, `country`, `mark`, `photo`, `created_at`, `disabled_at`) VALUES
(16, 'alexandre.ferraille@gmail.com', '60c6d277a8bd81de7fdde19201bf9c58a3df08f4', 'Alexandre', 'Ferraille', '10 allée de la tournelle', '77410', 'Annet Sur Marne', 'France', 2, 'public/assets/profil_1423518672.png', 1423518655, 0),
(17, 'max.fer@betatest.fr', '0706025b2bbcec1ed8d64822f4eccd96314938d0', 'Maxime', 'Ferraille', '', '', '', '', -1, '', 1423601470, 0),
(18, 'j.cherain@gmail.com', '7fdfe229fce69a4d7f38653f3755ccbae5703f88', 'Juliette', 'Chérain', '', '', '', '', -1, '', 1423759433, 0);

-- --------------------------------------------------------

--
-- Structure de la table `wish`
--

CREATE TABLE `wish` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_offer` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;
