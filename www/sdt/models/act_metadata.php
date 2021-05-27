<?php

/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 04.03.2015
 * Time: 16:27
 */
class ActMetaData extends Model
{

    const TABLE = 'act_metadata';
    public $_table = self::TABLE;

    static function getByActId($id)
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id) {
            return null;
        }

        $con = Connection::getInstance();
        $res = $con->query('select * from ' . self::TABLE . ' where act_id =  ' . $id, 1);
        if (!$res) {
            return null;
        }

        return new self($res);

    }


    public $id;
    public $act_id;
    public $special_price_group;
    public $test_group = 1;


    public function setFields()
    {
        $this->fields = array(
            'id',
            'act_id',
            'special_price_group',
            'test_group',


        );
    }

    protected function setFieldsTypes()
    {

    }

    public function getEditFields()
    {
        return array(

            'act_id',
            'special_price_group',
            'test_group',


        );
    }


}