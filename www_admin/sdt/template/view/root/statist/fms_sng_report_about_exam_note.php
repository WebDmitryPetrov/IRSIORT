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
        <input type="submit" value="Отфильтровать">
    </form>
<? if (!empty($search)): ?>
<h1><?=$reportName?> с <?= $from ?> по  <?= $to ?> </h1>
    <table class="table table-bordered">
        <thead>
        <tr>
            <?/*
            <th  ><p>Образовательная организация</p></th>
            <th  ><p>Количество организаций-партнеров, с которым заключены соглашения</p></th>
            */?>

            <th><p>Страна</p></th>

            <th  colspan="4" ><p>Количество мигрантов, сдавших экзамен</p></th>
            <?/*<th  ><p>Количество выданных сертификатов</p></th>*/?>
            <th  colspan="4" ><p>Количество мигрантов, несдавших экзамен</p></th>
            <?/*<th  ><p>Количество выданных справок</p></th>*/?>

        </tr>
        <tr>
            <?/*<th  ><p>&nbsp;</p></th>*/?>
            <th  ><p>&nbsp;</p></th>

            <th ><p>разрешение на работу или патент (РНР)</p></th>
            <th  ><p>разрешение на временное проживание (РВЖ)</p></th>
            <th  ><p>вид на жительство (ВНЖ)</p></th>
            <th  ><p>общее количество </p></th>
            <?/*<th  ><p>&nbsp;</p></th>*/?>
         <th ><p>разрешение на работу или патент (РНР)</p></th>
            <th  ><p>разрешение на временное проживание (РВЖ)</p></th>
            <th  ><p>вид на жительство (ВНЖ)</p></th>
            <th  ><p>общее количество </p></th>
            <?/*<th  ><p>&nbsp;</p></th>*/?>

        </tr>
        </thead>
        <tbody>
        <? foreach ($array as $item): ?>
            <tr>
                <td  ><?=$item['caption']?></td>
                <?/*<td  ><?=$item['data']['orgs']?></td>*/?>

                <td ><?=$item['data']['levels'][0] ?></td>
                <td  ><?=$item['data']['levels'][1] ?></td>
                <td  ><?=$item['data']['levels'][2] ?></td>
                <td  ><?=array_sum($item['data']['levels'])?></td>
                <?/*<td  ><?=$item['data']['certs']?></td>*/?>

                <td ><?=$item['data']['note_levels'][0] ?></td>
                <td  ><?=$item['data']['note_levels'][1] ?></td>
                <td  ><?=$item['data']['note_levels'][2] ?></td>
                <td  ><?=array_sum($item['data']['note_levels'])?></td>
                <?/*<td  ><?=$item['data']['notes']?></td>*/?>

            </tr>
        <? endforeach ?>
        </tbody>
    </table>

<? endif; ?>