<?

/** @var University $u */
$object = $u;
?>
<?
if ($u->id): ?>
    <a class=""
       href="index.php?action=university_child_view&id=<?php
       echo $u->id ?>">Вернуться к просмотру</a>
<?
else: ?>
    <a class=""
       href="index.php?action=university_children&id=<?php
       echo $u->parent_id ?>">Вернуться к списку</a>
<?
endif ?>
<br><br>
<h1><?= $caption ?></h1>
<form method="post" action="<?= $_SERVER['REQUEST_URI'] ?>" class="form-horizontal">


    <?php
    $field = 'name'; ?>
    <div class="control-group
             control-group-<?= $field ?>
<?
    if (($object instanceof Model) && $object->getValidateError($field)): ?> error <?
    endif ?>
">
        <label class="control-label" for="name">Название </label>

        <div class="controls">
            <textarea class="input-xxlarge" name="name" id="name" rows="5"
                <?= $u->isParentedField('name') ? 'disabled=disabled' : '' ?>
                      cols="50"><?= htmlspecialchars($u->getParentedFieldValue('name')) ?></textarea>
            <?php
            if ($fieldError = $object->getValidateError($field)):
                if (is_array($fieldError)) {
                    $fieldError = implode(', ', $fieldError);
                } ?>
                <span class="help-inline"><?= $fieldError ?></span>
            <?php
            endif; ?>
        </div>
    </div>

    <?php
    $field = 'short_name'; ?>
    <div class="control-group
             control-group-<?= $field ?>
<?
    if (($object instanceof Model) && $object->getValidateError($field)): ?> error <?
    endif ?>
">
        <label class="control-label" for="short_name">Сокращенное название </label>

        <div class="controls">
            <input class="input-xxlarge" name="short_name"
                   id="short_name" <?= $u->isParentedField('short_name') ? 'disabled=disabled' : '' ?>
                   value="<?= htmlspecialchars($u->getParentedFieldValue('short_name')) ?>" type="text">
            <?php
            if ($fieldError = $object->getValidateError($field)):
                if (is_array($fieldError)) {
                    $fieldError = implode(', ', $fieldError);
                } ?>
                <span class="help-inline"><?= $fieldError ?></span>
            <?php
            endif; ?>
        </div>
    </div>

    <?php
    $field = 'rector'; ?>
    <div class="control-group
             control-group-<?= $field ?>
<?
    if (($object instanceof Model) && $object->getValidateError($field)): ?> error <?
    endif ?>
">
        <label class="control-label" for="rector">Ректор </label>

        <div class="controls">
            <input class="input-xxlarge" name="rector"
                   id="rector" <?= $u->isParentedField('rector') ? 'disabled=disabled' : '' ?>
                   value="<?= htmlspecialchars($u->getParentedFieldValue('rector')) ?>" type="text">
            <?php
            if ($fieldError = $object->getValidateError($field)):
                if (is_array($fieldError)) {
                    $fieldError = implode(', ', $fieldError);
                } ?>
                <span class="help-inline"><?= $fieldError ?></span>
            <?php
            endif; ?>
        </div>
    </div>

    <?php
    $field = 'form'; ?>
    <div class="control-group
             control-group-<?= $field ?>
<?
    if (($object instanceof Model) && $object->getValidateError($field)): ?> error <?
    endif ?>
">
        <label class="control-label" for="form">Правовая форма </label>

        <div class="controls">
            <input class="input-xxlarge" name="form"
                   id="form" <?= $u->isParentedField('form') ? 'disabled=disabled' : '' ?>
                   value="<?= htmlspecialchars($u->getParentedFieldValue('form')) ?>"
                   type="text">        <?php
        if ($fieldError = $object->getValidateError($field)):
            if (is_array($fieldError)) {
                $fieldError = implode(', ', $fieldError);
            } ?>
            <span class="help-inline"><?= $fieldError ?></span>
        <?php
        endif; ?>  </div>
    </div>

    <?php
    $field = 'legal_address'; ?>
    <div class="control-group
             control-group-<?= $field ?>
<?
    if (($object instanceof Model) && $object->getValidateError($field)): ?> error <?
    endif ?>
