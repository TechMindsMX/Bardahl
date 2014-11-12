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
-- Table structure for table `gnsvz_update_sites`
--

DROP TABLE IF EXISTS `gnsvz_update_sites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gnsvz_update_sites` (
  `update_site_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT '',
  `type` varchar(20) DEFAULT '',
  `location` text NOT NULL,
  `enabled` int(11) DEFAULT '0',
  `last_check_timestamp` bigint(20) DEFAULT '0',
  `extra_query` varchar(1000) DEFAULT '',
  PRIMARY KEY (`update_site_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COMMENT='Update Sites';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gnsvz_update_sites`
--

LOCK TABLES `gnsvz_update_sites` WRITE;
/*!40000 ALTER TABLE `gnsvz_update_sites` DISABLE KEYS */;
INSERT INTO `gnsvz_update_sites` VALUES (1,'Joomla! Core','collection','http://update.joomla.org/core/list.xml',1,1412359382,''),(2,'Joomla! Extension Directory','collection','http://update.joomla.org/jed/list.xml',1,1412359382,''),(3,'Accredited Joomla! Translations','collection','http://update.joomla.org/language/translationlist_3.xml',1,1412359382,''),(4,'WebInstaller Update Site','extension','http://appscdn.joomla.org/webapps/jedapps/webinstaller.xml',1,1412359382,''),(5,'ext_joomsef4_banners','sef_update','http://www.artio.net/joomla-updates/list/ext_joomsef4_banners.xml',1,1412359382,''),(6,'ext_joomsef4_contact','sef_update','http://www.artio.net/joomla-updates/list/ext_joomsef4_contact.xml',1,1412359382,''),(7,'ext_joomsef4_content','sef_update','http://www.artio.net/joomla-updates/list/ext_joomsef4_content.xml',1,1412359382,''),(8,'ext_joomsef4_mailto','sef_update','http://www.artio.net/joomla-updates/list/ext_joomsef4_mailto.xml',1,1412359382,''),(9,'ext_joomsef4_newfeeds','sef_update','http://www.artio.net/joomla-updates/list/ext_joomsef4_newsfeeds.xml',1,1412359382,''),(10,'ext_joomsef4_search','sef_update','http://www.artio.net/joomla-updates/list/ext_joomsef4_search.xml',1,1412359382,''),(11,'ext_joomsef4_tags','sef_update','http://www.artio.net/joomla-updates/list/ext_joomsef4_tags.xml',1,1412359382,''),(12,'ext_joomsef4_users','sef_update','http://www.artio.net/joomla-updates/list/ext_joomsef4_users.xml',1,1412359382,''),(13,'ext_joomsef4_weblinks','sef_update','http://www.artio.net/joomla-updates/list/ext_joomsef4_weblinks.xml',1,1412359382,''),(14,'ext_joomsef4_wrapper','sef_update','http://www.artio.net/joomla-updates/list/ext_joomsef4_wrapper.xml',1,1412359382,''),(15,'com_joomsef','sef_update','http://www.artio.net/joomla-updates/list/com_joomsef4.xml',1,1412359382,''),(16,'FieldsAttach Update Site','extension','http://www.percha.com/update/fieldsattach-update_j3.xml',1,1412359382,''),(17,'Maximenu CK Update','extension','http://update.joomlack.fr/mod_maximenuck_update.xml',1,1412359382,''),(18,'','collection','http://update.joomlart.com/service/tracking/list.xml',1,1412359382,''),(19,'NoNumber Advanced Module Manager','extension','http://download.nonumber.nl/updates.php?e=advancedmodules&type=.zip',1,1412359380,''),(20,'NoNumber Sliders','extension','http://download.nonumber.nl/updates.php?e=sliders&type=.zip',1,1412359380,''),(21,'AllVideos','extension','http://www.joomlaworks.net/updates/jw_allvideos.xml',1,1412359380,''),(22,'NoNumber Modules Anywhere','extension','http://download.nonumber.nl/updates.php?e=modulesanywhere&type=.zip',1,1412359380,''),(23,'ContactUs Form Update','extension','http://sito.emmealfa.it/index.php?option=com_ars&view=update&task=stream&format=xml&id=2',0,0,''),(24,'Plugin include component Update Site','extension','http://www.burgersoftware.es/includecomponent/update.xml',1,1412359382,''),(25,'Visforms','extension','http://vi-solutions.de/updates/visforms/extension.xml',1,1412359380,'');
/*!40000 ALTER TABLE `gnsvz_update_sites` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-10-03 17:09:26
