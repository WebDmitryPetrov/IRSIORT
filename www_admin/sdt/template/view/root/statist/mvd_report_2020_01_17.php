<?
//echo $selected_lc.'-';
$reportName = !empty($caption) ? $caption : '��� ��������';
if (!isset($lc_caption)) $lc_caption = '';
?>
<style>
    tr.type-head {
        font-weight: bold;
    }

    th {
        vertical-align: top;
    }
</style>
<h1><?= $reportName ?></h1>
<form action="" method="POST" target="_blank">

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
    <label>��� ������������:<br>
       <select name="test_type" size="2">
           <option value="2" selected>����������� �������</option>
           <option value="1">���</option>
       </select>
    </label>
    <label>��������� ����� ����� � ���������������� :
        <input type="checkbox" value="1" name="files">
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

            <select name="hc" id="hc-list" style="width: 400px">
                <!--                <option value=0>�� ���� ������������</option>-->
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


            <select name="lc" id="lc-list"  style="width: 400px">
                <!--                <option value=0>�� ���� �������</option>-->
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
    <h2 style="color: red">��������� ��������� � ����� ����. ���� �� ���������, ���� �� ����� ������� ���������</h2>
</form>
<? if (!empty($search)): ?>

<? endif; ?>

<link rel="stylesheet" href="/css/select2/css/select2.css"/>
<script src="/css/select2/js/select2.full.min.js"></script>

<script>
    $(function () {
        var process = null;
        $('#hc-list').on('change', function () {
            if (process) process.abort();
            var list = $('#lc-list');
            list.find('.lc-item').remove();
            process = $.getJSON('/sdt/index.php?action=ajax_lc_list', {
                    hc: $(this).find(':selected').val()
                },
                function (res) {
//                    console.log(res);

                    _.each(res, function (item, key) {
                        var op = $('<option>');
                        op.addClass('lc-item');
                        op.val(item.id);
                        op.html(item.name);
                        list.append(op);
                    });

                    list.select2();
                }
            );
        });
        $('#hc-list').trigger('change');
    });
</script>



