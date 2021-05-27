<a class="btn btn-primary btn-small" href="index.php?action=user_type_add">Добавить</a>
<?php
$result='';
//var_dump($list);die;
//$res=mysql_query("select g_name,g_id from tb_groups") or die(mysql_error());
$result.='<table  class="table table-bordered  table-striped">';
    $result.="<tr><th>Название типа пользователя</th><th colspan='1'>Функции</th></tr>";
//$types=Groups::getAll();
//    while($row= mysql_fetch_array($res))
foreach ($list as $type)
    {
        //var_dump($type);
    $result.= "\t<tr>

    <td><span id='".$type->id."'>".$type->caption."( ID #".$type->id.")</span></td>
    <td><a href='index.php?action=user_type_rights_edit&id=".$type->id."' class='btn btn-info btn-small'>Роли</a>
    <a href='index.php?action=user_type_edit&id=".$type->id."' class='btn btn-warning btn-small'>Редактировать</a>
    <a href='index.php?action=user_type_delete&id=".$type->id."' class='btn btn-danger btn-small' onclick='return confirm(\"Вы уверены?\")'>Удалить</a></td>
        </tr>\n";
    //	$result.= "\t<tr><td><img src='./ico/textfield_rename.gif'/></td><td><img src='./ico/cross.gif'/></td><td>".$row[0]."</td></tr>\n";
    }
    $result.= "</table>";

echo $result;


