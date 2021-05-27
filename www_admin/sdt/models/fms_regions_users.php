<?php

/**
 * Created by PhpStorm.
 * User: LWR
 * Date: 03.09.14
 * Time: 10:39
 */
class FmsRegionsUsers extends ArrayObject
{


    /*static public function getAll()
    {
        $list = new FmsRegions();
        $sql = 'select * from fms_regions_users order by id_user';
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new FmsRegionsUser($row);
        }

        return $list;
    }*/
    static public function getAll($u_id=null)
    {
        $list = new Users();
        //$sql = 'select * from tb_users where head_id=' . CURRENT_HEAD_CENTER;
//        $sql = 'select * from tb_users';
        $sql = 'select * from tb_users as t1 join fms_regions_users as t2 on t1.u_id=t2.id_user';
        if ($u_id)
        {
            $user_region=FmsRegionUser::getByUser($u_id)->id_region;
            $sql.=' where t2.id_region='.$user_region;
        }


        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new User($row);
        }
//die(var_dump($list));
        return $list;
    }

}

class FmsRegionUser extends Model
{

    protected $_table = 'fms_regions_users';

    protected $primary = 'id_user';
    public $id_user;
    public $id_region;
    public $new=0;
//    public $caption;
//    public $deleted;


    static function getByUser($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $sql = 'select * from fms_regions_users where id_user=\'' . mysql_real_escape_string($id) . '\'';
        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return false;
        }
        $univer = new FmsRegionUser(mysql_fetch_assoc($result));

        return $univer;
    }
    static function getByRegion($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $sql = 'select * from fms_regions_users where id_region=\'' . mysql_real_escape_string($id) . '\'';
        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return false;
        }
        $univer = new FmsRegionUser(mysql_fetch_assoc($result));

        return $univer;
    }

    public function setFields()
    {
        $this->fields = array(
            'id_user',
            'id_region',
//            'caption',
//            'deleted',


        );
    }

    public function getEditFields()
    {
        $result = array(
            'id_region',


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
//            'id' => 'Идентификатор',
//            'caption' => 'Название',


        );


    }


    public function save()
    {
        $pk = $this->primary;
        if (!is_null($this->$pk) && !$this->new) {

            return $this->update();
        } else {
            return $this->insert();
        }
    }

    protected function insert()
    {
        $pk=$this->getPrimaryKey();

        $saveFields = $this->getSaveFields();
        if($this->new){
            $saveFields[]=$pk;
        }
        $sql = 'insert into ' . $this->_table . ' (' . implode(', ', $saveFields) . ') values ';
        $addFilds = array();

        foreach ($saveFields as $field) {

            $addFilds[] = "'" . mysql_real_escape_string($this->$field) . "'";
        }
        $sql .= '(' . implode(', ', $addFilds) . ')';
        //var_dump($sql);
        mysql_query($sql) or die(mysql_error());
        $id = mysql_insert_id();

        $this->$pk = $id;

        return $this->$pk;

    }


}