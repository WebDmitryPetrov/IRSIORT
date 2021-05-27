<form action="" method="get">
<input type="hidden" name="action" value="ministr_statist">
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
if(empty($data)) return;?>

<p align="right">Приложение</p>
<p align="center"><strong>Сведения  о проведении комплексного экзамена по русскому языку, истории России и основам  законодательства</strong><br />
    <strong>Соискатели разрешения на работу либо  патента</strong><strong> </strong></p>
<p>Наименование  учреждения: ФГАОУ ВО РУДН</p>
<p>&nbsp;</p>
<p align="right">Таблица 1.1</p>
<table border="1" cellspacing="0" cellpadding="0" >
    <tr>
        <td  rowspan="2" ><p align="center">Категории    иностранных граждан</p></td>
        <td  colspan="2" ><p align="center">Численность    иностранных граждан, сдавших комплексный экзамен, в головном вузе, чел.</p></td>
        <td  ><p align="center">Стоимость сдачи    комплексного экзамена, руб.</p></td>
        <td  colspan="3" ><p align="center">Численность    иностранных граждан, пересдавших комплексный экзамен, в головном вузе, чел.</p></td>
        <td  ><p align="center">Стоимость    пересдачи комплексного экзамена, руб.</p></td>
    </tr>
    <tr>
        <td><p align="center">планируемая на    <?=date('Y')?>г.</p></td>
        <td><p align="center">фактическая на    <?=$begin->format('d.m.Y');?> - <?=$end->format('d.m.Y');?></p></td>
        <td><p>&nbsp;</p></td>
        <td><p align="center">планируемая    <?=date('Y')?>г.</p></td>
        <td><p align="center">фактическая на    <?=$begin->format('d.m.Y');?> - <?=$end->format('d.m.Y');?><br />1 пересдача</p></td>
        <td  ><p align="center">2 пересдача</p></td>
        <td  ><p align="center">1-2 компонента</p></td>
    </tr>
    <tr>
        <td  ><p>Комплексный    экзамен для трудящихся мигрантов</p></td>
        <td  ><p align="center">0</p></td>
        <td  ><p align="center"><?=$data['pfur'][0]['total']['pass']?></p></td>
        <td  ><p>4900</p></td>
        <td  ><p align="center">0</p></td>
        <td  ><p align="center"><?=$data['pfur'][0]['total']['retry_1']?></p></td>
        <td  ><p align="center"><?=$data['pfur'][0]['total']['retry_2']?></p></td>
        <td  ><p align="center">1800-2400</p></td>
    </tr>
    <tr>
        <td  ><p>Комплексный    экзамен для трудящихся мигрантов (упрощенная процедура)</p></td>
        <td  ><p align="center">0</p></td>
        <td  ><p align="center"><?=$data['pfur'][0]['simple']['pass']?></p></td>
        <td  ><p>1900</p></td>
        <td  ><p align="center">0</p></td>
        <td  ><p align="center"><?=$data['pfur'][0]['simple']['retry_1']?></p></td>
        <td  ><p align="center"><?=$data['pfur'][0]['simple']['retry_2']?></p></td>
        <td  ><p align="center">-</p></td>
    </tr>
    <tr>
        <td  ><p>Комплексный    экзамен для трудящихся мигрантов, жителей ДНР и ЛНР</p></td>
        <td  ><p align="center">0</p></td>
        <td  ><p align="center"><?=$data['pfur'][0]['dnr_total']['pass']?></p></td>
        <td  ><p>2500</p></td>
        <td  ><p align="center">0</p></td>
        <td  ><p align="center"><?=$data['pfur'][0]['dnr_total']['retry_1']?></p></td>
        <td  ><p align="center"><?=$data['pfur'][0]['dnr_total']['retry_2']?></p></td>
        <td  ><p align="center">-</p></td>
    </tr>
    <tr>
        <td  ><p>Комплексный    экзамен для трудящихся мигрантов, жителей ДНР и ЛНР, подтвердивших владение    русским языком документом об образовании (упрощенная процедура)</p></td>
        <td  ><p align="center">0</p></td>
        <td  ><p align="center"><?=$data['pfur'][0]['dnr_simple']['pass']?></p></td>
        <td  ><p>900</p></td>
        <td  ><p align="center">0</p></td>
        <td  ><p align="center"><?=$data['pfur'][0]['dnr_simple']['retry_1']?></p></td>
        <td  ><p align="center"><?=$data['pfur'][0]['dnr_simple']['retry_2']?></p></td>
        <td  ><p align="center">800</p></td>
    </tr>
    <tr>
        <td  ><p>Комплексный    экзамен для трудящихся мигрантов, граждан Украины, подтвердивших владение    русским языком документом об образовании (упрощенная процедура)</p></td>
        <td  ><p align="center">0</p></td>
        <td  ><p align="center"><?=$data['pfur'][0]['ukr_simple']['pass']?></p></td>
        <td  ><p>1200</p></td>
        <td  ><p align="center">0</p></td>
        <td  ><p align="center"><?=$data['pfur'][0]['ukr_simple']['retry_1']?></p></td>
        <td  ><p align="center"><?=$data['pfur'][0]['ukr_simple']['retry_2']?></p></td>
        <td  ><p align="center">-</p></td>
    </tr>
    <tr>
        <td  ><p>ИТОГО</p></td>
        <td  ><p align="center">&nbsp;</p>
            <p align="center">0</p></td>
        <td  ><p align="center"><?=$data['pfur'][0]['total']['pass'] + $data['pfur'][0]['simple']['pass']?></p></td>
        <td  ><p align="center">0</p></td>
        <td  ><p align="center">0</p></td>
        <td  ><p align="center"><?=$data['pfur'][0]['total']['retry_1'] + $data['pfur'][0]['simple']['retry_1']?></p></td>
        <td  ><p align="center"><?=$data['pfur'][0]['total']['retry_2'] + $data['pfur'][0]['simple']['retry_2']?></p></td>
        <td  ><p align="center">&nbsp;</p></td>
    </tr>
