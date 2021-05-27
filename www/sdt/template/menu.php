<?php
/** @var Controller $C */
?>
<?php if ($C->userHasRole(Roles::ROLE_CENTER_EXTERNAL)): ?>
    <?php if ($C->userHasRole(Roles::ROLE_CENTER_EXTERNAL_API)): ?>
        <li><a href="./api.php?action=dics">�����������</a></li>
        <li><a href="./api.php?action=xml_upload">��������� ����</a></li>
        <li><a href="./index.php?action=act_fs_list">�������� ������ � ������</a></li>
    <?php else: ?>
        <li><a href="./index.php?action=act_choose">����� �������� ������</a></li>
        <li><a href="./index.php?action=act_fs_list">�������� ������ � ������</a></li>
        <li><a href="./index.php?action=act_univer_on_check">�������� ��������</a></li>
        <li><a href="./index.php?action=acts_print_list">�������� � ���������� ���������� �������� ������</a></li>

        <li><a href="./index.php?action=loc_archive">���������� �� ������������</a></li>

        <li><a href="./dubl.php?action=dubl">�������� ��������</a></li>
        <li><a href="./dubl.php?action=loc_archive">���������� �� ����������</a></li>
        <li><a href="./index.php?action=loc_notes">������ �������� �������</a></li>
        <!--        <li><a href="./index.php?action=message">���������</a></li>-->
    <? endif; ?>
<?php endif; ?>


<?php
if (
    $C->userHasRole(Roles::ROLE_CENTER_EDITOR)
    ||
    $C->userHasRole(Roles::ROLE_SIGNER_MANAGER)
):
    ?>
    <li class="nav-header">�����������</li>
    <? if (
$C->userHasRole(Roles::ROLE_CENTER_EDITOR)
): ?>
    <li><a href="./index.php?action=universities">��������� ������</a></li>
    <li><a href="./index.php?action=change_price_univers">��������� ��� ������� ������������</a></li>
    <!--    <li><a href="./index.php?action=user_list">���������� ��������������</a></li>-->
    <li><a href="./index.php?action=current_head_center_view">���������� � �������� ������</a></li>
    <li><a href="./index.php?action=current_head_center_text_view">���������� ��� ������</a></li>
<? endif; ?>
    <? if (
$C->userHasRole(Roles::ROLE_SIGNER_MANAGER)

): ?>
    <li><a href="./index.php?action=signing_list">�������������</a></li>
    <li><a href="./index.php?action=frdo_excel_reports_list">������ ����</a></li>
<? endif; ?>

    <? if (
$C->userHasRole(Roles::ROLE_CENTER_EDITOR)

): ?>
    <li><a href="./index.php?action=edit_user_list">�������������� �������������</a></li>
    <li><a href="./index.php?action=certificate_type_list">���������� �������������</a></li>
<? endif; ?>

    <li class="divider"></li>
<?php endif; ?>


<?php
if (
$C->userHasRole(Roles::ROLE_CERTIFICATE_MANAGER)

):
    ?>
    <li class="nav-header">�����������</li>
    <li><a href="./index.php?action=certificate_type_list">���������� �������������</a></li>
    <li class="divider"></li>
<?php endif; ?>


<?php /* ��������
if (
$C->userHasRole(Roles::ROLE_ADM_BSO)

):
    ?>
    <li class="nav-header">�����������</li>
    <li><a href="./index.php?action=certificate_type_list">���������� �������������</a></li>

<?php endif; */?>


<?php if (
    $C->userHasRole(Roles::ROLE_CENTER_FOR_CHECK) ||
    $C->userHasRole(Roles::ROLE_CENTER_RECEIVED) ||
    $C->userHasRole(Roles::ROLE_CENTER_FOR_PRINT) ||
    $C->userHasRole(Roles::ROLE_CENTER_PRINT) ||
    $C->userHasRole(Roles::ROLE_CENTER_WAIT_PAYMENT) ||
    $C->userHasRole(Roles::ROLE_CENTER_ARCHIVE) ||
    $C->userHasRole(Roles::ROLE_CERTIFICATE_MANAGER)
): ?>
    <li class="nav-header">�������� ������</li>
<? endif; ?>
<?php
if ($C->userHasRole(Roles::ROLE_CENTER_FOR_CHECK)):
    ?>
    <li><a href="./index.php?action=act_universities_second">��������
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
    <li><a href="./index.php?action=rework_list">��������� �� ���������
            � ��������� �������</a></li>
    <!-- <li><a href="./index.php?action=checked_list">��������� �� ����������
             � ��������� �������</a></li>-->
