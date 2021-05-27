<?php
/** @var Act $act */
header ("Content-Type: text/html; charset=windows-1251");

$organization = $act->getUniversity()->name; //название орагнизации

$num = 0; //номер п/п


$otvetst = $act->responsible;

?>


<html>
<script>window.print();</script>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
    <style type="text/css">

        .table td {
            padding: 0cm 5.4pt 0cm 5.4pt;
            font-size: 12pt;
        }

    </style>


</head>

<body>
<p>

<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <b>Список лиц, прошедших государственное тестирование&nbsp;в <u><?=$organization; ?></u></b></div>
<div><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (название организации)</span>
</div>
<div align=center><b>______________________________________________________________________________________</b></div>
<div><b>&nbsp;</b></div>
<table class=table cellspacing="0" cellpadding="0" border="1" style="border-collapse:collapse;border:none;">
    <tbody>
    <tr style="page-break-inside:avoid;
            height:8.0pt">
        <td width="36" valign="top" style="width:26.7pt;border:solid windowtext 1.0pt;height:8.0pt" rowspan="2">
            <div><b><span style="font-size:11.0pt">№</span></b></div>
            <div><b><span style="font-size:11.0pt">п/п</span></b></div>
        </td>
        <td width="104" valign="top" style="width:77.95pt;border:solid windowtext 1.0pt;
            border-left:none;height:8.0pt" rowspan="2">
            <div><b><span style="font-size:11.0pt">Фамилия</span></b></div>
            <div>русскими / латинскими</div>
        </td>
        <td width="104" valign="top" style="width:77.95pt;border:solid windowtext 1.0pt;
            border-left:none;height:8.0pt" rowspan="2">
            <div><b><span style="font-size:11.0pt">Имя</span></b></div>
            <div>русскими / латинскими</div>
        </td>
        <td width="95" valign="top" style="width:70.9pt;border:solid windowtext 1.0pt;
            border-left:none;height:8.0pt" rowspan="2">
            <div><b><span style="font-size:11.0pt">Страна</span></b></div>
        </td>
        <td width="85" valign="top" style="width:63.75pt;border:solid windowtext 1.0pt;
            border-left:none;height:8.0pt" rowspan="2">
            <div><b><span style="font-size:11.0pt">Дата теста</span></b></div>
        </td>
        <td width="444" valign="top" style="width:333.15pt;border:solid windowtext 1.0pt;
            border-left:none;height:8.0pt" colspan="6">
            <div align="center" style="text-align:center"><b><span style="font-size:11.0pt">Результаты (%)</span></b>
            </div>
        </td>
        <td width="76" valign="top" style="width:2.0cm;border:solid windowtext 1.0pt;
            border-left:none;height:8.0pt" rowspan="2">
            <div><b><span style="font-size:11.0pt">Уровень</span></b></div>
        </td>
        <td width="76" valign="top" style="width:2.0cm;border:solid windowtext 1.0pt;
            border-left:none;height:8.0pt" rowspan="2">
            <div><b><span style="font-size:11.0pt">№ </span></b></div>
            <div><b><span style="font-size:11.0pt">серт./</span></b></div>
            <div><b><span style="font-size:11.0pt">справки</span></b></div>
        </td>
    </tr>
    <tr style="page-break-inside:avoid;height:14.0pt">
        <td width="66" valign="top" style="width:49.65pt;border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;height:14.0pt">
            <div align="center" style="text-align:center"><span style="font-size:11.0pt">1</span></div>
            <div align="center" style="text-align:center"><span style="font-size:11.0pt">Чтение</span></div>
            <div align="center" style="text-align:center"><span style="font-size:11.0pt">баллы </span><span
                    style="font-size:11.0pt;">/</span><span style="font-size:11.0pt"> % </span></div>
        </td>
        <td width="76" valign="top" style="width:2.0cm;border-top:none;border-left:none;
            border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;height:14.0pt">
            <div align="center" style="text-align:center"><span style="font-size:11.0pt">2</span></div>
            <div align="center" style="text-align:center"><span style="font-size:11.0pt">Письмо</span></div>
            <div align="center" style="text-align:center"><span style="font-size:11.0pt">баллы </span><span
                    style="font-size:11.0pt;">/</span><span style="font-size:11.0pt"> %</span></div>
        </td>
        <td width="94" valign="top" style="width:70.85pt;border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;height:14.0pt">
            <div align="center" style="text-align:center"><span style="font-size:11.0pt">3</span></div>
            <div align="center" style="text-align:center"><span style="font-size:11.0pt">Лексика</span></div>
            <div align="center" style="text-align:center"><span style="font-size:11.0pt">Грамматика</span></div>
            <div align="center" style="text-align:center"><span style="font-size:11.0pt">баллы </span><span
                    style="font-size:11.0pt;">/</span><span style="font-size:11.0pt"> %</span></div>
        </td>
        <td width="85" valign="top" style="width:63.8pt;border-top:none;border-left:none;
            border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;height:14.0pt">
            <div align="center" style="text-align:center"><span style="font-size:11.0pt">4</span></div>
            <div align="center" style="text-align:center"><span style="font-size:11.0pt">Аудирова-ние</span></div>
            <div align="center" style="text-align:center"><span style="font-size:11.0pt">баллы </span><span
                    style="font-size:11.0pt;">/</span><span style="font-size:11.0pt"> %</span></div>
        </td>
        <td width="66" valign="top" style="width:49.6pt;border-top:none;border-left:none;
            border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;height:14.0pt">
            <div align="center" style="text-align:center"><span style="font-size:11.0pt">5</span></div>
            <div align="center" style="text-align:center"><span style="font-size:11.0pt">Устная </span></div>
            <div align="center" style="text-align:center"><span style="font-size:11.0pt">речь</span></div>
            <div align="center" style="text-align:center"><span style="font-size:11.0pt">баллы </span><span
                    style="font-size:11.0pt;">/</span><span style="font-size:11.0pt"> %</span></div>
        </td>
        <td width="57" valign="top" style="width:42.55pt;border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;height:14.0pt">
            <div align="center" style="text-align:center"><span style="font-size:11.0pt">Общ.</span></div>
            <div align="center" style="text-align:center"><span style="font-size:11.0pt">балл</span></div>
            <div align="center" style="text-align:center"><span style="font-size:11.0pt">баллы </span><span
                    style="font-size:11.0pt;">/</span><span style="font-size:11.0pt"> %</span></div>
        </td>
    </tr>
    <?php foreach ($act->getPeople() as $Man):
        $surname = $Man->surname_rus . ' / ' . $Man->surname_lat;
        $name = $Man->name_rus . ' / ' . $Man->name_lat;
        $country = $Man->getCountry()->name;
        $testing_date = date('d.m.Y', strtotime($Man->testing_date));


        $balli1 = sprintf('%01.1f', $Man->reading) . ' / ' . sprintf('%01.1f', $Man->reading_percent);

        $balli2 = sprintf('%01.1f', $Man->writing) . ' / ' . sprintf('%01.1f', $Man->writing_percent);
        $balli3 = sprintf('%01.1f', $Man->grammar) . ' / ' . sprintf('%01.1f', $Man->grammar_percent);
        $balli4 = sprintf('%01.1f', $Man->listening) . ' / ' . sprintf('%01.1f', $Man->listening_percent);
        $balli5 = sprintf('%01.1f', $Man->speaking) . ' / ' . sprintf('%01.1f', $Man->speaking_percent);
        $balli_total = sprintf('%01.1f', $Man->total) . ' / ' . sprintf('%01.1f', $Man->total_percent);
        $balli1 = str_replace('.', ',', $balli1);
        $balli2 = str_replace('.', ',', $balli2);
        $balli3 = str_replace('.', ',', $balli3);
        $balli4 = str_replace('.', ',', $balli4);
        $balli5 = str_replace('.', ',', $balli5);
        $balli_total = str_replace('.', ',', $balli_total);
        $uroven = $Man->document=='certificate'?'Сертификат':'Справка';
        $sert_num = ''; //$Man->document_nomer;


        ?>
    <tr>
        <td width="36" valign="top" style="width:26.7pt;border:solid windowtext 1.0pt;
            border-top:none;
            padding:0cm 5.4pt 0cm 5.4pt">
            <div><?=++$num; ?></div>

        </td>
        <td width="104" valign="top" style="width:77.95pt;border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div><?=$surname; ?></div>
        </td>
        <td width="104" valign="top" style="width:77.95pt;border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div><?=$name; ?></div>
        </td>
        <td width="95" valign="top" style="width:70.9pt;border-top:none;border-left:none;
            border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div><?=$country; ?></div>
        </td>
        <td width="85" valign="top" style="width:63.75pt;border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div><?=$testing_date; ?></div>
        </td>
        <td width="66" valign="top" style="width:49.65pt;border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center"><?=$balli1; ?></div>
        </td>
        <td width="76" valign="top" style="width:2.0cm;border-top:none;border-left:none;
            border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center"><?=$balli2; ?></div>
        </td>
        <td width="94" valign="top" style="width:70.85pt;border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center"><?=$balli3; ?></div>
        </td>
        <td width="85" valign="top" style="width:63.8pt;border-top:none;border-left:none;
            border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center"><?=$balli4; ?></div>
        </td>
        <td width="66" valign="top" style="width:49.6pt;border-top:none;border-left:none;
            border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center"><?=$balli5; ?></div>
        </td>
        <td width="57" valign="top" style="width:42.55pt;border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center"><?=$balli_total; ?></div>
        </td>
        <td width="76" valign="top" style="width:2.0cm;border-top:none;border-left:none;
            border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center"><?=$uroven; ?></div>
        </td>
        <td width="76" valign="top" style="width:2.0cm;border-top:none;border-left:none;
            border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center"><?=$sert_num; ?></div>
        </td>
    </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div>&nbsp;</div>
