<form action="" method="get">
    <input type="hidden" name="action" value="ministr_statist_rki">
    <label>От:

        <div class="input-prepend date datepicker "
             data-date="">
            <span class="add-on"><i class="icon-th"></i> </span> <input
                class="input-small"
                name="begin"

                readonly="readonly" size="16" type="text"
                value="<?= $begin->format('d.m.Y') ?>">
        </div>
    </label> <label>До:

        <div class="input-prepend date datepicker "
             data-date="">
            <span class="add-on"><i class="icon-th"></i> </span> <input
                class="input-small"
                name="end"

                readonly="readonly" size="16" type="text"
                value="<?= $end->format('d.m.Y') ?>">
        </div>
    </label>
    <input type="submit" value="Построить">
</form>
<?
//var_dump($data);
if (empty($data)) return; ?>

<p align="right">Приложение</p>
<p align="center"><strong>Сведения о проведении тестирования по русскому языку как иностранному</strong><u> </u><br/>
    Численность иностранных граждан, сдавших тестирование, в <strong>РУДН</strong></p>
<p align="right">Таблица 2.1</p>
<table border="1" cellspacing="0" cellpadding="0" width="999">
    <tr>
        <td width="173" rowspan="2" valign="top"><p align="center">Уровни тестирования</p></td>
        <td width="275" colspan="2" valign="top"><p align="center">Численность иностранных граждан, сдавших
                тестирование, в РУДН, чел.</p></td>
        <td width="156" colspan="2" valign="top"><p align="center">Стоимость сдачи тестирования, руб.</p></td>
        <td width="267" colspan="2" valign="top"><p align="center">Численность иностранных граждан, пересдавших
                тестирование, в РУДН, чел.</p></td>
        <td width="129" valign="top"><p align="center">Стоимость пересдачи тестирования, руб.</p></td>
    </tr>
    <tr>
        <td width="129" valign="top"><p align="center">планируемая на <?=date('Y')?>г.</p></td>
        <td width="147" valign="top"><p align="center">фактическая на  <?=$begin->format('d.m.Y');?> - <?=$end->format('d.m.Y');?></p></td>
        <td width="70" valign="top"><p align="center">Стоимость сдачи тестирования  головному вузу, руб.</p></td>
        <td width="86" valign="top"><p align="center">Доход головного вуза от сдачи комплексного экзамена, руб.</p></td>
        <td width="138" valign="top"><p align="center">планируемая <?=date('Y')?>г.</p></td>
        <td width="129" valign="top"><p align="center">фактическая на  <?=$begin->format('d.m.Y');?> - <?=$end->format('d.m.Y');?></p></td>
        <td width="129" valign="top"><p align="center">1 компонента</p></td>
    </tr>
    <tr>
        <td width="173"><p>элементарный ТЭУ/А 1<strong></strong></p></td>
        <td width="129" valign="top"><p align="center">0</p></td>
        <td width="147" valign="top"><p align="center"><?= $data['pfur'][1]['pass'] ?></p></td>
        <td width="70" valign="top"><p>&nbsp;</p></td>
        <td width="86" valign="top"><p>&nbsp;</p></td>
        <td width="138" valign="top"><p align="center">0</p></td>
        <td width="129" valign="top"><p align="center"><?= $data['pfur'][1]['retry'] ?></p></td>
        <td width="129" valign="top"><p align="center">&nbsp;</p></td>
    </tr>
    <tr>
        <td width="173"><p>базовый ТБУ /А 2<strong></strong></p></td>
        <td width="129" valign="top"><p align="center">0</p></td>
        <td width="147" valign="top"><p align="center"><?= $data['pfur'][2]['pass'] ?></p></td>
        <td width="70" valign="top"><p>&nbsp;</p></td>
        <td width="86" valign="top"><p>&nbsp;</p></td>
        <td width="138" valign="top"><p align="center">0</p></td>
        <td width="129" valign="top"><p align="center"><?= $data['pfur'][2]['retry'] ?></p></td>
        <td width="129" valign="top"><p align="center">-</p></td>
    </tr>
    <tr>
        <td width="173"><p>первый сертификационный уровень ТРКИ-I/В1<strong></strong></p></td>
        <td width="129" valign="top"><p align="center">0</p></td>
        <td width="147" valign="top"><p align="center"><?= $data['pfur'][3]['pass'] ?></p></td>
        <td width="70" valign="top"><p>&nbsp;</p></td>
        <td width="86" valign="top"><p>&nbsp;</p></td>
        <td width="138" valign="top"><p align="center">0</p></td>
        <td width="129" valign="top"><p align="center"><?= $data['pfur'][3]['retry'] ?></p></td>
        <td width="129" valign="top"><p align="center">-</p></td>
    </tr>
    <tr>
        <td width="173"><p>второй сертификационный уровень ТРКИ-II/В2<strong></strong></p></td>
        <td width="129" valign="top"><p align="center">0</p></td>
        <td width="147" valign="top"><p align="center"><?= $data['pfur'][4]['pass'] ?></p></td>
        <td width="70" valign="top"><p>&nbsp;</p></td>
        <td width="86" valign="top"><p>&nbsp;</p></td>
        <td width="138" valign="top"><p align="center">0</p></td>
        <td width="129" valign="top"><p align="center"><?= $data['pfur'][4]['retry'] ?></p></td>
        <td width="129" valign="top"><p align="center">-</p></td>
    </tr>
    <tr>
        <td width="173"><p>третий сертификационный уровень ТРКИ-III/С 1<strong></strong></p></td>
        <td width="129" valign="top"><p align="center">0</p></td>
        <td width="147" valign="top"><p align="center"><?= $data['pfur'][5]['pass'] ?></p></td>
        <td width="70" valign="top"><p>&nbsp;</p></td>
        <td width="86" valign="top"><p>&nbsp;</p></td>
        <td width="138" valign="top"><p align="center">0</p></td>
        <td width="129" valign="top"><p align="center"><?= $data['pfur'][5]['retry'] ?></p></td>
        <td width="129" valign="top"><p align="center">-</p></td>
    </tr>
    <tr>
        <td width="173"><p>четвертый сертификационный уровень ТРКИ-IV/С2<strong></strong></p></td>
        <td width="129" valign="top"><p align="center">&nbsp;</p>

            <p align="center">0</p></td>
        <td width="147" valign="top"><p align="center"><?= $data['pfur'][6]['pass'] ?></p></td>
        <td width="70" valign="top"><p align="center">&nbsp;</p></td>
        <td width="86" valign="top"><p align="center">&nbsp;</p></td>
        <td width="138" valign="top"><p align="center">0</p></td>
        <td width="129" valign="top"><p align="center"><?= $data['pfur'][6]['retry'] ?></p></td>
        <td width="129" valign="top"><p align="center">&nbsp;</p></td>
    </tr>
    <tr>
        <td width="173"><p>для приема в гражданство Российской Федерации</p></td>
        <td width="129" valign="top"><p align="center">0</p></td>
        <td width="147" valign="top"><p align="center"><?= $data['pfur'][7]['pass'] ?></p></td>
        <td width="70" valign="top"><p align="center">&nbsp;</p></td>
        <td width="86" valign="top"><p align="center">&nbsp;</p></td>
        <td width="138" valign="top"><p align="center">&nbsp;</p></td>
        <td width="129" valign="top"><p align="center"><?= $data['pfur'][7]['retry'] ?></p></td>
        <td width="129" valign="top"><p align="center">&nbsp;</p></td>
    </tr>
    <tr>
        <td width="173"><p>ИТОГО:</p></td>
        <td width="129" valign="top"><p align="center">0</p></td>
        <td width="147" valign="top"><p align="center"><?= (
                    $data['pfur'][1]['pass']
                    + $data['pfur'][2]['pass']
                    + $data['pfur'][3]['pass']
                    + $data['pfur'][4]['pass']
                    + $data['pfur'][5]['pass']
                    + $data['pfur'][6]['pass']
                    + $data['pfur'][7]['pass']
                )
                ?></p></td>
        <td width="70" valign="top"><p align="center">&nbsp;</p></td>
        <td width="86" valign="top"><p align="center">&nbsp;</p></td>
        <td width="138" valign="top"><p align="center">&nbsp;</p></td>
        <td width="129" valign="top"><p align="center"><?= (
                    $data['pfur'][1]['retry']
                    + $data['pfur'][2]['retry']
                    + $data['pfur'][3]['retry']
                    + $data['pfur'][4]['retry']
                    + $data['pfur'][5]['retry']
                    + $data['pfur'][6]['retry']
                    + $data['pfur'][7]['retry']
                )
                ?></p></td>
        <td width="129" valign="top"><p align="center">&nbsp;</p></td>
    </tr>
