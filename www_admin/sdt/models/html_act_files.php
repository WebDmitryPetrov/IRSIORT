<?php

/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 03.09.14
 * Time: 10:39
 */
class HTMLActFiles extends ArrayObject
{




}

class HTMLActFile extends Model
{

    protected $_table = 'html_act_files';

//    protected $primary = 'act_id';
    public $act_id;
    public $file_act_id;
    public $file_act_tabl_id;




    static function getByActID($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $sql = 'select * from html_act_files where act_id=\'' . mysql_real_escape_string($id) . '\'';
        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return false;
        }
        $univer = new HTMLActFile(mysql_fetch_assoc($result));

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
            /*'id',
            'caption',
            'deleted',*/
//            'act_id'


        );
    }

    public function getEditFields()
    {
        $result = array(
//            'caption',
//            'act_id'


        );

        return $result;
    }

    public function getFkFields()
    {
        return array(
//            'id',
            'act_id',
            'file_act_id',
            'file_act_tabl_id',
        );
    }

    public function setFieldsTypes()
    {
        $this->fieldTypes = array();
    }

    /* public function setTranslate()
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
 */
    /**
     * @return TestLevel[]
     */
    public function getTestLevels()
    {
        return TestLevels::getByType($this->id);
    }

}