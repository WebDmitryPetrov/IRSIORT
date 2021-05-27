<?php

class Acts extends ArrayObject
{

    public function __construct($array = array())
    {
        parent::__construct($array);

    }

    static public function getAll()
    {
        $list = new Acts();
        $sql = 'SELECT sdt_act.*
				FROM
				sdt_university
				INNER JOIN sdt_act
				ON sdt_university.id = sdt_act.university_id
				INNER JOIN sdt_university_dogovor
				ON sdt_university_dogovor.id = sdt_act.university_dogovor_id
				WHERE
				sdt_act.deleted = 0

				ORDER BY
				 sdt_act.created desc,
				sdt_university.name
				, sdt_university_dogovor.number
				, sdt_act.testing_date';
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new Act($row);
        }

        return $list;
    }


    static public function getPaid($univer_id = false)
    {
        $list = new Acts();
        $univer_sql = '';
        if ($univer_id) {
            $univer_sql = ' and sdt_university.id=' . intval($univer_id);
        }
        $sql = 'SELECT sdt_act.*
				FROM
				sdt_university
				INNER JOIN sdt_act
				ON sdt_university.id = sdt_act.university_id
				INNER JOIN sdt_university_dogovor
				ON sdt_university_dogovor.id = sdt_act.university_dogovor_id
				WHERE
				sdt_act.paid = 1

				AND sdt_act.deleted = 0
				' . $univer_sql . '
				ORDER BY
				 sdt_act.created,
				sdt_university.name
				, sdt_university_dogovor.number
				, sdt_act.testing_date';
        $result = mysql_query($sql) or die(mysql_error() . $sql);
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new Act($row);
        }

        return $list;
    }

    static public function getMinAddDate()
    {
        $sql = 'select date_format(min(created),\'%Y-%m-%d\') from sdt_act where created>\'0000-00-00\'';

        return mysql_result(mysql_query($sql), 0, 0);
    }

    static public function getMaxAddDate()
    {
        $sql = 'select date_format(max(created),\'%Y-%m-%d\') from sdt_act where created>\'0000-00-00\'';

        return mysql_result(mysql_query($sql), 0, 0);
    }

    static public function getMinTestDate()
    {
        $sql = 'select date_format(min(testing_date),\'%Y-%m-%d\') from sdt_act where testing_date>\'0000-00-00\'';

        return mysql_result(mysql_query($sql), 0, 0);
    }

    static public function getMaxTestDate()
    {
        $sql = 'select date_format(max(testing_date),\'%Y-%m-%d\') from sdt_act where testing_date>\'0000-00-00\'';

        return mysql_result(mysql_query($sql), 0, 0);
    }

    public static function Search($params, $restrict_id = false)
    {
        $restrict = "";
//        if ($restrict_id) {
//            $restrict = "
//             and (sdt_university.owner_id is null
//  or sdt_university.owner_id=0
//            or sdt_university.owner_id='" . mysql_real_escape_string($restrict_id) . "') ";
//        }
        if ($params['state']==1) $state='"wait_payment","received","check","print"';
        elseif ($params['state']==2) $state='"archive"';
        elseif ($params['state']==3) $state='"wait_payment","archive","received","check","print"';

        $sql = 'SELECT sdt_act.id
            FROM
  sdt_act
INNER JOIN sdt_act_test ON sdt_act.id = sdt_act_test.act_id
INNER JOIN sdt_university
				ON sdt_university.id = sdt_act.university_id

 where sdt_act.deleted = 0
				   and
sdt_act.state in ('.$state.') and
';
        $Where = array();
        if(!empty($params['act_id']) && is_numeric($params['act_id'])){
            $Where[] = "  sdt_act.id = " . $params['act_id'];

        }
        if(!empty($params['act_num']) && $params['act_num']){
            $Where[] = "  sdt_act.number like '" . $params['act_num']."%'";
        }

        $Where[] = "sdt_act.created>='" . $params['minAddDate'] . " 00:00:00'";
        $Where[] = "sdt_act.created<='" . $params['maxAddDate'] . " 23:59:59'";
        $Where[] = "sdt_act.testing_date>='" . $params['minTestDate'] . " 00:00:00'";
        $Where[] = "sdt_act.testing_date<='" . $params['maxTestDate'] . " 23:59:59'";
        if (isset($params['level']) && is_numeric($params['level']) && $params['level'] > 0) {
            $Where[] = "sdt_act_test.level_id='" . $params['level'] . "'";
        }
        if (isset($params['organization']) && is_numeric($params['organization']) && $params['organization'] > 0) {
            $Where[] = "sdt_act.university_id='" . $params['organization'] . "'";
        }
        //   $Where[]="sdt_act.state='".$params['state']."'";
        $sql .= implode(' and ', $Where) . $restrict . ' group by sdt_act.id limit 20';
        $result = mysql_query($sql);
        $return = array();
        if (!mysql_num_rows($result)) {
            return null;
        }

        while ($row = mysql_fetch_assoc($result)) {
            $return[] = Act::getByID($row['id']);
        }

        return $return;
    }


    public static function getByLevel($univer_id, $level)
    {
        $list = new Acts();


        $restrict = '';
        $C = Controller::getInstance();
        $restrictions = $C->getUniversityRestrictionArray();
        // var_dump($restrictions);die();
        if ($restrictions) {
            $restrict = "
             and  sdt_university.id in (" . implode(', ', $restrictions) . ') ';
        }

        $univer_sql = ' and sdt_university.id=' . intval($univer_id);

        $sql = 'SELECT sdt_act.*
				FROM
				sdt_university
				INNER JOIN sdt_act
				ON sdt_university.id = sdt_act.university_id
				INNER JOIN sdt_university_dogovor
				ON sdt_university_dogovor.id = sdt_act.university_dogovor_id
				WHERE
				  sdt_act.state=\'' . mysql_real_escape_string($level) . '\'

				AND sdt_act.deleted = 0
                ' . $univer_sql . '
                ' . $restrict . '
				ORDER BY
				 sdt_act.created desc,
				sdt_university.name
				, sdt_university_dogovor.number
				, sdt_act.testing_date';
        $result = mysql_query($sql) or die(mysql_error() . $sql);

        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new Act($row);
        }

//var_dump($list); die();
        return $list;
    }

    public static function getListByLevel4Head($level)
    {
        $list = new Acts();


        $restrict = '';
        $C = Controller::getInstance();
        $restrictions = $C->getUniversityRestrictionArray();
        // var_dump($restrictions);die();
        if ($restrictions) {
            $restrict = "
             and  sdt_university.id in (" . implode(', ', $restrictions) . ') ';
        }


        $sql = 'SELECT sdt_act.*
				FROM sdt_university
				INNER JOIN sdt_act
				ON sdt_university.id = sdt_act.university_id
				INNER JOIN sdt_university_dogovor
				ON sdt_university_dogovor.id = sdt_act.university_dogovor_id
				WHERE
				  sdt_act.state=\'' . mysql_real_escape_string($level) . '\'

				AND sdt_act.deleted = 0
                ' . $restrict . '
				ORDER BY
				 sdt_university.name,
				 sdt_act.last_state_update,
				 sdt_act.created desc

				';
        $result = mysql_query($sql) or die(mysql_error() . $sql);

        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new Act($row);
        }

        return $list;
    }

    public static function getListByHead($head,$till='')
    {
        $list = new Acts();


       // $restrict = '';
        //$C = Controller::getInstance();
       // $restrictions = $C->getUniversityRestrictionArray();
        // var_dump($restrictions);die();
       // if ($restrictions) {
//            $restrict = "
//             and  sdt_university.id in (" . implode(', ', $restrictions) . ') ';
//        }


        if (!empty($till)) $till=" and t1.created <= '".date('Y-m-d 23:59:59', strtotime($till))."'";
        else $till='';

        $sql = 'select t1.* from
                sdt_act as t1
                join sdt_university as t2 on t1.university_id=t2.id
                where
                t1.deleted=0
                and
                t2.head_id='.$head.'
                and t1.state in (\''.ACT::STATE_RECEIVED.'\',\''.ACT::STATE_PRINT.'\',\''.ACT::STATE_WAIT_PAYMENT.'\',\''.ACT::STATE_CHECK.'\' )
				'.$till;
//        die($sql);
        $result = mysql_query($sql) or die(mysql_error() . $sql);

        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new Act($row);
        }

        return $list;
    }


    public static function getListDeleted()
    {
        $list = new Acts();

        $levels=array();
        $levels[]="'".Act::STATE_RECEIVED."'";
        $levels[]="'".Act::STATE_PRINT."'";
        $levels[]="'".Act::STATE_WAIT_PAYMENT."'";
        $levels[]="'".Act::STATE_ARCHIVE."'";
        $levels=implode (',',$levels);


        $restrict = '';
        $C = Controller::getInstance();
//        $restrictions = $C->getUniversityRestrictionArray();
        $restrictions = '';
        // var_dump($restrictions);die();
        if ($restrictions) {
            $restrict = "
             and  sdt_university.id in (" . implode(', ', $restrictions) . ') ';
        }


        $sql = 'SELECT sdt_act.*
				FROM sdt_university
				INNER JOIN sdt_act
				ON sdt_university.id = sdt_act.university_id
				INNER JOIN sdt_university_dogovor
				ON sdt_university_dogovor.id = sdt_act.university_dogovor_id
				WHERE
				  sdt_act.state in('.$levels.')

				AND sdt_act.deleted = 1
                ' . $restrict . '
				ORDER BY
				 sdt_university.name,
				 sdt_act.last_state_update,
				 sdt_act.created desc

				';
//        die ($sql);
        $result = mysql_query($sql) or die(mysql_error() . $sql);
//var_dump($result);
        while ($row = mysql_fetch_assoc($result)) {

            $list[] = new Act($row);
        }
//die(var_dump($list));
        return $list;
    }



    public static function getListRework()
    {
        $list = new Acts();
        $level = 'init';

        $restrict = '';
        $C = Controller::getInstance();
        $restrictions = $C->getUniversityRestrictionArray();
        // var_dump($restrictions);die();
        if ($restrictions) {
            $restrict = "
             and  sdt_university.id in (" . implode(', ', $restrictions) . ') ';
        }


        $sql = 'SELECT sdt_act.*
				FROM sdt_university
				INNER JOIN sdt_act
				ON sdt_university.id = sdt_act.university_id
				INNER JOIN sdt_university_dogovor
				ON sdt_university_dogovor.id = sdt_act.university_dogovor_id
				WHERE
				  sdt_act.state=\'' . mysql_real_escape_string($level) . '\'
and
				 is_changed_checker = 1
				AND sdt_act.deleted = 0
                ' . $restrict . '
				ORDER BY
				 sdt_university.name,
				 sdt_act.last_state_update,
				 sdt_act.created desc';
        $result = mysql_query($sql) or die(mysql_error() . $sql);

        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new Act($row);
        }

        return $list;
    }


    public static function get4Buh($univer_id)
    {
        $list = new Acts();
        $univer_sql = '';

        $univer_sql = ' and sdt_university.id=' . intval($univer_id);

        $sql = 'SELECT sdt_act.*
				FROM
				sdt_university
				INNER JOIN sdt_act
				ON sdt_university.id = sdt_act.university_id
				INNER JOIN sdt_university_dogovor
				ON sdt_university_dogovor.id = sdt_act.university_dogovor_id
				WHERE
                  sdt_act.invoice > 0 and

				 sdt_act.deleted = 0
				' . $univer_sql . '
				ORDER BY
				 sdt_act.created desc,
				sdt_university.name
				, sdt_university_dogovor.number
				, sdt_act.testing_date';
        $result = mysql_query($sql) or die(mysql_error() . $sql);
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new Act($row);
        }

        return $list;
    }


}


