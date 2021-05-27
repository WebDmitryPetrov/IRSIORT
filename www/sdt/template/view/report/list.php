<h1>Реестр актов тестовых сессий</h1>
<? $cl = function ($input) {
    $input = preg_replace('/^УРОВЕНЬ:/', '', $input);

    return $input;
};

$xls_button = '<input type="button" value="Экспортировать в Excel" class="btn btn-primary btn-large" onclick="$(\'#xls\').val(1);$(\'#list_form\').submit();">';
?>
<form class="form-inline" method="post" id="list_form"
      action="<?php echo $_SERVER['REQUEST_URI'] ?>">

    <input type="hidden" name="do" value="search">
    <input type="hidden" name="xls" value=0 id="xls">
    <table class="table">
        <tbody>
        <tr>
            <td style="vertical-align: middle">
                <div align="right">Дата акта (сводного протокола)</div>
            </td>

            <td>
                От:
                <div class="input-prepend date datepicker" id="div_add_from"
                     data-date-start-date="<?php echo $C->date($query['minActDate_db']) ?>"
                     data-date-end-date="<?php echo $C->date($query['maxActDate_db']) ?>"
                     data-date="<?php echo $C->date($query['minActDate']) ?>"
                >
                    <span class="add-on"><i class="icon-th"></i> </span> <input
                            class="input-small" name="minActDate" id="minActDate"
                            readonly="readonly" size="16" type="text"
                            value="<?php echo $C->date($query['minActDate']) ?>">
                </div>

                До:
                <div class="input-prepend date datepicker" id="div_add_to"
                     data-date-start-date="<?php echo $C->date($query['minActDate_db']) ?>"
                     data-date-end-date="<?php echo $C->date($query['maxActDate_db']) ?>"
                     data-date="<?php echo $C->date($query['maxActDate']) ?>"
                >
                    <span class="add-on"><i class="icon-th"></i> </span> <input
                            class="input-small" name="maxActDate" id="maxActDate"
                            readonly="readonly" size="16" type="text"
                            value="<?php echo $C->date($query['maxActDate']) ?>">
                </div>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: middle">
                <div align="right">Дата счета</div>
            </td>

            <td>
                От:
                <div class="input-prepend date datepicker" id="div_check_from"
                     data-date="<?php echo $C->date($query['minCheckDate']) ?>"

                     data-date-start-date="<?php echo $C->date($query['minCheckDate_db']) ?>"
                     data-date-end-date="<?php echo $C->date($query['maxCheckDate_db']) ?>"
                >
                    <span class="add-on"><i class="icon-th"></i> </span> <input
                            class="input-small" name="minCheckDate" id="minCheckDate"
                            readonly="readonly" size="16" type="text"
                            value="<?php echo $C->date($query['minCheckDate']) ?>">
                </div>

                До:
                <div class="input-prepend date datepicker" id="div_check_to"
                     data-date="<?php echo $C->date($query['maxCheckDate']) ?>"

                     data-date-start-date="<?php echo $C->date($query['minCheckDate_db']) ?>"
                     data-date-end-date="<?php echo $C->date($query['maxCheckDate_db']) ?>"

                >
                    <span class="add-on"><i class="icon-th"></i> </span> <input
                            class="input-small" name="maxCheckDate" id="maxCheckDate"
                            readonly="readonly" size="16" type="text"
                            value="<?php echo $C->date($query['maxCheckDate']) ?>">
                </div>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: middle">
                <div align="right">Дата тестовой сессии</div>
            </td>

            <td>
                От:
                <div class="input-prepend date datepicker" id="div_test_from"
                     data-date="<?php echo $C->date($query['minTestDate']) ?>"

                     data-date-start-date="<?php echo $C->date($query['minTestDate_db']) ?>"
                     data-date-end-date="<?php echo $C->date($query['maxTestDate_db']) ?>"

                >
                    <span class="add-on"><i class="icon-th"></i> </span> <input
                            class="input-small" name="minTestDate" id="minTestDate"
                            readonly="readonly" size="16" type="text"
                            value="<?php echo $C->date($query['minTestDate']) ?>">
                </div>

                До:
                <div class="input-prepend date datepicker" id="div_test_to"
                     data-date="<?php echo $C->date($query['maxTestDate']) ?>"

                     data-date-start-date="<?php echo $C->date($query['minTestDate_db']) ?>"
                     data-date-end-date="<?php echo $C->date($query['maxTestDate_db']) ?>"

                >
                    <span class="add-on"><i class="icon-th"></i> </span> <input
                            class="input-small" name="maxTestDate" id="maxTestDate"
                            readonly="readonly" size="16" type="text"
                            value="<?php echo $C->date($query['maxTestDate']) ?>">
                </div>
            </td>
        </tr>

        </tbody>
    </table>
    <br>
    <input type="submit" value="Фильтровать" class="btn btn-primary btn-large">
