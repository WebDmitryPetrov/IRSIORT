<?
//echo $selected_lc.'-';
$reportName = !empty($caption) ? $caption : 'Без названия';
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

    <label>от :
        <div class="input-prepend date datepicker "
             data-date="">
            <span class="add-on"><i class="icon-th"></i> </span> <input
                class="input-small"
                name="from"

                readonly="readonly" size="16" type="text"
                value="<?= $from ?>">
        </div>
    </label> <label>До :
        <div class="input-prepend date datepicker "
             data-date="">
            <span class="add-on"><i class="icon-th"></i> </span> <input
                class="input-small"
                name="to"

                readonly="readonly" size="16" type="text"
                value="<?= $to ?>">
        </div>
    </label> <!--<label>Регион :
            <div>

                <select name="region">
                    <? /*
                    foreach($regions_list as $key => $value)
                    {
                        if (!$key)
                        {
                            echo '<option value='.$key.'>По всем регионам</option>';
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
    <label>Организация :
        <div>

            <select name="hc" id="hc-list">
                <option>Выберите головной центр</option>
                <?
                foreach ($hc_list as $item) {
                    /*  if ($item->id != 1 && $item->id != 2 && $item->id != 7 && $item->id != 8) {
                          continue;
                      }*/
                    if ($item['id'] == $head_id) {
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


        <label>Локальный центр :<br>


            <select name="lc" id="lc-list">

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

    <? //<input type="submit" value="Отфильтровать" onclick="return confirm('Запрос сложный! Продолжить?');">?>
    <input type="submit" value="Отфильтровать">
</form>
<? if (!empty($search)): ?>


<? endif; ?>


<script>
    $(function () {
        var process = null;
        $('#hc-list').on('change', function () {
            if (process) process.abort();
            process = $.getJSON('/sdt/index.php?action=ajax_lc_list_by_hc', {
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

