/*
Navicat MySQL Data Transfer

Source Server         : laragon
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : iurapp_printer

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2017-12-16 17:44:50
*/
CREATE DATABASE IF NOT EXISTS iurapp_printer 
	DEFAULT CHARACTER SET utf8
	DEFAULT COLLATE utf8_unicode_ci;

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for iurapp_printer
-- ----------------------------
DROP TABLE IF EXISTS `iurapp_printer`;
CREATE TABLE `iurapp_printer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `user` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ip` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_pc` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of iurapp_printer
-- ----------------------------
