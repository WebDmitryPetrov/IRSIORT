<?php
header ("Content-Type: text/html; charset=windows-1251");
//������������ ����
$vuz_name=$Man->getTest()->getAct()->getUniversity()->name;
$vuz_form=$Man->getTest()->getAct()->getUniversity()->form;
//����� �����������
//$sert_ser1='18'; ����� �� ������������?!
$Level= $Man->getTest()->getLevel();
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
$peresdacha = 0;
$balli_total=sprintf('%01.1f',$Man->total_percent);

//�������:
$razdeli_peresdacha='';


$balli_total=str_replace('.',',',$balli_total);


?>
<html>
<script>window.print() ;</script>
<body>
	<div style="width:180mm">
<p>
<div align="center" style="margin-right:1.0cm;text-align:center"><b>&nbsp;</b></div>
<div align="center" style="margin-right:1.0cm;text-align:center"><b>������������ ����� � ������� ����������� ���������� ���������</b></div>
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
		
		</tr>

        <?
        $sub_tests=SubTests::getByLevel($Level);
        //die(var_dump($sub_tests));
        $subTestResults = $Man->getResults();
        $li=0;
        foreach($sub_tests as $sub_test)
        {
            $li++;
            //echo $sub_test->caption;
//die(var_dump(SubTestResults::getByMan($Man)->getByOrder($sub_test->order)->percent));

            $percent=$subTestResults->getByOrder($sub_test->order)->percent;

            if ($percent < $Level->percent_max) {
                $ocenka = '�������������������';
                $razdeli_peresdacha.= '&laquo;' . $sub_test->full_caption . '&raquo;, ';
                $peresdacha++;
            } else {
                $ocenka = '�����������������';
            }

            echo '<tr>
            <td width="26" valign="top" style="width:19.8pt;border:solid white 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
                <div>'.$li.'.</div>
            </td>
            <td width="185" valign="top" style="width:138.6pt;border:solid white 1.0pt;
            border-left:none;
            padding:0cm 5.4pt 0cm 5.4pt">
                <div>'.$sub_test->full_caption.'</div>
            </td>
            <td width="132" valign="top" style="width:99.0pt;border-top:none;border-left:
            none;border-bottom:solid white 1.0pt;border-right:solid white 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
                <div align="center" style="ma rgin-right:15.8pt;text-align:center"><b>'.str_replace('.',',',sprintf('%01.1f', $percent)).'&nbsp;</b></div>
            </td>
            <td width="295" valign="top" style="width:221.15pt;border:solid white 1.0pt;
            border-left:none;
            padding:0cm 5.4pt 0cm 5.4pt">
                <div style="margin-left:45.4pt"><b>'.$ocenka.'</b></div>
            </td>
        </tr>';




        }




        ?>


    </tbody>
</table>
</div>
<?
if ($razdeli_peresdacha != '') {
    $razdeli_peresdacha=substr($razdeli_peresdacha,0,-2).'.';
}
?>
<div align="center" style="margin-right:21.25pt;text-align:center">&nbsp;</div>
<div align="center" style="margin-left:9.0pt;text-align:center;
text-indent:9.0pt">��� ��������� ����������� ������ ������ ��������� ������������ �� ��������:</div>
<div align="center" style="margin-left:9.0pt;text-align:center;
text-indent:9.0pt"><?=$razdeli_peresdacha; ?></div>
<div style="margin-left:9.0pt;text-align:justify;text-indent:
9.0pt">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
<div style="margin-right:21.25pt"><b>&nbsp;</b></div>





<table style="width:100%" border=0>

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


</table>








<div>&nbsp;</div>
</p>
</div>