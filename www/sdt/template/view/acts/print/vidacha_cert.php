<?php
header ("Content-Type: text/html; charset=windows-1251");



$old_view=0;   //в каком виде печатаются строчки подписей



$type = 1;
if (!empty($_GET['type']) &&is_numeric($_GET['type'])

) {
    $type = $_GET['type'];
}
$sign=ActSigning::getByID($type);
if(!$sign) $sign=ActSigning::getByID(1);

$rukovod_dolzhn_1 =  $sign->position;
$rukovod_fio = $sign->caption;


//ВУЗ
//$vuz_name=TEXT_HEADCENTER_MIDDLE_IP;
$vuz_name=TEXT_HEADCENTER_SHORT_IP;
//Вывод текущей даты :)


$print_date = $Act->getPrintDateAfterCheckDate();
//$date_day=date('d');
$date_day=date('d',strtotime($print_date));
//$date_month=date('m');
$date_month=date('m',strtotime($print_date));
$date_month=str_replace('01', 'Января',$date_month);
$date_month=str_replace('02', 'Февраля',$date_month);
$date_month=str_replace('03', 'Марта',$date_month);
$date_month=str_replace('04', 'Апреля',$date_month);
$date_month=str_replace('05', 'Мая',$date_month);
$date_month=str_replace('06', 'Июня',$date_month);
$date_month=str_replace('07', 'Июля',$date_month);
$date_month=str_replace('08', 'Августа',$date_month);
$date_month=str_replace('09', 'Сентября',$date_month);
$date_month=str_replace('10', 'Октября',$date_month);
$date_month=str_replace('11', 'Ноября',$date_month);
$date_month=str_replace('12', 'Декабря',$date_month);
//$date='&laquo;'.$date_day.'&raquo; &nbsp;'.strtolower($date_month).date(" Y");
$date='&laquo;'.$date_day.'&raquo; &nbsp;'.strtolower($date_month).date(" Y",strtotime($print_date));

$sign=ActSignings::get4VidachaCertFirst();


$date_check_2 = date('d.m.Y', strtotime($Act->testing_date));
//$date_check_1='&laquo;__<span class="GramE">_&raquo;_</span>______________201_';
//$date_check_2='';

$test_session_id = $Act->id;

function num2str($num) {
	$nul='ноль';
	$ten=array(
			array('','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'),
			array('','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять'),
	);
	$a20=array('десять','одиннадцать','двенадцать','тринадцать','четырнадцать' ,'пятнадцать','шестнадцать','семнадцать','восемнадцать','девятнадцать');
	$tens=array(2=>'двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят' ,'восемьдесят','девяносто');
	$hundred=array('','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот','восемьсот','девятьсот');
	$unit=array( // Units
			array('' ,'' ,'',	 1),
			array(''   ,''   ,''    ,0),
			array('тысяча'  ,'тысячи'  ,'тысяч'     ,1),
			array('миллион' ,'миллиона','миллионов' ,0),
			array('миллиард','милиарда','миллиардов',0),
	);
	//
	list($rub,$kop) = explode('.',sprintf("%015.2f", floatval($num)));
	$out = array();
	if (intval($rub)>0) {
		foreach(str_split($rub,3) as $uk=>$v) { // by 3 symbols
			if (!intval($v)) continue;
			$uk = sizeof($unit)-$uk-1; // unit key
			$gender = $unit[$uk][3];
			list($i1,$i2,$i3) = array_map('intval',str_split($v,1));
			// mega-logic
			$out[] = $hundred[$i1]; # 1xx-9xx
			if ($i2>1) $out[]= $tens[$i2].' '.$ten[$gender][$i3]; # 20-99
			else $out[]= $i2>0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9
			// units without rub & kop
			if ($uk>1) $out[]= morph($v,$unit[$uk][0],$unit[$uk][1],$unit[$uk][2]);
		} //foreach
	}
	else $out[] = $nul;
	//$out[] = morph(intval($rub), $unit[1][0],$unit[1][1],$unit[1][2]); // rub
	//$out[] = $kop.' '.morph($kop,$unit[0][0],$unit[0][1],$unit[0][2]); // kop
//var_dump($out);
	return join(' ',$out);// trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));
	
}

