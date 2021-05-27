<?php
header("Content-Type: text/html; charset=windows-1251");


$old_view = 0;   //� ����� ���� ���������� ������� ��������


$type = 1;
if (!empty($_GET['type']) && is_numeric($_GET['type'])

) {
    $type = $_GET['type'];
}
$sign = ActSigning::getByID($type);
if (!$sign) $sign = ActSigning::getByID(1);

$rukovod_dolzhn_1 = $sign->position;
$rukovod_fio = $sign->caption;


//���
$vuz_name = mb_strtoupper(TEXT_HEADCENTER_MIDDLE_IP, "cp1251");
$vuz_name = '���������� ����������� ������ �������';
//$vuz_name=TEXT_HEADCENTER_SHORT_IP;
//$lc_name=University::getByActID($Act->id);
//$lc_name='';
$gc_name = SIGNING_SHORT_CENTER_NAME;

$naim_document = '���������� � �������� ������� ������ ��� �����������, ������ ������� ������ � ����� ���������������� ���������� ���������';

$ved_vid_cert_num = $Act->ved_vid_cert_num;

$ved_vid_cert_num_date = date('d.m.Y', strtotime($Act->ved_vid_cert_num_date));
$new_ved_number = '<u>' . $ved_vid_cert_num . '</u>';
$new_ved_number2 = $ved_vid_cert_num;
//$new_ved_number='____';
//$new_ved_number2='';

//echo date()strtotime($Act->testing_date);

//����� ������� ���� :)
$date_day = date('d');
$date_month = get_month_name();
$date = '&laquo;' . $date_day . '&raquo; &nbsp;' . strtolower($date_month) . date(" Y");


$date_check_day = date('d', strtotime($Act->testing_date));
$date_check_month = date('m', strtotime($Act->testing_date));
$date_check_month = get_month_name($date_check_month);
$date_check_year = date('Y', strtotime($Act->testing_date));;

//$date1=date('m', strtotime($Act->check_date));
$date1 = date('m', strtotime($Act->testing_date));

$date_check_1 = '&laquo;<u>' . $date_check_day . '</u>&raquo;<u>' . mb_strtolower($date_check_month, 'cp1251') . ' ' . $date_check_year . '</u> ';
//$date_check_2='&laquo;'.$date_check_day.'&raquo;&nbsp;'.mb_strtolower($date_check_month,'cp1251').' '.$date_check_year;
$date_check_2 = date('d.m.Y', strtotime($Act->testing_date));
//$date_check_1='&laquo;__<span class="GramE">_&raquo;_</span>______________201_';
//$date_check_2='';

$test_session_id = $Act->id;
//$test_session_id='';

$responsible_fio = $Act->responsible;
$responsible_fio = '';


function get_month_name($input_date = 0)
{
    if (!empty($input_date) && $input_date > 0 && $input_date < 13) {
        $date_month = $input_date;
    } else {
        $date_month = date('m');
    }

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
    return $date_month;
}

$sign = ActSignings::get4VidachaCertFirst();

function num2str($num)
{
    $nul = '����';
    $ten = array(
        array('', '����', '���', '���', '������', '����', '�����', '����', '������', '������'),
        array('', '����', '���', '���', '������', '����', '�����', '����', '������', '������'),
    );
    $a20 = array('������', '�����������', '����������', '����������', '������������', '����������', '�����������', '����������', '������������', '������������');
    $tens = array(2 => '��������', '��������', '�����', '���������', '����������', '���������', '�����������', '���������');
    $hundred = array('', '���', '������', '������', '���������', '�������', '��������', '�������', '���������', '���������');
    $unit = array( // Units
        array('', '', '', 1),
        array('', '', '', 0),
        array('������', '������', '�����', 1),
        array('�������', '��������', '���������', 0),
        array('��������', '��������', '����������', 0),
    );
    //
    list($rub, $kop) = explode('.', sprintf("%015.2f", floatval($num)));
    $out = array();
    if (intval($rub) > 0) {
        foreach (str_split($rub, 3) as $uk => $v) { // by 3 symbols
            if (!intval($v)) continue;
            $uk = sizeof($unit) - $uk - 1; // unit key
            $gender = $unit[$uk][3];
            list($i1, $i2, $i3) = array_map('intval', str_split($v, 1));
            // mega-logic
            $out[] = $hundred[$i1]; # 1xx-9xx
            if ($i2 > 1) $out[] = $tens[$i2] . ' ' . $ten[$gender][$i3]; # 20-99
            else $out[] = $i2 > 0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9
            // units without rub & kop
            if ($uk > 1) $out[] = morph($v, $unit[$uk][0], $unit[$uk][1], $unit[$uk][2]);
        } //foreach
    } else $out[] = $nul;
    //$out[] = morph(intval($rub), $unit[1][0],$unit[1][1],$unit[1][2]); // rub
    //$out[] = $kop.' '.morph($kop,$unit[0][0],$unit[0][1],$unit[0][2]); // kop
//var_dump($out);
    return join(' ', $out);// trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));

}

