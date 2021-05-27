<style>
    table
    {
        width:100%;
        border-spacing: 0;
        border-collapse: collapse;

    }
    table td, th
    {
        border:1px solid black;
        text-align: center;
        padding: 5px 10px;
    }
</style>

<?php
/**
 * Created by PhpStorm.
 * User: lwr
 * Date: 19.02.2018
 * Time: 16:54
 */

//var_dump($certs);
$i=0;
echo '<h1>Недействительные сертификаты</h1>';
echo '<table >';
echo '<tr>
<th style="width:5%">№ п/п</th>
<th style="width:10%">Дата</th>
<th style="width:10%">Номер бланка</th>
<th style="width:10%">Головной центр</th>
<th style="width:10%">Тип тестирования</th>
<th style="width:30%">Причина</th>
<th style="width:20%">Действие</th></tr>';
foreach ($certs as $cert)
{
    echo '<tr>
<td>'.++$i.'</td>
<td>'.date('d.m.Y H:i:s',strtotime($cert['ts'])).'</td>
<td>'.$cert['number'].'</td>
<td>'.$cert['short_name'].'</td>
<td>'.getExamType($cert['test_type_id']).'</td>

<td>'.$cert['reason'].'</td>
<td>'.getInvaidType($cert['object_type']).'</td>
</tr>';
}

//echo '<table>';
echo '</table>';


function getExamType($test_level_type_id=2)
{
    $types=
    [
        1=>'РКИ',
        2=>'Экзамен'
    ];
    return $types[$test_level_type_id];
}

function getInvaidType($invalid_type_id='man')
{
    $types=
    [
        'dubl'=>'Выдача дубликата',
        'man'=>'Выдача сертификата',
        'cert_reserved'=>'Управление сертификатами',
    ];
    return isset($types[$invalid_type_id])?$types[$invalid_type_id]:$invalid_type_id;
}