<?
 include('include/_func.php');
 
# показать изменения от поледнего захода 
# типа идея такая записываем куки последнию активность 
# потом выводи возле даты пометку что это имнения с поледнего посещения
# как сделать, записываем два даты последний сеан если пусто
# то он новенький и дату когда он был тут в последнй раз
#hits_site("Попытка зайти admin.php >>");

auth('./');

if(!mysql_query ("SET CHARACTER SET utf8"))echo mysql_error();
if(!mysql_query ("SET character_set_connection = utf8"))echo mysql_error();
if(!mysql_query ("set character_set_results =  utf8 "))echo mysql_error();
if(!mysql_query ("set character_set_server = utf8"))echo mysql_error();

if(!empty($_GET['ch'])){
$ch1='';
for($i=0;$i<count($_GET['ch']);$i++)
$ch1.=$_GET['ch'][$i].':';
$_COOKIE['ch']=$ch1;
setcookie("ch",$ch1);
}

#узнаем когда был в последний раз и записываем дату в куки
if(empty($_COOKIE['last_time'])){
$res = mysql_query("SELECT UNIX_TIMESTAMP(date) as date FROM `tb_admin_hits` where u_id=".$_SESSION['u_id']." ORDER BY `tb_admin_hits`.`id` DESC limit 0, 5") or die(mysql_error());

			if (mysql_num_rows($res) > 0) {
			$last_time=mysql_result($res,0,0);
			setcookie("last_time",$last_time);		
			}
			
}
if(!empty($_COOKIE['last_time'])){
$last_time=$_COOKIE['last_time'];

$res = mysql_query("SELECT id FROM `tb_admin_hits` where date > '".date("Y-m-d H:i:s",$last_time)."' and u_id<>".$_SESSION['u_id']." ORDER BY `tb_admin_hits`.`id` DESC") or die(mysql_error());


			if (!$num1=mysql_num_rows($res)) {
			$num1='нету';}
			
}
if(empty($_COOKIE['last_time'])){hits_site('Успешно зашёл в admin.php',0);}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
<meta name="Description" content="">
<meta name="Keywords" content="">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Последний раз тут были в <?=@date("d-m-Y H:i",$last_time) ?>, новые записи в журнале <?=@$num1; ?></title>
      <link rel="stylesheet" href="css/theme.css" type="text/css" />
	  <link rel="stylesheet" href="css/template_css.css" type="text/css" />
	  <link rel="stylesheet" type="text/css" href="css/menu_alfavit.css">
	  <link rel="stylesheet" type="text/css" href="css/winds_admin.css">
<script  type="text/javascript"  language="JavaScript"  src="./include/script.js"></script>
<script type="text/javascript" src="include/highslide/highslide-with-html.js"></script>
<script type="text/javascript">    
    hs.graphicsDir = 'include/highslide/graphics/';
    hs.outlineType = 'rounded-white';
    hs.outlineWhileAnimating = true;
</script>

<script language="javascript">
document.onkeydown = NavigateThrough;
</script>
<style type="text/css">
table.feedMenu tr td{
	border:0px;
	padding: 3px 0px 0px 3px;
	vertical-align:bottom;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
}

.feedFallBackWrap {
  padding: 10px 180px 10px 10px; 
}

.feedFallBackWrapGroups {
  padding: 10px 10px 10px 10px; 
}

.feedFallBack {
  background: #f7f7f7;
  border: 1px solid #ccc;
  color: #777;
  margin:0px;
  font-size: 13px;
  line-height: 16px;
  padding: 50px 20px;
  text-align: center; 
}

table.feedMenu tr td img{
 vertical-align:bottom;
}

#checkboxFeed {
float:right; width:210px; margin-left: 15px; padding:20px 10px 20px 20px; background: #f7f7f7; border-bottom: 1px solid #ccc;
}

#checkboxFeed p {
	margin: -14px 0px 5px 0px;
	padding-bottom: 2px;
	color: #888;
	font-weight: bold;
	font-size: 11px;
	border-bottom: 1px solid #ccc;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}

#fcbox {
 margin-top:2px;
 vertical-align: bottom;
}

#mainFeed {
	background: #fff;
	border: 1px solid #CCCCCC;
}

#wrapFeed {
  position:relative;
  background:#f7f7f7;
  background: transparent url(ico/feedback.gif) repeat-y top left;
}

#leftFeed {
  float: left;
  width: 445px;
  overflow: hidden;
  padding: 0px;
  margin: 0px 0px 0px 10px; 
  background: transparent;
}

#rightFeed {
  float: right; 
  overflow: hidden;
  width: 166px;
  padding: 0px;
  margin: 0px;
  background: transparent;
}

