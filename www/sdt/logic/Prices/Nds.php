<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 13.04.2018
 * Time: 15:33
 */

namespace SDT\logic\Prices;


class Nds
{
    private $date;

    public function __construct($date)
    {
        $this->date=new \DateTime($date);
    }

    public function getNdsValue()
    {
        if ($this->date >= \Sdt_Config::getDateNewNDSValueBegin()) return 20;
        else return 18;
    }

    public function getNdsPart($value)
    {
        return $value - $this->getCostPart($value);
    }

    public function getCostPart($value)
    {
        return round($value / ( ($this->getNdsValue() + 100) / 100), 2);
    }

    public static function isNeedNDS($act)
    {
        if (new \DateTime($act->created) >= \Sdt_Config::getDateInvoceWithNDSBegin()) return true;
        return false;
    }
}