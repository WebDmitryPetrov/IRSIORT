<?php
/** @var University $object */
?>

<table class="table table-bordered  table-striped">

    <tr>
        <th>�����������</th>
        <td><?=$object->name; ?></td>
    </tr>



	<tr>
		<th>������������
		</th>
        <td>
            <form method="post">
            <?php            foreach ($users as $caption=> $user_list):        ?>
                <div class="well">
                    <strong><?=$caption?></strong>
                <?php            foreach ($user_list as  $user):        ?>
            <label>
                <input type="checkbox" name="users[]" value="<?=$user['id']?>"
                <? if ($user['checked']):?>
                checked="checked"
                <? endif; ?>
                > <?=$user['caption']?>
            </label>
        <?php endforeach;             ?>
                </div>
        <?php endforeach;             ?>
                <input type="submit" class="btn btn-success" value="���������">
            </form>
		</td>
	</tr>


</table>
