<h1>Отчет по годам</h1>
<form action="" method="POST">

    <label>от :
        <input
                class="input-small"
                name="from"

                size="16" type="number"
                value="<?= $from ?>">
    </label><br>
    <label>До :
        <input
                class="input-small"
                name="to"

                size="16" type="number"
                value="<?= $to ?>">

    </label>


    <input type="submit" value="Отфильтровать">
</form>

<?php
if ($result):
    ?>
    <h2>Количество сдававших экзамен в РУДН</h2>
    <?php
    printTable($from, $to, $result['rudn_head']) ?>

    <h2>Количество сдававших экзамен в РУДН и локальных центрах РУДН</h2>
    <?php
    printTable($from, $to, $result['rudn']) ?>
    <h2>Количество сдававших экзамен во всех головных вузах</h2>
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
        1 => "Январь",
        2 => "Февраль",
        3 => "Март",
        4 => "Апрель",
        5 => "Май",
        6 => "Июнь",
        7 => "Июль",
        8 => "Август",
        9 => "Сентябрь",
        10 => "Октябрь",
        11 => "Ноябрь",
        12 => "Декабрь",
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