<? endif; ?>

<? if ($C->userHasRole(Roles::ROLE_CENTER_RECEIVED) ||
    $C->userHasRole(Roles::ROLE_CENTER_PRINT) ||
    $C->userHasRole(Roles::ROLE_CENTER_WAIT_PAYMENT) ||
    $C->userHasRole(Roles::ROLE_CERTIFICATE_MANAGER)
):
    ?>
    <li class="divider"></li>
    <li><a href="./index.php?action=act_universities&type=1">������������ ��������
            ���� ����������
            �� ��������������������
            ������������
        </a></li>
    <li><a href="./index.php?action=act_universities&type=2">������������ ��������
            ���� ����������
            �� ��������������� ��������

        </a></li>


<? endif; ?>


<?php /*
if ($C->userHasRole(Roles::ROLE_CENTER_RECEIVED)):
    ?>
    <li><a href="./index.php?action=act_universities_received">����������
            <br>
            <small>(�������� ����)</small>
        </a></li>
<? endif; ?>

<?php
if ($C->userHasRole(Roles::ROLE_CENTER_PRINT)):
    ?>
    <li><a href="./index.php?action=act_universities_print">������
            <br>
            <small>(������ ������ �������)</small>
        </a></li>
<? endif; ?>
<?php
if ($C->userHasRole(Roles::ROLE_CENTER_WAIT_PAYMENT)):
    ?>
    <li><a href="./index.php?action=act_universities_wait">���� ������
            <br>
            <small>(������ ����� ��������)</small>
        </a></li>
<? endif;  */ ?>
<?php
if ($C->userHasRole(Roles::ROLE_CENTER_ARCHIVE)):
    ?>
    <li><a href="./index.php?action=act_universities_archive">�����</a></li>

<? endif; ?>





<?php
if ($C->userHasRole(Roles::ROLE_SUPERVISOR)):
    ?>
    <li class="divider"></li>
    <li><a href="./dubl.php?action=dubl_act_universities_received&type=1">�������� ��������
            �� ��������������������
            ������������</a></li>
    <li><a href="./dubl.php?action=dubl_act_universities_received&type=2">�������� ��������
            �� ��������������� ��������</a></li>
<? endif; ?>
<?php
if ($C->userHasRole(Roles::ROLE_CENTER_ARCHIVE)):
    ?>

    <li><a href="./dubl.php?action=dubl_lc_archive">����� ����������</a></li>
<? endif; ?>


<!--<li><a href="./index.php?action=act_paid">������������</a></li> -->
<li class="divider"></li>

<?php
if ($C->userHasRole(Roles::ROLE_REPORT)): ?>
    <li class="nav-header">������</li>
    <li><a href="./index.php?action=otch_country">���������� ����������� �� ������ ������</a></li>
    <li><a href="./index.php?action=report_not_insert_numbers">������ � �� ���������� �������� ������������ � ������� ��
            ��������������� �������� </a></li>
    <li><a href="./index.php?action=report_not_insert_numbers_rki">������ � �� ���������� �������� ������������ �
            ������� �� �������������������� ������������ </a></li>


    <li class="divider"></li>
<?php endif; ?>

<?php if ($C->userHasRole(Roles::ROLE_SEARCH)): ?>
    <li class="nav-header">�����</li>
    <li><a href="./index.php?action=search_pupil">����������� �� ������
            ����������� ��� ���</a></li>
    <li><a href="./index.php?action=search_act">����������� ����������
            �������� ������</a></li>
<?php endif; ?>
<li class="divider"></li>
<li><a href="./index.php?action=help">�������</a></li>
<?php if ($C->userHasRole(Roles::ROLE_BUH)): ?>
    <li class="divider"></li>


    <li class="nav-header">�����������</li>
    <li><a href="./index.php?action=buh_check_univer">������ ����� �������� ������</a></li>
    <li><a href="./index.php?action=report_check_univer">������ ����� �������� ������</a></li>
    <li><a href="./index.php?action=buh_dubl_month">������ ����� �������� ������ �� ����������</a></li>

<?php endif; ?>
<?php
if (
$C->userHasRole(Roles::ROLE_CONTR_BUH)

):
    ?>
    <li><a href="./index.php?action=buh_search_act">����� ����� �������� ������</a></li>
    <li><a href="./index.php?action=buh_search_man">����� ����������� �� ������ ����������� ��� ���</a></li>
<? endif ?>
<li class="divider"></li>
<li><a href="/">����� �� �������</a></li>