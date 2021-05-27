<? $i=1 ?>

<?
function echo_days($days)
{
    $last_day = (int)substr($days, -1);
    $prev_last_day = (int)substr($days, -2, 1);
    if ( $prev_last_day == 1 || in_array($last_day, array (0,5,6,7,8,9))) $day_word = ' дней';
    else if ( $last_day == 1) $day_word = ' день';
    else if (in_array($last_day, array (2,3,4))) $day_word = ' дня';
    return $day_word;
}



?>
<h1>Список выданных справок локального центра за <? echo $interval.' '.echo_days($interval); ?></h1>
<table class="table table-bordered">
    <thead>
    <tr>
        <th>№ п/п</th>
        <th>Номер справки</th>
        <th>ФИО</th>
        <th>Дата тестирования</th>
       
    </tr>
    </thead>
    <tbody>
    <?foreach($items as $item):?>
        <tr data-id="<?=$item->id?>">
            <td><?=$i++?></td>
            <td><?=$item->blank_number?></td>
            <td><?=$item->surname_rus.' '.$item->name_rus?></td>
            <td><?=date("d.m.Y",strtotime($item->testing_date))?></td>

        </tr>
    <?endforeach;?>
    </tbody>
</table>