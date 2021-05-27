<?php
/**
 * Created by JetBrains PhpStorm.
 * User: LWR
 * Date: 04.08.15
 * Time: 16:53
 * To change this template use File | Settings | File Templates.
 */
?>
<style>
    tr.type-head {
        font-weight: bold;
    }
</style>

<h1><?= $caption ?> </h1>
<form action="" method="POST">

    <!--    <label>от :
        <div class="input-prepend date datepicker "
             data-date="">
            <span class="add-on"><i class="icon-th"></i> </span> <input
                class="input-small"
                name="from"

                readonly="readonly" size="16" type="text"
                value="<? /*= $from */ ?>">
        </div>
    </label> <label>До :
        <div class="input-prepend date datepicker "
             data-date="">
            <span class="add-on"><i class="icon-th"></i> </span> <input
                class="input-small"
                name="to"

                readonly="readonly" size="16" type="text"
                value="<? /*= $to */ ?>">
        </div>
    </label> -->


    <label>от :
        <div class="input-prepend date datepicker "
             data-date="">
            <span class="add-on"><i class="icon-th"></i> </span> <input
                    class="input-small"
                    name="from"

                    readonly="readonly" size="16" type="text"
                    value="<?= $C->date($from) ?>">
        </div>
    </label> <label>До :
        <div class="input-prepend date datepicker "
             data-date="">
            <span class="add-on"><i class="icon-th"></i> </span> <input
                    class="input-small"
                    name="to"

                    readonly="readonly" size="16" type="text"
                    value="<?= $C->date($to) ?>">
        </div>
    </label>

    <label>Организация :
        <div><select name="hc">
                <? foreach ($hcs as $hc) {
                    echo '<option value="' . $hc['id'] . '">' . $hc['caption'] . '</option>';
                }
                ?>
            </select></div>

    </label>

    <label>Количество строк в документе :
        <div><input type="text" name="limiter" placeholder="3000" value="<?= (!empty($limiter)) ? $limiter : '' ?>">
        </div>

    </label>
    <input type="submit" value="Отфильтровать">


    <h3>Доступные файлы</h3>
    <? foreach ($files as $f): ?>
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <td><?= $f['horg']->captoin ?></td>
            </tr>
            </thead>
            <tbody>
            <? foreach ($f['files'] as $f): ?>
                <tr>
                    <td><a href="?action=excel_report_download&file=<?= $f['path'] ?>">Интервал <?= $f['from'] ?> - <?= $f['to'] ?></a>. <span class="text-muted">Создано <?= $f['created'] ?></span></td>
                </tr>
            <? endforeach; ?>
            </tbody>
        </table>
    <? endforeach; ?>
</form>