<?php
require_once 'sdt_config.php';
require_once 'server_config.php';
//require_once 'text.php';
date_default_timezone_set('Europe/Moscow');
date_default_timezone_set('Europe/Minsk');

define('PASSWORD_DURATION',60*60*24*30*3); //60*60*24*30*3   - 3 мес€ца
define('PASSWORD_REMIND',60*60*24*14 ); //60*60*24*30*3   - 1 недел€
define('DC',__DIR__.'/..'); //DOCUMENT_ROOT

//$db_conn = mysql_connect($db_serv, $db_user, $db_password) or die(mysql_error());
//$db_database = mysql_select_db($db_name);
$db_table_files = 'files'; //arm
$db_table_disc_value = ''; //arm
$db_table_spec_value = ''; //arm
$catalog = 'c:/test_folder_upload'; //arm

$color = array(
    1 =>
    '#FFFF99',
    '#FFC993',
    '#6BDCF8',
    '#87FAD5',
    '#99ee66',
    '#33FFCC',
    '#ECE9D8',
    'pink',
    '#99FFbb',
    '#FFFF99',
    '#ECE9D8',
    '#99CC99',
    '#FFC993',
    '#6BDCF8',
    '#87FAD5',
    '#99ee66',
    '#33FFCC',
    '#ECE9D8',
    'pink',
    '#99FFbb',
    '#FFFF99',
    '#ECE9D8',
    '#99CC99',
    '#FFC993',
    '#6BDCF8',
    '#87FAD5',
    '#99ee66',
    '#33FFCC',
    '#ECE9D8',
    'pink',
    '#99FFbb',
    '#FFFF99',
    '#ECE9D8',
    '#99CC99',
    '#FFC993',
    '#6BDCF8',
    '#87FAD5',
    '#99ee66',
    '#33FFCC',
    '#ECE9D8',
    'pink',
    '#99FFbb',
    '#FFFF99',
    '#ECE9D8',
    '#99CC99',
    '#FFC993',
    '#6BDCF8',
    '#87FAD5',
    '#99ee66',
    '#33FFCC',
    '#ECE9D8',
    'pink',
    '#99FFbb',
    '#FFFF99',
    '#ECE9D8',
    '#99CC99',
    '#FFC993',
    '#6BDCF8',
    '#87FAD5',
    '#99ee66',
    '#33FFCC',
    '#ECE9D8',
    'pink',
    '#99FFbb',
    '#FFFF99',
    '#ECE9D8',
    '#99CC99',
    '#cccccc'
);
$rash = array(
    ".txt",
    ".doc",
    ".rtf",
    ".xls",
    ".ppt",
    ".pps",
    '.pdf',
    '.gif',
    'jpeg',
    '.jpg',
    '.rar',
    '.zip',
    '.png',
    'docx',
    'xlsx',
    "pptx",
    "ppsx",
);
//arm
$extensions = array(
    "txt",
    "doc",
    "rtf",
    "xls",
    "ppt",
    "pps",
    'pdf',
    'gif',
    'jpeg',
    'jpg',
    'rar',
    'zip',
    'png',
    'docx',
    'xlsx',
    "pptx",
    "ppsx",
);
$groups = array();

$groups['admin'] = 21;
$groups['buh'] = 30;
$groups['center'] = 31;
$groups['level_list'] = 33;
$groups['center_list'] = 32;

$groups['for_check'] = 34;
$groups['received'] = 35;
$groups['for_print'] = 36;
$groups['print'] = 37;
$groups['wait_payment'] = 38;
$groups['archive'] = 39;
$groups['report'] = 40;
$groups['search'] = 41;
$groups['unblock'] = 42;
$groups['see_all'] = 43;
$groups['act_invalid'] = 44;
$groups['supervisor'] = 47;


$groups['arm_delete_doc'] = 29;


$groups['center_external'] = -1;


$groups['certificate_manager'] = 48;

$groups['signer_manager'] = 49;

$groups['contr_buh'] = 51;
$groups['adm_bso'] = 53;