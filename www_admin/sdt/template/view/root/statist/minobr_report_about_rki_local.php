<?
?>
    <style>
        tr.type-head {
            font-weight: bold;
        }
    </style>
    <h1><?=$caption?> </h1>
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
        <br>
        <label>Регион <select name="region">
                <option>Не указан</option>
                <? foreach ($regions as $r): /** @var Region $r */ ?>
                    <option value="<?= $r->id ?>"
                            <? if ($r->id == $region): ?>selected="selected"<? endif ?>><?= $r->caption ?></option>
                <? endforeach; ?>
            </select></label>
        <input type="submit" value="Отфильтровать">
    </form>
<? if (!empty($search)): ?>
    <h1>Информация о прохождении РКИ с <?= $from ?> по  <?= $to ?> </h1>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th rowspan="2"><p>Образовательная организация</p></th>
            <th rowspan="2"><p>Адрес</p></th>
            <th rowspan="2"><p>Договор</p></th>
            <th colspan="10"><p>Количество мигрантов, сдавших экзамен</p></th>
            <th colspan="10"><p>Количество мигрантов, получивших справки</p></th>
            <th rowspan="2"><p>Всего</p></th>

        </tr>
        <tr>

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


        </tr>
        </thead>
        <tbody>
        <? foreach ($array as $item): ?>
            <tr>

                <th><?= $item['caption'] ?></th>
                <?
                for($i=0;$i<23;$i++){
                    echo '<td>&nbsp;</td>';
                }

                /*  <td  ><?=$item['data']['orgs']?></td>
                <td  >&nbsp;</td>
                <td ><?=$item['data']['levels'][0] ?></td>
                <td  ><?=$item['data']['levels'][1] ?></td>
                <td  ><?=$item['data']['levels'][2] ?></td>
                <td  ><?=array_sum($item['data']['levels'])?></td>
                <td  ><?=$item['data']['certs']?></td>
                <td  >&nbsp</td>

 */ ?>
            </tr>
            <? foreach ($item['centers'] as $center): ?>
                <tr>
                    <td><?= $center['caption'] ?></td>
                    <td><?= !empty($center['address'])?$center['address']:'&nbsp;' ?></td>
                    <td><?= $center['dogovor'] ?></td>

                    <td><?= $center['certificate'][0] ?></td>
                    <td><?= $center['certificate'][1] ?></td>
                    <td><?= $center['certificate'][2] ?></td>
                    <td><?= $center['certificate'][3] ?></td>
                    <td><?= $center['certificate'][4] ?></td>
                    <td><?= $center['certificate'][5] ?></td>
                    <td><?= $center['certificate'][6] ?></td>
                    <td><?= $center['certificate'][7] ?></td>
                    <td><?= $center['certificate'][8] ?></td>
                    <td><?= array_sum($center['certificate']) ?></td>

                    <td><?= $center['note'][0] ?></td>
                    <td><?= $center['note'][1] ?></td>
                    <td><?= $center['note'][2] ?></td>
                    <td><?= $center['note'][3] ?></td>
                    <td><?= $center['note'][4] ?></td>
                    <td><?= $center['note'][5] ?></td>
                    <td><?= $center['note'][6] ?></td>
                    <td><?= $center['note'][7] ?></td>
                    <td><?= $center['note'][8] ?></td>
                    <td><?= array_sum($center['note']) ?></td>

                    <td><?= (array_sum($center['note']) + array_sum($center['certificate'])) ?></td>

                    <!--                    <td>--><? //= $center['certs'] ?><!--</td>-->

                </tr>
            <? endforeach ?>
        <? endforeach ?>
        </tbody>
    </table>

<? endif; ?>