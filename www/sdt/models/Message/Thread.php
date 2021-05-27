<?php

/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 19.03.2015
 * Time: 18:29
 */
class Thread extends Model
{

    protected $_table = 'ms_thread';

    public $id;
    public $subject;
    public $user_created_id;
    public $created_at;
    public $isNotify;
    public $user_created_key;

    public function setFields()
    {
        $this->fields = array(
            'id',
            'subject',
            'user_created_id',
            'created_at',
            'isNotify',
            'user_created_key',
        );
    }


    public function getEditFields()
    {
        return array(
            'id',
            'subject',
            'user_created_id',
            'created_at',
            'isNotify',
            'user_created_key',

        );
    }

    public function getLastMessage($userKey)
    {
        $con = Connection::getInstance();
//        $result = array();
        $result = $con->query(
            'SELECT
 mm.id, mm.created_at,mmm.is_read
FROM ms_message mm
  left JOIN ms_message_meta mmm ON mmm.message_id = mm.id
  AND mmm.participant_key=\'' . $con->escape($userKey) . '\'
 WHERE mm.thread_id = ' . intval($this->id) . '
ORDER by  mm.created_at desc
  limit 1',
            true
        );


        return $result;

    }


    /**
     * @param $userKey
     * @return Message[]
     */
    public function getMessagesByKey($userKey)
    {
        $con = Connection::getInstance();
        $res = $con->query(
            'SELECT
 mm.*, mmm.is_read
FROM ms_message mm
  left JOIN ms_message_meta mmm ON mmm.message_id = mm.id
  AND mmm.participant_key=\'' . $con->escape($userKey) . '\'
 WHERE mm.thread_id = ' . intval($this->id) . '
ORDER by  mm.created_at asc'
        );
        $result = array();

        foreach ($res as $i) {
            $result[] = new Message($i);
        }

        return $result;
    }
}