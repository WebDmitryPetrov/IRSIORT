<?php
/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 17.06.14
 * Time: 15:59
 */
/** @var User $user */
?>
<form method="post">
    <table class="table table-bordered  table-striped">


        <? foreach (User::getEditForm() as $fieldName => $value):
            $type = 'text';
            $translate = '';
            if (is_string($value)) {
                $translate = $value;
            }
            if (is_array($value)) {
                if (!empty($value['type'])) {
                    $type = $value['type'];
                }
                if (!empty($value['translate'])) {
                    $translate = $value['translate'];
                }
            }
            ?>
            <tr>
                <th><?=$translate ?></th>
                <td><input type="<?=$type?>" name="<?=$fieldName?>" value="<?=$user->$fieldName?>"></td>

            </tr>
        <? endforeach ?>



    </table>

    <input type="submit" class="btn btn-success" name="submit" value="Сохранить">
</form>