</table>
<p>&nbsp;</p>
<br clear="all" />
<div>
    <p align="right">Приложение</p>
    <p align="center"><strong>Сведения  о проведении комплексного экзамена по русскому языку, истории России и основам  законодательства</strong><br />
        <strong>Соискатели разрешения на временное  проживание</strong><strong> </strong></p>
    <p>Наименование  учреждения: ФГАОУ ВО РУДН</p>
    <p>&nbsp;</p>
    <p align="right">Таблица 1.2</p>
    <table border="1" cellspacing="0" cellpadding="0" >
        <tr>
            <td  rowspan="2" ><p align="center">Категории    иностранных граждан</p></td>
            <td  colspan="2" ><p align="center">Численность    иностранных граждан, сдавших комплексный экзамен, в головном вузе, чел.</p></td>
            <td  ><p align="center">Стоимость сдачи    комплексного экзамена, руб.</p></td>
            <td  colspan="3" ><p align="center">Численность    иностранных граждан, пересдавших комплексный экзамен, в головном вузе, чел.</p></td>
            <td  ><p align="center">Стоимость    пересдачи комплексного экзамена, руб.</p></td>
        </tr>
        <tr>
            <td  ><p align="center">планируемая на    <?=date('Y')?>г.</p></td>
            <td  ><p align="center">фактическая на    <?=$begin->format('d.m.Y');?> - <?=$end->format('d.m.Y');?></p></td>
            <td  ><p>&nbsp;</p></td>
            <td  ><p align="center">планируемая    <?=date('Y')?>г.</p></td>
            <td  ><p align="center">фактическая на    <?=$begin->format('d.m.Y');?> - <?=$end->format('d.m.Y');?><br />
                    1 пересдача</p></td>
            <td  ><p align="center">2 пересдача</p></td>
            <td  ><p align="center">1-2 компонента</p></td>
        </tr>
        <tr>
            <td  ><p>Комплексный    экзамен для соискателей разрешения на временное проживание</p></td>
            <td  ><p align="center">0</p></td>
            <td  ><p align="center"><?=$data['pfur'][1]['total']['pass']?></p></td>
            <td  ><p>5300</p></td>
            <td  ><p align="center">0</p></td>
            <td  ><p align="center"><?=$data['pfur'][1]['total']['retry_1']?></p></td>
            <td  ><p align="center"><?=$data['pfur'][1]['total']['retry_2']?></p></td>
            <td  ><p align="center">1800-2400</p></td>
        </tr>
        <tr>
            <td  ><p>Комплексный    экзамен для  соискателей разрешения на    временное проживание (упрощенная процедура)</p></td>
            <td  ><p align="center">0</p></td>
            <td  ><p align="center"><?=$data['pfur'][1]['simple']['pass']?></p></td>
            <td  ><p>1900</p></td>
            <td  ><p align="center">0</p></td>
            <td  ><p align="center"><?=$data['pfur'][1]['simple']['retry_1']?></p></td>
            <td  ><p align="center"><?=$data['pfur'][1]['simple']['retry_2']?></p></td>
            <td  ><p align="center">-</p></td>
        </tr>
        <tr>
            <td  ><p>Комплексный    экзамен для  соискателей разрешения на    временное проживание, жителей ДНР и ЛНР</p></td>
            <td  ><p align="center">0</p></td>
            <td  ><p align="center"><?=$data['pfur'][1]['dnr_total']['pass']?></p></td>
            <td  ><p>2800</p></td>
            <td  ><p align="center">0</p></td>
            <td  ><p align="center"><?=$data['pfur'][1]['dnr_total']['retry_1']?></p></td>
            <td  ><p align="center"><?=$data['pfur'][1]['dnr_total']['retry_2']?></p></td>
            <td  ><p align="center">800</p></td>
        </tr>
        <tr>
            <td  ><p>Комплексный    экзамен для  соискателей разрешения на    временное проживание, жителей ДНР и ЛНР, подтвердивших владение русским    языком документом об образовании (упрощенная процедура)</p></td>
            <td  ><p align="center">0</p></td>
            <td  ><p align="center"><?=$data['pfur'][1]['dnr_simple']['pass']?></p></td>
            <td  ><p>900</p></td>
            <td  ><p align="center">0</p></td>
            <td  ><p align="center"><?=$data['pfur'][1]['dnr_simple']['retry_1']?></p></td>
            <td  ><p align="center"><?=$data['pfur'][1]['dnr_simple']['retry_2']?></p></td>
            <td  ><p align="center">-</p></td>
        </tr>
        <tr>
            <td  ><p>ИТОГО</p></td>
            <td  ><p align="center">&nbsp;</p>
                <p align="center">0</p></td>
            <td  ><p align="center"><?=$data['pfur'][1]['total']['pass'] + $data['pfur'][1]['simple']['pass']?></p></td>
            <td  ><p align="center">0</p></td>
            <td  ><p align="center">0</p></td>
            <td  ><p align="center"><?=$data['pfur'][1]['total']['retry_1'] + $data['pfur'][1]['simple']['retry_1']?></p></td>
            <td  ><p align="center"><?=$data['pfur'][1]['total']['retry_2'] + $data['pfur'][1]['simple']['retry_2']?></p></td>
            <td  ><p align="center">&nbsp;</p></td>
        </tr>
    </table>
    <p>&nbsp;</p>
