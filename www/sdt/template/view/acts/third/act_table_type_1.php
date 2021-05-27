<table width="100%" cellspacing="0" cellpadding="0" border="1" class="main_td"
       style="width:100.0%;border-collapse:collapse;border:none;">
    <tbody>
    <tr>
        <td width="17%" rowspan="2" style="width:17.78%;border:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center"><b><span class="table_header_span">Уровень</span></b>
            </div>
            <div align="center" style="text-align:center"><b><span
                            class="table_header_span">тестирования</span></b></div>
        </td>
        <td colspan="3" style="width:17.78%;border:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center"><b><span class="table_header_span">тестирование</span></b>
        </td>
        <? if ($show_free) $colspan=4; else $colspan = 3 ?>
        <td colspan="<?=$colspan;?>" style="width:17.78%;border:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center"><b><span class="table_header_span">пересдачи</span></b>
        </td>
        <td width="17%" rowspan="2" style="width:17.78%;border:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center"><b><span class="table_header_span">Всего за тестовую сессию<?=$nds_header_text?></span></b>
            </div>
        </td>
    </tr>
    <tr>
        <td width="12%" rowspan="1" style="width:12.72%;border:solid windowtext 1.0pt;
            border-left:none;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center"><b><span
              class="table_header_span">Количество человек</span></b></div>
            <!--<div align="center" style="text-align:center"><b><span style="font-size:11.0pt;color:#333333">человек</span></b>
            </div>-->
        </td>
        <td width="12%" rowspan="1" style="width:12.72%;border:solid windowtext 1.0pt;
            border-left:none;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center"><b><span
                            class="table_header_span">Стоимость за 1 человека<?=$nds_header_text?></span></b></div>
            <!--<div align="center" style="text-align:center"><b><span style="font-size:11.0pt;color:#333333">человек</span></b>
            </div>-->
        </td>
        <td width="12%" rowspan="1" style="width:12.72%;border:solid windowtext 1.0pt;
            border-left:none;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center"><b><span
                            class="table_header_span">Сумма за тестирование<?=$nds_header_text?></span></b></div>
            <!--<div align="center" style="text-align:center"><b><span style="font-size:11.0pt;color:#333333">человек</span></b>
            </div>-->
        </td>
        <? if ($show_free):?>
            <td width="12%" rowspan="1" style="width:12.72%;border:solid windowtext 1.0pt;
            border-left:none;padding:0cm 5.4pt 0cm 5.4pt">
                <div align="center" style="text-align:center"><b><span
                                class="table_header_span">Количество бесплатных пересдач</span></b></div>
            </td>
        <? endif; ?>
        <td width="12%" rowspan="1" style="width:12.72%;border:solid windowtext 1.0pt;
            border-left:none;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center"><b><span
                            class="table_header_span">Количество пересдач</span></b></div>
        </td>
        <td width="12%" rowspan="1" style="width:12.72%;border:solid windowtext 1.0pt;
            border-left:none;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center"><b><span
                            class="table_header_span">Стоимость пересдачи 1 субтеста<?=$nds_header_text?></span></b></div>
            <!--<div align="center" style="text-align:center"><b><span style="font-size:11.0pt;color:#333333">человек</span></b>
            </div>-->
        </td>
        <td width="12%" rowspan="1" style="width:12.72%;border:solid windowtext 1.0pt;
            border-left:none;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center"><b><span
                            class="table_header_span">Сумма за пересдачи<?=$nds_header_text?></span></b></div>
            <!--<div align="center" style="text-align:center"><b><span style="font-size:11.0pt;color:#333333">человек</span></b>
            </div>-->
        </td>
        <!--<td width="18%" rowspan="1" style="width:18.12%;border:solid windowtext 1.0pt;
                border-left:none;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center"><b><span
                    style="font-size:11.0pt;color:#333333"> Сумма тестирования,
    </span></b></div>
            <div align="center" style="text-align:center"><b><span style="color:#333333;">руб.</span></b></div>
        </td>
        <td width="18%" rowspan="1" style="width:18.12%;border:solid windowtext 1.0pt;
                border-left:none;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center"><b><span
                    style="font-size:11.0pt;color:#333333"> Пересдача</span></b></div>
            <div align="center" style="text-align:center"><b><span style="color:#333333;">(кол-во чел.)</span></b></div>
        </td>
        <td width="18%" rowspan="1" style="width:18.12%;border:solid windowtext 1.0pt;
                border-left:none;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center"><b><span
                    style="font-size:11.0pt;color:#333333">Сумма Пересдачи
    </span></b></div>
            <div align="center" style="text-align:center"><b><span style="color:#333333;">руб.</span></b></div>
        </td>

        <td width="10%" rowspan="1" style="width:10.86%;border:solid windowtext 1.0pt;
                border-left:none;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center"><b><span style="font-size:11.0pt;color:#333333">Кол-во</span></b>
            </div>
            <div align="center" style="text-align:center"><b><span style="font-size:11.0pt;color:#333333">серт-ов</span></b>
            </div>
        </td>
        <td width="12%" rowspan="1" style="width:12.84%;border:solid windowtext 1.0pt;
                border-left:none;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center"><b><span style="font-size:11.0pt;color:#333333">Кол-во</span></b>
            </div>
            <div align="center" style="text-align:center"><b><span style="font-size:11.0pt;color:#333333">справок</span></b>
            </div>
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
                <div align="center" style="text-align:center"><span style="font-size:11.0pt;color:#333333">
            <?php echo /*$test->getLevel()->caption*/$test->getLevel()->getPrintAct(); ?>
        </span>
                </div>
            </td>
            <td width="12%" style="width:12.72%;border-top:none;border-left:none;
            border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
                <div align="center" style="text-align:center"><i>
                        <?php echo $test->people_first ?>
                    </i></div>
            </td>
            <td width="18%" style="width:18.12%;border-top:none;border-left:none;
            border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
                <div align="center" style="text-align:center"><i>
                        <?php //echo $test->getLevel()->price ?>
                        <?php echo $test->getPrice() ?>
                    </i></div>
            </td>
            <td width="18%" style="width:18.12%;border-top:none;border-left:none;
            border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
                <div align="center" style="text-align:center"><i>
                        <?php  //echo $test->getLevel()->price * $test->people_first  ?>
                        <?php