#rightFeed div {
 padding: 0px 2px 2px 2px;
 vertical-align: abstop;
}

#rightFeed div img {
 padding:2px 0px 0px 0px;
 vertical-align: absbottom;
}

.feedDayWrap {
 margin:0px; border:0px; padding:0px;
}

.feedDay {
	text-align:left;
	color:#777;
	font-weight:bold;
	margin: 0px 0px 6px 0px;
	padding:4px 4px 4px 8px;
	background: #F7F7F7;
	border-bottom: 1px solid #ccc;
	font-size: 11px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}

.feedTable {
 padding:0px; 
 margin:0px;
 border: 0px;
}

.feedTable table {
 padding:0px; 
 margin:0px;
 border: 0px;
}

table.feedTable {
	padding:0px;
	margin:0px;
	background: #F5F3DC;
	border-bottom: 1px solid #ccc;
	border-top: 1px solid #fff;
}

.feedTable tr {
 padding:0px; 
 margin:0px;
 border: 0px;
}
.feedTable:hover{
 margin-bottom:0px; 
padding-bottom:0px; 
border-bottom: 1px solid #000;
border-top: 1px solid #000;
background: #ccf;
}

.feedIcon {
 margin-right: 5px;
 border:0px;
}

.feedPhotos {
 vertical-align:middle;
}

.feedPhotos a img {
 vertical-align:middle;
 padding: 3px;
 border: 1px solid #aaa;
 background-color: #fff;
 margin:3px 6px 3px 0px;
}

.feedPhotos a:hover img{
 border: 1px solid #45688E;
}

.feedVideos {
 vertical-align:top;
}

.feedVideos a img {
 vertical-align:top;
 padding: 1px;
 border: 1px solid #aaa;
 background-color: #fff;
 margin:3px 6px 3px 0px;
}

.feedVideos a:hover img{
 border: 1px solid #45688E;
}

.feedStory {
	vertical-align:middle;
	width: 680px;
	line-height:130%;
	border:0px;
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 10pt;
	font-weight: bold;
}

.feedTime {
	width:60px;
	vertical-align: top;
	text-align: right;
	padding: 2px 25px 0px 10px;
	font-size:12px;
	color: #777;
	border:0px;
}

.feedIconWrap {
 vertical-align: top; width:43px;
 text-align: center; padding-top:2px;
}

.wrapIcon {
 width:30px; float: left; height:20px;
}

.wrapStory {
 width:560px;
  float:right;
   height: 20px;
}


</style>
</head>
<body bgcolor="#ffffff">

