-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           5.7.14 - MySQL Community Server (GPL)
-- SE du serveur:                Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Listage de la structure de la base pour immo
CREATE DATABASE IF NOT EXISTS `immo` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `immo`;

-- Listage de la structure de table immo. bon_commande_detail
DROP TABLE IF EXISTS `bon_commande_detail`;
CREATE TABLE IF NOT EXISTS `bon_commande_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_bon_de_commande` int(11) DEFAULT '0',
  `id_produit_variation` int(11) DEFAULT '0',
  `qte` int(11) DEFAULT NULL,
  `montant` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_bon_de_commande` (`id_bon_de_commande`),
  KEY `id_produit_variation` (`id_produit_variation`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de table immo. bon_de_commande
DROP TABLE IF EXISTS `bon_de_commande`;
CREATE TABLE IF NOT EXISTS `bon_de_commande` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_client` int(11) NOT NULL DEFAULT '0',
  `numero` varchar(255) NOT NULL,
  `qte_total` int(11) NOT NULL,
  `montant_total` float NOT NULL,
  `statut_facture` varchar(255) NOT NULL,
  `etat_suppression` int(11) NOT NULL DEFAULT '0',
  `date_bon_de_commande` date DEFAULT NULL,
  `numero_facture` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_client` (`id_client`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de table immo. categorie
DROP TABLE IF EXISTS `categorie`;
CREATE TABLE IF NOT EXISTS `categorie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom_categorie` varchar(500) NOT NULL,
  `description` varchar(300) NOT NULL,
  `photos` varchar(500) NOT NULL,
  `etat_suppression` tinyint(1) NOT NULL,
  `categorie_parent` int(11) DEFAULT '0',
  `is_child` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de table immo. client
DROP TABLE IF EXISTS `client`;
CREATE TABLE IF NOT EXISTS `client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom_client` varchar(255) DEFAULT NULL,
  `prenom_client` varchar(255) DEFAULT NULL,
  `mail_client` varchar(255) DEFAULT NULL,
  `code_pays` varchar(10) NOT NULL,
  `telephone_client` varchar(255) DEFAULT NULL,
  `adresse_client` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `type_client` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nif_client` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `stat_client` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `raison_sociale_client` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `mdp_client` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `login_client` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de table immo. mon_societe
DROP TABLE IF EXISTS `mon_societe`;
CREATE TABLE IF NOT EXISTS `mon_societe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adresse_mail` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de table immo. panier_client
DROP TABLE IF EXISTS `panier_client`;
CREATE TABLE IF NOT EXISTS `panier_client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_produit_variation` int(11) DEFAULT NULL,
  `id_client` int(11) DEFAULT NULL,
  `qte` int(11) DEFAULT NULL,
  `montant` double DEFAULT NULL,
  `etat_paiement` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_produit_variation` (`id_produit_variation`),
  KEY `id_client` (`id_client`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de table immo. produit
DROP TABLE IF EXISTS `produit`;
CREATE TABLE IF NOT EXISTS `produit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference_produit` varchar(300) NOT NULL,
  `designation` varchar(300) NOT NULL,
  `description_produit` text NOT NULL,
  `statut` varchar(200) NOT NULL,
  `categorie_id` int(11) NOT NULL,
  `etat_suppression` tinyint(1) NOT NULL,
  `superficie` double DEFAULT NULL,
  `longueur` double DEFAULT NULL,
  `largeur` double DEFAULT NULL,
  `position_map` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categorie_id` (`categorie_id`),
  CONSTRAINT `produit_ibfk_1` FOREIGN KEY (`categorie_id`) REFERENCES `categorie` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de table immo. produit_photos
DROP TABLE IF EXISTS `produit_photos`;
CREATE TABLE IF NOT EXISTS `produit_photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `photos` varchar(500) NOT NULL,
  `produit_id` int(11) NOT NULL,
  `etat_suppression` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `produit_id` (`produit_id`),
  CONSTRAINT `produit_photos_ibfk_1` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de table immo. produit_variation
DROP TABLE IF EXISTS `produit_variation`;
CREATE TABLE IF NOT EXISTS `produit_variation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `variation` varchar(500) NOT NULL,
  `prix` float NOT NULL,
  `photos` varchar(500) NOT NULL,
  `produit_id` int(11) NOT NULL,
  `etat_suppression` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `produit_id` (`produit_id`),
  CONSTRAINT `produit_variation_ibfk_1` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=139 DEFAULT CHARSET=utf8;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de vue immo. rq_bon_commande_detail
