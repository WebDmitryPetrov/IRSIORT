<?php
/** @var Act $object */
$show_free = Reexam_config::isShowInAct($object->test_level_type_id);

?>
<?/*if ($object->summary_table_id):?>
    <a class="btn btn-danger  btn-color-black" target="_blank"
       href="index.php?action=act_summary_table&id=<?php echo $object->id; ?>">Просмотреть/напечатать Сводный протокол</a>
<?endif*/?>



<? // какие условия ставить ?>
<? if (!empty($object->summary_table_id)):?>
    <a class="btn btn-danger  btn-color-black" target="_blank" id="summary_table_<?=$object->id?>"
       href="index.php?action=act_summary_table&id=<?php echo $object->id; ?>">Просмотреть/напечатать Сводный протокол</a>
    <div></div>
<? endif;?>
<? if (!empty($object->isActPrinted())):?>
    <a class="btn btn-danger  btn-color-black" target="_blank" id="act_print_<?=$object->id?>"
       href="index.php?action=act_print_view&id=<?php echo $object->id; ?>">Просмотреть/напечатать Акт</a>
    <div></div>
<? endif;?>
<? if (!empty($object->isActTablePrinted())):?>
    <a class="btn btn-danger  btn-color-black" target="_blank" id="act_table_print_<?=$object->id?>"
       href="index.php?action=act_table_print_view&id=<?php echo $object->id; ?>">Просмотреть/напечатать Сводную таблицу</a>
    <div></div>
<? endif;?>

<? // конец блока условий ?>



<table class="table table-bordered  table-striped">
    <tr>
        <th>Дата акта</th>
        <td><?php echo $C->date($object->actDate(), true) ?></td>
    </tr>
    <tr>
        <th>Номер</th>
        <td><?php echo $object->number ?>
        </td>
    </tr>

    <tr>
        <th>Локальный центр</th>
        <td><?php echo $object->getUniversity()->getLegalInfo()['name_parent'] ?>
        </td>
    </tr>
    <tr>
        <th>Договор с локальным  центром</th>
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
        <th>Оплата за оказанные услуги <?= TEXT_HEADCENTER_SHORT_IP ?></th>
        <td><?php echo $object->amount_contributions ?>
        </td>
    </tr>
    <tr>
        <th>Комментарий</th>
        <td class="text-success"><?php echo $object->comment ?>
        </td>
    </tr>
    <tr>
        <th>Оплачено</th>
        <td><?php echo $object->paid ? 'Да' : 'Нет' ?>
        </td>
    </tr>

    <tr>
        <th>Загруженные файлы</th>
        <td><?php $fileact = $object->getFileAct();
            if ($fileact): ?>
                <div>
                    <a href="<?php echo $fileact->getDownloadURL(); ?>">Отсканированный
                        акт</a>
                </div> <?php endif; ?> <?php $fileact = $object->getFileActTabl();
            if ($fileact): ?>
                <div>
                    <a href="<?php echo $fileact->getDownloadURL(); ?>">Отсканированная
                        сводная таблица</a>
                </div> <?php endif; ?>
        </td>
    </tr>

    <tr>
        <th>Тестирования</th>
        <td>
            <?
            $template_buttons = '';
            echo $this->import('acts/act_table_template', array('object' => $object, 'show_free' => $show_free, 'template_buttons' => $template_buttons));
            ?>

            <a class="btn btn-info btn-small"
               href="index.php?action=buh_view_table&id=<?php echo $object->id; ?>">Просмотреть сводную
                таблицу</a></td>
    </tr>


</table>


