<?
ini_set('memory_limit','3G');
//echo $selected_lc.'-';
$reportName = !empty($caption) ? $caption : 'Без названия';
if (!isset($lc_caption)) $lc_caption = '';
?>
<style>
    tr.type-head {
        font-weight: bold;
    }
</style>
<h1><?= $reportName ?></h1>
<form action="" method="POST">
<h3>Дата выдачи сертификата</h3>
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


    <input type="submit" value="Отфильтровать" onclick="return confirm('Запрос сложный! Продолжить?');">
</form>
<? if (!empty($search)): ?>
    <h1><?=$reportName?> с <?= $from ?> по  <?= $to ?> </h1>
    <? foreach ($arrays as $key => $array): ?>
        <?php if (!empty($key) && is_numeric($key)): ?>
            <h3><?= HeadCenter::getOrgName($key) ?> <?= $lc_caption ?></h3>
        <? else/*if ($selected_hc==='pfur')*/
            : ?>
            <h3>По всем организациям</h3>
        <? endif; ?>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th  ><p>ФИО получателя сертификата</p></th>
            <th  ><p>Дата рождения</p></th>
            <th  ><p>Место рождения</p></th>
            <th  ><p>Паспортные данные (серия, номер)</p></th>
            <th  ><p>Направление обучения</p></th>
            <th  ><p>Образовательное учреждение</p></th>
            <th  ><p>Место регистрации/нахождения</p></th>


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

