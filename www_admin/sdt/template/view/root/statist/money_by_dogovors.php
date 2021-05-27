<?
$c = 3;
if ($level_type == 1) {
    $c = 1;
}
?>

    <h1><?= $caption ?></h1>
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
        <label>Организация :
            <div>

                <select name="head_id" id="hc-list" style="width:400px">

                    <?
                    foreach ($hc_list as $item) {
                        /*  if ($item->id != 1 && $item->id != 2 && $item->id != 7 && $item->id != 8) {
                              continue;
                          }*/
                        if ($item->id == $head_id) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }

                        $name = $item->name;

                        echo '<option value=' . $item->id . ' ' . $selected . '>' . $name . '</option>';
                    }
                    ?>
                </select>
            </div>
        </label>
        <label>Тип тестирования
            <select name="level_type" style="width: 400px">
                <?
                foreach (TestLevelTypes::getAll() as $item) {

                    if ($item->id == $level_type) {
                        $selected = 'selected="selected"';
                    } else {
                        $selected = '';
                    }


                    echo '<option value=' . $item->id . ' ' . $selected . '>' . $item->caption . '</option>';
                }
                ?>
            </select>
        </label>

        <? //<input type="submit" value="Отфильтровать" onclick="return confirm('Запрос сложный! Продолжить?');">?>
        <input type="submit" value="Отфильтровать">
    </form>
<? if ($search): ?>
    <h1>с <?= $from ?> по <?= $to ?> </h1>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th rowspan="2">Название организации</th>
            <th rowspan="2">№ договоры</th>
            <? if ($level_type == 1): ?>
                <th colspan="2">ЛТ</th>
            <? endif ?>
            <? if ($level_type == 2): ?>
                <th colspan="2">ИР</th>
                <th colspan="2">РВ</th>
                <th colspan="2">ВЖ</th>
            <? endif ?>


        </tr>
        <tr>

            <? for ($i = 0; $i < $c; $i++): ?>
                <th>Кол-во, чел.</th>
                <th>Сумма, руб.</th>
            <? endfor; ?>
        </tr>
        </thead>
        <tbody>
        <? foreach ($array as $org): ?>
            <? foreach ($org['dogovors'] as $dogovor): ?>
                <tr>
                    <td><?= $org['caption'] ?></td>
                    <td><?= $dogovor['caption'] ?></td>
                    <? for ($i = 0; $i < $c; $i++):
//                        var_dump($c);
                        ?>
                        <td><?= $dogovor['numbers'][$i]['people'] ?></td>
                        <td><?= $dogovor['numbers'][$i]['money'] ?></td>
                    <? endfor; ?>

                </tr>
            <? endforeach; ?>
        <? endforeach; ?>

        </tbody>
    </table>


<? endif; ?>


<?
function month($id, $type = 0)
{
    $id = (int)$id;
    $month = array(
        '1' => array('0' => 'январь', '1' => 'января'),
        '2' => array('0' => 'февраль', '1' => 'февраля'),
        '3' => array('0' => 'март', '1' => 'марта'),
        '4' => array('0' => 'апрель', '1' => 'апреля'),
        '5' => array('0' => 'май', '1' => 'мая'),
        '6' => array('0' => 'июнь', '1' => 'июня'),
        '7' => array('0' => 'июль', '1' => 'июля'),
        '8' => array('0' => 'август', '1' => 'августа'),
        '9' => array('0' => 'сентябрь', '1' => 'сентября'),
        '10' => array('0' => 'октябрь', '1' => 'октября'),
        '11' => array('0' => 'ноябрь', '1' => 'ноября'),
        '12' => array('0' => 'декабрь', '1' => 'декабря'),
    );
    if (empty($id)) return $id;
    return $month[$id][$type];
}