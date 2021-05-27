<?php

/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 03.09.14
 * Time: 10:39
 */
class TestLevelTypes extends ArrayObject
{


    static public function getAll()
    {
        $list = new TestLevelTypes();
        $sql = 'select * from test_level_type  where deleted=0 order by id';
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new TestLevelType($row);
        }

        return $list;
    }


}

class TestLevelType extends Model
{

    protected $_table = 'test_level_type';

    public $id;
    public $caption;
    public $deleted;


    static function getByID($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $sql = 'select * from test_level_type where id=\'' . mysql_real_escape_string($id) . '\'';
        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return false;
        }
        $univer = new TestLevelType(mysql_fetch_assoc($result));

        return $univer;
    }


    static function getBlankTypes($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $sql = 'select * from blank_types where test_level_type_id=\'' . mysql_real_escape_string($id) . '\'';
        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return false;
        }
        $univer = new TestLevelType(mysql_fetch_assoc($result));

        return $univer;
    }



    public function setFields()
    {
        $this->fields = array(
            'id',
            'caption',
            'deleted',


        );
    }

    public function getEditFields()
    {
        $result = array(
            'caption',


        );

        return $result;
    }

    public function getFkFields()
    {
        return array();
    }

    public function setFieldsTypes()
    {
        $this->fieldTypes = array();
    }

    public function setTranslate()
    {
        $this->translate = array(
            'id' => 'Èäåíòèôèêàòîğ',
            'caption' => 'Íàçâàíèå',


        );


    }


    public function __toString()
    {
        return $this->caption;

    }

    /**
     * @return TestLevel[]
     */
    public function getTestLevels()
    {
        return TestLevels::getByType($this->id);
    }

}