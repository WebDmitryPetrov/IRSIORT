<?php
//������������ ����
$vuz_name=$Man->getTest()->getAct()->getUniversity()->name;
$vuz_form=$Man->getTest()->getAct()->getUniversity()->form;
//����� �����������
//$sert_ser1='18'; ����� �� ������������?!

//����� �����������
$sert_num=$Man->document_nomer;
//���� ������
$vidano=$Man->surname_rus.' '.$Man->name_rus;
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
if ($balli1<65){$balli1_ocenka='�������������������'; $razdeli_peresdacha='&laquo;'.$razd1.'&raquo;, ';} else {$balli1_ocenka='�����������������';}
if ($balli2<65){$balli2_ocenka='�������������������'; $razdeli_peresdacha.='&laquo;'.$razd2.'&raquo;, ';} else {$balli2_ocenka='�����������������';}
if ($balli3<65){$balli3_ocenka='�������������������'; $razdeli_peresdacha.='&laquo;'.$razd3.'&raquo;, ';} else {$balli3_ocenka='�����������������';}
if ($balli4<65){$balli4_ocenka='�������������������'; $razdeli_peresdacha.='&laquo;'.$razd4.'&raquo;, ';} else {$balli4_ocenka='�����������������';}
if ($balli5<65){$balli5_ocenka='�������������������'; $razdeli_peresdacha.='&laquo;'.$razd5.'&raquo;, ';} else {$balli5_ocenka='�����������������';}
if ($razdeli_peresdacha != '') {
$razdeli_peresdacha=substr($razdeli_peresdacha,0,-2).'.';
}

$balli_total=str_replace('.',',',$balli_total);
$balli1=str_replace('.',',',$balli1);
$balli2=str_replace('.',',',$balli2);
$balli3=str_replace('.',',',$balli3);
$balli4=str_replace('.',',',$balli4);
$balli5=str_replace('.',',',$balli5);



?>

	<div style="width:180mm">
<p>
<div align="center" style="margin-right:1.0cm;text-align:center"><b>&nbsp;</b></div>
<div align="center" style="margin-right:1.0cm;text-align:center"><b>������������ ����������� � ����� ���������� ���������</b></div>
<div align="center" style="margin-right:1.0cm;text-align:center"><b>��������������� ������� ������������ �������</b></div>
<div align="center" style="margin-right:1.0cm;text-align:center"><b>���������� ����� �� �������� �����</b></div>
<div align="center" style="margin-right:1.0cm;text-align:center"><span style="font-size:9.0pt"><?php echo $vuz_form; ?></span></div>
<div align="center" style="margin-right:1.0cm;text-align:center;"><b><u>&laquo;<?=$vuz_name; ?>&raquo;</u></b></div>
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
text-indent:0cm;line-height:150%">�������� ������������ �� �������� ����� � ������ <!--!-->��������<!--!--> ������ <!--�� ��������� ����������� ��-->� ������� ��������� ���������� �� �������� �����:</div>
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
text-indent:9.0pt">��� ��������� ����������� ������ ������ ��������� ������������ �� ��������:</div>
<div align="center" style="margin-left:9.0pt;text-align:center;
text-indent:9.0pt"><?=$razdeli_peresdacha; ?></div>
<div style="margin-left:9.0pt;text-align:justify;text-indent:
9.0pt">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
<div style="margin-right:21.25pt"><b>&nbsp;</b></div>
<div style="margin-left:1.0cm;text-align:justify">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span style="color:#080808">������������� �����������,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ��������</span></div>
<div style="margin-left:1.0cm;text-align:justify"><span style="color:#080808">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ���������� ������������&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ������ <?=TEXT_HEADCENTER_SHORT_IP?></span></div>
<div style="margin-left:1.0cm;text-align:justify">&nbsp;</div>
<div style="margin-left:1.0cm;text-align:justify"><span style="color:#080808">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ___________________________&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ___________________________</span></div>
<div style="margin-left:1.0cm;text-align:justify">&nbsp;</div>
<div style="margin-left:1.0cm;text-align:justify;text-indent:
36.0pt"><span style="color:#080808">_____________________ 201</span><span style="color:#080808;">2</span><span style="color:#080808">�.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &laquo;___&raquo; <i>________________</i> 201</span><span style="color:#080808;">2</span><span style="color:#080808">�.</span></div>
<div style="margin-left:1.0cm;text-align:justify;text-indent:
36.0pt">&nbsp;</div>
<table border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse;border:none;">
    <tbody>
        <tr>
            <td width="92" valign="top" style="width:68.65pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="margin-right:1.0cm;text-align:center">&nbsp;</div>
            </td>
            <td width="92" valign="top" style="width:68.7pt;padding:0cm 5.4pt 0cm 5.4pt;vertical-align:bottom;">
            <div align="center" style="text-align:center"><span style="color:#080808">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; �.�.</span></div>
            </td>
            <td width="92" valign="top" style="width:68.65pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="margin-right:1.0cm;text-align:center">&nbsp;</div>
            </td>
            <td width="92" valign="top" style="width:68.7pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="margin-right:1.0cm;text-align:center">&nbsp;</div>
            </td>
            <td width="92" valign="top" style="width:68.65pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div style="margin-right:1.0cm;text-align:justify">&nbsp;</div>
            </td>
            <td width="92" valign="top" style="width:68.7pt;padding:0cm 5.4pt 0cm 5.4pt; vertical-align:bottom;">
            <div align="center" style="text-align:center"><span style="color:#080808">&nbsp;&nbsp; �.�.</span></div>
            </td>
            <td width="92" valign="top" style="width:68.7pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div style="margin-right:1.0cm;text-align:justify">&nbsp;</div>
            </td>
            <td width="92" valign="top" style="width:68.7pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div style="margin-right:1.0cm;text-align:justify">&nbsp;</div>
            </td>
        </tr>
    </tbody>
</table>
<div>&nbsp;</div>
</p>
</div>