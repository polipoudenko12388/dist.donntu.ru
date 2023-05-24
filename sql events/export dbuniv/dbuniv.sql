CREATE DATABASE  IF NOT EXISTS `dbuniversity` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `dbuniversity`;
-- MySQL dump 10.13  Distrib 8.0.25, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: dbuniversity
-- ------------------------------------------------------
-- Server version	8.0.25

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
-- Table structure for table `country`
--

DROP TABLE IF EXISTS `country`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `country` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `country`
--

LOCK TABLES `country` WRITE;
/*!40000 ALTER TABLE `country` DISABLE KEYS */;
INSERT INTO `country` VALUES (1,'Россия'),(3,'Украина');
/*!40000 ALTER TABLE `country` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `department`
--

DROP TABLE IF EXISTS `department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `department` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `department`
--

LOCK TABLES `department` WRITE;
/*!40000 ALTER TABLE `department` DISABLE KEYS */;
INSERT INTO `department` VALUES (1,'автоматизированных систем управления'),(2,'компьютерного моделирования и дизайна'),(8,'английского языка'),(9,'экономической кибернетики'),(10,'прикладной математики и искусственного интеллекта'),(11,'компьютерной инженерии'),(12,'философии'),(13,'программной инженерии им. Л. П. Фельдмана');
/*!40000 ALTER TABLE `department` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `direction`
--

DROP TABLE IF EXISTS `direction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `direction` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_direction` varchar(45) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `profile` varchar(100) DEFAULT NULL,
  `id_educ_prog` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_educ_prog_idx` (`id_educ_prog`),
  CONSTRAINT `id_educ_prog` FOREIGN KEY (`id_educ_prog`) REFERENCES `educationalprogram` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `direction`
--

LOCK TABLES `direction` WRITE;
/*!40000 ALTER TABLE `direction` DISABLE KEYS */;
INSERT INTO `direction` VALUES (1,'09.03.02','Информационные системы и технологии','Информационные системы и технологии в технике и бизнесе',4),(3,'09.03.01','Информатика и вычислительная техника','Автоматизированные системы управления',4),(4,'09.04.02','Информационные системы и технологии','Информационные системы и технологии в технике и бизнесе',3),(5,'09.04.01','Информатика и вычислительная техника','Автоматизированные системы управления',3),(6,'38.03.05','Бизнес-информатика','IT-менеджмент',4),(7,'02.03.01','Математика и компьютерные науки','Компьютерное моделирование и дизайн',4),(8,'09.03.02','Информационные системы и технологии','Информационные системы и технологии в медиаиндустрии и дизайне',4),(9,'09.03.03','Прикладная информатика','Информатика в интелектуальных системах',4),(10,'09.03.04','Программная инженерия',NULL,4);
/*!40000 ALTER TABLE `direction` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `educationalprogram`
--

DROP TABLE IF EXISTS `educationalprogram`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `educationalprogram` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `educationalprogram`
--

LOCK TABLES `educationalprogram` WRITE;
/*!40000 ALTER TABLE `educationalprogram` DISABLE KEYS */;
INSERT INTO `educationalprogram` VALUES (2,'специалитет'),(3,'магистратура'),(4,'бакалавриат');
/*!40000 ALTER TABLE `educationalprogram` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faculty`
--

DROP TABLE IF EXISTS `faculty`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `faculty` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faculty`
--

LOCK TABLES `faculty` WRITE;
/*!40000 ALTER TABLE `faculty` DISABLE KEYS */;
INSERT INTO `faculty` VALUES (7,'Горный'),(8,'Инженерно-экономический'),(9,'Интеллектуальных систем и программирования'),(10,'Информационных систем и технологий'),(11,'Компьютерных информационных технологий и автоматики'),(12,'Металлургии и теплоэнергетики'),(13,'Недропользования и наук о Земле'),(14,'Интегрированных и мехатронных производств'),(15,'Интеллектуальной электроэнергетики и робототехники');
/*!40000 ALTER TABLE `faculty` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `form_education`
--

DROP TABLE IF EXISTS `form_education`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `form_education` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `form_education`
--

LOCK TABLES `form_education` WRITE;
/*!40000 ALTER TABLE `form_education` DISABLE KEYS */;
INSERT INTO `form_education` VALUES (1,'очная'),(2,'заочная'),(3,'очно-заочная');
/*!40000 ALTER TABLE `form_education` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `general_info_group`
--

DROP TABLE IF EXISTS `general_info_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `general_info_group` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_info_ifd` int DEFAULT NULL,
  `id_direction` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_instityte_idx` (`id_info_ifd`),
  KEY `id_direction_idx` (`id_direction`),
  CONSTRAINT `id_direction` FOREIGN KEY (`id_direction`) REFERENCES `direction` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `id_info_ifd` FOREIGN KEY (`id_info_ifd`) REFERENCES `info_inst_facul_depart` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `general_info_group`
--

LOCK TABLES `general_info_group` WRITE;
/*!40000 ALTER TABLE `general_info_group` DISABLE KEYS */;
INSERT INTO `general_info_group` VALUES (1,1,1),(2,1,3),(4,2,7),(5,2,8),(6,4,6),(7,5,9),(8,6,10);
/*!40000 ALTER TABLE `general_info_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `groups` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `id_form_education` int DEFAULT NULL,
  `id_general_info_group` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_form_education_idx` (`id_form_education`),
  KEY `id_general_info_group_idx` (`id_general_info_group`),
  CONSTRAINT `id_form_education` FOREIGN KEY (`id_form_education`) REFERENCES `form_education` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `id_general_info_group` FOREIGN KEY (`id_general_info_group`) REFERENCES `general_info_group` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groups`
--

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
INSERT INTO `groups` VALUES (4,'ИСТ-19а',1,1),(5,'ИСТ-19б',1,1),(6,'АСУ-19',1,2),(7,'БИ-19',1,6),(8,'КМД-19',1,4),(9,'ИСТ-19в',1,5),(10,'ИСТ-19г',1,5),(11,'ИНФ-19',1,7),(12,'ПИ-19',1,8);
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `human`
--

DROP TABLE IF EXISTS `human`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `human` (
  `id` int NOT NULL AUTO_INCREMENT,
  `surname` varchar(45) NOT NULL,
  `name` varchar(45) NOT NULL,
  `patronymic` varchar(45) DEFAULT NULL,
  `datebirth` date NOT NULL,
  `email` varchar(45) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `snils` varchar(11) DEFAULT NULL,
  `inn` varchar(12) DEFAULT NULL,
  `id_placeresidence` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_placeresidence_idx` (`id_placeresidence`),
  CONSTRAINT `id_placeresidence` FOREIGN KEY (`id_placeresidence`) REFERENCES `place_of_residence` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `human`
--

LOCK TABLES `human` WRITE;
/*!40000 ALTER TABLE `human` DISABLE KEYS */;
INSERT INTO `human` VALUES (11,'Белая','Алина','Юрьевна','2001-09-20','email@gmail.com','79491231122',NULL,'12345678911',NULL,11),(14,'Гоик','Георгий','Дмитриевич','2001-10-11','email2@gmail.com','79491231123',NULL,'12345678912',NULL,13),(15,'Гончаров','Данил','Дмитриевич','2001-10-12','email3@gmail.com','79491231124',NULL,'12345678913',NULL,13),(16,'Деркач','Кирилл','Игоревич','2002-11-11','email4@gmail.com','79491231125',NULL,'12345678914',NULL,11),(17,'Лефтеров','Дмитрий','Дмитриевич','2002-03-16','email5@gmail.com','79491231126',NULL,'12345678915',NULL,11),(18,'Пойденко','Полина','Александровна','2001-12-29','email6@gmail.com','79491231127','photohuman/id18poudenko.jpg',NULL,'123456789151',13),(19,'Рядко','Максим','Алексеевич','2001-12-01','email7@gmail.com','79491231128',NULL,NULL,'123456789152',13),(20,'Савенкова','Валерия','Олеговна','2002-08-11','email8@gmail.com','79491231129',NULL,NULL,'123456789153',11),(21,'Слободяник','Артем','Игоревич','2002-08-12','email9@gmail.com','79491231130',NULL,NULL,'123456789154',11),(22,'Шевченко','Михаил','Владимирович','2002-11-11','email10@gmail.com','79491231131',NULL,NULL,'123456789155',11),(23,'Шестаков','Богдан','Андреевич','2002-11-11','email11@gmail.com','79491231132',NULL,NULL,'123456789156',11),(24,'Яковченко','Артем','Александрович','2001-01-01','email12@gmail.com','79491231133',NULL,NULL,'123456789157',11),(25,'Агарков','Игорь','Анатольевич','2002-11-11','email13@gmail.com','79491231134',NULL,NULL,'123456789158',11),(26,'Бабичев','Владислав','Александрович','2002-11-12','email14@gmail.com','79491231135',NULL,NULL,'123456789159',13),(27,'Гейвандов','Илья','Романович','2001-01-01','email15@gmail.com','79491231136',NULL,'12345678916',NULL,13),(28,'Карпович','Владимир','Дмитриевич','2001-01-01','email16@gmail.com','79491231137',NULL,'12345678917',NULL,11),(29,'Клюйко','Данил','Андреевич','2001-01-01','email17@gmail.com','79491231138',NULL,'12345678918',NULL,11),(30,'Лисиченко','Виктор','Тимофеевич','2001-01-01','email18@gmail.com','79491231139',NULL,'12345678919',NULL,11),(31,'Моргунов','Владислав','Дмитриевич','2001-01-02','email19@gmail.com','79491231140',NULL,'12345678920',NULL,11),(32,'Шныра','Богдан','Олегович','2001-01-07','email20@gmail.com','79491231141',NULL,'12345678921',NULL,13),(33,'Бражников','Виталий','Андреевич','2001-01-08','email21@gmail.com','79491231142',NULL,'12345678922',NULL,13),(34,'Дворников','Денис','Евгеньевич','2001-01-09','email22@gmail.com','79491231143',NULL,'12345678923',NULL,13),(35,'Дудник','Егор','Витальевич','2001-01-10','email23@gmail.com','79491231144',NULL,'12345678924',NULL,13),(36,'Лунев','Дмитрий','Сергеевич','2001-01-11','email24@gmail.com','79491231145',NULL,'12345678925',NULL,13),(37,'Павленко','Мирослав','Сергеевич','2001-01-12','email25@gmail.com','79491231146',NULL,'12345678926',NULL,13),(38,'Пушкарь','Алексей','Дмитриевич','2001-01-13','email26@gmail.com','79491231147',NULL,'12345678927',NULL,13),(39,'Сыгинь','Леонид','Ярославович','2001-01-14','email27@gmail.com','79491231148',NULL,'12345678928',NULL,11),(40,'Шевцов','Максим','Викторович','2001-01-15','email28@gmail.com','79491231149',NULL,'12345678929',NULL,11),(41,'Яковлев','Марк','Юрьевич','2001-01-16','email29@gmail.com','79491231150',NULL,'12345678930',NULL,11),(42,'Ткаченко','Георгий','Александрович','2001-01-17','email30@gmail.com','79491231151',NULL,'12345678931',NULL,11),(43,'Теплова','Ольга','Валентиновна','1970-01-12','emailteacher1@gmail.com','79491231152','photohuman/id43teplova.jpg','12345678932',NULL,11),(44,'Матях','Ирина','Владимировна','1985-01-12','emailteacher2@gmail.com','79491231153','photohuman/id44matyah.jpg','12345678933',NULL,11),(45,'Поляков','Александр','Иванович','1965-01-12','emailteacher3@gmail.com','79491231154','photohuman/id45polyakov.jpg','12345678934',NULL,11),(46,'Секирин','Александр','Иванович','1970-01-14','emailteacher4@gmail.com','79491231155',NULL,'12345678935',NULL,12),(47,'Савкова','Елена','Осиповна','1960-02-14','emailteacher5@gmail.com','79491231156',NULL,'12345678936',NULL,12),(48,'Землянская','Светлана','Юрьевна','1965-01-12','emailteacher6@gmail.com','79491231157','photohuman/id48zemlyanskaya.jpg','12345678937',NULL,12),(49,'Шуватова','Екатерина','Александровна','1985-01-16','emailteacher7@gmail.com','79491231158',NULL,'12345678938',NULL,12),(50,'Новиков','Дмитрий','Дмитриевич','1985-01-17','emailteacher8@gmail.com','79491231159',NULL,'12345678939',NULL,12),(51,'Андриевская','Наталья','Климовна','1970-01-01','emailteacher9@gmail.com','79491231160',NULL,'12345678940',NULL,12),(52,'Мартыненко','Татьяна','Владимировна','1980-01-01','emailteacher10@gmail.com','79491231161',NULL,'12345678941',NULL,12),(53,'Пряхин','Владимир','Викторович','1970-01-01','emailteacher11@gmail.com','79491231162','photohuman/id53pryahin.jpg','12345678942',NULL,12),(54,'Светличная','Виктория','Антоновна','1960-05-05','emailteacher12@gmail.com','79491231163',NULL,'12345678943',NULL,12),(55,'Васяева','Татьяна','Александровна','1970-01-01','emailteacher13@gmail.com','79491231164',NULL,'12345678944',NULL,11);
/*!40000 ALTER TABLE `human` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `info_inst_facul_depart`
--

DROP TABLE IF EXISTS `info_inst_facul_depart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `info_inst_facul_depart` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_institute` int DEFAULT NULL,
  `id_faculty` int NOT NULL,
  `id_department` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_institute_idx` (`id_institute`),
  KEY `id_faculty_idx` (`id_faculty`),
  KEY `id_department_idx` (`id_department`),
  CONSTRAINT `id_department` FOREIGN KEY (`id_department`) REFERENCES `department` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `id_faculty` FOREIGN KEY (`id_faculty`) REFERENCES `faculty` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `id_institute` FOREIGN KEY (`id_institute`) REFERENCES `institute` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `info_inst_facul_depart`
--

LOCK TABLES `info_inst_facul_depart` WRITE;
/*!40000 ALTER TABLE `info_inst_facul_depart` DISABLE KEYS */;
INSERT INTO `info_inst_facul_depart` VALUES (1,2,10,1),(2,2,10,2),(3,2,10,8),(4,2,10,9),(5,2,9,10),(6,2,9,13);
/*!40000 ALTER TABLE `info_inst_facul_depart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `institute`
--

DROP TABLE IF EXISTS `institute`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `institute` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `institute`
--

LOCK TABLES `institute` WRITE;
/*!40000 ALTER TABLE `institute` DISABLE KEYS */;
INSERT INTO `institute` VALUES (1,'Горного дела и геологии'),(2,'Компьютерных наук и технологий'),(3,'Последипломного образования'),(4,'Автомобильно-дорожный'),(5,'Инновационных технологий заочного обучения');
/*!40000 ALTER TABLE `institute` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `names_of_settlements`
--

DROP TABLE IF EXISTS `names_of_settlements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `names_of_settlements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `names_of_settlements`
--

LOCK TABLES `names_of_settlements` WRITE;
/*!40000 ALTER TABLE `names_of_settlements` DISABLE KEYS */;
INSERT INTO `names_of_settlements` VALUES (2,'Снежное'),(4,'Докучаевск'),(5,'Макеевка'),(6,'Харцызск'),(7,'Донецк'),(9,'Горловка'),(10,'Ростов-на-Дону');
/*!40000 ALTER TABLE `names_of_settlements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `place_of_residence`
--

DROP TABLE IF EXISTS `place_of_residence`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `place_of_residence` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_country` int NOT NULL,
  `id_typesregions` int NOT NULL,
  `id_regions` int DEFAULT NULL,
  `id_typesettlements` int DEFAULT NULL,
  `id_namesettlements` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_country_idx` (`id_country`),
  KEY `id_regions_idx` (`id_regions`),
  KEY `id_typesettlements_idx` (`id_typesettlements`),
  KEY `id_namesettlements_idx` (`id_namesettlements`) /*!80000 INVISIBLE */,
  KEY `id_typesregions_idx` (`id_typesregions`),
  CONSTRAINT `` FOREIGN KEY (`id_namesettlements`) REFERENCES `names_of_settlements` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `id_country` FOREIGN KEY (`id_country`) REFERENCES `country` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `id_regions` FOREIGN KEY (`id_regions`) REFERENCES `regions` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `id_typesettlements` FOREIGN KEY (`id_typesettlements`) REFERENCES `types_of_settlements` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `id_typesregions` FOREIGN KEY (`id_typesregions`) REFERENCES `types_of_regions` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `place_of_residence`
