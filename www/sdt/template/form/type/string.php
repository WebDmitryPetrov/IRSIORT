<input class="input-xxlarge error" type="text" name="<?php echo $field; ?>" id="<?php echo $field; ?>"
       value="<?php echo htmlspecialchars($object->$field, ENT_QUOTES) ?>">
<?php if ($fieldError = $object->getValidateError($field)):
    if (is_array($fieldError)) $fieldError = implode(', ', $fieldError); ?>
    <span class="help-inline"><?= $fieldError ?></span>
<?php endif; ?>