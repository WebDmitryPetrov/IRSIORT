<h1 xmlns="http://www.w3.org/1999/html">������ ���������� ����������</h1>
<h3>����� <?=count($list)?></h3>

<table class="table table-bordered  table-striped">
    <thead>
    <tr>
        <th valign="top">
            <nobr>�</nobr>
        </th>
        <th valign="top">�����������</th>
        <th valign="top">����� ��������</th>
        <th valign="top">����� �����</th>
        <th valign="top">���� �����</th>
        <th valign="top">����� �����</th>
        <th valign="top">��������</th>

    </tr>
    </thead>
    <tbody>


    <?php

    foreach ($list as $item):
        /** @var Act $item */
        ?>
    <tr>
        <td><?=$item->number?></td>


        <td class="wrap"><?php echo $item->getUniversity() ?>
        </td>
        <td class="wrap"><?php echo $item->getUniversityDogovor() ?>
        </td>
         <td><?php echo $item->invoice ?>
        </td>
        <td><?php echo $C->date($item->invoice_date);?>
        </td>
        <td><?php echo $item->amount_contributions;?>
        </td>
        <td><?php echo $item->paid ? '��' : '���'; ?>
        </td>



        <?php endforeach;?>
    </tbody>
</table>

