<?php

class Univesities extends ArrayObject
{
    protected $_table;

    public function __construct($array = array())
    {
        parent::__construct($array);

    }

    static public function getAll($skipRights = false)
    {
        $list = new Univesities();
        $sql = 'select * from sdt_university where deleted=0
         and
         head_id = ' . CURRENT_HEAD_CENTER . '
         order by name';
//        die($sql);
        $result = mysql_query($sql) or die(mysql_error() . $sql);
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new University($row, $skipRights);
        }

        return $list;
    }


    public static function getByLevel($level)
    {
        $restrict = '';
        $C = AbstractController::getInstance();
        $restrictions = $C->getUniversityRestrictionArray();
        if ($restrictions) {
            $restrict = "
             and  sdt_university.id in (" . implode(', ', $restrictions) . ') ';
        }
        $list = new Univesities();
        $sql = 'SELECT sdt_university.id as id, sdt_university.name AS caption
     , count(sdt_act.id) AS cc,
     sum(if(sdt_act.viewed,0,1)) as unread
FROM
  sdt_university
INNER JOIN sdt_act
ON sdt_university.id = sdt_act.university_id
WHERE
  sdt_act.state = \'' . mysql_real_escape_string($level) . '\'

    and
         sdt_university.head_id = ' . CURRENT_HEAD_CENTER . '
  AND sdt_act.deleted = 0
  AND sdt_university.deleted = 0

  ' . $restrict . '
GROUP BY
  sdt_university.id
, sdt_university.name
, sdt_act.university_id
having cc>0
ORDER BY
  caption
, cc';


        $result = mysql_query($sql) or die(mysql_error() . $sql);
        while ($row = mysql_fetch_assoc($result)) {
            $new = new stdClass();
            $new->id = $row['id'];
            $new->caption = $row['caption'];
            $new->count = $row['cc'];
            $new->unread = $row['unread'];
            $list[] = $new;

        }

        return $list;
    }    
	
	public static function getByLevels($level)
    {
        $restrict = '';
        $C = AbstractController::getInstance();
        $restrictions = $C->getUniversityRestrictionArray();
        if ($restrictions) {
            $restrict = "
             and  sdt_university.id in (" . implode(', ', $restrictions) . ') ';
        }
        $list = new Univesities();
        $sql = 'SELECT sdt_university.id as id, sdt_university.name AS caption
     , count(sdt_act.id) AS cc,
     sum(if(sdt_act.viewed,0,1)) as unread
FROM
  sdt_university
INNER JOIN sdt_act
ON sdt_university.id = sdt_act.university_id
WHERE
  sdt_act.test_level_type_id = \'' . $level . '\'
	and
  sdt_act.state in (\''.ACT::STATE_RECEIVED.'\',\''.ACT::STATE_PRINT.'\',\''.ACT::STATE_WAIT_PAYMENT.'\',\''.ACT::STATE_CHECK.'\' )
    and
    sdt_university.head_id = ' . CURRENT_HEAD_CENTER . '

  AND sdt_act.deleted = 0
  AND sdt_university.deleted = 0

  ' . $restrict . '

GROUP BY
  sdt_university.id
, sdt_university.name
, sdt_act.university_id
having cc>0
ORDER BY
  caption
, cc';


        $sql="SELECT  sdt_university.id AS id, sdt_university.name AS caption,
 COUNT(sdt_act.id) AS cc,
 SUM(IF(sdt_act.viewed,0,1)) AS unread,
 SUM( IF(sdt_act.invoice = ''
 or sdt_act.invoice is  null  , 1,0) )  AS no_invoice,
 SUM( IF(sdt_act.invoice <> ''
and sdt_act.invoice is not null and sdt_act.paid = 0  , 1,0) )  AS wait_paid,
sum(ss.o) as no_blanks,
sum(if(
sdt_act.invoice <> ''
 and sdt_act.invoice is not null
  and sdt_act.paid = 1
  and ss.o = 0,1,0)) as to_arch

FROM sdt_university
INNER JOIN sdt_act ON sdt_university.id = sdt_act.university_id
left join (
	select sa.id,
	  IF(
    SUM(IF(sap.blank_number = '' OR sap.blank_number IS NULL, 1, 0))>0,1,0) AS o

	from sdt_act sa
  	left join sdt_act_people sap on sap.act_id = sa.id
  		left join sdt_university  on sa.university_id = sdt_university.id
   where sa.deleted = 0 and sap.deleted = 0
	and sa.state in (".Act::getStatesSql(Act::getReceivedStates()).")
	 " . $restrict . "
	and sa.test_level_type_id = '" . $level . "'
  	group by sa.id) ss on ss.id = sdt_act.id

WHERE sdt_act.test_level_type_id = '" . $level . "'
 AND sdt_act.state IN (".Act::getStatesSql(Act::getReceivedStates()).")
 AND sdt_university.head_id =  " . CURRENT_HEAD_CENTER . "
 AND sdt_act.deleted = 0 AND sdt_university.deleted = 0
  " . $restrict . "
 GROUP BY sdt_university.id, sdt_university.name, sdt_act.university_id

ORDER BY caption";
//die ($sql);

        $result = mysql_query($sql) or die(mysql_error() . $sql);
        while ($row = mysql_fetch_assoc($result)) {
            $new = new stdClass();
            $new->id = $row['id'];
            $new->caption = $row['caption'];
            $new->count = $row['cc'];
            $new->unread = $row['unread'];
            $new->no_invoice = $row['no_invoice'];
            $new->wait_paid = $row['wait_paid'];
            $new->no_blanks = $row['no_blanks'];
            $new->to_arch = $row['to_arch'];
            $list[] = $new;

        }

        return $list;
    }


    public static function get4Buh()
    {
        $list = new Univesities();
       $sql = '
SELECT sdt_university.id as id, sdt_university.name AS caption
     , count(sdt_act.id) AS cc
FROM
  sdt_university
INNER JOIN sdt_act
ON sdt_university.id = sdt_act.university_id
WHERE
     sdt_act.invoice is not NULL
                  and
                  sdt_act.invoice <> ""
                  and
   sdt_act.deleted = 0
    and
         sdt_university.head_id = ' . CURRENT_HEAD_CENTER . '
  AND sdt_university.deleted = 0
GROUP BY
  sdt_university.id
, sdt_university.name
, sdt_act.university_id
having cc>0
ORDER BY
  caption
, cc';
        $result = mysql_query($sql) or die(mysql_error() . $sql);
        while ($row = mysql_fetch_assoc($result)) {
            $new = new stdClass();
            $new->id = $row['id'];
            $new->caption = $row['caption'];
            $new->count = $row['cc'];
            $list[] = $new;

        }

        return $list;
    }

    static public function get4Act()
    {
        $list = new Univesities();
        $sql = 'select * from sdt_university
				where deleted=0
				   and
         head_id = ' . CURRENT_HEAD_CENTER . '
				and id in (
				select distinct(university_id)
				from sdt_university_dogovor
				where deleted=0)
				order by name';
        $result = mysql_query($sql) or die(mysql_error() . $sql);
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new University($row);
        }

        return $list;
    }

    public static function get4Admin()
    {
        return self::getAll();
    }

    public static function getUsers()
    {
        $sql = '
select
  u_id,surname,firstname,fathername

  from tb_users  tu

  WHERE

 ( tu.univer_id = 0 or tu.univer_id is NULL)

    and
         tu.head_id = ' . CURRENT_HEAD_CENTER . '
  order by surname,firstname,fathername ';
//        die($sql);
        $res = mysql_query($sql);
        $result = array();
        while ($row = mysql_fetch_assoc($res)) {
            $result[] = array(
                'id' => $row['u_id'],
                'caption' => $row['surname'] . ' ' . $row['firstname'] . ' ' . $row['fathername'],

            );
        }

        return $result;
    }

    public static function getByUser($id)
    {
        $sql = '
select
 tu.id,name, suu.user_id as checked

  from sdt_university  tu
  LEFT JOIN sdt_univer_user suu
  ON tu.id = suu.univer_id AND suu.user_id=' . $id . '
  WHERE

  tu.deleted = 0

    and
         tu.head_id = ' . CURRENT_HEAD_CENTER . '
        order by name;';
        $res = mysql_query($sql) or die(mysql_error());
        $result = array();
        while ($row = mysql_fetch_assoc($res)) {
            $result[] = array(
                'id' => $row['id'],
                'caption' => $row['name'],
                'checked' => intval($row['checked']),

            );
        }

        return $result;
    }

    public static function saveUserRight($user_id, $univers)
    {
        $sqlDelete = 'delete from sdt_univer_user where user_id=' . $user_id;
        mysql_query($sqlDelete);
        foreach ($univers as $univer) {
            $sqlIn = 'insert into sdt_univer_user(user_id,univer_id) values (' . intval(
                    $user_id
                ) . ',' . $univer . ')';
            mysql_query($sqlIn);
        }
    }
}

class University extends Model
{
    public $user_login;
    public $user_id;
    protected $_table = 'sdt_university';

    public $id;
    public $name;
    public $short_name;
    public $form;
    public $legal_address;
    public $contact_phone;
    public $contact_fax;
    public $contact_email;
    public $contact_other;
    public $responsible_person;

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
    public $head_id;
	public $is_price_change;
    public $is_head;
    public $api_enabled;

    public $print_invoice_quoute;

    public $user_password;

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

    static function getByID($id, $skipRestrict = false)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $sql = 'select * from sdt_university where


         head_id = ' . CURRENT_HEAD_CENTER . '
         and id=\'' . mysql_real_escape_string($id) . '\'';
        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return false;
        }
        $univer = new University(mysql_fetch_assoc($result), $skipRestrict);

        return $univer;
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
            'head_id',
            'print_invoice_quoute',
			'is_price_change',
            'is_head',
            'api_enabled',

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
            'print_invoice_quoute',
            'is_price_change',
            'is_head',


        );
    }

    public function getFkFields()
    {
        return array(
            'user_id',
            'head_id',
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
            'is_price_change' => 'checkbox',
            'is_head' => 'checkbox',
            'print_invoice_quoute' => 'checkbox'
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
            'is_price_change' => 'Может ли изменять цены уровней тестирований',
            'is_head' => 'Является ли головным',
            'print_invoice_quoute' => 'Наличие кавычек у названия организации в счете'


        );
    }

    /** @return University_dogovor[] */
    public function getDogovors()
    {
        return Univesity_dogovors::getByUniversity($this->id);
    }

    public function __toString()
    {
        return $this->name;
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
'".$format."'
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


    public function update_user()
    {

        $sql2 = 'select `u_id` from `tb_users`  where univer_id=' . $this->id;

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


    public function  getAvailableUsers()
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
            if(empty($row['caption'])){
                $row['caption']='Не указано';
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
        $sqlDelete = 'delete from sdt_univer_user where univer_id=' . $this->id;
        mysql_query($sqlDelete);
        foreach ($users as $user_id) {
            $sqlIn = 'insert into sdt_univer_user(user_id,univer_id) values (' . intval(
                    $user_id
                ) . ',' . $this->id . ')';
            mysql_query($sqlIn);
        }
    }

    protected function checkRights()
    {
        $C = AbstractController::getInstance();
        $restrictions = $C->getUniversityRestrictionArray();

        if (is_array($restrictions)) {
            if (!in_array($this->id, $restrictions)) {
//                var_dump($this->id,$restrictions);die;
                $C->redirectAccessRestricted();
            }
        }
    }

    protected function setAvailableValues()
    {
        $countries = Countries::getAll4Form();


        $this->availableValues = array(
            'country_id' => $countries,
        );

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
        if ($this->head_id != CURRENT_HEAD_CENTER) {
            return false;
        }
        $sqlDelete = 'delete from sdt_univer_user where univer_id=' . $this->id;
        mysql_query($sqlDelete);
        $sql = 'delete from tb_users where univer_id=' . $this->id;
        mysql_query($sql);

        return parent::delete();
    }    
	
	public function isHead($act_id)
    {
        $sql='select t2.is_head from sdt_act as t1 left join sdt_university as t2 on t1.university_id=t2.id where t1.id='.$act_id;
		$result=mysql_query($sql) or die(mysql_error());
		if (mysql_num_rows($result)==0 || mysql_result($result,0,0) != 1) return '';
		else return 1;
    }

}