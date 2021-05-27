<?
$reportName = 'Локальные центра, договора по патенту разбитые по годам и месяцам'
?>
    <style>
        tr.type-head {
            font-weight: bold;
        }
    </style>
    <h1><?= $reportName ?></h1>
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
    <h1><?= $reportName ?> с <?= $from ?> по <?= $to ?> </h1>
    <? foreach ($result as $h): ?>
        <h2><?= $h['caption'] ?></h2>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>№ п/п</th>
                <th>Название Локального центра</th>
                <th>Номер договора</th>
                <th>Дата заключения договора</th>
                <th>Год</th>
                <th>Цена КЭ для Патента (ИР)</th>
                <th>Январь</th>
                <th>Февраль</th>
                <th>Март</th>
                <th>Апрель</th>
                <th>Май</th>
                <th>Июнь</th>
                <th>Июль</th>
                <th>Август</th>
                <th>Сентябрь</th>
                <th>Октябрь</th>
                <th>Ноябрь</th>
                <th>Декабрь</th>

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