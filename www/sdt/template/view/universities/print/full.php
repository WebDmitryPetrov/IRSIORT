<?php
/** @var University $university */
//($university);


$vuz_name=$university->name;
$vuz_name_short=$university->short_name;
$form=$university->form;
$rektor=$university->rector;
$yur_addr=$university->legal_address;
$phone=$university->contact_phone;
$fax=$university->contact_fax;
$email=$university->contact_email;
$additional_cont=$university->contact_other;
$tester=$university->responsible_person;

$bank=$university->bank;
$city=$university->city;
$rasch_schet=$university->rc;
$lic_schet=$university->lc;
$korr_schet=$university->kc;
$bik=$university->bik;
$inn=$university->inn;
$kpp=$university->kpp;
$okato=$university->okato;
$okpo=$university->okpo;
$komments=$university->comments;



?>
<html>
<head>
    <script>window.print() ;</script>
</head>
<body>
<div style="width:180mm;">
<p>
<table border="0" align="left" cellspacing="0" cellpadding="0" style="border-collapse:collapse;margin-left:6.75pt;margin-right:6.75pt;">
<tbody>
<tr style="height:26.05pt">
    <td style="border:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:
            26.05pt">
        <div align="center" style="margin-top:0cm;margin-right:.45pt;
            margin-bottom:0cm;margin-left:-9.0pt;margin-bottom:.0001pt;text-align:center;"><b><span style="font-size:
            14.0pt">Название</span></b></div>
    </td>
    <td  style="border-top:solid windowtext 1.0pt;border-left:none;border-bottom:
            solid windowtext 1.0pt;border-right:solid black 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;
            height:26.05pt">
        <div align="center" style="text-align:center;"><?=$vuz_name ?></div>
    </td>
</tr>
<tr style="height:26.05pt">
    <td style="border:solid windowtext 1.0pt;border-top:none;padding:0cm 5.4pt 0cm 5.4pt;
            height:26.05pt">
        <div align="center" style="text-align:center;"><b><span style="font-size:14.0pt">Сокращенное название</span></b></div>
    </td>
    <td  style="border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;
            border-right:solid black 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;
            height:26.05pt">
        <div align="center" style="text-align:center;"><?=$vuz_name_short ?></div>
    </td>
</tr>
<tr style="height:13.0pt">
    <td style="border:solid windowtext 1.0pt;border-top:none;padding:0cm 5.4pt 0cm 5.4pt;
            height:13.0pt">
        <div align="center" style="text-align:center;"><b><span style="font-size:14.0pt">Правовая форма</span></b></div>
    </td>
    <td   style="border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;
            border-right:solid black 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;
            height:13.0pt">
        <div align="center" style="text-align:center;"><?=$form ?></div>
    </td>
</tr>
<tr style="height:13.0pt">
    <td style="border:solid windowtext 1.0pt;border-top:none;padding:0cm 5.4pt 0cm 5.4pt;
            height:13.0pt">
        <div align="center" style="text-align:center;"><b><span style="font-size:14.0pt">Ректор</span></b></div>
    </td>
    <td  style="border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;
            border-right:solid black 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;
            height:13.0pt">
        <div align="center" style="text-align:center;"><?=$rektor ?></div>
    </td>
</tr>
<tr style="height:26.05pt">
    <td style="border:solid windowtext 1.0pt;border-top:none;padding:0cm 5.4pt 0cm 5.4pt;
            height:26.05pt">
        <div align="center" style="text-align:center;"><b><span style="font-size:14.0pt">Юридический адрес</span></b></div>
    </td>
    <td  style="border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;
            border-right:solid black 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;
            height:26.05pt">
        <div align="center" style="text-align:center;"><?=$yur_addr ?></div>
    </td>
</tr>
<tr style="height:26.05pt">
    <td style="border:solid windowtext 1.0pt;border-top:none;padding:0cm 5.4pt 0cm 5.4pt;
            height:26.05pt">
        <div align="center" style="text-align:center;"><b><span style="font-size:14.0pt">Телефон</span></b></div>
    </td>
    <td  style="border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;
            border-right:solid black 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;
            height:26.05pt">
        <div align="center" style="text-align:center;"><?=$phone ?></div>
    </td>
</tr>
<tr style="height:19.7pt">
    <td style="border:solid windowtext 1.0pt;border-top:none;padding:0cm 5.4pt 0cm 5.4pt;
            height:19.7pt">
        <div align="center" style="text-align:center;"><b><span style="font-size:14.0pt">Факс</span></b></div>
    </td>
    <td  style="border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;
            border-right:solid black 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;
            height:19.7pt">
        <div align="center" style="text-align:center;"><?=$fax ?></div>
    </td>
</tr>
<tr style="height:26.05pt">
    <td style="border:solid windowtext 1.0pt;border-top:none;padding:0cm 5.4pt 0cm 5.4pt;
            height:26.05pt">
        <div align="center" style="text-align:center;"><b><span style="font-size:14.0pt">Email</span></b></div>
    </td>
    <td  style="border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;
            border-right:solid black 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;
            height:26.05pt">
        <div align="center" style="text-align:center;"><?=$email ?></div>
    </td>
</tr>
<tr style="height:26.05pt">
    <td style="border:solid windowtext 1.0pt;border-top:none;padding:0cm 5.4pt 0cm 5.4pt;
            height:26.05pt">
        <div align="center" style="text-align:center;"><b><span style="font-size:14.0pt">Дополнительные контакты</span></b></div>
    </td>
    <td  style="border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;
            border-right:solid black 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;
            height:26.05pt">
        <div align="center" style="text-align:center;"><?=$additional_cont ?></div>
    </td>
