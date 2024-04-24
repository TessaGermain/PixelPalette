-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 24 avr. 2024 à 19:59
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `pixelpalette`
--

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `user_id_id` int(11) NOT NULL,
  `picture_id` int(11) NOT NULL,
  `comment` longtext NOT NULL,
  `publish_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `comment`
--

INSERT INTO `comment` (`id`, `user_id_id`, `picture_id`, `comment`, `publish_date`) VALUES
(2, 6, 10, 'J\'aimerai avoir la même maison !', '2024-04-24'),
(3, 6, 15, 'Quel paysage magnifique !', '2024-04-24'),
(4, 6, 19, 'Quel endroit reposant !', '2024-04-24'),
(5, 6, 17, 'Les plus jolies montagnes que j\'ai jamais vu !', '2024-04-24'),
(6, 7, 17, 'Ces montagnes sont incroyables !', '2024-04-24');

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20240326082847', '2024-03-26 08:29:27', 311),
('DoctrineMigrations\\Version20240326085725', '2024-03-26 08:57:33', 9),
('DoctrineMigrations\\Version20240327132848', '2024-03-27 13:29:16', 42);

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `picture`
--

CREATE TABLE `picture` (
  `id` int(11) NOT NULL,
  `user_id_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `location` varchar(255) NOT NULL,
  `publish_date` date NOT NULL,
  `picture` varchar(255) NOT NULL,
  `likes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `picture`
--

INSERT INTO `picture` (`id`, `user_id_id`, `title`, `description`, `location`, `publish_date`, `picture`, `likes`) VALUES
(10, 7, 'Cabane en bois sur un lac', 'L\'image met en scène une charmante cabane en bois située au bord d\'un paisible lac. La cabane, construite avec des planches de bois usées par le temps, dégage une atmosphère rustique et chaleureuse. Son toit en pente est recouvert de mousse verdoyante, donnant l\'impression qu\'elle est en parfaite harmonie avec son environnement naturel. Des volets en bois encadrent les fenêtres, ajoutant une touche de charme traditionnel à la structure.\r\n', 'Islande (Reykjavik)', '2024-04-24', 'cabane-lac.jpg', 2),
(11, 7, 'Cascade majestueuses', 'L\'image capture la beauté majestueuse des cascades qui dévalent en cascade le long des parois rocheuses, créant un spectacle à couper le souffle. L\'eau cristalline jaillit avec force du sommet des montagnes, formant des torrents tumultueux qui se précipitent dans des bassins en contrebas.', 'Bolivie (La Paz)', '2024-04-24', 'cascade.jpg', 0),
(12, 7, 'Plage de sable fin', 'L\'image présente une plage de sable fin s\'étendant à perte de vue, bordée par des eaux turquoise étincelantes et un ciel d\'un bleu profond. Le sable, d\'une teinte dorée, s\'étend doucement vers l\'horizon, formant des dunes douces et invitantes. Les vagues, légères et régulières, viennent caresser doucement le rivage, laissant derrière elles des traces éphémères dans le sable.', 'France (Ajaccio)', '2024-04-24', 'couche-plage.jpg', 0),
(13, 7, 'Couché de soleil montagneux', 'L\'image capture l\'instant magique où le soleil se couche derrière les sommets majestueux des montagnes, peignant le ciel d\'une palette de couleurs éblouissantes. Les montagnes se dressent en silhouette contre l\'horizon, leur profil découpé contrastant avec les teintes chaudes et vibrantes du ciel.', 'France (Nice)', '2024-04-24', 'couche-soleil.jpg', 0),
(14, 7, 'Forêt en fleur rose', 'L\'image présente une forêt enchantée où les arbres se parent de teintes roses douces et vibrantes. Les branches des arbres s\'entrelacent délicatement, créant un dôme de feuillage rose qui filtre la lumière du soleil et crée une ambiance féérique. Les troncs des arbres, d\'un brun chaud, contrastent avec la douceur des feuilles roses, créant ainsi un contraste saisissant. Des rayons de lumière filtrent à travers les branches, projetant des ombres délicates sur le sol recouvert de feuilles.', 'Japon (Kyoto)', '2024-04-24', 'foret-rose.jpg', 0),
(15, 7, 'Vaste forêt bordant un lac', 'L\'image capture la beauté envoûtante d\'un vaste champ de lavande en pleine floraison, où des rangées de plantes violettes s\'étendent à perte de vue, embaumant l\'air d\'un parfum enivrant. Les rangs ordonnés de lavande créent un motif hypnotique qui ondule doucement sous la brise estivale, formant une mer violette qui se perd à l\'horizon.\r\n', 'Islande (Akureyri)', '2024-04-24', 'lac-foret.jpg', 2),
(16, 7, 'Champ de lavande infini', 'L\'image capture la beauté envoûtante d\'un vaste champ de lavande en pleine floraison, où des rangées de plantes violettes s\'étendent à perte de vue, embaumant l\'air d\'un parfum enivrant. Les rangs ordonnés de lavande créent un motif hypnotique qui ondule doucement sous la brise estivale, formant une mer violette qui se perd à l\'horizon.\r\n', 'France (Marseille)', '2024-04-24', 'lavande.jpg', 0),
(17, 6, 'Montagne herbeuse', 'L\'image capture la majesté d\'une montagne enveloppée de verdure, où les pentes escarpées sont recouvertes d\'une herbe luxuriante qui ondule doucement au gré du vent. Les flancs de la montagne s\'étendent à perte de vue, formant un paysage vallonné et spectaculaire où la nature règne en maître.', 'Lettonie (Riga)', '2024-04-24', 'montagne-verte.jpg', 4),
(18, 6, 'Paysage splendide', 'L\'image capture la splendeur d\'un paysage montagneux où les sommets enneigés se reflètent majestueusement dans les eaux calmes d\'un lac alpin. Les montagnes, imposantes et escarpées, se dressent fièrement contre le ciel bleu azur, formant une toile de fond spectaculaire pour le lac scintillant en contrebas.', 'Lituanie (Panevėžys)', '2024-04-24', 'montagnes-brumeuses.jpg', 0),
(19, 6, 'Ponton sur Lac', 'L\'image présente un ponton pittoresque s\'étendant sur les eaux calmes d\'un lac, offrant une vue spectaculaire sur les environs montagneux. Le ponton, fait de bois vieilli par le temps, s\'intègre harmonieusement dans le paysage naturel, créant un point de connexion entre la terre et l\'eau.', 'Allemagne (Munich)', '2024-04-24', 'ponton.jpg', 0);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `pseudo` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `mail`, `pseudo`, `password`) VALUES
(6, 'titouan@gmail.com', 'Tickev', '$2y$10$wgj7nsuiLChsAZxLLpNpM.MHzwkibg5tM1Sjv.rUQrIW8TahEFpJa'),
(7, 'tessa@gmail.com', 'Tessa', '$2y$10$5lL9ls/PqhzrR.CJ4BRoBuDdL0gD1WlStkBXg0hfSPXF.bTYL0eDK');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_9474526C9D86650F` (`user_id_id`),
  ADD KEY `IDX_9474526CEE45BDBF` (`picture_id`);

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Index pour la table `picture`
--
ALTER TABLE `picture`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_16DB4F899D86650F` (`user_id_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `picture`
--
ALTER TABLE `picture`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `FK_9474526C9D86650F` FOREIGN KEY (`user_id_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_9474526CEE45BDBF` FOREIGN KEY (`picture_id`) REFERENCES `picture` (`id`);

--
-- Contraintes pour la table `picture`
--
ALTER TABLE `picture`
  ADD CONSTRAINT `FK_16DB4F899D86650F` FOREIGN KEY (`user_id_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
