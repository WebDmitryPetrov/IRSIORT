<? $itog = array(
    'head'=>array(
        'acts'=>0,
        'people'=>0,
        'contributions'=>0,
        'invoices'=>0,
        'invoice_people'=>0,
        'invoice_contribution'=>0,
    ),
    'local'=>array(
        'acts'=>0,
        'people'=>0,
        'contributions'=>0,
        'invoices'=>0,
        'invoice_people'=>0,
        'invoice_contribution'=>0,
    ),
);
?>
<style>
    tr.type-head{
        font-weight: bold;
    }
</style>
<?
$reportName = !empty($caption) ? $caption : 'Без названия';
?>

<h1><?=$reportName?></h1>
    <form action="" method="POST">

      <label>от :
        <div class="input-prepend date datepicker "
             data-date=""    >
            <span class="add-on"><i class="icon-th"></i> </span> <input
                class="input-small"
                name="from"

             readonly="readonly" size="16" type="text"
                value="<?=$from?>">
        </div></label>  <label>До :
        <div class="input-prepend date datepicker "
             data-date=""    >
            <span class="add-on"><i class="icon-th"></i> </span> <input
                class="input-small"
                name="to"

              readonly="readonly" size="16" type="text"
                value="<?=$to?>">
        </div></label>
        <input type="submit" value="Отфильтровать">
    </form>
<?if (!empty($search)):?>
<table class="table table-bordered">
    <tr>
        <th>Название локального центра</th>
        <th>Количество тестовых сессий</th>
        <th>Количество людей</th>
        <th>Сумма отчислений без учета выставленных счетов</th>
        <th>Количество выставленных счетов</th>
        <th>Количество людей  по выставленным счетам</th>
        <th>Сумма отчислений по выставленным счетам</th>
    </tr>
    <?foreach ($array as $item):?>

        <?$type= $item['is_head']?'head':'local'?>
    <tr class="type-<?=$type?>">
        <td><?=$item['name']?></td>
        <td><?=$item['acts']?></td>
        <td><?=$item['people']?></td>
        <td><?=$item['contributions']?></td>
        <td><?=$item['invoices']?></td>
        <td><?=$item['invoice_people']?></td>
        <td><?=$item['invoice_contribution']?></td>

    </tr>
        <?
        $itog[$type]['acts']+=$item['acts'];
        $itog[$type]['people']+=$item['people'];
        $itog[$type]['contributions']+=$item['contributions'];
        $itog[$type]['invoices']+=$item['invoices'];
        $itog[$type]['invoice_people']+=$item['invoice_people'];
        $itog[$type]['invoice_contribution']+=$item['invoice_contribution'];
        ?>

<? endforeach;?>
    <tr>
        <? $type='head';?>
        <td>Итог по головным</td>
        <td><?= $itog[$type]['acts']?></td>
        <td><?= $itog[$type]['people']?></td>
        <td><?= $itog[$type]['contributions']?></td>
        <td><?= $itog[$type]['invoices']?></td>
        <td><?= $itog[$type]['invoice_people']?></td>
        <td><?= $itog[$type]['invoice_contribution']?></td>

    </tr>

    <tr>
        <? $type='local';?>
        <td>Итог по локальным</td>
        <td><?= $itog[$type]['acts']?></td>
        <td><?= $itog[$type]['people']?></td>
        <td><?= $itog[$type]['contributions']?></td>
        <td><?= $itog[$type]['invoices']?></td>
        <td><?= $itog[$type]['invoice_people']?></td>
        <td><?= $itog[$type]['invoice_contribution']?></td>

    </tr>
</table>
<? endif;?>