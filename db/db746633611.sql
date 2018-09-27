-- phpMyAdmin SQL Dump
-- version 4.1.14.8
-- http://www.phpmyadmin.net
--
-- Client :  db746633611.db.1and1.com
-- Généré le :  Jeu 27 Septembre 2018 à 13:13
-- Version du serveur :  5.5.60-0+deb7u1-log
-- Version de PHP :  5.4.45-0+deb7u14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `db746633611`
--

-- --------------------------------------------------------

--
-- Structure de la table `commodity`
--

CREATE TABLE IF NOT EXISTS `commodity` (
  `com_id` int(11) NOT NULL AUTO_INCREMENT,
  `com_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `com_title` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `com_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `com_picture` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `com_price` decimal(4,2) NOT NULL,
  PRIMARY KEY (`com_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=55 ;

--
-- Contenu de la table `commodity`
--

INSERT INTO `commodity` (`com_id`, `com_type`, `com_title`, `com_description`, `com_picture`, `com_price`) VALUES
(40, 'Pizza', 'Pizza Régina', 'Coulis de tomate, champignons de Paris, mozzarella, jambon supérieur.', 'pizza-regina.jpg', '9.50'),
(41, 'Pizza', 'Pizza Pêcheur', 'Coulis de tomate, olives noires, mozzarella, encornets, crevettes, anchois.', 'pizza-pecheur.jpg', '11.50'),
(42, 'Pizza', 'Pizza Fromagère', 'Pizza Fromagère 	Coulis de tomate, cheddar, comté, mozzarella, gorgonzola.', 'pizza-fromagere.jpg', '10.20'),
(43, 'Pizza', 'Pizza Végétarienne', 'Coulis de tomate, courgette, poivron jaune, olives vertes, mozzarella.', 'pizza-vegetarienne.jpg', '8.30'),
(44, 'Salade', 'Salade Caesar', 'Laitue romaine, croûtons à l''ail, jus de citron, parmesan, œufs durs, huile d''olive.', 'caesar.jpg', '7.50'),
(47, 'Boisson', 'Jus d''orange', 'Pur jus d''orange pressé avec pulpe (50cl).', 'jus-orange.jpg', '3.00'),
(48, 'Dessert', 'Tartelette Citron Meringuée', 'Tartelette une personne avec citron frais et meringue faite maison.', 'tarte_citron.png', '3.50'),
(51, 'Boisson', 'Eau', 'Bouteille d''eau de source (50 cl)', 'eau.jpg', '1.40'),
(52, 'Salade', 'Salade Grecque', 'Tomates fraîches, poivron vert et rouge, oignon coupé en fines tranches, feta, olives, concombres, zeste de citron.', 'salade_gecque.png', '6.80'),
(53, 'Dessert', 'Tartelette aux fraises', 'Tartelette sablée, fraises gariguettes, crème pâtissière à la fraise.', 'tarte_fraise.jpg', '2.80');

-- --------------------------------------------------------

--
-- Structure de la table `order_commodity`
--

CREATE TABLE IF NOT EXISTS `order_commodity` (
  `ordcom_id` int(11) NOT NULL AUTO_INCREMENT,
  `ord_id` int(11) NOT NULL,
  `com_id` int(11) NOT NULL,
  `ordcom_quantity` int(11) NOT NULL,
  PRIMARY KEY (`ordcom_id`),
  KEY `fk_ordcom_com_id` (`com_id`),
  KEY `fk_ordcom_ord_id` (`ord_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=35 ;

--
-- Contenu de la table `order_commodity`
--

INSERT INTO `order_commodity` (`ordcom_id`, `ord_id`, `com_id`, `ordcom_quantity`) VALUES
(2, 57, 40, 1),
(3, 57, 48, 2),
(4, 63, 41, 1),
(5, 63, 40, 1),
(6, 64, 41, 1),
(7, 64, 40, 1),
(8, 65, 40, 1),
(9, 65, 48, 1),
(10, 65, 53, 1),
(11, 68, 44, 2),
(12, 68, 52, 2),
(13, 69, 42, 2),
(14, 69, 43, 1),
(15, 71, 40, 2),
(16, 71, 43, 1),
(17, 72, 40, 2),
(18, 72, 43, 1),
(19, 73, 40, 3),
(20, 73, 51, 1),
(21, 73, 53, 1),
(22, 74, 40, 1),
(23, 74, 51, 3),
(24, 75, 41, 1),
(25, 75, 47, 2),
(26, 76, 44, 3),
(27, 76, 51, 1),
(28, 76, 47, 1),
(29, 77, 40, 2),
(30, 77, 42, 1),
(31, 77, 52, 1),
(32, 78, 40, 3),
(33, 78, 43, 1),
(34, 78, 53, 1);

-- --------------------------------------------------------

--
-- Structure de la table `t_order`
--

CREATE TABLE IF NOT EXISTS `t_order` (
  `ord_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `ord_status` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `ord_price` decimal(4,2) NOT NULL,
  `ord_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ord_id`),
  KEY `fk_ord_user` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=79 ;

--
-- Contenu de la table `t_order`
--

INSERT INTO `t_order` (`ord_id`, `user_id`, `ord_status`, `ord_price`, `ord_date`) VALUES
(57, 20, 'Validée', '14.85', '2018-09-11 09:48:01'),
(63, 21, 'Annulée', '18.90', '2018-09-12 11:50:06'),
(64, 20, 'Validée', '21.00', '2018-09-12 11:53:20'),
(65, 22, 'Annulée', '15.80', '2018-09-12 12:00:44'),
(68, 20, 'Validée', '28.60', '2018-09-17 12:57:47'),
(69, 20, 'En cours', '28.70', '2018-09-18 07:19:44'),
(71, 21, 'Annulée', '24.57', '2018-09-19 11:08:52'),
(72, 20, 'En cours', '24.57', '2018-09-19 12:25:35'),
(73, 20, 'En cours', '32.70', '2018-09-19 14:02:04'),
(74, 20, 'En cours', '13.70', '2018-09-19 14:04:57'),
(75, 20, 'En cours', '15.75', '2018-09-19 16:45:57'),
(76, 20, 'En cours', '26.90', '2018-09-20 08:53:15'),
(77, 23, 'En cours', '36.00', '2018-09-22 18:34:26'),
(78, 20, 'En cours', '39.60', '2018-09-27 11:06:42');

-- --------------------------------------------------------

--
-- Structure de la table `t_user`
--

CREATE TABLE IF NOT EXISTS `t_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_login` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `user_civility` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `user_name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `user_first_name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `user_email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_pwd` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_salt` varchar(23) COLLATE utf8_unicode_ci NOT NULL,
  `user_phone_number` char(10) COLLATE utf8_unicode_ci NOT NULL,
  `user_order_number` int(11) DEFAULT NULL,
  `user_role` varchar(20) COLLATE utf8_unicode_ci DEFAULT 'ROLE_USER',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=24 ;

--
-- Contenu de la table `t_user`
--

INSERT INTO `t_user` (`user_id`, `user_login`, `user_civility`, `user_name`, `user_first_name`, `user_email`, `user_pwd`, `user_salt`, `user_phone_number`, `user_order_number`, `user_role`) VALUES
(14, 'Admin', 'M.', 'FOLLIOT', 'Séb', 'sebastienfolliot@yahoo.fr', '$2y$13$5zKGuyKMk.GfOEqljLcIvellKQJcutBS5gAV/XhOgi/MO3gw3ue2S', 'b50a017c3e3cc3542c3f6bc', '0102030405', NULL, 'ROLE_ADMIN'),
(20, 'Seb44', 'M.', 'DUPONT', 'Sébastien', 'sebastienfolliot@gmail.com', '$2y$13$awJLXLk5zF9k3T.obIwhHeF6PfHeB/.yLq42IJ2t3t8W6I.ZX7Gmq', 'a82a00f2b22ce47f1b1080c', '0345678900', 17, 'ROLE_USER'),
(21, 'test', 'Mlle', 'test', 'test', 'test@sfr.fr', '$2y$13$leKLN9Hyc/E4Rg9cgINLZuiSn6jxs7SbRuFU1rkxLekm70zBeYRnu', '5a2cedd6c0e68aca3e8932c', '0202020203', 5, 'ROLE_USER'),
(22, 'St44', 'Mlle', 'FOLLIOT', 'Stella', 'stella@sfr.fr', '$2y$13$YfkTN.2RrNrCLaLgYN8UouhmOB2aNnvgdQbvB0wF2qxoj8Aw/UoRu', '1ef31faaf228de9870b99a9', '0202020202', 0, 'ROLE_USER'),
(23, 'Fil', 'M.', 'Bell', 'Fifi', 'stephfolliot@sfr.fr', '$2y$13$IPkSJQEIZ6rTvpH.UDmMgOCFWyiHOWTzWfpsv6GcOAg5tdmK7l99W', 'd87e7de7de731a39bb7f6c6', '0249546675', 1, 'ROLE_USER');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `order_commodity`
--
ALTER TABLE `order_commodity`
  ADD CONSTRAINT `fk_ordcom_com_id` FOREIGN KEY (`com_id`) REFERENCES `commodity` (`com_id`),
  ADD CONSTRAINT `fk_ordcom_ord_id` FOREIGN KEY (`ord_id`) REFERENCES `t_order` (`ord_id`);

--
-- Contraintes pour la table `t_order`
--
ALTER TABLE `t_order`
  ADD CONSTRAINT `fk_ord_user` FOREIGN KEY (`user_id`) REFERENCES `t_user` (`user_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
