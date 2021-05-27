<?php
/**
 * Created by JetBrains PhpStorm.
 * User: LWR
 * Date: 01.07.14
 * Time: 11:30
 * To change this template use File | Settings | File Templates.
 */
//var_dump($list);

?>
<form method="post">
    <table class="table table-bordered  table-striped">
        <tr>
            <td>Уровень тестирования</td>
            <td>Цена тестирования</td>
            <td>Цена пересдачи одного субтеста</td>
            <td>Цена пересдачи двух субтестов</td>
        </tr>
        <?
        $result = '';
        foreach ($list as $test_level) {

          //  var_dump($test_level);
//            var_dump()
            if ($test_level['id'] != 16 && (!empty($sp_id) && $sp_id == 2)) continue;
            if (empty($test_level['is_publicated'])) continue;

            if ($test_level['type_id'] == 2 || $test_level['type_id'] == 3)
                $sub_test_price_2_echo = '<input type="text" value="' . $test_level['sub_test_price_2'] . '" name="sub_test_price_2[' . $test_level['id'] . ']">';
            else
                $sub_test_price_2_echo = '<input type="hidden" value="' . $test_level['sub_test_price_2'] . '" name="sub_test_price_2[' . $test_level['id'] . ']">';

            $trclass='';
            if($test_level['test_group']==2) $trclass.=' warning ';
            $result .= '<tr class="'.$trclass.'"><td>' . $test_level['caption'] . '</td><td><input type="text" value="' . $test_level['price'] . '" name="price[' . $test_level['id'] . ']"></td><td><input type="text" value="' . $test_level['sub_test_price'] . '" name="sub_test_price[' . $test_level['id'] . ']"></td><td>' . $sub_test_price_2_echo . '</td></tr>';

        }
        echo $result;
        ?>


    </table>

    <? if (!empty($submit_button)): ?>
        <button class="btn btn-success" type="submit">Сохранить</button>
    <? endif; ?>

</form>
