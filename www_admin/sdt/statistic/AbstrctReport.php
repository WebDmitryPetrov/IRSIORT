<?php

/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 23.06.2016
 * Time: 11:21
 */
abstract class AbstractReport
{
    


    public function date($date){
        return date('d.m.Y',strtotime($date));
    }

    public function mysql_date($date){
        return date('Y-m-d',strtotime($date));
    }


    public function cp2Utf($string){
        return mb_convert_encoding($string, 'utf8', 'cp1251');

    }
}