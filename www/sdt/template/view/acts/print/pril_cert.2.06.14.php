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

$vuz_name=CERT_PRIL_RUDN_NAME;
$vuz_form=CERT_PRIL_RUDN_FORM;


//����� �����������
$sert_num='� '.$Man->document_nomer;
//���� ������
$vidano=$Man->surname_rus.' '.$Man->name_rus;
//������
$strana=$Man->getCountry()->name;
//������� (������� / ������� / ��������)
$uroven=$Man->getTest()->getLevel()->print;
//����� (�����, 1�, 2�...)
$balli_total=str_replace('.',',',sprintf('%01.1f',$Man->total_percent));
$balli1=str_replace('.',',',sprintf('%01.1f',$Man->grammar_percent));
$balli2=str_replace('.',',',sprintf('%01.1f',$Man->reading_percent));
$balli3=str_replace('.',',',sprintf('%01.1f',$Man->writing_percent));
$balli4=str_replace('.',',',sprintf('%01.1f',$Man->listening_percent));
$balli5=str_replace('.',',',sprintf('%01.1f',$Man->speaking_percent));
//�������������� �������
/*$balli_total=(($balli1+$balli2+$balli3+$balli4+$balli5)/5);
 $balli_total=str_replace('.',',',$balli_total);*/
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
<div style=" width: 180mm">
	<p>
	
	
	
	<div align="center" style="margin-right: 1.0cm; text-align: center">
		<b>&nbsp;</b>
	</div>
	<div align="center" style="margin-right: 1.0cm; text-align: center">
		<b><span style="font-size: 12.0pt;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				��������������� ������� ������������ �������</span> </b>
	</div>
	<div align="center" style="margin-right: 1.0cm; text-align: center">
		<b><span style="font-size: 12.0pt;">���������� ����� �� �������� �����</span>
		</b>
	</div>
	<div align="center" style="margin-right: 1.0cm; text-align: center">
		<span style="font-size: 9.0pt"><b><?php echo $vuz_form; ?></b></span>
	</div>
	<div align="center" style="margin-right: -7.1pt; text-align: center;">
		<b><span style="font-size: 12.0pt">&laquo;<?=$vuz_name; ?>&raquo;
			</span> <u></u> </b>
	</div>
	
<!--	<div align="center" style="margin-right: 1.0cm; text-align: center">
		<b>&nbsp;</b>
	</div>
	<div align="center" style="margin-right: 1.0cm; text-align: center">
		<b>&nbsp;</b>
	</div>
	<div align="center" style="margin-right: 1.0cm; text-align: center">
		<b>&nbsp;</b>
	</div>-->
	
	
	
	
	<div style="margin-right:1.0cm">&nbsp;</div>
<div align="center" style="margin-right:1.0cm;text-align:center"><b><?php echo $gct_full_name;?></b></div>
<div style="margin-right:1.0cm">&nbsp;</div>
	
	
	
	
	<div align="center" style="margin-right: 1.0cm; text-align: center">
		<b><span style="font-size: 16.0pt; color: #080808">������� ���� ���
				�����������</span> </b>
	</div>
	<div align="center" style="margin-right: 1.0cm; text-align: center">
		<b>&nbsp;</b>
	</div>
	<!--<div align="center" style="margin-right: 1.0cm; text-align: center;">
		<b>&nbsp;</b>
	</div>
	<div align="center" style="margin-right: 1.0cm; text-align: center">
		<b><span style="font-size: 16.0pt; color: #080808">���������� �
				����������� &nbsp; <?=$sert_num; ?>&nbsp;
		</span> </b>
	</div>	-->
	
	
	<div align="center" style="margin-right: 1.0cm; text-align: center">
		<b><span style="font-size: 16.0pt; color: #080808">���������� �
				�����������
		</span> </b>
	</div>
	<br>
	<div align="center" style="margin-right:1.0cm;text-align:center"><b><u><span style="font-size:16.0pt;"><?=$sert_num; ?></span></u></b><b><u><span style="font-size:16.0pt;"></span></u></b></div>
	
	
	<!--<div align="center" style="margin-right: 1.0cm; text-align: center">
		<b><span style="font-size: 16.0pt; color: #080808">&nbsp;</span> </b>
	</div>
	<div
		style="margin-right: 1.0cm; text-align: justify; text-indent: 2.0cm">
		<span style="font-size: 12.0pt; color: #080808">������<b> &nbsp;&nbsp;<?=$vidano; ?>
		</b>
		</span>
	</div>
	<div
		style="margin-right: 1.0cm; text-align: justify; text-indent: 36.0pt">&nbsp;</div>-->
		
		
		<div align="center" style="margin-right:1.0cm;text-align:center">&nbsp;</div>
