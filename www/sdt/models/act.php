<?php
require_once __DIR__ . '/collections/Acts.php';


class Act extends Model
{
    const STATE_RECEIVED = 'received';
    const STATE_CHECK = 'check';
    const STATE_ARCHIVE = 'archive';
    const STATE_PRINT = 'print';
    const STATE_WAIT_PAYMENT = 'wait_payment';
    const STATE_INIT = 'init';
    const STATE_SEND = 'send';
    const STATE_CHECKED = 'checked';
    const STATE_NEED_CONFIRM = 'need_confirm';
    static private $getByID = array();
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
    public $viewed = 0;
    public $date_received = 0;
    public $finally_check_date;
    public $ved_vid_cert_num;
    public $ved_vid_cert_num_date;
    public $is_printed = 0;
    public $checkRequiredError = '';
    public $summary_table_id;
    protected $_table = 'sdt_act';
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
    private $meta = null;

    public function __construct($input = false, $checkAccess = true)
    {
        parent::__construct($input);
        if ($this->id && $checkAccess) {
            $this->checkAccess();
        }
    }

    protected function checkAccess()
    {
        // return true;

        $C = AbstractController::getInstance();
        $restrictions = $C->getUniversityRestrictionArray();

        if (is_array($restrictions)) {
            if (!in_array($this->university_id, $restrictions)) {
                $C->redirectAccessRestricted();
            }
        }

    }

