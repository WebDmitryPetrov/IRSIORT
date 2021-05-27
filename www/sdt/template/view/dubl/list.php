
<a href="dubl.php?action=dubl" class="btn btn-info">����� � ������ ���� ������������</a>

<h1>������� �� ���������</h1>
<a href="dubl.php?action=dubl_create&type=<?=$type;?>" class="btn btn-success">������� ����� ������ �� ��������</a>
<table class="table table-bordered">
    <thead>
    <tr>
        <th>���� ��������</th>
        <th>���������� �����</th>
        <th>�����������</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <? foreach($list as $item):?>
        <tr>
            <td><?=$C->dateTime($item->created)?></td>
            <td><?=count($item->getPeople()); ?></td>
            <td><?=$item->comment; ?></td>
            <td>
                <a href="dubl.php?action=dubl_edit&id=<?=$item->id?>" class="btn btn-info btn-block">������������� ���</a>
                <a href="dubl.php?action=dubl_show&id=<?=$item->id?>" class="btn btn-warning btn-block">��������/������������� ������</a>
                <? if ($item->chechForSend()):?>
                <a class="btn btn-success btn-block disabled" onclick="alert('�������� ����������: <?=implode(',',$item->chechForSend());?>')">��������� ������ </a>
                <? else: ?>
                <a href="dubl.php?action=dubl_send&id=<?=$item->id?>" class="btn btn-success btn-block" onclick="return confirm('�� �������?')">��������� ������ </a>
                <? endif?>
                <a href="dubl.php?action=dubl_delete&id=<?=$item->id?>" class="btn btn-danger btn-block" onclick="return confirm('�� �������?')">������� ������</a>
            </td>
        </tr>
    <? endforeach;?>
    </tbody>
</table>
