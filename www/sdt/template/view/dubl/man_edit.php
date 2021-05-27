<?php
/**
 * Created by JetBrains PhpStorm.
 * User: LWR
 * Date: 20.07.15
 * Time: 18:22
 * To change this template use File | Settings | File Templates.
 */

/** @var DublActMan $man */

?>
<form method="post" action="" class="form-horizontal"
      enctype="multipart/form-data">
<div class="modal-body">

                    <div class="control-group">
                        <label class="control-label" for="surname_rus">Фамилия:</label>

                        <div class="controls">
                            <input class="input-large  only-rus "  value="<?= htmlspecialchars($man->getSurnameRus()) ?>"
                                   id="surname_rus" name="surname_rus" type="text">

                            <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="name_rus">Имя:</label>

                        <div class="controls">
                            <input class="input-large  only-rus " value="<?= htmlspecialchars($man->getNameRus()) ?>"
                                   id="name_rus"
                                   name="name_rus" type="text">

                            <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="surname_lat">Фамилия латиницей:</label>

                        <div class="controls">
                            <input class="input-large  only-lat " value="<?= htmlspecialchars($man->getSurnameLat()) ?>"
                                   id="surname_lat" name="surname_lat" type="text">

                            <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="name_lat">Имя латиницей:</label>

                        <div class="controls">
                            <input class="input-large only-lat" value="<?= htmlspecialchars($man->getNameLat()) ?>"
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


                    <!--<div class="control-group">
                        <label class="control-label" for="txt_email">Файл заявления:</label>

                        <div class="controls">
                            <input class="input-large" id="txt_email" name="request" type="file">

                            <p class="help-block"></p>
                        </div>
                    </div>-->


<!--                    <?/*  if ( (empty($man->blank_date) || $man->blank_date == '0000-00-00')
    && $man->isCertificate()
): */?>
    <div class="control-group">
        <label class="control-label" for="blank_date">Дата выдачи первого сертификата:</label>

        <div class="controls">
            <input class="input-prepend date datepicker"
                   id="blank_date" name="blank_date" type="text" placeholder="Дата выдачи" readonly="readonly">

            <p class="help-block"></p>
        </div>
    </div>
--><?/* endif; */?>


<input type="hidden" name="id" value="<?= $man->id ?>">
<input type="hidden" name="personal_changed" value="1">
    <input type="submit" value="Сохранить">
</div>
    </form>