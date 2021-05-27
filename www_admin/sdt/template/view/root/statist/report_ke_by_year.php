<h1>����� �� �����</h1>
<form action="" method="POST">

    <label>�� :
        <input
                class="input-small"
                name="from"

                size="16" type="number"
                value="<?= $from ?>">
    </label><br>
    <label>�� :
        <input
                class="input-small"
                name="to"

                size="16" type="number"
                value="<?= $to ?>">

    </label>


    <input type="submit" value="�������������">
</form>

<?php
if ($result):
    ?>
    <h2>���������� ��������� ������� � ����</h2>
    <?php
    printTable($from, $to, $result['rudn_head']) ?>

    <h2>���������� ��������� ������� � ���� � ��������� ������� ����</h2>
    <?php
    printTable($from, $to, $result['rudn']) ?>
    <h2>���������� ��������� ������� �� ���� �������� �����</h2>
    <?php
    printTable($from, $to, $result['all']) ?>
<?php
endif; ?>

<?php
function printTable($from, $to, $data)
{
    $years = array_reverse(range($from, $to));
    $month = range(1, 12);
    $ru_month = [
        1 => "������",
        2 => "�������",
        3 => "����",
        4 => "������",
        5 => "���",
        6 => "����",
        7 => "����",
        8 => "������",
        9 => "��������",
        10 => "�������",
        11 => "������",
        12 => "�������",
    ];
    ?>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th></th>
            <?
            foreach ($years as $y):?>
                <th><?= $y ?></th>
            <?
            endforeach; ?>
        </tr>
        </thead>
        <tbody>
        <?
        foreach ($month as $m):?>
            <tr>
                <th><?= $ru_month[$m] ?></th>
                <?
                foreach ($years as $y):?>
                    <td><?= $data[$y][$m] ?></td>
                <?
                endforeach; ?>
            </tr>
        <?
        endforeach; ?>
        </tbody>
    </table>
    <?php
}

?>