<div align="center" style="margin-right:1.0cm;text-align:center">������</div>
<div align="center" style="margin-right:1.0cm;text-align:center">&nbsp;</div>
		
		<div align="center" style="margin-right:1.0cm;text-align:center"><b><font size="5"><u><?=$vidano; ?></u></font></b></div>
		<!--<div align="center" style="margin-right:1.0cm;text-align:center">&nbsp;</div>-->
<div align="center" style="margin-right:1.0cm;text-align:center">(�.�.�.)</div>
<div align="center" style="margin-right:1.0cm;text-align:center">&nbsp;</div>
<div align="center" style="margin-right:1.0cm;text-align:center"><b><font size="5"><u><?=$strana; ?></u></font></b></div>
<!--<div align="center" style="margin-right:1.0cm;text-align:center">&nbsp;</div>-->
<div align="center" style="margin-right:1.0cm;text-align:center">(������)</div>
<div align="center" style="margin-right:1.0cm;text-align:center">&nbsp;</div>
		
	<!--<div
		style="margin-right: 1.0cm; text-align: justify; text-indent: 2.0cm">
		<span style="font-size: 12.0pt; color: #080808">������<b>&nbsp;&nbsp;
				&nbsp;<?=$strana; ?>
		</b>
		</span>
	</div>
	<div style="margin-left: 2.0cm; text-indent: -2.0cm">
		<b>&nbsp;</b>
	</div>-->
	<div style="margin-left: 2.0cm; text-indent: -2.0cm">
		<b>&nbsp;</b>
	</div>
	<div
		style="margin-top: 0cm; margin-right: 1.0cm; margin-bottom: 0cm; margin-left: 2.0cm; margin-bottom: .0001pt">
		<span style="font-size: 12.0pt; color: #080808">�� �����������
			������������ � ������ ������ <b><?=$uroven; ?>
			</b></span>
	</div>
	<div align="center"
		style="margin-top: 0cm; margin-right: 1.0cm; margin-bottom: 0cm; margin-left: 2.0cm; margin-bottom: .0001pt; text-align: center; text-indent: -2.0cm">&nbsp;</div>
	<div
		style="margin-top: 0cm; margin-right: 1.0cm; margin-bottom: 0cm; margin-left: 2.0cm; margin-bottom: .0001pt; text-align: justify; text-indent: -2.0cm">&nbsp;</div>
	<div
		style="margin-top: 0cm; margin-right: 1.0cm; margin-bottom: 0cm; margin-left: 2.0cm; margin-bottom: .0001pt; text-align: justify; text-indent: -2.0cm">
		<span style="font-size: 12.0pt; color: #080808">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			����� ���� (� ���������)&nbsp;&nbsp;&nbsp;&nbsp; <b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$balli_total; ?>
		</b>
		</span>
	</div>
	<div
		style="margin-top: 0cm; margin-right: 1.0cm; margin-bottom: 0cm; margin-left: 2.0cm; margin-bottom: .0001pt; text-align: justify; text-indent: -2.0cm">&nbsp;</div>
	<div align="center"
		style="margin-top: 0cm; margin-right: 1.0cm; margin-bottom: 0cm; margin-left: 2.0cm; margin-bottom: .0001pt; text-align: center; text-indent: -2.0cm">
		<b><span style="font-size: 14.0pt; color: #080808">���������� ����� ��
				��������:</span> </b>
	</div>
	<div align="center"
		style="margin-top: 0cm; margin-right: 1.0cm; margin-bottom: 0cm; margin-left: 2.0cm; margin-bottom: .0001pt; text-align: center; text-indent: -2.0cm">&nbsp;</div>
	<div
		style="margin-top: 0cm; margin-right: 1.0cm; margin-bottom: 0cm; margin-left: 2.0cm; margin-bottom: .0001pt; text-align: justify; text-indent: -2.0cm">
		<b><span style="font-size: 12.0pt; color: #080808">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				&nbsp;&nbsp;&nbsp;&nbsp;
				������&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				&nbsp;&nbsp;&nbsp;�������
				���������� �������</span> </b>
	</div>
	<div
		style="margin-top: 0cm; margin-right: 1.0cm; margin-bottom: 0cm; margin-left: 2.0cm; margin-bottom: .0001pt; text-align: justify; text-indent: -2.0cm">&nbsp;</div>
	<table border="1" cellspacing="0" cellpadding="0"
		style="margin-left: 55.05pt; border-collapse: collapse; border: none;">
		<tbody>
			<tr>
				<td width="369" valign="top"
					style="width: 277.0pt; border: solid white 1.0pt; padding: 0cm 5.4pt 0cm 5.4pt">
					<div style="margin-left: 17.0pt; text-indent: -17.0pt;">
						<span style="font-size: 12.0pt; color: #080808">1.<span
							style="font: 7.0pt&amp;quot;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>
						</span><span style="font-size: 12.0pt; color: #080808">��������
							�������� � ����������� </span>
					</div>
				</td>
				<td width="219" valign="top"
					style="width: 164.0pt; border: solid white 1.0pt; border-left: none; padding: 0cm 5.4pt 0cm 5.4pt">
					<div align="center" style="text-align: center">
						<b><span style="font-size: 12.0pt"><?=$balli1; ?> </span> </b>
					</div>
				</td>
			</tr>
			<tr>
				<td width="369" valign="top"
					style="width: 277.0pt; border: solid white 1.0pt; border-top: none; padding: 0cm 5.4pt 0cm 5.4pt">
					<div style="margin-left: 17.0pt; text-indent: -17.0pt;">
						<span style="font-size: 12.0pt; color: #080808">2.<span
							style="font: 7.0pt&amp;quot;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>
						</span><span style="font-size: 12.0pt; color: #080808">���������
							���������� ������� ��� ������</span>
					</div>
				</td>
				<td width="219" valign="top"
					style="width: 164.0pt; border-top: none; border-left: none; border-bottom: solid white 1.0pt; border-right: solid white 1.0pt; padding: 0cm 5.4pt 0cm 5.4pt">
					<div align="center" style="text-align: center">
						<b><span style="font-size: 12.0pt"><?=$balli2; ?> </span> </b>
					</div>
				</td>
			</tr>
			<tr>
				<td width="369" valign="top"
					style="width: 277.0pt; border: solid white 1.0pt; border-top: none; padding: 0cm 5.4pt 0cm 5.4pt">
					<div style="margin-left: 17.0pt; text-indent: -17.0pt;">
						<span style="font-size: 12.0pt; color: #080808">3.<span
							style="font: 7.0pt&amp;quot;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>
						</span><span style="font-size: 12.0pt; color: #080808">��������
							���������� �����</span>
					</div>
				</td>
				<td width="219" valign="top"
					style="width: 164.0pt; border-top: none; border-left: none; border-bottom: solid white 1.0pt; border-right: solid white 1.0pt; padding: 0cm 5.4pt 0cm 5.4pt">
					<div align="center" style="text-align: center">
						<b><span style="font-size: 12.0pt"><?=$balli3; ?> </span> </b>
					</div>
				</td>
			</tr>
			<tr style="height: 3.5pt">
				<td width="369" valign="top"
					style="width: 277.0pt; border: solid white 1.0pt; border-top: none; padding: 0cm 5.4pt 0cm 5.4pt">
					<div style="margin-left: 17.0pt; text-indent: -17.0pt;">
						<span style="font-size: 12.0pt; color: #080808">4.<span
							style="font: 7.0pt&amp;quot;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>
						</span><span style="font-size: 12.0pt; color: #080808">���������
							���������� �������� ����</span>
					</div>
				</td>

				<td width="219" valign="top"
					style="width: 164.0pt; border-top: none; border-left: none; border-bottom: solid white 1.0pt; border-right: solid white 1.0pt; padding: 0cm 5.4pt 0cm 5.4pt; height: 3.5pt">
					<div align="center" style="text-align: center">
						<b><span style="font-size: 12.0pt"><?=$balli4; ?> </span> </b>
					</div>
				</td>
			</tr>
			<tr>
				<td width="369" valign="top"
					style="width: 277.0pt; border: solid white 1.0pt; border-top: none; padding: 0cm 5.4pt 0cm 5.4pt">
					<div style="margin-left: 17.0pt; text-indent: -17.0pt;">
						<span style="font-size: 12.0pt; color: #080808">5.<span
							style="font: 7.0pt&amp;quot;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>
						</span><span style="font-size: 12.0pt; color: #080808">������
							�������</span>
					</div>
				</td>
				<td width="219" valign="top"
					style="width: 164.0pt; border-top: none; border-left: none; border-bottom: solid white 1.0pt; border-right: solid white 1.0pt; padding: 0cm 5.4pt 0cm 5.4pt">
					<div align="center" style="text-align: center">
						<b><span style="font-size: 12.0pt"><?=$balli5; ?> </span> </b>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
	<div style="margin-right: 1.0cm; text-align: justify">&nbsp;</div>
	<div style="margin-right: 1.0cm; text-align: justify">&nbsp;</div>
	<div style="margin-right: 1.0cm; text-align: justify">&nbsp;</div>
	<!--<div style="margin-right: 1.0cm; text-align: justify">&nbsp;</div>
	<div style="margin-right: 1.0cm; text-align: justify">&nbsp;</div>-->
	
	
	
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

