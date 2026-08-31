-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: localhost    Database: simmasserkom
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
-- Table structure for table `absensi`
--

DROP TABLE IF EXISTS `absensi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `absensi` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `siswa_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date NOT NULL,
  `jam_masuk` time DEFAULT NULL,
  `jam_pulang` time DEFAULT NULL,
  `status` enum('hadir','sakit','izin','alfa') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'hadir',
  `photo_masuk_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo_pulang_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_validasi` enum('pending','disetujui','ditolak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `catatan_guru` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `absensi_siswa_id_tanggal_unique` (`siswa_id`,`tanggal`),
  CONSTRAINT `absensi_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `absensi`
--

LOCK TABLES `absensi` WRITE;
/*!40000 ALTER TABLE `absensi` DISABLE KEYS */;
INSERT INTO `absensi` VALUES ('01a05116-1ef1-7162-81da-46a1ba0a8fe3','1bfe42a9-e364-4388-97a7-68733a6935b8','2026-08-30','12:12:00','17:18:00','hadir','absensi/1VzJsuqxagOyqFYCWbak66YbnxJjN6HeTTdSWXF1.jpg','absensi/KIZSYjt3MLEk8Rk0T6KPi7TPh36boFWVKqWBuK2Y.jpg','disetujui',NULL,'2026-08-29 22:13:19','2026-08-29 22:14:01'),('01a052dd-0e1f-720d-97d8-191af3b41ab5','01a051c5-6523-739a-b47d-761f6a18e3fe','2026-08-30','20:30:11',NULL,'hadir','absensi/qhjfrhcIdCPwHiSGVDxPkSuCLK7PqFiLCKETh0dT.jpg',NULL,'disetujui',NULL,'2026-08-30 06:30:13','2026-08-30 06:30:13'),('01a052f8-06c8-720d-b249-7846c53b28d7','01a046e9-bd1e-718c-9569-2355fe5022a4','2026-08-30','20:59:40',NULL,'hadir','absensi/10mBf6tfiJQM3EiWS92cQP09V2NkgcfHmr9g9GAF.jpg',NULL,'disetujui',NULL,'2026-08-30 06:59:41','2026-08-30 06:59:41'),('01a05657-486c-70c7-9d9d-efabc0c6dc4f','1bfe42a9-e364-4388-97a7-68733a6935b8','2026-08-31','12:42:35',NULL,'hadir','absensi/EnFT3aaxnuRUndoNK8oCF1kacebjnKwXBVEORz7y.jpg',NULL,'disetujui',NULL,'2026-08-30 22:42:35','2026-08-30 22:42:35');
/*!40000 ALTER TABLE `absensi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_logs` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` enum('info','warn','error') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'info',
  `action_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `actor_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `actor_role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
INSERT INTO `activity_logs` VALUES ('01a046a8-8823-71d8-8bab-39d5e851d32b','info','CREATE_GURU','admin@simmas.sch.id','admin','127.0.0.1','{\"description\": \"Menambahkan guru baru: Kirana Wikasandri.Sp.d\"}','2026-08-28 04:37:25'),('01a046a9-360b-705a-a8ac-54489959f3a9','info','UPDATE_STATUS_GURU','admin@simmas.sch.id','admin','127.0.0.1','{\"description\": \"Mengubah status guru Kirana Wikasandri.Sp.d menjadi Nonaktif\"}','2026-08-28 04:38:09'),('01a046a9-5f93-710c-a656-99a147337228','info','UPDATE_STATUS_GURU','admin@simmas.sch.id','admin','127.0.0.1','{\"description\": \"Mengubah status guru Kirana Wikasandri.Sp.d menjadi Aktif\"}','2026-08-28 04:38:20'),('01a046e8-e0ba-71a2-a383-eecbc5137c9c','info','CREATE_DUDI','admin@simmas.sch.id','admin','127.0.0.1','{\"description\": \"Menambahkan mitra DUDI: Cv.Hexa Integra Mandiri\"}','2026-08-28 05:47:42'),('01a046e9-bd27-700e-b68e-a44783eb37ad','info','CREATE_SISWA','admin@simmas.sch.id','admin','127.0.0.1','{\"description\": \"Menambahkan siswa baru: Fitria Tahta Alfina\"}','2026-08-28 05:48:38'),('01a04c44-9cc5-720a-88be-d35c2a5536ef','info','UPDATE_VERIFIKASI_DUDI','admin@simmas.sch.id','admin','127.0.0.1','{\"description\": \"Mengubah status verifikasi Cv.Hexa Integra Mandiri menjadi terverifikasi\"}','2026-08-29 06:46:00'),('01a04c4c-de74-73d1-814d-14c16fdef4d9','info','CREATE_PENEMPATAN','admin@simmas.sch.id','admin','127.0.0.1','{\"description\": \"Menempatkan siswa ID 01a046e9-bd1e-718c-9569-2355fe5022a4 ke Cv.Hexa Integra Mandiri\"}','2026-08-29 06:55:01'),('01a04c4e-1f72-714f-8d4c-df23912127f6','info','SAHKAN_PENEMPATAN','admin@simmas.sch.id','admin','127.0.0.1','{\"description\": \"Mengubah status pengesahan penempatan ID 01a04c4c-de52-70b7-95ee-24eefe257ff9 menjadi disahkan\"}','2026-08-29 06:56:23'),('01a0510b-533c-71b6-88dc-6d19f0e4b1d1','info','PENGAJUAN_MAGANG','siswa@simmas.sch.id','siswa','127.0.0.1','{\"posisi\": \"Web Developer\", \"description\": \"Siswa Siswa SIMMAS mengajukan magang di Cv.Hexa Integra Mandiri posisi Web Developer\", \"pengajuan_id\": \"01a0510b-530c-734a-b828-5f533ecad157\", \"tempat_magang\": \"Cv.Hexa Integra Mandiri\"}','2026-08-30 05:01:31'),('01a05116-1f08-7195-8afe-589213454da7','info','ABSENSI_MASUK','siswa@simmas.sch.id','siswa','127.0.0.1','{\"jam\": \"12:12\", \"status\": \"hadir\", \"tanggal\": \"2026-08-30\", \"description\": \"Siswa Siswa SIMMAS melakukan ABSENSI_MASUK tanggal 2026-08-30 jam 12:12\"}','2026-08-30 05:13:19'),('01a05116-c290-7150-b356-f0a555e9778a','info','ABSENSI_PULANG','siswa@simmas.sch.id','siswa','127.0.0.1','{\"jam\": \"17:18\", \"status\": \"hadir\", \"tanggal\": \"2026-08-30\", \"description\": \"Siswa Siswa SIMMAS melakukan ABSENSI_PULANG tanggal 2026-08-30 jam 17:18\"}','2026-08-30 05:14:01'),('01a05120-7ca5-7341-9458-d990e37bcae1','info','TULIS_JURNAL','siswa@simmas.sch.id','siswa','127.0.0.1','{\"tanggal\": \"2026-08-30\", \"jurnal_id\": \"01a05120-7c85-70e7-a7b5-e1223a14d4a2\", \"description\": \"Siswa Siswa SIMMAS menulis jurnal kegiatan tanggal 2026-08-30\"}','2026-08-30 05:24:38'),('01a0512e-f7b3-708e-a783-7fc474f10bcd','info','UPDATE_SISWA','admin@simmas.sch.id','admin','127.0.0.1','{\"description\": \"Memperbarui data siswa: Siswa SIMMAS\"}','2026-08-30 05:40:27'),('01a05148-c354-7089-aab5-1be1f755b226','info','SETUJUI_PENGAJUAN','admin@simmas.sch.id','admin','127.0.0.1','{\"description\": \"Admin menyetujui pengajuan dari Siswa SIMMAS ke Cv.Hexa Integra Mandiri\"}','2026-08-30 06:08:38'),('01a05152-8626-7187-bc2c-8975cb6a08c8','info','UPDATE_PENEMPATAN','admin@simmas.sch.id','admin','127.0.0.1','{\"description\": \"Memperbarui penempatan ID 01a04c4c-de52-70b7-95ee-24eefe257ff9\"}','2026-08-30 06:19:17'),('01a05159-7acf-72e7-b03b-59d6b74ced1c','info','UPDATE_PROFIL','2244@siswa.smk.sch.id','siswa','127.0.0.1','{\"description\": \"Siswa Siswa Test Update memperbarui profil / kata sandi\"}','2026-08-30 06:26:53'),('01a05159-a2ab-7249-b056-6fc455c93a1b','info','UPDATE_PROFIL','2244@siswa.smk.sch.id','siswa','127.0.0.1','{\"description\": \"Siswa Nama Baru Siswa memperbarui profil / kata sandi\"}','2026-08-30 06:27:03'),('01a0515d-5978-7111-9e9d-903c7f868fd4','info','UPDATE_PROFIL','siswa@simmas.sch.id','siswa','127.0.0.1','{\"description\": \"Siswa Siswa SIMMAS Update memperbarui pengaturan profil / kata sandi\"}','2026-08-30 06:31:07'),('01a0515d-5b05-71a8-a9ec-79587172fed7','info','UPDATE_PROFIL','siswa@simmas.sch.id','siswa','127.0.0.1','{\"description\": \"Siswa Siswa SIMMAS memperbarui pengaturan profil / kata sandi\"}','2026-08-30 06:31:07'),('01a0515f-dbed-704c-a542-b53e8bb6a3d1','info','UPDATE_SETTINGS','admin@simmas.sch.id','admin','127.0.0.1','{\"description\": \"Memperbarui pengaturan sistem SIMMAS\"}','2026-08-30 06:33:51'),('01a05160-6186-72bd-9e9f-90829aa25e0d','info','UPDATE_SETTINGS','admin@simmas.sch.id','admin','127.0.0.1','{\"description\": \"Memperbarui pengaturan sistem SIMMAS\"}','2026-08-30 06:34:26'),('01a05163-1c51-72d3-b6e7-bc91acd410ae','info','UPDATE_SETTINGS','admin@simmas.sch.id','admin','127.0.0.1','{\"description\": \"Memperbarui pengaturan sistem SIMMAS\"}','2026-08-30 06:37:24'),('01a05163-5d84-7351-a425-aeb102300900','info','UPDATE_SETTINGS','admin@simmas.sch.id','admin','127.0.0.1','{\"description\": \"Memperbarui pengaturan sistem SIMMAS\"}','2026-08-30 06:37:41'),('01a051c0-1a69-70d6-b049-0fb7049971ee','info','UPDATE_GURU','admin@simmas.sch.id','admin','127.0.0.1','{\"description\": \"Memperbarui data guru: Guru SIMMAS\"}','2026-08-30 08:18:59'),('01a051c3-1632-7261-a70b-42591c97d87d','info','CREATE_GURU','admin@simmas.sch.id','admin','127.0.0.1','{\"description\": \"Menambahkan guru baru: Bunga Lestari\"}','2026-08-30 08:22:14'),('01a051c5-6531-73f1-948d-5b65f6d2acd9','info','CREATE_SISWA','admin@simmas.sch.id','admin','127.0.0.1','{\"description\": \"Menambahkan siswa baru: Akhdan\"}','2026-08-30 08:24:46'),('01a051c6-dd61-72f1-a549-c7e2a322b90a','info','UPDATE_STATUS_SISWA','admin@simmas.sch.id','admin','127.0.0.1','{\"description\": \"Mengubah status siswa Nama Baru Siswa menjadi Nonaktif\"}','2026-08-30 08:26:22'),('01a051d4-440c-712b-902a-780a610a29f8','info','CREATE_SISWA','admin@simmas.sch.id','admin','127.0.0.1','{\"description\": \"Menambahkan siswa baru: Budi Pratama Wijaya\"}','2026-08-30 08:41:00'),('01a051d5-4892-7215-9747-aa4dc2acb649','info','CREATE_SISWA','admin@simmas.sch.id','admin','127.0.0.1','{\"description\": \"Menambahkan siswa baru: Dhea\"}','2026-08-30 08:42:07'),('01a052dd-0e3b-72a4-83b9-1bf7022191f6','info','ABSENSI_MASUK','akhdan@siswa.smk.sch.id','siswa','127.0.0.1','{\"jam\": \"20:30:11\", \"status\": \"hadir\", \"tanggal\": \"2026-08-30\", \"description\": \"Siswa Akhdan melakukan ABSENSI_MASUK tanggal 2026-08-30 jam 20:30:11\"}','2026-08-30 13:30:13'),('01a052eb-3d43-71ac-9b8e-97772b967035','info','CREATE_DUDI','admin@simmas.sch.id','admin','127.0.0.1','{\"description\": \"Menambahkan mitra DUDI: Cv. Gyituri Developer\"}','2026-08-30 13:45:43'),('01a052eb-757e-72fb-bf4b-83d93e29a152','info','UPDATE_VERIFIKASI_DUDI','admin@simmas.sch.id','admin','127.0.0.1','{\"description\": \"Mengubah status verifikasi Cv. Gyituri Developer menjadi terverifikasi\"}','2026-08-30 13:45:57'),('01a052ef-5abc-7291-a724-7a09d73223fa','info','CREATE_DUDI','admin@simmas.sch.id','admin','127.0.0.1','{\"description\": \"Menambahkan mitra DUDI: House Proggrammer\"}','2026-08-30 13:50:13'),('01a052f0-cbc9-721e-906a-40b2d87133b3','info','UPDATE_DUDI','admin@simmas.sch.id','admin','127.0.0.1','{\"description\": \"Memperbarui data mitra: Cv. Gyituri Developer\"}','2026-08-30 13:51:47'),('01a052f1-582b-7004-9112-bc408ac711ce','info','UPDATE_DUDI','admin@simmas.sch.id','admin','127.0.0.1','{\"description\": \"Memperbarui data mitra: Lion Marketing\"}','2026-08-30 13:52:23'),('01a052f3-032c-70c8-bb0a-d193a81d1c63','info','CREATE_DUDI','admin@simmas.sch.id','admin','127.0.0.1','{\"description\": \"Menambahkan mitra DUDI: IT Companny\"}','2026-08-30 13:54:12'),('01a052f3-b938-71be-a0eb-d879a965417f','info','UPDATE_DUDI','admin@simmas.sch.id','admin','127.0.0.1','{\"description\": \"Memperbarui data mitra: Cv. Gyituri Developer\"}','2026-08-30 13:54:59'),('01a052f8-06e0-7224-8282-70558bd9cdc5','info','ABSENSI_MASUK','nama.baru@siswa.smk.sch.id','siswa','127.0.0.1','{\"jam\": \"20:59:40\", \"status\": \"hadir\", \"tanggal\": \"2026-08-30\", \"description\": \"Siswa Nama Baru Siswa melakukan ABSENSI_MASUK tanggal 2026-08-30 jam 20:59:40\"}','2026-08-30 13:59:41'),('01a05341-37e3-71af-ae16-52d91385892b','info','UPDATE_PENEMPATAN','admin@simmas.sch.id','admin','127.0.0.1','{\"description\": \"Memperbarui penempatan ID 01a051b0-15b5-73ac-8c7d-50d8413320b6\"}','2026-08-30 15:19:38'),('01a05657-4882-73bf-a074-5ea02b3c8b76','info','ABSENSI_MASUK','siswa@simmas.sch.id','siswa','127.0.0.1','{\"jam\": \"12:42:35\", \"status\": \"hadir\", \"tanggal\": \"2026-08-31\", \"description\": \"Siswa Siswa SIMMAS melakukan ABSENSI_MASUK tanggal 2026-08-31 jam 12:42:35\"}','2026-08-31 05:42:35'),('01a056bb-01f5-7038-a59c-4e45ea88a1db','info','PENGAJUAN_MAGANG','akhdan@siswa.smk.sch.id','siswa','127.0.0.1','{\"posisi\": \"Software Engineering\", \"description\": \"Siswa Akhdan mengajukan magang di IT Companny posisi Software Engineering\", \"penempatan_id\": \"01a056bb-01a4-71a9-8f90-a29ffa962067\", \"tempat_magang\": \"IT Companny\"}','2026-08-31 07:31:31');
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Table structure for table `guru`
--

DROP TABLE IF EXISTS `guru`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guru` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip` varchar(18) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jurusan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `guru_nip_unique` (`nip`),
  KEY `guru_user_id_foreign` (`user_id`),
  CONSTRAINT `guru_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `profiles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guru`
--

LOCK TABLES `guru` WRITE;
/*!40000 ALTER TABLE `guru` DISABLE KEYS */;
INSERT INTO `guru` VALUES ('01a046a8-87f7-702a-a938-c75fc2531704','01a046a8-8752-7144-9362-4a3d843e0d4b','111222333444567876','Teknik Komputer & Jaringan (TKJ)',1,'2026-08-27 21:37:25','2026-08-27 21:38:20'),('01a051c3-1623-7378-a4ff-01947d8f64e3','01a051c3-1610-718f-b509-138092ff1f60','888888888888888888','Multimedia (MM)',1,'2026-08-30 01:22:14','2026-08-30 01:22:14'),('6ed39297-9799-4a26-a0a0-fc6631001520','6290732e-cd3a-425d-92e9-8dec57339f57','199001012020121001','Rekayasa Perangkat Lunak (RPL)',1,'2026-08-27 07:37:09','2026-08-30 01:18:59');
/*!40000 ALTER TABLE `guru` ENABLE KEYS */;
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
-- Table structure for table `jurnal_harian`
--

DROP TABLE IF EXISTS `jurnal_harian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jurnal_harian` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `siswa_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date NOT NULL,
  `kegiatan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `kendala` text COLLATE utf8mb4_unicode_ci,
  `solusi` text COLLATE utf8mb4_unicode_ci,
  `photo_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_verifikasi` enum('menunggu','disetujui','revisi') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `catatan_guru` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jurnal_harian_siswa_id_foreign` (`siswa_id`),
  CONSTRAINT `jurnal_harian_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jurnal_harian`
--

LOCK TABLES `jurnal_harian` WRITE;
/*!40000 ALTER TABLE `jurnal_harian` DISABLE KEYS */;
INSERT INTO `jurnal_harian` VALUES ('01a05120-7c85-70e7-a7b5-e1223a14d4a2','1bfe42a9-e364-4388-97a7-68733a6935b8','2026-08-30','menambahkan fitur absensi siswa','mismatch antara nama db dan nama file','konsultasi ke pembimbing dan mencoba beberapa kali','jurnal/MUFyf0uqJsQijvfDzBaRwTy7Yj2Moup0kbCshejk.png','disetujui','bagus,selanjutnya lebih baik lagi ya','2026-08-29 22:24:38','2026-08-30 08:22:53');
/*!40000 ALTER TABLE `jurnal_harian` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kunjungan`
--

DROP TABLE IF EXISTS `kunjungan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kunjungan` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guru_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tempat_magang_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date NOT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kunjungan_guru_id_foreign` (`guru_id`),
  KEY `kunjungan_tempat_magang_id_foreign` (`tempat_magang_id`),
  CONSTRAINT `kunjungan_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kunjungan_tempat_magang_id_foreign` FOREIGN KEY (`tempat_magang_id`) REFERENCES `tempat_magang` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kunjungan`
--

LOCK TABLES `kunjungan` WRITE;
/*!40000 ALTER TABLE `kunjungan` DISABLE KEYS */;
INSERT INTO `kunjungan` VALUES ('01a04ea7-8811-72d4-8586-0c79440c9bfb','6ed39297-9799-4a26-a0a0-fc6631001520','01a046e8-e0a3-7185-ae06-a7715494d359','2026-08-30','bisa menjelaskan backend dengan baik dan bisa bekerja sama tim dengan baik','kunjungan/k3dAoCuLJVeVTr5xB3WMp4TvXkoUSnGNi4ejZygE.jpg','2026-08-29 10:53:17','2026-08-29 11:03:15');
/*!40000 ALTER TABLE `kunjungan` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_08_26_125618_create_profiles_table',1),(5,'2026_08_26_125632_create_guru_table',1),(6,'2026_08_26_125638_create_siswa_table',1),(7,'2026_08_26_125649_create_tempat_magang_table',1),(8,'2026_08_26_125657_create_pengajuan_magang_table',1),(9,'2026_08_26_125742_create_penempatan_magang_table',1),(10,'2026_08_26_125750_create_absensi_table',1),(11,'2026_08_26_125757_create_jurnal_harian_table',1),(12,'2026_08_26_125805_create_kunjungan_table',1),(13,'2026_08_26_125812_create_activity_logs_table',1),(14,'2026_08_28_041119_change_user_id_type_in_sessions_table',2),(15,'2026_08_31_000000_update_penempatan_for_pengajuan_siswa',3);
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
-- Table structure for table `penempatan_magang`
--

DROP TABLE IF EXISTS `penempatan_magang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `penempatan_magang` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `siswa_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tempat_magang_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guru_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `posisi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `status_pengesahan` enum('menunggu','belum_disahkan','disahkan','ditolak','lulus_magang') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum_disahkan',
  `catatan_penolakan` text COLLATE utf8mb4_unicode_ci,
  `nilai_akhir` tinyint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `penempatan_magang_siswa_id_foreign` (`siswa_id`),
  KEY `penempatan_magang_tempat_magang_id_foreign` (`tempat_magang_id`),
  KEY `penempatan_magang_guru_id_foreign` (`guru_id`),
  CONSTRAINT `penempatan_magang_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`) ON DELETE CASCADE,
  CONSTRAINT `penempatan_magang_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE,
  CONSTRAINT `penempatan_magang_tempat_magang_id_foreign` FOREIGN KEY (`tempat_magang_id`) REFERENCES `tempat_magang` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `penempatan_magang`
--

LOCK TABLES `penempatan_magang` WRITE;
/*!40000 ALTER TABLE `penempatan_magang` DISABLE KEYS */;
INSERT INTO `penempatan_magang` VALUES ('01a04c4c-de52-70b7-95ee-24eefe257ff9','01a046e9-bd1e-718c-9569-2355fe5022a4','01a046e8-e0a3-7185-ae06-a7715494d359','6ed39297-9799-4a26-a0a0-fc6631001520',NULL,'2026-08-29','2026-12-30','disahkan',NULL,90,'2026-08-28 23:55:01','2026-08-30 07:18:06'),('01a0510b-530c-734a-b828-5f533ecad157','1bfe42a9-e364-4388-97a7-68733a6935b8','01a046e8-e0a3-7185-ae06-a7715494d359',NULL,'Web Developer','2026-08-30','2026-11-30','belum_disahkan',NULL,NULL,'2026-08-29 22:01:31','2026-08-29 23:08:36'),('01a051b0-15b5-73ac-8c7d-50d8413320b6','1bfe42a9-e364-4388-97a7-68733a6935b8','01a046e8-e0a3-7185-ae06-a7715494d359','01a051c3-1623-7378-a4ff-01947d8f64e3',NULL,'2026-08-01','2026-11-30','disahkan',NULL,NULL,'2026-08-30 01:01:29','2026-08-30 08:19:38'),('01a056bb-01a4-71a9-8f90-a29ffa962067','01a051c5-6523-739a-b47d-761f6a18e3fe','01a052f3-0319-710a-ab0c-821ffeb185c8',NULL,'Software Engineering','2026-08-31','2026-12-01','menunggu',NULL,NULL,'2026-08-31 00:31:31','2026-08-31 00:31:31');
/*!40000 ALTER TABLE `penempatan_magang` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profiles`
--

DROP TABLE IF EXISTS `profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `profiles` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','guru','siswa') COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `profiles_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profiles`
--

LOCK TABLES `profiles` WRITE;
/*!40000 ALTER TABLE `profiles` DISABLE KEYS */;
INSERT INTO `profiles` VALUES ('01a046a8-8752-7144-9362-4a3d843e0d4b','Kirana Wikasandri.Sp.d','kirana.wikasandri.sp.d@smk.sch.id',NULL,'$2y$12$X9wvVUSTiZSQkoCxh4e6IO9dR9I7BT2srtFN0OMLLR1gNLm3gOya2','guru',NULL,'2026-08-27 21:37:25','2026-08-27 21:37:25'),('01a046e9-bd0c-71bf-9f0b-58648f1cfb79','Nama Baru Siswa','nama.baru@siswa.smk.sch.id',NULL,'$2y$12$KHJyIeml/74nI71utiicMeXreBj7RSbax9s27JRR0iRsvOY66EOOC','siswa',NULL,'2026-08-27 22:48:38','2026-08-30 01:39:45'),('01a051c3-1610-718f-b509-138092ff1f60','Bunga Lestari','bunga.lestari@smk.sch.id',NULL,'$2y$12$SF9MfWFeIFuR5QCUmtwcv.xSlM3uwvfI5oOBtIIwlrA42oRQU8b1O','guru',NULL,'2026-08-30 01:22:14','2026-08-30 01:22:14'),('01a051c5-6510-72f0-b259-b70996e40a3d','Akhdan','akhdan@siswa.smk.sch.id',NULL,'$2y$12$/bdz342UaxFvTs/lc/PT3eaWFl4kagXMY0juz84N4pebT7nciUGl6','siswa',NULL,'2026-08-30 01:24:46','2026-08-30 01:39:44'),('01a051d5-4879-72ae-a1cf-ef344cc4353c','Dhea','dhea@siswa.smk.sch.id',NULL,'$2y$12$NilPfCFQIuufatbAPutwvuhdJh0ksIxss528XesSZwMuXTw5UcmKS','siswa',NULL,'2026-08-30 01:42:07','2026-08-30 01:42:07'),('6290732e-cd3a-425d-92e9-8dec57339f57','Guru SIMMAS','guru@simmas.sch.id',NULL,'$2y$12$TVG9T3T6saUYAU5dxK93mOiWvgxxDuRxpDbwdyMpXjAscP1EuF8N.','guru',NULL,'2026-08-27 07:37:09','2026-08-30 01:18:59'),('787b9fd3-5458-4b42-8b37-6905a96e844d','Siswa SIMMAS','siswa@simmas.sch.id',NULL,'$2y$12$FhveNufBqA8GihVuUv0VL.m1j6frG.lB2HfR4jBPQwZv0tMUXpBNm','siswa',NULL,'2026-08-27 07:37:09','2026-08-29 23:31:07'),('f7843874-56a1-41ba-a662-80d2fd6b537f','Admin SIMMAS','admin@simmas.sch.id',NULL,'$2y$12$U54IuOvm5u4vgeiheZmYMeOD9DqrwIQH5PJp8IzvgXs6L/yvBFeKy','admin',NULL,'2026-08-27 07:37:08','2026-08-27 07:37:08');
/*!40000 ALTER TABLE `profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
INSERT INTO `sessions` VALUES ('tKyyLS0kSAcz1h904mP5nkl76GvrrCL8vECEUjYm',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJiVTlWaW1qWnFrM3pLYWpTOW10N1J3R0NuMTVnYmJGOHZvVkt4aXZ2IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOiJsYW5kaW5nIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1788174763),('XitZB01lY47ig1mYsGUweeHjhS2LhPjMKqfBGKXG',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','eyJfdG9rZW4iOiI1N1cycHJOWmNDNDZ5NnplVzVJZEthcDJDemlYTGRkT3FJOXpaeXdzIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1788174761),('YBolbQdinKC3C00KQ3XEvrLGxLzIPjHV2UZ1T0y7','f7843874-56a1-41ba-a662-80d2fd6b537f','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJvdmRJbTNHc3F1YVkzcjNqU0p3RlZOYzBTUHRxRHZ4enF4SFE3cXMxIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2FkbWluXC9kYXNoYm9hcmQiLCJyb3V0ZSI6ImFkbWluLmRhc2hib2FyZCJ9LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6ImY3ODQzODc0LTU2YTEtNDFiYS1hNjYyLTgwZDJmZDZiNTM3ZiJ9',1788162882);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `siswa`
--

DROP TABLE IF EXISTS `siswa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `siswa` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nis` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kelas` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('belum_magang','pengajuan','sedang_magang','lulus') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum_magang',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `siswa_nis_unique` (`nis`),
  KEY `siswa_user_id_foreign` (`user_id`),
  CONSTRAINT `siswa_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `profiles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `siswa`
--

LOCK TABLES `siswa` WRITE;
/*!40000 ALTER TABLE `siswa` DISABLE KEYS */;
INSERT INTO `siswa` VALUES ('01a046e9-bd1e-718c-9569-2355fe5022a4','01a046e9-bd0c-71bf-9f0b-58648f1cfb79','2244','XI RPL 3','sedang_magang',0,'2026-08-27 22:48:38','2026-08-30 01:26:22'),('01a051c5-6523-739a-b47d-761f6a18e3fe','01a051c5-6510-72f0-b259-b70996e40a3d','9988776655','XII RPL A','pengajuan',1,'2026-08-30 01:24:46','2026-08-31 00:31:31'),('01a051d5-488b-70a6-a8eb-212ff6548536','01a051d5-4879-72ae-a1cf-ef344cc4353c','2223334445','XI RPL 3','belum_magang',1,'2026-08-30 01:42:07','2026-08-30 01:42:07'),('1bfe42a9-e364-4388-97a7-68733a6935b8','787b9fd3-5458-4b42-8b37-6905a96e844d','2223456789','XI RPL 2','sedang_magang',1,'2026-08-27 07:37:09','2026-08-30 01:01:29');
/*!40000 ALTER TABLE `siswa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tempat_magang`
--

DROP TABLE IF EXISTS `tempat_magang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tempat_magang` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_perusahaan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bidang_usaha` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_pic` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kontak_pic` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `kuota` int unsigned NOT NULL,
  `status_verifikasi` enum('terverifikasi','belum_diverifikasi') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum_diverifikasi',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tempat_magang`
--

LOCK TABLES `tempat_magang` WRITE;
/*!40000 ALTER TABLE `tempat_magang` DISABLE KEYS */;
INSERT INTO `tempat_magang` VALUES ('01a046e8-e0a3-7185-ae06-a7715494d359','Cv.Hexa Integra Mandiri','Teknologi Informasi (IT)','M.Burhan Islami','000999888777','Kompleks Balai RW Jl. Bukit Kismadani No.13, Dusun Tegal Gn., Bluru Kidul, Kec. Sidoarjo, Kabupaten Sidoarjo, Jawa Timur 61233',10,'terverifikasi','2026-08-27 22:47:42','2026-08-28 23:45:58'),('01a052eb-3d14-7365-b8c9-2eb052eaf102','Cv. Gyituri Developer','Software & App Development','Keyla Wulandari','087546387221','Singosari,Jawa Timur',4,'terverifikasi','2026-08-30 06:45:43','2026-08-30 06:54:59'),('01a052ef-5aa6-70d0-81ec-43aac2014384','Lion Marketing','Digital Marketing Agency','Lion Mikasa','085644738213','Pasuruan,Jawa Timur',7,'terverifikasi','2026-08-30 06:50:13','2026-08-30 06:52:23'),('01a052f3-0319-710a-ab0c-821ffeb185c8','IT Companny','Cybersecurity','Jelisa Sukandar','089655438290','Malang,Jawa Timur',3,'terverifikasi','2026-08-30 06:54:12','2026-08-30 06:54:12');
/*!40000 ALTER TABLE `tempat_magang` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
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

-- Dump completed on 2026-08-31 18:33:04
