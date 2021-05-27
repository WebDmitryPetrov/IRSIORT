<?php

/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 18.02.2015
 * Time: 16:59
 */
class ApiUserData extends Model
{

    static $table = 'sdt_api_user_data';

    protected $_table = 'sdt_api_user_data';

    private static $_cache = array();

    public $id;
    public $man_id;
    public $doc_type;
    public $ext_id;

    /**
     * @param $id
     * @return bool|self
     */
    static function getByManID($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        if (!array_key_exists($id, self::$_cache)) {

            $sql = 'select * from ' . self::$table . ' where  man_id=' . intval($id);
            //   echo $sql;
            $result = mysql_query($sql);
            if (!mysql_num_rows($result)) {
                return false;
            }
            self::$_cache[$id] = new self(mysql_fetch_assoc($result));
        }

        return self::$_cache[$id];
    }


    public function setFields()
    {
        $this->fields = array('id', 'man_id', 'doc_type','ext_id',);
    }

    protected function setFieldsTypes()
    {
        return array();
    }

    public function getEditFields()
    {
        return array('id', 'man_id', 'doc_type','ext_id');
    }
}