<?php

class BlankTypes extends ArrayObject
{

    public function __construct($array = array())
    {
        parent::__construct($array);

    }

    public static function switchBlanks()
    {

        $HC = array();
        foreach (HeadCenters::getAll() as $HCS)
        {
            if ($HCS->horg_id == 1) $HC[]=intval($HCS->id);
        }
        if (in_array(CURRENT_HEAD_CENTER, $HC))
            return true;
        else return false;
    }

    public static function countActiveByTypeAndBlankType($type, $blank_type)
    {

        $sql = 'select count(*) as cc  from certificate_reserved
                where head_center_id = ' . CURRENT_HEAD_CENTER . ' and used=0 and invalid=0 and test_type_id=' . intval(
                $type
            ) .' and blank_type_id ='.intval($blank_type);
//die($sql);
        $result = mysql_query($sql) or die(mysql_error());

        //mysql_result($result, 0)æ
        return mysql_result($result, 0);
    }


    public static function getBlankTypesByType($type)
    {

        if (!self::switchBlanks()) return 1;

        $list = new self();

        $sql = 'select *  from blank_types
                where visible=1 and test_level_type_id=' . intval(
                $type
            ) ;

        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new BlankType($row);
        }

        return $list;
    }

    /*public static function getActiveByType($type)
    {
        $list = new self();

        $sql = 'select * from certificate_reserved
                where head_center_id = ' . CURRENT_HEAD_CENTER . ' and used=0 and invalid=0 and test_type_id=' . intval(
                $type
            ). '  order by id';

        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new CertificateReserved($row);
        }

        return $list;
    }*/


}

class BlankType extends Model
{

    protected $_table = 'blank_types';

    public $id;
    public $test_level_type_id;
    public $name;
    public $default;
    public $visible;



    static function getBlankType($type, $num)
    {
        if (!is_numeric($type)) {
            return null;
        }

        if (!BlankTypes::switchBlanks()) return 1;

        $sql = 'select bt.* from certificate_reserved  cr
left join blank_types bt on bt.test_level_type_id = cr.test_type_id and bt.id = cr.blank_type_id

where cr.head_center_id=' . CURRENT_HEAD_CENTER . ' and cr.test_type_id=' . intval(
                $type
            ).' and cr.number="'. $num.'"';
        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return null;
        }
        $univer = new self(mysql_fetch_assoc($result));

        return $univer;
    }

    static function getDefaultBlankType($test_type)
    {
        if (!is_numeric($test_type)) {
            return null;
        }

        if (!BlankTypes::switchBlanks()) return 1;

        $sql = 'select id from blank_types where test_level_type_id=' . intval(
                $test_type) . ' and `default` = 1';
        $result = mysql_query($sql);
        //die($sql);
        if (!mysql_num_rows($result)) {
            return 1;
        }
        $univer = mysql_result($result, 0);


        return $univer;

    }


    /*static function getByID($id)
    {
        if (!is_numeric($id)) {
            return null;
        }
        $sql = 'select * from certificate_reserved where head_center_id=' . CURRENT_HEAD_CENTER . ' and id=' . intval(
                $id
            );
        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return null;
        }
        $univer = new self(mysql_fetch_assoc($result));

        return $univer;
    }

    static function getByBlankId($id)
    {
        if (!is_numeric($id)) {
            return null;
        }
        $sql = 'select * from certificate_reserved where head_center_id=' . CURRENT_HEAD_CENTER . ' and id=' . intval(
                $id
            );
        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return null;
        }
        $univer = new self(mysql_fetch_assoc($result));

        return $univer;
    }

    public static function ifExist($number, $type)
    {
        $sql = "select count(*) from certificate_reserved where test_type_id = " . intval(
                $type
            ) . " and number = '" . mysql_real_escape_string($number) . "'";
        $res = mysql_query($sql);

        $reservedCount = mysql_result($res, 0);
        if ($reservedCount) {
            return true;
        }

        $sql = "SELECT sap.id,sa.test_level_type_id FROM sdt_act_people sap
  INNER JOIN sdt_act sa  on sap.act_id  =  sa.id

  WHERE
  sap.document='certificate'
  and sa.deleted = 0
  and sap.deleted = 0
  AND sap.blank_number='" . mysql_real_escape_string($number) . "'
  AND sa.test_level_type_id = " . intval(
                $type
            ) . "
  LIMIT 1";

        $res = mysql_query($sql);

        $mnr = mysql_num_rows($res);
//var_dump($mnr);
        return $mnr;


    }

    public static function getActiveByType($test_level_type_id)
    {

        $sql = 'select * from certificate_reserved where head_center_id=' . CURRENT_HEAD_CENTER . '
         and used = 0 and invalid = 0 and test_type_id = ' . intval($test_level_type_id).' order by id limit 1';
        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return false;
        }
        $univer = new self(mysql_fetch_assoc($result));

        return $univer;
    }
*/

    public function setFields()
    {
        $this->fields = array(
            'id',
            'test_level_type_id',
            'name',
            'default',
            'visible',



        );
    }

    public function getEditFields()
    {
        return array(
            'id',
            'test_level_type_id',
            'name',
            'default',
            'visible',


        );
    }
/*
    public function delete()
    {
        $pk = $this->getPrimaryKey();
        $sql = 'update  ' . $this->_table . ' set invalid = 1  where ' . $pk . '=' . $this->$pk;
        mysql_query($sql);

        return mysql_affected_rows();
    }

    public function setFieldsTypes()
    {
        $this->fieldTypes = array(
//            'testing_date' => 'date',
//            'invoice_date' => 'date',
//            'birth_date' => 'date',
//            'blank_date' => 'date',
//            'valid_till' => 'date',
//            'comment' => 'text'

        );

    }

*/
}