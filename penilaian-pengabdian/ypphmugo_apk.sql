/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.16-MariaDB, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: ypphmugo_apk
-- ------------------------------------------------------
-- Server version	10.11.16-MariaDB-cll-lve-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
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
/*!40101 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = utf8mb4 */;
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
  KEY `idx_karyawan_active` (`is_active`),
  KEY `idx_karyawan_nama` (`nama_karyawan`),
  KEY `idx_karyawan_user` (`user_id`),
  KEY `idx_karyawan_tahun` (`tahun_penilaian_id`),
  CONSTRAINT `karyawan_pangkalan_id_foreign` FOREIGN KEY (`pangkalan_id`) REFERENCES `pangkalan` (`id`) ON DELETE SET NULL,
  CONSTRAINT `karyawan_tahun_penilaian_id_foreign` FOREIGN KEY (`tahun_penilaian_id`) REFERENCES `tahun_penilaian` (`id`) ON DELETE SET NULL,
  CONSTRAINT `karyawan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `karyawan`
--

LOCK TABLES `karyawan` WRITE;
/*!40000 ALTER TABLE `karyawan` DISABLE KEYS */;
INSERT INTO `karyawan` VALUES
(1,NULL,'KRY-0001','AMIRUL MU\'MININ',NULL,'L',0,NULL,NULL,NULL,NULL,'karyawan-foto/1777337948.png','Koperasi',NULL,'2026-04-28',1,9,'2026-02-27 00:30:48','2026-05-28 11:09:06'),
(2,NULL,'KRY-0002','ANDISKA ARIA WIJAYA',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'TU MI, Kemasjidan',NULL,NULL,1,3,'2026-02-27 00:30:48','2026-06-09 07:15:08'),
(3,NULL,'KRY-0003','APRIANTI',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'TU MI, MDTA',NULL,NULL,1,3,'2026-02-27 00:30:48','2026-05-28 11:09:10'),
(4,NULL,'KRY-0004','ARABIAH',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'Koperasi',NULL,NULL,1,9,'2026-02-27 00:30:48','2026-05-28 11:09:13'),
(5,NULL,'KRY-0005','DARMA LUTFIA',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'TU RA, MDTA',NULL,NULL,1,4,'2026-02-27 00:30:48','2026-05-31 09:20:32'),
(6,NULL,'KRY-0006','DAVID GUSTIA PUTRA',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'Depot, Taman',NULL,NULL,1,11,'2026-02-27 00:30:48','2026-05-28 11:08:49'),
(7,NULL,'KRY-0007','DIANA LESTARI',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'MDTA',NULL,NULL,1,6,'2026-02-27 00:30:48','2026-05-28 11:08:47'),
(8,NULL,'KRY-0008','ERIN AZKA FUAD SAPUTRA',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'TU MI',NULL,NULL,1,3,'2026-02-27 00:30:48','2026-05-28 11:08:45'),
(9,NULL,'KRY-0009','FIDIANA HADIATUL HIKMAH',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'Koperasi',NULL,NULL,1,9,'2026-02-27 00:30:48','2026-05-28 11:08:43'),
(10,NULL,'KRY-0010','HAPPY FARIDAH',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'TU RA',NULL,NULL,1,4,'2026-02-27 00:30:48','2026-05-28 11:08:41'),
(11,NULL,'KRY-0011','HARI UTAMI',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'Pustaka',NULL,NULL,1,12,'2026-02-27 00:30:48','2026-05-28 11:08:39'),
(12,NULL,'KRY-0012','INTAN MAULIDDIAH',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'BEND. MTs',NULL,NULL,1,2,'2026-02-27 00:30:48','2026-05-28 11:08:36'),
(13,NULL,'KRY-0013','KHIKMATUL MARIA',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'TU PAUD, MDTA',NULL,NULL,1,5,'2026-02-27 00:30:48','2026-05-28 11:08:34'),
(14,NULL,'KRY-0014','LAILI BINTI HABIBAH',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'Pustaka',NULL,NULL,1,12,'2026-02-27 00:30:48','2026-05-28 11:08:32'),
(15,NULL,'KRY-0015','LAILIN NASOIHAH',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'Bend. MI',NULL,NULL,1,3,'2026-02-27 00:30:48','2026-05-28 11:08:23'),
(16,NULL,'KRY-0016','LILIK ALISTIN',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'Koperasi',NULL,NULL,1,9,'2026-02-27 00:30:48','2026-05-28 11:08:21'),
(17,NULL,'KRY-0017','LILY HERAWATI',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'PAUD',NULL,NULL,1,5,'2026-02-27 00:30:48','2026-05-28 11:08:19'),
(18,NULL,'KRY-0018','M. FITROH AHSANI',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'Depot, Taman',NULL,NULL,1,11,'2026-02-27 00:30:48','2026-05-28 11:08:17'),
(19,NULL,'KRY-0019','M. MUSTAQIM MAHMUDIN',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'Depot, Taman',NULL,NULL,1,11,'2026-02-27 00:30:48','2026-05-28 11:08:15'),
(20,NULL,'KRY-0020','MOH. AKMAL SUKMA WARDANI',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'TU MA, Kemasjidan',NULL,NULL,1,1,'2026-02-27 00:30:48','2026-05-28 11:08:13'),
(21,NULL,'KRY-0021','MUHAMAD IBNU ATHO\'ILAH',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'TU MA',NULL,NULL,1,1,'2026-02-27 00:30:48','2026-05-28 11:08:12'),
(22,NULL,'KRY-0022','NOFI ALFIANI',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'TU MA',NULL,NULL,1,1,'2026-02-27 00:30:48','2026-05-28 11:08:09'),
(23,NULL,'KRY-0023','NUR LINDA',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'Pustaka',NULL,NULL,1,12,'2026-02-27 00:30:48','2026-05-28 11:08:07'),
(24,NULL,'KRY-0024','NURIL HUDA FERDIANSYAH',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'Koperasi',NULL,NULL,1,9,'2026-02-27 00:30:48','2026-05-28 11:08:03'),
(25,NULL,'KRY-0025','PUTRI AGUSTINA',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'PAUD',NULL,NULL,1,5,'2026-02-27 00:30:48','2026-05-28 11:07:56'),
(26,NULL,'KRY-0026','RIFKA RIFIA FITRIANI',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'TU MTs',NULL,NULL,1,2,'2026-02-27 00:30:48','2026-05-28 11:07:55'),
(27,NULL,'KRY-0027','SITI MUNIROTUS SHOLIHAH',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'Koperasi',NULL,NULL,1,9,'2026-02-27 00:30:48','2026-05-28 11:07:52'),
(28,NULL,'KRY-0028','ULVA INAYATUL IFTAKHIYAH',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'TU MA',NULL,NULL,1,1,'2026-02-27 00:30:48','2026-05-28 11:07:26'),
(30,4,'KRY-0030','Fathul Mu\'in, S.Pd.','1404200607730001','L',1,NULL,'fathulmuin@gmail.com','085355392211',NULL,'karyawan-foto/1780750103.png','Kepala Madrasah Aliyah','001/ST/2026','2025-07-01',1,1,'2026-04-13 00:36:52','2026-06-06 05:48:23'),
(32,5,'KRY-0031','AGUS IHSAN','1404182908060001','L',1,'Prt. Jarjani Benteng Barat Sungai Batang',NULL,NULL,NULL,'karyawan-foto/4Mtk97GtxFPkyfT0v1Ufjk7t6w9bLcj1Ph3iOBEr.png','TU MTS','001/ST/2026','2025-07-01',2,2,'2026-05-08 23:13:26','2026-05-08 23:16:04'),
(34,6,'KRY-0032','Riza Wafirotun Nisa`','1404185202060001','P',1,'Mugomulyo Sungai Batang',NULL,NULL,NULL,'karyawan-foto/eT3KTkHvFYxAUKrbv81nZtJs8qcwWlc7JoqNfg4M.jpg','TU MA','01/ST2026','2025-07-01',2,1,'2026-05-09 00:10:57','2026-05-09 00:10:57'),
(35,7,'KRY-0033','Ahmad Khoirul Amin','0079687472','L',1,'Sungai Undan, 17 Januari 2007',NULL,NULL,NULL,'karyawan-foto/WhOt9NEYpq3KC0YX7QyuyVb5aFT8TwSfyiD75XK6.jpg','GURU PAUD','001/ST/2026','2025-07-01',2,10,'2026-05-27 02:03:15','2026-06-09 07:25:36'),
(36,8,'KRY-0034','Ahmad Shofyan Nur Shobah','0065510414','L',1,'Mugomulyo Sungai Batang',NULL,NULL,NULL,'karyawan-foto/iIeeKJw0cduMlCMBYrTDRHBymrrjqy17jRFggSWF.jpg','TU MA','001/ST/2026','2025-07-01',2,1,'2026-05-27 02:08:16','2026-05-27 02:08:16'),
(37,9,'KRY-0035','Fadhil Nandila','0061185593','L',1,'Jl. Prt 2 No. 32 RT 001 RW 006 Desa Sungai Undan',NULL,NULL,NULL,'karyawan-foto/YJ8uVDXRlZ3FTzc77c031HIGaOUuF2KeV2XY8Oet.jpg','TU MI','001/ST/2026','2025-07-01',2,3,'2026-05-27 02:13:29','2026-05-27 02:13:29'),
(38,10,'KRY-0036','Himmatul Ulya Hs','0075921330','P',1,'Mugomulyo Sungai Batang',NULL,NULL,NULL,'karyawan-foto/JSgGQwbi0FrKshxBLmPliD9mzdhH32rLFhtx4YXo.jpg','kasir koprasi','001/ST/2026','2025-07-01',2,9,'2026-05-27 02:17:03','2026-05-27 02:17:03'),
(39,11,'KRY-0037','Indah Mustika Sari','0075118736','P',1,'Parit Cagat Seberang Tembilahan',NULL,NULL,NULL,'karyawan-foto/fQQRiYfcxiVaMR33NQfX0lcw4zLlK1H5QyHTyu3Z.jpg','kasir koprasi','001/ST/2026','2025-07-01',2,9,'2026-05-27 02:59:20','2026-05-27 02:59:20'),
(40,12,'KRY-0038','Khozainul Muna','0069237808','P',1,'Mugomulyo Sungai Batang',NULL,NULL,NULL,'karyawan-foto/Siw0eBO6EQt5uFriJIJ7KaLBCjYSkkWhZeibDWFD.jpg','GURU PAUD','001/ST/2026','2025-07-01',2,6,'2026-05-27 03:05:05','2026-06-08 08:34:43'),
(41,13,'KRY-0039','Maya Sulis Stiawati','0066519160','P',1,'Mugomulyo Sungai Batang',NULL,NULL,NULL,'karyawan-foto/njgPwqKF284umIdhh0M97wuxe0aSTVbGlTFHe0fG.jpg','TU MA','001/ST/2026','2025-07-01',2,1,'2026-05-27 03:08:30','2026-05-27 03:08:30'),
(42,14,'KRY-0040','Moh. Celvin Nugroho','0075411966','L',1,'Mugomulyo Sungai Batang',NULL,NULL,NULL,'karyawan-foto/JhZcNOjTyuZXUN9d0Z4NWRoc82oqq5oHs9C8dEgY.jpg','Penjaga Perpustakaan','001/ST/2026','2025-07-01',2,7,'2026-05-27 03:13:23','2026-06-09 07:11:05'),
(43,15,'KRY-0041','Nabila Salwa Zanjabila','0057644664','L',1,'Mugomulyo Sungai Batang',NULL,NULL,NULL,'karyawan-foto/C92snvV4IO55SwvanaBjFnfgHzRHAzUBuTzC8YQN.jpg','Depot Air','001/ST/2026','2025-07-01',2,11,'2026-05-27 03:16:46','2026-06-09 07:08:55'),
(45,17,'KRY-0043','Syukur Sofian Tahir','3075054819','L',1,'Parit Kaddas II RT 004 RW 002 Desa Benteng Barat',NULL,NULL,NULL,'karyawan-foto/oo3p5gy3CMOqjdhzGFFPZT66WfsMTHV8uTLCCa9f.jpg','kasir koprasi','001/ST/2026','2025-07-01',2,10,'2026-05-27 05:21:34','2026-06-05 08:09:56'),
(46,18,'KRY-0044','Umi Masruroh','0079301861','L',1,'JL. KH. Amir RT 002 RW 009 Desa Pulau Kijang',NULL,NULL,NULL,'karyawan-foto/gdhWtkAePMTz7obJXuIU7nPxcBfPOB8qSRxDbfpI.jpg','kasir koprasi','001/ST/2026','2025-07-01',2,9,'2026-05-27 05:23:32','2026-05-27 05:23:32'),
(47,19,'KRY-0045','Viko Romadhon','0072020419','L',1,'Parit Sibun RT 001 RW 002 Desa Jaya Bhakti Kec. Enok',NULL,NULL,NULL,'karyawan-foto/1af3sfcjcmbm3UC09OSD2Lw0UUMiHRyL02PRM5qx.jpg','Depot Air','001/ST/2026','2025-07-01',2,11,'2026-05-27 05:27:30','2026-06-09 07:08:08'),
(48,20,'KRY-0046','Wahyu Pratama','0074762800','L',1,'Prt. Masjid Seberang Sanglar Reteh',NULL,NULL,NULL,'karyawan-foto/x99zSeCiZ2Un8waQ4O7oqwAOyD8ue5amaqLfdcZj.jpg','STAF GURU MIS AL-IRSYAD PARIT MASJID','001/ST/2026','2025-07-01',2,NULL,'2026-05-27 05:30:04','2026-06-10 02:59:31'),
(49,21,'KRY-0047','Yusuf Mahendra','0073123317','L',1,'Prt. Gumpung Seberang Tembilahan Selatan',NULL,NULL,NULL,'karyawan-foto/QN3EFrZhIhDiM34kOlnFzKGuFOWBX7cxj2XjXzod.jpg','kasir koprasi','001/ST/2026','2025-07-01',2,9,'2026-05-27 05:32:27','2026-05-27 05:32:27'),
(50,22,'KRY-0048','Zainul Rifa`i','0069876000','L',1,'Sei Bangkar RT 002 RW 001 Desa Kembang Mekar Sari',NULL,NULL,NULL,'karyawan-foto/jCTLsmHcPlghYbVUzzkbZpwLmY8hIJ5GlGvO9pOJ.jpg','GURU PAUD','001/ST/2026','2025-07-01',2,5,'2026-05-27 05:34:07','2026-05-27 05:34:07'),
(52,23,'KRY-0049','Nadiatun Na`imah','0042707322','P',1,'Parit Karya Tani Desa Pebenaan Kec. Keritang',NULL,NULL,NULL,'karyawan-foto/7XZG4qq9PPZwfuJnpFI3sDaDhzi59BqKhYV3P3rk.jpg','TU Madrasah Ibtidaiyah','001/ST/2026','2025-07-01',2,6,'2026-05-28 10:43:49','2026-06-06 06:25:32'),
(53,24,'KRY-0050','Irma Safitri','0077428250','P',1,'Prt Karya Tani RT 001 RW 001 Desa Pebenaan',NULL,NULL,NULL,'karyawan-foto/lPogXCoNzmRBHJOO1v8dYOBCurgLXHpcc30nlHoQ.jpg','TU Madrasah Ibtidaiyah','001/ST/2026','2025-07-01',2,6,'2026-05-28 10:46:12','2026-06-06 06:39:56'),
(54,25,'KRY-0051','Ananda Indah Pangestuti','3067698287','P',1,'Parit Marzuki RT 001 RW 003 Desa Jaya Bhakti',NULL,NULL,NULL,'karyawan-foto/SGHL2f7jAMom3cJsb2x5A9obtq27ArbYDzdAaqLe.jpg','GURU PAUD','001/ST/2026','2025-07-01',2,5,'2026-05-28 10:50:47','2026-05-28 10:50:47'),
(55,26,'KRY-0052','Ayu Royyana','0074365002','P',1,'Mugomulyo Sungai Batang',NULL,NULL,NULL,'karyawan-foto/SH9CoDtC40TtLvF6rujbAcjvhXA70xrJc8bWT6xt.jpg','TU MTs','001/ST/2026','2025-07-01',2,2,'2026-05-28 10:53:18','2026-05-28 10:53:18'),
(56,27,'KRY-0053','Nashfa Erlina Sahli','0078716398','P',1,'Jl. Lintas RT 009 Desa Lintas Utara Kec. Keritang',NULL,NULL,NULL,'karyawan-foto/uzmLMzeXjAbaA2nCtgy3rS2LhYNg40oHNPCLFzrw.jpg','Guru RA','001/ST/2026','2025-07-01',2,8,'2026-05-28 10:56:07','2026-06-09 07:18:55'),
(57,28,'KRY-0054','Neli Syahputri','3081696510','P',1,'Seberang Pulau Kijang RT 002 RW 001',NULL,NULL,NULL,'karyawan-foto/6h8jo7JN0kNmQbo8HjQHT0IbaoHekGMneZBGyUcX.jpg','kasir koprasi','001/ST/2026','2025-07-01',2,9,'2026-05-28 10:59:24','2026-05-28 10:59:24'),
(58,29,'KRY-0055','Putri Zahrotul Kholisah','0071498067','P',1,'Jl. Darma Bakti GG. Rino No. 02 Lubuhbaru Barat Kec. Payung Sekaki Pekanbaru',NULL,NULL,NULL,'karyawan-foto/8U5Tnzv6k7Xhu1Gtx9sLHiF1lgXSrpnhaebzj0Zj.jpg','Guru RA','001/ST/2026','2025-07-01',2,8,'2026-05-28 11:01:39','2026-06-09 07:17:09'),
(59,30,'KRY-0056','Risna Maila Zulfa','0067983542','P',1,'Mugomulyo Sungai Batang',NULL,NULL,NULL,'karyawan-foto/mYnwqP7L2fRa0Kt9qNfE8wT4EoYadxgcqR7rVapL.jpg','TU MTS','001/ST/2026','2025-07-01',2,2,'2026-05-28 11:03:22','2026-05-28 11:03:22'),
(60,31,'KRY-0057','Siti Nur Hasanah','0075511226','P',1,'Mugomulyo Sungai Batang',NULL,NULL,NULL,'karyawan-foto/uU5Z5OXiSnT22gMjogUtAhbCyVHPZI9EqFvIUGpK.jpg','Guru RA','001/ST/2026','2025-07-01',2,6,'2026-05-28 11:05:55','2026-06-06 06:24:26'),
(65,32,'KRY-0058','Drs. H. Anas, M.Pd','1404180112660004','L',1,'Mugomulyo',NULL,NULL,NULL,'karyawan-foto/ufO7Q6df9rJlPCv4NQ9wL0pjp3Uo4vo3dcu2RoVE.jpg','Kepala Madrasah','001/ST/2026','2025-07-01',2,10,'2026-05-28 11:26:49','2026-06-05 09:01:24'),
(66,33,'KRY-0059','Nina Marlina, S.Pd, M.E','1404184703680002','P',1,'Mugomulyo',NULL,NULL,NULL,'karyawan-foto/2Uk6UpuIjJr8W1Yig9brife9wUA86W1LZUGkrUq6.jpg','Kepala Koperasi','001/ST/2026','2025-07-01',2,9,'2026-05-28 11:34:14','2026-05-28 11:34:14'),
(67,34,'KRY-0060','Rahmat Budi Permana, S.Pd., M.E','3206242803860002','L',1,'Mugomulyo',NULL,NULL,NULL,'karyawan-foto/zWuq03CzU0REjEcBi5ZPSUFpJVh9OBmPXvXcohB0.jpg','Kepala Depot Air','001/ST/2026','2025-07-01',2,11,'2026-05-28 11:37:31','2026-05-28 11:37:31'),
(71,36,'KRY-0061','MUH. HARUN, S.H.I., M.Sos','1404180607850002','L',1,'Jalan Pelajar, RT 009, RW 002, Desa Mugo Mulyo',NULL,NULL,NULL,'karyawan-foto/1780749240.png','Kepala Perpustakaan','001/ST/2026','2025-07-01',2,12,'2026-05-29 06:38:26','2026-06-06 05:55:00'),
(72,37,'KRY-0062','BANDIYAH','1404204207730001','P',1,'Mugomulyo',NULL,NULL,NULL,'karyawan-foto/gDslALYYsStQCRXdCqi8uSu25aJSuDchIXKBbwN2.jpg','kepala RA','001/ST/2026','2025-07-01',2,4,'2026-06-02 06:16:41','2026-06-02 06:16:41'),
(73,38,'KRY-0063','SITI JUARIAH','1404186001760001','P',1,'Mugomulyo',NULL,NULL,NULL,'karyawan-foto/4Q44EsgZj4RcDBHMfLWe1eP8UoUm31LUck63E4X2.jpg','Kepala paud','001/ST/2026','2025-07-01',2,5,'2026-06-02 06:26:35','2026-06-02 06:26:35'),
(74,39,'KRY-0064','MISRUN','1404181206640001','L',1,'Mugomulyo',NULL,NULL,NULL,'karyawan-foto/8nZAMNjzgY24PdnkhvjZUpRBkGvQN8X75agj0Cyj.jpg','Pengurus Pondok Pesantren Putra','001/ST/2026','2025-07-01',2,7,'2026-06-02 06:32:08','2026-06-06 05:54:32'),
(77,40,'KRY-0065','Sujitho, S.Pd. SD','1404182007670001','L',1,NULL,NULL,NULL,NULL,'karyawan-foto/F6hJkZjeAicBU1ef0LrlTDuzgT2LNkudwX1S0k8w.jpg','Kepala Madrasah Ibtidaiyah','001/ST/2026','2025-07-01',2,3,'2026-06-06 05:32:22','2026-06-06 05:32:22'),
(78,41,'KRY-0066','Nur Makmuroh','1404185506720001','P',1,'JL. PELAJAR RT.06 RW.02','nurmakmuroh@gmail.com','085184092030',NULL,'karyawan-foto/FVAajz0qZmj67QbmyhkuPfxyb6dKyhebq6q3ysak.jpg','Kepala MDTA','001/ST/2026','2025-07-01',2,6,'2026-06-06 06:15:07','2026-06-06 06:15:07'),
(79,42,'KRY-0067','Drs. H. Ah. Masyhuri SA, M.Pd.I',NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,17,'2026-06-07 09:57:56','2026-06-07 09:57:56');
/*!40000 ALTER TABLE `karyawan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `karyawan_pangkalan`
--

DROP TABLE IF EXISTS `karyawan_pangkalan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `karyawan_pangkalan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `karyawan_id` bigint(20) unsigned NOT NULL,
  `pangkalan_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `karyawan_pangkalan_karyawan_id_pangkalan_id_unique` (`karyawan_id`,`pangkalan_id`),
  KEY `karyawan_pangkalan_pangkalan_id_foreign` (`pangkalan_id`),
  KEY `idx_kp_pangkalan_karyawan` (`pangkalan_id`,`karyawan_id`),
  CONSTRAINT `karyawan_pangkalan_karyawan_id_foreign` FOREIGN KEY (`karyawan_id`) REFERENCES `karyawan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `karyawan_pangkalan_pangkalan_id_foreign` FOREIGN KEY (`pangkalan_id`) REFERENCES `pangkalan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=338 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `karyawan_pangkalan`
--

LOCK TABLES `karyawan_pangkalan` WRITE;
/*!40000 ALTER TABLE `karyawan_pangkalan` DISABLE KEYS */;
INSERT INTO `karyawan_pangkalan` VALUES
(1,20,1,'2026-04-09 18:04:17','2026-04-09 18:04:17'),
(2,21,1,'2026-04-09 18:04:17','2026-04-09 18:04:17'),
(3,22,1,'2026-04-09 18:04:17','2026-04-09 18:04:17'),
(4,28,1,'2026-04-09 18:04:17','2026-04-09 18:04:17'),
(5,12,2,'2026-04-09 18:04:17','2026-04-09 18:04:17'),
(6,26,2,'2026-04-09 18:04:17','2026-04-09 18:04:17'),
(7,2,3,'2026-04-09 18:04:17','2026-04-09 18:04:17'),
(8,3,3,'2026-04-09 18:04:17','2026-04-09 18:04:17'),
(9,8,3,'2026-04-09 18:04:17','2026-04-09 18:04:17'),
(10,15,3,'2026-04-09 18:04:17','2026-04-09 18:04:17'),
(11,5,4,'2026-04-09 18:04:17','2026-04-09 18:04:17'),
(12,10,4,'2026-04-09 18:04:17','2026-04-09 18:04:17'),
(13,13,5,'2026-04-09 18:04:17','2026-04-09 18:04:17'),
(14,17,5,'2026-04-09 18:04:17','2026-04-09 18:04:17'),
(15,25,5,'2026-04-09 18:04:17','2026-04-09 18:04:17'),
(16,7,6,'2026-04-09 18:04:17','2026-04-09 18:04:17'),
(17,1,9,'2026-04-09 18:04:17','2026-04-09 18:04:17'),
(18,4,9,'2026-04-09 18:04:17','2026-04-09 18:04:17'),
(19,9,9,'2026-04-09 18:04:17','2026-04-09 18:04:17'),
(20,16,9,'2026-04-09 18:04:17','2026-04-09 18:04:17'),
(21,24,9,'2026-04-09 18:04:17','2026-04-09 18:04:17'),
(22,27,9,'2026-04-09 18:04:17','2026-04-09 18:04:17'),
(23,6,11,'2026-04-09 18:04:17','2026-04-09 18:04:17'),
(24,18,11,'2026-04-09 18:04:17','2026-04-09 18:04:17'),
(25,19,11,'2026-04-09 18:04:17','2026-04-09 18:04:17'),
(26,11,12,'2026-04-09 18:04:17','2026-04-09 18:04:17'),
(27,14,12,'2026-04-09 18:04:17','2026-04-09 18:04:17'),
(28,23,12,'2026-04-09 18:04:17','2026-04-09 18:04:17'),
(113,30,1,'2026-06-04 17:46:24','2026-06-04 17:46:24'),
(114,32,2,'2026-06-04 17:46:24','2026-06-04 17:46:24'),
(115,34,1,'2026-06-04 17:46:24','2026-06-04 17:46:24'),
(116,35,5,'2026-06-04 17:46:24','2026-06-04 17:46:24'),
(117,36,1,'2026-06-04 17:46:24','2026-06-04 17:46:24'),
(118,37,3,'2026-06-04 17:46:24','2026-06-04 17:46:24'),
(119,38,9,'2026-06-04 17:46:24','2026-06-04 17:46:24'),
(120,39,9,'2026-06-04 17:46:24','2026-06-04 17:46:24'),
(121,40,5,'2026-06-04 17:46:24','2026-06-04 17:46:24'),
(122,41,1,'2026-06-04 17:46:24','2026-06-04 17:46:24'),
(123,42,12,'2026-06-04 17:46:24','2026-06-04 17:46:24'),
(124,43,11,'2026-06-04 17:46:24','2026-06-04 17:46:24'),
(125,45,9,'2026-06-04 17:46:24','2026-06-04 17:46:24'),
(126,46,9,'2026-06-04 17:46:24','2026-06-04 17:46:24'),
(127,47,11,'2026-06-04 17:46:24','2026-06-04 17:46:24'),
(128,48,3,'2026-06-04 17:46:24','2026-06-04 17:46:24'),
(129,49,9,'2026-06-04 17:46:24','2026-06-04 17:46:24'),
(130,50,5,'2026-06-04 17:46:24','2026-06-04 17:46:24'),
(131,52,3,'2026-06-04 17:46:24','2026-06-04 17:46:24'),
(132,53,3,'2026-06-04 17:46:24','2026-06-04 17:46:24'),
(133,54,5,'2026-06-04 17:46:24','2026-06-04 17:46:24'),
(134,55,2,'2026-06-04 17:46:24','2026-06-04 17:46:24'),
(135,56,4,'2026-06-04 17:46:24','2026-06-04 17:46:24'),
(136,57,9,'2026-06-04 17:46:24','2026-06-04 17:46:24'),
(137,58,4,'2026-06-04 17:46:24','2026-06-04 17:46:24'),
(138,59,2,'2026-06-04 17:46:24','2026-06-04 17:46:24'),
(139,60,4,'2026-06-04 17:46:24','2026-06-04 17:46:24'),
(140,65,2,'2026-06-04 17:46:24','2026-06-04 17:46:24'),
(141,66,9,'2026-06-04 17:46:24','2026-06-04 17:46:24'),
(142,67,11,'2026-06-04 17:46:24','2026-06-04 17:46:24'),
(143,71,12,'2026-06-04 17:46:24','2026-06-04 17:46:24'),
(144,72,4,'2026-06-04 17:46:24','2026-06-04 17:46:24'),
(145,73,5,'2026-06-04 17:46:24','2026-06-04 17:46:24'),
(146,74,7,'2026-06-04 17:46:24','2026-06-04 17:46:24'),
(152,45,7,'2026-06-05 08:09:56','2026-06-05 08:09:56'),
(154,66,8,'2026-06-05 08:57:43','2026-06-05 08:57:43'),
(155,65,14,'2026-06-05 09:01:24','2026-06-05 09:01:24'),
(158,77,3,'2026-06-06 05:32:22','2026-06-06 05:32:22'),
(160,78,6,'2026-06-06 06:15:08','2026-06-06 06:15:08'),
(161,60,6,'2026-06-06 06:24:26','2026-06-06 06:24:26'),
(162,60,8,'2026-06-06 06:24:27','2026-06-06 06:24:27'),
(163,52,6,'2026-06-06 06:25:32','2026-06-06 06:25:32'),
(164,52,8,'2026-06-06 06:25:32','2026-06-06 06:25:32'),
(166,53,6,'2026-06-06 06:39:56','2026-06-06 06:39:56'),
(167,53,8,'2026-06-06 06:39:56','2026-06-06 06:39:56'),
(169,59,8,'2026-06-06 07:37:32','2026-06-06 07:37:32'),
(174,32,16,'2026-06-07 09:34:45','2026-06-07 09:34:45'),
(175,34,16,'2026-06-07 09:34:45','2026-06-07 09:34:45'),
(176,35,16,'2026-06-07 09:34:45','2026-06-07 09:34:45'),
(177,36,16,'2026-06-07 09:34:45','2026-06-07 09:34:45'),
(178,37,16,'2026-06-07 09:34:45','2026-06-07 09:34:45'),
(179,38,16,'2026-06-07 09:34:45','2026-06-07 09:34:45'),
(180,39,16,'2026-06-07 09:34:45','2026-06-07 09:34:45'),
(181,40,16,'2026-06-07 09:34:45','2026-06-07 09:34:45'),
(182,41,16,'2026-06-07 09:34:45','2026-06-07 09:34:45'),
(183,42,16,'2026-06-07 09:34:45','2026-06-07 09:34:45'),
(184,43,16,'2026-06-07 09:34:45','2026-06-07 09:34:45'),
(185,45,16,'2026-06-07 09:34:45','2026-06-07 09:34:45'),
(186,46,16,'2026-06-07 09:34:45','2026-06-07 09:34:45'),
(187,47,16,'2026-06-07 09:34:45','2026-06-07 09:34:45'),
(188,48,16,'2026-06-07 09:34:45','2026-06-07 09:34:45'),
(189,49,16,'2026-06-07 09:34:45','2026-06-07 09:34:45'),
(190,50,16,'2026-06-07 09:34:45','2026-06-07 09:34:45'),
(191,52,16,'2026-06-07 09:34:45','2026-06-07 09:34:45'),
(192,53,16,'2026-06-07 09:34:45','2026-06-07 09:34:45'),
(193,54,16,'2026-06-07 09:34:45','2026-06-07 09:34:45'),
(194,55,16,'2026-06-07 09:34:45','2026-06-07 09:34:45'),
(195,56,16,'2026-06-07 09:34:45','2026-06-07 09:34:45'),
(196,57,16,'2026-06-07 09:34:45','2026-06-07 09:34:45'),
(197,58,16,'2026-06-07 09:34:45','2026-06-07 09:34:45'),
(198,59,16,'2026-06-07 09:34:45','2026-06-07 09:34:45'),
(199,60,16,'2026-06-07 09:34:45','2026-06-07 09:34:45'),
(208,65,10,'2026-06-07 09:49:28','2026-06-07 09:49:28'),
(210,32,17,'2026-06-07 09:53:20','2026-06-07 09:53:20'),
(211,34,17,'2026-06-07 09:53:20','2026-06-07 09:53:20'),
(212,35,17,'2026-06-07 09:53:20','2026-06-07 09:53:20'),
(213,36,17,'2026-06-07 09:53:20','2026-06-07 09:53:20'),
(214,37,17,'2026-06-07 09:53:20','2026-06-07 09:53:20'),
(215,38,17,'2026-06-07 09:53:20','2026-06-07 09:53:20'),
(216,39,17,'2026-06-07 09:53:20','2026-06-07 09:53:20'),
(217,40,17,'2026-06-07 09:53:20','2026-06-07 09:53:20'),
(218,41,17,'2026-06-07 09:53:20','2026-06-07 09:53:20'),
(219,42,17,'2026-06-07 09:53:20','2026-06-07 09:53:20'),
(220,43,17,'2026-06-07 09:53:20','2026-06-07 09:53:20'),
(221,45,17,'2026-06-07 09:53:20','2026-06-07 09:53:20'),
(222,46,17,'2026-06-07 09:53:20','2026-06-07 09:53:20'),
(223,47,17,'2026-06-07 09:53:20','2026-06-07 09:53:20'),
(224,48,17,'2026-06-07 09:53:20','2026-06-07 09:53:20'),
(225,49,17,'2026-06-07 09:53:20','2026-06-07 09:53:20'),
(226,50,17,'2026-06-07 09:53:20','2026-06-07 09:53:20'),
(227,52,17,'2026-06-07 09:53:20','2026-06-07 09:53:20'),
(228,53,17,'2026-06-07 09:53:20','2026-06-07 09:53:20'),
(229,54,17,'2026-06-07 09:53:20','2026-06-07 09:53:20'),
(230,55,17,'2026-06-07 09:53:20','2026-06-07 09:53:20'),
(231,56,17,'2026-06-07 09:53:20','2026-06-07 09:53:20'),
(232,57,17,'2026-06-07 09:53:20','2026-06-07 09:53:20'),
(233,58,17,'2026-06-07 09:53:20','2026-06-07 09:53:20'),
(234,59,17,'2026-06-07 09:53:20','2026-06-07 09:53:20'),
(235,60,17,'2026-06-07 09:53:20','2026-06-07 09:53:20'),
(276,32,13,'2026-06-07 10:03:57','2026-06-07 10:03:57'),
(277,34,13,'2026-06-07 10:03:57','2026-06-07 10:03:57'),
(278,35,13,'2026-06-07 10:03:57','2026-06-07 10:03:57'),
(279,36,13,'2026-06-07 10:03:57','2026-06-07 10:03:57'),
(280,37,13,'2026-06-07 10:03:57','2026-06-07 10:03:57'),
(281,38,13,'2026-06-07 10:03:57','2026-06-07 10:03:57'),
(282,39,13,'2026-06-07 10:03:57','2026-06-07 10:03:57'),
(283,40,13,'2026-06-07 10:03:57','2026-06-07 10:03:57'),
(284,41,13,'2026-06-07 10:03:57','2026-06-07 10:03:57'),
(285,42,13,'2026-06-07 10:03:57','2026-06-07 10:03:57'),
(286,43,13,'2026-06-07 10:03:57','2026-06-07 10:03:57'),
(287,45,13,'2026-06-07 10:03:57','2026-06-07 10:03:57'),
(288,46,13,'2026-06-07 10:03:57','2026-06-07 10:03:57'),
(289,47,13,'2026-06-07 10:03:57','2026-06-07 10:03:57'),
(290,48,13,'2026-06-07 10:03:57','2026-06-07 10:03:57'),
(291,49,13,'2026-06-07 10:03:57','2026-06-07 10:03:57'),
(292,50,13,'2026-06-07 10:03:57','2026-06-07 10:03:57'),
(293,52,13,'2026-06-07 10:03:57','2026-06-07 10:03:57'),
(294,53,13,'2026-06-07 10:03:57','2026-06-07 10:03:57'),
(295,54,13,'2026-06-07 10:03:57','2026-06-07 10:03:57'),
(296,55,13,'2026-06-07 10:03:57','2026-06-07 10:03:57'),
(297,56,13,'2026-06-07 10:03:57','2026-06-07 10:03:57'),
(298,57,13,'2026-06-07 10:03:57','2026-06-07 10:03:57'),
(299,58,13,'2026-06-07 10:03:57','2026-06-07 10:03:57'),
(300,59,13,'2026-06-07 10:03:57','2026-06-07 10:03:57'),
(301,60,13,'2026-06-07 10:03:57','2026-06-07 10:03:57'),
(311,71,16,'2026-06-07 10:08:09','2026-06-07 10:08:09'),
(312,40,6,'2026-06-08 08:34:43','2026-06-08 08:34:43'),
(313,50,7,'2026-06-09 07:06:39','2026-06-09 07:06:39'),
(314,49,7,'2026-06-09 07:07:10','2026-06-09 07:07:10'),
(315,47,7,'2026-06-09 07:08:08','2026-06-09 07:08:08'),
(316,43,7,'2026-06-09 07:08:55','2026-06-09 07:08:55'),
(317,42,7,'2026-06-09 07:11:05','2026-06-09 07:11:05'),
(318,37,7,'2026-06-09 07:11:58','2026-06-09 07:11:58'),
(319,36,7,'2026-06-09 07:12:56','2026-06-09 07:12:56'),
(320,35,7,'2026-06-09 07:13:28','2026-06-09 07:13:28'),
(321,32,7,'2026-06-09 07:13:56','2026-06-09 07:13:56'),
(322,58,8,'2026-06-09 07:17:09','2026-06-09 07:17:09'),
(323,57,8,'2026-06-09 07:18:32','2026-06-09 07:18:32'),
(324,56,8,'2026-06-09 07:18:55','2026-06-09 07:18:55'),
(325,55,8,'2026-06-09 07:20:03','2026-06-09 07:20:03'),
(326,54,8,'2026-06-09 07:20:50','2026-06-09 07:20:50'),
(327,46,8,'2026-06-09 07:21:19','2026-06-09 07:21:19'),
(328,41,8,'2026-06-09 07:21:46','2026-06-09 07:21:46'),
(329,40,8,'2026-06-09 07:22:09','2026-06-09 07:22:09'),
(330,39,8,'2026-06-09 07:22:35','2026-06-09 07:22:35'),
(331,38,8,'2026-06-09 07:22:58','2026-06-09 07:22:58'),
(332,34,8,'2026-06-09 07:23:19','2026-06-09 07:23:19'),
(333,45,10,'2026-06-09 07:24:49','2026-06-09 07:24:49'),
(334,35,10,'2026-06-09 07:25:36','2026-06-09 07:25:36'),
(335,45,14,'2026-06-09 07:27:02','2026-06-09 07:27:02');
/*!40000 ALTER TABLE `karyawan_pangkalan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kategori_kinerja`
--

DROP TABLE IF EXISTS `kategori_kinerja`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kategori_kinerja`
--

LOCK TABLES `kategori_kinerja` WRITE;
/*!40000 ALTER TABLE `kategori_kinerja` DISABLE KEYS */;
INSERT INTO `kategori_kinerja` VALUES
(4,'KTG-004','Pramuka','kegiatan',1,33.00,'2026-04-09 16:39:18','2026-06-07 07:22:58'),
(14,'KTG-011','Kedisiplinan','kinerja',0,0.00,'2026-04-13 00:48:19','2026-04-13 00:48:19'),
(15,'KTG-012','Karakter','kinerja',0,0.00,'2026-04-13 00:48:29','2026-04-13 00:48:29'),
(16,'KTG-013','Kompetensi','kinerja',0,0.00,'2026-04-13 00:48:41','2026-04-13 00:48:41'),
(17,'KTG-014','Pengajian','kegiatan',1,0.00,'2026-06-07 07:22:42','2026-06-07 07:22:42'),
(19,'KTG-016','Jamaah Shalat 5 Waktu','kegiatan',1,0.00,'2026-06-07 07:26:32','2026-06-07 09:49:49');
/*!40000 ALTER TABLE `kategori_kinerja` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kategori_kinerja_kompetensi`
--

DROP TABLE IF EXISTS `kategori_kinerja_kompetensi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kategori_kinerja_kompetensi`
--

LOCK TABLES `kategori_kinerja_kompetensi` WRITE;
/*!40000 ALTER TABLE `kategori_kinerja_kompetensi` DISABLE KEYS */;
INSERT INTO `kategori_kinerja_kompetensi` VALUES
(22,4,15,'2026-04-12 23:39:43','2026-04-12 23:39:43'),
(29,4,16,'2026-04-12 23:40:21','2026-04-12 23:40:21'),
(30,4,17,'2026-04-12 23:40:36','2026-04-12 23:40:36'),
(37,4,18,'2026-04-12 23:56:51','2026-04-12 23:56:51'),
(38,4,19,'2026-04-13 00:03:15','2026-04-13 00:03:15'),
(45,14,20,'2026-04-13 01:00:02','2026-04-13 01:00:02'),
(46,14,21,'2026-04-13 01:00:13','2026-04-13 01:00:13'),
(47,15,22,'2026-04-13 01:00:27','2026-04-13 01:00:27'),
(48,15,23,'2026-04-13 01:00:40','2026-04-13 01:00:40'),
(49,15,24,'2026-04-13 01:00:51','2026-04-13 01:00:51'),
(50,14,25,'2026-04-13 01:01:00','2026-04-13 01:01:00'),
(51,16,26,'2026-04-13 01:01:11','2026-04-13 01:01:11'),
(52,16,27,'2026-04-13 01:01:21','2026-04-13 01:01:21'),
(53,16,28,'2026-04-13 01:01:29','2026-04-13 01:01:29'),
(54,16,29,'2026-04-13 01:01:40','2026-04-13 01:01:40'),
(55,15,29,'2026-05-31 08:34:45','2026-05-31 08:34:45'),
(56,17,15,'2026-06-07 07:28:37','2026-06-07 07:28:37'),
(57,19,15,'2026-06-07 07:28:37','2026-06-07 07:28:37'),
(58,17,19,'2026-06-07 07:31:07','2026-06-07 07:31:07'),
(59,19,19,'2026-06-07 07:31:07','2026-06-07 07:31:07');
/*!40000 ALTER TABLE `kategori_kinerja_kompetensi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kepala_pangkalan`
--

DROP TABLE IF EXISTS `kepala_pangkalan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kepala_pangkalan`
--

LOCK TABLES `kepala_pangkalan` WRITE;
/*!40000 ALTER TABLE `kepala_pangkalan` DISABLE KEYS */;
INSERT INTO `kepala_pangkalan` VALUES
(1,4,1,'2026-06-04 17:26:47','2026-06-04 17:26:47'),
(2,32,2,'2026-06-04 17:27:10','2026-06-04 17:27:10'),
(3,37,4,'2026-06-04 17:28:19','2026-06-04 17:28:19'),
(4,33,9,'2026-06-04 17:28:35','2026-06-04 17:28:35'),
(5,39,7,'2026-06-04 17:28:52','2026-06-04 17:28:52'),
(6,38,5,'2026-06-04 17:29:13','2026-06-04 17:29:13'),
(7,32,10,'2026-06-04 17:30:05','2026-06-04 17:30:05'),
(8,34,11,'2026-06-04 17:32:51','2026-06-04 17:32:51'),
(9,36,12,'2026-06-04 17:33:04','2026-06-04 17:33:04'),
(11,33,8,'2026-06-05 08:58:52','2026-06-05 08:58:52'),
(12,32,14,'2026-06-06 05:06:43','2026-06-06 05:06:43'),
(13,40,3,'2026-06-06 05:29:58','2026-06-06 05:29:58'),
(14,41,6,'2026-06-06 06:11:20','2026-06-06 06:11:20'),
(15,39,13,'2026-06-06 07:36:11','2026-06-06 07:36:11'),
(16,36,16,'2026-06-06 07:53:01','2026-06-06 07:53:01'),
(17,42,17,'2026-06-07 09:53:20','2026-06-07 09:53:20');
/*!40000 ALTER TABLE `kepala_pangkalan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kompetensi`
--

DROP TABLE IF EXISTS `kompetensi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
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
INSERT INTO `kompetensi` VALUES
(15,'KMP-001',4,'Kehadiran','2026-04-12 23:39:43','2026-04-12 23:39:43'),
(16,'KMP-002',4,'Kepemimpinan','2026-04-12 23:40:21','2026-04-12 23:40:21'),
(17,'KMP-003',4,'Kompetensi','2026-04-12 23:40:36','2026-04-12 23:40:36'),
(18,'KMP-004',4,'Karakter','2026-04-12 23:56:51','2026-04-12 23:56:51'),
(19,'KMP-005',4,'Kedisiplinan','2026-04-13 00:03:15','2026-04-13 00:03:15'),
(20,'KMP-006',14,'Tepat Waktu','2026-04-13 01:00:02','2026-04-13 01:00:02'),
(21,'KMP-007',14,'Penggunaan Waktu','2026-04-13 01:00:13','2026-04-13 01:00:13'),
(22,'KMP-008',15,'Kepatuhan','2026-04-13 01:00:27','2026-04-13 01:00:27'),
(23,'KMP-009',15,'Loyalitas','2026-04-13 01:00:40','2026-04-13 01:00:40'),
(24,'KMP-010',15,'Leadership','2026-04-13 01:00:51','2026-04-13 01:00:51'),
(25,'KMP-011',14,'Tanggung-jawab','2026-04-13 01:01:00','2026-04-13 01:01:00'),
(26,'KMP-012',16,'Inisiatif','2026-04-13 01:01:11','2026-04-13 01:01:11'),
(27,'KMP-013',16,'Adaptasi','2026-04-13 01:01:21','2026-04-13 01:01:21'),
(28,'KMP-014',16,'Pemecahan Masalah','2026-04-13 01:01:29','2026-04-13 01:01:29'),
(29,'KMP-015',15,'Pengambilan Keputusan','2026-04-13 01:01:40','2026-05-31 08:34:45');
/*!40000 ALTER TABLE `kompetensi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2026_02_26_235503_create_kategori_kinerja_table',1),
(5,'2026_02_26_235503_create_tahun_penilaian_table',1),
(6,'2026_02_26_235504_create_kompetensi_table',1),
(7,'2026_02_26_235504_create_performance_rating_table',1),
(8,'2026_02_26_235505_create_karyawan_table',1),
(9,'2026_02_27_003552_create_mutasi_table',1),
(10,'2026_02_27_003553_create_transaksi_table',1),
(11,'2026_02_27_010311_add_user_id_to_karyawan_table',1),
(12,'2026_02_27_013207_create_pangkalan_table',1),
(13,'2026_02_27_013208_add_pangkalan_tugas_to_karyawan_table',1),
(14,'2026_04_09_100001_add_uac_fields_to_users_table',2),
(15,'2026_04_09_100002_add_foto_to_karyawan_table',2),
(16,'2026_04_09_100003_add_jenis_to_kategori_kinerja_table',2),
(17,'2026_04_09_100004_create_setting_lembaga_table',2),
(18,'2026_04_09_100005_create_penilaian_locks_table',2),
(19,'2026_04_09_100006_create_penilaian_unlock_requests_table',2),
(20,'2026_04_10_090001_add_is_active_to_karyawan_table',3),
(21,'2026_04_10_090002_add_kepala_level_to_users_table',3),
(22,'2026_04_10_090003_add_lokasi_surat_to_setting_lembaga_table',3),
(23,'2026_04_10_120001_remove_kepala_level_from_users_table',4),
(24,'2026_04_10_130001_create_kategori_kinerja_kompetensi_table',5),
(25,'2026_04_10_140001_add_is_wajib_and_karyawan_pangkalan_table',6),
(26,'2026_04_10_160001_add_laporan_format_fields_to_setting_lembaga_table',7),
(27,'2026_04_10_170001_add_laporan_layout_settings_to_setting_lembaga_table',8),
(28,'2026_04_10_180001_add_laporan_column_labels_order_and_scoring_to_setting_lembaga_table',9),
(29,'2026_04_13_090001_add_sidebar_fields_to_setting_lembaga_table',10),
(30,'2026_04_13_100002_add_profile_fields_to_karyawan_table',11),
(31,'2026_04_13_120001_add_kop_contact_fields_to_setting_lembaga_table',12),
(32,'2026_04_13_130001_add_sidebar_visibility_fields_to_setting_lembaga_table',13),
(33,'2026_04_13_130002_add_is_wajib_to_kategori_kinerja_table',13),
(34,'2026_04_13_150003_create_pangkalan_kategori_kinerja_table',14),
(35,'2026_04_27_120001_add_laporan_jenis_weight_fields_to_setting_lembaga_table',15),
(36,'2026_06_04_100001_create_kepala_pangkalan_table',16),
(37,'2026_06_04_100002_create_karyawan_pangkalan_table',17),
(38,'2026_06_04_200001_add_contact_fields_to_karyawan_table',18),
(39,'2026_06_05_100001_add_is_active_to_pangkalan_table',19),
(40,'2026_06_05_100002_add_kepala_user_id_to_pangkalan_table',20),
(41,'2026_06_05_100003_add_pangkalan_id_to_transaksi_table',21),
(42,'2026_06_07_100001_add_tata_usaha_role_to_users_table',22),
(43,'2026_06_07_100002_create_reward_punishment_table',22),
(44,'2026_06_07_200001_add_penanggung_jawab_to_pangkalan_kategori_kinerja_table',23),
(45,'2026_06_08_100001_add_kategori_kinerja_id_to_transaksi_table',24),
(46,'2026_06_10_100001_add_performance_indexes',25),
(47,'2026_06_10_110001_add_lock_enabled_to_setting_lembaga_table',25);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mutasi`
--

DROP TABLE IF EXISTS `mutasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pangkalan`
--

LOCK TABLES `pangkalan` WRITE;
/*!40000 ALTER TABLE `pangkalan` DISABLE KEYS */;
INSERT INTO `pangkalan` VALUES
(1,'PNG-001','MA AL-HUDA AL-ILAHIYAH',0,'FATHUL MU\'IN, S.Pd.',NULL,4,1,'2026-02-27 00:30:48','2026-06-04 17:26:47'),
(2,'PNG-002','MTs AL-HUDA AL-ILAHIYAH',0,'Drs. H. Anas, M.Pd',NULL,32,1,'2026-02-27 00:30:48','2026-06-04 17:27:10'),
(3,'PNG-003','MI AL-HUDA AL-ILAHIYAH',0,'SUJITHO, S.Pd.SD',NULL,40,1,'2026-02-27 00:30:48','2026-06-06 05:29:58'),
(4,'PNG-004','RA AL-HUDA AL-ILAHIYAH',0,'BANDIYAH',NULL,37,1,'2026-02-27 00:30:48','2026-06-04 17:28:19'),
(5,'PNG-005','PAUD DASARI BUDI',0,'SITI JUARIAH',NULL,38,1,'2026-02-27 00:30:48','2026-06-04 17:29:12'),
(6,'PNG-006','MDTA AL-HUDA AL-ILAHIYAH',0,'NUR MAKMUROH',NULL,41,1,'2026-02-27 00:30:48','2026-06-06 06:11:20'),
(7,'PNG-007','PENGURUS PONPES PA',0,'MISRUN',NULL,39,1,'2026-02-27 00:30:48','2026-06-04 17:28:52'),
(8,'PNG-008','PENGURUS PONPES PI',0,'NINA MARLINA, S.Pd.',NULL,33,1,'2026-02-27 00:30:48','2026-06-05 08:58:52'),
(9,'PNG-009','KOPERASI PONTREN',0,'Nina Marlina, S.Pd, M.E',NULL,33,1,'2026-02-27 00:30:48','2026-06-04 17:28:35'),
(10,'PNG-010','KEMASJIDAN',0,NULL,NULL,32,1,'2026-02-27 00:30:48','2026-06-07 09:49:28'),
(11,'PNG-011','DEPOT AIR, TAMAN, LOGISTIK',0,'Rahmat Budi Permana, S.Pd., M.E',NULL,34,1,'2026-02-27 00:30:48','2026-06-04 17:32:51'),
(12,'PNG-012','PERPUSTAKAAN',0,'MUH. HARUN, S.H.I., M.Sos',NULL,36,1,'2026-02-27 00:30:48','2026-06-04 17:33:04'),
(13,'PNG-013','PRAMUKA',1,NULL,NULL,39,1,'2026-02-27 00:30:48','2026-06-07 10:03:57'),
(14,'PNG-014','MADRASAH QUR\'AN (MQ)',0,NULL,NULL,32,1,'2026-06-05 00:34:51','2026-06-06 05:06:43'),
(16,'PNG-015','PENGAJIAN',1,NULL,'Kegiatan Pengajian Khusus Alumni',36,1,'2026-06-06 07:53:00','2026-06-07 09:34:45'),
(17,'PNG-016','Jamaah Shalat',1,NULL,'Kegiatan Jamaah Shalat',42,1,'2026-06-07 09:53:20','2026-06-07 09:53:20');
/*!40000 ALTER TABLE `pangkalan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pangkalan_kategori_kinerja`
--

DROP TABLE IF EXISTS `pangkalan_kategori_kinerja`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pangkalan_kategori_kinerja` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pangkalan_id` bigint(20) unsigned NOT NULL,
  `kategori_kinerja_id` bigint(20) unsigned NOT NULL,
  `penanggung_jawab_user_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pangkalan_kategori_unique` (`pangkalan_id`,`kategori_kinerja_id`),
  KEY `pangkalan_kategori_kinerja_kategori_kinerja_id_foreign` (`kategori_kinerja_id`),
  KEY `pangkalan_kategori_kinerja_penanggung_jawab_user_id_foreign` (`penanggung_jawab_user_id`),
  CONSTRAINT `pangkalan_kategori_kinerja_kategori_kinerja_id_foreign` FOREIGN KEY (`kategori_kinerja_id`) REFERENCES `kategori_kinerja` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pangkalan_kategori_kinerja_pangkalan_id_foreign` FOREIGN KEY (`pangkalan_id`) REFERENCES `pangkalan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pangkalan_kategori_kinerja_penanggung_jawab_user_id_foreign` FOREIGN KEY (`penanggung_jawab_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pangkalan_kategori_kinerja`
--

LOCK TABLES `pangkalan_kategori_kinerja` WRITE;
/*!40000 ALTER TABLE `pangkalan_kategori_kinerja` DISABLE KEYS */;
INSERT INTO `pangkalan_kategori_kinerja` VALUES
(1,1,14,NULL,'2026-04-13 01:19:58','2026-04-13 01:19:58'),
(2,1,15,NULL,'2026-04-13 01:19:58','2026-04-13 01:19:58'),
(3,1,16,NULL,'2026-04-13 01:19:58','2026-04-13 01:19:58'),
(4,2,14,NULL,'2026-04-13 01:20:05','2026-04-13 01:20:05'),
(5,2,15,NULL,'2026-04-13 01:20:05','2026-04-13 01:20:05'),
(6,2,16,NULL,'2026-04-13 01:20:05','2026-04-13 01:20:05'),
(7,3,14,NULL,'2026-04-13 01:20:11','2026-04-13 01:20:11'),
(8,3,15,NULL,'2026-04-13 01:20:11','2026-04-13 01:20:11'),
(9,3,16,NULL,'2026-04-13 01:20:11','2026-04-13 01:20:11'),
(10,10,14,NULL,'2026-04-13 01:20:18','2026-04-13 01:20:18'),
(11,10,15,NULL,'2026-04-13 01:20:18','2026-04-13 01:20:18'),
(12,10,16,NULL,'2026-04-13 01:20:18','2026-04-13 01:20:18'),
(13,8,14,NULL,'2026-04-13 01:20:24','2026-04-13 01:20:24'),
(14,8,15,NULL,'2026-04-13 01:20:24','2026-04-13 01:20:24'),
(15,8,16,NULL,'2026-04-13 01:20:24','2026-04-13 01:20:24'),
(16,7,14,NULL,'2026-04-13 01:20:30','2026-04-13 01:20:30'),
(17,7,15,NULL,'2026-04-13 01:20:30','2026-04-13 01:20:30'),
(18,7,16,NULL,'2026-04-13 01:20:30','2026-04-13 01:20:30'),
(19,6,14,NULL,'2026-04-13 01:20:35','2026-04-13 01:20:35'),
(20,6,15,NULL,'2026-04-13 01:20:35','2026-04-13 01:20:35'),
(21,6,16,NULL,'2026-04-13 01:20:35','2026-04-13 01:20:35'),
(22,5,14,NULL,'2026-04-13 01:20:41','2026-04-13 01:20:41'),
(23,5,15,NULL,'2026-04-13 01:20:41','2026-04-13 01:20:41'),
(24,5,16,NULL,'2026-04-13 01:20:41','2026-04-13 01:20:41'),
(25,4,14,NULL,'2026-04-13 01:20:46','2026-04-13 01:20:46'),
(26,4,15,NULL,'2026-04-13 01:20:46','2026-04-13 01:20:46'),
(27,4,16,NULL,'2026-04-13 01:20:46','2026-04-13 01:20:46'),
(28,9,14,NULL,'2026-04-13 01:20:52','2026-04-13 01:20:52'),
(29,9,15,NULL,'2026-04-13 01:20:52','2026-04-13 01:20:52'),
(30,9,16,NULL,'2026-04-13 01:20:52','2026-04-13 01:20:52'),
(34,12,14,NULL,'2026-04-13 01:21:06','2026-04-13 01:21:06'),
(35,12,15,NULL,'2026-04-13 01:21:06','2026-04-13 01:21:06'),
(36,12,16,NULL,'2026-04-13 01:21:06','2026-04-13 01:21:06'),
(37,11,14,NULL,'2026-04-13 01:21:20','2026-04-13 01:21:20'),
(38,11,15,NULL,'2026-04-13 01:21:20','2026-04-13 01:21:20'),
(39,11,16,NULL,'2026-04-13 01:21:20','2026-04-13 01:21:20'),
(40,14,14,NULL,'2026-06-05 00:34:51','2026-06-05 00:34:51'),
(41,14,15,NULL,'2026-06-05 00:34:51','2026-06-05 00:34:51'),
(42,14,16,NULL,'2026-06-05 00:34:51','2026-06-05 00:34:51'),
(49,16,17,NULL,'2026-06-07 08:48:36','2026-06-07 08:48:36'),
(50,13,4,NULL,'2026-06-07 08:50:12','2026-06-07 08:50:12'),
(51,17,19,NULL,'2026-06-07 09:53:20','2026-06-07 09:53:20');
/*!40000 ALTER TABLE `pangkalan_kategori_kinerja` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = utf8mb4 */;
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
  KEY `idx_lock_karyawan_tahun_locked` (`karyawan_id`,`tahun_penilaian_id`,`is_locked`),
  CONSTRAINT `penilaian_locks_karyawan_id_foreign` FOREIGN KEY (`karyawan_id`) REFERENCES `karyawan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `penilaian_locks_locked_by_user_id_foreign` FOREIGN KEY (`locked_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `penilaian_locks_submitted_by_user_id_foreign` FOREIGN KEY (`submitted_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `penilaian_locks_tahun_penilaian_id_foreign` FOREIGN KEY (`tahun_penilaian_id`) REFERENCES `tahun_penilaian` (`id`) ON DELETE CASCADE,
  CONSTRAINT `penilaian_locks_unlocked_by_user_id_foreign` FOREIGN KEY (`unlocked_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `penilaian_locks`
--

LOCK TABLES `penilaian_locks` WRITE;
/*!40000 ALTER TABLE `penilaian_locks` DISABLE KEYS */;
INSERT INTO `penilaian_locks` VALUES
(3,37,2,0,0,NULL,NULL,35,'2026-06-08 21:44:09',35,'2026-06-08 21:44:24','2026-06-08 21:44:09','2026-06-08 21:44:24'),
(4,53,2,0,0,NULL,NULL,1,'2026-06-09 09:07:31',1,'2026-06-09 09:08:05','2026-06-09 09:07:27','2026-06-09 09:08:05'),
(5,43,2,0,0,NULL,NULL,1,'2026-06-09 18:39:58',1,'2026-06-09 18:41:19','2026-06-09 18:39:58','2026-06-09 18:41:19'),
(6,47,2,0,0,NULL,NULL,NULL,NULL,1,'2026-06-09 18:41:19','2026-06-09 18:41:19','2026-06-09 18:41:19'),
(7,56,2,0,0,NULL,NULL,35,'2026-06-09 18:47:06',35,'2026-06-09 18:47:37','2026-06-09 18:47:06','2026-06-09 18:47:37'),
(8,58,2,0,0,NULL,NULL,35,'2026-06-09 18:50:40',35,'2026-06-09 18:50:53','2026-06-09 18:50:40','2026-06-09 18:50:53'),
(9,60,2,0,0,NULL,NULL,35,'2026-06-10 05:26:37',35,'2026-06-10 05:26:59','2026-06-10 05:26:37','2026-06-10 05:26:59'),
(10,35,2,0,0,NULL,NULL,NULL,NULL,1,'2026-06-10 07:33:05','2026-06-10 07:33:05','2026-06-10 07:33:05');
/*!40000 ALTER TABLE `penilaian_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `penilaian_unlock_requests`
--

DROP TABLE IF EXISTS `penilaian_unlock_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
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
  KEY `idx_unlock_status` (`status`),
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
/*!40101 SET character_set_client = utf8mb4 */;
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
INSERT INTO `performance_rating` VALUES
(1,'RTG-001','A (Sangat Baik)','Nilai 90 ke atas','2026-02-27 00:30:47','2026-02-27 00:30:47'),
(2,'RTG-002','B (Baik)','Nilai 80 - 89','2026-02-27 00:30:47','2026-02-27 00:30:47'),
(3,'RTG-003','C (Cukup)','Nilai 70 - 79','2026-02-27 00:30:47','2026-02-27 00:30:47'),
(4,'RTG-004','D (Kurang)','Nilai 60 - 69','2026-02-27 00:30:47','2026-02-27 00:30:47'),
(5,'RTG-005','E (Sangat Kurang)','Nilai di bawah 60','2026-02-27 00:30:48','2026-02-27 00:30:48');
/*!40000 ALTER TABLE `performance_rating` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reward_punishment`
--

DROP TABLE IF EXISTS `reward_punishment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `reward_punishment` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(20) NOT NULL,
  `tipe` enum('reward','punishment') NOT NULL,
  `grade` varchar(10) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `satuan` varchar(255) DEFAULT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reward_punishment_kode_unique` (`kode`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reward_punishment`
--

LOCK TABLES `reward_punishment` WRITE;
/*!40000 ALTER TABLE `reward_punishment` DISABLE KEYS */;
INSERT INTO `reward_punishment` VALUES
(1,'RP-001','punishment','C','Hukuman Nilai C','Karyawan yang mendapatkan nilai akhir C (Cukup) mendapatkan hukuman berupa pengurangan tunjangan.','Sak Semen',4,1,'2026-06-07 07:04:46','2026-06-10 08:28:44'),
(2,'RP-002','punishment','D','Hukuman Nilai D','Karyawan yang mendapatkan nilai akhir D (Kurang) mendapatkan hukuman berupa pengurangan tunjangan.','Sak Semen',8,1,'2026-06-07 07:04:46','2026-06-10 08:28:57'),
(3,'RP-003','reward','A','Reward Nilai A','Karyawan yang mendapatkan nilai akhir A (Sangat Baik) mendapatkan reward berupa bonus kinerja.',NULL,0,1,'2026-06-07 07:04:46','2026-06-07 07:04:46'),
(4,'RP-004','reward','B','Reward Nilai B','Karyawan yang mendapatkan nilai akhir B (Baik) mendapatkan reward berupa apresiasi.',NULL,0,1,'2026-06-07 07:04:46','2026-06-07 07:04:46'),
(5,'RP-005','punishment','E','Tidak Lulus',NULL,NULL,0,1,'2026-06-07 07:36:59','2026-06-07 07:36:59');
/*!40000 ALTER TABLE `reward_punishment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
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
INSERT INTO `sessions` VALUES
('1agsm28E32m023mcG0OzjMZvDQRAoj9r4wOCTQWc',NULL,'34.88.110.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRW5MWElTRndtYTdLQVlZdmdXWTZzZDlQT0czTGRWa0s1Q0dWZUtKYiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vd3d3LmFway55cHBobXVnb211bHlvLmlkIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1781110975),
('2FdQIpax5t5RZhaZwhW4LaT4dKrX7GFG5HiEL4eD',22,'202.65.236.37','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Mobile Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQlVBem04MDZTajlhRjZNQWNYMG82R3U5QU1OU0J2Mm9KcGtoOE41MiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDU6Imh0dHBzOi8vd3d3LmFway55cHBobXVnb211bHlvLmlkL3VzZXIvbGFwb3JhbiI7czo1OiJyb3V0ZSI7czoxODoidXNlci5sYXBvcmFuLmluZGV4Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjI7fQ==',1781106806),
('5LtxcziiiBtGyePx8bbve1i0Uh8nFqtWRhpd4ZrH',NULL,'202.65.236.37','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQVZYczI0VU03aGZvb3l4SDRpc3YxN2Z2bUlPVURQWjZUOGhjTTNzTSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czoxMzoibG9naW5fY2FwdGNoYSI7czo0OiI5ODEwIjtzOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czozODoiaHR0cHM6Ly93d3cuYXBrLnlwcGhtdWdvbXVseW8uaWQvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO319',1781109876),
('caSDFfelqylP1HbZhZvPvFynnekR0JpA8AhqTW5G',NULL,'202.65.236.37','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoialphSVNHMU5qN0JwS040N1BUd0NqRmhqbUY0akp3ZDI5Zlo4SHlPUiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czoxMzoibG9naW5fY2FwdGNoYSI7czo0OiIyNTk0IjtzOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czozODoiaHR0cHM6Ly93d3cuYXBrLnlwcGhtdWdvbXVseW8uaWQvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO319',1781113219),
('KrJtmLADC9Dilx3VduKzHg8MaccwG59Uj1MZOsO0',NULL,'34.88.110.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiM0Q0M0xIUjZZTmpQclVDR2pERnVFcks3TURxYVRVREpaOTZvTWM2QSI7czoxMzoibG9naW5fY2FwdGNoYSI7czo0OiI3MTE5IjtzOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czozODoiaHR0cHM6Ly93d3cuYXBrLnlwcGhtdWdvbXVseW8uaWQvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1781110976),
('M6rmk0RkMEoOvrxU1pEQdTLk9mPt1kYyChtUM7CV',1,'103.18.28.10','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoicFFIdmlXbjg2eFdTTERXS1JNMzdPRkFDZDh0NlBwc3IzVkE1ZDhndyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDc6Imh0dHBzOi8vd3d3LmFway55cHBobXVnb211bHlvLmlkL2FkbWluL2RhdGFiYXNlIjtzOjU6InJvdXRlIjtzOjIwOiJhZG1pbi5kYXRhYmFzZS5pbmRleCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==',1781135402),
('OtJ0MmafQGujgB1gjP2ZqLaYNmZGoZwmtAgZ7ORA',NULL,'202.65.236.37','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTFZRanpUQTVrSUlBMEJjMzhMeWlDcDZ0STZ2TFNUOElmVGExSnZQYiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czoxMzoibG9naW5fY2FwdGNoYSI7czo0OiI1Mzc3IjtzOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czozODoiaHR0cHM6Ly93d3cuYXBrLnlwcGhtdWdvbXVseW8uaWQvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO319',1781133581),
('pJ5i8rIrQejr9peIdeMbDbm14Zq1PU2GScFnpKlD',22,'202.65.236.37','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Mobile Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMGFnMkFJcnhpTVZYb2dvb3J0YnF6bWRFTzBSczM1YTNCaXh3T0ZwZyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDU6Imh0dHBzOi8vd3d3LmFway55cHBobXVnb211bHlvLmlkL3VzZXIvbGFwb3JhbiI7czo1OiJyb3V0ZSI7czoxODoidXNlci5sYXBvcmFuLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjI7fQ==',1781134602),
('tgHOEUeUjyGjaqPNqdpKRilZFXmWioQvkybNZVpT',NULL,'34.141.22.8','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNm5RODl0WWl3VlJYbWkyRXRHUGhEbW05bW40RG96M05CV3dJcnI0QyI7czoxMzoibG9naW5fY2FwdGNoYSI7czo0OiIyMTk5IjtzOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czozODoiaHR0cHM6Ly93d3cuYXBrLnlwcGhtdWdvbXVseW8uaWQvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1781118300),
('yVWGVezUMWZiCe0GhHidxb5muer0GYrmsQL9DV7z',NULL,'34.141.22.8','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiY3hrTGl2SGFicWdXR2ZnNGc0c09YSHBuSm8xeU9kSkg2ZkF0VkRtUyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vd3d3LmFway55cHBobXVnb211bHlvLmlkIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1781118299);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `setting_lembaga`
--

DROP TABLE IF EXISTS `setting_lembaga`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
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
  `lock_enabled` tinyint(1) NOT NULL DEFAULT 1,
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
INSERT INTO `setting_lembaga` VALUES
(1,'Yayasan Pondok Pesantren Al-Huda Mugomulyo',NULL,'Mugomulyo Kec.Sungai Batang Kab.INHIL','+62 852-2870-4379','babinlumni.ypph@gmail.com','https.//ypphmugomulyo.id','Website Aplikasi','Manajemen Kinerja Pengabdian','YPP. Al-Huda Mugomulyo',1,1,1,'Mugomulyo','Drs. H. AH. MASYHURI SA, M.Pd.I','Muh. Harun S.H.I., M.Sos.','setting-lembaga/rrojgIB1ZBbrq1EqBYSpsh5IJqQxBKvii2qIikcU.png',NULL,NULL,1,1,1,1,'rinci',1,0,1,1,1,1,1,'a4','portrait',2.54,2.54,2.54,2.54,'left','center',6,1.0,11,16,32,72,190,140,88,108,'[\"no\",\"kode_karyawan\",\"nama_karyawan\",\"pangkalan\",\"detail_kompetensi\",\"nilai_akhir\",\"rating\"]','No','Kode Karyawan','Nama Karyawan','Pangkalan','Detail Kompetensi','Nilai Akhir','Rating','weighted_kinerja_kegiatan',70.00,30.00,2,1,0,'2026-04-09 02:32:10','2026-06-09 18:40:32');
/*!40000 ALTER TABLE `setting_lembaga` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tahun_penilaian`
--

DROP TABLE IF EXISTS `tahun_penilaian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
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
INSERT INTO `tahun_penilaian` VALUES
(1,'2023/2024','Tahun Pengabdian 2023/2024',0,'2026-02-27 00:30:47','2026-05-08 23:07:29'),
(2,'2025/2026','Tahun Pengabdian 2025/2026',1,'2026-04-12 22:59:05','2026-05-08 23:07:29'),
(3,'2024/2025','Tahun Pengabdian 2024/2025',0,'2026-04-12 22:59:46','2026-04-12 22:59:54');
/*!40000 ALTER TABLE `tahun_penilaian` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksi`
--

DROP TABLE IF EXISTS `transaksi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `transaksi` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kode_transaksi` varchar(20) NOT NULL,
  `karyawan_id` bigint(20) unsigned NOT NULL,
  `pangkalan_id` bigint(20) unsigned DEFAULT NULL,
  `tahun_penilaian_id` bigint(20) unsigned DEFAULT NULL,
  `kompetensi_id` bigint(20) unsigned DEFAULT NULL,
  `kategori_kinerja_id` bigint(20) unsigned DEFAULT NULL,
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
  KEY `idx_transaksi_karyawan_tahun` (`karyawan_id`,`tahun_penilaian_id`),
  KEY `idx_transaksi_karyawan_pangkalan` (`karyawan_id`,`pangkalan_id`),
  KEY `idx_transaksi_kategori` (`kategori_kinerja_id`),
  CONSTRAINT `transaksi_karyawan_id_foreign` FOREIGN KEY (`karyawan_id`) REFERENCES `karyawan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transaksi_kategori_kinerja_id_foreign` FOREIGN KEY (`kategori_kinerja_id`) REFERENCES `kategori_kinerja` (`id`) ON DELETE SET NULL,
  CONSTRAINT `transaksi_kompetensi_id_foreign` FOREIGN KEY (`kompetensi_id`) REFERENCES `kompetensi` (`id`) ON DELETE SET NULL,
  CONSTRAINT `transaksi_pangkalan_id_foreign` FOREIGN KEY (`pangkalan_id`) REFERENCES `pangkalan` (`id`) ON DELETE SET NULL,
  CONSTRAINT `transaksi_performance_rating_id_foreign` FOREIGN KEY (`performance_rating_id`) REFERENCES `performance_rating` (`id`) ON DELETE SET NULL,
  CONSTRAINT `transaksi_tahun_penilaian_id_foreign` FOREIGN KEY (`tahun_penilaian_id`) REFERENCES `tahun_penilaian` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=839 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksi`
--

LOCK TABLES `transaksi` WRITE;
/*!40000 ALTER TABLE `transaksi` DISABLE KEYS */;
INSERT INTO `transaksi` VALUES
(32,'TRX-0258',37,3,2,20,14,NULL,70.00,NULL,'2026-06-08 19:08:44','2026-06-09 09:05:18'),
(33,'TRX-0259',37,3,2,21,14,NULL,70.00,NULL,'2026-06-08 19:08:44','2026-06-09 09:05:18'),
(34,'TRX-0260',37,3,2,25,14,NULL,70.00,NULL,'2026-06-08 19:08:44','2026-06-09 09:05:18'),
(35,'TRX-0261',37,3,2,22,15,NULL,75.00,NULL,'2026-06-08 19:08:44','2026-06-09 09:05:18'),
(36,'TRX-0262',37,3,2,23,15,NULL,75.00,NULL,'2026-06-08 19:08:44','2026-06-09 09:05:18'),
(37,'TRX-0263',37,3,2,24,15,NULL,65.00,NULL,'2026-06-08 19:08:44','2026-06-09 09:05:18'),
(38,'TRX-0264',37,3,2,29,16,NULL,70.00,NULL,'2026-06-08 19:08:44','2026-06-09 09:05:18'),
(39,'TRX-0265',37,3,2,26,16,NULL,70.00,NULL,'2026-06-08 19:08:44','2026-06-09 09:05:18'),
(40,'TRX-0266',37,3,2,27,16,NULL,70.00,NULL,'2026-06-08 19:08:44','2026-06-09 09:05:18'),
(41,'TRX-0267',37,3,2,28,16,NULL,70.00,NULL,'2026-06-08 19:08:44','2026-06-09 09:05:18'),
(42,'TRX-0568',53,3,2,20,14,NULL,85.00,NULL,'2026-06-08 19:10:45','2026-06-09 20:12:23'),
(43,'TRX-0569',53,3,2,21,14,NULL,80.00,NULL,'2026-06-08 19:10:45','2026-06-09 20:12:23'),
(44,'TRX-0570',53,3,2,25,14,NULL,80.00,NULL,'2026-06-08 19:10:45','2026-06-09 20:12:23'),
(45,'TRX-0571',53,3,2,22,15,NULL,80.00,NULL,'2026-06-08 19:10:45','2026-06-09 20:12:23'),
(46,'TRX-0572',53,3,2,23,15,NULL,85.00,NULL,'2026-06-08 19:10:45','2026-06-09 20:12:23'),
(47,'TRX-0573',53,3,2,24,15,NULL,75.00,NULL,'2026-06-08 19:10:45','2026-06-09 20:12:23'),
(48,'TRX-0574',53,3,2,29,16,NULL,85.00,NULL,'2026-06-08 19:10:45','2026-06-09 20:12:23'),
(49,'TRX-0575',53,3,2,26,16,NULL,80.00,NULL,'2026-06-08 19:10:45','2026-06-09 20:12:23'),
(50,'TRX-0576',53,3,2,27,16,NULL,80.00,NULL,'2026-06-08 19:10:45','2026-06-09 20:12:23'),
(51,'TRX-0577',53,3,2,28,16,NULL,75.00,NULL,'2026-06-08 19:10:45','2026-06-09 20:12:23'),
(52,'TRX-0052',52,3,2,20,14,NULL,85.00,NULL,'2026-06-08 19:12:26','2026-06-08 19:12:26'),
(53,'TRX-0053',52,3,2,21,14,NULL,80.00,NULL,'2026-06-08 19:12:26','2026-06-08 19:12:26'),
(54,'TRX-0054',52,3,2,25,14,NULL,85.00,NULL,'2026-06-08 19:12:26','2026-06-08 19:12:26'),
(55,'TRX-0055',52,3,2,22,15,NULL,85.00,NULL,'2026-06-08 19:12:26','2026-06-08 19:12:26'),
(56,'TRX-0056',52,3,2,23,15,NULL,80.00,NULL,'2026-06-08 19:12:26','2026-06-08 19:12:26'),
(57,'TRX-0057',52,3,2,24,15,NULL,85.00,NULL,'2026-06-08 19:12:26','2026-06-08 19:12:26'),
(58,'TRX-0058',52,3,2,29,16,NULL,80.00,NULL,'2026-06-08 19:12:26','2026-06-08 19:12:26'),
(59,'TRX-0059',52,3,2,26,16,NULL,80.00,NULL,'2026-06-08 19:12:26','2026-06-08 19:12:26'),
(60,'TRX-0060',52,3,2,27,16,NULL,80.00,NULL,'2026-06-08 19:12:26','2026-06-08 19:12:26'),
(61,'TRX-0061',52,3,2,28,16,NULL,78.00,NULL,'2026-06-08 19:12:26','2026-06-08 19:12:26'),
(62,'TRX-0062',42,12,2,20,14,NULL,80.00,NULL,'2026-06-08 21:10:30','2026-06-08 21:10:30'),
(63,'TRX-0063',42,12,2,21,14,NULL,70.00,NULL,'2026-06-08 21:10:30','2026-06-08 21:10:30'),
(64,'TRX-0064',42,12,2,25,14,NULL,73.00,NULL,'2026-06-08 21:10:30','2026-06-08 21:10:30'),
(65,'TRX-0065',42,12,2,22,15,NULL,75.00,NULL,'2026-06-08 21:10:30','2026-06-08 21:10:30'),
(66,'TRX-0066',42,12,2,23,15,NULL,70.00,NULL,'2026-06-08 21:10:30','2026-06-08 21:10:30'),
(67,'TRX-0067',42,12,2,24,15,NULL,76.00,NULL,'2026-06-08 21:10:30','2026-06-08 21:10:30'),
(68,'TRX-0068',42,12,2,29,16,NULL,75.00,NULL,'2026-06-08 21:10:30','2026-06-08 21:10:30'),
(69,'TRX-0069',42,12,2,26,16,NULL,74.00,NULL,'2026-06-08 21:10:30','2026-06-08 21:10:30'),
(70,'TRX-0070',42,12,2,27,16,NULL,73.00,NULL,'2026-06-08 21:10:30','2026-06-08 21:10:30'),
(71,'TRX-0071',42,12,2,28,16,NULL,75.00,NULL,'2026-06-08 21:10:30','2026-06-08 21:10:30'),
(72,'TRX-0598',37,16,2,15,17,NULL,76.00,NULL,'2026-06-08 21:14:17','2026-06-10 02:42:15'),
(73,'TRX-0599',37,16,2,19,17,NULL,75.00,NULL,'2026-06-08 21:14:17','2026-06-10 02:42:15'),
(74,'TRX-0074',32,16,2,15,17,NULL,80.00,NULL,'2026-06-08 21:15:42','2026-06-08 21:15:42'),
(75,'TRX-0075',32,16,2,19,17,NULL,85.00,NULL,'2026-06-08 21:15:42','2026-06-08 21:15:42'),
(76,'TRX-0076',35,16,2,15,17,NULL,80.00,NULL,'2026-06-08 21:16:49','2026-06-08 21:16:49'),
(77,'TRX-0077',35,16,2,19,17,NULL,80.00,NULL,'2026-06-08 21:16:49','2026-06-08 21:16:49'),
(78,'TRX-0078',36,16,2,15,17,NULL,80.00,NULL,'2026-06-08 21:17:33','2026-06-08 21:17:33'),
(79,'TRX-0079',36,16,2,19,17,NULL,82.00,NULL,'2026-06-08 21:17:33','2026-06-08 21:17:33'),
(80,'TRX-0080',54,16,2,15,17,NULL,90.00,NULL,'2026-06-08 21:18:32','2026-06-08 21:18:32'),
(81,'TRX-0081',54,16,2,19,17,NULL,87.00,NULL,'2026-06-08 21:18:32','2026-06-08 21:18:32'),
(82,'TRX-0082',55,16,2,15,17,NULL,90.00,NULL,'2026-06-08 21:19:19','2026-06-08 21:19:19'),
(83,'TRX-0083',55,16,2,19,17,NULL,86.00,NULL,'2026-06-08 21:19:19','2026-06-08 21:19:19'),
(84,'TRX-0084',38,16,2,15,17,NULL,88.00,NULL,'2026-06-08 21:20:02','2026-06-08 21:20:02'),
(85,'TRX-0085',38,16,2,19,17,NULL,85.00,NULL,'2026-06-08 21:20:02','2026-06-08 21:20:02'),
(86,'TRX-0086',39,16,2,15,17,NULL,87.00,NULL,'2026-06-08 21:21:13','2026-06-08 21:21:13'),
(87,'TRX-0087',39,16,2,19,17,NULL,86.00,NULL,'2026-06-08 21:21:13','2026-06-08 21:21:13'),
(88,'TRX-0088',40,16,2,15,17,NULL,88.00,NULL,'2026-06-08 21:22:27','2026-06-08 21:22:27'),
(89,'TRX-0089',40,16,2,19,17,NULL,85.00,NULL,'2026-06-08 21:22:27','2026-06-08 21:22:27'),
(90,'TRX-0090',41,16,2,15,17,NULL,89.00,NULL,'2026-06-08 21:23:09','2026-06-08 21:23:09'),
(91,'TRX-0091',41,16,2,19,17,NULL,87.00,NULL,'2026-06-08 21:23:09','2026-06-08 21:23:09'),
(92,'TRX-0092',56,16,2,15,17,NULL,88.00,NULL,'2026-06-08 21:23:55','2026-06-08 21:23:55'),
(93,'TRX-0093',56,16,2,19,17,NULL,85.00,NULL,'2026-06-08 21:23:55','2026-06-08 21:23:55'),
(94,'TRX-0094',43,16,2,15,17,NULL,70.00,NULL,'2026-06-08 21:24:39','2026-06-08 21:24:39'),
(95,'TRX-0095',43,16,2,19,17,NULL,72.00,NULL,'2026-06-08 21:24:39','2026-06-08 21:24:39'),
(96,'TRX-0096',57,16,2,15,17,NULL,89.00,NULL,'2026-06-08 21:25:27','2026-06-08 21:25:27'),
(97,'TRX-0097',57,16,2,19,17,NULL,87.00,NULL,'2026-06-08 21:25:27','2026-06-08 21:25:27'),
(98,'TRX-0356',58,16,2,15,17,NULL,87.00,NULL,'2026-06-08 21:26:01','2026-06-09 18:52:35'),
(99,'TRX-0357',58,16,2,19,17,NULL,87.00,NULL,'2026-06-08 21:26:01','2026-06-09 18:52:35'),
(100,'TRX-0100',59,16,2,15,17,NULL,89.00,NULL,'2026-06-08 21:26:40','2026-06-08 21:26:40'),
(101,'TRX-0101',59,16,2,19,17,NULL,88.00,NULL,'2026-06-08 21:26:40','2026-06-08 21:26:40'),
(102,'TRX-0102',34,16,2,15,17,NULL,90.00,NULL,'2026-06-08 21:27:09','2026-06-08 21:27:09'),
(103,'TRX-0103',34,16,2,19,17,NULL,89.00,NULL,'2026-06-08 21:27:10','2026-06-08 21:27:10'),
(104,'TRX-0104',42,16,2,15,17,NULL,80.00,NULL,'2026-06-08 21:27:54','2026-06-08 21:27:54'),
(105,'TRX-0105',42,16,2,19,17,NULL,80.00,NULL,'2026-06-08 21:27:54','2026-06-08 21:27:54'),
(106,'TRX-0875',60,16,2,15,17,NULL,87.00,NULL,'2026-06-08 21:28:54','2026-06-10 05:33:44'),
(107,'TRX-0876',60,16,2,19,17,NULL,88.00,NULL,'2026-06-08 21:28:54','2026-06-10 05:33:44'),
(108,'TRX-0108',45,16,2,15,17,NULL,85.00,NULL,'2026-06-08 21:29:40','2026-06-08 21:29:40'),
(109,'TRX-0109',45,16,2,19,17,NULL,87.00,NULL,'2026-06-08 21:29:40','2026-06-08 21:29:40'),
(110,'TRX-0110',46,16,2,15,17,NULL,87.00,NULL,'2026-06-08 21:30:12','2026-06-08 21:30:12'),
(111,'TRX-0111',46,16,2,19,17,NULL,86.00,NULL,'2026-06-08 21:30:13','2026-06-08 21:30:13'),
(112,'TRX-0112',47,16,2,15,17,NULL,78.00,NULL,'2026-06-08 21:31:05','2026-06-08 21:31:05'),
(113,'TRX-0113',47,16,2,19,17,NULL,76.00,NULL,'2026-06-08 21:31:05','2026-06-08 21:31:05'),
(114,'TRX-0114',49,16,2,15,17,NULL,80.00,NULL,'2026-06-08 21:31:57','2026-06-08 21:31:57'),
(115,'TRX-0115',49,16,2,19,17,NULL,80.00,NULL,'2026-06-08 21:31:57','2026-06-08 21:31:57'),
(116,'TRX-0116',50,16,2,15,17,NULL,80.00,NULL,'2026-06-08 21:32:32','2026-06-08 21:32:32'),
(117,'TRX-0117',50,16,2,19,17,NULL,80.00,NULL,'2026-06-08 21:32:32','2026-06-08 21:32:32'),
(118,'TRX-1031',56,4,2,20,14,NULL,84.00,NULL,'2026-06-08 22:24:04','2026-06-10 08:06:12'),
(119,'TRX-1032',56,4,2,21,14,NULL,80.00,NULL,'2026-06-08 22:24:04','2026-06-10 08:06:12'),
(120,'TRX-1033',56,4,2,25,14,NULL,80.00,NULL,'2026-06-08 22:24:04','2026-06-10 08:06:12'),
(121,'TRX-1034',56,4,2,22,15,NULL,85.00,NULL,'2026-06-08 22:24:04','2026-06-10 08:06:12'),
(122,'TRX-1035',56,4,2,23,15,NULL,84.00,NULL,'2026-06-08 22:24:04','2026-06-10 08:06:12'),
(123,'TRX-1036',56,4,2,24,15,NULL,80.00,NULL,'2026-06-08 22:24:04','2026-06-10 08:06:12'),
(124,'TRX-1037',56,4,2,29,16,NULL,80.00,NULL,'2026-06-08 22:24:04','2026-06-10 08:06:12'),
(125,'TRX-1038',56,4,2,26,16,NULL,80.00,NULL,'2026-06-08 22:24:04','2026-06-10 08:06:12'),
(126,'TRX-1039',56,4,2,27,16,NULL,80.00,NULL,'2026-06-08 22:24:04','2026-06-10 08:06:12'),
(127,'TRX-1040',56,4,2,28,16,NULL,80.00,NULL,'2026-06-08 22:24:04','2026-06-10 08:06:12'),
(128,'TRX-0840',58,4,2,20,14,NULL,75.00,NULL,'2026-06-08 23:00:59','2026-06-10 05:23:43'),
(129,'TRX-0841',58,4,2,21,14,NULL,75.00,NULL,'2026-06-08 23:00:59','2026-06-10 05:23:43'),
(130,'TRX-0842',58,4,2,25,14,NULL,73.00,NULL,'2026-06-08 23:00:59','2026-06-10 05:23:43'),
(131,'TRX-0843',58,4,2,22,15,NULL,73.00,NULL,'2026-06-08 23:00:59','2026-06-10 05:23:43'),
(132,'TRX-0844',58,4,2,23,15,NULL,78.00,NULL,'2026-06-08 23:00:59','2026-06-10 05:23:43'),
(133,'TRX-0845',58,4,2,24,15,NULL,75.00,NULL,'2026-06-08 23:00:59','2026-06-10 05:23:43'),
(134,'TRX-0846',58,4,2,29,16,NULL,73.00,NULL,'2026-06-08 23:00:59','2026-06-10 05:23:43'),
(135,'TRX-0847',58,4,2,26,16,NULL,72.00,NULL,'2026-06-08 23:00:59','2026-06-10 05:23:43'),
(136,'TRX-0848',58,4,2,27,16,NULL,80.00,NULL,'2026-06-08 23:00:59','2026-06-10 05:23:43'),
(137,'TRX-0849',58,4,2,28,16,NULL,75.00,NULL,'2026-06-08 23:00:59','2026-06-10 05:23:43'),
(138,'TRX-0877',60,4,2,20,14,NULL,80.00,NULL,'2026-06-08 23:08:01','2026-06-10 05:39:18'),
(139,'TRX-0878',60,4,2,21,14,NULL,82.00,NULL,'2026-06-08 23:08:01','2026-06-10 05:39:18'),
(140,'TRX-0879',60,4,2,25,14,NULL,78.00,NULL,'2026-06-08 23:08:01','2026-06-10 05:39:18'),
(141,'TRX-0880',60,4,2,22,15,NULL,80.00,NULL,'2026-06-08 23:08:01','2026-06-10 05:39:18'),
(142,'TRX-0881',60,4,2,23,15,NULL,83.00,NULL,'2026-06-08 23:08:01','2026-06-10 05:39:18'),
(143,'TRX-0882',60,4,2,24,15,NULL,80.00,NULL,'2026-06-08 23:08:01','2026-06-10 05:39:18'),
(144,'TRX-0883',60,4,2,29,16,NULL,75.00,NULL,'2026-06-08 23:08:01','2026-06-10 05:39:18'),
(145,'TRX-0884',60,4,2,26,16,NULL,80.00,NULL,'2026-06-08 23:08:01','2026-06-10 05:39:18'),
(146,'TRX-0885',60,4,2,27,16,NULL,80.00,NULL,'2026-06-08 23:08:01','2026-06-10 05:39:18'),
(147,'TRX-0886',60,4,2,28,16,NULL,75.00,NULL,'2026-06-08 23:08:01','2026-06-10 05:39:18'),
(148,'TRX-0148',53,6,2,20,14,NULL,87.00,NULL,'2026-06-09 07:31:37','2026-06-09 07:31:37'),
(149,'TRX-0149',53,6,2,21,14,NULL,86.00,NULL,'2026-06-09 07:31:37','2026-06-09 07:31:37'),
(150,'TRX-0150',53,6,2,25,14,NULL,87.00,NULL,'2026-06-09 07:31:37','2026-06-09 07:31:37'),
(151,'TRX-0151',53,6,2,22,15,NULL,88.00,NULL,'2026-06-09 07:31:37','2026-06-09 07:31:37'),
(152,'TRX-0152',53,6,2,23,15,NULL,89.00,NULL,'2026-06-09 07:31:37','2026-06-09 07:31:37'),
(153,'TRX-0153',53,6,2,24,15,NULL,88.00,NULL,'2026-06-09 07:31:37','2026-06-09 07:31:37'),
(154,'TRX-0154',53,6,2,29,16,NULL,87.00,NULL,'2026-06-09 07:31:37','2026-06-09 07:31:37'),
(155,'TRX-0155',53,6,2,26,16,NULL,89.00,NULL,'2026-06-09 07:31:37','2026-06-09 07:31:37'),
(156,'TRX-0156',53,6,2,27,16,NULL,88.00,NULL,'2026-06-09 07:31:37','2026-06-09 07:31:37'),
(157,'TRX-0157',53,6,2,28,16,NULL,87.00,NULL,'2026-06-09 07:31:37','2026-06-09 07:31:37'),
(158,'TRX-0158',32,7,2,20,14,NULL,85.00,NULL,'2026-06-09 08:02:34','2026-06-09 08:02:34'),
(159,'TRX-0159',32,7,2,21,14,NULL,90.00,NULL,'2026-06-09 08:02:34','2026-06-09 08:02:34'),
(160,'TRX-0160',32,7,2,25,14,NULL,85.00,NULL,'2026-06-09 08:02:34','2026-06-09 08:02:34'),
(161,'TRX-0161',32,7,2,22,15,NULL,90.00,NULL,'2026-06-09 08:02:34','2026-06-09 08:02:34'),
(162,'TRX-0162',32,7,2,23,15,NULL,90.00,NULL,'2026-06-09 08:03:47','2026-06-09 08:03:47'),
(163,'TRX-0163',32,7,2,24,15,NULL,85.00,NULL,'2026-06-09 08:03:47','2026-06-09 08:03:47'),
(164,'TRX-0164',32,7,2,29,16,NULL,85.00,NULL,'2026-06-09 08:03:47','2026-06-09 08:03:47'),
(165,'TRX-0165',32,7,2,26,16,NULL,87.00,NULL,'2026-06-09 08:03:47','2026-06-09 08:03:47'),
(166,'TRX-0166',32,7,2,27,16,NULL,85.00,NULL,'2026-06-09 08:03:47','2026-06-09 08:03:47'),
(167,'TRX-0167',32,7,2,28,16,NULL,85.00,NULL,'2026-06-09 08:03:47','2026-06-09 08:03:47'),
(168,'TRX-0168',35,7,2,20,14,NULL,84.00,NULL,'2026-06-09 08:20:27','2026-06-09 08:20:27'),
(169,'TRX-0169',35,7,2,21,14,NULL,85.00,NULL,'2026-06-09 08:20:27','2026-06-09 08:20:27'),
(170,'TRX-0170',35,7,2,25,14,NULL,88.00,NULL,'2026-06-09 08:20:27','2026-06-09 08:20:27'),
(171,'TRX-0171',35,7,2,22,15,NULL,86.00,NULL,'2026-06-09 08:20:27','2026-06-09 08:20:27'),
(172,'TRX-0172',35,7,2,23,15,NULL,86.00,NULL,'2026-06-09 08:20:27','2026-06-09 08:20:27'),
(173,'TRX-0173',35,7,2,24,15,NULL,80.00,NULL,'2026-06-09 08:20:27','2026-06-09 08:20:27'),
(174,'TRX-0174',35,7,2,29,16,NULL,85.00,NULL,'2026-06-09 08:20:27','2026-06-09 08:20:27'),
(175,'TRX-0175',35,7,2,26,16,NULL,85.00,NULL,'2026-06-09 08:20:27','2026-06-09 08:20:27'),
(176,'TRX-0176',35,7,2,27,16,NULL,80.00,NULL,'2026-06-09 08:20:27','2026-06-09 08:20:27'),
(177,'TRX-0177',35,7,2,28,16,NULL,85.00,NULL,'2026-06-09 08:20:27','2026-06-09 08:20:27'),
(178,'TRX-0178',36,7,2,20,14,NULL,85.00,NULL,'2026-06-09 08:28:23','2026-06-09 08:28:23'),
(179,'TRX-0179',36,7,2,21,14,NULL,86.00,NULL,'2026-06-09 08:28:23','2026-06-09 08:28:23'),
(180,'TRX-0180',36,7,2,25,14,NULL,80.00,NULL,'2026-06-09 08:28:23','2026-06-09 08:28:23'),
(181,'TRX-0181',36,7,2,22,15,NULL,86.00,NULL,'2026-06-09 08:28:23','2026-06-09 08:28:23'),
(182,'TRX-0182',36,7,2,23,15,NULL,85.00,NULL,'2026-06-09 08:28:23','2026-06-09 08:28:23'),
(183,'TRX-0183',36,7,2,24,15,NULL,80.00,NULL,'2026-06-09 08:28:23','2026-06-09 08:28:23'),
(184,'TRX-0184',36,7,2,29,16,NULL,80.00,NULL,'2026-06-09 08:28:23','2026-06-09 08:28:23'),
(185,'TRX-0185',36,7,2,26,16,NULL,84.00,NULL,'2026-06-09 08:28:23','2026-06-09 08:28:23'),
(186,'TRX-0186',36,7,2,27,16,NULL,82.00,NULL,'2026-06-09 08:28:23','2026-06-09 08:28:23'),
(187,'TRX-0187',36,7,2,28,16,NULL,80.00,NULL,'2026-06-09 08:28:23','2026-06-09 08:28:23'),
(188,'TRX-0188',37,7,2,20,14,NULL,82.00,NULL,'2026-06-09 08:38:05','2026-06-09 08:38:05'),
(189,'TRX-0189',37,7,2,21,14,NULL,80.00,NULL,'2026-06-09 08:38:05','2026-06-09 08:38:05'),
(190,'TRX-0190',37,7,2,25,14,NULL,75.00,NULL,'2026-06-09 08:38:05','2026-06-09 08:38:05'),
(191,'TRX-0191',37,7,2,22,15,NULL,77.00,NULL,'2026-06-09 08:38:05','2026-06-09 08:38:05'),
(192,'TRX-0192',37,7,2,23,15,NULL,80.00,NULL,'2026-06-09 08:38:05','2026-06-09 08:38:05'),
(193,'TRX-0193',37,7,2,24,15,NULL,75.00,NULL,'2026-06-09 08:38:05','2026-06-09 08:38:05'),
(194,'TRX-0194',37,7,2,29,16,NULL,77.00,NULL,'2026-06-09 08:38:05','2026-06-09 08:38:05'),
(195,'TRX-0195',37,7,2,26,16,NULL,76.00,NULL,'2026-06-09 08:38:05','2026-06-09 08:38:05'),
(196,'TRX-0196',37,7,2,27,16,NULL,75.00,NULL,'2026-06-09 08:38:05','2026-06-09 08:38:05'),
(197,'TRX-0197',37,7,2,28,16,NULL,76.00,NULL,'2026-06-09 08:38:05','2026-06-09 08:38:05'),
(198,'TRX-0198',42,7,2,20,14,NULL,82.00,NULL,'2026-06-09 08:42:44','2026-06-09 08:42:44'),
(199,'TRX-0199',42,7,2,21,14,NULL,85.00,NULL,'2026-06-09 08:42:44','2026-06-09 08:42:44'),
(200,'TRX-0200',42,7,2,25,14,NULL,80.00,NULL,'2026-06-09 08:42:44','2026-06-09 08:42:44'),
(201,'TRX-0201',42,7,2,22,15,NULL,88.00,NULL,'2026-06-09 08:42:44','2026-06-09 08:42:44'),
(202,'TRX-0202',42,7,2,23,15,NULL,80.00,NULL,'2026-06-09 08:42:44','2026-06-09 08:42:44'),
(203,'TRX-0203',42,7,2,24,15,NULL,80.00,NULL,'2026-06-09 08:42:44','2026-06-09 08:42:44'),
(204,'TRX-0204',42,7,2,29,16,NULL,85.00,NULL,'2026-06-09 08:42:44','2026-06-09 08:42:44'),
(205,'TRX-0205',42,7,2,26,16,NULL,80.00,NULL,'2026-06-09 08:42:44','2026-06-09 08:42:44'),
(206,'TRX-0206',42,7,2,27,16,NULL,82.00,NULL,'2026-06-09 08:42:44','2026-06-09 08:42:44'),
(207,'TRX-0207',42,7,2,28,16,NULL,90.00,NULL,'2026-06-09 08:42:44','2026-06-09 08:42:44'),
(208,'TRX-0208',43,7,2,20,14,NULL,80.00,NULL,'2026-06-09 08:45:45','2026-06-09 08:45:45'),
(209,'TRX-0209',43,7,2,21,14,NULL,80.00,NULL,'2026-06-09 08:45:45','2026-06-09 08:45:45'),
(210,'TRX-0210',43,7,2,25,14,NULL,76.00,NULL,'2026-06-09 08:45:45','2026-06-09 08:45:45'),
(211,'TRX-0211',43,7,2,22,15,NULL,75.00,NULL,'2026-06-09 08:45:45','2026-06-09 08:45:45'),
(212,'TRX-0212',43,7,2,23,15,NULL,75.00,NULL,'2026-06-09 08:45:45','2026-06-09 08:45:45'),
(213,'TRX-0213',43,7,2,24,15,NULL,77.00,NULL,'2026-06-09 08:45:45','2026-06-09 08:45:45'),
(214,'TRX-0214',43,7,2,29,16,NULL,80.00,NULL,'2026-06-09 08:45:45','2026-06-09 08:45:45'),
(215,'TRX-0215',43,7,2,26,16,NULL,75.00,NULL,'2026-06-09 08:45:45','2026-06-09 08:45:45'),
(216,'TRX-0216',43,7,2,27,16,NULL,75.00,NULL,'2026-06-09 08:45:45','2026-06-09 08:45:45'),
(217,'TRX-0217',43,7,2,28,16,NULL,76.00,NULL,'2026-06-09 08:45:45','2026-06-09 08:45:45'),
(218,'TRX-0218',45,7,2,20,14,NULL,85.00,NULL,'2026-06-09 08:50:01','2026-06-09 08:50:01'),
(219,'TRX-0219',45,7,2,21,14,NULL,90.00,NULL,'2026-06-09 08:50:01','2026-06-09 08:50:01'),
(220,'TRX-0220',45,7,2,25,14,NULL,90.00,NULL,'2026-06-09 08:50:01','2026-06-09 08:50:01'),
(221,'TRX-0221',45,7,2,22,15,NULL,90.00,NULL,'2026-06-09 08:50:01','2026-06-09 08:50:01'),
(222,'TRX-0222',45,7,2,23,15,NULL,88.00,NULL,'2026-06-09 08:50:01','2026-06-09 08:50:01'),
(223,'TRX-0223',45,7,2,24,15,NULL,86.00,NULL,'2026-06-09 08:50:01','2026-06-09 08:50:01'),
(224,'TRX-0224',45,7,2,29,16,NULL,85.00,NULL,'2026-06-09 08:50:01','2026-06-09 08:50:01'),
(225,'TRX-0225',45,7,2,26,16,NULL,86.00,NULL,'2026-06-09 08:50:01','2026-06-09 08:50:01'),
(226,'TRX-0226',45,7,2,27,16,NULL,85.00,NULL,'2026-06-09 08:50:02','2026-06-09 08:50:02'),
(227,'TRX-0227',45,7,2,28,16,NULL,85.00,NULL,'2026-06-09 08:50:02','2026-06-09 08:50:02'),
(228,'TRX-0228',47,7,2,20,14,NULL,85.00,NULL,'2026-06-09 08:53:08','2026-06-09 08:53:08'),
(229,'TRX-0229',47,7,2,21,14,NULL,85.00,NULL,'2026-06-09 08:53:08','2026-06-09 08:53:08'),
(230,'TRX-0230',47,7,2,25,14,NULL,85.00,NULL,'2026-06-09 08:53:08','2026-06-09 08:53:08'),
(231,'TRX-0231',47,7,2,22,15,NULL,85.00,NULL,'2026-06-09 08:53:08','2026-06-09 08:53:08'),
(232,'TRX-0232',47,7,2,23,15,NULL,86.00,NULL,'2026-06-09 08:53:08','2026-06-09 08:53:08'),
(233,'TRX-0233',47,7,2,24,15,NULL,85.00,NULL,'2026-06-09 08:53:08','2026-06-09 08:53:08'),
(234,'TRX-0234',47,7,2,29,16,NULL,85.00,NULL,'2026-06-09 08:53:08','2026-06-09 08:53:08'),
(235,'TRX-0235',47,7,2,26,16,NULL,86.00,NULL,'2026-06-09 08:53:08','2026-06-09 08:53:08'),
(236,'TRX-0236',47,7,2,27,16,NULL,80.00,NULL,'2026-06-09 08:53:08','2026-06-09 08:53:08'),
(237,'TRX-0237',47,7,2,28,16,NULL,80.00,NULL,'2026-06-09 08:53:08','2026-06-09 08:53:08'),
(238,'TRX-0238',49,7,2,20,14,NULL,85.00,NULL,'2026-06-09 08:55:27','2026-06-09 08:55:27'),
(239,'TRX-0239',49,7,2,21,14,NULL,90.00,NULL,'2026-06-09 08:55:27','2026-06-09 08:55:27'),
(240,'TRX-0240',49,7,2,25,14,NULL,87.00,NULL,'2026-06-09 08:55:27','2026-06-09 08:55:27'),
(241,'TRX-0241',49,7,2,22,15,NULL,88.00,NULL,'2026-06-09 08:55:27','2026-06-09 08:55:27'),
(242,'TRX-0242',49,7,2,23,15,NULL,85.00,NULL,'2026-06-09 08:55:27','2026-06-09 08:55:27'),
(243,'TRX-0243',49,7,2,24,15,NULL,85.00,NULL,'2026-06-09 08:55:27','2026-06-09 08:55:27'),
(244,'TRX-0244',49,7,2,29,16,NULL,80.00,NULL,'2026-06-09 08:55:27','2026-06-09 08:55:27'),
(245,'TRX-0245',49,7,2,26,16,NULL,88.00,NULL,'2026-06-09 08:55:27','2026-06-09 08:55:27'),
(246,'TRX-0246',49,7,2,27,16,NULL,85.00,NULL,'2026-06-09 08:55:27','2026-06-09 08:55:27'),
(247,'TRX-0247',49,7,2,28,16,NULL,80.00,NULL,'2026-06-09 08:55:27','2026-06-09 08:55:27'),
(248,'TRX-0248',50,7,2,20,14,NULL,85.00,NULL,'2026-06-09 08:57:56','2026-06-09 08:57:56'),
(249,'TRX-0249',50,7,2,21,14,NULL,88.00,NULL,'2026-06-09 08:57:56','2026-06-09 08:57:56'),
(250,'TRX-0250',50,7,2,25,14,NULL,90.00,NULL,'2026-06-09 08:57:56','2026-06-09 08:57:56'),
(251,'TRX-0251',50,7,2,22,15,NULL,90.00,NULL,'2026-06-09 08:57:56','2026-06-09 08:57:56'),
(252,'TRX-0252',50,7,2,23,15,NULL,88.00,NULL,'2026-06-09 08:57:56','2026-06-09 08:57:56'),
(253,'TRX-0253',50,7,2,24,15,NULL,88.00,NULL,'2026-06-09 08:57:56','2026-06-09 08:57:56'),
(254,'TRX-0254',50,7,2,29,16,NULL,85.00,NULL,'2026-06-09 08:57:56','2026-06-09 08:57:56'),
(255,'TRX-0255',50,7,2,26,16,NULL,88.00,NULL,'2026-06-09 08:57:56','2026-06-09 08:57:56'),
(256,'TRX-0256',50,7,2,27,16,NULL,85.00,NULL,'2026-06-09 08:57:56','2026-06-09 08:57:56'),
(257,'TRX-0257',50,7,2,28,16,NULL,90.00,NULL,'2026-06-09 08:57:56','2026-06-09 08:57:56'),
(263,'TRX-0991',43,11,2,20,14,NULL,90.00,NULL,'2026-06-09 18:34:15','2026-06-10 07:52:50'),
(264,'TRX-0992',43,11,2,21,14,NULL,85.00,NULL,'2026-06-09 18:34:15','2026-06-10 07:52:50'),
(265,'TRX-0993',43,11,2,25,14,NULL,80.00,NULL,'2026-06-09 18:34:15','2026-06-10 07:52:50'),
(266,'TRX-0994',43,11,2,22,15,NULL,80.00,NULL,'2026-06-09 18:34:15','2026-06-10 07:52:50'),
(267,'TRX-0995',43,11,2,23,15,NULL,80.00,NULL,'2026-06-09 18:34:15','2026-06-10 07:52:50'),
(268,'TRX-0996',43,11,2,24,15,NULL,80.00,NULL,'2026-06-09 18:34:15','2026-06-10 07:52:50'),
(269,'TRX-0997',43,11,2,29,16,NULL,80.00,NULL,'2026-06-09 18:34:15','2026-06-10 07:52:50'),
(270,'TRX-0998',43,11,2,26,16,NULL,85.00,NULL,'2026-06-09 18:34:15','2026-06-10 07:52:50'),
(271,'TRX-0999',43,11,2,27,16,NULL,85.00,NULL,'2026-06-09 18:34:15','2026-06-10 07:52:50'),
(272,'TRX-1000',43,11,2,28,16,NULL,84.00,NULL,'2026-06-09 18:34:15','2026-06-10 07:52:50'),
(273,'TRX-0278',47,11,2,20,14,NULL,85.00,NULL,'2026-06-09 18:36:15','2026-06-09 18:36:15'),
(274,'TRX-0279',47,11,2,21,14,NULL,85.00,NULL,'2026-06-09 18:36:15','2026-06-09 18:36:15'),
(275,'TRX-0280',47,11,2,25,14,NULL,88.00,NULL,'2026-06-09 18:36:15','2026-06-09 18:36:15'),
(276,'TRX-0281',47,11,2,22,15,NULL,89.00,NULL,'2026-06-09 18:36:15','2026-06-09 18:36:15'),
(277,'TRX-0282',47,11,2,23,15,NULL,90.00,NULL,'2026-06-09 18:36:15','2026-06-09 18:36:15'),
(278,'TRX-0283',47,11,2,24,15,NULL,90.00,NULL,'2026-06-09 18:36:15','2026-06-09 18:36:15'),
(279,'TRX-0284',47,11,2,29,16,NULL,85.00,NULL,'2026-06-09 18:36:15','2026-06-09 18:36:15'),
(280,'TRX-0285',47,11,2,26,16,NULL,85.00,NULL,'2026-06-09 18:36:15','2026-06-09 18:36:15'),
(281,'TRX-0286',47,11,2,27,16,NULL,80.00,NULL,'2026-06-09 18:36:15','2026-06-09 18:36:15'),
(282,'TRX-0287',47,11,2,28,16,NULL,85.00,NULL,'2026-06-09 18:36:15','2026-06-09 18:36:15'),
(283,'TRX-0288',53,16,2,15,17,NULL,87.00,NULL,'2026-06-09 18:40:08','2026-06-09 18:40:08'),
(284,'TRX-0289',53,16,2,19,17,NULL,86.00,NULL,'2026-06-09 18:40:08','2026-06-09 18:40:08'),
(285,'TRX-0302',52,6,2,20,14,NULL,88.00,NULL,'2026-06-09 18:45:14','2026-06-09 18:45:14'),
(286,'TRX-0303',52,6,2,21,14,NULL,88.00,NULL,'2026-06-09 18:45:14','2026-06-09 18:45:14'),
(287,'TRX-0304',52,6,2,25,14,NULL,89.00,NULL,'2026-06-09 18:45:14','2026-06-09 18:45:14'),
(288,'TRX-0305',52,6,2,22,15,NULL,88.00,NULL,'2026-06-09 18:45:14','2026-06-09 18:45:14'),
(289,'TRX-0306',52,6,2,23,15,NULL,89.00,NULL,'2026-06-09 18:45:14','2026-06-09 18:45:14'),
(290,'TRX-0307',52,6,2,24,15,NULL,89.00,NULL,'2026-06-09 18:45:14','2026-06-09 18:45:14'),
(291,'TRX-0308',52,6,2,29,16,NULL,87.00,NULL,'2026-06-09 18:45:14','2026-06-09 18:45:14'),
(292,'TRX-0309',52,6,2,26,16,NULL,88.00,NULL,'2026-06-09 18:45:14','2026-06-09 18:45:14'),
(293,'TRX-0310',52,6,2,27,16,NULL,88.00,NULL,'2026-06-09 18:45:14','2026-06-09 18:45:14'),
(294,'TRX-0311',52,6,2,28,16,NULL,88.00,NULL,'2026-06-09 18:45:14','2026-06-09 18:45:14'),
(295,'TRX-0865',60,6,2,20,14,NULL,87.00,NULL,'2026-06-09 18:50:18','2026-06-10 05:32:29'),
(296,'TRX-0866',60,6,2,21,14,NULL,88.00,NULL,'2026-06-09 18:50:18','2026-06-10 05:32:29'),
(297,'TRX-0867',60,6,2,25,14,NULL,90.00,NULL,'2026-06-09 18:50:18','2026-06-10 05:32:29'),
(298,'TRX-0868',60,6,2,22,15,NULL,88.00,NULL,'2026-06-09 18:50:18','2026-06-10 05:32:29'),
(299,'TRX-0869',60,6,2,23,15,NULL,87.00,NULL,'2026-06-09 18:50:18','2026-06-10 05:32:29'),
(300,'TRX-0870',60,6,2,24,15,NULL,88.00,NULL,'2026-06-09 18:50:18','2026-06-10 05:32:29'),
(301,'TRX-0871',60,6,2,29,16,NULL,88.00,NULL,'2026-06-09 18:50:18','2026-06-10 05:32:29'),
(302,'TRX-0872',60,6,2,26,16,NULL,88.00,NULL,'2026-06-09 18:50:18','2026-06-10 05:32:29'),
(303,'TRX-0873',60,6,2,27,16,NULL,88.00,NULL,'2026-06-09 18:50:18','2026-06-10 05:32:29'),
(304,'TRX-0874',60,6,2,28,16,NULL,87.00,NULL,'2026-06-09 18:50:18','2026-06-10 05:32:29'),
(305,'TRX-0358',54,8,2,20,14,NULL,85.00,NULL,'2026-06-09 19:09:12','2026-06-09 19:09:12'),
(306,'TRX-0359',54,8,2,21,14,NULL,80.00,NULL,'2026-06-09 19:09:12','2026-06-09 19:09:12'),
(307,'TRX-0360',54,8,2,25,14,NULL,82.00,NULL,'2026-06-09 19:09:12','2026-06-09 19:09:12'),
(308,'TRX-0361',54,8,2,22,15,NULL,80.00,NULL,'2026-06-09 19:09:12','2026-06-09 19:09:12'),
(309,'TRX-0362',54,8,2,23,15,NULL,80.00,NULL,'2026-06-09 19:09:12','2026-06-09 19:09:12'),
(310,'TRX-0363',54,8,2,24,15,NULL,80.00,NULL,'2026-06-09 19:09:12','2026-06-09 19:09:12'),
(311,'TRX-0364',54,8,2,29,16,NULL,84.00,NULL,'2026-06-09 19:09:12','2026-06-09 19:09:12'),
(312,'TRX-0365',54,8,2,26,16,NULL,82.00,NULL,'2026-06-09 19:09:12','2026-06-09 19:09:12'),
(313,'TRX-0366',54,8,2,27,16,NULL,82.00,NULL,'2026-06-09 19:09:12','2026-06-09 19:09:12'),
(314,'TRX-0367',54,8,2,28,16,NULL,82.00,NULL,'2026-06-09 19:09:12','2026-06-09 19:09:12'),
(315,'TRX-0368',55,8,2,20,14,NULL,87.00,NULL,'2026-06-09 19:10:19','2026-06-09 19:10:19'),
(316,'TRX-0369',55,8,2,21,14,NULL,85.00,NULL,'2026-06-09 19:10:19','2026-06-09 19:10:19'),
(317,'TRX-0370',55,8,2,25,14,NULL,90.00,NULL,'2026-06-09 19:10:19','2026-06-09 19:10:19'),
(318,'TRX-0371',55,8,2,22,15,NULL,85.00,NULL,'2026-06-09 19:10:19','2026-06-09 19:10:19'),
(319,'TRX-0372',55,8,2,23,15,NULL,85.00,NULL,'2026-06-09 19:10:19','2026-06-09 19:10:19'),
(320,'TRX-0373',55,8,2,24,15,NULL,85.00,NULL,'2026-06-09 19:10:19','2026-06-09 19:10:19'),
(321,'TRX-0374',55,8,2,29,16,NULL,84.00,NULL,'2026-06-09 19:10:19','2026-06-09 19:10:19'),
(322,'TRX-0375',55,8,2,26,16,NULL,84.00,NULL,'2026-06-09 19:10:19','2026-06-09 19:10:19'),
(323,'TRX-0376',55,8,2,27,16,NULL,84.00,NULL,'2026-06-09 19:10:19','2026-06-09 19:10:19'),
(324,'TRX-0377',55,8,2,28,16,NULL,84.00,NULL,'2026-06-09 19:10:19','2026-06-09 19:10:19'),
(325,'TRX-0378',38,8,2,20,14,NULL,82.00,NULL,'2026-06-09 19:12:07','2026-06-09 19:12:07'),
(326,'TRX-0379',38,8,2,21,14,NULL,80.00,NULL,'2026-06-09 19:12:07','2026-06-09 19:12:07'),
(327,'TRX-0380',38,8,2,25,14,NULL,83.00,NULL,'2026-06-09 19:12:07','2026-06-09 19:12:07'),
(328,'TRX-0381',38,8,2,22,15,NULL,80.00,NULL,'2026-06-09 19:12:07','2026-06-09 19:12:07'),
(329,'TRX-0382',38,8,2,23,15,NULL,80.00,NULL,'2026-06-09 19:12:07','2026-06-09 19:12:07'),
(330,'TRX-0383',38,8,2,24,15,NULL,80.00,NULL,'2026-06-09 19:12:07','2026-06-09 19:12:07'),
(331,'TRX-0384',38,8,2,29,16,NULL,85.00,NULL,'2026-06-09 19:12:07','2026-06-09 19:12:07'),
(332,'TRX-0385',38,8,2,26,16,NULL,82.00,NULL,'2026-06-09 19:12:07','2026-06-09 19:12:07'),
(333,'TRX-0386',38,8,2,27,16,NULL,82.00,NULL,'2026-06-09 19:12:07','2026-06-09 19:12:07'),
(334,'TRX-0387',38,8,2,28,16,NULL,82.00,NULL,'2026-06-09 19:12:07','2026-06-09 19:12:07'),
(335,'TRX-0388',39,8,2,20,14,NULL,85.00,NULL,'2026-06-09 19:13:22','2026-06-09 19:13:22'),
(336,'TRX-0389',39,8,2,21,14,NULL,83.00,NULL,'2026-06-09 19:13:22','2026-06-09 19:13:22'),
(337,'TRX-0390',39,8,2,25,14,NULL,84.00,NULL,'2026-06-09 19:13:22','2026-06-09 19:13:22'),
(338,'TRX-0391',39,8,2,22,15,NULL,83.00,NULL,'2026-06-09 19:13:22','2026-06-09 19:13:22'),
(339,'TRX-0392',39,8,2,23,15,NULL,85.00,NULL,'2026-06-09 19:13:22','2026-06-09 19:13:22'),
(340,'TRX-0393',39,8,2,24,15,NULL,85.00,NULL,'2026-06-09 19:13:23','2026-06-09 19:13:23'),
(341,'TRX-0394',39,8,2,29,16,NULL,83.00,NULL,'2026-06-09 19:13:23','2026-06-09 19:13:23'),
(342,'TRX-0395',39,8,2,26,16,NULL,84.00,NULL,'2026-06-09 19:13:23','2026-06-09 19:13:23'),
(343,'TRX-0396',39,8,2,27,16,NULL,83.00,NULL,'2026-06-09 19:13:23','2026-06-09 19:13:23'),
(344,'TRX-0397',39,8,2,28,16,NULL,83.00,NULL,'2026-06-09 19:13:23','2026-06-09 19:13:23'),
(345,'TRX-0398',53,8,2,20,14,NULL,80.00,NULL,'2026-06-09 19:14:51','2026-06-09 19:14:51'),
(346,'TRX-0399',53,8,2,21,14,NULL,80.00,NULL,'2026-06-09 19:14:51','2026-06-09 19:14:51'),
(347,'TRX-0400',53,8,2,25,14,NULL,80.00,NULL,'2026-06-09 19:14:51','2026-06-09 19:14:51'),
(348,'TRX-0401',53,8,2,22,15,NULL,78.00,NULL,'2026-06-09 19:14:51','2026-06-09 19:14:51'),
(349,'TRX-0402',53,8,2,23,15,NULL,78.00,NULL,'2026-06-09 19:14:51','2026-06-09 19:14:51'),
(350,'TRX-0403',53,8,2,24,15,NULL,78.00,NULL,'2026-06-09 19:14:51','2026-06-09 19:14:51'),
(351,'TRX-0404',53,8,2,29,16,NULL,78.00,NULL,'2026-06-09 19:14:51','2026-06-09 19:14:51'),
(352,'TRX-0405',53,8,2,26,16,NULL,78.00,NULL,'2026-06-09 19:14:51','2026-06-09 19:14:51'),
(353,'TRX-0406',53,8,2,27,16,NULL,80.00,NULL,'2026-06-09 19:14:51','2026-06-09 19:14:51'),
(354,'TRX-0407',53,8,2,28,16,NULL,78.00,NULL,'2026-06-09 19:14:51','2026-06-09 19:14:51'),
(355,'TRX-0408',40,8,2,20,14,NULL,80.00,NULL,'2026-06-09 19:16:31','2026-06-09 19:16:31'),
(356,'TRX-0409',40,8,2,21,14,NULL,80.00,NULL,'2026-06-09 19:16:31','2026-06-09 19:16:31'),
(357,'TRX-0410',40,8,2,25,14,NULL,80.00,NULL,'2026-06-09 19:16:31','2026-06-09 19:16:31'),
(358,'TRX-0411',40,8,2,22,15,NULL,82.00,NULL,'2026-06-09 19:16:31','2026-06-09 19:16:31'),
(359,'TRX-0412',40,8,2,23,15,NULL,78.00,NULL,'2026-06-09 19:16:31','2026-06-09 19:16:31'),
(360,'TRX-0413',40,8,2,24,15,NULL,78.00,NULL,'2026-06-09 19:16:31','2026-06-09 19:16:31'),
(361,'TRX-0414',40,8,2,29,16,NULL,80.00,NULL,'2026-06-09 19:16:31','2026-06-09 19:16:31'),
(362,'TRX-0415',40,8,2,26,16,NULL,80.00,NULL,'2026-06-09 19:16:31','2026-06-09 19:16:31'),
(363,'TRX-0416',40,8,2,27,16,NULL,80.00,NULL,'2026-06-09 19:16:31','2026-06-09 19:16:31'),
(364,'TRX-0417',40,8,2,28,16,NULL,78.00,NULL,'2026-06-09 19:16:31','2026-06-09 19:16:31'),
(365,'TRX-0418',52,8,2,20,14,NULL,87.00,NULL,'2026-06-09 19:17:56','2026-06-09 19:17:56'),
(366,'TRX-0419',52,8,2,21,14,NULL,85.00,NULL,'2026-06-09 19:17:56','2026-06-09 19:17:56'),
(367,'TRX-0420',52,8,2,25,14,NULL,85.00,NULL,'2026-06-09 19:17:56','2026-06-09 19:17:56'),
(368,'TRX-0421',52,8,2,22,15,NULL,82.00,NULL,'2026-06-09 19:17:56','2026-06-09 19:17:56'),
(369,'TRX-0422',52,8,2,23,15,NULL,84.00,NULL,'2026-06-09 19:17:56','2026-06-09 19:17:56'),
(370,'TRX-0423',52,8,2,24,15,NULL,84.00,NULL,'2026-06-09 19:17:56','2026-06-09 19:17:56'),
(371,'TRX-0424',52,8,2,29,16,NULL,84.00,NULL,'2026-06-09 19:17:56','2026-06-09 19:17:56'),
(372,'TRX-0425',52,8,2,26,16,NULL,83.00,NULL,'2026-06-09 19:17:56','2026-06-09 19:17:56'),
(373,'TRX-0426',52,8,2,27,16,NULL,84.00,NULL,'2026-06-09 19:17:56','2026-06-09 19:17:56'),
(374,'TRX-0427',52,8,2,28,16,NULL,84.00,NULL,'2026-06-09 19:17:56','2026-06-09 19:17:56'),
(375,'TRX-0428',56,8,2,20,14,NULL,80.00,NULL,'2026-06-09 19:19:07','2026-06-09 19:19:07'),
(376,'TRX-0429',56,8,2,21,14,NULL,78.00,NULL,'2026-06-09 19:19:07','2026-06-09 19:19:07'),
(377,'TRX-0430',56,8,2,25,14,NULL,78.00,NULL,'2026-06-09 19:19:07','2026-06-09 19:19:07'),
(378,'TRX-0431',56,8,2,22,15,NULL,78.00,NULL,'2026-06-09 19:19:07','2026-06-09 19:19:07'),
(379,'TRX-0432',56,8,2,23,15,NULL,78.00,NULL,'2026-06-09 19:19:07','2026-06-09 19:19:07'),
(380,'TRX-0433',56,8,2,24,15,NULL,79.00,NULL,'2026-06-09 19:19:07','2026-06-09 19:19:07'),
(381,'TRX-0434',56,8,2,29,16,NULL,80.00,NULL,'2026-06-09 19:19:07','2026-06-09 19:19:07'),
(382,'TRX-0435',56,8,2,26,16,NULL,80.00,NULL,'2026-06-09 19:19:07','2026-06-09 19:19:07'),
(383,'TRX-0436',56,8,2,27,16,NULL,80.00,NULL,'2026-06-09 19:19:07','2026-06-09 19:19:07'),
(384,'TRX-0437',56,8,2,28,16,NULL,80.00,NULL,'2026-06-09 19:19:07','2026-06-09 19:19:07'),
(385,'TRX-0438',57,8,2,20,14,NULL,90.00,NULL,'2026-06-09 19:22:29','2026-06-09 19:22:29'),
(386,'TRX-0439',57,8,2,21,14,NULL,94.00,NULL,'2026-06-09 19:22:29','2026-06-09 19:22:29'),
(387,'TRX-0440',57,8,2,25,14,NULL,92.00,NULL,'2026-06-09 19:22:29','2026-06-09 19:22:29'),
(388,'TRX-0441',57,8,2,22,15,NULL,96.00,NULL,'2026-06-09 19:22:29','2026-06-09 19:22:29'),
(389,'TRX-0442',57,8,2,23,15,NULL,94.00,NULL,'2026-06-09 19:22:29','2026-06-09 19:22:29'),
(390,'TRX-0443',57,8,2,24,15,NULL,94.00,NULL,'2026-06-09 19:22:29','2026-06-09 19:22:29'),
(391,'TRX-0444',57,8,2,29,16,NULL,94.00,NULL,'2026-06-09 19:22:29','2026-06-09 19:22:29'),
(392,'TRX-0445',57,8,2,26,16,NULL,92.00,NULL,'2026-06-09 19:22:29','2026-06-09 19:22:29'),
(393,'TRX-0446',57,8,2,27,16,NULL,92.00,NULL,'2026-06-09 19:22:29','2026-06-09 19:22:29'),
(394,'TRX-0447',57,8,2,28,16,NULL,92.00,NULL,'2026-06-09 19:22:29','2026-06-09 19:22:29'),
(395,'TRX-0448',58,8,2,20,14,NULL,75.00,NULL,'2026-06-09 19:23:52','2026-06-09 19:23:52'),
(396,'TRX-0449',58,8,2,21,14,NULL,78.00,NULL,'2026-06-09 19:23:52','2026-06-09 19:23:52'),
(397,'TRX-0450',58,8,2,25,14,NULL,75.00,NULL,'2026-06-09 19:23:52','2026-06-09 19:23:52'),
(398,'TRX-0451',58,8,2,22,15,NULL,75.00,NULL,'2026-06-09 19:23:52','2026-06-09 19:23:52'),
(399,'TRX-0452',58,8,2,23,15,NULL,75.00,NULL,'2026-06-09 19:23:52','2026-06-09 19:23:52'),
(400,'TRX-0453',58,8,2,24,15,NULL,75.00,NULL,'2026-06-09 19:23:52','2026-06-09 19:23:52'),
(401,'TRX-0454',58,8,2,29,16,NULL,78.00,NULL,'2026-06-09 19:23:52','2026-06-09 19:23:52'),
(402,'TRX-0455',58,8,2,26,16,NULL,78.00,NULL,'2026-06-09 19:23:52','2026-06-09 19:23:52'),
(403,'TRX-0456',58,8,2,27,16,NULL,78.00,NULL,'2026-06-09 19:23:52','2026-06-09 19:23:52'),
(404,'TRX-0457',58,8,2,28,16,NULL,78.00,NULL,'2026-06-09 19:23:52','2026-06-09 19:23:52'),
(405,'TRX-0458',59,8,2,20,14,NULL,90.00,NULL,'2026-06-09 19:24:51','2026-06-09 19:24:51'),
(406,'TRX-0459',59,8,2,21,14,NULL,90.00,NULL,'2026-06-09 19:24:51','2026-06-09 19:24:51'),
(407,'TRX-0460',59,8,2,25,14,NULL,90.00,NULL,'2026-06-09 19:24:51','2026-06-09 19:24:51'),
(408,'TRX-0461',59,8,2,22,15,NULL,95.00,NULL,'2026-06-09 19:24:51','2026-06-09 19:24:51'),
(409,'TRX-0462',59,8,2,23,15,NULL,95.00,NULL,'2026-06-09 19:24:51','2026-06-09 19:24:51'),
(410,'TRX-0463',59,8,2,24,15,NULL,95.00,NULL,'2026-06-09 19:24:51','2026-06-09 19:24:51'),
(411,'TRX-0464',59,8,2,29,16,NULL,90.00,NULL,'2026-06-09 19:24:51','2026-06-09 19:24:51'),
(412,'TRX-0465',59,8,2,26,16,NULL,90.00,NULL,'2026-06-09 19:24:51','2026-06-09 19:24:51'),
(413,'TRX-0466',59,8,2,27,16,NULL,90.00,NULL,'2026-06-09 19:24:51','2026-06-09 19:24:51'),
(414,'TRX-0467',59,8,2,28,16,NULL,90.00,NULL,'2026-06-09 19:24:51','2026-06-09 19:24:51'),
(415,'TRX-0468',34,8,2,20,14,NULL,90.00,NULL,'2026-06-09 19:25:42','2026-06-09 19:25:42'),
(416,'TRX-0469',34,8,2,21,14,NULL,90.00,NULL,'2026-06-09 19:25:42','2026-06-09 19:25:42'),
(417,'TRX-0470',34,8,2,25,14,NULL,95.00,NULL,'2026-06-09 19:25:42','2026-06-09 19:25:42'),
(418,'TRX-0471',34,8,2,22,15,NULL,95.00,NULL,'2026-06-09 19:25:42','2026-06-09 19:25:42'),
(419,'TRX-0472',34,8,2,23,15,NULL,90.00,NULL,'2026-06-09 19:25:42','2026-06-09 19:25:42'),
(420,'TRX-0473',34,8,2,24,15,NULL,94.00,NULL,'2026-06-09 19:25:42','2026-06-09 19:25:42'),
(421,'TRX-0474',34,8,2,29,16,NULL,90.00,NULL,'2026-06-09 19:25:42','2026-06-09 19:25:42'),
(422,'TRX-0475',34,8,2,26,16,NULL,90.00,NULL,'2026-06-09 19:25:42','2026-06-09 19:25:42'),
(423,'TRX-0476',34,8,2,27,16,NULL,90.00,NULL,'2026-06-09 19:25:42','2026-06-09 19:25:42'),
(424,'TRX-0477',34,8,2,28,16,NULL,90.00,NULL,'2026-06-09 19:25:42','2026-06-09 19:25:42'),
(425,'TRX-0887',60,8,2,20,14,NULL,78.00,NULL,'2026-06-09 19:27:22','2026-06-10 05:40:46'),
(426,'TRX-0888',60,8,2,21,14,NULL,78.00,NULL,'2026-06-09 19:27:22','2026-06-10 05:40:46'),
(427,'TRX-0889',60,8,2,25,14,NULL,78.00,NULL,'2026-06-09 19:27:22','2026-06-10 05:40:46'),
(428,'TRX-0890',60,8,2,22,15,NULL,78.00,NULL,'2026-06-09 19:27:22','2026-06-10 05:40:46'),
(429,'TRX-0891',60,8,2,23,15,NULL,80.00,NULL,'2026-06-09 19:27:22','2026-06-10 05:40:46'),
(430,'TRX-0892',60,8,2,24,15,NULL,78.00,NULL,'2026-06-09 19:27:22','2026-06-10 05:40:46'),
(431,'TRX-0893',60,8,2,29,16,NULL,80.00,NULL,'2026-06-09 19:27:22','2026-06-10 05:40:46'),
(432,'TRX-0894',60,8,2,26,16,NULL,80.00,NULL,'2026-06-09 19:27:22','2026-06-10 05:40:46'),
(433,'TRX-0895',60,8,2,27,16,NULL,80.00,NULL,'2026-06-09 19:27:22','2026-06-10 05:40:46'),
(434,'TRX-0896',60,8,2,28,16,NULL,80.00,NULL,'2026-06-09 19:27:22','2026-06-10 05:40:46'),
(435,'TRX-0488',46,8,2,20,14,NULL,80.00,NULL,'2026-06-09 19:28:26','2026-06-09 19:28:26'),
(436,'TRX-0489',46,8,2,21,14,NULL,78.00,NULL,'2026-06-09 19:28:26','2026-06-09 19:28:26'),
(437,'TRX-0490',46,8,2,25,14,NULL,78.00,NULL,'2026-06-09 19:28:26','2026-06-09 19:28:26'),
(438,'TRX-0491',46,8,2,22,15,NULL,80.00,NULL,'2026-06-09 19:28:26','2026-06-09 19:28:26'),
(439,'TRX-0492',46,8,2,23,15,NULL,80.00,NULL,'2026-06-09 19:28:26','2026-06-09 19:28:26'),
(440,'TRX-0493',46,8,2,24,15,NULL,80.00,NULL,'2026-06-09 19:28:26','2026-06-09 19:28:26'),
(441,'TRX-0494',46,8,2,29,16,NULL,85.00,NULL,'2026-06-09 19:28:26','2026-06-09 19:28:26'),
(442,'TRX-0495',46,8,2,26,16,NULL,85.00,NULL,'2026-06-09 19:28:26','2026-06-09 19:28:26'),
(443,'TRX-0496',46,8,2,27,16,NULL,85.00,NULL,'2026-06-09 19:28:26','2026-06-09 19:28:26'),
(444,'TRX-0497',46,8,2,28,16,NULL,85.00,NULL,'2026-06-09 19:28:26','2026-06-09 19:28:26'),
(445,'TRX-0498',38,9,2,20,14,NULL,80.00,NULL,'2026-06-09 19:30:58','2026-06-09 19:30:58'),
(446,'TRX-0499',38,9,2,21,14,NULL,80.00,NULL,'2026-06-09 19:30:58','2026-06-09 19:30:58'),
(447,'TRX-0500',38,9,2,25,14,NULL,80.00,NULL,'2026-06-09 19:30:58','2026-06-09 19:30:58'),
(448,'TRX-0501',38,9,2,22,15,NULL,80.00,NULL,'2026-06-09 19:30:58','2026-06-09 19:30:58'),
(449,'TRX-0502',38,9,2,23,15,NULL,80.00,NULL,'2026-06-09 19:30:58','2026-06-09 19:30:58'),
(450,'TRX-0503',38,9,2,24,15,NULL,80.00,NULL,'2026-06-09 19:30:58','2026-06-09 19:30:58'),
(451,'TRX-0504',38,9,2,29,16,NULL,80.00,NULL,'2026-06-09 19:30:58','2026-06-09 19:30:58'),
(452,'TRX-0505',38,9,2,26,16,NULL,82.00,NULL,'2026-06-09 19:30:58','2026-06-09 19:30:58'),
(453,'TRX-0506',38,9,2,27,16,NULL,82.00,NULL,'2026-06-09 19:30:58','2026-06-09 19:30:58'),
(454,'TRX-0507',38,9,2,28,16,NULL,80.00,NULL,'2026-06-09 19:30:58','2026-06-09 19:30:58'),
(455,'TRX-0508',39,9,2,20,14,NULL,85.00,NULL,'2026-06-09 19:32:21','2026-06-09 19:32:21'),
(456,'TRX-0509',39,9,2,21,14,NULL,85.00,NULL,'2026-06-09 19:32:21','2026-06-09 19:32:21'),
(457,'TRX-0510',39,9,2,25,14,NULL,84.00,NULL,'2026-06-09 19:32:21','2026-06-09 19:32:21'),
(458,'TRX-0511',39,9,2,22,15,NULL,84.00,NULL,'2026-06-09 19:32:21','2026-06-09 19:32:21'),
(459,'TRX-0512',39,9,2,23,15,NULL,84.00,NULL,'2026-06-09 19:32:21','2026-06-09 19:32:21'),
(460,'TRX-0513',39,9,2,24,15,NULL,82.00,NULL,'2026-06-09 19:32:21','2026-06-09 19:32:21'),
(461,'TRX-0514',39,9,2,29,16,NULL,84.00,NULL,'2026-06-09 19:32:21','2026-06-09 19:32:21'),
(462,'TRX-0515',39,9,2,26,16,NULL,82.00,NULL,'2026-06-09 19:32:21','2026-06-09 19:32:21'),
(463,'TRX-0516',39,9,2,27,16,NULL,82.00,NULL,'2026-06-09 19:32:21','2026-06-09 19:32:21'),
(464,'TRX-0517',39,9,2,28,16,NULL,84.00,NULL,'2026-06-09 19:32:21','2026-06-09 19:32:21'),
(465,'TRX-0518',57,9,2,20,14,NULL,87.00,NULL,'2026-06-09 19:33:19','2026-06-09 19:33:19'),
(466,'TRX-0519',57,9,2,21,14,NULL,87.00,NULL,'2026-06-09 19:33:19','2026-06-09 19:33:19'),
(467,'TRX-0520',57,9,2,25,14,NULL,87.00,NULL,'2026-06-09 19:33:19','2026-06-09 19:33:19'),
(468,'TRX-0521',57,9,2,22,15,NULL,85.00,NULL,'2026-06-09 19:33:19','2026-06-09 19:33:19'),
(469,'TRX-0522',57,9,2,23,15,NULL,84.00,NULL,'2026-06-09 19:33:19','2026-06-09 19:33:19'),
(470,'TRX-0523',57,9,2,24,15,NULL,84.00,NULL,'2026-06-09 19:33:19','2026-06-09 19:33:19'),
(471,'TRX-0524',57,9,2,29,16,NULL,84.00,NULL,'2026-06-09 19:33:19','2026-06-09 19:33:19'),
(472,'TRX-0525',57,9,2,26,16,NULL,80.00,NULL,'2026-06-09 19:33:19','2026-06-09 19:33:19'),
(473,'TRX-0526',57,9,2,27,16,NULL,80.00,NULL,'2026-06-09 19:33:19','2026-06-09 19:33:19'),
(474,'TRX-0527',57,9,2,28,16,NULL,80.00,NULL,'2026-06-09 19:33:19','2026-06-09 19:33:19'),
(475,'TRX-0528',45,9,2,20,14,NULL,85.00,NULL,'2026-06-09 19:34:12','2026-06-09 19:34:12'),
(476,'TRX-0529',45,9,2,21,14,NULL,80.00,NULL,'2026-06-09 19:34:12','2026-06-09 19:34:12'),
(477,'TRX-0530',45,9,2,25,14,NULL,80.00,NULL,'2026-06-09 19:34:12','2026-06-09 19:34:12'),
(478,'TRX-0531',45,9,2,22,15,NULL,80.00,NULL,'2026-06-09 19:34:12','2026-06-09 19:34:12'),
(479,'TRX-0532',45,9,2,23,15,NULL,80.00,NULL,'2026-06-09 19:34:12','2026-06-09 19:34:12'),
(480,'TRX-0533',45,9,2,24,15,NULL,80.00,NULL,'2026-06-09 19:34:12','2026-06-09 19:34:12'),
(481,'TRX-0534',45,9,2,29,16,NULL,80.00,NULL,'2026-06-09 19:34:12','2026-06-09 19:34:12'),
(482,'TRX-0535',45,9,2,26,16,NULL,80.00,NULL,'2026-06-09 19:34:12','2026-06-09 19:34:12'),
(483,'TRX-0536',45,9,2,27,16,NULL,80.00,NULL,'2026-06-09 19:34:12','2026-06-09 19:34:12'),
(484,'TRX-0537',45,9,2,28,16,NULL,80.00,NULL,'2026-06-09 19:34:12','2026-06-09 19:34:12'),
(485,'TRX-0538',46,9,2,20,14,NULL,87.00,NULL,'2026-06-09 19:35:04','2026-06-09 19:35:04'),
(486,'TRX-0539',46,9,2,21,14,NULL,85.00,NULL,'2026-06-09 19:35:04','2026-06-09 19:35:04'),
(487,'TRX-0540',46,9,2,25,14,NULL,80.00,NULL,'2026-06-09 19:35:04','2026-06-09 19:35:04'),
(488,'TRX-0541',46,9,2,22,15,NULL,80.00,NULL,'2026-06-09 19:35:04','2026-06-09 19:35:04'),
(489,'TRX-0542',46,9,2,23,15,NULL,80.00,NULL,'2026-06-09 19:35:04','2026-06-09 19:35:04'),
(490,'TRX-0543',46,9,2,24,15,NULL,80.00,NULL,'2026-06-09 19:35:04','2026-06-09 19:35:04'),
(491,'TRX-0544',46,9,2,29,16,NULL,80.00,NULL,'2026-06-09 19:35:04','2026-06-09 19:35:04'),
(492,'TRX-0545',46,9,2,26,16,NULL,80.00,NULL,'2026-06-09 19:35:04','2026-06-09 19:35:04'),
(493,'TRX-0546',46,9,2,27,16,NULL,80.00,NULL,'2026-06-09 19:35:04','2026-06-09 19:35:04'),
(494,'TRX-0547',46,9,2,28,16,NULL,80.00,NULL,'2026-06-09 19:35:04','2026-06-09 19:35:04'),
(495,'TRX-0548',49,9,2,20,14,NULL,85.00,NULL,'2026-06-09 19:36:24','2026-06-09 19:36:24'),
(496,'TRX-0549',49,9,2,21,14,NULL,80.00,NULL,'2026-06-09 19:36:24','2026-06-09 19:36:24'),
(497,'TRX-0550',49,9,2,25,14,NULL,82.00,NULL,'2026-06-09 19:36:24','2026-06-09 19:36:24'),
(498,'TRX-0551',49,9,2,22,15,NULL,82.00,NULL,'2026-06-09 19:36:24','2026-06-09 19:36:24'),
(499,'TRX-0552',49,9,2,23,15,NULL,84.00,NULL,'2026-06-09 19:36:24','2026-06-09 19:36:24'),
(500,'TRX-0553',49,9,2,24,15,NULL,80.00,NULL,'2026-06-09 19:36:24','2026-06-09 19:36:24'),
(501,'TRX-0554',49,9,2,29,16,NULL,82.00,NULL,'2026-06-09 19:36:24','2026-06-09 19:36:24'),
(502,'TRX-0555',49,9,2,26,16,NULL,82.00,NULL,'2026-06-09 19:36:24','2026-06-09 19:36:24'),
(503,'TRX-0556',49,9,2,27,16,NULL,82.00,NULL,'2026-06-09 19:36:24','2026-06-09 19:36:24'),
(504,'TRX-0557',49,9,2,28,16,NULL,82.00,NULL,'2026-06-09 19:36:24','2026-06-09 19:36:24'),
(505,'TRX-0578',40,6,2,20,14,NULL,87.00,NULL,'2026-06-09 20:37:22','2026-06-09 20:37:22'),
(506,'TRX-0579',40,6,2,21,14,NULL,88.00,NULL,'2026-06-09 20:37:22','2026-06-09 20:37:22'),
(507,'TRX-0580',40,6,2,25,14,NULL,80.00,NULL,'2026-06-09 20:37:22','2026-06-09 20:37:22'),
(508,'TRX-0581',40,6,2,22,15,NULL,88.00,NULL,'2026-06-09 20:37:22','2026-06-09 20:37:22'),
(509,'TRX-0582',40,6,2,23,15,NULL,87.00,NULL,'2026-06-09 20:37:22','2026-06-09 20:37:22'),
(510,'TRX-0583',40,6,2,24,15,NULL,80.00,NULL,'2026-06-09 20:37:22','2026-06-09 20:37:22'),
(511,'TRX-0584',40,6,2,29,16,NULL,84.00,NULL,'2026-06-09 20:37:22','2026-06-09 20:37:22'),
(512,'TRX-0585',40,6,2,26,16,NULL,84.00,NULL,'2026-06-09 20:37:22','2026-06-09 20:37:22'),
(513,'TRX-0586',40,6,2,27,16,NULL,80.00,NULL,'2026-06-09 20:37:22','2026-06-09 20:37:22'),
(514,'TRX-0587',40,6,2,28,16,NULL,80.00,NULL,'2026-06-09 20:37:22','2026-06-09 20:37:22'),
(515,'TRX-0588',48,NULL,2,20,14,NULL,100.00,NULL,'2026-06-10 02:40:27','2026-06-10 02:40:27'),
(516,'TRX-0589',48,NULL,2,21,14,NULL,89.00,NULL,'2026-06-10 02:40:27','2026-06-10 02:40:27'),
(517,'TRX-0590',48,NULL,2,25,14,NULL,89.00,NULL,'2026-06-10 02:40:27','2026-06-10 02:40:27'),
(518,'TRX-0591',48,NULL,2,22,15,NULL,89.00,NULL,'2026-06-10 02:40:27','2026-06-10 02:40:27'),
(519,'TRX-0592',48,NULL,2,23,15,NULL,90.00,NULL,'2026-06-10 02:40:27','2026-06-10 02:40:27'),
(520,'TRX-0593',48,NULL,2,24,15,NULL,88.00,NULL,'2026-06-10 02:40:27','2026-06-10 02:40:27'),
(521,'TRX-0594',48,NULL,2,29,16,NULL,89.00,NULL,'2026-06-10 02:40:27','2026-06-10 02:40:27'),
(522,'TRX-0595',48,NULL,2,26,16,NULL,89.00,NULL,'2026-06-10 02:40:27','2026-06-10 02:40:27'),
(523,'TRX-0596',48,NULL,2,27,16,NULL,79.00,NULL,'2026-06-10 02:40:27','2026-06-10 02:40:27'),
(524,'TRX-0597',48,NULL,2,28,16,NULL,89.00,NULL,'2026-06-10 02:40:27','2026-06-10 02:40:27'),
(525,'TRX-0600',48,3,2,20,14,NULL,100.00,NULL,'2026-06-10 03:35:43','2026-06-10 03:35:43'),
(526,'TRX-0601',48,3,2,21,14,NULL,89.00,NULL,'2026-06-10 03:35:43','2026-06-10 03:35:43'),
(527,'TRX-0602',48,3,2,25,14,NULL,89.00,NULL,'2026-06-10 03:35:43','2026-06-10 03:35:43'),
(528,'TRX-0603',48,3,2,22,15,NULL,89.00,NULL,'2026-06-10 03:35:43','2026-06-10 03:35:43'),
(529,'TRX-0604',48,3,2,23,15,NULL,90.00,NULL,'2026-06-10 03:35:43','2026-06-10 03:35:43'),
(530,'TRX-0605',48,3,2,24,15,NULL,88.00,NULL,'2026-06-10 03:35:43','2026-06-10 03:35:43'),
(531,'TRX-0606',48,3,2,29,16,NULL,89.00,NULL,'2026-06-10 03:35:43','2026-06-10 03:35:43'),
(532,'TRX-0607',48,3,2,26,16,NULL,89.00,NULL,'2026-06-10 03:35:43','2026-06-10 03:35:43'),
(533,'TRX-0608',48,3,2,27,16,NULL,79.00,NULL,'2026-06-10 03:35:43','2026-06-10 03:35:43'),
(534,'TRX-0609',48,3,2,28,16,NULL,89.00,NULL,'2026-06-10 03:35:43','2026-06-10 03:35:43'),
(535,'TRX-0610',32,2,2,20,14,NULL,85.00,NULL,'2026-06-10 03:40:38','2026-06-10 03:40:38'),
(536,'TRX-0611',32,2,2,21,14,NULL,81.00,NULL,'2026-06-10 03:40:38','2026-06-10 03:40:38'),
(537,'TRX-0612',32,2,2,25,14,NULL,90.00,NULL,'2026-06-10 03:40:38','2026-06-10 03:40:38'),
(538,'TRX-0613',32,2,2,22,15,NULL,90.00,NULL,'2026-06-10 03:40:38','2026-06-10 03:40:38'),
(539,'TRX-0614',32,2,2,23,15,NULL,95.00,NULL,'2026-06-10 03:40:38','2026-06-10 03:40:38'),
(540,'TRX-0615',32,2,2,24,15,NULL,80.00,NULL,'2026-06-10 03:40:38','2026-06-10 03:40:38'),
(541,'TRX-0616',32,2,2,29,16,NULL,80.00,NULL,'2026-06-10 03:40:38','2026-06-10 03:40:38'),
(542,'TRX-0617',32,2,2,26,16,NULL,80.00,NULL,'2026-06-10 03:40:38','2026-06-10 03:40:38'),
(543,'TRX-0618',32,2,2,27,16,NULL,82.00,NULL,'2026-06-10 03:40:38','2026-06-10 03:40:38'),
(544,'TRX-0619',32,2,2,28,16,NULL,80.00,NULL,'2026-06-10 03:40:38','2026-06-10 03:40:38'),
(545,'TRX-0620',32,13,2,15,4,NULL,90.00,NULL,'2026-06-10 03:43:45','2026-06-10 03:43:45'),
(546,'TRX-0621',32,13,2,16,4,NULL,85.00,NULL,'2026-06-10 03:43:45','2026-06-10 03:43:45'),
(547,'TRX-0622',32,13,2,17,4,NULL,86.00,NULL,'2026-06-10 03:43:45','2026-06-10 03:43:45'),
(548,'TRX-0623',32,13,2,18,4,NULL,85.00,NULL,'2026-06-10 03:43:45','2026-06-10 03:43:45'),
(549,'TRX-0624',32,13,2,19,4,NULL,85.00,NULL,'2026-06-10 03:43:45','2026-06-10 03:43:45'),
(550,'TRX-0625',35,13,2,15,4,NULL,86.00,NULL,'2026-06-10 03:44:53','2026-06-10 03:44:53'),
(551,'TRX-0626',35,13,2,16,4,NULL,88.00,NULL,'2026-06-10 03:44:53','2026-06-10 03:44:53'),
(552,'TRX-0627',35,13,2,17,4,NULL,80.00,NULL,'2026-06-10 03:44:53','2026-06-10 03:44:53'),
(553,'TRX-0628',35,13,2,18,4,NULL,80.00,NULL,'2026-06-10 03:44:53','2026-06-10 03:44:53'),
(554,'TRX-0629',35,13,2,19,4,NULL,80.00,NULL,'2026-06-10 03:44:53','2026-06-10 03:44:53'),
(555,'TRX-0630',36,13,2,15,4,NULL,90.00,NULL,'2026-06-10 03:46:13','2026-06-10 03:46:13'),
(556,'TRX-0631',36,13,2,16,4,NULL,87.00,NULL,'2026-06-10 03:46:13','2026-06-10 03:46:13'),
(557,'TRX-0632',36,13,2,17,4,NULL,86.00,NULL,'2026-06-10 03:46:13','2026-06-10 03:46:13'),
(558,'TRX-0633',36,13,2,18,4,NULL,86.00,NULL,'2026-06-10 03:46:13','2026-06-10 03:46:13'),
(559,'TRX-0634',36,13,2,19,4,NULL,88.00,NULL,'2026-06-10 03:46:13','2026-06-10 03:46:13'),
(560,'TRX-0635',54,13,2,15,4,NULL,90.00,NULL,'2026-06-10 03:47:33','2026-06-10 03:47:33'),
(561,'TRX-0636',54,13,2,16,4,NULL,88.00,NULL,'2026-06-10 03:47:33','2026-06-10 03:47:33'),
(562,'TRX-0637',54,13,2,17,4,NULL,85.00,NULL,'2026-06-10 03:47:33','2026-06-10 03:47:33'),
(563,'TRX-0638',54,13,2,18,4,NULL,85.00,NULL,'2026-06-10 03:47:33','2026-06-10 03:47:33'),
(564,'TRX-0639',54,13,2,19,4,NULL,86.00,NULL,'2026-06-10 03:47:33','2026-06-10 03:47:33'),
(565,'TRX-0640',55,13,2,15,4,NULL,90.00,NULL,'2026-06-10 03:48:15','2026-06-10 03:48:15'),
(566,'TRX-0641',55,13,2,16,4,NULL,88.00,NULL,'2026-06-10 03:48:15','2026-06-10 03:48:15'),
(567,'TRX-0642',55,13,2,17,4,NULL,88.00,NULL,'2026-06-10 03:48:15','2026-06-10 03:48:15'),
(568,'TRX-0643',55,13,2,18,4,NULL,88.00,NULL,'2026-06-10 03:48:15','2026-06-10 03:48:15'),
(569,'TRX-0644',55,13,2,19,4,NULL,87.00,NULL,'2026-06-10 03:48:15','2026-06-10 03:48:15'),
(570,'TRX-0645',37,13,2,15,4,NULL,80.00,NULL,'2026-06-10 03:49:08','2026-06-10 03:49:08'),
(571,'TRX-0646',37,13,2,16,4,NULL,80.00,NULL,'2026-06-10 03:49:08','2026-06-10 03:49:08'),
(572,'TRX-0647',37,13,2,17,4,NULL,80.00,NULL,'2026-06-10 03:49:08','2026-06-10 03:49:08'),
(573,'TRX-0648',37,13,2,18,4,NULL,80.00,NULL,'2026-06-10 03:49:08','2026-06-10 03:49:08'),
(574,'TRX-0649',37,13,2,19,4,NULL,77.00,NULL,'2026-06-10 03:49:08','2026-06-10 03:49:08'),
(575,'TRX-0650',55,2,2,20,14,NULL,85.00,NULL,'2026-06-10 03:50:35','2026-06-10 03:50:35'),
(576,'TRX-0651',55,2,2,21,14,NULL,81.00,NULL,'2026-06-10 03:50:35','2026-06-10 03:50:35'),
(577,'TRX-0652',55,2,2,25,14,NULL,80.00,NULL,'2026-06-10 03:50:35','2026-06-10 03:50:35'),
(578,'TRX-0653',55,2,2,22,15,NULL,82.00,NULL,'2026-06-10 03:50:35','2026-06-10 03:50:35'),
(579,'TRX-0654',55,2,2,23,15,NULL,81.00,NULL,'2026-06-10 03:50:35','2026-06-10 03:50:35'),
(580,'TRX-0655',55,2,2,24,15,NULL,78.00,NULL,'2026-06-10 03:50:35','2026-06-10 03:50:35'),
(581,'TRX-0656',55,2,2,29,16,NULL,81.00,NULL,'2026-06-10 03:50:35','2026-06-10 03:50:35'),
(582,'TRX-0657',55,2,2,26,16,NULL,79.00,NULL,'2026-06-10 03:50:35','2026-06-10 03:50:35'),
(583,'TRX-0658',55,2,2,27,16,NULL,81.00,NULL,'2026-06-10 03:50:35','2026-06-10 03:50:35'),
(584,'TRX-0659',55,2,2,28,16,NULL,80.00,NULL,'2026-06-10 03:50:35','2026-06-10 03:50:35'),
(585,'TRX-0660',38,13,2,15,4,NULL,86.00,NULL,'2026-06-10 03:51:00','2026-06-10 03:51:00'),
(586,'TRX-0661',38,13,2,16,4,NULL,80.00,NULL,'2026-06-10 03:51:00','2026-06-10 03:51:00'),
(587,'TRX-0662',38,13,2,17,4,NULL,85.00,NULL,'2026-06-10 03:51:00','2026-06-10 03:51:00'),
(588,'TRX-0663',38,13,2,18,4,NULL,85.00,NULL,'2026-06-10 03:51:00','2026-06-10 03:51:00'),
(589,'TRX-0664',38,13,2,19,4,NULL,85.00,NULL,'2026-06-10 03:51:00','2026-06-10 03:51:00'),
(590,'TRX-0665',39,13,2,15,4,NULL,86.00,NULL,'2026-06-10 03:52:12','2026-06-10 03:52:12'),
(591,'TRX-0666',39,13,2,16,4,NULL,80.00,NULL,'2026-06-10 03:52:12','2026-06-10 03:52:12'),
(592,'TRX-0667',39,13,2,17,4,NULL,85.00,NULL,'2026-06-10 03:52:12','2026-06-10 03:52:12'),
(593,'TRX-0668',39,13,2,18,4,NULL,80.00,NULL,'2026-06-10 03:52:12','2026-06-10 03:52:12'),
(594,'TRX-0669',39,13,2,19,4,NULL,86.00,NULL,'2026-06-10 03:52:12','2026-06-10 03:52:12'),
(595,'TRX-0670',53,13,2,15,4,NULL,86.00,NULL,'2026-06-10 03:53:12','2026-06-10 03:53:12'),
(596,'TRX-0671',53,13,2,16,4,NULL,86.00,NULL,'2026-06-10 03:53:12','2026-06-10 03:53:12'),
(597,'TRX-0672',53,13,2,17,4,NULL,88.00,NULL,'2026-06-10 03:53:12','2026-06-10 03:53:12'),
(598,'TRX-0673',53,13,2,18,4,NULL,80.00,NULL,'2026-06-10 03:53:12','2026-06-10 03:53:12'),
(599,'TRX-0674',53,13,2,19,4,NULL,85.00,NULL,'2026-06-10 03:53:12','2026-06-10 03:53:12'),
(600,'TRX-0675',59,2,2,20,14,NULL,90.00,NULL,'2026-06-10 03:53:50','2026-06-10 03:53:50'),
(601,'TRX-0676',59,2,2,21,14,NULL,87.00,NULL,'2026-06-10 03:53:50','2026-06-10 03:53:50'),
(602,'TRX-0677',59,2,2,25,14,NULL,90.00,NULL,'2026-06-10 03:53:50','2026-06-10 03:53:50'),
(603,'TRX-0678',59,2,2,22,15,NULL,82.00,NULL,'2026-06-10 03:53:50','2026-06-10 03:53:50'),
(604,'TRX-0679',59,2,2,23,15,NULL,82.00,NULL,'2026-06-10 03:53:50','2026-06-10 03:53:50'),
(605,'TRX-0680',59,2,2,24,15,NULL,82.00,NULL,'2026-06-10 03:53:50','2026-06-10 03:53:50'),
(606,'TRX-0681',59,2,2,29,16,NULL,82.00,NULL,'2026-06-10 03:53:50','2026-06-10 03:53:50'),
(607,'TRX-0682',59,2,2,26,16,NULL,80.00,NULL,'2026-06-10 03:53:50','2026-06-10 03:53:50'),
(608,'TRX-0683',59,2,2,27,16,NULL,85.00,NULL,'2026-06-10 03:53:50','2026-06-10 03:53:50'),
(609,'TRX-0684',59,2,2,28,16,NULL,81.00,NULL,'2026-06-10 03:53:50','2026-06-10 03:53:50'),
(610,'TRX-0685',40,13,2,15,4,NULL,90.00,NULL,'2026-06-10 03:54:12','2026-06-10 03:54:12'),
(611,'TRX-0686',40,13,2,16,4,NULL,85.00,NULL,'2026-06-10 03:54:12','2026-06-10 03:54:12'),
(612,'TRX-0687',40,13,2,17,4,NULL,86.00,NULL,'2026-06-10 03:54:12','2026-06-10 03:54:12'),
(613,'TRX-0688',40,13,2,18,4,NULL,86.00,NULL,'2026-06-10 03:54:12','2026-06-10 03:54:12'),
(614,'TRX-0689',40,13,2,19,4,NULL,88.00,NULL,'2026-06-10 03:54:12','2026-06-10 03:54:12'),
(615,'TRX-0690',41,13,2,15,4,NULL,90.00,NULL,'2026-06-10 03:56:51','2026-06-10 03:56:51'),
(616,'TRX-0691',41,13,2,16,4,NULL,90.00,NULL,'2026-06-10 03:56:51','2026-06-10 03:56:51'),
(617,'TRX-0692',41,13,2,17,4,NULL,88.00,NULL,'2026-06-10 03:56:51','2026-06-10 03:56:51'),
(618,'TRX-0693',41,13,2,18,4,NULL,86.00,NULL,'2026-06-10 03:56:51','2026-06-10 03:56:51'),
(619,'TRX-0694',41,13,2,19,4,NULL,88.00,NULL,'2026-06-10 03:56:51','2026-06-10 03:56:51'),
(620,'TRX-0695',42,13,2,15,4,NULL,90.00,NULL,'2026-06-10 03:57:45','2026-06-10 03:57:45'),
(621,'TRX-0696',42,13,2,16,4,NULL,87.00,NULL,'2026-06-10 03:57:45','2026-06-10 03:57:45'),
(622,'TRX-0697',42,13,2,17,4,NULL,86.00,NULL,'2026-06-10 03:57:45','2026-06-10 03:57:45'),
(623,'TRX-0698',42,13,2,18,4,NULL,86.00,NULL,'2026-06-10 03:57:45','2026-06-10 03:57:45'),
(624,'TRX-0699',42,13,2,19,4,NULL,88.00,NULL,'2026-06-10 03:57:45','2026-06-10 03:57:45'),
(625,'TRX-0700',43,13,2,15,4,NULL,80.00,NULL,'2026-06-10 03:58:44','2026-06-10 03:58:44'),
(626,'TRX-0701',43,13,2,16,4,NULL,80.00,NULL,'2026-06-10 03:58:44','2026-06-10 03:58:44'),
(627,'TRX-0702',43,13,2,17,4,NULL,80.00,NULL,'2026-06-10 03:58:44','2026-06-10 03:58:44'),
(628,'TRX-0703',43,13,2,18,4,NULL,77.00,NULL,'2026-06-10 03:58:44','2026-06-10 03:58:44'),
(629,'TRX-0704',43,13,2,19,4,NULL,77.00,NULL,'2026-06-10 03:58:44','2026-06-10 03:58:44'),
(630,'TRX-0705',52,13,2,15,4,NULL,90.00,NULL,'2026-06-10 03:59:40','2026-06-10 03:59:40'),
(631,'TRX-0706',52,13,2,16,4,NULL,85.00,NULL,'2026-06-10 03:59:40','2026-06-10 03:59:40'),
(632,'TRX-0707',52,13,2,17,4,NULL,85.00,NULL,'2026-06-10 03:59:40','2026-06-10 03:59:40'),
(633,'TRX-0708',52,13,2,18,4,NULL,85.00,NULL,'2026-06-10 03:59:40','2026-06-10 03:59:40'),
(634,'TRX-0709',52,13,2,19,4,NULL,85.00,NULL,'2026-06-10 03:59:40','2026-06-10 03:59:40'),
(635,'TRX-1041',56,13,2,15,4,NULL,90.00,NULL,'2026-06-10 04:00:39','2026-06-10 08:07:18'),
(636,'TRX-1042',56,13,2,16,4,NULL,85.00,NULL,'2026-06-10 04:00:39','2026-06-10 08:07:18'),
(637,'TRX-1043',56,13,2,17,4,NULL,80.00,NULL,'2026-06-10 04:00:39','2026-06-10 08:07:18'),
(638,'TRX-1044',56,13,2,18,4,NULL,85.00,NULL,'2026-06-10 04:00:39','2026-06-10 08:07:18'),
(639,'TRX-1045',56,13,2,19,4,NULL,84.00,NULL,'2026-06-10 04:00:39','2026-06-10 08:07:18'),
(640,'TRX-0715',57,13,2,15,4,NULL,90.00,NULL,'2026-06-10 04:02:14','2026-06-10 04:02:14'),
(641,'TRX-0716',57,13,2,16,4,NULL,87.00,NULL,'2026-06-10 04:02:14','2026-06-10 04:02:14'),
(642,'TRX-0717',57,13,2,17,4,NULL,87.00,NULL,'2026-06-10 04:02:14','2026-06-10 04:02:14'),
(643,'TRX-0718',57,13,2,18,4,NULL,86.00,NULL,'2026-06-10 04:02:14','2026-06-10 04:02:14'),
(644,'TRX-0719',57,13,2,19,4,NULL,88.00,NULL,'2026-06-10 04:02:14','2026-06-10 04:02:14'),
(645,'TRX-0720',58,13,2,15,4,NULL,87.00,NULL,'2026-06-10 04:04:21','2026-06-10 04:04:21'),
(646,'TRX-0721',58,13,2,16,4,NULL,80.00,NULL,'2026-06-10 04:04:21','2026-06-10 04:04:21'),
(647,'TRX-0722',58,13,2,17,4,NULL,85.00,NULL,'2026-06-10 04:04:21','2026-06-10 04:04:21'),
(648,'TRX-0723',58,13,2,18,4,NULL,85.00,NULL,'2026-06-10 04:04:21','2026-06-10 04:04:21'),
(649,'TRX-0724',58,13,2,19,4,NULL,88.00,NULL,'2026-06-10 04:04:21','2026-06-10 04:04:21'),
(650,'TRX-0725',35,5,2,20,14,NULL,82.00,NULL,'2026-06-10 04:04:36','2026-06-10 04:04:36'),
(651,'TRX-0726',35,5,2,21,14,NULL,77.00,NULL,'2026-06-10 04:04:36','2026-06-10 04:04:36'),
(652,'TRX-0727',35,5,2,25,14,NULL,88.00,NULL,'2026-06-10 04:04:36','2026-06-10 04:04:36'),
(653,'TRX-0728',35,5,2,22,15,NULL,81.00,NULL,'2026-06-10 04:04:36','2026-06-10 04:04:36'),
(654,'TRX-0729',35,5,2,23,15,NULL,88.00,NULL,'2026-06-10 04:04:36','2026-06-10 04:04:36'),
(655,'TRX-0730',35,5,2,24,15,NULL,76.00,NULL,'2026-06-10 04:04:36','2026-06-10 04:04:36'),
(656,'TRX-0731',35,5,2,29,16,NULL,75.00,NULL,'2026-06-10 04:04:36','2026-06-10 04:04:36'),
(657,'TRX-0732',35,5,2,26,16,NULL,80.00,NULL,'2026-06-10 04:04:36','2026-06-10 04:04:36'),
(658,'TRX-0733',35,5,2,27,16,NULL,82.00,NULL,'2026-06-10 04:04:36','2026-06-10 04:04:36'),
(659,'TRX-0734',35,5,2,28,16,NULL,76.00,NULL,'2026-06-10 04:04:36','2026-06-10 04:04:36'),
(660,'TRX-0735',54,5,2,20,14,NULL,83.00,NULL,'2026-06-10 04:06:48','2026-06-10 04:06:48'),
(661,'TRX-0736',54,5,2,21,14,NULL,80.00,NULL,'2026-06-10 04:06:48','2026-06-10 04:06:48'),
(662,'TRX-0737',54,5,2,25,14,NULL,90.00,NULL,'2026-06-10 04:06:48','2026-06-10 04:06:48'),
(663,'TRX-0738',54,5,2,22,15,NULL,82.00,NULL,'2026-06-10 04:06:48','2026-06-10 04:06:48'),
(664,'TRX-0739',54,5,2,23,15,NULL,88.00,NULL,'2026-06-10 04:06:48','2026-06-10 04:06:48'),
(665,'TRX-0740',54,5,2,24,15,NULL,77.00,NULL,'2026-06-10 04:06:48','2026-06-10 04:06:48'),
(666,'TRX-0741',54,5,2,29,16,NULL,75.00,NULL,'2026-06-10 04:06:48','2026-06-10 04:06:48'),
(667,'TRX-0742',54,5,2,26,16,NULL,76.00,NULL,'2026-06-10 04:06:48','2026-06-10 04:06:48'),
(668,'TRX-0743',54,5,2,27,16,NULL,83.00,NULL,'2026-06-10 04:06:48','2026-06-10 04:06:48'),
(669,'TRX-0744',54,5,2,28,16,NULL,76.00,NULL,'2026-06-10 04:06:48','2026-06-10 04:06:48'),
(670,'TRX-0745',59,13,2,15,4,NULL,90.00,NULL,'2026-06-10 04:06:59','2026-06-10 04:06:59'),
(671,'TRX-0746',59,13,2,16,4,NULL,88.00,NULL,'2026-06-10 04:06:59','2026-06-10 04:06:59'),
(672,'TRX-0747',59,13,2,17,4,NULL,88.00,NULL,'2026-06-10 04:06:59','2026-06-10 04:06:59'),
(673,'TRX-0748',59,13,2,18,4,NULL,88.00,NULL,'2026-06-10 04:06:59','2026-06-10 04:06:59'),
(674,'TRX-0749',59,13,2,19,4,NULL,88.00,NULL,'2026-06-10 04:06:59','2026-06-10 04:06:59'),
(675,'TRX-0750',34,13,2,15,4,NULL,90.00,NULL,'2026-06-10 04:09:05','2026-06-10 04:09:05'),
(676,'TRX-0751',34,13,2,16,4,NULL,90.00,NULL,'2026-06-10 04:09:05','2026-06-10 04:09:05'),
(677,'TRX-0752',34,13,2,17,4,NULL,88.00,NULL,'2026-06-10 04:09:05','2026-06-10 04:09:05'),
(678,'TRX-0753',34,13,2,18,4,NULL,86.00,NULL,'2026-06-10 04:09:05','2026-06-10 04:09:05'),
(679,'TRX-0754',34,13,2,19,4,NULL,88.00,NULL,'2026-06-10 04:09:05','2026-06-10 04:09:05'),
(680,'TRX-0755',40,5,2,20,14,NULL,89.00,NULL,'2026-06-10 04:10:15','2026-06-10 04:10:15'),
(681,'TRX-0756',40,5,2,21,14,NULL,80.00,NULL,'2026-06-10 04:10:15','2026-06-10 04:10:15'),
(682,'TRX-0757',40,5,2,25,14,NULL,80.00,NULL,'2026-06-10 04:10:15','2026-06-10 04:10:15'),
(683,'TRX-0758',40,5,2,22,15,NULL,82.00,NULL,'2026-06-10 04:10:15','2026-06-10 04:10:15'),
(684,'TRX-0759',40,5,2,23,15,NULL,79.00,NULL,'2026-06-10 04:10:15','2026-06-10 04:10:15'),
(685,'TRX-0760',40,5,2,24,15,NULL,79.00,NULL,'2026-06-10 04:10:15','2026-06-10 04:10:15'),
(686,'TRX-0761',40,5,2,29,16,NULL,75.00,NULL,'2026-06-10 04:10:15','2026-06-10 04:10:15'),
(687,'TRX-0762',40,5,2,26,16,NULL,80.00,NULL,'2026-06-10 04:10:15','2026-06-10 04:10:15'),
(688,'TRX-0763',40,5,2,27,16,NULL,83.00,NULL,'2026-06-10 04:10:15','2026-06-10 04:10:15'),
(689,'TRX-0764',40,5,2,28,16,NULL,76.00,NULL,'2026-06-10 04:10:15','2026-06-10 04:10:15'),
(690,'TRX-0850',60,13,2,15,4,NULL,80.00,NULL,'2026-06-10 04:10:56','2026-06-10 05:28:10'),
(691,'TRX-0851',60,13,2,16,4,NULL,83.00,NULL,'2026-06-10 04:10:56','2026-06-10 05:28:10'),
(692,'TRX-0852',60,13,2,17,4,NULL,83.00,NULL,'2026-06-10 04:10:56','2026-06-10 05:28:10'),
(693,'TRX-0853',60,13,2,18,4,NULL,78.00,NULL,'2026-06-10 04:10:56','2026-06-10 05:28:10'),
(694,'TRX-0854',60,13,2,19,4,NULL,78.00,NULL,'2026-06-10 04:10:56','2026-06-10 05:28:10'),
(695,'TRX-0770',45,13,2,15,4,NULL,90.00,NULL,'2026-06-10 04:13:11','2026-06-10 04:13:11'),
(696,'TRX-0771',45,13,2,16,4,NULL,85.00,NULL,'2026-06-10 04:13:11','2026-06-10 04:13:11'),
(697,'TRX-0772',45,13,2,17,4,NULL,85.00,NULL,'2026-06-10 04:13:11','2026-06-10 04:13:11'),
(698,'TRX-0773',45,13,2,18,4,NULL,85.00,NULL,'2026-06-10 04:13:11','2026-06-10 04:13:11'),
(699,'TRX-0774',45,13,2,19,4,NULL,88.00,NULL,'2026-06-10 04:13:11','2026-06-10 04:13:11'),
(700,'TRX-0775',50,5,2,20,14,NULL,83.00,NULL,'2026-06-10 04:13:40','2026-06-10 04:13:40'),
(701,'TRX-0776',50,5,2,21,14,NULL,80.00,NULL,'2026-06-10 04:13:40','2026-06-10 04:13:40'),
(702,'TRX-0777',50,5,2,25,14,NULL,90.00,NULL,'2026-06-10 04:13:40','2026-06-10 04:13:40'),
(703,'TRX-0778',50,5,2,22,15,NULL,83.00,NULL,'2026-06-10 04:13:40','2026-06-10 04:13:40'),
(704,'TRX-0779',50,5,2,23,15,NULL,90.00,NULL,'2026-06-10 04:13:40','2026-06-10 04:13:40'),
(705,'TRX-0780',50,5,2,24,15,NULL,77.00,NULL,'2026-06-10 04:13:40','2026-06-10 04:13:40'),
(706,'TRX-0781',50,5,2,29,16,NULL,75.00,NULL,'2026-06-10 04:13:40','2026-06-10 04:13:40'),
(707,'TRX-0782',50,5,2,26,16,NULL,77.00,NULL,'2026-06-10 04:13:40','2026-06-10 04:13:40'),
(708,'TRX-0783',50,5,2,27,16,NULL,83.00,NULL,'2026-06-10 04:13:40','2026-06-10 04:13:40'),
(709,'TRX-0784',50,5,2,28,16,NULL,76.00,NULL,'2026-06-10 04:13:40','2026-06-10 04:13:40'),
(710,'TRX-0785',46,13,2,15,4,NULL,90.00,NULL,'2026-06-10 04:15:23','2026-06-10 04:15:23'),
(711,'TRX-0786',46,13,2,16,4,NULL,85.00,NULL,'2026-06-10 04:15:23','2026-06-10 04:15:23'),
(712,'TRX-0787',46,13,2,17,4,NULL,85.00,NULL,'2026-06-10 04:15:23','2026-06-10 04:15:23'),
(713,'TRX-0788',46,13,2,18,4,NULL,86.00,NULL,'2026-06-10 04:15:23','2026-06-10 04:15:23'),
(714,'TRX-0789',46,13,2,19,4,NULL,85.00,NULL,'2026-06-10 04:15:23','2026-06-10 04:15:23'),
(715,'TRX-0790',47,13,2,15,4,NULL,85.00,NULL,'2026-06-10 04:17:36','2026-06-10 04:17:36'),
(716,'TRX-0791',47,13,2,16,4,NULL,85.00,NULL,'2026-06-10 04:17:36','2026-06-10 04:17:36'),
(717,'TRX-0792',47,13,2,17,4,NULL,85.00,NULL,'2026-06-10 04:17:36','2026-06-10 04:17:36'),
(718,'TRX-0793',47,13,2,18,4,NULL,80.00,NULL,'2026-06-10 04:17:36','2026-06-10 04:17:36'),
(719,'TRX-0794',47,13,2,19,4,NULL,85.00,NULL,'2026-06-10 04:17:36','2026-06-10 04:17:36'),
(720,'TRX-0795',49,13,2,15,4,NULL,85.00,NULL,'2026-06-10 04:19:11','2026-06-10 04:19:11'),
(721,'TRX-0796',49,13,2,16,4,NULL,85.00,NULL,'2026-06-10 04:19:11','2026-06-10 04:19:11'),
(722,'TRX-0797',49,13,2,17,4,NULL,85.00,NULL,'2026-06-10 04:19:11','2026-06-10 04:19:11'),
(723,'TRX-0798',49,13,2,18,4,NULL,80.00,NULL,'2026-06-10 04:19:11','2026-06-10 04:19:11'),
(724,'TRX-0799',49,13,2,19,4,NULL,85.00,NULL,'2026-06-10 04:19:11','2026-06-10 04:19:11'),
(725,'TRX-0800',48,13,2,15,4,NULL,80.00,NULL,'2026-06-10 04:19:34','2026-06-10 04:19:34'),
(726,'TRX-0801',48,13,2,16,4,NULL,83.00,NULL,'2026-06-10 04:19:34','2026-06-10 04:19:34'),
(727,'TRX-0802',48,13,2,17,4,NULL,82.00,NULL,'2026-06-10 04:19:34','2026-06-10 04:19:34'),
(728,'TRX-0803',48,13,2,18,4,NULL,80.00,NULL,'2026-06-10 04:19:34','2026-06-10 04:19:34'),
(729,'TRX-0804',48,13,2,19,4,NULL,80.00,NULL,'2026-06-10 04:19:34','2026-06-10 04:19:34'),
(730,'TRX-0805',50,13,2,15,4,NULL,88.00,NULL,'2026-06-10 04:20:14','2026-06-10 04:20:14'),
(731,'TRX-0806',50,13,2,16,4,NULL,87.00,NULL,'2026-06-10 04:20:14','2026-06-10 04:20:14'),
(732,'TRX-0807',50,13,2,17,4,NULL,88.00,NULL,'2026-06-10 04:20:14','2026-06-10 04:20:14'),
(733,'TRX-0808',50,13,2,18,4,NULL,85.00,NULL,'2026-06-10 04:20:14','2026-06-10 04:20:14'),
(734,'TRX-0809',50,13,2,19,4,NULL,85.00,NULL,'2026-06-10 04:20:14','2026-06-10 04:20:14'),
(735,'TRX-0810',36,1,2,20,14,NULL,80.00,NULL,'2026-06-10 05:13:52','2026-06-10 05:13:52'),
(736,'TRX-0811',36,1,2,21,14,NULL,80.00,NULL,'2026-06-10 05:13:52','2026-06-10 05:13:52'),
(737,'TRX-0812',36,1,2,25,14,NULL,80.00,NULL,'2026-06-10 05:13:52','2026-06-10 05:13:52'),
(738,'TRX-0813',36,1,2,22,15,NULL,80.00,NULL,'2026-06-10 05:13:52','2026-06-10 05:13:52'),
(739,'TRX-0814',36,1,2,23,15,NULL,85.00,NULL,'2026-06-10 05:13:52','2026-06-10 05:13:52'),
(740,'TRX-0815',36,1,2,24,15,NULL,80.00,NULL,'2026-06-10 05:13:52','2026-06-10 05:13:52'),
(741,'TRX-0816',36,1,2,29,16,NULL,85.00,NULL,'2026-06-10 05:13:52','2026-06-10 05:13:52'),
(742,'TRX-0817',36,1,2,26,16,NULL,85.00,NULL,'2026-06-10 05:13:52','2026-06-10 05:13:52'),
(743,'TRX-0818',36,1,2,27,16,NULL,85.00,NULL,'2026-06-10 05:13:52','2026-06-10 05:13:52'),
(744,'TRX-0819',36,1,2,28,16,NULL,85.00,NULL,'2026-06-10 05:13:52','2026-06-10 05:13:52'),
(745,'TRX-0820',41,1,2,20,14,NULL,85.00,NULL,'2026-06-10 05:16:11','2026-06-10 05:16:11'),
(746,'TRX-0821',41,1,2,21,14,NULL,85.00,NULL,'2026-06-10 05:16:11','2026-06-10 05:16:11'),
(747,'TRX-0822',41,1,2,25,14,NULL,85.00,NULL,'2026-06-10 05:16:11','2026-06-10 05:16:11'),
(748,'TRX-0823',41,1,2,22,15,NULL,85.00,NULL,'2026-06-10 05:16:11','2026-06-10 05:16:11'),
(749,'TRX-0824',41,1,2,23,15,NULL,85.00,NULL,'2026-06-10 05:16:11','2026-06-10 05:16:11'),
(750,'TRX-0825',41,1,2,24,15,NULL,80.00,NULL,'2026-06-10 05:16:11','2026-06-10 05:16:11'),
(751,'TRX-0826',41,1,2,29,16,NULL,85.00,NULL,'2026-06-10 05:16:11','2026-06-10 05:16:11'),
(752,'TRX-0827',41,1,2,26,16,NULL,85.00,NULL,'2026-06-10 05:16:11','2026-06-10 05:16:11'),
(753,'TRX-0828',41,1,2,27,16,NULL,85.00,NULL,'2026-06-10 05:16:11','2026-06-10 05:16:11'),
(754,'TRX-0829',41,1,2,28,16,NULL,85.00,NULL,'2026-06-10 05:16:11','2026-06-10 05:16:11'),
(755,'TRX-0830',34,1,2,20,14,NULL,85.00,NULL,'2026-06-10 05:18:37','2026-06-10 05:18:37'),
(756,'TRX-0831',34,1,2,21,14,NULL,85.00,NULL,'2026-06-10 05:18:37','2026-06-10 05:18:37'),
(757,'TRX-0832',34,1,2,25,14,NULL,90.00,NULL,'2026-06-10 05:18:37','2026-06-10 05:18:37'),
(758,'TRX-0833',34,1,2,22,15,NULL,85.00,NULL,'2026-06-10 05:18:37','2026-06-10 05:18:37'),
(759,'TRX-0834',34,1,2,23,15,NULL,85.00,NULL,'2026-06-10 05:18:37','2026-06-10 05:18:37'),
(760,'TRX-0835',34,1,2,24,15,NULL,80.00,NULL,'2026-06-10 05:18:37','2026-06-10 05:18:37'),
(761,'TRX-0836',34,1,2,29,16,NULL,85.00,NULL,'2026-06-10 05:18:37','2026-06-10 05:18:37'),
(762,'TRX-0837',34,1,2,26,16,NULL,85.00,NULL,'2026-06-10 05:18:37','2026-06-10 05:18:37'),
(763,'TRX-0838',34,1,2,27,16,NULL,85.00,NULL,'2026-06-10 05:18:37','2026-06-10 05:18:37'),
(764,'TRX-0839',34,1,2,28,16,NULL,85.00,NULL,'2026-06-10 05:18:37','2026-06-10 05:18:37'),
(765,'TRX-0897',32,17,2,15,19,NULL,80.00,NULL,'2026-06-10 06:29:54','2026-06-10 06:29:54'),
(766,'TRX-0898',32,17,2,19,19,NULL,80.00,NULL,'2026-06-10 06:29:54','2026-06-10 06:29:54'),
(767,'TRX-0899',35,17,2,15,19,NULL,70.00,NULL,'2026-06-10 06:30:12','2026-06-10 06:30:12'),
(768,'TRX-0900',35,17,2,19,19,NULL,70.00,NULL,'2026-06-10 06:30:12','2026-06-10 06:30:12'),
(769,'TRX-0901',36,17,2,15,19,NULL,70.00,NULL,'2026-06-10 06:30:29','2026-06-10 06:30:29'),
(770,'TRX-0902',36,17,2,19,19,NULL,80.00,NULL,'2026-06-10 06:30:29','2026-06-10 06:30:29'),
(771,'TRX-0903',54,17,2,15,19,NULL,80.00,NULL,'2026-06-10 06:30:50','2026-06-10 06:30:50'),
(772,'TRX-0904',54,17,2,19,19,NULL,80.00,NULL,'2026-06-10 06:30:50','2026-06-10 06:30:50'),
(773,'TRX-0905',55,17,2,15,19,NULL,80.00,NULL,'2026-06-10 06:31:06','2026-06-10 06:31:06'),
(774,'TRX-0906',55,17,2,19,19,NULL,80.00,NULL,'2026-06-10 06:31:06','2026-06-10 06:31:06'),
(775,'TRX-0907',37,17,2,15,19,NULL,70.00,NULL,'2026-06-10 06:31:59','2026-06-10 06:31:59'),
(776,'TRX-0908',37,17,2,19,19,NULL,70.00,NULL,'2026-06-10 06:31:59','2026-06-10 06:31:59'),
(777,'TRX-0909',38,17,2,15,19,NULL,80.00,NULL,'2026-06-10 06:32:26','2026-06-10 06:32:26'),
(778,'TRX-0910',38,17,2,19,19,NULL,80.00,NULL,'2026-06-10 06:32:26','2026-06-10 06:32:26'),
(779,'TRX-0911',39,17,2,15,19,NULL,80.00,NULL,'2026-06-10 06:32:56','2026-06-10 06:32:56'),
(780,'TRX-0912',39,17,2,19,19,NULL,80.00,NULL,'2026-06-10 06:32:56','2026-06-10 06:32:56'),
(781,'TRX-0913',53,17,2,15,19,NULL,80.00,NULL,'2026-06-10 06:33:12','2026-06-10 06:33:12'),
(782,'TRX-0914',53,17,2,19,19,NULL,80.00,NULL,'2026-06-10 06:33:12','2026-06-10 06:33:12'),
(783,'TRX-0915',40,17,2,15,19,NULL,80.00,NULL,'2026-06-10 06:33:30','2026-06-10 06:33:30'),
(784,'TRX-0916',40,17,2,19,19,NULL,80.00,NULL,'2026-06-10 06:33:30','2026-06-10 06:33:30'),
(785,'TRX-0917',41,17,2,15,19,NULL,80.00,NULL,'2026-06-10 06:33:47','2026-06-10 06:33:47'),
(786,'TRX-0918',41,17,2,19,19,NULL,80.00,NULL,'2026-06-10 06:33:47','2026-06-10 06:33:47'),
(787,'TRX-0919',42,17,2,15,19,NULL,70.00,NULL,'2026-06-10 06:34:05','2026-06-10 06:34:05'),
(788,'TRX-0920',42,17,2,19,19,NULL,80.00,NULL,'2026-06-10 06:34:05','2026-06-10 06:34:05'),
(789,'TRX-0921',43,17,2,15,19,NULL,70.00,NULL,'2026-06-10 06:34:25','2026-06-10 06:34:25'),
(790,'TRX-0922',43,17,2,19,19,NULL,70.00,NULL,'2026-06-10 06:34:25','2026-06-10 06:34:25'),
(791,'TRX-0923',52,17,2,15,19,NULL,80.00,NULL,'2026-06-10 06:34:42','2026-06-10 06:34:42'),
(792,'TRX-0924',52,17,2,19,19,NULL,80.00,NULL,'2026-06-10 06:34:42','2026-06-10 06:34:42'),
(793,'TRX-0925',56,17,2,15,19,NULL,80.00,NULL,'2026-06-10 06:35:00','2026-06-10 06:35:00'),
(794,'TRX-0926',56,17,2,19,19,NULL,80.00,NULL,'2026-06-10 06:35:00','2026-06-10 06:35:00'),
(795,'TRX-0927',57,17,2,15,19,NULL,80.00,NULL,'2026-06-10 06:35:19','2026-06-10 06:35:19'),
(796,'TRX-0928',57,17,2,19,19,NULL,80.00,NULL,'2026-06-10 06:35:19','2026-06-10 06:35:19'),
(797,'TRX-0929',58,17,2,15,19,NULL,75.00,NULL,'2026-06-10 06:35:35','2026-06-10 06:35:35'),
(798,'TRX-0930',58,17,2,19,19,NULL,75.00,NULL,'2026-06-10 06:35:35','2026-06-10 06:35:35'),
(799,'TRX-0931',59,17,2,15,19,NULL,80.00,NULL,'2026-06-10 06:35:49','2026-06-10 06:35:49'),
(800,'TRX-0932',59,17,2,19,19,NULL,80.00,NULL,'2026-06-10 06:35:49','2026-06-10 06:35:49'),
(801,'TRX-0933',34,17,2,15,19,NULL,80.00,NULL,'2026-06-10 06:36:02','2026-06-10 06:36:02'),
(802,'TRX-0934',34,17,2,19,19,NULL,80.00,NULL,'2026-06-10 06:36:03','2026-06-10 06:36:03'),
(803,'TRX-0935',60,17,2,15,19,NULL,75.00,NULL,'2026-06-10 06:36:25','2026-06-10 06:36:25'),
(804,'TRX-0936',60,17,2,19,19,NULL,75.00,NULL,'2026-06-10 06:36:25','2026-06-10 06:36:25'),
(805,'TRX-0937',45,17,2,15,19,NULL,80.00,NULL,'2026-06-10 06:36:40','2026-06-10 06:36:40'),
(806,'TRX-0938',45,17,2,19,19,NULL,80.00,NULL,'2026-06-10 06:36:40','2026-06-10 06:36:40'),
(807,'TRX-0939',46,17,2,15,19,NULL,80.00,NULL,'2026-06-10 06:36:58','2026-06-10 06:36:58'),
(808,'TRX-0940',46,17,2,19,19,NULL,80.00,NULL,'2026-06-10 06:36:58','2026-06-10 06:36:58'),
(809,'TRX-0941',47,17,2,15,19,NULL,70.00,NULL,'2026-06-10 06:37:13','2026-06-10 06:37:13'),
(810,'TRX-0942',47,17,2,19,19,NULL,70.00,NULL,'2026-06-10 06:37:13','2026-06-10 06:37:13'),
(811,'TRX-0943',48,17,2,15,19,NULL,70.00,NULL,'2026-06-10 06:37:30','2026-06-10 06:37:30'),
(812,'TRX-0944',48,17,2,19,19,NULL,65.00,NULL,'2026-06-10 06:37:30','2026-06-10 06:37:30'),
(813,'TRX-0945',49,17,2,15,19,NULL,70.00,NULL,'2026-06-10 06:37:47','2026-06-10 06:37:47'),
(814,'TRX-0946',49,17,2,19,19,NULL,70.00,NULL,'2026-06-10 06:37:47','2026-06-10 06:37:47'),
(815,'TRX-0947',50,17,2,15,19,NULL,80.00,NULL,'2026-06-10 06:38:01','2026-06-10 06:38:01'),
(816,'TRX-0948',50,17,2,19,19,NULL,80.00,NULL,'2026-06-10 06:38:01','2026-06-10 06:38:01'),
(817,'TRX-0949',52,16,2,15,17,NULL,87.00,NULL,'2026-06-10 06:45:24','2026-06-10 06:45:24'),
(818,'TRX-0950',52,16,2,19,17,NULL,88.00,NULL,'2026-06-10 06:45:24','2026-06-10 06:45:24'),
(819,'TRX-0951',45,10,2,20,14,NULL,85.00,NULL,'2026-06-10 06:50:56','2026-06-10 06:50:56'),
(820,'TRX-0952',45,10,2,21,14,NULL,80.00,NULL,'2026-06-10 06:50:56','2026-06-10 06:50:56'),
(821,'TRX-0953',45,10,2,25,14,NULL,83.00,NULL,'2026-06-10 06:50:56','2026-06-10 06:50:56'),
(822,'TRX-0954',45,10,2,22,15,NULL,83.00,NULL,'2026-06-10 06:50:56','2026-06-10 06:50:56'),
(823,'TRX-0955',45,10,2,23,15,NULL,85.00,NULL,'2026-06-10 06:50:56','2026-06-10 06:50:56'),
(824,'TRX-0956',45,10,2,24,15,NULL,84.00,NULL,'2026-06-10 06:50:56','2026-06-10 06:50:56'),
(825,'TRX-0957',45,10,2,29,16,NULL,80.00,NULL,'2026-06-10 06:50:56','2026-06-10 06:50:56'),
(826,'TRX-0958',45,10,2,26,16,NULL,82.00,NULL,'2026-06-10 06:50:56','2026-06-10 06:50:56'),
(827,'TRX-0959',45,10,2,27,16,NULL,83.00,NULL,'2026-06-10 06:50:56','2026-06-10 06:50:56'),
(828,'TRX-0960',45,10,2,28,16,NULL,80.00,NULL,'2026-06-10 06:50:56','2026-06-10 06:50:56'),
(829,'TRX-0981',35,10,2,20,14,NULL,0.00,NULL,'2026-06-10 06:52:14','2026-06-10 07:33:52'),
(830,'TRX-0982',35,10,2,21,14,NULL,0.00,NULL,'2026-06-10 06:52:14','2026-06-10 07:33:52'),
(831,'TRX-0983',35,10,2,25,14,NULL,0.00,NULL,'2026-06-10 06:52:14','2026-06-10 07:33:52'),
(832,'TRX-0984',35,10,2,22,15,NULL,0.00,NULL,'2026-06-10 06:52:14','2026-06-10 07:33:52'),
(833,'TRX-0985',35,10,2,23,15,NULL,0.00,NULL,'2026-06-10 06:52:14','2026-06-10 07:33:52'),
(834,'TRX-0986',35,10,2,24,15,NULL,0.00,NULL,'2026-06-10 06:52:14','2026-06-10 07:33:52'),
(835,'TRX-0987',35,10,2,29,16,NULL,0.00,NULL,'2026-06-10 06:52:14','2026-06-10 07:33:52'),
(836,'TRX-0988',35,10,2,26,16,NULL,0.00,NULL,'2026-06-10 06:52:14','2026-06-10 07:33:52'),
(837,'TRX-0989',35,10,2,27,16,NULL,0.00,NULL,'2026-06-10 06:52:14','2026-06-10 07:33:52'),
(838,'TRX-0990',35,10,2,28,16,NULL,0.00,NULL,'2026-06-10 06:52:14','2026-06-10 07:33:52');
/*!40000 ALTER TABLE `transaksi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user','tata_usaha') NOT NULL DEFAULT 'user',
  `pangkalan_id` bigint(20) unsigned DEFAULT NULL,
  `is_kepala` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_pangkalan_id_foreign` (`pangkalan_id`),
  KEY `idx_users_kepala` (`is_kepala`),
  KEY `idx_users_role` (`role`),
  CONSTRAINT `users_pangkalan_id_foreign` FOREIGN KEY (`pangkalan_id`) REFERENCES `pangkalan` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'Administrator','admin','admin@penilaian.com',NULL,'$2y$12$E6WsuGJY3g2tEMWqT4Uvfu1opztcjix9/joamBaTgEk.14FyGUigS','admin',NULL,0,'MdzyXGXYjhTp9gdGtg1AHjYz0DfyIuGRtgYPItwm6lUwxpf9nW41CsLx4mAo','2026-02-27 00:30:47','2026-02-27 00:30:47'),
(2,'User Penilai','user','user@penilaian.com',NULL,'$2y$12$djdlHO554Gv9j509VQVMwuu6e3Y3U8pB49AoGeyhNcd/6DLLtJUKm','user',NULL,0,NULL,'2026-02-27 00:30:47','2026-04-09 17:19:05'),
(4,'Fathul Mu\'in, S.Pd.','fathul','fathul@website.com',NULL,'$2y$12$ubEdapKfVNDFtOPH37eVuO/7yQhhKus/Srp5Sd5Gb3aDOvbtzpFju','user',1,1,'QemjujWykopO3E8RmaXRSXm31Lgj1pNQhqDjBG4mcGoRAlP0lPicR2pocEqc','2026-04-13 00:14:08','2026-06-08 08:42:18'),
(5,'Agus Ihsan','agus','agusihsann@gmail.com',NULL,'$2y$12$8B6TZegbrTu8Q6Iphl5sBuO4DbwcJkOB36FOJid1ZV9XKD996S1Dy','user',2,0,'qOkX3NbIY7oKCY9UiXP79Vd8scLfa8ZmvyDrJ2M4VeCLaiNwKIvuwdd2RFQv','2026-05-08 22:56:10','2026-05-08 22:56:10'),
(6,'Riza Wafirotun Nisa`','riza','rizawafirotunnisa@gmail.com',NULL,'$2y$12$06Z9AJc6kR.jocQPMkTlyeAJiTtszPJiao6A/4HS7cDKAImQE9F8y','user',1,0,'QAhrr9EkDOfWfxJLWmbFZKg1uSXHAkqasd5xNqpJP82WehaEvfoYCOVrNbTV','2026-05-09 00:08:00','2026-05-09 00:08:00'),
(7,'Ahmad Khoirul Amin','amin','ahmadkhoirulamin65@gmail.com',NULL,'$2y$12$nmHs3fSTJ23fC1hBAwBnP.AA0DrcEhfSMqzPEZX3F2zfQ4k1QlFFS','user',5,0,NULL,'2026-05-27 02:00:24','2026-05-27 02:00:24'),
(8,'Ahmad Shofyan Nur Shobah','ahmad','ahmadshofyannurshobah@gmail.com',NULL,'$2y$12$tcmnddlavipuflsPUZ0NSOH4rGsdlecAxjZCPxnBQjL0ozpaj3B.i','user',1,0,'LAzizste4AlTzR2QnqKxpbrdOdSTS6rkOjHDSXqVmkaUQmj2W4xSHsZzTIF8','2026-05-27 02:06:37','2026-05-27 02:06:37'),
(9,'Fadhil Nandila','fadhil','fadhilnandila62@gmail.com',NULL,'$2y$12$pvexvYsu/J3SCbfs9gzWTevAsjDEtUjIhrH3G3ORHuMXjoSLh6i5y','user',3,0,NULL,'2026-05-27 02:10:22','2026-05-27 02:10:22'),
(10,'Himmatul Ulya Hs','hima','himmatululyahs3@gmail.com',NULL,'$2y$12$AhjiXWYUg3NNaPQxFfBlRum4ehnZ0TwrMaXdCl6tPmCb1j1qoQ3Y6','user',9,0,NULL,'2026-05-27 02:15:06','2026-05-27 02:15:06'),
(11,'Indah Mustika Sari','indah','mustikasari17032007@gmail.com',NULL,'$2y$12$1oA9ZGrUmTt379CBZpQubeTjT61M8ElgzyoO4ILC/oeOdWqiuoZQa','user',NULL,0,NULL,'2026-05-27 02:57:55','2026-05-27 02:57:55'),
(12,'Khozainul Muna','muna','Khozainulmuna026@gmail.com',NULL,'$2y$12$rzZua93UJjqWYOeNKCITz.2Dte4OBhrvgKxj1tpNg80W8UUIQiJW6','user',5,0,NULL,'2026-05-27 03:01:15','2026-05-27 03:01:15'),
(13,'Maya Sulis Stiawati','maya','mayasulisstiawati@gmail.com',NULL,'$2y$12$c2lHn3JXZB05dNSjxSfyze9AxyzpI5JM3fpeIGFttO3CjrGDswZ1m','user',1,0,NULL,'2026-05-27 03:07:25','2026-05-27 03:07:25'),
(14,'Moh. Celvin Nugroho','celvin','muhammadcelvinnugroho.ypph.mts@gmail.com',NULL,'$2y$12$eY9IpdOY0bFndGTMs1LH8.PqOIuT/HNE4kBqRmYww3hgnx9V/zkr2','user',12,0,NULL,'2026-05-27 03:10:03','2026-05-27 03:10:03'),
(15,'Nabila Salwa Zanjabila','nabil','nabilslawazanzabila@gmail.com',NULL,'$2y$12$dQlkTyvq6iZMsYCjJd5jC.dMc/R5m0FMVDRLiGFQrFhz3Y228TQ1e','user',11,0,NULL,'2026-05-27 03:15:18','2026-05-27 03:15:18'),
(17,'Syakur Sofian Tahir','syukur','syukurshofiantahir@gmail.com',NULL,'$2y$12$vI8Lq6AlV1rY8CYvC04ntOP8jLVsw1wwQbFrD0kkeerV5D37wr.BK','user',9,0,NULL,'2026-05-27 05:20:04','2026-05-27 05:20:04'),
(18,'Umi Masruroh','umi','umimasruroh1607@gmail.com',NULL,'$2y$12$DDpg6nw3AzplnVqRDNfZK.1he3Z.C3eJrbF7hybETJ6HC8LgwjjOW','user',9,0,NULL,'2026-05-27 05:22:35','2026-05-27 05:22:35'),
(19,'Viko Romadhon','viko','vikoromadhon18@gmail.com',NULL,'$2y$12$DgW0OFFIgzDGO95.YfQgTeP6XcWk9aSjD.4.57AoUH2plLRezuQ56','user',11,0,NULL,'2026-05-27 05:26:23','2026-05-27 05:26:23'),
(20,'Wahyu Pratama','wahyu','wahyupratama@gmail.com',NULL,'$2y$12$TIbKdIk2aABjh8G9hM1e1OBU4Hl5efpYsbgooK1ePZ0fkPJ95Bfk2','user',3,0,NULL,'2026-05-27 05:28:54','2026-05-27 05:28:54'),
(21,'Yusuf Mahendra','yusuf','yusufmahendra866@gmail.com',NULL,'$2y$12$bs2LtMclpLwLvj62gN/M/OsIIFR0yrUGcfGuMw4QAqxO6nOln.vpC','user',9,0,NULL,'2026-05-27 05:31:30','2026-05-27 05:31:30'),
(22,'Zainul Rifa`i','zainul','zainulrifai@gmail.com',NULL,'$2y$12$9Xzu1eVhs.Ge9quKSLiZB.LuWrC4p6lUSHK4RMoRecKWKhx7YKV/C','user',5,0,NULL,'2026-05-27 05:33:22','2026-05-27 05:33:22'),
(23,'Nadiatun Na`imah','nadia','nadiatunnaimah10@gmail.com',NULL,'$2y$12$ummYHuaKRkaymqN3B6qy3ehobgmNRgu45A.P.eO1yCPUWWHGqCAFC','user',3,0,'wJkgts6OkLhfNIPNLqF12F3IhX36VIqZCVBpyfX421eAKqOpARdYi4qElMyL','2026-05-28 10:42:59','2026-05-28 10:42:59'),
(24,'Irma Safitri','irma','irmasaftiri@gmail.com',NULL,'$2y$12$X8CKog/uiBp2gEAUvBhHCeEse5AY7s3wlWrkeE7rzb3d3.6W8rliu','user',3,0,NULL,'2026-05-28 10:45:21','2026-05-28 10:45:21'),
(25,'Ananda Indah Pangestuti','nanda','anandaindahpangastuti@gmail.com',NULL,'$2y$12$IOXoCxP8BTuwZl34uezdVeB8dG.AUbx/pNr.ErEnMKPwszTGGHQki','user',5,0,NULL,'2026-05-28 10:49:58','2026-05-28 10:49:58'),
(26,'Ayu Royyana','ayu','ayuroyyana8@gmail.com',NULL,'$2y$12$2ZUjUoEkttinIsszRZncau/M6qeFvMswV0v5Sdip7UdB6EhDNRQKm','user',2,0,NULL,'2026-05-28 10:52:29','2026-05-28 10:52:29'),
(27,'Nashfa Erlina Sahli','nashfa','nashfaerlianasahli@gmail.com',NULL,'$2y$12$nmSbsCPIZrW1PnCOg5nNduF11DGon213MtQSxpjjPUH4UmRGgesO2','user',4,0,NULL,'2026-05-28 10:55:26','2026-05-28 10:55:26'),
(28,'Neli Syahputri','neli','nelysyaputri34@gmail.com',NULL,'$2y$12$dCbp5U1I4LRV1Em9wuXwV.DJzTY6gi1o3SpeLK0MNSYnrV6M4QFJS','user',9,0,NULL,'2026-05-28 10:58:32','2026-05-28 10:58:32'),
(29,'Putri Zahrotul Kholisah','putri','putrizahrotulkholisah@gmail.com',NULL,'$2y$12$glbMdZX6F6rSXzg4YRiTW.XmvLxmxh7BYLKtAiCmAv72TrpRhBv26','user',4,0,NULL,'2026-05-28 11:00:23','2026-05-28 11:00:23'),
(30,'Risna Maila Zulfa','risna','risnamailazulfa@gmail.com',NULL,'$2y$12$nTLs9CpLLwcidZsT2fBOIepu7PaZaH8i0SN14h1eMeQ75FAks6QPG','user',2,0,NULL,'2026-05-28 11:02:41','2026-05-28 11:02:41'),
(31,'Siti Nur Hasanah','siti','sitinurhasanah0114@gmail.com',NULL,'$2y$12$thohlcPftLxXKQYtKyoUSuJMzAD9OJSd1wY7ewvN5tkG777IEQFkK','user',4,0,NULL,'2026-05-28 11:05:08','2026-05-28 11:05:08'),
(32,'Drs. H. Anas, M.Pd','anas','anasmts112@gmail.com',NULL,'$2y$12$dzt6megxyyxgcdOKq6MAGeXCIe2Hf/Nl/ZHOpsA65gkOqz0K26NMq','user',2,1,'WqXxkHI6YLWmOGAIwStLaJloh2IidGQCRhHafw6KFPBnKuYdC8AjKq9aWz9d','2026-05-28 11:18:08','2026-06-07 09:49:28'),
(33,'Nina Marlina, S.Pd, M.E','nina','nienamarlina470@gmail.com',NULL,'$2y$12$EHqDWbOg6JCj2ru2lTP9neXaBFqSWmdZ0BBCOjdGOFlT2YM4P/5me','user',9,1,NULL,'2026-05-28 11:33:09','2026-06-05 08:58:52'),
(34,'Rahmat Budi Permana, S.Pd., M.E','rahmat','rbpermana@gmail.com',NULL,'$2y$12$c8CZLcRMv6Zty4GdS1To3OBg.v56tt8Tcp6P5JozaZvu8BPn.6g9O','user',11,1,NULL,'2026-05-28 11:35:31','2026-06-04 17:32:51'),
(35,'MUH. HARUN, S.H.I., M.Sos','babinlumni','harun.syirahay@gmail.com',NULL,'$2y$12$1pRW9gQgu52.Kl8d0Qekq.qakZHWeVmLKn7wZ.CEtlM/F8CZ22XXW','admin',NULL,0,'4rwGlbnmAPqNGXaxBryzuI3atWVIJWW7EZZeX2gSPxGfQCL4PbEEVjbEfr5v','2026-05-28 21:45:20','2026-05-28 21:45:20'),
(36,'MUH. HARUN, S.H.I., M.Sos','harun','harun.ysk4@gmail.com',NULL,'$2y$12$rww1XoZHEIWgihZl8zgof.0NnQ4JOfWKQ1KjSMB8D9KzhF/sQO/Sa','user',12,1,NULL,'2026-05-28 21:49:53','2026-06-10 02:21:46'),
(37,'BANDIYAH','bandiyah','bandiyah@gmail.com',NULL,'$2y$12$29AO4gP1.imkZjkMHmXfk.BKp2XnlL9.2TZVdzQ1MX4At7t4BISsi','user',4,1,NULL,'2026-06-02 06:14:52','2026-06-04 17:28:19'),
(38,'SITI JUARIAH','juariyah','juariyah@gmail.com',NULL,'$2y$12$5OTuiWsoiMMSDh2I4.ZkCukLNfaRovXaP9aoBkfwMr/lwP7yP0eXq','user',5,1,NULL,'2026-06-02 06:19:16','2026-06-04 17:29:12'),
(39,'MISRUN','misrun','misrun@gmail.com',NULL,'$2y$12$/Q2Qua5OAqzqvmQAJ6xcJOxEWwZE.i9hP/cisDPyqprEUm5pvKy7e','user',7,1,'OkzBpbUfKdo6Yj7VD5vHeQegX9HE1Uh1n0IHqybfe32tkqj7FutUwSz33Vhi','2026-06-02 06:30:33','2026-06-07 10:03:57'),
(40,'Sujitho, S.Pd. SD','sujitho','sujitho@gmail.com',NULL,'$2y$12$1.uq0mCJ8FBLHjHsdUlxyuJujemrzjLhNu4Y9lZHbmsVUECZvFpzu','user',3,1,'1kcCWNea7nhiBPassrQT0KRaJmNyXTLtZ9fSu5vsmU1Lr3civf6uXtlKvm0K','2026-06-06 05:12:55','2026-06-06 05:29:58'),
(41,'Nur Makmuroh','makmuroh','nurmakmuroh@gmail.com',NULL,'$2y$12$lJmUq9S8iZFaR95T5MS0b.0jzGu7EdfQqeQPTR37ACC5voOAcOvvm','user',6,1,NULL,'2026-06-06 06:10:25','2026-06-06 06:11:20'),
(42,'Drs. H. Ah. Masyhuri SA, M.Pd.I','masyhuri','masyhuri@gmail.com',NULL,'$2y$12$/f6Re32gRH3BT.XEo7P.vO.T94P3XNSdiLBLpQ.oWLSFWT./k6Wn.','user',17,1,NULL,'2026-06-07 09:52:00','2026-06-07 09:58:10');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'ypphmugo_apk'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-11  6:50:07
