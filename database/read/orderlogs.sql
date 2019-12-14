--
-- Структура таблицы `orderlogs`
--

CREATE TABLE `orderlogs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status` bigint(20) NOT NULL,
  `task_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `text` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `orderlogs`
--
ALTER TABLE `orderlogs`
  ADD PRIMARY KEY (`id`);