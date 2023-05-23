CREATE DATABASE  IF NOT EXISTS `dbwebsite_university` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `dbwebsite_university`;
-- MySQL dump 10.13  Distrib 8.0.25, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: dbwebsite_university
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
-- Table structure for table `discipline_flow`
--

DROP TABLE IF EXISTS `discipline_flow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `discipline_flow` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_list_discipline` int NOT NULL,
  `number_hours_reading` int DEFAULT NULL,
  `id_flow` int DEFAULT NULL,
  `folder` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_list_discipline_idx` (`id_list_discipline`),
  KEY `id_flow_idx` (`id_flow`),
  CONSTRAINT `id_flowfk` FOREIGN KEY (`id_flow`) REFERENCES `flow` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `id_list_discipline` FOREIGN KEY (`id_list_discipline`) REFERENCES `list_disciplines` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `discipline_flow`
--

LOCK TABLES `discipline_flow` WRITE;
/*!40000 ALTER TABLE `discipline_flow` DISABLE KEYS */;
INSERT INTO `discipline_flow` VALUES (22,23,200,1,'disciplines/id23Disciplina1_20230517_160849/id22Potok1_20230517_162857'),(25,24,256,4,'disciplines/id24Disciplina2_20230517_160930/id25Potok3_20230517_163430'),(26,24,256,5,'disciplines/id24Disciplina2_20230517_160930/id26Potok4_20230517_163504'),(27,25,256,6,'disciplines/id25Disciplina3_20230517_161009/id27Potok5_20230517_163656'),(28,23,256,5,'disciplines/id23Disciplina1_20230517_160849/id28Potok4_20230517_164955'),(29,23,256,7,'disciplines/id23Disciplina1_20230517_160849/id29Potok6_20230517_165012'),(30,26,256,1,'disciplines/id26Disciplina4_20230517_161013/id30Potok1_20230517_165139'),(31,26,200,7,'disciplines/id26Disciplina4_20230517_161013/id31Potok6_20230517_165202'),(32,27,200,4,'disciplines/id27Disciplina5_20230517_161016/id32Potok3_20230517_165304'),(33,28,200,18,'disciplines/id28Disciplina6_20230517_161018/id33Potok7_20230517_165321'),(34,29,200,6,'disciplines/id29Disciplina7_20230517_161034/id34Potok5_20230517_165715');
/*!40000 ALTER TABLE `discipline_flow` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `flow`
--

DROP TABLE IF EXISTS `flow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `flow` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `id_creator` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_creator_idx` (`id_creator`),
  CONSTRAINT `id_creator` FOREIGN KEY (`id_creator`) REFERENCES `teacher` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `flow`
--

LOCK TABLES `flow` WRITE;
/*!40000 ALTER TABLE `flow` DISABLE KEYS */;
INSERT INTO `flow` VALUES (1,'Поток1',9),(3,'Поток2',9),(4,'Поток3',10),(5,'Поток4',10),(6,'Поток5',11),(7,'Поток6',73),(18,'Поток7',74),(19,'Поток8',74),(20,'Поток9',74),(21,'Поток10',75);
/*!40000 ALTER TABLE `flow` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `flow_group`
--

DROP TABLE IF EXISTS `flow_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `flow_group` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_flow` int NOT NULL,
  `id_group` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_flow_idx` (`id_flow`),
  KEY `id_group_idx` (`id_group`),
  CONSTRAINT `id_flow` FOREIGN KEY (`id_flow`) REFERENCES `flow` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `id_groupfk` FOREIGN KEY (`id_group`) REFERENCES `group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `flow_group`
--

LOCK TABLES `flow_group` WRITE;
/*!40000 ALTER TABLE `flow_group` DISABLE KEYS */;
INSERT INTO `flow_group` VALUES (2,1,2),(32,1,3),(33,3,1),(34,3,2),(35,4,1),(36,4,2),(37,4,4),(38,4,5),(41,5,6),(42,5,7),(43,6,3),(44,7,8),(45,7,9),(46,18,1),(47,18,2),(48,18,6),(49,18,7),(50,18,9),(60,1,1),(61,19,4);
/*!40000 ALTER TABLE `flow_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `group`
--

DROP TABLE IF EXISTS `group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `group` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `id_group_db_univ` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_group_db_univ_idx` (`id_group_db_univ`),
  CONSTRAINT `id_group_db_univ` FOREIGN KEY (`id_group_db_univ`) REFERENCES `dbuniversity`.`groups` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `group`
--

