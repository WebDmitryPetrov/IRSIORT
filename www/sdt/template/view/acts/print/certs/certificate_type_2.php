<?php
header ("Content-Type: text/html; charset=windows-1251");
/** @var ActMan $Man */
$type = 1;
if (!empty($_GET['type']) &&is_numeric($_GET['type'])

) {
    $type = $_GET['type'];
}
//die('t2');
//��� �������
//$x=3; $y=9; //��� ������ �� ��������/����������� ��������
$x = 10.00125;
$y = 10.00125; //��� ������ �� ���������� ��������
$y = 8.5; //��� ������ �� ���������� ��������
$x = $y = 0; //!!
$wide_cell=105;
$small_cell=52.5;
//$textColour = array(255, 0, 0); //������ ���� ������
$textColour = array(0, 0, 0); //������ ���� ������
$borders = 0; //���������� �� ������� �����


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

$document_nomer = $Man->document_nomer; //����� ���������
$sign=ActSigning::getByID($type);
if(!$sign) $sign=ActSigning::getByID(1);

$rukovod_dolzhn_1 =  $sign->position;
$rukovod_fio = $sign->caption;

$rukovod_dolzhn_2 = OUR_SHORT_NAME; //��������� ������������ ������ 2


//$org = OUR_SHORT_NAME; // ����������� �������� ��������
$org = OUR_FULL_NAME; // ����������� ������� ��������
$org_first_line = ''; // ����������� ������� �������� 1� ������
$org_second_line = ''; // ����������� ������� �������� 2� ������



if (strlen($org) > 30) {
$org_first_line=$org;
$org_first_line=substr($org_first_line,0,30);

$org_first_line=substr($org_first_line,0,strrpos($org_first_line,' '));

$org_second_line=$org;
$org_second_line=substr($org_second_line,strlen($org_first_line)+1);

} else {
    $org_first_line=$org;
}




$fio_rus = $Man->surname_rus . ' ' . $Man->name_rus; //��� ���
$fio_lat = $Man->surname_lat . ' ' . $Man->name_lat; //��� ���
if (strlen($fio_rus) > 323) {
    $fontsize1 = 10;
} else {
    $fontsize1 = 14;
}

$country = $Man->getCountry()->name; //������
if (strlen($country) > 32) {
    $fontsize2 = 10;
} else {
    $fontsize2 = 14;
}

$uroven = $Man->getTest()->getLevel()->print; // �������
/*if ($Man->getTest()->level_id == 9) {
    $uroven = '�������';
}
*/


if (strlen($uroven) > 12) {
    $fontsize3 = 8;
} else {
    $fontsize3 = 14;
}

/*!!!!!!!!!!!*/


//$fio_rus = '��������� �������'; //��� ��� - �������
//$fio_lat = 'Iakhshibaeva mukadas'; //��� ��� - �������
//$uroven='������� ��� ����������� ����������';
//$rukovod_dolzhn_2 = '����';
//$city='������';
//$city=CERTIFICATE_CITY;
$city=strtoupper(CERTIFICATE_CITY);

//$print_date=date('d.m.Y');
//$cert_date=date('d.m').'.'.(date('Y')+5);

//$rukovod_dolzhn_1 =  '��������� asdasda asdasd';
//$rukovod_dolzhn_1 =  '���������';
//$rukovod_fio = '�.�. ���������';





/*!!!!!!!!!!!!!!*/



$fio_rus = strtoupper($fio_rus); //��� ��� - �������
$fio_lat = strtoupper($fio_lat); //��� ��� - �������
$uroven='�'.strtoupper($uroven).'�';
$rukovod_dolzhn_2 = strtoupper($rukovod_dolzhn_2);
//$city=????????????????????;
$city=strtoupper($city);

//$print_date=date('d.m.Y').' �.';
$print_date=date('d.m.Y',strtotime($Man->blank_date)).' �.';
//$cert_date=date('d.m').'.'.(date('Y')+5).' �.';
if ($Man->valid_till == null || $Man->valid_till == '0000-00-00') $cert_date = '���������';
else $cert_date=date('d.m.Y',strtotime($Man->valid_till)).' �.';;
//$cert_date=date('d.m').'.'.(date('Y')+5).' �.';

$rukovod_dolzhn_1 =  strtoupper($rukovod_dolzhn_1);
$rukovod_fio = strtoupper($rukovod_fio);



require_once($_SERVER['DOCUMENT_ROOT'] . "/fpdf/fpdf.php");


//$pdf = new FPDF('P', 'mm', 'A4');
$pdf = new FPDF('L', 'mm', array(210,158));


$pdf->SetTopMargin($y); //������ ������
$pdf->AddPage(); //��������� ��������
//$pdf->Image($_SERVER['DOCUMENT_ROOT']."/fpdf/background.jpg",0,0,210,297); //����������� �������� ����...��� �������� � ����������...����� ������������ ������!
$pdf->SetTextColor($textColour[0], $textColour[1], $textColour[2]);
//$asd=$pdf->GetY();
$pdf->SetX($x);

//$pdf->AddFont('VERDANA1251', '', '088769bf1635dbfeb3462a67efc657fc_verdana.php');
$pdf->AddFont('VERDANA1251', '', '1arial.php');
$pdf->AddFont('VERDANA1251_bold', '', 'arialbd.php');
$pdf->SetFont('VERDANA1251_bold', '', 11);
$pdf->SetX($x);
$pdf->Cell(0, 33, '', $borders, 2, 'R'); //������
$pdf->Cell($wide_cell, 3, '', $borders, 0, '�'); //������ �����
$pdf->Cell($wide_cell, 3, $fio_rus, $borders, 2, 'C'); //��� ���



//$pdf->Cell(0, 6, $document_nomer, $borders, 2, 'C'); //����� ��������� - !!


$pdf->SetX($x);
$pdf->Cell(0, 7.5, '', $borders, 2, 'R'); //������
$pdf->Cell($wide_cell, 3, '', $borders, 0, 'C'); //������ �����
$pdf->Cell($wide_cell, 3, $fio_lat, $borders, 2, 'C'); //��� ���


$pdf->SetX($x);
$pdf->Cell(0, 14, '', $borders, 2, 'R'); //������
$pdf->Cell($wide_cell, 3, '', $borders, 0, 'C'); //������ �����
$pdf->Cell($wide_cell, 3, $uroven, $borders, 2, 'C'); //������� ������������



$pdf->SetX($x);
$pdf->Cell(0, 19.5, '', $borders, 2, 'R'); //������
$pdf->Cell($wide_cell, 3, '', $borders, 0, 'C'); //������ �����
$pdf->Cell($wide_cell, 3, $org, $borders, 2, 'C'); //����������



$pdf->SetX($x);
$pdf->Cell(0, 23, '', $borders, 2, 'R'); //������
$pdf->Cell($wide_cell, 3, $city, $borders, 0, 'C'); //�����
$pdf->Cell($wide_cell, 3, '', $borders, 2, 'C'); //������ ������


$pdf->SetFont('VERDANA1251_bold', '', 9);

$pdf->SetX($x);
$pdf->Cell(0, 13, '', $borders, 2, 'R'); //������
$pdf->Cell(35, 3, $print_date, $borders, 0, 'R'); //���� ������
$pdf->Cell(36, 3, '', $borders, 0, 'C'); //������
$pdf->Cell(21, 3, $cert_date, $borders, 0, 'R'); //���� �����������
$pdf->Cell(10, 3, '', $borders, 0, 'C'); //������
$pdf->Cell(51, 3, $rukovod_dolzhn_1.' '.$rukovod_dolzhn_2, $borders, 0, 'C'); //���������
$pdf->Cell(51, 3, $rukovod_fio, $borders, 0, 'R'); //������������
$pdf->Cell(6, 3, '', $borders, 2, 'R'); //������




/*

$pdf->Cell(0, 2.5, '', $borders, 2, 'R'); //������
$pdf->SetFont('VERDANA1251', '', $fontsize1); // ��������� ������ ������
$pdf->Cell(0, 11.5, '', $borders, 2, 'R'); //������
$pdf->Cell(0, 6, $fio_rus, $borders, 2, 'C'); //���
$pdf->Cell(0, 1, '', $borders, 2, 'R'); //������

$pdf->SetX($x);
$pdf->SetFont('VERDANA1251', '', $fontsize2); // ��������� ������ ������
$pdf->Cell(0, 1.5, '', $borders, 2, 'R'); //������
$pdf->Cell(0, 6, $country, $borders, 2, 'C'); //������


$pdf->SetX($x);
$pdf->Cell(0, 2.5, '', $borders, 2, 'R'); //������
$pdf->Cell(115.5, 6.2, '', $borders, 0, 'R'); //������ �����
$pdf->SetFont('VERDANA1251', '', $fontsize3); //����� �� 8
$pdf->Cell(0, 5.7, $uroven, $borders, 1, 'L'); //�������
$pdf->Cell(0, 2.6, '', $borders, 2, 'R'); //������

$pdf->SetX($x);
//$pdf->Cell(0, 0.5, '', $borders, 2, 'R'); //������ �����
$pdf->SetFont('VERDANA1251', '', 14); //����� �� 12
$pdf->Cell(85, 6, '', $borders, 0, 'R'); //������ �����
$pdf->Cell(0, 6, $org_first_line, $borders, 1, 'L'); //������
$pdf->Cell(0, 6, $org_second_line, $borders, 2, 'C'); //������

$pdf->SetX($x);
$pdf->SetFont('VERDANA1251', '', 14); //����� �� 9
$pdf->Cell(0, 16.5, '', $borders, 1, 'R'); //������
$pdf->SetX($x);
$pdf->Cell(32, 6, '', $borders, 0, 'R'); //������ �����
$pdf->Cell(60, 6, $rukovod_dolzhn_1, $borders, 2, 'C'); //���������_1
$pdf->Cell(0, 2, '', $borders, 2, 'R'); //������
$pdf->Cell(60, 6, $rukovod_dolzhn_2, $borders, 2, 'C'); //���������_2

$pdf->SetX($x);
$pdf->SetFont('VERDANA1251', '', 14); //����� �� 12
$pdf->Cell(0, 10.8, '', $borders, 1, 'R'); //������
$pdf->SetX($x);
$pdf->Cell(100, 6, '', $borders, 0, 'R'); //������ �����
$pdf->Cell(60, 6, $rukovod_fio, $borders, 2, 'C'); //��� ������������

#$pdf->AddFont('VERDANA1251', '', 'arialbd.php');
$pdf->SetFont('VERDANA1251', '', 14); //����� �� 12
$pdf->SetX($x);
$pdf->Cell(0, 9, '', $borders, 1, 'R'); //������
$pdf->SetX($x);
$pdf->Cell(68, 6, '', $borders, 0, 'R'); //������ �����
$pdf->Cell(10, 6, $date_day, $borders, 0, 'C'); //��� ������������
$pdf->Cell(3, 6, '', $borders, 0, 'R'); //������ �����
$pdf->Cell(30, 6, $date_month, $borders, 0, 'C'); //��� ������������
$pdf->Cell(8, 6, '', $borders, 0, 'R'); //������ �����
$pdf->Cell(9, 6, date(" y"), $borders, 0, 'C'); //��� ������������
*/

$pdf->Output("report.pdf", "I");
