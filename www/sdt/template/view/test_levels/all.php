<a class="btn btn-primary btn-small" href="index.php?action=test_levels_add">��������</a>
<table class="table table-bordered  table-striped">
    <tr>
        <th>�������</th>
        <!--<th>������</th>
        <th>������</th>
        <th>������� � ����������</th>
        <th>�����������</th>
        <th>�����������</th>-->
        <th>����� ����</th>
        <th>�������</th>
        <th>���������</th>
        <th colspan="2">�������</th>
    </tr>
    <?php

    foreach ($list as $item): ?>
        <? /** @var TestLevel $item */  ?>
        <tr>
            <td>
                <nobr>
                    <?php echo $item->caption; ?>
                </nobr>
            </td>

            <td>
                <?php echo $item->total; ?>
            </td>
            <td>
                <?php echo $item->price; ?>
            </td>
            <td>
                <?php echo $item->sub_test_price; ?>
            </td>
            <td>
                <a class="btn btn-warning btn-small"
                   href="index.php?action=test_levels_edit&id=<?php echo $item->id; ?>">�������������</a>
            </td>
            <td>
                <a class="btn btn-danger btn-small" onclick="return confirm('�� �������?');"
                   href="index.php?action=test_levels_delete&id=<?php echo $item->id; ?>">�������</a>
            </td>

        </tr>

        <?php endforeach;?>

</table>