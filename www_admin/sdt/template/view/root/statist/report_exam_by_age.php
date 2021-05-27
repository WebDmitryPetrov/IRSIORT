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

        <label>от:
            <div class="input-prepend date datepicker "
                 data-date="">
                <span class="add-on"><i class="icon-th"></i> </span> <input
                        class="input-small"
                        name="from"

                        readonly="readonly" size="16" type="text"
                        value="<?= $from ?>">
            </div>
        </label>
        <label>До:
            <div class="input-prepend date datepicker "
                 data-date="">
                <span class="add-on"><i class="icon-th"></i> </span> <input
                        class="input-small"
                        name="to"

                        readonly="readonly" size="16" type="text"
                        value="<?= $to ?>">
            </div>
        </label>
        <label>Организация:
            <div>

                <select name="hc" id="hc-list">
                    <option value=0>По всем организациям</option>
                    <?
                    foreach ($hc_list as $item) {
                        /*  if ($item->id != 1 && $item->id != 2 && $item->id != 7 && $item->id != 8) {
                              continue;
                          }*/
                        if ($item['id'] == $selected_hc) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }

                        $name = $item['caption'];

                        echo '<option value=' . $item['id'] . ' ' . $selected . '>' . $name . '</option>';
                    }
                    ?>
                </select>
            </div>
        </label><label>Возраста:
            <div>

                <textarea type="text" name="ageString"  required="required"  style="width:350px"><?= htmlspecialchars($ageString) ?></textarea>
            </div>
        </label>
        <input type="submit" value="Отфильтровать">
    </form>
<? if (!empty($search)): ?>
    <h1><?= $reportName ?> с <?= $from ?> по <?= $to ?> </h1>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th rowspan="2"><p>Возраст сдающих экзамен </p></th>
            <th rowspan="2"><p>Общее количество мигрантов, проходивших экзамен (включая не сдавших его успешно)
                </p></th>

            <th colspan="4"><p>Количество мигрантов, сдавших экзамен</p></th>

            <th colspan="4"><p>Количество мигрантов, несдавших экзамен</p></th>


        </tr>
        <tr>


            <th><p>разрешение на работу или патент (РНР)</p></th>
            <th><p>разрешение на временное проживание (РВЖ)</p></th>
            <th><p>вид на жительство (ВНЖ)</p></th>
            <th><p>общее количество </p></th>

            <th><p>разрешение на работу или патент (РНР)</p></th>
            <th><p>разрешение на временное проживание (РВЖ)</p></th>
            <th><p>вид на жительство (ВНЖ)</p></th>
            <th><p>общее количество </p></th>


        </tr>
        </thead>
        <tbody>
        <? foreach ($ages[0] as $k => $age): ?>
            <tr>
                <td><?= $age[0] ?> - <?= $age[1] ?></td>
                <td><?= array_sum($array[ReportExamByYear::PREFIX . $k]['levels']) + array_sum($array[ReportExamByYear::PREFIX . $k]['note_levels']) ?></td>
                <td><?= $array[ReportExamByYear::PREFIX . $k]['levels'][0] ?></td>
                <td><?= $array[ReportExamByYear::PREFIX . $k]['levels'][1] ?></td>
                <td><?= $array[ReportExamByYear::PREFIX . $k]['levels'][2] ?></td>
                <td><?= array_sum($array[ReportExamByYear::PREFIX . $k]['levels']) ?></td>
                <td><?= $array[ReportExamByYear::PREFIX . $k]['note_levels'][0] ?></td>
                <td><?= $array[ReportExamByYear::PREFIX . $k]['note_levels'][1] ?></td>
                <td><?= $array[ReportExamByYear::PREFIX . $k]['note_levels'][2] ?></td>
                <td><?= array_sum($array[ReportExamByYear::PREFIX . $k]['note_levels']) ?></td>
            </tr>
        <? endforeach ?>

        <tr>
            <td>>= <?= $ages[2] ?></td>
            <td><?= array_sum($array[ReportExamByYear::GREATER]['levels']) + array_sum($array[ReportExamByYear::GREATER]['note_levels']) ?></td>
            <td><?= $array[ReportExamByYear::GREATER]['levels'][0] ?></td>
            <td><?= $array[ReportExamByYear::GREATER]['levels'][1] ?></td>
            <td><?= $array[ReportExamByYear::GREATER]['levels'][2] ?></td>
            <td><?= array_sum($array[ReportExamByYear::GREATER]['levels']) ?></td>
            <td><?= $array[ReportExamByYear::GREATER]['note_levels'][0] ?></td>
            <td><?= $array[ReportExamByYear::GREATER]['note_levels'][1] ?></td>
            <td><?= $array[ReportExamByYear::GREATER]['note_levels'][2] ?></td>
            <td><?= array_sum($array[ReportExamByYear::GREATER]['note_levels']) ?></td>
        </tr>
        <tr>
            <td>Некорректные</td>
            <td><?= array_sum($array[ReportExamByYear::INVALID]['levels']) + array_sum($array[ReportExamByYear::INVALID]['note_levels']) ?></td>
            <td><?= $array[ReportExamByYear::INVALID]['levels'][0] ?></td>
            <td><?= $array[ReportExamByYear::INVALID]['levels'][1] ?></td>
            <td><?= $array[ReportExamByYear::INVALID]['levels'][2] ?></td>
            <td><?= array_sum($array[ReportExamByYear::INVALID]['levels']) ?></td>
            <td><?= $array[ReportExamByYear::INVALID]['note_levels'][0] ?></td>
            <td><?= $array[ReportExamByYear::INVALID]['note_levels'][1] ?></td>
            <td><?= $array[ReportExamByYear::INVALID]['note_levels'][2] ?></td>
            <td><?= array_sum($array[ReportExamByYear::INVALID]['note_levels']) ?></td>
        </tr>
        </tbody>
    </table>

<? endif; ?>