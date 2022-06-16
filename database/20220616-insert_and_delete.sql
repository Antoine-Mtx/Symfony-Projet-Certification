-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           5.7.33 - MySQL Community Server (GPL)
-- SE du serveur:                Win64
-- HeidiSQL Version:             11.2.0.6213
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Listage des données de la table illustre.commentaire : ~0 rows (environ)
DELETE FROM `commentaire`;
/*!40000 ALTER TABLE `commentaire` DISABLE KEYS */;
/*!40000 ALTER TABLE `commentaire` ENABLE KEYS */;

-- Listage des données de la table illustre.competence : ~2 rows (environ)
DELETE FROM `competence`;
/*!40000 ALTER TABLE `competence` DISABLE KEYS */;
INSERT INTO `competence` (`id`, `concepteur_id`, `domaine_id`, `intitule`, `description`, `date_creation`, `image`, `icone`, `synopsis`) VALUES
	(1, 1, 1, 'Déterminer la raison d\'une suite arithmétique', 'Méthode permettant de déterminer la raison d\'une suite arithmétique', '2022-06-06', '1.png', NULL, 'Méthode permettant de déterminer la raison d\'une suite arithmétique'),
	(2, 1, 1, 'Déterminer la raison d\'une suite géométrique', 'Méthode permettant de déterminer la raison d\'une suite géométrique', '2022-06-12', NULL, NULL, 'Méthode permettant de déterminer la raison d\'une suite géométrique');
/*!40000 ALTER TABLE `competence` ENABLE KEYS */;

-- Listage des données de la table illustre.composant : ~0 rows (environ)
DELETE FROM `composant`;
/*!40000 ALTER TABLE `composant` DISABLE KEYS */;
/*!40000 ALTER TABLE `composant` ENABLE KEYS */;

-- Listage des données de la table illustre.doctrine_migration_versions : ~1 rows (environ)
DELETE FROM `doctrine_migration_versions`;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
	('DoctrineMigrations\\Version20220520222005', '2022-05-23 14:18:29', 1555);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;

-- Listage des données de la table illustre.domaine : ~4 rows (environ)
DELETE FROM `domaine`;
/*!40000 ALTER TABLE `domaine` DISABLE KEYS */;
INSERT INTO `domaine` (`id`, `intitule`, `description`, `image_filename`, `icone_filename`) VALUES
	(1, 'Arithmétique', 'Arithmétique, du grec « arithmos » qui signifie nombre. Branche des mathématiques ayant pour objet l\'étude des nombres entiers et de leurs propriétés.', NULL, NULL),
	(2, 'Algèbre', 'Algèbre, de l’arabe « al-djabr » qui signifie réparation. Branche des mathématiques ayant pour objet l\'étude des structures abstraites, notamment les propriétés des nombres et des opérations. Cela regroupe le calcul littéral, l’étude des relations entre une ou des inconnues et des valeurs connues, plus généralement la résolution des équations.', NULL, NULL),
	(3, 'Géométrie', 'Géométrie, du grec « mesure de la terre ». Branche des mathématiques ayant pour objet l’étude des objets dans l’espace.', NULL, NULL),
	(4, 'Probabilités', 'Probabilités et par extension les statistiques : branche des mathématiques qui estime les chances qu’un événement se réalise.', NULL, NULL);
/*!40000 ALTER TABLE `domaine` ENABLE KEYS */;

-- Listage des données de la table illustre.message : ~0 rows (environ)
DELETE FROM `message`;
/*!40000 ALTER TABLE `message` DISABLE KEYS */;
/*!40000 ALTER TABLE `message` ENABLE KEYS */;

