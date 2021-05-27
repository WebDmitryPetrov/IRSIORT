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

    //Для главных админов
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
                url: 'index.php?action=user_edit_form_fms' + head_visible + head_num,
                data: {
                    user_type_id: id,
                    u_id: u_id

                    // action: 'add_form',

                },
                type: 'GET',
                success: function (Response) {
                    //$(Response).dialog("close");
                    /*                if (Response == 'in_use') alert ('Имя пользователя уже занято');
                     else if (Response == 'empty_login') alert ('Имя пользователя не задано');
                     else if (Response == 'empty_password') alert ('Пароль не задан');*/
                    //else {alert('Пользователь создан'); $( "#dialog-message" ).dialog( "close" );}
                    //alert(Response);
                    $("#dialog-message").html(Response);
//                            $(this).html(Response);

                },
                error: function () {
                    alert('Ошибка');
                }

            });


            $("#dialog-message").dialog("open");
//        var $this = $(this);
//        var id = $this.data('id');
            //alert(id);


            var asd = 1;

            //$('.d').on('click', function () {

            $("#dialog-message").dialog({
                close:function () {
                    $(this).dialog("destroy");
                },
                title: typecaption,
                width: 500,
                top: 100,
                modal: true,

                //autoOpen: false,
                buttons: {
                    Удалить: function () {
                        if (u_id == '<?=MAINEST_ADMIN_ID?>') alert('Удалить главного администратора невозможно');
                        else {
                            return confirm("Вы уверены?") ? window.location.href = "index.php?action=user_delete_form_fms&id=" + u_id : '';
                        }
                    },
                    Отмена: function () {
                        $(this).dialog("destroy");
                    },
                    Сохранить: function () {


                        $.ajax({
                            url: 'index.php?action=user_edit_form_fms&u_id=' + u_id,
                            data: $("#edit_form").serialize(),
                            type: 'POST',
                            dataType: 'json',
                            success: function (Response) {
                                //$(Response).dialog("close");
                                if (Response == 'in_use') alert('Имя пользователя уже занято');
                                else if (Response == 'empty_login') alert('Имя пользователя не задано');
                                else if (Response == 'empty_password') alert('Пароль не задан');
                                else if (Response.status == 'ok') {
                                    alert('Пользователь изменен');
                                    $('[data-u_id=' + Response.user.id + ']').html(Response.user.name);
                                    $("#dialog-message").dialog("destroy");

                                }
                                else {
                                    alert(Response);
                                }
                                // alert(Response);
//                            $(this).html(Response);

                            },
                            error: function () {
                                alert('Ошибка');
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

    //Для главных админов
    $(function () {

        $('.adm_add').on('click', function () {
            var $this = $(this);
            var head = $this.data('head');
            var group_id = $this.data('group_id');
            var typecaption = $this.data('typecaption');
            if (head > 0) var head_visible = '&head_visible=' + head;
            else var head_visible = '';

            $.ajax({
                url: 'index.php?action=user_add_form_fms' + head_visible,
                data: {
                    user_type_id: group_id



                },
                type: 'GET',
                success: function (Response) {
                    //$(Response).dialog("close");
                    /*                if (Response == 'in_use') alert ('Имя пользователя уже занято');
                     else if (Response == 'empty_login') alert ('Имя пользователя не задано');
                     else if (Response == 'empty_password') alert ('Пароль не задан');*/
                    //else {alert('Пользователь создан'); $( "#dialog-message" ).dialog( "close" );}
                    //alert(Response);
                    $("#dialog-message_add").html(Response);
//                            $(this).html(Response);

                },
                error: function () {
                    alert('Ошибка');
                }

            });


            $("#dialog-message_add").dialog("open");


            $("#dialog-message_add").dialog({
                close:function () {
                    $(this).dialog("destroy");
                },
                title: typecaption,
                width: 500,
                top: 100,
                modal: true,

                buttons: {
                    Отмена: function () {
                        $(this).dialog("destroy");
                    },
                    Сохранить: function () {


                        $.ajax({
                            url: 'index.php?action=user_add_form_fms',
                            data: $("#add_form").serializeArray(),

                            type: 'POST',
                            success: function (Response) {
                                //$(Response).dialog("close");
                                if (Response == 'in_use') alert('Имя пользователя уже занято');
                                else if (Response == 'empty_login') alert('Имя пользователя не задано');
                                else if (Response == 'empty_password') alert('Пароль не задан');
                                else if (Response == 'ok') {
                                    alert('Пользователь создан');
                                    /*  var asd=$('#user_list_' +id).html();
                                     //alert(Response.user);
                                     if (!empty(Response.user.head_name)) var head_name=' - <strong>'+Response.user.head_name+'</strong>';
                                     else var head_name='';
                                     var add ='<li><a data-id="' + id + '" data-u_id="' + Response.user.id + '" data-head="1" data-head_id="1" class="bt n adm long_btn"> (' + Response.user.name + ')'+head_name+'</a></li>';

                                     var list=asd+add;

                                     $('#user_list_'+id).html(list);
                                     */

                                    $("#dialog-message_add").dialog("destroy");
                                    window.location.href = window.location.href;
                                }
                                else {
                                    alert(Response);
                                }
                                // alert(Response);
//                            $(this).html(Response);

                            },
                            error: function () {
                                alert('Ошибка');
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
    //var_dump($type->head_visible);

//echo $type->id;
    /*if ($type->id == 2 && empty($_SESSION['privelegies']['admin_head'])) {
        continue;
    }*/

    /* if ($type->id != 3 && ($type->id != 17 || empty($_SESSION['privelegies']['admin_head']))) {

         continue;
     }*/


    if ($type->id != 3 && ($type->id != 17 || (empty($_SESSION['privelegies']['admin_head'])) && $_SESSION['u_id'] != MAINEST_FMS_ADMIN_ID)) {

        continue;
    }



    /*  if ($type->id == 5 && empty($_SESSION['privelegies']['admin_head']) && empty($_SESSION['privelegies']['admin'])) {
          continue;
      }*/
    if (empty($new_list[$type->id])) {
        $disabled = 1;
        $disabled_echo = 'disabled="disabled"';

    } else {
        $disabled = 0;
        $disabled_echo = '';
    }

    $result .= '<li style="list-style-type: none"><a data-id="' . $type->id . '" class="btn ul_open long_btn" ' . $disabled_echo . ' >' . $type->caption . '</a><a data-group_id="' . $type->id . '" data-head="' . $type->head_visible . '" class="btn adm_add  btn-info" data-typecaption="'.$type->caption.'">Добавить</a>';

    if ($disabled == 1) {
        continue;
    }
    $result .= '<ul class="user_lists"  id="user_list_' . $type->id . '" style="display:none">';

    foreach ($new_list[$type->id] as $user) {


        if ($type->head_visible == 0) {
            $local_center_button = '';
        } elseif ($type->isHasRole(43)) {
            $local_center_button = ' - <strong>' . HeadCenter::getByID(
                    $user->head_id
                )->name . '</strong>';
        } else {
            $local_center_button = ' - <strong>' . HeadCenter::getByID(
                    $user->head_id
                )->name . '</strong> <a href="?action=user_list_edit&id=' . $user->u_id . '" class="btn">Доступ к локальным центрам</a>';
        }
//var_dump($user);

        $result .= '<li><a data-id="' . $type->id . '" data-u_id="' . $user->u_id . '" data-head="' . $type->head_visible . '" data-head_id="' . $user->head_id . '" class="bt n adm long_btn" data-typecaption="'.$type->caption.'">' . $user->shortName(
            ) . '</a>
        ' . $local_center_button . '
        </li>';
    }
    $result .= '</ul>';


}

//echo '</ul>';
echo $result . '</ul>';
?>


<div id="dialog-message" title="Пользователь" style="display: none;top:100px"></div>
<div id="dialog-message_add" title="Пользователь" style="display: none;top:100px"></div>

