<?php

class CenterSignings extends ArrayObject
{

    public function __construct($array = array())
    {
        parent::__construct($array);

    }

    static public function getCenterAndType($center_id, $type)
    {
        $list = new self();
        $sql = 'SELECT * 
FROM sdt_university_signing  
WHERE type=\'' . mysql_real_escape_string($type) . '\' AND deleted=0 
AND center_id=' . intval($center_id);
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new CenterSigning($row);
        }

        return $list;
    }    static public function getByCenter($center_id)
    {
        $list = new self();
        $sql = 'SELECT * 
FROM sdt_university_signing  
WHERE deleted=0 
AND center_id=' . intval($center_id);
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new CenterSigning($row);
        }

        return $list;
    }

    public function ifCaptionExist($name){
        foreach ($this as $el){
            if(trim($el->getPrint())==trim($name)){
                return true;
            }
        }
        return false;
    }

}

class CenterSigning extends Model
{
    const TYPE_APPROVE = 'approve';
    const TYPE_RESPONSIVE = 'responsive';

    protected $_table = 'sdt_university_signing';

    public $id;
    public $caption;
    public $position;
    public $deleted;
    public $center_id;
    public $type;


    static function getByID($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $sql = 'SELECT * FROM sdt_university_signing WHERE  id= ' . intval($id);
        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return new self();
        }
        $self = new self(mysql_fetch_assoc($result));

        return $self;
    }

    public function setFields()
    {
        $this->fields = array(
            'id',
            'caption',
            'position',
            'deleted',
            'center_id',
            'type',

        );
    }


    public function getEditFields()
    {
        return array(

            'caption',
            'position',


        );
    }

    public function getFkFields()
    {
        return array('center_id', 'deleted', 'type',);
    }

    public function setFieldsTypes()
    {
        $this->fieldTypes = array();
    }

    public function setTranslate()
    {
        $this->translate = array(
            'caption' => 'ФИО подписывающего',
            'position' => 'Должность',
        );
    }

    public function getPrint()
    {
        if ($this->type == self::TYPE_APPROVE) {
            return $this->position . ' ' . $this->caption;
        }

        return $this->caption;
    }

}