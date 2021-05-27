<style>
    .top_center
    {
        text-align: center !important;
        vertical-align: top !important;
    }

</style>

<h1><?=$caption?></h1>


<form action="" method="post">

    <?
    require_once('filters_for_reports.php');
    checkboxes_slide(FederalDCs::getAll(),'������� ������',@$_POST['districts'],'districts');
    checkboxes_slide(Regions::getAll(),'������� �������',@$_POST['regions'],'regions');
    checkboxes_slide(HeadCenters::getAll(),'������� ��',@$_POST['head_centers'],'head_centers', 'short_name');
    ?>

  <input type="hidden" name="filter" value=1>

<input type="submit" value="�������������">
</form>






<?php
/**
 * Created by JetBrains PhpStorm.
 * User: LWR
 * Date: 25.08.15
 * Time: 17:46
 * To change this template use File | Settings | File Templates.
 */

if (!empty($_POST['filter']))
{



$rowspan=0; //���� >0 - �� ��������� � rowspan








$in_reg=array();
foreach ($result as $dc_key => $dc)
{

    foreach($dc as $reg_key => $reg)
    {
        $in_reg[$dc_key][$reg_key]=0;
        $counter=count($reg);

        $in_reg[$dc_key][$reg_key]=$counter;


    }

}


?>
<table class="table table-bordered  table-striped">
    <tr>
        <th>����������� �����</th>
        <th>���������� ������� � ������</th>
        <th>������</th>
        <th>�������� �����</th>
        <th>���������� ������� � �������</th>
    </tr>
    <?php



if ($rowspan)
{
    /*������ � rowspan*/






    foreach ($result as $dc_key => $dc) // ������
    {

        $centers_in_dc=array_sum($in_reg[$dc_key]); //���-�� ������� � ������
        $regions_in_dc=count($in_reg[$dc_key]); // ���-�� �������� � ������
        $rows=$centers_in_dc+$regions_in_dc; //���-�� ����� ��� rowspan



        echo '<tr>
        <td class="top_center" rowspan="'.$rows.'">'.FederalDC::getByID($dc_key)->caption.'</td>
        <td class="top_center" rowspan="'.$rows.'">'.$centers_in_dc.'</td>';



        $last_reg=0;

        foreach($dc as $reg_key => $reg) //�������
        {


            foreach ($reg as $un_key => $item) //������
            {
                if (empty($item['u_sname']))
                    $uname=$item['u_name'];
                else $uname=$item['u_sname'];

                if ($last_reg != $reg_key)
                {
                echo '<td ><b>'.$item['r_cap'].'</b></td>';
                echo '<td ></td>';
                echo '<td class="top_center" rowspan="'. ($in_reg[$dc_key][$reg_key]+1) .'">'. $in_reg[$dc_key][$reg_key] .'</td></tr>';
                    echo '<td >'.$uname.'</td>';
                    echo '<td >'.$item['h_name'].'</td></tr>';
                }
                else
                {
                    echo '<td >'.$uname.'</td>';
                    echo '<td >'.$item['h_name'].'</td></tr>';

                }



                $last_reg=$reg_key;

            }



        }


    }
} /*����� � rowspan*/





else
{
 /*������ ��� rowspan*/

    $last_dc=0;
    foreach ($result as $dc_key => $dc) // ������
    {

        $centers_in_dc=array_sum($in_reg[$dc_key]); //���-�� ������� � ������
        $regions_in_dc=count($in_reg[$dc_key]); // ���-�� �������� � ������
        $rows=$centers_in_dc+$regions_in_dc; //���-�� ����� ��� rowspan




        echo '<tr>
        <td >'.FederalDC::getByID($dc_key)->caption.'</td>
        <td >'.$centers_in_dc.'</td>';



        $last_reg=0;

        foreach($dc as $reg_key => $reg) //�������
        {


            foreach ($reg as $un_key => $item) //������
            {
                if (empty($item['u_sname']))
                    $uname=$item['u_name'];
                else $uname=$item['u_sname'];

                if ($last_reg != $reg_key)
                {


                    if ($last_dc == $dc_key)
                        echo '<tr><td></td><td></td>';

                    echo '<td ><b>'.$item['r_cap'].'</b></td>';
                    echo '<td ></td>';
                    echo '<td >'. $in_reg[$dc_key][$reg_key] .'</td></tr>';


                    echo '<tr><td></td><td></td>';

                    echo '<td >'.$uname.'</td>';
                    echo '<td >'.$item['h_name'].'</td><td></td></tr>';
                }
                else
                {

                    echo '<tr><td></td><td></td>';



                    echo '<td >'.$uname.'</td>';
                    echo '<td >'.$item['h_name'].'</td>';

                    echo '<td></td></tr>';

                }



                $last_reg=$reg_key;


                $last_dc=$dc_key;

            }



        }


    }
    /*����� ��� rowspan*/



}
echo '</table>';

    echo '<b>���������� �������, � ������� �� ���������� ������: '.(
    mysql_num_rows(
        mysql_query(
            'select * from sdt_university su where su.deleted = 0 and (su.region_id is null or su.region_id = 0)')
    )).'</b>';

}
//echo memory_get_usage(1);