<?

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
<h1>Список сертификатов по ЭКЗАМЕНУ по уровням</h1>
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
    </label>
    <label>Организация :
        <div>

            <select name="hc" id="hc-list" style="width:500px">
             
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
        <label>Локальный центр :<br>


            <select name="lc" id="lc-list" style="width:700px">
             
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
    <? //<input type="submit" value="Отфильтровать" onclick="return confirm('Запрос сложный! Продолжить?');">?>
    <input type="submit" value="Отфильтровать">
</form>
<? if ($fired): ?>
    <h1>с <?= $from ?> по  <?= $to ?> </h1>
   
       

        <?
        $from_output = date('j', strtotime($from)) .' '.month(date('m', strtotime($from)),1).' '.date('Y', strtotime($from));
        $to_output = date('j', strtotime($to)) .' '.month(date('m', strtotime($to)),1).' '.date('Y', strtotime($to));
        ?>

        <table class="table table-bordered">
        <thead>
      
        <tr>
            <th class="center"><p>разрешение на работу или патент (РНР)</p></th>
            <th class="center"><p>разрешение на временное проживание (РВЖ)</p></th>
            <th class="center"><p>вид на жительство (ВНЖ)</p></th>
           
        </tr>
        </thead>
        <tbody>
      <tr>
	  <td><?=implode("<br>\n",$arrays[0])?></td>
	  <td><?=implode("<br>\n",$arrays[1])?></td>
	  <td><?=implode("<br>\n",$arrays[2])?></td>
	  
	  </tr>
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
		  <?php if (empty($lc_list)):?>
		  
$('#hc-list').trigger('change');
<? endif?>
    });
</script>

<?
function month($id, $type=0)
{
    $id=(int)$id;
    $month = array(
    '1' => array('0'=>'январь', '1' => 'января'),
    '2' => array('0'=>'февраль', '1' => 'февраля'),
    '3' => array('0'=>'март', '1' => 'марта'),
    '4' => array('0'=>'апрель', '1' => 'апреля'),
    '5' => array('0'=>'май', '1' => 'мая'),
    '6' => array('0'=>'июнь', '1' => 'июня'),
    '7' => array('0'=>'июль', '1' => 'июля'),
    '8' => array('0'=>'август', '1' => 'августа'),
    '9' => array('0'=>'сентябрь', '1' => 'сентября'),
    '10' => array('0'=>'октябрь', '1' => 'октября'),
    '11' => array('0'=>'ноябрь', '1' => 'ноября'),
    '12' => array('0'=>'декабрь', '1' => 'декабря'),
    );
    if (empty($id)) return $id;
    return $month[$id][$type];
}