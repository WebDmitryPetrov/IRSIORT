<?php
/** @var ActMan $Man */
$cert_type= $Man->getAct()->test_level_type_id;

$filename =  dirname(__FILE__).'\certificate_type_' . $cert_type . '.php';
//var_dump($cert_type,$filename,file_exists($filename));
if ($cert_type && file_exists($filename))
{
//    die('t2');
    require_once($filename);

}
else
{
    require_once('certificate_type_1.php');
}