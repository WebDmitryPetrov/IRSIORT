<?php
/**
 * Created by PhpStorm.
 * User: LWR
 * Date: 29.09.2017
 * Time: 11:52
 */
?>

<table class="table-bordered  table-striped">
    <thead>
    <tr>
        <th rowspan="2">Уровень тестирования</th>
        <th colspan="2">Тестирование</th>

        <?
        if ($object->test_level_type_id==2)
        {
            $colspan = 4;
        }
        else
        {
            $colspan = 3;
        }
        ?>

        <th colspan="<?=$colspan;?>">Пересдача</th>
        <? if (!empty($template_buttons)): ?><th rowspan="2">&nbsp;</th><? endif;?>


    </tr>
    <tr>
        <th>Человек</th>
        <th>Стоимость тестирования</th>
        <th>Человек</th>



        <th>По одному субтесту</th>
        <?if ($object->test_level_type_id==2):?>
            <th>По двум субтестам</th>
        <?endif;?>



        <th>Стоимость пересдач тестирования</th>

    </tr>
    </thead>
    <tbody>
    <?php foreach ($object->getTests() as $test):
        /** @var ActTest $test */
        //$prices=ChangedPriceTestLevel::checkPrice($object->id);
        ?>
        <tr>

            <td><?php  echo $test->getLevel()?>
            </td>
            <td><?php  echo $test->people_first;?>
            </td>
            <td><?php  echo $test->getMoneyFirst(); ?>
            </td>
            <td><?php  echo $test->people_retry;?>
            </td>
            <td><?php  echo $test->people_subtest_retry;?></td>
            <?if ($object->test_level_type_id==2):?>
                <td><?php  echo $test->people_subtest_2_retry;?></td>
            <?endif;?>



            <td><?php  echo  /*$test->getLevel()->sub_test_price*$test->people_subtest_retry;*/
                    $test->getMoneyRetry_1() + $test->getMoneyRetry_2(); ?>
            </td>
            <? if (!empty($template_buttons)): ?>
            <td>
            <?=$this->import($template_buttons, array('test' => $test)); ?>
            </td>
            <? endif?>

        </tr>
    <?php endforeach;?>
    </tbody>
</table>
