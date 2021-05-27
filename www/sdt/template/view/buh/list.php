<h1>������ ����� �������� ������</h1>


<table cellspacing="0" cellpadding="0"  class="table table-bordered  table-striped">

    <thead>
    <tr>
        <th>�/�</th>
        <th>id</th>
        <th>���� ��������</th>
        <th>����� ��������</th>
        <th>������������    ����������� �����������</th>
        <th>��� �����������</th>
        <th>���� ���� (�������� ���������)</th>
        <th>����� �����</th>
        <th>���� �����</th>
        <th>����� �����</th>
        <th>��������</th>
        <th>����� ���������� ���������</th>
        <th>���� �������</th>
        <th>���� �������� �������� ������</th>
        <th>�������</th>
        <? if(Roles::getInstance()->userHasRole(Roles::ROLE_CONTR_BUH)):?>
            <th></th>
        <?endif?>
    </tr>
    </thead>
    <tbody>
    <?php
$i=0;
    foreach ($list as $item):
    /** @var Act $item */
    ?>
    <tr class="<?if ($item->isDeleted()):?>error<?endif?>">
        <td><?=++$i?></td>
        <td><?=$item->id; ?></td>
        <td><?php echo $C->date($item->getUniversityDogovor()->date);?></td>
        <td><?php echo $item->getUniversityDogovor()->number ?></td>
        <td><?php echo $item->getUniversity()->getLegalInfo()['name_parent'] ?></td>
        <td><?php echo $item->getUniversity()->getLegalInfo()['inn'] ?></td>
        <td><?php echo $C->date($item->actDate(),true);?></td>
        <td><?php echo $item->invoice_index.'/'.$item->invoice ?></td>
        <td><?php echo $C->date($item->invoice_date,true);?></td>
        <td><?php echo $item->amount_contributions;?></td>
        <td><?php echo $item->paid ? '��' : '���'; ?></td>
        <td><?php echo $item->platez_number ?></td>
        <td><?php echo $C->date($item->platez_date,true); ?></td>
        <td><?php echo $C->date($item->created,true) ?></td>
        <td><?php echo $item->deleted?'������':''?></td>
        <? if(Roles::getInstance()->userHasRole(Roles::ROLE_CONTR_BUH)):?>
            <td><a target="_blank" href="index.php?action=buh_view_act&id=<?=$item->id?>" class="btn btn-primary btn-mini">�����������</a> </td>
        <?endif?>
    </tr>

    <? endforeach ?>
    </tbody>
    </table>