DROP VIEW IF EXISTS `rq_bon_commande_detail`;
-- Création d'une table temporaire pour palier aux erreurs de dépendances de VIEW
CREATE TABLE `rq_bon_commande_detail` (
	`id` INT(11) NOT NULL,
	`id_bon_de_commande` INT(11) NULL,
	`id_produit_variation` INT(11) NULL,
	`qte` INT(11) NULL,
	`montant` DOUBLE NULL,
	`numero` VARCHAR(255) NOT NULL COLLATE 'utf8_general_ci',
	`id_client` INT(11) NOT NULL,
	`nom_client` VARCHAR(255) NULL COLLATE 'utf8_general_ci',
	`prenom_client` VARCHAR(255) NULL COLLATE 'utf8_general_ci',
	`variation` VARCHAR(500) NOT NULL COLLATE 'utf8_general_ci',
	`reference_produit` VARCHAR(300) NOT NULL COLLATE 'utf8_general_ci',
	`designation` VARCHAR(300) NOT NULL COLLATE 'utf8_general_ci',
	`prix_produit` FLOAT NOT NULL
) ENGINE=MyISAM;

-- Listage de la structure de vue immo. rq_bon_de_commande
DROP VIEW IF EXISTS `rq_bon_de_commande`;
-- Création d'une table temporaire pour palier aux erreurs de dépendances de VIEW
CREATE TABLE `rq_bon_de_commande` (
	`id` INT(11) NOT NULL,
	`id_client` INT(11) NOT NULL,
	`numero` VARCHAR(255) NOT NULL COLLATE 'utf8_general_ci',
	`qte_total` INT(11) NOT NULL,
	`montant_total` FLOAT NOT NULL,
	`statut_facture` VARCHAR(255) NOT NULL COLLATE 'utf8_general_ci',
	`etat_suppression` INT(11) NOT NULL,
	`date_bon_de_commande` DATE NULL,
	`numero_facture` VARCHAR(255) NULL COLLATE 'utf8_general_ci',
	`nom_client` VARCHAR(255) NULL COLLATE 'utf8_general_ci',
	`prenom_client` VARCHAR(255) NULL COLLATE 'utf8_general_ci',
	`mail_client` VARCHAR(255) NULL COLLATE 'utf8_general_ci',
	`telephone_client` VARCHAR(255) NULL COLLATE 'utf8_general_ci',
	`adresse_client` VARCHAR(255) NULL COLLATE 'latin1_swedish_ci',
	`type_client` VARCHAR(255) NULL COLLATE 'latin1_swedish_ci',
	`nif_client` VARCHAR(255) NULL COLLATE 'latin1_swedish_ci',
	`stat_client` VARCHAR(255) NULL COLLATE 'latin1_swedish_ci',
	`raison_sociale_client` VARCHAR(255) NULL COLLATE 'latin1_swedish_ci',
	`login_client` VARCHAR(255) NULL COLLATE 'latin1_swedish_ci'
) ENGINE=MyISAM;

-- Listage de la structure de vue immo. rq_panier_client
DROP VIEW IF EXISTS `rq_panier_client`;
-- Création d'une table temporaire pour palier aux erreurs de dépendances de VIEW
CREATE TABLE `rq_panier_client` (
	`id` INT(11) NOT NULL,
	`id_produit_variation` INT(11) NULL,
	`id_client` INT(11) NULL,
	`qte` INT(11) NULL,
	`montant` DOUBLE NULL,
	`etat_paiement` VARCHAR(255) NULL COLLATE 'latin1_swedish_ci',
	`variation` VARCHAR(500) NOT NULL COLLATE 'utf8_general_ci',
	`prix` FLOAT NOT NULL,
	`reference_produit` VARCHAR(300) NOT NULL COLLATE 'utf8_general_ci'
) ENGINE=MyISAM;

-- Listage de la structure de vue immo. rq_produit
DROP VIEW IF EXISTS `rq_produit`;
-- Création d'une table temporaire pour palier aux erreurs de dépendances de VIEW
CREATE TABLE `rq_produit` (
	`categorie_parent` INT(11) NULL,
	`is_child` INT(11) NULL,
	`id` INT(11) NOT NULL,
	`superficie` DOUBLE NULL,
	`longueur` DOUBLE NULL,
	`largeur` DOUBLE NULL,
	`position_map` VARCHAR(255) NULL COLLATE 'utf8_general_ci',
	`description_produit` TEXT NOT NULL COLLATE 'utf8_general_ci',
	`reference_produit` VARCHAR(300) NOT NULL COLLATE 'utf8_general_ci',
	`categorie` VARCHAR(500) NOT NULL COLLATE 'utf8_general_ci',
	`parent_categorie_nom` VARCHAR(500) NOT NULL COLLATE 'utf8_general_ci',
	`photos_categorie` VARCHAR(500) NOT NULL COLLATE 'utf8_general_ci',
	`designation` VARCHAR(300) NOT NULL COLLATE 'utf8_general_ci',
	`categorie_id` INT(11) NOT NULL,
	`photos` VARCHAR(500) NULL COLLATE 'utf8_general_ci',
	`statut` VARCHAR(200) NOT NULL COLLATE 'utf8_general_ci',
	`etat_suppression` TINYINT(1) NOT NULL,
	`variation` VARCHAR(500) NULL COLLATE 'utf8_general_ci',
	`prix` FLOAT NULL,
	`photos_variation` VARCHAR(500) NULL COLLATE 'utf8_general_ci',
	`variation_produit` TEXT NULL COLLATE 'utf8_general_ci',
	`photos_produit` TEXT NULL COLLATE 'utf8_general_ci'
) ENGINE=MyISAM;

