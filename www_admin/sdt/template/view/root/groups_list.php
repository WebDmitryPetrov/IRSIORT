<a class="btn btn-primary btn-small" href="index.php?action=groups_add">Добавить</a>
<?
$result='';
//$res=mysql_query("select g_name,g_id from tb_groups") or die(mysql_error());
$result.='<table  class="table table-bordered  table-striped">';
    $result.="<tr><th>Название роли</th><th colspan='3'>Функции</th></tr>";
//$Groups=Groups::getAll();
//    while($row= mysql_fetch_array($res))
foreach ($list as $group)
    {
    $result.= "\t<tr>

    <td><span id='".$group->g_id."'>".$group->g_name."( ID #".$group->g_id.")</span></td>

    <td><a href='index.php?action=groups_edit&id=".$group->g_id."' class='btn btn-warning btn-small'>Переименовать</a></td>
    <td><a href='index.php?action=groups_delete&id=".$group->g_id."' class='btn btn-danger btn-small' onclick=\"return confirm('Вы уверены?')\">Удалить</a></td>
        </tr>\n";
    //	$result.= "\t<tr><td><img src='./ico/textfield_rename.gif'/></td><td><img src='./ico/cross.gif'/></td><td>".$row[0]."</td></tr>\n";
    }
    $result.= "</table>";

echo $result;
?>


