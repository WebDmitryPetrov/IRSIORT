<?php


/** @var Act $act */
$act=current($list);
if(empty($act))
{
    echo '<h2>Документов не найдено</h2>';
    return;
}



?>
<h1>Список документов центра - <?php echo $act->getUniversity(); ?>.</h1>
<h3>Всего <?=count($list)?></h3>

<table class="table table-bordered  table-striped">
    <thead>
    <tr>
        <th valign="top">
            <nobr>№</nobr>
        </th>
        <th valign="top">Дата создания Акта</th>

        <th valign="top">Дата тестирования</th>
        <th valign="top">Cчет<br>номер/дата</th>
        <th valign="top">Оплачено</th>


        <th valign="top">Комментарий</th>
        <th valign="top" class="button-width-50">&nbsp;</th>
    </tr>
    </thead>
    <tbody>


    <?php

    foreach ($list as $item):
        /** @var Act $item */
        ?>
        <?php echo $this->import('acts/act_table_view',array('item'=>$item)); ?>

        <?php endforeach;?>
    </tbody>
</table>

<?php

echo $this->import('acts/act_table_popups',array('signings'=>$signings));
