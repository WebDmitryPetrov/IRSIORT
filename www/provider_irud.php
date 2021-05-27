<?php
include("include/config.php");
include("include/_func.php");
@auth();
header('Content-Type: text/html; charset=utf8');
if(!mysql_query ("SET CHARACTER SET utf8"))echo mysql_error();
if(!mysql_query ("SET character_set_connection = utf8"))echo mysql_error();
if(!mysql_query ("set character_set_results =  utf8 "))echo mysql_error();
if(!mysql_query ("set character_set_server = utf8"))echo mysql_error();
if(isset($_GET['0x0']))
	$_ACTION=$_GET['0x0'];
else
	die("не установлен action");
switch ($_ACTION)
{
		case 'show_change_pass_user_irud':
		$content=show_change_pass_user_irud();
		break;
		case 'change_pass_user_irud':
		$content=change_pass_user_irud();
		break;
	case '10':
		$_SESSION = array();
		session_destroy();
		header('location:./');
		break;
	default:
		$_SESSION = array();
		session_destroy();
		header('location:./');
		break;
}

function drop_teni_div($a='0'){
	if($a=='0')return true;
	if($a=='1')
	{
		$out= '<div id=divt class="framed">
<div class="f_tt"></div>
<div class="f_r">
 <div class="f_rr"></div>
<div class="f_b">
<div class="f_bb">
<div></div>
</div>

<div class="f_l">
<div class="f_ll">
<div></div>
</div>

<div class="f_c">';
		return $out;
	}
	if($a=='2')
	{
		$out= '</div> <!-- f_c -->
</div> <!-- f_l -->
</div> <!-- f_b -->
</div> <!-- f_r -->
</div> <!-- f_framed -->';
		return $out;
	}

}

function show_change_pass_user_irud()
{
	
	$result="";
	if(!isset($_GET['u_id'])) return 'Не указан пользователь';
	$id=mysql_escape_string($_GET['u_id']);
	//$result=show_users();
	//$result.=drop_teni_div(1);
	$result.= "<form>
Введите новый пароль<br>
    <input type=\"password\" name=\"password\" id=\"password\" onkeyup=\"passw()\"/> &#8212; Пароль<br/>
    <input type=\"password\" name=\"password2\" id=\"password2\" onkeyup=\"passw()\"/> &#8212; Пароль<br/>
    <input type=\"hidden\" name=\"u_id\" id=\"u_id\" value=\"$id\"/>
    <input disabled type=\"button\" name=\"button\" id=\"button\" value=\"Пароли не совпадают\" onclick=\"send_passwd();\"/><br/>
</form>";
	return 'content|'.$result;
}
function change_pass_user_irud()
{
	//if(!isset($_GET['u_id'])) return 'Не указан пользователь'; -это пиздец было!!!
	$id=$_SESSION['u_id'];
	if(!isset($_GET['password'])) return 'Не указан новый пароль';
	if(!isset($_GET['password2'])) return 'Не указан новый пароль';
	if($_GET['password']!=$_GET['password2']) return 'Пароли не совпадают';
	//TODO Декодировать из utf8 в cp1251 унас авторизация на cp1251
	// u-id нужно проверять их базы!
	 
	$pas=iconv('utf-8', 'cp1251',$_GET['password']);
	$password=md5(md5(mysql_real_escape_string(rawurldecode($pas))));

	$id=intval($id);
	$oldPasswordSql = 'select password from tb_users WHERE u_id='.$id;
    $oldPasswordRes = mysql_query($oldPasswordSql);
    $oldPassword = mysql_result($oldPasswordRes,0,0);
    if($oldPassword==$password){
        $result="<h2 style=\"color:red\">Пароль пользователя не изменен т.к. он совпадает со старым</h2>";
        return 'content|'.$result;
    }

	$res=mysql_query("UPDATE tb_users SET password_changed_at = now(), password='$password' WHERE u_id=$id");
	if(mysql_affected_rows()==1)
    {
        $result="
	<b>Пароль пользователя успешно изменен</b>";
        $_SESSION['password_changed_at']=time();
    }
	else
    {
        $result="<h2 style=\"color:red\">Пароль пользователя не изменен т.к. он совпадает со старым</h2>";
    }
	return 'content|'.$result;
}

echo $content;
