<?php

require_once 'AbstractController.php';
require_once __DIR__.'/actions/UniversityTrait.php';
require_once __DIR__.'/actions/BuhTrait.php';
require_once __DIR__.'/actions/Acts/actOnCheckTrait.php';
require_once __DIR__.'/actions/ArchiveTrait.php';
//require_once __DIR__ . '/actions/PfurApi.php';
require_once __DIR__.'/actions/ReportTrait.php';
require_once __DIR__.'/actions/Acts/ActInitTrait.php';
require_once __DIR__.'/actions/ReExamTrait.php';

class Controller extends AbstractController
{
    use UniversityTrait;
    use BuhTrait;
    use actOnCheckTrait;
    use ArchiveTrait;

//    use PfurApi;
    use ReportTrait;
    use ReExamTrait;
    use ActInitTrait;

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


    public function user_delete_action()
    {
        $id = $this->getNumeric('id');
        $user = User::getByID($id);
        $user->delete();
        $_SESSION['flash'] = 'Пользователь удалён';
        $this->redirectByAction('user_list');
    }

    public function user_edit_action()
    {
        $id = $this->getNumeric('id');
        $user = User::getByID($id);
        if (count($_POST)) {
            $passChanges = $user->password_change;
            $user->parseParameters($_POST);
            $user->save();
            $_SESSION['flash'] = 'Данные пользователя сохранены';
            if ($passChanges) {
                $_SESSION['flash'] .= '<br>Новый пароль действует 6 дней до его смены пользователем!';
            }
        }

        return $this->render->view(
            'form',
            array(
                'item' => $user,
                'fields' => User::getEditForm(),
                'legend' => 'Редактирование пользователя',
            )
        );
    }

    public function user_create_action()
    {
        $user = new User();
        $user->head_id = CURRENT_HEAD_CENTER;
        if (count($_POST)) {
            $passChanges = $user->password_change;
            $user->parseParameters($_POST);
            $user->save();
            $_SESSION['flash'] = 'Пользователь создан';
            if ($passChanges) {
                $_SESSION['flash'] .= '<br>Новый пароль действует 6 дней до его смены пользователем!';
            }
            $this->redirectByAction('user_list');
        }

        return $this->render->view(
            'form',
            array(
                'item' => $user,
                'fields' => User::getEditForm(),
                'legend' => 'Создание пользователя',
            )
        );
    }

    public function signing_delete_action()
    {
        $id = $this->getNumeric('id');
        $user = ActSigning::getByID($id);
        $user->delete();
        $_SESSION['flash'] = 'Подписывающий удалён';
        $this->redirectByAction('signing_list');
    }

    public function signing_edit_action()
    {
        $id = $this->getNumeric('id');
        $signing = ActSigning::getByID($id);
        if (count($_POST)) {
            $signing->setCheckboxes($_POST);
            $signing->parseParameters($_POST);
            $signing->save();
            $_SESSION['flash'] = 'Подписывающий сохранён';
            $this->redirectByAction('signing_list');
        }

        return $this->render->view(
            'form',
            array(
                'item' => $signing,
                'fields' => ActSigning::getEditForm(),
                'legend' => 'Редактирование подписывающего',
            )
        );
    }

    public function signing_create_action()
    {
        $signing = new ActSigning();
        $signing->head_id = CURRENT_HEAD_CENTER;
        if (count($_POST)) {
            $signing->setCheckboxes($_POST);
            $signing->parseParameters($_POST);
            $signing->save();
            $_SESSION['flash'] = 'Подписывающий сохранён';
            $this->redirectByAction('signing_list');
        }

        return $this->render->view(
            'form',
            array(
                'item' => $signing,
                'fields' => ActSigning::getEditForm(),
                'legend' => 'Создание подписывающего',
            )
        );
    }

    public function user_add_form_action()
    {
        header("Content-type: text/html; charset=windows-1251");
        $user = new User();
        if (!count($_POST)) {
            die(
            $this->render->view(
                'add_user_form',
                array(
                    'item' => $user,
                    'fields' => User::getEditForm(),
                    //'legend'=>'Создание пользователя'
                )
            )
            );
        } else {
            $d = $_POST;
            $d = $this->recursive_utf_decode($d);
            if ($d['login'] == '') {
                die('empty_login');
            }
            $sql = 'SELECT * FROM tb_users WHERE login="'.$d['login'].'"';
            if (mysql_num_rows(mysql_query($sql)) > 0) {
                die ('in_use');
            }
            if ($d['password_change'] == '') {
                die('empty_password');
            }
            if (count($d)) {
                $user->parseParameters($d);
                $user->head_id = CURRENT_HEAD_CENTER;
                $user->save();
                die('ok');
            }
        }
    }

    public function user_edit_form_action()
    {
        header("Content-type: text/html; charset=windows-1251");
//        $user = new User();
        $u_id = $this->getNumeric('u_id');
        $user = User::getByID($u_id);
        if (!$_POST) {
            die(
            $this->render->view(
                'edit_user_form',
                array(
                    'item' => $user,
                    'fields' => User::getEditForm(),
                )
            )
            );
        } else {
            $d = $_POST;
            $d = $this->recursive_utf_decode($d);
            if ($d['login'] == '') {
                die('empty_login');
            }
            $sql = 'SELECT * FROM tb_users WHERE login="'.$d['login'].'" AND u_id != '.$u_id;
            if (mysql_num_rows(mysql_query($sql)) > 0) {
                die ('in_use');
            }
            if (count($d)) {
                $user->parseParameters($d);
                $user->save();
                die(
                json_encode(
                    array(
                        'user' => array(
                            'name' => $this->encode($user->shortName()),
                            'id' => $user->u_id,
                        ),
                        'status' => 'ok',
                    )
                )
                );
            }
        }
    }

    public function user_delete_form_action()
    {
        $id = $this->getNumeric('id');
        $user = User::getByID($id);
        $user->delete();
        $_SESSION['flash'] = 'Пользователь удалён';
        $this->redirectByAction('edit_user_list', array('id' => $_GET['id']));
    }

    public function change_price_univers_action()
    {
        //$CURRENT_HEAD_CENTER=2; //!!!!
        //die(CURRENT_HEAD_CENTER);
        $list = array();
        $sql = 'SELECT id,name FROM sdt_university WHERE head_id='.CURRENT_HEAD_CENTER.' AND deleted=0 AND is_price_change=1 AND (parent_id IS NULL OR parent_id = 0)';
        //die ($sql);
        $result = mysql_query($sql);
        while ($res = mysql_fetch_array($result)) {
            $list[] = $res;
        }

        return $this->render->view(
            'head_center/changing_price_univers_list',
            array(
                'list' => $list,
            )
        );
    }

    /* protected function  test_levels_action()
     {

         $univers = TestLevels::getAll();

         return $this->render->view(
             'test_levels/all',
             array(
                 'list' => $univers
             )
         );
     }

     protected function test_levels_add_action()
     {

         $univer = new TestLevel();

         if (count($_POST)) {
             $univer->parseParameters($_POST);
             $univer->save();
             $this->redirectByAction('test_levels');
         }

         return $this->render->form('add', $univer, 'Добавление уровня тестирования');

     }

     protected function test_levels_delete_action()
     {

         $univer = TestLevel::getByID($_GET['id']);


         $univer->delete();
         $this->redirectByAction('test_levels');
     }

     protected function test_levels_edit_action()
     {

         $univer = TestLevel::getByID($_GET['id']);

         if (count($_POST)) {
             $univer->parseParameters($_POST);
             $univer->save();
             $this->redirectByAction('test_levels');
         }

         return $this->render->form('add', $univer, 'Редактирование уровня тестирования');

     }*/
    public function test_level_price_edit_action()
    {
        header("Content-type: text/html; charset=windows-1251");
        $list = ChangedPriceTestLevel::getByID($_GET['univer_id']);
        if (!$_POST) {
            die(
            $this->render->view(
                'head_center/changing_price_form',
                array(
                    'list' => $list,
                )
            )
            );
        }
    }

    public function save_test_level_price_action()
    {
        $univer_id = $_GET['univer_id'];
        $to_save = array();
        foreach ($_POST['price'] as $key => $value) {
            $price = $value;
            if (!is_numeric($price)) {
                $price = '';
            }
            $sub_test_price = $_POST['sub_test_price'][$key];
            if (!is_numeric($sub_test_price)) {
                $sub_test_price = '';
            }
            $sub_test_price_2 = $_POST['sub_test_price_2'][$key];
            if (!is_numeric($sub_test_price_2)) {
                $sub_test_price_2 = '';
            }
            if (!empty($price) || !empty($sub_test_price) || !empty($sub_test_price_2)) {
                if (empty($price)) {
                    $price = 0;
                }
                if (empty($sub_test_price)) {
                    $sub_test_price = 0;
                }
                if (empty($sub_test_price_2)) {
                    $sub_test_price_2 = 0;
                }
                $to_save[] = array(
                    'univer_id' => $univer_id,
                    'test_level_id' => $key,
                    'price' => $price,
                    'sub_test_price' => $sub_test_price,
                    'sub_test_price_2' => $sub_test_price_2,
                );
            }
        }
        if (!empty($to_save)) {
            ChangedPriceTestLevel::save($to_save);
        }
        die('ok');
    }

    public function message_action()
    {
        $m = Messaging::getInstance();
        $key = $m->getCurrentKey();
        $threads = $m->getThreads($key);

        return $this->render->view(
            'message/threads',
            array(
                'm' => $m,
                'current_key' => $key,
                'threads' => $threads,
            )
        );
    }

    public function message_view_action()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $m = Messaging::getInstance();
        $key = $m->getCurrentKey();
        $thread = $m->getThread($id, $key);
        if (!$thread) {
            $this->redirectAccessRestricted();
        }

