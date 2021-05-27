<?php

class Groups extends ArrayObject
{

    public function __construct($array = array())
    {
        parent::__construct($array);

    }

    static public function getAll()
    {
        $list = new Groups();
        $sql = 'select g_name,g_id from tb_groups';
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new Group($row);

        }

        return $list;
    }

//    public static function getAvailable4Act($act_id)
//    {
//        $sqlUsed = 'select COUNT(DISTINCT IF(`level_id`= 1, level_id, NULL)) as migrants,
//  COUNT(DISTINCT IF(`level_id` >1, level_id, NULL)) as other
//  from sdt_act_test where act_id= ' . intval($act_id);
//
//        //die($sqlUsed);
//        $result_used = mysql_query($sqlUsed) or die(mysql_error());
//        $row = mysql_fetch_assoc($result_used);
//
//        $restrict = '';
//        if ($row['migrants']) {
//            $restrict = ' and id=1';
//        }
//        if ($row['other']) {
//            $restrict = ' and id>1';
//        }
//
//        $list = new TestLevels();
//        $sql = 'select * from sdt_test_levels  where deleted=0 ' . $restrict . ' order by id';
//        $result = mysql_query($sql) or die(mysql_error());
//        while ($row = mysql_fetch_assoc($result)) {
//            $list[] = new TestLevel($row);
//        }
//
//        return $list;
//    }
}

class Group extends Model
{
    protected $_table = 'tb_groups';


    public $g_name;
    public $head_visible;
    public $g_id;

    protected $primary = 'g_id';

    public function __construct($input = false)
    {
        parent::__construct($input);

    }

    static function getByID($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $sql = 'select * from tb_groups where g_id=\'' . mysql_real_escape_string($id) . '\'';
        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return false;
        }
        $group = new Group(mysql_fetch_assoc($result));
//die (var_dump($univer));
        return $group;
    }

    public function setFields()
    {
        $this->fields = array(
            'g_id',
            'g_name',
            'head_visible'

        );
    }

    public function getEditFields()
    {
        return array(
            'g_name',
            'head_visible'

            );
    }

//    public function getFkFields()
//    {
//        return array('total');
//    }

    public function setFieldsTypes()
    {
        $this->fieldTypes = array('head_visible' => 'select');
    }

    public function setTranslate()
    {
        $this->translate = array(
            'id' => 'Идентификатор',
            'g_name' => 'Название',
            'head_visible' => 'Видит ли администратор головного центра'
            );
    }

    public function save()
    {

        /*$this->price = floatval(str_replace(',', '.', $this->price));
        $this->sub_test_price = floatval(str_replace(',', '.', $this->sub_test_price));*/

        parent::save();
    }

    public function __toString()
    {
        return $this->caption;

    }
    protected function setAvailableValues()
    {
        $show=array(0=>"Нет", 1=>"Да");


        $this->availableValues = array(
            'head_visible' => $show,

        );

    }
    public function delete()
    {
        $sql = 'delete from tb_groups where g_id = ' . $this->g_id;
        mysql_query($sql);
        $sql = 'delete from tb_relations where fk_g_id = ' . $this->g_id;
        mysql_query($sql);

        return true;
    }
}