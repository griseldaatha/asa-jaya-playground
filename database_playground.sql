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
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detail_transaksi`
--

LOCK TABLES `detail_transaksi` WRITE;
/*!40000 ALTER TABLE `detail_transaksi` DISABLE KEYS */;
INSERT INTO `detail_transaksi` VALUES (1,1,1,2,10000,20000,'Anak umur 5 tahun','2026-10-05 18:46:03','2026-10-05 18:46:03'),(2,2,1,2,10000,20000,'Anak umur 5 tahun','2026-10-05 18:46:28','2026-10-05 18:46:28'),(3,3,1,3,10000,30000,NULL,'2026-10-05 19:11:20','2026-10-05 19:11:20'),(4,4,1,3,10000,30000,NULL,'2026-10-05 19:14:33','2026-10-05 19:14:33'),(5,5,1,1,10000,10000,NULL,'2026-10-05 19:27:56','2026-10-05 19:27:56'),(6,6,1,1,10000,10000,NULL,'2026-10-05 19:31:32','2026-10-05 19:31:32'),(7,7,1,1,10000,10000,NULL,'2026-10-05 19:44:02','2026-10-05 19:44:02'),(11,11,1,1,10000,10000,NULL,'2026-10-11 20:52:18','2026-10-11 20:52:18'),(12,12,1,2,10000,20000,NULL,'2026-10-11 23:14:14','2026-10-11 23:14:14'),(13,13,1,2,10000,20000,NULL,'2026-10-12 05:07:58','2026-10-12 05:07:58'),(14,14,1,1,10000,10000,NULL,'2026-10-12 05:17:32','2026-10-12 05:17:32'),(15,15,1,3,10000,30000,NULL,'2026-10-12 05:42:14','2026-10-12 05:42:14'),(16,16,1,5,10000,50000,NULL,'2026-10-12 05:47:54','2026-10-12 05:47:54'),(17,17,2,2,10000,20000,NULL,'2026-10-12 06:05:36','2026-10-12 06:05:36'),(18,17,1,2,10000,20000,NULL,'2026-10-12 06:05:36','2026-10-12 06:05:36'),(19,18,1,4,10000,40000,NULL,'2026-10-12 06:08:47','2026-10-12 06:08:47'),(20,18,2,4,10000,40000,NULL,'2026-10-12 06:08:47','2026-10-12 06:08:47'),(21,19,3,2,4000,8000,NULL,'2026-10-12 07:11:43','2026-10-12 07:11:43'),(22,19,7,1,17000,17000,NULL,'2026-10-12 07:11:43','2026-10-12 07:11:43'),(23,19,5,2,2000,4000,NULL,'2026-10-12 07:11:43','2026-10-12 07:11:43'),(24,19,6,1,3000,3000,NULL,'2026-10-12 07:11:43','2026-10-12 07:11:43'),(25,19,1,2,10000,20000,NULL,'2026-10-12 07:11:43','2026-10-12 07:11:43'),(26,19,2,2,10000,20000,NULL,'2026-10-12 07:11:43','2026-10-12 07:11:43'),(27,20,1,1,10000,10000,NULL,'2026-10-12 07:18:38','2026-10-12 07:18:38'),(28,20,2,1,10000,10000,NULL,'2026-10-12 07:18:38','2026-10-12 07:18:38'),(29,20,6,1,3000,3000,NULL,'2026-10-12 07:18:38','2026-10-12 07:18:38'),(30,20,5,1,2000,2000,NULL,'2026-10-12 07:18:38','2026-10-12 07:18:38'),(31,20,7,1,17000,17000,NULL,'2026-10-12 07:18:38','2026-10-12 07:18:38'),(32,20,4,1,3500,3500,NULL,'2026-10-12 07:18:38','2026-10-12 07:18:38'),(33,20,3,1,4000,4000,NULL,'2026-10-12 07:18:38','2026-10-12 07:18:38'),(34,21,6,1,3000,3000,NULL,'2026-10-12 07:20:33','2026-10-12 07:20:33'),(35,21,5,1,2000,2000,NULL,'2026-10-12 07:20:33','2026-10-12 07:20:33'),(36,21,7,1,17000,17000,NULL,'2026-10-12 07:20:33','2026-10-12 07:20:33'),(37,21,3,1,4000,4000,NULL,'2026-10-12 07:20:33','2026-10-12 07:20:33'),(38,21,4,1,3500,3500,NULL,'2026-10-12 07:20:33','2026-10-12 07:20:33'),(39,22,2,1,10000,10000,NULL,'2026-10-12 07:21:27','2026-10-12 07:21:27'),(40,22,6,1,3000,3000,NULL,'2026-10-12 07:21:27','2026-10-12 07:21:27'),(41,22,5,1,2000,2000,NULL,'2026-10-12 07:21:27','2026-10-12 07:21:27'),(42,22,3,1,4000,4000,NULL,'2026-10-12 07:21:27','2026-10-12 07:21:27'),(43,22,4,1,3500,3500,NULL,'2026-10-12 07:21:27','2026-10-12 07:21:27'),(44,23,3,2,4000,8000,NULL,'2026-10-12 07:35:19','2026-10-12 07:35:19'),(45,23,5,3,2000,6000,NULL,'2026-10-12 07:35:19','2026-10-12 07:35:19'),(46,23,6,3,3000,9000,NULL,'2026-10-12 07:35:19','2026-10-12 07:35:19'),(47,24,6,1,3000,3000,NULL,'2026-10-12 07:39:13','2026-10-12 07:39:13'),(48,25,4,1,3500,3500,NULL,'2026-10-12 07:39:34','2026-10-12 07:39:34'),(49,26,7,1,17000,17000,NULL,'2026-10-12 07:41:48','2026-10-12 07:41:48');
/*!40000 ALTER TABLE `detail_transaksi` ENABLE KEYS */;
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
INSERT INTO `kategori_produk` VALUES (1,'Atribut Bermain',NULL,NULL,'2026-10-12 06:03:24'),(2,'camilan',NULL,'2026-09-14 23:48:52','2026-10-12 07:00:37'),(3,'minuman',NULL,'2026-10-12 06:16:33','2026-10-12 06:16:33');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pengeluaran`
--

