<?php

/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 23.03.2015
 * Time: 10:41
 */
class Messaging
{
    private static $instance;

    static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    protected function __construct()
    {

    }


    const PREFIX_LOCAL = 'l_';
    const PREFIX_HEAD = 'h_';

    public function NewMessage($from_key, array $to, $subject, $body, $isNotify = false)
    {
        $thread = new Thread();
        $thread->created_at = date('Y-m-d H:i:s');
        $participiants = array();
        $from = new ThreadParticipant();
        $from->participant_key = $from_key;

        $participiants[] = $from;

        $messageTo = array();
        foreach ($to as $item) {
            $t = new ThreadParticipant();
            $t->participant_key = $item;
            $participiants[] = $t;


            $m = new MessageMeta();
            $m->participant_key = $item;
            $messageTo[] = $m;
        }

        $thread->isNotify = $isNotify;
        $thread->subject = $subject;
        $thread->user_created_id = $_SESSION['u_id'];
        $thread->user_created_key = $from_key;
        $thread->save();
        foreach ($participiants as $p) {
            $p->thread_id = $thread->id;
            $p->save();
        }

        $message = new Message();
        $message->created_at = date('Y-m-d H:i:s');
        $message->thread_id = $thread->id;
        $message->sender_key = $from_key;
        $message->body = $body;
        $message->save();

        foreach ($messageTo as $m) {
            $m->message_id = $message->id;
            $m->save();
        }


    }

    public function getCurrentKey()
    {
        $r = Roles::getInstance();
        if ($r->userHasRole(Roles::ROLE_CENTER_EXTERNAL) || $r->userHasRole(Roles::ROLE_CENTER_EXTERNAL_API)) {
            return $this->getLocalKey($_SESSION['univer_id']);
        } else {
            return $this->getHeadKey();
        }
    }

    public function getLocalKey($item)
    {
        return self::PREFIX_LOCAL . $item;
    }

    public function getHeadKey()
    {
        return self::PREFIX_HEAD . CURRENT_HEAD_CENTER;
    }

    public function getThreads($key)
    {
        $connection = Connection::getInstance();
        $res = $connection->query(
            'SELECT
  mt.*
FROM ms_thread mt
  JOIN ms_thread_participant mtp ON mtp.thread_id = mt.id
  WHERE mtp.participant_key = \'' . $connection->escape($key) . '\'
  order BY mt.created_at desc'
        );
        $result = array();
        if ($res) {
            foreach ($res as $i) {
                $result[] = new Thread($i);
            }
        }

        return $result;
    }

    /**
     * @param $id
     * @param $key
     * @return null|Thread
     */
    public function getThread($id, $key)
    {
        $connection = Connection::getInstance();
        $res = $connection->query(
            'SELECT
  mt.*
FROM ms_thread mt
  JOIN ms_thread_participant mtp ON mtp.thread_id = mt.id
  WHERE mt.id = ' . intval($id) . ' and mtp.participant_key = \'' . $connection->escape($key) . '\'
  ',
            true
        );

        if (!$res) {
            return null;
        }

        return new Thread($res);
    }

    public function getCaptionByKey($key)
    {
        if (strpos($key, self::PREFIX_HEAD) === 0) {
            list(, $id) = explode('_', $key);
            $hc = HeadCenter::getByID($id);

            return $hc->short_name;
        }

        if (strpos($key, self::PREFIX_LOCAL) === 0) {
            list(, $id) = explode('_', $key);
            $hc = University::getByID($id);

            return $hc->short_name;
        }

        return 'Неизвестен';
    }
}