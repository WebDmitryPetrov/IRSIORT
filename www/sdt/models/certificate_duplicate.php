<?php

/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 15.01.2015
 * Time: 16:45
 */
class CertificateDuplicates extends ArrayObject
{


    static public function getAllByUserID($id)
    {
        $list = new CertificateDuplicates();
        $sql = 'select * from certificate_duplicate where user_id=\'' . mysql_real_escape_string($id) . '\' order by certificate_issue_date asc';
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new CertificateDuplicate($row);
        }

        return $list;
    }

}

class CertificateDuplicate extends Model
{

    protected $_table='certificate_duplicate';
    public $id;
    public $surname_rus;
    public $name_rus;
    public $surname_lat;
    public $name_lat;
    public $user_id;
    public $certificate_id;
    public $certificate_number;
    public $certificate_issue_date;
    public $certificate_print_date;

    public $request_file_id;
    public $passport_file_id;
    public $personal_data_changed;
    public $cert_signer;


    static $_cache=array();

    static function getByUserID($id)
    {

        if (!is_numeric($id)) {
            return false;
        }
        if (!array_key_exists($id, self::$_cache)) {
//            $sql = 'select * from country where id=\'' . mysql_real_escape_string($id) . '\'';
            $sql = 'select * from certificate_duplicate
                where user_id=\'' . Connection::getInstance()->escape($id) . '\' order by id desc limit 1';


            $result =   Connection::getInstance()->query($sql,true);
//            (var_dump($result));
            self::$_cache[$id] = new CertificateDuplicate($result);
        }

        return self::$_cache[$id];
    }


    public static function checkForDuplicates(ActMan $man)
    {
        $act_state=Act::getActState($man->act_id);

//        if ($act_state==Act::STATE_ARCHIVE)
//        {
            $duplicate_data=self::getByUserID($man->id);


            $man->duplicate=$duplicate_data;

//        }

        return $man;
    }









    /*public function DuplicateData(ActMan $man)
    {
        $act_state=Act::getByID($man->act_id)->state;
       // die($act_state);
        if ($act_state==Act::STATE_ARCHIVE)
        {
            $duplicate_data=self::getByUserID($man->id);
            if ($duplicate_data)
            {
                $man->surname_lat=$duplicate_data->surname_lat;
                $man->name_lat=$duplicate_data->name_lat;
                $man->surname_rus=$duplicate_data->surname_rus;
                $man->name_rus=$duplicate_data->name_rus;
                $man->blank_number=$duplicate_data->certificate_number;
            }

        }

        return $man;
    }*/

    public function setFields()
    {
        $this->fields = array(
            'id',
            'surname_rus',
            'name_rus',
            'surname_lat',
            'name_lat',
            'user_id',
            'certificate_id',
            'certificate_number',
            'certificate_issue_date',
            'certificate_print_date',
            'request_file_id',
            'passport_file_id',
            'personal_data_changed',
            'cert_signer',
        );
    }

    public function getCenter()
    {
        $c = Connection::getInstance();
        $center_id= $c->queryOne('select da.center_id from dubl_act_man dam
left join dubl_act da
on da.id = dam.act_id
 where dam.dubl_cert_id = '.$this->certificate_id);
        
        if(!$center_id) return false;
        return University::getByID($center_id['center_id'],true);
    }

    protected function setFieldsTypes()
    {
        // TODO: Implement setFieldsTypes() method.
    }

    public function getEditFields()
    {
        return array(
//            'id',
            'surname_rus',
            'name_rus',
            'surname_lat',
            'name_lat',
            'user_id',
            'certificate_id',
            'certificate_number',
            'certificate_issue_date',
            'certificate_print_date',
            'request_file_id',
            'passport_file_id',
            'personal_data_changed',
            'cert_signer',
        );
    }

    public function getRequestFile()
    {
        if ($this->request_file_id) {
            return File::getByID($this->request_file_id);
        }

        return false;
    }

    public function getPassportFile()
    {
        if ($this->passport_file_id) {
            return File::getByID($this->passport_file_id);
        }

        return false;
    }
}