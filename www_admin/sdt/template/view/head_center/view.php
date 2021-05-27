<?php
/** @var HeadCenter $object */
?>

<a  class="btn btn-danger"
   href="index.php?action=head_center_edit&id=<?php echo $object->id; ?>">Редактировать</a>


<table class="table table-bordered  table-striped">


	<?php foreach ($object->getEditFields() as $field):
        if($field!='country_id'):
        ?>
	<tr>
		<th><?php echo $object->getTranslate($field); ?>
		</th>
		<td><?php echo $object->$field ?>
		</td>
	</tr>
	<?php
    endif;
    if($field=='country_id'):
        ?>
    <tr>
        <th><?php echo $object->getTranslate($field); ?>
        </th>
        <td><?=$object->country_id?$object->getCountry()->name:'' ?>
        </td>
    <?php
    endif;


    endforeach;?>
	

</table>
