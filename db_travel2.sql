/*
SQLyog Ultimate v11.11 (64 bit)
MySQL - 8.0.30 : Database - db_travel2
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`db_travel2` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `db_travel2`;

/*Table structure for table `detailpemesanan` */

DROP TABLE IF EXISTS `detailpemesanan`;

CREATE TABLE `detailpemesanan` (
  `detailpemesananid` int NOT NULL AUTO_INCREMENT,
  `pemesananid` int DEFAULT NULL,
  `kursikendaraanid` int DEFAULT NULL,
  `namapenumpang` varchar(100) DEFAULT NULL,
  `jeniskelamin` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`detailpemesananid`),
  KEY `pemesananid` (`pemesananid`),
  KEY `kursikendaraanid` (`kursikendaraanid`),
  CONSTRAINT `detailpemesanan_ibfk_1` FOREIGN KEY (`pemesananid`) REFERENCES `pemesanan` (`idpemesanan`),
  CONSTRAINT `detailpemesanan_ibfk_2` FOREIGN KEY (`kursikendaraanid`) REFERENCES `kursikendaraan` (`idkursi`)
) ENGINE=InnoDB AUTO_INCREMENT=145 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `detailpemesanan` */

insert  into `detailpemesanan`(`detailpemesananid`,`pemesananid`,`kursikendaraanid`,`namapenumpang`,`jeniskelamin`) values (139,88,8,'Toro','Laki-laki'),(140,89,10,'Rayhan','Laki-laki'),(141,90,10,'Aidil','Laki-laki'),(142,91,11,'Ardian','Laki-laki'),(144,93,10,'El Islami Fitri','Perempuan');

/*Table structure for table `jadwalberangkat` */

DROP TABLE IF EXISTS `jadwalberangkat`;