<? if(empty($_GET['h'])){ ?>
<table border=0 cellpadding=0 cellspacing=0 width=100%>
<tr>
<td  bgcolor="#175E6C"><img src="images/0.gif" width="1" height="1" alt=""></td>
<td width="100%"><img src="images/0.gif" width="770" height="1" alt=""><br>


<!-- header [ -->
<table border=0 cellpadding=0 cellspacing=0 width=100%>
<tr>
	<td rowspan=3>
	<img src="images/lg_01.gif" alt="">
	</td>
	<td rowspan=3>
	<img src="images/m_01.gif" alt="">
	</td>
	<td width=100% valign=bottom>

			<table border="0" cellpadding="0" cellspacing="0" width="100%" >
			<tr valign="middle"><td height="105" width=100% style="background-position : right; background-repeat : no-repeat;"  ><img src="images/hd_01.gif"  alt="Российский Университет Дружбы Народов" /><br>

			</td></tr></table>



		<img src="images/0.gif" width="1" height="25"  alt="">

	</td>
</tr>
</table>
<!-- header ] -->




<!-- menu_top [ -->
<table border=0 cellpadding=0 cellspacing=0 width=100%>

<tr>
        <td  bgcolor="#175E6C"><img src="images/0.gif" align="absmiddle" height="4" alt=""></td>
</tr>
<tr>
        <td><img src="images/0.gif" align="absmiddle" height="4" alt=""></td>
</tr>
<tr>
        <td  bgcolor="#017DA7"><img src="images/0.gif" align="absmiddle" height="1" alt=""></td>
</tr>
<tr>
        <td><img src="images/0.gif" align="absmiddle" height="13" alt=""></td>
</tr>
</table>
<!-- menu_top ] -->

<!-- content [ -->
<table border=0 cellpadding=0 cellspacing=0 width=100%>
<tr>
        <td >
    <table border=0 cellpadding=0 cellspacing=0 width=100%>
        <tr valign="top">
        <td align=center width="150" style="background-image : url('images/bg_01.gif');background-repeat : repeat-y;background-position : right;background-color : #EAEBCF;">
       <table border=0 cellpadding=0 cellspacing=0 width=100%>
        <tr valign="top">
                <td width="100%" colspan=2>
</td>
<td width="5"></td>
<td align="top"><img src="images/bg_01.gif" height="10" width="5" alt=""></td>
</tr></table>
 <br>
	  <div id="cpanel">
		<div style="float:left;">
			<div class="icon">
				<a href="#" onClick="show_group();">
					<img src="images/folder.gif" alt="Список групп" align="middle"  border="0" /><span>Список групп</span></a>
			</div>
		</div>
        		<div style="float:left;">
			<div class="icon">
				<a href="#" onClick="show_users();">
					<img src="images/folder.gif" alt="Список пользователей" align="middle"  border="0" /><span>Список пользователей</span></a>
			</div>
		</div>
		<div style="float:left;">
			<div class="icon">
				<a href="admin.php?h=1" onclick="return hs.htmlExpand(this, { contentId: 'highslide-html-8', objectType: 'iframe',	objectWidth:  627, objectHeight:400, objectLoadTime: 'after', allowWidthReduction: 1} )">
					<img src="images/folder.gif" alt="История" align="middle"  border="0" /><span>История</span></a>
			</div>
		</div>
		<!--
          <div style="float:left;">
			<div class="icon">
				<a href="#" onClick="show_stat();">
					<img src="images/folder.gif" alt="Статистика" align="middle"  border="0" /><span>Статистика</span></a>
			</div>
		</div>
		-->
          <div style="float:left;">
              <div class="icon">
                  <a href="/stat/stat.php" >
                      <img src="images/folder.gif" alt="Статистика работы" align="middle"  border="0" /><span>Статистика</span></a>
              </div>
          </div>

		<div style="float:left;">
			<div class="icon">
				<a href="index.php"><img src="images/door.gif" alt="Выход" align="middle"  border="0" />
				<span>Выход</span></a>
			</div>
		</div>

    </div>


                </td>
<td> </td>
                <td>

        <table border=0 cellpadding=0 cellspacing=0 width=100%>
        <tr valign="top">
                <td width="10">&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td width="100%" style="padding-right: 10px">
                 <?php /*print $content;*/ ?>
                 <div id="menu"></div>
                 <div id="content"></div>

<? }
if(!empty($_GET['h']) and $_GET['h'] == '1'){
$text=@mysql_escape_string($_GET['q']);


	?>
	 <div id="mainFeed">
     <a href="admin.php?h=1&rand=<?=time();?>"> Обновить Журнал</a>, дата:<?=date("d-m H:i:s");?><br>
<div id="checkboxFeed">
  <p>Фильтр журнала</p>
  <form action="admin.php?h=1&" method="GET" name="fMenu" id="fMenu">
  <input name="h" value="1" type="hidden">
    <!-- <input name="act" value="friends" type="hidden">
    <input name="subm" value="1" type="hidden"> -->
    <table class="feedMenu" border="0" cellpadding="0" cellspacing="0">
      <tbody>
<?
/*
add_photo
add_item
post
plus
person
group
event
record
*/
$d=array(0=>'Сегодня','Вчера');

$im[0]='event_icon';$im1[0]='Заходы на главную';
$im[1]='group_icon.gif" alt="" class="feedIcon"><img src="ico/default';$im1[1]='Просмотр группы';
$im[2]='group_icon.gif" alt="" class="feedIcon"><img src="ico/plus_icon';$im1[2]='Добавление группы';
$im[3]='group_icon.gif" alt="" class="feedIcon"><img src="ico/record_icon'; $im1[3]='Изменение группы';
$im[4]='group_icon.gif" alt="" class="feedIcon"><img src="ico/cross'; $im1[4]='Удаление группы';

$im[5]='person_icon.gif" alt="" class="feedIcon"><img src="ico/default';$im1[5]='Просмотр пользов.';
$im[6]='person_icon.gif" alt="" class="feedIcon"><img src="ico/plus_icon';$im1[6]='Добавление полз.';
$im[7]='person_icon.gif" alt="" class="feedIcon"><img src="ico/record_icon';$im1[7]='Изменение пользов.';
$im[8]='person_icon.gif" alt="" class="feedIcon"><img src="ico/cross';$im1[8]='Удаление пользов.';

$im[9]='person_icon.gif" alt="" class="feedIcon"><img src="ico/plus_icon.gif" alt="" class="feedIcon"><img src="ico/group_icon';$im1[9]='Добавление пользователя в группу';
$im[10]='movie_icon';$im1[10]='АРМ изменение прав';
//$im[1]='plus_icon';$im1[]='Изменения';
//$im[2]='person_icon';$im1[]='Доб. польз';

//$im[4]='group_icon';$im1[]='Группы';
//$im[5]='record_icon';$im1[]='Ред. Личных данных';
/*
$ch['10']='1';
$ch['9']='1';
$ch['8']='1';
$ch['7']='1';
$ch['6']='1';
$ch['5']='1';
$ch['4']='1';
$ch['3']='1';
$ch['2']='1';
$ch['1']='1';
$ch['0']='1';
*/
if(!empty($_COOKIE['ch'])){
$t=split(":",$_COOKIE['ch']); 
for($i=0;$i<count($t);$i++)$ch2["$t[$i]"]='1';
	 }

for($i=0;$i<count($im);$i++){ ?>
        <tr>
          <td><img src="ico/<?=$im[$i];?>.gif" alt="<?=$im1[$i];?>" class="feedIcon"><?=$im1[$i];?></td>
          <td id="fcbox"><input name="ch[]" value="<?=$i ?>"<?
if(!empty($ch2[$i]))echo ' checked="checked" ';
          ?>  type="checkbox"></td>
        </tr>
        <? } ?>
        <tr><td><br>
        <input value="<?=empty($text)?'%':$text; ?>" id="button" type=text name=q> </td></tr>
      </tbody>
    </table>
    <div align="center">
      <input type="submit" value="Сохранить">
      </div>
  </form>
</div>

	
	<?


function print_page_2($a,$b=50){
global $PHP_SELF,$text;

$start=0;

$c=($a/$b)+0.4999;
$c=round($c,0);


if($c == '1')return;
$t = 'Найдено записей &#8212; '.$a.' Страниц &#8212; '.$c.'<br> Страница: ';

if(empty($_GET["p"])){

		if($c > 10)	$c=10;
		$p=1;

		}else {
		$p=$_GET["p"];// Нужна проверка на ввод
		//$c=40 $p = 10;

		if ($p < 10)$start=$p-6; else $start=$p-6;

if(($c - $p) < 5) $start=$c-10;
		if($start >= $c)$start=$c-5;
		if($start <= 0){$start=0;  if($p <= 0 ) $p=1;}
//if($start + 10 >= $c)


		$end=$start+10;
		if ($p > $c)$p=$c;
		if(($end - $p) == 4)$end = $p+5;
		if ($end < $c)$c=$end;

		}
for($i=$start;$i<$c;$i++)
{
if(($i+1)==$p)$t.='<b>'.($i+1).'</b> ';else $t.='<a href="'.$PHP_SELF.'?h=1&p='.($i+1).'&q='.rawurlencode($text).'">'.($i+1).'</a> ';}

return $t;
}

	hits_out();
	
}

if(empty($_GET['h'])){ 
?>
 </td>
</tr>
 </table></td>
</tr>
</table>
<!-- content ] -->
<!-- footer [ -->
<table border=0 cellpadding=0 cellspacing=0 width=100%>
<tr>
        <td  bgcolor="#017DA7">

                <table border=0 cellpadding=0 cellspacing=0>
                <tr valign="middle">
                <td><img src="images/0.gif" width="22" height="20" alt=""></td>
                <td><a href="http://old.rudn.ru/?pagec=2719" class="menutop">Разработка и поддержка ОВИТ УИТО РУДН 2007</a></td>
                </tr></table>

        </td>
</tr>
<tr>
        <td   bgcolor="#175E6C"><img src="images/0.gif" align="absmiddle" height="3" alt=""></td>
</tr>
</table>
<!-- footer ] -->


</td>
</tr></table>
<td   bgcolor="#175E6C"><img src="images/0.gif" width="1" height="1" alt=""></td>


</tr></table>


</div>
<div id="highslide-html-9" style="width:300px; padding:0;">
	    <div class="highslide-move"> <a href="#" onclick="return hs.close(this)" class="control">Закрыть</a> </div>
	    <div class="highslide-body"></div>
	    	<div class="highslide-footer">
				<div>
					<span class="highslide-resize" title="Растянуть">      	
					<span></span>
					</span>
				</div>
			</div>
</div>
<div id="highslide-html-8" style="width:600px; padding:0;">
	    <div class="highslide-move"> <a href="#" onclick="return hs.close(this)" class="control">Закрыть</a> </div>
	    <div class="highslide-body"></div>
	    	<div class="highslide-footer">
				<div>
					<span class="highslide-resize" title="Растянуть">      	
					<span></span>
					</span>
				</div>
			</div>
</div>
<? } ?>
</body>
</html>