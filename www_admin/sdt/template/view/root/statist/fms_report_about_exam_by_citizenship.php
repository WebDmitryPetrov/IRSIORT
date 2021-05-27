<?
$reportName = !empty($caption) ? $caption : '��� ��������';
?>
    <style>
        tr.type-head {
            font-weight: bold;
        }
    </style>
    <h1><?= $reportName ?></h1>
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
        </label> <!--<label>������ :
            <div>

                <select name="region">
                    <? /*
                    foreach($regions_list as $key => $value)
                    {
                        if (!$key)
                        {
                            echo '<option value='.$key.'>�� ���� ��������</option>';
                        }
                        else
                        {
                            if ($key==$_POST['region']) $selected='selected="selected"';
                            else $selected='';

                            echo '<option value='.$key.' '.$selected.'>'.$value.'</option>';
                        }
                    }
                    */ ?>
                </select>
            </div>-->
        <label>�������� ����� ������������ :
            <div>

                <select name="hc">
                    <option value=0>�� ���� �������� �������</option>
                    <option value='pfur'>���� ������������</option>
                    <?
                    foreach ($hc_list as $item) {
                        if($item->horg_id != 1) continue;
//                        if ($item->id != 1 && $item->id != 2 && $item->id != 7 && $item->id != 8 && $item->id != 13) {
//                            continue;
//                        }
                        if ($item->id == $selected_hc) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }

                        $name = $item->short_name ? $item->short_name : $item->name;

                        echo '<option value=' . $item->id . ' ' . $selected . '>' . $name . '</option>';
                    }
                    ?>
                </select>
            </div>
        </label>

        <input type="submit" value="�������������">
    </form>
<? if (!empty($search)): ?>
    <h1><?= $reportName ?> � <?= $from ?> �� <?= $to ?> </h1>
    <? foreach ($arrays as $key => $array): ?>
        <?php if (is_numeric($key)): ?>
            <h3><?= HeadCenter::getByID($key) ?></h3>
        <? elseif ($selected_hc === 'pfur'): ?>
            <h3>���� ������������</h3>
        <? endif; ?>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th><p>������</p></th>
                <!--            <th  ><p>���������� �����������-���������, � ������� ��������� ����������</p></th>-->

                <th colspan="4"><p>���������� ���������, ������� �������</p></th>
                <th><p>���������� �������� ������������</p></th>


                <th colspan="4"><p>���������� ���������, ��������� �������</p></th>
                <th><p>���������� �������� �������</p></th>

            </tr>
            <tr>
                <!--            <th  ><p>&nbsp;</p></th>
                            <th  ><p>&nbsp;</p></th>

                            <th ><p>���������� �� ������ ��� ������ (���)</p></th>
                            <th  ><p>���������� �� ��������� ���������� (���)</p></th>
                            <th  ><p>��� �� ���������� (���)</p></th>
                            <th  ><p>����� ���������� </p></th>
                            <th  ><p>&nbsp;</p></th>

                            <th ><p>���������� �� ������ ��� ������ (���)</p></th>
                            <th  ><p>���������� �� ��������� ���������� (���)</p></th>
                            <th  ><p>��� �� ���������� (���)</p></th>
                            <th  ><p>����� ���������� </p></th>
                            <th  ><p>&nbsp;</p></th>-->


                <!--            <th><p>&nbsp;</p></th>-->
                <th><p>&nbsp;</p></th>
                <!--            <th><p>&nbsp;</p></th>-->
                <th><p>���������� �� ������ ��� ������ (���)</p></th>
                <th><p>���������� �� ��������� ���������� (���)</p></th>
                <th><p>��� �� ���������� (���)</p></th>

                <th><p>����� ���������� </p></th>
                <th><p>&nbsp;</p></th>

                <th><p>���������� �� ������ ��� ������ (���)</p></th>
                <th><p>���������� �� ��������� ���������� (���)</p></th>
                <th><p>��� �� ���������� (���)</p></th>

                <th><p>����� ���������� </p></th>
                <!--            <th><p>&nbsp;</p></th>-->
                <th><p>&nbsp;</p></th>

            </tr>
            </thead>
            <tbody>
            <? //var_dump($array);?>
            <? foreach ($array as $item): ?>
                <? if (array_sum($item['data']['levels']) == 0 && array_sum($item['data']['note_levels']) == 0) {
                    continue;
                } ?>
                <tr>
                    <td><?= $item['caption'] ?></td>
                    <!--                <td  >--><? //=$item['data']['orgs']?><!--</td>-->

                    <td><?= $item['data']['levels'][0] ?></td>
                    <td><?= $item['data']['levels'][1] ?></td>
                    <td><?= $item['data']['levels'][2] ?></td>

                    <td><?= array_sum($item['data']['levels']) ?></td>
                    <td><?= $item['data']['certs'] ?></td>

                    <td><?= $item['data']['note_levels'][0] ?></td>
                    <td><?= $item['data']['note_levels'][1] ?></td>
                    <td><?= $item['data']['note_levels'][2] ?></td>

                    <td><?= array_sum($item['data']['note_levels']) ?></td>
                    <td><?= $item['data']['notes'] ?></td>
                </tr>
            <? endforeach ?>
            </tbody>
        </table>
    <? endforeach ?>
<? endif; ?>