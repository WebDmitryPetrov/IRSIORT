<?php
$signings = ActSignings::get4Certificate();
$signingsVidacha = ActSignings::get4VidachaCert();
?>
<h3>Ввод номеров документов и печать</h3>
<h4 style="color: brown"><?=$type?></h4>
<h4>
    Дата тестирования:
    <?php echo $C->date($Act->testing_date); ?>
</h4>
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
<h2 style="color:red">Внимание! При печати сертификата установите размер бумаги 210*158, ориентация - альбомная, масштабирование - "подогнать"</h2>
<!--<form method="post" action="--><?php //echo $_SERVER['REQUEST_URI'] ?><!--"-->
<!--      class="form-horizontal marks">-->

<legend>Заполните номера сертификатов</legend>

<?php
$needFeelNumbers = 0;
$needFeelBlanks = 0;
$printCert = 0;
$printNote = 0;
foreach ($Act->getPeople() as $man) {

    if ($man->needNumber()) {
        $needFeelNumbers++;
    }
    if (!$man->needNumber()) {
        if ($man->isNote()) {
            $printNote++;
        }
        if ($man->isCertificate() && !$man->needBlank()) {
//        if (!($Act->test_level_type_id == 2 && $man->blank_date == '0000-00-00'))
            $printCert++;
        }
    }
    /*
        elseif ($man->isCertificate()) {
            $printCert++;
        } elseif ($man->isNote()) {
            $printNote++;
        }*/

    if ($man->needBlank()) {
        $needFeelBlanks++;
    }

}
?>
<div class="">
    <? if ($needFeelNumbers && ($C->userHasRole(Roles::ROLE_CENTER_PRINT) || $C->userHasRole(Roles::ROLE_CERTIFICATE_MANAGER))): ?>
        <a href="./?action=generate_numbers&id=<?= $Act->id ?>" class="btn btn-success"
           onclick="return confirm('Вы уверены?');">Вставить
            регистрационные
            номера сертификатов и справок</a>
    <? endif; ?>
    <? if ($needFeelBlanks && ($C->userHasRole(Roles::ROLE_CENTER_PRINT) || $C->userHasRole(Roles::ROLE_CERTIFICATE_MANAGER))): ?>
        <a href="./?action=act_insert_blanks&id=<?= $Act->id ?>" onclick="return confirm('ВНИМАНИЕ! ВЫ ВВОДИТЕ НОМЕРА РЕАЛЬНЫХ БЛАНКОВ СЕРТИФИКАТОВ!\n'+
'Изменить, при необходимости, номера можно будет только у отдельных людей, сделав номер недействительным, с утратой соответствующего бланка!');"
           class="btn btn-warning">Вставить номера
            бланков
            сертификатов
            <? $certCount = CertificateReservedList::countActiveByType($Act->test_level_type_id);
            if ($needFeelBlanks > $certCount) {
                ?>
                <span class="label label-important">Осталось: <?= $certCount ?></span>
            <? } ?>
        </a>

    <? endif; ?>

    <? if ($printCert && $C->userHasRole(Roles::ROLE_CENTER_PRINT)): ?>
        <div class="btn-group" style="display:inline-block">
            <a class="btn btn-info btn-block dropdown-toggle print-all"   data-toggle="dropdown"
               href="#">Печать сертификатов по всему акту <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <?php foreach ($signings as $sign): ?>
                    <li><a target="_blank"
                           href="index.php?action=print_certificates&type=<?= $sign->id ?>&id=<?= $Act->id ?>"><?= $sign->caption ?></a>
                    </li>
                <?php endforeach; ?>
                <ul>
        </div>

        <div class="btn-group" style=";display:inline-block">
            <a class="btn btn-info btn-block  dropdown-toggle print-all" data-toggle="dropdown"
               href="#">Печать приложений к сертификатам по всему акту <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <?php foreach ($signingsVidacha as $sign): ?>
                    <li><a target="_blank"
                           href="index.php?action=act_man_print_pril_certs&type=<?= $sign->id ?>&id=<?= $Act->id ?>"><?= $sign->caption ?></a>
                    </li>
                <?php endforeach; ?>
                <ul>
        </div>


        <? /* <a href="./?action=print_certificates&id=<?= $Act->id ?>"
           class="btn btn-warning">Печать сертификатов по всему акту
        </a>
        <a href="./?action=act_man_print_pril_certs&id=<?= $Act->id ?>"
           class="btn btn-warning">Печать приложений к сертификатам по всему акту
        </a>*/ ?>

    <? endif; ?>
    <? if ($printNote && $C->userHasRole(Roles::ROLE_CENTER_PRINT)): ?>

        <div class="btn-group" style="display:inline-block">
            <a class="btn btn-info btn-block dropdown-toggle print-all" data-toggle="dropdown"
               href="#">Печать справок по всему акту <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <?php foreach ($signingsVidacha as $sign): ?>
                    <li><a target="_blank"
                           href="index.php?action=act_rkis&type=<?= $sign->id ?>&id=<?= $Act->id ?>"><?= $sign->caption ?></a>
                    </li>
                <?php endforeach; ?>
                <ul>
        </div>

        <? /* <a href="./?action=act_rkis&id=<?= $Act->id ?>"
           class="btn btn-warning">Печать справок по всему акту
        </a>*/ ?>

    <? endif; ?>
    <?if ($Act->summary_table_id && $Act->allow_summary_table()):?>
    <div class="btn-group" style="display:inline-block">
        <a class="btn btn-danger  btn-block btn-color-black print-all" target="_blank"
           href="index.php?action=act_summary_table&id=<?php echo $Act->id; ?>">Просмотреть/напечатать Сводный протокол</a>
        </div>
    <?endif;?>