</div>
<br clear="all" />
<p align="right">Приложение</p>
<p align="center"><strong>Сведения  о проведении комплексного экзамена по русскому языку, истории России и основам  законодательства</strong><br />
    <strong>Соискатели вида на жительство</strong><strong> </strong></p>
<p>Наименование  учреждения: ФГАОУ ВО РУДН</p>
<p>&nbsp;</p>
<p align="right">Таблица 1.3</p>
<table border="1" cellspacing="0" cellpadding="0" >
    <tr>
        <td  rowspan="2" ><p align="center">Категории    иностранных граждан</p></td>
        <td  colspan="2" ><p align="center">Численность    иностранных граждан, сдавших комплексный экзамен, в головном вузе, чел.</p></td>
        <td  ><p align="center">Стоимость сдачи    комплексного экзамена, руб.</p></td>
        <td  colspan="3" ><p align="center">Численность    иностранных граждан, пересдавших комплексный экзамен, в головном вузе, чел.</p></td>
        <td  ><p align="center">Стоимость    пересдачи комплексного экзамена, руб.</p></td>
    </tr>
    <tr>
        <td  ><p align="center">планируемая на    <?=date('Y')?>г.</p></td>
        <td  ><p align="center">фактическая на    <?=$begin->format('d.m.Y');?> - <?=$end->format('d.m.Y');?></p></td>
        <td  ><p>&nbsp;</p></td>
        <td  ><p align="center">планируемая    <?=date('Y')?>г.</p></td>
        <td  ><p align="center">фактическая на    <?=$begin->format('d.m.Y');?> - <?=$end->format('d.m.Y');?><br />
                1 пересдача</p></td>
        <td  ><p align="center">2 пересдача</p></td>
        <td  ><p align="center">1-2 компонента</p></td>
    </tr>
    <tr>
        <td  ><p>Комплексный    экзамен для соискателей вида на жительство</p></td>
        <td  ><p align="center">0</p></td>
        <td  ><p align="center"><?=$data['pfur'][2]['total']['pass']?></p></td>
        <td  ><p>5300</p></td>
        <td  ><p align="center">0</p></td>
        <td  ><p align="center"><?=$data['pfur'][2]['total']['retry_1']?></p></td>
        <td  ><p align="center"><?=$data['pfur'][2]['total']['retry_2']?></p></td>
        <td  ><p align="center">1800-2400</p></td>
    </tr>
    <tr>
        <td  ><p>Комплексный    экзамен для   соискателей вида на    жительство (упрощенная процедура)</p></td>
        <td  ><p align="center">0</p></td>
        <td  ><p align="center"><?=$data['pfur'][2]['simple']['pass']?></p></td>
        <td  ><p>1900</p></td>
        <td  ><p align="center">0</p></td>
        <td  ><p align="center"><?=$data['pfur'][2]['simple']['retry_1']?></p></td>
        <td  ><p align="center"><?=$data['pfur'][2]['simple']['retry_2']?></p></td>
        <td  ><p align="center">-</p></td>
    </tr>
    <tr>
        <td  ><p>Комплексный    экзамен для   соискателей вида на    жительство , жителей ДНР и ЛНР</p></td>
        <td  ><p align="center">0</p></td>
        <td  ><p align="center"><?=$data['pfur'][2]['dnr_total']['pass']?></p></td>
        <td  ><p>2800</p></td>
        <td  ><p align="center">0</p></td>
        <td  ><p align="center"><?=$data['pfur'][2]['dnr_total']['retry_1']?></p></td>
        <td  ><p align="center"><?=$data['pfur'][2]['dnr_total']['retry_2']?></p></td>
        <td  ><p align="center">800</p></td>
    </tr>
    <tr>
        <td  ><p>Комплексный    экзамен для  соискателей вида на    жительство, жителей ДНР и ЛНР, подтвердивших владение русским языком    документом об образовании (упрощенная процедура)</p></td>
        <td  ><p align="center">0</p></td>
        <td  ><p align="center"><?=$data['pfur'][2]['dnr_simple']['pass']?></p></td>
        <td  ><p>900</p></td>
        <td  ><p align="center">0</p></td>
        <td  ><p align="center"><?=$data['pfur'][2]['dnr_simple']['retry_1']?></p></td>
        <td  ><p align="center"><?=$data['pfur'][2]['dnr_simple']['retry_2']?></p></td>
        <td  ><p align="center">-</p></td>
    </tr>
    <tr>
        <td  ><p>ИТОГО</p></td>
        <td  ><p align="center">&nbsp;</p>
            <p align="center">0</p></td>
        <td  ><p align="center"><?=$data['pfur'][2]['total']['pass'] + $data['pfur'][2]['simple']['pass']?></p></td>
        <td  ><p align="center">0</p></td>
        <td  ><p align="center">0</p></td>
        <td  ><p align="center"><?=$data['pfur'][2]['total']['retry_1'] + $data['pfur'][2]['simple']['retry_1']?></p></td>
        <td  ><p align="center"><?=$data['pfur'][2]['total']['retry_2'] + $data['pfur'][2]['simple']['retry_2']?></p></td>
        <td  ><p align="center">&nbsp;</p></td>
    </tr>
