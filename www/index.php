<?php
ob_start();
require('include/_func.php');

if (isset($_GET['exit']) && $_GET['exit'] == "exit") {
    ses_destr();
}
if (empty($_GET['ooops_auth']))
    auth($_SERVER['PHP_SELF']);


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
                <div class="span-2" style="width:87px">
                    <img src="/img/logotype_my_rus.png" height="70">
                </div>
                <div class="span-20" style="width:766px;margin: 0 7px 0 -7px !important;">
                    <h1 class="name_new" style="text-align: center">��������������� �������������� �������
                        ��������������� ������ ������������ ������������
                    </h1>
                </div>
                <div class="span-2 last" style="width:87px">
                    <img src="/img/flag.jpg" height="70">
                </div>
            </div>

            <div class="span-24 main-img"><img src="i/main-img.jpg" width="100%"/></div>
            <div class="span-24 shadow"><img src="i/shadow.png"/></div>
        </div>
    </div>
    <div class="container append-bottom">
        <div class="span-24">



            <div class="title_div"><h3 style="text-align:center;color: brown"><?=mysql_result(mysql_query('select login_page_title from head_center_text where head_id='.CURRENT_HEAD_CENTER),0);?></h3></div>


            <div class="span-5 colborder ">
                <?php if (isset($_SESSION['u_id'])) { ?>
                    <script>
                        function send_passwd() {
                            $.get('provider_irud.php', {
                                    '0x0': 'change_pass_user_irud',
                                    password: $('#password').val(),
                                    password2: $('#password2').val()
                                },
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
                                $.get("provider_irud.php", {
                                    '0x0': 'show_change_pass_user_irud',
                                    'u_id': '<?php echo $_SESSION['u_id']; ?>'
                                }, function (data) {
                                    var update = new Array();
                                    if (data.indexOf('|' != -1)) {
                                        update = data.split("|");
                                        $('#' + update[0]).html(update[1]);
                                        $('#' + update[0]).dialog({
                                            title: '������� ������',
                                            //autoOpen: true,
                                            width: 450,
                                            modal: true,
                                            bgiframe: true,
                                            resizable: false,
                                            buttons: {
                                                '������': function () {
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
                    <h3 style="color:red"><?php
                        if (!empty($_SESSION['user_type_caption'])) {
                            echo $_SESSION['user_type_caption'];
                            echo ' - ';
                        }
                        echo $_SESSION['surname']; ?></h3>
                    <ul class="menu">

                        <?php
                        require_once('sdt/controllers/AbstractController.php');
                        $C = AbstractController::getInstance();
                        ?>

                        <?php if ($C->userHasRole(Roles::ROLE_CENTER_EXTERNAL)): ?>
                            <? if ($C->userHasRole(Roles::ROLE_CENTER_EXTERNAL_API)): ?>
                                <li><a href="sdt/api.php?action=dics">�����������</a></li>
                                <li><a href="sdt/api.php?action=xml_upload">��������� ����</a></li>
                                <li><a href="sdt/index.php?action=act_fs_list">�������� ������ � ������</a></li>
                            <?php else: ?>
                                <li><a href="sdt/index.php?action=act_choose">����� �������� ������</a></li>
                                <li><a href="sdt/index.php?action=act_fs_list">�������� ������ � ������</a></li>
                                <li><a href="sdt/index.php?action=act_univer_on_check">�������� ��������</a></li>
                                <li><a href="sdt/index.php?action=acts_print_list">�������� � ���������� ���������� �������� ������</a></li>

                                <li><a href="sdt/index.php?action=loc_archive">���������� �� ������������</a></li>

                                <li><a href="sdt/dubl.php?action=dubl">�������� ��������</a></li>
                                <li><a href="sdt/dubl.php?action=loc_archive">���������� �� ����������</a></li>
                                <li><a href="sdt/index.php?action=loc_notes">������ �������� �������</a></li>

                            <? endif; ?>
                        <?php endif; ?>

                        <?php
                        if (
                            $C->userHasRole(Roles::ROLE_CENTER_EDITOR)
                            ||
                            $C->userHasRole(Roles::ROLE_SIGNER_MANAGER

                            )
                        ):
                            ?>
                            <li class="nav-header">�����������</li>
                            <? if (
                        $C->userHasRole(Roles::ROLE_CENTER_EDITOR)
                        ): ?>
                            <li><a href="sdt/index.php?action=universities">��������� ������</a></li>
                            <li><a href="sdt/index.php?action=change_price_univers">��������� ��� �������
                                    ������������</a></li>
                            <!--    <li><a href="sdt/index.php?action=user_list">���������� ��������������</a></li>-->
                            <li><a href="sdt/index.php?action=current_head_center_view">���������� � �������� ������</a>
                            </li>
                            <li><a href="sdt/index.php?action=current_head_center_text_view">���������� ��� ������</a>
                            </li>
                        <? endif; ?>
                            <? if (
                        $C->userHasRole(Roles::ROLE_SIGNER_MANAGER)

                        ): ?>
                            <li><a href="sdt/index.php?action=signing_list">�������������</a></li>
                            <li><a href="sdt/index.php?action=frdo_excel_reports_list">������ ����</a></li>
                        <? endif; ?>
                            <? if (
                        $C->userHasRole(Roles::ROLE_CENTER_EDITOR)

                        ): ?>
                            <li><a href="sdt/index.php?action=edit_user_list">�������������� �������������</a></li>
                            <li><a href="sdt/index.php?action=certificate_type_list">���������� �������������</a></li>
                        <? endif; ?>

                            <li class="divider"></li>
                        <?php endif; ?>



                        <?php if ($C->userHasRole(Roles::ROLE_CERTIFICATE_MANAGER)): ?>
                            <li class="nav-header">�����������</li>
                            <li><a href="sdt/index.php?action=certificate_type_list">���������� �������������</a></li>
                            <li class="divider"></li>
                        <?php endif; ?>

                        <?php if (
                            $C->userHasRole(Roles::ROLE_CENTER_FOR_CHECK) ||
                            $C->userHasRole(Roles::ROLE_CENTER_RECEIVED) ||
                            $C->userHasRole(Roles::ROLE_CENTER_FOR_PRINT) ||
                            $C->userHasRole(Roles::ROLE_CENTER_PRINT) ||
                            $C->userHasRole(Roles::ROLE_CENTER_WAIT_PAYMENT) ||
                            $C->userHasRole(Roles::ROLE_CENTER_ARCHIVE) ||
                            $C->userHasRole(Roles::ROLE_CERTIFICATE_MANAGER)
                        ): ?>
                            <li class="nav-header">��������� ������������</li>
                        <? endif; ?>
                        <?php
                        if ($C->userHasRole(Roles::ROLE_CENTER_FOR_CHECK)):
                            ?>
                            <li><a href="sdt/index.php?action=act_universities_second">��������
                                    �������� ������
                                    �� ��������� �������
                                </a></li>
                        <? endif; ?>

                        <? if ($C->userHasRole(Roles::ROLE_CENTER_FOR_CHECK) ||
                            $C->userHasRole(Roles::ROLE_CENTER_RECEIVED) ||
                            $C->userHasRole(Roles::ROLE_CENTER_FOR_PRINT) ||
                            $C->userHasRole(Roles::ROLE_CENTER_PRINT) ||
                            $C->userHasRole(Roles::ROLE_CENTER_WAIT_PAYMENT)
                        ):
                            ?>
                            <li><a href="sdt/index.php?action=rework_list">��������� �� ���������
                                    � ��������� �������
                                </a></li>
                        <!--    <li><a href="sdt/index.php?action=checked_list">��������� �� ����������
                                    � ��������� �������</a></li>-->
                        <? endif; ?>

                        <? if ($C->userHasRole(Roles::ROLE_CENTER_RECEIVED) ||
                            $C->userHasRole(Roles::ROLE_CENTER_PRINT) ||
                            $C->userHasRole(Roles::ROLE_CENTER_WAIT_PAYMENT) ||
                            $C->userHasRole(Roles::ROLE_CERTIFICATE_MANAGER)
                        ):
                            ?>
                            <li><a href="sdt/index.php?action=act_universities&type=1">������������ ��������
                                    ���� ����������
                                    �� ��������������������
                                    ������������
                                </a></li>
                            <li><a href="sdt/index.php?action=act_universities&type=2">������������ ��������
                                    ���� ����������
                                    �� ��������������� ��������

                                </a></li>



                        <? endif; ?>





                        <?php
                        if ($C->userHasRole(Roles::ROLE_CENTER_ARCHIVE)):
                            ?>
                            <li><a href="sdt/index.php?action=act_universities_archive">�����</a></li>
                        <? endif; ?>



                        <?php
                        if ($C->userHasRole(Roles::ROLE_SUPERVISOR)):
                            ?>
                            <li><a href="sdt/dubl.php?action=dubl_act_universities_received&type=1">�������� ��������
                                    �� ��������������������
                                    ������������</a></li>
                            <li><a href="sdt/dubl.php?action=dubl_act_universities_received&type=2">�������� ��������
                                    �� ��������������� ��������</a></li>
                        <? endif; ?>
                        <? if ($C->userHasRole(Roles::ROLE_CENTER_ARCHIVE)):
                        ?>

                        <li><a href="sdt/dubl.php?action=dubl_lc_archive">����� ����������</a></li>
                        <? endif; ?>


                        <!--<li><a href="sdt/index.php?action=act_paid">������������</a></li> -->
                        <li class="divider"></li>

                        <?php
                        if ($C->userHasRole(Roles::ROLE_REPORT)): ?>
                            <li class="nav-header">������</li>
                            <li><a href="sdt/index.php?action=otch_country">���������� ����������� �� ������ ������</a>
                            </li>
                            <li><a href="sdt/index.php?action=report_not_insert_numbers">������ � �� ���������� ��������
                                    ������������ � ������� �� ��������������� ��������</a></li>
                            <li><a href="sdt/index.php?action=report_not_insert_numbers_rki">������ � �� ����������
                                    �������� ������������ � ������� �� �������������������� ������������</a></li>

                            <li class="divider"></li>
                        <?php endif; ?>

                        <?php if ($C->userHasRole(Roles::ROLE_SEARCH)): ?>
                            <li class="nav-header">�����</li>
                            <li><a href="sdt/index.php?action=search_pupil">����������� �� ������
                                    ����������� ��� ���</a></li>
                            <li><a href="sdt/index.php?action=search_act">����������� ����������
                                    �������� ������</a></li>
                        <?php endif; ?>
                        <li class="divider"></li>
                        <li><a href="sdt/index.php?action=help">�������</a></li>
                        <?php if ($C->userHasRole(Roles::ROLE_BUH)): ?>
                            <li class="divider"></li>
                            <li class="nav-header">�����������</li>
                            <li><a href="sdt/index.php?action=buh_check_univer">������ ����� �������� ������</a></li>
                            <li><a href="sdt/index.php?action=report_check_univer">������ ����� �������� ������</a></li>
                            <li><a href="sdt/index.php?action=buh_dubl_month">������ ����� �������� ������ ��
                                    ����������</a></li>
                        <?php endif; ?>
                        <?php
                        if (
                        $C->userHasRole(Roles::ROLE_CONTR_BUH)

                        ):
                            ?>
                            <li><a href="sdt/index.php?action=buh_search_act">����� ����� �������� ������</a></li>
                            <li><a href="sdt/index.php?action=buh_search_man">����� ����������� �� ������ ����������� ��� ���</a></li>
                        <? endif ?>

                        <li class="nav-header"></li>
                        <li><a href="#" id="change_passwd">������� ������</a></li>

                        <li><a href="/index.php?exit=exit"> ����� </a></li>
                    </ul>
                <?php
                }
                else {
                ?>
                <?
                #echo $_SERVER["SSL_SESSION_ID"];
                if (is_blocked()) echo '<h3 style="color:red">�� ������������� �� ������ �������!<br> ��� �������������� ������� � ������� ���������� � ���� �������� ����� � ������������ � ��������� ������ Ip - ������</h3>';
                if (!empty($_GET['ooops_auth']) and $_GET['ooops_auth'] == 'x4') echo '<h3 style="color:red">����� ����� �����������</h3>';
                if (!empty($_GET['ooops_auth']) and $_GET['ooops_auth'] == '2') echo '<h3 style="color:red">������ �����</h3>';
                if (!empty($_GET['ooops_auth']) and $_GET['ooops_auth'] == '2') echo '<h3 style="color:red">������ ������</h3>';
                if (!empty($_GET['ooops_auth']) and $_GET['ooops_auth'] == '3') echo '<h3 style="color:red">������������ ����� ��� ������</h3>';
                if (!empty($_GET['ooops_auth']) and $_GET['ooops_auth'] == 'password_expired') echo '<h3 style="color:red">����� �������� ������ �������.<br>���������� � �������������� ��������� ������ ��� ��������� ������ ������.</h3>';
                if (!empty($_GET['exit']) and $_GET['exit'] == 'exit') echo '<h3 style="color:red">����� ��������!</h3>';

                ?>
                    <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>?t=<?= time(); ?>">
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
                <div id="message" style="color:red; font-weight: bold; font-size: 22px">
                    <?php
                    //                var_dump($_SESSION['password_changed_at'], PASSWORD_DURATION , PASSWORD_REMIND , time());
                    if (!empty($_SESSION) && $_SESSION['password_changed_at'] + PASSWORD_DURATION - PASSWORD_REMIND < time()) {


                        echo '��� ���������� �������� ������ �� ' . date(
                                'd.m.Y',
                                $_SESSION['password_changed_at'] + PASSWORD_DURATION
                            );

                    }
                    ?>
                </div>



                <h4> ������� �� ��������������� � ����������� �������� ���������� ���������� � ������� ��
                    ��������: <?= SDT_HELP_PHONE ?></h4>

<?php if (isset($_SESSION['u_id'])):?>
    <h3 style="margin-bottom: 3px"><a target="_blank"
                                                  href="http://37.18.92.27/web-local/prep/rj/?id=5">�����������
                        ���������</a></h3>
                <div style="font-size: 1.2em;">������ ������ �� ������ � �������� ����� �� ������ �����
                    ������������, ��������� ������ - <strong>903224313378</strong></div>
                <br>
<?endif?>
                <?php if (!empty($_GET['error']) && $_GET['error'] == 'nodogovor'): ?>
                    <div style="color:red;font-size: large; padding: 10px;border: 3px solid rgb(139, 0, 0)">
                        ��� ����� ������������ �� ��������� ��������������� � ������� �������� �������! <br>� �������
                        ����������� ���������� �� ����������� � ���� ���������.
                        <br>��� ����������� ����������:<br>
                        ���. <?= SDT_HELP_PHONE ?><br>
                        e-mail: <?= SDT_HELP_EMAIL ?><br>
                        <?= SDT_HELP_CAPTION ?><br>
                    </div><br>
                <?php endif; ?>
                <?php if (isset($_SESSION['u_id'])) {

//������
                    $for_user_type = (int)$_SESSION['user_type_id'];
                    if ($for_user_type == 19) $for_user_type = 5;

                    $horg =mysql_result(mysql_query('select horg_id from sdt_head_center where id='.$_SESSION['head_id']),0,0);

                    $sql = 'SELECT t1.*,t2.surname FROM system_message AS t1
    LEFT JOIN tb_users AS t2 ON t1.sender_id=t2.u_id
    WHERE t1.user_type = ' . $for_user_type . ' AND (t1.hc_id = 0 OR t1.hc_id = ' . $_SESSION['head_id'] . ' OR t1.hc_id = -'.$horg.') ORDER BY t1.date DESC';

                    $result = mysql_query($sql);

                    if (mysql_num_rows($result)) {
                        $i = 0;
                        echo '<div id="system_message" style="
                        border: 2px dashed orangered;
                        border-radius: 5px;
                        box-shadow: 0px 9px 51px -22px red;
                        margin-bottom: 30px;
                        padding: 5px;
                        overflow: auto;
                    "><h3 style="color:green">��������� �� �������������:</h3>';
                        while ($res = mysql_fetch_assoc($result)) {

                            if (strtotime($res['date']) < strtotime("2017-01-23 00:00:00"))
                                $system_message_text = nl2br($res['text']);
                            else $system_message_text = $res['text'];

                            echo ++$i . '. <span style="font-style:italic">' . $res['surname'] . ' - ' . date("d.m.Y H:i:s", strtotime($res['date'])) . '</span>
<br>
<span style="font-weight:bold">' . $system_message_text . '</span>
<hr>
';
                        }
                        echo '</div>';
                    }
//�����


                    ?>


                    <table>
                        <tr>
                            <td><img src="/img/report1.jpg" width=260></td>
                            <td>&nbsp;&nbsp;&nbsp;</td>
                            <td><img src="/img/report2.jpg" width=260></td>
                        </tr>
                    </table>
                    <?php
                } else {
                    ?>
                    <h3>������������ ������� ���������� ����� �� �������� �����</h3>
                    <h3>������ ������������ � �������</h3>
                    <table>
                        <tr>
                            <td><img src="/img/report1.jpg" width=260></td>
                            <td>&nbsp;&nbsp;&nbsp;</td>
                            <td><img src="/img/report2.jpg" width=260></td>
                        </tr>
                    </table>
                <?php } ?>

            </div>
        </div>
    </div>
    <div class="footer">
        <hr>
        <div class="container prepend-top">
            <div class="span-4 colborder ">� 2013 <? if (date("Y") != 2013): ?>- <?= date("Y") ?><?endif; ?></div>
            <div class="span-19 last">
               <span style="color:white">
                ��� ����� �������� ����������� ����������� ������ �������,
				<a href="https://testrus.rudn.ru/patent2019.pdf" style="color:white;" target="_blank">������������� � ����������� � 2019664281 �� 05.11.2019</a>
				(����� <a href="https://testrus.rudn.ru/patent.pdf"style="color:white" target="_blank">������������� � ����������� � 2014613400 �� 26.03.2014</a>)
				</span>
            </div>
        </div>
        <div class="container append-bottom">
            <div class="span-24">
                &nbsp;
            </div>
        </div>
    </div>
    <div id="content"></div>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-15299096-36"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-15299096-36');
    </script>
    </body>
    </html>
<?php
echo ob_get_clean();
