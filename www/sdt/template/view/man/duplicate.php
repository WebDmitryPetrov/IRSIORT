<?
/** @var ActMan $man */
?>
<style>

    .modal-body {
        max-height: 450px;
    }
	
	.modal input
	{
	width: 450px;
	}
	.modal
	{
	width: 700px;
	margin-left: -350px;
	}
</style>
<h1>Выдача дубликата сертификата</h1>
<table class="table table-bordered">
    <tr>
        <th>Фамилия</th>
        <td><?= $man->surname_rus ?></td>
    </tr>
    <tr>
        <th>Имя</th>
        <td><?= $man->name_rus ?></td>
    </tr>
    <tr>
        <th>Фамилия латиницей</th>
        <td><?= $man->surname_lat ?></td>
    </tr>
    <tr>
        <th>Имя латиницей</th>
        <td><?= $man->name_lat ?></td>
    </tr>
    <tr>
        <th>Гражданство</th>
        <td><?= $man->getCountry() ?></td>
    </tr>
    <tr>
        <th>Дата рождения</th>
        <td><?= $C->date($man->birth_date) ?></td>
    </tr>
    <tr>
        <th>Место рождения</th>
        <td><?= $man->birth_place ?></td>
    </tr>
    <tr>
        <th>Документ</th>
        <td><?= $man->passport_name ?>
            <?= $man->passport_series ?>/<?= $man->passport ?>
            от <?= $C->date($man->passport_date) ?> <?= $man->passport_department ?></td>
    </tr>
    <tr>
        <th>Миграционная карта</th>
        <td><?= $man->migration_card_series ?>/<?= $man->migration_card_number ?></td>
    </tr>
    <tr>
        <th>Уровень тестирования</th>
        <td><?= $man->getLevel()->caption ?></td>
    </tr>
    <tr>
        <th>Сертификат</th>
        <td><?
            $res = array();
            if ($man->document_nomer) {
                $res[] = $man->document_nomer;
            }
            if ($man->blank_number) {
                $res[] = $man->blank_number;
            }

            echo implode(' / ', $res);

            ?>
            <? if ($man->blank_date != '0000-00-00' && !is_null($man->blank_date)): ?>
                от
                <?= $C->date($man->blank_date); ?>
            <? endif ?>
        </td>
    </tr>
    <? if ($duplicates): ?>
        <tr class="warning">
            <td colspan="2">Выданные дубликаты</td>
        </tr>
        <? foreach ($duplicates as $dup):
            /** @var CertificateDuplicate $dup */
            ?>
            <tr class="info">
                <td>Дубликат от</td>
                <td><?= $C->date($dup->certificate_issue_date); ?></td>
            </tr>
            <tr>
                <td>Номер бланка сертификата</td>
                <td><?= $dup->certificate_number ?> </td>
            </tr>
            <tr>
                <td>Заявление</td>
                <td><a href="<?= $dup->getRequestFile()->getDownloadURL() ?>">Скачать</a></td>
            </tr>
            <? if ($dup->personal_data_changed): ?>
            <tr>
                <th>Фамилия</th>
                <td><?= $dup->surname_rus ?></td>
            </tr>
            <tr>
                <th>Имя</th>
                <td><?= $dup->name_rus ?></td>
            </tr>
            <tr>
                <th>Фамилия латиницей</th>
                <td><?= $dup->surname_lat ?></td>
            </tr>
            <tr>
                <th>Имя латиницей</th>
                <td><?= $dup->name_lat ?></td>
            </tr>
            <tr>
                <td>Паспорт</td>
                <td><a href="<?= $dup->getPassportFile()->getDownloadURL() ?>">Скачать</a></td>
            </tr>
        <? endif ?>
        <? endforeach ?>
    <? endif ?>


