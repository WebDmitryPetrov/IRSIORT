<?php
/** @var Act $item */
$signings = ActSignings::get4VidachaCert();
$meta = $item->getMeta();

$status = array();
$status['setblanks'] = $item->isSetBlanks() ? 1 : 0;
$status['setinvoice'] = $item->isSetInvoice() ? 1 : 0;
$status['paid'] = $item->isPaid() ? 1 : 0;

//$api_enabled = $item->getUniversity()->api_enabled;
//$head_org_id = $item->getUniversity()->getHeadCenter()->id;

?>

    <td class="js-act-date"><?= $C->date($item->created) ?></td>


    <td><?php echo $C->date($item->testing_date) ?>    </td>


    <td><?php echo $status['setblanks'] ? 'Да' : 'Нет'; ?></td>
    <td><?php echo $status['setinvoice'] ? 'Да' : 'Нет'; ?><br>
        <?php if (strlen($item->invoice)):
            echo $item->invoice_index . '/' . $item->invoice ?>
            <br><?php echo $C->date($item->invoice_date) . '<br>'; endif; ?>
        <?php echo '<strong>' . $item->amount_contributions . '</strong> руб.'; ?>
    </td>
    <td><?php echo $status['paid'] ? 'Да' : 'Нет'; ?></td>

<td class="">
    <?php if (!$item->isBlocked() || $item->isCanEdit()): ?>
        <a target="_blank" class="btn btn-info  btn-block"
           href="index.php?action=act_received_view&id=<?php echo $item->id; ?>">Карточка акта</a>
        <div></div>


        <?php if ($C->userHasRole(
            Roles::ROLE_SUPERVISOR
        )
        ): //сделал так, потому что эта роль есть только у супервизора

            //if (!$item->isToArchive())
            if (
            !$item->isToArchive()
            ) {
                $b_class = "disabled";
                $b_href = "onclick=\"return confirm('Недоступно! Обработка документа не завершена');\">";
            } else {
                $b_class = "";
                $b_href = "onclick=\"return confirm('Вы уверены?');\"
                    href=\"index.php?action=set_archive&id=$item->id\">";
            }

            //ActTest::calculateState($item);
            //echo $_SESSION['user_type_id'];
            ?>
            <div></div>
            <a class="btn btn-info " target="_blank" href="./index.php?action=act_list&uid=<?php echo $item->getUniversity()->id; ?>&type=<?php echo $item->test_level_type_id; ?>">Все акты центра</a>

            <div></div> <a
            class="btn btn-danger  btn-block <?= $b_class; ?>" <?= $b_href; ?>
            В архив</a>


        <? endif; ?>
    <? endif; ?>
</td>