    public static function getLocalArchive($univer_id, $interval = '-2 month')
    {

        $date = date('Y-m-d', strtotime($interval));
        $sql = 'select sa.number, sa.id, sa.created, sa.state, sa.testing_date AS testing, sa.date_received as received,
  count(sap.id) as people,
  sum(
  if ((sap.document =\'' . ActMan::DOCUMENT_CERTIFICATE . '\' and sap.blank_number is not null and sap.blank_number <>\'\'),1,0)
  ) as print_certs,
sum(
  if ((sap.document =\'' . ActMan::DOCUMENT_NOTE . '\' and sap.blank_number is not null and sap.blank_number <>\'\'),1,0)
  ) as print_note

  from sdt_act  sa
  LEFT join sdt_act_people sap ON sap.act_id = sa.id
  WHERE
  sa.created >=\'' . $date . '\'
and  sa.deleted  = 0 AND sa.university_id = ' . intval($univer_id) . '
  and sap.deleted = 0
  AND sa.state IN (' . self::getStatesSql(self::getInnerStates()) . ')
  GROUP by sa.id
order BY created desc';
//die($sql);
        $con = Connection::getInstance();

        return $con->query($sql);
    }

    public static function getStatesSql($states, $quote = "'")
    {
        $c = Connection::getInstance();

        $items = array();
        foreach ($states as $s) {
            $items[] = $quote . $c->escape($s) . $quote;
        }

        return implode(', ', $items);
    }

    public static function getInnerStates()
    {
        return array(
            Act::STATE_RECEIVED,
            Act::STATE_PRINT,
            Act::STATE_WAIT_PAYMENT,
            Act::STATE_CHECK,
            Act::STATE_NEED_CONFIRM,
            Act::STATE_ARCHIVE,
        );
    }

    public static function getReceivedStates()
    {
        return array(
            Act::STATE_RECEIVED,
            Act::STATE_PRINT,
            Act::STATE_WAIT_PAYMENT,
            Act::STATE_CHECK,
            Act::STATE_NEED_CONFIRM,

        );
    }

    /**
     * @param $id
     * @return Act|bool
     */
    static function getByID($id, $checkAccess = true)
    {
        if (!is_numeric($id)) {
            return false;
        }
        if (array_key_exists($id, self::$getByID)) {
            return self::$getByID[$id];
        }
        $sql = 'SELECT * FROM sdt_act WHERE id=\'' . mysql_real_escape_string($id) . '\'';
        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return false;
        }
        $univer = new Act(mysql_fetch_assoc($result), $checkAccess);
        self::$getByID[$id] = $univer;

        return $univer;
    }

    public static function getActState($id)
    {
        $sql = 'SELECT state FROM sdt_act WHERE deleted=0 AND id = ' . $id;
        $result = mysql_query($sql);
        if (mysql_num_rows($result)) {
            return mysql_result($result, 0, 0);
        }

        return null;
    }

    public static function getActTestLevelID($id)
    {
        $sql = 'SELECT test_level_type_id FROM sdt_act WHERE id = ' . $id;
        $result = mysql_query($sql);
        if (mysql_num_rows($result)) {
            return mysql_result($result, 0, 0);
        }

        return null;
    }

    public static function isHeadCenter($id, $hc)
    {
        $sql = 'SELECT su.head_id  FROM sdt_act sa
LEFT JOIN sdt_university su ON su.id = sa.university_id
WHERE sa.id = ' . $id;
//        die($sql);
        $result = mysql_query($sql);
        if (mysql_num_rows($result)) {
            $act_hc = mysql_result($result, 0, 0);
//            var_dump($act_hc);
            return $act_hc == $hc;
        }

        return false;
    }

    public function isSignersSelect()
    {

        $university = $this->getUniversity();
        $parentdID = $university->parent_id ? $university->parent_id : $university->id;
        $resps = CenterSignings::getCenterAndType($parentdID, CenterSigning::TYPE_RESPONSIVE);
        $approve = CenterSignings::getCenterAndType($parentdID, CenterSigning::TYPE_APPROVE);
        return $resps->ifCaptionExist($this->responsible) && $approve->ifCaptionExist($this->official);

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
            'viewed',
            'date_received',
            'is_printed',
            'finally_check_date',
            'ved_vid_cert_num',
            'ved_vid_cert_num_date',
            'summary_table_id',

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
            'ved_vid_cert_num',
            'ved_vid_cert_num_date',

        );
    }

    public function setFieldsTypes()
    {
        $this->fieldTypes = array(
            'testing_date' => 'date',
            'platez_date' => 'date',
            'invoice_date' => 'date',
            'finally_check_date' => 'date',
            'last_state_update' => 'date',
            'comment' => 'text'

        );

    }

    public function getFkFields()
    {
        return array(
            'university_id',
            'summary_table_id',
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
            'viewed',
            'date_received',
            'is_printed',
            'finally_check_date',
        );
    }

    public function incrementViewedAndSave()
    {
        $this->viewed++;
        $this->save();
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
     * Пересчет стоимости всего экзамена с учетом бесплатных пересдач
     */
    public function recountMoney()
    {
        $this->total_revenue = 0;
        $this->amount_contributions = 0;
        foreach ($this->getTests() as $test) {
            $this->total_revenue += $test->money;
            /*$this->amount_contributions += $test->getLevel()->price * $test->people_first;
            $this->amount_contributions += $test->getLevel()->sub_test_price * $test->people_subtest_retry;*/
            //$prices=ChangedPriceTestLevel::checkPrice($this->id);
            $this->amount_contributions += $test->getMoneyFirst();
            $this->amount_contributions += $test->getMoneyRetry_1();
            $this->amount_contributions += $test->getMoneyRetry_2();
        }
        //$this->amount_contributions = round((($this->total_revenue * $this->rate_of_contributions) / 100), 2);

    }

    public function getTests()
    {
        return ActTests::get4Act($this->id);
    }

    protected function calculateState()
    {
        /*    if(!$this->invoice && !$this->signing){
                $this->setStateReceived();
            }*/
        if (!$this->state) {
            $this->setState(self::STATE_INIT);
        }

        /* if ($this->state == self::STATE_RECEIVED && $this->invoice && $this->signing) {
             $this->setState(self::STATE_PRINT);
         }



         if ($this->state == self::STATE_PRINT) {
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
                 if ($files_left == 0) {
                     $this->setState(self::STATE_WAIT_PAYMENT);
                 }
             }
         }
         if ($this->state == self::STATE_WAIT_PAYMENT && $this->paid) {
             $this->setState(self::STATE_ARCHIVE);
         }*/


    }

    public function setState($state)
    {
        if ($this->state != $state && $this->isBlocked()) {
            $this->setUnBlocked();
        }

        $this->viewed = 0;

        $this->state = $state;
        $date = date('Y-m-d H:i');
        $this->last_state_update = $date;


        if ($this->id) {
            $q = "INSERT INTO sdt_act_status (act_id,status,user_id) VALUES ('" . mysql_real_escape_string(
                    $this->id
                ) . "','" . mysql_real_escape_string($this->state) . "','" . mysql_real_escape_string(
                    $_SESSION['u_id']
                ) . "')";

            @mysql_query($q);
        }
    }

    public function isBlocked()
    {
        return $this->blocked_flag;
    }

    public function setUnBlocked()
    {
        $this->blocked_time = date('Y-m-d H:i');
        $this->blocked_flag = 0;
        $this->blocked_user = null;
    }

    public function setTranslate()
    {
        $this->translate = array();
    }

    public function getUniversityDogovor()
    {
        return University_dogovor::getByID($this->university_dogovor_id);
    }

    /**
     * @return bool|TestLevelType
     */
    public function getTestLevelType()
    {
        return TestLevelType::getByID($this->test_level_type_id);
    }

    public function setPrinted()
    {
        $this->is_printed = 1;
        $sql = 'UPDATE sdt_act SET is_printed  = 1 WHERE id =  ' . intval($this->id);
        $c = Connection::getInstance();

        $c->execute($sql);


    }

    public function checkRequiredFields(&$errors = array())
    {
//        $result = true;


        $notApiRole = true;

        $errors = array();

        if (!$this->isSignersSelect() && !Roles::getInstance()->userHasRole(Roles::ROLE_CENTER_EXTERNAL_API)) {
            $errors[] = htmlspecialchars('заполните поля "Должностное лицо, утверждающее акт" и "Ответственный за проведение тестрования" лицами из действующего списка');
        }

        if (strtotime($this->testing_date) > strtotime(date("Y-m-d"))) {
            $errors[] = htmlspecialchars('некорректное заполнение поля "дата тестирования" в акте');
        }

        if (!strlen(trim($this->tester1)) || !strlen(trim($this->tester2))) {
            $errors[] = 'не указаны тесторы';
        }

        $people = $this->getPeople();
        if (!count($people)) {
            $errors[] = 'не заполнены тестируемые';
        }

        $blanksCert = array();
        $regCert = array();
        $note = array();

        $certRegPrefix = CERTIFICATE_REG_NUMBER_PREFIX;
//var_dump($certRegPrefix);


        /*if (Roles::getInstance()->userHasRole(Roles::ROLE_CENTER_PFUR_API)) {
            if ($this->test_level_type_id == 2 && empty($this->summary_table_id)) {
                $errors[] = 'не сформирован сводный протокол';
            }
        }*/ //29.11.17

        if (count($people)) {
            foreach ($people as $man) {

//            var_dump($man->passport_date);

                if ($notApiRole) {
                    if (!$man->passport_file) {
                        $errors[] = 'не загружен паспорт';
//                        return false;
                    }
                    if ($this->test_level_type_id == 2 && !\SDT\models\PeopleStorage\ManFile::getByUserType($man->id, \SDT\models\PeopleStorage\ManFile::TYPE_PHOTO)) {
                        $errors[] = 'не загружена фотография';
//                        return false;
                    }
                }
                if (!$man->country_id) {
                    $errors[] = 'не указано гражданство';
                }
                if (!$man->total_percent) {
                    $errors[] = 'не введены баллы';
                }
                if (!strlen(trim($man->surname_rus)) || !strlen(trim($man->name_rus))) {
                    $errors[] = 'не указаны фио на русском';
                }
                if (!strlen(trim($man->surname_lat)) || !strlen(trim($man->name_lat))) {
                    $errors[] = 'не указаны фио латиницей';

                }
                if (strtotime($man->testing_date) > strtotime(date("Y-m-d"))) {
                    $errors[] = htmlspecialchars('некорректное заполнение поля "дата тестирования"');
                }

                if (
                    !strlen(trim($man->passport))
//                || !strlen(trim($man->passport_series))
                    || !strlen(trim($man->passport_name))
//                || !strlen(trim($man->passport_date))
//                || $man->passport_date == '0000-00-00'
                ) {

                    $errors[] = 'не заполены паспортные данные';
                }

                if (

                    !strlen(trim($man->birth_date))
                    || $man->birth_date == '0000-00-00'
                ) {
                    $errors[] = 'не указана дата рождения';

                }


                if ($man->getAdditionalExam()) {
                    if (!strlen(trim($man->getAdditionalExam()->old_blank_number))) {
                        $errors[] = 'не заполнен старый бланк сертфиката';
                    }

                    if ($notApiRole) {

                        if (!$man->getAdditionalExam()->old_blank_scan && !$man->getAdditionalExam()->cert_exists) {
                            $errors[] = 'не загружен старый бланк сертфиката';
                        }
                    }
                }


                if (Roles::getInstance()->userHasRole(Roles::ROLE_CENTER_EXTERNAL_API)) {


                    if ($man->isCertificate()) {

                        if (!strlen(trim($man->blank_number))) {
                            $errors[] = 'не заполнен бланк сертфиката';
                        }


                        $blanksCert[] = $man->blank_number;
                        if ($this->test_level_type_id == 2) {
                            if (!strlen(trim($man->document_nomer))) {

                                $errors[] = 'не заполнен регистрационный номер';

                            }

                            if (strpos($man->document_nomer, $certRegPrefix) !== 0) {
//                            var_dump($man->document_nomer);
                                $errors[] = 'регистрационный номер не соответсвует центру';
                            }

                            $regCert[] = $man->document_nomer;

                        }
                        if (

                            !strlen(trim($man->blank_date))
                            || $man->blank_date == '0000-00-00'
                        ) {
                            $errors[] = 'не заполнена дата печати бланка';
                        }

                    }
                    if ($man->isNote()) {
                        if (!strlen(trim($man->blank_number))) {
                            $errors[] = 'не заполнен бланк справки';
                        }

                        $note[] = $man->blank_number;

                    }

                    if (count($blanksCert) != count(array_unique($blanksCert))) {
                        $errors[] = 'имеются дубли бланков сертификатов';
                    }
                    if (count($regCert) != count(array_unique($regCert))) {
                        $errors[] = 'имеются дубли регистрационных номеров сертификатов';
                    }
                    if (count($note) != count(array_unique($note))) {
                        $errors[] = 'имеются дубли  номеров справок';
                    }
                }
//            var_dump($man->id,$man->surname_rus);
            }
        }


        $dogovors = $this->getUniversity()->getDogovorsByType($this->test_level_type_id);
        $haveDogovor = false;
        foreach ($dogovors as $dog) {
            if ($this->university_dogovor_id == $dog->id) {
                $haveDogovor = true;
            }
        }
        if (!$haveDogovor) {
            $errors[] = 'Не выбран договор на тестирование';
        }


        $errors = array_unique($errors);

//        var_dump($errors);

        return !count($errors);
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

    public function getUniversity()
    {
        return University::getByID($this->university_id, true);
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
                "SELECT concat(surname,' ',firstname,' ',fathername) FROM tb_users WHERE u_id=" . $this->user_create_id
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
        $this->date_received = date('Y-m-d H:i');
    }

//check_date
    public function getPrintDateAfterCheckDate(DateTime $now = null, $withTime = false)
    {

        if (is_null($now)) {
            $now = new DateTime();
        }
        $time = '';
        if ($withTime) {
            $time = date(' H:i:s');
        }
        if ($this->getUniversity()->getHeadCenter()->horg_id == 1) {

            $checkDate = new DateTime($this->check_date);
            if ($now >= $checkDate) {
                return $now->format('Y-m-d') . $time;
            } else {
                return $checkDate->format('Y-m-d') . $time;
            }
        }
        return $now->format('Y-m-d') . $time;
    }

//check_date
    public function setStateChecked()
    {
//        throw new Exception('NO CHECKED STATUS');


        $dayNow = date('j'); // День месяца без ведущего нуля
        $days = date('t');  // Количество дней в указанном месяце
        $this->check_date = date('Y-m-d');

        //checked date hack
//        var_dump($this->getUniversity()->getHeadCenter()->horg_id);
//     die;
        /*       if ($this->getUniversity()->getHeadCenter()->horg_id == 1) {

                   if (($days - $dayNow) >= PFUR_CHECKED_CHANGE_DATE) {
                       $this->check_date = date('Y-m-d');
                   } else {
                       $dt = new DateTime('+' . PFUR_CHECKED_CHANGE_DATE . ' days');

                       $firstDay = 1;
                       if (12 == date('n')) {
                           $firstDay = 3;
                       }


                       $this->check_date = $dt->format('Y-m-' . $firstDay);
                   }

               }*/

        $this->setState(self::STATE_RECEIVED);
    }

    /**
     *
     */

    public function setStateFinished()
    {
        // $this->state = 'finished';
        $this->setState(self::STATE_RECEIVED);

        $this->date_received = date('Y-m-d H:i');


    }

    public function setStateApiFinished()
    {
        $this->setState(self::STATE_ARCHIVE);

        $this->date_received = date('Y-m-d H:i');

        return $this->checkApiUniqCertificate();
    }

    public function setStateApiReceived()
    {
        $this->setState(self::STATE_RECEIVED);

        $this->date_received = date('Y-m-d H:i');
        $this->check_date = date('Y-m-d H:i');

        return $this->checkPfurApiCertificate();
    }

    protected function checkApiUniqCertificate()
    {
        $errors = array(
            'certificate' => array(),
            'note' => array(),
            'nope' => array(),
        );
        $haveError = false;
        foreach ($this->getPeople() as $man) {
            $aud = $man->getApiUserData();
            $fio = $man->getSurname_rus() . ' ' . $man->getName_rus();
            if (!$aud) {

                $errors['nope'][] = $fio;
                $haveError = true;
                continue;
            }
            if ($man->document != $aud->doc_type) {
                $errors[$aud->doc_type][] = $fio;
                $haveError = true;
            }
        }

        if ($haveError) {
            $message = '';
            if (count($errors['nope'])) {
                $message .= "У перечисленных ниже тестируемых ну указан при загрузке тип документа:<br>" . implode(
                        ', ',
                        $errors['nope']
                    ) . "<br>";
            }
            if (count($errors['certificate'])) {
                $message .= "У перечисленных ниже тестируемых введенные баллы не соответствуют установленному при загрузке типу документа - СЕРТИФИКАТ:<br>" . implode(
                        ', ',
                        $errors['certificate']
                    ) . "<br>";
            }
            if (count($errors['note'])) {
                $message .= "У перечисленных ниже тестируемых введенные баллы не соответствуют установленному при загрузке типу документа - СПРАВКА:<br>" . implode(
                        ', ',
                        $errors['note']
                    ) . "<br>";
            }
            throw new ApiException($message);

        }

//        $people = $this->getPeople();
        $docs = $this->getPeopleDocNumbers();
        $blanks = array_column($docs, 'blank_number', 'id');
//        var_dump($blanks);
        $this->checkBlanks($blanks);


        if ($this->test_level_type_id == 2) {
            $docs = array_column($docs, 'document_nomer', 'id');
//            var_dump($docs);
            $this->checkDocNumbers($docs);
        }
//        die;
//        throw new ApiException('Stop');


        return true;
    }

    protected function checkPfurApiCertificate()
    {
        $errors = array(
            'certificate' => array(),
            'note' => array(),
            'nope' => array(),
        );
        $haveError = false;
        foreach ($this->getPeople() as $man) {
            $aud = $man->getApiUserData();
            $fio = $man->getSurname_rus() . ' ' . $man->getName_rus();
            if (!$aud) {

                $errors['nope'][] = $fio;
                $haveError = true;
                continue;
            }
            if ($man->document != $aud->doc_type) {
                $errors[$aud->doc_type][] = $fio;
                $haveError = true;
            }
        }

        if ($haveError) {
            $message = '';
            if (count($errors['nope'])) {
                $message .= "У перечисленных ниже тестируемых ну указан при загрузке тип документа:<br>" . implode(
                        ', ',
                        $errors['nope']
                    ) . "<br>";
            }
            if (count($errors['certificate'])) {
                $message .= "У перечисленных ниже тестируемых введенные баллы не соответствуют установленному при загрузке типу документа - СЕРТИФИКАТ:<br>" . implode(
                        ', ',
                        $errors['certificate']
                    ) . "<br>";
            }
            if (count($errors['note'])) {
                $message .= "У перечисленных ниже тестируемых введенные баллы не соответствуют установленному при загрузке типу документа - СПРАВКА:<br>" . implode(
                        ', ',
                        $errors['note']
                    ) . "<br>";
            }
            throw new ApiException($message);

        }
        return true;
    }

    private function getPeopleDocNumbers()
    {
        $sql = 'SELECT id, document_nomer, blank_number FROM sdt_act_people
 WHERE document=\'' . ActMan::DOCUMENT_CERTIFICATE . '\'  AND  act_id=' . intval(
                ($this->id)
            );

        $res = Connection::getInstance()->query($sql);
        if (!$res) {
            return array();
        }

        return $res;
    }

    private function checkBlanks(array $blanks)
    {
        $q = array();
        foreach ($blanks as $b) {
            $q[] = "'" . $b . "'";
        }

        $c = Connection::getInstance();
        $sql = 'SELECT cr.number FROM certificate_reserved cr WHERE cr.number IN (' . implode(
                ',',
                $q
            ) . ') AND cr.test_type_id = ' . $this->test_level_type_id;

        $res = $c->query($sql);
        if (count($res)) {
            $bl = implode(', ', array_column($res, 'number'));

            throw new ApiException(
                'Бланки сертификатов: ' . $bl . ' - уже зарезервированы в системе другим Головным центром'
            );

        }

        $states = array();
        foreach (self::getInnerStates() as $b) {
            $states[] = "'" . $b . "'";
        }
        $c = Connection::getInstance();
        $sql = 'SELECT sap.blank_number FROM sdt_act_people sap
LEFT JOIN sdt_act sa ON sa.id = sap.act_id
WHERE sa.state IN (' . implode(',', $states) . ')
AND sap.document=\'' . ActMan::DOCUMENT_CERTIFICATE . '\'
AND sa.deleted = 0
AND sap.deleted = 0
AND sap.blank_number IN (' . implode(
                ',',
                $q
            ) . ') AND sa.test_level_type_id = ' . $this->test_level_type_id;

        $res = $c->query($sql);
        if (count($res)) {
            $bl = implode(', ', array_column($res, 'blank_number'));
//            $bl = implode(', ', $res);
            throw new ApiException('Бланки: ' . $bl . ' - уже выданы');

        }
//        throw new ApiException($sql . '|' . intval($res['cc']));
    }

    private function checkDocNumbers(array $numbers)
    {
        $q = array();
        foreach ($numbers as $b) {
            $q[] = "'" . $b . "'";
        }
        $states = array();
        foreach (self::getInnerStates() as $b) {
            $states[] = "'" . $b . "'";
        }

        $sql = 'SELECT sap.document_nomer FROM sdt_act_people sap
LEFT JOIN sdt_act sa ON sa.id = sap.act_id
WHERE sa.state IN (' . implode(',', $states) . ')
AND sap.document=\'' . ActMan::DOCUMENT_CERTIFICATE . '\'
AND sa.deleted = 0
AND sap.deleted = 0
AND sap.document_nomer IN (' . implode(
                ',',
                $q
            ) . ') AND sa.test_level_type_id = ' . $this->test_level_type_id;

        $c = Connection::getInstance();
        $res = $c->query($sql);

        if (count($res)) {
            $bl = implode(', ', array_column($res, 'document_nomer'));

            throw new ApiException(
                'Регистрационные номера сертификатов:
             ' . $bl . ' - уже выданы'
            );

        }


    }

    public function setStateArchive()
    {
        $this->setState(self::STATE_ARCHIVE);
    }

    public function getSigning()
    {
        if (is_numeric($this->signing)) {
            return ActSigning::getByID($this->signing);
        }

        return new ActSigning();
    }

    public function getStateDate($state)
    {
        $q = "SELECT timest FROM sdt_act_status WHERE act_id='" . mysql_real_escape_string(
                $this->id
            ) . "' AND status='" . mysql_real_escape_string($state) . "' ORDER BY id DESC LIMIT 1";
        $res = mysql_query($q);
        if (mysql_num_rows($res)) {
            $row = mysql_fetch_assoc($res);

            return $row['timest'];
        }

        return null;

    }

    public function isBlanksEmpty()
    {

        if ($this->test_level_type_id != 2) {
            $sql = 'SELECT count(*) FROM sdt_act_people WHERE
         act_id=' . $this->id . ' AND
        length(blank_number)>0 ';
        } else {
            $sql = 'SELECT count(*) FROM sdt_act_people sap WHERE
         act_id=' . $this->id . ' AND
        (length(blank_number)>0 OR (length(document_nomer)>0 AND sap.document=\'certificate\' ))';
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
//var_dump($this->university_id);
        $univer = $this->getUniversity();
//        var_dump($univer);
        $apiEnabled = $univer->api_enabled;
        if ($apiEnabled && $this->isSetBlanks()) {
            return true;
        }

        $headOrgID = $univer->getHeadCenter()->horg_id;
        if ($headOrgID == 1) {
            return $this->isSetBlanks() && $this->isSetInvoice();
        }


        return $this->isSetBlanks();

        /*  {
              if (!self::isPaid()) {
                  return false;
              }
          }

          if (!self::isSetInvoice()) {
              return false;
          }

          if (!self::isSetBlanks()) {
              return false;
          }

          return true;*/

    }

    public function isSetBlanks()
    {

        if ($this->test_level_type_id != 2) {
            $sql = 'SELECT count(*) FROM sdt_act_people WHERE
         act_id=' . $this->id . ' AND
        length(blank_number)=0 ';
        } else {
            $sql = 'SELECT count(*) FROM sdt_act_people sap WHERE
         act_id=' . $this->id . ' AND
        (length(blank_number)=0 OR (length(document_nomer)=0 AND sap.document=\'certificate\' ))';
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

    public function isSetInvoice()
    {
        if (!$this->invoice || !$this->signing) {
            return false;
        }

        return true;
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

    public function isCanUnBlock()
    {
        //  var_dump($this->blocked_user,$this->id,$_SESSION['u_id']);
        if ($this->blocked_user == $_SESSION['u_id']) {
            return true;
        }
        $C = AbstractController::getInstance();

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

    public function canBeChecked()
    {
        $sql = 'SELECT COUNT(*) AS need_to_approve FROM sdt_act_people sap
  INNER JOIN people_additional_exam pam ON pam.man_id = sap.id

  WHERE sap.act_id = ' . $this->id . ' AND pam.approve=0
  AND pam.cert_exists = 0';
        $res = Connection::getInstance()->query($sql, true);

        return !$res['need_to_approve'];
    }

    public function canBeSended()
    {
        $sql = '

		SELECT COUNT(*) AS need_to_approve
FROM sdt_act_people sap
INNER JOIN people_additional_exam pam ON pam.man_id = sap.id
WHERE sap.act_id = ' . $this->id . '
 AND (pam.old_blank_scan = 0
  OR pam.old_blank_scan IS NULL
   OR length(trim(pam.old_blank_number)) = 0
	 OR pam.old_blank_number IS NULL)
	    AND cert_exists = 0';

        $res = Connection::getInstance()->query($sql, true);

//		die($sql);
        return !$res['need_to_approve'];
    }

    public function haveAdditionalExam()
    {
        $sql = 'SELECT COUNT(*) AS need_to_approve FROM sdt_act_people sap
  INNER JOIN people_additional_exam pam ON pam.man_id = sap.id

  WHERE sap.act_id = ' . $this->id . '
  AND pam.cert_exists = 0';
        $res = Connection::getInstance()->query($sql, true);

        return $res['need_to_approve'];
    }

    public function getMeta()
    {
        if (is_null($this->meta)) {
            $this->meta = ActMetaData::getByActId($this->id);
            if (!$this->meta) {
                $this->meta = new ActMetaData();
            }
        }

        return $this->meta;
    }

    public function setFinallyChecked()
    {
        if (is_null($this->finally_check_date) || $this->finally_check_date == '0000-00-00 00:00:00') {
            $this->finally_check_date = date('Y-m-d H:i');
        }
    }

    public function setVedVidCertNum()
    {
        if (empty($this->ved_vid_cert_num) && $this->test_level_type_id == 2 && $this->getUniversity()->getHeadCenter()->horg_id == 1) {
            $ved_vid_cert_num = PrintNumberList::generate('ved_vid_cert', 'lc' . $this->getUniversity()->id);
            $this->ved_vid_cert_num = $ved_vid_cert_num->getNumber();
//            $this->ved_vid_cert_num_date = date("Y-m-d H:i:s");
            $this->ved_vid_cert_num_date = $this->getPrintDateAfterCheckDate(null, true);
            return true;
        }
        return false;
    }

    public function checkBlanksNums()
    {
        $sql = "SELECT count(id) AS have_blank_num FROM sdt_act_people sap WHERE sap.blank_number <> '' AND sap.deleted = 0 AND sap.act_id = " . $this->id;
        $res = Connection::getInstance()->query($sql, true);

        return $res['have_blank_num'];
    }

    public function delete()
    {

        $q = "INSERT INTO sdt_act_status (act_id,status,user_id) VALUES (" . intval(
                $this->id
            ) . ",'deleted'," . intval(Session::getUserID()) . ")";

        @mysql_query($q);
        foreach ($this->getPeople() as $man) {
            if ($man->isRetry() && $retr = $man->getRetryInfo()) {
                $retr->delete();
            }
        }
        return parent::delete();

    }

    public function allow_summary_table()
    {
        if ($this->getUniversity()->getHeadCenter()->horg_id == 1) return true;
        else return false;
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

    public function actDate()
    {
        return $this->check_date;
    }

    public function invoicePrinted()
    {
        return !!strlen($this->invoice);
    }

    public function isBaseForForeign()
    {
        $tests = $this->getTests();
        $test = current($tests);
        /** @var ActTest $test */
        return $test->level_id == 1;
    }
}