SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for proxies
-- ----------------------------
DROP TABLE IF EXISTS `proxies`;
CREATE TABLE `proxies` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `proxy_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `proxy_type` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `use_encryption` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `use_compression` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `domain` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subdomain` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `locations` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `http_user` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `http_pwd` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `host_header_rewrite` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `headers` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remote_port` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `sk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `group` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `group_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastupdate` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for tokens
-- ----------------------------
DROP TABLE IF EXISTS `tokens`;
CREATE TABLE `tokens` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