        return $this->render->view(
            'message/view',
            array(
                'm' => $m,
                'current_key' => $key,
                'thread' => $thread,
            )
        );
    }

    public function loc_archive_action()
    {
        $data = Act::getLocalArchive($_SESSION['univer_id']);

//        die(var_dump($data));
        return $this->render->view(
            'local/archive',
            array(
                'items' => $data ? $data : array(),
            )
        );
    }

    public function report_not_insert_numbers_action()
    {
        $result = Acts::ReportNotInsertedAct(2);

        return $this->render->view(
            'otchet/report_not_insert_numbers',
            array(
                'items' => $result,
                'type' => ' по экзамену',
            )
        );
    }

    public function report_not_insert_numbers_rki_action()
    {
        $result = Acts::ReportNotInsertedAct(1);

        return $this->render->view(
            'otchet/report_not_insert_numbers',
            array(
                'items' => $result,
                'type' => ' по РКИ',
            )
        );
    }

    public function frdo_excel_reports_list_action()
    {
        echo '<h1>Список отчетов ФРДО по '.HeadCenter::getOrgName(CURRENT_HEAD_CENTER).'</h1>';
//        $hc=filter_input(INPUT_POST, 'hc', FILTER_VALIDATE_INT);
        $hc = CURRENT_HEAD_CENTER;
        $hc_object = HeadCenter::getByID($hc);
        $hcs = HeadCenters::getHeadOrgs();
        if (!empty($hc)) {
            $dir = FRDO_EXCEL_UPLOAD_DIR.'\\'.$hc_object->horg_id.'\show';
//            $dir=FRDO_EXCEL_UPLOAD_DIR.'\temp';
//        $filelist = array();
            if ($handle = @opendir($dir)) {
                while ($entry = readdir($handle)) {
                    if (!is_dir($entry)) {
                        $filelist = array();
                        $filelist[] = '<h3>'.$entry.'</h3>';
                        if ($handle2 = @opendir($dir.'\\'.$entry)) {
                            while ($entry2 = readdir($handle2)) {
                                if (!is_dir($entry2)) {
//                                $filelist[] = $entry;
                                    $filelist[] = '<a href="?action=excel_report_download&file='.$entry.'\\'.$entry2.'">'.$entry2.'</a><br>';
                                }
                            }
                            closedir($handle2);
                        }
                    }
                    if (!empty($filelist) && count($filelist) > 1) {
                        foreach ($filelist as $item) {
                            echo $item;
                        }
                    }
                    unset($filelist);
                }
                closedir($handle);
            }
        }
    }

    public function excel_report_download_action()
    {
        $hc = CURRENT_HEAD_CENTER;
        $hc_object = HeadCenter::getByID($hc);
        $dir = FRDO_EXCEL_UPLOAD_DIR.'\\'.$hc_object->horg_id;
        $file = filter_input(INPUT_GET, 'file');
        $file_name = substr($file, 4);
        if (file_exists($dir.'\show\\'.$file)) {
            header('Content-type: application/zip');
            header('Content-Disposition: attachment; filename="'.$file_name.'"');
            readfile($dir.'\show\\'.$file);
        } else {
            $this->redirectReturn();
        }
    }

    public function used_certificates_list_action()
    {
        $search = false;
        $res = array();
        $from = '1.01.'.date('Y');
        $to = date('d.m.Y');
        $test_level_type = 2;
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $search = true;
            $connection = Connection::getInstance();
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $test_level_type = filter_input(INPUT_POST, 'test_level_type', FILTER_VALIDATE_INT);
//            $from = date("Y-m-d", mktime(0, 0, 0, $month, 1, $year)); //ч м с мес день год
////            $to = date("Y-m-d H:i:s", mktime(23, 59, 59, $month+1, 0, $year)); //ч м с мес день год
//            $to = date("Y-m-d", mktime(23, 59, 59, $month+1, 0, $year));
            $dates = " and cu.timest BETWEEN '".$connection->escape(
                    $this->mysql_date($from)
                )." 0:0:0' and '".$connection->escape(
                    $this->mysql_date($to)
                )." 23:59:59'";
            $sql = 'SELECT cu.number FROM certificate_reserved cr
LEFT JOIN certificate_used cu ON cu.blank_id=cr.id
WHERE
cr.head_center_id= '.CURRENT_HEAD_CENTER.'
AND cr.test_type_id = '.$test_level_type.' AND cr.used = 1
'.$dates.'


ORDER BY cu.number ASC';
//die($sql);
            $res = $connection->query($sql);
        }

        return $this->render->view(
            'head_center/certificate/used_certificates_list',
            array(
                'array' => $res,
                'search' => $search,
                'from' => $from,
                'to' => $to,
                'test_level_type' => $test_level_type,
                'caption' => 'Получить список использованных бланков',
            )
        );
    }

    public function loc_notes_action()
    {
        $interval = 30;
        $data = ActPeople::getNotes4LC(Session::getLocalCenterID(), $interval);

//        die(var_dump($data));
        return $this->render->view(
            'local/notes',
            array(
                'items' => $data ? $data : array(),
                'interval' => $interval,
            )
        );
    }


    /* protected function act_rki_action()
     {
         $man = ActMan::getByID($_GET['id']);
 //        $man->blank_number = $man->id;
 //
 //        $man->save();
 //        $man->getAct()->save();

         die($this->render->view(
             'acts/print/rki',
             array(
                 'Man' => $man,
             )
         ));
     }*/
    public function act_table_popup_ajax_action()
    {
        $act_id = filter_input(INPUT_POST, 'act_id');
        $popup_type = filter_input(INPUT_POST, 'popup_type');
        $this->utf_header();
        die(
        $this->encode(
            $this->render->view(
                'acts/act_table_popup_ajax',
                array(
                    'act_id' => $act_id,
                    'popup_type' => $popup_type,
                )
            )
        )
        );
    }

    function archive_man_action()
    {

        $id = filter_input(INPUT_GET,'id',  FILTER_VALIDATE_INT);

        if (!$id) {
            $this->redirectAccessRestricted();
        }
        $p = new \SDT\models\Archive\People();
        $man = $p->find($id);

        return $this->render->view(
            'archive/show',
            array(
                'man' => $man,
            )
        );
    }

    protected function archive_man_file_download_action()
    {
        $file = \SDT\models\Archive\PhotoFile::getByHash(filter_input(INPUT_GET, 'hash'));
        if (!$file) {
            die('Файл не существует');
        }
//        session_write_close();
        $file->download();
    }

    protected function archive_man_passport_download_action()
    {
        $file = \SDT\models\Archive\PassportFile::getByHash(filter_input(INPUT_GET, 'hash'));
        if (!$file) {
            die('Файл не существует');
        }
//        session_write_close();
        $file->download();
    }
    protected function index_action()
    {
        return 'Выберите раздел в меню';
    }

    protected function act_univer_on_check_action()
    {
        $acts = Acts::getByLevel($_SESSION['univer_id'], Act::STATE_SEND);

        return $this->render->view(
            'acts/init/oncheck_list',
            array(
                'list' => $acts,
            )
        );
    }

    protected function sess_invalid_action()
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $reason = filter_input(INPUT_POST, 'reason', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        $act = Act::getByID($id);
        if (!$act) {
            $_SESSION['flash'] = "Тестовая сессия не найдена";
            die(
            json_encode(
                array(
                    'result' => 'error',
                    'message' => 'no act',
                )
            )
            );
        }
        writeStatistic(
            'sdt',
            'session_invalid',
            array(
                'id' => $act->id,
            )
        );
        $act->comment = $this->utf_decode($reason);
        $act->save();
        foreach ($act->getPeople() as $man) {
            if ($man->isCertificate() and !$man->needBlank()) {
                $man->invalidateBlank('Тестовая сессия отмечена как недействительная');
            }
        }
        $act->delete();
        $_SESSION['flash'] = "Тестовая сессия отмечена недействительная";
        //  $this->redirectByAction('act_fs_list');
        die(
        json_encode(
            array(
                'result' => 'ok',
            )
        )
        );
    }

    protected function sess_rework_action()
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $reason = filter_input(INPUT_POST, 'reason', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        $act = Act::getByID($id);
        if (!$act) {
            $_SESSION['flash'] = "Тестовая сессия не найдена";
            die(
            json_encode(
                array(
                    'result' => 'error',
                    'message' => 'no act',
                )
            )
            );
        }
        if ($act->state != Act::STATE_RECEIVED) {
            $_SESSION['flash'] = "Тестовая сессия не найдена";
            die(
            json_encode(
                array(
                    'result' => 'error',
                    'message' => 'no act',
                )
            )
            );
        }
        /*writeStatistic(
             'sdt',
             'session_invalid',
             array(
                 'id' => $act->id,

             )
         ); */
        $act->comment = $this->utf_decode($reason);
        if ($act->getUniversity()->pfur_api) {
            $act->setState(Act::STATE_INIT);
        } else {
            $act->setState(Act::STATE_CHECKED);
        }
        $act->summary_table_id = null;
        $act->save();
        /*foreach ($act->getPeople() as $man) {
            if ($man->isCertificate() and !$man->needBlank()) {
                $man->invalidateBlank('Тестовая сессия отмечена как недействительная');
            }
        }
        $act->delete();*/
        $_SESSION['flash'] = "Тестовая сессия возвращена в проверенные";
        //  $this->redirectByAction('act_fs_list');
        die(
        json_encode(
            array(
                'result' => 'ok',
            )
        )
        );
    }

    protected function act_vedomost_action()
    {
        $actTest = ActTest::getByID($_GET['id']);
        $countries = Countries::getAll();
        if (count($_POST)) {
            foreach ($_POST['user'] as $id => $params) {
                $man = ActMan::getByID($id);
                $man->parseParameters($params);
                $man->save();
            }
        }

        return $this->render->view(
            'acts/forms/vedomost',
            array(
                'ActTest' => $actTest,
                'Legend' => 'Заполнить ведомость',
                "Countries" => $countries,
            )
        );
    }

    protected function act_print_check_action()
    {
        $act = Act::getByID($_GET['id']);
        $act->invoice_date = date('Y-m-d H:m');
        $act->save();
        die(
        $this->render->view(
            'acts/print_check',
            array(
                'Act' => $act,
            )
        )
        );
    }

    protected function act_universities_list_action()
    {
        $dogovors = Univesity_dogovors::getByUniversity($_GET['id']);
        header('Content-Type: text/html; charset=utf-8');
        foreach ($dogovors as $dogovor) {
            $dogovor->caption = $this->encode($dogovor->caption);
        }
        die(json_encode($dogovors));
    }

    protected function act_numbers_action()
    {
        $act = Act::getByID($_GET['id']);
        if (count($_POST)) {
            foreach ($_POST['user'] as $id => $params) {
                $man = ActMan::getByID($id);
                $man->parseParameters($params);
                $man->save();
            }
        }

        return $this->render->view(
            'acts/forms/numbers',
            array(
                'Act' => $act,
            )
        );
    }

    protected function act_man_print_pril_cert_action()
    {
        $man = ActMan::getByID($_GET['id']);
//        $man = CertificateDuplicate::checkForDuplicates(ActMan::getByID($_GET['id']));;
        $persons = array($man);
        die(
        $this->render->view(
            'acts/print/pril_cert',
            array(
//                'Man' => $man,
                'persons' => $persons,
            )
        )
        );
    }

    protected function act_man_print_pril_certs_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }
        $persons = array();
        $Act = Act::getByID($_GET['id']);
        /*foreach ($Act->getPeople() as $man) {
            if ($man->document != 'certificate') continue;
            $persons[] = $man;
        }*/
