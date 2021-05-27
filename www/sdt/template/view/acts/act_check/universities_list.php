<h1>Список центров с документами на проверку</h1>

<table class="table table-bordered  table-striped">
    <thead>
    <tr>
        <th valign="top">
            <nobr>№</nobr>
        </th>
        <th valign="top">Название центра</th>
        <th valign="top">Актов</th>
    </tr>
    </thead>
    <tbody>
    <?php $i=0;
    $prev_parent = null;
    foreach ($list as $univer):

        ?>
        <? if (isset($univer->parent_name) && $univer->parent_name!=$prev_parent):
        $prev_parent=$univer->parent_name
        ?>
            <tr>
                <td></td>
                <td colspan="2">
                    <?=$univer->parent_name?>
                </td>
            </tr>
        <?endif?>
        <tr  class="<?=$univer->parent_id?'info':'' ?>">
            <td>
                <?=++$i;?>
            </td>
            <td style="<?=$univer->parent_id?'padding-left:40px;':'' ?>">
                  <a href="index.php?action=on_check&uid=<?=$univer->id;?>" ><?=$univer->caption;?></a>
            </td>
            <td>
                <?if ($univer->unread):?>
                    <strong class="text-error"><?=$univer->unread?></strong> /
                <?endif?>
                <strong><?=$univer->count;?></strong>
            </td>
        </tr>


        <?php endforeach; ?>

    </tbody>
</table>