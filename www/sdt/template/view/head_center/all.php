<h1>������ �������� �������</h1>
<a class="btn btn-primary btn-small" href="index.php?action=head_center_add">��������</a>
<table  class="table table-bordered  table-striped">
<?php

foreach($list as $item): ?>
<tr>
<td>
<?php echo $item->name; ?>
</td>
<td>
<a  class="btn btn-info btn-small" href="index.php?action=head_center_view&id=<?php echo $item->id; ?>">��������</a>
<a  class="btn btn-warning btn-small" href="index.php?action=head_center_edit&id=<?php echo $item->id; ?>">�������������</a>
<a href="index.php?action=head_center_delete&id=<?php echo $item->id; ?>" onclick="return confirm('�� �������?');" class="btn btn-danger  btn-small">�������</a>
</td>
</tr>	

<?php  endforeach;?>

</table>