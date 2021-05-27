<?php
/** @var Controller $C */
?>
<?php if ($C->userHasRole(Roles::ROLE_CENTER_EXTERNAL)): ?>
    <?php if ($C->userHasRole(Roles::ROLE_CENTER_EXTERNAL_API)): ?>
        <li><a href="./api.php?action=dics">Справочники</a></li>
        <li><a href="./api.php?action=xml_upload">Загрузить файл</a></li>
        <li><a href="./index.php?action=act_fs_list">Тестовые сессии в работе</a></li>
    <?php else: ?>
        <li><a href="./index.php?action=act_choose">Новая тестовая сессия</a></li>
        <li><a href="./index.php?action=act_fs_list">Тестовые сессии в работе</a></li>
        <li><a href="./index.php?action=act_univer_on_check">Проходят проверку</a></li>
        <li><a href="./index.php?action=acts_print_list">Просмотр и распечатка документов тестовых сессий</a></li>

        <li><a href="./index.php?action=loc_archive">Оповещения по сертификатам</a></li>

        <li><a href="./dubl.php?action=dubl">Заказать дубликат</a></li>
        <li><a href="./dubl.php?action=loc_archive">Оповещения по дубликатам</a></li>
        <li><a href="./index.php?action=loc_notes">Список выданных справок</a></li>
        <!--        <li><a href="./index.php?action=message">Сообщения</a></li>-->
    <? endif; ?>
<?php endif; ?>


<?php
if (
    $C->userHasRole(Roles::ROLE_CENTER_EDITOR)
    ||
    $C->userHasRole(Roles::ROLE_SIGNER_MANAGER)
):
    ?>
    <li class="nav-header">Справочники</li>
    <? if (
$C->userHasRole(Roles::ROLE_CENTER_EDITOR)
): ?>
    <li><a href="./index.php?action=universities">Локальные центры</a></li>
    <li><a href="./index.php?action=change_price_univers">Изменение цен уровней тестирования</a></li>
    <!--    <li><a href="./index.php?action=user_list">Управления пользователемя</a></li>-->
    <li><a href="./index.php?action=current_head_center_view">Информация о головном центре</a></li>
    <li><a href="./index.php?action=current_head_center_text_view">Переменные для печати</a></li>
<? endif; ?>
    <? if (
$C->userHasRole(Roles::ROLE_SIGNER_MANAGER)

): ?>
    <li><a href="./index.php?action=signing_list">Подписывающие</a></li>
    <li><a href="./index.php?action=frdo_excel_reports_list">Отчеты ФРДО</a></li>
<? endif; ?>

    <? if (
$C->userHasRole(Roles::ROLE_CENTER_EDITOR)

): ?>
    <li><a href="./index.php?action=edit_user_list">Редактирование пользователей</a></li>
    <li><a href="./index.php?action=certificate_type_list">Управление сертификатами</a></li>
<? endif; ?>

    <li class="divider"></li>
<?php endif; ?>


<?php
if (
$C->userHasRole(Roles::ROLE_CERTIFICATE_MANAGER)

):
    ?>
    <li class="nav-header">Справочники</li>
    <li><a href="./index.php?action=certificate_type_list">Управление сертификатами</a></li>
    <li class="divider"></li>
<?php endif; ?>


