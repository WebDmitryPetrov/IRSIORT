<?php
/**
 * Created by JetBrains PhpStorm.
 * User: LWR
 * Date: 20.03.15
 * Time: 17:01
 * To change this template use File | Settings | File Templates.
 */
$npp=0;
?>

<table class="table table-bordered  table-striped">
    <thead>
    <th>
        № п/п
    </th>

    <th>
        Сообщение
    </th>

    <th>
        Кому
    </th>

    <th>
        Головные центры
    </th>

    <th>
        Дата отправки
    </th>

    <th>
<!--        Действия-->
    </th>

    </thead>


<?
foreach ($list as $key => $item)
{


    if ($item['user_type'] == 5)
        $whom='Администраторы';
    else if ($item['user_type'] == 6)
        $whom='Супервизоры';
    else
        $whom='Локальные центры';

    $hc_name='Все';

    if (!empty($item['hc_id']))
    {
        if ($item['hc_id']==-1)$hc_name='Всем головным центрам РУДН';
        else {
            if (!empty($item['short_name'])) {
                $hc_name = $item['short_name'];
            } else {
                $hc_name = $item['name'];
            }
        }
    }

if (!empty($item['date']))
    $date=date("d.m.Y H:i:s",strtotime($item['date']));
    else $date='';


    echo '<tr>
        <td>'.++$npp.'</td>
        <td>'.$item['text'].'</td>
        <td>'.$whom.'</td>
        <td>'.$hc_name.'</td>
        <td>'.$date.'</td>
        <td>
        <a class="btn btn-warning btn-mini btn-block" href="index.php?action=message_edit&id='.$item['id'].'" >Редактировать</a>
        <a class="btn btn-danger btn-mini btn-block" href="index.php?action=message_delete&id='.$item['id'].'" onclick="return confirm(\'Вы уверены?\');">Удалить</a>
        </td>';
}


echo '</table>';