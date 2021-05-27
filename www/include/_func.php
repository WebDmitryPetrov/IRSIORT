<?php
//ini_set('display_errors',1);

require_once 'sdt_config.php';
require_once 'class/Connection.php';


include_once('config.php');
require DC . '/vendor/autoload.php';
__init__();

include_once('load_translation.php');
include_once('lang.php');
include_once('reexam_config.php');


function strtodate($str)
{
    $date_array = explode("/", $str);

    return $date_array[2] . '-' . $date_array[1] . '-' . $date_array[0];
}

function debag_trac($a = '', $b = '')
{

    if ($a != '') {
        echo '<h2>Временна остановка, по техническим причинам</h2>';
    }
//записываем лог
    $fstring = date("d-m-Y H:i:s") . ':' . getip() . ':' . $b . "\r\n";
    $fname = "error_log.txt";
    $fres = fopen($fname, 'a+');
    fwrite($fres, $fstring);
    fclose($fres);
}

function db_connect()
{
    Connection::getInstance();

}

function db_encoding_win()
{
    Connection::getInstance()->encoding_win();

}

function db_encoding_utf8()
{
    Connection::getInstance()->encoding_utf();
}

function ses_destr()
{
//session_id($_SERVER["SSL_SESSION_ID"]);
    if (!session_id()) {
        session_start();
    }
    $_SESSION = array();
    session_destroy();

    /*
    $_SESSION = array();
        session_destroy();
     $cool=array ("date_docd1" => "",
        "date_docm1" => "",
        "date_docy1" => "",
        "date_docd2" =>"",
        "date_docm2" => "",
        "date_docy2" => "",
        "new_dead_timed1" => "",
        "new_dead_timem1" =>"",
        "new_dead_timey1" => "",
        "new_dead_timed2" => "",
        "new_dead_timem2" => "",
        "new_dead_timey2" => "",
        "dead_timed1" => "",
        "dead_timem1" => "",
        "dead_timey1" => "",
        "dead_timed2" => "",
        "dead_timem2" => "",
        "dead_timey2" => "",
        "date_regd1" => "",
        "date_regm1" =>"",
        "date_regy1" => "",
        "date_regd2" => "",
        "date_regm2" =>"",
        "date_regy2" => "",
        "s_id" => "",
        "PHPSESSID" => "");

        setcookie("s_id","",time()-42000);
        setcookie("PHPSESSID","",time()-42000);
        $s_id=1;
        setcookie("s_id","",time()-42000,"/irud/control/");
        foreach ($_COOKIE as $name => $value)
        {
            setcookie($name,"",time()-42000);
            //echo $name."<br>";
        }
        foreach ($cool as $name => $value)
        {
            setcookie($name,$value,time()-42000);
            setcookie($name,$value,time()-42000,"/irud/control");
            //echo $name."<br>";
        }

           $_COOKIE=array();
         setcookie("PHPSESSID","1",time()-42000,"/irud/control/");*/


}

