<h1>Тестовые сессии, проходящие проверку в Головном центре</h1>

<? if (count($list)):?>
<h3>Всего <?=count($list)?></h3>

<table class="table table-bordered  table-striped">
    <thead>
    <tr>
        <th valign="top"><nobr>№</nobr></th>
        <th valign="top">Дата создания</th>

        <th valign="top">Дата тестирования</th>
       <!-- <th valign="top">Номер счета</th>
        <th valign="top">Дата счета</th>
        -->
        <th valign="top">Cчет<br>номер/дата</th>
        <th valign="top">Исполнитель</th>

        <th valign="top">Комментарий</th>
<!--        <th valign="top">id</th>-->

    </tr>
    </thead>
    <tbody>


	<?php

foreach($list as $item): ?>
	<tr <!--class="<?/* if($item->is_changed_checker):*/?>error--><?/*endif;*/?>" data-id="<?=$item->id?>">
        <td><?=$item->number?></td>
        <td><?=$C->date($item->created)?></td>


        <td><?php echo $C->date($item->testing_date); ?>
        </td>
      <!--  <td><?php echo $item->invoice_date=='0000-00-00'?'&nbsp;': '24/'.sprintf("%04d", intval($item->id)); ?>
        </td>
        <td><?php echo $item->invoice_date=='0000-00-00'?'&nbsp;': $C->date($item->invoice_date) ?>
        </td>
        -->
    <td>
        <? if ($item->invoicePrinted()):
            echo $item->invoice_index . '/' . $item->invoice ?>
            <br><?= $C->date($item->invoice_date) ?>  <br>
            <strong> <?= $item->amount_contributions ?> руб.</strong>
        <? endif ?>
    </td>
        <td  class="wrap"><?php echo $item->responsible ?>
        </td>

        <td  class="wrap text-error"><?php echo $item->comment ?>
        </td>

<!--        <td  class="wrap">--><?php //echo $item->id ?>
        </td>

	</tr>

	<?php  endforeach;?>
</tbody>
</table>
<? else: ?>
    <h3>Тестовые сессии отсутствуют</h3>
<? endif; ?>

