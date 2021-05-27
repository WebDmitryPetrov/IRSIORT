<a class="btn btn-warning btn-small"
   href="index.php?action=act_numbers&id=<?php echo $object->id; ?>">Номера
    документов</a> <a class="btn btn-info btn-small" target="_blank"
                      href="index.php?action=act_vidacha_cert&id=<?php echo $object->id; ?>">Ведомость
    выдачи сертификатов</a>

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
        <th>Дата тестирования</th>
        <td><?php echo $C->date($object->testing_date) ?>
        </td>
    </tr>
    <tr>
        <th>Общая стоимость</th>
        <td><?php echo $object->total_revenue ?>
        </td>
    </tr>
    <!--<tr>
        <th>Процент отчислений в центр <?=TEXT_HEADCENTER_SHORT_IP?></th>
        <td><?php echo $object->rate_of_contributions ?>
        </td>
    </tr>-->
    <tr>
        <th>Прибыль</th>
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
        <td><?php  $fileact = $object->getFileAct();
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
        <th>Количество протестированных<br>(сертификатов/справок)</th>
        <td>
            <?php
            $people = $object->getPeople();
            echo count($people);
            $count_cert = 0;
            $count_sprav = 0;
            foreach ($people as $man) {
                if($man->document=='certificate') {
                      $count_cert++;
                }
                else{
                    $count_sprav++;
                }


            }

            echo ' ('.$count_cert.'/'.$count_sprav.')';
            ?>

        </td>
    </tr>
    <tr>
        <th>Тестирования</th>
        <td>
            <table class="table table-bordered  table-striped">
                <tr>
                    <th>Уровень тестирования</th>
                    <th>Количество людей</th>
                    <th>Стоимость</th>
                    <th>&nbsp;</th>
                </tr>
                <?php foreach ($object->getTests() as $test): ?>
                <tr>
                    <td><?php  echo $test->getLevel()?>
                    </td>
                    <td><?php  echo $test->people_count;?>
                    </td>
                    <td><?php  echo $test->money; ?>
                    </td>
                    <td><a class="btn btn-info btn-small"
                           href="index.php?action=act_vedomost&id=<?php echo $test->id; ?>">Ведомость</a>
                    </td>
                    <!--  <td><a class="btn btn-warning btn-small"
						href="index.php?action=act_test_edit&id=<?php echo $test->id; ?>">Редактировать</a>
					</td>-->
                </tr>
                <?php endforeach;?>

            </table>
        </td>
    </tr>


</table>
