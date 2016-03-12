-- MySQL dump 10.13  Distrib 5.6.17, for Win32 (x86)
--
-- Host: localhost    Database: reference_checker
-- ------------------------------------------------------
-- Server version	5.6.17

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `login_attempts`
--

DROP TABLE IF EXISTS `login_attempts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `login_attempts` (
  `user_id` int(11) NOT NULL,
  `time` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `login_attempts`
--

LOCK TABLES `login_attempts` WRITE;
/*!40000 ALTER TABLE `login_attempts` DISABLE KEYS */;
INSERT INTO `login_attempts` VALUES (2,'1457117578'),(2,'1457144753'),(2,'1457144919'),(2,'1457145394'),(5,'1457524211'),(2,'1457528682'),(2,'1457528685'),(2,'1457528688'),(2,'1457528692'),(2,'1457528703'),(2,'1457528706');
/*!40000 ALTER TABLE `login_attempts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article_title` text NOT NULL,
  `article_url` text NOT NULL,
  `article_references` int(11) NOT NULL,
  `date` date NOT NULL,
  `views` int(11) NOT NULL DEFAULT '0',
  `category` text NOT NULL,
  `article_author` text NOT NULL,
  `author` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `posts_ibfk_1` (`author`),
  KEY `posts_reference_id` (`article_references`),
  CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`author`) REFERENCES `users` (`id`),
  CONSTRAINT `posts_reference_id` FOREIGN KEY (`article_references`) REFERENCES `reference` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (2,'Medical Report on Latest Findings Of Neurology','http://google.com/',2,'2016-03-09',2,'Neurology','Brian',4),(3,'DNA Evolutionary Study','http://youtube.com',3,'2016-03-09',2,'Gastroenterology','Chris Lao',5),(9,'Risk of non-fatal cardiovascular diseases in early-onset','http://www.thelancet.com/journals/landia/article/PIIS2213858715005082/references',9,'2016-03-10',4,'Endocrinology','Xiaoxu Hu',2),(13,'Metformin for chemoprevention of metachronous colorectal adenoma or polyps in post-polypectomy patients without diabetes: a multicentre double-blind, placebo-controlled, randomised','http://www.thelancet.com/journals/lanonc/article/PIIS1470-2045(15)00565-3/abstract',13,'2016-03-10',3,'Endocrinology','Takuma Higurashi, MD, Kunihiro Hosono, MD, Hirokazu Takahashi, MD, Yasuhiko Komiya, MD, Shotaro Umezawa, MD, Eiji Sakai, MD, Takashi Uchiyama, MD, Leo Taniguchi, MD, Yasuo Hata, MD',2);
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reference`
--

DROP TABLE IF EXISTS `reference`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reference` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference_text` text NOT NULL,
  `likes` int(11) NOT NULL DEFAULT '0',
  `dislikes` int(11) NOT NULL DEFAULT '0',
  `suggestions` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `suggestions` (`suggestions`),
  CONSTRAINT `reference_ibfk_1` FOREIGN KEY (`suggestions`) REFERENCES `suggestion_list` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reference`
--

LOCK TABLES `reference` WRITE;
/*!40000 ALTER TABLE `reference` DISABLE KEYS */;
INSERT INTO `reference` VALUES (2,'1) asodaiosdiahdsoasd\r\n2) as dasndo - sd asndoasnd\r\n3) to be or not to be\r\n4)\r\n5) asdalind0/;asd192j12onoas#*92',0,0,NULL),(3,'1) kasdaonasjdnasodnioqwen\r\n2) aksdasdnajksndjaksdnjkasd\r\n3) kasdakdnjkasndjkasdnjkasdnasj\r\n4) akdjabskdbajskda\r\n',0,0,NULL),(9,'1. van Dieren, S, Beulens, JW, van der Schouw, YT, Grobbee, DE, and Neal, B. The global burden of diabetes and its complications: an emerging pandemic. Eur J Cardiovasc Prev Rehabil. 2010; 17: S3–S8\r\n2. Song, SH and Hardisty, CA. Early-onset type 2 diabetes mellitus: an increasing phenomenon of elevated cardiovascular risk. Expert Rev Cardiovasc Ther. 2008; 6: 315–322\r\n3. Chowdhury, TA and Lasker, SS. Complications and cardiovascular risk factors in South Asians and Europeans with early-onset type 2 diabetes. QJM. 2002; 95: 241–246\r\n4. Hillier, TA and Pedula, KL. Complications in young adults with early-onset type 2 diabetes: losing the relative protection of youth. Diabetes Care. 2003; 26: 2999–3005',0,0,NULL),(13,'1. Torre, LA, Bray, F, Siegel, RL, Ferlay, J, Lortet-Tieulent, J, and Jemal, A. Global cancer statistics, 2012. CA Cancer J Clin. 2015; 65: 87–108\r\n2. Winawer, SJ, Zauber, AG, Ho, MN et al. Prevention of colorectal cancer by colonoscopic polypectomy. The National Polyp Study Workgroup. N Engl J Med. 1993; 329: 1977–1981\r\n3. Imperiale, TF, Glowinski, EA, Lin-Cooper, C, Larkin, GN, Rogge, JD, and Ransohoff, DF. Five-year risk of colorectal neoplasia after negative screening colonoscopy. N Engl J Med. 2008; 359: 1218–1224\r\n4. Das, D, Arber, N, and Jankowski, JA. Chemoprevention of colorectal cancer. Digestion. 2007; 76: 51–67',0,0,NULL);
/*!40000 ALTER TABLE `reference` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `suggestion`
--

DROP TABLE IF EXISTS `suggestion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `suggestion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `error_details` text,
  `correction` text,
  `citation_error` tinyint(1) NOT NULL DEFAULT '0',
  `likes` int(11) NOT NULL DEFAULT '0',
  `dislikes` int(11) NOT NULL DEFAULT '0',
  `by` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `suggestion_by` (`by`),
  CONSTRAINT `suggestion_by_cnstrt` FOREIGN KEY (`by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suggestion`
--

LOCK TABLES `suggestion` WRITE;
/*!40000 ALTER TABLE `suggestion` DISABLE KEYS */;
/*!40000 ALTER TABLE `suggestion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `suggestion_list`
--

DROP TABLE IF EXISTS `suggestion_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `suggestion_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `suggestion` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `suggestion` (`suggestion`),
  CONSTRAINT `suggestion_list_ibfk_1` FOREIGN KEY (`suggestion`) REFERENCES `suggestion` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suggestion_list`
--

LOCK TABLES `suggestion_list` WRITE;
/*!40000 ALTER TABLE `suggestion_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `suggestion_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `email` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (2,'bymi15','$2y$10$AEoi2LeM3h0f4xS6TJkzROFsNj3hdVA6/mHANpfdZNj.BPmyyKj0y','bymi15@yahoo.com'),(3,'sakana15','$2y$10$U3ITaSAR5JcVsQpzNLitn.M0CSVQrRZNlHnmh/ocP6CkaJhU0TGm2','sakana15@live.com'),(4,'ekko123','$2y$10$ZNBtOCVRd3C6HDWbJu9GbOsXXqLuwf6iQvc7SMXbsOFMavz1vs1QC','ekko@ucl.ac.uk'),(5,'chris','$2y$10$cWcPhGXA0.lSbg/5VeEW7.i7SyGGUpxr5iFl/mnyRHcAX6ESw5f8u','chris@chris.chris');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-03-12  7:56:29