/**
 * Склоняем словоформу
 * @ author runcore
 */
function morph($n, $f1, $f2, $f5) {
	$n = abs(intval($n)) % 100;
	if ($n>10 && $n<20) return $f5;
	$n = $n % 10;
	if ($n>1 && $n<5) return $f2;
	if ($n==1) return $f1;
	return $f5;
}
?>
<html>
<head>
<title><?php echo $Act->testing_date;?> <?php echo $Act->getUniversity()->getLegalInfo()['name'];?>  <?php echo $Act->getUniversityDogovor();?></title>
<script>window.print() ;</script>
<style>
#main_table td
{
padding: 0px 5.4pt !important;
height: 0px !important;
}
#main_table tr
{
height: 0px !important;
}/*если нужно убрать пробелы по высоте в ячейках таблиц*/

    .font1
    {
        font-size: 10pt;
    }
    .font2
    {
        font-size: 10pt;
    }

</style>
</head>
<body>



<div style="wi dth:180mm;">
<p>







	<? if ($old_view==0) :?>
<div align="right" style="text-align:right"><span class="font2" style="color:#161616;letter-spacing:0pt;padding-right: 60px;"><b>УТВЕРЖДАЮ</b></span></div>
<div align="right" style="text-align:right">&nbsp;</div>

<div align="right" style="text-align:right"><span class="font2" style="color:#161616;letter-spacing:0pt;font-weight:normal;padding-right: 60px;">______________________________________</span></div>
<div align="center" style="text-align:center">&nbsp;</div>
<div align="right" style="text-align:right"><span class="font2" style="color:#161616;letter-spacing:0pt;font-weight:normal;padding-right: 60px;">&laquo;___&raquo; ________________20__ г.</span></div>

<div align="center" style="text-align:center">&nbsp;</div>
<div align="right" style="text-align:right"><span class="font2" style="color:#161616;letter-spacing:0pt;font-weight:normal;padding-right: 60px;"><?=$sign->position?> <?=TEXT_HEADCENTER_SHORT_IP?></span></div>
<div align="right" style="text-align:right"><span class="font2" style="color:#161616;letter-spacing:0pt;font-weight:normal;padding-right: 60px;"><?=$sign->caption?></span></div>
<div align="center" style="text-align:center">&nbsp;</div>
<div align="right" style="text-align:right"><span class="font2" style="color:#161616;letter-spacing:0pt;font-weight:normal;padding-right: 60px;">М.П.</span></div>
<div align="center" style="text-align:center">&nbsp;</div>
	<? endif; 
	
	
	
	
	
	
	
	
	
	if ($old_view==1) :?>
	<div align="right" style="text-align:right;padding-right: 170px;"><span class="font2" style="color:#161616;letter-spacing:0pt;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span><span class="font2" style="color:#161616;letter-spacing:0pt"><b>УТВЕРЖДАЮ</b></span></div>
<div align="right" style="text-align:right">&nbsp;</div>
<div align="right" style="text-align:right"><span class="font2" style="color:#161616;letter-spacing:0pt;font-weight:normal;padding-right: 60px;"><?=$sign->position?> <?=TEXT_HEADCENTER_SHORT_IP?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?=$sign->caption?></span></div>
<div align="right" style="text-align:right">&nbsp;</div>
<div align="right" style="text-align:right;padding-right: 80px;"><span class="font2" style="color:#161616;letter-spacing:0pt;font-weight:normal;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="font2" style="color:#161616;letter-spacing:0pt;font-weight:normal;">&laquo;___&raquo; ________________20__ г.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;М.П.</span></div>
<div align="center" style="text-align:center">&nbsp;</div>
	
	<?endif;?>

    <div align="center" style="text-align:center; di splay: none">&nbsp;
    <table align="right" style="text-align:right;padding-right: 60px;" cellspacing="0" cellpadding="0">
        <tr style="mso-yfti-irow:6">


            <td width="90" valign="top" style="width:67.25pt;border:none;
            mso-border-right-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
                <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:7.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">Дата тестовой сессии </span></p>
            </td>
            <td width="90" valign="top" style="width:67.25pt;border:1px solid black ; padding:0cm 5.4pt 0cm 5.4pt">
                <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt"><?= $date_check_2 ?></span></p>
            </td>
        </tr>
        <tr style="mso-yfti-irow:7">

            <td width="90" valign="top" style="width:67.25pt;border:none;
            mso-border-right-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
                <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span lang="EN-US" style="font-size:7.0pt;line-height:125%;mso-fareast-font-family:
            &quot;Times New Roman&quot;;mso-fareast-theme-font:minor-fareast;color:black;
            letter-spacing:.05pt;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN">ID</span><span
                            style="font-size:7.0pt;line-height:125%;mso-fareast-font-family:&quot;Times New Roman&quot;;
            mso-fareast-theme-font:minor-fareast;color:black;letter-spacing:.05pt;
            mso-fareast-language:ZH-CN"> тестовой сессии </span></p>
            </td>
            <td width="90" valign="top" style="width:67.25pt;border:1px solid black ; border-top:none; padding:0cm 5.4pt 0cm 5.4pt">
                <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt"><?= $test_session_id ?> (<?= $Act->number ?>)</span></p>
            </td>
        </tr>
    </table>
    </div>
	
	
	
	
