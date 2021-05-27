<?
$reportName = '��������� ������, �������� �� ������� �������� �� ����� � �������'
?>
    <style>
        tr.type-head {
            font-weight: bold;
        }
    </style>
    <h1><?= $reportName ?></h1>
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
    <h1><?= $reportName ?> � <?= $from ?> �� <?= $to ?> </h1>
    <? foreach ($result as $h): ?>
        <h2><?= $h['caption'] ?></h2>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>� �/�</th>
                <th>�������� ���������� ������</th>
                <th>����� ��������</th>
                <th>���� ���������� ��������</th>
                <th>���</th>
                <th>���� �� ��� ������� (��)</th>
                <th>������</th>
                <th>�������</th>
                <th>����</th>
                <th>������</th>
                <th>���</th>
                <th>����</th>
                <th>����</th>
                <th>������</th>
                <th>��������</th>
                <th>�������</th>
                <th>������</th>
                <th>�������</th>

            </tr>

            </thead>
            <tbody>
            <?php

            foreach ($h['rows'] as $lc):


            ?>
            <tr>

                <td><?= $lc['n'] ?></td>
                <td><?= $lc['c'] ?></td>
                <td><?= $lc['d'] ?></td>
                <td><?= $lc['dd'] ?></td>
                <td><?= $lc['y'] ?></td>
                <td><?= $lc['p'] ?></td>
                <? foreach (range(1, 12) as $m): ?>
                    <td><?= $lc['m' . $m] ? 1 : 0 ?></td>
                <? endforeach ?>



                <? endforeach; ?>
            </tbody>
        </table>
    <? endforeach ?>
<? endif; ?>