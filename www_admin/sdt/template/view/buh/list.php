<h1 xmlns="http://www.w3.org/1999/html">Список присланных документов</h1>
<h3>Всего <?=count($list)?></h3>

<table class="table table-bordered  table-striped">
    <thead>
    <tr>
        <th valign="top">
            <nobr>№</nobr>
        </th>
        <th valign="top">Университет</th>
        <th valign="top">Номер договора</th>
        <th valign="top">Номер счета</th>
        <th valign="top">Дата счета</th>
        <th valign="top">Сумма счета</th>
        <th valign="top">Оплачено</th>

    </tr>
    </thead>
    <tbody>


    <?php

    foreach ($list as $item):
        /** @var Act $item */
        ?>
    <tr>
        <td><?=$item->number?></td>


        <td class="wrap"><?php echo $item->getUniversity() ?>
        </td>
        <td class="wrap"><?php echo $item->getUniversityDogovor() ?>
        </td>
         <td><?php echo $item->invoice ?>
        </td>
        <td><?php echo $C->date($item->invoice_date);?>
        </td>
        <td><?php echo $item->amount_contributions;?>
        </td>
        <td><?php echo $item->paid ? 'Да' : 'Нет'; ?>
        </td>



        <?php endforeach;?>
    </tbody>
</table>

