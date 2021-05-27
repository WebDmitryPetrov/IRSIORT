<h1>Список головных центров</h1>
<a class="btn btn-primary btn-small" href="index.php?action=head_center_add">Добавить</a>
<a class="btn btn-success btn-small" href="index.php?action=head_center_prefixes">Префиксы</a>
<table  class="table table-bordered  table-striped">
<?php

foreach($list as $item): ?>
<tr>
<td>
<?php echo $item->name.' ('.$item->href.')';
    //var_dump($_SESSION);
$alert=123;
    //echo $alert;die;
    if (!empty($_SESSION['privelegies']['admin_head'])) echo '<br><a href="#" onclick="window.open(\'index.php?action=apache_conf&id='.$item->id.'\')">настройка для apache</a>';

?>

</td>
<td>

<a  class="btn btn-info btn-small" href="index.php?action=signing_list&h_id=<?php echo $item->id; ?>">Подписывающие</a>
<a  class="btn btn-info btn-small" href="index.php?action=head_center_view&id=<?php echo $item->id; ?>">Карточка</a>
<a  class="btn btn-info btn-small" href="index.php?action=current_head_center_text_view&h_id=<?php echo $item->id; ?>">Переменные для печати</a>
<!--<a  class="btn btn-warning btn-small" href="index.php?action=head_center_edit&id=<?php echo $item->id; ?>">Редактировать</a>-->
<a href="index.php?action=head_center_delete&id=<?php echo $item->id; ?>" onclick="return confirm('Вы уверены?');" class="btn btn-danger  btn-small">Удалить</a>
</td>
</tr>	

<?php  endforeach;?>

</table>