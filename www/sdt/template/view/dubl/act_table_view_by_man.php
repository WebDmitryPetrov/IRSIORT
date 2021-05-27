<?php
/** @var DublAct $item */


$signings = ActSignings::get4VidachaCert();


$status = array();
//$status['setblanks'] = $item->isSetBlanks() ? 1 : 0;
$status['setinvoice'] = $item->isSetInvoice() ? 1 : 0;
//$status['paid'] = $item->isPaid() ? 1 : 0;

$api_enabled = $item->getUniversity()->api_enabled;
$head_org_id = $item->getUniversity()->getHeadCenter()->id;

?>
<tr class="js-act-row
	<? /*
if ($item->is_printed):?>success<?endif;
echo ($status['setblanks'] == 1) ? ' blanks_on ' : ' blanks_off ';
echo ($status['setinvoice'] == 1) ? ' invoice_on ' : ' invoice_off ';
echo ($status['paid'] == 1) ? ' paid_on ' : ' paid_off ';
*/ ?>
	">
<!--    <td><a name="--><?//= $item->id ?><!--"></a><span class="js-act-number">--><?//= $item->number ?><!--</span></td>-->
    <td class="js-act-date"><?= $C->date($item->created) ?></td>


    <!--<td><?php /*echo $C->date($item->testing_date) */?>    </td>
    <td><?php
/*        if (!is_null($item->date_received) && $item->date_received != '0000-00-00 00:00:00') {
            echo $C->dateTime($item->date_received);
        } */?>    </td>-->
    <td>   <?php if (strlen($item->invoice)):  echo $item->invoice_index . '/' . $item->invoice ?>
            <br><?php echo $C->date($item->invoice_date) . '<br>'; endif; ?>
        <?php echo '<strong>' . count($item->getPeople())*$item->invoice_price . '</strong>'; ?>
    </td>
<!--    <td>--><?php //echo $status['setblanks'] ? 'Да' : 'Нет'; ?><!--</td>-->
    <td><?php echo $status['setinvoice'] ? 'Да' : 'Нет'; ?></td>
<!--    <td>--><?php //echo $status['paid'] ? 'Да' : 'Нет'; ?><!--</td>-->

    <?
    /*
    <td><?php echo $item->paid ? 'Да' : 'Нет'; ?></td>
    <td class="wrap text-success"><?php echo $item->comment ?></td>
    */
    ?>
    <td class="">



        <a class="btn btn-info  btn-block"
           href="dubl.php?action=dubl_act_table&id=<?php echo $item->id; ?>">Карточка</a>
        <div></div>

        <? if ($item->state==DublAct::STATE_IN_HC || $item->state==DublAct::STATE_PROCESSED): ?>
         <a data-id="<?php echo $item->id; ?>"
              class="btn invoice btn-primary  btn-block" target="_blank"
              href="dubl.php?action=dubl_act_numbers&id=<?php echo $item->id; ?>">Печать документов</a>
            <div></div>

<?/*
        <div class="btn-group">
            <a class="btn btn-warning
                     dropdown-toggle  btn-block" data-toggle="dropdown"
               href="#">Печать акта<span class="caret"></span></a>
            <ul class="dropdown-menu">
                <?php foreach (ActSignings::get4Act() as $sign): ?>
                <li><a target="_blank"
                       href="dubl.php?action=dubl_act_print&id=<?php echo $item->id; ?>&type=<?= $sign->id ?>"><?= $sign->caption ?></a>
                </li>
                <?php endforeach; ?>
                <ul>
        </div>
		
		
		
		
		
		<div></div>
		                <div class="btn-group">
                    <a
                        class="btn btn-primary  dropdown-toggle  btn-block" data-toggle="dropdown"
                        href="#">Печать ведомости выдачи
                        сертификатов <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <?php foreach ($signings as $sign): ?>
                            <li><a target="_blank"
                                   href="dubl.php?action=dubl_act_vidacha_cert&id=<?php echo $item->id; ?>&type=<?= $sign->id ?>"><?= $sign->caption ?></a>
                            </li>
                        <?php endforeach; ?>
                        <ul>
                </div>

<div></div>
                <div class="btn-group">
                    <a
                        class="btn btn-primary  dropdown-toggle  btn-block" data-toggle="dropdown"
                        href="#">Печать реестра выдачи сертификатов <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <?php foreach ($signings as $sign): ?>
                            <li><a target="_blank"
                                   href="dubl.php?action=dubl_act_vidacha_reestr&id=<?php echo $item->id; ?>&type=<?= $sign->id ?>"><?= $sign->caption ?></a>
                            </li>
                        <?php endforeach; ?>
                        <ul>
                </div>
		
		
		
		
		

            <a data-id="<?php echo $item->id; ?>"
               class="btn invoice btn-warning  btn-block" target="_blank"
               href="dubl.php?action=dubl_print_invoice&id=<?php echo $item->id; ?>">Печать счета</a>
            <div></div>

           <? if ($item->state != DublAct::STATE_PROCESSED){?>
            <a data-id="<?php echo $item->id; ?>"
               class="btn invoice btn-danger  btn-block"
               href="dubl.php?action=dubl_act_processed&id=<?php echo $item->id; ?>" onclick="return confirm('Вы уверены?')">Завершить сессию</a>
            <div></div>
        <?}?>


            <? else: ?>
        <? if ($item->state != DublAct::STATE_PROCESSED){?>
            <div></div>
            <a class="btn btn-success  btn-block"
               href="dubl.php?action=dubl_act_accept&id=<?php echo $item->id; ?>">Принять</a>
            <div></div>

            <button data-id="<?= $item->id ?>"
                    class="btn btn-danger js-act-rework-button btn-block">


                Отклонить
            </button>
            <?}?>

*/?>
        <? endif;?>




















    </td>
</tr>