-- MySQL dump 10.13  Distrib 8.0.40, for Linux (x86_64)
--
-- Host: localhost    Database: employee_management
-- ------------------------------------------------------
-- Server version	8.0.40-0ubuntu0.22.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employees` (
  `employee_id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `department` varchar(50) NOT NULL,
  `dob` date NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `grade` char(1) NOT NULL,
  `vehicle` varchar(20) NOT NULL,
  `joining_date` date NOT NULL,
  `basic_salary` decimal(10,2) NOT NULL,
  PRIMARY KEY (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees`
--

LOCK TABLES `employees` WRITE;
/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
INSERT INTO `employees` VALUES (101,'Parikshit Sharma','Developer','1999-04-04','Male','A','Two Wheeler','2023-03-03',18000.00),(102,'Paras Thakur','Sales','1992-05-18','Male','B','Four Wheeler','2018-03-12',5000.00),(103,'Tijender Thakur','Developer','1998-04-02','Male','A','Two Wheeler','2020-09-09',15000.00),(104,'Tonisha Thakur','Admin','1991-07-09','Female','B','Four Wheeler','2017-11-30',13000.00),(105,'a','Developer','2000-03-02','Male','A','Two Wheeler','2023-03-03',500.00),(106,'Rupali Thakur','Developer','2000-04-03','Female','B','Two Wheeler','2021-02-03',800000.00),(107,'Vivek Sharma','Developer','2000-02-02','Male','A','Four Wheeler','2023-02-02',25000.00),(108,'b','Admin','1222-02-02','Male','C','Two Wheeler','1232-02-20',525.00),(109,'Tijender','Sales','2018-10-30','Male','C','Four Wheeler','2024-01-29',5000.00),(110,'test','Admin','2007-01-27','Female','A','Two Wheeler','2023-01-27',5000.00),(111,'c','Sales','2000-01-01','Female','A','Four Wheeler','2023-02-02',5000.00);
/*!40000 ALTER TABLE `employees` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-02-01 13:50:27
