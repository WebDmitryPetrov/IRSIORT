<?php
/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 12.01.2015
 * Time: 10:34
 */
require_once 'AbstractController.php';
require_once __DIR__.'/actions/DublArchiveTrait.php';
class Dubl extends AbstractController
{
 use DublArchiveTrait;

    const DUBL_PRICE = 295;
    private static $instance;

    protected function __construct()
    {
        parent::__construct();

    }

    static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function dubl_show_action()
    {
        $dubl = $this->getDublAct();
        $people = $dubl->getPeople();
        return $this->render->view('dubl/people', array(
            'dubl' => $dubl,
            'people' => $people,
        ));
    }

    private function getDublAct()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
//        var_dump($id); die;
        if (!$id) {
            Session::setFlash('Не найден id');
            $this->redirectAccessRestricted();
        }

        $dubl = DublAct::getByID($id);
        if (!$dubl) {
            Session::setFlash('Не найден запрос на дубликат');
            $this->redirectAccessRestricted();
        }
        if ($lc = Session::getLocalCenterID()) {
            if ($lc != $dubl->center_id) {
//                Session::setFlash('Y');
                $this->redirectAccessRestricted();
            }
        } else {
            if ($dubl->getLocalCenter()->head_id != CURRENT_HEAD_CENTER) {
                $this->redirectAccessRestricted();
            }
        }

