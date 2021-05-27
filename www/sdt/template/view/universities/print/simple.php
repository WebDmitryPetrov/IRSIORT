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
            <td style="border-top:solid windowtext 1.0pt;border-left:none;border-bottom:
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
            <td style="border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;
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
            <td style="border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;
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
            <td style="border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;
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
            <td style="border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;
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
            <td style="border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;
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
            <td style="border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;
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
            <td style="border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;
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
            <td style="border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;
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
            <td style="border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;
            border-right:solid black 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;
            height:42.3pt">
                <div align="center" style="text-align:center;"><?=$tester ?></div>
            </td>
        </tr>
        </tbody>
    </table>
    </p>
</div>