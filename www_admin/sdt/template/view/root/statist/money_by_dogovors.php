<?
$c = 3;
if ($level_type == 1) {
    $c = 1;
}
?>

    <h1><?= $caption ?></h1>
    <form action="" method="POST">

        <label>�� :
            <div class="input-prepend date datepicker "
                 data-date="">
                <span class="add-on"><i class="icon-th"></i> </span> <input
                        class="input-small"
                        name="from"

                        readonly="readonly" size="16" type="text"
                        value="<?= $from ?>">
            </div>
        </label> <label>�� :
            <div class="input-prepend date datepicker "
                 data-date="">
                <span class="add-on"><i class="icon-th"></i> </span> <input
                        class="input-small"
                        name="to"

                        readonly="readonly" size="16" type="text"
                        value="<?= $to ?>">
            </div>
        </label>
        <label>����������� :
            <div>

                <select name="head_id" id="hc-list" style="width:400px">

                    <?
                    foreach ($hc_list as $item) {
                        /*  if ($item->id != 1 && $item->id != 2 && $item->id != 7 && $item->id != 8) {
                              continue;
                          }*/
                        if ($item->id == $head_id) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }

                        $name = $item->name;

                        echo '<option value=' . $item->id . ' ' . $selected . '>' . $name . '</option>';
                    }
                    ?>
                </select>
            </div>
        </label>
        <label>��� ������������
            <select name="level_type" style="width: 400px">
                <?
                foreach (TestLevelTypes::getAll() as $item) {

                    if ($item->id == $level_type) {
                        $selected = 'selected="selected"';
                    } else {
                        $selected = '';
                    }


                    echo '<option value=' . $item->id . ' ' . $selected . '>' . $item->caption . '</option>';
                }
                ?>
            </select>
        </label>

        <? //<input type="submit" value="�������������" onclick="return confirm('������ �������! ����������?');">?>
        <input type="submit" value="�������������">
    </form>
<? if ($search): ?>
    <h1>� <?= $from ?> �� <?= $to ?> </h1>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th rowspan="2">�������� �����������</th>
            <th rowspan="2">� ��������</th>
            <? if ($level_type == 1): ?>
                <th colspan="2">��</th>
            <? endif ?>
            <? if ($level_type == 2): ?>
                <th colspan="2">��</th>
                <th colspan="2">��</th>
                <th colspan="2">��</th>
            <? endif ?>


        </tr>
        <tr>

            <? for ($i = 0; $i < $c; $i++): ?>
                <th>���-��, ���.</th>
                <th>�����, ���.</th>
            <? endfor; ?>
        </tr>
        </thead>
        <tbody>
        <? foreach ($array as $org): ?>
            <? foreach ($org['dogovors'] as $dogovor): ?>
                <tr>
                    <td><?= $org['caption'] ?></td>
                    <td><?= $dogovor['caption'] ?></td>
                    <? for ($i = 0; $i < $c; $i++):
//                        var_dump($c);
                        ?>
                        <td><?= $dogovor['numbers'][$i]['people'] ?></td>
                        <td><?= $dogovor['numbers'][$i]['money'] ?></td>
                    <? endfor; ?>

                </tr>
            <? endforeach; ?>
        <? endforeach; ?>

        </tbody>
    </table>


<? endif; ?>


<?
function month($id, $type = 0)
{
    $id = (int)$id;
    $month = array(
        '1' => array('0' => '������', '1' => '������'),
        '2' => array('0' => '�������', '1' => '�������'),
        '3' => array('0' => '����', '1' => '�����'),
        '4' => array('0' => '������', '1' => '������'),
        '5' => array('0' => '���', '1' => '���'),
        '6' => array('0' => '����', '1' => '����'),
        '7' => array('0' => '����', '1' => '����'),
        '8' => array('0' => '������', '1' => '�������'),
        '9' => array('0' => '��������', '1' => '��������'),
        '10' => array('0' => '�������', '1' => '�������'),
        '11' => array('0' => '������', '1' => '������'),
        '12' => array('0' => '�������', '1' => '�������'),
    );
    if (empty($id)) return $id;
    return $month[$id][$type];
}