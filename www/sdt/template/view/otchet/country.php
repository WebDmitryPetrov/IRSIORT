
<form class="form-inline otchet" method="post"
	action="<?php echo $_SERVER['REQUEST_URI']?>">
	<label>Страна <select class="input-medium" name="country">
	<?php foreach($Countries as $country):?>
			<option value="<?php echo $country->id;?>"
			<?php if (isset($_POST['country']) && $country->id==$_POST['country']) echo 'selected=selected';?>>
				<?php echo $country->name;?>
			</option>

			<?php endforeach;?>
	</select>
	</label> <label>С:
		<div class="input-prepend date datepicker"
			data-date="<?php echo $C->date(isset($_POST['from'])?$_POST['from']:date('Y-m-d'))?>"
		>
			<span class="add-on"><i class="icon-th"></i> </span> <input
				class="input-small" name="from" readonly="readonly" size="16"
				type="text"
				value="<?php echo $C->date(isset($_POST['from'])?$_POST['from']:date('Y-m-d')) ?>">
		</div>

	</label> <label>По:
		<div class="input-prepend date datepicker"
			data-date="<?php echo $C->date(isset($_POST['to'])?$_POST['to']:date('Y-m-d'))?>"
			>
			<span class="add-on"><i class="icon-th"></i> </span> <input
				class="input-small" name="to" readonly="readonly" size="16"
				type="text"
				value="<?php echo $C->date(isset($_POST['to'])?$_POST['to']:date('Y-m-d'))?>">
		</div>

	</label>
	<button type="submit" class="btn">Построить</button>
</form>
<?php
if (!empty($Result)):?>
<h1>Количество протестировавшихся</h1>
<table class="table table-bordered  table-striped">
	<thead>
		<tr>
			<th>Уровень тестирования</th>
			<th>Количество</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($Result as $item): ?>
		<tr><td><?php echo $item->level_caption; ?></td><td><?php echo $item->cc; ?></td></tr>
		<?php endforeach;?>
	</tbody>
</table>
<?php endif;?>

