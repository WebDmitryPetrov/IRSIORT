<?
?>
    <style>
        tr.type-head {
            font-weight: bold;
        }
    </style>
    <h1><?= $caption ?> </h1>
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
    <h1>Информация с <?= $from ?> по <?= $to ?> </h1>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th></th>
            <th>Наименование субъекта</th>
            <th>Количество локальных центров</th>
            <th>Наименование организации</th>
            <th>Количество сдавших</th>
        </tr>
        </thead>
        <tbody>
        <? foreach ($array as $f_key => $f):
            if ($f_key == 'none') continue;
            ?>
            <tr>
                <td colspan="5" style="text-align: center; font-weight: bold;">
                    <?= $f['caption'] ?> федеральный округ
                </td>
            </tr>
            <? foreach ($f['items'] as $r):
            $first = 0;
            ?>

            <? foreach ($r['items'] as $lc):
            $is_first = !$first++;
            ?>
            <tr>
                <td></td>
                <td><?= $is_first ? $r['caption'] : '' ?></td>
                <td><?= $is_first ? count($r['items']) : '' ?></td>
                <td><?= $lc['caption'] ?></td>
                <td><?= $lc['certs'] ?></td>
            </tr>
        <? endforeach ?>
        <? endforeach ?>
        <? endforeach ?>
        <? if (array_key_exists('none', $array)):
            $f = $array['none'];
            ?>
            <tr>
                <td colspan="5" style="text-align: center; font-weight: bold;">
                    <?= $f['caption'] ?>
                </td>
            </tr>
            <?  $first = 0;
            foreach ($f['items'] as $lc):

            $is_first = !$first++;
            ?>
            <tr>
                <td></td>
                <td></td>
                <td><?= $is_first ? count($f['items']) : '' ?></td>
                <td><?= $lc['caption'] ?></td>
                <td><?= $lc['certs'] ?></td>
            </tr>

        <? endforeach ?>
        <? endif ?>
        </tbody>
    </table>

<? endif; ?>