</tr>
<tr style="height:42.3pt">
    <td style="border:solid windowtext 1.0pt;border-top:none;padding:0cm 5.4pt 0cm 5.4pt;
            height:42.3pt">
        <div align="center" style="text-align:center;"><b><span style="font-size:14.0pt">Ответственный за проведение   тестирования</span></b></div>
    </td>
    <td  style="border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;
            border-right:solid black 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;
            height:42.3pt">
        <div align="center" style="text-align:center;"><?=$tester ?></div>
    </td>
</tr>
<tr style="height:25.5pt">
    <td  style="border:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;
            height:25.5pt">
        <div align="center" style="text-align:center;"><b><span style="font-size:14.0pt">Банк</span></b></div>
    </td>
    <td   style="border-top:solid windowtext 1.0pt;
            border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid black 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;
            height:25.5pt">
        <div align="center" style="text-align:center;"><?=$bank ?></div>
    </td>
</tr>
<tr style="height:12.75pt">
    <td  style="border:solid windowtext 1.0pt;border-top:
            none;padding:0cm 5.4pt 0cm 5.4pt;
            height:12.75pt">
        <div align="center" style="text-align:center;"><b><span style="font-size:14.0pt">Город</span></b></div>
    </td>
    <td   style="border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid black 1.0pt;
            padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt">
        <div align="center" style="text-align:center;"><?=$city ?></div>
    </td>
</tr>
<tr style="height:12.75pt">
    <td  style="border:solid windowtext 1.0pt;border-top:
            none;padding:0cm 5.4pt 0cm 5.4pt;
            height:12.75pt">
        <div align="center" style="text-align:center;"><b><span style="font-size:14.0pt">Расчетный   счет</span></b></div>
    </td>
    <td   style="border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid black 1.0pt;
            padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt">
        <div align="center" style="text-align:center;"><?=$rasch_schet ?></div>
    </td>
</tr>
<tr style="height:12.75pt">
    <td  style="border:solid windowtext 1.0pt;border-top:
            none;padding:0cm 5.4pt 0cm 5.4pt;
            height:12.75pt">
        <div align="center" style="text-align:center;"><b><span style="font-size:14.0pt">Лицевой   счет</span></b></div>
    </td>
    <td   style="border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid black 1.0pt;
            padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt">
        <div align="center" style="text-align:center;"><?=$lic_schet ?></div>
    </td>
</tr>
<tr style="height:25.5pt">
    <td  style="border:solid windowtext 1.0pt;border-top:
            none;padding:0cm 5.4pt 0cm 5.4pt;
            height:25.5pt">
        <div align="center" style="text-align:center;"><b><span style="font-size:14.0pt">Корреспондентский   счет</span></b></div>
    </td>
    <td   style="border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid black 1.0pt;
            padding:0cm 5.4pt 0cm 5.4pt;height:25.5pt">
        <div align="center" style="text-align:center;"><?=$korr_schet ?></div>
    </td>
</tr>
<tr style="height:12.75pt">
    <td  style="border:solid windowtext 1.0pt;border-top:
            none;padding:0cm 5.4pt 0cm 5.4pt;
            height:12.75pt">
        <div align="center" style="text-align:center;"><b><span style="font-size:14.0pt">БИК</span></b></div>
    </td>
    <td   style="border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid black 1.0pt;
            padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt">
        <div align="center" style="text-align:center;"><?=$bik ?></div>
    </td>
</tr>
<tr style="height:12.75pt">
    <td  style="border:solid windowtext 1.0pt;border-top:
            none;padding:0cm 5.4pt 0cm 5.4pt;
            height:12.75pt">
        <div align="center" style="margin-left:-18.0pt;text-align:center;"><b><span style="font-size:14.0pt">ИНН</span></b></div>
    </td>
    <td   style="border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid black 1.0pt;
            padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt">
        <div align="center" style="text-align:center;"><?=$inn ?></div>
    </td>
</tr>
<tr style="height:12.75pt">
    <td  style="border:solid windowtext 1.0pt;border-top:
            none;padding:0cm 5.4pt 0cm 5.4pt;
            height:12.75pt">
        <div align="center" style="text-align:center;"><b><span style="font-size:14.0pt">КПП</span></b></div>
    </td>
    <td   style="border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid black 1.0pt;
            padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt">
        <div align="center" style="text-align:center;"><?=$kpp ?></div>
    </td>
</tr>
<tr style="height:12.75pt">
    <td  style="border:solid windowtext 1.0pt;border-top:
            none;padding:0cm 5.4pt 0cm 5.4pt;
            height:12.75pt">
        <div align="center" style="text-align:center;"><b><span style="font-size:14.0pt">Код по   ОКАТО</span></b></div>
    </td>
    <td   style="border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid black 1.0pt;
            padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt">
        <div align="center" style="text-align:center;"><?=$okato ?></div>
    </td>
</tr>
<tr style="height:12.75pt">
    <td  style="border:solid windowtext 1.0pt;border-top:
            none;padding:0cm 5.4pt 0cm 5.4pt;
            height:12.75pt">
        <div align="center" style="text-align:center;"><b><span style="font-size:14.0pt">Код по   ОКПО</span></b></div>
    </td>
    <td   style="border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid black 1.0pt;
            padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt">
        <div align="center" style="text-align:center;"><?=$okpo ?></div>
    </td>
</tr>
<tr style="height:12.75pt">
    <td  style="border:solid windowtext 1.0pt;border-top:
            none;padding:0cm 5.4pt 0cm 5.4pt;
            height:12.75pt">
        <div align="center" style="text-align:center;"><b><span style="font-size:14.0pt">Комментарии</span></b></div>
    </td>
    <td   style="border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid black 1.0pt;
            padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt">
        <div align="center" style="text-align:center;"><?=$komments ?></div>
    </td>
</tr>
</tbody>
</table>
</p>
</div>