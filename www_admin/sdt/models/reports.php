<?php
class Reports extends ArrayObject
{

    public function __construct($array = array())
    {
        parent::__construct($array);

    }

    static public function getAll()
    {
        $list = new Reports();
        $sql = 'select * from reports order by id';
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            if(in_array($row['action_name'], ['report_people_gc', 'students_report_about_by_lc', 'report_antiterror_student'])) continue;
            $list[] = new Report($row);
        }

        return $list;
    }

    public static function getAll4User()
    {
        $list = new Reports();
        $sql = 'select * from reports where visible=1 order by id';
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new Report($row);
        }

        return $list;
    }


    static public function getRoles()
    {
        //$list = new Reports();
        $list=array();
        $sql = 'select action_name from reports order by id';
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = $row['action_name'];
        }

        return $list;
    }


    public static function getRoles4User()
    {
        $list=array();
        $sql = 'select action_name from reports where visible=1 order by id';
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = $row['action_name'];
        }


        return $list;
    }

    public static function save4User($list)
    {
       // $list = self::getAll();

        //if (!$list) return false;
        $list=implode(",", $list);

       // var_dump($list);die;

        $sql = 'UPDATE `reports` SET `visible`=0';
        $result = mysql_query($sql) or die(mysql_error());

        if ($list)
        {
        $sql = 'UPDATE `reports` SET `visible`=1 WHERE  `id` IN ('.$list.') ';
        $result = mysql_query($sql) or die(mysql_error());
        }

    }


}

class Report extends Model
{
    protected $_table = 'reports';

    public $id;
    public $caption;
    public $action_name;
    public $visible;


    public function __construct($input = false)
    {
        parent::__construct($input);

    }

    static function getByID($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $sql = 'select * from reports where id=\'' . mysql_real_escape_string($id) . '\'';
        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return false;
        }
        $report = new Report(mysql_fetch_assoc($result));

        return $report;
    }



    public function delete()
    {
        $sql = 'delete from reports where id = ' . $this->id;
        mysql_query($sql);

        return mysql_affected_rows();
    }


    public function setFields()
    {
        $this->fields = array(
            'id',
            'caption',
            'action_name',
            'visible',
        );

    }

    public function getEditFields()
    {
        return array(
            'caption',
            'action_name',
            'visible',
        );
    }


    public function setFieldsTypes()
    {
        $this->fieldTypes = array(
            'visible' => 'select'
        );
    }

    public function setTranslate()
    {
        $this->translate = array(
            'caption' => 'Название',
            'action_name' => 'Имя действия (action)',
            'visible' => 'Видит ли пользватель по отчетам'
        );
    }

    protected function setAvailableValues()
    {
        $show=array(0=>"Нет", 1=>"Да");


        $this->availableValues = array(
            'visible' => $show,

        );

    }



    public function getFkFields()
    {
        return array();
    }


    public function save()
    {

        parent::save();
    }

    function __toString()
    {
        return $this->caption;
    }

}