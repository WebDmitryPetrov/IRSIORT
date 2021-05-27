<?php
/** @var Act $Act */
?>

<h3>Сводная таблица. Дата тестирования: <?php echo $Act->testing_date;?></h3>
<h4>Университет: <?php echo $Act->getUniversity();?></h4>
<h4>Договор: <?php echo $Act->getUniversityDogovor();?></h4>
<a href="/sdt/index.php?action=act_second_view&id=<?php echo $Act->id ?>" class="btn btn-info">Вернуться в акт</a>
<form method="post" action="<?php echo $_SERVER['REQUEST_URI']?>"
      class="form-horizontal marks">
<?php if (!empty($Legend)): ?>
<legend>
    <?php echo $Legend; ?>
</legend>
    <?php endif;?>
<div class="bg_highlight">
    Для сохранения введенных в ячейки таблицы данных (перед загрузкой сканов!) нажмите клавишу "ENTER" или кнопку "Сохранить" внизу таблицы!
</div>
<fieldset>
    <legend>Тесторы</legend>
    <div   style="margin-bottom: 2px"><input name="tester1" value="<?php echo $Act->tester1?>"  style="width:400px" placeholder="ФИО тестора"
            ></div>
    <div><input name="tester2" value="<?php echo $Act->tester2?>" placeholder="ФИО тестора" style="width:400px"></div>
