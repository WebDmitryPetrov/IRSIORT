<?php
/** @var DublAct $item */
$signings = ActSignings::get4VidachaCert();


$status = array();

$status['setinvoice'] = $item->isSetInvoice() ? 1 : 0;


$api_enabled = $item->getUniversity()->api_enabled;
$head_org_id = $item->getUniversity()->getHeadCenter()->id;

?>
<tr class="js-act-row
	<?

echo ($status['setinvoice'] == 1) ? ' invoice_on ' : ' invoice_off ';

?>
	">

    <td class="js-act-date"><?= $C->date($item->created) ?></td>


    <td>   <?php if (strlen($item->invoice)): echo $item->invoice_index . '/' . $item->invoice ?>
            <br><?php echo $C->date($item->invoice_date) . '<br>'; endif; ?>
        <?php echo '<strong>' . count($item->getPeople()) * $item->invoice_price . '</strong>'; ?>
    </td>


    <td class="">
 <?   if ($item->summary_table_id):
    ?>



        <a target="_blank" class=" btn btn-color-black   btn-block btn-danger"
           href="dubl.php?action=dubl_act_summary_table&id=<?= $item->id; ?>" onclick="">Просмотреть/напечатать
            Сводный протокол</a>


<?

endif;?>

        <a class="btn btn-info  btn-block"
           href="dubl.php?action=dubl_act_table_archive&id=<?php echo $item->id; ?>">Карточка</a>




        <a data-id="<?php echo $item->id; ?>"
           class="btn invoice btn-primary  btn-block" target="_blank"
           href="dubl.php?action=dubl_act_numbers_archive&id=<?php echo $item->id; ?>">Номера дубликатов и
            печать</a>



    </td>
</tr>