</div>
<table
    class="table table-bordered  table-striped table-condensed">
    <thead>
    <tr>
        <td><strong>Фамилия</strong><br/> русскими <br/> латинскими</td>
        <td><strong>Имя</strong><br/> русскими<br/> латинскими</td>
        <td><strong>Паспорт</strong></td>
        <td><strong>Уровень тестирования</strong></td>
        <td><strong>Тип документа</strong></td>
        <td><strong>
                <? if ($Act->test_level_type_id == 2): ?>
                    Номер документа<br>
                <? endif ?>
                Номер бланка<br>Дата печати</strong></td>
        <td>Печать

        </td>
    </tr>

    </thead>
    <tbody>
    <?php foreach ($Act->getPeople() as $Man): ?>
        <tr
            class="js-man-row  <?php if ($Man->is_retry): ?> man-retry <?php endif ?>">

            <td><span class="js-surname-rus"><?php echo $Man->surname_rus; ?> </span>
                <br> <span><?php echo $Man->surname_lat; ?>
					</span>
            </td>
            <td><span class="js-name-rus"><?php echo $Man->name_rus; ?> </span> <br> <span><?php echo $Man->name_lat; ?>
					</span>
            </td>

            <td><span><?php echo $Man->passport; ?></span></td>
            <td><?php echo $Man->getTest()->getLevel()->caption; ?>
            </td>
            <td>
                        <span class="<?php echo $Man->document; ?> ">
                        <?php echo $Man->document == "certificate" ? 'Сертификат' : 'Справка' ?>
                        </span>
            </td>
            <td>
                <? if ($C->userHasRole(Roles::ROLE_CENTER_PRINT) || $C->userHasRole(Roles::ROLE_CERTIFICATE_MANAGER)): ?>
                    <? if ($Man->document == ActMan::DOCUMENT_CERTIFICATE) { ?>
                        <? if ($Act->test_level_type_id == 2): ?>
                            <input type="text" name="user[<?php echo $Man->id; ?>][document_nomer]"
                                   class="input-large"
                                   value="<?= $Man->document_nomer ?>" disabled="disabled"
                                   placeholder="Номер документа">
                            <? if (Errors::get(Errors::CERTIFICATE_DOCUMENT_NUMBER, $Man->id, 1)): ?>
                                <span class="text-error error"><?= Errors::get(
                                        Errors::CERTIFICATE_DOCUMENT_NUMBER,
                                        $Man->id,
                                        1
                                    ) ?></span><br>
                            <? endif; ?>
                            <br>
                        <? endif ?>

                        <? $blank_type=BlankType::getBlankType($Act->test_level_type_id,$Man->blank_number);
                        if (empty($blank_type->default) && !empty($blank_type->name)) echo '<span style="color:red">'.$blank_type->name.'</span>';
                        ?>


                        <input type="text" name="user[<?php echo $Man->id; ?>][blank_number]"
                               class="input-large js-blank"
                               value="<?= $Man->blank_number ?>" placeholder="Номер бланка" disabled="disabled">


                        <br>
                        <? if (Errors::get(Errors::CERTIFICATE_BLANK_NUMBER, $Man->id, 1)): ?>
                            <span class="text-error error"><?= Errors::get(
                                    Errors::CERTIFICATE_BLANK_NUMBER,
                                    $Man->id,
                                    1
                                ) ?></span><br>
                        <? endif; ?>

                        <input placeholder="Дата печати" type="text" disabled="disabled"
                               name="user[<?php echo $Man->id; ?>][blank_date]" class="input-large datepicker"
                               value="<?php
                               if (!is_null($Man->blank_date) && $Man->blank_date != '0000-00-00') {
                                   echo date('d.m.Y', strtotime($Man->blank_date));
                               }
                               ?>"><br>

                    <? } else { ?>
                        <input
                            type="text" name="user[<?php echo $Man->id; ?>][blank_number]"
                            class="input-large  js-blank" disabled="disabled"
                            value="<?= $Man->blank_number ?>"
                            placeholder="Номер справки"
                            > <br>
                        <? if (Errors::get(Errors::NOTE_BLANK_NUMBER, $Man->id, 1)): ?>
                            <span class="text-error error"><?= Errors::get(
                                    Errors::NOTE_BLANK_NUMBER,
                                    $Man->id,
                                    1
                                ) ?></span><br>
                        <? endif; ?>

                    <? } ?>
                <? endif; ?>
            </td>
            <td>
                <? if ($Man->is_anull()): ?>
                    <p class="text-error"><strong>Аннулирован</strong><br><?= $C->date($Man->getAnull()->date_annul)?><br><strong>Причина:</strong><br><?= $Man->getAnull()->reason ?></p>
                <? endif ?>

                <?php if ($C->userHasRole(Roles::ROLE_CENTER_PRINT)
                    && $Man->blank_number
                    && (
                        ($Act->test_level_type_id == 2 && $Man->document_nomer)
                        ||
                        $Act->test_level_type_id != 2
                    )
                    && $Man->blank_date
//                    && $Man->blank_date != '0000-00-00'
                    && $Man->document == "certificate"
                ): ?>
                    <div class="btn-group">
                        <a class="btn btn-info btn-block btn-small dropdown-toggle" data-toggle="dropdown"
                           href="#">Сертификат <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <?php foreach ($signings as $sign): ?>
                                <li><a target="_blank"
                                       href="index.php?action=print_certificate&type=<?= $sign->id ?>&id=<?php echo $Man->id; ?>"><?= $sign->caption ?></a>
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
                                       href="index.php?action=act_man_print_pril_cert&id=<?php echo $Man->id; ?>&type=<?= $sign->id ?>"><?= $sign->caption ?></a>
                                </li>
                            <?php endforeach; ?>
                            <ul>
                    </div>


                <?php endif; ?>
                <div></div>
                <?php if ($C->userHasRole(Roles::ROLE_ACT_INVALID) && $Man->isCertificate() && !$Man->needBlank()): ?>
                    <? /*href="./?action=man_issue_duplicate&id=<?= $Man->id ?>"*/ ?>
                    <button data-id="<?= $Man->id ?>"
                            class="btn btn-danger  btn-block  btn-small js-man-invalid-button">Сделать
                        недействительным
                    </button>
                <? endif; ?>


                <?php if ($C->userHasRole(
                        Roles::ROLE_CENTER_PRINT
                    ) && $Man->document != 'certificate' && !empty($Man->blank_number)
                ): ?>

                    <div class="btn-group">
                        <a
                            class="btn btn-info  btn-block  btn-small  dropdown-toggle" data-toggle="dropdown"
                            href="#">РКИ <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <?php foreach ($signingsVidacha as $sign): ?>
                                <li><a target="_blank"
                                       href="index.php?action=act_rki&id=<?php echo $Man->id; ?>&type=<?= $sign->id ?>"><?= $sign->caption ?></a>
                                </li>
                            <?php endforeach; ?>
                            <ul>
                    </div>


                <?php endif; ?>
                <div><?php if ($Man->getSoprovodPassport()): ?>
                        <a href="<?php echo $Man->getSoprovodPassport()->getDownloadUrl() ?>"
                           class="btn btn-success  btn-block  btn-small"><span class="custom-icon-download"></span></a>
                    <?php endif; ?></div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>


