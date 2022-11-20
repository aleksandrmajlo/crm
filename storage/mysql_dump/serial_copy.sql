DROP TABLE IF EXISTS `serial_copy`;
CREATE TABLE `serial_copy` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `serials` json NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
