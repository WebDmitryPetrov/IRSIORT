<?php


/** @var Act $act */
$act=current($list);
if(empty($act))
{
    echo '<h2>���������� �� �������</h2>';
    return;
}

$university = $act->getUniversity();

?>
<h1>������ ���������� ������ - <?php
    echo $university; ?>.</h1>
<h3>����� <?=count($list)?></h3>

<table class="table table-bordered  table-striped">
    <thead>
    <tr>
        <th valign="top">
            <nobr>�</nobr>
        </th>
        <th valign="top">���� ����</th>

        <th valign="top">���� ������������</th>
        <th valign="top">C���<br>�����/����</th>
        <th valign="top">��������</th>


        <th valign="top">�����������</th>
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

echo $this->import('acts/act_table_popups',array('signings'=>$signings,'university'=>$university));

