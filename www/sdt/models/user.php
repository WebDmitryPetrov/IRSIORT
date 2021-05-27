<?php

class Users extends ArrayObject
{

    public function __construct($array = array())
    {
        parent::__construct($array);

    }

    static public function getAll()
    {
        $list = new Users();
        $sql = 'select * from tb_users where head_id=' . CURRENT_HEAD_CENTER.' order by surname,login';
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new User($row);
        }

        return $list;
    }


}

class User extends Model
{
    protected $_table = 'tb_users';


    public $u_id;
    public $login;
    public $password;
    public $surname;
    public $firstname;
    public $fathername;
    public $univer_id;
    public $head_id;
    public $user_type_id;
    protected $primary = 'u_id';
    public  $password_change;

    public function __construct($input = false)
    {
        parent::__construct($input);
       $this->checkAccess();

    }

    protected static $availableRoles = null;



    public function saveRoles($roles_id)
    {
        $available = array_keys(self::getAvailableRoles());
        $roles_id = array_intersect($roles_id, $available);
        $sqlDelete = 'delete from tb_relations where fk_u_id=' . $this->u_id;
        mysql_query($sqlDelete);
        foreach ($roles_id as $id) {
            $sqlIn = 'insert into tb_relations(fk_g_id,fk_u_id) values (' . $id . ',' . $this->u_id . ')';
            mysql_query($sqlIn);
        }
    }

    static function getByID($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $sql = 'select * from tb_users where u_id=\'' . mysql_real_escape_string($id) . '\'';
        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return false;
        }
        $obj = new User(mysql_fetch_assoc($result));
        //$obj->checkAccess();

        return $obj;
    }

    public function setFields()
    {
        $this->fields = array(
            'u_id',
            'login',
            'surname',
            'firstname',
            'fathername',
            'univer_id',
            'head_id',
            'user_type_id',


        );

    }

    public function getEditFields()
    {
        return array(
            'u_id',
            'login',
            'surname',
            'firstname',
            'fathername',
            'univer_id',
            'user_type_id',


        );
    }

    public function setFieldsTypes()
    {
        $this->fieldTypes = array();

    }


    public static function getEditForm()
    {
        return array(
            'login' => 'Имя пользователя',
            'password_change' => array(
                'type' => 'password',
                'translate' => 'Пароль (если пусто, оставить прежний)',
            ),
            'surname' => 'Фамилия',
            'firstname' => 'Имя',
            'fathername' => 'Отчество',
        );
    }

    public function getFkFields()
    {
        return array(
            'head_id',
        );
    }

    public function setTranslate()
    {
        $this->translate = array();
    }


    public function delete()
    {
        $sql = 'delete from tb_users where u_id = ' . $this->u_id;
        mysql_query($sql);
        $sql = 'delete from tb_relations where fk_u_id = ' . $this->u_id;
        mysql_query($sql);

        return true;
    }

    public function getFullName()
    {
        return $this->surname . ' ' . $this->firstname . ' ' . $this->fathername;
    }

    private function checkAccess()
    {
//        return true;
//        die ('dfgdfgdfgdfgfdg');
        $C = Controller::getInstance();
        //if ($this->head_id != CURRENT_HEAD_CENTER) {
        if ($this->head_id != CURRENT_HEAD_CENTER && is_numeric($this->u_id)) {
            $C->redirectAccessRestricted();
        }


    }

    private $currentUser = null;

    public function getCurrentUser()
    {
        if (is_null($this->currentUser)) {
            $this->getByID($_SESSION['u_id']);
        }

        return $this->currentUser;
    }

    public function getRoles()
    {
        $sql = 'select fk_g_id from tb_relations where fk_u_id = ' . $this->u_id;
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

    public function save()
    {
        parent::save();
        if (!empty($this->password_change)) {
            $password = md5(md5($this->password_change));
            $this->password_change=null;
            $dt = new DateTime('- 84 days');
            $format = $dt->format('Y-m-d H:i:s');

            $sql = 'update ' . $this->_table . ' set password_changed_at = \''.$format.'\', password = \'' . mysql_real_escape_string($password) . '\'
            where u_id = ' . $this->u_id;
            mysql_query($sql);
//            var_dump($this->password_change);
//            die($sql);
        }
        return $this->id;
    }

    public function parseParameters($parameters = false)
    {
        parent::parseParameters($parameters);
        if (!empty($parameters['password_change'])) {
            $this->password_change = $parameters['password_change'];
        }
    }

    public function shortName()
    {


        if ($this->firstname)
            $firstname = substr($this->firstname, 0, 1) . '.';
        else
            $firstname = '';
        if ($this->fathername)
            $fathername = substr($this->fathername, 0, 1) . '.';
        else
            $fathername = '';

        return $this->surname . ' ' . $firstname . $fathername . '(' . $this->login . ')';
    }


}