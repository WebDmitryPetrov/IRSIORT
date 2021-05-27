<?php

/** @var DublAct $Act */

$signings = ActSignings::get4Certificate();
$signingsVidacha = ActSignings::get4VidachaCert();
?>
<h3>Ввод номеров документов и печать</h3>
<h4 style="color: brown"><?= $type ?></h4>
<!--<h4>
    Дата тестирования:
    <?php /*echo $C->date($Act->testing_date); */ ?>
</h4>-->
<h4>
    Локальный центр:
    <?php echo $Act->getUniversity(); ?>
</h4>
<h4>
    Договор:
    <?php echo $Act->getUniversityDogovor(); ?>
</h4>

<? /* if ($C->userHasRole(Roles::ROLE_CENTER_PRINT)): ?>
    <a href="/sdt/index.php?action=scan_blank_upload&id=<?php echo $Act->id ?>" class="btn btn-info">Ввести номера бланков сертификатов</a>
<?endif  */
?>
<h2 style="color:red">Внимание! При печати сертификата установите размер бумаги 210*158, ориентация - альбомная,
    масштабирование - "подогнать"</h2>



<div class="">


    <? if ( $C->userHasRole(Roles::ROLE_CENTER_PRINT)): ?>
        <div class="btn-group" style="display:inline-block">
            <a class="btn btn-info btn-block dropdown-toggle print-all" data-toggle="dropdown"
               href="#">Печать сертификатов по всему акту<span class="caret"></span></a>
            <ul class="dropdown-menu">
                <?php foreach ($signings as $sign): ?>
                    <li><a target="_blank"
                           href="dubl.php?action=dubl_print_certificates&type=<?= $sign->id ?>&id=<?= $Act->id ?>&tt=<?= $Act->test_level_type_id ?>"><?= $sign->caption ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <? /*<div class="btn-group" style="display:inline-block">
            <a class="btn btn-info btn-block dropdown-toggle print-all"   data-toggle="dropdown"
               href="#">Печать сертификатов по всему акту (экзамен)<span class="caret"></span></a>
            <ul class="dropdown-menu">
                <?php foreach ($signings as $sign): ?>
                    <li><a target="_blank"
                           href="dubl.php?action=dubl_print_certificates&type=<?= $sign->id ?>&id=<?= $Act->id ?>&tt=2"><?= $sign->caption ?></a>
                    </li>
                <?php endforeach; ?>
                <ul>
        </div>*/ ?>

        <div class="btn-group" style=";display:inline-block">
            <a class="btn btn-info btn-block  dropdown-toggle print-all" data-toggle="dropdown"
               href="#">Печать приложений к сертификатам по всему акту <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <?php foreach ($signingsVidacha as $sign): ?>
                    <li><a target="_blank"
                           href="dubl.php?action=dubl_act_man_print_pril_certs&type=<?= $sign->id ?>&id=<?= $Act->id ?>"><?= $sign->caption ?></a>
                    </li>
                <?php endforeach; ?>
                <ul>
        </div>


        <div></div>

        <div class="btn-group" style="display:inline-block">
            <a class="btn btn-warning
                     dropdown-toggle  btn-block print-all" data-toggle="dropdown"
               href="#">Печать акта<span class="caret"></span></a>
            <ul class="dropdown-menu">
                <?php foreach (ActSignings::get4Act() as $sign): ?>
                    <li><a target="_blank"
                           href="dubl.php?action=dubl_act_print&id=<?php echo $Act->id; ?>&type=<?= $sign->id ?>"><?= $sign->caption ?></a>
                    </li>
                <?php endforeach; ?>
                <ul>
        </div>




        <? if ($Act->allow_summary_table()):
            if ($Act->summary_table_id):
                ?>


                <div style="display:inline-block" class="btn-group">
                    <a target="_blank" class=" btn btn-color-black invoice btn-danger  btn-block print-all"
                       href="dubl.php?action=dubl_act_summary_table&id=<?= $Act->id; ?>" onclick="">Просмотреть/напечатать
                        Сводный протокол</a>
                </div>

                <?

            endif;
        endif; ?>


        <div></div>
        <? if ($Act->isShowInArchive()): ?>
        <div class="btn-group" style="display:inline-block">
            <a
                    class="btn btn-primary  dropdown-toggle  btn-block print-all" data-toggle="dropdown"
                    href="#">Печать ведомости выдачи
                сертификатов <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <?php foreach ($signingsVidacha as $sign): ?>
                    <li><a target="_blank"
                           href="dubl.php?action=dubl_act_vidacha_cert&id=<?php echo $Act->id; ?>&type=<?= $sign->id ?>"><?= $sign->caption ?></a>
                    </li>
                <?php endforeach; ?>
                <ul>
        </div>

        <!--    <div></div>-->
        <div class="btn-group" style="display:inline-block">
            <a
                    class="btn btn-primary  dropdown-toggle  btn-block print-all" data-toggle="dropdown"
                    href="#">Печать реестра выдачи сертификатов <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <?php foreach ($signingsVidacha as $sign): ?>
                    <li><a target="_blank"
                           href="dubl.php?action=dubl_act_vidacha_reestr&id=<?php echo $Act->id; ?>&type=<?= $sign->id ?>"><?= $sign->caption ?></a>
                    </li>
                <?php endforeach; ?>
                <ul>
        </div>
        <? endif?>

        <div></div>
        <div class="btn-group" style="display:inline-block">
            <a data-id="<?php echo $Act->id; ?>"
               class="btn invoice btn-warning  btn-block print-all" target="_blank"
               href="dubl.php?action=dubl_print_invoice&id=<?php echo $Act->id; ?>">Печать счета</a>
        </div>
        <div></div>

    <? endif; ?>

