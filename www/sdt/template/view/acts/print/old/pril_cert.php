<?php

//Наименование ВУЗа
$vuz_name=$Man->getTest()->getAct()->getUniversity()->name;
$vuz_form=$Man->getTest()->getAct()->getUniversity()->form;
//Номер сертификата
$sert_num=$Man->document_nomer;
//Кому выдано
$vidano=$Man->surname_rus.' '.$Man->name_rus;
//Страна
$strana=$Man->getCountry()->name;
//Уровень (первого / второго / базового)
$uroven=$Man->getTest()->getLevel()->print;
//Баллы (общий, 1й, 2й...)
$balli_total=str_replace('.',',',sprintf('%01.1f',$Man->total_percent));
$balli1=str_replace('.',',',sprintf('%01.1f',$Man->grammar_percent));
$balli2=str_replace('.',',',sprintf('%01.1f',$Man->reading_percent));
$balli3=str_replace('.',',',sprintf('%01.1f',$Man->writing_percent));
$balli4=str_replace('.',',',sprintf('%01.1f',$Man->listening_percent));
$balli5=str_replace('.',',',sprintf('%01.1f',$Man->speaking_percent));
//автоматический подсчет
/*$balli_total=(($balli1+$balli2+$balli3+$balli4+$balli5)/5);
 $balli_total=str_replace('.',',',$balli_total);*/

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
		<b>&nbsp;</b>
	</div>
	<div align="center" style="margin-right: 1.0cm; text-align: center">
		<b><span style="font-size: 12.0pt;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				Государственная система тестирования граждан</span> </b>
	</div>
	<div align="center" style="margin-right: 1.0cm; text-align: center">
		<b><span style="font-size: 12.0pt;">зарубежных стран по русскому языку</span>
		</b>
	</div>
	<div align="center" style="margin-right: 1.0cm; text-align: center">
		<span style="font-size: 9.0pt"><?php echo $vuz_form; ?></span>
	</div>
	<div align="center" style="margin-right: -7.1pt; text-align: center;">
		<b><u><span style="font-size: 12.0pt">&laquo;<?=$vuz_name; ?>&raquo;
			</span> </u> </b>
	</div>
	
	<div align="center" style="margin-right: 1.0cm; text-align: center">
		<b>&nbsp;</b>
	</div>
	<div align="center" style="margin-right: 1.0cm; text-align: center">
		<b>&nbsp;</b>
	</div>
	<div align="center" style="margin-right: 1.0cm; text-align: center">
		<b>&nbsp;</b>
	</div>
	<div align="center" style="margin-right: 1.0cm; text-align: center">
		<b><span style="font-size: 16.0pt; color: #080808">РУССКИЙ ЯЗЫК КАК
				ИНОСТРАННЫЙ</span> </b>
	</div>
	<div align="center" style="margin-right: 1.0cm; text-align: center">
		<b>&nbsp;</b>
	</div>
	<div align="center" style="margin-right: 1.0cm; text-align: center;">
		<b>&nbsp;</b>
	</div>
	<div align="center" style="margin-right: 1.0cm; text-align: center">
		<b><span style="font-size: 16.0pt; color: #080808">Приложение к
				сертификату &nbsp; <?=$sert_num; ?>&nbsp;
		</span> </b>
	</div>
	<div align="center" style="margin-right: 1.0cm; text-align: center">
		<b><span style="font-size: 16.0pt; color: #080808">&nbsp;</span> </b>
	</div>
	<div
		style="margin-right: 1.0cm; text-align: justify; text-indent: 2.0cm">
		<span style="font-size: 12.0pt; color: #080808">Выдано<b> &nbsp;&nbsp;<?=$vidano; ?>
		</b>
		</span>
	</div>
	<div
		style="margin-right: 1.0cm; text-align: justify; text-indent: 36.0pt">&nbsp;</div>
	<div
		style="margin-right: 1.0cm; text-align: justify; text-indent: 2.0cm">
		<span style="font-size: 12.0pt; color: #080808">Страна<b>&nbsp;&nbsp;
				&nbsp;<?=$strana; ?>
		</b>
		</span>
	</div>
	<div style="margin-left: 2.0cm; text-indent: -2.0cm">
		<b>&nbsp;</b>
	</div>
	<div style="margin-left: 2.0cm; text-indent: -2.0cm">
		<b>&nbsp;</b>
	</div>
	<div
		style="margin-top: 0cm; margin-right: 1.0cm; margin-bottom: 0cm; margin-left: 2.0cm; margin-bottom: .0001pt">
		<span style="font-size: 12.0pt; color: #080808">по результатам
			тестирования в объёме <b><u>&nbsp;<?=$uroven; ?>
			</u>&nbsp;</b>сертификационного уровня &nbsp;
		</span>
	</div>
	<div align="center"
		style="margin-top: 0cm; margin-right: 1.0cm; margin-bottom: 0cm; margin-left: 2.0cm; margin-bottom: .0001pt; text-align: center; text-indent: -2.0cm">&nbsp;</div>
	<div
		style="margin-top: 0cm; margin-right: 1.0cm; margin-bottom: 0cm; margin-left: 2.0cm; margin-bottom: .0001pt; text-align: justify; text-indent: -2.0cm">&nbsp;</div>
	<div
		style="margin-top: 0cm; margin-right: 1.0cm; margin-bottom: 0cm; margin-left: 2.0cm; margin-bottom: .0001pt; text-align: justify; text-indent: -2.0cm">
		<span style="font-size: 12.0pt; color: #080808">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			Общий балл (в процентах)&nbsp;&nbsp;&nbsp;&nbsp; <b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$balli_total; ?>
		</b>
		</span>
	</div>
	<div
		style="margin-top: 0cm; margin-right: 1.0cm; margin-bottom: 0cm; margin-left: 2.0cm; margin-bottom: .0001pt; text-align: justify; text-indent: -2.0cm">&nbsp;</div>
	<div align="center"
		style="margin-top: 0cm; margin-right: 1.0cm; margin-bottom: 0cm; margin-left: 2.0cm; margin-bottom: .0001pt; text-align: center; text-indent: -2.0cm">
		<b><span style="font-size: 14.0pt; color: #080808">Результаты теста по
				разделам:</span> </b>
	</div>
	<div align="center"
		style="margin-top: 0cm; margin-right: 1.0cm; margin-bottom: 0cm; margin-left: 2.0cm; margin-bottom: .0001pt; text-align: center; text-indent: -2.0cm">&nbsp;</div>
	<div
		style="margin-top: 0cm; margin-right: 1.0cm; margin-bottom: 0cm; margin-left: 2.0cm; margin-bottom: .0001pt; text-align: justify; text-indent: -2.0cm">
		<b><span style="font-size: 12.0pt; color: #080808">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				&nbsp;&nbsp;&nbsp;&nbsp;
				Раздел&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				&nbsp;&nbsp;&nbsp;Процент
				правильных ответов</span> </b>
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
						</span><span style="font-size: 12.0pt; color: #080808">Владение
							лексикой и грамматикой </span>
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
						</span><span style="font-size: 12.0pt; color: #080808">Понимание
							содержания текстов при чтении</span>
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
						</span><span style="font-size: 12.0pt; color: #080808">Владение
							письменной речью</span>
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
						</span><span style="font-size: 12.0pt; color: #080808">Понимание
							содержания звучащей речи</span>
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
						</span><span style="font-size: 12.0pt; color: #080808">Устное
							общение</span>
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
	<div style="margin-right: 1.0cm; text-align: justify">&nbsp;</div>
	<div style="margin-right: 1.0cm; text-align: justify">&nbsp;</div>
	<div
		style="margin-top: 0cm; margin-right: 1.0cm; margin-bottom: 0cm; margin-left: 2.0cm; margin-bottom: .0001pt; text-align: justify">
		<span style="font-size: 12.0pt; color: #080808">Представитель
			организации,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Директор</span>
	</div>
	<div
		style="margin-top: 0cm; margin-right: 1.0cm; margin-bottom: 0cm; margin-left: 2.0cm; margin-bottom: .0001pt; text-align: justify">
		<span style="font-size: 12.0pt; color: #080808">проводящей
			тестирование&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;центра тестирования <?=TEXT_HEADCENTER_SHORT_IP?></span>
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
			г.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span
			style="font-size: 12.0pt; color: #080808;">&laquo;___&raquo; <i>________________</i> <?php echo date('Y');?></span><span
			style="font-size: 12.0pt; color: #080808">г.</span>
	</div>
	<div
		style="margin-top: 0cm; margin-right: 1.0cm; margin-bottom: 0cm; margin-left: 2.0cm; margin-bottom: .0001pt; text-align: justify">
		<span style="font-size: 12.0pt; color: #080808">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</span>
	</div>
	<div style="margin-right: 1.0cm; text-align: justify">
		<span style="font-size: 12.0pt; color: #080808">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; М.П.</span>
	</div>
	
	</p>
</div>
</body>
</html>
