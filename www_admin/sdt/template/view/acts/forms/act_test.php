<?php
$current_level_id=$Act->level_id;
if(!$current_level_id){
	$current_level=current($Levels);
	$current_level_id=$current_level->id;
}


?>
<div style="color:red; background-color: #0000CD; padding: 5px;">
    <strong>Общая стоимость тестирования в данных документах не вводится.<br>
    Выбирается только уровень тестирования и вводится количество протестированных в сессию!</strong>
</div>
<form method="post" action="<?php echo $_SERVER['REQUEST_URI']?>"
	class="form-horizontal">
	<?php if(!empty($Legend)):?>
	<legend>
		<?php echo $Legend; ?>
	</legend>
	<?php endif;?>

	<div class="control-group">
		<label class="control-label" for="level_id">Уровень тестирования
		</label>
		<div class="controls">
			<select id="level_id" name="level_id"
				class="input-xxlarge">
				<?php  foreach($Levels as $level):?>
				<option <?php if($current_level_id==$level->id):?>
					selected="selected" <?php endif;?>
					value="<?php echo $level->id;?>">
					<?php echo $level;?>
				
				</option>
				<?php endforeach;?>
			</select>
		</div>
	</div>

    <fieldset>
        <legend><strong>Cдающие первый раз</strong></legend>
	<div class="control-group">
            <label class="control-label" for="people_first">Количество людей</label>

		<div class="controls">
                <input class="input-xxlarge" type="text" name="people_first"
                       id="people_first" value="0">
		</div>
	</div>
       <!--
	<div class="control-group">
            <label class="control-label" for="money_first">Общая стоимость тестирования</label>

		<div class="controls">
			
				<input class="input-xxlarge" type="text"
                       name="money_first" id="money_first"
                       value="0">


            </div>
        </div>
        -->
    </fieldset>
    <br>
    <fieldset>
        <legend><strong>Пересдачи <span style="color:red">(Все уровни, кроме "Базовый для трудящихся мигрантов")</span></strong></legend>
        <div class="control-group">
            <label class="control-label" for="people_retry">Количество людей</label>
					
            <div class="controls">
                <input class="input-xxlarge" type="text" name="people_retry"
                       id="people_retry" value="0">
            </div>
        </div>
        <!--<div class="control-group">
            <label class="control-label" style="padding-top: 0 " for="people_subtest_retry">Количество субтестов по всем тестируемым</label>

            <div class="controls">
                <input class="input-xxlarge" type="text" name="people_subtest_retry"
                       id="people_subtest_retry" value="0">
		</div>-->
	</div>
       <!--
        <div class="control-group">
            <label class="control-label" for="money_retry">Общая стоимость пересдач тестирования</label>

            <div class="controls">

                <input class="input-xxlarge" type="text"
                       name="money_retry" id="money_retry"
                       value="0">
	

            </div>
        </div>
        -->
    </fieldset>
	<div class="form-actions">
		<button class="btn btn-success" type="submit">Сохранить</button>

	</div>
</form>
