<?php
header("Content-Type: text/html; charset=windows-1251");
//�������
//var_dump($People[0]->getTest()->getLevel()->print);
//$a=$Act->getPeople();
//$uroven= $a[0]->getTest()->getLevel()->print; // ��� � �����������
$uroven = $People[0]->getTest()->getLevel()->print; // ��� � �����������
//$uroven= $a[0]->getTest()->getLevel()->getPrintAct(); // ��� � ���������


//�������� �����������
//$org_name=$Act->getUniversity()->name; //������ ��������
//$org_name=$Act->getUniversity()->short_name; //�����������


/*if ($Act->getUniversity()->short_name != '') $org_name=$Act->getUniversity()->short_name;
else $org_name=$Act->getUniversity()->name; */
if ($Act->getUniversity()->getLegalInfo()['short_name'] != '') $org_name = $Act->getUniversity()->getLegalInfo()['short_name'];
else $org_name = $Act->getUniversity()->getLegalInfo()['name'];

//���������� ������������ � �������
$cert_counter = 0;
$sprav_counter = 0;
//				foreach($Act->getPeople() as $Man){
foreach ($People as $Man) {
    if ($Man->document == 'certificate') $cert_counter++;
    else if ($Man->document == 'note') $sprav_counter++;
}


$type = 1;
if (!empty($_GET['type']) && is_numeric($_GET['type'])

) {
    $type = $_GET['type'];
}
$sign = ActSigning::getByID($type);
if (!$sign) $sign = ActSigning::getByID(1);

$rukovod_dolzhn_1 = $sign->position;
$rukovod_fio = $sign->caption;
$rukovod_center_name = SIGNING_SHORT_CENTER_NAME;

//���
$vuz_name = TEXT_HEADCENTER_MIDDLE_IP;
//����� ������� ���� :)

$print_date = $Man->getAct()->getPrintDateAfterCheckDate();

//$date_day=date('d');
$date_day = date('d', strtotime($print_date));
//$date_month=date('m');
$date_month = date('m', strtotime($print_date));
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
//$date='&laquo;'.$date_day.'&raquo; &nbsp;'.strtolower($date_month).date(" Y");
$date = '&laquo;' . $date_day . '&raquo; &nbsp;' . strtolower($date_month) . date(" Y", strtotime($print_date));

//$sign=ActSignings::get4VidachaCertFirst();

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

    </style>
</head>
<body>


<div style="width:180mm;">
    <p>
    <div align="center" style="text-align:center;pad ding-right: 170px;"><span
                style="font-size:14.0pt;color:#161616;letter-spacing:0pt"><b>������ ������ ������������ ����������� ���������������� ������������ <?= $rukovod_center_name ?></b></span>
    </div>
    <!--<div align="right" style="text-align:right">&nbsp;</div>
