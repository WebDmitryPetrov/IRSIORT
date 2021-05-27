<?php
/** @var Controller $C */
//var_dump($C->getCurrentRole());
?>
<?php if ($C->userHasRole(Roles::ROLE_CENTER_EXTERNAL)): ?>
    <li><a href="./index.php?action=act_add">����� �������� ������</a></li>
    <li><a href="./index.php?action=act_fs_list">�������� ������ � ������</a></li>
    <li><a href="./index.php?action=act_univer_on_check">�������� ��������</a></li>
    <li><a href="./index.php?action=act_third_list">����������� �������� ������</a></li>
<?php endif; ?>

<?php
if (
    $C->userHasRole(Roles::ROLE_CENTER_EDITOR)
    ||
    $C->userHasRole(Roles::ROLE_ROOT)
    ||
    $C->userHasRole(Roles::ADMIN_HEAD)
    /*diff8*/||
    $C->userHasRole(Roles::ROLE_FMS_ADMIN)
    ||
    $C->userHasRole(Roles::ROLE_STATISTICS)
):
    ?>
    <li class="nav-header">�����������</li>
    <?php if ($C->userHasRole(Roles::ROLE_CENTER_EDITOR)): ?>
    <li><a href="./index.php?action=universities">����</a></li>
    <li><a href="./index.php?action=user_list">���������� ��������������</a></li>
    <li><a href="./index.php?action=current_head_center_view">���������� � �������� ������</a></li>
    <li><a href="./index.php?action=current_head_center_text_view">���������� ��� ������</a></li>
    <li><a href="./index.php?action=signing_list">�������������</a></li>
<?php endif; ?>
    <?php if ($C->userHasRole(Roles::ADMIN_HEAD)): ?>
    <li><a href="./index.php?action=user_type_list">���� �������������</a></li>
    <li><a href="./index.php?action=groups_list">������ �����</a></li>
<?php endif; ?>
    <?php if ($C->userHasRole(Roles::ROLE_ROOT)): ?>
    <li><a href="./index.php?action=head_center">�������� ������</a></li>
    <li><a href="./index.php?action=head_centers">�������������� ��������� �������</a></li>
    <li><a href="./index.php?action=edit_user_list">�������������� �������������</a></li>
    <li><a href="./index.php?action=test_levels">������ ������������</a></li>
    <li><a href="./index.php?action=deleted_list">����������������</a></li>
    <li><a href="./index.php?action=federal_dc">����������� ������ � �������</a></li>

<?php endif; ?>



    <?php if ($C->userHasRole(Roles::ROLE_ROOT)): ?>
    <li><a href="./index.php?action=invalid_certs">���������������� ������</a></li>
<?php endif; ?>


    <li class="divider"></li>
<?php endif; ?>



<?php if ($C->userHasRole(Roles::ROLE_SEARCH)): ?>
<li class="nav-header">�����</li>
    <?php if ($C->userHasRole(Roles::ROLE_ROOT)): ?>
        <li><a href="./index.php?action=search_pupil_range_annul">�� ��������� ������� ��� ���������</a></li>
    <?php endif; ?>
<li><a href="./index.php?action=search_pupil">�����������</a></li>
<li><a href="./index.php?action=search_act">���</a></li>
<?php endif; ?>





<li class="divider"></li>
<li><a href="./index.php?action=help">�������</a></li>
<?php if ($C->userHasRole(Roles::ROLE_BUH)): ?>
<li class="divider"></li>
<li class="nav-header">�����������</li>
<li><a href="./index.php?action=buh_check_univer">�����</a></li>
<?php endif; ?>
<li class="divider"></li>
<li><a href="/">����� �� �������</a></li>
