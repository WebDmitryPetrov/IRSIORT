<?php
ob_start();
require('include/_func.php');

if (isset($_GET['exit']) && $_GET['exit'] == "exit") {
    ses_destr();
}
if (empty($_GET['ooops_auth']))
    auth($_SERVER['PHP_SELF']);

//$site_name='������������ ������� ������������������� ����� �<br>��������� ����������� ������������ ����������� �������';
$site_name='��������������� �������������� ������� ��������������� ������ ������������ ������������';
?>
<!DOCTYPE HTML>
<html>
<head>
    <title><?php echo $lang['_title']; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="stylesheet" href="css/blueprint/screen.css" type="text/css" media="screen, projection">
    <link rel="stylesheet" href="css/blueprint/print.css" type="text/css" media="print">
    <!--[if lt IE 8]>
    <link rel="stylesheet" href="css/blueprint/ie.css" type="text/css" media="screen, projection"><![endif]-->

    <link type="text/css" href="css/ui-lightness/jquery-ui-1.7.2.custom.css"
          rel="stylesheet"/>

    <script type="text/javascript" src="include/jquery.js"></script>
    <script type="text/javascript"
            src="/include/jquery-ui-1.7.2.custom.min.js"></script>
</head>
<body>

<div class="top">
    <div class="container">
        <div class="span-24">
            <div class="span-2">
                <img src="/img/logotype_my_rus.png" height="70">
            </div>
            <div class="span-20">
                <h1 class="name_new" style="text-align: center"><?=$site_name?>
                </h1>
            </div>
            <div class="span-2 last">
                <img src="/img/flag.jpg" height="70">
            </div>
        </div>

        <div class="span-24 main-img"><img src="i/main-img.jpg" width="100%"/></div>
        <div class="span-24 shadow"><img src="i/shadow.png"/></div>
    </div>
