<?php

/**
 * Created by PhpStorm.
 * User: LWR
 * Date: 03.09.14
 * Time: 10:39
 */
class Re_exams extends ArrayObject
{


    static public function getAll()
    {
        $list = new Regions();
        $sql = 'select * from re_exam_info order by id';
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new Re_exam($row);
        }

        return $list;
    }


   /* public static function getAll4Form()
    {
        $list = self::getAll();
        $result = array(
            0 => 'Íå óêàçàíî'
        );
        foreach ($list as $item) {
            $result[$item->id] = $item->caption;
        }

        return $result;
    }*/


}

class Re_exam extends Model
{

    protected $_table = 're_exam_info';

    public $id;
    public $document_number;
    public $document_type;
    public $created;
    public $is_free;
    public $number;
    public $man_id;
    public $old_man_id;
    public $first_man_id;
//    public $r_num;
//    public $deleted;


    static function getByID($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $sql = 'select * from re_exam_info where id=\'' . mysql_real_escape_string($id) . '\'';
        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return false;
        }
        $univer = new Re_exam(mysql_fetch_assoc($result));

        return $univer;
    }

    static function getByManID($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $sql = 'select * from re_exam_info where man_id=\'' . mysql_real_escape_string($id) . '\'';
        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return false;
        }
        $univer = new Re_exam(mysql_fetch_assoc($result));

        return $univer;
    }

    public function delete()
    {
        $con = Connection::getInstance();
        $sql = 'delete from ' . $this->_table . ' where id = ' . $this->id;

        return $con->execute($sql);


    }




    public function setFields()
    {
        $this->fields = array(
            'id',
            'document_number',
            'document_type',
            'is_free',
            'number',
            'man_id',
            'old_man_id',
            'first_man_id',



        );
    }

    public function getEditFields()
    {
        $result = array(
            'document_number',
            'document_type',
            'is_free',
            'number',
            'man_id',
            'old_man_id',
            'first_man_id',


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
            'document_number' => 'Íîìåğ äîêóìåíòà',
            'document_type' => 'Òèï äîêóìåíòà',
            'created' => 'Ñîçäàí',
            'is_free' => 'ßâëÿåòñÿ áåñïëàòíîé',
            'number' => 'Íîìåğ ïåğåñäà÷è',
            'man_id' => 'id òåñòèğóåìîãî',
            'old_man_id' => 'ñòàğûé id òåñòèğóåìîãî',
//            'r_num' => 'Êîä ÎÊÒÌÎ',

        );


    }


   /* public function __toString()
    {
        return $this->caption;

    }*/

    /**
     * @return TestLevel[]
     */
 /*   public function getTestLevels()
    {
        return TestLevels::getByType($this->id);
    }*/

}