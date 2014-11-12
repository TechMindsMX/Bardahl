CREATE DATABASE  IF NOT EXISTS `friks_bardahl_db` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `friks_bardahl_db`;
-- MySQL dump 10.13  Distrib 5.6.13, for Win32 (x86)
--
-- Host: localhost    Database: friks_bardahl_db
-- ------------------------------------------------------
-- Server version	5.1.69

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `#__votos_`
--

DROP TABLE IF EXISTS `#__votos_`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `#__votos_` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `calificacion` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `articulo` varchar(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `#__votos_`
--

LOCK TABLES `#__votos_` WRITE;
/*!40000 ALTER TABLE `#__votos_` DISABLE KEYS */;
INSERT INTO `#__votos_` VALUES (1,3,'1920-06-25','asdfjñ'),(2,5,'2014-09-30','articulox'),(3,5,'2014-09-30','articulox'),(4,5,'2014-09-30','articulox'),(5,0,'2014-09-30',''),(6,1,'2014-09-30','up'),(7,2,'2014-09-30','upsss'),(8,2,'2014-09-30','upsss'),(9,2,'2014-09-30','upsss'),(10,2,'2014-09-30','upsss'),(11,2,'2014-09-30','upsss'),(12,2,'2014-09-30','upsss'),(13,2,'2014-09-30','upsss'),(14,2,'2014-09-30','upsss'),(15,2,'2014-09-30','upsss'),(16,2,'2014-09-30','upsss'),(17,2,'2014-09-30','upsss'),(18,2,'2014-09-30','upsss');
/*!40000 ALTER TABLE `#__votos_` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-10-03 17:10:48
