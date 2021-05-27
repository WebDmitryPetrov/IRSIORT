<?php
require_once 'controllers/main.php';
$controller=Controller::getInstance();
/*
$refl=new ReflectionClass($controller);
foreach($refl->getMethods() as $method){
    if(preg_match('|.*_action$|',$method->name)) {
        echo "'".str_replace('_action','',$method->name)."',<br>";
    }
}

die();*/
//die(var_dump($_SESSION));
$render=Render::getInstance();

$content=$controller->executeAction();

$render->main($content);


