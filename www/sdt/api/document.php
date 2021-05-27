<?php
header("Content-Type: text/xml");

require_once 'DocumentWriter.php';
$doc = new DocumentWriter();

//die('tet');
echo $doc->makeXml();