<h1>Просмотр и распечатка документов тестовых сессий</h1>
<h3>Всего <?= count($list) ?></h3>
<?php
/** @var Act[] $list */
?>
<table class="table table-bordered  table-striped">
    <thead>
    <tr>
        <th valign="top">
            <nobr>№</nobr>
        </th>
        <th valign="top">Дата акта</th>

        <th valign="top">Дата тестирования</th>
        <th valign="top">Исполнитель</th>
        <th valign="top">Комментарий</th>
        <th valign="top">&nbsp;</th>
    </tr>
    </thead>
    <tbody>


    <?php

    foreach ($list as $item): ?>
        <tr>
            <td><?= $item->number ?></td>
            <td><?= $C->date($item->actDate()) ?></td>
            <td><?php echo $C->date($item->testing_date) ?></td>

            <td class="wrap"><?php echo $item->responsible; ?></td>

            <td class="wrap text-success"><?php echo $item->comment ?>
            </td>
            <td><!--<a class="btn btn-info btn-mini  btn-block"
                   href="index.php?action=act_third_view&id=<?php /*echo $item->id; */?>">Карточка</a>
                <div></div>-->



                <?php
                $svotdnay = !!$item->file_act_tabl_id;


                $file_act = !!$item->file_act_id;

                $summary_table = !!$item->summary_table_id;

                $errors = [];

                $confirm_text = 'Вы уверены?';

                if ($item->allow_summary_table()) {

                    if ($summary_table) $confirm_text = 'Вы уверены? Сводный протокол изменить будет нельзя!'; //тут будет дописано про невозможность изменить сводный протокол

                    if (!$file_act && !$summary_table)
                        $errors[] = 'загрузить скан акта либо сформировать сводный протокол';
                }
                else
                {
                    if (!$file_act)
                        $errors[] = 'загрузить скан акта';
                    $summary_table = false;
                }

                if (!$svotdnay)
                    $errors[] = 'загрузить скан сводной таблицы';
                $errors = implode(', ',$errors);
                ?>

                <? if (!empty($item->summary_table_id)):?>
                <a class="btn btn-danger  btn-block btn-color-black" target="_blank" id="summary_table_<?=$item->id?>"
                   href="index.php?action=act_summary_table&id=<?php echo $item->id; ?>">Просмотреть/напечатать Сводный протокол</a>
                <div></div>
                <? endif;?>
                <? if (!empty($item->isActPrinted())):?>
                <a class="btn btn-danger  btn-block btn-color-black" target="_blank" id="act_print_<?=$item->id?>"
                   href="index.php?action=act_print_view&id=<?php echo $item->id; ?>">Просмотреть/напечатать Акт</a>
                <div></div>
                <? endif;?>

                <? if (!empty($item->isActTablePrinted()) && !empty($item->file_act_tabl_id)) $tabl_href=$item->getFileActTabl()->getDownloadURL();
                else $tabl_href = "index.php?action=act_table_print_view&id=".$item->id; ?>

                <a class="btn btn-danger  btn-block btn-color-black" target="_blank" id="act_table_print_<?=$item->id?>"
                   href="<?php echo $tabl_href; ?>">Просмотреть/напечатать Сводную таблицу</a>
                <div></div>

            </td>
        </tr>

    <?php endforeach; ?>
    </tbody>
</table>
