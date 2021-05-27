<?php
/** @var Act $act */

//$organization = $act->getUniversity()->name; //название орагнизации
$organization = $act->getUniversity()->getLegalInfo()['name']; //название орагнизации

$num = 0; //номер п/п


$otvetst = $act->responsible;


switch ($act->test_level_type_id) {
    case 1:
        $caption = 'Сводная таблица результатов государственного тестирования по русскому языку,';
        break;
    case 2:
    default:
        $caption = 'Сводная таблица результатов комплексного экзамена по русскому языку, истории России и законодательству РФ,';
        break;

}

/*
$sub_test_groups=array(
1 => array(1,2),
2 => array(1,2),
3 => array(3,4),
4 => array(3,4)
);
$sub_test_groups=array(
4 => array(4,5),
5 => array(4,5),
6 => array(6,7),
7 => array(6,7));

/*6 => array(6,7),
7 => array(6,7),
8 => array(8,9),
9 => array(8,9));*/


//$sub_test_groups=array();




?>


<html>
<script>window.print();</script>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
    <style type="text/css">

        .table td {
            padding: 0cm 5.4pt 0cm 5.4pt;
            font-size: 12pt;
        }

        .underlined_tr td {
            border-bottom: 2px solid black !important;
        }

    </style>


</head>

<body style="margin-top: 20px">

<div style="text-align: left; margin-left: 4cm"><b><?= $caption; ?> <br>проведенного в <u><?= $organization; ?></u></b></div>

<!--<div><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (название организации)</span>
</div>-->
<!--<div align=center><b>______________________________________________________________________________________</b></div>-->
<div><b>&nbsp;</b></div>


<?

$tests = $act->getTests();

