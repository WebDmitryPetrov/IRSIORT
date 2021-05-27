<?
/** @var Thread[] $threads */
?>
<? if (!empty($threads)):?>
<table class="table table-bordered">
    <tr>
        <th>Тема</th>
        <th>Отправитель</th>
        <th>Дата создания</th>
        <th>Дата последного сообщения</th>
        <th></th>
    </tr>
<? foreach($threads as $t):

    $lastMessage =$t->getLastMessage($current_key);

    ?>
    <tr class="<?if (!$lastMessage['is_read']):?>success<?endif?>">
        <td><?=$t->subject ?></td>
        <td><?=$m->getCaptionByKey($t->user_created_key) ?></td>
        <td><?=$C->dateTime($t->created_at); ?></td>
        <td><?=$C->dateTime($lastMessage['created_at']); ?></td>
        <td><a href="index.php?action=message_view&id=<?php echo $t->id; ?>"">Просмотреть</a></td>
    </tr>
    <? endforeach;?>
</table>
    <? else:?>
    <h3>Нет сообщений</h3>
<? endif;?>