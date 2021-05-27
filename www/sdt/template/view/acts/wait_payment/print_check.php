<?php
/**
 * @var Act $Act
 */

//$show_was = 1; //убрал 14.11.13 образец и реквизиты...чтобы включить - поменять на 1


//Плательщик
$platelschik = $Act->getUniversity()->form . ' &laquo;' . $Act->getUniversity()->name . '&raquo;';
//Адрес плательщика
$platelschik_adr = $Act->getUniversity()->legal_address;
//Счет номер
$schet_num = $Act->invoice;
$podr_index = $Act->invoice_index;
//var_dump($schet_num); die();
//Дата счета
$schet_date = date('d.m.Y');
//Договор номер
$dogovor_num = $Act->getUniversityDogovor()->number;
//Дата договора
$dogovor_date = date('d.m.Y', strtotime($Act->getUniversityDogovor()->date));
//Сумма счета
$schet_sum = str_replace('.', ',', sprintf("%01.2f", $Act->amount_contributions));
//Наименование-дата
$naim_date = date('d.m.Y', strtotime($Act->testing_date));
//Наименование-дата акта
$naim_akt_date = $schet_date;
//Общая сумма
$sum_total = $schet_sum;
$per = $Act->amount_contributions;
$signing = $Act->signing;


$nds_val=new \SDT\logic\Prices\Nds($Act->actDate());
$nds = str_replace('.', ',', $nds_val->getNdsPart($sum_total));

$kolichestvo = count($Act->getPeople());
$za_chto = 'Тестирование граждан зарубежных стран по русскому языку как иностранному<br>' . $naim_date . ' г.<br>';
$za_chto = '<div align=center style="padding: 5pt 0 5pt 0">Оплата  за услуги тестирования:<br>по установлению уровня владения РКИ/<br>для вступления в гражданство<br></div>';


?>

<html>


<head>
    <script>window.print();</script>
    <style type="text/css">
        .top {
            font-size: 12.0pt;
            color: black;
        }

        .sidebar-nav {
            padding: 9px 0;
        }

        .table {
            width: 100.0%;
            border-collapse: collapse;
            border: none;
        }

        .table div {
            font-weight: bold;
        }

        .table td, .table2 td {
            padding: 0cm 5.4pt 0cm 5.4pt;
        }

        .table3 td {
            padding: 0 15pt 0 0;
            font-weight: bold;
        }

        .top span {
            font-size: 12pt;
            color: black;
            font-weight: bold;
        }

        .table2 {
            width: 100.0%;
            border-collapse: collapse;
            border: none;
        }

        .table2 span {
            font-size: 9pt;
            color: black;
        }
    </style>


</head>

<body>


