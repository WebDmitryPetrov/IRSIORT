<?php

class ExamHelper
{

    public static function getLevelGroups()
    {
        /*return array(
                            13 => 0,
                            16 => 0,
                            19 => 0,
                            14 => 1,
                            17 => 1,
                            20 => 1,
                            15 => 2,
                            18 => 2,
                            21 => 2,
                            22 => 2,
                            23 => 2,
                            24 => 2,
                            25 => 2,
                            26 => 2,
                            27 => 2,
                            28 => 2,
                            29 => 2,
                        );*/

        $list = [];

        foreach (self::getRNRlevels() as $level_id) {
            $list[$level_id] = 0;
        }

        foreach (self::getRVJlevels() as $level_id) {
            $list[$level_id] = 1;
        }

        foreach (self::getVNJlevels() as $level_id) {
            $list[$level_id] = 2;
        }

        return $list;
    }


    public static function getLevelGroup($level_id)
    {
        $levels = self::getLevelGroups();

        return array_key_exists($level_id, $levels) ? $levels[$level_id] : null;
    }

    /**
     * id ������� �� ������ ��� ������
     * @return array
     */
    public static function getRNRlevels()
    {
        //id ������� �� ������ ��� ������
        return array(13, 16, 19);
    }

    /**
     * id ������� �� ���������� �� ��������� ����������
     * @return array
     */
    public static function getRVJlevels()
    {
        //id ������� �� ���������� �� ��������� ����������
        return array(14, 20, 17);
    }

    /**
     * id ������� �� ��� �� ����������
     * @return array
     */
    public static function getVNJlevels()
    {
        //id ������� �� ��� �� ����������
        return array(15, 21, 18, 22, 23, 24, 25, 26, 27, 28, 29);
    }

    /**
     * id ������� � ������� ����������
     * @return array
     */
    public static function getFullExamlevels()
    {
        //id ������� � ������� ����������
        return array(13, 14, 15, 19, 20, 21, 22, 24, 25, 27, 28, 29);
    }


}