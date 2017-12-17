CREATE DATABASE IF NOT EXISTS iurapp DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_unicode_ci;
## -- Crear tabla
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