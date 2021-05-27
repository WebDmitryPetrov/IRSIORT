<?php
header ("Content-Type: text/html; charset=windows-1251");
//Наименование ВУЗа
$vuz_name=$Man->getTest()->getAct()->getUniversity()->name;
$vuz_form=$Man->getTest()->getAct()->getUniversity()->form;
//Серия сертификата
//$sert_ser1='18'; здесь не используется?!
$Level= $Man->getTest()->getLevel();
//Номер сертификата
$sert_num=$Man->document_nomer;
//Кому выдано
$vidano=$Man->surname_rus.' '.$Man->name_rus;
//Страна
$strana=$Man->getCountry()->name;
//Уровень (первого / второго / базового)
//$uroven='третьего'; здесь не используется?!
//Баллы (общий, 1й, 2й...)
//$balli_total='78,0'; здесь не используется?!
$peresdacha = 0;
$balli_total=sprintf('%01.1f',$Man->total_percent);

//разделы:
$razdeli_peresdacha='';


$balli_total=str_replace('.',',',$balli_total);


?>
<html>
<script>window.print() ;</script>
<body>
	<div style="width:180mm">
<p>
<div align="center" style="margin-right:1.0cm;text-align:center"><b>&nbsp;</b></div>
<div align="center" style="margin-right:1.0cm;text-align:center"><b>Министерство науки и высшего образования Российской Федерации</b></div>
<div align="center" style="margin-right:1.0cm;text-align:center"><b>Государственная система тестирования граждан</b></div>
<div align="center" style="margin-right:1.0cm;text-align:center"><b>зарубежных стран по русскому языку</b></div>
<div align="center" style="margin-right:1.0cm;text-align:center"><span style="font-size:9.0pt"><?php echo $vuz_form; ?></span></div>
<div align="center" style="margin-right:1.0cm;text-align:center;"><b><u>&laquo;<?=$vuz_name; ?>&raquo;</u></b></div>
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
text-indent:0cm;line-height:150%">проходил тестирование по русскому языку в объёме <!--!-->базового<!--!--> уровня <!--на получение гражданства РФ-->и показал следующие результаты по разделам теста:</div>
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
		
		</tr>

        <?
        $sub_tests=SubTests::getByLevel($Level);
        //die(var_dump($sub_tests));
        $subTestResults = $Man->getResults();
        $li=0;
        foreach($sub_tests as $sub_test)
        {
            $li++;
            //echo $sub_test->caption;
//die(var_dump(SubTestResults::getByMan($Man)->getByOrder($sub_test->order)->percent));

            $percent=$subTestResults->getByOrder($sub_test->order)->percent;

            if ($percent < $Level->percent_max) {
                $ocenka = 'неудовлетворительно';
                $razdeli_peresdacha.= '&laquo;' . $sub_test->full_caption . '&raquo;, ';
                $peresdacha++;
            } else {
                $ocenka = 'удовлетворительно';
            }

            echo '<tr>
            <td width="26" valign="top" style="width:19.8pt;border:solid white 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
                <div>'.$li.'.</div>
            </td>
            <td width="185" valign="top" style="width:138.6pt;border:solid white 1.0pt;
            border-left:none;
            padding:0cm 5.4pt 0cm 5.4pt">
                <div>'.$sub_test->full_caption.'</div>
            </td>
            <td width="132" valign="top" style="width:99.0pt;border-top:none;border-left:
            none;border-bottom:solid white 1.0pt;border-right:solid white 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
                <div align="center" style="ma rgin-right:15.8pt;text-align:center"><b>'.str_replace('.',',',sprintf('%01.1f', $percent)).'&nbsp;</b></div>
            </td>
            <td width="295" valign="top" style="width:221.15pt;border:solid white 1.0pt;
            border-left:none;
            padding:0cm 5.4pt 0cm 5.4pt">
                <div style="margin-left:45.4pt"><b>'.$ocenka.'</b></div>
            </td>
        </tr>';




        }




        ?>


    </tbody>
</table>
</div>
<?
if ($razdeli_peresdacha != '') {
    $razdeli_peresdacha=substr($razdeli_peresdacha,0,-2).'.';
}
?>
<div align="center" style="margin-right:21.25pt;text-align:center">&nbsp;</div>
<div align="center" style="margin-left:9.0pt;text-align:center;
text-indent:9.0pt">Для получения сертификата должен пройти повторное тестирование по разделам:</div>
<div align="center" style="margin-left:9.0pt;text-align:center;
text-indent:9.0pt"><?=$razdeli_peresdacha; ?></div>
<div style="margin-left:9.0pt;text-align:justify;text-indent:
9.0pt">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
<div style="margin-right:21.25pt"><b>&nbsp;</b></div>





<table style="width:100%" border=0>

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


</table>








<div>&nbsp;</div>
</p>
</div>