-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: localhost    Database: playground
-- ------------------------------------------------------
-- Server version	8.4.3

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
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
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
-- Table structure for table `detail_transaksi`
--

DROP TABLE IF EXISTS `detail_transaksi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detail_transaksi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transaksi_id` bigint unsigned DEFAULT NULL,
  `produk_id` bigint unsigned DEFAULT NULL,
  `jumlah` int DEFAULT NULL,
  `harga_satuan` int DEFAULT NULL,
  `subtotal` int DEFAULT NULL,
  `catatan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transaksi_id` (`transaksi_id`),
  KEY `produk_id` (`produk_id`),
  CONSTRAINT `detail_transaksi_ibfk_1` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id`) ON DELETE CASCADE,
  CONSTRAINT `detail_transaksi_ibfk_2` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detail_transaksi`
--

LOCK TABLES `detail_transaksi` WRITE;
/*!40000 ALTER TABLE `detail_transaksi` DISABLE KEYS */;
INSERT INTO `detail_transaksi` VALUES (1,1,1,2,10000,20000,'Anak umur 5 tahun','2026-10-05 18:46:03','2026-10-05 18:46:03'),(2,2,1,2,10000,20000,'Anak umur 5 tahun','2026-10-05 18:46:28','2026-10-05 18:46:28'),(3,3,1,3,10000,30000,NULL,'2026-10-05 19:11:20','2026-10-05 19:11:20'),(4,4,1,3,10000,30000,NULL,'2026-10-05 19:14:33','2026-10-05 19:14:33'),(5,5,1,1,10000,10000,NULL,'2026-10-05 19:27:56','2026-10-05 19:27:56'),(6,6,1,1,10000,10000,NULL,'2026-10-05 19:31:32','2026-10-05 19:31:32'),(7,7,1,1,10000,10000,NULL,'2026-10-05 19:44:02','2026-10-05 19:44:02'),(11,11,1,1,10000,10000,NULL,'2026-10-11 20:52:18','2026-10-11 20:52:18'),(12,12,1,2,10000,20000,NULL,'2026-10-11 23:14:14','2026-10-11 23:14:14'),(13,13,1,2,10000,20000,NULL,'2026-10-12 05:07:58','2026-10-12 05:07:58'),(14,14,1,1,10000,10000,NULL,'2026-10-12 05:17:32','2026-10-12 05:17:32'),(15,15,1,3,10000,30000,NULL,'2026-10-12 05:42:14','2026-10-12 05:42:14'),(16,16,1,5,10000,50000,NULL,'2026-10-12 05:47:54','2026-10-12 05:47:54'),(17,17,2,2,10000,20000,NULL,'2026-10-12 06:05:36','2026-10-12 06:05:36'),(18,17,1,2,10000,20000,NULL,'2026-10-12 06:05:36','2026-10-12 06:05:36'),(19,18,1,4,10000,40000,NULL,'2026-10-12 06:08:47','2026-10-12 06:08:47'),(20,18,2,4,10000,40000,NULL,'2026-10-12 06:08:47','2026-10-12 06:08:47'),(21,19,3,2,4000,8000,NULL,'2026-10-12 07:11:43','2026-10-12 07:11:43'),(22,19,7,1,17000,17000,NULL,'2026-10-12 07:11:43','2026-10-12 07:11:43'),(23,19,5,2,2000,4000,NULL,'2026-10-12 07:11:43','2026-10-12 07:11:43'),(24,19,6,1,3000,3000,NULL,'2026-10-12 07:11:43','2026-10-12 07:11:43'),(25,19,1,2,10000,20000,NULL,'2026-10-12 07:11:43','2026-10-12 07:11:43'),(26,19,2,2,10000,20000,NULL,'2026-10-12 07:11:43','2026-10-12 07:11:43'),(27,20,1,1,10000,10000,NULL,'2026-10-12 07:18:38','2026-10-12 07:18:38'),(28,20,2,1,10000,10000,NULL,'2026-10-12 07:18:38','2026-10-12 07:18:38'),(29,20,6,1,3000,3000,NULL,'2026-10-12 07:18:38','2026-10-12 07:18:38'),(30,20,5,1,2000,2000,NULL,'2026-10-12 07:18:38','2026-10-12 07:18:38'),(31,20,7,1,17000,17000,NULL,'2026-10-12 07:18:38','2026-10-12 07:18:38'),(32,20,4,1,3500,3500,NULL,'2026-10-12 07:18:38','2026-10-12 07:18:38'),(33,20,3,1,4000,4000,NULL,'2026-10-12 07:18:38','2026-10-12 07:18:38'),(34,21,6,1,3000,3000,NULL,'2026-10-12 07:20:33','2026-10-12 07:20:33'),(35,21,5,1,2000,2000,NULL,'2026-10-12 07:20:33','2026-10-12 07:20:33'),(36,21,7,1,17000,17000,NULL,'2026-10-12 07:20:33','2026-10-12 07:20:33'),(37,21,3,1,4000,4000,NULL,'2026-10-12 07:20:33','2026-10-12 07:20:33'),(38,21,4,1,3500,3500,NULL,'2026-10-12 07:20:33','2026-10-12 07:20:33'),(39,22,2,1,10000,10000,NULL,'2026-10-12 07:21:27','2026-10-12 07:21:27'),(40,22,6,1,3000,3000,NULL,'2026-10-12 07:21:27','2026-10-12 07:21:27'),(41,22,5,1,2000,2000,NULL,'2026-10-12 07:21:27','2026-10-12 07:21:27'),(42,22,3,1,4000,4000,NULL,'2026-10-12 07:21:27','2026-10-12 07:21:27'),(43,22,4,1,3500,3500,NULL,'2026-10-12 07:21:27','2026-10-12 07:21:27'),(44,23,3,2,4000,8000,NULL,'2026-10-12 07:35:19','2026-10-12 07:35:19'),(45,23,5,3,2000,6000,NULL,'2026-10-12 07:35:19','2026-10-12 07:35:19'),(46,23,6,3,3000,9000,NULL,'2026-10-12 07:35:19','2026-10-12 07:35:19'),(47,24,6,1,3000,3000,NULL,'2026-10-12 07:39:13','2026-10-12 07:39:13'),(48,25,4,1,3500,3500,NULL,'2026-10-12 07:39:34','2026-10-12 07:39:34'),(49,26,7,1,17000,17000,NULL,'2026-10-12 07:41:48','2026-10-12 07:41:48'),(50,27,1,1,10000,10000,NULL,'2026-10-12 18:00:16','2026-10-12 18:00:16'),(51,27,2,1,10000,10000,NULL,'2026-10-12 18:00:16','2026-10-12 18:00:16'),(52,28,4,3,3500,10500,NULL,'2026-10-12 18:52:32','2026-10-12 18:52:32'),(53,29,2,3,5000,15000,NULL,'2026-10-12 18:54:19','2026-10-12 18:54:19'),(54,30,7,1,17000,17000,NULL,'2026-10-12 18:55:18','2026-10-12 18:55:18'),(55,30,5,1,2000,2000,NULL,'2026-10-12 18:55:18','2026-10-12 18:55:18'),(56,31,3,1,4000,4000,NULL,'2026-10-12 18:57:19','2026-10-12 18:57:19'),(57,32,2,1,5000,5000,NULL,'2026-10-12 19:05:41','2026-10-12 19:05:41'),(58,32,5,1,2000,2000,NULL,'2026-10-12 19:05:41','2026-10-12 19:05:41'),(59,32,3,1,4000,4000,NULL,'2026-10-12 19:05:41','2026-10-12 19:05:41'),(60,33,1,1,10000,10000,NULL,'2026-10-13 19:06:00','2026-10-13 19:06:00'),(61,34,1,3,10000,30000,NULL,'2026-10-23 21:15:26','2026-10-23 21:15:26'),(62,35,1,1,10000,10000,NULL,'2026-10-26 21:05:30','2026-10-26 21:05:30'),(63,35,5,1,2000,2000,NULL,'2026-10-26 21:05:30','2026-10-26 21:05:30'),(64,36,1,1,10000,10000,NULL,'2026-10-26 21:05:32','2026-10-26 21:05:32'),(65,36,5,1,2000,2000,NULL,'2026-10-26 21:05:32','2026-10-26 21:05:32'),(66,37,1,1,10000,10000,NULL,'2026-10-26 21:08:42','2026-10-26 21:08:42'),(67,37,5,1,2000,2000,NULL,'2026-10-26 21:08:42','2026-10-26 21:08:42'),(68,37,3,1,4000,4000,NULL,'2026-10-26 21:08:42','2026-10-26 21:08:42'),(69,38,1,1,10000,10000,NULL,'2026-10-26 21:11:28','2026-10-26 21:11:28'),(70,38,7,1,17000,17000,NULL,'2026-10-26 21:11:28','2026-10-26 21:11:28'),(71,38,3,1,4000,4000,NULL,'2026-10-26 21:11:28','2026-10-26 21:11:28'),(72,39,1,1,10000,10000,NULL,'2026-10-26 21:12:33','2026-10-26 21:12:33'),(73,39,3,1,4000,4000,NULL,'2026-10-26 21:12:33','2026-10-26 21:12:33'),(74,39,7,1,17000,17000,NULL,'2026-10-26 21:12:33','2026-10-26 21:12:33'),(75,40,1,1,10000,10000,NULL,'2026-10-26 21:12:34','2026-10-26 21:12:34'),(76,40,3,1,4000,4000,NULL,'2026-10-26 21:12:34','2026-10-26 21:12:34'),(77,40,7,1,17000,17000,NULL,'2026-10-26 21:12:34','2026-10-26 21:12:34'),(78,41,1,1,10000,10000,NULL,'2026-10-26 21:12:36','2026-10-26 21:12:36'),(79,41,3,1,4000,4000,NULL,'2026-10-26 21:12:36','2026-10-26 21:12:36'),(80,41,7,1,17000,17000,NULL,'2026-10-26 21:12:36','2026-10-26 21:12:36'),(81,42,1,2,10000,20000,NULL,'2026-10-26 21:14:06','2026-10-26 21:14:06'),(82,42,3,1,4000,4000,NULL,'2026-10-26 21:14:06','2026-10-26 21:14:06'),(83,42,7,2,17000,34000,NULL,'2026-10-26 21:14:06','2026-10-26 21:14:06');
/*!40000 ALTER TABLE `detail_transaksi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
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
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
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
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
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
-- Table structure for table `kategori_pengeluaran`
--

DROP TABLE IF EXISTS `kategori_pengeluaran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kategori_pengeluaran` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(25) DEFAULT NULL,
  `deskripsi` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kategori_pengeluaran`
--

LOCK TABLES `kategori_pengeluaran` WRITE;
/*!40000 ALTER TABLE `kategori_pengeluaran` DISABLE KEYS */;
INSERT INTO `kategori_pengeluaran` VALUES (1,'Operasional Harian',NULL,NULL,NULL),(2,'Gaji Karyawan',NULL,NULL,NULL),(3,'Perawatan Wahana',NULL,NULL,NULL);
/*!40000 ALTER TABLE `kategori_pengeluaran` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kategori_produk`
--

DROP TABLE IF EXISTS `kategori_produk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kategori_produk` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(25) DEFAULT NULL,
  `deskripsi` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kategori_produk`
--

LOCK TABLES `kategori_produk` WRITE;
/*!40000 ALTER TABLE `kategori_produk` DISABLE KEYS */;
INSERT INTO `kategori_produk` VALUES (1,'Atribut Bermain',NULL,NULL,'2026-10-12 17:58:44'),(2,'camilan',NULL,'2026-09-14 23:48:52','2026-10-12 17:58:44'),(3,'minuman',NULL,'2026-10-12 06:16:33','2026-10-12 17:58:44');
/*!40000 ALTER TABLE `kategori_produk` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `meja`
--

DROP TABLE IF EXISTS `meja`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `meja` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nomor_meja` int DEFAULT NULL,
  `qr_token` varchar(255) DEFAULT NULL,
  `qr_code_url` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `qr_token` (`qr_token`),
  UNIQUE KEY `nomor_meja` (`nomor_meja`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `meja`
--

LOCK TABLES `meja` WRITE;
/*!40000 ALTER TABLE `meja` DISABLE KEYS */;
INSERT INTO `meja` VALUES (1,1,'X7A9',NULL,1,NULL,NULL),(2,2,'KATD0',NULL,1,'2026-09-14 23:54:34','2026-09-14 23:54:34'),(3,3,'1FZTB',NULL,1,'2026-10-05 18:58:12','2026-10-05 18:58:12');
/*!40000 ALTER TABLE `meja` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_10_13_000003_seed_kategori_dan_produk_katalog',1),(5,'2026_10_26_110037_ubah_kolom_pembayaran_transaksi_ke_snap',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `pengeluaran`
--

DROP TABLE IF EXISTS `pengeluaran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pengeluaran` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `kategori_pengeluaran_id` bigint unsigned DEFAULT NULL,
  `tanggal_pengeluaran` date DEFAULT NULL,
  `nama_pengeluaran` varchar(100) DEFAULT NULL,
  `nominal` int DEFAULT NULL,
  `deskripsi` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `kategori_pengeluaran_id` (`kategori_pengeluaran_id`),
  CONSTRAINT `pengeluaran_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pengeluaran_ibfk_2` FOREIGN KEY (`kategori_pengeluaran_id`) REFERENCES `kategori_pengeluaran` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pengeluaran`
--

LOCK TABLES `pengeluaran` WRITE;
/*!40000 ALTER TABLE `pengeluaran` DISABLE KEYS */;
INSERT INTO `pengeluaran` VALUES (1,2,1,'2026-10-13','tukar galon',20000,'tukar galon','2026-10-12 18:06:51','2026-10-12 18:06:51'),(2,2,2,'2026-10-13','penggajian bulan oktober',1500000,'penggajian bulan oktober','2026-10-12 18:08:34','2026-10-12 18:08:34');
/*!40000 ALTER TABLE `pengeluaran` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produk`
--

DROP TABLE IF EXISTS `produk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produk` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kategori_produk_id` bigint unsigned DEFAULT NULL,
  `nama_produk` varchar(100) DEFAULT NULL,
  `harga` int DEFAULT NULL,
  `stok` int DEFAULT NULL,
  `foto_produk` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kategori_produk_id` (`kategori_produk_id`),
  CONSTRAINT `produk_ibfk_1` FOREIGN KEY (`kategori_produk_id`) REFERENCES `kategori_produk` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produk`
--

LOCK TABLES `produk` WRITE;
/*!40000 ALTER TABLE `produk` DISABLE KEYS */;
INSERT INTO `produk` VALUES (1,1,'Tiket Mandi Bola',10000,7,'produk/GSTKWZP8tbuagea9YH7x01BKXfwW5nrrAXV9mM6s.jpg',1,NULL,'2026-10-26 21:14:06'),(2,1,'Kaos Kaki',5000,25,'produk/0XSRNAaZNdZnNFqZqNeC25uGsRORchzCcuhuporc.jpg',1,'2026-10-12 06:04:47','2026-10-12 19:05:41'),(3,3,'aqua',4000,9,'produk/fCKXxQGilXRr3GefXH9CID3lN7XYtHq28Q5nTrm2.jpg',1,'2026-10-12 06:18:44','2026-10-26 21:14:06'),(4,3,'teh pucuk',3500,12,'produk/6Ifl9BiSnIVRuDuX1APUOUYAv9vrkhOk8Z0w63Vg.jpg',1,'2026-10-12 06:19:21','2026-10-12 18:52:32'),(5,2,'oreo',2000,21,'produk/0tFhhzLz1UIdymmf8E6lIlxm7yMUm3vuUD3sskWb.png',1,'2026-10-12 07:00:29','2026-10-26 21:08:42'),(6,2,'qtela',3000,10,'produk/dm5ABX5lyXia1xVWI4jV3BynkNx0jdF0HyaRXo1o.jpg',1,'2026-10-12 07:01:45','2026-10-12 17:58:44'),(7,2,'chitato',17000,14,'produk/dEhHVqKTPcVh2MaCpVXiMu4ug6v5AgLne2qbj7BD.jpg',1,'2026-10-12 07:02:52','2026-10-26 21:14:06');
/*!40000 ALTER TABLE `produk` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
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
INSERT INTO `sessions` VALUES ('8VfE43GxLhoWvMuAgtNJvxvH5rNHr6Ylbw0sMh8C',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','eyJfdG9rZW4iOiI1amdvZDZ0ZDZtTnNsT1dPV1o0NUVxNVNGRnVtaG1ocUlaQktaVGs5IiwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvcGxheWdyb3VuZC50ZXN0XC9rYXNpclwvYXBpXC9wZXNhbmFuLWFrdGlmIn0sIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9wbGF5Z3JvdW5kLnRlc3RcL2thc2lyXC9hcGlcL3Blc2FuYW4tYWt0aWYiLCJyb3V0ZSI6bnVsbH0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1791856176);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksi`
--

DROP TABLE IF EXISTS `transaksi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transaksi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode_transaksi` varchar(255) DEFAULT NULL,
  `access_token` varchar(255) DEFAULT NULL,
  `kasir_id` bigint unsigned DEFAULT NULL,
  `meja_id` bigint unsigned DEFAULT NULL,
  `nama_pemesan` varchar(100) DEFAULT NULL,
  `tipe_pesanan` enum('qr code','kasir') DEFAULT NULL,
  `metode_pembayaran` enum('tunai','payment_gateway') DEFAULT NULL,
  `total_harga` int DEFAULT NULL,
  `uang_diterima` int DEFAULT NULL,
  `uang_kembalian` int DEFAULT NULL,
  `midtrans_transaction_id` varchar(64) DEFAULT NULL,
  `payment_url` varchar(255) DEFAULT NULL,
  `payment_expired_at` datetime DEFAULT NULL,
  `status_pembayaran` enum('pending','lunas','batal','kadaluarsa') DEFAULT NULL,
  `status_pesanan` enum('menunggu','diproses','selesai','dibatalkan') DEFAULT NULL,
  `waktu_transaksi` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `payment_channel` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_transaksi` (`kode_transaksi`),
  UNIQUE KEY `access_token` (`access_token`),
  KEY `meja_id` (`meja_id`),
  KEY `fk_transaksi_kasir` (`kasir_id`),
  CONSTRAINT `fk_transaksi_kasir` FOREIGN KEY (`kasir_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`meja_id`) REFERENCES `meja` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksi`
--

LOCK TABLES `transaksi` WRITE;
/*!40000 ALTER TABLE `transaksi` DISABLE KEYS */;
INSERT INTO `transaksi` VALUES (1,'TRX-6AC452DB78F35','x1XKxlyN7DjKgMqmBAaJT9xVj3QgljYN',2,1,'Budi Tes','qr code','tunai',20000,NULL,NULL,NULL,NULL,NULL,'lunas','selesai','2026-10-06 01:46:03','2026-10-05 18:46:03','2026-10-05 19:13:36',NULL),(2,'TRX-6AC452F4CD679','zNZwlpodaLKJTbJJvykzH10uhIix3ibU',2,1,'Budi Tes','qr code','tunai',20000,NULL,NULL,NULL,NULL,NULL,'lunas','selesai','2026-10-06 01:46:28','2026-10-05 18:46:28','2026-10-05 18:46:28',NULL),(3,'TRX-6AC458C899F82','OWMEAozGXy6CXAKAczpSh8jVGBA2zhyX',2,2,'tes','qr code','tunai',30000,NULL,NULL,NULL,NULL,NULL,'lunas','selesai','2026-10-06 02:11:20','2026-10-05 19:11:20','2026-10-05 19:13:05',NULL),(4,'TRX-6AC45989031A0','JljiwSvZPEUkvxyCtWaeHUBD1KPtgkFm',2,3,'tes','qr code','tunai',30000,NULL,NULL,NULL,NULL,NULL,'lunas','selesai','2026-10-06 02:14:33','2026-10-05 19:14:33','2026-10-05 19:28:42',NULL),(5,'TRX-6AC45CACABB94','swT9D8by5SPpXhdwU6rmejHfq45JpuYs',2,3,'tes','qr code','tunai',10000,10000,0,NULL,NULL,NULL,'lunas','selesai','2026-10-06 02:27:56','2026-10-05 19:27:56','2026-10-11 21:06:06',NULL),(6,'TRX-6AC45D84AAC98','oPT4ntjtVmPeRu6jRDzJCBgAiFpb7qBm',2,1,'fahmi','qr code','tunai',10000,10000,0,NULL,NULL,NULL,'lunas','selesai','2026-10-06 02:31:32','2026-10-05 19:31:32','2026-10-11 22:29:58',NULL),(7,'TRX-6AC46072C4AFD','0Z4DCXYaTbi55kW5wIcZzeFlkBx76P8p',2,1,'fahmi','qr code','tunai',10000,NULL,NULL,NULL,NULL,NULL,'batal','dibatalkan','2026-10-06 02:44:02','2026-10-05 19:44:02','2026-10-11 21:36:53',NULL),(11,'TRX-6ACC59726810B','042cz3VWdO5uqgAoXBSL2epsR4IvrB5o',2,3,'tes','qr code','payment_gateway',10000,NULL,NULL,'17b3a3a5-22be-42a6-b7fa-560b9e0cfa7e',NULL,'2026-10-12 04:07:19','lunas','selesai','2026-10-12 03:52:18','2026-10-11 20:52:18','2026-10-11 21:06:12',NULL),(12,'TRX-6ACC7AB675A78','pmCpueQc7GZ73dJdPhZSD18qTVIRffWq',2,1,'tes','qr code','payment_gateway',20000,NULL,NULL,'be221f02-174f-4d45-b2c0-cdd99c3dcf04',NULL,'2026-10-12 06:29:16','batal','dibatalkan','2026-10-12 06:14:14','2026-10-11 23:14:14','2026-10-11 23:15:37',NULL),(13,'TRX-6ACCCD9E6C2BB','qtutJ0HYnMdWCBF8WplFiejgHhMjiqQS',2,2,'tes','qr code','payment_gateway',20000,NULL,NULL,'7238d431-466a-4f85-941d-8c04bc02598b',NULL,'2026-10-12 12:23:00','lunas','selesai','2026-10-12 12:07:58','2026-10-12 05:07:58','2026-10-12 05:11:48',NULL),(14,'TRX-6ACCCFDC1A642','tBWiMiBFWL3QwUP6EVi1OZc80fnlTGIo',2,1,'tes','qr code','payment_gateway',10000,NULL,NULL,'18eb6c09-931a-4b20-aa8d-cfed3f2f3fab',NULL,'2026-10-12 12:32:35','lunas','selesai','2026-10-12 12:17:32','2026-10-12 05:17:32','2026-10-12 05:41:36',NULL),(15,'TRX-6ACCD5A6A8DD8','VYZ6kZ1R9elAPoQeSGnidRqknzWl0Dkx',2,1,'nia','qr code','payment_gateway',30000,NULL,NULL,'39331ec8-83b1-4ec6-8d96-7677b8187b4e',NULL,'2026-10-12 12:57:16','lunas','selesai','2026-10-12 12:42:14','2026-10-12 05:42:14','2026-10-12 05:47:06',NULL),(16,'TRX-6ACCD6FA37E01','owf3sQ3NVhQ1zhEgLDJS9MV96Zapvn1C',2,1,'wahyu','qr code','payment_gateway',50000,NULL,NULL,'98800205-a899-41ac-ac87-c0d3325b53e4',NULL,'2026-10-12 13:02:56','lunas','selesai','2026-10-12 12:47:54','2026-10-12 05:47:54','2026-10-12 05:48:12',NULL),(17,'TRX-6ACCDB2020951','nbnqScQ0sz6ODE5WkehoKl0PBPbH57L2',2,1,'bela','qr code','payment_gateway',40000,NULL,NULL,'f0a8da7c-322e-4bf0-9570-587df12f2aca',NULL,'2026-10-12 13:20:38','lunas','selesai','2026-10-12 13:05:36','2026-10-12 06:05:36','2026-10-12 06:06:35',NULL),(18,'TRX-6ACCDBDF84751','werYPrpAR7GxXJYfQT8KWclJ4pIW8Dgs',2,1,'yahya','qr code','payment_gateway',80000,NULL,NULL,'134d459c-143a-49f8-87e2-d4e0d0015af4',NULL,'2026-10-12 13:23:49','lunas','selesai','2026-10-12 13:08:47','2026-10-12 06:08:47','2026-10-12 06:09:33',NULL),(19,'TRX-6ACCEA9F68269','CFw4i7YHQGGnSpzAZx5NhzJ16b8MVHNd',2,1,'melia','qr code','tunai',72000,72000,0,NULL,NULL,NULL,'lunas','selesai','2026-10-12 14:11:43','2026-10-12 07:11:43','2026-10-12 07:17:42',NULL),(20,'TRX-6ACCEC3E84F53','7TFiztynbW1xZZ4M5vDnSb6KbD5VqzsD',2,1,'lala','qr code','tunai',49500,49500,0,NULL,NULL,NULL,'lunas','selesai','2026-10-12 14:18:38','2026-10-12 07:18:38','2026-10-12 18:56:20',NULL),(21,'TRX-6ACCECB11F34A','bgQkfD8eECLFm6BHOuM7h49sgCr1wW1J',2,1,'lala','qr code','tunai',29500,29500,0,NULL,NULL,NULL,'lunas','selesai','2026-10-12 14:20:33','2026-10-12 07:20:33','2026-10-12 07:22:24',NULL),(22,'TRX-6ACCECE7AE6C6','vvuDcXQTTvlDOpDUw1DbUBSQfSurTCds',2,1,'fian','qr code','payment_gateway',22500,NULL,NULL,'8b314500-3599-4d10-a4e3-a1fa38ccd4a5',NULL,'2026-10-12 14:36:29','lunas','selesai','2026-10-12 14:21:27','2026-10-12 07:21:27','2026-10-12 18:49:12',NULL),(23,'TRX-6ACCF0275BDD0','XFNZhLdY8E94oaMH8dB9AOIdNO9rIUZ2',2,NULL,'vera','kasir','tunai',23000,23000,0,NULL,NULL,NULL,'lunas','diproses','2026-10-12 14:35:19','2026-10-12 07:35:19','2026-10-12 07:35:19',NULL),(24,'TRX-6ACCF1111F942','jPDvNdmHd9wmjmNwJvxJdd2Y3g5SA1ZS',2,NULL,'Pelanggan','kasir','tunai',3000,3000,0,NULL,NULL,NULL,'lunas','diproses','2026-10-12 14:39:13','2026-10-12 07:39:13','2026-10-12 07:39:13',NULL),(25,'TRX-6ACCF12689E65','BbpFQl8hq7DKzdrQ4RsvrngZvC0mKoEA',2,NULL,'Pelanggan','kasir','tunai',3500,20000,16500,NULL,NULL,NULL,'lunas','selesai','2026-10-12 14:39:34','2026-10-12 07:39:34','2026-10-12 18:55:43',NULL),(26,'TRX-6ACCF1AC1336C','s8mlRuU1AmbAb1vPtCiRitllFIMIU87Y',2,NULL,'Pelanggan','kasir','tunai',17000,20000,3000,NULL,NULL,NULL,'lunas','diproses','2026-10-12 14:41:48','2026-10-12 07:41:48','2026-10-12 07:41:48',NULL),(27,'TRX-6ACD82A0BD8A6','cEnGoxzxTvxDWSadMNpInaZ5PnQjBWr1',2,1,'kris','qr code','payment_gateway',20000,NULL,NULL,'4486b9f8-7f9f-4f50-97d2-200596357cd6',NULL,'2026-10-13 01:15:19','lunas','selesai','2026-10-13 01:00:16','2026-10-12 18:00:16','2026-10-12 18:56:42',NULL),(28,'TRX-6ACD8EE051259','dFhxI2JpIWdhFVDvJCBofXC48YMg1oYi',2,NULL,'Pelanggan','kasir','tunai',10500,10500,0,NULL,NULL,NULL,'lunas','diproses','2026-10-13 01:52:32','2026-10-12 18:52:32','2026-10-12 18:52:32',NULL),(29,'TRX-6ACD8F4B70635','Ts9cOYlQtV9KJEHTYGqoDxnr3nLGhXf2',2,1,'berliana','qr code','payment_gateway',15000,NULL,NULL,'292df262-ea8e-4478-a3f7-04c04e9fbb36',NULL,'2026-10-13 02:09:21','lunas','selesai','2026-10-13 01:54:19','2026-10-12 18:54:19','2026-10-12 18:56:28',NULL),(30,'TRX-6ACD8F86C461F','IUDM88hwbOXGA6o9aPzxU2SPUsiHsaGA',2,1,'hai','qr code','payment_gateway',19000,NULL,NULL,'d7633df4-aa33-4dc7-afd0-a824e9b36acb',NULL,'2026-10-13 02:10:21','batal','dibatalkan','2026-10-13 01:55:18','2026-10-12 18:55:18','2026-10-12 18:56:36',NULL),(31,'TRX-6ACD8FFFA6A90','QRI9glE1oZMTHtmyo1Ibo5i4nVCblu6l',2,1,'dara','qr code','payment_gateway',4000,NULL,NULL,'88f341f3-bbfb-42b9-8594-00c6b7f85b35',NULL,'2026-10-13 02:12:21','batal','dibatalkan','2026-10-13 01:57:19','2026-10-12 18:57:19','2026-10-23 21:14:46',NULL),(32,'TRX-6ACD91F5B1F40','tbjRu4wr8kNHULFyz6NrUlkmJh8c3xG9',2,1,'aurel','qr code','payment_gateway',11000,NULL,NULL,'c8ae798d-eb56-4b7b-8edc-16728231ba55',NULL,'2026-10-13 02:20:44','lunas','selesai','2026-10-13 02:05:41','2026-10-12 19:05:41','2026-10-12 19:08:20',NULL),(33,'TRX-6ACEE3884CCC3','7lIybJhfMD8hY4gVpp8beI3WbVHHx0l6',2,3,'hai','qr code','payment_gateway',10000,NULL,NULL,'fa7dbdcc-c9a0-4c71-9aca-457df6b132fe',NULL,'2026-10-14 02:21:01','batal','dibatalkan','2026-10-14 02:06:00','2026-10-13 19:06:00','2026-10-23 21:14:48',NULL),(34,'TRX-6ADC30DEA753A','ZGA70ElvnoTlm4memM4PqQ8MTGmKk4iN',NULL,1,'dara','qr code','payment_gateway',30000,NULL,NULL,'144d6522-b7c7-4da2-ad7b-5e3bdac1fb01',NULL,'2026-10-24 04:30:27','pending','menunggu','2026-10-24 04:15:26','2026-10-23 21:15:26','2026-10-23 21:15:27',NULL),(35,'TRX-6AE0230A9D2EB','pw8IkR6i9oInlejZuvw5oGPF9E2KyBeJ',NULL,1,'Tara','qr code','payment_gateway',12000,NULL,NULL,NULL,'https://app.sandbox.midtrans.com/snap/v4/redirection/3fd84c67-a8ea-4dde-a6b1-a74cb190d901','2026-10-27 05:05:32','pending','menunggu','2026-10-27 04:05:30','2026-10-26 21:05:30','2026-10-26 21:05:32','bca_va'),(36,'TRX-6AE0230C9410B','tzuzKWwm7TOZkYBHvz0ODuHTRd4cM6ll',NULL,1,'Tara','qr code','payment_gateway',12000,NULL,NULL,'98d69641-14ce-43f5-a4a1-52f4206dcf36','https://app.sandbox.midtrans.com/snap/v4/redirection/3d277bc7-fc78-4387-bf8e-df8fd0070de6','2026-10-27 05:05:34','lunas','diproses','2026-10-27 04:05:32','2026-10-26 21:05:32','2026-10-26 21:06:22','bca_va'),(37,'TRX-6AE023CA65E8F','GrgZ5zgZaTrPrhjLqaphQFhDsVfnK2jg',NULL,1,'Jia','qr code','payment_gateway',16000,NULL,NULL,'4b8c342b-b65b-4a44-8c12-fb06caae2815','https://app.sandbox.midtrans.com/snap/v4/redirection/d03f1f5e-fe98-4ff9-8ce8-efb9254d6c22','2026-10-27 05:08:43','pending','menunggu','2026-10-27 04:08:42','2026-10-26 21:08:42','2026-10-26 21:09:07','gopay'),(38,'TRX-6AE02470C4950','3eIRepuLp9cnMMkBR5htJto3iwIUVpjk',NULL,1,'Ila','qr code','payment_gateway',31000,NULL,NULL,NULL,'https://app.sandbox.midtrans.com/snap/v4/redirection/11a9b000-7c0e-44e9-ba4a-2ae17b12c50d','2026-10-27 05:11:30','pending','menunggu','2026-10-27 04:11:28','2026-10-26 21:11:28','2026-10-26 21:11:30','bca_va'),(39,'TRX-6AE024B17E39D','pF1sxUN5mKTnMlrERqj5Qi8uqlxfo1eU',NULL,2,'Ila','qr code','payment_gateway',31000,NULL,NULL,NULL,'https://app.sandbox.midtrans.com/snap/v4/redirection/af39ecc0-ae12-4f80-8a5a-d1e4b2fa4305','2026-10-27 05:12:34','pending','menunggu','2026-10-27 04:12:33','2026-10-26 21:12:33','2026-10-26 21:12:34','bca_va'),(40,'TRX-6AE024B2B7403','z6P2LvIfrSO6N8kNA3sarTYPvKesVrJV',NULL,2,'Ila','qr code','payment_gateway',31000,NULL,NULL,NULL,'https://app.sandbox.midtrans.com/snap/v4/redirection/b4137fc3-2ef6-4a99-8f44-fdea09c4c37b','2026-10-27 05:12:36','pending','menunggu','2026-10-27 04:12:34','2026-10-26 21:12:34','2026-10-26 21:12:36','bca_va'),(41,'TRX-6AE024B42B989','vWpLuN9EgFvofjjybHaiypmFlZgPbo0Y',NULL,2,'Ila','qr code','payment_gateway',31000,NULL,NULL,NULL,'https://app.sandbox.midtrans.com/snap/v4/redirection/938f50b0-243e-4fcb-8023-cf9f2032ef5b','2026-10-27 05:12:37','pending','menunggu','2026-10-27 04:12:36','2026-10-26 21:12:36','2026-10-26 21:12:37','bca_va'),(42,'TRX-6AE0250E18B99','ODhJQVYCAJTVylaA8H5KFmL5skEP7xmx',NULL,3,'Lita','qr code','payment_gateway',58000,NULL,NULL,'b67d1067-bafa-44a9-afb1-39747053764b','https://app.sandbox.midtrans.com/snap/v4/redirection/9825f0db-14cd-4a71-8674-e26b775743c9','2026-10-27 05:14:06','lunas','diproses','2026-10-27 04:14:06','2026-10-26 21:14:06','2026-10-26 21:15:06','bca_va');
/*!40000 ALTER TABLE `transaksi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) DEFAULT NULL,
  `username` varchar(70) DEFAULT NULL,
  `role` enum('owner','kasir') DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Pak Owner','owner','owner','$2y$12$.ERyycIrZYNVA0Av5rhhGumcY5DGu9pjRqWXJuI5EfNv0FLK2Sf7u',NULL,NULL),(2,'Mbak Kasir','kasir','kasir','$2y$12$.ERyycIrZYNVA0Av5rhhGumcY5DGu9pjRqWXJuI5EfNv0FLK2Sf7u',NULL,NULL);
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

-- Dump completed on 2026-11-08 17:10:44