">
        <label class="control-label" for="legal_address">Адрес</label>

        <div class="controls">
            <textarea class="input-xxlarge" name="legal_address" id="legal_address"
                      rows="5" <?= $u->isParentedField('legal_address') ? 'disabled=disabled' : '' ?>
                      cols="50"><?= htmlspecialchars($u->getParentedFieldValue('legal_address')) ?></textarea>
            <?php
            if ($fieldError = $object->getValidateError($field)):
                if (is_array($fieldError)) {
                    $fieldError = implode(', ', $fieldError);
                } ?>
                <span class="help-inline"><?= $fieldError ?></span>
            <?php
            endif; ?>  </div>
    </div>

    <?php
    $field = 'contact_phone'; ?>
    <div class="control-group
             control-group-<?= $field ?>
<?
    if (($object instanceof Model) && $object->getValidateError($field)): ?> error <?
    endif ?>
">
        <label class="control-label" for="contact_phone">Телефон </label>

        <div class="controls">
            <input class="input-xxlarge" name="contact_phone"
                   id="contact_phone" <?= $u->isParentedField('contact_phone') ? 'disabled=disabled' : '' ?>
                   value="<?= htmlspecialchars($u->getParentedFieldValue('contact_phone')) ?>" type="text">
            <?php
            if ($fieldError = $object->getValidateError($field)):
                if (is_array($fieldError)) {
                    $fieldError = implode(', ', $fieldError);
                } ?>
                <span class="help-inline"><?= $fieldError ?></span>
            <?php
            endif; ?>
        </div>
    </div>

    <?php
    $field = 'contact_fax'; ?>
    <div class="control-group
             control-group-<?= $field ?>
<?
    if (($object instanceof Model) && $object->getValidateError($field)): ?> error <?
    endif ?>
">
        <label class="control-label" for="contact_fax">Факс </label>

        <div class="controls">
            <input class="input-xxlarge" name="contact_fax"
                   id="contact_fax" <?= $u->isParentedField('contact_fax') ? 'disabled=disabled' : '' ?>
                   value="<?= htmlspecialchars($u->getParentedFieldValue('contact_fax')) ?>" type="text">
            <?php
            if ($fieldError = $object->getValidateError($field)):
                if (is_array($fieldError)) {
                    $fieldError = implode(', ', $fieldError);
                } ?>
                <span class="help-inline"><?= $fieldError ?></span>
            <?php
            endif; ?>
        </div>
    </div>

    <?php
    $field = 'contact_email'; ?>
    <div class="control-group
             control-group-<?= $field ?>
<?
    if (($object instanceof Model) && $object->getValidateError($field)): ?> error <?
    endif ?>
">
        <label class="control-label" for="contact_email">Email </label>

        <div class="controls">
            <input class="input-xxlarge" name="contact_email"
                   id="contact_email" <?= $u->isParentedField('contact_email') ? 'disabled=disabled' : '' ?>
                   value="<?= htmlspecialchars($u->getParentedFieldValue('contact_email')) ?>" type="text">
            <?php
            if ($fieldError = $object->getValidateError($field)):
                if (is_array($fieldError)) {
                    $fieldError = implode(', ', $fieldError);
                } ?>
                <span class="help-inline"><?= $fieldError ?></span>
            <?php
            endif; ?>  </div>
    </div>

    <?php
    $field = 'contact_other'; ?>
    <div class="control-group
             control-group-<?= $field ?>
<?
    if (($object instanceof Model) && $object->getValidateError($field)): ?> error <?
    endif ?>
">
        <label class="control-label" for="contact_other">Дополнительные контакты </label>

        <div class="controls">
            <textarea class="input-xxlarge" name="contact_other" id="contact_other"
                      rows="5" <?= $u->isParentedField('contact_other') ? 'disabled=disabled' : '' ?>
                      cols="50"><?= htmlspecialchars($u->getParentedFieldValue('contact_other')) ?></textarea>
            <?php
            if ($fieldError = $object->getValidateError($field)):
                if (is_array($fieldError)) {
                    $fieldError = implode(', ', $fieldError);
                } ?>
                <span class="help-inline"><?= $fieldError ?></span>
            <?php
            endif; ?>
        </div>
    </div>

    <?php
    $field = 'responsible_person'; ?>
    <div class="control-group
             control-group-<?= $field ?>
