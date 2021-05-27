<?php

/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 08.12.2014
 * Time: 13:11
 */
class CertificateUsed extends Model
{


    protected $_table = 'certificate_used';
    public $id;
    public $fio;
    public $number;
    public $level_id;
    public $data;
    public $timest;
    public $user_id;
    public $blank_id;

    public static function add(ActMan $man, $data = array())
    {
        $o = new self();

        $o->fio = $man->surname_rus . ' ' . $man->name_rus;
        $o->user_id = $man->id;
        $o->level_id = $man->getLevel()->id;
        $o->number = $man->blank_number;
        $o->blank_id = $man->blank_id;
//        $manData = array(
//            'su'
//        )

        $userData =array(
//        'fio'=>$man->surname_rus.' '.$man->name_rus,
        'fio_lat'=>$man->surname_lat.' '.$man->name_lat,

        );
        $o->data = array(
            'user' => $userData,
            'level' => $man->getLevel()->caption,
            'data' => $data,

        );

        return $o->save();
    }

    public function setFields()
    {
        $this->fields = array(
            'id',
            'fio',
            'number',
            'level_id',
            'data',
            'timest',
            'user_id',
            'blank_id',

        );
    }

    protected function setFieldsTypes()
    {
        $this->fieldTypes = array();
    }

    protected function  beforeSave()
    {
        return array(
            'data' => 'encodeData',
        );
    }

    protected function encodeData()
    {
        $data = recursive_utf_encode($this->data);

        return json_encode($data);
    }

    public function getEditFields()
    {
        return array(

            'fio',
            'number',
            'level_id',
            'data',
            'user_id',
            'blank_id',

        );
    }


}