-- Listage de la structure de vue immo. rq_produit_variation
DROP VIEW IF EXISTS `rq_produit_variation`;
-- Création d'une table temporaire pour palier aux erreurs de dépendances de VIEW
CREATE TABLE `rq_produit_variation` (
	`id` INT(11) NOT NULL,
	`description_produit` TEXT NOT NULL COLLATE 'utf8_general_ci',
	`variation` VARCHAR(500) NOT NULL COLLATE 'utf8_general_ci',
	`prix` FLOAT NOT NULL,
	`photos` VARCHAR(500) NOT NULL COLLATE 'utf8_general_ci',
	`produit_id` INT(11) NOT NULL,
	`etat_suppression` TINYINT(1) NOT NULL,
	`reference_produit` VARCHAR(300) NOT NULL COLLATE 'utf8_general_ci',
	`designation` VARCHAR(300) NOT NULL COLLATE 'utf8_general_ci',
	`statut` VARCHAR(200) NOT NULL COLLATE 'utf8_general_ci',
	`categorie_id` INT(11) NOT NULL,
	`nom_categorie` VARCHAR(500) NOT NULL COLLATE 'utf8_general_ci',
	`description` VARCHAR(300) NOT NULL COLLATE 'utf8_general_ci'
) ENGINE=MyISAM;

-- Listage de la structure de table immo. send_mail
DROP TABLE IF EXISTS `send_mail`;
CREATE TABLE IF NOT EXISTS `send_mail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) DEFAULT NULL,
  `adresse_mail` varchar(255) DEFAULT NULL,
  `telephone` varchar(255) DEFAULT NULL,
  `msg_envoyer` varchar(255) DEFAULT NULL,
  `lien` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de table immo. utilisateur
DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom_utilisateur` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `mail_utilisateur` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `login_utilisateur` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `mdp_utilisateur` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `telephone_utilisateur` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `role_utilisateur` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `photos_utilisateur` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `statut_utilisateur` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `etat_suppression` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de vue immo. rq_bon_commande_detail
DROP VIEW IF EXISTS `rq_bon_commande_detail`;
-- Suppression de la table temporaire et création finale de la structure d'une vue
DROP TABLE IF EXISTS `rq_bon_commande_detail`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `rq_bon_commande_detail` AS select `d`.`id` AS `id`,`d`.`id_bon_de_commande` AS `id_bon_de_commande`,`d`.`id_produit_variation` AS `id_produit_variation`,`d`.`qte` AS `qte`,`d`.`montant` AS `montant`,`b`.`numero` AS `numero`,`b`.`id_client` AS `id_client`,`b`.`nom_client` AS `nom_client`,`b`.`prenom_client` AS `prenom_client`,`r`.`variation` AS `variation`,`r`.`reference_produit` AS `reference_produit`,`r`.`designation` AS `designation`,`r`.`prix` AS `prix_produit` from ((`bon_commande_detail` `d` join `rq_bon_de_commande` `b` on((`b`.`id` = `d`.`id_bon_de_commande`))) join `rq_produit_variation` `r` on((`r`.`id` = `d`.`id_produit_variation`)));

