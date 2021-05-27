<?php
/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 12.01.2015
 * Time: 10:34
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/_func.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/lang.php';
auth();
require_once dirname(__FILE__) . '/../models/include.php';
require_once dirname(__FILE__) . '/../logic/loader.php';
require_once dirname(__FILE__) . '/../render.php';
require_once dirname(__FILE__) . '/../Roles.php';
require_once dirname(__FILE__) . '/../Errors.php';
require_once dirname(__FILE__) . '/../helpers.php';
require_once dirname(__FILE__) . '/../Exception/Exceptions.php';

class AbstractController
{
    use ConvertTrait;
    private static $instance;
    /** @var Render */
    protected $render;
    protected $current_role = array();
    protected $accessLevelList = false;
    protected $accessCenterList = false;
    protected $roles;

    protected function __construct()
    {
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
           }*/
        // var_dump($univer);
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

    protected function redirectUniversityHasNoDogovors()
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

    public function postNumeric($key)
    {
        if (!isset($_POST[$key]) || !is_numeric($_POST[$key])) {
            return null;
        }

        return $_POST[$key];
    }

    public function redirectReturn($append = '')
    {

        if (empty($_SERVER['HTTP_REFERER'])) {
            $_SERVER['HTTP_REFERER'] = '/sdt/';
        }
        header('Location: ' . $_SERVER['HTTP_REFERER'] . $append);
        die();
    }

    private function getControllerName(){

    }

    private function logAction($action,$controller)
    {
        $C = Connection::getInstance();
        $log_sql = 'insert into log_actions (login_id, url, controller, action, method) values (%d,"%s","%s","%s","%s")';
        $log_sql=vsprintf($log_sql,[
            @$_SESSION['log_login_id'],
            @$_SERVER['REQUEST_URI'],
            $controller,
            $action,
            @$_SERVER['REQUEST_METHOD'],
        ]);
//        $C->execute($log_sql);

        if (in_array($action, ['xml_upload'])) {
            $log = new ActionRunLog();
            $log->timestamp = date('Y-m-d H:i:s');
            $log->server = json_encode($_SERVER);
            $log->url = @$_SERVER['REQUEST_URI'];
            $log->ip = @$_SERVER['REMOTE_ADDR'];
            $log->user_agent = @$_SERVER['HTTP_USER_AGENT'];
            $log->server_name = @$_SERVER['SERVER_NAME'];
            $log->method = @$_SERVER['REQUEST_METHOD'];
            $log->head_center_id = @CURRENT_HEAD_CENTER;
            $log->save();
//            $log=

//            die(var_dump($_SERVER));
        }
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
        $action = filter_input(INPUT_GET, 'action');
        $this->logAction($action,get_class($this));
        try {
            if (!empty($action)) {
                $method_name = $action . '_action';
                if (method_exists($this, $method_name)) {

                    if (!$this->getRoleAccess($action)) {

                        $this->redirectAccessRestricted();
                    }

                    return call_user_func(array($this, $method_name));
                }
            }
        } catch (\SDT\Exception\Http\AccessRestricted $ex) {
            $this->redirectAccessRestricted();
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
  public function renderAccessRestricted()
    {
         header('HTTP/1.0 403 Forbidden');
        return $this->render->view('access_restricted');

    }

    public function redirectByAction($action, $params = array(), $controller = 'index')
    {
        $query = array('action' => $action);
        if (is_array($params)) {
            $query = array_merge($params, $query);
        }


        header('Location: ./' . $controller . '.php?' . http_build_query($query));
        die();
    }

    protected function index_action()
    {
        return null;
    }

    public function rusMonth($n)
    {
        $monthes = array(
            1 => 'Январь', 2 => 'Февраль', 3 => 'Март', 4 => 'Апрель',
            5 => 'Май', 6 => 'Июнь', 7 => 'Июль', 8 => 'Август',
            9 => 'Сентябрь', 10 => 'Октябрь', 11 => 'Ноябрь', 12 => 'Декабрь'
        );
        return $monthes[ltrim($n, 0)];
    }

    protected function access_restricted_action()
    {
        return $this->render->view('access_restricted');
    }

    protected function isDeletedRedirect(Model $model)
    {
        if ($model->isDeleted()) {
            $this->redirectAccessRestricted();
        }
    }


    protected function download_action()
    {
        $file = File::getByHash(filter_input(INPUT_GET, 'hash'));
        if (!$file) {
            die('Файл не существует');
        }
//        session_write_close();
        $file->download();
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

    protected function renderText($text)
    {
        return $this->render->view(
            'text',
            array(//                'list' => $list
                'text' => $text,
            )
        );

    }


    public function excel_alphabet($num)
    {
        if ($num == 0) die('Слишком большое число!');

        $alphabet = array();
        for ($i = 1; $i < 27; $i++) {
            $alphabet[$i] = chr(64 + $i);
        }

        $letters = count($alphabet);
        if ($num <= $letters) $lt = $alphabet[$num];
        else {
            $t = (int)($num / $letters);
            if ($num - ($t * $letters) == 0) $t--;
            $first_letter = $alphabet[$t];
            //if (empty($alphabet[$num-($t*$letters)])) die('Слишком большое число -'.$num);
            if (empty($alphabet[$num - ($t * $letters)])) die('Слишком большое число -' . $num - ($t * $letters));
            $second_letter = $alphabet[$num - ($t * $letters)];
            $lt = $first_letter . $second_letter;
        }
        return mb_convert_encoding($lt, 'UTF-8', 'cp1251');
    }


}