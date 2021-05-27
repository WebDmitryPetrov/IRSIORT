<? $values = $object->getAvailableValues($field);

foreach ($values as $key => $value):
    ?>

    <label class="radio">

        <input type="radio" name="<?= $field ?>"
               id="<?= $field ?>_<?= $key ?>" value="<?= $key ?>"
               <? if ($object->$field==$key): ?>checked="checked"<? endif; ?>
        >
        <?= $value ?>
    </label>

<? endforeach;