//        $persons=$this->mass_print();
        if (empty($_POST['pers'])) {
            foreach ($Act->getPeople() as $man) {
                if ($man->document != 'certificate') {
                    continue;
                }
                $persons[] = $man;
            }
            $header_print = array('title' => 'приложений к сертификатам', 'show' => 0);

//            return $this->render->view($this->mass_print());
            return $this->render->view(
                'acts/print/choose_people',
                array(
                    'Act' => $Act,
                    'persons' => $persons,
                    'header_print' => $header_print,
                )
            );
        } else {
            foreach ($Act->getPeople() as $man) {
                if ($man->document != 'certificate' || !in_array($man->id, $_POST['pers'])) {
                    continue;
                }
                $persons[] = $man;
            }
            die(
            $this->render->view(
                'acts/print/pril_cert',
                array(
                    'persons' => $persons,
                )
            )
            );
        }
    }

    protected function act_vidacha_cert_action()
    {
        $act = Act::getByID($_GET['id']);
        $this->checkActIsEditable($act);
        die(
        $this->render->view(
            'acts/print/vidacha_cert',
            array(
                'Act' => $act,
                'People' => $act->getPeople(),
            )
        )
        );
    }

    protected function checkActIsEditable($act)
    {
        if ($act instanceof Act) {
            if ($act->isBlocked() && !$act->isCanEdit()) {
                $_SESSION['flash'] = 'Акт заблокирован. Редактирование невозможно';
                $this->redirectReturn();
            }
        }
    }

    protected function act_vidacha_cert_rudn_action()
    {
        $act = Act::getByID($_GET['id']);
        $this->checkActIsEditable($act);
        /* if (empty($act->ved_vid_cert_num) && $act->test_level_type_id == 2)
         {
             $ved_vid_cert_num=PrintNumberList::generate('ved_vid_cert', 'lc'.$act->getUniversity()->id);
             $act->ved_vid_cert_num = $ved_vid_cert_num->getNumber();
             $act->ved_vid_cert_num_date = date("Y-m-d H:i:s");
             $act->save();
         } */
        if ($act->setVedVidCertNum()) {
            $act->save();
        }
        die(
        $this->render->view(
            'acts/print/vidacha_cert_rudn',
            array(
                'Act' => $act,
                'People' => $act->getPeople(),
            )
        )
        );
    }

    protected function act_vidacha_cert_duplicate_action()
    {
        $man = ActMan::getByID($_GET['id']);
        $act = $man->getAct();
        $this->checkActIsEditable($act);
        die(
        $this->render->view(
            'acts/print/vidacha_cert',
            array(
                'Act' => $act,
                'People' => array($man),
            )
        )
        );
    }

    protected function act_vidacha_note_action()
    {
        $act = Act::getByID($_GET['id']);
        $this->checkActIsEditable($act);
        die(
        $this->render->view(
            'acts/print/vidacha_sprav',
            array(
                'Act' => $act,
            )
        )
        );
    }

    protected function act_vidacha_reestr_action()
    {
        $act = Act::getByID($_GET['id']);
        $this->checkActIsEditable($act);
        die(
        $this->render->view(
            'acts/print/vidacha_cert_reestr',
            array(
                'Act' => $act,
                'People' => $act->getPeople(),
            )
        )
        );
    }

    protected function act_vidacha_reestr_duplicate_action()
    {
        $man = ActMan::getByID($_GET['id']);
        $act = $man->getAct();
        $this->checkActIsEditable($act);
        die(
        $this->render->view(
            'acts/print/vidacha_cert_reestr',
            array(
                'Act' => $act,
                'People' => array($man),
            )
        )
        );
    }

    protected function act_grazhdan_action()
    {
        $man = ActMan::getByID($_GET['id']);
        $man->blank_number = $man->id;
        //  $man->document_nomer = $man->id;
        $man->save();
        $man->getAct()->save();
        die(
        $this->render->view(
            'acts/print/grazhdan',
            array(
                'Man' => $man,
            )
        )
        );
    }

    protected function act_rki_action()
    {
        $man = ActMan::getByID($_GET['id']);
//        $man->blank_number = $man->id;
//        $man->save();
//        $man->getAct()->save();
        $persons = array($man);
        die(
        $this->render->view(
            'acts/print/rki',
            array(
//                'Man' => $man,
                'persons' => $persons,
            )
        )
        );
    }

    protected function act_rkis_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }
        $persons = array();
        $Act = Act::getByID($_GET['id']);
        if (empty($_POST['pers'])) {
            foreach ($Act->getPeople() as $man) {
                if ($man->document != 'note') {
                    continue;
                }
                $persons[] = $man;
            }
            $header_print = array('title' => 'справок', 'show' => 0);

//            return $this->render->view($this->mass_print());
            return $this->render->view(
                'acts/print/choose_people',
                array(
                    'persons' => $persons,
                    'header_print' => $header_print,
                )
            );
        } else {
            foreach ($Act->getPeople() as $man) {
                if ($man->document != 'note' || !in_array($man->id, $_POST['pers'])) {
                    continue;
                }
                $persons[] = $man;
            }
            die(
            $this->render->view(
                'acts/print/rki',
                array(
                    'persons' => $persons,
                )
            )
            );
        }
    }

    protected function search_pupil_action()
    {
        $result = array();
        $query = $certificate = $name = '';
        $archiveResult = [];
        if (count($_POST)) {
            $query = filter_input(INPUT_POST, 'query', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $certificate = filter_input(
                INPUT_POST,
                'certificate',
                FILTER_SANITIZE_STRING,
                FILTER_FLAG_NO_ENCODE_QUOTES
            );
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
//            $blank = $_POST['blank'];
            $archiveSearch = new \SDT\models\Archive\People();
            $archiveResult = $archiveSearch->search(
                [
                    'surname' => $query,
                    'name' => $name,
                    'cert' => $certificate,
                ]
            );
            $result = ActPeople::Search($query, $certificate, $name);
//            echo '<pre>';
//            var_dump($result);
//            echo '</pre>';
//            die(var_dump($result));
        }

        return $this->render->view(
            'search/pupil',
            array(
                'Result' => $result,
                'archive' => $archiveResult,
                'query' => $query,
                'name' => $name,
                'certificate' => $certificate,
            )
        );
    }

    protected function otch_country_action()
    {
        $Result = array(); //Пустой массив для результат
        if (count($_POST)) { // Если пришёл пост
            $_POST['from'] = $this->mysql_date($_POST['from']);
            $_POST['to'] = $this->mysql_date($_POST['to']);
            $Result = new otchet_country($_POST); //Создаём объект отчета
            $Result->Search(); //Производим поиск
        }

        /*
         * Рендерим.
         * 1-я имя файла шаблона  -otchet/country.php
         * 2-й Список переменных доступных в отчете
         */

        return $this->render->view(
            'otchet/country',
            array(
                'Countries' => Countries::getAll(),
                'Result' => $Result,
            )
        );
    }

    protected function man_passport_upload_action()
    {
        $man = ActMan::getByID($_POST['man_id']);
        if (!$man) {
            $this->redirectReturn();
        }
        if (!empty($_FILES['file']['name'])) {
            $file = new File();
            $id = $file->upload($_FILES['file'], $error);
            $_SESSION['flash'] = $error;
            if ($id) {
                if ($man->passport_file) {
                    $oldFile = File::getByID($man->passport_file);
                    $oldFile->delete();
                }
                $man->passport_file = $id;
                $man->save();
            }
        } else {
            $_SESSION['flash'] = 'Файл не выбран';
        }
        //  $this->redirectByAction('act_numbers', array('id' => $man->getAct()->id));
        $this->redirectReturn();
    }

    protected function man_soprovod_upload_action()
    {
        $man = ActMan::getByID($_POST['man_id']);
        if (!$man) {
            $this->redirectReturn();
        }
        if (!empty($_FILES['file']['name'])) {
            $file = new File();
            $id = $file->upload($_FILES['file'], $error);
            $_SESSION['flash'] = $error;
            if ($id) {
                if ($man->soprovod_file) {
                    $oldFile = File::getByID($man->soprovod_file);
                    $oldFile->delete();
                }
                $man->soprovod_file = $id;
                $man->save();
                $man->getAct()->save();
                $_SESSION['flash'] = 'Файл загружен';
            }
        } else {
            $_SESSION['flash'] = 'Файл не выбран';
        }
        $this->redirectReturn();
    }

    protected function search_act_action()
    {
        $result = array();
        $params = array(
            'minAddDate' => Acts::getMinAddDate(),
            'maxAddDate' => Acts::getMaxAddDate(),
            'minTestDate' => Acts::getMinTestDate(),
            'maxTestDate' => Acts::getMaxTestDate(),
            'level' => 0,
            'organization' => 0,
            'state' => 'archive',
            'act_id' => null,
        );
        if (count($_POST)) {
            $_POST['minAddDate'] = $this->mysql_date($_POST['minAddDate']);
            $_POST['maxAddDate'] = $this->mysql_date($_POST['maxAddDate']);
            $_POST['minTestDate'] = $this->mysql_date($_POST['minTestDate']);
            $_POST['maxTestDate'] = $this->mysql_date($_POST['maxTestDate']);
            $params = array_merge($params, $_POST);
            $result = Acts::Search($params);
        }

        return $this->render->view(
            'search/act',
            array(
                'Result' => $result,
                'query' => $params,
                'Universities' => Univesities::getAll(true),
                'Levels' => TestLevels::getAll(),
            )
        );
    }

    protected function help_action()
    {
        $content = include('kolont.php');

        return $content;
    }

    protected function act_table_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }
        $act_id = $_GET['id'];
        $act = Act::getByID($act_id);
        // $people = $act->getPeople();
        if (!$act || $act->isDeleted() || $act->state != Act::STATE_INIT) {
            $this->redirectAccessRestricted();
        }
        $actMeta = $act->getMeta();
        if ($actMeta->special_price_group == 1) {
            $countries = Countries::getDNR();
        } elseif ($actMeta->special_price_group == 2) {
            $countries = Countries::getUkraine();
        } else {
            $countries = Countries::getDefault();
        }
        if (count($_POST)) {
            // gc_enable();
            //     var_dump($_POST);
            if (!empty($_POST['ajax'])) {
                $_POST = $this->recursive_utf_decode($_POST);
                set_time_limit(600);
            }
            if (isset($_POST['user']) && is_array($_POST['user'])) {
                foreach ($_POST['user'] as $id => $params) {
                    $man = ActMan::getByID($id);
                    $man->parseParameters($params);
                    if ($this->userHasRole(Roles::ROLE_CENTER_EXTERNAL_API)) {
                        $man->setValidTill();
                    }
                    $man->save();
                    if (!empty($params['old_blank'])) {
                        $man_old_blank = ManAdditionalExam::getByManID($id);
//                        die(var_dump($man_old_blank));
                        $man_old_blank->old_blank_number = $params['old_blank'];
                        $man_old_blank->save();
                    }
                    //gc_collect_cycles();
                }
            }
            $act->tester1 = $_POST['tester1'];
            $act->tester2 = $_POST['tester2'];
            $act->save();
            //gc_collect_cycles();
            if (!empty($_POST['ajax'])) {
                $data = array();
                $data['Ok'] = true;
                $data['html'] = $this->utf_encode(
                    $this->render->view(
                        'acts/init/table',
                        array(
                            //'people' => $people,
                            'Act' => $act,
                            'Countries' => $countries,
                        )
                    )
                );
                die(json_encode($data));
            }
        }

