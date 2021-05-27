
<script>
    function check_uncheck(item_class)
    {
        if ($("#"+item_class).attr("checked"))
        {
            $("."+item_class).attr("checked","checked");
        }
        else $("."+item_class).removeAttr("checked");
    }
</script>


<?php
/**
 * Created by JetBrains PhpStorm.
 * User: LWR
 * Date: 27.08.15
 * Time: 12:01
 * To change this template use File | Settings | File Templates.
 */

        function checkboxes_slide(
            $data,
            $button_name = "Выбрать",
            $post_data=array(),
            $div_class="slide",
            $field_name="",
            $allied_rudn = "", /*для костыля объединенного РУДН в списке ГЦ*/
            $no_region = "" /*для костыля без проставленного региона*/
        )
{
    ?>

<div id="<?=$div_class?>_div" class="ui-widget-content ui-corner-all" style="display: none">
    <h3><?=$button_name?></h3>
    <?
//$regions=Regions::getAll();

if (empty($post_data))
{
    $selected='checked="checked"';
}
else $selected='';

echo '<input type="checkbox" id="'.$div_class.'" onclick="check_uncheck(\''.$div_class.'\')" '.$selected.'><b>Выбрать/снять ВСЕ</b><br>';

    /*костыль для объединенного РУДН в списке ГЦ*/
    if (!empty($allied_rudn))
    {
        if (empty($post_data) || in_array('allied_rudn', $post_data))
        {
            $selected='checked="checked"';
        }
        else $selected='';

        echo '<input type="checkbox" value="allied_rudn" name="'.$div_class.'[]" class="'.$div_class.'" '.$selected.'>Объединенный РУДН<br>';
    }
    /*конец костыля*/

    /*костыль для объединенного РУДН в списке ГЦ*/
    if (!empty($no_region))
    {
        if (empty($post_data) || in_array('no_region', $post_data))
        {
            $selected='checked="checked"';
        }
        else $selected='';

        echo '<input type="checkbox" value="no_region" name="'.$div_class.'[]" class="'.$div_class.'" '.$selected.'>Без проставленного региона<br>';
    }
    /*конец костыля*/


foreach ($data as $key => $value)
{
    if (empty($post_data) || in_array($value->id, $post_data))
    {
        $selected='checked="checked"';
    }
    else $selected='';


    if (!empty($field_name)) $val=$value->$field_name;
    else $val=$value;


    echo '<input type="checkbox" value="'.$value->id.'" name="'.$div_class.'[]" class="'.$div_class.'" '.$selected.'>'.$val.'<br>';
}
?>
<a class="btn btn-warning" onclick='$( "#<?=$div_class?>_div" ).hide(500);$( "#<?=$div_class?>_button" ).show();'>Свернуть</a>

</div>
<div>
<a class="btn btn-warning" id="<?=$div_class?>_button" onclick='$( "#<?=$div_class?>_div" ).show(500);$( "#<?=$div_class?>_button" ).hide();'><?=$button_name?></a>
</div>
<?
}


function date_from_to($from = '1.01.2015',$to = '')
{
    if (empty($to)) $to = date('d.m.Y');
    ?>
    <label>от :
            <div class="input-prepend date datepicker "
                 data-date="">
                <span class="add-on"><i class="icon-th"></i> </span> <input
                    class="input-small"
                    name="from"

                    readonly="readonly" size="16" type="text"
                    value="<?= $from ?>">
            </div>
        </label> <label>До :
            <div class="input-prepend date datepicker "
                 data-date="">
                <span class="add-on"><i class="icon-th"></i> </span> <input
                    class="input-small"
                    name="to"

                    readonly="readonly" size="16" type="text"
                    value="<?= $to ?>">
            </div>
        </label>
<?
}