<?php
header("Content-Type: text/xml");
require_once 'CountryWriter.php';
$pdo = new PDO('mysql:host=localhost;dbname=migrants_multi','root','',array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
$stmt=$pdo->query('select * from country');
//var_dump($pdo->errorInfo());
//var_dump($stmt);

$array=$stmt->fetchAll(PDO::FETCH_ASSOC);
//die(var_dump($array));
$wr = new CountryWriter($array);
echo $wr->makeXml();