function auth($link = '../index.php')
{
    //if(empty($_SERVER["SSL_SESSION_ID"]))die(debag_trac(' В связи с переходом на новый уровень безопасности сессия будет привязана к протоколу <br> и хранится в переменной $_SERVER["SSL_SESSION_ID"] <br> Желаю удачной работы!<br> Для входа в систему воспользуётесь ссылкой ниже P.S. Уже все настроенно и проверенно :)','Если неоткрывается значит ошибка! <h1 style="color:red">Новый адресс <a  style="color:blue" href="https://'.$_SERVER['SERVER_NAME'].'/">http<B>s</b>://'.$_SERVER['SERVER_NAME'].'/</a></h1> позвоните по телефону!'));

    global $_POST, $_GET, $groups;
    //echo 'start!!!!!!!!!!!!!!!!!!!!';
    if (is_blocked()) {
        header('Location: ' . $link . '?ooops_auth=blocked');
        die;
    }
    if (isset($_GET['exit']) && $_GET['exit'] == "exit") {
        ses_destr();
        //echo 'stop1';
        header('Location: ' . $link . '?ooops=x4');
        die;

        return false;
    } else {
//        $con = db_connect();
#		if(isset($_COOKIE['s_id']) or (!empty($s_id) and $s_id != '1'))

        //session_id($_SERVER["SSL_SESSION_ID"]);
        if (!session_id()) {
            session_start();
        }

        if (!isset($_SESSION['u_id']) || empty($_SESSION['u_id'])) {
            if (isset($_POST['login']) && !empty($_POST['login'])) {
                $login = mysql_real_escape_string(trim(strip_tags($_POST['login'])));
            } else { #@ses_destr();
                ses_destr();


                header('Location:' . $link . '?ooops_auth=1');
                die;

                return false;
            }

            if (isset($_POST['password']) && !empty($_POST['password'])) {
                $password = mysql_real_escape_string(strip_tags($_POST['password']));
            } else {
                ses_destr();
                header('Location:' . $link . '?ooops_auth=2');
                die();
            }

            $sql = "select u_id, concat(surname,' ',firstname,' ', fathername) as userName,
UNIX_TIMESTAMP(password_changed_at) as password_changed_at, surname,
 univer_id, head_id,user_type_id, user_type.caption as user_type_caption

            from tb_users
               left join user_type on tb_users.user_type_id = user_type.id
            where login='$login' and password=md5(md5('$password'))
            and
            head_id = " . CURRENT_HEAD_CENTER;
//            die($sql);
            $res = mysql_query($sql) or die(debag_trac(
                "Проверка пользователя - неудачна, по причине, что нет таблицы в БД tb_users",
                mysql_error()
            ));
            $id = 0;
            $userName = '';
            $univer_id = 0;
            if (!mysql_num_rows($res)) {
                ses_destr();
                log_invalid_login($login);
                header('Location:' . $link . '?ooops_auth=3');


                die;

            }

            $row = mysql_fetch_array($res);

            if (!$row['password_changed_at']) {
                $dt = new DateTime('- 84 days');
                $format = $dt->format('Y-m-d H:i:s');
                mysql_query(
                    "UPDATE tb_users
SET password_changed_at = '" . mysql_real_escape_string($format) . "'
                    WHERE u_id = " . $row['u_id']
                ) or die(mysql_error());
                $row['password_changed_at'] = strtotime($format);
            }

            $now = time();

            if ($row['password_changed_at'] + PASSWORD_DURATION < $now) {
                ses_destr();
                header('Location:' . $link . '?ooops_auth=password_expired');
                die();
            }


            $_SESSION['u_id'] = $row['u_id'];
            $_SESSION['login'] = $login;
            $_SESSION['password'] = $password;
            $_SESSION['username'] = $row['userName'];
            $_SESSION['univer_id'] = $row['univer_id'];
            $_SESSION['surname'] = $row['surname'];
            $_SESSION['password_changed_at'] = $row['password_changed_at'];
            $_SESSION['head_id'] = $row['head_id'];
            $_SESSION['user_type_id'] = $row['user_type_id'];
            $_SESSION['user_type_caption'] = $row['user_type_caption'];
            if ($row['univer_id']) {
                $names = getUniversityName($univer_id);
                if ($names['short_name']) {
                    $_SESSION['surname'] = $names['short_name'];
                }
            }
            $sql = "insert into log_login (login, ip, user_id, head_id) values ('%s','%s',%d,%d)";
            $login_res = mysql_query(sprintf($sql, $_SESSION['login'],
                $_SERVER['REMOTE_ADDR'],
                $_SESSION['u_id'], @CURRENT_HEAD_CENTER
            ));
            $_SESSION['log_login_id'] = mysql_insert_id();
        }

        $privelegies = array();

        $sql = "SELECT  user_type_group_relations.group_id
      FROM user_type_group_relations
      WHERE user_type_group_relations.user_type_id = " . intval($_SESSION['user_type_id']);


        $res = mysql_query($sql) or die(debag_trac('Проверте настройку конфига! ', mysql_error()));
        $c1 = array_flip($groups);

        while ($row = mysql_fetch_array($res)) {
            if (in_array($row[0], $groups)) {
                $privelegies[$c1[$row[0]]] = $row[0];
            } else {
                $privelegies[$row[0]] = $row[0];
            }
        }


        unset($privelegies['center_external']);

        if ($_SESSION['univer_id']) {
            $privelegies['center_external'] = 1;
        }

        $_SESSION['privelegies'] = $privelegies;

//        redir_prava();
    }

}

function today_select($day, $month, $year, $a = '', $b = '', $c = '')
{
    $txt_day = "<select name=\"$day\" id=\"$day\">\n";
    for ($i = 1; $i <= 31; $i++) {
        if (!empty($a)) {
            $sel = ($a == $i) ? ' selected' : '';
        } else {
            $sel = (date('d') == $i) ? ' selected' : '';
        }
        $txt_day .= "<option value=\"$i\"$sel>$i</option>\n";
    }
    $txt_day .= "</select>\n";

    $months = array(
        1 => 'Январь',
        'Февраль',
        'Март',
        'Апрель',
        'Май',
        'Июнь',
        'Июль',
        'Август',
        'Сентябрь',
        'Октябрь',
        'Ноябрь',
        'Декабрь'
    );
    $txt_month = "<select name=\"$month\" id=\"$month\">\n";

    for ($i = 1; $i <= 12; $i++) {

        if (!empty($b)) {
            $sel = ($b == $i) ? ' selected' : '';
        } else {
            $sel = (date('n') == $i) ? ' selected' : '';
        }
        $txt_month .= "<option value=\"$i\"$sel>{$months[$i]}</option>\n";
    }
    $txt_month .= "</select>\n";

    $txt_year = "<select name=\"$year\" id=\"$year\">\n";
    for ($i = date('Y') + 10; $i >= (date('Y') - 10); $i--) {
        if (!empty($c)) {
            $sel = ($c == $i) ? ' selected' : '';
        } else {
            $sel = (date('Y') == $i) ? ' selected' : '';
        }
        $txt_year .= "<option value=\"$i\"$sel>$i</option>\n";
    }
    $txt_year .= "</select>\n";

    return $txt_day . ' - ' . $txt_month . ' - ' . $txt_year;
}

