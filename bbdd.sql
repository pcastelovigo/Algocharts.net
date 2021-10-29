/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nombres` (
  `asset_id` int NOT NULL,
  `nombre` varchar(32) NOT NULL,
  `unidad` varchar(8) DEFAULT NULL,
  `url` varchar(32) DEFAULT NULL,
  `cantidad` bigint unsigned DEFAULT NULL,
  `decimales` int DEFAULT NULL,
  `verify` varchar(1) DEFAULT '',
  PRIMARY KEY (`asset_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pares` (
  `id` int NOT NULL AUTO_INCREMENT,
  `assetin` int NOT NULL,
  `assetout` int NOT NULL,
  `nombre` varchar(32) DEFAULT NULL,
  `verify` varchar(1) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1453 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