        return $dubl;
    }

    public function dubl_man_search_action()
    {
        $dubl = $this->getDublAct();
        $surname = $name = $blank = '';
        $result = null;
        if (count($_POST)) {
            $surname = filter_input(INPUT_POST, 'surname', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $blank = filter_input(INPUT_POST, 'blank', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $result = DublActManList::Search($surname, $name, $blank);
        }
        /*var_dump($surname, $name, $blank);
        if ($surname or $name or $blank) {
            die(var_dump($surname, $name, $blank));
        }*/

//        var_dump($result);die();

        return $this->render->view('dubl/man_search', array(
            'dubl' => $dubl,

            'result' => $result,
            'surname' => $surname,
            'name' => $name,
            'blank' => $blank
        ));
    }

    public function dubl_man_add_action()
    {

        $dubl = $this->getDublAct();
        $old_id = filter_input(INPUT_GET, 'man_id', FILTER_VALIDATE_INT);
        $old_man = ActMan::getByID($old_id);
        $old_man = CertificateDuplicate::checkForDuplicates($old_man);

        /*if (Act::getActState($old_man->act_id) != Act::STATE_ARCHIVE) {
            Session::setFlash('Оригинал тестовой сессии в головном центре находится в работе (не в архиве).');
            $this->redirectReturn();
        }*/
//die(var_dump($dubl->id));

        $new_man = new DublActMan();
        $new_man->act_id = $dubl->id;
        $new_man->old_man_id = $old_id;
        $new_man->surname_rus_old = $old_man->getSurname_rus();
        $new_man->surname_lat_old = $old_man->getSurname_lat();
        $new_man->name_rus_old = $old_man->getName_rus();
        $new_man->name_lat_old = $old_man->getName_lat();
        $new_man->save();
//die(var_dump($new_man));
        $this->redirectByAction('dubl_show', array('id' => $dubl->id), 'dubl');


    }

    public function dubl_man_edit_action()
    {
        $dubl = $this->getDublAct();
        $old_id = filter_input(INPUT_GET, 'man_id', FILTER_VALIDATE_INT);
        $man = DublActMan::getByID($old_id);

        if (count($_POST)) {
            $surname_rus = filter_input(INPUT_POST, 'surname_rus', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $surname_lat = filter_input(INPUT_POST, 'surname_lat', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $name_rus = filter_input(INPUT_POST, 'name_rus', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $name_lat = filter_input(INPUT_POST, 'name_lat', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

            if ($surname_rus != $man->surname_rus_old ||
                $surname_lat != $man->surname_lat_old ||
                $name_rus != $man->name_rus_old ||
                $name_lat != $man->name_lat_old
            ) {
                $man->surname_rus_new = $surname_rus;
                $man->surname_lat_new = $surname_lat;
                $man->name_rus_new = $name_rus;
                $man->name_lat_new = $name_lat;
                $man->is_changed = 1;
            }


            if (!empty($_FILES['passport'])) {
                $_file = $_FILES['passport'];
                if (!empty($_file['name'])) {


                    $file = new File();
                    $id = $file->upload($_file, $error);
                    //$man = $act;
                    if (empty($_SESSION['flash'])) {
                        $_SESSION['flash'] = '';
                    }
                    $_SESSION['flash'] .= '<strong>Файл паспорта загружен</strong>: ' . $error . '<br>';
                    if ($id) {


                        if ($man->file_passport_id) {
                            $oldFile = File::getByID($man->file_passport_id);
                            $oldFile->delete();
                        }
                        $man->file_passport_id = $id;
//                            $man->save();
                    }
                }


            }
//            else echo 2;
//            die();
            $man->save();
            $this->redirectByAction('dubl_show', array('id' => $dubl->id), 'dubl');
        }


        return $this->render->view('dubl/man_edit', array(
            'man' => $man,
        ));


    }

    public function dubl_man_delete_action()
    {
        $dubl = $this->getDublAct();
        $old_id = filter_input(INPUT_GET, 'man_id', FILTER_VALIDATE_INT);
        $man = DublActMan::getByID($old_id);
        if (!empty($man) && $man->act_id = $dubl->id) {
            $man->delete();
        }

        $this->redirectByAction('dubl_show', array('id' => $dubl->id), 'dubl');

    }

    public function dubl_act_accept_action()
    {
        $dubl = $this->getDublAct();
        $signingsInvoice = ActSignings::get4Invoice();

        $dubl->accepted_date = date('Y-m-d H:i:s');
        $dayNow = date('j');
        $days = date('t');
        //check_date
        if ($dubl->getUniversity()->getHeadCenter()->horg_id == 1) {


            if (($days - $dayNow) >= PFUR_CHECKED_CHANGE_DATE) {
                $dubl->accepted_date = date('Y-m-d H:i:s');
            } else {

                $dt = new DateTime('+' . PFUR_CHECKED_CHANGE_DATE . ' days');
                $firstDay = 1;
                if (12 == date('n')) {
                    $firstDay = 3;
                }
                $dubl->accepted_date = $dt->format('Y-m-' . $firstDay) . date(' H:i:s');
            }


        }


        if (count($_POST)) {
            $dubl->parseParameters($_POST);
            $dubl->invoice_date = $this->mysql_date($_POST['invoice_date']);
            $dubl->invoice_price = str_replace(',', '.', $dubl->invoice_price);


            /*start*/
//check_date

            if ($dubl->getUniversity()->getHeadCenter()->horg_id == 1) {
                $now = new DateTime($dubl->invoice_date);
                $checkDate = new DateTime($dubl->accepted_date);
                if ($now < $checkDate) {
                    $dubl->invoice_date = $checkDate->format('Y-m-d');
                }
            }

            /*end*/


            $empty_fields = 0;
            foreach ($dubl as $key => $value) {
                if ($key != 'comment'
                    && $key != 'deleted'
                    && $key != 'file_request_id'
                    && $key != 'summary_table_id'
                    && empty($value)) {
                    $empty_fields++;
                }

            }
            if ($empty_fields == 0) {
                $dubl->state = DublAct::STATE_IN_HC;
            }

            $dubl->save();

            $this->redirectByAction('dubl_act_list', array('uid' => $dubl->center_id, 'type' => $dubl->test_level_type_id), 'dubl');
        }


        return $this->render->view(
            'dubl/invoice_form',
            array(
                'act' => $dubl,
                'signings' => $signingsInvoice,
            )
        );

    }

    public function dubl_act_processed_action()
    {

        $dubl = $this->getDublAct();
        $dubl->state = DublAct::STATE_PROCESSED;
        $dubl->save();

        $this->redirectReturn();


    }

    public function dubl_act_table_action()
    {
        $dubl = $this->getDublAct();
        $people = $dubl->getPeople();
        return $this->render->view('dubl/act_table', array(
            'dubl' => $dubl,
            'people' => $people,
        ));
    }

    public function loc_archive_action()
    {
        $data = DublActList::getLocalArchive(Session::getLocalCenterID());

//        die(var_dump($data));
        return $this->render->view(
            'local/archive_dubl',
            array(

                'items' => $data ? $data : array(),

            )
        );
    }

    protected function dubl_action()
    {

        $type = filter_input(INPUT_GET, 'type', FILTER_VALIDATE_INT);

        if (empty($type)) {
            $types = TestLevelTypes::getAll();
            return $this->render->view(
                'dubl/choose_test_type',
                array(
                    'Types' => $types,
                    'Legend' => 'Выбрать тип проведенного тестированимя',


                )
            );
        }

        if (!TestLevelType::getByID($type)) {
            $this->redirectByAction('dubl', array(), 'dubl');
        }

        $list = DublActList::get4LocalCenter(Session::getLocalCenterID(), $type);
//        var_dump($list);
        return $this->render->view('dubl/list', array(
            'list' => $list,
            'type' => $type
        ));
    }


    /*public function dubl_upload_action()
    {

    }*/

    protected function dubl_send_action()
    {


        $dubl = $this->getDublAct();
        $dubl->state = DublAct::STATE_ON_CHECK;
        $dubl->save();
        $this->redirectByAction('dubl', array('type' => $dubl->test_level_type_id), 'dubl');

    }

    protected function dubl_delete_action()
    {
        $dubl = $this->getDublAct();
        $dubl->delete();
//        var_dump($list);
        $this->redirectByAction('dubl', array('type' => $dubl->test_level_type_id), 'dubl');
    }

    protected function dubl_create_action()
    {

        $type = filter_input(INPUT_GET, 'type', FILTER_VALIDATE_INT);
        if (!TestLevelType::getByID($type)) {
            $this->redirectByAction('dubl', array(), 'dubl');
        }

        if (count($_POST)) {
            $dubl = new DublAct();
            $dubl->center_id = Session::getLocalCenterID();
            $dubl->official = filter_input(INPUT_POST, 'official', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);;
            $dubl->comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);;
            $dubl->center_dogovor_id = filter_input(INPUT_POST, 'university_dogovor_id', FILTER_VALIDATE_INT);;
            $dubl->created = date('Y-m-d H:i:s');
            $dubl->test_level_type_id = $type;
            $dubl->save();

            $this->redirectByAction('dubl', array('type' => $type), 'dubl');
        }

        $act = new DublAct();
        $act->center_id = $_SESSION['univer_id'];

        $university = University::getByID(Session::getLocalCenterID());

        $dogovors = $university->getDogovorsByType($type);
        if (!$dogovors || !count($dogovors)) {
            Session::setFlash('Для данного типа тестирования отсутствуют договора');
            $this->redirectByAction('dubl', array('type' => $type), 'dubl');
        }
        return $this->render->view('dubl/edit_act', array(
            'Act' => $act,
            'test_level_type' => $type,
            'University' => $university,
        ));


    }

    protected function dubl_edit_action()
    {

        $dubl = $this->getDublAct();

        if (count($_POST)) {
//        $dubl = new DublAct();
//        $dubl->center_id = Session::getLocalCenterID();
            $dubl->official = filter_input(INPUT_POST, 'official', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);;
            $dubl->comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);;
            $dubl->center_dogovor_id = filter_input(INPUT_POST, 'university_dogovor_id', FILTER_VALIDATE_INT);;
//        $dubl->created = date('Y-m-d H:i:s');
            $dubl->save();

            $this->redirectByAction('dubl', array('type' => $dubl->test_level_type_id), 'dubl');
        }

//        $act = new DublAct();
//        $act->center_id= $_SESSION['univer_id'];

        $university = University::getByID(Session::getLocalCenterID());
        $dogovors = $university->getDogovorsByType($dubl->test_level_type_id);
        if (!$dogovors || !count($dogovors)) {
            Session::setFlash('Для данного типа тестирования отсутствуют договора');
            $this->redirectByAction('dubl', array('type' => $dubl->test_level_type_id), 'dubl');
        }

        return $this->render->view('dubl/edit_act', array(
            'Act' => $dubl,
            'test_level_type' => $dubl->test_level_type_id,
            'University' => $university,
        ));


    }

    protected function dubl_upload_action()
    {
//        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
//            $this->redirectReturn();
//        }
        $act = $this->getDublAct();
        if (!$act) {
            $this->redirectByAction('act_fs_list');
        }
        /* if (!$act
             || $act->isDeleted()
             || $act->state != Act::STATE_INIT
         ) {
             $this->redirectAccessRestricted();
         }*/

        $_SESSION['flash'] = '';
        if (!empty($_FILES)) {
            $_FILES = $this->multipleFiles($_FILES);
//            die(var_dump($_FILES));
            if (!empty($_FILES['file'])) {
                foreach ($_FILES['file'] as $user_id => $_file) {
                    if (empty($_file['name'])) {
                        continue;
                    }
                    $file = new File();
                    $id = $file->upload($_file, $error);
                    $man = DublActMan::getByID($user_id);
                    $_SESSION['flash'] .= '<strong>' . $man->getSurnameRus() . ' ' . $man->getNameRus() . '</strong>: ' . $error . '<br>';
                    if ($id) {


                        if ($man->file_request_id) {
                            $oldFile = File::getByID($man->file_request_id);
                            $oldFile->delete();
                        }
                        $man->file_request_id = $id;
                        $man->save();
                    }
                }
            }
            if (!empty($_FILES['request'])) {
                $_file = $_FILES['request'];
                if (!empty($_file['name'])) {


                    $file = new File();
                    $id = $file->upload($_file, $error);
                    $man = $act;
                    $_SESSION['flash'] .= '<strong>Заявление</strong>: ' . $error . '<br>';
                    if ($id) {


                        if ($man->file_request_id) {
                            $oldFile = File::getByID($man->file_request_id);
                            $oldFile->delete();
                        }
                        $man->file_request_id = $id;
                        $man->save();
                    }
                }
            }

        }
//        if (!empty($_FILES['file'])) {


        return $this->render->view(
            'dubl/zayavleniya',
            array(
                'people' => $act->getPeople(),
                'Act' => $act,

            )
        );
    }

    protected function dubl_print_invoice_action()
    {
        //echo 'sadsadasd';
        /*die ('sadasd');
                if (count($_POST)) {
                    $act = Act::getByID($_POST['id']);
                    $act->invoice_date = $this->mysql_date($_POST['invoice_date']);
                    $act->invoice = $_POST['invoice_number'];
                    $act->invoice_index = $_POST['invoice_index'];
                    $act->signing = $_POST['invoice_signing'];

                    $act->save();
                    writeStatistic(
                        'sdt',
                        'act_print_invoice',
                        array(
                            'id' => $act->id,
                            'univer_id' => $act->getUniversity()->id,
                            'univer_name' => $act->getUniversity()->name,
                            'invoice_number' => $_POST['invoice_number'],
                            'invoice_index' => $_POST['invoice_index'],
                            'invoice_signing' => $_POST['invoice_signing'],
                            'invoice_date' => $_POST['invoice_date'],
                        )
                    );
                } else {
                    $act = Act::getByID($_GET['id']);
                    $act->save();
                }*/

        $act = $this->getDublAct();


//        $act->center_dogovor_id=168; //!!!!!!!!!!!!!!!!!!!потом убрать


        die($this->render->view(
            'dubl/print_check',
            array(
                'Act' => $act,
            )
        ));
    }

    protected function dubl_act_print_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id']) || empty($_GET['type']) || !is_numeric($_GET['type'])) {
            $this->redirectReturn();
        }
        $act = $this->getDublAct();
        $signer = ActSigning::getByID($_GET['type']);
//var_dump($signer);die();
        /*$is_head = University::isHead($_GET['id']);
         if ($is_head == 1) {
             $print = 'act_head';
         } else {*/
        $print = 'act';
        /*  }*/

        if (!$signer->id) {
            $this->redirectReturn();
        }
        die($this->render->view(
            'dubl/' . $print,
            array(
                'act' => $act,
                'signer' => $signer,

            )
        ));

    }


    protected function dubl_summary_table_print_action()
    {

        if (empty($_POST['id']) || !is_numeric($_POST['id']) || empty($_POST['hs_id']) || !is_numeric($_POST['hs_id'])) {
//            if (empty($_GET['id']) || !is_numeric($_GET['id']) || empty($_GET['hs_id']) || !is_numeric($_GET['hs_id'])) {
            $this->redirectReturn();
        }


        $act = DublAct::getByID($_POST['id']);
        $signer = ActSigning::getByID($_POST['hs_id']);


        if ($_POST['ls']=='responsible')$local_signer=$act->responsible;
        else if ($_POST['ls']=='official')$local_signer=$act->official;
        else $local_signer='';




        $is_head = University::isHead($_POST['id']);


        if (!$signer->id) {
            $this->redirectReturn();
        }


        /* die($this->render->view(
             'dubl/summary_table',
             array(
                 'act' => $act,
                 'signer' => $signer,
                 'local_signer' => $local_signer,

             )
         ));*/


        /*sd*/
        $template=($this->render->view(
            'dubl/summary_table',
//            'dubl/act',
            array(
                'act' => $act,
                'signer' => $signer,
                'local_signer' => $local_signer,

            )
        ));
        /*sd*/


        $filename = $act->id.'.html';
        $filepath = SDT_UPLOAD_SUMMARY_TABLE_DIR.'temp/'.$filename;

        file_put_contents($filepath,$template);
        $table= new ActSummaryTable();
        $table->move($filename, $filepath,$errros,$table::SUMMARY_TABLE);

        if ($act->summary_table_id){
            $old_table=ActSummaryTable::getByID($act->summary_table_id);
            $old_table->delete();
        }
        $act->summary_table_id = $table->id;
//        die(var_dump($act));
        $act->save();



        $this->redirectByAction('dubl_act_summary_table',array('id'=> $act->id),'dubl');

    }

    protected function dubl_act_summary_table_action()
    {
        $id = filter_input(INPUT_GET, 'id',FILTER_VALIDATE_INT);
        if(!$id) {
            $this->redirectAccessRestricted();
        }
        $act = DublAct::getByID($id);
        if(!$act){
            $this->redirectAccessRestricted();
        }

        $file = ActSummaryTable::getByID($act->summary_table_id);
        if (!$file) {
            die('Файл не существует');
        }
//        session_write_close();

        $file->show();
    }


    protected function dubl_act_universities_received_action()
    {
//        $acts = Univesities::getByLevel(DublAct::STATE_ON_CHECK);
        $level_type = filter_input(
            INPUT_GET,
            'type',
            FILTER_VALIDATE_INT,
            array("options" => array("default" => 1, "min_range" => 1, "max_range" => 2))
        );

        $caption = '';
        switch ($level_type) {
            case 1:
                $caption = 'Список центров тестирования с заявками на дубликаты по лингводидактическому тестированию';
                break;

            case 2:
                $caption = 'Список центров тестирования с заявками на дубликаты по интеграционному экзамену';
                break;
        }


        $acts = Univesities::getByDubl(array(DublAct::STATE_ON_CHECK, DublAct::STATE_IN_HC), $level_type);

        return $this->render->view(
            'dubl/universities_list',
            array(
                'list' => $acts,
                'caption' => $caption,
                'type' => $level_type,

            )
        );
    }

    protected function dubl_act_list_action()
    {
        if (empty($_GET['uid']) || !is_numeric($_GET['uid'])) {
            $_SESSION['flash'] = 'Локальный центр не указан';
            $this->redirectReturn();
        }


        $level_type = filter_input(
            INPUT_GET,
            'type',
            FILTER_VALIDATE_INT,
            array("options" => array("default" => 1, "min_range" => 1, "max_range" => 2))
        );

        $caption = '';
        switch ($level_type) {
            case 1:
                $caption = ' по лингводидактическому тестированию';
                break;

            case 2:
                $caption = ' по интеграционному экзамену';
                break;
        }


//        $acts = Acts::getByLevel($_GET['uid'], ACT::STATE_ARCHIVE);
        $acts = DublActList::getByLevel($_GET['uid'], array(DublAct::STATE_ON_CHECK, DublAct::STATE_IN_HC), $level_type);
        $signingsInvoice = ActSignings::get4Invoice();


        return $this->render->view(
            'dubl/acts_list',
            array(
                'list' => $acts,
                'signings' => $signingsInvoice,
                'caption' => $caption,
            )
        );
    }

    protected function dubl_act_decline_action()
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $reason = filter_input(INPUT_POST, 'reason', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

        $act = DublAct::getByID($id);
        if (!$act) {
            $_SESSION['flash'] = "акт не найден";
            die(json_encode(
                array(
                    'result' => 'error',
                    'message' => 'no act',
                )
            ));
        }


        $act->comment = $this->utf_decode($reason);
        $act->state = DublAct::STATE_IN_LC;
        $act->save();


        $_SESSION['flash'] = "акт возвращен в работу";


        die(json_encode(
            array(
                'result' => 'ok',
            )
        ));

    }

    protected function dubl_act_numbers_action()
    {
        $act = $dubl = $this->getDublAct();
        if (!$act
            || $act->isDeleted()

        ) {
            $this->redirectAccessRestricted();
        }
//die('sdfs');

        $this->isDeletedRedirect($act);
//        $this->checkActIsEditable($act);
//        $act->incrementViewedAndSave();


        $people = $act->getPeople();
        $need_to_archive_counter = 0;
        $acts = array();
        foreach ($people as $man) {

            $oldman = ActMan::getByID($man->old_man_id);
//            die(var_dump($oldman->act_id));
            if (Act::getActState($oldman->act_id) != Act::STATE_ARCHIVE)
                $acts[] = $man;
            //var_dump($old_act->id);echo '<br>';
            $need_to_archive_counter++;
        }
//var_dump($acts);

        if (!empty($acts)) {
//die('not in archive');


            $list = array();

            foreach ($acts as $man) {
                /** @var DublActMan $man */
                $item = array();
                $item['fio'] = $man->getSurnameRus() . ' ' . $man->getNameRus();
                $sql = 'select su.name, shc.short_name, shc.id as shc_id, sa.id as act_id from sdt_act_people sap
left join sdt_act sa on sap.act_id = sa.id
left join   sdt_university su on su.id = sa.university_id
left join sdt_head_center shc on shc.id = su.head_id
where  sap.id = ' . intval($man->old_man_id);
                $C = Connection::getInstance();
                $result = $C->queryOne($sql);

//                die(var_dump($result));

                $item['lc_name'] = $result['name'];
                $item['gc_name'] = $result['short_name'];
                $item['act_id'] = $result['act_id'];
                if ($result['shc_id'] == CURRENT_HEAD_CENTER) {
                    $item['man'] = ActMan::getByID($man->old_man_id);
                    $item['act'] = $item['man']->getAct();
                }
                $list[] = $item;
            }

            /*if (empty($_GET['uid']) || !is_numeric($_GET['uid'])) {
                $_SESSION['flash'] = 'Вуз не указан';
                $this->redirectReturn();
            }
            $level_type = filter_input(
                INPUT_GET,
                'type',
                FILTER_VALIDATE_INT,
                array("options" => array("default" => 1, "min_range" => 1, "max_range" => 2))
            );
            $acts = Acts::getByLevels($_GET['uid'], $level_type);*/
//            $signings = ActSignings::get4Invoice();
            /*$type = '';

            switch ($level_type) {
                case 1:
                    $type = ' лингводидактическому тестированию';
                    break;

                case 2:
                    $type = ' интеграционному экзамену';
                    break;
            }*/

            //var_dump($signings); die();
            return $this->render->view(
                'dubl/old_acts_list',
                array(
//                    'type' => $type,
                    'list' => $list,
//                    'signings' => $signings
                )
            );


            /* return $this->render->view(
                 'dubl/form_numbers',
                 array(
                     'Acts' => $old_acts,
 //                    'type' => $type,
                 )
             );*/
        } else {


            if (count($_POST)) {
                foreach ($_POST['user'] as $id => $params) {
//                var_dump($params);
                    $man = ActMan::getByID($id);
                    if ($man->document == ActMan::DOCUMENT_CERTIFICATE) {
                        unset($params['document_nomer']);
                    }
                    if ($man->document == ActMan::DOCUMENT_NOTE) {
                        unset($params['blank_number']);
                    }
                    $man->parseParameters($params);
//                var_dump($man);die;
                    $man->setValidTill();
                    $man->save();
                }
                //  $act->updateDocumentsCountLeft();
                $_SESSION['flash'] = 'Сохранено';
                $act->save();
            }

            $type = '';

            /*switch ($act->test_level_type_id) {
                case 1:
                    $type = '(Лингводидактическое тестирование)';
                    break;

                case 2:
                    $type = '(Интеграционный экзамен)';
                    break;
            }*/

            return $this->render->view(
                'dubl/form_numbers',
                array(
                    'Act' => $act,
                    'type' => $type,
                )
            );

        }
    }

    protected function dubl_act_insert_blanks_action()
    {
//        die('test');
        /*$act = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);


        $act = Act::getByID($act);*/

        $act = $dubl = $this->getDublAct();

        if (!$act) {
            $this->redirectAccessRestricted();
        }





//        die(var_dump($act->getPeople()));
//        DocumentNumber::lock();
        foreach ($act->getPeople() as $Man) {

            $old_man=ActMan::getByID($Man->old_man_id);

            if (empty($Man->dubl_cert_id) && !$old_man->is_anull()) {

                if (!$Man->assignBlank()) {
                    $_SESSION['flash'] = 'Номера бланков закончились';
                    $act->save();
                    $this->redirectReturn();
                }
            }
        }

//        DocumentNumber::unlock();
        $act->save();
        $_SESSION['flash'] = 'Номера бланков заполнены';
        $this->redirectReturn();
    }



    protected function dubl_print_certificate_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }

        //$act = $dubl = $this->getDublAct();

        $persons = array();


        $persons[] = ActMan::getByID($_GET['id']);
//        $persons[] = CertificateDuplicate::checkForDuplicates(ActMan::getByID($_GET['id']));

        die($this->render->view(
            'acts/print/certificate',
            array(
                //'Man' => $man,
                'persons' => $persons,

            )
        ));


    }

    protected function dubl_man_acts_action()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!($id)) {
            $this->redirectReturn();
        }


        //$act = $dubl = $this->getDublAct();

        if ($man = ActMan::getByID($id)) {
            $man = CertificateDuplicate::checkForDuplicates($man);
        } else $this->redirectReturn();

        //$persons = array();
        $acts = DublActList::getByMan($id);
        $signingsInvoice = ActSignings::get4Invoice();


        //$persons[] = ActMan::getByID($_GET['id']);
