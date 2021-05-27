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
        </tr>
        <?
        $result='';
        foreach ($list as $test_level)
        {
            $result.='<tr><td>'.$test_level['caption'].'</td><td><input type="text" value="'.$test_level['price'].'" name="price['.$test_level['id'].']"></td><td><input type="text" value="'.$test_level['sub_test_price'].'" name="sub_test_price['.$test_level['id'].']"></td></tr>';

        }
        echo $result;
        ?>

        <!--<tr><td>Цена тестирования</td><td><input type="text" value="<?=$list[0]['price']?>" name="price"></td></tr>
        <tr><td>Цена пересдачи одного субтеста</td><td><input type="text" value="<?=$list[0]['sub_test_price']?>" name="sub_test_price"></td></tr>-->
        </table>
    </form>
