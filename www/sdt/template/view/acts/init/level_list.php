<h1>Список подготовленных тестовых сессий </h1>
<h3>Всего <?= count($list) ?></h3>

<table class="table table-bordered  table-striped">
    <thead>
    <tr>
        <th valign="top">
            <nobr>№</nobr>
        </th>
        <th valign="top">Дата создания</th>

        <th valign="top">Дата тестирования</th>
        <!-- <th valign="top">Номер счета</th>
         <th valign="top">Дата счета</th>
         -->
        <th valign="top">Cчет<br>номер/дата</th>
        <th valign="top">Исполнитель</th>

        <th valign="top">Комментарий</th>
        <th valign="top">&nbsp;</th>
    </tr>
    </thead>
    <tbody>


    <?php

    foreach ($list as $item):
        /** @var Act $item */
        ?>

        <tr class="<? if ($item->is_changed_checker): ?>error<? endif; ?>">
            <td><?= $item->number ?></td>
            <td><?= $C->date($item->created) ?></td>


            <td><?php echo $C->date($item->testing_date) ?>
            </td>
            <td>
                <? if ($item->invoicePrinted()):
                    echo $item->invoice_index . '/' . $item->invoice ?>
                    <br><?= $C->date($item->invoice_date) ?>  <br>
                    <strong> <?= $item->amount_contributions ?> руб.</strong>
                <? endif ?>
            </td>
            <td class="wrap"><?php echo $item->responsible ?>
            </td>

            <td class="wrap text-error"><?php echo $item->comment ?>
            </td>
            <td>


                <? /*if ($C->userHasRole(Roles::ROLE_CENTER_PFUR_API)):

                    if ($item->allow_summary_table()):
                        if (!empty($item->official)) $offical = $item->official; else $offical = '';
                        if (!empty($item->responsible)) $responsible = $item->responsible; else $responsible = ''; ?>

                        <div class="btn-group">
                        <a
                            data-id="<?=$item->id;?>"
                            data-official="<?=$offical;?>"
                            data-responsible="<?=$responsible;?>"
                            class="summary_table btn invoice btn-danger  btn-mini  btn-block">Сформировать Сводный протокол</a>
                        </div>
                    <? endif; ?>
<!--                    <div class="btn-group">-->
                        <a
                            target="_blank"
                            href="index.php?action=act_table_print&id=<?php echo $item->id; ?>"
                            class=" btn  btn-danger  btn-mini  btn-block">Сформировать Сводную таблицу</a>
<!--                    </div>-->

                <? endif;*/ //29.11.17
                ?>


                <a class="btn btn-info btn-mini  btn-block"
                   href="index.php?action=act_fs_view&id=<?php echo $item->id; ?>">Карточка</a>

                <div class="btn-group  btn-block" style="margin-top: 0">
                    <button class="btn dropdown-toggle btn-mini btn-warning  btn-block" data-toggle="dropdown">
                        Редактировать
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class=""
                               href="index.php?action=act_fs_edit&id=<?php echo $item->id; ?>">акт</a>
                        </li>
                        <li><a class=""
                               href="index.php?action=act_table&id=<?php echo $item->id; ?>"
                               target="_blank">сводную таблицу</a></li>
                    </ul>
                </div>

                <? if (
                    !$C->userHasRole(Roles::ROLE_CENTER_EXTERNAL_API)

                 ): ?>
                     <?php if ($item->checkRequiredFields($errors) && $item->canBeSended()): ?>
                         <a onclick="return confirm('Отправить документы тестовой сессии на проверку в Головной центр.' +
              '\nВы уверены?');"
                            class="btn btn-success btn-mini payd  btn-block"
                            href="index.php?action=act_send&id=<?php echo $item->id; ?>">Утвердить и Отправить на
                             проверку</a>
                     <?php else: ?>
                         <a onclick="alert('<?= wordwrap(ucfirst(implode(', ', $errors)), 60, '\n') ?>'); return false"
                            class="btn btn-success btn-mini payd  btn-block disabled"
                            href="">Утвердить и Отправить на проверку</a> <!--<a onclick="alert('Необходимо заполнить ФИО тестируемых на 2-х языках,\nввести баллы, загрузить все сканы паспортов \nи заполнить поле &quot;тесторы&quot;,\nзагрузить скан, предъявленного сертификата,\nпри выборе \'упрощенного\' уровня тестирования.'); return false"
                            class="btn btn-success btn-mini payd  btn-block disabled"
                            href="">Отправить на проверку</a>-->
                     <?php endif; ?>

                 <? endif?>

                <? if ($C->userHasRole(Roles::ROLE_CENTER_EXTERNAL_API)): ?>
                    <?php if ($item->checkRequiredFields($errors)): ?>
                        <a onclick="return confirm('Отправить документы тестовой сессии  в Головной центр.' +
			 '\nВы уверены?');"
                           class="btn btn-success btn-mini payd  btn-block"
                           href="api.php?action=act_api_finished&id=<?php echo $item->id; ?>">Отправить в головной
                            центр</a>
                    <?php else: ?>
                        <a onclick="alert('<?= wordwrap(ucfirst(implode(', ', $errors)), 60, '\n') ?>'); return false"
                           class="btn btn-success btn-mini payd  btn-block disabled"
                           href="">Отправить в головной центр</a>
                    <?php endif; ?>

                <? endif ?>


                <a href="index.php?action=act_invalid&id=<?php echo $item->id; ?>"
                   onclick="return confirm('ВНИМАНИЕ! Все введенные данные тестовой сессии \nбудут безвозвратно удалены! Вы уверены?');"
                   class="btn btn-danger   btn-block btn-mini">Недейств</a>
            </td>
        </tr>

    <?php endforeach; ?>
    </tbody>
