<?php

/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 08.12.2014
 * Time: 13:11
 */
class CertificateInvalid extends Model
{


    protected $_table = 'certificate_invalid';
    public $id;

    public $certificate_id;

    public $user_id;
    public $number;
    public $test_type_id;

    public $reason;
    public $blank_date;

    public $object_type;

    public static function add(ActMan $man, CertificateReserved $certificateReserved = null, $reason = null)
    {
        $o = new self();


        $o->user_id = $man->id;
        if (!empty($certificateReserved)) {
            $o->certificate_id = $certificateReserved->id;
        }
        $o->number = $man->blank_number;
        $o->test_type_id = $man->getAct()->test_level_type_id;
        $o->reason = $reason;
        $o->blank_date = $man->blank_date;
        $o->object_type = 'man';

        return $o->save();


    }

    public static function dubl_add(DublActMan $man, CertificateReserved $certificateReserved = null, $reason = null)
    {
        $o = new self();

        $old_man = ActMan::getByID($man->old_man_id);

        $o->user_id = $man->id;
        if (!empty($certificateReserved)) {
            $o->certificate_id = $certificateReserved->id;
        }
        $o->number = $certificateReserved->number;
        $o->test_type_id = $old_man->getAct()->test_level_type_id;
        $o->reason = $reason;
        $o->blank_date = $old_man->blank_date;
        $o->object_type = 'dubl';

        return $o->save();


    }

    public static function isCertificateInvalid($number, $type)
    {
        $sql = "select * from certificate_invalid WHERE test_type_id = " . intval(
                $type
            ) . " AND number = '" . mysql_real_escape_string($number) . "'";


        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return null;
        }
        return true;
    }

    public static function add_cert_reserved(CertificateReserved $certificateReserved = null, $reason = null)
    {
        $o = new self();

        if (empty($certificateReserved)) return false;


            $o->certificate_id = $certificateReserved->id;
            $o->user_id = $certificateReserved->id;

        $o->number = $certificateReserved->number;
        $o->test_type_id = $certificateReserved->test_type_id;
        $o->reason = $reason;

        $o->object_type = 'cert_reserved';

        return $o->save();


    }


    public function setFields()
    {
        $this->fields = array(
            'id',
            'user_id',
            'certificate_id',
            'number',
            'test_type_id',
            'reason',
            'blank_date',
            'object_type',


        );
    }

    protected function setFieldsTypes()
    {
        $this->fieldTypes = array();
    }


    public function getEditFields()
    {
        return array(

            'user_id',
            'certificate_id',
            'number',
            'test_type_id',
            'reason',
            'blank_date',
            'object_type',

        );
    }


}