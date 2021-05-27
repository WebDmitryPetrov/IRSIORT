<form action="" method="get">
    <input type="hidden" name="action" value="exam_statist_fin_check">
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


<? if ($array):?>

<h1>Для внутенних лок. центров РУДН</h1>
<h2>Статистика работы внутренних локальных центров РУДН (суммарное значение) по различным типам услуг</h2>
<h2> при проведении комплексного экзамена с <?= $begin->format('d.m.Y') ?> по <?= $end->format('d.m.Y') ?></h2>
<? $current = $array['local']; ?>
<? $dubl = $dubl_array['local']; ?>
<table class="table table-bordered">
   <thead>
    <tr>
        <td rowspan="2">Наименование услуги</td>
        <td colspan="4">Количество первично мигрантов, сдавших экзамен</td>
        <td colspan="4">Количество мигрантов, несдавших экзамен</td>
        <td>&nbsp;</td>
        <td colspan="4">Количество мигрантов пересдавших 2 теста</td>
        <td colspan="4">Количество мигрантов пересдавших 1 тест</td>
    </tr>
    <tr>
        <td>разрешение на работу или патент (РНР)</td>
        <td>разрешение на временное проживание (РВЖ)</td>
        <td>вид на жительство (ВНЖ)</td>
        <td>общее количество</td>
        <td>разрешение на работу или патент (РНР)</td>
        <td>разрешение на временное проживание (РВЖ)</td>
        <td>вид на жительство (ВНЖ)</td>
        <td>общее количество</td>
        <td>Суммарное количество первичных мигрантов, участвующих в экзамене</td>
        <td>разрешение на работу или патент (РНР)</td>
        <td>разрешение на временное проживание (РВЖ)</td>
        <td>вид на жительство (ВНЖ)</td>
        <td>общее количество</td>
        <td>разрешение на работу или патент (РНР)</td>
        <td>разрешение на временное проживание (РВЖ)</td>
        <td>вид на жительство (ВНЖ)</td>
        <td>общее количество</td>
    </tr>
   </thead>
    <tbody>
    <? $type = $current['simple'] ?>
    <tr>
        <td>Стандартная тестовая сессия</td>
        <td><?= $type['rnr']['cc']['first'] ?></td>
        <td><?= $type['rvj']['cc']['first'] ?></td>
        <td><?= $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['note'] ?></td>
        <td><?= $type['rvj']['cc']['note'] ?></td>
        <td><?= $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
            + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['two'] ?></td>
        <td><?= $type['rvj']['cc']['two'] ?></td>
        <td><?= $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['one'] ?></td>
        <td><?= $type['rvj']['cc']['one'] ?></td>
        <td><?= $type['vnj']['cc']['one'] ?></td>
        <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
    </tr>
    <? $type = $current['simple_simple'] ?>
    <tr>
        <td>Сдача недостающих модулей «История России» и «Основы законодательства РФ» для стандартных ТС</td>
        <td><?= $type['rnr']['cc']['first'] ?></td>
        <td><?= $type['rvj']['cc']['first'] ?></td>
        <td><?= $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['note'] ?></td>
        <td><?= $type['rvj']['cc']['note'] ?></td>
        <td><?= $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
            + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['two'] ?></td>
        <td><?= $type['rvj']['cc']['two'] ?></td>
        <td><?= $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['one'] ?></td>
        <td><?= $type['rvj']['cc']['one'] ?></td>
        <td><?= $type['vnj']['cc']['one'] ?></td>
        <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
    </tr>
    <tr>
        <? $type = $current['dnr'] ?>
        <td>Для ДНР и ЛНР</td>
        <td><?= $type['rnr']['cc']['first'] ?></td>
        <td><?= $type['rvj']['cc']['first'] ?></td>
        <td><?= $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['note'] ?></td>
        <td><?= $type['rvj']['cc']['note'] ?></td>
        <td><?= $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
            + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['two'] ?></td>
        <td><?= $type['rvj']['cc']['two'] ?></td>
        <td><?= $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['one'] ?></td>
        <td><?= $type['rvj']['cc']['one'] ?></td>
        <td><?= $type['vnj']['cc']['one'] ?></td>
        <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
    </tr>
    <tr>
        <? $type = $current['dnr_simple'] ?>
        <td>Экзамен по сокращенной процедуре для ДНР и ЛНР (модули "История России" и "Основы законодательства РФ")</td>
        <td><?= $type['rnr']['cc']['first'] ?></td>
        <td><?= $type['rvj']['cc']['first'] ?></td>
        <td><?= $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['note'] ?></td>
        <td><?= $type['rvj']['cc']['note'] ?></td>
        <td><?= $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
            + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['two'] ?></td>
        <td><?= $type['rvj']['cc']['two'] ?></td>
        <td><?= $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['one'] ?></td>
        <td><?= $type['rvj']['cc']['one'] ?></td>
        <td><?= $type['vnj']['cc']['one'] ?></td>
        <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
    </tr>
    <tr>
        <? $type = $current['ukraine'] ?>
        <td>Сокращенная на уровень ИР для граждан украины, владеющих русским языком</td>
        <td><?= $type['rnr']['cc']['first'] ?></td>
        <td><?= $type['rvj']['cc']['first'] ?></td>
        <td><?= $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['note'] ?></td>
        <td><?= $type['rvj']['cc']['note'] ?></td>
        <td><?= $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
            + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['two'] ?></td>
        <td><?= $type['rvj']['cc']['two'] ?></td>
        <td><?= $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['one'] ?></td>
        <td><?= $type['rvj']['cc']['one'] ?></td>
        <td><?= $type['vnj']['cc']['one'] ?></td>
        <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
    </tr>
    <tr>
        <? $type = $current['invalid'] ?>
        <td>Для лиц с ограниченными возможностями</td>
        <td><?= $type['rnr']['cc']['first'] ?></td>
        <td><?= $type['rvj']['cc']['first'] ?></td>
        <td><?= $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['note'] ?></td>
        <td><?= $type['rvj']['cc']['note'] ?></td>
        <td><?= $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
            + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['two'] ?></td>
        <td><?= $type['rvj']['cc']['two'] ?></td>
        <td><?= $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['one'] ?></td>
        <td><?= $type['rvj']['cc']['one'] ?></td>
        <td><?= $type['vnj']['cc']['one'] ?></td>
        <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
    </tr>
    <tr>
        <td>Колич. созданных дубликатов</td>
        <td><?=$dubl['rnr']['cc']?></td>
        <td><?=$dubl['rvj']['cc']?></td>
        <td><?=$dubl['vnj']['cc']?></td>
        <td><?=$dubl['rnr']['cc'] + $dubl['rvj']['cc'] + $dubl['vnj']['cc']  ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    </tbody>
