<?php

//die('dfs');
require_once $_SERVER['DOCUMENT_ROOT'].'/include/_func.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/include/lang.php';
auth();
require ROOT_DIR.'\vendor\autoload.php';
require_once 'models/include.php';
require_once dirname(__FILE__).'/logic/loader.php';
require_once 'render.php';
require_once 'roles.php';
require_once 'statistic/include.php';
require_once 'helpers/ExamHelper.php';
require_once 'helpers/RkiHelper.php';
require_once __DIR__.'/actions/UniversityTrait.php';
require_once __DIR__.'/actions/ReportsTrait.php';
require_once __DIR__.'/actions/FrdoTrait.php';


class Controller
{

    use ConvertTrait;
    use UniversityTrait;
    use ReportsTrait;
    use FrdoTrait;

    private static $instance;
    /** @var Render */
    protected $render;
    protected $current_role = array();
    protected $accessLevelList = false;
    protected $accessCenterList = false;
    private $roles;

    private function __construct()
    {
        set_time_limit(0);
        //   var_dump($this);
        $this->render = Render::getInstance();
        $this->roles = Roles::getInstance();
        $this->universityHasDogovor();
    }

    protected function universityHasDogovor()
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
           }*/ // var_dump($univer);
        //die(var_dump($univer));
        if (!count($univer->getDogovors())) {
            $this->roles->emptyRoles();
            $this->redirectUniversityHasNoDogovors();
        }

        return true;
    }

    public function userHasRole($role)
    {
        return $this->roles->userHasRole($role);
    }

    private function redirectUniversityHasNoDogovors()
    {
        if (empty($_GET['error'])) {
            // var_dump($_GET['error']);
            //die();
            header('Location: /index.php?error=nodogovor');
            die();
        }
    }

    static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getCurrentRole()
    {
        return $this->roles->getCurrentRole();
    }

    public function getUniversityRestrictionArray()
    {
        return $this->roles->getUniversityRestrictionArray();
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

    protected function voter()
    {
        if (!empty($_GET['action'])) {
            $method_name = $_GET['action'].'_action';
            if (method_exists($this, $method_name)) {
                if (!$this->getRoleAccess($_GET['action'])) {
                    $this->redirectAccessRestricted();
                }

                return call_user_func(array($this, $method_name));
            }
        }

        return $this->index_action();
    }

    protected function getRoleAccess($action)
    {
        return $this->roles->getRoleAccess($action);
    }

    public function redirectAccessRestricted()
    {
        $this->redirectByAction('access_restricted');
    }

    public function redirectByAction($action, $params = array(), $hash = '')
    {
        $query = array('action' => $action);
        if (is_array($params)) {
            $query = array_merge($params, $query);
        }
        header('Location: ./index.php?'.http_build_query($query).$hash);
        die();
    }

    protected function index_action()
    {
        return 'Выберите раздел в меню';
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

    public function redirectReturn()
    {
        if (empty($_SERVER['HTTP_REFERER'])) {
            $_SERVER['HTTP_REFERER'] = '/sdt/';
        }
        header('Location: '.$_SERVER['HTTP_REFERER']);
        die();
    }

    public function user_rights_edit_action()
    {
        $id = $_GET['id'];
        $user = User::getByID($id);
        if (isset($_POST['submit'])) {
            if (empty($_POST['g'])) {
                $_POST['g'] = array();
            }
            $user->saveRoles($_POST['g']);
            $_SESSION['flash'] = 'Группы пользователя установлены';
        }
        $selectedRoles = $user->getRoles();
        $availableRoles = User::getAvailableRoles();

        return $this->render->view(
            'head_center/user_right_form',
            array(
                'userName' => $user->getFullName(),
                'selected' => $selectedRoles,
                'available' => $availableRoles,
            )
        );
    }

    public function user_delete_action()
    {
        $id = $this->getNumeric('id');
        $user = User::getByID($id);
        $user->delete();
        $_SESSION['flash'] = 'Пользователь удалён';
        $this->redirectByAction('user_list', array('id' => $_GET['id']));
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
                $dt = new DateTime('- 84 days');
                $format = $dt->format('Y-m-d H:i:s');
                $sql = 'update '.$user->_table.' set password_changed_at = \''.$format.'\'
            where u_id = '.$user->u_id;
                mysql_query($sql);
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
        //if (!$_GET['id'] || !is_numeric($_GET['id']))$this->redirectByAction('head_center');
        //$user->head_id = $_GET['id'];
        if (count($_POST)) {
            $passChanges = $user->password_change;
            $user->parseParameters($_POST);
            $user->save();
            $_SESSION['flash'] = 'Пользователь создан';
            if ($passChanges) {
                $dt = new DateTime('- 84 days');
                $format = $dt->format('Y-m-d H:i:s');
                $sql = 'update '.$user->_table.' set password_changed_at = \''.$format.'\'
            where u_id = '.$user->u_id;
                mysql_query($sql);
                $_SESSION['flash'] .= '<br>Новый пароль действует 6 дней до его смены пользователем!';
            }
            $this->redirectByAction('user_list', array('id' => $_GET['h_id']));
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
        $head_id = check_id($_GET['h_id']);
        $id = $this->getNumeric('id');
        $user = ActSigning::getByID($id);
        $user->delete();
        $_SESSION['flash'] = 'Подписывающий удалён';
        $this->redirectByAction('signing_list', array('h_id' => $head_id));
    }

    public function signing_edit_action()
    {
        $head_id = check_id($_GET['h_id']);
        $id = $this->getNumeric('id');
        $signing = ActSigning::getByID($id);
        if (count($_POST)) {
            $signing->setCheckboxes($_POST);
            $signing->parseParameters($_POST);
            $signing->save();
            $_SESSION['flash'] = 'Подписывающий сохранён';
            $this->redirectByAction('signing_list', array('h_id' => $head_id));
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
        $head_id = check_id($_GET['h_id']);
        $signing = new ActSigning();
        $signing->head_id = $head_id;
        if (count($_POST)) {
            $signing->setCheckboxes($_POST);
            $signing->parseParameters($_POST);
            $signing->save();
            $_SESSION['flash'] = 'Подписывающий сохранён';
            $this->redirectByAction('signing_list', array('h_id' => $head_id));
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

    public function postNumeric($key)
    {
        if (!isset($_POST[$key]) || !is_numeric($_POST[$key])) {
            return null;
        }

        return $_POST[$key];
    }

    /**/
    public function user_type_rights_edit_action()
    {
        $id = $_GET['id'];
        $user = UserType::getByID($id);
        if (isset($_POST['submit'])) {
            if (empty($_POST['g'])) {
                $_POST['g'] = array();
            }
            $user->saveRoles($_POST['g']);
            $_SESSION['flash'] = 'Группы для типа пользователя установлены';
        }
        // var_dump($user);die;
        if (!$user) {
            $this->redirectByAction('user_type_list');
        }
        $selectedRoles = $user->getRoles();
        $availableRoles = User::getAvailableRoles();

        return $this->render->view(
            'root/user_type_right_form',
            array(
                'userName' => $user->getFullName(),
                'selected' => $selectedRoles,
                'available' => $availableRoles,
            )
        );
    }

    public function user_add_form_action()
    {
        header("Content-type: text/html; charset=windows-1251");
        $user = new User();
        if (!$_POST) {
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
                $user->save();
                die('ok');
            }
        }
    }

    public function recursive_utf_decode($array)
    {
        foreach ($array as &$item) {
            if (is_array($item)) {
                $item = $this->recursive_utf_decode($item);
            } else {
                $item = $this->utf_decode($item);
            }
        }

        return $array;
    }

    public function utf_decode($string)
    {
        return mb_convert_encoding($string, 'cp1251', 'utf8');
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
                $passChanges = $user->password_change;
                $user->save();
                if ($passChanges) {
                    $dt = new DateTime('- 84 days');
                    $format = $dt->format('Y-m-d H:i:s');
                    $sql = 'update '.$user->_table.' set password_changed_at = \''.$format.'\'
            where u_id = '.$user->u_id;
                    mysql_query($sql);
//                    $_SESSION['flash'] .= '<br>Новый пароль действует 6 дней до его смены пользователем!';
                }
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

    protected function encode($text)
    {
        return iconv('CP1251', 'UTF-8', $text);
    }

    public function user_add_form_fms_action()
    {
        header("Content-type: text/html; charset=windows-1251");
        $user = new User();
        if (!$_POST) {
            die(
            $this->render->view(
                'add_user_form_fms',
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
                $user->save();
//                die(var_dump($d['fms_region']));
                $fms_region_user = new FmsRegionUser();
                $fms_region_user->id_user = $user->u_id;
                $fms_region_user->id_region = $d['fms_region'];
//                die(var_dump($fms_region_user));
                $fms_region_user->new = 1;
                $fms_region_user->save();
//                die(var_dump($fms_region_user));
                die('ok');
            }
        }
    }

    /**/
    public function user_edit_form_fms_action()
    {
        header("Content-type: text/html; charset=windows-1251");
//        $user = new User();
        $u_id = $this->getNumeric('u_id');
        $user = User::getByID($u_id);
        if (!$_POST) {
            die(
            $this->render->view(
                'edit_user_form_fms',
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
                $fms_region_user = new FmsRegionUser();
                $fms_region_user->id_user = $user->u_id;
                $fms_region_user->id_region = $d['fms_region'];
//                die(var_dump($fms_region_user));
//                $fms_region_user->new=1;
                $fms_region_user->save();
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

    public function user_delete_form_fms_action()
    {
        $id = $this->getNumeric('id');
        $user = User::getByID($id);
        $user->delete();
        $_SESSION['flash'] = 'Пользователь удалён';
        $this->redirectByAction('edit_user_list_fms', array('id' => $_GET['id']));
    }

    public function apache_conf_action()
    {
        /* $id = $this->getNumeric('id');
         $user = User::getByID($id);*/ //header("Content-type: text/html; charset=windows-1251");
        // $user = new User();
        $univer = HeadCenter::getByID($_GET['id']);
        if (!$univer) {
            $this->redirectAccessRestricted();
        }

        return $this->render->view(
            'root/apache_conf',
            array(
                'item' => $univer,
            )
        );
    }

    public function change_price_univers_action()
    {
        $CURRENT_HEAD_CENTER = 2; //!!!!
        $sql = 'SELECT id,name FROM sdt_university WHERE head_id='.$CURRENT_HEAD_CENTER.' AND is_price_change=1';
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
            if (!empty($price) || !empty($sub_test_price)) {
                if (empty($price)) {
                    $price = 0;
                }
                if (empty($sub_test_price)) {
                    $sub_test_price = 0;
                }
                $to_save[] = array(
                    'univer_id' => $univer_id,
                    'test_level_id' => $key,
                    'price' => $price,
                    'sub_test_price' => $sub_test_price,
                );
            }
        }
        if (!empty($to_save)) {
            ChangedPriceTestLevel::save($to_save);
        }
        die('ok');
    }

    public function statistics_gc_action()
    {
        $list = Reports::getAll();

        return $this->render->view(
            'root/statistics_gc',
            array(
                'list' => $list,
            )
        );
    }

    public function statistics_action()
    {
        $list = Reports::getAll4User();

        return $this->render->view(
            'root/statistics',
            array(
                'list' => $list,
            )
        );
    }

    /**
     * @return string
     * Отчет 2
     */
    public function statist_full_gc_work_action()
    {
        $search = false;
        $res = array();
        $from = '1.01.'.date('Y');
        $to = date('d.m.Y');
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $search = true;
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $st = new StatistFullGcWork();
            $res = $st->execute($from, $to);
        }

        return $this->render->view(
            'root/statist/full_gc_work',
            array(
                'array' => $res,
                'search' => $search,
                'from' => $from,
                'to' => $to,
                'caption' => 'Статистика работы головных центров',
            )
        );
    }

    /**
     * @return string
     * Отчет 3
     */
    public function fms_report_about_exam_action()
    {
        $search = false;
        $result = array();
        $from = '1.01.'.date('Y');
        $to = date('d.m.Y');
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $search = true;
            $connection = Connection::getInstance();
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $statest = array();
            foreach (Act::getInnerStates() as $st) {
                $statest[] = "'".$st."'";
            }
            $statest = implode(', ', $statest);
            $sql = "SELECT
  shc.short_name,
  shc.id,
  sdt_test_levels.caption,
  sdt_test_levels.id AS level_id,
  count(DISTINCT sap.id) AS sdalo,

  sum(if(sap.blank_number IS NOT NULL AND length(sap.blank_number)>0 , 1,0)) AS certs
FROM sdt_act_people sap
  LEFT JOIN sdt_act_test sat
    ON sap.test_id = sat.id
  LEFT JOIN sdt_act sa
    ON sa.id = sat.act_id
  LEFT JOIN sdt_university su
    ON sa.university_id = su.id
  LEFT JOIN sdt_head_center shc
    ON su.head_id = shc.id
  LEFT JOIN sdt_test_levels
    ON sat.level_id = sdt_test_levels.id
WHERE sa.created BETWEEN '".$connection->escape($this->mysql_date($from))." 0:0:0' AND '".$connection->escape(
                    $this->mysql_date($to)
                )." 23:59:59'
AND sa.test_level_type_id = 2
AND sa.deleted = 0
AND sap.deleted = 0
AND sat.deleted = 0
AND shc.deleted = 0

AND sa.state IN (".$statest.")
AND sap.document = '".ActMan::DOCUMENT_CERTIFICATE."'
GROUP BY shc.id,
         sat.level_id";
//            die($sql);
            $res = $connection->query($sql);
//            $levels = array(
//                0 => array(
//                    13,
//                    16
//                ),
//                1 => array(
//                    14,
//                    17
//                ),
//                2 => array(
//                    15,
//                    18
//                ),
//            );
            /* $levels = array(
                 13 => 0,
                 16 => 0,
                 19 => 0,
                 14 => 1,
                 17 => 1,
                 20 => 1,
                 15 => 2,
                 18 => 2,
                 21 => 2,
                 22 => 2,
                 23 => 2,
                 24 => 2,
                 25 => 2,
                 26 => 2,
                 27 => 2,
                 28 => 2,
                 29 => 2,
             );*/
            $levels = ExamHelper::getLevelGroups();
            /*            $gc = array(
                            2 => 0,
                            3 => 1,
                            1 => 0,
                            9 => 2,
                            10 => 3,
                            4 => 4,
                            8 => 0,
                            5 => 5,
                            7 => 0,
                            11 => 6,
                            12 => 7,
                            13 => 0,
                        );*/
            $gc = HeadCenters::getStatistHcArrayAll();
            $template = array(
                'levels' => array(
                    0 => 0,
                    1 => 0,
                    2 => 0,
                ),
                'certs' => 0,
                'orgs' => 0,
            );
            /*$result = array(
                0 => array(
                    'caption' => 'РУДН',
                    'data' => $template,
                ),
                1 => array(
                    'caption' => 'МГУ',
                    'data' => $template,
                ),
                2 => array(
                    'caption' => 'ТОГУ',
                    'data' => $template,
                ),
                3 => array(
                    'caption' => 'ТюмГУ',
                    'data' => $template,
                ),
                4 => array(
                    'caption' => 'Гос. ИРЯ им. А.С. Пушкина ',
                    'data' => $template,
                ),
                5 => array(
                    'caption' => 'СПбГУ ',
                    'data' => $template,
                ),
                6 => array(
                    'caption' => 'ВолГУ ',
                    'data' => $template,
                ),
                7 => array(
                    'caption' => 'КФУ ',
                    'data' => $template,
                ),

            );*/
            $result = HeadCenters::getStatistResultArrayAll($template);
            foreach ($res as $item) {
                if (!array_key_exists($item['id'], $gc)) {
                    continue;
                }
                $cr = &$result[$gc[$item['id']]]['data'];
                $cr['levels'][$levels[$item['level_id']]] += $item['sdalo'];
                $cr['certs'] += $item['certs'];
            }
//            echo '<pre>';
//            (var_dump($res));
//            echo '<hr>';
            $sql = 'SELECT count(*) AS cc, head_id FROM sdt_university WHERE deleted=0 GROUP BY head_id';
//            die($sql);
            $res = $connection->query($sql);
            foreach ($res as $item) {
                if (!array_key_exists($item['head_id'], $gc)) {
                    continue;
                }
                $cr = &$result[$gc[$item['head_id']]]['data'];
                $cr['orgs'] += $item['cc'];
            }
//            die(var_dump($result));
        }

        return $this->render->view(
            'root/statist/fms_report_about_exam',
            array(
                'array' => $result,
                'search' => $search,
                'from' => $from,
                'to' => $to,
                'caption' => 'Статистика работы всех головных центров по проведению комплексного экзамена',
            )
        );
    }

    public function mysql_date($date)
    {
        return date('Y-m-d', strtotime($date));
    }

    /**
     * Отчет 11
     * @return string
     */
    public function report_by_regions_action()
    {
        $search = false;
        $result = array();
        $from = '1.01.2015';
        $to = date('d.m.Y');
        $chosen_hc_name = null;
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $search = true;
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $region = filter_input(INPUT_POST, 'region');
            $hc = filter_input(INPUT_POST, 'hc');
            $rep = new report_by_regions();
            $result = $rep->execute($from, $to, $region, $hc);
        }
        $regions_list = Regions::getAll4Form();
        $hc_list = HeadCenters::getAll();

        return $this->render->view(
            'root/statist/report_by_regions',
            array(
                'array' => $result,
                'search' => $search,
                'from' => $from,
                'to' => $to,
                'regions_list' => $regions_list,
                'hc_list' => $hc_list,
                'caption' => 'Статистика работы всех головных центров по проведению комплексного экзамена по регионам'.$chosen_hc_name,
            )
        );
    }

    /**
     * @return string
     * Отчет 4
     */
    public function minobr_pfur_report_about_exam_action()
    {
        $search = false;
        $result = array();
        $from = '1.01.'.date('Y');
        $to = date('d.m.Y');
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $search = true;
            $connection = Connection::getInstance();
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $statest = array();
            foreach (Act::getInnerStates() as $st) {
                $statest[] = "'".$st."'";
            }
            $statest = implode(', ', $statest);
            $sql = "SELECT
  shc.short_name,
  shc.id,
  sdt_test_levels.caption,
  sdt_test_levels.id AS level_id,
  count(DISTINCT sap.id) AS sdalo,
  sum(if(sap.blank_number IS NOT NULL AND length(sap.blank_number)>0 , 1,0)) AS certs
FROM sdt_act_people sap
  LEFT JOIN sdt_act_test sat
    ON sap.test_id = sat.id
  LEFT JOIN sdt_act sa
    ON sa.id = sat.act_id
  LEFT JOIN sdt_university su
    ON sa.university_id = su.id
  LEFT JOIN sdt_head_center shc
    ON su.head_id = shc.id
  LEFT JOIN sdt_test_levels
    ON sat.level_id = sdt_test_levels.id
WHERE sa.created BETWEEN '".$connection->escape($this->mysql_date($from))." 0:0:0' AND '".$connection->escape(
                    $this->mysql_date($to)
                )." 23:59:59'
AND sa.test_level_type_id = 2
AND sa.deleted = 0
AND sap.deleted = 0
AND sat.deleted = 0
AND shc.deleted = 0
AND shc.horg_id = 1

AND sa.state IN (".$statest.")
AND sap.document = '".ActMan::DOCUMENT_CERTIFICATE."'
GROUP BY shc.id,
         sat.level_id";
            $res = $connection->query($sql);
//            $levels = array(
//                0 => array(
//                    13,
//                    16
//                ),
//                1 => array(
//                    14,
//                    17
//                ),
//                2 => array(
//                    15,
//                    18
//                ),
//            );
            /* $levels = array(
                 13 => 0,
                 16 => 0,
                 19 => 0,
                 14 => 1,
                 17 => 1,
                 20 => 1,
                 15 => 2,
                 18 => 2,
                 21 => 2,
                 22 => 2,
                 23 => 2,
                 24 => 2,
                 25 => 2,
                 26 => 2,
                 27 => 2,
                 28 => 2,
                 29 => 2,
             );*/
            $levels = ExamHelper::getLevelGroups();
            /*   $gc = array(
                   2 => 1,
                   1 => 0,
                   8 => 3,
                   7 => 2,
                   13 => 4,
               );*/
            $gc = HeadCenters::getStatistHcArrayPfur();
            $template = array(
                'levels' => array(
                    0 => 0,
                    1 => 0,
                    2 => 0,
                ),
                'certs' => 0,
                'orgs' => 0,
            );
            /*            $result = array(
                            0 => array(
                                'caption' => 'МЦТ',
                                'data' => $template,
                            ),
                            1 => array(
                                'caption' => 'ГЦТРКИ',
                                'data' => $template,
                            ),
                            2 => array(
                                'caption' => 'ШОПМ',
                                'data' => $template,
                            ),
                            3 => array(
                                'caption' => 'ЦТ РУДН',
                                'data' => $template,
                            ), 4 => array(
                                'caption' => 'РУДН СОЧИ',
                                'data' => $template,
                            ),


                        );*/
            $result = HeadCenters::getStatistResultArrayPfur($template);
            foreach ($res as $item) {
                if (!array_key_exists($item['id'], $gc)) {
                    continue;
                }
                $cr = &$result[$gc[$item['id']]]['data'];
                $cr['levels'][$levels[$item['level_id']]] += $item['sdalo'];
                $cr['certs'] += $item['certs'];
            }
            $sql = 'SELECT count(*) AS cc, head_id FROM sdt_university WHERE deleted=0 GROUP BY head_id';
            $res = $connection->query($sql);
            foreach ($res as $item) {
                if (!array_key_exists($item['head_id'], $gc)) {
                    continue;
                }
                $cr = &$result[$gc[$item['head_id']]]['data'];
                $cr['orgs'] += $item['cc'];
            }
//            die(var_dump($result));
        }

        return $this->render->view(
            'root/statist/fms_report_about_exam',
            array(
                'array' => $result,
                'search' => $search,
                'from' => $from,
                'to' => $to,
                'caption' => 'Статистика работы  головных центров РУДН по проведению комплексного экзамена',
            )
        );
    }

    /**
     * Информация о прохождении комплексного экзамена по локальным центрам РУДН
     * Отчет 5
     * @return string
     */
    public function minobr_pfur_local_report_about_exam_action()
    {
        $search = false;
        $result = array();
        $from = '1.01.2015';
        $to = date('d.m.Y');
        $region = null;
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $search = true;
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $region = filter_input(INPUT_POST, 'region', FILTER_VALIDATE_INT);
            $rep = new minobr_pfur_local_report_about_exam();
            $result = $rep->execute($from, $to, $region);
            /*  $sql = 'select count(*) as cc, head_id from sdt_university where deleted=0 group by head_id';
              $res = $connection->query($sql);

              foreach ($res as $item) {
                  if (!array_key_exists($item['head_id'], $gc)) {
                      continue;
                  }

                  $cr = &$result[$gc[$item['head_id']]]['data'];

                  $cr['orgs'] += $item['cc'];
              }*/ //echo '<pre>';
//            die(var_dump($result));
        }

        return $this->render->view(
            'root/statist/minobr_report_about_exam_local',
            array(
                'array' => $result,
                'search' => $search,
                'from' => $from,
                'to' => $to,
                'region' => $region,
                'regions' => Regions::getSorted(),
                'caption' => 'Информация о прохождении комплексного экзамена по РУДН по локальным центрам',
            )
        );
    }

    /**
     * @return string
     * Отчет 37
     */
    public function minobr_pfur_local_report_about_exam_with_empty_action()
    {
        $search = false;
        $result = array();
        $from = '1.01.2015';
        $to = date('d.m.Y');
        $region = null;
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $search = true;
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $region = filter_input(INPUT_POST, 'region', FILTER_VALIDATE_INT);
            $rep = new minobr_pfur_local_report_about_exam_with_empty();
            $result = $rep->execute($from, $to, $region);
            /*  $sql = 'select count(*) as cc, head_id from sdt_university where deleted=0 group by head_id';
              $res = $connection->query($sql);

              foreach ($res as $item) {
                  if (!array_key_exists($item['head_id'], $gc)) {
                      continue;
                  }

                  $cr = &$result[$gc[$item['head_id']]]['data'];

                  $cr['orgs'] += $item['cc'];
              }*/ //echo '<pre>';
//            die(var_dump($result));
        }

        return $this->render->view(
            'root/statist/minobr_report_about_exam_local',
            array(
                'array' => $result,
                'search' => $search,
                'from' => $from,
                'to' => $to,
                'region' => $region,
                'regions' => Regions::getSorted(),
                'caption' => 'Информация о прохождении комплексного экзамена по РУДН по локальным центрам с ПУСТЫМИ ЛЦ',
            )
        );
    }

    /**
     * Информация о прохождении комплексного экзамена по локальным центрам
     * Отчет 6
     * @return string
     */
    public function minobr_local_report_about_exam_action()
    {
        $search = false;
        $result = array();
        $from = '1.01.2015';
        $to = date('d.m.Y');
        $region = null;
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $search = true;
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $region = filter_input(INPUT_POST, 'region', FILTER_VALIDATE_INT);
            $report = new ReportMinobrLocalReportAboutExam();
            $result = $report->execute($from, $to, $region);
        }

        return $this->render->view(
            'root/statist/minobr_report_about_exam_local',
            array(
                'array' => $result,
                'search' => $search,
                'from' => $from,
                'to' => $to,
                'region' => $region,
                'regions' => Regions::getSorted(),
                'caption' => 'Информация о прохождении комплексного экзамена  по локальным центрам',
            )
        );
    }

    /**
     * Перечень организаций-партнеров образовательных организаций высшего образования Российской Федерации, на базе которых по состоянию на 8 августа 2016 года проводится экзамен по русскому языку как иностранному, истории России и основам законодательства Российской Федерации
     * Отчет 41
     * @return string
     */
    public function minobr_local_report_about_exam_with_region_action()
    {
        $search = false;
        $result = array();
        $from = '1.01.2015';
        $to = date('d.m.Y');
        $region = null;
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $search = true;
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $region = filter_input(INPUT_POST, 'region', FILTER_VALIDATE_INT);
            $report = new ReportMinobrLocalReportAboutExamWithRegion();
            $result = $report->execute($from, $to, $region);
        }

        return $this->render->view(
            'root/statist/minobr_local_report_about_exam_with_region',
            array(
                'array' => $result,
                'search' => $search,
                'from' => $from,
                'to' => $to,
                'region' => $region,
                'regions' => Regions::getSorted(),
                'caption' => 'Информация о прохождении комплексного экзамена  по локальным центрам',
            )
        );
    }

    /**
     * Информация о прохождении РКИ по локальным центрам
     * Отчет 18
     * @return string
     */
    public function minobr_local_report_about_rki_action()
    {
        $search = false;
        $result = array();
        $from = '1.01.2015';
        $to = date('d.m.Y');
        $region = null;
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $search = true;
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $region = filter_input(INPUT_POST, 'region', FILTER_VALIDATE_INT);
            $rep = new minobr_local_report_about_rki();
            $result = $rep->execute($from, $to, $region);
        }

        return $this->render->view(
            'root/statist/minobr_report_about_rki_local',
            array(
                'array' => $result,
                'search' => $search,
                'from' => $from,
                'to' => $to,
                'region' => $region,
                'regions' => Regions::getSorted(),
                'caption' => 'Информация о прохождении РКИ  по локальным центрам',
            )
        );
    }

    /**
     * Информация о прохождении РКИ по локальным центрам
     * Отчет 19
     * @return string
     */
    public function minobr_local_report_about_rki_pfur_action()
    {
        $search = false;
        $result = array();
        $from = '1.01.2015';
        $to = date('d.m.Y');
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $search = true;
            $connection = Connection::getInstance();
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $statest = array();
            foreach (Act::getInnerStates() as $st) {
                $statest[] = "'".$st."'";
            }
            $statest = implode(', ', $statest);
            $sql = "SELECT
  su.name, su.legal_address, su.id, shc.id AS head_id,
  sdt_test_levels.caption,
  sdt_test_levels.id AS level_id,
  count(DISTINCT sap.id) AS sdalo,
  sum(if(sap.blank_number IS NOT NULL AND length(sap.blank_number)>0 , 1,0)) AS certs,
  sap.document
FROM sdt_act_people sap
  LEFT JOIN sdt_act_test sat
    ON sap.test_id = sat.id
  LEFT JOIN sdt_act sa
    ON sa.id = sat.act_id
  LEFT JOIN sdt_university su
    ON sa.university_id = su.id
  LEFT JOIN sdt_head_center shc
    ON su.head_id = shc.id
  LEFT JOIN sdt_test_levels
    ON sat.level_id = sdt_test_levels.id

WHERE sa.created BETWEEN '".$connection->escape($this->mysql_date($from))." 0:0:0' AND '".$connection->escape(
                    $this->mysql_date($to)
                )." 23:59:59'
