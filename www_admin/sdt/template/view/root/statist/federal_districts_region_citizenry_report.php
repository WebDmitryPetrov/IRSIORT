<style>
    .top_center
    {
        text-align: center !important;
        vertical-align: top !important;
    }

</style>

<h1><?=$caption?></h1>
<i><?=$regions_for_title?></i>


<form action="" method="post">

    <?
    require_once('filters_for_reports.php');
    date_from_to($from, $to);
    checkboxes_slide(FederalDCs::getAll(),'Выбрать округа',@$_POST['districts'],'districts');
    checkboxes_slide(Regions::getAll(),'Выбрать регионы',@$_POST['regions'],'regions','',0,1);
//для сортировки как на главной странице    checkboxes_slide(HeadCenters::getAllSortedLikeMainPage(),'Выбрать ГЦ',@$_POST['head_centers'],'head_centers', 'short_name',1);
    checkboxes_slide(HeadCenters::getAll(),'Выбрать ГЦ',@$_POST['head_centers'],'head_centers', 'short_name',1,0);
    ?>

  <input type="hidden" name="filter" value=1>

<input type="submit" value="Отфильтровать">
</form>






<?php
/**
 * Created by JetBrains PhpStorm.
 * User: LWR
 * Date: 25.08.15
 * Time: 17:46
 * To change this template use File | Settings | File Templates.
 */

if (!empty($_POST['filter'])) {

/* обычный вывод
    foreach ($result as $hc_id => $values) {
        if ($hc_id == 0) $hc_name = 'Объединённый РУДН';
        else $hc_name = HeadCenter::getByID($hc_id)->short_name;
        echo '<h3>' . $hc_name . '</h3>';
        echo '<table class="table table-bordered  table-striped"><tr><th>Гражданство</th><th>Протестировно</th></tr>';
        foreach ($values as $key => $val) {
            echo '<tr><td>' . $val['country'] . '</td><td>' . $val['people'] . '</td></tr>';
        }
        echo '</table>';
    }

    */



/*с выводом пустых и сортировкой а-ля главная страница - начало */


        //вывод объединенного РУДН
        if (!empty($result[0]))
        {
            draw_result(0,$result[0]);

        }

        /* для сортировки как на главной странице
        foreach (HeadCenters::getAllSortedLikeMainPage() as $val)

        {
            if (in_array($val->id, $_POST['head_centers'])) {
                if (empty($result[$val->id])) $vals = array();
                else {
                    $vals = $result[$val->id];
                }
                    draw_result($val->id, $vals);

            }

        }*/

if (!empty($headcenters))
        foreach ($headcenters as $val) {

            if (empty($result[$val])) $vals = array();
            else {
                $vals = $result[$val];
            }
            draw_result($val, $vals);


        }


}

        function draw_result($hc_id,$values){

            if ($hc_id == 0) $hc_name = 'Объединённый РУДН';
            else $hc_name = HeadCenter::getByID($hc_id)->short_name;
            echo '<h3>' . $hc_name . '</h3>';
            echo '<table class="table table-bordered  table-striped"><tr><th style="width:60%">Гражданство</th><th>Протестировно</th></tr>';
            $empty='';
            foreach ($values as $k => $list) {
                if (empty($k)) $empty.= '<tr><td colspan="2" style = "font-weight: bold; text-align: center">Без проставленного региона</td></tr>';
                else echo '<tr><td colspan="2" style = "font-weight: bold; text-align: center">'.Region::getByID($k).'</td></tr>';

                foreach ($list as $key => $val) {
                    $temp_echo = '<tr><td>' . $val['country'] . '</td><td>' . $val['people'] . '</td></tr>';
                    if (empty($k)) $empty.=$temp_echo;
                    else echo $temp_echo;
                }
            }
            echo $empty;
            echo '</table>';
         /*конец вывода с сортировкой */
        }