<?php
header("Content-Type: text/xml");
require_once 'DogovorWriter.php';
$pdo = new PDO('mysql:host=localhost;dbname=migrants_multi','root','',array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));


$stmt=$pdo->query('select * from sdt_university_dogovor where deleted = 0 and university_id = 168');
//var_dump($pdo->errorInfo());
//var_dump($stmt);

$types=$stmt->fetchAll(PDO::FETCH_ASSOC);
//die(var_dump($array));
$wr = new DogovorWriter($types);
echo $wr->makeXml();
