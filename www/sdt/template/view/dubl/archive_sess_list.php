<?php
/** @var University $univer */
/** @var Act $act */
$signings = ActSignings::get4VidachaCert();
$act = current($list);
//if (empty($act)) {
//    echo '<h2>Документов не найдено</h2>';
//    return;
//}
$horg=$univer->getHeadCenter()->horg_id;


?>
    <h1>Архивный список дубликатов центра - <?php echo $univer; ?></h1>
<? if ($univer->parent_id): ?>
    <h2>Партнёр: <?= $univer->getParent()->name ?></h2>
<? endif ?>

    <h3>Всего <?= count($list) ?></h3>

    <table class="table table-bordered  table-striped">
        <thead>
        <tr>

            <th valign="top">Дата создания Акта</th>

            <th valign="top">Cчет<br>номер/дата<br>сумма</th>



            <? /*<th valign="top">Комментарий</th>*/ ?>
            <th valign="top">&nbsp;</th>
        </tr>
        </thead>
        <tbody>


        <?php



        foreach ($list as $item):
            /** @var Act $item */
            ?>

            <?php echo $this->import('dubl/archive_act_table_view', array('item' => $item)); ?>

        <?php endforeach; ?>
        </tbody>
    </table>


<?php

echo $this->import('acts/act_table_popups', array('signings' => $signingsInvoice,'university'=>$univer));