<div align="right" style="text-align:right"><span style="font-size:
12.0pt;color:#161616;letter-spacing:0pt;font-weight:normal;padding-right: 60px;"><?= $sign->position ?> <?= TEXT_HEADCENTER_SHORT_IP ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?= $sign->caption ?></span></div>
<div align="right" style="text-align:right">&nbsp;</div>
<div align="right" style="text-align:right;padding-right: 80px;"><span style="font-size:12.0pt;color:#161616;letter-spacing:0pt;font-weight:normal;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="font-size:12.0pt;
color:#161616;letter-spacing:0pt;font-weight:normal;">&laquo;___&raquo; ________________20__ �.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;�.�.</span></div>
<div align="center" style="text-align:center">&nbsp;</div>
<!--<div align="right" style="text-align:right;padding-right: 210px;"><span style="font-size:10.0pt;color:#161616;letter-spacing:0pt;font-weight:normal;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span><span style="font-size:10.0pt;color:#161616;letter-spacing:0pt;
font-weight:normal;">�.�</span></div>-->
    <!--<div><span style="font-size:10.0pt;color:#161616;letter-spacing:
    0pt;font-weight:normal;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></div>-->
    <div align="left" style="text-align:left"><span style="font-size:12.0pt;color:#161616;letter-spacing:0pt"><b>�� ������: </b><i><?= $uroven ?></i></span>
    </div>
    <div align="left" style="text-align:left"><span style="font-size:12.0pt;color:#161616;letter-spacing:0pt"><b>�����������, ����������� ������������: </b><i><?= $org_name ?></i></span>
    </div>
    <div align="left" style="text-align:left"><span style="font-size:12.0pt;color:#161616;letter-spacing:0pt"><b>���-�� ������������: </b><i><?= $cert_counter ?></i></span>
    </div>
    <div align="left" style="text-align:left"><span style="font-size:12.0pt;color:#161616;letter-spacing:0pt"><b>���-�� �������: </b><i><?= $sprav_counter ?></i></span>
    </div>
    <!--<div align="center" style="text-align:center"><span style="font-size:12.0pt;color:#161616;letter-spacing:0pt"><b>������ �������&nbsp;�����������&nbsp;����������������&nbsp;������������</b></span></div>-->
    <!--<div>&nbsp;</div>
    <div>&nbsp;</div>-->
    <div>&nbsp;</div>
    <div align="left" style="text-align:left"><span style="font-size:12.0pt;color:#161616;letter-spacing:0pt"><b>���� �����������: <?= $date; ?>
                �.</b></span></div>
    <!--<div>
<table style="width:100%;border-collapse: collapse; border-spacing: 0px;"><tr><td>
<span style="font-size:12.0pt;color:#161616;letter-spacing:
0pt;font-weight:normal;padding-left:30px">���� �����������:&nbsp; <?= $date; ?> �.</span></td>
<td align=right>
<span style="font-size:12.0pt;color:#161616;letter-spacing:
0pt;font-weight:normal;float:right;padding-right:30px">�����������: &laquo;<b><?= $vuz_name; ?></b>&raquo;</span>
</td></tr></table>
</div>-->
    <!--<div><span style="font-size:12.0pt;color:#161616;letter-spacing:
0pt;font-weight:normal;">�����������:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="font-size:12.0pt;
color:#161616;letter-spacing:0pt">&laquo;<b><?= $vuz_name; ?></b>&raquo;</span></div>-->
    <div align="cen ter">
        <table width="100%" cellspacing="0" cellpadding="0" border="1" style="border-collapse:collapse;border:none;"
               id="main_table">
            <tbody>
            <tr style="height:63.45pt">
                <td width="34"
                    style="width:25.25pt;border:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:63.45pt">
                    <div align="center" style="text-align:center"><span
                                style="font-size:10.0pt;color:#161616;letter-spacing:0pt;font-weight:normal;">�</span>
                    </div>
                    <div align="center" style="text-align:center"><span
                                style="font-size:10.0pt;color:#161616;letter-spacing:0pt;font-weight:normal;">�/�</span>
                    </div>
                </td>
                <td width="250" style="width:250.0pt;border:solid windowtext 1.0pt;border-left:
            none;
            padding:0cm 5.4pt 0cm 5.4pt;height:63.45pt">
                    <div align="center" style="text-align:center"><span
                                style="font-size:10.0pt;color:#161616;letter-spacing:0pt;font-weight:normal;">�.�.�.</span>
                    </div>
                </td>
                <td width="85" style="width:63.75pt;border:solid windowtext 1.0pt;border-left:
            none;
            padding:0cm 5.4pt 0cm 5.4pt;height:63.45pt">
                    <div align="center" style="text-align:center"><span
                                style="font-size:10.0pt;color:#161616;letter-spacing:0pt;font-weight:normal;">�����, ����� �������� (���������, ���������������   ��������)</span>
                    </div>
                </td>
                <!--<td width="84" style="width:62.75pt;border:solid windowtext 1.0pt;border-left:
                none;
                padding:0cm 5.4pt 0cm 5.4pt;height:63.45pt">
                <div align="center" style="text-align:center"><span style="font-size:10.0pt;color:#161616;letter-spacing:0pt;font-weight:normal;">������</span></div>
                <div align="center" style="text-align:center">&nbsp;</div>
                </td>
                <td width="100" style="width:80.3pt;border:solid windowtext 1.0pt;border-left:
                none;
                padding:0cm 5.4pt 0cm 5.4pt;height:63.45pt">
                <div align="center" style="text-align:center"><span style="font-size:10.0pt;color:#161616;letter-spacing:0pt;font-weight:normal;">����� �����������</span></div>
                <!--<div align="center" style="text-align:center"><span style="font-size:10.0pt;color:#161616;letter-spacing:0pt;font-weight:normal;">�����,</span></div>
                <div align="center" style="text-align:center"><span style="font-size:10.0pt;color:#161616;letter-spacing:0pt;font-weight:normal;">������� �����������</span></div>-->
                <!--</td>-->
                <td width="100" style="width:80.3pt;border:solid windowtext 1.0pt;border-left:
            none;
            padding:0cm 5.4pt 0cm 5.4pt;height:63.45pt">
                    <div align="center" style="text-align:center"><span
                                style="font-size:10.0pt;color:#161616;letter-spacing:0pt;font-weight:normal;">����� ������</span>
                    </div>
                    <!--<div align="center" style="text-align:center"><span style="font-size:10.0pt;color:#161616;letter-spacing:0pt;font-weight:normal;">�����,</span></div>
                    <div align="center" style="text-align:center"><span style="font-size:10.0pt;color:#161616;letter-spacing:0pt;font-weight:normal;">������� �����������</span></div>-->
                </td>
                <td width="64" style="width:50.85pt;border:solid windowtext 1.0pt;border-left:
            none;
            padding:0cm 5.4pt 0cm 5.4pt;height:63.45pt">
                    <div align="center" style="text-align:center"><span
                                style="font-size:10.0pt;color:#161616;letter-spacing:0pt;font-weight:normal;">���� ������������</span>
                    </div>
                </td>
                <!-- <td width="68" style="width:50.8pt;border:solid windowtext 1.0pt;border-left:
                 none;
                 padding:0cm 5.4pt 0cm 5.4pt;height:63.45pt">
                 <div align="center" style="text-align:center"><span style="font-size:10.0pt;color:#161616;letter-spacing:0pt;font-weight:normal;">�����</span></div>
                 <div align="center" style="text-align:center"><span style="font-size:10.0pt;color:#161616;letter-spacing:0pt;font-weight:normal;">����</span></div>
                 </td>
                 <td width="121" style="width:90.8pt;border:solid windowtext 1.0pt;border-left:
                 none;
                 padding:0cm 5.4pt 0cm 5.4pt;height:63.45pt">
                 <div align="center" style="text-align:center"><span style="font-size:10.0pt;color:#161616;letter-spacing:0pt;font-weight:normal;">������� ����, ���������� ������������ (�����������   �������)</span></div>
                 </td>-->
            </tr>
            <!--������ ������� � ������-->

            <?php
            $i = 0;
            foreach ($People as $Man):
                if ($Man->document != 'certificate') continue;
                //var_dump($Man);echo '<br>';
                ?>
                <?php

                $Man = CertificateDuplicate::checkForDuplicates($Man);

                echo '<tr style="height:17.0pt">
            <td width="34" valign="top" style="width:25.25pt;border:solid windowtext 1.0pt;
            border-top:none;
            padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt">
            <div><span style="font-size:12.0pt;color:#161616;letter-spacing:
            0pt;font-weight:normal;">' . (++$i) . '.</span></div>
            </td>
            <td width="104" valign="top" style="width:78.0pt;border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt">
            <div><span style="font-size:12.0pt;color:#161616;letter-spacing:
            0pt;font-weight:normal;">' . $Man->getSurname_rus() . ' ' . $Man->getName_rus() . '</span></div>
            </td>
            <td width="85" valign="top" style="width:63.75pt;border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt">
            <div align="center" style="text-align:center"><span style="font-size:12.0pt;color:#161616;letter-spacing:0pt;font-weight:normal;">' . $Man->passport_series . ' �' . $Man->passport . '</span></div>
            </td>
            <!--<td width="84" valign="top" style="width:62.75pt;border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt">
            <div align="center" style="text-align:center"><span style="font-size:12.0pt;color:#161616;letter-spacing:0pt;font-weight:normal;">' . $Man->getCountry()->name . '</span></div>
            </td>
            <td width="100" valign="top" style="width:80.3pt;border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt">
            <div align="center" style="text-align:center"><span style="font-size:12.0pt;color:#161616;letter-spacing:0pt;font-weight:normal;">' . $Man->document_nomer . '</span></div>
            </td>-->
			<td width="100" valign="top" style="width:80.3pt;border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt">
            <div align="center" style="text-align:center"><span style="font-size:12.0pt;color:#161616;letter-spacing:0pt;font-weight:normal;">' . $Man->getBlank_number() . '</span></div>
            </td>
            <td width="64" valign="top" style="width:50.85pt;border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt">
            <div align="center" style="text-align:center"><span style="font-size:12.0pt;color:#161616;letter-spacing:0pt;font-weight:normal;">' . date('d.m.Y', strtotime($Man->testing_date)) . '</span></div>
            </td>
            <!--<td width="68" valign="top" style="width:50.8pt;border-top:none;border-left:none;
            border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt">
            <div align="center" style="text-align:center"><span style="font-size:12.0pt;color:#161616;letter-spacing:0pt;font-weight:normal;">' . str_replace('.', ',', sprintf('%01.1f', $Man->total_percent)) . '%</span></div>
            </td>
            <td width="121" valign="top" style="width:90.8pt;border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt">
            <div>&nbsp;</div>
            </td>-->
			</tr>';
            endforeach;
            ?>

            </tbody>
        </table>
    </div>
    <div>&nbsp;</div>
    <!--
