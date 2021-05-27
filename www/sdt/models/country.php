<?php

class Countries extends ArrayObject
{

    public function __construct($array = array())
    {
        parent::__construct($array);

    }

    static public function getAll()
    {
        $list = new Countries();
        $sql = 'select * from country order by name';
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new Country($row);
        }

        return $list;
    }

    public static function getAll4Form()
    {
        $list = self::getAll();
        $result = array(
            0 => 'Не указано'
        );
        foreach ($list as $item) {
            $result[$item->id] = $item->name;
        }

        return $result;
    }

    public static function getAllID()
    {
//        $list = new Countries();
        $list = array();
        $sql = 'select * from country order by name';
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = $row['id'];
        }

        return $list;
    }

    public static function getDNR()
    {
        $list = new Countries();
        $sql = 'select * from country where id in (215) order by name';
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new Country($row);
        }

        return $list;
    }

    public static function getDefault()
    {
        $list = new Countries();
        $sql = 'select * from country where id not in (215) order by name';
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new Country($row);
        }

        return $list;
    }

    public static function getUkraine()
    {
        $list = new Countries();
        $sql = 'select * from country where id in (171) order by name';
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new Country($row);
        }

        return $list;
    }


}

class Country extends Model
{
    protected $_table = 'country';

    public $id;
    public $name;


    public function __construct($input = false)
    {
        parent::__construct($input);

    }

    private static $_cache = array();

    static function getByID($id)
    {

        if (!is_numeric($id)) {
            return false;
        }
        if (!array_key_exists($id, self::$_cache)) {
            $sql = 'select * from country where id=\'' . mysql_real_escape_string($id) . '\'';
            $result = mysql_query($sql);
            if (!mysql_num_rows($result)) {
                return false;
            }
            self::$_cache[$id] = new Country(mysql_fetch_assoc($result));
        }

        return  self::$_cache[$id];
    }

    public function setFields()
    {
        $this->fields = array(
            'id',
            'name',
        );

    }

    public function getEditFields()
    {
        return array('name',);
    }

    public function setFieldsTypes()
    {
        $this->fieldTypes = array();

    }

    public function getFkFields()
    {
        return array();
    }

    public function setTranslate()
    {
        $this->translate = array();
    }

    public function save()
    {

        parent::save();
    }

    function __toString()
    {

        return strval($this->name);
    }

}