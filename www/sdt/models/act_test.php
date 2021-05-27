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
        $sql = 'SELECT * FROM sdt_act_test  WHERE deleted=0';
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
        $sql = 'SELECT * FROM sdt_act_test  WHERE act_id=' . $id . ' AND deleted=0';
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new ActTest($row);
        }

        return $list;
    }

    static public function getCountsGroupedByLevel($act_id)
    {
      /*  $sql = 'SELECT stl.caption, stl.print, sum(sat.people_first) AS pf, sum(sat.people_retry) AS pr FROM sdt_act_test sat

LEFT JOIN sdt_test_levels stl ON stl.id = sat.level_id
 WHERE sat.act_id = %d AND sat.deleted=0
 GROUP BY stl.id';

        $sql=' SELECT stl.caption, stl.print, sum(sat.people_first) AS pf, sum(sat.people_retry) AS pr, sum(free_pr.cc) as fpr
FROM sdt_act_test sat 
LEFT JOIN sdt_test_levels stl ON stl.id = sat.level_id 
left join (
select count(distinct sap.id) as cc, sap.test_id from sdt_act_people sap 
INNER join re_exam_info rei on rei.man_id = sap.id and rei.is_free = 1
where sap.is_retry > 0 and sap.act_id = %d  group by sap.test_id) free_pr on sat.id = free_pr.test_id
WHERE sat.act_id = %d AND sat.deleted=0 GROUP BY stl.id';*/

        $sql = 'SELECT stl.print, sum(sat.people_first) AS pf, sum(sat.people_retry) AS pr, sum(free_pr.cc) as fpr
FROM sdt_act_test sat 
LEFT JOIN sdt_test_levels stl ON stl.id = sat.level_id 
left join (
select count(distinct sap.id) as cc, sap.test_id from sdt_act_people sap 
INNER join re_exam_info rei on rei.man_id = sap.id and rei.is_free = 1
where sap.is_retry > 0 and sap.act_id = %d  group by sap.test_id) free_pr on sat.id = free_pr.test_id
WHERE sat.act_id = %d AND sat.deleted=0 GROUP BY stl.print';
        $C = Connection::getInstance();
        return $C->query(sprintf($sql, intval($act_id), intval($act_id)));
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
    public $people_subtest_2_retry;
    public $people_subtest_all_retry;
    public $money_retry;
    public $price;
    public $price_subtest_retry;
    public $price_subtest_2_retry;

    public function __construct($input = false)
    {
        parent::__construct($input);

    }

    private static $_cache = array();

    /**
     * @param $id
     * @return bool|ActTest
     */
    static function getByID($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        if (!array_key_exists($id, self::$_cache)) {
            $sql = 'SELECT * FROM sdt_act_test WHERE  id=\'' . mysql_real_escape_string($id) . '\'';
            //   echo $sql;
            $result = mysql_query($sql);
            if (!mysql_num_rows($result)) {
                return false;
            }
            self::$_cache[$id] = new ActTest(mysql_fetch_assoc($result));
        }

        return self::$_cache[$id];
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
            'people_subtest_2_retry',
            'people_subtest_all_retry',
            'money_retry',
            'price',
            'price_subtest_retry',
            'price_subtest_2_retry',
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
            'people_subtest_2_retry',
            'people_subtest_all_retry',
            'price',
            'price_subtest_retry',
            'price_subtest_2_retry',
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
        $act = $this->getAct();

        if (is_null($this->id)) {
            $new = true;

            $prices = Prices::calcPrices($act, $this->getLevel());
//            die(var_dump($prices));
            $this->price = $prices->firstTime;

            $this->price_subtest_retry = $prices->subtest1;
            $this->price_subtest_2_retry = $prices->subtest2;
        }
        //var_dump($this);die;

        if(!in_array($this->level_id,Reexam_config::getTestLevels()) && $this->people_subtest_all_retry){
            $this->people_subtest_all_retry=0;
            $this->people_retry  = $this->people_subtest_retry + $this->people_subtest_2_retry;
        }

        $this->people_count = $this->people_first + $this->people_retry;
//        $this->money = $this->money_first + $this->money_retry;
        $id = parent::save();

        $act->save();
        if ($new) {
            if ($this->people_first) {
                for ($i = 0; $i < $this->people_first; $i++) {
                    $this->addMan();
                }
            }
            if ($this->people_subtest_all_retry) {
                for ($i = 0; $i < $this->people_subtest_all_retry; $i++) {
                    $this->addMan(ActMan::RETRY_ALL);
                }
            }
            if ($this->people_subtest_retry) {
                for ($i = 0; $i < $this->people_subtest_retry; $i++) {
                    $this->addMan(1);
                }
            }

            if ($this->people_subtest_2_retry) {
                for ($i = 0; $i < $this->people_subtest_2_retry; $i++) {
                    $this->addMan(2);
                }
            }
        }

        return $id;
    }


    private $currentLevel = null;

    /**
     * @return bool|TestLevel
     */
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

    public function getPriceSubTest2()
    {
        if (empty($this->price_subtest_2_retry)) {
            $this->price_subtest_2_retry = $this->getLevel()->sub_test_price_2;
        }

        return $this->price_subtest_2_retry;
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
        $sql = 'DELETE FROM sdt_act_test WHERE id = ' . $this->id;
        mysql_query($sql);
        $this->getAct()->save();

        return mysql_affected_rows();
    }

    public function countCertificate()
    {
        $count = 0;
        foreach ($this->getPeople() as $man) {
            if ($man->document == 'certificate') {
                $count++;
            }

        }

        return $count;
    }

    public function countNote()
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
    private function addMan($is_retry = 0)
    {
        $man = new ActMan();
        $man->act_id = $this->act_id;
        $man->test_id = $this->id;
        $man->is_retry = $is_retry;
        $man->save();
    }

    public function countFreeRetry_1()
    {
        $q='select COUNT(*) as cc FROM re_exam_info rei 
  left JOIN sdt_act_people sap ON rei.man_id = sap.id
  
  WHERE sap.test_id = %d AND rei.is_free = 1 and sap.is_retry = 1';

        $c=Connection::getInstance();
        $res = $c->queryOne(sprintf($q,$this->id));
        if($res){
            return $res['cc'];
        }
        return 0;
    }

    public function countFreeRetry_2()
    {
        $q='select COUNT(*) as cc FROM re_exam_info rei 
  left JOIN sdt_act_people sap ON rei.man_id = sap.id
  
  WHERE sap.test_id = %d AND rei.is_free = 1 and sap.is_retry = 2';

        $c=Connection::getInstance();
        $res = $c->queryOne(sprintf($q,$this->id));
        if($res){
            return $res['cc'];
        }
        return 0;
    }

    public function countFreeRetry()
    {
        /*$q='select COUNT(*) as cc FROM re_exam_info rei
  left JOIN sdt_act_people sap ON rei.man_id = sap.id

  WHERE sap.test_id = %d AND rei.is_free = 1 and sap.is_retry = '.ActMan::RETRY_ALL;
        */
        $q='select COUNT(*) as cc FROM sdt_act_people sap

  WHERE sap.test_id = %d and sap.is_retry = '.ActMan::RETRY_ALL;

        $c=Connection::getInstance();
        $res = $c->queryOne(sprintf($q,$this->id));
        if($res){
            return $res['cc'];
        }
        return 0;
    }


    public function getMoneyFirst()
    {
        return $this->getPrice() * $this->people_first;
    }

    public function getMoneyRetry_1()
    {
//        return $this->getPriceSubTest() * ($this->people_subtest_retry - $this->countFreeRetry_1());
        return $this->getPriceSubTest() * $this->people_subtest_retry;
    }

    public function getMoneyRetry_2()
    {
//        return $this->getPriceSubTest2() * ($this->people_subtest_2_retry - $this->countFreeRetry_2());
        return $this->getPriceSubTest2() * $this->people_subtest_2_retry;
    }

    public function getMoneyTotal()
    {
        return $this->getMoneyFirst() + $this->getMoneyRetry_1() + $this->getMoneyRetry_2();
    }


}