<?php

include("include/config.php");
include("include/_func.php");
@auth('./');
//redir_prava();



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
	case 'show_all_user_grup':
		$content=show_all_user_grup();
		break;
	case 'show_groups':
		hits_site('Зашел в Просмотр групп','1');
		$content=show_groups();
		break;
	case 'show_rename_group_field':
		hits_site('Просмотрел окно Редактирование группы #'.@$_GET['g_id'],'1');
		$content=show_rename_group_field();
		break;
	case 'group_rename':
		hits_site(getip().'Сделал ИЗМЕНЕНИЕ Имени В группе на "'.@$_GET['name'].'" ','3');
		$content=group_rename();
		break;
	case "show_add_group":
		hits_site('Посмотрел окно Добавления груп','1');
		$content=show_add_group();
		break;
	case 'group_add':
		hits_site('Добавил новую группу "'.@$_GET['name'].'"','2');
		$content=group_add();
		break;
	case 'show_delete_group':
		hits_site('Удалил группу #"'.@$_GET['g_id'].'"','4');
		$content=delete_group();
		break;
	case 'show_users':
		hits_site('Просмотрел на список пользователей ','5');
		$content=show_users();
		break;
	case 'show_rename_user_field':
		hits_site('Открыл окно редактирование пользователя ','5');
		$content=show_rename_user_field();
		break;
	case 'user_rename':
		hits_site('Редактировал пользователя #'.@$_GET['g_id'],'7');
		$content=user_rename();
		break;
	case 'user_rename_arm':
		hits_site('Просмотрел окно АРМ пользователя #'.@$_GET['g_id'],'5');
		$content=user_rename_arm();
		break;
	case 'user_arm_sql':
		hits_site('Изменил права для АРМ пользователя #'.@$_GET['g_id'],'10');
		$content= user_arm_sql().show_users();;
		break;

	case "show_add_user":
		hits_site('Просмотрел окно добавления пользователя','5');
		$content=show_add_user();
		break;
	case 'user_add':
		hits_site('Добавил пользователя '.@$_GET['surname'].' '.@$_GET['firstname'].' '.@$_GET['fathername'].' Login:'.$_GET['login'],'6');
		$content=user_add();
		break;
	case 'show_delete_user':
		hits_site('Удалил пользователя ' ,'8');
		$content=delete_user();
		break;
	case "show_change_pass_user":
		hits_site('Просмотрел окно смены пароля пользователя #'.@$_GET['g_id'],'5');
		$content=show_change_pass_user();
		break;
	case 'change_pass_user':
		hits_site(' Изменил пароль пользователю #'.@$_GET['g_id'],'7');
		$content=change_pass_user();
		break;
	case 'show_add_to_group':
		$content=show_add_to_group();
		break;
	case 'show_add_to_group_iframe':
		$content=show_add_to_group_iframe();
		break;
	case 'update_user_group':
		hits_site('Добавил пользователя в группу'.@$_GET['g_id'],'9');
		$content=update_user_group();
		break;
	case 'show_user_table':
		$content=show_user_table();
		break;
	case 'sort_fio':
		$content=sort_fio('');
		break;
	case 'show_stat':
		$content=show_stat();
		break;
	case 'show_all_users':
		$content=show_all_users();
		break;
	case 'show_kontr_otv_users':
		$content=show_kontr_otv_users();
		break;
	case 'show_kontr_sotr_users':
		$content=show_kontr_sotr_users();
		break;
	case 'show_kontr_ruk_users':
		$content=show_kontr_ruk_users();
		break;
	case 'show_arm_users':
		$content=show_arm_users();
		break;
	case 'show_rekt_read_users':
		$content=show_rekt_read_users();
		break;
	case 'show_count_docs_control':
		$content=show_count_docs_control();
		break;
	case 'show_count_arch_docs_control':
		$content=show_count_arch_docs_control();
		break;
	case 'show_count_vid_docs_control':
		$content=show_count_vid_docs_control();
		break;

	case 'show_docs_by_date':
		$content=show_docs_by_date();
		break;
			case 'show_arm_docs':
		$content=show_arm_docs();
		break;

    case 'show_watch_users':
        $content=show_watch_users();
        break;

	case '10':
		$_SESSION = array();
		session_destroy();
		header('location:./');
		break;

	case 'user_rename_arm2':
	$content='';
	echo user_rename_arm2();

	break;

	case 'update_arm_new1':
	$content='';
	echo user_arm_sql(1);

		break;
case 'arm_delete_1':
	$content='';
	echo arm_delete_1();

		break;

case 'arm_delete_2':
	$content='';
	echo arm_delete_2();
case 'show_docs_from_rukovod':
	$content='';
	echo show_docs_from_rukovod();
		break;

case 'show_docs_from_rektorat':
	$content='';
	echo show_docs_from_rektorat();
		break;
	case 'show_docs_from_static_group':
	$content='';
	echo show_docs_from_static_group();
		break;

			case 'count_docs_from_statist':
	$content='';
	echo count_docs_from_statist();
		break;


	default:
		//$_SESSION = array();
		//session_destroy();
		//header('location: /');
		$content='ТЕСТ!';
		break;
}

echo $content;