/**
 * �������� ����������
 * @ author runcore
 */
function morph($n, $f1, $f2, $f5)
{
    $n = abs(intval($n)) % 100;
    if ($n > 10 && $n < 20) return $f5;
    $n = $n % 10;
    if ($n > 1 && $n < 5) return $f2;
    if ($n == 1) return $f1;
    return $f5;
}

?>
<html>
<head>
    <title><?php echo $Act->testing_date; ?><?php echo $Act->getUniversity()->getLegalInfo()['name']; ?><?php echo $Act->getUniversityDogovor(); ?></title>
    <script>window.print();</script>
    <style>
        #main_table td {
            padding: 0px 5.4pt !important;
            height: 0px !important;
        }

        #main_table tr {
            height: 0px !important;
        }

        /*���� ����� ������ ������� �� ������ � ������� ������*/

        .people_span {
            font-size: 10.0pt;
            line-height: 125%;
        }

        .th_span {
            font-size: 11.0pt;
            line-height: 125%;
        }


    </style>
</head>
<body>


<div style="wi dth:180mm;">
    <p>


        <div class="WordSection1">
            <table cellspacing="0" cellpadding="0" border="0" align="left" class="MsoTableGrid" style="border-collapse:collapse;border:none;mso-yfti-tbllook:1184;mso-table-lspace:
    9.0pt;margin-left:6.75pt;mso-table-rspace:9.0pt;margin-right:6.75pt;
    mso-table-anchor-vertical:margin;mso-table-anchor-horizontal:margin;
    mso-table-left:left;mso-table-top:36.75pt;mso-padding-alt:0cm 5.4pt 0cm 5.4pt;
    mso-border-insideh:none;mso-border-insidev:none">
                <tbody>
                <tr style="mso-yfti-irow:0;mso-yfti-firstrow:yes">
                    <td width="281" valign="top" colspan="3" style="width:210.95pt;padding:0cm 5.4pt 0cm 5.4pt">
    <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><b style="mso-bidi-font-weight:normal"><span style="font-size:8.0pt;
            line-height:125%;color:black;letter-spacing:.05pt">���������</span></b></p>
    </td>
    <td width="123" valign="top" style="width:92.15pt;padding:0cm 5.4pt 0cm 5.4pt">
        <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">&nbsp;</span></p>
    </td>
    <td width="402" valign="top" colspan="5" rowspan="8" style="width:301.7pt;padding:
            0cm 5.4pt 0cm 5.4pt">
        <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><b style="mso-bidi-font-weight:normal"><span style="font-size:12.0pt;
            line-height:125%;color:black;letter-spacing:.05pt">&nbsp;</span></b></p>
        <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><b style="mso-bidi-font-weight:normal"><span style="font-size:12.0pt;
            line-height:125%;color:black;letter-spacing:.05pt">��������� � <?= $new_ved_number ?> �   ��������������� ������ �� <br><?= $date_check_1 ?>
                    �.</span></b></p>
        <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><b style="mso-bidi-font-weight:normal"><span style="font-size:12.0pt;
            line-height:125%;color:black;letter-spacing:.05pt">������ ������������ �   �������� ������� ������, ������</span></b><b
                    style="mso-bidi-font-weight:
            normal"><span style="font-size:12.0pt;line-height:125%"> ������� ������</span></b></p>
        <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><b style="mso-bidi-font-weight:normal"><span style="font-size:12.0pt;
            line-height:125%"><span style="mso-spacerun:yes">&nbsp;</span>� �����   ���������������� ���������� ���������</span></b>
        </p>
        <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">&nbsp;</span></p>
    </td>
    <td width="90" valign="top" style="width:67.25pt;border:none;border-right:solid windowtext 1.0pt;
            mso-border-right-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
        <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">&nbsp;</span></p>
    </td>
    <td width="90" valign="top" style="width:67.25pt;border:solid windowtext 1.0pt;
            border-left:none;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:
            solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
        <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">����</span></p>
    </td>
    </tr>
    <tr style="mso-yfti-irow:1">
        <td width="111" valign="top" style="width:83.4pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:7.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">�������� ��������� ������ ������������</span></p>
        </td>
        <td width="68" valign="top" style="width:50.85pt;border:none;border-bottom:solid windowtext 1.0pt;
            mso-border-bottom-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">&nbsp;</span></p>
        </td>
        <td width="102" valign="top" style="width:76.7pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">&nbsp;</span></p>
        </td>
        <td width="123" valign="bottom" style="width:92.15pt;border:none;border-bottom:solid windowtext 1.0pt;
            mso-border-bottom-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt"><?= $rukovod_fio ?></span></p>
        </td>
        <td width="90" valign="top" style="width:67.25pt;border:none;border-right:solid windowtext 1.0pt;
            mso-border-right-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:7.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">����� �� ����</span></p>
        </td>
        <td width="90" valign="top" style="width:67.25pt;border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
            mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
            mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">&nbsp;</span></p>
        </td>
    </tr>
    <tr style="mso-yfti-irow:2">
        <td width="111" valign="top" style="width:83.4pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">&nbsp;</span></p>
        </td>
        <td width="68" valign="top" style="width:50.85pt;border:none;mso-border-top-alt:
            solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:6.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">������� </span></p>
        </td>
        <td width="102" valign="top" style="width:76.7pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:6.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">&nbsp;</span></p>
        </td>
        <td width="123" valign="top" style="width:92.15pt;border:none;mso-border-top-alt:
            solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:6.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">����������� �������</span></p>
        </td>
        <td width="90" valign="top" style="width:67.25pt;border:none;border-right:solid windowtext 1.0pt;
            mso-border-right-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:7.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">����� ���������</span></p>
        </td>
        <td width="90" valign="top" style="width:67.25pt;border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
            mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
            mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt"><?= $new_ved_number2 ?></span></p>
        </td>
    </tr>
    <tr style="mso-yfti-irow:3">
        <td width="111" valign="top" style="width:83.4pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">&nbsp;</span></p>
        </td>
        <td width="68" valign="top" style="width:50.85pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">&laquo;<span
                            style="mso-spacerun:yes">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>&raquo;</span>
            </p>
        </td>
        <td width="102" valign="top" style="width:76.7pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">&nbsp;</span></p>
        </td>
        <td width="123" valign="top" style="width:92.15pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt"><?= date('Y', strtotime($Act->ved_vid_cert_num_date)); ?> �.</span></p>
        </td>
        <td width="90" valign="top" style="width:67.25pt;border:none;border-right:solid windowtext 1.0pt;
            mso-border-right-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:7.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">���� ��������� </span></p>
        </td>
        <td width="90" valign="top" style="width:67.25pt;border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
            mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
            mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt"><?= $ved_vid_cert_num_date ?></span></p>
        </td>
    </tr>
    <tr style="mso-yfti-irow:4;height:21.35pt">
        <td width="111" valign="top" style="width:83.4pt;padding:0cm 5.4pt 0cm 5.4pt;
            height:21.35pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:7.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">���������� </span></p>
        </td>
        <td width="293" valign="top" colspan="3" style="width:219.7pt;border:none;
            border-bottom:solid windowtext 1.0pt;mso-border-bottom-alt:solid windowtext .5pt;
            padding:0cm 5.4pt 0cm 5.4pt;height:21.35pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">&nbsp;</span></p>
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt"><?= $vuz_name ?> </span></p>
        </td>
        <td width="90" valign="top" style="width:67.25pt;padding:0cm 5.4pt 0cm 5.4pt;
            height:21.35pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">&nbsp;</span></p>
        </td>
        <td width="90" valign="top" style="width:67.25pt;border:none;border-bottom:solid windowtext 1.0pt;
            mso-border-top-alt:solid windowtext .5pt;mso-border-top-alt:solid windowtext .5pt;
            mso-border-bottom-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;
            height:21.35pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">&nbsp;</span></p>
        </td>
    </tr>
    <tr style="mso-yfti-irow:5">
        <td width="111" valign="top" style="width:83.4pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:7.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">����������� ������������� </span></p>
        </td>
        <td width="293" valign="bottom" colspan="3" style="width:219.7pt;border:none;
            border-bottom:solid windowtext 1.0pt;mso-border-bottom-alt:solid windowtext .5pt;
            padding:0cm 5.4pt 0cm 5.4pt;height:21.35pt">
            <!--   <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
           mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
           mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
           exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
           letter-spacing:.05pt">&nbsp;</span></p>-->
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt"><?= $gc_name ?> </span></p>
        </td>
        <td width="90" valign="top" style="width:67.25pt;border:none;border-right:solid windowtext 1.0pt;
            mso-border-right-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">�� ����</span></p>
        </td>
        <td width="90" valign="top" style="width:67.25pt;border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
            mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
            mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">02066463</span></p>
        </td>
    </tr>
    <tr style="mso-yfti-irow:6">
        <td width="111" valign="top" style="width:83.4pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:7.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">������������ ��������� �� ����������� </span></p>
        </td>
        <td width="293" valign="bottom" colspan="3" style="width:219.7pt;border:none;
            border-bottom:solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;
            mso-border-top-alt:solid windowtext .5pt;mso-border-bottom-alt:solid windowtext .5pt;
            padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt"><?= $naim_document ?></span></p>
        </td>
        <td width="90" valign="top" style="width:67.25pt;border:none;border-right:solid windowtext 1.0pt;
            mso-border-right-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:7.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">���� �������� ������ </span></p>
        </td>
        <td width="90" valign="top" style="width:67.25pt;border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
            mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
            mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt"><?= $date_check_2 ?></span></p>
        </td>
    </tr>
    <tr style="mso-yfti-irow:7">
        <td width="111" valign="top" style="width:83.4pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">&nbsp;</span></p>
        </td>
        <td width="68" valign="top" style="width:50.85pt;border:none;mso-border-top-alt:
            solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">&nbsp;</span></p>
        </td>
        <td width="102" valign="top" style="width:76.7pt;border:none;border-top:solid windowtext 1.0pt;
            mso-border-top-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">&nbsp;</span></p>
        </td>
        <td width="123" valign="top" style="width:92.15pt;border:none;border-top:solid windowtext 1.0pt;
            mso-border-top-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">&nbsp;</span></p>
        </td>
        <td width="90" valign="top" style="width:67.25pt;border:none;border-right:solid windowtext 1.0pt;
            mso-border-right-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span lang="EN-US" style="font-size:7.0pt;line-height:125%;mso-fareast-font-family:
            &quot;Times New Roman&quot;;mso-fareast-theme-font:minor-fareast;color:black;
            letter-spacing:.05pt;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN">ID</span><span
                        style="font-size:7.0pt;line-height:125%;mso-fareast-font-family:&quot;Times New Roman&quot;;
            mso-fareast-theme-font:minor-fareast;color:black;letter-spacing:.05pt;
            mso-fareast-language:ZH-CN"> �������� ������ </span></p>
        </td>
        <td width="90" valign="top" style="width:67.25pt;border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
            mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
            mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt"><?= $test_session_id ?> (<?= $Act->number ?>)</span></p>
        </td>
    </tr>
    <tr style="mso-yfti-irow:8">
        <td width="111" valign="top" style="width:83.4pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">&nbsp;</span></p>
        </td>
        <td width="68" valign="top" style="width:50.85pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">&nbsp;</span></p>
        </td>
        <td width="102" valign="top" style="width:76.7pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">&nbsp;</span></p>
        </td>
        <td width="123" valign="top" style="width:92.15pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">&nbsp;</span></p>
        </td>
        <td width="44" valign="top" style="width:32.7pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">&nbsp;</span></p>
        </td>
        <td width="90" valign="top" style="width:67.25pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">&nbsp;</span></p>
        </td>
        <td width="90" valign="top" style="width:67.25pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">&nbsp;</span></p>
        </td>
        <td width="179" valign="top" colspan="2" style="width:134.5pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">��������� ��������</span></p>
        </td>
        <td width="90" valign="top" style="width:67.25pt;border:none;border-bottom:solid windowtext 1.0pt;
            mso-border-bottom-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span lang="EN-US" style="font-size:7.0pt;line-height:125%;mso-fareast-font-family:
            &quot;Times New Roman&quot;;mso-fareast-theme-font:minor-fareast;color:black;
            letter-spacing:.05pt;mso-ansi-language:EN-US;mso-fareast-language:ZH-CN">&nbsp;</span></p>
        </td>
        <td width="90" valign="top" style="width:67.25pt;border:none;border-bottom:solid windowtext 1.0pt;
            mso-border-top-alt:solid windowtext .5pt;mso-border-top-alt:solid windowtext .5pt;
            mso-border-bottom-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">&nbsp;</span></p>
        </td>
    </tr>
    <tr style="mso-yfti-irow:9;mso-yfti-lastrow:yes">
        <td width="111" valign="top" style="width:83.4pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">&nbsp;</span></p>
        </td>
        <td width="68" valign="top" style="width:50.85pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">&nbsp;</span></p>
        </td>
        <td width="102" valign="top" style="width:76.7pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">&nbsp;</span></p>
        </td>
        <td width="123" valign="top" style="width:92.15pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">&nbsp;</span></p>
        </td>
        <td width="44" valign="top" style="width:32.7pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">&nbsp;</span></p>
        </td>
        <td width="90" valign="top" style="width:67.25pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">&nbsp;</span></p>
        </td>
        <td width="90" valign="top" style="width:67.25pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">&nbsp;</span></p>
        </td>
        <td width="90" valign="top" style="width:67.25pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">&nbsp;</span></p>
        </td>
        <td width="90" valign="top" style="width:67.25pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">&nbsp;</span></p>
        </td>
        <td width="90" valign="top" style="width:67.25pt;border:none;mso-border-top-alt:
            solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:7.0pt;line-height:125%;mso-fareast-font-family:
            &quot;Times New Roman&quot;;mso-fareast-theme-font:minor-fareast;color:black;
            letter-spacing:.05pt;mso-fareast-language:ZH-CN">������� <span class="SpellE">�������</span></span></p>
        </td>
        <td width="90" valign="top" style="width:67.25pt;border:none;mso-border-top-alt:
            solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center;tab-stops:261.0pt right 513.0pt;
            mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;
            mso-element-anchor-horizontal:margin;mso-element-top:36.75pt;mso-height-rule:
            exactly"><span style="font-size:8.0pt;line-height:125%;color:black;
            letter-spacing:.05pt">�������</span></p>
        </td>
    </tr>
    <tr height="0">
        <td width="89" style="border:none">&nbsp;</td>
        <td width="52" style="border:none">&nbsp;</td>
        <td width="49" style="border:none">&nbsp;</td>
        <td width="85" style="border:none">&nbsp;</td>
        <td width="42" style="border:none">&nbsp;</td>
        <td width="67" style="border:none">&nbsp;</td>
        <td width="63" style="border:none">&nbsp;</td>
        <td width="60" style="border:none">&nbsp;</td>
        <td width="58" style="border:none">&nbsp;</td>
        <td width="69" style="border:none">&nbsp;</td>
        <td width="73" style="border:none">&nbsp;</td>
    </tr>
    </tbody>
    </table>
    <p class="MsoNormal">&nbsp;</p>
    <div align="center">
        <table cellspacing="0" cellpadding="0" border="1" class="MsoTableGrid" style="border-collapse:collapse;border:none;mso-border-alt:solid windowtext .5pt;
    mso-yfti-tbllook:1184;mso-padding-alt:0cm 5.4pt 0cm 5.4pt">
            <tbody>
            <tr style="mso-yfti-irow:0;mso-yfti-firstrow:yes">
                <td width="60" valign="top" style="width:45.15pt;border:solid windowtext 1.0pt;
            mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
                    <p align="center" class="MsoNormal" style="text-align:center"><span class="th_span">� <span
                                    class="SpellE">��</span></span></p>
                </td>
                <td width="167" valign="top" style="width:125.4pt;border:solid windowtext 1.0pt;
            border-left:none;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:
            solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
                    <p align="center" class="MsoNormal" style="text-align:center"><span class="th_span">�.�.�.</span>
                    </p>
                </td>
                <td width="89" valign="top" style="width:67.0pt;border:solid windowtext 1.0pt;
            border-left:none;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:
            solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
                    <p align="center" class="MsoNormal" style="text-align:center"><span
                                class="th_span">� �����������</span></p>
                </td>
                <td width="86" valign="top" style="width:64.35pt;border:solid windowtext 1.0pt;
            border-left:none;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:
            solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
                    <p align="center" class="MsoNormal" style="text-align:center"><span class="th_span">���. � �����������</span>
                    </p>
                </td>
                <td width="86" valign="top" style="width:64.35pt;border:solid windowtext 1.0pt;
            border-left:none;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:
            solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
                    <p align="center" class="MsoNormal" style="text-align:center"><span class="th_span">���� ������
                <br>�����������</span></p>
                </td>
                <td width="74" valign="top" style="width:55.4pt;border:solid windowtext 1.0pt;
            border-left:none;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:
            solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
                    <p align="center" class="MsoNormal" style="text-align:center"><span class="th_span">������� ������������ ��������</span>
                    </p>
                </td>
                <td width="91" valign="top" style="width:68.55pt;border:solid windowtext 1.0pt;
            border-left:none;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:
            solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
                    <p align="center" class="MsoNormal" style="text-align:center"><span class="th_span">��������</span>
                    </p>
                </td>
                <td width="86" valign="top" style="width:64.35pt;border:solid windowtext 1.0pt;
            border-left:none;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:
            solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
                    <p align="center" class="MsoNormal" style="text-align:center"><span class="th_span">���� ���������
            <br>
                �����������
            <br>
                ��   ����</span></p>
                </td>
                <td width="90" valign="top" style="width:67.5pt;border:solid windowtext 1.0pt;
            border-left:none;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:
            solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
                    <p align="center" class="MsoNormal" style="text-align:center"><span class="th_span">������� ����,   ����������� ����������</span>
                    </p>
                </td>
                <td width="137" valign="top" style="width:102.75pt;border:solid windowtext 1.0pt;
            border-left:none;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:
            solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
                    <p align="center" class="MsoNormal" style="text-align:center"><span
                                class="th_span">����������</span></p>
                </td>
            </tr>


            <?php
            $i = 0;
            foreach ($People as $Man):
                if ($Man->document != 'certificate') continue;
                ?>
                <?php

                $Man = CertificateDuplicate::checkForDuplicates($Man);


                $date_cert_print = date('d.m.Y', strtotime($Man->blank_date));

                if (empty($date_cert_print) || $date_cert_print == '01.01.1970') $date_cert_print = '';

                echo '
