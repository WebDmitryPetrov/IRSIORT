<div class="btn-group">
    <a class="btn btn-primary btn-small dropdown-toggle" data-toggle="dropdown" href="#">
        �������� (������� ���������� ���������)<span class="caret"></span></a>
    <ul class="dropdown-menu">
        <? for ($i = 1; $i <= 15; $i++): ?>
            <li><a href="index.php?action=test_levels_add&count=<?= $i ?>"><?= $i ?></a></li>
        <? endfor; ?>
    </ul>
</div>
<? if (!$all_levels): ?>
    <a class="btn btn-info" href="?action=test_levels&all=1">�������� ���</a>
<? else: ?>
    <a class="btn btn-info" href="?action=test_levels">������ ����������������</a>
<? endif ?>

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
        <? /** @var TestLevel $item */ ?>
        <tr class="<? if ($item->test_group != 1): ?>warning<? endif; ?>">
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

    <?php endforeach; ?>

</table>