<div><span class="font1" style="color:#161616;letter-spacing:
0pt;font-weight:normal;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></div>
<div align="center" style="text-align:center"><span class="font2" style="color:#161616;letter-spacing:0pt"><b>Ведомость</b></span></div>
<div align="center" style="text-align:center"><span class="font2" style="color:#161616;letter-spacing:0pt"><b>выдачи сертификатов&nbsp;прохождения&nbsp;государственного&nbsp;тестирования</b></span></div>

<div>
<table style="width:100%;border-collapse: collapse; border-spacing: 0px;"><tr><td>
<span class="font2" style="color:#161616;letter-spacing:
0pt;font-weight:normal;padding-left:30px">Дата составления:&nbsp; <?=$date; ?> г.</span>
<br>
<span class="font2" style="color:#161616;letter-spacing:
0pt;font-weight:normal;padding-left:30px">Учреждение: &laquo;<b><?=$vuz_name; ?></b>&raquo;</span>



</td></tr></table>
</div>

<div align="cen ter">
<table width="100%" cellspacing="0" cellpadding="0" border="1" style="border-collapse:collapse;border:none;" id="main_table">
    <tbody>
        <tr style="height:63.45pt">
            <td width="34" style="width:25.25pt;border:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:63.45pt">
            <div align="center" style="text-align:center"><span class="font1" style="color:#161616;letter-spacing:0pt;font-weight:normal;">№</span></div>
            <div align="center" style="text-align:center"><span class="font1" style="color:#161616;letter-spacing:0pt;font-weight:normal;">п/п</span></div>
            </td>
            <td width="250" style="width:150.0pt;border:solid windowtext 1.0pt;border-left:
            none;
            padding:0cm 5.4pt 0cm 5.4pt;height:63.45pt">
            <div align="center" style="text-align:center"><span class="font1" style="color:#161616;letter-spacing:0pt;font-weight:normal;">Ф.И.О.</span></div>
            </td>
            <td width="85" style="width:63.75pt;border:solid windowtext 1.0pt;border-left:
            none;
            padding:0cm 5.4pt 0cm 5.4pt;height:63.45pt">
            <div align="center" style="text-align:center"><span class="font1" style="color:#161616;letter-spacing:0pt;font-weight:normal;">Номер, серия паспорта (документа удостоверяющего личность)</span></div>
            </td>
            <!--<td width="84" style="width:62.75pt;border:solid windowtext 1.0pt;border-left:
            none;
            padding:0cm 5.4pt 0cm 5.4pt;height:63.45pt">
            <div align="center" style="text-align:center"><span style="font-size:10.0pt;color:#161616;letter-spacing:0pt;font-weight:normal;">Страна</span></div>
            <div align="center" style="text-align:center">&nbsp;</div>
            </td>-->
            <td width="134" style="width:100.3pt;border:solid windowtext 1.0pt;border-left:
            none;
            padding:0cm 5.4pt 0cm 5.4pt;height:63.45pt">
            <div align="center" style="text-align:center"><span class="font1" style="color:#161616;letter-spacing:0pt;font-weight:normal;">Номер бланка сертификата</span></div>
            <!--<div align="center" style="text-align:center"><span style="font-size:10.0pt;color:#161616;letter-spacing:0pt;font-weight:normal;">серия,</span></div>
            <div align="center" style="text-align:center"><span style="font-size:10.0pt;color:#161616;letter-spacing:0pt;font-weight:normal;">уровень сертификата</span></div>-->
            </td>
            <td width="94" style="width:70.85pt;border:solid windowtext 1.0pt;border-left:
            none;
            padding:0cm 5.4pt 0cm 5.4pt;height:63.45pt">
            <div align="center" style="text-align:center"><span class="font1" style="color:#161616;letter-spacing:0pt;font-weight:normal;">Дата тестирования</span></div>
            </td>
			<td width="94" style="width:70.85pt;border:solid windowtext 1.0pt;border-left:
            none;
            padding:0cm 5.4pt 0cm 5.4pt;height:63.45pt">
            <div align="center" style="text-align:center"><span class="font1" style="color:#161616;letter-spacing:0pt;font-weight:normal;">Уровень сертификата</span></div>
            </td>
            <td width="68" style="width:50.8pt;border:solid windowtext 1.0pt;border-left:
            none;
            padding:0cm 5.4pt 0cm 5.4pt;height:63.45pt">
            <div align="center" style="text-align:center"><span class="font1" style="color:#161616;letter-spacing:0pt;font-weight:normal;">Общий</span></div>
            <div align="center" style="text-align:center"><span class="font1" style="color:#161616;letter-spacing:0pt;font-weight:normal;">балл</span></div>
            </td>
            <td width="121" style="width:90.8pt;border:solid windowtext 1.0pt;border-left:
            none;
            padding:0cm 5.4pt 0cm 5.4pt;height:63.45pt">
            <div align="center" style="text-align:center"><span class="font1" style="color:#161616;letter-spacing:0pt;font-weight:normal;">Подпись лица, прошедшего тестирование</span></div>
            </td>
			<td width="84" style="width:62.75pt;border:solid windowtext 1.0pt;border-left:
            none;
            padding:0cm 5.4pt 0cm 5.4pt;height:63.45pt">
            <div align="center" style="text-align:center"><span class="font1" style="color:#161616;letter-spacing:0pt;font-weight:normal;">Примечание</span></div>
            <div align="center" style="text-align:center">&nbsp;</div>
            </td>
        </tr>
				<!--Дальше таблица с циклом-->

				<?php 
				$i=0;
				foreach($People as $Man):
				if($Man->document!='certificate') continue;
				?>
				<?php

                    $Man=CertificateDuplicate::checkForDuplicates($Man);
					
				echo '<tr style="height:17.0pt">
            <td width="34" valign="top" style="width:25.25pt;border:solid windowtext 1.0pt;
            border-top:none;
            padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt">
            <div><span class="font2" style="color:#161616;letter-spacing:
            0pt;font-weight:normal;">'.(++$i).'.</span></div>
            </td>
            <td width="104" valign="top" style="width:78.0pt;border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt">
            <div><span class="font2" style="color:#161616;letter-spacing:
            0pt;font-weight:normal;">'.$Man->getSurname_rus().' '.$Man->getName_rus().'</span></div>
            </td>
            <td width="85" valign="top" style="width:63.75pt;border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt">
            <div align="center" style="text-align:center"><span class="font2" style="color:#161616;letter-spacing:0pt;font-weight:normal;">'.$Man->passport_series.' №'.$Man->passport.'</span></div>
            </td>
            
            <td width="134" valign="top" style="width:100.3pt;border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt">
            <div align="center" style="text-align:center"><span class="font2" style="color:#161616;letter-spacing:0pt;font-weight:normal;">'.$Man->getBlank_number().'</span></div>
            </td>
            <td width="94" valign="top" style="width:70.85pt;border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt">
            <div align="center" style="text-align:center"><span class="font2" style="color:#161616;letter-spacing:0pt;font-weight:normal;">'.date('d.m.Y',strtotime($Man->testing_date)).'</span></div>
            </td>
            <td width="94" valign="top" style="width:70.85pt;border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt">
            <div align="center" style="text-align:center"><span class="font2" style="color:#161616;letter-spacing:0pt;font-weight:normal;">'./*TestLevel::getByID(ActTest::getByID($Man->test_id)->level_id)*/TestLevel::getByID(ActTest::getByID($Man->test_id)->level_id)->print.'</span></div>
            </td>
            <td width="68" valign="top" style="width:50.8pt;border-top:none;border-left:none;
            border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt">
            <div align="center" style="text-align:center"><span class="font2" style="color:#161616;letter-spacing:0pt;font-weight:normal;">'.str_replace('.',',',sprintf('%01.1f',$Man->total_percent)).'%</span></div>
            </td>
            <td width="121" valign="top" style="width:90.8pt;border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt">
            <div>&nbsp;</div>
            </td>
			<td style="border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;"></td>
			</tr>';
				endforeach;
				?>

			</tbody>
		</table>
		</div>
