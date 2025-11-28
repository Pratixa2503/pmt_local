-- MySQL dump 10.13  Distrib 8.0.43, for Linux (x86_64)
--
-- Host: localhost    Database: pmt
-- ------------------------------------------------------
-- Server version	8.0.43-0ubuntu0.24.04.2

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
-- Table structure for table `activity_log`
--

DROP TABLE IF EXISTS `activity_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `log_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint unsigned DEFAULT NULL,
  `causer_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` bigint unsigned DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `batch_uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject_type`,`subject_id`),
  KEY `causer` (`causer_type`,`causer_id`),
  KEY `activity_log_log_name_index` (`log_name`)
) ENGINE=InnoDB AUTO_INCREMENT=242 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_log`
--

LOCK TABLES `activity_log` WRITE;
/*!40000 ALTER TABLE `activity_log` DISABLE KEYS */;
INSERT INTO `activity_log` VALUES (1,'user','created','App\\Models\\User','created',32,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"alice.smith1@example.com\", \"status\": 1, \"password\": \"$2y$12$crBdYeU/Uw0uIc2aocztnuckp2DsafNfuQxvPg327RTtSeC/AoZqe\", \"last_name\": \"Smith\", \"contact_no\": \"9123456780\", \"created_by\": null, \"first_name\": \"Alice\", \"updated_by\": null, \"company_name\": null, \"project_manager\": null, \"is_password_update\": 0}}',NULL,'2025-10-08 07:09:44','2025-10-08 07:09:44'),(2,'user','created','App\\Models\\User','created',33,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"emma.johnson1@example.com\", \"status\": 1, \"password\": \"$2y$12$10VAs6bWbyrQW4iOLAp5buCtpU.ItLY.0Zpj6GmmYKjoYQIBhD3qi\", \"last_name\": \"Johnson\", \"contact_no\": \"9876543210\", \"created_by\": null, \"first_name\": \"Emma\", \"updated_by\": null, \"company_name\": null, \"project_manager\": null, \"is_password_update\": 0}}',NULL,'2025-10-08 07:10:32','2025-10-08 07:10:32'),(3,'user','created','App\\Models\\User','created',34,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"jayakumari.p@springbord.com\", \"status\": 1, \"password\": \"$2y$12$E/AkG84rtsh65pmyKVgwpeTQm2tVf9qojZn4Ql9cg48BohNwLo4Hm\", \"last_name\": \"kumari\", \"contact_no\": \"8959483890\", \"created_by\": null, \"first_name\": \"Jaya\", \"updated_by\": null, \"company_name\": null, \"project_manager\": null, \"is_password_update\": 0}}',NULL,'2025-10-08 07:13:06','2025-10-08 07:13:06'),(4,'user','created','App\\Models\\User','created',35,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"abstracotr1@gmail.com\", \"status\": 1, \"password\": \"$2y$12$b8vv9Qwf0AzyXz273ln78eNVg0WUrAf.VKrt174IfmmNUK7ZzhjKO\", \"last_name\": \"k\", \"contact_no\": \"9876543210\", \"created_by\": null, \"first_name\": \"abstracotr1\", \"updated_by\": null, \"company_name\": null, \"project_manager\": 34, \"is_password_update\": 0}}',NULL,'2025-10-08 07:14:19','2025-10-08 07:14:19'),(5,'user','created','App\\Models\\User','created',36,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"abstracotr2@gmail.com\", \"status\": 1, \"password\": \"$2y$12$VhqoMyRQYZkrIoshL3LThOzj6fsoUD2sWoCcwTFSiAybCmP577Sc.\", \"last_name\": \"k\", \"contact_no\": \"7708234256\", \"created_by\": null, \"first_name\": \"abstracotr2\", \"updated_by\": null, \"company_name\": null, \"project_manager\": 34, \"is_password_update\": 0}}',NULL,'2025-10-08 07:14:51','2025-10-08 07:14:51'),(6,'user','created','App\\Models\\User','created',37,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"abstracotr3@gmail.com\", \"status\": 1, \"password\": \"$2y$12$qRklYsddQVWMDtBHH0NHsueH5AdOKHL7jpFCBMHk7Q1qZ4KaFqOOa\", \"last_name\": \"l\", \"contact_no\": \"8959483892\", \"created_by\": null, \"first_name\": \"abstracotr3\", \"updated_by\": null, \"company_name\": null, \"project_manager\": 34, \"is_password_update\": 0}}',NULL,'2025-10-08 07:15:15','2025-10-08 07:15:15'),(7,'user','created','App\\Models\\User','created',38,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"review1@gmail.com\", \"status\": 1, \"password\": \"$2y$12$lb5kFzlzYRoxEI8d3l/cAOUI.p3jmBr4CeVSuXG0RMAE1LpRjAJyi\", \"last_name\": \"A\", \"contact_no\": \"8959483891\", \"created_by\": null, \"first_name\": \"review1\", \"updated_by\": null, \"company_name\": null, \"project_manager\": 34, \"is_password_update\": 0}}',NULL,'2025-10-08 07:15:50','2025-10-08 07:15:50'),(8,'user','created','App\\Models\\User','created',39,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"review2@gmail.com\", \"status\": 1, \"password\": \"$2y$12$Tn/KAMv/0540FW29.ytNc.VEysH6xnEtViixyDfB1SQavpLoCOGtu\", \"last_name\": \"G\", \"contact_no\": \"8959483893\", \"created_by\": null, \"first_name\": \"review2\", \"updated_by\": null, \"company_name\": null, \"project_manager\": 34, \"is_password_update\": 0}}',NULL,'2025-10-08 07:16:12','2025-10-08 07:16:12'),(9,'user','created','App\\Models\\User','created',40,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"review3@gmail.com\", \"status\": 1, \"password\": \"$2y$12$q4oec59xVMHll/BQyRZFQefejgKE37mQQhFUS0rnpLq0O3yfIeZy.\", \"last_name\": \"H\", \"contact_no\": \"8959483896\", \"created_by\": null, \"first_name\": \"review3\", \"updated_by\": null, \"company_name\": null, \"project_manager\": 34, \"is_password_update\": 0}}',NULL,'2025-10-08 07:16:32','2025-10-08 07:16:32'),(10,'user','created','App\\Models\\User','created',41,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"sense1@gmail.com\", \"status\": 1, \"password\": \"$2y$12$cRadESWNijpISoQXy/KDYe7IYTOYU1whb/Im1ixphj4hx2N114uS6\", \"last_name\": \"F\", \"contact_no\": \"8955483890\", \"created_by\": null, \"first_name\": \"sense1\", \"updated_by\": null, \"company_name\": null, \"project_manager\": 34, \"is_password_update\": 0}}',NULL,'2025-10-08 07:17:05','2025-10-08 07:17:05'),(11,'user','created','App\\Models\\User','created',42,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"sense2@gmail.com\", \"status\": 1, \"password\": \"$2y$12$9pnFZDvS1iuNXa1P8rG7nOFvm3ET6HR.WsDP9./mVlNuEbYt/eJTO\", \"last_name\": \"J\", \"contact_no\": \"8959433890\", \"created_by\": null, \"first_name\": \"sense2\", \"updated_by\": null, \"company_name\": null, \"project_manager\": 34, \"is_password_update\": 0}}',NULL,'2025-10-08 07:17:26','2025-10-08 07:17:26'),(12,'user','created','App\\Models\\User','created',43,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"sense3@gmail.com\", \"status\": 1, \"password\": \"$2y$12$DHf8u5daUlVmEJy6s05wL.NZgOb.sG/YFnZDphsVVhETcm36AN5e.\", \"last_name\": \"K\", \"contact_no\": \"8989483890\", \"created_by\": null, \"first_name\": \"sense3\", \"updated_by\": null, \"company_name\": null, \"project_manager\": 34, \"is_password_update\": 0}}',NULL,'2025-10-08 07:17:47','2025-10-08 07:17:47'),(13,'user','updated','App\\Models\\User','updated',35,'App\\Models\\User',35,'{\"old\": {\"password\": \"$2y$12$b8vv9Qwf0AzyXz273ln78eNVg0WUrAf.VKrt174IfmmNUK7ZzhjKO\", \"is_password_update\": 0}, \"attributes\": {\"password\": \"$2y$12$lo.9GMPYbnipaScYTfbp9u0c3De9OKP8husJiVa1eJwtL6eG.wr5a\", \"is_password_update\": 1}}',NULL,'2025-10-08 07:37:09','2025-10-08 07:37:09'),(14,'user','updated','App\\Models\\User','updated',38,'App\\Models\\User',38,'{\"old\": {\"password\": \"$2y$12$lb5kFzlzYRoxEI8d3l/cAOUI.p3jmBr4CeVSuXG0RMAE1LpRjAJyi\", \"is_password_update\": 0}, \"attributes\": {\"password\": \"$2y$12$Bzdqyj1EbdLaSfOukjDrguhfgOtGFW7tQ4/7808IZdzxcxReQWnmS\", \"is_password_update\": 1}}',NULL,'2025-10-08 07:41:39','2025-10-08 07:41:39'),(15,'user','updated','App\\Models\\User','updated',41,'App\\Models\\User',41,'{\"old\": {\"password\": \"$2y$12$cRadESWNijpISoQXy/KDYe7IYTOYU1whb/Im1ixphj4hx2N114uS6\", \"is_password_update\": 0}, \"attributes\": {\"password\": \"$2y$12$vZBoPcIN8y2TpDvZWi88yusN6h99YhfMYyt4esMoTgMHqyw97cJ66\", \"is_password_update\": 1}}',NULL,'2025-10-08 07:47:47','2025-10-08 07:47:47'),(16,'user','updated','App\\Models\\User','updated',34,'App\\Models\\User',34,'{\"old\": {\"password\": \"$2y$12$E/AkG84rtsh65pmyKVgwpeTQm2tVf9qojZn4Ql9cg48BohNwLo4Hm\", \"is_password_update\": 0}, \"attributes\": {\"password\": \"$2y$12$Y/qvK0Tay7G8isDphN7duuPbsvna9VQlbGHcOB/iVljAtricfYJXm\", \"is_password_update\": 1}}',NULL,'2025-10-08 08:01:09','2025-10-08 08:01:09'),(17,'user','created','App\\Models\\User','created',44,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"finance1@gmail.com\", \"status\": 1, \"password\": \"$2y$12$GIc5k7NqFTZgt7sA.D8XseYZjQS8iNS5/4savaq42vn/zPd4FR8mG\", \"last_name\": \"1\", \"contact_no\": \"7708234253\", \"created_by\": null, \"first_name\": \"finance\", \"updated_by\": null, \"company_name\": null, \"project_manager\": 34, \"is_password_update\": 0}}',NULL,'2025-10-08 08:07:32','2025-10-08 08:07:32'),(18,'user','updated','App\\Models\\User','updated',44,'App\\Models\\User',44,'{\"old\": {\"password\": \"$2y$12$GIc5k7NqFTZgt7sA.D8XseYZjQS8iNS5/4savaq42vn/zPd4FR8mG\", \"is_password_update\": 0}, \"attributes\": {\"password\": \"$2y$12$mO0L84YavVC/Git.76ui2ePYCXhOQOZz4YHryS2d4Z/5DEDCq2jBe\", \"is_password_update\": 1}}',NULL,'2025-10-08 08:08:08','2025-10-08 08:08:08'),(19,'user','updated','App\\Models\\User','updated',37,'App\\Models\\User',11,'{\"old\": {\"email\": \"abstracotr3@gmail.com\", \"first_name\": \"abstracotr3\"}, \"attributes\": {\"email\": \"abstractor3@gmail.com\", \"first_name\": \"abstractor3\"}}',NULL,'2025-10-08 08:28:43','2025-10-08 08:28:43'),(20,'user','updated','App\\Models\\User','updated',36,'App\\Models\\User',11,'{\"old\": {\"email\": \"abstracotr2@gmail.com\", \"first_name\": \"abstracotr2\"}, \"attributes\": {\"email\": \"abstractor2@gmail.com\", \"first_name\": \"abstractor2\"}}',NULL,'2025-10-08 08:29:05','2025-10-08 08:29:05'),(21,'user','updated','App\\Models\\User','updated',35,'App\\Models\\User',11,'{\"old\": {\"email\": \"abstracotr1@gmail.com\", \"first_name\": \"abstracotr1\"}, \"attributes\": {\"email\": \"abstractor1@gmail.com\", \"first_name\": \"abstractor1\"}}',NULL,'2025-10-08 08:29:23','2025-10-08 08:29:23'),(22,'user','created','App\\Models\\User','created',45,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"john@example.com\", \"status\": 1, \"password\": \"$2y$12$hfoooNEx6l0LP6Zc4Z3CNe59WXZq94gDDTpVCoAv7OZGInSVb6Oq6\", \"last_name\": \"F\", \"contact_no\": \"7708234257\", \"created_by\": null, \"first_name\": \"John\", \"updated_by\": null, \"company_name\": null, \"project_manager\": null, \"is_password_update\": 0}}',NULL,'2025-10-08 09:27:06','2025-10-08 09:27:06'),(23,'user','created','App\\Models\\User','created',46,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"nijhanth@springbord.com\", \"status\": 1, \"password\": \"$2y$12$pyj7GJCmCTo/EoFtzx.Taeltl.42kq8NGwAGLJVbrq5sg1l09XJjC\", \"last_name\": \"G J\", \"contact_no\": \"7708234253\", \"created_by\": null, \"first_name\": \"Nijhanthanraj\", \"updated_by\": null, \"company_name\": null, \"project_manager\": null, \"is_password_update\": 0}}',NULL,'2025-10-08 09:30:58','2025-10-08 09:30:58'),(24,'user','updated','App\\Models\\User','updated',35,'App\\Models\\User',11,'{\"old\": {\"project_manager\": 34}, \"attributes\": {\"project_manager\": 46}}',NULL,'2025-10-08 09:33:07','2025-10-08 09:33:07'),(25,'user','updated','App\\Models\\User','updated',36,'App\\Models\\User',11,'{\"old\": {\"project_manager\": 34}, \"attributes\": {\"project_manager\": 46}}',NULL,'2025-10-08 09:33:50','2025-10-08 09:33:50'),(26,'user','updated','App\\Models\\User','updated',37,'App\\Models\\User',11,'{\"old\": {\"project_manager\": 34}, \"attributes\": {\"project_manager\": 46}}',NULL,'2025-10-08 09:34:11','2025-10-08 09:34:11'),(27,'user','updated','App\\Models\\User','updated',35,'App\\Models\\User',11,'{\"old\": {\"project_manager\": 46}, \"attributes\": {\"project_manager\": 34}}',NULL,'2025-10-08 09:34:28','2025-10-08 09:34:28'),(28,'user','updated','App\\Models\\User','updated',36,'App\\Models\\User',11,'{\"old\": {\"project_manager\": 46}, \"attributes\": {\"project_manager\": 34}}',NULL,'2025-10-08 09:34:37','2025-10-08 09:34:37'),(29,'user','updated','App\\Models\\User','updated',37,'App\\Models\\User',11,'{\"old\": {\"project_manager\": 46}, \"attributes\": {\"project_manager\": 34}}',NULL,'2025-10-08 09:34:43','2025-10-08 09:34:43'),(30,'user','created','App\\Models\\User','created',47,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"abstractor4@gmail.com\", \"status\": 1, \"password\": \"$2y$12$tDoLn7pGpuSe01fuqwL1qOIxD5fQVzTeKbADmQCVk/2BXGO9Bbpbq\", \"last_name\": \"A\", \"contact_no\": \"7708234253\", \"created_by\": null, \"first_name\": \"abstractor4\", \"updated_by\": null, \"company_name\": null, \"project_manager\": 46, \"is_password_update\": 0}}',NULL,'2025-10-08 09:36:18','2025-10-08 09:36:18'),(31,'user','created','App\\Models\\User','created',48,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"abstractor5@gmail.com\", \"status\": 1, \"password\": \"$2y$12$.G2cOqSfizmPMIBp6448be.UILziGJMK7qhyt3a9pp6RsxlKIjquO\", \"last_name\": \"B\", \"contact_no\": \"7708234253\", \"created_by\": null, \"first_name\": \"abstractor5\", \"updated_by\": null, \"company_name\": null, \"project_manager\": 46, \"is_password_update\": 0}}',NULL,'2025-10-08 09:36:43','2025-10-08 09:36:43'),(32,'user','created','App\\Models\\User','created',49,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"abstractor6@gmail.com\", \"status\": 1, \"password\": \"$2y$12$8zIJd8zO0xFIsIr6e6711eH5bce0.OqmmGbSUw/AEiGaHU672lP9i\", \"last_name\": \"C\", \"contact_no\": \"7708234253\", \"created_by\": null, \"first_name\": \"abstractor6\", \"updated_by\": null, \"company_name\": null, \"project_manager\": 46, \"is_password_update\": 0}}',NULL,'2025-10-08 09:37:01','2025-10-08 09:37:01'),(33,'user','created','App\\Models\\User','created',50,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"review4@gmail.com\", \"status\": 1, \"password\": \"$2y$12$58W2VEgVxl9AlJIMCdS.juFpRAZmPQ3hIykiwK.3q8.zfuSGDPPiO\", \"last_name\": \"A\", \"contact_no\": \"7708234253\", \"created_by\": null, \"first_name\": \"review4\", \"updated_by\": null, \"company_name\": null, \"project_manager\": 46, \"is_password_update\": 0}}',NULL,'2025-10-08 09:37:29','2025-10-08 09:37:29'),(34,'user','created','App\\Models\\User','created',51,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"review5@gmail.com\", \"status\": 1, \"password\": \"$2y$12$wNNp3kEKPqaiBeK.9a8mD.ZIsNgbcWPhfW2B1J.I.agMi1g92uGg.\", \"last_name\": \"B\", \"contact_no\": \"7708234253\", \"created_by\": null, \"first_name\": \"review5\", \"updated_by\": null, \"company_name\": null, \"project_manager\": 46, \"is_password_update\": 0}}',NULL,'2025-10-08 09:38:52','2025-10-08 09:38:52'),(35,'user','created','App\\Models\\User','created',52,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"review6@gmail.com\", \"status\": 1, \"password\": \"$2y$12$Iq28oXeZjA4oewvjGXSaAu79LAMe9UOvQXUSfM4OgWwzDaggEtate\", \"last_name\": \"C\", \"contact_no\": \"7708234253\", \"created_by\": null, \"first_name\": \"review6\", \"updated_by\": null, \"company_name\": null, \"project_manager\": 46, \"is_password_update\": 0}}',NULL,'2025-10-08 09:39:12','2025-10-08 09:39:12'),(36,'user','created','App\\Models\\User','created',53,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"sense4@gmail.com\", \"status\": 1, \"password\": \"$2y$12$sLoh0aXVamIBUmeOaqZaUehbCa2totkK5fvJeVImLZ8kkbJdjCykq\", \"last_name\": \"A\", \"contact_no\": \"7708234253\", \"created_by\": null, \"first_name\": \"sense4\", \"updated_by\": null, \"company_name\": null, \"project_manager\": 46, \"is_password_update\": 0}}',NULL,'2025-10-08 09:39:50','2025-10-08 09:39:50'),(37,'user','created','App\\Models\\User','created',54,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"sense6@gmail.com\", \"status\": 1, \"password\": \"$2y$12$1KDosqmHVNfjZ/bDVBWGROBIMsB8F32/M2ruomf2VM9WXJ1SiZ5ti\", \"last_name\": \"C\", \"contact_no\": \"7708234253\", \"created_by\": null, \"first_name\": \"sense6\", \"updated_by\": null, \"company_name\": null, \"project_manager\": 46, \"is_password_update\": 0}}',NULL,'2025-10-08 09:40:15','2025-10-08 09:40:15'),(38,'user','created','App\\Models\\User','created',55,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"sense5@gmail.com\", \"status\": 1, \"password\": \"$2y$12$stjaz40zJWoEdk/ifM8xxOE8k/Bw3/gYZDA8Hg8HA2gNZSkXEbUxS\", \"last_name\": \"B\", \"contact_no\": \"7708234253\", \"created_by\": null, \"first_name\": \"sense5\", \"updated_by\": null, \"company_name\": null, \"project_manager\": 46, \"is_password_update\": 0}}',NULL,'2025-10-08 09:40:45','2025-10-08 09:40:45'),(39,'user','updated','App\\Models\\User','updated',47,'App\\Models\\User',47,'{\"old\": {\"password\": \"$2y$12$tDoLn7pGpuSe01fuqwL1qOIxD5fQVzTeKbADmQCVk/2BXGO9Bbpbq\", \"is_password_update\": 0}, \"attributes\": {\"password\": \"$2y$12$P3yvaMXUnM3.qnTiXFdPx.Ez/BOxKhN4U5qmdg86y53nSppopDEje\", \"is_password_update\": 1}}',NULL,'2025-10-08 10:11:14','2025-10-08 10:11:14'),(40,'user','updated','App\\Models\\User','updated',35,'App\\Models\\User',11,'{\"old\": {\"email\": \"abstractor1@gmail.com\"}, \"attributes\": {\"email\": \"abstractor1@springbord.com\"}}',NULL,'2025-10-08 11:50:05','2025-10-08 11:50:05'),(41,'user','updated','App\\Models\\User','updated',36,'App\\Models\\User',11,'{\"old\": {\"email\": \"abstractor2@gmail.com\"}, \"attributes\": {\"email\": \"abstractor2@springbord.com\"}}',NULL,'2025-10-08 11:50:24','2025-10-08 11:50:24'),(42,'user','updated','App\\Models\\User','updated',37,'App\\Models\\User',11,'{\"old\": {\"email\": \"abstractor3@gmail.com\"}, \"attributes\": {\"email\": \"abstractor3@springbord.com\"}}',NULL,'2025-10-08 11:50:38','2025-10-08 11:50:38'),(43,'user','updated','App\\Models\\User','updated',38,'App\\Models\\User',11,'{\"old\": {\"email\": \"review1@gmail.com\"}, \"attributes\": {\"email\": \"review1@springbord.com\"}}',NULL,'2025-10-08 11:50:50','2025-10-08 11:50:50'),(44,'user','updated','App\\Models\\User','updated',39,'App\\Models\\User',11,'{\"old\": {\"email\": \"review2@gmail.com\"}, \"attributes\": {\"email\": \"review2@springbord.com\"}}',NULL,'2025-10-08 11:51:00','2025-10-08 11:51:00'),(45,'user','updated','App\\Models\\User','updated',40,'App\\Models\\User',11,'{\"old\": {\"email\": \"review3@gmail.com\"}, \"attributes\": {\"email\": \"review3@springbord.com\"}}',NULL,'2025-10-08 11:51:15','2025-10-08 11:51:15'),(46,'user','updated','App\\Models\\User','updated',41,'App\\Models\\User',11,'{\"old\": {\"email\": \"sense1@gmail.com\"}, \"attributes\": {\"email\": \"sense1@springbord.com\"}}',NULL,'2025-10-08 11:51:25','2025-10-08 11:51:25'),(47,'user','updated','App\\Models\\User','updated',42,'App\\Models\\User',11,'{\"old\": {\"email\": \"sense2@gmail.com\"}, \"attributes\": {\"email\": \"sense2@springbord.com\"}}',NULL,'2025-10-08 11:51:36','2025-10-08 11:51:36'),(48,'user','updated','App\\Models\\User','updated',43,'App\\Models\\User',11,'{\"old\": {\"email\": \"sense3@gmail.com\"}, \"attributes\": {\"email\": \"sense3@springbord.com\"}}',NULL,'2025-10-08 11:51:51','2025-10-08 11:51:51'),(49,'user','updated','App\\Models\\User','updated',47,'App\\Models\\User',11,'{\"old\": {\"email\": \"abstractor4@gmail.com\"}, \"attributes\": {\"email\": \"abstractor4@springbord.com\"}}',NULL,'2025-10-08 13:11:21','2025-10-08 13:11:21'),(50,'user','updated','App\\Models\\User','updated',55,'App\\Models\\User',11,'{\"old\": {\"email\": \"sense5@gmail.com\"}, \"attributes\": {\"email\": \"sense5@springbord.com\"}}',NULL,'2025-10-08 13:11:28','2025-10-08 13:11:28'),(51,'user','updated','App\\Models\\User','updated',54,'App\\Models\\User',11,'{\"old\": {\"email\": \"sense6@gmail.com\"}, \"attributes\": {\"email\": \"sense6@springbord.com\"}}',NULL,'2025-10-08 13:11:35','2025-10-08 13:11:35'),(52,'user','updated','App\\Models\\User','updated',53,'App\\Models\\User',11,'{\"old\": {\"email\": \"sense4@gmail.com\"}, \"attributes\": {\"email\": \"sense4@springbord.com\"}}',NULL,'2025-10-08 13:11:44','2025-10-08 13:11:44'),(53,'user','updated','App\\Models\\User','updated',52,'App\\Models\\User',11,'{\"old\": {\"email\": \"review6@gmail.com\"}, \"attributes\": {\"email\": \"review6@springbord.com\"}}',NULL,'2025-10-08 13:11:51','2025-10-08 13:11:51'),(54,'user','updated','App\\Models\\User','updated',51,'App\\Models\\User',11,'{\"old\": {\"email\": \"review5@gmail.com\"}, \"attributes\": {\"email\": \"review5@springbord.com\"}}',NULL,'2025-10-08 13:12:01','2025-10-08 13:12:01'),(55,'user','updated','App\\Models\\User','updated',50,'App\\Models\\User',11,'{\"old\": {\"email\": \"review4@gmail.com\"}, \"attributes\": {\"email\": \"review4@springbord.com\"}}',NULL,'2025-10-08 13:12:09','2025-10-08 13:12:09'),(56,'user','updated','App\\Models\\User','updated',48,'App\\Models\\User',11,'{\"old\": {\"email\": \"abstractor5@gmail.com\"}, \"attributes\": {\"email\": \"abstractor5@springbord.com\"}}',NULL,'2025-10-08 13:12:17','2025-10-08 13:12:17'),(57,'user','updated','App\\Models\\User','updated',49,'App\\Models\\User',11,'{\"old\": {\"email\": \"abstractor6@gmail.com\"}, \"attributes\": {\"email\": \"abstractor6@springbord.com\"}}',NULL,'2025-10-08 13:12:24','2025-10-08 13:12:24'),(58,'user','created','App\\Models\\User','created',56,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"david.wilson@springbord.com\", \"status\": 1, \"password\": \"$2y$12$3TQn./V6FEIvztnfbbktmO7SrhT6IFsXyGyVyyhO2Uvg/YyKNvVoi\", \"last_name\": \"Wilson\", \"contact_no\": \"9123456780\", \"created_by\": null, \"first_name\": \"David\", \"updated_by\": null, \"company_name\": null, \"project_manager\": null, \"is_password_update\": 0}}',NULL,'2025-10-09 04:26:49','2025-10-09 04:26:49'),(59,'user','updated','App\\Models\\User','updated',46,'App\\Models\\User',46,'{\"old\": {\"password\": \"$2y$12$pyj7GJCmCTo/EoFtzx.Taeltl.42kq8NGwAGLJVbrq5sg1l09XJjC\", \"is_password_update\": 0}, \"attributes\": {\"password\": \"$2y$12$Y51txIOnDd7u88aTM26Zk.Tzg.A7FdlyEPe332j1snyAGKQAab21a\", \"is_password_update\": 1}}',NULL,'2025-10-09 04:30:46','2025-10-09 04:30:46'),(60,'user','created','App\\Models\\User','created',57,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"subin.rabin@springbord.com\", \"status\": 1, \"password\": \"$2y$12$fciHYfOyWu/MT25KkfNYs.vVIOvfCKwRp/Bnl0mFFVZANTnrqVcCe\", \"last_name\": \"rabin\", \"contact_no\": \"8959483890\", \"created_by\": null, \"first_name\": \"subin\", \"updated_by\": null, \"company_name\": null, \"project_manager\": null, \"is_password_update\": 0}}',NULL,'2025-10-09 04:36:58','2025-10-09 04:36:58'),(61,'user','created','App\\Models\\User','created',58,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"abstractor7@springbord.com\", \"status\": 1, \"password\": \"$2y$12$aI030/1/b5UQ6kj8GaHmnetbpu2bYedq3ImCwcjQ5nGOJufRQTIVu\", \"last_name\": \"A\", \"contact_no\": \"8959483890\", \"created_by\": null, \"first_name\": \"abstractor7\", \"updated_by\": null, \"company_name\": null, \"project_manager\": 57, \"is_password_update\": 0}}',NULL,'2025-10-09 04:37:38','2025-10-09 04:37:38'),(62,'user','created','App\\Models\\User','created',59,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"review7@springbord.com\", \"status\": 1, \"password\": \"$2y$12$1RZ6Vi1rOytJgxIOkyGr0.1a8F3Qu2ZU47sRMe1m4L2SpPEuakOvS\", \"last_name\": \"B\", \"contact_no\": \"8959483890\", \"created_by\": null, \"first_name\": \"review7\", \"updated_by\": null, \"company_name\": null, \"project_manager\": 57, \"is_password_update\": 0}}',NULL,'2025-10-09 04:38:04','2025-10-09 04:38:04'),(63,'user','created','App\\Models\\User','created',60,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"sense7@springbord.com\", \"status\": 1, \"password\": \"$2y$12$8et6XwgDMY7W0jEu95S31u/4VTGSrgr6PfBZEIxXjVkAxDnoTlyfu\", \"last_name\": \"C\", \"contact_no\": \"8959483890\", \"created_by\": null, \"first_name\": \"sense7\", \"updated_by\": null, \"company_name\": null, \"project_manager\": 57, \"is_password_update\": 0}}',NULL,'2025-10-09 04:38:28','2025-10-09 04:38:28'),(64,'user','created','App\\Models\\User','created',61,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"finance2@springbord.com\", \"status\": 1, \"password\": \"$2y$12$.chBTmC5TKj32ZmNDEBFaeWu1qbRViPC9XtzlWgcSXiFFLeheAz5u\", \"last_name\": \"B\", \"contact_no\": \"8959483890\", \"created_by\": null, \"first_name\": \"Finance2\", \"updated_by\": null, \"company_name\": null, \"project_manager\": 57, \"is_password_update\": 0}}',NULL,'2025-10-09 05:01:10','2025-10-09 05:01:10'),(65,'user','updated','App\\Models\\User','updated',50,'App\\Models\\User',50,'{\"old\": {\"password\": \"$2y$12$58W2VEgVxl9AlJIMCdS.juFpRAZmPQ3hIykiwK.3q8.zfuSGDPPiO\", \"is_password_update\": 0}, \"attributes\": {\"password\": \"$2y$12$S9U6WyKF1x.8dWSq82xkAO.AN5mAjj9vLtYNYNxB5OasgEGjDHQ7a\", \"is_password_update\": 1}}',NULL,'2025-10-09 05:09:00','2025-10-09 05:09:00'),(66,'user','updated','App\\Models\\User','updated',53,'App\\Models\\User',53,'{\"old\": {\"password\": \"$2y$12$sLoh0aXVamIBUmeOaqZaUehbCa2totkK5fvJeVImLZ8kkbJdjCykq\", \"is_password_update\": 0}, \"attributes\": {\"password\": \"$2y$12$VJA3su0XCvGW1k.hRLjQKucneNXvlKZ..vEOuWzmF0pieXCFkUNOW\", \"is_password_update\": 1}}',NULL,'2025-10-09 05:09:47','2025-10-09 05:09:47'),(67,'user','created','App\\Models\\User','created',62,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"peter01@springbord.com\", \"status\": 1, \"password\": \"$2y$12$iTWqJL5p0MYdLO/2gwG29.nKMBRKiKUlcs2z29wNy1bcQAybyuAxm\", \"last_name\": \"Clark\", \"contact_no\": \"9123456780\", \"created_by\": null, \"first_name\": \"Peter\", \"updated_by\": null, \"company_name\": null, \"project_manager\": null, \"is_password_update\": 0}}',NULL,'2025-10-09 05:22:13','2025-10-09 05:22:13'),(68,'user','deleted','App\\Models\\User','deleted',57,'App\\Models\\User',11,'{\"old\": {\"email\": \"subin.rabin@springbord.com\", \"status\": 1, \"password\": \"$2y$12$fciHYfOyWu/MT25KkfNYs.vVIOvfCKwRp/Bnl0mFFVZANTnrqVcCe\", \"last_name\": \"rabin\", \"contact_no\": \"8959483890\", \"created_by\": null, \"first_name\": \"subin\", \"updated_by\": null, \"company_name\": null, \"project_manager\": null, \"is_password_update\": 0}}',NULL,'2025-10-09 05:51:48','2025-10-09 05:51:48'),(69,'user','created','App\\Models\\User','created',63,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"david@gmail.com\", \"status\": 1, \"password\": \"$2y$12$Key7tOwyC1YWIu/67YndYeq7K/f2TFClbor2BvKlaNX1cG5KV4C12\", \"last_name\": \"L\", \"contact_no\": null, \"created_by\": null, \"first_name\": \"David\", \"updated_by\": null, \"company_name\": null, \"project_manager\": null, \"is_password_update\": 0}}',NULL,'2025-10-09 05:55:01','2025-10-09 05:55:01'),(70,'user','created','App\\Models\\User','created',64,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"nahid@springbord.com\", \"status\": 1, \"password\": \"$2y$12$Ttob0t7d/kI2Zd4Y4kYPreHsHZwDcqQNLycKgjMd/UwMgVNe9/Ak.\", \"last_name\": \"S\", \"contact_no\": \"1234567899\", \"created_by\": null, \"first_name\": \"Nahid\", \"updated_by\": null, \"company_name\": null, \"project_manager\": null, \"is_password_update\": 0}}',NULL,'2025-10-09 05:57:26','2025-10-09 05:57:26'),(71,'user','created','App\\Models\\User','created',65,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"abstractor8@springbord.com\", \"status\": 1, \"password\": \"$2y$12$mhAncQU905wojjLy9Ehe8uBE.yySDWVXWXojldhQ.M24NeZMYb4Iq\", \"last_name\": \"L\", \"contact_no\": \"1234567899\", \"created_by\": null, \"first_name\": \"abstractor8\", \"updated_by\": null, \"company_name\": null, \"project_manager\": 64, \"is_password_update\": 0}}',NULL,'2025-10-09 06:00:05','2025-10-09 06:00:05'),(72,'user','created','App\\Models\\User','created',66,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"abstractor9@springbord.com\", \"status\": 1, \"password\": \"$2y$12$F1VNtlpUwuKicsYZZJlT9.DJGajPGS/WtIt2RSTwz/acRirEMIjNi\", \"last_name\": \"K\", \"contact_no\": \"1234567899\", \"created_by\": null, \"first_name\": \"abstractor9\", \"updated_by\": null, \"company_name\": null, \"project_manager\": 64, \"is_password_update\": 0}}',NULL,'2025-10-09 06:00:45','2025-10-09 06:00:45'),(73,'user','created','App\\Models\\User','created',67,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"review8@springbord.com\", \"status\": 1, \"password\": \"$2y$12$iwoLLPVZRKxxYJUuZnZKu.DvPF3poLWkZiJfyBN4SZ7Ewy2igMTqG\", \"last_name\": \"K\", \"contact_no\": \"3958734890\", \"created_by\": null, \"first_name\": \"review8\", \"updated_by\": null, \"company_name\": null, \"project_manager\": 64, \"is_password_update\": 0}}',NULL,'2025-10-09 06:01:26','2025-10-09 06:01:26'),(74,'user','created','App\\Models\\User','created',68,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"review9@springbord.com\", \"status\": 1, \"password\": \"$2y$12$099aUgMEmaE/EEIA9dpyZu1BadnU2MUXZYBWmLB.aCgJejNIMaPQO\", \"last_name\": \"I\", \"contact_no\": \"1234556678\", \"created_by\": null, \"first_name\": \"review9\", \"updated_by\": null, \"company_name\": null, \"project_manager\": 64, \"is_password_update\": 0}}',NULL,'2025-10-09 06:01:54','2025-10-09 06:01:54'),(75,'user','created','App\\Models\\User','created',69,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"sense8@springbord.com\", \"status\": 1, \"password\": \"$2y$12$VCz4yleJDKDv3LsCh9iHyOUKIgmcbEbHI6u5vVXVGsRYlpgD5vD6e\", \"last_name\": \"K\", \"contact_no\": \"1234567899\", \"created_by\": null, \"first_name\": \"sense8\", \"updated_by\": null, \"company_name\": null, \"project_manager\": 64, \"is_password_update\": 0}}',NULL,'2025-10-09 06:02:35','2025-10-09 06:02:35'),(76,'user','created','App\\Models\\User','created',70,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"sense9@springbord.com\", \"status\": 1, \"password\": \"$2y$12$FsA5b4xp4bYeKv5GuG4Gue2J.3dDznw6F0ceqrt3dArNzOhBPMS.G\", \"last_name\": \"J\", \"contact_no\": \"1234567899\", \"created_by\": null, \"first_name\": \"sense9\", \"updated_by\": null, \"company_name\": null, \"project_manager\": 64, \"is_password_update\": 0}}',NULL,'2025-10-09 06:03:03','2025-10-09 06:03:03'),(77,'user','updated','App\\Models\\User','updated',65,'App\\Models\\User',65,'{\"old\": {\"password\": \"$2y$12$mhAncQU905wojjLy9Ehe8uBE.yySDWVXWXojldhQ.M24NeZMYb4Iq\", \"is_password_update\": 0}, \"attributes\": {\"password\": \"$2y$12$IGOr51fDqQz9a4cae6Crx.3/.b.YWQl.nuXjcqDn1nrc2WCgpkpQq\", \"is_password_update\": 1}}',NULL,'2025-10-09 06:26:17','2025-10-09 06:26:17'),(78,'user','created','App\\Models\\User','created',71,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"peter02@springbord.com\", \"status\": 1, \"password\": \"$2y$12$JFOz8RPTpdi7yBWBUcQlBeI5SqZ/us59QJ.M.FKx8b5Sup6BfN9hS\", \"last_name\": \"Clark\", \"contact_no\": \"9123456780\", \"created_by\": null, \"first_name\": \"Peter\", \"updated_by\": null, \"company_name\": null, \"project_manager\": null, \"is_password_update\": 0}}',NULL,'2025-10-09 09:50:40','2025-10-09 09:50:40'),(79,'pricing_masters','created','App\\Models\\PricingMaster','created',11,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Price A\", \"rate\": \"10.00\", \"status\": true, \"volume\": null, \"currency_id\": 1, \"vendor_cost\": null, \"pricing_type\": \"static\", \"approval_note\": null, \"department_id\": 1, \"document_path\": null, \"description_id\": 2, \"conversion_rate\": null, \"margin_percentage\": null, \"infrastructure_cost\": null, \"overhead_percentage\": null, \"service_offering_id\": 2, \"industry_vertical_id\": 1, \"volume_based_discount\": null, \"unit_of_measurement_id\": 2, \"project_management_cost\": null}}',NULL,'2025-10-09 11:56:17','2025-10-09 11:56:17'),(80,'notes','created','App\\Models\\Note','created',1,'App\\Models\\User',11,'{\"attributes\": {\"price\": \"10.00\", \"create_by\": 11, \"note_type\": 3, \"description\": null, \"pricing_master_id\": 11, \"approve_rejected_by\": null}}',NULL,'2025-10-09 11:56:17','2025-10-09 11:56:17'),(81,'pricing_masters','updated','App\\Models\\PricingMaster','updated',11,'App\\Models\\User',11,'{\"old\": {\"approval_note\": null}, \"attributes\": {\"approval_note\": \"Not accepted the price range\"}}',NULL,'2025-10-09 11:57:15','2025-10-09 11:57:15'),(82,'user','updated','App\\Models\\User','updated',68,'App\\Models\\User',68,'{\"old\": {\"password\": \"$2y$12$099aUgMEmaE/EEIA9dpyZu1BadnU2MUXZYBWmLB.aCgJejNIMaPQO\", \"is_password_update\": 0}, \"attributes\": {\"password\": \"$2y$12$A5T6fir7m6emCZSdWDZ7S.wht8W8.d/IPGthusuJ5SsYFfWfpXXlm\", \"is_password_update\": 1}}',NULL,'2025-10-09 12:04:18','2025-10-09 12:04:18'),(83,'user','updated','App\\Models\\User','updated',69,'App\\Models\\User',69,'{\"old\": {\"password\": \"$2y$12$VCz4yleJDKDv3LsCh9iHyOUKIgmcbEbHI6u5vVXVGsRYlpgD5vD6e\", \"is_password_update\": 0}, \"attributes\": {\"password\": \"$2y$12$naZvSOJkGZBy4.iR/IW81elHcbnzPByg9UNbwuVf10ougvEcrSa/S\", \"is_password_update\": 1}}',NULL,'2025-10-09 12:14:02','2025-10-09 12:14:02'),(84,'user','updated','App\\Models\\User','updated',64,'App\\Models\\User',64,'{\"old\": {\"password\": \"$2y$12$Ttob0t7d/kI2Zd4Y4kYPreHsHZwDcqQNLycKgjMd/UwMgVNe9/Ak.\", \"is_password_update\": 0}, \"attributes\": {\"password\": \"$2y$12$6OrpOi/ozH0KA.qODbzz6.C/V4lv/ozv4527qi1g02LlMPMXgBo8i\", \"is_password_update\": 1}}',NULL,'2025-10-09 12:20:11','2025-10-09 12:20:11'),(85,'pricing_masters','created','App\\Models\\PricingMaster','created',12,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Property-bill\", \"rate\": \"1.00\", \"status\": true, \"volume\": null, \"currency_id\": 2, \"vendor_cost\": null, \"pricing_type\": \"static\", \"approval_note\": null, \"department_id\": 1, \"document_path\": null, \"description_id\": 1, \"conversion_rate\": null, \"margin_percentage\": null, \"infrastructure_cost\": null, \"overhead_percentage\": null, \"service_offering_id\": 3, \"industry_vertical_id\": 1, \"volume_based_discount\": null, \"unit_of_measurement_id\": 2, \"project_management_cost\": null}}',NULL,'2025-10-09 12:40:44','2025-10-09 12:40:44'),(86,'notes','created','App\\Models\\Note','created',2,'App\\Models\\User',11,'{\"attributes\": {\"price\": \"1.00\", \"create_by\": 11, \"note_type\": 3, \"description\": null, \"pricing_master_id\": 12, \"approve_rejected_by\": null}}',NULL,'2025-10-09 12:40:44','2025-10-09 12:40:44'),(87,'pricing_masters','updated','App\\Models\\PricingMaster','updated',12,'App\\Models\\User',11,'{\"old\": {\"approval_note\": null}, \"attributes\": {\"approval_note\": \"approved\"}}',NULL,'2025-10-09 12:41:37','2025-10-09 12:41:37'),(88,'notes','updated','App\\Models\\Note','updated',2,'App\\Models\\User',11,'{\"old\": {\"note_type\": 3, \"description\": null, \"approve_rejected_by\": null}, \"attributes\": {\"note_type\": 1, \"description\": \"approved\", \"approve_rejected_by\": 11}}',NULL,'2025-10-09 12:41:37','2025-10-09 12:41:37'),(89,'industry_verticals','created','App\\Models\\IndustryVertical','created',3,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"data conversion\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-09 13:30:35','2025-10-09 13:30:35'),(90,'skill_masters','created','App\\Models\\SkillMaster','created',5,'App\\Models\\User',11,'{\"attributes\": {\"ctc\": \"100.00\", \"name\": \"PHP developer\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11, \"skill_expertise_level\": \"Intermediate\"}}',NULL,'2025-10-09 13:34:59','2025-10-09 13:34:59'),(91,'user','updated','App\\Models\\User','updated',66,'App\\Models\\User',66,'{\"old\": {\"password\": \"$2y$12$F1VNtlpUwuKicsYZZJlT9.DJGajPGS/WtIt2RSTwz/acRirEMIjNi\", \"is_password_update\": 0}, \"attributes\": {\"password\": \"$2y$12$7gYKUSMOni1lrCmKZogO8uRq/qSV44ncJy23HSjhAXnRg0wr.BW1y\", \"is_password_update\": 1}}',NULL,'2025-10-10 04:03:20','2025-10-10 04:03:20'),(92,'project_types','created','App\\Models\\ProjectType','created',5,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Lease Project1\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-10 04:03:52','2025-10-10 04:03:52'),(93,'project_types','updated','App\\Models\\ProjectType','updated',5,'App\\Models\\User',11,'{\"old\": {\"name\": \"Lease Project1\"}, \"attributes\": {\"name\": \"Lease Project2\"}}',NULL,'2025-10-10 04:03:59','2025-10-10 04:03:59'),(94,'departments','created','App\\Models\\Department','created',5,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Technology\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-10 04:04:15','2025-10-10 04:04:15'),(95,'project_priorities','created','App\\Models\\ProjectPriority','created',4,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Medium\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-10 04:04:23','2025-10-10 04:04:23'),(96,'project_statuses','created','App\\Models\\ProjectStatus','created',7,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Test\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-10 04:04:43','2025-10-10 04:04:43'),(97,'project_delivery_frequencies','created','App\\Models\\ProjectDeliveryFrequency','created',4,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Day\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-10 04:05:07','2025-10-10 04:05:07'),(98,'mode_of_deliveries','created','App\\Models\\ModeOfDelivery','created',3,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Drive\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-10 04:05:34','2025-10-10 04:05:34'),(99,'input_output_formats','created','App\\Models\\InputOutputFormat','created',5,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"PNG\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-10 04:05:47','2025-10-10 04:05:47'),(100,'industry_verticals','created','App\\Models\\IndustryVertical','created',4,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"App Dev\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-10 04:19:04','2025-10-10 04:19:04'),(101,'service_offerings','created','App\\Models\\ServiceOffering','created',5,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"App Service\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-10 04:19:26','2025-10-10 04:19:26'),(102,'unit_of_measurements','created','App\\Models\\UnitOfMeasurement','created',8,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Percentage (%)\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-10 04:21:59','2025-10-10 04:21:59'),(103,'currencies','created','App\\Models\\Currency','created',6,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"KWD\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-10 04:22:48','2025-10-10 04:22:48'),(104,'descriptions','created','App\\Models\\Description','created',4,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Long Abstraction\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-10 04:23:10','2025-10-10 04:23:10'),(105,'user','deleted','App\\Models\\User','deleted',71,'App\\Models\\User',11,'{\"old\": {\"email\": \"peter02@springbord.com\", \"status\": 1, \"password\": \"$2y$12$JFOz8RPTpdi7yBWBUcQlBeI5SqZ/us59QJ.M.FKx8b5Sup6BfN9hS\", \"last_name\": \"Clark\", \"contact_no\": \"9123456780\", \"created_by\": null, \"first_name\": \"Peter\", \"updated_by\": null, \"company_name\": null, \"project_manager\": null, \"is_password_update\": 0}}',NULL,'2025-10-10 04:27:01','2025-10-10 04:27:01'),(106,'user','created','App\\Models\\User','created',72,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"subin.rabin@springbord.com\", \"status\": 1, \"password\": \"$2y$12$O5r.wmBsRcG5lYazwx5Tlu9olwkaRpcLVsKtcKpt/KFGofjHiJVwu\", \"last_name\": \"rabin\", \"contact_no\": \"8959483890\", \"created_by\": null, \"first_name\": \"subin\", \"updated_by\": null, \"company_name\": null, \"project_manager\": null, \"is_password_update\": 0}}',NULL,'2025-10-10 04:35:20','2025-10-10 04:35:20'),(107,'user','created','App\\Models\\User','created',73,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"rahul@gmail.com\", \"status\": 1, \"password\": \"$2y$12$R39D0EhHtyAMCLKqTR.r8utoeRuYRZ4W.QtXXY8qg2AxqljqKGX6.\", \"last_name\": \"S\", \"contact_no\": \"9988776655\", \"created_by\": null, \"first_name\": \"Rahul\", \"updated_by\": null, \"company_name\": null, \"project_manager\": null, \"is_password_update\": 0}}',NULL,'2025-10-10 06:26:11','2025-10-10 06:26:11'),(108,'user','updated','App\\Models\\User','updated',54,'App\\Models\\User',54,'{\"old\": {\"password\": \"$2y$12$1KDosqmHVNfjZ/bDVBWGROBIMsB8F32/M2ruomf2VM9WXJ1SiZ5ti\", \"is_password_update\": 0}, \"attributes\": {\"password\": \"$2y$12$Daa/N3sJfTX45BdLeFNDE.NYHmbcGaNiH0cGX2mqg6b3uZwBJhkIm\", \"is_password_update\": 1}}',NULL,'2025-10-13 07:03:19','2025-10-13 07:03:19'),(109,'user','deleted','App\\Models\\User','deleted',54,'App\\Models\\User',11,'{\"old\": {\"email\": \"sense6@springbord.com\", \"status\": 1, \"password\": \"$2y$12$Daa/N3sJfTX45BdLeFNDE.NYHmbcGaNiH0cGX2mqg6b3uZwBJhkIm\", \"last_name\": \"C\", \"contact_no\": \"7708234253\", \"created_by\": null, \"first_name\": \"sense6\", \"updated_by\": null, \"company_name\": null, \"project_manager\": 46, \"is_password_update\": 1}}',NULL,'2025-10-13 07:03:37','2025-10-13 07:03:37'),(110,'user','created','App\\Models\\User','created',74,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"sense6@springbord.com\", \"status\": 1, \"password\": \"$2y$12$chId0pfoQgnpMEF8h3mAuOf.JicUNst6WgjWKi7bP5s52wJdhyrES\", \"last_name\": \"B\", \"contact_no\": \"8959483890\", \"created_by\": null, \"first_name\": \"sense6\", \"updated_by\": null, \"company_name\": null, \"project_manager\": 46, \"is_password_update\": 0}}',NULL,'2025-10-13 07:04:21','2025-10-13 07:04:21'),(111,'user','updated','App\\Models\\User','updated',74,'App\\Models\\User',74,'{\"old\": {\"password\": \"$2y$12$chId0pfoQgnpMEF8h3mAuOf.JicUNst6WgjWKi7bP5s52wJdhyrES\", \"is_password_update\": 0}, \"attributes\": {\"password\": \"$2y$12$g7iQUecNmhq7NxN5Mmv2peEZShCxYaKLStSbN4nvVyHnwJzqAuMsy\", \"is_password_update\": 1}}',NULL,'2025-10-13 07:05:10','2025-10-13 07:05:10'),(112,'user','created','App\\Models\\User','created',75,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"pradeep@springbord.com\", \"status\": 1, \"password\": \"$2y$12$FAQm0mlFqxDvhP39v8Aq5eP181Jx6jdKklIcEbFJ6yYQv0E.F3Gri\", \"last_name\": \"K\", \"contact_no\": null, \"created_by\": null, \"first_name\": \"P\", \"updated_by\": null, \"company_name\": null, \"project_manager\": null, \"is_password_update\": 0}}',NULL,'2025-10-13 11:09:24','2025-10-13 11:09:24'),(113,'user','created','App\\Models\\User','created',76,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"finance3@springbord.com\", \"status\": 1, \"password\": \"$2y$12$tRtd6a7EPSrVVa6JHq4s5efhkApYzRRTgIozcokHAqqTNHve2kN0e\", \"last_name\": \"A\", \"contact_no\": \"9876543210\", \"created_by\": null, \"first_name\": \"Finance3\", \"updated_by\": null, \"company_name\": null, \"project_manager\": 46, \"is_password_update\": 0}}',NULL,'2025-10-13 11:25:50','2025-10-13 11:25:50'),(114,'user','updated','App\\Models\\User','updated',76,'App\\Models\\User',76,'{\"old\": {\"password\": \"$2y$12$tRtd6a7EPSrVVa6JHq4s5efhkApYzRRTgIozcokHAqqTNHve2kN0e\", \"is_password_update\": 0}, \"attributes\": {\"password\": \"$2y$12$V.Mpwj1YY0iWzBZrdmhrlOfPpJ1C/m4ZZD9qHZN9BQfEXiXY5rnAe\", \"is_password_update\": 1}}',NULL,'2025-10-13 11:31:09','2025-10-13 11:31:09'),(115,'banks','created','App\\Models\\Bank','created',1,'App\\Models\\User',76,'{\"attributes\": {\"micr\": \"021000021\", \"entity\": \"ABC Corporation\", \"status\": 1, \"bsr_code\": \"123456789\", \"bank_name\": \"HDFC Bank\", \"ifsc_code\": \"HDFC0001234\", \"aba_number\": \"021000021\", \"swift_code\": \"HDFCINBBXXX\", \"currency_id\": 1, \"account_name\": \"ABC Corp Operations\", \"account_number\": \"123456789012\", \"branch_address\": \"270 Park Avenue,\\r\\nNew York, NY 10017,\\r\\nUnited States\", \"routing_number\": \"021000021\", \"branch_location\": \"Manhattan Branch\"}}',NULL,'2025-10-13 11:39:52','2025-10-13 11:39:52'),(116,'banks','updated','App\\Models\\Bank','updated',1,'App\\Models\\User',11,'{\"old\": {\"account_name\": \"ABC Corp Operations\"}, \"attributes\": {\"account_name\": \"ABC Corp\"}}',NULL,'2025-10-13 11:48:52','2025-10-13 11:48:52'),(117,'industry_verticals','deleted','App\\Models\\IndustryVertical','deleted',4,'App\\Models\\User',11,'{\"old\": {\"name\": \"App Dev\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 11:50:12','2025-10-13 11:50:12'),(118,'industry_verticals','deleted','App\\Models\\IndustryVertical','deleted',3,'App\\Models\\User',11,'{\"old\": {\"name\": \"data conversion\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 11:50:17','2025-10-13 11:50:17'),(119,'industry_verticals','deleted','App\\Models\\IndustryVertical','deleted',2,'App\\Models\\User',11,'{\"old\": {\"name\": \"JKvertical\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 11:50:20','2025-10-13 11:50:20'),(120,'service_offerings','deleted','App\\Models\\ServiceOffering','deleted',5,'App\\Models\\User',11,'{\"old\": {\"name\": \"App Service\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 11:50:30','2025-10-13 11:50:30'),(121,'service_offerings','deleted','App\\Models\\ServiceOffering','deleted',4,'App\\Models\\User',11,'{\"old\": {\"name\": \"JKservice\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 11:50:34','2025-10-13 11:50:34'),(122,'pricing_masters','updated','App\\Models\\PricingMaster','updated',12,'App\\Models\\User',11,'{\"old\": {\"currency_id\": 2, \"approval_note\": \"approved\"}, \"attributes\": {\"currency_id\": 4, \"approval_note\": null}}',NULL,'2025-10-13 11:50:35','2025-10-13 11:50:35'),(123,'notes','created','App\\Models\\Note','created',3,'App\\Models\\User',11,'{\"attributes\": {\"price\": \"1.00\", \"create_by\": 11, \"note_type\": 3, \"description\": null, \"pricing_master_id\": 12, \"approve_rejected_by\": null}}',NULL,'2025-10-13 11:50:35','2025-10-13 11:50:35'),(124,'unit_of_measurements','deleted','App\\Models\\UnitOfMeasurement','deleted',8,'App\\Models\\User',11,'{\"old\": {\"name\": \"Percentage (%)\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 11:51:06','2025-10-13 11:51:06'),(125,'unit_of_measurements','deleted','App\\Models\\UnitOfMeasurement','deleted',7,'App\\Models\\User',11,'{\"old\": {\"name\": \"jkunit of measure\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 11:51:11','2025-10-13 11:51:11'),(126,'unit_of_measurements','deleted','App\\Models\\UnitOfMeasurement','deleted',6,'App\\Models\\User',11,'{\"old\": {\"name\": \"Project Based\", \"status\": 1, \"created_by\": 1, \"updated_by\": 1}}',NULL,'2025-10-13 11:51:15','2025-10-13 11:51:15'),(127,'unit_of_measurements','deleted','App\\Models\\UnitOfMeasurement','deleted',5,'App\\Models\\User',11,'{\"old\": {\"name\": \"Retainer Model\", \"status\": 1, \"created_by\": 1, \"updated_by\": 1}}',NULL,'2025-10-13 11:51:19','2025-10-13 11:51:19'),(128,'unit_of_measurements','deleted','App\\Models\\UnitOfMeasurement','deleted',4,'App\\Models\\User',11,'{\"old\": {\"name\": \"Contingency Percentage\", \"status\": 1, \"created_by\": 1, \"updated_by\": 1}}',NULL,'2025-10-13 11:51:23','2025-10-13 11:51:23'),(129,'currencies','deleted','App\\Models\\Currency','deleted',6,'App\\Models\\User',11,'{\"old\": {\"name\": \"KWD\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 11:51:41','2025-10-13 11:51:41'),(130,'currencies','deleted','App\\Models\\Currency','deleted',5,'App\\Models\\User',11,'{\"old\": {\"name\": \"JKUSD\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 11:51:44','2025-10-13 11:51:44'),(131,'service_offerings','updated','App\\Models\\ServiceOffering','updated',3,'App\\Models\\User',11,'{\"old\": {\"status\": 1, \"updated_by\": null}, \"attributes\": {\"status\": 0, \"updated_by\": 11}}',NULL,'2025-10-13 11:52:04','2025-10-13 11:52:04'),(132,'service_offerings','updated','App\\Models\\ServiceOffering','updated',2,'App\\Models\\User',11,'{\"old\": {\"status\": 1, \"updated_by\": null}, \"attributes\": {\"status\": 0, \"updated_by\": 11}}',NULL,'2025-10-13 11:52:20','2025-10-13 11:52:20'),(133,'service_offerings','updated','App\\Models\\ServiceOffering','updated',1,'App\\Models\\User',11,'{\"old\": {\"status\": 1, \"updated_by\": null}, \"attributes\": {\"status\": 0, \"updated_by\": 11}}',NULL,'2025-10-13 11:52:32','2025-10-13 11:52:32'),(134,'industry_verticals','updated','App\\Models\\IndustryVertical','updated',1,'App\\Models\\User',11,'{\"old\": {\"status\": 1, \"updated_by\": null}, \"attributes\": {\"status\": 0, \"updated_by\": 11}}',NULL,'2025-10-13 11:52:46','2025-10-13 11:52:46'),(135,'project_types','deleted','App\\Models\\ProjectType','deleted',5,'App\\Models\\User',11,'{\"old\": {\"name\": \"Lease Project2\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 11:53:13','2025-10-13 11:53:13'),(136,'project_types','deleted','App\\Models\\ProjectType','deleted',4,'App\\Models\\User',11,'{\"old\": {\"name\": \"CAM Reconciliation\", \"status\": 1, \"created_by\": null, \"updated_by\": 1}}',NULL,'2025-10-13 11:53:17','2025-10-13 11:53:17'),(137,'project_types','updated','App\\Models\\ProjectType','updated',3,'App\\Models\\User',11,'{\"old\": {\"status\": 1, \"updated_by\": null}, \"attributes\": {\"status\": 0, \"updated_by\": 11}}',NULL,'2025-10-13 11:53:28','2025-10-13 11:53:28'),(138,'project_types','updated','App\\Models\\ProjectType','updated',2,'App\\Models\\User',11,'{\"old\": {\"status\": 1, \"updated_by\": null}, \"attributes\": {\"status\": 0, \"updated_by\": 11}}',NULL,'2025-10-13 11:53:33','2025-10-13 11:53:33'),(139,'project_types','updated','App\\Models\\ProjectType','updated',1,'App\\Models\\User',11,'{\"old\": {\"status\": 1, \"updated_by\": null}, \"attributes\": {\"status\": 0, \"updated_by\": 11}}',NULL,'2025-10-13 11:53:38','2025-10-13 11:53:38'),(140,'project_statuses','deleted','App\\Models\\ProjectStatus','deleted',7,'App\\Models\\User',11,'{\"old\": {\"name\": \"Test\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 11:53:49','2025-10-13 11:53:49'),(141,'pricing_masters','deleted','App\\Models\\PricingMaster','deleted',11,'App\\Models\\User',11,'{\"old\": {\"name\": \"Price A\", \"rate\": \"10.00\", \"status\": true, \"volume\": null, \"currency_id\": 1, \"vendor_cost\": null, \"pricing_type\": \"static\", \"approval_note\": \"Not accepted the price range\", \"department_id\": 1, \"document_path\": null, \"description_id\": 2, \"conversion_rate\": null, \"margin_percentage\": null, \"infrastructure_cost\": null, \"overhead_percentage\": null, \"service_offering_id\": 2, \"industry_vertical_id\": 1, \"volume_based_discount\": null, \"unit_of_measurement_id\": 2, \"project_management_cost\": null}}',NULL,'2025-10-13 11:54:33','2025-10-13 11:54:33'),(142,'pricing_masters','deleted','App\\Models\\PricingMaster','deleted',10,'App\\Models\\User',11,'{\"old\": {\"name\": \"Acme Solutions\", \"rate\": \"10.00\", \"status\": true, \"volume\": null, \"currency_id\": 4, \"vendor_cost\": null, \"pricing_type\": \"static\", \"approval_note\": null, \"department_id\": 2, \"document_path\": null, \"description_id\": 2, \"conversion_rate\": null, \"margin_percentage\": null, \"infrastructure_cost\": null, \"overhead_percentage\": null, \"service_offering_id\": 1, \"industry_vertical_id\": 1, \"volume_based_discount\": null, \"unit_of_measurement_id\": 1, \"project_management_cost\": null}}',NULL,'2025-10-13 11:54:38','2025-10-13 11:54:38'),(143,'pricing_masters','deleted','App\\Models\\PricingMaster','deleted',8,'App\\Models\\User',11,'{\"old\": {\"name\": \"FTE usa\", \"rate\": \"3.20\", \"status\": true, \"volume\": \"100.00\", \"currency_id\": 1, \"vendor_cost\": null, \"pricing_type\": \"custom\", \"approval_note\": null, \"department_id\": 1, \"document_path\": null, \"description_id\": 1, \"conversion_rate\": \"88.3400\", \"margin_percentage\": \"10.00\", \"infrastructure_cost\": \"10.00\", \"overhead_percentage\": \"15.00\", \"service_offering_id\": 1, \"industry_vertical_id\": 1, \"volume_based_discount\": \"5.00\", \"unit_of_measurement_id\": 1, \"project_management_cost\": \"5.00\"}}',NULL,'2025-10-13 11:54:47','2025-10-13 11:54:47'),(144,'pricing_masters','deleted','App\\Models\\PricingMaster','deleted',7,'App\\Models\\User',11,'{\"old\": {\"name\": \"Abstract-limited\", \"rate\": \"10.00\", \"status\": true, \"volume\": null, \"currency_id\": 1, \"vendor_cost\": null, \"pricing_type\": \"static\", \"approval_note\": null, \"department_id\": 1, \"document_path\": null, \"description_id\": 2, \"conversion_rate\": null, \"margin_percentage\": null, \"infrastructure_cost\": null, \"overhead_percentage\": null, \"service_offering_id\": 2, \"industry_vertical_id\": 1, \"volume_based_discount\": null, \"unit_of_measurement_id\": 2, \"project_management_cost\": null}}',NULL,'2025-10-13 11:54:52','2025-10-13 11:54:52'),(145,'pricing_masters','deleted','App\\Models\\PricingMaster','deleted',5,'App\\Models\\User',11,'{\"old\": {\"name\": \"Abstraction - D&D Unit Based\", \"rate\": \"2.25\", \"status\": true, \"volume\": \"1000.00\", \"currency_id\": 1, \"vendor_cost\": \"10.00\", \"pricing_type\": \"custom\", \"approval_note\": null, \"department_id\": 1, \"document_path\": null, \"description_id\": 2, \"conversion_rate\": \"85.1600\", \"margin_percentage\": \"10.00\", \"infrastructure_cost\": \"150.00\", \"overhead_percentage\": \"10.00\", \"service_offering_id\": 3, \"industry_vertical_id\": 1, \"volume_based_discount\": \"1.00\", \"unit_of_measurement_id\": 2, \"project_management_cost\": \"20.00\"}}',NULL,'2025-10-13 11:54:58','2025-10-13 11:54:58'),(146,'pricing_masters','deleted','App\\Models\\PricingMaster','deleted',1,'App\\Models\\User',11,'{\"old\": {\"name\": \"Briskstar\", \"rate\": \"15.00\", \"status\": true, \"volume\": null, \"currency_id\": 1, \"vendor_cost\": null, \"pricing_type\": \"static\", \"approval_note\": null, \"department_id\": 1, \"document_path\": null, \"description_id\": 1, \"conversion_rate\": null, \"margin_percentage\": null, \"infrastructure_cost\": null, \"overhead_percentage\": null, \"service_offering_id\": 1, \"industry_vertical_id\": 1, \"volume_based_discount\": null, \"unit_of_measurement_id\": 1, \"project_management_cost\": null}}',NULL,'2025-10-13 11:55:04','2025-10-13 11:55:04'),(147,'pricing_masters','deleted','App\\Models\\PricingMaster','deleted',2,'App\\Models\\User',11,'{\"old\": {\"name\": \"Abstraction - D&D FTE\", \"rate\": \"475.90\", \"status\": true, \"volume\": \"1000.00\", \"currency_id\": 1, \"vendor_cost\": null, \"pricing_type\": \"custom\", \"approval_note\": null, \"department_id\": 1, \"document_path\": null, \"description_id\": 1, \"conversion_rate\": \"85.1600\", \"margin_percentage\": \"10.00\", \"infrastructure_cost\": \"150.00\", \"overhead_percentage\": \"10.00\", \"service_offering_id\": 1, \"industry_vertical_id\": 1, \"volume_based_discount\": \"1.00\", \"unit_of_measurement_id\": 1, \"project_management_cost\": \"20.00\"}}',NULL,'2025-10-13 11:55:08','2025-10-13 11:55:08'),(148,'pricing_masters','deleted','App\\Models\\PricingMaster','deleted',3,'App\\Models\\User',11,'{\"old\": {\"name\": \"Abstraction - D&D FTE 2\", \"rate\": \"1002.49\", \"status\": true, \"volume\": \"1000.00\", \"currency_id\": 1, \"vendor_cost\": \"40000.00\", \"pricing_type\": \"custom\", \"approval_note\": null, \"department_id\": 1, \"document_path\": null, \"description_id\": 2, \"conversion_rate\": \"85.1600\", \"margin_percentage\": \"10.00\", \"infrastructure_cost\": \"150.00\", \"overhead_percentage\": \"10.00\", \"service_offering_id\": 3, \"industry_vertical_id\": 1, \"volume_based_discount\": \"1.00\", \"unit_of_measurement_id\": 1, \"project_management_cost\": \"20.00\"}}',NULL,'2025-10-13 11:55:12','2025-10-13 11:55:12'),(149,'pricing_masters','deleted','App\\Models\\PricingMaster','deleted',4,'App\\Models\\User',11,'{\"old\": {\"name\": \"Abstraction - D&D Hourly\", \"rate\": \"9.44\", \"status\": true, \"volume\": \"1000.00\", \"currency_id\": 1, \"vendor_cost\": \"350.00\", \"pricing_type\": \"custom\", \"approval_note\": null, \"department_id\": 1, \"document_path\": null, \"description_id\": 2, \"conversion_rate\": \"86.1500\", \"margin_percentage\": \"10.00\", \"infrastructure_cost\": \"150.00\", \"overhead_percentage\": \"10.00\", \"service_offering_id\": 3, \"industry_vertical_id\": 1, \"volume_based_discount\": \"1.00\", \"unit_of_measurement_id\": 3, \"project_management_cost\": \"20.00\"}}',NULL,'2025-10-13 11:55:17','2025-10-13 11:55:17'),(150,'pricing_masters','deleted','App\\Models\\PricingMaster','deleted',6,'App\\Models\\User',11,'{\"old\": {\"name\": \"Abstraction - D&D Unit Based 1\", \"rate\": \"2.96\", \"status\": true, \"volume\": \"1000.00\", \"currency_id\": 1, \"vendor_cost\": null, \"pricing_type\": \"custom\", \"approval_note\": null, \"department_id\": 1, \"document_path\": null, \"description_id\": 2, \"conversion_rate\": \"85.1600\", \"margin_percentage\": \"10.00\", \"infrastructure_cost\": \"100.00\", \"overhead_percentage\": \"10.00\", \"service_offering_id\": 3, \"industry_vertical_id\": 1, \"volume_based_discount\": \"1.00\", \"unit_of_measurement_id\": 2, \"project_management_cost\": \"20.00\"}}',NULL,'2025-10-13 11:55:21','2025-10-13 11:55:21'),(151,'departments','deleted','App\\Models\\Department','deleted',5,'App\\Models\\User',11,'{\"old\": {\"name\": \"Technology\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 11:56:27','2025-10-13 11:56:27'),(152,'departments','deleted','App\\Models\\Department','deleted',4,'App\\Models\\User',11,'{\"old\": {\"name\": \"Development\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 11:56:43','2025-10-13 11:56:43'),(153,'departments','deleted','App\\Models\\Department','deleted',3,'App\\Models\\User',11,'{\"old\": {\"name\": \"Accounting Services\", \"status\": 1, \"created_by\": 1, \"updated_by\": 1}}',NULL,'2025-10-13 11:56:47','2025-10-13 11:56:47'),(154,'departments','deleted','App\\Models\\Department','deleted',2,'App\\Models\\User',11,'{\"old\": {\"name\": \"Auditing Services\", \"status\": 1, \"created_by\": 1, \"updated_by\": 1}}',NULL,'2025-10-13 11:56:51','2025-10-13 11:56:51'),(155,'departments','updated','App\\Models\\Department','updated',1,'App\\Models\\User',11,'{\"old\": {\"name\": \"Lease Abstraction\", \"updated_by\": 1}, \"attributes\": {\"name\": \"Lease Abstraction Services\", \"updated_by\": 11}}',NULL,'2025-10-13 11:57:10','2025-10-13 11:57:10'),(156,'departments','created','App\\Models\\Department','created',6,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"CAM Reconciliation Services\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 11:57:25','2025-10-13 11:57:25'),(157,'departments','created','App\\Models\\Department','created',7,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Property Accounting Services\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 11:57:43','2025-10-13 11:57:43'),(158,'departments','created','App\\Models\\Department','created',8,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Information Services\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 11:57:59','2025-10-13 11:57:59'),(159,'industry_verticals','created','App\\Models\\IndustryVertical','created',5,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Test1\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 11:58:06','2025-10-13 11:58:06'),(160,'industry_verticals','updated','App\\Models\\IndustryVertical','updated',5,'App\\Models\\User',11,'{\"old\": {\"status\": 1}, \"attributes\": {\"status\": 0}}',NULL,'2025-10-13 11:58:14','2025-10-13 11:58:14'),(161,'departments','created','App\\Models\\Department','created',9,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Data Processing Services\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 11:58:21','2025-10-13 11:58:21'),(162,'industry_verticals','updated','App\\Models\\IndustryVertical','updated',5,'App\\Models\\User',11,'{\"old\": {\"status\": 0}, \"attributes\": {\"status\": 1}}',NULL,'2025-10-13 11:58:21','2025-10-13 11:58:21'),(163,'service_offerings','created','App\\Models\\ServiceOffering','created',6,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Test Service\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 11:58:36','2025-10-13 11:58:36'),(164,'unit_of_measurements','created','App\\Models\\UnitOfMeasurement','created',9,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"LTR\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 11:58:46','2025-10-13 11:58:46'),(165,'service_offerings','deleted','App\\Models\\ServiceOffering','deleted',6,'App\\Models\\User',11,'{\"old\": {\"name\": \"Test Service\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 11:59:00','2025-10-13 11:59:00'),(166,'skill_masters','created','App\\Models\\SkillMaster','created',6,'App\\Models\\User',11,'{\"attributes\": {\"ctc\": \"10000.00\", \"name\": \"Test Engineer\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11, \"skill_expertise_level\": \"3\"}}',NULL,'2025-10-13 11:59:15','2025-10-13 11:59:15'),(167,'skill_masters','updated','App\\Models\\SkillMaster','updated',6,'App\\Models\\User',11,'{\"old\": {\"ctc\": \"10000.00\"}, \"attributes\": {\"ctc\": \"40000.00\"}}',NULL,'2025-10-13 11:59:22','2025-10-13 11:59:22'),(168,'service_offerings','created','App\\Models\\ServiceOffering','created',7,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Full Lease Abstraction\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 11:59:57','2025-10-13 11:59:57'),(169,'service_offerings','created','App\\Models\\ServiceOffering','created',8,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"CAM Reconciliation\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 12:00:15','2025-10-13 12:00:15'),(170,'descriptions','created','App\\Models\\Description','created',5,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Test Finance\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 12:00:17','2025-10-13 12:00:17'),(171,'service_offerings','created','App\\Models\\ServiceOffering','created',9,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Tax Reconcilation\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 12:00:27','2025-10-13 12:00:27'),(172,'currencies','created','App\\Models\\Currency','created',7,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"RIG\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 12:00:44','2025-10-13 12:00:44'),(173,'service_offerings','created','App\\Models\\ServiceOffering','created',10,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Full Reconciliation\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 12:00:45','2025-10-13 12:00:45'),(174,'service_offerings','created','App\\Models\\ServiceOffering','created',11,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Estimates\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 12:01:01','2025-10-13 12:01:01'),(175,'service_offerings','created','App\\Models\\ServiceOffering','created',12,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Recovery set up\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 12:01:17','2025-10-13 12:01:17'),(176,'service_offerings','created','App\\Models\\ServiceOffering','created',13,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Recovery Set up Audit\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 12:01:28','2025-10-13 12:01:28'),(177,'service_offerings','created','App\\Models\\ServiceOffering','created',14,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"CAM Audit\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 12:01:41','2025-10-13 12:01:41'),(178,'service_offerings','created','App\\Models\\ServiceOffering','created',15,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Posting of Charges\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 12:01:53','2025-10-13 12:01:53'),(179,'service_offerings','created','App\\Models\\ServiceOffering','created',16,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Accounting Services\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 12:02:06','2025-10-13 12:02:06'),(180,'service_offerings','created','App\\Models\\ServiceOffering','created',17,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Online Invoice / PO processing\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 12:04:08','2025-10-13 12:04:08'),(181,'service_offerings','created','App\\Models\\ServiceOffering','created',18,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"BFE process\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 12:04:18','2025-10-13 12:04:18'),(182,'service_offerings','created','App\\Models\\ServiceOffering','created',19,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Media invoice processing\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 12:04:29','2025-10-13 12:04:29'),(183,'service_offerings','created','App\\Models\\ServiceOffering','created',20,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Assessment Questions\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 12:04:42','2025-10-13 12:04:42'),(184,'service_offerings','created','App\\Models\\ServiceOffering','created',21,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Assessment Names\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 12:04:56','2025-10-13 12:04:56'),(185,'service_offerings','created','App\\Models\\ServiceOffering','created',22,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Broadcast Print\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 12:05:29','2025-10-13 12:05:29'),(186,'service_offerings','created','App\\Models\\ServiceOffering','created',23,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Purchase Orders\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 12:05:41','2025-10-13 12:05:41'),(187,'service_offerings','created','App\\Models\\ServiceOffering','created',24,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Test Purchase\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 12:07:41','2025-10-13 12:07:41'),(188,'departments','created','App\\Models\\Department','created',10,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Test Department\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 12:09:17','2025-10-13 12:09:17'),(189,'pricing_masters','created','App\\Models\\PricingMaster','created',13,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Test Price\", \"rate\": \"5.83\", \"status\": true, \"volume\": \"4.00\", \"currency_id\": 7, \"vendor_cost\": \"10.00\", \"pricing_type\": \"custom\", \"approval_note\": null, \"department_id\": 10, \"document_path\": null, \"description_id\": 5, \"conversion_rate\": \"12.0000\", \"margin_percentage\": \"5.00\", \"infrastructure_cost\": \"14.00\", \"overhead_percentage\": \"3.00\", \"service_offering_id\": 24, \"industry_vertical_id\": 5, \"volume_based_discount\": \"2.00\", \"unit_of_measurement_id\": 9, \"project_management_cost\": \"2.00\"}}',NULL,'2025-10-13 12:10:39','2025-10-13 12:10:39'),(190,'notes','created','App\\Models\\Note','created',4,'App\\Models\\User',11,'{\"attributes\": {\"price\": \"5.83\", \"create_by\": 11, \"note_type\": 3, \"description\": null, \"pricing_master_id\": 13, \"approve_rejected_by\": null}}',NULL,'2025-10-13 12:10:39','2025-10-13 12:10:39'),(191,'pricing_masters','deleted','App\\Models\\PricingMaster','deleted',13,'App\\Models\\User',11,'{\"old\": {\"name\": \"Test Price\", \"rate\": \"5.83\", \"status\": true, \"volume\": \"4.00\", \"currency_id\": 7, \"vendor_cost\": \"10.00\", \"pricing_type\": \"custom\", \"approval_note\": null, \"department_id\": 10, \"document_path\": null, \"description_id\": 5, \"conversion_rate\": \"12.0000\", \"margin_percentage\": \"5.00\", \"infrastructure_cost\": \"14.00\", \"overhead_percentage\": \"3.00\", \"service_offering_id\": 24, \"industry_vertical_id\": 5, \"volume_based_discount\": \"2.00\", \"unit_of_measurement_id\": 9, \"project_management_cost\": \"2.00\"}}',NULL,'2025-10-13 12:10:44','2025-10-13 12:10:44'),(192,'pricing_masters','created','App\\Models\\PricingMaster','created',14,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Test Pricing\", \"rate\": \"10.00\", \"status\": true, \"volume\": null, \"currency_id\": 7, \"vendor_cost\": null, \"pricing_type\": \"static\", \"approval_note\": null, \"department_id\": 10, \"document_path\": null, \"description_id\": 5, \"conversion_rate\": null, \"margin_percentage\": null, \"infrastructure_cost\": null, \"overhead_percentage\": null, \"service_offering_id\": 24, \"industry_vertical_id\": 5, \"volume_based_discount\": null, \"unit_of_measurement_id\": 9, \"project_management_cost\": null}}',NULL,'2025-10-13 12:11:23','2025-10-13 12:11:23'),(193,'notes','created','App\\Models\\Note','created',5,'App\\Models\\User',11,'{\"attributes\": {\"price\": \"10.00\", \"create_by\": 11, \"note_type\": 3, \"description\": null, \"pricing_master_id\": 14, \"approve_rejected_by\": null}}',NULL,'2025-10-13 12:11:23','2025-10-13 12:11:23'),(194,'pricing_masters','deleted','App\\Models\\PricingMaster','deleted',14,'App\\Models\\User',11,'{\"old\": {\"name\": \"Test Pricing\", \"rate\": \"10.00\", \"status\": true, \"volume\": null, \"currency_id\": 7, \"vendor_cost\": null, \"pricing_type\": \"static\", \"approval_note\": null, \"department_id\": 10, \"document_path\": null, \"description_id\": 5, \"conversion_rate\": null, \"margin_percentage\": null, \"infrastructure_cost\": null, \"overhead_percentage\": null, \"service_offering_id\": 24, \"industry_vertical_id\": 5, \"volume_based_discount\": null, \"unit_of_measurement_id\": 9, \"project_management_cost\": null}}',NULL,'2025-10-13 12:11:26','2025-10-13 12:11:26'),(195,'pricing_masters','updated','App\\Models\\PricingMaster','updated',12,'App\\Models\\User',11,'{\"old\": {\"department_id\": 1, \"description_id\": 1, \"service_offering_id\": 3, \"industry_vertical_id\": 1, \"unit_of_measurement_id\": 2}, \"attributes\": {\"department_id\": 10, \"description_id\": 5, \"service_offering_id\": 24, \"industry_vertical_id\": 5, \"unit_of_measurement_id\": 9}}',NULL,'2025-10-13 12:12:13','2025-10-13 12:12:13'),(196,'industry_verticals','created','App\\Models\\IndustryVertical','created',6,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Test2\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 12:19:16','2025-10-13 12:19:16'),(197,'unit_of_measurements','deleted','App\\Models\\UnitOfMeasurement','deleted',3,'App\\Models\\User',11,'{\"old\": {\"name\": \"Hourly Rate\", \"status\": 1, \"created_by\": 1, \"updated_by\": 1}}',NULL,'2025-10-13 12:19:38','2025-10-13 12:19:38'),(198,'unit_of_measurements','deleted','App\\Models\\UnitOfMeasurement','deleted',1,'App\\Models\\User',11,'{\"old\": {\"name\": \"FTE\", \"status\": 1, \"created_by\": 1, \"updated_by\": 1}}',NULL,'2025-10-13 12:19:46','2025-10-13 12:19:46'),(199,'unit_of_measurements','updated','App\\Models\\UnitOfMeasurement','updated',9,'App\\Models\\User',11,'{\"old\": {\"status\": 1}, \"attributes\": {\"status\": 0}}',NULL,'2025-10-13 12:19:53','2025-10-13 12:19:53'),(200,'unit_of_measurements','updated','App\\Models\\UnitOfMeasurement','updated',2,'App\\Models\\User',11,'{\"old\": {\"status\": 1, \"updated_by\": 1}, \"attributes\": {\"status\": 0, \"updated_by\": 11}}',NULL,'2025-10-13 12:20:14','2025-10-13 12:20:14'),(201,'unit_of_measurements','created','App\\Models\\UnitOfMeasurement','created',10,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Unit-Based\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 12:20:38','2025-10-13 12:20:38'),(202,'unit_of_measurements','created','App\\Models\\UnitOfMeasurement','created',11,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"FTE\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 12:20:59','2025-10-13 12:20:59'),(203,'unit_of_measurements','created','App\\Models\\UnitOfMeasurement','created',12,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Fixed\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 12:21:21','2025-10-13 12:21:21'),(204,'unit_of_measurements','created','App\\Models\\UnitOfMeasurement','created',13,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Hourly based\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 12:21:57','2025-10-13 12:21:57'),(205,'descriptions','deleted','App\\Models\\Description','deleted',4,'App\\Models\\User',11,'{\"old\": {\"name\": \"Long Abstraction\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 12:25:09','2025-10-13 12:25:09'),(206,'descriptions','deleted','App\\Models\\Description','deleted',3,'App\\Models\\User',11,'{\"old\": {\"name\": \"Only Clauses Abstraction\", \"status\": 1, \"created_by\": 1, \"updated_by\": 1}}',NULL,'2025-10-13 12:25:16','2025-10-13 12:25:16'),(207,'descriptions','deleted','App\\Models\\Description','deleted',1,'App\\Models\\User',11,'{\"old\": {\"name\": \"Full Scope Abstraction\", \"status\": 1, \"created_by\": 1, \"updated_by\": 1}}',NULL,'2025-10-13 12:25:24','2025-10-13 12:25:24'),(208,'industry_verticals','deleted','App\\Models\\IndustryVertical','deleted',6,'App\\Models\\User',11,'{\"old\": {\"name\": \"Test2\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 12:29:09','2025-10-13 12:29:09'),(209,'industry_verticals','updated','App\\Models\\IndustryVertical','updated',5,'App\\Models\\User',11,'{\"old\": {\"status\": 1}, \"attributes\": {\"status\": 0}}',NULL,'2025-10-13 12:29:23','2025-10-13 12:29:23'),(210,'pricing_masters','deleted','App\\Models\\PricingMaster','deleted',12,'App\\Models\\User',11,'{\"old\": {\"name\": \"Property-bill\", \"rate\": \"1.00\", \"status\": true, \"volume\": null, \"currency_id\": 4, \"vendor_cost\": null, \"pricing_type\": \"static\", \"approval_note\": null, \"department_id\": 10, \"document_path\": null, \"description_id\": 5, \"conversion_rate\": null, \"margin_percentage\": null, \"infrastructure_cost\": null, \"overhead_percentage\": null, \"service_offering_id\": 24, \"industry_vertical_id\": 5, \"volume_based_discount\": null, \"unit_of_measurement_id\": 9, \"project_management_cost\": null}}',NULL,'2025-10-13 12:42:21','2025-10-13 12:42:21'),(211,'project_types','updated','App\\Models\\ProjectType','updated',3,'App\\Models\\User',11,'{\"old\": {\"status\": 0}, \"attributes\": {\"status\": 1}}',NULL,'2025-10-13 12:44:05','2025-10-13 12:44:05'),(212,'project_types','updated','App\\Models\\ProjectType','updated',2,'App\\Models\\User',11,'{\"old\": {\"status\": 0}, \"attributes\": {\"status\": 1}}',NULL,'2025-10-13 12:44:29','2025-10-13 12:44:29'),(213,'project_types','updated','App\\Models\\ProjectType','updated',1,'App\\Models\\User',11,'{\"old\": {\"status\": 0}, \"attributes\": {\"status\": 1}}',NULL,'2025-10-13 12:44:34','2025-10-13 12:44:34'),(214,'industry_verticals','created','App\\Models\\IndustryVertical','created',7,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Real Estate Services\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 13:02:53','2025-10-13 13:02:53'),(215,'departments','updated','App\\Models\\Department','updated',1,'App\\Models\\User',11,'{\"old\": {\"name\": \"Lease Abstraction Services\"}, \"attributes\": {\"name\": \"Lease Administration Services\"}}',NULL,'2025-10-13 13:04:54','2025-10-13 13:04:54'),(216,'user','created','App\\Models\\User','created',77,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"abstracotr7@springbord.com\", \"status\": 1, \"password\": \"$2y$12$u24a5jzr2ODw/Ysc2ovyJuzb7eGoeeRFi5Q.mGAnwaFVT/aJ4j9wi\", \"last_name\": \"A\", \"contact_no\": \"8959483890\", \"created_by\": null, \"first_name\": \"abstracotr7\", \"updated_by\": null, \"company_name\": null, \"project_manager\": 46, \"is_password_update\": 0}}',NULL,'2025-10-13 13:05:00','2025-10-13 13:05:00'),(217,'user','deleted','App\\Models\\User','deleted',77,'App\\Models\\User',11,'{\"old\": {\"email\": \"abstracotr7@springbord.com\", \"status\": 1, \"password\": \"$2y$12$u24a5jzr2ODw/Ysc2ovyJuzb7eGoeeRFi5Q.mGAnwaFVT/aJ4j9wi\", \"last_name\": \"A\", \"contact_no\": \"8959483890\", \"created_by\": null, \"first_name\": \"abstracotr7\", \"updated_by\": null, \"company_name\": null, \"project_manager\": 46, \"is_password_update\": 0}}',NULL,'2025-10-13 13:05:12','2025-10-13 13:05:12'),(218,'user','created','App\\Models\\User','created',78,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"abstracotr7@springbord.com\", \"status\": 1, \"password\": \"$2y$12$jyTX/SXOS6DFnnzcIItMFep9/TQDFrKmdayHwvWKhO3V28jFVpuci\", \"last_name\": \"A\", \"contact_no\": \"9876543210\", \"created_by\": null, \"first_name\": \"Abstracotr7\", \"updated_by\": null, \"company_name\": null, \"project_manager\": 46, \"is_password_update\": 0}}',NULL,'2025-10-13 13:05:39','2025-10-13 13:05:39'),(219,'service_offerings','updated','App\\Models\\ServiceOffering','updated',7,'App\\Models\\User',11,'{\"old\": {\"name\": \"Full Lease Abstraction\"}, \"attributes\": {\"name\": \"Full Lease Abstraction - English\"}}',NULL,'2025-10-13 13:07:07','2025-10-13 13:07:07'),(220,'pricing_masters','created','App\\Models\\PricingMaster','created',15,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Standard Pricing\", \"rate\": \"10000.00\", \"status\": true, \"volume\": null, \"currency_id\": 1, \"vendor_cost\": null, \"pricing_type\": \"static\", \"approval_note\": null, \"department_id\": 1, \"document_path\": null, \"description_id\": 2, \"conversion_rate\": null, \"margin_percentage\": null, \"infrastructure_cost\": null, \"overhead_percentage\": null, \"service_offering_id\": 7, \"industry_vertical_id\": 7, \"volume_based_discount\": null, \"unit_of_measurement_id\": 10, \"project_management_cost\": null}}',NULL,'2025-10-13 13:08:26','2025-10-13 13:08:26'),(221,'notes','created','App\\Models\\Note','created',6,'App\\Models\\User',11,'{\"attributes\": {\"price\": \"10000.00\", \"create_by\": 11, \"note_type\": 3, \"description\": null, \"pricing_master_id\": 15, \"approve_rejected_by\": null}}',NULL,'2025-10-13 13:08:26','2025-10-13 13:08:26'),(223,'pricing_masters','updated','App\\Models\\PricingMaster','updated',15,'App\\Models\\User',11,'{\"old\": {\"approval_note\": null}, \"attributes\": {\"approval_note\": \"approve\"}}',NULL,'2025-10-13 13:08:58','2025-10-13 13:08:58'),(224,'notes','updated','App\\Models\\Note','updated',6,'App\\Models\\User',11,'{\"old\": {\"note_type\": 3, \"description\": null, \"approve_rejected_by\": null}, \"attributes\": {\"note_type\": 1, \"description\": \"approve\", \"approve_rejected_by\": 11}}',NULL,'2025-10-13 13:08:58','2025-10-13 13:08:58'),(227,'service_offerings','created','App\\Models\\ServiceOffering','created',25,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Full Lease Abstraction - Tier1\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-13 13:09:56','2025-10-13 13:09:56'),(228,'user','created','App\\Models\\User','created',79,'App\\Models\\User',11,'{\"attributes\": {\"email\": \"rahul1@gmail.com\", \"status\": 1, \"password\": \"$2y$12$4HjuX1A7LUgGZPxSfq3e9.ltOGO9LgiWvcldiTVeK5a.QR/qqJFRm\", \"last_name\": \"A\", \"contact_no\": \"9988776655\", \"created_by\": null, \"first_name\": \"Rahul\", \"updated_by\": null, \"company_name\": null, \"project_manager\": null, \"is_password_update\": 0}}',NULL,'2025-10-13 13:29:45','2025-10-13 13:29:45'),(229,'user','updated','App\\Models\\User','updated',79,'App\\Models\\User',79,'{\"old\": {\"password\": \"$2y$12$4HjuX1A7LUgGZPxSfq3e9.ltOGO9LgiWvcldiTVeK5a.QR/qqJFRm\", \"is_password_update\": 0}, \"attributes\": {\"password\": \"$2y$12$TYcKh.jV0YkAzpahDH75qeGnR3a0D6xuLCmDqOmVoNflfIzU60cUW\", \"is_password_update\": 1}}',NULL,'2025-10-13 13:30:15','2025-10-13 13:30:15'),(230,'user','deleted','App\\Models\\User','deleted',75,'App\\Models\\User',11,'{\"old\": {\"email\": \"pradeep@springbord.com\", \"status\": 1, \"password\": \"$2y$12$FAQm0mlFqxDvhP39v8Aq5eP181Jx6jdKklIcEbFJ6yYQv0E.F3Gri\", \"last_name\": \"K\", \"contact_no\": null, \"created_by\": null, \"first_name\": \"P\", \"updated_by\": null, \"company_name\": null, \"project_manager\": null, \"is_password_update\": 0}}',NULL,'2025-10-13 13:43:11','2025-10-13 13:43:11'),(231,'user','deleted','App\\Models\\User','deleted',78,'App\\Models\\User',11,'{\"old\": {\"email\": \"abstracotr7@springbord.com\", \"status\": 1, \"password\": \"$2y$12$jyTX/SXOS6DFnnzcIItMFep9/TQDFrKmdayHwvWKhO3V28jFVpuci\", \"last_name\": \"A\", \"contact_no\": \"9876543210\", \"created_by\": null, \"first_name\": \"Abstracotr7\", \"updated_by\": null, \"company_name\": null, \"project_manager\": 46, \"is_password_update\": 0}}',NULL,'2025-10-13 13:48:30','2025-10-13 13:48:30'),(232,'banks','created','App\\Models\\Bank','created',2,'App\\Models\\User',44,'{\"attributes\": {\"micr\": \"021000021\", \"entity\": \"ABC Corporation\", \"status\": 1, \"bsr_code\": \"1234\", \"bank_name\": \"ICICI Bank\", \"ifsc_code\": \"ICIC0001234\", \"aba_number\": \"021000021\", \"swift_code\": \"ICICINBBXXX\", \"currency_id\": 1, \"account_name\": \"ABC Corp Operations\", \"account_number\": \"123456789011\", \"branch_address\": \"Chennai\", \"routing_number\": \"021000021\", \"branch_location\": \"Manhattan Branch\"}}',NULL,'2025-10-14 05:27:09','2025-10-14 05:27:09'),(233,'banks','updated','App\\Models\\Bank','updated',2,'App\\Models\\User',44,'{\"old\": {\"currency_id\": 1}, \"attributes\": {\"currency_id\": 4}}',NULL,'2025-10-14 05:27:16','2025-10-14 05:27:16'),(234,'pricing_masters','deleted','App\\Models\\PricingMaster','deleted',9,'App\\Models\\User',11,'{\"old\": {\"name\": \"JKpricing\", \"rate\": \"1.00\", \"status\": true, \"volume\": null, \"currency_id\": 1, \"vendor_cost\": null, \"pricing_type\": \"static\", \"approval_note\": \"approved\", \"department_id\": 1, \"document_path\": null, \"description_id\": 2, \"conversion_rate\": null, \"margin_percentage\": null, \"infrastructure_cost\": null, \"overhead_percentage\": null, \"service_offering_id\": 2, \"industry_vertical_id\": 1, \"volume_based_discount\": null, \"unit_of_measurement_id\": 2, \"project_management_cost\": null}}',NULL,'2025-10-14 05:34:57','2025-10-14 05:34:57'),(235,'industry_verticals','created','App\\Models\\IndustryVertical','created',8,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Lease Administration Services\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-14 06:40:27','2025-10-14 06:40:27'),(236,'industry_verticals','created','App\\Models\\IndustryVertical','created',9,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"CAM Reconciliation Services\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-14 06:40:39','2025-10-14 06:40:39'),(237,'industry_verticals','created','App\\Models\\IndustryVertical','created',10,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Property Accounting Services\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-14 06:40:51','2025-10-14 06:40:51'),(238,'industry_verticals','created','App\\Models\\IndustryVertical','created',11,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Information Services\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-14 06:41:00','2025-10-14 06:41:00'),(239,'industry_verticals','created','App\\Models\\IndustryVertical','created',12,'App\\Models\\User',11,'{\"attributes\": {\"name\": \"Data Processing Services\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-14 06:41:06','2025-10-14 06:41:06'),(240,'currencies','deleted','App\\Models\\Currency','deleted',7,'App\\Models\\User',11,'{\"old\": {\"name\": \"RIG\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11}}',NULL,'2025-10-14 07:26:03','2025-10-14 07:26:03'),(241,'skill_masters','deleted','App\\Models\\SkillMaster','deleted',6,'App\\Models\\User',11,'{\"old\": {\"ctc\": \"40000.00\", \"name\": \"Test Engineer\", \"status\": 1, \"created_by\": 11, \"updated_by\": 11, \"skill_expertise_level\": \"3\"}}',NULL,'2025-10-14 07:26:25','2025-10-14 07:26:25');
/*!40000 ALTER TABLE `activity_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banks`
--

DROP TABLE IF EXISTS `banks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `banks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `entity` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_id` bigint unsigned DEFAULT NULL,
  `account_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ifsc_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `aba_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'US ABA routing (9 digits)',
  `routing_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'US routing number (usually same as ABA)',
  `swift_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `micr` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bsr_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_address` text COLLATE utf8mb4_unicode_ci,
  `bank_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `upi_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pan_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `aadhaar_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '0 = FALSE, 1 = TRUE',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `banks_account_number_unique` (`account_number`),
  KEY `banks_user_id_foreign` (`user_id`),
  KEY `banks_created_by_foreign` (`created_by`),
  KEY `banks_updated_by_foreign` (`updated_by`),
  KEY `banks_currency_id_foreign` (`currency_id`),
  KEY `banks_aba_number_index` (`aba_number`),
  KEY `banks_routing_number_index` (`routing_number`),
  CONSTRAINT `banks_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `banks_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE SET NULL,
  CONSTRAINT `banks_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `banks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banks`
--

LOCK TABLES `banks` WRITE;
/*!40000 ALTER TABLE `banks` DISABLE KEYS */;
INSERT INTO `banks` VALUES (1,'ABC Corporation',1,'ABC Corp','123456789012','HDFC0001234','021000021','021000021','HDFCINBBXXX','021000021','123456789','270 Park Avenue,\r\nNew York, NY 10017,\r\nUnited States','HDFC Bank','Manhattan Branch',NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,'2025-10-13 11:39:52','2025-10-13 11:48:52'),(2,'ABC Corporation',4,'ABC Corp Operations','123456789011','ICIC0001234','021000021','021000021','ICICINBBXXX','021000021','1234','Chennai','ICICI Bank','Manhattan Branch',NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,'2025-10-14 05:27:09','2025-10-14 05:27:16');
/*!40000 ALTER TABLE `banks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('springbord-cache-spatie.permission.cache','a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:132:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:9:\"view role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:11:\"create role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:9:\"edit role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:11:\"delete role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:12:\"view project\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:7:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;i:5;i:6;i:6;i:7;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:14:\"create project\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:12:\"edit project\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:14:\"delete project\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:13:\"view customer\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:15:\"create customer\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:13:\"edit customer\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:15:\"delete customer\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:9:\"view user\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:7;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:11:\"create user\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:9:\"edit user\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:11:\"delete user\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:12:\"view invoice\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:14:\"create invoice\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:7;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:12:\"edit invoice\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:7;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:14:\"delete invoice\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:7;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:12:\"view setting\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:14:\"create setting\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:12:\"edit setting\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:14:\"delete setting\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:15:\"view permission\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:25;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:17:\"create permission\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:26;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:15:\"edit permission\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:27;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:17:\"delete permission\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:28;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:9:\"view bank\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:7;}}i:29;a:4:{s:1:\"a\";i:30;s:1:\"b\";s:11:\"create bank\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:7;}}i:30;a:4:{s:1:\"a\";i:31;s:1:\"b\";s:9:\"edit bank\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:7;}}i:31;a:4:{s:1:\"a\";i:32;s:1:\"b\";s:11:\"delete bank\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:7;}}i:32;a:4:{s:1:\"a\";i:33;s:1:\"b\";s:14:\"view dashboard\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:33;a:4:{s:1:\"a\";i:34;s:1:\"b\";s:18:\"view collaboration\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:6:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;i:5;i:6;}}i:34;a:4:{s:1:\"a\";i:35;s:1:\"b\";s:11:\"view report\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:35;a:4:{s:1:\"a\";i:36;s:1:\"b\";s:19:\"create project type\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:36;a:4:{s:1:\"a\";i:37;s:1:\"b\";s:17:\"edit project type\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:37;a:4:{s:1:\"a\";i:38;s:1:\"b\";s:17:\"view project type\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:38;a:4:{s:1:\"a\";i:39;s:1:\"b\";s:19:\"delete project type\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:39;a:4:{s:1:\"a\";i:40;s:1:\"b\";s:17:\"create department\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:40;a:4:{s:1:\"a\";i:41;s:1:\"b\";s:15:\"view department\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:41;a:4:{s:1:\"a\";i:42;s:1:\"b\";s:15:\"edit department\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:42;a:4:{s:1:\"a\";i:43;s:1:\"b\";s:17:\"delete department\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:43;a:4:{s:1:\"a\";i:44;s:1:\"b\";s:21:\"view mode of delivery\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:44;a:4:{s:1:\"a\";i:45;s:1:\"b\";s:23:\"create mode of delivery\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:45;a:4:{s:1:\"a\";i:46;s:1:\"b\";s:21:\"edit mode of delivery\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:46;a:4:{s:1:\"a\";i:47;s:1:\"b\";s:23:\"delete mode of delivery\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:47;a:4:{s:1:\"a\";i:48;s:1:\"b\";s:25:\"create project priorities\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:48;a:4:{s:1:\"a\";i:49;s:1:\"b\";s:23:\"edit project priorities\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:49;a:4:{s:1:\"a\";i:50;s:1:\"b\";s:23:\"view project priorities\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:50;a:4:{s:1:\"a\";i:51;s:1:\"b\";s:25:\"delete project priorities\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:51;a:4:{s:1:\"a\";i:52;s:1:\"b\";s:21:\"create project status\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:52;a:4:{s:1:\"a\";i:53;s:1:\"b\";s:19:\"view project status\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:53;a:4:{s:1:\"a\";i:54;s:1:\"b\";s:19:\"edit project status\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:54;a:4:{s:1:\"a\";i:55;s:1:\"b\";s:21:\"delete project status\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:55;a:4:{s:1:\"a\";i:56;s:1:\"b\";s:27:\"create delivery frequencies\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:56;a:4:{s:1:\"a\";i:57;s:1:\"b\";s:25:\"view delivery frequencies\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:57;a:4:{s:1:\"a\";i:58;s:1:\"b\";s:25:\"edit delivery frequencies\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:58;a:4:{s:1:\"a\";i:59;s:1:\"b\";s:27:\"delete delivery frequencies\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:59;a:4:{s:1:\"a\";i:60;s:1:\"b\";s:26:\"create input output format\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:60;a:4:{s:1:\"a\";i:61;s:1:\"b\";s:24:\"view input output format\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:61;a:4:{s:1:\"a\";i:62;s:1:\"b\";s:24:\"edit input output format\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:62;a:4:{s:1:\"a\";i:63;s:1:\"b\";s:26:\"delete input output format\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:63;a:4:{s:1:\"a\";i:64;s:1:\"b\";s:24:\"delete industry vertical\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:64;a:4:{s:1:\"a\";i:65;s:1:\"b\";s:22:\"edit industry vertical\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:65;a:4:{s:1:\"a\";i:66;s:1:\"b\";s:22:\"view industry vertical\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:66;a:4:{s:1:\"a\";i:67;s:1:\"b\";s:24:\"create industry vertical\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:67;a:4:{s:1:\"a\";i:68;s:1:\"b\";s:23:\"create service offering\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:68;a:4:{s:1:\"a\";i:69;s:1:\"b\";s:21:\"edit service offering\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:69;a:4:{s:1:\"a\";i:70;s:1:\"b\";s:21:\"view service offering\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:70;a:4:{s:1:\"a\";i:71;s:1:\"b\";s:23:\"delete service offering\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:71;a:4:{s:1:\"a\";i:72;s:1:\"b\";s:26:\"create unit of measurement\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:72;a:4:{s:1:\"a\";i:73;s:1:\"b\";s:24:\"edit unit of measurement\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:73;a:4:{s:1:\"a\";i:74;s:1:\"b\";s:24:\"view unit of measurement\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:74;a:4:{s:1:\"a\";i:75;s:1:\"b\";s:26:\"delete unit of measurement\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:75;a:4:{s:1:\"a\";i:76;s:1:\"b\";s:15:\"create currency\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:76;a:4:{s:1:\"a\";i:77;s:1:\"b\";s:13:\"edit currency\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:77;a:4:{s:1:\"a\";i:78;s:1:\"b\";s:13:\"view currency\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:78;a:4:{s:1:\"a\";i:79;s:1:\"b\";s:15:\"delete currency\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:79;a:4:{s:1:\"a\";i:80;s:1:\"b\";s:18:\"create description\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:80;a:4:{s:1:\"a\";i:81;s:1:\"b\";s:16:\"view description\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:81;a:4:{s:1:\"a\";i:82;s:1:\"b\";s:16:\"edit description\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:82;a:4:{s:1:\"a\";i:83;s:1:\"b\";s:18:\"delete description\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:83;a:4:{s:1:\"a\";i:84;s:1:\"b\";s:21:\"create pricing master\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:84;a:4:{s:1:\"a\";i:85;s:1:\"b\";s:19:\"edit pricing master\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:85;a:4:{s:1:\"a\";i:86;s:1:\"b\";s:19:\"view pricing master\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:86;a:4:{s:1:\"a\";i:87;s:1:\"b\";s:21:\"delete pricing master\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:87;a:4:{s:1:\"a\";i:88;s:1:\"b\";s:22:\"approve pricing master\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:88;a:4:{s:1:\"a\";i:89;s:1:\"b\";s:17:\"view skill master\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:89;a:4:{s:1:\"a\";i:90;s:1:\"b\";s:17:\"edit skill master\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:90;a:4:{s:1:\"a\";i:91;s:1:\"b\";s:19:\"create skill master\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:91;a:4:{s:1:\"a\";i:92;s:1:\"b\";s:19:\"delete skill master\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:92;a:4:{s:1:\"a\";i:93;s:1:\"b\";s:15:\"intake form add\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:93;a:4:{s:1:\"a\";i:94;s:1:\"b\";s:18:\"intake form remove\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:94;a:4:{s:1:\"a\";i:95;s:1:\"b\";s:36:\"view intake form primary information\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:7:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;i:5;i:6;i:6;i:7;}}i:95;a:4:{s:1:\"a\";i:96;s:1:\"b\";s:24:\"view intake form queries\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:6:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;i:5;i:6;}}i:96;a:4:{s:1:\"a\";i:97;s:1:\"b\";s:35:\"view intake form production details\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:2;i:2;i:4;i:3;i:5;i:4;i:6;}}i:97;a:4:{s:1:\"a\";i:98;s:1:\"b\";s:32:\"view intake form billing details\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:7;}}i:98;a:4:{s:1:\"a\";i:99;s:1:\"b\";s:34:\"view intake form customer feedback\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:6:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;i:5;i:6;}}i:99;a:4:{s:1:\"a\";i:100;s:1:\"b\";s:16:\"view intake form\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:6:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;i:5;i:6;}}i:100;a:4:{s:1:\"a\";i:101;s:1:\"b\";s:9:\"view task\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:101;a:4:{s:1:\"a\";i:102;s:1:\"b\";s:11:\"create task\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:102;a:4:{s:1:\"a\";i:103;s:1:\"b\";s:9:\"edit task\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:103;a:4:{s:1:\"a\";i:104;s:1:\"b\";s:11:\"delete task\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:104;a:4:{s:1:\"a\";i:105;s:1:\"b\";s:22:\"view intake lease type\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:105;a:4:{s:1:\"a\";i:106;s:1:\"b\";s:24:\"create intake lease type\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:106;a:4:{s:1:\"a\";i:107;s:1:\"b\";s:22:\"edit intake lease type\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:107;a:4:{s:1:\"a\";i:108;s:1:\"b\";s:24:\"delete intake lease type\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:108;a:4:{s:1:\"a\";i:109;s:1:\"b\";s:21:\"view intake work type\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:109;a:4:{s:1:\"a\";i:110;s:1:\"b\";s:23:\"create intake work type\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:110;a:4:{s:1:\"a\";i:111;s:1:\"b\";s:21:\"edit intake work type\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:111;a:4:{s:1:\"a\";i:112;s:1:\"b\";s:23:\"delete intake work type\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:112;a:4:{s:1:\"a\";i:113;s:1:\"b\";s:20:\"view intake language\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:113;a:4:{s:1:\"a\";i:114;s:1:\"b\";s:22:\"create intake language\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:114;a:4:{s:1:\"a\";i:115;s:1:\"b\";s:20:\"edit intake language\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:115;a:4:{s:1:\"a\";i:116;s:1:\"b\";s:22:\"delete intake language\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:116;a:4:{s:1:\"a\";i:117;s:1:\"b\";s:15:\"delete document\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:117;a:4:{s:1:\"a\";i:118;s:1:\"b\";s:13:\"view document\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:118;a:4:{s:1:\"a\";i:119;s:1:\"b\";s:13:\"edit document\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:119;a:4:{s:1:\"a\";i:120;s:1:\"b\";s:15:\"create document\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:120;a:4:{s:1:\"a\";i:121;s:1:\"b\";s:12:\"list invoice\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:7;}}i:121;a:4:{s:1:\"a\";i:122;s:1:\"b\";s:23:\"finance approve invoice\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:7;}}i:122;a:4:{s:1:\"a\";i:123;s:1:\"b\";s:9:\"create po\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:7;}}i:123;a:4:{s:1:\"a\";i:124;s:1:\"b\";s:7:\"edit po\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:7;}}i:124;a:4:{s:1:\"a\";i:125;s:1:\"b\";s:7:\"view po\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:7;}}i:125;a:4:{s:1:\"a\";i:126;s:1:\"b\";s:9:\"delete po\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:7;}}i:126;a:4:{s:1:\"a\";i:127;s:1:\"b\";s:36:\"edit intake form primary information\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:127;a:4:{s:1:\"a\";i:128;s:1:\"b\";s:24:\"edit intake form queries\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:128;a:4:{s:1:\"a\";i:129;s:1:\"b\";s:35:\"edit intake form production details\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:2;i:2;i:4;i:3;i:5;i:4;i:6;}}i:129;a:4:{s:1:\"a\";i:130;s:1:\"b\";s:32:\"edit intake form billing details\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:7;}}i:130;a:4:{s:1:\"a\";i:131;s:1:\"b\";s:34:\"edit intake form customer feedback\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:131;a:4:{s:1:\"a\";i:132;s:1:\"b\";s:12:\"create query\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}}s:5:\"roles\";a:7:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:11:\"super admin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:15:\"project manager\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:8:\"customer\";s:1:\"c\";s:3:\"web\";}i:3;a:3:{s:1:\"a\";i:4;s:1:\"b\";s:10:\"abstractor\";s:1:\"c\";s:3:\"web\";}i:4;a:3:{s:1:\"a\";i:5;s:1:\"b\";s:8:\"reviewer\";s:1:\"c\";s:3:\"web\";}i:5;a:3:{s:1:\"a\";i:6;s:1:\"b\";s:11:\"sense check\";s:1:\"c\";s:3:\"web\";}i:6;a:3:{s:1:\"a\";i:7;s:1:\"b\";s:12:\"finance team\";s:1:\"c\";s:3:\"web\";}}}',1760511485);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `companies`
--

DROP TABLE IF EXISTS `companies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `companies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `zip_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Postal/ZIP code',
  `company_type` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '1=Indian, 2=Non-Indian',
  `invoice_type` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '1 = India, 2 = US',
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 = Active, 2 = Inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `companies_company_type_idx` (`company_type`),
  KEY `companies_zip_code_idx` (`zip_code`),
  CONSTRAINT `chk_companies_company_type` CHECK ((`company_type` in (1,2)))
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `companies`
--

LOCK TABLES `companies` WRITE;
/*!40000 ALTER TABLE `companies` DISABLE KEYS */;
INSERT INTO `companies` VALUES (1,'Briskstar',NULL,1,1,'404-405 Above starbazar','Ahmedabad','9409266722','briskstar.com',1,'2025-08-25 04:15:48','2025-08-25 04:15:48'),(2,'Customer1',NULL,1,1,'123 Main street','Ind','8978967890','www.springbord.com',1,'2025-09-29 09:11:17','2025-09-29 09:11:17'),(3,'John Doe',NULL,1,1,'123 Maple Street','USA','+1-202-555-0143','www.springbord.com',1,'2025-09-30 09:32:46','2025-09-30 09:32:46'),(4,'JKcustomer',NULL,1,1,'289303 dkdkfi9',NULL,'38389348','springbord.com',1,'2025-10-01 06:22:10','2025-10-01 06:22:10'),(5,'Acme Solutions Pvt Ltd','560001',1,1,'123 Green Street, MG Road','Bengaluru, Karnataka, India','9876543210','www.acmesolutions.com',1,'2025-10-07 09:40:13','2025-10-07 10:56:24'),(6,'Global Tech Solutions Ltd','94105',2,1,'456 Silicon Avenue, Suite 200','San Francisco, California, USA','4159876543','www.globaltech.com',1,'2025-10-07 10:41:08','2025-10-07 10:41:08'),(7,'Prime Innovations',NULL,2,1,'11120 KENWOOD ROAD, Cinncinnati, OH, United States','USA','8959483890','www.springbord.com',1,'2025-10-08 07:09:44','2025-10-08 07:09:44'),(8,'Acme Corp',NULL,2,1,'123 Green Street, MG Road',NULL,NULL,'www.springbord.com',1,'2025-10-08 07:10:31','2025-10-08 07:10:31'),(9,'Stellar','85602',2,1,'123, Main Street','IND','9876543210','www.springbord.com',1,'2025-10-08 09:27:06','2025-10-08 09:27:06'),(10,'Omega',NULL,2,1,'123 Main street','USA','9876543210',NULL,1,'2025-10-09 04:26:49','2025-10-09 04:27:09'),(11,'Vertex',NULL,2,1,'123 Main street',NULL,NULL,NULL,1,'2025-10-09 05:22:13','2025-10-09 05:22:57'),(12,'Omega1','560033',1,1,'abc','NY','28398543','springbord.com',1,'2025-10-09 05:55:01','2025-10-09 05:55:01');
/*!40000 ALTER TABLE `companies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_project`
--

DROP TABLE IF EXISTS `contact_project`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_project` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `contact_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `contact_project_project_id_contact_id_unique` (`project_id`,`contact_id`),
  KEY `contact_project_contact_id_foreign` (`contact_id`),
  CONSTRAINT `contact_project_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `contact_project_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_project`
--

LOCK TABLES `contact_project` WRITE;
/*!40000 ALTER TABLE `contact_project` DISABLE KEYS */;
INSERT INTO `contact_project` VALUES (1,1,32,'2025-10-08 07:23:32','2025-10-08 07:23:32'),(2,2,45,'2025-10-08 09:48:22','2025-10-08 09:48:22'),(3,3,45,'2025-10-08 09:54:17','2025-10-08 09:54:17'),(4,4,45,'2025-10-09 04:10:51','2025-10-09 04:10:51'),(5,5,56,'2025-10-09 04:39:23','2025-10-09 04:39:23'),(6,6,62,'2025-10-09 05:23:46','2025-10-09 05:23:46'),(7,7,63,'2025-10-09 06:15:31','2025-10-09 06:15:31'),(8,8,63,'2025-10-09 12:43:25','2025-10-09 12:43:25'),(9,9,63,'2025-10-10 05:11:44','2025-10-10 05:11:44'),(10,10,56,'2025-10-10 10:59:27','2025-10-10 10:59:27'),(11,9,73,'2025-10-10 11:21:42','2025-10-10 11:21:42'),(12,11,12,'2025-10-13 11:07:46','2025-10-13 11:07:46'),(14,13,45,'2025-10-13 13:15:25','2025-10-13 13:15:25'),(15,12,79,'2025-10-13 13:30:29','2025-10-13 13:30:29');
/*!40000 ALTER TABLE `contact_project` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `conversation_stats`
--

DROP TABLE IF EXISTS `conversation_stats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `conversation_stats` (
  `conversation_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message_count` bigint unsigned NOT NULL DEFAULT '0',
  `last_message_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_message_preview` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_message_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`conversation_id`),
  CONSTRAINT `conversation_stats_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conversation_stats`
--

LOCK TABLES `conversation_stats` WRITE;
/*!40000 ALTER TABLE `conversation_stats` DISABLE KEYS */;
INSERT INTO `conversation_stats` VALUES ('5d4788fd-fc33-4366-b8b7-ba28979ac07a',2,'4a19f010-ec2e-4796-8739-fc996c91e450','Test2','2025-10-08 11:32:03','2025-10-08 11:32:03'),('6c6c4be7-cfa1-4eda-aeaa-63f7923287b2',5,'ef7a69ba-caf6-46e4-8fda-c58ddf14d4ad','TestSense','2025-10-09 05:11:41','2025-10-09 05:11:41'),('82e07f86-0194-4e75-9a37-c6427bb47904',2,'0095877e-b2ad-4cc1-b139-642cb963f622','Test2','2025-10-08 08:00:25','2025-10-08 08:00:25'),('d6171261-64a2-4352-b344-08543d6ad602',3,'3bd9afa7-4a1e-4fe5-b071-04b920ada277','Test from REview','2025-10-09 12:11:52','2025-10-09 12:11:52'),('d764eefc-6075-419b-b2df-357ed5156ed6',2,'ed4fbeba-f24b-4195-b075-c5fd89503f90','abc','2025-10-13 06:15:21','2025-10-13 06:15:21');
/*!40000 ALTER TABLE `conversation_stats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `conversations`
--

DROP TABLE IF EXISTS `conversations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `conversations` (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_id` bigint unsigned NOT NULL,
  `is_locked` tinyint(1) NOT NULL DEFAULT '0',
  `last_message_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_message_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `conversations_project_id_unique` (`project_id`),
  KEY `conversations_last_message_at_index` (`last_message_at`),
  CONSTRAINT `conversations_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conversations`
--

LOCK TABLES `conversations` WRITE;
/*!40000 ALTER TABLE `conversations` DISABLE KEYS */;
INSERT INTO `conversations` VALUES ('11d215bd-2615-4480-992a-17e4c5c41421',12,0,NULL,NULL,'2025-10-13 13:42:03','2025-10-13 13:42:03',NULL),('5d4788fd-fc33-4366-b8b7-ba28979ac07a',3,0,'4a19f010-ec2e-4796-8739-fc996c91e450','2025-10-08 11:32:03','2025-10-08 10:23:21','2025-10-08 11:32:03',NULL),('6c6c4be7-cfa1-4eda-aeaa-63f7923287b2',4,0,'ef7a69ba-caf6-46e4-8fda-c58ddf14d4ad','2025-10-09 05:11:41','2025-10-09 04:27:30','2025-10-09 05:11:41',NULL),('72c339f5-9a9e-4332-8d62-d1a801c0f8f9',10,0,NULL,NULL,'2025-10-13 05:32:37','2025-10-13 05:32:37',NULL),('82e07f86-0194-4e75-9a37-c6427bb47904',1,0,'0095877e-b2ad-4cc1-b139-642cb963f622','2025-10-08 08:00:25','2025-10-08 07:59:53','2025-10-08 08:00:25',NULL),('d6171261-64a2-4352-b344-08543d6ad602',7,0,'3bd9afa7-4a1e-4fe5-b071-04b920ada277','2025-10-09 12:11:52','2025-10-09 06:31:57','2025-10-09 12:11:52',NULL),('d764eefc-6075-419b-b2df-357ed5156ed6',9,0,'ed4fbeba-f24b-4195-b075-c5fd89503f90','2025-10-13 06:15:21','2025-10-10 06:41:18','2025-10-13 06:15:21',NULL),('e9dc16c2-c894-4c32-bb11-0e71f5124f16',5,0,NULL,NULL,'2025-10-09 05:11:59','2025-10-09 05:11:59',NULL);
/*!40000 ALTER TABLE `conversations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `currencies`
--

DROP TABLE IF EXISTS `currencies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `currencies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `currencies_created_by_foreign` (`created_by`),
  KEY `currencies_updated_by_foreign` (`updated_by`),
  CONSTRAINT `currencies_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `currencies_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `currencies`
--

LOCK TABLES `currencies` WRITE;
/*!40000 ALTER TABLE `currencies` DISABLE KEYS */;
INSERT INTO `currencies` VALUES (1,'USD',1,1,1,'2025-08-25 03:59:04','2025-08-25 03:59:04'),(2,'EUR',1,1,1,'2025-08-25 03:59:04','2025-08-25 03:59:04'),(3,'GBP',1,1,1,'2025-08-25 03:59:04','2025-08-25 03:59:04'),(4,'IND',1,1,1,'2025-08-25 03:59:04','2025-08-25 03:59:04');
/*!40000 ALTER TABLE `currencies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer_approval_statuses`
--

DROP TABLE IF EXISTS `customer_approval_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_approval_statuses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_approval_statuses_created_by_foreign` (`created_by`),
  KEY `customer_approval_statuses_updated_by_foreign` (`updated_by`),
  CONSTRAINT `customer_approval_statuses_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `customer_approval_statuses_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_approval_statuses`
--

LOCK TABLES `customer_approval_statuses` WRITE;
/*!40000 ALTER TABLE `customer_approval_statuses` DISABLE KEYS */;
INSERT INTO `customer_approval_statuses` VALUES (1,'Pending',1,NULL,NULL,'2025-08-25 03:58:57','2025-08-25 03:58:57'),(2,'Approved',1,NULL,NULL,'2025-08-25 03:58:57','2025-08-25 03:58:57'),(3,'Unapproved',1,NULL,NULL,'2025-08-25 03:58:57','2025-08-25 03:58:57');
/*!40000 ALTER TABLE `customer_approval_statuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `departments_created_by_foreign` (`created_by`),
  KEY `departments_updated_by_foreign` (`updated_by`),
  CONSTRAINT `departments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `departments_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES (1,'Lease Administration Services',1,1,11,'2025-08-25 04:30:38','2025-10-13 13:04:54'),(6,'CAM Reconciliation Services',1,11,11,'2025-10-13 11:57:25','2025-10-13 11:57:25'),(7,'Property Accounting Services',1,11,11,'2025-10-13 11:57:43','2025-10-13 11:57:43'),(8,'Information Services',1,11,11,'2025-10-13 11:57:59','2025-10-13 11:57:59'),(9,'Data Processing Services',1,11,11,'2025-10-13 11:58:21','2025-10-13 11:58:21'),(10,'Test Department',1,11,11,'2025-10-13 12:09:17','2025-10-13 12:09:17');
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `descriptions`
--

DROP TABLE IF EXISTS `descriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `descriptions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `descriptions_created_by_foreign` (`created_by`),
  KEY `descriptions_updated_by_foreign` (`updated_by`),
  CONSTRAINT `descriptions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `descriptions_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `descriptions`
--

LOCK TABLES `descriptions` WRITE;
/*!40000 ALTER TABLE `descriptions` DISABLE KEYS */;
INSERT INTO `descriptions` VALUES (2,'Limited Scope Abstraction',1,1,1,'2025-08-25 03:59:05','2025-08-25 03:59:05'),(5,'Test Finance',1,11,11,'2025-10-13 12:00:17','2025-10-13 12:00:17');
/*!40000 ALTER TABLE `descriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint unsigned DEFAULT NULL,
  `contact_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `contract_start_date` date NOT NULL,
  `contract_end_date` date NOT NULL,
  `project_manager_id` bigint unsigned DEFAULT NULL,
  `industry_vertical_id` bigint unsigned DEFAULT NULL,
  `department_id` bigint unsigned DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 = Active, 0 = Inactive',
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `documents_customer_id_foreign` (`customer_id`),
  KEY `documents_project_manager_id_foreign` (`project_manager_id`),
  KEY `documents_industry_vertical_id_foreign` (`industry_vertical_id`),
  KEY `documents_department_id_foreign` (`department_id`),
  CONSTRAINT `documents_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `companies` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `documents_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `documents_industry_vertical_id_foreign` FOREIGN KEY (`industry_vertical_id`) REFERENCES `industry_verticals` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `documents_project_manager_id_foreign` FOREIGN KEY (`project_manager_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documents`
--

LOCK TABLES `documents` WRITE;
/*!40000 ALTER TABLE `documents` DISABLE KEYS */;
/*!40000 ALTER TABLE `documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `feedback_categories`
--

DROP TABLE IF EXISTS `feedback_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `feedback_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `feedback_categories_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feedback_categories`
--

LOCK TABLES `feedback_categories` WRITE;
/*!40000 ALTER TABLE `feedback_categories` DISABLE KEYS */;
INSERT INTO `feedback_categories` VALUES (1,'Critical','2025-09-10 05:34:47','2025-09-10 05:34:47'),(2,'Non-Critical','2025-09-10 05:34:47','2025-09-10 05:34:47');
/*!40000 ALTER TABLE `feedback_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `industry_verticals`
--

DROP TABLE IF EXISTS `industry_verticals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `industry_verticals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `industry_verticals_created_by_foreign` (`created_by`),
  KEY `industry_verticals_updated_by_foreign` (`updated_by`),
  CONSTRAINT `industry_verticals_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `industry_verticals_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `industry_verticals`
--

LOCK TABLES `industry_verticals` WRITE;
/*!40000 ALTER TABLE `industry_verticals` DISABLE KEYS */;
INSERT INTO `industry_verticals` VALUES (1,'Real Estate',0,NULL,11,'2025-08-25 03:58:58','2025-10-13 11:52:46'),(5,'Test1',0,11,11,'2025-10-13 11:58:05','2025-10-13 12:29:23'),(7,'Real Estate Services',1,11,11,'2025-10-13 13:02:53','2025-10-13 13:02:53'),(8,'Lease Administration Services',1,11,11,'2025-10-14 06:40:27','2025-10-14 06:40:27'),(9,'CAM Reconciliation Services',1,11,11,'2025-10-14 06:40:39','2025-10-14 06:40:39'),(10,'Property Accounting Services',1,11,11,'2025-10-14 06:40:51','2025-10-14 06:40:51'),(11,'Information Services',1,11,11,'2025-10-14 06:41:00','2025-10-14 06:41:00'),(12,'Data Processing Services',1,11,11,'2025-10-14 06:41:06','2025-10-14 06:41:06');
/*!40000 ALTER TABLE `industry_verticals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `input_output_formats`
--

DROP TABLE IF EXISTS `input_output_formats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `input_output_formats` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `input_output_formats_name_unique` (`name`),
  KEY `input_output_formats_created_by_foreign` (`created_by`),
  KEY `input_output_formats_updated_by_foreign` (`updated_by`),
  CONSTRAINT `input_output_formats_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `input_output_formats_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `input_output_formats`
--

LOCK TABLES `input_output_formats` WRITE;
/*!40000 ALTER TABLE `input_output_formats` DISABLE KEYS */;
INSERT INTO `input_output_formats` VALUES (1,'PDF',1,NULL,NULL,'2025-08-25 03:58:57','2025-08-25 03:58:57'),(2,'Excel',1,NULL,NULL,'2025-08-25 03:58:57','2025-08-25 03:58:57'),(3,'CSV',1,NULL,NULL,'2025-08-25 03:58:57','2025-08-25 03:58:57'),(5,'PNG',1,11,11,'2025-10-10 04:05:47','2025-10-10 04:05:47');
/*!40000 ALTER TABLE `input_output_formats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `intake_languages`
--

DROP TABLE IF EXISTS `intake_languages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `intake_languages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `intake_languages_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `intake_languages`
--

LOCK TABLES `intake_languages` WRITE;
/*!40000 ALTER TABLE `intake_languages` DISABLE KEYS */;
INSERT INTO `intake_languages` VALUES (1,'Dutch','2025-09-10 03:57:28','2025-09-10 03:57:28'),(2,'Polish','2025-09-10 03:57:28','2025-09-10 03:57:28'),(3,'Swedish','2025-09-10 03:57:28','2025-09-10 03:57:28'),(4,'Romanian','2025-09-10 03:57:28','2025-09-10 03:57:28'),(5,'Russian','2025-09-10 03:57:28','2025-09-10 03:57:28'),(6,'Turkish','2025-09-10 03:57:28','2025-09-10 03:57:28'),(7,'Norwegian','2025-09-10 03:57:28','2025-09-10 03:57:28'),(8,'Czech','2025-09-10 03:57:28','2025-09-10 03:57:28'),(9,'Malay','2025-09-10 03:57:28','2025-09-10 03:57:28'),(10,'Indonesian','2025-09-10 03:57:28','2025-09-10 03:57:28'),(11,'Ukrainian','2025-09-10 03:57:28','2025-09-10 03:57:28'),(12,'Finnish','2025-09-10 03:57:28','2025-09-10 03:57:28'),(13,'Slovak','2025-09-10 03:57:28','2025-09-10 03:57:28'),(14,'Crotian','2025-09-10 03:57:28','2025-09-10 03:57:28'),(15,'Danish','2025-09-10 03:57:28','2025-09-10 03:57:28'),(16,'Arabic','2025-09-10 03:57:28','2025-09-10 03:57:28'),(17,'Hebrew','2025-09-10 03:57:28','2025-09-10 03:57:28'),(18,'Chinese','2025-09-10 03:57:28','2025-09-10 03:57:28'),(19,'Japanese','2025-09-10 03:57:28','2025-09-10 03:57:28'),(20,'Greek','2025-09-10 03:57:28','2025-09-10 03:57:28'),(21,'Mandarin','2025-09-10 03:57:28','2025-09-10 03:57:28'),(22,'Vietnamese','2025-09-10 03:57:28','2025-09-10 03:57:28'),(23,'Thai','2025-09-10 03:57:28','2025-09-10 03:57:28'),(24,'Cantonese','2025-09-10 03:57:28','2025-09-10 03:57:28'),(25,'Armenian','2025-09-10 03:57:28','2025-09-10 03:57:28'),(26,'Korean','2025-09-10 03:57:28','2025-09-10 03:57:28'),(30,'English','2025-10-08 07:27:38','2025-10-08 07:27:38'),(31,'French','2025-10-08 07:27:38','2025-10-08 07:27:38'),(32,'German','2025-10-08 07:27:38','2025-10-08 07:27:38'),(33,'Spanish','2025-10-08 07:27:38','2025-10-08 07:27:38'),(34,'Italian','2025-10-08 07:27:38','2025-10-08 07:27:38'),(35,'Portuguese','2025-10-08 07:27:38','2025-10-08 07:27:38'),(37,'Lang','2025-10-10 04:17:36','2025-10-10 04:17:36');
/*!40000 ALTER TABLE `intake_languages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `intake_lease_types`
--

DROP TABLE IF EXISTS `intake_lease_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `intake_lease_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `intake_lease_types_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `intake_lease_types`
--

LOCK TABLES `intake_lease_types` WRITE;
/*!40000 ALTER TABLE `intake_lease_types` DISABLE KEYS */;
INSERT INTO `intake_lease_types` VALUES (1,'Lease','2025-09-10 03:57:29','2025-09-10 03:57:29'),(2,'Sublease','2025-09-10 03:57:29','2025-09-10 03:57:29'),(3,'Owned','2025-09-10 03:57:29','2025-09-10 03:57:29'),(4,'Ground Lease','2025-09-10 03:57:29','2025-09-10 03:57:29');
/*!40000 ALTER TABLE `intake_lease_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `intake_queries`
--

DROP TABLE IF EXISTS `intake_queries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `intake_queries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `intake_id` bigint unsigned NOT NULL,
  `type_of_queries_id` bigint unsigned DEFAULT NULL,
  `query_status_id` bigint unsigned DEFAULT NULL,
  `sb_queries` text COLLATE utf8mb4_unicode_ci,
  `client_response` text COLLATE utf8mb4_unicode_ci,
  `query_raised_date` date DEFAULT NULL,
  `query_resolved_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `intake_queries_intake_id_query_status_id_index` (`intake_id`,`query_status_id`),
  KEY `intake_queries_intake_id_index` (`intake_id`),
  KEY `intake_queries_type_of_queries_id_index` (`type_of_queries_id`),
  KEY `intake_queries_query_status_id_index` (`query_status_id`),
  KEY `intake_queries_query_raised_date_index` (`query_raised_date`),
  KEY `intake_queries_query_resolved_date_index` (`query_resolved_date`),
  KEY `intake_queries_created_by_index` (`created_by`),
  KEY `intake_queries_updated_by_index` (`updated_by`),
  CONSTRAINT `intake_queries_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `intake_queries_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `intake_queries`
--

LOCK TABLES `intake_queries` WRITE;
/*!40000 ALTER TABLE `intake_queries` DISABLE KEYS */;
INSERT INTO `intake_queries` VALUES (1,69,69,213,2,1,'example of query by Sense',NULL,'2025-10-09','2025-10-09','2025-10-09 13:15:01','2025-10-09 13:15:01'),(2,69,69,213,3,1,'second query',NULL,'2025-10-09','2025-10-09','2025-10-09 13:15:01','2025-10-09 13:15:01'),(3,66,66,257,3,1,'test on pagedocoment',NULL,'2025-10-13',NULL,'2025-10-13 06:08:00','2025-10-13 06:08:49'),(4,47,53,258,2,1,'Page values needed in table',NULL,'2025-10-13',NULL,'2025-10-13 07:24:42','2025-10-13 07:29:11'),(5,79,79,338,2,2,'Test','Ok','2025-10-13','2025-10-13','2025-10-13 13:32:26','2025-10-13 13:32:26');
/*!40000 ALTER TABLE `intake_queries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `intake_query_types`
--

DROP TABLE IF EXISTS `intake_query_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `intake_query_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `intake_query_types_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `intake_query_types`
--

LOCK TABLES `intake_query_types` WRITE;
/*!40000 ALTER TABLE `intake_query_types` DISABLE KEYS */;
INSERT INTO `intake_query_types` VALUES (1,'CD Contingent',NULL,NULL),(2,'Pages missing',NULL,NULL),(3,'Document Missing',NULL,NULL),(4,'Unexecuted documents',NULL,NULL),(5,'Term Expired',NULL,NULL);
/*!40000 ALTER TABLE `intake_query_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `intake_statuses`
--

DROP TABLE IF EXISTS `intake_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `intake_statuses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `intake_statuses_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `intake_statuses`
--

LOCK TABLES `intake_statuses` WRITE;
/*!40000 ALTER TABLE `intake_statuses` DISABLE KEYS */;
INSERT INTO `intake_statuses` VALUES (1,'Delivered','2025-09-10 02:08:19','2025-09-10 02:08:19'),(2,'Do not Abstract','2025-09-10 02:08:19','2025-09-10 02:08:19'),(3,'On-Hold','2025-09-10 02:08:19','2025-09-10 02:08:19'),(4,'Duplicate','2025-09-10 02:08:19','2025-09-10 02:08:19');
/*!40000 ALTER TABLE `intake_statuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `intake_work_types`
--

DROP TABLE IF EXISTS `intake_work_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `intake_work_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `intake_work_types_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `intake_work_types`
--

LOCK TABLES `intake_work_types` WRITE;
/*!40000 ALTER TABLE `intake_work_types` DISABLE KEYS */;
INSERT INTO `intake_work_types` VALUES (1,'Full Abstraction','2025-09-10 03:57:29','2025-09-10 03:57:29'),(2,'Partial Abstraction','2025-09-10 03:57:29','2025-09-10 03:57:29'),(3,'Limited Scope','2025-09-10 03:57:29','2025-09-10 03:57:29'),(4,'Dates & Dollars','2025-09-10 03:57:29','2025-09-10 03:57:29'),(5,'Validation','2025-09-10 03:57:29','2025-09-10 03:57:29'),(6,'Clauses','2025-09-10 03:57:29','2025-09-10 03:57:29'),(7,'Migration','2025-09-10 03:57:29','2025-09-10 03:57:29'),(8,'Incorporations / Modified Abstract','2025-09-10 03:57:29','2025-09-10 03:57:29'),(9,'Recovery Setup','2025-09-10 03:57:29','2025-09-10 03:57:29'),(10,'Property Setup','2025-09-10 03:57:29','2025-09-10 03:57:29'),(11,'Lease Setup','2025-09-10 03:57:29','2025-09-10 03:57:29'),(12,'Translation','2025-09-10 03:57:29','2025-09-10 03:57:29'),(13,'Multi lingual Abstract','2025-09-10 03:57:29','2025-09-10 03:57:29'),(15,'Testing','2025-10-10 04:15:21','2025-10-10 04:15:21');
/*!40000 ALTER TABLE `intake_work_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoice_formats`
--

DROP TABLE IF EXISTS `invoice_formats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoice_formats` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoice_formats_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice_formats`
--

LOCK TABLES `invoice_formats` WRITE;
/*!40000 ALTER TABLE `invoice_formats` DISABLE KEYS */;
INSERT INTO `invoice_formats` VALUES (1,'Property-wise','2025-09-10 05:34:47','2025-09-10 05:34:47'),(2,'Tenant-wise','2025-09-10 05:34:47','2025-09-10 05:34:47'),(3,'Monthly Completion','2025-09-10 05:34:47','2025-09-10 05:34:47'),(4,'One-time project','2025-09-10 05:34:47','2025-09-10 05:34:47'),(5,'Property Manager','2025-09-10 05:34:47','2025-09-10 05:34:47');
/*!40000 ALTER TABLE `invoice_formats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoice_lines`
--

DROP TABLE IF EXISTS `invoice_lines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoice_lines` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint unsigned NOT NULL,
  `project_id` bigint unsigned NOT NULL,
  `billing_month` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sno` int unsigned NOT NULL DEFAULT '1',
  `description` text COLLATE utf8mb4_unicode_ci,
  `sac` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qty` decimal(14,2) NOT NULL DEFAULT '1.00',
  `rate` decimal(14,2) NOT NULL DEFAULT '0.00',
  `value` decimal(14,2) NOT NULL DEFAULT '0.00',
  `source_intake_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_inv_lines_project_intake_month` (`project_id`,`source_intake_id`,`billing_month`,`deleted_at`),
  KEY `invoice_lines_invoice_id_index` (`invoice_id`),
  KEY `invoice_lines_project_id_index` (`project_id`),
  KEY `invoice_lines_billing_month_index` (`billing_month`),
  KEY `invoice_lines_source_intake_id_index` (`source_intake_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice_lines`
--

LOCK TABLES `invoice_lines` WRITE;
/*!40000 ALTER TABLE `invoice_lines` DISABLE KEYS */;
INSERT INTO `invoice_lines` VALUES (1,1,1,'2025-10',1,'- (WA-EVE-LSE-711 - 100th Street) — ID: -',NULL,1.00,1.00,1.00,61,'2025-10-08 08:06:13','2025-10-08 08:06:13',NULL),(2,1,1,'2025-10',2,'- (CO-FOR-LSE-430 North college Avenue) — ID: -',NULL,1.00,1.00,1.00,62,'2025-10-08 08:06:13','2025-10-08 08:06:13',NULL),(3,1,1,'2025-10',3,'- (CO-LIT-LSE-300 Plaza Drive, Suite 320) — ID: 1',NULL,1.00,1.00,1.00,63,'2025-10-08 08:06:13','2025-10-08 08:06:13',NULL),(4,1,1,'2025-10',4,'- (PA-WEX-7000 Brooktree Road) — ID: 47',NULL,1.00,1.00,1.00,64,'2025-10-08 08:06:13','2025-10-08 08:06:13',NULL),(5,1,1,'2025-10',5,'- (PA-WBR-LSE-613 Baltimore Drive) — ID: 4',NULL,1.00,1.00,1.00,65,'2025-10-08 08:06:13','2025-10-08 08:06:13',NULL),(6,2,7,'2025-10',1,'- (Best Buy Mobile) — ID: LR',NULL,1.00,1.00,1.00,213,'2025-10-09 09:24:17','2025-10-09 09:24:17',NULL),(7,3,9,'2025-10',1,'- (Best Buy Mobile) — ID: LR',NULL,1.00,1.00,1.00,257,'2025-10-13 06:24:57','2025-10-13 06:24:57',NULL),(8,4,9,'2025-10',1,'- (Best Buy Mobile) — ID: LR',NULL,1.00,1.00,1.00,256,'2025-10-13 10:25:24','2025-10-13 10:25:24',NULL),(9,5,4,'10-2025',1,'- (MGC Southeast Inc.) — ID: 247',NULL,1.00,1.00,1.00,262,'2025-10-13 12:22:46','2025-10-13 12:22:46',NULL),(10,6,12,'10-2025',1,'- (La Michoacana Paleteria & Bakery Springfield, LLC) — ID: 247',NULL,1.00,1.00,1.00,341,'2025-10-13 13:19:27','2025-10-13 13:19:27',NULL),(11,7,12,'09-2025',1,'- (MGC Southeast Inc.) — ID: 247',NULL,1.00,1.00,1.00,342,'2025-10-14 04:55:12','2025-10-14 04:55:12',NULL),(12,8,12,'09-2025',1,'- (Select Comfort Retail Corporation dba Sleep Number) — ID: 238',NULL,1.00,1.00,1.00,340,'2025-10-14 05:29:11','2025-10-14 05:29:11',NULL),(13,9,12,'10-2025',1,'- (Route 65, LLC) — ID: 238',NULL,1.00,1.00,1.00,339,'2025-10-14 06:06:29','2025-10-14 06:06:29',NULL),(14,10,12,'08-2025',1,'- (Greenvue Title & Escrow, LLC) — ID: 247',NULL,1.00,1.00,1.00,343,'2025-10-14 07:45:29','2025-10-14 07:45:29',NULL);
/*!40000 ALTER TABLE `invoice_lines` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `customer_id` bigint unsigned DEFAULT NULL,
  `bank_id` bigint unsigned DEFAULT NULL,
  `billing_month` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `po_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `status` enum('draft','submitted','finance_approved','sent','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'submitted',
  `payment_completed` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` bigint unsigned DEFAULT NULL,
  `assigned_to` bigint unsigned DEFAULT NULL,
  `currency_id` bigint unsigned DEFAULT NULL,
  `currency_name` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_symbol` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtotal` decimal(14,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(14,2) NOT NULL DEFAULT '0.00',
  `total` decimal(14,2) NOT NULL DEFAULT '0.00',
  `gross_total` decimal(14,2) NOT NULL DEFAULT '0.00',
  `discount_total` decimal(14,2) NOT NULL DEFAULT '0.00',
  `tax_total` decimal(14,2) NOT NULL DEFAULT '0.00',
  `net_total` decimal(14,2) NOT NULL DEFAULT '0.00',
  `finance_notes` text COLLATE utf8mb4_unicode_ci COMMENT 'Internal finance notes for this invoice',
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_address` text COLLATE utf8mb4_unicode_ci,
  `company_pan` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_gstin` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_lut_no` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_iec` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_reference_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_signatory` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_address` text COLLATE utf8mb4_unicode_ci,
  `customer_zipcode` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Snapshot of customer postal/ZIP at invoice time',
  `customer_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_type` tinyint unsigned NOT NULL COMMENT '1 = India, 2 = US',
  `description` text COLLATE utf8mb4_unicode_ci,
  `pdf_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pdf_generated_at` timestamp NULL DEFAULT NULL,
  `sac_number` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'SAC (Services Accounting Code) for this invoice',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoices_invoice_no_unique` (`invoice_no`),
  KEY `invoices_project_id_index` (`project_id`),
  KEY `invoices_customer_id_index` (`customer_id`),
  KEY `invoices_billing_month_index` (`billing_month`),
  KEY `invoices_status_index` (`status`),
  KEY `invoices_created_by_index` (`created_by`),
  KEY `invoices_assigned_to_index` (`assigned_to`),
  KEY `invoices_currency_id_index` (`currency_id`),
  KEY `invoices_bank_id_foreign` (`bank_id`),
  KEY `invoices_customer_zipcode_idx` (`customer_zipcode`),
  CONSTRAINT `invoices_bank_id_foreign` FOREIGN KEY (`bank_id`) REFERENCES `banks` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
INSERT INTO `invoices` VALUES (1,1,7,NULL,'2025-10','','SBS-25-26-0001','2025-10-08','2025-10-15','submitted',0,34,NULL,1,'USD','$',5.00,0.00,5.00,5.00,0.00,0.00,5.00,NULL,'Springbord Systems Private Limited','12th Floor, Phase - II, TICEL BIO PARK,\nModel No.: 1203 No 5, CSIR Road, Taramani,\nChennai - 600 013 Tamil Nadu, India. Tel: +91-044-2225-9700\nGSTIN: 33AAWCS8726L1ZH','AAWCS8726L','33AAWCS8726L1ZH','AD330425013610P - SUPPLY MEANT FOR EXPORT UNDER LETTER OF UNDERTAKING (LUT) WITHOUT PAYMENT OF INTEGRATED TAX\n','0416903703','82712804','Ranjith Kumar R','Prime Innovations','11120 KENWOOD ROAD, Cinncinnati, OH, United States','USA ','2',1,NULL,NULL,NULL,NULL,'2025-10-08 08:06:13','2025-10-08 08:06:13',NULL,34),(2,7,12,NULL,'2025-10','','SB-25-26-0001','2025-10-09','2025-10-16','rejected',0,11,NULL,1,'USD','$',1.00,0.00,1.18,1.00,0.00,0.18,1.18,'numbers are not correct','Springbord Systems Private Limited','12th Floor, Phase - II, TICEL BIO PARK,\nModel No.: 1203 No 5, CSIR Road, Taramani,\nChennai - 600 013 Tamil Nadu, India. Tel: +91-044-2225-9700\nGSTIN: 33AAWCS8726L1ZH','AAWCS8726L','33AAWCS8726L1ZH','AD330425013610P - SUPPLY MEANT FOR EXPORT UNDER LETTER OF UNDERTAKING (LUT) WITHOUT PAYMENT OF INTEGRATED TAX\n','0416903703','82712804','Ranjith Kumar R','Omega1','abc','NY 560033','1',1,NULL,NULL,NULL,NULL,'2025-10-09 09:24:17','2025-10-09 12:35:41',NULL,11),(3,9,12,NULL,'2025-10','','SB-25-26-0002','2025-10-13','2025-10-20','submitted',0,11,NULL,2,'EUR','€',1.00,0.00,1.18,1.00,0.00,0.18,1.18,NULL,'Springbord Systems Private Limited','12th Floor, Phase - II, TICEL BIO PARK,\nModel No.: 1203 No 5, CSIR Road, Taramani,\nChennai - 600 013 Tamil Nadu, India. Tel: +91-044-2225-9700\nGSTIN: 33AAWCS8726L1ZH','AAWCS8726L','33AAWCS8726L1ZH','AD330425013610P - SUPPLY MEANT FOR EXPORT UNDER LETTER OF UNDERTAKING (LUT) WITHOUT PAYMENT OF INTEGRATED TAX\n','0416903703','82712804','Ranjith Kumar R','Omega1','abc','NY 560033','1',1,NULL,NULL,NULL,NULL,'2025-10-13 06:24:57','2025-10-13 06:24:57',NULL,11),(4,9,12,NULL,'2025-10','','SB-25-26-0003','2025-10-13','2025-10-20','submitted',0,11,NULL,2,'EUR','€',1.00,0.00,1.18,1.00,0.00,0.18,1.18,NULL,'Springbord Systems Private Limited','12th Floor, Phase - II, TICEL BIO PARK,\nModel No.: 1203 No 5, CSIR Road, Taramani,\nChennai - 600 013 Tamil Nadu, India. Tel: +91-044-2225-9700\nGSTIN: 33AAWCS8726L1ZH','AAWCS8726L','33AAWCS8726L1ZH','AD330425013610P - SUPPLY MEANT FOR EXPORT UNDER LETTER OF UNDERTAKING (LUT) WITHOUT PAYMENT OF INTEGRATED TAX\n','0416903703','82712804','Ranjith Kumar R','Omega1','abc','NY 560033','1',1,NULL,NULL,NULL,NULL,'2025-10-13 10:25:24','2025-10-13 10:25:24',NULL,11),(5,4,9,NULL,'10-2025','','SBS-25-26-0002','2025-10-13','2025-10-20','submitted',0,46,NULL,1,'USD','$',1.00,0.00,1.00,1.00,0.00,0.00,1.00,NULL,'Springbord Systems Private Limited','12th Floor, Phase - II, TICEL BIO PARK,\nModel No.: 1203 No 5, CSIR Road, Taramani,\nChennai - 600 013 Tamil Nadu, India. Tel: +91-044-2225-9700\nGSTIN: 33AAWCS8726L1ZH','AAWCS8726L','33AAWCS8726L1ZH','AD330425013610P - SUPPLY MEANT FOR EXPORT UNDER LETTER OF UNDERTAKING (LUT) WITHOUT PAYMENT OF INTEGRATED TAX\n','0416903703','82712804','Ranjith Kumar R','Stellar','123, Main Street','IND 85602','2',1,NULL,NULL,NULL,NULL,'2025-10-13 12:22:46','2025-10-13 12:22:46',NULL,46),(6,12,9,1,'10-2025','1004','SBS-25-26-0003','2025-10-13','2025-10-20','finance_approved',0,11,NULL,1,'USD','$',1.00,0.00,1.00,1.00,0.00,0.00,1.00,NULL,'Springbord Systems Private Limited','12th Floor, Phase - II, TICEL BIO PARK,\nModel No.: 1203 No 5, CSIR Road, Taramani,\nChennai - 600 013 Tamil Nadu, India. Tel: +91-044-2225-9700\nGSTIN: 33AAWCS8726L1ZH','AAWCS8726L','33AAWCS8726L1ZH','AD330425013610P - SUPPLY MEANT FOR EXPORT UNDER LETTER OF UNDERTAKING (LUT) WITHOUT PAYMENT OF INTEGRATED TAX\n','0416903703','82712804','Ranjith Kumar R','Stellar','123, Main Street','IND 85602','2',1,NULL,'invoices/SBS-25-26-0003.pdf','2025-10-13 13:21:13','998311','2025-10-13 13:19:27','2025-10-13 13:21:13',NULL,11),(7,12,9,1,'09-2025','1005','SBS-25-26-0004','2025-10-14','2025-10-21','finance_approved',0,46,NULL,1,'USD','$',1.00,0.00,1.00,1.00,0.00,0.00,1.00,NULL,'Springbord Systems Private Limited','12th Floor, Phase - II, TICEL BIO PARK,\nModel No.: 1203 No 5, CSIR Road, Taramani,\nChennai - 600 013 Tamil Nadu, India. Tel: +91-044-2225-9700\nGSTIN: 33AAWCS8726L1ZH','AAWCS8726L','33AAWCS8726L1ZH','AD330425013610P - SUPPLY MEANT FOR EXPORT UNDER LETTER OF UNDERTAKING (LUT) WITHOUT PAYMENT OF INTEGRATED TAX\n','0416903703','82712804','Ranjith Kumar R','Stellar','123, Main Street','IND 85602','2',1,NULL,'invoices/SBS-25-26-0004.pdf','2025-10-14 05:41:52','998311','2025-10-14 04:55:12','2025-10-14 05:41:52',NULL,46),(8,12,9,1,'09-2025','1005','SBS-25-26-0005','2025-10-14','2025-10-21','finance_approved',0,46,NULL,1,'USD','$',1.00,0.00,1.00,1.00,0.00,0.00,1.00,'OK','Springbord Systems Private Limited','12th Floor, Phase - II, TICEL BIO PARK,\nModel No.: 1203 No 5, CSIR Road, Taramani,\nChennai - 600 013 Tamil Nadu, India. Tel: +91-044-2225-9700\nGSTIN: 33AAWCS8726L1ZH','AAWCS8726L','33AAWCS8726L1ZH','AD330425013610P - SUPPLY MEANT FOR EXPORT UNDER LETTER OF UNDERTAKING (LUT) WITHOUT PAYMENT OF INTEGRATED TAX\n','0416903703','82712804','Ranjith Kumar R','Stellar','123, Main Street','IND 85602','2',1,NULL,'invoices/SBS-25-26-0005.pdf','2025-10-14 05:30:24','998311','2025-10-14 05:29:11','2025-10-14 05:30:24',NULL,46),(9,12,9,NULL,'10-2025','1004','SBS-25-26-0006','2025-10-14','2025-10-21','submitted',0,46,NULL,1,'USD','$',1.00,0.00,1.00,1.00,0.00,0.00,1.00,NULL,'Springbord Systems Private Limited','12th Floor, Phase - II, TICEL BIO PARK,\nModel No.: 1203 No 5, CSIR Road, Taramani,\nChennai - 600 013 Tamil Nadu, India. Tel: +91-044-2225-9700\nGSTIN: 33AAWCS8726L1ZH','AAWCS8726L','33AAWCS8726L1ZH','AD330425013610P - SUPPLY MEANT FOR EXPORT UNDER LETTER OF UNDERTAKING (LUT) WITHOUT PAYMENT OF INTEGRATED TAX\n','0416903703','82712804','Ranjith Kumar R','Stellar','123, Main Street','IND 85602','2',1,NULL,NULL,NULL,NULL,'2025-10-14 06:06:29','2025-10-14 06:06:29',NULL,46),(10,12,9,1,'08-2025','1006','SBS-25-26-0007','2025-10-14','2025-10-21','finance_approved',0,46,NULL,1,'USD','$',1.00,0.00,1.00,1.00,0.00,0.00,1.00,NULL,'Springbord Systems Private Limited','12th Floor, Phase - II, TICEL BIO PARK,\nModel No.: 1203 No 5, CSIR Road, Taramani,\nChennai - 600 013 Tamil Nadu, India. Tel: +91-044-2225-9700\nGSTIN: 33AAWCS8726L1ZH','AAWCS8726L','33AAWCS8726L1ZH','AD330425013610P - SUPPLY MEANT FOR EXPORT UNDER LETTER OF UNDERTAKING (LUT) WITHOUT PAYMENT OF INTEGRATED TAX\n','0416903703','82712804','Ranjith Kumar R','Stellar','123, Main Street','IND 85602','2',1,NULL,'invoices/SBS-25-26-0007.pdf','2025-10-14 07:45:57','998311','2025-10-14 07:45:29','2025-10-14 07:45:57',NULL,46);
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `main_tasks`
--

DROP TABLE IF EXISTS `main_tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `main_tasks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `task_type` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '1=Productive, 2=General',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `main_tasks`
--

LOCK TABLES `main_tasks` WRITE;
/*!40000 ALTER TABLE `main_tasks` DISABLE KEYS */;
INSERT INTO `main_tasks` VALUES (1,1,'Email Review & Response',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(2,1,'Accounts Payables',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(3,1,'Accounts Receivables',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(4,1,'Bank Reconciliation',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(5,1,'General Ledger',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(6,1,'Month-End Activities',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(7,1,'Financial Packages',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(8,1,'Adhoc Request',1,'2025-09-18 07:52:13','2025-09-18 07:52:13'),(9,2,'Break Time',1,'2025-09-18 07:52:13','2025-09-22 01:06:12'),(10,1,'Meeting',1,'2025-09-18 07:52:13','2025-09-18 07:52:13'),(11,1,'Trainings & System Issue',1,'2025-09-18 07:52:13','2025-09-18 07:52:13'),(12,1,'TestApplication',1,'2025-10-10 04:18:01','2025-10-10 04:18:01');
/*!40000 ALTER TABLE `main_tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `message_reads`
--

DROP TABLE IF EXISTS `message_reads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `message_reads` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `message_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `participant_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `participant_id` bigint unsigned NOT NULL,
  `read_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `message_read_unique` (`message_id`,`participant_type`,`participant_id`),
  KEY `message_reads_participant_type_participant_id_read_at_index` (`participant_type`,`participant_id`,`read_at`),
  CONSTRAINT `message_reads_message_id_foreign` FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `message_reads`
--

LOCK TABLES `message_reads` WRITE;
/*!40000 ALTER TABLE `message_reads` DISABLE KEYS */;
INSERT INTO `message_reads` VALUES (1,'abecf818-b5cf-4c26-9efe-8103d176c7ac','App\\Models\\User',41,'2025-10-08 08:00:18','2025-10-08 08:00:06','2025-10-08 08:00:18'),(2,'0095877e-b2ad-4cc1-b139-642cb963f622','App\\Models\\User',41,'2025-10-08 08:00:25','2025-10-08 08:00:25','2025-10-08 08:00:25'),(3,'abecf818-b5cf-4c26-9efe-8103d176c7ac','App\\Models\\User',34,'2025-10-08 08:01:15','2025-10-08 08:01:15','2025-10-08 08:01:15'),(4,'0095877e-b2ad-4cc1-b139-642cb963f622','App\\Models\\User',34,'2025-10-08 08:01:15','2025-10-08 08:01:15','2025-10-08 08:01:15'),(5,'a401050e-1feb-43dc-875c-7fd133fab391','App\\Models\\User',47,'2025-10-08 11:31:59','2025-10-08 10:23:26','2025-10-08 11:31:59'),(6,'a401050e-1feb-43dc-875c-7fd133fab391','App\\Models\\User',11,'2025-10-08 10:23:34','2025-10-08 10:23:34','2025-10-08 10:23:34'),(7,'abecf818-b5cf-4c26-9efe-8103d176c7ac','App\\Models\\User',11,'2025-10-08 10:26:17','2025-10-08 10:26:17','2025-10-08 10:26:17'),(8,'0095877e-b2ad-4cc1-b139-642cb963f622','App\\Models\\User',11,'2025-10-08 10:26:17','2025-10-08 10:26:17','2025-10-08 10:26:17'),(9,'4a19f010-ec2e-4796-8739-fc996c91e450','App\\Models\\User',47,'2025-10-08 11:32:03','2025-10-08 11:32:03','2025-10-08 11:32:03'),(10,'373c89ec-538d-4827-9a76-9d2e2a10aacc','App\\Models\\User',47,'2025-10-09 04:31:22','2025-10-09 04:27:36','2025-10-09 04:31:22'),(11,'373c89ec-538d-4827-9a76-9d2e2a10aacc','App\\Models\\User',11,'2025-10-09 05:12:02','2025-10-09 04:27:49','2025-10-09 05:12:02'),(12,'b3d3180b-b78c-4353-9ca8-5f11ec7a6a60','App\\Models\\User',11,'2025-10-09 05:12:02','2025-10-09 04:27:59','2025-10-09 05:12:02'),(13,'b3d3180b-b78c-4353-9ca8-5f11ec7a6a60','App\\Models\\User',47,'2025-10-09 04:31:21','2025-10-09 04:28:06','2025-10-09 04:31:21'),(14,'373c89ec-538d-4827-9a76-9d2e2a10aacc','App\\Models\\User',46,'2025-10-13 07:19:18','2025-10-09 04:30:55','2025-10-13 07:19:18'),(15,'b3d3180b-b78c-4353-9ca8-5f11ec7a6a60','App\\Models\\User',46,'2025-10-13 07:19:18','2025-10-09 04:30:55','2025-10-13 07:19:18'),(16,'56e616a3-144c-44c0-86cd-59541f7b15bd','App\\Models\\User',46,'2025-10-13 07:19:18','2025-10-09 04:31:02','2025-10-13 07:19:18'),(17,'56e616a3-144c-44c0-86cd-59541f7b15bd','App\\Models\\User',11,'2025-10-09 05:12:02','2025-10-09 04:31:14','2025-10-09 05:12:02'),(18,'56e616a3-144c-44c0-86cd-59541f7b15bd','App\\Models\\User',47,'2025-10-09 04:31:21','2025-10-09 04:31:21','2025-10-09 04:31:21'),(19,'373c89ec-538d-4827-9a76-9d2e2a10aacc','App\\Models\\User',50,'2025-10-09 05:11:55','2025-10-09 05:11:17','2025-10-09 05:11:55'),(20,'b3d3180b-b78c-4353-9ca8-5f11ec7a6a60','App\\Models\\User',50,'2025-10-09 05:11:55','2025-10-09 05:11:17','2025-10-09 05:11:55'),(21,'56e616a3-144c-44c0-86cd-59541f7b15bd','App\\Models\\User',50,'2025-10-09 05:11:55','2025-10-09 05:11:17','2025-10-09 05:11:55'),(22,'79de49ba-77f0-4a79-a470-ab1bab5318be','App\\Models\\User',50,'2025-10-09 05:11:55','2025-10-09 05:11:27','2025-10-09 05:11:55'),(23,'373c89ec-538d-4827-9a76-9d2e2a10aacc','App\\Models\\User',53,'2025-10-09 05:27:29','2025-10-09 05:11:32','2025-10-09 05:27:29'),(24,'b3d3180b-b78c-4353-9ca8-5f11ec7a6a60','App\\Models\\User',53,'2025-10-09 05:27:29','2025-10-09 05:11:32','2025-10-09 05:27:29'),(25,'56e616a3-144c-44c0-86cd-59541f7b15bd','App\\Models\\User',53,'2025-10-09 05:27:29','2025-10-09 05:11:32','2025-10-09 05:27:29'),(26,'79de49ba-77f0-4a79-a470-ab1bab5318be','App\\Models\\User',53,'2025-10-09 05:27:29','2025-10-09 05:11:32','2025-10-09 05:27:29'),(27,'ef7a69ba-caf6-46e4-8fda-c58ddf14d4ad','App\\Models\\User',53,'2025-10-09 05:27:29','2025-10-09 05:11:41','2025-10-09 05:27:29'),(28,'ef7a69ba-caf6-46e4-8fda-c58ddf14d4ad','App\\Models\\User',50,'2025-10-09 05:11:55','2025-10-09 05:11:51','2025-10-09 05:11:55'),(29,'79de49ba-77f0-4a79-a470-ab1bab5318be','App\\Models\\User',11,'2025-10-09 05:12:02','2025-10-09 05:12:02','2025-10-09 05:12:02'),(30,'ef7a69ba-caf6-46e4-8fda-c58ddf14d4ad','App\\Models\\User',11,'2025-10-09 05:12:02','2025-10-09 05:12:02','2025-10-09 05:12:02'),(31,'22c477b3-fa3b-4ce0-a95e-c794ff99b17b','App\\Models\\User',65,'2025-10-09 06:32:44','2025-10-09 06:32:14','2025-10-09 06:32:44'),(32,'f2a9c321-acb7-4c7d-a691-f353103f7a0e','App\\Models\\User',65,'2025-10-09 06:32:44','2025-10-09 06:32:40','2025-10-09 06:32:44'),(33,'22c477b3-fa3b-4ce0-a95e-c794ff99b17b','App\\Models\\User',68,'2025-10-09 12:11:41','2025-10-09 12:11:40','2025-10-09 12:11:41'),(34,'f2a9c321-acb7-4c7d-a691-f353103f7a0e','App\\Models\\User',68,'2025-10-09 12:11:40','2025-10-09 12:11:40','2025-10-09 12:11:40'),(35,'3bd9afa7-4a1e-4fe5-b071-04b920ada277','App\\Models\\User',68,'2025-10-09 12:11:52','2025-10-09 12:11:52','2025-10-09 12:11:52'),(36,'22c477b3-fa3b-4ce0-a95e-c794ff99b17b','App\\Models\\User',69,'2025-10-09 12:18:50','2025-10-09 12:16:09','2025-10-09 12:18:50'),(37,'f2a9c321-acb7-4c7d-a691-f353103f7a0e','App\\Models\\User',69,'2025-10-09 12:18:49','2025-10-09 12:16:09','2025-10-09 12:18:49'),(38,'3bd9afa7-4a1e-4fe5-b071-04b920ada277','App\\Models\\User',69,'2025-10-09 12:18:49','2025-10-09 12:16:09','2025-10-09 12:18:49'),(39,'22c477b3-fa3b-4ce0-a95e-c794ff99b17b','App\\Models\\User',66,'2025-10-10 05:01:14','2025-10-10 05:01:14','2025-10-10 05:01:14'),(40,'f2a9c321-acb7-4c7d-a691-f353103f7a0e','App\\Models\\User',66,'2025-10-10 05:01:14','2025-10-10 05:01:14','2025-10-10 05:01:14'),(41,'3bd9afa7-4a1e-4fe5-b071-04b920ada277','App\\Models\\User',66,'2025-10-10 05:01:14','2025-10-10 05:01:14','2025-10-10 05:01:14'),(42,'37b02bc7-5c7d-4bbf-8d85-5b27bc37e23c','App\\Models\\User',66,'2025-10-13 06:15:17','2025-10-13 06:15:13','2025-10-13 06:15:17'),(43,'ed4fbeba-f24b-4195-b075-c5fd89503f90','App\\Models\\User',66,'2025-10-13 06:15:21','2025-10-13 06:15:21','2025-10-13 06:15:21'),(44,'79de49ba-77f0-4a79-a470-ab1bab5318be','App\\Models\\User',46,'2025-10-13 07:19:18','2025-10-13 07:19:18','2025-10-13 07:19:18'),(45,'ef7a69ba-caf6-46e4-8fda-c58ddf14d4ad','App\\Models\\User',46,'2025-10-13 07:19:18','2025-10-13 07:19:18','2025-10-13 07:19:18');
/*!40000 ALTER TABLE `message_reads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messages` (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `conversation_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sender_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sender_id` bigint unsigned NOT NULL,
  `kind` enum('text','system','note') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `meta` json DEFAULT NULL,
  `sent_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `conv_time` (`conversation_id`,`sent_at`,`id`),
  KEY `messages_sender_type_sender_id_index` (`sender_type`,`sender_id`),
  KEY `messages_kind_index` (`kind`),
  CONSTRAINT `messages_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES ('0095877e-b2ad-4cc1-b139-642cb963f622','82e07f86-0194-4e75-9a37-c6427bb47904','App\\Models\\User',41,'text','Test2',NULL,'2025-10-08 08:00:25','2025-10-08 08:00:25','2025-10-08 08:00:25',NULL),('22c477b3-fa3b-4ce0-a95e-c794ff99b17b','d6171261-64a2-4352-b344-08543d6ad602','App\\Models\\User',65,'text','TEsting for Jaya',NULL,'2025-10-09 06:32:14','2025-10-09 06:32:14','2025-10-09 06:32:14',NULL),('373c89ec-538d-4827-9a76-9d2e2a10aacc','6c6c4be7-cfa1-4eda-aeaa-63f7923287b2','App\\Models\\User',47,'text','GHI',NULL,'2025-10-09 04:27:36','2025-10-09 04:27:36','2025-10-09 04:27:36',NULL),('37b02bc7-5c7d-4bbf-8d85-5b27bc37e23c','d764eefc-6075-419b-b2df-357ed5156ed6','App\\Models\\User',66,'text','atest',NULL,'2025-10-13 06:15:13','2025-10-13 06:15:13','2025-10-13 06:15:13',NULL),('3bd9afa7-4a1e-4fe5-b071-04b920ada277','d6171261-64a2-4352-b344-08543d6ad602','App\\Models\\User',68,'text','Test from REview',NULL,'2025-10-09 12:11:52','2025-10-09 12:11:52','2025-10-09 12:11:52',NULL),('4a19f010-ec2e-4796-8739-fc996c91e450','5d4788fd-fc33-4366-b8b7-ba28979ac07a','App\\Models\\User',47,'text','Test2',NULL,'2025-10-08 11:32:03','2025-10-08 11:32:03','2025-10-08 11:32:03',NULL),('56e616a3-144c-44c0-86cd-59541f7b15bd','6c6c4be7-cfa1-4eda-aeaa-63f7923287b2','App\\Models\\User',46,'text','Test2',NULL,'2025-10-09 04:31:02','2025-10-09 04:31:02','2025-10-09 04:31:02',NULL),('79de49ba-77f0-4a79-a470-ab1bab5318be','6c6c4be7-cfa1-4eda-aeaa-63f7923287b2','App\\Models\\User',50,'text','Test Review',NULL,'2025-10-09 05:11:27','2025-10-09 05:11:27','2025-10-09 05:11:27',NULL),('a401050e-1feb-43dc-875c-7fd133fab391','5d4788fd-fc33-4366-b8b7-ba28979ac07a','App\\Models\\User',47,'text','Hi',NULL,'2025-10-08 10:23:26','2025-10-08 10:23:26','2025-10-08 10:23:26',NULL),('abecf818-b5cf-4c26-9efe-8103d176c7ac','82e07f86-0194-4e75-9a37-c6427bb47904','App\\Models\\User',41,'text','Test1',NULL,'2025-10-08 08:00:06','2025-10-08 08:00:06','2025-10-08 08:00:06',NULL),('b3d3180b-b78c-4353-9ca8-5f11ec7a6a60','6c6c4be7-cfa1-4eda-aeaa-63f7923287b2','App\\Models\\User',11,'text','Test1',NULL,'2025-10-09 04:27:59','2025-10-09 04:27:59','2025-10-09 04:27:59',NULL),('ed4fbeba-f24b-4195-b075-c5fd89503f90','d764eefc-6075-419b-b2df-357ed5156ed6','App\\Models\\User',66,'text','abc',NULL,'2025-10-13 06:15:21','2025-10-13 06:15:21','2025-10-13 06:15:21',NULL),('ef7a69ba-caf6-46e4-8fda-c58ddf14d4ad','6c6c4be7-cfa1-4eda-aeaa-63f7923287b2','App\\Models\\User',53,'text','TestSense',NULL,'2025-10-09 05:11:41','2025-10-09 05:11:41','2025-10-09 05:11:41',NULL),('f2a9c321-acb7-4c7d-a691-f353103f7a0e','d6171261-64a2-4352-b344-08543d6ad602','App\\Models\\User',65,'text','TEsting for Nahid',NULL,'2025-10-09 06:32:40','2025-10-09 06:32:40','2025-10-09 06:32:40',NULL);
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=109 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_07_15_085224_create_permission_tables',1),(5,'2025_07_16_124637_rename_name_column_in_users_table',1),(6,'2025_07_16_125210_add_fields_to_users_table',1),(7,'2025_07_16_131524_drop_is_delete_from_users_table',1),(8,'2025_07_18_105041_add_is_password_update_to_users_table',1),(9,'2025_07_18_124412_create_banks_table',1),(10,'2025_07_22_115107_create_project_statuses_table',1),(11,'2025_07_22_121304_create_project_types_table',1),(12,'2025_07_22_122344_create_project_priorities_table',1),(13,'2025_07_22_123258_create_customer_approval_statuses_table',1),(14,'2025_07_22_123811_create_project_delivery_frequencies_table',1),(15,'2025_07_24_125417_create_departments_table',1),(16,'2025_07_24_134829_create_mode_of_deliveries_table',1),(17,'2025_07_29_101743_create_input_output_formats_table',1),(18,'2025_08_04_063439_create_industry_verticals_table',1),(19,'2025_08_04_090235_create_service_offerings_table',1),(20,'2025_08_05_064226_add_created_by_and_updated_by_to_industry_verticals_table',1),(21,'2025_08_05_071026_create_activity_log_table',1),(22,'2025_08_05_071027_add_event_column_to_activity_log_table',1),(23,'2025_08_05_071028_add_batch_uuid_column_to_activity_log_table',1),(24,'2025_08_05_073858_add_created_by_and_updated_by_to_banks_table',1),(25,'2025_08_05_073926_add_created_by_and_updated_by_to_customer_approval_statuses_table',1),(26,'2025_08_05_073957_add_created_by_and_updated_by_to_departments_table',1),(27,'2025_08_05_074040_add_created_by_and_updated_by_to_input_output_formats_table',1),(28,'2025_08_05_074110_add_created_by_and_updated_by_to_project_delivery_frequencies_table',1),(29,'2025_08_05_074144_add_created_by_and_updated_by_to_project_priorities_table',1),(30,'2025_08_05_074207_add_created_by_and_updated_by_to_project_statuses_table',1),(31,'2025_08_05_074228_add_created_by_and_updated_by_to_project_types_table',1),(32,'2025_08_05_074248_add_created_by_and_updated_by_to_service_offerings_table',1),(33,'2025_08_05_074302_add_created_by_and_updated_by_to_users_table',1),(34,'2025_08_05_085420_add_created_by_and_updated_by_to_mode_of_deliveries_table',1),(35,'2025_08_05_104759_create_unit_of_measurements_table',1),(36,'2025_08_05_113629_create_currencies_table',1),(37,'2025_08_05_123942_create_descriptions_table',1),(38,'2025_08_06_084907_create_pricing_masters_table',1),(39,'2025_08_06_122545_add_name_and_status_to_pricing_masters_table',1),(40,'2025_08_07_071231_create_skill_masters_table',1),(41,'2025_08_08_061809_update_skill_masters_table_remove_aht_add_status',1),(42,'2025_08_08_115252_create_pricing_master_skill_lines_table',1),(43,'2025_08_08_130555_add_approval_fields_to_pricing_masters_table',1),(44,'2025_08_11_063346_create_notes_table',1),(45,'2025_08_12_052509_create_add_document_to_pricing_masters_table',1),(46,'2025_08_13_090542_add_project_manager_to_users_table',1),(47,'2025_08_13_093012_create_companies_table',1),(48,'2025_08_13_093100_add_company_id_to_users_table',1),(49,'2025_08_25_060535_create_projects_table',1),(50,'2025_08_25_060807_create_project_pm_pivot',1),(51,'2025_08_25_060926_create_poc_project_pivot',1),(53,'2025_08_28_121305_add_parent_id_to_projects_table',2),(54,'2025_09_04_134601_create_project_intakes_table',3),(55,'2025_09_09_060333_add_missing_columns_to_project_intakes_table',4),(56,'2025_09_09_091904_update_project_intakes_add_project_manager_drop_client_project',5),(57,'2025_09_09_130455_remove_project_manager_from_project_intakes_table',6),(59,'2025_09_10_060559_create_intake_statuses_table',7),(60,'2025_09_10_063655_create_intake_query_types_table',8),(61,'2025_09_10_091712_create_intake_languages_table',8),(62,'2025_09_10_091712_create_intake_lease_types_table',8),(63,'2025_09_10_091712_create_intake_work_types_table',8),(64,'2025_09_10_102515_create_feedback_categories_table',9),(65,'2025_09_10_102515_create_invoice_formats_table',9),(66,'2025_09_10_102515_create_query_statuses_table',9),(67,'2025_09_16_061922_add_product_category_to_projects_table',10),(68,'2025_09_16_092903_create_project_member_assignments_table',11),(69,'2025_09_17_053325_add_startdate_enddate_to_project_member_assignments_table',12),(71,'2025_09_18_085738_create_main_tasks',13),(72,'2025_09_18_124339_create_sub_tasks',13),(73,'2025_09_19_070740_create_task_items_table',14),(74,'2025_09_19_070740_create_work_sessions_table',14),(75,'2025_09_19_104140_update_datetime_precision_on_work_sessions_and_task_items',15),(76,'2025_09_22_062213_add_task_type_to_main_tasks_table',16),(84,'2025_09_22_000001_create_conversations_table',17),(85,'2025_09_22_000002_create_messages_table ',17),(86,'2025_09_22_000003_create_message_reads_table',17),(87,'2025_09_22_000004_create_conversation_stats_table',17),(88,'2025_09_16_095557_add_status_to_companies_table',18),(89,'2025_09_17_085633_create_documents_table',18),(90,'2025_09_24_152530_alter_banks_add_currency_id_drop_currency',18),(91,'2025_09_24_184320_create_po_numbers_table',18),(92,'2025_09_25_143015_create_invoices_table',18),(93,'2025_09_25_144122_create_invoice_lines_table',18),(94,'2025_09_26_123907_add_financial_fields_to_invoices_table',19),(95,'2025_09_26_185348_add_bank_id_and_payment_completed_to_invoices_table',19),(96,'2025_09_29_181941_add_suite_id_to_projects_table',19),(97,'2025_09_30_110837_move_suite_id_to_project_intakes',19),(98,'2025_10_01_143022_add_company_type_to_companies_table',19),(99,'2025_10_01_172002_add_aba_and_routing_to_banks_table',19),(100,'2025_10_01_180106_add_zip_code_to_companies_table',19),(101,'2025_10_01_182532_add_sac_zip_finance_notes_to_invoices_table',19),(102,'2025_10_02_124230_adjust_unique_index_on_invoice_lines',19),(103,'2025_10_02_150057_add_pdf_path_to_invoices_table',19),(104,'2025_10_03_143235_add_invoice_type_to_companies_table',19),(105,'2025_10_03_160919_add_invoice_type_to_invoices_table',19),(106,'2025_10_07_112922_create_intake_queries_table',20),(107,'2025_10_07_130033_add_audit_columns_to_intake_queries_table',20),(108,'2025_10_13_172702_add_soft_deletes_to_pricing_masters_table',21);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mode_of_deliveries`
--

DROP TABLE IF EXISTS `mode_of_deliveries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mode_of_deliveries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mode_of_deliveries_created_by_foreign` (`created_by`),
  KEY `mode_of_deliveries_updated_by_foreign` (`updated_by`),
  CONSTRAINT `mode_of_deliveries_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `mode_of_deliveries_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mode_of_deliveries`
--

LOCK TABLES `mode_of_deliveries` WRITE;
/*!40000 ALTER TABLE `mode_of_deliveries` DISABLE KEYS */;
INSERT INTO `mode_of_deliveries` VALUES (1,'Email',1,1,1,'2025-08-25 06:13:31','2025-08-25 06:13:31'),(3,'Drive',1,11,11,'2025-10-10 04:05:34','2025-10-10 04:05:34');
/*!40000 ALTER TABLE `mode_of_deliveries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\User',1),(3,'App\\Models\\User',2),(3,'App\\Models\\User',3),(2,'App\\Models\\User',5),(4,'App\\Models\\User',6),(4,'App\\Models\\User',7),(4,'App\\Models\\User',8),(2,'App\\Models\\User',9),(5,'App\\Models\\User',10),(6,'App\\Models\\User',10),(1,'App\\Models\\User',11),(3,'App\\Models\\User',12),(2,'App\\Models\\User',13),(5,'App\\Models\\User',14),(4,'App\\Models\\User',15),(5,'App\\Models\\User',16),(4,'App\\Models\\User',17),(4,'App\\Models\\User',18),(2,'App\\Models\\User',19),(3,'App\\Models\\User',20),(4,'App\\Models\\User',21),(3,'App\\Models\\User',22),(2,'App\\Models\\User',23),(4,'App\\Models\\User',24),(5,'App\\Models\\User',25),(6,'App\\Models\\User',26),(3,'App\\Models\\User',27),(3,'App\\Models\\User',28),(4,'App\\Models\\User',29),(3,'App\\Models\\User',30),(4,'App\\Models\\User',31),(3,'App\\Models\\User',32),(3,'App\\Models\\User',33),(2,'App\\Models\\User',34),(4,'App\\Models\\User',35),(4,'App\\Models\\User',36),(4,'App\\Models\\User',37),(5,'App\\Models\\User',38),(5,'App\\Models\\User',39),(5,'App\\Models\\User',40),(6,'App\\Models\\User',41),(6,'App\\Models\\User',42),(6,'App\\Models\\User',43),(7,'App\\Models\\User',44),(3,'App\\Models\\User',45),(2,'App\\Models\\User',46),(4,'App\\Models\\User',47),(4,'App\\Models\\User',48),(4,'App\\Models\\User',49),(5,'App\\Models\\User',50),(5,'App\\Models\\User',51),(5,'App\\Models\\User',52),(6,'App\\Models\\User',53),(6,'App\\Models\\User',55),(3,'App\\Models\\User',56),(4,'App\\Models\\User',58),(5,'App\\Models\\User',59),(6,'App\\Models\\User',60),(7,'App\\Models\\User',61),(3,'App\\Models\\User',62),(3,'App\\Models\\User',63),(2,'App\\Models\\User',64),(4,'App\\Models\\User',65),(4,'App\\Models\\User',66),(5,'App\\Models\\User',67),(5,'App\\Models\\User',68),(6,'App\\Models\\User',69),(6,'App\\Models\\User',70),(2,'App\\Models\\User',72),(3,'App\\Models\\User',73),(6,'App\\Models\\User',74),(7,'App\\Models\\User',76),(3,'App\\Models\\User',79);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notes`
--

DROP TABLE IF EXISTS `notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pricing_master_id` bigint unsigned NOT NULL,
  `note_type` tinyint NOT NULL COMMENT '1 = Approve, 2 = Reject,3=Action Required',
  `price` decimal(15,2) DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `approve_rejected_by` bigint unsigned DEFAULT NULL,
  `create_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notes_pricing_master_id_foreign` (`pricing_master_id`),
  KEY `notes_approve_rejected_by_foreign` (`approve_rejected_by`),
  KEY `notes_create_by_foreign` (`create_by`),
  CONSTRAINT `notes_approve_rejected_by_foreign` FOREIGN KEY (`approve_rejected_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `notes_create_by_foreign` FOREIGN KEY (`create_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `notes_pricing_master_id_foreign` FOREIGN KEY (`pricing_master_id`) REFERENCES `pricing_masters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notes`
--

LOCK TABLES `notes` WRITE;
/*!40000 ALTER TABLE `notes` DISABLE KEYS */;
INSERT INTO `notes` VALUES (2,12,1,1.00,'approved',11,11,'2025-10-09 12:40:44','2025-10-09 12:41:37'),(3,12,3,1.00,NULL,NULL,11,'2025-10-13 11:50:35','2025-10-13 11:50:35'),(6,15,1,10000.00,'approve',11,11,'2025-10-13 13:08:26','2025-10-13 13:08:58');
/*!40000 ALTER TABLE `notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=133 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'view role','web','2025-08-25 03:58:56','2025-08-25 03:58:56'),(2,'create role','web','2025-08-25 03:58:56','2025-08-25 03:58:56'),(3,'edit role','web','2025-08-25 03:58:56','2025-08-25 03:58:56'),(4,'delete role','web','2025-08-25 03:58:56','2025-08-25 03:58:56'),(5,'view project','web','2025-08-25 03:58:56','2025-08-25 03:58:56'),(6,'create project','web','2025-08-25 03:58:56','2025-08-25 03:58:56'),(7,'edit project','web','2025-08-25 03:58:56','2025-08-25 03:58:56'),(8,'delete project','web','2025-08-25 03:58:56','2025-08-25 03:58:56'),(9,'view customer','web','2025-08-25 03:58:56','2025-08-25 03:58:56'),(10,'create customer','web','2025-08-25 03:58:56','2025-08-25 03:58:56'),(11,'edit customer','web','2025-08-25 03:58:56','2025-08-25 03:58:56'),(12,'delete customer','web','2025-08-25 03:58:56','2025-08-25 03:58:56'),(13,'view user','web','2025-08-25 03:58:56','2025-08-25 03:58:56'),(14,'create user','web','2025-08-25 03:58:56','2025-08-25 03:58:56'),(15,'edit user','web','2025-08-25 03:58:56','2025-08-25 03:58:56'),(16,'delete user','web','2025-08-25 03:58:56','2025-08-25 03:58:56'),(17,'view invoice','web','2025-08-25 03:58:56','2025-08-25 03:58:56'),(18,'create invoice','web','2025-08-25 03:58:56','2025-08-25 03:58:56'),(19,'edit invoice','web','2025-08-25 03:58:56','2025-08-25 03:58:56'),(20,'delete invoice','web','2025-08-25 03:58:56','2025-08-25 03:58:56'),(21,'view setting','web','2025-08-25 03:58:56','2025-08-25 03:58:56'),(22,'create setting','web','2025-08-25 03:58:56','2025-08-25 03:58:56'),(23,'edit setting','web','2025-08-25 03:58:56','2025-08-25 03:58:56'),(24,'delete setting','web','2025-08-25 03:58:56','2025-08-25 03:58:56'),(25,'view permission','web','2025-08-25 03:58:56','2025-08-25 03:58:56'),(26,'create permission','web','2025-08-25 03:58:56','2025-08-25 03:58:56'),(27,'edit permission','web','2025-08-25 03:58:56','2025-08-25 03:58:56'),(28,'delete permission','web','2025-08-25 03:58:56','2025-08-25 03:58:56'),(29,'view bank','web','2025-08-25 03:58:56','2025-08-25 03:58:56'),(30,'create bank','web','2025-08-25 03:58:56','2025-08-25 03:58:56'),(31,'edit bank','web','2025-08-25 03:58:56','2025-08-25 03:58:56'),(32,'delete bank','web','2025-08-25 03:58:56','2025-08-25 03:58:56'),(33,'view dashboard','web','2025-08-25 03:58:56','2025-08-25 03:58:56'),(34,'view collaboration','web','2025-08-25 03:58:56','2025-08-25 03:58:56'),(35,'view report','web','2025-08-25 03:58:56','2025-08-25 03:58:56'),(36,'create project type','web',NULL,NULL),(37,'edit project type','web',NULL,NULL),(38,'view project type','web',NULL,NULL),(39,'delete project type','web',NULL,NULL),(40,'create department','web',NULL,NULL),(41,'view department','web',NULL,NULL),(42,'edit department','web',NULL,NULL),(43,'delete department','web',NULL,NULL),(44,'view mode of delivery','web',NULL,NULL),(45,'create mode of delivery','web',NULL,NULL),(46,'edit mode of delivery','web',NULL,NULL),(47,'delete mode of delivery','web',NULL,NULL),(48,'create project priorities','web',NULL,NULL),(49,'edit project priorities','web',NULL,NULL),(50,'view project priorities','web',NULL,NULL),(51,'delete project priorities','web',NULL,NULL),(52,'create project status','web',NULL,NULL),(53,'view project status','web',NULL,NULL),(54,'edit project status','web',NULL,NULL),(55,'delete project status','web',NULL,NULL),(56,'create delivery frequencies','web',NULL,NULL),(57,'view delivery frequencies','web',NULL,NULL),(58,'edit delivery frequencies','web',NULL,NULL),(59,'delete delivery frequencies','web',NULL,NULL),(60,'create input output format','web',NULL,NULL),(61,'view input output format','web',NULL,NULL),(62,'edit input output format','web',NULL,NULL),(63,'delete input output format','web',NULL,NULL),(64,'delete industry vertical','web',NULL,NULL),(65,'edit industry vertical','web',NULL,NULL),(66,'view industry vertical','web',NULL,NULL),(67,'create industry vertical','web',NULL,NULL),(68,'create service offering','web',NULL,NULL),(69,'edit service offering','web',NULL,NULL),(70,'view service offering','web',NULL,NULL),(71,'delete service offering','web',NULL,NULL),(72,'create unit of measurement','web',NULL,NULL),(73,'edit unit of measurement','web',NULL,NULL),(74,'view unit of measurement','web',NULL,NULL),(75,'delete unit of measurement','web',NULL,NULL),(76,'create currency','web',NULL,NULL),(77,'edit currency','web',NULL,NULL),(78,'view currency','web',NULL,NULL),(79,'delete currency','web',NULL,NULL),(80,'create description','web',NULL,NULL),(81,'view description','web',NULL,NULL),(82,'edit description','web',NULL,NULL),(83,'delete description','web',NULL,NULL),(84,'create pricing master','web',NULL,NULL),(85,'edit pricing master','web',NULL,NULL),(86,'view pricing master','web',NULL,NULL),(87,'delete pricing master','web',NULL,NULL),(88,'approve pricing master','web',NULL,NULL),(89,'view skill master','web',NULL,NULL),(90,'edit skill master','web',NULL,NULL),(91,'create skill master','web',NULL,NULL),(92,'delete skill master','web',NULL,NULL),(93,'intake form add','web','2025-09-08 07:02:12','2025-09-08 07:02:23'),(94,'intake form remove','web','2025-09-08 07:02:50','2025-09-08 07:02:50'),(95,'view intake form primary information','web','2025-09-08 07:04:05','2025-09-08 07:04:05'),(96,'view intake form queries','web','2025-09-08 07:04:43','2025-09-08 07:04:43'),(97,'view intake form production details','web','2025-09-08 07:05:25','2025-09-08 07:05:25'),(98,'view intake form billing details','web','2025-09-08 07:06:20','2025-09-08 07:06:20'),(99,'view intake form customer feedback','web','2025-09-08 07:06:51','2025-09-08 07:06:51'),(100,'view intake form','web','2025-09-10 06:57:33','2025-09-10 06:57:33'),(101,'view task','web','2025-09-18 08:12:23','2025-09-18 08:12:23'),(102,'create task','web','2025-09-18 08:12:33','2025-09-18 08:12:33'),(103,'edit task','web','2025-09-18 08:13:04','2025-09-18 08:13:04'),(104,'delete task','web','2025-09-18 08:13:14','2025-09-18 08:13:14'),(105,'view intake lease type','web','2025-09-25 11:52:11','2025-09-25 11:52:11'),(106,'create intake lease type','web','2025-09-25 11:52:17','2025-09-25 11:52:17'),(107,'edit intake lease type','web','2025-09-25 11:52:32','2025-09-25 11:52:32'),(108,'delete intake lease type','web','2025-09-25 11:52:42','2025-09-25 11:52:42'),(109,'view intake work type','web','2025-09-25 11:53:04','2025-09-25 11:53:04'),(110,'create intake work type','web','2025-09-25 11:53:12','2025-09-25 11:53:12'),(111,'edit intake work type','web','2025-09-25 11:53:18','2025-09-25 11:53:18'),(112,'delete intake work type','web','2025-09-25 11:53:27','2025-09-25 11:53:27'),(113,'view intake language','web','2025-09-25 11:53:54','2025-09-25 11:53:54'),(114,'create intake language','web','2025-09-25 11:53:59','2025-09-25 11:53:59'),(115,'edit intake language','web','2025-09-25 11:54:10','2025-09-25 11:54:10'),(116,'delete intake language','web','2025-09-25 11:54:19','2025-09-25 11:54:19'),(117,'delete document','web','2025-09-26 06:53:33','2025-09-26 06:53:33'),(118,'view document','web','2025-09-26 06:53:39','2025-09-26 06:53:39'),(119,'edit document','web','2025-09-26 06:53:47','2025-09-26 06:53:47'),(120,'create document','web','2025-09-26 06:53:52','2025-09-26 06:53:52'),(121,'list invoice','web','2025-10-06 05:52:42','2025-10-06 05:52:42'),(122,'finance approve invoice','web','2025-10-06 05:52:49','2025-10-06 05:52:49'),(123,'create po','web','2025-10-06 06:02:59','2025-10-06 06:02:59'),(124,'edit po','web','2025-10-06 06:03:05','2025-10-06 06:03:05'),(125,'view po','web','2025-10-06 06:03:13','2025-10-06 06:03:13'),(126,'delete po','web','2025-10-06 06:03:23','2025-10-06 06:03:23'),(127,'edit intake form primary information','web','2025-10-06 11:46:31','2025-10-06 11:46:31'),(128,'edit intake form queries','web','2025-10-06 11:46:45','2025-10-06 11:46:45'),(129,'edit intake form production details','web','2025-10-06 11:47:02','2025-10-06 11:47:02'),(130,'edit intake form billing details','web','2025-10-06 11:47:15','2025-10-06 11:47:15'),(131,'edit intake form customer feedback','web','2025-10-06 11:47:28','2025-10-06 11:47:28'),(132,'create query','web','2025-10-10 11:58:23','2025-10-10 11:58:23');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `po_numbers`
--

DROP TABLE IF EXISTS `po_numbers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `po_numbers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint unsigned NOT NULL,
  `project_id` bigint unsigned NOT NULL,
  `sub_project_id` bigint unsigned DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `po_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_project_sub_po` (`project_id`,`sub_project_id`,`po_number`),
  KEY `po_numbers_customer_id_index` (`customer_id`),
  KEY `po_numbers_project_id_index` (`project_id`),
  KEY `po_numbers_sub_project_id_index` (`sub_project_id`),
  KEY `po_numbers_status_index` (`status`),
  CONSTRAINT `po_numbers_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `companies` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `po_numbers_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `po_numbers_sub_project_id_foreign` FOREIGN KEY (`sub_project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `po_numbers`
--

LOCK TABLES `po_numbers` WRITE;
/*!40000 ALTER TABLE `po_numbers` DISABLE KEYS */;
INSERT INTO `po_numbers` VALUES (1,7,1,NULL,'2025-10-07','2025-10-08','1234567',1,44,44,'2025-10-08 08:09:35','2025-10-08 08:09:35',NULL),(2,9,4,6,'2025-08-01','2025-10-31','1004',1,11,11,'2025-10-09 11:50:16','2025-10-13 11:52:47',NULL),(3,12,7,NULL,NULL,NULL,'007',1,44,44,'2025-10-09 12:33:03','2025-10-09 12:33:03',NULL),(4,9,4,6,'2025-10-01','2025-10-31','1003',1,11,11,'2025-10-13 11:49:46','2025-10-13 11:50:01','2025-10-13 11:50:01'),(5,9,12,NULL,'2025-10-01','2025-10-31','1004',0,76,44,'2025-10-13 13:20:43','2025-10-14 07:46:34',NULL),(6,9,12,13,'2025-10-25','2025-10-31','1005',1,44,44,'2025-10-14 05:27:56','2025-10-14 07:37:09','2025-10-14 07:37:09'),(7,9,12,13,'2025-10-25','2025-10-31','1006',1,44,44,'2025-10-14 07:43:53','2025-10-14 07:43:53',NULL);
/*!40000 ALTER TABLE `po_numbers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pricing_master_skill_lines`
--

DROP TABLE IF EXISTS `pricing_master_skill_lines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pricing_master_skill_lines` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pricing_master_id` bigint unsigned NOT NULL,
  `skill_id` bigint unsigned NOT NULL,
  `average_handling_time` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pricing_master_skill_lines_pricing_master_id_skill_id_unique` (`pricing_master_id`,`skill_id`),
  KEY `pricing_master_skill_lines_pricing_master_id_index` (`pricing_master_id`),
  KEY `pricing_master_skill_lines_skill_id_index` (`skill_id`),
  CONSTRAINT `pricing_master_skill_lines_pricing_master_id_foreign` FOREIGN KEY (`pricing_master_id`) REFERENCES `pricing_masters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pricing_master_skill_lines`
--

LOCK TABLES `pricing_master_skill_lines` WRITE;
/*!40000 ALTER TABLE `pricing_master_skill_lines` DISABLE KEYS */;
/*!40000 ALTER TABLE `pricing_master_skill_lines` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pricing_masters`
--

DROP TABLE IF EXISTS `pricing_masters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pricing_masters` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `document_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pricing_type` enum('static','custom') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'static',
  `industry_vertical_id` bigint unsigned NOT NULL,
  `department_id` bigint unsigned NOT NULL,
  `service_offering_id` bigint unsigned NOT NULL,
  `unit_of_measurement_id` bigint unsigned NOT NULL,
  `description_id` bigint unsigned NOT NULL,
  `currency_id` bigint unsigned NOT NULL,
  `rate` decimal(12,2) NOT NULL,
  `project_management_cost` decimal(12,2) DEFAULT NULL,
  `vendor_cost` decimal(12,2) DEFAULT NULL,
  `infrastructure_cost` decimal(12,2) DEFAULT NULL,
  `overhead_percentage` decimal(5,2) DEFAULT NULL,
  `margin_percentage` decimal(5,2) DEFAULT NULL,
  `volume` decimal(12,2) DEFAULT NULL,
  `volume_based_discount` decimal(12,2) DEFAULT NULL,
  `conversion_rate` decimal(12,4) DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `approved_by` bigint unsigned DEFAULT NULL,
  `approval_status` enum('draft','pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `submitted_at` timestamp NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `approval_note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_industry_vertical_id` (`industry_vertical_id`),
  KEY `idx_department_id` (`department_id`),
  KEY `idx_service_offering_id` (`service_offering_id`),
  KEY `idx_unit_of_measurement_id` (`unit_of_measurement_id`),
  KEY `idx_description_id` (`description_id`),
  KEY `idx_currency_id` (`currency_id`),
  KEY `idx_created_by` (`created_by`),
  KEY `idx_updated_by` (`updated_by`),
  KEY `idx_approved_by` (`approved_by`),
  CONSTRAINT `pm_approved_by_fk` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `pm_created_by_fk` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `pm_currency_id_fk` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `pm_department_id_fk` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `pm_description_id_fk` FOREIGN KEY (`description_id`) REFERENCES `descriptions` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `pm_industry_vertical_id_fk` FOREIGN KEY (`industry_vertical_id`) REFERENCES `industry_verticals` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `pm_service_offering_id_fk` FOREIGN KEY (`service_offering_id`) REFERENCES `service_offerings` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `pm_unit_of_measurement_id_fk` FOREIGN KEY (`unit_of_measurement_id`) REFERENCES `unit_of_measurements` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `pm_updated_by_fk` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pricing_masters`
--

LOCK TABLES `pricing_masters` WRITE;
/*!40000 ALTER TABLE `pricing_masters` DISABLE KEYS */;
INSERT INTO `pricing_masters` VALUES (9,NULL,'static',1,1,2,2,2,1,1.00,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'JKpricing',1,11,11,11,'approved','2025-10-01 06:25:34','2025-10-01 06:26:47','approved','2025-10-01 06:25:34','2025-10-14 05:34:57','2025-10-14 05:34:57'),(12,NULL,'static',5,10,24,9,5,4,1.00,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Property-bill',1,11,11,NULL,'pending','2025-10-13 11:50:35',NULL,NULL,'2025-10-09 12:40:44','2025-10-13 12:42:21','2025-10-13 12:42:21'),(15,NULL,'static',7,1,7,10,2,1,10000.00,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Standard Pricing',1,11,11,11,'approved','2025-10-13 13:08:26','2025-10-13 13:08:58','approve','2025-10-13 13:08:26','2025-10-13 13:08:58',NULL);
/*!40000 ALTER TABLE `pricing_masters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_delivery_frequencies`
--

DROP TABLE IF EXISTS `project_delivery_frequencies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `project_delivery_frequencies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_delivery_frequencies_created_by_foreign` (`created_by`),
  KEY `project_delivery_frequencies_updated_by_foreign` (`updated_by`),
  CONSTRAINT `project_delivery_frequencies_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `project_delivery_frequencies_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_delivery_frequencies`
--

LOCK TABLES `project_delivery_frequencies` WRITE;
/*!40000 ALTER TABLE `project_delivery_frequencies` DISABLE KEYS */;
INSERT INTO `project_delivery_frequencies` VALUES (1,'One-time',1,NULL,NULL,'2025-08-25 03:58:57','2025-08-25 03:58:57'),(2,'Monthly',1,NULL,NULL,'2025-08-25 03:58:57','2025-08-25 03:58:57'),(3,'Weekly',1,NULL,NULL,'2025-08-25 03:58:57','2025-08-25 03:58:57'),(4,'Day',1,11,11,'2025-10-10 04:05:07','2025-10-10 04:05:07');
/*!40000 ALTER TABLE `project_delivery_frequencies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_intakes`
--

DROP TABLE IF EXISTS `project_intakes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `project_intakes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint unsigned DEFAULT NULL,
  `property_manager_id` bigint unsigned DEFAULT NULL,
  `request_received_date` date DEFAULT NULL,
  `delivered_date` date DEFAULT NULL,
  `priority_id` bigint unsigned DEFAULT NULL,
  `status_master_id` bigint unsigned DEFAULT NULL,
  `property_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `property_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tenant_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tenant_or_lease_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `suite_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `premises_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `no_of_documents` int unsigned NOT NULL DEFAULT '0',
  `pdf_names` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sb_queries` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `type_of_queries` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_response` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `query_status_id` bigint unsigned DEFAULT NULL,
  `query_raised_date` date DEFAULT NULL,
  `query_resolved_date` date DEFAULT NULL,
  `abstractor_id` bigint unsigned DEFAULT NULL,
  `abstraction_start_date` date DEFAULT NULL,
  `abstract_completion_date` date DEFAULT NULL,
  `reviewer_id` bigint unsigned DEFAULT NULL,
  `review_completion_date` date DEFAULT NULL,
  `sense_check_ddr_id` bigint unsigned DEFAULT NULL,
  `sense_check_completion_date` date DEFAULT NULL,
  `proposed_delivery_date` date DEFAULT NULL,
  `actual_delivered_date` date DEFAULT NULL,
  `feedback_received_date` date DEFAULT NULL,
  `feedback_completion_date` date DEFAULT NULL,
  `fb_date_received` date DEFAULT NULL,
  `fb_customer_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fb_category_id` bigint unsigned DEFAULT NULL,
  `fb_customer_comments` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `fb_sb_response` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `fb_feedback_completion_date` date DEFAULT NULL,
  `fb_feedback` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_month` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cost_usd` decimal(12,2) NOT NULL DEFAULT '0.00',
  `type_of_lease_id` bigint unsigned DEFAULT NULL,
  `type_of_work_id` bigint unsigned DEFAULT NULL,
  `language_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `non_english_pages` int unsigned NOT NULL DEFAULT '0',
  `invoice_format_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_intakes_parent_id_index` (`parent_id`),
  KEY `project_intakes_property_manager_id_index` (`property_manager_id`),
  KEY `project_intakes_priority_id_index` (`priority_id`),
  KEY `project_intakes_status_master_id_index` (`status_master_id`),
  KEY `project_intakes_query_status_id_index` (`query_status_id`),
  KEY `project_intakes_abstractor_id_index` (`abstractor_id`),
  KEY `project_intakes_reviewer_id_index` (`reviewer_id`),
  KEY `project_intakes_sense_check_ddr_id_index` (`sense_check_ddr_id`),
  KEY `project_intakes_fb_category_id_index` (`fb_category_id`),
  KEY `project_intakes_type_of_lease_id_index` (`type_of_lease_id`),
  KEY `project_intakes_type_of_work_id_index` (`type_of_work_id`),
  KEY `project_intakes_language_code_index` (`language_code`),
  KEY `project_intakes_invoice_format_id_index` (`invoice_format_id`),
  KEY `pi_suite_id_idx` (`suite_id`)
) ENGINE=InnoDB AUTO_INCREMENT=358 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_intakes`
--

LOCK TABLES `project_intakes` WRITE;
/*!40000 ALTER TABLE `project_intakes` DISABLE KEYS */;
INSERT INTO `project_intakes` VALUES (10,11,5,'2025-09-08',NULL,NULL,NULL,'dfsfs','sd','dsf','dsf',NULL,'dsf',2,'fsf','sdf','fsf','sdf',NULL,NULL,NULL,6,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,NULL,0,NULL,'2025-09-08 06:09:23','2025-09-08 06:09:23'),(18,2,3,'2025-09-11','2025-09-10',2,3,'Gambhu Change','dfgdfgdf','dfgdfgdfg','dfgfgdf',NULL,'dffgddfgfdg',0,'dfgdfgdfgfd','dfgdfgdfg','2','dfgdfgdfgd',1,'2025-09-11','2025-09-11',8,'2025-09-11','2025-09-11',10,'2025-09-11',10,'2025-09-11','2025-09-11','2025-09-11','2025-09-11',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-09',0.00,2,1,NULL,0,NULL,'2025-09-11 06:08:28','2025-09-12 02:04:45'),(20,2,2,'2025-09-11','2025-09-11',3,1,'Jayesh','sdf','dsfsdf','sdfsd',NULL,'sdfsdf',2,'dsfsdf','dfg',NULL,'sdfsdfs',NULL,'2025-09-11','2025-09-11',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,1,1,NULL,0,NULL,'2025-09-11 06:31:49','2025-09-23 01:25:22'),(22,2,2,'2025-09-11','2025-09-11',2,1,'Test test','test s',NULL,'dgdfgdfg',NULL,NULL,0,NULL,NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,1,1,NULL,0,NULL,'2025-09-11 06:45:47','2025-09-23 01:25:22'),(23,14,12,'2025-01-03','2025-01-03',1,1,'0031','Property A','OH-CIN-11120 Kenwood Rd.','0031L',NULL,'11120 KENWOOD ROAD, Cinncinnati, OH, United States',3,'1. 1.1.3.11.1.13.2 Cincinnati Lease - Kenwood S 2. 1.1.3.11.1.13.3 230501 Second Amendment to S 3. Kenwood Rd--250623 Third Amendment for Storage space - Verdantas',NULL,'3','05.27.25: These are garage units used for storage, the documents were include in the first round of documents sent , the folder is named Cincinnati Kenwood, there a are 3 documents.',1,'2025-01-03','2025-01-03',24,'2025-10-01','2025-10-01',25,'2025-09-27',26,NULL,'2025-09-13','2025-09-20',NULL,NULL,'2025-01-16','Customer1',1,'Good','None','2025-01-16',NULL,'2025-09',0.00,1,1,NULL,0,NULL,'2025-09-29 09:53:19','2025-10-01 06:36:07'),(24,17,27,'2025-09-01','2025-09-05',2,1,'0057','Property ABC','CA-IRV-LSE-2600 Michelson Drive','0057L','07-710','2600 Michelson Drive, Irvine, CA, 92612',3,'Medford_ Civil West Lease Agreement.pdf','Per Mail dated 06/22/2022, Lease term expired on 08/31/2022. Kindly confirm can we abstract the Lease with Terminated status or move it to Do No Abstract.','1','05.27.25: First part is same as above and a screen shot of the ED was provided (which is Middleton, CT)',1,'2025-09-10','2025-09-15',31,'2025-09-10','2025-09-15',NULL,NULL,NULL,NULL,'2025-09-16','2025-09-20','2025-09-21','2025-09-25','2025-09-26','Ok',2,NULL,NULL,'2025-09-30',NULL,'2025-09',0.00,1,1,'2',2,1,'2025-10-07 12:10:10','2025-10-07 12:43:25'),(93,3,NULL,'2025-01-07',NULL,NULL,NULL,'238',NULL,'Pita Way Cool Springs Blvd., LLC',NULL,'330-1','500 Cool Springs Boulevard, The Bulls-Eye, Space B, Franklin, TN 37067, United States',2,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,47,'2025-10-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'15',0,NULL,'2025-10-08 10:06:19','2025-10-08 10:21:27'),(94,3,NULL,'2025-01-07',NULL,NULL,NULL,'238',NULL,'Route 65, LLC',NULL,'330-3','500 Cool Springs Boulevard, Franklin, TN 37067, United States',4,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,47,'2025-10-02',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'16',0,NULL,'2025-10-08 10:06:19','2025-10-08 10:21:27'),(95,3,NULL,'2025-01-07',NULL,NULL,NULL,'238',NULL,'Select Comfort Retail Corporation dba Sleep Number',NULL,'330-5','500 Cool Springs Boulevard, Franklin, TN 37067, United States',4,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,47,'2025-10-03',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'17',0,NULL,'2025-10-08 10:06:19','2025-10-08 10:21:27'),(96,3,NULL,'2025-01-07',NULL,NULL,NULL,'247',NULL,'La Michoacana Paleteria & Bakery Springfield, LLC',NULL,'330-6','Oaks Village, North Thomson Lane, Murfreesboro, TN, United States',5,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,48,'2025-09-01',NULL,51,NULL,55,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'18',0,NULL,'2025-10-08 10:06:19','2025-10-08 10:21:27'),(97,3,NULL,'2025-01-07',NULL,NULL,NULL,'247',NULL,'MGC Southeast Inc.',NULL,'330-7','North Thomson Lane, Murfreesboro, TN, United States',5,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,48,NULL,NULL,51,NULL,55,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'19',0,NULL,'2025-10-08 10:06:19','2025-10-08 10:21:27'),(98,3,NULL,'2025-01-07',NULL,NULL,NULL,'247',NULL,'Greenvue Title & Escrow, LLC',NULL,'350-0','North Thomson Lane, Murfreesboro, TN, United States',4,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,48,NULL,NULL,51,NULL,55,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'20',0,NULL,'2025-10-08 10:06:19','2025-10-08 10:21:27'),(99,3,NULL,'2025-01-07',NULL,NULL,NULL,'247',NULL,'SRLA Murfreesboro',NULL,'101','North Thomson Lane, Murfreesboro, TN, United States',2,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,49,NULL,NULL,52,NULL,54,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'21',0,NULL,'2025-10-08 10:06:19','2025-10-08 10:21:27'),(100,3,NULL,'2025-01-07',NULL,NULL,NULL,'247',NULL,'YC Partneship LLC',NULL,'102','North Thomson Lane, Murfreesboro, TN, United States',6,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,49,NULL,NULL,52,NULL,54,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'22',0,NULL,'2025-10-08 10:06:19','2025-10-08 10:21:27'),(101,3,NULL,'2025-01-07',NULL,NULL,NULL,'284',NULL,'Taw Sports Cool Springs, LLC',NULL,'103','420 Cool Springs Blvd, Franklin, TN 37067, United States',7,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,49,NULL,NULL,52,NULL,54,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'23',0,NULL,'2025-10-08 10:06:19','2025-10-08 10:21:27'),(102,3,NULL,'2025-01-07',NULL,NULL,NULL,'284',NULL,'RPMTN, LLC dba InMotion Wellness Studio',NULL,'104','420 Cool Springs Blvd, Suite 110, Franklin, TN 37067, United States',4,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,47,'2025-10-04',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'24',0,NULL,'2025-10-08 10:06:19','2025-10-08 10:21:27'),(103,3,NULL,'2025-01-07',NULL,NULL,NULL,'284',NULL,'Salon Blonde Franklin, LLC',NULL,'105','420 Cool Springs Boulevard, Franklin, TN 37067, Unied States.',6,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,47,'2025-10-05',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'25',0,NULL,'2025-10-08 10:06:19','2025-10-08 10:21:27'),(104,3,NULL,'2025-01-07',NULL,NULL,NULL,'284',NULL,'Subway Real Estate, LLC',NULL,'106','420 Cool Springs Blvd. Suite 105, Franklin, TN 37067, United STates.',5,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,47,'2025-10-06',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'26',0,NULL,'2025-10-08 10:06:19','2025-10-08 10:21:27'),(105,3,NULL,'2025-01-07',NULL,NULL,NULL,'284',NULL,'Brown Bag, LLC',NULL,'MORRI','420 Cool Springs Blvd, Suite 135, Franklin, Tennessee 37067, United States',3,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,48,NULL,NULL,51,NULL,55,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,NULL,0,NULL,'2025-10-08 10:06:19','2025-10-08 10:21:27'),(106,3,NULL,'2025-01-07',NULL,NULL,NULL,'284',NULL,'Business for Good, LLC',NULL,'1260-0','420 Cool Springs Blvd., Suite 125, Franklin, Williamson County, Tennessee 37067, United States',6,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,48,NULL,NULL,51,NULL,55,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,NULL,0,NULL,'2025-10-08 10:06:19','2025-10-08 10:21:27'),(107,3,NULL,'2025-01-07',NULL,NULL,NULL,'284',NULL,'JoyRay, Inc',NULL,'1260-1','The Shoppes at Thoroughbred Square 1- CC (284), Franklin, Tennessee, United States',2,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,48,NULL,NULL,51,NULL,55,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,NULL,0,NULL,'2025-10-08 10:06:19','2025-10-08 10:21:27'),(108,3,NULL,'2025-01-07',NULL,NULL,2,'247',NULL,'451 N Thompson Ln, Murfreesboro, TN, 37129, United States.',NULL,'1260-2','451 N Thompson Ln, Murfreesboro, TN, 37129, United States.',3,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,49,NULL,NULL,52,NULL,54,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,NULL,0,NULL,'2025-10-08 10:06:19','2025-10-08 10:21:27'),(109,3,NULL,'2025-01-07',NULL,NULL,NULL,'LR',NULL,'Chatime',NULL,'1260-3, 1260-5','CF Lime Ridge Mall, Store No 0277C, Hamilton, Ontario, Canada',2,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,49,NULL,NULL,52,NULL,54,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,NULL,0,NULL,'2025-10-08 10:06:19','2025-10-08 10:21:27'),(110,3,NULL,'2025-01-07',NULL,NULL,NULL,'LR',NULL,'Fast Time Watch & Jewellery Repair',NULL,'2222','CF Lime Ridge Mall, Store No. Z201, Hamilton, ON L9A 4X5, Canada',2,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,49,NULL,NULL,52,NULL,54,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,NULL,0,NULL,'2025-10-08 10:06:19','2025-10-08 10:21:27'),(111,3,NULL,'2025-01-07',NULL,NULL,NULL,'LR',NULL,'Jump+',NULL,'2224','Unit 0261C, Lime Ridge Mall, Hamilton, Ontario',4,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,49,NULL,NULL,52,NULL,54,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,NULL,0,NULL,'2025-10-08 10:06:19','2025-10-08 10:21:27'),(112,3,NULL,'2025-01-07',NULL,NULL,NULL,'LR',NULL,'Bikini Village',NULL,'101','CF Lime Ridge Mall, Store 0160C, Hamilton, ON L9A 4X5, Canada.',1,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,49,NULL,NULL,52,NULL,54,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,NULL,0,NULL,'2025-10-08 10:06:19','2025-10-08 10:21:27'),(153,1,NULL,'2025-01-03',NULL,1,NULL,'31',NULL,'OH-CIN-11120 Kenwood Rd.','0031L','01-100','11120 KENWOOD ROAD, Cinncinnati, OH, United States',3,'1. 1.1.3.11.1.13.2 Cincinnati Lease - Kenwood S2. 1.1.3.11.1.13.3 230501 Second Amendment to S',NULL,NULL,NULL,NULL,NULL,NULL,35,NULL,NULL,38,NULL,41,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,0.00,1,1,'30',0,1,'2025-10-08 12:03:22','2025-10-08 12:09:28'),(154,1,NULL,'2025-01-03',NULL,1,NULL,'0070\'',NULL,'DE-DOV-LSE-1060 S Governors Avenue','0070L','01-110','1060 S Governors Avenue, Unit 101, Dover, DE 19904, United States',4,'1. 1.1.3.11.1.13.2 Cincinnati Lease - Kenwood S2. 1.1.3.11.1.13.3 230501 Second Amendment to S',NULL,NULL,NULL,NULL,NULL,NULL,35,NULL,NULL,38,NULL,41,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,0.00,2,2,'31',0,2,'2025-10-08 12:03:22','2025-10-08 12:09:28'),(155,1,NULL,'2025-01-03',NULL,1,2,NULL,NULL,'OH-NEW-LSE-59 Grant Street',NULL,'01-120, 02-100, 03-100, 05-100','59 Grant Street, Newark, OH, 43055, United States',3,'1. 1.1.3.11.1.13.2 Cincinnati Lease - Kenwood S2. 1.1.3.11.1.13.3 230501 Second Amendment to S',NULL,NULL,NULL,NULL,NULL,NULL,35,NULL,NULL,38,NULL,41,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,3,3,'32',0,3,'2025-10-08 12:03:22','2025-10-08 12:09:28'),(156,1,NULL,'2025-01-03',NULL,2,3,'57',NULL,'CA-IRV-LSE-2600 Michelson Drive','0057L','04-100, 06-600','2600 Michelson Drive, Irvine, CA, 92612',3,'1. 1.1.3.11.1.13.2 Cincinnati Lease - Kenwood S2. 1.1.3.11.1.13.3 230501 Second Amendment to S',NULL,NULL,NULL,NULL,NULL,NULL,36,NULL,NULL,39,NULL,42,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,4,4,'33',0,4,'2025-10-08 12:03:22','2025-10-08 12:09:28'),(157,1,NULL,'2025-01-03',NULL,2,NULL,'51',NULL,'VA-VBC-LSE-125 78th Street','0051L','07-710','125 78th street, Virginia Beach, VA, 2351, United States',3,'1. 1.1.3.11.1.13.2 Cincinnati Lease - Kenwood S2. 1.1.3.11.1.13.3 230501 Second Amendment to S',NULL,NULL,NULL,NULL,NULL,NULL,36,NULL,NULL,39,NULL,42,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,5,'34',0,5,'2025-10-08 12:03:22','2025-10-08 12:09:28'),(158,1,NULL,'2025-01-03',NULL,2,NULL,'94',NULL,'OR-CBY-LSE-486 E. Street','0094L','07-720','486 E Street, Coos Bay, OR, 97420, United States',1,'1. 1.1.3.11.1.13.2 Cincinnati Lease - Kenwood S2. 1.1.3.11.1.13.3 230501 Second Amendment to S',NULL,NULL,NULL,NULL,NULL,NULL,36,NULL,NULL,39,NULL,42,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,6,'35',0,NULL,'2025-10-08 12:03:22','2025-10-08 12:09:28'),(159,1,NULL,'2025-01-03',NULL,3,NULL,'95',NULL,'OR-MED-LSE-830 O\'Hard Pkwy, Suite 102','0095L','07-730','830 O\'Hare Parkway, Ste 102, Medford, OR, 97504, United States',2,'1. 1.1.3.11.1.13.2 Cincinnati Lease - Kenwood S2. 1.1.3.11.1.13.3 230501 Second Amendment to S',NULL,NULL,NULL,NULL,NULL,NULL,37,NULL,NULL,40,NULL,43,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,7,'1',0,NULL,'2025-10-08 12:03:22','2025-10-08 12:09:28'),(160,1,NULL,'2025-01-03',NULL,4,NULL,NULL,NULL,'MD-BLA-SL-1614 E Churchville Rd, Suite 3LL',NULL,'08-830','1614 E Churchville Rd, Suite 3LL, Bel Air, MD 21015, United States',2,'1. 1.1.3.11.1.13.2 Cincinnati Lease - Kenwood S2. 1.1.3.11.1.13.3 230501 Second Amendment to S',NULL,NULL,NULL,NULL,NULL,NULL,37,NULL,NULL,40,NULL,43,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,8,'2',0,NULL,'2025-10-08 12:03:22','2025-10-08 12:09:28'),(161,1,NULL,'2025-01-03',NULL,4,NULL,'81',NULL,'NH-MAN-186 Granite Street','0081L','08-840','186 Granite Street, Manchester, NH, United States.',4,'1. 1.1.3.11.1.13.2 Cincinnati Lease - Kenwood S2. 1.1.3.11.1.13.3 230501 Second Amendment to S',NULL,NULL,NULL,NULL,NULL,NULL,37,NULL,NULL,40,NULL,43,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,9,'3',0,NULL,'2025-10-08 12:03:22','2025-10-08 12:09:28'),(162,1,NULL,'2025-01-03',NULL,4,NULL,'108',NULL,'OH-DUB-6397 Emerald Pkwy','0108L','08-850','6397 Emerald Pkwy, Dublin, OH, 43016, United States',9,'1. 1.1.3.11.1.13.2 Cincinnati Lease - Kenwood S2. 1.1.3.11.1.13.3 230501 Second Amendment to S',NULL,NULL,NULL,NULL,NULL,NULL,35,NULL,NULL,38,NULL,41,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,10,'4',0,NULL,'2025-10-08 12:03:22','2025-10-08 12:09:28'),(163,1,NULL,'2025-01-03',NULL,4,NULL,'29',NULL,'OH-BED-LSE-Hemisphere way','0029L','3078','Hemisphere way, Bedford, OH, United States',7,'1. 1.1.3.11.1.13.2 Cincinnati Lease - Kenwood S2. 1.1.3.11.1.13.3 230501 Second Amendment to S',NULL,NULL,NULL,NULL,NULL,NULL,35,NULL,NULL,38,NULL,41,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,11,'5',0,NULL,'2025-10-08 12:03:22','2025-10-08 12:09:28'),(164,1,NULL,'2025-01-03',NULL,5,NULL,'93',NULL,'OH-TOL-LSE-219 S. Erie Street','0093L','3080','219 S. Erie Street, Toledo, OH, United States',1,'1. 1.1.3.11.1.13.2 Cincinnati Lease - Kenwood S2. 1.1.3.11.1.13.3 230501 Second Amendment to S',NULL,NULL,NULL,NULL,NULL,NULL,35,NULL,NULL,38,NULL,41,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,12,'6',0,NULL,'2025-10-08 12:03:22','2025-10-08 12:09:28'),(165,1,NULL,'2025-01-03',NULL,6,NULL,'49',NULL,'SC-AND-LSE-106 E. Benson St','0049L','3082','106 E. Benson Street, Anderson, South Carolina, United States',6,'1. 1.1.3.11.1.13.2 Cincinnati Lease - Kenwood S2. 1.1.3.11.1.13.3 230501 Second Amendment to S',NULL,NULL,NULL,NULL,NULL,NULL,36,NULL,NULL,39,NULL,42,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,13,'7',0,NULL,'2025-10-08 12:03:22','2025-10-08 12:09:28'),(166,1,NULL,'2025-01-03',NULL,6,NULL,'50',NULL,'VA-CHS-LSE-1511 Allecingie Pkwy N.','0050L','3106, 3120','11511 Allecingie parkway, North Chesterfield, VA, 23235, United States',2,'1. 1.1.3.11.1.13.2 Cincinnati Lease - Kenwood S2. 1.1.3.11.1.13.3 230501 Second Amendment to S',NULL,NULL,NULL,NULL,NULL,NULL,36,NULL,NULL,39,NULL,42,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'8',0,NULL,'2025-10-08 12:03:22','2025-10-08 12:09:28'),(167,1,NULL,'2025-01-03',NULL,6,2,NULL,NULL,'ON-POR-LSE-Pittock block',NULL,'3124','Pittock block, portland, ON, United States.',3,'1. 1.1.3.11.1.13.2 Cincinnati Lease - Kenwood S2. 1.1.3.11.1.13.3 230501 Second Amendment to S',NULL,NULL,NULL,NULL,NULL,NULL,36,NULL,NULL,39,NULL,42,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'9',0,NULL,'2025-10-08 12:03:22','2025-10-08 12:09:28'),(168,1,NULL,'2025-01-03',NULL,NULL,2,NULL,NULL,'WA-EVE-LSE-711 - 100th Street',NULL,'3132','711 - 100th Street, S.E.Everett, WA, United States',4,'1. 1.1.3.11.1.13.2 Cincinnati Lease - Kenwood S2. 1.1.3.11.1.13.3 230501 Second Amendment to S',NULL,NULL,NULL,NULL,NULL,NULL,37,NULL,NULL,40,NULL,43,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'10',0,NULL,'2025-10-08 12:03:22','2025-10-08 12:09:28'),(169,1,NULL,'2025-01-03',NULL,NULL,2,NULL,NULL,'CO-FOR-LSE-430 North college Avenue',NULL,'3140','430 North College Avenue, Fort Collins, CO, United States',8,'1. 1.1.3.11.1.13.2 Cincinnati Lease - Kenwood S2. 1.1.3.11.1.13.3 230501 Second Amendment to S',NULL,NULL,NULL,NULL,NULL,NULL,37,NULL,NULL,40,NULL,43,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'11',0,NULL,'2025-10-08 12:03:22','2025-10-08 12:09:28'),(170,1,NULL,'2025-01-03',NULL,NULL,NULL,'1',NULL,'CO-LIT-LSE-300 Plaza Drive, Suite 320','0001L','3150','300 PLZ DR Ste 320, Littleton, CO, United States',7,'1. 1.1.3.11.1.13.2 Cincinnati Lease - Kenwood S2. 1.1.3.11.1.13.3 230501 Second Amendment to S',NULL,NULL,NULL,NULL,NULL,NULL,37,NULL,NULL,40,NULL,43,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'12',0,NULL,'2025-10-08 12:03:22','2025-10-08 12:09:28'),(171,1,NULL,'2025-01-03',NULL,NULL,NULL,'47',NULL,'PA-WEX-7000 Brooktree Road','0047L','3160','7000 Brooktree Road, Wexford, PA, 15090, United States',3,'1. 1.1.3.11.1.13.2 Cincinnati Lease - Kenwood S2. 1.1.3.11.1.13.3 230501 Second Amendment to S',NULL,NULL,NULL,NULL,NULL,NULL,37,NULL,NULL,40,NULL,43,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'13',0,NULL,'2025-10-08 12:03:22','2025-10-08 12:09:28'),(172,1,NULL,'2025-01-03',NULL,NULL,NULL,'4',NULL,'PA-WBR-LSE-613 Baltimore Drive','0004L','330-0','613 Baltimore Dr, Wilkes Barre, PA, United States',4,'1. 1.1.3.11.1.13.2 Cincinnati Lease - Kenwood S2. 1.1.3.11.1.13.3 230501 Second Amendment to S',NULL,NULL,NULL,NULL,NULL,NULL,37,NULL,NULL,40,NULL,43,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'14',0,NULL,'2025-10-08 12:03:22','2025-10-08 12:09:28'),(193,6,NULL,'2025-01-07',NULL,NULL,NULL,'238',NULL,'Pita Way Cool Springs Blvd., LLC','t0000113','330-1','500 Cool Springs Boulevard, The Bulls-Eye, Space B, Franklin, TN 37067, United States',2,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,47,NULL,NULL,50,NULL,53,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'15',0,NULL,'2025-10-09 05:24:37','2025-10-13 07:06:36'),(194,6,NULL,'2025-01-07',NULL,NULL,NULL,'238',NULL,'Route 65, LLC','t0000114','330-3','500 Cool Springs Boulevard, Franklin, TN 37067, United States',4,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,47,NULL,NULL,50,NULL,53,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'16',0,NULL,'2025-10-09 05:24:37','2025-10-13 07:06:36'),(195,6,NULL,'2025-01-07',NULL,NULL,NULL,'238',NULL,'Select Comfort Retail Corporation dba Sleep Number','t0000112','330-5','500 Cool Springs Boulevard, Franklin, TN 37067, United States',4,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,47,NULL,NULL,50,NULL,53,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'17',0,NULL,'2025-10-09 05:24:37','2025-10-13 07:06:36'),(196,6,NULL,'2025-01-07',NULL,NULL,NULL,'247',NULL,'La Michoacana Paleteria & Bakery Springfield, LLC','t0000145','330-6','Oaks Village, North Thomson Lane, Murfreesboro, TN, United States',5,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,48,NULL,NULL,51,NULL,55,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'18',0,NULL,'2025-10-09 05:24:37','2025-10-13 07:06:36'),(197,6,NULL,'2025-01-07',NULL,NULL,NULL,'247',NULL,'MGC Southeast Inc.','t0000146','330-7','North Thomson Lane, Murfreesboro, TN, United States',5,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,48,NULL,NULL,51,NULL,55,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'19',0,NULL,'2025-10-09 05:24:37','2025-10-13 07:06:36'),(198,6,NULL,'2025-01-07',NULL,NULL,NULL,'247',NULL,'Greenvue Title & Escrow, LLC','t0000151','350-0','North Thomson Lane, Murfreesboro, TN, United States',4,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,48,NULL,NULL,51,NULL,55,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'20',0,NULL,'2025-10-09 05:24:37','2025-10-13 07:06:36'),(199,6,NULL,'2025-01-07',NULL,NULL,NULL,'247',NULL,'SRLA Murfreesboro','t0000149','101','North Thomson Lane, Murfreesboro, TN, United States',2,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,49,NULL,NULL,52,NULL,74,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'21',0,NULL,'2025-10-09 05:24:37','2025-10-13 07:06:36'),(200,6,NULL,'2025-01-07',NULL,NULL,NULL,'247',NULL,'YC Partneship LLC','t0000152','102','North Thomson Lane, Murfreesboro, TN, United States',6,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,49,NULL,NULL,52,NULL,74,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'22',0,NULL,'2025-10-09 05:24:37','2025-10-13 07:06:36'),(201,6,NULL,'2025-01-07',NULL,NULL,NULL,'284',NULL,'Taw Sports Cool Springs, LLC','t0000186','103','420 Cool Springs Blvd, Franklin, TN 37067, United States',7,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,49,NULL,NULL,52,NULL,74,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'23',0,NULL,'2025-10-09 05:24:37','2025-10-13 07:06:36'),(202,6,NULL,'2025-01-07',NULL,NULL,NULL,'284',NULL,'RPMTN, LLC dba InMotion Wellness Studio','t0000181','104','420 Cool Springs Blvd, Suite 110, Franklin, TN 37067, United States',4,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,47,NULL,NULL,50,NULL,53,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'24',0,NULL,'2025-10-09 05:24:37','2025-10-13 07:06:36'),(203,6,NULL,'2025-01-07',NULL,NULL,NULL,'284',NULL,'Salon Blonde Franklin, LLC','t0000178','105','420 Cool Springs Boulevard, Franklin, TN 37067, Unied States.',6,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,47,NULL,NULL,50,NULL,53,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'25',0,NULL,'2025-10-09 05:24:37','2025-10-13 07:06:36'),(204,6,NULL,'2025-01-07',NULL,NULL,NULL,'284',NULL,'Subway Real Estate, LLC','t0000179','106','420 Cool Springs Blvd. Suite 105, Franklin, TN 37067, United STates.',5,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,47,NULL,NULL,50,NULL,53,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'26',0,NULL,'2025-10-09 05:24:37','2025-10-13 07:06:36'),(205,6,NULL,'2025-01-07',NULL,NULL,NULL,'284',NULL,'Brown Bag, LLC','t0000187','MORRI','420 Cool Springs Blvd, Suite 135, Franklin, Tennessee 37067, United States',3,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,48,NULL,NULL,51,NULL,55,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,NULL,0,NULL,'2025-10-09 05:24:37','2025-10-13 07:06:36'),(206,6,NULL,'2025-01-07',NULL,NULL,NULL,'284',NULL,'Business for Good, LLC','t0000185','1260-0','420 Cool Springs Blvd., Suite 125, Franklin, Williamson County, Tennessee 37067, United States',6,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,48,NULL,NULL,51,NULL,55,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,NULL,0,NULL,'2025-10-09 05:24:37','2025-10-13 07:06:36'),(207,6,NULL,'2025-01-07',NULL,NULL,NULL,'284',NULL,'JoyRay, Inc','t0000184','1260-1','The Shoppes at Thoroughbred Square 1- CC (284), Franklin, Tennessee, United States',2,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,48,NULL,NULL,51,NULL,55,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,NULL,0,NULL,'2025-10-09 05:24:37','2025-10-13 07:06:36'),(208,6,NULL,'2025-01-07',NULL,NULL,2,'247',NULL,'451 N Thompson Ln, Murfreesboro, TN, 37129, United States.','t0000148','1260-2','451 N Thompson Ln, Murfreesboro, TN, 37129, United States.',3,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,49,NULL,NULL,52,NULL,74,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,NULL,0,NULL,'2025-10-09 05:24:37','2025-10-13 07:06:36'),(209,6,NULL,'2025-01-07',NULL,NULL,NULL,'LR',NULL,'Chatime','t0008014','1260-3, 1260-5','CF Lime Ridge Mall, Store No 0277C, Hamilton, Ontario, Canada',2,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,49,NULL,NULL,52,NULL,74,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,NULL,0,NULL,'2025-10-09 05:24:37','2025-10-13 07:06:36'),(210,6,NULL,'2025-01-07',NULL,NULL,NULL,'LR',NULL,'Fast Time Watch & Jewellery Repair','t0008015','2222','CF Lime Ridge Mall, Store No. Z201, Hamilton, ON L9A 4X5, Canada',2,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,49,NULL,NULL,52,NULL,74,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,NULL,0,NULL,'2025-10-09 05:24:37','2025-10-13 07:06:36'),(211,6,NULL,'2025-01-07',NULL,NULL,NULL,'LR',NULL,'Jump+','t0008026','2224','Unit 0261C, Lime Ridge Mall, Hamilton, Ontario',4,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,49,NULL,NULL,52,NULL,74,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,NULL,0,NULL,'2025-10-09 05:24:37','2025-10-13 07:06:36'),(212,6,NULL,'2025-01-07',NULL,NULL,NULL,'LR',NULL,'Bikini Village','t0008022','101','CF Lime Ridge Mall, Store 0160C, Hamilton, ON L9A 4X5, Canada.',1,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,49,NULL,NULL,52,NULL,74,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,NULL,0,NULL,'2025-10-09 05:24:37','2025-10-13 07:06:36'),(213,7,NULL,'2025-01-07',NULL,NULL,1,'LR','Testing','Best Buy Mobile','t0008021','102','CF Lime Ridge Mall, Store 0450, Hamilton, ON L9A 4X5, Canada.',9,'1. Fifth Lease Amending Agreement -December 8, 2022- FULLY EXECUTED.pdf 2. FOURTH LEASE EXTENSION _ AMENDING AGREEMENT - DECEMBER 20, 2021.pdf','No Issues',NULL,'-',NULL,NULL,NULL,NULL,NULL,NULL,67,NULL,69,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'10-2025',0.00,NULL,NULL,NULL,0,NULL,'2025-10-09 06:20:59','2025-10-09 13:18:03'),(214,7,NULL,'2025-01-07',NULL,NULL,1,'LR',NULL,'Lime Ridge Mall Dental Office','t0008016','103','Store No. 0241C, Lime Ridge Mall, Hamilton, Ontario',6,'1. Fifth Lease Amending Agreement -December 8, 2022- FULLY EXECUTED.pdf 2. FOURTH LEASE EXTENSION _ AMENDING AGREEMENT - DECEMBER 20, 2021.pdf','No Issues',NULL,'-',NULL,NULL,NULL,65,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'10-2025',0.00,NULL,NULL,NULL,0,NULL,'2025-10-09 06:20:59','2025-10-09 13:02:50'),(215,7,NULL,'2025-01-07',NULL,NULL,1,'LR',NULL,'MAC','t0008018','104','Store No. 0224, CF Lime Ridge Mall, Hamilton, Ontario',6,'1. Fifth Lease Amending Agreement -December 8, 2022- FULLY EXECUTED.pdf 2. FOURTH LEASE EXTENSION _ AMENDING AGREEMENT - DECEMBER 20, 2021.pdf','No Issues',NULL,'-',NULL,NULL,NULL,NULL,NULL,NULL,67,NULL,69,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'10-2025',0.00,NULL,NULL,NULL,0,NULL,'2025-10-09 06:20:59','2025-10-09 13:18:03'),(216,7,NULL,'2025-01-07',NULL,NULL,1,'LR',NULL,'Mr. Pretzels','t0008019','800','Store No. Z105, CF Lime Ridge Mall, Hamilton, ON, United States.',10,'1. Fifth Lease Amending Agreement -December 8, 2022- FULLY EXECUTED.pdf 2. FOURTH LEASE EXTENSION _ AMENDING AGREEMENT - DECEMBER 20, 2021.pdf','No Issues',NULL,'-',NULL,NULL,NULL,65,NULL,NULL,68,NULL,70,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'10-2025',0.00,NULL,NULL,NULL,0,NULL,'2025-10-09 06:20:59','2025-10-09 13:02:50'),(217,7,NULL,'2025-01-07',NULL,NULL,1,'LR',NULL,'Bee & Co.','t0008028','800A','CF Lime Ridge Mall, 999 Upper Wentworth Street, Store No. 0248, Hamilton, ON, L9A 4X5, Canada',1,'1. Fifth Lease Amending Agreement -December 8, 2022- FULLY EXECUTED.pdf 2. FOURTH LEASE EXTENSION _ AMENDING AGREEMENT - DECEMBER 20, 2021.pdf','No Issues',NULL,'-',NULL,NULL,NULL,66,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'01-2001',0.00,NULL,NULL,NULL,0,NULL,'2025-10-09 06:20:59','2025-10-09 13:02:50'),(218,7,NULL,'2025-01-07',NULL,NULL,1,'LR',NULL,'Baskin-Robbins','t0008033','800B','CF Lime Ridge Mall, 999 Upper Wentworth Street, Store No. 0313C, Hamilton, ON, L9A 4X5, Canada',4,'1. Fifth Lease Amending Agreement -December 8, 2022- FULLY EXECUTED.pdf 2. FOURTH LEASE EXTENSION _ AMENDING AGREEMENT - DECEMBER 20, 2021.pdf','No Issues',NULL,'-',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'01-2001',0.00,NULL,NULL,NULL,0,NULL,'2025-10-09 06:20:59','2025-10-09 13:02:50'),(219,7,NULL,'2025-01-07',NULL,NULL,1,'LR',NULL,'Butter Chicken Roti','t0008017','800C','CF Lime Ridge Mall, Store F101 , Hamilton, ON L9A 4X5, Canada.',2,'1. Fifth Lease Amending Agreement -December 8, 2022- FULLY EXECUTED.pdf 2. FOURTH LEASE EXTENSION _ AMENDING AGREEMENT - DECEMBER 20, 2021.pdf','No Issues',NULL,'-',NULL,NULL,NULL,NULL,NULL,NULL,67,NULL,69,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'01-2001',0.00,NULL,NULL,NULL,0,NULL,'2025-10-09 06:20:59','2025-10-09 13:18:03'),(220,7,NULL,'2025-01-07',NULL,NULL,1,'LR',NULL,'Glam Spot','t0008090','800D','CF Lime Ridge Mall, Store No. 0321, Hamilton, ON L9A 4X5, Canada',1,'1. Fifth Lease Amending Agreement -December 8, 2022- FULLY EXECUTED.pdf 2. FOURTH LEASE EXTENSION _ AMENDING AGREEMENT - DECEMBER 20, 2021.pdf','No Issues',NULL,'-',NULL,NULL,NULL,65,NULL,NULL,68,NULL,70,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'01-2001',0.00,NULL,NULL,NULL,0,NULL,'2025-10-09 06:20:59','2025-10-09 13:02:50'),(221,7,NULL,'2025-01-07',NULL,NULL,1,'KB',NULL,'A & W','t0008092','1337','CF Lime Ridge Mall, 999 Upper Wentworth Street, Store No. 0303A, Hamilton, ON, L9A 4X5, Canada',14,'1. Fifth Lease Amending Agreement -December 8, 2022- FULLY EXECUTED.pdf 2. FOURTH LEASE EXTENSION _ AMENDING AGREEMENT - DECEMBER 20, 2021.pdf','No Issues',NULL,'-',NULL,NULL,NULL,66,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'01-2001',0.00,NULL,NULL,NULL,0,NULL,'2025-10-09 06:20:59','2025-10-09 13:02:50'),(222,7,NULL,'2025-01-07',NULL,NULL,1,'KB',NULL,'Ashley Jewellers','t0008034','01-101','CF Lime Ridge Mall, 999 Upper Wentworth Street, Store No. 0257A, Hamilton, ON, L9A 4X5, Canada',5,'1. Fifth Lease Amending Agreement -December 8, 2022- FULLY EXECUTED.pdf 2. FOURTH LEASE EXTENSION _ AMENDING AGREEMENT - DECEMBER 20, 2021.pdf','No Issues',NULL,'-',NULL,NULL,NULL,NULL,NULL,NULL,67,NULL,69,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'01-2001',0.00,NULL,NULL,NULL,0,NULL,'2025-10-09 06:20:59','2025-10-09 13:18:03'),(223,7,NULL,'2025-01-07',NULL,NULL,1,'KB',NULL,'Coles','t0008037','02-101','CF Lime Ridge Mall, Store No. 0234, Hamilton, Ontario, Canada',9,'1. Fifth Lease Amending Agreement -December 8, 2022- FULLY EXECUTED.pdf 2. FOURTH LEASE EXTENSION _ AMENDING AGREEMENT - DECEMBER 20, 2021.pdf','No Issues',NULL,'-',NULL,NULL,NULL,65,NULL,NULL,68,NULL,70,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'01-2001',0.00,NULL,NULL,NULL,0,NULL,'2025-10-09 06:20:59','2025-10-09 13:02:50'),(224,7,NULL,'2025-01-07',NULL,NULL,1,'KB',NULL,'Bank of Montreal','t0008048','03-301','CF Lime Ridge Mall, 999 Upper Wentworth Street, Store No. 0135B, Hamilton, ON, L9A 4X5, Canada',4,'1. Fifth Lease Amending Agreement -December 8, 2022- FULLY EXECUTED.pdf 2. FOURTH LEASE EXTENSION _ AMENDING AGREEMENT - DECEMBER 20, 2021.pdf','No Issues',NULL,'-',NULL,NULL,NULL,66,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'01-2001',0.00,NULL,NULL,NULL,0,NULL,'2025-10-09 06:20:59','2025-10-09 13:02:50'),(225,7,NULL,'2025-01-07',NULL,NULL,1,'KB',NULL,'Continental Currency Exchange','t0008023','101','CF Lime Ridge Mall, Store No. 0136A Hamilton, Ontario, Canada',1,'1. Fifth Lease Amending Agreement -December 8, 2022- FULLY EXECUTED.pdf 2. FOURTH LEASE EXTENSION _ AMENDING AGREEMENT - DECEMBER 20, 2021.pdf','No Issues',NULL,'-',NULL,NULL,NULL,NULL,NULL,NULL,67,NULL,69,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'01-2001',0.00,NULL,NULL,NULL,0,NULL,'2025-10-09 06:20:59','2025-10-09 13:18:03'),(226,7,NULL,'2025-01-07',NULL,NULL,1,'KB',NULL,'Lenscrafters','t0008038','0102A','Store No. 0276, Lime Ridge Mall, Hamilton, Ontario',2,'1. Fifth Lease Amending Agreement -December 8, 2022- FULLY EXECUTED.pdf 2. FOURTH LEASE EXTENSION _ AMENDING AGREEMENT - DECEMBER 20, 2021.pdf','No Issues',NULL,'-',NULL,NULL,NULL,65,NULL,NULL,68,NULL,70,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'01-2001',0.00,NULL,NULL,NULL,0,NULL,'2025-10-09 06:20:59','2025-10-09 13:02:50'),(227,7,NULL,'2025-01-07',NULL,NULL,1,'KB',NULL,'Pandora','t0008049','200','Unit #0120B, Lime Ridge Mall, Hamilton, Ontario',1,'1. Fifth Lease Amending Agreement -December 8, 2022- FULLY EXECUTED.pdf 2. FOURTH LEASE EXTENSION _ AMENDING AGREEMENT - DECEMBER 20, 2021.pdf','No Issues',NULL,'-',NULL,NULL,NULL,66,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'01-2001',0.00,NULL,NULL,NULL,0,NULL,'2025-10-09 06:20:59','2025-10-09 13:02:50'),(228,7,NULL,'2025-01-07',NULL,NULL,1,'KB',NULL,'Telze','t0008047','101','Store No. 0136B, CF Lime Ridge Mall, Hamilton, ON, Canada',9,'1. Fifth Lease Amending Agreement -December 8, 2022- FULLY EXECUTED.pdf 2. FOURTH LEASE EXTENSION _ AMENDING AGREEMENT - DECEMBER 20, 2021.pdf','No Issues',NULL,'-',NULL,NULL,NULL,NULL,NULL,NULL,67,NULL,69,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'01-2001',0.00,NULL,NULL,NULL,0,NULL,'2025-10-09 06:20:59','2025-10-09 13:18:03'),(229,7,NULL,'2025-01-01',NULL,NULL,1,'-',NULL,'-','TBD-02','106','1420 Fifth Avenue',2,'1. Fifth Lease Amending Agreement -December 8, 2022- FULLY EXECUTED.pdf 2. FOURTH LEASE EXTENSION _ AMENDING AGREEMENT - DECEMBER 20, 2021.pdf','No Issues',NULL,'-',NULL,NULL,NULL,65,NULL,NULL,68,NULL,70,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'01-2001',0.00,NULL,NULL,NULL,0,NULL,'2025-10-09 06:20:59','2025-10-09 13:02:50'),(230,7,NULL,'2025-01-01',NULL,NULL,1,'-',NULL,'-','TBD-04','102','1980 Festival Plaza Drive',2,'1. Fifth Lease Amending Agreement -December 8, 2022- FULLY EXECUTED.pdf 2. FOURTH LEASE EXTENSION _ AMENDING AGREEMENT - DECEMBER 20, 2021.pdf','No Issues',NULL,'-',NULL,NULL,NULL,66,NULL,NULL,67,NULL,69,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'01-2001',0.00,NULL,NULL,NULL,0,NULL,'2025-10-09 06:20:59','2025-10-09 13:18:03'),(231,7,NULL,'2025-01-01',NULL,NULL,1,'-',NULL,'-','TBD-03','104','165 Broadway (Sublease)',2,'1. Fifth Lease Amending Agreement -December 8, 2022- FULLY EXECUTED.pdf 2. FOURTH LEASE EXTENSION _ AMENDING AGREEMENT - DECEMBER 20, 2021.pdf','No Issues',NULL,'-',NULL,NULL,NULL,65,NULL,NULL,68,NULL,70,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'01-2001',0.00,NULL,NULL,NULL,0,NULL,'2025-10-09 06:20:59','2025-10-09 13:02:50'),(232,7,NULL,'2025-01-03',NULL,NULL,1,'-',NULL,'-','TBD-05','01-101','17 Market Avenue Southwest',2,'1. Fifth Lease Amending Agreement -December 8, 2022- FULLY EXECUTED.pdf 2. FOURTH LEASE EXTENSION _ AMENDING AGREEMENT - DECEMBER 20, 2021.pdf','No Issues',NULL,'-',NULL,NULL,NULL,66,NULL,NULL,67,NULL,69,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'01-2001',0.00,NULL,NULL,NULL,0,NULL,'2025-10-09 06:20:59','2025-10-09 13:18:03'),(256,9,73,'2025-01-07',NULL,NULL,1,'LR',NULL,'Best Buy Mobile','t0008021','102','CF Lime Ridge Mall, Store 0450, Hamilton, ON L9A 4X5, Canada.',9,'1. Fifth Lease Amending Agreement -December 8, 2022- FULLY EXECUTED.pdf 2. FOURTH LEASE EXTENSION _ AMENDING AGREEMENT - DECEMBER 20, 2021.pdf','No Issues',NULL,'-',NULL,NULL,NULL,NULL,NULL,NULL,67,NULL,69,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-10',0.00,NULL,NULL,NULL,0,NULL,'2025-10-10 05:20:51','2025-10-10 11:22:09'),(257,9,NULL,'2025-01-07',NULL,NULL,1,'LR',NULL,'Best Buy Mobile','t0008021','102','CF Lime Ridge Mall, Store 0450, Hamilton, ON L9A 4X5, Canada.',9,'1. Fifth Lease Amending Agreement -December 8, 2022- FULLY EXECUTED.pdf 2. FOURTH LEASE EXTENSION _ AMENDING AGREEMENT - DECEMBER 20, 2021.pdf','No Issues',NULL,'-',NULL,NULL,NULL,66,NULL,NULL,67,NULL,69,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-10',0.00,NULL,NULL,NULL,0,NULL,'2025-10-10 05:23:37','2025-10-10 11:22:09'),(258,4,NULL,'2025-01-07',NULL,NULL,NULL,'238',NULL,'Pita Way Cool Springs Blvd., LLC','t0000113','330-1','500 Cool Springs Boulevard, The Bulls-Eye, Space B, Franklin, TN 37067, United States',2,'1. 1.1.3.11.1.13.2 Cincinnati Lease - Kenwood S2. 1.1.3.11.1.13.3 230501 Second Amendment to S',NULL,NULL,NULL,NULL,NULL,NULL,47,NULL,NULL,50,NULL,53,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'10-2025',0.00,NULL,NULL,'15',0,NULL,'2025-10-13 05:11:51','2025-10-13 11:35:14'),(259,4,NULL,'2025-01-07',NULL,NULL,NULL,'238',NULL,'Route 65, LLC','t0000114','330-3','500 Cool Springs Boulevard, Franklin, TN 37067, United States',4,'1. 1.1.3.11.1.13.2 Cincinnati Lease - Kenwood S2. 1.1.3.11.1.13.3 230501 Second Amendment to S',NULL,NULL,NULL,NULL,NULL,NULL,47,NULL,NULL,50,NULL,53,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'09-2025',0.00,NULL,NULL,'16',0,NULL,'2025-10-13 05:11:51','2025-10-13 11:35:14'),(260,4,NULL,'2025-01-07',NULL,NULL,NULL,'238',NULL,'Select Comfort Retail Corporation dba Sleep Number','t0000112','330-5','500 Cool Springs Boulevard, Franklin, TN 37067, United States',4,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,47,NULL,NULL,50,NULL,53,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'04-2025',0.00,NULL,NULL,'17',0,NULL,'2025-10-13 05:11:51','2025-10-13 11:35:14'),(261,4,NULL,'2025-01-07',NULL,NULL,NULL,'247',NULL,'La Michoacana Paleteria & Bakery Springfield, LLC','t0000145','330-6','Oaks Village, North Thomson Lane, Murfreesboro, TN, United States',5,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,48,NULL,NULL,51,NULL,55,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'10-2025',0.00,NULL,NULL,'18',0,NULL,'2025-10-13 05:11:51','2025-10-13 11:35:14'),(262,4,NULL,'2025-01-07',NULL,NULL,NULL,'247',NULL,'MGC Southeast Inc.','t0000146','330-7','North Thomson Lane, Murfreesboro, TN, United States',5,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,48,NULL,NULL,51,NULL,55,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'10-2025',0.00,NULL,NULL,'19',0,NULL,'2025-10-13 05:11:51','2025-10-13 11:35:14'),(263,4,NULL,'2025-01-07',NULL,NULL,NULL,'247',NULL,'Greenvue Title & Escrow, LLC','t0000151','350-0','North Thomson Lane, Murfreesboro, TN, United States',4,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,48,NULL,NULL,51,NULL,55,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'20',0,NULL,'2025-10-13 05:11:51','2025-10-13 11:35:14'),(264,4,NULL,'2025-01-07',NULL,NULL,NULL,'247',NULL,'SRLA Murfreesboro','t0000149','101','North Thomson Lane, Murfreesboro, TN, United States',2,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,49,NULL,NULL,52,NULL,74,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'21',0,NULL,'2025-10-13 05:11:52','2025-10-13 11:35:14'),(265,4,NULL,'2025-01-07',NULL,NULL,NULL,'247',NULL,'YC Partneship LLC','t0000152','102','North Thomson Lane, Murfreesboro, TN, United States',6,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,49,NULL,NULL,52,NULL,74,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'22',0,NULL,'2025-10-13 05:11:52','2025-10-13 11:35:14'),(266,4,NULL,'2025-01-07',NULL,NULL,NULL,'284',NULL,'Taw Sports Cool Springs, LLC','t0000186','103','420 Cool Springs Blvd, Franklin, TN 37067, United States',7,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,49,NULL,NULL,52,NULL,74,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'23',0,NULL,'2025-10-13 05:11:52','2025-10-13 11:35:14'),(267,4,NULL,'2025-01-07',NULL,NULL,NULL,'284',NULL,'RPMTN, LLC dba InMotion Wellness Studio','t0000181','104','420 Cool Springs Blvd, Suite 110, Franklin, TN 37067, United States',4,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,47,NULL,NULL,50,NULL,53,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'24',0,NULL,'2025-10-13 05:11:52','2025-10-13 11:35:14'),(268,4,NULL,'2025-01-07',NULL,NULL,NULL,'284',NULL,'Salon Blonde Franklin, LLC','t0000178','105','420 Cool Springs Boulevard, Franklin, TN 37067, Unied States.',6,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,47,NULL,NULL,50,NULL,53,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'25',0,NULL,'2025-10-13 05:11:52','2025-10-13 11:35:14'),(269,4,NULL,'2025-01-07',NULL,NULL,NULL,'284',NULL,'Subway Real Estate, LLC','t0000179','106','420 Cool Springs Blvd. Suite 105, Franklin, TN 37067, United STates.',5,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,47,NULL,NULL,50,NULL,53,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'26',0,NULL,'2025-10-13 05:11:52','2025-10-13 11:35:14'),(270,4,NULL,'2025-01-07',NULL,NULL,NULL,'284',NULL,'Brown Bag, LLC','t0000187','MORRI','420 Cool Springs Blvd, Suite 135, Franklin, Tennessee 37067, United States',3,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,48,NULL,NULL,51,NULL,55,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,NULL,0,NULL,'2025-10-13 05:11:52','2025-10-13 11:35:14'),(271,4,NULL,'2025-01-07',NULL,NULL,NULL,'284',NULL,'Business for Good, LLC','t0000185','1260-0','420 Cool Springs Blvd., Suite 125, Franklin, Williamson County, Tennessee 37067, United States',6,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,48,NULL,NULL,51,NULL,55,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,NULL,0,NULL,'2025-10-13 05:11:52','2025-10-13 11:35:14'),(272,4,NULL,'2025-01-07',NULL,NULL,NULL,'284',NULL,'JoyRay, Inc','t0000184','1260-1','The Shoppes at Thoroughbred Square 1- CC (284), Franklin, Tennessee, United States',2,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,48,NULL,NULL,51,NULL,55,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,NULL,0,NULL,'2025-10-13 05:11:52','2025-10-13 11:35:14'),(273,4,NULL,'2025-01-07',NULL,NULL,2,'247',NULL,'451 N Thompson Ln, Murfreesboro, TN, 37129, United States.','t0000148','1260-2','451 N Thompson Ln, Murfreesboro, TN, 37129, United States.',3,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,49,NULL,NULL,52,NULL,74,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,NULL,0,NULL,'2025-10-13 05:11:52','2025-10-13 11:35:14'),(274,4,NULL,'2025-01-07',NULL,NULL,NULL,'LR',NULL,'Chatime','t0008014','1260-3, 1260-5','CF Lime Ridge Mall, Store No 0277C, Hamilton, Ontario, Canada',2,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,49,NULL,NULL,52,NULL,74,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,NULL,0,NULL,'2025-10-13 05:11:52','2025-10-13 11:35:14'),(275,4,NULL,'2025-01-07',NULL,NULL,NULL,'LR',NULL,'Fast Time Watch & Jewellery Repair','t0008015','2222','CF Lime Ridge Mall, Store No. Z201, Hamilton, ON L9A 4X5, Canada',2,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,49,NULL,NULL,52,NULL,74,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,NULL,0,NULL,'2025-10-13 05:11:52','2025-10-13 11:35:14'),(276,4,NULL,'2025-01-07',NULL,NULL,NULL,'LR',NULL,'Jump+','t0008026','2224','Unit 0261C, Lime Ridge Mall, Hamilton, Ontario',4,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,49,NULL,NULL,52,NULL,74,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,NULL,0,NULL,'2025-10-13 05:11:52','2025-10-13 11:35:14'),(277,4,NULL,'2025-01-07',NULL,NULL,NULL,'LR',NULL,'Bikini Village','t0008022','101','CF Lime Ridge Mall, Store 0160C, Hamilton, ON L9A 4X5, Canada.',1,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,49,NULL,NULL,52,NULL,74,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,NULL,0,NULL,'2025-10-13 05:11:52','2025-10-13 11:35:14'),(318,13,NULL,'2025-01-07',NULL,NULL,NULL,'238',NULL,'Pita Way Cool Springs Blvd., LLC','t0000113','330-1','500 Cool Springs Boulevard, The Bulls-Eye, Space B, Franklin, TN 37067, United States',2,'1. 1.1.3.11.1.13.2 Cincinnati Lease - Kenwood S2. 1.1.3.11.1.13.3 230501 Second Amendment to S',NULL,NULL,NULL,NULL,NULL,NULL,47,NULL,NULL,50,NULL,53,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'15',0,NULL,'2025-10-13 13:15:40','2025-10-13 13:15:42'),(319,13,NULL,'2025-01-07',NULL,NULL,NULL,'238',NULL,'Route 65, LLC','t0000114','330-3','500 Cool Springs Boulevard, Franklin, TN 37067, United States',4,'1. 1.1.3.11.1.13.2 Cincinnati Lease - Kenwood S2. 1.1.3.11.1.13.3 230501 Second Amendment to S',NULL,NULL,NULL,NULL,NULL,NULL,47,NULL,NULL,50,NULL,53,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'16',0,NULL,'2025-10-13 13:15:40','2025-10-13 13:15:42'),(320,13,NULL,'2025-01-07',NULL,NULL,NULL,'238',NULL,'Select Comfort Retail Corporation dba Sleep Number','t0000112','330-5','500 Cool Springs Boulevard, Franklin, TN 37067, United States',4,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,47,NULL,NULL,50,NULL,53,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'17',0,NULL,'2025-10-13 13:15:40','2025-10-13 13:15:42'),(321,13,NULL,'2025-01-07',NULL,NULL,NULL,'247',NULL,'La Michoacana Paleteria & Bakery Springfield, LLC','t0000145','330-6','Oaks Village, North Thomson Lane, Murfreesboro, TN, United States',5,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,48,NULL,NULL,51,NULL,55,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'18',0,NULL,'2025-10-13 13:15:40','2025-10-13 13:15:42'),(322,13,NULL,'2025-01-07',NULL,NULL,NULL,'247',NULL,'MGC Southeast Inc.','t0000146','330-7','North Thomson Lane, Murfreesboro, TN, United States',5,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,48,NULL,NULL,51,NULL,55,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'19',0,NULL,'2025-10-13 13:15:40','2025-10-13 13:15:42'),(323,13,NULL,'2025-01-07',NULL,NULL,NULL,'247',NULL,'Greenvue Title & Escrow, LLC','t0000151','350-0','North Thomson Lane, Murfreesboro, TN, United States',4,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,48,NULL,NULL,51,NULL,55,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'20',0,NULL,'2025-10-13 13:15:40','2025-10-13 13:15:42'),(324,13,NULL,'2025-01-07',NULL,NULL,NULL,'247',NULL,'SRLA Murfreesboro','t0000149','101','North Thomson Lane, Murfreesboro, TN, United States',2,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,49,NULL,NULL,52,NULL,74,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'21',0,NULL,'2025-10-13 13:15:40','2025-10-13 13:15:42'),(325,13,NULL,'2025-01-07',NULL,NULL,NULL,'247',NULL,'YC Partneship LLC','t0000152','102','North Thomson Lane, Murfreesboro, TN, United States',6,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,49,NULL,NULL,52,NULL,74,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'22',0,NULL,'2025-10-13 13:15:40','2025-10-13 13:15:42'),(326,13,NULL,'2025-01-07',NULL,NULL,NULL,'284',NULL,'Taw Sports Cool Springs, LLC','t0000186','103','420 Cool Springs Blvd, Franklin, TN 37067, United States',7,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,49,NULL,NULL,52,NULL,74,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'23',0,NULL,'2025-10-13 13:15:40','2025-10-13 13:15:42'),(327,13,NULL,'2025-01-07',NULL,NULL,NULL,'284',NULL,'RPMTN, LLC dba InMotion Wellness Studio','t0000181','104','420 Cool Springs Blvd, Suite 110, Franklin, TN 37067, United States',4,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,47,NULL,NULL,50,NULL,53,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'24',0,NULL,'2025-10-13 13:15:40','2025-10-13 13:15:42'),(328,13,NULL,'2025-01-07',NULL,NULL,NULL,'284',NULL,'Salon Blonde Franklin, LLC','t0000178','105','420 Cool Springs Boulevard, Franklin, TN 37067, Unied States.',6,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,47,NULL,NULL,50,NULL,53,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'25',0,NULL,'2025-10-13 13:15:40','2025-10-13 13:15:42'),(329,13,NULL,'2025-01-07',NULL,NULL,NULL,'284',NULL,'Subway Real Estate, LLC','t0000179','106','420 Cool Springs Blvd. Suite 105, Franklin, TN 37067, United STates.',5,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,47,NULL,NULL,50,NULL,53,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'26',0,NULL,'2025-10-13 13:15:40','2025-10-13 13:15:42'),(330,13,NULL,'2025-01-07',NULL,NULL,NULL,'284',NULL,'Brown Bag, LLC','t0000187','MORRI','420 Cool Springs Blvd, Suite 135, Franklin, Tennessee 37067, United States',3,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,48,NULL,NULL,51,NULL,55,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,NULL,0,NULL,'2025-10-13 13:15:40','2025-10-13 13:15:42'),(331,13,NULL,'2025-01-07',NULL,NULL,NULL,'284',NULL,'Business for Good, LLC','t0000185','1260-0','420 Cool Springs Blvd., Suite 125, Franklin, Williamson County, Tennessee 37067, United States',6,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,48,NULL,NULL,51,NULL,55,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,NULL,0,NULL,'2025-10-13 13:15:40','2025-10-13 13:15:42'),(332,13,NULL,'2025-01-07',NULL,NULL,NULL,'284',NULL,'JoyRay, Inc','t0000184','1260-1','The Shoppes at Thoroughbred Square 1- CC (284), Franklin, Tennessee, United States',2,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,48,NULL,NULL,51,NULL,55,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,NULL,0,NULL,'2025-10-13 13:15:40','2025-10-13 13:15:42'),(333,13,NULL,'2025-01-07',NULL,NULL,2,'247',NULL,'451 N Thompson Ln, Murfreesboro, TN, 37129, United States.','t0000148','1260-2','451 N Thompson Ln, Murfreesboro, TN, 37129, United States.',3,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,49,NULL,NULL,52,NULL,74,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,NULL,0,NULL,'2025-10-13 13:15:40','2025-10-13 13:15:42'),(334,13,NULL,'2025-01-07',NULL,NULL,NULL,'LR',NULL,'Chatime','t0008014','1260-3, 1260-5','CF Lime Ridge Mall, Store No 0277C, Hamilton, Ontario, Canada',2,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,49,NULL,NULL,52,NULL,74,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,NULL,0,NULL,'2025-10-13 13:15:40','2025-10-13 13:15:42'),(335,13,NULL,'2025-01-07',NULL,NULL,NULL,'LR',NULL,'Fast Time Watch & Jewellery Repair','t0008015','2222','CF Lime Ridge Mall, Store No. Z201, Hamilton, ON L9A 4X5, Canada',2,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,49,NULL,NULL,52,NULL,74,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,NULL,0,NULL,'2025-10-13 13:15:40','2025-10-13 13:15:42'),(336,13,NULL,'2025-01-07',NULL,NULL,NULL,'LR',NULL,'Jump+','t0008026','2224','Unit 0261C, Lime Ridge Mall, Hamilton, Ontario',4,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,49,NULL,NULL,52,NULL,74,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,NULL,0,NULL,'2025-10-13 13:15:40','2025-10-13 13:15:42'),(337,13,NULL,'2025-01-07',NULL,NULL,NULL,'LR',NULL,'Bikini Village','t0008022','101','CF Lime Ridge Mall, Store 0160C, Hamilton, ON L9A 4X5, Canada.',1,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,49,NULL,NULL,52,NULL,74,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,NULL,0,NULL,'2025-10-13 13:15:40','2025-10-13 13:15:42'),(338,12,79,'2025-01-07',NULL,NULL,NULL,'238',NULL,'Pita Way Cool Springs Blvd., LLC','t0000113','330-1','500 Cool Springs Boulevard, The Bulls-Eye, Space B, Franklin, TN 37067, United States',2,'1. 1.1.3.11.1.13.2 Cincinnati Lease - Kenwood S2. 1.1.3.11.1.13.3 230501 Second Amendment to S',NULL,NULL,NULL,NULL,NULL,NULL,47,NULL,NULL,50,NULL,53,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'09-2025',0.00,NULL,NULL,'15',0,NULL,'2025-10-13 13:17:39','2025-10-14 04:53:21'),(339,12,79,'2025-01-07',NULL,NULL,NULL,'238',NULL,'Route 65, LLC','t0000114','330-3','500 Cool Springs Boulevard, Franklin, TN 37067, United States',4,'1. 1.1.3.11.1.13.2 Cincinnati Lease - Kenwood S2. 1.1.3.11.1.13.3 230501 Second Amendment to S',NULL,NULL,NULL,NULL,NULL,NULL,47,NULL,NULL,50,NULL,53,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'10-2025',0.00,NULL,NULL,'16',0,NULL,'2025-10-13 13:17:39','2025-10-14 04:53:21'),(340,12,NULL,'2025-01-07',NULL,NULL,NULL,'238',NULL,'Select Comfort Retail Corporation dba Sleep Number','t0000112','330-5','500 Cool Springs Boulevard, Franklin, TN 37067, United States',4,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,47,NULL,NULL,50,NULL,53,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'09-2025',0.00,NULL,NULL,'17',0,NULL,'2025-10-13 13:17:39','2025-10-14 04:53:21'),(341,12,NULL,'2025-01-07',NULL,NULL,NULL,'247',NULL,'La Michoacana Paleteria & Bakery Springfield, LLC','t0000145','330-6','Oaks Village, North Thomson Lane, Murfreesboro, TN, United States',5,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,48,NULL,NULL,51,NULL,55,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'10-2025',0.00,NULL,NULL,'18',0,NULL,'2025-10-13 13:17:39','2025-10-14 04:53:21'),(342,12,NULL,'2025-01-07',NULL,NULL,NULL,'247',NULL,'MGC Southeast Inc.','t0000146','330-7','North Thomson Lane, Murfreesboro, TN, United States',5,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,48,NULL,NULL,51,NULL,55,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'09-2025',0.00,NULL,NULL,'19',0,NULL,'2025-10-13 13:17:39','2025-10-14 04:53:21'),(343,12,NULL,'2025-01-07',NULL,NULL,NULL,'247',NULL,'Greenvue Title & Escrow, LLC','t0000151','350-0','North Thomson Lane, Murfreesboro, TN, United States',4,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,48,NULL,NULL,51,NULL,55,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'08-2025',0.00,NULL,NULL,'20',0,NULL,'2025-10-13 13:17:39','2025-10-14 04:53:21'),(344,12,NULL,'2025-01-07',NULL,NULL,NULL,'247',NULL,'SRLA Murfreesboro','t0000149','101','North Thomson Lane, Murfreesboro, TN, United States',2,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,49,NULL,NULL,52,NULL,74,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'21',0,NULL,'2025-10-13 13:17:39','2025-10-14 04:53:21'),(345,12,NULL,'2025-01-07',NULL,NULL,NULL,'247',NULL,'YC Partneship LLC','t0000152','102','North Thomson Lane, Murfreesboro, TN, United States',6,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,49,NULL,NULL,52,NULL,74,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'22',0,NULL,'2025-10-13 13:17:39','2025-10-14 04:53:21'),(346,12,NULL,'2025-01-07',NULL,NULL,NULL,'284',NULL,'Taw Sports Cool Springs, LLC','t0000186','103','420 Cool Springs Blvd, Franklin, TN 37067, United States',7,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,49,NULL,NULL,52,NULL,74,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'23',0,NULL,'2025-10-13 13:17:39','2025-10-14 04:53:21'),(347,12,NULL,'2025-01-07',NULL,NULL,NULL,'284',NULL,'RPMTN, LLC dba InMotion Wellness Studio','t0000181','104','420 Cool Springs Blvd, Suite 110, Franklin, TN 37067, United States',4,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,47,NULL,NULL,50,NULL,53,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'24',0,NULL,'2025-10-13 13:17:39','2025-10-14 04:53:21'),(348,12,NULL,'2025-01-07',NULL,NULL,NULL,'284',NULL,'Salon Blonde Franklin, LLC','t0000178','105','420 Cool Springs Boulevard, Franklin, TN 37067, Unied States.',6,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,47,NULL,NULL,50,NULL,53,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'25',0,NULL,'2025-10-13 13:17:39','2025-10-14 04:53:21'),(349,12,NULL,'2025-01-07',NULL,NULL,NULL,'284',NULL,'Subway Real Estate, LLC','t0000179','106','420 Cool Springs Blvd. Suite 105, Franklin, TN 37067, United STates.',5,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,47,NULL,NULL,50,NULL,53,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,'26',0,NULL,'2025-10-13 13:17:39','2025-10-14 04:53:21'),(350,12,NULL,'2025-01-07',NULL,NULL,NULL,'284',NULL,'Brown Bag, LLC','t0000187','MORRI','420 Cool Springs Blvd, Suite 135, Franklin, Tennessee 37067, United States',3,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,48,NULL,NULL,51,NULL,55,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,NULL,0,NULL,'2025-10-13 13:17:39','2025-10-14 04:53:21'),(351,12,NULL,'2025-01-07',NULL,NULL,NULL,'284',NULL,'Business for Good, LLC','t0000185','1260-0','420 Cool Springs Blvd., Suite 125, Franklin, Williamson County, Tennessee 37067, United States',6,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,48,NULL,NULL,51,NULL,55,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,NULL,0,NULL,'2025-10-13 13:17:39','2025-10-14 04:53:21'),(352,12,NULL,'2025-01-07',NULL,NULL,NULL,'284',NULL,'JoyRay, Inc','t0000184','1260-1','The Shoppes at Thoroughbred Square 1- CC (284), Franklin, Tennessee, United States',2,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,48,NULL,NULL,51,NULL,55,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,NULL,0,NULL,'2025-10-13 13:17:39','2025-10-14 04:53:21'),(353,12,NULL,'2025-01-07',NULL,NULL,2,'247',NULL,'451 N Thompson Ln, Murfreesboro, TN, 37129, United States.','t0000148','1260-2','451 N Thompson Ln, Murfreesboro, TN, 37129, United States.',3,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,49,NULL,NULL,52,NULL,74,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,NULL,0,NULL,'2025-10-13 13:17:39','2025-10-14 04:53:21'),(354,12,NULL,'2025-01-07',NULL,NULL,NULL,'LR',NULL,'Chatime','t0008014','1260-3, 1260-5','CF Lime Ridge Mall, Store No 0277C, Hamilton, Ontario, Canada',2,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,49,NULL,NULL,52,NULL,74,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,NULL,0,NULL,'2025-10-13 13:17:39','2025-10-14 04:53:21'),(355,12,NULL,'2025-01-07',NULL,NULL,NULL,'LR',NULL,'Fast Time Watch & Jewellery Repair','t0008015','2222','CF Lime Ridge Mall, Store No. Z201, Hamilton, ON L9A 4X5, Canada',2,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,49,NULL,NULL,52,NULL,74,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,NULL,0,NULL,'2025-10-13 13:17:39','2025-10-14 04:53:21'),(356,12,NULL,'2025-01-07',NULL,NULL,NULL,'LR',NULL,'Jump+','t0008026','2224','Unit 0261C, Lime Ridge Mall, Hamilton, Ontario',4,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,49,NULL,NULL,52,NULL,74,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,NULL,0,NULL,'2025-10-13 13:17:39','2025-10-14 04:53:21'),(357,12,NULL,'2025-01-07',NULL,NULL,NULL,'LR',NULL,'Bikini Village','t0008022','101','CF Lime Ridge Mall, Store 0160C, Hamilton, ON L9A 4X5, Canada.',1,'1. COMMENCEMENT AGREEMENT - Pita Way.pdf2. LEASE AGREEMENT - Pita Way.pdf',NULL,NULL,NULL,NULL,NULL,NULL,49,NULL,NULL,52,NULL,74,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL,NULL,0,NULL,'2025-10-13 13:17:39','2025-10-14 04:53:21');
/*!40000 ALTER TABLE `project_intakes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_member_assignments`
--

DROP TABLE IF EXISTS `project_member_assignments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `project_member_assignments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `startdate` date DEFAULT NULL,
  `enddate` date DEFAULT NULL,
  `project_id` bigint unsigned NOT NULL,
  `pm_id` bigint unsigned NOT NULL,
  `member_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_proj_pm_member` (`project_id`,`pm_id`,`member_id`),
  KEY `project_member_assignments_pm_id_foreign` (`pm_id`),
  KEY `project_member_assignments_member_id_foreign` (`member_id`),
  CONSTRAINT `project_member_assignments_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `project_member_assignments_pm_id_foreign` FOREIGN KEY (`pm_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `project_member_assignments_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_member_assignments`
--

LOCK TABLES `project_member_assignments` WRITE;
/*!40000 ALTER TABLE `project_member_assignments` DISABLE KEYS */;
/*!40000 ALTER TABLE `project_member_assignments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_priorities`
--

DROP TABLE IF EXISTS `project_priorities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `project_priorities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_priorities_created_by_foreign` (`created_by`),
  KEY `project_priorities_updated_by_foreign` (`updated_by`),
  CONSTRAINT `project_priorities_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `project_priorities_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_priorities`
--

LOCK TABLES `project_priorities` WRITE;
/*!40000 ALTER TABLE `project_priorities` DISABLE KEYS */;
INSERT INTO `project_priorities` VALUES (1,'Low',1,NULL,NULL,'2025-08-25 03:58:57','2025-08-25 03:58:57'),(2,'Moderate',1,NULL,NULL,'2025-08-25 03:58:57','2025-08-25 03:58:57'),(3,'High',1,NULL,NULL,'2025-08-25 03:58:57','2025-08-25 03:58:57'),(4,'Medium',1,11,11,'2025-10-10 04:04:23','2025-10-10 04:04:23');
/*!40000 ALTER TABLE `project_priorities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_statuses`
--

DROP TABLE IF EXISTS `project_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `project_statuses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_statuses_created_by_foreign` (`created_by`),
  KEY `project_statuses_updated_by_foreign` (`updated_by`),
  CONSTRAINT `project_statuses_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `project_statuses_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_statuses`
--

LOCK TABLES `project_statuses` WRITE;
/*!40000 ALTER TABLE `project_statuses` DISABLE KEYS */;
INSERT INTO `project_statuses` VALUES (1,'Draft',1,NULL,NULL,'2025-08-25 03:58:57','2025-08-25 03:58:57'),(2,'Active',1,NULL,NULL,'2025-08-25 03:58:57','2025-08-25 03:58:57'),(3,'On Hold',1,NULL,NULL,'2025-08-25 03:58:57','2025-08-25 03:58:57'),(4,'Completed',1,NULL,NULL,'2025-08-25 03:58:57','2025-08-25 03:58:57'),(5,'Cancelled',1,NULL,NULL,'2025-08-25 03:58:57','2025-08-25 03:58:57');
/*!40000 ALTER TABLE `project_statuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_types`
--

DROP TABLE IF EXISTS `project_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `project_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_types_created_by_foreign` (`created_by`),
  KEY `project_types_updated_by_foreign` (`updated_by`),
  CONSTRAINT `project_types_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `project_types_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_types`
--

LOCK TABLES `project_types` WRITE;
/*!40000 ALTER TABLE `project_types` DISABLE KEYS */;
INSERT INTO `project_types` VALUES (1,'Annotation',1,NULL,11,'2025-08-25 03:58:57','2025-10-13 12:44:34'),(2,'Lease Abstraction Services',1,NULL,11,'2025-08-25 03:58:57','2025-10-13 12:44:29'),(3,'Lease Administration',1,NULL,11,'2025-08-25 03:58:57','2025-10-13 12:44:05');
/*!40000 ALTER TABLE `project_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_user`
--

DROP TABLE IF EXISTS `project_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `project_user` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `project_user_project_id_user_id_unique` (`project_id`,`user_id`),
  KEY `project_user_user_id_foreign` (`user_id`),
  CONSTRAINT `project_user_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `project_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_user`
--

LOCK TABLES `project_user` WRITE;
/*!40000 ALTER TABLE `project_user` DISABLE KEYS */;
INSERT INTO `project_user` VALUES (2,1,34,'2025-10-08 07:30:18','2025-10-08 07:30:18'),(3,2,46,'2025-10-08 09:48:22','2025-10-08 09:48:22'),(4,3,46,'2025-10-08 09:54:17','2025-10-08 09:54:17'),(7,6,46,'2025-10-09 05:23:46','2025-10-09 05:23:46'),(8,7,64,'2025-10-09 06:15:31','2025-10-09 06:15:31'),(9,8,64,'2025-10-09 12:43:25','2025-10-09 12:43:25'),(10,9,64,'2025-10-10 05:11:44','2025-10-10 05:11:44'),(11,10,5,'2025-10-10 10:59:27','2025-10-10 10:59:27'),(13,4,46,'2025-10-13 07:16:26','2025-10-13 07:16:26'),(14,11,13,'2025-10-13 11:07:46','2025-10-13 11:07:46'),(15,12,46,'2025-10-13 12:45:20','2025-10-13 12:45:20'),(16,13,46,'2025-10-13 13:15:25','2025-10-13 13:15:25');
/*!40000 ALTER TABLE `project_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `projects` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint unsigned DEFAULT NULL,
  `project_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_recurring` tinyint(1) NOT NULL DEFAULT '0',
  `recurring_type` enum('weekly','monthly','yearly') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `customer_id` bigint unsigned NOT NULL,
  `project_type_id` bigint unsigned NOT NULL,
  `department_id` bigint unsigned NOT NULL,
  `pricing_id` bigint unsigned NOT NULL,
  `input_format_id` bigint unsigned NOT NULL,
  `output_format_id` bigint unsigned NOT NULL,
  `mode_of_delivery_id` bigint unsigned NOT NULL,
  `frequency_of_delivery_id` bigint unsigned NOT NULL,
  `project_priority_id` bigint unsigned NOT NULL,
  `project_status_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `project_category` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '1 = General Projects, 2 = LA Projects',
  PRIMARY KEY (`id`),
  KEY `projects_customer_id_foreign` (`customer_id`),
  KEY `projects_project_type_id_foreign` (`project_type_id`),
  KEY `projects_department_id_foreign` (`department_id`),
  KEY `projects_pricing_id_foreign` (`pricing_id`),
  KEY `projects_input_format_id_foreign` (`input_format_id`),
  KEY `projects_output_format_id_foreign` (`output_format_id`),
  KEY `projects_mode_of_delivery_id_foreign` (`mode_of_delivery_id`),
  KEY `projects_frequency_of_delivery_id_foreign` (`frequency_of_delivery_id`),
  KEY `projects_project_priority_id_foreign` (`project_priority_id`),
  KEY `projects_project_status_id_foreign` (`project_status_id`),
  KEY `projects_parent_id_foreign` (`parent_id`),
  CONSTRAINT `projects_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `companies` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `projects_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `projects_frequency_of_delivery_id_foreign` FOREIGN KEY (`frequency_of_delivery_id`) REFERENCES `project_delivery_frequencies` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `projects_input_format_id_foreign` FOREIGN KEY (`input_format_id`) REFERENCES `input_output_formats` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `projects_mode_of_delivery_id_foreign` FOREIGN KEY (`mode_of_delivery_id`) REFERENCES `mode_of_deliveries` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `projects_output_format_id_foreign` FOREIGN KEY (`output_format_id`) REFERENCES `input_output_formats` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `projects_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  CONSTRAINT `projects_pricing_id_foreign` FOREIGN KEY (`pricing_id`) REFERENCES `pricing_masters` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `projects_project_priority_id_foreign` FOREIGN KEY (`project_priority_id`) REFERENCES `project_priorities` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `projects_project_status_id_foreign` FOREIGN KEY (`project_status_id`) REFERENCES `project_statuses` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `projects_project_type_id_foreign` FOREIGN KEY (`project_type_id`) REFERENCES `project_types` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projects`
--

LOCK TABLES `projects` WRITE;
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
INSERT INTO `projects` VALUES (1,NULL,'Lease Abstraction','ABC',0,NULL,'2025-10-08','2025-10-31',7,2,1,9,1,1,1,2,2,2,'2025-10-08 07:23:32','2025-10-13 11:56:10','2025-10-13 11:56:10',2),(2,NULL,'Project GHII','Stellar LA project',0,NULL,'2025-10-08','2025-10-31',9,2,1,9,1,1,1,2,2,2,'2025-10-08 09:48:22','2025-10-08 09:55:11','2025-10-08 09:55:11',2),(3,NULL,'Project GHI','New Project',0,NULL,'2025-10-08','2025-10-20',9,3,1,9,1,1,1,2,2,2,'2025-10-08 09:54:17','2025-10-08 13:25:55','2025-10-08 13:25:55',2),(4,NULL,'Project GHI','New Project',0,NULL,'2025-10-09','2025-10-31',9,2,1,9,1,1,1,2,2,2,'2025-10-09 04:10:51','2025-10-13 11:56:06','2025-10-13 11:56:06',2),(5,NULL,'Project MNO','Test Project MNO',0,NULL,'2025-10-09','2025-10-25',10,2,1,9,1,1,1,2,2,2,'2025-10-09 04:39:23','2025-10-09 09:49:12','2025-10-09 09:49:12',2),(6,4,'Alpha','Test Alpha',0,NULL,'2025-10-10','2025-10-25',11,2,1,12,1,1,1,2,2,2,'2025-10-09 05:23:46','2025-10-13 07:14:36',NULL,2),(7,NULL,'Project XYZ','Test Project MNO',0,'weekly','2025-10-09','2025-10-16',12,2,1,9,1,1,1,2,2,2,'2025-10-09 06:15:31','2025-10-13 11:56:03','2025-10-13 11:56:03',2),(8,7,'XYZ sub','sodkjfd',0,NULL,'2025-10-09','2025-10-31',12,1,1,12,2,3,1,2,2,2,'2025-10-09 12:43:25','2025-10-09 12:43:25',NULL,2),(9,NULL,'2ndproject','abc',0,NULL,'2025-10-10','2025-10-24',12,2,1,12,2,1,1,2,2,2,'2025-10-10 05:11:44','2025-10-13 11:55:59','2025-10-13 11:55:59',2),(10,NULL,'Bahmas Test','Test',0,NULL,'2025-10-10','2025-10-24',10,1,1,9,2,2,1,2,2,2,'2025-10-10 10:59:27','2025-10-13 11:55:55','2025-10-13 11:55:55',3),(11,NULL,'Faropoint','Three different services',0,NULL,'2025-10-13','2025-10-25',2,2,1,12,2,1,1,2,2,2,'2025-10-13 11:07:46','2025-10-13 11:07:46',NULL,2),(12,NULL,'Project GHI New','Test',0,NULL,'2025-10-01','2025-10-31',9,3,1,9,1,1,1,2,2,2,'2025-10-13 12:45:20','2025-10-13 12:50:53',NULL,2),(13,12,'Sub1','test',0,NULL,'2025-10-01','2025-10-08',9,2,1,15,1,1,1,2,3,2,'2025-10-13 13:15:25','2025-10-13 13:15:25',NULL,2);
/*!40000 ALTER TABLE `projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `query_statuses`
--

DROP TABLE IF EXISTS `query_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `query_statuses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `query_statuses_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `query_statuses`
--

LOCK TABLES `query_statuses` WRITE;
/*!40000 ALTER TABLE `query_statuses` DISABLE KEYS */;
INSERT INTO `query_statuses` VALUES (1,'Open','2025-09-10 05:34:47','2025-09-10 05:34:47'),(2,'Closed','2025-09-10 05:34:47','2025-09-10 05:34:47');
/*!40000 ALTER TABLE `query_statuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT INTO `role_has_permissions` VALUES (1,1),(2,1),(3,1),(4,1),(5,1),(6,1),(7,1),(8,1),(9,1),(10,1),(11,1),(12,1),(13,1),(14,1),(15,1),(16,1),(17,1),(18,1),(19,1),(20,1),(21,1),(22,1),(23,1),(24,1),(25,1),(26,1),(27,1),(28,1),(29,1),(30,1),(31,1),(32,1),(33,1),(34,1),(35,1),(36,1),(37,1),(38,1),(39,1),(40,1),(41,1),(42,1),(43,1),(44,1),(45,1),(46,1),(47,1),(48,1),(49,1),(50,1),(51,1),(52,1),(53,1),(54,1),(55,1),(56,1),(57,1),(58,1),(59,1),(60,1),(61,1),(62,1),(63,1),(64,1),(65,1),(66,1),(67,1),(68,1),(69,1),(70,1),(71,1),(72,1),(73,1),(74,1),(75,1),(76,1),(77,1),(78,1),(79,1),(80,1),(81,1),(82,1),(83,1),(84,1),(85,1),(86,1),(87,1),(88,1),(89,1),(90,1),(91,1),(92,1),(93,1),(94,1),(95,1),(96,1),(97,1),(98,1),(99,1),(100,1),(101,1),(102,1),(103,1),(104,1),(105,1),(106,1),(107,1),(108,1),(109,1),(110,1),(111,1),(112,1),(113,1),(114,1),(115,1),(116,1),(117,1),(118,1),(119,1),(120,1),(123,1),(124,1),(125,1),(126,1),(127,1),(128,1),(129,1),(130,1),(131,1),(132,1),(5,2),(6,2),(7,2),(8,2),(17,2),(18,2),(19,2),(20,2),(29,2),(30,2),(31,2),(34,2),(93,2),(94,2),(95,2),(96,2),(97,2),(98,2),(99,2),(100,2),(123,2),(124,2),(125,2),(127,2),(128,2),(129,2),(130,2),(131,2),(132,2),(5,3),(6,3),(7,3),(8,3),(34,3),(95,3),(96,3),(99,3),(100,3),(128,3),(131,3),(5,4),(34,4),(95,4),(96,4),(97,4),(99,4),(100,4),(129,4),(5,5),(34,5),(95,5),(96,5),(97,5),(99,5),(100,5),(129,5),(5,6),(34,6),(95,6),(96,6),(97,6),(99,6),(100,6),(129,6),(5,7),(13,7),(18,7),(19,7),(20,7),(29,7),(30,7),(31,7),(32,7),(95,7),(98,7),(121,7),(122,7),(123,7),(124,7),(125,7),(126,7),(130,7);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'super admin','web','2025-08-25 03:58:56','2025-08-25 03:58:56'),(2,'project manager','web','2025-08-25 04:14:17','2025-08-25 04:14:17'),(3,'customer','web','2025-08-25 04:14:58','2025-08-25 04:14:58'),(4,'abstractor','web','2025-08-26 01:22:35','2025-08-26 01:22:35'),(5,'reviewer','web','2025-09-03 01:22:11','2025-09-03 01:22:11'),(6,'sense check','web','2025-09-09 04:10:22','2025-09-09 04:10:22'),(7,'finance team','web','2025-10-06 06:02:28','2025-10-06 06:02:28');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service_offerings`
--

DROP TABLE IF EXISTS `service_offerings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `service_offerings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `service_offerings_created_by_foreign` (`created_by`),
  KEY `service_offerings_updated_by_foreign` (`updated_by`),
  CONSTRAINT `service_offerings_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `service_offerings_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_offerings`
--

LOCK TABLES `service_offerings` WRITE;
/*!40000 ALTER TABLE `service_offerings` DISABLE KEYS */;
INSERT INTO `service_offerings` VALUES (1,'Abstraction',0,NULL,11,'2025-08-25 03:58:58','2025-10-13 11:52:32'),(2,'Abstraction - Limited Scope',0,NULL,11,'2025-08-25 03:58:58','2025-10-13 11:52:20'),(3,'Abstraction - D&D',0,NULL,11,'2025-08-25 03:58:58','2025-10-13 11:52:04'),(7,'Full Lease Abstraction - English',1,11,11,'2025-10-13 11:59:57','2025-10-13 13:07:07'),(8,'CAM Reconciliation',1,11,11,'2025-10-13 12:00:15','2025-10-13 12:00:15'),(9,'Tax Reconcilation',1,11,11,'2025-10-13 12:00:27','2025-10-13 12:00:27'),(10,'Full Reconciliation',1,11,11,'2025-10-13 12:00:45','2025-10-13 12:00:45'),(11,'Estimates',1,11,11,'2025-10-13 12:01:01','2025-10-13 12:01:01'),(12,'Recovery set up',1,11,11,'2025-10-13 12:01:17','2025-10-13 12:01:17'),(13,'Recovery Set up Audit',1,11,11,'2025-10-13 12:01:28','2025-10-13 12:01:28'),(14,'CAM Audit',1,11,11,'2025-10-13 12:01:41','2025-10-13 12:01:41'),(15,'Posting of Charges',1,11,11,'2025-10-13 12:01:53','2025-10-13 12:01:53'),(16,'Accounting Services',1,11,11,'2025-10-13 12:02:06','2025-10-13 12:02:06'),(17,'Online Invoice / PO processing',1,11,11,'2025-10-13 12:04:08','2025-10-13 12:04:08'),(18,'BFE process',1,11,11,'2025-10-13 12:04:18','2025-10-13 12:04:18'),(19,'Media invoice processing',1,11,11,'2025-10-13 12:04:29','2025-10-13 12:04:29'),(20,'Assessment Questions',1,11,11,'2025-10-13 12:04:42','2025-10-13 12:04:42'),(21,'Assessment Names',1,11,11,'2025-10-13 12:04:56','2025-10-13 12:04:56'),(22,'Broadcast Print',1,11,11,'2025-10-13 12:05:29','2025-10-13 12:05:29'),(23,'Purchase Orders',1,11,11,'2025-10-13 12:05:41','2025-10-13 12:05:41'),(24,'Test Purchase',1,11,11,'2025-10-13 12:07:41','2025-10-13 12:07:41'),(25,'Full Lease Abstraction - Tier1',1,11,11,'2025-10-13 13:09:56','2025-10-13 13:09:56');
/*!40000 ALTER TABLE `service_offerings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('0IpUxZk5ui48pBUC0quezDfoCz1SnZo2yWa0j72A',NULL,'117.239.177.152','Mozilla/5.0 (X11; Linux x86_64; rv:116.0) Gecko/20100101 Firefox/116.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiR1Z6M1RxSTcxSEZWY1NwcmVTQWxsS3lvVjg4UjIzSVlMaHRIbUNsMSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS9hcGkvdjEvcG9kcyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1760411950),('0UyP25RmLTU9hHR0pZKuBKMwzdTJ1w6FZhT30mgx',NULL,'117.239.177.152','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoia0dhSkdZcHlpcHRKN05ocVp2b094ZGpFUEtLRDMxTm9JWG95ZzRJYyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6OTI6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS9hY2NvdW50L3N5X2FkZG1vdW50LnBocD91c2VybmFtZT0lN0NleHByJTIwODk3NzIyNzQyJTIwJTJCJTIwOTQxMDYzMzU3Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1760419180),('1T1udSFWeVHqyqkFgYv4ImIGya1wmv1uJ2p1TWIy',NULL,'66.132.153.131','Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWDhQV2hNZFg2a0djMnNnS2dQa2FxbU1SV0F0ZHZTQmtmVGI1WWFsViI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzQ6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS9zZWN1cml0eS50eHQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1760412193),('4Bpz9CpNB2ehwRPoL61zLcHe4nCPGAZAaRf5Cxdu',NULL,'117.239.177.152','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSGV2NlVSellYWXZyNmo5blF2Z0RJOFF4SmhHdHBCbVdVbjVhRXhJWSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDg6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS9hZG1pbi9ldmVudC91cGxvYWRpbWcuaHRtbCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1760420086),('5pzf4wYDBUvI8z6YJtJQ5liIpPxA2CifuebOfHsv',NULL,'117.239.177.152','Mozilla/5.0 (X11; Linux x86_64; rv:116.0) Gecko/20100101 Firefox/116.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZEhrbVNVZ2hFWEM3c0xpa0sxNFl2TUFYbEd2NlNGU2Q4YTAwdkFoZSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDI6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS91cGRhdGUucGhwP2p1bmdsZT1pZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1760422023),('6GNCIXmzBOArJukxZz2RYMRr4yFPA3x2hg6qC9Xr',NULL,'64.62.197.77','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/112.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiR0xqVlN2dGlpMEFPRDlFRU15ZGZ2NEw3czhMYmRTU2ROSVREbjlsUCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS92cG4vaW5kZXguaHRtbCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1760412005),('6ow82n2Ig9JnUj97ZMjxghjGmegOCI6X5H0N4Qwd',NULL,'117.239.177.152','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSldES2Z0NndFQW01dXNCYkhYTjU0Y1dkdXl5cFpRcHVLVHRhUFNmTCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTc4OiJodHRwczovLzEwMS41My4xMzQuODEvdjEvYXV0aC91c2Vycz9hY2Nlc3NUb2tlbj1leUpoYkdjaU9pSklVekkxTmlKOS5leUp6ZFdJaU9pSnVZV052Y3lJc0ltVjRjQ0k2T1RrNU9UazVPVGs1T1RsOS4taXNrNTZSOE5maW9IVlltcGo0b3o5Mm5VdGVOQkNOM0hSZDAtSGZrNzZnJnBhZ2VObz0xJnBhZ2VTaXplPTEwIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1760415583),('6pVnd6Lo3eQWNPARWCIQu3s8WJKpfGRQ3WmtSUzJ',NULL,'117.239.177.152','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6 Safari/605.1.15','YTozOntzOjY6Il90b2tlbiI7czo0MDoid3VuemNsWWdhcUJ6c2FFNm5kTHMxM1dNbmxha3lPek92WnhWOWN0bSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Njc6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS9saWNlbnNlLnBocD9jbWQ9ZGVsZXRlJm5hbWU9aWQlM0VjcmpqYWtlbS50eHQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1760412137),('6ThRoZy2fMtYSKFsN95rOeRXuaMD3dqMRfsroSF6',NULL,'117.239.177.152','Nacos-Server','YTozOntzOjY6Il90b2tlbiI7czo0MDoic2kzbDFjNFlnRFllWDBzTEJtNXVqWm1VVVpQQXIwRlIzQURVeThKcCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Nzg6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS9uYWNvcy92MS9jcy9vcHMvZGVyYnk/c3FsPXNlbGVjdCUyMCUyQSUyMGZyb20lMjB1c2VycyUyMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1760416142),('71c7b2uoMXt7fdG1GSm8gKF6XcTL0z6Biv8UlDcP',NULL,'117.239.177.152','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTFhvVXBPa2hvUmxNc1pUR3k2ZWpUblBoa0xCRjllajUxNUs1RlV3ZSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjA2OiJodHRwczovLzEwMS41My4xMzQuODEvaW5kZXgucGhwL2tleXdvcmQ/YmFja3VybD1pZCZrZXl3b3JkPSU3RCU3QnBib290JTNBaWYlMjglMjhnZXRfbGclMkYlMkFzdWFudmUtJTJBJTJGJTI4JTI5JTI5JTJGJTJBJTJBJTJGJTI4Z2V0X2JhY2t1cmwlMkYlMkFzdWFudmUtJTJBJTJGJTI4JTI5JTI5JTI5JTdEMTIzMzIxc3VhbnZlJTdCJTJGcGJvb3QlM0FpZiU3RCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1760419850),('7Mu8ZVnA80xpgfYYBH0rNB2Ydgsjo4f56bQ6tWrx',NULL,'213.209.157.162','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.4.1 Safari/605.1.15','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTDZONThuOVpCeU9PeU5WQWk5ZEMwOXpjUGcwWWE1UHl3a3Bpa3R4MyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS8uZ2l0L2luZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1760421567),('7UFDz5VD2dL7d4XBlTIAWqV849ypu9C32DyPdh7U',NULL,'117.239.177.152','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6 Safari/605.1.15','YTozOntzOjY6Il90b2tlbiI7czo0MDoidDkxcGxnSGlZTDlaMHRNU3EwVEVvME5DZ2hOTmlZQ3lyTnRSUEFTcCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NzA6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS90b29sL2xvZy9jLnBocD9ob3N0PXJ4emlrZ3F5Z2smc3RyaXBfc2xhc2hlcz1tZDUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1760422872),('8WzYzdeJhufBDH28JqB9f30cEaFxpV91rwVttVql',NULL,'117.239.177.152','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoibWZUMkZ0S3BrZHBnajZHQnNVMmxvTFJLbTVrQXQzeGY2YkFiRE1mciI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6OTc6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS8/cz1tYW5hZ2UlMkYlNUN0aGluayU1Q21vZHVsZSUyRmFjdGlvbiUyRnBhcmFtMSUyRiUyNCU3QiU0MHBocGluZm8lMjglMjklN0QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1760427902),('abPQSE2axDh7nolDapjNgWn5nJl6REccssvEBaIA',NULL,'64.62.197.77','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/112.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZE9aeGJ5bEJKQ1JhTjZHeTN1TWw5UlI1aXJDbXlNd05ZY2tDaUE2TyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vMTAxLjUzLjEzNC44MSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1760410714),('AZgkz08ES9EWUjOAxJXVdKBnhYLLRLEctjxzCuC5',NULL,'93.123.109.214','l9tcpid/v1.1.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoidXBrZHVYcnl5QWtWbThMWUFtR1pzbmJGQlhTWDFMbUhFWjQxOXNkNiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vMTAxLjUzLjEzNC44MSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1760422471),('b5mCYDCFL51ZB4DamXt5h3NfIT1Oe4p3XqiUYWd2',NULL,'167.94.138.116','Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiazNPNE9xdWFqZ1BZWnZMTDl1b0hsY0JsS0VqZlRNTjg3TUk4MGpTUCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1760412437),('biykYiiU1BT5R484cHh6Uje9jLDslE9xGOQ9OEV4',NULL,'117.239.177.152','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ1pRM012blU1dElwOWxNaWIwQmJ4N2hPeFZ1b0ZLSW5weER5bVNnUiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTY0OiJodHRwczovLzEwMS41My4xMzQuODEvbmFjb3MvdjEvY3Mvb3BzL2RlcmJ5P2FjY2Vzc1Rva2VuPWV5SmhiR2NpT2lKSVV6STFOaUo5LmV5SnpkV0lpT2lKdVlXTnZjeUlzSW1WNGNDSTZNVFk1T0RnNU5EY3lOMzAuZmVldEttV29Qbk1rQWViamtObnl1S282YzIxX2h6VGd1MGRmTnFiZHBaUSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1760416144),('Bw9986zBGdKYv7EZDz7K77gu1xMtJCMOP4eTZDIQ',NULL,'66.132.153.131','Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)','YTozOntzOjY6Il90b2tlbiI7czo0MDoibDNDcnppa3REVzNERWk4eThVMVZSazd4RklqTXpzZWlRbENzbVhZUiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1760412161),('Cb8ked1SD2Tk9b56KNop4ebs7hwmCrMxbQEnhCcW',NULL,'117.239.177.152','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiM1ZlMzVNNVBlN0VhT0tTOHNGZlBKWDFPTFpxUnJCcElqUktqVFlsRCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6OTA6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS9uYWNvcy92MS9hdXRoL3VzZXJzP2FjY2Vzc1Rva2VuPSZwYWdlTm89MSZwYWdlU2l6ZT05JnNlYXJjaD1hY2N1cmF0ZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1760415871),('CLbRHkdSHbAU81a0Mv1j0lykj0YinjO4AqYgKZaP',NULL,'117.239.177.152','Nacos-Server','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZkVEYTdmaEhaWERzcWM3c2FEMWlUOHFDWDlWRlREU0ZpcUZ6OGhzSCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDM6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS9uYWNvcy92MS9jcy9vcHMvZGVyYnkiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1760416142),('Cpxvur2odmEyZ7eVxJrjG5ACQzQ7M4L6SQJJLpvV',NULL,'117.239.177.152','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSnhwaEFaaFJxZVFaeklzaUpha1NBalJwaVBnNm90ZjllQlJzZjNaSSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTYzOiJodHRwczovLzEwMS41My4xMzQuODEvdmlldy9zeXN0ZW1Db25maWcvbWFuYWdlbWVudC9ubWNfc3luYy5waHA/Y2VudGVyX2lwPTEyNy4wLjAuMSZ0ZW1wbGF0ZV9wYXRoPSU3Q2VjaG8lMjBjZTY3ZmFlMzJkOTViMWViNTMxOTFhZTZlNGVkOWJhZSUyMCUzRSUyMHV5ZWsudHh0JTdDY2F0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1760422210),('CTPzoJPvGPxWSzt6Ndy8dhMqjJnAvUNhUg3JOPXu',NULL,'117.239.177.152','Mozilla/5.0 (X11; Linux x86_64; rv:116.0) Gecko/20100101 Firefox/116.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiY1VyWnhBN2tDTDhuM0pNVmptVXhNc0pNeFRGakhSc1F4RXZmTWd2aiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTY1OiJodHRwczovLzEwMS41My4xMzQuODEvbmFjb3MvdjEvY3Mvb3BzL2RlcmJ5P2FjY2Vzc1Rva2VuPWV5SmhiR2NpT2lKSVV6STFOaUo5LmV5SnpkV0lpT2lKdVlXTnZjeUlzSW1WNGNDSTZPVGs1T1RrNU9UazVPVGw5Li1pc2s1NlI4TmZpb0hWWW1wajRvejkyblV0ZU5CQ04zSFJkMC1IZms3NmciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1760416143),('d00vkmMhqx2EWj4ropSXDXm68IhVsCbJVAWj9H6c',NULL,'117.239.177.152','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOWhEZG1WVjhxem5JMmtnQnJST2t2dG9WdHNkZTNhVnJSUHpXUjZRaiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTk5OiJodHRwczovLzEwMS41My4xMzQuODEvbmFjb3MvdjEvY3Mvb3BzL2RlcmJ5P2FjY2Vzc1Rva2VuPWV5SmhiR2NpT2lKSVV6STFOaUo5LmV5SnpkV0lpT2lKdVlXTnZjeUlzSW1WNGNDSTZNVFk1T0RnNU5EY3lOMzAuZmVldEttV29Qbk1rQWViamtObnl1S282YzIxX2h6VGd1MGRmTnFiZHBaUSZzcWw9c2VsZWN0JTIwJTJBJTIwZnJvbSUyMHVzZXJzJTIwIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1760416144),('DCjfxi9ixZy0GM72waS71HKN6N6VOIsGbF637qjo',NULL,'117.239.177.152','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ2NEOEFJTVdkdXNXMlFZczF3YzBWU3VkUGZtWElJNkdJaHQ0YWxFNSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTM3OiJodHRwczovLzEwMS41My4xMzQuODEvP3NlYXJjaD0lMjV4eHglMjV1cmwlMjUlM0ElMjVwYXNzd29yZCUyNSU3RCU3Qi5leGVjJTdDaXBjb25maWclN0N0aW1lb3V0JTNENSU3Q291dCUzRGFiYy4lN0RSRVNVTFQlM0ElN0IuJTVFYWJjLiU3RCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1760406170),('dEvUDrsW9wlSOsDaOcIOh183i2gwTKxb7GCj5oIp',NULL,'117.239.177.152','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYklmazBLVjV4THF0aHhKY3ZrZEVaM0dxcHdTdERmRXBPTGkzRHVXSyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Njg6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS9zZWV5b24vdGhpcmRwYXJ0eUNvbnRyb2xsZXIuZG8uY3NzLy4uOy9hamF4LmRvIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1760423986),('DewGEFx4ZvlrxdHJwenGivd43y6WyvSZscHu4Ec3',NULL,'117.239.177.152','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:116.0) Gecko/20100101 Firefox/116.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYVdUUjl4c0JaWm1YT2xsUzhONmU1cU96b2k2d2hMUDlSS2Zla0FvZyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTI5OiJodHRwczovLzEwMS41My4xMzQuODEvaW5kZXgucGhwL1NlYXJjaC9pbmRleD9rZXl3b3JkPTEyMyZ1cGRhdGV4bWwlMjgxJTJDY29uY2F0JTI4MHg3ZSUyQ3VzZXIlMjglMjklMkMweDdlJTI5JTJDMSUyOSUyOSUyMz0xMjMlMjkiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1760419636),('dPfMfOeANRNKCBVgfY7uwUGoK4A8B6I0tNp7H1m5',NULL,'117.239.177.152','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoibktzaUxDSmIyRXBpZzhWVFpnUjRHRE9CbHlKNnF1VExYb1dla0JEdyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTk5OiJodHRwczovLzEwMS41My4xMzQuODEvbmFjb3MvdjEvY3Mvb3BzL2RlcmJ5P2FjY2Vzc1Rva2VuPWV5SmhiR2NpT2lKSVV6STFOaUo5LmV5SnpkV0lpT2lKdVlXTnZjeUlzSW1WNGNDSTZNVGN4TURVd05EQXhPWDAudlc4bXBCTm9KN2hWS1BOaEV0UWw0WjViMDBHNFA5S3Rybl83YzU4Y3JPayZzcWw9c2VsZWN0JTIwJTJBJTIwZnJvbSUyMHVzZXJzJTIwIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1760416143),('DtwHv9YneaYfZe5tJqJcd3l5ja2CsZXEUwR30nq9',NULL,'183.83.147.101','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','YToyOntzOjY6Il90b2tlbiI7czo0MDoiS0VoaUdRSThsWkZ1a3ZVbmFGSGdmdzhObjdpNnNUbFJxdUp3a2xSUSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1760427368),('dzw5ZMu4EdXaQYMTbucaGABoCZigKH8grZDb8xMP',NULL,'117.239.177.152','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoid2g1RFVST1VOdEJ1ZHo2YUwxa0VyWE1ta0VxekR3RFlwdWdTTmlEbCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6OTQ6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS8/cz1hcGklMkYlNUN0aGluayU1Q21vZHVsZSUyRmFjdGlvbiUyRnBhcmFtMSUyRiUyNCU3QiU0MHBocGluZm8lMjglMjklN0QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1760427902),('EBGn9l0rmIHT23U5a8ZLNFagkS9RIzjjJfGz9vh9',NULL,'117.239.177.152','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQ2VJeFJvSUZYNlhFbFlOMTUzcTBqVGFqbTNpckNpNXh0V3dtdFZ2YyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTgzOiJodHRwczovLzEwMS41My4xMzQuODEvbmFjb3MvdjEvYXV0aC91c2Vycz9hY2Nlc3NUb2tlbj1leUpoYkdjaU9pSklVekkxTmlKOS5leUp6ZFdJaU9pSnVZV052Y3lJc0ltVjRjQ0k2TVRjeE1EVXdOREF4T1gwLnZXOG1wQk5vSjdoVktQTmhFdFFsNFo1YjAwRzRQOUt0cm5fN2M1OGNyT2smcGFnZU5vPTEmcGFnZVNpemU9MTAiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1760415583),('EgThdRvQnjUypmQu5o8oU7rIvELBykaQvEvJfgNR',NULL,'117.239.177.152','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNk4yY0w1czBURnJtVFdMUDFDb2xLY0RMakpuVVJReHEyNWFYSGJnTCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NjAxOiJodHRwczovLzEwMS41My4xMzQuODEvaW5kZXgucGhwL0luZGV4P2V4dF9wcmljZSUzRDElMkYlMkElMkElMkZhbmQlMkYlMkElMkElMkZ1cGRhdGV4bWwlMjgxJTJDY29uY2F0JTI4MHg3ZSUyQyUyOFNFTEVDVCUyRiUyQSUyQSUyRmRpc3RpbmN0JTJGJTJBJTJBJTJGY29uY2F0JTI4MHgyMyUyQ3VzZXIlMjglMjklMkMweDIzJTI5JTJGJTJBJTJBJTJGRlJPTSUyRiUyQSUyQSUyRmF5X3VzZXIlMkYlMkElMkElMkZsaW1pdCUyRiUyQSUyQSUyRjAlMkMxJTI5JTJDMHg3ZSUyOSUyQzElMjklMjklMjM9MTIzJTVEJTI4aHR0cCUzQSUyRiUyRjEyNy4wLjAuMSUyRlBib290Q01TJTJGaW5kZXgucGhwJTJGSW5kZXglM0ZleHRfcHJpY2UlM0QxJTJGJTJBJTJBJTJGYW5kJTJGJTJBJTJBJTJGdXBkYXRleG1sJTI4MSUyQ2NvbmNhdCUyODB4N2UlMkMlMjhTRUxFQ1QlMkYlMkElMkElMkZkaXN0aW5jdCUyRiUyQSUyQSUyRmNvbmNhdCUyODB4MjMlMkN1c2VyJTI4JTI5JTJDMHgyMyUyOSUyRiUyQSUyQSUyRkZST00lMkYlMkElMkElMkZheV91c2VyJTJGJTJBJTJBJTJGbGltaXQlMkYlMkElMkElMkYwJTJDMSUyOSUyQzB4N2UlMjklMkMxJTI5JTI5JTIzJTNEMTIzJTI5Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1760419404),('ExDD3xw5zfeVPAyjm7PZTKpCJPZRKbNDSY5MvQXj',NULL,'40.119.24.130','Mozilla/5.0 zgrab/0.x','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTldLNXFyRG1wVlFEa3FxeHV2cjZlNk1zQWx6SG5yUjdSVXhWN1B6YyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDE6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS9vd2EvYXV0aC9sb2dvbi5hc3B4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1760412122),('fvCrE5p4XYQBqO4HTO8mRLnShKeMAxYLe99ip7gT',NULL,'117.239.177.152','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSUdUUDU2WUtRa2lzMXQ0V1FIdnhaUGJ1UWtnRmpxMkxmUjhZUzh2MCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6OTY6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS8/cz1hZG1pbiUyRiU1Q3RoaW5rJTVDbW9kdWxlJTJGYWN0aW9uJTJGcGFyYW0xJTJGJTI0JTdCJTQwcGhwaW5mbyUyOCUyOSU3RCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1760427902),('Fzwlfa7IRm6WUw9v6hs9BRrEb3YHO93PckBwCCTN',NULL,'117.239.177.152','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNmttTG5uMkZib1dXeWdySGZxOGk3YTI0cFgwUk1yQURCQkwxNFVPUyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Njk6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS9tb2R1bGUvc3lzdGVtL3FyY2FyZC9tb2JpbGV3cml0ZS9xcmNhcmRtYWluLmpzcCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1760407609),('g0id5rUfp7YY1fu9LSkWUfKrZ6ageOhESjFfdHMF',11,'14.97.224.186','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoicjdaMWVwRTdQbTUxVWZRZ3RMOFBqaTdIMG9rQkd4TktOQ0FwTTM1ZiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDA6Imh0dHBzOi8vcG10dGVzdC5zcHJpbmdib3JkLmNvbS9kYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxMTt9',1760427082),('gasmjDN8B7VazhjVbjJH8um1VNqrTYNLlDrDMDI6',NULL,'167.71.4.34','Mozilla/5.0; Keydrop.io/1.0(onlyscans.com/about);','YTozOntzOjY6Il90b2tlbiI7czo0MDoieFAwbnphNlhoQ1pMR0pWUmpxZ29wUlpuMU80T2VhQTQxMjlMQXdKQiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS8uZW52Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1760399231),('GcD0rWSMnY11S0cSpIwKZiv1iklIMT4CT7qTtlrK',NULL,'117.239.177.152','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoib0M4THhQbWNQWncxWU1XSFhZa2VHODFsSlpBYXBXbU4wejZ1WXVFcyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS9pbmRleC5waHA/bT0xIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1760427476),('glLBH1yTVcgtijqwV0qvsq9UGgN3KHRV0Aaa6YC3',NULL,'64.62.197.77','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.2.1 Safari/605.1.65','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMU81RGluTGFWV0JjallqaHZrSjRORXl4TlpiNmhIMEF5eElOdm8xdiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1760411467),('GmbNHxZRM4JRJNzsmiKUfLhJpQOGT68cfUpeEJK2',NULL,'117.239.177.152','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6 Safari/605.1.15','YTozOntzOjY6Il90b2tlbiI7czo0MDoidkxBcnJCRGtRSlZnNHdxVm1vVmFacVpXQjVXVnFKeWE3dUhWMkRHSSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjAwOiJodHRwczovLzEwMS41My4xMzQuODEvZWdyb3Vwd2FyZS9waHBnd2FwaS9qcy9mY2tlZGl0b3IvZWRpdG9yL2RpYWxvZy9mY2tfc3BlbGxlcnBhZ2VzL3NwZWxsZXJwYWdlcy9zZXJ2ZXItc2NyaXB0cy9zcGVsbGNoZWNrZXIucGhwP3NwZWxsY2hlY2tlcl9sYW5nPWVncm91cHdhcmVfc3BlbGxjaGVja2VyX2NtZF9leGVjLm5hc2wlN0MlN0NpZCU3QyU3QyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1760399294),('GOy10LYo9A8wZbT1MFePBrMg3t4NQes9If8Xlv1k',NULL,'117.239.177.152','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6 Safari/605.1.15','YTozOntzOjY6Il90b2tlbiI7czo0MDoicDB1TE42aFlyV29vTm44aVFkb0ZzVWRNaVQxd25CbzZWZ3lqcXFQeiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDQ6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS9DREdTZXJ2ZXIzL3Rlc3R5c3QudHh0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1760402732),('Grw8Suoff8luHoAorolzFE7lL6wNMkitMOgeY0C8',NULL,'117.239.177.152','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaDRZUXNOQnJmdjBmV1RaNW1NYVp6SU5lSEdwUVVHQlJjOGIyZXJhbyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTAyOiJodHRwczovLzEwMS41My4xMzQuODEvaW5kZXgucGhwP209LS0lM0UlM0MlM0YlM0QlMjQlN0IlNDBwcmludCUyOGV2YWwlMjglMjRfUE9TVCU1QjElNUQlMjklMjklN0QlM0YlM0UiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1760427477),('hAXZmdMISg2F4SQxDfgK3BIj5tDbWtuFc5wwvPKh',44,'14.97.224.186','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoieEtzdjhzQUpKMXV4dnM3M2FiS1RoUmFBN2dpWXFmZ29uRWNUTDd5RyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjQwOiJodHRwczovL3BtdHRlc3Quc3ByaW5nYm9yZC5jb20vaW52b2ljZXMvZXlKcGRpSTZJbG94YXk4cmFHcDFiMlp3YUhwc2NUaFNiekZKZW1jOVBTSXNJblpoYkhWbElqb2liMVJMVEdkSGRFOXRiRGR1YlZOMVN6QmhOa3BXVVQwOUlpd2liV0ZqSWpvaVpUbGxNVGMyT1daaE5XTmlZelptWlRFeE9HVmxZVGN5WkdRd1pqUTFZbVEwT0dVek56Z3lPVE01WkRVelpEZzFOV1pqTVdWbVpXTXhZV0V5TTJNM055SXNJblJoWnlJNklpSjkiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo0NDt9',1760427998),('He5gX35VqjNmXYl8Y6qVrDeKfQ3tNqcUjeNkdhNU',NULL,'117.239.177.152','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOU10VWJsMjJsZ0VoY1NCZ1hkb2cwT2RlRE16bGF6YnROSU5XMFFxdiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTY0OiJodHRwczovLzEwMS41My4xMzQuODEvbmFjb3MvdjEvY3Mvb3BzL2RlcmJ5P2FjY2Vzc1Rva2VuPWV5SmhiR2NpT2lKSVV6STFOaUo5LmV5SnpkV0lpT2lKdVlXTnZjeUlzSW1WNGNDSTZNVGN4TURVd05EQXhPWDAudlc4bXBCTm9KN2hWS1BOaEV0UWw0WjViMDBHNFA5S3Rybl83YzU4Y3JPayI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1760416143),('HMoOzN3lfHVZddERGok2L5BRLdLsEtfRY3BMx1UY',NULL,'117.239.177.152','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:116.0) Gecko/20100101 Firefox/116.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoibXo5TmQwM1JSVHZyellaNG8zOEduQnZ1Z2dvMjJQaHF0dG9vNmJnTyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODU6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS9pbmRleC5waHA/cz0lMkZpbmRleCUyRmluZGV4JTJGbmFtZSUyRiUyNCU3QiU0MHBocGluZm8lMjglMjklN0QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1760427268),('hS8d6FvmhT0bQO4GDc5dJOJb0Ht5z7brjUBLSJuM',NULL,'117.239.177.152','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWGdGUU1iRzFBNjRHTHByRjZ5eURHM1gxR09sbG9zUXRJMklxSU5rSiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDU6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS92MS9jb25zb2xlL3NlcnZlci9zdGF0ZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1760416653),('i0KdLVIGTM0bU4sZ5QwaQ7hKUJU3TfQJljAiaHZy',NULL,'167.94.138.116','Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ2dwRGZPYlRjbVFDaXJlR0N5TVZxWXFzSVo3b1c2RkdaQXk4NkhOeSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1760412395),('ILl1D3AdS3xaAUyx05MB7uqkGgyuyssxrmuHwg0d',NULL,'117.239.177.152','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNGRvTmFJd25CYzhKSFNsaUF4WVhvR3l2Y3BJQ2E5NjNsNngycFdVYyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6OTY6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS8/cz1pbmRleCUyRiU1Q3RoaW5rJTVDbW9kdWxlJTJGYWN0aW9uJTJGcGFyYW0xJTJGJTI0JTdCJTQwcGhwaW5mbyUyOCUyOSU3RCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1760427901),('J1TWSeYWjXg9RV3LgBSk3BW6RoGmvsY04L9Mt1tW',NULL,'64.62.197.77','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYnVIbmNzb3YwamVWRWxEWHNoejNqZklvdEtaVHJ2dGNkTFBhNDg2RiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDk6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS9sb2dvbi9Mb2dvblBvaW50L2luZGV4Lmh0bWwiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1760412053),('jjEQ7S5TJN7zR99KbmJHdU5JR1V88iQLI2d9aus9',NULL,'117.239.177.152','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:116.0) Gecko/20100101 Firefox/116.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNU1pdjR0VkEzOE1rOEFZUGl3S2ZYZTlwazU4aWJYeXFLTXRBT2NpcyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vMTAxLjUzLjEzNC44MSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1760420263),('jOJGhXnZKT2o1BflU1TtCcjhvj8Sm465PJRveJ7Q',NULL,'117.239.177.152','Mozilla/5.0 (X11; Linux x86_64; rv:116.0) Gecko/20100101 Firefox/116.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoibTE2dmhtNGluTnRMZmlpb3gyY1YwMUs0V1gxMlZ6WGhGRUxaNHdRZyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjAwOiJodHRwczovLzEwMS41My4xMzQuODEvbmFjb3MvdjEvY3Mvb3BzL2RlcmJ5P2FjY2Vzc1Rva2VuPWV5SmhiR2NpT2lKSVV6STFOaUo5LmV5SnpkV0lpT2lKdVlXTnZjeUlzSW1WNGNDSTZPVGs1T1RrNU9UazVPVGw5Li1pc2s1NlI4TmZpb0hWWW1wajRvejkyblV0ZU5CQ04zSFJkMC1IZms3Nmcmc3FsPXNlbGVjdCUyMCUyQSUyMGZyb20lMjB1c2VycyUyMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1760416142),('jQnttNKV04HEg04M5bzdGTdDo4cF0OugM94n64xe',NULL,'117.239.177.152','Mozilla/5.0 (X11; Linux x86_64; rv:116.0) Gecko/20100101 Firefox/116.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZk8yemdxOUFKY0NjZ0I4ZUpSMkE3dGZLRUVjUDZwZ0phNTUzMGwxcCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDQ6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS9HUkYvQ3VzdG9tL3ZrbHhzYy5hc3B4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1760399997),('k29QujjQK2pPbAPILDVyF9yETETuIulvjjCw7dKc',NULL,'117.239.177.152','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUm53SXQwVnhyNWV2S3JZZG4xQjZvcWV3WWM5WDY4SldFWGV4OThJMSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6OTY6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS9kb3dubG9hZF9zdGF0c19kaWNvbS5waHA/ZmlsZW5hbWU9JTJGZXRjJTJGcGFzc3dkJmZ1bGxwYXRoPSUyRmV0YyUyRnBhc3N3ZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1760403181),('k2HgUNn0HL6nkYafHpa2Gnlxvh6Naon0lKWkAzAV',NULL,'117.239.177.152','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6 Safari/605.1.15','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ3VQZDNhOFYxaEVLcWtUMU9OME1GRkthN1pUNEk0TkJJWWttdjJRdSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODY6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS9DREdTZXJ2ZXIzL3dvcmtmbG93RS91c2VyYWN0aXZhdGUvdXBkYXRlLmpzcD9mbGFnPTEmaWRzPTElMkMzJTI5Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1760420759),('k2vHsUuZe4SkltRxmaxeomKrzoYYQIHQhtepfzi5',NULL,'195.178.110.15','l9tcpid/v1.1.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVkJwZEFvZ3AzQmFSZUY5T3FoNjlyU1ZuZG9la3czYUdUQzB4OUw2VyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vMTAxLjUzLjEzNC44MSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1760426846),('KfoS65XHz7SI4eB7NEfhK7uFcxha1R2Z0BJNDhLn',NULL,'117.239.177.152','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUDB4eEhIaDE4bWlZWXloY0NTVGY5SnpuWjdMckUwQkhBdnRMVW9FZiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vMTAxLjUzLjEzNC44MSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1760407741),('KSwqDQeZQnCDSpcSypghhrUfoEKzA3jzfoxDduef',NULL,'117.239.177.152','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTzJEVzJwc1JkUTlzNDR3VmRacGlIbUhaSmk5NlVPdUl3NFZPNFFXSSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTE6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS9uYWNvcy92MS9jb25zb2xlL3NlcnZlci9zdGF0ZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1760416652),('le7IysVlYnY4UnZHmeyGwQ3OVkXlGqmhs6lOWq4i',NULL,'117.239.177.152','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOVNDcExjVWxUeWpYZ1NLa3RwenAyYmpMRlZOOEZJSDE4QmU4Tkx3aCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjA4OiJodHRwczovLzEwMS41My4xMzQuODEvP2JhY2t1cmw9aWQmbWVtYmVyJTJGbG9naW4lMkYlM0ZzdWFudmU9JTdEJTdCcGJvb3QlM0FpZiUyOCUyOGdldF9sZyUyRiUyQXN1YW52ZS0lMkElMkYlMjglMjklMjklMkYlMkElMkElMkYlMjhnZXRfYmFja3VybCUyRiUyQXN1YW52ZS0lMkElMkYlMjglMjklMjklMjklN0QxMjMzMjFzdWFudmUlN0IlMkZwYm9vdCUzQWlmJTdEIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1760419850),('lfHYVEtPvyu3zsaq5D2XuBPMuv91zw5aqGY0tYH8',NULL,'47.128.41.139','Mozilla/5.0 (Linux; Android 5.0) AppleWebKit/537.36 (KHTML, like Gecko) Mobile Safari/537.36 (compatible; Bytespider; spider-feedback@bytedance.com)','YTozOntzOjY6Il90b2tlbiI7czo0MDoid0xJU3hVNlJRRWFtaEQ1SkRSS2VHbzhObXFQTG5RWllDQ2dkOVBncCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDY6Imh0dHBzOi8vbWVyYWludm9pY2UubWFrZWluaW5kaWF0cmFkZS5jb20vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1760403685),('LvCZUzpjKWnmnnpzn5PDZ9buibOnLPlGJFDQZVaY',NULL,'117.239.177.152','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ3FpZEFLVHM1WnF3VTN3OGVxUE8xa1A4YTFjTFhHdUVXbjNOTmQ3TiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODU6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS9kY3dyaXRlci90aGlyZHBhcnQvU2VydmljZVBhZ2UuYXNweD93YXNtcmVzPS4lMkYuLiUyRndlYi5jb25maWciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1760408262),('MFLYw3SOI0dsW6vyJ7JOZPRUZzVR6VJzknI3zIof',NULL,'117.239.177.152','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6 Safari/605.1.15','YTozOntzOjY6Il90b2tlbiI7czo0MDoicUpjTzBUYjJLUFFJWVhMM2drVTJPMHRvMDBlM21IV1N3WTczWHAyayI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTA6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS9hZG1pbi9ncm91cC94X2dyb3VwLnBocD9pZD0xIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1760421392),('mKXJwNZQOOZXXPJc2U63b9ibYpy62X39Ki64ewkh',NULL,'117.239.177.152','Mozilla/5.0 (X11; Linux x86_64; rv:116.0) Gecko/20100101 Firefox/116.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoic1ljdFR0UWV6eWZlRUJsbHBHWDNOVWwwZnlBWFp3UXlVZHhuZERkbSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTA0OiJodHRwczovLzEwMS41My4xMzQuODEvaW5kZXgucGhwP209dm9kLXNlYXJjaCZ3ZD0lN0JpZi1BJTNBcHJpbnRmJTI4bWQ1JTI4OTY4NjM0MjkzJTI5JTI5JTdEJTdCZW5kaWYtQSU3RCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1760413741),('n4AeOKJjQXUK1m86vvRXVbIwygNPxDDxD7uudT6t',NULL,'117.239.177.152','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRWVONDFvak1MMVBQakhYa3RwNWkybENSMU40dTZlbGd3WWtrTmw4ZyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODg6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS9uZXdfbW92aWUucGhwP2VuZD0yJmZpbGU9MWNhdCUyMCUyRmV0YyUyRnBhc3N3ZCZzdGFydD0yJnN0dWR5VUlEPTEiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1760403420),('O3BLtQXgSuooO9DtY9YaTD0oxbtCOOglpWQ7klqP',NULL,'117.239.177.152','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiejc1TFEzV3lBWlZvVEdVbHlRM0ZHS1hvem5LVDA5ekZyUGFZWDFibiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NzA6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS9tb2R1bGUucGhwP2NtZD1kZWxldGUmbmFtZT1pZCUzRXdiYWdhaGZieGdsYi50eHQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1760412246),('olj91EM5fhWlDbl8ttDPx3hb5jWwLKjsoNST4Aez',NULL,'117.239.177.152','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSU1LUnpocnVGZUp0Q1ZNWDBCcDBNQXdOQzQ1U0E1dkJlZjNxNHdIdSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Njk6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS9tb2R1bGVzL3BpbmcvZ2VuZXJhdGUucGhwP2hvc3RuYW1lPWlkJnNlbmQ9UGluZyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1760405177),('pbH3Oqo02vjbf9yGFEdKlvsjFzo7mZSc4htFg5sI',NULL,'117.239.177.152','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoibGxaN3ZEcjVkR1oxVFNFUnprY1lHY3lsRzQxVlZTOG5mMjBqT1lPbiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTAzOiJodHRwczovLzEwMS41My4xMzQuODEvamVlY2ctYm9vdC9zeXMvdXNlci9wYXNzd29yZENoYW5nZT9wYXNzd29yZD1hZG1pbiZwaG9uZT0mc21zY29kZT0mdXNlcm5hbWU9YWRtaW4xIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1760409337),('pLJgCKrW1Fbr43EhxKhE4MLzWfb4LEQuj1Tm71n6',NULL,'167.71.4.34','Mozilla/5.0; Keydrop.io/1.0(onlyscans.com/about);','YTozOntzOjY6Il90b2tlbiI7czo0MDoic2Z2aHJxakpFdG5PUFBkZXRKZmY1cWZ6TjFGV0tQdUtvZDVHZUpETSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS8uZ2l0L2NvbmZpZyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1760399232),('pMqL2FzKrHCEjQcM9qF5Pp0AWtpnhMAuMhP8tqGG',NULL,'117.239.177.152','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRVVFSWhCcGx1QzZyQjNVVll1T24xdzJDQ3ZRckY1Zkt0eFNXTWFWRCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTc3OiJodHRwczovLzEwMS41My4xMzQuODEvdjEvYXV0aC91c2Vycz9hY2Nlc3NUb2tlbj1leUpoYkdjaU9pSklVekkxTmlKOS5leUp6ZFdJaU9pSnVZV052Y3lJc0ltVjRjQ0k2TVRjeE1EVXdOREF4T1gwLnZXOG1wQk5vSjdoVktQTmhFdFFsNFo1YjAwRzRQOUt0cm5fN2M1OGNyT2smcGFnZU5vPTEmcGFnZVNpemU9MTAiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1760415583),('pSGoEbXoTNPUSy7VPAWQbOUuEkCru4Ejrm3kjyMa',NULL,'117.239.177.152','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOTNGZW9Yc3JjeDQxNmpLcDdQOGlPeUtBd0wyYUxEbGtSWnZmVWdZZSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTQ5OiJodHRwczovLzEwMS41My4xMzQuODEvc3NsdnBuL3NzbHZwbl9jbGllbnQucGhwP2NsaWVudD1sb2dvSW1nJmltZz0lMjAlMkZ0bXAlN0NlY2hvJTIwJTYwaWQlNjAlMjAlN0N0ZWUlMjAlMkZ1c3IlMkZsb2NhbCUyRndlYnVpJTJGc3NsdnBuJTJGam56b3hqLnR4dCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1760426692),('QEeycwDzTjMUmLEPx1bNMIyad7xkamNjHWyVgLOk',NULL,'213.209.157.162','Mozilla/5.0 (CentOS; Linux i686) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWks5Y3pGSVJDMzNldnJxNTVmeGhMZWhSQUxBR2FYUURWTFdOcmV2TSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS8uZ2l0L2NvbmZpZyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1760426037),('qNVpvl850861414FfPHiQiHt9BleeoWt6NPSCTbS',NULL,'64.62.197.87','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/112.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUUkxQ3FhdHNWaHVuM1JyM0hCa2d0ZVNqU1BKRnRVanlKbUhBR3hOYiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS92cG4vaW5kZXguaHRtbCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1760412031),('QoocXKu9aeDmAekWFXF6gOtPDpeMliVNA48fDuq8',NULL,'54.78.94.152','Mozilla/5.0 (compatible; NetcraftSurveyAgent/1.0; +info@netcraft.com)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaXpDSGFlSU9YcnVrWGYyVkhVeGtwa3ViN1ZVSDRtaGw4dnc0ekNJUyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDA6Imh0dHBzOi8vbWVyYWludm9pY2UubWFrZWluaW5kaWF0cmFkZS5jb20iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1760407553),('rEgy7HxNEVRPCCCoIQw0xQTmsdSSCJeuoVaSfLQl',NULL,'117.239.177.152','Mozilla/5.0 (X11; Linux x86_64; rv:116.0) Gecko/20100101 Firefox/116.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiV1FlN0hkdTRUQ05hTWM5TXAwQ2k1dUNzamJDcHRQTGdTVnBWdHc5VSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTExOiJodHRwczovLzEwMS41My4xMzQuODEvbGliL2NsYXNzZXMvZ29vZ2xlQ2hhcnQvbWFya2Vycy9Hb29nbGVDaGFydE1hcE1hcmtlci5waHA/Z29vZ2xlODg5OTA9c3lzdGVtJTI4aXBjb25maWclMjkiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1760413347),('SdQEnejpQWX1VUEj2M4WbdtAEKWGmycT38U0VRaj',NULL,'117.239.177.152','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6 Safari/605.1.15','YTozOntzOjY6Il90b2tlbiI7czo0MDoid2lidllKcmh0YXpHZUFWdmNRQ2c0cW1RQUVNUW1ienM5Y2xSRWh2VCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDg6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS9kYXRhL21hbmFnZS9jbWQucGhwP2NtZD1pZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1760417536),('slpI1Wqu7S3ND1aTDpjnhRIkK5aJ1poRlPtcnYly',NULL,'117.239.177.152','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWFhqOTA4N0huMnlCUXcwRTM4Y3poeWlwcTExdHJkb1ZsSWVhV3lPdyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTUzOiJodHRwczovLzEwMS41My4xMzQuODEvdGVtcGxhdGVzL2F0dGVzdGF0aW9uLyUyZSUyZS8lMmUlMmUvc2VydmxldC9wZXJmb3JtYW5jZS9LaEZpZWxkVHJlZT9wb2ludHNldGlkPS0xJnN1YnN5c19pZD0xMSUyN3dhaXRmb3IlMjBkZWxheSUyMCUyNzAlM0EwJTNBMTAlMjciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1760420978),('SZ0p4CMCV2sgOnwGlLS0YSNRgYfHcs4XXrlJMQiK',NULL,'117.239.177.152','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiczBHQVdrbllQcDhLdUVZckRZNGlYSHlBbnh1Z1JuTUlSMEF5UDloMCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDc6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS9zZWV5b24vU2VleW9uVXBkYXRlMS5qc3B4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1760424322),('t3YVXSFKuC1kijxsXkCwQneVeqC0zZjyh1k0UkVm',NULL,'117.239.177.152','Mozilla/5.0 (X11; Linux x86_64; rv:116.0) Gecko/20100101 Firefox/116.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoicklxSGxqeE8wVjJIaWtzbTU1bUNGRHRMQ0ZLNXZGVFlTclhPY09RVCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS9wb2RzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1760411950),('td0cerWbzsAyzaKdaTibdBGjpoF6AtuoEcZSk9YP',NULL,'117.239.177.152','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:104.0) Gecko/20100101 Firefox/104.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVVl1UGRPTXlpck1LODVtRkJ0U0pqMFVwNkRqeEJRTEx5dUdhaU1laCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTA3OiJodHRwczovLzEwMS41My4xMzQuODEvP2E9ZmV0Y2gmY29udGVudD1kaWUlMjglNDBtZDUlMjh0aGlua2NtZiUyOSUyOSZwcmVmaXg9JTIyJnRlbXBsYXRlRmlsZT1wdWJsaWMlMkZpbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1760427041),('TqkqvNqVKVN0fyhUUKUS8GVHoUNwvt94sMqaJRNy',NULL,'167.94.138.116','Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiR3FXa00xbVcwdTdJcDZNODJvVk84ZUs5bWZvNWdkbkh2OXpjUXZHWiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vMTAxLjUzLjEzNC44MSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1760412389),('tQl8UpKvtcaZLtyQhkcpBWjvg6RhQVCrRf714bCQ',NULL,'117.239.177.152','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaE1aM3hPcjdJUEQwNkhDY3YxaE51VUlRTnE3OHJZMDFJeEJ5eHlDNiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTQyOiJodHRwczovLzEwMS41My4xMzQuODEvaW5kZXgucGhwP2lkPTElMjBhbmQlMjB1cGRhdGV4bWwlMjgxJTJDY29uY2F0JTI4MHg3ZSUyQ21kNSUyODIwNjczMTk3OSUyOSUyQzB4N2UlMjklMkMxJTI5JnM9YXBpZ29vZHMlMkZnZXRfZ29vZHNfZGV0YWlsIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1760425570),('ul1zY1PP5MWubAUuN0Mr8iObFma0Wfy1DVXWuGzv',NULL,'117.239.177.152','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6 Safari/605.1.15','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ2NTZDA3T002dlhMcURxWE12cGlwVTJyMm9QcldtODBxZHRWNXg4MSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Nzc6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS9zeXMvdWkvc3lzX3VpX2NvbXBvbmVudC9zeXNVaUNvbXBvbmVudC5kbz9tZXRob2Q9dXBsb2FkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1760412743),('uo4cLcQecrFyO8UDeV1g4mowyTN0ld7XLtug77aC',NULL,'117.239.177.152','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoid1VvYnBwTDVsRDlkemFvc2FPZ3FMSUFZT1pRNk9TMVJtRHlIdHlOeiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTE6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS9uYWNvcy92MS9jb25zb2xlL3NlcnZlci9zdGF0ZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1760416652),('UpYaZWDjSkPbxZnDFwVLT2t7f0FdR3fmL0i4Cnhx',NULL,'47.128.60.21','Mozilla/5.0 (Linux; Android 5.0) AppleWebKit/537.36 (KHTML, like Gecko) Mobile Safari/537.36 (compatible; Bytespider; spider-feedback@bytedance.com)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMFZ1bktpSlA0Z09sTTUyRUc1dWRtQmFlS0lHa2dTNDBzb2NPa3E4eiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDA6Imh0dHBzOi8vYWRtaW4ubWFrZWluaW5kaWF0cmFkZS5jb20vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1760417425),('WDyNnBaoHkdRAO9scEc0j4Dj8tSGCKqosC6yLDLf',46,'14.97.224.186','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoic2ZFVmhHMHFvZ3pveWNNVWZDTVFVTTVpR0V1aFhoS2M0MDI3Skc2dCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDg6Imh0dHBzOi8vcG10dGVzdC5zcHJpbmdib3JkLmNvbS9zdWJtaXRlZC1pbnZvaWNlcyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjQ2O30=',1760427931),('wiQ55mcCKWFuD9QRsAMusiSi5bv72J89JKpqkgiC',NULL,'117.239.177.152','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoicW9USDN1YVA0dkNvRlB0NTJjSEJkeDB4RkhqUVJvV3UwcXpUNFF5NSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTA6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS9jZ2ktYmluL3BvcGVuLmNnaT9jb21tYW5kPWlkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1760408583),('WRfpf4lrKBFtP9pfQZXtwWXPGRORmRjTYqwdausc',NULL,'117.239.177.152','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTHNadjhCWEhoYlY4bHc4dWYwQ0JTeTNZcEpOY2pTRnRRM0czdTNrSSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS9hdXRoL2dldGF1dGhrZXkiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1760418530),('WTKxCHecJUmNgYioOietLmsXf5n9Lo2Ioi4ObZaH',NULL,'117.239.177.152','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWllkTjlxdmZSbEw3YnlpRXFYR0ZDRUxwYkh5ZlVEVHBTYnZCUnNTMSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjg6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS9zY3JpcHQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1760409728),('Xle9qncGBr5p8B4tNghMiWNT2R2bEiivEu6wllhE',NULL,'117.239.177.152','Mozilla/5.0 (X11; Linux x86_64; rv:116.0) Gecko/20100101 Firefox/116.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoialZKTHFseHZnb3RpMjVscnVFbUpZd1BOaGVRbld3SU9XV3pnckVjOCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDY6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS9hcGkvcGluZz9jb3VudD01Jmhvc3Q9aWQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1760426815),('xRAMaYI4YhBsTrMrljvSnRokpNyVlEJwp7mChTJz',NULL,'66.132.153.131','Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZkNkbUR2RjRZaGM1NzIxSXVQS21oOUlKdllCZks5Tk5DRGd3NHk2VCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vMTAxLjUzLjEzNC44MSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1760412158),('xRhGhtPiCwq73IwhTAjV6Ede0W3wCkYgyoSjTerQ',NULL,'64.62.197.77','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMVF2MVhVek5TUGJ1MVVhczFqc3BmM2x0SHVubEJiTUJvUWZFZXU2SCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS8uZ2l0L2NvbmZpZyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1760412459),('XzJsCQkoK9yrbvZ23XjRCQTEFtUVOknVxMalOYHv',NULL,'117.239.177.152','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6 Safari/605.1.15','YTozOntzOjY6Il90b2tlbiI7czo0MDoiU0NTa1FpMDBBeXhOSUE0czZyTmcwWm15bW9WcFhwRG9HQm9UbGU5MCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDY6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS9zb2xyL2FkbWluL2NvcmVzP3d0PWpzb24iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1760425953),('yEKbPPwRoTAKNY7vezaQr6pLhoyA8Bj4eSX8Ke3s',NULL,'195.178.110.15','l9tcpid/v1.1.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZE9tNTgxUDNqaUR1SnZ4M09YTWJZcDl4cnk1Q0hub2M0Y2lxZ2pCSCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vMTAxLjUzLjEzNC44MSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1760426209),('yFKFXf2xZm8G9CGrwcSxJfFScu1oMYuL3Ol5g5MK',NULL,'117.239.177.152','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTUNIR2dpaFlraDhHQmZwWFdFSU1leUxDUVJOb1pFWXdUc1RyQXV4aSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTg0OiJodHRwczovLzEwMS41My4xMzQuODEvbmFjb3MvdjEvYXV0aC91c2Vycz9hY2Nlc3NUb2tlbj1leUpoYkdjaU9pSklVekkxTmlKOS5leUp6ZFdJaU9pSnVZV052Y3lJc0ltVjRjQ0k2T1RrNU9UazVPVGs1T1RsOS4taXNrNTZSOE5maW9IVlltcGo0b3o5Mm5VdGVOQkNOM0hSZDAtSGZrNzZnJnBhZ2VObz0xJnBhZ2VTaXplPTEwIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1760415583),('YmwcOUtiI4KP36eO3px0tCVQNxVhOZZeCHUfV5cd',NULL,'117.239.177.152','Mozilla/5.0 (X11; Linux x86_64; rv:116.0) Gecko/20100101 Firefox/116.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaXFIQkxreFBxaWtPQnRKZmtJcFVkUm9ObFV1VG51UzFzSFdDd01IcSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDc6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS9sYWJlbC9tZW1iZXIvZ2V0aW5mby5hc3B4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1760403891),('YoiNu2EviUnf0wWWWWHAOEodMBu8BemZ2gVWWPgM',NULL,'117.239.177.152','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoidHZ0VWhFa09KYWI4cWlGcHpCSEhhZVRXR29TMmJjeFVtVWw0SnpBYSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTk6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS9lYXNwb3J0YWwvYnVmZmFsby8uLi9iaG5hcGprYWh4aW4uanNwIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1760411528),('Z32dKB0YhJxkgyvmnPupIHlZeDzsMO14Lpg67xiz',11,'49.43.248.127','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiR04xelZwZ2JOMDZ3dkYwVVB0S2l1YTNKMDUxeVp3STh6MzBjaVc2ZyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQwOiJodHRwczovL3BtdHRlc3Quc3ByaW5nYm9yZC5jb20vZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTE7fQ==',1760425130),('Z9YcUOZHff6mxxwGmoc9AVJWsegP9JALI8GwQaRm',NULL,'117.239.177.152','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoieU1kQkJsTjNpdHdaM1RTY2prSkRtd1FjTFlid2Fub1FuUWpaQm45NyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS9pbmRleC5waHAiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1760420443),('zCfJRyOPnCBhSgcmiCsmdROKqKWPmLqIbAtZKCoN',NULL,'117.239.177.152','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRHY2YlNscG82TlpvblAyd0lzVnNSOWJVS2RZUkZVbEhScVhwTFQ3QSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDU6Imh0dHBzOi8vMTAxLjUzLjEzNC44MS92MS9jb25zb2xlL3NlcnZlci9zdGF0ZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1760416653),('zOpUHyHDARqBcrnAbWCdXmwMYarCza3qEYw8zV95',NULL,'117.239.177.152','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOHZMUjJ3U25LaDBqNWJMSWVPR2xqOHRiMzVuRHdoY1FoSzdtMFNyVSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTI4OiJodHRwczovLzEwMS41My4xMzQuODEvQWpheC9MaWNNYW5hZ2UuYXNoeD9CaWxsRklEPTEmTW91bGRJRD0xJTI3JTIwd2FpdGZvciUyMGRlbGF5JTIwJTI3MCUzQTAlM0E2JTI3LS0lMjAmYWN0aW9uPUV4dGVuc2lvblBlcm1pdCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1760404928);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `skill_masters`
--

DROP TABLE IF EXISTS `skill_masters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `skill_masters` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `skill_expertise_level` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ctc` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '1=Active, 0=Inactive',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `skill_masters_created_by_foreign` (`created_by`),
  KEY `skill_masters_updated_by_foreign` (`updated_by`),
  CONSTRAINT `skill_masters_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `skill_masters_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `skill_masters`
--

LOCK TABLES `skill_masters` WRITE;
/*!40000 ALTER TABLE `skill_masters` DISABLE KEYS */;
INSERT INTO `skill_masters` VALUES (1,'Python Developer','Expert',20000.00,1,1,1,'2025-08-26 01:55:16','2025-08-26 01:55:16'),(2,'Java Developer','Beginner',10000.00,1,1,1,'2025-08-26 01:55:33','2025-08-26 01:55:33'),(3,'Python Developer Expert','Expert',50000.00,1,1,1,'2025-08-26 04:38:10','2025-08-26 04:38:10'),(4,'Java Developer Expert','Expert',25000.00,1,1,1,'2025-08-26 04:38:38','2025-08-26 04:38:38'),(5,'PHP developer','Intermediate',100.00,1,11,11,'2025-10-09 13:34:59','2025-10-09 13:34:59');
/*!40000 ALTER TABLE `skill_masters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sub_tasks`
--

DROP TABLE IF EXISTS `sub_tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sub_tasks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `main_task_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `task_type` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '1 = Production, 2 = Non-Production',
  `benchmarked_time` time DEFAULT NULL,
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '1 = Active, 0 = Inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sub_tasks_main_task_id_foreign` (`main_task_id`),
  CONSTRAINT `sub_tasks_main_task_id_foreign` FOREIGN KEY (`main_task_id`) REFERENCES `main_tasks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sub_tasks`
--

LOCK TABLES `sub_tasks` WRITE;
/*!40000 ALTER TABLE `sub_tasks` DISABLE KEYS */;
INSERT INTO `sub_tasks` VALUES (1,1,'Email Checking',1,NULL,1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(2,1,'Email Response',1,'00:05:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(3,2,'Invoice Processing',1,'00:01:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(4,2,'Vendor Creation',1,'00:01:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(5,2,'Vendor COI Update',1,'00:01:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(6,2,'Vendor COI followup',1,'00:01:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(7,2,'Reviewing Invoice',2,'00:01:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(8,2,'Vendor Follow-up Request',1,'00:01:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(9,2,'Credit Card Transaction Upload',1,'00:01:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(10,2,'Job Creation',1,'00:01:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(11,2,'Budget Setup',1,'00:01:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(12,2,'Contract & Change Orders',1,'00:01:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(13,2,'Budget Revisions',1,'00:01:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(14,2,'Amex - Procore update',1,'00:01:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(15,2,'Labor Uploads - Procore',1,'00:01:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(16,2,'Mortgage statements Process',1,'00:01:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(17,2,'Payment Update in Procore',1,'00:01:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(18,2,'Procore Validation',1,'00:01:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(19,2,'Posted Payables in Procore',1,'00:01:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(20,3,'Receipts Processing',1,'00:05:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(21,3,'Prepay Application',1,'00:05:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(22,3,'Move-out Accounting',1,'00:05:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(23,3,'Security Deposit Refund Request',1,'00:05:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(24,3,'Charges Creation',1,'00:05:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(25,3,'Tenant Billbacks',1,'00:05:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(26,3,'AR Aging Review',1,'00:05:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(27,3,'Commercial Billing',1,'00:05:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(28,3,'Statements send to PM\'s review',1,'00:05:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(29,3,'Statements send to tenants',1,'00:05:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(30,3,'Receipts Reviewing',2,'00:01:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(31,3,'Banks Transactions',1,'00:05:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(32,3,'NSF/ Reversal / Adjustments Entry Posting',1,'00:05:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(33,3,'Tenant COI Update',1,'00:05:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(34,3,'Contacts Update',1,'00:05:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(35,3,'CAM Impound Setup',1,'00:05:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(36,4,'Previous day reports download',1,'00:05:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(37,4,'Monthly Statements Download',1,'00:05:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(38,4,'Daily Reconciliation',1,'00:05:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(39,4,'Monthly Reconciliation',1,'00:05:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(40,4,'BRS Report',1,'00:05:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(41,4,'Cash flow activity',1,'00:05:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(42,5,'Journal Entry Preparation',1,'00:05:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(43,5,'Journal Entry Posting',1,'00:05:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(44,5,'GL Account Reconciliation',2,'00:05:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(45,6,'Accruals Preparation',1,'00:05:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(46,6,'Balance Sheet Reconciliation',1,'00:05:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(47,6,'GL Review Notes',1,'00:05:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(48,6,'Budget Comparison Notes',1,'00:05:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(49,6,'Prepaid/Deferred Schedules',1,'00:05:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(50,6,'Management Fee Calculation',1,'00:05:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(51,6,'Monthly Reports',1,'00:05:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(52,7,'Financial Reports Creation',1,'00:05:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(53,7,'Quarterly Reports',1,'00:05:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(54,7,'Financial package Review',2,'00:05:00',1,'2025-09-18 07:52:12','2025-09-18 07:52:12'),(55,8,'Lender Drawings',1,'00:05:00',1,'2025-09-18 07:52:13','2025-09-18 07:52:13'),(56,8,'Special Projects',1,'00:05:00',1,'2025-09-18 07:52:13','2025-09-18 07:52:13'),(57,9,'Tea Break',2,'00:15:00',1,'2025-09-18 07:52:13','2025-09-18 07:52:13'),(58,9,'Bio-Break',2,'00:15:00',1,'2025-09-18 07:52:13','2025-09-18 07:52:13'),(59,9,'Lunch Break',2,'00:30:00',1,'2025-09-18 07:52:13','2025-09-18 07:52:13'),(60,10,'Meeting - Internal',2,'00:30:00',1,'2025-09-18 07:52:13','2025-09-18 07:52:13'),(61,10,'Client Meeting',1,'00:30:00',1,'2025-09-18 07:52:13','2025-09-18 07:52:13'),(62,11,'Process Training',2,'00:30:00',1,'2025-09-18 07:52:13','2025-09-18 07:52:13'),(63,11,'Doubt Clarification',2,'00:30:00',1,'2025-09-18 07:52:13','2025-09-18 07:52:13'),(64,11,'System Issue',2,'00:30:00',1,'2025-09-18 07:52:13','2025-09-18 07:52:13'),(65,12,'PMT',1,'00:01:00',1,'2025-10-10 04:18:40','2025-10-10 04:18:40');
/*!40000 ALTER TABLE `sub_tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `task_items`
--

DROP TABLE IF EXISTS `task_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `task_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `main_task_id` bigint unsigned NOT NULL,
  `sub_task_id` bigint unsigned NOT NULL,
  `status` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '0=pending,1=in_progress,2=paused,3=completed,4=cancelled',
  `total_seconds` bigint unsigned NOT NULL DEFAULT '0' COMMENT 'Denormalized sum of work_sessions for quick reads',
  `started_at` datetime(6) DEFAULT NULL,
  `completed_at` datetime(6) DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `task_items_sub_task_id_foreign` (`sub_task_id`),
  KEY `task_items_project_id_user_id_index` (`project_id`,`user_id`),
  KEY `task_items_user_id_status_index` (`user_id`,`status`),
  KEY `task_items_project_id_status_index` (`project_id`,`status`),
  KEY `task_items_main_task_id_sub_task_id_index` (`main_task_id`,`sub_task_id`),
  CONSTRAINT `task_items_main_task_id_foreign` FOREIGN KEY (`main_task_id`) REFERENCES `main_tasks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `task_items_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `task_items_sub_task_id_foreign` FOREIGN KEY (`sub_task_id`) REFERENCES `sub_tasks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `task_items_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `task_items`
--

LOCK TABLES `task_items` WRITE;
/*!40000 ALTER TABLE `task_items` DISABLE KEYS */;
INSERT INTO `task_items` VALUES (1,1,41,9,59,3,7,'2025-10-08 07:59:02.000000','2025-10-08 07:59:09.000000',NULL,'2025-10-08 07:59:02','2025-10-08 07:59:09'),(2,1,41,9,57,3,6,'2025-10-08 07:59:25.000000','2025-10-08 07:59:31.000000',NULL,'2025-10-08 07:59:25','2025-10-08 07:59:31'),(3,1,38,9,58,3,3,'2025-10-08 12:07:44.000000','2025-10-08 12:07:47.000000',NULL,'2025-10-08 12:07:44','2025-10-08 12:07:47'),(4,4,47,9,59,3,7,'2025-10-09 04:50:30.000000','2025-10-09 04:50:37.000000',NULL,'2025-10-09 04:50:30','2025-10-09 04:50:37'),(5,4,47,9,57,3,6,'2025-10-09 04:51:03.000000','2025-10-09 04:51:09.000000',NULL,'2025-10-09 04:51:03','2025-10-09 04:51:09'),(6,7,65,9,58,3,8,'2025-10-09 06:29:40.000000','2025-10-09 06:29:48.000000',NULL,'2025-10-09 06:29:40','2025-10-09 06:29:48'),(7,7,65,9,59,3,7,'2025-10-09 06:29:57.000000','2025-10-09 06:30:04.000000',NULL,'2025-10-09 06:29:57','2025-10-09 06:30:04'),(8,7,65,2,13,3,10,'2025-10-09 06:30:35.000000','2025-10-09 06:31:12.000000',NULL,'2025-10-09 06:30:35','2025-10-09 06:31:12'),(9,7,65,9,57,3,8,'2025-10-09 06:30:49.000000','2025-10-09 06:30:57.000000',NULL,'2025-10-09 06:30:49','2025-10-09 06:30:57'),(10,7,65,9,57,3,13,'2025-10-09 09:20:59.000000','2025-10-09 09:21:12.000000',NULL,'2025-10-09 09:20:59','2025-10-09 09:21:12'),(11,7,65,4,41,3,24,'2025-10-09 09:21:23.000000','2025-10-09 09:22:11.000000',NULL,'2025-10-09 09:21:23','2025-10-09 09:22:11'),(12,7,65,9,57,3,10,'2025-10-09 09:21:49.000000','2025-10-09 09:21:59.000000',NULL,'2025-10-09 09:21:49','2025-10-09 09:21:59'),(13,7,65,3,35,3,16,'2025-10-09 09:55:07.000000','2025-10-09 09:55:45.000000',NULL,'2025-10-09 09:55:07','2025-10-09 09:55:45'),(14,7,65,9,58,3,7,'2025-10-09 09:55:30.000000','2025-10-09 09:55:37.000000',NULL,'2025-10-09 09:55:30','2025-10-09 09:55:37'),(15,7,68,3,35,3,7,'2025-10-09 12:09:52.000000','2025-10-09 12:11:18.000000',NULL,'2025-10-09 12:09:52','2025-10-09 12:11:18'),(16,7,68,9,58,3,4,'2025-10-09 12:10:03.000000','2025-10-09 12:10:07.000000',NULL,'2025-10-09 12:10:03','2025-10-09 12:10:07'),(17,7,68,1,2,3,13,'2025-10-09 12:10:20.000000','2025-10-09 12:11:08.000000',NULL,'2025-10-09 12:10:20','2025-10-09 12:11:08'),(18,7,66,2,11,3,34,'2025-10-10 05:00:18.000000','2025-10-10 05:00:52.000000',NULL,'2025-10-10 05:00:18','2025-10-10 05:00:52'),(19,4,46,9,59,3,5,'2025-10-13 04:38:40.000000','2025-10-13 04:38:45.000000',NULL,'2025-10-13 04:38:40','2025-10-13 04:38:45');
/*!40000 ALTER TABLE `task_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unit_of_measurements`
--

DROP TABLE IF EXISTS `unit_of_measurements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `unit_of_measurements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `unit_of_measurements_created_by_foreign` (`created_by`),
  KEY `unit_of_measurements_updated_by_foreign` (`updated_by`),
  CONSTRAINT `unit_of_measurements_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `unit_of_measurements_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unit_of_measurements`
--

LOCK TABLES `unit_of_measurements` WRITE;
/*!40000 ALTER TABLE `unit_of_measurements` DISABLE KEYS */;
INSERT INTO `unit_of_measurements` VALUES (2,'Unit Based',0,1,11,'2025-08-25 03:59:04','2025-10-13 12:20:14'),(9,'LTR',0,11,11,'2025-10-13 11:58:46','2025-10-13 12:19:53'),(10,'Unit-Based',1,11,11,'2025-10-13 12:20:38','2025-10-13 12:20:38'),(11,'FTE',1,11,11,'2025-10-13 12:20:59','2025-10-13 12:20:59'),(12,'Fixed',1,11,11,'2025-10-13 12:21:21','2025-10-13 12:21:21'),(13,'Hourly based',1,11,11,'2025-10-13 12:21:57','2025-10-13 12:21:57');
/*!40000 ALTER TABLE `unit_of_measurements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned DEFAULT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_manager` bigint unsigned DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_password_update` tinyint(1) NOT NULL DEFAULT '0',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '0 = FALSE, 1 = TRUE',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_created_by_foreign` (`created_by`),
  KEY `users_updated_by_foreign` (`updated_by`),
  KEY `users_company_id_index` (`company_id`),
  CONSTRAINT `users_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,NULL,'Admin','Briskstar',NULL,'admin@briskstar.com','1234567890','Briskstar',NULL,'$2y$12$F/XIirOZRF7ih0Ym1xhvHuICARYfdMyX/xKRT4qU4uizAeOJLk..O',1,NULL,1,NULL,NULL,NULL,'2025-08-25 03:58:56','2025-08-28 05:37:30'),(2,1,'Keyur','Padaria',NULL,'keyur.padaria@briskstar.com','9409266722',NULL,NULL,'$2y$12$siMQq6j4TqOrxFJaZqaWwe6Txz4lJGxqBYEcHtPdJ9BXOWxE9EMLC',1,NULL,1,NULL,NULL,NULL,'2025-08-25 04:15:48','2025-09-10 08:20:16'),(3,1,'Jayesh','Oad',NULL,'jayesh.oad@gmail.com','9404848411',NULL,NULL,'$2y$12$F/XIirOZRF7ih0Ym1xhvHuICARYfdMyX/xKRT4qU4uizAeOJLk..O',0,NULL,1,NULL,NULL,NULL,'2025-08-25 04:15:49','2025-08-25 04:15:49'),(5,NULL,'Harish','SpringBoard',NULL,'harish@springbord.com','1250212354','SpringBoard',NULL,'$2y$12$Z..IMvq4gkycIuBC05xynOQUVISMotbHtgpZDQQpRrj7UQDTxyWa2',0,NULL,1,NULL,NULL,NULL,'2025-08-26 01:17:37','2025-08-26 01:17:37'),(6,NULL,'Jagadish','SpringBoard',5,'jagdish@springboard.com','9409266722','Briskstar',NULL,'$2y$12$WJdyRgpfENeMNww0XVL8Q.wzcOXCifBpX9Kd7zCwWBXa2yz9Sf6jS',1,NULL,1,NULL,NULL,NULL,'2025-08-26 01:31:23','2025-09-22 04:35:18'),(7,NULL,'Jayesh','Briskstar',4,'jayesh.oad@briskstar.com','12510511210',NULL,NULL,'$2y$12$Z5SUKEci7ZkYmoTp/rs/v.1fhXowhUof3/poElcEHzt/LxKxDEAtK',0,NULL,1,NULL,NULL,NULL,'2025-08-26 07:13:41','2025-08-26 07:13:41'),(8,NULL,'Gambhier','Makwana',9,'gambhir.makwana@briskstar.com','9754540551','Briskstar',NULL,'$2y$12$ljga4Z/4iCSRGwCulbXIe.0a4ReoOsvu6vTuYxE6bl6QCzZHMttp2',1,NULL,1,NULL,NULL,NULL,'2025-08-28 03:57:19','2025-09-08 04:38:39'),(9,NULL,'Bijal','Soni',NULL,'bijal@briskstar.com','9402122020','Briskstar',NULL,'$2y$12$wa03PCuIEOhXl2e3x7TwPeaAE15x9VBTO3dZmB.M/v2O5qF82Wsku',1,NULL,1,NULL,NULL,NULL,'2025-09-05 04:13:27','2025-09-11 00:39:32'),(10,NULL,'Jayesh','Oad',9,'jayesh.oad12@briskstar.com','980545045','Briskstar',NULL,'$2y$12$W6yc5Z0ZInZmcJGKYk9TR.uHeJZWwMyMKXEDcczvQWxNWvw1gfHfG',1,NULL,1,NULL,NULL,NULL,'2025-09-10 06:30:04','2025-09-10 06:52:14'),(11,NULL,'Spring','Bord',NULL,'admin@springbord.com','1234567890','SpringBoard',NULL,'$2y$12$9nJmkUOtYvzU2uYLAdyEyuTo8SvoK1e8.lrcNOei7MJbQar0Pk24y',1,NULL,1,NULL,NULL,NULL,'2025-09-11 00:26:35','2025-09-11 00:27:52'),(12,2,'Jeya','Kumari',NULL,'nijhanthanraj.gj@springbord.com','789578905',NULL,NULL,'$2y$12$OXST8SmDW1S8uU76DoPAhuMddH2MvXZkBR8dImg9bokbNZ5A9n3dO',0,NULL,1,NULL,NULL,NULL,'2025-09-29 09:11:18','2025-09-29 09:11:18'),(13,NULL,'Jeya','kumari',5,'jaya@springbord.com','8959483890',NULL,NULL,'$2y$12$ctq4qjUmMoAWWZMBOERvf.BrInsIQPWEzAdZ2WZIB2B0M7qB7NStW',1,NULL,1,NULL,NULL,NULL,'2025-09-29 09:22:04','2025-09-30 04:07:59'),(14,NULL,'Jehgadesh','kumar',13,'jega@springbord.com','8959483890',NULL,NULL,'$2y$12$jIXI4r77aYIV/RpTrQ6PqOh27drJ2MmXBVEF.AKy.tXtRN/.aLWF2',0,NULL,1,NULL,NULL,NULL,'2025-09-29 09:23:02','2025-09-29 09:23:02'),(15,NULL,'Nijhanth','G J',13,'abc@gmail.com','8959483890',NULL,NULL,'$2y$12$KQ/t9z1ubd5jjiZCedPof.ORg/4b7EYuyuwBj1uCRkwLX8kD3HAJC',0,NULL,1,NULL,NULL,NULL,'2025-09-29 11:52:15','2025-09-29 11:52:15'),(16,NULL,'JKumar','K',13,'jaga@gmail.com','839358488',NULL,NULL,'$2y$12$XGvXyCrBAyZ.FCKwNoZsnu3m5BrcKBoX8Xr.8T0iyyAoJ9jNQWXb2',1,NULL,1,NULL,NULL,NULL,'2025-09-29 11:54:47','2025-10-01 06:44:06'),(17,NULL,'Nithya','Kumar',13,'xyz@gmail.com','38389348',NULL,NULL,'$2y$12$TY0Rs6AQfyO8cUfHit4NOOjLGvN60inpCP0eiyP.U.0P9VksMpIUq',1,NULL,1,NULL,NULL,NULL,'2025-09-29 12:00:52','2025-09-29 12:05:09'),(18,NULL,'Test','Test',13,'test@springbord.com','2345698725',NULL,NULL,'$2y$12$6ToF.J.Z4J0pOa8HWk.X2eRseQdUJSd2aANJs0VT5p7iopR4ywwUO',1,NULL,1,NULL,NULL,NULL,'2025-09-29 12:31:40','2025-09-29 12:33:40'),(19,NULL,'Vignesh','Kumar',NULL,'vig@gmail.com','848398409',NULL,NULL,'$2y$12$ueTto.H45H.f9eMLa9rfbOCkVTT2ZK.0tYiXixLYow2m14ADN6pXq',1,NULL,1,NULL,NULL,NULL,'2025-09-30 03:48:34','2025-09-30 04:45:29'),(20,3,'David','Wilson',NULL,'david.wilson@e','9876543210',NULL,NULL,'$2y$12$OuEiG030hTQXKlvUTAB9cei0Hxy4bpVgHZU7WTTxMtnZMbm43v6va',0,NULL,1,NULL,NULL,NULL,'2025-09-30 09:32:46','2025-09-30 09:32:46'),(21,NULL,'Priya','Sri',19,'priya@e','77082342570','GlobalTech Solutions',NULL,'$2y$12$BY4/.ncePRBfl9lm/KtO4eW79CwmDLmmIJCoG7zUkxIzKskF4P2BS',0,NULL,1,NULL,NULL,NULL,'2025-09-30 09:38:20','2025-09-30 09:38:40'),(22,4,'jaga','K',NULL,'abc@xmail.com','34845839',NULL,NULL,'$2y$12$IlFAfpwLTrgQZwK2He6tw.VNG0lwcqeP0jiTJ4bh7wxJUbxFekJfW',0,NULL,1,NULL,NULL,NULL,'2025-10-01 06:22:10','2025-10-01 06:22:10'),(23,NULL,'Jaggukumar','K',NULL,'jagg@gmail.com','38389348',NULL,NULL,'$2y$12$Yov35aGOOu3XEwSpkaw9xuleARJF6zWbjf7Xp1gxK/ppCcuh.uHrq',1,NULL,1,NULL,NULL,NULL,'2025-10-01 06:22:55','2025-10-01 06:45:18'),(24,NULL,'Nija','K',23,'nk@gmail.com','39835839',NULL,NULL,'$2y$12$noMJKGfqmoxc7nuKyZoSP.E1bnof3GDi7JLhfF.c6opNR/Cg5hB02',1,NULL,1,NULL,NULL,NULL,'2025-10-01 06:23:42','2025-10-01 06:33:24'),(25,NULL,'Hari Reviewer','K',23,'har@gmail.com','39587348',NULL,NULL,'$2y$12$4Ugq1PFvDgs/6ktZrgqt6O.cPeG5FIQXZwwGdmtumFRK4wo0mC0Ra',0,NULL,1,NULL,NULL,NULL,'2025-10-01 06:24:20','2025-10-01 06:24:20'),(26,NULL,'subin Sense','K',23,'sen@gmail.com','4859394',NULL,NULL,'$2y$12$nmDh63gUv10oEbmzoG37QOBHh.kRhuvG5XVIKVUNYLoj9tkG3xora',0,NULL,1,NULL,NULL,NULL,'2025-10-01 06:24:47','2025-10-01 06:24:47'),(27,5,'Alice','Smith',NULL,'alice.smith@example.com','9123456780',NULL,NULL,'$2y$12$cCsadwNRs3/SJ.m3B2QHAu39A10ehV0DwnmUTwxfa8ZpIq09ZaRgu',0,NULL,1,NULL,NULL,NULL,'2025-10-07 09:40:14','2025-10-07 09:40:14'),(28,5,'Rahul','Sharma',NULL,'rahul.sharma@example.com','9988776655',NULL,NULL,'$2y$12$nWmP4CZsTTmWu3bzxr4IwuIOauCdulPX4XW1AV6WKsyMETTrsCB8q',0,NULL,1,NULL,NULL,NULL,'2025-10-07 09:55:40','2025-10-07 09:55:40'),(29,NULL,'Samuel','Tan',NULL,'samuel.tan@example.com','8959483890',NULL,NULL,'$2y$12$cRAHJ1KWnwLVpReX.t8KNOO3rASgeag46AJ27aNT2VPh7JVqjIshG',0,NULL,1,NULL,NULL,NULL,'2025-10-07 10:04:16','2025-10-07 10:04:55'),(30,6,'Emma','Johnson',NULL,'emma.johnson@example.com','4159876543',NULL,NULL,'$2y$12$HFo.xoauePxBaZqS4vR27OT3sEiVh6aJD9l704X0V6IY0XrcbzKba',0,NULL,1,NULL,NULL,NULL,'2025-10-07 10:41:09','2025-10-07 10:41:09'),(31,NULL,'Michael','Lee',5,'michael.lee@example.com','9123456710',NULL,NULL,'$2y$12$SPoxga7EalPjl56/NKEMpejoumGkzyqmtzvReYYoEZ/UT.EQD98VK',0,NULL,1,NULL,NULL,NULL,'2025-10-07 11:00:23','2025-10-07 11:00:23'),(32,7,'Alice','Smith',NULL,'alice.smith1@example.com','9123456780',NULL,NULL,'$2y$12$crBdYeU/Uw0uIc2aocztnuckp2DsafNfuQxvPg327RTtSeC/AoZqe',0,NULL,1,NULL,NULL,NULL,'2025-10-08 07:09:44','2025-10-08 07:09:44'),(33,8,'Emma','Johnson',NULL,'emma.johnson1@example.com','9876543210',NULL,NULL,'$2y$12$10VAs6bWbyrQW4iOLAp5buCtpU.ItLY.0Zpj6GmmYKjoYQIBhD3qi',0,NULL,1,NULL,NULL,NULL,'2025-10-08 07:10:32','2025-10-08 07:10:32'),(34,NULL,'Jaya','kumari',NULL,'jayakumari.p@springbord.com','8959483890',NULL,NULL,'$2y$12$Y/qvK0Tay7G8isDphN7duuPbsvna9VQlbGHcOB/iVljAtricfYJXm',1,NULL,1,NULL,NULL,NULL,'2025-10-08 07:13:06','2025-10-08 08:01:09'),(35,NULL,'abstractor1','k',34,'abstractor1@springbord.com','9876543210',NULL,NULL,'$2y$12$lo.9GMPYbnipaScYTfbp9u0c3De9OKP8husJiVa1eJwtL6eG.wr5a',1,NULL,1,NULL,NULL,NULL,'2025-10-08 07:14:19','2025-10-08 11:50:05'),(36,NULL,'abstractor2','k',34,'abstractor2@springbord.com','7708234256',NULL,NULL,'$2y$12$VhqoMyRQYZkrIoshL3LThOzj6fsoUD2sWoCcwTFSiAybCmP577Sc.',0,NULL,1,NULL,NULL,NULL,'2025-10-08 07:14:51','2025-10-08 11:50:24'),(37,NULL,'abstractor3','l',34,'abstractor3@springbord.com','8959483892',NULL,NULL,'$2y$12$qRklYsddQVWMDtBHH0NHsueH5AdOKHL7jpFCBMHk7Q1qZ4KaFqOOa',0,NULL,1,NULL,NULL,NULL,'2025-10-08 07:15:15','2025-10-08 11:50:38'),(38,NULL,'review1','A',34,'review1@springbord.com','8959483891',NULL,NULL,'$2y$12$Bzdqyj1EbdLaSfOukjDrguhfgOtGFW7tQ4/7808IZdzxcxReQWnmS',1,NULL,1,NULL,NULL,NULL,'2025-10-08 07:15:50','2025-10-08 11:50:50'),(39,NULL,'review2','G',34,'review2@springbord.com','8959483893',NULL,NULL,'$2y$12$Tn/KAMv/0540FW29.ytNc.VEysH6xnEtViixyDfB1SQavpLoCOGtu',0,NULL,1,NULL,NULL,NULL,'2025-10-08 07:16:12','2025-10-08 11:51:00'),(40,NULL,'review3','H',34,'review3@springbord.com','8959483896',NULL,NULL,'$2y$12$q4oec59xVMHll/BQyRZFQefejgKE37mQQhFUS0rnpLq0O3yfIeZy.',0,NULL,1,NULL,NULL,NULL,'2025-10-08 07:16:32','2025-10-08 11:51:15'),(41,NULL,'sense1','F',34,'sense1@springbord.com','8955483890',NULL,NULL,'$2y$12$vZBoPcIN8y2TpDvZWi88yusN6h99YhfMYyt4esMoTgMHqyw97cJ66',1,NULL,1,NULL,NULL,NULL,'2025-10-08 07:17:05','2025-10-08 11:51:25'),(42,NULL,'sense2','J',34,'sense2@springbord.com','8959433890',NULL,NULL,'$2y$12$9pnFZDvS1iuNXa1P8rG7nOFvm3ET6HR.WsDP9./mVlNuEbYt/eJTO',0,NULL,1,NULL,NULL,NULL,'2025-10-08 07:17:26','2025-10-08 11:51:36'),(43,NULL,'sense3','K',34,'sense3@springbord.com','8989483890',NULL,NULL,'$2y$12$DHf8u5daUlVmEJy6s05wL.NZgOb.sG/YFnZDphsVVhETcm36AN5e.',0,NULL,1,NULL,NULL,NULL,'2025-10-08 07:17:47','2025-10-08 11:51:51'),(44,NULL,'finance','1',34,'finance1@gmail.com','7708234253',NULL,NULL,'$2y$12$mO0L84YavVC/Git.76ui2ePYCXhOQOZz4YHryS2d4Z/5DEDCq2jBe',1,NULL,1,NULL,NULL,NULL,'2025-10-08 08:07:32','2025-10-08 08:08:08'),(45,9,'John','F',NULL,'john@example.com','7708234257',NULL,NULL,'$2y$12$hfoooNEx6l0LP6Zc4Z3CNe59WXZq94gDDTpVCoAv7OZGInSVb6Oq6',0,NULL,1,NULL,NULL,NULL,'2025-10-08 09:27:06','2025-10-08 09:27:06'),(46,NULL,'Nijhanthanraj','G J',NULL,'nijhanth@springbord.com','7708234253',NULL,NULL,'$2y$12$Y51txIOnDd7u88aTM26Zk.Tzg.A7FdlyEPe332j1snyAGKQAab21a',1,NULL,1,NULL,NULL,NULL,'2025-10-08 09:30:58','2025-10-09 04:30:46'),(47,NULL,'abstractor4','A',46,'abstractor4@springbord.com','7708234253',NULL,NULL,'$2y$12$P3yvaMXUnM3.qnTiXFdPx.Ez/BOxKhN4U5qmdg86y53nSppopDEje',1,NULL,1,NULL,NULL,NULL,'2025-10-08 09:36:18','2025-10-08 13:11:21'),(48,NULL,'abstractor5','B',46,'abstractor5@springbord.com','7708234253',NULL,NULL,'$2y$12$.G2cOqSfizmPMIBp6448be.UILziGJMK7qhyt3a9pp6RsxlKIjquO',0,NULL,1,NULL,NULL,NULL,'2025-10-08 09:36:43','2025-10-08 13:12:17'),(49,NULL,'abstractor6','C',46,'abstractor6@springbord.com','7708234253',NULL,NULL,'$2y$12$8zIJd8zO0xFIsIr6e6711eH5bce0.OqmmGbSUw/AEiGaHU672lP9i',0,NULL,1,NULL,NULL,NULL,'2025-10-08 09:37:01','2025-10-08 13:12:24'),(50,NULL,'review4','A',46,'review4@springbord.com','7708234253',NULL,NULL,'$2y$12$S9U6WyKF1x.8dWSq82xkAO.AN5mAjj9vLtYNYNxB5OasgEGjDHQ7a',1,NULL,1,NULL,NULL,NULL,'2025-10-08 09:37:29','2025-10-09 05:09:00'),(51,NULL,'review5','B',46,'review5@springbord.com','7708234253',NULL,NULL,'$2y$12$wNNp3kEKPqaiBeK.9a8mD.ZIsNgbcWPhfW2B1J.I.agMi1g92uGg.',0,NULL,1,NULL,NULL,NULL,'2025-10-08 09:38:52','2025-10-08 13:12:01'),(52,NULL,'review6','C',46,'review6@springbord.com','7708234253',NULL,NULL,'$2y$12$Iq28oXeZjA4oewvjGXSaAu79LAMe9UOvQXUSfM4OgWwzDaggEtate',0,NULL,1,NULL,NULL,NULL,'2025-10-08 09:39:12','2025-10-08 13:11:51'),(53,NULL,'sense4','A',46,'sense4@springbord.com','7708234253',NULL,NULL,'$2y$12$VJA3su0XCvGW1k.hRLjQKucneNXvlKZ..vEOuWzmF0pieXCFkUNOW',1,NULL,1,NULL,NULL,NULL,'2025-10-08 09:39:50','2025-10-09 05:09:47'),(55,NULL,'sense5','B',46,'sense5@springbord.com','7708234253',NULL,NULL,'$2y$12$stjaz40zJWoEdk/ifM8xxOE8k/Bw3/gYZDA8Hg8HA2gNZSkXEbUxS',0,NULL,1,NULL,NULL,NULL,'2025-10-08 09:40:45','2025-10-08 13:11:28'),(56,10,'David','Wilson',NULL,'david.wilson@springbord.com','9123456780',NULL,NULL,'$2y$12$3TQn./V6FEIvztnfbbktmO7SrhT6IFsXyGyVyyhO2Uvg/YyKNvVoi',0,NULL,1,NULL,NULL,NULL,'2025-10-09 04:26:49','2025-10-09 04:26:49'),(58,NULL,'abstractor7','A',57,'abstractor7@springbord.com','8959483890',NULL,NULL,'$2y$12$aI030/1/b5UQ6kj8GaHmnetbpu2bYedq3ImCwcjQ5nGOJufRQTIVu',0,NULL,1,NULL,NULL,NULL,'2025-10-09 04:37:38','2025-10-09 04:37:38'),(59,NULL,'review7','B',57,'review7@springbord.com','8959483890',NULL,NULL,'$2y$12$1RZ6Vi1rOytJgxIOkyGr0.1a8F3Qu2ZU47sRMe1m4L2SpPEuakOvS',0,NULL,1,NULL,NULL,NULL,'2025-10-09 04:38:04','2025-10-09 04:38:04'),(60,NULL,'sense7','C',57,'sense7@springbord.com','8959483890',NULL,NULL,'$2y$12$8et6XwgDMY7W0jEu95S31u/4VTGSrgr6PfBZEIxXjVkAxDnoTlyfu',0,NULL,1,NULL,NULL,NULL,'2025-10-09 04:38:28','2025-10-09 04:38:28'),(61,NULL,'Finance2','B',57,'finance2@springbord.com','8959483890',NULL,NULL,'$2y$12$.chBTmC5TKj32ZmNDEBFaeWu1qbRViPC9XtzlWgcSXiFFLeheAz5u',0,NULL,1,NULL,NULL,NULL,'2025-10-09 05:01:10','2025-10-09 05:01:10'),(62,11,'Peter','Clark',NULL,'peter01@springbord.com','9123456780',NULL,NULL,'$2y$12$iTWqJL5p0MYdLO/2gwG29.nKMBRKiKUlcs2z29wNy1bcQAybyuAxm',0,NULL,1,NULL,NULL,NULL,'2025-10-09 05:22:13','2025-10-09 05:22:13'),(63,12,'David','L',NULL,'david@gmail.com',NULL,NULL,NULL,'$2y$12$Key7tOwyC1YWIu/67YndYeq7K/f2TFClbor2BvKlaNX1cG5KV4C12',0,NULL,1,NULL,NULL,NULL,'2025-10-09 05:55:01','2025-10-09 05:55:01'),(64,NULL,'Nahid','S',NULL,'nahid@springbord.com','1234567899',NULL,NULL,'$2y$12$6OrpOi/ozH0KA.qODbzz6.C/V4lv/ozv4527qi1g02LlMPMXgBo8i',1,NULL,1,NULL,NULL,NULL,'2025-10-09 05:57:26','2025-10-09 12:20:11'),(65,NULL,'abstractor8','L',64,'abstractor8@springbord.com','1234567899',NULL,NULL,'$2y$12$IGOr51fDqQz9a4cae6Crx.3/.b.YWQl.nuXjcqDn1nrc2WCgpkpQq',1,NULL,1,NULL,NULL,NULL,'2025-10-09 06:00:05','2025-10-09 06:26:17'),(66,NULL,'abstractor9','K',64,'abstractor9@springbord.com','1234567899',NULL,NULL,'$2y$12$7gYKUSMOni1lrCmKZogO8uRq/qSV44ncJy23HSjhAXnRg0wr.BW1y',1,NULL,1,NULL,NULL,NULL,'2025-10-09 06:00:45','2025-10-10 04:03:20'),(67,NULL,'review8','K',64,'review8@springbord.com','3958734890',NULL,NULL,'$2y$12$iwoLLPVZRKxxYJUuZnZKu.DvPF3poLWkZiJfyBN4SZ7Ewy2igMTqG',0,NULL,1,NULL,NULL,NULL,'2025-10-09 06:01:26','2025-10-09 06:01:26'),(68,NULL,'review9','I',64,'review9@springbord.com','1234556678',NULL,NULL,'$2y$12$A5T6fir7m6emCZSdWDZ7S.wht8W8.d/IPGthusuJ5SsYFfWfpXXlm',1,NULL,1,NULL,NULL,NULL,'2025-10-09 06:01:54','2025-10-09 12:04:18'),(69,NULL,'sense8','K',64,'sense8@springbord.com','1234567899',NULL,NULL,'$2y$12$naZvSOJkGZBy4.iR/IW81elHcbnzPByg9UNbwuVf10ougvEcrSa/S',1,NULL,1,NULL,NULL,NULL,'2025-10-09 06:02:35','2025-10-09 12:14:02'),(70,NULL,'sense9','J',64,'sense9@springbord.com','1234567899',NULL,NULL,'$2y$12$FsA5b4xp4bYeKv5GuG4Gue2J.3dDznw6F0ceqrt3dArNzOhBPMS.G',0,NULL,1,NULL,NULL,NULL,'2025-10-09 06:03:03','2025-10-09 06:03:03'),(72,NULL,'subin','rabin',NULL,'subin.rabin@springbord.com','8959483890',NULL,NULL,'$2y$12$O5r.wmBsRcG5lYazwx5Tlu9olwkaRpcLVsKtcKpt/KFGofjHiJVwu',0,NULL,1,NULL,NULL,NULL,'2025-10-10 04:35:20','2025-10-10 04:35:20'),(73,12,'Rahul','S',NULL,'rahul@gmail.com','9988776655',NULL,NULL,'$2y$12$R39D0EhHtyAMCLKqTR.r8utoeRuYRZ4W.QtXXY8qg2AxqljqKGX6.',0,NULL,1,NULL,NULL,NULL,'2025-10-10 06:26:11','2025-10-10 06:26:11'),(74,NULL,'sense6','B',46,'sense6@springbord.com','8959483890',NULL,NULL,'$2y$12$g7iQUecNmhq7NxN5Mmv2peEZShCxYaKLStSbN4nvVyHnwJzqAuMsy',1,NULL,1,NULL,NULL,NULL,'2025-10-13 07:04:21','2025-10-13 07:05:10'),(76,NULL,'Finance3','A',46,'finance3@springbord.com','9876543210',NULL,NULL,'$2y$12$V.Mpwj1YY0iWzBZrdmhrlOfPpJ1C/m4ZZD9qHZN9BQfEXiXY5rnAe',1,NULL,1,NULL,NULL,NULL,'2025-10-13 11:25:50','2025-10-13 11:31:09'),(79,9,'Rahul','A',NULL,'rahul1@gmail.com','9988776655',NULL,NULL,'$2y$12$TYcKh.jV0YkAzpahDH75qeGnR3a0D6xuLCmDqOmVoNflfIzU60cUW',1,NULL,1,NULL,NULL,NULL,'2025-10-13 13:29:45','2025-10-13 13:30:15');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `work_sessions`
--

DROP TABLE IF EXISTS `work_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `work_sessions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `task_item_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `started_at` datetime(6) NOT NULL,
  `ended_at` datetime(6) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `work_sessions_user_id_ended_at_index` (`user_id`,`ended_at`),
  KEY `work_sessions_task_item_id_started_at_index` (`task_item_id`,`started_at`),
  CONSTRAINT `work_sessions_task_item_id_foreign` FOREIGN KEY (`task_item_id`) REFERENCES `task_items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `work_sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `work_sessions`
--

LOCK TABLES `work_sessions` WRITE;
/*!40000 ALTER TABLE `work_sessions` DISABLE KEYS */;
INSERT INTO `work_sessions` VALUES (1,1,41,'2025-10-08 07:59:02.000000','2025-10-08 07:59:09.000000','2025-10-08 07:59:02','2025-10-08 07:59:02'),(2,2,41,'2025-10-08 07:59:25.000000','2025-10-08 07:59:31.000000','2025-10-08 07:59:25','2025-10-08 07:59:25'),(3,3,38,'2025-10-08 12:07:44.000000','2025-10-08 12:07:47.000000','2025-10-08 12:07:44','2025-10-08 12:07:44'),(4,4,47,'2025-10-09 04:50:30.000000','2025-10-09 04:50:37.000000','2025-10-09 04:50:30','2025-10-09 04:50:30'),(5,5,47,'2025-10-09 04:51:03.000000','2025-10-09 04:51:09.000000','2025-10-09 04:51:03','2025-10-09 04:51:03'),(6,6,65,'2025-10-09 06:29:40.000000','2025-10-09 06:29:48.000000','2025-10-09 06:29:40','2025-10-09 06:29:40'),(7,7,65,'2025-10-09 06:29:57.000000','2025-10-09 06:30:04.000000','2025-10-09 06:29:57','2025-10-09 06:29:57'),(8,8,65,'2025-10-09 06:30:35.000000','2025-10-09 06:30:40.000000','2025-10-09 06:30:35','2025-10-09 06:30:35'),(9,9,65,'2025-10-09 06:30:49.000000','2025-10-09 06:30:57.000000','2025-10-09 06:30:49','2025-10-09 06:30:49'),(10,8,65,'2025-10-09 06:31:07.000000','2025-10-09 06:31:12.000000','2025-10-09 06:31:07','2025-10-09 06:31:07'),(11,10,65,'2025-10-09 09:20:59.000000','2025-10-09 09:21:12.000000','2025-10-09 09:20:59','2025-10-09 09:20:59'),(12,11,65,'2025-10-09 09:21:23.000000','2025-10-09 09:21:40.000000','2025-10-09 09:21:23','2025-10-09 09:21:23'),(13,12,65,'2025-10-09 09:21:49.000000','2025-10-09 09:21:59.000000','2025-10-09 09:21:49','2025-10-09 09:21:49'),(14,11,65,'2025-10-09 09:22:04.000000','2025-10-09 09:22:11.000000','2025-10-09 09:22:04','2025-10-09 09:22:04'),(15,13,65,'2025-10-09 09:55:07.000000','2025-10-09 09:55:20.000000','2025-10-09 09:55:07','2025-10-09 09:55:07'),(16,14,65,'2025-10-09 09:55:30.000000','2025-10-09 09:55:37.000000','2025-10-09 09:55:30','2025-10-09 09:55:30'),(17,13,65,'2025-10-09 09:55:42.000000','2025-10-09 09:55:45.000000','2025-10-09 09:55:42','2025-10-09 09:55:42'),(18,15,68,'2025-10-09 12:09:52.000000','2025-10-09 12:09:56.000000','2025-10-09 12:09:52','2025-10-09 12:09:52'),(19,16,68,'2025-10-09 12:10:03.000000','2025-10-09 12:10:07.000000','2025-10-09 12:10:03','2025-10-09 12:10:03'),(20,17,68,'2025-10-09 12:10:20.000000','2025-10-09 12:10:24.000000','2025-10-09 12:10:20','2025-10-09 12:10:20'),(21,17,68,'2025-10-09 12:10:59.000000','2025-10-09 12:11:08.000000','2025-10-09 12:10:59','2025-10-09 12:10:59'),(22,15,68,'2025-10-09 12:11:15.000000','2025-10-09 12:11:18.000000','2025-10-09 12:11:15','2025-10-09 12:11:15'),(23,18,66,'2025-10-10 05:00:18.000000','2025-10-10 05:00:52.000000','2025-10-10 05:00:18','2025-10-10 05:00:18'),(24,19,46,'2025-10-13 04:38:40.000000','2025-10-13 04:38:45.000000','2025-10-13 04:38:40','2025-10-13 04:38:40');
/*!40000 ALTER TABLE `work_sessions` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-14 13:16:39
