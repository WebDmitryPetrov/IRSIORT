<? if (Errors::get(Errors::XML, Errors::UPLOAD)): ?>
    <? echo Errors::get(Errors::XML, Errors::UPLOAD, true); ?>
<? endif ?>

<? $parseErrors = Errors::get(Errors::XML, Errors::PARSE);
if ($parseErrors): ?>
    <ul>
        <? foreach ($parseErrors as $e): ?>
            <li><?=$e?></li>
        <? endforeach; ?>
    </ul>
<? endif ?>
<form class="form-horizontal" method="post" enctype="multipart/form-data"
      action="<?= $_SERVER['PHP_SELF'] ?>?action=xml_upload">
    <div class="control-group">
        <label class="control-label" for="file">������� XML ����</label>

        <div class="controls">
            <input type="file" id="file" name="file">
        </div>
    </div>
    <div class="control-group">
        <div class="controls">

            <button type="submit" class="btn">���������</button>
        </div>
    </div>
</form>