//var_dump($countries); die;
        return $this->render->view(
            'acts/init/table',
            array(
                //'people' => $people,
                'Act' => $act,
                'Countries' => $countries,
            )
        );
    }

    /*protected function print_certificate_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }

        $persons = array();


        $persons[] = $_GET['id'];

        die($this->render->view(
            'acts/print/certificate',
            array(
                //'Man' => $man,
                'persons' => $persons,

            )
        ));


    }

    protected function print_certificates_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }

        $persons = array();

        $Act = Act::getByID($_GET['id']);
        foreach ($Act->getPeople() as $Man) {
            $persons[] = $Man->id;
        }


        die($this->render->view(
            'acts/print/certificate',
            array(
                //'Man' => $man,
                'persons' => $persons,

            )
        ));


    }*/

    protected function act_passport_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }
        $act = Act::getByID(@$_GET['id']);
        if (!$act) {
            $this->redirectByAction('act_fs_list');
        }
        if (!$act || $act->isDeleted() || $act->state != Act::STATE_INIT) {
            $this->redirectAccessRestricted();
        }
        $_SESSION['flash'] = '';
        if (!empty($_FILES['file'])) {
            $_FILES = $this->multipleFiles($_FILES);
            //die(var_dump($_FILES));
            foreach ($_FILES['file'] as $user_id => $_file) {
                if (empty($_file['name'])) {
                    continue;
                }
                $file = new File();
                $id = $file->upload($_file, $error);
                $man = ActMan::getByID($user_id);
                $_SESSION['flash'] .= '<strong>'.$man->surname_rus.' '.$man->name_rus.'</strong>: '.$error.'<br>';
                if ($id) {
                    if ($man->passport_file) {
                        $oldFile = File::getByID($man->passport_file);
                        $oldFile->delete();
                    }
                    $man->passport_file = $id;
                    $man->save();
                }
            }
        }

        return $this->render->view(
            'acts/init/passports',
            array(
                'people' => $act->getPeople(),
                'Act' => $act,
            )
        );
    }

    protected function act_old_cert_scan_upload_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }
        $act = Act::getByID(@$_GET['id']);
        if (!$act) {
            $this->redirectByAction('act_fs_list');
        }
        $_SESSION['flash'] = '';
        if (!empty($_FILES['file'])) {
            $_FILES = $this->multipleFiles($_FILES);
            //die(var_dump($_FILES));
            foreach ($_FILES['file'] as $user_id => $_file) {
                if (empty($_file['name'])) {
                    continue;
                }
                $file = new File();
                $id = $file->upload($_file, $error);
                $man = ActMan::getByID($user_id);
                $man_additional_data = $man->getAdditionalExam();
                //var_dump($man_additional_data);die;
                $_SESSION['flash'] .= '<strong>'.$man->surname_rus.' '.$man->name_rus.'</strong>: '.$error.'<br>';
                if ($id) {
                    if ($man_additional_data->old_blank_scan) {
                        $oldFile = File::getByID($man_additional_data->old_blank_scan);
                        $oldFile->delete();
                    }
                    $man_additional_data->old_blank_scan = $id;
                    //die(var_dump($man_additional_data));
                    $man_additional_data->save();
                }
            }
        }

        return $this->render->view(
            'acts/init/old_certs',
            array(
                'people' => ActPeople::getAdditionalExam($act->id),
                'Act' => $act,
            )
        );
    }

    protected function act_set_blocked_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }
        $act = Act::getByID($_GET['id']);
        if (!$act->isBlocked()) {
            $act->setBlocked();
            //die(var_dump($act));
            $act->save();
            $_SESSION['flash'] = 'Акт заблокирован';
        } else {
            $_SESSION['flash'] = 'Акт уже был заблокирован';
        }
        $this->redirectReturn('#'.$act->id);
    }

    protected function act_set_unblocked_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }
        $act = Act::getByID($_GET['id']);
        if ($act->isCanUnBlock()) {
            $act->setUnBlocked();
            $act->save();
            $_SESSION['flash'] = 'Акт разблокирован';
        } else {
            $_SESSION['flash'] = 'Акт не разблокирован. У вас недостаточно прав.';
        }
        $this->redirectReturn('#'.$act->id);
    }

    protected function act_universities_received_action()
    {
        $acts = Univesities::getByLevel(ACT::STATE_RECEIVED);

        return $this->render->view(
            'acts/received/universities_list',
            array(
                'list' => $acts,
            )
        );
    }

    protected function act_received_list_action()
    {
        if (empty($_GET['uid']) || !is_numeric($_GET['uid'])) {
            $_SESSION['flash'] = 'Вуз не указан';
            $this->redirectReturn();
        }
        $acts = Acts::getByLevel($_GET['uid'], ACT::STATE_RECEIVED);
        $signings = ActSignings::get4Invoice();

        //var_dump($signings); die();
        return $this->render->view(
            'acts/received/list',
            array(
                'list' => $acts,
                'signings' => $signings,
            )
        );
    }

    protected function act_universities_checks_action()
    {
        $acts = Univesities::getByLevel(ACT::STATE_CHECK);

        return $this->render->view(
            'acts/checks/universities_list',
            array(
                'list' => $acts,
            )
        );
    }

    protected function act_checks_list_action()
    {
        if (empty($_GET['uid']) || !is_numeric($_GET['uid'])) {
            $_SESSION['flash'] = 'Вуз не указан';
            $this->redirectReturn();
        }
        $acts = Acts::getByLevel($_GET['uid'], ACT::STATE_CHECK);
        $signings = ActSignings::get4Invoice();

        //var_dump($signings); die();
        return $this->render->view(
            'acts/checks/list',
            array(
                'list' => $acts,
                'signings' => $signings,
            )
        );
    }

    protected function act_universities_print_action()
    {
        $acts = Univesities::getByLevel(ACT::STATE_PRINT);

        return $this->render->view(
            'acts/print_level/universities_list',
            array(
                'list' => $acts,
            )
        );
    }

    protected function act_universities_action()
    {
        //if (empty($_GET['type']) || (int)$_GET['type']==0) $level_type=1; else $level_type=(int)$_GET['type'];
        //$int_options = array("options"=> array("default"=>1, "min_range"=>1, "max_range"=>2));
        $level_type = filter_input(
            INPUT_GET,
            'type',
            FILTER_VALIDATE_INT,
            array("options" => array("default" => 1, "min_range" => 1, "max_range" => 2))
        );
        $acts = Univesities::getByLevels($level_type);
//die(var_dump($acts));
        $caption = '';
        switch ($level_type) {
            case 1:
                $caption = 'Список локальных центров,
                приславших тестовые сессии по лингводидактическому тестированию';
                break;
            case 2:
                $caption = 'Список локальных центров,
                приславших тестовые сессии по интеграционному экзамену';
                break;
        }
        $result = $this->universitySortByParent($acts);

        return $this->render->view(
            'acts/print_level/universities_list',
            array(
                'list' => $result,
                'level_type' => $level_type,
                'caption' => $caption,
            )
        );
    }

    protected function act_print_list_action()
    {
        if (empty($_GET['uid']) || !is_numeric($_GET['uid'])) {
            $_SESSION['flash'] = 'Вуз не указан';
            $this->redirectReturn();
        }
        $acts = Acts::getByLevel($_GET['uid'], ACT::STATE_PRINT);
        $signings = ActSignings::get4Invoice();

        //var_dump($signings); die();
        return $this->render->view(
            'acts/print_level/list',
            array(
                'list' => $acts,
                'signings' => $signings,
            )
        );
    }

    protected function act_list_action()
    {
        if (empty($_GET['uid']) || !is_numeric($_GET['uid'])) {
            $_SESSION['flash'] = 'Вуз не указан';
            $this->redirectReturn();
        }
        $level_type = filter_input(
            INPUT_GET,
            'type',
            FILTER_VALIDATE_INT,
            array("options" => array("default" => 1, "min_range" => 1, "max_range" => 2))
        );
        $acts = Acts::getByLevels($_GET['uid'], $level_type);
        $signings = ActSignings::get4Invoice();
        $type = '';
        switch ($level_type) {
            case 1:
                $type = ' лингводидактическому тестированию';
                break;
            case 2:
                $type = ' интеграционному экзамену';
                break;
        }

        //var_dump($signings); die();
        return $this->render->view(
            'acts/print_level/list',
            array(
                'type' => $type,
                'list' => $acts,
                'signings' => $signings,
            )
        );
    }

    protected function act_universities_wait_action()
    {
        $acts = Univesities::getByLevel(ACT::STATE_WAIT_PAYMENT);

        return $this->render->view(
            'acts/wait_payment/universities_list',
            array(
                'list' => $acts,
            )
        );
    }

    protected function act_wait_payment_list_action()
    {
        if (empty($_GET['uid']) || !is_numeric($_GET['uid'])) {
            $_SESSION['flash'] = 'Вуз не указан';
            $this->redirectReturn();
        }
        $acts = Acts::getByLevel($_GET['uid'], ACT::STATE_WAIT_PAYMENT);
        $signings = ActSignings::get4Invoice();

        //var_dump($signings); die();
        return $this->render->view(
            'acts/wait_payment/list',
            array(
                'list' => $acts,
                'signings' => $signings,
            )
        );
    }

    protected function act_received_view_action()
    {
        $act = Act::getByID($_GET['id']);
        if (!$act || $act->isDeleted()) {
            $this->redirectAccessRestricted();
        }
        $this->checkActIsEditable($act);
        $act->incrementViewedAndSave();
        $invoiceSignings = ActSignings::get4Invoice();
        $vidachaSignings = ActSignings::get4VidachaCert();

        return $this->render->view(
            'acts/received/view',
            array(
                'object' => $act,
                'invoiceSignings' => $invoiceSignings,
                'vidachaSignings' => $vidachaSignings,
            )
        );
    }

    protected function act_received_table_view_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }
        $act = Act::getByID($_GET['id']);
        if (!$act || $act->isDeleted()) {
            $this->redirectAccessRestricted();
        }
        $this->checkActIsEditable($act);

        return $this->render->view(
            'acts/received/table_view',
            array(
                //'people' => $people,
                'Act' => $act,
            )
        );
    }

    protected function act_receive_numbers_action()
    {
        $act = Act::getByID($_GET['id']);
        if (!$act || $act->isDeleted()) {
            $this->redirectAccessRestricted();
        }
        $this->isDeletedRedirect($act);
        $this->checkActIsEditable($act);
        $act->incrementViewedAndSave();
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
        switch ($act->test_level_type_id) {
            case 1:
                $type = '(Лингводидактическое тестирование)';
                break;
            case 2:
                $type = '(Интеграционный экзамен)';
                break;
        }

        return $this->render->view(
            'acts/received/form_numbers',
            array(
                'Act' => $act,
                'type' => $type,
            )
        );
    }

    protected function print_invoice_action()
    {
        if (count($_POST)) {
            $act = Act::getByID($_POST['id']);
//            $act->invoice_date = $this->mysql_date($_POST['invoice_date']);
            $invoice_date = new DateTime(date('Y-m-d', strtotime($_POST['invoice_date'])));
//            var_dump($invoice_date);
            $act->invoice_date = $act->getPrintDateAfterCheckDate($invoice_date);
//            var_dump(  $act->invoice_date);
//die;
            /*НУЖНО ЛИ? start
                        $dayNow = date('j', strtotime($act->invoice_date));
                        $days = date('t', strtotime($act->invoice_date));
                        if ($act->getUniversity()->getHeadCenter()->horg_id == 1) {
                            if (($days - $dayNow) < PFUR_CHECKED_CHANGE_DATE) {
                                $dt = new DateTime($act->invoice_date. '+' . PFUR_CHECKED_CHANGE_DATE . ' days');
                                $act->invoice_date = $dt->format('Y-m-1');
                            }
                        }
            end*/
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
            $this->redirectByAction('print_invoice', array('id' => $act->id));
        } else {
            $act_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            $act = Act::getByID($act_id);
            if (empty($act)) {
                $this->redirectAccessRestricted();
            }
            $act->save();
        }
        die(
        $this->render->view(
            'acts/print_check',
            array(
                'Act' => $act,
            )
        )
        );
    }




    /*    protected function head_center_action()
        {

            $univers = HeadCenters::getAll();

            return $this->render->view(
                'head_center/all',
                array(
                    'list' => $univers
                )
            );

        }*/
    /* protected function head_center_add_action()
     {

         $univer = new HeadCenter();

         if (count($_POST)) {
             $univer->parseParameters($_POST);
             $univer->save();
             // die(var_dump($univer));
             $params = array('id' => $univer->id);

             $this->redirectByAction('head_center_view', $params);
         }

         return $this->render->form('add', $univer, 'Добавление  головного центра');
     }*/
    /* protected function head_center_edit_action()
     {
         if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
             $this->redirectReturn();
         }

         $univer = HeadCenter::getByID($_GET['id']);

         if (!$univer) {
             $this->redirectAccessRestricted();
         }

         if (count($_POST)) {
             $univer->parseParameters($_POST);
             $univer->save();
             $params = array('id' => $univer->id);

             $this->redirectByAction('head_center_view', $params);
         }

         return $this->render->form('add', $univer, 'Редактирование  головного центра');
     }*/

    protected function act_set_payed_action()
    {
        if (empty($_POST['id']) || !is_numeric($_POST['id'])) {
            $this->redirectReturn();
        }
        $act = Act::getByID($_POST['id']);
        $act->parseParameters($_POST);
        $act->paid = 1;
        $act->save();
        writeStatistic(
            'sdt',
            'act_set_payed',
            array(
                'id' => $act->id,
                'univer_id' => $act->getUniversity()->id,
                'univer_name' => $act->getUniversity()->name,
                'platez_number' => $_POST['platez_number'],
                'platez_date' => $_POST['platez_date'],
            )
        );
        $this->redirectReturn();
    }

    protected function set_archive_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }
        $act = Act::getByID($_GET['id']);
        if ($act->isToArchive()) {
            $act->setStateArchive();
            $_SESSION['flash'] = 'Документ перенесен в архив';
            $act->save();
        } else {
            $_SESSION['flash'] = 'Документ не может быть отправлен в архив';
        }
        $this->redirectReturn();
    }



    /* protected function head_center_view_action()
     {
         if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
             $this->redirectReturn();
         }
         $id = $_GET['id'];
         $univer = HeadCenter::getByID($id);
         if (!$univer) {
             $this->redirectByAction('head_center');
         }

         return $this->render->view('head_center/view', array('object' => $univer));

     }*/
    /*  protected function head_center_delete_action()
      {
          if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
              $this->redirectReturn();
          }
          $univer = HeadCenter::getByID($_GET['id']);
          if (!$univer) {
              $this->redirectByAction('head_center');
          }

          $univer->delete();
          $this->redirectByAction('head_center');

      }*/

    protected function print_certificate_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }
        $persons = array();
        $persons[] = ActMan::getByID($_GET['id']);
