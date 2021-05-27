<script type="text/javascript">
    (function ($) {
        $(function () {
            $('.cert-container ol').autocolumnlist({
                columns: 4,
                classname: 'span3',
                min: 3
            });

            $('.remove').on('click', function (E) {
                E.preventDefault();
                var $this = $(this);
                /*if (!confirm('�� �������, ��� ������ ������� ����� ���������� \n' +
                    '� ' + $this.data('number') + '')) {

                    return false;
                }
*/
                var blank = $this.data('number');
                var name = '<?= $type->caption ?>';

                var modalMan = $('.js-man-invalid-modal');

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
                        url: './?action=certificate_delete',
                        data: {
                            id: $this.data('id'),
                            reason: reason
                        },
                        type: 'post',
                        dataType: 'json',
                        success: function (Response) {
                            if (Response.ok) {
                                $this.closest('li').remove();
                                return;
                            }
                            if (Response.error != undefined) {
                                alert(Response.error);
                            }
                            else {
                                alert('��������� ������');
                            }

                        },
                        error: function () {
                            alert('��������� ������');
                        },
                        complete: function () {
                            modalMan.modal('hide');
                        }
                    });


                });

                modalMan.modal();

            });
        });
    })(jQuery)
</script>
<h2>������ ��������� ������������ ��� "<?= $type->caption ?>"</h2>
<div class="text-info">�����: <?= count($items) ?></div>


<div class="cert-container good" style="margin-left: 15px; margin-bottom: 30px;">
    <div class="row">
<!--        <pre>-->
        <ol>
            <?
            foreach ($items as $item):
            /** @var CertificateReserved $item */
//            var_dump($item);
//                var_dump($item->getValue('bt_id'), !$item->getValue('bt_default'));
            ?>

                <li><?= $item->number ?>
                    <? if($item->getValue('bt_id') && !$item->getValue('bt_default')):?>
                        - <span class="text-warning"><?=$item->getValue('bt_name')?></span>
                    <?endif?>

                    <button class="remove btn btn-danger btn-mini" data-number="<?= $item->number ?>"
                            data-id="<?= $item->id ?>"><i class="icon-remove icon-white"></i></button>

                </li>
            <? endforeach ?>

        </ol>
    </div>
</div>



<div class="js-man-invalid-modal modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>������� ���������������� ����� �����������</h3>
    </div>
    <div class="modal-body">
        <p>�� ��������� ������� ���������������� ����� ����������� <span class="js-blank" style="font-weight: bold; color:red"></span>
            ��� "<span class="js-name"></span>"</p>

        <p><label style=" padding-right: 15px;">������� �������:<br>
                <textarea style="width: 100%" class="js-reason"
                          placeholder="��������: �������� �����"></textarea></label></p>
    </div>
    <div class="modal-footer">
        <a class="btn" data-dismiss="modal">��������</a>
        <a class="btn btn-primary js-accept">�����������</a>
    </div>
</div>
