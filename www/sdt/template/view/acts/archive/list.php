<?php
/** @var University $univer */
/** @var Act $act */
$signings = ActSignings::get4VidachaCert();
$act = current($list);
//if (empty($act)) {
//    echo '<h2>Документов не найдено</h2>';
//    return;
//}
$horg = $univer->getHeadCenter()->horg_id;


?>
<h1>Архивный список документов центра - <?php echo $univer; ?></h1>
<? if ($univer->parent_id): ?>
    <h2>Партнёр: <?= $univer->getParent()->name ?></h2>
<? endif ?>
<? if (empty($onlyNoPayd)): ?>
    <a class="btn btn-warning" href="?action=act_archive_list&notpayd=1&uid=<?= $uid ?>">Фильтр по неоплаченным
        актам</a>
<? else: ?>
    <h3>Отфильтровано по неоплаченным</h3>
    <a class="btn btn-warning" href="?action=act_archive_list&uid=<?= $uid ?>">Показать все</a>
<? endif ?>
<h3>Всего <?= count($list) ?></h3>

<table class="table table-bordered  table-striped">
    <thead>
    <tr>
        <th valign="top">
            <nobr>№</nobr>
        </th>
        <th valign="top">id</th>
        <th valign="top">Дата создания Акта</th>

        <th valign="top">Дата тестирования</th>
        <th valign="top">Cчет<br>номер/дата</th>
        <th valign="top">Оплачено</th>


        <th valign="top">Комментарий</th>
        <th valign="top">&nbsp;</th>
    </tr>
    </thead>
    <tbody>


    <?php

    foreach ($list as $item):
        /** @var Act $item */
        ?>
        <tr>
            <td><?= $item->number ?></td>
            <td><?= $item->id ?></td>
            <td><?= $C->date($item->created) ?></td>


            <td><?php echo $C->date($item->testing_date) ?>
            </td>
            <td>   <?php if (strlen($item->invoice)): echo $item->invoice_index . '/' . $item->invoice ?>
                    <br><?php echo $C->date($item->invoice_date); endif; ?>
            </td>
            <td><?php echo $item->paid ? 'Да' : 'Нет'; ?>
            </td>

            <td class="wrap text-success"><?php echo $item->comment ?>
            </td>
            <td class="button-width-50">
                <?php /*if ($item->summary_table_id): ?>
                    <a class="btn btn-danger btn-color-black" target="_blank"
                       href="index.php?action=act_summary_table&id=<?php echo $item->id; ?>">Просмотреть/напечатать
                        Сводный протокол</a>
                    <div></div>
                <? endif */?>

                <? if ($item->allow_summary_table() && !$item->summary_table_id
                    && in_array($item->id, Sdt_Config::getSummaryProtocolArchive())):




                    if (!empty($item->official)) $offical = $item->official; else $offical = '';
                    if (!empty($item->responsible)) $responsible = $item->responsible; else $responsible = ''; ?>

                    <div class="btn-group">
                        <a
                                data-id="<?= $item->id; ?>"
                                data-official="<?= $offical; ?>"
                                data-responsible="<?= $responsible; ?>"
                                class="summary_table btn btn-danger btn-color-black">Сформировать Сводный протокол</a>
                    </div>



                <? endif; ?>

                <? /*if ($item->getUniversity()->pfur_api):?>
                    <a class="btn btn-danger btn-color-black" target="_blank"
                       href="index.php?action=act_table_print&id=<?php echo $item->id; ?>">Просмотреть/напечатать
                        Сводную таблицу</a>
                    <div></div>
                <? endif; */?>

<? // какие условия ставить ?>
                <? if (!empty($item->summary_table_id)):?>
                    <a class="btn btn-danger  btn-color-black" target="_blank" id="summary_table_<?=$item->id?>"
                       href="index.php?action=act_summary_table&id=<?php echo $item->id; ?>">Просмотреть/напечатать Сводный протокол</a>
                    <div></div>
                <? endif;?>
                <? if (!empty($item->isActPrinted()) && !$item->file_act_id):?>
                    <a class="btn btn-danger  btn-color-black" target="_blank" id="act_print_<?=$item->id?>"
                       href="index.php?action=act_print_view&id=<?php echo $item->id; ?>">Просмотреть/напечатать Акт</a>
                    <div></div>
                <? endif;?>
                <? if (!empty($item->isActTablePrinted()) && !$item->file_act_tabl_id):?>
                <a class="btn btn-danger  btn-color-black" target="_blank" id="act_table_print_<?=$item->id?>"
                   href="index.php?action=act_table_print_view&id=<?php echo $item->id; ?>">Просмотреть/напечатать Сводную таблицу</a>
                <div></div>
                <? endif;?>