//        $persons[] = CertificateDuplicate::checkForDuplicates(ActMan::getByID($_GET['id']));
        die(
        $this->render->view(
            'acts/print/certificate',
            array(
                //'Man' => $man,
                'persons' => $persons,
            )
        )
        );
    }

    protected function print_certificates_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }
        $persons = array();
        $Act = Act::getByID($_GET['id']);
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
            foreach ($Act->getPeople() as $man) {
                if ($man->document != 'certificate') {
                    continue;
                }
                $persons[] = $man;
            }
            $header_print = array('title' => 'сертификатов', 'show' => 1);

//            return $this->render->view($this->mass_print());
            return $this->render->view(
                'acts/print/choose_people',
                array(
                    'Act' => $Act,
                    'persons' => $persons,
                    'header_print' => $header_print,
                )
            );
        } else {
            foreach ($Act->getPeople() as $man) {
                if ($man->document != 'certificate' || !in_array($man->id, $_POST['pers'])) {
                    continue;
                }
                $persons[] = $man;
            }
            if (!$Act->is_printed) {
                /*      $m = Messaging::getInstance();
                      $m->NewMessage(
                          $m->getCurrentKey(),
                          array(
                              $m->getLocalKey($Act->university_id)
                          ),
                          'Головной центр распечатал сертификаты',
                          'Для акта от ' . $this->date($Act->created) . ' были распечатаны сертификаты',
                          true
                      );*/
            }
            $Act->setPrinted();
            die(
            $this->render->view(
                'acts/print/certificate',
                array(
                    'persons' => $persons,
                )
            )
            );
        }
    }

    protected function oncheck_additional_exam_action()
    {
        $act_id = filter_input(INPUT_GET, 'act_id', FILTER_VALIDATE_INT);
        $act = Act::getByID($act_id);
        if ($act->state != Act::STATE_SEND) {
            $this->redirectAccessRestricted();
        }
        if (!$act) {
            $this->redirectAccessRestricted();
        }
        $people = ActPeople::getAdditionalExam($act_id);

        return $this->render->view(
            'acts/act_check/people_additional_exam',
            array(
                'Act' => $act,
                'people' => $people,
            )
        );
    }

    protected function oncheck_approve_additional_exam_action()
    {
        $man_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $man = ActMan::getByID($man_id);
        if (!$man) {
            $this->redirectAccessRestricted();
        }
        $ex = $man->getAdditionalExam();
        if (!$ex) {
            $_SESSION['flash'] = 'У тестируемого нет досдач';
        }
        $ex->approve();
        $this->redirectReturn();
    }

    protected function checked_list_action()
    {
        $acts = Acts::getListByLevel4Head(Act::STATE_CHECKED);

        return $this->render->view(
            'acts/list_head_inactive',
            array(
                'list' => $acts,
                'legend' => 'На оформлении',
            )
        );
    }

    protected function rework_list_action()
    {
        $acts = Acts::getListRework();

        return $this->render->view(
            'acts/list_head_inactive',
            array(
                'list' => $acts,
                'legend' => 'На доработке',
            )
        );
    }


    /*public function groups_edit_action()
    {

        $id = CURRENT_HEAD_CENTER;
        $univer = HeadCenter::getByID($id);
        /*if (!$univer) {
            $this->redirectByAction('head_center');
        }*/
    /* return $this->render->view('root/groups_edit', array('object' => $univer));

 }*/

    protected function act_finished_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }
        $act = Act::getByID($_GET['id']);
        if ($act->state != Act::STATE_CHECKED) {
            $this->redirectAccessRestricted();
        }
        $act->setStateFinished();
        $act->save();
        writeStatistic(
            'sdt',
            'act_finished',
            array(
                'id' => $act->id,
                'univer_id' => $act->getUniversity()->id,
                'univer_name' => $act->getUniversity()->name,
            )
        );
        $_SESSION['flash'] = 'Акт отправлен';
        $this->redirectReturn();
    }

    protected function act_print_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id']) || empty($_POST['s_id']) || !is_numeric($_POST['s_id'])) {
            $this->redirectReturn();
        }
        $act = Act::getByID($_GET['id']);
        $signer = ActSigning::getByID($_POST['s_id']);
        $is_head = University::isHead($_GET['id']);
        /* if ($is_head == 1) {
             $print = 'act_head';
         } else {*/
        $print = 'act';
        /*  }*/
        if (!$signer->id) {
            $this->redirectReturn();
        }
        $template = ($this->render->view(
            'acts/third/'.$print,
            array(
                'act' => $act,
                'signer' => $signer,
            )
        ));
//die($template);
        $filename = 'act_'.$act->id.'.html';
        $filepath = SDT_UPLOAD_SUMMARY_TABLE_DIR.'temp/'.$filename;
        file_put_contents($filepath, $template);
        $table = new ActSummaryTable();
        $table->move($filename, $filepath, $errors, ActSummaryTable::ACT);
        $HTML_table = HTMLActFile::getByActID($act->id);
        if (empty($HTML_table)) {
            $HTML_table = new HTMLActFile();
            $HTML_table->act_id = $act->id;
        }
        if ($HTML_table->file_act_id) {
            $old_table = ActSummaryTable::getByID($HTML_table->file_act_id);
            $old_table->delete();
        }
        $HTML_table->file_act_id = $table->id;
        $HTML_table->save();
        $this->redirectByAction('act_print_view', array('id' => $act->id));
    }


    /*protected function act_table_print_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }
        $act = Act::getByID($_GET['id']);

        die($this->render->view(
            'acts/third/table',
            array(
                'act' => $act,

            )
        ));
    }*/

    protected function act_print_old_template_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id']) || empty($_GET['s_id']) || !is_numeric($_GET['s_id'])) {
            $this->redirectReturn();
        }
        $act = Act::getByID($_GET['id']);
        $print = 'act_old_template';
        $signer = ActSigning::getByID($_GET['s_id']);
        /*if (!$signer->id) {
            $this->redirectReturn();
        }*/
        die(
        $this->render->view(
            'acts/third/'.$print,
            array(
                'act' => $act,
                'signer' => $signer,
            )
        )
        );
    }

    protected function archive_summary_table_print_action()
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if (!in_array($id, Sdt_Config::getSummaryProtocolArchive())) {
            $this->redirectAccessRestricted();
        }

        return $this->summary_table_print_action();
    }

    protected function summary_table_print_action()
    {
//die(var_dump($_POST));
        if (empty($_POST['id']) || !is_numeric($_POST['id']) || empty($_POST['hs_id']) || !is_numeric(
                $_POST['hs_id']
            )) {
//            if (empty($_GET['id']) || !is_numeric($_GET['id']) || empty($_GET['hs_id']) || !is_numeric($_GET['hs_id'])) {
            $this->redirectReturn();
        }
        /*sd*/ /* $_POST['hs_id']=$_GET['hs_id'];
         $_POST['id']=$_GET['id'];*/
        /*sd*/
        $act = Act::getByID($_POST['id']);
        $signer = ActSigning::getByID($_POST['hs_id']);
        if ($_POST['ls'] == 'responsible') {
            $local_signer = $act->responsible;
        } else {
            if ($_POST['ls'] == 'official') {
                $local_signer = $act->official;
            } else {
                $local_signer = '';
            }
        }
        /*$local_signer=$act->responsible;
        $local_signer=$act->official;
*/
        $is_head = University::isHead($_POST['id']);
        /* if ($is_head == 1) {
             $print = 'act_head';
         } else {*/
        /*  }*/
        if (!$signer->id) {
            $this->redirectReturn();
        }
        /* die($this->render->view(
             'acts/third/summary_table',
             array(
                 'act' => $act,
                 'signer' => $signer,
                 'local_signer' => $local_signer,

             )
         ));*/
        /*sd*/
        $template = ($this->render->view(
            'acts/third/summary_table',
            array(
                'act' => $act,
                'signer' => $signer,
                'local_signer' => $local_signer,
            )
        ));
        /*sd*/
//die($template);
        $filename = $act->id.'.html';
        $filepath = SDT_UPLOAD_SUMMARY_TABLE_DIR.'temp/'.$filename;
        file_put_contents($filepath, $template);
        $table = new ActSummaryTable();
        $table->move($filename, $filepath, $errros, $table::SUMMARY_TABLE);
        if ($act->summary_table_id) {
            $old_table = ActSummaryTable::getByID($act->summary_table_id);
            $old_table->delete();
        }
        $act->summary_table_id = $table->id;
        $act->save();
//            die($template);
        $this->redirectByAction('act_summary_table', array('id' => $act->id));
    }

    /**
     * Ак
     */
    protected function act_print_migrant_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id']) || empty($_POST['s_id']) || !is_numeric($_POST['s_id'])) {
            $this->redirectReturn();
        }
        $act = Act::getByID($_GET['id']);
        $signer = ActSigning::getByID($_POST['s_id']);
        $is_head = University::isHead($_GET['id']);
        $print = 'act_migrant';
        if (!$signer->id) {
            $this->redirectReturn();
        }
        $template = ($this->render->view(
            'acts/third/'.$print,
            array(
                'act' => $act,
                'signer' => $signer,
            )
        ));
