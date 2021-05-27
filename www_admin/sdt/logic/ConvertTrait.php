<?php
/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 05.10.2017
 * Time: 11:45
 */

trait ConvertTrait
{
    public function to1251($strval)
    {
        if (!is_string($strval)) {
            $strval = strval($strval);
        }

        return iconv('UTF-8', 'CP1251', $strval);
    }
    public function encode($text)
    {
        return iconv('CP1251', 'UTF-8', $text);

    }
    public function dateTime($date_received, $emptyble = false)
    {
        if ($emptyble && ($date_received == '0000-00-00 00:00:00' || is_null($date_received))) {
            return '';
        }

        return date('d.m.Y H:i', strtotime($date_received));
    }

    public function isDate($valid_date)
    {
        return ($valid_date && $valid_date != '0000-00-00');
    }

    public function utf_encode($string)
    {
        return utf_encode($string);

    }

    public function utf_decode($string)
    {
        return utf_decode($string);
    }

    public function recursive_utf_decode($array)
    {
        return recursive_utf_decode($array);
    }

    public function date($date, $emptyble = false)
    {
        if ($emptyble && ($date == '0000-00-00' || is_null($date))) {
            return '';
        }

        return date('d.m.Y', strtotime($date));
    }

    public function mysql_date($date)
    {
        return date('Y-m-d', strtotime($date));
    }
}