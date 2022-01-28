-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 22 avr. 2021 à 22:10
-- Version du serveur :  10.4.17-MariaDB
-- Version de PHP : 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `network`
--

-- --------------------------------------------------------

--
-- Structure de la table `aime`
--

CREATE TABLE `aime` (
  `id` int(11) NOT NULL,
  `poste` int(11) NOT NULL,
  `pers` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `aime`
--

INSERT INTO `aime` (`id`, `poste`, `pers`) VALUES
(151, 43, 1),
(157, 46, 1),
(158, 45, 14),
(165, 44, 18);

-- --------------------------------------------------------

--
-- Structure de la table `commentaire`
--

CREATE TABLE `commentaire` (
  `id` int(11) NOT NULL,
  `poste` int(11) NOT NULL,
  `commentaire` text NOT NULL,
  `pers` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `commentaire`
--

INSERT INTO `commentaire` (`id`, `poste`, `commentaire`, `pers`, `date`) VALUES
(2, 47, 'On va le gagner !!', 1, '2021-04-22 10:40:25'),
(3, 46, ';)', 1, '2021-04-22 10:43:52'),
(5, 44, 'on a hate !', 18, '2021-04-22 22:00:11');

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `envoi` int(11) NOT NULL,
  `recoit` int(11) NOT NULL,
  `message` text NOT NULL,
  `lu` int(11) NOT NULL DEFAULT 0,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `message`
--

INSERT INTO `message` (`id`, `envoi`, `recoit`, `message`, `lu`, `date`) VALUES
(5, 1, 1, 'salut mon pote', 1, '2021-04-20 21:08:03'),
(6, 15, 1, 'Salut !', 1, '2021-04-20 21:08:03'),
(7, 1, 1, '2eme message', 0, '2021-04-20 21:22:29'),
(8, 1, 15, 'idem !', 1, '2021-04-20 22:11:17'),
(10, 1, 15, 'comment tu vas ?', 1, '2021-04-21 10:09:57'),
(11, 15, 1, 'ca va', 1, '2021-04-22 17:36:53'),
(14, 18, 1, 'bonjour ', 1, '2021-04-22 22:01:11'),
(15, 1, 18, 'bonjour ', 1, '2021-04-22 22:01:53');

-- --------------------------------------------------------

--
-- Structure de la table `publication`
--

CREATE TABLE `publication` (
  `id` int(11) NOT NULL,
  `titre` text NOT NULL,
  `texte` text NOT NULL,
  `pers1` int(11) DEFAULT NULL,
  `image` blob DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `publication`
--

INSERT INTO `publication` (`id`, `titre`, `texte`, `pers1`, `image`, `date`) VALUES
(42, 'le roi pelé !!', 'champion !!', 1, 0x34322e6a7067, '2021-04-18 16:59:31'),
(43, 'mbappe', 'je suis fan de mbappe', 1, NULL, '2021-04-18 16:59:59'),
(44, 'coupe du monde ', 'dans 100 jours !!', 1, 0x34342e6a7067, '2021-04-18 17:00:50'),
(45, 'coupe du monde ', 'dans 100 jours !!', 15, 0x34352e6a7067, '2021-04-18 18:28:53'),
(46, 'le roi de la pop ', 'Michael Jackson [ˈmaɪkəl ˈdʒæksən]Note 1, né le 29 août 1958 à Gary (Indiana) et mort le 25 juin 2009 à Los Angeles (Californie), est un auteur-compositeur-interprète, danseur-chorégraphe et acteur américain.', 14, 0x34362e6a7067, '2021-04-21 13:12:20'),
(47, 'FFF', 'bientôt l\'euro !!!', 13, 0x34372e6a7067, '2021-04-21 21:34:56'),
(51, 'Allemagne', 'allemagne !!', 18, 0x35312e706e67, '2021-04-22 22:00:36');

-- --------------------------------------------------------

--
-- Structure de la table `suivre`
--

CREATE TABLE `suivre` (
  `id` int(11) NOT NULL,
  `suiveur` int(11) NOT NULL,
  `suivi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `suivre`
--

INSERT INTO `suivre` (`id`, `suiveur`, `suivi`) VALUES
(29, 1, 14),
(31, 1, 15),
(32, 15, 14),
(33, 1, 1),
(37, 18, 1),
(38, 18, 15);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(10) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `photo` text DEFAULT NULL,
  `prive` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`, `nom`, `prenom`, `photo`, `prive`) VALUES
(1, 'julesWtb', 'jules.wintrebert@outlook.fr', 'pass', 'Wintrebert', 'Jules', '1.jpg', 0),
(13, 'Oscar2', 'jules.wintrebert@laposte.net', 'pass', 'Wintrebert', 'Oscar', '13.jpg', 0),
(14, 'pseudo3', '3@laposte.net', 'pass', 'trois', 'arthur', '14.jpg', 0),
(15, 'projet', 'projet@laposte.net', 'pass', 'projet', 'john', 'base.png', 1),
(18, 'test', 'test@laposte.net', 'pass', 'test', 'test', '18.png', 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `aime`
--
ALTER TABLE `aime`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `publication`
--
ALTER TABLE `publication`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `suivre`
--
ALTER TABLE `suivre`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `aime`
--
ALTER TABLE `aime`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=166;

--
-- AUTO_INCREMENT pour la table `commentaire`
--
ALTER TABLE `commentaire`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `publication`
--
ALTER TABLE `publication`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT pour la table `suivre`
--
ALTER TABLE `suivre`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