<?
    if (($object instanceof Model) && $object->getValidateError($field)): ?> error <?
    endif ?>
">
        <label class="control-label" for="responsible_person">Ответственный за проведение тестирования </label>

        <div class="controls">
            <input class="input-xxlarge" name="responsible_person"
                   id="responsible_person" <?= $u->isParentedField('responsible_person') ? 'disabled=disabled' : '' ?>
                   value="<?= htmlspecialchars($u->getParentedFieldValue('responsible_person')) ?>"
                   type="text">
        <?php
        if ($fieldError = $object->getValidateError($field)):
            if (is_array($fieldError)) {
                $fieldError = implode(', ', $fieldError);
            } ?>
            <span class="help-inline"><?= $fieldError ?></span>
        <?php
        endif; ?>
    </div>
    </div>
    <?php
    $field = 'bank'; ?>
    <div class="control-group
             control-group-<?= $field ?>
<?
    if (($object instanceof Model) && $object->getValidateError($field)): ?> error <?
    endif ?>
">
        <label class="control-label" for="bank">Банк </label>

        <div class="controls">
            <input class="input-xxlarge" name="bank"
                   id="bank" <?= $u->isParentedField('bank') ? 'disabled=disabled' : '' ?>
                   value="<?= htmlspecialchars($u->getParentedFieldValue('bank')) ?>" type="text">
        <?php
        if ($fieldError = $object->getValidateError($field)):
            if (is_array($fieldError)) {
                $fieldError = implode(', ', $fieldError);
            } ?>
            <span class="help-inline"><?= $fieldError ?></span>
        <?php
        endif; ?>
    </div>
    </div>
    <?php
    $field = 'city'; ?>
    <div class="control-group
             control-group-<?= $field ?>
<?
    if (($object instanceof Model) && $object->getValidateError($field)): ?> error <?
    endif ?>
">
        <label class="control-label" for="city">Город </label>

        <div class="controls">
            <input class="input-xxlarge" name="city"
                   id="city" <?= $u->isParentedField('city') ? 'disabled=disabled' : '' ?>
                   value="<?= htmlspecialchars($u->getParentedFieldValue('city')) ?>" type="text">        <?php
        if ($fieldError = $object->getValidateError($field)):
            if (is_array($fieldError)) {
                $fieldError = implode(', ', $fieldError);
            } ?>
            <span class="help-inline"><?= $fieldError ?></span>
        <?php
        endif; ?>  </div>
    </div>

    <?php
    $field = 'rc'; ?>
    <div class="control-group
             control-group-<?= $field ?>
<?
    if (($object instanceof Model) && $object->getValidateError($field)): ?> error <?
    endif ?>
">
        <label class="control-label" for="rc">Расчетный счет </label>

        <div class="controls">
            <input class="input-xxlarge" name="rc" id="rc" <?= $u->isParentedField('rc') ? 'disabled=disabled' : '' ?>
                   value="<?= htmlspecialchars($u->getParentedFieldValue('rc')) ?>" type="text">        <?php
        if ($fieldError = $object->getValidateError($field)):
            if (is_array($fieldError)) {
                $fieldError = implode(', ', $fieldError);
            } ?>
            <span class="help-inline"><?= $fieldError ?></span>
        <?php
        endif; ?>  </div>
    </div>

    <?php
    $field = 'lc'; ?>
    <div class="control-group
             control-group-<?= $field ?>
<?
    if (($object instanceof Model) && $object->getValidateError($field)): ?> error <?
    endif ?>
">
        <label class="control-label" for="lc">Лицевой счет </label>

        <div class="controls">
            <input class="input-xxlarge" name="lc" id="lc" <?= $u->isParentedField('lc') ? 'disabled=disabled' : '' ?>
                   value="<?= htmlspecialchars($u->getParentedFieldValue('lc')) ?>" type="text">        <?php
        if ($fieldError = $object->getValidateError($field)):
            if (is_array($fieldError)) {
                $fieldError = implode(', ', $fieldError);
            } ?>
            <span class="help-inline"><?= $fieldError ?></span>
        <?php
        endif; ?>  </div>
    </div>

    <?php
    $field = 'kc'; ?>
    <div class="control-group
             control-group-<?= $field ?>