<div>&nbsp;</div>


	<? if ($old_view==0) :?>
<div align="center" style="text-align:center;padding-left:30px;"><span class="font2" style="color:#161616;letter-spacing:0pt;font-weight:normal;">По настоящей ведомости выдано сертификатов_______________________(количество прописью)<!--<u><?php echo num2str($i);?></u> сколько выдано сертификатов - прописью--></span></div>

<br>



<table style="width:200mm;" align="center">
<tr>
<td align="right" style="text-align:left; padding-left:30px; width:800px;">
<span class="font2" style="color:#161616;letter-spacing:0pt;font-weight:normal;"><?=$rukovod_dolzhn_1?></span></td>
<td style="width:250px;">&nbsp;
</td>
<td align="left" style="text-align:left; "><span class="font2" style="color:#161616;letter-spacing:0pt;font-weight:normal;"><nobr><?=$rukovod_fio?></nobr>
</td>
</tr>
<tr><td colspan=3>&nbsp;</td></tr>
<tr>
<td align="left" style="text-align:left; padding-left:30px;"><span class="font2" style="color:#161616;letter-spacing:0pt;font-weight:normal;">Ответственный за проведение тестирования </td>
<td width=550>&nbsp;
</td>
<td align="left" style="text-align:left "><span class="font2" style="color:#161616;letter-spacing:0pt;font-weight:normal;"><nobr><?=$Act->responsible; ?></nobr>
</td>
</tr>
</table>

	<?endif;?>
	<? if ($old_view==1) :?>

	<div align="center" style="text-align:center"><span class="font2" style="color:#161616;letter-spacing:0pt;font-weight:normal;">По настоящей ведомости выдано сертификатов_______________________(количество прописью)<!--<u><?php echo num2str($i);?></u> сколько выдано сертификатов - прописью--></span></div>

