<?
?>
    <style>
        tr.type-head {
            font-weight: bold;
        }
    </style>
<? if (!empty($caption)): ?>
    <h1><?= $caption ?></h1>
<? else: ?>
    <h1>���������� ������ ��������� ������� ���� </h1>
<? endif ?>
    <form action="" method="POST">

        <label>�� :
            <div class="input-prepend date datepicker "
                 data-date="">
                <span class="add-on"><i class="icon-th"></i> </span> <input
                    class="input-small"
                    name="from"

                    readonly="readonly" size="16" type="text"
                    value="<?= $from ?>">
            </div>
        </label> <label>�� :
            <div class="input-prepend date datepicker "
                 data-date="">
                <span class="add-on"><i class="icon-th"></i> </span> <input
                    class="input-small"
                    name="to"

                    readonly="readonly" size="16" type="text"
                    value="<?= $to ?>">
            </div>
        </label>
        <input type="submit" value="�������������">
    </form>
<? if (!empty($search)): ?>
    <h1>���������� � �����������  � <?= $from ?> ��  <?= $to ?> </h1>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th><p>��������������� �����������</p></th>
            <th><p>���������� �����������-���������, � ������� ��������� ����������</p></th>
            <th><p>���������� ���������, �������� �� ����� ��������</p></th>
            <th colspan="10"><p>���������� ���������, ������� �������</p></th>
            <th><p>���������� �������� ������������</p></th>
            <th><p>���������� �� �������</p></th>
        </tr>
        <tr>
            <th><p>&nbsp;</p></th>
            <th><p>&nbsp;</p></th>
            <th><p>&nbsp;</p></th>
            <th><p>������� ��� ����������� ����������</p></th>
            <th><p>���� "������������" �1</p></th>
            <th><p>���� "�������" �2</p></th>
            <th><p>���� "������" �1</p></th>
            <th><p>���� "������" �2</p></th>
            <th><p>���� "������" �1</p></th>
            <th><p>���� "���������" �2</p></th>
            <th><p>������� ����������� (485) </p></th>
            <th><p>������� ����������� (730) </p></th>
            <th><p>����� ���������� </p></th>
            <th><p>&nbsp;</p></th>
            <th><p>&nbsp;</p></th>
        </tr>
        </thead>
        <tbody>
        <? foreach ($array as $item): ?>
            <tr>
                <td><?= $item['caption'] ?></td>
                <td><?= $item['data']['orgs'] ?></td>
                <td>&nbsp;</td>
                <td><?= $item['data']['certificate'][0] ?></td>
                <td><?= $item['data']['certificate'][1] ?></td>
                <td><?= $item['data']['certificate'][2] ?></td>
                <td><?= $item['data']['certificate'][3] ?></td>
                <td><?= $item['data']['certificate'][4] ?></td>
                <td><?= $item['data']['certificate'][5] ?></td>
                <td><?= $item['data']['certificate'][6] ?></td>
                <td><?= $item['data']['certificate'][7] ?></td>
                <td><?= $item['data']['certificate'][8] ?></td>

                <td><?= array_sum($item['data']['certificate']) ?></td>
                <td><?= $item['data']['certs'] ?></td>
                <td><?= array_sum($item['data']['note']) ?></td>
            </tr>
        <? endforeach ?>
        </tbody>
    </table>

<? endif; ?>