<script>
$(function () {
        //	   $('.datepicker').datepicker();
        $('.invoice.new').on('click', function (e) {
                e.preventDefault();
                //$('#payd').data('id',$(this).data('id'));
                $('#invoice').find('#act_id').val($(this).data('id'));

                $('#invoice').modal();

            });
        $('#invoice .save').on('click', function (e) {
                e.preventDefault();
                if (!confirm('Данные нельзя будет изменить. Вы уверены?')) return false;
                $('#invoice_form').submit();
            });
    });
</script>

<div class="modal hide fade" id="invoice" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"
                aria-hidden="true">&times;</button>
        <h3 id="myModalLabel">Введите номер счета</h3>
    </div>
    <div class="modal-body">
          <span class="help-block">Вернуть прежнее состояние документа будет нельзя
</span>

        <form method="post" id="invoice_form" action="index.php?action=print_invoice"
              class="form-horizontal">


            <input type="hidden" value="" name="id" id="act_id">

            <div class="control-group">
                <label class="control-label" for="invoice_index">Индекс подразделения</label>

                <div class="controls">
                    <input class="input-medium" type="text" name="invoice_index"
                           id="invoice_index" value="24">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="invoice_number">Номер счета</label>

                <div class="controls">
                    <input class="input-medium" type="text" name="invoice_number"
                           id="invoice_number" value="">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="invoice_date">Дата счета</label>

                <div class="controls">
                    <div class="input-prepend date datepicker"
                         data-date="<?php echo $C->date(date('Y-m-d'))?>"
                        >
                        <span class="add-on"><i class="icon-th"></i> </span> <input
                            class="input-medium" name="invoice_date" id="invoice_date"
                            readonly="readonly" size="16" type="text"
                            value="<?php echo $C->date(date('Y-m-d'))?>">
                    </div>

              
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="invoice_signing">Подпись</label>
                <div class="controls">
                    <select name="invoice_signing" id="invoice_signing">
                        <?php foreach ($signings as $sign):?>
    <option value="<?php echo $sign->id; ?>"><?php echo $sign->caption; ?></option>
<?php endforeach; ?>
</select>
</div>
</div>
</form>
</div>
<div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
    <button class="btn btn-primary save">Подтвердить</button>
</div>
</div>

<script>
    $(function () {
        //	   $('.datepicker').datepicker();
        $('.payd').on('click', function (e) {
            e.preventDefault();
            //$('#payd').data('id',$(this).data('id'));
            $('#payd').find('#platez_id').val($(this).data('id'));
            $('#payd').find('#platez_sum').val($(this).data('money'));
            $('#payd').modal();

        });

        $('#payd .save').on('click', function (e) {
            e.preventDefault();
            if (!confirm('Данные нельзя будет изменить. Вы уверены?')) return false;
            $('#payd_form').submit();
        });
    });
</script>
<div class="modal hide fade" id="payd" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"
                aria-hidden="true">&times;</button>
        <h3 id="myModalLabel">Пометить документ, как оплаченный</h3>
    </div>
    <div class="modal-body">
        <span class="help-block">Вернуть прежнее состояние документа будет нельзя</span>

        <form method="post" id="payd_form" action="index.php?action=act_set_payed"
              class="form-horizontal">
            <legend>Введите параметры платежного поручения</legend>

            <input type="hidden" value="" name="id" id="platez_id">

            <div class="control-group">
                <label class="control-label" for="platez_number">Номер платежного
                    поручения</label>

                <div class="controls">
                    <input class="input-medium" type="text" name="platez_number"
                           id="platez_number" value="">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="platez_date">Дата платежа</label>

                <div class="controls">
                    <div class="input-prepend date datepicker" id="div_platez_date"
                         data-date="<?php echo $C->date(date('Y-m-d'))?>"
                        >
                        <span class="add-on"><i class="icon-th"></i> </span> <input
                            class="input-medium" name="platez_date" id="platez_date"
                            readonly="readonly" size="16" type="text"
                            value="<?php echo $C->date(date('Y-m-d'))?>">
                    </div>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="platez_sum">Проверка суммы платежа</label>

                <div class="controls">
                    <input class="input-medium" type="text" name="platez_sum"
                           id="platez_sum" value="" readonly="readonly"> <span
                        class="help-block">Сверьте указанную сумму, с той которая написана
						в платежном поручении</span>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
        <button class="btn btn-primary save">Подтвердить</button>
    </div>
</div>