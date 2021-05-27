<div class="btn-group">
    <a class="btn btn-primary btn-small dropdown-toggle" data-toggle="dropdown" href="#">
        Добавить (выбрать количество субтестов)<span class="caret"></span></a>
    <ul class="dropdown-menu">
        <? for ($i = 1; $i <= 15; $i++): ?>
            <li><a href="index.php?action=test_levels_add&count=<?= $i ?>"><?= $i ?></a></li>
        <? endfor; ?>
    </ul>
</div>
<? if (!$all_levels): ?>
    <a class="btn btn-info" href="?action=test_levels&all=1">Показать все</a>
<? else: ?>
    <a class="btn btn-info" href="?action=test_levels">Скрыть неопубликованные</a>
<? endif ?>

<table class="table table-bordered  table-striped">
    <tr>
        <th>Уровень</th>
        <!--<th>Чтение</th>
        <th>Письмо</th>
        <th>Лексика и грамматика</th>
        <th>Аудирование</th>
        <th>Устная речь</th>-->
        <th>Общий балл</th>
        <th>Экзамен</th>
        <th>Пересдача</th>
        <th colspan="2">Функции</th>
    </tr>
    <?php

    foreach ($list as $item): ?>
        <? /** @var TestLevel $item */ ?>
        <tr class="<? if ($item->test_group != 1): ?>warning<? endif; ?>">
            <td>
                <nobr>
                    <?php echo $item->caption; ?>
                </nobr>
            </td>

            <td>
                <?php echo $item->total; ?>
            </td>
            <td>
                <?php echo $item->price; ?>
            </td>
            <td>
                <?php echo $item->sub_test_price; ?>
            </td>
            <td>
                <a class="btn btn-warning btn-small"
                   href="index.php?action=test_levels_edit&id=<?php echo $item->id; ?>">Редактировать</a>

            </td>
            <td>
                <a class="btn btn-danger btn-small" onclick="return confirm('Вы уверены?');"
                   href="index.php?action=test_levels_delete&id=<?php echo $item->id; ?>">Удалить</a>
            </td>

        </tr>

    <?php endforeach; ?>

</table>