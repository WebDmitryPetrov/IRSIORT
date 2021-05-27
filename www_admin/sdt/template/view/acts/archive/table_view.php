<?php
/** @var Act $Act */
?>

<h3>Сводная таблица. Дата тестирования: <?php echo $C->date($Act->testing_date);?></h3>
<h4>Университет: <?php echo $Act->getUniversity();?></h4>
<h4>Договор: <?php echo $Act->getUniversityDogovor();?></h4>
<a href="/sdt/index.php?action=act_received_view&id=<?php echo $Act->id ?>" class="btn btn-info">Вернуться в акт</a>

<?php if (!empty($Legend)): ?>
<legend>
    <?php echo $Legend; ?>
</legend>
    <?php endif;?>
<fieldset>
    <legend>Тесторы</legend>
    <div   style="margin-bottom: 2px"><?php echo $Act->tester1?></div>
    <div><?php echo $Act->tester2?></div>
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

                            <span class="value"><?php echo $Man->surname_rus; ?></span><br>

                              <span class="value"><?php echo $Man->name_rus; ?></span>
                            <br>

                            <span class="value"><?php echo $Man->surname_lat; ?></span>

                            <br>
                              <span class="value"><?php echo $Man->name_lat; ?></span>
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <fieldset>
                            <legend>Документ удостоверяющий личность</legend>

                             <span class="value"><?php echo $Man->passport_name; ?></span><br>


                                 <span class="value"><?php echo $Man->passport_series; ?></span>

                                <span class="value"><?php echo $Man->passport; ?></span></br>
                            <span class="value"><?php echo $C->date($Man->passport_date);  ?></span><br>
                            <span class="value"><?php echo $Man->passport_department; ?></span> <br>
                            <?php if ($Man->getFilePassport()): ?>
                            <a href="<?php echo $Man->getFilePassport()->getDownloadUrl()?>" target="_blank"
                               class="btn  btn-small btn-success"><span class=" custom-icon-download icon-white"></span>Скачать</a>
                            <?php endif;?>
                        </fieldset>
                    </td>
                </tr>
            </table>
        </td>
        <td colspan="2">
            <table>
                <tr>  <td>
                    <span class="value"><?php echo $Man->getCountry();?></span>
                    </td>
                    <td>
                         <span class="value"><?php  echo $C->date($Man->testing_date);?></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <fieldset>
                            <legend>Рождение</legend>
                           <span class="value"><?php echo $C->date($Man->birth_date);      ?></span>
                             <span class="value"><?php echo $Man->birth_place; ?></span>

                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <fieldset>
                            <legend>Миграционная карта</legend>
                            <span class="value"><?php echo $Man->migration_card_series; ?></span>
                           <span class="value"><?php echo $Man->migration_card_number; ?></span>
                        </fieldset>
                    </td>
                </tr>
            </table>
        </td>
        <td><span class="value"><?php echo $Man->reading; ?></span><br>
            <span class="percent"><?php echo $Man->reading_percent; ?>%</span>
        </td>
        <td><span class="value"><?php echo $Man->writing; ?></span><br>
            <span class="percent"><?php echo $Man->writing_percent; ?>%</span>
        </td>
        <td><span class="value"><?php echo $Man->grammar; ?></span><br>
            <span class="percent"><?php echo $Man->grammar_percent; ?>%</span>
        </td>
        <td><span class="value"><?php echo $Man->listening; ?></span><br>
            <span class="percent"><?php echo $Man->listening_percent; ?>%</span>
        </td>
        <td><span class="value"><?php echo $Man->speaking; ?></span><br>
            <span class="percent"><?php echo $Man->speaking_percent; ?>%</span>
        </td>
        <td><span class="value"><?php echo $Man->total; ?></span><br>
            <span class="percent"><?php echo $Man->total_percent; ?>%</span>
        </td>
        <td><span class="<?php echo $Man->document;?> "><?php echo $Man->document == "certificate" ? 'Сертификат' : 'Справка' ?></span></td>
    </tr>
        <?php endforeach;?>
    </tbody>
    <?php endforeach;?>
</table>