<!--
<div><b>Тесторы:</b></div>
<div><b>&nbsp;</b></div>
<div style="margin-left:18.0pt;text-indent:-18.0pt;"><b>1.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></b><b  style="border-bottom:1px solid; "><?php echo  $act->tester1; ?></b>
</div>
<div><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    (Ф.И.О., подпись)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </b></div>
<div><b>&nbsp;</b></div>
<div style="margin-left:18.0pt;text-indent:-18.0pt;"><b>2.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></b><b  style="border-bottom:1px solid;"><?php echo  $act->tester2; ?></b>
</div>
<div><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    (Ф.И.О., подпись)</b></div>
<div><b>&nbsp;</b></div>
-->

<table border=0>

<tr>
<td colspan=3><b>Тесторы:</b></td>
</tr>
<tr>
<td>&nbsp;</td>
</tr>
<tr>
<td><b>1.&nbsp;&nbsp;&nbsp;</b></td><td style="border-bottom:1px solid;" width=500px><b  style="border-bo ttom:1px solid; "><?php echo  $act->tester1; ?></b>&nbsp;</td>
</tr>
<tr>
<td></td>
<td align=center><b>(Ф.И.О., подпись)</b></td>
</tr>
<tr>
<td>&nbsp;</td>
</tr>
<tr>
<td><b>2.&nbsp;&nbsp;&nbsp;</b></td><td style="border-bottom:1px solid;" width=500px><b  style="border-bo ttom:1px solid; "><?php echo  $act->tester2; ?></b>&nbsp;</td>
</tr>
<tr>
<td></td>
<td align=center><b>(Ф.И.О., подпись)</b></td>
</tr>
<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>
</table>

<table>
    <tr>
	
        <td><b>Ответственный за проведение тестирования&nbsp;&nbsp;&nbsp;</b></td>
        <td style="border-bottom:1px solid;" width=500px><b><?=$otvetst; ?></b></td>
    </tr>
    <tr>
        
        <td></td>
        <td align=center><b>(Ф.И.О., подпись)</b></td>
    </tr>
    <tr>
        
        <td></td>
        <td align=right>Место печати</td>
    </tr>
</table>
<!--
<div><b>Ответственный за проведение тестирования&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></b></div>
<div><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (Ф.И.О., подпись)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </b></div>
<div style="margin-top:0cm;margin-right:7.1pt;margin-bottom:0cm;
margin-left:17.0cm;margin-bottom:.0001pt;text-autospace:none">&nbsp;Место печати<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </b></div>
<div>&nbsp;</div>
<div>&nbsp;</div>-->
</p>
</body>
</html>