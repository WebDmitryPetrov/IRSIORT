<h1>������ ����������� �������������</h1>
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
    <label>��:

        <div class="input-prepend date datepicker "
             data-date="">
            <span class="add-on"><i class="icon-th"></i> </span> <input
                    class="input-small"
                    name="to"

                    readonly="readonly" size="16" type="text"
                    value="<?= $to ?>">
        </div>
    </label>
    <label>��:

        <div>

            <select name="hc" id="hc-list" required>
                <option value="all" <?= ('all' == $hc) ? 'selected="selected"' : '' ?> >��� �������� ������</option>
                <option value="pfur" <?= ('pfur' == $hc) ? 'selected="selected"' : '' ?> >����</option>
                <!--                <option disabled>�������� �������� �����</option>-->
                <?
                foreach ($hcs as $item) {
                    /*  if ($item->id != 1 && $item->id != 2 && $item->id != 7 && $item->id != 8) {
                          continue;
                      }*/
                    if ($item->id == $hc) {
                        $selected = 'selected="selected"';
                    } else {
                        $selected = '';
                    }

                    /** @var HeadCenter $name */
                    $name = $item->getTitle();

                    echo '<option value=' . $item->id . ' ' . $selected . '>' . $name . '</option>';
                }
                ?>
            </select>
        </div>
    </label>
    <input type="submit" value="���������">
</form>


<?php if ($search):
    if (!$result) $result = [];
//    var_dump($result);
//    var_dump(array_column($result, 'utype'));die;
    $types = array_count_values(array_filter(array_column($result, 'utype')));
    ksort($types);
//var_dump($types);
    ?>

    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>���</th>
            <th>����������</th>


        </tr>
        </thead>
        <tbody>

        <? foreach ($types as $type => $cc): ?>
            <tr>
                <th><?= $type ?></th>
                <td><?= $cc; ?></td>
            </tr>
        <? endforeach; ?>
        <tr>
            <th>�����</th>
            <th><?= count($result); ?></th>
        </tr>
        </tbody>
    </table>

    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>����</th>
            <th>�����</th>
            <th>���</th>
            <th>IP</th>
            <th>��� ������������</th>
            <th>��������� �����</th>
            <th>��</th>

        </tr>
        </thead>
        <tbody>
        <?

        foreach ($result as $item): ?>
            <tr>
                <td><?= $item['created_at'] ?></td>
                <td><?= $item['login'] ?></td>
                <td><?= $item['name'] ? $item['name'] : '����������� �����' ?></td>
                <td><?= $item['ip'] ?></td>
                <td><?= $item['utype'] ?></td>
                <td><?= $item['univer_id'] ? $item['univer'] : '' ?></td>
                <td><?= $item['short_name'] ?></td>

            </tr>
        <? endforeach ?>
        </tbody>
    </table>
<? endif ?>