function control($set_control, $rem_control = 'stop')
{
    /*
     * Контроль:
     * 9: установлено и снято с контроля
     * 5: Установлено
     * 10: снято
     * 6: ничего
     */
    if (!is_string($rem_control)) {
        if ($set_control) {
            $set_control = 1;
        } else {
            $set_control = 2;
        }
        if ($rem_control) {
            $rem_control = 8;
        } else {
            $rem_control = 4;
        }

        return $set_control + $rem_control;
    } else {
        $sw = $set_control;
        switch ($sw) {
            case 9:
            {
                $set_control = true;
                $rem_control = true;
                break;
            }
            case 5:
            {
                $set_control = true;
                $rem_control = false;
                break;
            }
            case 10:
            {
                $set_control = false;
                $rem_control = true;
                break;
            }
            case 6:
            {
                $set_control = false;
                $rem_control = false;
                break;
            }
            default:
            {
                $set_control = false;
                $rem_control = false;
                break;
            }
        }
        $arr = array("set_control" => $set_control, "rem_control" => $rem_control);

        return $arr;
    }
}

function arm()
{
    $sql_query = "SELECT * FROM users WHERE id='" . $_SESSION['u_id'] . "'";
    $query = mysql_query($sql_query) or die(debag_trac('', mysql_error()));
    if (mysql_num_rows($query)) {
        $data_array = mysql_fetch_array($query);
        $_SESSION['user_name'] = $data_array['dolz'];
        $_SESSION['user_qvote'] = $data_array['rang'];
        $_SESSION['user_write'] = $data_array['write'];


    } else {
        header("Location: ./../index.php?ooops=arm");
        exit();
    }

}

function checket_box_rub($a)
{
    global $_SESSION, $arr_ch;
    $user = $_SESSION['u_id'];

    if (empty($arr_ch)) {
        $arr_ch = array();
        $res = mysql_query("SELECT fk_r_id FROM `user_to_rub` where fk_u_id = $user") or die(debag_trac(
            '',
            mysql_error()
        ));
        while ($row = mysql_fetch_array($res)) {
            $arr_ch[$row['fk_r_id']] = '1';
        }
    }

    if (!empty($arr_ch[$a]) and $arr_ch[$a] == '1') {
        return " checked=\"checked\" ";
    } else {
        return "";
    }

}

function f_lincencode($f_name)
{

    $f_name = rawurlencode($f_name);

    $f_linc = str_replace("%2F", "_", $f_name); // /
    $f_linc = str_replace("%5C", "_", $f_linc); // \
    $f_linc = str_replace("%7C", "_", $f_linc); // |
    $f_linc = str_replace("%3F", "_", $f_linc); // ?
    $f_linc = str_replace("%3A", "_", $f_linc); // :
    $f_linc = str_replace("%2A", "_", $f_linc); // *
    $f_linc = str_replace("%22", "_", $f_linc); // "
    $f_linc = str_replace("%3C", "_", $f_linc); // <
    $f_linc = str_replace("%3E", "_", $f_linc); // >
    return rawurlencode($f_linc);
}

function set_rash($f_type)
{
    switch ($f_type) {
        case "application/pdf":
            return '.pdf';
            break;
        case "application/x-zip-compressed":
            return '.zip';
            break;
        case "application/rar":
            return '.rar';
            break;
        case "text/richtext":
            return '.rtf';
            break;
        case "application/msword":
            return '.doc';
            break;
        case "application/vnd.ms-excel":
            return '.xls';
            break;
        case "application/powerpoint":
            return '.ppt';
            break;
        case "application/vnd.ms-powerpoint":
            return '.pps';
            break;
        case "image/gif":
            return '.gif';
            break;
        case "image/png":
            return '.png';
            break;
        case "image/jpeg":
            return '.jpeg';
            break;
        case "image/jpg":
            return '.jpg';
            break;
        case "text/html":
            return '.html';
            break;
        case "text/plain":
            return '.txt';
            break;
        case "application/vnd.openxmlformats-officedocument.wordprocessingml.document":
            return '.docx';
            break;
        case "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet":
            return '.xlsx';
            break;
        case "application/vnd.openxmlformats-officedocument.presentationml.presentation":
            return '.pptx';
            break;
        case "application/vnd.openxmlformats-officedocument.presentationml.slideshow":
            return '.ppsx';
            break;
        default:
            return false;
    }

    return true;
}

