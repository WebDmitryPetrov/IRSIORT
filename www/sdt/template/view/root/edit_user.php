<? //var_dump($users[309]->user_type_id);
/*$new_list=array();
foreach ($users as $user)
{
    $new_list[$user->user_type_id][]=$user;


}*/
//var_dump($new_list[1]);
?>
<style>
    .long_btn {
        width: 250px;
    }
</style>
<script>

    //��� ������� �������
    $(function () {
        var u_id;
        $('.adm').on('click', function () {
            var $this = $(this);
            var head = $this.data('head');
            var head_id = $this.data('head_id');
            var id = $this.data('id');
            var typecaption = $this.data('typecaption');
            u_id = $this.data('u_id');
            if (head > 0) var head_visible = '&head_visible=' + head;
            else var head_visible = '';
            if (head_id > 0) var head_num = '&head_id=' + head_id;
            else var head_num = '';

            $.ajax({
                url: 'index.php?action=user_edit_form' + head_visible + head_num,
                data: {
                    user_type_id: id,
                    u_id: u_id

                    // action: 'add_form',

                },
                type: 'GET',
                success: function (Response) {
                    //$(Response).dialog("close");
                    /*                if (Response == 'in_use') alert ('��� ������������ ��� ������');
                     else if (Response == 'empty_login') alert ('��� ������������ �� ������');
                     else if (Response == 'empty_password') alert ('������ �� �����');*/
                    //else {alert('������������ ������'); $( "#dialog-message" ).dialog( "close" );}
                    //alert(Response);
                    $("#dialog-message").html(Response);
//                            $(this).html(Response);

                },
                error: function () {
                    alert('������');
                }

            });


            $("#dialog-message").dialog("open");
//        var $this = $(this);
//        var id = $this.data('id');
            //alert(id);


            var asd = 1;

            //$('.d').on('click', function () {

            $("#dialog-message").dialog({
                close: function () {
                    $(this).dialog("destroy");
                },

                title: typecaption,
                width: 500,
                top: 100,
                modal: true,
                //autoOpen: false,
                buttons: {
                    �������: function () {

                        confirm("�� �������?") ? window.location.href = "index.php?action=user_delete_form&id=" + u_id : '';

                    },
                    ������: function () {
                        $(this).dialog("close");
                    },
                    ���������: function () {


                        $.ajax({
                            url: 'index.php?action=user_edit_form&u_id=' + u_id,
                            data: $("#edit_form").serialize(),
                            type: 'POST',
                            dataType: 'json',
                            success: function (Response) {
                                //$(Response).dialog("close");
                                if (Response == 'in_use') alert('��� ������������ ��� ������');
                                else if (Response == 'empty_login') alert('��� ������������ �� ������');
                                else if (Response == 'empty_password') alert('������ �� �����');
                                else if (Response.status == 'ok') {
                                    alert('������������ �������');
                                    $('[data-u_id=' + Response.user.id + ']').html(Response.user.name);
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

    $(function () {
        $('.ul_open').on('click', function () {
            // alert(123);
            $(".user_lists").hide();
            var $this = $(this);
            var num = $this.data('id');
            $("#user_list_" + num).show();
        });
    });


</script>


<script>

    //��� ������� �������
    $(function () {

        $('.adm_add').on('click', function () {
            var $this = $(this);
            var head = $this.data('head');
            var group_id = $this.data('group_id');

            var typecaption = $this.data('typecaption');
            if (head > 0) var head_visible = '&head_visible=' + head;
            else var head_visible = '';

            $.ajax({
                url: 'index.php?action=user_add_form' + head_visible,
                data: {
                    user_type_id: group_id


                },
                type: 'GET',
                success: function (Response) {
                    //$(Response).dialog("close");
                    /*                if (Response == 'in_use') alert ('��� ������������ ��� ������');
                     else if (Response == 'empty_login') alert ('��� ������������ �� ������');
                     else if (Response == 'empty_password') alert ('������ �� �����');*/
                    //else {alert('������������ ������'); $( "#dialog-message" ).dialog( "close" );}
                    //alert(Response);
                    $("#dialog-message_add").html(Response);
//                            $(this).html(Response);

                },
                error: function () {
                    alert('������');
                }

            });


            $("#dialog-message_add").dialog("open");


            $("#dialog-message_add").dialog({
                close: function () {
                    $(this).dialog("destroy");
                },
                title: typecaption,
                width: 500,
                top: 100,
                modal: true,

                buttons: {
                    ������: function () {
                        $(this).dialog("destroy");
                    },
                    ���������: function () {


                        $.ajax({
                            url: 'index.php?action=user_add_form',
                            data: $("#add_form").serializeArray(),

                            type: 'POST',
                            success: function (Response) {
                                //$(Response).dialog("close");
                                if (Response == 'in_use') alert('��� ������������ ��� ������');
                                else if (Response == 'empty_login') alert('��� ������������ �� ������');
                                else if (Response == 'empty_password') alert('������ �� �����');
                                else if (Response == 'ok') {
                                    alert('������������ ������');
                                    /*  var asd=$('#user_list_' +id).html();
                                     //alert(Response.user);
                                     if (!empty(Response.user.head_name)) var head_name=' - <strong>'+Response.user.head_name+'</strong>';
                                     else var head_name='';
                                     var add ='<li><a data-id="' + id + '" data-u_id="' + Response.user.id + '" data-head="1" data-head_id="1" class="bt n adm long_btn"> (' + Response.user.name + ')'+head_name+'</a></li>';

                                     var list=asd+add;

                                     $('#user_list_'+id).html(list);
                                     */

                                    $("#dialog-message_add").dialog("close");
                                    window.location.href = window.location.href;
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








<? //var_dump($list);die;

$result = '<ul style="margin-left: 0">';
foreach ($list as $type) {
    /** @var UserType $type */
    //var_dump($type->head_visible);

//echo $type->id

    if ($type->id == 2 && empty($_SESSION['privelegies']['admin_head'])) {
        continue;
    }
    if (in_array($type->id,Sdt_Config::getHiddenUserTypes())) {
        continue;
    }
    if ($type->id == 5 && empty($_SESSION['privelegies']['admin_head']) && empty($_SESSION['privelegies']['admin'])) {
        continue;
    }
    if (empty($new_list[$type->id])) {
        $disabled = 1;
        $disabled_echo = 'disabled="disabled"';

    } else {
        $disabled = 0;
        $disabled_echo = '';
    }

    $result .= '<li style="list-style-type: none"><a data-id="' . $type->id . '" class="btn ul_open long_btn" ' . $disabled_echo . ' >' . $type->caption . '</a><a data-group_id="' . $type->id . '" data-head="' . $type->head_visible . '" class="btn adm_add  btn-info" data-typecaption="' . $type->caption . '">��������</a>';

    if ($disabled == 1) {
        continue;
    }
    $result .= '<ul class="user_lists"  id="user_list_' . $type->id . '" style="display:none">';

    foreach ($new_list[$type->id] as $user) {


        if ($type->isHasRole(43)) {
            $local_center_button = '';
        } //elseif ($type->isHasRole(43)) $local_center_button=' - <strong>'.HeadCenter::getByID($user->head_id)->name.'</strong>';
        else {
            $local_center_button = '<a href="?action=user_list_edit&id=' . $user->u_id . '" class="btn">������ � ��������� �������</a>';
        }
//var_dump($user);

        $result .= '<li><a data-id="' . $type->id . '" data-u_id="' . $user->u_id . '" data-head="' . $type->head_visible . '" data-head_id="' . $user->head_id . '" class="bt n adm long_btn" data-typecaption="' . $type->caption . '">' . $user->shortName(
            ) . '</a>
        ' . $local_center_button . '
        </li>';
    }
    $result .= '</ul>';


}

//echo '</ul>';
echo $result . '</ul>';
?>


<div id="dialog-message" title="������������" style="display: none;top:100px"></div>
<div id="dialog-message_add" title="������������" style="display: none;top:100px"></div>

<h1 align="center">���� ������������� ������������ �������</h1>

<ol>
    <li><strong>Admin</strong><strong>_</strong><strong>center</strong> � ������������� ��������� ������. �����:</li>
    <ol>
        <li>���� ���������� � �������� ������:</li>
        <ol>
            <li>���� � �������������� ���������� ��� ������;</li>
            <li>���� � �������������� �������, ������������� ���������;</li>
            <li>���� � �������������� ������ ������ ��������� ������</li>
        </ol>
        <li>����������, �������������� � �������� ������������� ��������� ������;</li>
        <li>��������, �������� � �������������� ��������� ������� � �� ���������� (������, ������, �������� � ��.);</li>
        <li>����� ���������� � ���������� ������� �� ������ ��������� ������;</li>
        <li>������������� ��������������� ���������� �������� ������, ����������� � �������� ������.</li>
    </ol>
    <li><strong>Supervisor</strong> - ���� ������������ ���������� ����� ������� ���������� ��������� ������,
        ����������� � �����������:
    </li>
    <ol>
        <li>��������� �������� �������� ������, ���������� �� ��������� ������� ������������ (<strong>��
                ��������</strong>);
        </li>
        <li>������ ��� ���������� �������� ������ � �������� ������ � ������ ����� (<strong>����������</strong> � ������
            �����);
        </li>
        <li>������ ��� ���������� �������� ������ � �������� ������ � ���� ������ ����������� (�������) � (<strong>�
                ������</strong>);
        </li>

        <li>������ ��� ���������� �������� ������ � �������� ������ � ������ ������������ � ������� � ���� ������ ������
            ������������� ����������� (<strong>������</strong>);
        </li>
        <li>��������� ��� ���������� �������� ������ � �������� ������ � ���� ������ �������� � ������ ����� �������
            (<strong>���� ������</strong>);
        </li>
        <li>�������� ������ �������� ������ (<strong>�����</strong>)</li>
        <li>������������� ��������������� ���������� �������� ������, ����������� � �������� ������.</li>
        <li>�������� ������������ � ���������� ������ ��������� ������ (<strong>�����</strong>);</li>
    </ol>
    <li><strong>Check</strong><strong>_</strong><strong>doc</strong><strong> � </strong>��������� ��������� ������,�
        ���������� ������ ��������� �������� �������� ������, ���������� �� ��������� ������� ������������ (<strong>��
            ��������</strong>);<strong></strong></li>
    <li><strong>Get</strong><strong>_</strong><strong>invoice</strong><strong>_</strong><strong>doc</strong><strong>
            - </strong>��������� ��������� ������,� ���������� ������ ������ ����� (<strong>����������);</strong></li>

    <li><strong>Print</strong><strong>_</strong><strong>doc</strong><strong> - </strong>��������� ��������� ������,�
        ���������� ������ ������ ������������ � ������� � ���������� ������� �������  ������������
        (<strong>������</strong>);
    </li>
    <li><strong>�</strong><strong>Payment</strong><strong>_</strong><strong>doc</strong><strong> - </strong>���������
        ��������� ������,� ���������� ������ ����� ������ �������� � ������ ����� ������� (<strong>���� ������</strong>);
    </li>
    <li><strong>�</strong><strong>Archiev</strong><strong>_</strong><strong>doc</strong><strong> -</strong> ���������
        ��������� ������,� ���������� ������ ��������� ������ �������� ������ (<strong>�����</strong>)
    </li>
    <li>�<strong>Bookkeeper</strong><strong>_</strong><strong>doc</strong><strong> � </strong>��������� �����������,
        ���������� ������ ��������� ������������ � ���������� ������ ��������� ������ (<strong>�����</strong>);
    </li>
    <li>�<strong>Report</strong><strong>_</strong><strong>seach</strong><strong>_</strong><strong>doc</strong><strong>
            - </strong>��������� ��������� ������,� ���������� ������� ���������� ������� �� ��������� ������ � �������
        � ������ ���������� �������� ������ � ������� (<strong>������, �����)</strong>.
    </li>
</ol>
