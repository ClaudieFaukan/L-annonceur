/*
SQLyog Community v13.1.5  (64 bit)
MySQL - 5.7.26 : Database - annonce
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`annonce` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `annonce`;

/*Table structure for table `annonce` */

DROP TABLE IF EXISTS `annonce`;

CREATE TABLE `annonce` (
  `ID_ANNONCE` int(4) NOT NULL AUTO_INCREMENT,
  `ID_USER` int(4) NOT NULL,
  `ID_RUBRIQUE` int(4) NOT NULL,
  `EN_TETE` char(80) NOT NULL,
  `CORP` varchar(500) NOT NULL,
  `DATE_VALIDITE` date DEFAULT NULL,
  `DATE_DEPOT` date DEFAULT NULL,
  PRIMARY KEY (`ID_ANNONCE`),
  KEY `I_FK_ANNONCE_UTILISATEUR` (`ID_USER`),
  KEY `I_FK_ANNONCE_RUBRIQUE` (`ID_RUBRIQUE`),
  CONSTRAINT `annonce_ibfk_1` FOREIGN KEY (`ID_USER`) REFERENCES `utilisateur` (`ID_USER`),
  CONSTRAINT `annonce_ibfk_2` FOREIGN KEY (`ID_RUBRIQUE`) REFERENCES `rubrique` (`ID_RUBRIQUE`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

/*Data for the table `annonce` */

insert  into `annonce`(`ID_ANNONCE`,`ID_USER`,`ID_RUBRIQUE`,`EN_TETE`,`CORP`,`DATE_VALIDITE`,`DATE_DEPOT`) values 
(5,5,1,'MAcbook','loresum ipipipsdloresum ipipipsdloresum ipipipsd','2019-12-17','2019-09-20'),
(6,6,44,'Opel Astra','Je blablate pas mal et ainsi de suite..','2019-12-19','2019-11-27'),
(9,13,44,'Developpeur Web junior','spécialiste PHP, 40K€','2020-01-18','2019-12-09'),
(13,13,6,'lk','lk','2020-01-25','2019-12-16'),
(14,15,5,'BDD','el famoso','2020-02-06','2019-12-17'),
(15,15,29,'MONEYYYY','MAKE MORE MOULA bro!','2020-02-06','2019-12-17'),
(16,15,29,'MONEYY','MORE MOULA FOR MOULAGA','2020-02-07','2019-12-17'),
(17,15,1,'pc gaming','vfqs fqgg eshg','2020-02-06','2019-12-17'),
(18,15,3,'AUTO','maman la beauté','2020-01-26','2019-12-17'),
(19,15,1,'tablette update photos','Je ne modifie pas les photos','2020-02-06','2019-12-17'),
(20,13,6,'blabal','le chien chien','2020-01-27','2019-12-18'),
(21,13,29,'lours','brun','2020-01-27','2019-12-18'),
(22,13,2,'lot immobilier','incroyable','2020-01-27','2019-12-18'),
(23,13,4,'balbala','lol','2020-01-27','2019-12-18'),
(24,13,1,'iphone','10€','2020-01-27','2019-12-18'),
(25,13,4,'fucking test','allez','2020-01-27','2019-12-18'),
(30,1,29,'test poid photos+ taille description','&quot;Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.&quot;','2020-01-29','2019-12-20'),
(31,1,6,'gdf','hththththththththt','2020-01-29','2019-12-20'),
(32,1,25,'tt','t','2020-01-29','2019-12-20'),
(33,1,6,'fe','mbMvbmrSORBWbl8z4Zne2dsoTMrAE3uFKtoj2J1V5l2rpcoTeFRecKhF8af1Ugike6Vyj09l21rEQ11q1OSHd6sGqrX7FH2hleQHki6XOksgBblbtd2GPBK5aZF1sJ5ZRcPtkfm2Y6A79zqGwKlrj83KsE38e8nc2ULerLyAFQTuhnxJc9O82qx2M6ohifGoi2CtXoqQAYpLkCl0bQIG9IIRmQNLJcoMH3pxlJcCzEb0zrm4KLdNDJdOXs2hztVumycGSHbuqnyykE535pSp67o0FA3kTIMnMZc9djw5DZQQ00qJqTBg5yxxX0NBV27gShTlPYrdMBVWwj6PT7Gq9qA7o3GyYEeWcqWc5tiCFVTEvqnpf12UABHXdBP9cPJikoDmPLAnNNACkOz6a2tMTRHjeMHSeJYgvoNeOjDvu3RO6InYZ01suZqvn0RLH0CQuQSw2J2xAqc1nkXP7q8rTDh3eEHwjsFbMKdSMCYKaN60lfSBi30d','2020-01-29','2019-12-20');

/*Table structure for table `images` */

DROP TABLE IF EXISTS `images`;

CREATE TABLE `images` (
  `ID_IMAGE` int(5) NOT NULL AUTO_INCREMENT,
  `ID_ANNONCE` int(5) NOT NULL,
  `HREF` varchar(100) NOT NULL,
  PRIMARY KEY (`ID_IMAGE`),
  KEY `I_FK_IMAGE_ANNONCE` (`ID_ANNONCE`),
  CONSTRAINT `images_ibfk_1` FOREIGN KEY (`ID_ANNONCE`) REFERENCES `annonce` (`ID_ANNONCE`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8;

/*Data for the table `images` */

insert  into `images`(`ID_IMAGE`,`ID_ANNONCE`,`HREF`) values 
(1,19,'upload/5e076a2355d1b7.90210560.jpg'),
(2,19,'upload/5e076c6ef0a732.82672950.png'),
(3,19,'upload/5e076a81613bb8.35742951.jpg'),
(4,24,'upload/no-img.jpg'),
(5,25,'upload/no-img.jpg'),
(6,5,'upload/no-img.jpg'),
(7,6,'upload/no-img.jpg'),
(8,9,'upload/no-img.jpg'),
(10,13,'upload/no-img.jpg'),
(11,14,'upload/5e077b635979f9.51168080.png'),
(12,15,'upload/no-img.jpg'),
(13,16,'upload/5e08d40c683b14.17914964.png'),
(14,17,'upload/5e077d8b2d0c60.35048422.png'),
(15,18,'upload/no-img.jpg'),
(16,20,'upload/no-img.jpg'),
(17,21,'upload/no-img.jpg'),
(18,22,'upload/no-img.jpg'),
(19,23,'upload/no-img.jpg'),
(20,30,'upload/5dfca180733f32.32420195.jpg'),
(21,30,'upload/5dfca1845df1c1.30249453.jpg'),
(22,30,'upload/5dfca188a888d4.00336444.jpg'),
(23,31,'upload/no-img.jpg'),
(24,32,'upload/no-img.jpg'),
(25,33,'upload/no-img.jpg'),
(26,24,'upload/no-img.jpg'),
(27,24,'upload/no-img.jpg'),
(28,25,'upload/no-img.jpg'),
(29,25,'upload/no-img.jpg'),
(30,5,'upload/no-img.jpg'),
(31,5,'upload/no-img.jpg'),
(32,6,'upload/no-img.jpg'),
(33,6,'upload/no-img.jpg'),
(34,9,'upload/no-img.jpg'),
(35,9,'upload/no-img.jpg'),
(38,13,'upload/no-img.jpg'),
(39,13,'upload/no-img.jpg'),
(40,14,'upload/5e077c50dad790.29303790.png'),
(41,14,'upload/no-img.jpg'),
(42,15,'upload/no-img.jpg'),
(43,15,'upload/5e077c836d6720.23909566.png'),
(44,16,'upload/no-img.jpg'),
(45,16,'upload/no-img.jpg'),
(46,17,'upload/no-img.jpg'),
(47,17,'upload/no-img.jpg'),
(48,18,'upload/no-img.jpg'),
(49,18,'upload/no-img.jpg'),
(50,20,'upload/no-img.jpg'),
(51,20,'upload/no-img.jpg'),
(52,21,'upload/no-img.jpg'),
(53,21,'upload/no-img.jpg'),
(54,22,'upload/no-img.jpg'),
(55,22,'upload/no-img.jpg'),
(56,23,'upload/no-img.jpg'),
(57,23,'upload/no-img.jpg'),
(58,31,'upload/no-img.jpg'),
(59,31,'upload/no-img.jpg'),
(60,32,'upload/no-img.jpg'),
(61,32,'upload/no-img.jpg'),
(62,33,'upload/no-img.jpg'),
(63,33,'upload/no-img.jpg'),
(64,24,'upload/no-img.jpg'),
(65,25,'upload/no-img.jpg');

/*Table structure for table `parametre` */

DROP TABLE IF EXISTS `parametre`;

CREATE TABLE `parametre` (
  `DUREE_ANNONCE` date NOT NULL,
  PRIMARY KEY (`DUREE_ANNONCE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `parametre` */

/*Table structure for table `rubrique` */

DROP TABLE IF EXISTS `rubrique`;

CREATE TABLE `rubrique` (
  `ID_RUBRIQUE` int(4) NOT NULL AUTO_INCREMENT,
  `LIBELLE` varchar(30) NOT NULL,
  PRIMARY KEY (`ID_RUBRIQUE`),
  UNIQUE KEY `ID_RUBRIQUE` (`ID_RUBRIQUE`),
  UNIQUE KEY `LIBELLE` (`LIBELLE`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;

/*Data for the table `rubrique` */

insert  into `rubrique`(`ID_RUBRIQUE`,`LIBELLE`) values 
(6,'Animaux'),
(29,'Argent'),
(4,'CHANGER'),
(44,'Emploi'),
(2,'Immobilier'),
(1,'Informatique'),
(51,'jghv'),
(5,'mySQL'),
(25,'Vacances'),
(3,'Vehicule');

/*Table structure for table `utilisateur` */

DROP TABLE IF EXISTS `utilisateur`;

CREATE TABLE `utilisateur` (
  `ID_USER` int(4) NOT NULL AUTO_INCREMENT,
  `MDP` varchar(224) NOT NULL,
  `NOM` varchar(30) NOT NULL,
  `PRENOM` varchar(30) DEFAULT NULL,
  `MAIL` varchar(50) DEFAULT NULL,
  `ADMINISTRATEUR` enum('non','oui') DEFAULT NULL,
  `CONFIRMATION_KEY` varchar(255) NOT NULL,
  `COMPTE_ACTIF` int(1) DEFAULT '0',
  `KEY_MDP` varchar(255) DEFAULT NULL,
  `SAVE_MDP` varchar(224) DEFAULT NULL,
  PRIMARY KEY (`ID_USER`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

/*Data for the table `utilisateur` */

insert  into `utilisateur`(`ID_USER`,`MDP`,`NOM`,`PRENOM`,`MAIL`,`ADMINISTRATEUR`,`CONFIRMATION_KEY`,`COMPTE_ACTIF`,`KEY_MDP`,`SAVE_MDP`) values 
(1,'ReNiTiAlIsE1,.M,d;P','Petrel','Pierre','pierre@hotmail.fr','non','',1,NULL,'ReNiTiAlIsE1,.M,d;P'),
(4,'carotte','Chasseur','Jean','fusil46@gmail.com','non','',1,NULL,'carotte'),
(5,'LEMOTDEPASSE','CARA','MALINE','boubi@gmail.com','non','',1,NULL,'LEMOTDEPASSE'),
(6,'9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08','Le MANCHOT','PAULO','LE MANCHOT','non','',1,NULL,'9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08'),
(13,'de974ee639e7a46e866dcfaaf7445b3b715a7eb56d956101932a0d7254e0e1d0','Aulas','Jean-Michel','user@hotmail.fr','non','',1,NULL,'de974ee639e7a46e866dcfaaf7445b3b715a7eb56d956101932a0d7254e0e1d0'),
(15,'50834064b84590ae6338309d4107a374d4a5516a12584051cc39c92061edba21','Administrateur','P-A','admin@admin.fr','oui','',1,NULL,'50834064b84590ae6338309d4107a374d4a5516a12584051cc39c92061edba21'),
(37,'ada46cdae368268424ebc17da5ac243020f7f46bcc82a53fe8d849a4234dabd0','Pétrel','Pierre-Alain','pierrealainpetrel@hotmail.fr','non','42233241880167',1,'56359958292880','ada46cdae368268424ebc17da5ac243020f7f46bcc82a53fe8d849a4234dabd0');

/* Trigger structure for table `utilisateur` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `test` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `test` BEFORE INSERT ON `utilisateur` FOR EACH ROW BEGIN
	SET new.mdp =SHA2(new.mdp, 256), new.save_mdp = (new.mdp);
    END */$$


DELIMITER ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
