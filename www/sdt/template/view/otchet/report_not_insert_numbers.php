<h1>������ � �� ���������� �������� ������������ � ������� <?= $type; ?></h1>
<table class="table table-bordered">
    <thead>
    <tr>
        <th>��</th>
        <th>ID ������</th>
        <th>���� ���������</th>
    </tr>
    </thead>
    <tbody>
    <? $i = 0;
    if ($items):
        foreach ($items as $item): ?>
            <tr>
                <td><?= ++$i ?></td>
                <td><?= $item['id']; ?></td>
                <td><?=
                    ($item['date_received'] == '0000-00-00 00:00:00' || is_null($item['date_received'])) ? '' : $C->dateTime($item['date_received']) ?></td>
            </tr>
        <? endforeach;
    endif; ?>
    </tbody>
</table>