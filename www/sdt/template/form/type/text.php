<textarea class="input-xxlarge" name="<?php echo $field; ?>" id="<?php echo $field; ?>" rows="5" cols="50"><?php echo $object->$field?></textarea>
<?php if ($fieldError = $object->getValidateError($field)):
    if (is_array($fieldError)) $fieldError = implode(', ', $fieldError); ?>
    <span class="help-inline"><?= $fieldError ?></span>
<?php endif; ?>