LOCK TABLES `pengeluaran` WRITE;
/*!40000 ALTER TABLE `pengeluaran` DISABLE KEYS */;
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
INSERT INTO `produk` VALUES (1,1,'Tiket Mandi Bola (1 Jam)',10000,19,'produk/GSTKWZP8tbuagea9YH7x01BKXfwW5nrrAXV9mM6s.jpg',1,NULL,'2026-10-12 07:18:38'),(2,1,'Kaos Kaki',10000,30,'produk/0XSRNAaZNdZnNFqZqNeC25uGsRORchzCcuhuporc.jpg',1,'2026-10-12 06:04:47','2026-10-12 07:21:27'),(3,3,'aqua',4000,13,'produk/fCKXxQGilXRr3GefXH9CID3lN7XYtHq28Q5nTrm2.jpg',1,'2026-10-12 06:18:44','2026-10-12 07:35:19'),(4,3,'teh pucuk',3500,11,'produk/6Ifl9BiSnIVRuDuX1APUOUYAv9vrkhOk8Z0w63Vg.jpg',1,'2026-10-12 06:19:21','2026-10-12 07:39:34'),(5,2,'oreo',2000,22,'produk/0tFhhzLz1UIdymmf8E6lIlxm7yMUm3vuUD3sskWb.png',1,'2026-10-12 07:00:29','2026-10-12 07:35:19'),(6,2,'qtela',3000,2,'produk/dm5ABX5lyXia1xVWI4jV3BynkNx0jdF0HyaRXo1o.jpg',1,'2026-10-12 07:01:45','2026-10-12 07:39:13'),(7,2,'chitato',17000,16,'produk/dEhHVqKTPcVh2MaCpVXiMu4ug6v5AgLne2qbj7BD.jpg',1,'2026-10-12 07:02:52','2026-10-12 07:41:48');
/*!40000 ALTER TABLE `produk` ENABLE KEYS */;
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
  `qris_url` varchar(255) DEFAULT NULL,
  `qris_expired_at` datetime DEFAULT NULL,
  `status_pembayaran` enum('pending','lunas','batal','kadaluarsa') DEFAULT NULL,
  `status_pesanan` enum('menunggu','diproses','selesai','dibatalkan') DEFAULT NULL,
  `waktu_transaksi` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_transaksi` (`kode_transaksi`),
  UNIQUE KEY `access_token` (`access_token`),
  KEY `meja_id` (`meja_id`),
  KEY `fk_transaksi_kasir` (`kasir_id`),
  CONSTRAINT `fk_transaksi_kasir` FOREIGN KEY (`kasir_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`meja_id`) REFERENCES `meja` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksi`
--

LOCK TABLES `transaksi` WRITE;
/*!40000 ALTER TABLE `transaksi` DISABLE KEYS */;
INSERT INTO `transaksi` VALUES (1,'TRX-6AC452DB78F35','x1XKxlyN7DjKgMqmBAaJT9xVj3QgljYN',2,1,'Budi Tes','qr code','tunai',20000,NULL,NULL,NULL,NULL,NULL,'lunas','selesai','2026-10-06 01:46:03','2026-10-05 18:46:03','2026-10-05 19:13:36'),(2,'TRX-6AC452F4CD679','zNZwlpodaLKJTbJJvykzH10uhIix3ibU',2,1,'Budi Tes','qr code','tunai',20000,NULL,NULL,NULL,NULL,NULL,'lunas','selesai','2026-10-06 01:46:28','2026-10-05 18:46:28','2026-10-05 18:46:28'),(3,'TRX-6AC458C899F82','OWMEAozGXy6CXAKAczpSh8jVGBA2zhyX',2,2,'tes','qr code','tunai',30000,NULL,NULL,NULL,NULL,NULL,'lunas','selesai','2026-10-06 02:11:20','2026-10-05 19:11:20','2026-10-05 19:13:05'),(4,'TRX-6AC45989031A0','JljiwSvZPEUkvxyCtWaeHUBD1KPtgkFm',2,3,'tes','qr code','tunai',30000,NULL,NULL,NULL,NULL,NULL,'lunas','selesai','2026-10-06 02:14:33','2026-10-05 19:14:33','2026-10-05 19:28:42'),(5,'TRX-6AC45CACABB94','swT9D8by5SPpXhdwU6rmejHfq45JpuYs',2,3,'tes','qr code','tunai',10000,10000,0,NULL,NULL,NULL,'lunas','selesai','2026-10-06 02:27:56','2026-10-05 19:27:56','2026-10-11 21:06:06'),(6,'TRX-6AC45D84AAC98','oPT4ntjtVmPeRu6jRDzJCBgAiFpb7qBm',2,1,'fahmi','qr code','tunai',10000,10000,0,NULL,NULL,NULL,'lunas','selesai','2026-10-06 02:31:32','2026-10-05 19:31:32','2026-10-11 22:29:58'),(7,'TRX-6AC46072C4AFD','0Z4DCXYaTbi55kW5wIcZzeFlkBx76P8p',2,1,'fahmi','qr code','tunai',10000,NULL,NULL,NULL,NULL,NULL,'batal','dibatalkan','2026-10-06 02:44:02','2026-10-05 19:44:02','2026-10-11 21:36:53'),(11,'TRX-6ACC59726810B','042cz3VWdO5uqgAoXBSL2epsR4IvrB5o',2,3,'tes','qr code','payment_gateway',10000,NULL,NULL,'17b3a3a5-22be-42a6-b7fa-560b9e0cfa7e','https://merchants-app.sbx.midtrans.com/v4/qris/gopay/A120261012035219FaNGsQVvtRID/qr-code','2026-10-12 04:07:19','lunas','selesai','2026-10-12 03:52:18','2026-10-11 20:52:18','2026-10-11 21:06:12'),(12,'TRX-6ACC7AB675A78','pmCpueQc7GZ73dJdPhZSD18qTVIRffWq',2,1,'tes','qr code','payment_gateway',20000,NULL,NULL,'be221f02-174f-4d45-b2c0-cdd99c3dcf04','https://merchants-app.sbx.midtrans.com/v4/qris/gopay/A120261012061416DW6w5KHtziID/qr-code','2026-10-12 06:29:16','batal','dibatalkan','2026-10-12 06:14:14','2026-10-11 23:14:14','2026-10-11 23:15:37'),(13,'TRX-6ACCCD9E6C2BB','qtutJ0HYnMdWCBF8WplFiejgHhMjiqQS',2,2,'tes','qr code','payment_gateway',20000,NULL,NULL,'7238d431-466a-4f85-941d-8c04bc02598b','https://merchants-app.sbx.midtrans.com/v4/qris/gopay/A120261012120800WNvCRCiShmID/qr-code','2026-10-12 12:23:00','lunas','selesai','2026-10-12 12:07:58','2026-10-12 05:07:58','2026-10-12 05:11:48'),(14,'TRX-6ACCCFDC1A642','tBWiMiBFWL3QwUP6EVi1OZc80fnlTGIo',2,1,'tes','qr code','payment_gateway',10000,NULL,NULL,'18eb6c09-931a-4b20-aa8d-cfed3f2f3fab','https://merchants-app.sbx.midtrans.com/v4/qris/gopay/A120261012121735oiMJorKHKfID/qr-code','2026-10-12 12:32:35','lunas','selesai','2026-10-12 12:17:32','2026-10-12 05:17:32','2026-10-12 05:41:36'),(15,'TRX-6ACCD5A6A8DD8','VYZ6kZ1R9elAPoQeSGnidRqknzWl0Dkx',2,1,'nia','qr code','payment_gateway',30000,NULL,NULL,'39331ec8-83b1-4ec6-8d96-7677b8187b4e','https://merchants-app.sbx.midtrans.com/v4/qris/gopay/A120261012124216I9KkgDVYSeID/qr-code','2026-10-12 12:57:16','lunas','selesai','2026-10-12 12:42:14','2026-10-12 05:42:14','2026-10-12 05:47:06'),(16,'TRX-6ACCD6FA37E01','owf3sQ3NVhQ1zhEgLDJS9MV96Zapvn1C',2,1,'wahyu','qr code','payment_gateway',50000,NULL,NULL,'98800205-a899-41ac-ac87-c0d3325b53e4','https://merchants-app.sbx.midtrans.com/v4/qris/gopay/A120261012124756qeupjxbMoiID/qr-code','2026-10-12 13:02:56','lunas','selesai','2026-10-12 12:47:54','2026-10-12 05:47:54','2026-10-12 05:48:12'),(17,'TRX-6ACCDB2020951','nbnqScQ0sz6ODE5WkehoKl0PBPbH57L2',2,1,'bela','qr code','payment_gateway',40000,NULL,NULL,'f0a8da7c-322e-4bf0-9570-587df12f2aca','https://merchants-app.sbx.midtrans.com/v4/qris/gopay/A120261012130538SFXnQhsctSID/qr-code','2026-10-12 13:20:38','lunas','selesai','2026-10-12 13:05:36','2026-10-12 06:05:36','2026-10-12 06:06:35'),(18,'TRX-6ACCDBDF84751','werYPrpAR7GxXJYfQT8KWclJ4pIW8Dgs',2,1,'yahya','qr code','payment_gateway',80000,NULL,NULL,'134d459c-143a-49f8-87e2-d4e0d0015af4','https://merchants-app.sbx.midtrans.com/v4/qris/gopay/A120261012130849qzjbE5b0xNID/qr-code','2026-10-12 13:23:49','lunas','selesai','2026-10-12 13:08:47','2026-10-12 06:08:47','2026-10-12 06:09:33'),(19,'TRX-6ACCEA9F68269','CFw4i7YHQGGnSpzAZx5NhzJ16b8MVHNd',2,1,'melia','qr code','tunai',72000,72000,0,NULL,NULL,NULL,'lunas','selesai','2026-10-12 14:11:43','2026-10-12 07:11:43','2026-10-12 07:17:42'),(20,'TRX-6ACCEC3E84F53','7TFiztynbW1xZZ4M5vDnSb6KbD5VqzsD',NULL,1,'lala','qr code','tunai',49500,NULL,NULL,NULL,NULL,NULL,'pending','menunggu','2026-10-12 14:18:38','2026-10-12 07:18:38','2026-10-12 07:18:38'),(21,'TRX-6ACCECB11F34A','bgQkfD8eECLFm6BHOuM7h49sgCr1wW1J',2,1,'lala','qr code','tunai',29500,29500,0,NULL,NULL,NULL,'lunas','selesai','2026-10-12 14:20:33','2026-10-12 07:20:33','2026-10-12 07:22:24'),(22,'TRX-6ACCECE7AE6C6','vvuDcXQTTvlDOpDUw1DbUBSQfSurTCds',NULL,1,'fian','qr code','payment_gateway',22500,NULL,NULL,'8b314500-3599-4d10-a4e3-a1fa38ccd4a5','https://merchants-app.sbx.midtrans.com/v4/qris/gopay/A120261012142129q2GVdc1ELDID/qr-code','2026-10-12 14:36:29','lunas','diproses','2026-10-12 14:21:27','2026-10-12 07:21:27','2026-10-12 07:22:04'),(23,'TRX-6ACCF0275BDD0','XFNZhLdY8E94oaMH8dB9AOIdNO9rIUZ2',2,NULL,'vera','kasir','tunai',23000,23000,0,NULL,NULL,NULL,'lunas','diproses','2026-10-12 14:35:19','2026-10-12 07:35:19','2026-10-12 07:35:19'),(24,'TRX-6ACCF1111F942','jPDvNdmHd9wmjmNwJvxJdd2Y3g5SA1ZS',2,NULL,'Pelanggan','kasir','tunai',3000,3000,0,NULL,NULL,NULL,'lunas','diproses','2026-10-12 14:39:13','2026-10-12 07:39:13','2026-10-12 07:39:13'),(25,'TRX-6ACCF12689E65','BbpFQl8hq7DKzdrQ4RsvrngZvC0mKoEA',2,NULL,'Pelanggan','kasir','tunai',3500,20000,16500,NULL,NULL,NULL,'lunas','diproses','2026-10-12 14:39:34','2026-10-12 07:39:34','2026-10-12 07:39:34'),(26,'TRX-6ACCF1AC1336C','s8mlRuU1AmbAb1vPtCiRitllFIMIU87Y',2,NULL,'Pelanggan','kasir','tunai',17000,20000,3000,NULL,NULL,NULL,'lunas','diproses','2026-10-12 14:41:48','2026-10-12 07:41:48','2026-10-12 07:41:48');
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

-- Dump completed on 2026-10-13  7:56:51
