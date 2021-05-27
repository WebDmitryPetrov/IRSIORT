<?php
/**
 * Created by JetBrains PhpStorm.
 * User: LWR
 * Date: 22.07.15
 * Time: 13:09
 * To change this template use File | Settings | File Templates.
 */
/** @var DublAct $act */

/** check_date */
$start_date = null;
$current_date = date('Y-m-d');

$invoice_index = $act->invoice_index;
$invoice = $act->invoice;


?>
<div class="modal-body">
<span class="help-block">После подтверждения счет изменению не подлежит!
</span>

<form method="post" id="invoice_form" action=""
      class="form-horizontal">


    <input type="hidden" value="<?= $act->id ?>" name="id" id="act_id">

    <div class="control-group">
        <label class="control-label" for="invoice_index">Индекс подразделения</label>

        <div class="controls">
            <input class="input-medium" type="text" name="invoice_index"
                   id="invoice_index" value="<?= $invoice_index ?>">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="invoice">Номер счета</label>

        <div class="controls">
            <input class="input-medium" type="text" name="invoice"
                   id="invoice" value="<?= $invoice ?>">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="invoice_date">Дата счета</label>

        <div class="controls">

            <div class="input-prepend date datepicker"
                <?= $start_date ? 'data-date-start-date="' . $C->date($start_date) . '"' : '' ?>

                 data-date="<?php echo (!empty($act->invoice_date) && $act->invoice_date != '0000-00-00') ? $C->date($act->invoice_date) : $C->date($current_date); ?>"
            >
                <span class="add-on"><i class="icon-th"></i> </span> <input
                        class="input-medium" name="invoice_date" id="invoice_date"
                        readonly="readonly" size="16" type="text"
                        value="<?php echo (!empty($act->invoice_date) && $act->invoice_date != '0000-00-00') ? $C->date($act->invoice_date) : $C->date($current_date); ?>">
            </div>


        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="signing">Подпись</label>

        <div class="controls">
            <select name="signing" id="signing">
                <?php foreach ($signings as $sign): ?>
                    <option value="<?php echo $sign->id; ?>"
                        <?= (!empty($act->signing) && $act->signing == $sign->id) ? 'selected="selected"' : ''; ?>>
                        <?php echo $sign->caption; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="invoice_price">Цена за дубликат</label>

        <div class="controls">
            <input class="input-medium" type="text" name="invoice_price"
                   id="invoice_price"
                   value="<?= ($act->invoice_price) ? $act->invoice_price : sprintf('%01.2f', Dubl::DUBL_PRICE); ?>">
        </div>
    </div>
    <input type="submit" value="Отправить">
</form>
    </div>