<?=$xls_button;?>
</form>
<? if ($list): ?>
<table cellspacing="0" cellpadding="0" class="table table-bordered  table-striped">

    <thead>
    <tr>
        <th>п/п</th>
        <th>id</th>
        <th>Номер сессии</th>
        <th>Дата договора</th>
        <th>Номер договора</th>
        <th>Наименование организации контрагента</th>
        <th>ИНН контрагента</th>
        <th>Дата акта (сводного протокола)</th>
        <th>Номер счета</th>
        <th>Дата счета</th>
        <th>Сумма счета</th>
        <th>Количество человек по уровням</th>
        <th>Количество человек с пересдачей</th>
        <th>Дата тестовой сессии</th>
        <th>Оплачено</th>
        <th>Номер платежного поручения</th>
        <th>Дата платежа</th>
        <th>Дата создания тестовой сессии</th>
        <th>Удалено</th>
        <? if (Roles::getInstance()->userHasRole(Roles::ROLE_CONTR_BUH)): ?>
            <th></th>
        <? endif ?>
    </tr>
    </thead>
    <tbody>
    <?php
    $i = 0;
    foreach ($list

             as $item):
    /** @var Act $item */
    $levels = ActTests::getCountsGroupedByLevel($item->id);

    ?>
    <tr class="<? if ($item->isDeleted()): ?>error<? endif ?>">
        <td><?= ++$i ?></td>
        <td><?= $item->id; ?></td>
        <td><?= $item->number; ?></td>
        <td><?php echo $C->date($item->getUniversityDogovor()->date); ?></td>
        <td><?php echo $item->getUniversityDogovor()->number ?></td>
        <td><?php echo $item->getUniversity()->getLegalInfo()['name_parent'] ?></td>
        <td><?php echo $item->getUniversity()->getLegalInfo()['inn'] ?></td>
        <td><?php echo $C->date($item->actDate(),true); ?></td>
        <td><?php echo $item->invoice?$item->invoice_index . '/' . $item->invoice:"" ?></td>
        <td><?php echo $C->date($item->invoice_date, true); ?></td>
        <td><?php echo $item->amount_contributions; ?></td>
        <td>

            <?
            $f = true;
            foreach ($levels as $l):
                if ($l['pf']):
                    if (!$f):?><br><?
                    else: $f = false; endif; ?>
                    <nobr><?= $cl($l['print']) ?>: <?= $l['pf'] ?></nobr>
                    <?
                endif;
            endforeach; ?>

        </td>
        <td>
            <?
            $f = true;
            foreach ($levels as $l):
                if ($l['pr']):
                    if (!$f):?><br><?
                    else: $f = false; endif; ?>
                    <nobr><?= $cl($l['print']) ?>: <?= $l['pr'] ?></nobr>
                    <?
                endif;
            endforeach; ?>

            <? if (Reexam_config::isShowInAct($item->test_level_type_id)): ?>
                <br><br>Из них бесплатно:<br>
                <?
                $f = true;
                foreach ($levels as $l):
                    if ($l['fpr']):
                        if (!$f):?><br><?
                    else: $f = false; endif; ?>
            <nobr><?= $cl($l['print']) ?>: <?= $l['fpr'] ?></nobr>
            <?
            endif;
            endforeach; ?>
            <? endif; ?>
        </td>
        <td><?php echo $C->date($item->testing_date); ?></td>
        <td><?php echo $item->paid ? 'Да' : 'Нет'; ?></td>
        <td><?php echo $item->platez_number ?></td>
        <td><?php echo $C->date($item->platez_date, true); ?></td>
        <td><?php echo $C->date($item->created, true) ?></td>
        <td><?php echo $item->deleted ? 'Удален' : '' ?></td>
        <? if (Roles::getInstance()->userHasRole(Roles::ROLE_CONTR_BUH)): ?>
            <td><a target="_blank" href="index.php?action=report_view_act&id=<?= $item->id ?>"
                   class="btn btn-primary btn-mini">Просмотреть</a></td>
        <? endif ?>
    </tr>

    <? endforeach ?>
    </tbody>
</table>
<? else: ?>
    <h3>Тестовых сессий не найдено</h3>
<? endif;

echo $xls_button;?>