<br>
<table style="width:65%;" align="center">
<tr>
<!--<td align="right" style="text-align:right; width:800px;"><span style="font-size:
12.0pt;color:#161616;letter-spacing:0pt;font-weight:normal;">Директор центра тестирования <?=TEXT_HEADCENTER_SHORT_IP?></td>-->

<td align="right" style="text-align:right; width:800px;"><span class="font2" style="color:#161616;letter-spacing:0pt;font-weight:normal;"><?=$rukovod_dolzhn_1?></td>
<td style="width:250px;">&nbsp;
</td>
<!--<td align="left" style="text-align:left; "><span style="font-size:
12.0pt;color:#161616;letter-spacing:0pt;font-weight:normal;">_____________________
</td>-->

<td align="left" style="text-align:left; "><span class="font2" style="color:#161616;letter-spacing:0pt;font-weight:normal;"><nobr><?=$rukovod_fio?></nobr>
</td>
</tr>

<tr><td colspan=3>&nbsp;</td></tr>
<tr>
<td align="right" style="text-align:right; "><span class="font2" style="color:#161616;letter-spacing:0pt;font-weight:normal;">Ответственный за проведение тестирования </td>
<td width=550>&nbsp;
</td>
<!--19.03.13 <td align="left"  width=100  style="text-align:left"><span style="font-size:-->
<td align="left" style="text-align:left"><span class="font2" style="color:#161616;letter-spacing:0pt;font-weight:normal;"><nobr><?=$Act->responsible; ?></nobr>
</td>
</tr>
</table>
	
	<?endif;?>
	
</p></div>
</body>
</html>