<?php /* УДАЛИИТЬ
if (
$C->userHasRole(Roles::ROLE_ADM_BSO)

):
    ?>
    <li class="nav-header">Справочники</li>
    <li><a href="./index.php?action=certificate_type_list">Управление сертификатами</a></li>

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
    <li class="nav-header">Тестовые сессии</li>
<? endif; ?>
<?php
if ($C->userHasRole(Roles::ROLE_CENTER_FOR_CHECK)):
    ?>
    <li><a href="./index.php?action=act_universities_second">Проверка
            тестовых сессий
            от локальных центров
        </a></li>
<? endif; ?>

<? if ($C->userHasRole(Roles::ROLE_CENTER_FOR_CHECK) ||
    $C->userHasRole(Roles::ROLE_CENTER_RECEIVED) ||
    $C->userHasRole(Roles::ROLE_CENTER_FOR_PRINT) ||
    $C->userHasRole(Roles::ROLE_CENTER_PRINT) ||
    $C->userHasRole(Roles::ROLE_CENTER_WAIT_PAYMENT)
):
    ?>
    <li><a href="./index.php?action=rework_list">Находятся на доработке
            в локальных центрах</a></li>
    <!-- <li><a href="./index.php?action=checked_list">Находятся на оформлении
             в локальных центрах</a></li>-->
<? endif; ?>

<? if ($C->userHasRole(Roles::ROLE_CENTER_RECEIVED) ||
    $C->userHasRole(Roles::ROLE_CENTER_PRINT) ||
    $C->userHasRole(Roles::ROLE_CENTER_WAIT_PAYMENT) ||
    $C->userHasRole(Roles::ROLE_CERTIFICATE_MANAGER)
):
    ?>
    <li class="divider"></li>
    <li><a href="./index.php?action=act_universities&type=1">Формирование печатных
            форм документов
            по лингводидактическому
            тестированию
        </a></li>
    <li><a href="./index.php?action=act_universities&type=2">Формирование печатных
            форм документов
            по интеграционному экзамену

        </a></li>


<? endif; ?>


<?php /*
if ($C->userHasRole(Roles::ROLE_CENTER_RECEIVED)):
    ?>
    <li><a href="./index.php?action=act_universities_received">Полученные
            <br>
            <small>(Печатать счет)</small>
        </a></li>
<? endif; ?>

<?php
if ($C->userHasRole(Roles::ROLE_CENTER_PRINT)):
    ?>
    <li><a href="./index.php?action=act_universities_print">Печать
            <br>
            <small>(Ввести номера бланков)</small>
        </a></li>
<? endif; ?>
<?php
if ($C->userHasRole(Roles::ROLE_CENTER_WAIT_PAYMENT)):
    ?>
    <li><a href="./index.php?action=act_universities_wait">Ждут оплаты
            <br>
            <small>(Ввести номер платежки)</small>
        </a></li>
<? endif;  */ ?>
<?php
if ($C->userHasRole(Roles::ROLE_CENTER_ARCHIVE)):
    ?>
    <li><a href="./index.php?action=act_universities_archive">Архив</a></li>

<? endif; ?>





<?php
if ($C->userHasRole(Roles::ROLE_SUPERVISOR)):
    ?>
    <li class="divider"></li>
    <li><a href="./dubl.php?action=dubl_act_universities_received&type=1">Оформить дубликат
            по лингводидактическому
            тестированию</a></li>
    <li><a href="./dubl.php?action=dubl_act_universities_received&type=2">Оформить дубликат
            по интеграционному экзамену</a></li>
<? endif; ?>
<?php
if ($C->userHasRole(Roles::ROLE_CENTER_ARCHIVE)):
    ?>

    <li><a href="./dubl.php?action=dubl_lc_archive">Архив дубликатов</a></li>
<? endif; ?>


<!--<li><a href="./index.php?action=act_paid">Отправленные</a></li> -->
<li class="divider"></li>

<?php
if ($C->userHasRole(Roles::ROLE_REPORT)): ?>
    <li class="nav-header">Отчеты</li>
    <li><a href="./index.php?action=otch_country">Количество тестируемых из каждой страны</a></li>
    <li><a href="./index.php?action=report_not_insert_numbers">Сессии с не введенными номерами сертификатов и справок по
            интеграционному экзамену </a></li>
    <li><a href="./index.php?action=report_not_insert_numbers_rki">Сессии с не введенными номерами сертификатов и
            справок по лингводидактическому тестированию </a></li>


    <li class="divider"></li>
<?php endif; ?>

<?php if ($C->userHasRole(Roles::ROLE_SEARCH)): ?>
    <li class="nav-header">Поиск</li>
    <li><a href="./index.php?action=search_pupil">Тестируемых по номеру
            сертификата или ФИО</a></li>
    <li><a href="./index.php?action=search_act">Электронных документов
            тестовых сессий</a></li>
<?php endif; ?>
<li class="divider"></li>
<li><a href="./index.php?action=help">Справка</a></li>
<?php if ($C->userHasRole(Roles::ROLE_BUH)): ?>
    <li class="divider"></li>


    <li class="nav-header">Бухгалтерия</li>
    <li><a href="./index.php?action=buh_check_univer">Данные актов тестовых сессий</a></li>
    <li><a href="./index.php?action=report_check_univer">Реестр актов тестовых сессий</a></li>
    <li><a href="./index.php?action=buh_dubl_month">Данные актов тестовых сессий по дубликатам</a></li>

<?php endif; ?>
<?php
if (
$C->userHasRole(Roles::ROLE_CONTR_BUH)

):
    ?>
    <li><a href="./index.php?action=buh_search_act">Поиск актов тестовых сессий</a></li>
    <li><a href="./index.php?action=buh_search_man">Поиск тестируемых по номеру сертификата или ФИО</a></li>
<? endif ?>
<li class="divider"></li>
<li><a href="/">Выход на главную</a></li>