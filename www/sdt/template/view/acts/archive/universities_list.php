<h1>Архив. Список центров тестирования.</h1>

<table class="table table-bordered  table-striped">
    <thead>
    <tr>
        <th valign="top">
            <nobr>№</nobr>
        </th>
        <th valign="top">Название центра</th>
        <th valign="top">Актов</th>
        <th valign="top">
            <nobr>Не оплачено</nobr>
        </th>
    </tr>
    </thead>
    <tbody>
    <? $prev_parent = null; ?>
    <?php $i = 0;
    foreach ($list as $univer):

        ?>
        <? if (isset($univer->parent_name) && $univer->parent_name != $prev_parent):
        $prev_parent = $univer->parent_name

        ?>
        <tr>
            <td></td>
            <td colspan="6">
                <?= $univer->parent_name ?>
            </td>
        </tr>
    <? endif ?>
        <tr class="<?= $univer->parent_id ? 'info' : '' ?>">
            <td>
                <?= ++$i; ?>
            </td>
            <td style="<?= $univer->parent_id ? 'padding-left:40px;' : '' ?>">
                <a target="_blank"
                   href="index.php?action=act_archive_list&uid=<?= $univer->id; ?>"><?= $univer->caption; ?></a>
                <? if ($univer->deleted): ?>удален<? endif ?>
            </td>
            <td>
                <strong><?= $univer->count; ?></strong>
            </td>
            <td>
                <strong><?= $univer->unpaid; ?></strong>
            </td>
        </tr>


    <?php endforeach; ?>

    </tbody>
</table>