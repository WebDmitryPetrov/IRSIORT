<?php
/** @var ActMan $Man */
header("Content-Type: text/html; charset=windows-1251");
$type = 1;
if (!empty($_GET['type']) && is_numeric($_GET['type'])

) {
    $type = $_GET['type'];
}
$sign = ActSigning::getByID($type);
if (!$sign) {
    $sign = ActSigning::getByID(1);
}

$rukovod_dolzhn_1 = $sign->position;
$rukovod_fio = $sign->caption;


$gct_full_name = SIGNING_CENTER_NAME;
//$gct_short_name='ГЦТРКИ'; не используется


//Наименование ВУЗа


$vuz_name = RKI_RUDN_NAME;
$vuz_form = RKI_RUDN_FORM;


?>
<html>
<script>window.print();</script>
<body>
<?
$counter = count($persons);
//echo $counter;
$ccc = 0;
foreach ($persons as $Man) {
  /*  if ($Man->blank_date == '0000-00-00' || is_null($Man->blank_date)) {
        $Man->blank_date = date("Y-m-d");
//        $Man->setValidTill();
        $Man->save();
    }*/


    if (empty($Man->blank_date) || $Man->blank_date == '0000-00-00') {
          $Man->blank_date = $Man->getAct()->getPrintDateAfterCheckDate();
        /*$now = new DateTime();
        $checkDate = new DateTime($Man->getAct()->check_date);
        if($now>=$checkDate)
        {
            $Man->blank_date = date('Y-m-d');
        }
        else{
            $Man->blank_date = $checkDate->format('Y-m-d');
        }*/

//        $Man->setValidTill();
        $Man->save();
    }



//Серия сертификата


//Номер сертификата

    $sert_num = '№ ' . $Man->blank_number;
//Кому выдано
    $vidano = $Man->surname_rus . ' ' . $Man->name_rus;
    $Level = $Man->getTest()->getLevel();
    $uroven = $Level->print_note;
//Страна
    $strana = $Man->getCountry()->name;

    $balli_total = sprintf('%01.1f', $Man->total_percent);

//разделы:
    $razdeli_peresdacha = '';
// 		Названия разделов

//		вывод оценки и пересдач
    $peresdacha = 0;
    $peresdacha_rus = 0;
    $peresdacha_addit = 0;

    if ($razdeli_peresdacha != '') {
        $razdeli_peresdacha = substr($razdeli_peresdacha, 0, -2) . '.';
    }

    $razdeli_peresdacha = array();

    $balli_total = str_replace('.', ',', $balli_total);


    $print_date = $Man->getAct()->getPrintDateAfterCheckDate();

//    $date_day = date('d');
    $date_day = date('d',strtotime($print_date));
//    $date_month = date('m');
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

//    $dateYear = date('Y');
    $dateYear = date('Y',strtotime($print_date));
    ?>

    <div style="width:180mm">

        <div align="center" style="margin-right:1.0cm;text-align:center"><b>Министерство науки и высшего образования Российской Федерации</b></div>
        <div align="center" style="margin-right:1.0cm;text-align:center"><b>Государственная система тестирования
                граждан</b>
        </div>
        <div align="center" style="margin-right:1.0cm;text-align:center"><b>зарубежных стран по русскому языку</b></div>
        <div align="center" style="margin-right:1.0cm;text-align:center"><span
                style="font-size:9.0pt"><b><?php echo $vuz_form; ?></b></span></div>
        <div align="center" style="margin-right:1.0cm;text-align:center;"><b><u><?= $vuz_name; ?></u></b></div>
        <div style="margin-right:1.0cm">&nbsp;</div>
        <div align="center" style="margin-right:1.0cm;text-align:center"><b><?php echo $gct_full_name; ?></b></div>
        <div style="margin-right:1.0cm">&nbsp;</div>
        <div align="center" style="margin-right:1.0cm;text-align:center"><b>РУССКИЙ ЯЗЫК КАК ИНОСТРАННЫЙ</b></div>
        <div align="center" style="margin-right:1.0cm;text-align:center"><b><span
                    style="font-size:20.0pt;">Справка</span></b>
        </div>
        <div align="center" style="margin-right:1.0cm;text-align:center"><b><u><span
                        style="font-size:16.0pt;"><?= $sert_num; ?></span></u></b><b><u><span
                        style="font-size:16.0pt;"></span></u></b>
        </div>
        <!--<div align="center" style="margin-right:1.0cm;text-align:center">&nbsp;</div>-->
        <div align="center" style="margin-right:1.0cm;text-align:center">удостоверяет, что</div>
        <!--<div align="center" style="margin-right:1.0cm;text-align:center">&nbsp;</div>-->
        <div align="center" style="margin-right:1.0cm;text-align:center"><b><font size="5"><u><?= $vidano; ?></u></font></b>
        </div>
        <div align="center" style="margin-right:1.0cm;text-align:center">(Ф.И.О.)</div>
        <div align="center" style="margin-right:1.0cm;text-align:center"><b><font size="5"><u><?= $strana; ?></u></font></b>
        </div>
        <div align="center" style="margin-right:1.0cm;text-align:center">(страна)</div>
        <!--<div align="center" style="margin-right:1.0cm;text-align:center">&nbsp;</div>-->
        <div style="margin-left:36.0pt;text-align:justify;
text-indent:0cm;line-height:150%">проходил(а) тестирование по русскому языку в объёме <b><?= $uroven; ?></b> уровня и
            показал(а) следующие результаты по разделам теста:
        </div>
        <div style="margin-right:1.0cm;text-align:justify">&nbsp;</div>

        <div align="center">
            <table border="1" cellspacing="0" cellpadding="0" style="border-collapse:collapse;border:none;">
                <tbody>

                <tr>
                    <td width="211" colspan=2 valign="top"
                        style="width:158.4pt;border:solid white 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
                        <div style="marg in-right:1.0cm;text-align:center"><b>Раздел</b></div>
                    </td>
                    <td width="132" valign="top" style="width:115.0pt;border:solid white 1.0pt;
            border-left:none;
            padding:0cm 5.4pt 0cm 5.4pt">
                        <div style="ma rgin-right:1.0cm;text-align:center"><b>Процент<br>правильных ответов</b></div>
                    </td>
                    <td width="132" valign="top" style="width:99.0pt;border:solid white 1.0pt;
            border-left:none;
            padding:0cm 5.4pt 0cm 5.4pt">
                        <div style="ma rgin-right:1.0cm;text-align:center"><b>Оценка<br>(удовлетворительно,<br>неудовлетворительно)<br><br></b>
                        </div>
                    </td>

                </tr>


                <?
                /** @var SubTest[] $sub_tests */
                $sub_tests = SubTests::getByLevel($Level);
                //die(var_dump($sub_tests));
                $subTestResults = $Man->getResults();
                $li = $min_passed = $i = 0;
                foreach ($sub_tests as $sub_test) {
                    $li++;
                    $i++;
                    //echo $sub_test->caption;
//die(var_dump(SubTestResults::getByMan($Man)->getByOrder($sub_test->order)->percent));

                    /** @var  SubTestMeta[] $sub_test_meta_array */
                    $sub_test_meta_array = array();
                    //$sub_test_groups_meta_array=array();
                    //$sub_test_groups=array();

                    foreach ($sub_tests as $s_key => $s_val) {
                        if (!empty($s_val->meta)) {
                            //$sub_test_groups_meta_array[$s_val->meta->group_id][]=$s_val->meta->num;
                            $sub_test_meta_array[$s_val->meta->num] = $s_val->meta;
                        }
                    }


                    if (!empty($sub_test_meta_array[$i]->vedomost_caption)) {
                        $vedomost_caption = '<nobr>' . $sub_test_meta_array[$i]->vedomost_caption . '</nobr>';
                    } else {
                        //$vedomost_caption = 'Субтест ' . (array_search($i,$sub_test_groups[$i])+1);
                        $vedomost_caption = '';
                    }

                    if (!empty($sub_test_meta_array[$i]->percent_show)) {
                        $percent_show = $sub_test_meta_array[$i]->percent_show;
                    } else {
                        $percent_show = 100;
                    }


                    $percent = $subTestResults->getByOrder($sub_test->order)->percent;

                    if (empty($sub_test_meta_array[$sub_test->order])) {
                        if (!empty($sub_test->pass_score) && $percent >= $sub_test->pass_score) {
                            $ocenka = 'удовлетворительно';
                        } //            else if (empty($sub_test->pass_score) && $percent >= $Level->percent_max)
                        else if (
                            empty($sub_test->pass_score)
                            && (
                                (empty($min_passed) && $percent >= $Level->percent_min)
                                || (!empty($min_passed) && $percent >= $Level->percent_max)
                            )
                        ) {
                            if ($percent >= $Level->percent_min && $percent < $Level->percent_max) {
                                $min_passed++;
                            }
                            $ocenka = 'удовлетворительно';
                        } else {
                            $ocenka = 'неудовлетворительно';
                            $razdeli_peresdacha[] = '&laquo;' . $sub_test->full_caption . '&raquo;';
                            $peresdacha++;
                            if (!empty($sub_test->pass_score)) $peresdacha_addit++;
                            else $peresdacha_rus++;
                        }
                    } else {
                        $currentBall =  $subTestResults->getByOrder($sub_test->order)->balls;

                        $meta = $sub_test_meta_array[$sub_test->order];
                        if ($meta->getGroup()->formula == 'hist2016') {
                            if ($meta->formula_var == 'a') {
                                $nextBall = $subTestResults->getByOrder($sub_test->order + 1)->balls;
                                $var = $currentBall + $nextBall;
//                                var_dump($var);
                                if (($var) >= 100) {
                                    $ocenka = 'удовлетворительно';
									$peresdacha_a=0;
                                } else {
                                    $ocenka = 'неудовлетворительно';
                                    //$razdeli_peresdacha[] = '&laquo;' . $sub_test->full_caption . '&raquo;';
                                    //$peresdacha++;
                                    //$peresdacha_addit++;
                                    $peresdacha_a=1;
                                }

                            }
                            if ($meta->formula_var == 'b') {
                                if ($currentBall >= 10) 
								{
									$ocenka = 'удовлетворительно';
									$peresdacha_b=0;
								}
                                else {
                                    $ocenka = 'неудовлетворительно';
                                    //$razdeli_peresdacha[] = '&laquo;' . $sub_test->full_caption . '&raquo;';
                                    //$peresdacha++;
                                    //$peresdacha_addit++;
									$peresdacha_b=1;
                                }
							
							if (!empty($peresdacha_a) || !empty($peresdacha_b))
							{
								$peresdacha++;
                                $peresdacha_addit++;
								$razdeli_peresdacha[] = '&laquo;' . $sub_test->full_caption . '&raquo;';
								$peresdacha_a = $peresdacha_b = 0;
							}
								
                            }
							

                        }
                    }
                    $percent = $percent * $percent_show / 100;
                    /* if ($percent < $Level->percent_max || (!empty($sub_test->pass_score) && $percent < $sub_test->pass_score)) {

                         $ocenka = 'неудовлетворительно';
                         $razdeli_peresdacha[] = '&laquo;' . $sub_test->full_caption . '&raquo;';
                         $peresdacha++;
                         if (!empty($sub_test->pass_score)) $peresdacha_addit++;
                         else $peresdacha_rus++;
                     } else {
                         $ocenka = 'удовлетворительно';
                     }*/

                    echo '<tr>
            <td width="26" valign="top" style="width:10pt;border:solid white 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
                <div>' . $li . '.</div>
            </td>
            <td width="185" valign="top" style="width:278pt;border:solid white 1.0pt;
            border-left:none;
            padding:0cm 5.4pt 0cm 5.4pt">
                <div>' . $sub_test->full_caption . ' ' . $vedomost_caption . '</div>
            </td>
            <td width="132" valign="top" style="width:99.0pt;border-top:none;border-left:
            none;border-bottom:solid white 1.0pt;border-right:solid white 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
                <div align="center" style="ma rgin-right:15.8pt;text-align:center"><b>' . str_replace('.', ',', sprintf('%01.1f', $percent)) . '&nbsp;</b></div>
            </td>
            <td width="295" valign="top" style="width:221.15pt;border:solid white 1.0pt;
            border-left:none;
            padding:0cm 5.4pt 0cm 5.4pt">
                <div style="margin-left:45.4pt"><b>' . $ocenka . '</b></div>
            </td>
        </tr>';


                }

                /*$peresdacha=1;
                $razdeli_peresdacha='&laquo;АДпапорл&raquo;';*/


                //17.02.15        if ($peresdacha > 1 || $Man->getTest()->level_id == 1) {


                if ($peresdacha > 2 || $Man->getTest()->level_id == 1 || $peresdacha_addit > 1 || $peresdacha_rus > 1) {
                    $peresdacha_text_1 = 'всем';
                    $peresdacha_text_2 = '.';
//            $razdeli_peresdacha = '';
                    $razdeli_peresdacha = array();
                } else {
                    $peresdacha_text_1 = '';
                    $peresdacha_text_2 = ':';

                }


                ?>


                </tbody>
            </table>
        </div>
        <div align="center" style="margin-right:21.25pt;text-align:center">&nbsp;</div>
        <div align="center" style="margin-left:9.0pt;text-align:center;
text-indent:9.0pt">Для получения сертификата должен пройти повторное тестирование по <?= $peresdacha_text_1 ?>
            разделам<?= $peresdacha_text_2 ?></div>
        <div align="center" style="margin-left:9.0pt;text-align:center;
text-indent:9.0pt"><? if (!empty($razdeli_peresdacha)) echo implode(',', $razdeli_peresdacha) . '.'; ?></div>
        <!--<div style="margin-left:9.0pt;text-align:justify;text-indent:
        9.0pt">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
        <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>-->
        <div style="margin-right:21.25pt"><b>&nbsp;</b></div>

        <table style="width:100%" border=0>

            <tr>

                <td style="width:10%; padd ing-right:10px"></td>
                <td align=right style="width:90%;"><b><?= $rukovod_dolzhn_1 ?></b></td>


            </tr>


            <tr>
                <td colspan=2 style="line-height: 14px;"><br></td>
            </tr>

            <tr>
                <td></td>


                <td align=right>______________________ <b><?= $rukovod_fio ?></b></td>


            </tr>


            <tr style="height:15px">
                <td colspan=2></td>
            </tr>


            <tr>
                <td></td>


                <td align=right><b>&laquo;<?= $date_day ?>&raquo; <?= $date_month ?> <?= $dateYear ?> г.</b></td>


            </tr>

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