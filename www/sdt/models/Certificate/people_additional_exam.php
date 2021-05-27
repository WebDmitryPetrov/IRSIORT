<?php

class PeopleAdditionalExam extends ArrayObject
{


}

class ManAdditionalExam extends Model
{

    protected $_table = 'people_additional_exam';
//    protected $primary  = 'man_id';
    public $man_id;
    public $old_blank_scan;
    public $old_blank_number;
    public $approve;
    public $user_approved;
    public $previous_man_id;
    public $cert_exists;
    public $id;

    public $new = 0;


    static function getByID($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $con = Connection::getInstance();
        $sql = 'select * from people_additional_exam where id=\'' . $con->escape($id) . '\'';
        $result = $con->query($sql, true);
        if (!$result) {
            return null;
        }
        $univer = new ManAdditionalExam($result);

        return $univer;
    }

    static function getByManID($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $con = Connection::getInstance();
        $sql = 'select * from people_additional_exam where man_id=\'' . $con->escape($id) . '\'';
        $result = $con->query($sql, true);
        if (!$result) {
            return null;
        }
        $univer = new ManAdditionalExam($result);

        return $univer;
    }


    public function setFields()
    {
        $this->fields = array(

            'man_id',
            'id',
            'old_blank_scan',
            'old_blank_number',
            'approve',
            'user_approved',
            'previous_man_id',
            'cert_exists',

        );
    }

    public function getEditFields()
    {
        return array(
            'old_blank_scan',
            'old_blank_number',
            'approve',
            'user_approved',
            'previous_man_id',
            'cert_exists',

        );
    }

    public function setFieldsTypes()
    {
        $this->fieldTypes = array();

    }

    public function getFkFields()
    {
        return array(
            'man_id',

        );
    }

    public function setTranslate()
    {
        $this->translate = array();
    }


    public function save()
    {

        parent::save();
    }


    public function getFileOldBlankScan()
    {
        if ($this->old_blank_scan) {
            return File::getByID($this->old_blank_scan);
        }

        return false;
    }


    public function delete()
    {
        $con = Connection::getInstance();
        $sql = 'delete from ' . $this->_table . ' where id = ' . $this->id;

        return $con->execute($sql);


    }

    public function approve()
    {
        $this->approve = 1;
        $this->user_approved = $_SESSION['u_id'];
        $this->save();
    }


    public function getPrevious()
    {
        if (!$this->previous_man_id) {
            return null;
        }

        return ActMan::getByID($this->previous_man_id);
    }


}