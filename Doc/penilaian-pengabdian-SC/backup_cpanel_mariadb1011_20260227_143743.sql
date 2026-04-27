-- MySQL dump 10.13  Distrib 9.4.0, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: penilaian_pengabdian
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: (imported into selected database)
--

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
  `alamat` text DEFAULT NULL,
  `tugas_khusus` varchar(255) DEFAULT NULL,
  `tahun_penilaian_id` bigint(20) unsigned DEFAULT NULL,
  `pangkalan_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `karyawan_kode_karyawan_unique` (`kode_karyawan`),
  KEY `karyawan_tahun_penilaian_id_foreign` (`tahun_penilaian_id`),
  KEY `karyawan_user_id_foreign` (`user_id`),
  KEY `karyawan_pangkalan_id_foreign` (`pangkalan_id`),
  CONSTRAINT `karyawan_pangkalan_id_foreign` FOREIGN KEY (`pangkalan_id`) REFERENCES `pangkalan` (`id`) ON DELETE SET NULL,
  CONSTRAINT `karyawan_tahun_penilaian_id_foreign` FOREIGN KEY (`tahun_penilaian_id`) REFERENCES `tahun_penilaian` (`id`) ON DELETE SET NULL,
  CONSTRAINT `karyawan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `karyawan`
--

