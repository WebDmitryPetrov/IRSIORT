<table width="100%" cellspacing="0" cellpadding="0" border="1" class="main_td"
       style="width:100.0%;border-collapse:collapse;border:none;">
    <tbody>
    <tr>
        <td style="width:17.78%;border:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center" class="act_test_head">
                <b>уровень</b>
        </div>
        </td>

        <td colspan="3" style="width:17.78%;border:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center" class="act_test_head"><b>экзамен</b>
        </td>

        <? //if ($show_free) $colspan=7; else $colspan = 5 ?>
        <? if ($show_free) $colspan=6; else $colspan = 5 ?>

        <td colspan="<?= $colspan; ?>" style="width:17.78%;border:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center" class="act_test_head"><b>пересдачи</b>
        </td>
        <td width="17%" rowspan="3" style="width:17.78%;border:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center" class="act_test_head"><b>Всего за сессию<?=$nds_header_text?></b>
            </div>
        </td>
    </tr>
    <tr>

        <td width="17%" rowspan="2" style="width:17.78%;border:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">

            <div align="center" style="text-align:center" class="act_test_head"><b>Комплексный экзамен для</b></div>
        </td>

        <td width="12%" rowspan="2" style="width:12.72%;border:solid windowtext 1.0pt;
            border-left:none;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center" class="act_test_head"><b>Количество человек</b></div>

        </td>
        <td width="12%" rowspan="2" style="width:12.72%;border:solid windowtext 1.0pt;
            border-left:none;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center" class="act_test_head"><b>Стоимость за 1 человека<?=$nds_header_text?></b></div>

        </td>
        <td width="12%" rowspan="2" style="width:12.72%;border:solid windowtext 1.0pt;
            border-left:none;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center" class="act_test_head"><b>Сумма за тестирование<?=$nds_header_text?></b></div>

        </td>
        <? if ($show_free): ?>
        <td width="12%" rowspan="2" style="width:12.72%;border:solid windowtext 1.0pt;
            border-left:none;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center" class="act_test_head"><b>Количество бесплатных пересдач</b></div>

        </td>
        <? endif;?>
        <td width="12%" rowspan="1" colspan="2" style="width:12.72%;border:solid windowtext 1.0pt;
            border-left:none;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center" class="act_test_head"><b>Количество пересдач</b></div>

        </td>

        <? /*if ($show_free): ?>
            <td width="12%" rowspan="1" colspan="2" style="width:12.72%;border:solid windowtext 1.0pt;
        border-left:none;padding:0cm 5.4pt 0cm 5.4pt">
                <div align="center" style="text-align:center" class="act_test_head"><b>Из них бесплатно</b></div>

            </td>
        <? endif; */?>

        <td width="12%" rowspan="1" colspan="2" style="width:12.72%;border:solid windowtext 1.0pt;
            border-left:none;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center" class="act_test_head"><b>Стоимость пересдачи компонентов<?=$nds_header_text?></b></div>

        </td>
        <td width="12%" rowspan="2" colspan="1" style="width:12.72%;border:solid windowtext 1.0pt;
            border-left:none;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center" class="act_test_head"><b>Сумма за пересдачи<?=$nds_header_text?></b></div>

        </td>

    </tr>








    <tr>

        <? /*if ($show_free):?>
        <td width="12%" rowspan="2" colspan="1" style="width:12.72%;border:solid windowtext 1.0pt;
            border-left:none;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center" class="act_test_head"><b>Количество бесплатных пересдач</b></div>

        </td>
        <? endif; */?>


        <td width="12%" rowspan="1" colspan="1" style="width:12.72%;border:solid windowtext 1.0pt;
            border-left:none;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center" class="act_test_head"><b>1-го</b></div>

        </td>
        <td width="12%" rowspan="1" colspan="1" style="width:12.72%;border:solid windowtext 1.0pt;
            border-left:none;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center" class="act_test_head"><b>2-х</b></div>

        </td>

