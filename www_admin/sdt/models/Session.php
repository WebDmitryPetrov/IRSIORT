<?php

/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 13.07.2015
 * Time: 12:27
 */
class Session
{

    /**
     * @return null|integer
     */
    public static function getLocalCenterID()
    {
        return !empty($_SESSION['univer_id']) ? $_SESSION['univer_id'] : null;
    }

    public static function getUserID()
    {
        return !empty($_SESSION['u_id']) ? $_SESSION['u_id'] : null;
    }

    public static function setFlash($string)
    {
        $_SESSION['flash'] = $string;
    }
}
