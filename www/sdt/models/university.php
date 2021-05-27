<?php
require_once __DIR__ . '/collections/Univesities.php';

class University extends Model
{
    private static $cache = array();
    public $user_login;
    public $user_id;
    public $id;
    /** @var string */
    public $name;
    public $short_name;
    public $form;
    public $legal_address;
    public $contact_phone;
    public $contact_fax;
    public $contact_email;
    public $contact_other;
    public $responsible_person;
    public $parent_id;
    public $bank;
    public $city;
    public $rc;
    public $lc;
    public $kc;
    public $bik;
    public $inn;
    public $kpp;
    public $okato;
    public $okpo;
    public $comments;
    public $rector;
    public $country_id;
    public $region_id;
    public $head_id;
    public $is_price_change;
    public $is_head;
    public $api_enabled;
    public $pfur_api;
    public $deleted;
    public $print_invoice_quoute;
    public $is_old_act = 0;

    public $user_password;
    protected $_table = 'sdt_university';

    public function __construct($input = false, $skipRestrict = false)
    {
        parent::__construct($input);

        if ($this->id) {
            $this->user_login = 'center_' . $this->id;
            if (!$skipRestrict) {
                $this->checkRights();
            }
        }
    }

    protected function checkRights()
    {
        $C = AbstractController::getInstance();
        $restrictions = $C->getUniversityRestrictionArray();
//var_dump($restrictions);
        if (is_array($restrictions)) {
            if (!in_array($this->id, $restrictions)) {
//                var_dump($this->id,$restrictions);die;
                $C->redirectAccessRestricted();
            }
        }
    }

    public static function getCaption($parent_id)
    {
        $C = Connection::getInstance();
        $sql = sprintf('SELECT name FROM sdt_university WHERE id = %d', (int)$parent_id);
        $row = $C->queryOne($sql);
        if (!$row) return null;
        return $row['name'];
    }

    public static function getByActID($id)
    {
        $sql = 'SELECT su.* FROM sdt_university su
 LEFT JOIN sdt_act sa ON sa.university_id = su.id
 WHERE
        
          sa.id=\'' . mysql_real_escape_string($id) . '\'';
        $result = mysql_query($sql) or die(mysql_error());
        if (!mysql_num_rows($result)) {
            return false;
        }
//        die(var_dump($result));
        return new University(mysql_fetch_assoc($result), true);
    }

