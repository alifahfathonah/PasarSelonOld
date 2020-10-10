/*
SQLyog Ultimate v11.33 (64 bit)
MySQL - 5.7.11-log : Database - KiselCommerce
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `ACL` */

DROP TABLE IF EXISTS `ACL`;

CREATE TABLE `ACL` (
  `id` int(20) NOT NULL,
  `model` varchar(128) CHARACTER SET latin1 DEFAULT NULL,
  `property` varchar(128) CHARACTER SET latin1 DEFAULT NULL,
  `accessType` enum('READ','WRITE','EXECUTE') CHARACTER SET latin1 DEFAULT NULL,
  `permission` enum('ALARM','ALLOW','AUDIT','DENY') CHARACTER SET latin1 DEFAULT NULL,
  `principalType` enum('USER','APP','ROLE') CHARACTER SET latin1 NOT NULL DEFAULT 'USER',
  `principalId` varchar(32) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `ACL` */

/*Table structure for table `AccessToken` */

DROP TABLE IF EXISTS `AccessToken`;

CREATE TABLE `AccessToken` (
  `id` varchar(256) CHARACTER SET latin1 NOT NULL,
  `ttl` int(10) DEFAULT NULL,
  `userId` int(20) unsigned NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`createdAt`),
  KEY `fk_AccessToken_User1_idx` (`userId`),
  CONSTRAINT `fk_AccessToken_User1` FOREIGN KEY (`userId`) REFERENCES `User` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `AccessToken` */

/*Table structure for table `Admin` */

DROP TABLE IF EXISTS `Admin`;

CREATE TABLE `Admin` (
  `id` int(20) unsigned NOT NULL,
  `firstName` varchar(64) CHARACTER SET latin1 NOT NULL,
  `lastName` varchar(64) CHARACTER SET latin1 NOT NULL,
  `sex` enum('F','M') CHARACTER SET latin1 NOT NULL,
  `phoneNo` varchar(16) CHARACTER SET latin1 NOT NULL,
  `avatarFile` varchar(64) CHARACTER SET latin1 DEFAULT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_Admin_Users1` FOREIGN KEY (`id`) REFERENCES `User` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `Admin` */

insert  into `Admin`(`id`,`firstName`,`lastName`,`sex`,`phoneNo`,`avatarFile`,`createdAt`,`updatedAt`) values (1,'Admin','Kisel','F','08123456789',NULL,'2017-04-04 11:27:02','2017-04-04 11:27:02');

/*Table structure for table `Bank` */

DROP TABLE IF EXISTS `Bank`;

CREATE TABLE `Bank` (
  `id` varchar(36) NOT NULL,
  `name` varchar(32) NOT NULL,
  `code` varchar(4) DEFAULT NULL,
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modifiedBy` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `Bank` */

/*Table structure for table `Cart` */

DROP TABLE IF EXISTS `Cart`;

CREATE TABLE `Cart` (
  `id` varchar(36) NOT NULL,
  `customerId` int(20) unsigned NOT NULL,
  `productId` varchar(36) NOT NULL,
  `quantity` int(10) NOT NULL,
  `note` varchar(140) DEFAULT NULL,
  `options` json DEFAULT NULL,
  `courierCostId` varchar(36) NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_Cart_Customer1_idx` (`customerId`),
  KEY `fk_Cart_Product1_idx` (`productId`),
  KEY `fk_Cart_CourierCost1_idx` (`courierCostId`),
  CONSTRAINT `fk_Cart_CourierCost1` FOREIGN KEY (`courierCostId`) REFERENCES `CourierCost` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Cart_Customer1` FOREIGN KEY (`customerId`) REFERENCES `Customer` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Cart_Product1` FOREIGN KEY (`productId`) REFERENCES `Product` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `Cart` */

/*Table structure for table `City` */

DROP TABLE IF EXISTS `City`;

CREATE TABLE `City` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `provinceId` int(3) unsigned NOT NULL,
  `countryId` int(3) unsigned NOT NULL,
  `name` varchar(32) NOT NULL,
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modifiedBy` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`,`provinceId`,`countryId`),
  KEY `fk_City_Province1_idx` (`provinceId`,`countryId`),
  CONSTRAINT `fk_City_Province1` FOREIGN KEY (`provinceId`, `countryId`) REFERENCES `Province` (`id`, `countryId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `City` */

insert  into `City`(`id`,`provinceId`,`countryId`,`name`,`updatedAt`,`modifiedBy`) values (1,1,1,'Jakarta Pusat','2017-04-04 11:26:57',NULL),(1,2,1,'Tangerang Selatan','2017-04-04 11:26:57',NULL),(1,3,1,'Bogor','2017-04-04 11:26:57',NULL),(1,4,1,'Palu','2017-04-04 11:26:57',NULL),(2,1,1,'Jakarta Utara','2017-04-04 11:26:57',NULL),(2,3,1,'Depok','2017-04-04 11:26:57',NULL),(3,1,1,'Jakarta Barat','2017-04-04 11:26:57',NULL),(4,1,1,'Jakarta Selatan','2017-04-04 11:26:57',NULL),(5,1,1,'Jakarta Timur','2017-04-04 11:26:57',NULL),(6,1,1,'Kepulauan Seribu','2017-04-04 11:26:57',NULL);

/*Table structure for table `Complaints` */

DROP TABLE IF EXISTS `Complaints`;

CREATE TABLE `Complaints` (
  `id` int(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `Complaints` */

/*Table structure for table `Country` */

DROP TABLE IF EXISTS `Country`;

CREATE TABLE `Country` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modifiedBy` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `Country` */

insert  into `Country`(`id`,`name`,`updatedAt`,`modifiedBy`) values (1,'Indonesia','2017-04-04 11:26:56',NULL);

/*Table structure for table `Courier` */

DROP TABLE IF EXISTS `Courier`;

CREATE TABLE `Courier` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) CHARACTER SET latin1 NOT NULL,
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modifiedBy` int(20) DEFAULT NULL COMMENT 'Admin who modifies data',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `Courier` */

insert  into `Courier`(`id`,`name`,`updatedAt`,`modifiedBy`) values (1,'JNE','2017-04-04 11:27:00',NULL),(2,'Pos Indonesia','2017-04-04 11:27:00',NULL),(3,'SiCepat','2017-04-04 11:27:00',NULL),(4,'GO-SEND','2017-04-04 11:27:00',NULL),(5,'J&T','2017-04-04 11:27:00',NULL),(6,'Ninja Express','2017-04-04 11:27:00',NULL),(7,'TIKI','2017-04-04 11:27:00',NULL);

/*Table structure for table `CourierCost` */

DROP TABLE IF EXISTS `CourierCost`;

CREATE TABLE `CourierCost` (
  `id` varchar(36) CHARACTER SET latin1 NOT NULL,
  `from` varchar(64) CHARACTER SET latin1 NOT NULL,
  `to` varchar(64) CHARACTER SET latin1 NOT NULL,
  `courierId` int(2) NOT NULL,
  `courierName` varchar(32) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `courierPackageId` int(3) NOT NULL,
  `courierPackageName` varchar(32) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `cost` double NOT NULL,
  `currencyId` int(3) unsigned NOT NULL,
  `currencyCode` varchar(4) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `weight` int(8) NOT NULL DEFAULT '1000' COMMENT 'Weight in grams',
  `minWeight` int(8) NOT NULL DEFAULT '1000' COMMENT 'Value in gram',
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deletedAt` datetime DEFAULT NULL,
  `isDeleted` tinyint(1) NOT NULL DEFAULT '0',
  `modifiedBy` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_CourierCost_CourierPackage1_idx` (`courierPackageId`,`courierId`),
  KEY `fk_CourierCost_Currency1_idx` (`currencyId`),
  CONSTRAINT `fk_CourierCost_CourierPackage1` FOREIGN KEY (`courierPackageId`, `courierId`) REFERENCES `CourierPackage` (`id`, `courierId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_CourierCost_Currency1` FOREIGN KEY (`currencyId`) REFERENCES `Currency` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `CourierCost` */

/*Table structure for table `CourierPackage` */

DROP TABLE IF EXISTS `CourierPackage`;

CREATE TABLE `CourierPackage` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `courierId` int(2) NOT NULL,
  `courierName` varchar(32) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `name` varchar(32) CHARACTER SET latin1 NOT NULL,
  `description` varchar(256) CHARACTER SET latin1 DEFAULT NULL,
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modifiedBy` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`,`courierId`),
  KEY `fk_CourierPackage_Courier1_idx` (`courierId`),
  CONSTRAINT `fk_CourierPackage_Courier1` FOREIGN KEY (`courierId`) REFERENCES `Courier` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `CourierPackage` */

insert  into `CourierPackage`(`id`,`courierId`,`courierName`,`name`,`description`,`updatedAt`,`modifiedBy`) values (1,1,'JNE','Reguler',NULL,'2017-04-04 11:27:00',NULL),(2,1,'JNE','YES',NULL,'2017-04-04 11:27:00',NULL),(3,2,'Pos Indonesia','Kilat',NULL,'2017-04-04 11:27:00',NULL),(4,3,'SiCepat','Reguler',NULL,'2017-04-04 11:27:00',NULL),(5,4,'GO-SEND','Reguler',NULL,'2017-04-04 11:27:00',NULL),(6,5,'J&T','Reguler',NULL,'2017-04-04 11:27:00',NULL);

/*Table structure for table `Currency` */

DROP TABLE IF EXISTS `Currency`;

CREATE TABLE `Currency` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(4) NOT NULL,
  `name` varchar(16) NOT NULL,
  `countryId` int(3) unsigned NOT NULL,
  `countryName` varchar(32) NOT NULL,
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modifiedBy` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Currency_Country1_idx` (`countryId`),
  CONSTRAINT `fk_Currency_Country1` FOREIGN KEY (`countryId`) REFERENCES `Country` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `Currency` */

insert  into `Currency`(`id`,`code`,`name`,`countryId`,`countryName`,`updatedAt`,`modifiedBy`) values (1,'IDR','Rupiah',1,'Indonesia','2017-04-04 11:26:58',NULL);

/*Table structure for table `Customer` */

DROP TABLE IF EXISTS `Customer`;

CREATE TABLE `Customer` (
  `id` int(20) unsigned NOT NULL,
  `firstName` varchar(64) NOT NULL,
  `lastName` varchar(64) NOT NULL,
  `alias` varchar(64) DEFAULT NULL,
  `phoneNo` varchar(16) NOT NULL,
  `sex` enum('F','M') NOT NULL,
  `avatarFile` varchar(64) DEFAULT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_Customer_Users1_idx` (`id`),
  CONSTRAINT `fk_Customer_Users1` FOREIGN KEY (`id`) REFERENCES `User` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Synchronized Table with mata2idprod.customer';

/*Data for the table `Customer` */

/*Table structure for table `CustomerAddress` */

DROP TABLE IF EXISTS `CustomerAddress`;

CREATE TABLE `CustomerAddress` (
  `id` varchar(36) NOT NULL,
  `userId` int(20) unsigned NOT NULL,
  `name` varchar(32) NOT NULL,
  `recipientName` varchar(64) NOT NULL,
  `recipientPhone` varchar(16) NOT NULL,
  `address` varchar(64) NOT NULL,
  `districtId` int(3) unsigned NOT NULL,
  `districtName` varchar(32) NOT NULL,
  `cityId` int(3) unsigned NOT NULL,
  `cityName` varchar(32) NOT NULL,
  `provinceId` int(3) unsigned NOT NULL,
  `provinceName` varchar(32) NOT NULL,
  `countryId` int(3) unsigned NOT NULL,
  `countryName` varchar(32) NOT NULL,
  `zipCode` varchar(8) DEFAULT NULL,
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_UserAddress_User_idx` (`userId`),
  KEY `fk_UserAddress_District1_idx` (`districtId`,`cityId`,`provinceId`,`countryId`),
  CONSTRAINT `fk_UserAddress_District1` FOREIGN KEY (`districtId`, `cityId`, `provinceId`, `countryId`) REFERENCES `District` (`id`, `cityId`, `provinceId`, `countryId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_UserAddress_User` FOREIGN KEY (`userId`) REFERENCES `Customer` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `CustomerAddress` */

/*Table structure for table `CustomerBankAccount` */

DROP TABLE IF EXISTS `CustomerBankAccount`;

CREATE TABLE `CustomerBankAccount` (
  `id` varchar(36) NOT NULL,
  `userId` int(20) unsigned NOT NULL,
  `bankId` varchar(36) NOT NULL,
  `bankName` varchar(32) NOT NULL,
  `accountNo` varchar(32) NOT NULL,
  `accountName` varchar(128) NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_UserBankAccount_User1_idx` (`userId`),
  KEY `fk_UserBankAccount_Bank1_idx` (`bankId`),
  CONSTRAINT `fk_UserBankAccount_User1` FOREIGN KEY (`userId`) REFERENCES `Customer` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `CustomerBankAccount` */

/*Table structure for table `District` */

DROP TABLE IF EXISTS `District`;

CREATE TABLE `District` (
  `id` int(3) unsigned NOT NULL,
  `cityId` int(3) unsigned NOT NULL,
  `provinceId` int(3) unsigned NOT NULL,
  `countryId` int(3) unsigned NOT NULL,
  `name` varchar(32) NOT NULL,
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modifiedBy` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`,`cityId`,`provinceId`,`countryId`),
  KEY `fk_District_City1_idx` (`cityId`,`provinceId`,`countryId`),
  CONSTRAINT `fk_District_City1` FOREIGN KEY (`cityId`, `provinceId`, `countryId`) REFERENCES `City` (`id`, `provinceId`, `countryId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `District` */

insert  into `District`(`id`,`cityId`,`provinceId`,`countryId`,`name`,`updatedAt`,`modifiedBy`) values (1,1,2,1,'Ciputat Timur','2017-04-04 11:26:58',NULL),(2,1,2,1,'Pisangan','2017-04-04 11:26:58',NULL),(3,1,2,1,'Pamulang','2017-04-04 11:26:58',NULL);

/*Table structure for table `Menu` */

DROP TABLE IF EXISTS `Menu`;

CREATE TABLE `Menu` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `displayName` varchar(100) NOT NULL,
  `routeName` varchar(100) NOT NULL,
  `url` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `order` int(5) DEFAULT NULL,
  `parent` int(12) DEFAULT NULL,
  `isActive` tinyint(1) DEFAULT NULL,
  `createdAt` datetime DEFAULT NULL,
  `updatedAt` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

/*Data for the table `Menu` */

insert  into `Menu`(`id`,`displayName`,`routeName`,`url`,`icon`,`order`,`parent`,`isActive`,`createdAt`,`updatedAt`) values (1,'Dashboard','dashboard','/','fa-home',1,NULL,NULL,'2017-04-08 03:18:15','2017-04-08 18:17:31'),(2,'Menu','manage.menu.index','menu','fa-list',4,NULL,NULL,'2017-04-09 00:53:07','2017-04-08 18:17:36'),(3,'User','manage.user.index','user','fa-user',5,NULL,NULL,'2017-04-08 03:20:22','2017-04-08 18:17:38'),(4,'Role','manage.role.index','role','fa-user',6,NULL,NULL,'2017-04-08 03:26:53','2017-04-08 18:55:15'),(5,'Product','manage.product.index','product','fa-cube',2,NULL,NULL,'2017-04-08 17:04:06','2017-04-08 18:17:33'),(6,'Product Category','manage.product-category.index','product-category','fa-cubes',3,NULL,NULL,'2017-04-08 17:04:49','2017-04-08 18:17:35'),(8,'Transaction','manage.transaction.index','transaction','fa-credit-card',7,NULL,NULL,'2017-04-10 04:17:16','2017-04-10 04:17:16'),(9,'Review','manage.review.index','review','fa-comment-o',8,NULL,NULL,'2017-04-10 05:11:03','2017-04-10 06:32:40'),(10,'Returns','dashboard','/','fa-reply',9,NULL,NULL,'2017-04-10 05:17:00','2017-04-10 06:33:18'),(11,'Supplier','manage.supplier.index','supplier','fa-users',10,NULL,NULL,'2017-04-10 05:58:49','2017-04-10 05:58:49'),(12,'Selling','manage.selling.index','selling','fa-usd',11,NULL,NULL,'2017-04-10 06:04:12','2017-04-10 06:05:24'),(13,'Discussion','manage.discussion.index','discussion','fa-user',12,NULL,NULL,'2017-04-10 06:22:23','2017-04-10 06:22:23'),(14,'Chat','dashboard','dashboard','fa-user',NULL,NULL,NULL,'2017-04-10 06:35:47','2017-04-10 06:35:47'),(15,'Trouble Ticket','dashboard','dashboard','fa-user',13,NULL,NULL,'2017-04-10 06:36:27','2017-04-10 06:36:27'),(16,'Notification','dashboard','dashboard','fa-bolt',14,NULL,NULL,'2017-04-10 06:38:36','2017-04-10 06:38:36'),(17,'Delivery','dashboard','dashbiard','fa-car',15,NULL,NULL,'2017-04-10 06:40:28','2017-04-10 06:40:28');

/*Table structure for table `Merchant` */

DROP TABLE IF EXISTS `Merchant`;

CREATE TABLE `Merchant` (
  `id` int(20) unsigned NOT NULL,
  `name` varchar(32) NOT NULL COMMENT 'Merchnt Name\n',
  `firstName` varchar(64) NOT NULL COMMENT 'Owner First Name',
  `lastName` varchar(64) NOT NULL COMMENT 'Owner Last Name',
  `sex` enum('F','M') NOT NULL COMMENT 'Owner Sex',
  `phone` varchar(16) NOT NULL,
  `description` varchar(128) NOT NULL,
  `logoFile` varchar(48) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `headerFile` varchar(48) DEFAULT NULL,
  `settings` json DEFAULT NULL,
  `address` varchar(64) NOT NULL,
  `districtId` int(3) unsigned NOT NULL,
  `districtName` varchar(32) NOT NULL,
  `cityId` int(3) unsigned NOT NULL,
  `cityName` varchar(32) NOT NULL,
  `provinceId` int(3) unsigned NOT NULL,
  `provinceName` varchar(32) NOT NULL,
  `countryId` int(3) unsigned NOT NULL,
  `countryName` varchar(32) NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_Merchant_District1_idx` (`districtId`,`cityId`,`provinceId`,`countryId`),
  KEY `fk_Merchant_User1_idx` (`id`),
  CONSTRAINT `fk_Merchant_District1` FOREIGN KEY (`districtId`, `cityId`, `provinceId`, `countryId`) REFERENCES `District` (`id`, `cityId`, `provinceId`, `countryId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Merchant_User1` FOREIGN KEY (`id`) REFERENCES `User` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `Merchant` */

insert  into `Merchant`(`id`,`name`,`firstName`,`lastName`,`sex`,`phone`,`description`,`logoFile`,`headerFile`,`settings`,`address`,`districtId`,`districtName`,`cityId`,`cityName`,`provinceId`,`provinceName`,`countryId`,`countryName`,`createdAt`,`updatedAt`) values (2,'Kisel Mart','Kisel','Indonesia','M','081234567890','Kisel Mart','',NULL,NULL,'Jl. Duta Permai',2,'Pisangan',1,'Tangerang Selatan',2,'Banten',1,'Indonesia','2017-04-04 11:26:58','2017-04-04 11:26:58');

/*Table structure for table `Order` */

DROP TABLE IF EXISTS `Order`;

CREATE TABLE `Order` (
  `id` varchar(36) NOT NULL,
  `customerId` int(20) unsigned NOT NULL,
  `customer` json NOT NULL,
  `customerAddressId` varchar(36) NOT NULL,
  `customerAddress` json NOT NULL,
  `totalPrice` double unsigned NOT NULL,
  `totalWeight` double unsigned NOT NULL,
  `totalShippingCost` double unsigned NOT NULL,
  `totalDiscount` double unsigned NOT NULL DEFAULT '0',
  `totalVoucher` double unsigned NOT NULL DEFAULT '0',
  `totalPromo` double NOT NULL DEFAULT '0' COMMENT 'Promo by System',
  `totalMargin` double NOT NULL DEFAULT '0' COMMENT 'Price Margin',
  `voucherCode` varchar(32) DEFAULT NULL,
  `paymentStatus` int(2) NOT NULL,
  `paymentMethod` int(2) NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modifiedBy` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Order_PaymentStatus1_idx` (`paymentStatus`),
  KEY `fk_Order_PaymentMethod1_idx` (`paymentMethod`),
  KEY `fk_Order_Voucher1_idx` (`voucherCode`),
  KEY `fk_Order_Customer1_idx` (`customerId`),
  CONSTRAINT `fk_Order_Customer1` FOREIGN KEY (`customerId`) REFERENCES `Customer` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Order_PaymentMethod1` FOREIGN KEY (`paymentMethod`) REFERENCES `PaymentMethod` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Order_PaymentStatus1` FOREIGN KEY (`paymentStatus`) REFERENCES `PaymentStatus` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Order_Voucher1` FOREIGN KEY (`voucherCode`) REFERENCES `Voucher` (`code`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `Order` */

/*Table structure for table `OrderDetail` */

DROP TABLE IF EXISTS `OrderDetail`;

CREATE TABLE `OrderDetail` (
  `orderId` varchar(36) NOT NULL,
  `merchantId` int(20) unsigned NOT NULL,
  `productId` varchar(36) NOT NULL,
  `customerId` int(20) unsigned NOT NULL COMMENT 'user_id',
  `product` json NOT NULL COMMENT 'Copy of Product information on order placed\n',
  `quantity` int(10) NOT NULL,
  `notes` varchar(140) DEFAULT NULL,
  `options` json DEFAULT NULL COMMENT 'Selected options\n',
  `priceMarginId` varchar(36) DEFAULT NULL,
  `subtotalPrice` double NOT NULL,
  `subtotalWeight` double NOT NULL,
  `subtotalDiscount` double NOT NULL DEFAULT '0',
  `subtotalMargin` double NOT NULL DEFAULT '0',
  `createdAt` datetime NOT NULL,
  `deletedAt` datetime DEFAULT NULL,
  `isDeleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `modifiedBy` json DEFAULT NULL,
  PRIMARY KEY (`orderId`,`merchantId`,`productId`),
  KEY `fk_OrderDetail_PriceMargin1_idx` (`priceMarginId`),
  CONSTRAINT `fk_OrderDetail_OrderMerchant1` FOREIGN KEY (`orderId`, `merchantId`) REFERENCES `OrderMerchant` (`orderId`, `merchantId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_OrderDetail_PriceMargin1` FOREIGN KEY (`priceMarginId`) REFERENCES `PriceMargin` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `OrderDetail` */

/*Table structure for table `OrderMerchant` */

DROP TABLE IF EXISTS `OrderMerchant`;

CREATE TABLE `OrderMerchant` (
  `orderId` varchar(36) NOT NULL,
  `merchantId` int(20) unsigned NOT NULL,
  `customerId` int(20) unsigned NOT NULL,
  `customer` json NOT NULL COMMENT 'Copy of user table',
  `courierCostId` varchar(36) NOT NULL,
  `courierCost` json NOT NULL,
  `customerAddressId` varchar(36) NOT NULL,
  `customerAddress` json NOT NULL,
  `subtotalPrice` double unsigned NOT NULL,
  `subtotalWeight` double unsigned NOT NULL,
  `subtotalShippingCost` double unsigned NOT NULL,
  `subtotalDiscount` double unsigned NOT NULL DEFAULT '0',
  `subtotalMargin` double NOT NULL DEFAULT '0',
  `status` int(2) unsigned NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modifiedBy` json DEFAULT NULL,
  PRIMARY KEY (`orderId`,`merchantId`),
  KEY `fk_OrderMerchant_OrderStatus1_idx` (`status`),
  KEY `fk_OrderMerchant_Merchant1_idx` (`merchantId`),
  CONSTRAINT `fk_OrderMerchant_Merchant1` FOREIGN KEY (`merchantId`) REFERENCES `Merchant` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_OrderMerchant_Order1` FOREIGN KEY (`orderId`) REFERENCES `Order` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_OrderMerchant_OrderStatus1` FOREIGN KEY (`status`) REFERENCES `OrderStatus` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `OrderMerchant` */

/*Table structure for table `OrderPayment` */

DROP TABLE IF EXISTS `OrderPayment`;

CREATE TABLE `OrderPayment` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `orderId` varchar(36) NOT NULL,
  `status` int(2) NOT NULL,
  `response` json NOT NULL COMMENT 'Response from Payment Gateway API',
  `transactionId` varchar(128) DEFAULT NULL COMMENT 'Transaction ID from Payment Gatewat API',
  `notes` varchar(64) DEFAULT NULL,
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modifiedBy` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`id`,`orderId`),
  KEY `fk_OrderPayment_PaymentStatus1_idx` (`status`),
  KEY `fk_OrderPayment_Order1` (`orderId`),
  CONSTRAINT `fk_OrderPayment_Order1` FOREIGN KEY (`orderId`) REFERENCES `Order` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_OrderPayment_PaymentStatus1` FOREIGN KEY (`status`) REFERENCES `PaymentStatus` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `OrderPayment` */

/*Table structure for table `OrderStatus` */

DROP TABLE IF EXISTS `OrderStatus`;

CREATE TABLE `OrderStatus` (
  `id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) CHARACTER SET latin1 NOT NULL,
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modifiedBy` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Data for the table `OrderStatus` */

insert  into `OrderStatus`(`id`,`name`,`updatedAt`,`modifiedBy`) values (1,'Placed','2017-04-04 11:27:02',NULL),(2,'Paid','2017-04-04 11:27:02',NULL),(3,'Payment Verified','2017-04-04 11:27:02',NULL),(4,'Shipped','2017-04-04 11:27:02',NULL),(5,'Delivered','2017-04-04 11:27:02',NULL),(6,'Success','2017-04-04 11:27:02',NULL),(7,'Canceled','2017-04-04 11:27:02',NULL),(8,'Returned','2017-04-04 11:27:02',NULL);

/*Table structure for table `OrderStatusLog` */

DROP TABLE IF EXISTS `OrderStatusLog`;

CREATE TABLE `OrderStatusLog` (
  `id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `orderId` varchar(36) NOT NULL,
  `merchantId` int(20) unsigned NOT NULL,
  `status` int(2) unsigned NOT NULL,
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modifiedBy` json DEFAULT NULL,
  PRIMARY KEY (`id`,`orderId`,`merchantId`),
  KEY `fk_OrderStatusLog_OrderMerchant1_idx` (`orderId`,`merchantId`),
  KEY `fk_OrderStatusLog_OrderStatus1_idx` (`status`),
  CONSTRAINT `fk_OrderStatusLog_OrderMerchant1` FOREIGN KEY (`orderId`, `merchantId`) REFERENCES `OrderMerchant` (`orderId`, `merchantId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_OrderStatusLog_OrderStatus1` FOREIGN KEY (`status`) REFERENCES `OrderStatus` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `OrderStatusLog` */

/*Table structure for table `PaymentMethod` */

DROP TABLE IF EXISTS `PaymentMethod`;

CREATE TABLE `PaymentMethod` (
  `id` int(2) NOT NULL,
  `name` varchar(32) CHARACTER SET latin1 NOT NULL,
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modifiedBy` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `PaymentMethod` */

insert  into `PaymentMethod`(`id`,`name`,`updatedAt`,`modifiedBy`) values (1,'Bank Transfer','2017-04-04 11:27:01',NULL),(2,'Credit Card','2017-04-04 11:27:01',NULL);

/*Table structure for table `PaymentStatus` */

DROP TABLE IF EXISTS `PaymentStatus`;

CREATE TABLE `PaymentStatus` (
  `id` int(2) NOT NULL,
  `name` varchar(32) CHARACTER SET latin1 NOT NULL,
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modifiedBy` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `PaymentStatus` */

insert  into `PaymentStatus`(`id`,`name`,`updatedAt`,`modifiedBy`) values (1,'Unpaid','2017-04-04 11:27:01',NULL),(2,'Pending','2017-04-04 11:27:01',NULL),(3,'Paid','2017-04-04 11:27:01',NULL),(4,'Rejected','2017-04-04 11:27:01',NULL),(5,'Canceled','2017-04-04 11:27:01',NULL);

/*Table structure for table `PriceMargin` */

DROP TABLE IF EXISTS `PriceMargin`;

CREATE TABLE `PriceMargin` (
  `id` varchar(36) CHARACTER SET latin1 NOT NULL COMMENT 'UUIDv4 Generated\n',
  `priceMargin` double NOT NULL DEFAULT '0' COMMENT 'Price margin in percentage\n',
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modifiedBy` int(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ApplicationSetting_Admin1_idx` (`modifiedBy`),
  CONSTRAINT `fk_ApplicationSetting_Admin1` FOREIGN KEY (`modifiedBy`) REFERENCES `Admin` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `PriceMargin` */

insert  into `PriceMargin`(`id`,`priceMargin`,`createdAt`,`modifiedBy`) values ('0',0,'2017-04-04 11:27:03',NULL);

/*Table structure for table `Product` */

DROP TABLE IF EXISTS `Product`;

CREATE TABLE `Product` (
  `id` varchar(36) NOT NULL COMMENT 'UUIDv4 Generated',
  `merchantId` int(20) unsigned NOT NULL,
  `name` varchar(128) NOT NULL,
  `price` double NOT NULL,
  `discount` double NOT NULL DEFAULT '0' COMMENT 'discount percentage',
  `currencyId` int(3) unsigned NOT NULL,
  `currencyCode` varchar(4) NOT NULL,
  `productCategoryId` varchar(36) NOT NULL,
  `productCategoryName` varchar(32) NOT NULL,
  `condition` enum('NEW','USED') NOT NULL DEFAULT 'NEW',
  `options` json DEFAULT NULL,
  `images` json NOT NULL COMMENT 'Consist array of images file',
  `description` varchar(140) NOT NULL COMMENT 'Short description',
  `descriptionFile` varchar(40) DEFAULT NULL COMMENT 'Save long description in a markdown file. Description file is <product_id>.md',
  `stock` int(20) NOT NULL DEFAULT '0',
  `specification` json DEFAULT NULL,
  `weight` int(10) NOT NULL COMMENT 'Product weight in gram\n',
  `tags` json DEFAULT NULL,
  `isPublished` tinyint(1) NOT NULL DEFAULT '1',
  `ratings` int(1) NOT NULL DEFAULT '0' COMMENT 'Average ratings\n',
  `reviewCount` int(20) NOT NULL DEFAULT '0',
  `sortPriority` int(2) NOT NULL DEFAULT '0',
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deletedAt` datetime DEFAULT NULL,
  `isDeleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_Product_Merchant1_idx` (`merchantId`),
  KEY `fk_Product_Currency1_idx` (`currencyId`),
  KEY `fk_Product_ProductCategory1_idx` (`productCategoryId`),
  CONSTRAINT `fk_Product_Currency1` FOREIGN KEY (`currencyId`) REFERENCES `Currency` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Product_Merchant1` FOREIGN KEY (`merchantId`) REFERENCES `Merchant` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Product_ProductCategory1` FOREIGN KEY (`productCategoryId`) REFERENCES `ProductCategory` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `Product` */

insert  into `Product`(`id`,`merchantId`,`name`,`price`,`discount`,`currencyId`,`currencyCode`,`productCategoryId`,`productCategoryName`,`condition`,`options`,`images`,`description`,`descriptionFile`,`stock`,`specification`,`weight`,`tags`,`isPublished`,`ratings`,`reviewCount`,`sortPriority`,`createdAt`,`updatedAt`,`deletedAt`,`isDeleted`) values ('6c0b795d-927c-4e84-a4d9-f0d2c47e916c',2,'Minyak Goreng Bimoli 2 L',36200,0,1,'IDR','6e528d9c-a377-4d12-93e2-6e80a216f29b','Oil','NEW',NULL,'[]','Minyak Goreng Mantap',NULL,10,NULL,1000,NULL,1,0,0,0,'2017-04-04 11:26:59','2017-04-08 09:20:33',NULL,0);

/*Table structure for table `ProductCategory` */

DROP TABLE IF EXISTS `ProductCategory`;

CREATE TABLE `ProductCategory` (
  `id` varchar(36) NOT NULL COMMENT 'UUIDv4 Generated',
  `name` varchar(32) NOT NULL,
  `parent` json DEFAULT NULL COMMENT 'If parent NULL, the category is a root\n\nInput must be array of ids, for example [''category1_id'', ''category2_id], then converted to object of category by TRIGGER. For example [{''no'': 0, ''id'': ''category1_id'', ''name'': ''category1_name''},{''no'': 1, ''id'': ''category2_id'', ''name'': ''category2_name''}]. Where 0 is root, 1 is child etc.',
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modifiedBy` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `ProductCategory` */

insert  into `ProductCategory`(`id`,`name`,`parent`,`updatedAt`,`modifiedBy`) values ('263a29dd-9caf-46c0-86cf-76400c447237','Handphone','[{\"id\": \"f748236c-f00a-4519-a1f6-ed58b1cbb608\", \"name\": \"Electronic\", \"index\": 0}]','2017-04-04 11:26:59',NULL),('2846b1a0-6653-4b1d-9379-41c3f7b93fb3','Tablet','[{\"id\": \"f748236c-f00a-4519-a1f6-ed58b1cbb608\", \"name\": \"Electronic\", \"index\": 0}]','2017-04-04 11:26:59',NULL),('4d3e8074-dc5d-4040-96c5-a464d902cc86','Tea','[{\"id\": \"6fb5c25a-e989-4ee0-b105-fd799da080b3\", \"name\": \"Grocery\", \"index\": 0}]','2017-04-04 11:26:59',0),('6e528d9c-a377-4d12-93e2-6e80a216f29b','Oil','[{\"id\": \"6fb5c25a-e989-4ee0-b105-fd799da080b3\", \"name\": \"Grocery\", \"index\": 0}]','2017-04-04 11:26:59',0),('6fb5c25a-e989-4ee0-b105-fd799da080b3','Grocery',NULL,'2017-04-04 11:26:59',0),('9a477523-35f6-4a29-abb4-9bc571a37e37','Milk','[{\"id\": \"6fb5c25a-e989-4ee0-b105-fd799da080b3\", \"name\": \"Grocery\", \"index\": 0}]','2017-04-04 11:26:59',NULL),('ec3ed96d-f1c6-46fa-a3f2-f97db9b6cd22','Handphone Accessories','[{\"id\": \"f748236c-f00a-4519-a1f6-ed58b1cbb608\", \"name\": \"Electronic\", \"index\": 0}, {\"id\": \"263a29dd-9caf-46c0-86cf-76400c447237\", \"name\": \"Handphone\", \"index\": 1}]','2017-04-04 11:26:59',NULL),('f748236c-f00a-4519-a1f6-ed58b1cbb608','Electronic',NULL,'2017-04-04 11:26:59',NULL);

/*Table structure for table `ProductStockLog` */

DROP TABLE IF EXISTS `ProductStockLog`;

CREATE TABLE `ProductStockLog` (
  `no` int(20) NOT NULL AUTO_INCREMENT,
  `productId` varchar(36) CHARACTER SET latin1 NOT NULL,
  `count` int(11) NOT NULL,
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`no`,`productId`),
  KEY `fk_ProductStockLog_Product1_idx` (`productId`),
  CONSTRAINT `fk_ProductStockLog_Product1` FOREIGN KEY (`productId`) REFERENCES `Product` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `ProductStockLog` */

insert  into `ProductStockLog`(`no`,`productId`,`count`,`updatedAt`) values (1,'6c0b795d-927c-4e84-a4d9-f0d2c47e916c',10,'2017-04-04 11:26:59');

/*Table structure for table `Promo` */

DROP TABLE IF EXISTS `Promo`;

CREATE TABLE `Promo` (
  `id` varchar(36) NOT NULL,
  `startDate` datetime NOT NULL,
  `endDate` datetime NOT NULL,
  `title` varchar(64) NOT NULL,
  `description` varchar(256) NOT NULL,
  `images` json DEFAULT NULL COMMENT 'Contains Array of images\n',
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deletedAt` datetime DEFAULT NULL,
  `isDeleted` tinyint(1) NOT NULL DEFAULT '0',
  `modifiedBy` int(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Promo_Admin1_idx` (`modifiedBy`),
  CONSTRAINT `fk_Promo_Admin1` FOREIGN KEY (`modifiedBy`) REFERENCES `Admin` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `Promo` */

/*Table structure for table `PromoUsage` */

DROP TABLE IF EXISTS `PromoUsage`;

CREATE TABLE `PromoUsage` (
  `orderId` varchar(36) NOT NULL,
  `promoId` varchar(36) NOT NULL,
  `productId` varchar(36) NOT NULL,
  `promoHistory` json DEFAULT NULL COMMENT 'Promo History',
  PRIMARY KEY (`orderId`,`promoId`,`productId`),
  KEY `fk_Order_has_PromotedProduct_PromotedProduct1_idx` (`promoId`,`productId`),
  KEY `fk_Order_has_PromotedProduct_Order1_idx` (`orderId`),
  CONSTRAINT `fk_Order_has_PromotedProduct_Order1` FOREIGN KEY (`orderId`) REFERENCES `Order` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Order_has_PromotedProduct_PromotedProduct1` FOREIGN KEY (`promoId`, `productId`) REFERENCES `PromotedProduct` (`promoId`, `productId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `PromoUsage` */

/*Table structure for table `PromotedProduct` */

DROP TABLE IF EXISTS `PromotedProduct`;

CREATE TABLE `PromotedProduct` (
  `promoId` varchar(36) CHARACTER SET latin1 NOT NULL,
  `productId` varchar(36) CHARACTER SET latin1 NOT NULL,
  `discount` double NOT NULL COMMENT 'Discount percentage',
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modifiedBy` int(20) unsigned NOT NULL,
  PRIMARY KEY (`promoId`,`productId`),
  KEY `fk_Product_has_Promo_Promo1_idx` (`promoId`),
  KEY `fk_Product_has_Promo_Product1_idx` (`productId`),
  KEY `fk_PromoProduct_Admin1_idx` (`modifiedBy`),
  CONSTRAINT `fk_Product_has_Promo_Product1` FOREIGN KEY (`productId`) REFERENCES `Product` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Product_has_Promo_Promo1` FOREIGN KEY (`promoId`) REFERENCES `Promo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_PromoProduct_Admin1` FOREIGN KEY (`modifiedBy`) REFERENCES `Admin` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `PromotedProduct` */

/*Table structure for table `Province` */

DROP TABLE IF EXISTS `Province`;

CREATE TABLE `Province` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `countryId` int(3) unsigned NOT NULL,
  `name` varchar(32) NOT NULL,
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modifiedBy` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`,`countryId`),
  KEY `fk_Province_Country1_idx` (`countryId`),
  CONSTRAINT `fk_Province_Country1` FOREIGN KEY (`countryId`) REFERENCES `Country` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `Province` */

insert  into `Province`(`id`,`countryId`,`name`,`updatedAt`,`modifiedBy`) values (1,1,'DKI Jakarta','2017-04-04 11:26:56',NULL),(2,1,'Banten','2017-04-04 11:26:56',NULL),(3,1,'Jawa Barat','2017-04-04 11:26:56',NULL),(4,1,'Sulawesi Tengah','2017-04-04 11:26:57',NULL);

/*Table structure for table `Review` */

DROP TABLE IF EXISTS `Review`;

CREATE TABLE `Review` (
  `id` varchar(36) CHARACTER SET latin1 NOT NULL,
  `productId` varchar(36) CHARACTER SET latin1 NOT NULL,
  `customerId` int(20) unsigned NOT NULL,
  `orderId` varchar(36) CHARACTER SET latin1 NOT NULL,
  `merchantId` int(20) unsigned NOT NULL,
  `text` varchar(256) CHARACTER SET latin1 DEFAULT NULL,
  `rating` int(1) NOT NULL DEFAULT '0',
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `fk_Review_Product1_idx` (`productId`),
  KEY `fk_Review_Customer1_idx` (`customerId`),
  KEY `fk_Review_Order1_idx` (`orderId`),
  KEY `fk_Review_Merchant1_idx` (`merchantId`),
  CONSTRAINT `fk_Review_Customer1` FOREIGN KEY (`customerId`) REFERENCES `Customer` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Review_Merchant1` FOREIGN KEY (`merchantId`) REFERENCES `Merchant` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Review_Order1` FOREIGN KEY (`orderId`) REFERENCES `Order` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Review_Product1` FOREIGN KEY (`productId`) REFERENCES `Product` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `Review` */

/*Table structure for table `Role` */

DROP TABLE IF EXISTS `Role`;

CREATE TABLE `Role` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) CHARACTER SET latin1 NOT NULL,
  `description` varchar(128) CHARACTER SET latin1 DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `Role` */

insert  into `Role`(`id`,`name`,`description`,`created`,`modified`) values (1,'Admin','Administrator','2017-04-07 13:29:25','2017-04-08 17:07:05'),(2,'Merchant','Merchant','2017-04-10 06:23:15','2017-04-10 06:26:27');

/*Table structure for table `RoleMapping` */

DROP TABLE IF EXISTS `RoleMapping`;

CREATE TABLE `RoleMapping` (
  `id` int(21) NOT NULL AUTO_INCREMENT,
  `principalType` enum('USER','APP','ROLE') CHARACTER SET latin1 NOT NULL DEFAULT 'USER',
  `principalId` varchar(32) CHARACTER SET latin1 NOT NULL,
  `roleId` int(2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_RoleMapping_Role1_idx` (`roleId`),
  CONSTRAINT `fk_RoleMapping_Role1` FOREIGN KEY (`roleId`) REFERENCES `Role` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `RoleMapping` */

/*Table structure for table `RoleMenu` */

DROP TABLE IF EXISTS `RoleMenu`;

CREATE TABLE `RoleMenu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roleId` int(11) unsigned DEFAULT NULL,
  `menuId` int(11) unsigned DEFAULT NULL,
  `order` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `roleId` (`roleId`),
  KEY `menuId` (`menuId`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

/*Data for the table `RoleMenu` */

insert  into `RoleMenu`(`id`,`roleId`,`menuId`,`order`) values (4,1,1,NULL),(5,1,5,NULL),(6,1,6,NULL),(7,1,2,NULL),(8,1,3,NULL),(9,1,4,NULL),(10,1,8,NULL),(11,1,9,NULL),(12,1,10,NULL),(13,2,1,NULL),(14,2,5,NULL),(15,2,9,NULL),(16,2,10,NULL),(17,2,11,NULL),(18,2,12,NULL),(19,2,13,NULL),(20,1,12,NULL),(21,1,14,NULL),(22,1,15,NULL),(23,1,16,NULL),(24,1,17,NULL);

/*Table structure for table `Token` */

DROP TABLE IF EXISTS `Token`;

CREATE TABLE `Token` (
  `id` varchar(36) CHARACTER SET latin1 NOT NULL,
  `userId` int(20) unsigned NOT NULL,
  `token` varchar(512) CHARACTER SET latin1 NOT NULL,
  `type` enum('FCM','APNS','FACEBOOK') CHARACTER SET latin1 NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_table1_Users1_idx` (`userId`),
  CONSTRAINT `fk_table1_Users1` FOREIGN KEY (`userId`) REFERENCES `User` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `Token` */

/*Table structure for table `User` */

DROP TABLE IF EXISTS `User`;

CREATE TABLE `User` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `realm` varchar(2) CHARACTER SET latin1 DEFAULT NULL,
  `username` varchar(64) CHARACTER SET latin1 NOT NULL,
  `password` varchar(128) CHARACTER SET latin1 NOT NULL,
  `email` varchar(64) CHARACTER SET latin1 NOT NULL,
  `emailVerified` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `verificationToken` varchar(128) CHARACTER SET latin1 DEFAULT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `User` */

insert  into `User`(`id`,`realm`,`username`,`password`,`email`,`emailVerified`,`verificationToken`,`createdAt`,`updatedAt`) values (1,NULL,'admin','$2y$10$lBHCx4SOOJ0diuioR26gUOTSw1e4v.WZkY.5qR6nTtWilPCR4x61C','commerce.admin@kiselindonesia.com',1,NULL,'2017-04-04 11:26:56','2017-04-10 02:47:33'),(2,NULL,'kiselmart','$2y$10$gWtQLEIVETd4aE55.2bUNOSLxtnPjSNvEfW06RORXXIZeCVz0yDPO','kisel.mart@kiselindonesia.com',1,NULL,'2017-04-04 11:26:56','2017-04-10 01:32:37'),(3,NULL,'Test','$2y$10$/XPo9uuWMY8yZDNG8aaSYupDzZNqG0PPebMFC9D2uD8Ii.Qy.a8jC','test@email.com',1,NULL,'2017-04-07 03:54:22','2017-04-07 06:45:01'),(4,NULL,'Testing','$2y$10$f72MI5ib9kMrTfU5mSMnQ.f5A28wqkTokjYKJeGE7q7xXc.W26VRO','testing@gmail.com',0,NULL,'2017-04-07 14:56:59','2017-04-10 02:05:17');

/*Table structure for table `UserRole` */

DROP TABLE IF EXISTS `UserRole`;

CREATE TABLE `UserRole` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `userId` int(20) NOT NULL,
  `roleId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`) USING BTREE,
  KEY `roleId` (`roleId`) USING BTREE,
  CONSTRAINT `UserRole_ibfk_1` FOREIGN KEY (`roleId`) REFERENCES `Role` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `UserRole` */

insert  into `UserRole`(`id`,`userId`,`roleId`) values (1,3,1),(2,1,1),(3,4,1),(4,2,2);

/*Table structure for table `Voucher` */

DROP TABLE IF EXISTS `Voucher`;

CREATE TABLE `Voucher` (
  `code` varchar(32) NOT NULL COMMENT 'Voucher Code\n',
  `startDate` datetime NOT NULL,
  `endDate` datetime NOT NULL,
  `title` varchar(64) NOT NULL,
  `description` varchar(256) NOT NULL,
  `quantity` int(20) NOT NULL DEFAULT '0',
  `rules` json DEFAULT NULL,
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deletedAt` datetime DEFAULT NULL,
  `isDeleted` tinyint(1) NOT NULL DEFAULT '0',
  `modifiedBy` int(20) unsigned NOT NULL,
  PRIMARY KEY (`code`),
  KEY `fk_Voucher_Admin1_idx` (`modifiedBy`),
  CONSTRAINT `fk_Voucher_Admin1` FOREIGN KEY (`modifiedBy`) REFERENCES `Admin` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `Voucher` */

/* Trigger structure for table `CourierCost` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `CourierCost_BEFORE_INSERT` */$$

/*!50003 CREATE */ /*!50017 DEFINER = CURRENT_USER */ /*!50003 TRIGGER `CourierCost_BEFORE_INSERT` BEFORE INSERT ON `CourierCost` FOR EACH ROW BEGIN
	# Fill denormalized values
    # -- Declare Variables
    DECLARE courierName, courierPackageName VARCHAR(32); DECLARE currencyCode VARCHAR(4);
    # -- Courier Package
    SELECT `name`, `CourierPackage`.`courierName` INTO @courierPackageName, @courierName FROM `KiselCommerce`.`CourierPackage`
		WHERE `id` = NEW.`courierPackageId` AND `courierId` = NEW.`courierId`;
        
    SET NEW.`courierName` = @courierName;
    SET NEW.`courierPackageName` = @courierPackageName;
    
    # -- Currency
    SELECT `code` INTO @currencyCode FROM `KiselCommerce`.`Currency` WHERE `id` = NEW.`currencyId`;
    SET NEW.`currencyCode` = @currencyCode;
END */$$


DELIMITER ;

/* Trigger structure for table `CourierPackage` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `CourierPackage_BEFORE_INSERT` */$$

/*!50003 CREATE */ /*!50017 DEFINER = CURRENT_USER */ /*!50003 TRIGGER `CourierPackage_BEFORE_INSERT` BEFORE INSERT ON `CourierPackage` FOR EACH ROW BEGIN
	DECLARE courierName VARCHAR(32);
    
    SELECT `name` INTO @courierName FROM `KiselCommerce`.`Courier` WHERE `id` = NEW.`courierId`;
    SET NEW.`courierName` := @courierName;
END */$$


DELIMITER ;

/* Trigger structure for table `CustomerAddress` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `CustomerAddress_BEFORE_INSERT` */$$

/*!50003 CREATE */ /*!50017 DEFINER = CURRENT_USER */ /*!50003 TRIGGER `CustomerAddress_BEFORE_INSERT` BEFORE INSERT ON `CustomerAddress` FOR EACH ROW BEGIN
	DECLARE districtName, cityName, provinceName, countryName VARCHAR(32);
    
	CALL `KiselCommerce`.getCompleteAddress(
		NEW.`districtId`, NEW.`cityId`, NEW.`provinceID`, NEW.`countryId`,
		@districtName, @cityName, @provinceName, @countryName);

	SET NEW.`districtName` = @districtName;
    SET NEW.`cityName` = @cityName;
    SET NEW.`provinceName` = @provinceName;
    SET NEW.`countryName` = @countryName;
END */$$


DELIMITER ;

/* Trigger structure for table `Merchant` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `Merchant_BEFORE_INSERT` */$$

/*!50003 CREATE */ /*!50017 DEFINER = CURRENT_USER */ /*!50003 TRIGGER `Merchant_BEFORE_INSERT` BEFORE INSERT ON `Merchant` FOR EACH ROW BEGIN
	DECLARE districtName, cityName, provinceName, countryName VARCHAR(32);
    
	CALL `KiselCommerce`.getCompleteAddress(
		NEW.`districtId`, NEW.`cityId`, NEW.`provinceID`, NEW.`countryId`,
		@districtName, @cityName, @provinceName, @countryName);

	SET NEW.`districtName` = @districtName;
    SET NEW.`cityName` = @cityName;
    SET NEW.`provinceName` = @provinceName;
    SET NEW.`countryName` = @countryName;
END */$$


DELIMITER ;

/* Trigger structure for table `OrderMerchant` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `OrderMerchant_AFTER_INSERT` */$$

/*!50003 CREATE */ /*!50017 DEFINER = CURRENT_USER */ /*!50003 TRIGGER `OrderMerchant_AFTER_INSERT` AFTER INSERT ON `OrderMerchant` FOR EACH ROW BEGIN	
	INSERT INTO `KiselCommerce`.`OrderStatusLog`
		(`orderId`, `merchantId`, `status`, `updatedAt`, `modifiedBy`)
	VALUES
		(NEW.`orderId`, NEW.`merchantId`, NEW.`status`, NEW.`updatedAt`, NEW.`modifiedBy`);
END */$$


DELIMITER ;

/* Trigger structure for table `OrderMerchant` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `OrderMerchant_AFTER_UPDATE` */$$

/*!50003 CREATE */ /*!50017 DEFINER = CURRENT_USER */ /*!50003 TRIGGER `OrderMerchant_AFTER_UPDATE` AFTER UPDATE ON `OrderMerchant` FOR EACH ROW BEGIN
	IF (NEW.`status` <> OLD.`status`) THEN
		INSERT INTO `KiselCommerce`.`OrderStatusLog`
			(`orderId`, `merchantId`, `status`, `updatedAt`, `modifiedBy`)
		VALUES
			(OLD.`orderId`, OLD.`merchantId`, NEW.`status`, NEW.`updatedAt`, NEW.`modifiedBy`);
    END IF;
END */$$


DELIMITER ;

/* Trigger structure for table `Product` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `Product_BEFORE_INSERT` */$$

/*!50003 CREATE */ /*!50017 DEFINER = CURRENT_USER */ /*!50003 TRIGGER `Product_BEFORE_INSERT` BEFORE INSERT ON `Product` FOR EACH ROW BEGIN
	# Complete Denormalized Field
    # -- Declare variables
    DECLARE currencyCode VARCHAR(4); DECLARE productCategoryName VARCHAR(32);    
    # -- Currency
    SELECT `code` INTO @currencyCode FROM `KiselCommerce`.`Currency` WHERE `id` = NEW.`currencyId`;
    SET NEW.`currencyCode` = @currencyCode;
    # -- Product Category
    SELECT `name` INTO @productCategoryName FROM `KiselCommerce`.`ProductCategory` WHERE `id` = NEW.`productCategoryId`;
    SET NEW.`productCategoryName` = @productCategoryName;
END */$$


DELIMITER ;