</table>

<hr>
<p>&nbsp;</p><h1>Для внешних лок. центров РУДН</h1>
<h2>Статистика работы внутренних локальных центров РУДН (суммарное значение) по различным типам услуг</h2>
<h2> при проведении комплексного экзамена с <?= $begin->format('d.m.Y') ?> по <?= $end->format('d.m.Y') ?></h2>
<? $current = $array['external']; ?>
    <? $dubl = $dubl_array['external']; ?>
<table class="table table-bordered">
   <thead>
    <tr>
        <td rowspan="2">Наименование услуги</td>
        <td colspan="4">Количество первично мигрантов, сдавших экзамен</td>
        <td colspan="4">Количество мигрантов, несдавших экзамен</td>
        <td>&nbsp;</td>
        <td colspan="4">Количество мигрантов пересдавших 2 теста</td>
        <td colspan="4">Количество мигрантов пересдавших 1 тест</td>
    </tr>
    <tr>
        <td>разрешение на работу или патент (РНР)</td>
        <td>разрешение на временное проживание (РВЖ)</td>
        <td>вид на жительство (ВНЖ)</td>
        <td>общее количество</td>
        <td>разрешение на работу или патент (РНР)</td>
        <td>разрешение на временное проживание (РВЖ)</td>
        <td>вид на жительство (ВНЖ)</td>
        <td>общее количество</td>
        <td>Суммарное количество первичных мигрантов, участвующих в экзамене</td>
        <td>разрешение на работу или патент (РНР)</td>
        <td>разрешение на временное проживание (РВЖ)</td>
        <td>вид на жительство (ВНЖ)</td>
        <td>общее количество</td>
        <td>разрешение на работу или патент (РНР)</td>
        <td>разрешение на временное проживание (РВЖ)</td>
        <td>вид на жительство (ВНЖ)</td>
        <td>общее количество</td>
    </tr>
   </thead>
    <tbody>
    <? $type = $current['simple'] ?>
    <tr>
        <td>Стандартная тестовая сессия</td>
        <td><?= $type['rnr']['cc']['first'] ?></td>
        <td><?= $type['rvj']['cc']['first'] ?></td>
        <td><?= $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['note'] ?></td>
        <td><?= $type['rvj']['cc']['note'] ?></td>
        <td><?= $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
            + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['two'] ?></td>
        <td><?= $type['rvj']['cc']['two'] ?></td>
        <td><?= $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['one'] ?></td>
        <td><?= $type['rvj']['cc']['one'] ?></td>
        <td><?= $type['vnj']['cc']['one'] ?></td>
        <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
    </tr>
    <? $type = $current['simple_simple'] ?>
    <tr>
        <td>Сдача недостающих модулей «История России» и «Основы законодательства РФ» для стандартных ТС</td>
        <td><?= $type['rnr']['cc']['first'] ?></td>
        <td><?= $type['rvj']['cc']['first'] ?></td>
        <td><?= $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['note'] ?></td>
        <td><?= $type['rvj']['cc']['note'] ?></td>
        <td><?= $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
            + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['two'] ?></td>
        <td><?= $type['rvj']['cc']['two'] ?></td>
        <td><?= $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['one'] ?></td>
        <td><?= $type['rvj']['cc']['one'] ?></td>
        <td><?= $type['vnj']['cc']['one'] ?></td>
        <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
    </tr>
    <tr>
        <? $type = $current['dnr'] ?>
        <td>Для ДНР и ЛНР</td>
        <td><?= $type['rnr']['cc']['first'] ?></td>
        <td><?= $type['rvj']['cc']['first'] ?></td>
        <td><?= $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['note'] ?></td>
        <td><?= $type['rvj']['cc']['note'] ?></td>
        <td><?= $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
            + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['two'] ?></td>
        <td><?= $type['rvj']['cc']['two'] ?></td>
        <td><?= $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['one'] ?></td>
        <td><?= $type['rvj']['cc']['one'] ?></td>
        <td><?= $type['vnj']['cc']['one'] ?></td>
        <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
    </tr>
    <tr>
        <? $type = $current['dnr_simple'] ?>
        <td>Экзамен по сокращенной процедуре для ДНР и ЛНР (модули "История России" и "Основы законодательства РФ")</td>
        <td><?= $type['rnr']['cc']['first'] ?></td>
        <td><?= $type['rvj']['cc']['first'] ?></td>
        <td><?= $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['note'] ?></td>
        <td><?= $type['rvj']['cc']['note'] ?></td>
        <td><?= $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
            + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['two'] ?></td>
        <td><?= $type['rvj']['cc']['two'] ?></td>
        <td><?= $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['one'] ?></td>
        <td><?= $type['rvj']['cc']['one'] ?></td>
        <td><?= $type['vnj']['cc']['one'] ?></td>
        <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
    </tr>
    <tr>
        <? $type = $current['ukraine'] ?>
        <td>Сокращенная на уровень ИР для граждан украины, владеющих русским языком</td>
        <td><?= $type['rnr']['cc']['first'] ?></td>
        <td><?= $type['rvj']['cc']['first'] ?></td>
        <td><?= $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['note'] ?></td>
        <td><?= $type['rvj']['cc']['note'] ?></td>
        <td><?= $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
            + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['two'] ?></td>
        <td><?= $type['rvj']['cc']['two'] ?></td>
        <td><?= $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['one'] ?></td>
        <td><?= $type['rvj']['cc']['one'] ?></td>
        <td><?= $type['vnj']['cc']['one'] ?></td>
        <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
    </tr>
    <tr>
        <? $type = $current['invalid'] ?>
        <td>Для лиц с ограниченными возможностями</td>
        <td><?= $type['rnr']['cc']['first'] ?></td>
        <td><?= $type['rvj']['cc']['first'] ?></td>
        <td><?= $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['note'] ?></td>
        <td><?= $type['rvj']['cc']['note'] ?></td>
        <td><?= $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
            + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['two'] ?></td>
        <td><?= $type['rvj']['cc']['two'] ?></td>
        <td><?= $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['one'] ?></td>
        <td><?= $type['rvj']['cc']['one'] ?></td>
        <td><?= $type['vnj']['cc']['one'] ?></td>
        <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
    </tr>
    <tr>
        <td>Колич. созданных дубликатов</td>
        <td><?=$dubl['rnr']['cc']?></td>
        <td><?=$dubl['rvj']['cc']?></td>
        <td><?=$dubl['vnj']['cc']?></td>
        <td><?=$dubl['rnr']['cc'] + $dubl['rvj']['cc'] + $dubl['vnj']['cc']  ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    </tbody>
