<?php
class HeadOrgs extends ArrayObject
{
    protected $_table;

    public function __construct($array = array())
    {
        parent::__construct($array);

    }

    static public function getAll()
    {
        $list = new Univesities();
        $sql = 'select * from sdt_head_org where deleted=0 order by caption';
        $result = mysql_query($sql) or die(mysql_error() . $sql);
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new HeadCenter($row);
        }

        return $list;
    }


}

class HeadOrg extends Model
{

    protected $_table = 'sdt_head_org';

 /** @var  string */
    public $caption;


    public function __construct($input = false)
    {
        parent::__construct($input);


    }

  public  static function getByID($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $sql = 'select * from sdt_head_org where deleted=0 and id=\'' . (int)$id . '\'';
        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return false;
        }
        $univer = new self(mysql_fetch_assoc($result));

        return $univer;
    }

    public function setFields()
    {
        $this->fields = array(
            'id',
            'caption',

        );
    }

    public function getEditFields()
    {
        return array(
            'caption',


        );
    }

    public function getFkFields()
    {
        return array();
    }

    public function setFieldsTypes()
    {
        $this->fieldTypes = array(
            'caption' => 'text',

        );
    }

    public function setTranslate()
    {
        $this->translate = array(
            'id' => 'Идентификатор',
            'caption' => 'Название',


        );
    }


    public function __toString()
    {
        return $this->caption;
    }








}