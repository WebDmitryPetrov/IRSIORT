<?
/**
 * @var ActMan $Man
 */


?>
<td>
    <fieldset>
        <legend>Уровень тестирования</legend>
        <span class="value"><?php echo $Man->getLevel()->caption; ?></span>
    </fieldset>


    <fieldset>
        <legend>ФИО</legend>

        <span class="value"><?php echo $Man->surname_rus; ?></span><br>

        <span class="value"><?php echo $Man->name_rus; ?></span>
        <br>

        <span class="value"><?php echo $Man->surname_lat; ?></span>

        <br>
        <span class="value"><?php echo $Man->name_lat; ?></span>

        <? if (!empty($Man->duplicate->personal_data_changed)): ?>

            <div class="duplicate"><span class="duplicate_header">Выдан дубликат на имя:</span><br>
                <span class="value"><?php echo $Man->getSurname_rus(); ?></span><br>

                <span class="value"><?php echo $Man->getName_rus(); ?></span>
                <br>

                <span class="value"><?php echo $Man->getSurname_lat(); ?></span>

                <br>
                <span class="value"><?php echo $Man->getName_lat(); ?></span>
            </div>
        <? endif;?>

    </fieldset>

    <fieldset>
        <legend>Документ удостоверяющий личность</legend>

        <span class="value"><?php echo $Man->passport_name; ?></span><br>


        <span class="value"><?php echo $Man->passport_series; ?></span>

        <span class="value"><?php echo $Man->passport; ?></span></br>
        <span class="value"><?php echo $C->date($Man->passport_date); ?></span><br>
        <span class="value"><?php echo $Man->passport_department; ?></span> <br>
        <?php if ($Man->getFilePassport()): ?>
            <a href="<?php echo $Man->getFilePassport()->getDownloadUrl() ?>"
               target="_blank"
               class="btn  btn-small btn-success"><span
                    class="custom-icon-download"></span>Скачать</a>
        <?php endif; ?>

        <?php if (!empty($Man->duplicate->personal_data_changed) && $Man->getDuplicateFilePassport()
        ): ?>
            <a href="<?php echo $Man->getDuplicateFilePassport()->getDownloadUrl() ?>"
               target="_blank"
               class="btn  btn-small btn-success"><span
                    class="custom-icon-download"></span>Скачать по новому ФИО</a>
        <?php endif; ?>
    </fieldset>

</td>
<td>
    <table>
        <tr>
            <td style="border:0">
                <fieldset>
                    <legend>Страна (гражданство)</legend>
                    <span class="value"><?php echo $Man->getCountry(); ?></span>
                </fieldset>
            </td>

        </tr>
        <tr>
            <td style="border:0">
                <fieldset>
                    <legend>Дата теста</legend>
                    <span class="value"><?php echo $C->date($Man->testing_date); ?></span>
                </fieldset>
            </td>
        </tr>
        <tr>
            <td style="border:0">&nbsp;</td>
        </tr>
        <tr>
            <td style="border:0">
                <fieldset>
                    <legend>Рождение</legend>
                    <span class="value"><?php echo $C->date($Man->birth_date); ?></span>
                    <span class="value"><?php echo $Man->birth_place; ?></span>

                </fieldset>
            </td>
        </tr>
        <tr>
            <td style="border:0">
                <fieldset>
                    <legend>Миграционная карта</legend>
                    <span class="value"><?php echo $Man->migration_card_series; ?></span>
                    <span class="value"><?php echo $Man->migration_card_number; ?></span>
                </fieldset>
            </td>
        </tr>
    </table>
</td>