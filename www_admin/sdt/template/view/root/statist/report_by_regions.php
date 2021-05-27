<?
$reportName = !empty($caption) ? $caption : 'Без названия';
?>
    <style>
        tr.type-head {
            font-weight: bold;
        }
    </style>
    <h1><?= $reportName ?></h1>
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
        </label> <label>Регион :
            <div>

                <select name="region">
                    <?
                    foreach ($regions_list as $key => $value) {
                        if (!$key) {
                            echo '<option value=' . $key . '>По всем регионам</option>';
                        } else {
                            if ($key == $_POST['region']) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }

                            echo '<option value=' . $key . ' ' . $selected . '>' . $value . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
        </label> <label>Головной центр тестирования :
            <div>

                <select name="hc">
                    <option value=0>По всем головным центрам</option>
                    <?
                    if ('pfur' == filter_input(INPUT_POST, 'hc')) {
                        $selected = 'selected="selected"';
                    } else {
                        $selected = '';
                    }
                    ?>
                    <option value="pfur" <?=$selected?>>Объединённый РУДН</option>
                    <?
                    foreach ($hc_list as $item) {
                        if ($item->id == 6) {
                            continue;
                        }
                        if ($item->id == filter_input(INPUT_POST, 'hc')) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }

                        $name = $item->short_name ? $item->short_name : $item->name;

                        echo '<option value=' . $item->id . ' ' . $selected . '>' . $name . '</option>';
                    }
                    ?>
                </select>
            </div>
        </label>

        <input type="submit" value="Отфильтровать">
    </form>
<? if (!empty($search)): ?>
    <h1><?= $reportName ?> с <?= $from ?> по <?= $to ?> </h1>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th><p>Регион</p></th>
            <th><p>Количество организаций-партнеров, с которым заключены соглашения</p></th>

            <th colspan="4"><p>Количество мигрантов, сдавших экзамен</p></th>
            <th><p>Количество выданных сертификатов</p></th>

            <th colspan="4"><p>Количество мигрантов, несдавших экзамен</p></th>
            <th><p>Количество выданных справок</p></th>

        </tr>
        <tr>
            <th><p>&nbsp;</p></th>
            <th><p>&nbsp;</p></th>

            <th><p>разрешение на работу или патент (РНР)</p></th>
            <th><p>разрешение на временное проживание (РВЖ)</p></th>
            <th><p>вид на жительство (ВНЖ)</p></th>
            <th><p>общее количество </p></th>
            <th><p>&nbsp;</p></th>

            <th><p>разрешение на работу или патент (РНР)</p></th>
            <th><p>разрешение на временное проживание (РВЖ)</p></th>
            <th><p>вид на жительство (ВНЖ)</p></th>
            <th><p>общее количество </p></th>
            <th><p>&nbsp;</p></th>

        </tr>
        </thead>
        <tbody>
        <? //var_dump($array);?>
        <? foreach ($array as $item):
            if (!array_sum($item['data']['levels']) && !array_sum($item['data']['note_levels'])) continue; ?>

            <tr>
                <td><?= $item['caption'] ?></td>
                <td><?= $item['data']['orgs_distinct'] ?></td>

                <td><?= $item['data']['levels'][0] ?></td>
                <td><?= $item['data']['levels'][1] ?></td>
                <td><?= $item['data']['levels'][2] ?></td>
                <td><?= array_sum($item['data']['levels']) ?></td>
                <td><?= $item['data']['certs'] ?></td>

                <td><?= $item['data']['note_levels'][0] ?></td>
                <td><?= $item['data']['note_levels'][1] ?></td>
                <td><?= $item['data']['note_levels'][2] ?></td>
                <td><?= array_sum($item['data']['note_levels']) ?></td>
                <td><?= $item['data']['notes'] ?></td>
            </tr>
        <? endforeach ?>
        </tbody>
    </table>

<? endif; ?>