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
-- Table structure for table `gnsvz_session`
--

DROP TABLE IF EXISTS `gnsvz_session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gnsvz_session` (
  `session_id` varchar(200) NOT NULL DEFAULT '',
  `client_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `guest` tinyint(4) unsigned DEFAULT '1',
  `time` varchar(14) DEFAULT '',
  `data` mediumtext,
  `userid` int(11) DEFAULT '0',
  `username` varchar(150) DEFAULT '',
  PRIMARY KEY (`session_id`),
  KEY `userid` (`userid`),
  KEY `time` (`time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gnsvz_session`
--

LOCK TABLES `gnsvz_session` WRITE;
/*!40000 ALTER TABLE `gnsvz_session` DISABLE KEYS */;
INSERT INTO `gnsvz_session` VALUES ('gcaprkq3uc56p0gcbf3vr3ipe6',0,1,'1412374897','__default|a:7:{s:15:\"session.counter\";i:63;s:19:\"session.timer.start\";i:1412356241;s:18:\"session.timer.last\";i:1412374698;s:17:\"session.timer.now\";i:1412374897;s:22:\"session.client.browser\";s:109:\"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36\";s:8:\"registry\";O:24:\"Joomla\\Registry\\Registry\":1:{s:7:\"\\0\\0\\0data\";O:8:\"stdClass\":3:{s:13:\"current_theme\";s:4:\"dark\";s:17:\"current_direction\";s:3:\"ltr\";s:17:\"current_key_sufix\";s:9:\"_dark_ltr\";}}s:4:\"user\";O:5:\"JUser\":25:{s:9:\"\\0\\0\\0isRoot\";b:0;s:2:\"id\";i:0;s:4:\"name\";N;s:8:\"username\";N;s:5:\"email\";N;s:8:\"password\";N;s:14:\"password_clear\";s:0:\"\";s:5:\"block\";N;s:9:\"sendEmail\";i:0;s:12:\"registerDate\";N;s:13:\"lastvisitDate\";N;s:10:\"activation\";N;s:6:\"params\";N;s:6:\"groups\";a:1:{i:0;s:1:\"9\";}s:5:\"guest\";i:1;s:13:\"lastResetTime\";N;s:10:\"resetCount\";N;s:12:\"requireReset\";N;s:10:\"\\0\\0\\0_params\";O:24:\"Joomla\\Registry\\Registry\":1:{s:7:\"\\0\\0\\0data\";O:8:\"stdClass\":0:{}}s:14:\"\\0\\0\\0_authGroups\";a:2:{i:0;i:1;i:1;i:9;}s:14:\"\\0\\0\\0_authLevels\";a:3:{i:0;i:1;i:1;i:1;i:2;i:5;}s:15:\"\\0\\0\\0_authActions\";N;s:12:\"\\0\\0\\0_errorMsg\";N;s:10:\"\\0\\0\\0_errors\";a:0:{}s:3:\"aid\";i:0;}}',0,''),('luarclmooi3pg97nr284k1oha5',1,0,'1412374649','__default|a:9:{s:15:\"session.counter\";i:68;s:19:\"session.timer.start\";i:1412372357;s:18:\"session.timer.last\";i:1412374645;s:17:\"session.timer.now\";i:1412374646;s:22:\"session.client.browser\";s:109:\"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36\";s:8:\"registry\";O:24:\"Joomla\\Registry\\Registry\":1:{s:7:\"\\0\\0\\0data\";O:8:\"stdClass\":2:{s:11:\"application\";O:8:\"stdClass\":1:{s:4:\"lang\";s:5:\"en-GB\";}s:19:\"com_advancedmodules\";O:8:\"stdClass\":3:{s:7:\"modules\";O:8:\"stdClass\":4:{s:9:\"client_id\";s:1:\"0\";s:6:\"filter\";a:7:{s:6:\"search\";s:0:\"\";s:9:\"published\";s:0:\"\";s:8:\"position\";s:0:\"\";s:6:\"module\";s:0:\"\";s:6:\"menuid\";s:0:\"\";s:6:\"access\";s:0:\"\";s:8:\"language\";s:0:\"\";}s:4:\"list\";a:2:{s:12:\"fullordering\";s:12:\"position ASC\";s:5:\"limit\";s:2:\"50\";}s:10:\"limitstart\";i:0;}s:4:\"edit\";O:8:\"stdClass\":1:{s:6:\"module\";O:8:\"stdClass\":2:{s:2:\"id\";a:0:{}s:4:\"data\";N;}}s:3:\"add\";O:8:\"stdClass\":1:{s:6:\"module\";O:8:\"stdClass\":2:{s:12:\"extension_id\";N;s:6:\"params\";N;}}}}}s:4:\"user\";O:5:\"JUser\":27:{s:9:\"\\0\\0\\0isRoot\";b:1;s:2:\"id\";s:3:\"451\";s:4:\"name\";s:10:\"Super User\";s:8:\"username\";s:9:\"JuanFriks\";s:5:\"email\";s:7:\"a@a.com\";s:8:\"password\";s:60:\"$2y$10$u9LWkw7Br1xKsUZJsuXft.ALoN3UXvT8YOwapzNpO4Cjp.oQSSeZS\";s:14:\"password_clear\";s:0:\"\";s:5:\"block\";s:1:\"0\";s:9:\"sendEmail\";s:1:\"1\";s:12:\"registerDate\";s:19:\"2014-09-08 16:47:58\";s:13:\"lastvisitDate\";s:19:\"2014-10-03 21:08:10\";s:10:\"activation\";s:1:\"0\";s:6:\"params\";s:2:\"{}\";s:6:\"groups\";a:1:{i:8;s:1:\"8\";}s:5:\"guest\";i:0;s:13:\"lastResetTime\";s:19:\"0000-00-00 00:00:00\";s:10:\"resetCount\";s:1:\"0\";s:12:\"requireReset\";s:1:\"0\";s:10:\"\\0\\0\\0_params\";O:24:\"Joomla\\Registry\\Registry\":1:{s:7:\"\\0\\0\\0data\";O:8:\"stdClass\":0:{}}s:14:\"\\0\\0\\0_authGroups\";a:2:{i:0;i:1;i:1;i:8;}s:14:\"\\0\\0\\0_authLevels\";a:5:{i:0;i:1;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:6;}s:15:\"\\0\\0\\0_authActions\";N;s:12:\"\\0\\0\\0_errorMsg\";N;s:10:\"\\0\\0\\0_errors\";a:0:{}s:3:\"aid\";i:0;s:6:\"otpKey\";s:0:\"\";s:4:\"otep\";s:0:\"\";}s:13:\"session.token\";s:32:\"51576cc344a242f82490963322d7d439\";s:20:\"com_media.return_url\";s:101:\"index.php?option=com_media&view=images&tmpl=component&fieldid=&e_name=jform_content&asset=215&author=\";}',451,'JuanFriks');
/*!40000 ALTER TABLE `gnsvz_session` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-10-03 17:09:55
