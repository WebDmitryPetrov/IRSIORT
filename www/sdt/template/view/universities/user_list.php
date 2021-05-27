<h1>������ ������������� ��� �������������� ���� �� ��������� ������</h1>
<a class="btn btn-success btn-small" href="index.php?action=user_create">��������</a>

<table class="table table-bordered  table-striped">
    <?php

    foreach ($users as $item): ?>
        <tr>
            <td>
                <?php echo $item['caption']; ?>
            </td>
            <td>
                <a class="btn btn-info btn-small" href="index.php?action=user_list_edit&id=<?php echo $item['id']; ?>">������
                    � ������� �������</a>
                <a class="btn btn-info btn-small"
                   href="index.php?action=user_rights_edit&id=<?php echo $item['id']; ?>">����</a>
                <a class="btn btn-primary btn-small" href="index.php?action=user_edit&id=<?php echo $item['id']; ?>">�������������</a>
                <? if ($_SESSION['u_id'] != $item['id']): ?>
                    <a class="btn btn-danger btn-small"
                       href="index.php?action=user_delete&id=<?php echo $item['id']; ?>">�������</a>
                <? endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>