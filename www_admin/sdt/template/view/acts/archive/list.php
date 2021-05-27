<?php
/** @var Act $act */

$act=current($list);
if(empty($act))
{
    echo '<h2>Документов не найдено</h2>';
    return;
}
 ?>
    <h1>Архивный список документов центра -  <?php echo $act->getUniversity(); ?></h1>
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
        <th valign="top">&nbsp;</th>
    </tr>
    </thead>
    <tbody>


    <?php

    foreach ($list as $item):
        /** @var Act $item */
        ?>
    <tr>
        <td><?=$item->number?></td>
        <td><?=$C->date($item->created)?></td>


        <td><?php echo $C->date($item->testing_date) ?>
        </td>
        <td>   <?php if (strlen($item->invoice)):  echo $item->invoice_index.'/'.$item->invoice ?><br><?php echo $C->date($item->invoice_date); endif; ?>
        </td>
        <td><?php echo $item->paid ? 'Да' : 'Нет'; ?>
        </td>

        <td class="wrap"><?php echo $item->comment ?>
        </td>
        <td class="button-width-50">
            <a class="btn btn-info"
               href="index.php?action=act_received_view&id=<?php echo $item->id; ?>">Карточка</a>
            <br>

            <a
                    class="btn btn-primary "
                    href="index.php?action=act_archive_numbers&id=<?php echo $item->id; ?>">Печать сертификатов
                (справок)</a> <br>
            <a
                    class="btn btn-danger "
                    href="index.php?action=act_vidacha_cert&id=<?php echo $item->id; ?>">Печать ведомости выдачи
                сертификатов</a>



            <a data-id="<?php echo $item->id; ?>"
               class="btn invoice btn-warning" target="_blank"
               href="index.php?action=print_invoice&id=<?php echo $item->id; ?>">Печать счет</a>

        </td>
    </tr>

        <?php endforeach;?>
    </tbody>
</table>

<?php

echo $this->import('acts/act_table_popups',array('signings'=>$signings));