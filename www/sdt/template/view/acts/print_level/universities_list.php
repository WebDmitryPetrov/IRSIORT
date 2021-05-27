<h1><?= $caption ?></h1>
<table class="table table-bordered">
    <tr><td><span class="acts-all">Всего тестовых сессий</span></td></tr>
    <tr><td><span class="acts-no-invoice">Количество сессий с не выставленными счетами</span></td></tr>
    <tr><td><span class="acts-no-blanks">Количество сессий  с  не введенными номерами сертификатов и справок</span></td></tr>
    <tr><td><span class="acts-wait-paiment">Количество сессий, ждущих  оплаты</span></td></tr>
    <tr><td><span class="acts-to-archive">Выполнены все действия</span></td></tr>
</table>
<table class="table table-bordered  table-striped">
    <thead>
    <tr>
        <th valign="top">
            <nobr>№</nobr>
        </th>
        <th valign="top">Название центра</th>
        <th valign="top" colspan="5"><nobr>Количество сессий</nobr></th>
    </tr>
    </thead>
    <tbody>
    <?php $i = 0;
    $prev_parent = null;
    foreach ($list as $univer): ?>
        <? if (isset($univer->parent_name) && $univer->parent_name!=$prev_parent):
            $prev_parent=$univer->parent_name
            ?>
            <tr>
                <td></td>
                <td colspan="6">
                    <?=$univer->parent_name?>
                </td>
            </tr>
            <?endif?>
        <tr class="<?=$univer->parent_id?'info':'' ?>">
            <td>
                <?= ++$i; ?>
            </td>
            <td style="<?=$univer->parent_id?'padding-left:40px;':'' ?>">
                <? /*<a target="_blank" href="index.php?action=act_print_list&uid=<?=$univer->id;?>" ><?=$univer->caption;?></a> */ ?>
                <a target="_blank"
                   href="index.php?action=act_list&uid=<?= $univer->id; ?>&type=<?= $level_type; ?>"><?= $univer->caption; ?></a>
            </td>
            <td>
                <span class="acts-count acts-all"><?= $univer->count; ?></span>
            </td>
            <td>
                <span class="acts-count acts-no-invoice"><?= $univer->no_invoice; ?></span>
            </td>
            <td>
                <span class="acts-count acts-no-blanks"><?= $univer->no_blanks; ?></span>
            </td>
            <td>
                <span class="acts-count acts-wait-paiment"><?= $univer->wait_paid; ?></span>
            </td>
            <td>
                <span class="acts-count acts-to-archive"><?= $univer->to_arch; ?></span>
            </td>
        </tr>


    <?php endforeach; ?>

    </tbody>
</table>