LOCK TABLES `group` WRITE;
/*!40000 ALTER TABLE `group` DISABLE KEYS */;
INSERT INTO `group` VALUES (1,'ИСТ-19а',4),(2,'ИСТ-19б',5),(3,'АСУ-19',6),(4,'БИ-19',7),(5,'КМД-19',8),(6,'ИСТ-19в',9),(7,'ИСТ-19г',10),(8,'ИНФ-19',11),(9,'ПИ-19',12);
/*!40000 ALTER TABLE `group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `group_student`
--

DROP TABLE IF EXISTS `group_student`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `group_student` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_student` int DEFAULT NULL,
  `id_group` int DEFAULT NULL,
  `id_student_db_univ` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_student_x_idx` (`id_student`),
  KEY `id_group_idx` (`id_group`),
  KEY `id_student_db_univ_idx` (`id_student_db_univ`),
  CONSTRAINT `id_group` FOREIGN KEY (`id_group`) REFERENCES `group` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `id_student_db_univ` FOREIGN KEY (`id_student_db_univ`) REFERENCES `dbuniversity`.`student` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `id_student_x` FOREIGN KEY (`id_student`) REFERENCES `student` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=120 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `group_student`
--

LOCK TABLES `group_student` WRITE;
/*!40000 ALTER TABLE `group_student` DISABLE KEYS */;
INSERT INTO `group_student` VALUES (1,19,1,7),(2,20,1,8),(3,21,1,9),(4,22,1,10),(5,23,1,11),(6,24,1,12),(7,25,1,13),(8,26,1,14),(9,27,1,15),(10,28,1,16),(11,29,1,17),(12,30,1,18),(13,31,2,19),(14,32,2,20),(15,33,2,21),(16,34,2,22),(17,35,2,23),(18,36,2,24),(19,37,2,25),(20,38,2,26),(21,39,3,27),(22,40,3,28),(23,41,3,29),(24,42,3,30),(25,43,3,31),(26,44,3,32),(27,45,3,33),(28,46,3,34),(29,47,3,35),(30,48,3,36),(119,111,4,37);
/*!40000 ALTER TABLE `group_student` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `list_disciplines`
--

DROP TABLE IF EXISTS `list_disciplines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `list_disciplines` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `id_institute_db_univ` int DEFAULT NULL,
  `id_faculty_db_univ` int DEFAULT NULL,
  `id_department_db_univ` int DEFAULT NULL,
  `id_teacher` int NOT NULL,
  `fon` varchar(255) DEFAULT NULL,
  `folder` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_teacher_idx` (`id_teacher`),
  KEY `id_institute_db_univ_idx` (`id_institute_db_univ`),
  KEY `id_department_db_univ_idx` (`id_department_db_univ`),
  KEY `id_faculty_db_univ_idx` (`id_faculty_db_univ`),
  CONSTRAINT `id_department_db_univ` FOREIGN KEY (`id_department_db_univ`) REFERENCES `dbuniversity`.`department` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `id_faculty_db_univ` FOREIGN KEY (`id_faculty_db_univ`) REFERENCES `dbuniversity`.`faculty` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `id_institute_db_univ` FOREIGN KEY (`id_institute_db_univ`) REFERENCES `dbuniversity`.`institute` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `id_teacher` FOREIGN KEY (`id_teacher`) REFERENCES `teacher` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `list_disciplines`
--

LOCK TABLES `list_disciplines` WRITE;
/*!40000 ALTER TABLE `list_disciplines` DISABLE KEYS */;
INSERT INTO `list_disciplines` VALUES (23,'Дисциплина1',2,10,1,9,'defaultimage/default_fon_discipline.png','disciplines/id23Disciplina1_20230517_160849'),(24,'Дисциплина2',2,10,1,9,'defaultimage/default_fon_discipline.png','disciplines/id24Disciplina2_20230517_160930'),(25,'Дисциплина3',2,10,1,9,'defaultimage/default_fon_discipline.png','disciplines/id25Disciplina3_20230517_161009'),(26,'Дисциплина4',2,10,1,10,'defaultimage/default_fon_discipline.png','disciplines/id26Disciplina4_20230517_161013'),(27,'Дисциплина5',2,10,1,11,'defaultimage/default_fon_discipline.png','disciplines/id27Disciplina5_20230517_161016'),(28,'Дисциплина6',2,10,1,11,'defaultimage/default_fon_discipline.png','disciplines/id28Disciplina6_20230517_161018'),(29,'Дисциплина7',2,10,1,73,'defaultimage/default_fon_discipline.png','disciplines/id29Disciplina7_20230517_161034'),(30,'Дисциплина8',2,10,1,74,'defaultimage/default_fon_discipline.png','disciplines/id30Disciplina8_20230517_161518'),(31,'Дисциплина9',2,10,1,74,'defaultimage/default_fon_discipline.png','disciplines/id31Disciplina9_20230517_161520'),(32,'Дисциплина10',2,10,1,75,'defaultimage/default_fon_discipline.png','disciplines/id32Disciplina10_20230517_161524'),(33,'Дисциплина11',2,10,1,75,'defaultimage/default_fon_discipline.png','disciplines/id33Disciplina11_20230517_161530'),(34,'Новая дисциплина12',2,10,1,77,'defaultimage/default_fon_discipline.png','disciplines/id34Novaya_disciplina12_20230517_161550'),(35,'Новая дисциплина13',2,10,1,77,'defaultimage/default_fon_discipline.png','disciplines/id35Novaya_disciplina13_20230517_161553'),(36,'Новая дисциплина14',2,10,1,77,'defaultimage/default_fon_discipline.png','disciplines/id36Novaya_disciplina14_20230517_161555'),(38,'testdisc',2,10,1,9,NULL,NULL);
/*!40000 ALTER TABLE `list_disciplines` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_disc_flow`
--

DROP TABLE IF EXISTS `log_disc_flow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_disc_flow` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_disc_flow` int DEFAULT NULL,
  `id_type_log` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_disc_flow_k_idx` (`id_disc_flow`),
  KEY `id_type_log_idx` (`id_type_log`),
  CONSTRAINT `id_disc_flow_k` FOREIGN KEY (`id_disc_flow`) REFERENCES `discipline_flow` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `id_type_log` FOREIGN KEY (`id_type_log`) REFERENCES `type_log` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_disc_flow`
--

LOCK TABLES `log_disc_flow` WRITE;
/*!40000 ALTER TABLE `log_disc_flow` DISABLE KEYS */;
INSERT INTO `log_disc_flow` VALUES (96,22,1),(97,28,1),(98,29,1),(99,25,1),(100,26,1),(101,27,1),(102,30,1),(103,31,1),(104,32,1),(105,33,1),(106,34,1);
/*!40000 ALTER TABLE `log_disc_flow` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_group`
--

DROP TABLE IF EXISTS `log_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_group` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_log` int DEFAULT NULL,
  `id_group` int DEFAULT NULL,
  `log_group_json` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_log_attendance_idx` (`id_log`),
  KEY `id_group_k_idx` (`id_group`),
  CONSTRAINT `id_group_k` FOREIGN KEY (`id_group`) REFERENCES `group` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `id_log_attendance` FOREIGN KEY (`id_log`) REFERENCES `log_disc_flow` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=152 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_group`
--

LOCK TABLES `log_group` WRITE;
/*!40000 ALTER TABLE `log_group` DISABLE KEYS */;
INSERT INTO `log_group` VALUES (123,96,2,'{\"id_group\": 2, \"name_group\": \"ИСТ-19б\", \"attendance_group\": [{\"date\": \"2023-05-21\", \"type_class\": \"Л\", \"array_students\": [{\"name\": \"Владислав\", \"surname\": \"Бабичев\", \"id_student\": 31, \"patronymic\": \"Александрович\", \"presence_class\": \"-\"}, {\"name\": \"Илья\", \"surname\": \"Гейвандов\", \"id_student\": 32, \"patronymic\": \"Романович\", \"presence_class\": \"-\"}, {\"name\": \"Владимир\", \"surname\": \"Карпович\", \"id_student\": 33, \"patronymic\": \"Дмитриевич\", \"presence_class\": \"-\"}, {\"name\": \"Данил\", \"surname\": \"Клюйко\", \"id_student\": 34, \"patronymic\": \"Андреевич\", \"presence_class\": \"-\"}, {\"name\": \"Виктор\", \"surname\": \"Лисиченко\", \"id_student\": 35, \"patronymic\": \"Тимофеевич\", \"presence_class\": \"-\"}, {\"name\": \"Владислав\", \"surname\": \"Моргунов\", \"id_student\": 36, \"patronymic\": \"Дмитриевич\", \"presence_class\": \"-\"}, {\"name\": \"Богдан\", \"surname\": \"Шныра\", \"id_student\": 37, \"patronymic\": \"Олегович\", \"presence_class\": \"-\"}, {\"name\": \"Игорь\", \"surname\": \"Агарков\", \"id_student\": 38, \"patronymic\": \"Анатольевич\", \"presence_class\": \"-\"}]}]}'),(124,96,3,'{\"id_group\": 3, \"name_group\": \"АСУ-19\", \"attendance_group\": [{\"date\": \"2023-05-21\", \"type_class\": \"Л\", \"array_students\": [{\"name\": \"Виталий\", \"surname\": \"Бражников\", \"id_student\": 39, \"patronymic\": \"Андреевич\", \"presence_class\": \"-\"}, {\"name\": \"Денис\", \"surname\": \"Дворников\", \"id_student\": 40, \"patronymic\": \"Евгеньевич\", \"presence_class\": \"-\"}, {\"name\": \"Егор\", \"surname\": \"Дудник\", \"id_student\": 41, \"patronymic\": \"Витальевич\", \"presence_class\": \"-\"}, {\"name\": \"Дмитрий\", \"surname\": \"Лунев\", \"id_student\": 42, \"patronymic\": \"Сергеевич\", \"presence_class\": \"-\"}, {\"name\": \"Мирослав\", \"surname\": \"Павленко\", \"id_student\": 43, \"patronymic\": \"Сергеевич\", \"presence_class\": \"-\"}, {\"name\": \"Алексей\", \"surname\": \"Пушкарь\", \"id_student\": 44, \"patronymic\": \"Дмитриевич\", \"presence_class\": \"-\"}, {\"name\": \"Леонид\", \"surname\": \"Сыгинь\", \"id_student\": 45, \"patronymic\": \"Ярославович\", \"presence_class\": \"-\"}, {\"name\": \"Максим\", \"surname\": \"Шевцов\", \"id_student\": 46, \"patronymic\": \"Викторович\", \"presence_class\": \"-\"}, {\"name\": \"Марк\", \"surname\": \"Яковлев\", \"id_student\": 47, \"patronymic\": \"Юрьевич\", \"presence_class\": \"-\"}, {\"name\": \"Георгий\", \"surname\": \"Ткаченко\", \"id_student\": 48, \"patronymic\": \"Александрович\", \"presence_class\": \"-\"}]}]}'),(125,96,1,'{\"id_group\": 1, \"name_group\": \"ИСТ-19а\", \"attendance_group\": [{\"date\": \"2023-05-21\", \"type_class\": \"Л\", \"array_students\": [{\"name\": \"Алина\", \"surname\": \"Белая\", \"id_student\": 19, \"patronymic\": \"Юрьевна\", \"presence_class\": \"-\"}, {\"name\": \"Георгий\", \"surname\": \"Гоик\", \"id_student\": 20, \"patronymic\": \"Дмитриевич\", \"presence_class\": \"-\"}, {\"name\": \"Данил\", \"surname\": \"Гончаров\", \"id_student\": 21, \"patronymic\": \"Дмитриевич\", \"presence_class\": \"-\"}, {\"name\": \"Кирилл\", \"surname\": \"Деркач\", \"id_student\": 22, \"patronymic\": \"Игоревич\", \"presence_class\": \"-\"}, {\"name\": \"Дмитрий\", \"surname\": \"Лефтеров\", \"id_student\": 23, \"patronymic\": \"Дмитриевич\", \"presence_class\": \"-\"}, {\"name\": \"Полина\", \"surname\": \"Пойденко\", \"id_student\": 24, \"patronymic\": \"Александровна\", \"presence_class\": \"-\"}, {\"name\": \"Максим\", \"surname\": \"Рядко\", \"id_student\": 25, \"patronymic\": \"Алексеевич\", \"presence_class\": \"-\"}, {\"name\": \"Валерия\", \"surname\": \"Савенкова\", \"id_student\": 26, \"patronymic\": \"Олеговна\", \"presence_class\": \"-\"}, {\"name\": \"Артем\", \"surname\": \"Слободяник\", \"id_student\": 27, \"patronymic\": \"Игоревич\", \"presence_class\": \"-\"}, {\"name\": \"Михаил\", \"surname\": \"Шевченко\", \"id_student\": 28, \"patronymic\": \"Владимирович\", \"presence_class\": \"-\"}, {\"name\": \"Богдан\", \"surname\": \"Шестаков\", \"id_student\": 29, \"patronymic\": \"Андреевич\", \"presence_class\": \"-\"}, {\"name\": \"Артем\", \"surname\": \"Яковченко\", \"id_student\": 30, \"patronymic\": \"Александрович\", \"presence_class\": \"-\"}]}]}'),(126,97,6,'{\"id_group\": 6, \"name_group\": \"ИСТ-19в\", \"attendance_group\": [{\"date\": \"2023-05-21\", \"type_class\": \"Л\", \"array_students\": []}]}'),(127,97,7,'{\"id_group\": 7, \"name_group\": \"ИСТ-19г\", \"attendance_group\": [{\"date\": \"2023-05-21\", \"type_class\": \"Л\", \"array_students\": []}]}'),(128,98,8,'{\"id_group\": 8, \"name_group\": \"ИНФ-19\", \"attendance_group\": [{\"date\": \"2023-05-21\", \"type_class\": \"Л\", \"array_students\": []}]}'),(129,98,9,'{\"id_group\": 9, \"name_group\": \"ПИ-19\", \"attendance_group\": [{\"date\": \"2023-05-21\", \"type_class\": \"Л\", \"array_students\": []}]}'),(130,99,1,'{\"id_group\": 1, \"name_group\": \"ИСТ-19а\", \"attendance_group\": [{\"date\": \"2023-05-21\", \"type_class\": \"Л\", \"array_students\": [{\"name\": \"Алина\", \"surname\": \"Белая\", \"id_student\": 19, \"patronymic\": \"Юрьевна\", \"presence_class\": \"-\"}, {\"name\": \"Георгий\", \"surname\": \"Гоик\", \"id_student\": 20, \"patronymic\": \"Дмитриевич\", \"presence_class\": \"-\"}, {\"name\": \"Данил\", \"surname\": \"Гончаров\", \"id_student\": 21, \"patronymic\": \"Дмитриевич\", \"presence_class\": \"-\"}, {\"name\": \"Кирилл\", \"surname\": \"Деркач\", \"id_student\": 22, \"patronymic\": \"Игоревич\", \"presence_class\": \"-\"}, {\"name\": \"Дмитрий\", \"surname\": \"Лефтеров\", \"id_student\": 23, \"patronymic\": \"Дмитриевич\", \"presence_class\": \"-\"}, {\"name\": \"Полина\", \"surname\": \"Пойденко\", \"id_student\": 24, \"patronymic\": \"Александровна\", \"presence_class\": \"-\"}, {\"name\": \"Максим\", \"surname\": \"Рядко\", \"id_student\": 25, \"patronymic\": \"Алексеевич\", \"presence_class\": \"-\"}, {\"name\": \"Валерия\", \"surname\": \"Савенкова\", \"id_student\": 26, \"patronymic\": \"Олеговна\", \"presence_class\": \"-\"}, {\"name\": \"Артем\", \"surname\": \"Слободяник\", \"id_student\": 27, \"patronymic\": \"Игоревич\", \"presence_class\": \"-\"}, {\"name\": \"Михаил\", \"surname\": \"Шевченко\", \"id_student\": 28, \"patronymic\": \"Владимирович\", \"presence_class\": \"-\"}, {\"name\": \"Богдан\", \"surname\": \"Шестаков\", \"id_student\": 29, \"patronymic\": \"Андреевич\", \"presence_class\": \"-\"}, {\"name\": \"Артем\", \"surname\": \"Яковченко\", \"id_student\": 30, \"patronymic\": \"Александрович\", \"presence_class\": \"-\"}]}]}'),(131,99,2,'{\"id_group\": 2, \"name_group\": \"ИСТ-19б\", \"attendance_group\": [{\"date\": \"2023-05-21\", \"type_class\": \"Л\", \"array_students\": [{\"name\": \"Владислав\", \"surname\": \"Бабичев\", \"id_student\": 31, \"patronymic\": \"Александрович\", \"presence_class\": \"-\"}, {\"name\": \"Илья\", \"surname\": \"Гейвандов\", \"id_student\": 32, \"patronymic\": \"Романович\", \"presence_class\": \"-\"}, {\"name\": \"Владимир\", \"surname\": \"Карпович\", \"id_student\": 33, \"patronymic\": \"Дмитриевич\", \"presence_class\": \"-\"}, {\"name\": \"Данил\", \"surname\": \"Клюйко\", \"id_student\": 34, \"patronymic\": \"Андреевич\", \"presence_class\": \"-\"}, {\"name\": \"Виктор\", \"surname\": \"Лисиченко\", \"id_student\": 35, \"patronymic\": \"Тимофеевич\", \"presence_class\": \"-\"}, {\"name\": \"Владислав\", \"surname\": \"Моргунов\", \"id_student\": 36, \"patronymic\": \"Дмитриевич\", \"presence_class\": \"-\"}, {\"name\": \"Богдан\", \"surname\": \"Шныра\", \"id_student\": 37, \"patronymic\": \"Олегович\", \"presence_class\": \"-\"}, {\"name\": \"Игорь\", \"surname\": \"Агарков\", \"id_student\": 38, \"patronymic\": \"Анатольевич\", \"presence_class\": \"-\"}]}]}'),(132,99,4,'{\"id_group\": 4, \"name_group\": \"БИ-19\", \"attendance_group\": [{\"date\": \"2023-05-21\", \"type_class\": \"Л\", \"array_students\": [{\"name\": \"Варанкин\", \"surname\": \"Даниил\", \"id_student\": 111, \"patronymic\": \"Вадимович\", \"presence_class\": \"-\"}]}]}'),(133,99,5,'{\"id_group\": 5, \"name_group\": \"КМД-19\", \"attendance_group\": [{\"date\": \"2023-05-21\", \"type_class\": \"Л\", \"array_students\": []}]}'),(134,100,6,'{\"id_group\": 6, \"name_group\": \"ИСТ-19в\", \"attendance_group\": [{\"date\": \"2023-05-21\", \"type_class\": \"Л\", \"array_students\": []}]}'),(135,100,7,'{\"id_group\": 7, \"name_group\": \"ИСТ-19г\", \"attendance_group\": [{\"date\": \"2023-05-21\", \"type_class\": \"Л\", \"array_students\": []}]}'),(136,101,3,'{\"id_group\": 3, \"name_group\": \"АСУ-19\", \"attendance_group\": [{\"date\": \"2023-05-21\", \"type_class\": \"Л\", \"array_students\": [{\"name\": \"Виталий\", \"surname\": \"Бражников\", \"id_student\": 39, \"patronymic\": \"Андреевич\", \"presence_class\": \"-\"}, {\"name\": \"Денис\", \"surname\": \"Дворников\", \"id_student\": 40, \"patronymic\": \"Евгеньевич\", \"presence_class\": \"-\"}, {\"name\": \"Егор\", \"surname\": \"Дудник\", \"id_student\": 41, \"patronymic\": \"Витальевич\", \"presence_class\": \"-\"}, {\"name\": \"Дмитрий\", \"surname\": \"Лунев\", \"id_student\": 42, \"patronymic\": \"Сергеевич\", \"presence_class\": \"-\"}, {\"name\": \"Мирослав\", \"surname\": \"Павленко\", \"id_student\": 43, \"patronymic\": \"Сергеевич\", \"presence_class\": \"-\"}, {\"name\": \"Алексей\", \"surname\": \"Пушкарь\", \"id_student\": 44, \"patronymic\": \"Дмитриевич\", \"presence_class\": \"-\"}, {\"name\": \"Леонид\", \"surname\": \"Сыгинь\", \"id_student\": 45, \"patronymic\": \"Ярославович\", \"presence_class\": \"-\"}, {\"name\": \"Максим\", \"surname\": \"Шевцов\", \"id_student\": 46, \"patronymic\": \"Викторович\", \"presence_class\": \"-\"}, {\"name\": \"Марк\", \"surname\": \"Яковлев\", \"id_student\": 47, \"patronymic\": \"Юрьевич\", \"presence_class\": \"-\"}, {\"name\": \"Георгий\", \"surname\": \"Ткаченко\", \"id_student\": 48, \"patronymic\": \"Александрович\", \"presence_class\": \"-\"}]}]}'),(137,102,2,'{\"id_group\": 2, \"name_group\": \"ИСТ-19б\", \"attendance_group\": [{\"date\": \"2023-05-21\", \"type_class\": \"Л\", \"array_students\": [{\"name\": \"Владислав\", \"surname\": \"Бабичев\", \"id_student\": 31, \"patronymic\": \"Александрович\", \"presence_class\": \"-\"}, {\"name\": \"Илья\", \"surname\": \"Гейвандов\", \"id_student\": 32, \"patronymic\": \"Романович\", \"presence_class\": \"-\"}, {\"name\": \"Владимир\", \"surname\": \"Карпович\", \"id_student\": 33, \"patronymic\": \"Дмитриевич\", \"presence_class\": \"-\"}, {\"name\": \"Данил\", \"surname\": \"Клюйко\", \"id_student\": 34, \"patronymic\": \"Андреевич\", \"presence_class\": \"-\"}, {\"name\": \"Виктор\", \"surname\": \"Лисиченко\", \"id_student\": 35, \"patronymic\": \"Тимофеевич\", \"presence_class\": \"-\"}, {\"name\": \"Владислав\", \"surname\": \"Моргунов\", \"id_student\": 36, \"patronymic\": \"Дмитриевич\", \"presence_class\": \"-\"}, {\"name\": \"Богдан\", \"surname\": \"Шныра\", \"id_student\": 37, \"patronymic\": \"Олегович\", \"presence_class\": \"-\"}, {\"name\": \"Игорь\", \"surname\": \"Агарков\", \"id_student\": 38, \"patronymic\": \"Анатольевич\", \"presence_class\": \"-\"}]}]}'),(138,102,3,'{\"id_group\": 3, \"name_group\": \"АСУ-19\", \"attendance_group\": [{\"date\": \"2023-05-21\", \"type_class\": \"Л\", \"array_students\": [{\"name\": \"Виталий\", \"surname\": \"Бражников\", \"id_student\": 39, \"patronymic\": \"Андреевич\", \"presence_class\": \"-\"}, {\"name\": \"Денис\", \"surname\": \"Дворников\", \"id_student\": 40, \"patronymic\": \"Евгеньевич\", \"presence_class\": \"-\"}, {\"name\": \"Егор\", \"surname\": \"Дудник\", \"id_student\": 41, \"patronymic\": \"Витальевич\", \"presence_class\": \"-\"}, {\"name\": \"Дмитрий\", \"surname\": \"Лунев\", \"id_student\": 42, \"patronymic\": \"Сергеевич\", \"presence_class\": \"-\"}, {\"name\": \"Мирослав\", \"surname\": \"Павленко\", \"id_student\": 43, \"patronymic\": \"Сергеевич\", \"presence_class\": \"-\"}, {\"name\": \"Алексей\", \"surname\": \"Пушкарь\", \"id_student\": 44, \"patronymic\": \"Дмитриевич\", \"presence_class\": \"-\"}, {\"name\": \"Леонид\", \"surname\": \"Сыгинь\", \"id_student\": 45, \"patronymic\": \"Ярославович\", \"presence_class\": \"-\"}, {\"name\": \"Максим\", \"surname\": \"Шевцов\", \"id_student\": 46, \"patronymic\": \"Викторович\", \"presence_class\": \"-\"}, {\"name\": \"Марк\", \"surname\": \"Яковлев\", \"id_student\": 47, \"patronymic\": \"Юрьевич\", \"presence_class\": \"-\"}, {\"name\": \"Георгий\", \"surname\": \"Ткаченко\", \"id_student\": 48, \"patronymic\": \"Александрович\", \"presence_class\": \"-\"}]}]}'),(139,102,1,'{\"id_group\": 1, \"name_group\": \"ИСТ-19а\", \"attendance_group\": [{\"date\": \"2023-05-21\", \"type_class\": \"Л\", \"array_students\": [{\"name\": \"Алина\", \"surname\": \"Белая\", \"id_student\": 19, \"patronymic\": \"Юрьевна\", \"presence_class\": \"-\"}, {\"name\": \"Георгий\", \"surname\": \"Гоик\", \"id_student\": 20, \"patronymic\": \"Дмитриевич\", \"presence_class\": \"-\"}, {\"name\": \"Данил\", \"surname\": \"Гончаров\", \"id_student\": 21, \"patronymic\": \"Дмитриевич\", \"presence_class\": \"-\"}, {\"name\": \"Кирилл\", \"surname\": \"Деркач\", \"id_student\": 22, \"patronymic\": \"Игоревич\", \"presence_class\": \"-\"}, {\"name\": \"Дмитрий\", \"surname\": \"Лефтеров\", \"id_student\": 23, \"patronymic\": \"Дмитриевич\", \"presence_class\": \"-\"}, {\"name\": \"Полина\", \"surname\": \"Пойденко\", \"id_student\": 24, \"patronymic\": \"Александровна\", \"presence_class\": \"-\"}, {\"name\": \"Максим\", \"surname\": \"Рядко\", \"id_student\": 25, \"patronymic\": \"Алексеевич\", \"presence_class\": \"-\"}, {\"name\": \"Валерия\", \"surname\": \"Савенкова\", \"id_student\": 26, \"patronymic\": \"Олеговна\", \"presence_class\": \"-\"}, {\"name\": \"Артем\", \"surname\": \"Слободяник\", \"id_student\": 27, \"patronymic\": \"Игоревич\", \"presence_class\": \"-\"}, {\"name\": \"Михаил\", \"surname\": \"Шевченко\", \"id_student\": 28, \"patronymic\": \"Владимирович\", \"presence_class\": \"-\"}, {\"name\": \"Богдан\", \"surname\": \"Шестаков\", \"id_student\": 29, \"patronymic\": \"Андреевич\", \"presence_class\": \"-\"}, {\"name\": \"Артем\", \"surname\": \"Яковченко\", \"id_student\": 30, \"patronymic\": \"Александрович\", \"presence_class\": \"-\"}]}]}'),(140,103,8,'{\"id_group\": 8, \"name_group\": \"ИНФ-19\", \"attendance_group\": [{\"date\": \"2023-05-21\", \"type_class\": \"Л\", \"array_students\": []}]}'),(141,103,9,'{\"id_group\": 9, \"name_group\": \"ПИ-19\", \"attendance_group\": [{\"date\": \"2023-05-21\", \"type_class\": \"Л\", \"array_students\": []}]}'),(142,104,1,'{\"id_group\": 1, \"name_group\": \"ИСТ-19а\", \"attendance_group\": [{\"date\": \"2023-05-21\", \"type_class\": \"Л\", \"array_students\": [{\"name\": \"Алина\", \"surname\": \"Белая\", \"id_student\": 19, \"patronymic\": \"Юрьевна\", \"presence_class\": \"-\"}, {\"name\": \"Георгий\", \"surname\": \"Гоик\", \"id_student\": 20, \"patronymic\": \"Дмитриевич\", \"presence_class\": \"-\"}, {\"name\": \"Данил\", \"surname\": \"Гончаров\", \"id_student\": 21, \"patronymic\": \"Дмитриевич\", \"presence_class\": \"-\"}, {\"name\": \"Кирилл\", \"surname\": \"Деркач\", \"id_student\": 22, \"patronymic\": \"Игоревич\", \"presence_class\": \"-\"}, {\"name\": \"Дмитрий\", \"surname\": \"Лефтеров\", \"id_student\": 23, \"patronymic\": \"Дмитриевич\", \"presence_class\": \"-\"}, {\"name\": \"Полина\", \"surname\": \"Пойденко\", \"id_student\": 24, \"patronymic\": \"Александровна\", \"presence_class\": \"-\"}, {\"name\": \"Максим\", \"surname\": \"Рядко\", \"id_student\": 25, \"patronymic\": \"Алексеевич\", \"presence_class\": \"-\"}, {\"name\": \"Валерия\", \"surname\": \"Савенкова\", \"id_student\": 26, \"patronymic\": \"Олеговна\", \"presence_class\": \"-\"}, {\"name\": \"Артем\", \"surname\": \"Слободяник\", \"id_student\": 27, \"patronymic\": \"Игоревич\", \"presence_class\": \"-\"}, {\"name\": \"Михаил\", \"surname\": \"Шевченко\", \"id_student\": 28, \"patronymic\": \"Владимирович\", \"presence_class\": \"-\"}, {\"name\": \"Богдан\", \"surname\": \"Шестаков\", \"id_student\": 29, \"patronymic\": \"Андреевич\", \"presence_class\": \"-\"}, {\"name\": \"Артем\", \"surname\": \"Яковченко\", \"id_student\": 30, \"patronymic\": \"Александрович\", \"presence_class\": \"-\"}]}]}'),(143,104,2,'{\"id_group\": 2, \"name_group\": \"ИСТ-19б\", \"attendance_group\": [{\"date\": \"2023-05-21\", \"type_class\": \"Л\", \"array_students\": [{\"name\": \"Владислав\", \"surname\": \"Бабичев\", \"id_student\": 31, \"patronymic\": \"Александрович\", \"presence_class\": \"-\"}, {\"name\": \"Илья\", \"surname\": \"Гейвандов\", \"id_student\": 32, \"patronymic\": \"Романович\", \"presence_class\": \"-\"}, {\"name\": \"Владимир\", \"surname\": \"Карпович\", \"id_student\": 33, \"patronymic\": \"Дмитриевич\", \"presence_class\": \"-\"}, {\"name\": \"Данил\", \"surname\": \"Клюйко\", \"id_student\": 34, \"patronymic\": \"Андреевич\", \"presence_class\": \"-\"}, {\"name\": \"Виктор\", \"surname\": \"Лисиченко\", \"id_student\": 35, \"patronymic\": \"Тимофеевич\", \"presence_class\": \"-\"}, {\"name\": \"Владислав\", \"surname\": \"Моргунов\", \"id_student\": 36, \"patronymic\": \"Дмитриевич\", \"presence_class\": \"-\"}, {\"name\": \"Богдан\", \"surname\": \"Шныра\", \"id_student\": 37, \"patronymic\": \"Олегович\", \"presence_class\": \"-\"}, {\"name\": \"Игорь\", \"surname\": \"Агарков\", \"id_student\": 38, \"patronymic\": \"Анатольевич\", \"presence_class\": \"-\"}]}]}'),(144,104,4,'{\"id_group\": 4, \"name_group\": \"БИ-19\", \"attendance_group\": [{\"date\": \"2023-05-21\", \"type_class\": \"Л\", \"array_students\": [{\"name\": \"Варанкин\", \"surname\": \"Даниил\", \"id_student\": 111, \"patronymic\": \"Вадимович\", \"presence_class\": \"-\"}]}]}'),(145,104,5,'{\"id_group\": 5, \"name_group\": \"КМД-19\", \"attendance_group\": [{\"date\": \"2023-05-21\", \"type_class\": \"Л\", \"array_students\": []}]}'),(146,105,1,'{\"id_group\": 1, \"name_group\": \"ИСТ-19а\", \"attendance_group\": [{\"date\": \"2023-05-21\", \"type_class\": \"Л\", \"array_students\": [{\"name\": \"Алина\", \"surname\": \"Белая\", \"id_student\": 19, \"patronymic\": \"Юрьевна\", \"presence_class\": \"-\"}, {\"name\": \"Георгий\", \"surname\": \"Гоик\", \"id_student\": 20, \"patronymic\": \"Дмитриевич\", \"presence_class\": \"-\"}, {\"name\": \"Данил\", \"surname\": \"Гончаров\", \"id_student\": 21, \"patronymic\": \"Дмитриевич\", \"presence_class\": \"-\"}, {\"name\": \"Кирилл\", \"surname\": \"Деркач\", \"id_student\": 22, \"patronymic\": \"Игоревич\", \"presence_class\": \"-\"}, {\"name\": \"Дмитрий\", \"surname\": \"Лефтеров\", \"id_student\": 23, \"patronymic\": \"Дмитриевич\", \"presence_class\": \"-\"}, {\"name\": \"Полина\", \"surname\": \"Пойденко\", \"id_student\": 24, \"patronymic\": \"Александровна\", \"presence_class\": \"-\"}, {\"name\": \"Максим\", \"surname\": \"Рядко\", \"id_student\": 25, \"patronymic\": \"Алексеевич\", \"presence_class\": \"-\"}, {\"name\": \"Валерия\", \"surname\": \"Савенкова\", \"id_student\": 26, \"patronymic\": \"Олеговна\", \"presence_class\": \"-\"}, {\"name\": \"Артем\", \"surname\": \"Слободяник\", \"id_student\": 27, \"patronymic\": \"Игоревич\", \"presence_class\": \"-\"}, {\"name\": \"Михаил\", \"surname\": \"Шевченко\", \"id_student\": 28, \"patronymic\": \"Владимирович\", \"presence_class\": \"-\"}, {\"name\": \"Богдан\", \"surname\": \"Шестаков\", \"id_student\": 29, \"patronymic\": \"Андреевич\", \"presence_class\": \"-\"}, {\"name\": \"Артем\", \"surname\": \"Яковченко\", \"id_student\": 30, \"patronymic\": \"Александрович\", \"presence_class\": \"-\"}]}]}'),(147,105,2,'{\"id_group\": 2, \"name_group\": \"ИСТ-19б\", \"attendance_group\": [{\"date\": \"2023-05-21\", \"type_class\": \"Л\", \"array_students\": [{\"name\": \"Владислав\", \"surname\": \"Бабичев\", \"id_student\": 31, \"patronymic\": \"Александрович\", \"presence_class\": \"-\"}, {\"name\": \"Илья\", \"surname\": \"Гейвандов\", \"id_student\": 32, \"patronymic\": \"Романович\", \"presence_class\": \"-\"}, {\"name\": \"Владимир\", \"surname\": \"Карпович\", \"id_student\": 33, \"patronymic\": \"Дмитриевич\", \"presence_class\": \"-\"}, {\"name\": \"Данил\", \"surname\": \"Клюйко\", \"id_student\": 34, \"patronymic\": \"Андреевич\", \"presence_class\": \"-\"}, {\"name\": \"Виктор\", \"surname\": \"Лисиченко\", \"id_student\": 35, \"patronymic\": \"Тимофеевич\", \"presence_class\": \"-\"}, {\"name\": \"Владислав\", \"surname\": \"Моргунов\", \"id_student\": 36, \"patronymic\": \"Дмитриевич\", \"presence_class\": \"-\"}, {\"name\": \"Богдан\", \"surname\": \"Шныра\", \"id_student\": 37, \"patronymic\": \"Олегович\", \"presence_class\": \"-\"}, {\"name\": \"Игорь\", \"surname\": \"Агарков\", \"id_student\": 38, \"patronymic\": \"Анатольевич\", \"presence_class\": \"-\"}]}]}'),(148,105,6,'{\"id_group\": 6, \"name_group\": \"ИСТ-19в\", \"attendance_group\": [{\"date\": \"2023-05-21\", \"type_class\": \"Л\", \"array_students\": []}]}'),(149,105,7,'{\"id_group\": 7, \"name_group\": \"ИСТ-19г\", \"attendance_group\": [{\"date\": \"2023-05-21\", \"type_class\": \"Л\", \"array_students\": []}]}'),(150,105,9,'{\"id_group\": 9, \"name_group\": \"ПИ-19\", \"attendance_group\": [{\"date\": \"2023-05-21\", \"type_class\": \"Л\", \"array_students\": []}]}'),(151,106,3,'{\"id_group\": 3, \"name_group\": \"АСУ-19\", \"attendance_group\": [{\"date\": \"2023-05-21\", \"type_class\": \"Л\", \"array_students\": [{\"name\": \"Виталий\", \"surname\": \"Бражников\", \"id_student\": 39, \"patronymic\": \"Андреевич\", \"presence_class\": \"-\"}, {\"name\": \"Денис\", \"surname\": \"Дворников\", \"id_student\": 40, \"patronymic\": \"Евгеньевич\", \"presence_class\": \"-\"}, {\"name\": \"Егор\", \"surname\": \"Дудник\", \"id_student\": 41, \"patronymic\": \"Витальевич\", \"presence_class\": \"-\"}, {\"name\": \"Дмитрий\", \"surname\": \"Лунев\", \"id_student\": 42, \"patronymic\": \"Сергеевич\", \"presence_class\": \"-\"}, {\"name\": \"Мирослав\", \"surname\": \"Павленко\", \"id_student\": 43, \"patronymic\": \"Сергеевич\", \"presence_class\": \"-\"}, {\"name\": \"Алексей\", \"surname\": \"Пушкарь\", \"id_student\": 44, \"patronymic\": \"Дмитриевич\", \"presence_class\": \"-\"}, {\"name\": \"Леонид\", \"surname\": \"Сыгинь\", \"id_student\": 45, \"patronymic\": \"Ярославович\", \"presence_class\": \"-\"}, {\"name\": \"Максим\", \"surname\": \"Шевцов\", \"id_student\": 46, \"patronymic\": \"Викторович\", \"presence_class\": \"-\"}, {\"name\": \"Марк\", \"surname\": \"Яковлев\", \"id_student\": 47, \"patronymic\": \"Юрьевич\", \"presence_class\": \"-\"}, {\"name\": \"Георгий\", \"surname\": \"Ткаченко\", \"id_student\": 48, \"patronymic\": \"Александрович\", \"presence_class\": \"-\"}]}]}');
/*!40000 ALTER TABLE `log_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `position_teacher`
--

DROP TABLE IF EXISTS `position_teacher`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `position_teacher` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_teacher_x` int DEFAULT NULL,
  `id_workes_db_univ` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_teacher_idx` (`id_teacher_x`),
  KEY `id_workes_db_univ_idx` (`id_workes_db_univ`),
  CONSTRAINT `id_teacher_x` FOREIGN KEY (`id_teacher_x`) REFERENCES `teacher` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `id_workes_db_univ` FOREIGN KEY (`id_workes_db_univ`) REFERENCES `dbuniversity`.`workes` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `position_teacher`
--

LOCK TABLES `position_teacher` WRITE;
/*!40000 ALTER TABLE `position_teacher` DISABLE KEYS */;
INSERT INTO `position_teacher` VALUES (1,9,7),(2,10,8),(3,11,9),(4,9,16),(16,73,10),(17,73,15),(18,74,11),(19,75,12),(20,76,13),(21,77,14),(22,78,17),(23,79,18),(24,80,19);
/*!40000 ALTER TABLE `position_teacher` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `posts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_disc_flow` int DEFAULT NULL,
  `id_teacher_creator` int DEFAULT NULL,
  `date_create_post` datetime DEFAULT NULL,
  `id_type_post` int DEFAULT NULL,
  `text` longtext,
  `folder` varchar(255) DEFAULT NULL,
  `attendance_button` tinyint DEFAULT NULL,
  `date_end_button` datetime DEFAULT NULL,
  `id_type_class` int DEFAULT NULL,
  `date_class` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_disc_flow_kk_idx` (`id_disc_flow`),
  KEY `id_teacher_creator_kk_idx` (`id_teacher_creator`),
  KEY `id_type_post_idx` (`id_type_post`),
  KEY `id_type_class_idx` (`id_type_class`),
  CONSTRAINT `id_disc_flow_kk` FOREIGN KEY (`id_disc_flow`) REFERENCES `discipline_flow` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `id_teacher_creator_kk` FOREIGN KEY (`id_teacher_creator`) REFERENCES `teacher` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `id_type_class` FOREIGN KEY (`id_type_class`) REFERENCES `type_class` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `id_type_post` FOREIGN KEY (`id_type_post`) REFERENCES `type_post` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (4,22,9,'2023-05-22 02:20:14',1,'первое объявление, урааааа',NULL,1,'2023-05-22 02:30:00',1,'2023-05-22'),(5,22,9,'2023-05-22 02:22:45',1,'первое объявление, урааааа','disciplines/id23Disciplina1_20230517_160849/id22Potok1_20230517_162857/posts/postid5',1,'2023-05-22 02:30:00',1,'2023-05-22'),(6,22,9,'2023-05-22 02:23:25',1,'первое объявление, урааааа','disciplines/id23Disciplina1_20230517_160849/id22Potok1_20230517_162857/posts/postid6',1,'2023-05-22 02:30:00',1,'2023-05-22');
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `registration`
--

DROP TABLE IF EXISTS `registration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `registration` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `id_role_user` int NOT NULL,
  `email` varchar(100) NOT NULL,
  `login` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `date_registration` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_userfkf_idx` (`id_user`),
  KEY `id_role_user_idx` (`id_role_user`),
  CONSTRAINT `id_role_user` FOREIGN KEY (`id_role_user`) REFERENCES `role_user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `id_user_x` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `registration`
--

LOCK TABLES `registration` WRITE;
/*!40000 ALTER TABLE `registration` DISABLE KEYS */;
INSERT INTO `registration` VALUES (19,16,1,'registemail1@gmail.com','login1','password1','2023-05-02'),(20,17,1,'registemail2@gmail.com','login2','password2','2023-05-02'),(21,18,1,'registemail3@gmail.com','login3','password3','2023-05-02'),(22,19,1,'registemail4@gmail.com','login4','password4','2023-05-02'),(23,20,1,'registemail5@gmail.com','login5','password5','2023-05-02'),(24,21,1,'registemail6@gmail.com','login6','password6','2023-05-02'),(25,22,1,'registemail7@gmail.com','login7','password7','2023-05-02'),(26,23,1,'registemail8@gmail.com','login8','password8','2023-05-02'),(27,24,1,'registemail9@gmail.com','login9','password9','2023-05-02'),(28,25,1,'registemail10@gmail.com','login10','password10','2023-05-02'),(29,26,1,'registemail11@gmail.com','login11','password11','2023-05-02'),(30,27,1,'registemail12@gmail.com','login12','password12','2023-05-02'),(31,31,1,'registemail13@gmail.com','login13','password13','2023-05-02'),(32,32,1,'registemail14@gmail.com','login14','password14','2023-05-02'),(33,33,1,'registemail15@gmail.com','login15','password15','2023-05-02'),(34,34,1,'registemail16@gmail.com','login16','password16','2023-05-02'),(35,35,1,'registemail17@gmail.com','login17','password17','2023-05-02'),(36,36,1,'registemail18@gmail.com','login18','password18','2023-05-02'),(37,37,1,'registemail19@gmail.com','login19','password19','2023-05-02'),(38,38,1,'registemail20@gmail.com','login20','password20','2023-05-02'),(39,39,5,'registemail21@gmail.com','login21','password21','2023-05-02'),(40,40,5,'registemail22@gmail.com','login22','password22','2023-05-02'),(41,41,5,'registemail23@gmail.com','login23','password23','2023-05-02'),(46,59,5,'emailsekirin@gmail.com','loginsekirin','password25','2023-05-03'),(47,61,1,'registemail24@gmail.com','login24','password26','2023-05-03'),(48,62,1,'registemail25@gmail.com','login25','password27','2023-05-03'),(49,63,1,'registemail26@gmail.com','login26','password28','2023-05-03'),(50,64,1,'registemail27@gmail.com','login27','password29','2023-05-03'),(51,65,1,'registemail28@gmail.com','login28','password30','2023-05-03'),(52,66,1,'registemail29@gmail.com','login29','password31','2023-05-03'),(53,67,1,'registemail30@gmail.com','login30','password32','2023-05-03'),(54,68,1,'registemail31@gmail.com','login31','password33','2023-05-03'),(55,69,1,'registemail32@gmail.com','login32','password34','2023-05-03'),(56,70,1,'registemail33@gmail.com','login33','password35','2023-05-03'),(57,78,5,'registemail34@gmail.com','login34','password36','2023-05-03'),(58,71,5,'registemail35@gmail.com','login35','password37','2023-05-03'),(59,72,5,'registemail36@gmail.com','login36','password38','2023-05-03'),(60,73,5,'registemail37@gmail.com','login37','password39','2023-05-03'),(61,74,5,'registemail38@gmail.com','login38','password40','2023-05-03'),(62,75,5,'registemail39@gmail.com','login39','password41','2023-05-03'),(63,76,5,'registemail40@gmail.com','login40','password42','2023-05-03'),(128,143,1,'emailvarankin@gmail.com','loginvarankin','passwordloginvarankin','2023-05-21');
/*!40000 ALTER TABLE `registration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_user`
--

DROP TABLE IF EXISTS `role_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `id_role_db_univ` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_role_from_dbunivers_idx` (`id_role_db_univ`),
  CONSTRAINT `id_role_db_univ` FOREIGN KEY (`id_role_db_univ`) REFERENCES `dbuniversity`.`role` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_user`
--

LOCK TABLES `role_user` WRITE;
/*!40000 ALTER TABLE `role_user` DISABLE KEYS */;
INSERT INTO `role_user` VALUES (1,'студент',NULL),(4,'сотрудник',1),(5,'преподаватель',2);
/*!40000 ALTER TABLE `role_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student`
--

DROP TABLE IF EXISTS `student`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `student` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_user` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user_idx` (`id_user`),
  CONSTRAINT `id_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=112 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student`
--

LOCK TABLES `student` WRITE;
/*!40000 ALTER TABLE `student` DISABLE KEYS */;
INSERT INTO `student` VALUES (19,16),(20,17),(21,18),(22,19),(23,20),(24,21),(25,22),(26,23),(27,24),(28,25),(29,26),(30,27),(31,31),(32,32),(33,33),(34,34),(35,35),(36,36),(37,37),(38,38),(39,61),(40,62),(41,63),(42,64),(43,65),(44,66),(45,67),(46,68),(47,69),(48,70),(111,143);
/*!40000 ALTER TABLE `student` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teacher`
--

DROP TABLE IF EXISTS `teacher`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `teacher` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user_idx` (`id_user`),
  CONSTRAINT `id_userfk` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teacher`
--

LOCK TABLES `teacher` WRITE;
/*!40000 ALTER TABLE `teacher` DISABLE KEYS */;
INSERT INTO `teacher` VALUES (9,39),(10,40),(11,41),(73,59),(75,71),(76,72),(77,73),(78,74),(79,75),(80,76),(74,78);
/*!40000 ALTER TABLE `teacher` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teacher_discipline`
--

DROP TABLE IF EXISTS `teacher_discipline`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `teacher_discipline` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_discipline_flow` int DEFAULT NULL,
  `id_teacher` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_discipline_flow_idx` (`id_discipline_flow`),
  KEY `id_teacher_idx` (`id_teacher`),
  CONSTRAINT `id_discipline_flow` FOREIGN KEY (`id_discipline_flow`) REFERENCES `discipline_flow` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `id_teacherfk` FOREIGN KEY (`id_teacher`) REFERENCES `teacher` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teacher_discipline`
--

LOCK TABLES `teacher_discipline` WRITE;
/*!40000 ALTER TABLE `teacher_discipline` DISABLE KEYS */;
INSERT INTO `teacher_discipline` VALUES (30,22,10),(31,22,80),(33,25,10),(34,25,76),(35,28,10),(36,30,11),(37,30,73),(38,31,9),(39,31,11),(40,32,9),(41,32,10),(42,33,80);
/*!40000 ALTER TABLE `teacher_discipline` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tokens_authorization`
--

DROP TABLE IF EXISTS `tokens_authorization`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tokens_authorization` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_registration` int DEFAULT NULL,
  `platform` varchar(100) DEFAULT NULL,
  `mobile` tinyint DEFAULT NULL,
  `brand` varchar(45) DEFAULT NULL,
  `version_brand` varchar(45) DEFAULT NULL,
  `data_save_note` date DEFAULT NULL,
  `token` longtext,
  PRIMARY KEY (`id`),
  KEY `id_registration_idx` (`id_registration`),
  CONSTRAINT `id_registration` FOREIGN KEY (`id_registration`) REFERENCES `registration` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tokens_authorization`
--

LOCK TABLES `tokens_authorization` WRITE;
/*!40000 ALTER TABLE `tokens_authorization` DISABLE KEYS */;
INSERT INTO `tokens_authorization` VALUES (3,39,'Windows',0,'Opera GX','97','2023-05-18','eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZF91c2VyIjozOSwiaWRfdGVhY2hlcl9zdHVkZW50Ijo5LCJpZF9yb2xlX3VzZXIiOjV9.jzjsKW0Tkc7rn1UC4A068urhPF6r7f96ytL8zmHuxBY='),(21,40,'Windows',0,'Opera GX','97','2023-05-10','eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZF91c2VyIjo0MCwiaWRfdGVhY2hlcl9zdHVkZW50IjoxMCwiaWRfcm9sZV91c2VyIjo1fQ==.LuPJy9TxIDjUusOUxDNT93Ge9yRb8vm2fmRAKo87MUA='),(23,39,'Windows',0,'Chromium','103','2023-05-18','eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZF91c2VyIjozOSwiaWRfdGVhY2hlcl9zdHVkZW50Ijo5LCJpZF9yb2xlX3VzZXIiOjV9.Rk6T4mcQkpuHZ8dv1+uY9PZjDXhuVV2AOhzXMqaTESE=');
/*!40000 ALTER TABLE `tokens_authorization` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `type_class`
--

DROP TABLE IF EXISTS `type_class`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `type_class` (
  `id` int NOT NULL AUTO_INCREMENT,
  `short_name` varchar(45) DEFAULT NULL,
  `full_name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `type_class`
--

LOCK TABLES `type_class` WRITE;
/*!40000 ALTER TABLE `type_class` DISABLE KEYS */;
INSERT INTO `type_class` VALUES (1,'Л','Лекция'),(2,'Лб','Лабораторная работа'),(3,'Кр','Контрольная работа');
/*!40000 ALTER TABLE `type_class` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `type_log`
--

DROP TABLE IF EXISTS `type_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `type_log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `type_log`
--

LOCK TABLES `type_log` WRITE;
/*!40000 ALTER TABLE `type_log` DISABLE KEYS */;
INSERT INTO `type_log` VALUES (1,'посещаемости'),(2,'успеваемости');
/*!40000 ALTER TABLE `type_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `type_post`
--

DROP TABLE IF EXISTS `type_post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `type_post` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `type_post`
--

LOCK TABLES `type_post` WRITE;
/*!40000 ALTER TABLE `type_post` DISABLE KEYS */;
INSERT INTO `type_post` VALUES (1,'сообщение'),(2,'уведомление');
/*!40000 ALTER TABLE `type_post` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `surname` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `patronymic` varchar(100) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `id_human_db_univ` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_human_db_univ_idx` (`id_human_db_univ`),
  CONSTRAINT `id_human_db_univ` FOREIGN KEY (`id_human_db_univ`) REFERENCES `dbuniversity`.`human` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=144 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (16,'Белая','Алина','Юрьевна','email@gmail.com','79491231122',11),(17,'Гоик','Георгий','Дмитриевич','email2@gmail.com','79491231123',14),(18,'Гончаров','Данил','Дмитриевич','email3@gmail.com','79491231124',15),(19,'Деркач','Кирилл','Игоревич','email4@gmail.com','79491231125',16),(20,'Лефтеров','Дмитрий','Дмитриевич','email5@gmail.com','79491231126',17),(21,'Пойденко','Полина','Александровна','email6@gmail.com','79491231127',18),(22,'Рядко','Максим','Алексеевич','email7@gmail.com','79491231128',19),(23,'Савенкова','Валерия','Олеговна','email8@gmail.com','79491231129',20),(24,'Слободяник','Артем','Игоревич','email9@gmail.com','79491231130',21),(25,'Шевченко','Михаил','Владимирович','email10@gmail.com','79491231131',22),(26,'Шестаков','Богдан','Андреевич','email11@gmail.com','79491231132',23),(27,'Яковченко','Артем','Александрович','email12@gmail.com','79491231133',24),(31,'Бабичев','Владислав','Александрович','email14@gmail.com','79491231135',26),(32,'Гейвандов','Илья','Романович','email15@gmail.com','79491231136',27),(33,'Карпович','Владимир','Дмитриевич','email16@gmail.com','79491231137',28),(34,'Клюйко','Данил','Андреевич','email17@gmail.com','79491231138',29),(35,'Лисиченко','Виктор','Тимофеевич','email18@gmail.com','79491231139',30),(36,'Моргунов','Владислав','Дмитриевич','email19@gmail.com','79491231140',31),(37,'Шныра','Богдан','Олегович','email20@gmail.com','79491231141',32),(38,'Агарков','Игорь','Анатольевич','email13@gmail.com','79491231134',25),(39,'Теплова','Ольга','Валентиновна','emailteacher1@gmail.com','79491231152',43),(40,'Матях','Ирина','Владимировна','emailteacher2@gmail.com','79491231153',44),(41,'Поляков','Александр','Иванович','emailteacher3@gmail.com','79491231154',45),(59,'Секирин','Александр','Иванович','emailteacher4@gmail.com','79491231155',46),(61,'Бражников','Виталий','Андреевич','email21@gmail.com','79491231142',33),(62,'Дворников','Денис','Евгеньевич','email22@gmail.com','79491231143',34),(63,'Дудник','Егор','Витальевич','email23@gmail.com','79491231144',35),(64,'Лунев','Дмитрий','Сергеевич','email24@gmail.com','79491231145',36),(65,'Павленко','Мирослав','Сергеевич','email25@gmail.com','79491231146',37),(66,'Пушкарь','Алексей','Дмитриевич','email26@gmail.com','79491231147',38),(67,'Сыгинь','Леонид','Ярославович','email27@gmail.com','79491231148',39),(68,'Шевцов','Максим','Викторович','email28@gmail.com','79491231149',40),(69,'Яковлев','Марк','Юрьевич','email29@gmail.com','79491231150',41),(70,'Ткаченко','Георгий','Александрович','email30@gmail.com','79491231151',42),(71,'Землянская','Светлана','Юрьевна','emailteacher6@gmail.com','79491231157',48),(72,'Шуватова','Екатерина','Александровна','emailteacher7@gmail.com','79491231158',49),(73,'Новиков','Дмитрий','Дмитриевич','emailteacher8@gmail.com','79491231159',50),(74,'Андриевская','Наталья','Климовна','emailteacher9@gmail.com','79491231160',51),(75,'Мартыненко','Татьяна','Владимировна','emailteacher10@gmail.com','79491231161',52),(76,'Пряхин','Владимир','Викторович','emailteacher11@gmail.com','79491231162',53),(78,'Савкова','Елена','Осиповна','emailteacher5@gmail.com','79491231156',47),(143,'Варанкин','Даниил','Вадимович','email31@gmail.com','79491231165',56);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'dbwebsite_university'
--

--
-- Dumping routines for database 'dbwebsite_university'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-05-23  4:37:06
