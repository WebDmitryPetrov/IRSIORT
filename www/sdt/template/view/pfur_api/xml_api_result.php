<table class="table table-bordered">
    <thead>
    <tr>
        <th>�</th>
        <th>���� ��������</th>
        <th>������</th>
        <th>���� ������������</th>
        <th>���� �������� � ��</th>
        <th>���������� �����������</th>
        <th></th>
<!--        <th>���������� ������������ ������������</th>-->
<!--        <th>���������� ������������ �������</th>-->
       
    </tr>
    </thead>
    <tbody>
    <?foreach($items as $item):?>
        <tr data-id="<?=$item['id']?>">
            <td><?=$item['number']?></td>
            <td><?=$C->date($item['created'])?></td>
            <td><? if ($item['state']==Act::STATE_ARCHIVE):?>� ������
                <?else:?>� ������
                <?endif?></td>

            <td><?=$C->date($item['testing'])?></td>
            <td><?=(strpos($item['received'],'0000-00-00')===0||empty($item['received']))?'':$C->date($item['received'])?></td>
            <td><?=$item['people']?></td>
<!--            <td>--><?//=$item['print_certs']?><!--</td>-->
<!--            <td>--><?//=$item['print_note']?><!--</td>-->

            <td>
                <? if ($item['state']==Act::STATE_ARCHIVE):?>
                    <a href="./index.php?action=api_xml_result_download&id=<?=$item['id']?>" target="_blank" class="btn btn-danger">Xml</a>
                <?else:?>
                <a href="" class="btn btn-danger disabled">Xml</a>
                <?endif?>
            </td>
        </tr>
    <?endforeach;?>
    </tbody>
</table>