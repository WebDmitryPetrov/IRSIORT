<?php
/**
 * Created by JetBrains PhpStorm.
 * User: LWR
 * Date: 26.01.15
 * Time: 18:54
 * To change this template use File | Settings | File Templates.
 */
?>

<a class="btn btn-success" href="./index.php?action=reports_edit_list">Редактировать список отчетов</a>
<br>
<br>
<ol class="statist-list">
    <li>

    <ul>
        <li> <a href="./index.php?action=acts_to_archive_list">Сброс полученных тестовых сессий по каждому головному центру в
            отдельности в Архив до введенной даты создания тестовой сессии</a></li>
        <li> <a href="./index.php?action=acts_to_archive_list_dubl">Сброс полученных АКТОВ ПО ДУБЛИКАТАМ по каждому головному центру в
            отдельности в Архив до введенной даты создания тестовой сессии</a></li>
    </ul>

    </li>
    <?
    foreach ($list as $item):
        ?>
        <li>
            <img src="../img/<?= $item->visible ? 'eye.png' : 'eye-close.png'; ?>">
            <a href="./index.php?action=<?= $item->action_name; ?>"><?= $item->caption; ?> </a></li>

        <?
    endforeach;
    ?>
</ol>
<script>
    $(function () {
        $('.statist-list li').each(function (i, item) {
           var current = i+1;
            if (_.contains([1, /*2,*/ 4, 6, 7, 8, 10, 11, 13, 15, 17], current)) {
                $(item).css('padding-bottom', '12px');
            }
            if (_.contains([19, 25, 27], current)) {
                $(item).css('padding-bottom', '24px');
            }
        });
    });
</script>