<? $values=$object->getAvailableValues($field);

?>
<select name="<?php echo $field; ?>" id="<?php echo $field; ?>" class="input-xxlarge">
    <? foreach($values as $key=>$value):?>
    <option value="<?=$key?>"
            <? if ($key==$object->$field):?> selected="selected" <?endif;?>
        ><?=$value?></option>
    <? endforeach; ?>

</select>
