<?
?>
    <style>
        tr.type-head {
            font-weight: bold;
        }
    </style>
<? if (!empty($caption)): ?>
    <h1><?= $caption ?></h1>

<? endif ?>
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
    <h1>Информация о прохождении  с <?= $from ?> по  <?= $to ?> </h1>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th><p>Страна</p></th>
            <th><p>Количество сдавших</p></th>

        </tr>

        </thead>
        <tbody>
        <? foreach ($array as $item): ?>
            <tr>
                <td><?= $item['country_caption'] ?></td>
                <td><?= $item['cc'] ?></td>

            </tr>
        <? endforeach ?>
        </tbody>
    </table>

<? endif; ?>