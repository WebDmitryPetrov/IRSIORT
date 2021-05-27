
<a href="dubl.php?action=dubl" class="btn btn-info">Назад к выбору типа тестирования</a>

<h1>Запросы на дубликаты</h1>
<a href="dubl.php?action=dubl_create&type=<?=$type;?>" class="btn btn-success">Создать новый запрос на дубликат</a>
<table class="table table-bordered">
    <thead>
    <tr>
        <th>Дата создания</th>
        <th>Количество людей</th>
        <th>Комментарий</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <? foreach($list as $item):?>
        <tr>
            <td><?=$C->dateTime($item->created)?></td>
            <td><?=count($item->getPeople()); ?></td>
            <td><?=$item->comment; ?></td>
            <td>
                <a href="dubl.php?action=dubl_edit&id=<?=$item->id?>" class="btn btn-info btn-block">Редактировать акт</a>
                <a href="dubl.php?action=dubl_show&id=<?=$item->id?>" class="btn btn-warning btn-block">Добавить/редактировать список</a>
                <? if ($item->chechForSend()):?>
                <a class="btn btn-success btn-block disabled" onclick="alert('Отправка невозможна: <?=implode(',',$item->chechForSend());?>')">Отправить запрос </a>
                <? else: ?>
                <a href="dubl.php?action=dubl_send&id=<?=$item->id?>" class="btn btn-success btn-block" onclick="return confirm('Вы уверены?')">Отправить запрос </a>
                <? endif?>
                <a href="dubl.php?action=dubl_delete&id=<?=$item->id?>" class="btn btn-danger btn-block" onclick="return confirm('Вы уверены?')">Удалить запрос</a>
            </td>
        </tr>
    <? endforeach;?>
    </tbody>
</table>
