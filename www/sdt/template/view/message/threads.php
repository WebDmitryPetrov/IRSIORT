<?
/** @var Thread[] $threads */
?>
<? if (!empty($threads)):?>
<table class="table table-bordered">
    <tr>
        <th>����</th>
        <th>�����������</th>
        <th>���� ��������</th>
        <th>���� ���������� ���������</th>
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
        <td><a href="index.php?action=message_view&id=<?php echo $t->id; ?>"">�����������</a></td>
    </tr>
    <? endforeach;?>
</table>
    <? else:?>
    <h3>��� ���������</h3>
<? endif;?>