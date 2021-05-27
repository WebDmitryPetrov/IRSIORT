<?php

class ActTests extends ArrayObject
{

    public function __construct($array = array())
    {
        parent::__construct($array);

    }

    static public function getAll()
    {
        $list = new ActTests();
        $sql = 'select * from sdt_act_test  where deleted=0';
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new ActTest($row);
        }

        return $list;
    }

    /**
     * @param $id
     *
     * @return ActTest[]
     */
    static public function get4Act($id)
    {
        $list = new ActTests();
        $sql = 'select * from sdt_act_test  where act_id=' . $id . ' and deleted=0';
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new ActTest($row);
        }

        return $list;
    }
}

class ActTest extends Model
{
    protected $_table = 'sdt_act_test';

    public $id;
    public $level_id;
    public $people_count;
    public $money;
    public $act_id;
    public $people_first;
    public $money_first;
    public $people_retry;
    public $people_subtest_retry;
    public $money_retry;
    public $price;
    public $price_subtest_retry;

    public function __construct($input = false)
    {
        parent::__construct($input);

    }

    static function getByID($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $sql = 'select * from sdt_act_test where  id=\'' . mysql_real_escape_string($id) . '\'';
        //   echo $sql;
        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return false;
        }
        $univer = new ActTest(mysql_fetch_assoc($result));

        return $univer;
    }

    public function setFields()
    {
        $this->fields = array(
            'id',
            'level_id',
            'people_count',
            'money',
            'act_id',
            'people_first',
            'money_first',
            'people_retry',
            'people_subtest_retry',
            'money_retry',
            'price',
            'price_subtest_retry',
        );
    }

    public function getEditFields()
    {
        return array(

            'people_count',
            'money',
            'people_first',
            'money_first',
            'people_retry',
            'money_retry',

        );
    }

    public function getFkFields()
    {
        return array(

            'level_id',
            'act_id',
            'people_subtest_retry',
            'price',
            'price_subtest_retry',
        );
    }

    public function setFieldsTypes()
    {
        $this->fieldTypes = array();
    }

    public function setTranslate()
    {
        $this->translate = array();
    }

    public function save()
    {


        $new = false;
        if (is_null($this->id)) {
            $new = true;
        }
        //var_dump($this);die;
        $this->people_count = $this->people_first + $this->people_retry;
        $this->money = $this->money_first + $this->money_retry;
        $id = parent::save();
        $act = $this->getAct();
        $act->save();
        if ($new) {
            if ($this->people_first) {
                for ($i = 0; $i < $this->people_first; $i++) {
                    $this->addMan($id);
                }
            }
            if ($this->people_retry) {
                for ($i = 0; $i < $this->people_retry; $i++) {
                    $this->addMan($id, 1);
                }
            }
        }

        return $id;
    }

    private $currentLevel = null;

    public function getLevel()
    {
        if (is_null($this->currentLevel)) {
            $this->currentLevel = TestLevel::getByID($this->level_id);
        }

        return $this->currentLevel;
    }


    public function getPrice()
    {

        if (empty($this->price)) {


            $this->price = $this->getLevel()->price;


        }

        return $this->price;
    }

    public function getPriceSubTest()
    {
        if (empty($this->price_subtest_retry)) {


            $this->price_subtest_retry = $this->getLevel()->sub_test_price;


        }

        return $this->price_subtest_retry;

    }


    public function getAct()
    {
        return Act::getByID($this->act_id);

    }

    public function getPeople()
    {
        return ActPeople::get4Test($this->id);
    }

    public function delete()
    {
        $people = $this->getPeople();
        foreach ($people as $man) {
            $man->delete();
        }
        $sql = 'delete from sdt_act_test where id = ' . $this->id;
        mysql_query($sql);
        $this->getAct()->save();

        return mysql_affected_rows();
    }

    public function  countCertificate()
    {
        $count = 0;
        foreach ($this->getPeople() as $man) {
            if ($man->document == 'certificate') {
                $count++;
            }

        }

        return $count;
    }

    public function  countNote()
    {
        $count = 0;
        foreach ($this->getPeople() as $man) {
            if ($man->document == 'note') {
                $count++;
            }

        }

        return $count;
    }

    /**
     * @param $act_test_id
     * @param $is_retry
     */
    private function addMan($act_test_id, $is_retry = 0)
    {
        $man = new ActMan();
        $man->act_id = $this->act_id;
        $man->test_id = $act_test_id;
        $man->is_retry = $is_retry;
        $man->save();
    }
}