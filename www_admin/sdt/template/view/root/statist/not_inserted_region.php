<?php
/** @var HeadCenter[] $hc_list */
?>
<form action="" method="post">
    <fieldset name="Головной центр">
        <? foreach ($hc_list as $hc_item): ?>
            <label><input type="checkbox" value="<?= $hc_item->id ?>" name="hc[]"
                          <? if (in_array($hc_item->id, $selected_hc)): ?>checked="checked"<? endif ?>>
                <?= $hc_item->short_name ?>
            </label>
        <? endforeach ?>
    </fieldset>
    <button type="submit" name="send">Создать</button>
</form>
<? if ($runned): ?>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Головной центр</th>
            <th>Локальный центр</th>
            <th>Id локального центра</th>
        </tr>
        </thead>
        <tbody>
        <? foreach ($result as $row): ?>
            <tr>
                <td><?=$row['short_name']?></td>
                <td><?=$row['name']?></td>
                <td><?=$row['local_id']?></td>
            </tr>
        <? endforeach ?>
        </tbody>
    </table>
<? endif ?>