//        die($template);
        $filename = 'act_'.$act->id.'.html';
        $filepath = SDT_UPLOAD_SUMMARY_TABLE_DIR.'temp/'.$filename;
        file_put_contents($filepath, $template);
        $table = new ActSummaryTable();
        $table->move($filename, $filepath, $errors, ActSummaryTable::ACT);
        $HTML_table = HTMLActFile::getByActID($act->id);
        if (empty($HTML_table)) {
            $HTML_table = new HTMLActFile();
            $HTML_table->act_id = $act->id;
        }
        if ($HTML_table->file_act_id) {
            $old_table = ActSummaryTable::getByID($HTML_table->file_act_id);
            $old_table->delete();
        }
        $HTML_table->file_act_id = $table->id;
        $HTML_table->save();
        $this->redirectByAction('act_print_view', array('id' => $act->id));
    }

    protected function act_table_print_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }
        $act = Act::getByID($_GET['id']);
        $template = ($this->render->view(
            'acts/third/table',
            array(
                'act' => $act,
            )
        ));
        $filename = 'act_table_'.$act->id.'.html';
        $filepath = SDT_UPLOAD_SUMMARY_TABLE_DIR.'temp/'.$filename;
        file_put_contents($filepath, $template);
        $table = new ActSummaryTable();
        $table->move($filename, $filepath, $errors, ActSummaryTable::ACT_TABLE);
        $HTML_table = HTMLActFile::getByActID($act->id);
        if (empty($HTML_table)) {
            $HTML_table = new HTMLActFile();
            $HTML_table->act_id = $act->id;
        }
        if ($HTML_table->file_act_tabl_id) {
            $old_table = ActSummaryTable::getByID($HTML_table->file_act_tabl_id);
            $old_table->delete();
        }
        $HTML_table->file_act_tabl_id = $table->id;
        $HTML_table->save();
        $this->redirectByAction('act_table_print_view', array('id' => $act->id));
    }

    protected function act_table_print_view_action()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            $this->redirectAccessRestricted();
        }
        $act = Act::getByID($id);
        if (!$act) {
            $this->redirectAccessRestricted();
        }
        $HTML_table = HTMLActFile::getByActID($act->id);
        if (!$HTML_table) {
//            $this->redirectAccessRestricted();
            die('Файл не существует');
        }
        $file = ActSummaryTable::getByID($HTML_table->file_act_tabl_id);
        if (!$file) {
            die('Файл не существует');
        }
        $file->show();
    }

    protected function act_print_view_action()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            $this->redirectAccessRestricted();
        }
        $act = Act::getByID($id);
//        die(var_dump($act,$id));
        if (!$act) {
            $this->redirectAccessRestricted();
        }
        $HTML_table = HTMLActFile::getByActID($act->id);
        if (!$HTML_table) {
            die('Файл не существует');
//            $this->redirectAccessRestricted();
        }
        $file = ActSummaryTable::getByID($HTML_table->file_act_id);
        if (!$file) {
            die('Файл не существует');
        }
        $file->show();
    }

    protected function act_upload_dogovor_scan_action()
    {
        $dogovor = University_dogovor::getByID(@$_POST['id']);
        if (!$dogovor) {
            $this->redirectAccessRestricted();
        }
        if (!empty($_FILES['file']['name'])) {
            $file = new File();
            $id = $file->upload($_FILES['file'], $error);
            $_SESSION['flash'] = $error;
            if ($id) {
                if ($dogovor->scan_id) {
                    $oldFile = File::getByID($dogovor->scan_id);
                    $oldFile->delete();
                }
                $dogovor->scan_id = $id;
                $dogovor->save();
            }
        } else {
            $_SESSION['flash'] = 'Файл не выбран';
        }
        $this->redirectReturn();
    }

    protected function scan_blank_upload_action()
    {
        if (empty($_GET['id'])) {
            $this->redirectReturn();
        }
        $Act = Act::getByID($_GET['id']);
        $this->checkActIsEditable($Act);
        if (!$Act) {
            $this->redirectReturn();
        }
        $_SESSION['flash'] = '';
        /*   if (!empty($_FILES['file'])) {
               $_FILES = $this->multipleFiles($_FILES);

               foreach ($_FILES['file'] as $user_id => $_file) {
                   if (empty($_file['name'])) {
                       continue;
                   }
                   $file = new File();
                   $id = $file->upload($_file, $error);
                   $man = ActMan::getByID($user_id);
                   $_SESSION['flash'] .= '<strong>' . $man->surname_rus . ' ' . $man->name_rus . '</strong>: ' . $error . '<br>';
                   if ($id) {


                       if ($man->soprovod_file) {
                           $oldFile = File::getByID($man->passport_file);
                           $oldFile->delete();
                       }
                       $man->soprovod_file = $id;
                       $man->save();
                   }
               }
           }*/
        if (!empty($_POST['blank'])) {
//         die(var_dump($_POST));
            foreach ($_POST['blank'] as $user_id => $text) {
                $man = ActMan::getByID($user_id);
                $man->blank_number = $text;
                if (!empty($_POST['blank_date'])) {
                    if (array_key_exists($user_id, $_POST['blank_date'])) {
                        $man->blank_date = date('Y-m-d', strtotime($_POST['blank_date'][$user_id]));
                    }
                }
                $man->save();
            }
        }
        $Act->save();
        $people = $Act->getPeople();
        $scansLeft = 0;
        $blanksLeft = 0;
        foreach ($people as $man) {
            if (empty($man->blank_number) && $man->document == 'certificate') {
                $blanksLeft++;
            }
            /*   if (empty($man->soprovod_file)) {
                   $scansLeft++;
               }*/
        }

        return $this->render->view(
            'acts\received\scan_blank_upload',
            array(
                'Act' => $Act,
                'people' => $people,
                'scansLeft' => $scansLeft,
                'blanksLeft' => $blanksLeft,
            )
        );
    }

    protected function current_head_center_edit_action()
    {
        $hc = HeadCenter::getByID(CURRENT_HEAD_CENTER);
        if (!$hc) {
            $this->redirectAccessRestricted();
        }
        if (count($_POST)) {
            $hc->parseParameters($_POST);
            $hc->save();
            $params = array('id' => $hc->id);
            $_SESSION['flash'] = 'Информация сохранена';
            $this->redirectByAction('current_head_center_view', $params);
        }

        return $this->render->form('add', $hc, 'Редактирование информации о головном центре');
    }

    protected function current_head_center_edit_text_action()
    {
        $univer = HeadCenterText::getByHeadCenterID(CURRENT_HEAD_CENTER);
//        die(var_dump($univer));
        if (!$univer) {
            $this->redirectAccessRestricted();
        }
        if (count($_POST)) {
            $univer->parseParameters($_POST);
            $univer->save();
            $params = array('id' => $univer->id);
            $this->redirectByAction('current_head_center_text_view', $params);
        }

        return $this->render->form('add', $univer, 'Редактирование переменных для печати головного центра');
    }

    protected function current_head_center_text_view_action()
    {
        $id = CURRENT_HEAD_CENTER;
        $univer = HeadCenterText::getByHeadCenterID($id);
        if (!$univer) {
            $this->redirectByAction('head_center');
        }

        return $this->render->view('head_center_text/view', array('object' => $univer));
    }

    protected function current_head_center_view_action()
    {
        $id = CURRENT_HEAD_CENTER;
        $univer = HeadCenter::getByID($id);
        if (!$univer) {
            $this->redirectByAction('head_center');
        }

        return $this->render->view('head_center/current_view', array('object' => $univer));
    }

    protected function signing_list_action()
    {
        $users = ActSignings::getAll();

        return $this->render->view(
            'signing/list',
            array(
                'items' => $users,
            )
        );
    }

    protected function user_type_list_action()
    {
        $types = UserTypes::getAll();

        return $this->render->view(
            'root/user_types_list',
            array(
                'list' => $types,
            )
        );
    }

    protected function user_type_add_action()
    {
        $group = new UserType();
        if (count($_POST)) {
            $group->parseParameters($_POST);
            $group->save();
            $this->redirectByAction('user_type_list');
        }

        return $this->render->form('add', $group, 'Добавление типа пользователя');
    }

    protected function user_type_edit_action()
    {
        $group = UserType::getByID($_GET['id']);
        if (count($_POST)) {
            $group->parseParameters($_POST);
            $group->save();
            $this->redirectByAction('user_type_list');
        }

        return $this->render->form('add', $group, 'Редактирование группы');
    }

    protected function user_type_delete_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }
        $group = UserType::getByID($_GET['id']);
        $group->delete();
        $_SESSION['flash'] = 'Тип пользователя удален';
        $this->redirectReturn();
    }

    protected function edit_user_list_action()
    {
        $users = Users::getAll();
        $new_list = array();
        foreach ($users as $user) {
            $new_list[$user->user_type_id][] = $user;
        }
        $types = UserTypes::getAll();

        return $this->render->view(
            'root/edit_user',
            array(
                'users' => $users,
                'list' => $types,
                'new_list' => $new_list,
            )
        );
    }

    protected function add_user_list_action()
    {
        $users = Users::getAll();
        $types = UserTypes::getAll();

        return $this->render->view(
            'root/add_user',
            array(
                'users' => $users,
                'list' => $types,
            )
        );
    }

    /**
     * @return string
     * @throws \SDT\Exception\Http\AccessRestricted
     */
    protected function certificate_add_action()
    {
        $types = TestLevelTypes::getAll();
        $type = filter_input(INPUT_POST, 'type', FILTER_VALIDATE_INT);
        $blank_type_id = 'blank_type_tt_'.$type;
        $blank_type = filter_input(INPUT_POST, $blank_type_id, FILTER_VALIDATE_INT);
        if (empty($blank_type)) {
            $blank_type = 1;
        }
        //die ($blank_type_id.'zzz');
        $series = filter_input(INPUT_POST, 'series');
        $start = filter_input(INPUT_POST, 'start');
        $end = filter_input(INPUT_POST, 'end');
        $items = array();

        return $this->render->view(
            'head_center/certificate/add',
            array(
                'types' => $types,
                'type' => $type,
                'start' => $start,
                'end' => $end,
                'items' => $items,
                'series' => $series,
                'blank_type' => $blank_type,
//                'list' => $list
            )
        );
    }

    protected function certificate_type_list_action()
    {
        $types = TestLevelTypes::getAll();

        return $this->render->view(
            'head_center/certificate/type_list',
            array(
                'types' => $types,
                /*'isPfur' => $isPfur,*/
            )
        );
    }

    protected function certificate_list_action()
    {
        $type_id = filter_input(INPUT_GET, 'type', FILTER_VALIDATE_INT);
        $type = TestLevelType::getByID($type_id);
//        die(var_dump($type));
        if (!$type) {
            $this->redirectAccessRestricted();
        }
        $activeCerts = CertificateReservedList::getActiveByType($type_id);

//var_dump($activeCerts);
        return $this->render->view(
            'head_center/certificate/list',
            array(
                'type' => $type,
                'items' => $activeCerts,
            )
        );
    }

    protected function certificate_delete_action()
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $reason = $this->utf_decode(
            filter_input(INPUT_POST, 'reason', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES)
        );
        $cert = CertificateReserved::getByID($id);
        if ($cert) {
            $cert->delete();
            CertificateInvalid::add_cert_reserved($cert, $reason);
//            add_cert_reserved
            die(
            json_encode(
                array('ok' => true)
            )
            );
        } else {
            die(
            json_encode(
                array(
                    'ok' => false,
                    'error' => $this->utf_encode('Не найдено'),
                )
            )
            );
        }
        die(
        json_encode(
            array(
                'ok' => false,
            )
        )
        );
    }

    protected function certificate_approve_action()
    {
        $type_id = filter_input(INPUT_POST, 'type', FILTER_VALIDATE_INT);
        $blank_type_id = 'blank_type_tt_'.$type_id;
        $blank_type = filter_input(INPUT_POST, $blank_type_id, FILTER_VALIDATE_INT);
        if (empty($blank_type)) {
            $blank_type = 1;
        }
        //echo ($blank_type.'zzz');
        //$blank_type = filter_input(INPUT_POST, 'blank_type', FILTER_VALIDATE_INT);
        $series = trim(filter_input(INPUT_POST, 'series'));
        $start = trim(filter_input(INPUT_POST, 'start'));
        $end = trim(filter_input(INPUT_POST, 'end'));
//        $items = array();
        if ($start === false || empty($type_id) || empty($series) || empty($end)) {
            $_SESSION['flash'] = 'Заполните все поля';
            $this->redirectByAction('certificate_add');
        }
        $type = TestLevelType::getByID($type_id);
        if ($start > $end) {
            $midle = $start;
            $start = $end;
            $end = $midle;
        }
        $items = array();
        $busy = array();
        if ($series && $start && $end) {
            /*   $item = $start;
               while ($item <= $end) {
                   $items[] = sprintf("%012d", $item);
                   $item++;
               }*/
            $values = range($start, $end);
            foreach ($values as $value) {
                $number = sprintf("%04s%08s", $series, $value);
                $item = array(
                    'number' => $number,
                    'exist' => CertificateReserved::ifExist($number, $type_id),
                    'type' => $type,
                    'blank_type' => $blank_type,
                );
                if ($item['exist']) {
                    $busy[] = $item;
                } else {
                    $items[] = $item;
                }
            }
        }

        return $this->render->view(
            'head_center/certificate/approve',
            array(
                'items' => $items,
                'busy' => $busy,
                'type' => $type,
                'blank_type' => $blank_type,
//                'list' => $list
            )
        );
    }

    protected function certificate_submit_action()
    {
        $toAdd = filter_input(INPUT_POST, 'item', FILTER_DEFAULT, array('flags' => FILTER_REQUIRE_ARRAY));
        $type_id = filter_input(INPUT_POST, 'type', FILTER_VALIDATE_INT);
        $blank_type = filter_input(INPUT_POST, 'blank_type', FILTER_VALIDATE_INT);
        if (empty($toAdd) || empty($type_id)) {
            return $this->renderText('<div class="text-error">Не указан список номеров документов</div>');
        }
        $type = TestLevelType::getByID($type_id);
        $added = array();
        $error = array();
        foreach ($toAdd as $number) {
            if (!CertificateReserved::ifExist($number, $type_id)) {
                $item = new CertificateReserved();
                $item->number = $number;
                $item->test_type_id = $type_id;
                $item->blank_type_id = $blank_type;
                $item->head_center_id = CURRENT_HEAD_CENTER;
                if ($item->save()) {
                    $added[] = $number;
                } else {
                    $error[] = $number;
                }
            } else {
                $error[] = $number;
            }
        }

//        die(var_dump($added, $error));
        return $this->render->view(
            'head_center/certificate/submit',
            array(//                'list' => $list
                'added' => $added,
                'error' => $error,
                'type' => $type,
                'blank_type' => $blank_type,
            )
        );
    }

    protected function generate_numbers_action()
    {
//        die('test');
        $act = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$act) {
            $this->redirectAccessRestricted();
        }
        $act = Act::getByID($act);
