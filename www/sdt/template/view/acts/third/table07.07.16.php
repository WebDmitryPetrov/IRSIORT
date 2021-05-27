<?php
/** @var Act $act */

$organization = $act->getUniversity()->name; //название орагнизации

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
    ?>

    <table width="100%" class=table cellspacing="0" cellpadding="0" border="1"
           style="border-collapse:collapse;border:none; width:100%">
        <tbody>
        <tr style="page-break-inside:avoid;
            height:8.0pt">
            <td width="36" valign="top" style="width:26.7pt;border:solid windowtext 1.0pt;height:8.0pt" rowspan="5">
                <div><b><span style="font-size:11.0pt">№</span></b></div>
                <div><b><span style="font-size:11.0pt">п/п</span></b></div>
            </td>

            <td width="104" valign="top" style="width:77.95pt;border:solid windowtext 1.0pt;
            border-left:none;height:8.0pt" rowspan="5">
                <div><b><span style="font-size:11.0pt">Фамилия</span></b></div>
                <div>русскими / латинскими</div>
            </td>
            <td width="104" valign="top" style="width:77.95pt;border:solid windowtext 1.0pt;
            border-left:none;height:8.0pt" rowspan="5">
                <div><b><span style="font-size:11.0pt">Имя</span></b></div>
                <div>русскими / латинскими</div>
            </td>
            <td width="95" valign="top" style="width:70.9pt;border:solid windowtext 1.0pt;
            border-left:none;height:8.0pt" rowspan="5">
                <div><b><span style="font-size:11.0pt">Страна</span></b></div>
            </td>
            <td width="85" valign="top" style="width:63.75pt;border:solid windowtext 1.0pt;
            border-left:none;height:8.0pt" rowspan="5">
                <div><b><span style="font-size:11.0pt">Дата тестиро&dash;<br>вания</span></b></div>
            </td>
            <td width="444" valign="top" style="width:333.15pt;border:solid windowtext 1.0pt;
            border-left:none;height:8.0pt" colspan="<?= $sub_tests_counter + 1 ?>">
                <div align="center" style="text-align:center"><b><span
                            style="font-size:11.0pt">Результаты (%)</span></b>
                </div>
            </td>
            <td width="76" valign="top" style="width:2.0cm;border:solid windowtext 1.0pt;
            border-left:1px solid;height:8.0pt" rowspan="5">
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

            $row1 = $row2 = $row3 = $row4 = '';
            foreach ($sub_tests as $sub_test) {
                $i++;

                $row1 .= '
            <td width="66" valign="top" style="width: 49.65pt; height: 14pt; border: medium none; border-right: 1px solid; text-align: center;">
              <div align="center" style="text-align:center"><span style="font-size:11.0pt">' . $i . '</span></div>

</td>';
                $row2 .= '
             <td width="66" valign="top" style="width: 49.65pt; height: 14pt; border: medium none; border-right: 1px solid; text-align: center;">
             <div align="center" style="text-align:center"><span style="font-size:11.0pt">' . $sub_test->caption . '</span></div>
            </td>
';
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

            }

            $row1.=' <td width="57" valign="top" style="width: 49.65pt; border-bottom: none; height: 14pt;  text-align: center;">
            <div align="center" style="text-align:center"><span style="font-size:11.0pt">Общ.</span></div>
            </td>';
             $row2.=' <td width="57" valign="top" style="width: 49.65pt; height: 14pt; border: medium none; text-align: center;">
            <div align="center" style="text-align:center"><span style="font-size:11.0pt">балл<br>%</span></div>
            </td>';
             $row3.=' <td width="57" valign="top" style="width: 49.65pt; height: 14pt; border: medium none; text-align: center;">
            <div align="center" style="text-align:center"><span style="font-size:11.0pt">100%</span></div>
            </td>';
             $row4.='<td width="57" valign="top" style="width: 49.65pt; height: 14pt; border: medium none; text-align: center;">
            <div align="center" style="text-align:center"><span style="font-size:11.0pt">'.$level->total.' б. </span></div>
            </td>';



            foreach(array($row1,$row2,$row3,$row4) as $r){
                echo '<tr  style="page-break-inside:avoid;height:14.0pt">'.$r.'</tr>';
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

                foreach ($sub_tests as $sub_test) {

                    $balli = str_replace(
                            '.',
                            ',',
                            sprintf('%01.1f', $subTestResults->getByOrder($sub_test->order)->percent)
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