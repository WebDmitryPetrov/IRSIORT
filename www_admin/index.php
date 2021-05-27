<?php
ob_start();
require('include/_func.php');

if (isset($_GET['exit']) && $_GET['exit'] == "exit") {
    ses_destr();
}
if (empty($_GET['ooops_auth']))
    auth($_SERVER['PHP_SELF']);

//$site_name='Объединенная система автоматизированного учета и<br>обработки результатов тестирования иностранных граждан';
$site_name='Интегрированная распределенная система информационного обмена результатами тестирования';
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
                        document.getElementById('button').value = "Сохранить пароль";
                    }
                    else {
                        document.getElementById('button').disabled = true;
                        document.getElementById('button').value = "Пароли не совпадают";

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
                                    title:'Сменить пароль',
                                    //autoOpen: true,
                                    width:450,
                                    modal:true,
                                    bgiframe:true,
                                    resizable:false,
                                    buttons:{
                                        'Отмена':function () {
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
                   <li><a href="sdt/index.php?action=act_add">Новая тестовая сессия</a></li>
                   <li><a href="sdt/index.php?action=act_fs_list">Тестовые сессии в работе</a></li>
                   <li><a href="sdt/index.php?action=act_univer_on_check">Проходят проверку</a></li>
                   <li><a href="sdt/index.php?action=act_third_list">Проверенные тестовые сессии</a></li>
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
                    <li class="nav-header">Справочники</li>
                    <?php if ($C->userHasRole(Roles::ROLE_CENTER_EDITOR)): ?>
                    <li><a href="sdt/index.php?action=universities">ВУЗы</a></li>
                    <li><a href="sdt/index.php?action=user_list">Права пользователей</a></li>
                    <li><a href="sdt/index.php?action=head_center">Головные центры</a></li>
                <?php endif; ?>
                    <?php if ($C->userHasRole(Roles::ADMIN_HEAD)): ?>
                    <li><a href="sdt/index.php?action=user_type_list">Типы пользователей</a></li>
                    <li><a href="sdt/index.php?action=groups_list">Список ролей</a></li>
                <?php endif; ?>
                    <?php if ($C->userHasRole(Roles::ROLE_ROOT)): ?>
                    <li><a href="sdt/index.php?action=head_center">Головные центры</a></li>
                    <li><a href="sdt/index.php?action=head_centers">Редактирование локальных центров</a></li>
                    <li><a href="sdt/index.php?action=edit_user_list">Редактирование пользователей</a></li>
                    <li><a href="sdt/index.php?action=test_levels">Уровни тестирования</a></li>
                    <li><a href="sdt/index.php?action=deleted_list">Недействительные</a></li>
                    <li><a href="sdt/index.php?action=federal_dc">Федеральные округа и регионы</a></li>


                <?php endif; ?>

                    <li class="divider"></li>
                <?php endif; ?>




                <?php if ($C->userHasRole(Roles::ROLE_ROOT)): ?>
                    <li><a href="sdt/index.php?action=invalid_certs">Недействительные бланки</a></li>
                <?php endif; ?>




                <?php if ($C->userHasRole(Roles::ROLE_SEARCH)): ?>
                    <li class="nav-header">Поиск</li>
                    <?php if ($C->userHasRole(Roles::ROLE_ROOT)): ?>
                        <li><a href="sdt/index.php?action=search_pupil_range_annul">По диапазону бланков для аннуляции</a></li>
                    <?php endif; ?>
                    <li><a href="sdt/index.php?action=search_pupil">Тестируемый</a></li>
                    <li><a href="sdt/index.php?action=search_act">Акт</a></li>
                <?php endif; ?>







                <li class="divider"></li>

                    <li><a href="sdt/index.php?action=help">Справка</a></li>

                <?php if ($C->userHasRole(Roles::ROLE_BUH)): ?>
                    <li class="divider"></li>
                    <li class="nav-header">Бухгалтерия</li>
                    <li><a href="sdt/index.php?action=buh_check_univer">Счета</a></li>
                <?php endif; ?>

                <li class="nav-header"></li>
                <li><a href="#" id="change_passwd">Сменить пароль</a></li>
                <?php if (isset($_SESSION['privelegies']['admin'])) { ?>
                <li><a href="admin.php"> Администрировать </a></li>
                <?php } ?>
                <li><a href="/index.php?exit=exit"> Выход </a></li>
            </ul>
            <?php
        }
        else {
            ?>
            <?
            #echo $_SERVER["SSL_SESSION_ID"];
            if (!empty($_GET['ooops_auth'])and $_GET['ooops_auth'] == 'x4') echo '<h3 style="color:red">Время сесии закончелось</h3>';
            if (!empty($_GET['ooops_auth'])and $_GET['ooops_auth'] == '2') echo '<h3 style="color:red">Пустой логин</h3>';
            if (!empty($_GET['ooops_auth'])and $_GET['ooops_auth'] == '2') echo '<h3 style="color:red">Пустой пароль</h3>';
            if (!empty($_GET['ooops_auth'])and $_GET['ooops_auth'] == '3') echo '<h3 style="color:red">Неправильный логин или пароль</h3>';
            if (!empty($_GET['exit'])and $_GET['exit'] == 'exit') echo '<h3 style="color:red">Сеанс завершён!</h3>';

            ?>
            <form method="post" action="<?=$_SERVER['PHP_SELF']?>?t=<?=time();?>">
                <label>
                    Логин:<br>
                    <input type="text" name="login"> </label><br>

                <label> Пароль:<br>
                    <input type="password" name="password"> </label><br>
                <input type="submit" value="Войти!">


            </form>
            <?php } ?>
        </div>
        <div class="span-15 last">
            <div id="message"></div>
<!--            <h4> Справки по организационным и юридическим вопросам оформления документов в Системе по телефону:  --><?//=SDT_HELP_PHONE?><!--</h4>-->
            <?php if (isset($_SESSION['u_id'])) { ?>

            <h3 style="margin-bottom: 3px"><a target="_blank" href="http://37.18.92.27/web-local/prep/rj/?id=5">Техническая поддержка</a></h3>
                <div style="font-size: 1.2em;">Задать вопрос по работе с Системой можно на форуме сайта техподдержки, используя пароль - <strong>903224313378</strong></div>
                <?php if(!empty($_GET['error'])&&$_GET['error']=='nodogovor'):?>
                   <div style="color:red;font-size: large; padding: 10px;border: 3px solid rgb(139, 0, 0)">
                       Ваш центр тестирования не полностью зарегистрирован в Системе Головным центром! <br>В Системе отсутствует информация по заключенным с Вами договорам.
                       :<br>Для исправления обратитесь:<br>
                       тел. <?=SDT_HELP_PHONE?><br>
                       e-mail: <?=SDT_HELP_EMAIL?><br>
                       <?=SDT_HELP_CAPTION?><br>
                               </div>
                <?php endif;?>


			<table><tr><td><img src="/img/report1.jpg" width=260></td><td>&nbsp;&nbsp;&nbsp;</td><td><img src="/img/report2.jpg" width=260></td></tr></table>
            <?php
        }
        else {
            ?>
<!--            <h3>Тестирования граждан зарубежных стран по русскому языку</h3>-->
            <h3>Тестирование граждан зарубежных стран по русскому языку и интеграционный экзамен</h3>
            <!--<h3>Выдача сертификатов и справок</h3>
			<table><tr><td><img src="/img/report1.jpg" width=260></td><td>&nbsp;&nbsp;&nbsp;</td><td><img src="/img/report2.jpg" width=260></td></tr></table>
-->            <?php } ?>

        </div>
    </div>
</div>
<div class="footer">
    <hr>
    <div class="container prepend-top">
        <div class="span-4 colborder ">© 2013 <? if (date("Y")!=2013): ?>- <?=date("Y")?><?endif;?></div>
        <div class="span-19 last">
            <a href="http://old.rudn.ru/?pagec=2719">
                Все права защищены “Российский университет дружбы народов”</a>
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
