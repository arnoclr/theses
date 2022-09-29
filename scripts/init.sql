-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : jeu. 29 sep. 2022 à 14:37
-- Version du serveur : 5.7.33
-- Version de PHP : 7.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `theses`
--

-- --------------------------------------------------------

--
-- Structure de la table `people`
--

CREATE TABLE `people` (
  `id` int(11) NOT NULL,
  `idref` varchar(255) DEFAULT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `theses`
--

CREATE TABLE `theses` (
  `iddoc` int(10) UNSIGNED NOT NULL,
  `nnt` char(12) NOT NULL,
  `status` varchar(255) NOT NULL,
  `online` tinyint(1) NOT NULL,
  `source` varchar(255) NOT NULL,
  `discipline` varchar(255) NOT NULL,
  `president_jury` int(11) DEFAULT NULL,
  `lang` char(2) NOT NULL,
  `timestamp` timestamp NOT NULL,
  `code_etab` char(4) NOT NULL,
  `title` varchar(255) NOT NULL,
  `summary` text,
  `subjects` varchar(255) DEFAULT NULL,
  `partners` varchar(255) DEFAULT NULL,
  `oai_set_specs` varchar(255) NOT NULL,
  `embargo` date DEFAULT NULL,
  `establishments` varchar(255) NOT NULL,
  `wip` tinyint(1) NOT NULL,
  FULLTEXT `search_content` (`title`,`summary`,`subjects`,`partners`,`establishments`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `theses_people`
--

CREATE TABLE `theses_people` (
  `iddoc` int(10) UNSIGNED NOT NULL,
  `id` int(11) NOT NULL,
  `role` char(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `people`
--
ALTER TABLE `people`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `theses`
--
ALTER TABLE `theses`
  ADD PRIMARY KEY (`iddoc`);

--
-- Index pour la table `theses_people`
--
ALTER TABLE `theses_people`
  ADD KEY `iddoc` (`iddoc`,`id`),
  ADD KEY `id` (`id`),
  ADD KEY `field` (`role`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `people`
--
ALTER TABLE `people`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `theses_people`
--
ALTER TABLE `theses_people`
  ADD CONSTRAINT `theses_people_ibfk_1` FOREIGN KEY (`iddoc`) REFERENCES `theses` (`iddoc`),
  ADD CONSTRAINT `theses_people_ibfk_2` FOREIGN KEY (`id`) REFERENCES `people` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