<?
    if (($object instanceof Model) && $object->getValidateError($field)): ?> error <?
    endif ?>
">
        <label class="control-label" for="kc">Корреспондентский счет </label>

        <div class="controls">
            <input class="input-xxlarge" name="kc" id="kc" <?= $u->isParentedField('kc') ? 'disabled=disabled' : '' ?>
                   value="<?= htmlspecialchars($u->getParentedFieldValue('kc')) ?>" type="text">        <?php
        if ($fieldError = $object->getValidateError($field)):
            if (is_array($fieldError)) {
                $fieldError = implode(', ', $fieldError);
            } ?>
            <span class="help-inline"><?= $fieldError ?></span>
        <?php
        endif; ?>  </div>
    </div>

    <?php
    $field = 'bik'; ?>
    <div class="control-group
             control-group-<?= $field ?>
<?
    if (($object instanceof Model) && $object->getValidateError($field)): ?> error <?
    endif ?>
">
        <label class="control-label" for="bik">БИК </label>

        <div class="controls">
            <input class="input-xxlarge" name="bik"
                   id="bik" <?= $u->isParentedField('bik') ? 'disabled=disabled' : '' ?>
                   value="<?= htmlspecialchars($u->getParentedFieldValue('bik')) ?>" type="text">        <?php
        if ($fieldError = $object->getValidateError($field)):
            if (is_array($fieldError)) {
                $fieldError = implode(', ', $fieldError);
            } ?>
            <span class="help-inline"><?= $fieldError ?></span>
        <?php
        endif; ?>  </div>
    </div>

    <?php
    $field = 'inn'; ?>
    <div class="control-group
             control-group-<?= $field ?>
<?
    if (($object instanceof Model) && $object->getValidateError($field)): ?> error <?
    endif ?>
">
        <label class="control-label" for="inn">ИНН </label>

        <div class="controls">
            <input class="input-xxlarge" name="inn"
                   id="inn" <?= $u->isParentedField('inn') ? 'disabled=disabled' : '' ?>
                   value="<?= htmlspecialchars($u->getParentedFieldValue('inn')) ?>" type="text">        <?php
        if ($fieldError = $object->getValidateError($field)):
            if (is_array($fieldError)) {
                $fieldError = implode(', ', $fieldError);
            } ?>
            <span class="help-inline"><?= $fieldError ?></span>
        <?php
        endif; ?>  </div>
    </div>

    <?php
    $field = 'kpp'; ?>
    <div class="control-group
             control-group-<?= $field ?>
<?
    if (($object instanceof Model) && $object->getValidateError($field)): ?> error <?
    endif ?>
">
        <label class="control-label" for="kpp">КПП </label>

        <div class="controls">
            <input class="input-xxlarge" name="kpp"
                   id="kpp" <?= $u->isParentedField('kpp') ? 'disabled=disabled' : '' ?>
                   value="<?= htmlspecialchars($u->getParentedFieldValue('kpp')) ?>" type="text">        <?php
        if ($fieldError = $object->getValidateError($field)):
            if (is_array($fieldError)) {
                $fieldError = implode(', ', $fieldError);
            } ?>
            <span class="help-inline"><?= $fieldError ?></span>
        <?php
        endif; ?>  </div>
    </div>

    <?php
    $field = 'okato'; ?>
    <div class="control-group
             control-group-<?= $field ?>
<?
    if (($object instanceof Model) && $object->getValidateError($field)): ?> error <?
    endif ?>
">
        <label class="control-label" for="okato">Код по ОКАТО </label>

        <div class="controls">
            <input class="input-xxlarge" name="okato"
                   id="okato" <?= $u->isParentedField('okato') ? 'disabled=disabled' : '' ?>
                   value="<?= htmlspecialchars($u->getParentedFieldValue('okato')) ?>" type="text">        <?php
        if ($fieldError = $object->getValidateError($field)):
            if (is_array($fieldError)) {
                $fieldError = implode(', ', $fieldError);
            } ?>
            <span class="help-inline"><?= $fieldError ?></span>
        <?php
        endif; ?>  </div>
    </div>

    <?php
    $field = 'okpo'; ?>
    <div class="control-group
             control-group-<?= $field ?>
