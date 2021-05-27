<?php
/** @var Controller $C */
//var_dump($C->getCurrentRole());
?>
<?php if ($C->userHasRole(Roles::ROLE_CENTER_EXTERNAL)): ?>
    <li><a href="./index.php?action=act_add">Новая тестовая сессия</a></li>
    <li><a href="./index.php?action=act_fs_list">Тестовые сессии в работе</a></li>
    <li><a href="./index.php?action=act_univer_on_check">Проходят проверку</a></li>
    <li><a href="./index.php?action=act_third_list">Проверенные тестовые сессии</a></li>
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
    <li class="nav-header">Справочники</li>
    <?php if ($C->userHasRole(Roles::ROLE_CENTER_EDITOR)): ?>
    <li><a href="./index.php?action=universities">ВУЗы</a></li>
    <li><a href="./index.php?action=user_list">Управление пользователями</a></li>
    <li><a href="./index.php?action=current_head_center_view">Информация о головном центре</a></li>
    <li><a href="./index.php?action=current_head_center_text_view">Переменные для печати</a></li>
    <li><a href="./index.php?action=signing_list">Подписывающие</a></li>
<?php endif; ?>
    <?php if ($C->userHasRole(Roles::ADMIN_HEAD)): ?>
    <li><a href="./index.php?action=user_type_list">Типы пользователей</a></li>
    <li><a href="./index.php?action=groups_list">Список ролей</a></li>
<?php endif; ?>
    <?php if ($C->userHasRole(Roles::ROLE_ROOT)): ?>
    <li><a href="./index.php?action=head_center">Головные центры</a></li>
    <li><a href="./index.php?action=head_centers">Редактирование локальных центров</a></li>
    <li><a href="./index.php?action=edit_user_list">Редактирование пользователей</a></li>
    <li><a href="./index.php?action=test_levels">Уровни тестирования</a></li>
    <li><a href="./index.php?action=deleted_list">Недействительные</a></li>
    <li><a href="./index.php?action=federal_dc">Федеральные округа и регионы</a></li>

<?php endif; ?>



    <?php if ($C->userHasRole(Roles::ROLE_ROOT)): ?>
    <li><a href="./index.php?action=invalid_certs">Недействительные бланки</a></li>
<?php endif; ?>


    <li class="divider"></li>
<?php endif; ?>



<?php if ($C->userHasRole(Roles::ROLE_SEARCH)): ?>
<li class="nav-header">Поиск</li>
    <?php if ($C->userHasRole(Roles::ROLE_ROOT)): ?>
        <li><a href="./index.php?action=search_pupil_range_annul">По диапазону бланков для аннуляции</a></li>
    <?php endif; ?>
<li><a href="./index.php?action=search_pupil">Тестируемый</a></li>
<li><a href="./index.php?action=search_act">Акт</a></li>
<?php endif; ?>





<li class="divider"></li>
<li><a href="./index.php?action=help">Справка</a></li>
<?php if ($C->userHasRole(Roles::ROLE_BUH)): ?>
<li class="divider"></li>
<li class="nav-header">Бухгалтерия</li>
<li><a href="./index.php?action=buh_check_univer">Счета</a></li>
<?php endif; ?>
<li class="divider"></li>
<li><a href="/">Выход на главную</a></li>
