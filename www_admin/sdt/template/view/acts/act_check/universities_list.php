<h1 xmlns="http://www.w3.org/1999/html">������ ������� � ����������� �� ��������</h1>

<table class="table table-bordered  table-striped">
    <thead>
    <tr>
        <th valign="top">
            <nobr>�</nobr>
        </th>
        <th valign="top">�������� ������</th>
        <th valign="top">�����</th>
    </tr>
    </thead>
    <tbody>
    <?php $i=0; foreach ($list as $univer): ?>
        <tr>
            <td>
                <?=++$i;?>
            </td>
            <td>
                  <a href="index.php?action=act_second_list&uid=<?=$univer->id;?>" ><?=$univer->caption;?></a>
            </td>
            <td>
                <strong><?=$univer->count;?></strong>
            </td>
        </tr>


        <?php endforeach; ?>

    </tbody>
</table>