<h1>Список проверенных тестовых сессий</h1>
<h3>Всего <?=count($list)?></h3>

<table class="table table-bordered  table-striped">
    <thead>
    <tr>
        <th valign="top">
            <nobr>№</nobr>
        </th>
        <th valign="top">Дата создания</th>

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
        <td><?=$item->number?></td>
        <td><?=$C->date($item->created)?></td>
       <td><?php echo $C->date($item->testing_date) ?></td>

        <td class="wrap"><?php echo $item->responsible; ?></td>

        <td class="wrap"><?php echo $item->comment ?>
        </td>
        <td><a class="btn btn-info btn-mini btn-width-medium"
               href="index.php?action=act_third_view&id=<?php echo $item->id; ?>">Карточка</a>




            <?php
            $print = false;
            if ($item->getFileAct() && $item->getFileActTabl()) {
                $print = true;
            }

            ?>
            <?php  if ($print): ?>
                <a onclick="return confirm('Вы уверены?');"
                   class="btn btn-success btn-mini payd btn-width-medium"
                   href="index.php?action=act_finished&id=<?php echo $item->id; ?>">Отправить окончательно</a>
                <?php else: ?>
                <a onclick="alert('Необходимо загрузить сканы документов');return false;"
                   class="btn btn-success btn-mini payd disabled btn-width-medium"
                   href="#">Отправить окончательно</a>
                <?php endif; ?>
            <a onclick="return confirm('Уверены?');"
               class="btn btn-warning btn-mini payd  btn-width-medium"
               href="index.php?action=act_return_to_work&id=<?php echo $item->id; ?>">Вернуть в работу</a>
        </td>
    </tr>

        <?php endforeach; ?>
    </tbody>
</table>