<!--<div class="form-actions">-->
<!--    <!--        <button class="btn btn-success" type="submit">Сохранить</button>-->
<!--</div>-->
<!--</form>-->


<script>
    $(function () {
        $('.js-man-invalid-button').on('click', function (E) {
            E.preventDefault();
            var $this = $(this);
            var manId = $this.data('id');
            var row = $this.closest('.js-man-row');
            var blank = row.find('.js-blank').val();
            var name = row.find('.js-surname-rus').text() + ' ' + row.find('.js-name-rus').text();


            var modalMan = $('.js-man-invalid-modal');
            modalMan.find('.js-blank').text(blank);
            modalMan.find('.js-name').text(name);
            modalMan.find('.js-reason').val('');
            modalMan.find('.js-accept').off('click');
            modalMan.find('.js-accept').on('click', function (E) {
                var reason = $('.js-reason').val();
                if (reason.length < 10) {
                    alert('Причина должна быть длинее 10 символов');
                    return;
                }

                $.ajax({
                    type: 'POST',
                    url: './index.php?action=man_blank_invalid',
                    data: {
                        id: manId,
                        reason: reason
                    }
                }).always(function () {
                        location.reload();
                    }
                );

//                $(this).off(E);
            });

            modalMan.modal();


        })
        ;
    })
    ;
</script>

<div class="js-man-invalid-modal modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Сделать недействительным бланк сертификата</h3>
    </div>
    <div class="modal-body">
        <p>Вы пытаетесь сделать недействительным бланк сертификата <span class="js-blank"></span>
            у <span class="js-name"></span></p>

        <p><label style=" padding-right: 15px;">Укажите причину:<br>
                <textarea style="width: 100%" class="js-reason"
                          placeholder="Например: испорчен бланк"></textarea></label></p>
    </div>
    <div class="modal-footer">
        <a class="btn" data-dismiss="modal">Отменить</a>
        <a class="btn btn-primary js-accept">Подтвердить</a>
    </div>
</div>


