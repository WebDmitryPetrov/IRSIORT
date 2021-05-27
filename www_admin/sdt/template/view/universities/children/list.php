<?php
/** @var University $object */
?>
    <h1>Список подчинённых локальных центров партнёра:<br> <?= $object->name ?></h1>


    <a href="index.php?action=university_view&id=<?php echo $object->id; ?>">Вернуться в карточку локального центра</a>
    <hr>
    <a target="_blank" class="btn btn-default"
       href="index.php?action=university_child_add&parent_id=<?php echo $object->id; ?>">
        Добавить подчинённый
        локальный центр</a><br><br>

<? if (!count($children)): ?>
    <h3 class="text-error">Подчинённые локальные центры отсутствуют</h3>
<? else: ?>
    <table class="table table-bordered  table-striped">
        <? foreach ($children as $child): ?>


            <tr>
                <th><?= $child->name;

                ?></th>
                <td style="width: 200px">
                    <a class="btn btn-info btn-small btn-block"
                       href="index.php?action=university_child_view&id=<?php echo $child->id; ?>">Карточка</a>
                    <a class="btn btn-warning btn-small btn-block"
                       href="index.php?action=university_child_edit&id=<?php echo $child->id; ?>">Редактировать</a>



                    <a href="index.php?action=university_child_delete&id=<?php echo $child->id; ?>"
                       onclick="return confirm('Вы уверены, что хотите удалить центр?\n' +
        'При удалении локального центра из Системы он будет удален из всех списков центров Системы, кроме того, будут удалены все связанные с ним тестовые сессии за исключением сессий, находящихся в архиве!');" class="btn btn-danger btn-block btn-small">Удалить</a>
                </td>
            </tr>

        <? endforeach; ?>
    </table>

<? endif ?>