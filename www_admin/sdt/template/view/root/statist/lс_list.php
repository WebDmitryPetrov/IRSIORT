<ul>
    <li><a href="./index.php?action=report_lc_list&type=1">��������� ������ �� ���� �� ����������� �������</a></li>
    <li><a href="./index.php?action=report_lc_list&type=2">��������� ������ �� ���� �� ���</a></li>
    <li><a href="./index.php?action=report_lc_list&type=3">��������� ������ �� ���� ����������� �������</a></li>
    <li><a href="./index.php?action=report_lc_list&type=4">��������� ������ �� ���� ���</a></li>
</ul>
<?php

if (empty($result)) return;

?>
<h1><?=$name?></h1>
<table class="table table-bordered">
    <thead>
    <tr>
        <th>��������� �����</th>
        <th>�������� �����</th>
    </tr>
    </thead>
    <tbody>
    <? foreach ($result as $r): ?>
        <tr>
            <td><?= $r['name'] ?></td>
            <td><?= $r['short_name'] ?></td>

        </tr>


    <? endforeach; ?>

    </tbody>
</table>
