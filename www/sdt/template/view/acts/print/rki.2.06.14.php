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
$gct_full_name='�������� ����� ������������ �������<br>���������� ����� �� �������� �����';
$gct_short_name='������';
*/

$gct_full_name=$sign->name_of_center;
//$gct_short_name='������'; �� ������������


//������������ ����
/*
$vuz_name=$Man->getTest()->getAct()->getUniversity()->name; //���� ���, ����� ��������� ������� ���
$vuz_form=$Man->getTest()->getAct()->getUniversity()->form; //���� ���, ����� ��������� ������� ���
*/

$vuz_name=RKI_RUDN_NAME;
$vuz_form=RKI_RUDN_FORM;

//����� �����������
//$sert_ser1='18'; ����� �� ������������?!

//����� �����������
//$sert_num=$Man->document_nomer; //���� �� 10.12.13
$sert_num='� '.$Man->document_nomer;
//���� ������
$vidano=$Man->surname_rus.' '.$Man->name_rus;
$uroven=$Man->getTest()->getLevel()->print;
//������
$strana=$Man->getCountry()->name;
//������� (������� / ������� / ��������)
//$uroven='��������'; ����� �� ������������?!
//����� (�����, 1�, 2�...)
//$balli_total='78,0'; ����� �� ������������?!
$balli_total=sprintf('%01.1f',$Man->total_percent);
$balli1=sprintf('%01.1f',$Man->grammar_percent);
$balli2=sprintf('%01.1f',$Man->reading_percent);
$balli3=sprintf('%01.1f',$Man->writing_percent);
$balli4=sprintf('%01.1f',$Man->listening_percent);
$balli5=sprintf('%01.1f',$Man->speaking_percent);
//�������������� �������
/* $balli_total=(($balli1+$balli2+$balli3+$balli4+$balli5)/5);
$balli_total=str_replace('.',',',$balli_total);����� �� ������������?!*/ 
//�������:
$razdeli_peresdacha='';
// 		�������� ��������
$razd1='�������� �������� � �����������';
$razd2='��������� ���������� ������� ��� ������';
$razd3='�������� ���������� �����';
$razd4='��������� ���������� �������� ����';
$razd5='������ �������';
//		����� ������ � ��������
$peresdacha=0;
if ($balli1<65){$balli1_ocenka='�������������������'; $razdeli_peresdacha='&laquo;'.$razd1.'&raquo;, '; $peresdacha++;} else {$balli1_ocenka='�����������������';}
if ($balli2<65){$balli2_ocenka='�������������������'; $razdeli_peresdacha.='&laquo;'.$razd2.'&raquo;, '; $peresdacha++;} else {$balli2_ocenka='�����������������';}
if ($balli3<65){$balli3_ocenka='�������������������'; $razdeli_peresdacha.='&laquo;'.$razd3.'&raquo;, '; $peresdacha++;} else {$balli3_ocenka='�����������������';}
if ($balli4<65){$balli4_ocenka='�������������������'; $razdeli_peresdacha.='&laquo;'.$razd4.'&raquo;, '; $peresdacha++;} else {$balli4_ocenka='�����������������';}
if ($balli5<65){$balli5_ocenka='�������������������'; $razdeli_peresdacha.='&laquo;'.$razd5.'&raquo;, '; $peresdacha++;} else {$balli5_ocenka='�����������������';}
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
$peresdacha_text_1='����';
$peresdacha_text_2='';
$razdeli_peresdacha='';
}
else {
$peresdacha_text_1='';
$peresdacha_text_2=':';

}
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

$dateYear=date('Y');
?>
<html>
<script>window.print() ;</script>
<body>
	<div style="width:180mm">
