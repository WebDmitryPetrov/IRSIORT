<?php/** @var Act $act */$official=trim($act->official);$official=str_replace('  ',' ',$official);$official_array=explode(' ',$official);$official_first=array_shift($official_array);$official_second=implode(' ',$official_array);$act_number =$act->number;$dogovor_date_simple = date('d.m.Y',strtotime($act->getUniversityDogovor()->date));$dogovor_date = date_of($dogovor_date_simple);$testing_date =date_of(date('d.m.Y',strtotime($act->testing_date)));if(!$act->check_date || $act->check_date=='0000-00-00'){    $document_check_date=date_of(date('d.m.Y'));} //��������� ���������else{    $document_check_date=date_of(date('d.m.Y',strtotime($act->check_date)));}function date_of($date) {$date_day=substr($date, 0, 2);$date_month=substr($date, 3, 2);$date_year=substr($date, 6, 4);$date_month = str_replace('01', '������', $date_month);$date_month = str_replace('02', '�������', $date_month);$date_month = str_replace('03', '�����', $date_month);$date_month = str_replace('04', '������', $date_month);$date_month = str_replace('05', '���', $date_month);$date_month = str_replace('06', '����', $date_month);$date_month = str_replace('07', '����', $date_month);$date_month = str_replace('08', '�������', $date_month);$date_month = str_replace('09', '��������', $date_month);$date_month = str_replace('10', '�������', $date_month);$date_month = str_replace('11', '������', $date_month);$date_month = str_replace('12', '�������', $date_month);$date = '&laquo;' . $date_day . '&raquo; ' . $date_month . ' '. $date_year ;return $date;}$vuz_name = $act->getUniversity()->name;$dogovor_num = $act->getUniversityDogovor()->number;$sum_total = sprintf('%01.2f',$act->amount_contributions);$sum_total2= $sum_total - $sum_total/1.18;$sum_total2 = sprintf('%01.2f',$sum_total2);$per = $sum_total; //�� �������, ��� ��� ������ �����$per2 = $sum_total2; //�� �������, ��� ��� ������ �����$otvetst = $act->responsible; //������������� �� ���������� ������������$sum_total_rub = substr($sum_total, 0, (strpos($sum_total, '.')));$sum_total_kop = substr($sum_total, (strpos($sum_total, '.') + 1));$sum_total_rub2 = substr($sum_total2, 0, (strpos($sum_total2, '.')));$sum_total_kop2 = substr($sum_total2, (strpos($sum_total2, '.') + 1));?><html><script>window.print();</script><!----><head>    <meta http-equiv="Content-Type" content="text/html; charset=windows-1251"></head><body><div style="width:180mm"><?php//������� �������� ������ � �������function num2str($num) {    $nul='����';    $ten=array(        array('','����','���','���','������','����','�����','����', '������','������'),        array('','����','���','���','������','����','�����','����', '������','������'),    );    $a20=array('������','�����������','����������','����������','������������' ,'����������','�����������','����������','������������','������������');    $tens=array(2=>'��������','��������','�����','���������','����������','���������' ,'�����������','���������');    $hundred=array('','���','������','������','���������','�������','��������', '�������','���������','���������');    $unit=array( // Units        array('�������' ,'�������' ,'������',	 1),        array('�����'   ,'�����'   ,'������'    ,0),        array('������'  ,'������'  ,'�����'     ,1),        array('�������' ,'��������','���������' ,0),        array('��������','��������','����������',0),    );    //    list($rub,$kop) = explode('.',sprintf("%015.2f", floatval($num)));    $out = array();    if (intval($rub)>0) {        foreach(str_split($rub,3) as $uk=>$v) { // by 3 symbols            if (!intval($v)) continue;            $uk = sizeof($unit)-$uk-1; // unit key            $gender = $unit[$uk][3];            list($i1,$i2,$i3) = array_map('intval',str_split($v,1));            // mega-logic            $out[] = $hundred[$i1]; # 1xx-9xx            if ($i2>1) $out[]= $tens[$i2].' '.$ten[$gender][$i3]; # 20-99            else $out[]= $i2>0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9            // units without rub & kop            if ($uk>1) $out[]= morph($v,$unit[$uk][0],$unit[$uk][1],$unit[$uk][2]);        } //foreach    }    else $out[] = $nul;    $out[] = morph(intval($rub), $unit[1][0],$unit[1][1],$unit[1][2]); // rub    $out[] = $kop.' '.morph($kop,$unit[0][0],$unit[0][1],$unit[0][2]); // kop    return trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));}//�������� ����������function morph($n, $f1, $f2, $f5) {    $n = abs(intval($n)) % 100;    if ($n>10 && $n<20) return $f5;    $n = $n % 10;    if ($n>1 && $n<5) return $f2;    if ($n==1) return $f1;    return $f5;}$asd='-';$dsa='.';$per=str_replace('-','.',$per);$per=str_replace(',','.',$per);$per=str_replace(' ','.',$per);$per=num2str($per);$per2=str_replace('-','.',$per2);$per2=str_replace(',','.',$per2);$per2=str_replace(' ','.',$per2);$per2=num2str($per2); //������� �� �����$per_1=strtoupper(substr($per,0,1));$per_2=substr($per,1);$per= $per_1.$per_2;$per2_1=strtoupper(substr($per2,0,1));$per2_2=substr($per2,1);$per2= $per2_1.$per2_2;//����� �������� �������� ������?><p><table cellspacing="0" cellpadding="0" border="1" style="border-collapse:collapse;border:none;">    <tbody>    <tr>        <td width="319" valign="top" style="width:239.25pt;border:solid white 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">            <div><b><span style="color:#333333; font-size:13pt">���������</span></b></div>        </td>        <td width="319" valign="top" style="width:239.25pt;border:solid white 1.0pt;            border-left:none;            padding:0cm 5.4pt 0cm 5.4pt">			<div style="margin-left:72.6pt"><b><span style="color:#333333; font-size:13pt">���������</span></b></div>            <!--<div><b><font size="1">&nbsp;</font></b></div>-->        </td>    </tr>    <tr>        <td width="319" valign="top" style="width:239.25pt;border:solid white 1.0pt;            border-top:none;            padding:0cm 5.4pt 0cm 5.4pt">			<!--<div><span style="color:#333333">__________________________</span></div>-->			<div style="ma rgin-left:72.6pt; bord er-bottom: 1px solid black;width:207px"><span style="color:#333333"><?=/*ACT_APPROVE_LEFT_TOP_POSITION*/$signer->position;?></span></div>			<!--<div><span style="color:#333333">__________________________</span></div>-->			                    </td>        <td width="319" valign="top" style="width:239.25pt;border-top:none;border-left:            none;border-bottom:solid white 1.0pt;border-right:solid white 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">			<div style="margin-left:72.6pt"><span style="color:#333333">������������ �����������</span></div>        </td>    </tr>     <tr>        <td width="319" valign="top"  style="width:239.25pt;border:solid white 1.0pt;            border-top:none;            padding:0cm 5.4pt 0cm 5.4pt">            <!--<div><span style="color:#333333">&nbsp;</span></div>-->			<div style="ma rgin-left:72.6pt; border-bo ttom: 1px solid black; width:207px;text-align:center"><span style="color:#333333;mar gin-left:40pt"><?=/*ACT_APPROVE_LEFT_TOP_NAME*/$signer->caption;?></span></div>			<div><span style="color:#333333">__________________________</span></div>        </td>        <td width="319" valign="top" align=center style="width:239.25pt;border-top:none;border-left:            none;border-bottom:solid white 1.0pt;border-right:solid white 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">            <div style="margin-left:72.6pt; bor der-bottom: 1px solid black"><span style="color:#333333"><?=$official_first?>&nbsp;<?=$official_second?></span></div>			<div><span style="color:#333333;margin-left:72.6pt">_____________________________</span></div>        </td>    </tr>    <!--<tr>        <td width="319" valign="top" style="width:239.25pt;border:solid white 1.0pt;            border-top:none;            padding:0cm 5.4pt 0cm 5.4pt">            <div><span style="color:#333333">&nbsp;</span></div>        </td>        <td width="319" valign="top" align=center style="width:239.25pt;border-top:none;border-left:            none;border-bottom:solid white 1.0pt;border-right:solid white 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">            <div style="margin-left:72.6pt;  border-bottom: 1px solid black"><span style="color:#333333">&nbsp;<?=$official_second?></span></div>        </td>    </tr>-->     <tr style="height:19.55pt">        <td width="319" valign="top" style="width:239.25pt;border:solid white 1.0pt;            border-top:none;            padding:0cm 5.4pt 0cm 5.4pt;height:19.55pt">            <div><span style="color:#333333">____________________20___�.</span></div>			        </td>        <td width="319" valign="top" style="width:239.25pt;border-top:none;border-left:            none;border-bottom:solid white 1.0pt;border-right:solid white 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:19.55pt">            <div style="margin-left:72.6pt"><span style="color:#333333">_______________________20___�.</span></div>        </td>    </tr>	<tr style="height:19.55pt">        <td width="319" valign="top" style="width:239.25pt;border:solid white 1.0pt;            border-top:none;            padding:0cm 5.4pt 0cm 5.4pt;height:19.55pt">            <div style="margin-left:62.6pt"><span style="fon t-size:14.0pt;color:#333333;f ont-weight:bold; ">�.�.</span></div>			        </td>        <td width="319" valign="top" style="width:239.25pt;border-top:none;border-left:            none;border-bottom:solid white 1.0pt;border-right:solid white 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:19.55pt">            <div style="margin-left:138.6pt"><span style="fo nt-size:14.0pt;color:#333333;fo nt-weight:bold; ">�.�.</span></div>        </td>    </tr>     </tbody></table></p><br><div>&nbsp;</div><!--<div align=center><b><span style="font-size:12.0pt;color:#333333">��� � ���������� ������������<br>�� �������� ����� ��� ������������ � <?php echo $act_number;?>--><div align=center><b><span style="font-size:15.0pt;color:#333333">���</b></span></div><div align=center><span style="font-size:15.0pt;color:#333333">� ���������� �������� ������</span></div><div align=center><span style="font-size:15.0pt;color:#333333">�� �������� ����� ��� ������������</span></div><br><div align=center><span style="font-size:12.0pt;color:#333333;text-align:center">��������� ��� ��������� ����� <?=TEXT_HEADCENTER_LONG_TP_PRINT_ACT_NEW?>  (����� �� ������ �<?=TEXT_HEADCENTER_SHORT_IP?>�)�<br><u><?=$vuz_name; ?></u><br>(����� �� ������ �������������) � �������� � <u><?=$dogovor_num; ?></u> ��    <u><?=strtolower($dogovor_date); ?>�.</u><br>(����� �� ������ ��������) � �������������:<b></b></span></div><div align="center" style="text-align:center"><b>&nbsp;</b></div><div style="margin-left:18.0pt;text-align:justify;text-indent:-18.0pt;"><span style="font-size:12.0pt;color:#333333"><b>1.</b><span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;&nbsp;&nbsp;&nbsp; </span></span><span        style="font-size:12.0pt;color:#333333">� ������������ � ��������� <u><?=strtolower($testing_date); ?>�.</u> ��������� �������� ������:<!--&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i><u><b><?=$testing_date; ?> �.</b></u></i>--></div><br><div style="text-align:justify;height:6px">&nbsp;</div><table width="100%" cellspacing="0" cellpadding="0" border="1"       style="width:100.0%;border-collapse:collapse;border:none;"><tbody><tr>    <td width="17%" rowspan="1" style="width:17.78%;border:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">        <div align="center" style="text-align:center"><b><span style="font-size:11.0pt;color:#333333">�������</span></b>        </div>        <div align="center" style="text-align:center"><b><span                style="font-size:11.0pt;color:#333333">������������</span></b></div>    </td>	    <td width="17%" rowspan="1" style="width:17.78%;border:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">        <div align="center" style="text-align:center"><b><span style="font-size:11.0pt;color:#333333">����� ���������� �������</span></b>        </div>    </td>	<td width="17%" rowspan="1" style="width:17.78%;border:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">        <div align="center" style="text-align:center"><b><span style="font-size:11.0pt;color:#333333">��������� ����� <?=TEXT_HEADCENTER_SHORT_IP?>  � ������� �� 1 �������� (���.)</span></b>        </div>    </td>	<td width="17%" rowspan="1" style="width:17.78%;border:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">        <div align="center" style="text-align:center"><b><span style="font-size:11.0pt;color:#333333">����� ��������� ����� <?=TEXT_HEADCENTER_SHORT_IP?> �� �������� ������ (���.)</span></b>        </div>    </td>	</tr><?php foreach($act->getTests() as $test):    /** @var ActTest $test */	$prices=ChangedPriceTestLevel::checkPrice($act->id);    ?><tr>    <td width="17%" style="width:17.78%;border:solid windowtext 1.0pt;border-top:            none;            padding:0cm 5.4pt 0cm 5.4pt">        <div align="center" style="text-align:center"><span style="font-size:11.0pt;color:#333333">            <?php echo $test->getLevel()->caption; ?>        </span>        </div>    </td>    <td width="12%" style="width:12.72%;border-top:none;border-left:none;            border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">        <div align="center" style="text-align:center"><i>            <?php echo $test->people_first; ?>        </i></div>    </td>    <td width="18%" style="width:18.12%;border-top:none;border-left:none;            border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">        <div align="center" style="text-align:center"><i>            <?php //echo $test->getLevel()->price ?>            <?php echo $prices->price; ?>        </i></div>    </td>	<td width="18%" style="width:18.12%;border-top:none;border-left:none;            border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">        <div align="center" style="text-align:center"><i>            <?php  //echo $test->getLevel()->price * $test->people_first ?>			<?php  echo $prices->price * $test->people_first;  ?>        </i></div>    </td>   </tr>    <?php endforeach; ?></tbody></table><div style="text-align:justify"><i>&nbsp;</i></div><div style="text-align:justify"><span style="font-size:12.0pt;color:#333333;"><!--2. ����� ��������� �� ��������� �� <?=$date; ?>    �. ��������� -->		��� ��������� �� ���������� ��������� �������� ������ �������� � <?=TEXT_HEADCENTER_SHORT_IP?>.	<br>	��������� ��������� <?= strtolower($document_check_date); ?>�.	<p>	<b>2.</b> ��������� ����� <?=TEXT_HEADCENTER_SHORT_IP?> ��� ���������� ������������� �������� ������ ����������	<!--<br>-->	<nobr><b><u><?=$sum_total_rub; ?></u>  <i>���.</i> <u><?=$sum_total_kop; ?></u> <i>���.</i> </b></nobr>	<!--<br>-->	(<u><?=$per; ?></u>),<!--<br>-->	<i>������� ��� � ������� <nobr><b><u><?=$sum_total_rub2; ?></u> ���. <u><?=$sum_total_kop2; ?></u> ���. </b></nobr>	<nobr>(<u><?= $per2; ?></u>)</nobr></i>.		</span>	</p></div><p style="text-align:justify"><span style="font-size:12.0pt;color:#333333">	<b>3.</b> ��������� ����� �������� ������������ � <?=TEXT_HEADCENTER_SHORT_IP?> � �����, ������������� ���������.		</span></p><p style="text-align:justify"><span style="font-size:12.0pt;color:#333333"><b>4.</b> ����������� �� ����� ��������� � <?=TEXT_HEADCENTER_SHORT_IP?> �� ������ � �������� ��������� ����� ��� ���������� ������������� �������� ������.</span></p><p style="text-align:justify"><span style="font-size:12.0pt;color:#333333"><b>5.</b> ��������� ��� ��������� � ���� ����������� �� ������ ��� ������ �� ������.</span></p><div style="text-align:justify"><span style="font-size:12.0pt;color:#333333">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;</span></div><div style="text-align:justify">&nbsp;</div><table border=0>    <tr><td style="font-size:12.0pt;color:#333333;width:10mm"></td>        <td align=center colspan=3 style="font-size:12.0pt; font-family: tahoma;color:#333333;width:85mm;"><i>            ������������� �� ����������            <br>            ������������ �� <?=TEXT_HEADCENTER_SHORT_IP?>  <!--          <br>            ������������-->			<br><br>        </i></td>        <td width=120px>&nbsp;</td>        <td align=center colspan=3 style="font-size:12.0pt; font-family: tahoma; color:#333333; vertical-align:top;width:85mm;"><i>            ������������� �� ����������            <br>            ������������ �� �����������		<!--	<br>			������������--><br><br>        </i></td>    </tr><!--������� 1-->    <tr><td></td>	        <td style="border-bottom:0.5pt solid black;font-size:12.0pt; color:#333333; vertical-align:top;width:30mm"><i>&nbsp;</i></td>        <td style="border-bottom:0.5pt solid black;font-size:12.0pt; color:#333333; vertical-align:top;width:45mm "><i>/&nbsp;</i></td>        <td style="bord er-bottom:0.5pt solid black;font-size:12.0pt; color:#333333; vertical-align:top;"><i>/</i></td>        <td></td>        <td style="border-bottom:0.5pt solid black;font-size:12.0pt; color:#333333; vertical-align:top;width:30mm;text-align:right"><i>&nbsp;</i></td>        <td style="border-bottom:0.5pt solid black;font-size:12.0pt; color:#333333; vertical-align:top;width:50mm;te xt-align:center "><i>/&nbsp;<?=$otvetst; ?></i></td>        <td style="bord er-bottom:0.5pt solid black;font-size:12.0pt; color:#333333; vertical-align:top;w idth:1mm;"><i>/</i></td>    </tr>			<!-- ������� 2	<tr><td></td>        <td style="border-bottom:0.5pt solid black;font-size:12.0pt; color:#333333; vertical-align:top;width:30mm"><i>&nbsp;</i></td>        <td style="border-bottom:0.5pt solid black;font-size:12.0pt; color:#333333; vertical-align:top;width:45mm "><i>/&nbsp;</i></td>        <td style="bord er-bottom:0.5pt solid black;font-size:12.0pt; color:#333333; vertical-align:top;"><i>/</i></td>        <td></td>        <td style="border-bottom:0.5pt solid black;font-size:12.0pt; color:#333333; vertical-align:top;width:30mm;text-align:right"><i>&nbsp;</i></td>        <td style="border-bottom:0.5pt solid black;font-size:12.0pt; color:#333333; vertical-align:top;w 	idth:50mm;text-align:center "><i>/&nbsp;<nobr><?=$otvetst; ?></nobr></i></td>        <td style="bord er-bottom:0.5pt solid black;font-size:12.0pt; color:#333333; vertical-align:top;width:1mm;"><i>/</i></td>    </tr>--></table></div></body></html>