<style>
    .long_btn{
        width:250px;
    }
</style>
<script>

    //��� ������� �������
    $(function() {
    $('.adm').on('click', function () {
        var $this = $(this);
        var head = $this.data('head');
        var id = $this.data('id');
        if (head > 0) var head_visible='&head_visible='+head;
        else var head_visible='';

        $.ajax({
            url: 'index.php?action=user_add_form_fms'+head_visible,
            data: {
                user_type_id: id

                // action: 'add_form',

            },
            type: 'GET',
            success: function(Response){
                //$(Response).dialog("close");
/*                if (Response == 'in_use') alert ('��� ������������ ��� ������');
                else if (Response == 'empty_login') alert ('��� ������������ �� ������');
                else if (Response == 'empty_password') alert ('������ �� �����');*/
                //else {alert('������������ ������'); $( "#dialog-message" ).dialog( "close" );}
                //alert(Response);
                $( "#dialog-message").html(Response);
//                            $(this).html(Response);

            },
            error: function(){alert('������');}

        });






        $( "#dialog-message" ).dialog( "open" );
//        var $this = $(this);
//        var id = $this.data('id');
        //alert(id);






    //$('.d').on('click', function () {

        $( "#dialog-message" ).dialog({
            width: 500,
            top: 100,
            modal: true,
            //autoOpen: false,
            buttons: {
                ������: function() {
                    $( this ).dialog( "close" );
                },
                ���������: function(){


                    $.ajax({
                        url: 'index.php?action=user_add_form_fms',
                        data: $("form").serializeArray(),

                        type: 'POST',
                        success: function(Response){
                            //$(Response).dialog("close");
                            if (Response == 'in_use') alert ('��� ������������ ��� ������');
                            else if (Response == 'empty_login') alert ('��� ������������ �� ������');
                            else if (Response == 'empty_password') alert ('������ �� �����');
                            else if (Response == 'ok'){ alert('������������ ������'); $( "#dialog-message" ).dialog( "close" );}
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

<? //var_dump($list);die;

echo '<ul>';
foreach ($list as $type) {
    //echo $type->id.'-'.$_SESSION['privelegies']['admin_head'].'<br>';
    if ($type->id==2 && !$_SESSION['privelegies']['admin_head']) continue;
    $result .= '<li><a data-id="'.$type->id.'" data-head="'.$type->head_visible.'" class="btn adm long_btn">'.$type->caption.'</a>';

}

echo '</ul>';
echo $result;
?>


<div id="dialog-message" title="������������" style="display: none;top:100px" ></div>