<p>
<div align="center" style="margin-right:1.0cm;text-align:center"><b>&nbsp;</b></div>
<div align="center" style="margin-right:1.0cm;text-align:center"><b>������������ ����������� � ����� ���������� ���������</b></div>
<div align="center" style="margin-right:1.0cm;text-align:center"><b>��������������� ������� ������������ �������</b></div>
<div align="center" style="margin-right:1.0cm;text-align:center"><b>���������� ����� �� �������� �����</b></div>
<div align="center" style="margin-right:1.0cm;text-align:center"><span style="font-size:9.0pt"><b><?php echo $vuz_form;?></b></span></div>
<div align="center" style="margin-right:1.0cm;text-align:center;"><b><u><?=$vuz_name; ?></u></b></div>
<div style="margin-right:1.0cm">&nbsp;</div>
<div align="center" style="margin-right:1.0cm;text-align:center"><b><?php echo $gct_full_name;?></b></div>
<div style="margin-right:1.0cm">&nbsp;</div>
<div align="center" style="margin-right:1.0cm;text-align:center"><b>������� ���� ��� �����������</b></div>
<div align="center" style="margin-right:1.0cm;text-align:center"><b><span style="font-size:20.0pt;">�������</span></b></div>
<div align="center" style="margin-right:1.0cm;text-align:center"><b><u><span style="font-size:16.0pt;"><?=$sert_num; ?></span></u></b><b><u><span style="font-size:16.0pt;"></span></u></b></div>
<div align="center" style="margin-right:1.0cm;text-align:center">&nbsp;</div>
<div align="center" style="margin-right:1.0cm;text-align:center">������������, ���</div>
<div align="center" style="margin-right:1.0cm;text-align:center">&nbsp;</div>
<div align="center" style="margin-right:1.0cm;text-align:center"><b><font size="5"><u><?=$vidano; ?></u></font></b></div>
<div align="center" style="margin-right:1.0cm;text-align:center">(�.�.�.)</div>
<div align="center" style="margin-right:1.0cm;text-align:center"><b><font size="5"><u><?=$strana; ?></u></font></b></div>
<div align="center" style="margin-right:1.0cm;text-align:center">(������)</div>
<div align="center" style="margin-right:1.0cm;text-align:center">&nbsp;</div>
<div style="margin-left:36.0pt;text-align:justify;
text-indent:0cm;line-height:150%">�������� ������������ �� �������� ����� � ������ <b><?=$uroven; ?></b> ������ � ������� ��������� ���������� �� �������� �����:</div>
<div style="margin-right:1.0cm;text-align:justify">&nbsp;</div>

<div align="center">
<table border="1" cellspacing="0" cellpadding="0" style="border-collapse:collapse;border:none;">
    <tbody>
        <tr>
		<td width="211" colspan=2 valign="top" style="width:158.4pt;border:solid white 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div style="marg in-right:1.0cm;text-align:center"><b>������</b></div>
            </td>
            <td width="132" valign="top" style="width:115.0pt;border:solid white 1.0pt;
            border-left:none;
            padding:0cm 5.4pt 0cm 5.4pt">
            <div style="ma rgin-right:1.0cm;text-align:center"><b>�������<br>���������� �������</b></div>
            </td>
			<td width="132" valign="top" style="width:99.0pt;border:solid white 1.0pt;
            border-left:none;
            padding:0cm 5.4pt 0cm 5.4pt">
            <div style="ma rgin-right:1.0cm;text-align:center"><b>������<br>(�����������������,<br>�������������������)<br><br></b></div>
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
text-indent:9.0pt">��� ��������� ����������� ������ ������ ��������� ������������ �� <?=$peresdacha_text_1 ?> ��������<?=$peresdacha_text_2 ?></div>
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


<td align=right><b>&laquo;<?=$date_day?>&raquo; <?=$date_month?> <?=$dateYear?> �.</b></td>


</tr>


<tr style="height:35px"><td colspan=2></td></tr>

    <!--

    <tr>
    <td></td>
    <td><span style="padding-left:25px">�.�.</span></td>
    <td></td>
    <td><span style="padding-left:60px">�.�.</span></td>
    <td></td>
    </tr>


    -->


</table>







<!--<table style="width:100%" border=0>

<tr>

<td style="width:12%; padd ing-right:10px"> </td>
<td style="text-align:justify;width:33%; pa dding-left:10px">������������� �����������,<br>���������� ������������</td>
<td style="width:13%; pa dding-right:10px; paddi ng-left:10px"></td>
<td style="text-align:justify;width:35%; pa dding-right:10px">��������<br>������ <?=TEXT_HEADCENTER_SHORT_IP?></td>
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
<td>_____________________ <?php echo date('Y');?>�.</td>
<td></td>
<td>&laquo;___&raquo; ________________ <?php echo date('Y');?>�.</td>
<td></td>

</tr>


<tr style="height:35px"><td colspan=5></td></tr>

<tr>
<td></td>
<td><span style="padding-left:25px">�.�.</span></td>
<td></td>
<td><span style="padding-left:60px">�.�.</span></td>
<td></td>

</tr>


</table>-->


<div>&nbsp;</div>
</p>
</div>
</body>
</html>