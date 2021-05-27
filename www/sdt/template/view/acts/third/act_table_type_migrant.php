<table width="100%" cellspacing="0" cellpadding="0" border="1"
       style="width:100.0%;border-collapse:collapse;border:none;">
    <tbody>
    <tr>
        <td width="17%" rowspan="1" style="width:17.78%;border:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center"><b><span
                            style="font-size:11.0pt;color:#333333">Уровень</span></b>
            </div>
            <div align="center" style="text-align:center"><b><span
                            class="table_header_span">тестирования</span></b></div>
        </td>
        <td width="17%" rowspan="1" style="width:17.78%;border:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center"><b><span class="table_header_span">Общее количество человек</span></b>
            </div>
        </td>
        <td width="17%" rowspan="1" style="width:17.78%;border:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center"><b><span
                            class="table_header_span">Стоимость услуг <?= TEXT_HEADCENTER_SHORT_IP ?>  в расчете за 1 человека (руб.)<?= $nds_header_text ?></span></b>
            </div>
        </td>
        <td width="17%" rowspan="1" style="width:17.78%;border:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
            <div align="center" style="text-align:center"><b><span
                            class="table_header_span">Общая стоимость услуг <?= TEXT_HEADCENTER_SHORT_IP ?> за тестовую сессию (руб.)<?= $nds_header_text ?></span></b>
            </div>
        </td>
    </tr>


    <?php foreach ($act->getTests() as $test):
        /** @var ActTest $test */
        //$prices=ChangedPriceTestLevel::checkPrice($act->id);
        ?>
        <tr>
            <td width="17%" style="width:17.78%;border:solid windowtext 1.0pt;border-top:
            none;
            padding:0cm 5.4pt 0cm 5.4pt">
                <div align="center" style="text-align:center"><span style="font-size:11.0pt;color:#333333">
            <?php echo $test->getLevel()->caption; ?>
        </span>
                </div>
            </td>
            <td width="12%" style="width:12.72%;border-top:none;border-left:none;
            border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
                <div align="center" style="text-align:center"><i>
                        <?php echo $test->people_first; ?>
                    </i></div>
            </td>
            <td width="18%" style="width:18.12%;border-top:none;border-left:none;
            border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
                <div align="center" style="text-align:center"><i>
                        <?php //echo $test->getLevel()->price
                        ?>
                        <?php echo $test->getPrice(); ?>
                    </i></div>
            </td>
            <td width="18%" style="width:18.12%;border-top:none;border-left:none;
            border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt">
                <div align="center" style="text-align:center"><i>
                        <?php //echo $test->getLevel()->price * $test->people_first
                        ?>
                        <?php echo $test->getPrice() * $test->people_first; ?>
                    </i></div>
            </td>

        </tr>
    <?php endforeach; ?>
    </tbody>
</table>