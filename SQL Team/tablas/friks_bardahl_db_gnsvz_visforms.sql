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
-- Table structure for table `gnsvz_visforms`
--

DROP TABLE IF EXISTS `gnsvz_visforms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gnsvz_visforms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_id` int(10) unsigned NOT NULL DEFAULT '0',
  `name` text,
  `title` text,
  `checked_out` int(10) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `description` longtext,
  `emailfrom` text,
  `emailfromname` text,
  `emailto` text,
  `emailcc` text,
  `emailbcc` text,
  `subject` text,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `hits` int(11) NOT NULL DEFAULT '0',
  `published` tinyint(4) DEFAULT NULL,
  `saveresult` tinyint(4) DEFAULT NULL,
  `emailresult` tinyint(4) DEFAULT NULL,
  `textresult` longtext,
  `redirecturl` text,
  `spambotcheck` tinyint(1) NOT NULL DEFAULT '0',
  `captcha` tinyint(4) DEFAULT NULL,
  `captchacustominfo` text,
  `captchacustomerror` text,
  `uploadpath` text,
  `maxfilesize` int(11) DEFAULT NULL,
  `allowedextensions` text,
  `poweredby` tinyint(4) DEFAULT NULL,
  `emailreceipt` tinyint(4) DEFAULT NULL,
  `emailreceipttext` longtext,
  `emailreceiptsubject` text,
  `emailreceiptsettings` text,
  `emailresultincfile` tinyint(4) DEFAULT NULL,
  `formCSSclass` text,
  `fronttitle` text,
  `frontdescription` longtext,
  `frontendsettings` text,
  `access` int(11) NOT NULL DEFAULT '0',
  `language` char(7) NOT NULL,
  `required` varchar(10) NOT NULL DEFAULT 'top',
  `exportsettings` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gnsvz_visforms`
--

LOCK TABLES `gnsvz_visforms` WRITE;
/*!40000 ALTER TABLE `gnsvz_visforms` DISABLE KEYS */;
INSERT INTO `gnsvz_visforms` VALUES (1,198,'formulario-contacto','formulario de contacto',0,'0000-00-00 00:00:00','','aguilar_2001@hotmail.com','aguilar_2001@hotmail.com','aguilar_2001@hotmail.com','','','Contacto Bardahl','2014-09-30 04:58:02',451,0,1,0,1,'','',0,0,'','','tmp',0,'bmp,csv,doc,gif,ico,jpg,jpeg,odg,odp,ods,odt,pdf,png,ppt,swf,txt,xcf,xls,BMP,CSV,DOC,GIF,ICO,JPG,JPEG,ODG,ODP,ODS,ODT,PDF,PNG,PPT,SWF,TXT,XCF,XLS',1,0,'','','{\"emailreceiptincfield\":\"0\",\"emailrecipientincfilepath\":\"0\",\"emailreceiptincfile\":\"0\",\"emailreceiptinccreated\":\"1\",\"emailreceiptincformtitle\":\"1\"}',0,'','','','{\"displayip\":\"0\",\"displayid\":\"0\",\"displaydetail\":\"0\",\"autopublish\":\"1\"}',1,'*','top','{\"exppublishfieldsonly\":\"1\",\"exppublisheddataonly\":\"1\",\"expfieldpublished\":\"0\",\"expfieldip\":\"0\",\"expfieldid\":\"0\",\"usewindowscharset\":\"1\"}');
/*!40000 ALTER TABLE `gnsvz_visforms` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-10-03 17:10:26
