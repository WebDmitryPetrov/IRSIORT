<?
//var_dump($personal_1,$personal_2);
//if (empty($selected_hc)) $selected_hc = -1;
$reportName = !empty($caption) ? $caption : '��� ��������';
if (!isset($lc_caption)) $lc_caption = '';
?>
<style>
    tr.type-head {
        font-weight: bold;
    }
    .table td, .table th
    {
        padding: 0 5px;
        line-height: 13px;
        font-size: 8px;
    }
    input[type="checkbox"]
    {
        vertical-align: unset;
    }

    .table th.left-border, .table td.left-border
    {
        border-left: 1px solid black;
    }

    .table th.right-border, .table td.right-border
    {
        border-right: 1px solid black;
    }
</style>

<? if (!empty($search)) {
 if (!empty($chosen_hc_name)) $chosen_hc_name = '('.$chosen_hc_name.')';
 if (!empty($lc_caption)) $lc_caption = ' � '.$lc_caption;

    $reportName .= '� ' . $from . ' �� ' . $to.' '.$lc_caption.' '.$chosen_hc_name;

}?>

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
    </label>

    <label>����������� :
        <div>

            <select name="hc" id="hc-list">
                <? /*<option value=0>�� ���� ������������</option>*/ ?>
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


        <label>�������� ������������� ��������:</label>
        <div>
            <label><input type="checkbox" name="personal_1" <?=(!empty($personal_1))?'checked="checked"':'';?>> ������ ������ (�����������, ���� ��������, ����� ��������)<br></label>
            <label><input type="checkbox" name="personal_2" <?=(!empty($personal_2))?'checked="checked"':'';?>> ���������� (�� �� �������� + ������������ )<br></label>
            <label><input type="checkbox" name="personal_3" <?=(!empty($personal_3))?'checked="checked"':'';?>> ������� ������������<br></label>
            <label><input type="checkbox" name="personal_4" <?=(!empty($personal_4))?'checked="checked"':'';?>> �����<br></label>
            <label><input type="checkbox" name="type_1" <?=(filter_input(INPUT_POST, 'type_1'))?'checked="checked"':'';?>> ���<br></label>
            <label><input type="checkbox" name="type_2" <?=(filter_input(INPUT_POST, 'type_2'))?'checked="checked"':'';?>> ����������� �������</label>
        </div>
        <br>


    <? endif ?>
    <input type="submit" value="�������������">
</form>
<? if (!empty($search)): ?>


    <?/*<p><button class="js-emailcopybtn"><img src="./images/copy-icon.png" /></button></p>*/?>


        <table class="table table-bordered ftable-condensed">
            <thead>
            <tr>
                <th><p>� �/�</p></th>
                <th><p>�.�.�. ���.</p></th>
                <th><p>�.�.�. ���.</p></th>
                <th><p>� �����������/ �������</p></th>
                <th><p>���. � �����������</p></th>
                <th><p>���� ������ �����������/ �������</p></th>
                <th><p>���� ������������</p></th>


                <? if (!empty($personal_1)):?>
                    <th class="left-border"><p>������ (�����������)</p></th>
                    <th><p>���� ��������</p></th>
                    <th class="right-border"><p>����� ��������</p></th>
                <? endif;?>

                <? if (!empty($personal_2)):?>
                    <th class="left-border"><p>�������� �������������� ��������</p></th>
                    <th><p>����� � �����</p></th>
                    <th><p>���� ������</p></th>
                    <th class="right-border"><p>��� �����</p></th>
                <? endif;?>

                <? if (!empty($personal_3)):?>

                    <th><p>������� ������������</p></th>
                <? endif;?>


                <? if (!empty($personal_4)):?>
                    <th class="left-border"><p>�����</p></th>

                <? endif;?>


            </tr>
            </thead>
            <tbody>

            <? $i=1; if (!empty($res))foreach ($res as $item): ?>
                    <td ><?=$i++; ?></td>
                    <td  ><?=$item['surname_rus'].' '.$item['name_rus'] ?></td>
                    <td  ><?=$item['surname_lat'].' '.$item['name_lat'] ?></td>
                    <td  ><?=$item['blank_number'] ?></td>
                    <td  ><?=$item['document_nomer'] ?></td>
                    <td  ><?=date("d.m.Y",strtotime($item['blank_date'])) ?></td>
                    <td  ><?=date("d.m.Y",strtotime($item['testing_date'])) ?></td>


                <? if (!empty($personal_1)):?>
                    <td class="left-border"><?=$item['country'] ?></td>
                    <td><?=date("d.m.Y",strtotime($item['birth_date'])) ?></td>
                    <td class="right-border"><?=$item['birth_place'] ?></td>
                <? endif;?>

                <? if (!empty($personal_2)):?>
                    <td class="left-border"><?=$item['passport_name'] ?></td>
                    <td><?=$item['passport_series'].' '.$item['passport'] ?></td>
                    <td><?=date("d.m.Y",strtotime($item['passport_date'])) ?></td>
                    <td class="right-border"><?=$item['passport_department'] ?></td>
                <? endif;?>

                <? if (!empty($personal_3)):?>

                    <td><?=$item['test_level'] ?></td>
                 <? endif;?>


                <? if (!empty($personal_4)):?>
                    <?

                    $level = TestLevel::getByID($item['test_level_id']);
                    $sub_tests = $level->getSubTests();
$subtests_array = array();

echo '<td class="left-border">';
foreach ($sub_tests as $key => $subtest)
                    {
                        $ball_number='test_'.($key+1).'_ball';

                        $subtests_array[]= $subtest->short_caption.':'.$item[$ball_number];
                    }
                    echo implode('; ',$subtests_array);
                    echo '</td class="right-border">';
?>
                <? endif;?>

                   </tr>
            <? endforeach ?>
            </tbody>
        </table>

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


<?/*
    var copyEmailBtn = document.querySelector('.js-emailcopybtn');
    copyEmailBtn.addEventListener('click', function(event) {
        // ������� ������ � ����������� ������
        var emailLink = document.querySelector('.table');
        var range = document.createRange();
        range.selectNode(emailLink);
        window.getSelection().addRange(range);

        try {
            // ������, ����� �� ������� ����� ������, �������� ������� �����������
            var successful = document.execCommand('copy');
            var msg = successful ? 'successful' : 'unsuccessful';
            console.log('Copy email command was ' + msg);
        } catch(err) {
            console.log('Oops, unable to copy');
        }

        // ������ ��������� - ��������: �� ������ ������������
        // removeRange(range) ����� ��� ��������
        window.getSelection().removeAllRanges();
    });
*/?>
</script>

