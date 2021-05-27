<?
//echo $selected_lc.'-';
$reportName = !empty($caption) ? $caption : '��� ��������';
if (!isset($lc_caption)) $lc_caption = '';
?>
<style>
    tr.type-head {
        font-weight: bold;
    }
    th
    {
        vertical-align:top;
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
    <label>����������� :
        <div>

            <select name="hc" id="hc-list">
                <option value=0>�� ���� ������������</option>
                <?
                foreach ($hc_list as $item) {
                    /*  if ($item->id != 1 && $item->id != 2 && $item->id != 7 && $item->id != 8) {
                          continue;
                      }*/
                    if ($item['id'] == $selected_hc) {
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

    <? if (!empty($enable_lc)): ?>
        <label>��������� ����� :<br>


            <select name="lc" id="lc-list">
                <option value=0>�� ���� �������</option>
                <?
                if (!empty($lc_list)) {
                    foreach ($lc_list as $item) {
                        /*  if ($item->id != 1 && $item->id != 2 && $item->id != 7 && $item->id != 8) {
                              continue;
                          }*/
                        if ($item->id == $selected_lc) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }


                        echo '<option class="lc-item"  value=' . $item->id . ' ' . $selected . '>' . $item->name . '</option>';
                    }
                }
                ?>
            </select>

        </label>
    <? endif ?>
    <? //<input type="submit" value="�������������" onclick="return confirm('������ �������! ����������?');">?>
    <input type="submit" value="�������������">
</form>
<? if (!empty($search)): ?>
    <h1><?=$reportName?> � <?= $from ?> ��  <?= $to ?> </h1>
    <? foreach ($arrays as $key => $array): ?>
        <?php if (!empty($key) && is_numeric($key)): ?>
            <h3><?= HeadCenter::getOrgName($key) ?> <?= $lc_caption ?></h3>
        <? else/*if ($selected_hc==='pfur')*/
            : ?>
            <h3>�� ���� ������������</h3>
        <? endif; ?>

        <?
        $from_output = date('j', strtotime($from)) .' '.month(date('m', strtotime($from)),1).' '.date('Y', strtotime($from));
        $to_output = date('j', strtotime($to)) .' '.month(date('m', strtotime($to)),1).' '.date('Y', strtotime($to));
        ?>

        <table class="table table-bordered">
        <thead>
        <tr>
            <th colspan="5" class="center">������������ ��������������� ����������� (��������� ����)</th>
        </tr>
        <tr>
            <th class="center" rowspan="2"><p>� �/�</p></th>
            <th class="center" rowspan="2"><p>������������ ���������� ������</p></th>
            <th class="center" rowspan="2"><p>�����</p></th>
            <th class="center" colspan="2" ><p>���������� �������� ������������ �� ������ � <?=$from_output?> �. �� <?=$to_output?> �.</p></th>
        </tr>
        <tr>
            <th  ><p>�����</p></th>
            <th  ><p>����������</p></th>
        </tr>

        </thead>
        <tbody>
        <?//var_dump($array);?>
        <? $i = 0; ?>
        <? foreach ($array as $lc_key => $lc_array):
//            if (empty($selected_lc)) echo '<tr><td colspan="9" style="text-align: center"><b>'.University::getByID($lc_key).'</b></td></tr>';
//            $i=0;
            foreach ($lc_array as $item): ?>
           <tr>
                <td  ><?=++$i.'.'?></td>
                <td  ><?=$item['name']?></td>
                <td  ><?=$item['legal_address']?></td>
                <td  ><?=month($item['month']).' '.$item['year']?></td>
                <td  ><?=$item['certs']?></td>
            </tr>
        <? endforeach ?>
        <? endforeach ?>
        </tbody>
    </table>
    <? endforeach ?>
<? endif; ?>


<script>
    $(function () {
        var process = null;
        $('#hc-list').on('change', function () {
            if (process) process.abort();
            process = $.getJSON('/sdt/index.php?action=ajax_lc_list', {
                    hc: $(this).find(':selected').val()
                },
                function (res) {
//                    console.log(res);
                    var list = $('#lc-list');
                    list.find('.lc-item').remove();
                    _.each(res,function(item,key){
                        var op = $('<option>');
                        op.addClass('lc-item');
                        op.val(item.id);
                        op.html(item.name);
                        list.append(op);
                    });
                }
            );
        });

    });
</script>

<?
function month($id, $type=0)
{
    $id=(int)$id;
    $month = array(
    '1' => array('0'=>'������', '1' => '������'),
    '2' => array('0'=>'�������', '1' => '�������'),
    '3' => array('0'=>'����', '1' => '�����'),
    '4' => array('0'=>'������', '1' => '������'),
    '5' => array('0'=>'���', '1' => '���'),
    '6' => array('0'=>'����', '1' => '����'),
    '7' => array('0'=>'����', '1' => '����'),
    '8' => array('0'=>'������', '1' => '�������'),
    '9' => array('0'=>'��������', '1' => '��������'),
    '10' => array('0'=>'�������', '1' => '�������'),
    '11' => array('0'=>'������', '1' => '������'),
    '12' => array('0'=>'�������', '1' => '�������'),
    );
    if (empty($id)) return $id;
    return $month[$id][$type];
}