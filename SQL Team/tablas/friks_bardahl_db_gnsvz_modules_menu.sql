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
-- Table structure for table `gnsvz_modules_menu`
--

DROP TABLE IF EXISTS `gnsvz_modules_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gnsvz_modules_menu` (
  `moduleid` int(11) NOT NULL DEFAULT '0',
  `menuid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`moduleid`,`menuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gnsvz_modules_menu`
--

LOCK TABLES `gnsvz_modules_menu` WRITE;
/*!40000 ALTER TABLE `gnsvz_modules_menu` DISABLE KEYS */;
INSERT INTO `gnsvz_modules_menu` VALUES (1,0),(2,0),(3,0),(4,0),(6,0),(7,0),(8,0),(9,0),(10,0),(12,0),(13,0),(14,0),(15,0),(16,0),(17,0),(79,0),(86,0),(87,0),(88,0),(89,0),(90,0),(91,0),(92,0),(94,0),(95,0),(96,0),(97,0),(98,0),(99,0),(100,0),(101,101),(102,0),(103,0),(106,0),(107,101),(108,0),(109,0),(110,0),(112,0),(114,0),(115,0),(116,0),(117,0),(118,0),(119,0),(120,0),(121,185),(122,116),(122,121),(122,123),(122,127),(122,128),(122,129),(122,130),(122,131),(122,132),(122,133),(122,134),(122,135),(122,136),(122,137),(122,138),(122,139),(122,140),(122,141),(122,142),(122,143),(122,144),(122,145),(122,146),(122,147),(122,148),(122,149),(122,150),(122,151),(122,152),(122,153),(122,154),(122,155),(122,156),(122,157),(122,158),(122,159),(122,160),(122,161),(122,162),(122,163),(122,164),(122,165),(122,166),(122,167),(122,168),(122,169),(122,170),(122,171),(122,172),(122,173),(122,174),(122,175),(122,176),(122,177),(122,178),(122,179),(122,180),(122,181),(122,182),(122,183),(122,184),(122,185),(122,186),(123,185),(124,177),(125,177),(126,0),(127,0),(128,0),(129,0),(130,0),(131,0),(132,0),(138,0),(139,0),(140,0),(141,0),(143,0),(144,0),(145,0),(146,0),(147,0),(148,0),(149,0),(150,0),(151,0);
/*!40000 ALTER TABLE `gnsvz_modules_menu` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-10-03 17:09:04