</table>


<div class="modal hide fade" id="summary_table" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <form method="post"
          action="index.php?action=summary_table_print" class="form-horizontal" target="_blank">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                    aria-hidden="true">&times;
            </button>
            <h3 id="myModalLabel">Сформировать Сводный протокол</h3>
        </div>
        <div class="modal-body">


            <div class="control-group">
                <label class="control-label" for="invoice_signing">Ответственный от локального центра</label>

                <div class="controls">
                    <select name="ls" id="signers2">
                        <option value="responsible" id="responsible"></option>
                        <option value="official" id="official"></option>

                    </select>
                </div>
            </div>


            <div class="control-group">
                <label class="control-label" for="invoice_signing">Ответственный от головного центра</label>

                <div class="controls">
                    <select name="hs_id" id="signers">
                        <?php
                        $signers = ActSignings::get4Act();
                        foreach ($signers as $signer): ?>
                            <option value="<?php echo $signer->id; ?>"><?php echo $signer->caption; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

        </div>
        <input type="hidden" value="" name="id" id="act_id">
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
            <button class="btn btn-primary save" type="submit"
                    onclick="$('#summary_table').modal('hide');setTimeout(function() { window.location=window.location }, 1000);">
                <!--Сформировать-->Сохранить
            </button>
        </div>
    </form>
</div>


<script>
    $(function () {

        /*        $('.act_upload').on('click', function (e) {
                 e.preventDefault();
                 $('#act').modal();
                 });
                 $('.act_tabl_upload').on('click', function (e) {
                 e.preventDefault();
                 $('#act_tabl').modal();
                 });*/
        $('.summary_table').on('click', function (e) {
            e.preventDefault();
            // $("#signers2").val()
            var $this = $(this);
            var actId = $this.data('id');
            var official = String($this.data('official'));
            var responsible = String($this.data('responsible'));
//            alert (actId);

            $('#act_id').val(actId);
            $('#official').html(official);
            $('#responsible').html(responsible);

            if (official.length) $('#official').show();
//            if (responsible.length) $('#responsible').show();
            if (official.length < 1 && responsible.length < 1) {
                $('#official').hide();
            }


            $('#summary_table').modal();
        });

    });
</script>