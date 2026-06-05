-- MySQL dump 10.13  Distrib 9.4.0, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: ypphmugo_apk
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
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
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
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
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
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
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
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
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
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
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
-- Table structure for table `karyawan`
--

DROP TABLE IF EXISTS `karyawan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `karyawan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `kode_karyawan` varchar(20) NOT NULL,
  `nama_karyawan` varchar(255) NOT NULL,
  `nomor_induk` varchar(50) DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `alamat` text DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `kontak_darurat` varchar(255) DEFAULT NULL,
  `foto_path` varchar(255) DEFAULT NULL,
  `tugas_khusus` varchar(255) DEFAULT NULL,
  `nomor_surat_tugas` varchar(100) DEFAULT NULL,
  `tanggal_surat_tugas` date DEFAULT NULL,
  `tahun_penilaian_id` bigint(20) unsigned DEFAULT NULL,
  `pangkalan_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `karyawan_kode_karyawan_unique` (`kode_karyawan`),
  UNIQUE KEY `karyawan_nomor_induk_unique` (`nomor_induk`),
  KEY `karyawan_tahun_penilaian_id_foreign` (`tahun_penilaian_id`),
  KEY `karyawan_user_id_foreign` (`user_id`),
  KEY `karyawan_pangkalan_id_foreign` (`pangkalan_id`),
  CONSTRAINT `karyawan_pangkalan_id_foreign` FOREIGN KEY (`pangkalan_id`) REFERENCES `pangkalan` (`id`) ON DELETE SET NULL,
  CONSTRAINT `karyawan_tahun_penilaian_id_foreign` FOREIGN KEY (`tahun_penilaian_id`) REFERENCES `tahun_penilaian` (`id`) ON DELETE SET NULL,
  CONSTRAINT `karyawan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `karyawan`
--