</table>
<p>&nbsp;</p>


<p align="right">Приложение</p>
<p align="center"><strong>Сведения о проведении тестирования по русскому языку как иностранному</strong><u> </u><br/>
    Численность иностранных граждан, сдавших тестирование, в <strong>в локальных центрах тестирования</strong></p>
<p align="right">Таблица 2.1</p>
<table border="1" cellspacing="0" cellpadding="0" width="999">
    <tr>
        <td width="173" rowspan="2" valign="top"><p align="center">Уровни тестирования</p></td>
        <td width="275" colspan="2" valign="top"><p align="center">Численность иностранных граждан, сдавших тестирование, в центрах тестирования, чел.</p></td>
        <td width="156" colspan="2" valign="top"><p align="center">Стоимость сдачи тестирования, руб.</p></td>
        <td width="267" colspan="2" valign="top"><p align="center">Численность иностранных граждан, пересдавших тестирование, в центрах тестирования, чел.</p></td>
        <td width="129" valign="top"><p align="center">Стоимость пересдачи тестирования, руб.</p></td>
    </tr>
    <tr>
        <td width="129" valign="top"><p align="center">планируемая на <?=date('Y')?>г.</p></td>
        <td width="147" valign="top"><p align="center">фактическая на  <?=$begin->format('d.m.Y');?> - <?=$end->format('d.m.Y');?></p></td>
        <td width="70" valign="top"><p align="center">Плата локальных центров головному вузу, руб.</p></td>
        <td width="86" valign="top"><p align="center">Доход головного вуза от сдачи комплексного экзамена в локальных центрах, руб.</p></td>
        <td width="138" valign="top"><p align="center">планируемая <?=date('Y')?>г.</p></td>
        <td width="129" valign="top"><p align="center">фактическая на  <?=$begin->format('d.m.Y');?> - <?=$end->format('d.m.Y');?></p></td>
        <td width="129" valign="top"><p align="center">1 компонента</p></td>
    </tr>
    <tr>
        <td width="173"><p>элементарный ТЭУ/А 1<strong></strong></p></td>
        <td width="129" valign="top"><p align="center">0</p></td>
        <td width="147" valign="top"><p align="center"><?= $data['local'][1]['pass'] ?></p></td>
        <td width="70" valign="top"><p>&nbsp;</p></td>
        <td width="86" valign="top"><p>&nbsp;</p></td>
        <td width="138" valign="top"><p align="center">0</p></td>
        <td width="129" valign="top"><p align="center"><?= $data['local'][1]['retry'] ?></p></td>
        <td width="129" valign="top"><p align="center">&nbsp;</p></td>
    </tr>
    <tr>
        <td width="173"><p>базовый ТБУ /А 2<strong></strong></p></td>
        <td width="129" valign="top"><p align="center">0</p></td>
        <td width="147" valign="top"><p align="center"><?= $data['local'][2]['pass'] ?></p></td>
        <td width="70" valign="top"><p>&nbsp;</p></td>
        <td width="86" valign="top"><p>&nbsp;</p></td>
        <td width="138" valign="top"><p align="center">0</p></td>
        <td width="129" valign="top"><p align="center"><?= $data['local'][2]['retry'] ?></p></td>
        <td width="129" valign="top"><p align="center">-</p></td>
    </tr>
    <tr>
        <td width="173"><p>первый сертификационный уровень ТРКИ-I/В1<strong></strong></p></td>
        <td width="129" valign="top"><p align="center">0</p></td>
        <td width="147" valign="top"><p align="center"><?= $data['local'][3]['pass'] ?></p></td>
        <td width="70" valign="top"><p>&nbsp;</p></td>
        <td width="86" valign="top"><p>&nbsp;</p></td>
        <td width="138" valign="top"><p align="center">0</p></td>
        <td width="129" valign="top"><p align="center"><?= $data['local'][3]['retry'] ?></p></td>
        <td width="129" valign="top"><p align="center">-</p></td>
    </tr>
    <tr>
        <td width="173"><p>второй сертификационный уровень ТРКИ-II/В2<strong></strong></p></td>
        <td width="129" valign="top"><p align="center">0</p></td>
        <td width="147" valign="top"><p align="center"><?= $data['local'][4]['pass'] ?></p></td>
        <td width="70" valign="top"><p>&nbsp;</p></td>
        <td width="86" valign="top"><p>&nbsp;</p></td>
        <td width="138" valign="top"><p align="center">0</p></td>
        <td width="129" valign="top"><p align="center"><?= $data['local'][4]['retry'] ?></p></td>
        <td width="129" valign="top"><p align="center">-</p></td>
    </tr>
    <tr>
        <td width="173"><p>третий сертификационный уровень ТРКИ-III/С 1<strong></strong></p></td>
        <td width="129" valign="top"><p align="center">0</p></td>
        <td width="147" valign="top"><p align="center"><?= $data['local'][5]['pass'] ?></p></td>
        <td width="70" valign="top"><p>&nbsp;</p></td>
        <td width="86" valign="top"><p>&nbsp;</p></td>
        <td width="138" valign="top"><p align="center">0</p></td>
        <td width="129" valign="top"><p align="center"><?= $data['local'][5]['retry'] ?></p></td>
        <td width="129" valign="top"><p align="center">-</p></td>
    </tr>
    <tr>
        <td width="173"><p>четвертый сертификационный уровень ТРКИ-IV/С2<strong></strong></p></td>
        <td width="129" valign="top"><p align="center">&nbsp;</p>

            <p align="center">0</p></td>
        <td width="147" valign="top"><p align="center"><?= $data['local'][6]['pass'] ?></p></td>
        <td width="70" valign="top"><p align="center">&nbsp;</p></td>
        <td width="86" valign="top"><p align="center">&nbsp;</p></td>
        <td width="138" valign="top"><p align="center">0</p></td>
        <td width="129" valign="top"><p align="center"><?= $data['local'][6]['retry'] ?></p></td>
        <td width="129" valign="top"><p align="center">&nbsp;</p></td>
    </tr>
    <tr>
        <td width="173"><p>для приема в гражданство Российской Федерации</p></td>
        <td width="129" valign="top"><p align="center">0</p></td>
        <td width="147" valign="top"><p align="center"><?= $data['local'][7]['pass'] ?></p></td>
        <td width="70" valign="top"><p align="center">&nbsp;</p></td>
        <td width="86" valign="top"><p align="center">&nbsp;</p></td>
        <td width="138" valign="top"><p align="center">&nbsp;</p></td>
        <td width="129" valign="top"><p align="center"><?= $data['local'][7]['retry'] ?></p></td>
        <td width="129" valign="top"><p align="center">&nbsp;</p></td>
    </tr>
    <tr>
        <td width="173"><p>ИТОГО:</p></td>
        <td width="129" valign="top"><p align="center">0</p></td>
        <td width="147" valign="top"><p align="center"><?= (
                    $data['local'][1]['pass']
                    + $data['local'][2]['pass']
                    + $data['local'][3]['pass']
                    + $data['local'][4]['pass']
                    + $data['local'][5]['pass']
                    + $data['local'][6]['pass']
                    + $data['local'][7]['pass']
                )
                ?></p></td>
        <td width="70" valign="top"><p align="center">&nbsp;</p></td>
        <td width="86" valign="top"><p align="center">&nbsp;</p></td>
        <td width="138" valign="top"><p align="center">&nbsp;</p></td>
        <td width="129" valign="top"><p align="center"><?= (
                    $data['local'][1]['retry']
                    + $data['local'][2]['retry']
                    + $data['local'][3]['retry']
                    + $data['local'][4]['retry']
                    + $data['local'][5]['retry']
                    + $data['local'][6]['retry']
                    + $data['local'][7]['retry']
                )
                ?></p></td>
        <td width="129" valign="top"><p align="center">&nbsp;</p></td>
    </tr>
</table>
<p>&nbsp;</p>