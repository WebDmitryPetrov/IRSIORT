<?php

class HeadCenters extends ArrayObject
{
    protected $_table;

    public function __construct($array = array())
    {
        parent::__construct($array);

    }

    static public function getAll()
    {
        $list = new Univesities();
        $sql = 'SELECT * FROM sdt_head_center WHERE deleted=0 ORDER BY name';
        $result = mysql_query($sql) or die(mysql_error() . $sql);
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new HeadCenter($row);
        }

        return $list;
    }

    public static function getHeadOrgs()
    {
        $list = array();
    }

}

class HeadCenter extends Model
{

    private static $cache = array();
    public $id;
    public $name;
    public $short_name;
    public $rector;
    public $form;
    public $legal_address;
    public $contact_phone;
    public $contact_fax;
    public $contact_email;
    public $contact_other;
    public $responsible_person;
    public $responsible_person_phone;
    public $responsible_person_email;

    public $city;
    public $comments;
    public $country_id;
    public $user_password;
    public $horg_id;
    public $pfur_api;
    public $bso_id;
    protected $_table = 'sdt_head_center';

    public function __construct($input = false)
    {
        parent::__construct($input);


    }

    /**
     * @param $id
     * @return bool|self
     */
    static function getByID($id)
    {
        if (!is_numeric($id)) {
            return false;
        }

        $id = (int)$id;
        if (!array_key_exists($id, self::$cache)) {
            $sql = 'SELECT * FROM sdt_head_center WHERE  id=\'' . $id . '\'';
            $result = mysql_query($sql);
            if (!mysql_num_rows($result)) {
                return false;
            }
            self::$cache[$id] = new HeadCenter(mysql_fetch_assoc($result));
        }

        return self::$cache[$id];
    }

    static function getNameByActID($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $sql = 'SELECT hc.* FROM sdt_head_center AS hc
                JOIN sdt_university AS u ON hc.id=u.head_id
                JOIN sdt_act AS a ON u.id=a.university_id
                WHERE a.id=\'' . mysql_real_escape_string($id) . '\'';
        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return false;
        }
        $univer = new HeadCenter(mysql_fetch_assoc($result));
        if (!empty($univer->short_name)) return $univer->short_name;
        else return $univer->name;

    }

    public static function getNameByCertificateID($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $sql = 'SELECT hc.* FROM sdt_head_center AS hc
                JOIN certificate_reserved AS cr ON hc.id=cr.head_center_id
              
                WHERE cr.id=\'' . intval($id) . '\'';
        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return false;
        }
        $univer = new HeadCenter(mysql_fetch_assoc($result));
        if (!empty($univer->short_name)) return $univer->short_name;
        else return $univer->name;
    }

    public static function getOrgName($head_id)
    {

        $sql = "SELECT sho.captoin FROM sdt_head_center shc
        LEFT JOIN sdt_head_org sho ON sho.id=shc.horg_id
        WHERE shc.id=" . $head_id;

        $result = mysql_query($sql);

        return mysql_result($result, 0, 0);
    }

    public function setFields()
    {
        $this->fields = array(
            'id',
            'name',
            'short_name',
            'rector',
            'form',
            'legal_address',
            'contact_phone',
            'contact_fax',
            'contact_email',
            'contact_other',
            'responsible_person',
            'responsible_person_phone',
            'responsible_person_email',
            'city',
            'comments',
            'country_id',
            'horg_id',
            'pfur_api',
            'bso_id',
        );
    }

    public function getEditFields()
    {
        return array(
            'name',
            'short_name',
            'rector',
            'form',
            'legal_address',
            'contact_phone',
            'contact_fax',
            'contact_email',
            'contact_other',
            'responsible_person',
            'responsible_person_phone',
            'responsible_person_email',
            'city',
            'comments',
            'country_id',

        );
    }

    public function getFkFields()
    {
        return array('horg_id', 'bso_id',);
    }

    public function setFieldsTypes()
    {
        $this->fieldTypes = array(
            'name' => 'text',
            'legal_address' => 'text',
            'contact_other' => 'text',
            'comments' => 'text',
            'country_id' => 'select'
        );
    }

    public function setTranslate()
    {
        $this->translate = array(
            'id' => 'Идентификатор',
            'name' => 'Название',
            'short_name' => 'Сокращенное  название',
            'rector' => 'Ректор',
            'form' => 'Правовая форма',
            'legal_address' => 'Юридический адрес',
            'contact_phone' => 'Телефон',
            'contact_fax' => 'Факс',
            'contact_email' => 'Email',
            'contact_other' => 'Дополнительные контакты',
            'responsible_person' => 'Ответственный по системе',
            'responsible_person_phone' => 'Телефон ответственного по системе',
            'responsible_person_email' => 'Email ответственного по системе',
            'comments' => 'Комментарии',
            'city' => 'Город',
            'country_id' => 'Страна'


        );
    }

    public static function getHorgID($hc_id)
    {
        $q = 'SELECT horg_id FROM sdt_head_center WHERE  id = %d';
        $C = Connection::getInstance();
        $res = $C->queryOne(sprintf($q, intval($hc_id)));

        if (!$res) return null;

        return $res['horg_id'];

    }

    public static function isPfurCenterByID($hcId)
    {
        return 1 == self::getHorgID($hcId);
    }

    public function __toString()
    {
        return (string)$this->name;
    }

    public function getCountry()
    {
        if ($this->country_id) {
            return Country::getByID($this->country_id);
        }

        return new stdClass();
    }

    public function delete()
    {
        return parent::delete();
    }

    protected function setAvailableValues()
    {
        $countries = Countries::getAll4Form();


        $this->availableValues = array(
            'country_id' => $countries,
        );

    }

}