CREATE TABLE IF NOT EXISTS `city` (
`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
`name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
`latitude` int(11) NOT NULL,
`longitude` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;