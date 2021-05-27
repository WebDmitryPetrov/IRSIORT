<?php
/**
 * Created by JetBrains PhpStorm.
 * User: LWR
 * Date: 25.08.15
 * Time: 14:48
 * To change this template use File | Settings | File Templates.
 */
echo '<h1>'.$caption.'</h1>';
?>

<table class="table table-bordered  table-striped">
    <tr>

        <th>Название</th>
        <th></th>
    </tr>
    <? foreach($result as $item)
{
  echo '<tr><td>'.$item->caption.'</td><td><a class="btn btn-warning"
  href="?action=federal_dc_regions&id='.$item->id.'">Регионы</a></td></tr>';
}
?>
</table>

<a class="btn btn-success" href="?action=federal_dc_regions_all">Все регионы по округам</a>