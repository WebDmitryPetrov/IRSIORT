<?php

/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 20.01.2015
 * Time: 10:30
 */
class Connection
{
    private static $instance;

    private $log = array();
    private $isLogging = true;

    static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private $con;

    private function __construct()
    {
        global $db_serv, $db_user, $db_password, $db_name;

        $this->con = mysql_connect($db_serv, $db_user, $db_password) or die(debag_trac(
            "Проверте кофигурацию: Ардрес базы данных, логин и пароль.",
            mysql_error()
        ));

        mysql_select_db($db_name, $this->con) or die(debag_trac(
            "К базе подключение - успешно<br> Не заходит (не найдена, нет прав) база '.$db_name.' <br>",
            mysql_error()
        ));

        $this->encoding_win();
    }

    public function encoding_win()
    {


        $this->execute("SET CHARACTER SET cp1251");
        $this->execute("SET character_set_connection = cp1251");
        $this->execute("set character_set_results = cp1251 ");
        $this->execute("set character_set_server =  cp1251");

        return true;
    }

    public function encoding_utf()
    {

        $this->execute("SET CHARACTER SET utf8");
        $this->execute("SET character_set_connection = utf8");
        $this->execute("set character_set_results = utf8 ");
        $this->execute("set character_set_server =  utf8");

        return true;
    }

    private function log($sql)
    {
        if ($this->isLogging) {
            $this->log[] = array(
                'ts' => date('r'),
                'sql' => $sql,
            );
        }
    }

    public function query($sql, $one = false)
    {
        $this->log($sql);


        $res = mysql_query($sql, $this->con);
        if (!mysql_num_rows($res)) {
            return null;
        }
        $result = array();
        while ($row = mysql_fetch_assoc($res)) {
            $result[] = $row;
        }
        if ($one) {
            return current($result);
        }

        return $result;
    }

    public function byRow($sql)
    {
        $this->log($sql);


        $res = mysql_query($sql, $this->con);
//        if(!$res) die(var_dump($sql));
        if (!mysql_num_rows($res)) {
            return;
        }

        while ($row = mysql_fetch_assoc($res)) {
            yield $row;
        }

    }

    public function queryOne($sql)
    {
        $this->log($sql);

//die($sql);
        $res = mysql_query($sql, $this->con);
        if (!mysql_num_rows($res)) {
            return null;
        }
//        $result = array();
        return mysql_fetch_assoc($res);
    }

    public function execute($sql)
    {
        $this->log($sql);
        mysql_query($sql, $this->con);

        return mysql_affected_rows($this->con);
    }

    public function escape($value)
    {
        return mysql_real_escape_string($value);
    }

    public function print_log()
    {
        $result = '';
        foreach ($this->log as $l) {
            $result .= sprintf("%s: %s<br>\n", $l['ts'], $l['sql']);
        }

        return $result;
    }

    public function format_date($date)
    {
        return date('Y-m-d', strtotime($date));
    }
}

