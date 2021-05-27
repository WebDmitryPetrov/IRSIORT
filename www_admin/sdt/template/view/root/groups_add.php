<a class="btn btn-primary btn-small" href="index.php?action=test_levels_add">Добавить</a>
<?
$result='';
$res=mysql_query("select g_name,g_id from tb_groups") or die(mysql_error());
$result.='<table  class="table table-bordered  table-striped">';
    $result.="<tr><th>Название группы</th><th colspan='3'>Функции</th></tr>";

    while($row= mysql_fetch_array($res))
    {
    $result.= "\t<tr>

    <td><span id='".$row[1]."'>".$row[0]."( ID #".$row['g_id'].")</span></td>
    <td><a href='#' class='btn btn-success btn-small' onClick=\"show_add_to_group('".$row[1]."')\">Редактировать</a></td>
    <td><a href='#' class='btn btn-warning btn-small onClick=\"show_rename_group_field('".$row[1]."')\">Переименовать</a></td>
    <td><a href='#' class='btn btn-danger btn-small onClick=\"show_delete_group('".$row[1]."')\">Удалить</a></td>
        </tr>\n";
    //	$result.= "\t<tr><td><img src='./ico/textfield_rename.gif'/></td><td><img src='./ico/cross.gif'/></td><td>".$row[0]."</td></tr>\n";
    }
    $result.= "</table>";

echo $result;
?>


<input type="text" name="g_name">
<input type="checkbox" name="head_visible">
<input type="text" name="g_name">