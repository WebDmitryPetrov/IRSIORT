<?php
/** @var University $University */
?>

<?if (!empty($Act->id)):?>
<a href="/sdt/dubl.php?action=dubl&type=<?=$Act->test_level_type_id;?>" class="btn btn-info">Назад к запросам</a>
    <h1>Редактировать тестовую сессию</h1>
<? else:?>

    <h1>Новая  тестовая сессия</h1>
<?endif;?>

<form method="post" action="<?php echo $_SERVER['REQUEST_URI']?>"
      class="form-horizontal">
    <?php if (!empty($Legend)): ?>
    <legend>
        <?php echo $Legend; ?>
    </legend>
    <?php endif; ?>
<!--    <div class="control-group">
        <label class="control-label" for="number">Номер</label>

        <div class="controls">

            <input class="input-xxlarge" type="text" name="number"
                   id="number"
                   value="<?php /* echo $Act->number; */?>">


        </div>
    </div>-->


    <div class="control-group">
        <label class="control-label" for="university_dogovor_id">Договор ВУЗа
        </label>

        <div class="controls">
            <select id="university_dogovor_id" name="university_dogovor_id"
                    class="input-xxlarge">
                <?php  foreach ($University->getDogovorsByType($test_level_type) as $dogovor): ?>

                    <option
                        <?php if ($Act->center_dogovor_id==$dogovor->id):?>
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
        <label class="control-label" for="official">Должностное лицо, утверждающее акт</label>
        <div class="controls">
            <?php

            if ($officialChoose = $University->getSigning(CenterSigning::TYPE_APPROVE)):
                ?>
                <select class="input-xxlarge" name="official" id="official">
                    <?php foreach ($officialChoose as $sign):
                        /** @var CenterSigning $sign */
                        ?>
                        <option

                            <?=$Act->official==$sign->getPrint()?'selected=selected':'' ?>
                                value="<?= htmlspecialchars($sign->getPrint()) ?>"><?= $sign->getPrint() ?></option>
                        <?php
                    endforeach; ?>
                </select>
                <?php
            else:
                ?>

                <input class="input-xxlarge" type="text" name="official"
                       placeholder="Директор/Ректор Сидоров И.П."
                       id="official"
                       value="<?php echo $Act->official; ?>">

            <? endif ?>
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
