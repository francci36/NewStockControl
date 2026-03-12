-- MySQL dump 10.13  Distrib 8.0.41, for Win64 (x86_64)
--
-- Host: localhost    Database: newstockcontrol
-- ------------------------------------------------------
-- Server version	9.2.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart_items`
--

DROP TABLE IF EXISTS `cart_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_items`
--

LOCK TABLES `cart_items` WRITE;
/*!40000 ALTER TABLE `cart_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `cart_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carts`
--

DROP TABLE IF EXISTS `carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `carts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `carts_user_id_product_id_unique` (`user_id`,`product_id`),
  UNIQUE KEY `carts_session_id_product_id_unique` (`session_id`,`product_id`),
  KEY `carts_product_id_foreign` (`product_id`),
  KEY `carts_session_id_index` (`session_id`),
  CONSTRAINT `carts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carts`
--

LOCK TABLES `carts` WRITE;
/*!40000 ALTER TABLE `carts` DISABLE KEYS */;
INSERT INTO `carts` VALUES (1,NULL,'xWnTPkE6smphyE4LexKLSdCV4UZMsm4CgWPS1cOD',4,1,'2026-02-17 15:57:49','2026-02-17 15:57:49'),(5,NULL,'C7ayTI7j2WVIPY9OF67wRRJAtKJGkZLUN85Ob6gw',4,1,'2026-02-17 22:00:19','2026-02-17 22:00:19'),(6,NULL,'hYrF6yLjdbvEA555RFJhvNAYTZRw5lNSaGK2heee',2,1,'2026-02-17 22:42:38','2026-02-17 22:42:38'),(8,NULL,'eMGjcW1yhNa3P1JzjViYThoOpSCjNjo3Gm4wPrzz',4,1,'2026-02-18 13:03:47','2026-02-18 13:03:47'),(10,NULL,'c9fQR67UQfo16xbZOeK0nBaF3NJkX9mWLlXkYEOd',4,1,'2026-02-18 15:34:41','2026-02-18 15:34:41'),(13,NULL,'MsFwdv79w5sSbgLuXoURf4mTKPw8kfz8ixpnD6Uu',3,1,'2026-02-18 16:18:00','2026-02-18 16:18:00'),(19,NULL,'w8AlU55i8MBZCWrmPd9wlnqEfyAHE0yITTqfh5Mb',3,1,'2026-02-25 09:00:29','2026-02-25 09:00:29'),(28,1,NULL,5,1,'2026-03-02 21:20:02','2026-03-02 21:20:02');
/*!40000 ALTER TABLE `carts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`),
  KEY `categories_parent_id_foreign` (`parent_id`),
  CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'{\"de\":\"Hundefutter\",\"en\":\"Dog Food\",\"es\":\"Alimento para perros\",\"fr\":\"Alimentation pour chiens\",\"it\":\"Cibo per cani\",\"pt\":\"Alimentation pour chiens\",\"pt_BR\":\"Alimenta\\u00e7\\u00e3o para c\\u00e3es\"}','alimentation-chiens','Croquettes, pâtées et friandises pour chiens',NULL,1,'2026-02-18 18:41:19','2026-02-18 18:41:19',NULL),(2,'{\"de\":\"Katzenfutter\",\"en\":\"Cat Food\",\"es\":\"Alimento para gatos\",\"fr\":\"Alimentation pour chats\",\"it\":\"Cibo per gatti\",\"pt\":\"Alimentation pour chats\",\"pt_BR\":\"Alimenta\\u00e7\\u00e3o para gatos\"}','alimentation-chats','Croquettes, pâtées et friandises pour chats',NULL,1,'2026-02-18 18:41:19','2026-02-18 18:41:19',NULL),(3,'{\"de\":\"Zubeh\\u00f6r\",\"en\":\"Accessories\",\"es\":\"Accesorios\",\"fr\":\"Accessoires\",\"it\":\"Accessori\",\"pt\":\"Accessoires\",\"pt_BR\":\"Acess\\u00f3rios\"}','accessoires','Litières, gamelles, jouets',NULL,1,'2026-02-18 18:41:19','2026-02-18 18:41:19',NULL),(4,'{\"de\":\"Gesundheitspflege\",\"en\":\"Health Care\",\"es\":\"Cuidados y salud\",\"fr\":\"Soins et sant\\u00e9\",\"it\":\"Cura e salute\",\"pt\":\"Soins et sant\\u00e9\",\"pt_BR\":\"Cuidados e sa\\u00fade\"}','soins-sante','Produits vétérinaires, antiparasitaires',NULL,1,'2026-02-18 18:41:19','2026-02-18 18:41:19',NULL),(5,'{\"de\":\"Aquaristik\",\"en\":\"Aquariums\",\"es\":\"Acuariofilia\",\"fr\":\"Aquariophilie\",\"it\":\"Acquariofilia\",\"pt\":\"Aquariophilie\",\"pt_BR\":\"Aquariofilia\"}','aquariophilie','Aquariums, poissons, accessoires',NULL,1,'2026-02-18 18:41:19','2026-02-18 18:41:19',NULL),(8,'{\"de\":\"K\\u00fcchenprodukte\",\"en\":\"Kitchen Products\",\"es\":\"Productos de cocina\",\"fr\":\"Produit de cuisine\",\"it\":\"Prodotti da cucina\",\"pt\":\"Produit de cuisine\",\"pt_BR\":\"Produtos de cozinha\"}','produit-de-cuisine','Catégorie créée rapidement',NULL,1,'2026-02-23 11:09:41','2026-02-23 11:09:41',NULL),(9,'{\"de\":\"Garten\",\"en\":\"Garden\",\"es\":\"Jard\\u00edn\",\"fr\":\"Jardin\",\"it\":\"Giardino\",\"pt\":\"Jardin\",\"pt_BR\":\"Jardim\"}','jardin',NULL,NULL,1,'2026-03-03 17:15:07','2026-03-03 17:15:07',NULL),(10,'{\"fr\":\"Vins rouges\",\"en\":\"Red wines\"}','vins-rouges',NULL,NULL,1,'2026-03-06 21:57:46','2026-03-07 10:24:15',14),(11,'{\"fr\":\"Vins blancs\",\"en\":\"White wines\"}','vins-blancs',NULL,NULL,1,'2026-03-06 21:57:46','2026-03-07 10:24:15',14),(12,'{\"fr\":\"Champagnes\",\"en\":\"Champagnes\"}','champagnes',NULL,NULL,1,'2026-03-06 21:57:46','2026-03-07 10:24:15',14),(13,'{\"fr\":\"Spiritueux\",\"en\":\"Spirits\"}','spiritueux',NULL,NULL,1,'2026-03-06 21:57:46','2026-03-07 10:24:15',14),(14,'{\"fr\":\"Vins\",\"en\":\"Wines\",\"pt_BR\":\"Vinhos\",\"es\":\"Vinos\",\"de\":\"Weine\",\"it\":\"Vini\"}','vins',NULL,NULL,1,'2026-03-07 10:23:56','2026-03-07 10:23:56',NULL);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `commandes`
--

DROP TABLE IF EXISTS `commandes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `commandes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `statut` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'En cours',
  `supplier_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `commandes_supplier_id_foreign` (`supplier_id`),
  KEY `commandes_product_id_foreign` (`product_id`),
  KEY `commandes_user_id_foreign` (`user_id`),
  CONSTRAINT `commandes_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `commandes_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `commandes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commandes`
--

LOCK TABLES `commandes` WRITE;
/*!40000 ALTER TABLE `commandes` DISABLE KEYS */;
/*!40000 ALTER TABLE `commandes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `commissions`
--

DROP TABLE IF EXISTS `commissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `commissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint unsigned NOT NULL,
  `order_id` bigint unsigned NOT NULL,
  `order_item_id` bigint unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `rate` decimal(5,2) NOT NULL,
  `status` enum('pending','paid','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `paid_at` timestamp NULL DEFAULT NULL,
  `payment_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `commissions_order_item_id_foreign` (`order_item_id`),
  KEY `commissions_vendor_id_status_index` (`vendor_id`,`status`),
  KEY `commissions_order_id_index` (`order_id`),
  CONSTRAINT `commissions_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `commissions_order_item_id_foreign` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `commissions_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commissions`
--

LOCK TABLES `commissions` WRITE;
/*!40000 ALTER TABLE `commissions` DISABLE KEYS */;
INSERT INTO `commissions` VALUES (1,2,31,33,5.60,10.00,'pending',NULL,NULL,'2026-03-06 17:37:19','2026-03-06 17:37:19');
/*!40000 ALTER TABLE `commissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `delivery_webhooks`
--

DROP TABLE IF EXISTS `delivery_webhooks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `delivery_webhooks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tracking_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_id` bigint unsigned DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` json NOT NULL,
  `raw_data` json DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'received',
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `delivery_webhooks_order_id_foreign` (`order_id`),
  KEY `delivery_webhooks_tracking_number_index` (`tracking_number`),
  KEY `delivery_webhooks_status_index` (`status`),
  CONSTRAINT `delivery_webhooks_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `delivery_webhooks`
--

LOCK TABLES `delivery_webhooks` WRITE;
/*!40000 ALTER TABLE `delivery_webhooks` DISABLE KEYS */;
/*!40000 ALTER TABLE `delivery_webhooks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `financial_transactions`
--

DROP TABLE IF EXISTS `financial_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `financial_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('income','expense','commission','refund') COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` enum('sale','purchase','fee','commission','withdrawal','adjustment') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total` decimal(10,2) NOT NULL,
  `status` enum('pending','completed','cancelled','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_method` enum('card','cash','bank_transfer','check','paypal','stripe','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `transaction_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` bigint unsigned DEFAULT NULL,
  `order_id` bigint unsigned DEFAULT NULL,
  `vendor_id` bigint unsigned DEFAULT NULL,
  `supplier_id` bigint unsigned DEFAULT NULL,
  `payment_proof` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `financial_transactions_reference_unique` (`reference`),
  KEY `financial_transactions_user_id_foreign` (`user_id`),
  KEY `financial_transactions_order_id_foreign` (`order_id`),
  KEY `financial_transactions_vendor_id_foreign` (`vendor_id`),
  KEY `financial_transactions_supplier_id_foreign` (`supplier_id`),
  KEY `financial_transactions_type_status_index` (`type`,`status`),
  KEY `financial_transactions_transaction_date_index` (`transaction_date`),
  KEY `financial_transactions_reference_index` (`reference`),
  CONSTRAINT `financial_transactions_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `financial_transactions_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `financial_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `financial_transactions_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `financial_transactions`
--

LOCK TABLES `financial_transactions` WRITE;
/*!40000 ALTER TABLE `financial_transactions` DISABLE KEYS */;
INSERT INTO `financial_transactions` VALUES (1,'PURCHASE-0001','expense','purchase',0.00,0.00,0.00,'completed','bank_transfer','Achat fournisseur #1 - LeroyMerlu','2026-02-17 12:29:50',1,1,NULL,1,NULL,NULL,'2026-03-06 17:11:55','2026-03-06 17:11:55'),(2,'PURCHASE-0002','expense','purchase',140.00,28.00,168.00,'completed','bank_transfer','Achat fournisseur #2 - AnimalLand','2026-02-17 12:34:36',1,2,NULL,2,NULL,NULL,'2026-03-06 17:11:55','2026-03-06 17:11:55'),(3,'PURCHASE-0003','expense','purchase',2281.67,456.33,2738.00,'completed','bank_transfer','Achat fournisseur #3 - LeroyMerlu','2026-02-17 13:20:20',1,3,NULL,1,NULL,NULL,'2026-03-06 17:11:55','2026-03-06 17:11:55'),(4,'SALE-0004','income','sale',204.17,40.83,245.00,'completed','card','Vente client #4 - chico','2026-02-17 16:04:16',2,4,NULL,NULL,NULL,NULL,'2026-03-06 17:11:55','2026-03-06 17:11:55'),(5,'SALE-0005','income','sale',204.17,40.83,245.00,'completed','card','Vente client #5 - chico','2026-02-17 16:05:51',2,5,NULL,NULL,NULL,NULL,'2026-03-06 17:11:55','2026-03-06 17:11:55'),(6,'SALE-0006','income','sale',17.50,3.50,21.00,'completed','card','Vente client #6 - chico','2026-02-17 16:50:53',2,6,NULL,NULL,NULL,NULL,'2026-03-06 17:11:55','2026-03-06 17:11:55'),(7,'SALE-0007','income','sale',26.67,5.33,32.00,'completed','card','Vente client #7 - chico','2026-02-17 22:43:31',2,7,NULL,NULL,NULL,NULL,'2026-03-06 17:11:55','2026-03-06 17:11:55'),(8,'PURCHASE-0010','expense','purchase',52.50,10.50,63.00,'completed','bank_transfer','Achat fournisseur #10 - AnimalLand','2026-02-18 10:36:29',1,10,NULL,2,NULL,NULL,'2026-03-06 17:11:55','2026-03-06 17:11:55'),(9,'SALE-0016','income','sale',17.50,3.50,21.00,'completed','card','Vente client #16 - chico','2026-02-18 14:42:18',2,16,NULL,NULL,NULL,NULL,'2026-03-06 17:11:55','2026-03-06 17:11:55'),(10,'SALE-0017','income','sale',26.67,5.33,32.00,'completed','card','Vente client #17 - Raphael','2026-02-18 15:42:00',3,17,NULL,NULL,NULL,NULL,'2026-03-06 17:11:55','2026-03-06 17:11:55'),(11,'SALE-0018','income','sale',26.67,5.33,32.00,'completed','card','Vente client #18 - Raphael','2026-02-18 15:47:11',3,18,NULL,NULL,NULL,NULL,'2026-03-06 17:11:55','2026-03-06 17:11:55'),(12,'PURCHASE-0019','expense','purchase',4250.00,850.00,5100.00,'completed','bank_transfer','Achat fournisseur #19 - LeroyMerlu','2026-02-23 11:11:32',1,19,NULL,1,NULL,NULL,'2026-03-06 17:11:55','2026-03-06 17:11:55'),(13,'SALE-0020','income','sale',49.83,9.97,59.80,'completed','card','Vente client #20 - chico','2026-02-23 11:41:08',2,20,NULL,NULL,NULL,NULL,'2026-03-06 17:11:55','2026-03-06 17:11:55'),(14,'SALE-0021','income','sale',10.00,2.00,12.00,'completed','card','Vente client #21 - chico','2026-02-23 12:18:02',2,21,NULL,NULL,NULL,NULL,'2026-03-06 17:11:55','2026-03-06 17:11:55'),(15,'SALE-0022','income','sale',33.32,6.66,39.98,'completed','card','Vente client #22 - Client Test','2026-02-23 13:24:04',1,22,NULL,NULL,NULL,NULL,'2026-03-06 17:11:55','2026-03-06 17:11:55'),(16,'SALE-0023','income','sale',26.67,5.33,32.00,'completed','card','Vente client #23 - chico','2026-02-25 14:51:10',2,23,NULL,NULL,NULL,NULL,'2026-03-06 17:11:55','2026-03-06 17:11:55'),(17,'SALE-0024','income','sale',39.58,7.92,47.50,'completed','card','Vente client #24 - chico','2026-02-25 14:56:24',2,24,NULL,NULL,NULL,NULL,'2026-03-06 17:11:55','2026-03-06 17:11:55'),(18,'SALE-0025','income','sale',108.33,21.67,129.99,'completed','card','Vente client #25 - chico','2026-02-25 14:57:25',2,25,NULL,NULL,NULL,NULL,'2026-03-06 17:11:55','2026-03-06 17:11:55'),(19,'SALE-0027','income','sale',10.00,2.00,12.00,'completed','card','Vente client #27 - chico','2026-02-25 15:22:22',2,27,NULL,NULL,NULL,NULL,'2026-03-06 17:11:55','2026-03-06 17:11:55'),(20,'PURCHASE-0028','expense','purchase',204.17,40.83,245.00,'completed','bank_transfer','Achat fournisseur #28 - LeroyMerlu','2026-02-27 15:16:32',1,28,NULL,1,NULL,NULL,'2026-03-06 17:11:55','2026-03-06 17:11:55'),(21,'SALE-0029','income','sale',64.92,12.98,77.90,'completed','card','Vente client #29 - Admin','2026-02-27 17:33:03',1,29,NULL,NULL,NULL,NULL,'2026-03-06 17:11:55','2026-03-06 17:11:55'),(22,'SALE-0030','income','sale',10.00,2.00,12.00,'completed','card','Vente client #30 - chico','2026-02-28 09:12:47',2,30,NULL,NULL,NULL,NULL,'2026-03-06 17:11:55','2026-03-06 17:11:55'),(23,'SALE-0031','income','sale',46.67,9.33,56.00,'completed','card','Vente client #31 - chico','2026-03-06 17:37:20',2,31,NULL,NULL,NULL,NULL,'2026-03-06 17:37:20','2026-03-06 17:37:20');
/*!40000 ALTER TABLE `financial_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
INSERT INTO `jobs` VALUES (1,'default','{\"uuid\":\"3c3af1c4-5f85-4513-b716-e6f5279f2130\",\"displayName\":\"App\\\\Events\\\\OrderShipped\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:23:\\\"App\\\\Events\\\\OrderShipped\\\":2:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:22;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:6:\\\"vendor\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:17:\\\"App\\\\Models\\\\Vendor\\\";s:2:\\\"id\\\";i:2;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"}}',0,NULL,1771857468,1771857468),(2,'default','{\"uuid\":\"506cccf2-6ea4-4768-a7c2-49d19d7949c1\",\"displayName\":\"App\\\\Events\\\\OrderShipped\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:23:\\\"App\\\\Events\\\\OrderShipped\\\":2:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:22;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:6:\\\"vendor\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:17:\\\"App\\\\Models\\\\Vendor\\\";s:2:\\\"id\\\";i:2;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"}}',0,NULL,1771857884,1771857884),(3,'default','{\"uuid\":\"e57088a6-6b62-40ce-8220-16c3f4a18d4a\",\"displayName\":\"App\\\\Events\\\\OrderShipped\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:23:\\\"App\\\\Events\\\\OrderShipped\\\":2:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:22;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:6:\\\"vendor\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:17:\\\"App\\\\Models\\\\Vendor\\\";s:2:\\\"id\\\";i:2;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"}}',0,NULL,1771858099,1771858099);
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2024_11_09_144623_create_suppliers_table',1),(5,'2024_11_09_144624_create_products_table',1),(6,'2024_11_09_153455_create_categories_table',1),(7,'2024_11_09_153512_create_orders_table',1),(8,'2024_11_12_114108_create_commandes_table',1),(9,'2025_01_14_140429_add_user_id_to_commandes_table',1),(10,'2025_01_15_105828_drop_product_column_from_commandes_table',1),(11,'2025_01_20_152354_add_stock_to_products_table',1),(12,'2025_02_05_085415_add_email_and_telephone_to_suppliers_table',1),(13,'2025_02_07_204435_create_transactions_table',1),(14,'2025_02_07_211259_add_stock_threshold_to_products_table',1),(15,'2025_03_03_121317_create_order_items_table',1),(16,'2025_03_07_103000_create_stocks_table',1),(17,'2025_03_07_110130_update_stocks_table',1),(18,'2025_03_07_110618_update_stocks_table_structure',1),(19,'2025_03_07_122333_remove_stock_from_products_table',1),(20,'2025_03_09_224523_add_price_to_transactions_table',1),(21,'2025_03_14_152228_update_products_table_add_default_name',1),(22,'2025_03_14_153129_update_products_table_add_default_name',1),(23,'2025_03_16_091527_add_price_to_order_items_table',1),(24,'2025_03_16_112406_update_transactions_table_foreign_key',1),(25,'2025_03_17_090407_update_stock_threshold_default_in_products_table',1),(26,'2025_03_17_092656_add_foreign_key_to_transactions_table',1),(27,'2025_03_17_130840_create_reports_table',1),(28,'2025_03_17_133711_add_summary_to_reports_table',1),(29,'2025_03_18_084941_add_report_id_to_transactions_table',1),(30,'2025_03_18_090453_add_report_id_to_stocks',1),(31,'2025_03_20_131852_add_total_amount_to_orders_table',1),(32,'2025_03_20_173407_add_profile_photo_url_to_users_table',1),(33,'2025_03_25_122840_add_role_to_users_table',1),(34,'2026_02_17_132221_remove_product_id_from_orders_table',2),(35,'2026_02_17_132720_remove_quantity_from_orders_table',3),(36,'2026_02_17_160425_create_carts_table',4),(37,'2026_02_17_160441_create_cart_items_table',4),(38,'2026_02_17_164845_add_shipping_fields_to_orders_table',5),(39,'2026_02_17_165638_create_carts_table',6),(40,'2026_02_17_233706_add_type_to_orders_table',7),(41,'2026_02_18_102839_make_supplier_id_nullable_in_orders',8),(42,'2026_02_18_120611_add_order_id_and_user_id_to_transactions',9),(43,'2026_02_18_153159_create_payments_table',10),(44,'2026_02_18_153351_create_payments_table',11),(45,'2026_02_18_170529_create_order_status_history_table',12),(46,'2026_02_18_194019_add_category_and_image_to_products_table',13),(47,'2026_02_20_085900_create_vendors_table',14),(48,'2026_02_20_090459_add_vendor_id_to_products_table',15),(49,'2026_02_20_095501_create_notifications_table',16),(50,'2026_02_20_111633_make_supplier_id_nullable_in_products',17),(51,'2026_02_20_112230_make_supplier_id_nullable_in_products',18),(52,'2026_02_23_133608_add_tracking_fields_to_orders_table',19),(53,'2026_02_23_133731_create_vendor_webhooks_table',19),(54,'2026_02_23_143555_add_shipping_fields_to_order_items_table',20),(55,'2026_02_25_112659_create_category_translations_table',21),(56,'2026_02_25_112710_create_product_translations_table',21),(57,'2026_02_25_122043_add_is_active_to_suppliers_table',22),(58,'2026_02_27_104051_add_vendor_id_to_order_items_table',23),(59,'2026_02_27_104927_add_address_fields_to_vendors_table',24),(60,'2026_02_27_122942_add_address_fields_to_suppliers_table',25),(61,'2026_02_28_133248_create_delivery_webhooks_table',26),(62,'2026_03_03_121017_add_sku_to_products_table',27),(63,'2026_03_05_124449_add_json_columns_to_products',28),(64,'2026_03_05_124640_rename_json_columns_in_products',29),(65,'2026_03_06_141006_create_financial_transactions_table',30),(66,'2026_03_06_141024_create_commissions_table',30),(67,'2026_03_06_141052_create_payouts_table',30),(68,'2026_03_07_105159_add_parent_id_to_categories_table',31),(69,'2026_03_07_110029_merge_category_translations_into_json',31);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES ('6b70fda3-7a5d-4d49-9e75-d11bab102d96','vendor-request','App\\Models\\User',1,'{\"vendor_id\":2,\"shop_name\":\"JeanDelaCave\",\"user_name\":\"jean\",\"user_email\":\"jean@gmail.com\",\"message\":\"Nouvelle demande vendeur : JeanDelaCave par jean\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/vendors\\/2\",\"icon\":\"fa-store\",\"color\":\"warning\",\"time\":\"2026-02-20 10:23:52\"}','2026-02-20 09:36:45','2026-02-20 09:23:52','2026-02-20 09:36:45'),('9b981914-4e3d-4c21-af8e-75792e467fff','vendor-request','App\\Models\\User',1,'{\"vendor_id\":3,\"shop_name\":\"JeanneduBarril\",\"user_name\":\"jeanneduBarril\",\"user_email\":\"jeanne@gmail.com\",\"message\":\"Nouvelle demande vendeur : JeanneduBarril par jeanneduBarril\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/vendors\\/3\",\"icon\":\"fa-store\",\"color\":\"warning\",\"time\":\"2026-03-03 18:33:25\"}','2026-03-03 17:44:54','2026-03-03 17:33:25','2026-03-03 17:44:54');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `vendor_id` bigint unsigned DEFAULT NULL,
  `quantity` int NOT NULL,
  `price` decimal(8,2) DEFAULT NULL,
  `shipped_at` timestamp NULL DEFAULT NULL,
  `tracking_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `carrier` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `delivered_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  KEY `order_items_vendor_id_foreign` (`vendor_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,2,4,NULL,8,21.00,NULL,NULL,NULL,'pending',NULL,'2026-02-17 12:34:36','2026-02-17 12:34:36'),(2,3,2,NULL,9,32.00,NULL,NULL,NULL,'pending',NULL,'2026-02-17 13:20:20','2026-02-17 13:20:20'),(3,3,3,NULL,10,245.00,NULL,NULL,NULL,'pending',NULL,'2026-02-17 13:20:20','2026-02-17 13:20:20'),(4,4,3,NULL,1,245.00,NULL,NULL,NULL,'pending',NULL,'2026-02-17 16:04:16','2026-02-17 16:04:16'),(5,5,3,NULL,1,245.00,NULL,NULL,NULL,'pending',NULL,'2026-02-17 16:05:51','2026-02-17 16:05:51'),(6,6,4,NULL,1,21.00,NULL,NULL,NULL,'pending',NULL,'2026-02-17 16:50:53','2026-02-17 16:50:53'),(7,7,2,NULL,1,32.00,NULL,NULL,NULL,'pending',NULL,'2026-02-17 22:43:31','2026-02-17 22:43:31'),(10,10,4,NULL,3,21.00,NULL,NULL,NULL,'pending',NULL,'2026-02-18 10:36:29','2026-02-18 10:36:29'),(16,16,4,NULL,1,21.00,NULL,NULL,NULL,'pending',NULL,'2026-02-18 14:42:18','2026-02-18 14:42:18'),(17,17,2,NULL,1,32.00,NULL,NULL,NULL,'pending',NULL,'2026-02-18 15:42:00','2026-02-18 15:42:00'),(18,18,2,NULL,1,32.00,NULL,NULL,NULL,'pending',NULL,'2026-02-18 15:47:11','2026-02-18 15:47:11'),(19,19,21,NULL,17,300.00,NULL,NULL,NULL,'pending',NULL,'2026-02-23 11:11:32','2026-02-23 11:11:32'),(20,20,13,NULL,2,29.90,NULL,NULL,NULL,'pending',NULL,'2026-02-23 11:41:08','2026-02-23 11:41:08'),(21,21,15,2,1,12.00,NULL,NULL,NULL,'pending',NULL,'2026-02-23 12:18:02','2026-02-28 11:07:54'),(23,23,2,NULL,1,32.00,NULL,NULL,NULL,'pending',NULL,'2026-02-25 14:51:10','2026-02-25 14:51:10'),(24,24,6,NULL,1,47.50,NULL,NULL,NULL,'pending',NULL,'2026-02-25 14:56:24','2026-02-25 14:56:24'),(25,25,17,NULL,1,129.99,NULL,NULL,NULL,'pending',NULL,'2026-02-25 14:57:25','2026-02-25 14:57:25'),(27,27,15,2,1,12.00,NULL,NULL,NULL,'pending',NULL,'2026-02-25 15:22:22','2026-02-28 11:07:54'),(29,28,3,NULL,1,245.00,NULL,NULL,NULL,'pending',NULL,'2026-02-27 15:23:24','2026-02-27 15:23:24'),(30,29,13,NULL,1,29.90,NULL,NULL,NULL,'pending',NULL,'2026-02-27 17:33:03','2026-02-27 17:33:03'),(31,29,15,2,4,12.00,NULL,NULL,NULL,'pending',NULL,'2026-02-27 17:33:03','2026-02-28 11:07:54'),(32,30,15,2,1,12.00,NULL,NULL,NULL,'pending',NULL,'2026-02-28 09:12:47','2026-02-28 11:07:54'),(33,31,15,2,2,28.00,NULL,NULL,NULL,'pending',NULL,'2026-03-06 17:37:00','2026-03-06 17:37:00');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_status_history`
--

DROP TABLE IF EXISTS `order_status_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_status_history` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_status_history_user_id_foreign` (`user_id`),
  KEY `order_status_history_order_id_created_at_index` (`order_id`,`created_at`),
  CONSTRAINT `order_status_history_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_status_history_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_status_history`
--

LOCK TABLES `order_status_history` WRITE;
/*!40000 ALTER TABLE `order_status_history` DISABLE KEYS */;
INSERT INTO `order_status_history` VALUES (1,18,'shipped','Commande expédiée le 18/02/2026',1,'2026-02-18 19:30:13','2026-02-18 19:30:13'),(2,18,'delivered','Votre colis a été livré cet après midi.',1,'2026-02-18 19:33:57','2026-02-18 19:33:57'),(3,19,'arrived','La commande vient d\'être réceptionné.',1,'2026-02-23 11:12:26','2026-02-23 11:12:26'),(4,16,'shipped','Par un souci de gestion des produits en stock, nous avons mit un peu de temps à expédier votre produit.',1,'2026-02-23 11:48:59','2026-02-23 11:48:59'),(5,4,'shipped','votre commande vient d\'être expédié.',1,'2026-02-23 11:56:23','2026-02-23 11:56:23'),(6,17,'shipped','Commande expédiée.',1,'2026-02-23 11:57:07','2026-02-23 11:57:07'),(7,20,'shipped','commande expédiée',1,'2026-02-23 11:57:36','2026-02-23 11:57:36'),(8,7,'shipped','commande expédié',1,'2026-02-23 11:58:07','2026-02-23 11:58:07'),(9,4,'delivered','Le livreur vient de passer chez vous.',1,'2026-02-23 11:58:45','2026-02-23 11:58:45'),(10,16,'delivered','Le livreur vient de passer chez vous.',1,'2026-02-23 11:59:23','2026-02-23 11:59:23'),(11,17,'delivered','Commande livrée.',1,'2026-02-23 11:59:56','2026-02-23 11:59:56'),(12,23,'pending','messages.order_created',2,'2026-02-25 14:51:10','2026-02-25 14:51:10'),(13,23,'processing','messages.order_processing',2,'2026-02-25 14:51:10','2026-02-25 14:51:10'),(14,24,'pending','messages.order_created',2,'2026-02-25 14:56:24','2026-02-25 14:56:24'),(15,24,'processing','messages.order_processing',2,'2026-02-25 14:56:24','2026-02-25 14:56:24'),(16,25,'pending','messages.order_created',2,'2026-02-25 14:57:25','2026-02-25 14:57:25'),(17,25,'processing','messages.order_processing',2,'2026-02-25 14:57:25','2026-02-25 14:57:25'),(19,27,'pending','messages.order_created',2,'2026-02-25 15:22:22','2026-02-25 15:22:22'),(20,27,'paid','messages.payment_received',2,'2026-02-25 15:22:24','2026-02-25 15:22:24'),(21,25,'shipped','Votre commande vient d\'être livrée.',1,'2026-02-25 17:39:53','2026-02-25 17:39:53'),(22,27,'processing','Mise à jour du statut',1,'2026-02-25 17:55:19','2026-02-25 17:55:19'),(23,28,'pending','Commande modifiée',1,'2026-02-27 15:23:24','2026-02-27 15:23:24'),(24,29,'pending','Order created',1,'2026-02-27 17:33:03','2026-02-27 17:33:03'),(25,29,'paid','Payment received',1,'2026-02-27 17:33:04','2026-02-27 17:33:04'),(26,30,'pending','Commande créée',2,'2026-02-28 09:12:47','2026-02-28 09:12:47'),(27,30,'paid','Paiement reçu',2,'2026-02-28 09:12:48','2026-02-28 09:12:48'),(28,30,'processing','Mise à jour du statut',1,'2026-02-28 09:23:05','2026-02-28 09:23:05'),(29,30,'shipped','Commande expédiée par le vendeur',4,'2026-02-28 11:22:30','2026-02-28 11:22:30'),(30,30,'delivered','commande arrivée avec succès',1,'2026-02-28 12:50:30','2026-02-28 12:50:30'),(31,27,'shipped','Commande expédiée par le vendeur',4,'2026-03-05 08:57:40','2026-03-05 08:57:40'),(32,31,'shipped','Commande expédiée par le vendeur',4,'2026-03-06 21:43:14','2026-03-06 21:43:14');
/*!40000 ALTER TABLE `order_status_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `supplier_id` bigint unsigned DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'supplier',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tracking_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `carrier` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipped_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `tracking_events` json DEFAULT NULL,
  `shipping_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address` text COLLATE utf8mb4_unicode_ci,
  `shipping_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_zip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `client_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_reference_unique` (`reference`),
  KEY `orders_user_id_foreign` (`user_id`),
  KEY `orders_supplier_id_foreign` (`supplier_id`),
  KEY `orders_client_id_foreign` (`client_id`),
  CONSTRAINT `orders_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`),
  CONSTRAINT `orders_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,NULL,1,1,'supplier','arrived',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-02-17','2026-02-17 12:29:50','2026-02-18 10:57:11',0.00,NULL),(2,NULL,1,2,'supplier','arrived',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-02-17','2026-02-17 12:34:36','2026-02-18 10:57:11',168.00,NULL),(3,NULL,1,1,'supplier','arrived',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-02-17','2026-02-17 13:20:20','2026-02-18 10:57:11',2738.00,NULL),(4,NULL,2,NULL,'client','delivered',NULL,NULL,NULL,NULL,NULL,'chico','1 rue de la Chappe','Bourges','18000','France','0663417598','card','2026-02-17','2026-02-17 16:04:16','2026-02-23 11:58:45',245.00,2),(5,NULL,2,NULL,'client','arrived',NULL,NULL,NULL,NULL,NULL,'chico','1 rue de la Chappe','Bourges','18000','France','0663417598','card','2026-02-17','2026-02-17 16:05:51','2026-02-18 10:57:11',245.00,2),(6,NULL,2,NULL,'client','arrived',NULL,NULL,NULL,NULL,NULL,'chico','1 rue de la Chappe','Bourges','18000','France','0663417598','card','2026-02-17','2026-02-17 16:50:53','2026-02-18 10:57:11',21.00,2),(7,NULL,2,NULL,'client','shipped',NULL,NULL,NULL,NULL,NULL,'chico','1 rue de la Chappe','Bourges','18000','France','0663417598','card','2026-02-17','2026-02-17 22:43:31','2026-02-23 11:58:07',32.00,2),(10,NULL,1,2,'supplier','arrived',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-02-18','2026-02-18 10:36:29','2026-02-18 10:57:11',63.00,NULL),(16,NULL,2,NULL,'client','delivered',NULL,NULL,NULL,NULL,NULL,'chico','1 rue de la Chappe','Bourges','18000','FR','0663417598','card','2026-02-18','2026-02-18 14:42:18','2026-02-23 11:59:23',21.00,2),(17,NULL,3,NULL,'client','delivered',NULL,NULL,NULL,NULL,NULL,'Raphael','1 rue de la Chappe','Bourges','18000','FR','0663417598','card','2026-02-18','2026-02-18 15:42:00','2026-02-23 11:59:56',32.00,3),(18,NULL,3,NULL,'client','delivered',NULL,NULL,NULL,NULL,NULL,'Raphael','1 rue de la Chappe','Bourges','18000','FR','0663417598','card','2026-02-18','2026-02-18 15:47:11','2026-02-18 19:33:57',32.00,3),(19,NULL,1,1,'supplier','arrived',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-02-23','2026-02-23 11:11:32','2026-02-23 11:12:26',5100.00,NULL),(20,NULL,2,NULL,'client','shipped',NULL,NULL,NULL,NULL,NULL,'chico','1 rue de la Chappe','Bourges','18000','FR','0663417598','card','2026-02-23','2026-02-23 11:41:08','2026-02-23 11:57:36',59.80,2),(21,NULL,2,NULL,'client','pending',NULL,NULL,NULL,NULL,NULL,'chico','1 rue de la Chappe','Bourges','18000','FR','0663417598','card','2026-02-23','2026-02-23 12:18:02','2026-02-23 12:18:02',12.00,2),(22,NULL,1,NULL,'client','shipped','1Z999AA10123456784','colissimo','2026-02-23 12:55:34',NULL,'[{\"event\": \"shipped\", \"carrier\": \"colissimo\", \"metadata\": [], \"shipped_at\": \"2026-02-23 13:55:34\", \"tracking_number\": \"1Z999AA10123456784\", \"estimated_delivery\": null}]','Client Test','123 Rue de Test','Paris','75001','France','0123456789','card','2026-02-23','2026-02-23 13:24:04','2026-02-23 13:48:19',39.98,NULL),(23,NULL,2,NULL,'client','processing',NULL,NULL,NULL,NULL,NULL,'chico','1 rue de la Chappe','Bourges','18000','FR','0663417598','card','2026-02-25','2026-02-25 14:51:10','2026-02-25 14:51:10',32.00,2),(24,NULL,2,NULL,'client','processing',NULL,NULL,NULL,NULL,NULL,'chico','1 rue de la Chappe','Bourges','18000','FR','0663417598','card','2026-02-25','2026-02-25 14:56:24','2026-02-25 14:56:24',47.50,2),(25,NULL,2,NULL,'client','shipped',NULL,NULL,NULL,NULL,NULL,'chico','1 rue de la Chappe','Bourges','18000','FR','0663417598','card','2026-02-25','2026-02-25 14:57:25','2026-02-25 17:39:53',129.99,2),(27,NULL,2,NULL,'client','shipped','LX569745123FR','colissimo','2026-03-05 08:57:40',NULL,NULL,'chico','1 rue de la Chappe','Bourges','18000','FR','0663417598','card','2026-02-25','2026-02-25 15:22:22','2026-03-05 08:57:40',12.00,2),(28,NULL,1,1,'supplier','pending',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-02-27','2026-02-27 15:16:32','2026-02-27 15:23:24',245.00,NULL),(29,NULL,1,NULL,'client','paid',NULL,NULL,NULL,NULL,NULL,'Admin','1 rue de la Chappe','Bourges','18000','FR','0663417598','card','2026-02-27','2026-02-27 17:33:03','2026-02-27 17:33:04',77.90,1),(30,NULL,2,NULL,'client','delivered','1235466984788','colissimo','2026-02-28 11:22:30',NULL,NULL,'chico','1 rue de la Chappe','Bourges','18000','FR','0663417598','card','2026-02-28','2026-02-28 09:12:47','2026-02-28 12:50:30',12.00,2),(31,NULL,2,NULL,'client','shipped','LX5697455323FR','colissimo','2026-03-06 21:43:14',NULL,NULL,'chico','1 rue de la Chappe','Bourges','18000','France','0663417598','card','2026-03-06','2026-03-06 17:37:00','2026-03-06 21:43:14',56.00,2);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'EUR',
  `payer_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payer_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_details` json DEFAULT NULL,
  `refund_amount` decimal(10,2) DEFAULT NULL,
  `refund_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `refunded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_order_id_foreign` (`order_id`),
  KEY `payments_user_id_foreign` (`user_id`),
  KEY `payments_status_payment_method_index` (`status`,`payment_method`),
  KEY `payments_payment_id_index` (`payment_id`),
  CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (1,16,2,'stripe','pi_3T2CmuEu99Yu9rVi0XsIotqZ','completed',21.00,'eur','chico@gmail.com','chico','{\"status\": \"succeeded\", \"payment_method\": \"pm_1T2CmvEu99Yu9rVithzvH5om\"}',NULL,NULL,NULL,'2026-02-18 14:42:19','2026-02-18 14:42:19'),(2,17,3,'stripe','pi_3T2Dp5Eu99Yu9rVi0OGDwvr0','completed',32.00,'eur','untigre1986@outlook.fr','Raphael','{\"status\": \"succeeded\", \"payment_method\": \"pm_1T2Dp5Eu99Yu9rViNnj7g0In\"}',NULL,NULL,NULL,'2026-02-18 15:42:01','2026-02-18 15:42:01'),(3,18,3,'stripe','pi_3T2Du6Eu99Yu9rVi1SQgahRD','completed',32.00,'eur','untigre1986@outlook.fr','Raphael','{\"status\": \"succeeded\", \"payment_method\": \"pm_1T2Du7Eu99Yu9rVi8IH4ysE6\"}',NULL,NULL,NULL,'2026-02-18 15:47:12','2026-02-18 15:47:12'),(4,20,2,'stripe','pi_3T3yRjEu99Yu9rVi1gIjFQZ4','completed',59.80,'eur','chico@gmail.com','chico','{\"status\": \"succeeded\", \"payment_method\": \"pm_1T3yRkEu99Yu9rViH3l8u5oR\"}',NULL,NULL,NULL,'2026-02-23 11:41:09','2026-02-23 11:41:09'),(5,21,2,'stripe','pi_3T3z1REu99Yu9rVi0i8eoPcZ','completed',12.00,'eur','chico@gmail.com','chico','{\"status\": \"succeeded\", \"payment_method\": \"pm_1T3z1REu99Yu9rVihbfQmIsj\"}',NULL,NULL,NULL,'2026-02-23 12:18:03','2026-02-23 12:18:03'),(7,27,2,'stripe','pi_3T4konEu99Yu9rVi0rLXgujF','completed',12.00,'eur','chico@gmail.com','chico','{\"status\": \"succeeded\", \"payment_method\": \"pm_1T4kooEu99Yu9rViVyhGSdZu\"}',NULL,NULL,NULL,'2026-02-25 15:22:24','2026-02-25 15:22:24'),(8,29,1,'stripe','pi_3T5VqTEu99Yu9rVi1KW2KWya','completed',77.90,'eur','admin@example.com','Admin','{\"status\": \"succeeded\", \"payment_method\": \"pm_1T5VqTEu99Yu9rVibZKwHhAJ\"}',NULL,NULL,NULL,'2026-02-27 17:33:04','2026-02-27 17:33:04'),(9,30,2,'stripe','pi_3T5kVtEu99Yu9rVi0UU5OFsm','completed',12.00,'eur','chico@gmail.com','chico','{\"status\": \"succeeded\", \"payment_method\": \"pm_1T5kVtEu99Yu9rViHh3qVT9R\"}',NULL,NULL,NULL,'2026-02-28 09:12:48','2026-02-28 09:12:48');
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payouts`
--

DROP TABLE IF EXISTS `payouts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payouts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint unsigned NOT NULL,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `commission_ids` json NOT NULL,
  `status` enum('pending','processing','completed','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_details` json DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payouts_reference_unique` (`reference`),
  KEY `payouts_vendor_id_status_index` (`vendor_id`,`status`),
  KEY `payouts_reference_index` (`reference`),
  CONSTRAINT `payouts_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payouts`
--

LOCK TABLES `payouts` WRITE;
/*!40000 ALTER TABLE `payouts` DISABLE KEYS */;
/*!40000 ALTER TABLE `payouts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_translations`
--

DROP TABLE IF EXISTS `product_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_translations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `locale` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `short_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_translations_product_id_locale_unique` (`product_id`,`locale`),
  KEY `product_translations_locale_index` (`locale`),
  CONSTRAINT `product_translations_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=152 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_translations`
--

LOCK TABLES `product_translations` WRITE;
/*!40000 ALTER TABLE `product_translations` DISABLE KEYS */;
INSERT INTO `product_translations` VALUES (1,5,'en','Royal Canin Maxi Adult Kibble','Complete food for adult dogs of large breeds (26-44kg). Rich in proteins and vitamins.','Premium kibble for large dogs','2026-03-05 13:42:25','2026-03-05 13:42:25'),(2,5,'pt_BR','Ração Royal Canin Maxi Adult','Alimento completo para cães adultos de raças grandes (26-44kg). Rico em proteínas e vitaminas.','Ração premium para cães grandes','2026-03-05 13:42:25','2026-03-05 13:42:25'),(3,6,'en','Purina Pro Plan Sensitive Kibble','For dogs with sensitive skin. Formula with salmon and rice.','Kibble for sensitive skin','2026-03-05 13:42:25','2026-03-05 13:42:25'),(4,6,'pt_BR','Ração Purina Pro Plan Sensitive','Para cães com pele sensível. Fórmula com salmão e arroz.','Ração para pele sensível','2026-03-05 13:42:25','2026-03-05 13:42:25'),(5,2,'en','Dog Bowl','Sturdy stainless steel dog bowl, non-slip and easy to clean.','Dog bowl','2026-03-05 13:42:26','2026-03-05 13:42:26'),(6,2,'pt_BR','Tigela para cachorro','Tigela resistente em aço inoxidável para cães, antiderrapante e fácil de limpar.','Tigela para cachorro','2026-03-05 13:42:26','2026-03-05 13:42:26'),(7,3,'en','Sink','White ceramic sink with faucet included.','Bathroom sink','2026-03-05 13:42:26','2026-03-05 13:42:26'),(8,3,'pt_BR','Lavatório','Lavatório de cerâmica branca com torneira inclusa.','Lavatório banheiro','2026-03-05 13:42:26','2026-03-05 13:42:26'),(9,4,'en','Dog Kibble','Premium dry dog food, rich in essential nutrients.','Dog food','2026-03-05 13:42:26','2026-03-05 13:42:26'),(10,4,'pt_BR','Ração para cachorro','Alimento seco premium para cães, rico em nutrientes essenciais.','Ração para cachorro','2026-03-05 13:42:26','2026-03-05 13:42:26'),(11,7,'pt_BR','Petiscos Pedigree Dentastix','Bastões dentais para cães, ajudam a reduzir a formação de tártaro.','Petiscos dentais','2026-03-05 13:42:26','2026-03-05 13:42:26'),(12,11,'pt_BR','Tigela anti-glutonaria','Tigela com labirinto para retardar a ingestão de alimentos.','Tigela anti-glutonaria','2026-03-05 13:42:25','2026-03-05 13:42:25'),(13,18,'pt_BR','Alimento para peixes','Alimentação balanceada para peixes de aquário','Alimento para peixes','2026-03-05 13:42:25','2026-03-05 13:42:25'),(14,21,'pt_BR','Armário de madeira','Armário de armazenamento em madeira maciça com prateleiras ajustáveis.','Armário de madeira','2026-03-05 13:42:26','2026-03-05 13:42:26'),(15,15,'pt_BR','Mercurey 2022 Tinto - \"Les Chavances\"','Vinho tinto de Mercurey com aromas de frutas vermelhas.','Vinho tinto 75cl','2026-03-05 13:42:26','2026-03-05 13:42:26'),(16,17,'pt_BR','Aquário 100L','Aquário completo para peixes','Aquário 100L','2026-03-05 13:42:25','2026-03-05 13:42:25'),(18,9,'pt_BR','Patê Whiskas em geleia','Alimento úmido saboroso para gatos em geleia','Patê em geleia','2026-03-05 13:42:25','2026-03-05 13:42:25'),(19,13,'pt_BR','Antiparasitário Frontline Combo','Tratamento eficaz contra pulgas e carrapatos para cães e gatos','Antiparasitário','2026-03-05 13:42:25','2026-03-05 13:42:25'),(20,5,'fr','Croquettes Royal Canin Maxi Adult','Alimentation complète pour chiens adultes de grande taille (26-44kg). Riche en protéines et vitamines.','Croquettes premium pour grands chiens','2026-03-05 13:42:25','2026-03-05 13:42:25'),(21,6,'fr','Croquettes Purina Pro Plan Sensitive','Pour chiens à peau sensible. Formule avec saumon et riz.','Croquettes pour peaux sensibles','2026-03-05 13:42:25','2026-03-05 13:42:25'),(22,2,'fr','gamelle de chien','Gamelle robuste en acier inoxydable pour chien, antidérapante et facile à nettoyer.','Gamelle pour chien','2026-03-05 13:42:26','2026-03-05 13:42:26'),(23,3,'fr','Lavabo','Lavabo en céramique blanche avec robinetterie comprise.','Lavabo salle de bain','2026-03-05 13:42:26','2026-03-05 13:42:26'),(24,4,'fr','Croquette pour chien','Alimentation sèche premium pour chien, riche en nutriments essentiels.','Croquettes chien','2026-03-05 13:42:26','2026-03-05 13:42:26'),(25,7,'fr','Friandises Pedigree Dentastix','Bâtonnets dentaires pour chiens, aident à réduire la formation de tartre.','Friandises dentaires','2026-03-05 13:42:26','2026-03-05 13:42:26'),(26,7,'en','Pedigree Dentastix Treats','Dental sticks for dogs, help reduce tartar buildup.','Dental treats','2026-03-05 13:42:26','2026-03-05 13:42:26'),(27,11,'fr','Gamelle anti-glouton','Gamelle avec labyrinthe pour ralentir la prise de nourriture.','Gamelle anti-glouton','2026-03-05 13:42:25','2026-03-05 13:42:25'),(28,11,'en','Slow Feeder Dog Bowl','Bowl with labyrinth to slow down food intake.','Slow feeder bowl','2026-03-05 13:42:25','2026-03-05 13:42:25'),(29,18,'fr','Nourriture pour poissons','Alimentation équilibrée pour poissons d\'aquarium','Nourriture aquarium','2026-03-05 13:42:25','2026-03-05 13:42:25'),(30,18,'en','Fish Food','Balanced nutrition for aquarium fish','Fish food','2026-03-05 13:42:25','2026-03-05 13:42:25'),(31,21,'fr','Armoire en bois','Armoire de rangement en bois massif avec étagères ajustables.','Armoire en bois','2026-03-05 13:42:26','2026-03-05 13:42:26'),(32,21,'en','Wooden Cabinet','Solid wood storage cabinet with adjustable shelves.','Wooden cabinet','2026-03-05 13:42:26','2026-03-05 13:42:26'),(33,15,'fr','Mercurey 2022 Rouge - \"Les Chavances\"','Vin rouge de Mercurey aux arômes de fruits rouges.','Vin rouge 75cl','2026-03-05 13:42:26','2026-03-05 13:42:26'),(34,15,'en','Mercurey 2022 Red - \"Les Chavances\"','Red wine from Mercurey with red fruit aromas.','Red wine 75cl','2026-03-05 13:42:26','2026-03-05 13:42:26'),(35,17,'fr','Aquarium 100L','Aquarium complet pour poissons','Aquarium 100L','2026-03-05 13:42:25','2026-03-05 13:42:25'),(36,17,'en','100L Aquarium','Complete aquarium for fish','100L aquarium','2026-03-05 13:42:25','2026-03-05 13:42:25'),(39,9,'fr','Pâtée Whiskas en gelée','Alimentation humide savoureuse pour chats en gelée','Pâtée pour chats','2026-03-05 13:42:25','2026-03-05 13:42:25'),(40,9,'en','Whiskas Jelly Cat Food','Tasty wet cat food in jelly','Cat food in jelly','2026-03-05 13:42:25','2026-03-05 13:42:25'),(41,13,'fr','Antiparasitaire Frontline Combo','Traitement efficace contre les puces et tiques pour chiens et chats','Antiparasitaire','2026-03-05 13:42:25','2026-03-05 13:42:25'),(42,13,'en','Frontline Combo Flea Treatment','Effective flea and tick treatment for dogs and cats','Flea treatment','2026-03-05 13:42:25','2026-03-05 13:42:25'),(43,8,'fr','Croquettes Royal Canin Sterilised','Alimentation pour chats stérilisés','Croquettes pour chats stérilisés','2026-03-05 13:42:25','2026-03-05 13:42:25'),(44,8,'en','Royal Canin Sterilised Cat Food','Food for sterilized cats','Sterilised cat food','2026-03-05 13:42:25','2026-03-05 13:42:25'),(45,8,'pt_BR','Ração Royal Canin Sterilised','Alimento para gatos esterilizados','Ração para gatos esterilizados','2026-03-05 13:42:25','2026-03-05 13:42:25'),(46,10,'fr','Litière agglomérante Catsan','Litière pour chat de haute qualité pour un excellent contrôle des odeurs','Litière agglomérante','2026-03-05 13:42:25','2026-03-05 13:42:25'),(47,10,'en','Catsan Clumping Litter','High-quality clumping cat litter for excellent odor control','Clumping litter','2026-03-05 13:42:25','2026-03-05 13:42:25'),(48,10,'pt_BR','Areia aglomerante Catsan','Areia de gato aglomerante de alta qualidade para excelente controle de odores','Areia aglomerante','2026-03-05 13:42:25','2026-03-05 13:42:25'),(49,14,'pt_BR','Vermífugo Milbemax para cães','Tratamento vermífugo eficaz contra vermes intestinais para cães.','Vermífugo para cães','2026-03-05 13:42:26','2026-03-05 13:42:26'),(50,14,'fr','Vermifuge Milbemax pour chiens','Traitement vermifuge efficace contre les vers intestinaux pour chiens.','Vermifuge chien','2026-03-05 13:42:26','2026-03-05 13:42:26'),(51,14,'en','Milbemax Dewormer for Dogs','Effective deworming treatment against intestinal worms for dogs.','Dog dewormer','2026-03-05 13:42:26','2026-03-05 13:42:26'),(52,5,'es','Crocquetas Royal Canin Maxi Adult','Alimento completo para perros adultos de razas grandes (26-44kg). Rico en proteínas y vitaminas.','Crocquetas premium para perros grandes','2026-03-05 13:42:25','2026-03-05 13:42:25'),(53,5,'de','Royal Canin Maxi Adult Trockenfutter','Vollständiges Futter für erwachsene Hunde großer Rassen (26-44kg). Reich an Proteinen und Vitaminen.','Premium-Trockenfutter für große Hunde','2026-03-05 13:42:25','2026-03-05 13:42:25'),(54,6,'es','Crocquetas Purina Pro Plan Sensitive','Para perros con piel sensible. Fórmula con salmón y arroz.','Crocquetas para piel sensible','2026-03-05 13:42:25','2026-03-05 13:42:25'),(55,6,'de','Purina Pro Plan Sensitive Trockenfutter','Für Hunde mit empfindlicher Haut. Formel mit Lachs und Reis.','Trockenfutter für empfindliche Haut','2026-03-05 13:42:25','2026-03-05 13:42:25'),(56,2,'es','Cuenco para perro','Cuenco resistente de acero inoxidable para perros, antideslizante y fácil de limpiar.','Cuenco para perro','2026-03-05 13:42:26','2026-03-05 13:42:26'),(57,2,'de','Hundenapf','Robuster Edelstahl-Hundenapf, rutschfest und leicht zu reinigen.','Hundenapf','2026-03-05 13:42:26','2026-03-05 13:42:26'),(58,3,'es','Lavabo','Lavabo de cerámica blanca con grifería incluida.','Lavabo baño','2026-03-05 13:42:26','2026-03-05 13:42:26'),(59,3,'de','Waschbecken','Weißes Keramikwaschbecken mit Wasserhahn.','Badezimmerwaschbecken','2026-03-05 13:42:26','2026-03-05 13:42:26'),(60,4,'es','Croqueta para perro','Alimento seco premium para perros, rico en nutrientes esenciales.','Comida para perro','2026-03-05 13:42:26','2026-03-05 13:42:26'),(61,4,'de','Hundetrockenfutter','Premium-Trockenfutter für Hunde, reich an essentiellen Nährstoffen.','Hundefutter','2026-03-05 13:42:26','2026-03-05 13:42:26'),(62,7,'es','Golosinas Pedigree Dentastix','Barras dentales para perros, ayudan a reducir la formación de sarro.','Golosinas dentales','2026-03-05 13:42:26','2026-03-05 13:42:26'),(63,7,'de','Pedigree Dentastix Leckerlis','Zahnpflege-Sticks für Hunde, helfen bei der Reduzierung von Zahnstein.','Zahnpflege-Leckerlis','2026-03-05 13:42:26','2026-03-05 13:42:26'),(64,11,'es','Cuenco anti-glotonería','Cuenco con laberinto para ralentizar la ingesta de alimentos.','Cuenco lento','2026-03-05 13:42:25','2026-03-05 13:42:25'),(65,11,'de','Anti-Schlingnapf','Napf mit Labyrinth, um die Nahrungsaufnahme zu verlangsamen.','Langsamfutternapf','2026-03-05 13:42:25','2026-03-05 13:42:25'),(66,18,'es','Comida para peces','Alimentación equilibrada para peces de acuario','Comida para peces','2026-03-05 13:42:25','2026-03-05 13:42:25'),(67,18,'de','Fischfutter','Ausgewogene Ernährung für Aquarienfische','Fischfutter','2026-03-05 13:42:25','2026-03-05 13:42:25'),(68,21,'es','Armario de madera','Armario de almacenaje de madera maciza con estantes ajustables.','Armario de madera','2026-03-05 13:42:26','2026-03-05 13:42:26'),(69,21,'de','Holzschrank','Massiver Holzschrank mit verstellbaren Regalen.','Holzschrank','2026-03-05 13:42:26','2026-03-05 13:42:26'),(70,15,'es','Mercurey 2022 Tinto - \"Les Chavances\"','Vino tinto de Mercurey con aromas de frutos rojos.','Vino tinto 75cl','2026-03-05 13:42:26','2026-03-05 13:42:26'),(71,15,'de','Mercurey 2022 Rot - \"Les Chavances\"','Rotwein aus Mercurey mit Aromen roter Früchte.','Rotwein 75cl','2026-03-05 13:42:26','2026-03-05 13:42:26'),(72,17,'es','Acuario 100L','Acuario completo para peces','Acuario 100L','2026-03-05 13:42:25','2026-03-05 13:42:25'),(73,17,'de','Aquarium 100L','Komplettes Aquarium für Fische','100L Aquarium','2026-03-05 13:42:25','2026-03-05 13:42:25'),(74,9,'es','Comida húmeda Whiskas en gelatina','Sabrosa comida húmeda para gatos en gelatina','Comida en gelatina','2026-03-05 13:42:25','2026-03-05 13:42:25'),(75,9,'de','Whiskas Nassfutter in Gelee','Leckeres Nassfutter für Katzen in Gelee','Nassfutter in Gelee','2026-03-05 13:42:25','2026-03-05 13:42:25'),(76,13,'es','Antiparasitario Frontline Combo','Tratamiento eficaz contra pulgas y garrapatas para perros y gatos','Antiparasitario','2026-03-05 13:42:25','2026-03-05 13:42:25'),(77,13,'de','Frontline Combo Antiparasitikum','Wirksame Zecken- und Flohbehandlung für Hunde und Katzen','Antiparasitikum','2026-03-05 13:42:25','2026-03-05 13:42:25'),(78,8,'es','Crocquetas Royal Canin Sterilised','Alimento para gatos esterilizados','Crocquetas para gatos esterilizados','2026-03-05 13:42:25','2026-03-05 13:42:25'),(79,8,'de','Royal Canin Sterilised Katzenfutter','Futter für sterilisierte Katzen','Katzenfutter für sterilisierte','2026-03-05 13:42:25','2026-03-05 13:42:25'),(80,10,'es','Arena aglomerante Catsan','Arena para gatos aglomerante de alta calidad para un excelente control de olores','Arena aglomerante','2026-03-05 13:42:25','2026-03-05 13:42:25'),(81,10,'de','Catsan Klumpstreu','Hochwertige Klumpstreu für Katzen mit ausgezeichneter Geruchskontrolle','Klumpstreu','2026-03-05 13:42:26','2026-03-05 13:42:26'),(82,14,'es','Desparasitante Milbemax para perros','Tratamiento desparasitante eficaz contra lombrices intestinales para perros.','Desparasitante para perros','2026-03-05 13:42:26','2026-03-05 13:42:26'),(83,14,'de','Milbemax Wurmkur für Hunde','Wirksame Wurmkur gegen Darmwürmer für Hunde.','Wurmkur für Hunde','2026-03-05 13:42:26','2026-03-05 13:42:26'),(84,12,'fr','Arbre à chat 3 étages','Arbre à chat avec 3 niveaux, griffoirs et cachettes pour le bien-être de votre chat.','Arbre à chat','2026-03-05 13:42:26','2026-03-05 13:42:26'),(85,12,'en','3-Tier Cat Tree','Cat tree with 3 levels, scratching posts and hiding spots for your cat\'s well-being.','Cat tree','2026-03-05 13:42:26','2026-03-05 13:42:26'),(86,12,'de','3-stöckiger Kratzbaum','Kratzbaum mit 3 Ebenen, Kratzsäulen und Verstecken für das Wohlbefinden Ihrer Katze.','Kratzbaum','2026-03-05 13:42:26','2026-03-05 13:42:26'),(87,12,'es','Árbol para gatos 3 niveles','Árbol para gatos con 3 niveles, rascadores y escondites para el bienestar de tu gato.','Árbol para gatos','2026-03-05 13:42:26','2026-03-05 13:42:26'),(88,12,'pt','Arbre à chat 3 étages','Arbre à chat avec griffoirs, cachettes et jouets suspendus.','Produit pour chats','2026-03-05 12:08:34','2026-03-05 12:25:46'),(89,26,'fr','Hautes côtes de nuit','Vin rouge domaine du baccara aux tannins soyeux.','Vin rouge de Bourgogne','2026-03-05 13:42:26','2026-03-05 13:42:26'),(90,26,'en','High Coasts of Night','Red wine from baccara estate with silky tannins.','Red wine from Burgundy','2026-03-05 13:42:26','2026-03-05 13:42:26'),(91,26,'de','Hohe Küsten der Nacht','Rotwein vom Weingut Baccara mit seidigen Tanninen.','Rotwein aus Burgund','2026-03-05 13:42:26','2026-03-05 13:42:26'),(92,26,'es','Altas costas de la noche','Vino tinto de la finca baccara con taninos sedosos.','Vino tinto de Borgoña','2026-03-05 13:42:26','2026-03-05 13:42:26'),(93,26,'pt','Hautes côtes de nuit','Vin rouge domaine du baccara aux tannins soyeux.','Vin rouge de Bourgogne','2026-03-05 12:08:34','2026-03-05 12:25:46'),(94,27,'fr','Couteaux Bourguignons 2021','Coteaux Bourguignons 2021 réalisés par le domaine Remoriquet.','Vin rouge de Bourgogne','2026-03-05 13:42:26','2026-03-05 13:42:26'),(95,27,'en','Burgundy Knives 2021','Coteaux Bourguignons 2021 produced by the Remoriquet estate.','Red wine from Burgundy','2026-03-05 13:42:26','2026-03-05 13:42:26'),(96,27,'de','Burgunder Messer 2021','Coteaux Bourguignons 2021 hergestellt vom Weingut Remoriquet.','Rotwein aus Burgund','2026-03-05 13:42:26','2026-03-05 13:42:26'),(97,27,'es','Cuchillos de Borgoña 2021','Coteaux Bourguignons 2021 producidos por la finca Remoriquet.','Vino tinto de Borgoña','2026-03-05 13:42:26','2026-03-05 13:42:26'),(98,27,'pt','Couteaux Bourguignons 2021','Coteaux Bourguignons 2021  \'l\'Passe tout Bien\" réalisés par le domaine Remoriquet à Nuits Saint Georges en Côte de Nuits.\r\nUn véritable rapport qualité prix imbattable pour ce Bourgogne rouge du quotidien fruité et accessible.','Vin rouge de Bourgogne','2026-03-05 12:08:34','2026-03-05 12:25:47'),(99,28,'fr','Bourgogne Passetoutgrain 2022','Le célèbre Bourgogne Passetoutgrains, assemblage de Pinot Noir et Gamay.','Vin rouge de Bourgogne','2026-03-05 13:42:26','2026-03-05 13:42:26'),(100,28,'en','Burgundy Passetoutgrain 2022','The famous Burgundy Passetoutgrains, blend of Pinot Noir and Gamay.','Red wine from Burgundy','2026-03-05 13:42:26','2026-03-05 13:42:26'),(101,28,'de','Burgunder Passetoutgrain 2022','Der berühmte Burgunder Passetoutgrains, Cuvée aus Pinot Noir und Gamay.','Rotwein aus Burgund','2026-03-05 13:42:26','2026-03-05 13:42:26'),(102,28,'es','Borgoña Passetoutgrain 2022','El famoso Borgoña Passetoutgrains, mezcla de Pinot Noir y Gamay.','Vino tinto de Borgoña','2026-03-05 13:42:26','2026-03-05 13:42:26'),(103,28,'pt','Bourgogne Passetoutgrain 2022','Le célèbre Bourgogne Passetoutgrains, ici en provenance directe des Hautes Côtes de Nuits. Issus de l\'excellent domaine Michel Joannet. Un véritable rapport qualité-prix imbattable pour cette exception en Bourgogne, un assemblage de Pinot Noir et Gamay ! Vin en prix réduit à partir de 6 bouteilles.','Rouge 75 cl','2026-03-05 12:08:34','2026-03-05 12:25:47'),(104,29,'fr','Bourgogne Rouge 2024 - \"Les Champs d\'Argent\"','Cuvée très spécifique, ce Bourgogne est issu de vieilles vignes plantées en 1952.','Vin rouge de Bourgogne','2026-03-05 13:42:26','2026-03-05 13:42:26'),(105,29,'en','Burgundy Red 2024 - \"The Silver Fields\"','Very specific vintage, this Burgundy comes from old vines planted in 1952.','Red wine from Burgundy','2026-03-05 13:42:26','2026-03-05 13:42:26'),(106,29,'de','Burgunder Rot 2024 - \"Die Silberfelder\"','Sehr spezifischer Jahrgang, dieser Burgunder stammt von alten Reben aus dem Jahr 1952.','Rotwein aus Burgund','2026-03-05 13:42:26','2026-03-05 13:42:26'),(107,29,'es','Borgoña Tinto 2024 - \"Los Campos de Plata\"','Cuvée muy específica, este Borgoña proviene de viñas viejas plantadas en 1952.','Vino tinto de Borgoña','2026-03-05 13:42:26','2026-03-05 13:42:26'),(108,29,'pt','Bourgogne Rouge 2024 - \"Les Champs d\'Argent\"','Cuvée très spécifique, ce Bourgogne est issu de la parcelle \"Les Champs d\'Argent\", située à proximité des vignes en appellation village Vosne Romanée, dans le sud de l\'appellation, Bourgogne rouge 2024 est une pépite de la côte de Nuits, à moins de 20 € !','Rouge 75cl','2026-03-05 12:08:34','2026-03-05 12:25:47'),(109,2,'pt','gamelle de chien','gamelle en plastique','Produit pour chiens','2026-03-05 12:25:46','2026-03-05 12:25:46'),(110,3,'pt','Lavabo','Lavabo blanc moderne','Accessoire pour animaux','2026-03-05 12:25:46','2026-03-05 12:25:46'),(111,4,'pt','Croquette pour chien','Croquette pour chien et chat Royal Canin','Produit pour chiens','2026-03-05 12:25:46','2026-03-05 12:25:46'),(112,5,'pt','Ração Royal Canin Maxi Adult','Alimento completo para cães adultos de raças grandes (26-44kg). Rico em proteínas e vitaminas.','Ração premium para cães grandes','2026-03-05 12:25:46','2026-03-05 13:20:13'),(113,6,'pt','Croquettes Purina Pro Plan Sensitive','Pour chiens à peau sensible. Formule avec saumon et riz, facile à digérer.','Accessoire pour animaux','2026-03-05 12:25:46','2026-03-05 12:25:46'),(114,7,'pt','Friandises Pedigree Dentastix','Bâtonnets dentaires pour chiens, aide à réduire la formation de tartre.','Accessoire pour animaux','2026-03-05 12:25:46','2026-03-05 12:25:46'),(115,8,'pt','Croquettes Royal Canin Sterilised','Pour chats stérilisés. Aide à maintenir un poids santé.','Accessoire pour animaux','2026-03-05 12:25:46','2026-03-05 12:25:46'),(116,9,'pt','Pâtée Whiskas en gelée','Lot de 12 sachets fraîcheur, variété de saveurs.','Accessoire pour animaux','2026-03-05 12:25:46','2026-03-05 12:25:46'),(117,10,'pt','Litière agglomérante Catsan','Litière minérale ultra-absorbante, 10 litres.','Accessoire pour animaux','2026-03-05 12:25:46','2026-03-05 12:25:46'),(118,11,'pt','Gamelle anti-glouton','Gamelle avec labyrinthe pour ralentir la prise de nourriture.','Accessoire pour animaux','2026-03-05 12:25:46','2026-03-05 12:25:46'),(119,13,'pt','Antiparasitaire Frontline Combo','Pipettes anti-puces et tiques pour chats, 3 pipettes.','Accessoire pour animaux','2026-03-05 12:25:46','2026-03-05 12:25:46'),(120,14,'pt','Vermifuge Milbemax pour chiens','Comprimés vermifuges pour chiens de 5-25kg.','Produit pour chiens','2026-03-05 12:25:46','2026-03-05 12:25:46'),(121,15,'pt','Mercurey 2022 Rouge - \"Les Chavances\"','Mercurey 2022 rouge \"Les Chavances\" du domaine Danjean Berthoux : vendanges manuelles et élevage fût de chêne délicat, qui offre de très belles notes de fruits rouges sur une bonne densité. Ce vin typique du Mercurey vous charmera sur les meilleures viandes. Jeune mais très équilibré et aux tanins déjà très doux.','Vin rouge 75cl','2026-03-05 12:25:46','2026-03-05 12:25:46'),(122,17,'pt','Aquarium 100L','Aquarium complet avec filtre','Aquarium pour poissons','2026-03-05 12:25:46','2026-03-05 12:25:46'),(123,18,'pt','Nourriture pour poissons','Flocons pour poissons tropicaux','Alimentation aquarium','2026-03-05 12:25:46','2026-03-05 12:25:46'),(124,21,'pt','Armoire en bois','Armoire de cuisine en bois de chêne.','Armoire de cuisine Maga','2026-03-05 12:25:46','2026-03-05 12:25:46'),(125,2,'it','Ciotola per cani','Ciotola robusta in acciaio inox per cani, antiscivolo e facile da pulire.','Ciotola per cani','2026-03-05 13:42:26','2026-03-05 13:42:26'),(126,3,'it','Lavabo','Lavabo in ceramica bianca con rubinetteria inclusa.','Lavabo bagno','2026-03-05 13:42:26','2026-03-05 13:42:26'),(127,4,'it','Cibo secco per cani','Cibo secco premium per cani, ricco di nutrienti essenziali.','Cibo per cani','2026-03-05 13:42:26','2026-03-05 13:42:26'),(128,5,'it','Royal Canin Maxi Adult Crocchette','Alimento completo per cani adulti di razze grandi (26-44kg). Ricco di proteine e vitamine.','Crocchette premium per cani grandi','2026-03-05 13:42:25','2026-03-05 13:42:25'),(129,6,'it','Purina Pro Plan Sensitive Crocchette','Per cani con pelle sensibile. Formula con salmone e riso.','Crocchette per pelle sensibile','2026-03-05 13:42:25','2026-03-05 13:42:25'),(130,7,'it','Pedigree Dentastix Snack','Bastoncini dentali per cani, aiutano a ridurre la formazione di tartaro.','Snack dentali','2026-03-05 13:42:26','2026-03-05 13:42:26'),(131,8,'it','Royal Canin Sterilised Cibo per gatti','Cibo per gatti sterilizzati','Cibo per gatti sterilizzati','2026-03-05 13:42:25','2026-03-05 13:42:25'),(132,9,'it','Whiskas Cibo umido in gelatina','Cibo umido gustoso per gatti in gelatina','Cibo in gelatina','2026-03-05 13:42:25','2026-03-05 13:42:25'),(133,10,'it','Catsan Lettiera agglomerante','Lettiera agglomerante di alta qualità per gatti per un eccellente controllo degli odori','Lettiera agglomerante','2026-03-05 13:42:26','2026-03-05 13:42:26'),(134,11,'it','Ciotola anti-ingozzamento','Ciotola con labirinto per rallentare l\'assunzione di cibo.','Ciotola lenta','2026-03-05 13:42:25','2026-03-05 13:42:25'),(135,12,'pt_BR','Árvore para gatos 3 andares','Árvore para gatos com 3 níveis, arranhadores e esconderijos para o bem-estar do seu gato.','Árvore para gatos','2026-03-05 13:42:26','2026-03-05 13:42:26'),(136,12,'it','Tiragraffi per gatti 3 livelli','Tiragraffi per gatti con 3 livelli, tiragraffi e nascondigli per il benessere del tuo gatto.','Tiragraffi per gatti','2026-03-05 13:42:26','2026-03-05 13:42:26'),(137,13,'it','Frontline Combo Antiparassitario','Trattamento efficace contro pulci e zecche per cani e gatti','Antiparassitario','2026-03-05 13:42:25','2026-03-05 13:42:25'),(138,14,'it','Sverminante Milbemax per cani','Trattamento sverminante efficace contro i vermi intestinali per cani.','Sverminante per cani','2026-03-05 13:42:26','2026-03-05 13:42:26'),(139,15,'it','Mercurey 2022 Rosso - \"Les Chavances\"','Vino rosso di Mercurey con aromi di frutti rossi.','Vino rosso 75cl','2026-03-05 13:42:26','2026-03-05 13:42:26'),(140,17,'it','Acquario 100L','Acquario completo per pesci','Acquario 100L','2026-03-05 13:42:25','2026-03-05 13:42:25'),(141,18,'it','Cibo per pesci','Alimentazione equilibrata per pesci d\'acquario','Cibo per pesci','2026-03-05 13:42:25','2026-03-05 13:42:25'),(142,21,'it','Armadio in legno','Armadio portaoggetti in legno massello con ripiani regolabili.','Armadio in legno','2026-03-05 13:42:26','2026-03-05 13:42:26'),(143,26,'pt_BR','Altas encostas da noite','Vinho tinto do domínio baccara com taninos sedosos.','Vinho tinto da Borgonha','2026-03-05 13:42:26','2026-03-05 13:42:26'),(144,26,'it','Alte coste della notte','Vino rosso della tenuta baccara con tannini setosi.','Vino rosso di Borgogna','2026-03-05 13:42:26','2026-03-05 13:42:26'),(145,27,'pt_BR','Facas da Borgonha 2021','Coteaux Bourguignons 2021 produzidos pelo domínio Remoriquet.','Vinho tinto da Borgonha','2026-03-05 13:42:26','2026-03-05 13:42:26'),(146,27,'it','Coltelli di Borgogna 2021','Coteaux Bourguignons 2021 prodotti dalla tenuta Remoriquet.','Vino rosso di Borgogna','2026-03-05 13:42:26','2026-03-05 13:42:26'),(147,28,'pt_BR','Borgonha Passetoutgrain 2022','O famoso Borgonha Passetoutgrains, assemblage de Pinot Noir e Gamay.','Vinho tinto da Borgonha','2026-03-05 13:42:26','2026-03-05 13:42:26'),(148,28,'it','Borgogna Passetoutgrain 2022','La famosa Borgogna Passetoutgrains, blend di Pinot Noir e Gamay.','Vino rosso di Borgogna','2026-03-05 13:42:26','2026-03-05 13:42:26'),(149,29,'pt_BR','Borgonha Tinto 2024 - \"Os Campos de Prata\"','Cuvée muito específica, este Borgonha vem de vinhas velhas plantadas em 1952.','Vinho tinto da Borgonha','2026-03-05 13:42:26','2026-03-05 13:42:26'),(150,29,'it','Borgogna Rosso 2024 - \"I Campi d\'Argento\"','Cuvée molto specifica, questa Borgogna proviene da vecchie viti piantate nel 1952.','Vino rosso di Borgogna','2026-03-05 13:42:26','2026-03-05 13:42:26');
/*!40000 ALTER TABLE `product_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(8,2) NOT NULL,
  `quantity` int NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `supplier_id` bigint unsigned DEFAULT NULL,
  `vendor_id` bigint unsigned DEFAULT NULL,
  `category_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `stock_threshold` int NOT NULL DEFAULT '5',
  `name` json DEFAULT NULL,
  `description` json DEFAULT NULL,
  `short_description` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_supplier_id_foreign` (`supplier_id`),
  KEY `products_category_id_foreign` (`category_id`),
  KEY `products_vendor_id_foreign` (`vendor_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `products_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `products_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (2,'PROD-00002','products/buAhe7wMZeMElKOESn2JSXsMGzSMaKkgRtbtGkRr.jpg',32.00,7,1,1,NULL,3,'2026-01-12 10:44:27','2026-03-05 11:46:01',5,'{\"de\": \"gamelle de chien\", \"en\": \"gamelle de chien\", \"es\": \"gamelle de chien\", \"fr\": \"gamelle de chien\", \"pt\": \"gamelle de chien\"}','{\"de\": \"gamelle en plastique\", \"en\": \"gamelle en plastique\", \"es\": \"gamelle en plastique\", \"fr\": \"gamelle en plastique\", \"pt\": \"gamelle en plastique\"}','{\"de\": \"Produit pour chiens\", \"en\": \"Produit pour chiens\", \"es\": \"Produit pour chiens\", \"fr\": \"Produit pour chiens\", \"pt\": \"Produit pour chiens\"}'),(3,'PROD-00003','products/7FikKWQVpxWACu7uVtZUrrIhm1ug31vJXShi1dW1.jpg',245.00,7,1,1,NULL,3,'2026-02-17 11:12:45','2026-03-05 11:46:01',5,'{\"de\": \"Lavabo\", \"en\": \"Lavabo\", \"es\": \"Lavabo\", \"fr\": \"Lavabo\", \"pt\": \"Lavabo\"}','{\"de\": \"Lavabo blanc moderne\", \"en\": \"Lavabo blanc moderne\", \"es\": \"Lavabo blanc moderne\", \"fr\": \"Lavabo blanc moderne\", \"pt\": \"Lavabo blanc moderne\"}','{\"de\": \"Accessoire pour animaux\", \"en\": \"Accessoire pour animaux\", \"es\": \"Accessoire pour animaux\", \"fr\": \"Accessoire pour animaux\", \"pt\": \"Accessoire pour animaux\"}'),(4,'PROD-00004','products/fZ2Lkck1WAZ0R6HO7YzgyO71AgnznGEOUJFlVLit.jpg',21.00,9,1,2,NULL,1,'2026-02-17 11:50:51','2026-03-05 11:46:01',5,'{\"de\": \"Croquette pour chien\", \"en\": \"Croquette pour chien\", \"es\": \"Croquette pour chien\", \"fr\": \"Croquette pour chien\", \"pt\": \"Croquette pour chien\"}','{\"de\": \"Croquette pour chien et chat Royal Canin\", \"en\": \"Croquette pour chien et chat Royal Canin\", \"es\": \"Croquette pour chien et chat Royal Canin\", \"fr\": \"Croquette pour chien et chat Royal Canin\", \"pt\": \"Croquette pour chien et chat Royal Canin\"}','{\"de\": \"Produit pour chiens\", \"en\": \"Produit pour chiens\", \"es\": \"Produit pour chiens\", \"fr\": \"Produit pour chiens\", \"pt\": \"Produit pour chiens\"}'),(5,'PROD-00005','products/zmpDiXbaXzdJTlkXJNiyEUaPFB7Ief7RxBnCo6ZY.jpg',54.99,45,1,1,NULL,1,'2026-02-18 18:41:19','2026-03-05 11:46:01',10,'{\"de\": \"Croquettes Royal Canin Maxi Adult\", \"en\": \"Croquettes Royal Canin Maxi Adult\", \"es\": \"Croquettes Royal Canin Maxi Adult\", \"fr\": \"Croquettes Royal Canin Maxi Adult\", \"pt\": \"Croquettes Royal Canin Maxi Adult\"}','{\"de\": \"Alimentation complète pour chiens adultes de grande taille (26-44kg). Riche en protéines et vitamines.\", \"en\": \"Alimentation complète pour chiens adultes de grande taille (26-44kg). Riche en protéines et vitamines.\", \"es\": \"Alimentation complète pour chiens adultes de grande taille (26-44kg). Riche en protéines et vitamines.\", \"fr\": \"Alimentation complète pour chiens adultes de grande taille (26-44kg). Riche en protéines et vitamines.\", \"pt\": \"Alimentation complète pour chiens adultes de grande taille (26-44kg). Riche en protéines et vitamines.\"}','{\"de\": \"Accessoire pour animaux\", \"en\": \"Accessoire pour animaux\", \"es\": \"Accessoire pour animaux\", \"fr\": \"Accessoire pour animaux\", \"pt\": \"Accessoire pour animaux\"}'),(6,'PROD-00006','products/IRX9XuEI8Hh4RVk6vFDWqDVn2yxMNc7YklWASUhJ.jpg',47.50,31,1,1,NULL,1,'2026-02-18 18:41:19','2026-03-05 11:46:01',8,'{\"de\": \"Croquettes Purina Pro Plan Sensitive\", \"en\": \"Croquettes Purina Pro Plan Sensitive\", \"es\": \"Croquettes Purina Pro Plan Sensitive\", \"fr\": \"Croquettes Purina Pro Plan Sensitive\", \"pt\": \"Croquettes Purina Pro Plan Sensitive\"}','{\"de\": \"Pour chiens à peau sensible. Formule avec saumon et riz, facile à digérer.\", \"en\": \"Pour chiens à peau sensible. Formule avec saumon et riz, facile à digérer.\", \"es\": \"Pour chiens à peau sensible. Formule avec saumon et riz, facile à digérer.\", \"fr\": \"Pour chiens à peau sensible. Formule avec saumon et riz, facile à digérer.\", \"pt\": \"Pour chiens à peau sensible. Formule avec saumon et riz, facile à digérer.\"}','{\"de\": \"Accessoire pour animaux\", \"en\": \"Accessoire pour animaux\", \"es\": \"Accessoire pour animaux\", \"fr\": \"Accessoire pour animaux\", \"pt\": \"Accessoire pour animaux\"}'),(7,'PROD-00007','products/sMbP7B3eIAGtIfvmVWwvUqxwFpVGVnCfPgf39I0M.jpg',12.90,120,1,2,NULL,1,'2026-02-18 18:41:19','2026-03-05 11:46:01',20,'{\"de\": \"Friandises Pedigree Dentastix\", \"en\": \"Friandises Pedigree Dentastix\", \"es\": \"Friandises Pedigree Dentastix\", \"fr\": \"Friandises Pedigree Dentastix\", \"pt\": \"Friandises Pedigree Dentastix\"}','{\"de\": \"Bâtonnets dentaires pour chiens, aide à réduire la formation de tartre.\", \"en\": \"Bâtonnets dentaires pour chiens, aide à réduire la formation de tartre.\", \"es\": \"Bâtonnets dentaires pour chiens, aide à réduire la formation de tartre.\", \"fr\": \"Bâtonnets dentaires pour chiens, aide à réduire la formation de tartre.\", \"pt\": \"Bâtonnets dentaires pour chiens, aide à réduire la formation de tartre.\"}','{\"de\": \"Accessoire pour animaux\", \"en\": \"Accessoire pour animaux\", \"es\": \"Accessoire pour animaux\", \"fr\": \"Accessoire pour animaux\", \"pt\": \"Accessoire pour animaux\"}'),(8,'PROD-00008','products/jgYitCcI4lf56NGMFn9RpooSP9zuo3PQDco3AgCU.jpg',39.99,38,1,1,NULL,2,'2026-02-18 18:41:19','2026-03-05 11:46:01',8,'{\"de\": \"Croquettes Royal Canin Sterilised\", \"en\": \"Croquettes Royal Canin Sterilised\", \"es\": \"Croquettes Royal Canin Sterilised\", \"fr\": \"Croquettes Royal Canin Sterilised\", \"pt\": \"Croquettes Royal Canin Sterilised\"}','{\"de\": \"Pour chats stérilisés. Aide à maintenir un poids santé.\", \"en\": \"Pour chats stérilisés. Aide à maintenir un poids santé.\", \"es\": \"Pour chats stérilisés. Aide à maintenir un poids santé.\", \"fr\": \"Pour chats stérilisés. Aide à maintenir un poids santé.\", \"pt\": \"Pour chats stérilisés. Aide à maintenir un poids santé.\"}','{\"de\": \"Accessoire pour animaux\", \"en\": \"Accessoire pour animaux\", \"es\": \"Accessoire pour animaux\", \"fr\": \"Accessoire pour animaux\", \"pt\": \"Accessoire pour animaux\"}'),(9,'PROD-00009','products/yl4Le88ftyzxBWY3mrSYHxtfkqJHHAeAzIYznUbW.jpg',15.90,75,1,1,NULL,2,'2026-02-18 18:41:19','2026-03-05 11:46:01',15,'{\"de\": \"Pâtée Whiskas en gelée\", \"en\": \"Pâtée Whiskas en gelée\", \"es\": \"Pâtée Whiskas en gelée\", \"fr\": \"Pâtée Whiskas en gelée\", \"pt\": \"Pâtée Whiskas en gelée\"}','{\"de\": \"Lot de 12 sachets fraîcheur, variété de saveurs.\", \"en\": \"Lot de 12 sachets fraîcheur, variété de saveurs.\", \"es\": \"Lot de 12 sachets fraîcheur, variété de saveurs.\", \"fr\": \"Lot de 12 sachets fraîcheur, variété de saveurs.\", \"pt\": \"Lot de 12 sachets fraîcheur, variété de saveurs.\"}','{\"de\": \"Accessoire pour animaux\", \"en\": \"Accessoire pour animaux\", \"es\": \"Accessoire pour animaux\", \"fr\": \"Accessoire pour animaux\", \"pt\": \"Accessoire pour animaux\"}'),(10,'PROD-00010','products/0Uu4HSVVuAb0hPxPPrh2RFB6IWgIsFY1gqFc7duU.jpg',18.50,42,1,2,NULL,2,'2026-02-18 18:41:19','2026-03-05 11:46:01',10,'{\"de\": \"Litière agglomérante Catsan\", \"en\": \"Litière agglomérante Catsan\", \"es\": \"Litière agglomérante Catsan\", \"fr\": \"Litière agglomérante Catsan\", \"pt\": \"Litière agglomérante Catsan\"}','{\"de\": \"Litière minérale ultra-absorbante, 10 litres.\", \"en\": \"Litière minérale ultra-absorbante, 10 litres.\", \"es\": \"Litière minérale ultra-absorbante, 10 litres.\", \"fr\": \"Litière minérale ultra-absorbante, 10 litres.\", \"pt\": \"Litière minérale ultra-absorbante, 10 litres.\"}','{\"de\": \"Accessoire pour animaux\", \"en\": \"Accessoire pour animaux\", \"es\": \"Accessoire pour animaux\", \"fr\": \"Accessoire pour animaux\", \"pt\": \"Accessoire pour animaux\"}'),(11,'PROD-00011','products/4A90c8PSSrrQkkWDmEihuJVXJbLEOt2uY1q9WJ6r.jpg',24.99,28,1,1,NULL,3,'2026-02-18 18:41:19','2026-03-05 11:46:01',5,'{\"de\": \"Gamelle anti-glouton\", \"en\": \"Gamelle anti-glouton\", \"es\": \"Gamelle anti-glouton\", \"fr\": \"Gamelle anti-glouton\", \"pt\": \"Gamelle anti-glouton\"}','{\"de\": \"Gamelle avec labyrinthe pour ralentir la prise de nourriture.\", \"en\": \"Gamelle avec labyrinthe pour ralentir la prise de nourriture.\", \"es\": \"Gamelle avec labyrinthe pour ralentir la prise de nourriture.\", \"fr\": \"Gamelle avec labyrinthe pour ralentir la prise de nourriture.\", \"pt\": \"Gamelle avec labyrinthe pour ralentir la prise de nourriture.\"}','{\"de\": \"Accessoire pour animaux\", \"en\": \"Accessoire pour animaux\", \"es\": \"Accessoire pour animaux\", \"fr\": \"Accessoire pour animaux\", \"pt\": \"Accessoire pour animaux\"}'),(12,'PROD-00012','products/G2UIugFVHKcDvKHDTffjEjVabHW7beI3sCO5rrgt.jpg',89.90,12,1,2,NULL,2,'2026-02-18 18:41:19','2026-03-05 11:46:01',3,'{\"de\": \"Arbre à chat 3 étages\", \"en\": \"Arbre à chat 3 étages\", \"es\": \"Arbre à chat 3 étages\", \"fr\": \"Arbre à chat 3 étages\", \"pt\": \"Arbre à chat 3 étages\"}','{\"de\": \"Arbre à chat avec griffoirs, cachettes et jouets suspendus.\", \"en\": \"Arbre à chat avec griffoirs, cachettes et jouets suspendus.\", \"es\": \"Arbre à chat avec griffoirs, cachettes et jouets suspendus.\", \"fr\": \"Arbre à chat avec griffoirs, cachettes et jouets suspendus.\", \"pt\": \"Arbre à chat avec griffoirs, cachettes et jouets suspendus.\"}','{\"de\": \"Produit pour chats\", \"en\": \"Produit pour chats\", \"es\": \"Produit pour chats\", \"fr\": \"Produit pour chats\", \"pt\": \"Produit pour chats\"}'),(13,'PROD-00013','products/LGjmu8uMxgY8mdav8sc85TsMsgEdYm3WizQfr3PY.jpg',29.90,22,1,1,NULL,4,'2026-02-18 18:41:19','2026-03-05 11:46:01',5,'{\"de\": \"Antiparasitaire Frontline Combo\", \"en\": \"Antiparasitaire Frontline Combo\", \"es\": \"Antiparasitaire Frontline Combo\", \"fr\": \"Antiparasitaire Frontline Combo\", \"pt\": \"Antiparasitaire Frontline Combo\"}','{\"de\": \"Pipettes anti-puces et tiques pour chats, 3 pipettes.\", \"en\": \"Pipettes anti-puces et tiques pour chats, 3 pipettes.\", \"es\": \"Pipettes anti-puces et tiques pour chats, 3 pipettes.\", \"fr\": \"Pipettes anti-puces et tiques pour chats, 3 pipettes.\", \"pt\": \"Pipettes anti-puces et tiques pour chats, 3 pipettes.\"}','{\"de\": \"Accessoire pour animaux\", \"en\": \"Accessoire pour animaux\", \"es\": \"Accessoire pour animaux\", \"fr\": \"Accessoire pour animaux\", \"pt\": \"Accessoire pour animaux\"}'),(14,'PROD-00014','products/lYWdIEneRDKHoI2KrChWSvqF0DZMl5ZHyMeVI92e.jpg',18.90,30,1,2,NULL,4,'2026-02-18 18:41:19','2026-03-05 11:46:01',6,'{\"de\": \"Vermifuge Milbemax pour chiens\", \"en\": \"Vermifuge Milbemax pour chiens\", \"es\": \"Vermifuge Milbemax pour chiens\", \"fr\": \"Vermifuge Milbemax pour chiens\", \"pt\": \"Vermifuge Milbemax pour chiens\"}','{\"de\": \"Comprimés vermifuges pour chiens de 5-25kg.\", \"en\": \"Comprimés vermifuges pour chiens de 5-25kg.\", \"es\": \"Comprimés vermifuges pour chiens de 5-25kg.\", \"fr\": \"Comprimés vermifuges pour chiens de 5-25kg.\", \"pt\": \"Comprimés vermifuges pour chiens de 5-25kg.\"}','{\"de\": \"Produit pour chiens\", \"en\": \"Produit pour chiens\", \"es\": \"Produit pour chiens\", \"fr\": \"Produit pour chiens\", \"pt\": \"Produit pour chiens\"}'),(15,'PROD-00015','products/QNCjht4DpxgloriTpdkiI3WyKtpinJci5ZaPA9sk.jpg',28.00,3,1,NULL,2,10,'2026-02-20 10:17:59','2026-03-06 22:58:22',5,'{\"en\": \"Mercurey 2022 Rouge - \\\"Les Chavances\\\"\", \"fr\": \"Mercurey 2022 Rouge - \\\"Les Chavances\\\"\"}','{\"en\": \"Mercurey 2022 rouge \\\"Les Chavances\\\" du domaine Danjean Berthoux : vendanges manuelles et élevage fût de chêne délicat, qui offre de très belles notes de fruits rouges sur une bonne densité. Ce vin typique du Mercurey vous charmera sur les meilleures viandes. Jeune mais très équilibré et aux tanins déjà très doux.\", \"fr\": \"Mercurey 2022 rouge \\\"Les Chavances\\\" du domaine Danjean Berthoux : vendanges manuelles et élevage fût de chêne délicat, qui offre de très belles notes de fruits rouges sur une bonne densité. Ce vin typique du Mercurey vous charmera sur les meilleures viandes. Jeune mais très équilibré et aux tanins déjà très doux.\"}','{\"en\": \"Vin rouge 75cl\", \"fr\": \"Vin rouge 75cl\"}'),(17,'PROD-00017','products/PXUevMZLhZHQY15OykHUmqRk45f504hm8NYdfGd8.jpg',129.99,4,1,1,NULL,5,'2026-02-23 10:17:10','2026-03-05 11:46:01',5,'{\"de\": \"Aquarium 100L\", \"en\": \"Aquarium 100L\", \"es\": \"Aquarium 100L\", \"fr\": \"Aquarium 100L\", \"pt\": \"Aquarium 100L\"}','{\"de\": \"Aquarium complet avec filtre\", \"en\": \"Aquarium complet avec filtre\", \"es\": \"Aquarium complet avec filtre\", \"fr\": \"Aquarium complet avec filtre\", \"pt\": \"Aquarium complet avec filtre\"}','{\"de\": \"Aquarium pour poissons\", \"en\": \"Aquarium pour poissons\", \"es\": \"Aquarium pour poissons\", \"fr\": \"Aquarium pour poissons\", \"pt\": \"Aquarium pour poissons\"}'),(18,'PROD-00018','products/xmqEOYiEUulRohpfJFEmKSmgJBLQ4stbAwwEdQ2d.jpg',8.99,50,1,1,NULL,5,'2026-02-23 10:17:12','2026-03-05 11:46:01',10,'{\"de\": \"Nourriture pour poissons\", \"en\": \"Nourriture pour poissons\", \"es\": \"Nourriture pour poissons\", \"fr\": \"Nourriture pour poissons\", \"pt\": \"Nourriture pour poissons\"}','{\"de\": \"Flocons pour poissons tropicaux\", \"en\": \"Flocons pour poissons tropicaux\", \"es\": \"Flocons pour poissons tropicaux\", \"fr\": \"Flocons pour poissons tropicaux\", \"pt\": \"Flocons pour poissons tropicaux\"}','{\"de\": \"Alimentation aquarium\", \"en\": \"Alimentation aquarium\", \"es\": \"Alimentation aquarium\", \"fr\": \"Alimentation aquarium\", \"pt\": \"Alimentation aquarium\"}'),(21,'PROD-00021','products/1enAWwIjJZe7UEEBZRQOz1CnKpnlbqWD4MlU11HK.jpg',300.00,17,1,1,NULL,8,'2026-02-23 11:10:23','2026-03-05 11:46:01',5,'{\"de\": \"Armoire en bois\", \"en\": \"Armoire en bois\", \"es\": \"Armoire en bois\", \"fr\": \"Armoire en bois\", \"pt\": \"Armoire en bois\"}','{\"de\": \"Armoire de cuisine en bois de chêne.\", \"en\": \"Armoire de cuisine en bois de chêne.\", \"es\": \"Armoire de cuisine en bois de chêne.\", \"fr\": \"Armoire de cuisine en bois de chêne.\", \"pt\": \"Armoire de cuisine en bois de chêne.\"}','{\"de\": \"Armoire de cuisine Maga\", \"en\": \"Armoire de cuisine Maga\", \"es\": \"Armoire de cuisine Maga\", \"fr\": \"Armoire de cuisine Maga\", \"pt\": \"Armoire de cuisine Maga\"}'),(26,'VIN-000026','products/diGGDFAXOgChe3pYWRhBycOFbZIC0ltUrUXXQ8Ik.jpg',18.00,10,1,NULL,2,10,'2026-03-05 09:43:17','2026-03-06 23:01:12',5,'{\"en\": \"Hautes côtes de nuit\", \"fr\": \"Hautes côtes de nuit\"}','{\"en\": \"Vin rouge domaine du baccara aux tannins soyeux.\", \"fr\": \"Vin rouge domaine du baccara aux tannins soyeux.\"}','{\"en\": \"Vin rouge de Bourgogne\", \"fr\": \"Vin rouge de Bourgogne\"}'),(27,'VIN-000027','products/B4JxgBm4V5CUjbA6Ltf8HfCQI96OrUcu9BC5Lunp.jpg',8.70,13,1,NULL,2,10,'2026-03-05 09:56:08','2026-03-06 23:01:43',5,'{\"en\": \"Couteaux Bourguignons 2021\", \"fr\": \"Couteaux Bourguignons 2021\"}','{\"en\": \"Coteaux Bourguignons 2021  \'l\'Passe tout Bien\\\" réalisés par le domaine Remoriquet à Nuits Saint Georges en Côte de Nuits.\\r\\nUn véritable rapport qualité prix imbattable pour ce Bourgogne rouge du quotidien fruité et accessible.\", \"fr\": \"Coteaux Bourguignons 2021  \'l\'Passe tout Bien\\\" réalisés par le domaine Remoriquet à Nuits Saint Georges en Côte de Nuits.\\r\\nUn véritable rapport qualité prix imbattable pour ce Bourgogne rouge du quotidien fruité et accessible.\"}','{\"en\": \"Vin rouge de Bourgogne\", \"fr\": \"Vin rouge de Bourgogne\"}'),(28,'VIN-000028','products/LqMwT7fcxQBa7rnpMQ5FatcYM4KbDPsna3jcqKXF.jpg',10.80,10,1,NULL,2,10,'2026-03-05 09:58:09','2026-03-06 23:01:55',5,'{\"en\": \"Bourgogne Passetoutgrain 2022\", \"fr\": \"Bourgogne Passetoutgrain 2022\"}','{\"en\": \"Le célèbre Bourgogne Passetoutgrains, ici en provenance directe des Hautes Côtes de Nuits. Issus de l\'excellent domaine Michel Joannet. Un véritable rapport qualité-prix imbattable pour cette exception en Bourgogne, un assemblage de Pinot Noir et Gamay ! Vin en prix réduit à partir de 6 bouteilles.\", \"fr\": \"Le célèbre Bourgogne Passetoutgrains, ici en provenance directe des Hautes Côtes de Nuits. Issus de l\'excellent domaine Michel Joannet. Un véritable rapport qualité-prix imbattable pour cette exception en Bourgogne, un assemblage de Pinot Noir et Gamay ! Vin en prix réduit à partir de 6 bouteilles.\"}','{\"en\": \"Rouge 75 cl\", \"fr\": \"Rouge 75 cl\"}'),(29,'VIN-000029','products/kfQ8QPeTmxND3cq9k1DzmO5ZKOSXIONIyKEEbrrO.jpg',18.50,16,1,NULL,2,10,'2026-03-05 09:59:47','2026-03-07 10:51:39',5,'{\"de\": \"Bourgogne Rouge 2024 - \\\"Les Champs d\'Argent\\\"\", \"en\": \"Bourgogne Rouge 2024 - \\\"Les Champs d\'Argent\\\"\", \"es\": \"Bourgogne Rouge 2024 - \\\"Les Champs d\'Argent\\\"\", \"fr\": \"Bourgogne Rouge 2024 - \\\"Les Champs d\'Argent\\\"\", \"pt\": \"Bourgogne Rouge 2024 - \\\"Les Champs d\'Argent\\\"\"}','{\"de\": \"Cuvée très spécifique, ce Bourgogne est issu de la parcelle \\\"Les Champs d\'Argent\\\", située à proximité des vignes en appellation village Vosne Romanée, dans le sud de l\'appellation, Bourgogne rouge 2024 est une pépite de la côte de Nuits, à moins de 20 € !\", \"en\": \"Cuvée très spécifique, ce Bourgogne est issu de la parcelle \\\"Les Champs d\'Argent\\\", située à proximité des vignes en appellation village Vosne Romanée, dans le sud de l\'appellation, Bourgogne rouge 2024 est une pépite de la côte de Nuits, à moins de 20 € !\", \"es\": \"Cuvée très spécifique, ce Bourgogne est issu de la parcelle \\\"Les Champs d\'Argent\\\", située à proximité des vignes en appellation village Vosne Romanée, dans le sud de l\'appellation, Bourgogne rouge 2024 est une pépite de la côte de Nuits, à moins de 20 € !\", \"fr\": \"Cuvée très spécifique, ce Bourgogne est issu de la parcelle \\\"Les Champs d\'Argent\\\", située à proximité des vignes en appellation village Vosne Romanée, dans le sud de l\'appellation, Bourgogne rouge 2024 est une pépite de la côte de Nuits, à moins de 20 € !\", \"pt\": \"Cuvée très spécifique, ce Bourgogne est issu de la parcelle \\\"Les Champs d\'Argent\\\", située à proximité des vignes en appellation village Vosne Romanée, dans le sud de l\'appellation, Bourgogne rouge 2024 est une pépite de la côte de Nuits, à moins de 20 € !\"}','{\"de\": \"Rouge 75cl\", \"en\": \"Rouge 75cl\", \"es\": \"Rouge 75cl\", \"fr\": \"Rouge 75cl\", \"pt\": \"Rouge 75cl\"}');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reports`
--

DROP TABLE IF EXISTS `reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `summary` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reports`
--

LOCK TABLES `reports` WRITE;
/*!40000 ALTER TABLE `reports` DISABLE KEYS */;
INSERT INTO `reports` VALUES (1,'Rapport des Ventes','Analyse des ventes mensuelles',NULL,'2026-01-12 10:42:21','2026-01-12 10:42:21'),(2,'Rapport des Stocks','État des stocks actuels',NULL,'2026-01-12 10:42:21','2026-01-12 10:42:21'),(3,'Rapport des Achats','Analyse des achats fournisseurs',NULL,'2026-02-18 11:02:27','2026-02-18 11:02:27'),(4,'Rapport Marketplace','Performance des vendeurs marketplace',NULL,'2026-03-06 12:03:23','2026-03-06 12:03:23');
/*!40000 ALTER TABLE `reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('XB8JrNVJSaVIByHuA0Zmzo0M4oXtmPeeS6Wg6lz8',4,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36','YTo2OntzOjY6Il90b2tlbiI7czo0MDoiZEpjckRWNlluSnJqZnI2M2NSYzFnemJFbHNNRTlzanlyMkI4YXhDUyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI2OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvc2hvcCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjQ7czo2OiJsb2NhbGUiO3M6NToicHRfQlIiO30=',1772885938);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stocks`
--

DROP TABLE IF EXISTS `stocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stocks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `report_id` bigint unsigned DEFAULT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stocks_product_id_foreign` (`product_id`),
  KEY `stocks_report_id_foreign` (`report_id`),
  CONSTRAINT `stocks_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stocks_report_id_foreign` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stocks`
--

LOCK TABLES `stocks` WRITE;
/*!40000 ALTER TABLE `stocks` DISABLE KEYS */;
INSERT INTO `stocks` VALUES (2,2,2,7,'2026-02-17 13:08:05','2026-03-03 15:52:06'),(3,2,3,7,'2026-02-17 13:08:05','2026-02-18 10:55:33'),(4,2,4,9,'2026-02-17 13:08:05','2026-02-18 14:42:18'),(5,NULL,5,45,'2026-02-19 07:04:01','2026-02-19 07:04:01'),(6,NULL,6,31,'2026-02-19 07:04:01','2026-02-25 14:56:24'),(7,NULL,7,120,'2026-02-19 07:04:01','2026-02-19 07:04:01'),(8,NULL,8,38,'2026-02-19 07:04:01','2026-02-19 07:04:01'),(9,NULL,9,75,'2026-02-19 07:04:01','2026-02-19 07:04:01'),(10,NULL,10,42,'2026-02-19 07:04:01','2026-02-19 07:04:01'),(11,NULL,11,28,'2026-02-19 07:04:01','2026-02-19 07:04:01'),(12,NULL,12,12,'2026-02-19 07:04:01','2026-02-19 07:04:01'),(13,NULL,13,22,'2026-02-19 07:04:01','2026-02-27 17:33:03'),(14,NULL,14,30,'2026-02-19 07:04:01','2026-02-19 07:04:01'),(15,2,17,4,'2026-02-23 10:18:46','2026-02-25 20:39:23'),(16,NULL,18,50,'2026-02-23 10:19:57','2026-02-23 10:19:57'),(19,NULL,21,17,'2026-02-23 11:10:23','2026-02-23 11:12:26'),(20,2,15,3,'2026-02-23 12:18:02','2026-03-05 10:01:36'),(23,2,26,10,'2026-03-05 09:43:17','2026-03-06 11:55:08'),(24,NULL,27,13,'2026-03-05 09:56:08','2026-03-05 09:56:08'),(25,2,28,10,'2026-03-05 09:58:09','2026-03-06 11:55:08'),(26,NULL,29,16,'2026-03-05 09:59:47','2026-03-05 09:59:47');
/*!40000 ALTER TABLE `stocks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `suppliers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `telephone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_terms` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_preferred` tinyint(1) NOT NULL DEFAULT '0',
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suppliers`
--

LOCK TABLES `suppliers` WRITE;
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;
INSERT INTO `suppliers` VALUES (1,'LeroyMerlu','leroy@hotmail.com',1,NULL,'+33453621478','1 rue de la chappe','Bourges','18000','France',NULL,NULL,'immediate',0,NULL,'2026-01-12 10:43:04','2026-02-27 11:33:57'),(2,'AnimalLand','animalland@gmail.com',1,NULL,'0453621478','1 rue du Rivoli','Paris','75000','France','http://www.animalland.com','dvqvqdvqevqevqe','immediate',0,NULL,'2026-02-17 11:49:34','2026-03-06 10:35:35'),(3,'Lemaitre Moreno SA','guillon.vincent@boyer.org',1,'Harum a rerum est ut facilis cumque cupiditate.','02 39 94 72 52','chemin Leroy','Francois','41230','Anguilla','http://pinto.net/debitis-omnis-error-minus-facere','ld642529214','15_days',0,NULL,'2025-07-26 17:25:14','2026-03-06 13:29:44'),(4,'Delaunay','oceane51@delaunay.fr',1,NULL,'+33 9 31 90 04 35','46, rue de Boucher','Dupre','91667','Samoa','http://www.durand.com/quisquam-enim-sed-debitis-error','qn666274434','immediate',1,NULL,'2024-12-23 14:59:09','2026-03-06 13:29:44'),(5,'Leclercq Ribeiro S.A.R.L.','bodin.suzanne@fernandes.com',1,NULL,'0824019399','77, avenue Roux','Rodrigues','57062','Belgique','http://www.besnard.org/laboriosam-veniam-tempora-dolorum-sit-quia.html','kv762798689','15_days',0,NULL,'2025-02-19 03:19:37','2026-03-06 13:29:44'),(6,'Jacques S.A.R.L.','flesage@aubert.com',1,NULL,'09 42 64 07 86','chemin Margaret Mathieu','Faivre-les-Bains','44184','Ethiopie','http://www.peron.com/','cu615676005','45_days',1,NULL,'2024-12-06 17:16:46','2026-03-06 13:29:44'),(7,'Normand Ferrand SARL','dijoux.olivie@arnaud.org',1,'Odio minus et magnam pariatur sint quia accusamus laudantium.','+33 1 83 19 93 98','360, impasse de Guillon','Diaz','27929','Thailande','https://www.valette.net/natus-sint-numquam-corrupti-delectus-ut-vel-quibusdam','hg701914689','60_days',1,NULL,'2024-08-09 00:46:45','2026-03-06 13:29:44');
/*!40000 ALTER TABLE `suppliers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `report_id` bigint unsigned DEFAULT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(8,2) NOT NULL DEFAULT '0.00',
  `type` enum('entry','exit') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `order_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transactions_product_id_foreign` (`product_id`),
  KEY `transactions_report_id_foreign` (`report_id`),
  KEY `transactions_order_id_foreign` (`order_id`),
  KEY `transactions_user_id_foreign` (`user_id`),
  CONSTRAINT `transactions_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `transactions_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transactions_report_id_foreign` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
INSERT INTO `transactions` VALUES (1,1,2,2,32.00,'exit','2026-02-17 13:24:14','2026-02-17 13:24:36',NULL,NULL),(2,1,4,1,21.00,'exit','2026-02-17 13:32:22','2026-02-17 13:40:50',NULL,NULL),(3,1,3,1,245.00,'exit','2026-02-17 16:04:16','2026-02-17 22:24:55',NULL,NULL),(4,1,3,1,245.00,'exit','2026-02-17 16:05:51','2026-02-17 22:24:55',NULL,NULL),(5,1,4,1,21.00,'exit','2026-02-17 16:50:53','2026-02-17 22:24:55',NULL,NULL),(6,1,2,1,32.00,'exit','2026-02-17 22:43:31','2026-02-18 09:07:58',NULL,NULL),(11,1,4,1,21.00,'exit','2026-02-18 14:42:18','2026-02-18 21:31:41',16,NULL),(12,1,2,1,32.00,'exit','2026-02-18 15:42:00','2026-02-18 21:31:41',17,3),(13,1,2,1,32.00,'exit','2026-02-18 15:47:11','2026-02-18 21:31:41',18,3),(14,1,13,2,29.90,'exit','2026-02-23 11:41:08','2026-02-25 20:39:23',20,2),(15,1,15,1,12.00,'exit','2026-02-23 12:18:02','2026-02-25 20:39:23',21,2),(16,1,2,1,32.00,'exit','2026-02-25 14:51:10','2026-02-25 20:39:23',23,2),(17,1,6,1,47.50,'exit','2026-02-25 14:56:24','2026-02-25 20:39:23',24,2),(18,1,17,1,129.99,'exit','2026-02-25 14:57:25','2026-02-25 20:39:23',25,2),(20,1,15,1,12.00,'exit','2026-02-25 15:22:22','2026-02-25 20:39:23',27,2),(21,1,13,1,29.90,'exit','2026-02-27 17:33:03','2026-02-27 17:34:51',29,1),(22,1,15,4,12.00,'exit','2026-02-27 17:33:03','2026-02-27 17:34:51',29,1),(23,1,15,1,12.00,'exit','2026-02-28 09:12:47','2026-02-28 12:52:16',30,2);
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `profile_photo_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin','admin@example.com',NULL,'$2y$12$WeHrCeVMceXrkPgSsNV48uH3.gDbOL1A8AqrHhDQLmoEOH48uLOS2',NULL,'2026-01-12 10:41:07','2026-02-18 21:05:15','profile-photos/GqLS7Eld8piRSoyt20ygHthInEZIOi3BbyZ8iI2w.jpg','admin'),(2,'chico','chico@gmail.com',NULL,'$2y$12$6tZa6NY7MRGmJ1qJhXblvOAtnXjqjiaihJB9q012dUlymonk4bkj2',NULL,'2026-02-17 13:55:52','2026-02-17 13:55:52',NULL,'user'),(3,'Raphael','untigre1986@outlook.fr',NULL,'$2y$12$UK.h.trdHqH0prk76w2Wde5lg5m6yfRCyjiaKoae9NKYbY63/Jthq',NULL,'2026-02-18 15:36:51','2026-02-18 15:36:51',NULL,'user'),(4,'jean','jean@gmail.com',NULL,'$2y$12$B7ULyXEFEQfTPPw/c7ORQuqF/ooctJ1s1vskMcbb7eghvv67n4/4K',NULL,'2026-02-20 08:34:11','2026-02-20 09:31:00',NULL,'vendor'),(5,'Vendeur Test','vendeur@test.com',NULL,'$2y$12$dxqvXpW0yf/XiCb/mqaCx.qv1Lzpw4at.d5FIa2ZA9qdHCTEOLiVi',NULL,'2026-02-23 13:18:00','2026-02-23 13:18:00',NULL,'user'),(6,'Test User','test@example.com','2026-02-25 11:43:56','$2y$12$Ve92Q1.eu/dsjXiPvp7mluEEIjEK759d3zo4oGWAMhKpBHhdUVR/C','5dXXjN1XVE','2026-02-25 11:43:56','2026-02-25 11:43:56',NULL,'user'),(8,'jeanneduBarril','jeanne@gmail.com',NULL,'$2y$12$ha8htssMzMubHKYiudJ19uHJiwiYOPpRLDC4OemNb7ilItm4MJM.G',NULL,'2026-03-03 17:30:05','2026-03-03 17:44:06',NULL,'vendor'),(9,'Manon Jacques','alphonse.pinto@example.org','2026-03-06 13:29:44','$2y$12$fzpnRZW.dcL0S6KIb669m.wjuS4NEf1IheIyPIx.DTUOwXsnJY3n.','B3SaWJ2A6G','2026-03-06 13:29:45','2026-03-06 13:29:45',NULL,'admin'),(10,'Véronique-Victoire Brun','agathe.marion@example.org','2026-03-06 13:29:45','$2y$12$fzpnRZW.dcL0S6KIb669m.wjuS4NEf1IheIyPIx.DTUOwXsnJY3n.','XddgMJWfhs','2026-03-06 13:29:45','2026-03-06 13:29:45',NULL,'admin'),(11,'Zacharie Faivre','william.garcia@example.com','2026-03-06 13:29:45','$2y$12$fzpnRZW.dcL0S6KIb669m.wjuS4NEf1IheIyPIx.DTUOwXsnJY3n.','5oWMO44QeL','2026-03-06 13:29:45','2026-03-06 13:29:45',NULL,'admin'),(12,'Nicolas Perez','timothee33@example.net','2026-03-06 13:29:45','$2y$12$fzpnRZW.dcL0S6KIb669m.wjuS4NEf1IheIyPIx.DTUOwXsnJY3n.','HsVMd3aJvq','2026-03-06 13:29:45','2026-03-06 13:29:45',NULL,'admin'),(13,'Clémence Ribeiro','eleonore12@example.net','2026-03-06 13:29:45','$2y$12$fzpnRZW.dcL0S6KIb669m.wjuS4NEf1IheIyPIx.DTUOwXsnJY3n.','v1ajBslbFo','2026-03-06 13:29:45','2026-03-06 13:29:45',NULL,'user'),(14,'Charlotte Becker','margaux.lejeune@example.org','2026-03-06 15:59:01','$2y$12$AiAsjRwcO8PrRg35j.5HTuviwO3Hz9ujyp8gxeTFYWpJYLy6XD3dy','xgpLse6GKu','2026-03-06 15:59:01','2026-03-06 15:59:01',NULL,'vendor'),(15,'Raymond Ruiz','dupuy.alain@example.com','2026-03-06 15:59:01','$2y$12$AiAsjRwcO8PrRg35j.5HTuviwO3Hz9ujyp8gxeTFYWpJYLy6XD3dy','Non4IUfMQt','2026-03-06 15:59:01','2026-03-06 15:59:01',NULL,'vendor'),(16,'Amélie Delmas','hardy.henri@example.org','2026-03-06 15:59:01','$2y$12$AiAsjRwcO8PrRg35j.5HTuviwO3Hz9ujyp8gxeTFYWpJYLy6XD3dy','oqfzMWy0Uf','2026-03-06 15:59:01','2026-03-06 15:59:01',NULL,'vendor'),(17,'Stéphane Richard','dominique64@example.net','2026-03-06 15:59:01','$2y$12$AiAsjRwcO8PrRg35j.5HTuviwO3Hz9ujyp8gxeTFYWpJYLy6XD3dy','86uhKGwTd7','2026-03-06 15:59:01','2026-03-06 15:59:01',NULL,'vendor'),(18,'Yves-Alfred Marchal','camille43@example.net','2026-03-06 15:59:01','$2y$12$AiAsjRwcO8PrRg35j.5HTuviwO3Hz9ujyp8gxeTFYWpJYLy6XD3dy','NAvjMvZgkr','2026-03-06 15:59:01','2026-03-06 15:59:01',NULL,'vendor');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vendor_webhooks`
--

DROP TABLE IF EXISTS `vendor_webhooks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vendor_webhooks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint unsigned NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `events` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vendor_webhooks_vendor_id_foreign` (`vendor_id`),
  CONSTRAINT `vendor_webhooks_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendor_webhooks`
--

LOCK TABLES `vendor_webhooks` WRITE;
/*!40000 ALTER TABLE `vendor_webhooks` DISABLE KEYS */;
/*!40000 ALTER TABLE `vendor_webhooks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vendors`
--

DROP TABLE IF EXISTS `vendors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vendors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `shop_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banner` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `commission_rate` decimal(5,2) NOT NULL DEFAULT '10.00',
  `status` enum('pending','active','suspended','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vendors_shop_name_unique` (`shop_name`),
  UNIQUE KEY `vendors_slug_unique` (`slug`),
  KEY `vendors_user_id_foreign` (`user_id`),
  KEY `vendors_status_approved_at_index` (`status`,`approved_at`),
  CONSTRAINT `vendors_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendors`
--

LOCK TABLES `vendors` WRITE;
/*!40000 ALTER TABLE `vendors` DISABLE KEYS */;
INSERT INTO `vendors` VALUES (2,4,'JeanDelaCave','jeandelacave','Caviste de vin de bourgogne','vendors/logos/VkLGjlmFxTUaSJz37g4BlG2zjPTibSW0YdxhiZ3X.jpg',NULL,'1 rue de la Chappe','Paris','75001','France','0663417598','kdnveknegrgiozrg\'gz\'','sdce;,c ekjcdfbsfdbsfbsvbnekjce',NULL,10.00,'active',NULL,'2026-02-20 09:31:00','2026-02-20 09:23:52','2026-03-06 21:47:47'),(3,8,'JeanneduBarril','jeannedubarril','Boutique de vetement prêt à porter',NULL,NULL,'5 rue fontVieille','Rognes','13430','France','0705428597','69649+41695+9+94+9+592','16945969+46+496334',NULL,10.00,'active',NULL,'2026-03-03 17:44:06','2026-03-03 17:33:25','2026-03-03 17:44:06'),(4,14,'Lacombe','lacombe-69ab07d598e7b','Cumque sit autem tempore incidunt quod nam voluptas qui. Qui tenetur possimus perspiciatis rerum maxime provident aspernatur. Id ducimus explicabo assumenda at cumque saepe laborum ducimus.',NULL,NULL,'rue Valentin','Louis','44232','Saint Pierre et Miquelon','0785710684','xz137006651','FR3154601848627CG60FLL5I236','http://pichon.com/sed-maiores-nostrum-saepe-voluptas-ipsum-magnam-voluptas',5.87,'rejected',NULL,'2025-11-22 00:36:02','2025-06-08 11:05:07','2026-03-06 15:59:01'),(5,15,'Collet','collet-69ab07d59c01c','Quam a commodi eum excepturi praesentium laborum. Pariatur dicta possimus laboriosam animi alias. Nam harum dolore repellendus iste omnis.',NULL,NULL,'10, rue Morel','Alves','72705','Marshall (Îles)','0525161735','aw372449001','FR397296323653QIHFMS6DA9X93','http://lebreton.org/in-repellendus-maiores-soluta-est-saepe-laudantium',14.90,'active',NULL,'2025-12-11 17:31:33','2025-12-26 07:45:41','2026-03-06 15:59:01'),(6,16,'Pires SA','pires-sa-69ab07d59d6c7','Aut ipsam tempore voluptatibus aut. Aut quam voluptatem sunt quae. Adipisci nisi dicta velit minima.',NULL,NULL,'3, rue Levy','Guyon','57567','Thailande','0900095267','jt527505347','FR88368834266102I1Z78ICWK23','http://www.lemoine.com/aut-molestias-est-quis-sunt-natus',5.09,'rejected',NULL,'2026-01-28 16:48:05','2025-08-05 12:46:46','2026-03-06 15:59:01'),(7,17,'Guichard','guichard-69ab07d59e979','Est ut nulla hic id nihil corporis soluta. Iste corrupti qui velit autem. Enim vitae tempore inventore aut totam in. Ut quo cupiditate veritatis quo dolorum rerum eum.',NULL,NULL,'rue Paul Bailly','Lemaitre','53897','Malawi','0407436256','gy959772611','FR2661189626208WGS604U72A58','http://www.mace.com/',9.63,'active',NULL,'2025-10-31 06:01:40','2025-10-18 05:19:52','2026-03-06 15:59:01'),(8,18,'Daniel','daniel-69ab07d59fd0c','Autem corporis error inventore ipsum unde iusto aspernatur. Nam eaque quibusdam sequi voluptatem vitae. Quia id et totam nihil dolorem numquam. Incidunt dolor blanditiis tempore aut.',NULL,NULL,'22, chemin Théodore Marques','Germaindan','65251','Afrique du sud','09 61 10 30 05','pt541831065','FR234453147718JFER63B312D67','http://www.teixeira.org/',12.18,'suspended',NULL,NULL,'2025-09-22 14:07:20','2026-03-06 15:59:01');
/*!40000 ALTER TABLE `vendors` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-03-09 10:23:18
