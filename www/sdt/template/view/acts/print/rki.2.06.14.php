<?php

$type = 1;
if (!empty($_GET['type']) &&is_numeric($_GET['type'])

) {
    $type = $_GET['type'];
}
$sign=ActSigning::getByID($type);
if(!$sign) $sign=ActSigning::getByID(1);

$rukovod_dolzhn_1 =  $sign->position;
$rukovod_fio = $sign->caption;


/*
$gct_full_name='Головной центр тестирования граждан<br>зарубежных стран по русскому языку';
$gct_short_name='ГЦТРКИ';
*/

$gct_full_name=$sign->name_of_center;
//$gct_short_name='ГЦТРКИ'; не используется


//Наименование ВУЗа
/*
$vuz_name=$Man->getTest()->getAct()->getUniversity()->name; //было так, когда выводился текцщий ВУЗ
$vuz_form=$Man->getTest()->getAct()->getUniversity()->form; //было так, когда выводился текцщий ВУЗ
*/

$vuz_name=RKI_RUDN_NAME;
$vuz_form=RKI_RUDN_FORM;

//Серия сертификата
//$sert_ser1='18'; здесь не используется?!

//Номер сертификата
//$sert_num=$Man->document_nomer; //было до 10.12.13
$sert_num='№ '.$Man->document_nomer;
//Кому выдано
$vidano=$Man->surname_rus.' '.$Man->name_rus;
$uroven=$Man->getTest()->getLevel()->print;
//Страна
$strana=$Man->getCountry()->name;
//Уровень (первого / второго / базового)
//$uroven='третьего'; здесь не используется?!
//Баллы (общий, 1й, 2й...)
//$balli_total='78,0'; здесь не используется?!
$balli_total=sprintf('%01.1f',$Man->total_percent);
$balli1=sprintf('%01.1f',$Man->grammar_percent);
$balli2=sprintf('%01.1f',$Man->reading_percent);
$balli3=sprintf('%01.1f',$Man->writing_percent);
$balli4=sprintf('%01.1f',$Man->listening_percent);
$balli5=sprintf('%01.1f',$Man->speaking_percent);
//автоматический подсчет
/* $balli_total=(($balli1+$balli2+$balli3+$balli4+$balli5)/5);
$balli_total=str_replace('.',',',$balli_total);здесь не используется?!*/ 
//разделы:
$razdeli_peresdacha='';
// 		Названия разделов
$razd1='Владение лексикой и грамматикой';
$razd2='Понимание содержания текстов при чтении';
$razd3='Владение письменной речью';
$razd4='Понимание содержания звучащей речи';
$razd5='Устное общение';
//		вывод оценки и пересдач
$peresdacha=0;
if ($balli1<65){$balli1_ocenka='неудовлетворительно'; $razdeli_peresdacha='&laquo;'.$razd1.'&raquo;, '; $peresdacha++;} else {$balli1_ocenka='удовлетворительно';}
if ($balli2<65){$balli2_ocenka='неудовлетворительно'; $razdeli_peresdacha.='&laquo;'.$razd2.'&raquo;, '; $peresdacha++;} else {$balli2_ocenka='удовлетворительно';}
if ($balli3<65){$balli3_ocenka='неудовлетворительно'; $razdeli_peresdacha.='&laquo;'.$razd3.'&raquo;, '; $peresdacha++;} else {$balli3_ocenka='удовлетворительно';}
if ($balli4<65){$balli4_ocenka='неудовлетворительно'; $razdeli_peresdacha.='&laquo;'.$razd4.'&raquo;, '; $peresdacha++;} else {$balli4_ocenka='удовлетворительно';}
if ($balli5<65){$balli5_ocenka='неудовлетворительно'; $razdeli_peresdacha.='&laquo;'.$razd5.'&raquo;, '; $peresdacha++;} else {$balli5_ocenka='удовлетворительно';}
if ($razdeli_peresdacha != '') {
$razdeli_peresdacha=substr($razdeli_peresdacha,0,-2).'.';
}

$balli_total=str_replace('.',',',$balli_total);
$balli1=str_replace('.',',',$balli1);
$balli2=str_replace('.',',',$balli2);
$balli3=str_replace('.',',',$balli3);
$balli4=str_replace('.',',',$balli4);
$balli5=str_replace('.',',',$balli5);


