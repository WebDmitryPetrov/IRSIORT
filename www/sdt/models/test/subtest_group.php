<?php

class SubTestGroups extends ArrayObject
{

    static public function getAll()
    {
        $list = new self();
        $sql = 'select * from  ' . SubTestGroup::TABLE . '  order by caption';
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new SubTestGroup($row);
        }

        return $list;
    }
}

class SubTestGroup extends Model
{
    const TABLE = 'subtest_group';
    private static $_cache = array();
    public $id;
    public $caption;
    public $formula;
    public $pass_score;
    protected $_table = self::TABLE;

   /* public function __construct($input = false)
    {
        parent::__construct($input);

    }*/

    static function getByID($id)
    {

        if (!is_numeric($id)) {
            return false;
        }
        if (!array_key_exists($id, self::$_cache)) {
            $sql = 'select * from ' . self::TABLE . ' where id=\'' . mysql_real_escape_string($id) . '\'';
            $result = mysql_query($sql);
            if (!mysql_num_rows($result)) {
                return false;
            }
            self::$_cache[$id] = new self(mysql_fetch_assoc($result));
        }

        return self::$_cache[$id];
    }

    public function setFields()
    {
        $this->fields = array(
            'id',
            'caption',
            'formula',
            'pass_score',
        );

    }

    public function getEditFields()
    {
        return array(
            'caption',
            'formula',
            'pass_score',
        );
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

    /*public function save()
    {

        parent::save();
    }*/

    function __toString()
    {

        return strval($this->caption);
    }

}