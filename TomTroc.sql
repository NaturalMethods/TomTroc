/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-12.1.2-MariaDB, for osx10.19 (x86_64)
--
-- Host: 127.0.0.1    Database: TomTroc
-- ------------------------------------------------------
-- Server version	12.1.2-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `books`
--

DROP TABLE IF EXISTS `books`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `books` (
  `idBook` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `author` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `disponibility` tinyint(1) NOT NULL DEFAULT 1,
  `idOwner` int(11) NOT NULL,
  `bookImg` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idBook`),
  UNIQUE KEY `title` (`title`),
  KEY `fkBooksOwner` (`idOwner`),
  CONSTRAINT `fkBooksOwner` FOREIGN KEY (`idOwner`) REFERENCES `users` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `books`
--

LOCK TABLES `books` WRITE;
/*!40000 ALTER TABLE `books` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `books` VALUES
(1,'A Book Full Of Hope','Rupi Kaur',NULL,1,10,'a_book_full_of_hope.jpg'),
(2,'Company Of One','Paul Jarvis',NULL,1,13,'company_of_one.jpg'),
(3,'Delight!','Justin Rossow',NULL,1,4,'delight.jpg'),
(4,'Milwaukee Mission','Elder Cooper Low',NULL,1,5,'elder_cooper_low.jpg'),
(5,'Esther','Alabaster','Curious and exciting, the brilliance of Esther is in its mysterious, unique intermingling of chance and divine providence. \r\n\r\nWhile its plot appears random, and chance-filled at first, it is an invitation to see it as a fateful encounter with God. \r\nIt is an encouragement to all of us, to watch and to listen—with curiosity and attentiveness—for the implicit workings of God in serendipitous moments. \r\n\r\nIt may not be explicit or how we would expect, but certainly God is there.',1,1,'esther.jpg'),
(6,'Innovation','Matt Ridley',NULL,1,7,'how_innovation_works.jpg'),
(7,'Hygge','Meik Wiking',NULL,1,3,'hygge.jpg'),
(8,'Milk & Honey','Rupi Kaur',NULL,1,3,'milk_and_honey.jpg'),
(9,'Minimalist Graphics','Julia Schonlau',NULL,1,6,'minimalist.jpg'),
(10,'Psalms','Alabaster',NULL,1,8,'psalms.jpg'),
(11,'Narnia','C.S Lewis',NULL,1,12,'the_chronicles_of_narnia.jpg'),
(12,'The Kinkfolk Table','Nathan Williams','J\'ai récemment plongé dans les pages de \'The Kinfolk Table\' et j\'ai été enchanté par cette œuvre captivante. Ce livre va bien au-delà d\'une simple collection de recettes ; il célèbre l\'art de partager des moments authentiques autour de la table.\n\nLes photographies magnifiques et le ton chaleureux captivent dès le départ, transportant le lecteur dans un voyage à travers des recettes et des histoires qui mettent en avant la beauté de la simplicité et de la convivialité.\n\nChaque page est une invitation à ralentir, à savourer et à créer des souvenirs durables avec les êtres chers.\n\n\'The Kinfolk Table\' incarne parfaitement l\'esprit de la cuisine et de la camaraderie, et il est certain que ce livre trouvera une place spéciale dans le cœur de tout amoureux de la cuisine et des rencontres inspirantes.',1,2,'the_kinkfolk_table.jpg'),
(13,'The Subtle Art Of Not Giving A Fuck','Mark Manson',NULL,1,11,'the_subtle_art_of_not_giving_a_fuck.jpg'),
(14,'The Two Towers','J.R.R Tolkien',NULL,1,14,'the_two_towers.jpg'),
(15,'Thinking, Fast & Slow','Daniel Kahneman',NULL,1,9,'thinking_fast_and_slow.jpg'),
(16,'Wabi Sabi','Beth Kempton',NULL,1,2,'wabi_sabi.jpg');
/*!40000 ALTER TABLE `books` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `messages` (
  `idMessage` int(11) NOT NULL AUTO_INCREMENT,
  `sentAt` datetime NOT NULL DEFAULT current_timestamp(),
  `message` text NOT NULL,
  `idSender` int(11) NOT NULL,
  `idReceiver` int(11) NOT NULL,
  `unread` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`idMessage`),
  KEY `fkMessagesSender` (`idSender`),
  KEY `fkMessagesReceiver` (`idReceiver`),
  CONSTRAINT `fkMessagesReceiver` FOREIGN KEY (`idReceiver`) REFERENCES `users` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fkMessagesSender` FOREIGN KEY (`idSender`) REFERENCES `users` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `messages` VALUES
(1,'2026-02-05 11:50:52','Salut je suis un test',2,1,0),
(2,'2026-02-05 12:10:21','Test de message',3,1,0),
(3,'2026-02-14 15:13:58','test',1,2,0),
(7,'2026-02-20 15:08:31','dd',1,2,0),
(8,'2026-02-20 16:00:52','test',1,2,0),
(9,'2026-02-25 19:42:58','Envoyer un nouveau message',2,33,0),
(10,'2026-02-25 19:43:09','Salut toi !',33,2,0),
(11,'2026-02-25 19:43:34','Salut',2,33,0),
(12,'2026-02-25 19:59:19','ça va ?',2,33,0),
(13,'2026-02-25 20:40:46','Envoyer un nouveau message',9,1,0);
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `idUser` int(11) NOT NULL AUTO_INCREMENT,
  `password` varchar(255) NOT NULL,
  `username` varchar(150) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  `userPic` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idUser`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `mail` (`mail`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `users` VALUES
(1,'$2y$12$QHv0sEZXdo.BtZ6Kub303ulyWKEEU84cBv42EVXJvltOiE5YDTN6q','CamilleClubLit','CamilleClubLit@gmail.com','user','2026-01-24 22:00:39','CamilleClubLit.jpg'),
(2,'$2y$12$XuNThBdog4MQL4l.2nTIGO735HxhvBccduz73noY9UJGRQGcvAKDS','Alexlecture','Alexlecture@gmail.com','user','2026-01-24 22:00:39',NULL),
(3,'$2y$12$XuNThBdog4MQL4l.2nTIGO735HxhvBccduz73noY9UJGRQGcvAKDS','Hugo1990_12','Hugo1990_12@gmail.com','user','2026-01-24 22:00:39',NULL),
(4,'$2y$12$XuNThBdog4MQL4l.2nTIGO735HxhvBccduz73noY9UJGRQGcvAKDS','Juju1432','Juju1432@gmail.com','user','2026-01-24 22:00:39',NULL),
(5,'$2y$12$XuNThBdog4MQL4l.2nTIGO735HxhvBccduz73noY9UJGRQGcvAKDS','Christiane75014','Christiane75014@gmail.com','user','2026-01-24 22:00:39',NULL),
(6,'$2y$12$XuNThBdog4MQL4l.2nTIGO735HxhvBccduz73noY9UJGRQGcvAKDS','Hamzalecture','Hamzalecture@gmail.com','user','2026-01-24 22:00:39',NULL),
(7,'$2y$12$XuNThBdog4MQL4l.2nTIGO735HxhvBccduz73noY9UJGRQGcvAKDS','Lou&Ben50','Lou&Ben50@gmail.com','user','2026-01-24 22:00:39',NULL),
(8,'$2y$12$XuNThBdog4MQL4l.2nTIGO735HxhvBccduz73noY9UJGRQGcvAKDS','Lolobzh','Lolobzh@gmail.com','user','2026-01-24 22:00:39',NULL),
(9,'$2y$12$XuNThBdog4MQL4l.2nTIGO735HxhvBccduz73noY9UJGRQGcvAKDS','Sas634','Sas634@gmail.com','user','2026-01-24 22:00:39',NULL),
(10,'$2y$12$XuNThBdog4MQL4l.2nTIGO735HxhvBccduz73noY9UJGRQGcvAKDS','ML95','ML95@gmail.com','user','2026-01-24 22:00:39',NULL),
(11,'$2y$12$XuNThBdog4MQL4l.2nTIGO735HxhvBccduz73noY9UJGRQGcvAKDS','Verogo33','Verogo33@gmail.com','user','2026-01-24 22:00:39',NULL),
(12,'$2y$12$XuNThBdog4MQL4l.2nTIGO735HxhvBccduz73noY9UJGRQGcvAKDS','AnnikaBrahms','AnnikaBrahms@gmail.com','user','2026-01-24 22:00:39',NULL),
(13,'$2y$12$XuNThBdog4MQL4l.2nTIGO735HxhvBccduz73noY9UJGRQGcvAKDS','Victoirefabr912','Victoirefabr912@gmail.com','user','2026-01-24 22:00:39',NULL),
(14,'$2y$12$XuNThBdog4MQL4l.2nTIGO735HxhvBccduz73noY9UJGRQGcvAKDS','Lotrfanclub67','Lotrfanclub67@gmail.com','user','2026-01-24 22:00:39',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
commit;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-02-25 20:48:01