AND sa.test_level_type_id = 1
AND sa.deleted = 0
AND sap.deleted = 0
AND sat.deleted = 0
AND shc.deleted = 0
AND sa.state IN (".$statest.")


  GROUP BY su.id, sat.level_id, sap.document
    ORDER BY shc.id, su.name";
//            die($sql);
            $res = $connection->query($sql);
//            AND sap.document = '" . ActMan::DOCUMENT_CERTIFICATE . "'
//            die($sql);
//and shc.id in (1,2,7,8)
//            $levels = array(
//                0 => array(
//                    13,
//                    16
//                ),
//                1 => array(
//                    14,
//                    17
//                ),
//                2 => array(
//                    15,
//                    18
//                ),
//            );
            $levels = array(
                1 => 0,
                2 => 1,
                3 => 2,
                5 => 3,
                6 => 4,
                7 => 5,
                8 => 6,
                10 => 8,
                11 => 7,
                12 => 8,
            );
            /*$gc = array(
                2 => 1,
                1 => 0,
                8 => 3,
                7 => 2,
                13 => 4,

            );*/
            $gc = HeadCenters::getStatistHcArrayPfur();
            $template = array(
                'caption' => '',
                'certificate' => array(
                    0 => 0,
                    1 => 0,
                    2 => 0,
                    3 => 0,
                    4 => 0,
                    5 => 0,
                    6 => 0,
                    7 => 0,
                    8 => 0,
                ),
                'note' => array(
                    0 => 0,
                    1 => 0,
                    2 => 0,
                    3 => 0,
                    4 => 0,
                    5 => 0,
                    6 => 0,
                    7 => 0,
                    8 => 0,
                ),
                'certs' => 0,
                'notes' => 0,
//                'orgs' => 0,
            );
            /*  $result = array(
                  0 => array(
                      'caption' => 'МЦТ',
                      'centers' => array(),

                  ),
                  1 => array(
                      'caption' => 'ГЦТРКИ',
                      'centers' => array(),
                  ),
                  2 => array(
                      'caption' => 'ШОПМ',
                      'centers' => array(),
                  ),
                  3 => array(
                      'caption' => 'ЦТ РУДН',
                      'centers' => array(),
                  ),  4 => array(
                      'caption' => 'РУДН СОЧИ',
                      'centers' => array(),
                  ),


              );*/
            $result = HeadCenters::getStatistResultArrayPfur();
            foreach ($res as $item) {
                if (!array_key_exists($item['head_id'], $gc)) {
                    continue;
                }
                $cr = &$result[$gc[$item['head_id']]]['centers'];
                if (!array_key_exists($item['id'], $cr)) {
                    $cr[$item['id']] = $template;
                    $contractSql = 'SELECT * FROM sdt_university_dogovor WHERE deleted = 0 AND  university_id =  '.intval(
                            $item['id']
                        );
                    $contractRes = $connection->query($contractSql);
                    $dogovor = array();
                    if ($contractRes) {
                        foreach ($contractRes as $d) {
                            $dogovor[] = $d['number'].' от '.$this->date($d['date']);
                        }
                    }
                    $cr[$item['id']]['caption'] = $item['name'];
                    $cr[$item['id']]['address'] = $item['legal_address'];
                    $cr[$item['id']]['dogovor'] = implode('; ', $dogovor);
                }
                $lc = &$cr[$item['id']];
                $lc[$item['document']][$levels[$item['level_id']]] += $item['sdalo'];
                if ($item['document'] == ActMan::DOCUMENT_CERTIFICATE) {
                    $lc['certs'] += $item['certs'];
                } else {
                    $lc['notes'] += $item['certs'];
                }
            }
            /*  $sql = 'select count(*) as cc, head_id from sdt_university where deleted=0 group by head_id';
              $res = $connection->query($sql);

              foreach ($res as $item) {
                  if (!array_key_exists($item['head_id'], $gc)) {
                      continue;
                  }

                  $cr = &$result[$gc[$item['head_id']]]['data'];

                  $cr['orgs'] += $item['cc'];
              }*/ //echo '<pre>';
//            die(var_dump($result));
        }

        return $this->render->view(
            'root/statist/minobr_report_about_rki_local',
            array(
                'array' => $result,
                'search' => $search,
                'from' => $from,
                'to' => $to,
                'caption' => 'Информация о прохождении РКИ  по локальным центрам по РУДН',
            )
        );
    }

    public function date($date)
    {
        return date('d.m.Y', strtotime($date));
    }

    public function minobr_local_report_about_rki_pfur_with_empty_action()
    {
        $search = false;
        $result = array();
        $from = '1.01.2015';
        $to = date('d.m.Y');
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $search = true;
            $connection = Connection::getInstance();
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $statest = array();
            foreach (Act::getInnerStates() as $st) {
                $statest[] = "'".$st."'";
            }
            $statest = implode(', ', $statest);
            $sql = "SELECT
  su.name, su.legal_address, su.id, shc.id AS head_id,
  sdt_test_levels.caption,
  sdt_test_levels.id AS level_id,
  count(DISTINCT sap.id) AS sdalo,
  sum(if(sap.blank_number IS NOT NULL AND length(sap.blank_number)>0 , 1,0)) AS certs,
  sap.document

  FROM sdt_university su
   LEFT JOIN sdt_act sa ON sa.university_id = su.id
   LEFT JOIN sdt_act_test sat ON sa.id = sat.act_id
LEFT JOIN  sdt_act_people sap ON sap.test_id = sat.id



LEFT JOIN sdt_head_center shc ON su.head_id = shc.id
LEFT JOIN sdt_test_levels ON sat.level_id = sdt_test_levels.id
 /*
FROM sdt_act_people sap
  left JOIN sdt_act_test sat
    ON sap.test_id = sat.id
  left JOIN sdt_act sa
    ON sa.id = sat.act_id
  left JOIN sdt_university su
    ON sa.university_id = su.id
  left JOIN sdt_head_center shc
    ON su.head_id = shc.id
  left JOIN sdt_test_levels
    ON sat.level_id = sdt_test_levels.id*/

WHERE (sa.created BETWEEN '".$connection->escape($this->mysql_date($from))." 0:0:0' AND '".$connection->escape(
                    $this->mysql_date($to)
                )." 23:59:59' OR sa.created IS NULL)
AND (sa.test_level_type_id = 1 OR sa.test_level_type_id IS NULL)
AND (sa.deleted = 0 OR sa.deleted IS NULL)
AND (sap.deleted = 0  OR sap.deleted IS NULL)
AND (sat.deleted = 0   OR sat.deleted IS NULL)
AND shc.deleted = 0
AND (sa.state IN (".$statest.") OR sa.state IS NULL)


  GROUP BY su.id, sat.level_id, sap.document
    ORDER BY shc.id, su.name";
//            die($sql);
            $res = $connection->query($sql);
//            AND sap.document = '" . ActMan::DOCUMENT_CERTIFICATE . "'
//            die($sql);
//and shc.id in (1,2,7,8)
//            $levels = array(
//                0 => array(
//                    13,
//                    16
//                ),
//                1 => array(
//                    14,
//                    17
//                ),
//                2 => array(
//                    15,
//                    18
//                ),
//            );
            $levels = array(
                1 => 0,
                2 => 1,
                3 => 2,
                5 => 3,
                6 => 4,
                7 => 5,
                8 => 6,
                10 => 8,
                11 => 7,
                12 => 8,
            );
            /* $gc = array(
                 2 => 1,
                 1 => 0,
                 8 => 3,
                 7 => 2,

             );*/
            $gc = HeadCenters::getStatistHcArrayPfur();
            $template = array(
                'caption' => '',
                'certificate' => array(
                    0 => 0,
                    1 => 0,
                    2 => 0,
                    3 => 0,
                    4 => 0,
                    5 => 0,
                    6 => 0,
                    7 => 0,
                    8 => 0,
                ),
                'note' => array(
                    0 => 0,
                    1 => 0,
                    2 => 0,
                    3 => 0,
                    4 => 0,
                    5 => 0,
                    6 => 0,
                    7 => 0,
                    8 => 0,
                ),
                'certs' => 0,
                'notes' => 0,
//                'orgs' => 0,
            );
            /*            $result = array(
                            0 => array(
                                'caption' => 'МЦТ',
                                'centers' => array(),

                            ),
                            1 => array(
                                'caption' => 'ГЦТРКИ',
                                'centers' => array(),
                            ),
                            2 => array(
                                'caption' => 'ШОПМ',
                                'centers' => array(),
                            ),
                            3 => array(
                                'caption' => 'ЦТ РУДН',
                                'centers' => array(),
                            ),


                        );*/
            $result = HeadCenters::getStatistResultArrayPfur();
            foreach ($res as $item) {
                if (!array_key_exists($item['head_id'], $gc)) {
                    continue;
                }
                $cr = &$result[$gc[$item['head_id']]]['centers'];
                if (!array_key_exists($item['id'], $cr)) {
                    $cr[$item['id']] = $template;
                    $contractSql = 'SELECT * FROM sdt_university_dogovor WHERE deleted = 0 AND  university_id =  '.intval(
                            $item['id']
                        );
                    $contractRes = $connection->query($contractSql);
                    $dogovor = array();
                    if ($contractRes) {
                        foreach ($contractRes as $d) {
                            $dogovor[] = $d['number'].' от '.$this->date($d['date']);
                        }
                    }
                    $cr[$item['id']]['caption'] = $item['name'];
                    $cr[$item['id']]['address'] = $item['legal_address'];
                    $cr[$item['id']]['dogovor'] = implode('<br>', $dogovor);
                }
                $lc = &$cr[$item['id']];
                if (!empty($item['document']) && !empty($item['level_id']) && !empty($item['sdalo'])) {
                    $lc[$item['document']][$levels[$item['level_id']]] += $item['sdalo'];
                }
                if ($item['document'] == ActMan::DOCUMENT_CERTIFICATE) {
                    $lc['certs'] += $item['certs'];
                } else {
                    $lc['notes'] += $item['certs'];
                }
            }
            /*  $sql = 'select count(*) as cc, head_id from sdt_university where deleted=0 group by head_id';
              $res = $connection->query($sql);

              foreach ($res as $item) {
                  if (!array_key_exists($item['head_id'], $gc)) {
                      continue;
                  }

                  $cr = &$result[$gc[$item['head_id']]]['data'];

                  $cr['orgs'] += $item['cc'];
              }*/ //echo '<pre>';
//            die(var_dump($result));
        }

        return $this->render->view(
            'root/statist/minobr_report_about_rki_local',
            array(
                'array' => $result,
                'search' => $search,
                'from' => $from,
                'to' => $to,
                'caption' => 'Информация о прохождении РКИ  по локальным центрам по РУДН с ПУСТЫМИ ЛЦ',
            )
        );
    }

    /**
     * Количество справок
     * Отчет 7
     * @return string
     */
    public function statist_exam_notes_action()
    {
        $search = false;
        $result = array();
        $from = '1.01.2015';
        $to = date('d.m.Y');
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $search = true;
            $connection = Connection::getInstance();
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $statest = array();
            foreach (Act::getInnerStates() as $st) {
                $statest[] = "'".$st."'";
            }
            $statest = implode(', ', $statest);
            $sql = "select shc.short_name as caption, count(distinct sap.id) as cc from sdt_act_people sap
left join sdt_act sa  on sa.id = sap.act_id
left join sdt_university su on su.id = sa.university_id
left join sdt_head_center shc on shc.id = su.head_id

where
sap.document = '".ActMan::DOCUMENT_NOTE."'
and sa.created >= '".$connection->escape($this->mysql_date($from))." 0:0:0'
and sa.created <= '".$connection->escape($this->mysql_date($to))." 23:59:59'
and sa.deleted = 0
and sap.deleted = 0
and sa.test_level_type_id = 2
and sa.state in ($statest)
and (sap.blank_number is not null and sap.blank_number  <> '')

group by shc.id";
            $result = $connection->query($sql);
        }

//
        return $this->render->view(
            'root/statist/notes',
            array(
                'array' => $result,
                'search' => $search,
                'from' => $from,
                'to' => $to,
                'caption' => 'Количество справок по экзамену',
            )
        );
    }

    public function messages_action()
    {
        return $this->render->view(
            'messages/menu',
            array()
        );
    }

    public function new_message_action()
    {
        $message_errors = array();
        if (!empty($_POST['sender_id']) && $_SESSION['u_id'] == $_POST['sender_id']) {
            if (empty($_POST['message'])) {
                $message_errors[] = 'Введите текст сообщения';
            }
            if (empty($message_errors)) {
                $sender_id = filter_input(INPUT_POST, 'sender_id', FILTER_VALIDATE_INT);
                $user_type = filter_input(INPUT_POST, 'user_type', FILTER_VALIDATE_INT);
                $hc_id = filter_input(INPUT_POST, 'hc_id', FILTER_VALIDATE_INT);
//                $text = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                $text = filter_input(INPUT_POST, 'message');
                $sql = "INSERT INTO `system_message` (`sender_id`, `text`, `user_type`, `hc_id`,`date`)
            VALUES (".$sender_id.", '".mysql_real_escape_string($text)."', ".$user_type.", ".$hc_id.",now())";
                $result = mysql_query($sql) or die('Ошибка записи');
                $_SESSION['flash'] = 'Сообщение отправлено';
                $this->redirectByAction('messages');
            }
        }
        $HC = HeadCenters::getAll();

        return $this->render->view(
            'messages/message_form',
            array(
                'HC' => $HC,
                'message_errors' => $message_errors,
            )
        );
    }

    public function messages_list_action()
    {
        $list = array();
        $sql = 'SELECT t1.*, t2.name,t2.short_name FROM system_message AS t1
          LEFT JOIN sdt_head_center AS t2 ON t1.hc_id=t2.id
          ORDER BY t1.`date` DESC';
        $result = mysql_query($sql);
        while ($res = mysql_fetch_assoc($result)) {
            $list[] = $res;
        }

        return $this->render->view(
            'messages/messages_list',
            array(
                'list' => $list,
            )
        );
    }

    public function message_delete_action()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!empty($id)) {
            $sql = 'DELETE FROM system_message WHERE id='.$id;
            $result = mysql_query($sql) or die(mysql_error());
            $_SESSION['flash'] = 'Сообщение удалено';
            $this->redirectByAction('messages_list');
        }
    }

    public function message_edit_action()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!empty($id)) {
            $sql = 'SELECT * FROM system_message WHERE id='.$id.' LIMIT 1';
            $result = mysql_query($sql) or die(mysql_error());
            if (!mysql_num_rows($result)) {
                $this->redirectByAction('messages');
            }
            $res = mysql_fetch_assoc($result);
//            $_SESSION['flash'] = 'Сообщение удалено';
//            $this->redirectByAction('messages_list');
            $message_errors = array();
            if (!empty($_POST['sender_id']) && $_SESSION['u_id'] == $_POST['sender_id']) {
                if (empty($_POST['message'])) {
                    $message_errors[] = 'Введите текст сообщения';
                }
                if (empty($message_errors)) {
                    $sender_id = filter_input(INPUT_POST, 'sender_id', FILTER_VALIDATE_INT);
                    $user_type = filter_input(INPUT_POST, 'user_type', FILTER_VALIDATE_INT);
                    $hc_id = filter_input(INPUT_POST, 'hc_id', FILTER_VALIDATE_INT);
//                    $text = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                    $text = filter_input(INPUT_POST, 'message');
                    $sql = "UPDATE `system_message` SET `text`='".mysql_real_escape_string(
                            $text
                        )."', `user_type`=".$user_type.", `hc_id`=".$hc_id.", `date`=now() WHERE  `id`=".$id;
                    $result = mysql_query($sql) or die('Ошибка записи');
                    $_SESSION['flash'] = 'Сообщение изменено';
                    $this->redirectByAction('messages');
                }
            }
            $HC = HeadCenters::getAll();

            return $this->render->view(
                'messages/message_form',
                array(
                    'HC' => $HC,
                    'message_errors' => $message_errors,
                    'data' => $res,
                )
            );
        }
        $this->redirectByAction('messages');
    }

    /**
     * @return string
     * Отчет 8
     */
    public function fms_report_about_exam_rki_action()
    {
        $search = false;
        $result = array();
        $from = '1.01.2015';
        $to = date('d.m.Y');
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $search = true;
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $report = new FmsReportAboutExamRkiReport();
            $result = $report->execute($from, $to);
//            die(var_dump($result));
        }

        return $this->render->view(
            'root/statist/fms_report_about_exam_rki',
            array(
                'array' => $result,
                'search' => $search,
                'from' => $from,
                'to' => $to,
                'caption' => 'Информация о прохождении РКИ для МинОбр',
            )
        );
    }

    /**
     * @return string
     * Отчет 9
     */
    public function minobr_pfur_report_about_rki_action()
    {
        $search = false;
        $result = array();
        $from = '1.01.'.date('Y');
        $to = date('d.m.Y');
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $search = true;
            $connection = Connection::getInstance();
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $statest = array();
            foreach (Act::getInnerStates() as $st) {
                $statest[] = "'".$st."'";
            }
            $statest = implode(', ', $statest);
            $sql = "SELECT
  shc.short_name,
  shc.id,
  sap.document,
  sdt_test_levels.caption,
  sdt_test_levels.id AS level_id,
  count(DISTINCT sap.id) AS sdalo,
  sum(if(sap.blank_number IS NOT NULL AND length(sap.blank_number)>0 , 1,0)) AS certs
FROM sdt_act_people sap
  LEFT JOIN sdt_act_test sat
    ON sap.test_id = sat.id
  LEFT JOIN sdt_act sa
    ON sa.id = sat.act_id
  LEFT JOIN sdt_university su
    ON sa.university_id = su.id
  LEFT JOIN sdt_head_center shc
    ON su.head_id = shc.id
  LEFT JOIN sdt_test_levels
    ON sat.level_id = sdt_test_levels.id
WHERE sa.created BETWEEN '".$connection->escape($this->mysql_date($from))." 0:0:0' AND '".$connection->escape(
                    $this->mysql_date($to)
                )." 23:59:59'
AND sa.test_level_type_id = 1
AND sa.deleted = 0
AND sap.deleted = 0
AND sat.deleted = 0
AND shc.deleted = 0
AND shc.horg_id = 1

AND sa.state IN (".$statest.")

GROUP BY shc.id,
         sat.level_id,
         sap.document
         ";
            $res = $connection->query($sql);
//            $levels = array(
//                0 => array(
//                    13,
//                    16
//                ),
//                1 => array(
//                    14,
//                    17
//                ),
//                2 => array(
//                    15,
//                    18
//                ),
//            );
            $levels = array(
                1 => 0,
                2 => 1,
                3 => 2,
                5 => 3,
                6 => 4,
                7 => 5,
                8 => 6,
                10 => 8,
                11 => 7,
                12 => 8,
            );
            /*$gc = array(
                2 => 1,
                1 => 0,
                8 => 3,
                7 => 2,
                13 => 4,
            );*/
            $gc = HeadCenters::getStatistHcArrayPfur();
            $template = array(
                'levels' => array(
                    0 => 0,
                    1 => 0,
                    2 => 0,
                    3 => 0,
                    4 => 0,
                    5 => 0,
                    6 => 0,
                    7 => 0,
                    8 => 0,
                ),
                'certs' => 0,
                'orgs' => 0,
            );
            $template = array(
                'levels' => array(
                    0 => 0,
                    1 => 0,
                    2 => 0,
                    3 => 0,
                    4 => 0,
                    5 => 0,
                    6 => 0,
                    7 => 0,
                    8 => 0,
                ),
                'certs' => 0,
                'note_levels' => array(
                    0 => 0,
                    1 => 0,
                    2 => 0,
                    3 => 0,
                    4 => 0,
                    5 => 0,
                    6 => 0,
                    7 => 0,
                    8 => 0,
                ),
                'notes' => 0,
                'orgs' => 0,
            );
            /*           $result = array(
                           0 => array(
                               'caption' => 'МЦТ',
                               'data' => $template,
                           ),
                           1 => array(
                               'caption' => 'ГЦТРКИ',
                               'data' => $template,
                           ),
                           2 => array(
                               'caption' => 'ШОПМ',
                               'data' => $template,
                           ),
                           3 => array(
                               'caption' => 'ЦТ РУДН',
                               'data' => $template,
                           ),
              4 => array(
                               'caption' => 'РУДН СОЧИ',
                               'data' => $template,
                           ),


                       );*/
            $result = HeadCenters::getStatistResultArrayPfur($template);
            foreach ($res as $item) {
                if (!array_key_exists($item['id'], $gc)) {
                    continue;
                }
                $cr = &$result[$gc[$item['id']]]['data'];
                if ($item['document'] === ActMan::DOCUMENT_CERTIFICATE) {
                    $cr['levels'][$levels[$item['level_id']]] += $item['sdalo'];
                    $cr['certs'] += $item['certs'];
                } else {
                    $cr['note_levels'][$levels[$item['level_id']]] += $item['sdalo'];
                    $cr['notes'] += $item['certs'];
                }
            }
            $sql = 'SELECT count(*) AS cc, head_id FROM sdt_university WHERE deleted=0 GROUP BY head_id';
            $res = $connection->query($sql);
            foreach ($res as $item) {
                if (!array_key_exists($item['head_id'], $gc)) {
                    continue;
                }
                $cr = &$result[$gc[$item['head_id']]]['data'];
                $cr['orgs'] += $item['cc'];
            }
//            die(var_dump($result));
        }

        return $this->render->view(
            'root/statist/rudn_report_about_exam_rki',
            array(
                'array' => $result,
                'search' => $search,
                'from' => $from,
                'to' => $to,
                'caption' => 'Информация о прохождении РКИ по РУДН',
            )
        );
    }

    /**
     * @return string
     * Отчет 10
     */
    public function report_sng_exam_action()
    {
        $search = false;
        $res = array();
        $from = '1.01.2015';
        $to = date('d.m.Y');
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $statest = array();
            foreach (Act::getInnerStates() as $st) {
                $statest[] = "'".$st."'";
            }
            $c = Connection::getInstance();
            $sql = "SELECT count(sdt_act_people.id) AS cc
			, c.name AS country_caption
			FROM
			sdt_act_people
			INNER JOIN sdt_act_test
			ON sdt_act_people.test_id = sdt_act_test.id
			INNER JOIN sdt_test_levels
			ON sdt_act_test.level_id = sdt_test_levels.id
			INNER JOIN sdt_act
			ON sdt_act_test.act_id = sdt_act.id
			INNER JOIN sdt_university
				ON sdt_university.id = sdt_act.university_id


        INNER JOIN country c
				ON c.id = sdt_act_people.country_id
			WHERE
			c.id IN ( 69,76,159, 170,167,17,171,111,90,95, 190,49 ,11,4 )
							   AND
sdt_act.state IN (".implode(', ', $statest).")
			AND sdt_act_people.testing_date >= '".$c->escape($this->mysql_date($from))." 0:0:0'
			AND sdt_act_people.testing_date <=  '".$c->escape(
                    $this->mysql_date($to)
                )." 23:59:59'
			AND sdt_act.deleted = 0
			AND sdt_act_people.document='".ActMan::DOCUMENT_CERTIFICATE."'
			AND sdt_act.test_level_type_id = 2
			AND sdt_act_people.deleted = 0
			AND sdt_act_test.deleted = 0

			GROUP BY
			c.id";
//die($sql);
            $search = true;
            $res = $c->query($sql);
        }

        return $this->render->view(
            'root/statist/report_sng_exam',
            array(
                'array' => $res,
                'search' => $search,
                'from' => $from,
                'to' => $to,
                'caption' => 'Отчет по количеству граждан из постсоветского пространства, сдавших комплексный экзамен',
            )
        );
    }

    public function minobr_report_about_exam_notes_action()
    {
        $search = false;
        $result = array();
        $from = '1.01.'.date('Y');
        $to = date('d.m.Y');
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $search = true;
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $report = new ReportMinobrReportAboutExamNotes();
            $result = $report->execute($from, $to);
//            die(var_dump($result));
        }

        return $this->render->view(
            'root/statist/fms_report_about_exam_note',
            array(
                'array' => $result,
                'search' => $search,
                'from' => $from,
                'to' => $to,
                'caption' => 'Статистика работы всех головных центров по проведению комплексного экзамена по сертификатам и справкам',
            )
        );
    }

// Отчет 12
    public function minobr_sng_report_about_exam_notes_action()
    {
        $search = false;
        $result = array();
        $from = '1.01.'.date('Y');
        $to = date('d.m.Y');
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $search = true;
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $report = new ReportMinobrSngReportAboutExamNotes();
            $result = $report->execute($from, $to);
//            die(var_dump($result));
        }

        return $this->render->view(
            'root/statist/fms_sng_report_about_exam_note',
            array(
                'array' => $result,
                'search' => $search,
                'from' => $from,
                'to' => $to,
                'caption' => 'Информация по количеству граждан из постсоветского пространства о прохождении комплексного экзамена для МинОбр по справкам и сертификатам',
            )
        );
    }

