-- MySQL dump 10.13  Distrib 8.0.36, for Win64 (x86_64)
--
-- Host: localhost    Database: University_Clinic_System
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `username` varchar(120) NOT NULL,
  `role` enum('Doctor','Nurse') NOT NULL,
  `action_type` varchar(100) NOT NULL,
  `action_description` text DEFAULT NULL,
  `record_id` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'SUCCESS',
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_activity_user` (`user_id`),
  CONSTRAINT `fk_activity_user` FOREIGN KEY (`user_id`) REFERENCES `admin` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=374 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
INSERT INTO `activity_logs` VALUES (1,5,'admin123@gmail.com','Doctor','Login','Admin logged in',NULL,'SUCCESS','2025-12-01 18:20:00'),(2,5,'admin123@gmail.com','Doctor','Login','Admin logged in',NULL,'SUCCESS','2025-12-01 18:28:56'),(3,5,'admin123@gmail.com','Doctor','Add Patient','Added patient: ID 13252, Name Angelo Cano',NULL,'SUCCESS','2025-12-01 18:45:37'),(4,5,'admin123@gmail.com','Doctor','Add Patient','Added patient: ID 13253, Name Carl Larga',NULL,'SUCCESS','2025-12-01 18:54:47'),(5,5,'admin123@gmail.com','Doctor','Add Patient','Added patient: ID 13255, Name Lheila Tandang',NULL,'SUCCESS','2025-12-01 19:06:26'),(6,5,'admin123@gmail.com','Doctor','Login','Admin logged in',NULL,'SUCCESS','2025-12-01 19:07:30'),(7,5,'admin123@gmail.com','Doctor','Add Patient','Added patient: ID 13256, Name Jeric Manibog',NULL,'SUCCESS','2025-12-01 20:10:21'),(8,5,'admin123@gmail.com','Doctor','Add Patient','Added patient: ID 13257, Name Lawrence Salvador',NULL,'SUCCESS','2025-12-01 20:12:42'),(9,5,'admin123@gmail.com','Doctor','Add Patient','Added patient: ID 13258, Name Mirah Lim',NULL,'SUCCESS','2025-12-01 20:21:02'),(10,5,'admin123@gmail.com','Doctor','Login','Nurse logged in',NULL,'SUCCESS','2025-12-01 20:22:03'),(11,5,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-01 20:23:18'),(12,5,'admin123@gmail.com','Doctor','Add Patient','Added patient: ID 13259, Name Jin Casili',NULL,'SUCCESS','2025-12-01 20:23:59'),(13,7,'jmmc@gmail.com','Nurse','Login','Nurse logged in',NULL,'SUCCESS','2025-12-01 21:07:16'),(14,7,'jmmc@gmail.com','Nurse','Login','Nurse logged in',NULL,'SUCCESS','2025-12-01 21:09:59'),(15,5,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-01 21:13:58'),(16,5,'admin123@gmail.com','Doctor','Logged out','Logged out',NULL,'SUCCESS','2025-12-01 21:17:14'),(17,7,'jmmc@gmail.com','Nurse','Login','Nurse logged in',NULL,'SUCCESS','2025-12-01 21:17:25'),(18,7,'jmmc@gmail.com','Nurse','Logout','Admin logged out',NULL,'SUCCESS','2025-12-01 21:20:11'),(19,7,'jmmc@gmail.com','Nurse','Login','Nurse logged in',NULL,'SUCCESS','2025-12-01 21:20:30'),(20,7,'jmmc@gmail.com','Nurse','Delete Client','Deleted client: ID: 13259, (Email: Jin2@gmail.com), ',NULL,'SUCCESS','2025-12-01 22:28:04'),(21,7,'jmmc@gmail.com','Nurse','Add Consultation Record','Added consultation record for Client Email: RoseAnn@gmail.com, ClientID: 13171',NULL,'SUCCESS','2025-12-01 22:35:40'),(22,7,'jmmc@gmail.com','Nurse','Create Prescription','Created prescription for Client Email: RoseAnn@gmail.com, ClientID: 13171',NULL,'SUCCESS','2025-12-01 22:41:56'),(23,7,'jmmc@gmail.com','Nurse','Create Diagnostic Record','Create Diagnostic Record for Client Email: juandelacruz2@gmail.com, ClientID: 13245',NULL,'SUCCESS','2025-12-01 22:44:17'),(24,7,'jmmc@gmail.com','Nurse','New Personnel Form Submission','Submitted new personnel form for Client Email: Chester123@gmail.com, ClientID: 13192',NULL,'SUCCESS','2025-12-01 22:47:21'),(25,7,'jmmc@gmail.com','Nurse','Logout','Admin logged out',NULL,'SUCCESS','2025-12-01 22:47:49'),(26,5,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-01 22:47:54'),(27,5,'admin123@gmail.com','Doctor','Update Physical Examination','Updated physical examination for Client Email: michael18jmmc@gmail.com, ClientID: 13228',NULL,'SUCCESS','2025-12-01 22:49:24'),(28,5,'admin123@gmail.com','Doctor','Update Physical Examination','Updated physical examination for Client Email: juandelacruz2@gmail.com, ClientID: 13245',NULL,'SUCCESS','2025-12-01 22:50:20'),(29,5,'admin123@gmail.com','Doctor','New Physical Examination','Inserted new physical examination for Client Email: Susana@gmail.com, ClientID: 13244',NULL,'SUCCESS','2025-12-01 22:50:51'),(30,5,'admin123@gmail.com','Doctor','Logout','Admin logged out',NULL,'SUCCESS','2025-12-01 23:02:50'),(31,5,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-01 23:03:29'),(32,5,'admin123@gmail.com','Doctor','Logout','Admin logged out',NULL,'SUCCESS','2025-12-01 23:10:47'),(33,7,'jmmc@gmail.com','Nurse','Login','Nurse logged in',NULL,'SUCCESS','2025-12-01 23:11:45'),(34,7,'jmmc@gmail.com','Nurse','Logout','Admin logged out',NULL,'SUCCESS','2025-12-01 23:41:16'),(35,5,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-02 07:10:02'),(36,5,'admin123@gmail.com','Doctor','Logout','Admin logged out',NULL,'SUCCESS','2025-12-02 09:50:40'),(37,7,'jmmc@gmail.com','Nurse','Login','Nurse logged in',NULL,'SUCCESS','2025-12-02 09:50:52'),(38,7,'jmmc@gmail.com','Nurse','Logout','Admin logged out',NULL,'SUCCESS','2025-12-02 10:00:14'),(39,5,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-02 10:00:20'),(40,5,'admin123@gmail.com','Doctor','Delete Client','Deleted client: ID: 13255, (Email: Lhiela@gmail.com), ',NULL,'SUCCESS','2025-12-02 10:11:31'),(41,5,'admin123@gmail.com','Doctor','Delete Client','Deleted client: ID: 13258, (Email: mirahlim@gmail.com), ',NULL,'SUCCESS','2025-12-02 10:11:34'),(42,5,'admin123@gmail.com','Doctor','Delete Client','Deleted client: ID: 13252, (Email: angelo@gmail.com), ',NULL,'SUCCESS','2025-12-02 10:11:37'),(43,5,'admin123@gmail.com','Doctor','Delete Client','Deleted client: ID: 13254, (Email: Jepi@gmail.com), ',NULL,'SUCCESS','2025-12-02 10:11:42'),(44,5,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-02 16:06:55'),(45,5,'admin123@gmail.com','Doctor','Logout','Admin logged out',NULL,'SUCCESS','2025-12-02 17:18:49'),(46,5,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-02 17:19:13'),(47,5,'admin123@gmail.com','Doctor','Logout','Admin logged out',NULL,'SUCCESS','2025-12-02 23:20:23'),(48,5,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-02 23:23:19'),(49,5,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-03 08:32:36'),(50,5,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-03 08:32:36'),(51,5,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-03 12:01:53'),(52,5,'admin123@gmail.com','Doctor','Logout','Admin logged out',NULL,'SUCCESS','2025-12-03 12:06:41'),(53,7,'jmmc@gmail.com','Nurse','Login','Nurse logged in',NULL,'SUCCESS','2025-12-03 17:26:18'),(54,5,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-04 08:38:35'),(55,5,'admin123@gmail.com','Doctor','Add Consultation Record','Added consultation record for Client Email: chesterTobey@gmail.com, ClientID: 13260',NULL,'SUCCESS','2025-12-04 08:46:37'),(56,5,'admin123@gmail.com','Doctor','Logout','Admin logged out',NULL,'SUCCESS','2025-12-04 08:51:23'),(57,5,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-04 08:51:56'),(58,5,'admin123@gmail.com','Doctor','Delete Client','Deleted client: ID: 13260, (Email: chesterTobey@gmail.com), ',NULL,'SUCCESS','2025-12-04 08:52:05'),(59,5,'admin123@gmail.com','Doctor','Delete Client','Deleted client: ID: 13257, (Email: lawrence@gmail.com), ',NULL,'SUCCESS','2025-12-04 08:55:30'),(60,5,'admin123@gmail.com','Doctor','Logout','Admin logged out',NULL,'SUCCESS','2025-12-04 08:55:45'),(61,5,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-04 08:56:42'),(62,5,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-04 09:01:39'),(63,5,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-04 09:02:06'),(64,5,'admin123@gmail.com','Doctor','Delete Client','Deleted client: ID: 13261, (Email: michaelcastillo12345@gmail.com), ',NULL,'SUCCESS','2025-12-04 09:02:38'),(65,5,'admin123@gmail.com','Doctor','Delete Client','Deleted client: ID: 13253, (Email: CarlLarga@gmail.com), ',NULL,'SUCCESS','2025-12-04 09:02:45'),(66,5,'admin123@gmail.com','Doctor','Logout','Admin logged out',NULL,'SUCCESS','2025-12-04 09:03:10'),(67,5,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-04 09:03:30'),(68,5,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-05 11:07:44'),(69,5,'admin123@gmail.com','Doctor','Delete Client','Deleted client: ID: 1093, (Email: ava.pearson954@example.com), ',NULL,'SUCCESS','2025-12-05 11:08:02'),(70,5,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-05 15:12:22'),(71,5,'admin123@gmail.com','Doctor','Logout','Admin logged out',NULL,'SUCCESS','2025-12-05 15:24:20'),(72,5,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-05 15:24:41'),(73,5,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-05 15:44:16'),(74,5,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-05 15:44:23'),(75,5,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-05 15:44:37'),(76,7,'jmmc@gmail.com','Nurse','Login','Nurse logged in',NULL,'SUCCESS','2025-12-05 15:44:54'),(77,7,'jmmc@gmail.com','Nurse','Logout','Nurse logged out',NULL,'SUCCESS','2025-12-05 15:45:21'),(78,5,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-05 15:45:29'),(79,5,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-05 16:24:32'),(80,5,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-05 16:25:15'),(81,5,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-05 18:48:22'),(82,5,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-05 20:23:15'),(83,7,'jmmc@gmail.com','Nurse','Login','Nurse logged in',NULL,'SUCCESS','2025-12-05 20:23:24'),(84,7,'jmmc@gmail.com','Nurse','Logout','Nurse logged out',NULL,'SUCCESS','2025-12-05 20:28:49'),(85,7,'jmmc@gmail.com','Nurse','Login','Nurse logged in',NULL,'SUCCESS','2025-12-05 20:33:38'),(86,7,'jmmc@gmail.com','Nurse','Logout','Nurse logged out',NULL,'SUCCESS','2025-12-05 20:34:35'),(87,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-05 20:34:42'),(88,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-05 22:33:17'),(89,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-05 22:33:23'),(90,NULL,'System','','Logout','Unknown logged out',NULL,'SUCCESS','2025-12-05 22:42:23'),(91,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-05 22:52:40'),(92,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-05 22:52:51'),(93,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-05 22:53:52'),(94,7,'jmmc@gmail.com','Nurse','Login','Nurse logged in',NULL,'SUCCESS','2025-12-05 22:54:01'),(95,7,'jmmc@gmail.com','Nurse','Logout','Nurse logged out',NULL,'SUCCESS','2025-12-05 22:54:08'),(96,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-05 22:54:21'),(97,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-05 22:54:38'),(98,7,'jmmc@gmail.com','Nurse','Login','Nurse logged in',NULL,'SUCCESS','2025-12-05 22:54:46'),(99,7,'jmmc@gmail.com','Nurse','Logout','Nurse logged out',NULL,'SUCCESS','2025-12-05 22:55:39'),(100,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-05 22:55:45'),(101,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-05 22:55:56'),(102,7,'jmmc@gmail.com','Nurse','Login','Nurse logged in',NULL,'SUCCESS','2025-12-05 22:56:07'),(103,7,'jmmc@gmail.com','Nurse','Logout','Nurse logged out',NULL,'SUCCESS','2025-12-05 22:56:32'),(104,7,'jmmc@gmail.com','Nurse','Login','Nurse logged in',NULL,'SUCCESS','2025-12-05 22:56:45'),(105,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-06 15:48:52'),(106,1,'admin123@gmail.com','Doctor','Soft Delete Client','Soft deleted client: ID: 13256, (Email: jericmanibog@gmail.com).',NULL,'SUCCESS','2025-12-06 16:20:08'),(107,1,'admin123@gmail.com','Doctor','Soft Delete Client','Soft deleted client: ID: 13248, (Email: chester2@gmail.com).',NULL,'SUCCESS','2025-12-06 16:21:05'),(108,1,'admin123@gmail.com','Doctor','Soft Delete Client','Soft deleted client: ID: 13243, (Email: michael23@gmail.com).',NULL,'SUCCESS','2025-12-06 16:23:13'),(109,1,'admin123@gmail.com','Doctor','Soft Delete Client','Soft deleted client: ID: 13240, (Email: CJ@gmail.com).',NULL,'SUCCESS','2025-12-06 16:25:34'),(110,1,'admin123@gmail.com','Doctor','Soft Delete Client','Soft deleted client: ID: 13244, (Email: Susana@gmail.com).',NULL,'SUCCESS','2025-12-06 17:01:23'),(111,1,'admin123@gmail.com','Doctor','Soft Delete Client','Soft deleted client: ID: 1099, (Email: lauren.english960@example.com).',NULL,'SUCCESS','2025-12-06 17:01:28'),(112,1,'admin123@gmail.com','Doctor','Soft Delete Client','Soft deleted client: ID: 13148, (Email: james@gmail.com).',NULL,'SUCCESS','2025-12-06 17:01:31'),(113,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-06 18:45:35'),(114,1,'admin123@gmail.com','Doctor','Add Patient','Added patient: ID 13262, Name Michael Castillo',NULL,'SUCCESS','2025-12-06 20:20:35'),(115,1,'admin123@gmail.com','Doctor','Soft Delete Client','Soft deleted client: ID: 13262, (Email: ).',NULL,'SUCCESS','2025-12-06 20:21:20'),(116,1,'admin123@gmail.com','Doctor','Add Patient','Added patient: ID 13270, Name Michael Castillo',NULL,'SUCCESS','2025-12-06 20:38:50'),(117,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-06 20:41:05'),(118,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-07 13:22:03'),(119,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-07 13:22:03'),(120,1,'admin123@gmail.com','Doctor','Add Patient','Added patient: ID 13273, Name Chester Mendoza',NULL,'SUCCESS','2025-12-07 13:53:05'),(121,1,'admin123@gmail.com','Doctor','Soft Delete Client','Soft deleted client: ID: 13273, (Email: N/A).',NULL,'SUCCESS','2025-12-07 13:53:15'),(122,1,'admin123@gmail.com','Doctor','Add Patient','Added patient: ID 13274, Name Chester Mendoza',NULL,'SUCCESS','2025-12-07 13:53:54'),(123,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-07 13:53:57'),(124,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-07 16:29:08'),(125,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-07 16:31:35'),(126,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-07 16:31:55'),(127,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-07 16:32:28'),(128,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-07 16:32:37'),(129,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-07 16:32:59'),(130,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-07 16:41:50'),(131,1,'admin123@gmail.com','Doctor','Soft Delete Client','Soft deleted client: ID: 13270, (Email: N/A).',NULL,'SUCCESS','2025-12-07 16:41:56'),(132,1,'admin123@gmail.com','Doctor','Soft Delete Client','Soft deleted client: ID: 1123, (Email: beverly.cain984@example.com).',NULL,'SUCCESS','2025-12-07 16:42:01'),(133,1,'admin123@gmail.com','Doctor','Soft Delete Client','Soft deleted client: ID: 1070, (Email: carolyn.monroe931@example.com).',NULL,'SUCCESS','2025-12-07 16:42:06'),(134,1,'admin123@gmail.com','Doctor','Soft Delete Client','Soft deleted client: ID: 1139, (Email: joshua@gmail.com).',NULL,'SUCCESS','2025-12-07 16:42:14'),(135,1,'admin123@gmail.com','Doctor','Soft Delete Client','Soft deleted client: ID: 13188, (Email: jay18@gmail.com).',NULL,'SUCCESS','2025-12-07 16:42:19'),(136,NULL,'System','','Logout','Unknown logged out',NULL,'SUCCESS','2025-12-07 16:44:12'),(137,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-07 16:44:25'),(138,1,'admin123@gmail.com','Doctor','Soft Delete Client','Soft deleted client: ID: 1105, (Email: evelyn.wilson966@example.com).',NULL,'SUCCESS','2025-12-07 16:44:33'),(139,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-07 16:47:01'),(140,1,'admin123@gmail.com','Doctor','Soft Delete Client','Soft deleted client: ID: 13147, (Email: mj@gmail.com).',NULL,'SUCCESS','2025-12-07 16:47:34'),(141,1,'admin123@gmail.com','Doctor','Soft Delete Client','Soft deleted client: ID: 1118, (Email: susan.cooper979@example.com).',NULL,'SUCCESS','2025-12-07 16:48:00'),(142,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-07 16:48:05'),(143,7,'jmmc@gmail.com','Nurse','Login','Nurse logged in',NULL,'SUCCESS','2025-12-07 16:50:06'),(144,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-07 16:50:11'),(145,1,'admin123@gmail.com','Doctor','Soft Delete Client','Soft deleted client: ID: 13145, (Email: regor@gmail.com).',NULL,'SUCCESS','2025-12-07 16:50:23'),(146,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-07 16:53:29'),(147,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-07 16:56:25'),(148,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-07 16:58:52'),(149,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-07 16:58:55'),(150,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-07 17:04:32'),(151,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-07 17:04:37'),(152,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-07 17:04:38'),(153,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-07 17:04:53'),(154,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-07 17:04:57'),(155,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-07 17:05:08'),(156,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-07 17:05:51'),(157,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-07 17:05:55'),(158,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-07 17:05:56'),(159,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-08 12:47:28'),(160,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-08 12:54:02'),(161,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-08 12:54:27'),(162,1,'admin123@gmail.com','Doctor','Soft Delete Client','Soft deleted client: ID: 13271, (Email: N/A).',NULL,'SUCCESS','2025-12-08 12:57:10'),(163,1,'admin123@gmail.com','Doctor','Soft Delete Client','Soft deleted client: ID: 13272, (Email: N/A).',NULL,'SUCCESS','2025-12-08 12:57:12'),(164,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-08 13:54:28'),(165,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-09 11:20:07'),(166,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-09 13:42:37'),(167,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-09 13:56:08'),(168,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-09 14:00:37'),(169,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-09 14:01:48'),(170,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-09 14:07:20'),(171,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-09 14:11:16'),(172,7,'jmmc@gmail.com','Nurse','Login','Nurse logged in',NULL,'SUCCESS','2025-12-09 14:11:33'),(173,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-09 20:29:43'),(174,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-10 10:43:23'),(175,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-10 10:52:01'),(176,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-10 10:57:03'),(177,1,'admin123@gmail.com','Doctor','Add Consultation Record','Added consultation record for Client Email: , ClientID: 13288',NULL,'SUCCESS','2025-12-10 10:59:50'),(178,1,'admin123@gmail.com','Doctor','Add Consultation Record','Added consultation record for Client Email: , ClientID: 13287',NULL,'SUCCESS','2025-12-10 11:01:32'),(179,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-10 11:02:54'),(180,1,'admin123@gmail.com','Doctor','Add Consultation Record','Added consultation record for Client Email: , ClientID: 13289',NULL,'SUCCESS','2025-12-10 11:03:54'),(181,1,'admin123@gmail.com','Doctor','Add Consultation Record','Added consultation record for Client Email: , ClientID: 13289',NULL,'SUCCESS','2025-12-10 11:03:54'),(182,1,'admin123@gmail.com','Doctor','Add Consultation Record','Added consultation record for Client Email: , ClientID: 13283',NULL,'SUCCESS','2025-12-10 11:05:41'),(183,1,'admin123@gmail.com','Doctor','Add Consultation Record','Added consultation record for Client Email: , ClientID: 13292',NULL,'SUCCESS','2025-12-10 11:07:23'),(184,1,'admin123@gmail.com','Doctor','Add Consultation Record','Added consultation record for Client Email: , ClientID: 13293',NULL,'SUCCESS','2025-12-10 11:08:13'),(185,1,'admin123@gmail.com','Doctor','Add Consultation Record','Added consultation record for Client Email: , ClientID: 13291',NULL,'SUCCESS','2025-12-10 11:09:00'),(186,1,'admin123@gmail.com','Doctor','Add Consultation Record','Added consultation record for Client Email: , ClientID: 13290',NULL,'SUCCESS','2025-12-10 11:09:31'),(187,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-10 11:10:00'),(188,7,'jmmc@gmail.com','Nurse','Login','Nurse logged in',NULL,'SUCCESS','2025-12-10 11:10:14'),(189,1,'admin123@gmail.com','Doctor','Add Consultation Record','Added consultation record for Client Email: , ClientID: 13294',NULL,'SUCCESS','2025-12-10 11:11:11'),(190,1,'admin123@gmail.com','Doctor','Add Consultation Record','Added consultation record for Client Email: , ClientID: 13286',NULL,'SUCCESS','2025-12-10 11:11:58'),(191,1,'admin123@gmail.com','Doctor','Add Consultation Record','Added consultation record for Client Email: , ClientID: 13280',NULL,'SUCCESS','2025-12-10 11:13:04'),(192,1,'admin123@gmail.com','Doctor','Add Consultation Record','Added consultation record for Client Email: , ClientID: 13280',NULL,'SUCCESS','2025-12-10 11:13:04'),(193,1,'admin123@gmail.com','Doctor','Add Consultation Record','Added consultation record for Client Email: , ClientID: 13295',NULL,'SUCCESS','2025-12-10 11:14:36'),(194,1,'admin123@gmail.com','Doctor','Add Consultation Record','Added consultation record for Client Email: , ClientID: 13281',NULL,'SUCCESS','2025-12-10 11:16:12'),(195,7,'jmmc@gmail.com','Nurse','Logout','Nurse logged out',NULL,'SUCCESS','2025-12-10 11:26:56'),(196,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-10 11:28:04'),(197,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-10 12:19:39'),(198,7,'jmmc@gmail.com','Nurse','Login','Nurse logged in',NULL,'SUCCESS','2025-12-10 12:20:04'),(199,7,'jmmc@gmail.com','Nurse','Logout','Nurse logged out',NULL,'SUCCESS','2025-12-10 12:36:37'),(200,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-10 12:38:50'),(201,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-10 12:39:40'),(202,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-10 13:27:02'),(203,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-10 13:27:45'),(204,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-10 13:28:08'),(205,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-11 14:07:47'),(206,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-11 15:13:47'),(207,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-11 15:13:51'),(208,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-12 15:41:28'),(209,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-12 15:44:35'),(210,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-12 16:27:03'),(211,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-12 16:48:43'),(212,1,'admin123@gmail.com','Doctor','Archive Client','Archived client. ID: 13304, Email: ',NULL,'SUCCESS','2025-12-12 17:36:08'),(213,1,'admin123@gmail.com','Doctor','Restore Client','Restored archived client. ID: 13304, Email: ',NULL,'SUCCESS','2025-12-12 17:40:59'),(214,1,'admin123@gmail.com','Doctor','Archive Client','Archived client. ID: 13303, Email: ',NULL,'SUCCESS','2025-12-12 17:41:10'),(215,1,'admin123@gmail.com','Doctor','Archive Client','Archived client. ID: 13304, Email: ',NULL,'SUCCESS','2025-12-12 17:41:12'),(216,1,'admin123@gmail.com','Doctor','Restore Client','Restored archived client. ID: 13303, Email: ',NULL,'SUCCESS','2025-12-12 17:41:23'),(217,1,'admin123@gmail.com','Doctor','Restore Client','Restored archived client. ID: 13304, Email: ',NULL,'SUCCESS','2025-12-12 17:41:24'),(218,1,'admin123@gmail.com','Doctor','Archive Client','Archived client. ID: 13304, Email: ',NULL,'SUCCESS','2025-12-12 18:44:33'),(219,1,'admin123@gmail.com','Doctor','Archive Client','Archived client. ID: 13303, Email: ',NULL,'SUCCESS','2025-12-12 18:47:35'),(220,1,'admin123@gmail.com','Doctor','Archive Client','Archived client. ID: 13302, Email: ',NULL,'SUCCESS','2025-12-12 18:47:38'),(221,1,'admin123@gmail.com','Doctor','Archive Client','Archived client. ID: 13297, Email: ',NULL,'SUCCESS','2025-12-12 18:48:06'),(222,1,'admin123@gmail.com','Doctor','Restore Client','Restored archived client. ID: 13297, Email: ',NULL,'SUCCESS','2025-12-12 19:17:36'),(223,1,'admin123@gmail.com','Doctor','Restore Client','Restored archived client. ID: 13302, Email: ',NULL,'SUCCESS','2025-12-12 19:17:39'),(224,1,'admin123@gmail.com','Doctor','Restore Client','Restored archived client. ID: 13303, Email: ',NULL,'SUCCESS','2025-12-12 19:17:42'),(225,1,'admin123@gmail.com','Doctor','Restore Client','Restored archived client. ID: 13304, Email: ',NULL,'SUCCESS','2025-12-12 19:20:22'),(226,1,'admin123@gmail.com','Doctor','Archive Client','Archived client. ID: 13304, Email: ',NULL,'SUCCESS','2025-12-12 19:21:00'),(227,1,'admin123@gmail.com','Doctor','Restore Client','Restored archived client. ID: 13304, Email: ',NULL,'SUCCESS','2025-12-12 19:21:05'),(228,1,'admin123@gmail.com','Doctor','Archive Client','Archived client. ID: 13296, Email: ',NULL,'SUCCESS','2025-12-12 19:21:19'),(229,1,'admin123@gmail.com','Doctor','Restore Client','Restored archived client. ID: 13296, Email: ',NULL,'SUCCESS','2025-12-12 19:21:28'),(230,1,'admin123@gmail.com','Doctor','Archive Client','Archived client. ID: 13301, Email: ',NULL,'SUCCESS','2025-12-12 19:21:46'),(231,1,'admin123@gmail.com','Doctor','Restore Client','Restored archived client. ID: 13301, Email: ',NULL,'SUCCESS','2025-12-12 19:21:51'),(232,1,'admin123@gmail.com','Doctor','Archive Client','Archived client. ID: 13295, Email: ',NULL,'SUCCESS','2025-12-12 19:22:03'),(233,1,'admin123@gmail.com','Doctor','Restore Client','Restored archived client. ID: 13295, Email: ',NULL,'SUCCESS','2025-12-12 19:22:06'),(234,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-12 19:23:21'),(235,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-12 19:27:57'),(236,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-12 19:29:58'),(237,1,'admin123@gmail.com','Doctor','Database Restore','Admin restored the database using backup file: phpB65B.tmp',NULL,'SUCCESS','2025-12-13 10:20:45'),(238,1,'admin123@gmail.com','Doctor','Database Restore','Admin restored the database using backup file: phpED97.tmp',NULL,'SUCCESS','2025-12-13 20:07:32'),(239,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-13 20:12:15'),(240,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-13 20:22:32'),(241,1,'admin123@gmail.com','Doctor','Archive Client','Archived client. ID: 13304, Email: ',NULL,'SUCCESS','2025-12-13 20:22:46'),(242,1,'admin123@gmail.com','Doctor','Restore Client','Restored archived client. ID: 13304, Email: ',NULL,'SUCCESS','2025-12-13 20:22:51'),(243,1,'admin123@gmail.com','Doctor','Archive Client','Archived client. ID: 13304, Email: ',NULL,'SUCCESS','2025-12-13 20:23:07'),(244,1,'admin123@gmail.com','Doctor','Restore Client','Restored archived client. ID: 13304, Email: ',NULL,'SUCCESS','2025-12-13 20:23:15'),(245,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-13 20:29:50'),(246,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-13 20:32:01'),(247,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-13 20:37:42'),(248,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-14 14:32:24'),(249,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-14 14:33:14'),(250,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-14 14:33:55'),(251,1,'admin123@gmail.com','Doctor','Archive Client','Archived client. ID: 13306, Email: castillojaymichaeltk5@gmail.com',NULL,'SUCCESS','2025-12-14 14:35:20'),(252,1,'admin123@gmail.com','Doctor','Restore Client','Restored archived client. ID: 13306, Email: castillojaymichaeltk5@gmail.com',NULL,'SUCCESS','2025-12-14 14:35:28'),(253,1,'admin123@gmail.com','Doctor','Add Patient','Added patient: ID 13307, Name Michael Castillo',NULL,'SUCCESS','2025-12-14 14:40:41'),(254,1,'admin123@gmail.com','Doctor','Create Diagnostic Record','Create Diagnostic Record for Client Email: Unknown, ClientID: 13305',NULL,'SUCCESS','2025-12-14 14:52:09'),(255,1,'admin123@gmail.com','Doctor','Archive Client','Archived client. ID: 13305, Email: ',NULL,'SUCCESS','2025-12-14 15:14:17'),(256,1,'admin123@gmail.com','Doctor','Restore Client','Restored archived client. ID: 13305, Email: ',NULL,'SUCCESS','2025-12-14 15:14:23'),(257,1,'admin123@gmail.com','Doctor','Archive Client','Archived client. ID: 13305, Email: ',NULL,'SUCCESS','2025-12-14 15:14:40'),(258,1,'admin123@gmail.com','Doctor','Restore Client','Restored archived client. ID: 13305, Email: ',NULL,'SUCCESS','2025-12-14 15:14:45'),(259,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-14 15:23:52'),(260,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-14 15:27:13'),(261,1,'admin123@gmail.com','Doctor','New Physical Examination','Inserted new physical examination for Client Email: , ClientID: 13305',NULL,'SUCCESS','2025-12-14 16:58:13'),(262,1,'admin123@gmail.com','Doctor','Create Diagnostic Record','Create Diagnostic Record for Client Email: Unknown, ClientID: 13305',NULL,'SUCCESS','2025-12-14 16:59:49'),(263,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-14 17:00:18'),(264,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-14 18:25:18'),(265,1,'admin123@gmail.com','Doctor','Archive Client','Archived client. ID: 13309, Email: ',NULL,'SUCCESS','2025-12-14 18:25:53'),(266,1,'admin123@gmail.com','Doctor','Archive Client','Archived client. ID: 13308, Email: jaymichaelmontemarcastillo@gmail.com',NULL,'SUCCESS','2025-12-14 18:25:56'),(267,1,'admin123@gmail.com','Doctor','Archive Client','Archived client. ID: 13305, Email: ',NULL,'SUCCESS','2025-12-14 18:25:59'),(268,1,'admin123@gmail.com','Doctor','Archive Client','Archived client. ID: 13307, Email: ',NULL,'SUCCESS','2025-12-14 18:26:14'),(269,1,'admin123@gmail.com','Doctor','Restore Client','Restored archived client. ID: 13307, Email: ',NULL,'SUCCESS','2025-12-14 18:34:23'),(270,1,'admin123@gmail.com','Doctor','Archive Client','Archived client. ID: 13306, Email: castillojaymichaeltk5@gmail.com',NULL,'SUCCESS','2025-12-14 18:34:29'),(279,1,'admin123@gmail.com','Doctor','Updated Personal Info','Admin Updated Personal Info for ClientID 13304',NULL,'SUCCESS','2025-12-14 18:50:13'),(280,1,'admin123@gmail.com','Doctor','Add Patient','Added patient: ID 13311, Name Michael Montemar',NULL,'SUCCESS','2025-12-14 18:51:04'),(281,1,'admin123@gmail.com','Doctor','Inserted Personal Info','Admin Inserted Personal Info for ClientID 13311',NULL,'SUCCESS','2025-12-14 18:52:37'),(282,1,'admin123@gmail.com','Doctor','Archive Client','Archived client. ID: 13311, Email: ',NULL,'SUCCESS','2025-12-14 18:52:57'),(283,1,'admin123@gmail.com','Doctor','Restore Client','Restored archived client. ID: 13309, Email: ',NULL,'SUCCESS','2025-12-14 18:53:39'),(284,1,'admin123@gmail.com','Doctor','Permanent Delete Client','Permanently deleted client. ID: 13309, Email: ',NULL,'SUCCESS','2025-12-14 18:53:46'),(285,1,'admin123@gmail.com','Doctor','Archive Client','Archived client. ID: 13300, Email: ',NULL,'SUCCESS','2025-12-14 18:54:38'),(286,1,'admin123@gmail.com','Doctor','Restore Client','Restored archived client. ID: 13300, Email: ',NULL,'SUCCESS','2025-12-14 18:54:48'),(287,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-14 18:55:17'),(288,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-14 19:47:50'),(289,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-14 19:48:07'),(290,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-14 19:48:16'),(291,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-14 20:24:28'),(292,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-14 20:34:22'),(293,1,'admin123@gmail.com','Doctor','New Personnel Form Submission','Submitted new personnel form for Client Email: Unknown, ClientID: 13315',NULL,'SUCCESS','2025-12-14 20:34:49'),(294,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-14 20:35:45'),(295,1,'admin123@gmail.com','Doctor','Restore Client','Restored archived client. ID: 13311, Email: ',NULL,'SUCCESS','2025-12-14 20:36:10'),(296,1,'admin123@gmail.com','Doctor','Archive Client','Archived client. ID: 13311, Email: ',NULL,'SUCCESS','2025-12-14 20:36:19'),(297,1,'admin123@gmail.com','Doctor','Archive Client','Archived client. ID: 13313, Email: ',NULL,'SUCCESS','2025-12-14 20:37:08'),(298,1,'admin123@gmail.com','Doctor','Archive Client','Archived client. ID: 13312, Email: castillojaymichaeltk@gmail.com',NULL,'SUCCESS','2025-12-14 20:37:12'),(299,1,'admin123@gmail.com','Doctor','Archive Client','Archived client. ID: 13304, Email: ',NULL,'SUCCESS','2025-12-14 20:37:18'),(300,1,'admin123@gmail.com','Doctor','Archive Client','Archived client. ID: 13314, Email: ',NULL,'SUCCESS','2025-12-14 20:37:23'),(301,1,'admin123@gmail.com','Doctor','Archive Client','Archived client. ID: 13315, Email: ',NULL,'SUCCESS','2025-12-14 20:37:28'),(302,1,'admin123@gmail.com','Doctor','Database Restore','Admin restored the database using backup file: php6C0.tmp',NULL,'SUCCESS','2025-12-14 20:40:07'),(303,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-14 20:40:41'),(304,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-14 20:41:24'),(305,1,'admin123@gmail.com','Doctor','Restore Client','Restored archived client. ID: 13315, Email: ',NULL,'SUCCESS','2025-12-14 20:41:29'),(306,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-14 20:41:43'),(307,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-14 20:42:10'),(308,1,'admin123@gmail.com','Doctor','Restore Client','Restored archived client. ID: 13312, Email: castillojaymichaeltk@gmail.com',NULL,'SUCCESS','2025-12-14 20:42:20'),(309,1,'admin123@gmail.com','Doctor','Restore Client','Restored archived client. ID: 13314, Email: ',NULL,'SUCCESS','2025-12-14 20:42:25'),(310,1,'admin123@gmail.com','Doctor','Restore Client','Restored archived client. ID: 13313, Email: ',NULL,'SUCCESS','2025-12-14 20:42:29'),(311,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-14 20:42:49'),(312,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-14 20:48:17'),(313,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-15 11:20:03'),(314,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-15 11:24:12'),(315,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-15 11:48:43'),(316,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-15 11:49:19'),(317,1,'admin123@gmail.com','Doctor','Permanent Delete Client','Permanently deleted client. ID: 13320, Email: ',NULL,'SUCCESS','2025-12-15 11:50:31'),(318,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-15 11:50:37'),(319,1,'admin123@gmail.com','Doctor','Archive Client','Archived client. ID: 13313, Email: ',NULL,'SUCCESS','2025-12-15 11:56:39'),(320,1,'admin123@gmail.com','Doctor','Archive Client','Archived client. ID: 13317, Email: ',NULL,'SUCCESS','2025-12-15 11:57:12'),(321,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-15 13:04:59'),(322,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-15 13:07:22'),(323,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-15 13:26:26'),(324,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-15 13:26:53'),(325,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-15 13:52:39'),(326,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-15 13:53:31'),(327,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-15 13:58:31'),(328,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-15 14:04:58'),(329,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-15 14:23:49'),(330,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-15 14:35:25'),(331,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-15 20:07:23'),(332,1,'admin123@gmail.com','Doctor','Archive Client','Archived client. ID: 13328, Email: ',NULL,'SUCCESS','2025-12-15 20:07:31'),(333,1,'admin123@gmail.com','Doctor','Restore Client','Restored archived client. ID: 13328, Email: ',NULL,'SUCCESS','2025-12-15 20:07:36'),(334,1,'admin123@gmail.com','Doctor','Archive Client','Archived client. ID: 13343, Email: ',NULL,'SUCCESS','2025-12-15 20:36:52'),(335,1,'admin123@gmail.com','Doctor','Restore Client','Restored archived client. ID: 13343, Email: ',NULL,'SUCCESS','2025-12-15 20:36:58'),(336,1,'admin123@gmail.com','Doctor','Database Restore','Admin restored the database using backup file: phpBFB.tmp',NULL,'SUCCESS','2025-12-15 20:37:33'),(337,1,'admin123@gmail.com','Doctor','Archive Client','Archived client. ID: 13342, Email: ',NULL,'SUCCESS','2025-12-15 20:37:57'),(338,1,'admin123@gmail.com','Doctor','Restore Client','Restored archived client. ID: 13342, Email: ',NULL,'SUCCESS','2025-12-15 20:38:04'),(339,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-15 20:42:59'),(340,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-16 19:41:50'),(341,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-16 20:41:06'),(342,1,'admin123@gmail.com','Doctor','Archive Client','Archived client. ID: 13343, Email: ',NULL,'SUCCESS','2025-12-16 20:41:22'),(343,1,'admin123@gmail.com','Doctor','Restore Client','Restored archived client. ID: 13343, Email: ',NULL,'SUCCESS','2025-12-16 20:41:29'),(344,1,'admin123@gmail.com','Doctor','Archive Client','Archived client. ID: 13343, Email: ',NULL,'SUCCESS','2025-12-16 20:41:34'),(345,1,'admin123@gmail.com','Doctor','Restore Client','Restored archived client. ID: 13343, Email: ',NULL,'SUCCESS','2025-12-16 20:41:39'),(346,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-16 20:41:59'),(347,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-16 20:42:05'),(348,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-16 21:34:32'),(349,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-17 09:36:21'),(350,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-17 09:49:47'),(351,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-17 10:32:22'),(352,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-17 10:33:06'),(353,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-17 10:35:38'),(354,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-17 11:07:32'),(355,1,'admin123@gmail.com','Doctor','Database Restore','Admin attempted to restore the database using backup file: phpDCBE.tmp but failed',NULL,'FAILED','2025-12-17 11:07:40'),(356,1,'admin123@gmail.com','Doctor','Database Restore','Admin restored the database using backup file: phpA47.tmp',NULL,'SUCCESS','2025-12-17 11:07:52'),(357,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-20 11:31:04'),(358,1,'admin123@gmail.com','Doctor','Archive Client','Archived client. ID: 13344, Email: ',NULL,'SUCCESS','2025-12-20 11:31:20'),(359,1,'admin123@gmail.com','Doctor','Database Restore','Admin restored the database using backup file: phpF268.tmp',NULL,'SUCCESS','2025-12-20 11:31:46'),(360,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-20 11:32:08'),(361,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-20 11:37:47'),(362,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-20 13:41:08'),(363,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-20 13:44:45'),(364,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-20 15:00:17'),(365,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-24 11:08:30'),(366,1,'admin123@gmail.com','Doctor','Database Restore','Admin restored the database using backup file: php4686.tmp',NULL,'SUCCESS','2025-12-24 12:32:59'),(367,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-24 12:33:07'),(368,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-29 17:06:37'),(369,1,'admin123@gmail.com','Doctor','Restore Client','Restored archived client. ID: 13344, Email: ',NULL,'SUCCESS','2025-12-29 17:07:08'),(370,1,'admin123@gmail.com','Doctor','Logout','Doctor logged out',NULL,'SUCCESS','2025-12-29 17:07:29'),(371,1,'admin123@gmail.com','Doctor','Login','Doctor logged in',NULL,'SUCCESS','2025-12-29 18:47:55'),(372,1,'admin123@gmail.com','Doctor','Archive Client','Archived client. ID: 13344, Email: ',NULL,'SUCCESS','2025-12-29 18:48:28'),(373,1,'admin123@gmail.com','Doctor','Restore Client','Restored archived client. ID: 13304, Email: ',NULL,'SUCCESS','2025-12-29 18:49:22');
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `user_type` enum('Doctor','Nurse') NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'Michael','Doctor','admin123@gmail.com','$2y$10$ddBiExNt3lWGlUQ.HHGrIef78pdSAqFYiyOVhXOKLDKGv/3vQF4Xu'),(2,'jmmc','Doctor','','$2y$10$5KnDyG5g5qQZ2OaEQnmliegh50zV6tewLTjFM7psl3KuuTPP20ZNC'),(3,'admin12345','Doctor','','$2y$10$Q4M...Eg1MwQXovdJMZCfe3LUMZe365/MEc1XXEoIyDlCxW1dZ5PS'),(4,'admin123456','Doctor','','$2y$10$aR9onAfD2tRL73tLQIDON.nRm/r8FHxPW7I.vSfvN7404IrQ50hZi'),(5,'admin123@gmail.com','Doctor','','$2y$10$t16JpmKOLnOi.a3BjM0qT.u6L.xpoBWITvYOplrUQ0FAL11u/VfJK'),(7,'Jay Michael','Nurse','jmmc@gmail.com','$2y$10$VA7JRRG8VSH/qW.AEAnw7e2A7e3XPwu4KN1eMNlcjY31dIWxaSe5u');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `annual_exams`
--

DROP TABLE IF EXISTS `annual_exams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `annual_exams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_size` int(11) NOT NULL,
  `file_type` varchar(100) NOT NULL,
  `upload_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `annual_exams_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`ClientID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `annual_exams`
--

LOCK TABLES `annual_exams` WRITE;
/*!40000 ALTER TABLE `annual_exams` DISABLE KEYS */;
/*!40000 ALTER TABLE `annual_exams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `archive_clients`
--

