<?php

/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 04.03.2015
 * Time: 16:27
 */
class SpecialPrice extends Model
{
    const TABLE = 'special_prices';
    public $_table = self::TABLE;

    static function getById($id)
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id) {
            return null;
        }

        $con = Connection::getInstance();
        $res = $con->query('select * from ' . self::TABLE . ' where id =  ' . $id, 1);
        if (!$res) {
            return null;
        }

        return new self($res);

    }

    static function getByGroupAndLevel($group_id, $level_id)
    {
        $level_id = filter_var($level_id, FILTER_VALIDATE_INT);
        if (!$level_id) {
            return null;
        }

        $group_id = filter_var($group_id, FILTER_VALIDATE_INT);
        if (!$group_id) {
            return null;
        }

        $con = Connection::getInstance();
        $res = $con->query(
            'select * from ' . self::TABLE . '
        where
        level_id =  ' . $level_id . '
        and group_id = ' . $group_id,
            1
        );
        if (!$res) {
            return null;
        }

        return new self($res);

    }


    const GROUP_DNR_LNR = 1;

    public $id;
    public $level_id;
    public $group_id;
    public $price_first_time;
    public $price_subtest_1;
    public $price_subtest_2;

    public function setFields()
    {
        $this->fields = array(
            'id',
            'level_id',
            'group_id',
            'price_first_time',
            'price_subtest_1',
            'price_subtest_2',


        );
    }

    protected function setFieldsTypes()
    {

    }

    public function getEditFields()
    {
        return array(
//            'id',
//            'level_id',
//            'group_id',
            'price_first_time',
            'price_subtest_1',
            'price_subtest_2',


        );
    }


    public function setTranslate()
    {
        $this->translate = array(

            'price_first_time' => 'Стоимость тестирования одного человека',
            'price_subtest_1' => 'Стоимость пересдачи одного субтеста',
            'price_subtest_2' => 'Стоимость пересдачи двух субтестов',

        );


    }

    public function getFkFields()
    {
        return array('level_id', 'group_id');
    }




}