--

LOCK TABLES `place_of_residence` WRITE;
/*!40000 ALTER TABLE `place_of_residence` DISABLE KEYS */;
INSERT INTO `place_of_residence` VALUES (11,1,2,8,1,5),(12,1,2,8,1,4),(13,1,2,8,1,7);
/*!40000 ALTER TABLE `place_of_residence` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `position`
--

DROP TABLE IF EXISTS `position`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `position` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `position`
--

LOCK TABLES `position` WRITE;
/*!40000 ALTER TABLE `position` DISABLE KEYS */;
INSERT INTO `position` VALUES (2,'ассистент'),(4,'доцент'),(7,'преподаватель'),(8,'профессор'),(9,'старший преподаватель');
/*!40000 ALTER TABLE `position` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `regions`
--

DROP TABLE IF EXISTS `regions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `regions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `regions`
--

LOCK TABLES `regions` WRITE;
/*!40000 ALTER TABLE `regions` DISABLE KEYS */;
INSERT INTO `regions` VALUES (1,'Амурская'),(2,'Архангельская'),(3,'Белгородская'),(4,'Брянская'),(5,'Иркутская'),(6,'Ростовская'),(7,'Московская'),(8,'Донецкая Народная'),(9,'Луганская Народная'),(10,'Чувашская');
/*!40000 ALTER TABLE `regions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role`
--

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` VALUES (1,'сотрудник'),(2,'преподаватель');
/*!40000 ALTER TABLE `role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `status`
--

DROP TABLE IF EXISTS `status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `status` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `status`
--

LOCK TABLES `status` WRITE;
/*!40000 ALTER TABLE `status` DISABLE KEYS */;
INSERT INTO `status` VALUES (1,'числится'),(2,'отчислен'),(3,'закончил'),(4,'переведен'),(5,'работает'),(6,'уволен');
/*!40000 ALTER TABLE `status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student`
--

DROP TABLE IF EXISTS `student`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `student` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_human` int NOT NULL,
  `id_group` int NOT NULL,
  `data_start_education` date NOT NULL,
  `data_end_education` date DEFAULT NULL,
  `id_status` int NOT NULL,
  `data_transfer_expulsion` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_human_idx` (`id_human`),
  KEY `id_group_idx` (`id_group`),
  KEY `id_status_idx` (`id_status`),
  CONSTRAINT `id_group` FOREIGN KEY (`id_group`) REFERENCES `groups` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `id_human` FOREIGN KEY (`id_human`) REFERENCES `human` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `id_status` FOREIGN KEY (`id_status`) REFERENCES `status` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student`
--

LOCK TABLES `student` WRITE;
/*!40000 ALTER TABLE `student` DISABLE KEYS */;
INSERT INTO `student` VALUES (7,11,4,'2019-07-29',NULL,1,NULL),(8,14,4,'2019-07-29',NULL,1,NULL),(9,15,4,'2019-07-29',NULL,1,NULL),(10,16,4,'2019-07-29',NULL,1,NULL),(11,17,4,'2019-07-29',NULL,1,NULL),(12,18,4,'2019-07-29',NULL,1,NULL),(13,19,4,'2019-07-29',NULL,1,NULL),(14,20,4,'2019-07-29',NULL,1,NULL),(15,21,4,'2019-07-29',NULL,2,'2021-07-29'),(16,22,4,'2019-07-29',NULL,1,NULL),(17,23,4,'2019-07-29',NULL,1,NULL),(18,24,4,'2019-07-29',NULL,1,NULL),(19,25,5,'2019-07-29',NULL,1,NULL),(20,26,5,'2019-07-29',NULL,1,NULL),(21,27,5,'2019-07-29',NULL,1,NULL),(22,28,5,'2019-07-29',NULL,1,NULL),(23,29,5,'2019-07-29',NULL,2,'2022-11-29'),(24,30,5,'2019-07-29',NULL,1,NULL),(25,31,5,'2019-07-29',NULL,1,NULL),(26,32,5,'2019-07-29',NULL,1,NULL),(27,33,6,'2019-07-29',NULL,1,NULL),(28,34,6,'2019-07-29',NULL,1,NULL),(29,35,6,'2019-07-29',NULL,1,NULL),(30,36,6,'2019-07-29',NULL,1,NULL),(31,37,6,'2019-07-29',NULL,1,NULL),(32,38,6,'2019-07-29',NULL,1,NULL),(33,39,6,'2019-07-29',NULL,1,NULL),(34,40,6,'2019-07-29',NULL,1,NULL),(35,41,6,'2019-07-29',NULL,1,NULL),(36,42,6,'2019-07-29',NULL,1,NULL);
/*!40000 ALTER TABLE `student` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `types_of_regions`
--

DROP TABLE IF EXISTS `types_of_regions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `types_of_regions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `types_of_regions`
--

LOCK TABLES `types_of_regions` WRITE;
/*!40000 ALTER TABLE `types_of_regions` DISABLE KEYS */;
INSERT INTO `types_of_regions` VALUES (1,'область'),(2,'республика'),(3,'автономная область'),(4,'автономный округ'),(5,'край'),(6,'город федерального значения');
/*!40000 ALTER TABLE `types_of_regions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `types_of_settlements`
--

DROP TABLE IF EXISTS `types_of_settlements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `types_of_settlements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `types_of_settlements`
--

LOCK TABLES `types_of_settlements` WRITE;
/*!40000 ALTER TABLE `types_of_settlements` DISABLE KEYS */;
INSERT INTO `types_of_settlements` VALUES (1,'город'),(2,'деревня'),(3,'поселок'),(4,'поселок городского типа'),(5,'село'),(6,'хутор'),(7,'пригород'),(8,'станция');
/*!40000 ALTER TABLE `types_of_settlements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `workes`
--

DROP TABLE IF EXISTS `workes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `workes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_human` int DEFAULT NULL,
  `id_role` int DEFAULT NULL,
  `id_position` int DEFAULT NULL,
  `id_status_workes` int DEFAULT '5',
  `id_info_ifd_x` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_role_idx` (`id_role`),
  KEY `id_position_idx` (`id_position`),
  KEY `id_status_idx` (`id_status_workes`),
  KEY `id_human_idx` (`id_human`),
  KEY `id_status_workes_idx` (`id_info_ifd_x`),
  CONSTRAINT `id_human_x` FOREIGN KEY (`id_human`) REFERENCES `human` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `id_position` FOREIGN KEY (`id_position`) REFERENCES `position` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `id_role` FOREIGN KEY (`id_role`) REFERENCES `role` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `id_status_workes` FOREIGN KEY (`id_status_workes`) REFERENCES `status` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workes`
--

LOCK TABLES `workes` WRITE;
/*!40000 ALTER TABLE `workes` DISABLE KEYS */;
INSERT INTO `workes` VALUES (7,43,2,9,5,1),(8,44,2,2,5,1),(9,45,2,9,5,1),(10,46,2,4,5,1),(11,47,2,4,5,1),(12,48,2,4,5,1),(13,49,2,2,5,1),(14,50,2,9,5,1),(15,46,2,4,5,2),(16,43,2,9,5,2),(17,51,2,9,5,1),(18,52,2,4,5,1),(19,53,2,2,5,1),(20,54,2,4,5,1),(21,55,2,4,5,1);
/*!40000 ALTER TABLE `workes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'dbuniversity'
--
/*!50106 SET @save_time_zone= @@TIME_ZONE */ ;
/*!50106 DROP EVENT IF EXISTS `DeleteOldRecordTokens` */;
DELIMITER ;;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;;
/*!50003 SET character_set_client  = utf8mb4 */ ;;
/*!50003 SET character_set_results = utf8mb4 */ ;;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;;
/*!50003 SET @saved_time_zone      = @@time_zone */ ;;
/*!50003 SET time_zone             = 'SYSTEM' */ ;;
/*!50106 CREATE*/ /*!50117 DEFINER=`root`@`localhost`*/ /*!50106 EVENT `DeleteOldRecordTokens` ON SCHEDULE EVERY 1 WEEK STARTS '2023-05-09 17:04:22' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
	DELETE FROM `dbwebsite_university`.`tokens_authorization` WHERE `id` in
	(SELECT id FROM 
	(SELECT id FROM `dbwebsite_university`.`tokens_authorization`
	WHERE TIMESTAMPDIFF(MONTH, `tokens_authorization`.`data_save_note`, CURDATE())>=1)temptable) 
    LIMIT 100;
END */ ;;
/*!50003 SET time_zone             = @saved_time_zone */ ;;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;;
/*!50003 SET character_set_client  = @saved_cs_client */ ;;
/*!50003 SET character_set_results = @saved_cs_results */ ;;
/*!50003 SET collation_connection  = @saved_col_connection */ ;;
DELIMITER ;
/*!50106 SET TIME_ZONE= @save_time_zone */ ;

--
-- Dumping routines for database 'dbuniversity'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-05-18  0:02:50
