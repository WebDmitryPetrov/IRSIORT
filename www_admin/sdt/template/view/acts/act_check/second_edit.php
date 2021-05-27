<?php
/** @var University $University */
?>

<a href="/sdt/index.php?action=act_second_view&id=<?php echo $Act->id ?>" class="btn btn-info">Вернуться в акт</a>
<form method="post" action="<?php echo $_SERVER['REQUEST_URI']?>"
      class="form-horizontal">
    <?php if (!empty($Legend)): ?>
        <legend>
            <?php echo $Legend; ?>
        </legend>
    <?php endif; ?>
    <div class="control-group">
        <label class="control-label" for="number">Номер</label>

        <div class="controls">

            <input class="input-xxlarge" type="text" name="number"
                   id="number"
                   value="<?php  echo $Act->number; ?>">


        </div>
    </div>


    <div class="control-group">
        <label class="control-label" for="university_dogovor_id">Договор ВУЗа
        </label>

        <div class="controls">
            <select id="university_dogovor_id" name="university_dogovor_id"
                    class="input-xxlarge">
                <?php  foreach ($University->getDogovors() as $dogovor): ?>

                    <option
<?php if ($Act->university_dogovor_id==$dogovor->id):?>
    selected="selected"
                        <?php endif; ?>

                        value="<?php echo $dogovor->id;?>">
                        <?php echo $dogovor->number;?>
                        <?php echo $C->date($dogovor->date);?>
                        <?php echo $dogovor->caption;?>
                    </option>

                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="testing_date">Дата тестирования </label>

        <div class="controls">

            <div class="input-prepend date div_testing_date datepicker"
                 data-date="<?php echo $C->date($Act->testing_date ? $Act->testing_date : date('Y-m-d')) ?>"
              >
                <span class="add-on"><i class="icon-th"></i> </span> <input
                    class="input-xxlarge" name="testing_date" id="testing_date"
                    readonly="readonly" size="16" type="text"
                    value="<?php  echo $C->date($Act->testing_date ? $Act->testing_date : date('Y-m-d'))?>">
            </div>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="official">Должностное лицо, утверждающее акт</label>

        <div class="controls">

            <input class="input-xxlarge" type="text" name="official"
                   placeholder="Проректор Сидоров И.П."
                   id="official"
                   value="<?php  echo $Act->official; ?>">


        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="responsible">Ответственный за проведение тестрования </label>

        <div class="controls">

            <input class="input-xxlarge" type="text" name="responsible"
                   id="responsible"
                   value="<?php  echo $Act->responsible; ?>">


        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="comment">Комментарий</label>

        <div class="controls">
            <textarea class="input-xxlarge" name="comment" id="comment"><?php  echo $Act->comment; ?></textarea>
        </div>
    </div>


    <div class="form-actions">
        <button class="btn btn-success" type="submit">Сохранить</button>

    </div>
</form>
