<h1>��������� ������ �� ���������� � ��������</h1>
<form action="" method="post">

    <label>��:

        <div class="input-prepend date datepicker "
             data-date="">
            <span class="add-on"><i class="icon-th"></i> </span> <input
                    class="input-small"
                    name="from"

                    readonly="readonly" size="16" type="text"
                    value="<?= $from ?>">
        </div>
    </label>

    <label>�������� �����:

        <div>

            <select name="hc" id="hc-list" required>
<!--                <option disabled>�������� �������� �����</option>-->
                <?
                foreach ($hcs as $item) {
                    /*  if ($item->id != 1 && $item->id != 2 && $item->id != 7 && $item->id != 8) {
                          continue;
                      }*/
                    if ($item['id'] == $hc) {
                        $selected = 'selected="selected"';
                    } else {
                        $selected = '';
                    }


                    $name = $item['caption'];

                    echo '<option value=' . $item['id'] . ' ' . $selected . '>' . $name . '</option>';
                }
                ?>
            </select>
        </div>
    </label>
    <input type="submit" value="���������">
</form>


<? if ($search): ?>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>id</th>
            <th>��������</th>
            <th>���� ��������</th>
            <th>���� ����������</th>
            <th>��</th>



        </tr>
        </thead>
        <tbody>
        <?
        if(!$result) $result=[];
        foreach ($result as $item): ?>
            <tr>
                <td><?= $item['su_id'] ?></td>
                <td><?= $item['su_name']?$item['su_name']:$item['su_fname'] ?></td>
                <td><nobr><?= $item['created'] ? date('d.m.Y H:i',strtotime($item['created'])) : '���' ?></nobr></td>
                <td><nobr><?= $item['m'] ? date('d.m.Y H:i',strtotime($item['m'])) : '���' ?></nobr></td>
                <td><?= $item['short_name'] ?></td>


            </tr>
        <? endforeach ?>
        </tbody>
    </table>
<? endif ?>