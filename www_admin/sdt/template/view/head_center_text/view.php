<?php
/** @var HeadCenter $object */
?>

<a  class="btn btn-danger"
   href="index.php?action=current_head_center_edit_text&h_id=<?php echo $_GET['h_id'];?>">Редактировать</a>


<table class="table table-bordered  table-striped">


	<?php foreach ($object->getEditFields() as $field):

        ?>
	<tr>
		<th><?php echo $object->getTranslate($field); ?>
		</th>
		<td><?php echo $object->$field ?>
		</td>
	</tr>
	<?php

    endforeach;?>
	

</table>
