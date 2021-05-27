<?
/** @var Thread $thread */
?>

<p>Тема: <?=$thread->subject?></p>
<table class="table table-bordered">
    <tr>
        <th>Отправитель</th>
        <th>Дата</th>
        <th>Текст</th>

    </tr>
    <? foreach ($thread->getMessagesByKey($current_key) as $t):



    ?>
    <tr class="<? if (!$t->isRead()):
        $t->markIsRead($current_key);
        ?>success<? endif?>">
        <td><?= $m->getCaptionByKey($t->sender_key) ?></td>
        <td><?= $C->dateTime($t->created_at); ?></td>
        <td><?= $t->body ?></td>
    </tr>
<? endforeach; ?>
</table>
<? if (!$thread->isNotify): ?>
    <a class="btn btn-success" href="index.php?action=message_reply&id=<?php echo $t->id; ?>"">Ответить</a>
<? endif; ?>