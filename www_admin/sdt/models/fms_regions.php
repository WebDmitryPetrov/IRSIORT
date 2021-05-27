<?php

/**
 * Created by PhpStorm.
 * User: LWR
 * Date: 03.09.14
 * Time: 10:39
 */
class FmsRegions extends ArrayObject
{


    static public function getAll()
    {
        $list = new FmsRegions();
        $sql = 'select * from fms_regions order by id';
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new FmsRegion($row);
        }

        return $list;
    }


}

class FmsRegion extends Model
{

    protected $_table = 'fms_regions';

    public $id;
    public $caption;
    public $r_num;
//    public $deleted;


    static function getByID($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $sql = 'select * from fms_regions where id=\'' . mysql_real_escape_string($id) . '\'';
        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return false;
        }
        $univer = new FmsRegion(mysql_fetch_assoc($result));

        return $univer;
    }

    public function setFields()
    {
        $this->fields = array(
            'id',
            'caption',
            'r_num',
//            'deleted',


        );
    }

    public function getEditFields()
    {
        $result = array(
            'caption',
            'r_num',


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
            'caption' => 'Íàèìåíîâàíèå ñóáúåêòà',
            'r_num' => 'Êîä ÎÊÒÌÎ',

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