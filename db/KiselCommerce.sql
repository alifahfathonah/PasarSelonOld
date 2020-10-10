/*
Navicat MySQL Data Transfer

Source Server         : NBS01
Source Server Version : 50711
Source Host           : nbs-rds-01.cbdishgab2xi.ap-southeast-1.rds.amazonaws.com:3306
Source Database       : KiselCommerce

Target Server Type    : MYSQL
Target Server Version : 50711
File Encoding         : 65001

Date: 2017-04-10 18:22:27
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for AccessToken
-- ----------------------------
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

-- ----------------------------
-- Table structure for ACL
-- ----------------------------
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

-- ----------------------------
-- Table structure for Admin
-- ----------------------------
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

-- ----------------------------
-- Table structure for Bank
-- ----------------------------
DROP TABLE IF EXISTS `Bank`;
CREATE TABLE `Bank` (
  `id` varchar(36) NOT NULL,
  `name` varchar(32) NOT NULL,
  `code` varchar(4) DEFAULT NULL,
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modifiedBy` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for Cart
-- ----------------------------
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

-- ----------------------------
-- Table structure for City
-- ----------------------------
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

-- ----------------------------
-- Table structure for Complaints
-- ----------------------------
DROP TABLE IF EXISTS `Complaints`;
CREATE TABLE `Complaints` (
  `id` int(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for Country
-- ----------------------------
DROP TABLE IF EXISTS `Country`;
CREATE TABLE `Country` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modifiedBy` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for Courier
-- ----------------------------
DROP TABLE IF EXISTS `Courier`;
CREATE TABLE `Courier` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) CHARACTER SET latin1 NOT NULL,
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modifiedBy` int(20) DEFAULT NULL COMMENT 'Admin who modifies data',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for CourierCost
-- ----------------------------
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

-- ----------------------------
-- Table structure for CourierPackage
-- ----------------------------
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

-- ----------------------------
-- Table structure for Currency
-- ----------------------------
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

-- ----------------------------
-- Table structure for Customer
-- ----------------------------
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

-- ----------------------------
-- Table structure for CustomerAddress
-- ----------------------------
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

-- ----------------------------
-- Table structure for CustomerBankAccount
-- ----------------------------
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

-- ----------------------------
-- Table structure for District
-- ----------------------------
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

-- ----------------------------
-- Table structure for Menu
-- ----------------------------
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

-- ----------------------------
-- Table structure for Merchant
-- ----------------------------
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

-- ----------------------------
-- Table structure for Order
-- ----------------------------
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

-- ----------------------------
-- Table structure for OrderDetail
-- ----------------------------
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

-- ----------------------------
-- Table structure for OrderMerchant
-- ----------------------------
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

-- ----------------------------
-- Table structure for OrderPayment
-- ----------------------------
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

-- ----------------------------
-- Table structure for OrderStatus
-- ----------------------------
DROP TABLE IF EXISTS `OrderStatus`;
CREATE TABLE `OrderStatus` (
  `id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) CHARACTER SET latin1 NOT NULL,
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modifiedBy` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for OrderStatusLog
-- ----------------------------
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

-- ----------------------------
-- Table structure for PaymentMethod
-- ----------------------------
DROP TABLE IF EXISTS `PaymentMethod`;
CREATE TABLE `PaymentMethod` (
  `id` int(2) NOT NULL,
  `name` varchar(32) CHARACTER SET latin1 NOT NULL,
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modifiedBy` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for PaymentStatus
-- ----------------------------
DROP TABLE IF EXISTS `PaymentStatus`;
CREATE TABLE `PaymentStatus` (
  `id` int(2) NOT NULL,
  `name` varchar(32) CHARACTER SET latin1 NOT NULL,
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modifiedBy` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for PriceMargin
-- ----------------------------
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

-- ----------------------------
-- Table structure for Product
-- ----------------------------
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

-- ----------------------------
-- Table structure for ProductCategory
-- ----------------------------
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

-- ----------------------------
-- Table structure for ProductStockLog
-- ----------------------------
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

-- ----------------------------
-- Table structure for Promo
-- ----------------------------
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

-- ----------------------------
-- Table structure for PromotedProduct
-- ----------------------------
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

-- ----------------------------
-- Table structure for PromoUsage
-- ----------------------------
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

-- ----------------------------
-- Table structure for Province
-- ----------------------------
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

-- ----------------------------
-- Table structure for Review
-- ----------------------------
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

-- ----------------------------
-- Table structure for Role
-- ----------------------------
DROP TABLE IF EXISTS `Role`;
CREATE TABLE `Role` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) CHARACTER SET latin1 NOT NULL,
  `description` varchar(128) CHARACTER SET latin1 DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for RoleMapping
-- ----------------------------
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

-- ----------------------------
-- Table structure for RoleMenu
-- ----------------------------
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

-- ----------------------------
-- Table structure for Token
-- ----------------------------
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

-- ----------------------------
-- Table structure for User
-- ----------------------------
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

-- ----------------------------
-- Table structure for UserRole
-- ----------------------------
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

-- ----------------------------
-- Table structure for Voucher
-- ----------------------------
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

-- ----------------------------
-- Procedure structure for getCompleteAddress
-- ----------------------------
DROP PROCEDURE IF EXISTS `getCompleteAddress`;
DELIMITER ;;
CREATE DEFINER=CURRENT_USER PROCEDURE `getCompleteAddress`(
	IN districtId INT(3), IN cityId INT(3), IN provinceID INT(3), countryID INT(3), OUT districtName VARCHAR(32),
	OUT cityName VARCHAR(32), OUT provinceName VARCHAR(32), OUT countryName VARCHAR(32))
BEGIN
		SELECT a.name, b.name, c.name, d.name INTO districtName, cityName, provinceName, countryName
		FROM `KiselCommerce`.`District` AS a
			INNER JOIN `KiselCommerce`.`City` AS b ON a.`cityId` = b.`id`
			INNER JOIN `KiselCommerce`.`Province` AS c ON b.`provinceId` = c.`id`
			INNER JOIN `KiselCommerce`.`Country` AS d ON c.`countryId` = d.`id`
		WHERE			
			a.`id` = districtId AND
			b.`id` = cityId AND
			c.`id` = provinceId AND
			d.`id` = countryID;
	END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `CourierCost_BEFORE_INSERT`;
DELIMITER ;;
CREATE TRIGGER `CourierCost_BEFORE_INSERT` BEFORE INSERT ON `CourierCost` FOR EACH ROW BEGIN
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
END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `CourierPackage_BEFORE_INSERT`;
DELIMITER ;;
CREATE TRIGGER `CourierPackage_BEFORE_INSERT` BEFORE INSERT ON `CourierPackage` FOR EACH ROW BEGIN
	DECLARE courierName VARCHAR(32);
    
    SELECT `name` INTO @courierName FROM `KiselCommerce`.`Courier` WHERE `id` = NEW.`courierId`;
    SET NEW.`courierName` := @courierName;
END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `CustomerAddress_BEFORE_INSERT`;
DELIMITER ;;
CREATE TRIGGER `CustomerAddress_BEFORE_INSERT` BEFORE INSERT ON `CustomerAddress` FOR EACH ROW BEGIN
	DECLARE districtName, cityName, provinceName, countryName VARCHAR(32);
    
	CALL `KiselCommerce`.getCompleteAddress(
		NEW.`districtId`, NEW.`cityId`, NEW.`provinceID`, NEW.`countryId`,
		@districtName, @cityName, @provinceName, @countryName);

	SET NEW.`districtName` = @districtName;
    SET NEW.`cityName` = @cityName;
    SET NEW.`provinceName` = @provinceName;
    SET NEW.`countryName` = @countryName;
END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `Merchant_BEFORE_INSERT`;
DELIMITER ;;
CREATE TRIGGER `Merchant_BEFORE_INSERT` BEFORE INSERT ON `Merchant` FOR EACH ROW BEGIN
	DECLARE districtName, cityName, provinceName, countryName VARCHAR(32);
    
	CALL `KiselCommerce`.getCompleteAddress(
		NEW.`districtId`, NEW.`cityId`, NEW.`provinceID`, NEW.`countryId`,
		@districtName, @cityName, @provinceName, @countryName);

	SET NEW.`districtName` = @districtName;
    SET NEW.`cityName` = @cityName;
    SET NEW.`provinceName` = @provinceName;
    SET NEW.`countryName` = @countryName;
END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `OrderMerchant_AFTER_INSERT`;
DELIMITER ;;
CREATE TRIGGER `OrderMerchant_AFTER_INSERT` AFTER INSERT ON `OrderMerchant` FOR EACH ROW BEGIN	
	INSERT INTO `KiselCommerce`.`OrderStatusLog`
		(`orderId`, `merchantId`, `status`, `updatedAt`, `modifiedBy`)
	VALUES
		(NEW.`orderId`, NEW.`merchantId`, NEW.`status`, NEW.`updatedAt`, NEW.`modifiedBy`);
END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `OrderMerchant_AFTER_UPDATE`;
DELIMITER ;;
CREATE TRIGGER `OrderMerchant_AFTER_UPDATE` AFTER UPDATE ON `OrderMerchant` FOR EACH ROW BEGIN
	IF (NEW.`status` <> OLD.`status`) THEN
		INSERT INTO `KiselCommerce`.`OrderStatusLog`
			(`orderId`, `merchantId`, `status`, `updatedAt`, `modifiedBy`)
		VALUES
			(OLD.`orderId`, OLD.`merchantId`, NEW.`status`, NEW.`updatedAt`, NEW.`modifiedBy`);
    END IF;
END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `Product_BEFORE_INSERT`;
DELIMITER ;;
CREATE TRIGGER `Product_BEFORE_INSERT` BEFORE INSERT ON `Product` FOR EACH ROW BEGIN
	# Complete Denormalized Field
    # -- Declare variables
    DECLARE currencyCode VARCHAR(4); DECLARE productCategoryName VARCHAR(32);    
    # -- Currency
    SELECT `code` INTO @currencyCode FROM `KiselCommerce`.`Currency` WHERE `id` = NEW.`currencyId`;
    SET NEW.`currencyCode` = @currencyCode;
    # -- Product Category
    SELECT `name` INTO @productCategoryName FROM `KiselCommerce`.`ProductCategory` WHERE `id` = NEW.`productCategoryId`;
    SET NEW.`productCategoryName` = @productCategoryName;
END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `Product_AFTER_INSERT`;
DELIMITER ;;
CREATE TRIGGER `Product_AFTER_INSERT` AFTER INSERT ON `Product` FOR EACH ROW BEGIN
	INSERT INTO `KiselCommerce`.`ProductStockLog`
		(`productId`, `count`, `updatedAt`)
	VALUES
		(NEW.`id`, NEW.`stock`, NEW.`updatedAt`);
END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `Product_AFTER_UPDATE`;
DELIMITER ;;
CREATE TRIGGER `Product_AFTER_UPDATE` AFTER UPDATE ON `Product` FOR EACH ROW BEGIN
	IF (NEW.`stock` <> OLD.`stock`) THEN
		INSERT INTO `KiselCommerce`.`ProductStockLog`
			(`productId`, `count`, `updatedAt`)
		VALUES
			(OLD.`id`, NEW.`stock`, NEW.`updatedAt`);
	END IF;
END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `Review_AFTER_INSERT`;
DELIMITER ;;
CREATE TRIGGER `Review_AFTER_INSERT` AFTER INSERT ON `Review` FOR EACH ROW BEGIN
	DECLARE ratings integer;
    DECLARE reviewCount integer;

    SELECT `ratings`, `reviewCount` INTO @currentRatings, @currentReviewCount FROM `KiselCommerce`.`Product` WHERE `id` = NEW.`productId`;
    
    SET @reviewCount := @reviewCount + 1;
	SET @ratings := (@ratings + NEW.`rating`)/@reviewCount;

	UPDATE `KiselCommerce`.`Product` SET `ratings`=@ratings, `reviewCount`=@reviewCount WHERE `id`=NEW.`productId`;
END
;;
DELIMITER ;
SET FOREIGN_KEY_CHECKS=1;