// Отчет 71
    public function report_active_local_centers_action()
    {
        $search = false;
        $result = array();
        $from = '1.01.'.date('Y');
        $to = date('d.m.Y');
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $search = true;
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $report = new ActiveLocalCenters();
            $result = $report->execute($from, $to);
//            die(var_dump($result));
        }

        return $this->render->view(
            'root/statist/report_active_local_centers',
            array(
                'array' => $result,
                'search' => $search,
                'from' => $from,
                'to' => $to,
                'caption' => 'Активные локальные центры (протестировали и выдали сертификат хотя бы одному человеку) за период',
            )
        );
    }


// Отчет 43

    /**
     * Отчет 13
     * @return string
     */
    public function minobr_pfur_report_about_exam_notes_action()
    {
        $search = false;
        $result = array();
        $from = '1.01.'.date('Y');
        $to = date('d.m.Y');
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $search = true;
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $report = new ReportMinobrPfurReportAboutExamNotes();
            $result = $report->execute($from, $to);
//            die(var_dump($result));
        }

        return $this->render->view(
            'root/statist/fms_report_about_exam_note',
            array(
                'array' => $result,
                'search' => $search,
                'from' => $from,
                'to' => $to,
                'caption' => 'Статистика работы  головных центров РУДН по проведению комплексного экзамена по сертификатам и справкам',
            )
        );
    }

    /**
     * @return string
     * Отчет 14
     */
    public function minobr_pfur_report_about_rki_by_citizenship_action()
    {
        $search = false;
        $result = array();
        $from = '1.01.2015';
        $to = date('d.m.Y');
        $chosen_hc_name = null;
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $search = true;
            $connection = Connection::getInstance();
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $hc = filter_input(INPUT_POST, 'hc');
            //$centers = array(1, 2, 7, 8);
            $centers = HeadCenters::getStatistHCByHO(1);
//var_dump($centers,$hc);
            if ($hc && is_numeric($hc)) {
                $centers = array($hc);
                $sql_hc = ' AND shc.id='.$hc.' ';
                $chosen_hc_name = ' по головному центру "'.HeadCenter::getByID($hc).'"';
            } elseif ($hc === 'pfur') {
                $chosen_hc_name = 'объеденённый';
                $sql_hc = ' and shc.id in ('.implode(",", $centers).') ';
            } else {
                $chosen_hc_name = '';
                $sql_hc = ' and shc.id in ('.implode(",", $centers).') ';
            }
            $statest = array();
            foreach (Act::getInnerStates() as $st) {
                $statest[] = "'".$st."'";
            }
            $statest = implode(', ', $statest);
//var_dump($centers);
            $sql = "
            SELECT
country.name, country.id, shc.id AS h_id, sap.document, sdt_test_levels.caption, sdt_test_levels.id AS level_id,
count(DISTINCT sap.id) AS sdalo,

sum(if(sap.blank_number IS NOT NULL AND length(sap.blank_number)>0 , 1,0)) AS certs
FROM sdt_act_people sap
LEFT JOIN sdt_act_test sat ON sap.test_id = sat.id
LEFT JOIN sdt_act sa ON sa.id = sat.act_id
LEFT JOIN sdt_university su ON sa.university_id = su.id
LEFT JOIN sdt_head_center shc ON su.head_id = shc.id
LEFT JOIN sdt_test_levels ON sat.level_id = sdt_test_levels.id
LEFT JOIN country ON country.id=sap.country_id
WHERE sa.created BETWEEN '".$connection->escape($this->mysql_date($from))." 0:0:0' AND '".$connection->escape(
                    $this->mysql_date($to)
                )." 23:59:59'
AND sa.test_level_type_id = 1 AND sa.deleted = 0 AND sap.deleted = 0 AND sat.deleted = 0 AND shc.deleted = 0
".$sql_hc."
AND sa.state IN (".$statest.")
GROUP BY shc.id,country.id, sat.level_id, sap.document
            ";
//die($sql);
            $res = $connection->query($sql);
            $levels = array(
                1 => 0,
                2 => 1,
                3 => 2,
                5 => 3,
                6 => 4,
                7 => 5,
                8 => 6,
                11 => 7,
                12 => 8,
            );
            $template = array(
                'levels' => array(
                    0 => 0,
                    1 => 0,
                    2 => 0,
                    3 => 0,
                    4 => 0,
                    5 => 0,
                    6 => 0,
                    7 => 0,
                    8 => 0,
                ),
                'certs' => 0,
                'note_levels' => array(
                    0 => 0,
                    1 => 0,
                    2 => 0,
                    3 => 0,
                    4 => 0,
                    5 => 0,
                    6 => 0,
                    7 => 0,
                    8 => 0,
                ),
                'notes' => 0,
                'orgs' => 0,
            );
//            var_dump($centers);
            $result = array();
            if ($hc === 'pfur') {
                $result['pfur'] = array();
            } else {
                foreach ($centers as $center) {
                    $result[$center] = array();
                }
            }
//            foreach ($centers as $center) {
//                $result[$center] = array();
//            }
//            die(var_dump($result));
//            foreach ($centers as $h_id) {
            foreach ($result as $h_id => $list) {
                foreach (Countries::getAll() as $reg) {
                    $result[$h_id][$reg->id] = array(
                        'caption' => $reg,
                        'data' => $template,
                    );
                }
                $result[$h_id]['no'] = array(
                    'caption' => 'Не указана',
                    'data' => $template,
                );
            }