<? // конец блока условий ?>
                <a class="btn btn-info"
                   href="index.php?action=act_received_view&id=<?php echo $item->id; ?>">Карточка</a>
                <br>
                <a
                        class="btn btn-primary "
                        href="index.php?action=act_archive_numbers&id=<?php echo $item->id; ?>">Печать сертификатов
                    (справок)</a> <br>

                <? /*  <div class="btn-group">
                        <a
                            class="btn btn-primary  dropdown-toggle" data-toggle="dropdown"
                            href="#">Печать ведомости выдачи
                            сертификатов <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <?php foreach ($signings as $sign): ?>
                                <li><a target="_blank"
                                       href="index.php?action=act_vidacha_cert&id=<?php echo $item->id; ?>&type=<?= $sign->id ?>"><?= $sign->caption ?></a>
                                </li>
                            <?php endforeach; ?>
                            <ul>
                    </div>
                    <div></div>
					
					*/
                ?>

                <? if ($horg == 1 && $item->test_level_type_id == 2 && !empty($item->ved_vid_cert_num)): ?>
                    <div class="btn-group">

                        <a
                                class="btn btn-primary  dropdown-toggle  " data-toggle="dropdown"
                                href="#">Печать ведомости выдачи
                            сертификатов <span class="caret"></span></a>

                        <ul class="dropdown-menu">
                            <?php foreach ($signings as $sign): ?>
                                <li><a target="_blank"
                                       href="index.php?action=act_vidacha_cert_rudn&id=<?php echo $item->id; ?>&type=<?= $sign->id ?>"><?= $sign->caption ?></a>
                                </li>
                            <?php endforeach; ?>
                            <ul>
                    </div>
                    <div></div>
                <? else: ?>
                    <div class="btn-group">
                        <a
                                class="btn btn-primary  dropdown-toggle  " data-toggle="dropdown"
                                href="#">Печать ведомости выдачи
                            сертификатов <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <?php foreach ($signings as $sign): ?>
                                <li><a target="_blank"
                                       href="index.php?action=act_vidacha_cert&id=<?php echo $item->id; ?>&type=<?= $sign->id ?>"><?= $sign->caption ?></a>
                                </li>
                            <?php endforeach; ?>
                            <ul>
                    </div>
                    <div></div>
                <? endif; ?>


                <div class="btn-group">
                    <a
                            class="btn btn-primary  dropdown-toggle" data-toggle="dropdown"
                            href="#">Печать ведомости выдачи
                        справок <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <?php foreach ($signings as $sign): ?>
                            <li><a target="_blank"
                                   href="index.php?action=act_vidacha_note&id=<?php echo $item->id; ?>&type=<?= $sign->id ?>"><?= $sign->caption ?></a>
                            </li>
                        <?php endforeach; ?>
                        <ul>
                </div>
                <div></div>
                <div class="btn-group">
                    <a
                            class="btn btn-primary  dropdown-toggle" data-toggle="dropdown"
                            href="#">Печать реестра выдачи сертификатов <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <?php foreach ($signings as $sign): ?>
                            <li><a target="_blank"
                                   href="index.php?action=act_vidacha_reestr&id=<?php echo $item->id; ?>&type=<?= $sign->id ?>"><?= $sign->caption ?></a>
                            </li>
                        <?php endforeach; ?>
                        <ul>
                </div>


                <a data-id="<?php echo $item->id; ?>"
                   class="btn invoice btn-warning " target="_blank"
                   href="index.php?action=print_invoice&id=<?php echo $item->id; ?>">Печать счета</a>
                <?php if (strlen(
                        $item->invoice
                    ) && !$item->paid && empty($api_enabled)
                ): ?>
                    <div></div> <a
                            class="btn btn-success  payd  "
                            data-id="<?php echo $item->id; ?>"
                            data-money="<?php echo $item->amount_contributions; ?>" href="#">
                        Оплачено</a>
                <? endif; ?>
            </td>
        </tr>

    <?php endforeach; ?>
    </tbody>
</table>

<?php

echo $this->import('acts/act_table_popups', array('signings' => $signingsInvoice, 'university' => $univer));

$summary_table_confirm_text = 'Вы уверены?';
?>
<div class="modal hide fade" id="summary_table" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <form method="post"
          action="index.php?action=archive_summary_table_print" class="form-horizontal" target="_blank">
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
        <input type="hidden" value="" name="id" id="a_id">
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
            <button class="btn btn-primary save" type="submit"
                    onclick="if (!confirm('<?= $summary_table_confirm_text ?>')) return false;$('#summary_table').modal('hide');setTimeout(function() { window.location=window.location }, 1000);">
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

            $('#a_id').val(actId);
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
