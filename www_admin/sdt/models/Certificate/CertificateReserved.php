<?php

namespace SDT\models\Certificate;

class CertificateReserved extends \Model
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
        $sql = 'SELECT * FROM certificate_reserved WHERE   id=' . intval(
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

    public function getHeadCenter()
    {
        if ($this->head_center_id) {
            return \HeadCenter::getByID($this->head_center_id);
        }

        return new \stdClass();
    }


    static function getAllNotUsedByHC($hc_id, $test_type_id)
    {
        if (empty($hc_id) || !is_numeric($hc_id)) {
            return null;
        }

        $list = array();

        $sql = 'SELECT * FROM certificate_reserved WHERE   head_center_id =' . intval(
                $hc_id
            ).' and test_type_id =' . intval(
                $test_type_id
            ).'
            and used = 0 and invalid = 0 order by number asc';

        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return null;
        }

        while ($row = mysql_fetch_assoc($result)) {
            $list[] = $row['number'];
        }
        return $list;

    }

    static function getRangeNumbers($start,$end)
    {
        //$list=array();
        $begin = (double)ltrim($start, 0);
        $end = (double)ltrim($end,0);
        if($begin>$end) return [];
        $range = range($begin, $end);
        $list = array_map(function ($v) {
            return sprintf("%012s", $v);
        }, $range);
        return $list;
    }

    static function checkCert($hc_id,$test_type_id,$blank_num)
    {

        $errors = array();

        if (empty($blank_num)){
            $errors[] = 'Номер бланка введен неверно'; return $errors;
        }
        $sql = 'SELECT * FROM certificate_reserved WHERE test_type_id =' . intval(
                $test_type_id
            ).'
            and number = "'.mysql_real_escape_string($blank_num).'"';

        $result = mysql_query($sql);

        if (!mysql_num_rows($result)) {
            $errors[] = 'Нет такого бланка в базе';
            return $errors;
        }

        $row = mysql_fetch_assoc($result);
        if (!empty($row['invalid'])) $errors[] = 'Бланк недействителен';
        if (!empty($row['used'])) $errors[] = 'Бланк уже использован';

        if ($row['head_center_id'] != $hc_id)
        {
            $hc = \HeadCenter::getByID($row['head_center_id'])->short_name;
            $errors[]='Бланк числится за другим ГЦ ('.$hc.')';
        }

        return $errors;


    }


}