<style>
    .ui-dialog {
        z-index: 10000 !important;
    }
</style>
<script>

    //��� ������� �������
    $(function () {
        var center_id;
        $('.btn.adm').on('click', function (E) {
            // alert(123);
            E.preventDefault();
            var $this = $(this);

            center_id = $this.data('id');

            $.ajax({

                url: 'index.php?action=test_level_price_edit',
                data: {
                    univer_id: center_id

                    // action: 'add_form',

                },
                type: 'GET',
                success: function (Response) {
                    $("#dialog-message").html(Response);

                },
                error: function () {
                    alert('������');
                }

            });


            $("#dialog-message").dialog("open");


            $("#dialog-message").dialog({
                width: 900,
                top: 100,
                modal: true,
                //autoOpen: false,
                buttons: {
                    ������: function () {
                        $(this).dialog("close");
                    },
                    ���������: function () {

//alert(center_id); return;
                        $.ajax({
                            url: 'index.php?action=save_test_level_price&univer_id=' + center_id,
                            data: $("form").serializeArray(),

                            type: 'POST',
                            success: function (Response) {
                                //$(Response).dialog("close");
                                /*if (Response == 'in_use') alert ('��� ������������ ��� ������');
                                 else if (Response == 'empty_login') alert ('��� ������������ �� ������');
                                 else if (Response == 'empty_password') alert ('������ �� �����');
                                 else */
                                if (Response == 'ok') {
                                    alert('��������� ���������');
                                    $("#dialog-message").dialog("close");
                                }
                                else {
                                    alert(Response);
                                }
                                // alert(Response);
//                            $(this).html(Response);

                            },
                            error: function () {
                                alert('������');
                            }

                        });
                    }
                }
            });
        });
    });


</script>




<?php
/**
 * Created by JetBrains PhpStorm.
 * User: LWR
 * Date: 01.07.14
 * Time: 11:22
 * To change this template use File | Settings | File Templates.
 */
if (empty($list)) {
    $result = '<h2 style="color:red">�������� ��������� ������ (� ������� "����"), � ������� ���������� �������� ����</h1>';
} else {
    $result = '<ul>';

    foreach ($list as $item) {
        $result .= '<li><a data-id="' . $item['id'] . '" class="btn adm long_btn">' . $item['name'] . '</a>';
    }
    $result .= '</ul>';
}
echo $result
?>
<div id="dialog-message" title="������ ������������" style="display: none; top:100px;"></div>