-- Listage des données de la table illustre.type : ~9 rows (environ)
DELETE FROM `type`;
/*!40000 ALTER TABLE `type` DISABLE KEYS */;
INSERT INTO `type` (`id`, `intitule`, `description`, `couleur`, `affichage`) VALUES
	(1, 'Définition', 'Introduction d\'une notion, d\'une chose. Énonciation des attributs qui distinguent une chose, qui lui appartiennent à l\'exclusion de toute autre.', '#08146d', 'carte'),
	(2, 'Démonstration', 'En mathématiques et en logique, une démonstration est un ensemble structuré d\'étapes correctes de raisonnement.\r\n\r\nDans une démonstration, chaque étape est soit un axiome (un fait acquis), soit l\'application d\'une règle qui permet d\'affirmer qu\'une proposition, la conclusion, est une conséquence logique d\'une ou plusieurs autres propositions, les prémisses de la règle. Les prémisses sont soit des axiomes, soit des propositions déjà obtenues comme conclusions de l\'application d\'autres règles. Une proposition qui est la conclusion de l\'étape ultime d\'une démonstration est un théorème.\r\n\r\nLe terme « preuve » est parfois employé comme un synonyme de « démonstration »', '#54a108', 'carte'),
	(3, 'Théorème', 'Proposition, dîte conclusion, qui est démontrée à partir d\'axiomes ou d\'autre théorèmes déjà démontrés. Une conséquence déduite d\'un théorème est un corollaire. Du latin theorema théorème, proposition; du grec theorein contempler, examiner.', '#54a108', 'carte'),
	(4, 'Proposition', 'En général, synonyme de théorème. Relation entre plusieurs termes. En logique synonyme d\'énoncé, d\'assertion, c\'est une affirmation connue par tous pour être soit vraie soit fausse.', NULL, 'carte'),
	(5, 'Propriété', 'Qualité particulière à quelque chose; ex: dans un triangle la longueur d\'un côté est toujours inférieure à la somme des longueurs des deux autres côtés.', NULL, 'carte'),
	(6, 'Lemme', 'Sorte d\'étape préliminaire à une démonstration. Proposition déduite d\'un ou de plusieurs postulats dont la démonstration prépare celle d\'un théorème. Un lemme est un petit théorème de nature technique. En pratique, c\'est une sorte d\'aparté qui allège la démonstration pricipale.', NULL, 'carte'),
	(7, 'Axiome', 'Vérité évidente; proposition élémentaire admise comme vérité; énoncé d\'une propriété vraie a priori; qu\'il faut admettre comme point de départ d\'une théorie; synonyme de postulat; ex: deux quantités égales à une troisième sont égales entre elles; le tout est plus grand ou égal à l\'une de ses parties …; mot qui vient du grec axioma, j\'estime, je tiens pour vrai.', NULL, 'carte'),
	(8, 'Postulat', 'Affirmation tenue pour vraie, avec intention de le démonter ultérieurement. Entre hypothèse – affirmation temporaire – et axiome – affirmation non démontrée. Vient du latin postulare demander, idée de quelque chose que l\'on demande d\'accepter.', NULL, 'carte'),
	(9, 'Représentation graphique', 'Représentation graphique d\'un objet mathématique', NULL, 'graphique');
/*!40000 ALTER TABLE `type` ENABLE KEYS */;

-- Listage des données de la table illustre.user : ~4 rows (environ)
DELETE FROM `user`;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`, `pseudo`, `roles`, `password`, `email`, `date_creation`, `is_verified`, `avatar`) VALUES
	(1, 'Antoine67', '[]', '$2y$13$OWGBYdMggBiUIYfMFTRRBODyAF0dt6EWuHHS346MZZIjes/7LNoUO', 'antoine.mortureux@gmail.com', '2022-05-30', 0, '01.jpg'),
	(3, 'Marvin', '[]', '$2y$13$DPZxtxiw0K9W02u42J.zOenNQK/Raut11msXMBv.eFGAbXFL/sA3S', 'brassler.marvin@gmail.com', '2022-05-30', 0, NULL),
	(4, 'Martin', '[]', '$2y$13$H4Uk4K30/Eeb0kwBCndAQeWW4c66ZeUV9BPKCixW/Tzi20tJ0DNz.', 'martin67610@hotmail.fr', '2022-05-30', 0, NULL),
	(5, 'AntoineOutlook', '[]', '$2y$13$Ji4g6vZSt9cBKm5d6lSpzu4tHm5yZwbulpy1x0JrGauxhBytMDNMi', 'antoine.mortureux@outlook.fr', '2022-05-30', 0, NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
