<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/_func.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/lang.php';
auth();
require_once 'models/include.php';
require_once 'render.php';
require_once 'roles.php';
require_once 'Errors.php';
require_once 'helpers.php';


class Controller
{
    /** @var Render */
    protected $render;


    protected $current_role = array();
    protected $accessLevelList = false;
    protected $accessCenterList = false;

    private static $instance;
    private $roles;

    static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        //   var_dump($this);
        $this->render = Render::getInstance();
        $this->roles = Roles::getInstance();
        $this->universityHasDogovor();
    }

    protected function  universityHasDogovor()
    {

        // die('asdfsf');
        if (!$this->userHasRole(Roles::ROLE_CENTER_EXTERNAL)) {
            return false;
        }
//die('as7df');
        //var_dump($_SESSION['univer_id']);
        $univer = University::getByID($_SESSION['univer_id'], true);
        /*   if (!$univer) {
               $this->redirectAccessRestricted();
           }*/
        // var_dump($univer);
        //die(var_dump($univer));
        if (!count($univer->getDogovors())) {
            $this->roles->emptyRoles();
            $this->redirectUniversityHasNoDogovors();
        }

        return true;

    }

    public function getCurrentRole()
    {
        return $this->roles->getCurrentRole();
    }

    protected function getRoleAccess($action)
    {
        return $this->roles->getRoleAccess($action);
    }


    public function userHasRole($role)
    {

        return $this->roles->userHasRole($role);
    }


    public function getUniversityRestrictionArray()
    {

        return $this->roles->getUniversityRestrictionArray();

    }

    public function redirectByAction($action, $params = array())
    {
        $query = array('action' => $action);
        if (is_array($params)) {
            $query = array_merge($params, $query);
        }
        header('Location: ./index.php?' . http_build_query($query));
        die();
    }


    protected function access_restricted_action()
    {
        return $this->render->view('access_restricted');
    }

    public function redirectReturn($append = '')
    {

        if (empty($_SERVER['HTTP_REFERER'])) {
            $_SERVER['HTTP_REFERER'] = '/sdt/';
        }
        header('Location: ' . $_SERVER['HTTP_REFERER'] . $append);
        die();
    }

    public function executeAction($debug = true)
    {


        ob_start();
        $content = $this->voter();

        $ob = ob_get_clean();
        if ($debug) {
            $content .= $ob;
        }

        return $content;
    }

    public function redirectAccessRestricted()
    {
        $this->redirectByAction('access_restricted');

    }

    protected function voter()
    {

        if (!empty($_GET['action'])) {
            $method_name = $_GET['action'] . '_action';
            if (method_exists($this, $method_name)) {

                if (!$this->getRoleAccess($_GET['action'])) {

                    $this->redirectAccessRestricted();
                }

                return call_user_func(array($this, $method_name));
            }
        }

        return $this->index_action();
    }

    protected function index_action()
    {
        return 'Выберите раздел в меню';
    }

    protected function universities_action()
    {


        $univers = Univesities::getAll();

        return $this->render->view(
            'universities/all',
            array(
                'list' => $univers
            )
        );

    }

    protected function act_test_delete_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }
        $testLevel = ActTest::getByID($_GET['id']);
        $testLevel->delete();
        $_SESSION['flash'] = 'Уровень тестирования удален';
        $this->redirectReturn();
    }

    protected function university_add_action()
    {

        $univer = new University();

        if (count($_POST)) {
            $univer->parseParameters($_POST);
            $univer->head_id = CURRENT_HEAD_CENTER;
            $univer->save();
            $params = array('id' => $univer->id);
            if ($univer->user_password) {
                $params['pwd'] = $univer->user_password;
            }
            $this->redirectByAction('university_view', $params);
        }

        return $this->render->form('add', $univer, 'Добавление  ВУЗа');

    }

    protected function university_edit_action()
    {


        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }
        $id = $_GET['id'];
        $univer = University::getByID($id);

        if (!$univer) {
            $this->redirectAccessRestricted();
        }

        if (!empty($_GET['do'])) {
            switch ($_GET['do']) {
                case 'change_pwd':
                    $univer->resetPassword();
                    $univer->save();
                    $params = array('id' => $univer->id);
                    if ($univer->user_password) {
                        $params['pwd'] = $univer->user_password;
                    }
                    $this->redirectByAction('university_view', $params);
            }
        }


        if (count($_POST)) {
            $univer->parseParameters($_POST);
            $univer->save();
            $params = array('id' => $univer->id);
            if ($univer->user_password) {
                $params['pwd'] = $univer->user_password;
            }
            $this->redirectByAction('university_view', $params);
        }
        $buttons = array(
            array(
                'link' => 'index.php?action=university_edit&id=' . $univer->id . '&do=change_pwd',
                'caption' => 'Сбросить пароль'
            )
        );

        return $this->render->form('add', $univer, 'Редактирование ВУЗа', $buttons);

    }

    protected function university_view_action()
    {

        $id = $_GET['id'];
        $univer = University::getByID($id);
        if (!$univer) {
            $this->redirectByAction('universities');
        }


        return $this->render->view('universities/view', array('object' => $univer));

    }

    protected function university_delete_action()
    {

        $univer = University::getByID($_GET['id']);


        $univer->delete();
        $this->redirectByAction('universities');

    }

    protected function university_view_dogovor_action()
    {

        $id = $_GET['id'];
        $univer = University::getByID($id);
        if (!$univer) {
            $this->redirectByAction('universities');
        }


        return $this->render->view('universities/view_dogovor', array('object' => $univer));

    }

    protected function university_dogovor_add_action()
    {

        $univer = new University_dogovor();
        $univer->date = date('d.m.Y');
        $univer->university_id = $_GET['id'];
        if (count($_POST)) {
            $univer->parseParameters($_POST);
            //die(var_dump($univer));
            $univer->save();
            $this->redirectByAction('university_view', array('id' => $_GET['id']));
        }

        return $this->render->form('add', $univer, 'Добавление договора ВУЗа');
    }

    protected function university_dogovor_edit_action()
    {

        $univer = University_dogovor::getByID($_GET['id']);

        if (count($_POST)) {
            //  echo '111';
            $univer->parseParameters($_POST);

            //   die(print_r($_POST));
            $univer->save();
            $this->redirectByAction('university_view_dogovor', array('id' => $univer->university_id));
        }

        return $this->render->form('add', $univer, 'Редактирование договора ВУЗа');
    }

    protected function   university_dogovor_delete_action()
    {

        $univer = University_dogovor::getByID($_GET['id']);


        $univer->delete();
        $this->redirectByAction('university_view_dogovor', array('id' => $univer->university_id));

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


    protected function act_choose_action()
    {
        $types = TestLevelTypes::getAll();

        return $this->render->view(
            'acts/init/choose_test_type',
            array(
                'Types' => $types,
                'Legend' => 'Выбрать тип проведенного тестированимя',


            )
        );
    }

    protected function act_add_action()
    {
        $type = $this->getNumeric('type', false);
        if (!$type || !$type = TestLevelType::getByID($type)) {
            $this->redirectByAction('act_choose');
        }
        $act = new Act();
        $act->test_level_type_id = $type->id;
        $act->paid = 0;
        // $act->state = 'init';
        $act->user_create_id = $_SESSION['u_id'];
        $act->university_id = $_SESSION['univer_id'];
        if (!empty($_COOKIE['new_act_official'])) {
            $act->official = $_COOKIE['new_act_official'];
        }
        if (!empty($_COOKIE['new_act_responsible'])) {
            $act->responsible = $_COOKIE['new_act_responsible'];
        }

        if (count($_POST)) {
            $act->parseParameters($_POST);
            setcookie('new_act_responsible', $act->responsible);
            setcookie('new_act_official', $act->official);


            $id = $act->save();
            $act = Act::getByID($id);
            $act->setState(Act::STATE_INIT);
            $act->save();
            writeStatistic(
                'sdt',
                'act_new',
                array(
                    'id' => $id,
                    'univer_id' => $act->getUniversity()->id,
                    'univer_name' => $act->getUniversity()->name,
                )
            );
            $_SESSION['flash'] = 'Акт создан';
            $this->redirectByAction('act_fs_view', array('id' => $id));
        }

        return $this->render->view(
            'acts/init/edit',
            array(
                'Act' => $act,
                'Legend' => 'Добавить акт',
                'University' => University::getByID($_SESSION['univer_id'])

            )
        );

    }


    protected function act_fs_edit_action()
    {
        $act = Act::getByID($_GET['id']);

        if (count($_POST)) {
            $act->parseParameters($_POST);
            $id = $act->save();

        }

        return $this->render->view(
            'acts/init/edit',
            array(
                'Act' => $act,
                'Legend' => 'Редактировать акт',
                'University' => University::getByID($_SESSION['univer_id'])
            )
        );

    }

    protected function act_fs_view_action()
    {
        $act = Act::getByID($_GET['id']);

        return $this->render->view(
            'acts/init/view',
            array(
                'object' => $act,

            )
        );
    }

    protected function act_send_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }
        $act = Act::getByID($_GET['id']);
        $act->setState(Act::STATE_SEND);
        $act->save();
        writeStatistic(
            'sdt',
            'act_send_check',
            array(
                'id' => $act->id,
                'univer_id' => $act->getUniversity()->id,
                'univer_name' => $act->getUniversity()->name,
            )
        );
        // die();
        $_SESSION['flash'] = 'Акт о тестовой сессии отправлен на проверку';
        $this->redirectReturn();
    }

    protected function act_fs_list_action()
    {

        $acts = Acts::getByLevel($_SESSION['univer_id'], Act::STATE_INIT);

        return $this->render->view(
            'acts/init/level_list',
            array(
                'list' => $acts,

            )
        );
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

private function isDeletedRedirect(Model $model){
    if($model->isDeleted()){
        $this->redirectAccessRestricted();
    }
}

    protected function act_invalid_action()
    {
        $univer = Act::getByID($_GET['id']);


        $univer->delete();


        $_SESSION['flash'] = "Тестовая сессия отмечена как недействительная";
        //  $this->redirectByAction('act_fs_list');
        $this->redirectReturn();
    }

    protected function sess_invalid_action()
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $reason = filter_input(INPUT_POST, 'reason', FILTER_SANITIZE_STRING);

        $act = Act::getByID($id);
        if (!$act) {
            $_SESSION['flash'] = "Тестовая сессия не найдена";
            die(json_encode(
                array(
                    'result' => 'error',
                    'message' => 'no act',
                )
            ));
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
                $man->invalidateBlank('Текстовая сессия отмечена как недействительная');
            }
        }
        $act->delete();


        $_SESSION['flash'] = "Тестовая сессия отмечена недействительная";
        //  $this->redirectByAction('act_fs_list');


        die(json_encode(
            array(
                'result' => 'ok',
            )
        ));

    }

    protected function act_test_add_action()
    {

        $actTest = new ActTest();
        $actTest->act_id = $_GET['id'];
        $Act_type = $actTest->getAct()->test_level_type_id;

        if (count($_POST)) {

            $actTest->parseParameters($_POST);

            if ($Act_type == 1) {
                $actTest->people_subtest_retry = $actTest->people_retry;
            }
//			echo '<pre>';
            $prices = ChangedPriceTestLevel::getPriceByLevel($actTest->act_id, $_POST['level_id']);
//            die(var_dump($prices));
            $actTest->price = $prices->price;

            $actTest->price_subtest_retry = $prices->sub_test_price;
            $actTest->price_subtest_2_retry = $prices->sub_test_price_2;
            //die(var_dump($act));
            $id = $actTest->save();

            $_SESSION['flash'] = "Добавлено тестирование";
            $this->redirectByAction('act_fs_view', array('id' => $_GET['id']));
        }


        return $this->render->view(
            'acts/forms/act_test',
            array(
                'Act' => $actTest,
                'Legend' => 'Добавить тестирование',
                'Levels' => TestLevels::getAvailable4Act($actTest->getAct()),
                'Type' => $Act_type,
            )
        );

    }

    protected function act_test_edit_action()
    {

        $act = ActTest::getByID($_GET['id']);


        if (count($_POST)) {
            $act->parseParameters($_POST);
            $act->save();
            $this->redirectByAction('act_fs_view', array('id' => $act->act_id));
        }

        return $this->render->view(
            'acts/forms/act_test',
            array(
                'Act' => $act,
                'Legend' => 'Редактировать тестирование',
                'Levels' => TestLevels::getAll(),

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
        die($this->render->view(
            'acts/print_check',
            array(
                'Act' => $act,
            )
        ));
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

        $persons = array($man);

        die($this->render->view(
            'acts/print/pril_cert',
            array(
//                'Man' => $man,
                'persons' => $persons,

            )
        ));
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

//            return $this->render->view($this->mass_print());
            return $this->render->view(
                'acts/print/choose_people',
                array(
                    'persons' => $persons,
                )
            );
        } else {

            foreach ($Act->getPeople() as $man) {
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

    protected function act_vidacha_cert_action()
    {
        $act = Act::getByID($_GET['id']);
        $this->checkActIsEditable($act);

        die($this->render->view(
            'acts/print/vidacha_cert',
            array(
                'Act' => $act,
            )
        ));
    }

    protected function act_vidacha_note_action()
    {
        $act = Act::getByID($_GET['id']);
        $this->checkActIsEditable($act);

        die($this->render->view(
            'acts/print/vidacha_sprav',
            array(
                'Act' => $act,
            )
        ));
    }

    protected function act_vidacha_reestr_action()
    {
        $act = Act::getByID($_GET['id']);
        $this->checkActIsEditable($act);

        die($this->render->view(
            'acts/print/vidacha_cert_reestr',
            array(
                'Act' => $act,

            )
        ));
    }

    protected function act_grazhdan_action()
    {
        $man = ActMan::getByID($_GET['id']);
        $man->blank_number = $man->id;
        //  $man->document_nomer = $man->id;
        $man->save();
        $man->getAct()->save();

        die($this->render->view(
            'acts/print/grazhdan',
            array(
                'Man' => $man,

            )
        ));
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


    protected function act_rki_action()
    {
        $man = ActMan::getByID($_GET['id']);
//        $man->blank_number = $man->id;

//        $man->save();
//        $man->getAct()->save();

        $persons = array($man);

        die($this->render->view(
            'acts/print/rki',
            array(
//                'Man' => $man,
                'persons' => $persons,
            )
        ));
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

//            return $this->render->view($this->mass_print());
            return $this->render->view(
                'acts/print/choose_people',
                array(
                    'persons' => $persons,
                )
            );
        } else {

            foreach ($Act->getPeople() as $man) {
                if ($man->document != 'note' || !in_array($man->id, $_POST['pers'])) {
                    continue;
                }
                $persons[] = $man;
            }


            die($this->render->view(
                'acts/print/rki',
                array(
                    'persons' => $persons,

                )
            ));
        }


    }

    protected function encode($text)
    {
        return iconv('CP1251', 'UTF-8', $text);

    }

    protected function search_pupil_action()
    {
        $result = array();
        $query = $certificate = '';
        if (count($_POST)) {
            $query = $_POST['query'];
            $certificate = $_POST['certificate'];
//            $blank = $_POST['blank'];
            $result = ActPeople::Search($query, $certificate);
        }

        return $this->render->view(
            'search/pupil',
            array(
                'Result' => $result,
                'query' => $query,
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
                'Result' => $Result
            )
        );
    }

    protected function act_upload_scan_action()
    {
        $act = Act::getByID(@$_POST['id']);
        if (!$act) {
            $this->redirectByAction('act_fs_list');
        }
        if (!empty($_FILES['file']['name'])) {
            $file = new File();
            $id = $file->upload($_FILES['file'], $error);

            $_SESSION['flash'] = $error;
            if ($id) {
                if ($act->file_act_id) {
                    $oldFile = File::getByID($act->file_act_id);
                    $oldFile->delete();
                }
                $act->file_act_id = $id;
                $act->save();
            }
        } else {
            $_SESSION['flash'] = 'Файл не выбран';
        }

        $this->redirectReturn();
    }

    protected function act_upload_tabl_scan_action()
    {
        $act = Act::getByID(@$_POST['id']);
        if (!$act) {
            $this->redirectByAction('act_fs_list');
        }
        if (!empty($_FILES['file']['name'])) {
            $file = new File();
            $id = $file->upload($_FILES['file'], $error);

            $_SESSION['flash'] = $error;
            if ($id) {
                if ($act->file_act_tabl_id) {
                    $oldFile = File::getByID($act->file_act_tabl_id);
                    $oldFile->delete();
                }
                $act->file_act_tabl_id = $id;
                $act->save();
            }
        } else {
            $_SESSION['flash'] = 'Файл не выбран';
        }

        $this->redirectReturn();
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

    protected function download_action()
    {
        $file = File::getByHash(@$_GET['hash']);
        if (!$file) {
            die('Файл не существует');
        }
		session_write_close();
        $file->download();
    }

    protected function university_print_simple_action()
    {
        $universtity = University::getByID($_GET['id']);
        if (!$universtity) {
            $this->redirectByAction('universities_action');

        }

        die($this->render->view(
            'universities/print/simple',
            array(
                'university' => $universtity,


            )
        ));
    }

    protected function university_print_full_action()
    {
        $universtity = University::getByID($_GET['id']);
        if (!$universtity) {
            $this->redirectByAction('universities_action');

        }

        die($this->render->view(
            'universities/print/full',
            array(
                'university' => $universtity,


            )
        ));
    }

    protected function university_print_full_dogovor_action()
    {
        $universtity = University::getByID($_GET['id']);
        if (!$universtity) {
            $this->redirectByAction('universities_action');

        }

        die($this->render->view(
            'universities/print/full_dogovor',
            array(
                'university' => $universtity,


            )
        ));
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

    protected function  act_table_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }
        $act_id = $_GET['id'];
        $act = Act::getByID($act_id);
        // $people = $act->getPeople();
        $countries = Countries::getAll();
        if (count($_POST)) {

            //     var_dump($_POST);

            if (!empty($_POST['ajax'])) {
                $_POST = $this->recursive_utf_decode($_POST);
            }

            if (isset($_POST['user']) && is_array($_POST['user'])) {
                foreach ($_POST['user'] as $id => $params) {
                    $man = ActMan::getByID($id);
                    $man->parseParameters($params);
                    $man->save();
                }
            }

            $act->tester1 = $_POST['tester1'];
            $act->tester2 = $_POST['tester2'];
            $act->save();

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

        return $this->render->view(
            'acts/init/table',
            array(
                //'people' => $people,
                'Act' => $act,
                'Countries' => $countries,
            )
        );
    }

    protected function  act_passport_action()
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
                $_SESSION['flash'] .= '<strong>' . $man->surname_rus . ' ' . $man->name_rus . '</strong>: ' . $error . '<br>';
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


    protected function act_checked_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }
        $act = Act::getByID($_GET['id']);
        $this->checkActIsEditable($act);
        $act->setStateChecked();
        $act->is_changed_checker = 0;
        $act->save();

        $_SESSION['flash'] = 'Акт отмечен как проверенный';

        writeStatistic(
            'sdt',
            'act_set_checked',
            array(
                'id' => $act->id,
                'univer_id' => $act->getUniversity()->id,
                'univer_name' => $act->getUniversity()->name,


            )
        );
        $this->redirectReturn();
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

        $this->redirectReturn('#' . $act->id);
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

        $this->redirectReturn('#' . $act->id);
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
                'signings' => $signings
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
                'signings' => $signings
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
                'signings' => $signings
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
                'signings' => $signings
            )
        );
    }

    protected function act_received_view_action()
    {
        $act = Act::getByID($_GET['id']);

        $this->checkActIsEditable($act);
        $act->incrementViewedAndSave();

        return $this->render->view(
            'acts/received/view',
            array(
                'object' => $act,

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

    protected function act_received_table_view_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }
        $act = Act::getByID($_GET['id']);
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
        if(!$act) {
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

        return $this->render->view(
            'acts/received/form_numbers',
            array(
                'Act' => $act,

            )
        );


    }

    protected function print_invoice_action()
    {

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
        }
        die($this->render->view(
            'acts/print_check',
            array(
                'Act' => $act,

            )
        ));


    }


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

        $act->setStateArchive();
        $_SESSION['flash'] = 'Документ перенесен в архив';
        $act->save();
        $this->redirectReturn();
    }

    protected function act_universities_archive_action()
    {
        $acts = Univesities::getByLevel(ACT::STATE_ARCHIVE);

        return $this->render->view(
            'acts/archive/universities_list',
            array(
                'list' => $acts,

            )
        );
    }

    protected function act_archive_list_action()
    {
        if (empty($_GET['uid']) || !is_numeric($_GET['uid'])) {
            $_SESSION['flash'] = 'Вуз не указан';
            $this->redirectReturn();
        }
        $acts = Acts::getByLevel($_GET['uid'], ACT::STATE_ARCHIVE);
        $signingsInvoice = ActSignings::get4Invoice();

        return $this->render->view(
            'acts/archive/list',
            array(
                'list' => $acts,
                'signingsInvoice' => $signingsInvoice,
            )
        );
    }

    protected function act_archive_view_action()
    {
        $act = Act::getByID($_GET['id']);

        return $this->render->view(
            'acts/archive/view',
            array(
                'object' => $act,

            )
        );
    }

    protected function act_archive_table_view_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }
        $act = Act::getByID($_GET['id']);

        return $this->render->view(
            'acts/archive/table_view',
            array(
                'Act' => $act,
            )
        );
    }


    protected function act_archive_numbers_action()
    {
        $act = Act::getByID($_GET['id']);


        return $this->render->view(
            'acts/archive/form_numbers',
            array(
                'Act' => $act,

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

    protected function print_certificate_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }

        $persons = array();


//        $persons[] = $_GET['id'];
        $persons[] = ActMan::getByID($_GET['id']);

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

//            return $this->render->view($this->mass_print());
            return $this->render->view(
                'acts/print/choose_people',
                array(
                    'persons' => $persons,
                )
            );
        } else {

            foreach ($Act->getPeople() as $man) {
                if ($man->document != 'certificate' || !in_array($man->id, $_POST['pers'])) {
                    continue;
                }
                $persons[] = $man;
            }


            die($this->render->view(
                'acts/print/certificate',
                array(
                    'persons' => $persons,

                )
            ));
        }

    }

    protected function buh_check_univer_action()
    {

        $univers = Univesities::get4Buh();

        return $this->render->view(
            'buh/universities_list',
            array(
                'list' => $univers,

            )
        );


    }

    protected function buh_list_action()
    {
        if (empty($_GET['uid']) || !is_numeric($_GET['uid'])) {
            $_SESSION['flash'] = 'Вуз не указан';
            $this->redirectReturn();
        }
        $acts = Acts::get4Buh($_GET['uid']);

        return $this->render->view(
            'buh/list',
            array(
                'list' => $acts,
            )
        );
    }

    protected function act_universities_second_action()
    {
        $acts = Univesities::getByLevel(Act::STATE_SEND);

        return $this->render->view(
            'acts/act_check/universities_list',
            array(
                'list' => $acts,

            )
        );
    }

    protected function act_second_list_action()
    {
        if (empty($_GET['uid']) || !is_numeric($_GET['uid'])) {
            $_SESSION['flash'] = 'Вуз не указан';
            $this->redirectReturn();
        }
        $acts = Acts::getByLevel($_GET['uid'], Act::STATE_SEND);

        return $this->render->view(
            'acts/act_check/level_list',
            array(
                'list' => $acts,

            )
        );
    }

    protected function act_second_view_action()
    {
        $act = Act::getByID($_GET['id']);
        $this->checkActIsEditable($act);
        $act->incrementViewedAndSave();

        return $this->render->view(
            'acts/act_check/level_view',
            array(
                'object' => $act,

            )
        );
    }

    protected function  act_table_second_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }
        $act_id = $_GET['id'];
        $act = Act::getByID($act_id);
        $this->checkActIsEditable($act);
        $act->incrementViewedAndSave();
        $countries = Countries::getAll();
        if (count($_POST)) {
            foreach ($_POST['user'] as $id => $params) {
                $man = ActMan::getByID($id);
                $man->parseParameters($params);
                $man->save();
            }
            $act->is_changed_checker = 1;
            $act->tester1 = $_POST['tester1'];
            $act->tester2 = $_POST['tester2'];

            $act->save();
            $_SESSION['flash'] = 'Таблица сохранена';
        }

        return $this->render->view(
            'acts/act_check/table_second',
            array(
                //'people' => $people,
                'Act' => $act,
                'Countries' => $countries,
            )
        );
    }

    protected function act_second_edit_action()
    {
        $act = Act::getByID($_GET['id']);
        $this->checkActIsEditable($act);
        if (count($_POST)) {
            $act->parseParameters($_POST);
            $act->is_changed_checker = 1;
            $id = $act->save();
            $_SESSION['flash'] = 'Акт сохранен';
        }

        return $this->render->view(
            'acts/act_check/second_edit',
            array(
                'Act' => $act,
                'Legend' => 'Редактировать акт.',
                'University' => University::getByID($act->university_id)
            )
        );

    }

    protected function act_return_work_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }
        $act = Act::getByID($_GET['id']);
        $this->checkActIsEditable($act);
        $act->setState(Act::STATE_INIT);
        $act->is_changed_checker = 1;
        $act->save();
        writeStatistic(
            'sdt',
            'act_returned',
            array(
                'id' => $act->id,
                'univer_id' => $act->getUniversity()->id,
                'univer_name' => $act->getUniversity()->name,
            )
        );
        $_SESSION['flash'] = 'Акт возвращен на доработку';
        $this->redirectReturn();
    }

    protected function act_third_list_action()
    {

        $acts = Acts::getByLevel($_SESSION['univer_id'], Act::STATE_CHECKED);

        return $this->render->view(
            'acts/third/level_list',
            array(
                'list' => $acts,

            )
        );
    }

    protected function checked_list_action()
    {

        $acts = Acts::getListByLevel4Head(Act::STATE_CHECKED);

        return $this->render->view(
            'acts/list_head_inactive',
            array(
                'list' => $acts,
                'legend' => 'На оформлении'
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
                'legend' => 'На доработке'
            )
        );
    }

    protected function act_third_view_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }
        $act = Act::getByID($_GET['id']);
        if ($act->state != Act::STATE_CHECKED) {
            $this->redirectAccessRestricted();
        }

        return $this->render->view(
            'acts/third/level_view',
            array(
                'object' => $act,

            )
        );
    }

    protected function act_table_view_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }
        $act = Act::getByID($_GET['id']);

        return $this->render->view(
            'acts/third/table_view',
            array(
                //'people' => $people,
                'Act' => $act,

            )
        );
    }

    protected function act_finished_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }
        $act = Act::getByID($_GET['id']);
        $act->setState(Act::STATE_RECEIVED);
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
        if (empty($_GET['id']) || !is_numeric($_GET['id']) || empty($_GET['s_id']) || !is_numeric($_GET['s_id'])) {
            $this->redirectReturn();
        }
        $act = Act::getByID($_GET['id']);
        $signer = ActSigning::getByID($_GET['s_id']);

        $is_head = University::isHead($_GET['id']);
       /* if ($is_head == 1) {
            $print = 'act_head';
        } else {*/
            $print = 'act';
      /*  }*/

        if (!$signer->id) {
            $this->redirectReturn();
        }
        die($this->render->view(
            'acts/third/' . $print,
            array(
                'act' => $act,
                'signer' => $signer,

            )
        ));

    }

    protected function act_print_migrant_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id']) || empty($_GET['s_id']) || !is_numeric($_GET['s_id'])) {
            $this->redirectReturn();
        }
        $act = Act::getByID($_GET['id']);
        $signer = ActSigning::getByID($_GET['s_id']);

        $is_head = University::isHead($_GET['id']);
        if ($is_head == 1) {
            $print = 'act_migrant_head';
        } else {
            $print = 'act_migrant';
        }

        if (!$signer->id) {
            $this->redirectReturn();
        }
        die($this->render->view(
            'acts/third/' . $print,
            array(
                'act' => $act,
                'signer' => $signer,

            )
        ));
    }

    protected function act_table_print_action()
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
    }

    protected function university_user_right_action()
    {
        $univer = University::getByID($_GET['id']);
        $Jobs = UserTypes::getForUniverRights();

        if (isset($_POST['users'])) {
            $univer->saveUsers($_POST['users']);
            $_SESSION['flash'] = 'Права пользователей установлены';
        }

//die(var_dump($univer->getAvailableUsers()));
        return $this->render->view(
            'universities/right',
            array(
                'object' => $univer,
                'users' => $univer->getAvailableUsers(),
                'jobs' => $Jobs
            )
        );
    }

    protected function user_list_action()
    {
        $users = Univesities::getUsers();

        return $this->render->view(
            'universities/user_list',
            array(

                'users' => $users
            )
        );
    }

    protected function user_list_edit_action()
    {
        $user_id = $_GET['id'];

        $user = User::getByID($user_id);

        if (isset($_POST['submit'])) {
            if (empty($_POST['univers'])) {
                $_POST['univers'] = array();
            }
            Univesities::saveUserRight($user_id, $_POST['univers']);
            $_SESSION['flash'] = 'Права пользователей установлены';
        }
        $univerfs = Univesities::getByUser($user_id);


        return $this->render->view(
            'universities/user_list_form',
            array(
                'userName' => $user->getFullName(),
                'univers' => $univerfs
            )
        );
    }

    public function act_return_to_work_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }
        $act = Act::getByID($_GET['id']);
        $act->setState(Act::STATE_INIT);
        $act->save();
        $_SESSION['flash'] = 'Акт перенесён в раздел "В работе"';
        $this->redirectReturn();
    }


    public function utf_encode($string)
    {
        return utf_encode($string);

    }

    public function utf_decode($string)
    {
        return utf_decode($string);
    }

    public function recursive_utf_decode($array)
    {
        return recursive_utf_decode($array);
    }

    protected function multipleFiles(array $_files, $top = true)
    {
        $files = array();
        foreach ($_files as $name => $file) {
            if ($top) {
                $sub_name = $file['name'];
            } else {
                $sub_name = $name;
            }

            if (is_array($sub_name)) {
                foreach (array_keys($sub_name) as $key) {
                    $files[$name][$key] = array(
                        'name' => $file['name'][$key],
                        'type' => $file['type'][$key],
                        'tmp_name' => $file['tmp_name'][$key],
                        'error' => $file['error'][$key],
                        'size' => $file['size'][$key],
                    );
                    $files[$name] = $this->multipleFiles($files[$name], false);
                }
            } else {
                $files[$name] = $file;
            }
        }

        return $files;
    }

    public function date($date)
    {
        return date('d.m.Y', strtotime($date));
    }

    public function mysql_date($date)
    {
        return date('Y-m-d', strtotime($date));
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
            $_SESSION['flash'] = 'Уровень тестирования удален';
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

    private function redirectUniversityHasNoDogovors()
    {
        if (empty($_GET['error'])) {
            // var_dump($_GET['error']);
            //die();
            header('Location: /index.php?error=nodogovor');
            die();
        }
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
            $user->parseParameters($_POST);
            $user->save();
            $_SESSION['flash'] = 'Данные пользователя сохранены';
        }

        return $this->render->view(
            'form',
            array(
                'item' => $user,
                'fields' => User::getEditForm(),
                'legend' => 'Редактирование пользователя'
            )
        );

    }

    public function user_create_action()
    {

        $user = new User();
        $user->head_id = CURRENT_HEAD_CENTER;

        if (count($_POST)) {
            $user->parseParameters($_POST);
            $user->save();
            $_SESSION['flash'] = 'Пользователь создан';
            $this->redirectByAction('user_list');
        }

        return $this->render->view(
            'form',
            array(
                'item' => $user,
                'fields' => User::getEditForm(),
                'legend' => 'Создание пользователя'
            )
        );

    }

    protected function getNumeric($key, $redirect = true)
    {
        if (!isset($_GET[$key]) || !is_numeric($_GET[$key])) {
            if ($redirect) {
                $this->redirectAccessRestricted();
            }

            return null;
        }

        return $_GET[$key];
    }


    protected function signing_list_action()
    {
        $users = ActSignings::getAll();

        return $this->render->view(
            'signing/list',
            array(

                'items' => $users
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
                'legend' => 'Редактирование подписывающего'
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
                'legend' => 'Создание подписывающего'
            )
        );
    }


    public function postNumeric($key)
    {
        if (!isset($_POST[$key]) || !is_numeric($_POST[$key])) {
            return null;
        }

        return $_POST[$key];
    }

    protected function user_type_list_action()
    {

        $types = UserTypes::getAll();

        return $this->render->view(
            'root/user_types_list',
            array(
                'list' => $types
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


    /*public function groups_edit_action()
    {

        $id = CURRENT_HEAD_CENTER;
        $univer = HeadCenter::getByID($id);
        /*if (!$univer) {
            $this->redirectByAction('head_center');
        }*/

    /* return $this->render->view('root/groups_edit', array('object' => $univer));

 }*/

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


    /* public function groups_rename_action()
     {

         $id = CURRENT_HEAD_CENTER;
         $univer = HeadCenter::getByID($id);
         /*if (!$univer) {
             $this->redirectByAction('head_center');
         }*/

    /*  return $this->render->view('root/groups_list', array('object' => $univer));

  }*/
    /* public function groups_delete_action()
     {

         $id = CURRENT_HEAD_CENTER;
         $univer = HeadCenter::getByID($id);
         /*if (!$univer) {
             $this->redirectByAction('head_center');
         }*/

    /*    return $this->render->view('root/groups_list', array('object' => $univer));

    }*/


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


    public function user_add_form_action()
    {

        header("Content-type: text/html; charset=windows-1251");
        $user = new User();

        if (!count($_POST)) {

            die($this->render->view(
                'add_user_form',
                array(
                    'item' => $user,
                    'fields' => User::getEditForm(),
                    //'legend'=>'Создание пользователя'
                )
            ));
        } else {


            $d = $_POST;
            $d = $this->recursive_utf_decode($d);

            if ($d['login'] == '') {
                die('empty_login');
            }

            $sql = 'select * from tb_users where login="' . $d['login'] . '"';
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
            die($this->render->view(
                'edit_user_form',
                array(
                    'item' => $user,
                    'fields' => User::getEditForm(),

                )
            ));
        } else {


            $d = $_POST;
            $d = $this->recursive_utf_decode($d);

            if ($d['login'] == '') {
                die('empty_login');
            }

            $sql = 'select * from tb_users where login="' . $d['login'] . '" and u_id != ' . $u_id;
            if (mysql_num_rows(mysql_query($sql)) > 0) {
                die ('in_use');
            }


            if (count($d)) {
                $user->parseParameters($d);
                $user->save();

                die(json_encode(
                    array(
                        'user' => array(
                            'name' => $this->encode($user->shortName()),
                            'id' => $user->u_id,

                        ),
                        'status' => 'ok'
                    )
                ));

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
                'new_list' => $new_list

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
                'list' => $types

            )
        );
    }


    public function change_price_univers_action()
    {

        //$CURRENT_HEAD_CENTER=2; //!!!!
        //die(CURRENT_HEAD_CENTER);
        $list = array();
        $sql = 'select id,name from sdt_university where head_id=' . CURRENT_HEAD_CENTER . ' and deleted=0 and is_price_change=1';
        //die ($sql);
        $result = mysql_query($sql);
        while ($res = mysql_fetch_array($result)) {
            $list[] = $res;
        }

        return $this->render->view(
            'head_center/changing_price_univers_list',
            array(

                'list' => $list

            )
        );

    }

    public function test_level_price_edit_action()
    {
        header("Content-type: text/html; charset=windows-1251");

        $list = ChangedPriceTestLevel::getByID($_GET['univer_id']);

        if (!$_POST) {
            die($this->render->view(
                'head_center/changing_price_form',
                array(
                    'list' => $list,

                )
            ));
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
                    'sub_test_price_2' => $sub_test_price_2
                );
            }

        }
        if (!empty($to_save)) {
            ChangedPriceTestLevel::save($to_save);
        }
        die('ok');


    }


    protected function certificate_add_action()
    {
        $types = TestLevelTypes::getAll();
        $type = filter_input(INPUT_POST, 'type', FILTER_VALIDATE_INT);
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


        $cert = CertificateReserved::getByID($id);
        if ($cert) {
            $cert->delete();
            die(json_encode(
                array('ok' => true)
            ));
        } else {
            die(json_encode(
                array(
                    'ok' => false,
                    'error' => $this->utf_encode('Не найдено')
                )
            )
            );
        }

        die(json_encode(
            array(
                'ok' => false
            )
        ));

    }


    protected function certificate_approve_action()
    {
        $type_id = filter_input(INPUT_POST, 'type', FILTER_VALIDATE_INT);

        $series = filter_input(INPUT_POST, 'series');
        $start = filter_input(INPUT_POST, 'start');
        $end = filter_input(INPUT_POST, 'end');
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
//                'list' => $list

            )
        );
    }


    protected function certificate_submit_action()
    {
        $toAdd = filter_input(INPUT_POST, 'item', FILTER_DEFAULT, array('flags' => FILTER_REQUIRE_ARRAY));
        $type_id = filter_input(INPUT_POST, 'type', FILTER_VALIDATE_INT);


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


    private function  renderText($text)
    {
        return $this->render->view(
            'text',
            array(//                'list' => $list
                'text' => $text,
            )
        );

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
            if ($Man->needBlank()) {
                $Man->assignBlank();
            }
        }
//        DocumentNumber::unlock();
        $act->save();
        $_SESSION['flash'] = 'Номера бланков заполнены';
        $this->redirectReturn();
    }


    protected function man_issue_duplicate_action()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $man = ActMan::getByID($id);
        if (!$man) {
            $this->redirectAccessRestricted();
        }
        $res = $man->issueDuplicate();
//        (var_dump($res));
        if ($res === -1) {
//            die('dfsf');
            $_SESSION['flash'] = 'Нет свободных номеров бланков. Необходимо ввести новые в систему';
        } else {
            $_SESSION['flash'] = 'Присвоен новый номер бланка';
        }


        $this->redirectReturn();
    }

    protected function man_blank_invalid_action()
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $reason = $this->utf_decode(filter_input(INPUT_POST, 'reason', FILTER_SANITIZE_STRING));
        $man = ActMan::getByID($id);
        if (!$man) {
            $this->redirectAccessRestricted();
        }
        $res = $man->invalidateBlank($reason);
//        (var_dump($res));

        $_SESSION['flash'] = 'Бланк помечен как недействительный';


        die(json_encode(
            array(
                'result' => 'ok',
            )
        ));
    }

}