class Act extends Model
{

    private $statuses = array(
        'init' => 'В работе',
        'send' => 'Проходят проверку',
        'checked' => 'Проверенные',
        'received' => 'Полученные',
        'check' => 'К печати',
        'print' => 'Печать',
        'wait_payment' => 'Ждут оплаты',
        'archive' => 'Архив',
    );

    const STATE_RECEIVED = 'received';
    const STATE_CHECK = 'check';
    const STATE_ARCHIVE = 'archive';
    const STATE_PRINT = 'print';
    const STATE_WAIT_PAYMENT = 'wait_payment';
    const STATE_INIT = 'init';
    const STATE_SEND = 'send';
    const STATE_CHECKED = 'checked';
    const STATE_NEED_CONFIRM = 'need_confirm';


    protected $_table = 'sdt_act';

    public $id;
    public $deleted;
    public $university_id;
    public $university_dogovor_id;
    public $testing_date;
    public $total_revenue;
    public $rate_of_contributions;
    public $amount_contributions;
    public $account;
    public $invoice_date;
    public $comment;
    public $number;
    public $paid;
    public $platez_number;
    public $platez_date;
    public $responsible;
    public $official;
    public $file_act_id;
    public $file_act_tabl_id;

    public $created;
    public $user_create_id;
    public $is_changed_checker;
    public $state;
    public $invoice;
    public $invoice_index;
    public $tester1;
    public $tester2;
    public $signing;