function redir_prava()
{
    global $PHP_SELF, $_SESSION;

    $a = explode("/", $PHP_SELF);

    for ($i = 0; $i < count($a); $i++) {
//	echo $a[$i];

        if (strtolower($a[$i]) == 'admin.php' || strtolower($a[$i]) == 'stat.php' || strtolower(
                $a[$i]
            ) == 'provider_admin.php'
        ) {
            //	echo 'доступ на файл';

            if (empty($_SESSION['privelegies']['admin'])) # || $_SESSION['privelegies']['admin'] == '0')
            {
                header('Location: ./index.php?ooops=p11');
                exit();
            }
            break;

        }
        if ((strtolower($a[$i]) == 'rectorat')) {
            if (empty($_SESSION['privelegies']['rekt_read']) || $_SESSION['privelegies']['rekt_read'] == '0') {
                header("Location: ./../index.php?ooops=rectorat");

                exit();
            }
            break;
        }
        if ((strtolower($a[$i]) == 'control')) { //	echo 'пїЅпїЅпїЅпїЅпїЅ пїЅ пїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅ';
            if ((empty($_SESSION['privelegies']['rukovod']) || $_SESSION['privelegies']['rukovod'] == '0') and
                (empty($_SESSION['privelegies']['otv_sotrudnik']) || $_SESSION['privelegies']['otv_sotrudnik'] == '0') &&
                (empty($_SESSION['privelegies']['otv_ispolnit']) || $_SESSION['privelegies']['otv_ispolnit'] == '0')
            ) {
                header("Location: ./../index.php?ooops=control");

                exit();
            }
            break;

        }
        if ((strtolower($a[$i]) == 'sovet')) {

            if (
                (empty($_SESSION['privelegies']['sov_admin']) || $_SESSION['privelegies']['sov_admin'] == '0') and
                (empty($_SESSION['privelegies']['sov_open_3gr']) || $_SESSION['privelegies']['sov_open_3gr'] == '0') and
                (empty($_SESSION['privelegies']['sov_close_prizd']) || $_SESSION['privelegies']['sov_close_prizd'] == '0') and
                (empty($_SESSION['privelegies']['26']) || $_SESSION['privelegies']['26'] == '0')
            ) {
                header("Location: ./../index.php?ooops=sovet");

                exit();
            }
            break;
        }

    }
//[''] - пїЅпїЅпїЅпїЅпїЅпїЅ пїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅ

// пїЅпїЅпїЅ пїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅ пїЅ пїЅпїЅпїЅпїЅпїЅпїЅпїЅ arm()

// /control/
// /control/

}


function generateCode($length = 6)
{
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
    $code = "";
    $clen = strlen($chars) - 1; //a variable with the fixed length of chars correct for the fence post issue
    while (strlen($code) < $length) {
        $code .= $chars[mt_rand(0, $clen)]; //mt_rand's range is inclusive - this is why we need 0 to n-1
    }

    return $code;
}

function generatedate()
{

    $t = time();
    $r = rand(-60000, 60000);
    $t = $t - $r;

    return date("Y-m-d", $t);
}

function hits_site_fluud()
{
    global $con, $_SESSION;

    $l = array(
        0 => '_',
        'A-Z',
        'пїЅ',
        'пїЅ',
        'пїЅ',
        'пїЅ',
        'пїЅ',
        'пїЅ',
        'пїЅ',
        'пїЅ',
        'пїЅ',
        'пїЅ',
        'пїЅ',
        'пїЅ',
        'пїЅ',
        'пїЅ',
        'пїЅ',
        'пїЅ',
        'пїЅ',
        'пїЅ',
        'пїЅ',
        'пїЅ',
        'пїЅ',
        'пїЅ',
        'пїЅ',
        'пїЅ',
        'пїЅ',
        'пїЅ',
        'пїЅ',
        'пїЅ',
        'пїЅ',
        'пїЅ',
        'пїЅ',
        'пїЅ',
        'пїЅ'
    );

    for ($i = 0; $i < 1000; $i++) {
        $s = rand(0, count($l));
        $s = $l[$s];
        $login = 'bot_' . generateCode(19);
        $pass = 'bot_' . generateCode(19);
        $surname = $s . 'bot_' . generateCode(19);
        $firstname = $s . 'bot_' . generateCode(19);
        $fathername = $s . 'bot_' . generateCode(19);


        $sql = "
	INSERT INTO `tb_users` (
`u_id` ,
`login` ,
`password` ,
`surname` ,
`firstname` ,
`fathername`
)
VALUES (
NULL , '$login', '$pass', '$surname', '$firstname', '$fathername '
)	";


        if (!mysql_query($sql)) {
            echo 'пїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅ пїЅпїЅпїЅпїЅ пїЅпїЅпїЅпїЅпїЅпїЅпїЅ пїЅпїЅпїЅ ' . mysql_error();
        }
    }
}


