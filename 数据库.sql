-- MySQL dump 10.13  Distrib 5.7.26, for Win64 (x86_64)
--
-- Host: localhost    Database: billing_app
-- ------------------------------------------------------
-- Server version	5.7.26

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
-- Table structure for table `daily_channels`
--

DROP TABLE IF EXISTS `daily_channels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `daily_channels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `daily_channels`
--

LOCK TABLES `daily_channels` WRITE;
/*!40000 ALTER TABLE `daily_channels` DISABLE KEYS */;
INSERT INTO `daily_channels` VALUES (1,'京东'),(2,'淘宝'),(3,'线下消费'),(4,'交通出行'),(5,'电话费');
/*!40000 ALTER TABLE `daily_channels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `daily_methods`
--

DROP TABLE IF EXISTS `daily_methods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `daily_methods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `daily_methods`
--

LOCK TABLES `daily_methods` WRITE;
/*!40000 ALTER TABLE `daily_methods` DISABLE KEYS */;
INSERT INTO `daily_methods` VALUES (1,'余额'),(2,'招商银行信用卡'),(3,'民生银行信用卡'),(4,'现金'),(5,'招商银行储蓄卡');
/*!40000 ALTER TABLE `daily_methods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `daily_platforms`
--

DROP TABLE IF EXISTS `daily_platforms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `daily_platforms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `daily_platforms`
--

LOCK TABLES `daily_platforms` WRITE;
/*!40000 ALTER TABLE `daily_platforms` DISABLE KEYS */;
INSERT INTO `daily_platforms` VALUES (1,'支付宝'),(2,'微信'),(3,'信用卡'),(4,'现金'),(5,'京东支付'),(6,'数字人民币');
/*!40000 ALTER TABLE `daily_platforms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `daily_records`
--

DROP TABLE IF EXISTS `daily_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `daily_records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `channel_id` int(11) NOT NULL,
  `platform_id` int(11) NOT NULL,
  `method_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `remark` text,
  `purchase_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `channel_id` (`channel_id`),
  KEY `platform_id` (`platform_id`),
  KEY `method_id` (`method_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `daily_records`
--

LOCK TABLES `daily_records` WRITE;
/*!40000 ALTER TABLE `daily_records` DISABLE KEYS */;
INSERT INTO `daily_records` VALUES (1,1,1,1,30.00,'购买电池','2025-06-01','2025-06-07 03:17:47'),(2,3,2,4,32.00,'晚餐','2025-06-02','2025-06-07 03:17:47'),(3,2,1,2,20.00,'淘宝买手机壳','2025-06-03','2025-06-07 03:17:47'),(5,2,6,1,50.00,'衣服','2025-05-28','2025-06-07 03:33:40'),(6,5,2,5,5.00,'上网','2025-06-07','2025-06-07 03:36:30'),(7,4,4,4,3.00,'','2025-02-15','2025-06-07 04:55:03'),(8,4,2,1,20.00,'','2024-12-07','2025-06-07 05:09:45'),(9,5,5,1,100.00,'100','2024-06-07','2025-06-07 05:12:25');
/*!40000 ALTER TABLE `daily_records` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `drink_brands`
--

DROP TABLE IF EXISTS `drink_brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `drink_brands` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `drink_brands`
--

LOCK TABLES `drink_brands` WRITE;
/*!40000 ALTER TABLE `drink_brands` DISABLE KEYS */;
INSERT INTO `drink_brands` VALUES (1,'喜茶'),(5,'库迪'),(11,'星巴克'),(12,'林里'),(7,'沪上阿姨'),(2,'瑞幸'),(8,'益禾堂'),(9,'茶百道'),(10,'蜜雪冰城'),(6,'阿水');
/*!40000 ALTER TABLE `drink_brands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `drink_records`
--

DROP TABLE IF EXISTS `drink_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `drink_records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `brand_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `purchase_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `brand_id` (`brand_id`),
  CONSTRAINT `drink_records_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `drink_brands` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `drink_records`
--

LOCK TABLES `drink_records` WRITE;
/*!40000 ALTER TABLE `drink_records` DISABLE KEYS */;
INSERT INTO `drink_records` VALUES (8,5,5.00,'2025-06-02','2025-06-07 01:23:07'),(10,1,5.00,'2025-05-09','2025-06-07 01:28:56'),(11,8,30.00,'2025-04-19','2025-06-07 01:29:08'),(12,1,19.00,'2025-05-16','2025-06-07 01:29:17'),(13,5,9.00,'2025-06-07','2025-06-07 01:34:25'),(14,9,50.00,'2025-04-17','2025-06-07 01:35:32'),(18,12,20.00,'2025-01-11','2025-06-07 04:27:16'),(19,9,50.00,'2025-02-20','2025-06-07 04:27:26'),(20,7,12.00,'2024-12-07','2025-06-07 04:35:51'),(21,11,5.00,'2025-06-07','2025-06-07 07:46:59');
/*!40000 ALTER TABLE `drink_records` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `takeout_channels`
--

DROP TABLE IF EXISTS `takeout_channels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `takeout_channels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `takeout_channels`
--

LOCK TABLES `takeout_channels` WRITE;
/*!40000 ALTER TABLE `takeout_channels` DISABLE KEYS */;
INSERT INTO `takeout_channels` VALUES (1,'美团外卖'),(2,'美团拼好饭'),(3,'饿了么外卖'),(4,'饿了么拼团'),(5,'京东外卖');
/*!40000 ALTER TABLE `takeout_channels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `takeout_records`
--

DROP TABLE IF EXISTS `takeout_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `takeout_records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shop_id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `purchase_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `shop_id` (`shop_id`),
  KEY `channel_id` (`channel_id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `takeout_records`
--

LOCK TABLES `takeout_records` WRITE;
/*!40000 ALTER TABLE `takeout_records` DISABLE KEYS */;
INSERT INTO `takeout_records` VALUES (24,9,3,15.00,'2025-06-01','2025-06-07 07:53:22'),(26,7,1,30.00,'2025-06-05','2025-06-07 07:54:13'),(25,10,5,20.00,'2025-05-22','2025-06-07 07:53:52');
/*!40000 ALTER TABLE `takeout_records` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `takeout_shops`
--

DROP TABLE IF EXISTS `takeout_shops`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `takeout_shops` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `takeout_shops`
--

LOCK TABLES `takeout_shops` WRITE;
/*!40000 ALTER TABLE `takeout_shops` DISABLE KEYS */;
INSERT INTO `takeout_shops` VALUES (9,'塔斯汀'),(8,'华莱士'),(6,'KFC'),(7,'麦当劳'),(10,'张亮麻辣烫'),(11,'杨国福麻辣烫');
/*!40000 ALTER TABLE `takeout_shops` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `remember_token` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','$2y$10$d47nJGEbDYX.1Vnc30kXqOoVo.jhNFIuzQe13cfsaQu/E9QBoihDW','2025-06-07 05:37:04','64455e0cf34cdf55ce8f2ea5456764cb111a22f371b0161d5f315b025c707cd5');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-07 16:01:33
