<table class="table table-bordered  table-striped">
    <tr>
        <th>Номер</th>
        <td><?php echo $object->number ?>
        </td>
    </tr>

    <tr>
        <th>ВУЗ</th>
        <td><?php echo $object->getUniversity()->name ?>
        </td>
    </tr>
    <tr>
        <th>Договор с ВУЗом</th>
        <td><?php echo $object->getUniversityDogovor() ?>
        </td>
    </tr>
    <tr>
        <th>Утверждающий акт</th>
        <td><?php echo $object->official ?>
        </td>
    </tr>
    <tr>
        <th>Ответственный за проведение тестрования</th>
        <td><?php echo $object->responsible ?>
        </td>
    </tr>
    <tr>
        <th>Дата тестирования</th>
        <td><?php echo $C->date($object->testing_date) ?>
        </td>
    </tr>
    <!--
    <tr>
        <th>Общая стоимость</th>
        <td><?php echo $object->total_revenue ?>
        </td>
    </tr>
    -->


    <tr>
        <th>Оплата за оказанные услуги <?=TEXT_HEADCENTER_SHORT_IP?></th>
        <td><?php echo $object->amount_contributions ?>
        </td>
    </tr>
    <tr>
        <th>Комментарий</th>
        <td><?php echo $object->comment ?>
        </td>
    </tr>
    <tr>
        <th>Оплачено</th>
        <td><?php echo $object->paid ? 'Да' : 'Нет' ?>
        </td>
    </tr>

    <tr>
        <th>Загруженные файлы</th>
        <td>






            <? if (!empty($object->summary_table_id)):?>
                <a class="btn btn-danger  btn-color-black" target="_blank" id="summary_table_<?=$object->id?>"
                   href="index.php?action=act_summary_table&id=<?php echo $object->id; ?>">Просмотреть/напечатать Сводный протокол</a>
                <div></div>
            <? endif;?>
            <? if (!empty($object->isActPrinted()) && !$object->file_act_id):?>
                <a class="btn btn-danger  btn-color-black" target="_blank" id="act_print_<?=$object->id?>"
                   href="index.php?action=act_print_view&id=<?php echo $object->id; ?>">Просмотреть/напечатать Акт</a>
                <div></div>
            <? endif;?>
            <? if (!empty($object->isActTablePrinted()) && !$object->file_act_tabl_id):?>
            <a class="btn btn-danger  btn-color-black" target="_blank" id="act_table_print_<?=$object->id?>"
               href="index.php?action=act_table_print_view&id=<?php echo $object->id; ?>">Просмотреть/напечатать Сводную таблицу</a>
            <div></div>
            <? endif;?>









            <?php  $fileact = $object->getFileAct();
            if ($fileact): ?>
                <div>
                    <a href="<?php echo $fileact->getDownloadURL(); ?>">Отсканированный
                        акт</a>
                </div> <?php endif;?> <?php  $fileact = $object->getFileActTabl();
            if ($fileact): ?>
                <div>
                    <a href="<?php echo $fileact->getDownloadURL(); ?>">Отсканированная
                        сводная таблица</a>
                </div> <?php endif;?>
        </td>
    </tr>

    <tr>
        <th>Тестирования</th>
        <td>
            <table class="table-bordered  table-striped">
                <thead>
                <tr>
                    <th rowspan="2">Уровень тестирования</th>
                    <th colspan="2">Тестирование</th>
                    <th colspan="3">Пересдача</th>

                </tr>
                <tr>
                    <th>Человек</th>
                    <th>Стоимость тестирования</th>
                    <th>Человек</th>
                    <th>Субтестов</th>
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
                    <td><?php  /*echo $test->getLevel()->price*$test->people_first;*/echo $test->getPrice()*$test->people_first; ?>
                    </td>
                    <td><?php  echo $test->people_retry;?>
                    </td>
                    <td><?php  echo $test->people_subtest_retry;?>
                    </td>
                    <td><?php  echo  /*$test->getLevel()->sub_test_price*$test->people_subtest_retry;*/$test->getPriceSubTest()*$test->people_subtest_retry; ?>
                    </td>

                </tr>
                    <?php endforeach;?>
                </tbody>
            </table>

            <a class="btn btn-info btn-small"
               href="index.php?action=act_received_table_view&id=<?php echo $object->id; ?>">Просмотреть сводную таблицу</a></td>
    </tr>


</table>