//            die(var_dump($result));
            foreach ($res as $item) {
                $region_id = $item['id'];
                if (!$region_id) {
                    $region_id = 'no';
                }
//                $cr = &$result[$item['h_id']][$region_id]['data'];
                if ($hc === 'pfur') {
                    $cr = &$result['pfur'][$region_id]['data'];
                } else {
                    $cr = &$result[$item['h_id']][$region_id]['data'];
                }
                if ($item['document'] === ActMan::DOCUMENT_CERTIFICATE) {
                    $cr['levels'][$levels[$item['level_id']]] += $item['sdalo'];
                    $cr['certs'] += $item['certs'];
                } else {
                    $cr['note_levels'][$levels[$item['level_id']]] += $item['sdalo'];
                    $cr['notes'] += $item['certs'];
                }
            }
        }
        $regions_list = Countries::getAll4Form();
        $hc_list = array_map(
            function ($id) {
                return HeadCenter::getByID($id);
            },
            HeadCenters::getStatistHCByHO(1)
        );

        return $this->render->view(
            'root/statist/fms_report_about_exam_note_by_citizenship',
            array(
                'arrays' => $result,
                'search' => $search,
                'from' => $from,
                'to' => $to,
                'regions_list' => $regions_list,
                'hc_list' => $hc_list,
                'caption' => 'Результаты тестирования РКИ в РУДН по гражданству мигрантов ',
            )
        );
    }

    /**
     * Отчет 15
     *
     * @return string
     */
    public function minobr_pfur_report_about_exam_by_citizenship_action()
    {
        $search = false;
        $result = array();
        $from = '1.01.2015';
        $hc = null;
        $to = date('d.m.Y');
        $chosen_hc_name = null;
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $search = true;
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $hc = filter_input(INPUT_POST, 'hc');
            $rep = new minobr_pfur_report_about_exam_by_citizenship();
            $result = $rep->execute($from, $to, $hc);
//            die(var_dump($result));
        }
        $regions_list = Countries::getAll4Form();
        $hc_list = HeadCenters::getAll();

        return $this->render->view(
            'root/statist/fms_report_about_exam_by_citizenship',
            array(
                'selected_hc' => $hc,
                'arrays' => $result,
                'search' => $search,
                'from' => $from,
                'to' => $to,
                'regions_list' => $regions_list,
                'hc_list' => $hc_list,
                'caption' => 'Результаты тестирования по комплексному экзамену в РУДН по гражданству мигрантов ',
            )
        );
    }

    /**
     * @return string
     * Отчет 17
     */
    public function report_people_gc_action()
    {
        $search = false;
        $result = array();
        $from = '1.01.2015';
        $to = date('d.m.Y');
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $search = true;
            $connection = Connection::getInstance();
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $hc_id = (int)filter_input(INPUT_POST, 'hc');
            $hc = HeadCenter::getByID($hc_id);
//            $statest = array();
            foreach (Act::getInnerStates() as $st) {
                $statest[] = "'".$st."'";
            }
            $statest = implode(', ', $statest);
            $sql = "select
concat(sap.surname_rus,' ',sap.name_rus) as fio_rus,
concat(sap.surname_lat,' ',sap.name_lat) as fio_lat,
concat(sap.passport_series,' ',sap.passport) as passport,
c.name as country,
stl.caption,
date_format(sap.testing_date,'%d.%m.%Y') as test_date,
if
(sap.blank_date <> '0000-00-00',
date_format(sap.blank_date,'%d.%m.%Y'),
null) as blank_date,
sap.blank_number,
sap.document_nomer,
if(sap.document = 'certificate','Сертификат', 'Справка') as document,

GROUP_CONCAT(cd.certificate_number) as duplicate_number

from sdt_act_people sap
left join country c on c.id = sap.country_id
left join sdt_act sa on sa.id = sap.act_id
left join sdt_university su on su.id = sa.university_id
left join sdt_act_test sat on sat.id = sap.test_id
left join sdt_test_levels stl on stl.id = sat.level_id
left join certificate_duplicate cd on cd.user_id = sap.id AND cd.deleted = 0
where su.head_id = $hc_id
and sa.state IN (".$statest.")
and sap.deleted = 0
and sa.deleted = 0
and
(length(sap.blank_number) > 1)
and sap.testing_date >= '".$connection->escape($this->mysql_date($from))." 0:0:0'
and sap.testing_date <= '".$connection->escape($this->mysql_date($to))." 23:59:59'



group by sap.id
order by sap.testing_date";
//die($sql);
            $result = $connection->query($sql);
        }
        $hc_list = HeadCenters::getAll();

        return $this->render->view(
            'root/statist/people_gc',
            array(
                'result' => $result,
                'search' => $search,
                'from' => $from,
                'to' => $to,
                'hc_list' => $hc_list,
                'caption' => 'Список протестированных по ГЦ',
            )
        );
    }

    /**
     * Отчет 16
     * @return string
     */
    public function report_lc_list_action()
    {
        $type = filter_input(INPUT_GET, 'type', FILTER_VALIDATE_INT);
        $name = null;
        $result = null;
        if ($type) {
            switch ($type) {
                case 1:
                    $name = 'Локальные центры по всем ГЦ комплексный экзамен';
                    $sql = 'SELECT  su.name, shc.short_name FROM sdt_university su
INNER JOIN sdt_head_center shc ON su.head_id = shc.id
WHERE su.deleted=0
AND su.id IN (SELECT  sa.university_id  FROM sdt_act sa WHERE sa.test_level_type_id = 2)
';
                    break;
                case 2:
                    $name = 'Локальные центры по всем ГЦ РКИ';
                    $sql = 'SELECT  su.name, shc.short_name FROM sdt_university su
INNER JOIN sdt_head_center shc ON su.head_id = shc.id
WHERE su.deleted=0
AND su.id IN (SELECT  sa.university_id  FROM sdt_act sa WHERE sa.test_level_type_id = 1)
';
                    break;
                case 3:
                    $name = 'Локальные центры по  РУДН комплексный экзамен';
                    $sql = 'SELECT  su.name, shc.short_name FROM sdt_university su
INNER JOIN sdt_head_center shc ON su.head_id = shc.id
WHERE su.deleted=0  AND shc.horg_id = 1
AND su.id IN (SELECT  sa.university_id  FROM sdt_act sa WHERE sa.test_level_type_id = 2)
';
                    break;
                case 4:
                    $name = 'Локальные центры по  РУДН РКИ';
                    $sql = 'SELECT  su.name, shc.short_name FROM sdt_university su
INNER JOIN sdt_head_center shc ON su.head_id = shc.id
WHERE su.deleted=0 AND shc.horg_id = 1
AND su.id IN (SELECT  sa.university_id  FROM sdt_act sa WHERE sa.test_level_type_id = 1)
';
                    break;
            }
            $con = Connection::getInstance();
            if (!empty($sql)) {
//                die($sql);
                $result = $con->query($sql);
            }
        }

        return $this->render->view(
            'root/statist/lс_list',
            array(
                'name' => $name,
                'result' => $result,
                'caption' => 'Список локальных центров',
            )
        );
    }


    public function federal_dc_regions_action()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (empty($id)) {
            $this->redirectAccessRestricted();
        }
        if (count($_POST) && !empty($_POST['DC'])) {
            FederalDC::saveRegions($_POST);
        }
        $sqlResult = FederalDC::getRegionsByID($id);

        return $this->render->view(
            'root/federal_dc_regions',
            array(
                'result' => $sqlResult,
                'id' => $id,
                'caption' => FederalDC::getByID($id)->full_caption.' - список регионов',
            )
        );
    }

    public function federal_dc_regions_all_action()
    {
        return $this->render->view(
            'root/federal_dc_regions_all',
            array(
                'result' => FederalDCs::getRegionsByAllDC(),
                //                'id' => $id,
                'caption' => 'список регионов по округам',
            )
        );
    }

    public function federal_dc_action()
    {
        return $this->render->view(
            'root/federal_dc',
            array(
                'result' => FederalDCs::getAll(),
                //'id' => $id,
                'caption' => 'Федеральные округа',
            )
        );
    }

    public function federal_districts_region_centers_report_action()
    {
        $result_array = array();
        if (count($_POST) && !empty($_POST['filter'])) {
            if (!empty($_POST['regions'])) {
                $regions = ' and rs.id in ('.implode(',', $_POST['regions']).') ';
            } else {
                $regions = '';
            }
            if (!empty($_POST['head_centers'])) {
                $hcs = ' and hc.id in ('.implode(',', $_POST['head_centers']).') ';
            } else {
                $hcs = '';
            }
            if (!empty($_POST['districts'])) {
                $dcs = ' and fd.id in ('.implode(',', $_POST['districts']).') ';
            } else {
                $dcs = '';
            }
            $sql = 'SELECT
            fd.id f_id, fd.caption f_cap, rs.id r_id, rs.caption r_cap,
            su.id u_id,su.short_name u_sname,su.name u_name, hc.short_name AS h_name
             FROM sdt_university su
            LEFT JOIN sdt_head_center hc ON hc.id=su.head_id
            LEFT JOIN regions rs ON su.region_id=rs.id
            LEFT JOIN federal_dc_region fdr ON fdr.region_id=rs.id
            LEFT JOIN federal_dc fd ON fd.id=fdr.dc_id
            WHERE su.region_id > 0
            AND hc.deleted = 0 AND su.head_id > 0
            AND su.deleted = 0
            '.$regions.$hcs.$dcs.'
            ORDER BY fd.id ASC, rs.id ASC, su.id ASC
            ';
            $con = Connection::getInstance();
            if (!empty($sql)) {
                $result = $con->query($sql);
            }
            if (empty($result)) {
                $result = array();
            }
            foreach ($result as $res) {
                $result_array[$res['f_id']][$res['r_id']][$res['u_id']] = array(
                    'f_cap' => $res['f_cap'],
                    'r_cap' => $res['r_cap'],
                    'u_sname' => $res['u_sname'],
                    'u_name' => $res['u_name'],
                    'h_name' => $res['h_name'],
                );
            }
        }

        return $this->render->view(
            'root/statist/federal_districts_region_centers_report',
            array(
                'result' => $result_array,
                'caption' => 'Количество локальных центров в системе. Разбивка по округам/регионам',
            )
        );
    }

    public function federal_districts_region_citizenry_rki_report_action()
    {
        return $this->federal_districts_region_citizenry_report_action(1);
    }

    public function federal_districts_region_citizenry_report_action($test_level_type = 1)
    {
        $result_array = array();
        $results = array();
        $regions_for_title = $in_dates = '';
        $from = '1.01.2015';
//        $from = '1.09.2017';
        $to = date('d.m.Y');
        $headcenters = array();
        $allied_rudn = false;
        if (count($_POST) && !empty($_POST['filter'])) {
            if (!empty($_POST['regions'])) {
                if (in_array('no_region', $_POST['regions'])) {
                    $no_region_in_rs = ' or (rs.id is null or rs.id = 0) ';
                    $no_region_in_fd = ' or (fd.id is null or fd.id = 0) ';
                } else {
                    $no_region_in_rs = $no_region_in_fd = '';
                }
                $regions_for_title = array();
                $regs = $_POST['regions'];
                foreach ($regs as $k => $r) {
                    if ($r == 'no_region') {
                        $regions_for_title[] = 'Без проставленного региона';
                        unset ($regs[$k]);
                    } else {
                        $regions_for_title[] = Region::getByID($r)->caption;
                    }
                }
                $regs[] = '-1';
                $regions = ' and (rs.id in ('.implode(',', $regs).') '.$no_region_in_rs.' )';
                if (!empty($regions_for_title)) {
                    $regions_for_title = 'Регионы: '.implode(', ', $regions_for_title);
                }
            } else {
                $regions = '';
            }
            if (!empty($_POST['head_centers'])) {
                $headcenters = $_POST['head_centers'];
                foreach ($headcenters as $key => $val) {
                    if ($val == 'allied_rudn') {
                        unset ($headcenters[$key]);
                        $allied_rudn = true;
                    }
                }
                $hcs = ' and hc.id in ('.implode(',', $headcenters).') ';
            } else {
                $hcs = '';
            }
            if (!empty($_POST['districts'])) {
                $dcs = ' and (fd.id in ('.implode(
                        ',',
                        array_merge([-1], (array)$_POST['districts'])
                    ).') '.$no_region_in_fd.' )';
            } else {
                $dcs = '';
            }
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $con = Connection::getInstance();
            $sql = $sql_rudn = '
            SELECT
 %s rs.id AS r_id, co.name, COUNT(DISTINCT sap.id) AS people
FROM sdt_act_people sap
LEFT JOIN sdt_act sa ON	sap.act_id = sa.id
LEFT JOIN sdt_university su ON sa.university_id = su.id
LEFT JOIN sdt_head_center hc ON hc.id=su.head_id
LEFT JOIN regions rs ON su.region_id=rs.id
LEFT JOIN federal_dc_region fdr ON fdr.region_id=rs.id
LEFT JOIN federal_dc fd ON fd.id=fdr.dc_id
LEFT JOIN country co ON co.id = sap.country_id
WHERE hc.deleted = 0 
AND sap.deleted = 0 AND sa.deleted = 0
AND sa.test_level_type_id = '.$test_level_type.'
AND sa.created >= \''.$con->escape($this->mysql_date($from)).' 0:0:0\' AND sa.created <=  \''.$con->escape(
                    $this->mysql_date($to)
                ).' 23:59:59\'
AND sa.state IN (\'received\',\'check\',\'print\',\'wait_payment\',\'archive\')
 '.$regions.$dcs.' %s
GROUP BY rs.id, %s co.id
ORDER BY hc.horg_id, rs.id, %s co.name ASC
            ';
            $sql = sprintf($sql, 'hc.id as hc_id,', $hcs, 'hc.id,', 'hc.id asc,');
            if ($allied_rudn) {
                $sql_rudn = sprintf($sql_rudn, '0 as hc_id,', ' and hc.horg_id=1 ', '', '');
            } else {
                $sql_rudn = '';
            }
//die($sql_rudn);
            if (!empty($sql_rudn)) {
                $results[] = $con->query($sql_rudn);
            }
            if (!empty($sql) && !empty($headcenters)) {
                $results[] = $con->query($sql);
            }
            foreach ($results as $result) {
                if (empty($result)) {
                    $result = array();
                }
                foreach ($result as $res) {
                    $result_array[$res['hc_id']][$res['r_id']][] = array(
                        'country' => $res['name'],
                        'people' => $res['people'],
                    );
                }
            }
            $in_dates = ' в период с '.$from.' по '.$to;
        }
        if ($test_level_type == 1) {
            $type = "РКИ";
        }
        if ($test_level_type == 2) {
            $type = "экзамену";
        }

        return $this->render->view(
            'root/statist/federal_districts_region_citizenry_report',
            array(
                'result' => $result_array,
                'caption' => 'Количество протестированных по '.$type.$in_dates.'. Разбивка по гражданству',
                'regions_for_title' => $regions_for_title,
                'from' => $from,
                'to' => $to,
                'headcenters' => $headcenters,
            )
        );
    }

    public function federal_districts_region_citizenry_exam_report_action()
    {
        return $this->federal_districts_region_citizenry_report_action(2);
    }

    /**
     * Отчет 21
     * @return string
     */
    public function head_centers_region_centers_report_action()
    {
        $result_array = array();
        if (count($_POST) && !empty($_POST['filter'])) {
            if (!empty($_POST['regions'])) {
                $regions = ' and rs.id in ('.implode(',', $_POST['regions']).') ';
            } else {
                $regions = '';
            }
            if (!empty($_POST['head_centers'])) {
                $hcs = ' and hc.id in ('.implode(',', $_POST['head_centers']).') ';
            } else {
                $hcs = '';
            }
            if (!empty($_POST['districts'])) {
                $dcs = ' and fd.id in ('.implode(',', $_POST['districts']).') ';
            } else {
                $dcs = '';
            }
            $sql = 'SELECT
                    hc.id h_id, hc.short_name AS h_name,
                    rs.id r_id, rs.caption r_cap,
                    su.id u_id,su.short_name u_sname,su.name u_name
                    FROM sdt_university su
                    LEFT JOIN sdt_head_center hc ON hc.id=su.head_id
                    LEFT JOIN regions rs ON su.region_id=rs.id
                    LEFT JOIN federal_dc_region fdr ON fdr.region_id=rs.id
                    LEFT JOIN federal_dc fd ON fd.id=fdr.dc_id
                    WHERE su.region_id > 0
                    AND hc.deleted = 0 AND su.head_id > 0
                    AND su.deleted = 0
                    '.$regions.$hcs.$dcs.'
                    ORDER BY hc.id ASC, rs.id ASC, su.id ASC
                    ';
            $con = Connection::getInstance();
            if (!empty($sql)) {
                $result = $con->query($sql);
            }
            if (empty($result)) {
                $result = array();
            }
            $result_array = array();
            foreach ($result as $res) {
                $result_array[$res['h_id']][$res['r_id']][$res['u_id']] = array(
                    'h_name' => $res['h_name'],
                    'r_cap' => $res['r_cap'],
                    'u_sname' => $res['u_sname'],
                    'u_name' => $res['u_name'],
                    //                    'h_name'=>$res['h_name']
                );
            }
        }

        return $this->render->view(
            'root/statist/head_centers_region_centers_report',
            array(
                'result' => $result_array,
                'caption' => 'Количество локальных центров в системе. Разбивка по головным центрам',
            )
        );
    }

    /**
     * @return string
     * Отчет 22 - вызов
     */
    public function federal_districts_region_rki_cert_notes_report_action()
    {
        return $this->federal_districts_region_cert_notes_report(1);
    }

    /**
     * @return string
     * Отчет 22 и 23 - логика
     */
    public function federal_districts_region_cert_notes_report($test_level_type = 1)
    {
        $result = array();
        $from = '1.01.2015';
        $to = date('d.m.Y');
        if (count($_POST) && !empty($_POST['filter'])) {
            if (!empty($_POST['regions'])) {
                $regions = ' and rs.id in ('.implode(',', $_POST['regions']).') ';
            } else {
                $regions = '';
            }
            if (!empty($_POST['head_centers'])) {
                $hcs = ' and su.head_id in ('.implode(',', $_POST['head_centers']).') ';
            } else {
                $hcs = '';
            }
            if (!empty($_POST['districts'])) {
                $dcs = ' and fd.id in ('.implode(',', $_POST['districts']).') ';
            } else {
                $dcs = '';
            }
            if (!empty($_POST['test_levels'])) {
                $tls = ' and tlg.group_id in ('.implode(',', $_POST['test_levels']).') ';
            } else {
                $tls = '';
            }
            /**/
            if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
                $from = filter_input(INPUT_POST, 'from');
                $to = filter_input(INPUT_POST, 'to');
                $connection = Connection::getInstance();
                $dates = " and sa.created BETWEEN '".$connection->escape(
                        $this->mysql_date($from)
                    )." 0:0:0' and '".$connection->escape(
                        $this->mysql_date($to)
                    )." 23:59:59'";
            } else {
                $dates = '';
            }
            $sql = 'SELECT
fd.id f_id, fd.caption f_cap,
rs.id r_id, rs.caption r_cap,
stl.id tl_id, stl.caption tl_cap,
sum(if(sap.document="note", 1,0)) AS notes,
  sum(if(sap.document="certificate" AND sap.blank_number IS NOT NULL AND length(sap.blank_number)>0 , 1,0)) AS certs

FROM
sdt_university su
LEFT JOIN regions rs ON rs.id=su.region_id
LEFT JOIN sdt_act sa ON su.id = sa.university_id
LEFT JOIN sdt_act_test sat ON sat.act_id=sa.id
LEFT JOIN sdt_act_people sap ON sat.id=sap.test_id
LEFT JOIN sdt_test_levels stl ON stl.id=sat.level_id
LEFT JOIN federal_dc_region fdr ON fdr.region_id=rs.id
LEFT JOIN federal_dc fd ON fd.id=fdr.dc_id
LEFT JOIN test_level_groups AS tlg ON tlg.level_id=stl.id

WHERE
sap.deleted =0 AND sa.deleted = 0
AND su.deleted = 0 AND stl.deleted = 0
AND stl.deleted = 0 AND sat.deleted = 0
AND su.region_id > 0 AND su.head_id > 0
AND stl.type_id='.$test_level_type.'

'.$regions.$hcs.$dcs.$tls.'
'.$dates.'



GROUP BY fd.id,
			rs.id,
         tlg.group_id

ORDER BY fd.id ASC, rs.id ASC, stl.id ASC
';
//            die($sql);
//        and fd.id=9
            $con = Connection::getInstance();
            if (!empty($sql)) {
                $result = $con->query($sql);
            }
            if (empty($result)) {
                $result = array();
            }
        }
        if ($test_level_type == 1) {
            $caption_suffix = ' (РКИ)';
        } else {
            $caption_suffix = ' (Экзамен)';
        }

        return $this->render->view(
            'root/statist/federal_districts_region_cert_notes_report',
            array(
                'test_level_type' => $test_level_type,
                'from' => $from,
                'to' => $to,
                'result' => $result,
                'caption' => 'Количество протестировавшихся с разбивкой по округам/регионам'.$caption_suffix,
            )
        );
    }

    /**
     * @return string
     * Отчет 23 - вызов
     */
    public function federal_districts_region_exam_cert_notes_report_action()
    {
        return $this->federal_districts_region_cert_notes_report(2);
    }

    /**
     * @return string
     * Отчет 24 - вызов
     */
    public function hc_universities_rki_cert_notes_report_action()
    {
        return $this->hc_universities_cert_notes_report(1);
    }

    /**
     * @return string
     * Отчет 24-25 - логика
     */
    public function hc_universities_cert_notes_report($test_level_type = 1)
    {
        $result = array();
        $from = '1.01.2015';
        $to = date('d.m.Y');
        if (count($_POST) && !empty($_POST['filter'])) {
            /*if (!empty($_POST['regions']))
                $regions = ' and rs.id in ('.implode(',',$_POST['regions']).') ';
            else $regions = '';
*/
            if (!empty($_POST['head_centers'])) {
                $hcs = ' and su.head_id in ('.implode(',', $_POST['head_centers']).') ';
            } else {
                $hcs = '';
            }
            /*
                        if (!empty($_POST['districts']))
                            $dcs = ' and fd.id in ('.implode(',',$_POST['districts']).') ';
                        else $dcs = '';
            */
            if (!empty($_POST['test_levels'])) {
                $tls = ' and tlg.group_id in ('.implode(',', $_POST['test_levels']).') ';
            } else {
                $tls = '';
            }
            /**/
            if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
                $from = filter_input(INPUT_POST, 'from');
                $to = filter_input(INPUT_POST, 'to');
                $connection = Connection::getInstance();
                $dates = " and sa.created BETWEEN '".$connection->escape(
                        $this->mysql_date($from)
                    )." 0:0:0' and '".$connection->escape(
                        $this->mysql_date($to)
                    )." 23:59:59'";
            } else {
                $dates = '';
            }
            $sql = 'SELECT
hc.id h_id, hc.short_name AS h_name,
su.id u_id,su.short_name u_sname,su.name u_name,

stl.id tl_id, stl.caption tl_cap,
 /*count(distinct sap.id) as sdalo,*/
 sum(if(sap.document="note", 1,0)) AS notes,
  sum(if(sap.document="certificate" AND sap.blank_number IS NOT NULL AND length(sap.blank_number)>0 , 1,0)) AS certs


FROM
sdt_university su
LEFT JOIN sdt_head_center hc ON hc.id=su.head_id
LEFT JOIN sdt_act sa ON su.id = sa.university_id
LEFT JOIN sdt_act_test sat ON sat.act_id=sa.id
LEFT JOIN sdt_act_people sap ON sat.id=sap.test_id
LEFT JOIN sdt_test_levels stl ON stl.id=sat.level_id
LEFT JOIN test_level_groups AS tlg ON tlg.level_id=stl.id

WHERE
sap.deleted =0 AND sa.deleted = 0
AND su.deleted = 0 AND stl.deleted = 0
AND stl.deleted = 0 AND sat.deleted = 0
AND su.head_id > 0
AND hc.deleted = 0 AND su.head_id > 0
AND stl.type_id='.$test_level_type.'

'.$hcs.$tls.'
'.$dates.'

GROUP BY hc.id,
			su.id,
         tlg.group_id

ORDER BY hc.id ASC, su.id ASC, stl.id ASC
';
//and hc.id = 1
            $con = Connection::getInstance();
            if (!empty($sql)) {
                $result = $con->query($sql);
            }
        }
        if (empty($result)) {
            $result = array();
        }
        if ($test_level_type == 1) {
            $caption_suffix = ' (РКИ)';
        } else {
            $caption_suffix = ' (Экзамен)';
        }

        return $this->render->view(
            'root/statist/hc_universities_cert_notes_report',
            array(
                'test_level_type' => $test_level_type,
                'from' => $from,
                'to' => $to,
                'result' => $result,
                'caption' => 'Количество протестировавшихся. Общее'.$caption_suffix,
            )
        );
    }

    /**
     * @return string
     * Отчет 25 - вызов
     */
    public function hc_universities_exam_cert_notes_report_action()
    {
        return $this->hc_universities_cert_notes_report(2);
    }

    /**
     * @return string
     * Отчет 28
     */
    public function ministr_statist_action()
    {
        set_time_limit(0);
//        $date = new DateTime();
        $begin = filter_input(INPUT_GET, 'begin');
        $end = filter_input(INPUT_GET, 'end');
        if (!($begin) || !($end)) {
            return $this->render->view(
                'root/statist/ministr',
                array(
                    'data' => [],
                    'end' => new DateTime(),
                    'begin' => new DateTime('1.1.2015'),
//            'test_level_type' => $test_level_type,
//            'from' => $from,
//            'to' => $to,
//            'result' => $result,
//            'caption' => 'Количество протестировавшихся. Общее'.$caption_suffix,
                )
            );
        }
        $begin = new DateTime($begin);
        $end = new DateTime($end);
//        $date = new DateTime(filter_input(INPUT_GET, 'date'));
        $data_template = [
            'total' => [
                'pass' => 0,
                'retry_1' => 0,
                'retry_2' => 0,
            ],
            'simple' => [
                'pass' => 0,
                'retry_1' => 0,
                'retry_2' => 0,
            ],
            'dnr_total' => [
                'pass' => 0,
                'retry_1' => 0,
                'retry_2' => 0,
            ],
            'dnr_simple' => [
                'pass' => 0,
                'retry_1' => 0,
                'retry_2' => 0,
            ],
            'ukr_simple' => [
                'pass' => 0,
                'retry_1' => 0,
                'retry_2' => 0,
            ],
        ];
        $data = array(
            'pfur' => [
                0 => $data_template,
                1 => $data_template,
                2 => $data_template,
            ],
            'local' => [
                0 => $data_template,
                1 => $data_template,
                2 => $data_template,
            ],
        );
        $c = Connection::getInstance();
        // PFUR ALL
        $sql = 'SELECT
sum(if(sap.is_retry=0,1,0))AS total,
sum(if(sap.is_retry = 1,1,0)) AS retry_1,
sum(if(sap.is_retry > 0,1,0)) AS retry_0,
sum(if(sap.is_retry= 2,1,0)) AS retry_2,
 stl.caption,
 stl.id AS test_id,
stl.print,
   sho.captoin

     FROM sdt_act_people sap

INNER JOIN sdt_act_test sat ON sat.id = sap.test_id
INNER JOIN sdt_act sa ON sa.id = sat.act_id
INNER JOIN sdt_test_levels stl ON stl.id = sat.level_id



INNER JOIN sdt_university su ON su.id = sa.university_id

INNER JOIN sdt_head_center shc ON shc.id = su.head_id
LEFT JOIN sdt_head_org sho ON sho.id = shc.horg_id
WHERE
length(sap.blank_number) > 1
AND su.id  IN (166,167,298,307)
AND sa.deleted = 0
AND sap.deleted = 0
AND sa.state IN (\'received\',\'check\',\'print\',\'wait_payment\',\'archive\')
AND sap.document = \'certificate\'
AND sa.created <= \''.$end->format('Y-m-d').' 23:59:59\'
AND sa.created >= \''.$begin->format('Y-m-d').' 0:0:0\'
AND sa.test_level_type_id = 2

AND sho.id = 1

GROUP BY  stl.id';
//die($sql);
        $result = $c->query($sql);
        foreach ($result as $row) {
            $group = $this->getExamGroup($row['test_id']);
            $type = 'total';
            if (!$this->isFullExam($row['test_id'])) {
                $type = 'simple';
            }
            $data['pfur'][$group][$type]['pass'] += $row['total'];
            $data['pfur'][$group][$type]['retry_1'] += $row['retry_1'];
            $data['pfur'][$group][$type]['retry_2'] += $row['retry_2'];
        }
        //PFUR DNR
        $sql = 'SELECT
sum(if(sap.is_retry=0,1,0))AS total,
sum(if(sap.is_retry = 1,1,0)) AS retry_1,
sum(if(sap.is_retry= 2,1,0)) AS retry_2,
 stl.caption,
stl.print,
 stl.id AS test_id,
   sho.captoin

     FROM sdt_act_people sap

INNER JOIN sdt_act_test sat ON sat.id = sap.test_id
INNER JOIN sdt_act sa ON sa.id = sat.act_id
INNER JOIN sdt_test_levels stl ON stl.id = sat.level_id
LEFT JOIN act_metadata am ON am.act_id = sa.id


INNER JOIN sdt_university su ON su.id = sa.university_id

INNER JOIN sdt_head_center shc ON shc.id = su.head_id
LEFT JOIN sdt_head_org sho ON sho.id = shc.horg_id
WHERE
length(sap.blank_number) > 1
AND su.id  IN (166,167,298,307)
AND sa.deleted = 0
AND sap.deleted = 0
AND sa.state IN (\'received\',\'check\',\'print\',\'wait_payment\',\'archive\')
AND sap.document = \'certificate\'
AND sa.created <= \''.$end->format('Y-m-d').' 23:59:59\'
AND sa.created >= \''.$begin->format('Y-m-d').' 0:0:0\'
AND sa.test_level_type_id = 2
AND am.special_price_group = 1
AND sho.id = 1

GROUP BY  stl.id';
//die($sql);
        $result = $c->query($sql);
        foreach ($result as $row) {
            $group = $this->getExamGroup($row['test_id']);
            $type = 'dnr_total';
            if (!$this->isFullExam($row['test_id'])) {
                $type = 'dnr_simple';
            }
            $data['pfur'][$group][$type]['pass'] += $row['total'];
            $data['pfur'][$group][$type]['retry_1'] += $row['retry_1'];
            $data['pfur'][$group][$type]['retry_2'] += $row['retry_2'];
        }
        //PFUR UKR
        $sql = '
SELECT
sum(if(sap.is_retry=0,1,0))AS total,

sum(if(sap.is_retry = 1,1,0)) AS retry_1,
sum(if(sap.is_retry= 2,1,0)) AS retry_2,
 stl.caption,
stl.print,
 stl.id AS test_id,
   sho.captoin,sap.country_id

     FROM sdt_act_people sap

INNER JOIN sdt_act_test sat ON sat.id = sap.test_id
INNER JOIN sdt_act sa ON sa.id = sat.act_id
INNER JOIN sdt_test_levels stl ON stl.id = sat.level_id



INNER JOIN sdt_university su ON su.id = sa.university_id

INNER JOIN sdt_head_center shc ON shc.id = su.head_id
LEFT JOIN sdt_head_org sho ON sho.id = shc.horg_id
WHERE
length(sap.blank_number) > 1
AND su.id   IN (166,167,298,307)
AND sa.deleted = 0
AND sap.deleted = 0
AND sa.state IN (\'received\',\'check\',\'print\',\'wait_payment\',\'archive\')
AND sap.document = \'certificate\'
AND sa.created <= \''.$end->format('Y-m-d').' 23:59:59\'
AND sa.created >= \''.$begin->format('Y-m-d').' 0:0:0\'
AND sa.test_level_type_id = 2
AND sap.country_id = 171
AND sho.id = 1

GROUP BY  stl.id';
//die($sql);
        $result = $c->query($sql);
        foreach ($result as $row) {
            $group = $this->getExamGroup($row['test_id']);
            if (!$this->isFullExam($row['test_id'])) {
                $type = 'ukr_simple';
                $data['pfur'][$group][$type]['pass'] += $row['total'];
                $data['pfur'][$group][$type]['retry_1'] += $row['retry_1'];
                $data['pfur'][$group][$type]['retry_2'] += $row['retry_2'];
            }
        }
//------------------------------------------------------------
        // LOCAL
        // local ALL
        $sql = 'SELECT
sum(if(sap.is_retry=0,1,0))AS total,
sum(if(sap.is_retry = 1,1,0)) AS retry_1,
sum(if(sap.is_retry > 0,1,0)) AS retry_0,
sum(if(sap.is_retry= 2,1,0)) AS retry_2,
 stl.caption,
 stl.id AS test_id,
stl.print,
   sho.captoin

     FROM sdt_act_people sap

INNER JOIN sdt_act_test sat ON sat.id = sap.test_id
INNER JOIN sdt_act sa ON sa.id = sat.act_id
INNER JOIN sdt_test_levels stl ON stl.id = sat.level_id



INNER JOIN sdt_university su ON su.id = sa.university_id

INNER JOIN sdt_head_center shc ON shc.id = su.head_id
LEFT JOIN sdt_head_org sho ON sho.id = shc.horg_id
WHERE
length(sap.blank_number) > 1
AND su.id NOT IN (166,167,298,307)
AND sa.deleted = 0
AND sap.deleted = 0
AND sa.state IN (\'received\',\'check\',\'print\',\'wait_payment\',\'archive\')
AND sap.document = \'certificate\'
AND sa.created <= \''.$end->format('Y-m-d').' 23:59:59\'
AND sa.created >= \''.$begin->format('Y-m-d').' 0:0:0\'
AND sa.test_level_type_id = 2

AND sho.id = 1

GROUP BY  stl.id';
//die($sql);
        $result = $c->query($sql);
        foreach ($result as $row) {
            $group = $this->getExamGroup($row['test_id']);
            $type = 'total';
            if (!$this->isFullExam($row['test_id'])) {
                $type = 'simple';
            }
            $data['local'][$group][$type]['pass'] += $row['total'];
            $data['local'][$group][$type]['retry_1'] += $row['retry_1'];
            $data['local'][$group][$type]['retry_2'] += $row['retry_2'];
        }
        //local DNR
        $sql = 'SELECT
sum(if(sap.is_retry=0,1,0))AS total,
sum(if(sap.is_retry = 1,1,0)) AS retry_1,
sum(if(sap.is_retry= 2,1,0)) AS retry_2,
 stl.caption,
stl.print,
 stl.id AS test_id,
   sho.captoin

     FROM sdt_act_people sap

INNER JOIN sdt_act_test sat ON sat.id = sap.test_id
INNER JOIN sdt_act sa ON sa.id = sat.act_id
INNER JOIN sdt_test_levels stl ON stl.id = sat.level_id
LEFT JOIN act_metadata am ON am.act_id = sa.id


INNER JOIN sdt_university su ON su.id = sa.university_id

INNER JOIN sdt_head_center shc ON shc.id = su.head_id
LEFT JOIN sdt_head_org sho ON sho.id = shc.horg_id
WHERE
length(sap.blank_number) > 1
AND su.id NOT  IN (166,167,298,307)
AND sa.deleted = 0
AND sap.deleted = 0
AND sa.state IN (\'received\',\'check\',\'print\',\'wait_payment\',\'archive\')
AND sap.document = \'certificate\'
AND sa.created <= \''.$end->format('Y-m-d').' 23:59:59\'
AND sa.created >= \''.$begin->format('Y-m-d').' 0:0:0\'
AND sa.test_level_type_id = 2
AND am.special_price_group = 1
AND sho.id = 1

GROUP BY  stl.id';
//die($sql);
        $result = $c->query($sql);
        foreach ($result as $row) {
            $group = $this->getExamGroup($row['test_id']);
            $type = 'dnr_total';
            if (!$this->isFullExam($row['test_id'])) {
                $type = 'dnr_simple';
            }
            $data['local'][$group][$type]['pass'] += $row['total'];
            $data['local'][$group][$type]['retry_1'] += $row['retry_1'];
            $data['local'][$group][$type]['retry_2'] += $row['retry_2'];
        }
        //local UKR
        $sql = '
SELECT
sum(if(sap.is_retry=0,1,0))AS total,

sum(if(sap.is_retry = 1,1,0)) AS retry_1,
sum(if(sap.is_retry= 2,1,0)) AS retry_2,
 stl.caption,
stl.print,
 stl.id AS test_id,
   sho.captoin,sap.country_id

     FROM sdt_act_people sap

INNER JOIN sdt_act_test sat ON sat.id = sap.test_id
INNER JOIN sdt_act sa ON sa.id = sat.act_id
INNER JOIN sdt_test_levels stl ON stl.id = sat.level_id



INNER JOIN sdt_university su ON su.id = sa.university_id

INNER JOIN sdt_head_center shc ON shc.id = su.head_id
LEFT JOIN sdt_head_org sho ON sho.id = shc.horg_id
WHERE
length(sap.blank_number) > 1
AND su.id  NOT IN (166,167,298,307)
AND sa.deleted = 0
AND sap.deleted = 0
AND sa.state IN (\'received\',\'check\',\'print\',\'wait_payment\',\'archive\')
AND sap.document = \'certificate\'
AND sa.created <= \''.$end->format('Y-m-d').' 23:59:59\'
AND sa.created >= \''.$begin->format('Y-m-d').' 0:0:0\'
AND sa.test_level_type_id = 2
AND sap.country_id = 171
AND sho.id = 1

GROUP BY  stl.id';
//die($sql);
        $result = $c->query($sql);
        foreach ($result as $row) {
            $group = $this->getExamGroup($row['test_id']);
            if (!$this->isFullExam($row['test_id'])) {
                $type = 'ukr_simple';
                $data['local'][$group][$type]['pass'] += $row['total'];
                $data['local'][$group][$type]['retry_1'] += $row['retry_1'];
                $data['local'][$group][$type]['retry_2'] += $row['retry_2'];
            }
        }

        return $this->render->view(
            'root/statist/ministr',
            array(
                'data' => $data,
                'begin' => $begin,
                'end' => $end,
//            'test_level_type' => $test_level_type,
//            'from' => $from,
//            'to' => $to,
//            'result' => $result,
//            'caption' => 'Количество протестировавшихся. Общее'.$caption_suffix,
            )
        );
    }

    private function getExamGroup($level)
    {
        /*$levels = array(
            13 => 0,
            16 => 0,
            19 => 0,
            14 => 1,
            17 => 1,
            20 => 1,
            15 => 2,
            18 => 2,
            21 => 2,
            22 => 2,
            23 => 2,
            24 => 2,
            25 => 2,
            26 => 2,
            27 => 2,
            28 => 2,
            29 => 2,
        );*/
        $levels = ExamHelper::getLevelGroups();

        return $levels[$level];
    }

    private function isFullExam($level)
    {
        /*$levels = array(
            13,
            14,
            15,
            19, 20, 21, 22, 24, 25, 27,
            28, 29
        );*/
        $levels = ExamHelper::getFullExamlevels();

        return in_array($level, $levels);
    }

    public function ministr_statist_rki_action()
    {
        set_time_limit(0);
//        $date = new DateTime();
        $begin = filter_input(INPUT_GET, 'begin');
        $end = filter_input(INPUT_GET, 'end');
        if (!($begin) || !($end)) {
            return $this->render->view(
                'root/statist/ministr_rki',
                array(
                    'data' => [],
                    'end' => new DateTime(),
                    'begin' => new DateTime('1.1.2015'),
//            'test_level_type' => $test_level_type,
//            'from' => $from,
//            'to' => $to,
//            'result' => $result,
//            'caption' => 'Количество протестировавшихся. Общее'.$caption_suffix,
                )
            );
        }
//        $date = new DateTime(filter_input(INPUT_GET, 'date'));
        $begin = new DateTime($begin);
        $end = new DateTime($end);
        $levels = array(
            1 => [2], //ТРКИ "Элементарный" А1
            2 => [3], // ТРКИ "Базовый" А2
            3 => [5], // ТРКИ "Первый" В1
            4 => [6], // ТРКИ "Второй" В2
            5 => [7],  // ТРКИ "Третий" С1
            6 => [8], // ТРКИ "Четвертый" С2
            7 => [11, 12, 9, 10], //Гражданство
        );
        $keyLevel = [];
        foreach ($levels as $key => $val) {
            foreach ($val as $l) {
                $keyLevel[$l] = $key;
            }
        }
        $data_template = [];
        foreach ($levels as $key => $val) {
            $data_template[$key] = [
                'pass' => 0,
                'retry' => 0,
            ];
        }
        $data = array(
            'pfur' => $data_template,
            'local' => $data_template,
        );
        $c = Connection::getInstance();
        // PFUR ALL
        $sql = 'SELECT
sum(if(sap.is_retry=0,1,0))AS total,

sum(if(sap.is_retry > 0,1,0)) AS retry,

 stl.caption,
 stl.id AS test_id,
stl.print,
   sho.captoin

     FROM sdt_act_people sap

INNER JOIN sdt_act_test sat ON sat.id = sap.test_id
INNER JOIN sdt_act sa ON sa.id = sat.act_id
INNER JOIN sdt_test_levels stl ON stl.id = sat.level_id



INNER JOIN sdt_university su ON su.id = sa.university_id

INNER JOIN sdt_head_center shc ON shc.id = su.head_id
LEFT JOIN sdt_head_org sho ON sho.id = shc.horg_id
WHERE
length(sap.blank_number) > 1
AND su.id  IN (166,167,298,307)
AND sa.deleted = 0
AND sap.deleted = 0
AND sa.state IN (\'received\',\'check\',\'print\',\'wait_payment\',\'archive\')
AND sap.document = \'certificate\'
AND sa.created <= \''.$end->format('Y-m-d').' 23:59:59\'
AND sa.created >= \''.$begin->format('Y-m-d').' 0:0:0\'
AND sa.test_level_type_id = 1

AND sho.id = 1

GROUP BY  stl.id';
//die($sql);
        $result = $c->query($sql);
        foreach ($result as $row) {
            if (!array_key_exists($row['test_id'], $keyLevel)) {
                continue;
            }
            $levelKey = $keyLevel[$row['test_id']];
            $data['pfur'][$levelKey]['pass'] += $row['total'];
            $data['pfur'][$levelKey]['retry'] += $row['retry'];
        }
//------------------------------------------------------------
        // LOCAL
        // local ALL
        $sql = 'SELECT
sum(if(sap.is_retry=0,1,0))AS total,

sum(if(sap.is_retry > 0,1,0)) AS retry,

 stl.caption,
 stl.id AS test_id,
stl.print,
   sho.captoin

     FROM sdt_act_people sap

INNER JOIN sdt_act_test sat ON sat.id = sap.test_id
INNER JOIN sdt_act sa ON sa.id = sat.act_id
INNER JOIN sdt_test_levels stl ON stl.id = sat.level_id



INNER JOIN sdt_university su ON su.id = sa.university_id

INNER JOIN sdt_head_center shc ON shc.id = su.head_id
LEFT JOIN sdt_head_org sho ON sho.id = shc.horg_id
WHERE
length(sap.blank_number) > 1
AND su.id NOT IN (166,167,298,307)
AND sa.deleted = 0
AND sap.deleted = 0
AND sa.state IN (\'received\',\'check\',\'print\',\'wait_payment\',\'archive\')
AND sap.document = \'certificate\'
AND sa.created <= \''.$end->format('Y-m-d').' 23:59:59\'
AND sa.created >= \''.$begin->format('Y-m-d').' 0:0:0\'
AND sa.test_level_type_id = 1

AND sho.id = 1

GROUP BY  stl.id';
//die($sql);
        $result = $c->query($sql);
        foreach ($result as $row) {
            if (!array_key_exists($row['test_id'], $keyLevel)) {
                continue;
            }
            $levelKey = $keyLevel[$row['test_id']];
            $data['local'][$levelKey]['pass'] += $row['total'];
            $data['local'][$levelKey]['retry'] += $row['retry'];
        }

//echo '<pre>';        die(var_dump($data));
        return $this->render->view(
            'root/statist/ministr_rki',
            array(
                'data' => $data,
                'begin' => $begin,
                'end' => $end,
//            'test_level_type' => $test_level_type,
//            'from' => $from,
//            'to' => $to,
//            'result' => $result,
//            'caption' => 'Количество протестировавшихся. Общее'.$caption_suffix,
            )
        );
    }

    public function minobr_org_report_about_rki_by_citizenship_action()
    {
        $search = false;
        $result = array();
        $from = '1.01.2015';
        $to = date('d.m.Y');
        $hc = null;
        $chosen_hc_name = null;
        $lc = null;
        $lc_list = array();
//        $chosen_hc_name = '';
        $lc_caption = null;
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $search = true;
            $connection = Connection::getInstance();
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $hc = filter_input(INPUT_POST, 'hc');
//            $centers = array(1, 2, 7, 8);
            $centers = array();
            $all_centers = HeadCenters::getHeadOrgs();
            foreach ($all_centers as $cen) {
                $centers[] = $cen['id'];
            }
            if ($hc && is_numeric($hc)) {
                $lc = filter_input(INPUT_POST, 'lc');
                $lc_list = Univesities::getByHORG($hc);
                $centers = array($hc);
                $sql_hc = ' AND sho.id='.$hc.' ';
                $chosen_hc_name = ' по "'.HeadCenter::getOrgName($hc).'"';
            } else {
                $chosen_hc_name = '';
                $sql_hc = ' and sho.id in ('.implode(",", $centers).') ';
            }
            if ($hc && $lc && is_numeric($lc)) {
                $sql_hc .= ' AND su.id='.$lc.' ';
                $lc_caption = ' по "'.University::getByID($lc)->name.'"';
                /*   $chosen_hc_name = ' по головному центру "' . HeadCenter::getByID($hc) . '"';
               } elseif ($hc === 'pfur') {
                   $chosen_hc_name = 'объеденённый';
                   $sql_hc = ' and shc.id in (' . implode(",", $centers) . ') ';*/
            }
            $statest = array();
            foreach (Act::getInnerStates() as $st) {
                $statest[] = "'".$st."'";
            }
            $statest = implode(', ', $statest);
            $sql = "
            SELECT
country.name, country.id, sho.id AS h_id, sap.document, sdt_test_levels.caption, sdt_test_levels.id AS level_id,
count(DISTINCT sap.id) AS sdalo,

sum(if(sap.blank_number IS NOT NULL AND length(sap.blank_number)>0 , 1,0)) AS certs
FROM sdt_act_people sap
LEFT JOIN sdt_act_test sat ON sap.test_id = sat.id
LEFT JOIN sdt_act sa ON sa.id = sat.act_id
LEFT JOIN sdt_university su ON sa.university_id = su.id
LEFT JOIN sdt_head_center shc ON su.head_id = shc.id
LEFT JOIN sdt_head_org sho ON sho.id = shc.horg_id
LEFT JOIN sdt_test_levels ON sat.level_id = sdt_test_levels.id
LEFT JOIN country ON country.id=sap.country_id
WHERE sa.created BETWEEN '".$connection->escape($this->mysql_date($from))." 0:0:0' AND '".$connection->escape(
                    $this->mysql_date($to)
                )." 23:59:59'
AND sa.test_level_type_id = 1 AND sa.deleted = 0 AND sap.deleted = 0 AND sat.deleted = 0
AND shc.deleted = 0
AND sho.deleted = 0
".$sql_hc."
AND sa.state IN (".$statest.")
GROUP BY sho.id, country.id, sat.level_id, sap.document
            ";
//die($sql);
            $res = $connection->query($sql);
            $levels = array(
                1 => 0,
                2 => 1,
                3 => 2,
                5 => 3,
                6 => 4,
                7 => 5,
                8 => 6,
                11 => 7,
                12 => 8,
            );
            $template = array(
                'levels' => array(
                    0 => 0,
                    1 => 0,
                    2 => 0,
                    3 => 0,
                    4 => 0,
                    5 => 0,
                    6 => 0,
                    7 => 0,
                    8 => 0,
                ),
                'certs' => 0,
                'note_levels' => array(
                    0 => 0,
                    1 => 0,
                    2 => 0,
                    3 => 0,
                    4 => 0,
                    5 => 0,
                    6 => 0,
                    7 => 0,
                    8 => 0,
                ),
                'notes' => 0,
                'orgs' => 0,
            );
            $result = array();
            /*            foreach ($centers as $center) {
                            $result[$center] = array();
                        } 13.10.15*/
            $result[$hc] = array();
//            foreach ($centers as $h_id) { 13.10.15
            foreach ($result as $h_id => $list) {
                foreach (Countries::getAll() as $reg) {
                    $result[$h_id][$reg->id] = array(
                        'caption' => $reg,
                        'data' => $template,
                    );
                }
                $result[$h_id]['no'] = array(
                    'caption' => 'Не указана',
                    'data' => $template,
                );
            }
            foreach ($res as $item) {
                $region_id = $item['id'];
                if (!$region_id) {
                    $region_id = 'no';
                }
//                $cr = &$result[$item['h_id']][$region_id]['data'];
                $cr = &$result[$hc][$region_id]['data'];
                if ($item['document'] === ActMan::DOCUMENT_CERTIFICATE) {
                    $cr['levels'][$levels[$item['level_id']]] += $item['sdalo'];
                    $cr['certs'] += $item['certs'];
                } else {
                    $cr['note_levels'][$levels[$item['level_id']]] += $item['sdalo'];
                    $cr['notes'] += $item['certs'];
                }
            }
        }
        $regions_list = Countries::getAll4Form();
        $hc_list = HeadCenters::getHeadOrgs();

        return $this->render->view(
            'root/statist/fms_org_report_about_exam_note_by_citizenship',
            array(
                'selected_hc' => $hc,
                'arrays' => $result,
                'search' => $search,
                'from' => $from,
                'to' => $to,
                'regions_list' => $regions_list,
                'hc_list' => $hc_list,
                'caption' => 'Результаты тестирования РКИ по гражданству мигрантов ',
                'lc_list' => $lc_list,
                'lc_caption' => $lc_caption,
                'selected_lc' => $lc,
                'enable_lc' => true,
            )
        );
    }

    public function ajax_lc_list_action()
    {
        $hc = filter_input(INPUT_GET, 'hc', FILTER_VALIDATE_INT);
        if (!$hc) {
            die(json_encode([]));
        }
//        var
        $lc_list = Univesities::getByHORG($hc);
        $rseult = array_map(
            function (University $item) {
                return [
                    'id' => $item->id,
                    'name' => $this->utf_encode(
                        vsprintf(
                            '%s (%d,%s)',
                            [$item->name, $item->id, $item->getHeadCenter()->short_name]
                        )
                    ),
                ];
            },
            $lc_list->getArrayCopy()
        );
//var_dump($rseult);
        echo json_encode($rseult);
//        echo json_last_error_msg();
        die;
    }

    public function utf_encode($string)
    {
        return mb_convert_encoding($string, 'utf8', 'cp1251');
    }

    public function ajax_lc_list_by_hc_action()
    {
        $hc = filter_input(INPUT_GET, 'hc', FILTER_VALIDATE_INT);
        if (!$hc) {
            die(json_encode([]));
        }
//        var
        $lc_list = Univesities::getByHORG($hc);
        $rseult = array_map(
            function (University $item) {
                return [
                    'id' => $item->id,
                    'name' => $this->utf_encode($item->name),
                ];
            },
            $lc_list->getArrayCopy()
        );
//var_dump($rseult);
        echo json_encode($rseult);
//        echo json_last_error_msg();
        die;
    }

    /**
     * @return string
     * Отчет 30
     */
    public function minobr_org_report_about_exam_by_citizenship_action()
    {
        $search = false;
        $result = array();
        $from = '1.01.2015';
        $hc = null;
        $to = date('d.m.Y');
        $chosen_hc_name = null;
        $lc = null;
        $lc_list = array();
//        $chosen_hc_name = '';
        $lc_caption = null;
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $search = true;
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $hc = filter_input(INPUT_POST, 'hc');
            $lc = filter_input(INPUT_POST, 'lc');
            $rep = new minobr_org_report_about_exam_by_citizenship();
            $repRes = $rep->execute($from, $to, $hc, $lc);
//            die(var_dump($result));
            $result = $repRes['result'];
            $lc_caption = $repRes['lc_caption'];
        }
        $regions_list = Countries::getAll4Form();
//        $hc_list = HeadCenters::getAll();
        $hc_list = HeadCenters::getHeadOrgs();

        return $this->render->view(
            'root/statist/fms_org_report_about_exam_by_citizenship',
            array(
                'selected_hc' => $hc,
                'selected_lc' => $lc,
                'arrays' => $result,
                'enable_lc' => true,
                'search' => $search,
                'from' => $from,
                'to' => $to,
                'regions_list' => $regions_list,
                'hc_list' => $hc_list,
                'lc_list' => $lc_list,
                'lc_caption' => $lc_caption,
                'caption' => 'Результаты тестирования по комплексному экзамену по гражданству мигрантов ',
            )
        );
    }

    /**
     * @return string
     * Отчет 33
     */
    public function minobr_simple_exam_by_citizenship_action()
    {
        $search = false;
        $result = array();
        $from = '1.01.2015';
        $hc = null;
        $to = date('d.m.Y');
        $chosen_hc_name = null;
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $search = true;
            $connection = Connection::getInstance();
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $hc = filter_input(INPUT_POST, 'hc');
            //$centers = array(1, 2, 7, 8);
            $centers = array();
            $all_centers = HeadCenters::getHeadOrgs();
            foreach ($all_centers as $cen) {
                $centers[] = $cen['id'];
            }
            //$centers = HeadCenters::getHeadOrgs();
            if ($hc && is_numeric($hc)) {
                $centers = array($hc);
                $sql_hc = ' AND sho.id='.$hc.' ';
                $chosen_hc_name = ' по "'.HeadCenter::getOrgName($hc).'"';
                /*   $chosen_hc_name = ' по головному центру "' . HeadCenter::getByID($hc) . '"';
               } elseif ($hc === 'pfur') {
                   $chosen_hc_name = 'объеденённый';
                   $sql_hc = ' and shc.id in (' . implode(",", $centers) . ') ';*/
            } else {
                $chosen_hc_name = '';
                $sql_hc = ' and sho.id in ('.implode(",", $centers).') ';
            }
//            $statest = array();
            foreach (Act::getInnerStates() as $st) {
                $statest[] = "'".$st."'";
            }
            $statest = implode(', ', $statest);
            $sql = "
            SELECT
country.name, country.id, sho.id AS h_id, sap.document, sdt_test_levels.caption, sdt_test_levels.id AS level_id,
count(DISTINCT sap.id) AS sdalo,

sum(if(sap.blank_number IS NOT NULL AND length(sap.blank_number)>0 , 1,0)) AS certs
FROM sdt_act_people sap
LEFT JOIN sdt_act_test sat ON sap.test_id = sat.id
LEFT JOIN sdt_act sa ON sa.id = sat.act_id
LEFT JOIN sdt_university su ON sa.university_id = su.id
LEFT JOIN sdt_head_center shc ON su.head_id = shc.id
LEFT JOIN sdt_head_org sho ON sho.id = shc.horg_id
LEFT JOIN sdt_test_levels ON sat.level_id = sdt_test_levels.id
LEFT JOIN country ON country.id=sap.country_id
WHERE sa.created BETWEEN '".$connection->escape($this->mysql_date($from))." 0:0:0' AND '".$connection->escape(
                    $this->mysql_date($to)
                )." 23:59:59'
AND sa.test_level_type_id = 2
AND sa.deleted = 0
 AND sap.deleted = 0
 AND sat.deleted = 0
 AND shc.deleted = 0
 AND sho.deleted = 0
AND sdt_test_levels.is_additional = 1
".$sql_hc."
AND sa.state IN (".$statest.")
GROUP BY sho.id, country.id, sat.level_id, sap.document";
//die($sql);
            $res = $connection->query($sql);
            /*$levels = array(
                13 => 0,
                16 => 0,
                19 => 0,
                14 => 1,
                17 => 1,
                20 => 1,
                15 => 2,
                18 => 2,
                21 => 2,
                22 => 2,
                23 => 2,
                24 => 2,
                25 => 2,
                26 => 2,
                27 => 2,
                28 => 2,
                29 => 2,
            );*/
            $levels = ExamHelper::getLevelGroups();
            $template = array(
                'levels' => array(
                    0 => 0,
                    1 => 0,
                    2 => 0,
                ),
                'certs' => 0,
                'note_levels' => array(
                    0 => 0,
                    1 => 0,
                    2 => 0,
                ),
                'notes' => 0,
                'orgs' => 0,
            );
            $result = array();
            /*            if ($hc === 'pfur') {
                            $result['pfur'] = array();
                        } else {
                            foreach ($centers as $center) {
                                $result[$center] = array();
                            }
                        } 13.10.15*/
            $result[$hc] = array();
            foreach ($result as $h_id => $list) {
                foreach (Countries::getAll() as $reg) {
                    $result[$h_id][$reg->id] = array(
                        'caption' => $reg->name,
                        'data' => $template,
                    );
                }
                $result[$h_id]['no'] = array(
                    'caption' => 'Не указана',
                    'data' => $template,
                );
            }
            foreach ($res as $item) {
                $region_id = $item['id'];
                if (!$region_id) {
                    $region_id = 'no';
                }
                /*                if ($hc === 'pfur') {
                                    $cr = &$result['pfur'][$region_id]['data'];
                                } else {
                                    $cr = &$result[$item['h_id']][$region_id]['data'];
                                }  13.10.15*/
                $cr = &$result[$hc][$region_id]['data'];
                if ($item['document'] === ActMan::DOCUMENT_CERTIFICATE) {
                    $cr['levels'][$levels[$item['level_id']]] += $item['sdalo'];
                    $cr['certs'] += $item['certs'];
                } else {
                    $cr['note_levels'][$levels[$item['level_id']]] += $item['sdalo'];
                    $cr['notes'] += $item['certs'];
                }
            }
//            die(var_dump($result));
        }
        $regions_list = Countries::getAll4Form();
//        $hc_list = HeadCenters::getAll();
        $hc_list = HeadCenters::getHeadOrgs();

        return $this->render->view(
            'root/statist/fms_org_report_about_exam_by_citizenship',
            array(
                'selected_hc' => $hc,
                'arrays' => $result,
                'search' => $search,
                'from' => $from,
                'to' => $to,
                'regions_list' => $regions_list,
                'hc_list' => $hc_list,
                'caption' => 'Результаты тестирования по Упрощенному комплексному экзамену по гражданству мигрантов ',
            )
        );
    }

    public function not_inserted_region_action()
    {
        $hc = $result = [];
        $runned = false;
        if (!empty($_POST)) {
            $runned = true;
            $hc = filter_input(INPUT_POST, 'hc', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY);
            $hc_sql = '';
            if ($hc) {
                $hc_sql = ' and su.head_id in ('.implode(', ', $hc).')';
            }
            $sql = 'SELECT shc.short_name, shc.id, su.head_id, su.name, su.id AS local_id, su.user_id, su.deleted  FROM sdt_university su
INNER JOIN sdt_head_center shc ON su.head_id = shc.id
 WHERE (su.region_id = 0 OR su.region_id IS NULL) AND su.deleted = 0 AND su.head_id>0 '.$hc_sql.'
 ORDER BY shc.horg_id, shc.id';
            $c = Connection::getInstance();
            $result = $c->query($sql);
//            die(var_dump($result));
            if (!$hc) {
                $hc = [];
            }
        }
        $hc_list = HeadCenters::getAll();

        return $this->render->view(
            'root/statist/not_inserted_region',
            array(
                'selected_hc' => $hc,
                'runned' => $runned,
                'result' => $result,
                'hc_list' => $hc_list,
                'caption' => 'Локальные центры без региона',
            )
        );
    }

    /**
     * @return string
     * Отчет 34
     */
    public function students_report_about_by_lc_action()
    {
        $search = false;
        $result = array();
        $from = '1.01.2015';
        $to = date('d.m.Y');
        $hc = null;
        $chosen_hc_name = null;
        $lc = null;
        $lc_list = array();
        $lc_caption = null;
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $search = true;
            $connection = Connection::getInstance();
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $hc = filter_input(INPUT_POST, 'hc');
            $centers = array();
            $all_centers = HeadCenters::getHeadOrgs();
            foreach ($all_centers as $cen) {
                $centers[] = $cen['id'];
            }
            if ($hc && is_numeric($hc)) {
                $lc = filter_input(INPUT_POST, 'lc');
                $lc_list = Univesities::getByHORG($hc);
                $centers = array($hc);
                $sql_hc = ' AND sho.id='.$hc.' ';
                $chosen_hc_name = ' по "'.HeadCenter::getOrgName($hc).'"';
            } else {
                $chosen_hc_name = '';
                $sql_hc = ' and sho.id in ('.implode(",", $centers).') ';
            }
            if ($hc && $lc && is_numeric($lc)) {
                $sql_hc .= ' AND su.id='.$lc.' ';
                $lc_caption = ' по "'.University::getByID($lc)->name.'"';
            }
            $statest = array();
            foreach (Act::getInnerStates() as $st) {
                $statest[] = "'".$st."'";
            }
            $statest = implode(', ', $statest);
            $sql = "
            SELECT

sho.id AS sho,
su.id AS su,
sap.id,

if(cd.id IS NOT NULL,
	(SELECT cds.surname_rus FROM certificate_duplicate cds
	WHERE cds.user_id=sap.id AND cds.deleted = 0
	ORDER BY cds.id DESC LIMIT 1),sap.surname_rus)
	AS surname_rus,
/*
if(cd.id is not null,
	(select cds.surname_lat from certificate_duplicate cds
	where cds.user_id=sap.id AND cds.deleted = 0
	order by cds.id desc limit 1),sap.surname_lat)
	as surname_lat,
*/
if(cd.id IS NOT NULL,
	(SELECT cds.name_rus FROM certificate_duplicate cds
	WHERE cds.user_id=sap.id AND cds.deleted = 0
	ORDER BY cds.id DESC LIMIT 1),sap.name_rus)
	AS name_rus,
/*
if(cd.id is not null,
	(select cds.name_lat from certificate_duplicate cds
	where cds.user_id=sap.id AND cds.deleted = 0
	order by cds.id desc limit 1),sap.name_lat)
	as name_lat,
*/
co.name AS `country`,

sap.passport_name,
sap.passport_series,
sap.passport,
sap.passport_date,
sap.passport_department,
sap.birth_date,
sap.birth_place,
/*
sap.migration_card_number,
sap.migration_card_series,
*/
/*if(sap.document ='certificate','Сертификат','Справка') as document,*/
sap.blank_number,
/*sap.blank_date,*/

/*if (cd.id IS NOT NULL, '+', '') AS dublikat,*/

if(cd.id IS NOT NULL,
	(SELECT cds.certificate_number FROM certificate_duplicate cds
	WHERE cds.user_id=sap.id AND cds.deleted = 0
	ORDER BY cds.id DESC LIMIT 1),'')
	AS dublikat,

if (sap.document = 'certificate',sap.blank_number,'') AS cert_num,
if (sap.document = 'note',sap.blank_number,'') AS note_num,

sap.blank_date AS blank_date

FROM sdt_act AS sa
LEFT JOIN sdt_act_people AS sap ON sa.id=sap.act_id
LEFT JOIN certificate_duplicate AS cd ON cd.user_id=sap.id AND cd.deleted = 0
LEFT JOIN sdt_act_test AS sat ON sap.test_id=sat.id
LEFT JOIN country AS co ON co.id = sap.country_id
LEFT JOIN sdt_university su ON sa.university_id = su.id
LEFT JOIN sdt_head_center shc ON su.head_id = shc.id
LEFT JOIN sdt_head_org sho ON sho.id = shc.horg_id

WHERE sap.blank_date BETWEEN '".$connection->escape($this->mysql_date($from))." 0:0:0' AND '".$connection->escape(
                    $this->mysql_date($to)
                )." 23:59:59'

AND sap.deleted = 0 AND ( (sap.document = 'certificate' AND sap.blank_number <> '') || (sap.document='note' AND sap.blank_number <> ''))
AND sa.deleted = 0 AND sap.deleted = 0 AND sat.deleted = 0
AND shc.deleted = 0
AND sho.deleted = 0
".$sql_hc."
AND sa.state IN (".$statest.")
/*and sa.university_id = 495*/

ORDER BY trim(LOWER(sap.surname_rus)) ASC, trim(LOWER(sap.name_rus)) ASC, sap.document ASC
";
            $res = $connection->query($sql);
            if (!empty($res)) {
                foreach ($res as $key => $value) {
                    $result[$value['sho']][$value['su']][] = $value;
                }
            }
        }
        $regions_list = Countries::getAll4Form();
        $hc_list = HeadCenters::getHeadOrgs();

        return $this->render->view(
            'root/statist/students_report_about_by_lc',
            array(
                'selected_hc' => $hc,
                'arrays' => $result,
                'search' => $search,
                'from' => $from,
                'to' => $to,
                'regions_list' => $regions_list,
                'hc_list' => $hc_list,
                'caption' => 'Данные о получивших сертификаты / справки ',
                'lc_list' => $lc_list,
                'lc_caption' => $lc_caption,
                'selected_lc' => $lc,
                'enable_lc' => true,
            )
        );
    }

    /**
     * @return string
     * Отчет 40
     */
    public function examine_certs_report_by_lc_action()
    {
        $search = false;
        $result = array();
        $from = '1.01.2015';
        $to = date('d.m.Y');
        $hc = null;
        $chosen_hc_name = null;
        $lc = null;
        $lc_list = array();
        $lc_caption = null;
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $search = true;
            $connection = Connection::getInstance();
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $hc = filter_input(INPUT_POST, 'hc');
            $centers = array();
            $all_centers = HeadCenters::getHeadOrgs();
            foreach ($all_centers as $cen) {
                $centers[] = $cen['id'];
            }
            if ($hc && is_numeric($hc)) {
                $lc = filter_input(INPUT_POST, 'lc');
                $lc_list = Univesities::getByHORG($hc);
                $centers = array($hc);
                $sql_hc = ' AND sho.id='.$hc.' ';
                $chosen_hc_name = ' по "'.HeadCenter::getOrgName($hc).'"';
            } else {
                $chosen_hc_name = '';
                $sql_hc = ' and sho.id in ('.implode(",", $centers).') ';
            }
            if ($hc && $lc && is_numeric($lc)) {
                $sql_hc .= ' AND su.id='.$lc.' ';
                $lc_caption = ' по "'.University::getByID($lc)->name.'"';
            }
            $statest = array();
            foreach (Act::getInnerStates() as $st) {
                $statest[] = "'".$st."'";
            }
            $statest = implode(', ', $statest);
            $sql = "
            SELECT
            su.id,
            su.name,
            su.legal_address,
            count(sap.id) AS `certs`,
            month(sap.blank_date) AS `month`,
            year(sap.blank_date) AS `year`,
            sho.id AS sho,
            su.id AS su
             FROM sdt_act_people sap
LEFT JOIN sdt_act sa ON sa.id = sap.act_id
LEFT JOIN sdt_university su ON su.id = sa.university_id
LEFT JOIN sdt_head_center shc ON su.head_id = shc.id
LEFT JOIN sdt_head_org sho ON sho.id = shc.horg_id
WHERE sap.deleted = 0
	AND sa.deleted = 0
".$sql_hc."

AND sa.state IN (".$statest.")

AND sa.test_level_type_id = 2
AND sap.blank_number IS NOT NULL AND sap.blank_number <> '' AND sap.document = 'certificate'

AND sap.blank_date BETWEEN '".$connection->escape($this->mysql_date($from))." 0:0:0' AND '".$connection->escape(
                    $this->mysql_date($to)
                )." 23:59:59'

GROUP BY su.id, YEAR(sap.blank_date), MONTH(sap.blank_date)

";
//            die($sql);
            $res = $connection->query($sql);
            if (!empty($res)) {
                foreach ($res as $key => $value) {
                    $result[$value['sho']][$value['su']][] = $value;
                }
            }
        }
        $regions_list = Countries::getAll4Form();
        $hc_list = HeadCenters::getHeadOrgs();

        return $this->render->view(
            'root/statist/examine_certs_report_by_lc',
            array(
                'selected_hc' => $hc,
                'arrays' => $result,
                'search' => $search,
                'from' => $from,
                'to' => $to,
                'regions_list' => $regions_list,
                'hc_list' => $hc_list,
                'caption' => 'Сведения о локальных центрах и выданных сертификатах (по дате выдачи сертификата) ',
                'lc_list' => $lc_list,
                'lc_caption' => $lc_caption,
                'selected_lc' => $lc,
                'enable_lc' => true,
            )
        );
    }

    /**
     * @return string
     * Отчет 41
     */
    public function examine_certs_report_by_lc_act_action()
    {
        $search = false;
        $result = array();
        $from = '1.01.2015';
        $to = date('d.m.Y');
        $hc = null;
        $chosen_hc_name = null;
        $lc = null;
        $lc_list = array();
        $lc_caption = null;
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $search = true;
            $connection = Connection::getInstance();
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $hc = filter_input(INPUT_POST, 'hc');
            $centers = array();
            $all_centers = HeadCenters::getHeadOrgs();
            foreach ($all_centers as $cen) {
                $centers[] = $cen['id'];
            }
            if ($hc && is_numeric($hc)) {
                $lc = filter_input(INPUT_POST, 'lc');
                $lc_list = Univesities::getByHORG($hc);
                $centers = array($hc);
                $sql_hc = ' AND sho.id='.$hc.' ';
                $chosen_hc_name = ' по "'.HeadCenter::getOrgName($hc).'"';
            } else {
                $chosen_hc_name = '';
                $sql_hc = ' and sho.id in ('.implode(",", $centers).') ';
            }
            if ($hc && $lc && is_numeric($lc)) {
                $sql_hc .= ' AND su.id='.$lc.' ';
                $lc_caption = ' по "'.University::getByID($lc)->name.'"';
            }
            $statest = array();
            foreach (Act::getInnerStates() as $st) {
                $statest[] = "'".$st."'";
            }
            $statest = implode(', ', $statest);
            $sql = "
            SELECT
            su.id,
            su.name,
            su.legal_address,
            count(sap.id) AS `certs`,
            month(sa.created) AS `month`,
            year(sa.created) AS `year`,
            sho.id AS sho,
            su.id AS su
             FROM sdt_act_people sap
LEFT JOIN sdt_act sa ON sa.id = sap.act_id
LEFT JOIN sdt_university su ON su.id = sa.university_id
LEFT JOIN sdt_head_center shc ON su.head_id = shc.id
LEFT JOIN sdt_head_org sho ON sho.id = shc.horg_id
WHERE sap.deleted = 0
	AND sa.deleted = 0
".$sql_hc."

AND sa.state IN (".$statest.")

AND sa.test_level_type_id = 2
AND sap.blank_number IS NOT NULL AND sap.blank_number <> '' AND sap.document = 'certificate'

AND sa.created BETWEEN '".$connection->escape($this->mysql_date($from))." 0:0:0' AND '".$connection->escape(
                    $this->mysql_date($to)
                )." 23:59:59'