//        die(var_dump($act->getPeople()));
//        DocumentNumber::lock();
        foreach ($act->getPeople() as $Man) {
            $Man->assignNumbers();
        }
        $act->save();
//        DocumentNumber::unlock();
        $_SESSION['flash'] = 'Номера документов заполнены';
        $this->redirectReturn();
    }

    protected function act_insert_blanks_action()
    {
//        die('test');
        $act = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $act = Act::getByID($act);
        if (!$act) {
            $this->redirectAccessRestricted();
        }
//        die(var_dump($act->getPeople()));
//        DocumentNumber::lock();
        foreach ($act->getPeople() as $Man) {
            if ($Man->is_anull()) {
                continue;
            }
            if ($Man->needBlank()) {
                $Man->assignBlank();
            }
        }
        $act->setFinallyChecked();
//        DocumentNumber::unlock();
        $act->setVedVidCertNum(); // Вставка/проверка номера ведомости выдачи сертификатов
        $act->save();
        $_SESSION['flash'] = 'Номера бланков заполнены';
        $this->redirectReturn();
    }

    protected function man_issue_duplicate_action()
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $personal_changed = filter_input(INPUT_POST, 'personal_changed', FILTER_VALIDATE_INT);
        $man = ActMan::getByID($id);
        if (!$man) {
            $this->redirectAccessRestricted();
        }
        CertificateDuplicate::checkForDuplicates($man);
        $cd = new CertificateDuplicate();
        if (!empty($_FILES['request']['name'])) {
            $file = new File();
            $request_file_id = $file->upload($_FILES['request'], $error);
            if (!$request_file_id) {
                $_SESSION['flash'] = $error;
                $this->redirectReturn();
            }
            $cd->request_file_id = $request_file_id;
        } else {
            $_SESSION['flash'] = 'Необходимо загрузить файл заявления';
            $this->redirectReturn();
        }
        if ($personal_changed) {
            if (!empty($_FILES['passport']['name'])) {
                $file = new File();
                $passport_file_id = $file->upload($_FILES['passport'], $error);
                if (!$passport_file_id) {
                    $_SESSION['flash'] = $error;
                    $this->redirectReturn();
                }
                $cd->passport_file_id = $passport_file_id;
            } else {
                $_SESSION['flash'] = 'Необходимо загрузить файл паспорта';
                $this->redirectReturn();
            }
        }
        $act = $man->getAct();
        $newCert = CertificateReserved::getActiveByType($act->test_level_type_id);
        if (!$newCert) {
            $_SESSION['flash'] = 'Нет свободных номеров бланков. Необходимо ввести новые в систему';
            $this->redirectReturn();
        }
//        (var_dump($res));
        $cd->user_id = $man->id;
        $cd->personal_data_changed = (bool)$personal_changed;
        if (!$personal_changed) {
            $cd->surname_rus = $man->getSurname_rus();
            $cd->name_rus = $man->getName_rus();
            $cd->surname_lat = $man->getSurname_lat();
            $cd->name_lat = $man->getName_lat();
        } else {
            $cd->surname_rus = trim(
                filter_input(INPUT_POST, 'surname_rus', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES)
            );
            $cd->name_rus = trim(
                filter_input(INPUT_POST, 'name_rus', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES)
            );
            $cd->surname_lat = trim(
                filter_input(INPUT_POST, 'surname_lat', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES)
            );
            $cd->name_lat = trim(
                filter_input(INPUT_POST, 'name_lat', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES)
            );
            if (empty($cd->surname_rus) || empty($cd->name_rus) || empty($cd->surname_lat) || empty($cd->name_lat)) {
                $_SESSION['flash'] = 'Необходимо указать все личные данные';
                $this->redirectReturn();
            }
        }
        if ((empty($man->blank_date) || $man->blank_date == '0000-00-00')) {
//            Connection::getInstance()->encoding_win();
            if (empty($_POST['blank_date'])) {
                $_SESSION['flash'] = 'Необходимо указать дату выдачи первого бланка';
                $this->redirectReturn();
            }
            $old_blank_date = trim(
                filter_input(INPUT_POST, 'blank_date', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES)
            );
            $old_blank_date = date("Y-m-d", strtotime($old_blank_date));
            $man->blank_date = $old_blank_date;
            $man->setValidTill();
//            die(var_dump($man));
            $man->save();
        }
        $now = new DateTime();
        $checkDate = new DateTime($act->check_date);
        if ($now >= $checkDate) {
            $certificate_issue_date = date('Y-m-d H:i');
        } else {
            $certificate_issue_date = $checkDate->format('Y-m-d').' '.date('H:i');
        }
        $cd->certificate_issue_date = $certificate_issue_date;
        $cd->certificate_print_date = $man->blank_date;
        $cd->certificate_id = $newCert->id;
        $cd->certificate_number = $newCert->number;
        $_SESSION['flash'] = 'Присвоен новый номер бланка';
        $cd->save();
        $newCert->used = 1;
        $newCert->save();
        /*переводит в utf*/
        CertificateUsed::add(
            $man,
            array(
                'duplicate' => $cd,
            )
        );
        $this->redirectReturn();
    }

    protected function man_blank_invalid_action()
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $reason = $this->utf_decode(
            filter_input(INPUT_POST, 'reason', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES)
        );
        $man = ActMan::getByID($id);
        if (!$man) {
            $this->redirectAccessRestricted();
        }
        $res = $man->invalidateBlank($reason);
