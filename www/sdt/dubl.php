<?php
require_once 'controllers/dubl.php';
$controller=Dubl::getInstance();


$render=Render::getInstance();

$content=$controller->executeAction();

$render->main($content);