if ($peresdacha > 1 || $Man->getTest()->level_id==1) {
$peresdacha_text_1='всем';
$peresdacha_text_2='';
$razdeli_peresdacha='';
}
else {
$peresdacha_text_1='';
$peresdacha_text_2=':';

}
$date_day = date('d');
$date_month = date('m');
$date_month = str_replace('01', 'января', $date_month);
$date_month = str_replace('02', 'февраля', $date_month);
$date_month = str_replace('03', 'марта', $date_month);
$date_month = str_replace('04', 'апреля', $date_month);
$date_month = str_replace('05', 'мая', $date_month);
$date_month = str_replace('06', 'июня', $date_month);
$date_month = str_replace('07', 'июля', $date_month);
$date_month = str_replace('08', 'августа', $date_month);
$date_month = str_replace('09', 'сентября', $date_month);
$date_month = str_replace('10', 'октября', $date_month);
$date_month = str_replace('11', 'ноября', $date_month);
$date_month = str_replace('12', 'декабря', $date_month);

$dateYear=date('Y');
?>
<html>
<script>window.print() ;</script>
<body>
	<div style="width:180mm">
<p>
<div align="center" style="margin-right:1.0cm;text-align:center"><b>&nbsp;</b></div>
<div align="center" style="margin-right:1.0cm;text-align:center"><b>Министерство образования и науки Российской Федерации</b></div>
<div align="center" style="margin-right:1.0cm;text-align:center"><b>Государственная система тестирования граждан</b></div>
<div align="center" style="margin-right:1.0cm;text-align:center"><b>зарубежных стран по русскому языку</b></div>
<div align="center" style="margin-right:1.0cm;text-align:center"><span style="font-size:9.0pt"><b><?php echo $vuz_form;?></b></span></div>
<div align="center" style="margin-right:1.0cm;text-align:center;"><b><u><?=$vuz_name; ?></u></b></div>
<div style="margin-right:1.0cm">&nbsp;</div>
<div align="center" style="margin-right:1.0cm;text-align:center"><b><?php echo $gct_full_name;?></b></div>
<div style="margin-right:1.0cm">&nbsp;</div>
<div align="center" style="margin-right:1.0cm;text-align:center"><b>РУССКИЙ ЯЗЫК КАК ИНОСТРАННЫЙ</b></div>
<div align="center" style="margin-right:1.0cm;text-align:center"><b><span style="font-size:20.0pt;">Справка</span></b></div>
<div align="center" style="margin-right:1.0cm;text-align:center"><b><u><span style="font-size:16.0pt;"><?=$sert_num; ?></span></u></b><b><u><span style="font-size:16.0pt;"></span></u></b></div>
<div align="center" style="margin-right:1.0cm;text-align:center">&nbsp;</div>
<div align="center" style="margin-right:1.0cm;text-align:center">удостоверяет, что</div>
<div align="center" style="margin-right:1.0cm;text-align:center">&nbsp;</div>
<div align="center" style="margin-right:1.0cm;text-align:center"><b><font size="5"><u><?=$vidano; ?></u></font></b></div>
<div align="center" style="margin-right:1.0cm;text-align:center">(Ф.И.О.)</div>
<div align="center" style="margin-right:1.0cm;text-align:center"><b><font size="5"><u><?=$strana; ?></u></font></b></div>
<div align="center" style="margin-right:1.0cm;text-align:center">(страна)</div>
<div align="center" style="margin-right:1.0cm;text-align:center">&nbsp;</div>
<div style="margin-left:36.0pt;text-align:justify;
text-indent:0cm;line-height:150%">проходил тестирование по русскому языку в объёме <b><?=$uroven; ?></b> уровня и показал следующие результаты по разделам теста:</div>
<div style="margin-right:1.0cm;text-align:justify">&nbsp;</div>

