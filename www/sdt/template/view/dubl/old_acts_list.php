<?php
/** @var Act $act */
$act = current($list);
if (empty($act)) {
    echo '<h2>���������� �� �������</h2>';

    return;
}


$listing = ' onchange="filter()"><option value=2>���</option><option value=1>��</option><option value=0>���</option></select>';
?>
<style>
    .sel {
        overflow: hidden;
        box-shadow: 0 2px 3px rgba(0, 0, 0, 0.3);
        border-radius: 5px;
        width: 60px;
        height: 22px;
        background: url('content/img/filter_s.png') no-repeat scroll 43px 6px rgba(0, 0, 0, 0)
    }

    .sel select {
        font-size: 12px;
        height: 22px;
        padding: 0;
        width: 80px;
        background: none repeat scroll 0 0 rgba(0, 0, 0, 0);
    }


</style>


<!--    <h1>������ �������� ������ ������ - --><?php //echo $act->getUniversity(); ?><!-- - �� --><? //= $type ?><!--</h1>-->
<h1 style="color:red">���������� ������������� ���� � �����!</h1>
<h3>����� <?= count($list) ?></h3>

<table class="table table-bordered  table-striped">
    <thead>
    <tr>
        <!--           <th valign="top">
                       <nobr>�</nobr>
                   </th>-->
        <th valign="top">
            �����������
        </th>
        <th valign="top">�������� ���������� ������</th>
        <th valign="top">�������� ��������� ������</th>

        <th>���� ��������</th>
        <th>���� ������������</th>
        <th>������� ������ ����������</th>
        <th>��������� ����</th>
        <th>��������</th>
<th></th>
    </tr>
    </thead>
    <tbody>


    <?php


    foreach ($list as $item):

        ?>
        <tr>
            <td><?= $item['fio'] ?></td>
            <td><?= $item['lc_name'] ?></td>
            <td><?= $item['gc_name'] ?></td>

                <? if (!empty($item['act'])) {
                    echo $this->import('dubl/old_act_table_view', array('item' => $item['act']));
                } else { ?>
            <td colspan="6">
                    ��� ���������� ��������� ����������� ���������� ��������� � ����� �������� ������ (<?= $item['act_id'] ?>), ��������� ������ - <?= $item['gc_name'] ?>.
                    ��������� � ���� ������� ��� �������� � ����� ��������� ������.
            </td>
                <? } ?>


        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