</div>
<div class="container append-bottom">
    <div class="span-24">
        <div class="span-5 colborder ">
            <?php if (isset($_SESSION['u_id'])) { ?>
            <script>
                function send_passwd() {
                    $.get('provider_irud.php', {'0x0':'change_pass_user_irud', password:$('#password').val(), password2:$('#password2').val()},
                            function (data) {
                                var update = new Array();
                                if (data.indexOf('|' != -1)) {
                                    update = data.split('|');
                                    $('#' + update[0]).dialog("close");
                                    $('#message').html(update[1]);
                                    $('#message').show();
                                    //	alert($('#'+update[0]).html())

                                }
                            }
                    );
                }

                function passw() {
                    if ((document.getElementById('password').value == document.getElementById('password2').value) && (document.getElementById('password').value != '') && (document.getElementById('password2').value != '')) {
                        document.getElementById('button').disabled = false;
                        document.getElementById('button').value = "��������� ������";
                    }
                    else {
                        document.getElementById('button').disabled = true;
                        document.getElementById('button').value = "������ �� ���������";

                    }
                }

                $(function () {

                    $('#change_passwd').click(function () {
                        $.get("provider_irud.php", {'0x0':'show_change_pass_user_irud', 'u_id':'<?php echo $_SESSION['u_id']; ?>'}, function (data) {
                            var update = new Array();
                            if (data.indexOf('|' != -1)) {
                                update = data.split("|");
                                $('#' + update[0]).html(update[1]);
                                $('#' + update[0]).dialog({
                                    title:'������� ������',
                                    //autoOpen: true,
                                    width:450,
                                    modal:true,
                                    bgiframe:true,
                                    resizable:false,
                                    buttons:{
                                        '������':function () {
                                            $(this).dialog('close');
                                        }
                                    }
                                });
                                $('#' + update[0]).dialog('open');
                            }
                        });
                    });
                });
            </script>
            <h3 style="color:red"><?php echo  $_SESSION['surname'];?></h3>
            <ul class="menu">

                <?php
                require_once('sdt/controller.php');
                $C=Controller::getInstance();

               if ($C->userHasRole(Roles::ROLE_CENTER_EXTERNAL)): ?>
                   <li><a href="sdt/index.php?action=act_add">����� �������� ������</a></li>
                   <li><a href="sdt/index.php?action=act_fs_list">�������� ������ � ������</a></li>
                   <li><a href="sdt/index.php?action=act_univer_on_check">�������� ��������</a></li>
                   <li><a href="sdt/index.php?action=act_third_list">����������� �������� ������</a></li>
                <?php endif; ?>


                <?php
                if (
                    $C->userHasRole(Roles::ROLE_CENTER_EDITOR)
                    ||
                    $C->userHasRole(Roles::ROLE_ROOT)
                    ||
                    $C->userHasRole(Roles::ADMIN_HEAD)
                    /*diff9*/||
                    $C->userHasRole(Roles::ROLE_FMS_ADMIN)
                    ||
                    $C->userHasRole(Roles::ROLE_STATISTICS)
                    ):
                    ?>
                    <li class="nav-header">�����������</li>
                    <?php if ($C->userHasRole(Roles::ROLE_CENTER_EDITOR)): ?>
                    <li><a href="sdt/index.php?action=universities">����</a></li>
                    <li><a href="sdt/index.php?action=user_list">����� �������������</a></li>
                    <li><a href="sdt/index.php?action=head_center">�������� ������</a></li>
                <?php endif; ?>
                    <?php if ($C->userHasRole(Roles::ADMIN_HEAD)): ?>
                    <li><a href="sdt/index.php?action=user_type_list">���� �������������</a></li>
                    <li><a href="sdt/index.php?action=groups_list">������ �����</a></li>
                <?php endif; ?>
                    <?php if ($C->userHasRole(Roles::ROLE_ROOT)): ?>
                    <li><a href="sdt/index.php?action=head_center">�������� ������</a></li>
                    <li><a href="sdt/index.php?action=head_centers">�������������� ��������� �������</a></li>
                    <li><a href="sdt/index.php?action=edit_user_list">�������������� �������������</a></li>
                    <li><a href="sdt/index.php?action=test_levels">������ ������������</a></li>
                    <li><a href="sdt/index.php?action=deleted_list">����������������</a></li>
                    <li><a href="sdt/index.php?action=federal_dc">����������� ������ � �������</a></li>


                <?php endif; ?>

                    <li class="divider"></li>
                <?php endif; ?>




                <?php if ($C->userHasRole(Roles::ROLE_ROOT)): ?>
                    <li><a href="sdt/index.php?action=invalid_certs">���������������� ������</a></li>
                <?php endif; ?>




                <?php if ($C->userHasRole(Roles::ROLE_SEARCH)): ?>
                    <li class="nav-header">�����</li>
                    <?php if ($C->userHasRole(Roles::ROLE_ROOT)): ?>
                        <li><a href="sdt/index.php?action=search_pupil_range_annul">�� ��������� ������� ��� ���������</a></li>
                    <?php endif; ?>
                    <li><a href="sdt/index.php?action=search_pupil">�����������</a></li>
                    <li><a href="sdt/index.php?action=search_act">���</a></li>
                <?php endif; ?>







                <li class="divider"></li>

                    <li><a href="sdt/index.php?action=help">�������</a></li>

                <?php if ($C->userHasRole(Roles::ROLE_BUH)): ?>
                    <li class="divider"></li>
                    <li class="nav-header">�����������</li>
                    <li><a href="sdt/index.php?action=buh_check_univer">�����</a></li>
                <?php endif; ?>

                <li class="nav-header"></li>
                <li><a href="#" id="change_passwd">������� ������</a></li>
                <?php if (isset($_SESSION['privelegies']['admin'])) { ?>
                <li><a href="admin.php"> ���������������� </a></li>
                <?php } ?>
                <li><a href="/index.php?exit=exit"> ����� </a></li>
            </ul>
            <?php
        }
        else {
            ?>
            <?
            #echo $_SERVER["SSL_SESSION_ID"];
            if (!empty($_GET['ooops_auth'])and $_GET['ooops_auth'] == 'x4') echo '<h3 style="color:red">����� ����� �����������</h3>';
            if (!empty($_GET['ooops_auth'])and $_GET['ooops_auth'] == '2') echo '<h3 style="color:red">������ �����</h3>';
            if (!empty($_GET['ooops_auth'])and $_GET['ooops_auth'] == '2') echo '<h3 style="color:red">������ ������</h3>';
            if (!empty($_GET['ooops_auth'])and $_GET['ooops_auth'] == '3') echo '<h3 style="color:red">������������ ����� ��� ������</h3>';
            if (!empty($_GET['exit'])and $_GET['exit'] == 'exit') echo '<h3 style="color:red">����� ��������!</h3>';

            ?>
            <form method="post" action="<?=$_SERVER['PHP_SELF']?>?t=<?=time();?>">
                <label>
                    �����:<br>
                    <input type="text" name="login"> </label><br>

                <label> ������:<br>
                    <input type="password" name="password"> </label><br>
                <input type="submit" value="�����!">


            </form>
            <?php } ?>
        </div>
        <div class="span-15 last">
            <div id="message"></div>