<div align="center">
<table border="1" cellspacing="0" cellpadding="0" style="border-collapse:collapse;border:none;">
    <tbody>
        <tr>
		<td width="211" colspan=2 valign="top" style="width:158.4pt;border:solid white 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div style="marg in-right:1.0cm;text-align:center"><b>Раздел</b></div>
            </td>
            <td width="132" valign="top" style="width:115.0pt;border:solid white 1.0pt;
            border-left:none;
            padding:0cm 5.4pt 0cm 5.4pt">
            <div style="ma rgin-right:1.0cm;text-align:center"><b>Процент<br>правильных ответов</b></div>
            </td>
			<td width="132" valign="top" style="width:99.0pt;border:solid white 1.0pt;
            border-left:none;
            padding:0cm 5.4pt 0cm 5.4pt">
            <div style="ma rgin-right:1.0cm;text-align:center"><b>Оценка<br>(удовлетворительно,<br>неудовлетворительно)<br><br></b></div>
            </td>
		
		</tr><tr>
            <td width="26" valign="top" style="width:19.8pt;border:solid white 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div>1.</div>
            </td>
            <td width="185" valign="top" style="width:138.6pt;border:solid white 1.0pt;
            border-left:none;
            padding:0cm 5.4pt 0cm 5.4pt">
            <div><?=$razd1; ?></div>
            </td>
			<td width="132" valign="top" style="width:99.0pt;border-top:none;border-left:
            none;border-bottom:solid white 1.0pt;border-right:solid white 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="ma rgin-right:15.8pt;text-align:center"><b><?=$balli1; ?>&nbsp;</b></div>
            </td>
            <td width="295" valign="top" style="width:221.15pt;border:solid white 1.0pt;
            border-left:none;
            padding:0cm 5.4pt 0cm 5.4pt">
            <div style="margin-left:45.4pt"><b><?=$balli1_ocenka; ?></b></div>
            </td>
        </tr>
        <tr>
            <td width="26" valign="top" style="width:19.8pt;border:solid white 1.0pt;
            border-top:none;
            padding:0cm 5.4pt 0cm 5.4pt">
            <div>2.</div>
            </td>
            <td width="185" valign="top" style="width:138.6pt;border-top:none;border-left:
            none;border-bottom:solid white 1.0pt;border-right:solid white 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div><?=$razd2; ?></div>
            </td>
            <td width="132" valign="top" style="width:99.0pt;border-top:none;border-left:
            none;border-bottom:solid white 1.0pt;border-right:solid white 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="ma rgin-right:15.8pt;text-align:center"><b><?=$balli2; ?> &nbsp;</b></div>
            </td>
            <td width="295" valign="top" style="width:221.15pt;border-top:none;border-left:
            none;border-bottom:solid white 1.0pt;border-right:solid white 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div style="margin-left:45.4pt"><b><?=$balli2_ocenka; ?></b></div>
            </td>
        </tr>
        <tr>
            <td width="26" valign="top" style="width:19.8pt;border:solid white 1.0pt;
            border-top:none;
            padding:0cm 5.4pt 0cm 5.4pt">
            <div>3.</div>
            </td>
            <td width="185" valign="top" style="width:138.6pt;border-top:none;border-left:
            none;border-bottom:solid white 1.0pt;border-right:solid white 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div><?=$razd3; ?></div>
            </td>
            <td width="132" valign="top" style="width:99.0pt;border-top:none;border-left:
            none;border-bottom:solid white 1.0pt;border-right:solid white 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="ma rgin-right:15.8pt;text-align:center"><b><?=$balli3; ?> &nbsp;</b></div>
            </td>
            <td width="295" valign="top" style="width:221.15pt;border-top:none;border-left:
            none;border-bottom:solid white 1.0pt;border-right:solid white 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div style="margin-left:45.4pt"><b><?=$balli3_ocenka; ?></b></div>
            </td>
        </tr>
        <tr>
            <td width="26" valign="top" style="width:19.8pt;border:solid white 1.0pt;
            border-top:none;
            padding:0cm 5.4pt 0cm 5.4pt">
            <div>4.</div>
            </td>
            <td width="185" valign="top" style="width:138.6pt;border-top:none;border-left:
            none;border-bottom:solid white 1.0pt;border-right:solid white 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div><?=$razd4; ?></div>
            </td>
            <td width="132" valign="top" style="width:99.0pt;border-top:none;border-left:
            none;border-bottom:solid white 1.0pt;border-right:solid white 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="ma rgin-right:15.8pt;text-align:center"><b><?=$balli4; ?> &nbsp;</b></div>
            </td>
            <td width="295" valign="top" style="width:221.15pt;border-top:none;border-left:
            none;border-bottom:solid white 1.0pt;border-right:solid white 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div style="margin-left:45.4pt"><b><?=$balli4_ocenka; ?></b></div>
            </td>
        </tr>
        <tr>
            <td width="26" valign="top" style="width:19.8pt;border:solid white 1.0pt;
            border-top:none;
            padding:0cm 5.4pt 0cm 5.4pt">
            <div>5.</div>
            </td>
            <td width="185" valign="top" style="width:138.6pt;border-top:none;border-left:
            none;border-bottom:solid white 1.0pt;border-right:solid white 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div><?=$razd5; ?></div>
            </td>
            <td width="132" valign="top" style="width:99.0pt;border-top:none;border-left:
            none;border-bottom:solid white 1.0pt;border-right:solid white 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="ma rgin-right:15.8pt;text-align:center"><b><?=$balli5; ?> &nbsp;</b></div>
            </td>
            <td width="295" valign="top" style="width:221.15pt;border-top:none;border-left:
            none;border-bottom:solid white 1.0pt;border-right:solid white 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div style="margin-left:45.4pt"><b><?=$balli5_ocenka; ?></b></div>
            </td>
        </tr>
    </tbody>