</div>
<table
        class="table table-bordered  table-striped table-condensed">
    <thead>
    <tr>
        <td><strong>Фамилия</strong><br/> русскими <br/> латинскими</td>
        <td><strong>Имя</strong><br/> русскими<br/> латинскими</td>
        <? if ($Act->isShowInArchive()): ?><td><strong>Паспорт</strong></td><?endif?>
        <td><strong>Уровень тестирования</strong></td>
        <!--        <td><strong>Тип документа</strong></td>-->
        <td><strong>
                <!--                <? /* if ($Act->test_level_type_id == 2): */ ?>
                    Номер документа<br>
                --><? /* endif */ ?>
                Номер бланка<br>Дата печати</strong></td>
        <td>Печать

        </td>
    </tr>

    </thead>
    <tbody>
    <?php

    foreach ($Act->getPeople() as $Man):
        $old_man = ActMan::getByID($Man->old_man_id);
        $old_man = CertificateDuplicate::checkForDuplicates($old_man);
//echo $Man->old_man_id;
        //echo (var_dump($old_man));
        ?>
        <tr
                class="js-man-row  <?php if ($old_man->is_retry): ?> man-retry <?php endif ?>">

            <td><span class="js-surname-rus"><?php echo $Man->getSurnameRus(); ?> </span>
                <br> <span><?php echo $Man->getSurnameLat(); ?>
					</span>
            </td>
            <td><span class="js-name-rus"><?php echo $Man->getNameRus(); ?> </span> <br>
                <span><?php echo $Man->getNameLat(); ?>
					</span>
            </td>

            <? if ($Act->isShowInArchive()): ?><td><span><?php echo $old_man->passport; ?></span></td><?endif?>
            <td><?php echo $old_man->getTest()->getLevel()->caption; ?>
            </td>

            <td>

                <? $blank_type = BlankType::getBlankType($Act->test_level_type_id, $Man->getCertNum());
                if (empty($blank_type->default) && !empty($blank_type->name)) echo '<span style="color:red">' . $blank_type->name . '</span><br>';
                ?>


                <input type="text" name="user[<?php echo $Man->id; ?>][blank_number]"
                       class="input-large js-blank"
                       value="<?= $Man->getCertNum() ?>" placeholder="Номер бланка" readonly="readonly"><br>


            </td>
            <td>

                <?php if ($C->userHasRole(Roles::ROLE_CENTER_PRINT)
                    && $Man->dubl_cert_id

                ): ?>
                    <div class="btn-group">
                        <a class="btn btn-info btn-block btn-small dropdown-toggle" data-toggle="dropdown"
                           href="#">Сертификат <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <?php foreach ($signings as $sign): ?>
                                <li><a target="_blank"
                                       href="dubl.php?action=dubl_print_certificate&type=<?= $sign->id ?>&id=<?php echo $Man->old_man_id; ?>"><?= $sign->caption ?></a>
                                </li>
                            <?php endforeach; ?>
                            <ul>
                    </div>
                    <div></div>
                    <div class="btn-group">
                        <a
                                class="btn btn-info btn-small  btn-block  dropdown-toggle" data-toggle="dropdown"
                                href="#">Приложение <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <?php foreach ($signingsVidacha as $sign): ?>
                                <li><a target="_blank"
                                       href="dubl.php?action=dubl_act_man_print_pril_cert&id=<?php echo $Man->old_man_id; ?>&type=<?= $sign->id ?>"><?= $sign->caption ?></a>
                                </li>
                            <?php endforeach; ?>
                            <ul>
                    </div>


                <?php endif; ?>
                <div></div>

            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