LOCK TABLES `karyawan` WRITE;
/*!40000 ALTER TABLE `karyawan` DISABLE KEYS */;
INSERT INTO `karyawan` VALUES (1,NULL,'KRY-0001','AMIRUL MU\'MININ',NULL,'L',0,NULL,NULL,NULL,NULL,'karyawan-foto/1777337948.png','Koperasi',NULL,'2026-04-28',1,9,'2026-02-27 00:30:48','2026-05-28 11:09:06'),(2,NULL,'KRY-0002','ANDISKA ARIA WIJAYA',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'TU MI, Kemasjidan',NULL,NULL,1,3,'2026-02-27 00:30:48','2026-05-28 11:09:01'),(3,NULL,'KRY-0003','APRIANTI',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'TU MI, MDTA',NULL,NULL,1,3,'2026-02-27 00:30:48','2026-05-28 11:09:10'),(4,NULL,'KRY-0004','ARABIAH',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'Koperasi',NULL,NULL,1,9,'2026-02-27 00:30:48','2026-05-28 11:09:13'),(5,NULL,'KRY-0005','DARMA LUTFIA',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'TU RA, MDTA',NULL,NULL,1,4,'2026-02-27 00:30:48','2026-05-31 09:20:32'),(6,NULL,'KRY-0006','DAVID GUSTIA PUTRA',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'Depot, Taman',NULL,NULL,1,11,'2026-02-27 00:30:48','2026-05-28 11:08:49'),(7,NULL,'KRY-0007','DIANA LESTARI',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'MDTA',NULL,NULL,1,6,'2026-02-27 00:30:48','2026-05-28 11:08:47'),(8,NULL,'KRY-0008','ERIN AZKA FUAD SAPUTRA',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'TU MI',NULL,NULL,1,3,'2026-02-27 00:30:48','2026-05-28 11:08:45'),(9,NULL,'KRY-0009','FIDIANA HADIATUL HIKMAH',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'Koperasi',NULL,NULL,1,9,'2026-02-27 00:30:48','2026-05-28 11:08:43'),(10,NULL,'KRY-0010','HAPPY FARIDAH',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'TU RA',NULL,NULL,1,4,'2026-02-27 00:30:48','2026-05-28 11:08:41'),(11,NULL,'KRY-0011','HARI UTAMI',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'Pustaka',NULL,NULL,1,12,'2026-02-27 00:30:48','2026-05-28 11:08:39'),(12,NULL,'KRY-0012','INTAN MAULIDDIAH',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'BEND. MTs',NULL,NULL,1,2,'2026-02-27 00:30:48','2026-05-28 11:08:36'),(13,NULL,'KRY-0013','KHIKMATUL MARIA',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'TU PAUD, MDTA',NULL,NULL,1,5,'2026-02-27 00:30:48','2026-05-28 11:08:34'),(14,NULL,'KRY-0014','LAILI BINTI HABIBAH',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'Pustaka',NULL,NULL,1,12,'2026-02-27 00:30:48','2026-05-28 11:08:32'),(15,NULL,'KRY-0015','LAILIN NASOIHAH',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'Bend. MI',NULL,NULL,1,3,'2026-02-27 00:30:48','2026-05-28 11:08:23'),(16,NULL,'KRY-0016','LILIK ALISTIN',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'Koperasi',NULL,NULL,1,9,'2026-02-27 00:30:48','2026-05-28 11:08:21'),(17,NULL,'KRY-0017','LILY HERAWATI',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'PAUD',NULL,NULL,1,5,'2026-02-27 00:30:48','2026-05-28 11:08:19'),(18,NULL,'KRY-0018','M. FITROH AHSANI',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'Depot, Taman',NULL,NULL,1,11,'2026-02-27 00:30:48','2026-05-28 11:08:17'),(19,NULL,'KRY-0019','M. MUSTAQIM MAHMUDIN',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'Depot, Taman',NULL,NULL,1,11,'2026-02-27 00:30:48','2026-05-28 11:08:15'),(20,NULL,'KRY-0020','MOH. AKMAL SUKMA WARDANI',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'TU MA, Kemasjidan',NULL,NULL,1,1,'2026-02-27 00:30:48','2026-05-28 11:08:13'),(21,NULL,'KRY-0021','MUHAMAD IBNU ATHO\'ILAH',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'TU MA',NULL,NULL,1,1,'2026-02-27 00:30:48','2026-05-28 11:08:12'),(22,NULL,'KRY-0022','NOFI ALFIANI',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'TU MA',NULL,NULL,1,1,'2026-02-27 00:30:48','2026-05-28 11:08:09'),(23,NULL,'KRY-0023','NUR LINDA',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'Pustaka',NULL,NULL,1,12,'2026-02-27 00:30:48','2026-05-28 11:08:07'),(24,NULL,'KRY-0024','NURIL HUDA FERDIANSYAH',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'Koperasi',NULL,NULL,1,9,'2026-02-27 00:30:48','2026-05-28 11:08:03'),(25,NULL,'KRY-0025','PUTRI AGUSTINA',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'PAUD',NULL,NULL,1,5,'2026-02-27 00:30:48','2026-05-28 11:07:56'),(26,NULL,'KRY-0026','RIFKA RIFIA FITRIANI',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'TU MTs',NULL,NULL,1,2,'2026-02-27 00:30:48','2026-05-28 11:07:55'),(27,NULL,'KRY-0027','SITI MUNIROTUS SHOLIHAH',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'Koperasi',NULL,NULL,1,9,'2026-02-27 00:30:48','2026-05-28 11:07:52'),(28,NULL,'KRY-0028','ULVA INAYATUL IFTAKHIYAH',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'TU MA',NULL,NULL,1,1,'2026-02-27 00:30:48','2026-05-28 11:07:26'),(30,4,'KRY-0030','Fathul Mu\'in, S.Pd.',NULL,'L',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,'2026-04-13 00:36:52','2026-04-13 00:36:52'),(32,5,'KRY-0031','AGUS IHSAN','1404182908060001','L',1,'Prt. Jarjani Benteng Barat Sungai Batang',NULL,NULL,NULL,'karyawan-foto/4Mtk97GtxFPkyfT0v1Ufjk7t6w9bLcj1Ph3iOBEr.png','TU MTS','001/ST/2026','2025-07-01',2,2,'2026-05-08 23:13:26','2026-05-08 23:16:04'),(34,6,'KRY-0032','Riza Wafirotun Nisa`','1404185202060001','P',1,'Mugomulyo Sungai Batang',NULL,NULL,NULL,'karyawan-foto/eT3KTkHvFYxAUKrbv81nZtJs8qcwWlc7JoqNfg4M.jpg','TU MA','01/ST2026','2025-07-01',2,1,'2026-05-09 00:10:57','2026-05-09 00:10:57'),(35,7,'KRY-0033','Ahmad Khoirul Amin','0079687472','L',1,'Sungai Undan, 17 Januari 2007',NULL,NULL,NULL,'karyawan-foto/WhOt9NEYpq3KC0YX7QyuyVb5aFT8TwSfyiD75XK6.jpg','GURU PAUD','001/ST/2026','2025-07-01',2,5,'2026-05-27 02:03:15','2026-05-27 02:03:15'),(36,8,'KRY-0034','Ahmad Shofyan Nur Shobah','0065510414','L',1,'Mugomulyo Sungai Batang',NULL,NULL,NULL,'karyawan-foto/iIeeKJw0cduMlCMBYrTDRHBymrrjqy17jRFggSWF.jpg','TU MA','001/ST/2026','2025-07-01',2,1,'2026-05-27 02:08:16','2026-05-27 02:08:16'),(37,9,'KRY-0035','Fadhil Nandila','0061185593','L',1,'Jl. Prt 2 No. 32 RT 001 RW 006 Desa Sungai Undan',NULL,NULL,NULL,'karyawan-foto/YJ8uVDXRlZ3FTzc77c031HIGaOUuF2KeV2XY8Oet.jpg','TU MI','001/ST/2026','2025-07-01',2,3,'2026-05-27 02:13:29','2026-05-27 02:13:29'),(38,10,'KRY-0036','Himmatul Ulya Hs','0075921330','P',1,'Mugomulyo Sungai Batang',NULL,NULL,NULL,'karyawan-foto/JSgGQwbi0FrKshxBLmPliD9mzdhH32rLFhtx4YXo.jpg','kasir koprasi','001/ST/2026','2025-07-01',2,9,'2026-05-27 02:17:03','2026-05-27 02:17:03'),(39,11,'KRY-0037','Indah Mustika Sari','0075118736','P',1,'Parit Cagat Seberang Tembilahan',NULL,NULL,NULL,'karyawan-foto/fQQRiYfcxiVaMR33NQfX0lcw4zLlK1H5QyHTyu3Z.jpg','kasir koprasi','001/ST/2026','2025-07-01',2,9,'2026-05-27 02:59:20','2026-05-27 02:59:20'),(40,12,'KRY-0038','Khozainul Muna','0069237808','P',1,'Mugomulyo Sungai Batang',NULL,NULL,NULL,'karyawan-foto/Siw0eBO6EQt5uFriJIJ7KaLBCjYSkkWhZeibDWFD.jpg','GURU PAUD','001/ST/2026','2025-07-01',2,5,'2026-05-27 03:05:05','2026-05-27 03:05:05'),(41,13,'KRY-0039','Maya Sulis Stiawati','0066519160','P',1,'Mugomulyo Sungai Batang',NULL,NULL,NULL,'karyawan-foto/njgPwqKF284umIdhh0M97wuxe0aSTVbGlTFHe0fG.jpg','TU MA','001/ST/2026','2025-07-01',2,1,'2026-05-27 03:08:30','2026-05-27 03:08:30'),(42,14,'KRY-0040','Moh. Celvin Nugroho','0075411966','L',1,'Mugomulyo Sungai Batang',NULL,NULL,NULL,'karyawan-foto/JhZcNOjTyuZXUN9d0Z4NWRoc82oqq5oHs9C8dEgY.jpg','Penjaga Perpustakaan','001/ST/2026','2025-07-01',2,12,'2026-05-27 03:13:23','2026-05-27 03:13:23'),(43,15,'KRY-0041','Nabila Salwa Zanjabila','0057644664','L',1,'Mugomulyo Sungai Batang',NULL,NULL,NULL,'karyawan-foto/C92snvV4IO55SwvanaBjFnfgHzRHAzUBuTzC8YQN.jpg',NULL,'001/ST/2026','2025-07-01',2,11,'2026-05-27 03:16:46','2026-05-27 03:16:46'),(45,17,'KRY-0043','Syukur Sofian Tahir','3075054819','L',1,'Parit Kaddas II RT 004 RW 002 Desa Benteng Barat',NULL,NULL,NULL,'karyawan-foto/oo3p5gy3CMOqjdhzGFFPZT66WfsMTHV8uTLCCa9f.jpg','kasir koprasi','001/ST/2026','2025-07-01',2,9,'2026-05-27 05:21:34','2026-05-28 10:57:14'),(46,18,'KRY-0044','Umi Masruroh','0079301861','L',1,'JL. KH. Amir RT 002 RW 009 Desa Pulau Kijang',NULL,NULL,NULL,'karyawan-foto/gdhWtkAePMTz7obJXuIU7nPxcBfPOB8qSRxDbfpI.jpg','kasir koprasi','001/ST/2026','2025-07-01',2,9,'2026-05-27 05:23:32','2026-05-27 05:23:32'),(47,19,'KRY-0045','Viko Romadhon','0072020419','L',1,'Parit Sibun RT 001 RW 002 Desa Jaya Bhakti Kec. Enok',NULL,NULL,NULL,'karyawan-foto/1af3sfcjcmbm3UC09OSD2Lw0UUMiHRyL02PRM5qx.jpg',NULL,'001/ST/2026','2025-07-01',2,11,'2026-05-27 05:27:30','2026-05-27 05:27:30'),(48,20,'KRY-0046','Wahyu Pratama','0074762800','L',1,'Prt. Masjid Seberang Sanglar Reteh',NULL,NULL,NULL,'karyawan-foto/x99zSeCiZ2Un8waQ4O7oqwAOyD8ue5amaqLfdcZj.jpg','TU MI PRT MASJID','001/ST/2026','2025-07-01',2,3,'2026-05-27 05:30:04','2026-05-27 05:30:04'),(49,21,'KRY-0047','Yusuf Mahendra','0073123317','L',1,'Prt. Gumpung Seberang Tembilahan Selatan',NULL,NULL,NULL,'karyawan-foto/QN3EFrZhIhDiM34kOlnFzKGuFOWBX7cxj2XjXzod.jpg','kasir koprasi','001/ST/2026','2025-07-01',2,9,'2026-05-27 05:32:27','2026-05-27 05:32:27'),(50,22,'KRY-0048','Zainul Rifa`i','0069876000','L',1,'Sei Bangkar RT 002 RW 001 Desa Kembang Mekar Sari',NULL,NULL,NULL,'karyawan-foto/jCTLsmHcPlghYbVUzzkbZpwLmY8hIJ5GlGvO9pOJ.jpg','GURU PAUD','001/ST/2026','2025-07-01',2,5,'2026-05-27 05:34:07','2026-05-27 05:34:07'),(52,23,'KRY-0049','Nadiatun Na`imah','0042707322','P',1,'Parit Karya Tani Desa Pebenaan Kec. Keritang',NULL,NULL,NULL,'karyawan-foto/7XZG4qq9PPZwfuJnpFI3sDaDhzi59BqKhYV3P3rk.jpg','TU Madrasah Ibtidaiyah','001/ST/2026','2025-07-01',2,3,'2026-05-28 10:43:49','2026-05-28 10:43:49'),(53,24,'KRY-0050','Irma Safitri','0077428250','P',1,'Prt Karya Tani RT 001 RW 001 Desa Pebenaan',NULL,NULL,NULL,'karyawan-foto/lPogXCoNzmRBHJOO1v8dYOBCurgLXHpcc30nlHoQ.jpg','TU Madrasah Ibtidaiyah','001/ST/2026','2025-07-01',2,3,'2026-05-28 10:46:12','2026-05-28 10:46:12'),(54,25,'KRY-0051','Ananda Indah Pangestuti','3067698287','P',1,'Parit Marzuki RT 001 RW 003 Desa Jaya Bhakti',NULL,NULL,NULL,'karyawan-foto/SGHL2f7jAMom3cJsb2x5A9obtq27ArbYDzdAaqLe.jpg','GURU PAUD','001/ST/2026','2025-07-01',2,5,'2026-05-28 10:50:47','2026-05-28 10:50:47'),(55,26,'KRY-0052','Ayu Royyana','0074365002','P',1,'Mugomulyo Sungai Batang',NULL,NULL,NULL,'karyawan-foto/SH9CoDtC40TtLvF6rujbAcjvhXA70xrJc8bWT6xt.jpg','TU MTs','001/ST/2026','2025-07-01',2,2,'2026-05-28 10:53:18','2026-05-28 10:53:18'),(56,27,'KRY-0053','Nashfa Erlina Sahli','0078716398','P',1,'Jl. Lintas RT 009 Desa Lintas Utara Kec. Keritang',NULL,NULL,NULL,'karyawan-foto/uzmLMzeXjAbaA2nCtgy3rS2LhYNg40oHNPCLFzrw.jpg','Guru RA','001/ST/2026','2025-07-01',2,4,'2026-05-28 10:56:07','2026-05-28 10:56:07'),(57,28,'KRY-0054','Neli Syahputri','3081696510','P',1,'Seberang Pulau Kijang RT 002 RW 001',NULL,NULL,NULL,'karyawan-foto/6h8jo7JN0kNmQbo8HjQHT0IbaoHekGMneZBGyUcX.jpg','kasir koprasi','001/ST/2026','2025-07-01',2,9,'2026-05-28 10:59:24','2026-05-28 10:59:24'),(58,29,'KRY-0055','Putri Zahrotul Kholisah','0071498067','P',1,'Jl. Darma Bakti GG. Rino No. 02 Lubuhbaru Barat Kec. Payung Sekaki Pekanbaru',NULL,NULL,NULL,'karyawan-foto/8U5Tnzv6k7Xhu1Gtx9sLHiF1lgXSrpnhaebzj0Zj.jpg','Guru RA','001/ST/2026','2025-07-01',2,4,'2026-05-28 11:01:39','2026-05-28 11:01:39'),(59,30,'KRY-0056','Risna Maila Zulfa','0067983542','P',1,'Mugomulyo Sungai Batang',NULL,NULL,NULL,'karyawan-foto/mYnwqP7L2fRa0Kt9qNfE8wT4EoYadxgcqR7rVapL.jpg','TU MTS','001/ST/2026','2025-07-01',2,2,'2026-05-28 11:03:22','2026-05-28 11:03:22'),(60,31,'KRY-0057','Siti Nur Hasanah','0075511226','P',1,'Mugomulyo Sungai Batang',NULL,NULL,NULL,'karyawan-foto/uU5Z5OXiSnT22gMjogUtAhbCyVHPZI9EqFvIUGpK.jpg','Guru RA','001/ST/2026','2025-07-01',2,13,'2026-05-28 11:05:55','2026-06-04 17:50:41'),(65,32,'KRY-0058','Drs. H. Anas, M.Pd','1404180112660004','L',1,'Mugomulyo',NULL,NULL,NULL,'karyawan-foto/ufO7Q6df9rJlPCv4NQ9wL0pjp3Uo4vo3dcu2RoVE.jpg','Kepala Madrasah','001/ST/2026','2025-07-01',2,2,'2026-05-28 11:26:49','2026-05-28 11:26:49'),(66,33,'KRY-0059','Nina Marlina, S.Pd, M.E','1404184703680002','P',1,'Mugomulyo',NULL,NULL,NULL,'karyawan-foto/2Uk6UpuIjJr8W1Yig9brife9wUA86W1LZUGkrUq6.jpg','Kepala Koperasi','001/ST/2026','2025-07-01',2,9,'2026-05-28 11:34:14','2026-05-28 11:34:14'),(67,34,'KRY-0060','Rahmat Budi Permana, S.Pd., M.E','3206242803860002','L',1,'Mugomulyo',NULL,NULL,NULL,'karyawan-foto/zWuq03CzU0REjEcBi5ZPSUFpJVh9OBmPXvXcohB0.jpg','Kepala Depot Air','001/ST/2026','2025-07-01',2,11,'2026-05-28 11:37:31','2026-05-28 11:37:31'),(71,36,'KRY-0061','MUH. HARUN, S.H.I., M.Sos','1404180607850002','L',1,'Jalan Pelajar, RT 009, RW 002, Desa Mugo Mulyo',NULL,NULL,NULL,NULL,'Kepala Perpustakaan',NULL,'2025-07-01',2,12,'2026-05-29 06:38:26','2026-05-29 06:38:26'),(72,37,'KRY-0062','BANDIYAH','1404204207730001','P',1,'Mugomulyo',NULL,NULL,NULL,'karyawan-foto/gDslALYYsStQCRXdCqi8uSu25aJSuDchIXKBbwN2.jpg','kepala RA','001/ST/2026','2025-07-01',2,4,'2026-06-02 06:16:41','2026-06-02 06:16:41'),(73,38,'KRY-0063','SITI JUARIAH','1404186001760001','P',1,'Mugomulyo',NULL,NULL,NULL,'karyawan-foto/4Q44EsgZj4RcDBHMfLWe1eP8UoUm31LUck63E4X2.jpg','Kepala paud','001/ST/2026','2025-07-01',2,5,'2026-06-02 06:26:35','2026-06-02 06:26:35'),(74,39,'KRY-0064','MISRUN','1404181206640001','L',1,'Mugomulyo',NULL,NULL,NULL,'karyawan-foto/8nZAMNjzgY24PdnkhvjZUpRBkGvQN8X75agj0Cyj.jpg','Pengurus Pondok Pesantren Putra',NULL,'2025-07-01',2,7,'2026-06-02 06:32:08','2026-06-02 06:32:08');
/*!40000 ALTER TABLE `karyawan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `karyawan_pangkalan`
--

DROP TABLE IF EXISTS `karyawan_pangkalan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `karyawan_pangkalan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `karyawan_id` bigint(20) unsigned NOT NULL,
  `pangkalan_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `karyawan_pangkalan_karyawan_id_pangkalan_id_unique` (`karyawan_id`,`pangkalan_id`),
  KEY `karyawan_pangkalan_pangkalan_id_foreign` (`pangkalan_id`),
  CONSTRAINT `karyawan_pangkalan_karyawan_id_foreign` FOREIGN KEY (`karyawan_id`) REFERENCES `karyawan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `karyawan_pangkalan_pangkalan_id_foreign` FOREIGN KEY (`pangkalan_id`) REFERENCES `pangkalan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=151 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `karyawan_pangkalan`
--

LOCK TABLES `karyawan_pangkalan` WRITE;
/*!40000 ALTER TABLE `karyawan_pangkalan` DISABLE KEYS */;
INSERT INTO `karyawan_pangkalan` VALUES (1,20,1,'2026-04-09 18:04:17','2026-04-09 18:04:17'),(2,21,1,'2026-04-09 18:04:17','2026-04-09 18:04:17'),(3,22,1,'2026-04-09 18:04:17','2026-04-09 18:04:17'),(4,28,1,'2026-04-09 18:04:17','2026-04-09 18:04:17'),(5,12,2,'2026-04-09 18:04:17','2026-04-09 18:04:17'),(6,26,2,'2026-04-09 18:04:17','2026-04-09 18:04:17'),(7,2,3,'2026-04-09 18:04:17','2026-04-09 18:04:17'),(8,3,3,'2026-04-09 18:04:17','2026-04-09 18:04:17'),(9,8,3,'2026-04-09 18:04:17','2026-04-09 18:04:17'),(10,15,3,'2026-04-09 18:04:17','2026-04-09 18:04:17'),(11,5,4,'2026-04-09 18:04:17','2026-04-09 18:04:17'),(12,10,4,'2026-04-09 18:04:17','2026-04-09 18:04:17'),(13,13,5,'2026-04-09 18:04:17','2026-04-09 18:04:17'),(14,17,5,'2026-04-09 18:04:17','2026-04-09 18:04:17'),(15,25,5,'2026-04-09 18:04:17','2026-04-09 18:04:17'),(16,7,6,'2026-04-09 18:04:17','2026-04-09 18:04:17'),(17,1,9,'2026-04-09 18:04:17','2026-04-09 18:04:17'),(18,4,9,'2026-04-09 18:04:17','2026-04-09 18:04:17'),(19,9,9,'2026-04-09 18:04:17','2026-04-09 18:04:17'),(20,16,9,'2026-04-09 18:04:17','2026-04-09 18:04:17'),(21,24,9,'2026-04-09 18:04:17','2026-04-09 18:04:17'),(22,27,9,'2026-04-09 18:04:17','2026-04-09 18:04:17'),(23,6,11,'2026-04-09 18:04:17','2026-04-09 18:04:17'),(24,18,11,'2026-04-09 18:04:17','2026-04-09 18:04:17'),(25,19,11,'2026-04-09 18:04:17','2026-04-09 18:04:17'),(26,11,12,'2026-04-09 18:04:17','2026-04-09 18:04:17'),(27,14,12,'2026-04-09 18:04:17','2026-04-09 18:04:17'),(28,23,12,'2026-04-09 18:04:17','2026-04-09 18:04:17'),(29,1,13,'2026-04-09 18:04:35','2026-04-09 18:04:35'),(30,2,13,'2026-04-09 18:04:35','2026-04-09 18:04:35'),(31,3,13,'2026-04-09 18:04:35','2026-04-09 18:04:35'),(32,4,13,'2026-04-09 18:04:35','2026-04-09 18:04:35'),(33,5,13,'2026-04-09 18:04:35','2026-04-09 18:04:35'),(34,6,13,'2026-04-09 18:04:35','2026-04-09 18:04:35'),(35,7,13,'2026-04-09 18:04:35','2026-04-09 18:04:35'),(36,8,13,'2026-04-09 18:04:35','2026-04-09 18:04:35'),(37,9,13,'2026-04-09 18:04:35','2026-04-09 18:04:35'),(38,10,13,'2026-04-09 18:04:35','2026-04-09 18:04:35'),(39,11,13,'2026-04-09 18:04:35','2026-04-09 18:04:35'),(40,12,13,'2026-04-09 18:04:35','2026-04-09 18:04:35'),(41,13,13,'2026-04-09 18:04:35','2026-04-09 18:04:35'),(42,14,13,'2026-04-09 18:04:35','2026-04-09 18:04:35'),(43,15,13,'2026-04-09 18:04:35','2026-04-09 18:04:35'),(44,16,13,'2026-04-09 18:04:35','2026-04-09 18:04:35'),(45,17,13,'2026-04-09 18:04:35','2026-04-09 18:04:35'),(46,18,13,'2026-04-09 18:04:35','2026-04-09 18:04:35'),(47,19,13,'2026-04-09 18:04:35','2026-04-09 18:04:35'),(48,20,13,'2026-04-09 18:04:35','2026-04-09 18:04:35'),(49,21,13,'2026-04-09 18:04:35','2026-04-09 18:04:35'),(50,22,13,'2026-04-09 18:04:35','2026-04-09 18:04:35'),(51,23,13,'2026-04-09 18:04:35','2026-04-09 18:04:35'),(52,24,13,'2026-04-09 18:04:35','2026-04-09 18:04:35'),(53,25,13,'2026-04-09 18:04:35','2026-04-09 18:04:35'),(54,26,13,'2026-04-09 18:04:35','2026-04-09 18:04:35'),(55,27,13,'2026-04-09 18:04:35','2026-04-09 18:04:35'),(56,28,13,'2026-04-09 18:04:35','2026-04-09 18:04:35'),(85,1,10,'2026-04-09 18:05:19','2026-04-09 18:05:19'),(86,2,10,'2026-04-09 18:05:19','2026-04-09 18:05:19'),(87,3,10,'2026-04-09 18:05:19','2026-04-09 18:05:19'),(88,4,10,'2026-04-09 18:05:19','2026-04-09 18:05:19'),(89,5,10,'2026-04-09 18:05:19','2026-04-09 18:05:19'),(90,6,10,'2026-04-09 18:05:19','2026-04-09 18:05:19'),(91,7,10,'2026-04-09 18:05:19','2026-04-09 18:05:19'),(92,8,10,'2026-04-09 18:05:19','2026-04-09 18:05:19'),(93,9,10,'2026-04-09 18:05:19','2026-04-09 18:05:19'),(94,10,10,'2026-04-09 18:05:19','2026-04-09 18:05:19'),(95,11,10,'2026-04-09 18:05:19','2026-04-09 18:05:19'),(96,12,10,'2026-04-09 18:05:19','2026-04-09 18:05:19'),(97,13,10,'2026-04-09 18:05:19','2026-04-09 18:05:19'),(98,14,10,'2026-04-09 18:05:19','2026-04-09 18:05:19'),(99,15,10,'2026-04-09 18:05:19','2026-04-09 18:05:19'),(100,16,10,'2026-04-09 18:05:19','2026-04-09 18:05:19'),(101,17,10,'2026-04-09 18:05:19','2026-04-09 18:05:19'),(102,18,10,'2026-04-09 18:05:19','2026-04-09 18:05:19'),(103,19,10,'2026-04-09 18:05:19','2026-04-09 18:05:19'),(104,20,10,'2026-04-09 18:05:19','2026-04-09 18:05:19'),(105,21,10,'2026-04-09 18:05:19','2026-04-09 18:05:19'),(106,22,10,'2026-04-09 18:05:19','2026-04-09 18:05:19'),(107,23,10,'2026-04-09 18:05:19','2026-04-09 18:05:19'),(108,24,10,'2026-04-09 18:05:19','2026-04-09 18:05:19'),(109,25,10,'2026-04-09 18:05:19','2026-04-09 18:05:19'),(110,26,10,'2026-04-09 18:05:19','2026-04-09 18:05:19'),(111,27,10,'2026-04-09 18:05:19','2026-04-09 18:05:19'),(112,28,10,'2026-04-09 18:05:19','2026-04-09 18:05:19'),(113,30,1,'2026-06-04 17:46:24','2026-06-04 17:46:24'),(114,32,2,'2026-06-04 17:46:24','2026-06-04 17:46:24'),(115,34,1,'2026-06-04 17:46:24','2026-06-04 17:46:24'),(116,35,5,'2026-06-04 17:46:24','2026-06-04 17:46:24'),(117,36,1,'2026-06-04 17:46:24','2026-06-04 17:46:24'),(118,37,3,'2026-06-04 17:46:24','2026-06-04 17:46:24'),(119,38,9,'2026-06-04 17:46:24','2026-06-04 17:46:24'),(120,39,9,'2026-06-04 17:46:24','2026-06-04 17:46:24'),(121,40,5,'2026-06-04 17:46:24','2026-06-04 17:46:24'),(122,41,1,'2026-06-04 17:46:24','2026-06-04 17:46:24'),(123,42,12,'2026-06-04 17:46:24','2026-06-04 17:46:24'),(124,43,11,'2026-06-04 17:46:24','2026-06-04 17:46:24'),(125,45,9,'2026-06-04 17:46:24','2026-06-04 17:46:24'),(126,46,9,'2026-06-04 17:46:24','2026-06-04 17:46:24'),(127,47,11,'2026-06-04 17:46:24','2026-06-04 17:46:24'),(128,48,3,'2026-06-04 17:46:24','2026-06-04 17:46:24'),(129,49,9,'2026-06-04 17:46:24','2026-06-04 17:46:24'),(130,50,5,'2026-06-04 17:46:24','2026-06-04 17:46:24'),(131,52,3,'2026-06-04 17:46:24','2026-06-04 17:46:24'),(132,53,3,'2026-06-04 17:46:24','2026-06-04 17:46:24'),(133,54,5,'2026-06-04 17:46:24','2026-06-04 17:46:24'),(134,55,2,'2026-06-04 17:46:24','2026-06-04 17:46:24'),(135,56,4,'2026-06-04 17:46:24','2026-06-04 17:46:24'),(136,57,9,'2026-06-04 17:46:24','2026-06-04 17:46:24'),(137,58,4,'2026-06-04 17:46:24','2026-06-04 17:46:24'),(138,59,2,'2026-06-04 17:46:24','2026-06-04 17:46:24'),(139,60,4,'2026-06-04 17:46:24','2026-06-04 17:46:24'),(140,65,2,'2026-06-04 17:46:24','2026-06-04 17:46:24'),(141,66,9,'2026-06-04 17:46:24','2026-06-04 17:46:24'),(142,67,11,'2026-06-04 17:46:24','2026-06-04 17:46:24'),(143,71,12,'2026-06-04 17:46:24','2026-06-04 17:46:24'),(144,72,4,'2026-06-04 17:46:24','2026-06-04 17:46:24'),(145,73,5,'2026-06-04 17:46:24','2026-06-04 17:46:24'),(146,74,7,'2026-06-04 17:46:24','2026-06-04 17:46:24'),(147,60,13,'2026-06-04 17:50:41','2026-06-04 17:50:41'),(148,30,13,'2026-06-05 00:51:48','2026-06-05 00:51:48'),(149,65,10,'2026-06-05 00:51:48','2026-06-05 00:51:48');
/*!40000 ALTER TABLE `karyawan_pangkalan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kategori_kinerja`
--

DROP TABLE IF EXISTS `kategori_kinerja`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kategori_kinerja` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kode_kategori` varchar(20) NOT NULL,
  `kategori` varchar(255) NOT NULL,
  `jenis` enum('kinerja','kegiatan') NOT NULL DEFAULT 'kinerja',
  `is_wajib` tinyint(1) NOT NULL DEFAULT 0,
  `bobot` decimal(5,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kategori_kinerja_kode_kategori_unique` (`kode_kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kategori_kinerja`
--

LOCK TABLES `kategori_kinerja` WRITE;
/*!40000 ALTER TABLE `kategori_kinerja` DISABLE KEYS */;
INSERT INTO `kategori_kinerja` VALUES (4,'KTG-004','PRAMUKA','kegiatan',1,33.00,'2026-04-09 16:39:18','2026-04-09 19:26:57'),(5,'KTG-005','PONDOK','kegiatan',1,34.00,'2026-04-09 16:39:28','2026-05-31 08:37:05'),(6,'KTG-006','MASJID/MQ/DINIYAH','kegiatan',1,33.00,'2026-04-09 16:39:49','2026-04-12 17:03:51'),(7,'KTG-007','PUSTAKA','kegiatan',0,0.00,'2026-04-12 23:37:28','2026-04-12 23:37:28'),(8,'KTG-008','MADRASAH','kegiatan',0,0.00,'2026-04-12 23:37:44','2026-04-12 23:37:44'),(9,'KTG-009','KOPERASI/ TPKU/ TIRTA MULYA','kegiatan',0,0.00,'2026-04-12 23:37:56','2026-04-12 23:37:56'),(10,'KTG-010','LOGISTIK','kegiatan',0,0.00,'2026-04-12 23:38:10','2026-04-12 23:38:10'),(14,'KTG-011','Kedisiplinan','kinerja',0,0.00,'2026-04-13 00:48:19','2026-04-13 00:48:19'),(15,'KTG-012','Karakter','kinerja',0,0.00,'2026-04-13 00:48:29','2026-04-13 00:48:29'),(16,'KTG-013','Kompetensi','kinerja',0,0.00,'2026-04-13 00:48:41','2026-04-13 00:48:41');
/*!40000 ALTER TABLE `kategori_kinerja` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kategori_kinerja_kompetensi`
--

DROP TABLE IF EXISTS `kategori_kinerja_kompetensi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kategori_kinerja_kompetensi` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kategori_kinerja_id` bigint(20) unsigned NOT NULL,
  `kompetensi_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_kategori_kompetensi` (`kategori_kinerja_id`,`kompetensi_id`),
  KEY `kategori_kinerja_kompetensi_kompetensi_id_foreign` (`kompetensi_id`),
  CONSTRAINT `kategori_kinerja_kompetensi_kategori_kinerja_id_foreign` FOREIGN KEY (`kategori_kinerja_id`) REFERENCES `kategori_kinerja` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kategori_kinerja_kompetensi_kompetensi_id_foreign` FOREIGN KEY (`kompetensi_id`) REFERENCES `kompetensi` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kategori_kinerja_kompetensi`
--

LOCK TABLES `kategori_kinerja_kompetensi` WRITE;
/*!40000 ALTER TABLE `kategori_kinerja_kompetensi` DISABLE KEYS */;
INSERT INTO `kategori_kinerja_kompetensi` VALUES (22,4,15,'2026-04-12 23:39:43','2026-04-12 23:39:43'),(23,5,15,'2026-04-12 23:40:03','2026-04-12 23:40:03'),(24,6,15,'2026-04-12 23:40:03','2026-04-12 23:40:03'),(25,7,15,'2026-04-12 23:40:03','2026-04-12 23:40:03'),(26,8,15,'2026-04-12 23:40:03','2026-04-12 23:40:03'),(27,9,15,'2026-04-12 23:40:03','2026-04-12 23:40:03'),(28,10,15,'2026-04-12 23:40:03','2026-04-12 23:40:03'),(29,4,16,'2026-04-12 23:40:21','2026-04-12 23:40:21'),(30,4,17,'2026-04-12 23:40:36','2026-04-12 23:40:36'),(31,5,17,'2026-04-12 23:40:36','2026-04-12 23:40:36'),(32,6,17,'2026-04-12 23:40:36','2026-04-12 23:40:36'),(33,7,17,'2026-04-12 23:40:36','2026-04-12 23:40:36'),(34,8,17,'2026-04-12 23:40:36','2026-04-12 23:40:36'),(35,9,17,'2026-04-12 23:40:36','2026-04-12 23:40:36'),(36,10,17,'2026-04-12 23:40:36','2026-04-12 23:40:36'),(37,4,18,'2026-04-12 23:56:51','2026-04-12 23:56:51'),(38,4,19,'2026-04-13 00:03:15','2026-04-13 00:03:15'),(39,5,19,'2026-04-13 00:03:15','2026-04-13 00:03:15'),(40,6,19,'2026-04-13 00:03:15','2026-04-13 00:03:15'),(41,7,19,'2026-04-13 00:03:15','2026-04-13 00:03:15'),(42,8,19,'2026-04-13 00:03:15','2026-04-13 00:03:15'),(43,9,19,'2026-04-13 00:03:15','2026-04-13 00:03:15'),(44,10,19,'2026-04-13 00:03:15','2026-04-13 00:03:15'),(45,14,20,'2026-04-13 01:00:02','2026-04-13 01:00:02'),(46,14,21,'2026-04-13 01:00:13','2026-04-13 01:00:13'),(47,15,22,'2026-04-13 01:00:27','2026-04-13 01:00:27'),(48,15,23,'2026-04-13 01:00:40','2026-04-13 01:00:40'),(49,15,24,'2026-04-13 01:00:51','2026-04-13 01:00:51'),(50,14,25,'2026-04-13 01:01:00','2026-04-13 01:01:00'),(51,16,26,'2026-04-13 01:01:11','2026-04-13 01:01:11'),(52,16,27,'2026-04-13 01:01:21','2026-04-13 01:01:21'),(53,16,28,'2026-04-13 01:01:29','2026-04-13 01:01:29'),(54,16,29,'2026-04-13 01:01:40','2026-04-13 01:01:40'),(55,15,29,'2026-05-31 08:34:45','2026-05-31 08:34:45');
/*!40000 ALTER TABLE `kategori_kinerja_kompetensi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kepala_pangkalan`
--

DROP TABLE IF EXISTS `kepala_pangkalan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kepala_pangkalan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `pangkalan_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kepala_pangkalan_user_id_pangkalan_id_unique` (`user_id`,`pangkalan_id`),
  KEY `kepala_pangkalan_pangkalan_id_foreign` (`pangkalan_id`),
  CONSTRAINT `kepala_pangkalan_pangkalan_id_foreign` FOREIGN KEY (`pangkalan_id`) REFERENCES `pangkalan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kepala_pangkalan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kepala_pangkalan`
--

LOCK TABLES `kepala_pangkalan` WRITE;
/*!40000 ALTER TABLE `kepala_pangkalan` DISABLE KEYS */;
INSERT INTO `kepala_pangkalan` VALUES (1,4,1,'2026-06-04 17:26:47','2026-06-04 17:26:47'),(2,32,2,'2026-06-04 17:27:10','2026-06-04 17:27:10'),(3,37,4,'2026-06-04 17:28:19','2026-06-04 17:28:19'),(4,33,9,'2026-06-04 17:28:35','2026-06-04 17:28:35'),(5,39,7,'2026-06-04 17:28:52','2026-06-04 17:28:52'),(6,38,5,'2026-06-04 17:29:13','2026-06-04 17:29:13'),(7,32,10,'2026-06-04 17:30:05','2026-06-04 17:30:05'),(8,34,11,'2026-06-04 17:32:51','2026-06-04 17:32:51'),(9,36,12,'2026-06-04 17:33:04','2026-06-04 17:33:04'),(10,4,13,'2026-06-04 17:33:17','2026-06-04 17:33:17');
/*!40000 ALTER TABLE `kepala_pangkalan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kompetensi`
--

DROP TABLE IF EXISTS `kompetensi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kompetensi` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kode_kompetensi` varchar(20) NOT NULL,
  `kategori_kinerja_id` bigint(20) unsigned NOT NULL,
  `kompetensi` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kompetensi_kode_kompetensi_unique` (`kode_kompetensi`),
  KEY `kompetensi_kategori_kinerja_id_foreign` (`kategori_kinerja_id`),
  CONSTRAINT `kompetensi_kategori_kinerja_id_foreign` FOREIGN KEY (`kategori_kinerja_id`) REFERENCES `kategori_kinerja` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kompetensi`
--

LOCK TABLES `kompetensi` WRITE;
/*!40000 ALTER TABLE `kompetensi` DISABLE KEYS */;
INSERT INTO `kompetensi` VALUES (15,'KMP-001',4,'Kehadiran','2026-04-12 23:39:43','2026-04-12 23:39:43'),(16,'KMP-002',4,'Kepemimpinan','2026-04-12 23:40:21','2026-04-12 23:40:21'),(17,'KMP-003',4,'Kompetensi','2026-04-12 23:40:36','2026-04-12 23:40:36'),(18,'KMP-004',4,'Karakter','2026-04-12 23:56:51','2026-04-12 23:56:51'),(19,'KMP-005',4,'Kedisiplinan','2026-04-13 00:03:15','2026-04-13 00:03:15'),(20,'KMP-006',14,'Tepat Waktu','2026-04-13 01:00:02','2026-04-13 01:00:02'),(21,'KMP-007',14,'Penggunaan Waktu','2026-04-13 01:00:13','2026-04-13 01:00:13'),(22,'KMP-008',15,'Kepatuhan','2026-04-13 01:00:27','2026-04-13 01:00:27'),(23,'KMP-009',15,'Loyalitas','2026-04-13 01:00:40','2026-04-13 01:00:40'),(24,'KMP-010',15,'Leadership','2026-04-13 01:00:51','2026-04-13 01:00:51'),(25,'KMP-011',14,'Tanggung-jawab','2026-04-13 01:01:00','2026-04-13 01:01:00'),(26,'KMP-012',16,'Inisiatif','2026-04-13 01:01:11','2026-04-13 01:01:11'),(27,'KMP-013',16,'Adaptasi','2026-04-13 01:01:21','2026-04-13 01:01:21'),(28,'KMP-014',16,'Pemecahan Masalah','2026-04-13 01:01:29','2026-04-13 01:01:29'),(29,'KMP-015',15,'Pengambilan Keputusan','2026-04-13 01:01:40','2026-05-31 08:34:45');
/*!40000 ALTER TABLE `kompetensi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_02_26_235503_create_kategori_kinerja_table',1),(5,'2026_02_26_235503_create_tahun_penilaian_table',1),(6,'2026_02_26_235504_create_kompetensi_table',1),(7,'2026_02_26_235504_create_performance_rating_table',1),(8,'2026_02_26_235505_create_karyawan_table',1),(9,'2026_02_27_003552_create_mutasi_table',1),(10,'2026_02_27_003553_create_transaksi_table',1),(11,'2026_02_27_010311_add_user_id_to_karyawan_table',1),(12,'2026_02_27_013207_create_pangkalan_table',1),(13,'2026_02_27_013208_add_pangkalan_tugas_to_karyawan_table',1),(14,'2026_04_09_100001_add_uac_fields_to_users_table',2),(15,'2026_04_09_100002_add_foto_to_karyawan_table',2),(16,'2026_04_09_100003_add_jenis_to_kategori_kinerja_table',2),(17,'2026_04_09_100004_create_setting_lembaga_table',2),(18,'2026_04_09_100005_create_penilaian_locks_table',2),(19,'2026_04_09_100006_create_penilaian_unlock_requests_table',2),(20,'2026_04_10_090001_add_is_active_to_karyawan_table',3),(21,'2026_04_10_090002_add_kepala_level_to_users_table',3),(22,'2026_04_10_090003_add_lokasi_surat_to_setting_lembaga_table',3),(23,'2026_04_10_120001_remove_kepala_level_from_users_table',4),(24,'2026_04_10_130001_create_kategori_kinerja_kompetensi_table',5),(25,'2026_04_10_140001_add_is_wajib_and_karyawan_pangkalan_table',6),(26,'2026_04_10_160001_add_laporan_format_fields_to_setting_lembaga_table',7),(27,'2026_04_10_170001_add_laporan_layout_settings_to_setting_lembaga_table',8),(28,'2026_04_10_180001_add_laporan_column_labels_order_and_scoring_to_setting_lembaga_table',9),(29,'2026_04_13_090001_add_sidebar_fields_to_setting_lembaga_table',10),(30,'2026_04_13_100002_add_profile_fields_to_karyawan_table',11),(31,'2026_04_13_120001_add_kop_contact_fields_to_setting_lembaga_table',12),(32,'2026_04_13_130001_add_sidebar_visibility_fields_to_setting_lembaga_table',13),(33,'2026_04_13_130002_add_is_wajib_to_kategori_kinerja_table',13),(34,'2026_04_13_150003_create_pangkalan_kategori_kinerja_table',14),(35,'2026_04_27_120001_add_laporan_jenis_weight_fields_to_setting_lembaga_table',15),(36,'2026_06_04_100001_create_kepala_pangkalan_table',16),(37,'2026_06_04_100002_create_karyawan_pangkalan_table',17),(38,'2026_06_04_200001_add_contact_fields_to_karyawan_table',18),(39,'2026_06_05_100001_add_is_active_to_pangkalan_table',19),(40,'2026_06_05_100002_add_kepala_user_id_to_pangkalan_table',20),(41,'2026_06_05_100003_add_pangkalan_id_to_transaksi_table',21);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mutasi`
--

DROP TABLE IF EXISTS `mutasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mutasi` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kode_mutasi` varchar(20) NOT NULL,
  `karyawan_id` bigint(20) unsigned NOT NULL,
  `tahun_penilaian_id` bigint(20) unsigned DEFAULT NULL,
  `jenis_mutasi` varchar(255) NOT NULL DEFAULT 'pindah',
  `keterangan` text DEFAULT NULL,
  `tanggal_mutasi` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mutasi_kode_mutasi_unique` (`kode_mutasi`),
  KEY `mutasi_karyawan_id_foreign` (`karyawan_id`),
  KEY `mutasi_tahun_penilaian_id_foreign` (`tahun_penilaian_id`),
  CONSTRAINT `mutasi_karyawan_id_foreign` FOREIGN KEY (`karyawan_id`) REFERENCES `karyawan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mutasi_tahun_penilaian_id_foreign` FOREIGN KEY (`tahun_penilaian_id`) REFERENCES `tahun_penilaian` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mutasi`
--

LOCK TABLES `mutasi` WRITE;
/*!40000 ALTER TABLE `mutasi` DISABLE KEYS */;
/*!40000 ALTER TABLE `mutasi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pangkalan`
--

DROP TABLE IF EXISTS `pangkalan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pangkalan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kode_pangkalan` varchar(20) NOT NULL,
  `nama_pangkalan` varchar(255) NOT NULL,
  `is_wajib` tinyint(1) NOT NULL DEFAULT 0,
  `pimpinan_pos` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `kepala_user_id` bigint(20) unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pangkalan_kode_pangkalan_unique` (`kode_pangkalan`),
  KEY `pangkalan_kepala_user_id_foreign` (`kepala_user_id`),
  CONSTRAINT `pangkalan_kepala_user_id_foreign` FOREIGN KEY (`kepala_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pangkalan`
--

LOCK TABLES `pangkalan` WRITE;
/*!40000 ALTER TABLE `pangkalan` DISABLE KEYS */;
INSERT INTO `pangkalan` VALUES (1,'PNG-001','MA AL-HUDA AL-ILAHIYAH',0,'FATHUL MU\'IN, S.Pd.',NULL,4,1,'2026-02-27 00:30:48','2026-06-04 17:26:47'),(2,'PNG-002','MTs AL-HUDA AL-ILAHIYAH',0,'Drs. H. Anas, M.Pd',NULL,32,1,'2026-02-27 00:30:48','2026-06-04 17:27:10'),(3,'PNG-003','MI AL-HUDA AL-ILAHIYAH',0,'SUJITHO, S.Pd.SD',NULL,NULL,1,'2026-02-27 00:30:48','2026-02-27 00:30:48'),(4,'PNG-004','RA AL-HUDA AL-ILAHIYAH',0,'BANDIYAH',NULL,37,1,'2026-02-27 00:30:48','2026-06-04 17:28:19'),(5,'PNG-005','PAUD DASARI BUDI',0,'SITI JUARIAH',NULL,38,1,'2026-02-27 00:30:48','2026-06-04 17:29:12'),(6,'PNG-006','MDTA AL-HUDA AL-ILAHIYAH',0,'NUR MAKMUROH',NULL,NULL,1,'2026-02-27 00:30:48','2026-02-27 00:30:48'),(7,'PNG-007','PENGURUS PONPES PA',0,'MISRUN',NULL,39,1,'2026-02-27 00:30:48','2026-06-04 17:28:52'),(8,'PNG-008','PENGURUS PONPES PI',0,'NINA MARLINA, S.Pd.',NULL,NULL,1,'2026-02-27 00:30:48','2026-02-27 00:30:48'),(9,'PNG-009','KOPERASI PONTREN',0,'Nina Marlina, S.Pd, M.E',NULL,33,1,'2026-02-27 00:30:48','2026-06-04 17:28:35'),(10,'PNG-010','KEMASJIDAN',1,'Drs. ANAS',NULL,32,1,'2026-02-27 00:30:48','2026-06-04 17:30:05'),(11,'PNG-011','DEPOT AIR, TAMAN, LOGISTIK',0,'Rahmat Budi Permana, S.Pd., M.E',NULL,34,1,'2026-02-27 00:30:48','2026-06-04 17:32:51'),(12,'PNG-012','PERPUSTAKAAN',0,'MUH. HARUN, S.H.I., M.Sos',NULL,36,1,'2026-02-27 00:30:48','2026-06-04 17:33:04'),(13,'PNG-013','PRAMUKA',1,'FATHUL MU\'IN, S.Pd.',NULL,4,1,'2026-02-27 00:30:48','2026-06-04 17:33:17');
/*!40000 ALTER TABLE `pangkalan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pangkalan_kategori_kinerja`
--

DROP TABLE IF EXISTS `pangkalan_kategori_kinerja`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pangkalan_kategori_kinerja` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pangkalan_id` bigint(20) unsigned NOT NULL,
  `kategori_kinerja_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pangkalan_kategori_unique` (`pangkalan_id`,`kategori_kinerja_id`),
  KEY `pangkalan_kategori_kinerja_kategori_kinerja_id_foreign` (`kategori_kinerja_id`),
  CONSTRAINT `pangkalan_kategori_kinerja_kategori_kinerja_id_foreign` FOREIGN KEY (`kategori_kinerja_id`) REFERENCES `kategori_kinerja` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pangkalan_kategori_kinerja_pangkalan_id_foreign` FOREIGN KEY (`pangkalan_id`) REFERENCES `pangkalan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pangkalan_kategori_kinerja`
--

LOCK TABLES `pangkalan_kategori_kinerja` WRITE;
/*!40000 ALTER TABLE `pangkalan_kategori_kinerja` DISABLE KEYS */;
INSERT INTO `pangkalan_kategori_kinerja` VALUES (1,1,14,'2026-04-13 01:19:58','2026-04-13 01:19:58'),(2,1,15,'2026-04-13 01:19:58','2026-04-13 01:19:58'),(3,1,16,'2026-04-13 01:19:58','2026-04-13 01:19:58'),(4,2,14,'2026-04-13 01:20:05','2026-04-13 01:20:05'),(5,2,15,'2026-04-13 01:20:05','2026-04-13 01:20:05'),(6,2,16,'2026-04-13 01:20:05','2026-04-13 01:20:05'),(7,3,14,'2026-04-13 01:20:11','2026-04-13 01:20:11'),(8,3,15,'2026-04-13 01:20:11','2026-04-13 01:20:11'),(9,3,16,'2026-04-13 01:20:11','2026-04-13 01:20:11'),(10,10,14,'2026-04-13 01:20:18','2026-04-13 01:20:18'),(11,10,15,'2026-04-13 01:20:18','2026-04-13 01:20:18'),(12,10,16,'2026-04-13 01:20:18','2026-04-13 01:20:18'),(13,8,14,'2026-04-13 01:20:24','2026-04-13 01:20:24'),(14,8,15,'2026-04-13 01:20:24','2026-04-13 01:20:24'),(15,8,16,'2026-04-13 01:20:24','2026-04-13 01:20:24'),(16,7,14,'2026-04-13 01:20:30','2026-04-13 01:20:30'),(17,7,15,'2026-04-13 01:20:30','2026-04-13 01:20:30'),(18,7,16,'2026-04-13 01:20:30','2026-04-13 01:20:30'),(19,6,14,'2026-04-13 01:20:35','2026-04-13 01:20:35'),(20,6,15,'2026-04-13 01:20:35','2026-04-13 01:20:35'),(21,6,16,'2026-04-13 01:20:35','2026-04-13 01:20:35'),(22,5,14,'2026-04-13 01:20:41','2026-04-13 01:20:41'),(23,5,15,'2026-04-13 01:20:41','2026-04-13 01:20:41'),(24,5,16,'2026-04-13 01:20:41','2026-04-13 01:20:41'),(25,4,14,'2026-04-13 01:20:46','2026-04-13 01:20:46'),(26,4,15,'2026-04-13 01:20:46','2026-04-13 01:20:46'),(27,4,16,'2026-04-13 01:20:46','2026-04-13 01:20:46'),(28,9,14,'2026-04-13 01:20:52','2026-04-13 01:20:52'),(29,9,15,'2026-04-13 01:20:52','2026-04-13 01:20:52'),(30,9,16,'2026-04-13 01:20:52','2026-04-13 01:20:52'),(31,13,14,'2026-04-13 01:20:59','2026-04-13 01:20:59'),(32,13,15,'2026-04-13 01:20:59','2026-04-13 01:20:59'),(33,13,16,'2026-04-13 01:20:59','2026-04-13 01:20:59'),(34,12,14,'2026-04-13 01:21:06','2026-04-13 01:21:06'),(35,12,15,'2026-04-13 01:21:06','2026-04-13 01:21:06'),(36,12,16,'2026-04-13 01:21:06','2026-04-13 01:21:06'),(37,11,14,'2026-04-13 01:21:20','2026-04-13 01:21:20'),(38,11,15,'2026-04-13 01:21:20','2026-04-13 01:21:20'),(39,11,16,'2026-04-13 01:21:20','2026-04-13 01:21:20');
/*!40000 ALTER TABLE `pangkalan_kategori_kinerja` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
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
-- Table structure for table `penilaian_locks`
--

DROP TABLE IF EXISTS `penilaian_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `penilaian_locks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `karyawan_id` bigint(20) unsigned NOT NULL,
  `tahun_penilaian_id` bigint(20) unsigned NOT NULL,
  `is_final_submitted` tinyint(1) NOT NULL DEFAULT 0,
  `is_locked` tinyint(1) NOT NULL DEFAULT 0,
  `submitted_by_user_id` bigint(20) unsigned DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `locked_by_user_id` bigint(20) unsigned DEFAULT NULL,
  `locked_at` timestamp NULL DEFAULT NULL,
  `unlocked_by_user_id` bigint(20) unsigned DEFAULT NULL,
  `unlocked_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `penilaian_locks_karyawan_id_tahun_penilaian_id_unique` (`karyawan_id`,`tahun_penilaian_id`),
  KEY `penilaian_locks_tahun_penilaian_id_foreign` (`tahun_penilaian_id`),
  KEY `penilaian_locks_submitted_by_user_id_foreign` (`submitted_by_user_id`),
  KEY `penilaian_locks_locked_by_user_id_foreign` (`locked_by_user_id`),
  KEY `penilaian_locks_unlocked_by_user_id_foreign` (`unlocked_by_user_id`),
  CONSTRAINT `penilaian_locks_karyawan_id_foreign` FOREIGN KEY (`karyawan_id`) REFERENCES `karyawan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `penilaian_locks_locked_by_user_id_foreign` FOREIGN KEY (`locked_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `penilaian_locks_submitted_by_user_id_foreign` FOREIGN KEY (`submitted_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `penilaian_locks_tahun_penilaian_id_foreign` FOREIGN KEY (`tahun_penilaian_id`) REFERENCES `tahun_penilaian` (`id`) ON DELETE CASCADE,
  CONSTRAINT `penilaian_locks_unlocked_by_user_id_foreign` FOREIGN KEY (`unlocked_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `penilaian_locks`
--

LOCK TABLES `penilaian_locks` WRITE;
/*!40000 ALTER TABLE `penilaian_locks` DISABLE KEYS */;
INSERT INTO `penilaian_locks` VALUES (2,32,2,0,0,NULL,NULL,1,'2026-05-31 08:55:36',1,'2026-05-31 08:56:20','2026-05-31 08:55:13','2026-05-31 08:56:20');
/*!40000 ALTER TABLE `penilaian_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `penilaian_unlock_requests`
--

DROP TABLE IF EXISTS `penilaian_unlock_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `penilaian_unlock_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `karyawan_id` bigint(20) unsigned NOT NULL,
  `tahun_penilaian_id` bigint(20) unsigned NOT NULL,
  `requested_by_user_id` bigint(20) unsigned NOT NULL,
  `alasan` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `reviewed_by_user_id` bigint(20) unsigned DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `catatan_admin` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `penilaian_unlock_requests_karyawan_id_foreign` (`karyawan_id`),
  KEY `penilaian_unlock_requests_tahun_penilaian_id_foreign` (`tahun_penilaian_id`),
  KEY `penilaian_unlock_requests_requested_by_user_id_foreign` (`requested_by_user_id`),
  KEY `penilaian_unlock_requests_reviewed_by_user_id_foreign` (`reviewed_by_user_id`),
  KEY `penilaian_unlock_requests_status_created_at_index` (`status`,`created_at`),
  CONSTRAINT `penilaian_unlock_requests_karyawan_id_foreign` FOREIGN KEY (`karyawan_id`) REFERENCES `karyawan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `penilaian_unlock_requests_requested_by_user_id_foreign` FOREIGN KEY (`requested_by_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `penilaian_unlock_requests_reviewed_by_user_id_foreign` FOREIGN KEY (`reviewed_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `penilaian_unlock_requests_tahun_penilaian_id_foreign` FOREIGN KEY (`tahun_penilaian_id`) REFERENCES `tahun_penilaian` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `penilaian_unlock_requests`
--

LOCK TABLES `penilaian_unlock_requests` WRITE;
/*!40000 ALTER TABLE `penilaian_unlock_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `penilaian_unlock_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `performance_rating`
--

DROP TABLE IF EXISTS `performance_rating`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `performance_rating` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kode_rating` varchar(20) NOT NULL,
  `rating` varchar(255) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `performance_rating_kode_rating_unique` (`kode_rating`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `performance_rating`
--

LOCK TABLES `performance_rating` WRITE;
/*!40000 ALTER TABLE `performance_rating` DISABLE KEYS */;
INSERT INTO `performance_rating` VALUES (1,'RTG-001','A (Sangat Baik)','Nilai 90 ke atas','2026-02-27 00:30:47','2026-02-27 00:30:47'),(2,'RTG-002','B (Baik)','Nilai 80 - 89','2026-02-27 00:30:47','2026-02-27 00:30:47'),(3,'RTG-003','C (Cukup)','Nilai 70 - 79','2026-02-27 00:30:47','2026-02-27 00:30:47'),(4,'RTG-004','D (Kurang)','Nilai 60 - 69','2026-02-27 00:30:47','2026-02-27 00:30:47'),(5,'RTG-005','E (Sangat Kurang)','Nilai di bawah 60','2026-02-27 00:30:48','2026-02-27 00:30:48');
/*!40000 ALTER TABLE `performance_rating` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
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
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `setting_lembaga`
--

DROP TABLE IF EXISTS `setting_lembaga`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `setting_lembaga` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_lembaga` varchar(255) DEFAULT NULL,
  `nama_yayasan` varchar(255) DEFAULT NULL,
  `alamat_lembaga` varchar(255) DEFAULT NULL,
  `telepon_lembaga` varchar(100) DEFAULT NULL,
  `email_lembaga` varchar(150) DEFAULT NULL,
  `website_lembaga` varchar(150) DEFAULT NULL,
  `sidebar_title` varchar(150) NOT NULL DEFAULT 'Website Aplikasi',
  `sidebar_subtitle_1` varchar(255) NOT NULL DEFAULT 'Sistem Manajemen Kinerja Pengabdian',
  `sidebar_subtitle_2` varchar(255) NOT NULL DEFAULT 'Yayasan Pondok Pesantren Al-Huda Mugomulyo',
  `sidebar_show_title` tinyint(1) NOT NULL DEFAULT 1,
  `sidebar_show_subtitle_1` tinyint(1) NOT NULL DEFAULT 1,
  `sidebar_show_subtitle_2` tinyint(1) NOT NULL DEFAULT 1,
  `lokasi_surat` varchar(150) DEFAULT NULL,
  `nama_ketua_yayasan` varchar(255) DEFAULT NULL,
  `nama_ketua_babinlumni` varchar(255) DEFAULT NULL,
  `logo_path` varchar(255) DEFAULT NULL,
  `ttd_ketua_yayasan_path` varchar(255) DEFAULT NULL,
  `ttd_ketua_babinlumni_path` varchar(255) DEFAULT NULL,
  `show_logo` tinyint(1) NOT NULL DEFAULT 1,
  `show_tahun_ajaran` tinyint(1) NOT NULL DEFAULT 1,
  `show_nama_pimpinan` tinyint(1) NOT NULL DEFAULT 1,
  `show_tanda_tangan` tinyint(1) NOT NULL DEFAULT 1,
  `laporan_default_jenis` enum('ringkas','rinci') NOT NULL DEFAULT 'ringkas',
  `laporan_show_no` tinyint(1) NOT NULL DEFAULT 1,
  `laporan_show_kode_karyawan` tinyint(1) NOT NULL DEFAULT 1,
  `laporan_show_pangkalan` tinyint(1) NOT NULL DEFAULT 1,
  `laporan_show_nilai_akhir` tinyint(1) NOT NULL DEFAULT 1,
  `laporan_show_rating` tinyint(1) NOT NULL DEFAULT 1,
  `laporan_show_detail_kompetensi` tinyint(1) NOT NULL DEFAULT 1,
  `laporan_show_bobot_kategori` tinyint(1) NOT NULL DEFAULT 1,
  `laporan_paper_size` varchar(20) NOT NULL DEFAULT 'a4',
  `laporan_orientation` varchar(20) NOT NULL DEFAULT 'portrait',
  `laporan_margin_top` decimal(4,2) NOT NULL DEFAULT 2.54,
  `laporan_margin_right` decimal(4,2) NOT NULL DEFAULT 2.54,
  `laporan_margin_bottom` decimal(4,2) NOT NULL DEFAULT 2.54,
  `laporan_margin_left` decimal(4,2) NOT NULL DEFAULT 2.54,
  `laporan_text_align` varchar(20) NOT NULL DEFAULT 'left',
  `laporan_header_align` varchar(20) NOT NULL DEFAULT 'center',
  `laporan_cell_padding` tinyint(3) unsigned NOT NULL DEFAULT 6,
  `laporan_border_width` decimal(3,1) NOT NULL DEFAULT 1.0,
  `laporan_font_size` tinyint(3) unsigned NOT NULL DEFAULT 11,
  `laporan_title_font_size` tinyint(3) unsigned NOT NULL DEFAULT 16,
  `laporan_col_width_no` smallint(5) unsigned NOT NULL DEFAULT 32,
  `laporan_col_width_kode` smallint(5) unsigned NOT NULL DEFAULT 72,
  `laporan_col_width_nama` smallint(5) unsigned NOT NULL DEFAULT 190,
  `laporan_col_width_pangkalan` smallint(5) unsigned NOT NULL DEFAULT 140,
  `laporan_col_width_nilai` smallint(5) unsigned NOT NULL DEFAULT 88,
  `laporan_col_width_rating` smallint(5) unsigned NOT NULL DEFAULT 108,
  `laporan_column_order` text DEFAULT NULL,
  `laporan_label_no` varchar(100) NOT NULL DEFAULT 'No',
  `laporan_label_kode_karyawan` varchar(100) NOT NULL DEFAULT 'Kode Karyawan',
  `laporan_label_nama_karyawan` varchar(100) NOT NULL DEFAULT 'Nama Karyawan',
  `laporan_label_pangkalan` varchar(100) NOT NULL DEFAULT 'Pangkalan',
  `laporan_label_detail_kompetensi` varchar(100) NOT NULL DEFAULT 'Detail Kompetensi',
  `laporan_label_nilai_akhir` varchar(100) NOT NULL DEFAULT 'Nilai Akhir',
  `laporan_label_rating` varchar(100) NOT NULL DEFAULT 'Rating',
  `laporan_scoring_method` varchar(40) NOT NULL DEFAULT 'weighted_kategori',
  `laporan_bobot_kinerja` decimal(5,2) NOT NULL DEFAULT 70.00,
  `laporan_bobot_kegiatan` decimal(5,2) NOT NULL DEFAULT 30.00,
  `tahun_penilaian_id` bigint(20) unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `setting_lembaga_tahun_penilaian_id_foreign` (`tahun_penilaian_id`),
  CONSTRAINT `setting_lembaga_tahun_penilaian_id_foreign` FOREIGN KEY (`tahun_penilaian_id`) REFERENCES `tahun_penilaian` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `setting_lembaga`
--

LOCK TABLES `setting_lembaga` WRITE;
/*!40000 ALTER TABLE `setting_lembaga` DISABLE KEYS */;
INSERT INTO `setting_lembaga` VALUES (1,'Yayasan Pondok Pesantren Al-Huda Mugomulyo',NULL,'Mugomulyo Kec.Sungai Batang Kab.INHIL','+62 852-2870-4379','ypphmugomulyo@gmail.com','https.//ypphmugomulyo.id','Website Aplikasi','Manajemen Kinerja Pengabdian','YPP. Al-Huda Mugomulyo',1,1,1,'Mugomulyo','Drs. H. AH. MASYHURI SA, M.Pd.I','Muh. Harun S.H.I., M.Sos.','setting-lembaga/rrojgIB1ZBbrq1EqBYSpsh5IJqQxBKvii2qIikcU.png',NULL,NULL,1,1,1,1,'rinci',1,0,1,1,1,1,1,'a4','portrait',2.54,2.54,2.54,2.54,'left','center',6,1.0,11,16,32,72,190,140,88,108,'[\"no\",\"kode_karyawan\",\"nama_karyawan\",\"pangkalan\",\"detail_kompetensi\",\"nilai_akhir\",\"rating\"]','No','Kode Karyawan','Nama Karyawan','Pangkalan','Detail Kompetensi','Nilai Akhir','Rating','weighted_kinerja_kegiatan',70.00,30.00,2,1,'2026-04-09 02:32:10','2026-05-29 07:29:22');
/*!40000 ALTER TABLE `setting_lembaga` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tahun_penilaian`
--

DROP TABLE IF EXISTS `tahun_penilaian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tahun_penilaian` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `periode_penilaian` varchar(255) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tahun_penilaian`
--

LOCK TABLES `tahun_penilaian` WRITE;
/*!40000 ALTER TABLE `tahun_penilaian` DISABLE KEYS */;
INSERT INTO `tahun_penilaian` VALUES (1,'2023/2024','Tahun Pengabdian 2023/2024',0,'2026-02-27 00:30:47','2026-05-08 23:07:29'),(2,'2025/2026','Tahun Pengabdian 2025/2026',1,'2026-04-12 22:59:05','2026-05-08 23:07:29'),(3,'2024/2025','Tahun Pengabdian 2024/2025',0,'2026-04-12 22:59:46','2026-04-12 22:59:54');
/*!40000 ALTER TABLE `tahun_penilaian` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksi`
--

DROP TABLE IF EXISTS `transaksi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transaksi` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kode_transaksi` varchar(20) NOT NULL,
  `karyawan_id` bigint(20) unsigned NOT NULL,
  `pangkalan_id` bigint(20) unsigned DEFAULT NULL,
  `tahun_penilaian_id` bigint(20) unsigned DEFAULT NULL,
  `kompetensi_id` bigint(20) unsigned DEFAULT NULL,
  `performance_rating_id` bigint(20) unsigned DEFAULT NULL,
  `nilai` decimal(5,2) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transaksi_kode_transaksi_unique` (`kode_transaksi`),
  KEY `transaksi_karyawan_id_foreign` (`karyawan_id`),
  KEY `transaksi_tahun_penilaian_id_foreign` (`tahun_penilaian_id`),
  KEY `transaksi_kompetensi_id_foreign` (`kompetensi_id`),
  KEY `transaksi_performance_rating_id_foreign` (`performance_rating_id`),
  KEY `transaksi_pangkalan_id_foreign` (`pangkalan_id`),
  CONSTRAINT `transaksi_karyawan_id_foreign` FOREIGN KEY (`karyawan_id`) REFERENCES `karyawan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transaksi_kompetensi_id_foreign` FOREIGN KEY (`kompetensi_id`) REFERENCES `kompetensi` (`id`) ON DELETE SET NULL,
  CONSTRAINT `transaksi_pangkalan_id_foreign` FOREIGN KEY (`pangkalan_id`) REFERENCES `pangkalan` (`id`) ON DELETE SET NULL,
  CONSTRAINT `transaksi_performance_rating_id_foreign` FOREIGN KEY (`performance_rating_id`) REFERENCES `performance_rating` (`id`) ON DELETE SET NULL,
  CONSTRAINT `transaksi_tahun_penilaian_id_foreign` FOREIGN KEY (`tahun_penilaian_id`) REFERENCES `tahun_penilaian` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksi`
--

LOCK TABLES `transaksi` WRITE;
/*!40000 ALTER TABLE `transaksi` DISABLE KEYS */;
INSERT INTO `transaksi` VALUES (14,'TRX-0029',32,2,2,20,NULL,85.00,NULL,'2026-05-28 11:40:25','2026-05-31 09:00:28'),(15,'TRX-0030',32,2,2,21,NULL,95.00,NULL,'2026-05-28 11:40:25','2026-05-31 09:00:28'),(16,'TRX-0031',32,2,2,25,NULL,90.00,NULL,'2026-05-28 11:40:25','2026-05-31 09:00:28'),(17,'TRX-0032',32,2,2,22,NULL,90.00,NULL,'2026-05-28 11:40:25','2026-05-31 09:00:28'),(18,'TRX-0033',32,2,2,23,NULL,90.00,NULL,'2026-05-28 11:40:25','2026-05-31 09:00:28'),(19,'TRX-0034',32,2,2,24,NULL,90.00,NULL,'2026-05-28 11:40:25','2026-05-31 09:00:28'),(20,'TRX-0036',32,2,2,26,NULL,90.00,NULL,'2026-05-28 11:40:25','2026-05-31 09:00:28'),(21,'TRX-0037',32,2,2,27,NULL,90.00,NULL,'2026-05-28 11:40:25','2026-05-31 09:00:28'),(22,'TRX-0038',32,2,2,28,NULL,90.00,NULL,'2026-05-28 11:40:25','2026-05-31 09:00:28'),(23,'TRX-0035',32,2,2,29,NULL,90.00,NULL,'2026-05-28 11:40:25','2026-05-31 09:00:28'),(24,'TRX-0039',32,2,2,15,NULL,90.00,NULL,'2026-05-28 11:40:25','2026-05-31 09:00:28'),(25,'TRX-0040',32,2,2,16,NULL,90.00,NULL,'2026-05-28 11:40:25','2026-05-31 09:00:28'),(26,'TRX-0041',32,2,2,17,NULL,90.00,NULL,'2026-05-28 11:40:25','2026-05-31 09:00:28'),(27,'TRX-0042',32,2,2,18,NULL,90.00,NULL,'2026-05-28 11:40:25','2026-05-31 09:00:28'),(28,'TRX-0043',32,2,2,19,NULL,90.00,NULL,'2026-05-28 11:40:25','2026-05-31 09:00:28');
/*!40000 ALTER TABLE `transaksi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `pangkalan_id` bigint(20) unsigned DEFAULT NULL,
  `is_kepala` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_pangkalan_id_foreign` (`pangkalan_id`),
  CONSTRAINT `users_pangkalan_id_foreign` FOREIGN KEY (`pangkalan_id`) REFERENCES `pangkalan` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrator','admin','admin@penilaian.com',NULL,'$2y$12$E6WsuGJY3g2tEMWqT4Uvfu1opztcjix9/joamBaTgEk.14FyGUigS','admin',NULL,0,'1QsXjgPo5HORgDE81qGmfGxVad95tG9CI7e1DucMVOfeHMIyJ9NHUPDBM8Rj','2026-02-27 00:30:47','2026-02-27 00:30:47'),(2,'User Penilai','user','user@penilaian.com',NULL,'$2y$12$djdlHO554Gv9j509VQVMwuu6e3Y3U8pB49AoGeyhNcd/6DLLtJUKm','user',NULL,0,NULL,'2026-02-27 00:30:47','2026-04-09 17:19:05'),(4,'Fathul Mu\'in, S.Pd.','fathul','fathul@website.com',NULL,'$2y$12$NIo.z9.qFFKOibbmcEcqku46dZ.FlhnmElF7/UtBnTpFiJywJ/WtS','user',1,1,NULL,'2026-04-13 00:14:08','2026-06-04 17:33:17'),(5,'Agus Ihsan','agus','agusihsann@gmail.com',NULL,'$2y$12$8B6TZegbrTu8Q6Iphl5sBuO4DbwcJkOB36FOJid1ZV9XKD996S1Dy','user',2,0,'DVCHQWCbCnt80ckrEwJaij8RWOdsTXTBlqOEMD2lCh3QwSK7c7l3ZnErdvxc','2026-05-08 22:56:10','2026-05-08 22:56:10'),(6,'Riza Wafirotun Nisa`','riza','rizawafirotunnisa@gmail.com',NULL,'$2y$12$06Z9AJc6kR.jocQPMkTlyeAJiTtszPJiao6A/4HS7cDKAImQE9F8y','user',1,0,'AxIYJpQ25XH8HOCR34PGYgV7PwtdoPkZvfE5oiPP0qWFHoJAHU7QxrHeHcYk','2026-05-09 00:08:00','2026-05-09 00:08:00'),(7,'Ahmad Khoirul Amin','amin','ahmadkhoirulamin65@gmail.com',NULL,'$2y$12$nmHs3fSTJ23fC1hBAwBnP.AA0DrcEhfSMqzPEZX3F2zfQ4k1QlFFS','user',5,0,NULL,'2026-05-27 02:00:24','2026-05-27 02:00:24'),(8,'Ahmad Shofyan Nur Shobah','ahmad','ahmadshofyannurshobah@gmail.com',NULL,'$2y$12$tcmnddlavipuflsPUZ0NSOH4rGsdlecAxjZCPxnBQjL0ozpaj3B.i','user',1,0,'LAzizste4AlTzR2QnqKxpbrdOdSTS6rkOjHDSXqVmkaUQmj2W4xSHsZzTIF8','2026-05-27 02:06:37','2026-05-27 02:06:37'),(9,'Fadhil Nandila','fadhil','fadhilnandila62@gmail.com',NULL,'$2y$12$pvexvYsu/J3SCbfs9gzWTevAsjDEtUjIhrH3G3ORHuMXjoSLh6i5y','user',3,0,NULL,'2026-05-27 02:10:22','2026-05-27 02:10:22'),(10,'Himmatul Ulya Hs','hima','himmatululyahs3@gmail.com',NULL,'$2y$12$AhjiXWYUg3NNaPQxFfBlRum4ehnZ0TwrMaXdCl6tPmCb1j1qoQ3Y6','user',9,0,NULL,'2026-05-27 02:15:06','2026-05-27 02:15:06'),(11,'Indah Mustika Sari','indah','mustikasari17032007@gmail.com',NULL,'$2y$12$1oA9ZGrUmTt379CBZpQubeTjT61M8ElgzyoO4ILC/oeOdWqiuoZQa','user',NULL,0,NULL,'2026-05-27 02:57:55','2026-05-27 02:57:55'),(12,'Khozainul Muna','muna','Khozainulmuna026@gmail.com',NULL,'$2y$12$rzZua93UJjqWYOeNKCITz.2Dte4OBhrvgKxj1tpNg80W8UUIQiJW6','user',5,0,NULL,'2026-05-27 03:01:15','2026-05-27 03:01:15'),(13,'Maya Sulis Stiawati','maya','mayasulisstiawati@gmail.com',NULL,'$2y$12$c2lHn3JXZB05dNSjxSfyze9AxyzpI5JM3fpeIGFttO3CjrGDswZ1m','user',1,0,NULL,'2026-05-27 03:07:25','2026-05-27 03:07:25'),(14,'Moh. Celvin Nugroho','celvin','muhammadcelvinnugroho.ypph.mts@gmail.com',NULL,'$2y$12$eY9IpdOY0bFndGTMs1LH8.PqOIuT/HNE4kBqRmYww3hgnx9V/zkr2','user',12,0,NULL,'2026-05-27 03:10:03','2026-05-27 03:10:03'),(15,'Nabila Salwa Zanjabila','nabil','nabilslawazanzabila@gmail.com',NULL,'$2y$12$dQlkTyvq6iZMsYCjJd5jC.dMc/R5m0FMVDRLiGFQrFhz3Y228TQ1e','user',11,0,NULL,'2026-05-27 03:15:18','2026-05-27 03:15:18'),(17,'Syakur Sofian Tahir','syukur','syukurshofiantahir@gmail.com',NULL,'$2y$12$vI8Lq6AlV1rY8CYvC04ntOP8jLVsw1wwQbFrD0kkeerV5D37wr.BK','user',9,0,NULL,'2026-05-27 05:20:04','2026-05-27 05:20:04'),(18,'Umi Masruroh','umi','umimasruroh1607@gmail.com',NULL,'$2y$12$DDpg6nw3AzplnVqRDNfZK.1he3Z.C3eJrbF7hybETJ6HC8LgwjjOW','user',9,0,NULL,'2026-05-27 05:22:35','2026-05-27 05:22:35'),(19,'Viko Romadhon','viko','vikoromadhon18@gmail.com',NULL,'$2y$12$DgW0OFFIgzDGO95.YfQgTeP6XcWk9aSjD.4.57AoUH2plLRezuQ56','user',11,0,NULL,'2026-05-27 05:26:23','2026-05-27 05:26:23'),(20,'Wahyu Pratama','wahyu','wahyupratama@gmail.com',NULL,'$2y$12$TIbKdIk2aABjh8G9hM1e1OBU4Hl5efpYsbgooK1ePZ0fkPJ95Bfk2','user',3,0,NULL,'2026-05-27 05:28:54','2026-05-27 05:28:54'),(21,'Yusuf Mahendra','yusuf','yusufmahendra866@gmail.com',NULL,'$2y$12$bs2LtMclpLwLvj62gN/M/OsIIFR0yrUGcfGuMw4QAqxO6nOln.vpC','user',9,0,NULL,'2026-05-27 05:31:30','2026-05-27 05:31:30'),(22,'Zainul Rifa`i','zainul','zainulrifai@gmail.com',NULL,'$2y$12$9Xzu1eVhs.Ge9quKSLiZB.LuWrC4p6lUSHK4RMoRecKWKhx7YKV/C','user',5,0,NULL,'2026-05-27 05:33:22','2026-05-27 05:33:22'),(23,'Nadiatun Na`imah','nadia','nadiatunnaimah10@gmail.com',NULL,'$2y$12$ummYHuaKRkaymqN3B6qy3ehobgmNRgu45A.P.eO1yCPUWWHGqCAFC','user',3,0,NULL,'2026-05-28 10:42:59','2026-05-28 10:42:59'),(24,'Irma Safitri','irma','irmasaftiri@gmail.com',NULL,'$2y$12$X8CKog/uiBp2gEAUvBhHCeEse5AY7s3wlWrkeE7rzb3d3.6W8rliu','user',3,0,NULL,'2026-05-28 10:45:21','2026-05-28 10:45:21'),(25,'Ananda Indah Pangestuti','nanda','anandaindahpangastuti@gmail.com',NULL,'$2y$12$IOXoCxP8BTuwZl34uezdVeB8dG.AUbx/pNr.ErEnMKPwszTGGHQki','user',5,0,NULL,'2026-05-28 10:49:58','2026-05-28 10:49:58'),(26,'Ayu Royyana','ayu','ayuroyyana8@gmail.com',NULL,'$2y$12$2ZUjUoEkttinIsszRZncau/M6qeFvMswV0v5Sdip7UdB6EhDNRQKm','user',2,0,NULL,'2026-05-28 10:52:29','2026-05-28 10:52:29'),(27,'Nashfa Erlina Sahli','nashfa','nashfaerlianasahli@gmail.com',NULL,'$2y$12$nmSbsCPIZrW1PnCOg5nNduF11DGon213MtQSxpjjPUH4UmRGgesO2','user',4,0,NULL,'2026-05-28 10:55:26','2026-05-28 10:55:26'),(28,'Neli Syahputri','neli','nelysyaputri34@gmail.com',NULL,'$2y$12$dCbp5U1I4LRV1Em9wuXwV.DJzTY6gi1o3SpeLK0MNSYnrV6M4QFJS','user',9,0,NULL,'2026-05-28 10:58:32','2026-05-28 10:58:32'),(29,'Putri Zahrotul Kholisah','putri','putrizahrotulkholisah@gmail.com',NULL,'$2y$12$glbMdZX6F6rSXzg4YRiTW.XmvLxmxh7BYLKtAiCmAv72TrpRhBv26','user',4,0,NULL,'2026-05-28 11:00:23','2026-05-28 11:00:23'),(30,'Risna Maila Zulfa','risna','risnamailazulfa@gmail.com',NULL,'$2y$12$nTLs9CpLLwcidZsT2fBOIepu7PaZaH8i0SN14h1eMeQ75FAks6QPG','user',2,0,NULL,'2026-05-28 11:02:41','2026-05-28 11:02:41'),(31,'Siti Nur Hasanah','siti','sitinurhasanah0114@gmail.com',NULL,'$2y$12$thohlcPftLxXKQYtKyoUSuJMzAD9OJSd1wY7ewvN5tkG777IEQFkK','user',4,0,NULL,'2026-05-28 11:05:08','2026-05-28 11:05:08'),(32,'Drs. H. Anas, M.Pd','anas','anasmts112@gmail.com',NULL,'$2y$12$dzt6megxyyxgcdOKq6MAGeXCIe2Hf/Nl/ZHOpsA65gkOqz0K26NMq','user',2,1,NULL,'2026-05-28 11:18:08','2026-06-04 17:30:05'),(33,'Nina Marlina, S.Pd, M.E','nina','nienamarlina470@gmail.com',NULL,'$2y$12$EHqDWbOg6JCj2ru2lTP9neXaBFqSWmdZ0BBCOjdGOFlT2YM4P/5me','user',9,1,NULL,'2026-05-28 11:33:09','2026-06-04 17:31:10'),(34,'Rahmat Budi Permana, S.Pd., M.E','rahmat','rbpermana@gmail.com',NULL,'$2y$12$c8CZLcRMv6Zty4GdS1To3OBg.v56tt8Tcp6P5JozaZvu8BPn.6g9O','user',11,1,NULL,'2026-05-28 11:35:31','2026-06-04 17:32:51'),(35,'MUH. HARUN, S.H.I., M.Sos','babinlumni','harun.syirahay@gmail.com',NULL,'$2y$12$1pRW9gQgu52.Kl8d0Qekq.qakZHWeVmLKn7wZ.CEtlM/F8CZ22XXW','admin',NULL,0,'DsapmOejEaQBgzF2TOOaw4brzoSLv3TPP1mifnzoiZDhcmkFXTneBXtJeQPG','2026-05-28 21:45:20','2026-05-28 21:45:20'),(36,'MUH. HARUN, S.H.I., M.Sos','harun','harun.ysk4@gmail.com',NULL,'$2y$12$rww1XoZHEIWgihZl8zgof.0NnQ4JOfWKQ1KjSMB8D9KzhF/sQO/Sa','user',12,1,NULL,'2026-05-28 21:49:53','2026-06-04 17:33:04'),(37,'BANDIYAH','bandiyah','bandiyah@gmail.com',NULL,'$2y$12$29AO4gP1.imkZjkMHmXfk.BKp2XnlL9.2TZVdzQ1MX4At7t4BISsi','user',4,1,NULL,'2026-06-02 06:14:52','2026-06-04 17:28:19'),(38,'SITI JUARIAH','juariyah','juariyah@gmail.com',NULL,'$2y$12$5OTuiWsoiMMSDh2I4.ZkCukLNfaRovXaP9aoBkfwMr/lwP7yP0eXq','user',5,1,NULL,'2026-06-02 06:19:16','2026-06-04 17:29:12'),(39,'MISRUN','misrun','misrun@gmail.com',NULL,'$2y$12$/Q2Qua5OAqzqvmQAJ6xcJOxEWwZE.i9hP/cisDPyqprEUm5pvKy7e','user',7,1,NULL,'2026-06-02 06:30:33','2026-06-04 17:28:52');
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

-- Dump completed on 2026-06-05  9:26:09
