<?php

/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 19.03.2015
 * Time: 18:31
 */
class MessageMeta extends Model
{

    protected $_table = 'ms_message_meta';

    public $id;
    public $message_id;
    public $participant_key;
    public $is_read=0;
    public $read_at;


    public function setFields()
    {
        $this->fields = array(
            'id',
            'message_id',
            'participant_key',
            'is_read',
            'read_at',

        );
    }


    public function getEditFields()
    {
        return array(
            'id',
            'message_id',
            'participant_key',
            'is_read',
            'read_at',

        );
    }
}