<? if (!empty($legend)): ?>
<h1><?=$legend ?></h1>
<? endif; ?>
<form method="post" id="add_form">
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

           if ($fieldName=='login')
        {
            $prefix='ufms_';
            $suffix='<br>(введите номер региона)';
        }
           else
        {
            $prefix=$suffix='';

        }

            ?>
            <tr>
                <th><?= $translate.$suffix ?></th>
                <td>
                    <? switch ($type):
                        case 'checkbox':
                            ?>
                            <input type="<?= $type ?>" name="<?= $fieldName ?>" value="1" <? if($item->$fieldName): ?>checked="checked" <? endif;?>>
                            <? break;
                        default:
                            ?>

                                <input type="<?= $type ?>" name="<?= $fieldName ?>" value="<?= $prefix.$item->$fieldName ?>">
                            <? endswitch; ?>
                </td>


            </tr>
        <? endforeach ?>
<input type="hidden" name="user_type_id" value="<?=$_GET['user_type_id']?>">
        <? if (!empty($_GET['u_id'])):?>
<input type="hidden" name="u_id" value="<?=$_GET['u_id']?>">
        <? endif;?>
        <? if (!empty($_GET['head_visible']) && $_GET['head_visible']==1):?>
    <tr><td>Головной центр</td><td><select name="head_id">
        <?

        $head_centers=HeadCenters::getAll();
        foreach ($head_centers as $h_c)
        {
            if (isset($_GET['head_id']) && $_GET['head_id']==$h_c->id) $selected='selected="selected"';else $selected='';
            //echo '<option value="'.$h_c->id.'">'.$h_c->short_name.'</option>';
            echo '<option value="'.$h_c->id.'" '.$selected.'>'.$h_c->name.'</option>';


        }
                echo '</select></td></tr>';
     endif;
                ?>

         <? if (!empty($_GET['user_type_id']) && ($_GET['user_type_id']==17 || $_GET['user_type_id']==3) ):?>


                <? if (!empty($_SESSION['privelegies']['fms_admin']) && $_SESSION['u_id'] != MAINEST_FMS_ADMIN_ID)
                {
                    $user_region=FmsRegionUser::getByUser($_SESSION['u_id'])->id_region;
                    echo '<input type="hidden" name="fms_region" value='.$user_region.'>';
                }
                else
                {?>
                <tr><td>Регион</td><td><select name="fms_region">
                            <?

                            $fms_regions=FmsRegions::getAll();
                            //        var_dump($fms_regions);
                            $chosen=FmsRegionUser::getByUser(filter_input(INPUT_GET,'u_id',FILTER_VALIDATE_INT) );
                            foreach ($fms_regions as $f_r)
                            {
                                if (!empty($chosen) && $chosen->id_region==$f_r->id) $selected='selected="selected"';else $selected='';
                                //echo '<option value="'.$h_c->id.'">'.$h_c->short_name.'</option>';
                                echo '<option value="'.$f_r->id.'" '.$selected.'>'.$f_r->caption.' ('.$f_r->r_num.')</option>';


                            }
                            echo '</select></td></tr>';
                            }


                            endif;
                ?>

<?//=$_GET['user_type_id'];?>
    </table>
    <!--<input type="submit" class="btn btn-success" name="submit" value="Сохранить">-->
</form>