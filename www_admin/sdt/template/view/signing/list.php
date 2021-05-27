<h1>������ ������������� ���������</h1>
<a class="btn btn-success btn-small" href="index.php?action=signing_create&h_id=<?=check_id($_GET['h_id'])?>">��������</a>

<table class="table table-bordered  table-striped">
    <thead>
    <tr>
        <th>���</th>
        <th>���������</th>

        <th>�����</th>
        <th>�����������</th>
        <th>��������� ������</th>
        <th>����</th>
        <th>���������� ��������� ������</th>
        <th>&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    <?php

    foreach ($items as $item):
        /** @var ActSigning $item */
        ?>

        <tr>
            <td>
                <?php echo $item->caption; ?>
            </td>
            <td>
                <?php echo $item->position; ?>
            </td>
            <td>
                <?php echo $item->invoice ? '<i class="icon-ok"></i>' : '<i class="icon-ban-circle"></i>'; ?>
            </td>
            <td>
                <?php echo $item->certificate ? '<i class="icon-ok"></i>' : '<i class="icon-ban-circle"></i>'; ?>
            </td>
            <td>
                <?php echo $item->vidacha_cert ? '<i class="icon-ok"></i>' : '<i class="icon-ban-circle"></i>'; ?>
            </td>
             <td>
                <?php echo $item->act ? '<i class="icon-ok"></i>' : '<i class="icon-ban-circle"></i>'; ?>
            </td>
            <td>
                <?php echo $item->aprove_vidacha_cert ? '<i class="icon-ok"></i>' : '<i class="icon-ban-circle"></i>'; ?>
            </td>
            <td>

                <a class="btn btn-primary btn-small" href="index.php?action=signing_edit&id=<?php echo $item->id; ?>&h_id=<?php echo $_GET['h_id'];?>">�������������</a>

                <a class="btn btn-danger btn-small"
                   href="index.php?action=signing_delete&id=<?php echo $item->id; ?>&h_id=<?php echo $_GET['h_id'];?>">�������</a>

            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>