function hits_site($msg, $b, $conv = 'UTF8')
{
    global $con, $_SESSION;
    $msg = getip() . ' ' . $msg;
    $sql = "INSERT INTO .`tb_admin_hits` (`id` ,`u_id` ,`date` ,`mesg`, `tip`) VALUES ( NULL , '" . $_SESSION['u_id'] . "', '" . date(
            "Y-m-d H:i:s"
        ) . "', '" . mysql_escape_string($msg) . " ', '" . $b . "')";
    if (!mysql_query($sql)) {
        echo 'пїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅ пїЅпїЅпїЅпїЅ пїЅпїЅпїЅпїЅпїЅпїЅпїЅ пїЅпїЅпїЅ ' . mysql_error();
    }

}

function cp1251_utf8($text)
{
    $text = str_replace(chr(208), chr(208) . chr(160), $text); # пїЅ
    $text = str_replace(chr(192), chr(208) . chr(144), $text); # пїЅ
    $text = str_replace(chr(193), chr(208) . chr(145), $text); # пїЅ
    $text = str_replace(chr(194), chr(208) . chr(146), $text); # пїЅ
    $text = str_replace(chr(195), chr(208) . chr(147), $text); # пїЅ
    $text = str_replace(chr(196), chr(208) . chr(148), $text); # пїЅ
    $text = str_replace(chr(197), chr(208) . chr(149), $text); # пїЅ
    $text = str_replace(chr(168), chr(208) . chr(129), $text); # пїЅ
    $text = str_replace(chr(198), chr(208) . chr(150), $text); # пїЅ
    $text = str_replace(chr(199), chr(208) . chr(151), $text); # пїЅ
    $text = str_replace(chr(200), chr(208) . chr(152), $text); # пїЅ
    $text = str_replace(chr(201), chr(208) . chr(153), $text); # пїЅ
    $text = str_replace(chr(202), chr(208) . chr(154), $text); # пїЅ
    $text = str_replace(chr(203), chr(208) . chr(155), $text); # пїЅ
    $text = str_replace(chr(204), chr(208) . chr(156), $text); # пїЅ
    $text = str_replace(chr(205), chr(208) . chr(157), $text); # пїЅ
    $text = str_replace(chr(206), chr(208) . chr(158), $text); # пїЅ
    $text = str_replace(chr(207), chr(208) . chr(159), $text); # пїЅ
    $text = str_replace(chr(209), chr(208) . chr(161), $text); # пїЅ
    $text = str_replace(chr(210), chr(208) . chr(162), $text); # пїЅ
    $text = str_replace(chr(211), chr(208) . chr(163), $text); # пїЅ
    $text = str_replace(chr(212), chr(208) . chr(164), $text); # пїЅ
    $text = str_replace(chr(213), chr(208) . chr(165), $text); # пїЅ
    $text = str_replace(chr(214), chr(208) . chr(166), $text); # пїЅ
    $text = str_replace(chr(215), chr(208) . chr(167), $text); # пїЅ
    $text = str_replace(chr(216), chr(208) . chr(168), $text); # пїЅ
    $text = str_replace(chr(217), chr(208) . chr(169), $text); # пїЅ
    $text = str_replace(chr(218), chr(208) . chr(170), $text); # пїЅ
    $text = str_replace(chr(219), chr(208) . chr(171), $text); # пїЅ
    $text = str_replace(chr(220), chr(208) . chr(172), $text); # пїЅ
    $text = str_replace(chr(221), chr(208) . chr(173), $text); # пїЅ
    $text = str_replace(chr(222), chr(208) . chr(174), $text); # пїЅ
    $text = str_replace(chr(223), chr(208) . chr(175), $text); # пїЅ
    $text = str_replace(chr(224), chr(208) . chr(176), $text); # пїЅ
    $text = str_replace(chr(225), chr(208) . chr(177), $text); # пїЅ
    $text = str_replace(chr(226), chr(208) . chr(178), $text); # пїЅ
    $text = str_replace(chr(227), chr(208) . chr(179), $text); # пїЅ
    $text = str_replace(chr(228), chr(208) . chr(180), $text); # пїЅ
    $text = str_replace(chr(229), chr(208) . chr(181), $text); # пїЅ
    $text = str_replace(chr(184), chr(209) . chr(145), $text); # пїЅ
    $text = str_replace(chr(230), chr(208) . chr(182), $text); # пїЅ
    $text = str_replace(chr(231), chr(208) . chr(183), $text); # пїЅ
    $text = str_replace(chr(232), chr(208) . chr(184), $text); # пїЅ
    $text = str_replace(chr(233), chr(208) . chr(185), $text); # пїЅ
    $text = str_replace(chr(234), chr(208) . chr(186), $text); # пїЅ
    $text = str_replace(chr(235), chr(208) . chr(187), $text); # пїЅ
    $text = str_replace(chr(236), chr(208) . chr(188), $text); # пїЅ
    $text = str_replace(chr(237), chr(208) . chr(189), $text); # пїЅ
    $text = str_replace(chr(238), chr(208) . chr(190), $text); # пїЅ
    $text = str_replace(chr(239), chr(208) . chr(191), $text); # пїЅ
    $text = str_replace(chr(240), chr(209) . chr(128), $text); # пїЅ
    $text = str_replace(chr(241), chr(209) . chr(129), $text); # пїЅ
    $text = str_replace(chr(242), chr(209) . chr(130), $text); # пїЅ
    $text = str_replace(chr(243), chr(209) . chr(131), $text); # пїЅ
    $text = str_replace(chr(244), chr(209) . chr(132), $text); # пїЅ
    $text = str_replace(chr(245), chr(209) . chr(133), $text); # пїЅ
    $text = str_replace(chr(246), chr(209) . chr(134), $text); # пїЅ
    $text = str_replace(chr(247), chr(209) . chr(135), $text); # пїЅ
    $text = str_replace(chr(248), chr(209) . chr(136), $text); # пїЅ
    $text = str_replace(chr(249), chr(209) . chr(137), $text); # пїЅ
    $text = str_replace(chr(250), chr(209) . chr(138), $text); # пїЅ
    $text = str_replace(chr(251), chr(209) . chr(139), $text); # пїЅ
    $text = str_replace(chr(252), chr(209) . chr(140), $text); # пїЅ
    $text = str_replace(chr(253), chr(209) . chr(141), $text); # пїЅ
    $text = str_replace(chr(254), chr(209) . chr(142), $text); # пїЅ
    $text = str_replace(chr(255), chr(209) . chr(143), $text); # пїЅ

    return $text;
}

