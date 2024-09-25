CREATE TABLE `favourite` (
`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
`business_id` int(11) NOT NULL,
`user_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;