</table>
<? if ($man->getAct() && ($man->getAct()->state == ACT::STATE_ARCHIVE)
    || ($C->userHasRole(Roles::ROLE_CENTER_EDITOR) && $man->getBlank_number())): ?>
    <? $certCount = CertificateReservedList::countActiveByType($man->getAct()->test_level_type_id);
    if (!$certCount):?>
        <div class="hero-unit">
            Нет доступных сертификатов. Обратитесь к администору для добавления
        </div>
    <? else: ?>


        <button
            class="btn btn-primary js-without">Выдать дубликат без изменений
        </button>
        <button class="btn btn-warning js-with">Выдать дубликат с изменением в личных данных</button>
        <script>
            $(function () {
                $('.js-without').on('click', function () {
                    var modal = $('.js-without-modal');
                    modal.modal();
                });

                $('.js-with').on('click', function () {
                    var modal = $('.js-with-modal');
                    modal.modal();
                });
            });
        </script>

        <div class="js-without-modal modal hide fade">
            <form method="post" action="./?action=man_issue_duplicate" class="form-horizontal"
                  enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3>Выдать дубликат без изменений</h3>
                </div>
                <div class="modal-body">
                    <div class="control-group">
                        <label class="control-label" for="txt_email">Файл заявления:</label>

                        <div class="controls">
                            <input class="input-large" id="txt_email" name="request" type="file"
                                >

                            <p class="help-block"></p>
                        </div>
                    </div>

                    <?  if ((empty($man->blank_date) || $man->blank_date == '0000-00-00')
                        && $man->isCertificate()
                    ): ?>
                        <div class="control-group">
                            <label class="control-label" for="blank_date">Дата выдачи первого сертификата:</label>

                            <div class="controls">
                                <input class="input-prepend date datepicker"
                                       id="blank_date" name="blank_date" type="text" placeholder="Дата выдачи" readonly="readonly">

                                <p class="help-block"></p>
                            </div>
                        </div>
                    <? endif; ?>



                    <input type="hidden" name="id" value="<?= $man->id ?>">
                </div>

                <div class="modal-footer">
                    <a class="btn" data-dismiss="modal">Отменить</a>
                    <button type="submit" class="btn btn-primary">Подтвердить</button>
                </div>
            </form>
        </div>

        <div class="js-with-modal modal hide fade">
            <form method="post" action="./?action=man_issue_duplicate" class="form-horizontal"
                  enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3>Выдать дубликат без изменений</h3>
                </div>
                <div class="modal-body">
                    <div class="control-group">
                        <label class="control-label" for="surname_rus">Фамилия:</label>

                        <div class="controls">
                            <input class="input-large" value="<?= htmlspecialchars($man->getSurname_rus()) ?>"
                                   id="surname_rus" name="surname_rus" type="text">

                            <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="name_rus">Имя:</label>

                        <div class="controls">
                            <input class="input-large" value="<?= htmlspecialchars($man->getName_rus()) ?>"
                                   id="name_rus"
                                   name="name_rus" type="text">

                            <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="surname_lat">Фамилия латиницей:</label>

                        <div class="controls">
                            <input class="input-large" value="<?= htmlspecialchars($man->getSurname_lat()) ?>"
                                   id="surname_lat" name="surname_lat" type="text">

                            <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="name_lat">Имя латиницей:</label>

                        <div class="controls">
                            <input class="input-large" value="<?= htmlspecialchars($man->getName_lat()) ?>"
                                   id="name_lat"
                                   name="name_lat" type="text">

                            <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="passport">Файл паспорта:</label>

                        <div class="controls">
                            <input class="input-large" id="passport" name="passport" type="file">

                            <p class="help-block"></p>
                        </div>
                    </div>


                    <div class="control-group">
                        <label class="control-label" for="txt_email">Файл заявления:</label>

                        <div class="controls">
                            <input class="input-large" id="txt_email" name="request" type="file">

                            <p class="help-block"></p>
                        </div>
                    </div>


                    <?  if ( (empty($man->blank_date) || $man->blank_date == '0000-00-00')
                        && $man->isCertificate()
                    ): ?>
                        <div class="control-group">
                            <label class="control-label" for="blank_date">Дата выдачи первого сертификата:</label>

                            <div class="controls">
                                <input class="input-prepend date datepicker"
                                       id="blank_date" name="blank_date" type="text" placeholder="Дата выдачи" readonly="readonly">

                                <p class="help-block"></p>
                            </div>
                        </div>
                    <? endif; ?>


                    <input type="hidden" name="id" value="<?= $man->id ?>">
                    <input type="hidden" name="personal_changed" value="1">
                </div>

                <div class="modal-footer">
                    <a class="btn" data-dismiss="modal">Отменить</a>
                    <button type="submit" class="btn btn-primary">Подтвердить</button>
                </div>
            </form>
        </div>
    <? endif ?>
<? endif ?>



<? $signings = ActSignings::get4Certificate();
$signingsVidacha = ActSignings::get4VidachaCert();

CertificateDuplicate::checkForDuplicates($man);

?>


<?php if ($man->getBlank_number()
    && $man->blank_date
    && $man->blank_date != '0000-00-00' && $man->isCertificate()
): ?>
    <div class="btn-group">
        <a class="btn btn-info btn-small dropdown-toggle" data-toggle="dropdown"
           href="">Сертификат <span class="caret"></span></a>
        <ul class="dropdown-menu">
            <?php foreach ($signings as $sign): ?>
                <li><a target="_blank"
                       href="index.php?action=print_certificate&type=<?= $sign->id ?>&id=<?php echo $man->id; ?>"><?= $sign->caption ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div></div>
    <div class="btn-group">
        <a class="btn btn-info btn-small dropdown-toggle" data-toggle="dropdown"
           href="">Приложение <span class="caret"></span></a>
        <ul class="dropdown-menu">
            <?php foreach ($signingsVidacha as $sign): ?>
                <li><a target="_blank"
                       href="index.php?action=act_man_print_pril_cert&type=<?= $sign->id ?>&id=<?php echo $man->id; ?>"><?= $sign->caption ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div></div>



<?php endif; ?>


<div class="btn-group">
    <a
        class="btn btn-danger  dropdown-toggle  btn-b lock" data-toggle="dropdown"
        href="#">Печать ведомости выдачи
        сертификатов <span class="caret"></span></a>
    <ul class="dropdown-menu">
        <?php foreach ($signingsVidacha as $sign): ?>
            <li><a target="_blank"
                   href="index.php?action=act_vidacha_cert_duplicate&id=<?php echo $man->id; ?>&type=<?= $sign->id ?>"><?= $sign->caption ?></a>
            </li>
        <?php endforeach; ?>
        <ul>
</div>
<div></div>
<div class="btn-group">
    <a
        class="btn btn-danger  btn-blo ck dropdown-toggle" data-toggle="dropdown"
        href="#">Печать реестра выдачи сертификатов <span class="caret"></span></a>
    <ul class="dropdown-menu">
        <?php foreach ($signingsVidacha as $sign): ?>
            <li><a target="_blank"
                   href="index.php?action=act_vidacha_reestr_duplicate&id=<?php echo $man->id; ?>&type=<?= $sign->id ?>"><?= $sign->caption ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