    public $check_date;

    public $blocked_flag;
    public $blocked_time;
    public $blocked_user;
    public $last_state_update;
    public $test_level_type_id;

    public $summary_table_id;

    public function __construct($input = false)
    {
        parent::__construct($input);
        if ($this->id) {
            //$this->checkAccess();
        }
    }

    public static function getInnerStates()
    {
        return array(
            ACT::STATE_RECEIVED,
            ACT::STATE_PRINT,
            ACT::STATE_WAIT_PAYMENT,
            ACT::STATE_CHECK,
            ACT::STATE_NEED_CONFIRM,
            ACT::STATE_ARCHIVE,
        );
    }

    static function getByID($id)
    {
        if (!is_numeric($id)) {
            return false;
        }

        $sql = 'select * from sdt_act where id=\'' . mysql_real_escape_string($id) . '\'';
        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return false;
        }
        $univer = new Act(mysql_fetch_assoc($result));

        return $univer;
    }

    public function setFields()
    {
        $this->fields = array(
            'id',
            'deleted',
            'university_id',
            'university_dogovor_id',
            'testing_date',
            'total_revenue',
            'rate_of_contributions',
            'amount_contributions',
            'account',
            'invoice_date',
            'invoice',
            'invoice_index',
            'paid',
            'comment',
            'platez_number',
            'platez_date',
            'responsible',
            'file_act_id',
            'file_act_tabl_id',
            'created',
            'number',
            'user_create_id',
            'state',
            'official',
            'is_changed_checker',
            'tester1',
            'tester2',
            'signing',
            'check_date',
            'blocked_flag',
            'blocked_time',
            'blocked_user',
            'last_state_update',
            'test_level_type_id',


        );

    }

    public function getEditFields()
    {
        return array(
            'testing_date',
            'total_revenue',
            'rate_of_contributions',
            'account',
            'invoice_date',
            'comment',
            'platez_number',
            'platez_date',
            'responsible',
            'number',
            'user_create_id',
            'official',
            'tester1',
            'tester2',
            'signing',
            'check_date',
        );
    }

    public function setFieldsTypes()
    {
        $this->fieldTypes = array(
            'testing_date' => 'date',
            'platez_date' => 'date',
            'invoice_date' => 'date',
            'last_state_update' => 'date',
            'comment' => 'text'

        );

    }

    public function getFkFields()
    {
        return array(
            'university_id',
            'university_dogovor_id',
            'amount_contributions',
            'paid',
            'file_act_id',
            'file_act_tabl_id',
            'state',
            'invoice',
            'invoice_index',
            'is_changed_checker',
            'blocked_flag',
            'blocked_time',
            'blocked_user',
            'test_level_type_id',
            'last_state_update',
        );
    }

    public function setTranslate()
    {
        $this->translate = array();
    }

    public function getUniversity()
    {
        return University::getByID($this->university_id);
    }

    public function getUniversityDogovor()
    {
        return University_dogovor::getByID($this->university_dogovor_id);
    }

    public function getTests()
    {
        return ActTests::get4Act($this->id);
    }

    /**
     * @return bool|TestLevelType
     */
    public function getTestLevelType()
    {
        return TestLevelType::getByID($this->test_level_type_id);
    }

    public function recountMoney()
    {
        $this->total_revenue = 0;
        $this->amount_contributions = 0;
        foreach ($this->getTests() as $test) {
            $this->total_revenue += $test->money;
            /*$this->amount_contributions += $test->getLevel()->price * $test->people_first;
            $this->amount_contributions += $test->getLevel()->sub_test_price * $test->people_subtest_retry;*/
            //$prices=ChangedPriceTestLevel::checkPrice($this->id);
            $this->amount_contributions += $test->getPrice() * $test->people_first;
            $this->amount_contributions += $test->getPriceSubTest() * $test->people_subtest_retry;
        }
        //$this->amount_contributions = round((($this->total_revenue * $this->rate_of_contributions) / 100), 2);

    }

    public function save()
    {
        if (!is_null($this->id) && $this->state == self::STATE_INIT) {
            $this->recountMoney();
        }


        $this->calculateState();

        return parent::save();
    }

    /**
     * @return ActMan[]
     */
    public function getPeople()
    {
        $people = array();
        foreach ($this->getTests() as $test) {
            /** @var ActTest $test */
            foreach ($test->getPeople() as $man) {
                $people[] = $man;
            }
        }

        return $people;
    }

    public function checkPassport()
    {
        $result = true;
        $people = $this->getPeople();
        if (!count($people)) {
            $result = false;
        }
        foreach ($people as $man) {
            if (!$man->passport_file) {
                $result = false;
            }
            if (!$man->total_percent) {
                $result = false;
            }
        }
        if (!strlen(trim($this->tester1)) || !strlen(trim($this->tester2))) {
            $result = false;
        }

        return $result;
    }

    public function getFileAct()
    {
        if ($this->file_act_id) {
            return File::getByID($this->file_act_id);
        }

        return false;
    }

    public function getFileActTabl()
    {
        if ($this->file_act_tabl_id) {
            return File::getByID($this->file_act_tabl_id);
        }

        return false;
    }

    public function getUserCreate()
    {

        return @mysql_result(
            mysql_query(
                "select concat(surname,' ',firstname,' ',fathername) from tb_users where u_id=" . $this->user_create_id
            ),
            0,
            0
        );
    }

    /**
     * @return ActTest[]
     */
    public function getLevels()
    {
        return ActTests::get4Act($this->id);
    }

    public function setStateSend()
    {
        $this->setState(self::STATE_SEND);
    }

    public function setStateChecked()
    {
        $this->check_date = date('Y-m-d');
        $this->setState(self::STATE_CHECKED);
    }

    /**
     * @deprecated
     */
    public function setStateFinished()
    {
        // $this->state = 'finished';
        $this->setState(self::STATE_RECEIVED);
    }

    public function setStateArchive()
    {
        $this->setState(self::STATE_ARCHIVE);
    }

    protected function checkAccess()
    {
        // return true;

        $C = Controller::getInstance();
        $restrictions = $C->getUniversityRestrictionArray();

        if (is_array($restrictions)) {
            if (!in_array($this->university_id, $restrictions)) {
                $C->redirectAccessRestricted();
            }
        }

    }

    public function getSigning()
    {
        if (is_numeric($this->signing)) {
            return ActSigning::getByID($this->signing);
        }

        return new ActSigning();
    }

    public function setState($state)
    {
        if ($this->state != $state && $this->isBlocked()) {
            $this->setUnBlocked();
        }


        $this->state = $state;
        $this->last_state_update = date('Y-m-d H:i');
        if ($this->id) {
            $q = "insert into sdt_act_status (act_id,status,user_id) values ('" . mysql_real_escape_string(
                $this->id
            ) . "','" . mysql_real_escape_string($this->state) . "','" . mysql_real_escape_string(
                $_SESSION['u_id']
            ) . "')";

            @mysql_query($q);
        }
    }


    public function getStateDate($state)
    {
        $q = "select timest from sdt_act_status where act_id='" . mysql_real_escape_string(
            $this->id
        ) . "' and status='" . mysql_real_escape_string($state) . "' order by id desc limit 1";
        $res = mysql_query($q);
        if (mysql_num_rows($res)) {
            $row = mysql_fetch_assoc($res);

            return $row['timest'];
        }

        return null;

    }

    protected function calculateState()
    {
        /*    if(!$this->invoice && !$this->signing){
                $this->setStateReceived();
            }*/
        if (!$this->state) {
            $this->setState(self::STATE_INIT);
        }

        if ($this->state == self::STATE_RECEIVED && $this->invoice && $this->signing) {
            $this->setState(self::STATE_CHECK);
        }

        if ($this->state == self::STATE_CHECK) {
            $sql = 'select count(*) from sdt_act_people where
         act_id=' . $this->id . ' and
         length(document_nomer)=0 ';
            //  echo $sql;
            $res = mysql_query($sql);
            if (mysql_num_rows($res)) {
                $document_numbers_left = mysql_result($res, 0, 0);
                if ($document_numbers_left == 0) {
                    $this->setState(self::STATE_PRINT);
                }
            }
        }
        if ($this->state == self::STATE_PRINT) {
            $sql = 'select count(*) from sdt_act_people where
         act_id=' . $this->id . ' and
        length(blank_number)=0 ';
            /*  $sql = 'select count(*) from sdt_act_people where
               act_id=' . $this->id . ' and
             ( length(blank_number)=0 or soprovod_file=0 or soprovod_file is null)';
          */ /*echo $sql;
            die();*/
            $res = mysql_query($sql);
            if (mysql_num_rows($res)) {
                $files_left = mysql_result($res, 0, 0);
                if ($files_left == 0) {
                    $this->setState(self::STATE_WAIT_PAYMENT);
                }
            }
        }
        if ($this->state == self::STATE_WAIT_PAYMENT && $this->paid) {
            $this->setState(self::STATE_ARCHIVE);
        }


    }

    public function isMigrantSession()
    {
        $tests = $this->getTests();
        foreach ($tests as $test) {
            if ($test->level_id == 1) {
                return true;
            }
        }

        return false;
    }


    public function setBlocked()
    {
        $this->blocked_time = date('Y-m-d H:i');
        $this->blocked_flag = 1;
        $this->blocked_user = $_SESSION['u_id'];
    }

    public function isBlocked()
    {
        return $this->blocked_flag;
    }

    public function isCanUnBlock()
    {
        //  var_dump($this->blocked_user,$this->id,$_SESSION['u_id']);
        if ($this->blocked_user == $_SESSION['u_id']) {
            return true;
        }
        $C = Controller::getInstance();

        if ($C->userHasRole(Roles::ROLE_UNBLOCK)) {
            return true;
        }

        return false;
    }

    public function isCanEdit()
    {
        //  var_dump($this->blocked_user,$this->id,$_SESSION['u_id']);
        if ($this->blocked_user == $_SESSION['u_id']) {
            return true;
        }

        return false;
    }

    public function setUnBlocked()
    {
        $this->blocked_time = date('Y-m-d H:i');
        $this->blocked_flag = 0;
        $this->blocked_user = null;
    }


    public function statusToText()
    {
        $statuses = array(
            'init' => 'В работе',
            'send' => 'Проходят проверку',
            'checked' => 'Проверенные',
            'received' => 'Полученные',
            'check' => 'К печати',
            'print' => 'Печать',
            'wait_payment' => 'Ждут оплаты',
            'archive' => 'Архив',
        );

        if (array_key_exists($this->state, $statuses)) {
            return $statuses[$this->state];
        }

        return $this->state;
    }


    public function isSetInvoice()
    {
        if (!$this->invoice || !$this->signing) {
            return false;
        }
        return true;
    }

    public function isSetBlanks()
    {

        if ($this->test_level_type_id != 2) {
            $sql = 'select count(*) from sdt_act_people where
         act_id=' . $this->id . ' and
        length(blank_number)=0 ';
        } else {
            $sql = 'select count(*) from sdt_act_people sap where
         act_id=' . $this->id . ' and
        (length(blank_number)=0 or (length(document_nomer)=0 and sap.document=\'certificate\' ))';
        }

        $res = mysql_query($sql);
        if (mysql_num_rows($res)) {
            $files_left = mysql_result($res, 0, 0);
            if ($files_left != 0) {
                return false;
            }
        }


        return true;
    }

    public function isPaid()
    {

        if (!$this->paid) {
            return false;
        }


        return true;
    }




    public function isToArchive()
    {



        if (!self::isPaid()) {
            return false;
        }

        if (!self::isSetInvoice()) {
            return false;
        }

        if (!self::isSetBlanks()) {
            return false;
        }

        return true;

    }



    public function isActPrinted()
    {
        if ($this->file_act_id || !empty(HTMLActFile::getByActID($this->id)->file_act_id)) return true;
        return false;
    }

    public function isActTablePrinted()
    {
        if ($this->file_act_tabl_id || !empty(HTMLActFile::getByActID($this->id)->file_act_tabl_id)) return true;
        return false;
    }

    public function isAllPrinted()
    {
        if ($this->isActTablePrinted() && ($this->isActPrinted() || $this->summary_table_id)) return true;
        return false;
    }

}