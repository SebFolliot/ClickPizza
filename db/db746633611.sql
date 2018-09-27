
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
