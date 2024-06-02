CREATE DATABASE IF NOT EXISTS OmnesImmobilier;
USE OmnesImmobilier;

DROP TABLE IF EXISTS agents;
CREATE TABLE IF NOT EXISTS agents (
  id int NOT NULL AUTO_INCREMENT,
  utilisateur_id int NOT NULL,
  telephone varchar(20) DEFAULT NULL,
  photo_profil_url varchar(255) DEFAULT NULL,
  cv text,
  disponibilite text,
  PRIMARY KEY (id),
  KEY utilisateur_id (utilisateur_id)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table agents
--

INSERT INTO agents (id, utilisateur_id, telephone, photo_profil_url, cv, disponibilite) VALUES
(1, 14, '+33 1 23 45 67 89', 'photojeanpierre.png', 'CV de Jean-Pierre', 'Lundi au Vendredi'),
(2, 15, '+33 1 98 76 54 32', 'photohugo.webp', 'CV de Hugo', 'Mardi au Samedi');

-- --------------------------------------------------------

--
-- Structure de la table proprietes
--

DROP TABLE IF EXISTS proprietes;
CREATE TABLE IF NOT EXISTS proprietes (
  id int NOT NULL AUTO_INCREMENT,
  propriete_id varchar(50) NOT NULL,
  type_propriete enum('résidentiel','commercial','terrain','appartement') NOT NULL,
  adresse varchar(255) NOT NULL,
  ville varchar(100) NOT NULL,
  description text,
  prix decimal(10,2) NOT NULL,
  dimension varchar(50) NOT NULL,
  photo_url varchar(255) DEFAULT NULL,
  agent_id int DEFAULT NULL,
  digicode varchar(50) DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY propriete_id (propriete_id),
  KEY agent_id (agent_id)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table proprietes
--

INSERT INTO proprietes (id, propriete_id, type_propriete, adresse, ville, description, prix, dimension, photo_url, agent_id, digicode) VALUES
(5, 'P002', 'commercial', '45 Avenue des Champs', 'Paris', 'Local commercial au centre-ville', 1200000.00, '300m²', 'Photo_propriete2.jpeg', 2, '1234'),
(4, 'P001', 'résidentiel', '123 Rue de Paris', 'Paris', 'Maison individuelle avec jardin', 750000.00, '120m²', 'Photo_propriete1.jpeg', 1, '456'),
(6, 'P003', 'terrain', 'Route de la Forêt', 'Marseille', 'Terrain constructible de 5000m²', 300000.00, '5000m²', 'Photo_propriete3.jpeg', 3, '1433'),
(7, 'P004', 'appartement', '67 Boulevard Haussmann', 'Lyon', 'Appartement 2 pièces au centre', 350000.00, '50m²', 'Photo_propriete4.jpeg', 4, '7854'),
(8, 'P005', 'résidentiel', '5 Rue de Rivoli', 'Nice', 'Maison de ville avec garage', 600000.00, '140m²', 'Photo_propriete5.jpg', 5, '54336'),
(9, 'P006', 'résidentiel', '12 Rue de Lyon', 'Bordeaux', 'Appartement 3 pièces avec balcon', 450000.00, '80m²', 'Photo_propriete6.jpeg', 1, '2466'),
(10, 'P007', 'commercial', '98 Avenue de la Liberté', 'Lille', 'Bureau spacieux en open space', 950000.00, '400m²', 'Photo_propriete7.jpeg', 2, '5743'),
(11, 'P008', 'terrain', 'Chemin des Vignes', 'Toulouse', 'Terrain agricole de 10 hectares', 500000.00, '100000m²', 'Photo_propriete8.jpeg', 3, '75335'),
(12, 'P009', 'appartement', '14 Quai de la Gare', 'Nantes', 'Studio moderne proche gare', 200000.00, '30m²', 'Photo_propriete9.jpeg', 4, '1467'),
(13, 'P010', 'résidentiel', '21 Rue Saint-Honoré', 'Strasbourg', 'Maison de campagne avec piscine', 800000.00, '160m²', 'Photo_propriete10.jpg', 5, '5675'),
(14, 'P011', 'résidentiel', '23 Rue de Lille', 'Montpellier', 'Villa luxueuse avec vue mer', 1500000.00, '220m²', 'Photo_propriete11.jpg', 1, '578655'),
(15, 'P012', 'commercial', '11 Avenue Montaigne', 'Rouen', 'Centre commercial avec parking', 2200000.00, '1000m²', 'Photo_propriete12.jpg', 2, '3355'),
(16, 'P013', 'terrain', 'Parc de la Montagne', 'Grenoble', 'Terrain boisé de 15 hectares', 700000.00, '150000m²', 'Photo_propriete13.jpg', 3, '4657'),
(17, 'P014', 'appartement', '30 Rue du Faubourg', 'Dijon', 'Loft industriel rénové', 550000.00, '120m²', 'Photo_propriete14.jpg', 4, '8987'),
(18, 'P015', 'résidentiel', '9 Place de la Concorde', 'Reims', 'Résidence de standing avec piscine', 1000000.00, '200m²', 'Photo_propriete15.jpg', 5, '4754'),
(19, 'P016', 'résidentiel', '17 Rue de la Paix', 'Aix-en-Provence', 'Appartement avec terrasse et vue', 480000.00, '90m²', 'Photo_propriete16.jpg', 1, '24578'),
(20, 'P017', 'commercial', '72 Boulevard Saint-Germain', 'Clermont-Ferrand', 'Immeuble de bureaux moderne', 1700000.00, '800m²', 'Photo_propriete17.jpg', 2, '2435'),
(21, 'P018', 'terrain', 'Terrasse du Jardin', 'Le Havre', 'Prairie de 20 hectares', 400000.00, '200000m²', 'Photo_propriete18.jpg', 3, '56786'),
(22, 'P019', 'appartement', '3 Rue des Capucines', 'Tours', 'Duplex en plein cœur de la ville', 680000.00, '110m²', 'Photo_propriete19.jpg', 4, '4788'),
(23, 'P020', 'résidentiel', '88 Rue Mouffetard', 'Orléans', 'Maison traditionnelle avec cheminée', 720000.00, '130m²', 'Photo_propriete20.jpg', 5, '8763');

-- --------------------------------------------------------

--
-- Structure de la table rendezvous
--

DROP TABLE IF EXISTS rendezvous;
CREATE TABLE IF NOT EXISTS rendezvous (
  id int NOT NULL AUTO_INCREMENT,
  client_id int NOT NULL,
  agent_id int NOT NULL,
  propriete_id varchar(50) NOT NULL,
  date_heure datetime NOT NULL,
  statut enum('confirmé','annulé') DEFAULT 'confirmé',
  PRIMARY KEY (id),
  KEY client_id (client_id),
  KEY agent_id (agent_id),
  KEY propriete_id (propriete_id)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table rendezvous
--

INSERT INTO rendezvous (id, client_id, agent_id, propriete_id, date_heure, statut) VALUES
(1, 1, 1, 'P001', '2024-06-01 14:00:00', 'annulé'),
(2, 1, 1, 'P001', '2024-05-17 16:14:00', 'annulé'),
(3, 1, 3, 'P001', '2024-05-16 23:36:00', 'confirmé'),
(4, 1, 3, 'P001', '2024-05-16 23:36:00', 'annulé'),
(5, 1, 3, 'P001', '2024-05-16 23:36:00', 'confirmé'),
(6, 1, 4, 'P001', '2024-05-19 23:41:00', 'confirmé'),
(7, 1, 1, 'P001', '2024-06-12 14:52:00', 'annulé'),
(8, 1, 2, 'P002', '2024-06-15 18:42:00', 'confirmé');

-- --------------------------------------------------------

--
-- Structure de la table utilisateurs
--

DROP TABLE IF EXISTS utilisateurs;
CREATE TABLE IF NOT EXISTS utilisateurs (
  id int NOT NULL AUTO_INCREMENT,
  nom varchar(50) NOT NULL,
  prenom varchar(50) NOT NULL,
  email varchar(100) NOT NULL,
  mot_de_passe varchar(255) NOT NULL,
  type_utilisateur enum('client','agent','administrateur') NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY email (email)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table utilisateurs
--

INSERT INTO utilisateurs (id, nom, prenom, email, mot_de_passe, type_utilisateur) VALUES
(1, 'Doe', 'John', 'john.doe@example.com', 'password', 'client'),
(5, 'Admin', 'User', 'admin@example.com', '1', 'administrateur'),
(6, 'berte', 'max', 'max@admin.com', '2', 'agent'),
(7, 'aubert', 'titi', 'tit@admin.com', '3', 'agent'),
(8, 'meyer', 'ronan', 'ronan@admin.com', '4', 'agent'),
(9, 'charvet', 'alex', 'alex@admin.com', '5', 'agent'),
(10, 'max', 'max', 'admin@example1.com', '$2y$10$ThGLSpWL.b6TV7Q1toMqo.jRHLxdS7UZPhNp8pMTiKxJEk58WdMGW', 'agent'),
(11, 'roro', 'roror', 'admin@example2.com', '$2y$10$s/HWqb5fKtse2LBflihEp.gHqRrwz8DzieNwrbc2sgmPUREfKJTxS', 'agent'),
(12, 'matthias', 'aubert', 'm@example.com', '$2y$10$iYnJNGmHiBVJ6XTHqggq3eRm4830qQq3fvaQ.gY6s.9lrcM3mWUyu', 'agent'),
(13, 'aubert', 'matthias', 'a@example.com', '$2y$10$1cKAhMvR4hwrWO.knOziJuaCid72V8JlN23qrCJpDL0HeeQmq717W', 'agent'),
(14, 'Segado', 'Jean-Pierre', 'jeanpierre.segado@omnesimmobilier.fr', '967520ae23e8ee14888bae72809031b98398ae4a636773e18fff917d77679334', 'agent'),
(15, 'Dubois', 'Hugo', 'hugo.dubois@omnesimmobilier.fr', '967520ae23e8ee14888bae72809031b98398ae4a636773e18fff917d77679334', 'agent');