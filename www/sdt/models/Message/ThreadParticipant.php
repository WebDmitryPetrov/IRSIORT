<?php
/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 19.03.2015
 * Time: 18:30
 */

class ThreadParticipant extends Model {

    protected $_table = 'ms_thread_participant';

    public $id;
    public $thread_id;
    public $participant_key;


    public function setFields()
    {
        $this->fields = array(
            'id',
            'thread_id',
            'participant_key',

        );
    }



    public function getEditFields()
    {
        return  array(
            'id',
            'thread_id',
            'participant_key',

        );
    }
}