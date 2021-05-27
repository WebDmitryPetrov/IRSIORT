<?
?>
    <style>
        tr.type-head {
            font-weight: bold;
        }
    </style>
<? if (!empty($caption)): ?>
    <h1><?= $caption ?></h1>
<? else: ?>
    <h1>Статистика работы локальных центров РУДН </h1>
<? endif ?>
    <form action="" method="POST">

        <label>от :
            <div class="input-prepend date datepicker "
                 data-date="">
                <span class="add-on"><i class="icon-th"></i> </span> <input
                    class="input-small"
                    name="from"

                    readonly="readonly" size="16" type="text"
                    value="<?= $from ?>">
            </div>
        </label> <label>До :
            <div class="input-prepend date datepicker "
                 data-date="">
                <span class="add-on"><i class="icon-th"></i> </span> <input
                    class="input-small"
                    name="to"

                    readonly="readonly" size="16" type="text"
                    value="<?= $to ?>">
            </div>
        </label>
        <input type="submit" value="Отфильтровать">
    </form>
<? if (!empty($search)): ?>
    <h1>Информация о прохождении  с <?= $from ?> по  <?= $to ?> </h1>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th><p>Образовательная организация</p></th>
            <th><p>Количество организаций-партнеров, с которым заключены соглашения</p></th>
<!--            <th><p>Количество заявлений, поданных на сдачу экзамена</p></th>-->
            <th colspan="10"><p>Количество мигрантов, сдавших экзамен</p></th>
            <th><p>Количество выданных сертификатов</p></th>
 <th colspan="10"><p>Количество мигрантов, НЕ сдавших экзамен</p></th>
            <th><p>Количество выданных справок</p></th>
<!--            <th><p>Наличие проблем</p></th>-->
        </tr>
        <tr>
            <th><p>&nbsp;</p></th>
            <th><p>&nbsp;</p></th>

            <th><p>Базовый для иностранных работников</p></th>
            <th><p>ТРКИ "Элементарный" А1</p></th>
            <th><p>ТРКИ "Базовый" А2</p></th>
            <th><p>ТРКИ "Первый" В1</p></th>
            <th><p>ТРКИ "Второй" В2</p></th>
            <th><p>ТРКИ "Третий" С1</p></th>
            <th><p>ТРКИ "Четвертый" С2</p></th>
            <th><p>Базовый Гражданство (485) </p></th>
            <th><p>Базовый Гражданство (730) </p></th>
            <th><p>общее количество </p></th>
            <th><p>&nbsp;</p></th>

            <th><p>Базовый для иностранных работников</p></th>
            <th><p>ТРКИ "Элементарный" А1</p></th>
            <th><p>ТРКИ "Базовый" А2</p></th>
            <th><p>ТРКИ "Первый" В1</p></th>
            <th><p>ТРКИ "Второй" В2</p></th>
            <th><p>ТРКИ "Третий" С1</p></th>
            <th><p>ТРКИ "Четвертый" С2</p></th>
            <th><p>Базовый Гражданство (485) </p></th>
            <th><p>Базовый Гражданство (730) </p></th>
            <th><p>общее количество </p></th>
            <th><p>&nbsp;</p></th>

        </tr>
        </thead>
        <tbody>
        <? foreach ($array as $item): ?>
            <tr>
                <td><?= $item['caption'] ?></td>
                <td><?= $item['data']['orgs'] ?></td>

                <td><?= $item['data']['levels'][0] ?></td>
                <td><?= $item['data']['levels'][1] ?></td>
                <td><?= $item['data']['levels'][2] ?></td>
                <td><?= $item['data']['levels'][3] ?></td>
                <td><?= $item['data']['levels'][4] ?></td>
                <td><?= $item['data']['levels'][5] ?></td>
                <td><?= $item['data']['levels'][6] ?></td>
                <td><?= $item['data']['levels'][7] ?></td>
                <td><?= $item['data']['levels'][8] ?></td>

                <td><?= array_sum($item['data']['levels']) ?></td>
                <td><?= $item['data']['certs'] ?></td>

                 <td><?= $item['data']['note_levels'][0] ?></td>
                <td><?= $item['data']['note_levels'][1] ?></td>
                <td><?= $item['data']['note_levels'][2] ?></td>
                <td><?= $item['data']['note_levels'][3] ?></td>
                <td><?= $item['data']['note_levels'][4] ?></td>
                <td><?= $item['data']['note_levels'][5] ?></td>
                <td><?= $item['data']['note_levels'][6] ?></td>
                <td><?= $item['data']['note_levels'][7] ?></td>
                <td><?= $item['data']['note_levels'][8] ?></td>

                <td><?= array_sum($item['data']['note_levels']) ?></td>
                <td><?= $item['data']['notes'] ?></td>

            </tr>
        <? endforeach ?>
        </tbody>
    </table>

<? endif; ?>