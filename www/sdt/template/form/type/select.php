<? $values=$object->getAvailableValues($field);

?>
<select name="<?php echo $field; ?>" id="<?php echo $field; ?>" class="input-xxlarge">
    <? foreach($values as $key=>$value):?>
        <option value="<?=$key?>"
            <? if ($key==$object->$field):?> selected="selected" <?endif;?>
        ><?=$value?></option>
    <? endforeach; ?>

</select>
<?php if ($fieldError = $object->getValidateError($field)):
    if (is_array($fieldError)) $fieldError = implode(', ', $fieldError); ?>
    <span class="help-inline"><?= $fieldError ?></span>
<?php endif; ?>