<?php

/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 19.03.2015
 * Time: 18:30
 */
class Message extends Model
{

    protected $_table = 'ms_message';

    public $id;
    public $thread_id;
    public $sender_key;
    public $body;
    public $created_at;


    public function setFields()
    {
        $this->fields = array(
            'id',
            'thread_id',
            'sender_key',
            'body',
            'created_at',

        );
    }


    public function getEditFields()
    {
        return array(
            'id',
            'thread_id',
            'sender_key',
            'body',
            'created_at',

        );
    }

    public function isRead()
    {
        if (array_key_exists('is_read', $this->values)) {
            return $this->values['is_read'];
        }

        return true;
    }


    public function markIsRead($currentKey)
    {
        if (array_key_exists('is_read', $this->values)) {
            if (!$this->values['is_read']) {
                $con = Connection::getInstance();
                $sql = "update ms_message_meta set
                    is_read = 1,
                    read_at = '" . $con->escape(date('Y-m-d H:i:s')) . "'
                    where
                        participant_key = '" . $con->escape($currentKey) . "'
                        and message_id = " . intval($this->id) . "
                    ";
                $con->execute($sql);
            }
        }
    }
}