</table>
<p>&nbsp;</p>
<hr>

<p align="right">Приложение</p>
<p align="center"><strong>Сведения  о проведении комплексного экзамена по русскому языку, истории России и основам  законодательства</strong></p>
<p>Численность иностранных граждан, сдавших комплексный  экзамен, в <strong>локальных центрах  тестирования</strong>, чел. (уровень ИР)</p>
<p align="right">Таблица 2.1</p>
<table border="1" cellspacing="0" cellpadding="0" >
    <tr>
        <td  rowspan="2" ><p align="center">Категории    иностранных граждан</p></td>
        <td  colspan="2" ><p align="center">Численность    иностранных граждан, сдавших комплексный экзамен, в центрах тестирования,    чел.</p></td>
        <td  colspan="2" ><p align="center">Стоимость сдачи    комплексного экзамена, руб.</p></td>
        <td  colspan="3" ><p align="center">Численность    иностранных граждан, пересдавших комплексный экзамен, в центрах тестирования,    чел.</p></td>
        <td  ><p align="center">Стоимость    пересдачи комплексного экзамена, руб.</p></td>
    </tr>
    <tr>
        <td  ><p align="center">планируемая на    <?=date('Y')?>г.</p></td>
        <td  ><p align="center">фактическая на    <?=$begin->format('d.m.Y');?> - <?=$end->format('d.m.Y');?></p></td>
        <td  ><p align="center">Плата локальных    центров головному вузу, руб.</p></td>
        <td  ><p align="center">Доход головного    вуза от сдачи комплексного экзамена в локальных центрах, руб.</p></td>
        <td  ><p align="center">планируемая    <?=date('Y')?>г.</p></td>
        <td  ><p align="center">фактическая на    <?=$begin->format('d.m.Y');?> - <?=$end->format('d.m.Y');?><br />
                1 пересдача</p></td>
        <td  ><p align="center">2 пересдача</p></td>
        <td  ><p align="center">1-2 компонента</p></td>
    </tr>
    <tr>
        <td  ><p>Комплексный    экзамен для трудящихся мигрантов</p></td>
        <td  ><p align="center">0</p></td>
        <td  ><p align="center"><?=$data['local'][0]['total']['pass']?></p></td>
        <td  ><p>1450</p></td>
        <td  ><p>&nbsp;</p></td>
        <td  ><p align="center">0</p></td>
        <td  ><p align="center"><?=$data['local'][0]['total']['retry_1']?></p></td>
        <td  ><p align="center"><?=$data['local'][0]['total']['retry_2']?></p></td>
        <td  ><p align="center">800,0 – 1000,0</p></td>
    </tr>
    <tr>
        <td  ><p>Комплексный    экзамен для трудящихся мигрантов (упрощенная процедура)</p></td>
        <td  ><p align="center">0</p></td>
        <td  ><p align="center"><?=$data['local'][0]['simple']['pass']?></p></td>
        <td  ><p>600</p></td>
        <td  ><p>&nbsp;</p></td>
        <td  ><p align="center">0</p></td>
        <td  ><p align="center"><?=$data['local'][0]['simple']['retry_1']?></p></td>
        <td  ><p align="center"><?=$data['local'][0]['simple']['retry_2']?></p></td>
        <td  ><p align="center">-</p></td>
    </tr>
    <tr>
        <td  ><p>Комплексный    экзамен для трудящихся мигрантов, жителей ДНР и ЛНР</p></td>
        <td  ><p align="center">0</p></td>
        <td  ><p align="center"><?=$data['local'][0]['dnr_total']['pass']?></p></td>
        <td  ><p>950</p></td>
        <td  ><p>&nbsp;</p></td>
        <td  ><p align="center">0</p></td>
        <td  ><p align="center"><?=$data['local'][0]['dnr_total']['retry_1']?></p></td>
        <td  ><p align="center"><?=$data['local'][0]['dnr_total']['retry_2']?></p></td>
        <td  ><p align="center">-</p></td>
    </tr>
    <tr>
        <td  ><p>Комплексный    экзамен для трудящихся мигрантов, жителей ДНР и ЛНР, подтвердивших владение    русским языком документом об образовании (упрощенная процедура)</p></td>
        <td  ><p align="center">0</p></td>
        <td  ><p align="center"><?=$data['local'][0]['dnr_simple']['pass']?></p></td>
        <td  ><p>400</p></td>
        <td  ><p>&nbsp;</p></td>
        <td  ><p align="center">0</p></td>
        <td  ><p align="center"><?=$data['local'][0]['dnr_simple']['retry_1']?></p></td>
        <td  ><p align="center"><?=$data['local'][0]['dnr_simple']['retry_2']?></p></td>
        <td  ><p align="center">-</p></td>
    </tr>
    <tr>
        <td  ><p>Комплексный    экзамен для трудящихся мигрантов, граждан Украины, подтвердивших владение    русским языком документом об образовании (упрощенная процедура)</p></td>
        <td  ><p align="center">0</p></td>
        <td  ><p align="center"><?=$data['local'][0]['ukr_simple']['pass']?></p></td>
        <td  ><p>500</p></td>
        <td  ><p>&nbsp;</p></td>
        <td  ><p align="center">0</p></td>
        <td  ><p align="center"><?=$data['local'][0]['ukr_simple']['retry_1']?></p></td>
        <td  ><p align="center"><?=$data['local'][0]['ukr_simple']['retry_2']?></p></td>
        <td  ><p align="center">-</p></td>
    </tr>
    <tr>
        <td  ><p>ИТОГО</p></td>
      
        <td  >
            <p align="center">0</p></td>
        <td  ><p align="center"><?=$data['local'][0]['total']['pass'] + $data['local'][0]['simple']['pass']?></p></td>
		  <td  ><p>&nbsp;</p></td>
        <td  ><p align="center">&nbsp;</p></td>
        <td  ><p align="center">0</p></td>
        <td  ><p align="center"><?=$data['local'][0]['total']['retry_1'] + $data['local'][0]['simple']['retry_1']?></p></td>
        <td  ><p align="center"><?=$data['local'][0]['total']['retry_2'] + $data['local'][0]['simple']['retry_2']?></p></td>
        <td  ><p align="center">&nbsp;</p></td>
    </tr>
