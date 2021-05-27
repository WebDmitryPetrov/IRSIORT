<?
ini_set('memory_limit','3G');
//echo $selected_lc.'-';
$reportName = !empty($caption) ? $caption : '��� ��������';
if (!isset($lc_caption)) $lc_caption = '';
?>
<style>
    tr.type-head {
        font-weight: bold;
    }
</style>
<h1><?= $reportName ?></h1>
<form action="" method="POST">
<h3>���� ������ �����������</h3>
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


    <input type="submit" value="�������������" onclick="return confirm('������ �������! ����������?');">
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
    <table class="table table-bordered">
        <thead>
        <tr>
            <th  ><p>��� ���������� �����������</p></th>
            <th  ><p>���� ��������</p></th>
            <th  ><p>����� ��������</p></th>
            <th  ><p>���������� ������ (�����, �����)</p></th>
            <th  ><p>����������� ��������</p></th>
            <th  ><p>��������������� ����������</p></th>
            <th  ><p>����� �����������/����������</p></th>


        </tr>

        </thead>
        <tbody>
        <?//var_dump($array);?>
        <? foreach ($array as $lc_key => $item): ?>

           <tr>
                <td  ><?=$item['surname_rus']?> <?=$item['name_rus']?></td>
                <td  ><?=date('d.m.Y', strtotime($item['birth_date']))?></td>
                <td  ><?=$item['birth_place']?></td>
                <td  ><?=$item['passport_name']?> <?=$item['passport_series']?> <?=$item['passport']?>
             <?=date('d.m.Y', strtotime($item['passport_date']))?> </td>

                <td  ><?=$item['test_level']?></td>
                <td  ><?=$item['lc_caption']?></td>
                <td  ></td>

            </tr>

        <? endforeach ?>
        </tbody>
    </table>
    <? endforeach ?>
<? endif; ?>


<script>
    $(function () {


    });
</script>

