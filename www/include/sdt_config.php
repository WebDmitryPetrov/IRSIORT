<?php
/**
 * Created by PhpStorm.
 * User: ������
 * Date: 28.11.2017
 * Time: 11:55
 */

class Sdt_Config
{
    static $summary_protocol_archive = array();

    static $hidden_user_types = [];

    /** @var \DateTime */
    static $dateInvoceWithNDSBegin;

    /**
     * @var int Кол-во попыток за интервал
     */
    static private $loginAttemptsNumber = 5;
    /**
     * @var float|int Время отслеживания
     */
    static  private $loginAttemptsTimeout = 1 * 60;
    /**
     * @var float|int Время блокировки
     */
    static  private $loginAttemptsBlockTimeout = 15 * 60;

    public static function getSummaryProtocolArchive()
    {
        return self::$summary_protocol_archive;
    }

    public static function setSummaryProtocolArchive(array $spa)
    {
        self::$summary_protocol_archive = $spa;
    }

    /**
     * @return array
     */
    public static function getHiddenUserTypes()
    {
        return self::$hidden_user_types;
    }

    /**
     * @param array $hidden_user_types
     */
    public static function setHiddenUserTypes($hidden_user_types)
    {
        self::$hidden_user_types = $hidden_user_types;
    }

    /**
     * @return \DateTime
     */
    public static function getDateInvoceWithNDSBegin()
    {
        return self::$dateInvoceWithNDSBegin;
    }

    /**
     * @param \DateTime $dateInvoceWithNDSBegin
     */
    public static function setDateInvoceWithNDSBegin(\DateTime $dateInvoceWithNDSBegin)
    {
        self::$dateInvoceWithNDSBegin = $dateInvoceWithNDSBegin;
    }

    public static function getDateNewNDSValueBegin()
    {
        return new \DateTime("2019-01-01 00:00");
    }

    public static function isOutOfOrder()
    {
        if (defined('OUT_OF_ORDER_BY_TIME') && OUT_OF_ORDER_BY_TIME) {
            $now = new \DateTime();

            $start = new  \DateTime("12:00");
            $stop = new  \DateTime("12:10");
//var_dump($start,$now,$stop);
            if ($start <= $now && $now <= $stop) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return int
     */
    public static function getLoginAttemptsNumber()
    {
        return self::$loginAttemptsNumber;
    }

    /**
     * @param int $loginAttemptsNumber
     */
    public static function setLoginAttemptsNumber($loginAttemptsNumber)
    {
        self::$loginAttemptsNumber = $loginAttemptsNumber;
    }

    /**
     * @return float|int
     */
    public static function getLoginAttemptsTimeout()
    {
        return self::$loginAttemptsTimeout;
    }

    /**
     * @param float|int $loginAttemptsTimeout
     */
    public static function setLoginAttemptsTimeout($loginAttemptsTimeout)
    {
        self::$loginAttemptsTimeout = $loginAttemptsTimeout;
    }

    /**
     * @return float|int
     */
    public static function getLoginAttemptsBlockTimeout()
    {
        return self::$loginAttemptsBlockTimeout;
    }

    /**
     * @param float|int $loginAttemptsBlockTimeout
     */
    public static function setLoginAttemptsBlockTimeout($loginAttemptsBlockTimeout)
    {
        self::$loginAttemptsBlockTimeout = $loginAttemptsBlockTimeout;
    }

}