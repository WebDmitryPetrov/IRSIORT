<?php

class CertificateReservedList extends ArrayObject
{

    public function __construct($array = array())
    {
        parent::__construct($array);

    }

    public static function countActiveByType($type)
    {


        $sql = 'SELECT count(*) AS cc  FROM certificate_reserved
                WHERE head_center_id = ' . CURRENT_HEAD_CENTER . ' AND used=0 AND invalid=0 AND test_type_id=' . intval(
                $type
            );

        $result = mysql_query($sql) or die(mysql_error());

        //mysql_result($result, 0)æ
        return mysql_result($result, 0);
    }

    public static function getActiveByType($type)
    {
        $list = new self();

        $sql = 'SELECT cr.*, bt.id AS bt_id,bt.name AS bt_name, bt.`default` AS bt_default  FROM certificate_reserved cr
                LEFT JOIN blank_types bt ON bt.id = cr.blank_type_id AND cr.test_type_id = bt.test_level_type_id
                WHERE cr.head_center_id = ' . CURRENT_HEAD_CENTER . ' 
                AND cr.used=0 
                AND cr.invalid=0 
                AND cr.test_type_id=' . intval(
                $type
            ) . '  ORDER BY cr.id';

        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new CertificateReserved($row);
        }

        return $list;
    }


}

class CertificateReserved extends Model
{

    protected $_table = 'certificate_reserved';

    public $id;
    public $number;
    public $test_type_id;
    public $blank_type_id;
    public $head_center_id;
    public $used = 0;
    public $invalid = 0;


    static function getByID($id)
    {
        if (!is_numeric($id)) {
            return null;
        }
        $sql = 'SELECT * FROM certificate_reserved WHERE head_center_id=' . CURRENT_HEAD_CENTER . ' AND id=' . intval(
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
        $sql = "SELECT count(*) FROM certificate_reserved WHERE test_type_id = " . intval(
                $type
            ) . " AND number = '" . mysql_real_escape_string($number) . "'";
        $res = mysql_query($sql);

        $reservedCount = mysql_result($res, 0);
        if ($reservedCount) {
            return true;
        }

        $sql = "SELECT sap.id,sa.test_level_type_id FROM sdt_act_people sap
  INNER JOIN sdt_act sa  ON sap.act_id  =  sa.id

  WHERE
  sap.document='certificate'
  AND sa.deleted = 0
  AND sap.deleted = 0
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

        /* $sql = 'select * from certificate_reserved where head_center_id=' . CURRENT_HEAD_CENTER . '
          and used = 0 and invalid = 0 and test_type_id = ' . intval($test_level_type_id).' order by id limit 1';
        */
        $sql = 'SELECT cr.* FROM certificate_reserved cr
LEFT JOIN blank_types bt ON bt.id = cr.blank_type_id AND bt.test_level_type_id = cr.test_type_id
 WHERE cr.head_center_id=' . CURRENT_HEAD_CENTER . '
         AND cr.used = 0 AND cr.invalid = 0 AND cr.test_type_id = ' . intval($test_level_type_id) . ' ORDER BY bt.`default` DESC, cr.id ASC LIMIT 1';
        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return false;
        }
        $univer = new self(mysql_fetch_assoc($result));

        return $univer;
    }

    public static function getActiveByTypeAndNum($test_level_type_id, $num)
    {

        /* $sql = 'select * from certificate_reserved where head_center_id=' . CURRENT_HEAD_CENTER . '
          and used = 0 and invalid = 0 and test_type_id = ' . intval($test_level_type_id).' order by id limit 1';
        */
        $sql = 'SELECT cr.* FROM certificate_reserved cr
LEFT JOIN blank_types bt ON bt.id = cr.blank_type_id AND bt.test_level_type_id = cr.test_type_id
 WHERE cr.head_center_id=' . CURRENT_HEAD_CENTER . '
         AND cr.used = 0 AND cr.invalid = 0 
         AND cr.number = ' . mysql_real_escape_string($num) . ' 
         AND cr.test_type_id = ' . intval($test_level_type_id) . ' ORDER BY bt.`default` DESC, cr.id ASC LIMIT 1';
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
            'number',
            'test_type_id',
            'blank_type_id',
            'head_center_id',
            'used',
            'invalid',


        );
    }

    public function getEditFields()
    {
        return array(
            'id',
            'number',
            'test_type_id',
            'blank_type_id',
            'head_center_id',
            'used',
            'invalid',

        );
    }

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


}