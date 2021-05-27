<?php
class Countries extends ArrayObject
{

    public function __construct($array = array())
    {
        parent::__construct($array);

    }

    /**
     * @return array|Countries|Country[]
     */
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
            0=>'Не указано'
        );
        foreach ($list as $item) {
            $result[$item->id] = $item->name;
        }

        return $result;
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

    static function getByID($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $sql = 'select * from country where id=\'' . mysql_real_escape_string($id) . '\'';
        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return false;
        }
        $univer = new Country(mysql_fetch_assoc($result));

        return $univer;
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

    public function __toString()
    {
        return (string)$this->name;
    }

}