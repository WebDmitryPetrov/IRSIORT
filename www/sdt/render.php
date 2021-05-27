<?php
class Render
{
    static $instance;
    static function getInstance(){
        if(!self::$instance){
            self::$instance=new self();
        }
        return   self::$instance;
    }

    private  function __construct()
    {

    }

    function view($template, $parameters = array())
    {
        ob_start();
        extract($parameters);
        $C=AbstractController::getInstance();
        $template = dirname(__FILE__) . '/template/view/' . $template . '.php';
        //echo $template;
        if (file_exists($template) && is_file($template)) {
            global $controller;
            $UserRole = $controller->getCurrentRole();
            $Roles=Roles::getInstance();
            require($template);
        }

        return ob_get_clean();
    }

    function form($template, Model $object, $legend = null,$buttons=array())
    {
        ob_start();

        $template = dirname(__FILE__) . '/template/form/' . $template . '.php';
        //echo $template;
        $C=AbstractController::getInstance();
        if (file_exists($template) && is_file($template)) {
            global $controller;
            $UserRole = $controller->getCurrentRole();
            $Roles=Roles::getInstance();
            require($template);
        }

        return ob_get_clean();
    }

    function main($content)
    {
        ob_start();
        global $lang;
        global $controller;
        $UserRole = $controller->getCurrentRole();
        $C=AbstractController::getInstance();
        $Roles=Roles::getInstance();
        require_once 'template/main.php';

        echo ob_get_clean();
    }

    function import($template_name,$parameters=array())
    {
       ob_start();
        $template = dirname(__FILE__) . '/template/view/' . $template_name . '.php';
        $result='';
        if (file_exists($template) && is_file($template)) {
        //    global $controller;
          //  $UserRole = $controller->getCurrentRole();
          //  ob_start();
            extract($parameters);
            $C=AbstractController::getInstance();
            $Roles=Roles::getInstance();
           require($template);
        }
        else{
            die('no template');
        }
       // echo $result;
        return ob_get_clean();
    }

}