<div align="center" style="text-align:center"><span style="font-size:12.0pt;color:#161616;letter-spacing:0pt;font-weight:normal;">�� ��������� ��������� ������ �������_______________________(���������� ��������)<!--<u><?php echo num2str($i); ?></u> ������� ������ ������������ - ��������-->
    <!--</span></div>-->

    <br>
    <table style="width:65%;" align="center">
        <tr>
            <!--<td align="right" style="text-align:right; width:800px;"><span style="font-size:
12.0pt;color:#161616;letter-spacing:0pt;font-weight:normal;">�������� ������ ������������ <?= TEXT_HEADCENTER_SHORT_IP ?></td>-->

            <td align="right" style="text-align:right; width:350px;"><span style="font-size:
12.0pt;color:#161616;letter-spacing:0pt;font-weight:normal;"><b><?= $rukovod_dolzhn_1 ?></b></td>
            <td style="width:250px;">&nbsp;
            </td>
            <!--<td align="left" style="text-align:left; "><span style="font-size:
            12.0pt;color:#161616;letter-spacing:0pt;font-weight:normal;">_____________________
            </td>-->

            <td align="left" style="text-align:left; "><span style="font-size:
12.0pt;color:#161616;letter-spacing:0pt;font-weight:normal;"><nobr><b><?= $rukovod_fio ?></b></nobr>
            </td>
        </tr>

        <!--<tr><td colspan=3>&nbsp;</td></tr>
        <tr>
        <td align="right" style="text-align:right; "><span style="font-size:
        12.0pt;color:#161616;letter-spacing:0pt;font-weight:normal;">������������� �� ���������� ������������ </td>
        <td width=550>&nbsp;
        </td>
        <!--19.03.13 <td align="left"  width=100  style="text-align:left"><span style="font-size:-->
        <!--<td align="left" style="text-align:left"><span style="font-size:
12.0pt;color:#161616;letter-spacing:0pt;font-weight:normal;"><nobr><?= $Act->responsible; ?></nobr>
</td>
</tr>-->
    </table>

    </p></div>
</body>
</html>
