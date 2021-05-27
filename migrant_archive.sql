-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               5.6.24 - MySQL Community Server (GPL)
-- Операционная система:         Win64
-- HeidiSQL Версия:              11.0.0.5919
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Дамп структуры базы данных migrant_archive
CREATE DATABASE IF NOT EXISTS `migrant_archive` /*!40100 DEFAULT CHARACTER SET cp1251 */;
USE `migrant_archive`;

-- Дамп структуры для таблица migrant_archive.passport_files
DROP TABLE IF EXISTS `passport_files`;
CREATE TABLE IF NOT EXISTS `passport_files` (
  `id` int(11) NOT NULL DEFAULT '0',
  `filename` varchar(255) NOT NULL DEFAULT '' COMMENT 'название файла',
  `caption` text COMMENT 'Отображаемое название',
  `uploaded` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'дата загрузки',
  `deleted` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Удаленые',
  `download_id` varchar(255) DEFAULT NULL COMMENT 'hash для скачивания',
  PRIMARY KEY (`id`),
  KEY `IDX_passport_files_download_id` (`download_id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

-- Экспортируемые данные не выделены.

-- Дамп структуры для таблица migrant_archive.passport_processing
DROP TABLE IF EXISTS `passport_processing`;
CREATE TABLE IF NOT EXISTS `passport_processing` (
  `id` int(11) NOT NULL DEFAULT '0',
  `done` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

-- Экспортируемые данные не выделены.

-- Дамп структуры для таблица migrant_archive.people_archive
DROP TABLE IF EXISTS `people_archive`;
CREATE TABLE IF NOT EXISTS `people_archive` (
  `id` int(11) NOT NULL DEFAULT '0',
  `level_id` int(11) DEFAULT NULL COMMENT 'Уровень тестирования',
  `surname_rus` varchar(255) DEFAULT NULL,
  `name_rus` varchar(255) DEFAULT NULL,
  `surname_lat` varchar(255) DEFAULT NULL,
  `name_lat` varchar(255) DEFAULT NULL,
  `country` text,
  `country_id` int(11) DEFAULT '0',
  `cert_signer_caption` text COMMENT 'Кто подписывает',
  `cert_signer_position` text COMMENT 'порядок',
  `testing_date` date DEFAULT NULL COMMENT 'дата тестирования',
  `test_1_ball` double NOT NULL DEFAULT '0' COMMENT 'балы чтение',
  `test_2_ball` double NOT NULL DEFAULT '0' COMMENT 'балы письмо',
  `test_3_ball` double NOT NULL DEFAULT '0' COMMENT 'балы граматика',
  `test_4_ball` double NOT NULL DEFAULT '0' COMMENT 'балы аудиторование',
  `test_5_ball` double NOT NULL DEFAULT '0' COMMENT 'балы речь',
  `total` double NOT NULL DEFAULT '0' COMMENT 'сумма балов',
  `test_1_percent` double NOT NULL DEFAULT '0' COMMENT 'Процент чтение',
  `test_2_percent` double NOT NULL DEFAULT '0' COMMENT 'Процент письмо',
  `test_3_percent` double NOT NULL DEFAULT '0' COMMENT 'Процент граматика',
  `test_4_percent` double NOT NULL DEFAULT '0' COMMENT 'Процент аудирования',
  `test_5_percent` double NOT NULL DEFAULT '0' COMMENT 'процент речь',
  `total_percent` double NOT NULL DEFAULT '0' COMMENT 'процент среднее',
  `document` enum('note','certificate') DEFAULT NULL COMMENT 'Тип документа',
  `document_nomer` varchar(255) DEFAULT NULL COMMENT 'Номер документа',
  `passport_name` varchar(255) DEFAULT NULL COMMENT 'Наименование документа',
  `passport_series` varchar(255) DEFAULT NULL COMMENT 'Серия паспорта',
  `passport` varchar(255) DEFAULT NULL COMMENT 'номер',
  `passport_date` varchar(255) DEFAULT NULL COMMENT 'дата выдачи паспорта',
  `passport_department` varchar(255) DEFAULT NULL COMMENT 'Орган выдавший документ',
  `passport_file` int(11) NOT NULL DEFAULT '0' COMMENT 'скан паспорта',
  `birth_date` date DEFAULT NULL COMMENT 'дата рождения',
  `birth_place` varchar(255) DEFAULT NULL COMMENT 'место рождения',
  `migration_card_number` varchar(255) DEFAULT NULL COMMENT 'номер миграционной карты',
  `migration_card_series` varchar(255) DEFAULT NULL COMMENT 'серия миграционной карты',
  `valid_till` date DEFAULT NULL,
  `test_6_ball` double NOT NULL DEFAULT '0',
  `test_6_percent` double NOT NULL DEFAULT '0',
  `test_7_ball` double NOT NULL DEFAULT '0',
  `test_7_percent` double NOT NULL DEFAULT '0',
  `test_8_ball` double NOT NULL DEFAULT '0',
  `test_8_percent` double NOT NULL DEFAULT '0',
  `test_9_ball` double NOT NULL DEFAULT '0',
  `test_9_percent` double NOT NULL DEFAULT '0',
  `test_10_ball` double NOT NULL DEFAULT '0',
  `test_10_percent` double NOT NULL DEFAULT '0',
  `test_11_ball` double NOT NULL DEFAULT '0',
  `test_11_percent` double NOT NULL DEFAULT '0',
  `test_12_ball` double NOT NULL DEFAULT '0',
  `test_12_percent` double NOT NULL DEFAULT '0',
  `test_13_ball` double NOT NULL DEFAULT '0',
  `test_13_percent` double NOT NULL DEFAULT '0',
  `test_14_ball` double NOT NULL DEFAULT '0',
  `test_14_percent` double NOT NULL DEFAULT '0',
  `test_15_ball` double NOT NULL DEFAULT '0',
  `test_15_percent` double NOT NULL DEFAULT '0',
  `gender` char(1) DEFAULT NULL,
  `blank_date` date DEFAULT NULL,
  `blank_number` varchar(100) DEFAULT NULL,
  `original_blank_number` varchar(100) DEFAULT NULL,
  `dubl_issue_date` timestamp NULL DEFAULT NULL,
  `dubl_request_file_id` int(11) DEFAULT '0',
  `dubl_passport_file_id` int(11) DEFAULT '0',
  `dubl_cert_signer_caption` text COMMENT 'Кто подписывает',
  `dubl_cert_signer_position` text COMMENT 'порядок',
  `head_center` text COMMENT 'Короткое название',
  `annul` int(1) NOT NULL DEFAULT '0',
  `annul_reason` text,
  `annul_blank` varchar(50),
  `annul_date` date,
  PRIMARY KEY (`id`),
  KEY `IDX_people_archive_surname_rus` (`surname_rus`),
  KEY `IDX_people_archive_name_rus` (`name_rus`),
  KEY `IDX_people_archive_surname_lat` (`surname_lat`),
  KEY `IDX_people_archive_name_lat` (`name_lat`),
  KEY `IDX_people_archive_document` (`document`),
  KEY `IDX_people_archive_document_nomer` (`document_nomer`),
  KEY `IDX_people_archive_blank_number` (`blank_number`),
  KEY `IDX_people_archive_annul` (`annul`),
  KEY `IDX_people_archive_annul_blank` (`annul_blank`),
  KEY `IDX_people_archive_original_blank_number` (`original_blank_number`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

-- Экспортируемые данные не выделены.

-- Дамп структуры для таблица migrant_archive.photo_files
DROP TABLE IF EXISTS `photo_files`;
CREATE TABLE IF NOT EXISTS `photo_files` (
  `id` int(11) NOT NULL DEFAULT '0',
  `filename` varchar(255) NOT NULL DEFAULT '' COMMENT 'название файла',
  `caption` text COMMENT 'Отображаемое название',
  `uploaded` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'дата загрузки',
  `deleted` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Удаленые',
  `download_id` varchar(255) DEFAULT NULL COMMENT 'hash для скачивания',
  `type` varchar(50) NOT NULL,
  `man_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_photo_files2` (`man_id`,`deleted`),
  KEY `IDX_photo_files_download_id` (`download_id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

-- Экспортируемые данные не выделены.

-- Дамп структуры для таблица migrant_archive.photo_processing
DROP TABLE IF EXISTS `photo_processing`;
CREATE TABLE IF NOT EXISTS `photo_processing` (
  `id` int(11) NOT NULL DEFAULT '0',
  `done` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

-- Экспортируемые данные не выделены.

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