-- Listage de la structure de vue immo. rq_bon_de_commande
DROP VIEW IF EXISTS `rq_bon_de_commande`;
-- Suppression de la table temporaire et création finale de la structure d'une vue
DROP TABLE IF EXISTS `rq_bon_de_commande`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `rq_bon_de_commande` AS select `c`.`id` AS `id`,`c`.`id_client` AS `id_client`,`c`.`numero` AS `numero`,`c`.`qte_total` AS `qte_total`,`c`.`montant_total` AS `montant_total`,`c`.`statut_facture` AS `statut_facture`,`c`.`etat_suppression` AS `etat_suppression`,`c`.`date_bon_de_commande` AS `date_bon_de_commande`,`c`.`numero_facture` AS `numero_facture`,`cl`.`nom_client` AS `nom_client`,`cl`.`prenom_client` AS `prenom_client`,`cl`.`mail_client` AS `mail_client`,`cl`.`telephone_client` AS `telephone_client`,`cl`.`adresse_client` AS `adresse_client`,`cl`.`type_client` AS `type_client`,`cl`.`nif_client` AS `nif_client`,`cl`.`stat_client` AS `stat_client`,`cl`.`raison_sociale_client` AS `raison_sociale_client`,`cl`.`login_client` AS `login_client` from (`bon_de_commande` `c` join `client` `cl` on((`c`.`id_client` = `cl`.`id`)));

-- Listage de la structure de vue immo. rq_panier_client
DROP VIEW IF EXISTS `rq_panier_client`;
-- Suppression de la table temporaire et création finale de la structure d'une vue
DROP TABLE IF EXISTS `rq_panier_client`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `rq_panier_client` AS select `pc`.`id` AS `id`,`pc`.`id_produit_variation` AS `id_produit_variation`,`pc`.`id_client` AS `id_client`,`pc`.`qte` AS `qte`,`pc`.`montant` AS `montant`,`pc`.`etat_paiement` AS `etat_paiement`,`rp`.`variation` AS `variation`,`rp`.`prix` AS `prix`,`rp`.`reference_produit` AS `reference_produit` from (`panier_client` `pc` join `rq_produit_variation` `rp` on((`rp`.`id` = `pc`.`id_produit_variation`)));

-- Listage de la structure de vue immo. rq_produit
DROP VIEW IF EXISTS `rq_produit`;
-- Suppression de la table temporaire et création finale de la structure d'une vue
DROP TABLE IF EXISTS `rq_produit`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `rq_produit` AS select `categorie`.`categorie_parent` AS `categorie_parent`,`categorie`.`is_child` AS `is_child`,`produit`.`id` AS `id`,`produit`.`superficie` AS `superficie`,`produit`.`longueur` AS `longueur`,`produit`.`largeur` AS `largeur`,`produit`.`position_map` AS `position_map`,`produit`.`description_produit` AS `description_produit`,`produit`.`reference_produit` AS `reference_produit`,`categorie`.`nom_categorie` AS `categorie`,ifnull(`s`.`nom_categorie`,'') AS `parent_categorie_nom`,`categorie`.`photos` AS `photos_categorie`,`produit`.`designation` AS `designation`,`categorie`.`id` AS `categorie_id`,`produit_photos`.`photos` AS `photos`,`produit`.`statut` AS `statut`,`produit`.`etat_suppression` AS `etat_suppression`,`produit_variation`.`variation` AS `variation`,`produit_variation`.`prix` AS `prix`,`produit_variation`.`photos` AS `photos_variation`,group_concat(distinct concat(`produit_variation`.`variation`,' ',`produit_variation`.`prix`,'  ',`produit_variation`.`photos`) separator ',') AS `variation_produit`,group_concat(distinct concat(`produit_photos`.`photos`,' ') separator ',') AS `photos_produit` from ((((`produit` join `categorie` on((`categorie`.`id` = `produit`.`categorie_id`))) left join `categorie` `s` on((`s`.`id` = `categorie`.`categorie_parent`))) left join `produit_variation` on((`produit_variation`.`produit_id` = `produit`.`id`))) left join `produit_photos` on((`produit_photos`.`produit_id` = `produit`.`id`))) group by `produit`.`id` order by `produit`.`id`;

-- Listage de la structure de vue immo. rq_produit_variation
DROP VIEW IF EXISTS `rq_produit_variation`;
-- Suppression de la table temporaire et création finale de la structure d'une vue
DROP TABLE IF EXISTS `rq_produit_variation`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `rq_produit_variation` AS select `pr`.`id` AS `id`,`p`.`description_produit` AS `description_produit`,`pr`.`variation` AS `variation`,`pr`.`prix` AS `prix`,`pr`.`photos` AS `photos`,`pr`.`produit_id` AS `produit_id`,`pr`.`etat_suppression` AS `etat_suppression`,`p`.`reference_produit` AS `reference_produit`,`p`.`designation` AS `designation`,`p`.`statut` AS `statut`,`p`.`categorie_id` AS `categorie_id`,`c`.`nom_categorie` AS `nom_categorie`,`c`.`description` AS `description` from ((`produit_variation` `pr` join `produit` `p` on((`pr`.`produit_id` = `p`.`id`))) join `categorie` `c` on((`c`.`id` = `p`.`categorie_id`)));

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
