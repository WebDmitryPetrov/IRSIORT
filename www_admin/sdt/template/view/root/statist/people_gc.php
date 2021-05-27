<?
$reportName = !empty($caption) ? $caption : 'Без названия';
?>
    <style>
        tr.type-head {
            font-weight: bold;
        }
    </style>
    <h1><?=  $reportName ?></h1>
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
     <label>Головной центр тестирования :
            <div>

                <select name="hc">
<!--                    <option value=0>По всем головным центрам</option>-->
                    <?
                    foreach($hc_list as $item)
                    {
//                        if ($item->id != 1 && $item->id != 2 && $item->id != 7 && $item->id != 8) continue;
                        if ($item->id==$_POST['hc']) $selected='selected="selected"';
                        else $selected='';

                        $name=$item->short_name?$item->short_name:$item->name;

                        echo '<option value='.$item->id.' '.$selected.'>'. $name .'</option>';
                    }
                    ?>
                </select>
            </div>
        </label>

        <input type="submit" value="Отфильтровать">
    </form>
<? if (!empty($result)): ?>
    <h1><?=$reportName?> с <?= $from ?> по  <?= $to ?> </h1>
    <table class="table table-bordered">
    <thead>
    <tr>
        <th  >ФИО рус</th>
        <th  >ФИО лат</th>
        <th  >Паспорт</th>
        <th  >Гражданство</th>
        <th  >Уровенть тестирования</th>
        <th  >Дата тестирования</th>
        <th  >Дата печати</th>
        <th  >Номер бланка</th>
        <th>Регистрационный номер</th>
        <th>Тип документа</th>
        <th>Дубликаты</th>


    </tr>

    </thead>
    <tbody>
    <? foreach ($result as $key => $item): ?>






            <tr>
                <td  ><?=$item['fio_rus']?></td>
                <td  ><?=$item['fio_lat']?></td>
                <td  ><?=$item['passport']?></td>
                <td  ><?=$item['country']?></td>
                <td  ><?=$item['caption']?></td>
                <td  ><?=$item['test_date']?></td>
                <td  ><?=$item['blank_date']?></td>
                <td  ><?=$item['blank_number']?></td>
                <td  ><?=$item['document_nomer']?></td>
                <td  ><?=$item['document']?></td>
                <td  ><?=$item['duplicate_number']?></td>
            </tr>
    <? endforeach ?>
        </tbody>
    </table>

<? endif; ?>