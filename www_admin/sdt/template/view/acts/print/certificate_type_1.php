<?php
header ("Content-Type: text/html; charset=windows-1251");
/** @var ActMan $Man */
//$type = 1;
//if (!empty($_GET['type']) &&is_numeric($_GET['type'])
//
//) {
//    $type = $_GET['type'];
//}

$x = 10.00125;
$y = 10.00125; //для печати на нормальной странице
$y = 8.5; //для печати на нормальной странице
$x = $y = 0; //!!
$wide_cell=105;
$small_cell=52.5;
//$textColour = array(255, 0, 0); //задаем цвет текста
$textColour = array(0, 0, 0); //задаем цвет текста
$borders = 0; //отображать ли границы ячеек
$wide_cell_enhancer=3; //смещение ячееек

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

//$document_nomer = $Man->document_nomer; //номер документа
//$sign=ActSigning::getByID($type);
//if(!$sign) $sign=ActSigning::getByID(1);
//
//$rukovod_dolzhn_1 =  $sign->position;
//$rukovod_fio = $sign->caption;
//
//$rukovod_dolzhn_2 = OUR_SHORT_NAME; //должность руководителя строка 2
//
//
//$org = OUR_FULL_NAME; // Организация длинное название
//$org_first_line = ''; // Организация длинное название 1я строка
//$org_second_line = ''; // Организация длинное название 2я строка
//
//
//
//if (strlen($org) > 30) {
//$org_first_line=$org;
//$org_first_line=substr($org_first_line,0,30);
//
//$org_first_line=substr($org_first_line,0,strrpos($org_first_line,' '));
//
//$org_second_line=$org;
//$org_second_line=substr($org_second_line,strlen($org_first_line)+1);
//
//} else {
//    $org_first_line=$org;
//}
//
//
//
//
//$fio_rus = $Man->surname_rus . ' ' . $Man->name_rus; //ФИО рус
//$fio_lat = $Man->surname_lat . ' ' . $Man->name_lat; //ФИО лат
//if (strlen($fio_rus) > 323) {
//    $fontsize1 = 10;
//} else {
//    $fontsize1 = 14;
//}
//
//$country = $Man->getCountry()->name; //страна
//if (strlen($country) > 32) {
//    $fontsize2 = 10;
//} else {
//    $fontsize2 = 14;
//}
//
//$uroven = $Man->getTest()->getLevel()->print; // уровень
//
//
//
//if (strlen($uroven) > 12) {
//    $fontsize3 = 8;
//} else {
//    $fontsize3 = 14;
//}
//
//
//
//$city=strtoupper(CERTIFICATE_CITY);
//
//
//
//
//$fio_rus = strtoupper($fio_rus); //ФИО рус - большие
//$fio_lat = strtoupper($fio_lat); //ФИО лат - большие
//$uroven='«'.strtoupper($uroven).'»';
//$rukovod_dolzhn_2 = strtoupper($rukovod_dolzhn_2);
//
//$city=strtoupper($city);
//
//
//$print_date=date('d.m.Y',strtotime($Man->blank_date)).' г.';
//
//if ($Man->valid_till == null || $Man->valid_till == '0000-00-00') $cert_date = 'БЕССРОЧНО';
//else $cert_date=date('d.m.Y',strtotime($Man->valid_till)).' г.';;
//
//
//$rukovod_dolzhn_1 =  strtoupper($rukovod_dolzhn_1);
//$rukovod_fio = strtoupper($rukovod_fio);



require_once($_SERVER['DOCUMENT_ROOT'] . "/fpdf/fpdf.php");


$pdf = new FPDF('L', 'mm', array(210,158));


$pdf->SetTopMargin($y); //отступ сверху
foreach ($people as $person)
    {
    $pdf->AddPage(); //вставляем страницу
//        $pdf->Image($_SERVER['DOCUMENT_ROOT'] . "/fpdf/certifikat_1_cut.jpg",0,0,210,158);
    $pdf->SetTextColor($textColour[0], $textColour[1], $textColour[2]);
    $pdf->SetX($x);

    $pdf->AddFont('VERDANA1251', '', '1arial.php');
    $pdf->AddFont('VERDANA1251_bold', '', 'arialbd.php');
    $pdf->SetFont('VERDANA1251_bold', '', 11*$person['font_resizer']);
    $pdf->SetX($x);
    $pdf->Cell(0, 33, '', $borders, 2, 'R'); //отступ
    $pdf->Cell($wide_cell+$wide_cell_enhancer, 3, '', $borders, 0, 'С'); //отступ слева
    $pdf->Cell($wide_cell-$wide_cell_enhancer, 3, $person['fio_rus'], $borders, 2, $person['fio_align']); //ФИО рус




    $pdf->SetX($x);
    $pdf->Cell(0, 7.5, '', $borders, 2, 'R'); //отступ
    $pdf->Cell($wide_cell+$wide_cell_enhancer, 3, '', $borders, 0, 'C'); //отступ слева
    $pdf->Cell($wide_cell-$wide_cell_enhancer, 3, $person['fio_lat'], $borders, 2, $person['fio_align']); //ФИО лат


    $pdf->SetX($x);
    $pdf->Cell(0, 14, '', $borders, 2, 'R'); //отступ
    $pdf->Cell($wide_cell+$wide_cell_enhancer, 3, '', $borders, 0, 'C'); //отступ слева
    $pdf->Cell($wide_cell-$wide_cell_enhancer, 3, '«'.$person['uroven'].'»', $borders, 2, 'C'); //уровень тестирования



    $pdf->SetX($x);
    $pdf->Cell(0, 19.5, '', $borders, 2, 'R'); //отступ
    $pdf->Cell($wide_cell+$wide_cell_enhancer, 3, '', $borders, 0, 'C'); //отступ слева
    $pdf->Cell($wide_cell-$wide_cell_enhancer, 3, $person['org'], $borders, 2, 'C'); //Учреждение



    $pdf->SetX($x);
    $pdf->Cell(0, 23, '', $borders, 2, 'R'); //отступ
    $pdf->Cell($wide_cell, 3, $person['city'], $borders, 0, 'C'); //Город
    $pdf->Cell($wide_cell, 3, '', $borders, 2, 'C'); //отступ справа


    $pdf->SetFont('VERDANA1251_bold', '', 9);

    $pdf->SetX($x);
    $pdf->Cell(0, 13, '', $borders, 2, 'R'); //отступ
    $pdf->Cell(35, 3, $person['print_date'], $borders, 0, 'C'); //Дата печати
    $pdf->Cell(36, 3, '', $borders, 0, 'C'); //отступ
    $pdf->Cell(23, 3, $person['cert_date'], $borders, 0, 'R'); //срок сертификата
    $pdf->Cell(8, 3, '', $borders, 0, 'C'); //отступ
    $pdf->Cell(51, 3, $person['rukovod_dolzhn_1'].' '.$person['rukovod_dolzhn_2'], $borders, 0, 'C'); //должность
    $pdf->Cell(51, 3, $person['rukovod_fio'], $borders, 0, 'R'); //руководитель
    $pdf->Cell(6, 3, '', $borders, 2, 'R'); //отступ


}




//$pdf->Output("Сертификат".$blanks.".pdf", "D");