<tr style="mso-yfti-irow:0;mso-yfti-firstrow:yes">
            <td width="60" valign="top" style="width:45.15pt;border:solid windowtext 1.0pt;
            mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center"><span class="people_span">' . (++$i) . '</span></p>
            </td>
            <td width="167" valign="top" style="width:125.4pt;border:solid windowtext 1.0pt;
            border-left:none;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:
            solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center"><span class="people_span">' . $Man->getSurname_rus() . ' ' . $Man->getName_rus() . '</span></p>
            </td>
            <td width="89" valign="top" style="width:67.0pt;border:solid windowtext 1.0pt;
            border-left:none;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:
            solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center"><span class="people_span">' . $Man->getBlank_number() . '</span></p>
            </td>
            <td width="86" valign="top" style="width:64.35pt;border:solid windowtext 1.0pt;
            border-left:none;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:
            solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center"><span class="people_span">' . $Man->document_nomer . '</span></p>
            </td>
            <td width="86" valign="top" style="width:64.35pt;border:solid windowtext 1.0pt;
            border-left:none;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:
            solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center"><span class="people_span">' . $date_cert_print . '</span></p>
            </td>
            <td width="74" valign="top" style="width:55.4pt;border:solid windowtext 1.0pt;
            border-left:none;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:
            solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center"><span class="people_span">' . TestLevel::getByID(ActTest::getByID($Man->test_id)->level_id)->print . '</span></p>
            </td>
            <td width="91" valign="top" style="width:68.55pt;border:solid windowtext 1.0pt;
            border-left:none;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:
            solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center"><span class="people_span">' ./*$Man->passport_name*/
                    $Man->passport_series . ' �' . $Man->passport . '</span></p>
            </td>
            <td width="86" valign="top" style="width:64.35pt;border:solid windowtext 1.0pt;
            border-left:none;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:
            solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center"></p>
            </td>
            <td width="90" valign="top" style="width:67.5pt;border:solid windowtext 1.0pt;
            border-left:none;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:
            solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center"></p>
            </td>
            <td width="137" valign="top" style="width:102.75pt;border:solid windowtext 1.0pt;
            border-left:none;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:
            solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
            <p align="center" class="MsoNormal" style="text-align:center"></p>
            </td>
        </tr>';
            endforeach;
            ?>


            <tr style="mso-yfti-irow:6">
                <td width="317" valign="top" colspan="3" style="width:237.55pt;border:none;
            mso-border-top-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
                    <p align="right" class="MsoNormal" style="text-align:right"><span
                                style="font-size:8.0pt;line-height:125%">&nbsp;</span></p>
                </td>
                <td width="86" valign="top" style="width:64.35pt;border:none;border-bottom:solid windowtext 1.0pt;
            mso-border-top-alt:solid windowtext .5pt;mso-border-top-alt:solid windowtext .5pt;
            mso-border-bottom-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
                    <p class="MsoNormal"><span style="font-size:8.0pt;line-height:125%">&nbsp;</span></p>
                </td>
                <td width="86" valign="top" style="width:64.35pt;border:none;mso-border-top-alt:
            solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
                    <p class="MsoNormal"><span style="font-size:8.0pt;line-height:125%"><span style="mso-tab-count:1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span>
                    </p>
                </td>
                <td width="74" valign="top" style="width:55.4pt;border:none;mso-border-top-alt:
            solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
                    <p class="MsoNormal"><span style="font-size:8.0pt;line-height:125%">&nbsp;</span></p>
                </td>
                <td width="91" valign="top" style="width:68.55pt;border:none;mso-border-top-alt:
            solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
                    <p class="MsoNormal"><span style="font-size:8.0pt;line-height:125%">&nbsp;</span></p>
                </td>
                <td width="86" valign="top" style="width:64.35pt;border:none;mso-border-top-alt:
            solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
                    <p class="MsoNormal"><span style="font-size:8.0pt;line-height:125%">&nbsp;</span></p>
                </td>
                <td width="90" valign="top" style="width:67.5pt;border:none;mso-border-top-alt:
            solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
                    <p class="MsoNormal"><span style="font-size:8.0pt;line-height:125%">&nbsp;</span></p>
                </td>
                <td width="137" valign="top" style="width:102.75pt;border:none;mso-border-top-alt:
            solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
                    <p class="MsoNormal"><span style="font-size:8.0pt;line-height:125%">&nbsp;</span></p>
                </td>
            </tr>
            <tr style="mso-yfti-irow:7">
                <td width="317" valign="top" colspan="3" style="width:237.55pt;border:none;
            border-right:solid windowtext 1.0pt;mso-border-right-alt:solid windowtext .5pt;
            padding:0cm 5.4pt 0cm 5.4pt">
                    <p align="right" class="MsoNormal" style="text-align:right"><span
                                style="font-size:8.0pt;line-height:125%">�� ��������� ��������� ������   ����������</span>
                    </p>
                </td>
                <td width="86" valign="top" style="width:64.35pt;border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
            mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
            mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
                    <p class="MsoNormal"><span style="font-size:8.0pt;line-height:125%">&nbsp;</span></p>
                </td>
                <td width="86" valign="top" style="width:64.35pt;border:none;mso-border-left-alt:
            solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
                    <p class="MsoNormal"><span style="font-size:8.0pt;line-height:125%">&nbsp;</span></p>
                </td>
                <td width="74" valign="top" style="width:55.4pt;border:none;padding:0cm 5.4pt 0cm 5.4pt">
                    <p class="MsoNormal"><span style="font-size:8.0pt;line-height:125%">&nbsp;</span></p>
                </td>
                <td width="91" valign="top" style="width:68.55pt;border:none;padding:0cm 5.4pt 0cm 5.4pt">
                    <p class="MsoNormal"><span style="font-size:8.0pt;line-height:125%">&nbsp;</span></p>
                </td>
                <td width="86" valign="top" style="width:64.35pt;border:none;padding:0cm 5.4pt 0cm 5.4pt">
                    <p class="MsoNormal"><span style="font-size:8.0pt;line-height:125%">&nbsp;</span></p>
                </td>
                <td width="90" valign="top" style="width:67.5pt;border:none;padding:0cm 5.4pt 0cm 5.4pt">
                    <p class="MsoNormal"><span style="font-size:8.0pt;line-height:125%">&nbsp;</span></p>
                </td>
                <td width="137" valign="top" style="width:102.75pt;border:none;padding:0cm 5.4pt 0cm 5.4pt">
                    <p class="MsoNormal"><span style="font-size:8.0pt;line-height:125%">&nbsp;</span></p>
                </td>
            </tr>
            <tr style="mso-yfti-irow:8">
                <td width="317" valign="top" colspan="3" style="width:237.55pt;border:none;
            border-right:solid windowtext 1.0pt;mso-border-right-alt:solid windowtext .5pt;
            padding:0cm 5.4pt 0cm 5.4pt">
                    <p align="right" class="MsoNormal" style="text-align:right"><span
                                style="font-size:8.0pt;line-height:125%">�� ������</span></p>
                </td>
                <td width="86" valign="top" style="width:64.35pt;border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
            mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
            mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
                    <p class="MsoNormal"><span style="font-size:8.0pt;line-height:125%">&nbsp;</span></p>
                </td>
                <td width="86" valign="top" style="width:64.35pt;border:none;mso-border-left-alt:
            solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
                    <p class="MsoNormal"><span style="font-size:8.0pt;line-height:125%">&nbsp;</span></p>
                </td>
                <td width="74" valign="top" style="width:55.4pt;border:none;padding:0cm 5.4pt 0cm 5.4pt">
                    <p class="MsoNormal"><span style="font-size:8.0pt;line-height:125%">&nbsp;</span></p>
                </td>
                <td width="91" valign="top" style="width:68.55pt;border:none;padding:0cm 5.4pt 0cm 5.4pt">
                    <p class="MsoNormal"><span style="font-size:8.0pt;line-height:125%">&nbsp;</span></p>
                </td>
                <td width="86" valign="top" style="width:64.35pt;border:none;padding:0cm 5.4pt 0cm 5.4pt">
                    <p class="MsoNormal"><span style="font-size:8.0pt;line-height:125%">&nbsp;</span></p>
                </td>
                <td width="90" valign="top" style="width:67.5pt;border:none;padding:0cm 5.4pt 0cm 5.4pt">
                    <p class="MsoNormal"><span style="font-size:8.0pt;line-height:125%">&nbsp;</span></p>
                </td>
                <td width="137" valign="top" style="width:102.75pt;border:none;padding:0cm 5.4pt 0cm 5.4pt">
                    <p class="MsoNormal"><span style="font-size:8.0pt;line-height:125%">&nbsp;</span></p>
                </td>
            </tr>
            <tr style="mso-yfti-irow:9">
                <td width="317" valign="top" colspan="3" style="width:237.55pt;border:none;
            padding:0cm 5.4pt 0cm 5.4pt">
                    <p align="right" class="MsoNormal" style="text-align:right"><span
                                style="font-size:8.0pt;line-height:125%">&nbsp;</span></p>
                </td>
                <td width="245" valign="top" colspan="3" rowspan="2" style="width:184.1pt;border:
            none;padding:0cm 5.4pt 0cm 5.4pt">
                    <p align="right" class="MsoNormal" style="text-align:right"><span
                                style="font-size:8.0pt;line-height:125%">������ �������� ������������� �� <span
                                    class="SpellE">����������</span><br> ������������ � ���</span></p>
                </td>
                <td width="177" valign="top" colspan="2" rowspan="2" style="width:132.9pt;border:
            none;border-bottom:solid windowtext 1.0pt;mso-border-bottom-alt:solid windowtext .5pt;
            padding:0cm 5.4pt 0cm 5.4pt">
                    <p class="MsoNormal" style="text-align:center"><span
                                style="font-size:8.0pt;line-height:125%">&nbsp;</span>
                        <nobr><?= $responsible_fio ?></nobr>
                    </p>
                </td>
                <td width="227" valign="top" colspan="2" rowspan="2" style="width:170.25pt;
            border:none;border-bottom:solid windowtext 1.0pt;mso-border-bottom-alt:solid windowtext .5pt;
            padding:0cm 5.4pt 0cm 5.4pt">
                    <p class="MsoNormal"><span style="font-size:8.0pt;line-height:125%">&nbsp;</span></p>
                </td>
            </tr>
            <tr style="mso-yfti-irow:10">
                <td width="317" valign="top" colspan="3" style="width:237.55pt;border:none;
            padding:0cm 5.4pt 0cm 5.4pt">
                    <p align="right" class="MsoNormal" style="text-align:right"><span
                                style="font-size:8.0pt;line-height:125%">&nbsp;</span></p>
                </td>
            </tr>
            <tr style="mso-yfti-irow:11;mso-yfti-lastrow:yes">
                <td width="317" valign="top" colspan="3" style="width:237.55pt;border:none;
            padding:0cm 5.4pt 0cm 5.4pt">
                    <p align="right" class="MsoNormal" style="text-align:right"><span
                                style="font-size:8.0pt;line-height:125%">&nbsp;</span></p>
                </td>
                <td width="245" valign="top" colspan="3" style="width:184.1pt;border:none;
            padding:0cm 5.4pt 0cm 5.4pt">
                    <p class="MsoNormal"><span style="font-size:8.0pt;line-height:125%">&nbsp;</span></p>
                </td>
                <td width="177" valign="top" colspan="2" style="width:132.9pt;border:none;
            mso-border-top-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
                    <p align="center" class="MsoNormal" style="text-align:center"><span
                                style="font-size:8.0pt;line-height:125%">�������, <span
                                    class="SpellE">�������</span></span></p>
                </td>
                <td width="227" valign="top" colspan="2" style="width:170.25pt;border:none;
            mso-border-top-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt">
                    <p align="center" class="MsoNormal" style="text-align:center"><span
                                style="font-size:8.0pt;line-height:125%">�������</span></p>
                </td>
            </tr>
            </tbody>
        </table>
    </div>


    </p></div>
</body>
</html>