foreach ($tests as $test) :

    /** @var TestLevel $level */
    $level = $test->getLevel();
    $sub_tests = SubTests::getByLevel($level);
	
	$sub_tests_counter = count($sub_tests);
	
	
	
	
	
	
	
	
	$sub_test_meta_array=array();
	$sub_test_groups_meta_array=array();
	$sub_test_groups=array();
	
	foreach ($sub_tests as $s_key => $s_val)
	{
		if (!empty($s_val->meta))
		{
			$sub_test_groups_meta_array[$s_val->meta->group_id][]=$s_val->meta->num;
			$sub_test_meta_array[$s_val->meta->num]=$s_val->meta;
		}
	}
	
	
	if (!empty($sub_test_groups_meta_array))
	{
		foreach($sub_test_groups_meta_array as $key => $val)
		{
			foreach ($val as $kk => $vv)
			{
			$sub_test_groups[$vv]=$val;
			}
		}
	}
	
	//$sub_test_groups=array();
	
	

	if (!empty($sub_test_groups))
	{
		$th_rowspan=6;
	}
	else
	{
		$th_rowspan = 5;
	}



    
    ?>

    <table width="100%" class=table cellspacing="0" cellpadding="0" border="1"
           style="border-collapse:collapse;border:none; width:100%">
        <tbody>
        <tr style="page-break-inside:avoid;
            height:8.0pt">
            <td width="36" valign="top" style="width:26.7pt;border:solid windowtext 1.0pt;height:8.0pt" rowspan="<?=$th_rowspan?>">
                <div><b><span style="font-size:11.0pt">№</span></b></div>
                <div><b><span style="font-size:11.0pt">п/п</span></b></div>
            </td>

            <td width="104" valign="top" style="width:77.95pt;border:solid windowtext 1.0pt;
            border-left:none;height:8.0pt" rowspan="<?=$th_rowspan?>">
                <div><b><span style="font-size:11.0pt">Фамилия</span></b></div>
                <div>русскими / латинскими</div>
            </td>
            <td width="104" valign="top" style="width:77.95pt;border:solid windowtext 1.0pt;
            border-left:none;height:8.0pt" rowspan="<?=$th_rowspan?>">
                <div><b><span style="font-size:11.0pt">Имя</span></b></div>
                <div>русскими / латинскими</div>
            </td>
            <td width="95" valign="top" style="width:70.9pt;border:solid windowtext 1.0pt;
            border-left:none;height:8.0pt" rowspan="<?=$th_rowspan?>">
                <div><b><span style="font-size:11.0pt">Страна</span></b></div>
            </td>
            <td width="85" valign="top" style="width:63.75pt;border:solid windowtext 1.0pt;
            border-left:none;height:8.0pt" rowspan="<?=$th_rowspan?>">
                <div><b><span style="font-size:11.0pt">Дата тестиро&dash;<br>вания</span></b></div>
            </td>
            <td width="444" valign="top" style="width:333.15pt;border:solid windowtext 1.0pt;
            border-left:none;height:8.0pt" colspan="<?= $sub_tests_counter + 1 ?>">
                <div align="center" style="text-align:center"><b><span
                            style="font-size:11.0pt">Результаты (%)</span></b>
                </div>
            </td>
            <td width="76" valign="top" style="width:2.0cm;border:solid windowtext 1.0pt;
            border-left:1px solid;height:8.0pt" rowspan="<?=$th_rowspan?>">
                <div><b><span style="font-size:11.0pt">Уровень</span></b></div>
            </td>
           <!-- <td width="76" valign="top" style="width:2.0cm;border:solid windowtext 1.0pt;
            border-left:none;height:8.0pt" rowspan="5">
                <div><b><span style="font-size:11.0pt">№ бланка</span></b></div>
                <div><b><span style="font-size:11.0pt">серт./</span></b></div>
                <div><b><span style="font-size:11.0pt">справки</span></b></div>
            </td>-->
        </tr>



            <?
            $i = 0;
            $c = 1;

            $row1 = $row2 = $row2e = $row3 = $row4 = '';
            foreach ($sub_tests as $sub_test) {
                $i++;
                

				
				if (!empty($sub_test_groups[$i]))
				{
					 
					 if (in_array($i,$sub_test_groups[$i]))
					 {
						 //var_dump($sub_test_groups[$i]);
						 //echo $i;
						 
						 $td_colspan = ' colspan = "'.count($sub_test_groups[$i]).'" ';


		if (!empty($sub_test_meta_array[$i]->vedomost_caption))
		{
			$vedomost_caption = $sub_test_meta_array[$i]->vedomost_caption;
		}
		else
		{
			$vedomost_caption = 'Субтест ' . (array_search($i,$sub_test_groups[$i])+1);
		}

						 
						 $row2_extend = ' <td width="66" valign="top" style="width: 49.65pt; height: 14pt; border-right: 1px solid; text-align: center;" >
             <div align="center" style="text-align:center"><span style="font-size:11.0pt">' . $vedomost_caption . '</span></div>
            </td>';
						 
					 }
					 else
					 {
						 $c++;
						 $td_colspan = '';
						 $row2_extend = '';
					 }
				
if ($i == $sub_test_groups[$i][0]){
	//echo '<br>'.$i.'-'.$sub_test_groups[$i][0].'<br>';
				$row1 .= '
            <td width="66" valign="top" style="width: 49.65pt; height: 14pt; border: medium none; border-right: 1px solid; text-align: center;" '.$td_colspan.'>
              <div align="center" style="text-align:center"><span style="font-size:11.0pt">' . $c . '</span></div>

</td>';
                $row2 .= '
             <td width="66" valign="top" style="width: 49.65pt; height: 14pt; border: medium none; border-right: 1px solid; text-align: center;" '.$td_colspan.'>
             <div align="center" style="text-align:center"><span style="font-size:11.0pt">' . $sub_test->caption . '</span></div>
            </td>
';
$c++;
}
$row2e .=$row2_extend;

		if (!empty($sub_test_meta_array[$i]->percent_show))
		{
			$percent_show = $sub_test_meta_array[$i]->percent_show;
		}
		else
		{
			$percent_show = 100;
		}
                $row3 .= '

             <td width="66" valign="top" style="width: 49.65pt; height: 14pt; border: medium none; border-right: 1px solid; text-align: center;">
            <span style="font-size:11.0pt"> '.$percent_show.'% </span>
            </td>


      ';
                $row4 .= '
             <td width="66" valign="top" style="width: 49.65pt; height: 14pt; border: medium none; border-right: 1px solid; text-align: center;">

            <div align="center" style="text-align:center"><span style="font-size:11.0pt">' . $sub_test->max_ball . ' б.</span></div>
                    </td>


        ';
				}
				
				
				
				
				
				else
				{
					 $row1 .= '
            <td width="66" valign="top" style="width: 49.65pt; height: 14pt; border: medium none; border-right: 1px solid; text-align: center;">
              <div align="center" style="text-align:center"><span style="font-size:11.0pt">' . $c . '</span></div>

</td>';
                $row2 .= '
             <td width="66" valign="top" style="width: 49.65pt; height: 14pt; border: medium none; border-right: 1px solid; text-align: center;">
             <div align="center" style="text-align:center"><span style="font-size:11.0pt">' . $sub_test->caption . '</span></div>
            </td>
';
$row2e.='<td style=" border-top: none; border-bottom: none;border-right: 1px solid;"></td>';
                $row3 .= '

             <td width="66" valign="top" style="width: 49.65pt; height: 14pt; border: medium none; border-right: 1px solid; text-align: center;">
            <span style="font-size:11.0pt"> 100% </span>
            </td>


      ';
                $row4 .= '
             <td width="66" valign="top" style="width: 49.65pt; height: 14pt; border: medium none; border-right: 1px solid; text-align: center;">

            <div align="center" style="text-align:center"><span style="font-size:11.0pt">' . $sub_test->max_ball . ' б.</span></div>
                    </td>


        ';
		//echo $c;
		$c++;
				}
				
               

            }

            $row1.=' <td width="57" valign="top" style="width: 49.65pt; border-bottom: none; height: 14pt;  text-align: center;">
            <div align="center" style="text-align:center"><span style="font-size:11.0pt">Общ.</span></div>
            </td>';
             $row2.=' <td width="57" valign="top" style="width: 49.65pt; height: 14pt; border: medium none; text-align: center;">
            <div align="center" style="text-align:center"><span style="font-size:11.0pt">балл<br>%</span></div>
            </td>';
			$row2e.='<td style=" border-top: none; border-bottom: none;border-right: 1px solid;"></td>';
             $row3.=' <td width="57" valign="top" style="width: 49.65pt; height: 14pt; border: medium none; text-align: center;">
            <div align="center" style="text-align:center"><span style="font-size:11.0pt">100%</span></div>
            </td>';
             $row4.='<td width="57" valign="top" style="width: 49.65pt; height: 14pt; border: medium none; text-align: center;">
            <div align="center" style="text-align:center"><span style="font-size:11.0pt">'.$level->total.' б. </span></div>
            </td>';

