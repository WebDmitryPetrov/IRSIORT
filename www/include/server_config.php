<?php
$db_serv = "localhost";
$db_name = "migrants";
$db_user = "root";
$db_password = "";
ini_set('memory_limit','2048M');
Sdt_Config::setSummaryProtocolArchive([ ]);

Sdt_Config::setHiddenUserTypes([19,21,13,9]);

Sdt_Config::setDateInvoceWithNDSBegin(new \DateTime('1.01.2018'));

ini_set('display_errors', '0');
define('SDT_UPLOAD_DIR', 'E:/storage/');
define('SDT_ACT_MAN_FILES', 'E:/storage_act_man/');
define('SDT_UPLOAD_SUMMARY_TABLE_DIR', 'E:/storage_summary_protocol/');
define('FRDO_EXCEL_UPLOAD_DIR', 'E:\\frdo'); 
define('ARCHIVE_UPLOAD_DIR',  'e:/archive/storage/');
define('ARCHIVE_ACT_MAN_FILES',  'e:/archive/storage_act_man/');
define('OUT_OF_ORDER_BY_TIME',false);

Sdt_Config::setLoginAttemptsTimeout(1 * 60);
Sdt_Config::setLoginAttemptsBlockTimeout(365 * 24 * 60 * 60);
Sdt_Config::setLoginAttemptsNumber(10);