//        (var_dump($res));
        $_SESSION['flash'] = 'Бланк помечен как недействительный';
        die(
        json_encode(
            array(
                'result' => 'ok',
            )
        )
        );
    }

    protected function man_duplicate_action()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $man = ActMan::getByID($id);
        if (!$man) {
            $this->redirectAccessRestricted();
        }
        CertificateDuplicate::checkForDuplicates($man);
        $duplicates = false;
        if ($man->duplicate) {
            $duplicates = CertificateDuplicates::getAllByUserID($id);
        }

        return $this->render->view(
            'man/duplicate',
            array(
                'man' => $man,
                'duplicates' => $duplicates,
            )
        );
    }

    protected function check_old_cert_action()
    {
        $certificate = filter_input(INPUT_POST, 'num', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        $certificate = $this->utf_decode($certificate);
        $new_man_id = filter_input(INPUT_POST, 'new_man_id', FILTER_VALIDATE_INT);
        $result = ActMan::searchByCertNum($certificate);
        if (!empty($result)) {
            $man = CertificateDuplicate::checkForDuplicates($result);
            $testlevel = $man->getLevel();
            $testlevel_id = $man->getLevel()->id;
            $available_levels = ActMan::getByID($new_man_id)->getLevel()->getAvailableLevels();
            //die(var_dump($available_levels));
            if (!count($available_levels) || !in_array($testlevel_id, $available_levels)) {
                die(
                json_encode(
                    array(
                        //'result' => $return,
                        'error' => 'denied_level',
                    )
                )
                );
            }
            if ($man->getBlank_number() == $certificate) {
                $return = array();
                $return['surname_rus'] = $this->encode($man->getSurname_rus());
                $return['surname_lat'] = $this->encode($man->getSurname_lat());
                $return['name_rus'] = $this->encode($man->getName_rus());
                $return['name_lat'] = $this->encode($man->getName_lat());
                $return['old_man_id'] = $man->id;
                /*
                            $return['country_id']=$man->country_id;



                            $return['passport_name']=$this->encode ($man->passport_name);
                            $return['passport_series']=$this->encode ($man->passport_series);
                            $return['passport']=$this->encode ($man->passport);
                            $return['passport_date']=$this->date($man->passport_date);
                            $return['passport_department']=$this->encode ($man->passport_department);

                            $return['birth_place']=$this->encode ($man->birth_place);
                            $return['birth_date']=$this->date($man->birth_date);

                            $return['migration_card_number']=$this->encode ($man->migration_card_number);
                            $return['migration_card_series']=$this->encode ($man->migration_card_series);

                */
                die(
                json_encode(
                    array(
                        'result' => $return,
                        'error' => 'ok',
                    )
                )
                );
            }
        }
        die(
        json_encode(
            array(
                'error' => 'not_found',
            )
        )
        );
    }

    protected function paste_old_cert_action()
    {
        $certificate = filter_input(INPUT_POST, 'num', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        $old_man_id = filter_input(INPUT_POST, 'old_man_id', FILTER_VALIDATE_INT);
        $new_man_id = filter_input(INPUT_POST, 'new_man_id', FILTER_VALIDATE_INT);
        /*$new_man_test_id = filter_input(INPUT_POST, 'new_man_test_id', FILTER_VALIDATE_INT);
        $new_man_act_id = filter_input(INPUT_POST, 'new_man_act_id', FILTER_VALIDATE_INT);*/ //die($old_man_id);
        /* die(json_encode(
             array(
                 'result' => $old_man_id,
                 'error' => 'ok',
             )
         ));*/
        $result = ActMan::getByID($old_man_id);
        if (!empty($result)) {
            $man = CertificateDuplicate::checkForDuplicates($result);
//        $blank_number=$man->getBlank_number();
            if ($man->getBlank_number() == $certificate) {
                $return = array();
//            $return['blank_number']=$man->getBlank_number();
//                $file_passport=$man->getDuplicateFilePassport();
                $newman = ActMan::getByID($new_man_id);
                $return['surname_rus'] = $man->getSurname_rus();
                $return['surname_rus'] = $man->getSurname_rus();
                $return['surname_lat'] = $man->getSurname_lat();
                $return['name_rus'] = $man->getName_rus();
                $return['name_lat'] = $man->getName_lat();
                $return['country_id'] = $man->country_id;
//            $return['testing_date']=$this->date($man->testing_date);
                $return['passport_name'] = $man->passport_name;
                $return['passport_series'] = $man->passport_series;
                $return['passport'] = $man->passport;
                $return['passport_date'] = $man->passport_date;
                $return['passport_department'] = $man->passport_department;
                $return['birth_place'] = $man->birth_place;
                $return['birth_date'] = $man->birth_date;
                $return['migration_card_number'] = $man->migration_card_number;
                $return['migration_card_series'] = $man->migration_card_series;
//die(var_dump($return));
                $newman->parseParameters($return);
                $newman->passport_file = File::duplicateFile($man->getIfDuplicatedFilePassport());
//                $return['act_id'] = 13967;
//                $return['test_id = $man->test_id;
//                $newman = new ActMan($return);
//                $newman->id= $new_man_id;
//                $newman->act_id= $new_man_act_id;
//                $newman->test_id= $new_man_test_id;
                $newman->saveOldCertMan();
                $return = array();
                $return['surname_rus'] = $this->encode($man->getSurname_rus());
                $return['surname_lat'] = $this->encode($man->getSurname_lat());
                $return['name_rus'] = $this->encode($man->getName_rus());
                $return['name_lat'] = $this->encode($man->getName_lat());
                $return['country_id'] = $man->country_id;
                $return['passport_name'] = $this->encode($man->passport_name);
                $return['passport_series'] = $this->encode($man->passport_series);
                $return['passport'] = $this->encode($man->passport);
                $return['passport_date'] = $this->date($man->passport_date);
                $return['passport_department'] = $this->encode($man->passport_department);
                $return['birth_place'] = $this->encode($man->birth_place);
                $return['birth_date'] = $this->date($man->birth_date);
                $return['migration_card_number'] = $this->encode($man->migration_card_number);
                $return['migration_card_series'] = $this->encode($man->migration_card_series);
                /**/
                die(
                json_encode(
                    array(
                        'result' => $return,
                        'error' => 'ok',
                    )
                )
                );
            }
        }
        die(
        json_encode(
            array(
                'error' => 'not_found',
            )
        )
        );
    }

    protected function act_summary_table_action()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            $this->redirectAccessRestricted();
        }
        $act = Act::getByID($id);
        if (!$act) {
            $this->redirectAccessRestricted();
        }
        $file = ActSummaryTable::getByID($act->summary_table_id);
        if (!$file) {
            die('Файл не существует');
        }
//        session_write_close();
        $file->show();
    }

    protected function paste_old_cert_manually_action()
    {
        $certificate = filter_input(INPUT_POST, 'num', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        $old_man_id = filter_input(INPUT_POST, 'old_man_id', FILTER_VALIDATE_INT);
        $new_man_id = filter_input(INPUT_POST, 'new_man_id', FILTER_VALIDATE_INT);
        $certificate = mb_convert_encoding($certificate, "cp1251", "UTF-8");
        $new_man = ManAdditionalExam::getByManID($new_man_id);
        if (!$new_man) {
            $new_man = new ManAdditionalExam();
            $new_man->man_id = $new_man_id;
        }
        if (!empty($old_man_id)) {
            $new_man->previous_man_id = $old_man_id;
            $result = ActMan::getByID($old_man_id);
            if (!empty($result)) {
                $man = CertificateDuplicate::checkForDuplicates($result);
                if ($man->getBlank_number()) {
                    $new_man->old_blank_number = $man->getBlank_number();
                    $new_man->cert_exists = 1;
                } else {
                    $new_man->old_blank_number = $certificate;
                }
            }
        } else {
            $new_man->old_blank_number = $certificate;
        }
        $new_man->save();
        die(
        json_encode(
            array(
                'new_man' => $new_man,
            )
        )
        );
    }

    protected function clean_man_action()
    {
        //die('123');
//        $certificate = filter_input(INPUT_POST, 'num', FILTER_SANITIZE_STRING);
//        $old_man_id = filter_input(INPUT_POST, 'old_man_id', FILTER_VALIDATE_INT);
        $new_man_id = filter_input(INPUT_POST, 'new_man_id', FILTER_VALIDATE_INT);
//        $new_man_id=3333333333;
        $old_man_additional_exam = ManAdditionalExam::getByManID($new_man_id);
        $old_man_main_exam = ActMan::getByID($new_man_id);
        if (empty($old_man_additional_exam) || empty($old_man_main_exam)) {
            die(
            json_encode(
                array(
                    'error' => 'not found',
                )
            )
            );
        }
        $new_man = new ManAdditionalExam();
        $old_man = $old_man_additional_exam;
        /*$new_man->man_id = $new_man_id;
        $new_man->id = $old_man->id;
        $new_man->save();*/
        $old_man->delete();
        $old_man_main_exam = ActMan::getByID($new_man_id);
        /* if (empty($old_man))
         {
             die(json_encode(
                 array(
                     'error' => 'not found',
                 )
             ));
         }*/
        $old_man = $old_man_main_exam;
        $new_man = new ActMan();
        $new_man->id = $old_man->id;
        $new_man->act_id = $old_man->act_id;
        $new_man->test_id = $old_man->test_id;
        $new_man->save();
        die(
        json_encode(
            array(
                'error' => 'ok',
            )
        )
        );
    }

    protected function acts_print_list_action()
    {
        $acts = Acts::getByLevel($_SESSION['univer_id'], Act::STATE_RECEIVED);
        $acts_list_for_print = array();
        foreach ($acts as $act) {
            if (!$act->isAllPrinted()) {
                continue;
            }
            $acts_list_for_print[] = $act;
        }

        return $this->render->view(
            'acts/third/level_list',
            array(
                'list' => $acts_list_for_print,
            )
        );
    }

    protected function act_man_file_download_action()
    {
        $file = \SDT\models\PeopleStorage\ManFile::getByHash(filter_input(INPUT_GET, 'hash'));
        if (!$file) {
            die('Файл не существует');
        }
//        session_write_close();
        $file->download();
    }

    protected function act_man_files_action()
    {
        $id = $this->getNumeric('id', false);
        if (!$id) {
            $this->redirectReturn();
        }
        $act = Act::getByID($id);
        if (!$act) {
            $this->redirectByAction('act_fs_list');
        }
        if (!$act || $act->isDeleted() || $act->state != Act::STATE_INIT) {
            $this->redirectAccessRestricted();
        }
        $_SESSION['flash'] = '';
        $_FILES = $this->multipleFiles($_FILES);
        if (!empty($_FILES['photo'])) {
            foreach ($_FILES['photo'] as $user_id => $_file) {
                if (empty($_file['name'])) {
                    continue;
                }
                $file = new \SDT\models\PeopleStorage\ManFile();
                $file->man_id = $user_id;
                $file->type = $file::TYPE_PHOTO;
                $id = $file->upload($_file, $error);
                $man = ActMan::getByID($user_id);
                $_SESSION['flash'] .= '<strong>'.$man->surname_rus.' '.$man->name_rus.'</strong>: Фотография - '.$error.'<br>';
                if ($id) {
                    \SDT\models\PeopleStorage\ManFile::deleteOther($man->id, $file::TYPE_PHOTO, [$id]);
                }
//                if ($id) {
//
//
//                    if ($man->passport_file) {
//                        $oldFile = File::getByID($man->passport_file);
//                        $oldFile->delete();
//                    }
//                    $man->passport_file = $id;
//                    $man->save();
//                }
            }
        }
        if (!empty($_FILES['aud'])) {
            foreach ($_FILES['aud'] as $user_id => $_file) {
                if (empty($_file['name'])) {
                    continue;
                }
                $file = new \SDT\models\PeopleStorage\ManFile();
                $file->man_id = $user_id;
                $file->type = $file::TYPE_AUDITION;
                $id = $file->upload($_file, $error);
                $man = ActMan::getByID($user_id);
                $_SESSION['flash'] .= '<strong>'.$man->surname_rus.' '.$man->name_rus.'</strong>: Аудирование - '.$error.'<br>';
                if ($id) {
                    \SDT\models\PeopleStorage\ManFile::deleteOther($man->id, $file::TYPE_AUDITION, [$id]);
                }
//
//
//                    if ($man->passport_file) {
//                        $oldFile = File::getByID($man->passport_file);
//                        $oldFile->delete();
//                    }
//                    $man->passport_file = $id;
//                    $man->save();
//                }
            }
        }

        return $this->render->view(
            'acts/init/act_man_files',
            array(
                'people' => $act->getPeople(),
                'Act' => $act,
            )
        );
    }
}