function hits_out()
{
    global $im, $im1, $d, $con, $_COOKIE, $num1, $_SESSION, $text;
    if ($num1 != 'РќРµС‚Сѓ') {
        $fl = 1;
        $tex[] = '<sup>РќРѕРІРёРєР°!</sup>';
        $tex[] = '<sup>РќРѕРІРѕРµ!</sup>';
        $tex[] = '<sup>Р”РѕР±Р°РІР»РµРЅРѕ!</sup>';
        $tex[] = '<sup>New!</sup>';
        $tex[] = '<sup>Update!</sup>';
        $tex[] = '<sup>Wow</sup>';
        $tex[] = '<sup>РќРѕРІРёРєР°</sup>';
        $tex[] = '<sup>РќРѕР’РёРЅРљР°</sup>';
        $tex[] = '<sup>РќРѕР’РёРќРљР°</sup>';
    }
    $fl1 = $fl2 = $fl3 = '0';
    #$con=db_connect();

    if (empty($text)) {
        $ser = ' 1 ';
    } else {
        $ser = ' `mesg` LIKE \'%' . $text . '%\' ';
    }
    $ch1 = '';
    $o = 'or';
    if (!empty($_COOKIE['ch'])) {
        $t = split(":", $_COOKIE['ch']);
        for ($i = 0; $i < count($t) - 1; $i++) {
            if (count($t) - 2 == $i) {
                $o = '';
            }
            $ch1 .= ' `tip`=' . $t[$i] . ' ' . $o;

        }
    }

    $sql = "SELECT login,  `surname` as fam, `tb_admin_hits`.u_id, mesg,UNIX_TIMESTAMP(date) as date, tip FROM `tb_admin_hits` join tb_users on `tb_admin_hits`.`u_id` =  `tb_users`.`u_id` where $ser and ($ch1) ORDER BY `tb_admin_hits`.`date` DESC";

    $res = mysql_query($sql); // or die(mysql_error());
    $id = 0;
    $limit1 = $limit = 50;


    //$start;
    $c = $i = 0;

    $num = mysql_num_rows($res);
    if ($num > 0) {
        echo print_page_2($num, $limit);
        if (!empty($_GET["p"])) {
            $p = $_GET["p"];
            $start = ($p * $limit) - $limit;
            $limit = ($p * $limit);
        } else {
            $start = 0;
        }

        while ($r = mysql_fetch_array($res)) {

            $c++;
            if ($c > $limit) {
                break;
            }

            if ($c > $start) {

#$r['tip']='1';
                $t = time() - 86400;
                $rt = $r['fam'] . ' (' . $r['login'] . ') ' . $r['mesg'];

                if ($fl == '1') {
                    if ($_COOKIE['last_time'] < $r['date'] and $r['u_id'] != $_SESSION['u_id']) {
                        $rt = $num1 . $tex[rand(0, count($tex) - 1)] . $rt;
                        $num1--;
                    }
                }

                if (date('d-mY') == date("d-mY", $r['date'])) {

                    if ($fl1 == '0') {
                        echo '<div class="feedDayWrap"><div class="feedDay">' . $d['0'] . '</div></div><div style="padding: 10px 10px 20px;">';
                    }
                    $fl1 = 1;
                    echo '<table class="feedTable" border="0" cellpadding="0" cellspacing="0">
    <tbody>
      <tr>
        <td class="feedIconWrap"><img src="ico/' . $im[$r['tip']] . '.gif" alt="' . $im1[$r['tip']] . '" class="feedIcon"> </td>
        <td class="feedStory">' . $rt . '</td>
        <td class="feedTime">' . date("H:i", $r['date']) . ' </td>
      </tr>
    </tbody>
  </table>';


                } elseif (date("d-mY", $t) == date("d-mY", $r['date'])) {
                    if ($fl1 == '1') {
                        echo '</div>';
                        $fl1 = 2;
                    }
                    if ($fl2 == '0') {
                        echo '<div class="feedDayWrap"><div class="feedDay">' . $d['1'] . '</div></div><div style="padding: 10px 10px 20px;">';
                    }
                    $fl2 = 2;

                    echo '<table class="feedTable" border="0" cellpadding="0" cellspacing="0">
    <tbody>
      <tr>
        <td class="feedIconWrap"><img src="ico/' . $im[$r['tip']] . '.gif" alt="' . $im1[$r['tip']] . '" class="feedIcon"> </td>
        <td class="feedStory">' . $rt . '</td>
        <td class="feedTime">' . date("H:i", $r['date']) . ' </td>
      </tr>
    </tbody>
  </table>';
                } else {

                    if ($fl2 == '2') {
                        echo '</div>';
                        $fl2 = '3';
                    }

                    if ($fl3 != '0' and $fl3 != date("d-mY", $r['date'])) {
                        echo '</div>';
                    }


                    if ($fl3 != date("d-mY", $r['date'])) {
                        echo '<div class="feedDayWrap"><div class="feedDay">' . date(
                                "d/m/Y",
                                $r['date']
                            ) . '</div></div><div style="padding: 10px 10px 20px;">';
                        $fl3 = date("d-mY", $r['date']);
                    }

                    echo '<table class="feedTable" border="0" cellpadding="0" cellspacing="0">
    <tbody>
      <tr>
        <td class="feedIconWrap"><img src="ico/' . $im[$r['tip']] . '.gif" alt="' . $im1[$r['tip']] . '" class="feedIcon"> </td>
        <td class="feedStory">' . $rt . '</td>
        <td class="feedTime">' . date("H:i", $r['date']) . ' </td>
      </tr>
    </tbody>
  </table>';
                }

            }
            // end break page
        }
        // end while
    }

}

