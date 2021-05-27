<h1>Тестовая сессия</h1>
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
    <!--<tr>
        <th>Общая стоимость</th>
        <td><?php echo $object->total_revenue ?>
        </td>
    </tr>-->

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
        <th>Тестирования</th>
        <td><a class="btn btn-primary"
               href="index.php?action=act_test_add&id=<?php echo $object->id; ?>">Добавить тестирование</a>
            <table class="table-bordered  table-striped">
                <thead>
                <tr>
                    <th rowspan="2">Уровень тестирования</th>
                    <th colspan="2">Тестирование</th>
                    <th colspan="3">Пересдача</th>
                    <th rowspan="2">&nbsp;</th>
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
                    $prices=ChangedPriceTestLevel::checkPrice($object->id);
                    ?>
                <tr>

                    <td><?php  echo $test->getLevel()?>
                    </td>
                    <td><?php  echo $test->people_first;?>
                    </td>
                    <td><?php  /*echo $test->getLevel()->price*$test->people_first;*/echo $prices->price*$test->people_first; ?>
                    </td>
                    <td><?php  echo $test->people_retry;?>
                    </td>
                    <td><?php  echo $test->people_subtest_retry;?>
                    </td>
                    <td><?php  echo  /*$test->getLevel()->sub_test_price*$test->people_subtest_retry;*/$prices->sub_test_price*$test->people_subtest_retry; ?>
                    </td>
                    <td><a class="btn btn-small btn-warning" onclick="return confirm('Вы уверены?');"
                           href="index.php?action=act_test_delete&id=<?php echo $test->id; ?>">Удалить</a>
                    </td>
                    <!--  <td><a class="btn btn-warning btn-small"
						href="index.php?action=act_test_edit&id=<?php echo $test->id; ?>">Редактировать</a>
					</td>-->
                </tr>
                    <?php endforeach;?>
                </tbody>
            </table>

            <?php if($object->getTests() and count($object->getTests())): ?>
            <a class="btn btn-info btn-small"
               href="index.php?action=act_table&id=<?php echo $object->id; ?>">Заполнить сводную таблицу</a>
                    <? endif; ?>
        </td>

    </tr>


</table>
