<h1><?=$caption?></h1>


<form action="" method="post">

    <?
    require_once('filters_for_reports.php');
    checkboxes_slide(FederalDCs::getAll(),'Выбрать округа',@$_POST['districts'],'districts');
    checkboxes_slide(Regions::getAll(),'Выбрать регионы',@$_POST['regions'],'regions');
    checkboxes_slide(HeadCenters::getAll(),'Выбрать ГЦ',@$_POST['head_centers'],'head_centers', 'short_name');
    checkboxes_slide(TestLevels::getAllByGroups($test_level_type),'Выбрать уровни тестирований',@$_POST['test_levels'],'test_levels');
    date_from_to($from, $to);
    ?>

    <input type="hidden" name="filter" value=1>

    <input type="submit" value="Отфильтровать">
</form>


<? if (!empty($_POST['filter']))
{
    ?>




<table class="table table-bordered  table-striped">
    <tr>
        <th>Округ</th>
        <th>Регион, Уровень</th>
        <th>Справка</th>
        <th>Сертификат</th>
    </tr>
<?php





$total_certs=$total_notes=0;

$last_dc=$last_reg=0;
foreach ($result as $dc_key => $item) // ГЦ
{






    if ($last_dc != $item['f_id'])
    {

        $total_dc_certs= $total_dc_notes=0;

        echo '<tr>
                            <td >'.$item['f_cap'].'</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            </tr>
                                ';
    }




    if ($last_reg != $item['r_id'])
    {

        $total_reg_certs= $total_reg_notes=0;

        //$rname=$item['r_cap'];

        echo '<tr>
                            <td></td>
                            <td >'.$item['r_cap'].'</td>
                            <td></td>
                            <td></td>
                            </tr>
                                ';
    }


    echo '<tr>
                            <td></td>
                            <td >'.$item['tl_cap'].'</td>
                            <td>'.$item['notes'].'</td>
                            <td>'.$item['certs'].'</td>
                            </tr>
                                ';


    $last_dc=$item['f_id'];
    $last_reg=$item['r_id'];

    $total_reg_certs+=$item['certs'];
    $total_reg_notes+=$item['notes'];
    $total_dc_certs+=$item['certs'];
    $total_dc_notes+=$item['notes'];
    $total_certs+=$item['certs'];
    $total_notes+=$item['notes'];




    if(empty($result[$dc_key+1]['r_id']) || $result[$dc_key+1]['r_id'] != $item['r_id'])
    {
        echo '<tr>
                <td></td>
                <td><b>Всего по уровням</b></td>
                <td>'.$total_reg_notes.'</td>
                <td>'.$total_reg_certs.'</td>
                </tr>
                ';
    }

    if(empty($result[$dc_key+1]['f_id']) || $result[$dc_key+1]['f_id'] != $item['f_id'])
    {
        echo '<tr>
                <td></td>
                <td><b>Всего по округу</b></td>
                <td>'.$total_dc_notes.'</td>
                <td>'.$total_dc_certs.'</td>
                </tr>
                ';
    }





}



echo '<tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>

                </tr>
                ';

echo '<tr>
                <td></td>
                <td><b>Итого</b></td>
                <td>'.$total_notes.'</td>
                <td>'.$total_certs.'</td>
                </tr>
                ';


//echo memory_get_usage(1);
echo '</table>';

        echo '<b>Количество центров, в которых не проставлен регион: '.(
        mysql_num_rows(
            mysql_query(
                'select * from sdt_university su where su.region_id is null or su.region_id = 0')
        )).'</b>';

    }

