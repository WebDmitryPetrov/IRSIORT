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
    <?
    $months = array(
        1 => 'Январь',
        2 => 'Февраль',
        3 => 'Март',
        4 => 'Апрель',
        5 => 'Май',
        6 => 'Июнь',
        7 => 'Июль',
        8 => 'Август',
        9 => 'Сентябрь',
        10 => 'Октябрь',
        11 => 'Ноябрь',
        12 => 'Декабрь',
    );
    //    echo $year.$month;
//    var_dump($year,$month, $current_hc);

    ?>

    <label>Год :

        <div><select name="year">

                <?
                $startyear = 2013;
                for ($startyear; $startyear <= date("Y"); $startyear++) {
                    if ($startyear == $year) $selected = 'selected="selected"';
                    else $selected = "";
                    echo '<option value="' . $startyear . '" ' . $selected . '>' . $startyear . '</option>';
                }
                ?>

            </select></div>
    </label>

    <label>Месяц :
        <div><select name="month">
                <? foreach ($months as $num => $name) {
                    if ($num == $month) $selected = 'selected="selected"';
                    else $selected = "";
                    echo '<option value="' . $num . '" ' . $selected . '>' . $name . '</option>';
                }
                ?>

            </select></div>
    </label>

    <label>Организация :
        <div><select name="hc">
                <? foreach ($hcs as $hc) {
//                    var_dump($hc,$current_hc);
                    if ($hc['id'] == $current_hc) $selected = 'selected="selected"';
                    else $selected = "";
                    echo '<option value="' . $hc['id'] . '" '.$selected.' >' . $hc['caption'] . '</option>';
                }
                ?>
            </select></div>

    </label>

    <label>Количество строк в документе :
        <div><input type="text" name="limiter" placeholder="3000" value="<?= (!empty($limiter)) ? $limiter : '' ?>">
        </div>

    </label>
    <input type="submit" value="Отфильтровать">
</form>