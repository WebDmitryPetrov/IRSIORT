<?php
/** @var University $univer */
/** @var Act $act */
$signings = ActSignings::get4VidachaCert();
$act = current($list);
//if (empty($act)) {
//    echo '<h2>���������� �� �������</h2>';
//    return;
//}
$horg=$univer->getHeadCenter()->horg_id;


?>
    <h1>�������� ������ ���������� ������ - <?php echo $univer; ?></h1>
<? if ($univer->parent_id): ?>
    <h2>������: <?= $univer->getParent()->name ?></h2>
<? endif ?>

    <h3>����� <?= count($list) ?></h3>

    <table class="table table-bordered  table-striped">
        <thead>
        <tr>

            <th valign="top">���� �������� ����</th>

            <th valign="top">C���<br>�����/����<br>�����</th>



            <? /*<th valign="top">�����������</th>*/ ?>
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