</table>
<p>&nbsp;</p>
<br clear="all" />
<div>
    <p align="right">Приложение</p>
    <p align="center"><strong>Сведения  о проведении комплексного экзамена по русскому языку, истории России и основам  законодательства</strong></p>
    <p>Численность иностранных граждан, сдавших комплексный  экзамен, в <strong>локальных центрах  тестирования</strong>, чел. (уровень РВ)</p>
    <p align="right">Таблица 2.2</p>
    <table border="1" cellspacing="0" cellpadding="0" >
        <tr>
            <td  rowspan="2" ><p align="center">Категории    иностранных граждан</p></td>
            <td  colspan="2" ><p align="center">Численность    иностранных граждан, сдавших комплексный экзамен, в центрах тестирования,    чел.</p></td>
            <td  colspan="2" ><p align="center">Стоимость сдачи    комплексного экзамена, руб.</p></td>
            <td  colspan="3" ><p align="center">Численность    иностранных граждан, пересдавших комплексный экзамен, в центрах тестирования,    чел.</p></td>
            <td  ><p align="center">Стоимость    пересдачи комплексного экзамена, руб.</p></td>
        </tr>
        <tr>
            <td  ><p align="center">планируемая на    <?=date('Y')?>г.</p></td>
            <td  ><p align="center">фактическая на    <?=$begin->format('d.m.Y');?> - <?=$end->format('d.m.Y');?></p></td>
            <td  ><p>Плата    локальных центров головному вузу, руб.</p></td>
            <td  ><p>Доход    головного вуза от сдачи комплексного экзамена в локальных центрах, руб.</p></td>
            <td  ><p align="center">планируемая    <?=date('Y')?>г.</p></td>
            <td  ><p align="center">фактическая на    <?=$begin->format('d.m.Y');?> - <?=$end->format('d.m.Y');?><br />
                    1 пересдача</p></td>
            <td  ><p align="center">2 пересдача</p></td>
            <td  ><p align="center">1-2 компонента</p></td>
        </tr>
        <tr>
            <td  ><p>Комплексный    экзамен для соискателей разрешения на временное проживание</p></td>
            <td  ><p align="center">0</p></td>
            <td  ><p align="center"><?=$data['local'][1]['total']['pass']?></p></td>
            <td  ><p>1590</p></td>
            <td  ><p>&nbsp;</p></td>
            <td  ><p align="center">0</p></td>
            <td  ><p align="center"><?=$data['local'][1]['total']['retry_1']?></p></td>
            <td  ><p align="center"><?=$data['local'][1]['total']['retry_2']?></p></td>
            <td  ><p align="center">800-1000</p></td>
        </tr>
        <tr>
            <td  ><p>Комплексный    экзамен для  соискателей разрешения на    временное проживание (упрощенная процедура)</p></td>
            <td  ><p align="center">0</p></td>
            <td  ><p align="center"><?=$data['local'][1]['simple']['pass']?></p></td>
            <td  ><p>600</p></td>
            <td  ><p>&nbsp;</p></td>
            <td  ><p align="center">0</p></td>
            <td  ><p align="center"><?=$data['local'][1]['simple']['retry_1']?></p></td>
            <td  ><p align="center"><?=$data['local'][1]['simple']['retry_2']?></p></td>
            <td  ><p align="center">-</p></td>
        </tr>
        <tr>
            <td  ><p>Комплексный    экзамен для  соискателей разрешения на    временное проживание, жителей ДНР и ЛНР</p></td>
            <td  ><p align="center">0</p></td>
            <td  ><p align="center"><?=$data['local'][1]['dnr_total']['pass']?></p></td>
            <td  ><p>950</p></td>
            <td  ><p>&nbsp;</p></td>
            <td  ><p align="center">0</p></td>
            <td  ><p align="center"><?=$data['local'][1]['dnr_total']['retry_1']?></p></td>
            <td  ><p align="center"><?=$data['local'][1]['dnr_total']['retry_2']?></p></td>
            <td  ><p align="center">-</p></td>
        </tr>
        <tr>
            <td  ><p>Комплексный    экзамен для  соискателей разрешения на    временное проживание, жителей ДНР и ЛНР, подтвердивших владение русским    языком документом об образовании (упрощенная процедура)</p></td>
            <td  ><p align="center">0</p></td>
            <td  ><p align="center"><?=$data['local'][1]['dnr_simple']['pass']?></p></td>
            <td  ><p>400</p></td>
            <td  ><p>&nbsp;</p></td>
            <td  ><p align="center">0</p></td>
            <td  ><p align="center"><?=$data['local'][1]['dnr_simple']['retry_1']?></p></td>
            <td  ><p align="center"><?=$data['local'][1]['dnr_simple']['retry_2']?></p></td>
            <td  ><p align="center">-</p></td>
        </tr>
        <tr>
            <td  ><p>ИТОГО</p></td>
      
        <td  ><p align="center">0</p></td>
            <td  ><p align="center"><?=$data['local'][1]['total']['pass'] + $data['local'][1]['simple']['pass']?></p></td>
			  <td  ><p>&nbsp;</p></td>
            <td  ><p align="center">&nbsp;</p></td>
            <td  ><p align="center">0</p></td>
            <td  ><p align="center"><?=$data['local'][1]['total']['retry_1'] + $data['local'][1]['simple']['retry_1']?></p></td>
            <td  ><p align="center"><?=$data['local'][1]['total']['retry_2'] + $data['local'][1]['simple']['retry_2']?></p></td>
            <td  ><p align="center">&nbsp;</p></td>
        </tr>
    </table>
    <p>&nbsp;</p>
