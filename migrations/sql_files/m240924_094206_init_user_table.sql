CREATE TABLE `user` (
`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
`first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
`last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
`email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
`password_hash` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
`status` int(11) NOT NULL DEFAULT '1' COMMENT '0 - inactive; 1 - active',
`profile_picture` varchar(255) NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;