if (!empty($sub_test_groups))
{

            foreach(array($row1,$row2,$row2e,$row3,$row4) as $r){
                echo '<tr  style="page-break-inside:avoid;height:14.0pt">'.$r.'</tr>';
            }
}
else
{
	 foreach(array($row1,$row2,$row3,$row4) as $r){
                echo '<tr  style="page-break-inside:avoid;height:14.0pt">'.$r.'</tr>';
            }
}
            ?>


        <?php



        //    die($test->people_first);


        //$people=$test->getPeople();
        $man_counter = 0;
        foreach ($test->getPeople() as $Man):


            /** @var ActMan $Man */

            $man_counter++;
            if ($test->people_retry != 0 && $test->people_first == $man_counter) {
                $add_class = 'underlined_tr';
            } else {
                $add_class = '';
            }

            /*    {
                    //die(var_dump($Man));

                }*/

//    foreach ($act->getPeople() as $Man):
            $subTestResults = $Man->getResults();
            // $balli=  str_replace('.',',',sprintf('%01.1f', $subTestResults->getByOrder($sub_test->order)->ball)) . ' / ' . str_replace('.',',',sprintf('%01.1f', $subTestResults->getByOrder($sub_test->order)->percent));

            $surname = $Man->surname_rus . ' / ' . $Man->surname_lat;
            $name = $Man->name_rus . ' / ' . $Man->name_lat;
            $country = $Man->getCountry()->name;
            $testing_date = date('d.m.Y', strtotime($Man->testing_date));


            $balli_total = sprintf('%01.1f', $Man->total_percent) . '% <br> ' . sprintf('%01.1f', $Man->total);
            $balli_total = str_replace('.', ',', $balli_total);
            $uroven = $Man->document == ActMan::DOCUMENT_CERTIFICATE ? 'Сертификат' : 'Справка';
            $uroven .= '<br>' ./*$level->caption*/
                $level->getPrintSummaryTable();
            $sert_num = ''; //$Man->document_nomer;


            ?>
            <tr class="<?= $add_class ?>">
                <td width="36" valign="top" style="width:26.7pt;border:solid windowtext 1.0pt;
            border-top:none;
            padding:0cm 5.4pt 0cm 5.4pt">
                    <div><?= ++$num; ?></div>

                </td>
                <td width="104" valign="top" style="width:77.95pt;border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
                    <div><?= $surname; ?></div>
                </td>
                <td width="104" valign="top" style="width:77.95pt;border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
                    <div><?= $name; ?></div>
                </td>
                <td width="95" valign="top" style="width:70.9pt;border-top:none;border-left:none;
            border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
                    <div><?= $country; ?></div>
                </td>
                <td width="85" valign="top" style="width:63.75pt;border-top:none;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
                    <div><?= $testing_date; ?></div>
                </td>

                <?