CREATE TABLE `jadwalberangkat` (
  `idjadwal` int NOT NULL AUTO_INCREMENT,
  `iduser` int DEFAULT NULL,
  `idkendaraan` int DEFAULT NULL,
  `idrute` int DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `jam` time DEFAULT NULL,
  PRIMARY KEY (`idjadwal`),
  KEY `iduser` (`iduser`),
  KEY `idkendaraan` (`idkendaraan`),
  KEY `idrute` (`idrute`),
  CONSTRAINT `jadwalberangkat_ibfk_1` FOREIGN KEY (`iduser`) REFERENCES `user` (`id_user`),
  CONSTRAINT `jadwalberangkat_ibfk_2` FOREIGN KEY (`idkendaraan`) REFERENCES `kendaraan` (`idkendaraan`),
  CONSTRAINT `jadwalberangkat_ibfk_3` FOREIGN KEY (`idrute`) REFERENCES `rute` (`idrute`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `jadwalberangkat` */

insert  into `jadwalberangkat`(`idjadwal`,`iduser`,`idkendaraan`,`idrute`,`tanggal`,`jam`) values (61,3,2,4,'2025-08-09','10:00:00'),(62,7,3,2,'2025-08-09','14:00:00'),(63,3,3,9,'2025-08-09','19:00:00'),(64,7,4,12,'2025-08-25','14:00:00'),(65,3,4,10,'2025-08-25','14:00:00'),(66,8,3,11,'2025-08-25','19:00:00');

/*Table structure for table `kendaraan` */

DROP TABLE IF EXISTS `kendaraan`;

CREATE TABLE `kendaraan` (
  `idkendaraan` int NOT NULL AUTO_INCREMENT,
  `nopolisi_kendaraan` varchar(15) DEFAULT NULL,
  `namakendaraan` varchar(100) DEFAULT NULL,
  `jumlahkursi` int DEFAULT NULL,
  PRIMARY KEY (`idkendaraan`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `kendaraan` */

insert  into `kendaraan`(`idkendaraan`,`nopolisi_kendaraan`,`namakendaraan`,`jumlahkursi`) values (1,'BA 1234 AA','Avanza',7),(2,'BA 2222 BA','Xenia',7),(3,'BA 3123 KK','Inova',7),(4,'BA 4567 OO','Pajero',7);

/*Table structure for table `kursikendaraan` */

DROP TABLE IF EXISTS `kursikendaraan`;

CREATE TABLE `kursikendaraan` (
  `idkursi` int NOT NULL AUTO_INCREMENT,
  `idkendaraan` int DEFAULT NULL,
  `nomorkursi` int DEFAULT NULL,
  `jadwalberangkat` enum('pagi','siang') DEFAULT NULL,
  PRIMARY KEY (`idkursi`),
  KEY `idkendaraan` (`idkendaraan`),
  CONSTRAINT `kursikendaraan_ibfk_1` FOREIGN KEY (`idkendaraan`) REFERENCES `kendaraan` (`idkendaraan`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `kursikendaraan` */

insert  into `kursikendaraan`(`idkursi`,`idkendaraan`,`nomorkursi`,`jadwalberangkat`) values (7,1,1,'pagi'),(8,2,4,'pagi'),(9,1,2,'pagi'),(10,3,1,'pagi'),(11,4,1,'pagi'),(12,4,2,'siang');

/*Table structure for table `pemesanan` */

DROP TABLE IF EXISTS `pemesanan`;

CREATE TABLE `pemesanan` (
  `idpemesanan` int NOT NULL AUTO_INCREMENT,
  `id_user` int DEFAULT NULL,
  `idrute` int DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `jumlah_orang` int DEFAULT NULL,
  `total` int DEFAULT NULL,
  `status` enum('sudah berangkat','belum bayar','sudah bayar belum konfirmasi','pembayaran sudah di konfirmasi') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `idjadwal` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`idpemesanan`),
  KEY `id_user` (`id_user`),
  KEY `idrute` (`idrute`),
  KEY `idjadwal` (`idjadwal`),
  CONSTRAINT `pemesanan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`),
  CONSTRAINT `pemesanan_ibfk_2` FOREIGN KEY (`idrute`) REFERENCES `rute` (`idrute`),
  CONSTRAINT `pemesanan_ibfk_3` FOREIGN KEY (`idjadwal`) REFERENCES `jadwalberangkat` (`idjadwal`)
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `pemesanan` */

insert  into `pemesanan`(`idpemesanan`,`id_user`,`idrute`,`tanggal`,`jumlah_orang`,`total`,`status`,`bukti_pembayaran`,`idjadwal`,`created_at`,`updated_at`) values (88,1,4,'2025-08-09',1,80000,'sudah bayar belum konfirmasi','1754725419_d4f065c6a763b7e6a3fd.png',61,'2025-08-09 07:43:39','2025-08-09 07:43:39'),(89,4,2,'2025-08-09',1,450000,'sudah bayar belum konfirmasi','1754725471_1a92080effaf46dfeb11.png',62,'2025-08-09 07:44:31','2025-08-09 07:44:31'),(90,6,9,'2025-08-09',1,80000,'sudah bayar belum konfirmasi','1754725689_208ed37a618c9ed13764.png',63,'2025-08-09 07:48:09','2025-08-09 07:48:09'),(91,12,12,'2025-08-25',1,80000,'sudah bayar belum konfirmasi','1756103594_19d063b17228189b7b52.png',64,'2025-08-25 06:33:14','2025-08-25 06:33:14'),(93,4,11,'2025-08-25',1,80000,'belum bayar',NULL,66,'2025-08-25 06:35:45','2025-08-25 06:35:45');

/*Table structure for table `rute` */

DROP TABLE IF EXISTS `rute`;

CREATE TABLE `rute` (
  `idrute` int NOT NULL AUTO_INCREMENT,
  `asal` varchar(100) DEFAULT NULL,
  `tujuan` varchar(100) DEFAULT NULL,
  `harga` int DEFAULT NULL,
  `jadwalberangkat` enum('pagi','siang','malam') DEFAULT NULL,
  `idkendaraan` int DEFAULT NULL,
  PRIMARY KEY (`idrute`),
  KEY `idkendaraan` (`idkendaraan`),
  CONSTRAINT `rute_ibfk_1` FOREIGN KEY (`idkendaraan`) REFERENCES `kendaraan` (`idkendaraan`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `rute` */

insert  into `rute`(`idrute`,`asal`,`tujuan`,`harga`,`jadwalberangkat`,`idkendaraan`) values (2,'Padang','Pekanbaru',450000,'siang',3),(3,'Padang','Batusangkar',80000,'pagi',4),(4,'Batusangkar','Padang',80000,'pagi',2),(8,'Pekanbaru','Padang',350000,'siang',4),(9,'Padang','Batusangkar',80000,'malam',3),(10,'Padang','Batusangkar',80000,'siang',4),(11,'Batusangkar ','Padang',80000,'malam',3),(12,'Batusangkar','Padang',80000,'siang',4);

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `nama_user` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `nohp` varchar(20) DEFAULT NULL,
  `status` enum('penumpang','admin','pimpinan','supir') DEFAULT NULL,
  `nosim` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `user` */

insert  into `user`(`id_user`,`nama_user`,`email`,`password`,`nohp`,`status`,`nosim`) values (1,'Toro','toro@gmail.com','$2y$10$7abmH25pvRCxsGtXt12MDeD6Fo7xJBjcDx02Me9CLaoqNvliPZ1.2','08789123123','penumpang',''),(2,'admin','admin@gmail.com','$2y$10$oLtxJvHWyZsaR8476B4./OVpK2kP5sXnRj6.wgN0VfRa0Dgra.JPe','087801178499','admin',''),(3,'Silvia','silvia@gmail.com','$2y$10$PJ2H3GIygDecgTQzG2aXFevRwFTUZ/D3O3lUmXHhjsIRlyZVVznNK','087801178499','supir','012310293123'),(4,'rayhan','rayhan@gmail.com','$2y$10$kHOj.NneNcO3VKOs/V8L2eAsu9aVeyRYq5DosE8qBkqqnFI3Lo75.','08123123123123','penumpang',''),(5,'pimpinan','pimpinan@gmail.com','$2y$10$B368Cs.mJn3P8xzb5kwFeOHThql0O.FQlzKui9KLVZukd/9yx2Dky','12345678','pimpinan',''),(6,'aidil','aidil@gmail.com','$2y$10$KBbZXRapw.kdw/w76xJamup/4oqcPTx4trnX3s.AvFWTFOi/4gJDW','123123123123','penumpang',''),(7,'fadil','fadil@gmail.com','$2y$10$uycsN5ZKaP1SbYlE6v//eewrxyRsH5lW8DyTJEWFP8wvJ8W7lFClG','123123123','supir','123123123123'),(8,'agus','agus@gmail.com','$2y$10$7GD7g/U8EPYRvRsHX7BUXeDr643FCVM2s89HxlU4eQQPVlX5qtuai','081231231236','supir','123124123123'),(11,'theza','theza@gmail.com','$2y$10$QhL8GStflJ6OCfDQQEKFSePeTqO.Hxu/uYBaDhZMsWG8Iau7H1fki','081231982312','supir','12312312412'),(12,'ardian','ardian@gmail.com','$2y$10$alMb/AMCgd3fAu4myI2AhuJ7oWgQcKtd7ttfH1XdpNm2eX0e012r2','98766554','penumpang','');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
