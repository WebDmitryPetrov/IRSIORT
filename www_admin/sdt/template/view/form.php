<? if (!empty($legend)): ?>
<h1><?=$legend ?></h1>
<? endif; ?>
<form method="post">
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
    </table>
    <input type="submit" class="btn btn-success" name="submit" value="Сохранить">
</form>