</div>
<br clear="all" />
<p align="right">Приложение</p>
<p align="center"><strong>Сведения  о проведении комплексного экзамена по русскому языку, истории России и основам  законодательства</strong></p>
<p>Численность иностранных граждан, сдавших комплексный  экзамен, в <strong>локальных центрах  тестирования</strong>, чел. (уровень ВЖ)</p>
<p align="right">Таблица 2.3</p>
<table border="1" cellspacing="0" cellpadding="0" >
    <tr>
        <td  rowspan="2" ><p align="center">Категории    иностранных граждан</p></td>
        <td  colspan="2" ><p align="center">Численность    иностранных граждан, сдавших комплексный экзамен, в центрах тестирования,    чел.</p></td>
        <td  colspan="2" ><p align="center">Стоимость сдачи    комплексного экзамена, руб.</p></td>
        <td  colspan="3" ><p align="center">Численность    иностранных граждан, пересдавших комплексный экзамен, в центрах тестирования,    чел.</p></td>
        <td  ><p align="center">Стоимость    пересдачи комплексного экзамена, руб.</p></td>
    </tr>
    <tr>
        <td  ><p align="center">планируемая на    <?=date('Y')?>г.</p></td>
        <td  ><p align="center">фактическая на    <?=$begin->format('d.m.Y');?> - <?=$end->format('d.m.Y');?></p></td>
        <td  ><p>Плата    локальных центров головному вузу, руб.</p></td>
        <td  ><p>Доход    головного вуза от сдачи комплексного экзамена в локальных центрах, руб.</p></td>
        <td  ><p align="center">планируемая    <?=date('Y')?>г.</p></td>
        <td  ><p align="center">фактическая на    <?=$begin->format('d.m.Y');?> - <?=$end->format('d.m.Y');?><br />
                1 пересдача</p></td>
        <td  ><p align="center">2 пересдача</p></td>
        <td  ><p align="center">1-2 компонента</p></td>
    </tr>
    <tr>
        <td  ><p>Комплексный    экзамен для соискателей вида на жительство</p></td>
        <td  ><p align="center">0</p></td>
        <td  ><p align="center"><?=$data['local'][2]['total']['pass']?></p></td>
        <td  ><p>1590</p></td>
        <td  ><p>&nbsp;</p></td>
        <td  ><p align="center">0</p></td>
        <td  ><p align="center"><?=$data['local'][2]['total']['retry_1']?></p></td>
        <td  ><p align="center"><?=$data['local'][2]['total']['retry_2']?></p></td>
        <td  ><p align="center">800-1000</p></td>
    </tr>
    <tr>
        <td  ><p>Комплексный    экзамен для   соискателей вида на    жительство (упрощенная процедура)</p></td>
        <td  ><p align="center">0</p></td>
        <td  ><p align="center"><?=$data['local'][2]['simple']['pass']?></p></td>
        <td  ><p>600</p></td>
        <td  ><p>&nbsp;</p></td>
        <td  ><p align="center">0</p></td>
        <td  ><p align="center"><?=$data['local'][2]['simple']['retry_1']?></p></td>
        <td  ><p align="center"><?=$data['local'][2]['simple']['retry_2']?></p></td>
        <td  ><p align="center">-</p></td>
    </tr>
    <tr>
        <td  ><p>Комплексный    экзамен для   соискателей вида на    жительство , жителей ДНР и ЛНР</p></td>
        <td  ><p align="center">0</p></td>
        <td  ><p align="center"><?=$data['local'][2]['dnr_total']['pass']?></p></td>
        <td  ><p>950</p></td>
        <td  ><p>&nbsp;</p></td>
        <td  ><p align="center">0</p></td>
        <td  ><p align="center"><?=$data['local'][2]['dnr_total']['retry_1']?></p></td>
        <td  ><p align="center"><?=$data['local'][2]['dnr_total']['retry_2']?></p></td>
        <td  ><p align="center">-</p></td>
    </tr>
    <tr>
        <td  ><p>Комплексный    экзамен для  соискателей вида на    жительство, жителей ДНР и ЛНР, подтвердивших владение русским языком    документом об образовании (упрощенная процедура)</p></td>
        <td  ><p align="center">0</p></td>
        <td  ><p align="center"><?=$data['local'][2]['dnr_simple']['pass']?></p></td>
        <td  ><p>400</p></td>
        <td  ><p>&nbsp;</p></td>
        <td  ><p align="center">0</p></td>
        <td  ><p align="center"><?=$data['local'][2]['dnr_simple']['retry_1']?></p></td>
        <td  ><p align="center"><?=$data['local'][2]['dnr_simple']['retry_2']?></p></td>
        <td  ><p align="center">-</p></td>
    </tr>
    <tr>
        <td  ><p>ИТОГО</p></td>
        <td  ><p align="center">0</p></td>
        <td  ><p align="center"><?=$data['local'][2]['total']['pass'] + $data['local'][2]['simple']['pass']?></p></td>
		  <td  ><p>&nbsp;</p></td>
        <td  ><p align="center">&nbsp;</p></td>
        <td  ><p align="center">0</p></td>
        <td  ><p align="center"><?=$data['local'][2]['total']['retry_1'] + $data['local'][2]['simple']['retry_1']?></p></td>
        <td  ><p align="center"><?=$data['local'][2]['total']['retry_2'] + $data['local'][2]['simple']['retry_2']?></p></td>
        <td  ><p align="center">&nbsp;</p></td>
    </tr>
</table>
<p>&nbsp;</p>
