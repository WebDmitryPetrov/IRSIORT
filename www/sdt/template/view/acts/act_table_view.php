<?php
/** @var Act $item */
$signings = ActSignings::get4VidachaCert();
$meta = $item->getMeta();

$status = array();
$status['setblanks'] = $item->isSetBlanks() ? 1 : 0;
$status['setinvoice'] = $item->isSetInvoice() ? 1 : 0;
$status['paid'] = $item->isPaid() ? 1 : 0;

$api_enabled = $item->getUniversity()->api_enabled;
$head_org_id = $item->getUniversity()->getHeadCenter()->id;
$horg = $item->getUniversity()->getHeadCenter()->horg_id;

?>
<tr class="js-act-row
	<?
if ($item->is_printed):?>success<?
endif;
if ($meta->test_group == 2) {
    echo 'warning';
}
echo ($status['setblanks'] == 1) ? ' blanks_on ' : ' blanks_off ';
echo ($status['setinvoice'] == 1) ? ' invoice_on ' : ' invoice_off ';
echo ($status['paid'] == 1) ? ' paid_on ' : ' paid_off ';
?>
	">
    <td><a name="<?= $item->id ?>"></a><span class="js-act-number"><?= $item->number ?></span></td>
    <td class="js-act-date"><?= $C->date($item->actDate()) ?></td>


    <td><?php echo $C->date($item->testing_date) ?>    </td>

    <td><?php
        if (!is_null($item->date_received) && $item->date_received != '0000-00-00 00:00:00') {
            echo $C->dateTime($item->date_received);
        } ?>    </td>

    <? if (Session::getUserTypeID() != 10): ?>
    <td>   <?php if (strlen($item->invoice)): echo $item->invoice_index . '/' . $item->invoice ?>
            <br><?php echo $C->date($item->invoice_date) . '<br>'; endif; ?>
        <?php echo '<strong>' . $item->amount_contributions . '</strong>'; ?>
        <? endif; ?>
    </td>
    <td><?php echo $status['setblanks'] ? 'Да' : 'Нет'; ?></td>
    <td><?php echo $status['setinvoice'] ? 'Да' : 'Нет'; ?></td>
    <td><?php echo $status['paid'] ? 'Да' : 'Нет'; ?></td>

    <?
    /*
    <td><?php echo $item->paid ? 'Да' : 'Нет'; ?></td>
    <td class="wrap text-success"><?php echo $item->comment ?></td>
    */
    ?>
    <td class="">
        <? if (Session::getUserTypeID() != 10): ?>
            <? if ($item->allow_summary_table() && $item->getUniversityDogovor()->isPrintProtocol()):
                $show_hide = '';
                if (!$item->summary_table_id):?>

                    <a class="summary_table btn invoice btn-danger  btn-block p rint-all" data-id="<?= $item->id; ?>"
                       id="summary_table_button_<?= $item->id ?>">Сформировать Сводный протокол</a>
                    <div></div>
                    <?
                    $show_hide = ' style="display:none;" ';
                endif; ?>
                <a class="btn btn-danger  btn-block btn-color-black" target="_blank" <?= $show_hide ?>
                   id="summary_table_<?= $item->id ?>"
                   href="index.php?action=act_summary_table&id=<?php echo $item->id; ?>">Просмотреть/напечатать Сводный
                    протокол</a>
                <div></div>
            <? endif; ?>


            <? if ($item->getUniversityDogovor()->isPrintAct() && !$item->file_act_id):
                $show_hide = '';
                if (!$item->isActPrinted()):?>

                    <a class="act_print btn invoice btn-danger  btn-block pr int-all" data-id="<?= $item->id; ?>"
                       id="act_print_button_<?= $item->id ?>">Сформировать Акт</a>
                    <div></div>
                    <?
                    $show_hide = ' style="display:none;" ';
                endif; ?>
                <a class="btn btn-danger  btn-block btn-color-black" target="_blank" <?= $show_hide ?>
                   id="act_print_<?= $item->id ?>"
                   href="index.php?action=act_print_view&id=<?php echo $item->id; ?>">Просмотреть/напечатать Акт</a>
                <div></div>

            <? endif; ?>
        <? endif; ?>

        <? $show_hide = '';
        if (!$item->isActTablePrinted()):?>
            <a class="act_table_print btn invoice btn-danger  btn-block p rint-all" data-id="<?= $item->id; ?>"
               id="act_table_print_button_<?= $item->id ?>">Сформировать Сводную таблицу</a>
            <div></div>
            <?
            $show_hide = ' style="display:none;" ';
        endif;
        if (!$item->file_act_tabl_id):
            ?>
            <a class="btn btn-danger  btn-block btn-color-black" target="_blank" <?= $show_hide ?>
               id="act_table_print_<?= $item->id ?>"
               href="index.php?action=act_table_print_view&id=<?php echo $item->id; ?>">Просмотреть/напечатать
                Сводную
                таблицу</a>
        <? endif; ?>
        <div></div>


        <? /*if ($item->summary_table_id && $item->allow_summary_table()):*/ ?><!--
        <a class="btn btn-danger  btn-block btn-color-black" target="_blank"
           href="index.php?action=act_summary_table&id=<?php /*echo $item->id; */ ?>">Просмотреть/напечатать Сводный протокол</a>
        <div></div>
        <? /*endif;*/ ?>

        <? /*if ($item->isActPrinted()):*/ ?>
        <a class="btn btn-danger  btn-block btn-color-black" target="_blank"
           href="index.php?action=act_print_view&id=<?php /*echo $item->id; */ ?>">Просмотреть/напечатать Акт</a>
        <div></div>
        <? /*endif;*/ ?>

        <? /*if ($item->isActTablePrinted()):*/ ?>
        <a class="btn btn-danger  btn-block btn-color-black" target="_blank"
           href="index.php?action=act_table_print_view&id=<?php /*echo $item->id; */ ?>">Просмотреть/напечатать Сводную таблицу</a>
        <div></div>
        --><? /*endif;*/ ?>


        <? /*if ($item->getUniversity()->pfur_api):?>
        <a class="btn btn-danger  btn-block btn-color-black" target="_blank"
           href="index.php?action=act_table_print&id=<?php echo $item->id; ?>">Просмотреть/напечатать Сводную таблицу</a>
        <div></div>
        <?endif;*/ ?>


        <?php if (!$item->isBlocked() || $item->isCanEdit()): ?>
            <? if (Session::getUserTypeID() != 10): ?>
                <a class="btn btn-info  btn-block"
                   href="index.php?action=act_received_view&id=<?php echo $item->id; ?>">Карточка</a>
                <div></div>
            <? endif; ?>
            <?
            if (!$item->isAllPrinted()) $print_docs_hide = ' style="display:none" ';
            else $print_docs_hide = '';
            ?>
            <?php if ($C->userHasRole(Roles::ROLE_CENTER_PRINT) || $C->userHasRole(
                    Roles::ROLE_CENTER_FOR_PRINT
                ) || $C->userHasRole(Roles::ROLE_CERTIFICATE_MANAGER)
            ): ?>
                <a
                        class="btn btn-primary btn-block print_docs_<?= $item->id; ?>"
                    <?= $print_docs_hide ?>
                        href="index.php?action=act_receive_numbers&id=<?php echo $item->id; ?>">Печать сертификатов
                    (справок)</a>
                <div></div>
            <? endif; ?>
            <?php if ($C->userHasRole(Roles::ROLE_CENTER_PRINT)): ?>


                <? if ($horg == 1 && $item->test_level_type_id == 2 && $item->state != $item::STATE_ARCHIVE): ?>
                    <div class="btn-group print_docs_<?= $item->id; ?>"
                        <?= $print_docs_hide ?>>
                        <? if (!empty($item->ved_vid_cert_num) || $item->checkBlanksNums()): ?>
                            <a
                                    class="btn btn-primary  dropdown-toggle  btn-block" data-toggle="dropdown"
                                    href="#">Печать ведомости выдачи
                                сертификатов <span class="caret"></span></a>
                        <? else : ?>
                            <a
                                    class="btn btn-primary disabled  dropdown-toggle  btn-block"
                                    data-toggle="dropdown"
                            >Печать ведомости выдачи
                                сертификатов <span class="caret"></span></a>
                        <? endif; ?>
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
                    <div class="btn-group print_docs_<?= $item->id; ?>"
                        <?= $print_docs_hide ?>>
                        <a
                                class="btn btn-primary  dropdown-toggle  btn-block" data-toggle="dropdown"
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

                <div class="btn-group print_docs_<?= $item->id; ?>"
                    <?= $print_docs_hide ?>>
                    <a class="btn btn-primary
                     dropdown-toggle  btn-block" data-toggle="dropdown"
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
                <div class="btn-group print_docs_<?= $item->id; ?>"
                    <?= $print_docs_hide ?>>
                    <a
                            class="btn btn-primary  dropdown-toggle  btn-block" data-toggle="dropdown"
                            href="#">Печать реестра выдачи сертификатов <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <?php foreach ($signings as $sign): ?>
                            <li><a target="_blank"
                                   href="index.php?action=act_vidacha_reestr&id=<?php echo $item->id; ?>&type=<?= $sign->id ?>"><?= $sign->caption ?></a>
                            </li>
                        <?php endforeach; ?>
                        <ul>
                </div>

            <? endif ?>

            <?php if ($C->userHasRole(Roles::ROLE_CENTER_RECEIVED) && !strlen(
                    $item->invoice
                ) && empty($api_enabled)
            ):
                $checkDate = null;
                if ($item->getUniversity()->getHeadCenter()->horg_id == 1) {
                    $checkDate = $item->check_date;
                }
                //                $checkDate = null;
                ?>

                <div></div>
                <a data-id="<?php echo $item->id; ?>"
                   data-date="<?= $item->getPrintDateAfterCheckDate() ?>"
                   data-level_id="<?= $item->test_level_type_id ?>"
                   data-check_date="<?= $checkDate ?>"
                   class="btn invoice btn-warning new  btn-block print_docs_<?= $item->id; ?>"
                    <?= $print_docs_hide ?>
                   href="#">Печать счета</a>
            <?php
            endif;
            if ($C->userHasRole(Roles::ROLE_CENTER_RECEIVED) && strlen($item->invoice) && empty($api_enabled)): ?>
                <div></div>
                <a data-id="<?php echo $item->id; ?>"
                   class="btn invoice btn-warning  btn-block print_docs_<?= $item->id; ?>"
                    <?= $print_docs_hide ?> target="_blank"
                   href="index.php?action=print_invoice&id=<?php echo $item->id; ?>">Печать счета</a>
            <?php endif; ?>


            <?php if ($C->userHasRole(Roles::ROLE_CENTER_WAIT_PAYMENT) && strlen(
                    $item->invoice
                ) && !$item->paid && empty($api_enabled)
            ): ?>
                <div></div>
                <a
                        class="btn btn-success  payd  btn-block print_docs_<?= $item->id; ?>"
                    <?= $print_docs_hide ?>
                        data-id="<?php echo $item->id; ?>"
                        data-money="<?php echo $item->amount_contributions; ?>" href="#">
                    Оплачено</a>
            <? endif; ?>

            <?php if ($C->userHasRole(
                Roles::ROLE_SUPERVISOR
            )
            ): //сделал так, потому что эта роль есть только у супервизора

                //if (!$item->isToArchive())
                if (
                !$item->isToArchive()
                ) {
                    $b_class = "disabled";
                    $b_href = "onclick=\"return confirm('Недоступно! Обработка документа не завершена');\">";
                } else {
                    $b_class = "";
                    $b_href = "onclick=\"return confirm('Вы уверены?');\"
                    href=\"index.php?action=set_archive&id=$item->id\">";
                }


                ?>
                <div></div>
                <a
                        class="btn btn-danger  btn-block <?= $b_class; ?> print_docs_<?= $item->id; ?>"
                    <?= $print_docs_hide ?> <?= $b_href; ?>
                        В архив</a>

                <?
                /* КНОПКА ВЕРНУТЬ В РАБОТУ */
                /*if ($item->isBlanksEmpty() && !$status['paid'] && !$status['setinvoice']) { ?>
                    <div></div>
                    <button data-id="<?= $item->id ?>"
                            class="btn btn-danger js-act-rework-button btn-block">


                        Возврат в проверенные
                    </button>
                <? }*/

                ?>


            <? endif; ?>


            <? if ($item->isBlocked()): ?>
                <div></div>
                <a
                        class="btn btn-danger  btn-block"
                        href="index.php?action=act_set_unblocked&id=<?php echo $item->id; ?>">
                    Разблокировать</a>
            <? else: ?>
                <div></div>
                <a
                        class="btn btn-danger  btn-block"
                        href="index.php?action=act_set_blocked&id=<?php echo $item->id; ?>">
                    Заблокировать</a>
            <? endif; ?>

            <?php if ($C->userHasRole(Roles::ROLE_ACT_INVALID)): ?>
                <div></div>
                <button data-id="<?= $item->id ?>"
                        class="btn btn-inverse js-act-invalid-button btn-block"
                >
                    Недействительно
                </button>
            <? endif; ?>
        <? else: ?>
            <? if ($item->isCanUnBlock()): ?>
                <div></div>
                <a
                        class="btn btn-danger  btn-block"
                        href="index.php?action=act_set_unblocked&id=<?php echo $item->id; ?>">
                    Разблокировать</a>
            <? else: ?>
                <div></div>
                <a
                        class="btn disabled">

                    Акт заблокирован</a>
            <? endif; ?>
        <? endif; ?>
    </td>
</tr>