GROUP BY su.id, YEAR(sa.created), MONTH(sa.created)

";
//            die($sql);
            $res = $connection->query($sql);
            if (!empty($res)) {
                foreach ($res as $key => $value) {
                    $result[$value['sho']][$value['su']][] = $value;
                }
            }
        }
        $regions_list = Countries::getAll4Form();
        $hc_list = HeadCenters::getHeadOrgs();

        return $this->render->view(
            'root/statist/examine_certs_report_by_lc',
            array(
                'selected_hc' => $hc,
                'arrays' => $result,
                'search' => $search,
                'from' => $from,
                'to' => $to,
                'regions_list' => $regions_list,
                'hc_list' => $hc_list,
                'caption' => 'Сведения о локальных центрах и выданных сертификатах (по дате создания акта) ',
                'lc_list' => $lc_list,
                'lc_caption' => $lc_caption,
                'selected_lc' => $lc,
                'enable_lc' => true,
            )
        );
    }

    public function deleteArchivePeople_action()
    {
        $run = filter_input(INPUT_POST, 'remove');
        $C = Connection::getInstance();
        $affected = 0;
        $founded = 0;
        $ids = array();
        if ($run) {
            $sql = ' SELECT
sap.id
FROM sdt_act_people  sap
LEFT JOIN sdt_act sa ON sap.act_id = sa.id

LEFT JOIN certs_annul ca ON ca.man_id = sap.id

WHERE
ca.id IS NULL AND
(sap.blank_number = \'\' OR sap.blank_number IS NULL)
AND sap.deleted = 0 AND sa.deleted = 0
AND sa.state = \'archive\'';
            $res = $C->query($sql);
            if ($res) {
                $ids = array_column($res, 'id');
                $founded = count($ids);
                foreach (array_chunk($ids, 40) as $chunk) {
                    $sql = 'UPDATE sdt_act_people SET deleted = 1 WHERE id IN ('.implode(', ', $chunk).')';
                    $affected += $C->execute($sql);
//                if($affected > 50) break;
                }
            }
//             die(var_dump($ids));
        }
        $sql = 'SELECT
count(*) AS cc
FROM sdt_act_people  sap
LEFT JOIN sdt_act sa ON sap.act_id = sa.id
LEFT JOIN certs_annul ca ON ca.man_id = sap.id

WHERE
ca.id IS NULL AND
(sap.blank_number = \'\' OR sap.blank_number IS NULL)
AND sap.deleted = 0 AND sa.deleted = 0
AND sa.state = \'archive\'';
        $res = $C->query($sql, 1);
        $count = $res['cc'];

        return $this->render->view(
            'root/statist/delete_archive_people',
            array(
                'ids' => $ids,
                'people_count' => $count,
                'affected' => $affected,
                'founded' => $founded,
                'run' => $run,
            )
        );
    }

    /**
     * @return string
     * Отчет 36
     */
    public function fms_report_about_exam_blank_action()
    {
        $search = false;
        $result = array();
        $from = '1.01.'.date('Y');
        $to = date('d.m.Y');
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $search = true;
            $connection = Connection::getInstance();
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $statest = array();
            foreach (Act::getInnerStates() as $st) {
                $statest[] = "'".$st."'";
            }
            $statest = implode(', ', $statest);
            $sql = "SELECT
  shc.short_name,
  shc.id,
  sdt_test_levels.caption,
  sdt_test_levels.id AS level_id,
  count(DISTINCT sap.id) AS sdalo,

  sum(if(sap.blank_number IS NOT NULL AND length(sap.blank_number)>0 , 1,0)) AS certs
FROM sdt_act_people sap
  LEFT JOIN sdt_act_test sat
    ON sap.test_id = sat.id
  LEFT JOIN sdt_act sa
    ON sa.id = sat.act_id
  LEFT JOIN sdt_university su
    ON sa.university_id = su.id
  LEFT JOIN sdt_head_center shc
    ON su.head_id = shc.id
  LEFT JOIN sdt_test_levels
    ON sat.level_id = sdt_test_levels.id
WHERE sa.created BETWEEN '".$connection->escape($this->mysql_date($from))." 0:0:0' AND '".$connection->escape(
                    $this->mysql_date($to)
                )." 23:59:59'
AND sa.test_level_type_id = 2
AND sa.deleted = 0
AND sap.deleted = 0
AND sat.deleted = 0
AND shc.deleted = 0

AND sa.state IN (".$statest.")
AND sap.document = '".ActMan::DOCUMENT_CERTIFICATE."'
GROUP BY shc.id,
         sat.level_id";
//            die($sql);
            $res = $connection->query($sql);
//            $levels = array(
//                0 => array(
//                    13,
//                    16
//                ),
//                1 => array(
//                    14,
//                    17
//                ),
//                2 => array(
//                    15,
//                    18
//                ),
//            );
            /*$levels = array(
                13 => 0,
                16 => 0,
                19 => 0,
                14 => 1,
                17 => 1,
                20 => 1,
                15 => 2,
                18 => 2,
                21 => 2,
                22 => 2,
                23 => 2,
                24 => 2,
                25 => 2,
                26 => 2,
                27 => 2,
                28 => 2,
                29 => 2,
            );*/
            $levels = ExamHelper::getLevelGroups();
            /*$gc = array(
                2 => 0,
                3 => 1,
                1 => 0,
                9 => 2,
                10 => 3,
                4 => 4,
                8 => 0,
                5 => 5,
                7 => 0,
                11 => 6,
                12 => 7,
                13 => 0,
            );*/
            $gc = HeadCenters::getStatistHcArrayAll();
            $template = array(
                'levels' => array(
                    0 => 0,
                    1 => 0,
                    2 => 0,
                ),
                'certs' => 0,
                'orgs' => 0,
            );
            /* $result = array(
                 0 => array(
                     'caption' => 'РУДН',
                     'data' => $template,
                 ),
                 1 => array(
                     'caption' => 'МГУ',
                     'data' => $template,
                 ),
                 2 => array(
                     'caption' => 'ТОГУ',
                     'data' => $template,
                 ),
                 3 => array(
                     'caption' => 'ТюмГУ',
                     'data' => $template,
                 ),
                 4 => array(
                     'caption' => 'Гос. ИРЯ им. А.С. Пушкина ',
                     'data' => $template,
                 ),
                 5 => array(
                     'caption' => 'СПбГУ ',
                     'data' => $template,
                 ),
                 6 => array(
                     'caption' => 'ВолГУ ',
                     'data' => $template,
                 ),
                 7 => array(
                     'caption' => 'КФУ ',
                     'data' => $template,
                 ),

             );*/
            $result = HeadCenters::getStatistResultArrayAll($template);
            foreach ($res as $item) {
                if (!array_key_exists($item['id'], $gc)) {
                    continue;
                }
                $cr = &$result[$gc[$item['id']]]['data'];
//                $cr['levels'][$levels[$item['level_id']]] += $item['sdalo'];
                $cr['levels'][$levels[$item['level_id']]] += $item['certs'];
//                $cr['certs'] += $item['certs'];
                $cr['certs'] += $item['sdalo'];
            }
//            echo '<pre>';
//            (var_dump($res));
//            echo '<hr>';
            $sql = 'SELECT count(*) AS cc, head_id FROM sdt_university WHERE deleted=0 GROUP BY head_id';
//            die($sql);
            $sql = 'SELECT  count(DISTINCT su.id) AS cc, su.head_id FROM sdt_university su
WHERE  su.id IN (SELECT  sa.university_id  FROM sdt_act sa WHERE sa.test_level_type_id = 2
AND  sa.created BETWEEN \''.$connection->escape($this->mysql_date($from)).' 0:0:0\'
AND \''.$connection->escape(
                    $this->mysql_date($to)
                ).' 23:59:59\'
)
GROUP BY su.head_id';
            $res = $connection->query($sql);
            foreach ($res as $item) {
                if (!array_key_exists($item['head_id'], $gc)) {
                    continue;
                }
                $cr = &$result[$gc[$item['head_id']]]['data'];
                $cr['orgs'] += $item['cc'];
            }
//            die(var_dump($result));
        }

        return $this->render->view(
            'root/statist/fms_report_about_exam_blank',
            array(
                'array' => $result,
                'search' => $search,
                'from' => $from,
                'to' => $to,
                'caption' => 'Статистика работы всех головных центров по проведению комплексного экзамена',
            )
        );
    }

    /**
     * @return string
     * Отчет 39
     */
    public function exam_statist_fin_check_action()
    {
        $begin = filter_input(INPUT_GET, 'begin');
        $end = filter_input(INPUT_GET, 'end');
        if (!($begin) || !($end)) {
            return $this->render->view(
                'root/statist/exam_statist_fin_check',
                array(
                    'array' => [],
                    'end' => new DateTime(),
                    'begin' => new DateTime('1.1.2015'),
//            'test_level_type' => $test_level_type,
//            'from' => $from,
//            'to' => $to,
//            'result' => $result,
//            'caption' => 'Количество протестировавшихся. Общее'.$caption_suffix,
                )
            );
        }
        $begin = new DateTime($begin);
        $end = new DateTime($end);
        $statest = array();
        foreach (Act::getInnerStates() as $st) {
            $statest[] = "'".$st."'";
        }
        $statest = implode(', ', $statest);
        $sql = 'SELECT sum(if(sap.is_retry=0,1,0)) AS first,
 sum(if(sap.is_retry=1,1,0)) AS one, 
 sum(if(sap.is_retry=2,1,0)) AS two ,
 sap.document,
 count(*)  AS total,
 if(su.id IN (166,167,298,307),\'local\',\'external\') AS hc,
  CASE
 	 WHEN am.id  IS NULL AND stl.id NOT IN (16,17,18,23,26)THEN \'simple\'
 	 WHEN am.id  IS NULL AND stl.id  IN (16,17,18,23,26) THEN \'simple_simple\'
	 WHEN am.special_price_group = 0 AND am.test_group = 2 THEN \'invalid\' 
	 WHEN am.special_price_group = 2 AND am.test_group = 1 THEN \'ukraine\' 
	 WHEN am.special_price_group = 1 AND am.test_group = 1 AND stl.id NOT IN (16,17,18,23,26) THEN \'dnr\'
	 WHEN am.special_price_group = 1 AND am.test_group = 1 AND stl.id  IN (16,17,18,23,26) THEN \'dnr_simple\'

 		ELSE \'not found\'
 END   AS tst_grop 
  ,sat.level_id, stl.caption, shc.id AS shc_id
  
  FROM sdt_act_people sap
  
LEFT JOIN sdt_act_test sat ON sat.id = sap.test_id
LEFT JOIN sdt_test_levels stl ON stl.id = sat.level_id
LEFT JOIN sdt_act sa  ON sa.id = sat.act_id
LEFT JOIN act_metadata  am ON am.act_id = sa.id
LEFT JOIN sdt_university su ON su.id = sa.university_id
LEFT JOIN sdt_head_center shc ON shc.id = su.head_id
WHERE 
 sa.created <= \''.$end->format('Y-m-d').' 23:59:59\'
AND sa.created >= \''.$begin->format('Y-m-d').' 0:0:0\'
AND sa.state IN ('.$statest.')
AND shc.horg_id  = 1
AND sap.deleted = 0 AND sa.deleted = 0
AND sa.test_level_type_id = 2
GROUP BY hc,tst_grop, sat.level_id, sap.document, shc_id
ORDER BY hc DESC, tst_grop ASC,stl.caption, sap.document DESC';
//die($sql);
        $level_template = [
            'first' => 0,
            'one' => 0,
            'two' => 0,
            'note' => 0,
        ];
        $byLevel = [
            'rnr' => [
                'ids' => ExamHelper::getRNRlevels(),
                'cc' => $level_template,
            ],
            'rvj' => [
                'ids' => ExamHelper::getRVJlevels(),
                'cc' => $level_template,
            ],
            'vnj' => [
                'ids' => ExamHelper::getVNJlevels(),
                'cc' => $level_template,
            ],
        ];
        $byType = [
            'simple' => $byLevel,
            'simple_simple' => $byLevel,
            'invalid' => $byLevel,
            'ukraine' => $byLevel,
            'dnr' => $byLevel,
            'dnr_simple' => $byLevel,
        ];
        $result = [
            'local' => $byType,
            'external' => $byType,
        ];
        /*$hc_list = [
            1 => [
                'id' => [1],
                'caption' => 'МЦТ',
                'result' => $result,
            ],
            2 => [
                'id' => [2],
                'caption' => 'МЦТ ФПКП РКИ',
                'result' => $result,
            ],
            7 => [
                'id' => [7],
                'caption' => 'ШОПМ',
                'result' => $result,
            ],
            8 => [
                'id' => [8],
                'caption' => 'ЦТ РУДН',
                'result' => $result,
            ],
        ];*/
        $hc_list = HeadCenters::getStatistHCListArrayPfur($result);
        $con = Connection::getInstance();
        $data = $con->query($sql);
        foreach ($data as $row) {
            $levels = &$result[$row['hc']][$row['tst_grop']];
//            die(var_dump($levels));
            $hc = &$hc_list[$row['shc_id']];
//if(!array_key_exist($row['shc_id'],$hc_list));
//            die(var_dump($hc));
            $level = null;
            $l_id = null;
            foreach ($levels as $key => $l) {
                if (in_array($row['level_id'], $l['ids'])) {
                    $l_id = $key;
                    break;
                }
            }
            if (is_null($l_id)) {
                die(var_dump('unknown_level', $row, $levels));
            }
            $level = &$levels[$l_id];
            if ($row['document'] == 'note') {
                $level['cc']['note'] += $row['first'];
                $hc['result'][$row['hc']][$row['tst_grop']][$l_id]['cc']['note'] += $row['first'];
            } else {
                $level['cc']['first'] += $row['first'];
                $hc['result'][$row['hc']][$row['tst_grop']][$l_id]['cc']['first'] += $row['first'];
            }
            $level['cc']['one'] += $row['one'];
            $hc['result'][$row['hc']][$row['tst_grop']][$l_id]['cc']['one'] += $row['one'];
            $level['cc']['two'] += $row['two'];
            $hc['result'][$row['hc']][$row['tst_grop']][$l_id]['cc']['two'] += $row['two'];
            unset($level);
            unset($hc);
        }
//die(var_dump($hc_list));
//array_k
        $dulb_sql = 'SELECT COUNT(DISTINCT dam.id) AS cc,sat.level_id, stl.caption,
  if(su.id IN (166,167,298,307),\'local\',\'external\') AS hc
  FROM dubl_act_man dam 
  LEFT JOIN sdt_act_people sap ON  sap.id = dam.old_man_id
LEFT JOIN sdt_act_test sat ON sat.id = sap.test_id
  LEFT JOIN sdt_test_levels stl ON stl.id = sat.level_id
LEFT JOIN dubl_act da ON da.id = dam.act_id
  LEFT JOIN sdt_university su ON su.id = da.center_id
  LEFT JOIN sdt_head_center shc ON shc.id = su.head_id

WHERE shc.horg_id = 1 AND da.state IN (\'processed\',\'in_headcenter\')
  AND da.created <= \''.$end->format('Y-m-d').' 23:59:59\'
AND da.created >= \''.$begin->format('Y-m-d').' 0:0:0\'
  AND da.test_level_type_id = 2

GROUP BY sat.level_id, hc';
//die($dulb_sql);
        $dublData = [
            'local' => [
                'rnr' => [
                    'ids' => ExamHelper::getRNRlevels(),
                    'cc' => 0,
                ],
                'rvj' => [
                    'ids' => ExamHelper::getRVJlevels(),
                    'cc' => 0,
                ],
                'vnj' => [
                    'ids' => ExamHelper::getVNJlevels(),
                    'cc' => 0,
                ],
            ],
            'external' => [
                'rnr' => [
                    'ids' => ExamHelper::getRNRlevels(),
                    'cc' => 0,
                ],
                'rvj' => [
                    'ids' => ExamHelper::getRVJlevels(),
                    'cc' => 0,
                ],
                'vnj' => [
                    'ids' => ExamHelper::getVNJlevels(),
                    'cc' => 0,
                ],
            ],
        ];
        $dubl_res = $con->query($dulb_sql);
        foreach ($dubl_res as $row) {
            $levels = &$dublData[$row['hc']];
//            die(var_dump($levels));
            $level = null;
            $l_id = null;
            foreach ($levels as $key => $l) {
                if (in_array($row['level_id'], $l['ids'])) {
                    $l_id = $key;
                    break;
                }
            }
            if (is_null($l_id)) {
                die(var_dump('unknown_level', $row, $levels));
            }
            $level = &$levels[$l_id];
            $level['cc'] += $row['cc'];
            unset($level);
        }

        return $this->render->view(
            'root/statist/exam_statist_fin_check',
            array(
                'array' => $result,
//                'search' => $search,
                'dubl_array' => $dublData,
                'hc_list' => $hc_list,
                'begin' => $begin,
                'end' => $end,
                'caption' => 'Статистика работы всех головных центров по проведению комплексного экзамена',
            )
        );
    }

    /**
     * @return string
     * Отчет 44
     */
    public function report_antiterror_student_action()
    {
        $search = false;
        $result = array();
        $from = '1.01.2010';
        $to = date('d.m.Y');
        $hc = null;
        $chosen_hc_name = null;
        $lc = null;
        $lc_list = array();
        $lc_caption = null;
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $search = true;
            $connection = Connection::getInstance();
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $hc = filter_input(INPUT_POST, 'hc');
            $centers = array();
            $all_centers = HeadCenters::getHeadOrgs();
            foreach ($all_centers as $cen) {
                $centers[] = $cen['id'];
            }
            if ($hc && is_numeric($hc)) {
                $centers = array($hc);
                $sql_hc = ' AND sho.id='.$hc.' ';
                $chosen_hc_name = ' по "'.HeadCenter::getOrgName($hc).'"';
            } else {
                $chosen_hc_name = '';
                $sql_hc = ' and sho.id in ('.implode(",", $centers).') ';
            }
            $statest = array();
            foreach (Act::getInnerStates() as $st) {
                $statest[] = "'".$st."'";
            }
            $statest = implode(', ', $statest);
            $sql = "
            SELECT

sho.id AS sho,
su.id AS su,
su.name AS lc_caption,
stl.caption AS test_level,
sap.id,

if(cd.id IS NOT NULL,
	(SELECT cds.surname_rus FROM certificate_duplicate cds
	WHERE cds.user_id=sap.id AND cds.deleted = 0
	ORDER BY cds.id DESC LIMIT 1),sap.surname_rus)
	AS surname_rus,
/*
if(cd.id is not null,
	(select cds.surname_lat from certificate_duplicate cds
	where cds.user_id=sap.id AND cds.deleted = 0
	order by cds.id desc limit 1),sap.surname_lat)
	as surname_lat,
*/
if(cd.id IS NOT NULL,
	(SELECT cds.name_rus FROM certificate_duplicate cds
	WHERE cds.user_id=sap.id AND cds.deleted = 0
	ORDER BY cds.id DESC LIMIT 1),sap.name_rus)
	AS name_rus,
/*
if(cd.id is not null,
	(select cds.name_lat from certificate_duplicate cds
	where cds.user_id=sap.id AND cds.deleted = 0
	order by cds.id desc limit 1),sap.name_lat)
	as name_lat,
*/
co.name AS `country`,

sap.passport_name,
sap.passport_series,
sap.passport,
sap.passport_date,
sap.passport_department,
sap.birth_date,
sap.birth_place,




/*
if (sap.document = 'certificate',sap.blank_number,'') AS cert_num,
if (sap.document = 'note',sap.blank_number,'') AS note_num,
*/
sap.blank_date AS blank_date

FROM  sdt_act_people AS sap
LEFT JOIN  sdt_act AS sa ON sa.id=sap.act_id
LEFT JOIN certificate_duplicate AS cd ON cd.user_id=sap.id AND cd.deleted = 0
LEFT JOIN sdt_act_test AS sat ON sap.test_id=sat.id
LEFT JOIN sdt_test_levels stl ON stl.id = sat.level_id
LEFT JOIN country AS co ON co.id = sap.country_id
LEFT JOIN sdt_university su ON sa.university_id = su.id
LEFT JOIN sdt_head_center shc ON su.head_id = shc.id
LEFT JOIN sdt_head_org sho ON sho.id = shc.horg_id

WHERE (
sap.blank_date BETWEEN '".$connection->escape($this->mysql_date($from))." 0:0:0' AND '".$connection->escape(
                    $this->mysql_date($to)
                )." 23:59:59'
				 OR sap.blank_date IS NULL  OR sap.blank_date = '0000-00-00'
				)
AND sa.test_level_type_id=1
AND sap.deleted = 0 AND  sap.document = 'certificate' AND sap.blank_number <> ''
AND sa.deleted = 0 AND sat.deleted = 0
AND shc.deleted = 0
AND su.id <> 168
AND sho.deleted = 0

".$sql_hc."
AND sa.state IN (".$statest.")
/*and sa.university_id = 495*/

ORDER BY trim(LOWER(sap.surname_rus)) ASC, trim(LOWER(sap.name_rus)) ASC
";
            // die($sql);
            $res = $connection->query($sql);
            if (!empty($res)) {
                foreach ($res as $key => $value) {
                    $result[$value['sho']][] = $value;
                }
            }
        }
        $regions_list = Countries::getAll4Form();
        $hc_list = HeadCenters::getHeadOrgs();

        return $this->render->view(
            'root/statist/report_antiterror_student',
            array(
                'selected_hc' => $hc,
                'arrays' => $result,
                'search' => $search,
                'from' => $from,
                'to' => $to,
                'regions_list' => $regions_list,
                'hc_list' => $hc_list,
                'caption' => 'Информация о выдачи сертификатов о прохождении
                 государственного тестирования по русскому языку как иностранному',
            )
        );
    }

    /**
     * @return string
     * Отчет 44
     */
    public function exam_cert_numbers_by_lc_action()
    {
        $search = false;
        $result = array();
        $from = '1.01.2015';
        $to = date('d.m.Y');
        $hc = null;
        $chosen_hc_name = null;
        $items = [
            '0' => [],
            '1' => [],
            '2' => [],
        ];
        $lc = null;
        $lc_list = array();
        $lc_caption = null;
        $fired = false;
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $search = true;
            $connection = Connection::getInstance();
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $hc = filter_input(INPUT_POST, 'hc');
            $lc = filter_input(INPUT_POST, 'lc');
            $centers = array();
            $all_centers = HeadCenters::getHeadOrgs();
            foreach ($all_centers as $cen) {
                $centers[] = $cen['id'];
            }
            $lc_list = Univesities::getByHORG($hc);
            $centers = array($hc);
            $sql_hc = ' AND sho.id='.$hc.' ';
            $chosen_hc_name = ' по "'.HeadCenter::getOrgName($hc).'"';
            $sql_hc .= ' AND su.id='.$lc.' ';
            $lc_caption = ' по "'.University::getByID($lc)->name.'"';
            $statest = array();
            foreach (Act::getInnerStates() as $st) {
                $statest[] = "'".$st."'";
            }
            $statest = implode(', ', $statest);
            $fired = true;
            $sql = sprintf(
                "
			SELECT  sap.blank_number AS numb, sat.level_id
	  FROM sdt_act_people sap 
	  LEFT JOIN sdt_act sa ON sa.id = sap.act_id 
	  LEFT JOIN sdt_act_test sat ON sap.test_id = sat.id
	  LEFT JOIN sdt_university su ON su.id = sa.university_id
	   LEFT JOIN sdt_head_center shc ON su.head_id = shc.id 
		LEFT JOIN sdt_head_org sho ON sho.id = shc.horg_id
		 WHERE sap.deleted = 0 AND sa.deleted = 0 
		 AND sho.id=%d
		  AND su.id=%d
		  AND sa.state IN (%s) 
		  AND sa.test_level_type_id = %d AND sap.blank_number IS NOT NULL AND 
		  sap.blank_number <> '' 
		  AND sap.document = 'certificate'
		   AND sa.created BETWEEN '%s  0:0:0' AND '%s' 
	
	UNION ALL 
	SELECT 
cd.certificate_number AS numb, sat.level_id
FROM certificate_duplicate cd
	  LEFT JOIN sdt_act_people sap  ON sap.id = cd.user_id
	  LEFT JOIN sdt_act sa ON sa.id = sap.act_id 
	  LEFT JOIN sdt_act_test sat ON sap.test_id = sat.id
	  LEFT JOIN sdt_university su ON su.id = sa.university_id
	   LEFT JOIN sdt_head_center shc ON su.head_id = shc.id 
		LEFT JOIN sdt_head_org sho ON sho.id = shc.horg_id
		 WHERE sap.deleted = 0 AND sa.deleted = 0 AND cd.deleted = 0
		 AND sho.id=%d
		  AND su.id=%d
		  AND sa.state IN (%s) 
		  AND sa.test_level_type_id = %d AND sap.blank_number IS NOT NULL AND 
		  sap.blank_number <> '' 
		  AND sap.document = 'certificate'
		   AND sa.created BETWEEN '%s  0:0:0' AND '%s  23:59:59' 
		   ",
                $hc,
                $lc,
                $statest,
                2,
                $connection->escape($this->mysql_date($from)),
                $connection->escape($this->mysql_date($to)),
                $hc,
                $lc,
                $statest,
                2,
                $connection->escape($this->mysql_date($from)),
                $connection->escape($this->mysql_date($to))
            );
            //die($sql);
            $res = $connection->query($sql);
            if (!empty($res)) {
                foreach ($res as $row) {
                    $items[ExamHelper::getLevelGroup($row['level_id'])][] = $row['numb'];
                }
//				var_dump($items);
                //	die;
            }
        }
        $hc_list = HeadCenters::getHeadOrgs();

        return $this->render->view(
            'root/statist/exam_cert_numbers_by_lc',
            array(
                'selected_hc' => $hc,
                'arrays' => $items,
                'search' => $search,
                'from' => $from,
                'to' => $to,
                'hc_list' => $hc_list,
                'fired' => $fired,
                'lc_list' => $lc_list,
                'lc_caption' => $lc_caption,
                'selected_lc' => $lc,
                'enable_lc' => true,
            )
        );
    }

    /**
     * @return string
     * Отчет 45
     */
    public function rki_cert_numbers_by_lc_action()
    {
        $search = false;
        $result = array();
        $from = '1.01.2015';
        $to = date('d.m.Y');
        $hc = null;
        $chosen_hc_name = null;
        $items = [
            '0' => [],
            '1' => [],
            '2' => [],
            '3' => [],
            '4' => [],
            '5' => [],
            '6' => [],
            '7' => [],
            '8' => [],
        ];
        $lc = null;
        $lc_list = array();
        $lc_caption = null;
        $fired = false;
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $search = true;
            $connection = Connection::getInstance();
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $hc = filter_input(INPUT_POST, 'hc');
            $lc = filter_input(INPUT_POST, 'lc');
            $centers = array();
            $all_centers = HeadCenters::getHeadOrgs();
            foreach ($all_centers as $cen) {
                $centers[] = $cen['id'];
            }
            $lc_list = Univesities::getByHORG($hc);
            $centers = array($hc);
            $sql_hc = ' AND sho.id='.$hc.' ';
            $chosen_hc_name = ' по "'.HeadCenter::getOrgName($hc).'"';
            $sql_hc .= ' AND su.id='.$lc.' ';
            $lc_caption = ' по "'.University::getByID($lc)->name.'"';
            $statest = array();
            foreach (Act::getInnerStates() as $st) {
                $statest[] = "'".$st."'";
            }
            $statest = implode(', ', $statest);
            $fired = true;
            $sql = sprintf(
                "
			SELECT  sap.blank_number AS numb, sat.level_id
	  FROM sdt_act_people sap 
	  LEFT JOIN sdt_act sa ON sa.id = sap.act_id 
	  LEFT JOIN sdt_act_test sat ON sap.test_id = sat.id
	  LEFT JOIN sdt_university su ON su.id = sa.university_id
	   LEFT JOIN sdt_head_center shc ON su.head_id = shc.id 
		LEFT JOIN sdt_head_org sho ON sho.id = shc.horg_id
		 WHERE sap.deleted = 0 AND sa.deleted = 0 
		 AND sho.id=%d
		  AND su.id=%d
		  AND sa.state IN (%s) 
		  AND sa.test_level_type_id = %d AND sap.blank_number IS NOT NULL AND 
		  sap.blank_number <> '' 
		  AND sap.document = 'certificate'
		   AND sa.created BETWEEN '%s  0:0:0' AND '%s' 
	
	UNION ALL 
	SELECT 
cd.certificate_number AS numb, sat.level_id
FROM certificate_duplicate cd
	  LEFT JOIN sdt_act_people sap  ON sap.id = cd.user_id
	  LEFT JOIN sdt_act sa ON sa.id = sap.act_id 
	  LEFT JOIN sdt_act_test sat ON sap.test_id = sat.id
	  LEFT JOIN sdt_university su ON su.id = sa.university_id
	   LEFT JOIN sdt_head_center shc ON su.head_id = shc.id 
		LEFT JOIN sdt_head_org sho ON sho.id = shc.horg_id
		 WHERE sap.deleted = 0 AND sa.deleted = 0 AND cd.deleted = 0
		 AND sho.id=%d
		  AND su.id=%d
		  AND sa.state IN (%s) 
		  AND sa.test_level_type_id = %d AND sap.blank_number IS NOT NULL AND 
		  sap.blank_number <> '' 
		  AND sap.document = 'certificate'
		   AND sa.created BETWEEN '%s  0:0:0' AND '%s  23:59:59' 
		   ",
                $hc,
                $lc,
                $statest,
                1,
                $connection->escape($this->mysql_date($from)),
                $connection->escape($this->mysql_date($to)),
                $hc,
                $lc,
                $statest,
                1,
                $connection->escape($this->mysql_date($from)),
                $connection->escape($this->mysql_date($to))
            );
            //die($sql);
            $res = $connection->query($sql);
            if (!empty($res)) {
                foreach ($res as $row) {
                    $items[RkiHelper::getLevelGroup($row['level_id'])][] = $row['numb'];
                }
//				var_dump($items);
                //	die;
            }
        }
        $hc_list = HeadCenters::getHeadOrgs();

        return $this->render->view(
            'root/statist/rki_cert_numbers_by_lc',
            array(
                'selected_hc' => $hc,
                'arrays' => $items,
                'search' => $search,
                'from' => $from,
                'to' => $to,
                'hc_list' => $hc_list,
                'fired' => $fired,
                'lc_list' => $lc_list,
                'lc_caption' => $lc_caption,
                'selected_lc' => $lc,
                'enable_lc' => true,
            )
        );
    }

    /**
     * @return string
     * Отчет 50
     */
    public function check_pfur_by_excel_action()
    {
        $search = false;
        $result = array();
        $from = '1.01.2015';
        $to = date('d.m.Y');
        if (isset($_FILES['report'])) {
            //var_dump($_FILES['report']);
            require_once dirname(__FILE__).'/template/view/root/statist/PHPExcel-1.8.1/Classes/PHPExcel.php';
            error_reporting(E_ALL);
            set_time_limit(0);
            $objPHPExcel = PHPExcel_IOFactory::load($_FILES['report']['tmp_name']);
            $sheet = $objPHPExcel->getActiveSheet();
            /*			$objPHPExcel->getActiveSheet()->setCellValueExplicit(
                'A8',
                "01513789642",
                PHPExcel_Cell_DataType::TYPE_STRING
            );
            */ /*$objPHPExcel->getActiveSheet()
                ->fromArray(
                    $arrayData,  // The data to set
                    NULL,        // Array values with this value will not be set
                    'C3'         // Top left coordinate of the worksheet range where
                                 //    we want to set these values (default is A1)
                );

                */ //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 8, 'Some value');
//$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, 8)->getValue();
            $result = [
                [
                    'Найдено',
                    'Номер документа',
                    'Номер бланка',
                    'Дата выдачи',
                    'Срок действия',
                    'Фамилия рус',
                    'Фамилия лат',
                    'Имя рус',
                    'Имя лат',
                    'Уровень',
                    'Дата тестирования',
                    'Тип документа',
                    'Серия',
                    'Номер',
                    'Дата выдачи',
                    'Подразделение',
                    'Место рождения',
                    'Дата рождения',
                    'Мигр карта серия',
                    'Мигр карта номер',
                    'Локальный центр',
                    'Гражданство',
                    'Дубликат',
                ],
            ];
            $result = [
                [
                    'Номер бланка',
                    'Дата выдачи',
                    'Полный номер паспорта',
                ],
            ];
            $highestRow = $sheet->getHighestRow(); // e.g. 10
            $highestColumn = $sheet->getHighestColumn(); // e.g 'F'
            //var_dump($sheet);
            //foreach($sheet->getRowIterator() as $row){
            //var_dump($row);
            //}
            //echo '<table class="table table-bordered">' . "\n";
            $statest = array();
            foreach (Act::getInnerStates() as $st) {
                $statest[] = "'".$st."'";
            }
            $statest = implode(', ', $statest);
            $hc_id = 1;
            $tempQuery = [
                '			DROP TABLE IF EXISTS temp_active_duplicates;',
                'CREATE TEMPORARY TABLE
  temp_active_duplicates
  (
  INDEX user_id (user_id),
  INDEX name (surname_rus, name_rus)
  )
  REPLACE
 AS
  SELECT
      cds.*
    FROM certificate_duplicate cds
  LEFT JOIN certificate_duplicate cd2 ON cds.user_id = cd2.user_id AND cds.id<cd2.id 
  WHERE cd2.id IS NULL AND cds.deleted = 0 AND cd2.deleted = 0;',
                'DROP TABLE IF EXISTS temp_active_duplicates2;',
                'CREATE TEMPORARY TABLE
  temp_active_duplicates2
  (
  INDEX user_id (user_id),
  INDEX name (surname_rus, name_rus)
  )
  REPLACE
 AS
  SELECT
      cds.*
    FROM certificate_duplicate cds
  LEFT JOIN certificate_duplicate cd2 ON cds.user_id = cd2.user_id AND cds.id<cd2.id
  WHERE cd2.id IS NULL AND cds.deleted = 0 AND cd2.deleted = 0;',
                'DROP TABLE IF EXISTS temp_pfur_certificates;',
                "CREATE TABLE temp_pfur_certificates 
  (
  INDEX search (fio,birth_date,long_password),
  INDEX search2 (birth_date,long_password)
  )
  AS
SELECT
   CONCAT(  TRIM(IF(cd.id IS NOT NULL, cd.surname_rus, sap.surname_rus)) ,' ',TRIM(IF(cd.id IS NOT NULL, cd.name_rus, sap.name_rus))) AS fio,
  sap.document_nomer,
  IF(cd.id IS NOT NULL, cd.certificate_number, sap.blank_number) AS blank_number,
  DATE_FORMAT(sap.blank_date, '%d.%m.%Y') AS blank_date,
  DATE_FORMAT(sap.valid_till, '%d.%m.%Y') AS valid_till,
  IF(cd.id IS NOT NULL, cd.surname_rus, sap.surname_rus) AS surname_rus,
  IF(cd.id IS NOT NULL, cd.surname_lat, sap.surname_lat) AS surname_lat,
  IF(cd.id IS NOT NULL, cd.name_rus, sap.name_rus) AS name_rus,
  IF(cd.id IS NOT NULL, cd.name_lat, sap.name_lat) AS name_lat,
  stl.caption AS test_level,
  DATE_FORMAT(sap.testing_date, '%d.%m.%Y') AS testing_date,
  sap.passport_name,
  sap.passport_series,
  sap.passport,
   CONCAT(sap.passport_series,sap.passport) AS long_password,
  DATE_FORMAT(sap.passport_date, '%d.%m.%Y') AS passport_date,
  sap.passport_department,
  sap.birth_place,
  DATE_FORMAT(sap.birth_date, '%d.%m.%Y') AS birth_date,
  sap.migration_card_series,
  sap.migration_card_number,
  su.name AS local_center,
  c.name AS country,

  IF(cd.id IS NOT NULL, 1, 0) AS is_dup
FROM sdt_act_people sap
  LEFT JOIN temp_active_duplicates cd
    ON cd.user_id = sap.id
  LEFT JOIN sdt_act_test sat
    ON sap.test_id = sat.id
  LEFT JOIN sdt_act sa
    ON sa.id = sap.act_id
  LEFT JOIN country c
    ON sap.country_id = c.id
  LEFT JOIN sdt_university su
    ON sa.university_id = su.id
  LEFT JOIN sdt_head_center shc
    ON su.head_id = shc.id
  LEFT JOIN head_center_text hct
    ON hct.head_id = shc.id
  LEFT JOIN sdt_test_levels stl
    ON stl.id = sat.level_id
WHERE sa.deleted = 0
AND sap.deleted = 0
AND shc.deleted = 0
AND sat.deleted = 0
AND shc.horg_id =  ".$hc_id."
AND sa.state IN (".$statest.") 
AND sap.document =  '".ActMan::DOCUMENT_CERTIFICATE."'
AND sa.test_level_type_id = 2
AND sap.blank_number <> ''
AND sap.document_nomer <> ''


GROUP BY sap.id",
            ];
//echo $tempQuery;
            $C = Connection::getInstance();
            foreach ($tempQuery as $t) {
                $C->execute($t);
            }
            $sql = 'SELECT blank_number, blank_date, long_password, fio,
if( long_password LIKE  \'%s\', 1, 0) AS pass_eq
			FROM temp_pfur_certificates
  WHERE fio LIKE \'%s%%\'
  AND 
   ( 
   birth_date LIKE \'%s\'
   OR 
   long_password LIKE  \'%s\'
   )

 ORDER BY pass_eq DESC, blank_date DESC
  LIMIT 1';
            //  and long_password like \'%s\'
            for ($row = 4; $row <= $highestRow; $row++) {
                //echo '<tr>' . "\n";
                $excel_name = $name = trim($this->utf_decode($sheet->getCellByColumnAndRow(1, $row)->getValue()));
                //	$bd=trim($this->utf_decode($sheet->getCellByColumnAndRow(2, $row)->getCalculatedValue())) ;
                $cellData = $sheet->getCellByColumnAndRow(2, $row)->getValue();
                $data = PHPExcel_Shared_Date::ExcelToPHP($cellData);
                $bd = trim(date('d.m.Y', $data));
                //var_dump($cellData, $data,$bd);die;
                $passport_ser = trim($this->utf_decode($sheet->getCellByColumnAndRow(4, $row)->getValue()));
                $passport_d = trim($this->utf_decode($sheet->getCellByColumnAndRow(5, $row)->getValue()));
                if ($passport_ser == '---') {
                    $passport_ser = '';
                }
                $passport = $passport_ser.$passport_d;
                //,$C->escape($passport)
                $query = sprintf(
                    $sql,
                    $C->escape($passport),
                    $C->escape($name),
                    $C->escape($bd),
                    $C->escape($passport)
                );
//					echo $query.'<br>';
                /* echo '<td>' .  $this->utf_decode(
                 $sheet->getCellByColumnAndRow(0, $row)->getValue()) . '</td>' . "\n";
                 echo '<td>' .  $this->utf_decode(
                 $sheet->getCellByColumnAndRow(1, $row)->getValue()) . '</td>' . "\n";
                 echo '<td>' . $sheet->getCellByColumnAndRow(2, $row)->getValue() . '</td>' . "\n";
                 echo '</tr>' . "\n";
                 */
//die($sql);
                $res = $C->query($query, true);
                if (is_null($res)) {
                    $word_res = preg_match_all('|\S+|', $name, $words);
                    $words = $words[0];
                    if ($word_res) {
                        $name = $words[0];
                        if (strlen($name) <= 3) {
                            $name .= ' '.$words[1];
                        }
                        $query = sprintf(
                            $sql,
                            $C->escape($passport),
                            $C->escape($name),
                            $C->escape($bd),
                            $C->escape($passport)
                        );
                        $res = $C->query($query, true);
                        if ($res) {
                            unset($res['pass_eq']);
                            if ($res['long_password'] == $passport) {
                                //unset($res['long_password']);
                                $res['long_password'] = '';
                            } else {
                                $res[] = 'Паспорт не совпадает';
                            }
                            if ($excel_name != $res['fio']) {
                                $res[] = 'ФИО не совпадает';
                            }
                            $result[$row] = $res;
                            continue;
                        } else {
                            $sql_passport = 'SELECT blank_number, blank_date, long_password, fio 
			FROM temp_pfur_certificates
  WHERE 
   
   
   birth_date LIKE \'%s\'
   AND 
   long_password LIKE  \'%s\'
   

 ORDER BY  blank_date DESC
  LIMIT 1';
                            $query = sprintf($sql_passport, $C->escape($bd), $C->escape($passport));
                            $res = $C->query($query, true);
                            if ($res) {
                                if ($res['long_password'] == $passport) {
                                    $res['long_password'] = '';
                                } else {
                                    $res[] = 'Паспорт не совпадает';
                                }
                                if ($excel_name != $res['fio']) {
                                    $res[] = 'ФИО не совпадает';
                                }
                                $result[$row] = $res;
                                continue;
                            }
                        }
                    }
                    $result[$row] = ['НЕ НАЙДЕН'];
                } else {
                    if ($res['long_password'] == $passport) {
                        //unset($res['long_password']);
                        $res['long_password'] = '';
                    } else {
                        $res[] = 'Паспорт не совпадает';
                    }
                    unset($res['pass_eq']);
                    unset($res['fio']);
                    $result[$row] = $res;
                }
            }
            //die;
//			var_dump($result);
            //echo '</table>' . "\n";
            $sheet->fromArray(
                $this->recursive_utf_encode($result),  // The data to set
                null,        // Array values with this value will not be set
                'G3'         // Top left coordinate of the worksheet range where
            //    we want to set these values (default is A1)
            );
            //$excel = new PHPExcel();
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.date('r').'.xlsx"');
            header('Cache-Control: max-age=0');
            $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $writer->save('php://output');
            die;
            //var_dump($sheetData);
        }

        return $this->render->view(
            'root/statist/check_pfur_by_excel',
            []
        );
    }

    public function recursive_utf_encode($array)
    {
        foreach ($array as &$item) {
            if (is_array($item)) {
                $item = $this->recursive_utf_encode($item);
            } else {
                $item = $this->encode($item);
            }
        }

        return $array;
    }

    /**
     * Отчет 47
     * Отчет 48
     * @return string
     */
    public function report_count_exam_federal_action()
    {
        $search = false;
        $res = array();
        $from = '1.01.2015';
        $to = date('d.m.Y');
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $search = true;
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $st = new CountExamFederal(1);
            $res = $st->execute($from, $to);
        }

        return $this->render->view(
            'root/statist/count_exam_federal',
            array(
                'array' => $res,
                'search' => $search,
                'from' => $from,
                'to' => $to,
                'caption' => 'Статистика для министерства по РУДН - группировка - федеральный округ, субъект, локальный центр по экзамену',
            )
        );
    }

    /**
     * Отчет 48
     * Отчет 49
     * @return string
     */
    public function report_count_exam_federal_msu_action()
    {
        $search = false;
        $res = array();
        $from = '1.01.2015';
        $to = date('d.m.Y');
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $search = true;
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $st = new CountExamFederal(2);
            $res = $st->execute($from, $to);
        }

        return $this->render->view(
            'root/statist/count_exam_federal',
            array(
                'array' => $res,
                'search' => $search,
                'from' => $from,
                'to' => $to,
                'caption' => 'Статистика для министерства по МГУ - группировка - федеральный округ, субъект, локальный центр по экзамену',
            )
        );
    }

    public function report_money_by_dogovors_action()
    {
        $search = false;
        $res = array();
        $from = '1.01.2017';
        $to = date('d.m.Y');
        $head_id = null;
        $level_type = 1;
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to') && filter_input(
                INPUT_POST,
                'level_type',
                FILTER_VALIDATE_INT
            ) && filter_input(INPUT_POST, 'head_id', FILTER_VALIDATE_INT)) {
            $search = true;
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $head_id = filter_input(INPUT_POST, 'head_id', FILTER_VALIDATE_INT);
            $level_type = filter_input(INPUT_POST, 'level_type', FILTER_VALIDATE_INT);
            $st = new MoneyByDogovors();
            $res = $st->execute($from, $to, $level_type, $head_id);
//            echo '<pre>'; die(var_dump($res));
        }
        $hc_list = HeadCenters::getAll();

        return $this->render->view(
            'root/statist/money_by_dogovors',
            array(
                'array' => $res,
                'search' => $search,
                'from' => $from,
                'level_type' => $level_type,
                'head_id' => $head_id,
                'to' => $to,
                'hc_list' => $hc_list,
                'caption' => 'Отчет по контрагентам',
            )
        );
    }

    public function report_money_by_citizen_action()
    {
        $search = false;
        $res = array();
        $from = '1.01.2017';
        $to = date('d.m.Y');
        $head_id = null;
        $level_type = 1;
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to') && filter_input(
                INPUT_POST,
                'level_type',
                FILTER_VALIDATE_INT
            )//  && filter_input(INPUT_POST, 'head_id', FILTER_VALIDATE_INT)
        ) {
            $search = true;
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $head_id = filter_input(INPUT_POST, 'head_id', FILTER_VALIDATE_INT);
            $level_type = filter_input(INPUT_POST, 'level_type', FILTER_VALIDATE_INT);
            $st = new MoneyByCitizen();
            $res = $st->execute(
                $from,
                $to,
                $level_type,
                $head_id,
                filter_input(INPUT_POST, 'citizen', FILTER_VALIDATE_INT)
            );
//            echo '<pre>'; die(var_dump($res));
        }
        $hc_list = HeadCenters::getHeadOrgs();

        return $this->render->view(
            'root/statist/money_by_citizen',
            array(
                'array' => $res,
                'search' => $search,
                'from' => $from,
                'level_type' => $level_type,
                'head_id' => $head_id,
                'to' => $to,
                'hc_list' => $hc_list,
                'caption' => 'Отчет с деньгами по гражданству',
            )
        );
    }

    public function report_peoples_by_lc_action()
    {
        $search = false;
        $res = array();
        $from = '1.01.2017';
        $to = date('d.m.Y');
        $lc = null;
        $hc = null;
        $head_id = null;
        $contract = null;
        $level_type = 2;
        $lc_list = array();
        $levelTypes = TestLevelTypes::getAll();
        $localCenter = null;
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to') && filter_input(
                INPUT_POST,
                'level_type',
                FILTER_VALIDATE_INT
            ) && filter_input(INPUT_POST, 'lc', FILTER_VALIDATE_INT)) {
            $search = true;
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $lc = filter_input(INPUT_POST, 'lc', FILTER_VALIDATE_INT);
            $level_type = filter_input(INPUT_POST, 'level_type', FILTER_VALIDATE_INT);
            $contract = filter_input(INPUT_POST, 'contract', FILTER_VALIDATE_INT);
            $localCenter = University::getByID($lc);
            $hc = $localCenter->getHeadCenter();
            $head_id = $hc->id;
            $st = new people_by_lc();
            $res = $st->execute($level_type, $from, $to, $lc, $contract);
//            echo '<pre>'; die(var_dump($res));
            $lc_list = Univesities::getByHead($hc, true);
        }
        $hc_list = HeadCenters::getAll();
        $hc_list = HeadCenters::getHeadOrgs();

        return $this->render->view(
            'root/statist/report_peoples_by_lc',
            array(
                'array' => $res,
                'search' => $search,
                'from' => $from,
                'level_type' => $level_type,
                'hc' => $hc,
                'levelTypes' => $levelTypes,
                'to' => $to,
                'hc_list' => $hc_list,
                'lc_list' => $lc_list,
                'head_id' => $head_id,
                'caption' => 'Отчет по контрагентам',
            )
        );
    }


    public function invalid_certs_action()
    {
        $certs = CertificatesInvalid::getAll();

        return $this->render->view(
            'root/invalid_certs',
            array(
                'certs' => $certs,
//                'bso' => $bso
            )
        );
    }

    public function ukraine_dont_change_price_action()
    {
        $search = false;
        $res = array();
        $from = '1.01.2017';
        $to = date('d.m.Y');
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $search = true;
            $st = new \SDT\statistic\ukraine_dont_change_price();
            $res = $st->execute($from, $to, 500);
        }

        return $this->render->view(
            'root/statist/ukraine_dont_change_price',
            array(
                'array' => $res,
                'search' => $search,
                'from' => $from,
                'to' => $to,
                'caption' => 'Отчет по контрагентам',
            )
        );
    }

    public function lc_by_dogovor_date_action()
    {
        $search = false;
        $res = array();
        $date = date('d.m.Y');
        if (filter_input(INPUT_POST, 'date')) {
            $date = filter_input(INPUT_POST, 'date');
            $search = true;
            $st = new \SDT\statistic\lc_by_dogovor_date();
            $res = $st->execute($date);
        }

        return $this->render->view(
            'root/statist/lc_by_dogovor_date',
            array(
                'array' => $res,
                'search' => $search,
                'date' => $date,
            )
        );
    }

    public function report_lc_testing_action()
    {
        $search = false;
        $result = array();
        $from = '1.01.2016';
        $to = date('d.m.Y');
        $hc = null;
        $chosen_hc_name = null;
        $personal_1 = $personal_2 = $personal_3 = $personal_4 = 0;
        /*временно
        $from = '1.01.2016';
        $to = '31.01.2016';
        */
        $lc = null;
        $lc_list = array();
        $lc_caption = null;
        $res = '';
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $search = true;
            $connection = Connection::getInstance();
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $hc = filter_input(INPUT_POST, 'hc');
            $types = [];
            if (filter_input(INPUT_POST, 'type_1')) {
                $types[] = 'sa.test_level_type_id = 1';
            }
            if (filter_input(INPUT_POST, 'type_2')) {
                $types[] = 'sa.test_level_type_id = 2';
            }
            if (!$types) {
                $types[] = 'sa.test_level_type_id = 1';
                $types[] = 'sa.test_level_type_id = 2';
            }
            $types_sql = implode(' or ', $types);
            $personal_1 = filter_input(INPUT_POST, 'personal_1');
            $personal_2 = filter_input(INPUT_POST, 'personal_2');
            $personal_3 = filter_input(INPUT_POST, 'personal_3');
            $personal_4 = filter_input(INPUT_POST, 'personal_4');
            $centers = array();
            $all_centers = HeadCenters::getHeadOrgs();
            foreach ($all_centers as $cen) {
                $centers[] = $cen['id'];
            }
            if ($hc && is_numeric($hc)) {
                $lc = filter_input(INPUT_POST, 'lc');
                $lc_list = Univesities::getByHORG($hc);
                $centers = array($hc);
                $sql_hc = ' AND sho.id='.$hc.' ';
                $chosen_hc_name = HeadCenter::getOrgName($hc);
            } else {
                $chosen_hc_name = '';
                $sql_hc = ' and sho.id in ('.implode(",", $centers).') ';
            }
            if ($hc && $lc && is_numeric($lc)) {
                $sql_hc .= ' AND su.id='.$lc.' ';
                $lc_caption = '"'.University::getByID($lc)->name.'"';
            }
            $statest = array();
            foreach (Act::getInnerStates() as $st) {
                $statest[] = "'".$st."'";
            }
            $statest = implode(', ', $statest);
            $sql = "
SELECT 

sap.*,



sap.id as id, 

IF(cd.id IS NOT NULL,
	(
SELECT cds.surname_rus
FROM certificate_duplicate cds
WHERE cds.user_id=sap.id AND cds.deleted = 0
ORDER BY cds.id DESC
LIMIT 1),sap.surname_rus) AS surname_rus, 

IF(cd.id IS NOT NULL,
	(
SELECT cds.surname_lat
FROM certificate_duplicate cds
WHERE cds.user_id=sap.id AND cds.deleted = 0
ORDER BY cds.id DESC
LIMIT 1),sap.surname_lat) AS surname_lat, 

IF(cd.id IS NOT NULL,
	(
SELECT cds.name_rus
FROM certificate_duplicate cds
WHERE cds.user_id=sap.id AND cds.deleted = 0
ORDER BY cds.id DESC
LIMIT 1),sap.name_rus) AS name_rus, 

IF(cd.id IS NOT NULL,
	(
SELECT cds.name_lat
FROM certificate_duplicate cds
WHERE cds.user_id=sap.id AND cds.deleted = 0
ORDER BY cds.id DESC
LIMIT 1),sap.name_lat) AS name_lat, 

IF(cd.id IS NOT NULL,
	(
SELECT cds.certificate_number
FROM certificate_duplicate cds
WHERE cds.user_id=sap.id AND cds.deleted = 0
ORDER BY cds.id DESC
LIMIT 1),sap.blank_number) AS blank_number,
	
	
	
	co.name AS country,
	/*sap.document_nomer AS document_nomer, 
	sap.blank_date AS blank_date,
	
	
	
	
	
	sap.birth_date AS birth_date,
	sap.birth_place AS birth_place,
	
	sap.passport_name AS passport_name,
	sap.passport_series AS passport_series,
	sap.passport AS passport,
	sap.passport_date AS passport_date,
	sap.passport_department AS passport_department,*/
	
	stl.caption AS test_level,
	stl.id AS test_level_id
	
	
	
FROM 
sdt_act_people sap
LEFT JOIN certificate_duplicate cd ON cd.user_id=sap.id AND cd.deleted = 0
LEFT JOIN sdt_act_test sat ON sap.test_id = sat.id
LEFT JOIN sdt_act sa ON sa.id = sat.act_id
LEFT JOIN sdt_university su ON sa.university_id = su.id
LEFT JOIN sdt_head_center shc ON su.head_id = shc.id
LEFT JOIN sdt_head_org sho ON sho.id = shc.horg_id


LEFT JOIN country co on co.id=sap.country_id
LEFT JOIN sdt_test_levels stl on stl.id=sat.level_id

WHERE sap.blank_date BETWEEN '".$connection->escape($this->mysql_date($from))." 0:0:0' AND '".$connection->escape(
                    $this->mysql_date($to)
                )." 23:59:59'