<!--            <h4> ������� �� ��������������� � ����������� �������� ���������� ���������� � ������� �� ��������:  --><?//=SDT_HELP_PHONE?><!--</h4>-->
            <?php if (isset($_SESSION['u_id'])) { ?>

            <h3 style="margin-bottom: 3px"><a target="_blank" href="http://37.18.92.27/web-local/prep/rj/?id=5">����������� ���������</a></h3>
                <div style="font-size: 1.2em;">������ ������ �� ������ � �������� ����� �� ������ ����� ������������, ��������� ������ - <strong>903224313378</strong></div>
                <?php if(!empty($_GET['error'])&&$_GET['error']=='nodogovor'):?>
                   <div style="color:red;font-size: large; padding: 10px;border: 3px solid rgb(139, 0, 0)">
                       ��� ����� ������������ �� ��������� ��������������� � ������� �������� �������! <br>� ������� ����������� ���������� �� ����������� � ���� ���������.
                       :<br>��� ����������� ����������:<br>
                       ���. <?=SDT_HELP_PHONE?><br>
                       e-mail: <?=SDT_HELP_EMAIL?><br>
                       <?=SDT_HELP_CAPTION?><br>
                               </div>
                <?php endif;?>


			<table><tr><td><img src="/img/report1.jpg" width=260></td><td>&nbsp;&nbsp;&nbsp;</td><td><img src="/img/report2.jpg" width=260></td></tr></table>
            <?php
        }
        else {
            ?>
<!--            <h3>������������ ������� ���������� ����� �� �������� �����</h3>-->
            <h3>������������ ������� ���������� ����� �� �������� ����� � �������������� �������</h3>
            <!--<h3>������ ������������ � �������</h3>
			<table><tr><td><img src="/img/report1.jpg" width=260></td><td>&nbsp;&nbsp;&nbsp;</td><td><img src="/img/report2.jpg" width=260></td></tr></table>
-->            <?php } ?>

        </div>
    </div>
</div>
<div class="footer">
    <hr>
    <div class="container prepend-top">
        <div class="span-4 colborder ">� 2013 <? if (date("Y")!=2013): ?>- <?=date("Y")?><?endif;?></div>
        <div class="span-19 last">
            <a href="http://old.rudn.ru/?pagec=2719">
                ��� ����� �������� ����������� ����������� ������ �������</a>
        </div>
    </div>
    <div class="container append-bottom">
        <div class="span-24">
            &nbsp;
        </div>
    </div>
</div>
<div id="content"></div>
</body>
</html>
<?php
echo ob_get_clean();
