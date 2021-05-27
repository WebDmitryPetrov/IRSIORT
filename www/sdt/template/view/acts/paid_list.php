<h1>������ ���������� ������������</h1>
<h3>����� <?=count($list)?></h3>
<table class="table table-bordered  table-striped">
    <thead>
    <tr>
        <th valign="top"><nobr>�</nobr></th>
        <th valign="top">���� �������� ����</th>
        <th valign="top">�����������</th>
        <th valign="top">���� ������������</th>
        <th valign="top">����� �����</th>
        <th valign="top">���� �����</th>
        <th valign="top">�����������</th>
        <th valign="top">���� ��������    ��������� (� ������)</th>
        <th valign="top">�����������</th>
        <th valign="top">&nbsp;</th>
    </tr>
    </thead>
    <tbody>

    <?php

    foreach ($list as $item): ?>

    <tr>
        <td><?=$item->number?></td>
        <td><?=$C->date($item->created)?></td>

        <td><?php echo $item->getUniversity() ?>
        </td>
        <td><?php echo $C->date($item->testing_date) ?>
        </td>
        <td>24/<?php echo sprintf("%04d", intval($item->id));?>
        </td>
        <td><?php echo $item->invoice_date=='0000-00-00'?'&nbsp;': $C->date($item->invoice_date) ?>
        </td>
        <td><?php echo $item->responsible ?>
        </td>
        <td>&nbsp;</td>
        <td><?php echo $item->comment ?>
        </td>
        <td><a class="btn btn-info btn-small"
               href="index.php?action=act_paid_view&id=<?php echo $item->id; ?>">��������</a>
            <a class="btn btn-warning btn-small"
               href="index.php?action=act_fs_edit&id=<?php echo $item->id; ?>">�������������</a>
            <a class="btn btn-warning btn-small"
               href="index.php?action=act_numbers&id=<?php echo $item->id; ?>">������
                ����������</a> <a class="btn btn-info btn-small" target="_blank"
                                  href="index.php?action=act_vidacha_cert&id=<?php echo $item->id; ?>">���������
                ������ ������������</a>
            <a href="index.php?action=act_invalid&id=<?php echo $item->id; ?>" onclick="return confirm('�� �������?');" class="btn btn-danger  btn-small">��������</a>

        </td>
    </tr>

        <?php endforeach;?>
    </tbody>
</table>
