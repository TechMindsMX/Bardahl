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
-- Table structure for table `gnsvz_template_styles`
--

DROP TABLE IF EXISTS `gnsvz_template_styles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gnsvz_template_styles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `template` varchar(50) NOT NULL DEFAULT '',
  `client_id` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `home` char(7) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `params` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_template` (`template`),
  KEY `idx_home` (`home`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gnsvz_template_styles`
--

LOCK TABLES `gnsvz_template_styles` WRITE;
/*!40000 ALTER TABLE `gnsvz_template_styles` DISABLE KEYS */;
INSERT INTO `gnsvz_template_styles` VALUES (4,'beez3',0,'0','Beez3 - Default','{\"wrapperSmall\":\"53\",\"wrapperLarge\":\"72\",\"logo\":\"images\\/joomla_black.gif\",\"sitetitle\":\"Joomla!\",\"sitedescription\":\"Open Source Content Management\",\"navposition\":\"left\",\"templatecolor\":\"personal\",\"html5\":\"0\"}'),(5,'hathor',1,'0','Hathor - Default','{\"showSiteName\":\"0\",\"colourChoice\":\"\",\"boldText\":\"0\"}'),(7,'protostar',0,'0','protostar - Default','{\"templateColor\":\"\",\"logoFile\":\"\",\"googleFont\":\"1\",\"googleFontName\":\"Open+Sans\",\"fluidContainer\":\"0\"}'),(8,'isis',1,'1','isis - Default','{\"templateColor\":\"\",\"logoFile\":\"\"}'),(9,'t3_bs3_blank',0,'1','t3_bs3_blank - Default','{\"t3_template\":\"1\",\"devmode\":\"1\",\"themermode\":\"1\",\"legacy_css\":\"1\",\"responsive\":\"1\",\"non_responsive_width\":\"970px\",\"build_rtl\":\"1\",\"t3-assets\":\"t3-assets\",\"t3-rmvlogo\":\"0\",\"minify\":\"0\",\"minify_js\":\"0\",\"minify_js_tool\":\"jsmin\",\"minify_exclude\":\"\",\"link_titles\":\"\",\"theme\":\"dark\",\"logotype\":\"text\",\"sitename\":\" \",\"slogan\":\"\",\"logoimage\":\"\",\"enable_logoimage_sm\":\"0\",\"logoimage_sm\":\"\",\"mainlayout\":\"default\",\"mm_type\":\"mainmenu\",\"navigation_trigger\":\"hover\",\"navigation_type\":\"megamenu\",\"navigation_animation\":\"slide\",\"navigation_animation_duration\":\"400\",\"mm_config\":\"{\\\"mainmenu\\\":{\\\"item-116\\\":{\\\"sub\\\":{\\\"class\\\":\\\"menu_productos\\\",\\\"rows\\\":[[{\\\"position\\\":119,\\\"class\\\":\\\"encuentralos\\\",\\\"width\\\":12},{\\\"position\\\":120,\\\"width\\\":12}]]},\\\"alignsub\\\":\\\"justify\\\"},\\\"item-158\\\":{\\\"sub\\\":{\\\"rows\\\":[[{\\\"item\\\":159,\\\"width\\\":12}]]}},\\\"item-175\\\":{\\\"sub\\\":{\\\"rows\\\":[[{\\\"item\\\":176,\\\"width\\\":12}]]}},\\\"item-178\\\":{\\\"sub\\\":{\\\"rows\\\":[[{\\\"item\\\":179,\\\"width\\\":12}]]}},\\\"item-167\\\":{\\\"sub\\\":{\\\"rows\\\":[[{\\\"item\\\":168,\\\"width\\\":12}]]}}}}\",\"navigation_collapse_enable\":\"1\",\"addon_offcanvas_enable\":\"0\",\"addon_offcanvas_effect\":\"off-canvas-effect-4\",\"snippet_open_head\":\"\",\"snippet_close_head\":\"\",\"snippet_open_body\":\"\",\"snippet_close_body\":\"\",\"snippet_debug\":\"0\",\"mm_config_needupdate\":\"\"}'),(10,'joomlart_template',0,'0','joomlart_template - Default','{}'),(11,'t3_blank',0,'0','t3_blank - Default','{\"t3_template\":\"1\",\"devmode\":\"0\",\"themermode\":\"1\",\"responsive\":\"1\",\"build_rtl\":\"0\",\"t3-assets\":\"t3-assets\",\"t3-rmvlogo\":\"1\",\"minify\":\"0\",\"minify_js\":\"0\",\"minify_js_tool\":\"jsmin\",\"minify_exclude\":\"\",\"link_titles\":\"\",\"theme\":\"\",\"logotype\":\"image\",\"sitename\":\"\",\"slogan\":\"\",\"logoimage\":\"\",\"enable_logoimage_sm\":\"0\",\"logoimage_sm\":\"\",\"mainlayout\":\"default-joomla-3.x\",\"navigation_trigger\":\"hover\",\"navigation_collapse_offcanvas\":\"1\",\"navigation_collapse_showsub\":\"1\",\"navigation_type\":\"joomla\",\"navigation_animation\":\"\",\"navigation_animation_duration\":\"400\",\"mm_type\":\"mainmenu\",\"mm_config\":\"\",\"snippet_open_head\":\"\",\"snippet_close_head\":\"\",\"snippet_open_body\":\"\",\"snippet_close_body\":\"\",\"snippet_debug\":\"0\"}'),(12,'ja_elastica',0,'0','ja_elastica - Default','[]');
/*!40000 ALTER TABLE `gnsvz_template_styles` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-10-03 17:10:07
