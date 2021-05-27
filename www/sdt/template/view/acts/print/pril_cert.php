<?php
header ("Content-Type: text/html; charset=windows-1251");
$type = 1;
if (!empty($_GET['type']) &&is_numeric($_GET['type'])

) {
    $type = $_GET['type'];
}
$sign=ActSigning::getByID($type);
if(!$sign) $sign=ActSigning::getByID(1);

$rukovod_dolzhn_1 =  $sign->position;
$rukovod_fio = $sign->caption;



$gct_full_name=SIGNING_CENTER_NAME;
//$gct_short_name='ГЦТРКИ'; не используется

//Наименование ВУЗа


$vuz_name=CERT_PRIL_RUDN_NAME;
$vuz_form=CERT_PRIL_RUDN_FORM;

?>
<html>
<script>window.print();</script>
<body>
<?
$counter=count($persons);
//echo $counter;
$ccc=0;
foreach ($persons as $Man)
{
   /* if ($Man->blank_date == '0000-00-00' || is_null($Man->blank_date))
    {
        $Man->blank_date=date("Y-m-d");
        $Man->setValidTill();
        $Man->save();
    }*/


	if (empty($Man->blank_date) || $Man->blank_date == '0000-00-00') {

		$this->blank_date = $this->getAct()->getPrintDateAfterCheckDate();

		/*$now = new DateTime();
		$checkDate = new DateTime($Man->getAct()->check_date);
		if($now>=$checkDate)
		{
			$Man->blank_date = date('Y-m-d');
		}
		else{
			$Man->blank_date = $checkDate->format('Y-m-d');
		}*/

		$Man->setValidTill();
		$Man->save();
	}



    $Man=CertificateDuplicate::checkForDuplicates($Man); //!!!! обязательно после сохранения



//Номер сертификата
//$sert_num='№ '.$Man->document_nomer;
//$sert_num='№ '.$Man->blank_number;
$sert_num='№ '.$Man->getBlank_number();
//Кому выдано
//$vidano=$Man->surname_rus.' '.$Man->name_rus;
$vidano=$Man->getSurname_rus().' '.$Man->getName_rus();
//Страна
$strana=$Man->getCountry()->name;
//Уровень (первого / второго / базового)
$Level= $Man->getTest()->getLevel();
$uroven=$Level->print;

//Баллы (общий, 1й, 2й...)
$balli_total=str_replace('.',',',sprintf('%01.1f',$Man->total_percent));

//автоматический подсчет

	$print_date = $Man->getAct()->getPrintDateAfterCheckDate();

//$date_day = date('d');
$date_day = date('d',strtotime($print_date));
//$date_month = date('m');
$date_month = date('m',strtotime($print_date));
$date_month = str_replace('01', 'января', $date_month);
$date_month = str_replace('02', 'февраля', $date_month);
$date_month = str_replace('03', 'марта', $date_month);
$date_month = str_replace('04', 'апреля', $date_month);
$date_month = str_replace('05', 'мая', $date_month);
$date_month = str_replace('06', 'июня', $date_month);
$date_month = str_replace('07', 'июля', $date_month);
$date_month = str_replace('08', 'августа', $date_month);
$date_month = str_replace('09', 'сентября', $date_month);
$date_month = str_replace('10', 'октября', $date_month);
$date_month = str_replace('11', 'ноября', $date_month);
$date_month = str_replace('12', 'декабря', $date_month);

//$dateYear=date('Y');
$dateYear=date('Y',strtotime($print_date));
?>

<div style=" width: 180mm">

	
	
	

	<div align="center" style="margin-right: 1.0cm; text-align: center">
		<b><span style="font-size: 12.0pt;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				Государственная система тестирования граждан</span> </b>
	</div>
	<div align="center" style="margin-right: 1.0cm; text-align: center">
		<b><span style="font-size: 12.0pt;">зарубежных стран по русскому языку</span>
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
		<b><span style="font-size: 16.0pt; color: #080808">РУССКИЙ ЯЗЫК КАК
				ИНОСТРАННЫЙ</span> </b>
	</div>
	<div align="center" style="margin-right: 1.0cm; text-align: center">
		<b>&nbsp;</b>
	</div>

	
	
	<div align="center" style="margin-right: 1.0cm; text-align: center">
		<b><span style="font-size: 16.0pt; color: #080808">Приложение к
				сертификату
		</span> </b>
	</div>
	<br>
	<div align="center" style="margin-right:1.0cm;text-align:center"><b><u><span style="font-size:16.0pt;"><?=$sert_num; ?></span></u></b><b><u><span style="font-size:16.0pt;"></span></u></b></div>
	
	

		
		
		<div align="center" style="margin-right:1.0cm;text-align:center; height: 8px;">&nbsp;</div>
<div align="center" style="margin-right:1.0cm;text-align:center">выдано</div>
<div align="center" style="margin-right:1.0cm;text-align:center; height: 8px;">&nbsp;</div>
		
		<div align="center" style="margin-right:1.0cm;text-align:center"><b><font size="5"><u><?=$vidano; ?></u></font></b></div>
		<!--<div align="center" style="margin-right:1.0cm;text-align:center">&nbsp;</div>-->
<div align="center" style="margin-right:1.0cm;text-align:center">(Ф.И.О.)</div>
<div align="center" style="margin-right:1.0cm;text-align:center; height: 8px;">&nbsp;</div>
<div align="center" style="margin-right:1.0cm;text-align:center"><b><font size="5"><u><?=$strana; ?></u></font></b></div>
<!--<div align="center" style="margin-right:1.0cm;text-align:center">&nbsp;</div>-->
<div align="center" style="margin-right:1.0cm;text-align:center">(страна)</div>
<div align="center" style="margin-right:1.0cm;text-align:center">&nbsp;</div>
		

	<div
		style="margin-top: 0cm; margin-right: 1.0cm; margin-bottom: 0cm; margin-left: 2.0cm; margin-bottom: .0001pt">
		<span style="font-size: 12.0pt; color: #080808">По результатам
			тестирования в объёме уровня <b>&laquo;<?=$uroven; ?>&raquo;
			</b></span>
	</div>
	<div align="center"
		style="margin-top: 0cm; margin-right: 1.0cm; margin-bottom: 0cm; margin-left: 2.0cm; margin-bottom: .0001pt; text-align: center; text-indent: -2.0cm">&nbsp;</div>
<!--	<div-->
<!--		style="margin-top: 0cm; margin-right: 1.0cm; margin-bottom: 0cm; margin-left: 2.0cm; margin-bottom: .0001pt; text-align: justify; text-indent: -2.0cm">&nbsp;</div>-->
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
		<b><span style="font-size: 12.0pt; color: #080808">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				Раздел
			&nbsp;&nbsp;&nbsp;&nbsp;	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				&nbsp;&nbsp;&nbsp;Процент
				правильных ответов</span> </b>
	</div>
	<div
		style="margin-top: 0cm; margin-right: 1.0cm; margin-bottom: 0cm; margin-left: 2.0cm; margin-bottom: .0001pt; text-align: justify; text-indent: -2.0cm">&nbsp;</div>
	<table border="1" cellspacing="0" cellpadding="0"
		style="margin-left: 55.05pt; border-collapse: collapse; border: none;">
		<tbody>
        <?
        $sub_tests=SubTests::getByLevel($Level);
        //die(var_dump($sub_tests));
        $subTestResults = $Man->getResults();
        $li=$i=0;
        foreach($sub_tests as $sub_test)
        {
            $li++;
            $i++;
            //echo $sub_test->caption;
//die(var_dump(SubTestResults::getByMan($Man)->getByOrder($sub_test->order)->percent));









	$sub_test_meta_array=array();
	//$sub_test_groups_meta_array=array();
	//$sub_test_groups=array();
	
	foreach ($sub_tests as $s_key => $s_val)
	{
		if (!empty($s_val->meta))
		{
			//$sub_test_groups_meta_array[$s_val->meta->group_id][]=$s_val->meta->num;
			$sub_test_meta_array[$s_val->meta->num]=$s_val->meta;
		}
	}
	
	


		if (!empty($sub_test_meta_array[$i]->vedomost_caption))
		{
			$vedomost_caption = '<nobr>'.$sub_test_meta_array[$i]->vedomost_caption.'</nobr>';
		}
		else
		{
			//$vedomost_caption = 'Субтест ' . (array_search($i,$sub_test_groups[$i])+1);
			$vedomost_caption = '';
		}

		if (!empty($sub_test_meta_array[$i]->percent_show))
		{
			$percent_show = $sub_test_meta_array[$i]->percent_show;
		}
		else
		{
			$percent_show = 100;
		}
		






            echo '<tr>
            <td width="369" valign="top"
                style="width: 357.0pt; border: solid white 1.0pt; padd ing: 0cm 5.4pt 0cm 5.4pt">
                <div style="margin-left: 7.0pt; text-indent: -37.0pt;">
                    <span style="font-size: 12.0pt; color: #080808">'.$li.'.<span
                        style="font: 7.0pt&amp;quot;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>
                    </span><span style="font-size: 12.0pt; color: #080808">'.$sub_test->full_caption.' '.$vedomost_caption.'</span>
                </div>
            </td>
            <td width="219" valign="top"
                style="width: 164.0pt; border: solid white 1.0pt; border-left: none; paddi ng: 0cm 5.4pt 0cm 5.4pt">
                <div align="center" style="text-align: center">
                    <b><span style="font-size: 12.0pt">'.str_replace('.',',',sprintf('%01.1f', $subTestResults->getByOrder($sub_test->order)->percent*$percent_show/100)).'</span> </b>
                </div>
            </td>
        </tr>';




        }


        ?>

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


<td align=right><b>&laquo;<?=$date_day?>&raquo; <?=$date_month?> <?=$dateYear?> г.</b></td>


</tr>


<tr style="height:35px"><td colspan=2></td></tr>



</table>
	
	
	

	

</div>
    <?
    $ccc++;
    if ($counter > $ccc)
        echo '<div style="page-break-after: always;"></div>';


}
?>
</body>
</html>
