<? if (!empty($legend)): ?>
<h1><?=$legend ?></h1>
<? endif; ?>
<form method="post" id="edit_form">
    <table class="table table-bordered  table-striped">


        <? foreach ($fields as $fieldName => $value):
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
                <th><?= $translate ?></th>
                <td>
                    <? switch ($type):
                        case 'checkbox':
                            ?>
                            <input type="<?= $type ?>" name="<?= $fieldName ?>" value="1" <? if($item->$fieldName): ?>checked="checked" <? endif;?>>
                            <? break;
                        default:
                            ?>
                                <input type="<?= $type ?>" name="<?= $fieldName ?>" value="<?= $item->$fieldName ?>">
                            <? endswitch; ?>
                </td>


            </tr>
        <? endforeach ?>
<input type="hidden" name="user_type_id" value="<?=$_GET['user_type_id']?>">
        <? if ($_GET['u_id']):?>
<input type="hidden" name="u_id" value="<?=$_GET['u_id']?>">
        <? endif;?>



    </table>
    <!--<input type="submit" class="btn btn-success" name="submit" value="Сохранить">-->
</form>