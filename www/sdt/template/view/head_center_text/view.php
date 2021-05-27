<?php
/** @var HeadCenter $object */
?>
<h1>Переменные для печати</h1>
<a  class="btn btn-danger"
   href="index.php?action=current_head_center_edit_text">Редактировать</a>


<table class="table table-bordered  table-striped">


	<?php foreach ($object->getEditFields() as $field):

        ?>
	<tr>
		<th><?php echo $object->getTranslate($field); ?>
		</th>
		<td><?php echo htmlspecialchars($object->$field) ?>
		</td>
	</tr>
	<?php

    endforeach;?>
	

</table>
