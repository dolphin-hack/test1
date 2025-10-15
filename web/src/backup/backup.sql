/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.8.2-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: ecsite
-- ------------------------------------------------------
-- Server version	11.8.2-MariaDB-ubu2404

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
-- Table structure for table `bags`
--

DROP TABLE IF EXISTS `bags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `bags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bags`
--

LOCK TABLES `bags` WRITE;
/*!40000 ALTER TABLE `bags` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `bags` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `text` text DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `coupons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` text DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `used` int(11) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coupons`
--

LOCK TABLES `coupons` WRITE;
/*!40000 ALTER TABLE `coupons` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `coupons` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `friends`
--

DROP TABLE IF EXISTS `friends`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `friends` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_user_id` int(11) DEFAULT NULL,
  `to_user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `friends`
--

LOCK TABLES `friends` WRITE;
/*!40000 ALTER TABLE `friends` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `friends` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `limitUsers`
--

DROP TABLE IF EXISTS `limitUsers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `limitUsers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `users` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `limitUsers`
--

LOCK TABLES `limitUsers` WRITE;
/*!40000 ALTER TABLE `limitUsers` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `limitUsers` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `mails`
--

DROP TABLE IF EXISTS `mails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `mails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `to_user_id` int(11) DEFAULT NULL,
  `from_user_id` int(11) DEFAULT NULL,
  `title` text DEFAULT NULL,
  `message` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mails`
--

LOCK TABLES `mails` WRITE;
/*!40000 ALTER TABLE `mails` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `mails` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `points`
--

DROP TABLE IF EXISTS `points`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `points` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `points`
--

LOCK TABLES `points` WRITE;
/*!40000 ALTER TABLE `points` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `points` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `title` text DEFAULT NULL,
  `state` int(11) DEFAULT NULL,
  `mode` int(11) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `img` text DEFAULT NULL,
  `text` text DEFAULT NULL,
  `limitUser_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `products` VALUES
(1,1,'コーヒー豆',1,0,30000,'f4ae31d0432ffc02de34ca1bb8912772061d82b5','伝説のコーヒー豆　1kg',NULL),
(2,1,'コーヒーミル',1,0,10000,'10d89f8e1d277a353841640913256fe7d6150680','アンティークとしても使える！\r\nお値段以上の価値があります！\r\n\r\n',NULL),
(3,1,'懐中時計',1,0,1000000,'37a2a9d3df865045b27c96b59bafb611c1f8ecfa','海中で拾った時計です。\r\n',NULL);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `ratings`
--

DROP TABLE IF EXISTS `ratings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ratings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) DEFAULT NULL,
  `from_user_id` int(11) DEFAULT NULL,
  `to_user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ratings`
--

LOCK TABLES `ratings` WRITE;
/*!40000 ALTER TABLE `ratings` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `ratings` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `loginid` text DEFAULT NULL,
  `password` text DEFAULT NULL,
  `name` text DEFAULT NULL,
  `point` int(11) DEFAULT NULL,
  `cardno` text DEFAULT NULL,
  `priv` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `users` VALUES
(1,'master','gJa23x4XFIo4DPT1rTNbD','MASTER',100000,'1234-5678-9012',1),
(2,'quia17','9go36Cmz88u0cY8ztmXEO','Miss Lolita Howell',1000,'3562-7989-6605-7018',0),
(3,'id83','SZrI9OWb.b1YufdiWrEWcIG','Craig Lind',1000,'5623-8807-1096-7125',0),
(4,'odio39','MRamup.hYCeruDRjZ0uKpb.0/DZ6','Rubye Kuhn III',1000,'6634-0590-2305-1990',0),
(5,'dolore60','eTe89uQnIUNC1/LqeniataJbOdGAV2E4Mu','Ms. Mya Kutch',1000,'7271-8581-6112-8443',0),
(6,'placeat43','JINpsHDMcwuHySahh9Y9nfHQa5DTvj9R8w9s0OxpRO','Melba DuBuque',1000,'3273-4140-4519-5842',0),
(7,'tempore45','MQMULv.ZoqLXm/UwWrSknBD2IaqUbKO','Odell Ryan MD',1000,'9770-3881-0637-1786',0),
(8,'nostrum63','8ijeBeUhEQjLLvPOQm1ytZjQgjVWxkrWOstcm','Mandy Kilback',1000,'6328-8774-8489-0346',0),
(9,'debitis76','O0TGtOfp.ff.YlohnuiQXdLve0P/5ZTnBeNAiXg.','Robbie Rath',1000,'8228-9358-7529-9725',0),
(10,'voluptatibus77','GOfVe7ySkelyVEwhNQsw5fkhatFhd0cny','Mr. Presley Heathcote I',1000,'7668-1609-4581-6598',0),
(11,'illo85','IDDkvcSrGZqZhqW07VA4akFN5tj9Vs6','Cecelia Russel',1000,'0203-9233-3495-3037',0),
(12,'quisquam77','paucu9OaYwpArtF5UbQAJTtOhzpupCH8TO4x7W','Elyse Brakus II',1000,'0071-3871-7851-5188',0),
(13,'eum52','IKkYQyTkXG7LcrI.AKUFo4NRYaP6','Orland Smitham Sr.',1000,'7144-7969-8523-5474',0),
(14,'aut28','Lc4QWGVuY90sm5gLK','Hayley Grant IV',1000,'9756-0434-7832-8254',0),
(15,'aut6','KZ6zERQLlI6KYEZq','Norwood Graham Jr.',1000,'3776-1809-3555-1325',0),
(16,'commodi83','XgxAsDLaxMNAR84QZeT9F5RWXQFsKS','Jackson Durgan MD',1000,'5324-3464-7823-4345',0),
(17,'rerum57','viFtHnfkU0GNazLp1ZfUxpi','Annie Balistreri',1000,'3410-0375-8370-2809',0),
(18,'ex51','2TVBu/zugOloRN19ExMbQu0jS','Rudolph Denesik',1000,'2835-5086-0149-2284',0),
(19,'repellendus75','nU/hj8XU63SRT3rBidZPMnKXP1HanS','Prof. Marcelino Abbott',1000,'4497-1347-5480-8033',0),
(20,'porro45','hcvAc6Y.ddlayjwbXmuUEzD/GjuT.ce','Emily Ankunding',1000,'6885-8907-1457-2768',0),
(21,'corrupti52','4mg3.vxEW3.4ovNqQDiYWCQppM6A4TrnqJF.','Miss Electa Sauer I',1000,'9522-7150-5608-8039',0);
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

-- Dump completed on 2025-08-04  8:42:54