function getip()
{
    if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) {
        $ip = getenv("HTTP_CLIENT_IP");
    } elseif (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) {
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    } elseif (getenv("REMOTE_ADDR") && strcasecmp(
            getenv("REMOTE_ADDR"),
            "unknown"
        )
    ) {
        $ip = getenv("REMOTE_ADDR");
    } elseif (!empty($_SERVER['REMOTE_ADDR']) && strcasecmp(
            $_SERVER['REMOTE_ADDR'],
            "unknown"
        )
    ) {
        $ip = $_SERVER['REMOTE_ADDR'];
    } else {
        $ip = "unknown";
    }

    return ($ip);
}


function arm_txt($n)
{
    switch ($n) {
        case 1:
            return "Не требуется";
            break;
        case 2:
            return "Требуется";
            break;
        case 3:
            return "Отсканировано";
            break;
        default:
            return "Не требуется";
            break;
    }

}

function getUniversityName($u_id)
{
    $resultArray = array(
        'name' => '',
        'short_name' => '',
    );

    $sql = 'SELECT sdt_university.name
     , sdt_university.short_name
FROM
  sdt_university
WHERE
  sdt_university.id = ' . intval($u_id);
    $result = mysql_query($sql);
    if (mysql_num_rows($result)) {


        $row = mysql_fetch_assoc($result);

        $resultArray['name'] = $row['name'];
        $resultArray['short_name'] = $row['short_name'];
    }

    return $resultArray;
}

