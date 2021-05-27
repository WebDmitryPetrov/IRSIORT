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

        );
    }


}