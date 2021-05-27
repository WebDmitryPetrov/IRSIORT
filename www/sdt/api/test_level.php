<?php
header("Content-Type: text/xml");
require_once 'TestLevelWriter.php';
$pdo = new PDO('mysql:host=localhost;dbname=migrants_multi','root','',array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
$stmt=$pdo->query('select * from sdt_test_levels where deleted = 0');
//var_dump($pdo->errorInfo());
//var_dump($stmt);

$array=$stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt=$pdo->query('select * from test_level_type where deleted = 0');
//var_dump($pdo->errorInfo());
//var_dump($stmt);

$types=$stmt->fetchAll(PDO::FETCH_ASSOC);
//die(var_dump($array));
$wr = new TestLevelWriter($array,$types);
echo $wr->makeXml();
