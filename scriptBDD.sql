-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Ven 29 Septembre 2017 à 11:42
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `hse01`
--

-- --------------------------------------------------------

--
-- Structure de la table `hse_administration`
--

CREATE TABLE IF NOT EXISTS `hse_administration` (
  `ADM_ID` int(11) NOT NULL AUTO_INCREMENT,
  `ADM_NOM` varchar(40) DEFAULT NULL,
  `ADM_PRENOM` varchar(40) DEFAULT NULL,
  `ADM_LOGIN` varchar(40) DEFAULT NULL,
  `ADM_MDP` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`ADM_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `hse_administration`
--

INSERT INTO `hse_administration` (`ADM_ID`, `ADM_NOM`, `ADM_PRENOM`, `ADM_LOGIN`, `ADM_MDP`) VALUES
(2, 'NomADMIN', 'PrenomADMIN', 'admin.t', '7c1681a524f9467fd043ce357ed7c6f2b4326a12');

-- --------------------------------------------------------

--
-- Structure de la table `hse_classes`
--

CREATE TABLE IF NOT EXISTS `hse_classes` (
  `CLASSE_ID` int(11) NOT NULL AUTO_INCREMENT,
  `CLASSE_LIBELLE` text,
  `CLASSE_SIGLE` varchar(20) DEFAULT NULL,
  `affListeKholle` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`CLASSE_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Contenu de la table `hse_classes`
--

INSERT INTO `hse_classes` (`CLASSE_ID`, `CLASSE_LIBELLE`, `CLASSE_SIGLE`, `affListeKholle`) VALUES
(1, 'BTS Services Informatiques aux Organisations 1ère année', 'BTS SIO1', 0),
(5, 'BTS Négociation Relation Client 1ère année', 'BTS NRC1', 0),
(7, 'BTS Services Informatiques aux Organisations 2ème année', 'BTS SIO2', 0),
(8, 'BTS Négociation Relation Client 2ème année', 'BTS NRC2', 0),
(10, 'BTS Management des Unités Commerciales 1ère année', 'BTS MUC1', 0),
(11, 'Prépa 1', 'PECT1', 1),
(12, 'Prépa 2', 'PECT2', 1),
(13, 'Prépa 3', 'PECT3', 1),
(14, 'Aucune classe', 'Aucune', 0),
(15, 'Regroupement de classes', 'Regroupement', 0);

-- --------------------------------------------------------

--
-- Structure de la table `hse_declarations`
--

CREATE TABLE IF NOT EXISTS `hse_declarations` (
  `DECLARATION_ID` int(11) NOT NULL AUTO_INCREMENT,
  `ENSEIGNANT_ID` int(11) NOT NULL,
  `TYPEACTIVITE_ID` int(11) NOT NULL,
  `CLASSE_ID` int(11) DEFAULT NULL,
  `DUREE` int(11) DEFAULT NULL,
  `NBETUDIANTS` int(11) NOT NULL,
  `DUREEPARETUDIANT` int(11) NOT NULL,
  `DECLARATION_DATE` date DEFAULT NULL,
  `PRECISIONSADMIN` text,
  `PRECISIONSPROF` text,
  `DEJAEXPORTE` tinyint(1) DEFAULT NULL,
  `DATETIMESAISIE` datetime DEFAULT NULL,
  PRIMARY KEY (`DECLARATION_ID`),
  KEY `FK_APPARTENIR` (`CLASSE_ID`),
  KEY `FK_CORRESPONDRE` (`TYPEACTIVITE_ID`),
  KEY `FK_EFFECTUER` (`ENSEIGNANT_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=59 ;

--
-- Contenu de la table `hse_declarations`
--

INSERT INTO `hse_declarations` (`DECLARATION_ID`, `ENSEIGNANT_ID`, `TYPEACTIVITE_ID`, `CLASSE_ID`, `DUREE`, `NBETUDIANTS`, `DUREEPARETUDIANT`, `DECLARATION_DATE`, `PRECISIONSADMIN`, `PRECISIONSPROF`, `DEJAEXPORTE`, `DATETIMESAISIE`) VALUES
(48, 3, 1, 10, 25, 5, 5, '2020-01-01', 'aujourd''hui l''o''l''o''l', NULL, 1, '2017-06-16 14:55:09'),
(49, 3, 2, 10, 5, 0, 0, '2024-01-01', 'à à à à', NULL, 1, '2017-06-16 14:55:57'),
(50, 3, 1, 11, 100, 5, 20, '2019-01-01', NULL, NULL, 1, '2017-06-22 09:05:54'),
(51, 3, 2, 1, 5, 0, 0, '2019-01-01', NULL, NULL, 1, '2017-06-22 09:06:35'),
(56, 3, 5, 15, 44, 0, 0, '2020-01-01', 'éàéàé''l''l''', 's''a''l''u''t''éàéàé', 1, '2017-06-26 10:45:57'),
(57, 3, 5, 14, 50, 0, 0, '2020-01-01', NULL, 'Test', 1, '2017-06-28 09:32:07'),
(58, 3, 2, 14, 150, 0, 0, '2020-01-01', NULL, '', 0, '2017-06-29 11:08:22');

-- --------------------------------------------------------

--
-- Structure de la table `hse_enseignants`
--

CREATE TABLE IF NOT EXISTS `hse_enseignants` (
  `ENSEIGNANT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `ENSEIGNANT_NOM` varchar(40) DEFAULT NULL,
  `ENSEIGNANT_PRENOM` varchar(40) DEFAULT NULL,
  `ENSEIGNANT_LOGIN` varchar(40) DEFAULT NULL,
  `ENSEIGNANT_MDP` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`ENSEIGNANT_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `hse_enseignants`
--

INSERT INTO `hse_enseignants` (`ENSEIGNANT_ID`, `ENSEIGNANT_NOM`, `ENSEIGNANT_PRENOM`, `ENSEIGNANT_LOGIN`, `ENSEIGNANT_MDP`) VALUES
(3, 'MARTIN', 'Prénom', 'prof.t', 'ef5e39d345ed4ea0526219be4b4058ab4db86bfe'),
(4, 'NomProf', 'PrénomProf', 'prof.t2', 'ef5e39d345ed4ea0526219be4b4058ab4db86bfe');

-- --------------------------------------------------------

--
-- Structure de la table `hse_parametres`
--

CREATE TABLE IF NOT EXISTS `hse_parametres` (
  `ANNEESCOLAIRE` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `hse_parametres`
--

INSERT INTO `hse_parametres` (`ANNEESCOLAIRE`) VALUES
('2017/2018');

-- --------------------------------------------------------

--
-- Structure de la table `hse_typeactivite`
--

CREATE TABLE IF NOT EXISTS `hse_typeactivite` (
  `TYPEACTIVITE_ID` int(11) NOT NULL AUTO_INCREMENT,
  `TYPEACTIVITE_LIBELLE` text,
  PRIMARY KEY (`TYPEACTIVITE_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `hse_typeactivite`
--

INSERT INTO `hse_typeactivite` (`TYPEACTIVITE_ID`, `TYPEACTIVITE_LIBELLE`) VALUES
(1, 'Khôlle'),
(2, 'Passerelle'),
(3, 'Accompagnement personnalisé'),
(4, 'Remplacement courte durée'),
(5, 'Autre');

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `hse_vue_kholle`
--
CREATE TABLE IF NOT EXISTS `hse_vue_kholle` (
`type` int(11)
);
-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `hse_vue_listedeclarations`
--
CREATE TABLE IF NOT EXISTS `hse_vue_listedeclarations` (
`decID` int(11)
,`ensId` int(11)
,`ensNom` varchar(40)
,`ensPrenom` varchar(40)
,`decDate` date
,`classeID` int(11)
,`typeActiviteID` int(11)
,`duree` int(11)
,`nbetudiants` int(11)
,`dureeParEtudiant` int(11)
,`dejaexporte` tinyint(1)
);
-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `hse_vue_listeexportcsv`
--
CREATE TABLE IF NOT EXISTS `hse_vue_listeexportcsv` (
`EnseignantPrenom` varchar(40)
,`EnseignantNom` varchar(40)
,`Duree` int(11)
,`Date` date
,`Classe` varchar(20)
,`Activite` text
,`Enveloppe` varchar(14)
,`DuréeBaseDécimale` varchar(15)
,`MoisLibellé` varchar(9)
,`Commentaire` text
);
-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `hse_vue_listesanskholle`
--
CREATE TABLE IF NOT EXISTS `hse_vue_listesanskholle` (
`TypeId` int(11)
,`TypeLibelle` text
);
-- --------------------------------------------------------

--
-- Structure de la vue `hse_vue_kholle`
--
DROP TABLE IF EXISTS `hse_vue_kholle`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `hse_vue_kholle` AS select `hse_typeactivite`.`TYPEACTIVITE_ID` AS `type` from `hse_typeactivite` where (`hse_typeactivite`.`TYPEACTIVITE_LIBELLE` = 'Khôlle');

-- --------------------------------------------------------

--
-- Structure de la vue `hse_vue_listedeclarations`
--
DROP TABLE IF EXISTS `hse_vue_listedeclarations`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `hse_vue_listedeclarations` AS select `hse_declarations`.`DECLARATION_ID` AS `decID`,`hse_declarations`.`ENSEIGNANT_ID` AS `ensId`,`hse_enseignants`.`ENSEIGNANT_NOM` AS `ensNom`,`hse_enseignants`.`ENSEIGNANT_PRENOM` AS `ensPrenom`,`hse_declarations`.`DECLARATION_DATE` AS `decDate`,`hse_declarations`.`CLASSE_ID` AS `classeID`,`hse_declarations`.`TYPEACTIVITE_ID` AS `typeActiviteID`,`hse_declarations`.`DUREE` AS `duree`,`hse_declarations`.`NBETUDIANTS` AS `nbetudiants`,`hse_declarations`.`DUREEPARETUDIANT` AS `dureeParEtudiant`,`hse_declarations`.`DEJAEXPORTE` AS `dejaexporte` from (`hse_declarations` join `hse_enseignants`) where (`hse_declarations`.`ENSEIGNANT_ID` = `hse_enseignants`.`ENSEIGNANT_ID`) order by `hse_declarations`.`DATETIMESAISIE`;

-- --------------------------------------------------------

--
-- Structure de la vue `hse_vue_listeexportcsv`
--
DROP TABLE IF EXISTS `hse_vue_listeexportcsv`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `hse_vue_listeexportcsv` AS select `hse_enseignants`.`ENSEIGNANT_PRENOM` AS `EnseignantPrenom`,`hse_enseignants`.`ENSEIGNANT_NOM` AS `EnseignantNom`,`hse_declarations`.`DUREE` AS `Duree`,`hse_declarations`.`DECLARATION_DATE` AS `Date`,`hse_classes`.`CLASSE_SIGLE` AS `Classe`,`hse_typeactivite`.`TYPEACTIVITE_LIBELLE` AS `Activite`,(case `hse_typeactivite`.`TYPEACTIVITE_LIBELLE` when 'Khôlle' then 'KHOLLES' when 'Remplacement courte durée' then 'REMPLACEMENT' when 'Accompagnement personnalisé' then 'ACCOMPAGNEMENT' when 'Autre' then 'AUTRE' end) AS `Enveloppe`,replace(round((`hse_declarations`.`DUREE` / 60),2),'.',',') AS `DuréeBaseDécimale`,(case month(`hse_declarations`.`DECLARATION_DATE`) when '1' then 'JANVIER' when '2' then 'FEVRIER' when '3' then 'MARS' when '4' then 'AVRIL' when '5' then 'MAI' when '6' then 'JUIN' when '7' then 'JUILLET' when '8' then 'AOUT' when '9' then 'SEPTEMBRE' when '10' then 'OCTOBRE' when '11' then 'NOVEMBRE' when '12' then 'DECEMBRE' end) AS `MoisLibellé`,`hse_declarations`.`PRECISIONSPROF` AS `Commentaire` from (((`hse_declarations` join `hse_enseignants`) join `hse_classes`) join `hse_typeactivite`) where ((`hse_declarations`.`ENSEIGNANT_ID` = `hse_enseignants`.`ENSEIGNANT_ID`) and (`hse_declarations`.`CLASSE_ID` = `hse_classes`.`CLASSE_ID`) and (`hse_declarations`.`TYPEACTIVITE_ID` = `hse_typeactivite`.`TYPEACTIVITE_ID`) and (`hse_declarations`.`DEJAEXPORTE` = 0)) order by `hse_enseignants`.`ENSEIGNANT_NOM`,`hse_enseignants`.`ENSEIGNANT_PRENOM`;

-- --------------------------------------------------------

--
-- Structure de la vue `hse_vue_listesanskholle`
--
DROP TABLE IF EXISTS `hse_vue_listesanskholle`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `hse_vue_listesanskholle` AS select `hse_typeactivite`.`TYPEACTIVITE_ID` AS `TypeId`,`hse_typeactivite`.`TYPEACTIVITE_LIBELLE` AS `TypeLibelle` from `hse_typeactivite` where (not((`hse_typeactivite`.`TYPEACTIVITE_LIBELLE` like 'Khôlle')));

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `hse_declarations`
--
ALTER TABLE `hse_declarations`
  ADD CONSTRAINT `FK_APPARTENIR` FOREIGN KEY (`CLASSE_ID`) REFERENCES `hse_classes` (`CLASSE_ID`),
  ADD CONSTRAINT `FK_CORRESPONDRE` FOREIGN KEY (`TYPEACTIVITE_ID`) REFERENCES `hse_typeactivite` (`TYPEACTIVITE_ID`),
  ADD CONSTRAINT `FK_EFFECTUER` FOREIGN KEY (`ENSEIGNANT_ID`) REFERENCES `hse_enseignants` (`ENSEIGNANT_ID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
