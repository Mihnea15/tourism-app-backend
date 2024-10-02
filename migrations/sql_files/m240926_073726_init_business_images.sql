CREATE TABLE `business_image` (
`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
`business_id` int(11) NOT NULL,
`image_name` varchar(255) NOT NULL,
`image_path` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
COMMIT;