    public static function isHead($act_id)
    {
        $sql = 'SELECT 
IF( (t2.parent_id IS NULL OR t2.parent_id = 0), t2.is_head, t3.is_head) AS is_head

FROM sdt_act AS t1
LEFT JOIN sdt_university AS t2 ON t1.university_id=t2.id
LEFT JOIN sdt_university AS t3 ON t2.parent_id = t3.id
WHERE t1.id=' . $act_id;
        $result = mysql_query($sql) or die(mysql_error());

        if (!mysql_num_rows($result)) return false;
        $row = mysql_fetch_assoc($result);

        return !!$row['is_head'];
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
            'bank',
            'city',
            'rc',
            'lc',
            'kc',
            'bik',
            'inn',
            'kpp',
            'okato',
            'okpo',
            'comments',
            'user_id',
            'country_id',
            'region_id',
            'head_id',
            'print_invoice_quoute',
            'is_price_change',
            'is_head',
            'api_enabled',
            'is_old_act',
            'parent_id',
            'pfur_api',
            'deleted',
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
            'bank',
            'city',
            'rc',
            'lc',
            'kc',
            'bik',
            'inn',
            'kpp',
            'okato',
            'okpo',
            'comments',
            'country_id',
            'region_id',
            'print_invoice_quoute',
            'is_price_change',
            'is_head',
            'is_old_act',

        );
    }

    public function getFkFields()
    {
        return array(
            'user_id',
            'head_id',
            'parent_id',
        );
    }

    public function setFieldsTypes()
    {
        $this->fieldTypes = array(
            'name' => 'text',
            'legal_address' => 'text',
            'contact_other' => 'text',
            'comments' => 'text',
            'country_id' => 'select',
            'region_id' => 'select',
            'is_price_change' => 'checkbox',
            'is_head' => 'checkbox',
            'print_invoice_quoute' => 'checkbox',
            'is_old_act' => 'checkbox'
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
            'responsible_person' => 'Ответственный за проведение тестирования',
            'comments' => 'Комментарии',
            'bank' => 'Банк',
            'city' => 'Город',
            'rc' => 'Расчетный счет',
            'lc' => 'Лицевой счет',
            'kc' => 'Корреспондентский счет',
            'bik' => 'БИК',
            'inn' => 'ИНН',
            'kpp' => 'КПП',
            'okato' => 'Код по ОКАТО',
            'okpo' => 'Код по ОКПО',
            'country_id' => 'Страна',
            'region_id' => 'Регион',
            'is_price_change' => 'Может ли изменять цены уровней тестирований',
            'is_head' => 'Является ли головным',
            'print_invoice_quoute' => 'Наличие кавычек у названия организации в счете',
            'is_old_act' => 'Разрешить создавать акты с датой тестирования более 15 дней'


        );
    }

    /** @return University_dogovor[] */
    public function getDogovors()
    {
        if ($this->parent_id)
            return Univesity_dogovors::getByUniversity($this->parent_id);
        return Univesity_dogovors::getByUniversity($this->id);
    }

    public function __toString()
    {
        return $this->name;
    }

    public function save()
    {
        $is_new = false;
        if ($this->id) {
            $is_new = true;
        }

        $res = parent::save(); // TODO: Change the autogenerated stub
        $this->id = $res;
        if (!$this->user_id) {
            $this->create_user();
        } else {
            $this->update_user();
        }

        return $res;
    }

    public function create_user()
    {
        $dt = new DateTime('- 84 days');
        $format = $dt->format('Y-m-d H:i:s');
        $login = 'center_' . $this->id;
        $password = genpsw();
        $firstname = $fathername = $this->short_name;
        $surname = $this->name;
        $sql = "INSERT INTO `tb_users` (`u_id`,`login`,`password`,`surname`,`firstname`,`fathername`,univer_id, head_id, password_changed_at)
        VALUES (NULL,'$login',md5(md5('$password')),'$surname','$firstname','$fathername','" . $this->id . "',
           " . CURRENT_HEAD_CENTER . ",
'" . $format . "'
        );";
        $res = mysql_query($sql);
        $external_id = mysql_insert_id();

        $this->user_id = $external_id;
        /*
                $sql = "INSERT INTO  `users` (`id` ,`rang` ,`dolz` ,`write`)VALUES (" . $external_id . ",  3,  '" . $surname . "',  1);";
                $res = mysql_query($sql);

                $sql2 = "INSERT INTO  `rubrik` (`r_id` ,`r_name` ,`r_par`)VALUES (" . $external_id . ",  '" . $surname . "',  271);";
                $res2 = mysql_query($sql2);
        */

        $this->user_login = $login;
        $this->user_password = $password;
        // return $password;
        parent::save();
    }

    public function update_user()
    {

        $sql2 = 'SELECT `u_id` FROM `tb_users`  WHERE univer_id=' . $this->id;

        $res2 = mysql_query($sql2);
        if (!mysql_num_rows($res2)) {
            return $this->create_user();
        }
        while ($row2 = mysql_fetch_array($res2)) {
            /*   $u_id = $row2['u_id'];
               $surname = $this->name;
               $sql3 = "update rubrik set r_name='" . mysql_real_escape_string($surname) . "'  where r_id=" . $u_id;
               $res3 = mysql_query($sql3);
               $sql4 = "update tb_users set surname='" . mysql_real_escape_string(
                       $surname
                   ) . "'  where u_id=" . $u_id;

               $res4 = mysql_query($sql4);*/
        }
        // return $password;
    }

    public function resetPassword()
    {

        if (!$this->user_id) {
            $this->create_user();
        }
        $password = genpsw(); //'013'.$this->id;

        $dt = new DateTime('- 84 days');
        $format = $dt->format('Y-m-d H:i:s');

        $sql =
            "update `tb_users`
            set password_changed_at = '$format', password= md5(md5('$password'))
            where u_id = " . $this->user_id;
        mysql_query($sql);
//die($sql);
        $this->user_password = $password;
        // return $password;
        //    parent::save();
    }

    public function getAvailableUsers()
    {
        $sql = '
        SELECT
  tu.u_id,
  tu.surname,
  tu.firstname,
  tu.fathername,
  tu.user_type_id,
  ut.caption,
  suu.id AS checked
FROM tb_users tu
  LEFT OUTER JOIN user_type ut
    ON tu.user_type_id = ut.id
  LEFT OUTER JOIN sdt_univer_user suu
    ON tu.u_id = suu.user_id AND suu.univer_id = ' . $this->id . '
  LEFT OUTER JOIN user_type_group_relations
    ON ut.id = user_type_group_relations.user_type_id AND user_type_group_relations.group_id = 43
WHERE tu.head_id =  ' . CURRENT_HEAD_CENTER . '  AND (tu.univer_id = 0 OR tu.univer_id IS NULL) AND user_type_group_relations.group_id IS NULL
ORDER BY tu.surname, tu.firstname, tu.fathername
';
//        die($sql);
        $res = mysql_query($sql);
        $result = array();
        while ($row = mysql_fetch_assoc($res)) {
            if (empty($row['caption'])) {
                $row['caption'] = 'Не указано';
            }
            if (!array_key_exists($row['caption'], $result)) {
                $result[$row['caption']] = array();
            }

            $result[$row['caption']][] = array(
                'id' => $row['u_id'],
                'caption' => $row['surname'] . ' ' . $row['firstname'] . ' ' . $row['fathername'],
                'checked' => (bool)$row['checked'],
                'user_type_id' => (bool)$row['user_type_id'],
            );
        }

        return $result;
    }

    public function saveUsers($users)
    {
        $sqlDelete = 'DELETE FROM sdt_univer_user WHERE univer_id=' . $this->id;
        mysql_query($sqlDelete);
        foreach ($users as $user_id) {
            $sqlIn = 'INSERT INTO sdt_univer_user(user_id,univer_id) VALUES (' . intval(
                    $user_id
                ) . ',' . $this->id . ')';
            mysql_query($sqlIn);
        }
    }

    public function getDogovorsByType($test_level_type_id)
    {
        if ($this->parent_id)
            return Univesity_dogovors::getByUniversityType($this->parent_id, $test_level_type_id);

        return Univesity_dogovors::getByUniversityType($this->id, $test_level_type_id);
    }

    public function getCountry()
    {
        if ($this->country_id) {
            return Country::getByID($this->country_id);
        }

        return new stdClass();
    }

    public function getRegion()
    {
        if ($this->region_id) {
            return Region::getByID($this->region_id);
        }

        return new stdClass();
    }

    public function getHeadCenter()
    {
        if ($this->head_id) {
            return HeadCenter::getByID($this->head_id);
        }

        return new stdClass();
    }

    public function delete()
    {
        if ($this->head_id != CURRENT_HEAD_CENTER) {
            return false;
        }
        $sqlDelete = 'DELETE FROM sdt_univer_user WHERE univer_id=' . $this->id;
        mysql_query($sqlDelete);
        $sql = 'DELETE FROM tb_users WHERE univer_id=' . $this->id;
        mysql_query($sql);
        if ($this->isHaveChildren()) {
            foreach ($this->getChildren() as $child) {
                $child->delete();
            }
        }
        return parent::delete();
    }

    /**
     * @return bool
     */
    public function isHaveChildren()
    {
        $c = Connection::getInstance();
        $res = $c->queryOne('SELECT count(*) AS cc FROM ' . $this->getTable() . ' WHERE deleted = 0 AND parent_id = ' . intval($this->id));
        return !!$res['cc'];

    }

    /**
     * @return Univesities|University[]
     */
    public function getChildren()
    {
        return Univesities::getChildren($this->id);

    }

    public function getSigning($TYPE_APPROVE)
    {
        $id = $this->parent_id ? $this->parent_id : $this->id;

        $signing = CenterSignings::getCenterAndType($id, $TYPE_APPROVE);
        if (!$signing || !count($signing))
            return null;

        return $signing;
    }

    /**
     * @return bool|null|University
     */
    public function getParent()
    {
        if (!$this->parent_id) return null;
        return self::getByID($this->parent_id, true);
    }

    /**
     * @param $id
     * @return bool|self
     */
    static function getByID($id, $skipRestrict = false)
    {
        if (!is_numeric($id)) {
            return false;
        }

        $id = (int)$id;
        $key = $id . '.' . (int)$skipRestrict;
        if (!array_key_exists($key, self::$cache)) {

//--    deleted=0 and

// head_id = ' . CURRENT_HEAD_CENTER . '         and
            $sql = 'SELECT * FROM sdt_university WHERE
        
      
           
         id=\'' . mysql_real_escape_string($id) . '\'';
            $result = mysql_query($sql);
            if (!mysql_num_rows($result)) {
                return false;
            }
            self::$cache[$key] = new University(mysql_fetch_assoc($result), $skipRestrict);
        }

        return self::$cache[$key];
    }

    public function getLegalInfo()
    {
        if (!$this->parent_id) {
            $parent = $this;
        } else {
            $parent = self::getByID($this->parent_id, true);
        }
        $university = array(
            'name' => $this->getFieldValue('name'), //'Название',
            'short_name' => $this->getFieldValue('short_name'), //'Сокращенное  название',
            'legal_address' => $this->getFieldValue('legal_address'),   //'Юридический адрес',


            'name_parent' => $parent->getFieldValue('name'), //'Название',
            'short_name_parent' => $parent->getFieldValue('short_name'), //'Сокращенное  название',
            'legal_address_parent' => $parent->getFieldValue('legal_address'), //'Юридический адрес',

            //'contact_phone' =>  $this->getParentedFieldValue('contact_phone'), //'Телефон',
            //'contact_fax' =>  $this->getParentedFieldValue('contact_fax'), //'Факс',
            //'contact_email' =>  $this->getParentedFieldValue('contact_email'), //'Email',
            //'contact_other' =>  $this->getParentedFieldValue('contact_other'), //'Дополнительные контакты',
            //'responsible_person' =>  $this->getParentedFieldValue('responsible_person'), //'Ответственный за проведение тестирования',
            //'comments' =>  $this->getParentedFieldValue('comments'), //'Комментарии',

            'rector' => $this->getParentedFieldValue('rector'), //'Ректор',
            'form' => $this->getParentedFieldValue('form'), //'Правовая форма',

            'bank' => $this->getParentedFieldValue('bank'), //'Банк',
            'city' => $this->getParentedFieldValue('city'), //'Город',
            'rc' => $this->getParentedFieldValue('rc'), //'Расчетный счет',
            'lc' => $this->getParentedFieldValue('lc'), //'Лицевой счет',
            'kc' => $this->getParentedFieldValue('kc'), //'Корреспондентский счет',
            'bik' => $this->getParentedFieldValue('bik'), //'БИК',
            'inn' => $this->getParentedFieldValue('inn'), //'ИНН',
            'kpp' => $this->getParentedFieldValue('kpp'), //'КПП',
            'okato' => $this->getParentedFieldValue('okato'), //'Код по ОКАТО',
            'okpo' => $this->getParentedFieldValue('okpo'), //'Код по ОКПО',
            //'country_id' =>  $this->getParentedFieldValue('country_id'), //'Страна',
            //'region_id' =>  $this->getParentedFieldValue('region_id'), //'Регион',
            //'is_price_change' =>  $this->getParentedFieldValue('is_price_change'), //'Может ли изменять цены уровней тестирований',
            //'is_head' =>  $this->getParentedFieldValue('is_head'), //'Является ли головным',
            //'print_invoice_quoute' =>  $this->getParentedFieldValue('print_invoice_quoute'), //'Наличие кавычек у названия организации в счете',
            //'is_old_act' =>  $this->getParentedFieldValue('is_old_act'), //'Разрешить создавать акты с датой тестирования более 15 дней'
        );
        return $university;


    }

    public function getParentedFieldValue($field)
    {
        if ($this->parent_id && $this->isParentedField($field)) {
            return self::getByID($this->parent_id, true)->$field;
        }
        return $this->$field;
    }

    public function isParentedField($field)
    {
        return in_array($field, $this->getParentFieldsList());
    }

    private function getParentFieldsList()
    {
        return
            [
//                'name',
//                'short_name',
                'rector',
                'form',
                'bank',
                'city',
                'rc',
                'lc',
                'kc',
                'bik',
                'inn',
                'kpp',
                'okato',
                'okpo',
                'is_old_act',
                'is_head',
                'okpo',
                'print_invoice_quoute',
            ];
    }

    protected function setAvailableValues()
    {
        $countries = Countries::getAll4Form();
        $regions = Regions::getAll4Form();

        $this->availableValues = array(
            'country_id' => $countries,
            'region_id' => $regions,
        );

    }

}