function writeStatistic($module, $action, $data)
{
    $user_id = !empty($_SESSION['u_id']) ? $_SESSION['u_id'] : 0;
    if (!is_array($data)) {
        $data = array($data);
    }
    $newData = array();
    foreach ($data as $key => $value) {
        if (is_string($value)) {
            $value = iconv('CP1251', 'UTF-8', $value);
        }
        $newData[$key] = $value;
    }

    // var_dump($newData);
    $data = json_encode($newData);
    // var_dump($data);
    $sql = sprintf(
        "INSERT INTO statistic
        (module, user_id, `action`, `data`)
          VALUES ('%s', '%d', '%s', '%s');",
        mysql_real_escape_string($module),
        $user_id,
        mysql_real_escape_string($action),
        mysql_real_escape_string($data)
    );
    mysql_query($sql);

//    echo var_dump(json_decode($data));
    return mysql_affected_rows();
}


define('SECRET', 'UEASTgiuthRhi0VM6cor');

function genpsw($length = 6)
{
    $str = "qwertyuiopasdfghjklzxcvbnm123456";
    $res = "";
    for ($i = 0; $i < $length; $i++) {
        $res .= $str[rand(0, strlen($str) - 1)];
    }

    return $res;

}


function __init__()
{
    if (Sdt_Config::isOutOfOrder()) {
        require __DIR__ . '/../outoforder.php';
        die();
    }

    db_connect();
}

function log_invalid_login($login)
{
    $selectUser = "select u_id, surname, firstname, fathername, univer_id from tb_users where login = '%s'";
    $res = mysql_query(vsprintf($selectUser, [
        mysql_real_escape_string($login),
    ]));
    $user_id = 'null';
    $username = 'null';

    $lc_id = 'null';
    if (mysql_num_rows($res)) {
        $row = mysql_fetch_assoc($res);
        $user_id = $row['u_id'];
        $lc_id = $row['univer_id'];
        $username = "'" . ($row['univer_id'] ? $row['surname'] : vsprintf('%s %s %s', [
                $row['surname'],
                $row['firstname'],
                $row['fathername'],
            ])) . "'";
    }

    $hc_sql = 'SELECT IF(hct.login_page_title IS NOT NULL AND hct.login_page_title <> \'\', hct.login_page_title, shc.short_name ) AS login FROM head_center_text hct
  LEFT JOIN sdt_head_center shc ON shc.id = hct.head_id where shc.id = ' . intval(@CURRENT_HEAD_CENTER);
    $hc_res = mysql_query($hc_sql);
    $head_name = mysql_result($hc_res, 0, 0);


    $sql = "insert into log_login_invalid (login, ip, user_id, head_id, user_name, head_name, lc_id) values ('%s','%s',%s,%d,%s,'%s',%s)";
    $ip = $_SERVER['REMOTE_ADDR'];

    $sql = vsprintf($sql, [
            mysql_real_escape_string($login),
            $ip,
            $user_id,
            @CURRENT_HEAD_CENTER,
            $username,
            $head_name,
            $lc_id
        ]
    );

    mysql_query($sql);
    $invalid_id = mysql_insert_id();

    $dt = new DateTime('-' . Sdt_Config::getLoginAttemptsTimeout() . ' second');
    $sql = "select count(*) as cc from log_login_invalid where created_at >= '%s' and ip = '%s'";
    $sql = vsprintf($sql, [
            $dt->format('Y-m-d H:i:s'),
            $ip,

        ]
    );

    $blockRes = mysql_query($sql);
    $count = mysql_result($blockRes, 0, 0);
//    var_dump($count,$sql,!$count ,$count < Sdt_Config::getLoginAttemptsNumber());die;
    if (!$count || $count < Sdt_Config::getLoginAttemptsNumber()) return;
    $sql_update = 'update log_login_invalid set blocked=1 where id=' . $invalid_id;
    mysql_query($sql_update);
    $sql = "
INSERT INTO log_login_block
(
  ip
 ,`data`
 ,block_until
 ,attempt_id
)
VALUES
(
  '%s' 
 ,'%s' 
 ,'%s'
 ,%d
);";
    $dtBlock = new DateTime('+' . Sdt_Config::getLoginAttemptsBlockTimeout() . ' second');
    $sql = vsprintf($sql, [

            $ip,
            json_encode([]),
            $dtBlock->format('Y-m-d H:i:s'),
            $invalid_id,
        ]
    );
//    var_dump($sql);die;
    mysql_query($sql);
}

function is_blocked()
{
    $ip = $_SERVER['REMOTE_ADDR'];
    $sql = "select count(*) as cc from log_login_block where blocked=1 and block_until >= '%s' and ip = '%s'";
    $dtBlock = new DateTime();
    $sql = vsprintf($sql, [
            $dtBlock->format('Y-m-d H:i:s'),
            $ip,


        ]
    );
//    var_dump($sql);die;
    $res = mysql_query($sql);
    $is_blocked = mysql_result($res, 0, 0);
    return !!$is_blocked;
}