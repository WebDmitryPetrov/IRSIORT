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
$y = 10.00125; //��� ������ �� ���������� ��������
$y = 8.5; //��� ������ �� ���������� ��������
$x = $y = 0; //!!
$wide_cell=105;
$small_cell=52.5;
//$textColour = array(255, 0, 0); //������ ���� ������
$textColour = array(0, 0, 0); //������ ���� ������
$borders = 0; //���������� �� ������� �����
$wide_cell_enhancer=3; //�������� ������

$date_day = date('d');
$date_month = date('m');
$date_month = str_replace('01', '������', $date_month);
$date_month = str_replace('02', '�������', $date_month);
$date_month = str_replace('03', '�����', $date_month);
$date_month = str_replace('04', '������', $date_month);
$date_month = str_replace('05', '���', $date_month);
$date_month = str_replace('06', '����', $date_month);
$date_month = str_replace('07', '����', $date_month);
$date_month = str_replace('08', '�������', $date_month);
$date_month = str_replace('09', '��������', $date_month);
$date_month = str_replace('10', '�������', $date_month);
$date_month = str_replace('11', '������', $date_month);
$date_month = str_replace('12', '�������', $date_month);

//$document_nomer = $Man->document_nomer; //����� ���������
//$sign=ActSigning::getByID($type);
//if(!$sign) $sign=ActSigning::getByID(1);
//
//$rukovod_dolzhn_1 =  $sign->position;
//$rukovod_fio = $sign->caption;
//
//$rukovod_dolzhn_2 = OUR_SHORT_NAME; //��������� ������������ ������ 2
//
//
//$org = OUR_FULL_NAME; // ����������� ������� ��������
//$org_first_line = ''; // ����������� ������� �������� 1� ������
//$org_second_line = ''; // ����������� ������� �������� 2� ������
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
//$fio_rus = $Man->surname_rus . ' ' . $Man->name_rus; //��� ���
//$fio_lat = $Man->surname_lat . ' ' . $Man->name_lat; //��� ���
//if (strlen($fio_rus) > 323) {
//    $fontsize1 = 10;
//} else {
//    $fontsize1 = 14;
//}
//
//$country = $Man->getCountry()->name; //������
//if (strlen($country) > 32) {
//    $fontsize2 = 10;
//} else {
//    $fontsize2 = 14;
//}
//
//$uroven = $Man->getTest()->getLevel()->print; // �������
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
//$fio_rus = strtoupper($fio_rus); //��� ��� - �������
//$fio_lat = strtoupper($fio_lat); //��� ��� - �������
//$uroven='�'.strtoupper($uroven).'�';
//$rukovod_dolzhn_2 = strtoupper($rukovod_dolzhn_2);
//
//$city=strtoupper($city);
//
//
//$print_date=date('d.m.Y',strtotime($Man->blank_date)).' �.';
//
//if ($Man->valid_till == null || $Man->valid_till == '0000-00-00') $cert_date = '���������';
//else $cert_date=date('d.m.Y',strtotime($Man->valid_till)).' �.';;
//
//
//$rukovod_dolzhn_1 =  strtoupper($rukovod_dolzhn_1);
//$rukovod_fio = strtoupper($rukovod_fio);



require_once($_SERVER['DOCUMENT_ROOT'] . "/fpdf/fpdf.php");


$pdf = new FPDF('L', 'mm', array(210,158));


$pdf->SetTopMargin($y); //������ ������
foreach ($people as $person)
    {
    $pdf->AddPage(); //��������� ��������
//        $pdf->Image($_SERVER['DOCUMENT_ROOT'] . "/fpdf/certifikat_1_cut.jpg",0,0,210,158);
    $pdf->SetTextColor($textColour[0], $textColour[1], $textColour[2]);
    $pdf->SetX($x);

    $pdf->AddFont('VERDANA1251', '', '1arial.php');
    $pdf->AddFont('VERDANA1251_bold', '', 'arialbd.php');
    $pdf->SetFont('VERDANA1251_bold', '', 11*$person['font_resizer']);
    $pdf->SetX($x);
    $pdf->Cell(0, 33, '', $borders, 2, 'R'); //������
    $pdf->Cell($wide_cell+$wide_cell_enhancer, 3, '', $borders, 0, '�'); //������ �����
    $pdf->Cell($wide_cell-$wide_cell_enhancer, 3, $person['fio_rus'], $borders, 2, $person['fio_align']); //��� ���




    $pdf->SetX($x);
    $pdf->Cell(0, 7.5, '', $borders, 2, 'R'); //������
    $pdf->Cell($wide_cell+$wide_cell_enhancer, 3, '', $borders, 0, 'C'); //������ �����
    $pdf->Cell($wide_cell-$wide_cell_enhancer, 3, $person['fio_lat'], $borders, 2, $person['fio_align']); //��� ���


    $pdf->SetX($x);
    $pdf->Cell(0, 14, '', $borders, 2, 'R'); //������
    $pdf->Cell($wide_cell+$wide_cell_enhancer, 3, '', $borders, 0, 'C'); //������ �����
    $pdf->Cell($wide_cell-$wide_cell_enhancer, 3, '�'.$person['uroven'].'�', $borders, 2, 'C'); //������� ������������



    $pdf->SetX($x);
    $pdf->Cell(0, 19.5, '', $borders, 2, 'R'); //������
    $pdf->Cell($wide_cell+$wide_cell_enhancer, 3, '', $borders, 0, 'C'); //������ �����
    $pdf->Cell($wide_cell-$wide_cell_enhancer, 3, $person['org'], $borders, 2, 'C'); //����������



    $pdf->SetX($x);
    $pdf->Cell(0, 23, '', $borders, 2, 'R'); //������
    $pdf->Cell($wide_cell, 3, $person['city'], $borders, 0, 'C'); //�����
    $pdf->Cell($wide_cell, 3, '', $borders, 2, 'C'); //������ ������


    $pdf->SetFont('VERDANA1251_bold', '', 9);

    $pdf->SetX($x);
    $pdf->Cell(0, 13, '', $borders, 2, 'R'); //������
    $pdf->Cell(35, 3, $person['print_date'], $borders, 0, 'C'); //���� ������
    $pdf->Cell(36, 3, '', $borders, 0, 'C'); //������
    $pdf->Cell(23, 3, $person['cert_date'], $borders, 0, 'R'); //���� �����������
    $pdf->Cell(8, 3, '', $borders, 0, 'C'); //������
    $pdf->Cell(51, 3, $person['rukovod_dolzhn_1'].' '.$person['rukovod_dolzhn_2'], $borders, 0, 'C'); //���������
    $pdf->Cell(51, 3, $person['rukovod_fio'], $borders, 0, 'R'); //������������
    $pdf->Cell(6, 3, '', $borders, 2, 'R'); //������


}




//$pdf->Output("����������".$blanks.".pdf", "D");