</table>
</div>
<div align="center" style="margin-right:21.25pt;text-align:center">&nbsp;</div>
<div align="center" style="margin-left:9.0pt;text-align:center;
text-indent:9.0pt">Для получения сертификата должен пройти повторное тестирование по <?=$peresdacha_text_1 ?> разделам<?=$peresdacha_text_2 ?></div>
<div align="center" style="margin-left:9.0pt;text-align:center;
text-indent:9.0pt"><?=$razdeli_peresdacha; ?></div>
<div style="margin-left:9.0pt;text-align:justify;text-indent:
9.0pt">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
<div style="margin-right:21.25pt"><b>&nbsp;</b></div>

<table style="width:100%" border=0>

<tr>

<td style="width:10%; padd ing-right:10px"> </td>
<td align=right style="width:90%;"><b><?=$rukovod_dolzhn_1 ?></b></td>


</tr>


<tr><td colspan=2><br></td></tr>

<tr>
<td></td>


<td align=right>______________________ <b><?=$rukovod_fio ?></b></td>


</tr>



<tr style="height:15px"><td colspan=2></td></tr>


<tr>
<td></td>


<td align=right><b>&laquo;<?=$date_day?>&raquo; <?=$date_month?> <?=$dateYear?> г.</b></td>


</tr>


<tr style="height:35px"><td colspan=2></td></tr>

    <!--

    <tr>
    <td></td>
    <td><span style="padding-left:25px">М.П.</span></td>
    <td></td>
    <td><span style="padding-left:60px">М.П.</span></td>
    <td></td>
    </tr>


    -->


</table>







<!--<table style="width:100%" border=0>

<tr>

<td style="width:12%; padd ing-right:10px"> </td>
<td style="text-align:justify;width:33%; pa dding-left:10px">Представитель организации,<br>проводящей тестирование</td>
<td style="width:13%; pa dding-right:10px; paddi ng-left:10px"></td>
<td style="text-align:justify;width:35%; pa dding-right:10px">Директор<br>Центра <?=TEXT_HEADCENTER_SHORT_IP?></td>
<td style="width:10%;pad ding-left:10px"></td>


</tr>


<tr><td colspan=5><br></td></tr>

<tr>
<td></td>
<td>___________________________</td>
<td></td>
<td>___________________________</td>
<td></td>

</tr>



<tr style="height:15px"><td colspan=5></td></tr>


<tr>
<td></td>
<td>_____________________ <?php echo date('Y');?>г.</td>
<td></td>
<td>&laquo;___&raquo; ________________ <?php echo date('Y');?>г.</td>
<td></td>

</tr>


<tr style="height:35px"><td colspan=5></td></tr>

<tr>
<td></td>
<td><span style="padding-left:25px">М.П.</span></td>
<td></td>
<td><span style="padding-left:60px">М.П.</span></td>
<td></td>

</tr>


</table>-->


<div>&nbsp;</div>
</p>
</div>
</body>
</html>