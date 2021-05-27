<?php 
$Act=$ActTest->getAct();
?>

<h3>Дата тестирования: <?php echo $C->date($Act->testing_date);?></h3>
<h4>Университет: <?php echo $Act->getUniversity();?></h4>
<h4>Договор: <?php echo $Act->getUniversityDogovor();?></h4>
<form method="post" action="<?php echo $_SERVER['REQUEST_URI']?>"
	class="form-horizontal marks" >
	<?php if(!empty($Legend)):?>
	<legend>
		<?php echo $Legend; ?> (уровень тестирования: <?php  $level=$ActTest->getLevel(); echo  $level->caption?>)
	</legend>
	<?php endif;?>

	<table
		class="table table-bordered  table-striped table-condensed ">
		<thead>
			<tr>
				<td rowspan="2"><strong>Фамилия</strong><br /> русскими <br />
					латинскими</td>
				<td rowspan="2"><strong>Имя</strong><br /> русскими<br /> латинскими</td>
				<td rowspan="2"><strong>Страна</strong></td>
				<td rowspan="2"><strong>Дата теста</strong></td>
				<td colspan="6" class="center"><strong>Результаты</strong> (баллы /
					%)</td>
				<td rowspan="2" class="center"><strong>Итог</strong>
				</td>
			</tr>
			<tr>
				<td class="center">Чт<span class="percent"><?php echo $level->reading; ?></span></td>
				<td class="center">Пис<span class="percent"><?php echo $level->writing; ?></span></td>
				<td class="center">Лекс<span class="percent"><?php echo $level->grammar; ?></span></td>
				<td class="center">Ауд<span class="percent"><?php echo $level->listening; ?></span></td>
				<td class="center">Уст<span class="percent"><?php echo $level->speaking; ?></span></td>
				<td class="center">Общ<span class="percent"><?php echo $level->total; ?></span></td>
			</tr>
		</thead>
		<tbody>
			<?php foreach($ActTest->getPeople() as $Man):?>
			<tr class="" >
			
				<td><input type="text"
					name="user[<?php echo $Man->id; ?>][surname_rus]"
					class="input-small" placeholder="по-русски"
					value="<?php echo $Man->surname_rus; ?>"> <br> <input type="text"
					name="user[<?php echo $Man->id; ?>][surname_lat]"
					class="input-small" placeholder="латиницей"
					value="<?php echo $Man->surname_lat; ?>">


				</td>
				<td><input type="text"
					name="user[<?php echo $Man->id; ?>][name_rus]" class="input-small"
					placeholder="по-русски" value="<?php echo $Man->name_rus; ?>"> <br>
					<input type="text" name="user[<?php echo $Man->id; ?>][name_lat]"
					class="input-small" placeholder="латиницей"
					value="<?php echo $Man->name_lat; ?>">
				</td>
				<td><select name="user[<?php echo $Man->id; ?>][country_id]"
					class="input-small">
						<?php foreach($Countries as $country):?>
						<option value="<?php echo $country->id;?>"
						<?php if ($country->id==$Man->country_id) echo 'selected=selected';?>>
							<?php echo $country->name;?>
						</option>

						<?php endforeach;?>
				</select></td>
				<td><div class="input-prepend date datepicker "
						data-date="<?php if ($Man->testing_date!='0000-00-00') echo $C->date($Man->testing_date); else echo $C->date($Act->testing_date); ?>"
						>
						<span class="add-on"><i class="icon-th"></i> </span> <input
							class="input-small"
							name="user[<?php echo $Man->id; ?>][testing_date]"
							id="testing_date" readonly="readonly" size="16" type="text"
							value="<?php if ($Man->testing_date!='0000-00-00') echo $C->date($Man->testing_date); else echo $C->date($Act->testing_date); ?>">
					</div></td>
				<td><input type="text" maxlength="5"
					name="user[<?php echo $Man->id; ?>][reading]" class="scores"
					value="<?php echo $Man->reading; ?>">
					<span class="percent"><?php echo $Man->reading_percent; ?>%</span>
				</td>
				<td><input type="text" maxlength="5"
					name="user[<?php echo $Man->id; ?>][writing]" class="scores"
					value="<?php echo $Man->writing; ?>">
						<span class="percent"><?php echo $Man->writing_percent; ?>%</span>
				</td>
				<td><input type="text" maxlength="5"
					name="user[<?php echo $Man->id; ?>][grammar]" class="scores"
					value="<?php echo $Man->grammar; ?>">
						<span class="percent"><?php echo $Man->grammar_percent; ?>%</span>
				</td>
				<td><input type="text" maxlength="5"
					name="user[<?php echo $Man->id; ?>][listening]" class="scores"
					value="<?php echo $Man->listening; ?>">
						<span class="percent"><?php echo $Man->listening_percent; ?>%</span>
				</td>
				<td><input type="text" maxlength="5"
					name="user[<?php echo $Man->id; ?>][speaking]" class="scores"
					value="<?php echo $Man->speaking; ?>">
						<span class="percent"><?php echo $Man->speaking_percent; ?>%</span>
				</td>
				<td><input type="text" maxlength="5"
					name="user[<?php echo $Man->id; ?>][total]" class="scores"
					value="<?php echo $Man->total; ?>" readonly="readonly">
						<span class="percent"><?php echo $Man->total_percent; ?>%</span>
					</td>
				<td>  <span class="<?php echo $Man->document;?> ">
                        <?php echo $Man->document=="certificate"?'Сертификат':'Справка' ?>
                        </span></td>
			</tr>
			<?php endforeach;?>
		</tbody>
	</table>


	<div class="form-actions">
		<button class="btn btn-success" type="submit">Сохранить</button>

	</div>
</form>