<!--<tr>
<td></td>
<td><span style="padding-left:25px">�.�.</span></td>
<td></td>
<td><span style="padding-left:60px">�.�.</span></td>
<td></td>

</tr>-->


</table>
	
	
	
	<!--<table style="width:100%" border=0>

<tr>

<td style="width:10%; padd ing-right:10px"> </td>
<td style="text-align:justify;width:35%; pa dding-left:10px">������������� �����������,<br>���������� ������������</td>
<td style="width:16%; pa dding-right:10px; paddi ng-left:10px"></td>
<td style="text-align:justify;width:35%; pa dding-right:10px">��������<br>������ ������������ <?=TEXT_HEADCENTER_SHORT_IP?></td>
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
<td>&laquo;___&raquo; ________________ <?php echo date('Y');?> �.</td>
<td></td>
<td>&laquo;___&raquo; ________________ <?php echo date('Y');?> �.</td>
<td></td>

</tr>


<tr style="height:35px"><td colspan=5></td></tr>

<tr>
<td></td>
<td><span style="padding-left:25px">�.�.</span></td>
<td></td>
<td><span style="padding-left:60px"></span></td>
<td></td>

</tr>


</table>-->
	
	
	
	
	
	
	
	
	
	<!--<div
		style="margin-top: 0cm; margin-right: 1.0cm; margin-bottom: 0cm; margin-left: 2.0cm; margin-bottom: .0001pt; text-align: justify">
		<span style="font-size: 12.0pt; color: #080808">�������������
			�����������,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;��������</span>
	</div>
	<div
		style="margin-top: 0cm; margin-right: 1.0cm; margin-bottom: 0cm; margin-left: 2.0cm; margin-bottom: .0001pt; text-align: justify">
		<span style="font-size: 12.0pt; color: #080808">����������
			������������&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;������ ������������ <?=TEXT_HEADCENTER_SHORT_IP?></span>
	</div>
	<div
		style="margin-top: 0cm; margin-right: 1.0cm; margin-bottom: 0cm; margin-left: 2.0cm; margin-bottom: .0001pt; text-align: justify">&nbsp;</div>
	<div
		style="margin-top: 0cm; margin-right: 1.0cm; margin-bottom: 0cm; margin-left: 2.0cm; margin-bottom: .0001pt; text-align: justify">
		<span style="font-size: 12.0pt; color: #080808">__________________________&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;___________________________</span>
	</div>
	<div
		style="margin-top: 0cm; margin-right: 1.0cm; margin-bottom: 0cm; margin-left: 2.0cm; margin-bottom: .0001pt; text-align: justify">&nbsp;</div>
	<div
		style="margin-top: 0cm; margin-right: 1.0cm; margin-bottom: 0cm; margin-left: 2.0cm; margin-bottom: .0001pt; text-align: justify">
		<span style="font-size: 12.0pt; color: #080808">&laquo;___&raquo; <i>________________</i> <?php echo date('Y');?></span><span
			style="font-size: 12.0pt; color: #080808">
			�.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span
			style="font-size: 12.0pt; color: #080808;">&laquo;___&raquo; <i>________________</i> <?php echo date('Y');?></span><span
			style="font-size: 12.0pt; color: #080808">�.</span>
	</div>
	<div
		style="margin-top: 0cm; margin-right: 1.0cm; margin-bottom: 0cm; margin-left: 2.0cm; margin-bottom: .0001pt; text-align: justify">
		<span style="font-size: 12.0pt; color: #080808">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</span>
	</div>
	<div style="margin-right: 1.0cm; text-align: justify">
		<span style="font-size: 12.0pt; color: #080808">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; �.�.</span>
	</div>-->
	
	</p>
</div>
</body>
</html>
