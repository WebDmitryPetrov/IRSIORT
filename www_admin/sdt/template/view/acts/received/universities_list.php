<h1>Список центров тестирования, приславших документы на оформление</h1>

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
    <?php $i=0; foreach ($list as $univer): ?>
        <tr>
            <td>
                <?=++$i;?>
            </td>
            <td>
                  <a target="_blank" href="index.php?action=act_received_list&uid=<?=$univer->id;?>" ><?=$univer->caption;?></a>
            </td>
            <td>
                <strong><?=$univer->count;?></strong>
            </td>
        </tr>


        <?php endforeach; ?>

    </tbody>
</table>