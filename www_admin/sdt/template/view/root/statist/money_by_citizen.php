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
    <!--        <label>Организация :
            <div>

                <select name="head_id" id="hc-list" style="width:400px">

                    <? /*

                    foreach ($hc_list as $item) {

                        if ($item['id'] == $head_id) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }

                        $name = $item['caption'];

                        echo '<option value=' . $item['id']. ' ' . $selected . '>' . $name . '</option>';
                    }
                    */ ?>
                </select>
            </div>
        </label>-->
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
    <label>Гражданство
        <select name="citizen" style="width: 400px">
            <?php if (!filter_input(INPUT_POST, 'citizen')) {
                $selected = 'selected="selected"';
            } else {
                $selected = '';
            } ?>
            <option value=0
                <?= $selected ?>>Все
            </option>
            <?
            foreach (Countries::getAll() as $item) {

                if ($item->id == filter_input(INPUT_POST, 'citizen')) {
                    $selected = 'selected="selected"';
                } else {
                    $selected = '';
                }


                echo '<option value=' . $item->id . ' ' . $selected . '>' . $item->name . '</option>';
            }
            ?>
        </select>
    </label>

    <? //<input type="submit" value="Отфильтровать" onclick="return confirm('Запрос сложный! Продолжить?');">?>
    <input type="submit" value="Отфильтровать">
</form>
<? if ($search):
    $horgs = [];
    $years = [];
    foreach ($array as $horg) {
//var_dump($horg['years']);
        $years = array_unique(array_merge($years, array_keys($horg['years'])));
    }

//    die(var_dump($years));
//    foreach ($array as $years) {
//        foreach ($years  as $id => $row) {
////            var_dump($year);
////            foreach ($year) {
//                 if(empty($horgs[$id]))
//                     $horgs[$id] = $row['caption'];
////            }
//        }
//    };
    $horgs = array_column($array, 'caption', 'id');
//    die(var_dump($horgs));
    ?>
    <h1>с <?= $from ?> по <?= $to ?> </h1>


    <h2>Количество протестированных</h2>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>ГЦ</th>
            <? foreach ($years as $year): ?>
                <th><?= $year ?></th><? endforeach; ?>
            <th>Всего</th>
        </tr>
        </thead>
        <tbody>
        <? foreach ($array as $horg): ?>
            <tr>
                <th><?= $horg['caption'] ?></th>
                <?
                $buffer = 0;
                foreach ($years as $year):
                    $current = isset($horg['years'][$year]['people']) ? $horg['years'][$year]['people'] : 0;
                    $buffer += $current;
                    ?>
                    <td><?= $current ?></td>

                <? endforeach; ?>
                <td><?= $buffer ?></td>
            </tr>
        <? endforeach; ?>
        </tbody>
    </table>

    <h2>Суммарную стоимость тестирования </h2>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>ГЦ</th>
            <? foreach ($years as $year): ?>
                <th><?= $year ?></th><? endforeach; ?>
            <th>Всего</th>
        </tr>
        </thead>
        <tbody>
        <? foreach ($array as $horg): ?>
            <tr>
                <th><?= $horg['caption'] ?></th>
                <?
                $buffer = 0;
                foreach ($years as $year):
                    $current = isset($horg['years'][$year]['money']) ? $horg['years'][$year]['money'] : 0;
                    $buffer += $current;
                    ?>
                    <td><?= $current ?></td>

                <? endforeach; ?>
                <td><?= $buffer ?></td>
            </tr>
        <? endforeach; ?>
        </tbody>
    </table>
<? endif; ?>