//        $persons[] = CertificateDuplicate::checkForDuplicates(ActMan::getByID($_GET['id']));


        return ($this->render->view(
            'dubl/dubl_man_acts',
            array(
//                'Man' => $man,
                'list' => $acts,
                'signings' => $signingsInvoice,
                'caption' => ' по тестируемому ' . $man->getSurname_rus() . ' ' . $man->getName_rus(),


            )
        ));


    }

    protected function dubl_print_certificates_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }
        $Act = $dubl = $this->getDublAct();

        $persons = array();

//        $Act = Act::getByID($_GET['id']);

        /*foreach ($Act->getPeople() as $Man) {
            if ($Man->document=='note') continue;
            $persons[] = $Man->id;
        }


        die($this->render->view(
            'acts/print/certificate',
            array(
                //'Man' => $man,
                'persons' => $persons,

            )
        ));
        */
        if (empty($_POST['pers'])) {

            foreach ($Act->getPeople() as $Man) {

                $old_man=ActMan::getByID($Man->old_man_id);

                if ($old_man->is_anull()) continue;

                $man = ActMan::getByID($Man->old_man_id);

                $man = CertificateDuplicate::checkForDuplicates($man);

                if ($man->document != 'certificate' || Act::getActTestLevelID($man->act_id) != $_GET['tt']) {
                    continue;
                }
                $persons[] = $man;
            }

            $header_print = array('title' => 'сертификатов', 'show' => 1);

//            return $this->render->view($this->mass_print());
            return $this->render->view(
                'dubl/choose_people',
                array(
                    'Act' => $Act,
                    'persons' => $persons,
                    'header_print' => $header_print,
                )
            );
        } else {

            foreach ($Act->getPeople() as $Man) {

                $man = ActMan::getByID($Man->old_man_id);

                $man = CertificateDuplicate::checkForDuplicates($man);

                if ($man->document != 'certificate' || !in_array($man->id, $_POST['pers'])) {
                    continue;
                }
                $persons[] = $man;
            }

            /*  if (!$Act->is_printed) {
                      $m = Messaging::getInstance();
                       $m->NewMessage(
                           $m->getCurrentKey(),
                           array(
                               $m->getLocalKey($Act->university_id)
                           ),
                           'Головной центр распечатал сертификаты',
                           'Для акта от ' . $this->date($Act->created) . ' были распечатаны сертификаты',
                           true
                       );
             }
             $Act->setPrinted();*/

            die($this->render->view(
                'acts/print/certificate',
                array(
                    'persons' => $persons,

                )
            ));
        }

    }

    protected function dubl_act_man_print_pril_cert_action()
    {
        $man = ActMan::getByID($_GET['id']);
//        $man = CertificateDuplicate::checkForDuplicates(ActMan::getByID($_GET['id']));;


        $persons = array($man);

        die($this->render->view(
            'acts/print/pril_cert',
            array(
//                'Man' => $man,
                'persons' => $persons,

            )
        ));
    }

    protected function dubl_act_man_print_pril_certs_action()
    {

//        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
//            $this->redirectReturn();
//        }

        $persons = array();

        $Act = $dubl = $this->getDublAct();

//        $Act = Act::getByID($_GET['id']);

        /*foreach ($Act->getPeople() as $man) {
            if ($man->document != 'certificate') continue;
            $persons[] = $man;
        }*/


//        $persons=$this->mass_print();
        if (empty($_POST['pers'])) {

            foreach ($Act->getPeople() as $Man) {

                $old_man=ActMan::getByID($Man->old_man_id);
                if ($old_man->is_anull()) continue;

                $man = ActMan::getByID($Man->old_man_id);

                $man = CertificateDuplicate::checkForDuplicates($man);

                if ($man->document != 'certificate') {
                    continue;
                }
                $persons[] = $man;
            }

            $header_print = array('title' => 'приложений к сертификатам', 'show' => 0);

//            return $this->render->view($this->mass_print());
            return $this->render->view(
                'dubl/choose_people',
                array(
                    'persons' => $persons,
                    'header_print' => $header_print,
                )
            );
        } else {

            foreach ($Act->getPeople() as $Man) {

                $man = ActMan::getByID($Man->old_man_id);

                $man = CertificateDuplicate::checkForDuplicates($man);

                if ($man->document != 'certificate' || !in_array($man->id, $_POST['pers'])) {
                    continue;
                }
                $persons[] = $man;
            }


            die($this->render->view(
                'acts/print/pril_cert',
                array(
                    'persons' => $persons,

                )
            ));
        }
    }

    protected function dubl_act_vidacha_reestr_action()
    {
        //$act = Act::getByID($_GET['id']);

        $act = $dubl = $this->getDublAct();

        //$this->checkActIsEditable($act);

        die($this->render->view(
            'dubl/vidacha_cert_reestr',
            array(
                'Act' => $act,
                'People' => $act->getPeople(),

            )
        ));
    }

    protected function dubl_act_vidacha_cert_action()
    {
        //$act = Act::getByID($_GET['id']);

        $act = $dubl = $this->getDublAct();

        //$this->checkActIsEditable($act);

        die($this->render->view(
            'dubl/vidacha_cert',
            array(
                'Act' => $act,
                'People' => $act->getPeople(),
            )
        ));
    }

    protected    function man_blank_invalid_action()
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $reason = $this->utf_decode(filter_input(INPUT_POST, 'reason', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES));
        $man = DublActMan::getByID($id);
        if (!$man) {
            $this->redirectAccessRestricted();
        }

        $cd_id = $man->dubl_cert_id;
//        die($cd_id);
        if ($man->invalidateBlank($reason))
        {
            $cd = CertificateDuplicate::getByBlandkID($cd_id);
            $cd->delete();
        }
//        (var_dump($res));

        $_SESSION['flash'] = 'Бланк помечен как недействительный';


        die(json_encode(
            array(
                'result' => 'ok',
            )
        ));
    }


}
