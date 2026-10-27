/*
SQLyog Ultimate v11.11 (64 bit)
MySQL - 8.0.30 : Database - dbfadly
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`dbfadly` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `dbfadly`;

/*Table structure for table `booking` */

DROP TABLE IF EXISTS `booking`;

CREATE TABLE `booking` (
  `kdbooking` char(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `idpelanggan` char(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `jenispembayaran` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jumlahbayar` double NOT NULL DEFAULT '0',
  `total` char(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `idkaryawan` char(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_booking` date NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `expired_at` datetime DEFAULT NULL,
  PRIMARY KEY (`kdbooking`),
  KEY `booking_idpelanggan_foreign` (`idpelanggan`),
  KEY `booking_idkaryawan_foreign` (`idkaryawan`),
  CONSTRAINT `booking_idkaryawan_foreign` FOREIGN KEY (`idkaryawan`) REFERENCES `karyawan` (`idkaryawan`) ON DELETE CASCADE ON UPDATE SET NULL,
  CONSTRAINT `booking_idpelanggan_foreign` FOREIGN KEY (`idpelanggan`) REFERENCES `pelanggan` (`idpelanggan`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `booking` */

insert  into `booking`(`kdbooking`,`idpelanggan`,`status`,`jenispembayaran`,`jumlahbayar`,`total`,`idkaryawan`,`tanggal_booking`,`created_at`,`updated_at`,`expired_at`) values ('BK-20250830-0001','PLG0001','completed','Lunas',50000,'50000','BRM0001','2025-08-31','2025-08-30 21:49:01','2025-09-05 15:43:45','2025-08-30 21:54:01'),('BK-20250906-0001','PLG0002','completed','Lunas',25000,'20000.00','BRM0001','2025-09-06','2025-09-06 11:58:07','2025-09-06 12:06:09','2025-09-06 12:03:07'),('BK-20250906-0002','PLG0003','completed','Lunas',25000,'20000.00','BRM0001','2025-09-06','2025-09-06 14:11:26','2025-09-06 14:15:17','2025-09-06 14:16:26'),('BK-20250910-0001','PLG0001','completed','Lunas',50000,'50000','BRM0001','2025-09-10','2025-09-10 17:19:54','2025-09-10 17:21:32','2025-09-10 17:24:53'),('BK-20250911-0001','PLG0001','completed','Lunas',20000,'20000','BRM0001','2025-09-11','2025-09-11 08:24:13','2025-09-11 08:28:08','2025-09-11 08:29:13'),('BK-20250911-0002','PLG0001','completed','Lunas',30000,'30000','BRM0002','2025-09-11','2025-09-11 08:29:04','2025-09-11 08:30:09','2025-09-11 08:34:04');

/*Table structure for table `detail_booking` */

DROP TABLE IF EXISTS `detail_booking`;

CREATE TABLE `detail_booking` (
  `iddetail` char(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tgl` date NOT NULL,
  `kdbooking` char(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kdpaket` char(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama_paket` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `harga` double NOT NULL DEFAULT '0',
  `jamstart` time NOT NULL,
  `jamend` time NOT NULL,
  `status` enum('1','2','3','4') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '1',
  `idkaryawan` char(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`iddetail`),
  KEY `detail_booking_kdbooking_foreign` (`kdbooking`),
  KEY `detail_booking_kdpaket_foreign` (`kdpaket`),
  KEY `detail_booking_idkaryawan_foreign` (`idkaryawan`),
  CONSTRAINT `detail_booking_idkaryawan_foreign` FOREIGN KEY (`idkaryawan`) REFERENCES `karyawan` (`idkaryawan`) ON DELETE CASCADE ON UPDATE SET NULL,
  CONSTRAINT `detail_booking_kdbooking_foreign` FOREIGN KEY (`kdbooking`) REFERENCES `booking` (`kdbooking`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `detail_booking_kdpaket_foreign` FOREIGN KEY (`kdpaket`) REFERENCES `paket` (`idpaket`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `detail_booking` */

insert  into `detail_booking`(`iddetail`,`tgl`,`kdbooking`,`kdpaket`,`nama_paket`,`deskripsi`,`harga`,`jamstart`,`jamend`,`status`,`idkaryawan`,`created_at`,`updated_at`) values ('DTL-20250830-0001','2025-08-31','BK-20250830-0001','PKT001','Basic','cukur rambut biasa',20000,'12:00:00','12:30:00','2','BRM0001','2025-08-30 21:49:01','2025-08-30 21:49:21'),('DTL-20250830-0002','2025-08-31','BK-20250830-0001','PKT002','BLECING','WARNAI RAMBUT',30000,'12:30:00','12:55:00','2','BRM0001','2025-08-30 21:49:01','2025-08-30 21:49:21'),('DTL-20250906-0001','2025-09-06','BK-20250906-0001','PKT001','Basic','cukur rambut biasa',20000,'12:00:00','12:30:00','2','BRM0001','2025-09-06 11:58:07','2025-09-06 11:58:33'),('DTL-20250906-0002','2025-09-06','BK-20250906-0001','PKT002','BLECING','WARNAI RAMBUT',30000,'12:30:00','12:55:00','2','BRM0001','2025-09-06 11:58:07','2025-09-06 11:58:33'),('DTL-20250910-0001','2025-09-10','BK-20250910-0001','PKT001','Basic','cukur rambut biasa',20000,'18:00:00','18:30:00','2','BRM0001','2025-09-10 17:19:54','2025-09-10 17:20:12'),('DTL-20250910-0002','2025-09-10','BK-20250910-0001','PKT002','BLECING','WARNAI RAMBUT',30000,'18:30:00','18:55:00','2','BRM0001','2025-09-10 17:19:54','2025-09-10 17:20:12'),('DTL-20250911-0001','2025-09-11','BK-20250911-0001','PKT001','Basic','cukur rambut biasa',20000,'09:00:00','09:30:00','2','BRM0001','2025-09-11 08:24:13','2025-09-11 08:24:27'),('DTL-20250911-0002','2025-09-11','BK-20250911-0002','PKT002','BLECING','WARNAI RAMBUT',30000,'09:00:00','09:25:00','2','BRM0002','2025-09-11 08:29:04','2025-09-11 08:29:16');

/*Table structure for table `galeri` */

DROP TABLE IF EXISTS `galeri`;

CREATE TABLE `galeri` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) DEFAULT NULL,
  `gambar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `galeri` */

insert  into `galeri`(`id`,`nama`,`gambar`) values (3,'TESTIMONI','1756563026_4f7c3dc0744b1af43f0b.jpeg'),(4,'TESTIMONI','1756563065_bdf28ca4c598a7531b74.jpeg'),(5,'TESTIMONI','1756563086_c011105aa38adaa241ac.jpeg');

/*Table structure for table `karyawan` */

DROP TABLE IF EXISTS `karyawan`;

CREATE TABLE `karyawan` (
  `idkaryawan` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `namakaryawan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jenkel` enum('L','P') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `nohp` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_id` int unsigned DEFAULT NULL,
  `status` enum('aktif','nonaktif') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'aktif',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`idkaryawan`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `karyawan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `karyawan` */

insert  into `karyawan`(`idkaryawan`,`namakaryawan`,`jenkel`,`alamat`,`nohp`,`user_id`,`status`,`created_at`,`updated_at`) values ('BRM0001','FADLI','L','PADANG','0895602512886',52,'aktif','2025-08-30 21:47:27','2025-08-30 21:47:27'),('BRM0002','RAUL','L','padang','082321267977',53,'aktif','2025-08-30 21:48:24','2025-08-30 21:48:24'),('BRM0003','udin','L','padang','08232424',55,'aktif','2025-09-06 12:03:06','2025-09-06 12:03:06'),('BRM0004','fadliz','L','padang','082324242',56,'aktif','2025-09-06 14:07:06','2025-09-06 14:07:06');

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `class` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `namespace` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `migrations` */

/*Table structure for table `notifications` */

DROP TABLE IF EXISTS `notifications`;

CREATE TABLE `notifications` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'jenis notifikasi: booking_baru, pembayaran, dll',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `reference_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'kdbooking, idfaktur, atau ID lain yang terkait',
  `idpelanggan` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'ID pelanggan yang terkait dengan notifikasi',
  `is_read` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=unread, 1=read',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idpelanggan` (`idpelanggan`),
  KEY `is_read` (`is_read`),
  KEY `notifications_ibfk_2` (`reference_id`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`idpelanggan`) REFERENCES `pelanggan` (`idpelanggan`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`reference_id`) REFERENCES `booking` (`kdbooking`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=112 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `notifications` */

insert  into `notifications`(`id`,`type`,`title`,`message`,`reference_id`,`idpelanggan`,`is_read`,`created_at`,`updated_at`) values (94,'booking_baru','Booking Baru','Booking baru oleh pramtoxz dengan kode BK-20250827-0001',NULL,NULL,0,'2025-08-27 03:05:35','2025-08-27 03:05:35'),(95,'booking_baru','Booking Baru','Booking baru oleh Rafi chandra dengan kode BK-20250827-0001',NULL,NULL,0,'2025-08-27 21:07:35','2025-08-27 21:07:35'),(96,'pembayaran_baru','Pembayaran Baru','Pembayaran baru untuk booking BK-20250827-0001 telah diterima dan menunggu konfirmasi.',NULL,NULL,0,'2025-08-27 21:08:21','2025-08-27 21:08:21'),(97,'booking_baru','Booking Baru','Booking baru oleh UDIN dengan kode BK-20250830-0001',NULL,'PLG0001',0,'2025-08-30 21:14:39','2025-08-30 21:14:39'),(98,'pembayaran_baru','Pembayaran Baru','Pembayaran baru untuk booking BK-20250830-0001 telah diterima dan menunggu konfirmasi.',NULL,NULL,0,'2025-08-30 21:15:18','2025-08-30 21:15:18'),(99,'booking_baru','Booking Baru','Booking baru oleh UDIN dengan kode BK-20250830-0002',NULL,'PLG0001',0,'2025-08-30 21:18:11','2025-08-30 21:18:11'),(100,'booking_baru','Booking Baru','Booking baru oleh UDIN dengan kode BK-20250830-0001','BK-20250830-0001','PLG0001',0,'2025-08-30 21:49:01','2025-08-30 21:49:01'),(101,'pembayaran_baru','Pembayaran Baru','Pembayaran baru untuk booking BK-20250830-0001 telah diterima dan menunggu konfirmasi.','BK-20250830-0001',NULL,0,'2025-08-30 21:49:21','2025-08-30 21:49:21'),(102,'booking_baru','Booking Baru','Booking baru oleh fadliansyari dengan kode BK-20250906-0001','BK-20250906-0001','PLG0002',0,'2025-09-06 11:58:07','2025-09-06 11:58:07'),(103,'pembayaran_baru','Pembayaran Baru','Pembayaran baru untuk booking BK-20250906-0001 telah diterima dan menunggu konfirmasi.','BK-20250906-0001',NULL,0,'2025-09-06 11:58:33','2025-09-06 11:58:33'),(104,'booking_baru','Booking Baru','Booking baru oleh zen dengan kode BK-20250906-0002','BK-20250906-0002','PLG0003',0,'2025-09-06 14:11:26','2025-09-06 14:11:26'),(105,'pembayaran_baru','Pembayaran Baru','Pembayaran baru untuk booking BK-20250906-0002 telah diterima dan menunggu konfirmasi.','BK-20250906-0002',NULL,0,'2025-09-06 14:13:19','2025-09-06 14:13:19'),(106,'booking_baru','Booking Baru','Booking baru oleh UDIN dengan kode BK-20250910-0001','BK-20250910-0001','PLG0001',0,'2025-09-10 17:19:54','2025-09-10 17:19:54'),(107,'pembayaran_baru','Pembayaran Baru','Pembayaran baru untuk booking BK-20250910-0001 telah diterima dan menunggu konfirmasi.','BK-20250910-0001',NULL,0,'2025-09-10 17:20:12','2025-09-10 17:20:12'),(108,'booking_baru','Booking Baru','Booking baru oleh UDIN dengan kode BK-20250911-0001','BK-20250911-0001','PLG0001',0,'2025-09-11 08:24:13','2025-09-11 08:24:13'),(109,'pembayaran_baru','Pembayaran Baru','Pembayaran baru untuk booking BK-20250911-0001 telah diterima dan menunggu konfirmasi.','BK-20250911-0001',NULL,0,'2025-09-11 08:24:27','2025-09-11 08:24:27'),(110,'booking_baru','Booking Baru','Booking baru oleh UDIN dengan kode BK-20250911-0002','BK-20250911-0002','PLG0001',0,'2025-09-11 08:29:04','2025-09-11 08:29:04'),(111,'pembayaran_baru','Pembayaran Baru','Pembayaran baru untuk booking BK-20250911-0002 telah diterima dan menunggu konfirmasi.','BK-20250911-0002',NULL,0,'2025-09-11 08:29:16','2025-09-11 08:29:16');

/*Table structure for table `otp` */

DROP TABLE IF EXISTS `otp`;

CREATE TABLE `otp` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned DEFAULT NULL,
  `otp_code` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `type` enum('registration','reset_password') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'registration',
  `expired_at` datetime NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `otp_user_id_foreign` (`user_id`),
  CONSTRAINT `otp_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `otp` */

/*Table structure for table `paket` */

DROP TABLE IF EXISTS `paket`;

CREATE TABLE `paket` (
  `idpaket` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `namapaket` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `harga` decimal(10,2) NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `durasi` int DEFAULT NULL,
  PRIMARY KEY (`idpaket`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `paket` */

insert  into `paket`(`idpaket`,`namapaket`,`deskripsi`,`harga`,`image`,`created_at`,`updated_at`,`durasi`) values ('PKT001','Basic','cukur rambut biasa',20000.00,'1756562667_771aacd6b40ea36f383b.jpeg','2025-08-30 21:04:27','2025-08-30 21:06:52',30),('PKT002','BLECING','WARNAI RAMBUT',30000.00,'1756562727_eb2abe8d91edce4c634e.jpeg','2025-08-30 21:05:27','2025-08-30 21:06:31',25),('PKT003','PIJAT','PIJAT KEPALA',40000.00,'1756562775_2bdf935e9b36da2d72ae.jpeg','2025-08-30 21:06:15','2025-08-30 21:06:15',20);

/*Table structure for table `pelanggan` */

DROP TABLE IF EXISTS `pelanggan`;

CREATE TABLE `pelanggan` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned DEFAULT NULL,
  `idpelanggan` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama_lengkap` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jeniskelamin` enum('Laki-laki','Perempuan','-') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `no_hp` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idpelanggan` (`idpelanggan`),
  KEY `pelanggan_user_id_foreign` (`user_id`),
  CONSTRAINT `pelanggan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=149 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `pelanggan` */

insert  into `pelanggan`(`id`,`user_id`,`idpelanggan`,`nama_lengkap`,`jeniskelamin`,`alamat`,`no_hp`,`tanggal_lahir`,`created_at`,`updated_at`) values (146,51,'PLG0001','UDIN','Laki-laki','-','0895602512886','2025-08-30','2025-08-30 21:13:12','2025-08-30 21:14:05'),(147,54,'PLG0002','fadliansyari','Laki-laki','-','0823232423232','2002-05-11','2025-09-06 11:56:01','2025-09-06 11:56:57'),(148,57,'PLG0003','zen','Laki-laki','-','08323242424','2002-07-10','2025-09-06 14:09:36','2025-09-06 14:10:53');

/*Table structure for table `pembayaran` */

DROP TABLE IF EXISTS `pembayaran`;

CREATE TABLE `pembayaran` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `fakturbooking` char(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `total_bayar` double NOT NULL DEFAULT '0',
  `grandtotal` double NOT NULL DEFAULT '0',
  `metode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'cash',
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'paid',
  `jenis` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Lunas',
  `bukti` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pembayaran_fakturbooking_foreign` (`fakturbooking`),
  CONSTRAINT `pembayaran_fakturbooking_foreign` FOREIGN KEY (`fakturbooking`) REFERENCES `booking` (`kdbooking`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `pembayaran` */

insert  into `pembayaran`(`id`,`fakturbooking`,`total_bayar`,`grandtotal`,`metode`,`status`,`jenis`,`bukti`,`created_at`,`updated_at`) values (74,'BK-20250830-0001',50000,50000,'transfer','paid','Lunas','BK-20250830-0001_1756565361_ea3cc2e73ec6e6542a37.jpeg','2025-08-30 21:49:21','2025-09-05 15:43:45'),(75,'BK-20250906-0001',25000,50000,'transfer','paid','DP','BK-20250906-0001_1757134713_82babcf0b9d1e71be8fb.png','2025-09-06 11:58:33','2025-09-06 12:06:09'),(76,'BK-20250906-0002',25000,50000,'transfer','paid','DP','BK-20250906-0002_1757142799_35c04bef0118d1000699.png','2025-09-06 14:13:19','2025-09-06 14:15:17'),(77,'BK-20250910-0001',25000,50000,'transfer','paid','DP','BK-20250910-0001_1757499612_9791bcdd7c4ee5263f4d.jpg','2025-09-10 17:20:12','2025-09-10 17:21:32'),(78,'BK-20250910-0001',25000,50000,'cash','paid','Pelunasan',NULL,'2025-09-10 17:21:32','2025-09-10 17:21:32'),(79,'BK-20250911-0001',10000,20000,'transfer','paid','DP','BK-20250911-0001_1757553867_bc02b23fcaaf29f038cc.png','2025-09-11 08:24:27','2025-09-11 08:28:08'),(80,'BK-20250911-0001',10000,20000,'cash','paid','Pelunasan',NULL,'2025-09-11 08:24:50','2025-09-11 08:28:08'),(81,'BK-20250911-0002',15000,30000,'transfer','paid','DP','BK-20250911-0002_1757554156_18c1317e653402db4e11.png','2025-09-11 08:29:16','2025-09-11 08:30:09'),(82,'BK-20250911-0002',15000,30000,'cash','paid','Pelunasan',NULL,'2025-09-11 08:30:09','2025-09-11 08:30:09');

/*Table structure for table `pengeluaran` */

DROP TABLE IF EXISTS `pengeluaran`;

CREATE TABLE `pengeluaran` (
  `idpengeluaran` char(30) NOT NULL,
  `tgl` date DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `jumlah` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idpengeluaran`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `pengeluaran` */

insert  into `pengeluaran`(`idpengeluaran`,`tgl`,`keterangan`,`jumlah`,`created_at`,`updated_at`) values ('PG-20250830-0001','2025-08-30','tes',200000,'2025-08-30 21:30:22','2025-08-30 21:30:22'),('PG-20250830-0002','2025-07-08','tesssss',10000000,'2025-08-30 21:31:14','2025-08-30 21:31:14'),('PG-20250906-0003','2025-09-06','listrik',20000,'2025-09-06 12:06:46','2025-09-06 12:06:46');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'admin, user, dll',
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'active' COMMENT 'active, inactive',
  `last_login` datetime DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`username`,`email`,`password`,`name`,`role`,`status`,`last_login`,`remember_token`,`created_at`,`updated_at`,`deleted_at`) values (1,'admin','admin@gmail.com','$2y$10$q0diYPnm5cBnu664cMMd9ulmEgjW6hUZA2gILBEWA56OGygYVpW86','Administrator','admin','active','2025-09-11 07:57:48',NULL,'2025-06-19 09:29:55','2025-09-11 07:57:48',NULL),(2,'Pimpinan','pimpinan@gmail.com','$2y$10$q0diYPnm5cBnu664cMMd9ulmEgjW6hUZA2gILBEWA56OGygYVpW86','Pimpinan','pimpinan','active','2025-09-10 18:02:09',NULL,'2025-06-27 22:25:42','2025-09-10 18:02:09',NULL),(42,'barberman1','barberman1@gmail.com','$2y$10$q0diYPnm5cBnu664cMMd9ulmEgjW6hUZA2gILBEWA56OGygYVpW86','barberman1','karyawan','active','2025-08-25 02:40:31',NULL,'2025-08-05 16:50:03','2025-08-25 02:40:31',NULL),(51,'UDINZ','fadliansyari54@gmail.com','$2y$10$t.9n1Wqw2KKMNOfq0SXNT.vFDpFEbIzvlIk4/olygOJa.F08h276i','UDIN','pelanggan','active','2025-09-11 08:23:54',NULL,'2025-08-30 21:12:33','2025-09-11 08:23:54',NULL),(52,'leypad174@gmail.com','leypad174@gmail.com','$2y$10$etMig4c67.rmTMuAv0mvHuPBQy5IetlU4Sn/qdBypjArmWAf9JF0e','FADLI','karyawan','active','2025-09-06 14:08:10',NULL,'2025-08-30 21:47:27','2025-09-06 14:08:10',NULL),(53,'raul123@gmail.com','raul123@gmail.com','$2y$10$.UvjHWZLFVhsf8oGcK2BHebNQmirF38rf0p0lbgycwq.5DiOp1GE2','RAUL','karyawan','active','2025-08-30 21:50:17',NULL,'2025-08-30 21:48:24','2025-08-30 21:50:17',NULL),(54,'fadli12','fadliansyari57@gmail.com','$2y$10$5XHA4Tc.O2AQUKTvdag3I.iIEK7K1wIehRO5YQodBV5wT34dJaUVW','fadliansyari','pelanggan','active','2025-09-06 14:05:00',NULL,'2025-09-06 11:55:29','2025-09-06 14:05:00',NULL),(55,'manaingat@gmail.com','manaingat@gmail.com','$2y$10$kk2frY8CUHEXe73IqMuG3OkS6rravb9X7zYgSy0m5VckcFA5QTzxO','udin','karyawan','active','2025-09-06 12:03:36',NULL,'2025-09-06 12:03:06','2025-09-06 12:03:36',NULL),(56,'zein@gmail.com','zein@gmail.com','$2y$10$RVYePorAv/HgLdeSF6NyOOsxOz7twyLxxSJEHSkMZtyRf4sr4.vNq','fadliz','karyawan','active','2025-09-06 14:07:23',NULL,'2025-09-06 14:07:06','2025-09-06 14:07:23',NULL),(57,'zen123','zenyen30@gmail.com','$2y$10$eovaAv3WSTEw0i38k5jDZe/b5zwFV9QG.J4MB5X5kG8/k9k6njb4G','zen','pelanggan','active','2025-09-06 14:10:07',NULL,'2025-09-06 14:09:09','2025-09-06 14:10:53',NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