//                        echo $test->getPrice() * $test->people_first;
                        echo $test->getMoneyFirst();
                        ?>
                    </i></div>
            </td>
            <? if ($show_free):?>
                <td width="18%" style="width:18.12%;border-top:none;border-left:none;
            border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
                    <div align="center" style="text-align:center"><i>
                            <?php echo $test->countFreeRetry() ?>
                        </i></div>
                </td>
            <? endif; ?>
            <td width="18%" style="width:18.12%;border-top:none;border-left:none;
            border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
                <div align="center" style="text-align:center"><i>
                        <?php echo $test->people_subtest_retry ?>
                    </i></div>
            </td>
            <td width="18%" style="width:18.12%;border-top:none;border-left:none;
            border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
                <div align="center" style="text-align:center"><i>
                        <?php //echo $test->getLevel()->sub_test_price ?>
                        <?php echo $test->getPriceSubTest() ?>
                    </i></div>
            </td>
            <td width="18%" style="width:18.12%;border-top:none;border-left:none;
            border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
                <div align="center" style="text-align:center"><i>
                        <?php //echo  $test->getLevel()->sub_test_price*$test->people_subtest_retry; ?>
                        <?php
//                        echo  $test->getPriceSubTest()*$test->people_subtest_retry;
                        echo $test->getMoneyRetry_1();
                        ?>
                    </i></div>
            </td>
            <!--<td width="10%" style="width:10.86%;border-top:none;border-left:none;
            border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
        <div align="center" style="text-align:center"><i>
            <?php echo $test->countCertificate() ?>
        </i></div>
    </td>
    <td width="12%" style="width:12.84%;border-top:none;border-left:none;
            border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
        <div align="center" style="text-align:center"><i>
            <?php echo $test->countNote() ?>
        </i></div>
    </td>-->
            <td width="18%" style="width:18.12%;border-top:none;border-left:none;
            border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
                <div align="center" style="text-align:center"><i>
                        <?php

                           /*echo
                            $test->getPriceSubTest()*$test->people_subtest_retry
                            +
                            $test->getPrice() * $test->people_first*/
                        echo $test->getMoneyTotal();
                        ?>
                    </i></div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
