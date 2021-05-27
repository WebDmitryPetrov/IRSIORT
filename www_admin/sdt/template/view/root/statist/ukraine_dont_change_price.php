<h1>Отчет по протестированным с украины по упрощённому уровню с ценной 500 рублей по РУДН</h1>
<form action="" method="POST">

    <label>от :
        <div class="input-prepend date datepicker "
             data-date="">
            <span class="add-on"><i class="icon-th"></i> </span> <input
                    class="input-small"
                    name="from"

                    readonly="readonly" size="16" type="text"
                    value="<?= $from ?>">
        </div>
    </label> <label>До :
        <div class="input-prepend date datepicker "
             data-date="">
            <span class="add-on"><i class="icon-th"></i> </span> <input
                    class="input-small"
                    name="to"

                    readonly="readonly" size="16" type="text"
                    value="<?= $to ?>">
        </div>
    </label>


    <input type="submit" value="Отфильтровать">
</form>
<? if (!empty($search)): ?>
    <h1>Отчет по протестированным с украины по упрощённому уровню с ценной 500 рублей по РУДН
        с <?= $from ?> по <?= $to ?> </h1>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Наименование организации</th>
            <th>дата тестовой сессии</th>
            <th>№ тестовой сессии</th>
            <th>кол-во человек</th>
            <th>дата акта</th>
            <th>№ счёта</th>


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