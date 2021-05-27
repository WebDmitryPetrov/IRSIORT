<?php
/**
 * Created by JetBrains PhpStorm.
 * User: LWR
 * Date: 13.01.15
 * Time: 14:00
 * To change this template use File | Settings | File Templates.
 */

//var_dump($array);

?>



<form action="" method="POST">

Акты, созданные до(включительно):
<div class="input-prepend date datepicker "
     data-date=""    >
    <span class="add-on"><i class="icon-th"></i> </span> <input
        class="input-small"
        name="till"
        placeholder="Дата"
        id="birth_date" readonly="readonly" size="16" type="text"
        value="<? echo (!empty($till))? $till: date('d.m.Y');?>" >
</div>
    <input type="submit" value="Отфильтровать">
</form>


<h2 style="color:red">Отфильтровано до <?=$till;?> (включительно)</h2>

<table class="table table-bordered  table-striped">
    <tr>
        <th>id ГЦ</th><th>Название</th><th>Количество актов, готовых к отправке в архив</th><th style="width:10%"></th>
    </tr>


<?



foreach ($array as $hc)
{
    $count=count($hc['acts']);

    if ($count > 0)
    {
       $button='<a class="btn btn-danger btn-block" onclick="return confirm(\'Будут отправлены в архив до '.$till.' (включительно).Вы уверены?\');" href="index.php?action=set_archive_by_head&id='.$hc['id'].'&till='.$till.'" >В архив</a>';
    }
    else
    {
//       $button='';
       $button='<a class="btn btn-danger btn-block disabled" onclick="return confirm(\'Недоступно! Нет документов, готовых к отправке в архив\');">В архив</a>';
    }


    echo '<tr>
        <td>'.$hc['id'].'</td>
        <td>'.$hc['name'].'</td>
        <td>'.$count.'</td>
        <td>'.$button.'</td>
        </tr>';
}




?>
</table>