<?php
function num2str($num)
{
    $nul = 'ноль';
    $ten = array(
        array('', 'один', 'два', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'),
        array('', 'одна', 'две', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'),
    );
    $a20 = array(
        'десять',
        'одиннадцать',
        'двенадцать',
        'тринадцать',
        'четырнадцать',
        'пятнадцать',
        'шестнадцать',
        'семнадцать',
        'восемнадцать',
        'девятнадцать'
    );
    $tens = array(
        2 => 'двадцать',
        'тридцать',
        'сорок',
        'пятьдесят',
        'шестьдесят',
        'семьдесят',
        'восемьдесят',
        'девяносто'
    );
    $hundred = array(
        '',
        'сто',
        'двести',
        'триста',
        'четыреста',
        'пятьсот',
        'шестьсот',
        'семьсот',
        'восемьсот',
        'девятьсот'
    );
    $unit = array( // Units
        //array('копейка' ,'копейки' ,'копеек',	 1),
        array('коп.', 'коп.', 'коп.', 1),
        //array('рубль'   ,'рубля'   ,'рублей'    ,0),
        array('руб.', 'руб.', 'руб.', 0),
        array('тысяча', 'тысячи', 'тысяч', 1),
        array('миллион', 'миллиона', 'миллионов', 0),
        array('миллиард', 'милиарда', 'миллиардов', 0),
    );
    //
    list($rub, $kop) = explode('.', sprintf("%015.2f", floatval($num)));
    $out = array();
    if (intval($rub) > 0) {
        foreach (str_split($rub, 3) as $uk => $v) { // by 3 symbols
            if (!intval($v)) {
                continue;
            }
            $uk = sizeof($unit) - $uk - 1; // unit key
            $gender = $unit[$uk][3];
            list($i1, $i2, $i3) = array_map('intval', str_split($v, 1));
            // mega-logic
            $out[] = $hundred[$i1]; # 1xx-9xx
            if ($i2 > 1) {
                $out[] = $tens[$i2] . ' ' . $ten[$gender][$i3];
            } # 20-99
            else {
                $out[] = $i2 > 0 ? $a20[$i3] : $ten[$gender][$i3];
            } # 10-19 | 1-9
            // units without rub & kop
            if ($uk > 1) {
                $out[] = morph($v, $unit[$uk][0], $unit[$uk][1], $unit[$uk][2]);
            }
        } //foreach
    } else {
        $out[] = $nul;
    }
    $out[] = morph(intval($rub), $unit[1][0], $unit[1][1], $unit[1][2]); // rub
    $out[] = $kop . ' ' . morph($kop, $unit[0][0], $unit[0][1], $unit[0][2]); // kop
    return trim(preg_replace('/ {2,}/', ' ', join(' ', $out)));
}

/**
 * Склоняем словоформу
 * @ author runcore
 */
function morph($n, $f1, $f2, $f5)
{
    $n = abs(intval($n)) % 100;
    if ($n > 10 && $n < 20) {
        return $f5;
    }
    $n = $n % 10;
    if ($n > 1 && $n < 5) {
        return $f2;
    }
    if ($n == 1) {
        return $f1;
    }

    return $f5;
}

$per = num2str($per);


$pol_name_full =TEXT_HEADCENTER_LONG_IP;
$pol_name_short = TEXT_HEADCENTER_SHORT_VP;
$pol_addr = TEXT_HEADCENTER_ADDRESS_1;


?>




<div style="width: 180mm">
<table cellspacing="0" cellpadding="0" border="1"
       class=table>
    <tbody>
    <tr class=toptable>
        <td width="100%" valign="top"
            style="width: 100.0%; border: none; border-bottom: solid windowtext 1.0pt"
            colspan="5">
            <div>
							<span>
							<!--Получатель: --><?= $pol_name_full ?> <br>(<?= $pol_name_short ?>)
                            </span>
            </div>

            <div>
							<span>ОКВЭД <?=TEXT_HEADCENTER_OKVED?>,
                                ОКПО <?=TEXT_HEADCENTER_OKPO?>,
                                ОКТМО <?=TEXT_HEADCENTER_OKTMO?>,
                                ОГРН <?=TEXT_HEADCENTER_OGRN?>
                            </span>
            </div>
            <div>
							<span>Юридический адрес: <?=TEXT_HEADCENTER_LEGAL_ADDRESS_1?>
                            </span>
            </div>
            <div>
							<span>Получатель: <?=TEXT_HEADCENTER_CHECK_RECEIVER?>
</span>
            </div>
            <div>
							<span>Сч.№ <?=TEXT_HEADCENTER_CHECK_N?>  БИК <?=TEXT_HEADCENTER_BIK?>
</span>
            </div>
            <div>
							<span><?=TEXT_HEADCENTER_BANK_1?>
</span>
            </div>
            <div>
							<span>ИНН <?=TEXT_HEADCENTER_BANK_INN?>
                                КПП <?=TEXT_HEADCENTER_BANK_KPP?>
</span>
            </div>
            <div style="margin-bottom:20px">
							<span>КБК <?=TEXT_HEADCENTER_BANK_KBK?>
</span>
            </div>


        </td>
    </tr>
    <tr>
        <td width="100%" valign="top" colspan="5">
            <table class=table3>
                <tr>
                    <td>
                        Плательщик:
                    </td>
                    <td>
                        <i><?= $platelschik ?> </i>
                    </td>
                </tr>
                <tr>
                    <td>
                        Адрес:
                    </td>
                    <td>
                        <i><?= $platelschik_adr ?> </i>
                    </td>
                </tr>
            </table>
        </td>
    </tr>


    <tr>
        <td width="100%" valign="top" colspan="5">
            <table class=table3>
                <tr>
                    <td>
                        Получатель:&nbsp;
                    </td>
                    <td>
                        <i><?= $pol_name_short ?></i>
                    </td>
                </tr>
                <tr>
                    <td>
                        Адрес:
                    </td>
                    <td>
                        <?= $pol_addr ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td width="100%"
            style="width: 100.0%; border: solid windowtext 1.0pt; border-top: none"
            colspan="5">
            <div align="center">
							<span style="font-size: 18.0pt">СЧЕТ № <?= $podr_index; ?> / <?= $schet_num ?>
                                от <?= $schet_date ?>г.
							</span>
            </div>
        </td>
    </tr>
    <tr>
        <td width="55%"
            style="width: 55.26%; border: solid windowtext 1.0pt; border-top: none"
            rowspan="2" colspan="2">
            <div align="center">
                <span style="font-size: 13.0pt">Договор </span><span
                    style="font-size: 12.0pt">№ <?= $dogovor_num ?> от <?= $dogovor_date ?>
                    г.
							</span>
            </div>
        </td>
        <td width="44%" valign="top" colspan="3">
            <div align="center">
							<span style="font-size: 14.0pt; margin-right: 2.85pt; ">Сумма
								счета (руб.)</span>
            </div>
        </td>
    </tr>
    <tr style="height: 29.85pt">
        <td width="44%" colspan="3">
            <div align="right">
							<span style="font-size: 14.0pt; margin-right: 2.85pt;"><?= $schet_sum ?>
							</span>
            </div>
        </td>
    </tr>
    <tr>
        <td width="46%" valign="top"
            style="width: 46.32%; border: solid windowtext 1.0pt; border-top: none">
            <div align="center">
                <span style="font-size: 12.0pt;">Наименование</span>
            </div>
        </td>
        <td width="8%" valign="top">
            <div align="center">
							<span style="font-size: 12.0pt;">Един.<br>изм.
							</span>
            </div>
        </td>
        <td width="11%" valign="top">
            <div align="center">
                <span style="font-size: 12.0pt;">Кол-во</span>
            </div>
        </td>
        <td width="8%" valign="top">
            <div align="center">
                <span style="font-size: 12.0pt;">Цена</span>
            </div>
        </td>
        <td width="24%" valign="top">
            <div align="center">
                <span style="font-size: 12.0pt;">Сумма</span>
            </div>
        </td>
    </tr>
    <tr>
        <td width="46%" valign="top"
            style="width: 46.32%; border: solid windowtext 1.0pt; border-top: none; border-bottom: 0">
            <div>
							<span style="font-size: 12.0pt"><?php echo $za_chto ?>
                                <!--Акт от <?=$naim_akt_date ?> г.--><br>
							</span>
            </div>
        </td>
        <td width="9%" valign="top" style="vertical-align: middle" rowspan="2">
            <div align="center">руб.</div>
        </td>
        <td width="11%" valign="top" style="vertical-align: middle" rowspan="2">
            <div align="center"><?php echo $kolichestvo ?></div>
        </td>
        <td width="8%" valign="top" rowspan="2">
            <div align="center">&nbsp;</div>
        </td>
        <td width="24%" style="vertical-align:middle; border-bottom: 0">
            <div align="right">
							<span style="font-size: 14.0pt;"> <?= $sum_total ?> <br>
						</span>
            </div>
        </td>
    </tr>
    <tr>
        <?/*<td align=right style="border-top: 0"><span style="font-size: 12.0pt;"><div align=center
                                                                                    style="padding: 5pt 0 5pt 0">В т.ч.
                    НДС 20%
                </div></span></td>
        <td align=right style="border-top: 0"><span style="font-size: 14.0pt;"><div
                    style="padding: 5pt 0 5pt 0"><?= str_replace('.', '-', $nds); ?></div></span></td>*/?>
    </tr>
    <tr>
        <td width="100%" valign="top" colspan="5">
            <div>
                <span style="font-size: 12.0pt;">Сумма прописью:</span><span
                    style="font-size: 14.0pt;"><i> <? echo ucfirst($per); ?> </i>
							</span>
            </div>
        </td>
    </tr>
    </tbody>
</table>
<br> <br> <br>

<div style="margin-bottom: 6.0pt; width: 100%; font-weight: bold;padding-left: 10px">
    <span style="font-size: 16.0pt; float: left;"><?php echo $Act->getSigning()->position ?></span><span
        style="font-size: 16.0pt; float: right; margin-right: 20px"><?php echo $Act->getSigning()->caption ?></span>
</div>
<br> <br> <br>





<table border="0" cellspacing="0" cellpadding="0" width="601"
       style="wi dth:450.75pt;width:100%;margin-left:4.65pt;border-collapse:collapse;">
    <tbody>
    <tr style="height:12.75pt;">
        <td width="287" valign="bottom" nowrap="" colspan="3"
            style="width: 150pt; padding: 0cm 5.4pt; height: 12.75pt; background-position: initial initial; background-repeat: initial initial;">
            <div><b><span style="font-size:10.0pt">Образец заполнения   платежного поручения</span></b></div>
        </td>
        <td width="32" valign="bottom" nowrap="" style="padding: 0cm 5.4pt; height: 12.75pt; width: 36.85pt;">
            <div>&nbsp;</div>
        </td>
        <td width="72" valign="bottom" nowrap="" colspan="4"
            style="padding: 0cm 5.4pt; height: 12.75pt; width: 41.2pt;">
            <div>&nbsp;</div>
        </td>
        <td width="18" valign="bottom" nowrap="" style="padding: 0cm 5.4pt; height: 12.75pt; width: 0.3pt;">
            <div>&nbsp;</div>
        </td>
        <td width="59" valign="bottom" nowrap="" colspan="2"
            style="padding: 0cm 5.4pt; height: 12.75pt; width: 13.2pt;">
            <div>&nbsp;</div>
        </td>
        <td width="18" valign="bottom" nowrap="" colspan="2"
            style="padding: 0cm 5.4pt; height: 12.75pt; width: 30.3pt;">
            <div>&nbsp;</div>
        </td>
        <td width="66" valign="bottom" nowrap="" colspan="2"
            style="padding: 0cm 5.4pt; height: 12.75pt; width: 37.2pt;">
            <div>&nbsp;</div>
        </td>
        <td width="44" valign="bottom" nowrap="" colspan="2" style="padding: 0cm 5.4pt; height: 12.75pt; width: 0pt;">
            <div>&nbsp;</div>
        </td>
        <td width="6" style="border-style: none none solid; border-bottom: 1pt solid windowtext; width: 100px;">
            <div>&nbsp;</div>
        </td>
    </tr>
    <tr style="height:12.75pt">
        <td width="361" colspan="6"
            style="width: 270.8pt; border-style: solid solid none; border-top-color: windowtext; border-top-width: 1pt; border-left-color: windowtext; border-left-width: 1pt; border-right-color: black; border-right-width: 1pt; padding: 0cm 5.4pt; height: 12.75pt; background-position: initial initial; background-repeat: initial initial;">
            <div><span style="font-size:10.0pt"><?=TEXT_HEADCENTER_BANK_2?></span></div>
        </td>
        <td width="72" colspan="4"
            style="width: 53.95pt; border-style: solid solid solid none; border-top-color: windowtext; border-top-width: 1pt; border-bottom-color: windowtext; border-bottom-width: 1pt; border-right-color: black; border-right-width: 1pt; padding: 0cm 5.4pt; height: 12.75pt; background-position: initial initial; background-repeat: initial initial;">
            <div><span style="font-size:10.0pt">БИК</span></div>
        </td>
        <td width="168" valign="top" colspan="8" rowspan="3"
            style="width: 126pt; border-style: solid solid none none; border-top-color: windowtext; border-top-width: 1pt; border-right-color: black; border-right-width: 1pt; padding: 0cm 5.4pt; height: 12.75pt; background-position: initial initial; background-repeat: initial initial;">
            <div><span style="font-size:10.0pt"><?=TEXT_HEADCENTER_BIK?></span></div>
        </td>
    </tr>
    <tr style="height:12.75pt">
        <td width="361" colspan="6"
            style="width: 270.8pt; border-style: none solid; border-left-color: windowtext; border-left-width: 1pt; border-right-color: black; border-right-width: 1pt; padding: 0cm 5.4pt; height: 12.75pt; background-position: initial initial; background-repeat: initial initial;">
            <div><span style="font-size:10.0pt"><?=TEXT_HEADCENTER_BANK_3?></span></div>
        </td>
        <td width="72" valign="top" colspan="4" rowspan="2"
            style="width: 53.95pt; border-style: none solid solid none; border-bottom-color: black; border-bottom-width: 1pt; border-right-color: black; border-right-width: 1pt; padding: 0cm 5.4pt; height: 12.75pt; background-position: initial initial; background-repeat: initial initial;">
            <div><span style="font-size:10.0pt">Сч. №</span></div>
        </td>
    </tr>
    <tr style="height:12.75pt">
        <td width="361" colspan="6"
            style="width: 270.8pt; border-style: none solid solid; border-left-color: windowtext; border-left-width: 1pt; border-bottom-color: windowtext; border-bottom-width: 1pt; border-right-color: black; border-right-width: 1pt; padding: 0cm 5.4pt; height: 12.75pt; background-position: initial initial; background-repeat: initial initial;">
            <div><span style="font-size:10.0pt">Банк получателя</span></div>
        </td>
    </tr>
    <tr style="height:12.75pt">
        <td width="287" valign="bottom" colspan="3"
            style="width: 215pt; border-style: none solid solid; border-left-color: windowtext; border-left-width: 1pt; border-bottom-color: windowtext; border-bottom-width: 1pt; border-right-color: black; border-right-width: 1pt; padding: 0cm 5.4pt; height: 12.75pt; background-position: initial initial; background-repeat: initial initial;">
            <div><span style="font-size:10.0pt">ИНН&nbsp; <?=TEXT_HEADCENTER_BANK_INN?></span></div>
        </td>
        <td width="74" valign="bottom" colspan="3"
            style="width: 55.8pt; border-style: none solid solid none; border-bottom-color: windowtext; border-bottom-width: 1pt; border-right-color: black; border-right-width: 1pt; padding: 0cm 5.4pt; height: 12.75pt; background-position: initial initial; background-repeat: initial initial;">
            <div><span style="font-size:10.0pt">КПП&nbsp; <?=TEXT_HEADCENTER_BANK_KPP?></span></div>
        </td>
        <td width="72" valign="top" colspan="4" rowspan="2"
            style="width: 53.95pt; border-style: none solid solid none; border-bottom-color: black; border-bottom-width: 1pt; border-right-color: black; border-right-width: 1pt; padding: 0cm 5.4pt; height: 12.75pt; background-position: initial initial; background-repeat: initial initial;">
            <div><span style="font-size:10.0pt">Сч. №</span></div>
        </td>
        <td width="168" valign="top" colspan="8" rowspan="2"
            style="width: 126pt; border-style: none solid solid none; border-bottom-color: black; border-bottom-width: 1pt; border-right-color: black; border-right-width: 1pt; padding: 0cm 5.4pt; height: 12.75pt; background-position: initial initial; background-repeat: initial initial;">
            <div><span style="font-size:10.0pt"><?=TEXT_HEADCENTER_CHECK_N?></span></div>
        </td>
    </tr>
    <tr style="height:12.75pt">
        <td width="361" valign="top" colspan="6" rowspan="2"
            style="width: 270.8pt; border-style: none solid; border-left-color: windowtext; border-left-width: 1pt; border-right-color: black; border-right-width: 1pt; padding: 0cm 5.4pt; height: 12.75pt; background-position: initial initial; background-repeat: initial initial;">
            <div><span style="font-size:10.0pt"><?=TEXT_HEADCENTER_CHECK_RECEIVER?></span></div>
        </td>
    </tr>
    <tr style="height:12.75pt">
        <td width="72" valign="bottom" colspan="4"
            style="width: 53.95pt; border-style: none solid solid none; border-bottom-color: windowtext; border-bottom-width: 1pt; border-right-color: black; border-right-width: 1pt; padding: 0cm 5.4pt; height: 12.75pt; background-position: initial initial; background-repeat: initial initial;">
            <div><span style="font-size:10.0pt">Вид оп.</span></div>
        </td>
        <td width="48" valign="top" colspan="2" rowspan="3"
            style="width: 36pt; border-style: none solid solid none; border-bottom-color: black; border-bottom-width: 1pt; border-right-color: windowtext; border-right-width: 1pt; padding: 0cm 5.4pt; height: 12.75pt; background-position: initial initial; background-repeat: initial initial;">
            <div align="center" style="text-align:center">&nbsp;</div>
        </td>
        <td width="84" valign="bottom" colspan="4"
            style="width: 63pt; border-style: none solid solid none; border-bottom-color: windowtext; border-bottom-width: 1pt; border-right-color: black; border-right-width: 1pt; padding: 0cm 5.4pt; height: 12.75pt; background-position: initial initial; background-repeat: initial initial;">
            <div><span style="font-size:10.0pt">Срок плат.</span></div>
        </td>
        <td width="36" valign="bottom" colspan="2" rowspan="3"
            style="width: 27pt; border-style: none solid solid none; border-bottom-color: black; border-bottom-width: 1pt; border-right-color: black; border-right-width: 1pt; padding: 0cm 5.4pt; height: 12.75pt; background-position: initial initial; background-repeat: initial initial;">
            <div align="center" style="text-align:center">&nbsp;</div>
        </td>
    </tr>
    <tr style="height:12.75pt">
        <td width="361" valign="bottom" colspan="6" rowspan="2"
            style="width: 270.8pt; border-style: none solid solid; border-left-color: windowtext; border-left-width: 1pt; border-bottom-color: black; border-bottom-width: 1pt; border-right-color: black; border-right-width: 1pt; padding: 0cm 5.4pt; height: 12.75pt; background-position: initial initial; background-repeat: initial initial;">
            <div><span style="font-size:10.0pt">Получатель</span></div>
        </td>
        <td width="72" valign="bottom" colspan="4"
            style="width: 53.95pt; border-style: none solid solid none; border-bottom-color: windowtext; border-bottom-width: 1pt; border-right-color: black; border-right-width: 1pt; padding: 0cm 5.4pt; height: 12.75pt; background-position: initial initial; background-repeat: initial initial;">
            <div><span style="font-size:10.0pt">Наз. пл.</span></div>
        </td>
        <td width="84" valign="bottom" colspan="4"
            style="width: 63pt; border-style: none solid solid none; border-bottom-color: windowtext; border-bottom-width: 1pt; border-right-color: black; border-right-width: 1pt; padding: 0cm 5.4pt; height: 12.75pt; background-position: initial initial; background-repeat: initial initial;">
            <div><span style="font-size:10.0pt">Очер. плат.</span></div>
        </td>
    </tr>
    <tr style="height:12.75pt">
        <td width="72" valign="bottom" colspan="4"
            style="width: 53.95pt; border-style: none solid solid none; border-bottom-color: windowtext; border-bottom-width: 1pt; border-right-color: black; border-right-width: 1pt; padding: 0cm 5.4pt; height: 12.75pt; background-position: initial initial; background-repeat: initial initial;">
            <div><span style="font-size:10.0pt">Код</span></div>
        </td>
        <td width="84" valign="bottom" colspan="4"
            style="width: 63pt; border-style: none solid solid none; border-bottom-color: windowtext; border-bottom-width: 1pt; border-right-color: black; border-right-width: 1pt; padding: 0cm 5.4pt; height: 12.75pt; background-position: initial initial; background-repeat: initial initial;">
            <div><span style="font-size:10.0pt">Рез. поле</span></div>
        </td>
    </tr>
    <tr style="height:12.75pt">
        <td width="169"
            style="width: 126.75pt; border-style: none solid solid; border-left-color: windowtext; border-left-width: 1pt; border-bottom-color: windowtext; border-bottom-width: 1pt; border-right-color: black; border-right-width: 1pt; padding: 0cm 5.4pt; height: 12.75pt; background-position: initial initial; background-repeat: initial initial;">
            <div><span style="font-size:10.0pt"><?=TEXT_HEADCENTER_BANK_KBK?></span></div>
        </td>
        <td width="96" nowrap=""
            style="width: 72pt; border-style: none solid solid none; border-bottom-color: windowtext; border-bottom-width: 1pt; border-right-color: black; border-right-width: 1pt; padding: 0cm 5.4pt; height: 12.75pt; background-position: initial initial; background-repeat: initial initial;">
            <div><span style="font-size:10.0pt"><?=TEXT_HEADCENTER_OKTMO?></span></div>
        </td>
        <td width="60" colspan="3"
            style="width: 45pt; border-style: none solid solid none; border-bottom-color: windowtext; border-bottom-width: 1pt; border-right-color: windowtext; border-right-width: 1pt; padding: 0cm 5.4pt; height: 12.75pt; background-position: initial initial; background-repeat: initial initial;">
            <div align="center" style="text-align:center"><span style="font-size:10.0pt">0</span></div>
        </td>
        <td width="60" nowrap="" colspan="2"
            style="width: 45pt; border-style: none solid solid none; border-bottom-color: windowtext; border-bottom-width: 1pt; border-right-color: black; border-right-width: 1pt; padding: 0cm 5.4pt; height: 12.75pt; background-position: initial initial; background-repeat: initial initial;">
            <div align="center" style="text-align:center"><span style="font-size:10.0pt">0</span></div>
        </td>
        <td width="96"
            style="border-style: none solid solid none; border-bottom: 1pt solid windowtext; border-right: 1pt solid black; padding: 0cm 5.4pt; height: 12.75pt; width: 54pt;"
            colspan="6">
            <div align="center" style="text-align:center"><span style="font-size:10.0pt">0</span></div>
        </td>
        <td width="60"
            style="width: 45pt; border-style: none solid solid none; border-bottom-color: windowtext; border-bottom-width: 1pt; border-right-color: black; border-right-width: 1pt; padding: 0cm 5.4pt; height: 12.75pt; background-position: initial initial; background-repeat: initial initial;"
            colspan="4">
            <div align="center" style="text-align:center"><span style="font-size:10.0pt">0</span></div>
        </td>
        <td width="60" colspan="4"
            style="width: 45pt; border-style: none solid solid none; border-bottom-color: windowtext; border-bottom-width: 1pt; border-right-color: windowtext; border-right-width: 1pt; padding: 0cm 5.4pt; height: 12.75pt; background-position: initial initial; background-repeat: initial initial;"
            x:num="">
            <div align="center" style="text-align:center"><span style="font-size:10.0pt">0</span></div>
        </td>
    </tr>
    <tr style="height:12.75pt">
        <td width="601" valign="top" colspan="18"
            style="width: 450.75pt; padding: 0cm 5.4pt; height: 12.75pt; background-position: initial initial; background-repeat: initial initial;">
            <div><span style="font-size:10.0pt">Оплата по сч. № 24/ХХХХ от   "__" _____ г. за услуги тестирования согл. дог. № ____ от   "__" _____ г.<br>Акт от "__" _____ г. <?/*в т.ч. НДС 20% -   ____,__ руб.*/?></span>
            </div>
        </td>
    </tr>
    <tr style="height:12.75pt;">
        <td width="703" valign="bottom" colspan="18"
            style="width: 527pt; border-style: none none solid; border-bottom-color: windowtext; border-bottom-width: 1pt; padding: 0cm 5.4pt; height: 12.75pt; background-position: initial initial; background-repeat: initial initial;">
            <div><span style="font-size:10.0pt">Назначение платежа</span></div>
        </td>
    </tr>
    <tr style="height:25.5pt;">
        <td width="703" valign="bottom" colspan="18" rowspan="3"
            style="width: 527pt; border: none; padding: 0cm 5.4pt; height: 25.5pt; background-position: initial initial; background-repeat: initial initial;">
            <div align="center" style="text-align:center; font-size:11.0pt">
                <br><b>В Н И М А Н И Е !</b>
                <br>При заполнении поля "Получатель" необходимо строго соблюдать
                <br>регистры, наличие/отсутствие пробелов между знаками.
                <br><b>Особое внимание: в номере лицевого счета&nbsp; 20736Х13430 - <u>"Х" - заглавная "икс" в
                        латинице</u></b></div>
        </td>
    </tr>
    </tbody>
</table>
</div>


</body>
</html>
