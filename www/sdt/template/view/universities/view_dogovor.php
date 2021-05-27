<h1>
    <?
    /** @var University $object */
    ?>
    <?php echo $object->name; ?>
</h1>
<a href="index.php?action=university_view&id=<?php echo $object->id; ?>">Вернуться в карточку локального центра</a>
<h2>Договоры</h2>


<a class="btn btn-primary btn-small"
   href="index.php?action=university_dogovor_add&id=<?= $object->id; ?>">Добавить</a>
<table class="table table-bordered  table-striped" table-condensed>

    <thead>
    <tr>
        <th>Номер</th>
        <th>Дата</th>
        <th>Название</th>
        <th>Тип договора</th>
        <th>Срок действия</th>
        <th>&nbsp</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $Now = new \DateTime();
    foreach ($object->getDogovors() as $dogovor): ?>
        <tr class="
	<?

        $valid = new \DateTime($dogovor->valid_date.' 23:59:59');
        if ($C->isDate($dogovor->valid_date) && $valid <= $Now): ?> error <? endif; ?>
	">
            <td><strong><?= $dogovor->number; ?></strong>
            </td>
            <td><?= $C->date($dogovor->date); ?>
            </td>
            <td><?= $dogovor->caption; ?>
            </td>

            <td>
                <?= $dogovor->getType(); ?>
                <?if ($dogovor->print_protocol):?>
                    <br><strong>только для Сводного протокола</strong>
                <?endif?>

                <?if ($dogovor->print_act):?>
                    <br><strong>только для Акта</strong>
                <?endif?>
            </td>
            <td><? if ($C->isDate($dogovor->valid_date)) {
                    echo $valid->format('d.m.Y');
                } ?>
            </td>
            <td><a class="btn btn-warning btn-small btn-block"
                   href="index.php?action=university_dogovor_edit&id=<?= $dogovor->id; ?>">Редактировать</a>
                <a class="btn btn-info btn-small upload_scan  btn-block" data-id="<?= $dogovor->id; ?>">Загрузить скан</a>
                <?php if ($dogovor->scan_id): ?>
                    <a class="btn btn-success btn-smal  btn-blockl" href="<?= $dogovor->getScan()->getDownloadUrl() ?>">Скачать
                        скан</a>
                <?php endif; ?>
                <a class="btn btn-danger  btn-small  btn-block"
                   onclick="return confirm('Вы уверены?');"
                   href="index.php?action=university_dogovor_delete&id=<?= $dogovor->id; ?>">Удалить</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>

</table>


<div class="modal hide fade" id="scan_form" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <form method="post" enctype="multipart/form-data"
          action="index.php?action=act_upload_dogovor_scan" class="form-inline">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                    aria-hidden="true">&times;</button>
            <h3 id="myModalLabel">Загрузить скан договора</h3>
        </div>
        <div class="modal-body">


            <legend>Выберите файл</legend>

            <input type="hidden" value="" name="id" id="id"> <input
                type="file" name="file" class="input-xlarge">

        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
            <button class="btn btn-primary save" type="submit">Загрузить</button>
        </div>
    </form>
</div>

<script>
    $(function () {
        $('.upload_scan').on('click', function (e) {
            e.preventDefault();
            $('#scan_form').find('#id').val($(this).data('id'));
            $('#scan_form').modal();
        });
    });
</script>