</fieldset>
<table
        class="table table-bordered  table-condensed summary_table">
    <?php foreach ($Act->getLevels() as $ActTest):
    $level = $ActTest->getLevel();
    ?>
    <thead>
    <tr>
        <td colspan="11"><h4>Уровень тестирования "<strong><?php echo $level->caption ?></strong>"</h4></td>
    </tr>
    <tr>
        <td rowspan="2" colspan="2"> &nbsp;</td>
        <td rowspan="2"><strong>Страна</strong></td>
        <td rowspan="2"><strong>Дата теста</strong></td>
        <td colspan="6" class="center"><strong>Результаты</strong> (баллы /
            %)
        </td>
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
        <?php foreach ($ActTest->getPeople() as $Man): ?>
    <tr class="summary <?php if ($Man->id % 2): ?> info <?php endif ?>">

        <td colspan="2">
            <table>
                <tr>
                    <td colspan="2">
                        <fieldset>
                            <legend>ФИО</legend>

                            <input type="text"
                                   name="user[<?php echo $Man->id; ?>][surname_rus]"
                                   class="input-large" placeholder="Фамилия по-русски"
                                   value="<?php echo $Man->surname_rus; ?>"><br>

                            <input type="text"
                                   name="user[<?php echo $Man->id; ?>][name_rus]" class="input-large"
                                   placeholder="Имя по-русски" value="<?php echo $Man->name_rus; ?>">
                            <br>

                            <input type="text" name="user[<?php echo $Man->id; ?>][surname_lat]"
                                   class="input-large" placeholder="Фамилия латиницей"
                                   value="<?php echo $Man->surname_lat; ?>">

                            <br>
                            <input type="text" name="user[<?php echo $Man->id; ?>][name_lat]"
                                   class="input-large" placeholder="Имя латиницей"
                                   value="<?php echo $Man->name_lat; ?>">
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <fieldset>
                            <legend>Документ удостоверяющий личность</legend>

                            <input placeholder="Наименование" type="text"
                                   name="user[<?php echo $Man->id; ?>][passport_name]"
                                   class="input-large" value="<?php echo $Man->passport_name; ?>">

                            <div>
                                <input placeholder="Серия " type="text"
                                       name="user[<?php echo $Man->id; ?>][passport_series]"
                                       class="input-mini" value="<?php echo $Man->passport_series; ?>">

                                <div class="input-append">
                                    <input type="text" name="user[<?php echo $Man->id; ?>][passport]"
                                           class="input-small" value="<?php echo $Man->passport; ?>"
                                           placeholder="Номер"><span class="add-on"><a href="#"
                                                                                       class="passport_upload custom-icon-upload"
                                                                                       data-id="<?php echo $Man->id; ?>"></a> </span>

                                </div>

                            </div>
                            <div class="input-prepend date datepicker "
                                 data-date="<?php if ($Man->passport_date != '0000-00-00') echo $C->date($Man->passport_date);  ?>"
                                >
                                <span class="add-on"><i class="icon-th"></i> </span> <input
                                    placeholder="Дата выдачи"
                                    class="input-small"
                                    name="user[<?php echo $Man->id; ?>][passport_date]"
                                    id="passport_date" readonly="readonly" size="16" type="text"
                                    value="<?php if ($Man->passport_date != '0000-00-00') echo $C->date($Man->passport_date);  ?>">
                            </div>
                            <input placeholder="Орган выдавший " type="text"
                                   name="user[<?php echo $Man->id; ?>][passport_department]"
                                   class="input-large" value="<?php echo $Man->passport_department; ?>">
                            <?php if ($Man->getFilePassport()): ?>
                            <a href="<?php echo $Man->getFilePassport()->getDownloadUrl()?>"   target="_blank"
                               class="btn  btn-small btn-success"><span class=" custom-icon-download icon-white"></span>Скачать</a>
                            <?php endif;?>
                        </fieldset>
                    </td>
                </tr>
            </table>
        </td>
        <td colspan="2">
            <table>
                <tr>
                    <td><select name="user[<?php echo $Man->id; ?>][country_id]"
                                class="input-small">
                        <?php foreach ($Countries as $country): ?>
                        <option value="<?php echo $country->id;?>"
                            <?php if ($country->id == $Man->country_id) echo 'selected=selected';?>>
                            <?php echo $country->name;?>
                        </option>

                        <?php endforeach;?>
                    </select>
                    </td>
                    <td>
                        <div class="input-prepend date datepicker "
                             data-date="<?php if ($Man->testing_date != '0000-00-00') echo $C->date($Man->testing_date);
                             else echo $C->date($Act->testing_date); ?>"
                           >
                            <span class="add-on"><i class="icon-th"></i> </span> <input
                                class="input-small"
                                name="user[<?php echo $Man->id; ?>][testing_date]"
                                id="testing_date" readonly="readonly" size="16" type="text"
                                value="<?php if ($Man->testing_date != '0000-00-00') echo $C->date($Man->testing_date);
                                else echo $C->date($Act->testing_date); ?>">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <fieldset>
                            <legend>Рождение</legend>
                            <div class="input-prepend date datepicker "
                                 data-date="<?php if ($Man->birth_date != '0000-00-00') echo $C->date($Man->birth_date);      ?>"
                                >
                                <span class="add-on"><i class="icon-th"></i> </span> <input
                                    class="input-small"
                                    name="user[<?php echo $Man->id; ?>][birth_date]"
                                    placeholder="Дата"
                                    id="birth_date" readonly="readonly" size="16" type="text"
                                    value="<?php if ($Man->birth_date != '0000-00-00') echo $C->date($Man->birth_date); ?>">
                            </div>
                            <input placeholder="Место" type="text" name="user[<?php echo $Man->id; ?>][birth_place]"
                                   class="input-large" value="<?php echo $C->date($Man->birth_place); ?>">

                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <fieldset>
                            <legend>Миграционная карта</legend>
                            <input placeholder="Серия" type="text"
                                   name="user[<?php echo $Man->id; ?>][migration_card_series]"
                                   class="input-mini" value="<?php echo $Man->migration_card_series; ?>">
                            <input placeholder="Номер" type="text"
                                   name="user[<?php echo $Man->id; ?>][migration_card_number]"
                                   class="input-small" value="<?php echo $Man->migration_card_number; ?>">
                        </fieldset>
                    </td>
                </tr>
            </table>
        </td>
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
        <td><span class="<?php echo $Man->document;?> ">
                        <?php echo $Man->document=="certificate"?'Сертификат':'Справка' ?>
                        </span></td>
    </tr>
        <?php endforeach;?>
    </tbody>
    <?php endforeach;?>
</table>


<div class="form-actions">
    <button class="btn btn-success" type="submit">Сохранить</button>

</div>
</form>


<div class="modal hide fade" id="passport_upload" tabindex="-1"
     role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form method="post" enctype="multipart/form-data"
          action="index.php?action=man_passport_upload" class="form-inline">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                    aria-hidden="true">&times;</button>
            <h3 id="myModalLabel">Загрузить отсканированный паспорт и миграционную карту в одном файле</h3>
        </div>
        <div class="modal-body">

              <span class="help-block">Перед загрузкой файла сохраните введенные данные!  <br>
(Для этого щелкните "Закрыть" и далее внизу "Сохранить")
</span>
            <legend>Выберите файл</legend>

            <input type="hidden" value="" name="man_id" class="man_id"> <input
                type="file" name="file" class="input-xlarge">

        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
            <button class="btn btn-primary save" type="submit">Загрузить</button>
        </div>
    </form>
</div>
<script>
    $(function () {

        $('.passport_upload').on('click', function (e) {
            e.preventDefault();
            //alert($(this).data('id'));
            $('#passport_upload').find('.man_id').val($(this).data('id'));
            $('#passport_upload').modal();
        });
    });

</script>