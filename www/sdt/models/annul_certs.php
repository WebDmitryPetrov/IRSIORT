<?php

class AnnulCerts extends ArrayObject
{

    public function __construct($array = array())
    {
        parent::__construct($array);

    }


}

class AnnulCert extends Model
{
    protected $_table = 'certs_annul';

    public $id;
    public $man_id;
    public $created;
    public $user_id;
    public $blank_number;
    public $reg_number;
    public $date_annul;
    public $man_name_ru;
    public $man_surname_ru;
    public $man_name_en;
    public $man_surname_en;
    public $reason;
    public $blank_id;
    public $level_type_id;


    public function __construct($input = false)
    {
        parent::__construct($input);

    }

    static function getByID($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $sql = 'SELECT * FROM certs_annul WHERE id=\'' . mysql_real_escape_string($id) . '\'';
        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return false;
        }
        $report = new self(mysql_fetch_assoc($result));

        return $report;
    }

    static function getByManID($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $sql = 'SELECT * FROM certs_annul WHERE man_id=\'' . mysql_real_escape_string($id) . '\'';
        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return false;
        }
        $report = new self(mysql_fetch_assoc($result));

        return $report;
    }


    public function setFields()
    {
        $this->fields = array(
            'id',
            'man_id',
            'created',
            'user_id',
            'blank_id',
            'level_type_id',
            'blank_number',
            'reg_number',
            'date_annul',
            'man_name_ru',
            'man_surname_ru',
            'man_name_en',
            'man_surname_en',
            'reason',
        );

    }

    public function getEditFields()
    {
        return array(

            'man_id',
//            'created',
            'user_id',
            'blank_id',
            'level_type_id',
            'blank_number',
            'reg_number',
            'date_annul',
            'man_name_ru',
            'man_surname_ru',
            'man_name_en',
            'man_surname_en',
            'reason',
        );
    }


    public function getFkFields()
    {
        return array();
    }


    protected function setFieldsTypes()
    {
        return [];
    }
}