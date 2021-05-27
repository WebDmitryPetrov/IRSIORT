<? /*<script>
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
            if (!confirm('������ ������ ����� ��������. �� �������?')) return false;
            $('#invoice_form').submit();
        });
    });
</script>

<div class="modal hide fade" id="invoice" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"
                aria-hidden="true">&times;</button>
        <h3 id="myModalLabel">������� ����� �����</h3>
    </div>
    <div class="modal-body">
          <span class="help-block">������� ������� ��������� ��������� ����� ������
</span>

        <form method="post" id="invoice_form" action="index.php?action=print_invoice"
              class="form-horizontal">


            <input type="hidden" value="" name="id" id="act_id">

            <div class="control-group">
                <label class="control-label" for="invoice_index">������ �������������</label>

                <div class="controls">
                    <input class="input-medium" type="text" name="invoice_index"
                           id="invoice_index" value="">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="invoice_number">����� �����</label>

                <div class="controls">
                    <input class="input-medium" type="text" name="invoice_number"
                           id="invoice_number" value="">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="invoice_date">���� �����</label>

                <div class="controls">
                    <div class="input-prepend date datepicker"
                         data-date="<?php echo $C->date(date('Y-m-d')) ?>"
                        >
                        <span class="add-on"><i class="icon-th"></i> </span> <input
                            class="input-medium" name="invoice_date" id="invoice_date"
                            readonly="readonly" size="16" type="text"
                            value="<?php echo $C->date(date('Y-m-d')) ?>">
                    </div>


                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="invoice_signing">�������</label>

                <div class="controls">
                    <select name="invoice_signing" id="invoice_signing">
                        <?php foreach ($signings as $sign): ?>
                            <option value="<?php echo $sign->id; ?>"><?php echo $sign->caption; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">�������</button>
        <button class="btn btn-primary save">�����������</button>
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
            if (!confirm('������ ������ ����� ��������. �� �������?')) return false;
            $('#payd_form').submit();
        });
    });
</script>
<div class="modal hide fade" id="payd" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"
                aria-hidden="true">&times;</button>
        <h3 id="myModalLabel">�������� ��������, ��� ����������</h3>
    </div>
    <div class="modal-body">
        <span class="help-block">������� ������� ��������� ��������� ����� ������</span>

        <form method="post" id="payd_form" action="index.php?action=act_set_payed"
              class="form-horizontal">
            <legend>������� ��������� ���������� ���������</legend>

            <input type="hidden" value="" name="id" id="platez_id">

            <div class="control-group">
                <label class="control-label" for="platez_number">����� ����������
                    ���������</label>

                <div class="controls">
                    <input class="input-medium" type="text" name="platez_number"
                           id="platez_number" value="">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="platez_date">���� �������</label>

                <div class="controls">
                    <div class="input-prepend date datepicker" id="div_platez_date"
                         data-date="<?php echo $C->date(date('Y-m-d')) ?>"
                        >
                        <span class="add-on"><i class="icon-th"></i> </span> <input
                            class="input-medium" name="platez_date" id="platez_date"
                            readonly="readonly" size="16" type="text"
                            value="<?php echo $C->date(date('Y-m-d')) ?>">
                    </div>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="platez_sum">�������� ����� �������</label>

                <div class="controls">
                    <input class="input-medium" type="text" name="platez_sum"
                           id="platez_sum" value="" readonly="readonly"> <span
                        class="help-block">������� ��������� �����, � ��� ������� ��������
						� ��������� ���������</span>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">�������</button>
        <button class="btn btn-primary save">�����������</button>
    </div>
</div>

<script>
    $(function () {
        $('.js-act-invalid-button').on('click', function (E) {
            E.preventDefault();
            var $this = $(this);
            var manId = $this.data('id');
            var row = $this.closest('.js-act-row');
            var blank = row.find('.js-act-number').text();
            var name = row.find('.js-act-date').text();


            var modalMan = $('.js-act-invalid-modal');
            modalMan.find('.js-blank').text(blank);
            modalMan.find('.js-name').text(name);
            modalMan.find('.js-reason').val('');
            modalMan.find('.js-accept').off('click');
            modalMan.find('.js-accept').on('click', function (E) {
                var reason = $('.js-reason').val();
                if (reason.length < 10) {
                    alert('������� ������ ���� ������ 10 ��������');
                    return;
                }

                $.ajax({
                    type: 'POST',
                    url: './index.php?action=sess_invalid',
                    data: {
                        id: manId,
                        reason: reason
                    }
                }).always(function () {
                        location.reload();
                    }
                );

//                $(this).off(E);
            });

            modalMan.modal();


        })
        ;
    })
    ;
</script>

<div class="js-act-invalid-modal modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>������� ���������������� ���</h3>
    </div>
    <div class="modal-body">
        <p>�� ��������� ������� ���������������� ��� <span class="js-blank"></span>
            �� <span class="js-name"></span>.</p>

        <p>
            <strong>��� ������� ������ ������������ ��������� ��� ����������������</strong>
        </p>

        <p><label style=" padding-right: 15px;">������� �������:<br>
                <textarea style="width: 100%" class="js-reason"
                          placeholder="��������: ������������ ������"></textarea></label></p>
    </div>
    <div class="modal-footer">
        <a class="btn" data-dismiss="modal">��������</a>
        <a class="btn btn-primary js-accept">�����������</a>
    </div>
</div>

*/?>
<script>
    $(function () {
        $('.js-act-rework-button').on('click', function (E) {
            E.preventDefault();
            var $this = $(this);
            var manId = $this.data('id');
            var row = $this.closest('.js-act-row');
            var blank = row.find('.js-act-number').text();
            var name = row.find('.js-act-date').text();


            var modalManRework = $('.js-act-rework-modal');
            modalManRework.find('.js-blank').text(blank);
            modalManRework.find('.js-name').text(name);
            modalManRework.find('.js-reason-rework').val('');
            modalManRework.find('.js-accept-rework').off('click');
            modalManRework.find('.js-accept-rework').on('click', function (E) {
                var reason_rework = $('.js-reason-rework').val();
                if (reason_rework.length < 10) {
                    alert('������� ������ ���� ������ 10 ��������');
                    return;
                }

                $.ajax({
                    type: 'POST',
                    url: './dubl.php?action=dubl_act_decline',
                    data: {
                        id: manId,
                        reason: reason_rework
                    }
                }).always(function () {

                        location.reload();
                    }
                );

//                $(this).off(E);
            });

            modalManRework.modal();


        })
        ;
    })
    ;
</script>

<div class="js-act-rework-modal modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>��������� ��� ������� � ������</h3>
    </div>
    <div class="modal-body">
        <p>��������� ������� � ������ ��� <span class="js-blank"></span>
            �� <span class="js-name"></span>.</p>

<!--        <p>
            <strong>��� ������� ������ ������������ ��������� ��� ����������������</strong>
        </p>-->

        <p><label style=" padding-right: 15px;">������� ������� (�����������):<br>
                <textarea style="width: 100%" class="js-reason-rework"
                          placeholder="��������: ������������ ������"></textarea></label></p>
    </div>
    <div class="modal-footer">
        <a class="btn" data-dismiss="modal">��������</a>
        <a class="btn btn-primary js-accept-rework">�����������</a>
    </div>
</div>

