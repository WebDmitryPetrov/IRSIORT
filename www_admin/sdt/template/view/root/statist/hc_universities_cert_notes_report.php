
<h1><?=$caption?></h1>

<form action="" method="post">

    <?
    require_once('filters_for_reports.php');
//    checkboxes_slide(FederalDCs::getAll(),'Выбрать округа',@$_POST['districts'],'districts');
//    checkboxes_slide(Regions::getAll(),'Выбрать регионы',@$_POST['regions'],'regions');
    checkboxes_slide(HeadCenters::getAll(),'Выбрать ГЦ',@$_POST['head_centers'],'head_centers', 'short_name');
//    checkboxes_slide(TestLevels::getAll(),'Выбрать ГЦ',@$_POST['head_centers'],'head_centers', 'short_name');
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
        <th>Головной центр</th>

        <th>Центр, Уровень</th>
        <th>Справка</th>
        <th>Сертификат</th>
    </tr>
    <?php







    $total_certs=$total_notes=0;

    $last_hc=$last_un=0;
    foreach ($result as $hc_key => $item) // ГЦ
    {






                    if ($last_hc != $item['h_id'])
                    {

                        $total_hc_certs= $total_hc_notes=0;

                            echo '<tr>
                            <td >'.$item['h_name'].'</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            </tr>
                                ';
                    }




                     if ($last_un != $item['u_id'])
                    {

                        $total_un_certs= $total_un_notes=0;

                        if (empty($item['u_sname']))
                            $uname=$item['u_name'];
                        else $uname=$item['u_sname'];

                            echo '<tr>
                            <td></td>
                            <td >'.$uname.'</td>
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


        $last_hc=$item['h_id'];
        $last_un=$item['u_id'];

            $total_un_certs+=$item['certs'];
            $total_un_notes+=$item['notes'];
            $total_hc_certs+=$item['certs'];
            $total_hc_notes+=$item['notes'];
            $total_certs+=$item['certs'];
            $total_notes+=$item['notes'];




        if(empty($result[$hc_key+1]['u_id']) || $result[$hc_key+1]['u_id'] != $item['u_id'])
        {
            echo '<tr>
                <td></td>
                <td><b>Всего по уровням</b></td>
                <td>'.$total_un_notes.'</td>
                <td>'.$total_un_certs.'</td>
                </tr>
                ';
        }

        if(empty($result[$hc_key+1]['h_id']) || $result[$hc_key+1]['h_id'] != $item['h_id'])
        {
            echo '<tr>
                <td></td>
                <td><b>Всего по головному центру</b></td>
                <td>'.$total_hc_notes.'</td>
                <td>'.$total_hc_certs.'</td>
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