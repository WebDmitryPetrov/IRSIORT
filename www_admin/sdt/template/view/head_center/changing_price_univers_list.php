<style>
    .ui-dialog
    {
        z-index: 10000 !important;
    }
</style>
<script>

    //��� ������� �������
    $(function() {
        $('.adm').on('click', function () {
           // alert(123);
            var $this = $(this);

            var id = $this.data('id');

            $.ajax({

                url: 'index.php?action=test_level_price_edit',
                data: {
                    univer_id: id

                    // action: 'add_form',

                },
                type: 'GET',
                success: function(Response){
                    $( "#dialog-message").html(Response);

                },
                error: function(){alert('������');}

            });






            $( "#dialog-message" ).dialog( "open" );









            $( "#dialog-message" ).dialog({
                width: 800,
                top: 100,
                modal: true,
                //autoOpen: false,
                buttons: {
                    ������: function() {
                        $( this ).dialog( "close" );
                    },
                    ���������: function(){


                        $.ajax({
                            url: 'index.php?action=save_test_level_price&univer_id='+id,
                            data: $("form").serializeArray(),

                            type: 'POST',
                            success: function(Response){
                                //$(Response).dialog("close");
                                /*if (Response == 'in_use') alert ('��� ������������ ��� ������');
                                else if (Response == 'empty_login') alert ('��� ������������ �� ������');
                                else if (Response == 'empty_password') alert ('������ �� �����');
                                else */if (Response == 'ok'){ alert('��������� ���������'); $( "#dialog-message" ).dialog( "close" );}
                                else {alert(Response);}
                                // alert(Response);
//                            $(this).html(Response);

                            },
                            error: function(){alert('������');}

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
$result='<ul>';
foreach ($list as $item)
{
    $result .= '<li><a data-id="'.$item['id'].'" class="btn adm long_btn">'.$item['name'].'</a>';

}
echo $result.'</ul>';
?>
<div id="dialog-message" title="������ ������������" style="display: none; top:100px;" ></div>