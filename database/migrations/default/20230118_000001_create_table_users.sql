CREATE TABLE IF NOT EXISTS `users` (
 `id` bigint(20) NOT NULL,
 `profileSurname` varchar(32) DEFAULT NULL,
 `profileName` varchar(32) DEFAULT NULL,
 `profileQualification` varchar(255) DEFAULT NULL,
 `profileAbout` varchar(2048) DEFAULT NULL,
 `profileMetaMaskId` varchar(255) DEFAULT NULL,
 `profileAvatar` varchar(255) DEFAULT NULL,
 `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `modifiedAt` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