</table>
<p>&nbsp;</p>

    <?foreach ($hc_list as $hc):?>
        <hr>
        <hr>
        <hr>


        <h1>Для внутенних лок. центров <?=$hc['caption']?></h1>
        <h2>Статистика работы внутренних локальных центров РУДН (суммарное значение) по различным типам услуг</h2>
        <h2> при проведении комплексного экзамена с <?= $begin->format('d.m.Y') ?> по <?= $end->format('d.m.Y') ?></h2>
        <? $current = $hc['result']['local']; ?>

        <table class="table table-bordered">
            <thead>
            <tr>
                <td rowspan="2">Наименование услуги</td>
                <td colspan="4">Количество первично мигрантов, сдавших экзамен</td>
                <td colspan="4">Количество мигрантов, несдавших экзамен</td>
                <td>&nbsp;</td>
                <td colspan="4">Количество мигрантов пересдавших 2 теста</td>
                <td colspan="4">Количество мигрантов пересдавших 1 тест</td>
            </tr>
            <tr>
                <td>разрешение на работу или патент (РНР)</td>
                <td>разрешение на временное проживание (РВЖ)</td>
                <td>вид на жительство (ВНЖ)</td>
                <td>общее количество</td>
                <td>разрешение на работу или патент (РНР)</td>
                <td>разрешение на временное проживание (РВЖ)</td>
                <td>вид на жительство (ВНЖ)</td>
                <td>общее количество</td>
                <td>Суммарное количество первичных мигрантов, участвующих в экзамене</td>
                <td>разрешение на работу или патент (РНР)</td>
                <td>разрешение на временное проживание (РВЖ)</td>
                <td>вид на жительство (ВНЖ)</td>
                <td>общее количество</td>
                <td>разрешение на работу или патент (РНР)</td>
                <td>разрешение на временное проживание (РВЖ)</td>
                <td>вид на жительство (ВНЖ)</td>
                <td>общее количество</td>
            </tr>
            </thead>
            <tbody>
            <? $type = $current['simple'] ?>
            <tr>
                <td>Стандартная тестовая сессия</td>
                <td><?= $type['rnr']['cc']['first'] ?></td>
                <td><?= $type['rvj']['cc']['first'] ?></td>
                <td><?= $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['note'] ?></td>
                <td><?= $type['rvj']['cc']['note'] ?></td>
                <td><?= $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
                    + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['two'] ?></td>
                <td><?= $type['rvj']['cc']['two'] ?></td>
                <td><?= $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['one'] ?></td>
                <td><?= $type['rvj']['cc']['one'] ?></td>
                <td><?= $type['vnj']['cc']['one'] ?></td>
                <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
            </tr>
            <? $type = $current['simple_simple'] ?>
            <tr>
                <td>Сдача недостающих модулей «История России» и «Основы законодательства РФ» для стандартных ТС</td>
                <td><?= $type['rnr']['cc']['first'] ?></td>
                <td><?= $type['rvj']['cc']['first'] ?></td>
                <td><?= $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['note'] ?></td>
                <td><?= $type['rvj']['cc']['note'] ?></td>
                <td><?= $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
                    + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['two'] ?></td>
                <td><?= $type['rvj']['cc']['two'] ?></td>
                <td><?= $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['one'] ?></td>
                <td><?= $type['rvj']['cc']['one'] ?></td>
                <td><?= $type['vnj']['cc']['one'] ?></td>
                <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
            </tr>
            <tr>
                <? $type = $current['dnr'] ?>
                <td>Для ДНР и ЛНР</td>
                <td><?= $type['rnr']['cc']['first'] ?></td>
                <td><?= $type['rvj']['cc']['first'] ?></td>
                <td><?= $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['note'] ?></td>
                <td><?= $type['rvj']['cc']['note'] ?></td>
                <td><?= $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
                    + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['two'] ?></td>
                <td><?= $type['rvj']['cc']['two'] ?></td>
                <td><?= $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['one'] ?></td>
                <td><?= $type['rvj']['cc']['one'] ?></td>
                <td><?= $type['vnj']['cc']['one'] ?></td>
                <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
            </tr>
            <tr>
                <? $type = $current['dnr_simple'] ?>
                <td>Экзамен по сокращенной процедуре для ДНР и ЛНР (модули "История России" и "Основы законодательства РФ")</td>
                <td><?= $type['rnr']['cc']['first'] ?></td>
                <td><?= $type['rvj']['cc']['first'] ?></td>
                <td><?= $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['note'] ?></td>
                <td><?= $type['rvj']['cc']['note'] ?></td>
                <td><?= $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
                    + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['two'] ?></td>
                <td><?= $type['rvj']['cc']['two'] ?></td>
                <td><?= $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['one'] ?></td>
                <td><?= $type['rvj']['cc']['one'] ?></td>
                <td><?= $type['vnj']['cc']['one'] ?></td>
                <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
            </tr>
            <tr>
                <? $type = $current['ukraine'] ?>
                <td>Сокращенная на уровень ИР для граждан украины, владеющих русским языком</td>
                <td><?= $type['rnr']['cc']['first'] ?></td>
                <td><?= $type['rvj']['cc']['first'] ?></td>
                <td><?= $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['note'] ?></td>
                <td><?= $type['rvj']['cc']['note'] ?></td>
                <td><?= $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
                    + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['two'] ?></td>
                <td><?= $type['rvj']['cc']['two'] ?></td>
                <td><?= $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['one'] ?></td>
                <td><?= $type['rvj']['cc']['one'] ?></td>
                <td><?= $type['vnj']['cc']['one'] ?></td>
                <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
            </tr>
            <tr>
                <? $type = $current['invalid'] ?>
                <td>Для лиц с ограниченными возможностями</td>
                <td><?= $type['rnr']['cc']['first'] ?></td>
                <td><?= $type['rvj']['cc']['first'] ?></td>
                <td><?= $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['note'] ?></td>
                <td><?= $type['rvj']['cc']['note'] ?></td>
                <td><?= $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
                    + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['two'] ?></td>
                <td><?= $type['rvj']['cc']['two'] ?></td>
                <td><?= $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['one'] ?></td>
                <td><?= $type['rvj']['cc']['one'] ?></td>
                <td><?= $type['vnj']['cc']['one'] ?></td>
                <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
            </tr>

            </tbody>
        </table>

        <hr>
        <p>&nbsp;</p><h1>Для внешних лок. центров <?=$hc['caption']?></h1>
        <h2>Статистика работы внутренних локальных центров РУДН (суммарное значение) по различным типам услуг</h2>
        <h2> при проведении комплексного экзамена с <?= $begin->format('d.m.Y') ?> по <?= $end->format('d.m.Y') ?></h2>
        <? $current = $hc['result']['external']; ?>

        <table class="table table-bordered">
            <thead>
            <tr>
                <td rowspan="2">Наименование услуги</td>
                <td colspan="4">Количество первично мигрантов, сдавших экзамен</td>
                <td colspan="4">Количество мигрантов, несдавших экзамен</td>
                <td>&nbsp;</td>
                <td colspan="4">Количество мигрантов пересдавших 2 теста</td>
                <td colspan="4">Количество мигрантов пересдавших 1 тест</td>
            </tr>
            <tr>
                <td>разрешение на работу или патент (РНР)</td>
                <td>разрешение на временное проживание (РВЖ)</td>
                <td>вид на жительство (ВНЖ)</td>
                <td>общее количество</td>
                <td>разрешение на работу или патент (РНР)</td>
                <td>разрешение на временное проживание (РВЖ)</td>
                <td>вид на жительство (ВНЖ)</td>
                <td>общее количество</td>
                <td>Суммарное количество первичных мигрантов, участвующих в экзамене</td>
                <td>разрешение на работу или патент (РНР)</td>
                <td>разрешение на временное проживание (РВЖ)</td>
                <td>вид на жительство (ВНЖ)</td>
                <td>общее количество</td>
                <td>разрешение на работу или патент (РНР)</td>
                <td>разрешение на временное проживание (РВЖ)</td>
                <td>вид на жительство (ВНЖ)</td>
                <td>общее количество</td>
            </tr>
            </thead>
            <tbody>
            <? $type = $current['simple'] ?>
            <tr>
                <td>Стандартная тестовая сессия</td>
                <td><?= $type['rnr']['cc']['first'] ?></td>
                <td><?= $type['rvj']['cc']['first'] ?></td>
                <td><?= $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['note'] ?></td>
                <td><?= $type['rvj']['cc']['note'] ?></td>
                <td><?= $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
                    + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['two'] ?></td>
                <td><?= $type['rvj']['cc']['two'] ?></td>
                <td><?= $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['one'] ?></td>
                <td><?= $type['rvj']['cc']['one'] ?></td>
                <td><?= $type['vnj']['cc']['one'] ?></td>
                <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
            </tr>
            <? $type = $current['simple_simple'] ?>
            <tr>
                <td>Сдача недостающих модулей «История России» и «Основы законодательства РФ» для стандартных ТС</td>
                <td><?= $type['rnr']['cc']['first'] ?></td>
                <td><?= $type['rvj']['cc']['first'] ?></td>
                <td><?= $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['note'] ?></td>
                <td><?= $type['rvj']['cc']['note'] ?></td>
                <td><?= $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
                    + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['two'] ?></td>
                <td><?= $type['rvj']['cc']['two'] ?></td>
                <td><?= $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['one'] ?></td>
                <td><?= $type['rvj']['cc']['one'] ?></td>
                <td><?= $type['vnj']['cc']['one'] ?></td>
                <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
            </tr>
            <tr>
                <? $type = $current['dnr'] ?>
                <td>Для ДНР и ЛНР</td>
                <td><?= $type['rnr']['cc']['first'] ?></td>
                <td><?= $type['rvj']['cc']['first'] ?></td>
                <td><?= $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['note'] ?></td>
                <td><?= $type['rvj']['cc']['note'] ?></td>
                <td><?= $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
                    + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['two'] ?></td>
                <td><?= $type['rvj']['cc']['two'] ?></td>
                <td><?= $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['one'] ?></td>
                <td><?= $type['rvj']['cc']['one'] ?></td>
                <td><?= $type['vnj']['cc']['one'] ?></td>
                <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
            </tr>
            <tr>
                <? $type = $current['dnr_simple'] ?>
                <td>Экзамен по сокращенной процедуре для ДНР и ЛНР (модули "История России" и "Основы законодательства РФ")</td>
                <td><?= $type['rnr']['cc']['first'] ?></td>
                <td><?= $type['rvj']['cc']['first'] ?></td>
                <td><?= $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['note'] ?></td>
                <td><?= $type['rvj']['cc']['note'] ?></td>
                <td><?= $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
                    + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['two'] ?></td>
                <td><?= $type['rvj']['cc']['two'] ?></td>
                <td><?= $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['one'] ?></td>
                <td><?= $type['rvj']['cc']['one'] ?></td>
                <td><?= $type['vnj']['cc']['one'] ?></td>
                <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
            </tr>
            <tr>
                <? $type = $current['ukraine'] ?>
                <td>Сокращенная на уровень ИР для граждан украины, владеющих русским языком</td>
                <td><?= $type['rnr']['cc']['first'] ?></td>
                <td><?= $type['rvj']['cc']['first'] ?></td>
                <td><?= $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['note'] ?></td>
                <td><?= $type['rvj']['cc']['note'] ?></td>
                <td><?= $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
                    + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['two'] ?></td>
                <td><?= $type['rvj']['cc']['two'] ?></td>
                <td><?= $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['one'] ?></td>
                <td><?= $type['rvj']['cc']['one'] ?></td>
                <td><?= $type['vnj']['cc']['one'] ?></td>
                <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
            </tr>
            <tr>
                <? $type = $current['invalid'] ?>
                <td>Для лиц с ограниченными возможностями</td>
                <td><?= $type['rnr']['cc']['first'] ?></td>
                <td><?= $type['rvj']['cc']['first'] ?></td>
                <td><?= $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['note'] ?></td>
                <td><?= $type['rvj']['cc']['note'] ?></td>
                <td><?= $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
                    + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['two'] ?></td>
                <td><?= $type['rvj']['cc']['two'] ?></td>
                <td><?= $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['one'] ?></td>
                <td><?= $type['rvj']['cc']['one'] ?></td>
                <td><?= $type['vnj']['cc']['one'] ?></td>
                <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
            </tr>

            </tbody>
        </table>

        <?endforeach?>
<?endif?>