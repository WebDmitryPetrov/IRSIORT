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


-- Дамп структуры базы данных migrants
CREATE DATABASE IF NOT EXISTS `migrants` /*!40100 DEFAULT CHARACTER SET cp1251 */;
USE `migrants`;

-- Дамп структуры для таблица migrants.act_metadata
DROP TABLE IF EXISTS `act_metadata`;
CREATE TABLE IF NOT EXISTS `act_metadata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `act_id` int(11) NOT NULL DEFAULT '0',
  `special_price_group` int(11) NOT NULL DEFAULT '0',
  `test_group` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UK_act_special_price_act_id` (`act_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=cp1251;

-- Дамп данных таблицы migrants.act_metadata: ~0 rows (приблизительно)
DELETE FROM `act_metadata`;
/*!40000 ALTER TABLE `act_metadata` DISABLE KEYS */;
/*!40000 ALTER TABLE `act_metadata` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.act_summary_table
DROP TABLE IF EXISTS `act_summary_table`;
CREATE TABLE IF NOT EXISTS `act_summary_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL DEFAULT '' COMMENT 'название файла',
  `caption` text COMMENT 'Отображаемое название',
  `uploaded` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'дата загрузки',
  `user_id` int(11) DEFAULT NULL COMMENT 'id загрузившего',
  `deleted` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Удаленые',
  `download_id` varchar(255) DEFAULT NULL COMMENT 'hash для скачивания',
  `type` enum('summary','act','act_table') DEFAULT 'summary' COMMENT 'тип файла (сводный протокол, акт или сводная таблица)',
  PRIMARY KEY (`id`),
  KEY `download_id` (`download_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=cp1251 COMMENT='Сканы сводных таблиц';

-- Дамп данных таблицы migrants.act_summary_table: ~0 rows (приблизительно)
DELETE FROM `act_summary_table`;
/*!40000 ALTER TABLE `act_summary_table` DISABLE KEYS */;
/*!40000 ALTER TABLE `act_summary_table` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.blank_types
DROP TABLE IF EXISTS `blank_types`;
CREATE TABLE IF NOT EXISTS `blank_types` (
  `id` int(10) NOT NULL DEFAULT '1',
  `test_level_type_id` int(10) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `default` int(10) DEFAULT '0',
  `visible` int(10) DEFAULT '1',
  PRIMARY KEY (`id`,`test_level_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

-- Дамп данных таблицы migrants.blank_types: ~2 rows (приблизительно)
DELETE FROM `blank_types`;
/*!40000 ALTER TABLE `blank_types` DISABLE KEYS */;
INSERT INTO `blank_types` (`id`, `test_level_type_id`, `name`, `default`, `visible`) VALUES
	(1, 1, 'Старый', 1, 1),
	(2, 1, 'Новый', 0, 1);
/*!40000 ALTER TABLE `blank_types` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.certificate_duplicate
DROP TABLE IF EXISTS `certificate_duplicate`;
CREATE TABLE IF NOT EXISTS `certificate_duplicate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `surname_rus` varchar(255) NOT NULL DEFAULT '',
  `name_rus` varchar(255) NOT NULL DEFAULT '',
  `surname_lat` varchar(255) NOT NULL DEFAULT '',
  `name_lat` varchar(255) NOT NULL DEFAULT '',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `certificate_id` int(11) NOT NULL DEFAULT '0',
  `certificate_number` varchar(50) NOT NULL DEFAULT '',
  `certificate_issue_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `certificate_print_date` date NOT NULL DEFAULT '0000-00-00',
  `request_file_id` int(11) NOT NULL DEFAULT '0',
  `passport_file_id` int(11) NOT NULL DEFAULT '0',
  `personal_data_changed` tinyint(4) NOT NULL DEFAULT '0',
  `cert_signer` int(11) DEFAULT NULL,
  `deleted` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `certificate_id` (`certificate_id`),
  KEY `certificate_number` (`certificate_number`,`user_id`),
  KEY `surname_rus` (`surname_rus`,`surname_lat`),
  KEY `surname_lat` (`surname_lat`),
  KEY `name_rus` (`name_rus`,`name_lat`),
  KEY `name_lat` (`name_lat`),
  KEY `certificate_issue_date` (`certificate_issue_date`,`deleted`),
  KEY `certificate_print_date` (`certificate_print_date`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=cp1251;

-- Дамп данных таблицы migrants.certificate_duplicate: ~0 rows (приблизительно)
DELETE FROM `certificate_duplicate`;
/*!40000 ALTER TABLE `certificate_duplicate` DISABLE KEYS */;
/*!40000 ALTER TABLE `certificate_duplicate` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.certificate_invalid
DROP TABLE IF EXISTS `certificate_invalid`;
CREATE TABLE IF NOT EXISTS `certificate_invalid` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `certificate_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `number` varchar(50) NOT NULL DEFAULT '',
  `test_type_id` int(11) NOT NULL DEFAULT '0',
  `blank_date` date DEFAULT NULL,
  `reason` text,
  `ts` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `object_type` varchar(50) DEFAULT 'man' COMMENT 'man - обычная выдача сертификата, dubl - по дубликату',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `test_type_id` (`test_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=cp1251;

-- Дамп данных таблицы migrants.certificate_invalid: ~0 rows (приблизительно)
DELETE FROM `certificate_invalid`;
/*!40000 ALTER TABLE `certificate_invalid` DISABLE KEYS */;
/*!40000 ALTER TABLE `certificate_invalid` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.certificate_reserved
DROP TABLE IF EXISTS `certificate_reserved`;
CREATE TABLE IF NOT EXISTS `certificate_reserved` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` varchar(50) NOT NULL DEFAULT '',
  `test_type_id` int(11) NOT NULL DEFAULT '0',
  `blank_type_id` int(11) NOT NULL DEFAULT '1',
  `head_center_id` int(11) NOT NULL DEFAULT '0',
  `used` tinyint(1) NOT NULL DEFAULT '0',
  `invalid` tinyint(1) NOT NULL DEFAULT '0',
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `local_center_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_number` (`number`,`test_type_id`),
  KEY `head_center_id` (`head_center_id`,`used`,`invalid`,`test_type_id`,`id`)
) ENGINE=InnoDB AUTO_INCREMENT=125 DEFAULT CHARSET=cp1251 COMMENT='Список добавленных сертификатов';

-- Дамп данных таблицы migrants.certificate_reserved: ~0 rows (приблизительно)
DELETE FROM `certificate_reserved`;
/*!40000 ALTER TABLE `certificate_reserved` DISABLE KEYS */;
/*!40000 ALTER TABLE `certificate_reserved` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.certificate_used
DROP TABLE IF EXISTS `certificate_used`;
CREATE TABLE IF NOT EXISTS `certificate_used` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fio` text NOT NULL,
  `number` varchar(50) NOT NULL DEFAULT '',
  `level_id` int(11) NOT NULL DEFAULT '0',
  `data` text NOT NULL,
  `blank_id` int(11) NOT NULL DEFAULT '0',
  `timest` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `level_id` (`level_id`),
  KEY `blank_id` (`blank_id`),
  KEY `user_id` (`user_id`),
  KEY `timest` (`timest`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=cp1251;

-- Дамп данных таблицы migrants.certificate_used: ~0 rows (приблизительно)
DELETE FROM `certificate_used`;
/*!40000 ALTER TABLE `certificate_used` DISABLE KEYS */;
/*!40000 ALTER TABLE `certificate_used` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.certs_annul
DROP TABLE IF EXISTS `certs_annul`;
CREATE TABLE IF NOT EXISTS `certs_annul` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `man_id` int(11) NOT NULL,
  `level_type_id` int(11) DEFAULT NULL,
  `blank_id` int(11) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) NOT NULL,
  `blank_number` varchar(50) NOT NULL,
  `reg_number` varchar(50) DEFAULT NULL,
  `date_annul` date NOT NULL,
  `man_name_ru` varchar(200) NOT NULL,
  `man_surname_ru` varchar(200) NOT NULL,
  `man_name_en` varchar(200) NOT NULL,
  `man_surname_en` varchar(200) NOT NULL,
  `reason` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `man_id` (`man_id`),
  KEY `blank_id` (`blank_id`),
  KEY `blank_number` (`blank_number`),
  KEY `man_name_ru` (`man_name_ru`,`man_name_en`),
  KEY `man_surname_ru` (`man_surname_ru`,`man_surname_en`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=cp1251 COMMENT='Список аннулированных сертификатов';

-- Дамп данных таблицы migrants.certs_annul: ~0 rows (приблизительно)
DELETE FROM `certs_annul`;
/*!40000 ALTER TABLE `certs_annul` DISABLE KEYS */;
/*!40000 ALTER TABLE `certs_annul` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.country
DROP TABLE IF EXISTS `country`;
CREATE TABLE IF NOT EXISTS `country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text,
  PRIMARY KEY (`id`),
  KEY `name` (`name`(400))
) ENGINE=InnoDB AUTO_INCREMENT=216 DEFAULT CHARSET=cp1251;

-- Дамп данных таблицы migrants.country: ~215 rows (приблизительно)
DELETE FROM `country`;
/*!40000 ALTER TABLE `country` DISABLE KEYS */;
INSERT INTO `country` (`id`, `name`) VALUES
	(1, 'ЛБГ'),
	(2, 'Австралия'),
	(3, 'Австрия'),
	(4, 'Азербайджан'),
	(5, 'Албания'),
	(6, 'Алжир'),
	(7, 'Ангола'),
	(8, 'Андорра'),
	(9, 'Антигуа и Барбуда'),
	(10, 'Аргентина'),
	(11, 'Республика Армения'),
	(12, 'Афганистан'),
	(13, 'Багамы'),
	(14, 'Бангладеш'),
	(15, 'Барбадос'),
	(16, 'Бахрейн'),
	(17, 'Республика Беларусь'),
	(18, 'Белиз'),
	(19, 'Бельгия'),
	(20, 'Бенин'),
	(21, 'Болгария'),
	(22, 'Боливия'),
	(23, 'Босния и Герцеговина'),
	(24, 'Ботсвана'),
	(25, 'Бразилия'),
	(26, 'Бруней'),
	(27, 'Буркина-Фасо'),
	(28, 'Бурунди'),
	(29, 'Бутан'),
	(30, 'Вануату'),
	(31, 'Ватикан'),
	(32, 'Великобритания'),
	(33, 'Венгрия'),
	(34, 'Венесуэла'),
	(35, 'Восточный Тимор'),
	(36, 'Вьетнам'),
	(37, 'Габон'),
	(38, 'Гаити'),
	(39, 'Гайана'),
	(40, 'Гамбия'),
	(41, 'Гана'),
	(42, 'Гватемала'),
	(43, 'Гвинея'),
	(44, 'Гвинея-Бисау'),
	(45, 'Германия'),
	(46, 'Гондурас'),
	(47, 'Гренада'),
	(48, 'Греция'),
	(49, 'Грузия'),
	(50, 'Дания'),
	(51, 'Джибути'),
	(52, 'Доминика'),
	(53, 'Доминиканская Республика'),
	(54, 'Египет'),
	(55, 'Замбия'),
	(56, 'Зимбабве'),
	(57, 'Израиль'),
	(58, 'Индия'),
	(59, 'Индонезия'),
	(60, 'Иордания'),
	(61, 'Ирак'),
	(62, 'Иран'),
	(63, 'Ирландия'),
	(64, 'Исландия'),
	(65, 'Испания'),
	(66, 'Италия'),
	(67, 'Йемен'),
	(68, 'Кабо-Верде'),
	(69, 'Казахстан'),
	(70, 'Камбоджа'),
	(71, 'Камерун'),
	(72, 'Канада'),
	(73, 'Катар'),
	(74, 'Кения'),
	(75, 'Кипр'),
	(76, 'Кыргызская Республика'),
	(77, 'Кирибати'),
	(78, 'КНР'),
	(79, 'Колумбия'),
	(80, 'Коморы'),
	(81, 'Республика Конго'),
	(82, 'ДР Конго'),
	(83, 'КНДР'),
	(84, 'Республика Корея'),
	(85, 'Коста-Рика'),
	(86, 'Кот-д’Ивуар'),
	(87, 'Куба'),
	(88, 'Кувейт'),
	(89, 'Лаос'),
	(90, 'Латвия'),
	(91, 'Лесото'),
	(92, 'Либерия'),
	(93, 'Ливан'),
	(94, 'Ливия'),
	(95, 'Литва'),
	(96, 'Лихтенштейн'),
	(97, 'Люксембург'),
	(98, 'Маврикий'),
	(99, 'Мавритания'),
	(100, 'Мадагаскар'),
	(101, 'Македония'),
	(102, 'Малави'),
	(103, 'Малайзия'),
	(104, 'Мали'),
	(105, 'Мальдивы'),
	(106, 'Мальта'),
	(107, 'Марокко'),
	(108, 'Маршалловы Острова'),
	(109, 'Мексика'),
	(110, 'Мозамбик'),
	(111, 'Республика Молдова'),
	(112, 'Монако'),
	(113, 'Монголия'),
	(114, 'Мьянма'),
	(115, 'Намибия'),
	(116, 'Науру'),
	(117, 'Непал'),
	(118, 'Нигер'),
	(119, 'Нигерия'),
	(120, 'Нидерланды'),
	(121, 'Никарагуа'),
	(122, 'Новая Зеландия'),
	(123, 'Норвегия'),
	(124, 'ОАЭ'),
	(125, 'Оман'),
	(126, 'Пакистан'),
	(127, 'Палау'),
	(128, 'Панама'),
	(129, 'Папуа'),
	(130, 'Парагвай'),
	(131, 'Перу'),
	(132, 'Польша'),
	(133, 'Португалия'),
	(134, 'Россия'),
	(135, 'Руанда'),
	(136, 'Румыния'),
	(137, 'Сальвадор'),
	(138, 'Самоа'),
	(139, 'Сан-Марино'),
	(140, 'Сан-Томе и Принсипи'),
	(141, 'Саудовская Аравия'),
	(142, 'Свазиленд'),
	(143, 'Сейшельские Острова'),
	(144, 'Сенегал'),
	(145, 'Сент-Винсент и Гренадины'),
	(146, 'Сент-Китс и Невис'),
	(147, 'Сент-Люсия'),
	(148, 'Сербия'),
	(149, 'Сингапур'),
	(150, 'Сирия'),
	(151, 'Словакия'),
	(152, 'Словения'),
	(153, 'США'),
	(154, 'Соломоновы Острова'),
	(155, 'Сомали'),
	(156, 'Судан'),
	(157, 'Суринам'),
	(158, 'Сьерра-Леоне'),
	(159, 'Таджикистан'),
	(160, 'Таиланд'),
	(161, 'Танзания'),
	(162, 'Того'),
	(163, 'Тонга'),
	(164, 'Тринидад и Тобаго'),
	(165, 'Тувалу'),
	(166, 'Тунис'),
	(167, 'Туркменистан'),
	(168, 'Турция'),
	(169, 'Уганда'),
	(170, 'Узбекистан'),
	(171, 'Украина'),
	(172, 'Уругвай'),
	(173, 'Федеративные Штаты Микронезии'),
	(174, 'Фиджи'),
	(175, 'Филиппины'),
	(176, 'Финляндия'),
	(177, 'Франция'),
	(178, 'Хорватия'),
	(179, 'ЦАР'),
	(180, 'Чад'),
	(181, 'Черногория'),
	(182, 'Чехия'),
	(183, 'Чили'),
	(184, 'Швейцария'),
	(185, 'Швеция'),
	(186, 'Шри-Ланка'),
	(187, 'Эквадор'),
	(188, 'Экваториальная Гвинея'),
	(189, 'Эритрея'),
	(190, 'Эстония'),
	(191, 'Эфиопия'),
	(192, 'ЮАР'),
	(193, 'Южный Судан'),
	(194, 'Ямайка'),
	(195, 'Япония'),
	(196, 'Абхазия'),
	(197, 'Вазиристан'),
	(198, 'Галмудуг'),
	(199, 'Иракский Курдистан'),
	(200, 'Республика Косово'),
	(201, 'Нагорно-Карабахская Республика'),
	(202, 'Приднестровская Молдавская Республика'),
	(203, 'Пунтленд'),
	(204, 'Сахарская Арабская Демократическая Республика'),
	(205, 'Турецкая Республика Северного Кипра'),
	(206, 'Силенд'),
	(207, 'Сомалиленд'),
	(208, 'Китайская Народная Республика'),
	(209, 'Южная Осетия'),
	(210, 'Азад Кашмир'),
	(211, 'Государство Шан'),
	(212, 'Государство Ва'),
	(213, 'Лицо без гражданства'),
	(214, 'Палестина'),
	(215, 'ДНР и ЛНР');
/*!40000 ALTER TABLE `country` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.document_numbers_used
DROP TABLE IF EXISTS `document_numbers_used`;
CREATE TABLE IF NOT EXISTS `document_numbers_used` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` varchar(50) NOT NULL DEFAULT '',
  `test_type_id` int(11) NOT NULL DEFAULT '0',
  `head_center_id` int(11) NOT NULL DEFAULT '0',
  `doc_type` enum('note','certificate') NOT NULL DEFAULT 'note',
  `prefix` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_number` (`number`,`test_type_id`,`doc_type`,`prefix`),
  KEY `head_center_id` (`head_center_id`),
  KEY `doc_type` (`doc_type`,`prefix`,`test_type_id`,`number`),
  KEY `doc_type_note` (`doc_type`,`prefix`,`number`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=cp1251 COMMENT='Список использованых номеров';

-- Дамп данных таблицы migrants.document_numbers_used: ~0 rows (приблизительно)
DELETE FROM `document_numbers_used`;
/*!40000 ALTER TABLE `document_numbers_used` DISABLE KEYS */;
/*!40000 ALTER TABLE `document_numbers_used` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.dubl_act
DROP TABLE IF EXISTS `dubl_act`;
CREATE TABLE IF NOT EXISTS `dubl_act` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `state` varchar(50) NOT NULL DEFAULT '',
  `comment` text NOT NULL,
  `center_id` int(11) NOT NULL DEFAULT '0',
  `center_dogovor_id` int(11) NOT NULL DEFAULT '0' COMMENT 'id договора',
  `file_request_id` int(11) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `invoice_index` varchar(255) DEFAULT NULL COMMENT 'индекс подразделения для счета',
  `invoice` text COMMENT 'номер счета',
  `invoice_date` date DEFAULT NULL COMMENT 'Дата счета',
  `signing` int(11) DEFAULT NULL COMMENT 'Кто подписывает счет',
  `official` text COMMENT 'Кто утверждает от ЦТ (Долж.,фио)',
  `invoice_price` int(11) DEFAULT NULL,
  `test_level_type_id` int(11) DEFAULT NULL,
  `accepted_date` datetime DEFAULT NULL,
  `summary_table_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `center_id` (`center_id`,`state`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=cp1251;

-- Дамп данных таблицы migrants.dubl_act: ~0 rows (приблизительно)
DELETE FROM `dubl_act`;
/*!40000 ALTER TABLE `dubl_act` DISABLE KEYS */;
/*!40000 ALTER TABLE `dubl_act` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.dubl_act_man
DROP TABLE IF EXISTS `dubl_act_man`;
CREATE TABLE IF NOT EXISTS `dubl_act_man` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `act_id` int(11) NOT NULL DEFAULT '0',
  `old_man_id` int(11) DEFAULT NULL,
  `file_passport_id` int(11) NOT NULL DEFAULT '0',
  `file_request_id` int(11) DEFAULT NULL,
  `surname_rus_old` varchar(255) NOT NULL DEFAULT '0',
  `surname_lat_old` varchar(255) NOT NULL DEFAULT '0',
  `surname_rus_new` varchar(255) NOT NULL DEFAULT '0',
  `surname_lat_new` varchar(255) NOT NULL DEFAULT '0',
  `name_rus_old` varchar(255) NOT NULL DEFAULT '0',
  `name_lat_old` varchar(255) NOT NULL DEFAULT '0',
  `name_rus_new` varchar(255) NOT NULL DEFAULT '0',
  `name_lat_new` varchar(255) NOT NULL DEFAULT '0',
  `is_changed` tinyint(4) NOT NULL DEFAULT '0',
  `dubl_cert_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `act_id` (`act_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=cp1251;

-- Дамп данных таблицы migrants.dubl_act_man: ~0 rows (приблизительно)
DELETE FROM `dubl_act_man`;
/*!40000 ALTER TABLE `dubl_act_man` DISABLE KEYS */;
/*!40000 ALTER TABLE `dubl_act_man` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.federal_dc
DROP TABLE IF EXISTS `federal_dc`;
CREATE TABLE IF NOT EXISTS `federal_dc` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `caption` varchar(50) DEFAULT NULL,
  `full_caption` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=cp1251;

-- Дамп данных таблицы migrants.federal_dc: ~9 rows (приблизительно)
DELETE FROM `federal_dc`;
/*!40000 ALTER TABLE `federal_dc` DISABLE KEYS */;
INSERT INTO `federal_dc` (`id`, `caption`, `full_caption`) VALUES
	(1, 'Центральный', 'Центральный федеральный округ'),
	(2, 'Южный', 'Южный федеральный округ'),
	(3, 'Северо-Западный', 'Северо-Западный федеральный округ'),
	(4, 'Дальневосточный', 'Дальневосточный федеральный округ'),
	(5, 'Сибирский', 'Сибирский федеральный округ'),
	(6, 'Уральский', 'Уральский федеральный округ'),
	(7, 'Приволжский', 'Приволжский федеральный округ'),
	(8, 'Северо-Кавказский', 'Северо-Кавказский федеральный округ'),
	(9, 'Крымский', 'Крымский федеральный округ');
/*!40000 ALTER TABLE `federal_dc` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.federal_dc_region
DROP TABLE IF EXISTS `federal_dc_region`;
CREATE TABLE IF NOT EXISTS `federal_dc_region` (
  `region_id` int(10) NOT NULL DEFAULT '0',
  `dc_id` int(10) NOT NULL DEFAULT '0',
  KEY `region_id` (`region_id`),
  KEY `dc_id` (`dc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

-- Дамп данных таблицы migrants.federal_dc_region: ~85 rows (приблизительно)
DELETE FROM `federal_dc_region`;
/*!40000 ALTER TABLE `federal_dc_region` DISABLE KEYS */;
INSERT INTO `federal_dc_region` (`region_id`, `dc_id`) VALUES
	(34, 2),
	(38, 2),
	(26, 2),
	(1, 2),
	(8, 2),
	(63, 2),
	(33, 3),
	(39, 3),
	(43, 3),
	(50, 3),
	(54, 3),
	(82, 3),
	(56, 3),
	(62, 3),
	(10, 3),
	(11, 3),
	(79, 3),
	(32, 4),
	(81, 4),
	(25, 4),
	(52, 4),
	(29, 4),
	(15, 4),
	(67, 4),
	(31, 4),
	(84, 4),
	(23, 5),
	(24, 5),
	(42, 5),
	(45, 5),
	(27, 5),
	(57, 5),
	(58, 5),
	(2, 5),
	(4, 5),
	(18, 5),
	(20, 5),
	(72, 5),
	(46, 7),
	(55, 7),
	(59, 7),
	(61, 7),
	(28, 7),
	(3, 7),
	(13, 7),
	(14, 7),
	(17, 7),
	(65, 7),
	(66, 7),
	(19, 7),
	(75, 7),
	(22, 7),
	(7, 8),
	(9, 8),
	(5, 8),
	(6, 8),
	(16, 8),
	(30, 8),
	(21, 8),
	(48, 6),
	(68, 6),
	(74, 6),
	(83, 6),
	(76, 6),
	(85, 6),
	(12, 9),
	(80, 9),
	(35, 1),
	(36, 1),
	(37, 1),
	(40, 1),
	(41, 1),
	(44, 1),
	(47, 1),
	(49, 1),
	(51, 1),
	(78, 1),
	(53, 1),
	(60, 1),
	(64, 1),
	(69, 1),
	(70, 1),
	(71, 1),
	(73, 1),
	(77, 1);
/*!40000 ALTER TABLE `federal_dc_region` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.fms_regions
DROP TABLE IF EXISTS `fms_regions`;
CREATE TABLE IF NOT EXISTS `fms_regions` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `caption` varchar(255) DEFAULT NULL,
  `r_num` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=cp1251;

-- Дамп данных таблицы migrants.fms_regions: ~85 rows (приблизительно)
DELETE FROM `fms_regions`;
/*!40000 ALTER TABLE `fms_regions` DISABLE KEYS */;
INSERT INTO `fms_regions` (`id`, `caption`, `r_num`) VALUES
	(1, 'Республика Адыгея', '79'),
	(2, 'Республика Алтай', '84'),
	(3, 'Республика Башкортостан', '80'),
	(4, 'Республика Бурятия', '81'),
	(5, 'Республика Дагестан', '82'),
	(6, 'Республика Ингушетия', '26'),
	(7, 'Кабардино-Балкарская республика', '83'),
	(8, 'Республика Калмыкия', '85'),
	(9, 'Карачаево-Черкесская республика', '91'),
	(10, 'Республика Карелия', '86'),
	(11, 'Республика Коми', '87'),
	(12, 'Республика Крым', '35'),
	(13, 'Республика Марий Эл', '88'),
	(14, 'Республика Мордовия', '89'),
	(15, 'Республика Саха (Якутия)', '98'),
	(16, 'Республика Северная Осетия — Алания', '90'),
	(17, 'Республика Татарстан', '92'),
	(18, 'Республика Тыва', '93'),
	(19, 'Удмуртская республика', '94'),
	(20, 'Республика Хакасия', '95'),
	(21, 'Чеченская республика', '96'),
	(22, 'Чувашская республика', '97'),
	(23, 'Алтайский край', '01'),
	(24, 'Забайкальский край', '76'),
	(25, 'Камчатский край', '30'),
	(26, 'Краснодарский край', '03'),
	(27, 'Красноярский край', '04'),
	(28, 'Пермский край', '57'),
	(29, 'Приморский край', '05'),
	(30, 'Ставропольский край', '07'),
	(31, 'Хабаровский край', '08'),
	(32, 'Амурская область', '10'),
	(33, 'Архангельская область', '11'),
	(34, 'Астраханская область', '12'),
	(35, 'Белгородская область', '14'),
	(36, 'Брянская область', '15'),
	(37, 'Владимирская область', '17'),
	(38, 'Волгоградская область', '18'),
	(39, 'Вологодская область', '19'),
	(40, 'Воронежская область', '20'),
	(41, 'Ивановская область', '24'),
	(42, 'Иркутская область', '25'),
	(43, 'Калининградская область', '27'),
	(44, 'Калужская область', '29'),
	(45, 'Кемеровская область', '32'),
	(46, 'Кировская область', '33'),
	(47, 'Костромская область', '34'),
	(48, 'Курганская область', '37'),
	(49, 'Курская область', '38'),
	(50, 'Ленинградская область', '41'),
	(51, 'Липецкая область', '42'),
	(52, 'Магаданская область', '44'),
	(53, 'Московская область', '46'),
	(54, 'Мурманская область', '47'),
	(55, 'Нижегородская область', '22'),
	(56, 'Новгородская область', '49'),
	(57, 'Новосибирская область', '50'),
	(58, 'Омская область', '52'),
	(59, 'Оренбургская область', '53'),
	(60, 'Орловская область', '54'),
	(61, 'Пензенская область', '56'),
	(62, 'Псковская область', '58'),
	(63, 'Ростовская область', '60'),
	(64, 'Рязанская область', '61'),
	(65, 'Самарская область', '36'),
	(66, 'Саратовская область', '63'),
	(67, 'Сахалинская область', '64'),
	(68, 'Свердловская область', '65'),
	(69, 'Смоленская область', '66'),
	(70, 'Тамбовская область', '68'),
	(71, 'Тверская область', '28'),
	(72, 'Томская область', '69'),
	(73, 'Тульская область', '70'),
	(74, 'Тюменская область', '71'),
	(75, 'Ульяновская область', '73'),
	(76, 'Челябинская область', '75'),
	(77, 'Ярославская область', '78'),
	(78, 'Москва', '45'),
	(79, 'Санкт-Петербург', '40'),
	(80, 'Севастополь', '67'),
	(81, 'Еврейская автономная область', '99'),
	(82, 'Ненецкий автономный округ', '11-8'),
	(83, 'Ханты-Мансийский автономный округ - Югра', '71-8'),
	(84, 'Чукотский автономный округ', '77'),
	(85, 'Ямало-Ненецкий автономный округ', '71-9');
/*!40000 ALTER TABLE `fms_regions` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.fms_regions_users
DROP TABLE IF EXISTS `fms_regions_users`;
CREATE TABLE IF NOT EXISTS `fms_regions_users` (
  `id_user` int(10) NOT NULL DEFAULT '0',
  `id_region` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_user`),
  KEY `id_region` (`id_region`,`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

-- Дамп данных таблицы migrants.fms_regions_users: ~1 rows (приблизительно)
DELETE FROM `fms_regions_users`;
/*!40000 ALTER TABLE `fms_regions_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `fms_regions_users` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.head_center_text
DROP TABLE IF EXISTS `head_center_text`;
CREATE TABLE IF NOT EXISTS `head_center_text` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `head_id` int(11) DEFAULT NULL,
  `short_ip` varchar(255) DEFAULT NULL,
  `short_vp` varchar(255) DEFAULT NULL,
  `okved` varchar(255) DEFAULT NULL,
  `okpo` varchar(255) DEFAULT NULL,
  `okato` varchar(255) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `oktmo` varchar(255) DEFAULT NULL,
  `ogrn` varchar(255) DEFAULT NULL,
  `legal_address_1` text,
  `check_receiver` text,
  `check_n` varchar(255) DEFAULT NULL,
  `bik` varchar(255) DEFAULT NULL,
  `bank_1` varchar(255) DEFAULT NULL,
  `bank_2` varchar(255) DEFAULT NULL,
  `bank_3` varchar(255) DEFAULT NULL,
  `bank_inn` varchar(255) DEFAULT NULL,
  `bank_kpp` varchar(255) DEFAULT NULL,
  `bank_kbk` varchar(255) DEFAULT NULL,
  `address_1` text,
  `long_ip` text,
  `middle_ip` text,
  `long_tp_print_act` text,
  `our_short_name` varchar(50) DEFAULT NULL,
  `our_full_name` text,
  `help_caption` text,
  `help_phone` varchar(255) DEFAULT NULL,
  `help_email` varchar(50) DEFAULT NULL,
  `rki_rudn_form` text,
  `rki_rudn_name` text,
  `cert_pril_rudn_form` text,
  `cert_pril_rudn_name` text,
  `long_tp_print_act_new` text,
  `signing_center_name` text,
  `signing_short_center_name` text,
  `certificate_city` text,
  `note_prefix` varchar(10) DEFAULT NULL,
  `cert_reg_num_prefix` varchar(10) DEFAULT NULL,
  `hc_prefix` varchar(20) DEFAULT NULL COMMENT 'Юридические лица (is_head=0)',
  `hc_prefix_gc` varchar(20) DEFAULT NULL COMMENT 'Физические лица (is_head=1)',
  `login_page_title` varchar(255) DEFAULT NULL COMMENT 'название ГЦ на странице авторизации',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UK_head_center_text_head_id` (`head_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=cp1251;

-- Дамп данных таблицы migrants.head_center_text: ~1 rows (приблизительно)
DELETE FROM `head_center_text`;
/*!40000 ALTER TABLE `head_center_text` DISABLE KEYS */;
INSERT INTO `head_center_text` (`id`, `head_id`, `short_ip`, `short_vp`, `okved`, `okpo`, `okato`, `deleted`, `oktmo`, `ogrn`, `legal_address_1`, `check_receiver`, `check_n`, `bik`, `bank_1`, `bank_2`, `bank_3`, `bank_inn`, `bank_kpp`, `bank_kbk`, `address_1`, `long_ip`, `middle_ip`, `long_tp_print_act`, `our_short_name`, `our_full_name`, `help_caption`, `help_phone`, `help_email`, `rki_rudn_form`, `rki_rudn_name`, `cert_pril_rudn_form`, `cert_pril_rudn_name`, `long_tp_print_act_new`, `signing_center_name`, `signing_short_center_name`, `certificate_city`, `note_prefix`, `cert_reg_num_prefix`, `hc_prefix`, `hc_prefix_gc`, `login_page_title`) VALUES
	(1, 1, 'Короткое название – Именительный падеж', ' Короткое название – винительный падеж', 'ОКВЕД', 'ОКПО', 'ОКАТО', 0, 'ОКТМО', 'ОГРН', ' Юридический адрес с телефоном', ' Получатель счета', ' Номер счета', 'БИК', ' Название банка с городом', ' Название банка большими буквами', ' Город с кодом большими буквами', ' ИНН банка', ' КПП банка', 'КБК банка', 'Адрес без телефона', ' Длинное название в именительном падеже', ' Среднее название в именительном падеже', ' Длинное название с переносами для печати в акте в творительном падеже', ' Короткое назв.', ' Название для печати сертификата', 'ФИО к кому обратиться для заполнения договоров внешних центров', ' Телефон к кому обратиться для заполнения договоров внешних центров', ' Почта к кому обратиться для заполнения договоров ', ' Справка РКИ форма университета', ' Справка РКИ название университета', 'Приложение сертификата форма университета', ' Приложение сертификата название университета', ' Название вуза при печати акта', ' Название центра (Справка РКИ, приложение к сертификату)', ' Короткое название центра (Реестр выдачи сертификатов)', ' Город, печатающийся в сертификатах', 'А', '0001', '', '', 'ГЦ Пример');
/*!40000 ALTER TABLE `head_center_text` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.html_act_files
DROP TABLE IF EXISTS `html_act_files`;
CREATE TABLE IF NOT EXISTS `html_act_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `act_id` int(11) NOT NULL,
  `file_act_id` int(11) NOT NULL DEFAULT '0' COMMENT 'файл сформированного акта',
  `file_act_tabl_id` int(11) NOT NULL DEFAULT '0' COMMENT 'файл сформированной сводной таблицы',
  PRIMARY KEY (`id`),
  UNIQUE KEY `act_id` (`act_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=cp1251;

-- Дамп данных таблицы migrants.html_act_files: ~0 rows (приблизительно)
DELETE FROM `html_act_files`;
/*!40000 ALTER TABLE `html_act_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `html_act_files` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.log_actions
DROP TABLE IF EXISTS `log_actions`;
CREATE TABLE IF NOT EXISTS `log_actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login_id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `controller` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `method` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `IDX_log_actions` (`login_id`,`created_at`),
  KEY `IDX_log_actions_created_at` (`created_at`),
  KEY `IDX_log_actions2` (`controller`,`method`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AVG_ROW_LENGTH=442;

-- Дамп данных таблицы migrants.log_actions: ~0 rows (приблизительно)
DELETE FROM `log_actions`;
/*!40000 ALTER TABLE `log_actions` DISABLE KEYS */;
/*!40000 ALTER TABLE `log_actions` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.log_action_run
DROP TABLE IF EXISTS `log_action_run`;
CREATE TABLE IF NOT EXISTS `log_action_run` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `server` text NOT NULL,
  `url` text NOT NULL,
  `ip` varchar(100) DEFAULT NULL,
  `user_agent` varchar(200) DEFAULT NULL,
  `server_name` varchar(50) DEFAULT NULL,
  `head_center_id` int(11) DEFAULT NULL,
  `method` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 COMMENT='Логи входа в action';

-- Дамп данных таблицы migrants.log_action_run: ~0 rows (приблизительно)
DELETE FROM `log_action_run`;
/*!40000 ALTER TABLE `log_action_run` DISABLE KEYS */;
/*!40000 ALTER TABLE `log_action_run` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.log_login
DROP TABLE IF EXISTS `log_login`;
CREATE TABLE IF NOT EXISTS `log_login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `head_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_log_login` (`login`,`created_at`),
  KEY `IDX_log_login_created_at` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=cp1251 AVG_ROW_LENGTH=1820;

-- Дамп данных таблицы migrants.log_login: ~0 rows (приблизительно)
DELETE FROM `log_login`;
/*!40000 ALTER TABLE `log_login` DISABLE KEYS */;
/*!40000 ALTER TABLE `log_login` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.log_login_block
DROP TABLE IF EXISTS `log_login_block`;
CREATE TABLE IF NOT EXISTS `log_login_block` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(255) NOT NULL,
  `data` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `block_until` datetime NOT NULL,
  `blocked` tinyint(4) NOT NULL DEFAULT '1',
  `unblocked_at` datetime DEFAULT NULL,
  `attempt_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_log_login_block` (`blocked`,`ip`),
  KEY `IDX_log_login_block2` (`block_until`,`blocked`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

-- Дамп данных таблицы migrants.log_login_block: ~0 rows (приблизительно)
DELETE FROM `log_login_block`;
/*!40000 ALTER TABLE `log_login_block` DISABLE KEYS */;
/*!40000 ALTER TABLE `log_login_block` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.log_login_invalid
DROP TABLE IF EXISTS `log_login_invalid`;
CREATE TABLE IF NOT EXISTS `log_login_invalid` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(255) NOT NULL,
  `blocked` tinyint(1) NOT NULL DEFAULT '0',
  `user_id` int(11) DEFAULT NULL,
  `head_id` int(11) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `head_name` varchar(255) DEFAULT NULL,
  `lc_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_log_login` (`login`,`created_at`),
  KEY `IDX_log_login_created_at` (`created_at`,`ip`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=cp1251 AVG_ROW_LENGTH=862;

-- Дамп данных таблицы migrants.log_login_invalid: ~0 rows (приблизительно)
DELETE FROM `log_login_invalid`;
/*!40000 ALTER TABLE `log_login_invalid` DISABLE KEYS */;
/*!40000 ALTER TABLE `log_login_invalid` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.ms_message_meta
DROP TABLE IF EXISTS `ms_message_meta`;
CREATE TABLE IF NOT EXISTS `ms_message_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message_id` int(11) NOT NULL DEFAULT '0',
  `participant_key` varchar(50) NOT NULL DEFAULT '',
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `read_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

-- Дамп данных таблицы migrants.ms_message_meta: ~0 rows (приблизительно)
DELETE FROM `ms_message_meta`;
/*!40000 ALTER TABLE `ms_message_meta` DISABLE KEYS */;
/*!40000 ALTER TABLE `ms_message_meta` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.people_additional_exam
DROP TABLE IF EXISTS `people_additional_exam`;
CREATE TABLE IF NOT EXISTS `people_additional_exam` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `man_id` int(11) NOT NULL DEFAULT '0',
  `old_blank_scan` int(11) NOT NULL DEFAULT '0',
  `old_blank_number` varchar(100) NOT NULL DEFAULT '',
  `approve` tinyint(4) NOT NULL DEFAULT '0',
  `user_approved` int(11) NOT NULL DEFAULT '0',
  `previous_man_id` int(11) NOT NULL DEFAULT '0',
  `cert_exists` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `man_id` (`man_id`),
  KEY `cert_exists` (`cert_exists`),
  KEY `old_blank_number` (`old_blank_number`),
  KEY `previous_man_id` (`previous_man_id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

-- Дамп данных таблицы migrants.people_additional_exam: ~0 rows (приблизительно)
DELETE FROM `people_additional_exam`;
/*!40000 ALTER TABLE `people_additional_exam` DISABLE KEYS */;
/*!40000 ALTER TABLE `people_additional_exam` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.print_numbers
DROP TABLE IF EXISTS `print_numbers`;
CREATE TABLE IF NOT EXISTS `print_numbers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `number` int(11) NOT NULL DEFAULT '0',
  `who` varchar(50) NOT NULL DEFAULT '',
  `doc_type` varchar(50) NOT NULL DEFAULT '',
  `prefix` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_number` (`number`,`who`,`doc_type`,`prefix`),
  KEY `doc_type` (`doc_type`,`prefix`,`who`,`number`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=cp1251 COMMENT='Список использованых номеров печатных документов';

-- Дамп данных таблицы migrants.print_numbers: ~0 rows (приблизительно)
DELETE FROM `print_numbers`;
/*!40000 ALTER TABLE `print_numbers` DISABLE KEYS */;
/*!40000 ALTER TABLE `print_numbers` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.regions
DROP TABLE IF EXISTS `regions`;
CREATE TABLE IF NOT EXISTS `regions` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `caption` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC;

-- Дамп данных таблицы migrants.regions: ~85 rows (приблизительно)
DELETE FROM `regions`;
/*!40000 ALTER TABLE `regions` DISABLE KEYS */;
INSERT INTO `regions` (`id`, `caption`) VALUES
	(1, 'Республика Адыгея'),
	(2, 'Республика Алтай'),
	(3, 'Республика Башкортостан'),
	(4, 'Республика Бурятия'),
	(5, 'Республика Дагестан'),
	(6, 'Республика Ингушетия'),
	(7, 'Кабардино-Балкарская республика'),
	(8, 'Республика Калмыкия'),
	(9, 'Карачаево-Черкесская республика'),
	(10, 'Республика Карелия'),
	(11, 'Республика Коми'),
	(12, 'Республика Крым'),
	(13, 'Республика Марий Эл'),
	(14, 'Республика Мордовия'),
	(15, 'Республика Саха (Якутия)'),
	(16, 'Республика Северная Осетия — Алания'),
	(17, 'Республика Татарстан'),
	(18, 'Республика Тыва'),
	(19, 'Удмуртская республика'),
	(20, 'Республика Хакасия'),
	(21, 'Чеченская республика'),
	(22, 'Чувашская республика'),
	(23, 'Алтайский край'),
	(24, 'Забайкальский край'),
	(25, 'Камчатский край'),
	(26, 'Краснодарский край'),
	(27, 'Красноярский край'),
	(28, 'Пермский край'),
	(29, 'Приморский край'),
	(30, 'Ставропольский край'),
	(31, 'Хабаровский край'),
	(32, 'Амурская область'),
	(33, 'Архангельская область'),
	(34, 'Астраханская область'),
	(35, 'Белгородская область'),
	(36, 'Брянская область'),
	(37, 'Владимирская область'),
	(38, 'Волгоградская область'),
	(39, 'Вологодская область'),
	(40, 'Воронежская область'),
	(41, 'Ивановская область'),
	(42, 'Иркутская область'),
	(43, 'Калининградская область'),
	(44, 'Калужская область'),
	(45, 'Кемеровская область'),
	(46, 'Кировская область'),
	(47, 'Костромская область'),
	(48, 'Курганская область'),
	(49, 'Курская область'),
	(50, 'Ленинградская область'),
	(51, 'Липецкая область'),
	(52, 'Магаданская область'),
	(53, 'Московская область'),
	(54, 'Мурманская область'),
	(55, 'Нижегородская область'),
	(56, 'Новгородская область'),
	(57, 'Новосибирская область'),
	(58, 'Омская область'),
	(59, 'Оренбургская область'),
	(60, 'Орловская область'),
	(61, 'Пензенская область'),
	(62, 'Псковская область'),
	(63, 'Ростовская область'),
	(64, 'Рязанская область'),
	(65, 'Самарская область'),
	(66, 'Саратовская область'),
	(67, 'Сахалинская область'),
	(68, 'Свердловская область'),
	(69, 'Смоленская область'),
	(70, 'Тамбовская область'),
	(71, 'Тверская область'),
	(72, 'Томская область'),
	(73, 'Тульская область'),
	(74, 'Тюменская область'),
	(75, 'Ульяновская область'),
	(76, 'Челябинская область'),
	(77, 'Ярославская область'),
	(78, 'Москва'),
	(79, 'Санкт-Петербург'),
	(80, 'Севастополь'),
	(81, 'Еврейская автономная область'),
	(82, 'Ненецкий автономный округ'),
	(83, 'Ханты-Мансийский автономный округ - Югра'),
	(84, 'Чукотский автономный округ'),
	(85, 'Ямало-Ненецкий автономный округ');
/*!40000 ALTER TABLE `regions` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.reports
DROP TABLE IF EXISTS `reports`;
CREATE TABLE IF NOT EXISTS `reports` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `caption` varchar(255) DEFAULT NULL,
  `action_name` varchar(255) DEFAULT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

-- Дамп данных таблицы migrants.reports: ~0 rows (приблизительно)
DELETE FROM `reports`;
/*!40000 ALTER TABLE `reports` DISABLE KEYS */;
/*!40000 ALTER TABLE `reports` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.re_exam_info
DROP TABLE IF EXISTS `re_exam_info`;
CREATE TABLE IF NOT EXISTS `re_exam_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_number` varchar(32) NOT NULL COMMENT 'Номер документа',
  `document_type` varchar(32) NOT NULL COMMENT 'тип документа (на будущее)',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'когда добавлен',
  `is_free` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'является ли бесплатной',
  `number` int(11) NOT NULL DEFAULT '1' COMMENT 'Номер пересдачи',
  `man_id` int(11) NOT NULL COMMENT 'Новый id человека',
  `old_man_id` int(11) NOT NULL COMMENT 'Старый id человека',
  `first_man_id` int(11) NOT NULL COMMENT 'Id первой справки',
  PRIMARY KEY (`id`),
  KEY `document_number` (`document_number`,`document_type`),
  KEY `first_man_id` (`first_man_id`),
  KEY `is_free` (`is_free`),
  KEY `man_id` (`man_id`),
  KEY `old_man_id` (`old_man_id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

-- Дамп данных таблицы migrants.re_exam_info: ~0 rows (приблизительно)
DELETE FROM `re_exam_info`;
/*!40000 ALTER TABLE `re_exam_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `re_exam_info` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.sdt_act
DROP TABLE IF EXISTS `sdt_act`;
CREATE TABLE IF NOT EXISTS `sdt_act` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `test_level_type_id` int(11) NOT NULL DEFAULT '1',
  `university_id` int(11) DEFAULT NULL COMMENT 'id университета',
  `university_dogovor_id` int(11) DEFAULT NULL COMMENT 'id договора',
  `testing_date` date DEFAULT NULL COMMENT 'дата тестирования',
  `total_revenue` double DEFAULT NULL COMMENT 'Общая выручка',
  `rate_of_contributions` tinyint(4) DEFAULT NULL COMMENT 'Процент отчислений',
  `amount_contributions` double DEFAULT NULL COMMENT 'Сумма отчислений',
  `account` varchar(255) DEFAULT NULL COMMENT 'Номер счета',
  `invoice_date` date DEFAULT NULL COMMENT 'Дата счета',
  `comment` text COMMENT 'комментарий',
  `paid` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Оплачено',
  `state` enum('init','send','checked','wait_payment','archive','received','check','print','need_confirm') NOT NULL DEFAULT 'init' COMMENT 'Статус',
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата создания',
  `platez_date` date DEFAULT NULL COMMENT 'Дата платежа',
  `platez_number` varchar(255) DEFAULT NULL COMMENT 'Номер платежа',
  `responsible` text COMMENT 'Ответственный за проведение тестирования',
  `file_act_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Скан акта',
  `file_act_tabl_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Скан сводной таблицы',
  `number` varchar(255) DEFAULT NULL COMMENT 'номер документа',
  `user_create_id` int(11) DEFAULT NULL COMMENT 'id пользователя создавшего документ',
  `official` text COMMENT 'Кто утверждает от ЦТ (Долж.,фио)',
  `out_id` int(11) DEFAULT NULL COMMENT 'id из внешней системы',
  `invoice_index` varchar(255) NOT NULL DEFAULT '24' COMMENT 'индекс подразделения для счета',
  `invoice` text COMMENT 'номер счета',
  `tester1` text COMMENT 'Тестер 1',
  `tester2` text COMMENT 'Тестер 2',
  `signing` int(11) DEFAULT NULL COMMENT 'Кто подписывает счет',
  `is_changed_checker` int(1) DEFAULT '0',
  `check_date` date DEFAULT NULL,
  `blocked_flag` tinyint(4) NOT NULL DEFAULT '0',
  `blocked_user` int(11) DEFAULT NULL,
  `blocked_time` datetime DEFAULT NULL,
  `last_state_update` datetime DEFAULT NULL,
  `viewed` tinyint(4) NOT NULL DEFAULT '0',
  `date_received` datetime DEFAULT NULL,
  `is_printed` tinyint(1) DEFAULT '0',
  `finally_check_date` datetime DEFAULT NULL COMMENT 'Дата завершения проверки',
  `ved_vid_cert_num` varchar(255) DEFAULT NULL COMMENT 'Номер ведомости выдачи сертификата',
  `ved_vid_cert_num_date` datetime DEFAULT NULL COMMENT 'Дата присвоения номера ведомости выдачи сертификата',
  `summary_table_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `state` (`state`),
  KEY `university_dogovor_id` (`university_dogovor_id`),
  KEY `signing` (`signing`),
  KEY `deleted` (`deleted`),
  KEY `test_level_type_id` (`test_level_type_id`,`state`),
  KEY `testing_date` (`testing_date`),
  KEY `university_id` (`university_id`,`state`,`deleted`),
  KEY `created` (`created`),
  KEY `paid` (`paid`),
  KEY `invoice_date` (`invoice_date`),
  KEY `platez_date` (`platez_date`),
  KEY `duplicate` (`deleted`,`state`,`id`),
  KEY `IDX_sdt_act` (`deleted`,`test_level_type_id`,`state`,`university_id`,`id`),
  KEY `search` (`state`,`deleted`,`university_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=cp1251 AVG_ROW_LENGTH=54 COMMENT='Акты';

-- Дамп данных таблицы migrants.sdt_act: ~0 rows (приблизительно)
DELETE FROM `sdt_act`;
/*!40000 ALTER TABLE `sdt_act` DISABLE KEYS */;
/*!40000 ALTER TABLE `sdt_act` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.sdt_act_man_files
DROP TABLE IF EXISTS `sdt_act_man_files`;
CREATE TABLE IF NOT EXISTS `sdt_act_man_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL DEFAULT '' COMMENT 'название файла',
  `caption` text COMMENT 'Отображаемое название',
  `uploaded` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'дата загрузки',
  `user_id` int(11) DEFAULT NULL COMMENT 'id загрузившего',
  `deleted` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Удаленые',
  `download_id` varchar(255) DEFAULT NULL COMMENT 'hash для скачивания',
  `type` varchar(50) NOT NULL,
  `man_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `download_id` (`download_id`),
  KEY `user_id` (`user_id`),
  KEY `man_id` (`man_id`,`type`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=cp1251 ROW_FORMAT=COMPACT COMMENT='Файлы загруженные к человеку';

-- Дамп данных таблицы migrants.sdt_act_man_files: ~0 rows (приблизительно)
DELETE FROM `sdt_act_man_files`;
/*!40000 ALTER TABLE `sdt_act_man_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `sdt_act_man_files` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.sdt_act_people
DROP TABLE IF EXISTS `sdt_act_people`;
CREATE TABLE IF NOT EXISTS `sdt_act_people` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `act_id` int(11) NOT NULL DEFAULT '0' COMMENT 'id акта',
  `test_id` int(11) NOT NULL DEFAULT '0' COMMENT 'id тестирования',
  `deleted` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'удалено',
  `surname_rus` varchar(255) DEFAULT NULL COMMENT 'Фамилия рус',
  `surname_lat` varchar(255) DEFAULT NULL COMMENT 'Фамилия лат',
  `name_rus` varchar(255) DEFAULT NULL COMMENT 'Имя рус',
  `name_lat` varchar(255) DEFAULT NULL COMMENT 'Имя лат',
  `country_id` int(11) DEFAULT NULL COMMENT 'id страна',
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
  `soprovod_file` int(11) NOT NULL DEFAULT '0' COMMENT 'файл сертификата или справки',
  `birth_date` date DEFAULT NULL COMMENT 'дата рождения',
  `birth_place` varchar(255) DEFAULT NULL COMMENT 'место рождения',
  `migration_card_number` varchar(255) DEFAULT NULL COMMENT 'номер миграционной карты',
  `migration_card_series` varchar(255) DEFAULT NULL COMMENT 'серия миграционной карты',
  `blank_number` varchar(100) DEFAULT NULL COMMENT 'номер бланка сертификата',
  `blank_date` date DEFAULT NULL,
  `valid_till` date DEFAULT NULL,
  `is_retry` int(11) NOT NULL DEFAULT '0',
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
  `updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `blank_id` int(11) DEFAULT NULL,
  `cert_signer` int(11) DEFAULT NULL,
  `gender` char(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `blank_number` (`blank_number`,`document`),
  KEY `country_id` (`country_id`),
  KEY `document` (`document`),
  KEY `search` (`document_nomer`,`blank_number`,`deleted`,`test_id`),
  KEY `act_id` (`act_id`,`deleted`),
  KEY `test_id` (`test_id`,`deleted`),
  KEY `free_cert` (`act_id`,`document`,`id`,`blank_number`,`document_nomer`),
  KEY `blank_doc_act` (`blank_number`,`document_nomer`,`act_id`),
  KEY `surname_rus` (`surname_rus`,`surname_lat`),
  KEY `surname_lat` (`surname_lat`),
  KEY `name_rus` (`name_rus`,`name_lat`),
  KEY `name_lat` (`name_lat`),
  KEY `deleted_with_surname` (`act_id`,`deleted`,`surname_rus`,`surname_lat`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=cp1251 AVG_ROW_LENGTH=48 COMMENT='Тестирующиеся';

-- Дамп данных таблицы migrants.sdt_act_people: ~0 rows (приблизительно)
DELETE FROM `sdt_act_people`;
/*!40000 ALTER TABLE `sdt_act_people` DISABLE KEYS */;
/*!40000 ALTER TABLE `sdt_act_people` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.sdt_act_status
DROP TABLE IF EXISTS `sdt_act_status`;
CREATE TABLE IF NOT EXISTS `sdt_act_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `act_id` int(11) NOT NULL DEFAULT '0',
  `status` varchar(100) NOT NULL DEFAULT '',
  `timest` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `act_id` (`act_id`,`status`,`timest`),
  KEY `user_id` (`user_id`,`act_id`,`status`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=cp1251;

-- Дамп данных таблицы migrants.sdt_act_status: ~0 rows (приблизительно)
DELETE FROM `sdt_act_status`;
/*!40000 ALTER TABLE `sdt_act_status` DISABLE KEYS */;
/*!40000 ALTER TABLE `sdt_act_status` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.sdt_act_test
DROP TABLE IF EXISTS `sdt_act_test`;
CREATE TABLE IF NOT EXISTS `sdt_act_test` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level_id` int(11) DEFAULT NULL COMMENT 'Уровень тестирования',
  `people_count` int(11) NOT NULL DEFAULT '0' COMMENT 'Суммарное количество людей',
  `money` double NOT NULL DEFAULT '0' COMMENT 'суммарное количество денег',
  `act_id` int(11) NOT NULL DEFAULT '0' COMMENT 'id акта',
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `people_first` int(10) NOT NULL DEFAULT '0' COMMENT 'Количество людей в 1-й раз',
  `money_first` double NOT NULL DEFAULT '0' COMMENT 'Неиспользуется',
  `people_retry` int(11) NOT NULL DEFAULT '0' COMMENT 'Количество людей для пересдачи',
  `people_subtest_retry` int(11) NOT NULL DEFAULT '0' COMMENT 'Количество тестов в пересдаче',
  `money_retry` double NOT NULL DEFAULT '0' COMMENT 'Неиспользуется',
  `price` double NOT NULL DEFAULT '0' COMMENT 'Неиспользуется',
  `price_subtest_retry` double NOT NULL DEFAULT '0' COMMENT 'Неиспользуется',
  `people_subtest_2_retry` int(11) NOT NULL DEFAULT '0',
  `price_subtest_2_retry` double NOT NULL DEFAULT '0',
  `people_subtest_all_retry` int(11) NOT NULL DEFAULT '0' COMMENT 'Бесплатная пересдача',
  PRIMARY KEY (`id`),
  KEY `level_id` (`level_id`),
  KEY `act_id` (`act_id`,`deleted`),
  KEY `deleted` (`deleted`,`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=cp1251 COMMENT='Проведенные тестирования в акте';

-- Дамп данных таблицы migrants.sdt_act_test: ~0 rows (приблизительно)
DELETE FROM `sdt_act_test`;
/*!40000 ALTER TABLE `sdt_act_test` DISABLE KEYS */;
/*!40000 ALTER TABLE `sdt_act_test` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.sdt_api_user_data
DROP TABLE IF EXISTS `sdt_api_user_data`;
CREATE TABLE IF NOT EXISTS `sdt_api_user_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `man_id` int(11) NOT NULL DEFAULT '0',
  `doc_type` varchar(50) NOT NULL DEFAULT '',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ext_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `man_id` (`man_id`),
  KEY `IDX_sdt_api_user_data_ext_id` (`ext_id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

-- Дамп данных таблицы migrants.sdt_api_user_data: ~0 rows (приблизительно)
DELETE FROM `sdt_api_user_data`;
/*!40000 ALTER TABLE `sdt_api_user_data` DISABLE KEYS */;
/*!40000 ALTER TABLE `sdt_api_user_data` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.sdt_files
DROP TABLE IF EXISTS `sdt_files`;
CREATE TABLE IF NOT EXISTS `sdt_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL DEFAULT '' COMMENT 'название файла',
  `caption` text COMMENT 'Отображаемое название',
  `uploaded` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'дата загрузки',
  `user_id` int(11) DEFAULT NULL COMMENT 'id загрузившего',
  `deleted` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Удаленые',
  `download_id` varchar(255) DEFAULT NULL COMMENT 'hash для скачивания',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `download_id` (`download_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=cp1251 COMMENT='Сканы документов';

-- Дамп данных таблицы migrants.sdt_files: ~0 rows (приблизительно)
DELETE FROM `sdt_files`;
/*!40000 ALTER TABLE `sdt_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `sdt_files` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.sdt_head_center
DROP TABLE IF EXISTS `sdt_head_center`;
CREATE TABLE IF NOT EXISTS `sdt_head_center` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text COMMENT 'Название',
  `short_name` text COMMENT 'Короткое название',
  `rector` text COMMENT 'ректор',
  `form` text COMMENT 'форма',
  `legal_address` text COMMENT 'юридически адрес',
  `contact_phone` varchar(255) DEFAULT NULL COMMENT 'телефон',
  `contact_fax` varchar(255) DEFAULT NULL COMMENT 'факс',
  `contact_email` varchar(255) DEFAULT NULL COMMENT 'почта',
  `country_id` int(11) DEFAULT NULL,
  `contact_other` text COMMENT 'другие контакты',
  `responsible_person` text COMMENT 'ответсвенное лицо',
  `responsible_person_phone` varchar(255) DEFAULT NULL COMMENT 'ответсвенное лицо  телефон',
  `responsible_person_email` varchar(255) DEFAULT NULL COMMENT 'ответсвенное лицо почта',
  `comments` text COMMENT 'коментарии',
  `deleted` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'удаление',
  `city` text COMMENT 'город',
  `href` text COMMENT 'адрес захода',
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `horg_id` int(11) DEFAULT NULL,
  `pfur_api` tinyint(4) NOT NULL DEFAULT '0',
  `bso_id` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=cp1251 COMMENT='Список университетов';

-- Дамп данных таблицы migrants.sdt_head_center: ~1 rows (приблизительно)
DELETE FROM `sdt_head_center`;
/*!40000 ALTER TABLE `sdt_head_center` DISABLE KEYS */;
INSERT INTO `sdt_head_center` (`id`, `name`, `short_name`, `rector`, `form`, `legal_address`, `contact_phone`, `contact_fax`, `contact_email`, `country_id`, `contact_other`, `responsible_person`, `responsible_person_phone`, `responsible_person_email`, `comments`, `deleted`, `city`, `href`, `created`, `horg_id`, `pfur_api`, `bso_id`) VALUES
	(1, 'Головной центр ПРИМЕР', 'ГЦ Пример', '', '', '', '+74951234567', '', '', 20, '', '', '', '', '', 0, '', 'primer.rudn.ru', '2020-10-06 15:22:54', 1, 0, '');
/*!40000 ALTER TABLE `sdt_head_center` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.sdt_head_org
DROP TABLE IF EXISTS `sdt_head_org`;
CREATE TABLE IF NOT EXISTS `sdt_head_org` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `captoin` text,
  `deleted` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=cp1251;

-- Дамп данных таблицы migrants.sdt_head_org: ~1 rows (приблизительно)
DELETE FROM `sdt_head_org`;
/*!40000 ALTER TABLE `sdt_head_org` DISABLE KEYS */;
INSERT INTO `sdt_head_org` (`id`, `captoin`, `deleted`) VALUES
	(1, 'ГЦ Пример', 0);
/*!40000 ALTER TABLE `sdt_head_org` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.sdt_signing
DROP TABLE IF EXISTS `sdt_signing`;
CREATE TABLE IF NOT EXISTS `sdt_signing` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `caption` text COMMENT 'Кто подписывает',
  `position` text COMMENT 'порядок',
  `deleted` tinyint(4) DEFAULT '0',
  `invoice` int(11) NOT NULL DEFAULT '0' COMMENT 'в счета',
  `certificate` int(11) NOT NULL DEFAULT '0' COMMENT 'в сертификаты',
  `vidacha_cert` int(11) NOT NULL DEFAULT '0',
  `name_of_center` text,
  `short_name` varchar(255) DEFAULT NULL,
  `act` int(11) DEFAULT '0',
  `aprove_vidacha_cert` int(11) DEFAULT '0',
  `head_id` int(11) DEFAULT NULL,
  `old_certs` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `act` (`act`,`head_id`),
  KEY `head_id` (`head_id`,`deleted`),
  KEY `invoice` (`invoice`,`head_id`),
  KEY `vidacha_cert` (`vidacha_cert`,`head_id`),
  KEY `aprove_vidacha_cert` (`aprove_vidacha_cert`,`head_id`,`deleted`),
  KEY `certificate` (`certificate`,`head_id`,`deleted`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=cp1251 COMMENT='Кто подписывает счета';

-- Дамп данных таблицы migrants.sdt_signing: ~1 rows (приблизительно)
DELETE FROM `sdt_signing`;
/*!40000 ALTER TABLE `sdt_signing` DISABLE KEYS */;
INSERT INTO `sdt_signing` (`id`, `caption`, `position`, `deleted`, `invoice`, `certificate`, `vidacha_cert`, `name_of_center`, `short_name`, `act`, `aprove_vidacha_cert`, `head_id`, `old_certs`) VALUES
	(1, 'П.П. Проверка', 'Директор', 0, 1, 1, 1, NULL, NULL, 1, 1, 1, 0);
/*!40000 ALTER TABLE `sdt_signing` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.sdt_test_levels
DROP TABLE IF EXISTS `sdt_test_levels`;
CREATE TABLE IF NOT EXISTS `sdt_test_levels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `caption` text COMMENT 'название',
  `type_id` int(11) NOT NULL DEFAULT '1',
  `deleted` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Удаленые',
  `test_group` tinyint(4) NOT NULL DEFAULT '1',
  `valid_duration` tinyint(4) DEFAULT NULL,
  `test_1_maxball` int(11) NOT NULL DEFAULT '0' COMMENT 'Макс. баллы за чтение',
  `test_1_caption` varchar(100) DEFAULT 'Чтение',
  `test_1_short_caption` varchar(100) DEFAULT 'Чт',
  `test_1_full_caption` varchar(100) DEFAULT 'Понимание содержания текстов при чтении',
  `test_2_maxball` int(11) NOT NULL DEFAULT '0' COMMENT 'Макс. баллы  письмо',
  `test_2_caption` varchar(100) DEFAULT 'Письмо',
  `test_2_short_caption` varchar(100) DEFAULT 'Пис',
  `test_2_full_caption` varchar(100) DEFAULT 'Владение письменной речью',
  `test_3_maxball` int(11) NOT NULL DEFAULT '0' COMMENT 'Макс. баллы лексика и граматика',
  `test_3_caption` varchar(100) DEFAULT 'Грамматика',
  `test_3_short_caption` varchar(100) DEFAULT 'Гр',
  `test_3_full_caption` varchar(100) DEFAULT 'Владение лексикой и грамматикой',
  `test_4_maxball` int(11) NOT NULL DEFAULT '0' COMMENT 'Макс. баллы аудирование',
  `test_4_caption` varchar(100) DEFAULT 'Аудиторование',
  `test_4_short_caption` varchar(100) DEFAULT 'Ауд',
  `test_4_full_caption` varchar(100) DEFAULT 'Понимание содержания звучащей речи',
  `test_5_maxball` int(11) NOT NULL DEFAULT '0' COMMENT 'Макс. баллы устная речь',
  `test_5_caption` varchar(100) DEFAULT 'Устная речь',
  `test_5_short_caption` varchar(100) DEFAULT 'Уст',
  `test_5_full_caption` varchar(100) DEFAULT 'Устное общение',
  `print_note` text NOT NULL,
  `total` int(11) NOT NULL DEFAULT '0' COMMENT 'Макс. баллы сумма',
  `print` text COMMENT 'название для печати в сертификатах',
  `print_act` text COMMENT 'название для печати в сертификатах',
  `price` double DEFAULT NULL COMMENT 'цена тестирования',
  `sub_test_price` double DEFAULT NULL COMMENT 'цена пересдачи одного субтеста',
  `sub_test_price_2` double DEFAULT NULL COMMENT 'цена пересдачи субтеста второго типа',
  `percent_min` int(11) NOT NULL DEFAULT '60',
  `percent_max` int(11) NOT NULL DEFAULT '65',
  `subtest_count` int(11) NOT NULL DEFAULT '5',
  `test_6_maxball` int(11) NOT NULL DEFAULT '0',
  `test_6_caption` varchar(100) DEFAULT NULL,
  `test_6_short_caption` varchar(100) DEFAULT NULL,
  `test_6_full_caption` varchar(100) DEFAULT NULL,
  `test_7_maxball` int(11) NOT NULL DEFAULT '0',
  `test_7_caption` varchar(100) DEFAULT NULL,
  `test_7_short_caption` varchar(100) DEFAULT NULL,
  `test_7_full_caption` varchar(100) DEFAULT NULL,
  `test_8_maxball` int(11) NOT NULL DEFAULT '0',
  `test_8_caption` varchar(100) DEFAULT NULL,
  `test_8_short_caption` varchar(100) DEFAULT NULL,
  `test_8_full_caption` varchar(100) DEFAULT NULL,
  `test_9_maxball` int(11) NOT NULL DEFAULT '0',
  `test_9_caption` varchar(100) DEFAULT NULL,
  `test_9_short_caption` varchar(100) DEFAULT NULL,
  `test_9_full_caption` varchar(100) DEFAULT NULL,
  `test_10_maxball` int(11) NOT NULL DEFAULT '0',
  `test_10_caption` varchar(100) DEFAULT NULL,
  `test_10_short_caption` varchar(100) DEFAULT NULL,
  `test_10_full_caption` varchar(100) DEFAULT NULL,
  `test_11_maxball` int(11) NOT NULL DEFAULT '0',
  `test_11_caption` varchar(100) DEFAULT NULL,
  `test_11_short_caption` varchar(100) DEFAULT NULL,
  `test_11_full_caption` varchar(100) DEFAULT NULL,
  `test_12_maxball` int(11) NOT NULL DEFAULT '0',
  `test_12_caption` varchar(100) DEFAULT NULL,
  `test_12_short_caption` varchar(100) DEFAULT NULL,
  `test_12_full_caption` varchar(100) DEFAULT NULL,
  `test_13_maxball` int(11) NOT NULL DEFAULT '0',
  `test_13_caption` varchar(100) DEFAULT NULL,
  `test_13_short_caption` varchar(100) DEFAULT NULL,
  `test_13_full_caption` varchar(100) DEFAULT NULL,
  `test_14_maxball` int(11) NOT NULL DEFAULT '0',
  `test_14_caption` varchar(100) DEFAULT NULL,
  `test_14_short_caption` varchar(100) DEFAULT NULL,
  `test_14_full_caption` varchar(100) DEFAULT NULL,
  `test_15_maxball` int(11) NOT NULL DEFAULT '0',
  `test_15_caption` varchar(100) DEFAULT NULL,
  `test_15_short_caption` varchar(100) DEFAULT NULL,
  `test_15_full_caption` varchar(100) DEFAULT NULL,
  `test_1_pass_score` int(11) DEFAULT NULL,
  `test_2_pass_score` int(11) DEFAULT NULL,
  `test_3_pass_score` int(11) DEFAULT NULL,
  `test_4_pass_score` int(11) DEFAULT NULL,
  `test_5_pass_score` int(11) DEFAULT NULL,
  `test_6_pass_score` int(11) DEFAULT NULL,
  `test_7_pass_score` int(11) DEFAULT NULL,
  `test_8_pass_score` int(11) DEFAULT NULL,
  `test_9_pass_score` int(11) DEFAULT NULL,
  `test_10_pass_score` int(11) DEFAULT NULL,
  `test_11_pass_score` int(11) DEFAULT NULL,
  `test_12_pass_score` int(11) DEFAULT NULL,
  `test_13_pass_score` int(11) DEFAULT NULL,
  `test_14_pass_score` int(11) DEFAULT NULL,
  `test_15_pass_score` int(11) DEFAULT NULL,
  `is_additional` tinyint(4) DEFAULT '0',
  `available_levels` text,
  `print_summary_table` text,
  `is_publicated` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `test_level` (`type_id`,`deleted`),
  KEY `deleted` (`deleted`,`type_id`,`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=cp1251 COMMENT='Уровни тестирования';

-- Дамп данных таблицы migrants.sdt_test_levels: ~30 rows (приблизительно)
DELETE FROM `sdt_test_levels`;
/*!40000 ALTER TABLE `sdt_test_levels` DISABLE KEYS */;
INSERT INTO `sdt_test_levels` (`id`, `caption`, `type_id`, `deleted`, `test_group`, `valid_duration`, `test_1_maxball`, `test_1_caption`, `test_1_short_caption`, `test_1_full_caption`, `test_2_maxball`, `test_2_caption`, `test_2_short_caption`, `test_2_full_caption`, `test_3_maxball`, `test_3_caption`, `test_3_short_caption`, `test_3_full_caption`, `test_4_maxball`, `test_4_caption`, `test_4_short_caption`, `test_4_full_caption`, `test_5_maxball`, `test_5_caption`, `test_5_short_caption`, `test_5_full_caption`, `print_note`, `total`, `print`, `print_act`, `price`, `sub_test_price`, `sub_test_price_2`, `percent_min`, `percent_max`, `subtest_count`, `test_6_maxball`, `test_6_caption`, `test_6_short_caption`, `test_6_full_caption`, `test_7_maxball`, `test_7_caption`, `test_7_short_caption`, `test_7_full_caption`, `test_8_maxball`, `test_8_caption`, `test_8_short_caption`, `test_8_full_caption`, `test_9_maxball`, `test_9_caption`, `test_9_short_caption`, `test_9_full_caption`, `test_10_maxball`, `test_10_caption`, `test_10_short_caption`, `test_10_full_caption`, `test_11_maxball`, `test_11_caption`, `test_11_short_caption`, `test_11_full_caption`, `test_12_maxball`, `test_12_caption`, `test_12_short_caption`, `test_12_full_caption`, `test_13_maxball`, `test_13_caption`, `test_13_short_caption`, `test_13_full_caption`, `test_14_maxball`, `test_14_caption`, `test_14_short_caption`, `test_14_full_caption`, `test_15_maxball`, `test_15_caption`, `test_15_short_caption`, `test_15_full_caption`, `test_1_pass_score`, `test_2_pass_score`, `test_3_pass_score`, `test_4_pass_score`, `test_5_pass_score`, `test_6_pass_score`, `test_7_pass_score`, `test_8_pass_score`, `test_9_pass_score`, `test_10_pass_score`, `test_11_pass_score`, `test_12_pass_score`, `test_13_pass_score`, `test_14_pass_score`, `test_15_pass_score`, `is_additional`, `available_levels`, `print_summary_table`, `is_publicated`) VALUES
	(1, 'Базовый для иностранных работников', 1, 0, 1, 5, 60, 'Чтение', 'Чт', 'Понимание содержания текстов при чтении', 30, 'Письмо', 'Пис', 'Владение письменной речью', 25, 'Грамматика', 'Гр', 'Владение лексикой и грамматикой', 60, 'Аудирование', 'Ауд', 'Понимание содержания звучащей речи', 50, 'Устная речь', 'Уст', 'Устное общение', 'базового для иностранных работников', 225, 'БАЗОВЫЙ ДЛЯ ИНОСТРАННЫХ РАБОТНИКОВ', '', 1003, 0, NULL, 55, 60, 5, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1),
	(2, 'ТРКИ "Элементарный" А1', 1, 0, 1, 5, 120, 'Чтение', 'Чт', 'Понимание содержания текстов при чтении', 80, 'Письмо', 'Пис', 'Владение письменной речью', 100, 'Грамматика', 'Гр', 'Владение лексикой и грамматикой', 100, 'Аудирование', 'Ауд', 'Понимание содержания звучащей речи', 130, 'Устная речь', 'Уст', 'Устное общение', 'элементарного', 530, 'ЭЛЕМЕНТАРНЫЙ (ТЭУ/А1)', '', 1800, 1500, NULL, 60, 66, 5, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1),
	(3, 'ТРКИ "Базовый" А2', 1, 0, 1, 0, 180, 'Чтение', 'Чт', 'Понимание содержания текстов при чтении', 80, 'Письмо', 'Пис', 'Владение письменной речью', 110, 'Грамматика', 'Гр', 'Владение лексикой и грамматикой', 180, 'Аудирование', 'Ауд', 'Понимание содержания звучащей речи', 180, 'Устная речь', 'Уст', 'Устное общение', 'базового', 730, 'БАЗОВЫЙ (ТБУ/А2)', '', 2000, 1600, NULL, 60, 66, 5, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1),
	(4, 'Первый (1 вар.)', 1, 1, 1, NULL, 140, 'Чтение', 'Чт', 'Понимание содержания текстов при чтении', 80, 'Письмо', 'Пис', 'Владение письменной речью', 150, 'Грамматика', 'Гр', 'Владение лексикой и грамматикой', 120, 'Аудирование', 'Ауд', 'Понимание содержания звучащей речи', 170, 'Устная речь', 'Уст', 'Устное общение', '', 660, 'первом', NULL, 1593, 944, NULL, 60, 65, 5, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1),
	(5, 'ТРКИ "Первый" В1', 1, 0, 1, 0, 140, 'Чтение', 'Чт', 'Понимание содержания текстов при чтении', 80, 'Письмо', 'Пис', 'Владение письменной речью', 165, 'Грамматика', 'Гр', 'Владение лексикой и грамматикой', 120, 'Аудирование', 'Ауд', 'Понимание содержания звучащей речи', 170, 'Устная речь', 'Уст', 'Устное общение', 'первого', 675, 'ПЕРВЫЙ (ТРКИ-I/В1)', '', 2000, 1600, NULL, 60, 66, 5, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1),
	(6, 'ТРКИ "Второй" В2', 1, 0, 1, 0, 150, 'Чтение', 'Чт', 'Понимание содержания текстов при чтении', 65, 'Письмо', 'Пис', 'Владение письменной речью', 150, 'Грамматика', 'Гр', 'Владение лексикой и грамматикой', 150, 'Аудирование', 'Ауд', 'Понимание содержания звучащей речи', 145, 'Устная речь', 'Уст', 'Устное общение', 'второго', 660, 'ВТОРОЙ (ТРКИ-II/В2)', '', 2100, 1700, NULL, 60, 66, 5, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1),
	(7, 'ТРКИ "Третий" С1', 1, 0, 1, 0, 150, 'Чтение', 'Чт', 'Понимание содержания текстов при чтении', 100, 'Письмо', 'Пис', 'Владение письменной речью', 100, 'Грамматика', 'Гр', 'Владение лексикой и грамматикой', 150, 'Аудирование', 'Ауд', 'Понимание содержания звучащей речи', 150, 'Устная речь', 'Уст', 'Устное общение', 'третьего', 650, 'ТРЕТИЙ (ТРКИ-III/С1)', '', 2200, 1800, NULL, 60, 66, 5, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1),
	(8, 'ТРКИ "Четвертый" С2', 1, 0, 1, 0, 135, 'Чтение', 'Чт', 'Понимание содержания текстов при чтении', 95, 'Письмо', 'Пис', 'Владение письменной речью', 140, 'Грамматика', 'Гр', 'Владение лексикой и грамматикой', 150, 'Аудирование', 'Ауд', 'Понимание содержания звучащей речи', 165, 'Устная речь', 'Уст', 'Устное общение', 'четвертого', 685, 'ЧЕТВЕРТЫЙ (ТРКИ-IV/С2)', '', 2200, 1800, NULL, 60, 66, 5, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1),
	(9, 'Гражданство', 1, 1, 1, NULL, 100, 'Чтение', 'Чт', 'Понимание содержания текстов при чтении', 100, 'Письмо', 'Пис', 'Владение письменной речью', 85, 'Грамматика', 'Гр', 'Владение лексикой и грамматикой', 100, 'Аудирование', 'Ауд', 'Понимание содержания звучащей речи', 100, 'Устная речь', 'Уст', 'Устное общение', '', 485, 'базового', NULL, 1593, 767, NULL, 60, 65, 5, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1),
	(10, 'Базовый Гражданство (730)', 1, 1, 1, 0, 180, 'Чтение', 'Чт', 'Понимание содержания текстов при чтении', 80, 'Письмо', 'Пис', 'Владение письменной речью', 110, 'Грамматика', 'Гр', 'Владение лексикой и грамматикой', 180, 'Аудирование', 'Ауд', 'Понимание содержания звучащей речи', 180, 'Устная речь', 'Уст', 'Устное общение', 'базового', 730, 'БАЗОВЫЙ (ТБУ/А2)', NULL, 1593, 767, NULL, 60, 65, 5, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1),
	(11, 'Базовый Гражданство (485)', 1, 0, 1, 0, 100, 'Чтение', 'Чт', 'Понимание содержания текстов при чтении', 100, 'Письмо', 'Пис', 'Владение письменной речью', 85, 'Грамматика', 'Гр', 'Владение лексикой и грамматикой', 100, 'Аудирование', 'Ауд', 'Понимание содержания звучащей речи', 100, 'Устная речь', 'Уст', 'Устное общение', 'базового', 485, 'БАЗОВЫЙ (ТБУ/А2)', '', 2200, 1800, NULL, 60, 65, 5, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1),
	(12, 'Базовый Гражданство (730)', 1, 0, 1, 0, 180, 'Чтение', 'Чт', 'Понимание содержания текстов при чтении', 80, 'Письмо', 'Пис', 'Владение письменной речью', 110, 'Грамматика', 'Гр', 'Владение лексикой и грамматикой', 180, 'Аудирование', 'Ауд', 'Понимание содержания звучащей речи', 180, 'Устная речь', 'Уст', 'Устное общение', 'базового', 730, 'БАЗОВЫЙ (ТБУ/А2)', '', 2200, 1800, NULL, 60, 65, 5, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1),
	(13, 'Соискатели разрешения на работу либо патента', 2, 0, 1, 5, 60, 'Чтение', 'Чт', 'Понимание содержания текстов при чтении', 40, 'Письмо', 'Пис', 'Владение письменной речью', 50, 'Грамматика', 'Гр', 'Владение лексикой и грамматикой', 70, 'Аудирование', 'Ауд', 'Понимание содержания звучащей речи', 80, 'Устная речь', 'Уст', 'Устное общение', 'Разрешение на работу или патент', 500, 'УРОВЕНЬ: ИР', 'трудящихся мигрантов', 1450, 800, 1000, 50, 60, 7, 100, 'История', 'Ист', 'История России', 100, 'Законодательство', 'Зак', 'Основы законодательства  Российской Федерации', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, 50, 50, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 'Комплексный экзамен для трудящихся мигрантов', 1),
	(14, 'Соискатели разрешения на временное проживание', 2, 0, 1, 5, 60, 'Чтение', 'Чт', 'Понимание содержания текстов при чтении', 40, 'Письмо', 'Пис', 'Владение письменной речью', 50, 'Грамматика', 'Гр', 'Владение лексикой и грамматикой', 70, 'Аудирование', 'Ауд', 'Понимание содержания звучащей речи', 80, 'Устная речь', 'Уст', 'Устное общение', 'Разрешение на временное проживание', 500, 'УРОВЕНЬ: РВ', 'лиц, желающих получить разрешение на временное проживание', 1590, 800, 1000, 60, 70, 7, 100, 'История', 'Ист', 'История России', 100, 'Законодательство', 'Зак', 'Основы законодательства  Российской Федерации', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, 50, 50, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 'Комплексный экзамен для лиц, желающих получить разрешение на временное проживание', 1),
	(15, 'Соискатели вида на жительство', 2, 0, 1, 5, 60, 'Чтение', 'Чт', 'Понимание содержания текстов при чтении', 40, 'Письмо', 'Пис', 'Владение письменной речью', 50, 'Грамматика', 'Гр', 'Владение лексикой и грамматикой', 70, 'Аудирование', 'Ауд', 'Понимание содержания звучащей речи', 80, 'Устная речь', 'Уст', 'Устное общение', 'Вид на жительство', 500, 'УРОВЕНЬ: ВЖ', 'лиц, желающих получить вид на жительство', 1590, 800, 1000, 70, 80, 7, 100, 'История', 'Ист', 'История России', 100, 'Законодательство', 'Зак', 'Основы законодательства  Российской Федерации', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, 75, 75, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 'Комплексный экзамен для лиц, желающих получить  вид на жительство', 1),
	(16, 'Упрощенное разрешение на работу либо патент', 2, 0, 1, 5, 100, 'История', 'Ист', 'История России', 100, 'Законодательство', 'Зак', 'Основы законодательства  Российской Федерации', 0, 'Грамматика', 'Гр', 'Владение лексикой и грамматикой', 0, 'Аудиторование', 'Ауд', 'Понимание содержания звучащей речи', 0, 'Устная речь', 'Уст', 'Устное общение', 'Разрешение на работу или патент', 200, 'УРОВЕНЬ: ИР', 'трудящихся мигрантов', 600, 600, 0, 0, 0, 2, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 50, 50, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '[1,3,5,6,7,8,10,11,12]', 'Комплексный экзамен для трудящихся мигрантов', 1),
	(17, 'Упрощенное разрешение на временное проживание', 2, 0, 1, 5, 100, 'История', 'Ист', 'История России', 100, 'Законодательство', 'Зак', 'Основы законодательства  Российской Федерации', 0, 'Грамматика', 'Гр', 'Владение лексикой и грамматикой', 0, 'Аудиторование', 'Ауд', 'Понимание содержания звучащей речи', 0, 'Устная речь', 'Уст', 'Устное общение', 'Разрешение на временное проживание', 200, 'УРОВЕНЬ: РВ', 'лиц, желающих получить разрешение на временное проживание', 600, 600, 0, 0, 0, 2, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 50, 50, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '[3,5,6,7,8,10,11,12]', 'Комплексный экзамен для лиц, желающих получить разрешение на временное проживание', 1),
	(18, 'Упрощенное вида на жительство', 2, 0, 1, 5, 100, 'История', 'Ист', 'История России', 100, 'Законодательство', 'Зак', 'Основы законодательства  Российской Федерации', 0, 'Грамматика', 'Гр', 'Владение лексикой и грамматикой', 0, 'Аудиторование', 'Ауд', 'Понимание содержания звучащей речи', 0, 'Устная речь', 'Уст', 'Устное общение', 'Вид на жительство', 200, 'УРОВЕНЬ: ВЖ', 'лиц, желающих получить вид на жительство', 600, 600, 0, 0, 0, 2, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 75, 75, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '[3,5,6,7,8,10,11,12]', 'Комплексный экзамен для лиц, желающих получить  вид на жительство', 1),
	(19, 'Соискатели разрешения на работу либо патента', 2, 0, 2, 5, 60, 'Чтение', 'Чт', 'Понимание содержания текстов при чтении', 40, 'Письмо', 'Пис', 'Владение письменной речью', 50, 'Грамматика', 'Гр', 'Владение лексикой и грамматикой', 100, 'История', 'Ист', 'История России', 100, 'Законодательство', 'Зак', 'Основы законодательства  Российской Федерации', 'Разрешение на работу или патент', 350, 'УРОВЕНЬ: ИР', 'трудящихся мигрантов', 1450, 800, 1000, 50, 60, 5, 0, '', '', '', 0, '', '', '', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, 0, 0, 50, 50, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 'Комплексный экзамен для трудящихся мигрантов', 1),
	(20, 'Соискатели разрешения на временное проживание', 2, 0, 2, 5, 60, 'Чтение', 'Чт', 'Понимание содержания текстов при чтении', 40, 'Письмо', 'Пис', 'Владение письменной речью', 50, 'Грамматика', 'Гр', 'Владение лексикой и грамматикой', 100, 'История', 'Ист', 'История России', 100, 'Законодательство', 'Зак', 'Основы законодательства  Российской Федерации', 'Разрешение на временное проживание', 350, 'УРОВЕНЬ: РВ', 'лиц, желающих получить разрешение на временное проживание', 1590, 800, 1000, 60, 70, 5, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, 0, 0, 50, 50, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 'Комплексный экзамен для лиц, желающих получить разрешение на временное проживание', 1),
	(21, 'Соискатели вида на жительство', 2, 0, 2, 5, 60, 'Чтение', 'Чт', 'Понимание содержания текстов при чтении', 40, 'Письмо', 'Пис', 'Владение письменной речью', 50, 'Грамматика', 'Гр', 'Владение лексикой и грамматикой', 100, 'История', 'Ист', 'История России', 100, 'Законодательство', 'Зак', 'Основы законодательства  Российской Федерации', 'Вид на жительство', 350, 'УРОВЕНЬ: ВЖ', 'лиц, желающих получить вид на жительство', 1590, 800, 1000, 70, 80, 5, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, 0, 0, 75, 75, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 'Комплексный экзамен для лиц, желающих получить  вид на жительство', 1),
	(22, 'Соискатели вида на жительство (н)', 2, 0, 1, 5, 60, 'Чтение', 'Чт', 'Понимание содержания текстов при чтении', 40, 'Письмо', 'Пис', 'Владение письменной речью', 50, 'Грамматика', 'Гр', 'Владение лексикой и грамматикой', 70, 'Аудирование', 'Ауд', 'Понимание содержания звучащей речи', 80, 'Устная речь', 'Уст', 'Устное общение', 'Вид на жительство', 550, 'УРОВЕНЬ: ВЖ', 'лиц, желающих получить вид на жительство', 1590, 800, 1000, 70, 80, 7, 125, 'История', 'Ист', 'История России', 125, 'Законодательство', 'Зак', 'Основы законодательства Российской Федерации', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, 80, 80, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 'Комплексный экзамен для лиц, желающих получить  вид на жительство', 0),
	(23, 'Упрощенное вида на жительство (н)', 2, 0, 1, 5, 125, 'История', 'Ист', 'История России', 125, 'Законодательство', 'Зак', 'Основы законодательства  Российской Федерации', 0, 'Грамматика', 'Гр', 'Владение лексикой и грамматикой', 0, 'Аудиторование', 'Ауд', 'Понимание содержания звучащей речи', 0, 'Устная речь', 'Уст', 'Устное общение', 'Вид на жительство', 250, 'УРОВЕНЬ: ВЖ', 'лиц, желающих получить вид на жительство', 600, 600, 0, 0, 0, 2, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 80, 80, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '[3,5,6,7,8,10,11,12]', 'Комплексный экзамен для лиц, желающих получить  вид на жительство', 0),
	(24, 'Соискатели вида на жительство (н)', 2, 0, 2, 5, 60, 'Чтение', 'Чт', 'Понимание содержания текстов при чтении', 40, 'Письмо', 'Пис', 'Владение письменной речью', 50, 'Грамматика', 'Гр', 'Владение лексикой и грамматикой', 125, 'История', 'Ист', 'История России', 125, 'Законодательство', 'Зак', 'Основы законодательства  Российской Федерации', 'Вид на жительство', 400, 'УРОВЕНЬ: ВЖ', 'лиц, желающих получить вид на жительство', 1590, 800, 1000, 70, 80, 5, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, 0, 0, 80, 80, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 'Комплексный экзамен для лиц, желающих получить  вид на жительство', 0),
	(25, 'Соискатели вида на жительство (н)', 2, 0, 1, 5, 60, 'Чтение', 'Чт', 'Понимание содержания текстов при чтении', 40, 'Письмо', 'Пис', 'Владение письменной речью', 50, 'Грамматика', 'Гр', 'Владение лексикой и грамматикой', 70, 'Аудирование', 'Ауд', 'Понимание содержания звучащей речи', 80, 'Устная речь', 'Уст', 'Устное общение', 'Вид на жительство', 550, 'УРОВЕНЬ: ВЖ', 'лиц, желающих получить вид на жительство', 1590, 800, 1000, 70, 80, 9, 100, 'История', 'Ист1', 'История России', 25, 'История', 'Ист2', 'История России', 100, 'Законодательство', 'Зак1', 'Основы законодательства Российской Федерации', 25, 'Законодательство', 'Зак2', 'Основы законодательства Российской Федерации', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, 80, 80, 80, 80, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 'Комплексный экзамен для лиц, желающих получить  вид на жительство', 1),
	(26, 'Упрощенное вида на жительство (н)', 2, 0, 1, 5, 100, 'История', 'Ист1', 'История России', 25, 'История', 'Ист2', 'История России', 100, 'Законодательство', 'Зак1', 'Основы законодательства Российской Федерации', 25, 'Законодательство', 'Зак2', 'Основы законодательства Российской Федерации', 0, 'Устная речь', 'Уст', 'Устное общение', 'Вид на жительство', 250, 'УРОВЕНЬ: ВЖ', 'лиц, желающих получить вид на жительство', 600, 600, 0, 0, 0, 4, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 80, 80, 80, 80, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '[3,5,6,7,8,10,11,12]', 'Комплексный экзамен для лиц, желающих получить  вид на жительство', 1),
	(27, 'Соискатели вида на жительство (н)', 2, 0, 2, 5, 60, 'Чтение', 'Чт', 'Понимание содержания текстов при чтении', 40, 'Письмо', 'Пис', 'Владение письменной речью', 50, 'Грамматика', 'Гр', 'Владение лексикой и грамматикой', 100, 'История', 'Ист1', 'История России', 25, 'История', 'Ист2', 'История России', 'Вид на жительство', 400, 'УРОВЕНЬ: ВЖ', 'лиц, желающих получить вид на жительство', 1590, 800, 1000, 70, 80, 7, 100, 'Законодательство', 'Зак1', 'Основы законодательства Российской Федерации', 25, 'Законодательство', 'Зак2', 'Основы законодательства Российской Федерации', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, 0, 0, 80, 80, 80, 80, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 'Комплексный экзамен для лиц, желающих получить  вид на жительство', 1),
	(28, 'Соискатели вида на жительство (60)', 2, 0, 1, 5, 60, 'Чтение', 'Чт', 'Понимание содержания текстов при чтении', 60, 'Письмо', 'Пис', 'Владение письменной речью', 60, 'Грамматика', 'Гр', 'Владение лексикой и грамматикой', 70, 'Аудирование', 'Ауд', 'Понимание содержания звучащей речи', 80, 'Устная речь', 'Уст', 'Устное общение', 'Вид на жительство', 580, 'УРОВЕНЬ: ВЖ', 'лиц, желающих получить вид на жительство', 1590, 800, 1000, 70, 80, 9, 100, 'История', 'Ист1', 'История России', 25, 'История', 'Ист2', 'История России', 100, 'Законодательство', 'Зак1', 'Основы законодательства Российской Федерации', 25, 'Законодательство', 'Зак2', 'Основы законодательства Российской Федерации', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, 80, 80, 80, 80, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 'Комплексный экзамен для лиц, желающих получить  вид на жительство', 1),
	(29, 'Соискатели вида на жительство (60)', 2, 0, 2, 5, 60, 'Чтение', 'Чт', 'Понимание содержания текстов при чтении', 60, 'Письмо', 'Пис', 'Владение письменной речью', 60, 'Грамматика', 'Гр', 'Владение лексикой и грамматикой', 100, 'История', 'Ист1', 'История России', 25, 'История', 'Ист2', 'История России', 'Вид на жительство', 430, 'УРОВЕНЬ: ВЖ', 'лиц, желающих получить вид на жительство', 1590, 800, 1000, 70, 80, 7, 100, 'Законодательство', 'Зак1', 'Основы законодательства Российской Федерации', 25, 'Законодательство', 'Зак2', 'Основы законодательства Российской Федерации', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, 0, 0, 80, 80, 80, 80, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 'Комплексный экзамен для лиц, желающих получить  вид на жительство', 1),
	(30, 'фывфывфыв', 2, 1, 1, 0, 10, 'ыы', 'а', 'аа', 0, 'Письмо', 'Пис', 'Владение письменной речью', 0, 'Грамматика', 'Гр', 'Владение лексикой и грамматикой', 0, 'Аудиторование', 'Ауд', 'Понимание содержания звучащей речи', 0, 'Устная речь', 'Уст', 'Устное общение', '', 10, '', '', 1, 2, 3, 0, 100, 1, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1);
/*!40000 ALTER TABLE `sdt_test_levels` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.sdt_university
DROP TABLE IF EXISTS `sdt_university`;
CREATE TABLE IF NOT EXISTS `sdt_university` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text COMMENT 'Название',
  `short_name` text COMMENT 'Короткое название',
  `form` text COMMENT 'форма',
  `legal_address` text COMMENT 'юридически адрес',
  `contact_phone` varchar(255) DEFAULT NULL COMMENT 'телефон',
  `contact_fax` varchar(255) DEFAULT NULL COMMENT 'факс',
  `contact_email` varchar(255) DEFAULT NULL COMMENT 'почта',
  `contact_other` text COMMENT 'другие контакты',
  `responsible_person` text COMMENT 'ответсвенное лицо',
  `comments` text COMMENT 'коментарии',
  `deleted` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'удаление',
  `parent_id` int(11) DEFAULT NULL,
  `bank` text COMMENT 'банк',
  `city` text COMMENT 'город',
  `rc` text COMMENT 'расчетный счет',
  `lc` text COMMENT 'личный счет',
  `kc` text COMMENT 'корреспондирующий счет',
  `bik` text COMMENT 'бик',
  `inn` text COMMENT 'инн',
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'инн',
  `kpp` text COMMENT 'кпп',
  `okato` text COMMENT 'окато',
  `okpo` text COMMENT 'окпо',
  `rector` text COMMENT 'ректор',
  `user_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `head_id` int(11) NOT NULL DEFAULT '0',
  `is_price_change` int(11) NOT NULL DEFAULT '0',
  `is_head` int(11) NOT NULL DEFAULT '0',
  `api_enabled` tinyint(4) NOT NULL DEFAULT '0',
  `print_invoice_quoute` tinyint(1) NOT NULL DEFAULT '1',
  `region_id` int(11) DEFAULT NULL,
  `is_old_act` tinyint(1) DEFAULT '0',
  `pfur_api` tinyint(1) NOT NULL DEFAULT '0',
  `old_dubl` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user` (`user_id`),
  KEY `head_id` (`head_id`,`deleted`),
  KEY `name` (`id`,`name`(300)),
  KEY `head_id_id` (`is_head`,`id`,`deleted`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=cp1251 AVG_ROW_LENGTH=84 COMMENT='Список университетов';

-- Дамп данных таблицы migrants.sdt_university: ~1 rows (приблизительно)
DELETE FROM `sdt_university`;
/*!40000 ALTER TABLE `sdt_university` DISABLE KEYS */;
INSERT INTO `sdt_university` (`id`, `name`, `short_name`, `form`, `legal_address`, `contact_phone`, `contact_fax`, `contact_email`, `contact_other`, `responsible_person`, `comments`, `deleted`, `parent_id`, `bank`, `city`, `rc`, `lc`, `kc`, `bik`, `inn`, `created`, `kpp`, `okato`, `okpo`, `rector`, `user_id`, `country_id`, `head_id`, `is_price_change`, `is_head`, `api_enabled`, `print_invoice_quoute`, `region_id`, `is_old_act`, `pfur_api`, `old_dubl`) VALUES
	(1, 'ООО Пример', 'Пример', 'Правовая форма', 'Юридический адрес', 'Телефон', '', 'email@email.email', '', 'Ответственный за проведение тестирования', '', 0, 0, 'Банк', 'Город', 'Расчетный счет', 'Лицевой счет', 'Корреспондентский счет', 'БИК1', 'ИНН2', '2020-10-06 15:25:09', 'КПП3', 'Код по ОКАТО4', 'Код по ОКПО5', 'Ректор', 2, 134, 1, 1, 0, 0, 1, 78, 0, 0, 0);
/*!40000 ALTER TABLE `sdt_university` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.sdt_university_dogovor
DROP TABLE IF EXISTS `sdt_university_dogovor`;
CREATE TABLE IF NOT EXISTS `sdt_university_dogovor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` varchar(255) DEFAULT NULL COMMENT 'номер',
  `date` date DEFAULT NULL COMMENT 'дата',
  `caption` text COMMENT 'название',
  `university_id` int(11) DEFAULT NULL COMMENT 'id университета',
  `deleted` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'удаление',
  `scan_id` int(11) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `valid_date` date DEFAULT NULL,
  `print_act` tinyint(1) NOT NULL DEFAULT '0',
  `print_protocol` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `university_id` (`university_id`,`deleted`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=cp1251 COMMENT='Договоры университетов';

-- Дамп данных таблицы migrants.sdt_university_dogovor: ~2 rows (приблизительно)
DELETE FROM `sdt_university_dogovor`;
/*!40000 ALTER TABLE `sdt_university_dogovor` DISABLE KEYS */;
INSERT INTO `sdt_university_dogovor` (`id`, `number`, `date`, `caption`, `university_id`, `deleted`, `scan_id`, `type_id`, `valid_date`, `print_act`, `print_protocol`) VALUES
	(1, '1', '2020-10-06', 'РКИ', 1, 0, 0, 1, '0000-00-00', 0, 0),
	(2, '2', '2020-10-06', 'КЭ', 1, 0, 0, 2, '0000-00-00', 0, 0);
/*!40000 ALTER TABLE `sdt_university_dogovor` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.sdt_university_signing
DROP TABLE IF EXISTS `sdt_university_signing`;
CREATE TABLE IF NOT EXISTS `sdt_university_signing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `center_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `caption` varchar(250) NOT NULL,
  `position` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `center_id` (`center_id`,`type`,`deleted`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=cp1251 COMMENT='Подписывающие у локальных центров';

-- Дамп данных таблицы migrants.sdt_university_signing: ~2 rows (приблизительно)
DELETE FROM `sdt_university_signing`;
/*!40000 ALTER TABLE `sdt_university_signing` DISABLE KEYS */;
INSERT INTO `sdt_university_signing` (`id`, `center_id`, `type`, `deleted`, `caption`, `position`) VALUES
	(1, 1, 'approve', 0, 'Примеров П.П,', 'Ректор'),
	(2, 1, 'responsive', 0, 'Примерович П.П.', 'нач. отдела');
/*!40000 ALTER TABLE `sdt_university_signing` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.sdt_univer_user
DROP TABLE IF EXISTS `sdt_univer_user`;
CREATE TABLE IF NOT EXISTS `sdt_univer_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `univer_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UK_sdt_univer_user` (`univer_id`,`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=cp1251;

-- Дамп данных таблицы migrants.sdt_univer_user: ~0 rows (приблизительно)
DELETE FROM `sdt_univer_user`;
/*!40000 ALTER TABLE `sdt_univer_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `sdt_univer_user` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.special_prices
DROP TABLE IF EXISTS `special_prices`;
CREATE TABLE IF NOT EXISTS `special_prices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level_id` int(11) NOT NULL DEFAULT '0',
  `group_id` int(11) NOT NULL DEFAULT '0',
  `price_first_time` double DEFAULT NULL,
  `price_subtest_1` double DEFAULT NULL,
  `price_subtest_2` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=cp1251;

-- Дамп данных таблицы migrants.special_prices: ~13 rows (приблизительно)
DELETE FROM `special_prices`;
/*!40000 ALTER TABLE `special_prices` DISABLE KEYS */;
INSERT INTO `special_prices` (`id`, `level_id`, `group_id`, `price_first_time`, `price_subtest_1`, `price_subtest_2`) VALUES
	(1, 13, 1, 950, 500, 500),
	(2, 14, 1, 950, 800, 1000),
	(3, 15, 1, 950, 800, 1000),
	(4, 16, 1, 400, 0, 0),
	(5, 18, 1, 400, 0, 0),
	(6, 17, 1, 400, 0, 0),
	(7, 16, 2, 600, 0, 0),
	(8, 22, 1, 950, 800, 1000),
	(9, 23, 1, 400, 0, 0),
	(10, 25, 1, 950, 800, 1000),
	(11, 26, 1, 400, 0, 0),
	(12, 28, 1, 950, 800, 1000),
	(13, 30, 1, 1, 2, 3);
/*!40000 ALTER TABLE `special_prices` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.special_prices_university
DROP TABLE IF EXISTS `special_prices_university`;
CREATE TABLE IF NOT EXISTS `special_prices_university` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `university_id` int(11) NOT NULL DEFAULT '0',
  `level_id` int(11) NOT NULL DEFAULT '0',
  `group_id` int(11) NOT NULL DEFAULT '0',
  `price_first_time` double DEFAULT NULL,
  `price_subtest_1` double DEFAULT NULL,
  `price_subtest_2` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=cp1251;

-- Дамп данных таблицы migrants.special_prices_university: ~0 rows (приблизительно)
DELETE FROM `special_prices_university`;
/*!40000 ALTER TABLE `special_prices_university` DISABLE KEYS */;
/*!40000 ALTER TABLE `special_prices_university` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.statistic
DROP TABLE IF EXISTS `statistic`;
CREATE TABLE IF NOT EXISTS `statistic` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `module` varchar(50) NOT NULL DEFAULT '',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `action` varchar(200) NOT NULL DEFAULT '',
  `data` text,
  `timest` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=205;

-- Дамп данных таблицы migrants.statistic: ~0 rows (приблизительно)
DELETE FROM `statistic`;
/*!40000 ALTER TABLE `statistic` DISABLE KEYS */;
/*!40000 ALTER TABLE `statistic` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.subtest_group
DROP TABLE IF EXISTS `subtest_group`;
CREATE TABLE IF NOT EXISTS `subtest_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `caption` varchar(200) DEFAULT NULL COMMENT 'Название',
  `formula` varchar(200) DEFAULT NULL COMMENT 'Идентификатор формулы расчета',
  `pass_score` varchar(200) DEFAULT NULL COMMENT 'Проходной балл всей группы',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=cp1251 COMMENT='Группы субтестов в одном тесте';

-- Дамп данных таблицы migrants.subtest_group: ~2 rows (приблизительно)
DELETE FROM `subtest_group`;
/*!40000 ALTER TABLE `subtest_group` DISABLE KEYS */;
INSERT INTO `subtest_group` (`id`, `caption`, `formula`, `pass_score`) VALUES
	(1, 'История', 'hist2016', '100'),
	(2, 'Законодательство', 'hist2016', '100');
/*!40000 ALTER TABLE `subtest_group` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.subtest_meta
DROP TABLE IF EXISTS `subtest_meta`;
CREATE TABLE IF NOT EXISTS `subtest_meta` (
  `level_id` int(11) NOT NULL COMMENT 'Уровень тестирования',
  `num` int(11) NOT NULL COMMENT 'Номер субтеста',
  `group_id` int(11) DEFAULT NULL COMMENT 'Группа субтестов',
  `percent_show` int(11) DEFAULT NULL COMMENT 'Отображаемый процент',
  `pass_score` int(11) DEFAULT NULL COMMENT 'Проходной балл',
  `formula_var` varchar(50) DEFAULT NULL COMMENT 'Имя переменной в формуле',
  `vedomost_caption` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`level_id`,`num`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 COMMENT='Мета информация для субтестов';

-- Дамп данных таблицы migrants.subtest_meta: ~20 rows (приблизительно)
DELETE FROM `subtest_meta`;
/*!40000 ALTER TABLE `subtest_meta` DISABLE KEYS */;
INSERT INTO `subtest_meta` (`level_id`, `num`, `group_id`, `percent_show`, `pass_score`, `formula_var`, `vedomost_caption`) VALUES
	(25, 6, 1, 80, NULL, 'a', 'Субтест 1'),
	(25, 7, 1, 20, 10, 'b', 'Субтест 2'),
	(25, 8, 2, 80, NULL, 'a', 'Субтест 1'),
	(25, 9, 2, 20, 10, 'b', 'Субтест 2'),
	(26, 1, 1, 80, NULL, 'a', 'Субтест 1'),
	(26, 2, 1, 20, 10, 'b', 'Субтест 2'),
	(26, 3, 2, 80, NULL, 'a', 'Субтест 1'),
	(26, 4, 2, 20, 10, 'b', 'Субтест 2'),
	(27, 4, 1, 80, NULL, 'a', 'Субтест 1'),
	(27, 5, 1, 20, 10, 'b', 'Субтест 2'),
	(27, 6, 2, 80, NULL, 'a', 'Субтест 1'),
	(27, 7, 2, 20, 10, 'b', 'Субтест 2'),
	(28, 6, 1, 80, NULL, 'a', 'Субтест 1'),
	(28, 7, 1, 20, 10, 'b', 'Субтест 2'),
	(28, 8, 2, 80, NULL, 'a', 'Субтест 1'),
	(28, 9, 2, 20, 10, 'b', 'Субтест 2'),
	(29, 4, 1, 80, NULL, 'a', 'Субтест 1'),
	(29, 5, 1, 20, 10, 'b', 'Субтест 2'),
	(29, 6, 2, 80, NULL, 'a', 'Субтест 1'),
	(29, 7, 2, 20, 10, 'b', 'Субтест 2');
/*!40000 ALTER TABLE `subtest_meta` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.system_message
DROP TABLE IF EXISTS `system_message`;
CREATE TABLE IF NOT EXISTS `system_message` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sender_id` int(10) DEFAULT NULL,
  `text` text,
  `user_type` int(10) DEFAULT NULL,
  `hc_id` int(10) DEFAULT NULL,
  `date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_type` (`user_type`,`hc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC;

-- Дамп данных таблицы migrants.system_message: ~0 rows (приблизительно)
DELETE FROM `system_message`;
/*!40000 ALTER TABLE `system_message` DISABLE KEYS */;
/*!40000 ALTER TABLE `system_message` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.tb_admin_hits
DROP TABLE IF EXISTS `tb_admin_hits`;
CREATE TABLE IF NOT EXISTS `tb_admin_hits` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `u_id` int(8) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `mesg` text CHARACTER SET cp1251 NOT NULL,
  `tip` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `u_id` (`u_id`),
  KEY `tip` (`tip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы migrants.tb_admin_hits: ~0 rows (приблизительно)
DELETE FROM `tb_admin_hits`;
/*!40000 ALTER TABLE `tb_admin_hits` DISABLE KEYS */;
/*!40000 ALTER TABLE `tb_admin_hits` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.tb_groups
DROP TABLE IF EXISTS `tb_groups`;
CREATE TABLE IF NOT EXISTS `tb_groups` (
  `g_id` int(11) NOT NULL AUTO_INCREMENT,
  `g_name` varchar(255) NOT NULL DEFAULT '',
  `head_visible` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`g_id`),
  UNIQUE KEY `g_name` (`g_name`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=cp1251;

-- Дамп данных таблицы migrants.tb_groups: ~23 rows (приблизительно)
DELETE FROM `tb_groups`;
/*!40000 ALTER TABLE `tb_groups` DISABLE KEYS */;
INSERT INTO `tb_groups` (`g_id`, `g_name`, `head_visible`) VALUES
	(21, 'Администраторы', 0),
	(30, 'Бухгалтерия', 1),
	(32, 'Список центров', 0),
	(33, 'Список уровней', 0),
	(34, 'На проверку', 1),
	(35, 'Полученные', 1),
	(36, 'К печати', 1),
	(37, 'Печать', 1),
	(38, 'Ждут оплаты', 1),
	(39, 'Архив', 1),
	(40, 'Отчеты', 1),
	(41, 'Поиск', 1),
	(42, 'Разблокировка документов', 1),
	(43, 'Видит все внешние центры', 0),
	(44, 'Делать документы недействительными', 0),
	(47, 'супервизор', 0),
	(48, 'Управление сертификатами', 0),
	(49, 'Управление подписывающими', 0),
	(50, 'Просмотр отчётов в админке', 0),
	(51, 'Бухгалтерский контроль', 1),
	(55, 'Ввод старых сертификатов', 0);
/*!40000 ALTER TABLE `tb_groups` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.tb_relations
DROP TABLE IF EXISTS `tb_relations`;
CREATE TABLE IF NOT EXISTS `tb_relations` (
  `fk_g_id` int(11) NOT NULL DEFAULT '0',
  `fk_u_id` int(11) NOT NULL DEFAULT '0',
  KEY `fk_u_id` (`fk_u_id`),
  KEY `fk_g_id` (`fk_g_id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

-- Дамп данных таблицы migrants.tb_relations: ~0 rows (приблизительно)
DELETE FROM `tb_relations`;
/*!40000 ALTER TABLE `tb_relations` DISABLE KEYS */;
/*!40000 ALTER TABLE `tb_relations` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.tb_users
DROP TABLE IF EXISTS `tb_users`;
CREATE TABLE IF NOT EXISTS `tb_users` (
  `u_id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(100) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `surname` text NOT NULL,
  `firstname` text NOT NULL,
  `fathername` text NOT NULL,
  `univer_id` int(11) NOT NULL DEFAULT '0',
  `head_id` int(11) DEFAULT NULL,
  `user_type_id` int(11) DEFAULT NULL,
  `password_changed_at` datetime DEFAULT NULL,
  PRIMARY KEY (`u_id`),
  UNIQUE KEY `login` (`login`),
  KEY `head_id` (`head_id`,`login`),
  KEY `univer_id` (`univer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6524 DEFAULT CHARSET=cp1251;

-- Дамп данных таблицы migrants.tb_users: ~4 rows (приблизительно)
DELETE FROM `tb_users`;
/*!40000 ALTER TABLE `tb_users` DISABLE KEYS */;
INSERT INTO `tb_users` (`u_id`, `login`, `password`, `surname`, `firstname`, `fathername`, `univer_id`, `head_id`, `user_type_id`, `password_changed_at`) VALUES
	(1, 'admin', '14e1b600b1fd579f47433b88e8d85291', 'Админ', '', '', 0, NULL, 2, '2021-04-15 12:45:30'),
	(2, 'center_1', '14e1b600b1fd579f47433b88e8d85291', 'ООО Пример', 'Пример', 'Пример', 1, 1, NULL, '2021-04-15 12:45:30'),
	(3, 'signer', '14e1b600b1fd579f47433b88e8d85291', 'Сайнер', 'Тест', 'Тестович', 0, 1, 19, '2021-04-15 12:45:30');
/*!40000 ALTER TABLE `tb_users` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.test_levels_changed_price
DROP TABLE IF EXISTS `test_levels_changed_price`;
CREATE TABLE IF NOT EXISTS `test_levels_changed_price` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `univer_id` int(10) NOT NULL DEFAULT '0' COMMENT 'id университета',
  `test_level_id` int(10) NOT NULL DEFAULT '0' COMMENT 'id уровня тестирования',
  `price` double DEFAULT NULL COMMENT 'цена тестирования',
  `sub_test_price` double DEFAULT NULL COMMENT 'цена пересдачи одного субтеста',
  `sub_test_price_2` double DEFAULT NULL COMMENT 'цена пересдачи одного субтеста второго типа',
  PRIMARY KEY (`id`),
  KEY `test_level_id` (`test_level_id`,`univer_id`),
  KEY `univer_id` (`univer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=cp1251;

-- Дамп данных таблицы migrants.test_levels_changed_price: ~0 rows (приблизительно)
DELETE FROM `test_levels_changed_price`;
/*!40000 ALTER TABLE `test_levels_changed_price` DISABLE KEYS */;
/*!40000 ALTER TABLE `test_levels_changed_price` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.test_level_groups
DROP TABLE IF EXISTS `test_level_groups`;
CREATE TABLE IF NOT EXISTS `test_level_groups` (
  `level_id` int(10) NOT NULL DEFAULT '0',
  `group_id` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`level_id`,`group_id`),
  KEY `group_id` (`group_id`),
  KEY `level_id` (`level_id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

-- Дамп данных таблицы migrants.test_level_groups: ~28 rows (приблизительно)
DELETE FROM `test_level_groups`;
/*!40000 ALTER TABLE `test_level_groups` DISABLE KEYS */;
INSERT INTO `test_level_groups` (`level_id`, `group_id`) VALUES
	(2, 2),
	(3, 3),
	(4, 4),
	(5, 5),
	(6, 6),
	(7, 7),
	(8, 8),
	(9, 9),
	(10, 10),
	(11, 11),
	(12, 12),
	(13, 13),
	(16, 13),
	(19, 13),
	(14, 14),
	(17, 14),
	(20, 14),
	(15, 15),
	(18, 15),
	(21, 15),
	(22, 15),
	(23, 15),
	(24, 15),
	(25, 15),
	(26, 15),
	(27, 15),
	(28, 15),
	(29, 15);
/*!40000 ALTER TABLE `test_level_groups` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.test_level_type
DROP TABLE IF EXISTS `test_level_type`;
CREATE TABLE IF NOT EXISTS `test_level_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `caption` varchar(200) NOT NULL DEFAULT '',
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `deleted` (`deleted`,`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=cp1251;

-- Дамп данных таблицы migrants.test_level_type: ~2 rows (приблизительно)
DELETE FROM `test_level_type`;
/*!40000 ALTER TABLE `test_level_type` DISABLE KEYS */;
INSERT INTO `test_level_type` (`id`, `caption`, `deleted`) VALUES
	(1, 'Лингводидактическое тестирование по русскому языку как иностранному', 0),
	(2, 'Интеграционный экзамен по русскому языку, истории России и основам законодательства Российской Федерации', 0);
/*!40000 ALTER TABLE `test_level_type` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.user_type
DROP TABLE IF EXISTS `user_type`;
CREATE TABLE IF NOT EXISTS `user_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `caption` varchar(255) NOT NULL DEFAULT '',
  `head_visible` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `head_visible` (`head_visible`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=cp1251;

-- Дамп данных таблицы migrants.user_type: ~18 rows (приблизительно)
DELETE FROM `user_type`;
/*!40000 ALTER TABLE `user_type` DISABLE KEYS */;
INSERT INTO `user_type` (`id`, `caption`, `head_visible`) VALUES
	(2, 'Admin_sys', 0),
	(5, 'Admin_center', 1),
	(6, 'Supervisor', 1),
	(7, 'Check_doc', 1),
	(8, 'Get_invoice_doc', 1),
	(10, 'Print_doc', 1),
	(11, 'Payment_doc', 1),
	(12, 'Archiev_doc', 1),
	(13, 'Bookkeeper_doc', 1),
	(14, 'Report_seach_doc', 1),
	(19, 'Signer', 1),
	(20, 'Reporter', 0),
	(21, 'contr_buh', 1);
/*!40000 ALTER TABLE `user_type` ENABLE KEYS */;

-- Дамп структуры для таблица migrants.user_type_group_relations
DROP TABLE IF EXISTS `user_type_group_relations`;
CREATE TABLE IF NOT EXISTS `user_type_group_relations` (
  `user_type_id` int(1) NOT NULL DEFAULT '0',
  `group_id` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_type_id`,`group_id`),
  KEY `user_type_id` (`user_type_id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

-- Дамп данных таблицы migrants.user_type_group_relations: ~52 rows (приблизительно)
DELETE FROM `user_type_group_relations`;
/*!40000 ALTER TABLE `user_type_group_relations` DISABLE KEYS */;
INSERT INTO `user_type_group_relations` (`user_type_id`, `group_id`) VALUES
	(2, 33),
	(2, 40),
	(2, 41),
	(2, 50),
	(5, 32),
	(5, 40),
	(5, 41),
	(5, 42),
	(5, 43),
	(6, 30),
	(6, 34),
	(6, 35),
	(6, 36),
	(6, 37),
	(6, 38),
	(6, 39),
	(6, 40),
	(6, 41),
	(6, 42),
	(6, 43),
	(6, 44),
	(6, 47),
	(7, 34),
	(8, 35),
	(10, 37),
	(11, 38),
	(12, 39),
	(13, 30),
	(13, 43),
	(14, 40),
	(14, 41),
	(19, 32),
	(19, 40),
	(19, 41),
	(19, 42),
	(19, 43),
	(19, 49),
	(20, 50),
	(21, 30),
	(21, 43),
	(21, 51);
/*!40000 ALTER TABLE `user_type_group_relations` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