AND (".$types_sql.") 
AND sa.deleted = 0 
AND sap.deleted = 0 
AND sat.deleted = 0
AND shc.deleted = 0
AND sho.deleted = 0
".$sql_hc."
AND sa.state IN (".$statest.")
GROUP BY sap.id
ORDER BY surname_rus ASC, name_rus asc, surname_lat asc, name_lat asc

            ";
//die($sql);
            $res = $connection->query($sql);
        }
//        else $lc_list = Univesities::getByHORG(1);
//        $regions_list = Countries::getAll4Form();
        $hc_list = HeadCenters::getHeadOrgs();
        if (empty($lc_list)) {
            $lc_list = Univesities::getByHORG($hc_list[0]['id']);
        }

        //die(var_dump($hc_list[0]['id']));
        return $this->render->view(
            'root/statist/report_lc_testing',
            array(
                'selected_hc' => $hc,
//                'arrays' => $result,
                'search' => $search,
                'from' => $from,
                'to' => $to,
//                'regions_list' => $regions_list,
                'hc_list' => $hc_list,
                'caption' => 'ФИО, номера, даты и т.п. получивших справки и сертификаты ',
                'lc_list' => $lc_list,
                'lc_caption' => $lc_caption,
                'selected_lc' => $lc,
                'enable_lc' => true,
                'res' => $res,
                'chosen_hc_name' => $chosen_hc_name,
                'personal_1' => $personal_1,
                'personal_2' => $personal_2,
                'personal_3' => $personal_3,
                'personal_4' => $personal_4,
            )
        );
    }

    /* protected function adm_user_list_action()
     {
         $users = Users::getAll();

         return $this->render->view(
             'root/adm_user_list',
             array(

                 'users' => $users
             )
         );
     }*/
    public function lc_not_testing_action()
    {
        $search = false;
        $result = array();
        $from = date('1.1.Y');
        $hc_list = HeadCenters::getHeadOrgs();
        $selected = null;
        if (filter_input(INPUT_POST, 'from')) {
//            var_dump('sdf');
            $from = filter_input(INPUT_POST, 'from');
            $selected = filter_input(INPUT_POST, 'hc');
            $search = true;
            $report = new LcNotWorking();
            $result = $report->execute($from, $selected);
//            var_dump($result);die;
        }

        return $this->render->view(
            'root/statist/lc_not_testing',
            array(
                'search' => $search,
                'from' => $from,
                'hcs' => $hc_list,
                'hc' => $selected,
                'result' => $result,
            )
        );
    }

    /**
     * Информация о прохождении комплексного экзамена по локальным центрам РУДН  с указанием страны и региона
     * @return string
     */
    public function minobr_pfur_local_report_exam_with_country_action()
    {
        $search = false;
        $result = array();
        $from = '1.01.'.date('Y');
        $to = date('d.m.Y');
        $region = null;
        $toDate = date('Y');
        $fromDate = 2015;
        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $search = true;
            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $region = filter_input(INPUT_POST, 'region', FILTER_VALIDATE_INT);
            $fromDate = date('Y', strtotime($from));
            $toDate = date('Y', strtotime($to));
            $rep = new minobr_pfur_local_report_exam_with_country();
            $result = $rep->execute($from, $to);
            /*  $sql = 'select count(*) as cc, head_id from sdt_university where deleted=0 group by head_id';
              $res = $connection->query($sql);

              foreach ($res as $item) {
                  if (!array_key_exists($item['head_id'], $gc)) {
                      continue;
                  }

                  $cr = &$result[$gc[$item['head_id']]]['data'];

                  $cr['orgs'] += $item['cc'];
              }*/ //echo '<pre>';
//            die(var_dump($result));
        }

        return $this->render->view(
            'root/statist/minobr_pfur_local_report_exam_with_country',
            array(
                'fromDate' => $fromDate,
                'toDate' => $toDate,
                'array' => $result,
                'search' => $search,
                'from' => $from,
                'to' => $to,
                'region' => $region,
                'regions' => Regions::getSorted(),
                'caption' => 'Информация о прохождении комплексного экзамена по РУДН по локальным центрам c разбивкой на регионы и страны',
            )
        );
    }

    protected function access_restricted_action()
    {
        return $this->render->view('access_restricted');
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

    protected function test_levels_action()
    {
        $all_levels = filter_input(INPUT_GET, 'all', FILTER_VALIDATE_INT);
        if ($all_levels) {
            $univers = TestLevels::getAll();
        } else {
            $univers = array_filter(
                TestLevels::getAll()->getArrayCopy(),
                function (TestLevel $univer) {
                    return $univer->is_publicated;
                }
            );
        }

        return $this->render->view(
            'test_levels/all',
            array(
                'list' => $univers,
                'all_levels' => $all_levels,
            )
        );
    }

    protected function test_levels_add_action()
    {
        $count = $this->getNumeric('count');
        $univer = new TestLevel();
        $univer->subtest_count = $count;
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
    }

    protected function act_add_action()
    {
        $act = new Act();
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
                'University' => University::getByID($_SESSION['univer_id']),
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
                'University' => University::getByID($_SESSION['univer_id']),
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

    protected function act_invalid_action()
    {
        $univer = Act::getByID($_GET['id']);
        $univer->delete();
        //  $this->redirectByAction('act_fs_list');
        $this->redirectReturn();
    }

    protected function sess_invalid_action()
    {
        $univer = Act::getByID($_GET['id']);
        writeStatistic(
            'sdt',
            'session_invalid',
            array(
                'id' => $univer->id,
            )
        );
        $univer->delete();
        $_SESSION['flash'] = "Тестовая сессия отмечена недействительная";
        //  $this->redirectByAction('act_fs_list');
        $this->redirectReturn();
    }

    protected function act_test_add_action()
    {
        $actTest = new ActTest();
        $actTest->act_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (count($_POST)) {
            $actTest->parseParameters($_POST);
            $actTest->people_subtest_retry = $actTest->people_retry;
            $prices = ChangedPriceTestLevel::checkPricebyLevel($actTest->act_id, $_POST['level_id']);
            $actTest->price = $prices->price;
            $actTest->price_subtest_retry = $prices->sub_test_price;
            $id = $actTest->save();
            $_SESSION['flash'] = "Добавлено тестирование";
            $this->redirectByAction('act_fs_view', array('id' => $actTest->act_id));
        }

        return $this->render->view(
            'acts/forms/act_test',
            array(
                'Act' => $actTest,
                'Legend' => 'Добавить тестирование',
                'Levels' => TestLevels::getAvailable4Act($actTest),
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


    /*  public function groups_list_action()
      {

          $id = CURRENT_HEAD_CENTER;
          $univer = Groups::getAll();
          /*if (!$univer) {
              $this->redirectByAction('head_center');
          }*/
    /* return $this->render->view('root/groups_list', array('object' => $univer));

 }*/
    /*  public function groups_add_action()
      {

          $id = CURRENT_HEAD_CENTER;
          $univer = HeadCenter::getByID($id);
          /*if (!$univer) {
              $this->redirectByAction('head_center');
          }*/
    /*   return $this->render->view('root/groups_list', array('object' => $univer));

   }*/
    protected function act_man_print_pril_cert_action()
    {
        $man = ActMan::getByID($_GET['id']);
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


    /*public function groups_edit_action()
    {

        $id = CURRENT_HEAD_CENTER;
        $univer = HeadCenter::getByID($id);
        /*if (!$univer) {
            $this->redirectByAction('head_center');
        }*/
    /* return $this->render->view('root/groups_edit', array('object' => $univer));

 }*/
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
    protected function act_vidacha_cert_action()
    {
        $act = Act::getByID($_GET['id']);
        $this->checkActIsEditable($act);
        die(
        $this->render->view(
            'acts/print/vidacha_cert',
            array(
                'Act' => $act,
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
        /* $man = ActMan::getByID($_GET['id']);
         $man->blank_number = $man->id;
         //  $man->document_nomer = $man->id;
         $man->save();
         $man->getAct()->save();

         die($this->render->view(
             'acts/print/rki',
             array(
                 'Man' => $man,
             )
         ));*/
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }
        $persons = array();
        $Act = Act::getByID($_GET['id']);
        /* foreach ($Act->getPeople() as $man) {
             if ($man->document != 'note') continue;
             $man->blank_number = $man->id;
             $man->save();
             $man->getAct()->save();
             $persons[] = $man;
         }


         die($this->render->view(
             'acts/print/rkis',
             array(
                 //'Man' => $man,
                 'persons' => $persons,

             )
         ));

         */
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
        $archiveResult = [];
        $query = $certificate = $name = $birthday = '';
        if (count($_POST)) {
            /*            $query = $_POST['query'];
                        $certificate = $_POST['certificate'];
            //            $blank = $_POST['blank'];
                        $result = ActPeople::Search($query, $certificate);*/
            $query = filter_input(INPUT_POST, 'query', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $certificate = filter_input(
                INPUT_POST,
                'certificate',
                FILTER_SANITIZE_STRING,
                FILTER_FLAG_NO_ENCODE_QUOTES
            );
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $birthday = filter_input(INPUT_POST, 'birthday', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
//            $blank = $_POST['blank'];
            $result = ActPeople::Search($query, $certificate, $name, $birthday);

            $archiveSearch = new \SDT\models\Archive\People();
            $archiveResult = $archiveSearch->search(
                [
                    'surname' => $query,
                    'name' => $name,
                    'cert' => $certificate,
                    'birthday' => $birthday,
                ]
            );
        }

        return $this->render->view(
            'search/pupil',
            array(
                'Result' => $result,
                'query' => $query,
                'certificate' => $certificate,
                'name' => $name,
                'birthday' => $birthday,
                'archive' => $archiveResult,
            )
        );
    }

    protected function search_pupil_range_annul_action()
    {
        $result = array();
        $type = $certificate = $certs_count = '';
        if (count($_POST)) {
            /*            $query = $_POST['query'];
                        $certificate = $_POST['certificate'];
            //            $blank = $_POST['blank'];
                        $result = ActPeople::Search($query, $certificate);*/
            $type = filter_input(INPUT_POST, 'type', FILTER_VALIDATE_INT);
            $certificate = filter_input(
                INPUT_POST,
                'certificate',
                FILTER_SANITIZE_STRING,
                FILTER_FLAG_NO_ENCODE_QUOTES
            );
//            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
//            $blank = $_POST['blank'];
            $certs_count = count(explode(" ", $certificate));
            $result = ActPeople::Search_by_range($certificate, $type);
        }
        if (empty($result[0])) {
            $list = array();
        } else {
            $list = $result[0];
        }
        if (empty($result[1])) {
            $certs_count = array();
        } else {
            $certs_count = $result[1];
        }

        return $this->render->view(
            'search/pupil_range_annul',
            array(
                'Result' => $list,
                'certs_count' => $certs_count,
                'certificate' => $certificate,
                'type' => $type,
            )
        );
    }

    protected function annul_cert_action()
    {
        if (empty($_POST['id']) || !is_numeric($_POST['id'])) {
            $this->redirectReturn();
        }
        $man = ActMan::getByID($_POST['id']);
        $reason = filter_input(INPUT_POST, 'reason', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        $reason = $this->utf_decode($reason);
        $annul_date = $this->mysql_date($_POST['annul_date']);
        $annul_cert = new AnnulCert();
        $annul_cert->man_id = $man->id;
//            $annul_cert->created='';
        $annul_cert->level_type_id = $man->getAct()->test_level_type_id;
        $annul_cert->blank_id = $man->getBlankID();
        $annul_cert->user_id = $_SESSION['u_id'];
        $annul_cert->blank_number = $man->getBlank_number();
        $annul_cert->reg_number = $man->document_nomer;
        $annul_cert->date_annul = $annul_date;
        $annul_cert->man_name_ru = $man->getName_rus();
        $annul_cert->man_surname_ru = $man->getSurname_rus();
        $annul_cert->man_name_en = $man->getName_lat();
        $annul_cert->man_surname_en = $man->getSurname_lat();
        $annul_cert->reason = $reason;
        if ($annul_cert->save()) {
            $man->emptyBlankFields();
            $man->save();
        }
    }

    protected function annul_cert_by_range_action()
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        if (empty($id)) {
            $this->redirectReturn();
        }
//        var_dump($id);die();
        $blanks_id = explode(",", $id);
        $reason = filter_input(INPUT_POST, 'reason', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        $reason = $this->utf_decode($reason);
        $annul_date = $this->mysql_date($_POST['annul_date']);
        foreach ($blanks_id as $blank) {
            $man = ActMan::getByID($blank);
            $annul_cert = new AnnulCert();
            $annul_cert->man_id = $man->id;
//            $annul_cert->created='';
            $annul_cert->level_type_id = $man->getAct()->test_level_type_id;
            $annul_cert->blank_id = $man->getBlankID();
            $annul_cert->user_id = $_SESSION['u_id'];
            $annul_cert->blank_number = $man->getBlank_number();
            $annul_cert->reg_number = $man->document_nomer;
            $annul_cert->date_annul = $annul_date;
            $annul_cert->man_name_ru = $man->getName_rus();
            $annul_cert->man_surname_ru = $man->getSurname_rus();
            $annul_cert->man_name_en = $man->getName_lat();
            $annul_cert->man_surname_en = $man->getSurname_lat();
            $annul_cert->reason = $reason;
            if ($annul_cert->save()) {
                $man->emptyBlankFields();
                $man->save();
            }
        }
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

    protected function search_pupil_fms_action()
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


    /*public function groups_edit_action()
    {

        $id = CURRENT_HEAD_CENTER;
        $univer = HeadCenter::getByID($id);
        /*if (!$univer) {
            $this->redirectByAction('head_center');
        }*/
    /* return $this->render->view('root/groups_edit', array('object' => $univer));

 }*/
    protected function otch_country_fms_action()
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
        $file = File::getByHash(filter_input(INPUT_GET, 'hash'));
        $fname = filter_input(INPUT_GET, 'fname');
        if (!$file) {
            die('Файл не существует');
        }
        $file->download($fname);
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
            'act_num' => null,
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

    protected function search_act_fms_action()
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

    protected function act_passport_action()
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
        $this->redirectReturn();
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
        $this->redirectReturn();
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
        $this->checkActIsEditable($act);

        return $this->render->view(
            'acts/received/view',
            array(
                'object' => $act,
            )
        );
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
        $this->checkActIsEditable($act);
        if (count($_POST)) {
            foreach ($_POST['user'] as $id => $params) {
                $man = ActMan::getByID($id);
                $man->parseParameters($params);
                $man->save();
            }
            //  $act->updateDocumentsCountLeft();
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
        die(
        $this->render->view(
            'acts/print_check',
            array(
                'Act' => $act,
            )
        )
        );
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
        $signings = ActSignings::get4Invoice();

        return $this->render->view(
            'acts/archive/list',
            array(
                'list' => $acts,
                'signings' => $signings,
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

    protected function print_certificate_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }
        $persons = array();
//        $persons[] = $_GET['id'];
        $persons[] = ActMan::getByID($_GET['id']);
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

        return $this->render->view(
            'acts/act_check/level_view',
            array(
                'object' => $act,
            )
        );
    }

    protected function act_table_second_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }
        $act_id = $_GET['id'];
        $act = Act::getByID($act_id);
        $this->checkActIsEditable($act);
        // $people = $act->getPeople();
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
                'University' => University::getByID($act->university_id),
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
                'legend' => 'На оформлении',
            )
        );
    }

    protected function deleted_list_action()
    {
//        $acts = Acts::getListByLevel4Head(Act::STATE_CHECKED);
        $acts = Acts::getListDeleted();

//        var_dump($acts);
        return $this->render->view(
            'acts/list_deleted',
            array(
                'list' => $acts,
                'legend' => 'Недействительные',
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
        if ($is_head == 1) {
            $print = 'act_head';
        } else {
            $print = 'act';
        }
        if (!$signer->id) {
            $this->redirectReturn();
        }
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

    protected function act_table_print_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }
        $act = Act::getByID($_GET['id']);
        die(
        $this->render->view(
            'acts/third/table',
            array(
                'act' => $act,
            )
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

    protected function add_user_list_fms_action()
    {
        $users = Users::getAll();
        $types = UserTypes::getAll();

        return $this->render->view(
            'root/add_user_fms',
            array(
                'users' => $users,
                'list' => $types,
            )
        );
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

    protected function edit_user_list_fms_action()
    {
//        $users = Users::getAll();
//        var_dump($_SESSION);
        if (!empty($_SESSION['privelegies']['fms_admin']) && $_SESSION['u_id'] != MAINEST_FMS_ADMIN_ID) {
            $users = FmsRegionsUsers::getAll($_SESSION['u_id']);
        } else {
            $users = FmsRegionsUsers::getAll();
        }
        $new_list = array();
        foreach ($users as $user) {
            $new_list[$user->user_type_id][] = $user;
        }
        $types = UserTypes::getAll();

        return $this->render->view(
            'root/edit_user_fms',
            array(
                'users' => $users,
                'list' => $types,
                'new_list' => $new_list,
            )
        );
    }

    protected function act_upload_dogovor_scan_action()
    {
        $dogovor = University_dogovor::getByID(@$_POST['id']);
        if (!$dogovor) {
            $this->redirectAccessRestricted();
        }
        $_SESSION['CURRENT_HEAD_CENTER'] = University::getByID($dogovor->university_id)->getHeadCenter()->id;
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

    protected function head_center_action()
    {
//var_dump($_SESSION);die;
        $univers = HeadCenters::getAll();

        return $this->render->view(
            'head_center/all',
            array(
                'list' => $univers,
            )
        );
    }

    protected function head_center_prefixes_action()
    {
//var_dump($_SESSION);die;
        $univers = HeadCenters::getAll();

        return $this->render->view(
            'head_center/prefixes',
            array(
                'list' => $univers,
            )
        );
    }

    protected function head_center_add_action()
    {
        $univer = new HeadCenter();
        if (count($_POST)) {
            $univer->parseParameters($_POST);
            mysql_query('INSERT INTO sdt_head_org  (`captoin`) VALUES ("'.$univer->short_name.'")');
            $univer->horg_id = mysql_insert_id();
            $univer->save();
            // die(var_dump($univer));
            $params = array('id' => $univer->id);
            $this->redirectByAction('head_center_view', $params);
        }

        return $this->render->form('add', $univer, 'Добавление  головного центра');
    }

    protected function head_center_edit_action()
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
            $_SESSION['flash'] = 'Уровень тестирования удален';
            $this->redirectByAction('current_head_center_view', $params);
        }

        return $this->render->form('add', $hc, 'Редактирование информации о головном центре');
    }

    protected function current_head_center_edit_text_action()
    {
        $h_id = check_id($_GET['h_id']);
        $univer = HeadCenterText::getByHeadCenterID($h_id);
//        die(var_dump($univer));
        if (!$univer) {
            $this->redirectAccessRestricted();
        }
        if (count($_POST)) {
            $univer->parseParameters($_POST);
            $univer->save();
            $params = array('id' => $univer->id, 'h_id' => $h_id);
            $this->redirectByAction('current_head_center_text_view', $params);
        }

        return $this->render->form('add', $univer, 'Редактирование текстов головного центра');
    }

    protected function current_head_center_text_view_action()
    {
        $id = check_id($_GET['h_id']);
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

    protected function groups_list_action()
    {
        $groups = Groups::getAll();

        return $this->render->view(
            'root/groups_list',
            array(
                'list' => $groups,
            )
        );
    }

    protected function groups_add_action()
    {
        $group = new Group();
        if (count($_POST)) {
            $group->parseParameters($_POST);
            $group->save();
            $this->redirectByAction('groups_list');
        }

        return $this->render->form('add', $group, 'Добавление роли');
    }

    protected function groups_edit_action()
    {
        $group = Group::getByID($_GET['id']);
        if (count($_POST)) {
            $group->parseParameters($_POST);
            $group->save();
            $this->redirectByAction('groups_list');
        }

        return $this->render->form('add', $group, 'Редактирование группы');
    }

    protected function groups_delete_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }
        $group = Group::getByID($_GET['id']);
        $group->delete();
        $_SESSION['flash'] = 'Группа удалена';
        $this->redirectReturn();
    }

    protected function head_center_view_action()
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
    }

    protected function head_center_delete_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }
        $univer = HeadCenter::getByID($_GET['id']);
        if (!$univer) {
            $this->redirectByAction('head_center');
        }
        $univer->delete();
        if (empty(HeadCenters::getStatistHCByHO($univer->horg_id))) {
            mysql_query('UPDATE sdt_head_org SET deleted = 1 WHERE id = '.$univer->horg_id);
        }
        $this->redirectByAction('head_center');
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

    protected function acts_to_archive_list_action()
    {
        $array = array();
        $head_centers = HeadCenters::getAll();
        foreach ($head_centers as $hc) {
            $array[$hc->id]['id'] = $hc->id;
            $array[$hc->id]['name'] = $hc->name;
            $array[$hc->id]['acts'] = array();
            if (!empty($_POST['till'])) {
                $till = $_POST['till'];
            } else {
                if (!empty($_GET['till'])) {
                    $till = $_GET['till'];
                } else {
//                    $till = '';
                    $till = date('d.m.Y');
                }
            }
            $hc_acts = Acts::getListByHead($hc->id, $till);
            foreach ($hc_acts as $act) {
                //if ($act->isToArchive())
                //{
                $array[$hc->id]['acts'][] = $act->id;
                //}
            }
        }

        return $this->render->view(
            'root/acts_to_archive_list',
            array(
                'array' => $array,
                'till' => $till,
            )
        );
    }

    protected function set_archive_by_head_action()
    {
        $id = intval($_GET['id']);
        $counter = 0;
        if (!empty($_GET['till'])) {
            $till = $_GET['till'];
        } else {
            $till = '';
            $_SESSION['flash'] = 'Невозможно отправить в архив - НЕ ВЫБРАНА ДАТА';
//        $this->redirectReturn();
            $this->redirectByAction('acts_to_archive_list', array('till' => $till));
        }
        $hc_acts = Acts::getListByHead($id, $till);
        $list = array();
        foreach ($hc_acts as $act) {
            // if ($act->isToArchive())
            //{
            $list[] = $act->id;
            $act->setState(Act::STATE_ARCHIVE);
            $act->save();
            $counter++;
//                }
        }
        /*$_SESSION['flash'] = 'По головному центру "' . HeadCenter::getByID(
                $id
            )->name . '" в архив перемещено ' . $counter . ' документов';
        $this->redirectByAction('acts_to_archive_list', array('till' => $till));*/
        $header = 'По головному центру "'.HeadCenter::getByID(
                $id
            )->name.'" в архив перемещено  документов - '.$counter.':';

        return $this->render->view(
            'root/set_archive_by_head',
            array(
                'header' => $header,
                'list' => $list,
                'till' => $till,
            )
        );/**/
    }

    protected function acts_to_archive_list_dubl_action()
    {
        $array = array();
        $head_centers = HeadCenters::getAll();
        foreach ($head_centers as $hc) {
            $array[$hc->id]['id'] = $hc->id;
            $array[$hc->id]['name'] = $hc->name;
            $array[$hc->id]['acts'] = array();
            if (!empty($_POST['till'])) {
                $till = $_POST['till'];
            } else {
                if (!empty($_GET['till'])) {
                    $till = $_GET['till'];
                } else {
//                    $till = '';
                    $till = date('d.m.Y');
                }
            }
            $hc_acts = DublActList::get4HeadCenter($hc->id, $till);
            foreach ($hc_acts as $act) {
                //if ($act->isToArchive())
                //{
                $array[$hc->id]['acts'][] = $act->id;
                //}
            }
        }

        return $this->render->view(
            'root/acts_to_archive_list_dubl',
            array(
                'array' => $array,
                'till' => $till,
            )
        );
    }

    protected function set_archive_by_head_dubl_action()
    {
        $id = intval($_GET['id']);
        $counter = 0;
        if (!empty($_GET['till'])) {
            $till = $_GET['till'];
        } else {
            $till = '';
            $_SESSION['flash'] = 'Невозможно отправить в архив - НЕ ВЫБРАНА ДАТА';
//        $this->redirectReturn();
            $this->redirectByAction('acts_to_archive_list_dubl', array('till' => $till));
        }
        $hc_acts = DublActList::get4HeadCenter($id, $till);
        $list = array();
        foreach ($hc_acts as $act) {
            // if ($act->isToArchive())
            //{
            $list[] = $act->id;
//            $act->setState(DublAct::STATE_PROCESSED);
            $act->state = DublAct::STATE_PROCESSED;;
            $act->save();
            $counter++;
//                }
        }
        /*$_SESSION['flash'] = 'По головному центру "' . HeadCenter::getByID(
                $id
            )->name . '" в архив перемещено ' . $counter . ' документов';
        $this->redirectByAction('acts_to_archive_list', array('till' => $till));*/
        $header = 'По головному центру "'.HeadCenter::getByID(
                $id
            )->name.'" в архив перемещено  документов - '.$counter.':';

        return $this->render->view(
            'root/set_archive_by_head_dubl',
            array(
                'header' => $header,
                'list' => $list,
                'till' => $till,
            )
        );/**/
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

    protected function report_add_action()
    {
        $report = new Report();
        if (count($_POST)) {
            $report->parseParameters($_POST);
            $report->save();
            $this->redirectByAction('reports_edit_list');
        }

        return $this->render->form('add', $report, 'Добавление  отчета');
    }

    protected function report_edit_action()
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirectReturn();
        }
        $id = $_GET['id'];
        $report = Report::getByID($id);
        if (!$report) {
            $this->redirectAccessRestricted();
        }
        if (count($_POST)) {
            $report->parseParameters($_POST);
            $report->save();
            $this->redirectByAction('reports_edit_list');
        }

        return $this->render->form('add', $report, 'Редактирование отчета');
    }

    protected function reports_edit_list_action()
    {
        if (isset($_POST['do'])) {
            $reports = new Reports();
            $reports->save4User($_POST['reports']);
            $_SESSION['flash'] = 'Доступ к отчетам установлен';
        }
        $reports = Reports::getAll();

        return $this->render->view(
            'root/reports_edit_list',
            array(
                'object' => $reports,
            )
        );
    }

    protected function report_delete_action()
    {
        $report = Report::getByID($_GET['id']);
//var_dump($report);die();
        $report->delete();
        $_SESSION['flash'] = 'Отчет удален';
        $this->redirectByAction('reports_edit_list');
    }


    protected function act_table_print_view_action()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $fname = filter_input(INPUT_GET, 'fname');
        $forceDownload = filter_input(INPUT_GET, 'download', FILTER_VALIDATE_INT);
        if (!$id) {
            $this->redirectAccessRestricted();
        }
        $act = Act::getByID($id);
        if (!$act) {
            $this->redirectAccessRestricted();
        }
        $HTML_table = HTMLActFile::getByActID($act->id);
        if (!$HTML_table) {
            $this->redirectAccessRestricted();
        }
        $file = ActSummaryTable::getByID($HTML_table->file_act_tabl_id);
        if (!$file) {
            die('Файл не существует');
        }
        if ($forceDownload) {
            $file->download($fname);
} else {
            $file->show();
        }
    }

    protected function act_print_view_action()
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
            $this->redirectAccessRestricted();
        }
        $file = ActSummaryTable::getByID($HTML_table->file_act_id);
        if (!$file) {
            die('Файл не существует');
        }
        $file->show();
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





    protected function move_certificates_action($pfur = 0)
    {
        $from = filter_input(INPUT_POST, 'from', FILTER_VALIDATE_INT);
        $to = filter_input(INPUT_POST, 'to', FILTER_VALIDATE_INT);
        $level_type = filter_input(INPUT_POST, 'level_type', FILTER_VALIDATE_INT);
        $start = filter_input(INPUT_POST, 'start');
        $end = filter_input(INPUT_POST, 'end');
        $not_used_certificates_list = array();
        $result = array();
        if ($_POST) {
            $not_used_certificates_list = \SDT\models\Certificate\CertificateReserved::getAllNotUsedByHC(
                $from,
                $level_type
            );
            if (empty($not_used_certificates_list)) $not_used_certificates_list = array();
            $certificates_list = \SDT\models\Certificate\CertificateReserved::getRangeNumbers(
                mysql_real_escape_string($start),
                mysql_real_escape_string($end)
            );
            if (!empty($certificates_list)) {
                foreach ($certificates_list as $item) {
                    if (in_array($item, $not_used_certificates_list)) {
                        $result['success'][] = $item;
                    } else {
                        $result['error'][] = $item;
                    }
                }
            }
        }

        return $this->render->view(
            'certificates/move_form',
            array(
                'from' => $from,
                'to' => $to,
                'level_type' => $level_type,
                'start' => $start,
                'end' => $end,
                'result' => $result,
                'pfur' => $pfur,
            )
        );
    }

    protected function move_certificates_all_hc_action()
    {
        return $this->move_certificates_action(0);
    }

    protected function save_moved_certificates_action()
    {
        if (empty($_POST)) {
            $this->redirectByAction('move_certificates_all_hc');
        }
        $from = filter_input(INPUT_POST, 'from', FILTER_VALIDATE_INT);
        $to = filter_input(INPUT_POST, 'to', FILTER_VALIDATE_INT);
        $level_type = filter_input(INPUT_POST, 'level_type', FILTER_VALIDATE_INT);
        $pfur = filter_input(INPUT_POST, 'pfur', FILTER_VALIDATE_INT);
        $result = array();
        $start = $end = '';
        if (!empty($from) && !empty($to) && !empty($level_type) && !empty($_POST['to_send'])):
            $array = explode(',', $_POST['to_send']);
            //var_dump(array_chunk($array,2));
            $start = $array[0];
            $end = end($array);
            foreach ($array as $item) {
                $sql = 'update certificate_reserved 
                set head_center_id = '.$to.'
                where test_type_id='.$level_type.' 
                and number = "'.$item.'"
                and invalid = 0 and used = 0
                and head_center_id = '.$from;
                //echo $sql.'<br>';
                mysql_query($sql);
//            var_dump(mysql_affected_rows());
                if (mysql_affected_rows()) {
                    $result['success'][] = $item;
                } else {
                    $result['error'][] = $item;
                }
            }
        endif;

        return $this->render->view(
            'certificates/save_moved_certificates',
            array(
                'from' => $from,
                'to' => $to,
                'level_type' => $level_type,
                'start' => $start,
                'end' => $end,
                'result' => $result,
                'pfur' => $pfur,
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
}





/*
 * select
count(*)
from sdt_act_people  sap
left join sdt_act sa on sap.act_id = sa.id
where
(sap.blank_number = '' or sap.blank_number is null)
and sap.deleted = 0 and sa.deleted = 0
and sa.state = 'archive'

 */