<? /*if ($show_free):?>
        <td width="12%" rowspan="1" colspan="1" style="width:12.72%;border:solid windowtext 1.0pt;
            border-left:none;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center" class="act_test_head"><b>1-го</b></div>

        </td>
        <td width="12%" rowspan="1" colspan="1" style="width:12.72%;border:solid windowtext 1.0pt;
            border-left:none;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center" class="act_test_head"><b>2-х</b></div>

        </td>
<? endif; */?>

        <td width="12%" rowspan="1" colspan="1" style="width:12.72%;border:solid windowtext 1.0pt;
            border-left:none;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center" class="act_test_head"><b>1-го</b></div>

        </td>
        <td width="12%" rowspan="1" colspan="1" style="width:12.72%;border:solid windowtext 1.0pt;
            border-left:none;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center" class="act_test_head"><b>2-х</b></div>

        </td>
        <!--<td width="12%" rowspan="1" colspan="1" style="width:12.72%;border:solid windowtext 1.0pt;
            border-left:none;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center"><b><span
                        style="font-size:8.0pt;color:#333333">1-го</span></b></div>

        </td>
        <td width="12%" rowspan="1" colspan="1" style="width:12.72%;border:solid windowtext 1.0pt;
            border-left:none;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center"><b><span
                        style="font-size:8.0pt;color:#333333">2-х</span></b></div>

        </td>-->
    </tr>








    <?php foreach($act->getTests() as $test):
        /** @var ActTest $test */
        //$prices=ChangedPriceTestLevel::checkPrice($act->id);
        ?>
        <tr>
            <td width="17%" style="width:17.78%;border:solid windowtext 1.0pt;border-top:
            none;
            padding:0cm 5.4pt 0cm 5.4pt">
                <div align="center" style="text-align:center" class="act_test"><!--<span style="font-size:11.0pt;color:#333333">-->
            <?php echo $test->getLevel()->getPrintAct(); ?>
<!--        </span>-->
                </div>
            </td>
            <td width="12%" style="width:12.72%;border-top:none;border-left:none;
            border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
                <div align="center" style="text-align:center" class="act_test"><i>
                        <?php echo $test->people_first ?>
                    </i></div>
            </td>
            <td width="18%" style="width:18.12%;border-top:none;border-left:none;
            border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
                <div align="center" style="text-align:center" class="act_test"><i>

                        <?php echo $test->getPrice() ?>
                    </i></div>
            </td>
            <td width="18%" style="width:18.12%;border-top:none;border-left:none;
            border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
                <div align="center" style="text-align:center" class="act_test"><i>

                        <?php
//                        echo $test->getPrice() * $test->people_first
                        echo $test->getMoneyFirst();
                        ?>
                    </i></div>
            </td>
            <? if ($show_free):?>
                <td width="18%" style="width:18.12%;border-top:none;border-left:none;
            border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
                    <div align="center" style="text-align:center" class="act_test"><i>
                            <?php echo $test->countFreeRetry() ?>
                        </i></div>
                </td>
            <? endif; ?>
            <td width="18%" style="width:18.12%;border-top:none;border-left:none;
            border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
                <div align="center" style="text-align:center" class="act_test"><i>
                        <?php echo $test->people_subtest_retry ?>
                    </i></div>
            </td>
            <td width="18%" style="width:18.12%;border-top:none;border-left:none;
            border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
                <div align="center" style="text-align:center" class="act_test"><i>
                        <?php echo $test->people_subtest_2_retry ?>
                    </i></div>
            </td>

                <? /*if ($show_free): ?>
            <td width="18%" style="width:18.12%;border-top:none;border-left:none;
                border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
                <div align="center" style="text-align:center" class="act_test"><i>
                        <?php echo $test->countFreeRetry_1() ?>
                    </i></div>
            </td>
            <td width="18%" style="width:18.12%;border-top:none;border-left:none;
                border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
                <div align="center" style="text-align:center" class="act_test"><i>
                        <?php echo $test->countFreeRetry_2() ?>
                    </i></div>
            </td>
                <? endif; */?>

            <td width="18%" style="width:18.12%;border-top:none;border-left:none;
            border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
                <div align="center" style="text-align:center" class="act_test"><i>

                        <?php echo $test->getPriceSubTest() ?>
                    </i></div>
            </td>
            <td width="18%" style="width:18.12%;border-top:none;border-left:none;
            border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
                <div align="center" style="text-align:center" class="act_test"><i>

                        <?php echo $test->getPriceSubTest2() ?>
                    </i></div>
            </td>
            <!--<td width="18%" style="width:18.12%;border-top:none;border-left:none;
            border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
                <div align="center" style="text-align:center"><i>

                        <?php
//            echo  $test->getPriceSubTest()*$test->people_subtest_retry;
                    echo $test->getMoneyRetry_1();
            ?>
                    </i></div>
            </td>-->
            <td width="18%" style="width:18.12%;border-top:none;border-left:none;
            border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
                <div align="center" style="text-align:center" class="act_test"><i>

                        <?php
//                        echo  $test->getPriceSubTest2()*$test->people_subtest_2_retry + $test->getPriceSubTest()*$test->people_subtest_retry;
                        echo $test->getMoneyRetry_1() + $test->getMoneyRetry_2();
                        ?>
                    </i></div>
            </td>

            <td width="18%" style="width:18.12%;border-top:none;border-left:none;
            border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
                <div align="center" style="text-align:center" class="act_test"><i>
                        <?php
/*                          echo
                            $test->getPriceSubTest()*$test->people_subtest_retry
                            +
                            $test->getPrice() * $test->people_first
                            +
                            $test->getPriceSubTest2()*$test->people_subtest_2_retry
*/
                        echo $test->getMoneyTotal();
                        ?>
                    </i></div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
