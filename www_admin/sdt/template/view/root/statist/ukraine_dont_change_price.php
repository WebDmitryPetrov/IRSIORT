<h1>����� �� ���������������� � ������� �� ����������� ������ � ������ 500 ������ �� ����</h1>
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
    <h1>����� �� ���������������� � ������� �� ����������� ������ � ������ 500 ������ �� ����
        � <?= $from ?> �� <?= $to ?> </h1>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>������������ �����������</th>
            <th>���� �������� ������</th>
            <th>� �������� ������</th>
            <th>���-�� �������</th>
            <th>���� ����</th>
            <th>� �����</th>


        </tr>

        </thead>
        <tbody>
        <? foreach ($array as $item): ?>
            <tr>

                <th><?= $item['caption'] ?></th>
                <?= str_repeat("<th>&nbsp;</th>", 5 )?>
            </tr>
            <? foreach ($item['ts'] as $center): ?>
                <tr>
                    <td><?= $center['caption'] ?></td>
                    <td><?= $C->date($center['created']) ?></td>
                    <td><?= $center['number'] ?></td>
                    <td><?= $center['count'] ?></td>
                    <td><?= $C->date($center['act_date']) ?></td>
                    <td><?= $center['invoice'] ?></td>
                </tr>
            <? endforeach ?>
        <? endforeach ?>
        </tbody>
    </table>

<? endif; ?>