<?
    if (($object instanceof Model) && $object->getValidateError($field)): ?> error <?
    endif ?>
">
        <label class="control-label" for="okpo">Код по ОКПО </label>

        <div class="controls">
            <input class="input-xxlarge" name="okpo"
                   id="okpo" <?= $u->isParentedField('okpo') ? 'disabled=disabled' : '' ?>
                   value="<?= htmlspecialchars($u->getParentedFieldValue('okpo')) ?>" type="text">        <?php
        if ($fieldError = $object->getValidateError($field)):
            if (is_array($fieldError)) {
                $fieldError = implode(', ', $fieldError);
            } ?>
            <span class="help-inline"><?= $fieldError ?></span>
        <?php
        endif; ?>  </div>
    </div>

    <?php
    $field = 'comments'; ?>
    <div class="control-group
             control-group-<?= $field ?>
<?
    if (($object instanceof Model) && $object->getValidateError($field)): ?> error <?
    endif ?>
">
        <label class="control-label" for="comments">Комментарии </label>

        <div class="controls">
            <textarea class="input-xxlarge" name="comments" id="comments"
                      rows="5" <?= $u->isParentedField('comments') ? 'disabled=disabled' : '' ?>
                      cols="50"><?= htmlspecialchars($u->getParentedFieldValue('comments')) ?></textarea>
            <?php
            if ($fieldError = $object->getValidateError($field)):
                if (is_array($fieldError)) {
                    $fieldError = implode(', ', $fieldError);
                } ?>
                <span class="help-inline"><?= $fieldError ?></span>
            <?php
            endif; ?>  </div>
    </div>

    <?php
    $field = 'country_id'; ?>
    <div class="control-group
             control-group-<?= $field ?>
<?
    if (($object instanceof Model) && $object->getValidateError($field)): ?> error <?
    endif ?>
">
        <label class="control-label" for="country_id">Страна </label>

        <div class="controls">
            <select name="country_id" id="country_id"
                    class="input-xxlarge" <?= $u->isParentedField('country_id') ? 'disabled=disabled' : '' ?>>
                <?
                foreach ($countries as $key => $c): ?>
                    <option value="<?= $key ?>" <?= $key == $u->getParentedFieldValue(
                        'country_id'
                    ) ? 'selected=selected' : '' ?>><?= $c ?></option>

                <?
                endforeach; ?>
            </select>
            <?php
            if ($fieldError = $object->getValidateError($field)):
                if (is_array($fieldError)) {
                    $fieldError = implode(', ', $fieldError);
                } ?>
                <span class="help-inline"><?= $fieldError ?></span>
            <?php
            endif; ?>  </div>
    </div>

    <?php
    $field = 'region_id'; ?>
    <div class="control-group
             control-group-<?= $field ?>
<?
    if (($object instanceof Model) && $object->getValidateError($field)): ?> error <?
    endif ?>
">
        <label class="control-label" for="region_id">Регион </label>

        <div class="controls">
            <select name="region_id" id="region_id"
                    class="input-xxlarge" <?= $u->isParentedField('region_id') ? 'disabled=disabled' : '' ?>>
                <?
                foreach ($regions as $key => $c): ?>
                    <option value="<?= $key ?>" <?= $key == $u->getParentedFieldValue(
                        'region_id'
                    ) ? 'selected=selected' : '' ?>><?= $c ?></option>

                <?
                endforeach; ?>
            </select>
            <?php
            if ($fieldError = $object->getValidateError($field)):
                if (is_array($fieldError)) {
                    $fieldError = implode(', ', $fieldError);
                } ?>
                <span class="help-inline"><?= $fieldError ?></span>
            <?php
            endif; ?>  </div>
    </div>


    <div class="form-actions">
        <button class="btn btn-success" type="submit">Сохранить</button>
        <?
        if ($u->id): ?>
            <a class="btn" href="index.php?action=university_child_edit&id=<?= $u->id ?>&do=change_pwd">Сбросить
                пароль</a>
        <?
        endif; ?>
    </div>
</form>