<?php
/**
 * Created by PhpStorm.
 * User: LWR
 * Date: 18.09.2017
 * Time: 10:29
 */

class Reexam_config
{

    public static function getHeadCenters()
    {
        $HCs=array();
        foreach (HeadCenters::getAll() as $HC)
        {
            if ($HC->horg_id != 1) continue; //����� ������ ���� ���� ���
            $HCs[]=(int)$HC->id; //����� �� int - �� ���� :)
        }
        return $HCs;
    }

    public static function getTestLevels()
    {
        return array(13,16,19); //���������� ���������� �� ������ ���� �������
    }

    public static function isShowInAct($test_level_type)
    {

        //return false;


        if ($test_level_type == 2 && in_array(CURRENT_HEAD_CENTER, Reexam_config::getHeadCenters())) return true;
        else return false;
    }

}