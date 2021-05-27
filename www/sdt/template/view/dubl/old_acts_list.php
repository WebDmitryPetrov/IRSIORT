<?php
/** @var Act $act */
$act = current($list);
if (empty($act)) {
    echo '<h2>Документов не найдено</h2>';

    return;
}


$listing = ' onchange="filter()"><option value=2>Все</option><option value=1>Да</option><option value=0>Нет</option></select>';
?>
<style>
    .sel {
        overflow: hidden;
        box-shadow: 0 2px 3px rgba(0, 0, 0, 0.3);
        border-radius: 5px;
        width: 60px;
        height: 22px;
        background: url('content/img/filter_s.png') no-repeat scroll 43px 6px rgba(0, 0, 0, 0)
    }

    .sel select {
        font-size: 12px;
        height: 22px;
        padding: 0;
        width: 80px;
        background: none repeat scroll 0 0 rgba(0, 0, 0, 0);
    }


</style>


<!--    <h1>Список тестовых сессий центра - --><?php //echo $act->getUniversity(); ?><!-- - по --><? //= $type ?><!--</h1>-->
<h1 style="color:red">Переведите незавершенные акты в архив!</h1>
<h3>Всего <?= count($list) ?></h3>

<table class="table table-bordered  table-striped">
    <thead>
    <tr>
        <!--           <th valign="top">
                       <nobr>№</nobr>
                   </th>-->
        <th valign="top">
            Тестируемый
        </th>
        <th valign="top">Название локального центра</th>
        <th valign="top">Название головного центра</th>

        <th>Дата создания</th>
        <th>Дата тестирования</th>
        <th>Введены номера документов</th>
        <th>Выставлен счет</th>
        <th>Оплачено</th>
<th></th>
    </tr>
    </thead>
    <tbody>


    <?php


    foreach ($list as $item):

        ?>
        <tr>
            <td><?= $item['fio'] ?></td>
            <td><?= $item['lc_name'] ?></td>
            <td><?= $item['gc_name'] ?></td>

                <? if (!empty($item['act'])) {
                    echo $this->import('dubl/old_act_table_view', array('item' => $item['act']));
                } else { ?>
            <td colspan="6">
                    Для оформления дубликата сертификата необходимо отправить в архив тестовую сессию (<?= $item['act_id'] ?>), головного центра - <?= $item['gc_name'] ?>.
                    Свяжитесь с этим центром для отправки в архив указанной сессии.
            </td>
                <? } ?>


        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
