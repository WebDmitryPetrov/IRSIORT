<?php

class UserTypes extends ArrayObject
{

    public function __construct($array = array())
    {
        parent::__construct($array);

    }

    static public function getAll()
    {
        $list = new UserTypes();
        $sql = 'select id,caption,head_visible from user_type';
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new UserType($row);
        }

        return $list;
    }

    static public function getForUniverRights()
    {
        $list = new UserTypes();
        $sql = 'SELECT
  user_type.id,
  user_type.caption,
  user_type.head_visible
FROM user_type
  LEFT OUTER JOIN user_type_group_relations
    ON user_type.id = user_type_group_relations.user_type_id AND user_type_group_relations.group_id = 43
WHERE user_type.head_visible = 1 AND user_type_group_relations.user_type_id IS NULL
GROUP BY user_type.id,
         user_type_group_relations.group_id';
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new UserType($row);
        }

        return $list;
    }

//    public static function getAvailable4Act($act_id)
//    {
//        $sqlUsed = 'select COUNT(DISTINCT IF(`level_id`= 1, level_id, NULL)) as migrants,
//  COUNT(DISTINCT IF(`level_id` >1, level_id, NULL)) as other
//  from sdt_act_test where act_id= ' . intval($act_id);
//
//        //die($sqlUsed);
//        $result_used = mysql_query($sqlUsed) or die(mysql_error());
//        $row = mysql_fetch_assoc($result_used);
//
//        $restrict = '';
//        if ($row['migrants']) {
//            $restrict = ' and id=1';
//        }
//        if ($row['other']) {
//            $restrict = ' and id>1';
//        }
//
//        $list = new TestLevels();
//        $sql = 'select * from sdt_test_levels  where deleted=0 ' . $restrict . ' order by id';
//        $result = mysql_query($sql) or die(mysql_error());
//        while ($row = mysql_fetch_assoc($result)) {
//            $list[] = new TestLevel($row);
//        }
//
//        return $list;
//    }
}

class UserType extends Model
{
    protected $_table = 'user_type';

    public $id;
    /**
     * @var string
     */
    public $caption;
    public $head_visible;

	public function isHasRole($group_id)
	{
		$sql='select * from user_type_group_relations where user_type_id='.$this->id.' and group_id='.$group_id;
		$result=mysql_query($sql) or die (mysql_error());
		return mysql_num_rows($result);
	}

    public function __construct($input = false)
    {
        parent::__construct($input);

    }

    static function getByID($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $sql = 'select * from user_type where id=\'' . mysql_real_escape_string($id) . '\'';
        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return false;
        }
        $group = new UserType(mysql_fetch_assoc($result));
//die (var_dump($univer));
        return $group;
    }

    public function setFields()
    {
        $this->fields = array(
            'id',
            'caption',
            'head_visible'

        );
    }

    public function getEditFields()
    {
        return array(
            'caption',
            'head_visible'

            );
    }

//    public function getFkFields()
//    {
//        return array('total');
//    }

    public function setFieldsTypes()
    {
        $this->fieldTypes = array('head_visible' => 'select');
    }

    public function setTranslate()
    {
        $this->translate = array(
            'id' => 'Идентификатор',
            'caption' => 'Название',
            'head_visible' => 'Можно выбрать головной центр'
            );
    }

    public function save()
    {

        /*$this->price = floatval(str_replace(',', '.', $this->price));
        $this->sub_test_price = floatval(str_replace(',', '.', $this->sub_test_price));*/

        parent::save();
    }

    /*public function save()
    {
        parent::save();
        if (!empty($this->password_change)) {
            $password = md5(md5($this->password_change));
            $this->password_change=null;
            $sql = 'update ' . $this->_table . ' set password = \'' . mysql_real_escape_string($password) . '\'
            where u_id = ' . $this->u_id;
            mysql_query($sql);
//            var_dump($this->password_change);
//            die($sql);
        }
        return $this->id;
    }*/

    public function __toString()
    {
        return $this->caption;

    }
    protected function setAvailableValues()
    {
        $show=array(0=>"Нет", 1=>"Да");


        $this->availableValues = array(
            'head_visible' => $show,

        );

    }
    public function delete()
    {
        $sql = 'delete from user_type where id = ' . $this->id;
        mysql_query($sql);
        $sql = 'delete from user_type_group_relations where user_type_id = ' . $this->id;
        mysql_query($sql);

        return true;
    }
    protected static $availableRoles = null;

    public static function getAvailableRoles()
    {
        if (is_null(self::$availableRoles)) {
            $list = array();

            $C=Controller::getInstance();
            if ($_SESSION["u_id"]==MAINEST_ADMIN_ID) $sql = 'select * from tb_groups';
            elseif ($C->userHasRole(Roles::ROLE_ROOT)) $sql = 'select * from tb_groups where g_id != 33';
            else $sql = 'select * from tb_groups where head_visible=1';


            $result = mysql_query($sql) or die(mysql_error());
            while ($row = mysql_fetch_assoc($result)) {
                $list[$row['g_id']] = $row['g_name'];
            }
            self::$availableRoles = $list;
        }

        return self::$availableRoles;
    }

    public function saveRoles($roles_id)
    {
        $available = array_keys(self::getAvailableRoles());
        $roles_id = array_intersect($roles_id, $available);
        $sqlDelete = 'delete from user_type_group_relations where user_type_id=' . $this->id;
        mysql_query($sqlDelete);
        foreach ($roles_id as $id) {
            $sqlIn = 'insert into user_type_group_relations(group_id,user_type_id) values (' . $id . ',' . $this->id . ')';
            mysql_query($sqlIn);
        }
    }

    public function getRoles()
    {
        $sql = 'select group_id from user_type_group_relations where user_type_id = ' . $this->id;
//        die($sql);
        $res = mysql_query($sql);
        if (!mysql_num_rows($res)) {
            return array();
        }
        $result = array();
        while ($row = mysql_fetch_array($res)) {
            $result[] = $row[0];
        }

        return $result;

    }


    public function getFullName()
    {
        return $this->caption;
    }


}