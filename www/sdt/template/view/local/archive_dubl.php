<?
$translate = [
        'in_headcenter'=>'��������������',
        'processed'=>'��������� ���������',
        'on_check'=>'�� ��������',
    ];
?>

<table class="table table-bordered">
    <thead>
    <tr>
        <th>���� ��������</th>
        <th>���������</th>

        <th>��� ������������</th>
        <th>���������� ������������</th>
        <th>���������� ������������</th>

       
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