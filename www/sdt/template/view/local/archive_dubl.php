<?
$translate = [
        'in_headcenter'=>'Обрабатывается',
        'processed'=>'Обработка завершена',
        'on_check'=>'На проверке',
    ];
?>

<table class="table table-bordered">
    <thead>
    <tr>
        <th>Дата создания</th>
        <th>Состояние</th>

        <th>Тип тестирования</th>
        <th>Количество сертификатов</th>
        <th>Количество отпечатанных</th>

       
    </tr>
    </thead>
    <tbody>
    <?foreach($items as $item):?>
        <tr data-id="<?=$item['id']?>">

            <td><?=$C->date($item['created'])?></td>
            <td><?=$translate[$item['state']]?></td>
           <td><?=$item['test_type']?></td>
           <td><?=$item['cc_total']?></td>
            <td><?=$item['cc']?></td>

        </tr>
    <?endforeach;?>
    </tbody>
</table>