DROP TABLE IF EXISTS `archive_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `archive_clients` (
  `ArchiveID` int(11) NOT NULL AUTO_INCREMENT,
  `ClientID` int(11) NOT NULL,
  `Firstname` varchar(50) DEFAULT NULL,
  `Lastname` varchar(50) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Username` varchar(50) DEFAULT NULL,
  `Sex` enum('Male','Female') DEFAULT NULL,
  `BirthDate` date DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `ClientType` enum('Freshman','Student','Faculty','Personnel','NewPersonnel','Default') DEFAULT NULL,
  `Department` varchar(100) DEFAULT NULL,
  `Course` varchar(50) DEFAULT NULL,
  `profilePicturePath` varchar(255) DEFAULT NULL,
  `ResetCode` varchar(10) DEFAULT NULL,
  `deleted_by` int(11) NOT NULL,
  `deleted_at` datetime NOT NULL,
  PRIMARY KEY (`ArchiveID`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `archive_clients`
--

LOCK TABLES `archive_clients` WRITE;
/*!40000 ALTER TABLE `archive_clients` DISABLE KEYS */;
INSERT INTO `archive_clients` VALUES (18,13308,'Jay','Michael Castillo','jaymichaelmontemarcastillo@gmail.com','Jay Michael','Male','2004-02-18','$2y$10$Mr7Oe2szFtE2rRykDGY6U.OvPE99FiXIAEYsyLzWMesFxH.u4jn.u','Freshman','College of Computer Studies','Bachelor of Science in Information Technology',NULL,NULL,1,'2025-12-14 18:25:56'),(19,13305,'Michael','Montemar',NULL,'Michael Jay','Male','2004-02-18','$2y$10$rzF5QlTwFJRcQgyAdxKYp.0ELJgc1PJoRe36ejLrZM4bSjq0RgADO','Freshman','College of Computer Studies','Bachelor of Science in Information Technology',NULL,NULL,1,'2025-12-14 18:25:59'),(21,13306,'Michael','Castillo','castillojaymichaeltk5@gmail.com','MichaelJay18','Male','2004-02-18','$2y$10$OhWMGmLs9owJMO1Go28seuLL72lWvpNakDwZ61i1g/jdN9VD/hhEO','Student','College of Computer Studies','Bachelor of Science in Information Technology',NULL,NULL,1,'2025-12-14 18:34:29'),(24,13311,'Michael','Montemar',NULL,'Michael181414','Male','2004-02-18','$2y$10$m6tZ2ijxa2CzpSGZWJQ8k.nHlWU4/QHTT/fBBwzOU5yl9R4MbmrRm','Freshman','College of Computer Studies',NULL,NULL,NULL,1,'2025-12-14 20:36:19'),(30,13313,'User Test ','User Test ',NULL,'Test User ','Male','2004-02-18','$2y$10$zHzXJfro23WL18sgPBv9aO5REVOgw.XbynQYnXYcPhZBST9Zh62IW','Freshman','College of Computer Studies','Bachelor of Science in Information Technology',NULL,NULL,1,'2025-12-15 11:56:39'),(31,13317,'Test User','Castillo',NULL,'MIchael Test ','Male','2004-02-18','$2y$10$pnfdH4rynP3GhYP4Y/tQ2./NcDCIDPHRxWnBJlE65LviwXvM3LZ.u','Freshman','College of Computer Studies','Bachelor of Science in Information Technology',NULL,NULL,1,'2025-12-15 11:57:12'),(38,13344,'try','skill',NULL,'tryskill','Female','2025-12-15','$2y$10$6cM2AUXq2ttQiectCz4nRugYD2MR54bqlf7B9VmncYS4NFKV2gYAO','Student','College of Computer Studies','Bachelor of Science in Information Technology',NULL,NULL,1,'2025-12-29 18:48:28');
/*!40000 ALTER TABLE `archive_clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `backup_logs`
--

DROP TABLE IF EXISTS `backup_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `backup_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) NOT NULL,
  `backup_date` date NOT NULL,
  `backup_time` time NOT NULL,
  `status` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `backup_logs`
--

LOCK TABLES `backup_logs` WRITE;
/*!40000 ALTER TABLE `backup_logs` DISABLE KEYS */;
INSERT INTO `backup_logs` VALUES (1,'university_clinic_backup_2025-10-09_01-49-24.sql','2025-10-09','01:49:26','success'),(2,'university_clinic_backup_2025-10-14_13-51-59.sql','2025-10-14','01:52:00','success'),(3,'university_clinic_backup_2025-10-18_14-10-07.sql','2025-10-18','02:10:10','success'),(4,'university_clinic_backup_2025-10-20_02-46-08.sql','2025-10-20','02:46:10','success'),(5,'university_clinic_backup_2025-10-20_12-56-23.sql','2025-10-20','12:56:25','success'),(6,'university_clinic_backup_2025-10-20_16-18-34.sql','2025-10-20','04:18:36','success'),(7,'university_clinic_backup_2025-10-30_00-42-28.sql','2025-10-30','12:42:29','success'),(8,'university_clinic_backup_2025-10-30_01-05-52.sql','2025-10-30','01:05:54','success'),(9,'university_clinic_backup_2025-10-30_01-11-46.sql','2025-10-30','01:11:48','success'),(10,'university_clinic_backup_2025-10-30_01-13-12.sql','2025-10-30','01:13:14','success'),(11,'university_clinic_backup_2025-10-30_01-13-59.sql','2025-10-30','01:14:00','success'),(12,'university_clinic_backup_2025-12-04_02-04-03.sql','2025-12-04','02:04:04','success'),(13,'university_clinic_backup_2025-12-10_05-11-27.sql','2025-12-10','05:11:28','success');
/*!40000 ALTER TABLE `backup_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_certificates`
--

DROP TABLE IF EXISTS `client_certificates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `client_certificates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_certificates`
--

LOCK TABLES `client_certificates` WRITE;
/*!40000 ALTER TABLE `client_certificates` DISABLE KEYS */;
INSERT INTO `client_certificates` VALUES (1,20,'uploads/medical_certificates/20_1745829961.pdf','2025-04-28 08:46:01'),(2,20,'uploads/medical_certificates/20_1745831054.pdf','2025-04-28 09:04:14'),(3,20,'uploads/medical_certificates/20_1745832230.pdf','2025-04-28 09:23:50'),(4,20,'uploads/medical_certificates/20_1745832660.pdf','2025-04-28 09:31:00'),(5,20,'../../../uploadspdf/medical_certificates/20_1745832802.pdf','2025-04-28 09:33:22'),(6,20,'../../../uploadspdf/medical_certificates/20_1745834223.pdf','2025-04-28 09:57:03'),(7,20,'../../../public/uploads/client_certificates/20_1745835030.pdf','2025-04-28 10:10:30'),(8,20,'../../../public/uploads/client_certificates/20_1745835119.pdf','2025-04-28 10:11:59'),(9,20,'../../../uploads/client_certificates/20_1745835294.pdf','2025-04-28 10:14:54');
/*!40000 ALTER TABLE `client_certificates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clients` (
  `ClientID` int(11) NOT NULL AUTO_INCREMENT,
  `Firstname` varchar(50) NOT NULL,
  `Lastname` varchar(50) NOT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Username` varchar(50) DEFAULT NULL,
  `Sex` enum('Male','Female') DEFAULT NULL,
  `BirthDate` date DEFAULT NULL,
  `Password` varchar(255) NOT NULL,
  `ClientType` enum('Freshman','Student','Faculty','Personnel','NewPersonnel','Default') NOT NULL DEFAULT 'Default',
  `Department` varchar(100) DEFAULT NULL,
  `Course` varchar(50) DEFAULT NULL,
  `profilePicturePath` varchar(255) DEFAULT NULL,
  `ResetCode` varchar(10) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`ClientID`),
  UNIQUE KEY `Email` (`Email`),
  UNIQUE KEY `Username` (`Username`),
  UNIQUE KEY `Email_2` (`Email`)
) ENGINE=InnoDB AUTO_INCREMENT=13348 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients`
--

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
INSERT INTO `clients` VALUES (13278,'John','Valdez',NULL,'Valdez','Male','1999-11-11','$2y$10$I4UWp2p8jQjRKDAo4fIt1O82Mt926bG5MWlNXPP.m5wK3hcsOShQi','Faculty','College of Computer Studies','',NULL,NULL,NULL),(13279,'Wil','Su',NULL,'su11','Male','1990-04-11','$2y$10$Mq6ls6USoNS7XxrPgRekGuwU4uSH4RozrzDLduDxjqcbLf/9abh/i','Faculty','College of Computer Studies','',NULL,NULL,NULL),(13280,'John Paolo','Medallom',NULL,'jpmedallom','Male','2003-06-30','$2y$10$2n2wWCJgZLY24TYexhAfCOgHrBZ4zV.vwAj4ZcCb/7lxj/NlLsRqa','Student','College of Computer Studies','Bachelor of Science in Information Technology',NULL,NULL,NULL),(13281,'jeric','manibog',NULL,'jerics02','Male','2005-05-02','$2y$10$2DFOLXVHL3Gl8FMQiyncpeFBIxmzN6kRFPKdyisLawEvypTUdpgAi','Student','College of Computer Studies','Bachelor of Science in Information Technology',NULL,NULL,NULL),(13282,'Noemelyn','Abasola',NULL,'noemelyn_abasola','Female','2003-11-23','$2y$10$CeSjiaJXitpPzwn6clpwZe1jXC8aL6ReAZ0wFaOkRM2yMUJBNWvge','Student','College of Computer Studies','Bachelor of Science in Information Technology',NULL,NULL,NULL),(13283,'Tobey','Fernandez',NULL,'Tobey','Male','2004-01-11','$2y$10$Y76lvprF91mMyOrOkuIy2.pVvNWVtvlGedm9dFArbAuicNcSVVrn.','Student','College of Computer Studies','Bachelor of Science in Information Technology',NULL,NULL,NULL),(13284,'Aldrin','Terbi',NULL,'Drin','Male','2004-05-17','$2y$10$PMpXVXnt5l9ejKnCY9Qa2.vqQkAKyhr71xzEsOXjZ8ZzEzzdiEbKm','Student','College of Computer Studies','Bachelor of Science in Information Technology',NULL,NULL,NULL),(13285,'Sam','Alcantara',NULL,'Samlcntr','Female','2004-12-09','$2y$10$U4K.LlwH.sghhpEg.FlcCOShoctXUmrRjdtj4B0JJ6qYLJzZDdhE.','Student','College of Computer Studies','Bachelor of Science in Information Technology',NULL,NULL,NULL),(13286,'Alexis','Dela cruz',NULL,'Alexisdelacruz','Male','2004-10-10','$2y$10$2LhEhnr1VTBGKSPSZYSfIuLEMxU4eTWjIPBSSobkwrrz8D1o0yRZy','Student','College of Hospitality Management and Tourism','Bachelor of Science in Hospitality Management',NULL,NULL,NULL),(13287,'Gretchen','Andaya',NULL,'gretchen','Female','2005-05-09','$2y$10$xZJbU2A8TdT/ydK5dL9Xl.I.JoRHZFOtNW/Rm8Ssnqn09amqbGD3O','Student','College of Computer Studies','Bachelor of Science in Information Technology',NULL,NULL,NULL),(13288,'Israel','San valentin',NULL,'Rael04','Male','2004-08-14','$2y$10$KtXgy0m2zYxRJ.LUbwREfunM4VnwTi8uVgGbOiNkcJAS9VJyUX6Fy','Student','College of Fisheries','Bachelor of Science in Fisheries',NULL,NULL,NULL),(13289,'Angel','Martin',NULL,'angelmartin','Female','2004-06-22','$2y$10$1/R4lWLfbdaohTvtLoxW0O.ikhD.IYmYpIeL6lLV77uXX3XBKqdrW','Student','College of Computer Studies','Bachelor of Science in Information Technology',NULL,NULL,NULL),(13290,'Jared Nate ','Odian',NULL,'Jaywan','Male','2005-10-28','$2y$10$ePg856VlSPL/baCaObVYR.y8DsF1CzT4yPn3HYn2q7cTI/N9Cxh1e','Student','College of Computer Studies','Bachelor of Science in Information Technology',NULL,NULL,NULL),(13291,'Danielle','Linga',NULL,'NYEL','Male','2000-10-02','$2y$10$d1gJjLHBhdSlKkTvwD/8ceeQKYoZ3KbOCiwB2R/U9l6noK6N6ezrm','Student','College of Computer Studies','Bachelor of Science in Information Technology',NULL,NULL,NULL),(13292,'Alex','Blasco',NULL,'Alexandra','Female','2025-12-10','$2y$10$BrPQmQUcthbHC/KvRTfMOur7yZnW/RjHaOR68PUddVJRS/OxelXB2','Student','College of Arts and Sciences','Bachelor of Arts in English',NULL,NULL,NULL),(13293,'John Royce','Esporlas',NULL,'John Royce','Male','2005-05-15','$2y$10$L9TgnhtDFrhE4RPoA80UCu.2CKQ9oS.GLMMPRKqm2Fsbf0/cmzQnG','Student','College of Computer Studies','Bachelor of Science in Information Technology',NULL,NULL,NULL),(13294,'Christian ','Cabisada ',NULL,'Shinra','Male','2001-11-12','$2y$10$6aG.IQgjvY57nRygWH4ko.sgbWiLbkR4bPmuuELBxjfd2wqRQifsC','Student','College of Computer Studies','Bachelor of Science in Information Technology',NULL,NULL,NULL),(13295,'Gife','Laforteza',NULL,'gife','Male','2010-01-26','$2y$10$7XcvHxVzDC6VED34A1BJVeL0lRewsY8h.dSt737Td.QApx6jR2A.y','Student','College of Computer Studies','Bachelor of Science in Information Technology',NULL,NULL,NULL),(13296,'Lawrence ','Salvador ',NULL,'Thoughtoy','Male','2005-01-29','$2y$10$4mVBMfM35rIVQk0iZAxu1.u60TEYh6ec9DcyB9U1mPvT5lJi.FRQe','Student','College of Computer Studies','Bachelor of Science in Information Technology',NULL,NULL,NULL),(13297,'Myra','Paicaglino',NULL,'myraxvas@gmail.com','Female','1971-09-02','$2y$10$xK8C7bpAST2/324u7cAc6./Qs4Hr6kRqCBfwBTdia1h3i2qEOlkyi','Faculty','College of Computer Studies','',NULL,NULL,NULL),(13298,'Jocelyn','Padallan',NULL,'joy','Female','1981-10-15','$2y$10$MyZyaiDrLh01WUpo7abWUusxnu0DBEHhaIXYxOA0B/GuqsizP4tvG','Faculty','College of Computer Studies','',NULL,NULL,NULL),(13299,'Sherwin','Sapin',NULL,'sbsapin','Male','1977-07-24','$2y$10$jjSfEkWQ740a7sdAOgJ/C.FVl1A0a1J4E7DKyuSoScQ3Vvtl5k5IW','Faculty','College of Computer Studies','',NULL,NULL,NULL),(13300,'Alisah','Alinader',NULL,'Aly','Female','2005-06-07','$2y$10$G42XHI.3Y9XQ1368j9tylem8LsEVzeI.h6BhDE6Z.50zMsKfbl4Eu','Freshman','College of Arts and Sciences','Bachelor of Arts in English',NULL,NULL,NULL),(13301,'Lheilah','Tandang',NULL,'lbcallie_tp','Female','2004-06-19','$2y$10$puIEQ97YgJ2t5pK9vJwFJORJHwgAhPkLcpFpFSjg6OeMpz6oQef0.','Student','College of Computer Studies','Bachelor of Science in Information Technology',NULL,NULL,NULL),(13302,'JOSHUA','ANGELES',NULL,'JOSHUA','Male','2003-12-20','$2y$10$184fgqe4fvYyjjXhs9VYaeoFeXGEJqc3hYAakAxbezciamyIX/4mC','Student','College of Computer Studies','Bachelor of Science in Information Technology',NULL,NULL,NULL),(13303,'AJ','ESCOBIN',NULL,'EYJAY','Male','2004-08-03','$2y$10$ezrQeybS.iSa5jyKnDiK.u9bMqhcjIjqURzQZTXcUhFW8dClqjYFa','Student','College of Computer Studies','Bachelor of Science in Information Technology',NULL,NULL,NULL),(13304,'Jay Michael','Castillo',NULL,'Michael','Male','2004-02-18','$2y$10$WtRb93z.I0wwaTZB44fFROhSZ0wishEi7xi8Enig7bTt969L4Cyca','Student','College of Computer Studies','Bachelor of Science in Information Technology',NULL,NULL,NULL),(13307,'Michael','Castillo',NULL,'Michael Personnel','Male','2004-02-18','$2y$10$JkI/QACHsytKOhJMBVlOfeCaijMZuenFYDPdtw9Vou7HEACMf8Z5.','NewPersonnel','',NULL,NULL,NULL,NULL),(13310,'Chester','Mendoza',NULL,'Chester','Male','2004-12-14','$2y$10$/4jcHwrW.mmUHiUVuc9r9OA0SXammc/F0qgweWUTkY0iX4aw/L4hG','Default',NULL,NULL,NULL,NULL,NULL),(13312,'Juan','Dela Cruz','castillojaymichaeltk@gmail.com','Juan18','Male','2004-02-18','$2y$10$03xnygS1yf9hMRPNhpBqK.AcL6V5lfb7xc9Q.Xm615WoAW5IQc68S','Freshman','College of Computer Studies','Bachelor of Science in Information Technology',NULL,NULL,NULL),(13314,'Test User','Test User ',NULL,'Test Users 2','Male','2004-02-18','$2y$10$L3WOJZ3ANyjhd8TB9vZJV..pi3nzWXTDhNsFtGV.x06g8U.3WBdyK','Faculty','College of Computer Studies','',NULL,NULL,NULL),(13315,'Test User ','Test User ',NULL,'Test User 3','Male','2004-02-18','$2y$10$fUTxMHalg8kYBiJaYlalL.6Skw3OpaZnw8EyfrcSx3KLudBLj3ixm','NewPersonnel','College of Computer Studies','',NULL,NULL,NULL),(13316,'John Russel','Blanco',NULL,'RussKiko','Male','2006-09-08','$2y$10$NKXUYNnP6JPPuC0uiC8A7OK03BiRXllsgtJXhZvJAzl/ISfTxO/iu','Student','College of Computer Studies','Bachelor of Science in Computer Science',NULL,NULL,NULL),(13318,'Paulo','Escritor',NULL,'Pau','Male','2004-04-19','$2y$10$NOalDmprnrpJHEWmF0cQQOwT6v79mcIigF794njoY5oVphbi3gk/W','Student','College of Computer Studies','Bachelor of Science in Computer Science',NULL,NULL,NULL),(13319,'joshua miguel','santos',NULL,'jmsantos18','Male','2006-08-14','$2y$10$z5y.NuVLURTKY3DXJ.MCwud9t4hKVy4FF2T3JX0X4jNGCKL46H.PC','Freshman','College of Teacher Education','Bachelor of Secondary Education',NULL,NULL,NULL),(13321,'vincent','reyes',NULL,'vincent','Male','2005-09-21','$2y$10$KaKrHsKr2YskVBzptawbfuFBn2py3V0epO5Jcew8VkEHIO05OQgMu','Freshman','College of Arts and Sciences','Bachelor of Arts in English',NULL,NULL,NULL),(13322,'angela','cruz',NULL,'angela02','Female','2006-11-22','$2y$10$qzeQzfRP2xTrIIYcM/Nw7.MRjOD.QkA8k.MoNSTCr8XImK7tTsnaW','Freshman','College of Teacher Education','Bachelor of Secondary Education',NULL,NULL,NULL),(13323,'mark anthony','flores',NULL,'markkk','Male','2005-04-17','$2y$10$.XtUu6msfboeJdAKpJ885ednerQwROZXuBSWmnoVKErpom7abtFAa','Freshman','College of Teacher Education','Bachelor of Secondary Education',NULL,NULL,NULL),(13324,'alyssa','torres',NULL,'alyssa','Female','2005-10-18','$2y$10$yxQRZbiG6kpvj7DLGbTDteeaO.44htPBmY/oz1ypXzGofIFP413ie','Freshman','College of Hospitality Management and Tourism','Bachelor of Science in Hospitality Management',NULL,NULL,NULL),(13325,'hannah','peralta',NULL,'hannahperalta','Female','2006-05-03','$2y$10$vnb.EF3NFB5zfMsqwdmPOeH0JGWtBlpUgSEnHXcZkDQdv057g1/OO','Freshman','College of Hospitality Management and Tourism','Bachelor of Science in Hospitality Management',NULL,NULL,NULL),(13326,'samantha','villanueva',NULL,'sam','Female','2004-09-26','$2y$10$GoGNNpa19naewUDSx2hiz.JNSXkF/W1lLPw6brI5Zm2yO6fC6TRBW','Freshman','College of Hospitality Management and Tourism','Bachelor of Science in Hospitality Management',NULL,NULL,NULL),(13327,'ronald','aquino',NULL,'ronaldaquino','Male','2005-08-11','$2y$10$zmvqUrEBSvhaDCbVuajaPuNf.PbD5.DZnI3/Kk0CZ7B69Hy1ymKia','Freshman','College of Computer Studies','Bachelor of Science in Computer Science',NULL,NULL,NULL),(13328,'John Ian','Subiaga',NULL,'Iansubiaga','Male','2004-07-14','$2y$10$ogznvwfDx5FGp2mw7yZR8uY.1tsWOJplfHBwZ2bN5TTUkobyM6ZO.','Freshman','College of Computer Studies','Bachelor of Science in Information Technology',NULL,NULL,NULL),(13329,'Jonalyn Joy','Labayne',NULL,'jojoylabayne','Female','1984-05-28','$2y$10$zqMqzedRzTyQaruQFGgW3uIAjHcXuFOxZcR5WZZo0iFg/OXOaO3oq','Personnel','College of Computer Studies','',NULL,NULL,NULL),(13330,'trisha','sasoy',NULL,'Trishaann','Female','2006-04-09','$2y$10$lCdLzic7VJpRoIT2vKJGSO7G6KWCh3pNvMgoYmxt4KJjXzpzRBSfu','Student','College of Hospitality Management and Tourism','Bachelor of Science in Tourism Management',NULL,NULL,NULL),(13331,'zaira mae','Atienza',NULL,'zayii-22','Female','2006-09-22','$2y$10$W1Vqbu.Y0XlxLzR9zWWHsOTMAlQJze3HgDRWOnkGc.7bzxGt50Xg2','Student','College of Hospitality Management and Tourism','Bachelor of Science in Tourism Management',NULL,NULL,NULL),(13332,'denver','drio',NULL,'denverxnestor','Male','2005-09-10','$2y$10$qxp8wsivattuNhDhjp0H5eLeiEW9rpQIiatD/tMtz7hROwTDw3mI.','Student','College of Computer Studies','Bachelor of Science in Information Technology',NULL,NULL,NULL),(13333,'Jefferson','Lerios',NULL,'jefferson','Male','1982-08-24','$2y$10$7Eg4NNdHOUpUIpxWUtwZBOy13pUHlL5OvH64H1oKc9u1uYe0dH9DC','Faculty','College of Computer Studies','',NULL,NULL,NULL),(13334,'Alejandro','Matute',NULL,'u8aos1','Male','1994-03-03','$2y$10$QVCYqoza4v2nMpOfuOGRbeRP5CH54ZQTNoMpn.Om8FAQwAL9AHhAG','Faculty','College of Computer Studies','',NULL,NULL,NULL),(13335,'mirah ','lim',NULL,'mirayy07','Female','2005-09-07','$2y$10$OcGUqFsY13KW7yBKk4DwyOOGfN2V3zfNF3eYb5w0e7kc/UrPhmNUq','Student','College of Computer Studies','Bachelor of Science in Information Technology',NULL,NULL,NULL),(13336,'josef ','leonardo',NULL,'josefleonardo','Male','2003-07-26','$2y$10$5xWEMm6Zc6W70PGPWxFmnuXOlmD/SVGSLyVWId1XZUCB5I5BtCWN2','Student','College of Computer Studies','Bachelor of Science in Computer Science',NULL,NULL,NULL),(13337,'Andrea','Gecolea',NULL,'AEGECO','Female','2004-05-01','$2y$10$2oL06uOFxL2pyvXpCMMTo.xYbniDJNWiqfYj4d0PadycvU8q1B.1a','Default',NULL,NULL,NULL,NULL,NULL),(13338,'Andrea','Gecolea',NULL,'ZKDLIN18','Female','2004-05-01','$2y$10$M7qXeYLbeUogvPM8.bftluuYH3K6Jpu/rPUMbeVNO5MYjOvggoLtC','Student','College of Computer Studies','Bachelor of Science in Computer Science',NULL,NULL,NULL),(13339,'Xailah May ','Banasihan',NULL,'lhaiax','Female','2004-09-02','$2y$10$PDTVbApbCL1Blu5D1tfhxOhMzQSslY1Wyh.o8vKAfOYKnzMQbKOgG','Student','College of Computer Studies','Bachelor of Science in Computer Science',NULL,NULL,NULL),(13340,'vic','salen',NULL,'vickyy','Male','2025-11-30','$2y$10$TDZLxf4hS7215ayqvDhfYu6CufT8r3MGm7wKEYhIc9r31mTiugy4a','Student','College of Computer Studies','Bachelor of Science in Information Technology',NULL,NULL,NULL),(13341,'Kenneth','Punla',NULL,'Kinnit','Male','2005-10-25','$2y$10$b0PXRnOt2CmMdPaohldXs.62sFfZCMEycvsOC8Vom3.wGXopbg.IC','Student','College of Computer Studies','Bachelor of Science in Computer Science',NULL,NULL,NULL),(13342,'Chris','Balogo',NULL,'12345678','Male','2003-10-12','$2y$10$q8rYJBsBTykXSXxL.pm0Fud85Gsf8DhEnDbQhsUSfWPNlpgcxyhle','Student','College of Computer Studies','Bachelor of Science in Information Technology',NULL,NULL,NULL),(13343,'Ivan Angelo','Cano',NULL,'ivaughn','Male','2005-12-16','$2y$10$zgKzSwfl6UOY8fwROOM12Oe9MluB/qFWSSJ5SyaABbM5nOqFbnfzG','Student','College of Computer Studies','Bachelor of Science in Information Technology',NULL,NULL,NULL),(13345,'Michael ','Castillo',NULL,'MichaelJay18','Female','2004-02-18','$2y$10$NNPrMIAb5DVWnboyUq5DVOLnm0a.TQlzvQNMddP1vkspeoVvP4xB6','Freshman','College of Computer Studies','Bachelor of Science in Information Technology',NULL,NULL,NULL),(13346,'Jay Michael ','Castillo',NULL,'Michaeljay1814','Male','2004-02-18','$2y$10$K5IfeU1Sq0A.o.rrRDE4KeUE.Yw8s39zWKBBA63tlkj7LXMhWrUNK','Freshman','College of Computer Studies','Bachelor of Science in Information Technology',NULL,NULL,NULL),(13347,'Michael','Castillo',NULL,'MichaelJay123','Male','2004-02-18','$2y$10$eNDeFF1sSihWFspdW71wROkYw4hiRHhhcZDK21.AMUrV5gbPrN5P6','Freshman','College of Computer Studies','Bachelor of Science in Information Technology',NULL,NULL,NULL);
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `consultationrecords`
--

DROP TABLE IF EXISTS `consultationrecords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `consultationrecords` (
  `consultationid` int(11) NOT NULL AUTO_INCREMENT,
  `ClientID` int(11) NOT NULL,
  `historyid` int(11) NOT NULL,
  `BP` varchar(10) DEFAULT NULL,
  `HR_PR` varchar(10) DEFAULT NULL,
  `Temp` varchar(10) DEFAULT NULL,
  `O2sat` varchar(10) DEFAULT NULL,
  `Subjective` text DEFAULT NULL,
  `Objective` text DEFAULT NULL,
  `Assesment` text DEFAULT NULL,
  `Plan` text DEFAULT NULL,
  `datecreated` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`consultationid`),
  KEY `fk_consult_client` (`ClientID`),
  KEY `fk_consult_history` (`historyid`),
  CONSTRAINT `fk_consult_client` FOREIGN KEY (`ClientID`) REFERENCES `clients` (`ClientID`) ON DELETE CASCADE,
  CONSTRAINT `fk_consult_history` FOREIGN KEY (`historyid`) REFERENCES `history` (`historyID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=191 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `consultationrecords`
--

LOCK TABLES `consultationrecords` WRITE;
/*!40000 ALTER TABLE `consultationrecords` DISABLE KEYS */;
INSERT INTO `consultationrecords` VALUES (176,13288,347,'43','32','35','65','Subjective','Subjective','Subjective','Subjective','2025-12-10 02:59:50'),(177,13287,348,'80/100','100/100','35.6','65','Subjective','Objective','Assessment','Plan','2025-12-10 03:01:32'),(178,13289,349,'80/100','100/100','35','65','Subjective','Subjective','Subjective','Subjective','2025-12-10 03:03:54'),(179,13289,350,'80/100','100/100','35','65','Subjective','Subjective','Subjective','Subjective','2025-12-10 03:03:54'),(180,13283,351,'180/100','100/200','123','123','test','test','test','test','2025-12-10 03:05:41'),(181,13292,352,'180/100','100/200','123','123','TEST','TEST','TEST','TEST','2025-12-10 03:07:23'),(182,13293,353,'180/100','100/200','123','123','TEST','TEST','TEST','TEST','2025-12-10 03:08:13'),(183,13291,354,'180/100','100/200','123','123','TEST','TEST','TEST','TEST','2025-12-10 03:09:00'),(184,13290,355,'180/100','100/200','123','123','TEST','TEST','TESTQ','TEST','2025-12-10 03:09:31'),(185,13294,356,'180/100','100/200','123','123','TEST','TEST','TEST','TEST','2025-12-10 03:11:11'),(186,13286,357,'180/100','100/200','123','123','TEST','TEST','TEST','TEST','2025-12-10 03:11:58'),(187,13280,358,'180/100','100/200','123','123','TEST','TEST','TEST','TEST','2025-12-10 03:13:04'),(188,13280,359,'180/100','100/200','123','123','TEST','TEST','TEST','TEST','2025-12-10 03:13:04'),(190,13281,361,'180/100','100/200','123','123','TEST','TEST','TEST','TEST','2025-12-10 03:16:12');
/*!40000 ALTER TABLE `consultationrecords` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `consultations`
--

DROP TABLE IF EXISTS `consultations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `consultations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `historyID` int(11) DEFAULT NULL,
  `consultation_date` date NOT NULL,
  `certificate_issued` tinyint(1) DEFAULT 0,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_consulthistory` (`historyID`),
  KEY `consultations_ibfk_1` (`client_id`),
  CONSTRAINT `consultations_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`ClientID`) ON DELETE CASCADE,
  CONSTRAINT `fk_consulthistory` FOREIGN KEY (`historyID`) REFERENCES `history` (`historyID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=669 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `consultations`
--

LOCK TABLES `consultations` WRITE;
/*!40000 ALTER TABLE `consultations` DISABLE KEYS */;
INSERT INTO `consultations` VALUES (654,13288,347,'2025-12-10',1,'Medical certificate issued on 2025-12-10','2025-12-10 02:59:50'),(655,13287,348,'2025-12-10',1,'Medical certificate issued on 2025-12-10','2025-12-10 03:01:32'),(656,13289,349,'2025-12-10',1,'Medical certificate issued on 2025-12-10','2025-12-10 03:03:54'),(657,13289,350,'2025-12-10',1,'Medical certificate issued on 2025-12-10','2025-12-10 03:03:54'),(658,13283,351,'2025-12-10',1,'Medical certificate issued on 2025-12-10','2025-12-10 03:05:41'),(659,13292,352,'2025-12-10',1,'Medical certificate issued on 2025-12-10','2025-12-10 03:07:23'),(660,13293,353,'2025-12-10',1,'Medical certificate issued on 2025-12-10','2025-12-10 03:08:13'),(661,13291,354,'2025-12-10',1,'Medical certificate issued on 2025-12-10','2025-12-10 03:09:00'),(662,13290,355,'2025-12-10',1,'Medical certificate issued on 2025-12-10','2025-12-10 03:09:31'),(663,13294,356,'2025-12-10',1,'Medical certificate issued on 2025-12-10','2025-12-10 03:11:11'),(664,13286,357,'2025-12-10',1,'Medical certificate issued on 2025-12-10','2025-12-10 03:11:58'),(665,13280,358,'2025-12-10',1,'Medical certificate issued on 2025-12-10','2025-12-10 03:13:04'),(666,13280,359,'2025-12-10',1,'Medical certificate issued on 2025-12-10','2025-12-10 03:13:04'),(668,13281,361,'2025-12-10',1,'Medical certificate issued on 2025-12-10','2025-12-10 03:16:12');
/*!40000 ALTER TABLE `consultations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `diagnosticresults`
--

DROP TABLE IF EXISTS `diagnosticresults`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `diagnosticresults` (
  `DiagnosticID` int(11) NOT NULL AUTO_INCREMENT,
  `ClientID` int(11) DEFAULT NULL,
  `historyID` int(11) DEFAULT NULL,
  `ExamDate` date DEFAULT NULL,
  `ChestXrayPerformed` tinyint(1) DEFAULT NULL,
  `XrayFindings` text DEFAULT NULL,
  `Impression` text DEFAULT NULL,
  `Discussions` tinyint(1) DEFAULT NULL,
  `DiscussionDetails` text DEFAULT NULL,
  `HomeMedication` tinyint(1) DEFAULT NULL,
  `MedicationDetails` text DEFAULT NULL,
  `HomeInstructions` tinyint(1) DEFAULT NULL,
  `InstructionDetails` text DEFAULT NULL,
  `AbbreviationsUsed` varchar(255) DEFAULT NULL,
  `F1Date` date DEFAULT NULL,
  `MedicalCertIssued` tinyint(1) DEFAULT NULL,
  `ReferredTo` varchar(255) DEFAULT NULL,
  `Recommendation` enum('fit','fit_sports','fit_enroll','fit_work_eval','fit_sports_eval') DEFAULT NULL,
  `PhysicianName` varchar(255) DEFAULT NULL,
  `LicenseNo` varchar(100) DEFAULT NULL,
  `SignatureDate` date DEFAULT NULL,
  `Institution` varchar(255) DEFAULT 'LAGUNA STATE POLYTECHNIC UNIVERSITY, UNIVERSITY CLINIC',
  PRIMARY KEY (`DiagnosticID`),
  KEY `diagnosticresults_ibfk_1` (`ClientID`),
  KEY `fk_diagnosticresults_historyID` (`historyID`),
  CONSTRAINT `diagnosticresults_ibfk_1` FOREIGN KEY (`ClientID`) REFERENCES `clients` (`ClientID`) ON DELETE CASCADE,
  CONSTRAINT `fk_diagnosticresults_historyID` FOREIGN KEY (`historyID`) REFERENCES `history` (`historyID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `diagnosticresults`
--

LOCK TABLES `diagnosticresults` WRITE;
/*!40000 ALTER TABLE `diagnosticresults` DISABLE KEYS */;
/*!40000 ALTER TABLE `diagnosticresults` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `familymedicalhistory`
--

DROP TABLE IF EXISTS `familymedicalhistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `familymedicalhistory` (
  `FamilyMedicalHistoryID` int(11) NOT NULL AUTO_INCREMENT,
  `ClientID` int(11) DEFAULT NULL,
  `historyID` int(11) DEFAULT NULL,
  `Allergy` tinyint(1) DEFAULT 0,
  `AllergyDetails` varchar(255) DEFAULT NULL,
  `Asthma` tinyint(1) DEFAULT 0,
  `AsthmaDetails` varchar(255) DEFAULT NULL,
  `Tuberculosis` tinyint(1) DEFAULT 0,
  `TuberculosisDetails` varchar(255) DEFAULT NULL,
  `Hypertension` tinyint(1) DEFAULT 0,
  `HypertensionDetails` varchar(255) DEFAULT NULL,
  `BloodDisease` tinyint(1) DEFAULT 0,
  `BloodDiseaseDetails` varchar(255) DEFAULT NULL,
  `Stroke` tinyint(1) DEFAULT 0,
  `StrokeDetails` varchar(255) DEFAULT NULL,
  `Diabetes` tinyint(1) DEFAULT 0,
  `DiabetesDetails` varchar(255) DEFAULT NULL,
  `Cancer` tinyint(1) DEFAULT 0,
  `CancerDetails` varchar(255) DEFAULT NULL,
  `LiverDisease` tinyint(1) DEFAULT 0,
  `LiverDiseaseDetails` varchar(255) DEFAULT NULL,
  `KidneyBladder` tinyint(1) DEFAULT 0,
  `KidneyBladderDetails` varchar(255) DEFAULT NULL,
  `BloodDisorder` tinyint(1) DEFAULT 0,
  `BloodDisorderDetails` varchar(255) DEFAULT NULL,
  `Epilepsy` tinyint(1) DEFAULT 0,
  `EpilepsyDetails` varchar(255) DEFAULT NULL,
  `MentalDisorder` tinyint(1) DEFAULT 0,
  `MentalDisorderDetails` varchar(255) DEFAULT NULL,
  `OtherIllness` tinyint(1) DEFAULT 0,
  `OtherIllnessDetails` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`FamilyMedicalHistoryID`),
  UNIQUE KEY `unique_client_history` (`ClientID`,`historyID`),
  KEY `ClientID` (`ClientID`),
  KEY `fk_familymedhis` (`historyID`),
  CONSTRAINT `familymedicalhistory_ibfk_1` FOREIGN KEY (`ClientID`) REFERENCES `clients` (`ClientID`) ON DELETE CASCADE,
  CONSTRAINT `fk_familymedhis` FOREIGN KEY (`historyID`) REFERENCES `history` (`historyID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `familymedicalhistory`
--

LOCK TABLES `familymedicalhistory` WRITE;
/*!40000 ALTER TABLE `familymedicalhistory` DISABLE KEYS */;
INSERT INTO `familymedicalhistory` VALUES (80,13346,365,0,'',0,'',0,'',0,'',0,'',0,'',0,'',0,'',0,'',0,'',0,'',0,'',0,'',1,'Test ');
/*!40000 ALTER TABLE `familymedicalhistory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `femalehealthhistory`
--

DROP TABLE IF EXISTS `femalehealthhistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `femalehealthhistory` (
  `FemaleHealthHistoryID` int(11) NOT NULL AUTO_INCREMENT,
  `ClientID` int(11) DEFAULT NULL,
  `historyID` int(11) DEFAULT NULL,
  `LastPeriod` date DEFAULT NULL,
  `Regularity` enum('regular','irregular') DEFAULT NULL,
  `Duration` varchar(50) DEFAULT NULL,
  `PadsPerDay` int(11) DEFAULT NULL,
  `Dysmenorrhea` enum('yes','no') DEFAULT NULL,
  `DysmenorrheaSeverity` enum('mild','moderate','severe') DEFAULT NULL,
  `LastOBVisit` date DEFAULT NULL,
  `AbnormalBleeding` enum('yes','no') DEFAULT NULL,
  `PreviousPregnancy` enum('yes','no') DEFAULT NULL,
  `PregnancyDetails` varchar(255) DEFAULT NULL,
  `HasChildren` enum('yes','no') DEFAULT NULL,
  `ChildrenCount` int(11) DEFAULT NULL,
  PRIMARY KEY (`FemaleHealthHistoryID`),
  KEY `ClientID` (`ClientID`),
  KEY `fk_fhhistoryID` (`historyID`),
  CONSTRAINT `femalehealthhistory_ibfk_1` FOREIGN KEY (`ClientID`) REFERENCES `clients` (`ClientID`) ON DELETE CASCADE,
  CONSTRAINT `fk_fhhistoryID` FOREIGN KEY (`historyID`) REFERENCES `history` (`historyID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `femalehealthhistory`
--

LOCK TABLES `femalehealthhistory` WRITE;
/*!40000 ALTER TABLE `femalehealthhistory` DISABLE KEYS */;
/*!40000 ALTER TABLE `femalehealthhistory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `history`
--

DROP TABLE IF EXISTS `history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `history` (
  `historyID` int(11) NOT NULL AUTO_INCREMENT,
  `ClientID` int(11) DEFAULT NULL,
  `actionDate` date DEFAULT NULL,
  `actionTime` time NOT NULL,
  PRIMARY KEY (`historyID`),
  KEY `history_ibfk_1` (`ClientID`),
  CONSTRAINT `history_ibfk_1` FOREIGN KEY (`ClientID`) REFERENCES `clients` (`ClientID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=366 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `history`
--

LOCK TABLES `history` WRITE;
/*!40000 ALTER TABLE `history` DISABLE KEYS */;
INSERT INTO `history` VALUES (32,NULL,'2025-05-12','00:00:00'),(33,NULL,'2025-05-12','00:00:00'),(125,NULL,'2025-05-22','04:12:26'),(129,NULL,'2025-05-22','10:19:37'),(133,NULL,'2025-05-22','10:27:47'),(347,13288,'2025-12-10','10:59:50'),(348,13287,'2025-12-10','11:01:32'),(349,13289,'2025-12-10','11:03:54'),(350,13289,'2025-12-10','11:03:54'),(351,13283,'2025-12-10','11:05:41'),(352,13292,'2025-12-10','11:07:23'),(353,13293,'2025-12-10','11:08:13'),(354,13291,'2025-12-10','11:09:00'),(355,13290,'2025-12-10','11:09:31'),(356,13294,'2025-12-10','11:11:11'),(357,13286,'2025-12-10','11:11:58'),(358,13280,'2025-12-10','11:13:04'),(359,13280,'2025-12-10','11:13:04'),(361,13281,'2025-12-10','11:16:12'),(365,13346,'2025-12-16','21:35:58');
/*!40000 ALTER TABLE `history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logbook`
--

DROP TABLE IF EXISTS `logbook`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `logbook` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ClientID` int(11) DEFAULT NULL,
  `log_date` date DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `course` varchar(100) DEFAULT NULL,
  `year` varchar(10) DEFAULT NULL,
  `section` varchar(10) DEFAULT NULL,
  `time_started` time DEFAULT NULL,
  `time_finished` time DEFAULT NULL,
  `medication_treatment` text DEFAULT NULL,
  `illness` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ClientID` (`ClientID`),
  CONSTRAINT `logbook_ibfk_1` FOREIGN KEY (`ClientID`) REFERENCES `clients` (`ClientID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logbook`
--

LOCK TABLES `logbook` WRITE;
/*!40000 ALTER TABLE `logbook` DISABLE KEYS */;
/*!40000 ALTER TABLE `logbook` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `medicalcertificate`
--

DROP TABLE IF EXISTS `medicalcertificate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `medicalcertificate` (
  `MedicalCertID` int(11) NOT NULL AUTO_INCREMENT,
  `ClientID` int(11) NOT NULL,
  `historyID` int(11) DEFAULT NULL,
  `PatientName` varchar(255) DEFAULT NULL,
  `PatientAge` int(11) DEFAULT NULL,
  `ExamDate` date DEFAULT NULL,
  `Findings` text DEFAULT NULL,
  `Impression` text DEFAULT NULL,
  `NoteContent` text DEFAULT NULL,
  `LicenseNo` varchar(100) DEFAULT NULL,
  `DateIssued` date DEFAULT NULL,
  `isDownload` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`MedicalCertID`),
  KEY `ClientID` (`ClientID`),
  KEY `fk_medcerthistory` (`historyID`),
  CONSTRAINT `fk_medcerthistory` FOREIGN KEY (`historyID`) REFERENCES `history` (`historyID`) ON DELETE CASCADE,
  CONSTRAINT `medicalcertificate_ibfk_1` FOREIGN KEY (`ClientID`) REFERENCES `clients` (`ClientID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `medicalcertificate`
--

LOCK TABLES `medicalcertificate` WRITE;
/*!40000 ALTER TABLE `medicalcertificate` DISABLE KEYS */;
/*!40000 ALTER TABLE `medicalcertificate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `medicaldentalhistory`
--

DROP TABLE IF EXISTS `medicaldentalhistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `medicaldentalhistory` (
  `MedicalDentalHistoryID` int(11) NOT NULL AUTO_INCREMENT,
  `ClientID` int(11) DEFAULT NULL,
  `historyID` int(11) DEFAULT NULL,
  `KnownIllness` tinyint(1) DEFAULT 0,
  `KnownIllnessDetails` varchar(255) DEFAULT NULL,
  `Hospitalization` tinyint(1) DEFAULT 0,
  `HospitalizationDetails` varchar(255) DEFAULT NULL,
  `Allergies` tinyint(1) DEFAULT 0,
  `AllergiesDetails` varchar(255) DEFAULT NULL,
  `ChildImmunization` tinyint(1) DEFAULT 0,
  `ChildImmunizationDetails` varchar(255) DEFAULT NULL,
  `PresentImmunizations` tinyint(1) DEFAULT 0,
  `PresentImmunizationsDetails` varchar(255) DEFAULT NULL,
  `CurrentMedicines` tinyint(1) DEFAULT 0,
  `CurrentMedicinesDetails` varchar(255) DEFAULT NULL,
  `DentalProblems` tinyint(1) DEFAULT 0,
  `DentalProblemsDetails` varchar(255) DEFAULT NULL,
  `PrimaryPhysician` tinyint(1) DEFAULT 0,
  `PrimaryPhysicianDetails` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`MedicalDentalHistoryID`),
  UNIQUE KEY `unique_client_history` (`ClientID`,`historyID`),
  KEY `ClientID` (`ClientID`),
  KEY `fk_historyID` (`historyID`),
  CONSTRAINT `fk_historyID` FOREIGN KEY (`historyID`) REFERENCES `history` (`historyID`) ON DELETE CASCADE,
  CONSTRAINT `medicaldentalhistory_ibfk_1` FOREIGN KEY (`ClientID`) REFERENCES `clients` (`ClientID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `medicaldentalhistory`
--

LOCK TABLES `medicaldentalhistory` WRITE;
/*!40000 ALTER TABLE `medicaldentalhistory` DISABLE KEYS */;
INSERT INTO `medicaldentalhistory` VALUES (76,13346,365,0,'',0,'',0,'',0,'',0,'',0,'',0,'',1,'Test ');
/*!40000 ALTER TABLE `medicaldentalhistory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `newpersonnel_form`
--

DROP TABLE IF EXISTS `newpersonnel_form`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `newpersonnel_form` (
  `form_id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `blood_test` tinyint(1) DEFAULT 0,
  `urinalysis` tinyint(1) DEFAULT 0,
  `chest_xray` tinyint(1) DEFAULT 0,
  `drug_test` tinyint(1) DEFAULT 0,
  `psych_test` tinyint(1) DEFAULT 0,
  `neuro_test` tinyint(1) DEFAULT 0,
  `full_name` varchar(255) NOT NULL,
  `agency_address` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `age` int(11) NOT NULL,
  `sex` enum('Male','Female') NOT NULL,
  `civil_status` enum('Single','Married','Divorced','Widowed') NOT NULL,
  `proposed_position` varchar(255) NOT NULL,
  `height` decimal(5,2) NOT NULL COMMENT 'In meters',
  `weight` decimal(5,2) NOT NULL COMMENT 'In kilograms',
  `blood_type` varchar(5) NOT NULL,
  `physician_signature` varchar(255) DEFAULT NULL,
  `physician_name` varchar(255) DEFAULT NULL,
  `physician_agency` varchar(255) DEFAULT NULL,
  `physician_license` varchar(50) DEFAULT NULL,
  `physician_designation` varchar(255) DEFAULT NULL,
  `examination_date` date DEFAULT NULL,
  `fitness_status` enum('FIT','UNFIT') DEFAULT NULL,
  `OtherInfo` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`form_id`),
  KEY `idx_newpersonnel_form_client_id` (`client_id`),
  CONSTRAINT `newpersonnel_form_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`ClientID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `newpersonnel_form`
--

LOCK TABLES `newpersonnel_form` WRITE;
/*!40000 ALTER TABLE `newpersonnel_form` DISABLE KEYS */;
/*!40000 ALTER TABLE `newpersonnel_form` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personalinfo`
--

DROP TABLE IF EXISTS `personalinfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personalinfo` (
  `PersonalInfoID` int(11) NOT NULL AUTO_INCREMENT,
  `ClientID` int(11) DEFAULT NULL,
  `Surname` varchar(100) NOT NULL,
  `GivenName` varchar(100) NOT NULL,
  `MiddleName` varchar(100) DEFAULT NULL,
  `Age` int(11) NOT NULL,
  `Gender` enum('male','female') NOT NULL,
  `DateOfBirth` date NOT NULL,
  `Status` enum('single','married') NOT NULL,
  `Course` varchar(100) DEFAULT NULL,
  `SchoolYearEntered` varchar(100) DEFAULT NULL,
  `CurrentAddress` varchar(255) NOT NULL,
  `ContactNumber` varchar(20) NOT NULL,
  `MothersName` varchar(100) DEFAULT NULL,
  `FathersName` varchar(100) DEFAULT NULL,
  `GuardiansName` varchar(100) DEFAULT NULL,
  `EmergencyContactPerson` varchar(100) DEFAULT NULL,
  `EmergencyContactName` varchar(100) NOT NULL,
  `EmergencyContactRelationship` varchar(100) NOT NULL,
  PRIMARY KEY (`PersonalInfoID`),
  UNIQUE KEY `unique_client` (`ClientID`),
  KEY `ClientID` (`ClientID`),
  CONSTRAINT `personalinfo_ibfk_1` FOREIGN KEY (`ClientID`) REFERENCES `clients` (`ClientID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personalinfo`
--

LOCK TABLES `personalinfo` WRITE;
/*!40000 ALTER TABLE `personalinfo` DISABLE KEYS */;
INSERT INTO `personalinfo` VALUES (103,13346,'Castillo','Jay Michael ','',21,'male','2004-02-18','single','Bachelor of Science in Information Technology','2023','Timugan, Los Banos, Laguan','09395529749','Mom','Dad','Mom','Mom (09211231234','09211231234','Mom');
/*!40000 ALTER TABLE `personalinfo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personalsocialhistory`
--

DROP TABLE IF EXISTS `personalsocialhistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personalsocialhistory` (
  `PersonalSocialHistoryID` int(11) NOT NULL AUTO_INCREMENT,
  `ClientID` int(11) DEFAULT NULL,
  `historyID` int(11) DEFAULT NULL,
  `AlcoholIntake` enum('no','yes','former') DEFAULT 'no',
  `AlcoholDetails` varchar(255) DEFAULT NULL,
  `TobaccoUse` enum('no','yes','former') DEFAULT 'no',
  `TobaccoDetails` varchar(255) DEFAULT NULL,
  `DrugUse` enum('no','yes','former') DEFAULT 'no',
  `DrugDetails` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`PersonalSocialHistoryID`),
  UNIQUE KEY `unique_client_history` (`ClientID`,`historyID`),
  KEY `ClientID` (`ClientID`),
  KEY `fk_pshistoryID` (`historyID`),
  CONSTRAINT `fk_pshistoryID` FOREIGN KEY (`historyID`) REFERENCES `history` (`historyID`) ON DELETE CASCADE,
  CONSTRAINT `personalsocialhistory_ibfk_1` FOREIGN KEY (`ClientID`) REFERENCES `clients` (`ClientID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personalsocialhistory`
--

LOCK TABLES `personalsocialhistory` WRITE;
/*!40000 ALTER TABLE `personalsocialhistory` DISABLE KEYS */;
INSERT INTO `personalsocialhistory` VALUES (61,13346,365,'yes','Test ','yes','Test ','yes','Test ');
/*!40000 ALTER TABLE `personalsocialhistory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `physicalexamination`
--

DROP TABLE IF EXISTS `physicalexamination`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `physicalexamination` (
  `PhysicalExaminationID` int(11) NOT NULL AUTO_INCREMENT,
  `ClientID` int(11) DEFAULT NULL,
  `historyID` int(11) DEFAULT NULL,
  `Height` decimal(5,2) DEFAULT NULL,
  `Weight` decimal(5,2) DEFAULT NULL,
  `BMI` decimal(5,2) DEFAULT NULL,
  `BP` varchar(20) DEFAULT NULL,
  `HR` int(11) DEFAULT NULL,
  `RR` int(11) DEFAULT NULL,
  `Temp` decimal(4,2) DEFAULT NULL,
  `GenAppearanceAndSkinNormal` tinyint(1) DEFAULT NULL,
  `GenAppearanceAndSkinFindings` varchar(255) DEFAULT NULL,
  `HeadAndNeckNormal` tinyint(1) DEFAULT NULL,
  `HeadAndNeckFindings` varchar(255) DEFAULT NULL,
  `ChestAndBackNormal` tinyint(1) DEFAULT NULL,
  `ChestAndBackFindings` varchar(255) DEFAULT NULL,
  `AbdomenNormal` tinyint(1) DEFAULT NULL,
  `AbdomenFindings` varchar(255) DEFAULT NULL,
  `ExtremitiesNormal` tinyint(1) DEFAULT NULL,
  `ExtremitiesFindings` varchar(255) DEFAULT NULL,
  `OthersNormal` tinyint(1) DEFAULT NULL,
  `OthersFindings` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`PhysicalExaminationID`),
  KEY `ClientID` (`ClientID`),
  KEY `fk_physicalexam_historyID` (`historyID`),
  CONSTRAINT `fk_physicalexam_historyID` FOREIGN KEY (`historyID`) REFERENCES `history` (`historyID`) ON DELETE CASCADE,
  CONSTRAINT `physicalexamination_ibfk_1` FOREIGN KEY (`ClientID`) REFERENCES `clients` (`ClientID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `physicalexamination`
--

LOCK TABLES `physicalexamination` WRITE;
/*!40000 ALTER TABLE `physicalexamination` DISABLE KEYS */;
/*!40000 ALTER TABLE `physicalexamination` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prescriptions`
--

DROP TABLE IF EXISTS `prescriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prescriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ClientID` int(11) NOT NULL,
  `historyID` int(11) NOT NULL,
  `patient_name` varchar(255) DEFAULT NULL,
  `age` varchar(10) DEFAULT NULL,
  `impression` varchar(255) DEFAULT NULL,
  `physician` varchar(255) DEFAULT NULL,
  `license_no` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `date_created` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_client` (`ClientID`),
  KEY `fk_history` (`historyID`),
  CONSTRAINT `fk_client` FOREIGN KEY (`ClientID`) REFERENCES `clients` (`ClientID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_history` FOREIGN KEY (`historyID`) REFERENCES `history` (`historyID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prescriptions`
--

LOCK TABLES `prescriptions` WRITE;
/*!40000 ALTER TABLE `prescriptions` DISABLE KEYS */;
/*!40000 ALTER TABLE `prescriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `progresstable`
--

DROP TABLE IF EXISTS `progresstable`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `progresstable` (
  `ProgressID` int(11) NOT NULL AUTO_INCREMENT,
  `ClientID` int(11) NOT NULL,
  `DateSubmitted` date DEFAULT NULL,
  `DateCompleted` date DEFAULT NULL,
  `Status` enum('Inprogress','Completed','Cancelled') DEFAULT 'Inprogress',
  PRIMARY KEY (`ProgressID`),
  KEY `ClientID` (`ClientID`),
  CONSTRAINT `progresstable_ibfk_1` FOREIGN KEY (`ClientID`) REFERENCES `clients` (`ClientID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `progresstable`
--

LOCK TABLES `progresstable` WRITE;
/*!40000 ALTER TABLE `progresstable` DISABLE KEYS */;
/*!40000 ALTER TABLE `progresstable` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(128) NOT NULL,
  `data` text NOT NULL,
  `access` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `access` (`access`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('2ofqmjmbk8fi6anefmp2e6vah4','',1744068698);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `todolist`
--

DROP TABLE IF EXISTS `todolist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `todolist` (
  `todolistid` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `event` text NOT NULL,
  `location` text DEFAULT NULL,
  `noted` text DEFAULT NULL,
  PRIMARY KEY (`todolistid`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `todolist`
--

LOCK TABLES `todolist` WRITE;
/*!40000 ALTER TABLE `todolist` DISABLE KEYS */;
INSERT INTO `todolist` VALUES (26,'2025-04-30','09:00:00','Final Presentation','AVR','Good Luck!!!'),(27,'2025-05-16','01:00:00','Presentation','Lab 1/Lab 2','Good Luck'),(28,'2025-05-19','20:00:00','Meeting','Lab 1/Lab 2','');
/*!40000 ALTER TABLE `todolist` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-29 18:49:33
