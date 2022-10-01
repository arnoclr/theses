-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : sam. 01 oct. 2022 à 16:15
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
-- Structure de la table `establishments`
--

CREATE TABLE `establishments` (
  `code_etab` char(4) NOT NULL,
  `region` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `establishments`
--

INSERT INTO `establishments` (`code_etab`, `region`, `name`) VALUES
('AGPT', 'FR-IDF', 'AgroParisTech'),
('AGUY', 'FR-PAC', 'Antilles-Guyane'),
('AIX1', 'FR-PAC', 'Aix-Marseille 1'),
('AIX2', 'FR-PAC', 'Aix-Marseille 2'),
('AIX3', 'FR-PAC', 'Aix-Marseille 3'),
('AIXM', 'FR-PAC', 'Aix-Marseille'),
('ALGF', '0', 'Alger (1909-1962)'),
('AMIE', 'FR-HDF', 'Amiens'),
('ANGE', 'FR-PDL', 'Angers'),
('ANTI', '0', 'Antilles'),
('ARTO', 'FR-NOR', 'Artois'),
('ASSA', 'FR-IDF', 'Université Paris-Panthéon-Assas'),
('AVIG', 'FR-PAC', 'Avignon'),
('AZUR', 'FR-PAC', 'Université Côte d\'Azur (ComUE)'),
('BELF', 'FR-BFC', 'Belfort Montbéliard'),
('BESA', 'FR-BFC', 'Besançon'),
('BOR1', 'FR-NAQ', 'Bordeaux 1'),
('BOR2', 'FR-NAQ', 'Bordeaux 2'),
('BOR3', 'FR-NAQ', 'Bordeaux 3'),
('BOR4', 'FR-NAQ', 'Bordaux 4'),
('BORD', 'FR-NAQ', 'Bordeaux'),
('BORU', 'FR-NAQ', 'Université de Bordeaux (1441-1970)'),
('BRES', 'FR-BRE', 'Brest - Bretagne occidentale'),
('CAEN', 'FR-NOR', 'Caen'),
('CERG', 'FR-IDF', 'Cergy-Pontoise'),
('CHAM', 'FR-ARA', 'Chambéry'),
('CLF1', 'FR-ARA', 'Clermont-Ferrand 1'),
('CLF2', 'FR-ARA', 'Clermont-Ferrand 2'),
('CLFA', 'FR-ARA', 'Université Clermont Auvergne (2017-2020)'),
('CLFD', 'FR-ARA', 'Université de Clermont-Ferrand'),
('CLIL', 'FR-HDF', 'Centrale Lille Institut'),
('CNAM', 'FR-IDF', 'Conservatoire national des arts et métiers'),
('COAZ', 'FR-PAC', 'Université Côte d\'Azur'),
('COMP', 'FR-HDF', 'Compiègne'),
('CORT', 'FR-20R', 'Corte'),
('CSUP', 'FR-IDF', 'CentraleSupélec'),
('CYUN', 'FR-IDF', 'CY Cergy Paris Université'),
('DENS', 'FR-IDF', 'Ecole normale supérieure - Cachan'),
('DIJO', 'FR-BFC', 'Dijon'),
('DUNK', 'FR-HDF', 'Littoral Dunkerque'),
('ECAP', 'FR-IDF', 'Ecole centrale des arts et manufactures de Paris'),
('ECDL', 'FR-ARA', 'Ecole centrale de Lyon'),
('ECDM', 'FR-PAC', 'Ecole centrale de Marseille'),
('ECLI', 'FR-HDF', 'Ecole centrale de Lille'),
('EHEC', 'FR-IDF', 'Ecole des hautes études commerciales'),
('EHES', 'FR-IDF', 'Ecole des hautes études en sciences sociales'),
('EIAA', 'FR-IDF', 'Ecole nationale supérieure des industries alimentaires - Massy'),
('EMAC', 'FR-OCC', 'Ecole nationale des Mines d\'Albi-Carmaux'),
('EMAL', 'FR-OCC', 'IMT Mines Alès'),
('EMNA', 'FR-PDL', 'Ecole des Mines de Nantes'),
('EMSE', 'FR-ARA', 'Ecole nationale supérieure des Mines - Saint-Etienne'),
('ENAC', 'FR-OCC', 'Ecole nationale de l\'aviation civile'),
('ENAM', 'FR-IDF', 'Ecole nationale supérieure d\'arts et métiers'),
('ENCM', 'FR-OCC', 'Ecole nationale supérieure de chimie de Montpellier'),
('ENCP', 'FR-IDF', 'Ecole nationale des chartes'),
('ENCR', 'FR-BRE', 'Ecole nationale supérieure de chimie de Rennes'),
('ENGR', 'FR-GES', 'Ecole nationale du génie rural, des eaux et forêts'),
('ENIB', 'FR-BRE', 'Ecole nationale d\'ingénieurs de Brest'),
('ENIS', 'FR-ARA', 'Ecole nationale d\'ingénieurs de Saint-Etienne'),
('ENMP', 'FR-IDF', 'Ecole nationale supérieure des Mines - Paris'),
('ENPC', 'FR-IDF', 'Ecole nationale des ponts et chaussées'),
('ENSA', 'FR-OCC', 'Ecole nationale supérieure d\'agronomie - Montpellier'),
('ENSF', 'FR-ARA', 'Ecole normale supérieure lettres et sciences humaines - Lyon\n(anciennement Ecole normale supérieure lettres et sciences humaines - Fontenay-Saint-Cloud'),
('ENSL', 'FR-ARA', 'Ecole normale supérieure de Lyon'),
('ENSR', 'FR-BRE', 'Ecole normale supérieure de Rennes'),
('ENST', 'FR-IDF', 'Ecole nationale supérieure des télécommunications'),
('ENSU', 'FR-IDF', 'Ecole normale supérieure- Paris (rue d\'Ulm)'),
('ENTA', 'FR-BRE', 'Ecole nationale supérieure de techniques avancées Bretagne'),
('ENTP', 'FR-ARA', 'Ecole nationale des travaux publics'),
('ENVA', 'FR-IDF', 'Ecole nationale vétérinaire - Maisons Alfort'),
('EPHE', 'FR-IDF', 'Ecole pratique des hautes études'),
('EPXX', 'FR-IDF', 'Ecole polytechnique'),
('ESAE', 'FR-OCC', 'ISAE\n(Fusion de SUPAERO et ENSICA à  partir de 2007-01-01)'),
('ESEC', 'FR-IDF', 'Ecole supérieure des sciences économiques et commerciales'),
('ESMA', 'FR-OCC', 'Ecole nationale supérieure de mécanique et d\'aérotechnique'),
('ESTA', 'FR-IDF', 'Ecole nationale supérieure de techniques avancées'),
('EVRY', 'FR-IDF', 'Evry Val d\'Essonne'),
('GFDE', 'FR-ARA', 'Faculté de Droit et Sciences économiques de Grenoble'),
('GFLH', 'FR-ARA', 'Faculté de Lettres et Sciences humaines de Grenoble'),
('GLOB', 'FR-IDF', 'Institut de physique du Globe'),
('GRAL', 'FR-ARA', 'Université Grenoble Alpes'),
('GRE1', 'FR-ARA', 'Grenoble 1'),
('GRE2', 'FR-ARA', 'Grenoble 2'),
('GRE3', 'FR-ARA', 'Grenoble 3'),
('GREA', 'FR-ARA', 'Université Grenoble Alpes (ComUE)'),
('GREN', 'FR-ARA', 'Grenoble'),
('GRFD', 'FR-ARA', 'Faculté de droit de Grenoble'),
('GRFL', 'FR-ARA', 'Faculté de Lettres de Grenoble'),
('HESA', 'FR-IDF', 'HESAM'),
('HESP', 'FR-BRE', 'Ecole des hautes études en santé publique - Rennes'),
('IAVF', 'FR-IDF', 'Institut agronomique, vétérinaire et forestier de France - Paris'),
('IEPP', 'FR-IDF', 'Institut d\'études politiques - Paris'),
('IMTA', 'FR-PDL', 'Ecole nationale supérieure Mines-Télécom Atlantique Bretagne Pays de la Loire'),
('INAL', 'FR-IDF', 'Institut national des langues et civilisations orientales (INALCO)'),
('INAP', 'FR-IDF', 'Institut national d\'agronomie - Paris Grignon'),
('INPG', 'FR-ARA', 'Institut national polytechnique - Grenoble'),
('INPL', 'FR-GES', 'Institut national polytechnique - Lorraine'),
('INPT', 'FR-OCC', 'Institut national polytechnique - Toulouse'),
('IOTA', 'FR-IDF', 'Institut d\'optique théorique et appliquée - Palaiseau'),
('IPPA', 'FR-IDF', 'Institut Polytechnique de Paris'),
('ISAB', 'FR-CVL', 'Institut national des sciences appliquées Val de Loire - Bourges'),
('ISAL', 'FR-ARA', 'Institut national des sciences appliquées - Lyon'),
('ISAM', 'FR-NOR', 'Institut national des sciences appliquées - Rouen'),
('ISAR', 'FR-BRE', 'Institut national des sciences appliquées - Rennes'),
('ISAT', 'FR-OCC', 'Institut national des sciences appliquées - Toulouse'),
('LARE', 'FR-LRE', 'La Réunion'),
('LARO', 'FR-NAQ', 'La Rochelle'),
('LEHA', 'FR-NOR', 'Le Havre'),
('LEMA', 'FR-CVL', 'Le Mans'),
('LIFS', 'FR-HDF', 'Faculté des sciences de Lille'),
('LIL1', 'FR-HDF', 'Lille 1'),
('LIL2', 'FR-HDF', 'Lille 2'),
('LIL3', 'FR-HDF', 'Lille 3'),
('LILL', 'FR-HDF', 'Université de Lille'),
('LILU', 'FR-HDF', 'Université de Lille (2018-2021)'),
('LIMO', 'FR-NAQ', 'Limoges'),
('LORI', 'FR-BRE', 'Lorient - Bretagne sud'),
('LORR', 'FR-GES', 'Université de Lorraine'),
('LY01', 'FR-ARA', 'Ecole nationale vétérinaire - Lyon'),
('LYFM', 'FR-ARA', 'Faculté de médecine et de pharmacie de Lyon'),
('LYFS', 'FR-ARA', 'Faculté de sciences de Lyon'),
('LYMP', 'FR-ARA', 'Ecole préparatoire de médecine et de pharmacie de Lyon'),
('LYO1', 'FR-ARA', 'Lyon 1'),
('LYO2', 'FR-ARA', 'Lyon 2'),
('LYO3', 'FR-ARA', 'Lyon 3'),
('LYON', 'FR-ARA', 'Université de Lyon'),
('LYSE', 'FR-ARA', 'Lyon (COMUE)'),
('MARN', 'FR-IDF', 'Marne la Vallée'),
('METZ', 'FR-GES', 'Metz'),
('MNHN', 'FR-IDF', 'Museum d\'histoire naturelle'),
('MON1', 'FR-OCC', 'Montpellier 1'),
('MON2', 'FR-OCC', 'Montpellier 2'),
('MON3', 'FR-OCC', 'Montpellier 3'),
('MONT', 'FR-OCC', 'Montpellier'),
('MTLD', 'FR-HDF', 'Ecole nationale supérieure Mines-Télécom Lille Douai'),
('MULH', 'FR-GES', 'Mulhouse'),
('NAFD', 'FR-GES', 'Faculté de droit de Nancy'),
('NAFL', 'FR-GES', 'Faculté des lettres de Nancy'),
('NAFS', 'FR-GES', 'Faculté des sciences de Nancy'),
('NAN1', 'FR-GES', 'Nancy 1'),
('NAN2', 'FR-GES', 'Nancy 2'),
('NANT', 'FR-PDL', 'Nantes'),
('NCAL', '0', 'Nouvelle Calédonie'),
('NICE', 'FR-PAC', 'Nice'),
('NIME', 'FR-OCC', 'Nîmes'),
('NORM', 'FR-NOR', 'Normandie (COMUE)'),
('NSAI', 'FR-BRE', 'Ecole nationale de la Statistique et de l\'Analyse de l\'Information - Rennes'),
('NSAM', 'FR-OCC', 'SupAgro - Montpellier'),
('NSAR', 'FR-BRE', 'Agrocampus - Rennes'),
('OBSP', 'FR-IDF', 'Observatoire de Paris'),
('ONIR', 'FR-PDL', 'Ecole nationale vétérinaire - Nantes'),
('ORLE', 'FR-CVL', 'Orléans'),
('PA00', 'FR-IDF', 'Université de Paris (1896-1968)'),
('PA01', 'FR-IDF', 'Paris 1'),
('PA02', 'FR-IDF', 'Paris 2'),
('PA03', 'FR-IDF', 'Paris 3'),
('PA04', 'FR-IDF', 'Paris 4'),
('PA05', 'FR-IDF', 'Paris 5'),
('PA06', 'FR-IDF', 'Paris 6'),
('PA07', 'FR-IDF', 'Paris 7'),
('PA08', 'FR-IDF', 'Paris 8'),
('PA09', 'FR-IDF', 'Paris 9'),
('PA10', 'FR-IDF', 'Paris 10'),
('PA11', 'FR-IDF', 'Paris 11'),
('PA12', 'FR-IDF', 'Paris 12'),
('PA13', 'FR-IDF', 'Paris 13'),
('PACI', '0', 'Pacifique'),
('PAFD', 'FR-IDF', 'Faculté de Droit de Paris'),
('PAFL', 'FR-IDF', 'Faculté des Lettres de Paris'),
('PAFM', 'FR-IDF', 'Faculté de Médecine de Paris'),
('PAFS', 'FR-IDF', 'Faculté des Sciences de Paris'),
('PAPH', 'FR-IDF', 'Ecole supérieure de pharmacie de Paris'),
('PATC', 'FR-IDF', 'Faculté de Théologie catholique de Paris'),
('PATP', 'FR-IDF', 'Faculté de Théologie protestante de Paris'),
('PAUU', 'FR-NAQ', 'Pau'),
('PERP', 'FR-OCC', 'Perpignan'),
('PESC', 'FR-IDF', 'Paris Est (COMUE)'),
('PEST', 'FR-IDF', 'Paris Est (PRES)'),
('POIT', 'FR-NAQ', 'Poitiers'),
('POLF', '0', 'Polynésie française'),
('PSLE', 'FR-IDF', 'Paris Sciences et Lettres (ComUE)'),
('REIM', 'FR-GES', 'Reims'),
('REN0', 'FR-BRE', 'Université de Rennes'),
('REN1', 'FR-BRE', 'Rennes 1'),
('REN2', 'FR-BRE', 'Rennes 2'),
('ROUE', 'FR-NOR', 'Rouen'),
('SACL', 'FR-IDF', 'Université Paris-Saclay (ComUE)'),
('SORU', 'FR-IDF', 'Sorbonne université'),
('STET', 'FR-ARA', 'Saint-Etienne'),
('STR1', 'FR-GES', 'Strasbourg 1'),
('STR2', 'FR-GES', 'Strasbourg 2'),
('STR3', 'FR-GES', 'Strasbourg 3'),
('STRA', 'FR-GES', 'Strasbourg'),
('SUPL', 'FR-IDF', 'Supélec'),
('TELB', 'FR-BRE', 'Ecole nationale supérieure des Telecom de Bretagne - Brest'),
('TELE', 'FR-IDF', 'Institut national des télécommunications'),
('TOU1', 'FR-OCC', 'Toulouse 1'),
('TOU2', 'FR-OCC', 'Toulouse 2'),
('TOU3', 'FR-OCC', 'Toulouse 3'),
('TOUL', 'FR-PAC', 'Toulon'),
('TOUR', 'FR-CVL', 'Tours'),
('TROY', 'FR-GES', 'Troyes'),
('UBSC', 'FR-BFC', 'Bourgogne Franche-Comté'),
('UCFA', 'FR-ARA', 'Université Clermont-Auvergne (2021-...)'),
('UEFL', 'FR-IDF', 'Université Gustave Eiffel'),
('ULIL', 'FR-HDF', 'Université de Lille (2022-....)'),
('UNIP', 'FR-IDF', 'Université de Paris (2019-....)'),
('UPAS', 'FR-IDF', 'Université Paris-Saclay'),
('UPHF', 'FR-HDF', 'Université Polytechnique des Hauts-de-France - Valenciennes'),
('UPSL', 'FR-IDF', 'Université Paris sciences et lettres'),
('USPC', 'FR-IDF', 'Sorbonne Paris Cité'),
('VALE', 'FR-HDF', 'Valenciennes'),
('VERS', 'FR-IDF', 'Versailles St Quentin en Yvelines'),
('YANE', 'FR-GF', 'Guyane');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `establishments`
--
ALTER TABLE `establishments`
  ADD PRIMARY KEY (`code_etab`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
