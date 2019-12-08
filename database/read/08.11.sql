 DROP TABLE IF EXISTS `tasklogs`;
CREATE TABLE `tasklogs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `task_id` int(10) unsigned NOT NULL,
  `messange` bigint(20) NOT NULL DEFAULT '0',
  `log` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;