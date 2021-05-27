<? if (Errors::get(Errors::XML, Errors::UPLOAD)): ?>
    <? echo Errors::get(Errors::XML, Errors::UPLOAD, true); ?>
<? endif ?>

<? if (Errors::get(Errors::XML, Errors::PARSE)): ?>
    <? echo Errors::get(Errors::XML, Errors::PARSE, true); ?>
<? endif ?>
<form class="form-horizontal" method="post" enctype="multipart/form-data"
      action="<?= $_SERVER['PHP_SELF'] ?>?action=xml_upload">
    <div class="control-group">
        <label class="control-label" for="file">Выбрать XML файл</label>

        <div class="controls">
            <input type="file" id="file" name="file">
        </div>
    </div>
    <div class="control-group">
        <div class="controls">

            <button type="submit" class="btn">Загрузить</button>
        </div>
    </div>
</form>