$i=0;
                foreach ($sub_tests as $sub_test) {
$i++;
		if (!empty($sub_test_meta_array[$i]->percent_show))
		{
			$percent_show = $sub_test_meta_array[$i]->percent_show;
		}
		else
		{
			$percent_show = 100;
		}
                    $balli = str_replace(
                            '.',
                            ',',
                            /*sprintf('%01.1f', $subTestResults->getByOrder($sub_test->order)->percent)*/
                            sprintf('%01.1f', ($subTestResults->getByOrder($sub_test->order)->percent*$percent_show/100))
                        ) . '%<br> ' . str_replace(
                            '.',
                            ',',
                            sprintf('%01.1f', $subTestResults->getByOrder($sub_test->order)->balls)
                        );


                    echo '
            <td width="66" valign="top" style="width:49.65pt;border-top:1px solid;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center">' . $balli . '</div>
            </td>
        ';
                }
                ?>


                <td width="57" valign="top" style="width:42.55pt;border-top:1px solid;border-left:
            none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
<!--                    <div align="center" style="text-align:center">--><?//= $Man->total_percent; ?><!--%</div>-->
                    <div align="center" style="text-align:center"><?= $balli_total; ?></div>
                </td>
                <td width="76" valign="top" style="width:2.0cm;border-top:none;border-left:none;
            border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
                    <div align="center" style="text-align:center"><?= $uroven; ?></div>
                </td>
         <!--       <td width="76" valign="top" style="width:2.0cm;border-top:none;border-left:none;
            border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
                    <div align="center" style="text-align:center"><?/*= $sert_num; */?></div>
                </td>-->
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div>&nbsp;</div>
<?php endforeach; ?>



<!--<div>&nbsp;</div>-->
<table>
    <tr>

        <td><b>Ответственный за проведение тестирования: &nbsp;&nbsp;&nbsp;</b></td>
        <td style="border-bottom:1px solid;" width=500px><b><?= $otvetst; ?></b></td>
        <td align=center><b>(Ф.И.О., подпись)</b></td>
    </tr>


</table>

<table border=0>

    <tr>
        <td colspan=3><b>Тесторы-члены комиссии:</b></td>
    </tr>

    <tr>
        <td><b>1.&nbsp;&nbsp;&nbsp;</b></td>
        <td style="border-bottom:1px solid;" width=500px><b
                style="border-bo ttom:1px solid; "><?php echo $act->tester1; ?></b>&nbsp;</td>
        <td align=center>(Ф.И.О., подпись)</td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td><b>2.&nbsp;&nbsp;&nbsp;</b></td>
        <td style="border-bottom:1px solid;" width=500px><b
                style="border-bo ttom:1px solid; "><?php echo $act->tester2; ?></b></td>
        <td align=center>(Ф.И.О., подпись)</td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
</table>

<table>
    <tr>

        <td colspan="2"><b>Результаты экзамена проверены, аудиофайлы прослушаны.</b></td>

    </tr>
    <tr>

        <td><b>Председатель комиссии&nbsp;&nbsp;&nbsp;</b></td>
        <td style="border-bottom:1px solid;" width=500px></td>
        <td align="left"><b>(Ф.И.О., подпись)</b></td>
    </tr>

</table>

</p>
</body>
</html>