LOCK TABLES `karyawan` WRITE;
/*!40000 ALTER TABLE `karyawan` DISABLE KEYS */;
INSERT INTO `karyawan` VALUES (1,NULL,'KRY-0001','AMIRUL MU\'MININ',NULL,'Koperasi',1,9,'2026-02-27 07:30:48','2026-02-27 07:30:48'),(2,NULL,'KRY-0002','ANDISKA ARIA WIJAYA',NULL,'TU MI, Kemasjidan',1,3,'2026-02-27 07:30:48','2026-02-27 07:30:48'),(3,NULL,'KRY-0003','APRIANTI',NULL,'TU MI, MDTA',1,3,'2026-02-27 07:30:48','2026-02-27 07:30:48'),(4,NULL,'KRY-0004','ARABIAH',NULL,'Koperasi',1,9,'2026-02-27 07:30:48','2026-02-27 07:30:48'),(5,NULL,'KRY-0005','DARMA LUTFIA',NULL,'TU RA, MDTA',1,4,'2026-02-27 07:30:48','2026-02-27 07:30:48'),(6,NULL,'KRY-0006','DAVID GUSTIA PUTRA',NULL,'Depot, Taman',1,11,'2026-02-27 07:30:48','2026-02-27 07:30:48'),(7,NULL,'KRY-0007','DIANA LESTARI',NULL,'MDTA',1,6,'2026-02-27 07:30:48','2026-02-27 07:30:48'),(8,NULL,'KRY-0008','ERIN AZKA FUAD SAPUTRA',NULL,'TU MI',1,3,'2026-02-27 07:30:48','2026-02-27 07:30:48'),(9,NULL,'KRY-0009','FIDIANA HADIATUL HIKMAH',NULL,'Koperasi',1,9,'2026-02-27 07:30:48','2026-02-27 07:30:48'),(10,NULL,'KRY-0010','HAPPY FARIDAH',NULL,'TU RA',1,4,'2026-02-27 07:30:48','2026-02-27 07:30:48'),(11,NULL,'KRY-0011','HARI UTAMI',NULL,'Pustaka',1,12,'2026-02-27 07:30:48','2026-02-27 07:30:48'),(12,NULL,'KRY-0012','INTAN MAULIDDIAH',NULL,'BEND. MTs',1,2,'2026-02-27 07:30:48','2026-02-27 07:30:48'),(13,NULL,'KRY-0013','KHIKMATUL MARIA',NULL,'TU PAUD, MDTA',1,5,'2026-02-27 07:30:48','2026-02-27 07:30:48'),(14,NULL,'KRY-0014','LAILI BINTI HABIBAH',NULL,'Pustaka',1,12,'2026-02-27 07:30:48','2026-02-27 07:30:48'),(15,NULL,'KRY-0015','LAILIN NASOIHAH',NULL,'Bend. MI',1,3,'2026-02-27 07:30:48','2026-02-27 07:30:48'),(16,NULL,'KRY-0016','LILIK ALISTIN',NULL,'Koperasi',1,9,'2026-02-27 07:30:48','2026-02-27 07:30:48'),(17,NULL,'KRY-0017','LILY HERAWATI',NULL,'PAUD',1,5,'2026-02-27 07:30:48','2026-02-27 07:30:48'),(18,NULL,'KRY-0018','M. FITROH AHSANI',NULL,'Depot, Taman',1,11,'2026-02-27 07:30:48','2026-02-27 07:30:48'),(19,NULL,'KRY-0019','M. MUSTAQIM MAHMUDIN',NULL,'Depot, Taman',1,11,'2026-02-27 07:30:48','2026-02-27 07:30:48'),(20,NULL,'KRY-0020','MOH. AKMAL SUKMA WARDANI',NULL,'TU MA, Kemasjidan',1,1,'2026-02-27 07:30:48','2026-02-27 07:30:48'),(21,NULL,'KRY-0021','MUHAMAD IBNU ATHO\'ILAH',NULL,'TU MA',1,1,'2026-02-27 07:30:48','2026-02-27 07:30:48'),(22,NULL,'KRY-0022','NOFI ALFIANI',NULL,'TU MA',1,1,'2026-02-27 07:30:48','2026-02-27 07:30:48'),(23,NULL,'KRY-0023','NUR LINDA',NULL,'Pustaka',1,12,'2026-02-27 07:30:48','2026-02-27 07:30:48'),(24,NULL,'KRY-0024','NURIL HUDA FERDIANSYAH',NULL,'Koperasi',1,9,'2026-02-27 07:30:48','2026-02-27 07:30:48'),(25,NULL,'KRY-0025','PUTRI AGUSTINA',NULL,'PAUD',1,5,'2026-02-27 07:30:48','2026-02-27 07:30:48'),(26,NULL,'KRY-0026','RIFKA RIFIA FITRIANI',NULL,'TU MTs',1,2,'2026-02-27 07:30:48','2026-02-27 07:30:48'),(27,NULL,'KRY-0027','SITI MUNIROTUS SHOLIHAH',NULL,'Koperasi',1,9,'2026-02-27 07:30:48','2026-02-27 07:30:48'),(28,NULL,'KRY-0028','ULVA INAYATUL IFTAKHIYAH',NULL,'TU MA',1,1,'2026-02-27 07:30:48','2026-02-27 07:30:48');
/*!40000 ALTER TABLE `karyawan` ENABLE KEYS */;
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
  `bobot` decimal(5,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kategori_kinerja_kode_kategori_unique` (`kode_kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kategori_kinerja`
--

LOCK TABLES `kategori_kinerja` WRITE;
/*!40000 ALTER TABLE `kategori_kinerja` DISABLE KEYS */;
INSERT INTO `kategori_kinerja` VALUES (1,'KTG-001','Kedisiplinan',30.00,'2026-02-27 07:30:47','2026-02-27 07:30:47'),(2,'KTG-002','Karakter',40.00,'2026-02-27 07:30:47','2026-02-27 07:30:47'),(3,'KTG-003','Kompetensi',30.00,'2026-02-27 07:30:47','2026-02-27 07:30:47');
/*!40000 ALTER TABLE `kategori_kinerja` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kompetensi`
--

LOCK TABLES `kompetensi` WRITE;
/*!40000 ALTER TABLE `kompetensi` DISABLE KEYS */;
INSERT INTO `kompetensi` VALUES (1,'KMP-001',1,'Kehadiran','2026-02-27 07:30:47','2026-02-27 07:30:47'),(2,'KMP-002',1,'Tepat Waktu','2026-02-27 07:30:47','2026-02-27 07:30:47'),(3,'KMP-003',1,'Penggunaan Waktu','2026-02-27 07:30:47','2026-02-27 07:30:47'),(4,'KMP-004',2,'Kepatuhan','2026-02-27 07:30:47','2026-02-27 07:30:47'),(5,'KMP-005',2,'Loyalitas','2026-02-27 07:30:47','2026-02-27 07:30:47'),(6,'KMP-006',2,'Leadership','2026-02-27 07:30:47','2026-02-27 07:30:47'),(7,'KMP-007',2,'Tanggung-jawab','2026-02-27 07:30:47','2026-02-27 07:30:47'),(8,'KMP-008',3,'Inisiatif','2026-02-27 07:30:47','2026-02-27 07:30:47'),(9,'KMP-009',3,'Adaptasi','2026-02-27 07:30:47','2026-02-27 07:30:47'),(10,'KMP-010',3,'Pemecahan Masalah','2026-02-27 07:30:47','2026-02-27 07:30:47'),(11,'KMP-011',3,'Pengambilan Keputusan','2026-02-27 07:30:47','2026-02-27 07:30:47');
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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_02_26_235503_create_kategori_kinerja_table',1),(5,'2026_02_26_235503_create_tahun_penilaian_table',1),(6,'2026_02_26_235504_create_kompetensi_table',1),(7,'2026_02_26_235504_create_performance_rating_table',1),(8,'2026_02_26_235505_create_karyawan_table',1),(9,'2026_02_27_003552_create_mutasi_table',1),(10,'2026_02_27_003553_create_transaksi_table',1),(11,'2026_02_27_010311_add_user_id_to_karyawan_table',1),(12,'2026_02_27_013207_create_pangkalan_table',1),(13,'2026_02_27_013208_add_pangkalan_tugas_to_karyawan_table',1);
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
  `pimpinan_pos` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pangkalan_kode_pangkalan_unique` (`kode_pangkalan`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pangkalan`
--

LOCK TABLES `pangkalan` WRITE;
/*!40000 ALTER TABLE `pangkalan` DISABLE KEYS */;
INSERT INTO `pangkalan` VALUES (1,'PNG-001','MA AL-HUDA AL-ILAHIYAH','FATHUL MU\'IN, S.Pd.',NULL,'2026-02-27 07:30:48','2026-02-27 07:30:48'),(2,'PNG-002','MTs AL-HUDA AL-ILAHIYAH','Drs. ANAS',NULL,'2026-02-27 07:30:48','2026-02-27 07:30:48'),(3,'PNG-003','MI AL-HUDA AL-ILAHIYAH','SUJITHO, S.Pd.SD',NULL,'2026-02-27 07:30:48','2026-02-27 07:30:48'),(4,'PNG-004','RA AL-HUDA AL-ILAHIYAH','BANDIAH, S.Pd.',NULL,'2026-02-27 07:30:48','2026-02-27 07:30:48'),(5,'PNG-005','PAUD DASARI BUDI','SITI JUARIAH, S.Pd.I',NULL,'2026-02-27 07:30:48','2026-02-27 07:30:48'),(6,'PNG-006','MDTA AL-HUDA AL-ILAHIYAH','NUR MAKMUROH',NULL,'2026-02-27 07:30:48','2026-02-27 07:30:48'),(7,'PNG-007','PENGURUS PONPES PA','MISRUN, S.Ag.',NULL,'2026-02-27 07:30:48','2026-02-27 07:30:48'),(8,'PNG-008','PENGURUS PONPES PI','NINA MARLINA, S.Pd.',NULL,'2026-02-27 07:30:48','2026-02-27 07:30:48'),(9,'PNG-009','KOPERASI PONTREN','NINA MARLINA, S.Pd.',NULL,'2026-02-27 07:30:48','2026-02-27 07:30:48'),(10,'PNG-010','KEMASJIDAN','Drs. ANAS',NULL,'2026-02-27 07:30:48','2026-02-27 07:30:48'),(11,'PNG-011','DEPOT AIR, TAMAN, LOGISTIK','RAHMAT BUDI PERMANA, S.Pd.',NULL,'2026-02-27 07:30:48','2026-02-27 07:30:48'),(12,'PNG-012','PERPUSTAKAAN',NULL,NULL,'2026-02-27 07:30:48','2026-02-27 07:30:48'),(13,'PNG-013','PEMBINA PRAMUKA','FATHUL MU\'IN, S.Pd.',NULL,'2026-02-27 07:30:48','2026-02-27 07:30:48');
/*!40000 ALTER TABLE `pangkalan` ENABLE KEYS */;
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
INSERT INTO `performance_rating` VALUES (1,'RTG-001','A (Sangat Baik)','Nilai 90 ke atas','2026-02-27 07:30:47','2026-02-27 07:30:47'),(2,'RTG-002','B (Baik)','Nilai 80 - 89','2026-02-27 07:30:47','2026-02-27 07:30:47'),(3,'RTG-003','C (Cukup)','Nilai 70 - 79','2026-02-27 07:30:47','2026-02-27 07:30:47'),(4,'RTG-004','D (Kurang)','Nilai 60 - 69','2026-02-27 07:30:47','2026-02-27 07:30:47'),(5,'RTG-005','E (Sangat Kurang)','Nilai di bawah 60','2026-02-27 07:30:48','2026-02-27 07:30:48');
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tahun_penilaian`
--

LOCK TABLES `tahun_penilaian` WRITE;
/*!40000 ALTER TABLE `tahun_penilaian` DISABLE KEYS */;
INSERT INTO `tahun_penilaian` VALUES (1,'2023/2024','Tahun Pengabdian 2023/2024',1,'2026-02-27 07:30:47','2026-02-27 07:30:47');
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
  CONSTRAINT `transaksi_karyawan_id_foreign` FOREIGN KEY (`karyawan_id`) REFERENCES `karyawan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transaksi_kompetensi_id_foreign` FOREIGN KEY (`kompetensi_id`) REFERENCES `kompetensi` (`id`) ON DELETE SET NULL,
  CONSTRAINT `transaksi_performance_rating_id_foreign` FOREIGN KEY (`performance_rating_id`) REFERENCES `performance_rating` (`id`) ON DELETE SET NULL,
  CONSTRAINT `transaksi_tahun_penilaian_id_foreign` FOREIGN KEY (`tahun_penilaian_id`) REFERENCES `tahun_penilaian` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksi`
--

LOCK TABLES `transaksi` WRITE;
/*!40000 ALTER TABLE `transaksi` DISABLE KEYS */;
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
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrator','admin','admin@penilaian.com',NULL,'$2y$12$E6WsuGJY3g2tEMWqT4Uvfu1opztcjix9/joamBaTgEk.14FyGUigS','admin',NULL,'2026-02-27 07:30:47','2026-02-27 07:30:47'),(2,'User Penilai','user','user@penilaian.com',NULL,'$2y$12$djdlHO554Gv9j509VQVMwuu6e3Y3U8pB49AoGeyhNcd/6DLLtJUKm','user',NULL,'2026-02-27 07:30:47','2026-02-27 07:30:47'),(3,'Budi Santoso','budi','budi@penilaian.com',NULL,'$2y$12$Rc3hQ9tu9DuT0vt5dbygaur46q0x8UT62wvdHm8GLDNCRTmK8zRhe','user',NULL,'2026-02-27 07:30:47','2026-02-27 07:30:47');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'penilaian_pengabdian'
--

--
-- Dumping routines for database 'penilaian_pengabdian'
--
--
-- WARNING: can't read the INFORMATION_SCHEMA.libraries table. It's most probably an old server 5.5.5-10.4.32-MariaDB.
--

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-02-27 14:37:43
