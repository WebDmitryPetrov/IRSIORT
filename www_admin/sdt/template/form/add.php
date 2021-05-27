<form method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>"
      class="form-horizontal">
    <?php if (!empty($legend)): ?>
        <legend>
            <?php echo $legend; ?>
        </legend>
    <?php endif; ?>

    <?php foreach ($object->getFormFields() as $field):

        if (is_string($field)):
            ?>

            <div class="control-group
             control-group-<?=$field?>">
                <label class="control-label" for="<?php echo $field; ?>"><?php echo $object->getTranslate($field); ?>
                </label>

                <div class="controls">
                    <?php include('type/' . $object->getFieldType($field) . '.php'); ?>
                </div>
            </div>
        <? elseif (is_array($field) && isset($field['name'])): ?>
            <? if ($field['type'] === 'include' && isset($field['source'])): ?>
                <?php include('include/' . $field['source']); ?>
            <? endif ?>
        <? endif ?>
    <?php endforeach; ?>


    <div class="form-actions">
        <button class="btn btn-success" type="submit">Сохранить</button>
        <?php if (!empty($buttons)):
            foreach ($buttons as $button):
                ?>
                <a class="btn" href="<?= $button['link']; ?>"><?= $button['caption']; ?></a>
                <?php
            endforeach;
        endif; ?>
    </div>
</form>