function drop_teni_div($a='0'){
	if($a=='0')return true;
	if($a=='1')
	{
		$out= '<div class="framed">
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

function show_groups()

{

	$result="<a href='#' onClick=\"show_add_group();\">Добавить новую группу</a>";
	$res=mysql_query("select g_name,g_id from tb_groups") or die(mysql_error());
	$result.="<table  border='0'>";
	$result.="<tr><th width='16px'></th><th width='16px'></th><th width='16px'></th><th>Название группы</th></tr>";

while($row= mysql_fetch_array($res))
	{
		$result.= "\t<tr><td><a href='#' onClick=\"show_add_to_group('".$row[1]."')\"><img  border=0 src='./ico/add_to_group.gif'/></a></td>
<td><a href='#' onClick=\"show_rename_group_field('".$row[1]."')\"><img  border=0 src='./ico/textfield_rename.gif'/></a></td>
<td><a href='#' onClick=\"show_delete_group('".$row[1]."')\"><img border=0 src='./ico/cross.gif'/></a></td>
<td><span id='".$row[1]."'>".$row[0]."( ID #".$row['g_id'].")</span></td></tr>\n";
		//	$result.= "\t<tr><td><img src='./ico/textfield_rename.gif'/></td><td><img src='./ico/cross.gif'/></td><td>".$row[0]."</td></tr>\n";
	}
	$result.= "</table>";

	return $result;
}
/*
 SELECT `u_id` , `login` , concat( `surname` , ' ', `firstname` , ' ', `fathername` ) AS fio, group_concat( `fk_g_id` ) AS gr FROM `tb_relations` RIGHT JOIN tb_users ON `fk_u_id` = `u_id` GROUP BY `fk_u_id` ORDER BY `fio` ASC
 */

function show_all_user_grup(){
	$res=mysql_query("SELECT  concat(`login`,' ',`surname` , ' ', `firstname` , ' ', `fathername` ) AS fio, `u_id` , group_concat( `fk_g_id` ) AS gr FROM `tb_relations` RIGHT JOIN tb_users ON `fk_u_id` = `u_id` where (univer_id is null or univer_id= 0) GROUP BY `fk_u_id` ORDER BY `fio` ASC ") or die(mysql_error());
	$result="<table  border='0'>";
	$result.="<tr><th width='16px'></th><th width='16px'></th><th width='16px'></th><th>Название группы</th></tr>";
	$result.="<tr><td><a href='#' onClick=\"show_add_to_group('ARM')\"><img  border=0 src='./ico/add_to_group.gif'/></a></td>
<td><a href='#' onClick=\"show_rename_group_field('ARM')\"><img  border=0 src='./ico/textfield_rename.gif'/></a></td>
<td><a href='#' onClick=\"show_delete_group('ARM')\"><img border=0 src='./ico/cross.gif'/></a></td>
<td><span id='ARM'>ARM Установка параметров должность и ранг для пользователей(Другая таблица)</span></td></tr>";
	while($row= mysql_fetch_array($res))
	{
		$result.= "\t<tr><td><a href='#' onClick=\"show_add_to_group('".$row[1]."')\"><img border=0 src='./ico/add_to_group.gif'/></a></td>
<td><a href='#' onClick=\"show_rename_group_field('".$row[1]."')\"><img  border=0 src='./ico/textfield_rename.gif'/></a></td>
<td><a href='#' onClick=\"show_delete_group('".$row[1]."')\"><img border=0 src='./ico/cross.gif'/></a></td>
<td><span id='".$row[1]."'>".$row[0]."</span></td></tr>\n";
		//	$result.= "\t<tr><td><img src='./ico/textfield_rename.gif'/></td>		<td><img src='./ico/cross.gif'/></td><td>".$row[0]."</td></tr>\n";
	}
	$result.= "</table>";
	return $result;
}

function show_rename_group_field()
{

	if(!isset($_GET['g_id'])&&!is_numeric($_GET['g_id'])) return 'неправильный формат числа';
	$id=mysql_escape_string($_GET['g_id']);
	$res=mysql_query("select g_name from tb_groups where g_id=$id") or die(mysql_error());
	if(mysql_num_rows($res)!=1)return 'ненайдена запись';

	$result=show_groups();
	$result.=drop_teni_div(1);
	$result.= "<div id=\"apDiv1\">";
	$result.= "<input type=\"button\" name=\"close\" id=\"close\" value=\"Закрыть\" onClick=\"document.getElementById('apDiv1').style.display='none';document.getElementById('apDiv2').style.display='none';\" /><span style=\"font-size:18px;\"> &#8212; ".wordwrap(mysql_result($res,0,0),46, '<br />')."</span><br />
	<input type=\"text\" name=\"new_name\" id=\"new_name\" /><br>
	<input name=\"g_id\" id=\"g_id\"  type=\"hidden\" value=\"$id\" />
	<input type=\"button\" name=\"button\" id=\"button\" value=\"Переименовать\" onClick=\"group_rename();\"/><br/>
	</div>".drop_teni_div(2)."
<div id=\"apDiv2\"></div>\n";
	return $result;

}
function group_rename()
{
	if(!isset($_GET['g_id'])&&!is_numeric($_GET['g_id'])) return 'неправильный формат числа';
	if(!isset($_GET['name'])) return 'Не указано новое имя группы';
	$id=mysql_escape_string($_GET['g_id']);
	$name=mysql_escape_string($_GET['name']);
	$res=mysql_query("UPDATE tb_groups SET g_name='$name' WHERE g_id=$id");
	if(mysql_affected_rows()==1)
	$result="<b>Группа успешно переименнована</b><br/>";
	else
	$result="<b>Переименнование прошло неудачно</b><br/>".mysql_error();
	$result.=show_groups();
	return $result;
}

function show_add_group()
{
	$result=show_groups();
	$result.=drop_teni_div(1);
	$result.= "
<div id=\"apDiv1\">
<input type=\"button\" name=\"close\" id=\"close\" value=\"Закрыть\" onClick=\"document.getElementById('apDiv1').style.display='none';document.getElementById('apDiv2').style.display='none';\">
<span style=\"font-size:18px\">Добавление новой группы</span><br>
<input type=\"text\" name=\"new_name\" id=\"new_name\">
<input type=\"button\" name=\"button\" id=\"button\" value=\"Добавить\" onClick=\"group_add();\"><br>
</div>".drop_teni_div(2)."<div id=\"apDiv2\"></div>\n";
	return $result;
}
function group_add()
{
	if(!isset($_GET['name'])) return 'Не указано имя новой группы';
	$name=mysql_escape_string($_GET['name']);
	$res=mysql_query("INSERT INTO `tb_groups` (`g_id`,`g_name`) VALUES (NULL,'$name')");
	if(mysql_affected_rows()==1)
	$result="<b>Группа успешно добавлена</b><br/>";
	else
	$result="<b>Добавление прошло неудачно</b><br/>".mysql_error();
	$result.=show_groups();
	return $result;


}

function delete_group()
{
	if(!isset($_GET['g_id'])&&!is_numeric($_GET['g_id'])) return 'Не указано имя новой группы';
	$g_id=mysql_escape_string($_GET['g_id']);
	$res=mysql_query("DELETE FROM `tb_groups` WHERE `g_id`=$g_id");
	if(mysql_affected_rows()==1)
	{
		@mysql_query("DELETE FROM tb_relations WHERE fk_g_id=$g_id");
		$result="<b>Группа успешно удалена</b><br/>";
	}
	else
	$result="<b>Удаление прошло неудачно</b><br/>".mysql_error();
	$result.=show_groups();
	return $result;
}
function show_users()
{
	$result="<a href='#' onClick=\"show_add_user();\">Добавить нового пользователя</a>";

	$result.=sort_fio2();
	$result.='<br><br>'.show_user_all();
	return $result;



}
function show_user_all(){
	if(!isset($_GET['b'])) return 'Не указана буква';
	$b=mysql_escape_string($_GET['b']);

	$result="";
	if($_GET['b'] == '_')
	$sql="SELECT * FROM tb_users WHERE  (univer_id is null or univer_id = 0) and  `surname` LIKE  '1%' OR  `surname` LIKE  '2%' OR  `surname` LIKE  '3%' OR  `surname` LIKE  '4%' OR  `surname` LIKE  '5%' OR  `surname` LIKE  '6%' OR  `surname` LIKE  '7%' OR  `surname` LIKE  '8%' OR  `surname` LIKE  '9%' OR  `surname` LIKE  '0%' OR  `surname` LIKE  ' %' OR  `surname` =  '' ORDER BY `tb_users`.`surname` ASC";
	elseif($b == 'A-Z'){
		$sql='SELECT * FROM tb_users WHERE  (univer_id is null or univer_id= 0) and  (`surname` LIKE  \'A%\' ';
		for($a='B';$a<'Z';$a++)
		$sql.='OR  `surname` LIKE  \''.$a.'%\' ';
		$sql.='OR  `surname` LIKE  \'Z%\' ) ORDER BY `tb_users`.`surname` ASC';
	}else
	$sql="select * from tb_users WHERE (univer_id is null or univer_id= 0) and `surname` LIKE '$b%' ORDER BY `tb_users`.`surname` ASC";

	$res=mysql_query($sql) or die(mysql_error());
	$result.="<table  border='0' width=100%>";
	$result.="<tr>
		<th>Настройки</th>
	<th>Фамилия (#ID)</th>
	<th>Имя</th>
	<th>Отчества</th>
	<th>Login</th>

	</tr>";


	while($row= mysql_fetch_array($res))
	{

		$result.= "\t<tr class=\"trusr\">
<td style=\"width:110px\">
<!-- <a href='#' onClick=\"show_arm_user_field('".$row['u_id']."')\" title=\"Добавить в АРМ\"><img  border=0 src='./ico/arm.png'></a> -->
<a href='#' onClick=\"show_rename_user_field('".$row['u_id']."')\" title=\"Изменить данные\"><img  border=0 src='./ico/textfield_rename.gif'></a>
<a href='#' onClick=\"show_change_pass_user('".$row['u_id']."')\" title=\"Изменить пароль\"><img border=0 src='./ico/pass.gif'></a>
<a href='#' onClick=\"show_delete_user('".$row['u_id']."')\" title=\"Удалить\"><img border=0 src='./ico/cross.gif'></a> </td>

<td style=\"width:23%\">".$row['surname']." (#".$row['u_id'].")</td><td style=\"width:20%\">".$row['firstname']."</td><td>".$row['fathername']."</td><td style=\"width:20%\">".$row['login']."</td>

</tr>\n";

	}
	$result .="</table>";
	return $result;

}


function show_rename_user_field()
{

	if(!isset($_GET['u_id']) && !is_numeric($_GET['u_id'])) return 'неправильный формат числа';
	$id=mysql_escape_string($_GET['u_id']);
	$res=mysql_query("select * from tb_users where u_id=$id") or die(mysql_error());
	if(mysql_num_rows($res)!=1)return 'ненайдена запись';


	$result=drop_teni_div(1);
	$result.= "<div id=\"apDiv3\"> <h3>
 <input type=\"button\" name=\"close\" id=\"close\" value=\"Закрыть\" onClick=\"document.getElementById('apDiv3').style.display='none';document.getElementById('apDiv2').style.display='none';\" />Изменение данныйх пользователя</h3><br>
    <input type=\"text\" name=\"login\" id=\"login\"  value=\"".mysql_result($res,0,'login')."\"/> &#8212; Логин<br/>
    <input type=\"text\" name=\"surname\" id=\"surname\"  value=\"".mysql_result($res,0,'surname')."\"/> &#8212; Фамилия<br/>
    <input type=\"text\" name=\"firstname\" id=\"firstname\"  value=\"".mysql_result($res,0,'firstname')."\"/> &#8212; Имя<br/>
    <input type=\"text\" name=\"fathername\" id=\"fathername\"  value=\"".mysql_result($res,0,'fathername')."\"/> &#8212; Отчество<br/>
    <input type=\"text\" name=\"univer_id\" id=\"univer_id\"  value=\"".mysql_result($res,0,'univer_id')."\"/> &#8212; ID университета<br/>
	<input name=\"u_id\" id=\"u_id\"  type=\"hidden\" value=\"$id\" />
	<input type=\"button\" name=\"button\" id=\"button\" value=\"Переименовать\" onClick=\"user_rename();\"/><br/>



	</div>".drop_teni_div(2)."
<div id=\"apDiv2\"></div>\n";

	return $result.show_users();

}
function user_rename_arm($a='')
{
	$sel1=$sel2='';
	if(!isset($_GET['u_id']) && !is_numeric($_GET['u_id'])) return 'неправильный формат числа';

	$id=mysql_escape_string($_GET['u_id']);
	if($a == ''){
		$res=mysql_query("Select * From users left Join tb_users On users.id = tb_users.u_id where users.id = $id")
		or die(mysql_error());

		if(mysql_num_rows($res))
		{
			$dolz=mysql_result($res,0,2);
			$rang=mysql_result($res,0,1);

			if(mysql_result($res,0,3) == '1'){			$sel1=' selected="selected"';			$sel2='';}else {
				$sel1=' ';			$sel2=' selected="selected"';}

		} else {
			$dolz=$rang=$sel1='';
			$sel2=' selected="selected"';
		}
	}else {

		$id=mysql_escape_string($_GET['u_id']);
		$dolz=mysql_escape_string($_GET['login']);
		$rang=mysql_escape_string($_GET['surname']);
		$firstname=mysql_escape_string($_GET['firstname']);

		if($firstname == '1')
		$sel1=' selected="selected"';
		else
		$sel2=' selected="selected"';

	}
	$result=drop_teni_div(1);
	$result.= "<div id=\"apDiv3\"> <h3>
 <input type=\"button\" name=\"close\" id=\"close\" value=\"Закрыть\" onClick=\"document.getElementById('apDiv3').style.display='none';document.getElementById('apDiv2').style.display='none';\" /> Добавить / Изменить доступ в АРМ</h3><br>
 ";
	if($a != '')$result.= '<h4>'.$a.'</h4>';

	$result.="<input type=\"text\" name=\"login\" id=\"login\"  value=\"".$dolz."\"/> &#8212; Должность<br/>
    <input type=\"text\" name=\"surname\" id=\"surname\"  value=\"".$rang."\"/> &#8212; Ранг (1 - самый высокий)<br/>
 Права на запись  &#8212;
       <select name=\"firstname\" id=\"firstname\">
        <option value=\"1\" ".$sel1.">Да</option>
        <option value=\"0\" ".$sel2.">Нет</option>
	</select>
	<br/>
	<input name=\"u_id\" id=\"u_id\"  type=\"hidden\" value=\"$id\" />
	<input type=\"button\" name=\"button\" id=\"button\" value=\"Добавить или Изменить\" onClick=\"user_rename_arm();\"/><br/>



	</div>".drop_teni_div(2)."
<div id=\"apDiv2\"></div>\n";

	return $result.show_users();

}
//для новых окон!
function user_rename_arm2($a='')
{ //a - сообщение для ошибки
	$sel1=$sel2='';
	if(empty($_GET['u_id']) && !is_numeric(@$_GET['u_id'])) return 'неправильный формат числа';

	$id=mysql_escape_string($_GET['u_id']);
	if($a == ''){
		$res=mysql_query("Select * From users left Join tb_users On users.id = tb_users.u_id where users.id = $id")
		or die(mysql_error());

		if(mysql_num_rows($res))
		{
			$dolz=mysql_result($res,0,2);
			$rang=mysql_result($res,0,1);

			if(mysql_result($res,0,3) == '1'){			$sel1=' selected="selected"';			$sel2='';}else {
				$sel1=' ';			$sel2=' selected="selected"';}

		} else {
			$dolz=$rang=$sel1='';
			$sel2=' selected="selected"';
		}
	}else {

		$id=mysql_escape_string($_GET['u_id']);
		$dolz=mysql_escape_string($_GET['login']);
		$rang=mysql_escape_string($_GET['surname']);
		$firstname=mysql_escape_string($_GET['firstname']);

		if($firstname == '1')
		$sel1=' selected="selected"';
		else
		$sel2=' selected="selected"';

	}

	$result= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
</head>
<body> Добавить / Изменить доступ в АРМ<br>
<form method="GET" action="provider_admin.php">';
	if($a != '')$result.= '<h4>'.$a.'</h4>';

	$result.="<input type=\"text\" name=\"login\" id=\"login\"  value=\"".$dolz."\"/> &#8212; Должность<br/>
    <input type=\"text\" name=\"surname\" id=\"surname\"  value=\"".$rang."\"/> &#8212; Ранг (1 - самый высокий)<br/>
 Права на запись  &#8212;
       <select name=\"firstname\" id=\"firstname\">
        <option value=\"1\" ".$sel1.">Да</option>
        <option value=\"0\" ".$sel2.">Нет</option>
	</select>
	<br/>
	<input name=\"u_id\" id=\"u_id\"  type=\"hidden\" value=\"$id\" />
	<input name=\"0x0\" type=\"hidden\" value=\"update_arm_new1\" />
	<input type=\"submit\" value=\" OK \" style=\"width:120px;\" /></form>
</body>
</html>";

	return $result;

}
function arm_delete_1($a='')
{ //a - сообщение для ошибки

	$sel1=$sel2='';
	if(empty($_GET['u_id']) && !is_numeric(@$_GET['u_id'])) return 'неправильный формат числа';

	$id=mysql_escape_string($_GET['u_id']);
	if($a == ''){
		$res=mysql_query("Select `rang`, `dolz`, `write`, concat(`surname`,' ',`firstname`,' ',`fathername`) as fio From users left Join tb_users On users.id = tb_users.u_id where users.id = $id")
		or die(mysql_error());

		if(mysql_num_rows($res))
		{
			$dolz=mysql_result($res,0,1);
			$rang=mysql_result($res,0,0);
			$fio=mysql_result($res,0,3);
			if(mysql_result($res,0,2) == '1'){
			$sel1=' Да';

			}else {
			$sel1=' НЕТ';}

		} else {
			$dolz=$rang=$sel1=' Пусто ';
			$sel1=' НЕТ ';
		}
	}

	$result= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
</head>
<body> Удаление из таблицы АРМ<br>
';
	if($a != '')
	{ $result.= '<h4>'.$a.'</h4>'; }
 else {
	$result.="
	<form method=\"GET\" action=\"provider_admin.php\">
	ВЫ хотите удалить $fio ?<br>
 Должность &#8212; $dolz <br/>
Ранг (1 - самый высокий) &#8212; $rang <br/>
 Права на запись  &#8212;  $sel1	<br/>
 	<input name=\"u_id\" id=\"u_id\"  type=\"hidden\" value=\"$id\" />
	<input name=\"0x0\" type=\"hidden\" value=\"arm_delete_2\" />
	<input type=\"submit\" value=\" OK \" style=\"width:120px;\" /></form>";
}

	$result.="</body></html>";

	return $result;

}
function arm_delete_2(){
	if(empty($_GET['u_id']) && !is_numeric(@$_GET['u_id'])) return arm_delete_1('неправильный формат числа<br> Удаление не возможно!');
	$u_id=mysql_escape_string($_GET['u_id']);
	$res=mysql_query("DELETE FROM users WHERE id=$u_id");
	if(mysql_affected_rows()==1)
	{
	return arm_delete_1("Удалил!");
	}else {
	return arm_delete_1("Удаление прошло не удачно");
	 }
}
//для новых окон!

function user_arm_sql($out='')
{

	if(empty($_GET['u_id']) && !is_numeric(@$_GET['u_id'])) if(empty($out))return user_rename_arm('неправильный формат числа'); else return user_rename_arm2('неправильный формат числа');

	if(empty($_GET['login'])) return user_rename_arm("Не указана должность");

	ereg("([0-9]{1,8})", $_GET["surname"], $regs);
	#if ($regs[0] !=  $_GET["surname"] or !is_numeric($_GET["surname"]))
	#return user_rename_arm('Не указан Ранг или неправильный формат числа, максимум 8 символов');

	//firstname
	if(empty($_GET['firstname'])and  !is_numeric($_GET['firstname'])) if(empty($out)) return user_rename_arm($_GET['surname'].'Неправильны права на запись'); else return user_rename_arm2($_GET['surname'].'Неправильны права на запись');
	#if(!isset($_GET['fathername'])) return user_rename_arm('Не указано отчество пользователя');

	$id=mysql_escape_string($_GET['u_id']);
	$login=mysql_escape_string($_GET['login']);
	$surname=mysql_escape_string($_GET['surname']);
	$firstname=mysql_escape_string($_GET['firstname']);
	#$fathername=mysql_escape_string($_GET['fathername']);
	$res=mysql_query("Select * From users Inner Join tb_users On users.id = tb_users.u_id where users.id = $id") or die(mysql_error());
	if(mysql_affected_rows()!=1) { // Ненайден !
		$res2=mysql_query("INSERT INTO  `users` (`id` ,`rang` ,`dolz` ,`write`)VALUES ('$id',  '$surname',  '$login',  '$firstname')") or die(mysql_error());
		if(mysql_affected_rows()==1)
		$result="Информация для системы <b>АРМ успешно добавлена</b><br/>";
		else
		$result="<b>Информация для системы АРМ не изменена</b><br/>".mysql_error();
		//$result.=show_users();
		return $result;

	}ELSE {
		//..есть такой

		//$sql="UPDATE users SET , rang='$surname', `write`='$firstname' WHERE id='$id'";
		$sql="UPDATE  `users` SET  `dolz`='$login', `rang` =  '$surname',`write`='$firstname' WHERE  `users`.`id` =$id LIMIT 1 ;";

		$res2=mysql_query($sql) or die(mysql_error());
		if(mysql_affected_rows()==1)
		$result="Информация для системы <b>АРМ успешно изменена!</b><br/>";
		else
		$result="<b>Информация для системы АРМ не изменена</b><br/>".mysql_error();
		//$result.=show_users();

		if(empty($out))return $result; else

		return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
</head>
<body> Добавить / Изменить доступ в АРМ<br> <br>'.
	$result
.'</body></html>';

	}

}


function user_rename()
{
	if(!isset($_GET['u_id'])&&!is_numeric($_GET['u_id'])) return 'неправильный формат числа';
	if(!isset($_GET['login'])) return 'Не указан логин';

	if(!isset($_GET['surname'])) return 'Не указана фамилия пользователя';
	if(!isset($_GET['firstname'])) return 'Не указано имя пользователя';
	if(!isset($_GET['fathername'])) return 'Не указано отчество пользователя';

	$id=mysql_escape_string($_GET['u_id']);
	$login=mysql_escape_string($_GET['login']);
	$surname=mysql_escape_string($_GET['surname']);
	$firstname=mysql_escape_string($_GET['firstname']);
	$fathername=mysql_escape_string($_GET['fathername']);
    $univer_id=mysql_escape_string($_GET['univer_id']);

	$res=mysql_query("UPDATE tb_users SET login='$login',surname='$surname',firstname='$firstname',fathername='$fathername', univer_id = '$univer_id' WHERE u_id=$id");
	if(mysql_affected_rows()==1)
	$result="<b>Информация о пользователе успешно изменена</b><br/>";
	else
	$result="<b>Информация о пользователе не изменена</b><br/>".mysql_error();
	$result.=show_users();
	return $result;
}



function show_add_user()
{
	$result=show_users();
	$result.=drop_teni_div(1);
	$result.= "<div id=\"apDiv3\">
    <input type=\"button\" name=\"close\" id=\"close\" value=\"Закрыть\" onClick=\"document.getElementById('apDiv3').style.display='none';document.getElementById('apDiv2').style.display='none';\" /><h3>Добавление пользователя</h3>

    <input type=\"text\" name=\"login\" id=\"login\"/> - Логин<br/>
    <input type=\"password\" name=\"password\" id=\"password\"/> - Пароль<br/>
    <input type=\"text\" name=\"surname\" id=\"surname\"/> - Фамилия<br/>
    <input type=\"text\" name=\"firstname\" id=\"firstname\"/> - Имя<br/>
    <input type=\"text\" name=\"fathername\" id=\"fathername\"/> - Отчество<br/>
    <input type=\"text\" name=\"univer_id\" id=\"univer_id\"/> - ID университета<br/>

    <input type=\"button\" name=\"button\" id=\"button\" value=\"Добавить\" onClick=\"user_add();\"/><br/>



</div>".drop_teni_div(2)."
<div id=\"apDiv2\"></div>\n";
	return $result;
}


function user_add()
{
	if(!isset($_GET['login'])) return 'Не указан логин';
	if(!isset($_GET['password'])) return 'Не указан пароль';
	if(!isset($_GET['surname'])) return 'Не указана фамилия пользователя';
	if(!isset($_GET['firstname'])) return 'Не указано имя пользователя';
	if(!isset($_GET['fathername'])) return 'Не указано отчество пользователя';

	$login=mysql_escape_string($_GET['login']);
	$password=mysql_escape_string($_GET['password']);
	$surname=mysql_escape_string($_GET['surname']);
	$firstname=mysql_escape_string($_GET['firstname']);
	$fathername=mysql_escape_string($_GET['fathername']);
    $univer_id=mysql_escape_string($_GET['univer_id']);



    $res=mysql_query("INSERT INTO `tb_users` (`u_id`,`login`,`password`,`surname`,`firstname`,`fathername`,`univer_id`) VALUES (NULL,'$login',md5(md5('$password')),'$surname','$firstname','$fathername','$univer_id')");
	if(mysql_affected_rows()==1)
	$result="<b>Пользователь успешно добавлен</b><br/>";
	else
	$result="<h2 style=\"color:red\"><b>Добавление пользователя прошло неудачно!!</b></h2><br/>Причина <em>".mysql_error().'</em><br/>';
	$result.=show_users();
	return $result;
}

function delete_user()
{
	if(!isset($_GET['u_id'])&&!is_numeric($_GET['u_id'])) return 'не указан пользователь';
	$u_id=mysql_escape_string($_GET['u_id']);
	$res=mysql_query("DELETE FROM tb_users WHERE u_id=$u_id");
	if(mysql_affected_rows()==1)
	{
//удалени из груп
	@mysql_query("DELETE FROM tb_relations WHERE fk_u_id=$u_id");
//удаление из арм
	@mysql_query("DELETE FROM users WHERE id=$u_id");
//удаление с hhd лишнего
 	@mysql_query("OPTIMIZE TABLE `tb_relations` , `tb_users` , `users`");
	$result="<b>Пользователь успешно удален, все его группы были удалены</b><br/>";

	}
	else
	$result="<h2 style=\"color :red\"><b>Удаление прошло неудачно</b></h2><br/>Причина <em>".mysql_error().'</em><br/>';
	$result.=show_users();
	return $result;
}
function show_change_pass_user()
{
	if(!isset($_GET['u_id'])) return 'Не указан пользователь';
	$id=mysql_escape_string($_GET['u_id']);
	$result=show_users();
	$result.=drop_teni_div(1);
	$result.= "<div id=\"apDiv1\">
	<input type=\"button\" name=\"close\" id=\"close\" value=\"Закрыть\" onClick=\"document.getElementById('apDiv1').style.display='none';document.getElementById('apDiv2').style.display='none';\" /><h3>Изменение пароля</h3>
	Ведите новый пароль<br>
	<input type=\"password\" name=\"password\" id=\"password\"/> &#8212; Пароль<br/>
	<input type=\"hidden\" name=\"u_id\" id=\"u_id\" value=\"$id\"/>
	<input type=\"button\" name=\"button\" id=\"button\" value=\"Изменить\" onClick=\"change_pass_user();\"/><br/>



	</div>
".drop_teni_div(2)."
<div id=\"apDiv2\"></div>\n";
	return $result;
}


function change_pass_user()
{
	if(!isset($_GET['u_id'])) return 'Не указан пользователь';
	if(!isset($_GET['password'])) return 'Не указан новый пароль';
	$password=md5(md5(mysql_escape_string(rawurldecode($_GET['password']))));

	$id=mysql_escape_string($_GET['u_id']);
	$res=mysql_query("UPDATE tb_users SET password='$password' WHERE u_id=$id");
	if(mysql_affected_rows()==1)
	$result="<b>Пароль пользователя успешно изменен</b><br/>";
	else
	$result="<h2 style=\"color:red\"><b>Пароль пользователя не изменен</b></h2><br/>".mysql_error();
	$result.=show_users();

	return $result;
}


function show_add_to_group()
{
	if(!isset($_GET['g_id']))return 'Не указана группа';
	$id=mysql_escape_string($_GET['g_id']);
	if($id == 'ARM'){

		$sql="SELECT `id`, `u_id`, `rang`, `dolz`, `write`, concat(`surname`,' ',`firstname`,' ',`fathername`) as fio, login FROM `users` LEFT JOIN `tb_users` ON id = u_id ORDER BY `tb_users`.`surname` ";
		$res2=mysql_query($sql) or die(mysql_error());
		$result="<Br><br><table  border='0' width=100%>";
		$result.="<tr><th>Должность, Ранг, зап. </th><th>Фамилия Имя Отчества</th><th>login</th></tr>";
		while($row= mysql_fetch_array($res2))
		{
			if(is_null($row['u_id'])){
				$row['fio'] = 'Удален ';
				$row['u_id']= 'Пусто записи нет';

			}
			if($row['write'] == 1) $w='Да'; else  $w='Нет';
			$result.= "<tr>
<td><a href=\"provider_admin.php?0x0=arm_delete_1&u_id=".$row['id']."&ran=".time()."\" onclick=\"return hs.htmlExpand(this, { contentId: 'highslide-html-9', objectType: 'iframe',
			objectWidth:  527, objectHeight:200, objectLoadTime: 'after', allowWidthReduction: 1} )\"><img border=0 src='./ico/cross.gif'/></a>
<a href=\"provider_admin.php?0x0=user_rename_arm2&u_id=".$row['id']."&ran=".time()."\" title=\"Перейти к редактированию\" onclick=\"return hs.htmlExpand(this, { contentId: 'highslide-html-9', objectType: 'iframe',
			objectWidth: 527, objectHeight: 200, objectLoadTime: 'after', allowWidthReduction: 1} )\"><img  border=0 src='./ico/textfield_rename.gif'/></a>
 ".$row['dolz'].", ".$row['rang'].", ".$w." </td>
<td>".$row['fio']." (#".$row['u_id'].")</td>
<td>".$row['login']."</td>
</tr>\n";

		}
		$result .="</table>";

	}else{
		$res1=mysql_query("select g_name from tb_groups where g_id='$id'");
		if(mysql_num_rows($res1)!=1) return "Такой группы не существует";
		$gname=mysql_result($res1,0,0);
		$result=show_groups().drop_teni_div(1);
		$result.="<div id=\"apDiv4\">
<input type=\"button\" name=\"close\" id=\"close\" value=\"Закрыть\" onClick=\"document.getElementById('apDiv4').style.display='none';document.getElementById('apDiv2').style.display='none';\" /><span style=\"font-size:18px\">Вы редактируете группу: ".$gname."</span>";

		$result.="<iframe src=\"provider_admin.php?0x0=show_add_to_group_iframe&g_id=".$id."\" vspace=\"0\" hspace=\"0\" marginwidth=\"0\" marginheight=\"0\" style=\"border: 0px solid;\" frameborder=\"1\" width=\"99%\" height=\"500\" style=\"height:500px;\" ></iframe>";
		$result.= "</div>".drop_teni_div(2)."
	<div id=\"apDiv2\"></div>\n";
	}

	return $result;
}

function show_add_to_group_iframe()
{
	if(!isset($_GET['g_id'])) return 'Не указана группа';
	$id=mysql_escape_string($_GET['g_id']);

	$result="<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\"><html xmlns=\"http://www.w3.org/1999/xhtml\"><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"><script  type=\"text/javascript\"  language=\"JavaScript\" src=\"./include/script.js\"></script><link rel=\"stylesheet\" type=\"text/css\" href=\"css/menu_alfavit.css\"><title>Frame windows</title></head><body style=\"margin:0px; paddeng:0px;\">";


	//$bykva='Р';

	$result.=sort_fio($id);
	$result.='<br><br>';
	$result.='<div id="content">'.show_user_table().'</div>';
	$result .="</body></html>";
	return $result;
}

function show_add_to_group1($id)
{

	$t='';
	$res=mysql_query("select fk_u_id from tb_relations where fk_g_id=$id");
	while($row= mysql_fetch_array($res)){ $t.=' or `u_id` = '.$row["fk_u_id"].' '; }

	$sql="select * from tb_users where `u_id` = -1 $t ORDER BY `tb_users`.`surname` ASC ";
	$res2=mysql_query($sql) or die(mysql_error());
	$result="<Br><br><table  border='0' width=100%>";
	$result.="<tr><th></th><th>Фамилия</th><th>Имя</th><th>Отчества</th><th>login</th></tr>";
	while($row= mysql_fetch_array($res2))
	{
		$result.= "\t<tr>
<td> <input name=\"ch[]\" type=\"checkbox\" value='".$row['u_id']."' checked onChange=\"change_user_group(this,".$id.")\"></td>
<td>".$row['surname']." (#".$row['u_id'].")</td>
<td>".$row['firstname']."</td>
<td>".$row['fathername']."</td>
<td>".$row['login']."</td>
</tr>\n";

	}
	$result .="</table>";


	return  $result;
}

function show_user_table(){
	if(!isset($_GET['g_id']) and !is_numeric($_GET['g_id'])) return '<Br>Не указана группа';
	$id=mysql_escape_string($_GET['g_id']);

	if(!isset($_GET['b'])) return show_add_to_group1($id);//'<Br>Не указана буква';
	$b=mysql_escape_string($_GET['b']);

	if($_GET['b'] == '_')
	$sql="SELECT * FROM tb_users  where (univer_id is null or univer_id= 0) and `surname` LIKE  '1%'
OR  `surname` LIKE  '2%'
OR  `surname` LIKE  '3%'
OR  `surname` LIKE  '4%'
OR  `surname` LIKE  '5%'
OR  `surname` LIKE  '6%'
OR  `surname` LIKE  '7%'
OR  `surname` LIKE  '8%'
OR  `surname` LIKE  '9%'
OR  `surname` LIKE  '0%'
OR  `surname` LIKE  ' %'
OR  `surname` =  ''";
	elseif($b == 'A-Z'){

		$sql='SELECT * FROM tb_users where (univer_id is null or univer_id= 0) and  `surname` LIKE  \'A%\' ';
		for($a='B';$a<'Z';$a++)
		$sql.='OR  `surname` LIKE  \''.$a.'%\' ';
		$sql.='OR  `surname` LIKE  \'Z%\' ORDER BY `tb_users`.`surname` ASC';

	}else
	$sql="select * from tb_users where (univer_id is null or univer_id= 0) and `surname` LIKE '$b%' ORDER BY `tb_users`.`surname` ASC";



	$res=mysql_query("select fk_u_id from tb_relations where fk_g_id=$id");
	$ch=array();
	while($row= mysql_fetch_array($res)){ $ch[$row["fk_u_id"]]='1'; }

	$res=mysql_query($sql) or die(mysql_error());
	$result="<table  border='0' width=100%>";
	$result.="<tr><th></th><th>Фамилия</th><th>Имя</th><th>Отчества</th><th>login</th></tr>";


	while($row= mysql_fetch_array($res))
	{
		//	$res2=mysql_query("select * from tb_ relations where fk_u_id=".$row['u_id']." and fk_g_id=".$id." ") or die(mysql_error());
		if(isset($ch[$row['u_id']]) and $ch[$row['u_id']] == '1')
		{
			$checked=' checked';
		}
		else
		{
			$checked='';
		}

		$result.= "\t<tr>
<td> <input name=\"ch[]\" type=\"checkbox\" value='".$row['u_id']."'$checked onChange=\"change_user_group(this,".$id.")\"></td>
<td>".$row['surname']." (#".$row['u_id'].")</td>
<td>".$row['firstname']."</td>
<td>".$row['fathername']."</td>
<td>".$row['login']."</td>
</tr>\n";

	}
	$result .="</table>";
	return '<Br>'.$result;
}



function update_user_group()
{
	if(!isset($_GET['g_id'])) return 'Не указана группа';
	$g_id=mysql_escape_string($_GET['g_id']);
	if(!isset($_GET['u_id'])) return 'Не указан пользователь';
	$u_id=mysql_escape_string($_GET['u_id']);
	if(!isset($_GET['do'])) return 'Не указано действие';
	$do=mysql_escape_string($_GET['do']);
	switch ($do)
	{
		case 'add':
			mysql_query("INSERT INTO tb_relations (fk_g_id,fk_u_id) VALUES (".$g_id.",".$u_id.")") or die(mysql_error());
			if(mysql_affected_rows()!=1)
			return false;
			break;
		case 'del':
			mysql_query("DELETE FROM tb_relations WHERE fk_g_id=".$g_id." AND fk_u_id=".$u_id."") or die(mysql_error());
			if(mysql_affected_rows()!=1)
			return false;
			break;
		default: return false;
		break;
	}
	return true;
}
function sort_fio($id,$a=''){
	$l=array(0=>'_','A-Z','А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ч','Ц','Ш','Щ','Ь','Ы','Ъ','Э','Ю','Я');
	$res=mysql_query("SELECT upper(substring( `surname` , 1, 1 )) AS prep2 FROM `tb_users` where (univer_id is null or univer_id= 0) GROUP BY prep2 ORDER BY `prep2` ASC") or die(mysql_error());
	$out='<ul id="topmenu">';
	//arra s bykvami kotorie est!

	for($i=0;$i<count($l);$i++){$est[$l[$i]]='0';}

	while($row=mysql_fetch_array($res)){
		if(is_numeric($row['prep2']))$est['_']='1'; else
		if($row['prep2'] == '')$est['_']='1';
		if(ereg ("([A-Z]{1})", $row['prep2'], $rr))$est['A-Z']='1';
		else
		$est[$row['prep2']]='1';
	}

	$est[$a]='2';

	//array alfavit

	for($i=0;$i<count($l);$i++){

		$out.='<li id=\''.$l[$i].'\'';
		if($est[$l[$i]]=='1'){
			$out.='><a class="tab" href="#" onClick="Update_table(\''.$l[$i].'\',\''.$id.'\')">'.$l[$i].'</a></li>';
		}		else
		{
			if($est[$l[$i]] == 2)
			$out.=' class="active"><span class="tab"><b>'.$l[$i]."</b></span></li>\n";

			else
			{

				//to4no net v spiske
				$out.='><span class="tab">'.$l[$i]."</span></li>\n";
			}
		}
		//link tru fels


	}//end for

	return $out.'</ul>';
}
function sort_fio2($a=''){
	$l=array(0=>'_','A-Z','А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ч','Ц','Ш','Щ','Ь','Ы','Ъ','Э','Ю','Я');
	$res=mysql_query("SELECT upper(substring( `surname` , 1, 1 )) AS prep2 FROM `tb_users` where (univer_id is null or univer_id= 0) GROUP BY prep2 ORDER BY `prep2` ASC") or die(mysql_error());
	$out='<ul id="topmenu">';

	//arra s bykvami kotorie est!
	for($i=0;$i<count($l);$i++){$est[$l[$i]]='0';}
	while($row=mysql_fetch_array($res)){

		if(is_numeric($row['prep2']))$est['_']='1'; else
		if($row['prep2'] == '')$est['_']='1';
		if(ereg ("([A-Z]{1})", $row['prep2'], $rr))$est['A-Z']='1';
		else
		$est[$row['prep2']]='1';
	}

	$est[$a]='2';

	//array alfavit

	for($i=0;$i<count($l);$i++){

		$out.='<li id=\''.$l[$i].'\'';

		if($est[$l[$i]]=='1'){
			$out.='><a class="tab" href="#" onClick="Update_table2(\''.$l[$i].'\')">'.$l[$i].'</a></li>';
		}
		else
		{
			if($est[$l[$i]] == 2)
			$out.=' class="active"><span class="tab"><b>'.$l[$i]."</b></span></li>\n";

			else
			{

				//to4no net v spiske
				$out.='><span class="tab">'.$l[$i]."</span></li>\n";
			}
		}
		//link tru fels


	}//end for

	return $out.'</ul><Br><BR><br>';
}

function show_change_pass_user_irud()
{
	if(!isset($_GET['u_id'])) return 'Не указан пользователь';
	$id=mysql_escape_string($_GET['u_id']);
	//$result=show_users();
	$result.=drop_teni_div(1);
	$result.= "<div id=\"apDiv1\">
	<input type=\"button\" name=\"close\" id=\"close\" value=\"Закрыть\" onClick=\"document.getElementById('apDiv1').style.display='none';document.getElementById('apDiv2').style.display='none';\" /><h3>Изменение пароля</h3>Ведите новый пароль<br>
	<input type=\"password\" name=\"password\" id=\"password\"/> &#8212; Пароль<br/>
	<input type=\"hidden\" name=\"u_id\" id=\"u_id\" value=\"$id\"/>
	<input type=\"button\" name=\"button\" id=\"button\" value=\"Изменить\" onClick=\"change_pass_user();\"/><br/>
	</div>
".drop_teni_div(2)."
<div id=\"apDiv2\"></div>\n";
	return $result;
}

function show_stat()
{
	$result="";
	$result.="1. <a href=# onclick='show_all_users()' >Список всех пользователей по алфавиту</a><br>
2. <a href=# onclick='show_kontr_otv_users()' >Список отв. исполнителей по алфавиту</a><br>
3. <a href=# onclick='show_kontr_sotr_users()' >Список отв. сотрудников Управления делами по алфавиту</a><br>
4. <a href=# onclick='show_kontr_ruk_users()' >Список всех руководителей по алфавиту</a><br>
5. <a href=# onclick='show_arm_users()' >Список только пользователей АРМ по алфавиту</a><br>
6. <a href=# onclick='show_rekt_read_users()' >Список только пользователей Ректората(на чтение) по алфавиту</a><br>
7. <a href=# onclick='show_count_docs_control()' >Общее количество документов в системе на данный момент (писать дату и время выборки)</a><br>
8. <a href=# onclick='show_count_arch_docs_control()' >Общее количество документов в архиве на данный момент (писать дату и время выборки)</a><br>
9. <a href=# onclick='show_count_vid_docs_control()' >Количество документов по видам на данный момент  (писать дату и время выборки)</a><br>
10. <a href=# onclick='show_count_calender_docs_control()' >количество документов введенных за устанавливаемый календариком период</a><br>
11. <a href=# onclick='show_arm_docs()' >АРМ Кол-во документов и объем файлов</a><br>
12. <a href=# onclick='show_docs_from_rukovod_calender()' >Количество документов Руководителей за период:</a><br>
13. <a href=# onclick='show_docs_from_rektorat_calender()' >Количество документов членов ректората за период:</a><br>
14. <a href=# onclick='show_docs_from_static_group_calender()' >Количество документов по группе Статистика за период:</a><br>
15. <a href=# onclick='count_docs_from_statist()' >Количество документов в контроле по датам</a><br>
16. <a href=# onclick='show_date_watch_users()'>Показать действия пользователей</a><br>";

	return $result;
}
function show_arm_docs(){
$result=date("r");
	$res_isp=mysql_query("SELECT count(*) as doc,sum(`f_size`) as size FROM `files`");
	$result.= "<h2>АРМ Кол-во документов и объем файлов</h2>";
	while($row=mysql_fetch_array($res_isp))
	{
	 $s2=$s=$row['size'];
	 $s1='байт.';
 if($s > 1024){$s2=number_format(round($s/1024,2), 2, ',', ' '); $s1='КБ.';}// kb;}
 if($s > 1024*1024){$s2=number_format(round($s/(1024*1024),2), 2, ',', ' '); $s1='МБ.';} // mb;

	 	$result.=  "Вдокументов в базе : ".$row['doc']." <br>	Объем :<b>$s2</b> $s1 <br><br>";
	}
	return $result;

}
function show_all_users()
{
	$result="";
	$res_isp=mysql_query("select * from tb_users  order by surname,firstname,fathername");
	$result.= "<h2>Все пользователи</h2>";
	$result.= '<h3>Количество: '.mysql_result(mysql_query('select count(*) from tb_users'),0,0).'</h3>';
	while($row=mysql_fetch_array($res_isp))
	{

		$result.=  "{$row['surname']} {$row['firstname']} {$row['fathername']} {$row['login']}<br>";
	}
	return $result;
}
function show_kontr_otv_users()
{
	$result="";
	$res_isp=mysql_query("select * from tb_users, tb_relations where u_id=fk_u_id and fk_g_id=4 order by surname,firstname,fathername");
	$result.= "<h2>Контроль_ответственные_исполнители</h2>";
	$result.= '<h3>Количество: '.mysql_result(mysql_query('select count(*) from tb_users, tb_relations where u_id=fk_u_id and fk_g_id=4'),0,0).'</h3>';

	while($row=mysql_fetch_array($res_isp))
	{

		$result.="{$row['surname']} {$row['firstname']} {$row['fathername']} {$row['login']}<br>";
	}
	return $result;
}
function show_kontr_sotr_users()
{
	$result="";
	$res_isp=mysql_query("select * from tb_users, tb_relations where u_id=fk_u_id and fk_g_id=6   order by surname,firstname,fathername");
	$result.= "<h2>Контроль_ответственные_сотрудники</h2>";
	$result.= '<h3>Количество: '.mysql_result(mysql_query('select count(*) from tb_users, tb_relations where u_id=fk_u_id and fk_g_id=6'),0,0).'</h3>';

	while($row=mysql_fetch_array($res_isp))
	{

		$result.=  "{$row['surname']} {$row['firstname']} {$row['fathername']} {$row['login']}<br>";
	}
	return $result;
}
function show_kontr_ruk_users()
{
	$result="";
	$res_isp=mysql_query("select * from tb_users, tb_relations where u_id=fk_u_id and fk_g_id=5   order by surname,firstname,fathername");
	$result.= "<h2>Контроль_руководители</h2>";
	$result.= '<h3>Количество: '.mysql_result(mysql_query('select count(*) from tb_users, tb_relations where u_id=fk_u_id and fk_g_id=5'),0,0).'</h3>';

	while($row=mysql_fetch_array($res_isp))
	{

		$result.=  "{$row['surname']} {$row['firstname']} {$row['fathername']} {$row['login']}<br>";
	}
	return $result;
}
function show_arm_users()
{
	$result="";
	$res_isp=mysql_query("SELECT * FROM `users` JOIN `tb_users` ON id = u_id ORDER BY  surname,firstname,fathername");
	$result.= "<h2>Пользователи АРМ</h2>";
$result.= '<h3>Количество: '.mysql_result(mysql_query('select count(*) FROM `users` JOIN `tb_users` ON id = u_id'),0,0).'</h3>';

	while($row=mysql_fetch_array($res_isp))
	{

		$result.=  "{$row['surname']} {$row['firstname']} {$row['fathername']} {$row['login']}<br>";
	}
	return $result;
}
function show_rekt_read_users()
{
	$result="";
	$res_isp=mysql_query("select * from tb_users, tb_relations where u_id=fk_u_id and fk_g_id=12   order by surname,firstname,fathername");
	$result.= "<h2>Ректорат на чтение</h2>";
$result.= '<h3>Количество: '.mysql_result(mysql_query('select count(*) from tb_users, tb_relations where u_id=fk_u_id and fk_g_id=12'),0,0).'</h3>';

	while($row=mysql_fetch_array($res_isp))
	{

		$result.=  "{$row['surname']} {$row['firstname']} {$row['fathername']} {$row['login']}<br>";
	}
	return $result;
}
function show_count_docs_control()
{
	$result="";

	$result.=date('r');
	$result.= "<h2>Кол-во документов в системе Контроль</h2>";
	$res_isp=mysql_query("select count(cf_id) as c1 from tb_control_files");
	while($row=mysql_fetch_array($res_isp))
	{

		$result.="<h3>Всего документов: {$row[0]}</h3>";
	}
	$result.="<h4>Из них:</h4>";
	$res_isp=mysql_query("select count(cf_id) as c1 from tb_control_files where (control=9 or control=5) and good=1");
	while($row=mysql_fetch_array($res_isp))
	{

		$result.="<h5>На контроле стоят или сняты: {$row[0]}</h5>";
	}
	$res_isp=mysql_query("select count(cf_id) as c1 from tb_control_files  where (control=6 or control=10) and good=1");
	while($row=mysql_fetch_array($res_isp))
	{

		$result.="<h5>Не были поставлены на контроль: {$row[0]}</h5>";

	}
	$res_isp=mysql_query("select count(cf_id) as c1 from tb_control_files  where good=0");
	while($row=mysql_fetch_array($res_isp))
	{
		$result.="<h5>Недействительны: {$row[0]}</h5>";
	}


	return $result;
}
function show_count_arch_docs_control()
{
	$result="";
	$result.=date('r');
	$result.= "<h2>Кол-во документов в архиве системы Контроль</h2>";

	$res_isp=mysql_query("select count(cf_id) as c1 from tb_control_archive");
	while($row=mysql_fetch_array($res_isp))
	{

		$result.="<h3>Всего документов: {$row[0]}</h3>";
	}
	$result.="<h4>Из них:</h4>";
	$res_isp=mysql_query("select count(cf_id) as c1 from tb_control_archive where (control=9 or control=5) and good=1");
	while($row=mysql_fetch_array($res_isp))
	{

		$result.="<h5>На контроле были поставлены или сняты: {$row[0]}</h5>";
	}
	$res_isp=mysql_query("select count(cf_id) as c1 from tb_control_archive  where (control=6 or control=10) and good=1");
	while($row=mysql_fetch_array($res_isp))
	{

		$result.="<h5>Не были поставлены на контроль: {$row[0]}</h5>";

	}
	$res_isp=mysql_query("select count(cf_id) as c1 from tb_control_archive  where good=0");
	while($row=mysql_fetch_array($res_isp))
	{
		$result.="<h5>Недействительны: {$row[0]}</h5>";
	}

	return $result;
}
function show_count_vid_docs_control()
{
	$result="";
	$result.=date('r');
	$result.= "<h2>Кол-во документов в системе Контроль по видам</h2>";
	$res_v=mysql_query("select vid_txt,vid_n,vid_id from tb_control_vids");
	while($r2=mysql_fetch_array($res_v))
	{
		$res_isp=mysql_query("select count(cf_id) as c1 from tb_control_files where vid_doc={$r2['vid_id']}");


		while($row=mysql_fetch_array($res_isp))
		{

			$result.=  "{$r2['vid_txt']}-{$r2['vid_n']}: {$row[0]}<br>";
		}
	}
	return $result;
}
function show_docs_by_date()
{
	$result="";
	$res_isp=mysql_query("select count(cf_id) as c1 from tb_control_files where reg_date>='".$_GET['first']."' and reg_date<='".$_GET['last']."'");
	//$result.="select count(cf_id) as c1 from tb_control_files where reg_date>='".$_GET['first']."' and reg_date<='".$_GET['last']."'";
	$result.=date('r');
	$result.= "<h2>Кол-во документов в системе Контроль</h2>";

	while($row=mysql_fetch_array($res_isp))
	{

		$result.=  "{$row[0]}<br>";
	}
	return $result;
}

function show_docs_from_rukovod()
{
	$group_id=5;
$result="";
$listRukovodSql="select u_id,
concat(substring(tb_users.firstname, 1, 1), '.', substring(tb_users.fathername, 1, 1), '. ', tb_users.surname) As FIO,
concat( tb_users.surname, ' ',tb_users.firstname , ' ',tb_users.fathername) As fullFIO
from tb_users
left join tb_relations on (tb_relations.fk_u_id = tb_users.u_id)
where tb_relations.fk_g_id = $group_id ORDER by surname";

if(!isset($_GET['first'])||!isset($_GET['last'])||!preg_match('/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/',$_GET['first'])
||!preg_match('/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/',$_GET['last']))
{
	die('Даты в неправильном формате');
}
$res=mysql_query($listRukovodSql) or die(mysql_error());
$listRukovods=array();
$fioList=array();


while($row=mysql_fetch_array($res))
{

	$listRukovods[$row['u_id']]=array('fio'=>$row['FIO'],
										'fullFio'=>$row['fullFIO'],
										'controlRuk'=>0,
										'archiveRuk'=>0,
										'controlIsp'=>0,
										'archiveIsp'=>0,
										'controlRukIsp'=>0,
										'archiveRukIsp'=>0,
	);
	$fioList[]=$row['FIO'];
}


$idCommaSeperate = implode(',',array_keys($listRukovods));

$searchDocsControlSql="select count(cf_id) as c,tb_control_files.fk_rukovodit
from tb_control_files
where tb_control_files.fk_rukovodit in (".$idCommaSeperate.")
and tb_control_files.reg_date>='".$_GET['first']."'
and tb_control_files.reg_date<='".$_GET['last']."'
and tb_control_files.fk_otv_user <> tb_control_files.fk_rukovodit
group by tb_control_files.fk_rukovodit";
//echo $searchDocsControlSql;
$res=mysql_query($searchDocsControlSql) or die(mysql_error());
while($row=mysql_fetch_array($res))
{
	$listRukovods[$row['fk_rukovodit']]['controlRuk']=$row['c'];
}

$searchDocsControlSql="select count(cf_id) as c,tb_control_files.fk_otv_user
from tb_control_files
where tb_control_files.fk_otv_user in (".$idCommaSeperate.")
and tb_control_files.reg_date>='".$_GET['first']."'
and tb_control_files.reg_date<='".$_GET['last']."'
and tb_control_files.fk_otv_user <> tb_control_files.fk_rukovodit
group by tb_control_files.fk_otv_user;";
$res=mysql_query($searchDocsControlSql) or die(mysql_error());
while($row=mysql_fetch_array($res))
{
	$listRukovods[$row['fk_otv_user']]['controlIsp']=$row['c'];
}

$searchDocsControlSql="select count(cf_id) as c,tb_control_files.fk_otv_user
from tb_control_files
where tb_control_files.fk_otv_user in (".$idCommaSeperate.")
and tb_control_files.reg_date>='".$_GET['first']."'
and tb_control_files.reg_date<='".$_GET['last']."'
and tb_control_files.fk_otv_user = tb_control_files.fk_rukovodit
group by tb_control_files.fk_otv_user;";
$res=mysql_query($searchDocsControlSql) or die(mysql_error());
while($row=mysql_fetch_array($res))
{
	$listRukovods[$row['fk_otv_user']]['controlRukIsp']=$row['c'];
}


$fioCommaSeperate = "'".implode("','",($fioList))."'";
$searchDocsControlSql="select count(cf_id) as c,tb_control_archive.rukovodit
from tb_control_archive
where tb_control_archive.rukovodit in (".$fioCommaSeperate.")
and tb_control_archive.reg_date>='".$_GET['first']."'
and tb_control_archive.reg_date<='".$_GET['last']."'
and tb_control_archive.otv_user<>tb_control_archive.rukovodit
group by tb_control_archive.rukovodit";
$res=mysql_query($searchDocsControlSql) or die(mysql_error());
while($row=mysql_fetch_array($res))
{
	foreach ($listRukovods as $u_id => $value)
	{
		if($value['fio']==$row['rukovodit'])
		{
			$listRukovods[$u_id]['archiveRuk']=$row['c'];
		}
	}
}

$searchDocsControlSql="select count(cf_id) as c,tb_control_archive.otv_user
from tb_control_archive
where tb_control_archive.otv_user in (".$fioCommaSeperate.")
and tb_control_archive.reg_date>='".$_GET['first']."'
and tb_control_archive.reg_date<='".$_GET['last']."'
and tb_control_archive.otv_user<>tb_control_archive.rukovodit
group by tb_control_archive.otv_user";
//die($searchDocsControlSql);
$res=mysql_query($searchDocsControlSql) or die(mysql_error());
while($row=mysql_fetch_array($res))
{
	foreach ($listRukovods as $u_id => $value)
	{
		if($value['fio']==$row['otv_user'])
		{
			$listRukovods[$u_id]['archiveIsp']=$row['c'];
		}
	}
}

$searchDocsControlSql="select count(cf_id) as c,tb_control_archive.otv_user
from tb_control_archive
where tb_control_archive.otv_user in (".$fioCommaSeperate.")
and tb_control_archive.reg_date>='".$_GET['first']."'
and tb_control_archive.reg_date<='".$_GET['last']."'
and tb_control_archive.otv_user=tb_control_archive.rukovodit
group by tb_control_archive.otv_user";
//echo $searchDocsControlSql;
//die($searchDocsControlSql);
$res=mysql_query($searchDocsControlSql) or die(mysql_error());
while($row=mysql_fetch_array($res))
{
	foreach ($listRukovods as $u_id => $value)
	{
		if($value['fio']==$row['otv_user'])
		{
			$listRukovods[$u_id]['archiveRukIsp']=$row['c'];
		}
	}
}

$result='<table width="80%" border="1">
  <tr><th>№№</th>
    <th>ФИО</th>
    <th>В работе</th>
    <th>Архив</th>
    <th>Сумма</th>
     <th>Тип</th>
      <th>Всего</th>
  </tr>



';
$string='
  <tr><td rowspan="3">%nomer%</td>
    <td rowspan="3">%FIO%</td>
    <td>%controlRuk%</td>
    <td>%archiveRuk%</td>
    <td>%rukSum%</td>
     <td>руководитель</td>
     <td rowspan="3">%sum%</td>
  </tr>
  <tr>
    <td>%controlIsp%</td>
    <td>%archiveIsp%</td>
    <td>%ispSum%</td>
    <td>исполнитель</td>
  </tr>
    <tr>
    <td>%controlRukIsp%</td>
    <td>%archiveRukIsp%</td>
    <td>%rukIspSum%</td>
    <td>рук + исп</td>
  </tr>
  ';
$i=0;
foreach ($listRukovods as $u_id => $value)
	{
		$i++;
		$result.=str_replace(array('%nomer%',
		'%FIO%',
										'%controlRuk%',
										'%archiveRuk%',
										'%controlIsp%',
										'%archiveIsp%',
										'%rukSum%',
										'%ispSum%',

										'%controlRukIsp%',
										'%archiveRukIsp%',
										'%rukIspSum%',
										'%sum%',

									),
									array($i,$value['fullFio'],
										$value['controlRuk'],
										$value['archiveRuk'],
										$value['controlIsp'],
										$value['archiveIsp'],
										$value['controlRuk']+$value['archiveRuk'],
										$value['controlIsp']+$value['archiveIsp'],
										$value['controlRukIsp'],
										$value['archiveRukIsp'],
										$value['controlRukIsp']+$value['archiveRukIsp'],
										$value['controlRukIsp']+$value['archiveRukIsp']+$value['controlRuk']+$value['archiveRuk']+$value['controlIsp']+$value['archiveIsp'],
									),


									$string
							);

	}
$result.='</table>';
	return $result;
//return '<pre>'.print_r($listRukovods,1);

}


function show_docs_from_rektorat()
{
	$group_id=28;
$result="";
$listRukovodSql="select u_id,
concat(substring(tb_users.firstname, 1, 1), '.', substring(tb_users.fathername, 1, 1), '. ', tb_users.surname) As FIO,
concat( tb_users.surname, ' ',tb_users.firstname , ' ',tb_users.fathername) As fullFIO
from tb_users
left join tb_relations on (tb_relations.fk_u_id = tb_users.u_id)
where tb_relations.fk_g_id = $group_id ORDER by surname";


if(!isset($_GET['first'])||!isset($_GET['last'])||!preg_match('/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/',$_GET['first'])
||!preg_match('/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/',$_GET['last']))
{
	die('Даты в неправильном формате');
}
$res=mysql_query($listRukovodSql) or die(mysql_error().__FILE__.__LINE__);
if(mysql_num_rows($res)==0) return 'Группа пустая';
$listRukovods=array();
$fioList=array();


while($row=mysql_fetch_array($res))
{

	$listRukovods[$row['u_id']]=array('fio'=>$row['FIO'],
										'fullFio'=>$row['fullFIO'],
										'controlRuk'=>0,
										'archiveRuk'=>0,
										'controlIsp'=>0,
										'archiveIsp'=>0);
	$fioList[]=$row['FIO'];
}


$idCommaSeperate = implode(',',array_keys($listRukovods));

$searchDocsControlSql="select count(cf_id) as c,tb_control_files.fk_otv_user
from tb_control_files
where tb_control_files.fk_otv_user in (".$idCommaSeperate.")
and tb_control_files.reg_date>='".$_GET['first']."'
and tb_control_files.reg_date<='".$_GET['last']."'
group by tb_control_files.fk_otv_user;";
$res=mysql_query($searchDocsControlSql) or die(mysql_error().__FILE__.__LINE__);


while($row=mysql_fetch_array($res))
{
	$listRukovods[$row['fk_otv_user']]['controlIsp']=$row['c'];
}

$fioCommaSeperate = "'".implode("','",($fioList))."'";

$searchDocsControlSql="select count(cf_id) as c,tb_control_archive.otv_user
from tb_control_archive
where tb_control_archive.otv_user in (".$fioCommaSeperate.")
and tb_control_archive.reg_date>='".$_GET['first']."'
and tb_control_archive.reg_date<='".$_GET['last']."'
group by tb_control_archive.otv_user";
//die($searchDocsControlSql);
$res=mysql_query($searchDocsControlSql) or die(mysql_error().__FILE__.__LINE__);
while($row=mysql_fetch_array($res))
{
	foreach ($listRukovods as $u_id => $value)
	{
		if($value['fio']==$row['otv_user'])
		{
			$listRukovods[$u_id]['archiveIsp']=$row['c'];
		}
	}
}
$result='<table width="80%" border="1">
  <tr><th>№№</th>
    <th>ФИО</th>
    <th>В работе</th>
    <th>Архив</th>
    <th>Сумма</th>

  </tr>



';
$string='
  <tr><td>%nomer%</td>
    <td>%FIO%</td>
      <td>%controlIsp%</td>
    <td>%archiveIsp%</td>
    <td>%ispSum%</td>

  </tr>';
$i=0;
foreach ($listRukovods as $u_id => $value)
	{
		$i++;
		$result.=str_replace(array('%nomer%',
		'%FIO%',
										'%controlIsp%',
										'%archiveIsp%',
										'%ispSum%'
									),
									array($i,$value['fullFio'],
										$value['controlIsp'],
										$value['archiveIsp'],
										$value['controlIsp']+$value['archiveIsp']
									),
									$string
							);

	}
$result.='</table>';
	return $result;
//return '<pre>'.print_r($listRukovods,1);

}
function show_docs_from_static_group()
{
	$group_id=27;
$result="";
$listRukovodSql="select u_id,
concat(substring(tb_users.firstname, 1, 1), '.', substring(tb_users.fathername, 1, 1), '. ', tb_users.surname) As FIO,
concat( tb_users.surname, ' ',tb_users.firstname , ' ',tb_users.fathername) As fullFIO
from tb_users
left join tb_relations on (tb_relations.fk_u_id = tb_users.u_id)
where tb_relations.fk_g_id = $group_id ORDER by surname";


if(!isset($_GET['first'])||!isset($_GET['last'])||!preg_match('/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/',$_GET['first'])
||!preg_match('/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/',$_GET['last']))
{
	die('Даты в неправильном формате');
}
$res=mysql_query($listRukovodSql) or die(mysql_error().__FILE__.__LINE__);
if(mysql_num_rows($res)==0) return 'Группа пустая';
$listRukovods=array();
$fioList=array();


while($row=mysql_fetch_array($res))
{

	$listRukovods[$row['u_id']]=array('fio'=>$row['FIO'],
										'fullFio'=>$row['fullFIO'],
										'controlRuk'=>0,
										'archiveRuk'=>0,
										'controlIsp'=>0,
										'archiveIsp'=>0);
	$fioList[]=$row['FIO'];
}


$idCommaSeperate = implode(',',array_keys($listRukovods));

$searchDocsControlSql="select count(cf_id) as c,tb_control_files.fk_otv_user
from tb_control_files
where tb_control_files.fk_otv_user in (".$idCommaSeperate.")
and tb_control_files.reg_date>='".$_GET['first']."'
and tb_control_files.reg_date<='".$_GET['last']."'
group by tb_control_files.fk_otv_user;";
$res=mysql_query($searchDocsControlSql) or die(mysql_error().__FILE__.__LINE__);


while($row=mysql_fetch_array($res))
{
	$listRukovods[$row['fk_otv_user']]['controlIsp']=$row['c'];
}

$fioCommaSeperate = "'".implode("','",($fioList))."'";

$searchDocsControlSql="select count(cf_id) as c,tb_control_archive.otv_user
from tb_control_archive
where tb_control_archive.otv_user in (".$fioCommaSeperate.")
and tb_control_archive.reg_date>='".$_GET['first']."'
and tb_control_archive.reg_date<='".$_GET['last']."'
group by tb_control_archive.otv_user";
//die($searchDocsControlSql);
$res=mysql_query($searchDocsControlSql) or die(mysql_error().__FILE__.__LINE__);
while($row=mysql_fetch_array($res))
{
	foreach ($listRukovods as $u_id => $value)
	{
		if($value['fio']==$row['otv_user'])
		{
			$listRukovods[$u_id]['archiveIsp']=$row['c'];
		}
	}
}
$result='<table width="80%" border="1">
  <tr><th>№№</th>
    <th>ФИО</th>
    <th>В работе</th>
    <th>Архив</th>
    <th>Сумма</th>

  </tr>



';
$string='
  <tr><td>%nomer%</td>
    <td>%FIO%</td>
      <td>%controlIsp%</td>
    <td>%archiveIsp%</td>
    <td>%ispSum%</td>

  </tr>';
$i=0;
foreach ($listRukovods as $u_id => $value)
	{
		$i++;
		$result.=str_replace(array('%nomer%',
		'%FIO%',
										'%controlIsp%',
										'%archiveIsp%',
										'%ispSum%'
									),
									array($i,$value['fullFio'],
										$value['controlIsp'],
										$value['archiveIsp'],
										$value['controlIsp']+$value['archiveIsp']
									),
									$string
							);

	}
$result.='</table>';
	return $result;
//return '<pre>'.print_r($listRukovods,1);

}
function count_docs_from_statist()
{
	//НЕзабыть файлик /include/script.js
	$sql='SELECT  `count_control`, `count_archive`, date_format(`date`,\'%d.%m.%Y\') as date FROM `tb_control_statist` order by `tb_control_statist`.`date`';
	$res=mysql_query($sql);
	$result='<table border=1>
	<tr>
		<th>Дата</th>
		<th>В работе</th>
		<th>Архив</th>
		</tr>';
	while($row=mysql_fetch_array($res))
	{
		$result.='<tr>
		<td>'.$row['date'].'</td>
		<td>'.$row['count_control'].'</td>
		<td>'.$row['count_archive'].'</td>
		</tr>';
	}
$result.='</table>';
	return $result;
}

function show_watch_users() {
    if (
            !isset($_GET['first']) ||
            !isset($_GET['last']) ||
            !preg_match('/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/', $_GET['first']) ||
            !preg_match('/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/', $_GET['last'])
    ) {
        die('Даты в неправильном формате');
    }
    $first = $_GET['first'] . ' 00:00:00';
    $last = $_GET['last'] . ' 23:59:59';
    $sql = "SELECT `watch_users`.`id`, `watch_users`.`who`,
     concat( tb_users.surname, ' ',tb_users.firstname , ' ',tb_users.fathername) As fullFIO,
      `watch_users`.`what`, `watch_users`.`element`, `watch_users`.`comment`,
       `watch_users`.`date`
       FROM `watch_users`
       join `tb_users` on `u_id`=`who` where `date`>='$first' and `date`<='$last'  ORDER BY `date` ASC";
    // echo $sql;
    $res = mysql_query($sql);
    $result = '<table border=1>
	<tr>
		<th>Пользователь</th>
		<th>Действие</th>
		<th>Объект</th>
		<th>Комментарий</th>
		<th>Дата</th>
		</tr>';
    while ($row = mysql_fetch_array($res))
    {
        $what = '';
        switch ($row['what'])
        {
            case 'arm_delete_doc':
                $what = 'Удаление файла из АРМ';
                break;
                        case 'rectorat_create_zas':
                $what = 'Создание заседания в Ректората';
                break;
            case 'rectorat_create_text':
                $what = 'Создание документа в Ректората';
                break;
            case 'sovet_create_zas':
                $what = 'Создание заседания в Совета';
                break;
            case 'sovet_create_text':
                $what = 'Создание документа в Совета';
                break;

                        case 'rectorat_edit_zas':
                $what = 'Редактирование заседания в Ректората';
                break;
            case 'rectorat_edit_text':
                $what = 'Редактирование документа в Ректората';
                break;
            case 'sovet_edit_zas':
                $what = 'Редактирование заседания в Совета';
                break;
            case 'sovet_edit_text':
                $what = 'Редактирование документа в Совета';
                break;

            case 'rectorat_delete_zas':
                $what = 'Удаление заседания из Ректората';
                break;
            case 'rectorat_delete_text':
                $what = 'Удаление документа из Ректората';
                break;
            case 'sovet_delete_zas':
                $what = 'Удаление заседания из Совета';
                break;
            case 'sovet_delete_text':
                $what = 'Удаление документа из Совета';
                break;
            default:
                $what = $row['what'];
                break;
        }
        $result .= '<tr>
		<td>' . $row['fullFIO'] . '</td>
		<td>' . $what . '</td>
		<td>' . $row['element'] . '</td>
		<td>' . $row['comment'] . '</td>
		<td>' . $row['date'] . '</td>
		</tr>';
    }
    $result .= '</table>';
    return $result;
}



?>
